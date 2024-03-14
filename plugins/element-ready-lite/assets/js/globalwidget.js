(function ($) {
   
    // conditional section

   
    
    var Element_Ready_Global_Widget = function( $scope, $ ){
        
        var $target  = $scope,
            instance = null,
            editMode = Boolean( elementorFrontend.isEditMode() );
            instance = new Element_Ready_Widget_Plugin( $target );
            // run main funcionality
            instance.init(instance);

    };

    Element_Ready_Widget_Plugin = function( $target ){

       
        var self      = this,
            sectionId = $target.data('id'),
            settings  = false,
            editMode  = Boolean( elementorFrontend.isEditMode() ),
            $window   = $( window ),
            $body     = $( 'body' ),
            platform  = navigator.platform;

        /**
        * Init
        */
        self.init = function(){
            if($target.data('tooltip_data') !='undefined' && $target.data('tooltip_data')){
                self.tooltip_service( $target );
            }            
            return false;
        };

        self.tooltip_service = function( $target ){
            
            let tooltip               = $target.data('tooltip_data');
            var enable_tooltip        = false;
            enable_tooltip = tooltip['enable_tooltip']  == 'yes' ? true : false;
            var default_open          = tooltip['default_open']  == 'yes' ? true : false;
            var tooltip_position      = tooltip['tooltip_position'] ? tooltip['tooltip_position'] : 'top';
            var tooltip_target        = tooltip['tooltip_target'] ? tooltip['tooltip_target'] : 'element';
            var tooltip_enable_title  = tooltip['tooltip_enable_title']  == 'yes' ? true : false;
            var tooltip_title         = tooltip['tooltip_title'] ? tooltip['tooltip_title'] : '';
            var tooltip_content       = tooltip['tooltip_content'] ? tooltip['tooltip_content'] : '';
            var tooltip_behavior      = tooltip['tooltip_behavior'] ? tooltip['tooltip_behavior'] : 'hide';
            var tooltip_cache         = tooltip['tooltip_cache']  == 'yes' ? true : false;
            var tooltip_close_btn     = tooltip['tooltip_close_btn']  == 'yes' ? true : false;
            var tooltip_hide_false    = tooltip['tooltip_hide_false']  == 'yes' ? true : false;
            var tooltip_skin          = tooltip['tooltip_skin'] ? tooltip['tooltip_skin'] : 'top';
            var tooltip_detach        = tooltip['tooltip_detach']  == 'yes' ? true : false;
            var tooltip_fadein_dealy  = tooltip['tooltip_fadein_dealy'] ? parseInt( tooltip['tooltip_fadein_dealy'] ) : 200;
            var tooltip_fadeout_dealy = tooltip['tooltip_fadeout_dealy'] ? parseInt( tooltip['tooltip_fadeout_dealy'] ) : 200;
            var hide_on_outside_click = tooltip['hide_on_outside_click']  == 'yes' ? true : false;
            var tooltip_max_width     = tooltip['tooltip_max_width'] ? parseInt( tooltip['tooltip_max_width'] ) : 300;
            
            if(enable_tooltip){
               
                $target.find('.elementor-widget-container').first().addClass('er-widget-tooltip-enable');
            }

            var activation_class = $target.find('.er-widget-tooltip-enable');
            var options = {
                title             : tooltip_title,
                behavior          : tooltip_behavior,
                cache             : tooltip_cache,
                close             : tooltip_close_btn,
                detach            : tooltip_detach,
                fadeIn            : tooltip_fadein_dealy,
                fadeOut           : tooltip_fadeout_dealy,
                position          : tooltip_position,
                skin              : tooltip_skin,
                target            : tooltip_target,
                hideOnClickOutside: hide_on_outside_click,
                maxWidth          : tooltip_max_width
            };

            if( tooltip_close_btn ){
                options.hideOn = false;
              
            }

            if( enable_tooltip ){
                Tipped.create( activation_class, tooltip_content, options );
                if(default_open){
                    Tipped.show( activation_class );
                }
                
            }
           
        };

        self.getSettings = function(sectionId, key){
            var editorElements      = null,
            sectionData             = {};
         
            if ( ! editMode ) {
                sectionId = 'section' + sectionId;

                if(!window.element_ready_tooltip_data || ! window.element_ready_tooltip_data.hasOwnProperty( sectionId )){
                    return false;
                }

                if(! window.element_ready_tooltip_data[ sectionId ].hasOwnProperty( key )){
                    return false;
                }

                return window.element_ready_tooltip_data[ sectionId ][key];
            }else{
                 
                if ( ! window.elementor.hasOwnProperty( 'elements' ) ) {
                    return false;
                }
                editorElements = window.elementor.elements;
                
                if ( ! editorElements.models ) {
                    return false;
                }
                $.each( editorElements.models, function( index, obj ) {
                    if ( sectionId == obj.id ) {
                        sectionData = obj.attributes.settings.attributes;
                    }
                });

                if ( ! sectionData.hasOwnProperty( key ) ) {
                    return false;
                }
            }

            return sectionData[ key ];
        };
    };
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

    var Element_Ready_Conditional_Section = {

        elementorSection: function( $scope ) {
            var $element_ready_target   = $scope,
                instance  = null,
                editMode  = Boolean( elementorFrontend.isEditMode() );
                instance = new Element_Ready_Conditional_Plugin( $element_ready_target );
                // run main functionality
               
                instance.init(instance);
        },
    };

    Element_Ready_Conditional_Plugin = function( $target ) {

        var self         = this,
        sectionId        = $target.data('id'),
        settings         = false,
        editMode         = Boolean( elementorFrontend.isEditMode() ),
        $window          = $( window ),
        $body            = $( 'body' ),
        platform         = navigator.platform;
        /**
         * Init
         */
        self.init = function() {
           
            self.element_ready_conditional( sectionId );
             
            return false;
        };

        
        self.element_ready_conditional = function (sectionId){
          
            let element_ready_section_condition   = false;
            let element_ready_hide   = false;
       
            element_ready_section_condition = self.getSettings( sectionId, 'element_ready_pro_conditional_section_btn_enable' );
            element_ready_hide = Boolean( self.getSettings( sectionId, 'element_ready_pro_conditional_section_show' ) );
            
            if(element_ready_section_condition == 'yes'){
               
                $target.removeClass('element-ready-pro-conditional-content-hide-if'); 
               
                $target.addClass('element-ready-pro-conditional-content-container');
                if(!element_ready_hide){
                    $target.addClass('element-ready-pro-conditional-content-hide-if');
                }else{
                   
                    $target.removeClass('element-ready-pro-conditional-content-hide-if');
                }

               
         
            }else{
                $target.removeClass('element-ready-pro-conditional-content-container'); 
                $target.removeClass('element-ready-pro-conditional-content-hide-if'); 
            }

        };

        self.getSettings = function(sectionId, key){
            var editorElements      = null,
            sectionData             = {};
             

            if ( ! editMode ) {
                sectionId = 'section' + sectionId;

                if(!window.element_ready_pro_conditional_section_data || ! window.element_ready_pro_conditional_section_data.hasOwnProperty( sectionId )){
                    return false;
                }

                if(! window.element_ready_pro_conditional_section_data[ sectionId ].hasOwnProperty( key )){
                    return false;
                }

                return window.element_ready_pro_conditional_section_data[ sectionId ][key];
            }else{
                 
                if ( ! window.elementor.hasOwnProperty( 'elements' ) ) {
                    return false;
                }
                editorElements = window.elementor.elements;
                
                if ( ! editorElements.models ) {
                    return false;
                }
                $.each( editorElements.models, function( index, obj ) {
                    if ( sectionId == obj.id ) {
                        sectionData = obj.attributes.settings.attributes;
                    }
                });

                if ( ! sectionData.hasOwnProperty( key ) ) {
                    return false;
                }
            }

            return sectionData[ key ];
        };
    }

    // sticky
     
    var Element_Ready_Sticky_Menu = {

        elementorSection: function( $scope ) {
            var $element_ready_target   = $scope,
                instance  = null,
                editMode  = Boolean( elementorFrontend.isEditMode() );
                instance = new Element_ready_Sticky_Menu_Plugin( $element_ready_target );
                // run main functionality
                
                instance.init(instance);
        },
    };

    Element_ready_Sticky_Menu_Plugin = function( $target ) {

        var self         = this,
        sectionId        = $target.data('id'),
        settings         = false,
        editMode         = Boolean( elementorFrontend.isEditMode() ),
        $window          = $( window ),
        $body            = $( 'body' ),
        platform         = navigator.platform;
        /**
         * Init
         */
        self.init = function() {
           
            self.element_ready_sticky( sectionId );
            //self.element_ready_custom_css( sectionId );
            
            return false;
        };

        
        self.element_ready_sticky = function (sectionId){
          
            var element_ready_global_sticky   = false;
            var element_ready_sticky_offset   = 110;
            var element_ready_sticky_type        = null;

            element_ready_global_sticky = self.getSettings( sectionId, 'element_ready_global_sticky' );
            element_ready_sticky_type   = self.getSettings( sectionId, 'element_ready_sticky_type' );
            element_ready_sticky_offset = parseInt(self.getSettings( sectionId, 'element_ready_sticky_offset' ));
           
            //default offset
             if(element_ready_sticky_offset < 5){
                 element_ready_sticky_offset = 110;  
             }
          
            if(element_ready_global_sticky == 'yes'){

                $target.addClass('element-ready-sticky-container');

                if(element_ready_sticky_type == 'top'){
                    $target.addClass('top');
                    $target.removeClass('bottom');
                }

                if(element_ready_sticky_type == 'bottom'){
                    $target.addClass('bottom');
                    $target.removeClass('top');
                }
                if(element_ready_sticky_type == ''){
                    $target.removeClass('top');
                    $target.removeClass('bottom');
                }   
                  
                $window.on('scroll', function (event) {
                   
                    var scroll = $window.scrollTop();
                   
                    if (scroll < element_ready_sticky_offset) {
                        $target.removeClass("element-ready-sticky");
                    } else {
                        $target.addClass("element-ready-sticky");
                    }

                });
            }else{

                $target.removeClass('element-ready-sticky-container');
                
            }


        };

        self.getSettings = function(sectionId, key){
            var editorElements      = null,
            sectionData             = {};
             

            if ( ! editMode ) {
                sectionId = 'section' + sectionId;

                if(!window.element_ready_section_sticky_data || ! window.element_ready_section_sticky_data.hasOwnProperty( sectionId )){
                    return false;
                }

                if(! window.element_ready_section_sticky_data[ sectionId ].hasOwnProperty( key )){
                    return false;
                }

                return window.element_ready_section_sticky_data[ sectionId ][key];
            }else{
                 
                if ( ! window.elementor.hasOwnProperty( 'elements' ) ) {
                    return false;
                }
                editorElements = window.elementor.elements;
                
                if ( ! editorElements.models ) {
                    return false;
                }
                $.each( editorElements.models, function( index, obj ) {
                    if ( sectionId == obj.id ) {
                        sectionData = obj.attributes.settings.attributes;
                    }
                });

                if ( ! sectionData.hasOwnProperty( key ) ) {
                    return false;
                }
            }

            return sectionData[ key ];
        };
    }

    var Element_Ready_Dismissable_Section = {

        elementorSection: function( $scope ) {
            var $element_ready_target   = $scope,
                instance  = null,
                editMode  = Boolean( elementorFrontend.isEditMode() );
                instance = new Element_Ready_Dismissable__Section_Plugin( $element_ready_target );
                // run main functionality
                
                instance.init(instance);
        },
    };


    Element_Ready_Dismissable__Section_Plugin = function( $target ) {

        var self         = this,
        
        sectionId        = $target.data('id'),
        settings         = false,
        editMode         = Boolean( elementorFrontend.isEditMode() ),
        $window          = $( window ),
        $body            = $( 'body' );
        
        /**
         * Init
         */
        self.init = function() {
           
            self.element_ready_dismiss( sectionId );
            
            return false;
        };

        
        self.element_ready_dismiss = function (sectionId){
          
            var element_ready_global_dismiss                            = false;
            var element_ready_section_dissmis_type                      = 'fadeOut';
            var element_ready_section_dissmis_timeout_obj               = null;
            var element_ready_section_dissmis_timeout                   = 500;
            var element_ready_main_section__dismissabley_close_icon_obj = '';
            var is_dismissabley_close_svg_obj                           = false;
            var is_dismissabley_close_svg_url                           = '';
            var dismissabley_close_icon                                 = 'fa fa-times';
       
            element_ready_global_dismiss = self.getSettings( sectionId, 'element_ready_section_dissmis' );
            element_ready_main_section__dismissabley_close_icon_obj = self.getSettings( sectionId, 'element_ready_main_section__dismissabley_close_icon' );
            element_ready_section_dissmis_timeout_obj = self.getSettings( sectionId, 'element_ready_section_dissmis_timeout' );
            element_ready_section_dissmis_type = self.getSettings( sectionId, 'element_ready_section_dissmis_type' );
           
         
            
            //icon 
            if(element_ready_main_section__dismissabley_close_icon_obj.value !==undefined && element_ready_main_section__dismissabley_close_icon_obj.value !== null){
                dismissabley_close_icon = element_ready_main_section__dismissabley_close_icon_obj.value;
            }
            //svg
            if(element_ready_main_section__dismissabley_close_icon_obj.library !==undefined &&
                element_ready_main_section__dismissabley_close_icon_obj.library !== null &&
                element_ready_main_section__dismissabley_close_icon_obj.library == 'svg'
                ){
                is_dismissabley_close_svg_obj = true
                is_dismissabley_close_svg_url = `<img src="${element_ready_main_section__dismissabley_close_icon_obj.value.url}"/>`;
            }
            
            if(element_ready_section_dissmis_timeout_obj.size !==undefined && element_ready_section_dissmis_timeout_obj.size !== null){
                element_ready_section_dissmis_timeout = element_ready_section_dissmis_timeout_obj.size;
            }
          

            if(element_ready_global_dismiss == 'yes'){
               
                $target.addClass('element-ready-dismissable-container');
                if(is_dismissabley_close_svg_obj){
                    $target.prepend(`<div class="element-ready-section--dismissable-html">${is_dismissabley_close_svg_url}</div> `);
                }else{
 
                    $target.prepend(`<div class="element-ready-section--dismissable-html"><i class="${dismissabley_close_icon}"> </i></div> `);
                }
               
                 
                $target.on('click','.element-ready-section--dismissable-html', function (event) {
                  
                    if(element_ready_section_dissmis_type == 'slideUp'){
   
                        $target.slideUp(element_ready_section_dissmis_timeout, function() {
                            $(this).remove();
                        }); 

                    }else{

                        $target.fadeOut(element_ready_section_dissmis_timeout, function() {
                            $(this).remove();
                        }); 

                    } 
           
                });

            }
 

        };

        self.getSettings = function(sectionId, key){
            var editorElements      = null,
            sectionData             = {};
             

            if ( ! editMode ) {
                sectionId = 'section' + sectionId;

                if(!window.element_ready_section_dismiss_data || ! window.element_ready_section_dismiss_data.hasOwnProperty( sectionId )){
                    return false;
                }

                if(! window.element_ready_section_dismiss_data[ sectionId ].hasOwnProperty( key )){
                    return false;
                }

                return window.element_ready_section_dismiss_data[ sectionId ][key];
            }else{
                 
                if ( ! window.elementor.hasOwnProperty( 'elements' ) ) {
                    return false;
                }
                editorElements = window.elementor.elements;
                
                if ( ! editorElements.models ) {
                    return false;
                }
                $.each( editorElements.models, function( index, obj ) {
                    if ( sectionId == obj.id ) {
                        sectionData = obj.attributes.settings.attributes;
                    }
                });

                if ( ! sectionData.hasOwnProperty( key ) ) {
                    return false;
                }
            }

            return sectionData[ key ];
        };
    }

    Element_ready_Live_Button_Module = function( $target ) {

        var self         = this,
        sectionId        = $target.data('id'),
        settings         = false,
        editMode         = Boolean( elementorFrontend.isEditMode() ),
        $window          = $( window ),
        $body            = $( 'body' ),
        platform         = navigator.platform;
        /**
         * Init
         */
        self.init = function() {
            
            self.element_ready_live_btn( sectionId );
            //self.element_ready_custom_css( sectionId );
            
            return false;
        };

        
        self.element_ready_live_btn = function (sectionId){
            
            
            let template = wp.template( 'element-ready-live-btn' );

            let element_ready_section_live_btn = false;
            let element_ready_pro_live_link = '#';
            let element_ready_pro_live_btn_text = 'live copy';
        
            element_ready_section_live_btn = self.getSettings( sectionId, 'element_ready_pro_live_btn_enable' );
            element_ready_pro_live_btn_text = self.getSettings( sectionId, 'element_ready_pro_live_btn_text' );
            element_ready_pro_live_link = self.getSettings( sectionId, 'element_ready_pro_live_link' );
          
            
            
            if( element_ready_section_live_btn == 'yes' ){

                $target.addClass('element-ready-pro-live-btn');
              
                setTimeout(function(){

                    $target.append(template( { text: element_ready_pro_live_btn_text , link: element_ready_pro_live_link } ));
                 },
                2000
                );
               
                
            }else{

                $target.removeClass('element-ready-pro-live-btn');
                
            }


        };

        self.getSettings = function(sectionId, key){
            var editorElements      = null,
            sectionData             = {};
             

            if ( ! editMode ) {
                sectionId = 'section' + sectionId;

                if(!window.element_ready_pro_section_live_button_data || ! window.element_ready_pro_section_live_button_data.hasOwnProperty( sectionId )){
                    return false;
                }

                if(! window.element_ready_pro_section_live_button_data[ sectionId ].hasOwnProperty( key )){
                    return false;
                }

                return window.element_ready_pro_section_live_button_data[ sectionId ][key];
            }else{
                 
                if ( ! window.elementor.hasOwnProperty( 'elements' ) ) {
                    return false;
                }
                editorElements = window.elementor.elements;
                
                if ( ! editorElements.models ) {
                    return false;
                }
                $.each( editorElements.models, function( index, obj ) {
                    if ( sectionId == obj.id ) {
                        sectionData = obj.attributes.settings.attributes;
                    }
                });

                if ( ! sectionData.hasOwnProperty( key ) ) {
                    return false;
                }
            }

            return sectionData[ key ];
        };
    }

    var Element_Ready_Live_Button = {

        elementorSection: function( $scope ) {
            var $element_ready_target   = $scope,
                instance  = null,
                editMode  = Boolean( elementorFrontend.isEditMode() );
                instance = new Element_ready_Live_Button_Module( $element_ready_target );
                // run main functionality
                
                instance.init(instance);
        },
    };

    $(window).on('elementor/frontend/init', function () {

        elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', Element_Ready_Global_Widget );
        if(typeof element_ready_cookie_consent !== 'undefined'){
            elementorFrontend.hooks.addAction( 'frontend/element_ready/global', Element_Ready_Cookie_Modules);
        }
       
        elementorFrontend.hooks.addAction( 'frontend/element_ready/section', Element_Ready_Dismissable_Section.elementorSection );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/section', Element_Ready_Sticky_Menu.elementorSection );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/section', Element_Ready_Conditional_Section.elementorSection );

        if (typeof ercp !== 'undefined') {
             elementorFrontend.hooks.addAction( 'frontend/element_ready/section', Element_Ready_Live_Button.elementorSection );
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
    
    /* Live Copy */

    $(document).on('click', '.element-ready-live-btn-wrp a', function (e) {
       
        let that         = $( this );
        let button_lebel         = that.text();
        let parentTag         = that.parent().parent().get( 0 );
        let element_id        = $(parentTag).data('id');
        let element_type      = $(parentTag).data('element_type');
        let post_id           = elementorFrontend.config.post.id;
      
        var json_data = {
            'action'    : 'element_ready_fetch_live_copy_data',
            'type'      : element_type,
            'section_id': element_id,
            'post_id'   : post_id
        };

        fetch(ercp.ajaxurl, {
            method: 'POST',
             headers: new Headers({'Content-Type': 'application/x-www-form-urlencoded'}),
             body: $.param(json_data)
         })
         .then(response => response.json())
         .then((tmpl) => {

            let copiedElement = tmpl.data;
            localStorage.clear();
            xdLocalStorage.setItem( 'element-ready-ercp-element', JSON.stringify(copiedElement), function (data) {
               that.html('<span>&#10003;</span> Copied section').css({'font-style':'italic'});
               
            });

            setTimeout(function(){ 

                that.text(button_lebel).css({'font-style':'normal'});
             
            }, 
             1000
            );
  
       
        })
        .catch(function(error) {
            that.text('Copy error').css({'font-style':'italic'});
        });

    });

    if (typeof ercp !== 'undefined') {

        xdLocalStorage.init(
            {
                iframeUrl: ercp.script_url,
                initCallback: function () {  }
        } );  
        
    }
        
   

})(jQuery);