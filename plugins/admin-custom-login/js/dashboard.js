function Custom_login_dashboard(Action, id) {
	if(Action == "dashboardSave") {
		(function() {
			var dlgtrigger = document.querySelector( '[data-dialog]' ),
				somedialog = document.getElementById( dlgtrigger.getAttribute( 'data-dialog' ) ),
				// svg..
				morphEl = somedialog.querySelector( '.morph-shape' ),
				s = Snap( morphEl.querySelector( 'svg' ) ),
				path = s.select( 'path' ),
				steps = { 
					open : morphEl.getAttribute( 'data-morph-open' ),
					close : morphEl.getAttribute( 'data-morph-close' )
				},
				dlg = new DialogFx( somedialog, {
					onOpenDialog : function( instance ) {
						// animate path
						setTimeout(function() {
							path.stop().animate( { 'path' : steps.open }, 1500, mina.elastic );
						}, 250 );
					},
					onCloseDialog : function( instance ) {
						// animate path
						path.stop().animate( { 'path' : steps.close }, 250, mina.easeout );
					}
				} );
			dlgtrigger.addEventListener( 'click', dlg.toggle.bind(dlg) );
		})();
		
		if (document.getElementById('dashboard_status1').checked) {
			var dashboard_status = document.getElementById('dashboard_status1').value;
		}
		else{
			var dashboard_status = document.getElementById('dashboard_status2').value;
		}
		var PostData = "Action=" + Action + "&dashboard_status=" + dashboard_status ;
		jQuery.ajax({
			dataType : 'html',
			type: 'POST',
			url : location.href,
			cache: false,
			//data : PostData,
			data: {
				nonce_ajax: WEBLIZARADMINNONCE.adminsecurity,
				Action: Action,
				dashboard_status: dashboard_status,
			},
			complete : function() { },
			success: function(data) {
				// Save message box open
				jQuery(".dialog-button").click();
				// Function to close message box
				
				setTimeout(function(){
					jQuery("#dialog-close-button").click();
				}, 1000);
			}
		});
	}
	
	// Save Message box Close On Mouse Hover
	document.getElementById('dialog-close-button').disabled = false;
		jQuery('#dialog-close-button').hover(function () {
			jQuery("#dialog-close-button").click();
			document.getElementById('dialog-close-button').disabled = true; 
		}
	 );
	 
	// Reset Message box Close On Mouse Hover
   document.getElementById('dialog-close-button7').disabled = false;
		jQuery('#dialog-close-button7').hover(function () {
			jQuery("#dialog-close-button7").click();
			document.getElementById('dialog-close-button7').disabled = true; 
		}
	);

	if(Action == "dashboardReset") {
		(function() {
			var dlgtrigger = document.querySelector( '[data-dialog7]' ),
				somedialog = document.getElementById( dlgtrigger.getAttribute( 'data-dialog7' ) ),
				// svg..
				morphEl = somedialog.querySelector( '.morph-shape' ),
				s = Snap( morphEl.querySelector( 'svg' ) ),
				path = s.select( 'path' ),
				steps = { 
					open : morphEl.getAttribute( 'data-morph-open' ),
					close : morphEl.getAttribute( 'data-morph-close' )
				},
				dlg = new DialogFx( somedialog, {
					onOpenDialog : function( instance ) {
						// animate path
						setTimeout(function() {
							path.stop().animate( { 'path' : steps.open }, 1500, mina.elastic );
						}, 250 );
					},
					onCloseDialog : function( instance ) {
						// animate path
						path.stop().animate( { 'path' : steps.close }, 250, mina.easeout );
					}
				} );
			dlgtrigger.addEventListener( 'click', dlg.toggle.bind(dlg) );
		})();
		
		var PostData = "Action=" + Action ;
		jQuery.ajax({
			dataType : 'html',
			type: 'POST',
			url : location.href,
			cache: false,
			data: {
				nonce_ajax: WEBLIZARADMINNONCE.adminsecurity,
				Action: Action,				
			},
			//data : PostData,
			complete : function() {  },
			success: function(data) {
			jQuery(document).ready( function() {
					jQuery('input[name=dashboard_status]').val(['disable']);
				});
				// Reset message box open
				jQuery(".dialog-button7").click();
				// Function to close message box
				setTimeout(function(){
					jQuery("#dialog-close-button7").click();
				}, 1000);
			}
		});
	}
}

function Acl_show_login_form_Image() {
	var img_src= document.getElementById("login_form_image").value;
	jQuery("#top_form_img_prev").attr('src',img_src);
}	

jQuery('document').ready(function(){
	jQuery('#preview_btn_custom_copy').click(function(){
		var copyText = jQuery('#preview_btn_custom_copy');
		var clipval = jQuery('#preview_btn_custom_copy').val();
		navigator.clipboard.writeText(clipval);
		var x = document.getElementById("snackbar");
		  x.className = "show";
		  setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
	});
});