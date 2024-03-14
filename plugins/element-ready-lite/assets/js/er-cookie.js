;(function ($) {
   
    var element_ready_single_callable_cookie = false;
    var Element_Ready_Cookie_Modules = function( $scope ){

       if(!element_ready_single_callable_cookie){
        element_ready_single_callable_cookie = true;
        if(undefined !== window.elementor ){
            elementor.settings.page.addChangeCallback( 'eready_cookie_consent_enable', eready_cookie_consent_enable );
            elementor.settings.page.addChangeCallback( 'eready_cookie_consent_title', eready_cookie_consent_title );
            elementor.settings.page.addChangeCallback( 'eready_cookie_consent_message', eready_cookie_consent_message );
        }
        element_ready_deleteCookies();
       
        if( element_ready_cookie_consent.enable =='yes' ){
            $cookie_obj = {
        
                onAccept:function(){
                   
                    element_ready_setCookie(0);
                },

                moreInfoLabel: element_ready_cookie_consent.more_info_lavel,
                acceptBtnLabel: element_ready_cookie_consent.accept_cookie_lavel,
                advancedBtnLabel: element_ready_cookie_consent.advanced_cookie_lavel,
                expires: element_ready_cookie_consent.expire,
                link: element_ready_cookie_consent.more_info_link.url,
                delay: parseInt(element_ready_cookie_consent.delay),
                uncheckBoxes: element_ready_cookie_consent.cookie_unchecked=='yes'?true:false,
                element_ready_cookie_consent: element_ready_cookie_consent,
                title: element_ready_cookie_consent.title || "Cookies & Privacy",
                message: element_ready_cookie_consent.message || "This website uses cookies to ensure you get the best experience on our website."
        
            };
            if( !element_ready_getCookie('element_ready_global') ){
 
                $('body').ihavecookies($cookie_obj);
        
            }
        }
       }
    };


    $(window).on('elementor/frontend/init', function () {
   
        if(typeof element_ready_cookie_consent !== 'undefined'){
            elementorFrontend.hooks.addAction( 'frontend/element_ready/global', Element_Ready_Cookie_Modules);
        }
         
    });
   
    function eready_cookie_consent_title( newValue ) {
        $('#gdpr-cookie-message > h4').html(newValue);
         
        
         elementor.saver.update( {
                onSuccess: function() {
                      
                    element_ready_cookie_consent.title = newValue;
                    elementor.reloadPreview();

                    elementor.once( 'preview:loaded', function() {
                        
                        elementor.getPanelView().setPage( 'page_settings' );
                    } );
                }
            } );
       
    }
    function eready_cookie_consent_message( newValue ) {
        $('#gdpr-cookie-message > p').html(newValue);
    }

    function eready_cookie_consent_enable( newValue ) {
        $cookie__obj = {
        
            onAccept:function(){
               
                element_ready_setCookie(0);
            },
            moreInfoLabel: element_ready_cookie_consent.more_info_lavel,
            acceptBtnLabel: element_ready_cookie_consent.accept_cookie_lavel,
            advancedBtnLabel: element_ready_cookie_consent.advanced_cookie_lavel,
            expires: element_ready_cookie_consent.expire,
            link: element_ready_cookie_consent.more_info_link.url,
            delay: parseInt(element_ready_cookie_consent.delay),
            uncheckBoxes: element_ready_cookie_consent.cookie_unchecked=='yes'?true:false,
            element_ready_cookie_consent: element_ready_cookie_consent,
            title: element_ready_cookie_consent.title || "Cookies & Privacy",
            message: element_ready_cookie_consent.message || "This website uses cookies to ensure you get the best experience on our website."
    
        };
        if(newValue =='yes'){
            $('body').ihavecookies($cookie__obj);
        }
        elementor.reloadPreview();  
      
    }

    function element_ready_setCookie(page=false, name='element_ready_global', value='Q' ) {
        var time  = parseInt(element_ready_cookie_consent.expire);
        if (typeof(time) != 'undefined') {

            var date = new Date();
            
            if(element_ready_cookie_consent.expire_type == 'day'){
               date.setTime(date.getTime() + (time*24*60*60*1000));
            } 
            if(element_ready_cookie_consent.expire_type == 'sec'){
                date.setTime(date.getTime() + (time*1000));
            }

            if(element_ready_cookie_consent.expire_type == 'min'){
                date.setTime(date.getTime() + (time*60*1000));
            }
            if(element_ready_cookie_consent.expire_type == 'hour'){
                date.setTime(date.getTime() + (time*60*60*1000));
            }
           
            var expires = "; expires=" + date.toGMTString();
           
        }
        else {
            var expires = "";
        }
        if(page){
            document.cookie = name+"="+value+expires+"; path=/";
        }else{
            document.cookie = name+"="+value+expires+";";
        }
       
    }

    function element_ready_getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i <ca.length; i++) {
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

    function element_ready_deleteCookies() { 
        document.cookie.split(";").forEach(function(c) { document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/"); });
    }
    

})(jQuery);