<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Base\Fields;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Base\Field;
use FPFramework\Libs\Registry;

class MediaUploader extends Field
{
	/**
	 * Set specific field options
	 * 
	 * @param   array  $options
	 * 
	 * @return  void
	 */
	protected function setFieldOptions($options)
	{
		$options = new Registry($options);
		
		$this->field_options = [
			'media' => $options->get('media', 'image'),
		];
	}
	
	/**
	 * Runs before field renders
	 * 
	 * @return  void
	 */
	public function onBeforeRender()
	{
		// media uploader
		wp_enqueue_media();

		// CSS
		wp_register_style(
			'fpframework-mediauploader-field',
			FPF_MEDIA_URL . 'admin/css/fpf_mediauploader.css',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_style( 'fpframework-mediauploader-field' );

		// JS
		wp_register_script(
			'fpframework-mediauploader-field',
			FPF_MEDIA_URL . 'admin/js/fpf_mediauploader.js',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_script( 'fpframework-mediauploader-field' );
	}
}