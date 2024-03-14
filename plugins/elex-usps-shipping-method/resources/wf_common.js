

jQuery(document).ready(function(){


	jQuery('#woocommerce_wf_shipping_usps_availability').change(function()
	{
		var value =document.getElementById("woocommerce_wf_shipping_usps_availability").value;
		if (value === "specific") {
			jQuery('#woocommerce_wf_shipping_usps_countries').closest('tr').show();
		} else {
			jQuery('#woocommerce_wf_shipping_usps_countries').closest('tr').hide();
		}
	});
});

