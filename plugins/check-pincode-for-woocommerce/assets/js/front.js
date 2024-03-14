function setCookie(cname,cvalue,exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires=" + d.toGMTString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i < ca.length; i++) {
            var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

jQuery(document).ready(function() {
    
    /*cookie set in billing wordpress*/
    var Cpiw_Pincode = getCookie("Cpiw_Pincode");
    jQuery("#billing_postcode").val(Cpiw_Pincode);
    jQuery("body").on('click', '.single_add_to_cart_button.Disableclass', function() {
        alert("Please Add Valid Pincode");
        return false;
    });
    
      /* Single product */
     	jQuery("body").on('click', '.checkpincodebuttom', function() {
      		var CheckPincode = jQuery('.checkpincode').val();

            if(CheckPincode != '') {
                    jQuery('.cpiwc_maindiv_popup').append('<div class="cpiwc_spinner"><img src="'+ CpiwData.cpiw_plugin_url +'/assets/image/loading-load.gif"></div>');
                    jQuery('.cpiwc_maindiv_popup').addClass('cpiwc_loader');
          		jQuery.ajax({
                      type: "POST",
                      url: CpiwData.ajaxurl,
                      dataType: 'json',
                      data:{ 
                              action:"CPIW_CheckPincodeSingleProduct",
                              CheckPincode: CheckPincode,
                      },
                      success: function(msg){
                        jQuery(".cpiwc_spinner").remove();
                        jQuery('.cpiwc_maindiv_popup').removeClass('wczpc_loader');
                        
                        if(msg.totalrec == 1) {
                            
                          jQuery('.cpiw_inner').html(msg.avai_msg);
                          jQuery('.Cpiw_avaicode').html(msg.pincode);
                          jQuery('.cpiw_main').hide();
                          jQuery('.cpiw_inner').css('display', 'block');
                        }else{
                            jQuery('.Cpiw_avaicode').html(msg.pincode);
                          jQuery('.cpiw_main').hide();
                          jQuery('.pincode_not_availabel').css('display', 'flex');

                        }
                      	
                      }
                });
            }else{
                jQuery('.wczp_empty').css('display', 'block');

            }
      });

    jQuery("body").on('click', '.cpiwcheckbtn', function() {
        jQuery('.cpiw_main').css('display', 'flex');
        jQuery('.cpiw_inner').hide();
         jQuery('.pincode_not_availabel').hide();
    });


    jQuery("body").on('click', '.cpiwinzipsubmit', function() {
         var popup_postcode = jQuery('.cpiwopuppinzip').val();
        if(popup_postcode != '') {
            jQuery('.wczp_empty').css('display', 'none');
            jQuery('.cpiwc_maindiv_popup').append('<div class="cpiwc_spinner"><img src="'+ CpiwData.cpiw_plugin_url +'/assets/image/loading-load.gif"></div>');
            jQuery('.cpiwc_maindiv_popup').addClass('cpiwc_loader');
            jQuery.ajax({
                type: "POST",
                url: CpiwData.ajaxurl,
                dataType: 'json',
                data: { 
                        action:"CPIW_PopupCheckZipCode",
                        popup_postcode: popup_postcode,
                     },
                success: function(msg){
                    jQuery(".cpiwc_spinner").remove();
                    jQuery('.cpiwc_maindiv_popup').removeClass('wczpc_loader');
                        if(msg.totalrec == 1) {
                            
                                jQuery('.cpiw_inner').html(msg.avai_msg);
                                jQuery('.cpiw_main').hide();
                                jQuery('.cpiw_inner').css('display', 'flex');
                                jQuery('.popuppincoderesponce').html('<p>'+msg.avai_msg+'</p>');
                                setInterval(function(){ location.reload(); }, 5000);
                        }else{
                            jQuery('.Cpiw_avaicode').html(msg.popup_pincode);
                            jQuery('.cpiw_main').hide();
                            jQuery('.pincode_not_availabel').css('display', 'flex');
                            jQuery('.popuppincoderesponce').html('<p>'+CpiwData.cpiw_not_availabletext+'</p>');
                        }
                }
            });
        }else{
            jQuery('.wczp_empty').css('display', 'block');
            jQuery('.popuppincoderesponce').hide();
        }
    });

    /* checkout postcode check*/
    function CPIW_AjaxForPincodeChnageCheckout(pincode){
        if(pincode != '') {
            jQuery.ajax({
                type: "POST",
                url: CpiwData.ajaxurl,
                dataType: 'json',
                data: { 
                    action:"CPIW_OnCheckoutPincodeCheck",
                    pincode: pincode,
                },
                success: function(response) {
                    jQuery("body").trigger("update_checkout");
                }
            });
        }
    }

    jQuery("body").on('keyup', '#shipping_postcode', function() {
        if(jQuery('#ship-to-different-address-checkbox').is(':checked')==true){
            var pincode = jQuery(this).val();
            CPIW_AjaxForPincodeChnageCheckout(pincode);
        }
    });

    jQuery("body").on('keyup', '#billing_postcode', function() {
        if(jQuery('#ship-to-different-address-checkbox').is(':checked')==false){
            var pincode = jQuery(this).val();
            CPIW_AjaxForPincodeChnageCheckout(pincode);
        }
    });


    var popup_cookkie = getCookie("popup_cookkie");
      

    if (popup_cookkie != "popusetp") {
        setTimeout(function() {
            jQuery("#cpiwModal").show();
            jQuery('#cpiw_pincode_popup').show();
        }, 1000);
    }

    jQuery("body").on('click', '.close', function(e){

        e.preventDefault();
        jQuery('#cpiwModal').hide();
        jQuery('#cpiw_pincode_popup').hide();
        setCookie("popup_cookkie", "popusetp", 7);
    });

    jQuery('.cpiwbtn').click(function() {
            jQuery('.response_pin').animate(
                { deg: 360 },
                {
                    duration: 1200,
                    step: function(now) {
                    jQuery(this).css({ transform: 'rotate(' + now + 'deg)' });
                }
            }
        );
    });

    jQuery("body").on('click', '#cpiw_pincode_popup', function() {
        jQuery('#cpiwModal').hide();
        jQuery('#cpiw_pincode_popup').hide();
        setCookie("popup_cookkie", "popusetp", 7);
    });

    jQuery(document).on('keypress','#cpiwModal',function(e) {
        if(e.which == 13) {
            return false;
        }
    });
});

  

