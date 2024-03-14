;(function($) {
    
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
            $body     = $( 'body' );
         

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

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', Element_Ready_Global_Widget );
    });
})(jQuery);