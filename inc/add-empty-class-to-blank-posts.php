<?php
// Add Empty Class to Blank Posts

add_filter( 'post_class', function( $classes, $class, $post_id ) {
	if ( ! is_admin() && empty( get_post( $post_id )->post_content ) )
		$classes[] = 'article-is-empty';
	return $classes;
}, 10, 3 );
