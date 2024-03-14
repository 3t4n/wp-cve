jQuery(function(){
    jQuery('.wrld-ts-custom-popup-modal').fadeIn(500);
    body = document.querySelector("body").style.overflow = "hidden";

    jQuery('.wrld-close-modal-btn').on('click', function(event) {
      // event.preventDefault();
       jQuery('.wrld-ts-custom-popup-modal').hide();
       body = document.querySelector("body").style.overflow = "scroll";      
    });
    jQuery('.wrld-close-modal-btn2').on('click', function(event) {
      // event.preventDefault();
       jQuery('.wrld-ts-custom-popup-modal').hide();
       body = document.querySelector("body").style.overflow = "scroll";      
    });
    jQuery('.table-section table tbody tr:nth-child(n+7)').hide();
    jQuery('.table-section + div span').on('click', function(){
        if ( jQuery(this).hasClass('less')) {
          jQuery(this).removeClass('less');
          jQuery(this).text('Show Less');
          jQuery('.table-overlay').hide();
          jQuery('.table-section table tbody tr:nth-child(n+5)').show();
        } else {
          jQuery(this).addClass('less');
          jQuery(this).text('View More Features');
          jQuery('.table-overlay').show();
          jQuery('.table-section table tbody tr:nth-child(n+5)').hide();
        }
    });
});