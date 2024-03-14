
//on load form floating
var floatingform = login_object.login_form_position;
if(floatingform == "default") {
	jQuery( "#div-login-float" ).hide();
	jQuery( "#div-login-custom" ).hide();
}	
if(floatingform == "lf_float_style") {
	jQuery( "#div-login-float" ).show();
	jQuery( "#div-login-custom" ).hide();
}
if(floatingform == "lf_customize_style") {
	jQuery( "#div-login-float" ).hide();
	jQuery( "#div-login-custom" ).show();	
}

function form_position_change() {
	var floatingformchange = jQuery( "#login_form_position option:selected" ).val();
	
	if(floatingformchange== "default") {
		jQuery( "#div-login-float" ).hide();
		jQuery( "#div-login-custom" ).hide();
	}	
	if(floatingformchange== "lf_float_style") {
		jQuery( "#div-login-float" ).show();
		jQuery( "#div-login-custom" ).hide();
	}
	if(floatingformchange== "lf_customize_style") {
		jQuery( "#div-login-float" ).hide();
		jQuery( "#div-login-custom" ).show();		
	}
}

function Acl_show_Image_2() {
	var img_src= document.getElementById("login_bg_image").value;
	jQuery("#img").attr('src',img_src);   
}

function Acl_login_img_clear() {
	document.getElementById("login_bg_image").value ="";
}

//button Login Form Width slider
jQuery(function() {
	jQuery( "#login-opacity-slider" ).slider({
		orientation: "horizontal",
		range: "min",
		max: 10,
		min: 0,
		slide: function( event, ui ) {
			jQuery( "#login-opacity-text-box" ).val( ui.value );
		}
	});


	var login_form_opacity = login_object.login_form_opacity;
	
    if(login_form_opacity != ""){
		jQuery( "#login-opacity-slider" ).slider("value",login_form_opacity);
	}else{
		login_form_opacity = "300";
	}	
	jQuery( "#login-opacity-text-box" ).val( jQuery( "#login-opacity-slider" ).slider( "value") );
});

//button Login Form Width slider
jQuery(function() {
	jQuery( "#button-size-slider4" ).slider({
		orientation: "horizontal",
		range: "min",
		max: 600,
		min:200,
		slide: function( event, ui ) {
		jQuery( "#login-width-text-box" ).val( ui.value );
		}
	});	

	var login_form_width = login_object.login_form_width;
	if(login_form_width != ""){
		jQuery( "#button-size-slider4" ).slider("value",login_form_width);
	}else{
		login_form_width = "300";
	}	
	jQuery( "#login-width-text-box" ).val( jQuery( "#button-size-slider4" ).slider( "value") );  
});

		
//button Login Form Align Left
jQuery(function() {
	jQuery( "#button_left" ).slider({
		orientation: "horizontal",
		range: "min",
		max:1300,
		min:0,
		slide: function( event, ui ) {
		jQuery( "#login_form_left" ).val( ui.value );
		}
	});	
	var login_form_left = login_object.login_form_left;
	if(login_form_left != ""){
		jQuery( "#button_left" ).slider("value",login_form_left);
	}else{
		login_form_left = "700";
	}	
	jQuery( "#login_form_left" ).val( jQuery( "#button_left" ).slider( "value") );  
});
//button Login Form Align Top
jQuery(function() {
	jQuery( "#button_top" ).slider({
		orientation: "horizontal",
		range: "min",
		max:600,
		min:0,
		slide: function( event, ui ) {
			jQuery( "#login_form_top" ).val( ui.value );
		}
	});	

	var login_form_top = login_object.login_form_top;
	if(login_form_top != ""){
		jQuery( "#button_top" ).slider("value",login_form_top);
	}else{
		login_form_top = "300";
	}		
	jQuery( "#login_form_top" ).val( jQuery( "#button_top" ).slider( "value") );  
});

