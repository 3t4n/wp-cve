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

class Dropdown extends \FireBox\Core\Form\Fields\Field
{
	protected $type = 'dropdown';
	
	public function __construct($options = [])
	{
        parent::__construct($options);

		$this->options['choices'] = $this->getChoices();
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
	 * Returns the field input.
	 * 
	 * @return  void
	 */
	public function getInput()
	{
		if (!$choices = $this->getOptionValue('choices'))
		{
			return;
		}

		$selectedValue = $this->getOptionValue('value') ? $this->getOptionValue('value') : ($this->getOptionValue('placeholder') ? '' : '');
		?>
		<div class="fb-form-select-wrapper">
			<select
				type="text"
				id="fb-form-input-<?php esc_attr_e($this->getOptionValue('id')); ?>"
				name="fb_form[<?php esc_attr_e($this->getOptionValue('name')); ?>]"
				class="fb-form-input<?php echo $this->getOptionValue('input_css_class') ? ' ' . esc_attr(implode(' ', $this->getOptionValue('input_css_class'))) : ''; ?>"
				<?php if ($this->getOptionValue('required')): ?>
					required
				<?php endif; ?>
			>
				<?php
				foreach ($choices as $choice)
				{
					$value = !empty($choice['value']) ? $choice['value'] : '';
					$isDisabled = isset($choice['disabled']) ? $choice['disabled'] : false;
					$isSelected = (string) $selectedValue === (string) $value;
					?>
					<option
						value="<?php esc_attr_e($value); ?>"
						<?php echo $isSelected ? ' selected' : ''; ?>
						<?php echo $isDisabled ? ' disabled' : ''; ?>
					>
						<?php esc_html_e($choice['label']); ?>
					</option>
					<?php
				}
				?>
			</select>
		</div>
		<?php
	}

	protected function getChoices()
	{
		if (!$choices = $this->getOptionValue('choices'))
		{
			return;
		}

		$placeholder = $this->getOptionValue('placeholder');

        foreach ($choices as &$choice)
        {
            if (!isset($choice['label']) || $choice['label'] == '')
            {
                continue;
            }

			// Replace Smart Tags
			$choice = \FPFramework\Base\SmartTags\SmartTags::getInstance()->replace($choice);

            $label = trim($choice['label']);
            $value = !isset($choice['value']) || $choice['value'] == '' ? wp_strip_all_tags($label) : $choice['value'];

            $choice = [
                'label'      => $label,
                'value'      => $value,
                'selected'   => (isset($choice['default']) && $choice['default'] && !$placeholder) ? true : false
			];
        }

		if ($placeholder)
		{
            array_unshift($choices, array(
                'label'    => trim($placeholder),
                'value'    => '',
                'selected' => true,
                'disabled' => true
            ));
		}

		return $choices;
	}

	public function prepareValueHTML($value)
	{
        if (is_array($value))
        {
            foreach ($value as &$value_)
            {
                $value_ = $this->findChoiceLabelByValue($value_);
            }
        }
		else 
        {
            $value = $this->findChoiceLabelByValue($value);
        }

        return parent::prepareValueHTML($value);
	}
	
    private function findChoiceLabelByValue($value)
    {
        // In multiple choice fields, the value can't be empty.
        if ($value == '')
        {
            return $value;
        }

        if ($choices = $this->getOptionValue('choices'))
        {
            foreach ($choices as $choice)
            {
                // We might lowercase both values?
                if ($choice['value'] == $value)
                {
                    return $choice['label'];
                }
            }
        }

        // If we can't assosiacte the given value with a label, return the raw value as a fallback.
        return $value;
    }
}