<?php
/**
 * Plugin Name: Disable Attachment Pages
 * Description: Redirects attachment pages to the post, where they are placed, and hides backend option to link images to attachment page (if not default).
 * Version: 1.1
 * Author: PALASTHOTEL by Kim-Christian Meyer
 * Author URI: https://palasthotel.de
 */


add_action( 'template_redirect', 'disable_attachment_pages_redirect_attachments', 1 );
function disable_attachment_pages_redirect_attachments() {
	if ( is_attachment() ) {
		if ( ! empty( $GLOBALS['post']->post_parent ) ) {
			// Permanent redirect to post/page, where the image or document was placed.
			wp_redirect( get_permalink( $GLOBALS['post']->post_parent ), 301 );
			exit;
		}

		// Temporary redirect to WordPress home URL for images or documents not associated to any post/page.
		wp_redirect( get_bloginfo( 'wpurl' ), 302 );
		exit;
	}
}


/**
 * Hide the 'attachment page' option for the link-to part when editing or
 * inserting images.
 */
add_action( 'admin_head', 'disable_attachment_pages_disable_linkto' );
function disable_attachment_pages_disable_linkto() {
	echo <<<EOT
<style>
.setting select.link-to option[value="post"],
.setting select[data-setting="link"] option[value="post"],
.components-select-control__input option[value="attachment"] {
	display: none;
}
</style>
EOT;
}
