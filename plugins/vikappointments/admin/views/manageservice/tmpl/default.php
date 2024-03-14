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

$service = $this->service;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewService". The event method receives the
 * view instance as argument.
 *
 * @since 1.6.6
 */
$this->forms = $this->onDisplayView();

?>

<form name="adminForm" action="index.php" method="post" id="adminForm">

	<?php echo $vik->bootStartTabSet('service', array('active' => $this->getActiveTab('service_details'), 'cookie' => $this->getCookieTab()->name)); ?>

		<!-- DETAILS -->
			
		<?php
		echo $vik->bootAddTab('service', 'service_details', JText::translate('VAPCUSTFIELDSLEGEND1'));
		echo $this->loadTemplate('details');
		echo $vik->bootEndTab();
		?>

		<!-- ASSIGNMENTS -->

		<?php
		echo $vik->bootAddTab('service', 'service_assoc', JText::translate('VAPFIELDSETASSOC'));
		echo $this->loadTemplate('assoc');
		echo $vik->bootEndTab();
		?>

		<!-- METADATA -->

		<?php
		echo $vik->bootAddTab('service', 'service_metadata', JText::translate('JGLOBAL_FIELDSET_METADATA_OPTIONS'));
		echo $this->loadTemplate('metadata');
		echo $vik->bootEndTab();
		?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewService","type":"tab"} -->

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

			if (!preg_match("/^service_/", $key))
			{
				// keep same notation for fieldset IDs
				$key = 'service_' . $key;
			}

			echo $vik->bootAddTab('service', $key, $title);
			echo $formHtml;
			echo $vik->bootEndTab();
		}
		?>

	<?php echo $vik->bootEndTabSet(); ?>

	<?php echo JHtml::fetch('form.token'); ?>
	
	<input type="hidden" name="id" value="<?php echo $service->id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikappointments" />
</form>

<?php
$footer  = '<button type="button" class="btn btn-success" data-role="save">' . JText::translate('JAPPLY') . '</button>';
$footer .= '<button type="button" class="btn btn-danger" data-role="delete" style="float:right;">' . JText::translate('VAPDELETE') . '</button>';

// render inspector to manage service employees
echo JHtml::fetch(
	'vaphtml.inspector.render',
	'service-employee-inspector',
	array(
		'title'       => JText::translate('VAPMANAGERESERVATION3'),
		'closeButton' => true,
		'keyboard'    => true,
		'footer'      => $footer,
		'width'       => 600,
	),
	$this->loadTemplate('assoc_employees_modal')
);

$footer = '<button type="button" class="btn btn-success" data-role="save">' . JText::translate('JAPPLY') . '</button>';

// render inspector to manage service options
echo JHtml::fetch(
	'vaphtml.inspector.render',
	'service-options-inspector',
	array(
		'title'       => JText::translate('VAPMANAGERESERVATION14'),
		'closeButton' => true,
		'keyboard'    => true,
		'footer'      => $footer,
		'width'       => 400,
	),
	$this->loadTemplate('assoc_options_modal')
);
?>

<script>

	function timelineLayoutValueChanged(name) {
		var lookup = ['checkout_selection', 'display_seats'];

		for (var i = 0; i < lookup.length; i++) {
			if (lookup[i] != name) {
				jQuery('input[name="' + lookup[i] + '"]').prop('checked', false);
			}
		}
	}

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
