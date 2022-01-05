<?php

// davidsword.ca TwentyFifteen Custom CSS

/**
 * Add here instead of in Customizer for version control.
 */
add_action( 'wp_head', function() {
	?>
	<!-- via davidsword/davidsword.ca-custom-plugins/change-twentyfifteen-custom-css.php -->
	<style type='text/css'>
		.entry-content .more-link {
			background:#D5E4F9;
			padding:0 3px;
			border-radius:2px;
		}
		.entry-content .more-link:hover,
		.entry-content .more-link:visited:hover {
			background:#E1D2FA;
		}
		/* sidebar header logo */
		.custom-logo {
			max-height: 40px;
			border-radius: 50%;
			overflow:hidden;
		}

		/* change font */
		body,
		body .site-branding * {
			font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
		}

		/* inline code */
		pre.wp-block-code, code {
			background: #F7F7F7;
			border: 1px solid #E4E4E4;
			padding: 0 4px;
			font-family: "Operator Mono", Courier, monospace;
			font-weight: 300;
		}

		pre.wp-block-code {
			padding: 15px;
		}

		pre code {
			border: none;
			background: transparent;
			padding:0;
		}

		/* No author, tag, or format deets */
		html .entry-footer .byline,
		html .entry-footer .tags-links,
		html .entry-footer .entry-format {
			display: none;
		}

		/* Make ftr img full width */
		html body .post-thumbnail img {
			width: 100%;
		}

		/* ART */
		html body .category-art.article-is-empty .post-thumbnail {
			margin-bottom: 0;
		}
		html body .category-art > header,
		html body .category-art.article-is-empty > .entry-content {
			display:none;
		}

		/* Bigger phones, minor tweaks */
		@media screen and (min-width: 360px) {
			.category header.page-header { margin-bottom: 6%; }
			body {
				font-size: 1.7rem;
			}
			.pagination,
			.hentry + .hentry {
				margin-top: 6%;
			}
			.site-main {
				padding: 6% 0;
			}
			.hentry {
				margin: 0 6%;
			}
			.custom-logo {
				max-height: 75px;
			}
			.site-description {
				display: block;
				margin: 0;
			}
		}

		@media screen and (min-width: 420px) {
			.custom-logo {
				max-height: 100px;
			}
		}

		/* about cat trickery */
		.category-about .entry-footer {
			display:none;
		}
		.category-about .page-header + .hentry {
			margin-top:0;
		}

		/* when on single, highlight parent in nav */
		.current-post-parent a {
			font-weight: bold;
		}
	</style>
	<?php
}, 99 );
