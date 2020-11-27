<?php

/**
 * Plugin Name: DSCA - Add Evn to Admin Bar
 * Plugin URI: https://github.com/davidsword/davidsword.ca-custom-plugins
 * Description: Add Evn to Admin Bar
 * Version: 0.0.1
 * Author: David Sword
 * Author URI: https://davidsword.ca/
 * License: GNU GENERAL PUBLIC LICENSE
 */

add_action( 'wp_before_admin_bar_render', function () {
	global $wp_admin_bar;

	$evn   = strstr( get_home_url(), 'vvv.' ) ? 'local' : 'prod';
	$color = 'prod' === $evn ? '#81e481' : 'orange';

	$wp_admin_bar->add_node(
		[
			'id'     => 'env',
			'title'  => sprintf(
				'EVN: <strong style="font-weight:bold;color: %s;">%s</strong>',
				esc_attr( $color ),
				esc_html( strtoupper( $evn ) )
			),
			'parent' => 'top-secondary',
		]
	);
} );
