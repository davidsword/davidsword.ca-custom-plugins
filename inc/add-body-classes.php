<?php

// category-art

add_filter( 'body_class', function( $classes  ) {
	if ( is_single() && has_term( 'art', 'category' ) )
		$classes[] = 'category-art'; //@TODO probably change this to single-art adn adjust css, but whatever.
	return $classes;
}, 10, 3 );
