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

$payment = $this->payment;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewPaymentDetails".
 * It is also possible to use "onDisplayViewPaymentDetailsSidebar"
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

		<!-- PAYMENT -->

		<div class="row-fluid">
			<div class="span12">
				<?php
				echo $vik->openFieldset(JText::translate('VAPORDERPAYMENT'));
				echo $this->loadTemplate('details_payment');
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewPayment","key":"payment","type":"field"} -->

				<?php	
				/**
				 * Look for any additional fields to be pushed within
				 * the "Payment" fieldset (left-side).
				 *
				 * NOTE: retrieved from "onDisplayViewPayment" hook.
				 *
				 * @since 1.7
				 */
				if (isset($this->forms['payment']))
				{
					echo $this->forms['payment'];

					// unset details form to avoid displaying it twice
					unset($this->forms['payment']);
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
				<!-- {"rule":"customizer","event":"onDisplayViewPayment","key":"publishing","type":"field"} -->

				<?php	
				/**
				 * Look for any additional fields to be pushed within
				 * the "Publishing" fieldset (left-side).
				 *
				 * NOTE: retrieved from "onDisplayViewPayment" hook.
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

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewPaymentDetails","type":"fieldset"} -->

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

		<!-- PARAMS -->

		<div class="row-fluid">
			<div class="span12">
				<?php
				echo $vik->openFieldset(JText::translate('VAPMANAGEPAYMENT8'));
				echo $this->loadTemplate('details_params');
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewPayment","key":"params","type":"field"} -->

				<?php	
				/**
				 * Look for any additional fields to be pushed within
				 * the "Parameters" fieldset (right-side).
				 *
				 * NOTE: retrieved from "onDisplayViewPayment" hook.
				 *
				 * @since 1.7
				 */
				if (isset($this->forms['params']))
				{
					echo $this->forms['params'];

					// unset details form to avoid displaying it twice
					unset($this->forms['params']);
				}
				
				echo $vik->closeFieldset();
				?>
			</div>
		</div>

		<!-- APPEARANCE -->

		<div class="row-fluid">
			<div class="span12">
				<?php
				echo $vik->openFieldset(JText::translate('VAPAPPEARANCE'));
				echo $this->loadTemplate('details_appearance');
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewPayment","key":"appearance","type":"field"} -->

				<?php	
				/**
				 * Look for any additional fields to be pushed within
				 * the "Appearance" fieldset (left-side).
				 *
				 * NOTE: retrieved from "onDisplayViewPayment" hook.
				 *
				 * @since 1.7
				 */
				if (isset($this->forms['appearance']))
				{
					echo $this->forms['appearance'];

					// unset details form to avoid displaying it twice
					unset($this->forms['appearance']);
				}
					
				echo $vik->closeFieldset();
				?>
			</div>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewPaymentDetailsSidebar","type":"fieldset"} -->

		<?php
		/**
		 * Iterate remaining forms to be displayed within
		 * the sidebar (below "Parameters" fieldset).
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
