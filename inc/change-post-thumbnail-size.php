<?php

// Change Post Thumbnail Size to Full

add_action( 'after_setup_theme', function () {
	add_image_size( 'featured-image', 871, 800 ); // specific size for TwentyFiffteen theme.
} );


// see https://developer.wordpress.org/reference/functions/get_the_post_thumbnail/
// twenty15 theme has tiny header images, full is best for hidpi.
add_filter( 'post_thumbnail_size', function( $size ) {
	return 'featured-image';
} );
