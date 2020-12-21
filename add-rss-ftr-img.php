<?php

/**
 * Plugin Name: DSCA - rss ftr img
 * Plugin URI: https://github.com/davidsword/davidsword.ca-custom-plugins
 * Description: add ftr img to rss
 * Version: 0.0.1
 * Author: David Sword
 * Author URI: https://davidsword.ca/
 * License: GNU GENERAL PUBLIC LICENSE
 */

add_filter('the_content_feed', function( $content ) {
	$ftrimg = get_the_post_thumbnail();
	return $ftrimg . $content;
});