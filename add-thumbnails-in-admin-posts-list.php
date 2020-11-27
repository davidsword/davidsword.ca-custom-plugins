<?php

/**
 * Plugin Name: DSCA - Add Thumbnails in Admin Posts Table
 * Plugin URI: https://github.com/davidsword/davidsword.ca-custom-plugins
 * Description: Add Thumbnails in Admin Posts Table
 * Version: 0.0.1
 * Author: David Sword
 * Author URI: https://davidsword.ca/
 * License: GNU GENERAL PUBLIC LICENSE
 */

/**
 * Display the post thumbnail in the edit page table for eaiser management
 *
 * @param array $columns from wp api.
 * @return array $columns for wp api.
 */
function ds_make_thumbnail_cols( $columns ) {
	unset( $columns['date'] );
	unset( $columns['comments'] );
	unset( $columns['author'] );
	$columns['img_thumbnail'] = 'Ftr Img';
	return $columns;
}

add_action(
	'init',
	function() {
		$cpts = [ 'post', 'project', 'photo', 'status', 'page' ];
		foreach ( $cpts as $cpt ) {
			add_filter( "manage_{$cpt}_posts_columns", 'ds_make_thumbnail_cols' );
		}
		add_action( 'manage_posts_custom_column', 'ds_make_thumbnail_cells', 999, 2 );
	},
	11
);

/**
 * Display the post thumbnail in the edit page table for eaiser management
 */
function ds_make_thumbnail_cells( $column_name, $id ) {
	if ( 'img_thumbnail' === $column_name ) {
		echo "<a href='" . esc_url( get_edit_post_link() ) . "'>";
		echo wp_kses_post( the_post_thumbnail( 'thumbnail', [ 'style' => 'max-width: 60px;height:auto' ] ) );
		echo '</a>';
	}
}
