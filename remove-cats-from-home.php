<?php

/**
 * Plugin Name: Remove select categories from Home/blog
 * Plugin URI: https://github.com/davidsword/davidsword.ca-custom-plugins
 * Description: Remove select categories from Home/blog
 * Version: 0.0.1
 * Author: David Sword
 * Author URI: https://davidsword.ca/
 * License: GNU GENERAL PUBLIC LICENSE
 */

// need to add `dsca_exempt_cats_from_home` manually, string like `-1,-2,-3`.
add_filter(
	'pre_get_posts',
	function ( $query ) {
		if ( $query->is_home )
			$query->set( 'cat', get_option( 'dsca_exempt_cats_from_home' ) );
		return $query;
	}
);
