<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
add_action('plugins_loaded', 'wpsm_test_tr');
function wpsm_test_tr() {
	load_plugin_textdomain( wpshopmart_test_b_text_domain, FALSE, dirname( plugin_basename(__FILE__)).'/languages/' );
}

function wpsm_test_b_front_script() {
	wp_enqueue_style('wpsm_test_b_bootstrap-front', wpshopmart_test_b_directory_url.'assets/css/bootstrap-front.css');
	wp_enqueue_style('wpsm_test_b_style-1', wpshopmart_test_b_directory_url.'assets/css/style-1.css');
	wp_enqueue_style('wpsm_test_b_style-2', wpshopmart_test_b_directory_url.'assets/css/style-2.css');
	wp_enqueue_style('wpsm_test_b_owl_carousel_min_css', wpshopmart_test_b_directory_url.'assets/css/owl.carousel.min.css');
		//JS
	wp_enqueue_script( 'wpsm_test_b_owl.carousel.min.js', wpshopmart_test_b_directory_url.'assets/js/owl.carousel.min.js',array('jquery'),true);
}

add_action( 'wp_enqueue_scripts', 'wpsm_test_b_front_script' );
add_filter( 'widget_text', 'do_shortcode');

add_action( 'admin_notices', 'wpsm_testi_b_review' );
function wpsm_testi_b_review() {

	// Verify that we can do a check for reviews.
	$review = get_option( 'wpsm_testi_b_review' );
	$time	= time();
	$load	= false;
	if ( ! $review ) {
		$review = array(
			'time' 		=> $time,
			'dismissed' => false
		);
		add_option('wpsm_testi_b_review', $review);
		//$load = true;
	} else {
		// Check if it has been dismissed or not.
		if ( (isset( $review['dismissed'] ) && ! $review['dismissed']) && (isset( $review['time'] ) && (($review['time'] + (DAY_IN_SECONDS * 2)) <= $time)) ) {
			$load = true;
		}
	}
	// If we cannot load, return early.
	if ( ! $load ) {
		return;
	}	
	?>
	<div class="notice notice-info is-dismissible wpsm-testi-b-review-notice" style="padding:15px 0 35px 0; margin-bottom: 15px;">
		<div style="float:left;margin-right:10px;margin-bottom:5px;">
			<img style="width: 150px;height: auto;" src="<?php echo esc_url(wpshopmart_test_b_directory_url.'assets/images/icon-show.png'); ?>" />
		</div>
		<p style="font-size:17px;"><?php esc_html_e('Hi! We saw you have been using',wpshopmart_test_b_text_domain); ?><strong><?php esc_html_e('Testimonial Builder plugin',wpshopmart_test_b_text_domain); ?></strong><?php esc_html_e(' for a few days and wanted to ask for your help to ',wpshopmart_test_b_text_domain); ?><strong><?php esc_html_e('make the plugin better',wpshopmart_test_b_text_domain); ?></strong><?php esc_html_e('.We just need a minute of your time to rate the plugin. Thank you!',wpshopmart_test_b_text_domain); ?></p>
		<p style="font-size:17px;"><strong><?php _e( '~ wpshopmart', '' ); ?></strong></p>
		<p style="font-size:18px;"> 
			<a style="color: #fff;background: #ef4238;padding: 3px 11px 7px 11px;border-radius: 4px;text-decoration:none" href="https://wordpress.org/support/plugin/testimonial-builder/reviews/?filter=5#new-post" class="wpsm-testi-b-dismiss-review-notice wpsm-testi-b-review-out" target="_blank" rel="noopener">Rate the plugin</a>&nbsp; &nbsp;
			<a style="color: #fff;background: #27d63c;padding: 3px 11px 7px 11px;border-radius: 4px;text-decoration:none" href="#"  class="wpsm-testi-b-dismiss-review-notice wpsm-rate-later" target="_self" rel="noopener"><?php _e( 'Nope, maybe later', '' ); ?></a>&nbsp; &nbsp;
			<a style="color: #fff;background: #31a3dd;padding: 3px 11px 7px 11px;border-radius: 4px;text-decoration:none" href="#" class="wpsm-testi-b-dismiss-review-notice wpsm-rated" target="_self" rel="noopener"><?php _e( 'I already did', '' ); ?></a>
			
		</p>
	</div>
	<script type="text/javascript">
		jQuery(document).ready( function($) {
			$(document).on('click', '.wpsm-testi-b-dismiss-review-notice, .wpsm-testi-b-dismiss-notice .notice-dismiss', function( event ) {
				if ( $(this).hasClass('wpsm-testi-b-review-out') ) {
					var wpsm_rate_data_val = "1";
				}
				if ( $(this).hasClass('wpsm-rate-later') ) {
					var wpsm_rate_data_val =  "2";
					event.preventDefault();
				}
				if ( $(this).hasClass('wpsm-rated') ) {
					var wpsm_rate_data_val =  "3";
					event.preventDefault();
				}

				$.post( ajaxurl, {
					action: 'wpsm_testi_b_dismiss_review',
					wpsm_rate_data_testi_b : wpsm_rate_data_val
				});
				
				$('.wpsm-testi-b-review-notice').hide();
				//location.reload();
			});
		});
	</script>
	<?php
}

