(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$(function(){
		$('.st-credentials__signin').on('click', function(){
			var username = $('.st-credentials__email').val(),
				password = $('.st-credentials__password').val();
			jQuery.ajax({
				url: st.apilink+'users/me/',
				method: 'GET',
				crossDomain: true,
				beforeSend: function ( xhr ) {
					xhr.setRequestHeader( 'Authorization', 'Basic ' + btoa( username+':'+password ) );
				},
				success: function( data ) {
					data.action = 'login_post';
					data.nonce = st.login_nonce;
					jQuery.ajax({
						type: "post",
						dataType: "json",
						url: st.ajax_url,
						data: data,
						success: function(msg,status){
							if( status == 'success' ){
								setCookie('st-logged-in', btoa( username+':'+password ), 365);
								window.location.href = st.pluginurl;
							}
								
						}
					});
				}
			});
		})

		$('.st-install-template').on('click', function(e){
			e.preventDefault();
			var download_id = $(this).data('download');
			var purchase_code = $(this).data('purchaseCode');
			jQuery.ajax({
				url: st.customapilink+'check-download/'+ download_id + '?theme-slug='+st.themeslug + '&purchase_code='+purchase_code,
				method: 'GET',
				crossDomain: true,
				beforeSend: function ( xhr ) {
					xhr.setRequestHeader( 'Authorization', 'Basic ' + getCookie('st-logged-in') );
				},
				success: function( data ) {
					if( data.free_from_theme ){
						installTemplate(download_id);
						return true;
					}
					if( data.free_logged_in ){
						if( st.logged && getCookie('st-logged-in') !== false )
							installTemplate(download_id);
						else	
							window.location.href = st.pluginurl + '&message=no-login';

						return true;
					}

					if( ! st.logged ){
						window.location.href = st.pluginurl + '&message=no-login';
						return true;
					}
					
					if( parseInt(st.is_premium) ){			
						installTemplate( download_id );
						return true;
					}

					window.location.href = st.pluginurl + '&message=premium-needed';
					return true;
				}
			});
		});


		function installTemplate(id){
			$('.st-template[data-id="'+id+'"]').addClass('start-install');

			jQuery.ajax({
				url: st.customapilink+'get-link/'+ id + '?uid='+st.logged,
				method: 'GET',
				crossDomain: true,
				beforeSend: function ( xhr ) {
					xhr.setRequestHeader( 'Authorization', 'Basic ' + getCookie('st-logged-in') );
				},
				success: function( data ){
					if( data.length == 0 || !data )
						return false;
					jQuery.ajax({
						url: st.apilink+'dlm_download_version/'+data['ID'],
						method: 'GET',
						crossDomain: true,
						beforeSend: function ( xhr ) {
							xhr.setRequestHeader( 'Authorization', 'Basic ' + getCookie('st-logged-in') );
						},
						success: function( data ){
							var file = JSON.parse( data['_files'] );
							var url = file[0];
							var data_ = {};

							data_.action = 'install_template';
							data_.nonce = st.install_nonce;
							data_.file = encodeURI(url);
							data_.download_id = id;

							jQuery.ajax({
								type: "post",
								dataType: "json",
								url: st.ajax_url,
								data: data_,
								success: function(msg,status){
									if( msg.data == 'Limit-END' )
										window.location.href = st.pluginurl + '&message=limit-end';

									if( msg.success ){
										$('.st-template[data-id="'+id+'"]').removeClass('start-install');
										$('.st-template[data-id="'+id+'"]').addClass('success-install');
									}


								}
							});
						}
					});

					
					
				}
			});
		}
		
	});


	function setCookie(name,value,days) {
		var expires = "";
		if (days) {
			var date = new Date();
			date.setTime(date.getTime() + (days*24*60*60*1000));
			expires = "; expires=" + date.toUTCString();
		}
		document.cookie = name + "=" + (value || "")  + expires + "; path=/";
	}
	function getCookie(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		}
		return false;
	}
	function eraseCookie(name) {   
		document.cookie = name+'=; Max-Age=-99999999;';  
	}
})( jQuery );
