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

	return $data;

}, 11, 2 );

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
