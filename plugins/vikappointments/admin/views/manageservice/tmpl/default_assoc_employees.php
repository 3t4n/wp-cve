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

	<!-- ADD PLACEHOLDER -->

	<div class="vap-card-fieldset up-to-1 add add-service-employee">
		<div class="vap-card compress">
			<i class="fas fa-plus"></i>
		</div>
	</div>

	<?php
	foreach ($this->service->employees as $i => $employee)
	{
		?>
		<div class="vap-card-fieldset up-to-1" id="service-employee-fieldset-<?php echo $i; ?>" data-id="<?php echo $employee->id; ?>" data-id-employee="<?php echo $employee->id_employee; ?>">

			<?php
			$displayData = array();

			// reduce card size
			$displayData['class'] = 'compress';

			if ($employee->global)
			{
				$displayData['class'] .= ' published';

				$icon = 'star';
			}
			else
			{
				$icon = 'sliders-h';
			}

			// fetch primary text
			$displayData['primary'] = $employee->nickname;

			// fetch badge
			$displayData['badge'] = '<i class="fas fa-' . $icon . '"></i>';

			// fetch edit button
			$displayData['edit'] = 'vapOpenServiceEmployeeCard(\'' . $i . '\');';

			// render layout
			echo $empLayout->render($displayData);
			?>

			<input type="hidden" name="employee_json[]" value="<?php echo $this->escape(json_encode($employee)); ?>" />

		</div>
		<?php
	}
	?>

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
?>

<script>
	var EMPLOYEES_COUNT   = <?php echo count($this->service->employees); ?>;
	var SELECTED_OPTION = null;

	jQuery(function($) {
		// open inspector for new employees
		$('.vap-card-fieldset.add-service-employee').on('click', () => {
			// open inspector
			vapOpenServiceEmployeeCard();
		});

		$('#cards-service-employees').sortable({
			// exclude "add" boxes
			items: '.vap-card-fieldset:not(.add)',
		});

		// show the inspector
		$('#service-employee-inspector').on('inspector.show', () => {
			var json = [];

			// fetch JSON data
			if (SELECTED_OPTION) {
				var fieldset = $('#' + SELECTED_OPTION);

				json = fieldset.find('input[name="employee_json[]"]').val();

				try {
					json = JSON.parse(json);
				} catch (err) {
					json = {};
				}
			} else {
				// load list of already selected employees
				$('[data-id-employee]').each(function() {
					json.push(parseInt($(this).attr('data-id-employee')));
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
			if (!empValidator.validate()) {
				return false;
			}

			var employees = getServiceEmployeesData();

			let is_edit = SELECTED_OPTION ? true : false;

			employees.forEach((emp) => {
				let fieldset;
				
				if (is_edit) {
					fieldset = $('#' + SELECTED_OPTION);
				} else {
					fieldset = vapAddServiceEmployeeCard(emp);
				}

				// refresh card details
				vapRefreshServiceEmployeeCard(fieldset.find('.vap-card'), emp);

				// save JSON data
				fieldset.find('input[name="employee_json[]"]')
					.val(JSON.stringify(emp));
			});

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
				let editor = Joomla.editors.instances.employee_description;
				
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
			var json = fieldset.find('input[name="employee_json[]"]').val();

			try {
				json = JSON.parse(json);
			} catch (err) {
				json = {};
			}

			if (json.id) {
				// commit record delete
				$('#adminForm').append('<input type="hidden" name="employee_deleted[]" value="' + json.id + '" />');
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
		let index = EMPLOYEES_COUNT++;

		SELECTED_OPTION = 'service-employee-fieldset-' + index;

		var html = jQuery('#service-employee-struct').clone().html();

		html = html.replace(/{id}/, index);

		jQuery('#cards-service-employees').append(
			'<div class="vap-card-fieldset up-to-1" id="service-employee-fieldset-' + index + '" data-id-employee="' + data.id_employee + '">' + html + '</div>'
		);

		// get created fieldset
		let fieldset = jQuery('#' + SELECTED_OPTION);

		fieldset.vapcard('edit', 'vapOpenServiceEmployeeCard(' + index + ')');

		fieldset.append('<input type="hidden" name="employee_json[]" value="" />');

		return fieldset;
	}

	function vapRefreshServiceEmployeeCard(elem, data) {
		// update badge
		var icon;

		elem.vapcard('primary', data.nickname);

		if (parseInt(data.global) == 1) {
			icon = 'star';
			elem.addClass('published');
		} else {
			icon = 'sliders-h';
			elem.removeClass('published');
		}

		elem.vapcard('badge', '<i class="fas fa-' + icon + '"></i>');
	}

</script>
