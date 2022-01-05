<?php

// Add Image Credit

add_filter( 'post_thumbnail_html', function( $html, $postid, $post_thumbnail_id, $size ) {
	$attachment = wp_get_attachment_caption( $post_thumbnail_id );
	$caption = '';
	$correct_context = ! is_admin() && ! is_feed();
	if ( $correct_context && $attachment && ! empty( $attachment ) ) {
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
