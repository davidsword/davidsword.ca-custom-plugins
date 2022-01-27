<?php

add_filter( 'upload_mimes', function( $mimes_types ) {
    $mimes_types['alfredworkflow'] = 'application/zip';
    return $mimes_types;
}, 99 );

add_filter( 'wp_check_filetype_and_ext', function ( $types, $file, $filename, $mimes ) {
    $wp_filetype = wp_check_filetype( $filename, $mimes );
    $ext         = $wp_filetype['ext'];
    $type        = $wp_filetype['type'];
    if( in_array( $ext, ['alfredworkflow'] ) ) { // it allows zip files
        $types['ext'] = $ext;
        $types['type'] = $type;
    }
    return $types;
}, 99, 4 );
