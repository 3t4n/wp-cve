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

/**
 * Template file used to display a form to redeem a coupon code.
 *
 * @since 1.7
 */

$data = array(
	/**
	 * The controller to reach to redeem the coupon code. The specified controller must
	 * provide a task (method) called "redeemcoupon".
	 *
	 * @var string
	 */
	'controller' => 'confirmapp',

	/**
	 * The Item ID that will be used to route the URL used for SEF.
	 *
	 * @var integer|null
	 */
	'itemid' => $this->itemid,
);

/**
 * This form is displayed from the layout below:
 * /components/com_vikappointments/layouts/blocks/couponform.php
 * 
 * If you need to change something from this layout, just create
 * an override of this layout by following the instructions below:
 * - open the back-end of your Joomla
 * - visit the Extensions > Templates > Templates page
 * - edit the active template
 * - access the "Create Overrides" tab
 * - select Layouts > com_vikappointments > blocks
 * - start editing the couponform.php file on your template to create your own layout
 *
 * @since 1.7
 */
echo JLayoutHelper::render('blocks.couponform', $data);
