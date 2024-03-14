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

VAPLoader::import('libraries.cron.dispatcher');
VAPLoader::import('libraries.cron.job');
VAPLoader::import('libraries.cron.response');
VAPLoader::import('libraries.cron.formfield');
VAPLoader::import('libraries.cron.formfieldconstraints');
VAPLoader::import('libraries.cron.formbuilder');
