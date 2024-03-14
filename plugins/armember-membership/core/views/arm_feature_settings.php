<?php
global $wpdb, $ARMemberLite, $arm_slugs, $arm_social_feature,$myplugarr;
$ARMemberLite->arm_session_start();
$social_feature              = get_option( 'arm_is_social_feature' );
$user_private_content        = 0;
$social_login_feature        = 0;
$drip_content_feature        = 0;
$opt_ins_feature             = 0;
$coupon_feature              = 0;
$buddypress_feature          = 0;
$invoice_tax_feature         = 0;
$multiple_membership_feature = 0;
$arm_is_mycred_active        = 0;
$woocommerce_feature         = 0;
$arm_pay_per_post            = 0;
$arm_admin_mycred_feature    = 0;
$plan_limit_feature 	     = 0;
$arm_api_service_feature     = 0;
$gutenberg_block_restriction_feature = 0;
$beaver_builder_restriction_feature = 0;
$divi_builder_restriction_feature = 0;
$wpbakery_page_builder_restriction_feature = 0;




$featureActiveIcon = MEMBERSHIPLITE_IMAGES_URL . '/feature_active_icon.png';
if ( is_rtl() ) {
	$featureActiveIcon = MEMBERSHIPLITE_IMAGES_URL . '/feature_active_icon_rtl.png';
}
?>
<style>
	.purchased_info{
		color:#7cba6c;
		font-weight:bold;
		font-size: 15px;
	}
	.arperrmessage{color:red;}
	#wpcontent{
		background: #EEF2F8;
	}
	.arfnewmodalclose
	{
		font-size: 15px;
		font-weight: bold;
		height: 19px;
		position: absolute;
		right: 3px;
		top:5px;
		width: 19px;
		cursor:pointer;
		color:#D1D6E5;
	}
	.newform_modal_title { font-size:25px; line-height:25px; margin-bottom: 10px; }
	.newmodal_field_title { font-size: 16px;
	line-height: 16px;
	margin-bottom: 10px; }
