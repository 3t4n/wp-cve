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

<div class="inspector-form" id="inspector-app-opt-form">

	<div class="inspector-fieldset">

		<h3><?php echo JText::translate('VAPCUSTFIELDSLEGEND1'); ?></h3>

		<input type="hidden" id="option_id" value="0" />

		<!-- OPTION - Dropdown -->

		<?php
		// get options supported by the selected service
		$options = JHtml::fetch('vaphtml.admin.options', $strict = false, $blank = '', $group = true, $this->reservation->id_service);
		
		echo $vik->openControl(JText::translate('VAPMANAGERESERVATION14') . '*');

		// create dropdown attributes
		$params = array(
			'id'          => 'vap-app-option-sel',
			'group.items' => null,
			'list.select' => null,
			'list.attr'   => 'class="required"',
		);
		
		// render select
		echo JHtml::fetch('select.groupedList', $options, null, $params);
			
		echo $vik->closeControl();
		?>

		<!-- VARIATION -->

		<?php echo $vik->openControl(JText::translate('VAPMANAGERESERVATION39') . '*', 'var-control', array('style' => 'display:none;')); ?>
			<select id="option_id_variation"></select>
		<?php echo $vik->closeControl(); ?>

		<!-- QUANTITY -->

		<?php echo $vik->openControl(JText::translate('VAPQUANTITY') . '*'); ?>
			<input type="number" id="option_quantity" min="1" step="1" class="required" />
		<?php echo $vik->closeControl(); ?>

		<!-- PRICE -->

		<?php echo $vik->openControl(JText::translate('VAPMANAGEPACKAGE3') . '*'); ?>
			<div class="input-prepend currency-field">
				<span class="btn"><?php echo VAPFactory::getCurrency()->getSymbol(); ?></span>

				<input type="number" id="option_inc_price" min="0" step="any" class="required" />
			</div>
		<?php echo $vik->closeControl(); ?>

		<input type="hidden" id="option_discount" value="" />

	</div>

</div>

<?php
JText::script('VAPFILTERSELECTOPTION');
JText::script('VAPFILTERSELECTVAL');
JText::script('VAP_AJAX_GENERIC_ERROR');
?>

