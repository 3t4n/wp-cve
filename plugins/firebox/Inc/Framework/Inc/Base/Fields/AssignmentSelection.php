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

use FPFramework\Libs\Registry;

class AssignmentSelection extends Toggle
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
		parent::setFieldOptions($options);
		
		$options = new Registry($options);

		$choices = [
			'0' => 'FPF_DISABLED',
			'1' => 'FPF_INCLUDE',
			'2' => 'FPF_EXCLUDE'
		];
		
		$this->field_options = array_merge($this->field_options, [
			'choices' => $options->get('choices', $choices)
		]);
	}
}