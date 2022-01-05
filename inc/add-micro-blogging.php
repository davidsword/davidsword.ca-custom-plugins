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
 * The post format is for the theme, the category is for organization/querying.
 * Anything else: images, tags, other cats, content length, blocked used, featured
 * image, etc, is free game. Not using a custom post type as CPTs aren't supported
 * via the WordPress mobile app.
 */

// @TODO RSS modifications
// @TODO change post author if from restricted accounts
// @TODO cronjob check if micro blog post today, ifnot, email, 8pm

const MB_POST_SLUG_WORD_LENGTH = 3;
const MB_CAT_NAME 				= 'micro-blog';
const MB_POST_FORMAT 			= 'aside';
const MB_ONE_TIME_FIX_SLUG		= 'dsca_update_micro_blog_permalinks';
const MB_AUTHOR_ID 				= 1;

/**
 * Register post format
 */
add_action('after_setup_theme', function() {
	add_theme_support( 'post-formats', [ MB_POST_FORMAT ] );
});

/**
 * Detect and modify Microblog posts on post save
 *
 * @param int $post_ID
 * @param WP_Post $post
 * @param bool Whether this is an existing post being updated
 * @return void
 */
function dsca_microblog_save_post( $post_ID, $post, $update ) {
	// allow 'publish', 'draft', 'future'
	if ($post->post_type != 'post' || $post->post_status == 'auto-draft')
		return;

	// only change slug when the post is created (both dates are equal)
	if ($post->post_date_gmt != $post->post_modified_gmt)
		return;

	$title_is_blank = empty( $post->post_title );

	if ( $title_is_blank ) {

		$has_aside_post_format = has_post_format( MB_POST_FORMAT, $post );
		$has_micro_blog_term = has_term( MB_CAT_NAME, 'category', $post_ID );

		if ( ! $has_aside_post_format )
			set_post_format( $post->ID, MB_POST_FORMAT );
		if ( ! $has_micro_blog_term )
			wp_add_object_terms( $post_ID, MB_CAT_NAME, 'category' );
	}

	if ( ! dsca_is_microblog_post( $post->ID ) )
		return;

	$new_slug = create_micro_blog_post_slug( $post );

	if ($new_slug == $post->post_name)
		return; // already set

	// unhook this function to prevent infinite looping
	remove_action( 'save_post', 'dsca_microblog_save_post', 10, 3 );
	// update the post slug (WP handles unique post slug)
	wp_update_post( array(
		'ID' => $post_ID,
		'post_name' => $new_slug,
		'author' => MB_AUTHOR_ID
	));

	// re-hook this function
	add_action( 'save_post', 'dsca_microblog_save_post', 10, 3 );
}
add_action( 'save_post', 'dsca_microblog_save_post', 10, 3 );

/**
 * Create micro blog post slug
 *
 * Without a title, WP will use the post ID, and worse, the post ID may have a `-2`.
 * Change to first x words of a micro post.
 *
 * @param WP_Post $post
 * @return string new slug
 */
function create_micro_blog_post_slug( $post ) {
	return sanitize_title( truncate_words( strip_tags( $post->post_content ), MB_POST_SLUG_WORD_LENGTH ) );

}

/**
 * Truncate string into first x words
 *
 * Be sure to truncate words instead of characters as may accidentally spell things in permalinks.
 *
 * @param string $text string of words to cut from
 * @param int $limit words to cut off at
 * @return string
 */
function truncate_words($text, $limit) {
	if (str_word_count($text, 0) > $limit) {
		$words = str_word_count($text, 2);
		$pos   = array_keys($words);
		$text  = substr($text, 0, $pos[$limit]);
	}
	return $text;
}

/**
 * Remove microblog posts from main category pages
 */
add_filter( 'pre_get_posts', function( $query ) {

	if ( $query->is_main_query() ) {
		// @TODO
	}

	$query_is_micro_blog = '';

	if ( !$query_is_micro_blog ) {
		//  @TODO remove from posts ()
	}
});

/**
 * Clean up wp-admin posts screen titles for cleaner managing (remove "(no title)")
 */
add_filter('the_title', function($title, $id){
	if ( ! is_admin() )
		return $title;

	if ( ! is_admin() && dsca_is_microblog_post( $id ) )
		return false;

	// @TODO check && is edit screen
	if ( dsca_is_microblog_post( $id ) )
		return get_post($id)->post_name; //@TODO maybe use slug here

	return $title;
}, 10, 2 );

/**
 * everything about microbloging should be short, including the date:
 * opinionated override of wp's date format
 */
add_filter( 'get_the_date', function( $date, $format, $id ) {

	if ( ! is_admin() && dsca_is_microblog_post( $id ) )
		return date('M n');

	return $date;
}, 10, 3);

/**
 * remove "micro-blog" tag from list of tags, this is more for organization than display
 */
add_filter( 'wp_get_object_terms', function( $terms, $object_ids, $taxonomies, $args ) {
	if ( is_admin() )
		return $terms;

		foreach ( $terms as $k => $term )
		if ( $term->slug === MB_CAT_NAME )
			unset($terms[$k]);

	return $terms;
}, 10, 4 );

/**
 * Update permalinks
 * Would do cli, but Pressable doesnt allow wpcli cmds
 * need to trigger one time actions via querystring
 */
add_action( 'init', function(){
	if ( ! is_admin() || ! current_user_can('manage_options') || ! isset( $_GET[MB_ONE_TIME_FIX_SLUG] ))
		return;

	$args = array(
		'post_type' => 'post',
		'tax_query' => array(
			'relation' => 'OR',
			array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => array( MB_CAT_NAME ),
			),
			array(
				'taxonomy' => 'post_format',
				'field'    => 'slug',
				'terms'    => array( 'post-format-'.MB_POST_FORMAT ),
			),
		),
		'posts_per_page' => '-1',
	);
	$micro_blog_posts = new WP_Query( $args );

	// unhook this function to prevent infinite looping
	remove_action( 'save_post', 'dsca_microblog_save_post', 10, 3 );

	foreach ( $micro_blog_posts->posts as $mpost ) {
		// update the post slug (WP handles unique post slug)
		$new_slug = create_micro_blog_post_slug( $mpost );
		if ( $post->post_name != $new_slug) {
			wp_update_post( array(
				'ID' => $mpost->ID,
				'post_name' => $new_slug
			));
			// @TODO set aside, and micro-blog term to ensure all fixed and meet the criteria
			// @TODO maybe null or at least look for the post_title not empty
		}
	}

	// re-hook this function
	add_action( 'save_post', 'dsca_microblog_save_post', 10, 3 );

});
