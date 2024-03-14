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

$service = $this->service;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewServiceDetails".
 * It is also possible to use "onDisplayViewServiceDetailsSidebar"
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

	<div class="span7">

		<!-- SERVICE -->

		<div class="row-fluid">
			<div class="span12">
				<?php
				echo $vik->openFieldset(JText::translate('VAPMANAGERESERVATION4'));
				echo $this->loadTemplate('details_service');
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewService","key":"service","type":"field"} -->

				<?php	
				/**
				 * Look for any additional fields to be pushed within
				 * the "Service" fieldset (left-side).
				 *
				 * NOTE: retrieved from "onDisplayViewService" hook.
				 *
				 * @since 1.7
				 */
				if (isset($this->forms['service']))
				{
					echo $this->forms['service'];

					// unset details form to avoid displaying it twice
					unset($this->forms['service']);
				}
					
				echo $vik->closeFieldset();
				?>
			</div>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewServiceDetails","type":"fieldset"} -->

		<?php
		/**
		 * Iterate remaining forms to be displayed within
		 * the main panel (below "service" fieldset).
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

		<!-- DESCRIPTION -->

		<div class="row-fluid">
			<div class="span12">
				<?php
				echo $vik->openFieldset(JText::translate('VAPMANAGESERVICE3'));
				echo $vik->getEditor()->display('description', $service->description, '100%', 550, 40, 20);
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewService","key":"description","type":"field"} -->
				
				<?php
				/**
				 * Look for any additional fields to be pushed within
				 * the "Description" fieldset (left-side).
				 *
				 * NOTE: retrieved from "onDisplayViewService" hook.
				 *
				 * @since 1.6.6
				 */
				if (isset($this->forms['description']))
				{
					echo $this->forms['description'];

					// unset details form to avoid displaying it twice
					unset($this->forms['description']);
				}
					
				echo $vik->closeFieldset();
				?>
			</div>
		</div>

	</div>

	<!-- SIDEBAR -->

	<div class="span5 full-width">

		<!-- BOOKING -->

		<div class="row-fluid">
			<div class="span12">
				<?php
				echo $vik->openFieldset(JText::translate('VAPBOOKING'));
				echo $this->loadTemplate('details_booking');
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewService","key":"booking","type":"field"} -->
				
				<?php
				/**
				 * Look for any additional fields to be pushed within
				 * the "Booking" fieldset (sidebar).
				 *
				 * NOTE: retrieved from "onDisplayViewService" hook.
				 *
				 * @since 1.7
				 */
				if (isset($this->forms['booking']))
				{
					echo $this->forms['booking'];

					// unset details form to avoid displaying it twice
					unset($this->forms['booking']);
				}

				echo $vik->closeFieldset();
				?>
			</div>
		</div>

		<!-- CAPACITY -->

		<div class="row-fluid">
			<div class="span12">
				<?php
				echo $vik->openFieldset(JText::translate('VAPCAPACITY'));
				echo $this->loadTemplate('details_capacity');
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewService","key":"capacity","type":"field"} -->
				
				<?php
				/**
				 * Look for any additional fields to be pushed within
				 * the "Capacity" fieldset (sidebar).
				 *
				 * NOTE: retrieved from "onDisplayViewService" hook.
				 *
				 * @since 1.7
				 */
				if (isset($this->forms['capacity']))
				{
					echo $this->forms['capacity'];

					// unset details form to avoid displaying it twice
					unset($this->forms['capacity']);
				}

				echo $vik->closeFieldset();
				?>
			</div>
		</div>

		<!-- VISIBILITY -->

		<div class="row-fluid">
			<div class="span12">
				<?php
				echo $vik->openFieldset(JText::translate('VAPVISIBILITY'));
				echo $this->loadTemplate('details_visibility');
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewService","key":"visibility","type":"field"} -->
				
				<?php
				/**
				 * Look for any additional fields to be pushed within
				 * the "Visibility" fieldset (sidebar).
				 *
				 * NOTE: retrieved from "onDisplayViewService" hook.
				 *
				 * @since 1.7
				 */
				if (isset($this->forms['visibility']))
				{
					echo $this->forms['visibility'];

					// unset details form to avoid displaying it twice
					unset($this->forms['visibility']);
				}

				echo $vik->closeFieldset();
				?>
			</div>
		</div>

		<!-- PUBLISHING -->

		<div class="row-fluid">
			<div class="span12">
				<?php
				echo $vik->openFieldset(JText::translate('JGLOBAL_FIELDSET_PUBLISHING'));
				echo $this->loadTemplate('details_publishing');
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewService","key":"publishing","type":"field"} -->
				
				<?php
				/**
				 * Look for any additional fields to be pushed within
				 * the "Publishing" fieldset (sidebar).
				 *
				 * NOTE: retrieved from "onDisplayViewService" hook.
				 *
				 * @since 1.7
				 */
				if (isset($this->forms['publishing']))
				{
					echo $this->forms['publishing'];

					// unset details form to avoid displaying it twice
					unset($this->forms['publishing']);
				}

				echo $vik->closeFieldset();
				?>
			</div>
		</div>

		<!-- SETTINGS -->

		<div class="row-fluid">
			<div class="span12">
				<?php
				echo $vik->openFieldset(JText::translate('VAPMANAGECRONJOBFIELDSET2'));
				echo $this->loadTemplate('details_settings');
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewService","key":"settings","type":"field"} -->
				
				<?php
				/**
				 * Look for any additional fields to be pushed within
				 * the "Settings" fieldset (sidebar).
				 *
				 * NOTE: retrieved from "onDisplayViewService" hook.
				 *
				 * @since 1.7
				 */
				if (isset($this->forms['settings']))
				{
					echo $this->forms['settings'];

					// unset details form to avoid displaying it twice
					unset($this->forms['settings']);
				}

				echo $vik->closeFieldset();
				?>
			</div>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewServiceDetailsSidebar","type":"fieldset"} -->

		<?php
		/**
		 * Iterate remaining forms to be displayed within
		 * the sidebar (below "Settings" fieldset).
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
