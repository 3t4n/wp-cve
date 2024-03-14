<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       www.redefiningtheweb.com
 * @since      1.0.0
 *
 * @package    Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite
 * @subpackage Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php
	$rtwwdpdl_discount_rules_active 	= '';
	$rtwwdpdl_specific_customer_active 	= '';
	$rtwwdpdl_settings_active 			= '';
	$rtwwdpdl_coming_sale_active 		= '';
	$rtwwdpdl_plus_member_active 		= '';

	if( isset( $_GET[ 'rtwwdpdl_tab' ] ) )
	{
		if( $_GET[ 'rtwwdpdl_tab' ] == "rtwwdpdl_discount_rules" )
		{
			$rtwwdpdl_discount_rules_active = "nav-tab-active";
		}
		elseif( $_GET[ 'rtwwdpdl_tab' ] == "rtwwdpdl_specific_customer" )
		{
			$rtwwdpdl_specific_customer_active = "nav-tab-active";
		}
		elseif( $_GET[ 'rtwwdpdl_tab' ] == "rtwwdpdl_settings" ) 
		{
			$rtwwdpdl_settings_active = "nav-tab-active";
		}
		elseif( $_GET[ 'rtwwdpdl_tab' ] == "rtwwdpdl_coming_sale" ) 
		{
			$rtwwdpdl_coming_sale_active = "nav-tab-active";
		}
		elseif( $_GET[ 'rtwwdpdl_tab' ] == "rtwwdpdl_plus_member" ) 
		{
			$rtwwdpdl_plus_member_active = "nav-tab-active";
		}elseif ( $_GET[ 'rtwwdpdl_tab' ] == "rtwwdpdl_pro_section" ) {
			$rtwwdpdl_pro_section_active = "nav-tab-active";
		}
	}
	else
	{
		$rtwwdpdl_discount_rules_active = "nav-tab-active";
	}
	settings_errors();
?>

<div class="rtw_sb_popup">
	<div class="rtw_sb_card">
		<div class="rtw_sb_card_label">
			<label>Limited Period Offer</label>
		</div>
		<div class="rtw_sb_card_body">
			<div class="rtw_sb_close_popup">
				<div class="rtw_sb_close_icon"></div>
			</div>
			<h2>Get PRO @ 50% Off</h2>
			<a class="rtw_sb_link" href="https://codecanyon.net/item/woocommerce-dynamic-pricing-discounts-with-ai/24165502" target="_blank"> <button id="rtw_sb_banner_button">Buy Now</button></a>
			<p class="rtw_popper"><img src="<?php echo RTWWDPDL_URL.'/admin/images/party-popper.png'?>" alt="offer popper image"></p>
			<p class="rtw_sb_price">Just in <span><strike>$99</strike></span><span>$19</span></p>
			<p class="rtw_sb_bottom_text">* Hurry up offer valid till <span class="rtw_sb_date">10th March</span></p>
		</div>
	</div>
</div>

<div class="wrap rtwwdpdl">
	<h2 class="rtw-main-heading"><span><img src="<?php echo esc_url(RTWWDPDL_URL.'admin/images/Dynamic-Pricing-Discount-logo.png'); ?>" alt=""></span><?php esc_html_e( 'Dynamic Pricing & Discounts Lite', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?></h2>
	<nav class="nav-tab-wrapper">
		<a class="nav-tab <?php echo esc_attr($rtwwdpdl_discount_rules_active); ?>" href="<?php echo esc_url( get_admin_url() . 'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_discount_rules' );?>">
			<span><img src="<?php echo esc_url(RTWWDPDL_URL.'admin/images/Discount-Rules.png'); ?>" alt=""></span>
			<?php esc_html_e( 'Discount Rules', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
		</a>

		<a class="nav-tab <?php echo esc_attr($rtwwdpdl_specific_customer_active); ?>" href="<?php echo esc_url( get_admin_url() . 'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_specific_customer' );?>">
			<span><img src="<?php echo esc_url(RTWWDPDL_URL.'admin/images/Customer-Rules.png'); ?>" alt=""></span>
			<?php esc_html_e( 'Rules for Specific Customer', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
			<span class="dashicons dashicons-lock rtwwdpd_lock_icon"></span>
		</a>

		<a class="nav-tab <?php echo esc_attr($rtwwdpdl_settings_active); ?>" href="<?php echo esc_url( get_admin_url() . 'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_settings' );?>">
			<span><img src="<?php echo esc_url(RTWWDPDL_URL.'admin/images/Setting.png'); ?>" alt=""></span>
			<?php esc_html_e( 'Settings', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
		</a>

		<a class="nav-tab <?php echo esc_attr($rtwwdpdl_coming_sale_active); ?>" href="<?php echo esc_url( get_admin_url() . 'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_coming_sale' );?>">
			<span><img src="<?php echo esc_url(RTWWDPDL_URL.'admin/images/Coming-Sale.png'); ?>" alt=""></span>
			<?php esc_html_e( 'Coming Sale', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
			<span class="dashicons dashicons-lock rtwwdpd_lock_icon"></span>
		</a>
		
		<a class="nav-tab <?php echo esc_attr($rtwwdpdl_plus_member_active); ?>" href="<?php echo esc_url( get_admin_url() . 'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_plus_member' );?>">
			<span><img src="<?php echo esc_url(RTWWDPDL_URL.'admin/images/Plus-Members.png'); ?>" alt=""></span>
			<?php esc_html_e( 'Plus Members', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
			<span class="dashicons dashicons-lock rtwwdpd_lock_icon"></span>
		</a>

		<a class="nav-tab <?php echo esc_attr($rtwwdpdl_pro_section_active); ?>" href="<?php echo esc_url( get_admin_url() . 'admin.php?page=rtwwdpdl&rtwwdpdl_tab=rtwwdpdl_pro_section' );?>">
			<span><img src="<?php echo esc_url(RTWWDPDL_URL.'admin/images/compare.png'); ?>" alt=""></span>
			<?php esc_html_e( 'Compare With Pro', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ); ?>
		</a>
	</nav>
	<div class="main-wrapper">
	<?php
		if( isset( $_GET[ 'rtwwdpdl_tab' ] ) )
		{
			if( $_GET[ 'rtwwdpdl_tab' ] == "rtwwdpdl_discount_rules" ){
				include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_tabs/rtwwdpdl_discount_rules.php' );
			}
			elseif( $_GET[ 'rtwwdpdl_tab' ] == "rtwwdpdl_specific_customer" ){
				include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_tabs/rtwwdpdl_specific_customer.php' );
			}
			elseif( $_GET[ 'rtwwdpdl_tab' ] == "rtwwdpdl_settings" ){
				include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_tabs/rtwwdpdl_settings.php' );
			}
			elseif( $_GET[ 'rtwwdpdl_tab' ] == "rtwwdpdl_coming_sale" ){
				include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_tabs/rtwwdpdl_coming_sale.php' );
			}
			elseif( $_GET[ 'rtwwdpdl_tab' ] == "rtwwdpdl_plus_member" ){
				include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_tabs/rtwwdpdl_plus_member.php' );
			}
			elseif( $_GET[ 'rtwwdpdl_tab' ] == "rtwwdpdl_pro_section" ){
				include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_tabs/rtwwdpdl_pro_section.php' );
			}
		}
		else{
			include_once( RTWWDPDL_DIR . 'admin/partials/rtwwdpdl_tabs/rtwwdpdl_discount_rules.php' );
		}
	?>
	</div>
</div>