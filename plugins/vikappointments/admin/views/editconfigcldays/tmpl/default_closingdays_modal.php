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

<div class="inspector-form" id="inspector-clday-form">

	<div class="inspector-fieldset">

		<!-- DATE -->

		<?php
		echo $vik->openControl(JText::translate('VAPMANAGEREVIEW4'));
		echo $vik->calendar('', 'cl_day_ts', 'cl_day_ts', null, array('class' => 'required'));
		echo $vik->closeControl();
		?>

		<!-- RECURRENCE -->

		<?php
		$options = array(
			// single day
			JHtml::fetch('select.option', 0, JText::translate('VAPFREQUENCYTYPE0')),
			// weekly
			JHtml::fetch('select.option', 1, JText::translate('VAPFREQUENCYTYPE1')),
			// monthly
			JHtml::fetch('select.option', 2, JText::translate('VAPFREQUENCYTYPE2')),
			// yearly
			JHtml::fetch('select.option', 3, JText::translate('VAPFREQUENCYTYPE3')),
		);

		echo $vik->openControl(JText::translate('VAPRECURRENCE')); ?>
			<select id="cl_day_freq">
				<?php echo JHtml::fetch('select.options', $options); ?>
			</select>
		<?php echo $vik->closeControl(); ?>

		<!-- SERVICES -->

		<?php
		// load services and group them
		$options = JHtml::fetch('vaphtml.admin.services', $strict = false, $blank = false, $group = true);

		echo $vik->openControl(JText::translate('VAPMENUSERVICES'));

		// create dropdown attributes
		$params = array(
			'id'          => 'cl_day_services',
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
		$('#cl_day_freq').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: '100%',
		});

		$('#cl_day_services').select2({
			allowClear: true,
			width: '100%',
		});
	});

	var cdValidator = new VikFormValidator('#inspector-clday-form');

	function fillClosingDayForm(data) {
		// update date
		if (data.date !== undefined) {
			// update data-alt-value too for MooTools compliance
			jQuery('#cl_day_ts').val(data.date).attr('data-alt-value', data.date);
		}

		cdValidator.unsetInvalid(jQuery('#cl_day_ts'));

		// update frequency
		if (data.freq !== undefined) {
			jQuery('#cl_day_freq').select2('val', data.freq);
		}

		// update services
		if (data.services !== undefined) {
			jQuery('#cl_day_services').select2('val', data.services);
		}
	}

	function getClosingDayData() {
		var data = {};

		// set formatted date
		data.date = jQuery('#cl_day_ts').val();

		// obtain date in military format (false to avoid instantiating a date object)
		data.ts = getDateFromFormat(data.date, '<?php echo $config->get('dateformat'); ?>', false);

		// set frequency
		data.freq = jQuery('#cl_day_freq').val();

		// set services
		data.services = jQuery('#cl_day_services').val();

		// fetch names of selected services
		data.servicesName = [];

		jQuery('#cl_day_services option:selected').each(function() {
			data.servicesName.push(jQuery(this).text());
		});

		return data;
	}

</script>
