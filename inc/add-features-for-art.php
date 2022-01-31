<?php

// category-art
add_filter( 'body_class', function( $classes  ) {
	if ( is_single() && has_term( 'art', 'category' ) )
		$classes[] = 'category-art'; //@TODO probably change this to single-art adn adjust css, but whatever.
	return $classes;
}, 10, 3 );

add_action( 'pre_get_posts', function ( $query ) {
	if ( is_admin() || ! $query->is_main_query() )
		return;

	if ( is_category( 'art' ) ) {
		$query->set( 'posts_per_page', 99 ); // pagination here doesnt make much sense, so long as lazy load is working.
		return;
	}
}, 1 );
