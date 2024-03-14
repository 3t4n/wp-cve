<?php
$orientation = $settings['slider-orientation'];
$width = $settings['slider-width'];
$height = $settings['slider-height'];
$visible_images = $settings['ras-visible-images'];
$image_distance = $settings['ras-image-distance'];
$max_opened_image_size = $settings['ras-opened-image-size'];
$open_image_on = $settings['ras-open-image'];
$shadow = $settings['slider-shadow'];
$autoplay = $settings['slider-autoplay'];
$mouse_wheel = $settings['slider-mouse-wheel'];
$close_panel_on_mouse_out = $settings['ras-panel-on-mouse-out'];
$autoplay_direction = $settings['slider-direction'];
$autoplay_delay = $settings['slider-delay'];

$shadow       = ($shadow == '1') ? 'true' : 'false';
$autoplay     = ($autoplay == '1') ? 'true' : 'false';
$mouse_wheel  = ($mouse_wheel == '1') ? 'true' : 'false';
$close_panel_on_mouse_out = ($close_panel_on_mouse_out == '1') ? 'true' : 'false';


$slider_conf = compact('visible_images', 'width', 'height', 'orientation','image_distance', 'max_opened_image_size','open_image_on','shadow','autoplay','mouse_wheel', 'close_panel_on_mouse_out', 'autoplay_direction', 'autoplay_delay' );

?>

<style type="text/css">

    .read-btn{
      text-align: center !important;
      padding: 10px !important;
      cursor: pointer !important;
      margin: 5px 0 !important;
      text-transform: unset !important;
      text-decoration: none !important;
    }

    .ras-panels .ras-panel .text-block-2 {
      position: absolute;
      left: 30px;
      bottom: 30px;
      max-width: 400px;
      padding: 20px;
      border-radius: 5px;
      background-color: rgba(0, 0, 0, 0.6);
      color: #fff;
      z-index: 4;
      visibility: hidden;
    }
    .ras-panels .ras-panel .text-block-2 h3 {
      font-size: 20px;
      font-weight: 700;
    }
    .ras-panels:hover .ras-panel:hover {
      -ms-flex-negative: 0;
      flex-shrink: 0;
      width: 100%;
    }

    .ras-panels .ras-panel .text-block-2 {
      bottom: -60px;
    }

    .ras-panels:hover .ras-panel:hover .text-block-2:not(:hover):after {
      -webkit-transition-property: all;
      transition-property: all;
      -webkit-transition-duration: 0.2s;
      transition-duration: 0.2s;
      -webkit-transition-timing-function: linear;
      transition-timing-function: linear;
      -webkit-transition-delay: 0s;
      transition-delay: 0s;
      opacity: 1;
    }
    .ras-panels:hover .ras-panel:hover .text-block-2 {
      -webkit-transition-property: all;
      transition-property: all;
      -webkit-transition-duration: 0.2s;
      transition-duration: 0.2s;
      -webkit-transition-timing-function: linear;
      transition-timing-function: linear;
      -webkit-transition-delay: 1s;
      transition-delay: 1s;
      bottom: 30px;
      opacity: 1;
      visibility: visible;
    }

    #responsive-accordion-<?php echo $PostId; ?> .ras-panels .ras-panel .text-block-2 .text{
      margin-bottom: 15px;
    }

    @media screen and (max-width: 480px){
      .ras-panels:hover .ras-panel:hover .text-block-2 {
        left: 10px;
        bottom: 10px;
      }
      .ras-panels .ras-panel .text-block-2 p,
      .ras-panels .ras-panel .text-block-2 .read-btn{
        font-size: 10px !important;
      }
      .ras-panels .ras-panel .text-block-2 h3{
        font-size: 14px !important;
      }
    }

    @media screen and (max-width: 320px){
      .ras-panels .ras-panel .text-block-2{
        display: none;
      }
    }

    <?php echo $settings['style']; ?>

</style>

