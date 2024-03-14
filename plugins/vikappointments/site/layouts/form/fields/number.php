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
$min   = isset($displayData['min'])   ? $displayData['min']   : '';
$max   = isset($displayData['max'])   ? $displayData['max']   : '';
$step  = isset($displayData['step'])  ? $displayData['step']  : 1;

?>

<input
	type="number"
	name="<?php echo $this->escape($name); ?>"
	id="<?php echo $this->escape($id); ?>"
	value="<?php echo (float) $value; ?>"
	size="40"
	class="<?php echo $this->escape($class); ?>"
	<?php echo strlen($min) ? 'min="' . (float) $min . '"' : ''; ?>
	<?php echo strlen($max) ? 'max="' . (float) $max . '"' : ''; ?>
	step="<?php echo $step ? 'any' : '1'; ?>"
	aria-labelledby="<?php echo $id; ?>-label"
/>
