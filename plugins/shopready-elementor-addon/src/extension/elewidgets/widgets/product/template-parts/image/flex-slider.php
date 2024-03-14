<?php

    defined('ABSPATH') || exit;

    wp_enqueue_script('slick');
    $id = get_the_id();

    if (shop_ready_is_elementor_mode()) {

        if ($settings['wready_product_id'] != '') {
            $id = $settings['wready_product_id'];
        }
    }

    global $product;
    $product = is_null($product) ? wc_get_product($id) : $product;

    if (!is_object($product)) {
        return;
    }

    if (!method_exists($product, 'get_id')) {
        return;
    }

    $attachment_ids = $product->get_gallery_image_ids();
    $post_thumbnail_id = method_exists($product, 'get_image_id') ? $product->get_image_id() : get_post_thumbnail_id($product->get_id());
    
    // video 
    $product_videos = get_post_meta($product->get_id(),'shop_ready_videos_gal_list', true);
 
?>
<div class="wooready_product_details_thumb shop_ready_product_details_thumb_wrapper_mod display:flex justify-content:center">
    <div class="wooready_product_details_thumb_wrapper margin-right:20">
        <div class="wooready_product_details_thumb_1 shopready_product_details_thumb_wrapper_mod">
               
               <?php
                
                if(is_array($product_videos)){

                    foreach($product_videos as $item):
                        if($item['type'] == 'youtube'){
                            ?>
                            <div class="item video youtube">
                                <iframe class="sr--product--video" allowfullscreen src="https://www.youtube.com/embed/<?php echo esc_attr($item['url_or_code']); ?>" frameborder="0"></iframe>
                            </div>
                            <?php
                        }elseif($item['type'] == 'vimeo'){
                        ?>
                            <div class="item video vimeo">
                                <iframe width="640" height="360" class="sr--product--video" src="https://player.vimeo.com/video/<?php echo esc_attr($item['url_or_code']); ?>?h=cdb860bbfe&color=8ac17f&portrait=0" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        <?php    
                        }else{
                            ?>
                                <div class="item video ">
                                    <video class="sr--product--video" controls="controls">
                                        <source src="<?php echo esc_attr($item['url_or_code']) ?>" type="video/mp4">
                                        <?php echo esc_html__('Your browser does not support the video tag.','shopready-elementor-addon'); ?>
                                    </video>
                                </div>
                            <?php
                        }
                    endforeach;
                }
                ?>     
                
            <?php

                if ( is_numeric( $post_thumbnail_id ) ) {
                    echo wp_kses_post('<div class="item">');
                      $image_link = wp_get_attachment_url($post_thumbnail_id);
                      echo wp_kses_post(sprintf('<img class="shop-ready-product-thumb" src="%s" alt="%s">', esc_url($image_link), esc_html($product->get_name())));
                    echo wp_kses_post('</div>');
                }

                foreach ( $attachment_ids as $attachment_id ) {
                    echo wp_kses_post('<div class="item">');
                        if ($settings['show_flash']  == 'yes') {
                            wc_get_template( 'loop/sale-flash.php' );
                        }
                      $image_link = wp_get_attachment_url($attachment_id);
                      echo wp_kses_post(sprintf('<img class="shop-ready-product-thumb" src="%s" alt="%s">', esc_url($image_link), esc_html($product->get_name())));
                    echo wp_kses_post('</div>');
                }
                ?>
                
                
        </div>
        <div class="wooready_product_details_small_item margin-top:15">
            <?php
                if(is_array($product_videos)){

                    foreach($product_videos as $t_item):

                        if(isset($t_item['attachment_id']) && $t_item['attachment_id'] > 10){
                            echo wp_kses_post('<div class="item sr-product-video">');
                                echo wp_kses_post(sprintf('<img src="%s"/>', shop_ready_resize(wp_get_attachment_url($t_item[ 'attachment_id' ]) , 300 , 300 , true)));
                            echo wp_kses_post('</div>');
                        }else{
                            
                            echo wp_kses_post('<div class="item sr-product-video">');
                                echo wp_kses_post('<svg width="257" height="152" viewBox="0 0 257 152" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M86.2001 151.3C67.5001 151.3 48.9001 151.3 30.2001 151.3C11.8001 151.3 0.100098 139.7 0.100098 121.4C0.100098 91.2 0.100098 61.1 0.100098 30.9C0.100098 12.8 11.7001 1.00005 29.7001 1.00005C67.7001 0.900049 105.7 0.900049 143.7 1.00005C161.4 1.00005 173 12.8001 173 30.6001C173.1 60.9001 173.1 91.3001 173 121.6C173 139.5 161.2 151.2 143.2 151.3C124.1 151.3 105.1 151.3 86.2001 151.3Z" fill="black"/>
                                <path d="M256.1 75.9C256.1 89.7 256.1 103.5 256.1 117.4C256 132.7 242.9 140.4 229.6 132.9C216.4 125.5 203.3 117.9 190.3 110.2C188.7 109.3 187.2 106.7 187.2 104.9C187 85.6 187 66.3 187.2 47C187.2 45.2 188.8 42.7001 190.4 41.7001C203.5 33.8001 216.8 26.2001 230.1 18.7001C242.8 11.5001 256.1 19.4 256.2 34C256.2 48 256.1 62 256.1 75.9Z" fill="black"/>
                                </svg>');
                            echo wp_kses_post('</div>');
                        }
        
                    endforeach;

                }
                if (is_numeric($post_thumbnail_id)) {
                    echo wp_kses_post('<div class="item">');
                    $image_link = shop_ready_resize(wp_get_attachment_url($post_thumbnail_id), 300, 300);
                    echo wp_kses_post(sprintf('<img class="shop-ready-product-thumb-gly" src="%s" alt="%s">', esc_url($image_link), esc_html($product->get_name())));
                    echo wp_kses_post('</div>');
                }

                foreach ($attachment_ids as $attachment_id) {
                    echo wp_kses_post('<div class="item">');
                    $image_link = shop_ready_resize(wp_get_attachment_url($attachment_id), 300, 300);
                    echo wp_kses_post(sprintf('<img class="shop-ready-product-thumb-gly" src="%s" alt="%s">', esc_url($image_link), esc_html($product->get_name())));
                    echo wp_kses_post('</div>');
                }
               

            ?>
        </div>
    </div>
</div>