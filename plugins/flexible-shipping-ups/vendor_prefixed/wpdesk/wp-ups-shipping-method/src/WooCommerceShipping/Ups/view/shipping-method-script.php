<script type="text/javascript">
    jQuery(document).ready(function(){
        let $custom_origin = jQuery('#woocommerce_flexible_shipping_ups_custom_origin');

        $custom_origin.change(function(){
            let $origin_address = jQuery('#woocommerce_flexible_shipping_ups_origin_address');
            let $origin_city = jQuery('#woocommerce_flexible_shipping_ups_origin_city');
            let $origin_postcode = jQuery('#woocommerce_flexible_shipping_ups_origin_postcode');
            let $origin_country = jQuery('#woocommerce_flexible_shipping_ups_origin_country');
            if ( jQuery(this).is(':checked') ) {
                $origin_address.closest('tr').show();
                $origin_address.attr('required',true);
                $origin_city.closest('tr').show();
                $origin_city.attr('required',true);
                $origin_postcode.closest('tr').show();
                $origin_postcode.attr('required',true);
                $origin_country.closest('tr').show();
                $origin_country.attr('required',true);
            }
            else {
                $origin_address.closest('tr').hide();
                $origin_address.attr('required',false);
                $origin_city.closest('tr').hide();
                $origin_city.attr('required',false);
                $origin_postcode.closest('tr').hide();
                $origin_postcode.attr('required',false);
                $origin_country.closest('tr').hide();
                $origin_country.attr('required',false);
            }
        });

        if ( $custom_origin.length ) {
            $custom_origin.change();
        }

        jQuery('#woocommerce_flexible_shipping_ups_units').select2( {
            minimumResultsForSearch: -1
        } );

        jQuery('#woocommerce_flexible_shipping_ups_origin_country').select2();

		const $api_type = jQuery('#woocommerce_flexible_shipping_ups_api_type');

		function change_api_type() {
			const val = $api_type.val();
			const $xml_api_fields = jQuery('.xml-api').closest('tr');
			const $rest_api_fields = jQuery('.rest-api').closest('tr');
			const $xml_api_inputs = $xml_api_fields.find('input:not([type="checkbox"]),select');
			if ( val === 'xml' ) {
				$xml_api_fields.show();
				$rest_api_fields.hide();
				$xml_api_inputs.prop('required', true);
			} else {
				$xml_api_fields.hide();
				$rest_api_fields.show();
				$xml_api_inputs.prop('required', false);
			}
		}

		$api_type.change(function(){
			change_api_type();
		})

		change_api_type();

		function removeParameterFromUrl() {
			const url = new URL(location);
			url.searchParams.delete('ups-oauth-status');
			url.searchParams.delete('security');
			url.searchParams.delete('ups-oauth-code');
			history.replaceState(null, null, url)
		}

		removeParameterFromUrl();

    });

</script>

