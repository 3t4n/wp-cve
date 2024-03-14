<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

$name     = !empty($displayData['name'])    ? $displayData['name']     : 'name';
$id       = !empty($displayData['id'])      ? $displayData['id']       : $name;
$value    = isset($displayData['value'])    ? $displayData['value']    : '';
$class    = isset($displayData['class'])    ? $displayData['class']    : '';
$multiple = isset($displayData['multiple']) ? $displayData['multiple'] : false;
$options  = isset($displayData['options'])  ? $displayData['options']  : array();

if ($multiple)
{
	$value = (array) $value;
}

// check if we have an associative array
$is_assoc = (array_keys($options) !== range(0, count($options) - 1));

// check whether we should build an array of options
if ($is_assoc || ($options && is_scalar($options[0])))
{
	$tmp = array();

	foreach ($options as $optValue => $optText)
	{
		if (!$is_assoc)
		{
			// in case of linear array, use the option text as value
			$optValue = $optText;
		}

		$tmp[] = JHtml::fetch('select.option', $optValue, $optText);
	}

	$options = $tmp;
}

if ($options && !strlen($options[0]->value))
{
	// use default placeholder provided by the CMS
	$options[0]->text = JText::translate('JGLOBAL_SELECT_AN_OPTION');
}

?>

<select
	name="<?php echo $this->escape($name); ?>"
	id="<?php echo $this->escape($id); ?>"
	class="<?php echo $this->escape($class); ?>"
	aria-labelledby="<?php echo $id; ?>-label"
	<?php echo $multiple ? 'multiple' : ''; ?>
>
	<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $value); ?>
</select>
