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

$vik = VAPApplication::getInstance();

$label    = isset($displayData['label'])    ? $displayData['label']    : '';
$required = isset($displayData['required']) ? $displayData['required'] : false;

if ($required && $label)
{
	$label .= '*';
}

if (!empty($displayData['description']))
{
	// append tooltip to label
	$label .= $vik->createPopover(array(
		'title'   => $label,
		'content' => $displayData['description'],
	));
}

echo $vik->openControl($label);
