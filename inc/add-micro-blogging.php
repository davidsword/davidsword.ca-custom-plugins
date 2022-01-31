<?php
/**
 * Tweak WordPress to be more Micro-Blogg'y (inspired by micro.blog)
 *
 * A micro blog post has the following criteria:
 *
 * - no post title
 * - post format of `aside`
 * - in category `micro-blog`
 * - no excerpts in feeds, must be full post
 *
 * No post title is an implicit indication it's a micro blog post
 * Post format aside is an explicit indication
 * The category is for organization/querying, as is set auto when confirmed is micro blog post
 *
 * Anything else: featured images, tags, other cats, content length, blocks used,
 * etc, is free game. Not using a custom post type as CPTs aren't supported
 * via the WordPress mobile app.
 */

const MB_POST_SLUG_WORD_LENGTH  = 3;
const MB_CAT_NAME 				= 'micro-blog';
const MB_POST_FORMAT 			= 'aside';
const MB_ONE_TIME_FIX_SLUG		= 'micro_blog_fix_posts';
const MB_AUTHOR_ID 				= 1;
const MB_CRONJOB_REMINDER 		= 'micro_blog_cron_reminder';
const MB_CRONJOB_REMINDER_HOUR  = 20; // localtime `get_option('timezone_string')`, no UTC math or DLS
const MB_LAST_POST_OPTION		= 'micro_blog_last_post_time';

/**
 * Register post format
 */
add_action('after_setup_theme', function() {
	add_theme_support( 'post-formats', [ MB_POST_FORMAT ] );
});

/**
 * Detect and modify Microblog posts on post save
 *
 * @param int $post_ID
 * @param WP_Post $post
 * @param bool Whether this is an existing post being updated
 * @return void
 */
function dsca_microblog_save_post( $post_ID, $post, $update ) {

	// @TODO for some reason Scheduled posts dont flow through here

	if ($post->post_type !== 'post' || $post->post_status === 'auto-draft')
		return;

	$title_is_blank = empty( $post->post_title );

	// when title is blank it's implicitly a micro blog post, set format and cat incase it wasnt
	if ( $title_is_blank ) {
		set_microblog_format_and_term( $post );
	}

	// a microblog post could have an explicit title IF the post_format andor term was set. this checks for that.
	if ( ! dsca_is_microblog_post( $post->ID ) )
		return;

	$new_slug = create_micro_blog_post_slug( $post );

	// we're all good here.
	if ($new_slug === $post->post_name)
		return;

	// prevent infinite looping
	remove_action( 'save_post', 'dsca_microblog_save_post', 10, 3 );

	wp_update_post( array(
		'ID' => $post_ID,
		'post_name' => $new_slug,
		'author' => MB_AUTHOR_ID
	));

	add_action( 'save_post', 'dsca_microblog_save_post', 10, 3 );
}
add_action( 'save_post', 'dsca_microblog_save_post', 10, 3 );
add_action( 'future_post', 'dsca_microblog_save_post', 10, 3 ); // scheduled posts

/**
 * Set nessisary term and post format if not set
 *
 * @param WP_Post $post
 * @return void
 */
function set_microblog_format_and_term( $post ) {
	$has_aside_post_format = has_post_format( MB_POST_FORMAT, $post );
	$has_micro_blog_term = has_term( MB_CAT_NAME, 'category', $post->ID );

	if ( ! $has_aside_post_format )
		set_post_format( $post->ID, MB_POST_FORMAT );
	if ( ! $has_micro_blog_term )
		wp_add_object_terms( $post->ID, MB_CAT_NAME, 'category' );

	// remove default category
	$default_category_id = get_option('default_category');
	wp_remove_object_terms( $post->ID, intval( $default_category_id ), 'category' );
}
/**
 * Create micro blog post slug
 *
 * Without a title, WP will use the post ID, and worse, the post ID may have a `-2`.
 * Change to first x words of a micro post.
 *
 * @param WP_Post $post
 * @return string new slug
 */
function create_micro_blog_post_slug( $post ) {

	$slug = sanitize_title( wp_trim_words( wp_strip_all_tags( $post->post_content ), MB_POST_SLUG_WORD_LENGTH, '' ) );

	// there's a change a URL was one of the three "words".
	if ( strstr( $slug, 'https-' ) )
		// converts a link-slug like `5-9-https-twitter-com-wordpress-status-1486081411157315592` to `5-9-twitter`
		$slug = implode( '-', array_slice( explode( '-', str_replace( 'https-', '', $slug ) ),0,MB_POST_SLUG_WORD_LENGTH) );

	return $slug;
}

/**
 * Clean up wp-admin posts screen titles for cleaner managing (remove "(no title)")
 */
add_filter('the_title', function($title, $id){
	if ( ! is_admin() )
		return $title;

	$is_edit_screen = get_current_screen()->base === 'edit';
	if ( $is_edit_screen && dsca_is_microblog_post( $id ) )
		return '/'.get_post($id)->post_name.'/';

	return $title;
}, 10, 2 );

/**
 * everything about microbloging should be short, including the date:
 * opinionated override of wp's date format
 */
