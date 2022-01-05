<?php
// Add enviroment name to Admin Bar

add_action( 'wp_before_admin_bar_render', function () {
	global $wp_admin_bar;

	$color    = dsca_is_develop() ? 'orange' : '#81e481'; // green.
	$evn_name = dsca_is_develop() ? 'develop' : 'prod';

	$wp_admin_bar->add_node(
		[
			'id'     => 'env',
			'title'  => sprintf(
				'EVN: <strong style="font-weight:bold;color: %s;">%s</strong>',
				esc_attr( $color ),
				esc_html( strtoupper( $evn_name ) )
			),
			'parent' => 'top-secondary',
		]
	);
} );
