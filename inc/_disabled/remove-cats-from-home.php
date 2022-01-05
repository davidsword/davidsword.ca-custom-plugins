<?php

// Remove select categories from Home/blog

// need to add `dsca_exempt_cats_from_home` manually, string like `-1,-2,-3`.
add_filter(
	'pre_get_posts',
	function ( $query ) {
		if ( $query->is_home )
			$query->set( 'cat', get_option( 'dsca_exempt_cats_from_home' ) );
		return $query;
	}
);
