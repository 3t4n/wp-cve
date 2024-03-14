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

$config = VAPFactory::getConfig();

// check whether the recurrence is enabled for the selected service
$recurrence = $config->getBool('enablerecur') && $this->service->use_recurrence;

$data = array(
	/**
	 * True if the cart system is enabled (false by default). When enabled,
	 * it will be displayed a button to add multiple services into the cart.
	 *
	 * @var boolean
	 */
	'cartEnabled' => $config->getBool('enablecart'),

	/**
	 * True if the cart doesn't contain yet any appointment. When false, it
	 * will be possible to proceed to the checkout (the cart must be enabled too).
	 *
	 * @var boolean
	 */
	'cartEmpty' => $this->isCartEmpty,

	/**
	 * True if the waiting list is enabled (false by default). When enabled,
	 * it will be displayed a button to allow the customers register a
	 * subscription for the current employee/service.
	 *
	 * @var boolean
	 */
	'waitlistEnabled' => VikAppointments::isWaitingList(),

	/**
	 * True if the recurrence is enabled (false by default). When enabled,
	 * the customers will be able to book an appointment for the selected
	 * employee/service with recurrence.
	 *
	 * @var boolean
	 */
	'recurrenceEnabled' => $recurrence,

	/**
	 * An associative array containing the recurrence params:
	 * - repeat  a list containing the values allowed to start the recurrence;
	 * - for     a list containing the values allowed to end the recurrence;
	 * - min     the minimum recurrence number;
	 * - max     the maximum recurrence number.
	 *
	 * @var array
	 */
	'recurrenceParams' => $recurrence ? VikAppointments::getRecurrenceParams() : array(),

	/**
	 * The Item ID that will be used to route the URL used for AJAX.
	 * If not provided, the current one will be used.
	 *
	 * @var integer
	 */
	'itemid' => $this->itemid,
);

/**
 * The checkout block is displayed from the layout below:
 * /components/com_vikappointments/layouts/blocks/checkout.php
 * 
 * If you need to change something from this layout, just create
 * an override of this layout by following the instructions below:
 * - open the back-end of your Joomla
 * - visit the Extensions > Templates > Templates page
 * - edit the active template
 * - access the "Create Overrides" tab
 * - select Layouts > com_vikappointments > blocks
 * - start editing the checkout.php file on your template to create your own layout
 *
 * @since 1.6
 */
echo JLayoutHelper::render('blocks.checkout', $data);
