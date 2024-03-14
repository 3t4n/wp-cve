<?php
/**
 * File to show notice for recommended add-ons based on the IDP selected.
 *
 * @package miniorange-saml-20-single-sign-on\views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Function to recommend add-ons based on the IDP selected.
 *
 * @param string $display variable to show or hide the notice.
 * @return void
 */
function mo_saml_display_plugin_notice( $display ) {   ?>
	<div class="mo_pt-3 mo_pe-5 mo_pb-5 mo_ps-5 mo_shadow-cstm mo_bg-cstm mo_rounded period mo_notice_style" style = "display: <?php echo esc_attr( $display ); ?>" id = "mo_service">
		<form action = "" method = "POST">
			<button type = "submit" name = "mo_idp_close_notice" class="mo_notice_cross_btn" value = "mo_idp_close_notice">X</button>
			<?php wp_nonce_field( 'mo_idp_close_notice' ); ?>
		</form>
		<h7 class="mo_text-secondary mo-saml-bootstrap-text-black">It seems you are using<b>&nbsp;<span class = "mo-saml-bootstrap-text-black" id ="idp_ads_check_idp_name">  </span></b>&nbsp;as your IDP. Hence, you might be interested in our other <a href="https://plugins.miniorange.com/wordpress-azure-office365-integrations" target="_blank" rel="noopener noreferrer" style="text-decoration: none;">Microsoft Integrations</a>
		like Real-time User Sync, Power BI Embed, SharePoint Document Library, etc. </h7><br>

		<a href = "https://wordpress.org/plugins/embed-power-bi-reports/" target="_blank" rel="noopener noreferrer"><button type="submit" class = "btn_cstm mo_btn-cstm mo_rounded mo_mt-3 mo_me-3 mo_w-176 text-white mo_notice_btn_style">
		<img src=" <?php echo esc_url( Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() ); ?>images/addons_logos/power_bi.webp" width="10%" class="mo_notice_btn_text"/>&nbsp;<h7 class="mo_notice_btn_text">Get Power BI Plugin</h7></button></a>

		<a href = "https://wordpress.org/plugins/embed-sharepoint-onedrive-documents/" target="_blank" rel="noopener noreferrer"><button type="submit" class = "btn_cstm mo_btn-cstm mo_rounded mo_mt-3 mo_me-3 mo_w-176 text-white mo_notice_btn_style">
		<img src="<?php echo esc_url( Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() ); ?>images/addons_logos/sharepoint.webp" width="12%" class="mo_notice_btn_text"/>&nbsp;<h7 class="mo_notice_btn_text">&nbsp;Get SharePoint Plugin</h7></button></a>

		<a href = "https://wordpress.org/plugins/user-sync-for-azure-office365/" target="_blank" rel="noopener noreferrer"><button type="submit" class = "btn_cstm mo_btn-cstm mo_rounded mo_mt-3 mo_me-3 mo_w-176 text-white mo_notice_btn_style" >
		<img src="<?php echo esc_url( Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() ); ?>images/addons_logos/user-sync.webp" width= "9%" class="mo_notice_btn_text" />&nbsp;<h7 class="mo_notice_btn_text">Get User/Profile Sync Plugin</h7></button></a>

		<a href = "https://wordpress.org/plugins/employee-staff-directory/" target="_blank" rel="noopener noreferrer"><button type="submit" class = "btn_cstm mo_btn-cstm mo_rounded mo_mt-3 mo_me-3 mo_w-176 text-white mo_notice_btn_style" style = "width: 290px !important;">
		<img src="<?php echo esc_url( Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() ); ?>images/addons_logos/employee_directory.webp" width="8%" class="mo_notice_btn_text"/>&nbsp;<h7 class="mo_notice_btn_text">Get Employee Directory Plugin</h7></button></a>
	</div>
	<?php
}

?>
