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

$currency = VAPFactory::getCurrency();

$empLayout = new JLayoutFile('blocks.card');

?>

<div class="vap-cards-container cards-service-employees" id="cards-service-employees">

	<?php
	foreach ($this->assigned as $i => $service)
	{
		?>
		<div class="vap-card-fieldset up-to-3" id="service-employee-fieldset-<?php echo $i; ?>" data-id="<?php echo $service->id; ?>" data-id-service="<?php echo $service->id_service; ?>">

			<?php
			$displayData = array();

			// reduce card size
			$displayData['class'] = 'compress';

			if ($service->global)
			{
				$displayData['class'] .= ' published';

				$icon = 'star';
			}
			else
			{
				$icon = 'sliders-h';
			}

			// fetch primary text
			$displayData['primary'] = $this->services[$service->id_service]->name;

			// fetch secondary text
			$displayData['secondary'] = '<span class="badge badge-info">' . $currency->format($service->rate) . '</span>'
				. '<span class="badge badge-success">' . VikAppointments::formatMinutesToTime($service->duration + $service->sleep, false) . '</span>';

			// fetch badge
			$displayData['badge'] = '<i class="fas fa-' . $icon . '"></i>';

			// fetch edit button
			$displayData['edit'] = 'vapOpenServiceEmployeeCard(\'' . $i . '\');';

			// render layout
			echo $empLayout->render($displayData);
			?>

			<input type="hidden" name="service_json[]" value="<?php echo $this->escape(json_encode($service)); ?>" />

		</div>
		<?php
	}
	?>

	<!-- ADD PLACEHOLDER -->

	<div class="vap-card-fieldset up-to-3 add add-service-employee">
		<div class="vap-card compress">
			<i class="fas fa-plus"></i>
		</div>
	</div>

</div>

<div style="display:none;" id="service-employee-struct">
			
	<?php
	// create structure for records
	$displayData = array();
	$displayData['class']     = 'compress';
	$displayData['badge']     = '<i class="fas fa-star"></i>';
	$displayData['primary']   = '';
	$displayData['secondary'] = '';
	$displayData['edit']      = true;

	echo $empLayout->render($displayData);
	?>

</div>

<?php
JText::script('VAPSYSTEMCONFIRMATIONMSG');
JText::script('VAPSHORTCUTMINUTE');
?>

<script>
	var OPTIONS_COUNT   = <?php echo count($this->assigned); ?>;
	var SELECTED_OPTION = null;
	var SERVICES_LOOKUP = <?php echo json_encode($this->services); ?>;

	jQuery(function($) {
		// open inspector for new services
		$('.vap-card-fieldset.add-service-employee').on('click', () => {
			// open inspector
			vapOpenServiceEmployeeCard();
		});

		// show the inspector
		$('#service-employee-inspector').on('inspector.show', () => {
			var json = [];

			// fetch JSON data
			if (SELECTED_OPTION) {
				var fieldset = $('#' + SELECTED_OPTION);

				json = fieldset.find('input[name="service_json[]"]').val();

				try {
					json = JSON.parse(json);
				} catch (err) {
					json = {};
				}
			} else {
				// load list of already selected services
				$('[data-id-service]').each(function() {
					json.push(parseInt($(this).attr('data-id-service')));
				});
			}

			if (json.id === undefined) {
				// creating new record, hide delete button
				$(this).find('[data-role="delete"]').hide();
			} else {
				// editing existing record, show delete button
				$(this).find('[data-role="delete"]').show();
			}

			fillServiceEmployeesForm(json);
		});

		// apply the changes
		$('#service-employee-inspector').on('inspector.save', function() {
			if (!serValidator.validate()) {
				return false;
			}

			var service = getServiceEmployeesData();

			let fieldset;

			if (SELECTED_OPTION) {
				fieldset = $('#' + SELECTED_OPTION);
			} else {
				fieldset = vapAddServiceEmployeeCard(service);
			}

			// refresh card details
			vapRefreshServiceEmployeeCard(fieldset.find('.vap-card'), service);

			// save JSON data
			fieldset.find('input[name="service_json[]"]').val(JSON.stringify(service));

			// auto-close on save
			$(this).inspector('dismiss');
		});

		/**
		 * Handle inspector hide.
		 *
		 * We need to bind the event by using a handler in order to have a lower priority,
		 * since the hook used to observe any form changes may be attached after this one.
		 */
		$(document).on('inspector.close', '#service-employee-inspector', function() {
			if (typeof tinyMCE !== 'undefined' && tinyMCE.activeEditor) {
				// reset editor after closing the inspector
				let editor = Joomla.editors.instances.service_description;
				
				editor.setValue('');

				if (editor.onSave) {
					editor.onSave();
				}

				// flag TinyMCE editor as clean because every time we edit
				// something and we close the inspector, the editor might
				// prompt an alert saying if we wish to stay or leave
				if (editor.instance && editor.instance.isNotDirty === false) {
					editor.instance.isNotDirty = true;
				}
			}
		});

		// delete the record
		$('#service-employee-inspector').on('inspector.delete', function() {
			var fieldset = $('#' + SELECTED_OPTION);

			if (fieldset.length == 0) {
				// record not found
				return false;
			}

			// get existing record
			var json = fieldset.find('input[name="service_json[]"]').val();

			try {
				json = JSON.parse(json);
			} catch (err) {
				json = {};
			}

			if (json.id) {
				// commit record delete
				$('#adminForm').append('<input type="hidden" name="service_deleted[]" value="' + json.id + '" />');
			}

			// auto delete fieldset
			fieldset.remove();

			// auto-close on delete
			$(this).inspector('dismiss');
		});
	});

	function vapOpenServiceEmployeeCard(index) {
		if (index !== undefined) {
			SELECTED_OPTION = 'service-employee-fieldset-' + index;
		} else {
			SELECTED_OPTION = null;
		}

		jQuery('#service-employee-inspector').inspector('show');
	}

	function vapAddServiceEmployeeCard(data) {
		let index = OPTIONS_COUNT++;

		SELECTED_OPTION = 'service-employee-fieldset-' + index;

		var html = jQuery('#service-employee-struct').clone().html();

		html = html.replace(/{id}/, index);

		jQuery(
			'<div class="vap-card-fieldset up-to-3" id="service-employee-fieldset-' + index + '" data-id-service="' + data.id_service + '">' + html + '</div>'
		).insertBefore(jQuery('.vap-card-fieldset.add-service-employee').last());

		// get created fieldset
		let fieldset = jQuery('#' + SELECTED_OPTION);

		fieldset.vapcard('edit', 'vapOpenServiceEmployeeCard(' + index + ')');

		fieldset.append('<input type="hidden" name="service_json[]" value="" />');

		return fieldset;
	}

	function vapRefreshServiceEmployeeCard(elem, data) {
		// update badge
		var icon;

		elem.vapcard('primary', data.name);

		if (parseInt(data.global) == 1) {
			icon = 'star';
			elem.addClass('published');
		} else {
			icon = 'sliders-h';
			elem.removeClass('published');
		}

		let price = jQuery('<span class="badge badge-info"></span>')
						.html(Currency.getInstance().format(data.rate));

		let duration = jQuery('<span class="badge badge-success"></span>')
						.html((data.duration + data.sleep) + ' ' + Joomla.JText._('VAPSHORTCUTMINUTE'));

		elem.vapcard('secondary', price.add(duration));

		elem.vapcard('badge', '<i class="fas fa-' + icon + '"></i>');
	}

</script>
