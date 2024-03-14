<?php
/**
 * Lord of the Files: Developer Reference
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
	<h2 class="hndle"><?php echo \esc_html__('Table of Contents', 'blob-mimes'); ?></h2>
	<div class="inside">
		<ul id="lotf-links" class="lotf__links"></ul>
	</div><!-- .inside -->
</div><!-- .postbox -->
