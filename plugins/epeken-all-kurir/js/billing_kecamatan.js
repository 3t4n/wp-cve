(function($){
	billing_kecamatan = function(kecamatan_pelanggan){
			 $('#billing_city').on('change',function(){
				 		$('#div_epeken_popup').css('display','block');
                                                var call = $.get(PT_Ajax_Bill_Kec.ajaxurl, 
                                                                {
                                                                        action: 'get_list_kecamatan',
                                                                        nextNonce: PT_Ajax_Bill_Kec.nextNonce,
                                                                        kota: this.value        
                                                                },
                                                                function(data,status){
                                                                $('#billing_address_2').empty();
                                                                        var arr = data.split(';');
                                                                        $.each(arr, function (i,valu) {
                                                                         if (valu != '' && valu != '0') {               
									   var index = '';
									   if(valu != 'Kecamatan (District)')
										index = valu;
                                                                           $('#billing_address_2').append('<option value="'+index+'">'+valu+'</option>');       
                                                                         }
                                                                        });
								$('#div_epeken_popup').css('display','none');
                                                });
				 		$.when(call).done(function(){$('#billing_address_2').val(kecamatan_pelanggan).change();});
                                        });
	  if($('#insurance_chkbox') != null) {
			$('#insurance_chkbox').on('change',function() {$('#billing_address_2 option').removeAttr('selected');$('#billing_address_2').change();$('#billing_city option').removeAttr('selected');$('#billing_city').change();alert('Please re-input city and district.');});
	  }
	if($('#billing_country').val() === 'ID') {
	 try{
	  $('#billing_city').select2();
	  $('#billing_address_2').select2();
	 } catch(err) {
	  //do nothing.
	 }
	}
	}
})(jQuery);
