<?php

/*
 * Created on Feb 10, 2013
 * Author: Mohsin Rasool
 * 
 * Copyright 2013 NeuMarkets. All rights reserved.
 * COMPANY PROPRIETARY/CONFIDENTIAL. Use is subject to license terms.
 */

function fsi_scripts_method() {
    if(!wpdev_fsi_should_display())
        return false;
    wp_enqueue_script('jquery');            
}    
 
add_action('wp_enqueue_scripts', 'fsi_scripts_method'); // For use on the Front end (ie. Theme)

add_filter('wp_footer', 'fsi_scripts');
function fsi_scripts(){
    if(!wpdev_fsi_should_display())
        return false;
    $opacity = get_option('fsi_opacity');
    if(empty($opacity) || !is_numeric($opacity) || $opacity>100)
        $opacity =100;
    
    global $post;
    $fsi_animation = get_option('fsi_animation');
    if(is_page() && get_post_meta($post->ID, 'fsi_fs_images',true)){
        $fsi_animation = get_post_meta($post->ID, 'fsi_fs_images_slideshow',true);
    }
    
    $animationDelay = get_option('fsi_animation_delay');
    if(empty($animationDelay) || !is_numeric($animationDelay) || $animationDelay<1)
        $animationDelay =4;

    $animationDuration = get_option('fsi_animation_duration');
    if(empty($animationDuration) || !is_numeric($animationDuration) || $animationDuration<1)
        $animationDuration =5;

?>
<script type="text/javascript">
jQuery(document).ready(function($) {
    <?php   if($fsi_animation) {       ?>
        window.fsi_interval = setInterval(fsi_slideshow,<?php echo $animationDelay*1000 ?>);
   <?php } ?>
       function fsi_slideshow(){
            $('#fsi-full-bg img.fsi_active').animate({opacity:0},<?php echo $animationDuration*100 ?>, function(){
                $(this).removeClass('fsi_active');
            })
            if($('#fsi-full-bg img.fsi_active').next().length>0)
                $('#fsi-full-bg img.fsi_active').next().animate({opacity:<?php echo $opacity/100 ?>},<?php echo $animationDuration*100 ?>).addClass('fsi_active');
            else
                $('#fsi-full-bg img:first').animate({opacity:<?php echo $opacity/100 ?>},<?php echo $animationDuration*100 ?>).addClass('fsi_active');

        }
	var fsi_theWindow = $(window);
	
        function fsi_resize_images() {
		
            $("#fsi-full-bg img").load(function(){
                var imgWidth = $(this).width();
                var imgHeight = $(this).height();;
                var aspectRatio =  imgWidth/ imgHeight;

                if(imgWidth < fsi_theWindow.width() || imgHeight < fsi_theWindow.height()  ){
                    if(imgWidth < fsi_theWindow.width() ) {

                        $(this).css('left','50%');
                        $(this).css('margin-left','-'+imgWidth/2+'px');

                    }
                    if(imgHeight < fsi_theWindow.height() ) {

                        $(this).css('top','50%');
                        $(this).css('margin-top','-'+imgHeight/2+'px');

                    }
                }else {
                    if ( (fsi_theWindow.width() / fsi_theWindow.height()) < aspectRatio ) {
                        $(this)
                            .removeClass()
                            .addClass('fsi-full-bg-full-height');
                        $(this).css('left','50%');
                        $(this).css('margin-left','-'+$(this).width()/2+'px');
                    } else {
                        $(this)
                            .removeClass()
                            .addClass('fsi-full-bg-full-width');
                        $(this).css('top','50%');
                        $(this).css('margin-top','-'+$(this).height()/2+'px');
                    }
                }
                // if first image is loaded.. start slideshow
                if($('#fsi-full-bg img:first')[0] == $(this)[0] ) {
                    $(this).animate({opacity:<?php echo $opacity/100 ?>},100).addClass('fsi_active');    
                }
            });
            //fsi_theWindow.resize(resizeBg);
            
            
	}
	fsi_resize_images();
	

});
</script>
<?php

}

?>