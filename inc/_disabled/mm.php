<?php

// maintenance mode
return;

add_action('wp', function(){
    if( is_front_page() )
		return;

	if(is_user_logged_in())
		return;

	if(is_login_page())
		return;

	if(!headers_sent()){
        header('X-Robots-Tag', 'noindex, nofollow, noarchive');
    }

	$show_posts = [
		10288, // uses
		10707  // custom ir remote
	];

	if ( in_array( get_the_ID(), $show_posts, true ) )
		return;

    wp_redirect( get_option('home') , 302);
    die;
});

function is_login_page() {
    return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'), true);
}
