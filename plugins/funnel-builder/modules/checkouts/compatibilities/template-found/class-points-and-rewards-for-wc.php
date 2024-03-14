<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Points and Rewards for WooCommerce By MakeWebBetter
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Point_Rewards_For_WC
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Point_Rewards_For_WC {
	public function __construct() {        /* checkout page */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'actions' ] );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );
	}

	public function actions() {
		add_action( 'woocommerce_before_checkout_form', [ $this, 'add_points_rewards' ], 100 );
	}

	public function add_points_rewards() {
		$public_obj = new Points_Rewards_For_WooCommerce_Public( 'points-rewads-for-woocommerce', '1.0.0' );
		echo "<div id='wfacp_custom_point_checkout_wrap' class='wfacp_clearfix'>";
		if ( method_exists( $public_obj, 'mwb_wpr_display_apply_points_checkout' ) ) {
			$public_obj->mwb_wpr_display_apply_points_checkout();
		} else if ( method_exists( $public_obj, 'wps_wpr_display_apply_points_checkout' ) ) {
			$public_obj->wps_wpr_display_apply_points_checkout();
		}
		echo "</div>";
	}

	public function internal_css() {


		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}

		$bodyClass = "body ";

		if ( 'pre_built' !== $instance->get_template_type() ) {
			$bodyClass = "body #wfacp-e-form .wfacp_main_form.woocommerce ";

		}


		$cssHtml = "<style>";
		$cssHtml .= $bodyClass . "#wfacp_custom_point_checkout_wrap .custom_point_checkout { width: 100%;background: transparent;}";
		$cssHtml .= $bodyClass . "#wfacp_custom_point_checkout_wrap {margin: 20px 0;}";

		$cssHtml .= $bodyClass . "#wfacp_custom_point_checkout_wrap .custom_point_checkout #mwb_cart_points,#wfacp_custom_point_checkout_wrap .custom_point_checkout input#wps_cart_points{font-size: 14px;line-height: 1.5;background-color: #ffffff;border-radius: 4px;position: relative;color: #404040;display: inline-block;padding: 12px 12px 10px;vertical-align: top;box-shadow: none;opacity: 1;border: 1px solid #bfbfbf;width: calc(100% - 170px);min-height: 52px;margin-right:5px;} ";
		$cssHtml .= $bodyClass . "#wfacp_custom_point_checkout_wrap .custom_point_checkout button:hover {background-color: #878484;}";
		$cssHtml .= $bodyClass . "#wfacp_custom_point_checkout_wrap .custom_point_checkout button {font-size: 14px;cursor: pointer;background-color: #999999;color: #ffffff;text-decoration: none;font-weight: normal;line-height: 18px;margin-bottom: 0;padding: 10px 20px;border: 1px solid rgba(0, 0, 0, 0.1);border-radius: 4px;width: 100%;max-width: 160px;min-height: 52px;margin: 0;display: inline-block;float:none}";
		$cssHtml .= $bodyClass . "#wfacp_custom_point_checkout_wrap #mwb_cart_points_apply ~ p,#wfacp_custom_point_checkout_wrap .custom_point_checkout button ~ p {margin-top: 5px;color: #737373;float: none;clear: both;}";


		$cssHtml .= "</style>";

		echo $cssHtml;
		?>
        <style>
            @media (max-width: 767px) {
            <?php echo $bodyClass; ?> #wfacp_custom_point_checkout_wrap .custom_point_checkout #mwb_cart_points,
            <?php echo $bodyClass; ?> #wfacp_custom_point_checkout_wrap .custom_point_checkout input#wps_cart_points {margin: 0 0 10px;width: 100%;}
            <?php echo $bodyClass; ?> #wfacp_custom_point_checkout_wrap .custom_point_checkout button {width: 100%;display: block;max-width: 100%;float: none;}
            }
        </style>
		<?php


	}

}


WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Point_Rewards_For_WC(), 'prfwc' );
