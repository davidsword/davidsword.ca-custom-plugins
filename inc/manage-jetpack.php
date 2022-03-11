<?php

// Jetpack Module Manager

// Quickly see active modules in Admin (as there's no wpcli on Pressable).
add_action(
	'admin_notices',
	function () {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- not a form
		if ( ! ( isset( $_GET['jp_show_active_modules'] ) && current_user_can('manage_options') ) )
			return;
		?>
		<div class="notice notice-info">
			<p><?php echo 'Active JP Modules: ' . esc_html( implode( ',', get_option( 'jetpack_active_modules' ) ) ); ?></p>
		</div>
		<?php
	}
);

// Force modules off that aren't toggle'able in admin UI.
add_filter( 'option_jetpack_active_modules', function ( $modules ) {
	// @TODO make this a dynamic option.
	$disabled_modules = array(
		'stats',                 // not nessisary.
		'woocommerce-analytics', // unused.
		'tiled-gallery',         // unused.
		'json-api',              // not nessisary.
		'monitor',				 // not nessisary.
	);

	// locally images are from prod, w. photon the img requests dont hit localhost
	// need to disable photon when deving locally
	// @see https://css-tricks.com/develop-locally-use-images-production/
	if ( dsca_is_develop() )
		$disabled_modules[] = 'photon';

	foreach ( $disabled_modules as $module_slug ) {
		$found = array_search( $module_slug, $modules );
		if ( false !== $found )
			unset( $modules[ $found ] );
	}
	return $modules;
} );

// a 1720x image is 3mb from the cdn without this.
add_filter( 'jetpack_photon_pre_args', function ( $args ) {
	if ( isset( $args['w'] ) && $args['w'] > 1600 )
		$args['w'] = 1600;
	$args['quality'] = 85; // @TODO only reduce when $args reveal its an image >1000px
	$args['strip']   = 'all';
	return $args;
} );

// shh notifications
// @see https://github.com/Automattic/jetpack/blob/c46ee346c9cda88796a53a69240f2a1033967632/projects/packages/jitm/src/class-jitm.php#L86-L97
add_filter( 'jetpack_just_in_time_msgs', '__return_false', 99 );
