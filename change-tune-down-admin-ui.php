<?php

/**
 * Plugin Name: DSCA - Tune down admin ui noise
 * Plugin URI: https://github.com/davidsword/davidsword.ca-custom-plugins
 * Description: css edits to wp-admin
 * Version: 0.0.1
 * Author: David Sword
 * Author URI: https://davidsword.ca/
 * License: GNU GENERAL PUBLIC LICENSE
 */

 add_action( 'admin_head', function() {
	 //@todo check current page.
	 global $_wp_admin_css_colors;
	 $admin_color = get_user_option( 'admin_color' );
	 $colors = $_wp_admin_css_colors[$admin_color]->colors;
	 ?>
		<style>
		    html body #toplevel_page_wpseo_dashboard > ul > li:nth-child(5), /* tools */
			html body #toplevel_page_wpseo_dashboard > ul > li:nth-child(6), /* prem */
			html body .yoast_premium_upsell,
			html body .yoast-container.yoast-container__warning,
			html body #yoast-helpscout-beacon,
			html body #sidebar.yoast-sidebar,
			html body .yoast-container__configuration-wizard,
			html body #toplevel_page_wpseo_dashboard .update-plugins {
				display: none !important;
			}

			html body .switch-light.switch-yoast-seo a,
			html body .switch-toggle.switch-yoast-seo a {
				background: <?php echo esc_attr( $colors[2] ) ?>;
			}
		</style>
	 <?php
 } );
