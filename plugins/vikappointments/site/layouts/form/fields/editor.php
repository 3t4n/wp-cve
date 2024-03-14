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
$value  = isset($displayData['value'])  ? $displayData['value']  : '';
$width  = isset($displayData['width'])  ? $displayData['width']  : '100%';
$height = isset($displayData['height']) ? $displayData['height'] : 550;
$rows   = isset($displayData['rows'])   ? $displayData['rows']   : 30;
$cols   = isset($displayData['cols'])   ? $displayData['cols']   : 30;

// get system editor
$editor = VAPApplication::getInstance()->getEditor();

// display editor
echo $editor->display($name, $value, $width, $height, $rows, $cols);
