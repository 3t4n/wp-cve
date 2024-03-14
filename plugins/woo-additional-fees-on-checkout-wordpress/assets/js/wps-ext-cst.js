var $ =jQuery.noConflict();
$(document).ready(function() {
    show_hide_cndtn()
});

function show_hide_cndtn(){
	$(".cndtn_mode").hide();
    var id = $('#ext_cst_apply_cndtn').val();
    $("#"+id).show();
}
function show_hide_cndtn_extra(s_id){
	$(".cndtn_mode_extra"+s_id).hide();
    var id = $('#ext_cst_apply_cndtn_extra'+s_id).val();
    $("#"+id+s_id).show();
}
function remove_fees( id ){
	var numberCount =  $('#current_number_fees').val();
	var r = confirm("Are you want to delete?");
	if (r == true) {
	    $.when($("#fees"+id).fadeOut('slow') ).done(function() {
	       	$("#fees"+id).remove();
	       	numberCount = parseInt(numberCount)-1;
	       	$('#current_number_fees').val(numberCount)
	       	var flagCount = 2;
	       	$('#current_number_fees').val(numberCount);
			alert("Successfully deleted.")
		});
	}

}

$(".wafoc-bottom-line-add-new a").click(function(){
	var numberCount =  $('#current_number_fees').val();
	if(!numberCount){
		numberCount = 2;
	}else{
		numberCount = parseInt(numberCount)+1;
	}
	

	
	

	$(".wafoc-bottom-line-add-new a").html('Generating...');
	var data = {
		'action': 'wps_generate_new_fees',
		'dataType': "html",
		'number': numberCount
	};
  	jQuery.post(ajaxurl, data, function(response) {
      	$("#wps_custom_fees_add_more").append(response);
      	$(".wafoc-bottom-line-add-new a").html('Add More New Fees');
      	$('#current_number_fees').val(numberCount);
      	show_hide_cndtn_extra(numberCount);
      	//remove_fees();
      	
  	});

});

$(".woocommerce_page_wps-ext-cst-option").find("#submit").on('click',function(){
	//console.log("Validation");
	var error = false;
	$(".ext_cst_cndtn_dropdown").each(function(){
		
		var data_id = $(this).attr("data-id");
		$("input[type='number']").css('border','1px solid #DDD');

		//console.log(data_id);
		if( data_id == 1 ){
			if( this.value == 'cart_total_amount'){
				var input_min = $("input[name='cart_total_amount_min']").val();
				var input_max = $("input[name='cart_total_amount_max']").val();

				

				if(!isNormalInteger(input_min)){
					$("input[name='cart_total_amount_min']").css('border','1px solid red');
					$("input[name='cart_total_amount_min']").focus();
					error = true;
					return false;
				}

				if(!isNormalInteger(input_max)){

					$("input[name='cart_total_amount_max']").css('border','1px solid red');
					$("input[name='cart_total_amount_max']").focus();
					error = true;
					return false;
				}

				
			}
			if( this.value == 'cart_no_product'){
				var input_min = $("input[name='cart_no_product_min']").val();
				var input_max = $("input[name='cart_no_product_max']").val();
				


				if(!isNormalInteger(input_min)){
					$("input[name='cart_no_product_min']").css('border','1px solid red');
					$("input[name='cart_no_product_min']").focus();
					error = true;
					return false;
				}
				
				if(!isNormalInteger(input_max)){
					$("input[name='cart_no_product_max']").css('border','1px solid red');
					$("input[name='cart_no_product_max']").focus();
					error = true;
					return false;
				}
				
				
			}
		}
		else{
			if( this.value == 'cart_total_amount'){
				var input_min = $("input[name='ext_cst_extra["+data_id+"][cart_total_amount_min_extra]']").val();
				var input_max = $("input[name='ext_cst_extra["+data_id+"][cart_total_amount_max_extra]']").val();

				

				if(!isNormalInteger(input_max)){
					$("input[name='ext_cst_extra["+data_id+"][cart_total_amount_max_extra]']").css('border','1px solid red');
					$("input[name='ext_cst_extra["+data_id+"][cart_total_amount_max_extra]']").focus();
					error = true;
					return false;
				}

				if(!isNormalInteger(input_min)){
					$("input[name='ext_cst_extra["+data_id+"][cart_total_amount_min_extra]']").css('border','1px solid red');
					$("input[name='ext_cst_extra["+data_id+"][cart_total_amount_min_extra]']").focus();
					error = true;
					return false;
				}
			}
			if( this.value == 'cart_no_product'){
				var input_min = $("input[name='ext_cst_extra["+data_id+"][cart_no_product_min_extra]']").val();
				var input_max = $("input[name='ext_cst_extra["+data_id+"][cart_no_product_max_extra]']").val();


				if(!isNormalInteger(input_min)){
					$("input[name='ext_cst_extra["+data_id+"][cart_no_product_min_extra]']").css('border','1px solid red');
					$("input[name='ext_cst_extra["+data_id+"][cart_no_product_min_extra]']").focus();
					error = true;
					return false;
				}

				if(!isNormalInteger(input_max)){
					$("input[name='ext_cst_extra["+data_id+"][cart_no_product_max_extra]']").css('border','1px solid red');
					$("input[name='ext_cst_extra["+data_id+"][cart_no_product_max_extra]']").focus();
					error = true;
					return false;
				}
				

			}
		}
	});
	

	
	if(error){
		return false;
	}else{
		return true;
	}
});

function isNormalInteger(str) {
    var n = Math.floor(Number(str));
    return String(n) === str && n > 0;
}

$(document).ready(function() {
    $('.wps_wafc_multiselect').select2({
    	placeholder: "Select your choices",
    });
});