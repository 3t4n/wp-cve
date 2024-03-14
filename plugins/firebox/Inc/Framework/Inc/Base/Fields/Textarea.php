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

class Textarea extends Field
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
			'mode' => $options->get('mode', null),
			'rows' => $options->get('rows'),
			'filter' => $options->get('filter', 'textarea')
		];
	}

	/**
	 * Runs before field renders.
	 * 
	 * @return  void
	 */
	public function onBeforeRender()
	{
		// Run only if a textarea mode is given
		if (!$mode = $this->options['mode'])
		{
			return;
		}

		$mode = 'text/html';

		if ($mode == 'php')
		{
			$mode = 'application/x-httpd-php';
		}
		else if ($mode == 'javascript')
		{
			$mode = 'text/javascript';
		}
		else if ($mode == 'css')
		{
			$mode = 'text/css';
		}

		// load code editor
		$settings = wp_enqueue_code_editor([ 'type' => 'text/html' ]);

		// Bail if user disabled CodeMirror
		if ( false === $settings ) {
			return;
		}
		
		// JS
		wp_register_script(
			'fpframework-textarea-field',
			FPF_MEDIA_URL . 'admin/js/fpf_textarea.js',
			[],
			FPF_VERSION,
			false
		);
		wp_enqueue_script( 'fpframework-textarea-field' );
	}
}