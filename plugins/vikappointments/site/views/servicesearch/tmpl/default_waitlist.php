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
	 * The ID of the service for which the customer is interested to (required).
	 *
	 * @var integer
	 */
	'id_service' => $this->idService,

	/**
	 * The ID of the employee for which the customer is interested to (optional).
	 *
	 * @var integer
	 */
	'id_employee' => $this->idEmployee,

	/**
	 * An optional title to use for the modal box (optional).
	 *
	 * @var string
	 */
	'title' => JText::translate('VAPWAITLISTADDBUTTON'),

	/**
	 * The Item ID that will be used to route the URL used for AJAX.
	 * If not provided, the current one will be used.
	 * 
	 * @var integer
	 */
	'itemid' => $this->itemid,
);

/**
 * The waiting list modal is displayed from the layout below:
 * /components/com_vikappointments/layouts/blocks/waitlist.php
 * 
 * If you need to change something from this layout, just create
 * an override of this layout by following the instructions below:
 * - open the back-end of your Joomla
 * - visit the Extensions > Templates > Templates page
 * - edit the active template
 * - access the "Create Overrides" tab
 * - select Layouts > com_vikappointments > blocks
 * - start editing the waitlist.php file on your template to create your own layout
 *
 * @since 1.6
 */
echo JLayoutHelper::render('blocks.waitlist', $data);
