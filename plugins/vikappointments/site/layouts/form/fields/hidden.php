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

$name     = !empty($displayData['name'])    ? $displayData['name']  : 'name';
$id       = !empty($displayData['id'])      ? $displayData['id']    : $name;
$value    = isset($displayData['value'])    ? $displayData['value'] : '';
$class    = isset($displayData['class'])    ? $displayData['class'] : '';
$multiple = isset($displayData['multiple']) ? $displayData['multiple'] : false;

// cast value to an array
$value = (array) $value;

foreach ($value as $v)
{
	?>
	<input
		type="hidden"
		name="<?php echo $this->escape($name); ?>"
		id="<?php echo $this->escape($id); ?>"
		value="<?php echo $this->escape($v); ?>"
		class="<?php echo $this->escape($class); ?>"
	/>
	<?php
}
