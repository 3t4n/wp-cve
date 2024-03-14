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

class Text extends \FireBox\Core\Form\Fields\Field
{
	protected $type = 'text';
	
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
			placeholder="<?php esc_attr_e($this->getOptionValue('placeholder')); ?>"
			class="<?php esc_attr_e(implode(' ', $this->getOptionValue('input_css_class'))); ?>"
		/>
		<?php
	}
}