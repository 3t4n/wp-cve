<?php
/**
 * This file displays all the add-ons listed in the plugin.
 *
 * @package miniorange-saml-20-single-sign-on\views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * The function contains details for all the add-ons.
 *
 * @return void
 */
function mo_saml_show_addons_page() {
	require_once ABSPATH . '/wp-admin/includes/plugin.php';
	$mo_saml_identity_provider_identifier_name = get_option( Mo_Saml_Options_Enum_Service_Provider::IDENTITY_PROVIDER_NAME );
	$addons_displayed                          = array();
	$addon_desc                                = array(
		'salesforce_sync'             => __( 'Allows to synchronize WordPress objects to Salesforce. Along with WordPress to Salesforce sync the plugin can also enable syncing data from Salesforce to WordPress.', 'miniorange-saml-20-single-sign-on' ),
		'power_bi'                    => __( 'To establishing trust between the WordPress site and Power BI to securely authenticate and login users to the WordPress site.', 'miniorange-saml-20-single-sign-on' ),
		'sharepoint'                  => __( 'To establishing trust between the WordPress site and Microsoft SharePoint to securely authenticate and login users to the WordPress site.', 'miniorange-saml-20-single-sign-on' ),
		'employee_directory'          => __( 'Creates a Central directory of Employees, Staff or Team. Provides an easily searchable, sortable list of all the Employees, group or tag your Employees based on categories and many more.', 'miniorange-saml-20-single-sign-on' ),
		'azure_sync'                  => __( 'Provides an option for bi-directional synchronization of the users from Azure AD / Azure B2C / Office 365 to WordPress. It also supports seamless WordPress integration with all Microsoft Apps', 'miniorange-saml-20-single-sign-on' ),
		'scim'                        => __( 'Allows real-time user sync (automatic user create, delete, and update) from your Identity Provider such as Azure, Okta, Onelogin into your WordPress site.', 'miniorange-saml-20-single-sign-on' ),
		'page_restriction'            => __( 'Restrict access to WordPress pages/posts based on user roles and their login status, thereby protecting these pages/posts from unauthorized access.', 'miniorange-saml-20-single-sign-on' ),
		'file_prevention'             => __( 'Restrict any kind of media files such as images, audio, videos, documents, etc, and any extension (configurable) such as png, pdf, jpeg, jpg, bmp, gif, etc.', 'miniorange-saml-20-single-sign-on' ),
		'ssologin'                    => __( 'SSO Login Audit tracks all the SSO users and generates detailed reports. The advanced search filters in audit reports makes it easy to find and keep track of your users.', 'miniorange-saml-20-single-sign-on' ),
		'buddypress'                  => __( 'Integrate user information sent by the SAML Identity Provider in SAML Assertion with the BuddyPress profile fields.', 'miniorange-saml-20-single-sign-on' ),
		'learndash'                   => __( 'Allows mapping your users to different LearnDash LMS plugin groups as per their group information sent by configured  SAML Identity Provider.', 'miniorange-saml-20-single-sign-on' ),
		'attribute_based_redirection' => __( 'Enables you to redirect your users to different pages after they log into your site, based on the attributes sent by your Identity Provider.', 'miniorange-saml-20-single-sign-on' ),
		'ssosession'                  => __( 'Helps you in managing the login session time of your users based on their WordPress roles. Session time for roles can be specified.', 'miniorange-saml-20-single-sign-on' ),
		'fsso'                        => __( 'Allows secure access to the site using various federations such as InCommon, HAKA, HKAF, etc. Users can log into the WordPress site using their university credentials.', 'miniorange-saml-20-single-sign-on' ),
		'memberpress'                 => __( 'Map users to different membership levels created by the MemberPress plugin using the group information sent by your Identity Provider.', 'miniorange-saml-20-single-sign-on' ),
		'wp_members'                  => __( 'Integrate WP-members fields using the attributes sent by your SAML Identity Provider in the SAML Assertion.', 'miniorange-saml-20-single-sign-on' ),
		'woocommerce'                 => __( 'Map WooCommerce checkout page fields using the attributes sent by your IDP. This also allows you to map the users in different WooCommerce roles based on their IDP groups.', 'miniorange-saml-20-single-sign-on' ),
		'guest_login'                 => __( 'Allows users to SSO into your site without creating a user account for them. This is useful when you dont want to manage the user accounts at the WordPress site.', 'miniorange-saml-20-single-sign-on' ),
		'paid_mem_pro'                => __( 'Map your users to different Paid MembershipPro membership levels as per the group information sent by your Identity Provider.', 'miniorange-saml-20-single-sign-on' ),
		'profile_picture_add_on'      => __( 'Maps raw image data or URL received from your Identity Provider into Gravatar for the user.', 'miniorange-saml-20-single-sign-on' ),
	);
	?>
	<div id="miniorange-addons" style="position:relative;z-index: 1">

	<div class="mo-saml-bootstrap-row mo-saml-bootstrap-container-fluid" id="addon-tab-form">
			<div class="mo-saml-bootstrap-col-md-8 mo-saml-bootstrap-mt-4 mo-saml-bootstrap-ms-5">
			<?php
			$is_header_displayed     = false;
			$active_external_plugins = mo_saml_external_active_plugins();
			$display_addons          = array();
			if ( ! empty( $active_external_plugins ) ) {
				$display_addons = $active_external_plugins;
			}
			if ( ! empty( Mo_Saml_Options_Plugin_Idp::$idp_list[ $mo_saml_identity_provider_identifier_name ] ) ) {
				$display_addons = array_merge( $display_addons, Mo_Saml_Options_Addons::$addon_specific );
			}

			if ( ! empty( $display_addons ) ) {
				?>
					<h4 class="form-head" id="recommended_section"><?php esc_html_e( 'Recommended Add-ons for you', 'miniorange-saml-20-single-sign-on' ); ?></h4> 
																						<?php
																						foreach ( $display_addons as $key => $value ) {
																							$addon                      = $key;
																							$addons_displayed[ $addon ] = $addon;
																							if ( ! $is_header_displayed ) {
																								$is_header_displayed = true;
																							}

																							get_addon_tile( $addon, Mo_Saml_Options_Addons::$addon_title[ $addon ], $addon_desc[ $addon ], Mo_Saml_Options_Addons::$addons_url[ $addon ], true );
																						}
																						if ( ! $active_external_plugins ) {
																							?>
						<div class = "line_break_recommended"></div>
																							<?php
																						}
			}

			if ( $is_header_displayed ) {
				?>
					<div class = "line_break_checkout"></div>
				<?php
			}
			?>
				<h4 class="form-head"><?php esc_html_e( 'Check out all our add-ons', 'miniorange-saml-20-single-sign-on' ); ?></h4>
														<?php
														foreach ( $addon_desc as $key => $value ) {
															if ( ! in_array( $key, $addons_displayed, true ) ) {
																get_addon_tile( $key, Mo_Saml_Options_Addons::$addon_title[ $key ], $value, Mo_Saml_Options_Addons::$addons_url[ $key ], false );
															}
														}
														?>
			</div>
			<?php mo_saml_display_support_form(); ?>
		</div>
	</div> 
	<?php
}

