// console.log('hello');

function Acl_show_Image() {
	var img_src= document.getElementById("top_image").value;
	jQuery("#top_img_prev").attr('src',img_src);
}
function Acl_top_img_clear() {
	document.getElementById("top_image").value ="";
}

//Set Value of Drop Down
jQuery(document).ready(function(){

	//Top Background Select
 	// var top_bg_type =  frontend_ajax_object.top_bg_type;
	if ( frontend_ajax_object.top_bg_type.length != 0 && frontend_ajax_object.top_bg_type != undefined ) {
		var top_bg_type = frontend_ajax_object.top_bg_type;
	} else {
		var top_bg_type = '' ;
	}

	jQuery("#select-background").val();
	getComboid();
	 
	//Top Background strech
	var top_cover = frontend_ajax_object.top_cover;
	if( top_cover == "yes"){
		jQuery("#div-on-strech").hide();
	 } else {
		jQuery("#div-on-strech").show();
	 }	
	
	//Top Background Repeat 
	var top_repeat = frontend_ajax_object.top_repeat;
	 if (top_repeat != ""){
	 	jQuery("#top_bg_repeat").val();
	 }

	//Top Background Position 
	var top_position = frontend_ajax_object.top_position;
	 	if(top_position != ""){ 
	 		jQuery("#top_bg_position").val();
	 	}

	//Top Background Attachment 
	var top_attachment = frontend_ajax_object.top_attachment;
	 if(top_attachment != ""){
	 	jQuery("#top_bg_attachment").val();
	 };

	//Top SlideShow No 
	 var top_attachment = frontend_ajax_object.top_slideshow_no;
	 if(top_attachment != ""){
	 	jQuery("#top_slideshow_no").val();
	 };

	//Top Slide Animation 
	 var top_attachment = frontend_ajax_object.top_bg_slider_animation;
	 if(top_attachment != ""){
	 	jQuery("#top_bg_slider_animation").val();
	 };
});

function OnChangeCheckbox (checkbox) {
    if (checkbox.checked) {		
        jQuery("#div-on-strech").hide();
    }
    else {        
		 jQuery("#div-on-strech").show();
    }
}

function getComboid() {
	var optionvalue = jQuery( "#select-background option:selected" ).val();
	jQuery(".no-background").hide();
	
	if(optionvalue== "static-background-color")
	{
		jQuery( "#div-bakground-color" ).show();
	}
	if(optionvalue== "static-background-image")
	{
		jQuery( "#div-bakground-image" ).show();
	}
	if(optionvalue== "slider-background")
	{
		jQuery( "#div-bakground-Slideshow" ).show();
	}
}

function set_slideshow(){
	number = jQuery("#top_slideshow_no").val();	
	for(i=1;i<=6;i++){
		if(i<=number){
			jQuery("#slideshow_settings_"+i).show(500);
		}
		else{
			jQuery("#slideshow_settings_"+i).hide(500);
		}
	}
}



