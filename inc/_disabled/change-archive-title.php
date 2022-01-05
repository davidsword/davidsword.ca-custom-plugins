<?php

// Change Archive Title

// remove "category:" prefix, it goes without saying.
add_filter(
	'get_the_archive_title',
	function ( $title ) {
		if ( is_category() ) {
			$title = str_replace( 'Category:', '', $title );
		}
		return $title;
	}
);