</style>
<div class="wrap arm_page arm_feature_settings_main_wrapper">
	<div class="content_wrapper arm_feature_settings_content" id="content_wrapper">
		<div class="page_title"><?php esc_html_e( 'Additional Membership Modules', 'armember-membership' ); ?></div>
		<div class="armclear"></div>
		<div class="arm_feature_settings_wrapper">            
			<div class="arm_feature_settings_container">
				<div class="arm_feature_list social_enable <?php echo ( $social_feature == 1 ) ? 'active' : ''; ?>">
					<div class="arm_feature_icon"></div>
					<div class="arm_feature_active_icon"><div class="arm_check_mark"></div></div>
					<div class="arm_feature_content">
						<div class="arm_feature_title"><?php esc_html_e( 'Social Feature', 'armember-membership' ); ?></div>
						<div class="arm_feature_text"><?php esc_html_e( 'With this feature, enable social activities like Member Directory/Public Profile, Social Profile Fields etc.', 'armember-membership' ); ?></div>
						
						<div class="arm_feature_button_activate_wrapper <?php echo ( $social_feature == 1 ) ? 'hidden_section' : ''; ?>">
							<a href="javascript:void(0)" class="arm_feature_activate_btn arm_feature_settings_switch" data-feature_val="1" data-feature="social"><?php esc_html_e( 'Activate', 'armember-membership' ); ?></a>
							<a href="javascript:void(0)" class="arm_feature_configure_btn"><?php esc_html_e( 'Configure', 'armember-membership' ); ?></a>
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
						
						<div class="arm_feature_button_deactivate_wrapper <?php echo ( $social_feature == 1 ) ? '' : 'hidden_section'; ?>">
							<a href="javascript:void(0)" class="arm_feature_deactivate_btn arm_feature_settings_switch" data-feature_val="0" data-feature="social"><?php esc_html_e( 'Deactivate', 'armember-membership' ); ?></a>
							<a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $arm_slugs->profiles_directories ) ); //phpcs:ignore ?>" class="arm_feature_configure_btn"><?php esc_html_e( 'Configure', 'armember-membership' ); ?></a>
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
					</div>
					<a class="arm_ref_info_links arm_feature_link" target="_blank" href="https://www.armemberplugin.com/documents/brief-of-social-features/"><?php esc_html_e( 'More Info', 'armember-membership' ); ?></a>
				</div>

				<div class="arm_feature_list opt_ins_enable <?php echo ( $opt_ins_feature == 1 ) ? 'active' : ''; ?>">
					<div class="arm_feature_icon"></div>
					<div class="arm_feature_active_icon"><div class="arm_check_mark"></div></div>
					<div class="arm_feature_content">
						<div class="arm_feature_title"><?php esc_html_e( 'Opt-ins', 'armember-membership' ); ?></div>
						<div class="arm_feature_text"><?php esc_html_e( 'build you subscription list with external list builder like Aweber, Mailchimp while user registration.', 'armember-membership' ); ?></div>
						
						<div class="arm_feature_button_activate_wrapper <?php echo ( $opt_ins_feature == 1 ) ? 'hidden_section' : ''; ?>">
							<a href="javascript:void(0)" class="arm_feature_activate_btn arm_feature_settings_switch" data-feature_val="1" data-feature="opt_ins"><?php esc_html_e( 'Activate', 'armember-membership' ); ?></a>
							<a href="javascript:void(0)" class="arm_feature_configure_btn"><?php esc_html_e( 'Configure', 'armember-membership' ); ?></a>
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
						<div class="arm_feature_button_deactivate_wrapper <?php echo ( $opt_ins_feature == 1 ) ? '' : 'hidden_section'; ?>">
							<a href="javascript:void(0)" class="arm_feature_deactivate_btn arm_feature_settings_switch" data-feature_val="0" data-feature="opt_ins"><?php esc_html_e( 'Deactivate', 'armember-membership' ); ?></a>
							<a href="#" class="arm_feature_configure_btn"><?php esc_html_e( 'Configure', 'armember-membership' ); ?></a>
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
					</div>
					<a class="arm_ref_info_links arm_feature_link" target="_blank" href="https://www.armemberplugin.com/documents/armember-opt-ins-provide-ease-of-email-marketing/"><?php esc_html_e( 'More Info', 'armember-membership' ); ?></a>
				</div>

				<div class="arm_feature_list drip_content_enable <?php echo ( $drip_content_feature == 1 ) ? 'active' : ''; ?>">
					<div class="arm_feature_icon"></div>
					<div class="arm_feature_active_icon"><div class="arm_check_mark"></div></div>
					<div class="arm_feature_content">
						<div class="arm_feature_title"><?php esc_html_e( 'Drip Content', 'armember-membership' ); ?></div>
						<div class="arm_feature_text"><?php esc_html_e( 'Publish your site content based on different time intervals by enabling this feature.', 'armember-membership' ); ?></div>
						
						<div class="arm_feature_button_activate_wrapper <?php echo ( $drip_content_feature == 1 ) ? 'hidden_section' : ''; ?>">
							<a href="javascript:void(0)" class="arm_feature_activate_btn arm_feature_settings_switch" data-feature_val="1" data-feature="drip_content"><?php esc_html_e( 'Activate', 'armember-membership' ); ?></a>
							<a href="javascript:void(0)" class="arm_feature_configure_btn"><?php esc_html_e( 'Configure', 'armember-membership' ); ?></a>
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
						<div class="arm_feature_button_deactivate_wrapper <?php echo ( $drip_content_feature == 1 ) ? '' : 'hidden_section'; ?>">
							<a href="javascript:void(0)" class="arm_feature_deactivate_btn arm_feature_settings_switch" data-feature_val="0" data-feature="drip_content"><?php esc_html_e( 'Deactivate', 'armember-membership' ); ?></a>
							<a href="#"><?php esc_html_e( 'Configure', 'armember-membership' ); ?></a>
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
					</div>
					<a class="arm_ref_info_links arm_feature_link" target="_blank" href="https://www.armemberplugin.com/documents/enable-drip-content-for-your-site/"><?php esc_html_e( 'More Info', 'armember-membership' ); ?></a>
				</div>

				<div class="arm_feature_list social_login_enable <?php echo ( $social_login_feature == 1 ) ? 'active' : ''; ?>">
					<div class="arm_feature_icon"></div>
					<div class="arm_feature_active_icon"><div class="arm_check_mark"></div></div>
					<div class="arm_feature_content">
						<div class="arm_feature_title"><?php esc_html_e( 'Social Connect', 'armember-membership' ); ?></div>
						<div class="arm_feature_text"><?php esc_html_e( 'Allow users to sign up / login with their social accounts by enabling this feature.', 'armember-membership' ); ?></div>
						<div class="arm_feature_button_activate_wrapper <?php echo ( $social_login_feature == 1 ) ? 'hidden_section' : ''; ?>">
							<a href="javascript:void(0)" class="arm_feature_activate_btn arm_feature_settings_switch" data-feature_val="1" data-feature="social_login"><?php esc_html_e( 'Activate', 'armember-membership' ); ?></a>
							<a href="javascript:void(0)" class="arm_feature_configure_btn"><?php esc_html_e( 'Configure', 'armember-membership' ); ?></a>
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
						<div class="arm_feature_button_deactivate_wrapper <?php echo ( $social_login_feature == 1 ) ? '' : 'hidden_section'; ?>">
							<a href="javascript:void(0)" class="arm_feature_deactivate_btn arm_feature_settings_switch" data-feature_val="0" data-feature="social_login"><?php esc_html_e( 'Deactivate', 'armember-membership' ); ?></a>
							<a href="#" class="arm_feature_configure_btn"><?php esc_html_e( 'Configure', 'armember-membership' ); ?></a>
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
					</div>
					<a class="arm_ref_info_links arm_feature_link arm_advanced_link" target="_blank" href="https://www.armemberplugin.com/documents/basic-information-for-social-login/"><?php esc_html_e( 'More Info', 'armember-membership' ); ?></a>
				</div>

				<div class="arm_feature_list pay_per_post_enable <?php echo ( $arm_pay_per_post == 1 ) ? 'active' : ''; ?>">
					<div class="arm_feature_icon"></div>
					<div class="arm_feature_active_icon"><div class="arm_check_mark"></div></div>
					<div class="arm_feature_content">
						<div class="arm_feature_title"><?php esc_html_e( 'Pay Per Post', 'armember-membership' ); ?></div>
						<div class="arm_feature_text"><?php esc_html_e( 'With this feature, you can sell post separately without creating plan(s).', 'armember-membership' ); ?></div>
						<div class="arm_feature_button_activate_wrapper <?php echo ( $arm_pay_per_post == 1 ) ? 'hidden_section' : ''; ?>">
							<a href="javascript:void(0)" class="arm_feature_activate_btn arm_feature_settings_switch" data-feature_val="1" data-feature="arm_pay_per_post"><?php esc_html_e( 'Activate', 'armember-membership' ); ?></a>
							<a href="javascript:void(0)" class="arm_feature_configure_btn"><?php esc_html_e( 'Configure', 'armember-membership' ); ?></a>
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
						<div class="arm_feature_button_deactivate_wrapper <?php echo ( $arm_pay_per_post == 1 ) ? '' : 'hidden_section'; ?>">
							<a href="javascript:void(0)" class="arm_feature_deactivate_btn arm_feature_settings_switch" data-feature_val="0" data-feature="coupon"><?php esc_html_e( 'Deactivate', 'armember-membership' ); ?></a>
							<a href="#" class="arm_feature_configure_btn"><?php esc_html_e( 'Configure', 'armember-membership' ); ?></a>
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
					</div>
					<a class="arm_ref_info_links arm_feature_link" target="_blank" href="https://www.armemberplugin.com/documents/pay-per-post/"><?php esc_html_e( 'More Info', 'armember-membership' ); ?></a>
				</div>

				<div class="arm_feature_list coupon_enable <?php echo ( $coupon_feature == 1 ) ? 'active' : ''; ?>">
					<div class="arm_feature_icon"></div>
					<div class="arm_feature_active_icon"><div class="arm_check_mark"></div></div>
					<div class="arm_feature_content">
						<div class="arm_feature_title"><?php esc_html_e( 'Coupon', 'armember-membership' ); ?></div>
						<div class="arm_feature_text"><?php esc_html_e( 'Let users get benefit of discounts coupons while making payment with your site.', 'armember-membership' ); ?></div>
						<div class="arm_feature_button_activate_wrapper <?php echo ( $coupon_feature == 1 ) ? 'hidden_section' : ''; ?>">
							<a href="javascript:void(0)" class="arm_feature_activate_btn arm_feature_settings_switch" data-feature_val="1" data-feature="coupon"><?php esc_html_e( 'Activate', 'armember-membership' ); ?></a>
							<a href="javascript:void(0)" class="arm_feature_configure_btn"><?php esc_html_e( 'Configure', 'armember-membership' ); ?></a>
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
						<div class="arm_feature_button_deactivate_wrapper <?php echo ( $coupon_feature == 1 ) ? '' : 'hidden_section'; ?>">
							<a href="javascript:void(0)" class="arm_feature_deactivate_btn arm_feature_settings_switch" data-feature_val="0" data-feature="coupon"><?php esc_html_e( 'Deactivate', 'armember-membership' ); ?></a>
							<a href="#" class="arm_feature_configure_btn"><?php esc_html_e( 'Configure', 'armember-membership' ); ?></a>
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
					</div>
					<a class="arm_ref_info_links arm_feature_link arm_advanced_link" target="_blank" href="https://www.armemberplugin.com/documents/how-to-do-coupon-management/"><?php esc_html_e( 'More Info', 'armember-membership' ); ?></a>
				</div>

				<div class="arm_feature_list invoice_tax_enable <?php echo ( $invoice_tax_feature == 1 ) ? 'active' : ''; ?>">
					<div class="arm_feature_icon"></div>
					<div class="arm_feature_active_icon"><div class="arm_check_mark"></div></div>
					<div class="arm_feature_content">
						<div class="arm_feature_title"><?php esc_html_e( 'Invoice and Tax', 'armember-membership' ); ?></div>
						<div class="arm_feature_text"><?php esc_html_e( 'Enable facility to send Invoice and apply Sales Tax on membership plans.', 'armember-membership' ); ?></div>
						<div class="arm_feature_button_activate_wrapper <?php echo ( $invoice_tax_feature == 1 ) ? 'hidden_section' : ''; ?>">
							<a href="javascript:void(0)" class="arm_feature_activate_btn arm_feature_settings_switch" data-feature_val="1" data-feature="invoice_tax"><?php esc_html_e( 'Activate', 'armember-membership' ); ?></a>
							<a href="javascript:void(0)" class="arm_feature_configure_btn"><?php esc_html_e( 'Configure', 'armember-membership' ); ?></a>
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
						<div class="arm_feature_button_deactivate_wrapper <?php echo ( $invoice_tax_feature == 1 ) ? '' : 'hidden_section'; ?>">
							<a href="javascript:void(0)" class="arm_feature_deactivate_btn arm_feature_settings_switch" data-feature_val="0" data-feature="coupon"><?php esc_html_e( 'Deactivate', 'armember-membership' ); ?></a>
							<a href="#" class="arm_feature_configure_btn"><?php esc_html_e( 'Configure', 'armember-membership' ); ?></a>
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
					</div>
					<a class="arm_ref_info_links arm_feature_link arm_advanced_link" target="_blank" href="https://www.armemberplugin.com/documents/invoice-and-tax"><?php esc_html_e( 'More Info', 'armember-membership' ); ?></a>
				</div>

				<div class="arm_feature_list user_private_content_enable <?php echo ( $user_private_content == 1 ) ? 'active' : ''; ?>">
					<div class="arm_feature_icon"></div>
					<div class="arm_feature_active_icon"><div class="arm_check_mark"></div></div>
					<div class="arm_feature_content">
						<div class="arm_feature_title"><?php esc_html_e( 'User Private Content', 'armember-membership' ); ?></div>
						<div class="arm_feature_text"><?php esc_html_e( 'With this feature, you can set different content for different user.', 'armember-membership' ); ?></div>
						<div class="arm_feature_button_activate_wrapper <?php echo ( $user_private_content == 1 ) ? 'hidden_section' : ''; ?>">
							<a href="javascript:void(0)" class="arm_feature_activate_btn arm_feature_settings_switch" data-feature_val="1" data-feature="user_private_content"><?php esc_html_e( 'Activate', 'armember-membership' ); ?></a>
							<a href="javascript:void(0)" class="arm_feature_configure_btn"><?php esc_html_e( 'Configure', 'armember-membership' ); ?></a>
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
						<div class="arm_feature_button_deactivate_wrapper <?php echo ( $user_private_content == 1 ) ? '' : 'hidden_section'; ?>">
							<a href="javascript:void(0)" class="arm_feature_deactivate_btn arm_feature_settings_switch" data-feature_val="0" data-feature="coupon"><?php esc_html_e( 'Deactivate', 'armember-membership' ); ?></a>
							<a href="#" class="arm_feature_configure_btn"><?php esc_html_e( 'Configure', 'armember-membership' ); ?></a>
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
					</div>
					<a class="arm_ref_info_links arm_feature_link" target="_blank" href="https://www.armemberplugin.com/documents/user-private-content/"><?php esc_html_e( 'More Info', 'armember-membership' ); ?></a>
				</div>

				<div class="arm_feature_list multiple_membership_enable <?php echo ( $multiple_membership_feature == 1 ) ? 'active' : ''; ?>">
					<div class="arm_feature_icon"></div>
					<div class="arm_feature_active_icon"><div class="arm_check_mark"></div></div>
					<div class="arm_feature_content">
						<div class="arm_feature_title"><?php esc_html_e( 'Multiple Membership/Plans', 'armember-membership' ); ?></div>
						<div class="arm_feature_text"><?php esc_html_e( 'Allow members to subscribe multiple plans simultaneously.', 'armember-membership' ); ?></div>
						<div class="arm_feature_button_activate_wrapper <?php echo ( $multiple_membership_feature == 1 ) ? 'hidden_section' : ''; ?>">
							<a href="javascript:void(0)" class="arm_feature_activate_btn arm_feature_settings_switch" data-feature_val="1" data-feature="multiple_membership"><?php esc_html_e( 'Activate', 'armember-membership' ); ?></a>
				
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
						<div class="arm_feature_button_deactivate_wrapper <?php echo ( $multiple_membership_feature == 1 ) ? '' : 'hidden_section'; ?>">
							<a href="javascript:void(0)" class="arm_feature_deactivate_btn arm_feature_settings_switch" data-feature_val="0" data-feature="multiple_membership"><?php esc_html_e( 'Deactivate', 'armember-membership' ); ?></a>
					
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
					</div>
						<a class="arm_ref_info_links arm_feature_link arm_advanced_link" target="_blank" href="https://www.armemberplugin.com/documents/single-vs-multiple-membership/"><?php esc_html_e( 'More Info', 'armember-membership' ); ?></a>
				</div>
				<!-- START -->
				<div class="arm_feature_list plan_limit_enable <?php echo ( $plan_limit_feature == 1 ) ? 'active' : ''; ?>">
					<div class="arm_feature_icon"></div>
					<div class="arm_feature_active_icon"><div class="arm_check_mark"></div></div>
					<div class="arm_feature_content">
						<div class="arm_feature_title"><?php esc_html_e( 'Membership Limit', 'armember-membership' ); ?></div>
						<div class="arm_feature_text"><?php esc_html_e( 'With this feature, you can limit plan, Pay Per Post purchases for members.', 'armember-membership' ); ?></div>
						<div class="arm_feature_button_activate_wrapper <?php echo ( $plan_limit_feature == 1 ) ? 'hidden_section' : ''; ?>">
							<a href="javascript:void(0)" class="arm_feature_activate_btn arm_feature_settings_switch" data-feature_val="1" data-feature="plan_limit"><?php esc_html_e( 'Activate', 'armember-membership' ); ?></a>
				
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
						<div class="arm_feature_button_deactivate_wrapper <?php echo ( $plan_limit_feature == 1 ) ? '' : 'hidden_section'; ?>">
							<a href="javascript:void(0)" class="arm_feature_deactivate_btn arm_feature_settings_switch" data-feature_val="0" data-feature="plan_limit"><?php esc_html_e( 'Deactivate', 'armember-membership' ); ?></a>
					
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
					</div>
						<a class="arm_ref_info_links arm_feature_link arm_advanced_link" target="_blank" href="https://www.armemberplugin.com/documents/paid-membership-plan-payment-process/"><?php esc_html_e( 'More Info', 'armember-membership' ); ?></a>
				</div>
				<div class="arm_feature_list api_service_enable <?php echo ( $arm_api_service_feature == 1 ) ? 'active' : ''; ?>">
					<div class="arm_feature_icon"></div>
					<div class="arm_feature_active_icon"><div class="arm_check_mark"></div></div>
					<div class="arm_feature_content">
						<div class="arm_feature_title"><?php esc_html_e( 'API Services', 'armember-membership' ); ?></div>
						<div class="arm_feature_text"><?php esc_html_e( 'With this feature, you will able to use Membership API Services for your Application.', 'armember-membership' ); ?></div>
						<div class="arm_feature_button_activate_wrapper <?php echo ( $arm_api_service_feature == 1 ) ? 'hidden_section' : ''; ?>">
							<a href="javascript:void(0)" class="arm_feature_activate_btn arm_feature_settings_switch" data-feature_val="1" data-feature="api_service"><?php esc_html_e( 'Activate', 'armember-membership' ); ?></a>
				
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
						<div class="arm_feature_button_deactivate_wrapper <?php echo ( $plan_limit_feature == 1 ) ? '' : 'hidden_section'; ?>">
							<a href="javascript:void(0)" class="arm_feature_deactivate_btn arm_feature_settings_switch" data-feature_val="0" data-feature="api_service"><?php esc_html_e( 'Deactivate', 'armember-membership' ); ?></a>
					
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
					</div>
						<a class="arm_ref_info_links arm_feature_link arm_advanced_link" target="_blank" href="https://www.armemberplugin.com/documents/paid-membership-plan-payment-process/"><?php esc_html_e( 'More Info', 'armember-membership' ); ?></a>
				</div>
				<!-- END -->

				<div class="arm_feature_list buddypress_enable <?php echo ( $buddypress_feature == 1 ) ? 'active' : ''; ?>">
					<div class="arm_feature_icon"></div>
					<div class="arm_feature_active_icon"><div class="arm_check_mark"></div></div>
					<div class="arm_feature_content">
						<div class="arm_feature_title"><?php esc_html_e( 'Buddypress/Buddyboss Integration', 'armember-membership' ); ?></div>
						<div class="arm_feature_text"><?php esc_html_e( 'Integrate BuddyPress/Buddyboss with ARMember.', 'armember-membership' ); ?></div>
						<div class="arm_feature_button_activate_wrapper <?php echo ( $buddypress_feature == 1 ) ? 'hidden_section' : ''; ?>">
							<a href="javascript:void(0)" class="arm_feature_activate_btn arm_feature_settings_switch" data-feature_val="1" data-feature="buddypress"><?php esc_html_e( 'Activate', 'armember-membership' ); ?></a>
							<a href="javascript:void(0)" class="arm_feature_configure_btn"><?php esc_html_e( 'Configure', 'armember-membership' ); ?></a>
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
						<div class="arm_feature_button_deactivate_wrapper <?php echo ( $buddypress_feature == 1 ) ? '' : 'hidden_section'; ?>">
							<a href="javascript:void(0)" class="arm_feature_deactivate_btn arm_feature_settings_switch" data-feature_val="0" data-feature="buddypress"><?php esc_html_e( 'Deactivate', 'armember-membership' ); ?></a>
							<a href="#" class="arm_feature_configure_btn"><?php esc_html_e( 'Configure', 'armember-membership' ); ?></a>
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
					</div>
					<a class="arm_ref_info_links arm_feature_link arm_advanced_link" target="_blank" href="https://www.armemberplugin.com/documents/buddypress-support/"><?php esc_html_e( 'More Info', 'armember-membership' ); ?></a>
				</div>

				<div class="arm_feature_list woocommerce_enable <?php echo ( $woocommerce_feature == 1 ) ? 'active' : ''; ?>">
					<div class="arm_feature_icon"></div>
					<div class="arm_feature_active_icon"><div class="arm_check_mark"></div></div>
					<div class="arm_feature_content">
						<div class="arm_feature_title"><?php esc_html_e( 'Woocommerce Integration', 'armember-membership' ); ?></div>
						<div class="arm_feature_text" style=" min-height: 0;"><?php esc_html_e( 'Integrate Woocommerce with ARMember.', 'armember-membership' ); ?></div>
						<div class="arm_feature_text arm_woocommerce_feature_version_required_notice"><?php esc_html_e( 'Minimum Required Woocommerce Version: 3.0.2', 'armember-membership' ); ?></div>
						<div class="arm_feature_button_activate_wrapper <?php echo ( $woocommerce_feature == 1 ) ? 'hidden_section' : ''; ?>">
							<a href="javascript:void(0)" class="arm_feature_activate_btn arm_feature_settings_switch" data-feature_val="1" data-feature="woocommerce"><?php esc_html_e( 'Activate', 'armember-membership' ); ?></a>
							<!--<a href="javascript:void(0)" class="arm_feature_configure_btn"><?php esc_html_e( 'Configure', 'armember-membership' ); ?></a>-->
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
						<div class="arm_feature_button_deactivate_wrapper <?php echo ( $woocommerce_feature == 1 ) ? '' : 'hidden_section'; ?>">
							<a href="javascript:void(0)" class="arm_feature_deactivate_btn arm_feature_settings_switch" data-feature_val="0" data-feature="woocommerce"><?php esc_html_e( 'Deactivate', 'armember-membership' ); ?></a>
							<!--<a href="#" class="arm_feature_configure_btn"><?php esc_html_e( 'Configure', 'armember-membership' ); ?></a>-->
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
					</div>
					<a class="arm_ref_info_links arm_feature_link arm_advanced_link" target="_blank" href="https://www.armemberplugin.com/documents/woocommerce-support/"><?php esc_html_e( 'More Info', 'armember-membership' ); ?></a>
				</div>

				<div class="arm_feature_list mycred_enable <?php echo ( $arm_admin_mycred_feature == 1 ) ? 'active' : ''; ?>">
					<div class="arm_feature_icon"></div>
					<div class="arm_feature_active_icon"><div class="arm_check_mark"></div></div>
					<div class="arm_feature_content">
						<div class="arm_feature_title"><?php esc_html_e( 'myCRED Integration', 'armember-membership' ); ?></div>
						<div class="arm_feature_text"><?php esc_html_e( 'Integrate myCRED adaptive points management system with ARMember.', 'armember-membership' ); ?></div>
						<div class="arm_feature_button_activate_wrapper <?php echo ( $arm_admin_mycred_feature == 1 ) ? 'hidden_section' : ''; ?>">
							<a href="javascript:void(0)" class="arm_feature_activate_btn arm_feature_settings_switch" data-feature_val="1" data-feature="mycred"><?php esc_html_e( 'Activate', 'armember-membership' ); ?></a>
							<a href="javascript:void(0)" class="arm_feature_configure_btn"><?php esc_html_e( 'Configure', 'armember-membership' ); ?></a>
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
						<div class="arm_feature_button_deactivate_wrapper <?php echo ( $arm_admin_mycred_feature == 1 ) ? '' : 'hidden_section'; ?>">
							<a href="javascript:void(0)" class="arm_feature_deactivate_btn arm_feature_settings_switch" data-feature_val="0" data-feature="coupon"><?php esc_html_e( 'Deactivate', 'armember-membership' ); ?></a>
							<a href="#" class="arm_feature_configure_btn"><?php esc_html_e( 'Configure', 'armember-membership' ); ?></a>
							<img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/arm_loader.gif'; //phpcs:ignore ?>" class="arm_addon_loader_img" width="24" height="24" />
						</div>
					</div>
					<a class="arm_ref_info_links arm_feature_link arm_advanced_link" target="_blank" href="https://www.armemberplugin.com/documents/mycred-integration/"><?php esc_html_e( 'More Info', 'armember-membership' ); ?></a>
				</div>

				<div class="arm_feature_list gutenberg_block_restriction_enable <?php echo ($gutenberg_block_restriction_feature == 1) ? 'active':'';?>">
                    <div class="arm_feature_icon"></div>
                    <div class="arm_feature_active_icon"><div class="arm_check_mark"></div></div>
                    <div class="arm_feature_content">
                        <div class="arm_feature_title"><?php esc_html_e('Gutenberg Block Restriction','armember-membership'); ?></div>
                        <div class="arm_feature_text"><?php esc_html_e("Allows facility to set the Access for Gutenberg Blocks per Membership Plan or Logged in member.", 'armember-membership');?></div>
						<div class="arm_feature_button_activate_wrapper <?php echo ($gutenberg_block_restriction_feature == 1) ? 'hidden_section':'';?>">
							<a href="javascript:void(0)" class="arm_feature_activate_btn arm_feature_settings_switch" data-feature_val="1" data-feature="gutenberg_block_restriction"><?php esc_html_e('Activate','armember-membership'); ?></a>
							<span class="arm_addon_loader">
								<svg class="arm_circular" viewBox="0 0 60 60">
								<circle class="path" cx="25px" cy="23px" r="18" fill="none" stroke-width="4" stroke-miterlimit="7"></circle>
								</svg>
                            </span>
                        </div>
                        <div class="arm_feature_button_deactivate_wrapper <?php echo ($gutenberg_block_restriction_feature == 1) ? '':'hidden_section';?>">
                            <a href="javascript:void(0)" class="arm_feature_deactivate_btn arm_no_config_feature_btn arm_feature_settings_switch" data-feature_val="0" data-feature="gutenberg_block_restriction"><?php esc_html_e('Deactivate','armember-membership'); ?></a>
                            
                            <span class="arm_addon_loader">
                                <svg class="arm_circular" viewBox="0 0 60 60">
                                    <circle class="path" cx="25px" cy="23px" r="18" fill="none" stroke-width="4" stroke-miterlimit="7"></circle>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <a class="arm_ref_info_links arm_feature_link arm_advanced_link" target="_blank" href="https://www.armemberplugin.com/documents/gutenberg-block-support/"><?php esc_html_e('More Info', 'armember-membership'); ?></a>
                </div>

				<div class="arm_feature_list beaver_builder_restriction_enable <?php echo ($beaver_builder_restriction_feature == 1) ? 'active':'';?>">
                    <div class="arm_feature_icon"></div>
                    <div class="arm_feature_active_icon"><div class="arm_check_mark"></div></div>
                    <div class="arm_feature_content">
                        <div class="arm_feature_title"><?php esc_html_e('Beaver Builder Restriction','armember-membership'); ?></div>
                        <div class="arm_feature_text"><?php esc_html_e("Allows Beaver Builder widgets to restrict based on Membership Plan.", 'armember-membership');?></div>
                        <div class="arm_feature_button_activate_wrapper <?php echo ($beaver_builder_restriction_feature == 1) ? 'hidden_section':'';?>">
                            <a href="javascript:void(0)" class="arm_feature_activate_btn arm_feature_settings_switch" data-feature_val="1" data-feature="beaver_builder_restriction"><?php esc_html_e('Activate','armember-membership'); ?></a>
                            <span class="arm_addon_loader">
                                <svg class="arm_circular" viewBox="0 0 60 60">
                                    <circle class="path" cx="25px" cy="23px" r="18" fill="none" stroke-width="4" stroke-miterlimit="7"></circle>
                                </svg>
                            </span>
                        </div>
                        <div class="arm_feature_button_deactivate_wrapper <?php echo ($beaver_builder_restriction_feature == 1) ? '':'hidden_section';?>">
                            <a href="javascript:void(0)" class="arm_feature_deactivate_btn arm_no_config_feature_btn arm_feature_settings_switch" data-feature_val="0" data-feature="beaver_builder_restriction"><?php esc_html_e('Deactivate','armember-membership'); ?></a>
                            
                            <span class="arm_addon_loader">
                                <svg class="arm_circular" viewBox="0 0 60 60">
                                    <circle class="path" cx="25px" cy="23px" r="18" fill="none" stroke-width="4" stroke-miterlimit="7"></circle>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <a class="arm_ref_info_links arm_feature_link arm_advanced_link" target="_blank" href="https://www.armemberplugin.com/documents/beaver-builder-support/"><?php esc_html_e('More Info', 'armember-membership'); ?></a>
                </div>

				<div class="arm_feature_list divi_builder_restriction_enable <?php echo ($divi_builder_restriction_feature == 1) ? 'active':'';?>">
                    <div class="arm_feature_icon"></div>
                    <div class="arm_feature_active_icon"><div class="arm_check_mark"></div></div>
                    <div class="arm_feature_content">
                        <div class="arm_feature_title"><?php esc_html_e('Divi Builder Restriction','armember-membership'); ?></div>
                        <div class="arm_feature_text"><?php esc_html_e("Allows facility to set the access for Divi Builder content Like Section and Row per Membership Plan.", 'armember-membership');?></div>
                        <div class="arm_feature_button_activate_wrapper <?php echo ($divi_builder_restriction_feature == 1) ? 'hidden_section':'';?>">
                            <a href="javascript:void(0)" class="arm_feature_activate_btn arm_feature_settings_switch" data-feature_val="1" data-feature="divi_builder_restriction"><?php esc_html_e('Activate','armember-membership'); ?></a>
                            <span class="arm_addon_loader">
                                <svg class="arm_circular" viewBox="0 0 60 60">
                                    <circle class="path" cx="25px" cy="23px" r="18" fill="none" stroke-width="4" stroke-miterlimit="7"></circle>
                                </svg>
                            </span>
                        </div>
                        <div class="arm_feature_button_deactivate_wrapper <?php echo ($divi_builder_restriction_feature == 1) ? '':'hidden_section';?>">
                            <a href="javascript:void(0)" class="arm_feature_deactivate_btn arm_no_config_feature_btn arm_feature_settings_switch" data-feature_val="0" data-feature="divi_builder_restriction"><?php esc_html_e('Deactivate','armember-membership'); ?></a>
                            
                            <span class="arm_addon_loader">
                                <svg class="arm_circular" viewBox="0 0 60 60">
                                    <circle class="path" cx="25px" cy="23px" r="18" fill="none" stroke-width="4" stroke-miterlimit="7"></circle>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <a class="arm_ref_info_links arm_feature_link arm_advanced_link" target="_blank" href="https://www.armemberplugin.com/documents/divi-builder-support/"><?php esc_html_e('More Info', 'armember-membership'); ?></a>
                </div>

				<div class="arm_feature_list wpbakery_page_builder_restriction_enable <?php echo ($wpbakery_page_builder_restriction_feature == 1) ? 'active':'';?>">
                    <div class="arm_feature_icon"></div>
                    <div class="arm_feature_active_icon"><div class="arm_check_mark"></div></div>
                    <div class="arm_feature_content">
                        <div class="arm_feature_title"><?php esc_html_e('WPBakery Page Builder Restriction','armember-membership'); ?></div>
                        <div class="arm_feature_text"><?php esc_html_e("Allows to set restrict content on WPBakery Elements per Membership Plan.", 'armember-membership');?></div>
                        <div class="arm_feature_button_activate_wrapper <?php echo ($wpbakery_page_builder_restriction_feature == 1) ? 'hidden_section':'';?>">
                            <a href="javascript:void(0)" class="arm_feature_activate_btn arm_feature_settings_switch" data-feature_val="1" data-feature="wpbakery_page_builder_restriction"><?php esc_html_e('Activate','armember-membership'); ?></a>
                            <span class="arm_addon_loader">
                                <svg class="arm_circular" viewBox="0 0 60 60">
                                    <circle class="path" cx="25px" cy="23px" r="18" fill="none" stroke-width="4" stroke-miterlimit="7"></circle>
                                </svg>
                            </span>
                        </div>
                        <div class="arm_feature_button_deactivate_wrapper <?php echo ($wpbakery_page_builder_restriction_feature == 1) ? '':'hidden_section';?>">
                            <a href="javascript:void(0)" class="arm_feature_deactivate_btn arm_no_config_feature_btn arm_feature_settings_switch" data-feature_val="0" data-feature="wpbakery_page_builder_restriction"><?php esc_html_e('Deactivate','armember-membership'); ?></a>
                            
                            <span class="arm_addon_loader">
                                <svg class="arm_circular" viewBox="0 0 60 60">
                                    <circle class="path" cx="25px" cy="23px" r="18" fill="none" stroke-width="4" stroke-miterlimit="7"></circle>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <a class="arm_ref_info_links arm_feature_link arm_advanced_link" target="_blank" href="https://www.armemberplugin.com/documents/divi-builder-support/"><?php esc_html_e('More Info', 'armember-membership'); ?></a>
                </div>
				
				<?php echo do_action( 'arm_add_new_custom_add_on' ); //phpcs:ignore ?>
			</div>
			
			<div class="arm_feature_settings_container arm_margin_top_30">
				<?php
				global $arm_social_feature;
				global $arm_lite_version;
				$addon_resp = '';
				$addon_resp = $arm_social_feature->addons_page();

				$plugins           = get_plugins();
				$installed_plugins = array();
				foreach ( $plugins as $key => $plugin ) {
					$is_active                            = is_plugin_active( $key );
					$installed_plugin                     = array(
						'plugin'    => $key,
						'name'      => $plugin['Name'],
						'is_active' => $is_active,
					);
					$installed_plugin['activation_url']   = $is_active ? '' : wp_nonce_url( "plugins.php?action=activate&plugin={$key}", "activate-plugin_{$key}" );
					$installed_plugin['deactivation_url'] = ! $is_active ? '' : wp_nonce_url( "plugins.php?action=deactivate&plugin={$key}", "deactivate-plugin_{$key}" );

					$installed_plugins[] = $installed_plugin;
				}


				?>
		<?php $wpnonce = wp_create_nonce( 'arm_wp_nonce' );?>
				<input type="hidden" name="arm_wp_nonce" value="<?php echo esc_attr($wpnonce);?>"/>
		</div>
		</div>
		<div class="armclear"></div>
	</div>
