<?php

/**
 * Plugin Name: DSCA - Disable SRCSET
 * Plugin URI: https://github.com/davidsword/davidsword.ca-custom-plugins
 * Description: Disable SRCSET
 * Version: 0.0.1
 * Author: David Sword
 * Author URI: https://davidsword.ca/
 * License: GNU GENERAL PUBLIC LICENSE
 */

// disable srcset on frontend
add_filter('max_srcset_image_width', function () { return 1; });
