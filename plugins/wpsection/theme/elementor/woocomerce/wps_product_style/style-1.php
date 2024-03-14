<?php
/*
wps_product_basic Style-1
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
                clickable: true,
            },
        
            autoplay: {
                delay: 3000, // Set the delay (in milliseconds) between slides
            },
        });
		
        //put the code above the line 
    });
</script>

';

?>

<section class="mr_shop mr_products_one produt_section product_basic_one <?php echo $unique_id; ?>">
    <div class="auto-container">
        <div class="row row-5">
            <!-- While Loop Area -->
              <?php while ($query->have_posts()) : $query->the_post(); ?>
                <?php global $post, $product; ?>
                <!-- Product Column Start if WHILE LOOP -->
			
                <div class="wps_product_column <?php echo $columns_markup_print; ?> ">
					
                    <div class="mr_product_block product-block_hr_001">
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
                                <!-- End Thumbnail Area -->
                               
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
<?php  if ( 'top' === $settings['wps_columns_expand'] ) { ?>
  </div>
<?php } ?> 
                                <!-- End Product Bottom Area -->
                            </div>
                            <!-- End Product Style One -->
                        </div>
						

						
                </div>
					
					
                </div>
<?php endwhile; ?>

<?php
require $templatePartDir . 'pagination.php'; ?>
			
			


			
			
        </div>
    </div>
</section>



