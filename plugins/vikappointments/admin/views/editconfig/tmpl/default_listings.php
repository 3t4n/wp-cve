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
$tabs['VAPCONFIGGLOBTITLE9']  = $this->loadTemplate('listings_employees');
$tabs['VAPCONFIGGLOBTITLE11'] = $this->loadTemplate('listings_services');

// prepare default icons
$setup->icons['VAPCONFIGGLOBTITLE9']  = 'fas fa-th-list';
$setup->icons['VAPCONFIGGLOBTITLE11'] = 'fas fa-th';

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewConfigListings". The event method receives the
 * view instance as argument.
 *
 * @since 1.6.6
 */
$forms = $this->onDisplayView('Listings', $setup);

// create display data
$data = array();
$data['id']     = 5;
$data['active'] = $this->selectedTab == $data['id'];
$data['tabs']   = array_merge($tabs, $forms);
$data['setup']  = $setup;
$data['hook']   = 'Listings';

// render configuration pane with apposite layout
echo JLayoutHelper::render('configuration.tabview', $data);
