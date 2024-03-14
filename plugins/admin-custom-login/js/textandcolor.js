jQuery(function() {
    jQuery( "#button-size-slider" ).slider({
		orientation: "horizontal",
		range: "min",
		max: 100,
		min:0,
		slide: function( event, ui ) {
			jQuery( "#headline-size-text-box" ).val( ui.value );
		}
	});

    var heading_font_size = textandcolor_object.heading_font_size;
    if ( heading_font_size.length != 0 && heading_font_size != undefined ){
    	heading_font_size = textandcolor_object.heading_font_size;
    } else {
    	heading_font_size = '30';
    }
	jQuery( "#button-size-slider" ).slider("value",heading_font_size);

	jQuery( "#headline-size-text-box" ).val( jQuery( "#button-size-slider" ).slider( "value") );
  });

//button Input-Font-size slider
jQuery(function() {
	jQuery( "#button-size-slider2" ).slider({
		orientation: "horizontal",
		range: "min",
		max: 100,
		min:0,
		slide: function( event, ui ) {
		jQuery( "#input-size-text-box" ).val( ui.value );
		}
	});

	var input_font_size = textandcolor_object.input_font_size;
	if(input_font_size != ""){
		jQuery( "#button-size-slider2" ).slider("value",input_font_size);
	}else{
		jQuery( "#button-size-slider2" ).slider("value",'30');
	}
	
	jQuery( "#input-size-text-box" ).val( jQuery( "#button-size-slider2" ).slider( "value") );
});

//button Link-font-size slider
jQuery(function() {
	jQuery( "#button-size-slider3" ).slider({
		orientation: "horizontal",
		range: "min",
		max: 100,
		min:0,
		slide: function( event, ui ) {
		jQuery( "#link-size-text-box" ).val( ui.value );
		}
	});

	var link_size = textandcolor_object.link_size;
	if(link_size != ""){
		jQuery( "#button-size-slider3" ).slider("value",link_size);
	}else {
		jQuery( "#button-size-slider3" ).slider("value","30");
	}


	jQuery( "#link-size-text-box" ).val( jQuery( "#button-size-slider3" ).slider( "value") );
});

//button Button-font-size slider
jQuery(function() {
	jQuery( "#button-size-slider7" ).slider({
		orientation: "horizontal",
		range: "min",
		max: 100,
		min:0,
		slide: function( event, ui ){
		jQuery( "#button-size-text-box" ).val( ui.value );
		}
	});
	
	var button_font_size = textandcolor_object.button_font_size;
	if(button_font_size != ""){
		jQuery( "#button-size-slider7" ).slider("value",button_font_size);
	}else {
		jQuery( "#button-size-slider7" ).slider("value","30");
	}
	jQuery( "#button-size-text-box" ).val( jQuery("#button-size-slider7").slider("value"));
});

//Set Value of Drop Down
jQuery(document).ready(function(){
	//Headline Font Style
	var heading_font_style = textandcolor_object.heading_font_style;
	if(textandcolor_object != ""){
		jQuery("#headline_font_style").val(heading_font_style);
	}else {
		var heading_font_style = '';
	};
	//Input Font Style
	var  input_font_style = textandcolor_object.input_font_style;
	if(input_font_style != ""){
		jQuery("#input_font_style").val(input_font_style);
	}else{
	 var input_font_style = "";
	}

	//Link Font Style 
	var link_font_style = textandcolor_object.link_font_style;
	if(link_font_style != ""){
		jQuery("#link_font_style").val(link_font_style);
	}else{
		var link_font_style = "";
	}

	//Button Font Style 
	var button_font_style = textandcolor_object.button_font_style;
	if(button_font_style != ""){
		jQuery("#button_font_style").val( button_font_style); 
	} else {
			var button_font_style = "";
		} 
	});


