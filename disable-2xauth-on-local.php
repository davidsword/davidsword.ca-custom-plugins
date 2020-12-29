<?php

/**
 * Plugin Name: DSCA - remove 2x auth on local
 * Plugin URI: https://github.com/davidsword/davidsword.ca-custom-plugins
 * Description: remove 2x auth on mobile
 * Version: 0.0.1
 * Author: David Sword
 * Author URI: https://davidsword.ca/
 * License: GNU GENERAL PUBLIC LICENSE
 */

add_action( 'init', function() {
	$evn   = strstr( get_home_url(), '192.168.' ) ? 'dev' : 'prod'; //@todo detection should be global 
		if ( $evn === 'dev' ) {
			global $google_authenticator;
			remove_action( 'init', [ $google_authenticator, 'init' ] );
		}
 }, 9);
