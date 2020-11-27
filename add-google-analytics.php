<?php

/**
 * Plugin Name: DSCA - Add Google Analytics
 * Plugin URI: https://github.com/davidsword/davidsword.ca-custom-plugins
 * Description: Add Google Analytics
 * Version: 0.0.1
 * Author: David Sword
 * Author URI: https://davidsword.ca/
 * License: GNU GENERAL PUBLIC LICENSE
 */

add_action('wp_head', function(){
	?>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-2879373-23"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-2879373-23');
</script>

	<?php
});
