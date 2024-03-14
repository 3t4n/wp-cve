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

$cart_enabled         = isset($displayData['cartEnabled'])       ? $displayData['cartEnabled']       : false;
$cart_empty           = isset($displayData['cartEmpty'])         ? $displayData['cartEmpty']         : true; 
$waiting_list_enabled = isset($displayData['waitlistEnabled'])   ? $displayData['waitlistEnabled']   : false;
$recurrence_enabled   = isset($displayData['recurrenceEnabled']) ? $displayData['recurrenceEnabled'] : false;
$recurrence           = isset($displayData['recurrenceParams'])  ? $displayData['recurrenceParams']  : array();
$itemid               = isset($displayData['itemid'])            ? $displayData['itemid']            : null;

if (is_null($itemid))
{
	// item id not provided, get the current one (if set)
	$itemid = JFactory::getApplication()->input->getInt('Itemid');
}

$vik = VAPApplication::getInstance();

if ($recurrence_enabled)
{ 		
	?>
	<div class="vaprecurrencediv <?php echo $vik->getThemeClass('background'); ?>" style="display: none;">
		
		<div class="vaprecurrenceprediv">
			<input type="checkbox" value="1" onChange="vapRecurrenceConfirmValueChanged();" id="vaprecokcheck" />

			<label for="vaprecokcheck"><?php echo JText::translate('VAPRECURRENCECONFIRM'); ?></label>
		</div>

		<div class="vaprecurrencenextdiv" style="display: none">
			
			<div class="recurrence-repeat-box">
				<span class="vaprecurrencerepeatlabel">
					<label for="vaprepeatbyrecsel"><?php echo JText::translate('VAPRECURRENCEREPEAT'); ?></label>
				</span>

				<span class="vaprecurrencerepeatselect">
					<select id="vaprepeatbyrecsel" onChange="vapRecurrenceSelectChanged();">
						<option value="0"><?php echo JText::translate('VAPRECURRENCENONE'); ?></option>
						<?php
						/**
						 * Added support for "fortnightly" and "bi-monthly" recurrence.
						 *
						 * @since 1.6.4
						 */
						$lookup = array(
							1 => 'VAPDAY',
							2 => 'VAPWEEK',
							4 => 'VAPFORTNIGHT',
							3 => 'VAPMONTH',
							5 => 'VAP2MONTHS',
						);

						$repeat_text = array();

						foreach ($lookup as $i => $label)
						{
							if ($recurrence['repeat'][$i - 1] == 1)
							{
								$repeat_text[] = JHtml::fetch('select.option', $i, JText::translate($label));
							}
						}

						echo JHtml::fetch('select.options', $repeat_text);
						?>
					</select>
				</span>
			</div>

			<div class="recurrence-for-box">
				<span class="vaprecurrenceforlabel">
					<label for="vapamountrecsel"><?php echo JText::translate('VAPRECURRENCEFOR'); ?></label>
				</span>

				<span class="vaprecurrenceamountselect">
					<select id="vapamountrecsel">
						<?php
						for ($i = $recurrence['min']; $i <= $recurrence['max']; $i++)
						{
							?>
							<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
							<?php
						}
						?>
					</select>
				</span>

				<span class="vaprecurrenceforselect">
					<select id="vapfornextrecsel">
						<option value="0"><?php echo JText::translate('VAPRECURRENCENONE'); ?></option>
						<?php
						$lookup = array(
							'VAPDAYS',
							'VAPWEEKS',
							'VAPMONTHS',
						);

						$for_text = array();

						for ($i = 0; $i < count($lookup); $i++)
						{
							if ($recurrence['for'][$i] == 1)
							{
								$for_text[] = JHtml::fetch('select.option', $i + 1, JText::translate($lookup[$i]));
							}
						}

						echo JHtml::fetch('select.options', $for_text);
						?>
					</select>
				</span>
			</div>

		</div>
	</div>

<?php } ?>

