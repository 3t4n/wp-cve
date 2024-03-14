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
	 * An object containing the following keys:
	 * - rows  the list containing the reviews to show;
	 * - size  the total number of reviews for this employee/service.
	 *
	 * @var object
	 */
	'reviews' => $this->employee->reviews,

	/**
	 * True if the current customer can leave a review for the selected
	 * service or employee. If not provided, false by default.
	 *
	 * @var boolean
	 */
	'canLeave' => $this->userCanLeaveReview,

	/**
	 * An array containing the ordering links to sort the reviews.
	 * Each element of the list must be an associative array containing:
	 * - uri     the URI to reload the reviews with a different ordering;	
	 * - active  true if the current ordering is active;
	 * - mode    the ordering mode (ASC or DESC).
	 *
	 * @var array
	 */
	'orderingLinks' => $this->reviewsOrderingLinks,

	/**
	 * The ID of the service for which the customer may leave a review.
	 *
	 * @var integer
	 */
	// 'id_service' => 0,

	/**
	 * The ID of the employee for which the customer may leave a review.
	 * Provide this attribute ONLY if the id_service attribute is not specified.
	 *
	 * @var integer
	 */
	'id_employee' => $this->idEmployee,

	/**
	 * The subtitle that describes the average ratio and the total count of reviews.
	 *
	 * @var string
	 */
	'subtitle' => $this->displayData['subtitle'],

	/**
	 * The date time format used to display when the reviews were created.
	 * If not provided, the military format will be used (Y-m-d H:i).
	 *
	 * @var string
	 */
	'datetime_format' => JText::translate('DATE_FORMAT_LC2'),

	/**
	 * The Item ID that will be used to route the URL used for AJAX.
	 * If not provided, the current one will be used.
	 *
	 * @var integer
	 */
	'itemid' => $this->itemid,
);

/**
 * The reviews block is displayed from the layout below:
 * /components/com_vikappointments/layouts/blocks/reviews.php
 * 
 * If you need to change something from this layout, just create
 * an override of this layout by following the instructions below:
 * - open the back-end of your Joomla
 * - visit the Extensions > Templates > Templates page
 * - edit the active template
 * - access the "Create Overrides" tab
 * - select Layouts > com_vikappointments > blocks
 * - start editing the reviews.php file on your template to create your own layout
 *
 * @since 1.6
 */
echo JLayoutHelper::render('blocks.reviews', $data);
