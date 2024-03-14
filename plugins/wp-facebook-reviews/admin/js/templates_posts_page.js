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
	 * $( document ).ready(function() same as
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
	 
	 //document ready
	$(function(){
	
		var prestyle = "";
		//color picker
		var myOptions = {
			// a callback to fire whenever the color changes to a valid color
			change: function(event, ui){
				var color = ui.color.toString();
				var element = event.target;
				var curid = $(element).attr('id');
				$( element ).val(color)
				//manuall change after css. hack since jquery can't access before and after elements    border-top: 30px solid #943939;
				if(curid=='wprevpro_template_misc_bgcolor1'){
					prestyle = "<style>.wprevpro_t1_DIV_2::after{ border-top: 30px solid "+color+"; }</style>";
				}
				changepreviewhtml();
			},
			// a callback to fire when the input is emptied or an invalid color
			clear: function() {}
		};
		 
		$('.my-color-field').wpColorPicker(myOptions);
		

		
		//for style preview changes.-------------
		//var starhtml = '<span class="wprevpro_star_imgs"><img src="' + adminjs_script_vars.pluginsUrl + '/public/partials/imgs/stars_5_yellow.png" alt="" >&nbsp;&nbsp;</span>';
		var starhtml = '<span class="starloc1 wprevpro_star_imgs wprevpro_star_imgsloc1"><span class="svgicons svg-wprsp-star"></span><span class="svgicons svg-wprsp-star"></span><span class="svgicons svg-wprsp-star"></span><span class="svgicons svg-wprsp-star"></span><span class="svgicons svg-wprsp-star"></span></span>';
		var sampltext = 'This is a sample review. Hands down the best experience we have had in the southeast! Awesome accommodations, great staff. We will gladly drive four hours for this gem!';
		var datehtml = '<span id="wprev_showdate">'+adminjs_script_vars.sampledate+'</span>';
		var imagehref = adminjs_script_vars.pluginsUrl + '/admin/partials/sample_avatar.jpg';
		var imagehrefmystery = adminjs_script_vars.pluginsUrl + '/admin/partials/fb_profile.jpg';
		var iconhref = adminjs_script_vars.pluginsUrl + '/admin/partials/facebook_small_icon.png';
		var avatarimg = imagehref;
		var verified1 = '<span class="verifiedloc1 wprevpro_verified_svg wprevtooltip" data-wprevtooltip="Verified on Facebook"><span class="svgicons svg-wprsp-verified"></span></span>';
		
		var style1html ='<div class="wprevpro_t1_outer_div w3_wprs-row-padding">	\
							<div class="wprevpro_t1_DIV_1 w3_wprs-col">	\
								<div class="wprevpro_t1_DIV_2 wprev_preview_bg1 wprev_preview_bradius">	\
									<p class="wprevpro_t1_P_3 wprev_preview_tcolor1">	\
										'+starhtml+''+verified1+''+sampltext+'		</p>	\
										<img id="wprev_showicon" src="'+iconhref+'" alt="Facebook Logo" class="wprevpro_t1_site_logo siteicon">	\
								</div><span class="wprevpro_t1_A_8"><img src="'+avatarimg+'" alt="thumb" class="wprevpro_t1_IMG_4 wprev_avatar_opt"></span> <span class="wprevpro_t1_SPAN_5 wprev_preview_tcolor2">Josh W.<br>'+datehtml+' </span>	\
							</div>	\
							</div>';
		
		changepreviewhtml();
		
		//reset colors to default
		$( "#wprevpro_pre_resetbtn" ).click(function() {
			resetcolors();
		});
		function resetcolors(){
				var templatenum = $( "#wprevpro_template_style" ).val();
				//reset colors to default
				if(templatenum=='1'){
					
					$( "#wprevpro_template_misc_bradius" ).val('0');
					$( "#wprevpro_template_misc_bgcolor1" ).val('#ffffff');
					$( "#wprevpro_template_misc_bgcolor2" ).val('#ffffff');
					$( "#wprevpro_template_misc_tcolor1" ).val('#777777');
					$( "#wprevpro_template_misc_tcolor2" ).val('#555555');
					prestyle="";
					//reset color picker
					$('#wprevpro_template_misc_bgcolor1').iris('color', '#ffffff');
					$('#wprevpro_template_misc_bgcolor2').iris('color', '#ffffff');
					$( "#wprevpro_template_misc_tcolor1" ).iris('color','#777777');
					$( "#wprevpro_template_misc_tcolor2" ).iris('color','#555555');
				}
		}

		
		//on template num change
		$( "#wprevpro_template_style" ).change(function() {
				//reset colors if not editing, otherwise leave alone
				if($( "#edittid" ).val()==""){
				resetcolors();
				}
				changepreviewhtml();
		});
		
		$( "#wprevpro_template_misc_showstars" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_template_misc_showdate" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_template_misc_bradius" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_template_misc_bgcolor1" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_template_misc_tcolor1" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_template_misc_showicon" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_template_misc_avataropt" ).change(function() {
				changepreviewhtml();
		});
		$( "#wprevpro_template_misc_verified" ).change(function() {
				changepreviewhtml();
		});
		//custom css change preview
		var lastValue = '';
		$("#wpfbr_template_css").on('change keyup paste mouseup', function() {
			if ($(this).val() != lastValue) {
				lastValue = $(this).val();
				changepreviewhtml();
			}
		});
		
		function changepreviewhtml(){
			var templatenum = $( "#wprevpro_template_style" ).val();
			var bradius = $( "#wprevpro_template_misc_bradius" ).val();
			var bg1 = $( "#wprevpro_template_misc_bgcolor1" ).val();
			var bg2 = $( "#wprevpro_template_misc_bgcolor2" ).val();
			var tcolor1 = $( "#wprevpro_template_misc_tcolor1" ).val();
			var tcolor2 = $( "#wprevpro_template_misc_tcolor2" ).val();
			var tcolor3 = $( "#wprevpro_template_misc_tcolor3" ).val();
			var avataropt = $( "#wprevpro_template_misc_avataropt" ).val();
			var verified = $( "#wprevpro_template_misc_verified" ).val();
			
			if($( "#wpfbr_template_css" ).val()!=""){
				var customcss = '<style>'+$( "#wpfbr_template_css" ).val()+'</style>';
				prestyle =  prestyle + customcss;
			}
			
				var temphtml;
				if(templatenum=='1'){
					$( "#wprevpro_template_preview" ).html(prestyle+style1html);
					//hide background 2 select
					$( ".wprevpre_bgcolor2" ).hide();
					$( ".wprevpre_tcolor3" ).hide();
				}
			//now hide and show things based on values in select boxes
			if($( "#wprevpro_template_misc_showstars" ).val()=="no"){
				$( ".wprevpro_star_imgs" ).hide();
			} else {
				$( ".wprevpro_star_imgs" ).show();
			}
			if($( "#wprevpro_template_misc_showdate" ).val()=="no"){
				$( "#wprev_showdate" ).hide();
			} else {
				$( "#wprev_showdate" ).show();
			}
			if($( "#wprevpro_template_misc_showicon" ).val()=="no"){
				$( "#wprev_showicon" ).hide();
			} else {
				$( "#wprev_showicon" ).show();
			}
			//set colors and bradius by changing css via jQuery     border-radius: 10px 10px 10px 10px;
			$( '.wprev_preview_bradius' ).css( "border-radius", bradius+'px' );
			$( '.wprev_preview_bg1' ).css( "background", bg1 );
			$( '.wprev_preview_bg2' ).css( "background", bg2 );
			$( '.wprev_preview_tcolor1' ).css( "color", tcolor1 );
			$( '.wprev_preview_tcolor2' ).css( "color", tcolor2 );
			
			if(avataropt=='hide'){
				//set to display none
				$( ".wprev_avatar_opt" ).hide();
			} else if(avataropt=='mystery'){
				//set img src
				$(".wprev_avatar_opt").attr("src",imagehrefmystery);
			} else if(avataropt=='init'){
				//set img src
				$(".wprev_avatar_opt").attr("src",'https://avatar.oxro.io/avatar.svg?name=J');
			}
			
			//for hiding and showing verified star in preview
			if(verified=='yes1'){
				$( ".verifiedloc1" ).show();
			} else {
				$( ".verifiedloc1" ).hide();
			}
		}

		//help button clicked
		$( "#wpfbr_helpicon_posts" ).click(function() {
		  openpopup(adminjs_script_vars.popuptitle, '<p>'+adminjs_script_vars.popupbody+'</p>', "");
		});
		//display shortcode button click wpfbr_addnewtemplate
		$( ".wpfbr_displayshortcode" ).click(function() {
			//get id and template type
			var tid = $( this ).parent().attr( "templateid" );
			var ttype = $( this ).parent().attr( "templatetype" );
			
		  if(ttype=="widget"){
			openpopup(adminjs_script_vars.popuptitle2, '<p>'+adminjs_script_vars.popupbody2+'</p>', '');
		  } else {
			openpopup(adminjs_script_vars.popuptitle3, '<p>'+adminjs_script_vars.popupbody3+' </br></br>[wprevpro_usetemplate tid="'+tid+'"]</p><p>'+adminjs_script_vars.popupbody4+' </br></br><code> do_action( \'wprev_pro_plugin_action\', '+tid+' ); </code></p>', '');
		  }
		  
		});
		
		
		//launch pop-up windows code--------
		function openpopup(title, body, body2){

			//set text
			jQuery( "#popup_titletext").html(title);
			jQuery( "#popup_bobytext1").html(body);
			jQuery( "#popup_bobytext2").html(body2);
			
			var popup = jQuery('#popup_review_list').popup({
				width: 400,
				offsetX: -100,
				offsetY: 0,
			});
			
			popup.open();
			//set height
			var bodyheight = Number(jQuery( ".popup-content").height()) + 10;
			jQuery( "#popup_review_list").height(bodyheight);

		}
		//--------------------------------
		//get the url parameter-----------
		function getParameterByName(name, url) {
			if (!url) {
			  url = window.location.href;
			}
			name = name.replace(/[\[\]]/g, "\\$&");
			var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
				results = regex.exec(url);
			if (!results) return null;
			if (!results[2]) return '';
			return decodeURIComponent(results[2].replace(/\+/g, " "));
		}
		//---------------------------------
		
		//hide or show new template form ----------
		var checkedittemplate = getParameterByName('taction'); // "lorem"
		if(checkedittemplate=="edit"){
			jQuery("#wpfbr_new_template").show("slow");
			checkwidgetradio();
		} else {
			jQuery("#wpfbr_new_template").hide();
		}
		
		$( "#wpfbr_addnewtemplate" ).click(function() {
		  jQuery("#wpfbr_new_template").show("slow");
		});	
		$( "#wpfbr_addnewtemplate_cancel" ).click(function() {
		  jQuery("#wpfbr_new_template").hide("slow");
		  //reload page without taction and tid
		  setTimeout(function(){ 
			window.location.href = "?page=wpfb-templates_posts"; 
		  }, 500);
		  
		});	
		
		//-------------------------------
		
		//form validation
		$("#newtemplateform").submit(function(){   
			if(jQuery( "#wpfbr_template_title").val()==""){
				alert("Please enter a title.");
				$( "#wpfbr_template_title" ).focus();
				return false;
			} else if(jQuery( "#wpfbr_t_display_num_total").val()<1){
				alert("Please enter a 1 or greater.");
				$( "#wpfbr_t_display_num_total" ).focus();
				return false;
			} else {
			return true;
			}

		});
		
		//widget radio clicked
		$('input[type=radio][name=wpfbr_template_type]').change(function() {
			checkwidgetradio();
		});
		
		//check widget radio----------------------
		function checkwidgetradio() {
			var widgetvalue = $("input[name=wpfbr_template_type]:checked").val();
			if (widgetvalue == 'widget') {
				//change how many per a row to 1
				$('#wpfbr_t_display_num').val("1");
				$('#wpfbr_t_display_num').hide();
				$('#wpfbr_t_display_num').prev().hide();
				//force hide arrows and do not allow horizontal scroll on slideshow
				//$('input:radio[name=wpfbr_sliderdirection]').val(['vertical']);
				//$('input[id=wpfbr_sliderdirection1-radio]').attr("disabled",true);
				$('input:radio[name=wpfbr_sliderarrows]').val(['no']);
				$('input[id=wpfbr_sliderarrows1-radio]').attr("disabled",true);
			}
			else if (widgetvalue == 'post') {
				//alert("post type");
				if($('#edittid').val()==""){
				$('#wpfbr_t_display_num').val("3");
				}
				$('#wpfbr_t_display_num').show();
				$('#wpfbr_t_display_num').prev().show();
				$('input[id=wpfbr_sliderdirection1-radio]').attr("disabled",false);
				$('input[id=wpfbr_sliderarrows1-radio]').attr("disabled",false);
			}
		}
		
		//simple tooltip for added elements and mobile devices
		$(".wprevpro_t1_outer_div").on('mouseenter touchstart', '.wprevtooltip', function(e) {
			var titleText = $(this).attr('data-wprevtooltip');
			$(this).data('tiptext', titleText).removeAttr('data-wprevtooltip');
			$('<p class="wprevpro_tooltip"></p>').text(titleText).appendTo('body').css('top', (e.pageY - 15) + 'px').css('left', (e.pageX + 10) + 'px').fadeIn('slow');
		});
		$(".wprevpro_t1_outer_div").on('mouseleave touchend', '.wprevtooltip', function(e) {
			$(this).attr('data-wprevtooltip', $(this).data('tiptext'));
			$('.wprevpro_tooltip').remove();
		});
		$(".wprevpro_t1_outer_div").on('mousemove', '.wprevtooltip', function(e) {
			$('.wprevpro_tooltip').css('top', (e.pageY - 15) + 'px').css('left', (e.pageX + 10) + 'px');
		});
		
	});

})( jQuery );