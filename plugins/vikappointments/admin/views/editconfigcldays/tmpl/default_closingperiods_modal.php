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

$config = VAPFactory::getConfig();

?>

<div class="inspector-form" id="inspector-clperiod-form">

	<div class="inspector-fieldset">

		<!-- START DATE -->

		<?php
		echo $vik->openControl(JText::translate('VAPMANAGEPACKAGE6'));
		echo $vik->calendar('', 'cl_period_datestart', 'cl_period_datestart', null, array('class' => 'required'));
		echo $vik->closeControl();
		?>

		<!-- END DATE -->

		<?php
		echo $vik->openControl(JText::translate('VAPMANAGEPACKAGE7'));
		echo $vik->calendar('', 'cl_period_dateend', 'cl_period_dateend', null, array('class' => 'required'));
		echo $vik->closeControl();
		?>

		<!-- SERVICES -->

		<?php
		// load services and group them
		$options = JHtml::fetch('vaphtml.admin.services', $strict = false, $blank = false, $group = true);

		echo $vik->openControl(JText::translate('VAPMENUSERVICES'));

		// create dropdown attributes
		$params = array(
			'id'          => 'cl_period_services',
			'group.items' => null,
			'list.select' => null,
			'list.attr'   => array('multiple' => true),
		);

		// render select
		echo JHtml::fetch('select.groupedList', $options, null, $params);

		echo $vik->closeControl();
		?>

	</div>

</div>

<script>

	jQuery(function($) {
		$('#cl_period_services').select2({
			allowClear: true,
			width: '100%',
		});
	});

	var cpValidator = new VikFormValidator('#inspector-clperiod-form');

	function fillClosingPeriodForm(data) {
		// update start date
		if (data.datestart !== undefined) {
			// update data-alt-value too for MooTools compliance
			jQuery('#cl_period_datestart').val(data.datestart).attr('data-alt-value', data.datestart);
		}

		cpValidator.unsetInvalid(jQuery('#cl_period_datestart'));

		// update end date
		if (data.dateend !== undefined) {
			// update data-alt-value too for MooTools compliance
			jQuery('#cl_period_dateend').val(data.dateend).attr('data-alt-value', data.dateend);
		}

		cpValidator.unsetInvalid(jQuery('#cl_period_dateend'));

		// update services
		if (data.services !== undefined) {
			jQuery('#cl_period_services').select2('val', data.services);
		}
	}

	function getClosingPeriodData() {
		var data = {};

		// set formatted start date
		data.datestart = jQuery('#cl_period_datestart').val();

		// obtain date in military format (false to avoid instantiating a date object)
		data.start = getDateFromFormat(data.datestart, '<?php echo $config->get('dateformat'); ?>', false);

		// set formatted end date
		data.dateend = jQuery('#cl_period_dateend').val();

		// obtain date in military format (false to avoid instantiating a date object)
		data.end = getDateFromFormat(data.dateend, '<?php echo $config->get('dateformat'); ?>', false);

		// set services
		data.services = jQuery('#cl_period_services').val();

		// fetch names of selected services
		data.servicesName = [];

		jQuery('#cl_period_services option:selected').each(function() {
			data.servicesName.push(jQuery(this).text());
		});

		return data;
	}

</script>
