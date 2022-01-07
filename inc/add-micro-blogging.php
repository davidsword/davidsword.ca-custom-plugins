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
 * The post format is for the theme, the category is for organization/querying.
 * Anything else: images, tags, other cats, content length, blocked used, featured
 * image, etc, is free game. Not using a custom post type as CPTs aren't supported
 * via the WordPress mobile app.
 */

const MB_POST_SLUG_WORD_LENGTH  = 3;
const MB_CAT_NAME 				= 'micro-blog';
const MB_POST_FORMAT 			= 'aside';
const MB_ONE_TIME_FIX_SLUG		= 'micro_blog_fix_posts';
const MB_AUTHOR_ID 				= 1;
const MB_CRONJOB_REMINDER 		= 'micro_blog_cron_reminder';
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
	// allow 'publish', 'draft', 'future'
	if ($post->post_type != 'post' || $post->post_status == 'auto-draft')
		return;

	// only change slug when the post is created
	if ($post->post_date_gmt != $post->post_modified_gmt)
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

	if ($new_slug == $post->post_name)
		return;

	// prevent infinite looping
	remove_action( 'save_post', 'dsca_microblog_save_post', 10, 3 );

	wp_update_post( array(
		'ID' => $post_ID,
		'post_name' => $new_slug,
		'author' => MB_AUTHOR_ID
	));

	add_action( 'save_post', 'dsca_microblog_save_post', 10, 3 );

	// stash time for reminder cron to check agaisnt
	update_option(MB_LAST_POST_OPTION, time() );
}
add_action( 'save_post', 'dsca_microblog_save_post', 10, 3 );

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
	return sanitize_title( truncate_words( strip_tags( $post->post_content ), MB_POST_SLUG_WORD_LENGTH ) );

}

/**
 * Truncate string into first x words
 *
 * Be sure to truncate words instead of characters as may accidentally spell things in permalinks.
 *
 * @param string $text string of words to cut from
 * @param int $limit words to cut off at
 * @return string
 */
function truncate_words($text, $limit) {
	if (str_word_count($text, 0) > $limit) {
		$words = str_word_count($text, 2);
		$pos   = array_keys($words);
		$text  = substr($text, 0, $pos[$limit]);
	}
	return $text;
}

/**
 * Clean up wp-admin posts screen titles for cleaner managing (remove "(no title)")
 */
add_filter('the_title', function($title, $id){
	if ( ! is_admin() )
		return $title;

	// @TODO check `&& is edit screen` https://developer.wordpress.org/reference/functions/get_current_screen/
	if ( dsca_is_microblog_post( $id ) )
		return get_post($id)->post_name;

	return $title;
}, 10, 2 );

/**
 * everything about microbloging should be short, including the date:
 * opinionated override of wp's date format
 */
add_filter( 'get_the_date', function( $date, $format, $id ) {

	if ( ! is_admin() && dsca_is_microblog_post( $id ) )
		return date('M n');

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

	if ( has_term('micro-blog', 'category', $id ) )
		return true;

	return false;
}

/**
 * Set a cron job to remind to micro blog.
 */
add_action('wp', function() {
	if ( ! wp_next_scheduled( MB_CRONJOB_REMINDER ) ) {
		$tomorrow = date('h') > 20 ? ' tomorrow' : '';
		$run_next = strtotime('8pm'.$tomorrow);
		wp_schedule_event($run_next, 'daily', MB_CRONJOB_REMINDER);
	}
});
add_action(MB_CRONJOB_REMINDER, function(){
	$last_posted = get_option(MB_LAST_POST_OPTION );
	if ( $last_posted < strtotime( '12am today' ) )
		wp_mail(
			get_option('admin_email'),
			'Reminder: Micro Blog!',
			"'stop being a passive consumer of the internet and join the class of creators' \n\n".esc_url( get_admin_url() )
		);
});

// @TODO hard code "no excerpts in feeds, must be full post" to override options and theme settings
