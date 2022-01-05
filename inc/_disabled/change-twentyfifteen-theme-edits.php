<?php

// custom TwentyFifteen Theme Edits

// Add search link to footer.
add_action(
	'twentyfifteen_credits',
	function() {
		// @TODO make this a dynamic nav instead.
		echo "<a href='/search'>Search</a> &nbsp; <span style='opacity:0.25'>|</span> &nbsp; ";
	}
);
