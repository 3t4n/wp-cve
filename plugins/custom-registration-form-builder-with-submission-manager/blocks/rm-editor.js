jQuery(document).ready(function(){
        
        function this_init(){
            do_something();
        }

        let blockLoaded = false;
        let blockLoadedInterval = setInterval(function(){
            if(document.getElementById('rmsubmissionTrigger')){
                blockLoaded = true;
                this_init();
            }
            if(blockLoaded){
                clearInterval(blockLoadedInterval);
            }
        }, 500);
        
        function do_something(){
            // load_rm_submissions_page();
            // console.log('Hallo Gutenberg, lets do some performance');
        }

            if (jQuery('body').hasClass('block-editor-page')) {
                // If found, add the class "rm-block-page" to the top HTML tag
                jQuery('html').addClass('rm-block-page');
                
               jQuery('head').find('link#RegistrationMagic-css').remove();
            } else {
                jQuery('html').addClass('rm-has-content');
                jQuery('head').find('link#rm_blocks_custom_tabs-css').remove();
            }
        
        
     
        
    });
    
