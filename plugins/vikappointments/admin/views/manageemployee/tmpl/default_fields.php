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

<div class="row-fluid">

	<div class="span6 full-width">
		<?php
		echo $vik->openEmptyFieldset();
		
		/**
		 * Render the custom fields form by using the apposite helper.
		 *
		 * Looking for a way to override the custom fields? Take a look
		 * at "/layouts/form/fields/" folder, which should contain all
		 * the supported types of custom fields.
		 *
		 * @since 1.7
		 */
		echo VAPCustomFieldsRenderer::display($this->customFields, (array) $this->employee, $strict = false);
			
		echo $vik->closeEmptyFieldset();
		?>
	</div>

	<?php
	/**
	 * Look for any additional fields to be pushed within
	 * the "Custom Fields" fieldset (right-side).
	 *
	 * NOTE: retrieved from "onDisplayViewEmployee" hook.
	 *
	 * @since 1.7
	 */
	if (isset($this->forms['fields']))
	{
		?>
		<div class="span6 full-width">
			<?php
			echo $vik->openEmptyFieldset();
			echo $this->forms['fields'];
			echo $vik->closeEmptyFieldset();
			?>
		</div>
		<?php

		// unset details form to avoid displaying it twice
		unset($this->forms['fields']);
	}
	?>

	<!-- Define role to detect the supported hook -->
	<!-- {"rule":"customizer","event":"onDisplayViewEmployee","type":"field","key":"fields"} -->

</div>

<?php
JText::script('JGLOBAL_SELECT_AN_OPTION');
?>

<script>

	jQuery(function($) {
		// render select
		$('select.custom-field').each(function() {
			// check whether the first option is a placeholder
			let hasPlaceholder = $(this).find('option').first().text().length == 0;

			$(this).select2({
				// check whether we should specify a placeholder
				placeholder: hasPlaceholder ? Joomla.JText._('JGLOBAL_SELECT_AN_OPTION') : '',
				// disable search for select with 3 or lower options
				minimumResultsForSearch: $(this).find('option').length > 3 ? 0 : -1,
				// check whether the field supports empty values
				allowClear: !$(this).hasClass('required') && hasPlaceholder ? true : false,
				width: '90%',
			});
		});
	});

</script>
