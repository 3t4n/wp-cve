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

class Comparator extends Dropdown
{
	/**
	 * Override the layout of the field.
	 * 
	 * @var  string
	 */
	protected $layout_override = 'FPFramework\Base\Fields\Dropdown';
	
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
			'includes' => 'FPF_IS',
			'not_includes' => 'FPF_IS_NOT',
		];
		
		$this->field_options = array_merge($this->field_options, [
			'label' => $options->get('label', fpframework()->_('FPF_MATCH')),
			'choices' => $options->get('choices', $choices)
		]);
	}
}