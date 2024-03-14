(function($) {
   
    if (typeof newslatter_service === 'undefined') {
        return;
    }
   
    var $autoclose_time = parseInt( newslatter_service.autoclose_time ) || 3000;
    var $load_after_time = parseInt( newslatter_service.load_after_time ) || 1000;

     setTimeout(function(){ 
      
            $('#element-ready-pro-sr-newslatter-popup-modal').nifty('show');

         },
         $load_after_time
     );  
     
    
    if(newslatter_service.auto_close && $autoclose_time > 0){

        setTimeout(function(){ 
            $("#element-ready-pro-sr-newslatter-popup-modal").nifty("hide")
        },

        $autoclose_time
        );
        
    }

  

})(jQuery);