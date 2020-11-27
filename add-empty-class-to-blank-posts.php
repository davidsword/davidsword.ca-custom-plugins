<?php

/**
 * Plugin Name: DSCA - Add Empty Class to Blank Posts
 * Plugin URI: https://github.com/davidsword/davidsword.ca-custom-plugins
 * Description: Add Empty Class to Blank Posts
 * Version: 0.0.1
 * Author: David Sword
 * Author URI: https://davidsword.ca/
 * License: GNU GENERAL PUBLIC LICENSE
 */

add_filter( 'post_class', function( $classes, $class, $post_id ) {
	if ( ! is_admin() && empty( get_post( $post_id )->post_content ) )
		$classes[] = 'article-is-empty';
	return $classes;
}, 10, 3 );
