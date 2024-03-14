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

$payment = $this->payment;

$vik = VAPApplication::getInstance();

if (!$this->isOwner)
{
	// payment method created by an employee, do not allow access
	// to sensitive data and raise an error message
	echo $vik->alert(JText::translate('JERROR_ALERTNOAUTHOR'), 'error');
	return;
}
?>

<!-- PARAMETERS -->

<div class="vikpayparamdiv">
	<?php echo $vik->alert(JText::translate('VAPMANAGEPAYMENT9')); ?>
</div>

<!-- CONNECTION ERROR -->

<div id="vikparamerr" style="display: none;">
	<?php echo $vik->alert(JText::translate('VAP_AJAX_GENERIC_ERROR'), 'error'); ?>
</div>

<?php
JText::script('JGLOBAL_SELECT_AN_OPTION');
?>

<script>

	jQuery(function($) {
		<?php
		if ($payment->file)
		{
			?>
			vapPaymentGatewayChanged();
			<?php
		}
		?>
	});

	function vapPaymentGatewayChanged() {
		var gp = jQuery('#vap-file-sel').val();

		// destroy select2 
		jQuery('.vikpayparamdiv select').select2('destroy');
		// unregister form fields
		validator.unregisterFields('.vikpayparamdiv .required');
		
		jQuery('.vikpayparamdiv').html('');
		jQuery('#vikparamerr').hide();

		UIAjax.do(
			'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=payment.driverfields'); ?>',
			{
				driver: gp,
				id: <?php echo (int) $payment->id; ?>,
			},
			function(html) {
				jQuery('.vikpayparamdiv').html(html);

				// render select
				jQuery('.vikpayparamdiv select').each(function() {
					let option = jQuery(this).find('option').first();

					let data = {
						// disable search for select with 3 or lower options
						minimumResultsForSearch: jQuery(this).find('option').length > 3 ? 0 : -1,
						// allow clear selection in case the value of the first option is empty
						allowClear: option.val() || jQuery(this).hasClass('required') ? false : true,
						// take the whole space
						width: '90%',
					};

					if (!option.val()) {
						// set placeholder by using the option text
						data.placeholder = option.text() || Joomla.JText._('JGLOBAL_SELECT_AN_OPTION');
						// unset the text from the option for a correct rendering
						option.text('');
					}

					jQuery(this).select2(data);
				});

				// register form fields for validation
				validator.registerFields('.vikpayparamdiv .required');

				// init helpers
				jQuery('.vikpayparamdiv .vap-quest-popover').popover({sanitize: false, container: 'body', trigger: 'hover focus', html: true});

				jQuery('.vikpayparamdiv').trigger('payment.load');
			},
			function(error) {
				jQuery('#vikparamerr').show();
			}
		);
	}

</script>
