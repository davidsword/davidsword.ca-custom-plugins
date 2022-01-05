<?php
// maintenance mode

// add_action('init', 'dsca_mm');
// add_action('rest_api_init', 'dsca_mm');

// function dsca_mm() {
// 	$is_login = $GLOBALS['pagenow'] === 'wp-login.php';
// 	if ( is_admin() || $is_login )
// 		return;

// 	status_header( 503 );
// 	die('👋');
// }
