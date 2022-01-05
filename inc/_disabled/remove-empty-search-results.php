<?php

// Remove Empty Search Results

/**
 * Ensure blank searches (`/?s=`) return no results.
 *
 * For whatever reason WP wants to return default WP_Query on a blank search.
 * Using `found_posts` hook is irelevant, it's just an avaliable hook after the query.
 *
 * @return string number of found posts.
 */
add_filter(
	'found_posts',
	function ( $found, $query ) {
		$empty_search = empty( $query->query_vars['s'] );
		if ( ! is_admin() && is_search() && $empty_search ) {
			$query->posts       = [];
			$query->found_posts = 0;
		}
		return $found;
	},
	10,
	3
);
