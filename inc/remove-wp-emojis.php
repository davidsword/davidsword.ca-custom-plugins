<?php

// Remove WP Emojis, use System instead

/**
 * Remove WordPress emojicon, rely on users systems instead.
 *
 * It's 2022 & my audience is of the technical-up-to-date crowd.
 */
add_action(
	'init',
	function() {
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

		add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
		add_filter( 'emoji_svg_url', '__return_false' );
	}
);
