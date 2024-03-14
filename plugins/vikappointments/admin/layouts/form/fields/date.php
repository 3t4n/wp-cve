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

$vik = VAPApplication::getInstance();

echo $vik->calendar($value, $name, $id, $format = null, $attrs);
