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

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewReservationDetails".
 * It is also possible to use "onDisplayViewReservationDetailsSidebar"
 * to include any additional fieldsets within the right sidebar.
 * The event method receives the view instance as argument.
 *
 * @since 1.7
 */
$detailsForms = $this->onDisplayView('Details');
$sidebarForms = $this->onDisplayView('DetailsSidebar');

?>

<div class="row-fluid">

	<!-- MAIN -->

	<div class="span7 full-width">

		<!-- APPOINTMENTS -->

		<div class="row-fluid">
			<div class="span12">
				<?php
				echo $vik->openFieldset(JText::translate('VAPMENUTITLEHEADER2'));
				echo $this->loadTemplate('details_appointments');
				echo $vik->closeFieldset();
				?>
			</div>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewReservationDetails","type":"fieldset"} -->

		<?php
		/**
		 * Iterate remaining forms to be displayed within
		 * the main panel.
		 *
		 * @since 1.7
		 */
		foreach ($detailsForms as $formName => $formHtml)
		{
			$title = JText::translate($formName);
			?>
			<div class="row-fluid">
				<div class="span12">
					<?php
					echo $vik->openFieldset($title);
					echo $formHtml;
					echo $vik->closeFieldset();
					?>
				</div>
			</div>
			<?php
		}
		?>

	</div>

	<!-- SIDEBAR -->

	<div class="span5 full-width">

		<!-- ORDER -->

		<div class="row-fluid">
			<div class="span12">
				<?php
				echo $vik->openFieldset(JText::translate('VAPORDER'));
				echo $this->loadTemplate('details_order');
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewReservation","key":"order","type":"field"} -->

				<?php	
				/**
				 * Look for any additional fields to be pushed within
				 * the "Order" fieldset (left-side).
				 *
				 * NOTE: retrieved from "onDisplayViewReservation" hook.
				 *
				 * @since 1.6.6
				 */
				if (isset($this->forms['order']))
				{
					echo $this->forms['order'];

					// unset details form to avoid displaying it twice
					unset($this->forms['order']);
				}
					
				echo $vik->closeFieldset();
				?>
			</div>
		</div>

		<!-- BILLING -->

		<div class="row-fluid">
			<div class="span12">
				<?php
				echo $vik->openFieldset(JText::translate('VAPMANAGECUSTOMERTITLE2'));
				echo $this->loadTemplate('details_billing');
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewReservation","key":"billing","type":"field"} -->

				<?php	
				/**
				 * Look for any additional fields to be pushed within
				 * the "Billing" fieldset (left-side).
				 *
				 * NOTE: retrieved from "onDisplayViewReservation" hook.
				 *
				 * @since 1.6.6
				 */
				if (isset($this->forms['billing']))
				{
					echo $this->forms['billing'];

					// unset details form to avoid displaying it twice
					unset($this->forms['billing']);
				}
					
				echo $vik->closeFieldset();
				?>
			</div>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewReservationDetailsSidebar","type":"fieldset"} -->

		<?php
		/**
		 * Iterate remaining forms to be displayed within
		 * the sidebar (below "Billing" fieldset).
		 *
		 * @since 1.7
		 */
		foreach ($sidebarForms as $formName => $formHtml)
		{
			$title = JText::translate($formName);
			?>
			<div class="row-fluid">
				<div class="span12">
					<?php
					echo $vik->openFieldset($title);
					echo $formHtml;
					echo $vik->closeFieldset();
					?>
				</div>
			</div>
			<?php
		}
		?>

	</div>

</div>
