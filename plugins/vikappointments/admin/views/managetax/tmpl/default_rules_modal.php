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

<div class="inspector-form" id="inspector-tax-rule-form">

	<?php echo $vik->bootStartTabSet('taxrule', array('active' => 'taxrule_details')); ?>

		<!-- DETAILS -->

		<?php echo $vik->bootAddTab('taxrule', 'taxrule_details', JText::translate('VAPCUSTFIELDSLEGEND1')); ?>

			<div class="inspector-fieldset">

				<!-- NAME - Text -->

				<?php echo $vik->openControl(JText::translate('VAPMANAGEOPTION2') . '*'); ?>
					<input type="text" id="rule_name" class="required" />
				<?php echo $vik->closeControl(); ?>

				<!-- MODIFIER - Select -->

				<?php
				$options = array(
					JHtml::fetch('select.option', 1, JText::translate('VAPTAXAPPLY_OPT1')),
					JHtml::fetch('select.option', 2, JText::translate('VAPTAXAPPLY_OPT2')),
				);

				echo $vik->openControl(JText::translate('VAPTAXAPPLY')); ?>
					<select id="rule_apply">
						<?php echo JHtml::fetch('select.options', $options); ?>
					</select>
				<?php echo $vik->closeControl(); ?>

				<!-- OPERATION - Select -->

				<?php
				$options = array();

				// get list of supported math operators
				foreach (VAPTaxFactory::getMathOperators() as $value => $text)
				{
					$options[] = JHtml::fetch('select.option', $value, $text);
				}

				echo $vik->openControl(JText::translate('VAPTAXMATHOP')); ?>
					<select id="rule_operator">
						<?php echo JHtml::fetch('select.options', $options); ?>
					</select>
				<?php echo $vik->closeControl(); ?>

				<!-- AMOUNT - Number -->

				<?php echo $vik->openControl(JText::translate('VAPMANAGESUBSCR2')); ?>
					<input type="number" id="rule_amount" step="any" min="0" />
				<?php echo $vik->closeControl(); ?>

				<!-- TAX CAP - Number -->

				<?php
				$help = $vik->createPopover(array(
					'title'   => JText::translate('VAPTAXCAP'),
					'content' => JText::translate('VAPTAXCAP_HELP'),
				));

				echo $vik->openControl(JText::translate('VAPTAXCAP') . $help); ?>
					<div class="input-prepend currency-field">
						<span class="btn"><?php echo VAPFactory::getCurrency()->getSymbol(); ?></span>

						<input type="number" id="rule_cap" step="any" min="0" />
					</div>
				<?php echo $vik->closeControl(); ?>

			</div>

		<?php echo $vik->bootEndTab(); ?>

		<!-- BREAKDOWN -->

		<?php echo $vik->bootAddTab('taxrule', 'taxrule_breakdown', JText::translate('VAPTAXBREAKDOWN')); ?>

			<div style="display:none;" id="tax-bd-repeat">

				<div class="inspector-repeatable-head">
					<span class="tax-bd-summary">
						<i class="fas fa-ellipsis-v big hndl" style="margin-right: 4px;"></i>

						<span class="badge badge-info bd-name"></span>
						<span class="badge badge-important bd-amount"></span>
					</span>

					<span>
						<a href="javascript: void(0);" class="tax-rule-edit-bd no-underline">
							<i class="fas fa-pen-square big ok"></i>
						</a>

						<a href="javascript: void(0);" class="tax-rule-trash-bd no-underline">
							<i class="fas fa-minus-square big no"></i>
						</a>
					</span>
				</div>

				<div class="inspector-repeatable-body">

					<!-- NAME - Text -->

					<?php echo $vik->openControl(JText::translate('VATTAXBDLABEL')); ?>
						<input type="text" class="rule_breakdown_name" placeholder="<?php echo $this->escape(JText::translate('VATTAXBDPLACEHOLDER')); ?>" />
					<?php echo $vik->closeControl(); ?>

					<!-- AMOUNT - Number -->

					<?php echo $vik->openControl(JText::translate('VAPMANAGESUBSCR2')); ?>
						<div class="input-append">
							<input type="number" class="rule_breakdown_amount" />

							<span class="btn">%</span>
						</div>
					<?php echo $vik->closeControl(); ?>

					<input type="hidden" class="rule_breakdown_id" />

				</div>

			</div>

			<div class="inspector-repeatable-container" id="tax-bd-pool">
				
			</div>

			<!-- ADD TIME -->

			<?php echo $vik->openControl(''); ?>
				<button type="button" class="btn" id="add-tax-breakdown"><?php echo JText::translate('VAPADD'); ?></button>
			<?php echo $vik->closeControl(); ?>

		<?php echo $vik->bootEndTab(); ?>

	<?php echo $vik->bootEndTabSet(); ?>

	<input type="hidden" id="rule_id" value="" />

</div>

<?php
JText::script('VAPSYSTEMCONFIRMATIONMSG');
?>

