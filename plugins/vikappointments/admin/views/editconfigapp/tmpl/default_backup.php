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

// create object to pass to the hook, so that external plugins
// can extend the appearance of any additional tab
$setup = new stdClass;
$setup->icons = array();

$tabs = array();
$tabs['VAPCONFIGCRONTITLE1'] = $this->loadTemplate('backup_basic');

// prepare default icons
$setup->icons['VAPCONFIGCRONTITLE1'] = 'fas fa-tools';

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewConfigappBackup". The event method receives the
 * view instance as argument.
 *
 * @since 1.7.1
 */
$forms = $this->onDisplayView('Backup', $setup);

// create display data
$data = array();
$data['id']     = 3;
$data['active'] = $this->selectedTab == $data['id'];
$data['tabs']   = array_merge($tabs, $forms);
$data['setup']  = $setup;
$data['hook']   = 'Backup';
$data['suffix'] = 'app';

// render configuration pane with apposite layout
echo JLayoutHelper::render('configuration.tabview', $data);
