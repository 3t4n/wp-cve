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

$vik = VAPApplication::getInstance();

?>

<div class="row-fluid">

	<!-- MAIN -->

	<div class="span8 full-width">

		<!-- HISTORY -->

		<div class="row-fluid">
			<div class="span12">
				<?php
				echo $vik->openEmptyFieldset();
				echo $this->loadTemplate('orderstatus_history');
				echo $vik->closeEmptyFieldset();
				?>
			</div>
		</div>

	</div>

	<!-- SIDEBAR -->

	<div class="span4 full-width">

		<!-- STATUS -->

		<div class="row-fluid">
			<div class="span12">
				<?php
				echo $vik->openEmptyFieldset('form-vertical');
				echo $this->loadTemplate('orderstatus_manage');
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewReservation","key":"orderstatus","type":"field"} -->

				<?php	
				/**
				 * Look for any additional fields to be pushed within
				 * the "Order Status" sidebar fieldset (left-side).
				 *
				 * NOTE: retrieved from "onDisplayViewReservation" hook.
				 *
				 * @since 1.6.6
				 */
				if (isset($this->forms['orderstatus']))
				{
					echo $this->forms['orderstatus'];

					// unset details form to avoid displaying it twice
					unset($this->forms['orderstatus']);
				}
					
				echo $vik->closeEmptyFieldset();
				?>
			</div>
		</div>

	</div>

</div>
