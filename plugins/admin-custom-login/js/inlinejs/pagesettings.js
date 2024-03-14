function Acl_show_Image_3() {
	var img_src= document.getElementById("logo-image").value;
	jQuery("#logo_img_prev").attr('src',img_src);   
}
//button Button-font-size slider
jQuery(function() {
	jQuery( "#logo-width-slider" ).slider({
		orientation: "horizontal",
		range: "min",
		max: 500,
		min:0,
		slide: function( event, ui ) {
			jQuery( "#logo-width-text-box" ).val( ui.value );
		}
	});	
	var logo_width = page_settings_object.logo_width;
	if(logo_width != ""){
		jQuery( "#logo-width-slider" ).slider("value",logo_width);
	}else {
		logo_width = "200";
	}
	jQuery( "#logo-width-text-box" ).val( jQuery( "#logo-width-slider" ).slider( "value") );
});

//button Button-font-size slider
jQuery(function() {
	jQuery( "#logo-height-slider" ).slider({
		orientation: "horizontal",
		range: "min",
		max: 500,
		min:0,
		slide: function(event,ui){
			jQuery( "#logo-height-text-box" ).val( ui.value );
		}
	});
	var logo_height = page_settings_object.logo_height;
	if(logo_height != ""){
		jQuery( "#logo-height-slider" ).slider("value",logo_height);
	}else {
		logo_height = "60";
	}
	jQuery( "#logo-height-text-box" ).val( jQuery( "#logo-height-slider" ).slider( "value") );
});


function Custom_login_logo(Action, id){
	if(Action == "logoSave") {
		(function(){
			var dlgtrigger = document.querySelector( '[data-dialog4]' ),
				somedialog = document.getElementById( dlgtrigger.getAttribute( 'data-dialog4' ) ),
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

		var logo_image     = jQuery("#logo-image").val();
		var logo_show      = jQuery('input[name=show_logo]:checked').val();
		var logo_width     = jQuery("#logo-width-text-box").val();
		var logo_height    = jQuery("#logo-height-text-box").val();
		var logo_url       = jQuery("#log-url").val();
		var logo_url_title = jQuery("#log-url-title").val();
		//var PostData = "Action=" + Action + "&logo_image=" + logo_image + "&logo_show=" + logo_show + "&logo_width=" + logo_width + "&logo_height=" + logo_height + "&logo_url=" + logo_url + "&logo_url_title=" + logo_url_title;
		jQuery.ajax({
			dataType : 'html',
			type: 'POST',
			url : location.href,
			cache: false,
			//data : PostData,
			data: {
				nonce_ajax : WEBLIZARADMINNONCE.adminsecurity,
				Action : Action,
				logo_image : logo_image,
				logo_show : logo_show,
				logo_width : logo_width,
				logo_height : logo_height,
				logo_url : logo_url,
				logo_url_title : logo_url_title,
			},
			complete : function() {  },
			success: function(data) {
				// Save message box open
				jQuery(".dialog-button4").click();
				// Function to close message box
				setTimeout(function(){
					jQuery("#dialog-close-button4").click();
				}, 1000);
			}
		});
	}
	
	// Save Message box Close On Mouse Hover
	document.getElementById('dialog-close-button4').disabled = false;
	jQuery('#dialog-close-button4').hover(function () {
		jQuery("#dialog-close-button4").click();
		document.getElementById('dialog-close-button4').disabled = true; 
	});
	 
	// Reset Message box Close On Mouse Hover
	document.getElementById('dialog-close-button10').disabled = false;
	jQuery('#dialog-close-button10').hover(function () {
	   jQuery("#dialog-close-button10").click();
	   document.getElementById('dialog-close-button10').disabled = true; 
	});
	
	if(Action == "logoReset") {		
		(function(){
			var dlgtrigger = document.querySelector( '[data-dialog10]' ),
				somedialog = document.getElementById( dlgtrigger.getAttribute( 'data-dialog10' ) ),
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
			//data : PostData,
			data: {
				nonce_ajax : WEBLIZARADMINNONCE.adminsecurity,
				Action : Action,
			},
			complete : function() {  },
			success: function(data) {
				document.getElementById("logo-image").value;

				jQuery( "#logo-width-slider" ).slider("value",274 );
				jQuery( "#logo-width-text-box" ).val( jQuery( "#logo-width-slider" ).slider( "value") );

				jQuery( "#logo-height-slider" ).slider("value",63);
				jQuery( "#logo-height-text-box" ).val( jQuery( "#logo-height-slider" ).slider( "value") );

				document.getElementById("log-url").value;
				document.getElementById("log-url-title").value ="Your Site Name and Info";

				// Reset message box open
				jQuery(".dialog-button10").click();
				// Function to close message box
				setTimeout(function(){
					jQuery("#dialog-close-button10").click();
				}, 1000);
			}
		});
	}
}
// Font awsome icon picker
  jQuery(function() {
	jQuery('.icp').iconpicker({
			title: 'Font Awesome 5 Free', // Popover title (optional) only if specified in the template
			selected: false, // use this value as the current item and ignore the original
			defaultValue: true, // use this value as the current item if input or element value is empty
			placement: 'topRight', // (has some issues with auto and CSS). auto, top, bottom, left, right
			showFooter: true,
			mustAccept:false,
		});              
    });