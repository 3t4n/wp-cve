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

VAPLoader::import('libraries.menu.leftboard.custom.license');

/**
 * Extends the CustomShape class to display a button to check the WordPress software license.
 *
 * @since 1.6.3
 */
class HorizontalCustomShapeLicense extends LeftboardCustomShapeLicense
{
	
}
