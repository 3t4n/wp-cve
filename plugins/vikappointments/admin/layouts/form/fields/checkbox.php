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

$name    = !empty($displayData['name'])   ? $displayData['name']    : 'name';
$checked = isset($displayData['checked']) ? $displayData['checked'] : false;

$vik = VAPApplication::getInstance();

$yes = $vik->initRadioElement('', '', $checked);
$no  = $vik->initRadioElement('', '', !$checked);

echo $vik->radioYesNo($name, $yes, $no);