<div class="vapbookbuttondiv">

	<div class="vapbooksuccessdiv" style="display: none;">
		<i class="fas fa-check-circle"></i>
		<span id="booksuccess-msg"><?php echo JText::translate('VAPCARTITEMADDOK'); ?></span>
	</div>

	<div class="vapbookerrordiv" style="display: none;">
		<i class="fas fa-times-circle"></i>
		<span id="bookerror-msg"><?php echo JText::translate('VAPBOOKNOTIMESELECTED'); ?></span>
	</div>

	<div class="vap-checkout-bar">

		<?php if ($cart_enabled) { ?>

			<div class="vapbookbuttoninnerdiv additem">
				<button type="button" class="vap-btn blue vapadditembutton" id="vapadditembutton" onClick="vapAddItemToCart();">
					<?php echo JText::translate('VAPADDCARTBUTTON'); ?>
				</button>
			</div>

		<?php } ?>

		<div class="vapbookbuttoninnerdiv checkout">
			<button type="button" class="vap-btn green vapcheckoutbutton" onClick="vapBookNow();">
				<?php echo JText::translate('VAPBOOKNOWBUTTON'); ?>
			</button>
		</div>

	</div>

	<?php if ($waiting_list_enabled) { ?>

		<div class="vapbookbuttoninnerdiv waitlist" id="vapwaitlistbox" style="display: none;">
			<?php
			/**
			 * See waiting list layout for further details about 
			 * the vapOpenWaitListOverlay() function.
			 *
			 * @link layouts/blocks/waitlist.php
			 *
			 * See the following views for further details about LAST_TIMESTAMP_USED.
			 *
			 * @link views/servicesearch/tmpl/default.php
			 * @link views/employeesearch/tmpl/default.php
			 */
			?>
			<button type="button" class="vap-btn dark-gray vapwaitlistbutton" onClick="vapOpenWaitListOverlay('vapaddwaitlistoverlay', LAST_TIMESTAMP_USED);">
				<i class="fas fa-calendar-plus"></i>
				<?php echo JText::translate('VAPWAITLISTADDBUTTON'); ?>
			</button>
		</div>

	<?php } ?>

</div>

<?php
JText::script('VAPBOOKNOTIMESELECTED');
JText::script('VAPCARTITEMADDOK');
JText::script('VAPCARTMULTIITEMSADDOK');
?>

