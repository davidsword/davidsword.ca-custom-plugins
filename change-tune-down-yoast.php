<?php

/**
 * Plugin Name: DSCA - Tune down Yoast
 * Plugin URI: https://github.com/davidsword/davidsword.ca-custom-plugins
 * Description: Strip some Yoast features out
 * Version: 0.0.1
 * Author: David Sword
 * Author URI: https://davidsword.ca/
 * License: GNU GENERAL PUBLIC LICENSE
 */

 add_action( 'admin_head', function() {
	 //@todo check current page.
	 ?>
		<style>
		html body .yoast_premium_upsell,
		html body .yoast-container.yoast-container__warning,
		html body #yoast-helpscout-beacon,
		html body #sidebar.yoast-sidebar,
		html body .yoast-container__configuration-wizard,
			html body #toplevel_page_wpseo_dashboard .update-plugins {
				display: none !important;
			}
		</style>
	 <?php
 } );
