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
$tabs['VAPCONFIGGLOBTITLE1']  = $this->loadTemplate('global_system');
$tabs['VAPCONFIGGLOBTITLE7']  = $this->loadTemplate('global_calendars');
$tabs['GDPR']                 = $this->loadTemplate('global_gdpr');
$tabs['VAPCONFIGGLOBTITLE13'] = $this->loadTemplate('global_timezone');
$tabs['VAPCONFIGGLOBTITLE10'] = $this->loadTemplate('global_sync');
$tabs['VAPCONFIGGLOBTITLE2']  = $this->loadTemplate('global_zip');
$tabs['VAPCOLUMNS']           = $this->loadTemplate('global_columns');

// prepare default icons
$setup->icons['VAPCONFIGGLOBTITLE1']  = 'fas fa-tools';
$setup->icons['VAPCONFIGGLOBTITLE7']  = 'fas fa-calendar';
$setup->icons['GDPR']                 = 'fas fa-gavel';
$setup->icons['VAPCONFIGGLOBTITLE13'] = 'fas fa-globe-americas';
$setup->icons['VAPCONFIGGLOBTITLE10'] = 'fas fa-sync';
$setup->icons['VAPCONFIGGLOBTITLE2']  = 'fas fa-map-marker-alt';
$setup->icons['VAPCOLUMNS']           = 'fas fa-columns';

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewConfigGlobal". The event method receives the
 * view instance as argument.
 *
 * @since 1.6.6
 */
$forms = $this->onDisplayView('Global', $setup);

// create display data
$data = array();
$data['id']     = 1;
$data['active'] = $this->selectedTab == $data['id'];
$data['tabs']   = array_merge($tabs, $forms);
$data['setup']  = $setup;
$data['hook']   = 'Global';

// render configuration pane with apposite layout
echo JLayoutHelper::render('configuration.tabview', $data);
