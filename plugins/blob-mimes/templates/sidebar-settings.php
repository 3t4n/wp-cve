<?php
/**
 * Lord of the Files: File Settings
 *
 * The sidebar body content goes here.
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\bm;

// Do not execute directly.
if (! \defined('ABSPATH')) {
	exit;
}

?>
<div class="postbox">
	<div class="inside">
		<p><?php \printf(
			\esc_html__("For security reasons, these settings must be manually defined in your site's %s.", 'blob-mimes'),
			\sprintf(
				'<a href="https://wordpress.org/support/article/editing-wp-config-php/" target="_blank" rel="noopener">%s</a>',
				\esc_html__('configuration file', 'blob-mimes')
			)
		); ?></p>

		<p><?php echo \esc_html__("But don't worry.", 'blob-mimes'); ?></p>

		<p><?php echo \esc_html__('This page will generate all the code; you just need to copy-and-paste it into place.', 'blob-mimes'); ?></p>
	</div><!-- .inside -->
</div><!-- .postbox -->
