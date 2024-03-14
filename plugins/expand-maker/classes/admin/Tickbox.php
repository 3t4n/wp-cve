<?php
namespace yrm;

class Tickbox {
	private $isEditorButton = false;
	private $isLoadedMediaData = false;

	public function __construct($isEditorButton = false, $isLoadedMediaData = false) {
		if (isset($isEditorButton)) {
			$this->isEditorButton = $isEditorButton;
		}
		if (isset($isLoadedMediaData)) {
			$this->isLoadedMediaData = $isLoadedMediaData;
		}
		$this->mediaButton();

		if(!$this->isLoadedMediaData) {
			add_action( 'admin_footer', array($this, 'yrmAdminTickBox'));
		}
	}

	public function mediaButton() {
		global $pagenow, $typenow;
		$output = '';
		$allowedTag = \ReadMoreAdminHelper::getAllowedTags();

		/** Only run in post/page creation and edit screens */
		if ( in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) && $typenow !== 'download' ) {

			wp_enqueue_script('jquery-ui-dialog');
			wp_register_style('yrm_JQuery_ui', YRM_ADMIN_CSS_URL . 'jQueryDialog/jquery-ui.css');
			wp_enqueue_style('yrm_JQuery_ui');

			$img = '<span class="wp-media-buttons-icon dashicons dashicons-clock" id="yrm-media-button" style="margin-right: 5px !important;"></span>';
			$output = '<a href="javascript:void(0);" class="button yrm-thickbox" style="padding-left: .4em;">' . wp_kses($img, $allowedTag) . __('Countdown Builder', YRM_LANG) . '</a>';
		}

		if (!$this->isEditorButton) {
			echo wp_kses($output, $allowedTag);
		}
	}

	function yrmAdminTickBox() {
		global $pagenow, $typenow;

		// Only run in post/page creation and edit screens
		if (in_array($pagenow, array('post.php', 'page.php', 'post-new.php', 'post-edit.php')) && $typenow !== 'download') :
		?>
		<script type="text/javascript">
			function insertReadMoreDownload() {
				var id = jQuery('.yrm-readmore').val();

				if (!id) {
					alert('<?php _e('Select your read more', YRM_LANG); ?>');
					return;
				}

				function getTextTabSelectionText() {
					var txtarea = document.querySelector("textarea[name='content']");
					var start = txtarea.selectionStart;
					var finish = txtarea.selectionEnd;
					var sel = txtarea.value.substring(start, finish);

					return sel;
				}

				if (tinyMCE.activeEditor == null) {
					var selection = getTextTabSelectionText();
				} else {
					var selection = tinyMCE.activeEditor.selection.getContent();
				}

				if (!selection) {
					selection = 'Read more hidden text';
				}

				var moreAttribute = jQuery('#yrm-shortcode-more-attribute-enable').is(':checked') ? 'more="' + jQuery('#yrm-shortcode-more-attribute').val() + '"' : '';
				var lessAttribute = jQuery('#yrm-shortcode-less-attribute-enable').is(':checked') ? 'less="' + jQuery('#yrm-shortcode-less-attribute').val() + '"' : '';

				window.send_to_editor('[expander_maker id="' + id + '" ' + moreAttribute + ' ' + lessAttribute + ']' + selection + '[/expander_maker]');
				jQuery('.yrm-readmore-builder .ui-dialog-titlebar-close').click();
			}

			jQuery(document).ready(function ($) {
				$('.yrm-thickbox').bind('click', function () {
					jQuery('#yrm-dialog').dialog({
						width: 450,
						modal: true,
						title: "Insert the shortcode",
						dialogClass: "yrm-readmore-builder"
					});
				});
			});
		</script>
		<?php
		$idTitle = \ReadMoreData::getReadMoresIdAndTitle();
		?>

		<div id="yrm-dialog" style="display: none;">
			<div class="wrap" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
				<p>
					<label><?php _e('Select read more', YRM_LANG); ?>:</label>
					<?php if (!empty($idTitle)) : ?>
						<?php echo \ReadMoreFunctions::yrmSelectBox($idTitle, '', array('name' => 'yrmOption', 'class' => 'yrm-readmore')); ?>
					<?php else : ?>
						<a href="<?php echo admin_url(); ?>admin.php?page=<?php echo YRM_ADD_NEW_MENU_KEY; ?>"><?php _e('Add New Read More', YRM_LANG); ?></a>
					<?php endif; ?>
				</p>
				<?php if (!empty($idTitle)) : ?>
					<div class="row">
						<div class="yrm-custom-more-label-wrapper">
							<label for="yrm-shortcode-more-attribute-enable"><?php _e('Enable More Custom Attribute', YRM_LANG); ?></label>
							<input type="checkbox" id="yrm-shortcode-more-attribute-enable">
							<div class="yrm-custom-more-wrapper yrm-custom-more-hide">
								<label for="yrm-shortcode-more-attribute"><?php _e('Attribute', YRM_LANG); ?></label>
								<input type="text" id="yrm-shortcode-more-attribute" name="yrm-shortcode-more-attribute" value="<?php _e('Read More', YRM_LANG); ?>">
							</div>
						</div>
						<div class="yrm-custom-less-label-wrapper">
							<label for="yrm-shortcode-less-attribute-enable"><?php _e('Enable Less Custom Attribute', YRM_LANG); ?></label>
							<input type="checkbox" id="yrm-shortcode-less-attribute-enable">
							<div class="yrm-custom-more-wrapper yrm-custom-less-hide">
								<label for="yrm-shortcode-less-attribute"><?php _e('Attribute', YRM_LANG); ?></label>
								<input type="text" id="yrm-shortcode-less-attribute" name="yrm-shortcode-less-attribute" value="<?php _e('Read Less', YRM_LANG); ?>">
							</div>
						</div>
					</div>
				<?php endif; ?>
				<p class="submit">
					<input type="button" id="edd-insert-download" class="button-primary" value="<?php _e('Insert', YRM_LANG) ?>" onclick="insertReadMoreDownload();" />
					<a id="edd-cancel-download-insert" class="button-secondary" onclick="jQuery('#yrm-dialog').dialog('close');"><?php _e('Cancel', 'easy-digital-downloads'); ?></a>
				</p>
			</div>
			<style>
				.yrm-custom-more-wrapper,
				.yrm-custom-less-wrapper {
					padding-top: 5px
				}

				.yrm-custom-more-label-wrapper {
					margin-bottom: 12px
				}

				.yrm-custom-more-hide,
				.yrm-custom-less-hide {
					display: none;
				}

				#yrm-shortcode-more-attribute-enable:checked~.yrm-custom-more-hide,
				#yrm-shortcode-less-attribute-enable:checked~.yrm-custom-less-hide {
					display: block;
				}

				.yrm-readmore-builder .ui-button-icon-only .ui-icon {
					left: 50%;
					margin-left: -6px;
					margin-top: -6px;
				}
			</style>
		</div>
		<?php
		endif;
	}
}