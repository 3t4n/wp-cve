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

JHtml::fetch('vaphtml.assets.select2');
JHtml::fetch('vaphtml.assets.fontawesome');
JHtml::fetch('vaphtml.scripts.selectflags', '#vap-lang-sel', array('width' => '90%', 'allowClear' => true));

$args = $this->args;

$vik = VAPApplication::getInstance();

?>

<div class="ratestest" style="padding: 10px;">

	<form action="index.php" method="post" name="adminForm" id="adminForm">

		<!-- MAIN -->

		<div class="row-fluid">

			<!-- SEARCH -->

			<div class="span6 full-width">
				<?php echo $vik->openEmptyFieldset(); ?>

					<!-- TAX - Select -->

					<?php
					$taxes = JHtml::fetch('vaphtml.admin.taxes', $blank = '');

					echo $vik->openControl(JText::translate('VAPTAXFIELDSET') . '*'); ?>
						<select name="id_tax" id="vap-taxes-sel" class="required">
							<?php echo JHtml::fetch('select.options', $taxes, 'value', 'text', $args['id_tax']); ?>
						</select>
					<?php echo $vik->closeControl(); ?>

					<!-- AMOUNT -->

					<?php echo $vik->openControl(JText::translate('VAPMANAGESUBSCR2') . '*'); ?>
						<div class="input-prepend currency-field">
							<button type="button" class="btn"><?php echo VAPFactory::getCurrency()->getSymbol(); ?></button>

							<input type="number" name="amount" value="<?php echo $args['amount']; ?>" min="0" step="any" class="required" />
						</div>
					<?php echo $vik->closeControl(); ?>

					<!-- LANGUAGE -->

					<?php
					$options = JHtml::fetch('contentlanguage.existing');
					
					echo $vik->openControl(JText::translate('VAPLANGUAGE')); ?>
						<select name="langtag" id="vap-lang-sel">
							<option></option>
							<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $args['langtag']); ?>
						</select>
					<?php echo $vik->closeControl(); ?>

					<!-- SUBMIT - Button -->

					<?php echo $vik->openControl(''); ?>
						<button type="button" class="btn" id="test-tax-btn">
							<?php echo JText::translate('VAPTESTTAXES'); ?>
						</button>
					<?php echo $vik->closeControl(); ?>

				<?php echo $vik->closeEmptyFieldset(); ?>
			</div>

			<!-- RESULT -->

			<div class="span6" id="result-wrap" style="display: none;">
				<?php echo $vik->openEmptyFieldset(); ?>

					<table class="rates-table table" id="tax-table" style="display: none;">
						<thead>
							<tr>
								<th><?php echo JText::translate('JDETAILS'); ?></th>
								<th style="text-align: right;"><?php echo JText::translate('VAPMANAGESUBSCR2'); ?></th>
								<th style="text-align: right;"><?php echo JText::translate('VAPCOUPONVALUETYPE2'); ?></th>
							</tr>
						</thead>

						<tbody></tbody>

						<tfoot></tfoot>
					</table>

				<?php echo $vik->closeEmptyFieldset(); ?>
			</div>

		</div>

	</form>

</div>

<?php
JText::script('VAPCONNECTIONLOSTERROR');
JText::script('VAPINVTOTAL');
JText::script('VAPINVTAXES');
JText::script('VAPINVGRANDTOTAL');
?>

<script>

	var validator = new VikFormValidator('#adminForm');
	
	jQuery(function($) {

		$('#vap-taxes-sel').select2({
			placeholder: '--',
			allowClear: false,
			width: '90%',
		});

		$('#test-tax-btn').on('click', function() {
			$('#result-wrap').show();
			$('#tax-table').hide();

			if (!validator.validate()) {
				return false;
			}

			$(this).attr('disabled', true);

			UIAjax.do(
				'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=tax.testajax'); ?>',
				jQuery('#adminForm').serialize(),
				(obj) => {
					// for debug purposes
					console.log(obj);

					// build tax table
					buildTaxTable(obj);

					$(this).attr('disabled', false);
				},
				(err) => {
					alert(Joomla.JText._('VAPCONNECTIONLOSTERROR'));

					$(this).attr('disabled', false);
				}
			);
		});

		// helper function used to build the resulting table
		const buildTaxTable = (data) => {
			var table = $('#tax-table');
			var tbody = table.find('tbody');
			var tfoot = table.find('tfoot');

			// reset body and footer contents
			tbody.html('');
			tfoot.html('');

			// append total net to table body
			tbody.append(createTableRow(Joomla.JText._('VAPINVTOTAL'), null, data.net));

			let total = data.net;

			// iterate breakdowns
			data.breakdown.forEach((bd) => {
				// increment net by the given taxes
				total += parseFloat(bd.tax);

				// append breakdown to body
				tbody.append(createTableRow(bd.name, bd.tax, total, {class: 'rate-child'}));
			});

			// append total taxes to footer
			tfoot.append(createTableRow(Joomla.JText._('VAPINVTAXES'), null, data.tax, {class: 'final-cost'}));
			// append total gross to footer
			tfoot.append(createTableRow(Joomla.JText._('VAPINVGRANDTOTAL'), null, data.gross, {class: 'final-cost'}));

			table.show();
		}

		// helper function used to build a table row
		const createTableRow = (details, cost, total, attrs) => {
			const tr = $('<tr></tr>');

			if (attrs) {
				// set up row attributes
				for (var k in attrs) {
					tr.attr(k, attrs[k]);
				}
			}

			const currency = Currency.getInstance();

			// create details column
			const detailsTD = $('<td></td>');
			detailsTD.html(details);
			detailsTD.css('text-align', 'left');

			// create cost column
			const costTD = $('<td></td>');
			costTD.css('text-align', 'right');
			
			if (cost !== null && cost !== undefined) {
				costTD.html(currency.format(cost));
			}

			// create total column
			const totalTD = $('<td></td>');
			totalTD.css('text-align', 'right');
			totalTD.addClass('rate-price');
			
			if (total !== null && total !== undefined) {
				totalTD.html(currency.format(total));
			}

			// build row
			tr.append(detailsTD);
			tr.append(costTD);
			tr.append(totalTD);

			return tr;
		}

	});

</script>
