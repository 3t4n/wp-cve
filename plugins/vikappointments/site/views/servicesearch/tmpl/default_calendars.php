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

$data = array(
	/**
	 * The calendar object fetched by the view model.
	 *
	 * @var object
	 */
	'calendar' => $this->calendar,

	/**
	 * The service ID.
	 *
	 * @var integer
	 */
	'id_service' => $this->idService,

	/**
	 * The employee ID.
	 *
	 * @var integer
	 */
	'id_employee' => $this->idEmployee,

	/**
	 * The selected check-in date.
	 *
	 * @var int|null
	 */
	'date' => $this->date,

	/**
	 * The selected check-in hour.
	 *
	 * @var int|null
	 */
	'hour' => $this->hour,

	/**
	 * The selected check-in minutes.
	 *
	 * @var int|null
	 */
	'min' => $this->min,
);

/**
 * The calendar block is displayed from the layout below:
 * /components/com_vikappointments/layouts/blocks/calendar/[NAME].php
 * where [NAME] may vary according to the configuration of the calendar.
 * 
 * If you need to change something from this layout, just create
 * an override of this layout by following the instructions below:
 * - open the back-end of your Joomla
 * - visit the Extensions > Templates > Templates page
 * - edit the active template
 * - access the "Create Overrides" tab
 * - select Layouts > com_vikappointments > blocks
 * - start editing the calendar/[NAME].php file on your template to create your own layout
 *
 * @since 1.7
 */
echo JLayoutHelper::render('blocks.calendar.' . $this->calendar->layout, $data);
