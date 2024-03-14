(function($){
jQuery(document).ready(function(){
    
    var init = function(){
        
        if(window.location.hash){

            var hash = window.location.hash.substring(1);
            if(hash.search('ufw') == -1){
                return;
            }

            $(window).on('load', function(){
                var $block_editor_wrap = $('.blocks-widgets-container');

                if($block_editor_wrap.length){
                    setTimeout(function(){
                        $widget_box = $('[data-widget-area-id="' + hash + '"]');
                        if($widget_box.length){
                            $block_wrap = $widget_box.closest('div[data-block]');
                            $block_wrap.addClass('ufw_widget_highlight');
                            scroll_to_box($block_wrap);
                        }
                    }, 2000);
                }else{
                    $widget_box = $('[class*="sidebar-' + hash + '"]');
                    if($widget_box.length){
                        $widget_box.addClass('ufw_widget_highlight');
                        scroll_to_box($widget_box);
                    }
                }
            });

        }

    }
    
    var scroll_to_box = function($wrap){
        $(window).scrollTop($wrap.first().offset().top - 300);
    }

    init();
    
});
})( jQuery );