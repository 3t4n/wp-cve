jQuery(document).ready(function($){
    
    var maxHeight = -1;

    $('.series-main').each(function() {
        maxHeight = maxHeight > $(this).height() ? maxHeight : $(this).height();
    });

    $('.series-main').each(function() {
       $(this).height(maxHeight);
    });
    
});