function Custom_login_top(Action, id){		
	if(Action == "topbgSave") {
		(function() {
			var dlgtrigger1 = document.querySelector( '[data-dialog1]' ),

				somedialog1 = document.getElementById( dlgtrigger1.getAttribute( 'data-dialog1' ) ),
				// svg..
				morphEl1 = somedialog1.querySelector( '#morph-shape1' ),
				s1 = Snap( morphEl1.querySelector( 'svg' ) ),
				path = s1.select( 'path' ),
				steps = { 
					open : morphEl1.getAttribute( 'data-morph-open1' ),
					close : morphEl1.getAttribute( 'data-morph-close1' )
				},
				dlg = new DialogFx( somedialog1, {
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
			dlgtrigger1.addEventListener( 'click', dlg.toggle.bind(dlg) );
		})();
		// Top Background Type Option
		var select_bg_value = jQuery( "#select-background option:selected" ).val();
		
		//Top Background Color
		var top_background_color = jQuery("#top-background-color").val();
		
		//Top background Image URL
		var top_bg_image = jQuery("#top_image").val();
		
		//Top background Strech
		if (jQuery('#bg-strech').is(":checked")) {
		  var top_cover = "yes";
		} else {
			var top_cover = "no";
		}

		var top_bg_repeat = jQuery( "#top_bg_repeat option:selected" ).val();
		var top_bg_position = jQuery( "#top_bg_position option:selected" ).val();
		var top_bg_attachment = jQuery( "#top_bg_attachment option:selected" ).val();
		var top_slideshow_no = jQuery( "#top_slideshow_no option:selected" ).val();
		var top_bg_slider_animation = jQuery( "#top_bg_slider_animation option:selected" ).val();		
		
		// Slider image URL and Label Save
		number = jQuery("#top_slideshow_no").val();
		var a =[];
		var b =[];
		for(i=1;i<=6;i++){
			if(i<=number){				
				a[i] =document.getElementById("simages-"+i).src;
				b[i] =jQuery("#image_label-"+i).val();				  
			} else {
				a[i]= '';
				b[i] = "";
			}
		}
		/*var PostData = "Action=" + Action + "&select_bg_value=" + select_bg_value + "&top_background_color=" + top_background_color + "&top_bg_image=" + top_bg_image  + "&top_cover=" + top_cover + "&top_bg_repeat=" + top_bg_repeat + "&top_bg_position=" + top_bg_position + "&top_bg_attachment=" + top_bg_attachment + "&top_slideshow_no=" + top_slideshow_no + "&top_bg_slider_animation=" + top_bg_slider_animation + "&Slidshow_image_1=" + a[1] + "&Slidshow_image_2=" + a[2] + "&Slidshow_image_3=" + a[3] + "&Slidshow_image_4=" + a[4] + "&Slidshow_image_5=" + a[5] + "&Slidshow_image_6=" + a[6] + "&image_label_1=" + b[1] + "&image_label_2=" + b[2] + "&image_label_3=" + b[3] + "&image_label_4=" + b[4] + "&image_label_5=" + b[5] + "&image_label_6=" + b[6];*/
		jQuery.ajax({
			dataType : 'html',
			type: 'POST',
			url : location.href,
			cache: false,
			data: {
				nonce_ajax: WEBLIZARADMINNONCE.adminsecurity,
				Action: Action,
				select_bg_value: select_bg_value,
				top_background_color: top_background_color,
				top_bg_image: top_bg_image,
				top_cover: top_cover,
				top_bg_repeat: top_bg_repeat,
				top_bg_position: top_bg_position,
				top_bg_attachment: top_bg_attachment,
				top_slideshow_no: top_slideshow_no,
				top_bg_slider_animation: top_bg_slider_animation,
				Slidshow_image_1: a[1],
				Slidshow_image_2: a[2],
				Slidshow_image_3: a[3],
				Slidshow_image_4: a[4],
				Slidshow_image_5: a[5],
				Slidshow_image_6: a[6],
				image_label_1: b[1],
				image_label_2: b[2],
				image_label_3: b[3],
				image_label_4: b[4],
				image_label_5: b[5],
				image_label_6: b[6],
			},
			//data : PostData,
			complete : function() { },
			success: function(data) {	
				// message box open
				jQuery(".dialog-button1").click();	
				// Function to close message box
				setTimeout(function(){
					jQuery("#dialog-close-button1").click();
				}, 1000);					
			}
		});
	}
	
	// Save Message box Close On Mouse Hover
	document.getElementById('dialog-close-button1').disabled = false;
	jQuery('#dialog-close-button1').hover(function () {
		jQuery("#dialog-close-button1").click();
		document.getElementById('dialog-close-button1').disabled = true; 
	});
	 
	// Reset Message box Close On Mouse Hover
	document.getElementById('dialog-close-button11').disabled = false;
	jQuery('#dialog-close-button11').hover(function () {
		jQuery("#dialog-close-button11").click();
		document.getElementById('dialog-close-button11').disabled = true; 
	});
	 
	if(Action == "topbgReset") {
		(function() {
			var dlgtrigger1 = document.querySelector( '[data-dialog11]' ),
				somedialog1 = document.getElementById( dlgtrigger1.getAttribute( 'data-dialog11' ) ),
				// svg..
				morphEl1 = somedialog1.querySelector( '#morph-shape1' ),
				s1 = Snap( morphEl1.querySelector( 'svg' ) ),
				path = s1.select( 'path' ),
				steps = { 
					open : morphEl1.getAttribute( 'data-morph-open1' ),
					close : morphEl1.getAttribute( 'data-morph-close1' )
				},
				dlg = new DialogFx( somedialog1, {
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
			dlgtrigger1.addEventListener( 'click', dlg.toggle.bind(dlg) );
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
				Action: Action,				
			},
			complete : function() { },
			success: function(data) {
				// Show Background Image Option
				jQuery(".no-background").hide();
				jQuery( "#div-bakground-image" ).show();

				//Top Background Select
				 jQuery("#select-background").val('static-background-image');

				//Top Background Repeat 
				 jQuery("#top_bg_repeat").val('repeat');

				//Top Background Position 
				 jQuery("#top_bg_position").val('left top');

				//Top Background Attachment 
				 jQuery("#top_bg_attachment").val('fixed');

				//Top SlideShow No 
				 jQuery("#top_slideshow_no").val('6');

				//Top Slider Animation 
				 jQuery("#top_bg_slider_animation").val('slider-style1');

				// Top Background Image
				document.getElementById("top_image").value = "/images/3d-background.jpg";

				// Top Background Color
				jQuery("#td-top-background-color a.wp-color-result").closest("a").css({"background-color": "#f9fad2"});

				//hide Image slider
				number = jQuery("#top_slideshow_no").val();

				for(i=1;i<=6;i++){
					if(i<=number){
						jQuery("#slideshow_settings_"+i).show();
					} else {
						jQuery("#slideshow_settings_"+i).hide();
					}
				}
				//set default value to 	Image slider
				for(i=1;i<=6;i++){
					jQuery("#simages-"+i).attr('src','/images/rpg-default.jpg' );
				}
				jQuery(".dialog-button11").click();
				// Function to close message box
				setTimeout(function(){
					jQuery("#dialog-close-button11").click();
				}, 1000);
			}
		});
	}
}		