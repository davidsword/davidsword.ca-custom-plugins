<?php

// Statuses Post Type

add_action( 'init', function () {
	$labels = array(
		'name'                => _x( 'Statuses', 'Post Type General Name', 'twentyfifteen' ),
		'singular_name'       => _x( 'Status', 'Post Type Singular Name', 'twentyfifteen' ),
		'menu_name'           => __( 'Statuses', 'twentyfifteen' ),
		'parent_item_colon'   => __( 'Parent Status', 'twentyfifteen' ),
		'all_items'           => __( 'All Statuses', 'twentyfifteen' ),
		'view_item'           => __( 'View Status', 'twentyfifteen' ),
		'add_new_item'        => __( 'Add New Status', 'twentyfifteen' ),
		'add_new'             => __( 'Add New', 'twentyfifteen' ),
		'edit_item'           => __( 'Edit Status', 'twentyfifteen' ),
		'update_item'         => __( 'Update Status', 'twentyfifteen' ),
		'search_items'        => __( 'Search Status', 'twentyfifteen' ),
		'not_found'           => __( 'Not Found', 'twentyfifteen' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'twentyfifteen' ),
	);
	$args = array(
		'label'               => __( 'Statuses', 'twentyfifteen' ),
		'description'         => __( 'Status news and reviews', 'twentyfifteen' ),
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
	register_post_type( 'status', $args );
}, 0 );
