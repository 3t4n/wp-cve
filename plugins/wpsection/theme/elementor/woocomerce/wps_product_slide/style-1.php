<?php
$templatePartDir = __DIR__ . '/../template_part/';
$unique_id = 'wps_slider_path_' . uniqid();

echo '<script>

    window["hoverSliderOptions"] = { preloadImages: true };

    jQuery(document).ready(function($) {

    // Owl Carousel Initialization
        if ($(".' . $unique_id . ' .wps_owls_slide").length) {
            $(".' . $unique_id . ' .wps_owls_slide").owlCarousel({
				loop: false,
                margin:0,
                nav:true,
                smartSpeed: 500,
				autoplay: ' . json_encode($settings['slide_auto_loop'] === '1') . ',
                navText: [ \'<span class="' . $unique_id . ' wps_slider_path wps_slider_left eicon-angle-left"></span>\', \'<span class="' . $unique_id . ' wps_slider_path wps_slider_right eicon-angle-right"></span>\' ],
                responsive:{
                    0:{
                        items:1
                    },
                    480:{
                        items:1
                    },
                    600:{
                        items: ' . json_encode($settings['wps_columns_tab']) . ' 
                    },
                    900:{
                        items: ' . json_encode($settings['wps_columns_tab']) . ' 
                    },
                    1024:{
                        items:' . json_encode($settings['wps_columns']) . ' 
                    },
                }
            });         
        }
		
	//put the js code under this line 
        var swiper = new Swiper(".' . $unique_id . ' .mySwiper", {
            pagination: {
                el: ".' . $unique_id . ' .swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".' . $unique_id . ' .swiper-button-next",
                prevEl: ".' . $unique_id . ' .swiper-button-prev",
            },
            autoplay: {
                delay: 3000, // Set the delay (in milliseconds) between slides
            },
        });
		
		

     //This is Plus Minus Code

// End of area
    });
</script>';

// Place the style tag inside the if condition

if ( 'none' === $settings['slider_path_hide_mobile'] ) {
    echo '<style>
        @media screen and (max-width: 1200px){ 
            .slider_path .owl-theme .owl-dots {
                display: none!important;
            } 
        }
    </style>';
}
?>

<section class="mr_shop mr_products_one produt_section slider_path wps_slider_path <?php echo $unique_id; ?>">
    <div class="auto-container">
<?php $sliderClass = esc_attr($settings['show_slider']) ? 'mr_shop_slide wps_owls_slide owl-theme owl-carousel owl-nav-style-one owl-dot-style-one' : 'row row-5'; ?>
        <div class="<?php echo $sliderClass; ?>" id="myCarousel">

            <!-- While Loop Area -->
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <?php global $post, $product; ?>
                <!-- Product Column Start if WHILE LOOP -->
                <?php
                $sliderColumnClass = esc_attr($settings['show_slider'])
                    ? 'col-lg-12 col-md-12'
                    : 'wps_product_column ' . $columns_markup_print . ' ';
                ?>
                <div class="<?php echo $sliderColumnClass; ?>">
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
                                        require $templatePartDir . 'thumbnail.php';
                                        ?>
                                    </div><!-- Thumbnail area div -->
                                    <?php require $templatePartDir . 'product_overlay.php'; ?>
                                <?php endif; ?>
<?php  if ( 'top' === $settings['wps_columns_expand'] ) { ?>
  <div class="wps_hide_two_block">
<?php } ?>        
                                <!-- Product Details Area -->
                                <div class="wps_product_details product_bottom mr_bottom wps_order_container">
                                    <?php
                                    // Include various template parts
                                    require $templatePartDir . 'title.php';
                                    require $templatePartDir . 'rating.php';
                                    require $templatePartDir . 'price.php';
                                    require $templatePartDir . 'progress.php';
                                    require $templatePartDir . 'instock.php';
                                    require $templatePartDir . 'offer_countdown.php';
									require $templatePartDir . 'offer_text.php';
                                    require $templatePartDir . 'category.php';
                                    require $templatePartDir . 'feature_addtocart.php';
                                    ?>
                                </div>
                                <!-- End Product Details Area -->
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
    </div>
</section>
