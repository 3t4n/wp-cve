<?php
/**
 * Lord of the Files: Developer Reference
 *
 * @package blob-mimes
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\bm;

use blobfolio\wp\bm\admin\reference;

// Do not execute directly.
if (! \defined('ABSPATH')) {
	exit;
}

$help = reference::help();
foreach ($help as $k=>$ref) {
	$id = \esc_attr(\strtolower('lotf-ref-' . $k));
	?>
	<div class="postbox" id="<?php echo $id; ?>">
		<h2 class="hndle"><span><?php echo $ref['title']; ?></span></h2>
		<div
			class="inside lotf__markdown"
			data-title="<?php echo \esc_attr($ref['title']); ?>"
			data-id="<?php echo $id; ?>"
		>
			<!-- A little spinny loader thing. -->
			<div
				style="position: relative; height: 20px; display: flex; align-items: center; justify-content: center;">
				<span class="spinner is-active"></span>
			</div>

			<!-- The content. -->
			<textarea
				style="position: fixed; top: -1000px; left: -1000px; height: 1px; width: 1px;"
				class="lotf__markdown_body"
			><?php echo \esc_textarea($ref['content']); ?></textarea>
		</div>
	</div>
	<?php
}
?>
