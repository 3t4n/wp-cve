<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  html.form
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

$name  		= isset($displayData['name'])     ? $displayData['name']     : '';
$value 		= isset($displayData['value'])    ? $displayData['value']    : '';
$id 		= isset($displayData['id'])       ? $displayData['id']       : '';
$class 		= isset($displayData['class'])    ? $displayData['class']    : '';
$req 		= isset($displayData['required']) ? $displayData['required'] : 0;
$options 	= isset($displayData['options'])  ? $displayData['options']  : '';
$disabled 	= isset($displayData['disabled']) ? $displayData['disabled'] : false;