<script>

	var appOptValidator = new VikFormValidator('#inspector-app-opt-form');
	var PREV_VAR_PRICE = 0;

	jQuery(function($) {
		const form = $('#inspector-app-opt-form');

		$('#vap-app-option-sel').select2({
			placeholder: Joomla.JText._('VAPFILTERSELECTOPTION'),
			allowClear: false,
			width: '100%',
		});

		$('#option_id_variation').select2({
			placeholder: Joomla.JText._('VAPFILTERSELECTVAL'),
			allowClear: false,
			width: '100%',
		});

		$('#vap-app-option-sel').on('change', function() {
			// get option fields
			let fields = $(this).add(form.find('input[id^="option_"]'));
			// disable fields before request
			fields.prop('disabled', true);

			// load option details
			loadOptionDetails().then((data) => {
				// fetch selected quantity and make sure it doesn't exceed the max amount
				let quantity = parseInt($('#option_quantity').val());
				quantity = isNaN(quantity) ? 1 : Math.min(quantity, data.maxq);

				// update quantity and field attributes
				$('#option_quantity').val(quantity);

				// update price with new one
				$('#option_inc_price').val(data.price);
				// reset current variation price
				PREV_VAR_PRICE = 0;
			}).catch((err) => {
				// raise error message
				alert(err);
			}).finally(() => {
				// finally re-enable fields
				fields.prop('disabled', false);
			});
		});

		$('#option_id_variation').on('change', function() {
			// get variation incremental price
			let incPrice = parseFloat($(this).find('option:selected').attr('data-price'));
			incPrice = isNaN(incPrice) ? 0 : incPrice;

			// get option price
			let optPrice = parseFloat($('#option_inc_price').val());
			optPrice = isNaN(optPrice) ? 0 : optPrice;

			currency = Currency.getInstance();

			// subtract previous variation price from current cost
			optPrice = currency.diff(optPrice, PREV_VAR_PRICE);
			// add variation price to base option cost
			optPrice = currency.sum(optPrice, incPrice);

			PREV_VAR_PRICE = incPrice;

			$('#option_inc_price').val(optPrice);
		});
	});

	function fillAppOptionForm(data) {
		// update quantity
		if (data.quantity === undefined) {
			data.quantity = 1;
		}

		jQuery('#option_quantity').val(data.quantity);

		appOptValidator.unsetInvalid(jQuery('#option_quantity'));

		// update price
		if (data.inc_price === undefined) {
			data.inc_price = 0.0;
		}

		jQuery('#option_inc_price').val(data.inc_price);

		appOptValidator.unsetInvalid(jQuery('#option_inc_price'));

		// update discount
		if (data.discount === undefined) {
			data.discount = 0.0;
		}

		jQuery('#option_discount').val(data.discount);
		
		// update ID
		jQuery('#option_id').val(data.id);

		// clear variation ID
		jQuery('#option_id_variation').select2('val', null);
		// hide variation field by default
		jQuery('.var-control').hide();

		if (data.id_option) {
			jQuery('#vap-app-option-sel').select2('val', data.id_option);

			// load option details
			loadOptionDetails().then((option) => {
				if (data.id_variation && parseInt(data.id_variation) > 0) {
					// auto-select variation
					jQuery('#option_id_variation').select2('val', data.id_variation);
					// register current incremental price of the variation
					PREV_VAR_PRICE = parseFloat(jQuery('#option_id_variation option:selected').attr('data-price'));
				}

				// commit changes after loading the option details, needed to
				// avoid prompting the alert when nothing has changed
				jQuery('#order-app-opt-inspector').trigger('inspector.commit');
			}).catch((err) => {
				// raise error message
				alert(err);
			});
		} else {
			jQuery('#vap-app-option-sel').select2('val', null);
		}

		appOptValidator.unsetInvalid(jQuery('#vap-app-option-sel'));
	}

	function getAppOptionData() {
		var data = {};

		// set ID
		data.id = jQuery('#option_id').val();

		// set option ID
		data.id_option = parseInt(jQuery('#vap-app-option-sel').val());

		// set variation ID
		data.id_variation = parseInt(jQuery('#option_id_variation').val());
		data.id_variation = isNaN(data.id_variation) ? 0 : data.id_variation;

		// set option name
		data.name = jQuery('#vap-app-option-sel option:selected').text().trim();
		// set variation name
		data.var_name = jQuery('#option_id_variation option:selected').attr('data-name');

		// set incremental price
		data.inc_price = parseFloat(jQuery('#option_inc_price').val());
		data.inc_price = isNaN(data.inc_price) ? 0 : Math.max(0, data.inc_price);

		// set discount
		data.discount = parseFloat(jQuery('#option_discount').val());
		data.discount = isNaN(data.discount) ? 0 : Math.max(0, data.discount);

		// set quantity
		data.quantity = parseInt(jQuery('#option_quantity').val());
		data.quantity = isNaN(data.quantity) ? 1 : Math.max(1, data.quantity);

		const currency = Currency.getInstance();

		// calculate base cost by multiplying the price by the selected
		// quantity and subtracting the discount, if any
		let baseCost = currency.multiply(data.inc_price, data.quantity);
		baseCost = currency.diff(baseCost, data.discount);

		// create promise to load the costs of the option (tax, net, gross...)
		return new Promise((resolve, reject) => {
			UIAjax.do(
				'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=tax.testajax'); ?>',
				{
					id_tax:  data.id_option,
					amount:  baseCost,
					id_user: jQuery('#vap-users-select').val(),
					langtag: '<?php echo $this->reservation->langtag; ?>',
					subject: 'option',
				},
				(bill) => {
					// assign fetched prices to item object
					Object.assign(data, bill);

					// rename breakdown property
					data.tax_breakdown = data.breakdown;
					delete data.breakdown;

					resolve(data);
				},
				(err) => {
					reject(err.responseText || Joomla.JText._('VAP_AJAX_GENERIC_ERROR'));
				}
			);
		});
	}

	function loadOptionDetails() {
		// create new promise for ease of use
		return new Promise((resolve, reject) => {
			// load option details via AJAX
			makeOptionRequest().then((data) => {
				if (data.maxqpeople > 0) {
					// the maximum quantity depends on the number of participants
					data.maxq = parseInt(jQuery('#app_people').val());
					data.maxq = isNaN(data.maxq) || data.maxq <= 0 ? 1 : data.maxq;

					if (data.maxqpeople == 2) {
						// must be equal to the number of participants
						jQuery('#option_quantity').val(data.maxq).attr('min', data.maxq);
					}
				}

				// update quantity attributes
				jQuery('#option_quantity').attr('max', data.maxq).prop('readonly', data.maxq == 1);

				// build variations dropdown on success
				let varSelect = jQuery('#option_id_variation');
				// always reset options
				varSelect.html('');

				// check whether the option supports any variations
				if (data.variations.length) {
					currency = Currency.getInstance();

					// add empty option
					varSelect.append('<option></option>');

					data.variations.forEach((variation) => {
						let name = variation.name;

						if (variation.inc_price != 0) {
							// add variation price to name
							name += ' (' + currency.format(variation.inc_price) + ')';
						}

						// create variation option and append it to the select
						varSelect.append(
							jQuery('<option></option>')
								.val(variation.id)
								.attr('data-name', variation.name)
								.attr('data-price', variation.inc_price)
								.html(name)
						);
					});

					// register field as mandatory and show it
					appOptValidator.registerFields(varSelect);
					varSelect.select2('val', null);
					jQuery('.var-control').show();
				} else {
					// no variations, hide field
					jQuery('.var-control').hide();
					appOptValidator.unregisterFields(varSelect);
					varSelect.select2('val', null);
				}

				// propagate data
				resolve(data);
			}).catch((err) => {
				// propagate error
				reject(err);
			});
		});
	}

	function makeOptionRequest() {
		// get selected option
		let id_opt = parseInt(jQuery('#vap-app-option-sel').select2('val'));

		// create promise for ease of use
		return new Promise((resolve, reject) => {
			// check whether the same option has been already loaded
			let data = VAPTempCache.get(['option', id_opt]);

			if (data) {
				// yep, immediately resolve promise with cached data
				resolve(data);
				return true;
			}

			// make request to load option details
			UIAjax.do(
				'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=option.detailsajax'); ?>',
				{
					id_opt: id_opt,
				},
				(data) => {
					// cache data for later use
					VAPTempCache.set(['option', id_opt], data);
					// resolve promise with fetched data
					resolve(data);
				},
				(err) => {
					// reject with returned error
					reject(err.responseText || Joomla.JText._('VAP_AJAX_GENERIC_ERROR'));
				}
			);
		});
	}

</script>