function Custom_login_text(Action, id){
	if(Action == "textandcolorSave") {
		(function() {
			var dlgtrigger = document.querySelector( '[data-dialog3]' ),
				somedialog = document.getElementById( dlgtrigger.getAttribute( 'data-dialog3' ) ),
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

		var heading_font_color = jQuery("#headline-font-color").val();
		var input_font_color = jQuery("#input-font-color").val();
		var link_color = jQuery("#link-color").val();
		var button_color = jQuery("#button-color").val();
		var login_button_font_color = jQuery("#login-button-text-color").val();
		var heading_font_size = jQuery("#headline-size-text-box").val();
		var input_font_size = jQuery("#input-size-text-box").val();
		var link_size = jQuery("#link-size-text-box").val();
		var button_font_size = jQuery("#button-size-text-box").val();
		
		if (document.getElementById('enable_Link_shadow1').checked) {
			var enable_link_shadow = document.getElementById('enable_Link_shadow1').value;
		} else {
			var enable_link_shadow = document.getElementById('enable_Link_shadow2').value;
		}
		if (document.getElementById('show_remember_me_field1').checked) {
			var show_remember_me_field = document.getElementById('show_remember_me_field1').value;
		} else {
			var show_remember_me_field = document.getElementById('show_remember_me_field2').value;
		}
		if (document.getElementById('show_back_to_site_link1').checked) {
			var show_back_to_site_link = document.getElementById('show_back_to_site_link1').value;
		} else {
			var show_back_to_site_link = document.getElementById('show_back_to_site_link2').value;
		}
		if (document.getElementById('show_copyright_link_text1').checked) {
			var show_copyright_link_text = document.getElementById('show_copyright_link_text1').value;
		} else {
			var show_copyright_link_text = document.getElementById('show_copyright_link_text2').value;
		}
		var link_shadow_color = jQuery("#link-shadow-color").val();
		
		var heading_font_style = jQuery( "#headline_font_style option:selected" ).val();
		var input_font_style = jQuery( "#input_font_style option:selected" ).val();
		var link_font_style = jQuery( "#link_font_style option:selected" ).val();
		var button_font_style = jQuery( "#button_font_style option:selected" ).val();
		
		if (document.getElementById('enable_inputbox_icon1').checked) {
			var enable_inputbox_icon = document.getElementById('enable_inputbox_icon1').value;
		} else {
			var enable_inputbox_icon = document.getElementById('enable_inputbox_icon2').value;
		}
		var user_input_icon = jQuery("#user-input-icon").val();
		var password_input_icon = jQuery("#password-input-icon").val();
		//var PostData = "Action=" + Action + "&heading_font_color=" + heading_font_color + "&input_font_color=" + input_font_color + "&link_color=" + link_color + "&button_color=" + button_color + "&login_button_font_color=" + login_button_font_color  + "&heading_font_size=" + heading_font_size + "&input_font_size=" + input_font_size + "&link_size=" + link_size + "&button_font_size=" + button_font_size + "&enable_link_shadow=" + enable_link_shadow + "&show_remember_me_field=" + show_remember_me_field + "&show_back_to_site_link=" + show_back_to_site_link + "&show_copyright_link_text=" + show_copyright_link_text + "&link_shadow_color=" + link_shadow_color + "&heading_font_style=" + heading_font_style + "&input_font_style=" + input_font_style + "&link_font_style=" + link_font_style + "&button_font_style=" + button_font_style + "&enable_inputbox_icon=" + enable_inputbox_icon + "&user_input_icon=" + user_input_icon + "&password_input_icon=" + password_input_icon;
		jQuery.ajax({
			dataType : 'html',
			type: 'POST',
			url : location.href,
			cache: false,
			//data : PostData,
			data : {
				nonce_ajax: WEBLIZARADMINNONCE.adminsecurity,
				Action: Action,
				heading_font_color: heading_font_color,
				input_font_color : input_font_color,
				link_color : link_color,
				button_color : button_color,
				login_button_font_color : login_button_font_color,
				heading_font_size : heading_font_size,
				input_font_size : input_font_size,
				link_size : link_size,
				button_font_size : button_font_size,
				enable_link_shadow : enable_link_shadow,
				show_remember_me_field : show_remember_me_field,
				show_back_to_site_link : show_back_to_site_link,
				show_copyright_link_text : show_copyright_link_text,
				link_shadow_color : link_shadow_color,
				heading_font_style : heading_font_style,
				input_font_style : input_font_style,
				link_font_style : link_font_style,
				button_font_style : button_font_style,
				enable_inputbox_icon : enable_inputbox_icon,
				user_input_icon : user_input_icon,
				password_input_icon : password_input_icon,
			},
			complete : function() {  },
			success: function(data) {
				// Save message box open
				jQuery(".dialog-button3").click();
				// Function to close message box
				setTimeout(function(){
					jQuery("#dialog-close-button3").click();
				}, 1000);
			}
		});
	}
	// Save Message box Close On Mouse Hover
	document.getElementById('dialog-close-button3').disabled = false;
	jQuery('#dialog-close-button3').hover(function () {
		jQuery("#dialog-close-button3").click();
		document.getElementById('dialog-close-button3').disabled = true; 
	});
	 
	// Reset Message box Close On Mouse Hover
	document.getElementById('dialog-close-button9').disabled = false;
	jQuery('#dialog-close-button9').hover(function () {
		jQuery("#dialog-close-button9").click();
		document.getElementById('dialog-close-button9').disabled = true; 
	});
	 
	if(Action == "textandcolorReset") {		
		(function() {
			var dlgtrigger = document.querySelector( '[data-dialog9]' ),
				somedialog = document.getElementById( dlgtrigger.getAttribute( 'data-dialog9' ) ),
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
				});
			dlgtrigger.addEventListener( 'click', dlg.toggle.bind(dlg) );
		})();
		
		var PostData = "Action=" + Action ;
		jQuery.ajax({
			dataType : 'html',
			type: 'POST',
			url : location.href,
			cache: false,
			//data : PostData,
			data : {
				nonce_ajax: WEBLIZARADMINNONCE.adminsecurity,
				Action: Action,
			},
			complete : function() {  },
			success: function(data) {				 
				//Headline Font Style
				jQuery("#headline_font_style").val('Arial');
				//Input Font Style
				jQuery("#input_font_style").val('Arial');
				//Link Font Style 
				jQuery("#link_font_style").val('Arial');	
				//Button Font Style 
				jQuery("#button_font_style").val('Arial');
				//	Heading Font Color
				jQuery("#td-headline-font-color a.wp-color-result").closest("a").css({"background-color": "#ffffff"});
				//	Input Font Color
				jQuery("#td-input-font-color a.wp-color-result").closest("a").css({"background-color": "#000000"});
				//Link Font Color
				jQuery("#td-link-font-color a.wp-color-result").closest("a").css({"background-color": "#ffffff"});
				//	Button Font Color
				jQuery("#td-button-font-color a.wp-color-result").closest("a").css({"background-color": "#dd3333"});
				jQuery("#td-login-button-font-color a.wp-color-result").closest("a").css({"background-color": "#ffffff"});

				jQuery( "#button-size-slider" ).slider("value",14 );
				jQuery( "#headline-size-text-box" ).val( jQuery( "#button-size-slider" ).slider( "value") );
				
				jQuery( "#button-size-slider2" ).slider("value",18 );
				jQuery( "#input-size-text-box" ).val( jQuery( "#button-size-slider2" ).slider( "value") );
				
				jQuery( "#button-size-slider3" ).slider("value",14 );
				jQuery( "#link-size-text-box" ).val( jQuery( "#button-size-slider3" ).slider( "value") );
				
				jQuery( "#button-size-slider7" ).slider("value",14 );
				jQuery( "#button-size-text-box" ).val( jQuery( "#button-size-slider7" ).slider( "value") );
				
				document.getElementById("user-input-icon").value ='fa-user';
				document.getElementById("password-input-icon").value ='fa-key';
				// Reset message box open
				jQuery(".dialog-button9").click();
				// Function to close message box
				setTimeout(function(){
					jQuery("#dialog-close-button9").click();
				}, 1000);
			}
		});
	}
}