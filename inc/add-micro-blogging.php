<?php

/**
 * Tweak WordPress to be more Micro-Blogg'y (inspired by micro.blog)
 *
 * A micro blog post has the following criteria:
 *
 * - no post title
 * - post format of `aside`
 * - in category `micro-blog`
 *
 * The post format is for the theme, the category is for organization and query filtering.
 *
 * Anything else: images, tags, other cats, content length, blocked used, featured image, etc, are free game.
 *
 * Not using a custom post type as CPTs aren't supported via the WordPress mobile app.
 */

 const MB_POST_SLUG_WORD_LENGTH = 3;

// Register `aside`
add_action('after_setup_theme', function() {
	add_theme_support( 'post-formats', [ 'aside' ] );
});

// All new posts without a post title should implicitly be microblog Posts
add_filter( 'wp_insert_post_data', function($data, $postarr ) { // maybe change to save_post

	$the_post_id    = $postarr['ID'];
	$title_is_blank = empty( $data['post_title'] );

	if ( $title_is_blank )
		set_post_format( $the_post_id, 'aside' );

	// @TODO set category

	// @TODO maybe modify slug
	// wp_unique_post_slug( string $slug, int $post_ID, string $post_status, string $post_type, int $post_parent )
	// $desired_slug = sanitize_title( truncate_words( $data['post_content'], 4 ) );
	// $data['post_name'] = wp_unique_post_slug( $desired_slug, $the_post_id, $data['post_status'], $data['post_type'], $data['post_parent'] );

	return $data;

}, 11, 2 );

function dsca_microblog_save_post( $post_ID, $post, $update ) {
	// allow 'publish', 'draft', 'future'
	if ($post->post_type != 'post' || $post->post_status == 'auto-draft')
		return;

	// only change slug when the post is created (both dates are equal)
	if ($post->post_date_gmt != $post->post_modified_gmt)
		return;

	$new_slug = create_micro_blog_post_slug( $post );

	if ($new_slug == $post->post_name)
		return; // already set

	// unhook this function to prevent infinite looping
	remove_action( 'save_post', 'dsca_microblog_save_post', 10, 3 );
	// update the post slug (WP handles unique post slug)
	wp_update_post( array(
		'ID' => $post_ID,
		'post_name' => $new_slug
	));
	// re-hook this function
	add_action( 'save_post', 'dsca_microblog_save_post', 10, 3 );
}
add_action( 'save_post', 'dsca_microblog_save_post', 10, 3 );

function create_micro_blog_post_slug( $post ) {
	return sanitize_title( truncate_words( strip_tags( $post->post_content ), MB_POST_SLUG_WORD_LENGTH ) );

}
// add_action('init', function(){
// 	echo sanitize_title( truncate_words( 'testing a string of text with words here 123', 4 ) );
// 	die;
// });
function truncate_words($text, $limit) {
	if (str_word_count($text, 0) > $limit) {
		$words = str_word_count($text, 2);
		$pos   = array_keys($words);
		$text  = substr($text, 0, $pos[$limit]);
	}
	return $text;
}

// Remove microblog posts from main category pages
add_filter( 'pre_get_post2s', function( $query ) {

	if ( $query->is_main_query() ) {
		//echo "<pre>";    var_dump($query); die;
	}

	$query_is_micro_blog = '';

	if ( !$query_is_micro_blog ) {
		// remove aside posts ()
	}
});

// @TODO RSS modifications

// in admin, add the title for managing
add_filter('the_title', function($title, $id){
	if ( ! is_admin() )
		return $title;

	if ( ! is_admin() && dsca_is_microblog_post( $id ) )
		return false;

	// @TODO && is edit screen
	if ( dsca_is_microblog_post( $id ) )
		return '&nbsp;';//'microblog: ' . substr($title, 0, 25);

	return $title;
}, 10, 2 );

// everything about microbloging should be short, including the date:
// opinionated override of wp's date format
add_filter( 'get_the_date', function( $date, $format, $id ) {

	if ( ! is_admin() && dsca_is_microblog_post( $id ) )
		return date('M n');

	return $date;
}, 10, 3);

// remove "micro-blog" tag from list of tags, this is more for organization than display
add_filter( 'wp_get_object_terms', function( $terms, $object_ids, $taxonomies, $args ) {
	if ( is_admin() )
		return $terms;

		foreach ( $terms as $k => $term )
		if ( $term->slug === 'micro-blog' )
			unset($terms[$k]);

	return $terms;
}, 10, 4 );

// @TODO change post author if from restricted accounts

// @TODO cronjob check if micro blog post today, ifnot, email, 8pm

//@TODO permalinks for microblog posts should be `/yyyy/mm/dd/first-few-words/`, the alt "/first-few-words/" is just weird

// Update permalinks
// Pressable doesnt allow wpcli cmds, need to trigger one time actions via querystring
add_action( 'init', function(){
	if ( ! is_admin() || ! current_user_can('manage_options') || ! isset( $_GET['dsca_update_micro_blog_permalinks'] ))
		return;

	$args = array(
		'post_type' => 'post',
		'tax_query' => array(
			'relation' => 'OR',
			array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => array( 'micro-blog' ),
			),
			array(
				'taxonomy' => 'post_format',
				'field'    => 'slug',
				'terms'    => array( 'post-format-aside' ),
			),
		),
	);
	$micro_blog_posts = new WP_Query( $args );

	// unhook this function to prevent infinite looping
	remove_action( 'save_post', 'dsca_microblog_save_post', 10, 3 );

	foreach ( $micro_blog_posts->posts as $mpost ) {
		// echo $mpost->post_name.' -> '.create_micro_blog_post_slug( $mpost ).'<br />';
		// update the post slug (WP handles unique post slug)
		$new_slug = create_micro_blog_post_slug( $mpost );
		if ( $post->post_name != $new_slug) {
			wp_update_post( array(
				'ID' => $mpost->ID,
				'post_name' => $new_slug
			));
		}
	}

	// re-hook this function
	add_action( 'save_post', 'dsca_microblog_save_post', 10, 3 );

	die;
});
