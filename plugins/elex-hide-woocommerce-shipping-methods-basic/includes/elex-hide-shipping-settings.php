<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ELEX_Hide_Shipping_Setting {
	public function __construct() {
		$this->elex_hs_load_assets();
		$this->elex_hs_display_tabs();
	}
	public function elex_hs_load_assets() {
		wp_nonce_field( 'elex_hs_ajax_nonce', '_elex_hs_ajax_nonce' );
		global $woocommerce;
		$woocommerce_version = function_exists( 'WC' ) ? WC()->version : $woocommerce->version;
		wp_enqueue_style( 'woocommerce_admin_styles', $woocommerce->plugin_url() . '/assets/css/admin.css', array(), $woocommerce_version );
		wp_register_style( 'elex-hs-plugin-bootstrap', plugins_url( '/assets/css/bootstrap.css', dirname( __FILE__ ) ), array(), $woocommerce_version );
		wp_enqueue_style( 'elex-hs-plugin-bootstrap' );
		wp_register_style( 'elex-hs-plugin-styles', plugins_url( '/assets/css/elex-hs-styles.css', dirname( __FILE__ ) ), array(), $woocommerce_version );
		wp_enqueue_style( 'elex-hs-plugin-styles' );
		wp_register_script( 'elex-hs-tooltip-jquery', plugins_url( '/assets/js/tooltip.js', dirname( __FILE__ ) ), array(), $woocommerce_version );
		wp_enqueue_script( 'elex-hs-tooltip-jquery' );
		wp_register_script( 'elex-chosen-jquery', plugins_url( '/assets/js/chosen.jquery.js', dirname( __FILE__ ) ), array(), $woocommerce_version );
		wp_enqueue_script( 'elex-chosen-jquery' );
		wp_register_script( 'elex-hs-jquery', plugins_url( '/assets/js/elex-hs-script.js', dirname( __FILE__ ) ), array(), $woocommerce_version );
		wp_enqueue_style( 'elex-gpf-bootstrap', plugins_url( 'resources/css/elex-market-styles.css', dirname( __FILE__ ) ), array(), $woocommerce_version );
		$js_var = array(
			'elex_order_weight_min_range' => esc_html__( 'Min Value', 'elex-hide-shipping-methods' ),
			'elex_order_weight_max_range' => esc_html__( 'Max Value', 'elex-hide-shipping-methods' ),
			'elex_order_weight_value' => esc_html__( 'Enter Value', 'elex-hide-shipping-methods' ),
			'elex_states' => esc_html__( 'States', 'elex-hide-shipping-methods' ),
			'elex_states_placeholder' => esc_html__( 'Select States', 'elex-hide-shipping-methods' ),
			'elex_filter_state' => esc_html__( 'Choose the shipping destination states which you want to hide the shipping methods.', 'elex-hide-shipping-methods' ),
		);
		wp_localize_script( 'elex-hs-jquery', 'elex_hs_js_texts', $js_var );
		wp_enqueue_script( 'elex-hs-jquery' );
	}

	public function elex_hs_display_tabs() {
		$current_tab = 'elex_hs_create_rule';
		echo '
					<script>
					jQuery(function($){
					show_selected_tab($(".tab_elex_hs_create_rule"),"elex_hs_create_rule");
					$(".tab_elex_hs_create_rule").on("click",function() {
						return show_selected_tab($(this),"elex_hs_create_rule");
					});
					
					$(".tab_elex_hs_manage_rule").on("click",function() {
						return show_selected_tab($(this),"elex_hs_manage_rule");
					});
					$(".tab_elex_hs_go_premium").on("click",function() {
						return show_selected_tab($(this),"elex_hs_go_premium");
					});
					$(".elex_hs_advanced_rule").on("click",function() {
						return show_selected_tab($(this),"elex_hs_advanced_rule");
					});
				   
					function show_selected_tab($element,$tab) {
						$(".nav-tab").removeClass("nav-tab-active");
						$element.addClass("nav-tab-active");
						$(".elex_hs_create_rule_tab_field").closest("tr,h3").hide();
						$(".elex_hs_create_rule_tab_field").next("p").hide();
										 
						$(".elex_hs_manage_rule_tab_field").closest("tr,h3").hide();
						$(".elex_hs_manage_rule_tab_field").next("p").hide();
						
						$(".tab_elex_hs_advanced_rule").on("click",function() {
							return show_selected_tab($(this),"elex_hs_advanced_rule");
						});
						
						$("."+$tab+"_tab_field").closest("tr,h3").show();
						$("."+$tab+"_tab_field").next("p").show();
						
						
						if($tab=="elex_hs_create_rule") {
								$(".elex-hs-all-step").show();
								$("#elex_hs_step2").removeClass("active");
								$("#elex_hs_step1").addClass("active");
							$("#elex_hs_filter_div").show();
								$("#elex_hs_hide_shipping_div").hide();
						}
						else {
								$(".elex-hs-all-step").hide();
							$("#elex_hs_filter_div").hide();
								$("#elex_hs_hide_shipping_div").hide();
						}
						if($tab=="elex_hs_manage_rule") {
							$("#elex_hs_manage_rule_div").show();
							$("#elex_show_rule_btn").show();
							$("#elex_show_adv_rule_btn").show();
							$("#elex_show_adv_rule_btn").css("color","#0073aa");
         					$("#elex_show_rule_btn").css("color","black");
						}
						else {
							$("#elex_hs_manage_rule_div").hide();
							$("#elex_hs_manage_adv_rule_div").hide();
							$("#elex_show_rule_btn").hide();
							$("#elex_show_adv_rule_btn").hide();
						}
						if($tab=="elex_hs_advanced_rule") {
							$("#elex_hs_adv_div").show();
						}
						else {
							$("#elex_hs_adv_div").hide();
						}

						if($tab=="elex_hs_go_premium") {
							$("#elex_hs_market_content").show();
						}
						else {
							$("#elex_hs_market_content").hide();
						}
						
						return false;
					}   
					});
					</script>
					<style>
				   
					a.nav-tab{
								cursor: default;
					}
					</style>
					<hr class = "wp-header-end">';
		$tabs = array(
			'elex_hs_create_rule' => esc_html__( 'Create Rule', 'elex-hide-shipping-methods' ),
			'elex_hs_advanced_rule' => esc_html__( 'Advanced Rule', 'elex-hide-shipping-methods' ) . '<span class="go_premium_color">[Premium]</span>',
			'elex_hs_manage_rule' => esc_html__( 'Manage Rules', 'elex-hide-shipping-methods' ),
			'elex_hs_go_premium' => "<span style='color:red;'>" . esc_html__( 'Go Premium!', 'elex-hide-shipping-methods' ) . '</span>',
		);
		$html = '<h2 class="nav-tab-wrapper">';
		foreach ( $tabs as $stab => $name ) {
			$class = ( $stab == $current_tab ) ? 'nav-tab-active' : '';
			$html .= '<a style="text-decoration:none !important;" class="nav-tab ' . $class . ' tab_' . $stab . '" >' . $name . '</a>';
		}
		$html        .= '</h2>';
		$allowed_html = wp_kses_allowed_html( 'post' );
		echo wp_kses( $html, $allowed_html );
		?>
		<div class='elex-hs-all-step'>
			<div id ="elex_hs_step1" class="elex-hs-steps active">
				<?php echo esc_html_e( 'STEP 1: Set the Filter', 'elex-hide-shipping-methods' ); ?>
			</div>
			<div id ="elex_hs_step2" class="elex-hs-steps">
				<?php echo esc_html_e( 'STEP 2: Shipping Methods to Hide', 'elex-hide-shipping-methods' ); ?>
			</div>
		</div>
		<?php
	}
}

new ELEX_Hide_Shipping_Setting();
require_once ELEX_HIDE_SHIPPING_METHODS_TEMPLATE_PATH . '/elex-template-create-rule.php';
require_once  'market.php' ;
