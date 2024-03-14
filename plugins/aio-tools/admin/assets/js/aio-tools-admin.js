( function( $ ) {
	"use strict";
	//Check SMTP Mailer
	/*$('.w2w-smtp-mailer input').change(function(){
		var e = $(this),
			t = $(".w2w-smtp-host input"),
			s = $('.w2w-smtp-encryption input');
		"outlook" === e.val() 
			? t.val("smtp.office365.com") : t.val("");
	});*/
	//Check SMTP Port
	$('.w2w-smtp-encryption input').change(function(){
		var e = $(this),
			t = $(".w2w-smtp-port input");
		"tls" === e.val()
			? (t.val("587"))
			: ("ssl" === e.val() ? t.val("465") : t.val("25"));
	});
	
	$(".btnSendTestEmail").on('click',function(){
		let your_email = $(".txtYourEmail").val();
		if(ValidateEmailInput(your_email)){
			$.ajax({
				type : 'POST',
				dataType: "html",
				url : w2w_smtp_param.ajaxurl,
				data: {
					action: 'w2wSmtpCheckHandler',
					your_email: your_email
				},
				//context: this,
				beforeSend : function ( xhr ) {
					$('.smtpTestMessage').removeClass('hidden');
					$('.smtpTestMessage .csf-submessage').remove();
					$('.smtpTestMessage').append('<div class="csf-submessage csf-submessage-info"><i class="fas fa-sync fa-spin"></i> ' + w2w_smtp_param.message.sending + '</div>' );
				},
				success : function( response ){
					$('.smtpTestMessage .csf-submessage').remove();
					$('.smtpTestMessage').append( '<div class="csf-submessage csf-submessage-success"><i class="fas fa-check"></i> ' + w2w_smtp_param.message.success + '</div>' );
				},
				error: function (jqXHR, textStatus, errorThrown) {
					//Làm gì đó khi có lỗi xảy ra
					$('.smtpTestMessage .csf-submessage').remove();
					$('.smtpTestMessage').append( '<div class="csf-submessage csf-submessage-warning"><i class="fas fa-exclamation-circle"></i> ' + w2w_smtp_param.message.error + textStatus, errorThrown + '</div>' );
					
				}
			});
		}
	});
	function ValidateEmailInput(mail){
		if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail))
		{
			return (true);
		} else {
			alert(w2w_smtp_param.message.alertdata);
			return (false);
		}
	}
	
} )( jQuery );