add_action( 'wp_ajax_wpsm_testi_b_dismiss_review', 'wpsm_testi_b_dismiss_review' );
function wpsm_testi_b_dismiss_review() {
	if ( ! $review ) {
		$review = array();
	}
	
	if($_POST['wpsm_rate_data_testi_b']=="1"){
		$review['time'] 	 = time();
		$review['dismissed'] = false;
		
	}
	if($_POST['wpsm_rate_data_testi_b']=="2"){
		$review['time'] 	 = time();
		$review['dismissed'] = false;
		
	}
	if($_POST['wpsm_rate_data_testi_b']=="3"){
		$review['time'] 	 = time();
		$review['dismissed'] = true;
		
	}
	update_option( 'wpsm_testi_b_review', $review );
	die;
}

function wpsm_testi_b_header_info() {
 	if(get_post_type()=="test_builder") {
		?>
		<style>
		@media screen and (max-width: 760px){
			.wpsm_ac_h_i{
				display:none;
				
			}
		}
		.wpsm_ac_h_i{
			background-color: #4916d7;
			background: -webkit-linear-gradient(60deg, #4916d7, #be94f8);
			background: linear-gradient(60deg, #4916d7, #be94f8);
			-webkit-box-shadow: 0px 13px 21px -10px rgba(128,128,128,1);
			-moz-box-shadow: 0px 13px 21px -10px rgba(128,128,128,1);
			box-shadow: 0px 13px 21px -10px rgba(128,128,128,1);			
			margin-left: -20px;
			padding-top:20px;
			overflow: hidden;
			text-align: center;
		}
		.wpsm_ac_h_i .wpsm_ac_h_b{
			color: white;
			font-size: 30px;
			font-weight: bolder;
			padding: 0 0 0px 0;
		}
		.wpsm_ac_h_i .wpsm_ac_h_b .dashicons{
			font-size: 40px;
			position: absolute;
			margin-left: -45px;
			margin-top: -10px;
		}
		 .wpsm_ac_h_small{
			font-weight: bolder;
			color: white;
			font-size: 18px;
			padding: 0 0 15px 15px;
		}
		.wpsm_ac_h_i a{
			text-decoration: none;
		}
		@media screen and ( max-width: 600px ) {
			.wpsm_ac_h_i{ padding-top: 60px; margin-bottom: -50px; }
			.wpsm_ac_h_i .WlTSmall { display: none; }
		}
		.texture-layer {
			background: rgba(0,0,0,0);
			padding-top: 0px;
			padding: 0 15px;
		}
		.wpsm_ac_h_i  ul{
			padding:0px 0px 0px 0px;
		}
		.wpsm_ac_h_i  li {
			text-align:left;
			color:#fff;
			font-size: 16px;
			line-height: 26px;
			font-weight: 600;
			
		}
		.wpsm_ac_h_i  li i{
			margin-right:6px ;
			margin-bottom:10px;	
			font-size: 12px;			
		}		 
		.wpsm_ac_h_i .btn-danger{
			font-size: 29px;
			background-color: #000000;
			border-radius:1px;
			margin-right:10px;
			margin-top: 0px;
			border-color:#000;
			 
		}
		.wpsm_ac_h_i .btn-success{
			font-size: 28px;
			border-radius:1px;
			background-color: #ffffff;
			border-color: #ffffff;
			color:#000;
		}
		.btn-danger {
			color: #fff;
			background-color: #01c698 !important;
			border-color: #01c698 !important;
		}
		.pad-o{
			padding-top: 15px;
			
		}

		</style>
			<div class="wpsm_ac_h_i ">
				<div class="row texture-layer">					
					<div class="col-md-3">
						<img src="<?php echo esc_url(wpshopmart_test_b_directory_url.'assets/images/testimonial-intro.png'); ?>"  class="img-fluid"/>		
					</div>
					
					<div class="col-md-9 row px-4">
						<div class="wpsm_ac_h_b col-md-6" style="text-align:left">
							<a class="btn btn-danger btn-lg " href="https://wpshopmart.com/plugins/testimonial-pro/" target="_blank"><?php esc_html_e('Buy Pro Version',wpshopmart_test_b_text_domain); ?></a><a class="btn btn-success btn-lg " href="http://demo.wpshopmart.com/testimonial-pro-demo/" target="_blank"><?php esc_html_e('View Demo',wpshopmart_test_b_text_domain); ?></a>
						</div>								
						<div class="col-md-6" style="text-align:left">							
							<h1 style="color:#fff;font-size:34px;font-weight:800"><?php esc_html_e('Testimonial Pro Features',wpshopmart_test_b_text_domain); ?></h1>							
						</div>					
						
						<a href="https://wpshopmart.com/plugins/testimonial-pro/" target="_blank">
						<div class="col-md-12 row" style="padding-bottom:20px;">
							
								<div class="col-md-3 pad-o">
									<ul>
										<li> <i class="fa fa-check"></i><?php esc_html_e('100+ Slider Templates',wpshopmart_test_b_text_domain); ?> </li>
										<li> <i class="fa fa-check"></i><?php esc_html_e('50+ Grid Templates',wpshopmart_test_b_text_domain); ?></li>
										<li> <i class="fa fa-check"></i><?php esc_html_e('4+ Video Testimonial Templates',wpshopmart_test_b_text_domain); ?></li>
										<li> <i class="fa fa-check"></i><?php esc_html_e('3d Testimonial Effect',wpshopmart_test_b_text_domain); ?></li>
										<li> <i class="fa fa-check"></i><?php esc_html_e('Front End Submission',wpshopmart_test_b_text_domain); ?></li>
										<li> <i class="fa fa-check"></i><?php esc_html_e('Filter Option',wpshopmart_test_b_text_domain); ?></li>
										
									</ul>
								</div>
								<div class="col-md-3 pad-o">
									<ul>
										<li><i class="fa fa-check"></i><?php esc_html_e('2+ Table Layouts',wpshopmart_test_b_text_domain); ?></li>
										<li><i class="fa fa-check"></i><?php esc_html_e('10+ Column Layout',wpshopmart_test_b_text_domain); ?></li>
										<li> <i class="fa fa-check"></i><?php esc_html_e('Add Website Link',wpshopmart_test_b_text_domain); ?></li>
										<li> <i class="fa fa-check"></i><?php esc_html_e('Add Email Link',wpshopmart_test_b_text_domain); ?> </li>								
										<li> <i class="fa fa-check"></i><?php esc_html_e('Add Ratings',wpshopmart_test_b_text_domain); ?></li>								
										<li> <i class="fa fa-check"></i><?php esc_html_e('Touch Slider Effect',wpshopmart_test_b_text_domain); ?> </li>								
									</ul>
								</div>
								<div class="col-md-3 pad-o">
									<ul>
										<li> <i class="fa fa-check"></i><?php esc_html_e('1000+ FontAwesome Icons',wpshopmart_test_b_text_domain); ?></li>
										<li> <i class="fa fa-check"></i><?php esc_html_e('5+ Dot navigation Style',wpshopmart_test_b_text_domain); ?></li>
										<li> <i class="fa fa-check"></i><?php esc_html_e('5+ Button Navigation Style',wpshopmart_test_b_text_domain); ?></li>
										<li> <i class="fa fa-check"></i><?php esc_html_e('GDPR Supported',wpshopmart_test_b_text_domain); ?></li>
										<li> <i class="fa fa-check"></i><?php esc_html_e('Quotes Icon Settings',wpshopmart_test_b_text_domain); ?> </li>
										
									</ul>
								</div>
								<div class="col-md-3 pad-o">
									<ul>
										<li> <i class="fa fa-check"></i><?php esc_html_e('500+ Google Fonts',wpshopmart_test_b_text_domain); ?> </li>
										<li> <i class="fa fa-check"></i><?php esc_html_e('Border Color Customization',wpshopmart_test_b_text_domain); ?> </li>
										<li> <i class="fa fa-check"></i><?php esc_html_e('Advanced Settings Panel',wpshopmart_test_b_text_domain); ?> </li>
										<li> <i class="fa fa-check"></i><?php esc_html_e('High Priority Support',wpshopmart_test_b_text_domain); ?> </li>
										<li> <i class="fa fa-check"></i><?php esc_html_e('All Browser Compatible',wpshopmart_test_b_text_domain); ?> </li>
									</ul>
								</div>
							
						</div>		
						</a>		
					</div>
				</div>
			
			</div>
		<?php  
	}
}
add_action('in_admin_header','wpsm_testi_b_header_info');
?>