jQuery(document).ready(function(){
	var angularScope = angular.element("body").scope();
	var OxyReloginWindowHeartBeat = function() {
		jQuery.getJSON( OxyReloginWindowBEData.admin_url + '?action=oxy-relogin-window-heartbeat&post_id=' + CtBuilderAjax.postId, function( data ) {
			if( typeof data.session_status !== 'undefined' && data.session_status != 'expired' ) {
				angularScope.iframeScope.ajaxVar.nonce = data.session_status;
				document.getElementById('ct-artificial-viewport').contentWindow.CtBuilderAjax.nonce = data.session_status;
				CtBuilderAjax.nonce = data.session_status;
				if( jQuery('#opp-login').is(":visible") ){
					jQuery('#opp-login').fadeOut(300,function(){ jQuery('#opp-login').remove(); });
					angularScope.iframeScope.showNoticeModal("<div><h3>PHEW!!!</h3><p><strong>OXY Re-Login Window</strong> just saved your life.<br/><br/>If you haven't yet, please consider buying <a href='https://oxypowerpack.com' target='_blank' style='color:white;'>OxyPowerPack</a> for more powerful features.</p></div>", "ct-notice");
				}
				setTimeout( OxyReloginWindowHeartBeat, 6000 );
			} else if(typeof data.session_status !== 'undefined' && data.session_status == 'expired'){
				if( !jQuery('#opp-login').length ){
					var html = jQuery('#opp-login-template').html();
					html = html.replace('IFRAMESRC', OxyReloginWindowBEData.loginIframeSrc);
					jQuery('body').append(html);
				}
				setTimeout( OxyReloginWindowHeartBeat, 6000 );
			} else {
				setTimeout( OxyReloginWindowHeartBeat, 6000 );
			}
		}).fail(function(){
			setTimeout( OxyReloginWindowHeartBeat, 6000 );
		});
	};
	setTimeout( OxyReloginWindowHeartBeat, 6000 );
});
