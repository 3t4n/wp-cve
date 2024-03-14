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

$editor = $vik->getEditor();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewPaymentNotes".
 * The event method receives the view instance as argument.
 *
 * @since 1.7
 */
$notesForms = $this->onDisplayView('Notes');

?>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewPaymentNotes","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed within
 * the Notes tab (at the beginning).
 *
 * @since 1.7
 */
foreach ($notesForms as $formName => $formHtml)
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

<div class="row-fluid">

	<!-- LEFT SIDE -->

	<div class="span6">

		<!-- NOTES BEFORE PURCHASE -->

		<div class="row-fluid">
			<div class="span12">
				<?php
				echo $vik->openFieldset(JText::translate('VAPMANAGEPAYMENT13'));
				echo $editor->display('prenote', $payment->prenote, '100%', 550, 70, 20);
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewPayment","key":"prenote","type":"field"} -->
				
				<?php
				/**
				 * Look for any additional fields to be pushed within
				 * the "Notes before purchase" fieldset (left-side).
				 *
				 * NOTE: retrieved from "onDisplayViewPayment" hook.
				 *
				 * @since 1.7
				 */
				if (isset($this->forms['prenote']))
				{
					echo $this->forms['prenote'];

					// unset details form to avoid displaying it twice
					unset($this->forms['prenote']);
				}

				echo $vik->closeFieldset();
				?>
			</div>
		</div>

	</div>

	<!-- RIGHT SIDE -->

	<div class="span6">

		<!-- NOTES AFTER PURCHASE -->

		<div class="row-fluid">
			<div class="span12">
				<?php
				echo $vik->openFieldset(JText::translate('VAPMANAGEPAYMENT14'));
				echo $editor->display('note', $payment->note, '100%', 550, 70, 20);
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewPayment","key":"note","type":"field"} -->
				
				<?php
				/**
				 * Look for any additional fields to be pushed within
				 * the "Notes after purchase" fieldset (right-side).
				 *
				 * NOTE: retrieved from "onDisplayViewPayment" hook.
				 *
				 * @since 1.7
				 */
				if (isset($this->forms['note']))
				{
					echo $this->forms['note'];

					// unset details form to avoid displaying it twice
					unset($this->forms['note']);
				}

				echo $vik->closeFieldset();
				?>
			</div>
		</div>

	</div>

</div>
