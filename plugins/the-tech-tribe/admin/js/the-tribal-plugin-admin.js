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

	function initUpdateTabHashLink(){
		var hash = location.hash.replace(/^#/, '');  // ^ means starting, meaning only match the first hash
		if (hash) {
			$('#tttUserDashboard button[data-bs-target="#'+hash+'"]').tab('show');
		} 
		
		$('#tttUserDashboard button').on('shown.bs.tab', function (event) {
			toggleAlertHTML();

			var getId = $(event.target).data('bs-target');

			if(history.pushState) {
				history.pushState(null, null, getId);
			} else {
				window.location.hash = getId; //Polyfill for old browsers
			}
			
		});
	}

	function ajaxShowAlert(dataArray)
	{
		var retData = dataArray;

		var retCode = retData.data.code;
		
		if(retCode == '' || retCode == 'rest_no_route' || retCode == 'error'){
			retCode = 'danger';
		}
		var retMsgHeader = retData.data.msg_header;

		var retMsg = retData.data.msg;
		if(retMsg == '' || retMsg === null || retMsg === 'undefined'){
			retMsg = 'Something is wrong';
		}

		var retMsgContent = retData.data.msg_content;

		let msgHtml = '<div class="alert alert-'+retCode+' alert-dismissible fade show" role="alert">'
			+ '<h4 class="alert-heading ttt-show-alert-error-code">'+retMsgHeader+'</h4>'
			+ '<div class="msg-content">'+retMsg + retMsgContent+'</div>'
			+ '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'
		+ '</div>';

		return msgHtml;
	}

	function toggleAlertHTML()
	{
		var alertNode = document.querySelector('.alert')
		var alert = bootstrap.Alert.getInstance(alertNode)
		alert.close()
	}

	function toggleAjaxStatusHTML(enable = false, msg = '')
	{
		$(".import-ajax-status").html(msg);

		if(enable)
		{
			$(".import-ajax-status").show();
		} else {
			$(".import-ajax-status").hide();
		}
	}
	
	function toggleAlertHTML(enable = false, msg = '')
	{
		$(".dashboard-alert").html(msg);

		if(enable)
		{
			$(".dashboard-alert").show();
		} else {
			$(".dashboard-alert").hide();
		}
	}

	var ajaxImportPost = function() {
		function ajaxImportWithoutRealTimeProgress()
		{
			$('.dashboard-form-import').on('submit', function(e){
				e.preventDefault();
				
				let msg = '<div class="ajax-loader"><p>Please wait, this might take up to 60 seconds...</p> ';
				msg += '<p><img src="'+ttt_admin_ajax_object.plugin_url+'/assets/images/ajax-loader.gif"></p>';
				msg += '<p></p></div>';

				toggleAjaxStatusHTML(true, msg);
				toggleAlertHTML();

				$(".btn-import").prop('disabled', true);
				
				var data = {
					'action' : 'ttt_import_post'
				};
				var request = $.ajax({
					url: ttt_admin_ajax_object.ajax_url,
					method: "POST",
					data: data,
					dataType: "json"
				});
				
				request.done(function( msg ) {
					console.log(msg);
					toggleAlertHTML(true, ajaxShowAlert(msg));
					toggleAjaxStatusHTML(false);
					
					if(msg.data.last_check !== 'undefined' || msg.data.last_check !== null){
						$('.last-check').text(msg.data.last_check)
					}
					
					if(msg.data.last_successfull_import !== 'undefined' || msg.data.last_successfull_import !== null){
						$('.last-success-import').text(msg.data.last_successfull_import)
					}
					
					$(".btn-import").prop('disabled', false);
				});
				
				request.fail(function( jqXHR, textStatus ) {
					//console.log(textStatus);
					msg = "<h4>Request failed: " + textStatus+"</h4>";
					toggleAjaxStatusHTML(true, msg);
					$(".btn-import").prop('disabled', false);
				});
			});
		}
		return {
			initWithoutRealTimeProgress: function(){
				ajaxImportWithoutRealTimeProgress();
			}//init: function()
		};
	}();

	$(function(){
		initUpdateTabHashLink();
		ajaxImportPost.initWithoutRealTimeProgress();
	});

})( jQuery );
