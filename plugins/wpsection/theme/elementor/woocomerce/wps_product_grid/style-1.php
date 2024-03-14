<?php
/*
wps_product_grid Style-1
This has Extanded All Input included
*/
$templatePartDir = __DIR__ . '/../template_part/';
$unique_id = 'product_basic_one_' . uniqid(); // Generate a unique ID
echo '
<script>

    window["hoverSliderOptions"] = { preloadImages: true };

    jQuery(document).ready(function($) { 
        //put the js code under this line 
        var swiper = new Swiper(".' . $unique_id . ' .mySwiper", {
            pagination: {
                el: ".' . $unique_id . ' .swiper-pagination",
                type: "fraction",
            },
            navigation: {
                nextEl: ".' . $unique_id . ' .swiper-button-next",
                prevEl: ".' . $unique_id . ' .swiper-button-prev",
            },
            autoplay: {
                delay: 3000, // Set the delay (in milliseconds) between slides
            },
        });
        //put the code above the line 
    });


</script>';
?>
<section class="mr_shop mr_products_one produt_section wps_grid_one <?php echo $unique_id; ?>">
    <div class="auto-container">
        <div class="row row-5"> 
            <!-- While Loop Area -->
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <?php global $post, $product; ?>
                <!-- Product Column Start if WHILE LOOP -->
                <div class="wps_product_column <?php echo $columns_markup; ?> col-md-6">
                    <div class="mr_product_block product-block_hr_001">
                        <!-- Global Settings -->
                        <?php require $templatePartDir . 'hook.php'; ?>
                        <div <?php wc_product_class(); ?>>
                            <!-- Product Style Start -->
                            <div class="mr_style_one wps_product_block_one">
                                <div class="row">
                                    
	
									
 
										
<div class="col-md-6 col-lg-<?php echo esc_attr($settings['grid_width_x']); ?> 	 <?php echo ('1' === $settings['grid_order']) ? 'wps_grid_left' : ' '; ?>">
										
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
                                        <!-- End Thumbnail Area -->
                                    </div>

                                    <div class="col-md-6 col-lg-<?php echo esc_attr(12 - $settings['grid_width_x']); ?> wps_grid-<?php echo esc_attr($settings['grid_order']); ?>">
										
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
											require $templatePartDir . 'offer_text.php';
                                            require $templatePartDir . 'category.php';
                                            require $templatePartDir . 'feature_addtocart.php';
                                            ?>
                                        </div>
                                        <!-- End Product Bottom Area -->
	  
 <?php  if ( 'top' === $settings['wps_columns_expand'] ) { ?>
  </div>
<?php } ?> 
                                    </div>

                                </div>
                            </div>
                            <!-- End Product Style One -->
                        </div>
                    </div>
                </div>
                <!-- End Product Column -->
            <?php endwhile; ?>
            <?php
require $templatePartDir . 'pagination.php'; ?>
        </div>
    </div>
</section>


