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

namespace FireBox\Core\Form\Fields\Fields;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Base\Filter;

class Hidden extends \FireBox\Core\Form\Fields\Field
{
	protected $type = 'hidden';
	
	protected function getFieldOptions()
	{
		return [
			'required' => isset($this->options['required']) ? $this->options['required'] : false
		];
	}

	/**
	 * Validate the field.
	 * 
	 * @param   mixed  $value
	 * 
	 * @return  void
	 */
	public function validate(&$value = '')
	{
		$value = Filter::getInstance()->clean($value);
		
		return parent::validate($value);
	}

	/**	
	 * Return the field label.
	 * 
	 * @return  string
	 */
	public function getLabel()
	{
		return $this->getOptionValue('name');
	}

	/**
	 * Returns the field input.
	 * 
	 * @return  void
	 */
	public function getInput()
	{
		?>
		<input
			type="text"
			name="fb_form[<?php esc_attr_e($this->getOptionValue('name')); ?>]"
			value="<?php esc_attr_e($this->getOptionValue('value')); ?>"
			class="<?php esc_attr_e(implode(' ', $this->getOptionValue('input_css_class'))); ?>"
		/>
		<?php
	}
}