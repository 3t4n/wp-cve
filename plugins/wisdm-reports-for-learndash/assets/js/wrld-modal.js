jQuery(function(){
    jQuery('.wrld-custom-popup-modal').fadeIn(500);
    body = document.querySelector("body").style.overflow = "hidden";

    jQuery('.wrld-modal-button.update-pro').on('click', function(event) {
       event.preventDefault();
       jQuery('.wrld-custom-popup-modal').hide();
       body = document.querySelector("body").style.overflow = "scroll";
       let the_plugin_list = jQuery('#the-list tr');
       let pro_row         = '';
        console.log(the_plugin_list);
       if (the_plugin_list.length>0) {
        the_plugin_list.each(function(index){
            if('wisdm-reports-for-learndash-pro'==jQuery(the_plugin_list[index]).data('slug')) {
                pro_row = the_plugin_list[index];
            }
        });
       }       
    });
});