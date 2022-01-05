<?php

// Disable SRCSET

// disable srcset on frontend, seems to load way-to-big images
add_filter('max_srcset_image_width', function () { return 1; });
