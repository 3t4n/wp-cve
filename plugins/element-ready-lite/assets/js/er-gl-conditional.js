;(function($) {


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
        $body            = $( 'body' );
        
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

    $(window).on('elementor/frontend/init', function () {
  
        elementorFrontend.hooks.addAction( 'frontend/element_ready/section', Element_Ready_Conditional_Section.elementorSection );
      
    });
    
})(jQuery);