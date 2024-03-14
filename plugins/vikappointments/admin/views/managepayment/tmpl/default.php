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

JHtml::fetch('bootstrap.tooltip', '.hasTooltip');
JHtml::fetch('vaphtml.assets.select2');
JHtml::fetch('vaphtml.assets.fontawesome');

$payment = $this->payment;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewPayment". The event method receives the
 * view instance as argument.
 *
 * @since 1.7
 */
$this->forms = $this->onDisplayView();

?>

<form name="adminForm" action="index.php" method="post" id="adminForm">

	<?php echo $vik->bootStartTabSet('payment', array('active' => $this->getActiveTab('payment_details'), 'cookie' => $this->getCookieTab()->name)); ?>

		<!-- DETAILS -->
			
		<?php
		echo $vik->bootAddTab('payment', 'payment_details', JText::translate('VAPCUSTFIELDSLEGEND1'));
		echo $this->loadTemplate('details');
		echo $vik->bootEndTab();
		?>

		<!-- NOTES -->

		<?php
		echo $vik->bootAddTab('payment', 'payment_notes', JText::translate('VAPMANAGEPAYMENT7'));
		echo $this->loadTemplate('notes');
		echo $vik->bootEndTab();
		?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewPayment","type":"tab"} -->

		<?php
		/**
		 * Iterate remaining forms to be displayed within
		 * the nav bar as custom sections.
		 *
		 * @since 1.7
		 */
		foreach ($this->forms as $formName => $formHtml)
		{
			$title = JText::translate($formName);

			// fetch form key
			$key = strtolower(preg_replace("/[^a-zA-Z0-9_]/", '', $title));

			if (!preg_match("/^payment_/", $key))
			{
				// keep same notation for fieldset IDs
				$key = 'payment_' . $key;
			}

			echo $vik->bootAddTab('payment', $key, $title);
			echo $formHtml;
			echo $vik->bootEndTab();
		}
		?>

	<?php echo $vik->bootEndTabSet(); ?>

	<?php echo JHtml::fetch('form.token'); ?>

	<input type="hidden" name="id_employee" value="<?php echo $payment->id_employee; ?>" />
	
	<input type="hidden" name="id" value="<?php echo $payment->id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikappointments" />
</form>

<script>

	// validate

	var validator = new VikFormValidator('#adminForm');

	Joomla.submitbutton = function(task) {
		if (task.indexOf('save') !== -1) {
			if (validator.validate()) {
				Joomla.submitform(task, document.adminForm);	
			}
		} else {
			Joomla.submitform(task, document.adminForm);
		}
	}
	
</script>
