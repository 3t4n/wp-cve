// JavaScript Document
var product_id = 0;
var product_cost = 0;
var _this ;
var admin_notice_number = 1;
var admin_notice = "";
var notice_class = " notice-success";
		
jQuery(function($){
	jQuery( document ).on( 'click', '._add_product_cost', function(event ) {
		 event.preventDefault();
		 
		 product_id = $(this).attr("product-id");
		 product_cost = $(this).parent().parent().find("._product_cost").val();
		 //niwoocog_error
		 if (!$.isNumeric(product_cost)){
		 	 $(this).parent().parent().find("._product_cost").addClass('niwoocog_error');
			return false;
		 }
		 
		_this = this;
		var JData = {
			'action': 'ni_cog_action',
			'sub_action': 'add_product_cost',
			'call': 'add_cost',
			'product_id': product_id,
			'product_cost': product_cost,
			
		};
		
		  jQuery(this).html(
									'<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
			  );
		admin_notice = "";
		admin_notice_number = admin_notice_number + 1;
		
		$.ajax({
			
			url:ni_cog_ajax_object.ni_cog_ajax_object_ajaxurl,
			data: JData,
			success:function(response) {
				
				var return_data = JSON.parse(response);
				if ($(_this).parent().parent().find("._product_cost").hasClass('niwoocog_error')){
					 $(_this).parent().parent().find("._product_cost").removeClass('niwoocog_error');	
				}
				
				$(_this).parent().parent().find("._product_cost").addClass('niwoocog_success');
				
				
				if(return_data.status == 1){
					notice_class = " notice-success";				
				}else{
					notice_class = " error";
				}
				
				$(_this).html('Add');
				
				/*Admin notice*/
				admin_notice += '<div id="admin_notice_number_'+admin_notice_number+'" class="notice '+notice_class+'" style="display:none">';
				admin_notice += '	<p>'+ return_data.message +'</p>';
				admin_notice += '</div>';
				jQuery(".wrap").prepend(admin_notice);
					jQuery("#admin_notice_number_"+admin_notice_number).fadeIn("slow").delay(3000).fadeOut("slow",function(){
					jQuery("#admin_notice_number_"+admin_notice_number).remove();
				});
				/*End Admin notice*/		
				
				
			},
			error: function(response){
				console.log(response);
				//alert("e");
			}
		}); 
		
		
	});
});