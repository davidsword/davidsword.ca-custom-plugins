<?php
/**
 * Remove author from RSS feeds
 */
add_filter( 'the_author', function( $display_name ) {
	if ( dsca_is_microblog_post() && is_feed() )
		return '';
	return $display_name;
} );
