<?php
// Generate a unique identifier based on the widget instance ID
$templatePartDir = __DIR__ . '/../template_part/';

$unique_id_sweeper_two = 'wps_slide_sweeper_two_' . uniqid();

// Output the unique identifier and the script
echo '
<script>
   window["hoverSliderOptions"] = { preloadImages: true };

    jQuery(document).ready(function($) {

        //put the js code under this line 
        var swiper = new Swiper(".' . $unique_id_sweeper_two . ' .mySwiper", {
        

             grid: {
        rows: 2,
      },
            spaceBetween: 0,
            pagination: {
                el: ".' . $unique_id_sweeper_two . ' .swiper-pagination",
                clickable: true,
            },
			
			
    breakpoints: {
                600: {
                    slidesPerView: ' . esc_js($settings['wps_columns_tab']) . ',
                },
                1024: {
                    slidesPerView: ' . esc_js($settings['wps_columns']) . ',
                },
            },

			
			
			
autoplay: ' . json_encode($settings['slide_auto_loop'] === '1') . ',


 

			
        });


        
        //put the code above the line 

    });
</script>';


// Place the style tag inside the if condition

if ( 'none' === $settings['slider_path_hide_sweep_mobile'] ) {
    echo '<style>
        @media screen and (max-width: 1200px){ 
            .wps_slider_two_dot {
                display: none!important;
            } 
        }
    </style>';
}
?>


<section class="mr_shop mr_products_one wps_slide_sweeper_two <?php echo $unique_id_sweeper_two; ?> wps_slide_row">
    <div class="auto-container swiper mySwiper">
        <div class="swiper-wrapper">
            <!-- While Loop Area -->
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <?php global $post, $product; ?>
                <!-- Product Column Start if WHILE LOOP -->
                <div class="wps_product_column swiper-slide">
                    <div class="mr_product_block product-block_hr_001">
                        <!-- Global Settings -->
                        <?php require $templatePartDir . 'hook.php'; ?>
                        <div <?php wc_product_class(); ?>>
                            <!-- Product Style Start -->
                            <div class="mr_style_one wps_product_block_one">
                                <!-- Thumbnail Area -->
                                <?php if ($settings['show_product_x_thumbnail'] && !empty(get_the_post_thumbnail())) : ?>
                                    <div class="wps_thumbnail_area wps_slide_thumb">
                                        <?php
                                        require $templatePartDir . 'hot_offer.php';
                                        require $templatePartDir . 'special_offer.php';
                                        require $templatePartDir . 'thumbnail_two.php';
									
                                        ?>
                                    </div><!-- Thumbnail area div -->
                                    <?php require $templatePartDir . 'product_overlay.php'; ?>
                                <?php endif; ?>
<?php  if ( 'top' === $settings['wps_columns_expand'] ) { ?>
  <div class="wps_hide_two_block">
<?php } ?>  
                                <!-- Product Bottom Area -->
                                <div class="wps_product_details product_bottom mr_bottom wps_order_container">
                                    <?php
                                    // Include various template parts
                                    require $templatePartDir . 'title.php';
                                    require $templatePartDir . 'rating.php';
                                    require $templatePartDir . 'price.php';
                                    require $templatePartDir . 'progress.php';
                                    require $templatePartDir . 'instock.php';
                                    require $templatePartDir . 'offer_countdown.php';
                                    require $templatePartDir . 'category.php';
                                    require $templatePartDir . 'feature_addtocart.php';
                                    ?>
                                </div>
                                <!-- End Product Bottom Area -->
 <?php  if ( 'top' === $settings['wps_columns_expand'] ) { ?>
  </div>
<?php } ?>
                            </div>
                            <!-- End Product Style One -->
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="wps_slider_two_dot swiper-pagination"></div>
    </div>
</section>



