<?php

/**
 * I don't use or see any purpose for any of these tempaltes.
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

/**
 * Disable REST api for non-logged in, nothing's using this AFAIK.
 *
 * This may be adding some security by preventing access to authors list, etc
 * @see https://developer.wordpress.org/rest-api/frequently-asked-questions/#can-i-disable-the-rest-api
 */
add_filter( 'rest_authentication_errors', function( $result ) {
	// If a previous authentication check was applied, pass that result along without modification.
	if ( true === $result || is_wp_error( $result ) ) {
		return $result;
	}
	if ( ! is_user_logged_in() ) {
		return new WP_Error(
			'rest_not_logged_in',
			__( 'You are not currently logged in.' ),
			array( 'status' => 401 )
		);
	}
	return $result;
});
