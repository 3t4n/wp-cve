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

$returnUri = 'index.php?option=com_vikappointments&view=employeesearch&id_employee=' . $this->idEmployee . '&id_service=' . $this->idService;

if ($this->itemid)
{
	$returnUri .= '&Itemid=' . $this->itemid;
}

/**
 * If both the id_employee and id_service attributes are empty,
 * it is assumed that we are in the employees list view, in which
 * the selection of the employee is dynamic.
 *
 * @see 	vapGoToMail() JS function
 */
$data = array(
	/**
	 * The quick contact heading title. Empty by default.
	 *
	 * @var string
	 */
	'title' => JText::sprintf('VAPEMPTALKINGTO', $this->employee->nickname),

	/**
	 * The ID of the service for which the customer may ask a question.
	 *
	 * @var integer
	 */
	// 'id_service' => 0,

	/**
	 * The ID of the employee for which the customer may ask a question.
	 * Provide this attribute ONLY if the id_service attribute is not specified.
	 *
	 * @var integer
	 */
	'id_employee' => $this->idEmployee,

	/**
	 * The plain return URI.
	 *
	 * @var string
	 */
	'return' => $returnUri,

	/**
	 * True to place a disclaimer for GDPR European law, otherwise false.
	 * If not provided, the value will be retrived from the global configuration.
	 *
	 * @var boolean
	 */
	// 'gdpr' => false,

	/**
	 * The Item ID that will be used to route the URL used for AJAX.
	 * If not provided, the current one will be used.
	 *
	 * @var integer
	 */
	'itemid' => $this->itemid,
);

/**
 * The quick contact block is displayed from the layout below:
 * /components/com_vikappointments/layouts/blocks/quickcontact.php
 * 
 * If you need to change something from this layout, just create
 * an override of this layout by following the instructions below:
 * - open the back-end of your Joomla
 * - visit the Extensions > Templates > Templates page
 * - edit the active template
 * - access the "Create Overrides" tab
 * - select Layouts > com_vikappointments > blocks
 * - start editing the quickcontact.php file on your template to create your own layout
 *
 * @since 1.6
 */
echo JLayoutHelper::render('blocks.quickcontact', $data);