</div>

<?php
$addon_content                   = '<span class="arm_confirm_text">' . esc_html__( 'You need to have ARMember version 1.6 OR higher to install this addon.', 'armember-membership' ) . '</span>';
		$addon_content          .= '<input type="hidden" value="false" id="bulk_delete_flag"/>';
		$addon_content_popup_arg = array(
			'id'             => 'addon_message',
			'class'          => 'adddon_message',
			'title'          => esc_html__( 'Confirmation', 'armember-membership' ),
			'content'        => $addon_content,
			'button_id'      => 'addon_ok_btn',
			'button_onclick' => 'addon_message();',
		);
		echo $arm_global_settings->arm_get_bpopup_html( $addon_content_popup_arg ); //phpcs:ignore



		$addon_not_supported_content = '<span class="arm_confirm_text ">' . esc_html__( 'This feature is available only in Pro version.', 'armember-membership' ) . '</span>';
		$popup                       = '<div id="arm_addon_not_supoported_notice" class="popup_wrapper arm_addon_not_supoported_notice"><div class="popup_wrapper_inner">';

			$popup .= '<div class="popup_content_text arm_text_align_center">' . $addon_not_supported_content . '</div>';
			$popup .= '<div class="armclear"></div>';
			$popup .= '<div class="popup_footer">';
			$popup .= '<div class="popup_content_btn_wrapper">';

			$popup .= '<a type="button" class="arm_submit_btn popup_ok_btn" id="addon_not_supported_notices_ok_btn" href="https://www.armemberplugin.com/buy-now" target="_blank">' . esc_html__( 'Get ARMember Pro Now', 'armember-membership' ) . '</a>';
			$popup .= '</div>';

			$popup .= '</div>';
			$popup .= '<div class="armclear"></div>';
			$popup .= '</div></div>';


		echo $popup //phpcs:ignore
		?>

