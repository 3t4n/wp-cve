(function($){
	shipping_kecamatan = function(kecamatan_pelanggan){
			 $('#shipping_city').on('change',function(){
						$('#div_epeken_popup').css('display','block');
                                                var call = $.get(PT_Ajax_Ship_Kec.ajaxurl, 
                                                                {
                                                                        action: 'get_list_kecamatan',
                                                                        nextNonce: PT_Ajax_Ship_Kec.nextNonce,
                                                                        kota: this.value        
                                                                },
                                                                function(data,status){
                                                                $('#shipping_address_2').empty();
                                                                        var arr = data.split(';');
                                                                        $.each(arr, function (i,valu) {
                                                                         if (valu != '' && valu != '0') {               
									   var index = '';
                                                                           if(valu != 'Kecamatan (District)')
                                                                                index = valu;
                                                                           $('#shipping_address_2').append('<option value="'+index+'">'+valu+'</option>');       
                                                                         }
                                                                        });
								$('#div_epeken_popup').css('display','none');
                                                });
				 		$.when(call).done(function(){$('#shipping_address_2').val(kecamatan_pelanggan).change();});
                                        });
					//$('.checkout').on('submit', function(){
                                        //        $('#billing_state').attr('disabled',false);
                                        //        $('#shipping_state').attr('disabled',false);
                                        //});
	 	if($('#insurance_chkbox') != null) {
                $('#insurance_chkbox').on('change', function(){
						$('#insurance_chkbox').on('change',function() {$('#shipping_address_2 option').removeAttr('selected');$('#shipping_address_2').change();$('#shipping_city option').removeAttr('selected');$('#shipping_city').change();alert('Please re-input city and district.');});
                                        });
          	}
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
