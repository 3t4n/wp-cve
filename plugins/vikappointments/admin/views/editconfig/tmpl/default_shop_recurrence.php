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

$params = $this->params;

$vik = VAPApplication::getInstance();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewConfigShopRecurrence". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('ShopRecurrence');

?>

<div class="config-fieldset">

	<div class="config-fieldset-body">
		
		<!-- ENABLE RECURRENCE - Radio Button -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['enablerecur'] == 1, 'onClick="recurrenceValueChanged(1);"');
		$no  = $vik->initRadioElement('', '', $params['enablerecur'] == 0, 'onClick="recurrenceValueChanged(0);"');
		
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIGREC1'),
			'content' => JText::translate('VAPMANAGECONFIGREC1_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIGREC1') . $help);
		echo $vik->radioYesNo('enablerecur', $yes, $no);
		echo $vik->closeControl();
		?>
		
		<!-- RECURRENCE REPEAT BY - Checkbox List -->
		
		<?php
		$control = array();
		$control['style'] = $params['enablerecur'] == 0 ? 'display:none;' : '';

		$repeat_by = explode(';', $params['repeatbyrecur']);

		echo $vik->openControl(JText::translate('VAPMANAGECONFIGREC2'), 'vaprecurrtr', $control);
		
		for ($i = 0; $i < 5; $i++)
		{
			?>
			<input type="checkbox" name="repeatby<?php echo ($i + 1); ?>" value="1" id="repeatby<?php echo ($i + 1); ?>" <?php echo (!empty($repeat_by[$i]) ? "checked=\"checked\"" : "" ); ?> class="recurrence-by-renderer" />
			
			<label for="repeatby<?php echo ($i + 1); ?>" style="display: inline-block; margin-right: 6px;">
				<?php echo JText::translate('VAPMANAGECONFIGRECSINGOPT' . ($i + 1)); ?>
			</label>
			<?php
		}
		
		echo $vik->closeControl();
		?>
		
		<!-- RECURRENCE MIN VALUE - Number -->
		
		<?php echo $vik->openControl(JText::translate('VAPMANAGECONFIGREC3'), 'vaprecurrtr', $control); ?>
			<input type="number" name="minamountrecur" id="minamountrecur" value="<?php echo $params['minamountrecur']; ?>" size="40" min="1" max="999" />
		<?php echo $vik->closeControl(); ?>
		
		<!-- RECURRENCE MAX VALUE - Number -->
		
		<?php echo $vik->openControl(JText::translate('VAPMANAGECONFIGREC4'), 'vaprecurrtr', $control); ?>
			<input type="number" name="maxamountrecur" id="maxamountrecur" value="<?php echo $params['maxamountrecur']; ?>" size="40" min="1" max="999" />
		<?php echo $vik->closeControl(); ?>
		
		<!-- RECURRENCE FOR NEXT - Checkbox List -->
		
		<?php
		$for_next = explode(';', $params['fornextrecur']);

		echo $vik->openControl(JText::translate('VAPMANAGECONFIGREC5'), 'vaprecurrtr', $control);
			
		for ($i = 0; $i < 3; $i++)
		{
			?>
			<input type="checkbox" name="fornext<?php echo ($i + 1); ?>" value="1" id="fornext<?php echo ($i + 1); ?>" <?php echo ($for_next[$i] == 1 ? "checked=\"checked\"" : "" ); ?> class="recurrence-for-renderer" />
			
			<label for="fornext<?php echo ($i + 1); ?>" style="display: inline-block; margin-right: 6px;">
				<?php echo JText::translate('VAPMANAGECONFIGRECMULTOPT' . ($i + 1)); ?>
			</label>
			<?php
		}
			
		echo $vik->closeControl();
		?>
		
		<!-- RECURRENCE DEMO BOX -->
		
		<?php echo $vik->openControl(JText::translate(''), 'vaprecurrtr', $control); ?>
			<div class="inline-fields">
				<span><?php echo JText::translate('VAPMANAGECONFIGREC2'); ?></span>
				<select id="vaprecrepeatbysel" class="short"></select>
				&nbsp;&nbsp;
				<span><?php echo JText::translate('VAPMANAGECONFIGREC5'); ?></span>
				<select id="vaprecamountsel" class="short"></select>
				&nbsp;
				<select id="vaprecfornextsel" class="short"></select>
			</div>
			
			<div style="margin-top: 5px;"><small><em><?php echo JText::translate('VAPMANAGECONFIGREC6'); ?></em></small></div>
			
		<?php echo $vik->closeControl(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigShopRecurrence","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Shop > Recurrence > Details fieldset.
		 *
		 * @since 1.7
		 */
		if (isset($forms['basic']))
		{
			echo $forms['basic'];

			// unset details form to avoid displaying it twice
			unset($forms['basic']);
		}
		?>

	</div>
	
</div>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewConfigShopRecurrence","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed as new fieldsets
 * within the Shop > Recurrence tab.
 *
 * @since 1.7
 */
foreach ($forms as $formTitle => $formHtml)
{
	?>
	<div class="config-fieldset">
		
		<div class="config-fieldset-head">
			<h3><?php echo JText::translate($formTitle); ?></h3>
		</div>

		<div class="config-fieldset-body">
			<?php echo $formHtml; ?>
		</div>
		
	</div>
	<?php
}

JText::script('VAPMANAGECONFIGRECSINGOPT1');
JText::script('VAPMANAGECONFIGRECSINGOPT2');
JText::script('VAPMANAGECONFIGRECSINGOPT3');
JText::script('VAPMANAGECONFIGRECSINGOPT4');
JText::script('VAPMANAGECONFIGRECSINGOPT5');
JText::script('VAPMANAGECONFIGRECMULTOPT1');
JText::script('VAPMANAGECONFIGRECMULTOPT2');
JText::script('VAPMANAGECONFIGRECMULTOPT3');
?>

<script>

	jQuery(function($) {
		$('.recurrence-by-renderer').on('change', () => {
			const dropdown = $('#vaprecrepeatbysel');

			var selected = dropdown.select2('val');
			dropdown.html('');
			
			const ordering = [1, 2, 4, 3, 5];

			for (let i = 0; i < ordering.length; i++) {
				if ($('#repeatby' + ordering[i]).is(':checked')) {
					let opt = $('<option></option>')
						.val(ordering[i])
						.text(Joomla.JText._('VAPMANAGECONFIGRECSINGOPT' + ordering[i]));

					dropdown.append(opt);
				}
			}

			if (dropdown.find('option[value="' + selected + '"]').length == 0) {
				selected = dropdown.find('option').first().val();
			}

			// refresh selected value
			dropdown.select2('val', selected);
		});

		$('.recurrence-for-renderer').on('change', () => {
			const dropdown = $('#vaprecfornextsel');

			var selected = dropdown.select2('val');
			dropdown.html('');
			
			for (var i = 0; i < 3; i++) {
				if ($('#fornext' + (i + 1)).is(':checked')) {
					let opt = $('<option></option>')
						.val(i + 1)
						.text(Joomla.JText._('VAPMANAGECONFIGRECMULTOPT' + (i + 1)));

					dropdown.append(opt);
				}
			}

			if (dropdown.find('option[value="' + selected + '"]').length == 0) {
				selected = dropdown.find('option').first().val();
			}

			// refresh selected value
			dropdown.select2('val', selected);
		});

		$('#minamountrecur, #maxamountrecur').on('change', () => {
			const dropdown = $('#vaprecamountsel');

			var selected = dropdown.select2('val');
			dropdown.html('');
			
			var min = parseInt($('#minamountrecur').val());
			var max = parseInt($('#maxamountrecur').val());

			if (min > max) {
				min = max;

				$('#minamountrecur').val(min);
			}
			 
			for (var i = min; i <= max; i++) {
				dropdown.append('<option value="' + i + '">' + i + '</option>');
			}

			if (dropdown.find('option[value="' + selected + '"]').length == 0) {
				selected = dropdown.find('option').first().val();
			}

			// refresh selected value
			dropdown.select2('val', selected);
		});

		// set up recurrence preview
		$('.recurrence-by-renderer')
			.add('.recurrence-for-renderer')
			.add('#minamountrecur, #maxamountrecur')
			.trigger('change');
	});

	function recurrenceValueChanged(is) {
		if (is) {
			jQuery('.vaprecurrtr').show();
		} else {
			jQuery('.vaprecurrtr').hide();
		}
	}

</script>
