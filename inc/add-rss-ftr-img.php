<?php

// add ftr img to rss

add_filter('the_content_feed', function( $content ) {
	$ftrimg = get_the_post_thumbnail();
	return $ftrimg . $content;
});