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

<!-- PARAMETERS -->

<div class="vikpayparamdiv">
	<?php echo $vik->alert(JText::translate('VAPMANAGEPAYMENT9')); ?>
</div>

<!-- CONNECTION ERROR -->

<div id="vikparamerr" style="display: none;">
	<?php echo $vik->alert(JText::translate('VAP_AJAX_GENERIC_ERROR'), 'error'); ?>
</div>	

<script>

	jQuery(function($) {

		$('#vap-driver-sel').on('change', function() {
			// destroy select2 
			$('.vikpayparamdiv select').select2('destroy');
			// unregister form fields
			validator.unregisterFields('.vikpayparamdiv .required');
			
			$('.vikpayparamdiv').html('');
			$('#vikparamerr').hide();

			// fetch driver form
			UIAjax.do(
				'index.php?option=com_vikappointments&task=exportres.getdriverformajax&tmpl=component',
				{
					driver: $(this).val(),
					type:   $('input[name="type"]').val(),
				},
				(resp) => {
					$('.vikpayparamdiv').html(resp);

					// render select
					$('.vikpayparamdiv select').each(function() {
						$(this).select2({
							// disable search for select with 3 or lower options
							minimumResultsForSearch: $(this).find('option').length > 3 ? 0 : -1,
							allowClear: false,
							width: '90%',
						});
					});

					// register form fields for validation
					validator.registerFields('.vikpayparamdiv .required');

					// init helpers
					$('.vikpayparamdiv .vap-quest-popover').popover({sanitize: false, container: 'body', trigger: 'hover', html: true});

					$('.vikpayparamdiv').trigger('payment.load');
				},
				(error) => {
					$('#vikparamerr').show();
				}
			);
		});

	});

</script>
