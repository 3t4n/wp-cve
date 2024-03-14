jQuery(document).ready(function($) {
	$(".quantity input.qty").change(function(){
		var qty = $(this).val();
		if($(".variations select option:checked").val()){
			var variation = $(".variations select option:checked").val();
		}else{
			var variation = $(".variations select option:nth-child(2)").val();
		}
		var product_id = $("input.cwmp_product_id").val();
		console.log(product_id);
        $.ajax({
            type: "POST",
            url: pmwp_ajax.ajax_url,
            data: {
                action: "pmwp_price_ajax",
				qty: qty,
				variacao: variation,
				product_id: product_id
            },
            success: function(data) {
				$(".elementor-widget-container .pmwp_price").html("");
				$(".elementor-widget-container .pmwp_price").html(data);
            }
        });
	});
	$(".minus, .plus").click(function(){
		var qty = $(".quantity input.qty").val();
		if($(".variations select option:checked").val()){
			var variation = $(".variations select option:checked").val();
		}else{
			var variation = $(".variations select option:nth-child(2)").val();
		}
		var product_id = $("input.cwmp_product_id").val();
        $.ajax({
            type: "POST",
            url: pmwp_ajax.ajax_url,
            data: {
                action: "pmwp_price_ajax",
				qty: qty,
				variacao: variation,
				product_id: product_id
            },
            success: function(data) {
				$(".elementor-widget-container .pmwp_price").html(data);
            }
        });
	});
	
});