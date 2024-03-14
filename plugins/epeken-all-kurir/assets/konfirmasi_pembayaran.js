(function($){
			konfirmasi_pembayaran = function(){
			 $('#submit_konfirmasi').on('click',function(e){
			 e.preventDefault();
			 if(confirm("Do you want to submit this form ?") == true){
			 var file_data = $('#imgbuktitransfer').prop('files')[0];
			 var form_data = new FormData();
			 form_data.append('file', file_data);
			 form_data.append('action','submit_konfirmasi_pembayaran');
			 form_data.append('nextNonce', PT_Ajax_Konfirmasi_Pembayaran.nextNonce);
			 form_data.append('orderid',$("#orderid_pembayaran").val());
			 form_data.append('tglpembayaran', $("#tgl_pembayaran").val());
			 form_data.append('namapembayar', $("#nama_pembayar").val());
			 form_data.append('rekeningpembayar', $("#rekening_pembayar").val());
			 form_data.append('namabank', $("#nama_bank").val());
			 form_data.append('transferamount', $("#transfer_amount").val());
			 form_data.append('notespembayaran', $("#notes_pembayaran").val());
			 $("#submit_konfirmasi").html("Mengirim Konfirmasi..");
			 $('#div_epeken_popup').css('display','block');
			 $("#submit_konfirmasi").prop('disabled', true);
			 $.ajax( 
				{   
				url: PT_Ajax_Konfirmasi_Pembayaran.ajaxurl,
				type: 'post', 
				contentType: false,
				processData: false,
				data: form_data,    
				async: true,
				success: function (data) {
					$('#div_epeken_popup').css('display','none');
					alert('Done.');
					location.reload();
				},
				error: function (xhr, ajaxOptions, thrownError) {
					$('#div_epeken_popup').css('display','none');
					location.reload();
				}
                            }
			);
		 }});
		}
	}
)(jQuery);