<div id="responsive-accordion-<?php echo $PostId; ?>" class="design-2 ras-slider ras-slider-<?php echo $PostId; ?>" style="margin-bottom: 100px;">
        <div class="ras-panels">
            <?php
            foreach ( $images as $image ): ?>
                    <?php 
                        
                        $image_object = get_post( $image['id'] );

                        if ( is_wp_error( $image_object ) || get_post_type( $image_object ) != 'attachment' ) {
                            continue;
                        }

                        /*--image cropping--*/
                        $id=$image['id'];
                      
                        $url = wp_get_attachment_image_src($id, 'resp_accordion_slider_img', true);
                    
                       ?>  
                        <div class="ras-panel">
                            <img class="as-background" src="<?php echo $url[0]; ?>"/>


                        <?php if((!$settings['hide-title'] && !empty($image['title'])) || (!$settings['hide-description'] && (!empty($image['description']))) || (!$settings['hide-button'] && $image['alt'] && $image['link'])) { ?>
                          <div class="text-block-2">
                            <?php if( ! $settings['hide-title']  ): ?>
                                <h3 style="
                                display: block;
                                font-size: <?php echo $settings['titleFontSize'] ?>px;
                                font-family: <?php echo $settings['font-family'] ?>;
                                color: <?php echo $settings['titleColor'] ?>;
                                background-color: <?php echo $settings['titleBgColor'] ?>;
                                    margin: 0 0 10px 0;
                                    text-align: justify;
                                "><?php echo $image['title']; ?></h3>
                            <?php endif ?>
                            <div class="text">
                              <?php if( ! $settings['hide-description']  ): ?>
                                <p style="
                                font-size: <?php echo $settings['captionFontSize'] ?>px;
                                font-family: <?php echo $settings['font-family'] ?>;
                                              color: <?php echo $settings['captionColor'] ?>;
                                              background-color: <?php echo $settings['captionBgColor'] ?>;
                                                  margin: 0 0 8px 0;
                                                  text-align: justify;
                                "><?php echo $image['description']; ?></p>
                              <?php endif ?>
                            </div>
                            <?php if( ! $settings['hide-button'] ): ?>
                              <?php if($image['alt'] && $image['link']) { ?>
                               <a href="<?php echo $image['link']; ?>" target="<?php if($image['target']==1) {?>_blank<?php } ?>" class="read-btn" style="
                             font-size: <?php echo $settings['buttonFontSize'] ?>px;
                             font-family: <?php echo $settings['font-family'] ?>;
                             color: <?php echo $settings['buttonTextColor'] ?>;
                             background-color: <?php echo $settings['buttonBgColor'] ?>;
                             border-radius: <?php echo $settings['buttonBorder'] ?>px;"><?php echo $image['alt']; ?></a>
                             <?php } ?>
                            <?php endif ?>
                          </div>
                        <?php } ?> 


                        </div>
               <?php endforeach; ?>                      
        </div>
        <div class="wpaas-conf-<?php echo $PostId; ?>" style="display: none;"><?php echo json_encode( $slider_conf ); ?></div>
    </div>


<script type="text/javascript">
    jQuery(document).ready(function($) {
        $( '.ras-slider-<?php echo $PostId; ?>' ).each(function( index ) { 
            var slider_id   = $(this).attr('id');  
            var slider_conf = $.parseJSON( $(this).closest('#responsive-accordion-<?php echo $PostId; ?>').find('.wpaas-conf-<?php echo $PostId; ?>').text());
                
            if( typeof(slider_id) != 'undefined' && slider_id != '' ) { 
                $('#responsive-accordion-<?php echo $PostId; ?>').ResponsiveAccordionSlider({
                width:parseInt(slider_conf.width), 
                height: parseInt(slider_conf.height),
                responsiveMode: 'custom',
                orientation: slider_conf.orientation,
                maxOpenedPanelSize: slider_conf.max_opened_image_size,
                openPanelOn: slider_conf.open_image_on,
                visiblePanels: parseInt(slider_conf.visible_images),
                autoplayDirection: slider_conf.autoplay_direction,
                autoplayDelay: parseInt(slider_conf.autoplay_delay),
                startPanel: -1,
                panelDistance: parseInt(slider_conf.image_distance),
                shadow     : (slider_conf.shadow)      == "true"           ? true          : false,
                autoplay   : (slider_conf.autoplay)    == "true"           ? true          : false,
                mouseWheel : (slider_conf.mouse_wheel) == "true"           ? true          : false,    
                closePanelsOnMouseOut : (slider_conf.close_panel_on_mouse_out) == "true"           ? true          : false,    
                   
                breakpoints: {
                    960: {visiblePanels: (parseInt(slider_conf.visible_images) > 5) ? 5 : parseInt(slider_conf.visible_images)},
                    800: {visiblePanels: (parseInt(slider_conf.visible_images) > 3) ? 3 : parseInt(slider_conf.visible_images)},
                    650: {visiblePanels: 3},
                    500: {visiblePanels: 3}
                }
             });
            }
        });
    });
</script>