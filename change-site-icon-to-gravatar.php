<?php

/**
 * Plugin Name: Change Site Icon To Gravatar
 * Plugin URI: https://github.com/davidsword/davidsword.ca-custom-plugins
 * Description: Change Site Icon To Gravatar
 * Version: 0.0.1
 * Author: David Sword
 * Author URI: https://davidsword.ca/
 * License: GNU GENERAL PUBLIC LICENSE
 */

/**
 * If no site_icon - use the gravatar instead.
 */
add_filter(
	'get_site_icon_url',
	function( $url, $size ) {
		return empty( $url ) ? dsca_get_gravatar_from_admin_email( $size ) : $url;
	},
	999,
	2
);

/**
 * If no custom_logo - use the gravatar instead.
 */
add_filter(
	'get_custom_logo',
	function ( $html ) {
		if ( empty( get_theme_mod( 'custom_logo' ) ) ) {
			$html = sprintf( '<a href="%1$s" class="custom-logo-link" rel="home" itemprop="url"><img src="%2$s" class="custom-logo" /></a>',
				esc_url( home_url( '/' ) ),
				esc_url( dsca_get_gravatar_from_admin_email() )
			);
		}
		return $html;
	},
	999
);


function dsca_get_gravatar_from_admin_email( $size = 512 ) {
	return 'https://www.gravatar.com/avatar/' . md5( get_option( 'admin_email' ) ) . '?s=' . intval( $size*2 );
}
