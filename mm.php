<?php
/**
 * Plugin Name: DSCA - maintenance mode
 * Plugin URI: https://github.com/davidsword/davidsword.ca-custom-plugins
 * Description: disable site
 * Version: 0.0.1
 * Author: David Sword
 * Author URI: https://davidsword.ca/
 * License: GNU GENERAL PUBLIC LICENSE
 */

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
	
    wp_redirect( get_option('home') , 302);
    die;
});

function is_login_page() {
    return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
}
