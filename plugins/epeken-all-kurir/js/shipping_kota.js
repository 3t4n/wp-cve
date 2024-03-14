(function($){
	shipping_kota = function(kota_pelanggan){
			 $('#shipping_state').on('change',function(){
				 		$('#div_epeken_popup').css('display','block');
                                                var call = $.get(PT_Ajax_Ship_Kota.ajaxurl, 
                                                                {
                                                                        action: 'get_list_kota',
                                                                        nextNonce: PT_Ajax_Ship_Kota.nextNonce,
                                                                        province: this.value        
                                                                },
                                                                function(data,status){
                                                                $('#shipping_city').empty();
                                                                        var arr = data.split(';');
                                                                        $.each(arr, function (i,valu) {
                                                                         if (valu != '' && valu != '0') {               
									   var index = '';
									   if(valu != 'Kota')
										index = valu;
                                                                                $('#shipping_city').append('<option value="'+index+'">'+valu+'</option>');       
                                                                         }
                                                                        });
								$('#div_epeken_popup').css('display','none');
                                                });
				 		$.when(call).done(function(){$('#shipping_city').val(kota_pelanggan).change();});
                                        });
	if($('#shipping_country').val() === 'ID') {
	try{
	 $('#shipping_city').select2();
	 $('#shipping_address_2').select2();
	 }catch(err) {
	   //do nothing.
	 }
	}
	}
})(jQuery);
