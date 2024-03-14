jQuery(document).ready(function ($){
	
    jQuery('.tooltip').tooltipster({
        animation: 'fade',
        delay: 200,
        theme: 'light',
        maxWidth: 300
    });	
					
	jQuery('.legal_form_first_step').hide();

	jQuery('.legal_form_opt2').hide();

	jQuery('.legalform_inforulecontainer').hide();

	jQuery('.legalform_inforulecontaineryes').hide();

	jQuery('.supervisoryauthoritycontainer').hide();

	jQuery('.registerentry').hide();

	jQuery('.needconsescontent').hide();

	jQuery('.legal_form_needconsenscontainer').hide();

	jQuery('.legal_form_nocompany').hide();

	jQuery('.legal_form_jornal').hide();

	jQuery('.legal_form_jornal_container').hide();

	jQuery('.owntextsimprint_container').hide();

	jQuery('input[name=legalform_needconsens]').on('change', function() {
		
		var value = this.value;
		
		if (value == "yes") {
			
			jQuery('.supervisoryauthoritycontainer').show(300);
			
			jQuery('.registerentry').show(300);
			
		} else {
			
			jQuery('.supervisoryauthoritycontainer').hide(300);
			
			jQuery('.registerentry').show(300);
			
		}
		
	});
		
	jQuery('.dsdvo_legalform_inforule').on('change', function() {
		
		var value = this.value;
		
		if (value == 1 || value == 0) {
			
			jQuery('.legalform_inforulecontainer').show(300);
			
			jQuery('.legalform_inforulecontaineryes').hide(300);
			
		} else {
			
			jQuery('.legalform_inforulecontaineryes').show(300);
			
			jQuery('.legalform_inforulecontainer').show(300);
			
		}
		
	});

   jQuery('input[name=legalform_journalist]').on('change', function() {
	   
		var value = this.value;
		
		if (value == "yes") {
			
			jQuery('.legal_form_jornal_container').show(300);
			
		} else {
			
			jQuery('.legal_form_jornal_container').hide(300);
			
		}
		
   });
   
   jQuery('input[name=owntextsimprint]').on('change', function() {
	   
		var value = this.value;
		
		if (value == "yes") {
			
			jQuery('.owntextsimprint_container').show(300);
			
		} else {
			
			jQuery('.owntextsimprint_container').hide(300);
			
		}
		
   }); 
   
   jQuery('input[name=legalform_needregister]').on('change', function() {
	   
		var value = this.value;
		
		if (value == "yes") {
			
			jQuery('.needconsescontent').show(300);
			
			jQuery('.legal_form_opt2').show(300);
			
		} else {
			
			jQuery('.needconsescontent').hide(300);
			
			jQuery('.legal_form_opt2').show(300);
			
		}
		
   });   
   
   jQuery('.dsdvo_legalform').on('change', function() {
	   
		  var value = this.value;
		  
		  if(value == 1 || value == 2 || value == 6 || value == 7 || value == 8 || value == 9 || value == 10 || value == 11  || value == 12 || value == 13  || value == 18 || value == 19) {
				
				jQuery('.legal_form_needconsenscontainer').show(300);
				
				
				jQuery('.legal_form_opt2').hide(300);
				
				jQuery('.legal_form_nocompany').show(300);
				
		  } else if (value == 3 || value == 4 || value == 5 || value == 14 || value == 15 || value == 16 || value == 17) {
				
				jQuery('.legal_form_opt2').show(300);
				
				jQuery('.legal_form_needconsenscontainer').show(300);
				
				jQuery('.legal_form_nocompany').show(300);
				
		  }
		  
		jQuery('.legal_form_jornal').show(300);
		  
	});

	if(jQuery( 'input[name="owntextsimprint"]:checked' ).val()) {
		
		var value = jQuery( 'input[name="owntextsimprint"]:checked' ).val();
		
		if (value == "yes") {
			
			jQuery('.owntextsimprint_container').show(300);
			
		} else {
			
			jQuery('.owntextsimprint_container').hide(300);
			
		}	   
		
   }
   
	if(jQuery( 'input[name="legalform_journalist"]:checked' ).val()) {
		
		var value = jQuery( 'input[name="legalform_journalist"]:checked' ).val();
		
		if (value == "yes") {
			
			jQuery('.legal_form_jornal_container').show(300);
			
		} else {
			
			jQuery('.legal_form_jornal_container').hide(300);
			
		}	   
   }   

	if(jQuery( ".dsdvo_legalform option:selected" ).val()) {
		
		var value = jQuery( ".dsdvo_legalform option:selected" ).val();
		
			  if(value == 1 || value == 2 || value == 6 || value == 7 || value == 8 || value == 9 || value == 10 || value == 11  || value == 12 || value == 13  || value == 18 || value == 19) {
					
				jQuery('.legal_form_needconsenscontainer').show(300);
					
				jQuery('.legal_form_opt2').hide(300);
					
				jQuery('.legal_form_nocompany').show(300);
				
			  } else if (value == 3 || value == 4 || value == 5 || value == 14 || value == 15 || value == 16 || value == 17) {
				
				jQuery('.legal_form_opt2').show(300);
				
				jQuery('.legal_form_needconsenscontainer').show(300);
				
				jQuery('.legal_form_nocompany').show(300);
					
			  }
			  
			  jQuery('.legal_form_jornal').show(300);
			  
	}

	if(jQuery( ".dsdvo_legalform_inforule option:selected" ).val()) {
		
		var value = jQuery( ".dsdvo_legalform_inforule option:selected" ).val();
		
			if (value == 1) {
				
				jQuery('.legalform_inforulecontainer').show(300);
				
				jQuery('.legalform_inforulecontaineryes').hide(300);
				
			} else {
				
				jQuery('.legalform_inforulecontaineryes').show(300);
				
				jQuery('.legalform_inforulecontainer').show(300);
				
			}
			
	}

	if(jQuery('input[name="legalform_needconsens"]:checked').val()) {
		
			var value = jQuery('input[name="legalform_needconsens"]:checked').val();
			
			if (value == "yes") {
				
				jQuery('.supervisoryauthoritycontainer').show(300);
				
				jQuery('.registerentry').show(300);
				
			} else {
				
				jQuery('.supervisoryauthoritycontainer').hide(300);
				
				jQuery('.registerentry').show(300);
				
			}	
		
	}

	if(jQuery('input[name="legalform_needregister"]:checked').val()) {
		
			var value = jQuery('input[name="legalform_needregister"]:checked').val();
			
			if (value == "yes") {
				
				jQuery('.needconsescontent').show(300);
				
				jQuery('.legal_form_opt2').show(300);
				
			} else {
				
				jQuery('.needconsescontent').hide(300);
				
				jQuery('.legal_form_opt2').show(300);
				
			}
		
	}
					
	jQuery(".dsgvoaio_ga_type").click(function(event) {
		
		var value = jQuery(this).attr("data-value");
		
		if(value)  {
			
			if (value == 'manual'){
										
				jQuery(".dsgvoaio_ga_type_manual").show(300);
										
				jQuery(".dsgvoaio_ga_type_monsterinsights").hide(300);
										
				jQuery(".dsgvoaio_ga_type_analytify").hide(300);
										
			} else if (value == 'monterinsights'){
										
				jQuery(".dsgvoaio_ga_type_monsterinsights").show(300);
										
				jQuery(".dsgvoaio_ga_type_manual").hide(300);
										
				jQuery(".dsgvoaio_ga_type_analytify").hide(300);
										
			} else if (value == 'analytify'){
										
				jQuery(".dsgvoaio_ga_type_analytify").show(300);
										
				jQuery(".dsgvoaio_ga_type_manual").hide(300);
										
				jQuery(".dsgvoaio_ga_type_monsterinsights").hide(300);
										
			}
									
		} 
								
	});	
					
	jQuery(".services_content").click(function(event) {
		
		event.preventDefault();
		
		var tab = jQuery(this).attr("data-tab");
		
		if(tab)  {
			
			if ( jQuery( ".content_"+tab ).hasClass( "dsgvoaio_hide" )){

				jQuery(".content_"+tab).show(300);
				
				jQuery( ".content_"+tab ).removeClass( "dsgvoaio_hide" );
				
				jQuery( this ).addClass( "dsgvoaio_toggled" );
				
			} else{
				jQuery(".content_"+tab).hide(300);
				
				jQuery( ".content_"+tab ).addClass( "dsgvoaio_hide" );
				
				jQuery( this ).removeClass( "dsgvoaio_toggled" );
				
			}
									
		} 
								
	});			
						
	var allPanels = jQuery('#dsgvooptions > .dsgvooptionsinner').hide();
					  
	var allPanelsTog = jQuery('#dsgvooptions > .dsgvoheader a');
						
	jQuery('#dsgvooptions > h2.dsgvoheader > a').click(function() {
						  
		var state = jQuery(this).parent().next().css('display');
						
		if (state == "none") {
			
			allPanels.slideUp();
			
			allPanelsTog.removeClass('dsgvoaio_toggled');
			
			jQuery(this).parent().next().slideDown();
			
			jQuery(this).addClass('dsgvoaio_toggled');
			
			var offset = jQuery(this).offset();
			
			var posY = offset.top - jQuery(window).scrollTop();
			
			jQuery("body, html").animate({ scrollTop: posY-50 }, 600);
			
		} else {
			
			allPanels.slideUp();
			
			allPanelsTog.removeClass('dsgvoaio_toggled');
			
		}
						
		return false;
						
	});

	jQuery(":checkbox").change(function () {
				
		if (this.name == "show_policy") {
			
			if(jQuery(this).is(":checked"))  {  
			
				jQuery(".showonnoticeon").show(300);
			
			} else {
				
				jQuery(".showonnoticeon").hide(300);
				
			}
			
		}		

		if (this.name == "show_outgoing_notice") {
			
			if(jQuery(this).is(":checked"))  { 
			
				jQuery(".outgoingnoticewrap").show(300);
				
			} else {
				
				jQuery(".outgoingnoticewrap").hide(300);
				
			}
			
		}					
				
		if (this.name == "use_facebookcomments") {
			
			if(jQuery(this).is(":checked"))  {
				
				jQuery(".facebookcommentswrap").show(300);
				
			} else {
				
				jQuery(".facebookcommentswrap").hide(300);
				
			}
			
		}
							
		if (this.name == "show_servicecontrol") {
			
			if(jQuery(this).is(":checked"))    {
				
				jQuery(".servicecontrolwrap").show(300);
				
			} else {
				
				jQuery(".servicecontrolwrap").hide(300);
				
			}
		}								
									
		if (this.name == "show_rejectbtn") {
			
			if(jQuery(this).is(":checked"))    {
				
				jQuery(".rejectbtnwrap").show(300);
				
			} else {
				
				jQuery(".rejectbtnwrap").hide(300);	
				
			}
			
		}	
		
		if (this.name == "use_facebooklike") {
			
			if(jQuery(this).is(":checked"))  {
				
				jQuery(".facebooklikewrap").show(300);
			
			} else {
			
				jQuery(".facebooklikewrap").hide(300);
			
			}
			
		}
		
		if (this.name == "use_fbpixel") {
			
			if(jQuery(this).is(":checked"))    {
				
				jQuery(".fbpixelwrap").show(300);
				
				jQuery('.fbpixelid').prop('required',true);
				
			} else {
				
				jQuery(".fbpixelwrap").hide(300);
				
				jQuery('.fbpixelid').prop('required',false);
				
			}
			
		}
		
		if (this.name == "use_ga") {
			
			if(jQuery(this).is(":checked"))  {
				
				jQuery(".gawrap").show(300);
				
				jQuery('.gaid').prop('required',true);
				
			} else {
				
				jQuery(".gawrap").hide(300);
				
				jQuery('.gaid').prop('required',false);
				
			}
			
		}

		if (this.name == "use_piwik") {
			
			if(jQuery(this).is(":checked"))  {
				
				jQuery(".piwikwrap").show(300);
				
				jQuery('.piwik_host').prop('required',true);
				
				jQuery('.piwik_siteid').prop('required',true);
				
			} else {
				
				jQuery(".piwikwrap").hide(300);
				
				jQuery('.piwik_host').prop('required',false);
				
				jQuery('.piwik_siteid').prop('required',false);
				
			}
			
		}
							
							if (this.name == "use_gtagmanager") {
								if(jQuery(this).is(":checked"))  {
									jQuery(".gtagmanagerwrap").show(300);
									jQuery('.gtagmanagerid').prop('required',true);
								} else {
									jQuery(".gtagmanagerwrap").hide(300);
									jQuery('.gtagmanagerid').prop('required',false);
								}
							}	

							if (this.name == "use_shareaholic") {
								if(jQuery(this).is(":checked"))  {
									jQuery(".shareaholicwrap").show(300);
									jQuery('.shareaholicsiteid').prop('required',true);
								} else {
									jQuery(".shareaholicwrap").hide(300);
									jQuery('.shareaholicsiteid').prop('required',false);
								}
							}		

							if (this.name == "blog_agb") {
								if(jQuery(this).is(":checked"))  {
									jQuery(".dsgvoaio_blog_policy_wrap").show(300);
								} else {
									jQuery(".dsgvoaio_blog_policy_wrap").hide(300);
								}
							}								
							
							if (this.name == "use_vgwort") {
								if(jQuery(this).is(":checked"))  {
									jQuery(".vgwortwrap").show(300);
								} else {
									jQuery(".vgwortwrap").hide(300);
								}
							}	

							if (this.name == "use_koko") {
								if(jQuery(this).is(":checked"))  {
									jQuery(".kokowrap").show(300);
								} else {
									jQuery(".kokowrap").hide(300);
								}
							}							
							
							if (this.name == "use_twitter") {
								if(jQuery(this).is(":checked"))    {
									jQuery(".twitterwrap").show(300);
									jQuery('.twitterusername').prop('required',true);
								} else {
									jQuery(".twitterwrap").hide(300);
									jQuery('.twitterusername').prop('required',false);
								}
							}
							if (this.name == "use_youtube") {
								if(jQuery(this).is(":checked"))    {
									jQuery(".youtubewrap").show(300);
								} else {
									jQuery(".youtubewrap").hide(300);
								}
							}
							if (this.name == "use_vimeo") {
								if (jQuery(this).is(":checked")) {
									jQuery(".vimeowrap").show(300);
								} else {
									jQuery(".vimeowrap").hide(300);
								}
							}							
							if (this.name == "use_linkedin") {
								if(jQuery(this).is(":checked"))    {
									jQuery(".linkedinwrap").show(300);
								} else {
									jQuery(".linkedinwrap").hide(300);
								}
							}
							if (this.name == "use_addthis") {
								if(jQuery(this).is(":checked"))    {
									jQuery(".addthiswrap").show(300);
									jQuery('.addthisid').prop('required',true);
								} else {
									jQuery(".addthiswrap").hide(300);
									jQuery('.addthisid').prop('required',false);
								}
							}
						});
						
							jQuery( document ).on( 'click', '.dsgvo_delete_ip_adresses', function(event) {
								event.preventDefault();
								var post_id = jQuery(this).data('id');
								var nonce = jQuery(this).attr('data-nonce')
									jQuery.ajax({
										type: "POST",
										url: ajaxurl,
										data: { action: 'dsgvo_delete_usr_ip' , param: 'st1', nonce: nonce }
									  }).done(function( msg ) {
										alert( msg.response );
									 });
							});		

							(function($) {
								
							  var allPanels = $('.dsgvoaio_changlog_accordion > dd').hide();
								
							  $('.dsgvoaio_changlog_accordion > dt > a').click(function() {
								allPanels.slideUp();
								$(this).parent().next().slideDown();
								return false;
							  });

							})(jQuery);							
						
						
						
 jQuery("#dsgvoaio_select_style").change(function() {
        if (jQuery(this).val() == 2 || jQuery(this).val() == 3){ 
            jQuery('#dsgvoaio_closebtn_wrap').show(300);   
        } else {
            jQuery('#dsgvoaio_closebtn_wrap').hide(300); 
        }
    });						


    var allPanels_layertext = jQuery('.dsgvoaio_inner_tab').hide();

    var allPanelsTogLayerText = jQuery('.load_layer_policy');

    jQuery('.load_layer_policy').click(function() {

        var state = jQuery(this).parent().next().css('display');

        if (state == "none") {
            allPanels_layertext.slideUp();
            allPanelsTogLayerText.removeClass('dsgvoaio_toggled');
            jQuery(this).parent().next().slideDown();
            jQuery(this).addClass('dsgvoaio_toggled');
        } else {
            allPanels_layertext.slideUp();
            allPanelsTogLayerText.removeClass('dsgvoaio_toggled');
        }

        return false;

    });						
						
					});	