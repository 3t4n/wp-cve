jQuery( document ).ready(function() {
    jQuery('#af2_goto_formularbuilder_settings').on('click', _ => {
        window.location.href = af2_formularbuilder_preview_object.redirect_formularbuilder_settings_url;
    });

    jQuery('#device_button_mobile').on('click', _ => {
        jQuery('.af2_custom_builder_wrapper.af2_formularbuilder_preview').addClass('af2_preview_mobile_view');
        jQuery('.af2_custom_builder_wrapper.af2_formularbuilder_preview').removeClass('af2_preview_ipad_view');
        jQuery('.af2_custom_builder_wrapper.af2_formularbuilder_preview').removeClass('af2_preview_desktop_view');

        
        jQuery('.af2_form_carousel').css('min-width', 'unset');
        jQuery('.af2_form_carousel').css('max-width', 'unset');
        jQuery('.af2_form_carousel').css('min-width',  jQuery('.af2_form_carousel').width());
        jQuery('.af2_form_carousel').css('max-width',  jQuery('.af2_form_carousel').width());

        const height = jQuery('.af2_form_carousel .af2_carousel_content').last().height();
        jQuery('.af2_form_carousel').css('height', height);
    });
    jQuery('#device_button_ipad').on('click', _ => {
        jQuery('.af2_custom_builder_wrapper.af2_formularbuilder_preview').removeClass('af2_preview_mobile_view');
        jQuery('.af2_custom_builder_wrapper.af2_formularbuilder_preview').addClass('af2_preview_ipad_view');
        jQuery('.af2_custom_builder_wrapper.af2_formularbuilder_preview').removeClass('af2_preview_desktop_view');

        
        jQuery('.af2_form_carousel').css('min-width', 'unset');
        jQuery('.af2_form_carousel').css('max-width', 'unset');
        jQuery('.af2_form_carousel').css('min-width',  jQuery('.af2_form_carousel').width());
        jQuery('.af2_form_carousel').css('max-width',  jQuery('.af2_form_carousel').width());


        const height = jQuery('.af2_form_carousel .af2_carousel_content').last().height();
        jQuery('.af2_form_carousel').css('height', height);
    });
    jQuery('#device_button_desktop').on('click', _ => {
        jQuery('.af2_custom_builder_wrapper.af2_formularbuilder_preview').removeClass('af2_preview_mobile_view');
        jQuery('.af2_custom_builder_wrapper.af2_formularbuilder_preview').removeClass('af2_preview_ipad_view');
        jQuery('.af2_custom_builder_wrapper.af2_formularbuilder_preview').addClass('af2_preview_desktop_view');

        
        jQuery('.af2_form_carousel').css('min-width', 'unset');
        jQuery('.af2_form_carousel').css('max-width', 'unset');
        jQuery('.af2_form_carousel').css('min-width',  jQuery('.af2_form_carousel').width());
        jQuery('.af2_form_carousel').css('max-width',  jQuery('.af2_form_carousel').width());


        const height = jQuery('.af2_form_carousel .af2_carousel_content').last().height();
        jQuery('.af2_form_carousel').css('height', height);
    });
});



/*
(function($, window, document) {

    var breakpoints = {
      "Mobile":  {"width": 360, "height": 768, "icon" : "phone_iphone"},
      "iPad":  {"width": 780, "height": 1024, "icon" : "tablet_mac"},
      "Desktop":    {"width": 100, "height": 700, "icon" : "desktop_windows"}
    }
    
    jQuery(function() {
      var deviceCurrent;
      
      var $deviceChooser = jQuery('.device-chooser'),
          $content = jQuery('.preview_wrapper'),
          $iframe = jQuery('iframe#af2_preview_window'),
          $deviceButtons = jQuery('.device-chooser button');
  
  
      $deviceButtons.on('click', function(e){
          var key = this.dataset.device;
          $deviceChooser.find('li').removeClass('active');
          changeDevice(
            key, 
            breakpoints[key]["width"], 
            breakpoints[key]["height"]
          );
          jQuery(this).parent('li').addClass('active');
      });
         
      function changeDevice(key, w, h) {
          var w = w.toString(); var h = h.toString();
          $iframe
            .css('width', w + (key != 'Desktop' ? 'px' : '%'))
            .css('height', h + (key != 'Desktop' ? 'px' : '%'));
          if(key == 'Desktop'){
            $content.addClass('content--maximized');
          }else{
            $content.removeClass('content--maximized');
          }
          deviceCurrent = key;
      }
  
      var initDeviceType = "Desktop"
      var initDevice = changeDevice(initDeviceType, breakpoints[initDeviceType]["width"], breakpoints[initDeviceType]["height"]);
    });
  
  }(window.jQuery, window, document));
  */