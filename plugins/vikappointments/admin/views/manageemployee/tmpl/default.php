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
JHtml::fetch('vaphtml.assets.toast', 'bottom-right');

$employee = $this->employee;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewEmployee". The event method receives the
 * view instance as argument.
 *
 * @since 1.6.6
 */
$this->forms = $this->onDisplayView();

?>

<form name="adminForm" action="index.php" method="post" enctype="multipart/form-data" id="adminForm">

	<?php echo $vik->bootStartTabSet('employee', array('active' => $this->getActiveTab('employee_details'), 'cookie' => $this->getCookieTab()->name)); ?>

		<!-- DETAILS -->
			
		<?php
		echo $vik->bootAddTab('employee', 'employee_details', JText::translate('VAPCUSTFIELDSLEGEND1'));
		echo $this->loadTemplate('details');
		echo $vik->bootEndTab();
		?>

		<!-- CUSTOM FIELDS -->

		<?php
		if (count($this->customFields))
		{
			echo $vik->bootAddTab('employee', 'employee_custfields', JText::translate('VAPMANAGERESERVATIONTITLE2'));
			echo $this->loadTemplate('fields');
			echo $vik->bootEndTab();
		}
		?>

		<!-- WORKING DAYS -->

		<?php
		echo $vik->bootAddTab('employee', 'employee_workdays', JText::translate('VAPMANAGEEMPLOYEE12'));
		echo $this->loadTemplate('workdays');
		echo $vik->bootEndTab(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewEmployee","type":"tab"} -->

		<?php
		/**
		 * Iterate remaining forms to be displayed within
		 * the nav bar as custom sections.
		 *
		 * @since 1.6.6
		 */
		foreach ($this->forms as $formName => $formHtml)
		{
			$title = JText::translate($formName);

			// fetch form key
			$key = strtolower(preg_replace("/[^a-zA-Z0-9_]/", '', $title));

			if (!preg_match("/^employee_/", $key))
			{
				// keep same notation for fieldset IDs
				$key = 'employee_' . $key;
			}

			echo $vik->bootAddTab('employee', $key, $title);
			echo $formHtml;
			echo $vik->bootEndTab();
		}
		?>

	<?php echo $vik->bootEndTabSet(); ?>

	<?php echo JHtml::fetch('form.token'); ?>
	
	<input type="hidden" name="id" value="<?php echo $employee->id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikappointments" />
</form>

<?php
// fetch working days import modal
echo JHtml::fetch(
	'bootstrap.renderModal',
	'jmodal-wdimport',
	array(
		'title'       => JText::translate('VAPMAINTITLEMANAGEIMPORT'),
		'closeButton' => true,
		'keyboard'    => true, 
		'bodyHeight'  => 80,
		'footer'      => '<button type="button" class="btn btn-success" id="wdimport-save" disabled>' . JText::translate('JAPPLY') . '</button>',
	),
	$this->loadTemplate('workdays_import')
);
?>

<script>

	var validator;

	(function(w) {
		'use strict';

		validator = new VikFormValidator('#adminForm');

		Joomla.submitbutton = (task) => {
			if (task.indexOf('save') === -1 || validator.validate()) {
				Joomla.submitform(task, document.adminForm);	
			}
		}

		w['vapOpenJModal'] = (id, url, jqmodal) => {
			<?php echo $vik->bootOpenModalJS(); ?>
		}

		w['vapCloseJModal'] = (id) => {
			<?php echo $vik->bootDismissModalJS(); ?>
		}
	})(window);
	
</script>
