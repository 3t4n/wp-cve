(function($) {

    var Video_Popup_Button_Script_Handle = function($scope, $) {

        var video_popup  = $scope.find('.video__popup__button').eq(0);
        var settings     = video_popup.data('value');
        var random_id    = parseInt(settings['random_id']);
        var channel_type = settings['channel_type'];
        var videoModal   = $("#video__popup__button" + random_id);
    
        videoModal.modalVideo({
            channel: channel_type
        });

      
    };

    var Element_Ready_Lite_Video_Script = function($scope, $) {
        var $img_elem_wrap = $scope.find('.elementor-custom-embed-image-overlay');
        var $img_elem_wrap_default = $scope.find('.elementor-custom-embed-image-overlay img').eq(0);
        var $img_elem_wrap_child = $scope.find('.elementor-custom-embed-image-overlay img').eq(1);
        if(!$img_elem_wrap_child.length){
          return;
        }

        $img_elem_wrap_child.hide();
        var $default_img_src    = $img_elem_wrap_default.attr('src');
        var $default_img_srcset = $img_elem_wrap_default.attr('srcset');
        var $img_src            = $img_elem_wrap_child.attr('src');
        var $img_srcset         = $img_elem_wrap_child.attr('srcset');
        if($img_srcset == undefined){
            $img_srcset = $img_src;   
        }
        
        $img_elem_wrap.on('mouseleave', function(e){
             
            $img_elem_wrap_default.fadeTo(1000,0.1, function() {
                $img_elem_wrap_default.attr('src',$default_img_src);
                $img_elem_wrap_default.attr('srcset',$default_img_srcset);
            }).fadeTo(500,1);
        });

        $img_elem_wrap.on('mouseenter', function(e){
           
            $img_elem_wrap_default.fadeTo(1000,0.1, function() {
                $img_elem_wrap_default.attr('src',$img_src);
                $img_elem_wrap_default.attr('srcset',$img_srcset);
            }).fadeTo(500,1);

        });
 

    };

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Video_Button.default', Video_Popup_Button_Script_Handle);
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_Widget_Video.default', Element_Ready_Lite_Video_Script);
       
    });

})(jQuery);