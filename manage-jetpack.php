<?php

/**
 * Plugin Name: davidsword.ca Jetpack Module Manager
 * Plugin URI: https://github.com/davidsword/davidsword.ca-custom-plugins
 * Description: davidsword.ca TwentyFifteen Theme Edits
 * Version: 0.0.1
 * Author: David Sword
 * Author URI: https://davidsword.ca/
 * License: GNU GENERAL PUBLIC LICENSE
 */

// Quickly see active modules in Admin (as there's no wpcli on Pressable).
add_action(
	'admin_notices',
	function () {
		if ( ! $_GET['jp_show_active_modules'] )
			return;
		?>
		<div class="notice notice-info">
			<p><?php echo 'Active JP Modules: ' . implode( ',', get_option( 'jetpack_active_modules' ) ); ?></p>
		</div>
		<?php
	}
);

// Force modules off that aren't toggle'able in admin UI.
add_filter( 'option_jetpack_active_modules', function ( $modules ) {
	// @TODO make this a dynamic option.
	$disabled_modules = array(
		'stats',                 // using Google Analytics.
		'woocommerce-analytics', // unused.
		'tiled-gallery',         // unused.
		'json-api',              // not nessisary.
	);
	foreach ( $disabled_modules as $module_slug ) {
		$found = array_search( $module_slug, $modules );
		if ( false !== $found )
			unset( $modules[ $found ] );
	}
	return $modules;
} );

// a 1720x image is 3mb from the cdn without this.
add_filter( 'jetpack_photon_pre_args', function ( $args ) {
	$args['quality'] = 80;
	$args['strip'] = 'all';
	return $args;
} );
