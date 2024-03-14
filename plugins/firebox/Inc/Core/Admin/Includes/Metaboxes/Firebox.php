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

namespace FireBox\Core\Admin\Includes\Metaboxes;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FireBox\Core\Admin\Forms\FireBox as FireBoxForm;
use FPFramework\Base\Ui\Tabs;
use FPFramework\Base\FieldsParser;

class FireBox
{
	/**
	 * The slug
	 * 
	 * @var  String
	 */
	public $slug = 'firebox_settings';

	/**
	 * The title
	 * 
	 * @var  String
	 */
	public $title;

	/**
	 * The callback
	 * 
	 * @var  String
	 */
	public $callback = 'render_settings_meta_box';

	/**
	 * The screen
	 * 
	 * @var  String
	 */
	public $screen = 'firebox';

	/**
	 * The context
	 * 
	 * @var  String
	 */
	public $context = 'advanced';

	/**
	 * The priority
	 * 
	 * @var  String
	 */
	public $priority = 'high';

	public function __construct()
	{
		$this->title = firebox()->_('FB_SETTINGS_PAGE_TITLE');
	}

	/**
	 * FireBox Metabox Settings.
	 * Used by MetaboxManager to retrieve the settings and validate during form saving process.
	 * 
	 * @return  array
	 */
	public function getSettings()
	{
		return FireBoxForm::getSettings();
	}

	/**
	 * Render the meta box
	 * 
	 * @return  void
	 */
	public function render_settings_meta_box()
	{
		$fieldsParser = new FieldsParser([
			'bind_data' => FireBoxForm::getBindData(),
			'fields_path' => apply_filters('firebox/metabox/firebox/fields_path_modify', ['\\FireBox\\Core\\Fields\\']),
			'fields_name_prefix' => \FPFramework\Admin\Includes\MetaboxManager::$fields_prefix
		]);
		
		$settings = $this->getSettings();
		foreach ($settings['data'] as $key => $value)
		{
			ob_start();
			$fieldsParser->renderContentFields($value);
			$html = ob_get_contents();
			ob_end_clean();

			$settings['data'][$key]['title'] = $value['title'];
			$settings['data'][$key]['content'] = $html;
		}

		// render settings as tabs
		$tabs = new Tabs($settings);
		?>
		<div class="fpf-metaboxes fpf-content-wrapper">
			<?php
			do_action('firebox/editor/before_tabs');

			echo $tabs->render();
			wp_nonce_field('fpframework_metaboxes_save_data', '_fpframework_metabox_nonce');

			do_action('firebox/editor/after_tabs');
			?>
		</div>
		<?php
	}
}