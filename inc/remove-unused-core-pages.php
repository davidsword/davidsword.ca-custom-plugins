<?php

/**
 * I don't use any of this.
 */
function dsca_remove_unused_core_pages() {
	global $wp_query;
	// @see https://developer.wordpress.org/files/2014/10/wp-template-hierarchy.jpg
	if ( is_author() || is_attachment() || is_date() || is_tag() ) {
		// add referal for debuggin'
		wp_redirect(get_option('home').'?ref=dsca_remove_unused_core_pages', 301);
		exit;
	}
}
add_action( 'template_redirect', 'dsca_remove_unused_core_pages' );

