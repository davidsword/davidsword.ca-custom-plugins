<?php

/**
 * Plugin Name: DSCA - Add Image Credit
 * Plugin URI: https://github.com/davidsword/davidsword.ca-custom-plugins
 * Description: Add Image Credit of Featured Image to Featured Image via a media items `Caption`
 * Version: 0.0.1
 * Author: David Sword
 * Author URI: https://davidsword.ca/
 * License: GNU GENERAL PUBLIC LICENSE
 */

add_filter( 'post_thumbnail_html', function( $html, $postid, $post_thumbnail_id, $size ) {
	$attachment = wp_get_attachment_caption( $post_thumbnail_id );
	$caption = '';
	if ( $attachment && ! empty( $attachment ) ) {
		$caption = sprintf(
			'<div style="font-size: 0.6em;
			line-height: 1em;
			position: absolute;
			right: 7px;
			margin-top: 7px;
			opacity: 0.5;
			font-style: italic;">%s</div>',
			esc_html( $attachment )
		);
	}
	return $html.$caption;
}, 10, 4 );
