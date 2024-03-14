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

$name  = !empty($displayData['name']) ? $displayData['name']  : 'name';
$id    = !empty($displayData['id'])   ? $displayData['id']    : $name;
$value = isset($displayData['value']) ? $displayData['value'] : '';
$class = isset($displayData['class']) ? $displayData['class'] : '';
$attrs = isset($displayData['attrs']) ? $displayData['attrs'] : array();

if ($class)
{
	if (!empty($attrs['class']))
	{
		$attrs['class'] .= ' ' . $class;
	}
	else
	{
		$attrs['class'] = $class;
	}
}

// prepare jQuery datepicker options
$options = array(
	/**
	 * Whether the year should be rendered as a dropdown instead of text.
	 * Use the yearRange option to control which years are made available
	 * for selection.
	 *
	 * Since the calendar field is usually used for Birth dates, the year
	 * selection will be enabled by default, so that the date can be filled
	 * without having to manually enter the year through the keyboard.
	 *
	 * @var boolean
	 *
	 * @link https://api.jqueryui.com/datepicker/#option-changeYear
	 */
	'changeYear' => true,

	/**
	 * The range of years displayed in the year drop-down: either relative
	 * to today's year ("-nn:+nn"), relative to the currently selected year
	 * ("c-nn:c+nn"), absolute ("nnnn:nnnn"), or combinations of these formats
	 * ("nnnn:-nn").
	 *
	 * Since the calendar field is usually used for Birth dates, the year
	 * selection won't allow future years and will go back up to 100 years.
	 *
	 * @var boolean
	 *
	 * @link https://api.jqueryui.com/datepicker/#option-yearRange
	 */
	'yearRange' => '-100:+0',
);

// init datepicker
JHtml::fetch('vaphtml.sitescripts.calendar', '#' . $id . ':input', $options);

?>

<input
	type="text"
	name="<?php echo $this->escape($name); ?>"
	id="<?php echo $this->escape($id); ?>"
	value="<?php echo $this->escape($value); ?>"
	size="40"
	class="<?php echo $this->escape($class); ?>"
	aria-labelledby="<?php echo $id; ?>-label"
/>
