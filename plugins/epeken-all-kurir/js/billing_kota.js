(function($){
	billing_kota = function(kota_pelanggan){
			 $('#billing_state').on('change',function(){
				 		$('#div_epeken_popup').css('display','block');
                                                var call = $.get(PT_Ajax_Bill_Kota.ajaxurl, 
                                                                {
                                                                        action: 'get_list_kota',
                                                                        nextNonce: PT_Ajax_Bill_Kota.nextNonce,
                                                                        province: this.value        
                                                                },
                                                                function(data,status){
                                                                $('#billing_city').empty();
                                                                        var arr = data.split(';');
                                                                        $.each(arr, function (i,valu) {
                                                                         if (valu != '' && valu != '0') {               
									   var index = '';
									   if(valu != 'Kota')
										index = valu;
                                                                                $('#billing_city').append('<option value="'+index+'">'+valu+'</option>');       
                                                                         }
                                                                        });
								$('#div_epeken_popup').css('display','none');
                                                });
				 		$.when(call).done(function(){$('#billing_city').val(kota_pelanggan).change();});
                                        });
	if($('#billing_country').val() === 'ID') {
	 try{
	   $('#billing_city').select2();
	   $('#billing_address_2').select2();
	  }catch(err){
	    //do nothing
	  }
	}
	}
})(jQuery);
