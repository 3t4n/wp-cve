window.addEventListener('DOMContentLoaded', function() {
	(function($){
		// auto submit
		$('.adminz_form_filter_taxonomy .change_to_redirect').on("change",function(){
	 		this.form.submit();
	 	});
		// select2
	 	if(adminz_woo_form_data.is_select2){
			// wiget
			$(".change_to_redirect").each(function(){
	            var args = {width: '100%'};
	            if($(this).attr('data-placeholder')){
	                args.placehoder = $(this).attr('data-placeholder');
	            }

	            $(this).select2(args).on("select2:open", function (e) {     
	                $("body>.select2-container .select2-search__field").attr("placeholder",adminz_woo_form_data.text_search);
	                var originalOptions = $(this).find('option');
			        var selectWooDropdown = $('.select2-container--open .select2-results__options');
			        var is_multiple = $(this).prop('multiple'); 
			        
			        setTimeout(function() {
			          	originalOptions.each(function(index) {
			          		zindex = index;
			          		if(is_multiple){
			          			zindex = index -1; // Bổ sung thêm 1 vì đã tạo 1 option trống đầu tiên	
			          		}

			            	var originalClass = $(this).attr('class');
			            	selectWooDropdown.find('.select2-results__option').eq(zindex).addClass(originalClass);

			            	var originalParent = $(this).attr("data-parent");
			            	selectWooDropdown.find('.select2-results__option').eq(zindex).attr("data-parent",originalParent);

			            	var originalTax = $(this).attr("data-taxonomy");
			            	selectWooDropdown.find('.select2-results__option').eq(zindex).attr("data-taxonomy",originalTax);

			            	var originalValue = $(this).attr("data-value");
			            	selectWooDropdown.find('.select2-results__option').eq(zindex).attr("data-value",originalValue);	
			          	});
			        }, 100);
	            }).on("select2:close", function (e) {     
	                $("body>.select2-container .select2-search__field").attr("placeholder","");
	            });
	        });
	        //form shortcode
	        $(".adminz_woo_form select").each(function(){
	            var args = {width: '100%'};
	            if($(this).attr('data-placeholder')){
	                args.placehoder = $(this).attr('data-placeholder');
	            }

	            $(this).select2(args).on("select2:open", function (e) {     
	                $("body>.select2-container .select2-search__field").attr("placeholder",adminz_woo_form_data.text_search);
	                var originalOptions = $(this).find('option');
			        var selectWooDropdown = $('.select2-container--open .select2-results__options');
			        var is_multiple = $(this).prop('multiple'); 

			        setTimeout(function() {
			          	originalOptions.each(function(index) {
			          		zindex = index;
			          		if(is_multiple){
			          			zindex = index -1; // Bổ sung thêm 1 vì đã tạo 1 option trống đầu tiên	
			          		}

			            	var originalClass = $(this).attr('class');
			            	selectWooDropdown.find('.select2-results__option').eq(zindex).addClass(originalClass);

			            	var originalParent = $(this).attr("data-parent");
			            	selectWooDropdown.find('.select2-results__option').eq(zindex).attr("data-parent",originalParent);	

			            	var originalTax = $(this).attr("data-taxonomy");
			            	selectWooDropdown.find('.select2-results__option').eq(zindex).attr("data-taxonomy",originalTax);

			            	var originalValue = $(this).attr("data-value");
			            	selectWooDropdown.find('.select2-results__option').eq(zindex).attr("data-value",originalValue);	
			          	});
			        }, 100);
	            }).on("select2:close", function (e) {     
	                $("body>.select2-container .select2-search__field").attr("placeholder","");
	            });
	        });

	        // Ẩn children, mở khi click vào parent
	        if(adminz_woo_form_data.enable_select2_multiple_hide_child){
		        $('.adminz_woo_form_get_field_taxonomy').on('select2:selecting', function (e) {
					var selectedOption = e.params.args.data;		
					var selectedValue = selectedOption.id;
					var selectedLi = $(".select2-results__option[data-value="+selectedValue+"]");					
					// kiểm tra xem có children hay không. nếu có thì mở ra children
					// nếu không thì kiểm tra có parent không
					if($('[data-parent="'+selectedValue+'"').length){			
						$(".select2-results__option").addClass("hidden");
						// console.log($(".select2-results__option[data-parent="+selectedValue+"]"));
						$(".select2-results__option[data-parent="+selectedValue+"]").removeClass("hidden");
						e.preventDefault();
					}else{

					}
				});
	        }
        }        
	})(jQuery);
});