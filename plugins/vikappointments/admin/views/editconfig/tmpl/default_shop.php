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
$tabs['VAPCONFIGGLOBTITLE8']  = $this->loadTemplate('shop_details');
$tabs['VAPCONFIGGLOBTITLE14'] = $this->loadTemplate('shop_waitlist');
$tabs['VAPCONFIGGLOBTITLE3']  = $this->loadTemplate('shop_recurrence');
$tabs['VAPCONFIGGLOBTITLE12'] = $this->loadTemplate('shop_reviews');
$tabs['VAPCONFIGGLOBTITLE16'] = $this->loadTemplate('shop_packages');
$tabs['VAPMENUSUBSCRIPTIONS'] = $this->loadTemplate('shop_subscriptions');
$tabs['VAPINVOICE']           = $this->loadTemplate('shop_invoice');

// prepare default icons
$setup->icons['VAPCONFIGGLOBTITLE8']  = 'fas fa-store';
$setup->icons['VAPCONFIGGLOBTITLE14'] = 'fas fa-hourglass-start';
$setup->icons['VAPCONFIGGLOBTITLE3']  = 'fas fa-redo-alt';
$setup->icons['VAPCONFIGGLOBTITLE12'] = 'fas fa-star';
$setup->icons['VAPCONFIGGLOBTITLE16'] = 'fas fa-gift';
$setup->icons['VAPMENUSUBSCRIPTIONS'] = 'fas fa-certificate';
$setup->icons['VAPINVOICE']           = 'fas fa-file-pdf';

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewConfigShop". The event method receives the
 * view instance as argument.
 *
 * @since 1.6.6
 */
$forms = $this->onDisplayView('Shop', $setup);

// create display data
$data = array();
$data['id']     = 4;
$data['active'] = $this->selectedTab == $data['id'];
$data['tabs']   = array_merge($tabs, $forms);
$data['setup']  = $setup;
$data['hook']   = 'Shop';

// render configuration pane with apposite layout
echo JLayoutHelper::render('configuration.tabview', $data);
