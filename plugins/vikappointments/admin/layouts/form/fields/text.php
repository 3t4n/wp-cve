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

$name     = !empty($displayData['name'])       ? $displayData['name']        : 'name';
$id       = !empty($displayData['id'])         ? $displayData['id']          : $name;
$type     = !empty($displayData['type'])       ? $displayData['type']        : 'text';
$value    = isset($displayData['value'])       ? $displayData['value']       : '';
$class    = isset($displayData['class'])       ? $displayData['class']       : '';
$hint     = isset($displayData['placeholder']) ? $displayData['placeholder'] : '';
$maxlen   = isset($displayData['maxlength'])   ? $displayData['maxlength']   : false;
$disabled = isset($displayData['disabled'])    ? $displayData['disabled']    : false;
$readonly = isset($displayData['readonly'])    ? $displayData['readonly']    : false;
$style    = isset($displayData['style'])       ? $displayData['style']       : '';

?>

<input
	type="<?php echo $this->escape($type); ?>"
	name="<?php echo $this->escape($name); ?>"
	id="<?php echo $this->escape($id); ?>"
	value="<?php echo $this->escape($value); ?>"
	size="40"
	class="<?php echo $this->escape($class); ?>"
    <?php echo $style ? 'style="' . $this->escape($style) . '"' : ''; ?>
    <?php echo $hint ? 'placeholder="' . $this->escape($hint) . '"' : ''; ?>
    <?php echo $maxlen ? 'maxlength="' . abs((int) $maxlen) . '"' : ''; ?>
    <?php echo $disabled ? 'disabled' : ''; ?>
    <?php echo $readonly && !$disabled ? 'readonly' : ''; ?>
/>