//button Border Radius slider
jQuery(function() {
	jQuery( "#button-size-slider5" ).slider({
		orientation: "horizontal",
		range: "min",
		max: 15,
		min:0,
		slide: function( event, ui ) {
		jQuery( "#login-Radius-text-box" ).val( ui.value );
		}
	});

	var login_form_radius = login_object.login_form_radius;
	if(login_form_radius != ""){
		jQuery( "#button-size-slider5" ).slider("value",login_form_radius);
	}else{
		login_form_radius = "3";
	}	
	jQuery( "#login-Radius-text-box" ).val( jQuery( "#button-size-slider5" ).slider( "value") );
});

//button Border Thickness slider
jQuery(function() {
	jQuery( "#button-size-slider6" ).slider({
		orientation: "horizontal",
		range: "min",
		max: 20,
		min:0,
		slide: function( event, ui ) {
			jQuery( "#login-thickness-text-box" ).val( ui.value );
		}
	});

	var login_border_thikness = login_object.login_border_thikness;
	if(login_border_thikness != ""){
		jQuery( "#button-size-slider6" ).slider("value",login_border_thikness);
	}else{
		login_border_thikness = "3";
	}	
	jQuery( "#login-thickness-text-box" ).val( jQuery( "#button-size-slider6" ).slider( "value") );
});


//Button Font Size slider for login form message
jQuery(function() {
	jQuery( "#button-msg-font-resizer" ).slider({
		orientation: "horizontal",
		range: "min",
		max: 48,
		min:12,
		slide: function( event, ui ) {
			jQuery( "#login-msg-text-size" ).val( ui.value );
		}
	});

	var login_msg_fontsize = login_object.login_msg_fontsize;
	if(login_msg_fontsize != ""){
		jQuery( "#button-msg-font-resizer" ).slider("value",login_msg_fontsize);
	}else{
		login_msg_fontsize = "16";
	}	
	jQuery( "#login-msg-text-size" ).val( jQuery( "#button-msg-font-resizer" ).slider( "value") );
});	

//Set Value of Drop Down
jQuery(document).ready(function() {
	//Login Background Select
	var login_bg_type = login_object.login_bg_type;
	if(login_bg_type != ""){
		jQuery("#select-login-bg").val(login_bg_type);
	}else{
		login_bg_type = "";
	}
	loginbgchange();
	//login Background Effect
	var login_bg_effect = login_object.login_bg_effect;
	if(login_bg_effect != ""){
		jQuery("#login_bg_color_overlay").val(login_bg_effect);
	}else{
		login_bg_effect = "";
	}
	//login border style 
	var login_border_style = login_object.login_border_style;
	if(login_border_style != ""){
		jQuery("#login_border_style").val(login_border_style);
	}else{
		login_border_style = "";
	}
	var login_redirect_force = login_object.login_redirect_force;
	if(login_redirect_force != ""){
		jQuery("#login_redirect_force").val(login_redirect_force);
	}else{
		login_redirect_force = "";
	}
	//login Background Repeat 
	var login_bg_repeat = login_object.login_bg_repeat;
	if(login_bg_repeat != ""){
		jQuery("#login_bg_repeat").val(login_bg_repeat);
	}else{
		login_bg_repeat = "";
	}
	//login Background Position 
	var login_bg_position = login_object.login_bg_position;
	if(login_bg_position != ""){
		jQuery("#login_bg_position").val(login_bg_position);
	}else{
		login_bg_position = "";
	}
});

function loginbgchange() {
	jQuery(".no-login-bg").hide();

	var Login_optionvalue = jQuery( "#select-login-bg option:selected" ).val();
	if(Login_optionvalue== "static-background-color") {
		jQuery( "#div-login-bg-color" ).show();
	}
	
	if(Login_optionvalue== "static-background-image") {
		jQuery( "#div-login-bg-image" ).show();
	}		
}



