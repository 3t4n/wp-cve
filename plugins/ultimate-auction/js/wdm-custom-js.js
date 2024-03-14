jQuery(document).ready(function(){
    jQuery(".auction-small-img").click(function(){
        jQuery(this).addClass("wdm-current-preview-image").siblings().removeClass('wdm-current-preview-image');
    });
    
jQuery(".auction-main-img-a").boxer({'fixed': true});
});