<script>

	var isTimeChoosen      = false;
	var vapCheckoutProceed = <?php echo (!$cart_empty ? 1 : 0); ?>;

	jQuery(function($) {
		$('#vaprepeatbyrecsel, #vapfornextrecsel').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 150,
		});

		$('#vapamountrecsel').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 70,
		});
	});

	/**
	 * Used to register the selected options within the DOM
	 * or to return them as array.
	 */
	function vapRegisterOptions(what) {
		try
		{
			var options = vapGetSelectedOptions();
		}
		catch (error)
		{
			if (error == 'MissingRequiredOptionException')
			{
				// do not proceed as the customer forgot to fill
				// one or more required fields
				return false;
			}

			// Proceed because the service doesn't own any option 
			// and the function vapGetSelectedOptions() hasn't been declared.
			// Define an empty options array to avoid breaking the flow.
			var options = [];
		}

		if (what === 'ajax') {
			// return options array
			return options;
		}

		options.forEach((opt) => {
			jQuery('#vapempconfirmapp').append(
				'<input type="hidden" name="opt_id[]" value="' + opt.id + '" />'+
				'<input type="hidden" name="opt_quantity[]" value="' + opt.quantity + '" />'+
				'<input type="hidden" name="opt_var[]" value="' + opt.variation + '" />'
			);
		});

		return true;
	}

	/**
	 * Used to book the selected details by submitting the form.
	 */
	function vapBookNow() {
		if (isTimeChoosen) {
			// register options only in case the time has been selected
			if (vapRegisterOptions('submit') === false) {
				// missing some required options
				return false;
			}

			// register recurrence only in case the time has been selected
			var recurrence = vapGetSelectedRecurrence();
			
			if (recurrence) {
				jQuery('#vapempconfirmapp').append('<input type="hidden" name="recurrence" value="' + recurrence + '" />');
			}
		}

		if (isTimeChoosen || vapCheckoutProceed) {
			document.confirmapp.submit();
		} else {
			vapDisplayWrongMessage(2500, Joomla.JText._('VAPBOOKNOTIMESELECTED'));
		}
	}

	var _items_add_count   = 0;
	var _items_timeout     = null;
	var _items_bad_timeout = null;

	/**
	 * Used to book one or more services via AJAX.
	 */
	function vapAddItemToCart() {
		if (!isTimeChoosen) {
			return false;
		}
			
		var id_ser = jQuery("#vapconfserselected").val();
		var id_emp = jQuery("#vapconfempselected").val();
		var day    = jQuery("#vapconfdayselected").val();
		var hour   = jQuery("#vapconfhourselected").val();
		var min    = jQuery("#vapconfminselected").val();
		var people = jQuery("#vapconfpeopleselected").val();
		
		// get selected options
		var options = vapRegisterOptions('ajax');

		if (options === false) {
			// missing some required options
			return false;
		}

		// It doesn't matter if the checkout select exists
		// as the controller won't use this value (because the
		// checkout selection is disabled for this service).
		var factor = jQuery('#vap-checkout-sel').val();
			
		var recurrence = vapGetSelectedRecurrence();
		
		// use default URL
		var _url = '<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=cart.additem' . ($itemid ? '&Itemid=' . $itemid : '')); ?>';
		
		if (recurrence) {
			// user recurrence URL
			_url = '<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=cart.addrecuritem' . ($itemid ? '&Itemid=' . $itemid : '')); ?>';
		} else {
			// set default "no" recurrence
			recurrence = [-1, -1, -1].join(',');
		}
		
		jQuery('.option-required').removeClass('vapoptred');
		
		UIAjax.do(
			_url,
			{
				id_ser:          id_ser,
				id_emp:          id_emp,
				date:            day,
				hour:            hour,
				min:             min,
				people:          people,
				options:         options,
				recurrence:      recurrence,
				duration_factor: factor,
			},
			(resp) => {
				// check if we have recurring items
				if (typeof resp.items !== 'undefined') {
					// ok count
					if (resp.count > 0) {
						// recurring appointments -> get number of items added
						vapDisplayRightMessage(resp.count);

						// we can proceed with the checkout
						vapCheckoutProceed = 1;
					}
					
					var wrong = [];

					// fetch all error messages
					resp.items.forEach((item) => {
						if (item.status == 0) {
							wrong.push(item.error);
						}
					});
					
					if (wrong.length) {
						// at least an error found, display a wrong message too
						vapDisplayWrongMessage(Math.max(wrong.length * 1500, 2500), wrong.join('<br />'));
					}
				} else {
					// display message for one service only
					vapDisplayRightMessage(1);

					// we can proceed with the checkout
					vapCheckoutProceed = 1;
				}

				// inject received response within the event to dispatch
				var event = jQuery.Event('vikappointments.cart.add');
				event.params = resp;

				// trigger event
				jQuery(window).trigger(event);
			},
			(err) => {
				vapDisplayWrongMessage(0, err.responseText || Joomla.JText._('VAPBOOKNOTIMESELECTED'));
			}
		);
	}

	function vapDisplayRightMessage(count) {
		if (!jQuery('.vapbooksuccessdiv').is(':visible')) {
			_items_add_count = count;
		} else {
			_items_add_count++;
		}

		if (!_items_bad_timeout) {
			// no registered timer for error messages, auto hide them
			jQuery('.vapbookerrordiv').hide();
		}
		
		if (_items_add_count == 1) {
			jQuery('.vapbooksuccessdiv #booksuccess-msg').text(Joomla.JText._('VAPCARTITEMADDOK'));
		} else {
			jQuery('.vapbooksuccessdiv #booksuccess-msg').text(_items_add_count + ' ' + Joomla.JText._('VAPCARTMULTIITEMSADDOK'));
		}
		
		if (_items_timeout != null) {
			clearTimeout(_items_timeout);
		}
		
		jQuery('.vapbooksuccessdiv').stop(true, true).fadeIn();

		_items_timeout = setTimeout(function() {
			jQuery('.vapbooksuccessdiv').fadeOut();
		}, 2500);
	}

	function vapDisplayWrongMessage(ms, html) {
		if (_items_bad_timeout != null) {
			clearTimeout(_items_bad_timeout);
		}

		if (html) {
			jQuery('.vapbookerrordiv #bookerror-msg').html(html);
		}
		
		jQuery('.vapbookerrordiv').stop(true, true).fadeIn();

		if (ms > 0) {
			_items_bad_timeout = setTimeout(function() {
				jQuery('.vapbookerrordiv').fadeOut();
			}, ms);
		}
	}

	function vapRecurrenceSelectChanged() {
		var val = jQuery('#vaprepeatbyrecsel').val();
		
		if (val > 0) { 
			if (jQuery('#vapfornextrecsel option[value="' + val + '"]').length > 0) {
				// update select to have the same interval
				jQuery('#vapfornextrecsel').select2('val', val);

			} else if (jQuery('#vapfornextrecsel').val() == "0") {
				// option not found, select the first index available
				jQuery('#vapfornextrecsel').prop('selectedIndex', 1);
				// update val on select2
				jQuery('#vapfornextrecsel').select2('val', jQuery('#vapfornextrecsel').val());
			}
		} else {
			jQuery('#vaprecokcheck').prop('checked', false);
			jQuery('.vaprecurrencenextdiv').hide();
			jQuery('.vaprecurrenceprediv').fadeIn();
		}
	}

	function vapRecurrenceConfirmValueChanged() {
		// change index
		jQuery('#vaprepeatbyrecsel').prop('selectedIndex', 1);
		// update val on select2
		jQuery('#vaprepeatbyrecsel').select2('val', jQuery('#vaprepeatbyrecsel').val());
		// trigger change to update [fornext] select
		vapRecurrenceSelectChanged();

		jQuery('.vaprecurrenceprediv').hide();
		jQuery('.vaprecurrencenextdiv').fadeIn();
	}

	function vapGetSelectedRecurrence() {
		var enabled = <?php echo (int) $recurrence_enabled; ?>;

		if (!enabled) {
			return false;
		}

		var recurrence = [];

		recurrence.push(parseInt(jQuery('#vaprepeatbyrecsel').val()));
		recurrence.push(parseInt(jQuery('#vapfornextrecsel').val()));
		recurrence.push(parseInt(jQuery('#vapamountrecsel').val()));

		if (!recurrence[0]) {
			return false;
		}

		return recurrence.join(',');
	}

</script>
