<?php

/**
 * Plugin Name: Change Site Icon To Gravitar
 * Plugin URI: https://github.com/davidsword/davidsword.ca-custom-plugins
 * Description: Change Site Icon To Gravitar
 * Version: 0.0.1
 * Author: David Sword
 * Author URI: https://davidsword.ca/
 * License: GNU GENERAL PUBLIC LICENSE
 */

/**
 * If no site icon - use the gravatar instead.
 */
add_filter(
	'get_site_icon_url',
	function( $url ) {
		return empty( $url ) ? 'https://www.gravatar.com/avatar/' . md5( get_option( 'admin_email' ) ) . '?s=512' : $url;
	},
	99,
	1
);
