;(function ($) {

    // Gradient Animated Color
    
    var Element_Ready_Animated_Color = {

        elementorSection: function( $scope ) {
            var $element_ready_target   = $scope,
                instance  = null,
                editMode  = Boolean( elementorFrontend.isEditMode() );
                instance = new Element_Ready_Animated_Color_Plugin( $element_ready_target );
                instance.init(instance);
             
        },
    };

    Element_Ready_Animated_Color_Plugin = function( $target ) {

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
           
           
            self.element_ready_animated_color_run( sectionId );
            
            return false;
        };

        
        self.element_ready_animated_color_run = function (sectionId){
         
            var element_ready_gl_active = false;
            var color_list = ['red','blue','yellow'];
            var color_list = [];
            var color_obj = [];
            var gdeg   = 120;
            var speed   = 15;
            var loop   = 0;
            var background_size   = 400;

            element_ready_gl_active = self.getSettings( sectionId, 'element_ready_global_animated_gr_bg' );
            gdeg                    = self.getSettings( sectionId, 'element_ready_main_section_gradient_deg' );
            speed                   = self.getSettings( sectionId, 'element_ready_animated_background_speed' );
            background_size         = self.getSettings( sectionId, 'element_ready_animated_background_size' ) || 400;
            color_obj               = self.getSettings( sectionId, 'er_sec_gradient_color_list' );
            loop                    = self.getSettings( sectionId, 'element_ready_animated_background_loop' );
            
            if(element_ready_gl_active !== 'yes'){
              $target.removeClass('element-ready-gradient-background'); 
              return false;
            }
                      // active
            if(color_obj.length){

                color_list = [];
                for (let i = 0; i < color_obj.length; i++) {
                    
                     if(editMode){
                        if (typeof(color_obj.models[i].attributes.list_color) != "undefined"){

                            if(color_obj.models[i].attributes.list_color !=''){
                                color_list.push(color_obj.models[i].attributes.list_color);
                            }
                            
                        }
                     }else{
                        if (typeof(color_obj[i].list_color) != "undefined"){
                            color_list.push(color_obj[i].list_color);
                        }
                     }
                    
                   
                }
               
            }
               
            if(!speed){
                speed = 15;
            }
            if(background_size < 0 ){
                background_size = 400;
            }
            if(loop == 0){
                loop = 'infinite';
            }
            $target.addClass('element-ready-gradient-background');
           
            var css = `.element-ready-gradient-background[data-id="${sectionId}"] {
                background: linear-gradient(${gdeg['size']}deg, ${color_list.join()});
                animation: er_anim_gradient ${speed}s ease ${loop}; 
                background-size: 400% 400%;
               
            }`;

            head = document.head || document.getElementsByTagName('head')[0],
            style = document.createElement('style');
            style.setAttribute("id", sectionId);
            head.appendChild(style);

            style.type = 'text/css';
            if (style.styleSheet){
            style.styleSheet.cssText = css;
            } else {
            style.appendChild(document.createTextNode(css));
            }
     
        };

        self.getSettings = function(sectionId, key){
            var editorElements      = null,
            sectionData             = {};
             

            if ( ! editMode ) {
                sectionId = 'section' + sectionId;

                if(!window.element_ready_section_animated_color_data || ! window.element_ready_section_animated_color_data.hasOwnProperty( sectionId )){
                    return false;
                }

                if(! window.element_ready_section_animated_color_data[ sectionId ].hasOwnProperty( key )){
                    return false;
                }

                return window.element_ready_section_animated_color_data[ sectionId ][key];
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
    
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/section', Element_Ready_Animated_Color.elementorSection );
    });

})(jQuery);