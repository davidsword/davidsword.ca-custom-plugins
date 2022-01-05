<?php

// Change Site Icon To Gravatar

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

	// no need to stare at my ugly mug whilst developing.
	if ( dsca_is_develop() )
		return get_home_url().'/wp-content/local-icon.png';

	return 'https://www.gravatar.com/avatar/' . md5( get_option( 'admin_email' ) ) . '?s=' . intval( $size*2 ); // for retina!
}
