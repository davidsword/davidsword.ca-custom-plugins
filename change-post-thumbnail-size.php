<?php

/**
 * Plugin Name: Change Post Thumbnail Size to Full
 * Plugin URI: https://github.com/davidsword/davidsword.ca-custom-plugins
 * Description: Change Post Thumbnail Size to Full
 * Version: 0.0.1
 * Author: David Sword
 * Author URI: https://davidsword.ca/
 * License: GNU GENERAL PUBLIC LICENSE
 */

add_action( 'after_setup_theme', function () {
	add_image_size( 'featured-image', 1742, 1400 );
} );


// see https://developer.wordpress.org/reference/functions/get_the_post_thumbnail/
// twenty15 theme has tiny header images, full is best for hidpi.
add_filter( 'post_thumbnail_size', function( $size ) {
	return 'featured-image';
} );