<script>

	jQuery(function($) {
		$('#rule_apply, #rule_operator').select2({
			minimumResultsForSeach: -1,
			allowClear: false,
			width: '100%',
		});

		$('#add-tax-breakdown').on('click', () => {
			addTaxBreakdown();
		});

		$('#tax-bd-pool').sortable({
			items:  '.inspector-repeatable',
			revert: false,
			axis:   'y',
			handle: '.hndl',
			cursor: 'move',
		});
	});

	var ruleValidator = new VikFormValidator('#inspector-tax-rule-form');

	function fillTaxRuleForm(data) {
		// update name
		if (data.name === undefined) {
			data.name = '';
		}

		jQuery('#rule_name').val(data.name);

		ruleValidator.unsetInvalid(jQuery('#rule_name'));

		// update modifier
		if (data.apply === undefined) {
			data.apply = 1;
		}

		jQuery('#rule_apply').select2('val', data.apply);

		// update operator
		if (data.operator === undefined) {
			data.operator = 'add';
		}

		jQuery('#rule_operator').select2('val', data.operator);

		// update amount
		if (data.amount === undefined) {
			data.amount = 0.0;
		}

		jQuery('#rule_amount').val(data.amount);

		// update cap
		if (data.cap === undefined) {
			data.cap = 0.0;
		}

		jQuery('#rule_cap').val(data.cap);

		// populate breakdown
		if (data.breakdown === undefined) {
			data.breakdown = [];
		}

		jQuery('#tax-bd-pool').html('');

		data.breakdown.forEach((bd) => {
			addTaxBreakdown(bd);
		});
		
		// update ID
		jQuery('#rule_id').val(data.id);
	}

	function getTaxRuleData() {
		var data = {};

		// set ID
		data.id = jQuery('#rule_id').val();

		// set name
		data.name = jQuery('#rule_name').val();

		// set modifier
		data.apply = jQuery('#rule_apply').val();

		// set operator
		data.operator = jQuery('#rule_operator').val();

		// set amount
		data.amount = parseFloat(jQuery('#rule_amount').val());
		data.amount = isNaN(data.amount) ? 0.0 : data.amount;

		// set cap
		data.cap = parseFloat(jQuery('#rule_cap').val());
		data.cap = isNaN(data.cap) ? 0.0 : data.cap;

		// set breakdown
		data.breakdown = [];

		// iterate forms
		jQuery('#tax-bd-pool .inspector-repeatable').each(function() {
			let tmp = {};

			// retrieve breakdown ID
			tmp.id = parseInt(jQuery(this).find('input.rule_breakdown_id').val());

			// retrieve breakdown name
			tmp.name = jQuery(this).find('input.rule_breakdown_name').val();

			// retrieve breakdown amount
			tmp.amount = parseFloat(jQuery(this).find('input.rule_breakdown_amount').val());
			tmp.amount = isNaN(tmp.amount) ? 0.0 : tmp.amount;

			// register breakdown only if not empty
			if (tmp.name.length || tmp.amount != 0) {
				data.breakdown.push(tmp);
			}
		});

		return data;
	}

	function addTaxBreakdown(data) {
		if (typeof data !== 'object') {
			data = {};
		}

		let form = jQuery('#inspector-tax-rule-form');

		// get repeatable form of the inspector
		var repeatable = jQuery(form).find('#tax-bd-repeat');
		// clone the form
		var clone = jQuery('<div class="inspector-repeatable"></div>')
			.append(repeatable.clone().html());

		let nameInput = clone.find('input.rule_breakdown_name');

		// set up breakdown name/label
		if (typeof data.name !== 'undefined') {
			nameInput.val(data.name);

			// auto-collapse existing blocks
			clone.addClass('collapsed');
		}

		let amountInput = clone.find('input.rule_breakdown_amount');

		// set up breakdown amount
		if (typeof data.amount !== 'undefined') {
			amountInput.val(data.amount);
		}

		let idInput = clone.find('input.rule_breakdown_id');

		// set up breakdown ID
		idInput.val(data.id || getIncrementalBreakdownID());

		// refresh head every time something changes
		jQuery(nameInput).add(amountInput).on('change', function() {
			let amount = parseFloat(jQuery(amountInput).val());

			if (isNaN(amount) || amount <= 0) {
				jQuery(amountInput).val(Math.max(1, jQuery('#rule_amount').val()));
			}

			vapRefreshSummaryBreakdown(clone);
		});

		// set up summary head
		vapRefreshSummaryBreakdown(clone);

		// handle delete button
		clone.find('.tax-rule-trash-bd').on('click', () => {
			if (confirm(Joomla.JText._('VAPSYSTEMCONFIRMATIONMSG'))) {
				clone.remove();
			}
		});

		// handle edit button
		clone.find('.tax-rule-edit-bd').on('click', () => {
			clone.toggleClass('collapsed');
		});

		// append the clone to the document
		jQuery('#tax-bd-pool').append(clone);

		// start by focusing "name" input
		nameInput.focus();
	}

	function vapRefreshSummaryBreakdown(block) {
		// extract name from block
		let name = block.find('input.rule_breakdown_name').val();

		// extract amount from block
		let amount = parseFloat(block.find('input.rule_breakdown_amount').val());
		amount = isNaN(amount) ? 0 : amount;

		// set badge within block head
		block.find('.tax-bd-summary').find('.bd-name').text(name);
		block.find('.tax-bd-summary').find('.bd-amount').text(amount + '%');
	}

	function getIncrementalBreakdownID() {
		let max = 0;

		jQuery('#tax-bd-pool input.rule_breakdown_id').each(function() {
			max = Math.max(max, parseInt(jQuery(this).val()));
		});

		return max + 1;
	}

</script>
