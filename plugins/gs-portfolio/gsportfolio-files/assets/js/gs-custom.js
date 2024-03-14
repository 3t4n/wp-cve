jQuery(document).ready(function($) {
    "use strict";
    
    $('.gs_p_portfolio').magnificPopup({
        type:'inline',
        midClick: false,
        gallery:{
            enabled:true
        },
        delegate: 'a.gs_p_pop',
        removalDelay: 500, //delay removal by X to allow out-animation
        callbacks: {
            beforeOpen: function() {
               this.st.mainClass = this.st.el.attr('data-effect');
            }
        },
        //closeOnContentClick: true,
    });
   
});