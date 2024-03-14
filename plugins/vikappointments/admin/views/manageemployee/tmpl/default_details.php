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

$employee = $this->employee;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewEmployeeDetails".
 * It is also possible to use "onDisplayViewEmployeeDetailsSidebar"
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

		<!-- EMPLOYEE -->

		<div class="row-fluid">
			<div class="span12">
				<?php
				echo $vik->openFieldset(JText::translate('VAPMANAGERESERVATION3'));
				echo $this->loadTemplate('details_employee');
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewEmployee","key":"employee","type":"field"} -->

				<?php	
				/**
				 * Look for any additional fields to be pushed within
				 * the "Employee" fieldset (left-side).
				 *
				 * NOTE: retrieved from "onDisplayViewEmployee" hook.
				 *
				 * @since 1.7
				 */
				if (isset($this->forms['employee']))
				{
					echo $this->forms['employee'];

					// unset details form to avoid displaying it twice
					unset($this->forms['employee']);
				}
					
				echo $vik->closeFieldset();
				?>
			</div>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewEmployeeDetails","type":"fieldset"} -->

		<?php
		/**
		 * Iterate remaining forms to be displayed within
		 * the main panel (below "employee" fieldset).
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
				echo $vik->openFieldset(JText::translate('VAPMANAGEEMPLOYEE11'));
				echo $vik->getEditor()->display('note', $employee->note, '100%', 550, 40, 20);
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewEmployee","key":"description","type":"field"} -->
				
				<?php
				/**
				 * Look for any additional fields to be pushed within
				 * the "Description" fieldset (left-side).
				 *
				 * NOTE: retrieved from "onDisplayViewEmployee" hook.
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

		<!-- CONTACT -->

		<div class="row-fluid">
			<div class="span12">
				<?php
				echo $vik->openFieldset(JText::translate('VAPCONTACT'));
				echo $this->loadTemplate('details_contact');
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewEmployee","key":"contact","type":"field"} -->
				
				<?php
				/**
				 * Look for any additional fields to be pushed within
				 * the "Contact" fieldset (sidebar).
				 *
				 * NOTE: retrieved from "onDisplayViewEmployee" hook.
				 *
				 * @since 1.7
				 */
				if (isset($this->forms['contact']))
				{
					echo $this->forms['contact'];

					// unset details form to avoid displaying it twice
					unset($this->forms['contact']);
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
				<!-- {"rule":"customizer","event":"onDisplayViewEmployee","key":"visibility","type":"field"} -->
				
				<?php
				/**
				 * Look for any additional fields to be pushed within
				 * the "Visibility" fieldset (sidebar).
				 *
				 * NOTE: retrieved from "onDisplayViewEmployee" hook.
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

		<!-- NOTIFICATIONS -->

		<div class="row-fluid">
			<div class="span12">
				<?php
				echo $vik->openFieldset(JText::translate('VAPNOTIFICATIONS'));
				echo $this->loadTemplate('details_notifications');
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewEmployee","key":"notifications","type":"field"} -->

				<?php
				/**
				 * Look for any additional fields to be pushed within
				 * the "Notifications" fieldset (sidebar).
				 *
				 * NOTE: retrieved from "onDisplayViewEmployee" hook.
				 *
				 * @since 1.7
				 */
				if (isset($this->forms['notifications']))
				{
					echo $this->forms['notifications'];

					// unset details form to avoid displaying it twice
					unset($this->forms['notifications']);
				}

				echo $vik->closeFieldset();
				?>
			</div>
		</div>

		<!-- CALENDAR -->

		<div class="row-fluid">
			<div class="span12">
				<?php
				echo $vik->openFieldset(JText::translate('VAPMENUCALENDAR'));
				echo $this->loadTemplate('details_calendar');
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewEmployee","key":"calendar","type":"field"} -->

				<?php
				/**
				 * Look for any additional fields to be pushed within
				 * the "Calendar" fieldset (sidebar).
				 *
				 * NOTE: retrieved from "onDisplayViewEmployee" hook.
				 *
				 * @since 1.7
				 */
				if (isset($this->forms['calendar']))
				{
					echo $this->forms['calendar'];

					// unset details form to avoid displaying it twice
					unset($this->forms['calendar']);
				}

				echo $vik->closeFieldset();
				?>
			</div>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewEmployeeDetailsSidebar","type":"fieldset"} -->

		<?php
		/**
		 * Iterate remaining forms to be displayed within
		 * the sidebar (below "calendar" fieldset).
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
