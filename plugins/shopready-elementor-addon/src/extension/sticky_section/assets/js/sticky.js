(function ($) {
   
    // Sticky Section
       
    var Shop_Ready_Sticky_Menu = {

        Sticky_Section: function( $scope ) {
            var $sr_ready_target   = $scope,
                instance  = null,
                editMode  = Boolean( elementorFrontend.isEditMode() );
                instance = new Shop_Ready_Sticky_Menu_Plugin( $sr_ready_target );
                // run main functionality
                instance.init(instance);
        },
    };

    Shop_Ready_Sticky_Menu_Plugin = function( $target ) {

        var self         = this,
        sectionId        = $target.data('id'),
        settings         = false,
        editMode         = Boolean( elementorFrontend.isEditMode() ),
        $window          = $( window );
         /**
         * Init
         */
        self.init = function() {
           
            self.shop_ready_sticky( sectionId );
            return false;
        };

        
        self.shop_ready_sticky = function (sectionId){
          
            var element_ready_global_sticky   = false;
            var element_ready_sticky_offset   = 110;
            var element_ready_sticky_type        = null;

            element_ready_global_sticky = self.getSettings( sectionId, 'shop_ready_global_sticky' );
            element_ready_sticky_type   = self.getSettings( sectionId, 'shop_ready_sticky_type' );
            element_ready_sticky_offset = parseInt(self.getSettings( sectionId, 'shop_ready_sticky_offset' ));
           
            //default offset
             if(element_ready_sticky_offset < 5){
                 element_ready_sticky_offset = 110;  
             }
          
            if(element_ready_global_sticky == 'yes'){
              
                $target.addClass('shop-ready-sticky-container');

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
                        $target.removeClass("shop-ready-sticky");
                    } else {
                        $target.addClass("shop-ready-sticky");
                    }

                });
                
            }else{

                $target.removeClass('shop-ready-sticky-container');
                
            }


        };

        self.getSettings = function(sectionId, key){
            var editorElements      = null,
            sectionData             = {};
             

            if ( ! editMode ) {
                sectionId = 'section' + sectionId;

                if(!window.shop_ready_section_sticky_data || ! window.shop_ready_section_sticky_data.hasOwnProperty( sectionId )){
                    return false;
                }

                if(! window.shop_ready_section_sticky_data[ sectionId ].hasOwnProperty( key )){
                    return false;
                }

                return window.shop_ready_section_sticky_data[ sectionId ][key];
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
        elementorFrontend.hooks.addAction( 'frontend/element_ready/section', Shop_Ready_Sticky_Menu.Sticky_Section );
     });
 
})(jQuery);