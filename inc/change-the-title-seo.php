<?php

/**
 * Change the title for usability and SEO
 *
 * High level, should be simple:
 *   `{optional context}: {current object(s)} | {site name}`
 * with a few exceptions.
 */
add_filter( 'wp_title', function ( $title, $sep ) {
	global $page, $paged;

	$sep  		 = '|';
	$name 		 = esc_html( get_bloginfo( 'name', 'display' ) );
	$description = esc_html( get_bloginfo( 'description', 'display' ) );

	// RSS
	if ( is_feed() )
		return $name;

	if ( $description && ( is_home() || is_front_page() ) ) {
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$num = max( $paged, $page );
			return "Page: $num $sep $name";
		} else {
			return "$name $sep $description";
		}
	}

	if ( is_404() )
		return "404: Page not found $sep $name";

	if ( is_single() && function_exists( 'dsca_is_microblog_post' ) && dsca_is_microblog_post() )
		return $name;

	if ( is_single() || is_page() )
		return esc_html( get_the_title() )." $sep $name";

	if ( is_category() || is_tax() || is_archive() )
		return esc_html( single_cat_title( '' ) ) ." $sep $name";

	if ( is_search() )
		// get_search_query is pre-escaped
		return "Search: ".wp_trim_words( get_search_query(), 3 ) ." $sep $name";

	// media, date, author, etc blocked

	// fallthrough for whatever was missed
	return $title;

}, 10, 2 );
