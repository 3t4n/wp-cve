(function($){
	var bufferCities  = "<p class=\"form-row form-row-first col-sm-6\" id=\"billing_city_field\" data-priority=\"\" data-o_class=\"form-row \">" + $("#billing_city_field").html() + "</p>";
	var bufferKecamatan = "<p class=\"form-row form-row-last col-sm-6 address-field validate-required update_totals_on_change\" id=\"billing_address_2_field\" data-priority=\"\" data-o_class=\"form-row \">" + $("#billing_address_2_field").html() + "</>";
	var bufferCities_shipping = "<p class=\"form-row form-row-first col-sm-6\" id=\"shipping_city_field\" data-priority=\"\" data-o_class=\"form-row \">" + $("#shipping_city_field").html() + "</p>"; 
	var bufferKecamatan_shipping = "<p class=\"form-row form-row-last col-sm-6 address-field validate-required update_totals_on_change\" id=\"shipping_address_2_field\" data-priority=\"\" data-o_class=\"form-row \">" + $("#shipping_address_2_field").html() + "</>";
	init_control = function(){
 	     $('#shipping_city').attr('class','state_select'); 
             $('#shipping_address_2').attr('class','state_select');
             $('#billing_city').attr('class','state_select');
             $('#billing_address_2').attr('class','state_select');
	}
	$("#billing_country").on("change", function(){
		 if($("#billing_country").val() !== "ID") {
		 /* international shipping */
			$("#billing_city_field").replaceWith("<p class=\"form-row address-field validate-required woocommerce-validated update_totals_on_change\" id=\"billing_city_field\" data-priority=\"\" data-o_class=\"form-row \"><label for=\"billing_city\" class=\"\">City</label><input type=\"text\" name=\"billing_city\" id=\"billing_city\"></p>");
			$("#billing_address_3_field").hide(); 
			$("#billing_address_2_field").replaceWith("<p class=\"form-row\" id=\"billing_address_2_field\" data-priority=\"\" data-o_class=\"form-row \"><input type=\"hidden\" name=\"billing_address_2\" value=\"N/A\" /></p>");
			$("#billing_state").prop("disabled", false);
			$("#billing_city").on('change', function() {
				$(document.body).trigger('update_checkout');
			});
		 }else{
		 /* local shipping */
			$("#billing_city_field").replaceWith(bufferCities);
			$("#billing_city").select2();
			$("#billing_address_3_field").show();
			$("#billing_address_2_field").replaceWith(bufferKecamatan);
			$("#billing_address_2").select2();
			billing_kota();
			billing_kecamatan();
			$('#billing_state').val('JK');
			$('#billing_state').trigger('change');
			$('#billing_city').trigger('change');
		 } 
	});
	$("#shipping_country").on("change", function(){
                 if($("#shipping_country").val() !== "ID") {
                 /* international shipping */
                        $("#shipping_city_field").replaceWith("<p class=\"form-row update_totals_on_change\" id=\"shipping_city_field\" data-priority=\"\" data-o_class=\"form-row \"><label for=\"shipping_city\" class=\"\">City</label><input type=\"text\" name=\"shipping_city\" id=\"shipping_city\"></p>");
                        $("#shipping_address_3_field").hide(); 
                        $("#shipping_address_2_field").replaceWith("<p class=\"form-row\" id=\"shipping_address_2_field\" data-priority=\"\" data-o_class=\"form-row \"><input type=\"hidden\" name=\"shipping_address_2\" value=\"N/A\" /></p>");
			$("#shipping_state").prop("disabled", false);
			$("#shipping_city").on('change', function() {
				$(document.body).trigger('update_checkout');
			});

                 }else{
                 /* local shipping */
                        $("#shipping_city_field").replaceWith(bufferCities_shipping);
                        $("#shipping_city").select2();
                        $("#shipping_address_3_field").show();
                        $("#shipping_address_2_field").replaceWith(bufferKecamatan_shipping);
                        $("#shipping_address_2").select2();
			shipping_kota();
                        shipping_kecamatan();
			$('#shipping_state').val('JK');
			$('#shipping_state').trigger('change');
                        $('#shipping_city').trigger('change');
                 }   
        }); 
	//Commented because it created bug on performance issue //$("#billing_country").trigger('change');
	$("#billing_country").val("ID");
	$("#billing_country").select2();
	//Commented because it created bug on performance issue //$("#shipping_country").trigger('change');
	$("#shipping_country").val("ID");
	$("#shipping_country").select2();
	if($("#billing_country").val() === undefined) {
		$('#billing_state').val('').change();
	}
	if($("#shipping_country").val() === undefined) {
		$('#shipping_state').val('').change();
 	}
})(jQuery);
