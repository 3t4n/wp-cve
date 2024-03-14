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

?>

<div class="inspector-form" id="inspector-service-option-form">

	<div class="inspector-fieldset">

		<!-- OPTION - Select -->

		<?php
		echo $vik->openControl(JText::translate('VAPMANAGESERVICE39') . '*');
		
		// load options and group them by status
		$options = JHtml::fetch('vaphtml.admin.options', $strict = false, $blank = false, $group = true);

		if ($options)
		{
			// create dropdown attributes
			$params = array(
				'id'          => 'vap-options-sel',
				'group.items' => null,
				'list.select' => null,
				'list.attr'   => 'multiple class="required"',
			);
			
			// render select
			echo JHtml::fetch('select.groupedList', $options, '', $params);
		}
		else
		{
			// no available options
			echo $vik->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
		}

		echo $vik->closeControl();
		?>

	</div>

</div>

<?php
JText::script('JUNPUBLISHED');
?>

<script>

	var optValidator = new VikFormValidator('#inspector-service-option-form');

	jQuery(function($) {
		$('#vap-options-sel').select2({
			width: '100%',
		});
	});

	function fillServiceOptionsForm(options) {
		let select = jQuery('#vap-options-sel');

		// clear selection
		select.select2('val', []);

		// iterate all options inside select and fetch their status
		select.find('option').each(function() {
			let id = parseInt(jQuery(this).val());

			jQuery(this).prop('disabled', options.indexOf(id) !== -1);
		});
	}

	function getServiceOptionsData() {
		let data = [];

		let select = jQuery('#vap-options-sel');

		select.select2('val').forEach((id) => {
			// get selected option
			let option = select.find('option[value="' + id + '"]');

			// create option
			let tmp = {
				id:        id,
				name:      option.text(),
				published: 1,
			};

			// find group of selected option
			let group = option.closest('optgroup');

			if (group.attr('label') == Joomla.JText._('JUNPUBLISHED')) {
				// we selected an unpublished option
				tmp.published = 0;
			}

			data.push(tmp);
		});

		return data;
	}

</script>
