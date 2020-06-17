<?php

/**
 * Plugin Name: Full Post Thumbnail Size
 * Plugin URI: https://github.com/davidsword/davidsword.ca-custom-plugins
 * Description: 
 * Version: 0.0.1
 * Author: David Sword
 * Author URI: https://davidsword.ca/
 * License: GNU GENERAL PUBLIC LICENSE
 */

// see https://developer.wordpress.org/reference/functions/get_the_post_thumbnail/
// twenty15 theme has tiny header images, full is best for hidpi.
add_filter( 'post_thumbnail_size', function( $size ){
	return 'full';
} );
