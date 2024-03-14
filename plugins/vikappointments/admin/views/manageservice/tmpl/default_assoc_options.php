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

$optLayout = new JLayoutFile('blocks.card');

?>

<div class="vap-cards-container cards-service-options" id="cards-service-options">

	<!-- ADD PLACEHOLDER -->

	<div class="vap-card-fieldset up-to-1 add add-service-option">
		<div class="vap-card compress">
			<i class="fas fa-plus"></i>
		</div>
	</div>

	<?php
	foreach ($this->service->options as $i => $option)
	{
		?>
		<div class="vap-card-fieldset up-to-1" id="service-option-fieldset-<?php echo $i; ?>" data-id="<?php echo $option->id_assoc; ?>" data-id-option="<?php echo $option->id; ?>">

			<?php
			$displayData = array();

			// reduce card size
			$displayData['class'] = 'compress';

			if ($option->published)
			{
				$displayData['class'] .= ' published';

				$icon = 'fas fa-check-circle';
			}
			else
			{
				$icon = 'far fa-circle';
			}

			// fetch primary text
			$displayData['primary'] = $option->name;

			// fetch badge
			$displayData['badge'] = '<i class="' . $icon . '"></i>';

			// fetch edit button
			$displayData['edit'] = 'vapDeleteServiceOptionCard(\'' . $i . '\');';
			// use different text
			$displayData['editText'] = JText::translate('VAPDELETE');
			// use custom class
			$displayData['editClass'] = 'btn-danger';

			// render layout
			echo $optLayout->render($displayData);
			?>

			<input type="hidden" name="id_option_copy[]" value="<?php echo $option->id; ?>" />

		</div>
		<?php
	}
	?>

</div>

<div style="display:none;" id="service-option-struct">
			
	<?php
	// create structure for records
	$displayData = array();
	$displayData['class']     = 'compress';
	$displayData['badge']     = '<i class="far fa-circle"></i>';
	$displayData['primary']   = '';
	$displayData['secondary'] = '';
	$displayData['edit']      = true;
	$displayData['editText']  = JText::translate('VAPDELETE');
	$displayData['editClass'] = 'btn-danger';

	echo $optLayout->render($displayData);
	?>

</div>

<?php
JText::script('VAPSYSTEMCONFIRMATIONMSG');
?>

<script>
	var OPTIONS_COUNT   = <?php echo count($this->service->options); ?>;
	var SELECTED_OPTION = null;

	jQuery(function($) {
		// open inspector for new options
		$('.vap-card-fieldset.add-service-option').on('click', () => {
			// open inspector
			vapOpenInspector('service-options-inspector');
		});

		// apply the changes
		$('#service-options-inspector').on('inspector.show', function() {
			let options = [];

			// load list of already selected options
			$('[data-id-option]').each(function() {
				options.push(parseInt($(this).attr('data-id-option')));
			});

			fillServiceOptionsForm(options);
		});

		// apply the changes
		$('#service-options-inspector').on('inspector.save', function() {
			if (!optValidator.validate()) {
				return false;
			}

			// get saved record
			var options = getServiceOptionsData();

			options.forEach((option) => {
				// create new card
				let fieldset = vapAddServiceOptionCard(option);

				// refresh card details
				vapRefreshServiceOptionCard(fieldset.find('.vap-card'), option);
			});

			// auto-close on save
			$(this).inspector('dismiss');
		});
	});

	function vapDeleteServiceOptionCard(index) {
		// ask confirmation
		if (!confirm(Joomla.JText._('VAPSYSTEMCONFIRMATIONMSG'))) {
			return false;
		}

		// get fieldset to delete
		let fieldset = jQuery('#service-option-fieldset-' + index);

		// get database option ID
		let id = fieldset.data('id');

		if (id) {
			// register option to delete
			jQuery('#adminForm').append('<input type="hidden" name="option_deleted[]" value="' + id + '" />');
		}

		fieldset.remove();
	}

	function vapAddServiceOptionCard(data) {
		let index = OPTIONS_COUNT++;

		SELECTED_OPTION = 'service-option-fieldset-' + index;

		var html = jQuery('#service-option-struct').clone().html();

		html = html.replace(/{id}/, index);

		jQuery('#cards-service-options').append(
			'<div class="vap-card-fieldset up-to-1" id="service-option-fieldset-' + index + '" data-id-option="' + data.id + '">' + html + '</div>'
		);

		// get created fieldset
		let fieldset = jQuery('#' + SELECTED_OPTION);

		fieldset.vapcard('edit', 'vapDeleteServiceOptionCard(' + index + ')');

		// create input to hold option ID
		let input = jQuery('<input type="hidden" name="id_option[]" />').val(data.id);

		// append input to fieldset
		fieldset.append(input);

		return fieldset;
	}

	function vapRefreshServiceOptionCard(elem, data) {
		// update badge
		var icon;

		elem.vapcard('primary', data.name);

		if (parseInt(data.published) == 1) {
			icon = 'fas fa-check-circle';
			elem.addClass('published');
		} else {
			icon = 'far fa-circle';
			elem.removeClass('published');
		}

		elem.vapcard('badge', '<i class="' + icon + '"></i>');
	}

</script>
