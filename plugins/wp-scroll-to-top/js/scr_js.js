
jQuery(document).ready(function(){
var doc_height = jQuery(document).height();
var height_half = doc_height /5;
jQuery(window).scroll(function() {
  
  
   if(jQuery(this).scrollTop()>= height_half) {
       jQuery('#scr_wrapper').slideDown(300);
       jQuery('#scr_wrapper').click(function(){
       	jQuery('html , body').animate({scrollTop:0},300);
       	throw '';
       	return false;
       jQuery("#scr_wrapper").slideUp(700);
       });
   }
    else{
   jQuery('#scr_wrapper').slideUp(300); }


   
  

});

});