<div id="arfactnotcompatible" style="display:none; background:white; padding:15px; border-radius:3px; width:400px; height:100px;">
		
		<div class="arfactnotcompatiblemodalclose" style="float:right;text-align:right;cursor:pointer; position:absolute;right:10px; " onclick="javascript:return false;"><img src="<?php echo esc_attr(MEMBERSHIPLITE_IMAGES_URL) . '/close-button.png'; //phpcs:ignore ?>" align="absmiddle" /></div>
		
	   <table class="form-table">
			<tr class="form-field">
				<th class="arm-form-table-label arm_font_size_16">You need to have ARMember version 1.6 OR higher to install this addon.</th>
			</tr>				
		</table>
</div>
<script type="text/javascript">
	var ADDON_NOT_COMPATIBLE_MESSAGE = "<?php esc_html_e( 'This Addon is not compatible with current ARMember version. Please update ARMember to latest version.', 'armember-membership' ); ?>";
	<?php if ( ! empty( $_REQUEST['arm_activate_social_feature'] ) ) { ?>
		armToast("<?php esc_html_e( 'Please activate the \"Social Feature\" module to make this feature work.', 'armember-membership' ); ?>", 'error', 5000, false);
	<?php } ?>
	</script>
	
<?php
$_SESSION['arm_member_addon'] = $myplugarr;
