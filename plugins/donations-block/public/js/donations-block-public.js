jQuery(document).ready(function () {
	// Donation suggestion amount select
	if (jQuery('.pdb-sa').length) {
		document.forms.wp_paypal_donation_form.donation_amount.forEach(radio => {
			radio.addEventListener('change', () => {
				if (document.forms.wp_paypal_donation_form.donation_amount.value == 'custome_amount') {
					jQuery('input[name="custom_donation_amount"]').attr('disabled', false);
				} else {
					jQuery('form[name="wp_paypal_donation_form"] input[name="amount"]').val(document.forms.wp_paypal_donation_form.donation_amount.value)
					jQuery('input[name="custom_donation_amount"]').val('');
					jQuery('input[name="custom_donation_amount"]').attr('disabled', true);
				}
			})
		});
	}

	// Enter custom amount
	jQuery('input[name="custom_donation_amount"]').on('keyup', function (){
		if (jQuery('input[name="donation_amount"]:checked').val() == 'custome_amount') {
			jQuery('form[name="wp_paypal_donation_form"] input[name="amount"]').val(jQuery(this).val())
		} else {
			jQuery('input[name="custom_donation_amount"]').val('');
			jQuery('input[name="custom_donation_amount"]').attr('disabled', true);
		}
	})

	// Store donner urse data by AJAX
	jQuery('.donation_data_submit').on('click', function () {
		let donner_name = jQuery('#donner_name').val();
		let donner_email = jQuery('#donner_email').val();
		let donner_phone = jQuery('#donner_phone').val();
		jQuery.ajax({
			type:'post',
			data:{'donner_name':donner_name, 'donner_email':donner_email, 'donner_phone':donner_phone},
			url:'/wp-content/plugins/donations-block/public/ajax-submit.php',
			success:function (result) {
				jQuery('#pdb_item_number').val(result);
				jQuery('#pdb_paypal_donation_form').submit();
			}
		})
	})

	// Suggestion amount change event
	jQuery('.suggested-donation-amount').on('click', function(){
		jQuery('.suggested-donation-amount.amount-checked').removeClass('amount-checked');
		jQuery(this).addClass('amount-checked');
		jQuery('.suggested-donation-amount.amount-checked input[type="radio"]').prop("checked", true);

		if (document.forms.wp_paypal_donation_form.donation_amount.value == 'custome_amount') {
			jQuery('input[name="custom_donation_amount"]').attr('disabled', false);
		} else {
			jQuery('form[name="wp_paypal_donation_form"] input[name="amount"]').val(document.forms.wp_paypal_donation_form.donation_amount.value)
			jQuery('.selected_amount').html(document.forms.wp_paypal_donation_form.donation_amount.value)
			jQuery('input[name="custom_donation_amount"]').val('');
			jQuery('input[name="custom_donation_amount"]').attr('disabled', true);
		}
	})

})

// Donation popup
function togglePopup() {
	jQuery(".content-modal").toggle();
	jQuery('#overlay').toggleClass('model-open');
}

