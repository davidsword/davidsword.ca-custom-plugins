<?php

// Change Tags to be Hierarchical & Private
// Tags for internal notes

// @see https://css-tricks.com/how-and-why-to-convert-wordpress-tags-from-flat-to-hierarchical/
add_action( 'init', function () {
	$labels = [
		'name'                       => _x( 'Tags', 'Taxonomy General Name', 'hierarchical_tags' ),
		'singular_name'              => _x( 'Tag', 'Taxonomy Singular Name', 'hierarchical_tags' ),
		'menu_name'                  => __( 'Tags', 'hierarchical_tags' ),
		'all_items'                  => __( 'All Tags', 'hierarchical_tags' ),
		'parent_item'                => __( 'Parent Tag', 'hierarchical_tags' ),
		'parent_item_colon'          => __( 'Parent Tag:', 'hierarchical_tags' ),
		'new_item_name'              => __( 'New Tag Name', 'hierarchical_tags' ),
		'add_new_item'               => __( 'Add New Tag', 'hierarchical_tags' ),
		'edit_item'                  => __( 'Edit Tag', 'hierarchical_tags' ),
		'update_item'                => __( 'Update Tag', 'hierarchical_tags' ),
		'view_item'                  => __( 'View Tag', 'hierarchical_tags' ),
		'separate_items_with_commas' => __( 'Separate tags with commas', 'hierarchical_tags' ),
		'add_or_remove_items'        => __( 'Add or remove tags', 'hierarchical_tags' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'hierarchical_tags' ),
		'popular_items'              => __( 'Popular Tags', 'hierarchical_tags' ),
		'search_items'               => __( 'Search Tags', 'hierarchical_tags' ),
		'not_found'                  => __( 'Not Found', 'hierarchical_tags' ),
	];

	register_taxonomy(
		'post_tag',
		'post',
		[
			'hierarchical'       => true,
			'labels'             => $labels,
			'query_var'          => 'tag',
			'public'             => false, // back-end filtering only.
			'publicly_queryable' => false, // back-end filtering only.
			'show_tagcloud'      => false, // back-end filtering only.
			'rewrite'            => false,
			'show_admin_column'  => true,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'_builtin'           => true,
			'show_in_rest'       => true,
			'rewrite'           => [
				'hierarchical' => false,
				'slug'         => get_option( 'tag_base' ) ? get_option( 'tag_base' ) : 'tag',
				'with_front'   => false,
				'ep_mask'      => EP_TAGS,
			],
		]
	);
}, 99 );
