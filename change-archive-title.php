<?php

/**
 * Plugin Name: Change Archive Title
 * Plugin URI: https://github.com/davidsword/davidsword.ca-custom-plugins
 * Description: Change Archive Title
 * Version: 0.0.1
 * Author: David Sword
 * Author URI: https://davidsword.ca/
 * License: GNU GENERAL PUBLIC LICENSE
 */

// remove "category:" prefix, it goes without saying.
add_filter(
	'get_the_archive_title',
	function ( $title ) {
		if ( is_category() ) {
			$title = str_replace( 'Category:', '', $title );
		}
		return $title;
	}
);
