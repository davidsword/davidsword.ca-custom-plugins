<?php

// Add Flags to Posts based on Tags

add_action(
	'the_content',
	function( $content ) {
		if ( is_admin() || is_feed() || is_search() )
			return $content;

		// @TODO this should be dynamic, not hard coded.
		$tags = [
			118 => [
				'name'          => 'archived',
				'colour'        => '#FFFCDA',
				'colour_border' => '#EFE5AB',
				'msg'           => 'This project has been archived. It is no longer maintained.',
			],
			110 => [
				'name'          => 'work-in-progress',
				'colour'        => '#F3FCFF',
				'colour_border' => '#ABE2EF',
				'msg'           => 'This project is incomplete, a work in progress. ',
			],
		];

		foreach ( $tags as $tag_id => $flag ) {
			if ( has_tag( $tag_id ) ) {
				$flag_html = sprintf(
					"<p class='tag_flag tag_flag-%s' style='
						padding: 10px;
						text-align:center;
						background-color:%s;
						border: 1px solid %s;
					'>%s</p>",
					esc_attr($flag['name']),
					esc_attr($flag['colour']),
					esc_attr($flag['colour_border']),
					esc_html($flag['msg'])
				);
				$content = $flag_html . $content;
			}
		}

		return $content;
	}
);
