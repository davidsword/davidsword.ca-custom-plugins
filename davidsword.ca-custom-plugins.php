<?php

/**
 * Plugin Name: davidsword.ca Custom WordPress plugins
 * Plugin URI: https://github.com/davidsword/davidsword.ca-custom-plugins
 * Description: A bunch of single-file custom WordPress tweaks for my website. See each file for description.
 * Version: 0
 * Author: David Sword
 * Author URI: https://davidsword.ca/
 * License: GNU GENERAL PUBLIC LICENSE
 */

 // phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.NotAbsolutePath -- shh
require('helpers.php');

// auto load mini plugins
$filenames = glob(__DIR__.'/inc/*.php');
foreach ($filenames as $filename)
	if ( 'index.php' !== $filename )
		// phpcs:ignore WordPressVIPMinimum.Files.IncludingFile.NotAbsolutePath -- shh
		require( $filename );
