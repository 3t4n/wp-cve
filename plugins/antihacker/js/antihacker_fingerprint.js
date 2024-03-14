jQuery(document).ready(function($) {
            if(window.screen)
            {
                $wsize = screen.width;
            }
            else
            {
                $wsize = 0;
            }

            jQuery.ajax({
                url: ajaxurl,
                data: {
                    'action':'antihacker_grava_fingerprint',
                    'fingerprint' : $wsize
                },
                success:function(data) {
                    // This outputs the result of the ajax request
                    //console.log(data);
                },
                error: function(errorThrown){
                    //console.log(errorThrown);
                }
            });  
    
});