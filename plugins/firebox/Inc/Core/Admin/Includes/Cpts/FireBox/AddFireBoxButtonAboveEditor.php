<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\Admin\Includes\Cpts\FireBox;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class AddFireBoxButtonAboveEditor
{
	/**
	 * The modal ID
	 * 
	 * @var  String
	 */
	const MODAL_ID = 'fpf-add-firebox-classic-editor-modal';
	
	public function __construct()
	{
		if (!$this->canRun())
		{
			return;
		}

		// adds popup at the end of the page
		add_action('admin_footer', [$this, 'setupPopup'], 13);
		
		// load button js
		add_action('admin_enqueue_scripts', [$this, 'registerMedia']);

		// add button above classic editor
		add_action('media_buttons', [$this, 'add_firebox_button_above_editor']);
	}

	/**
	 * Adds the popup to the page
	 * 
	 * @return  void
	 */
	public function setupPopup()
	{
		$content = '<div class="fpf-modal-form-wrapper">' . $this->getForm() . '</div>';
		
		$payload = [
			'id' => self::MODAL_ID,
			'title' => firebox()->_('FB_ADD_A_FIREBOX_BUTTON'),
			'content' => $content,
			'width' => '480px',
			'footer' => '<a href="#" class="fpf-modal-close fpf-button">' . fpframework()->_('FPF_CLOSE') . '</a><a href="#" class="fpf-button primary fpf-modal-close fb-insert-firebox-shortcode-to-editor">' . firebox()->_('FB_ADD_BUTTON') . '</a>'
		];
		
        \FPFramework\Helpers\HTML::renderModal($payload);
	}

	/**
	 * Get the form
	 * 
	 * @return  string
	 */
	private function getForm()
	{
		$fields = [
			'id' => 'FireBoxClassicEditorModalForm',
			'data' => [
				'mySection' => [
					'content' => [
						'section' => [
							'wrapper' => [
								'class' => ['grid-x', 'grid-margin-y', 'fpf-cells-small-margin']
							],
							'fields' => [
								[
									'type' => 'Heading',
									'name' => 'fb_box_ce_modal_heading',
									'title' => firebox()->_('FB_CHOOSE_BOX_TO_HANDLE'),
								],
								[
									'name' => 'box_selector',
									'type' => 'Dropdown',
									'label' => firebox()->_('FB_SELECT_A_CAMPAIGN'),
									'input_class' => ['fb_box_ce_modal_box_selector'],
									'choices' => \FireBox\Core\Helpers\BoxHelper::getAllBoxesParsedByKeyValue()
								],
								[
									'name' => 'box_action',
									'type' => 'Dropdown',
									'label' => firebox()->_('FB_FIREBOX_ACTION'),
									'input_class' => ['fb_box_ce_modal_box_action'],
									'choices' => [
										'open' => 'FPF_OPEN',
										'close' => 'FPF_CLOSE',
										'toggle' => 'FPF_TOGGLE'
									],
									'default' => 'close'
								],
								[
									'name' => 'box_button_label',
									'type' => 'Text',
									'label' => 'FPF_BUTTON_LABEL',
									'input_class' => ['fb_box_ce_modal_box_button_label'],
									'default' => fpframework()->_('FPF_CLOSE')
								],
								[
									'name' => 'box_button_classes',
									'type' => 'Text',
									'label' => 'FPF_BUTTON_CLASSES',
									'input_class' => ['fb_box_ce_modal_box_button_classes'],
									'default' => 'button'
								],
								[
									'name' => 'box_prevent_default',
									'type' => 'FPToggle',
									'label' => 'FPF_PREVENT_DEFAULT_ACTION',
									'input_class' => ['fb_box_ce_modal_box_prevent_default'],
									'value' => 'on'
								],
							]
						]
					]
				]
			]
		];

		$form = new \FPFramework\Base\Form($fields, ['render_form' => false]);
		return $form->render();
	}

	/**
	 * Adds media
	 * 
	 * @return  void
	 */
	public function registerMedia()
	{
		// load button js
		wp_register_script(
			'fb-button-above-classic-editor',
			FBOX_MEDIA_ADMIN_URL . 'js/fb_add_firebox_above_classic_editor_button.js',
			['wp-i18n', 'wp-api-fetch'],
			FBOX_VERSION,
			false
		);
		wp_enqueue_script( 'fb-button-above-classic-editor' );
	}

	/**
	 * Adds a "Add FireBox" button on the top of the Classic Editor
	 * 
	 * @param   string  $editor_id
	 * 
	 * @return  void
	 */
	public function add_firebox_button_above_editor($editor_id)
	{
		?><a href="#" id="add-firebox-modal-button" data-fpf-modal="#<?php echo esc_attr(self::MODAL_ID); ?>" class="button fpf-modal-opener fpf-above-classic-editor-btn"><span class="dashicons dashicons-plus-alt2"></span><?php echo esc_html(firebox()->_('FB_ADD_FIREBOX')); ?></a><?php
	}

	/**
	 * Whether we can run
	 * 
	 * @return  boolean
	 */
	protected function canRun()
	{
		if (!current_user_can('manage_options') || !current_user_can('edit_posts') || !current_user_can('edit_pages'))
		{
			return false;
		}
		
		if (!\FPFramework\Helpers\WPHelper::isClassicEditorPluginActive())
		{
			return false;
		}

		global $pagenow;
		if (!in_array($pagenow, ['post.php', 'post-new.php']))
		{
			return false;
		}

		return true;
	}
}