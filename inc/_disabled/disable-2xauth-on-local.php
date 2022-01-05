<?php

// remove 2x auth on local

add_action( 'init', function() {
		if ( dsca_is_develop() ) {
			global $google_authenticator;
			remove_action( 'init', [ $google_authenticator, 'init' ] );
		}
 }, 9);
