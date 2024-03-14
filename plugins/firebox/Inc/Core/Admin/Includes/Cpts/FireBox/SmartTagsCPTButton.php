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

class SmartTagsCPTButton
{
	public function __construct()
	{
		if (!is_admin())
		{
			return;
		}
		
		add_action( 'current_screen', [$this, 'validate'] );
	}

	/**
	 * Runs only on FireBox CPT
	 * 
	 * @return  void
	 */
	public function validate()
	{
		$current_screen = get_current_screen();
		if ($current_screen->id == 'firebox')
		{
			$this->run();
		}
	}

	/**
	 * Runs the module
	 * 
	 * @return  void
	 */
	private function run()
	{
		add_filter('fpframework/filter_admin_js_object', [$this, 'filter_admin_js_object']);
		
		// enqueue media
		add_action('admin_enqueue_scripts', [$this, 'registerMedia']);

		// add the Smart Tags popup
		add_action('admin_footer', [$this, 'add_smart_tags_popup'], 11);
	}

	/**
	 * Filters the admin JS Object
	 * 
	 * @param   array  $data
	 * 
	 * @return  array
	 */
	public function filter_admin_js_object($data)
	{
		$data['SMART_TAGS_TITLE'] = fpframework()->_('FPF_SMART_TAGS_TITLE');

		return $data;
	}

	/**
	 * Adds admin media
	 * 
	 * @return  void
	 */
	public function registerMedia()
	{
		wp_enqueue_script(
			'fpf-smart-tags-editor-button',
			FPF_MEDIA_URL . 'admin/js/smart-tags-editor-button.js',
			[ 'wp-editor' ],
			FPF_VERSION,
			false
		);
	}

	/**
	 * Adds the popup at the footer of the page. Appears when you click the Smart Tags button
	 * 
	 * @param   string  $text
	 * 
	 * @return  void
	 */
	public function add_smart_tags_popup()
	{
		// global post is the new/editing box
		global $post;
		
		// get smart tags
		$smartTags = new \FPFramework\Base\SmartTags\SmartTags();

		// register FB Smart Tags
		$smartTags->register('\FireBox\Core\SmartTags', FBOX_BASE_FOLDER . '/Inc/Core/SmartTags', $post);
		
		// get smart tags table layout
		$content = fpframework()->renderer->admin->render('smart_tags/table_list', ['tags' => $smartTags->get()], true);
		
		$payload = [
			'id' => 'fpf-smart-tags-list-modal',
			'title' => 'FPF_SMART_TAGS_TITLE',
			'content' => $content,
			'width' => 70,
			'height' => 70,
			'footer' => '<a href="#" class="fpf-modal-close fpf-button">' . fpframework()->_('FPF_CLOSE') . '</a>'
		];
		
		// render a modal with smart tags table layout and return it
        \FPFramework\Helpers\HTML::renderModal($payload);
	}
}