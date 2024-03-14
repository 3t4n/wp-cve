<?php
	// Reset Data
	add_filter(
		'plugin_row_meta',
		/**
		 * Add links below the description on the Plugins page.
		 *
		 * @param array $links
		 * @param string $file
		 * @retun array
		 */
		function( $links, $file ) {

			if ( INS_BASE_LOCATION !== $file ) {
				return $links;
			}

			return array_merge(
				$links,
				array(
					sprintf(
						'<a target="_blank" href="%1$s">%2$s</a>',
						'https://themefic.com/docs/instantio/',
						__( 'Documentation', 'instantio' )
					),
					sprintf(
						'<a target="_blank" href="%1$s">%2$s</a>',
						'https://portal.themefic.com/support/',
						__( 'Get help', 'instantio' )
					),
					sprintf(
						'<a target="_blank" href="%1$s">%2$s</a>',
						'https://themefic.com/feature-request/',
						__( 'Request a feature', 'instantio' )
					),
					sprintf(
						'<a target="_blank" href="%1$s">%2$s</a>',
						'https://portal.themefic.com/support/',
						__( 'Submit a bug', 'instantio' )
					),
				)
			);
		},
		10,
		2
	);

	// Reset Data
	add_action( 'wp_ajax_nopriv_ins_del_billing_fields', 'ins_del_billing_fields'  );  
	add_action( 'wp_ajax_ins_del_billing_fields', 'ins_del_billing_fields');
	add_action( 'wp_ajax_nopriv_ins_del_shipping_fields', 'ins_del_shipping_fields'  );  
	add_action( 'wp_ajax_ins_del_shipping_fields', 'ins_del_shipping_fields');

	function insopt( $option = '', $default = null ) {
		$options = get_option( 'wiopt' ); 
		return ( isset( $options[$option] ) ) ? $options[$option] : $default;
	}

	/**
	 * Check Pro Active or not
	 * @since 3.1.6
	 * @author M Hemel Hasan
	 * @return bool
	 */
	function is_ins_pro_active() {
		if ( is_plugin_active( 'wooinstant/wooinstant.php' ) && class_exists('WOOINS') ) {
			return true;
		}
		return false;
	}

	function ins_del_billing_fields() {
		$ins_billing_fields = get_option('wiopt');

		if (isset($ins_billing_fields['checkout_editors_fields'])) {
			// Remove the 'checkout_editors_fields' key from the 'wiopt' option
			unset($ins_billing_fields['checkout_editors_fields']);
		
			// Update the 'wiopt' option without the 'checkout_editors_fields' key
			update_option('wiopt', $ins_billing_fields);
		}

	}



	function ins_del_shipping_fields() {
		$ins_shipping_fields = get_option('wiopt');

		if (isset($ins_shipping_fields['checkout_shiping_editors_fields'])) {
			// Remove the 'checkout_shiping_editors_fields' key from the 'wiopt' option
			unset($ins_shipping_fields['checkout_shiping_editors_fields']);
		
			// Update the 'wiopt' option without the 'checkout_shiping_editors_fields' key
			update_option('wiopt', $ins_shipping_fields);
		}

	}


	/**
	 * Black Friday Deals 2023
	 */
	if(!function_exists('tf_black_friday_2023_admin_notice')){
		function tf_black_friday_2023_admin_notice(){ 
			if(is_ins_pro_active()){
				return;
			}

			$expiration_time = time() + 3 * 60 * 60;  
        	$tf_display_admin_notice_time = get_option( 'tf_display_admin_notice_time' );

        	if($tf_display_admin_notice_time == ''){
            	update_option( 'tf_display_admin_notice_time', $expiration_time );
        	}

			$deal_link =sanitize_url('https://themefic.com/deals/');
			$tf_display_admin_notice_time = get_option( 'tf_display_admin_notice_time' );
			$get_current_screen = get_current_screen();  
			if(!isset($_COOKIE['tf_dismiss_admin_notice']) && $get_current_screen->base == 'dashboard' && time() > $tf_display_admin_notice_time){ 
				?>
				<style> 
					.tf_black_friday_20222_admin_notice a:focus {
						box-shadow: none;
					} 
					.tf_black_friday_20222_admin_notice {
						padding: 7px;
						border: none;
						background: transparent;
						position: relative;
						z-index: 10;
						max-width: 825px;
					} 
					.tf_black_friday_20222_admin_notice button:before {
						color: #fff !important;
					}
					.tf_black_friday_20222_admin_notice button:hover::before {
						color: #d63638 !important;
					}
				</style>
				<div class="notice notice-success tf_black_friday_20222_admin_notice"> 
					<a href="<?php echo $deal_link; ?>" target="_blank" >
						<img  style="width: 100%;" src="<?php echo esc_url( 'https://themefic.com/wp-content/uploads/2023/11/Themefic_BlackFriday_rectangle_banner.png') ?>" alt="">
					</a> 
					<button type="button" class="notice-dismiss tf_black_friday_notice_dismiss"><span class="screen-reader-text"><?php echo __('Dismiss this notice.', 'instantio' ) ?></span></button>
				</div>
				<script>
					jQuery(document).ready(function($) {
						$(document).on('click', '.tf_black_friday_notice_dismiss', function( event ) {
							jQuery('.tf_black_friday_20222_admin_notice').css('display', 'none')
							data = {
								action : 'tf_black_friday_notice_dismiss_callback',
							};
							$.ajax({
								url: ajaxurl,
								type: 'post',
								data: data,
								success: function (data) { ;
								},
								error: function (data) { 
								}
							});
						});
					});
				</script>
			
			<?php 
			}
			
		} 
		if (strtotime('2023-12-01') > time()) {
			add_action( 'admin_notices', 'tf_black_friday_2023_admin_notice' );  
		}   
	}
	if(!function_exists('tf_black_friday_notice_dismiss_callback')){
		function tf_black_friday_notice_dismiss_callback() { 
			$cookie_name = "tf_dismiss_admin_notice";
			$cookie_value = "1"; 
			setcookie($cookie_name, $cookie_value, strtotime('2023-12-01'), "/"); 
			update_option( 'tf_display_admin_notice_time', '1' );
			wp_die();
		}
		add_action( 'wp_ajax_tf_black_friday_notice_dismiss_callback', 'tf_black_friday_notice_dismiss_callback' );
	}


	//product pages 
	if ( ! function_exists( 'tf_black_friday_2023_woo_product' ) ) {
		function tf_black_friday_2023_woo_product() {
			if ( ! isset( $_COOKIE['tf_black_friday_sidbar_notice'] ) ) {
				add_meta_box( 'tf_black_friday_annous', __( ' ', 'instantio' ), 'tf_black_friday_2023_callback_woo_product', 'product', 'side', 'high' );
			}
		}
	
		if ( strtotime( '2023-12-01' ) > time() && !is_plugin_active( 'wooinstant/wooinstant.php' ) ) {
			add_action( 'add_meta_boxes', 'tf_black_friday_2023_woo_product' );
		}
		function tf_black_friday_2023_callback_woo_product() {
			$deal_link = sanitize_url( 'https://themefic.com/deals' );
			?>
			<style>
				#tf_black_friday_annous{
					border: 0px solid;
					box-shadow: none;
					background: transparent;
				}
				.back_friday_2023_preview a:focus {
					box-shadow: none;
				}
	
				.back_friday_2023_preview a {
					display: inline-block;
				}
	
				#tf_black_friday_annous .inside {
					padding: 0;
					margin-top: 0;
				}
	
				#tf_black_friday_annous .postbox-header {
					display: none;
					visibility: hidden;
				}
			</style>
			<div class="back_friday_2023_preview ins-bf-preview" style="text-align: center; overflow: hidden;">
				<button type="button" class="notice-dismiss tf_hotel_friday_notice_dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
				<a href="<?php echo $deal_link; ?>" target="_blank">
					<img style="width: 100%;" src="<?php echo esc_url('https://themefic.com/wp-content/uploads/2023/11/Instantio_BlackFriday_Square_banner.png') ?>" alt="">
				</a>
				<script>
				jQuery(document).ready(function($) {
					$(document).on('click', '.tf_hotel_friday_notice_dismiss', function( event ) { 
						jQuery('.ins-bf-preview').css('display', 'none')
						var cookieName = "tf_black_friday_sidbar_notice";
						var cookieValue = "1";
	
						// Create a date object for the expiration date
						var expirationDate = new Date();
						expirationDate.setTime(expirationDate.getTime() + (5 * 24 * 60 * 60 * 1000)); // 5 days in milliseconds
	
						// Construct the cookie string
						var cookieString = cookieName + "=" + cookieValue + ";expires=" + expirationDate.toUTCString() + ";path=/";
	
						// Set the cookie
						document.cookie = cookieString;
					});
				});
				</script>
			</div>
			<?php
		}
	}

	add_filter( 'get_user_option_meta-box-order_product', 'metabox_order' );
	function metabox_order( $order ) {
		return array(
			'side' => join( 
				",", 
				array(       // vvv  Arrange here as you desire
					'submitdiv',
					'tf_black_friday_annous',
				)
			),
		);
	}

?>