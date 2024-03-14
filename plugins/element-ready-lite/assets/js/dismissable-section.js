;(function($) {

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


    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/section', Element_Ready_Dismissable_Section.elementorSection );
    });

})(jQuery);