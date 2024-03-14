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

$name   = !empty($displayData['name'])  ? $displayData['name']   : 'name';
$id     = !empty($displayData['id'])    ? $displayData['id']     : $name;
$value  = isset($displayData['value'])  ? $displayData['value']  : '';
$class  = isset($displayData['class'])  ? $displayData['class']  : '';
$width  = isset($displayData['width'])  ? $displayData['width']  : '';
$height = isset($displayData['height']) ? $displayData['height'] : '80px';

$style = array();

if ($width)
{
	$style[] = 'width: ' . $width . (is_numeric($width) ? 'px' : '') . ';';
}

if ($height)
{
	$style[] = 'height: ' . $height . (is_numeric($height) ? 'px' : '') . ';';
}

?>

<textarea
	name="<?php echo $this->escape($name); ?>"
	id="<?php echo $this->escape($id); ?>"
	class="<?php echo $this->escape($class); ?>"
	style="<?php echo $this->escape(implode(' ', $style)); ?>"
><?php echo htmlspecialchars($value, ENT_COMPAT, 'UTF-8'); ?></textarea>