function Custom_login_login(Action, id){
	if(Action == "loginbgSave") {
		(function() {
		
			var dlgtrigger = document.querySelector( '[data-dialog2]' ),
			
				somedialog = document.getElementById( dlgtrigger.getAttribute( 'data-dialog2' ) ),
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
		var login_form_position  = jQuery( "#login_form_position option:selected" ).val();
		var login_form_float = jQuery('input[name=login_form_float]:checked').val();
		if (!login_form_float) {
			login_form_float = 'center';
		}
		var login_form_left = jQuery("#login_form_left").val();
		var login_form_top = jQuery("#login_form_top").val();
		var Login_bg_value = jQuery( "#select-login-bg option:selected" ).val();
		var login_background_color = jQuery("#login-background-color").val();
		var login_bg_color_overlay = jQuery( "#login_bg_color_overlay option:selected" ).val();
		var login_bg_image = jQuery("#login_bg_image").val();
		var login_form_opacity = jQuery("#login-opacity-text-box").val();
		var login_form_width= jQuery("#login-width-text-box").val();
		var login_form_radius= jQuery("#login-Radius-text-box").val();
		var login_border_style = jQuery( "#login_border_style option:selected" ).val();
		var login_redirect_force = jQuery( "#login_redirect_force option:selected" ).val();
		var login_border_thikness = jQuery("#login-thickness-text-box").val();
		var login_border_color = jQuery("#login-Border-color").val();
		var login_bg_repeat = jQuery( "#login_bg_repeat option:selected" ).val();
		var login_bg_position = jQuery( "#login_bg_position option:selected" ).val();
		var login_redirect_user = jQuery( "#login_redirect_user").val();
		var login_custom_css = jQuery( "#login_custom_css").val();
	    var login_force_redirect_url = jQuery( "#login_force_redirect_url").val();
		var log_form_above_msg = jQuery( "#log_form_above_msg").val();
		var login_pass_reset_msg = jQuery( "#login_pass_reset_msg").val();
		var login_msg_fontsize = jQuery("#login-msg-text-size").val();
		var login_msg_font_color = jQuery("#login-msg-font-color").val();
		var tagline_msg = jQuery( "#tagline_msg").val();
		var login_tagline_text_color = jQuery("#login-tagline-text-color").val();
		var login_tagline_link_color = jQuery("#login-tagline-link-color").val();
		var user_cust_lbl = jQuery( "#user_cust_lbl").val();
		var pass_cust_lbl = jQuery( "#pass_cust_lbl").val();
		var label_username = jQuery( "#label_username").val();
		var label_password = jQuery( "#label_password").val();
		var label_loginButton = jQuery( "#label_loginButton").val();
		//alert(tagline_msg);
		
		if (document.getElementById('login_enable_shadow1').checked) {
			var login_enable_shadow = document.getElementById('login_enable_shadow1').value;
		} else {
			var login_enable_shadow = document.getElementById('login_enable_shadow2').value;
		}
		
		if (document.getElementById('login_enable_language_switcher1').checked) {
			var login_enable_language_switcher = document.getElementById('login_enable_language_switcher1').value;
		} else {
			var login_enable_language_switcher = document.getElementById('login_enable_language_switcher2').value;
		}
		var login_shadow_color = jQuery("#login_shadow_color").val();		

		var PostData = "Action=" + Action + "&login_form_position=" + login_form_position + "&Login_bg_value=" + Login_bg_value + "&login_background_color=" + login_background_color + "&login_bg_color_overlay=" + login_bg_color_overlay + "&login_bg_image=" + login_bg_image + "&login_form_opacity=" + login_form_opacity  + "&login_form_width=" + login_form_width + "&login_form_radius=" + login_form_radius + "&login_border_style=" + login_border_style + "&login_redirect_force=" + login_redirect_force +"&login_border_thikness=" + login_border_thikness + "&login_border_color=" + login_border_color + "&login_bg_repeat=" + login_bg_repeat + "&login_bg_position=" + login_bg_position + "&login_enable_shadow=" + login_enable_shadow+ "&login_enable_language_switcher=" + login_enable_language_switcher + "&login_shadow_color=" + login_shadow_color + "&login_custom_css=" + login_custom_css + "&login_redirect_user=" + login_redirect_user + "&login_force_redirect_url=" + login_force_redirect_url +"&login_form_left=" + login_form_left + "&log_form_above_msg=" + log_form_above_msg +"&login_pass_reset_msg=" + login_pass_reset_msg + "&login_msg_font_color=" + login_msg_font_color + "&login_tagline_text_color=" + login_tagline_text_color + "&login_tagline_link_color=" + login_tagline_link_color + "&login_msg_fontsize=" + login_msg_fontsize  +  "&login_form_top=" + login_form_top + "&login_form_float=" + login_form_float + "&tagline_msg=" + tagline_msg + "&user_cust_lbl=" + user_cust_lbl + "&pass_cust_lbl=" + pass_cust_lbl + '&label_username=' + label_username + '&label_password=' + label_password + '&label_loginButton=' + label_loginButton;
		jQuery.ajax({
			dataType : 'html',
			type: 'POST',
			url : location.href,
			cache: false,
			//data : PostData,
			data: {
				nonce_ajax: WEBLIZARADMINNONCE.adminsecurity,
				Action : Action,
				login_form_position : login_form_position,
				Login_bg_value : Login_bg_value,
				login_background_color : login_background_color,
				login_bg_color_overlay : login_bg_color_overlay,
				login_bg_image : login_bg_image,
				login_form_opacity : login_form_opacity,
				login_form_width: login_form_width,
				login_form_radius: login_form_radius,
				login_border_style: login_border_style,
				login_redirect_force: login_redirect_force,
				login_border_thikness: login_border_thikness,
				login_border_color: login_border_color,
				login_bg_repeat: login_bg_repeat,
				login_bg_position: login_bg_position,
				login_enable_shadow: login_enable_shadow,
				login_enable_language_switcher: login_enable_language_switcher,
				login_shadow_color: login_shadow_color,
				login_redirect_user: login_redirect_user,
				login_custom_css: login_custom_css,
				login_force_redirect_url: login_force_redirect_url,
				login_form_left: login_form_left,
				log_form_above_msg: log_form_above_msg,
				login_pass_reset_msg: login_pass_reset_msg,
				login_msg_font_color: login_msg_font_color,
				login_tagline_text_color: login_tagline_text_color,
				login_tagline_link_color: login_tagline_link_color,
				login_msg_fontsize: login_msg_fontsize,
				login_form_top: login_form_top,
				login_form_float: login_form_float,
				tagline_msg: tagline_msg,
				user_cust_lbl: user_cust_lbl,
				pass_cust_lbl: pass_cust_lbl,
				label_username: label_username,
				label_password: label_password,
				label_loginButton: label_loginButton,
			},
			complete : function() {  },
			success: function(data) {
				// Save message box open
				jQuery(".dialog-button2").click();
				// Function to close message box
				setTimeout(function(){
					jQuery("#dialog-close-button2").click();
				}, 1000);
			}
		});
	}
	// Save Message box Close On Mouse Hover
	document.getElementById('dialog-close-button2').disabled = false;
	jQuery('#dialog-close-button2').hover(function () {
		jQuery("#dialog-close-button2").click();
		document.getElementById('dialog-close-button2').disabled = true; 
	});

	// Reset Message box Close On Mouse Hover
	document.getElementById('dialog-close-button8').disabled = false;
	jQuery('#dialog-close-button8').hover(function () {
		jQuery("#dialog-close-button8").click();
		document.getElementById('dialog-close-button8').disabled = true; 
	});
	 
	//on reset settings
	if(Action == "loginbgReset") {
		(function() {
			var dlgtrigger = document.querySelector( '[data-dialog8]' ),
			
				somedialog = document.getElementById( dlgtrigger.getAttribute( 'data-dialog8' ) ),
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
				nonce_ajax: WEBLIZARADMINNONCE.adminsecurity,
				Action : Action,
			},
			complete : function() {  },
			success: function(data) {
				// Show Background Image Option
				jQuery(".no-login-bg").hide();
				jQuery( "#div-login-bg-image" ).show();
				
				// Show Login Form Position
				jQuery( "#div-login-float" ).hide();
				jQuery( "#div-login-custom" ).hide();
				jQuery("#login_form_position").val('login_form_float');
				
				//login Background Effect
				 jQuery("#login_bg_color_overlay").val('pattern-1');
				//login border style 
				 jQuery("#login_border_style").val('solid');

				 jQuery("#login_redirect_force").val('no');
				//login Background Repeat 
				 jQuery("#login_bg_repeat").val('repeat');	
				//login Background Position 
				 jQuery("#login_bg_position").val('left top'); 
				 
				//Enable Login From Shadow
				jQuery(document).ready( function() {
					jQuery('input[name=enable_form_shadow]').val(['yes']);
					// Message Display Above Login Form
					jQuery("#log_form_above_msg").val(''); 
					jQuery("#login_pass_reset_msg").val(''); 

					jQuery("#login_custom_css").val(''); 
					
					// Tagline Message Display Below Login Form
					jQuery("#tagline_msg").val(''); 
					//login Redirect  User
					jQuery("#login_redirect_user").val(''); 

					jQuery("#login_force_redirect_url").val("/wp-login.php");
					// Username label text
					jQuery("#user_cust_lbl").val('Type Username or Email');
					// Password label text
					jQuery("#pass_cust_lbl").val('Type Password'); 
				});
				
				//Login Image
				document.getElementById("login_bg_image").value ="images/3d-background.jpg";
				// Login From Background Color
				jQuery("#td-login-background-color a.wp-color-result").closest("a").css({"background-color": "#1e73be"});
				// Login From Border Color
				jQuery("#td-login-Border-color a.wp-color-result").closest("a").css({"background-color": "#0069A0"});
				// Login From Shadow Color
				jQuery("#login_shadow_color a.wp-color-result").closest("a").css({"background-color": "#C8C8C8"});	

				// Login Message Font Color
				jQuery("#td-login-msg-font-color a.wp-color-result").closest("a").css({"background-color": "#ffffff"});	
				jQuery("#td-login-tagline-text-color a.wp-color-result").closest("a").css({"background-color": "#ffffff"});	
				jQuery("#td-login-tagline-link-color a.wp-color-result").closest("a").css({"background-color": "#f00"});	

				jQuery( "#login-opacity-slider" ).slider("value",10);
				jQuery( "#login-opacity-text-box" ).val( jQuery( "#login-opacity-slider" ).slider( "value") );
				
				jQuery( "#button-size-slider4" ).slider("value",520);
				jQuery( "#login-width-text-box" ).val( jQuery( "#button-size-slider4" ).slider( "value") );
				
				jQuery( "#button-size-slider5" ).slider("value",10 );
				jQuery( "#login-Radius-text-box" ).val( jQuery( "#button-size-slider5" ).slider( "value") );
				
				jQuery( "#button-size-slider6" ).slider("value",4 );
				jQuery( "#login-thickness-text-box" ).val( jQuery( "#button-size-slider6" ).slider( "value") );

				jQuery( "#button-msg-font-resizer" ).slider("value",16 );
				jQuery( "#login-msg-text-size" ).val( jQuery( "#button-msg-font-resizer" ).slider( "value") );
				// Reset message box open
				jQuery(".dialog-button8").click();
				// Function to close message box
				setTimeout(function(){
					jQuery("#dialog-close-button8").click();
				}, 1000);
			}
		});
	}
}

function Custom_login_social(Action, id){
	if(Action == "socialSave") {
		(function() {
			var dlgtrigger = document.querySelector( '[data-dialog5]' ),

				somedialog = document.getElementById( dlgtrigger.getAttribute( 'data-dialog5' ) ),
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
		//enable disable
		var enable_social_icon = jQuery( "#enable_social_icon option:selected" ).val();

		if (document.getElementById('social_size1').checked) {
			var social_icon_size = document.getElementById('social_size1').value;
		} else if (document.getElementById('social_size2').checked) {
			var social_icon_size = document.getElementById('social_size2').value;
		} else {
			var social_icon_size = document.getElementById('social_size3').value;
		}
		if (document.getElementById('social_layout1').checked) {
			var social_icon_layout = document.getElementById('social_layout1').value;
		} else {
			var social_icon_layout = document.getElementById('social_layout2').value;
		}
		if (document.getElementById('social_link_new_window1').checked) {
			var social_link_new_window = document.getElementById('social_link_new_window1').value;
		} else {
			var social_link_new_window = document.getElementById('social_link_new_window2').value;
		}
		var social_icon_color = jQuery("#social-icon-color").val();
		var social_icon_color_onhover = jQuery("#social-icon-color-onhover").val();
		var social_icon_bg = jQuery("#social-bg-color").val();
		var social_icon_bg_onhover = jQuery("#social-bg-color-onhover").val();
		
		// Social Links
		var social_facebook_link = jQuery("#facebook-link").val();
		var social_twitter_link = jQuery("#twitter-link").val();
		var social_linkedin_link = jQuery("#linkedin-link").val();
		var social_pinterest_link = jQuery("#pinterest-link").val();
		var social_digg_link = jQuery("#digg-link").val();
		var social_youtube_link = jQuery("#youtube-link").val();
		var social_flickr_link = jQuery("#flickr-link").val();
		var social_tumblr_link = jQuery("#tumblr-link").val();
		var social_skype_link = jQuery("#skype-link").val();
		var social_instagram_link = jQuery("#instagram-link").val();
		var social_telegram_link = jQuery("#telegram-link").val();
		var social_whatsapp_link = jQuery("#whatsapp-link").val();

		//console.log(social_link_new_window);

		var PostData = "Action=" + Action + "&enable_social_icon=" + enable_social_icon + "&social_icon_size=" + social_icon_size + "&social_icon_layout=" + social_icon_layout + "&social_link_new_window=" + social_link_new_window + "&social_icon_color=" + social_icon_color + "&social_icon_color_onhover=" + social_icon_color_onhover + "&social_icon_bg=" + social_icon_bg  + "&social_icon_bg_onhover=" + social_icon_bg_onhover + "&social_facebook_link=" + social_facebook_link + "&social_twitter_link=" + social_twitter_link + "&social_linkedin_link=" + social_linkedin_link + "&social_pinterest_link=" + social_pinterest_link + "&social_digg_link=" + social_digg_link + "&social_youtube_link=" + social_youtube_link + "&social_flickr_link=" + social_flickr_link + "&social_tumblr_link=" + social_tumblr_link + "&social_skype_link=" + social_skype_link + "&social_instagram_link=" + social_instagram_link + "&social_telegram_link=" + social_telegram_link + "&social_whatsapp_link=" + social_whatsapp_link;
		jQuery.ajax({
			dataType : 'html',
			type: 'POST',
			url : location.href,
			cache: false,
			//data : PostData,
			data: {
				nonce_ajax : WEBLIZARADMINNONCE.adminsecurity,
				Action : Action,
				enable_social_icon : enable_social_icon,
				social_icon_size : social_icon_size,
				social_icon_layout : social_icon_layout,
				social_link_new_window : social_link_new_window,
				social_icon_color : social_icon_color,
				social_icon_color_onhover : social_icon_color_onhover,
				social_icon_bg : social_icon_bg,
				social_icon_bg_onhover : social_icon_bg_onhover,
				social_facebook_link : social_facebook_link,
				social_twitter_link : social_twitter_link,
				social_linkedin_link : social_linkedin_link,
				social_pinterest_link : social_pinterest_link,
				social_digg_link : social_digg_link,
				social_youtube_link : social_youtube_link,
				social_flickr_link : social_flickr_link,
				social_tumblr_link : social_tumblr_link,
				social_skype_link : social_skype_link,
				social_instagram_link : social_instagram_link,
				social_telegram_link : social_telegram_link,
				social_whatsapp_link : social_whatsapp_link,
			},
			complete : function() {  },
			success: function(data) {
				// Save message box open
				jQuery(".dialog-button5").click();
				// Function to close message box
				setTimeout(function(){
					jQuery("#dialog-close-button5").click();
				}, 1000);
			}
		});
	}
	// Save Message box Close On Mouse Hover
	document.getElementById('dialog-close-button5').disabled = false;
	jQuery('#dialog-close-button5').hover(function () {
		jQuery("#dialog-close-button5").click();
		document.getElementById('dialog-close-button5').disabled = true; 
	});
	 
	// Reset Message box Close On Mouse Hover
	document.getElementById('dialog-close-button6').disabled = false;
	jQuery('#dialog-close-button6').hover(function () {
		jQuery("#dialog-close-button6").click();
		document.getElementById('dialog-close-button6').disabled = true; 
	});
	if(Action == "socialReset") {
		(function() {
			var dlgtrigger = document.querySelector( '[data-dialog6]' ),

				somedialog = document.getElementById( dlgtrigger.getAttribute( 'data-dialog6' ) ),
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
				jQuery("#enable_social_icon").val('outer');

				jQuery(document).ready( function() {
					jQuery('input[name=social_size]').val(['mediam']);
				});
				
				jQuery(document).ready( function() {
					jQuery('input[name=social_layout]').val(['rectangle']);
				});
				
				jQuery("#social-icon-color a.wp-color-result").closest("a").css({"background-color": "#ffffff"});
				jQuery("#social-icon-color-onhover a.wp-color-result").closest("a").css({"background-color": "#1e73be"});
				jQuery("#social-bg-color a.wp-color-result").closest("a").css({"background-color": "#1e73be"});
				jQuery("#social-bg-color-onhover a.wp-color-result").closest("a").css({"background-color": "#ffffff"});
				
				document.getElementById("facebook-link").value ="http://facebook.com";
				document.getElementById("twitter-link").value ="https://twitter.com/";
				document.getElementById("linkedin-link").value ="https://in.linkedin.com/";
				document.getElementById("google-plus-link").value ="http://plus.google.com";
				document.getElementById("pinterest-link").value ="https://in.pinterest.com/";
				document.getElementById("digg-link").value ="https://digg.com/";
				document.getElementById("youtube-link").value ="https://youtube.com/";
				document.getElementById("flickr-link").value ="https://flickr.com/";
				document.getElementById("tumblr-link").value ="https://tumblr.com/";
				document.getElementById("skype-link").value ="https://skype.com/";
				document.getElementById("instagram-link").value ="https://instagram.com/";
				document.getElementById("telegram-link").value ="https://telegram.org/";
				document.getElementById("whatsapp-link").value ="https://www.whatsapp.com/";
				// Save message box open
				jQuery(".dialog-button6").click();
				// Function to close message box
				setTimeout(function(){
					jQuery("#dialog-close-button6").click();
				}, 1000);
			}
		});
	}
}

jQuery(document).ready(function(){
	//Enable Social Icon
	var enable_social_icon = login_object.enable_social_icon;
	if (enable_social_icon != "") {
		jQuery("#enable_social_icon").val(enable_social_icon);
	}else {
		enable_social_icon = "";
	}
	
});