add_filter( 'get_the_date', function( $date, $format, $id ) {

	// @TODO fix
	// if ( ! is_admin() && dsca_is_microblog_post( $id ) )
	// 	return get_the_date('M n', $id);

	return $date;
}, 10, 3);

/**
 * remove "micro-blog" tag from list of tags, this is more for organization than display
 */
add_filter( 'wp_get_object_terms', function( $terms, $object_ids, $taxonomies, $args ) {
	$is_rest = defined('REST_REQUEST') && REST_REQUEST;
	if ( is_admin() || $is_rest )
		return $terms;

	foreach ( $terms as $k => $term )
		if ( isset( $term->slug ) && $term->slug === MB_CAT_NAME )
			unset($terms[$k]);

	return $terms;
}, 10, 4 );

/**
 * One time repair of micro blog posts
 *
 * Would do cli, but Pressable doesnt allow wpcli cmds so need to trigger actions via querystring :upsidedownsmile:
 */
add_action( 'init', function(){
	if ( ! is_admin() || ! current_user_can('manage_options') || ! isset( $_GET[MB_ONE_TIME_FIX_SLUG] ))
		return;

	$args = array(
		'post_type' => 'post',
		'tax_query' => array(
			'relation' => 'OR',
			array(
				'taxonomy' => 'category',
				'field'    => 'slug',
				'terms'    => array( MB_CAT_NAME ),
			),
			array(
				'taxonomy' => 'post_format',
				'field'    => 'slug',
				'terms'    => array( 'post-format-'.MB_POST_FORMAT ),
			),
		),
		'posts_per_page' => '-1',
	);
	$micro_blog_posts = new WP_Query( $args );

	// prevent infinite looping
	remove_action( 'save_post', 'dsca_microblog_save_post', 10, 3 );

	foreach ( $micro_blog_posts->posts as $mpost ) {
		$new_slug = create_micro_blog_post_slug( $mpost );
		if ( $mpost->post_name != $new_slug) {
			wp_update_post( array(
				'ID' => $mpost->ID,
				'post_name' => $new_slug
			));
			set_microblog_format_and_term( $mpost );
			// @TODO maybe null or at least look for the post_title not empty
		}
	}

	// re-hook this function
	add_action( 'save_post', 'dsca_microblog_save_post', 10, 3 );
});


add_action( 'transition_post_status', function ( $new_status, $old_status, $post ) {
	if ( 'publish' !== $new_status || 'publish' !== $old_status )
		return;

	if ( ! dsca_is_microblog_post( $post->ID ) )
		return;

	// stash time for reminder cron to check agaisnt
	update_option(MB_LAST_POST_OPTION, time() );

}, 10, 3 );

/**
 * Check if micro blog post or not.
 *
 * @param int $id of post
 * @return bool
 */
function dsca_is_microblog_post( $id = false ) {
	if ( ! $id )
		$id = get_the_ID();

	if ( has_post_format('aside', $id) )
		return true;

	if ( empty( get_post( $id )->post_title ) )
		return true;

	// not checking for micro-blog term. if default cat is micro-blog, this will accidentally fire on normal drafts.

	return false;
}

/**
 * Set a cron job to remind to micro blog.
 */
add_action('wp', function() {

	$tz       = get_option('timezone_string');
	$now 	  = new DateTime("now", new DateTimeZone($tz) );

	if ( ! wp_next_scheduled( MB_CRONJOB_REMINDER ) ) {
		$tomorrow = $now->format('H') > MB_CRONJOB_REMINDER_HOUR ? 'tomorrow ' : '';
		$run_next = strtotime("{$tomorrow} 8pm {$tz}");
		wp_schedule_event($run_next, 'daily', MB_CRONJOB_REMINDER);
	}
});
add_action(MB_CRONJOB_REMINDER, function(){
	$last_posted = get_option(MB_LAST_POST_OPTION );
	if ( $last_posted > strtotime( '-24 hours' ) )
		return; // awesome.

	$message = "'stop being a passive consumer of the internet and join the class of creators' \n\n".esc_url( get_admin_url() );
	wp_mail( // phpcs:ignore
		get_option('admin_email'),
		'Reminder: Micro Blog!',
		$message
	);
});

/**
 * Change Permalinks for single micro blog post pages.
 *
 * Default is whatever (personally I've always used /%post_name%/)
 * Single mb post should be `/yyyy/mm/dd/%slug%/`
 */
add_action( 'init',  function() {
	if ( is_user_logged_in() && current_user_can('manage_options') && isset($_GET['flush_rewrites']))
		flush_rewrite_rules( true );

	if ( '/%year%/%monthnum%/%day%/%postname%/' !== get_option('permalink_structure') )
		add_rewrite_rule( '([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/([a-z0-9-]+)[/]?$', 'index.php?name=$matches[4]', 'top' );
} );

add_filter( 'post_link', function( $url, $post, $leavename ) {
	if ( dsca_is_microblog_post( $post->ID ) ) {
		$date = strtotime( date( $post->post_date ) );
		$url= trailingslashit( home_url(date('/Y/m/d/', $date).$post->post_name.'/') );
	}
	return $url;
}, 10, 3 );
