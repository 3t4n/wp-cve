(function($) {

    var Element_Ready_HotSpot_View_Widget = function($scope, $) {

        var $hotspot        = $scope.find('.er-hotspot-hotspot');
        var $hotspot__wrap  = $scope.find('.element__ready____hotspot__wrap');
        var $hotspots_label = $scope.find('.er-hotspot-hotspots-label');
        var e_type          = $hotspot__wrap.data('event');
        var er_open_all_spot = Boolean($hotspot__wrap.data('open_all'));
        $hotspot.each(function(){
	
            var $this = $(this),
                    top = $this.data('top'),
                    left = $this.data('left');
            
            $this.css({
                top: top + "%",
                left: left + "%"
            })
            .addClass('er-hotspot-is-visible');
            if(er_open_all_spot){
                $this.addClass('er-hotspot-is-active');
            }
            
        });
        
        var active_event = e_type == 'hover' ? 'mouseenter' : 'click';
        var deactive_event = e_type == 'hover' ? 'mouseleave' : 'click';

        $hotspots_label.on(deactive_event, function(e){
           
            $(this).removeClass('er-hotspot-is-visible');
            $(this).parents('.er-hotspot-image').find('.er-hotspot-hotspot').removeClass('er-hotspot-is-active');
            e.preventDefault();
            
        });
        
        $hotspot.on(active_event, function(e){
   
            var text = $(this).data('text');
            var button_text = $(this).data('button-text');
            var button_url = $(this).data('button-url');
           
            var $_hot_html_detail = `<h4>${$(this).text()}</h4><p>${text}</p>
            ${Boolean(button_text)} ?? <a href="${button_url}" >${button_text} </a>
            `;
      
            if(!$(this).hasClass('er-hotspot-is-active'))
            {
                $(this).parents('.er-hotspot-image').find('.er-hotspot-hotspot').removeClass('er-hotspot-is-active');
                $(this).addClass('er-hotspot-is-active');
                $(this).parents('.er-hotspot-image').find('.er-hotspot-hotspots-label').html( $_hot_html_detail ).addClass('er-hotspot-is-visible');
            }
            else
            {
                $(this).removeClass('er-hotspot-is-active');
                $(this).parents('.er-hotspot-image').find('.er-hotspot-hotspots-label').html( $_hot_html_detail ).removeClass('er-hotspot-is-visible');	
            }
            
            e.preventDefault();
           
        });

        /** Layout One end*/
        var $lg_hotspot = $scope.find('.er--hotspot--lg-hotspot__button');
     
        if($lg_hotspot.length){
             active_event = e_type == 'hover' ? 'mouseenter' : 'click';
             deactive_event = e_type == 'hover' ? 'hover' : 'click';
             
            const selectHotspot = (e) => {

                const clickedHotspot = e.target.parentElement;
                const container = clickedHotspot.parentElement;
                const hotspots = container.querySelectorAll(".er--hotspot--lg-hotspot"); 
              
                hotspots.forEach(hotspot => {

                  if (hotspot === clickedHotspot) {
                    hotspot.classList.toggle("er--hotspot--lg-hotspot--selected");
                  } else {
                    hotspot.classList.remove("er--hotspot--lg-hotspot--selected");
                  }
                  
                });
            }
              
            $lg_hotspot.each(function( index ) {
                $(this).on(active_event,selectHotspot);
            });

            // default open 
            const default_hotspots = $scope.find(".er--hotspot--lg-hotspot"); 
            if(er_open_all_spot){
                default_hotspots.each(function( index ) {
                    $(this).addClass('er--hotspot--lg-hotspot--selected');
                });
            }  
      
        }
        
        /** end layout two */

        let clickEvent = 'ontouchstart' in window ? 'touchend' : 'click';
       
        let triggers = $scope.find('.er--trigger');
        let hotspots = $scope.find('.er--hotspot');

        for (let trigger of triggers) {

        trigger.style.top = trigger.dataset.top +'%';
        trigger.style.left = trigger.dataset.left+'%';//
        trigger.style.setProperty('--er_hostspot_keyframe_pulse_hover_color', trigger.dataset.color);
        trigger.style.setProperty('--er_hostspot_keyframe_pulse_color', trigger.dataset.normal_color);
        trigger.addEventListener(clickEvent, function (event) {
            event.stopPropagation();
            this.parentNode.classList.add('reveal');
        }, false);
        }

        for (let hotspot of hotspots) {
       
        hotspot.addEventListener(clickEvent, function () {
            for (let hotspot of hotspots) {
             
            hotspot.classList.remove('reveal');
            }
        });
        }

        document.body.addEventListener(clickEvent, function () {
        for (let hotspot of hotspots) {
            hotspot.classList.remove('reveal');
        }
        });
       
    }

  

    $(window).on('elementor/frontend/init', function() {
        
        elementorFrontend.hooks.addAction('frontend/element_ready/Element_Ready_HotSpot_Widget.default', Element_Ready_HotSpot_View_Widget);
       
    });
})(jQuery);