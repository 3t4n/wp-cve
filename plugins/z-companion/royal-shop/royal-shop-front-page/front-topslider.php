<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
if(get_theme_mod('royal_shop_disable_top_slider_sec',false) == true){
    return;
  }
?>
<section class="wzta-slider-section <?php echo esc_attr(get_theme_mod('royal_shop_top_slide_layout','slide-layout-5'));?>">
<?php z_companion_display_customizer_shortcut( 'royal_shop_top_slider_section' );
 ?>
<?php if(get_theme_mod('royal_shop_top_slide_layout','slide-layout-5')=='slide-layout-1'):?>
<div  id="wzta-slider" style="position:relative;margin:0 auto;top:0px;left:0px;overflow:hidden;visibility:hidden;">
                          <div  data-u="slides" class="slides" >
                           <?php royal_shop_top_slider_content('royal_shop_top_slide_content', ''); ?>                    
                          </div> 
                <!-- Bullet Navigator -->
        <div data-u="navigator" class="jssor-pagination" >
            <div data-u="prototype" class="i" >
                <svg viewBox="0 0 16000 16000">
                    <circle class="b" cx="8000" cy="8000" r="5800"></circle>
                </svg>
            </div>
        </div>                             
 </div>
<?php elseif(get_theme_mod('royal_shop_top_slide_layout','slide-layout-5')=='slide-layout-2'):?>
<div  id="wzta-widget-slider">
         <div class="wzta-widget-slider-wrap">
           <div class="wzta-slider-content">
               <div class="wzta-top2-slide owl-carousel">
                 <?php  royal_shop_top_slider_2_content('royal_shop_top_slide_content', ''); ?>      
               </div>
             </div>
           <div class="wzta-add-content">
                 <a href="<?php echo esc_url(get_theme_mod('royal_shop_lay2_url'));?>"><img src="<?php echo esc_url(get_theme_mod('royal_shop_lay2_adimg'));?>"></a>
            </div>
         </div>    
    </div>                              
<?php elseif(get_theme_mod('royal_shop_top_slide_layout','slide-layout-5')=='slide-layout-3'): ?>

<div  id="wzta-3col-slider">
         <div class="wzta-3col-slider-wrap">
           <div class="wzta-slider-content">
               <div class="wzta-top2-slide owl-carousel">
               <?php  royal_shop_top_slider_2_content('royal_shop_top_slide_content', ''); ?>
               </div>
             </div>
           <div class="wzta-add-content">
                 <div class="wzta-3-add-content">
                   <div class="wzta-row">
                   <a href="<?php echo esc_url(get_theme_mod('royal_shop_lay3_url'));?>"><img src="<?php echo esc_url(get_theme_mod('royal_shop_lay3_adimg'));?>"></a>
                   </div>
                   <div class="wzta-row">
                    <a href="<?php echo esc_url(get_theme_mod('royal_shop_lay3_2url'));?>"><img src="<?php echo esc_url(get_theme_mod('royal_shop_lay3_adimg2'));?>"></a>
                   </div>
                   <div class="wzta-row"><a href="<?php echo esc_url(get_theme_mod('royal_shop_lay3_3url'));?>"><img src="<?php echo esc_url(get_theme_mod('royal_shop_lay3_adimg3'));?>"></a>
                   </div>
                 </div>
            </div>
         </div>    
    </div> 
<?php elseif(get_theme_mod('royal_shop_top_slide_layout','slide-layout-5')=='slide-layout-4'): ?>
<div  id="wzta-2col-slider">
         <div class="wzta-2col-slider-wrap">
           <div class="wzta-slider-content">
               <div class="wzta-top2-slide owl-carousel">
                  <?php  royal_shop_top_slider_2_content('royal_shop_top_slide_content', ''); ?>
                  
               </div>
             </div>
           <div class="wzta-add-content">
                 <div class="wzta-2-add-content">
                   <div class="wzta-row">
                    <a href="<?php echo esc_url(get_theme_mod('royal_shop_lay4_url1'));?>"><img src="<?php echo esc_url(get_theme_mod('royal_shop_lay4_adimg1'));?>"></a></div>
                   <div class="wzta-row">
                    <a href="<?php echo esc_url(get_theme_mod('royal_shop_lay4_url2'));?>"><img src="<?php echo esc_url(get_theme_mod('royal_shop_lay4_adimg2'));?>"></a>
                  </div>
                   
                 </div>
            </div>
         </div>    
    </div> 
<?php elseif(get_theme_mod('royal_shop_top_slide_layout','slide-layout-5')=='slide-layout-5'): ?>
<div  id="wzta-single-slider" style="position:relative;margin:0 auto;top:0px;left:0px;overflow:hidden;visibility:hidden;">
                          <div  data-u="slides" class="slides" >
                           <?php royal_shop_top_single_slider_content('royal_shop_top_slide_lay5_content', ''); ?>                    
                          </div> 
                <!-- Bullet Navigator -->
        <div data-u="navigator" class="jssor-pagination" data-autocenter="1">
            <div data-u="prototype" class="i" >
                <svg viewBox="0 0 16000 16000">
                    <circle class="b" cx="8000" cy="8000" r="5800"></circle>
                </svg>
            </div>
        </div>                             
 </div><?php elseif(get_theme_mod('royal_shop_top_slide_layout','slide-layout-5')=='slide-layout-6'): ?>
 <div  id="wzta-4col-slider">
         <div class="wzta-4col-slider-wrap">
           <div class="wzta-slider-content">
               <div class="wzta-top2-slide owl-carousel">
                  <?php  royal_shop_top_slider_2_content('royal_shop_top_slide_content', ''); ?>
                  
               </div>
             </div>
           <div class="wzta-add-content">
                 <div class="wzta-3-add-content">
                  <div class="wzta-add-content-twocol-1">
                   <div class="wzta-row">
                   <a href="<?php echo esc_url(get_theme_mod('royal_shop_lay6_url'));?>"><img src="<?php echo esc_url(get_theme_mod('royal_shop_lay6_adimg'));?>"></a>
                   </div>
                   <div class="wzta-row">
                    <a href="<?php echo esc_url(get_theme_mod('royal_shop_lay6_2url'));?>"><img src="<?php echo esc_url(get_theme_mod('royal_shop_lay6_adimg2'));?>"></a>
                   </div>
                 </div>
                 <div class="wzta-add-content-twocol-2">
                   <div class="wzta-row"><a href="<?php echo esc_url(get_theme_mod('royal_shop_lay6_3url'));?>"><img src="<?php echo esc_url(get_theme_mod('royal_shop_lay6_adimg3'));?>"></a>
                   </div>
                 </div>
                 </div>
            </div>
         </div>    
    </div> 
<?php endif; ?>      
</section>