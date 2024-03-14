

jQuery(function($) {

	$.ajax({
		method: 'GET',
		url: SME.api.url,
		beforeSend: function ( xhr ) {
						jQuery('.SME_loading').show();

			xhr.setRequestHeader( 'X-WP-Nonce', SME.api.nonce );
		},
		error: function(r) {
					jQuery('.SME_loading').hide();
		}
	}).then( function ( r ) {
		if( r.hasOwnProperty( 'caption' ) ){
			$( '#caption' ).val( r.caption );
		}
		if( r.hasOwnProperty( 'editorimagesize' ) ){
			$( '#editorimagesize' ).val( r.editorimagesize );
		}		

		if( r.hasOwnProperty( 'title' ) ){
			$( '#title' ).val( r.title );
		}
		if( r.hasOwnProperty( 'alttext' ) ){
			$( '#alttext' ).val( r.alttext );
		}		
		if( r.hasOwnProperty( 'defaultwidesize' ) ){
			$( '#defaultwidesize' ).val( r.defaultwidesize );
		}	
		if( r.hasOwnProperty( 'defaultclickresponse' ) ){
			$( '#defaultclickresponse' ).val( r.defaultclickresponse );
		}
		if( r.hasOwnProperty( 'defaultnewwindow' ) ){
			$( '#defaultnewwindow' ).prop("checked", r.defaultnewwindow==='true' );
		}		
		if( r.hasOwnProperty( 'license_email' ) ){
			$( '#license_email' ).val( r.license_email );
		}		
		if( r.hasOwnProperty( 'license_key' ) ){
			$( '#license_key' ).val( r.license_key );
		}		
		jQuery('.SME_loading').hide();

	}
	);		

	
	$( '#SME_Settings-form' ).on( 'submit', function (e) {
		e.preventDefault();
		var data = {
			caption: $( '#caption' ).val(),
			title: $( '#title' ).val(),
			editorimagesize: $( '#editorimagesize' ).val(),
			alttext: $( '#alttext' ).val(),
			defaultwidesize:$( '#defaultwidesize' ).val(),
			defaultnewwindow:$( '#defaultnewwindow' ).prop('checked'),
			defaultclickresponse:$( '#defaultclickresponse' ).val(),
			license_email: $( '#license_email' ).val(),
			license_key: $( '#license_key' ).val(),		
		};
		$.ajax({
			method: 'POST',
			url: SME.api.url,
			beforeSend: function ( xhr ) {
				jQuery('.SME_loading').show();
				xhr.setRequestHeader('X-WP-Nonce', SME.api.nonce);
			},
			dataType:'text',
			data:data,
			error:function (r) {
				var message = SME.strings.error;
				if( r.hasOwnProperty( 'message' ) ){
					message = r.message;
				}
				jQuery('.SME_loading').hide();

				SME_displayFeedback(message,"pink")
			}
		}).then (function (r) {
			jQuery('.SME_loading').hide();
			
			SME_displayFeedback( SME.strings.saved,"lightgreen");
			if(data){ // if true (1)
				console.log("in here");
				setTimeout(function(){// wait for 5 secs(2)
					location.reload(); // then reload the page.(3)
				}, 5000); 
			}
		});
		
	})
});