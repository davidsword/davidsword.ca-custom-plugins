<?php

// add ftr img to rss

add_filter('the_content_feed', function( $content ) {
	$ftrimg = get_the_post_thumbnail();

	if ( ! has_post_format( 'aside', get_the_ID() ) )
		return $ftrimg . $content;
	else
		return $content . $ftrimg;
});
