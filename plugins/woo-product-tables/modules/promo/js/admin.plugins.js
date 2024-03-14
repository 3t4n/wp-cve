"use strict";
jQuery(document).ready(function(){
	var $deactivateLnk = jQuery('#the-list tr[data-plugin="'+ wtbpPluginsData.plugName+ '"] .row-actions .deactivate a');
	if($deactivateLnk && $deactivateLnk.length > 0) {
		var $deactivateForm = jQuery('#wtbpDeactivateForm');
		var $deactivateWnd = jQuery('#wtbpDeactivateWnd').dialog({
			modal:    true
		,	autoOpen: false
		,	width: 500
		,	height: 390
		,	buttons:  {
				'Submit & Deactivate': function() {
					$deactivateForm.submit();
				}
			}
		});
		var $wndButtonset = $deactivateWnd.parents('.ui-dialog:first')
			.find('.ui-dialog-buttonpane .ui-dialog-buttonset')
		,	$deactivateDlgBtn = $deactivateWnd.find('.wtbpDeactivateSkipDataBtn')
		,	deactivateUrl = $deactivateLnk.attr('href');
		$deactivateDlgBtn.attr('href', deactivateUrl);
		$wndButtonset.append( $deactivateDlgBtn );
		$deactivateLnk.click(function(){
			$deactivateWnd.dialog('open');
			return false;
		});
		
		$deactivateForm.submit(function(){
			var $btn = $wndButtonset.find('button:first');
			$btn.width( $btn.width() );	// Ha:)
			$btn.showLoaderWtbp();
			jQuery(this).sendFormWtbp({
				btn: $btn
			,	onSuccess: function(res) {
					toeRedirect( deactivateUrl );
				}
			});
			return false;
		});
		$deactivateForm.find('[name="deactivate_reason"]').change(function(){
			jQuery('.wtbpDeactivateDescShell').slideUp( g_wtbpAnimationSpeed );
			if(jQuery(this).prop('checked')) {
				var $descShell = jQuery(this).parents('.wtbpDeactivateReasonShell:first').find('.wtbpDeactivateDescShell');
				if($descShell && $descShell.size()) {
					$descShell.slideDown( g_wtbpAnimationSpeed );
				}
			}
		});
	}
});