/**
 * This function creates a card for displaying the add-ons.
 *
 * @param string  $addon_name this will display addon-name.
 * @param string  $addon_title this will display addon_title.
 * @param string  $addon_desc this will display addon_description.
 * @param string  $addon_url this will display addon_url.
 * @param boolean $active this will display if the addon is in the active state.
 * @return void
 */
function get_addon_tile( $addon_name, $addon_title, $addon_desc, $addon_url, $active ) {
		$icon_url = Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() . 'images/addons_logos/' . $addon_name . '.webp';
	?>
			<div class="mo-saml-add-ons-cards mo-saml-bootstrap-mt-3">
				<h4 class="mo-saml-addons-head"><?php echo esc_attr( $addon_title ); ?></h4>
				<p class="mo-saml-bootstrap-pt-4 mo-saml-bootstrap-pe-2 mo-saml-bootstrap-pb-4 mo-saml-bootstrap-ps-4"><?php echo esc_html( $addon_desc ); ?></p>
				<img src="<?php echo esc_url( $icon_url ); ?>" class="mo-saml-addons-logo" alt=" Image">
				<span class="mo-saml-add-ons-rect"></span>
				<span class="mo-saml-add-ons-tri"></span>
				<a class="mo-saml-addons-readmore" href="<?php echo esc_url( $addon_url ); ?>" target="_blank">Learn More</a>
			</div>
	<?php
}
/**
 * This function returns list of active third party add-ons.
 *
 * @return $active_plugins
 */
function mo_saml_external_active_plugins() {
	$active_plugins = array();
	foreach ( Mo_Saml_Options_Addons::$recommended_addons_path as $key => $value ) {
		if ( is_plugin_active( $value ) ) {
			$active_plugins[ $key ] = $value;
		}
	}
	return $active_plugins;
}
?>
