<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $related_products ) : 
	
	if ( count($related_products) > get_option( 'glideffxf_related_visible' ) ) { 
		wp_enqueue_script( 'ffxf_glide' );
		wp_enqueue_script( 'ffxf_glide-init-carousel-related' );
		wp_enqueue_style( 'ffxf_glide-core' );
		wp_enqueue_style( 'ffxf_glide-theme' ); 
		
		add_action( 'wp_footer', 'ffxf_action_footer_related', 99 );
		function ffxf_action_footer_related(){ ?>

			<script>
				var element_two = document.getElementById('carusel_poduct_related');
					if(element_two){
						var carusel_poduct_related = new Glide('#carusel_poduct_related', {

							type: 'carousel',

							perView: <?php echo get_option( 'glideffxf_related_visible' ); ?>,

							<?php if ( get_option( 'glideffxf_related_autoplay' ) === "yes" ){ ?>
                            autoplay: <?php echo get_option( 'glideffxf_related_interval' ); ?>,
                            hoverpause: <?php if ( get_option( 'glideffxf_related_hover_stop' ) === "no") { echo 'false'; } else { echo 'true'; } ?>,
							<?php } ?>

							<?php if ( get_option( 'glideffxf_related_center_mode' ) === "yes" and get_option( 'glideffxf_related_center_mode_mobile' ) === "no" ){ ?>
                            peek: { before: <?php echo get_option( 'glideffxf_related_center_mode_left'); ?>, after: <?php echo get_option( 'glideffxf_related_center_mode_right'); ?> },
							<?php } ?>

							<?php if ( get_option( "glideffxf_related_animation" ) ){ ?>
                            animationTimingFunc: '<?php echo get_option( "glideffxf_related_animation" ); ?>',
							<?php } ?>

							<?php if ( get_option( "glideffxf_releted_animationDuration" ) ){ ?>
                            animationDuration: <?php echo get_option( "glideffxf_releted_animationDuration" ); ?>,
							<?php } ?>

                            <?php if ( get_option( "glideffxf_releted_gap" ) ){ ?>
								gap: <?php echo get_option( "glideffxf_releted_gap" ); ?>,
							<?php } ?>

							arrows: 'glide__bullet--active',

							breakpoints: {
								1199: {
									perView: <?php echo get_option( 'glideffxf_related_visible' ); ?>
								},
								992: {
									perView: <?php echo get_option( 'glideffxf_related_td' ); ?>
								},
								768: {
									perView: <?php echo get_option( 'glideffxf_related_td' ); ?>
								},
								414: {
									perView: <?php echo get_option( 'glideffxf_related_md' ); ?>,
									<?php if ( get_option( 'glideffxf_related_center_mode' ) === "yes" and get_option( 'glideffxf_related_center_mode_mobile' ) === "yes" ){ ?>
                                    peek: { before: <?php echo get_option( 'glideffxf_related_center_mode_left'); ?>, after: <?php echo get_option( 'glideffxf_related_center_mode_right'); ?> }
									<?php } ?>
								}
							},
							afterInit: function (event) {
								coverflow(event.index, event.current);
							},
							afterTransition: function (event) {
								coverflow(event.index, event.current);
							}
						})
						
						<?php if ( get_option( 'glideffxf_releted_javascript_fix' ) === "yes" ){ ?>
							document.addEventListener('DOMContentLoaded', function(){
								carusel_poduct_related.mount()
                        	}, false);
						<?php }else{ ?>
							carusel_poduct_related.mount()
						<?php } ?>
					
					}
			</script>
		<?php } ?>

<section class="related products">



	<h2><?php echo get_option( 'glideffxf_related_title' ); ?></h2>

    <?php if ( get_option( 'glideffxf_related_mobile_notification' ) === 'yes' ){ ?>
        <div class="mobile_notification" style="border-color: <?php echo get_option( 'glideffxf_related_mobile_tooltip_color' ); ?>">
            <p style="color:<?php echo get_option( 'glideffxf_related_mobile_tooltip_color' ); ?>">
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 368.416 368.416" style="enable-background:new 0 0 368.416 368.416;" xml:space="preserve"><g><path fill="<?php echo get_option( 'glideffxf_related_mobile_tooltip_color' ); ?>" d="M262.843,44.673h37.364c-3.809,3.913-3.782,10.169,0.091,14.042c1.953,1.952,4.512,2.929,7.071,2.929c2.559,0,5.119-0.977,7.071-2.929l16.971-16.971c3.905-3.905,3.905-10.237,0-14.143l-16.971-16.971c-3.905-3.904-10.237-3.904-14.143,0c-3.873,3.873-3.899,10.129-0.091,14.042h-37.364c-5.523,0-10,4.477-10,10C252.843,40.196,257.321,44.673,262.843,44.673z"/><path fill="<?php echo get_option( 'glideffxf_related_mobile_tooltip_color' ); ?>" d="M324.341,82.148h-37.364c3.809-3.913,3.782-10.169-0.091-14.042c-3.905-3.904-10.237-3.904-14.143,0l-16.971,16.971c-3.905,3.905-3.905,10.237,0,14.143l16.971,16.971c1.953,1.952,4.512,2.929,7.071,2.929s5.119-0.977,7.071-2.929c3.873-3.873,3.899-10.129,0.091-14.042h37.364c5.523,0,10-4.477,10-10C334.341,86.625,329.863,82.148,324.341,82.148z"/><path fill="<?php echo get_option( 'glideffxf_related_mobile_tooltip_color' ); ?>" d="M279.421,154.193c-5.573,0-11.22,1.072-16.508,3.065c-7.351-14.431-22.344-24.338-39.604-24.338c-6.153,0-12.015,1.165-17.344,3.271c-7.494-14.005-22.274-23.558-39.244-23.558c-4.251,0-8.415,0.594-12.393,1.743c0.003-4.005,0.006-8.109,0.009-12.229l0.03-41.719c0.008-11.525,0.011-16.671-0.031-19.242h0.042C154.378,18.861,134.116,0,110.133,0C85.611,0,65.661,19.95,65.661,44.472v135.569c-16.375,5.479-30.829,20.592-31.436,42.169c-0.95,33.752,1.817,76.462,31.894,107.397c25.39,26.115,65.127,38.81,121.481,38.81c50.685,0,88.17-14.908,111.415-44.311c16.326-20.651,24.955-48.48,24.955-80.48l-0.078-44.995C323.892,174.143,303.942,154.193,279.421,154.193z M187.6,348.416c-119.309,0-135.217-60.477-133.383-125.644c0.442-15.717,13.954-25,25-25v28.823c0,3.625,2.514,4.047,3.264,4.047s3.18-0.412,3.18-4.038c0-3.499,0-182.132,0-182.132C85.661,30.957,96.617,20,110.133,20c12.401,0,24.246,9.583,24.246,21.188c0.007,0.045-0.064,89.86-0.077,111.957c-0.001,0.046-0.013,0.089-0.013,0.135v4.617c0,2.201,1.785,3.985,3.986,3.985c2.2,0,3.984-1.784,3.984-3.985v-1.194c0.217-13.328,11.083-24.067,24.463-24.067c13.515,0,24.472,10.957,24.472,24.473l0.019,17.721c0,2.168,1.757,3.925,3.925,3.925c2.167,0,3.923-1.757,3.923-3.925l-0.008-1.014c0-12.305,11.956-20.894,24.256-20.894c13.517,0,24.422,10.956,24.422,24.472l0.049,17.937c0,2.109,1.71,3.819,3.819,3.819c2.109,0,3.818-1.71,3.818-3.819l-0.014-1.365c0-11.19,12.108-19.77,24.017-19.77c13.514,0,24.472,10.957,24.472,24.472l0.078,44.96C303.97,285.44,287.708,348.416,187.6,348.416z"/></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
                    <?php echo __( 'Swipe carousel to the left or to the right while holding your finger on the display.', 'carousel-upsells-and-related-product-for-woocommerce' ); ?>
            </p>
        </div>
    <?php } ?>


	<div class="carusel_block">
		<div class="glide" id="carusel_poduct_related">
			<div data-glide-el="track" class="glide__track">
				<ul class="glide__slides products columns-4">

					<?php foreach ($related_products as $related_product) : ?>

					<?php
					$post_object = get_post($related_product->get_id());

					setup_postdata($GLOBALS['post'] =& $post_object);

					wc_get_template_part('content', 'product'); ?>

					<?php endforeach; ?>
					
				</ul>
			</div>
			<div class="glide__arrows" data-glide-el="controls">
				<?php if ( get_option( 'glideffxf_releted_navigation' ) ){
					$glideffxf_ar_left = plugins_url( 'assets/img/' . get_option( 'glideffxf_releted_navigation' ) . 'left.svg' , dirname(__FILE__)  );
					$glideffxf_ar_right = plugins_url( 'assets/img/' . get_option( 'glideffxf_releted_navigation') . 'right.svg' , dirname(__FILE__) );
				}else{
					$glideffxf_ar_left = plugins_url( 'assets/img/one_left.svg' , dirname(__FILE__)  );
					$glideffxf_ar_right = plugins_url( 'assets/img/one_right.svg' , dirname(__FILE__) );
				} ?>

				<div style="background-color:<?php echo get_option( 'glideffxf_releted_picker' ); ?>;" class="glide__arrow glide__arrow--left" data-glide-dir="&lt;"><img src="<?php echo $glideffxf_ar_left; ?>" alt=""></div>
                <div style="background-color:<?php echo get_option( 'glideffxf_releted_picker' ); ?>;" class="glide__arrow glide__arrow--right" data-glide-dir="&gt;"><img src="<?php echo $glideffxf_ar_right; ?>" alt=""></div>
			</div>
		</div>
	</div>
</section>

<?php } else { ?>

<section class="related products">

	<h2><?php echo get_option( 'glideffxf_related_title' ); ?></h2>

	<?php woocommerce_product_loop_start(); ?>

	<?php foreach ( $related_products as $related_product ) : ?>

	<?php $post_object = get_post( $related_product->get_id() );
	
 			setup_postdata( $GLOBALS['post'] =& $post_object );
			wc_get_template_part( 'content', 'product' ); ?>

	<?php endforeach; ?>

	<?php woocommerce_product_loop_end(); ?>

</section>

<?php } ?>

<?php endif;

wp_reset_postdata();