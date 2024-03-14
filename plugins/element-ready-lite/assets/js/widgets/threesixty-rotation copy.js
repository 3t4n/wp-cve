(function($) {

    /*------------------------------
            Image 360 Rotation
        -------------------------------*/
        var Element_Ready_ThreeSixty_Rotation = function($scope, $) {
    
            var three__sixty_rotation = $scope.find('.er-three-sixty');

            var id                    = three__sixty_rotation.attr('id');
            var height                = parseInt( three__sixty_rotation.attr('data-height') );
            var width                 = parseInt(three__sixty_rotation.attr('data-width') );
            var perrow                = parseInt( three__sixty_rotation.attr('data-perrow') );
            var count                 = parseInt( three__sixty_rotation.attr('data-count') );
            var speed                 = parseInt( three__sixty_rotation.attr('data-speed') );
            var controls              = three__sixty_rotation.attr('data-controls');
            var autoplay              = three__sixty_rotation.attr('data-autoplay');
            var toggle_play           = three__sixty_rotation.attr('data-toggle_play');
            var images_url            = three__sixty_rotation.attr('data-urls');

            const el                    = document.querySelector('#'+id + ' ' +'.er-rotate-image');
            const prev                  = document.querySelector('#'+id + ' ' +'.er-rotate-prev');
            const next                  = document.querySelector('#'+id + ' ' +'.er-rotate-next');
            const play                  = document.querySelector('#'+id + ' ' +'.er-rotate-play');

            var $configs = {
                image: 'https://s3.eu-central-1.amazonaws.com/threesixty.js/watch.jpg',
                width: width,
                height: height,
                count: count,
                perRow: perrow,
                speed: speed,
            };
         

            images_url = images_url.split('||');
            
            if(images_url.length == 1){
                $configs.image = images_url[0];
            }else if(images_url.length > 1){
                $configs.image = images_url; 
            }
           
            if(prev){
                $configs.prev = prev; 
                $configs.next = next; 
            }

            var threesixty = new ThreeSixty(el, $configs);
            if(autoplay == 'yes'){
                threesixty.play();  
            }
            if(play && toggle_play == 'yes'){
              play.addEventListener('click',function(){
                threesixty.toggle();
              });
            }
              
        }
     
        $(window).on('elementor/frontend/init', function() {
            
            elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_ThreeSixty_Rotation.default', Element_Ready_ThreeSixty_Rotation);
             
        });
    })(jQuery);