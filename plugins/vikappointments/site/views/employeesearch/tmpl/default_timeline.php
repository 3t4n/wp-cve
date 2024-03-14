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
	 * A pre-selected check-in hour.
	 *
	 * @var integer
	 */
	'hour' => $this->hour,

	/**
	 * A pre-selected check-in minute.
	 *
	 * @var integer
	 */
	'min' => $this->min,

	/**
	 * Flag used to check whether the selected service supports the
	 * check-out selection through the dropdown timeline.
	 *
	 * @var boolean
	 */
	'checkout' => $this->service ? $this->service->checkout_selection : false,
);

/**
 * The timeline block is displayed from the layout below:
 * /components/com_vikappointments/layouts/blocks/timeline.php
 * 
 * If you need to change something from this layout, just create
 * an override of this layout by following the instructions below:
 * - open the back-end of your Joomla
 * - visit the Extensions > Templates > Templates page
 * - edit the active template
 * - access the "Create Overrides" tab
 * - select Layouts > com_vikappointments > blocks
 * - start editing the timeline.php file on your template to create your own layout
 *
 * @since 1.6
 */
echo JLayoutHelper::render('blocks.timeline', $data);
