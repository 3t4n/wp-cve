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

<div class="vap-cards-container cards-tax-rules" id="cards-tax-rules">

	<?php
	foreach ($this->tax->rules as $i => $rule)
	{
		?>
		<div class="vap-card-fieldset up-to-1" id="tax-rule-fieldset-<?php echo $i; ?>">

			<?php
			$displayData = array();

			// reduce card size
			$displayData['class'] = 'compress';

			// fetch primary text
			$displayData['primary'] = $rule->name;

			// fetch edit button
			$displayData['edit'] = 'vapOpenTaxRuleCard(\'' . $i . '\');';

			// render layout
			echo $optLayout->render($displayData);
			?>
			
			<input type="hidden" name="rule_json[]" value="<?php echo $this->escape(json_encode($rule)); ?>" />

		</div>
		<?php
	}
	?>

	<!-- ADD PLACEHOLDER -->

	<div class="vap-card-fieldset up-to-1 add add-tax-rule">
		<div class="vap-card compress">
			<i class="fas fa-plus"></i>
		</div>
	</div>

</div>

<div style="display:none;" id="tax-rule-struct">
			
	<?php
	// create structure for records
	$displayData = array();
	$displayData['class']   = 'compress';
	$displayData['primary'] = '';
	$displayData['edit']    = true;

	echo $optLayout->render($displayData);
	?>

</div>

<script>
	var OPTIONS_COUNT   = <?php echo count($this->tax->rules); ?>;
	var SELECTED_OPTION = null;

	jQuery(function($) {
		// open inspector for new rules
		$('.vap-card-fieldset.add-tax-rule').on('click', () => {
			vapOpenTaxRuleCard();
		});

		$('#cards-tax-rules').sortable({
			// exclude "add" boxes
			items: '.vap-card-fieldset:not(.add)',
			// hide "add" box when sorting starts
			start: function() {
				jQuery('.vap-card-fieldset.add-tax-rule').hide();
			},
			// show "add" box again when sorting stops
			stop: function() {
				jQuery('.vap-card-fieldset.add-tax-rule').show();
			},
		});

		// fill the form before showing the inspector
		$('#tax-rule-inspector').on('inspector.show', () => {
			var json = [];

			// fetch JSON data
			if (SELECTED_OPTION) {
				var fieldset = $('#' + SELECTED_OPTION);

				json = fieldset.find('input[name="rule_json[]"]').val();

				try {
					json = JSON.parse(json);
				} catch (err) {
					json = {};
				}
			}

			if (json.id === undefined) {
				// creating new record, hide delete button
				$('#tax-rule-inspector [data-role="delete"]').hide();
			} else {
				// editing existing record, show delete button
				$('#tax-rule-inspector [data-role="delete"]').show();
			}

			fillTaxRuleForm(json);
		});

		// apply the changes
		$('#tax-rule-inspector').on('inspector.save', function() {
			// validate form
			if (!ruleValidator.validate()) {
				return false;
			}

			// get saved record
			var data = getTaxRuleData();

			var fieldset;

			if (SELECTED_OPTION) {
				fieldset = $('#' + SELECTED_OPTION);
			} else {
				fieldset = vapAddTaxRuleCard(data);
			}

			if (fieldset.length == 0) {
				// an error occurred, abort
				return false;
			}

			// save JSON data
			fieldset.find('input[name="rule_json[]"]').val(JSON.stringify(data));

			// refresh card details
			vapRefreshTaxRuleCard(fieldset, data);

			// auto-close on save
			$(this).inspector('dismiss');
		});

		// delete the record
		$('#tax-rule-inspector').on('inspector.delete', function() {
			var fieldset = $('#' + SELECTED_OPTION);

			if (fieldset.length == 0) {
				// record not found
				return false;
			}

			// get existing record
			var json = fieldset.find('input[name="rule_json[]"]').val();

			try {
				json = JSON.parse(json);
			} catch (err) {
				json = {};
			}

			if (json.id) {
				// commit record delete
				$('#adminForm').append('<input type="hidden" name="rule_deleted[]" value="' + json.id + '" />');
			}

			// auto delete fieldset
			fieldset.remove();

			// auto-close on delete
			$(this).inspector('dismiss');
		});
	});

	function vapOpenTaxRuleCard(index) {
		if (typeof index !== 'undefined') {
			SELECTED_OPTION = 'tax-rule-fieldset-' + index;
		} else {
			SELECTED_OPTION = null;
		}

		// open inspector
		vapOpenInspector('tax-rule-inspector');
	}

	function vapAddTaxRuleCard(data) {
		let index = OPTIONS_COUNT++;

		SELECTED_OPTION = 'tax-rule-fieldset-' + index;

		var html = jQuery('#tax-rule-struct').clone().html();

		html = html.replace(/{id}/, index);

		jQuery(
			'<div class="vap-card-fieldset up-to-1" id="tax-rule-fieldset-' + index + '">' + html + '</div>'
		).insertBefore(jQuery('.vap-card-fieldset.add-tax-rule').last());

		// get created fieldset
		let fieldset = jQuery('#' + SELECTED_OPTION);

		fieldset.vapcard('edit', 'vapOpenTaxRuleCard(' + index + ')');

		// create input to hold JSON data
		let input = jQuery('<input type="hidden" name="rule_json[]" />').val(JSON.stringify(data));

		// append input to fieldset
		fieldset.append(input);

		return fieldset;
	}

	function vapRefreshTaxRuleCard(elem, data) {
		// update primary text
		elem.vapcard('primary', data.name);
	}

</script>
