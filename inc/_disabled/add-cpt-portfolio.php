<?php

// Portfolio Post Type

add_action( 'init', function () {
	$labels = array(
		'name'                => _x( 'Portfolio', 'Post Type General Name', 'twentyfifteen' ),
		'singular_name'       => _x( 'Portfolio', 'Post Type Singular Name', 'twentyfifteen' ),
		'menu_name'           => __( 'Portfolio', 'twentyfifteen' ),
		'parent_item_colon'   => __( 'Parent Portfolio', 'twentyfifteen' ),
		'all_items'           => __( 'All Portfolio', 'twentyfifteen' ),
		'view_item'           => __( 'View Portfolio', 'twentyfifteen' ),
		'add_new_item'        => __( 'Add New Portfolio', 'twentyfifteen' ),
		'add_new'             => __( 'Add New', 'twentyfifteen' ),
		'edit_item'           => __( 'Edit Portfolio', 'twentyfifteen' ),
		'update_item'         => __( 'Update Portfolio', 'twentyfifteen' ),
		'search_items'        => __( 'Search Portfolio', 'twentyfifteen' ),
		'not_found'           => __( 'Not Found', 'twentyfifteen' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'twentyfifteen' ),
	);
	$args = array(
		'label'               => __( 'Portfolio', 'twentyfifteen' ),
		'description'         => __( 'Portfolio', 'twentyfifteen' ),
		'labels'              => $labels,
		'supports'            => array( 'editor', 'excerpt', 'thumbnail', 'comments' ),
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'post',
		'show_in_rest' 		  => false,
	);
	register_post_type( 'portfolio', $args );
}, 0 );
