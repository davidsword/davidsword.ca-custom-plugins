<?php

/**
 * Plugin Name: DSCA - custom TwentyFifteen Theme Edits
 * Plugin URI: https://github.com/davidsword/davidsword.ca-custom-plugins
 * Description: davidsword.ca TwentyFifteen Theme Edits
 * Version: 0.0.1
 * Author: David Sword
 * Author URI: https://davidsword.ca/
 * License: GNU GENERAL PUBLIC LICENSE
 */

// Add search link to footer.
add_action(
	'twentyfifteen_credits',
	function() {
		// @TODO make this a dynamic nav instead.
		echo "<a href='/search'>Search</a> &nbsp; <span style='opacity:0.25'>|</span> &nbsp; ";
	}
);
