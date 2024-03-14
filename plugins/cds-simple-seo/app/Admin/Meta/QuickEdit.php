<?php 

namespace app\Admin\Meta;

/* Exit if accessed directly. */
if (!defined('ABSPATH')) {
	exit;
}
	
/**
 * Quick edit class for meta box. This contains the meta box,
 * HTML, form fields, everything to have a SimpleSEO work 
 * under the quick edit feature.
 *
 * @since  2.0.0
 */
class QuickEdit {
	
	public function __construct($columnName) {
		$currentScreen = get_current_screen();
		if (!empty($_GET['post_type']) && $_GET['post_type'] != $currentScreen->post_type) {
			return;
		}

		static $printNonce = true;
		if ($printNonce) {
			$printNonce = false;
			wp_nonce_field(SSEO_PATH, 'sseo_nonce');
		}

		switch($columnName) {
			case 'seo_title': ?>
		<fieldset class="inline-edit-col-left clear">
			<div class="inline-edit-col">
				<label>
					<span class="title"><?php echo __('SEO Title', SSEO_TXTDOMAIN); ?></span>
					<span class="input-text-wrap">
						<input class="seo_title" type="text" name="sseo_meta_title" placeholder="">
						<span><span class="seo_title_count" style="color: rgb(112, 192, 52);">0</span> / <?php echo __('70 recommended characters', SSEO_TXTDOMAIN); ?></span></span>
				</label>
			</div>
		</fieldset>
		<?php break; case 'seo_description': ?>
		<fieldset class="inline-edit-col-left clear">
			<div class="inline-edit-col">
				<label>
					<span class="title"><?php echo __('SEO Desc.'); ?></span>
					<span class="input-text-wrap">
						<textarea class="seo_description" name="sseo_meta_description"></textarea>
						<span><span class="seo_description_count" style="color: rgb(112, 192, 52);">0</span> / <?php echo __('160 recommended characters', SSEO_TXTDOMAIN); ?></span>
					</span>
				</label>
			</div>
		</fieldset>
		<?php break; }
	}
}

?>