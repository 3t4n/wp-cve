<?php
/**
 * File to display sections of Attribute and Role Mapping.
 *
 * @package miniorange-saml-20-single-sign-on\views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Function to display Attribute/Role Mapping tab.
 *
 * @return void
 */
function mo_saml_save_optional_config() {
	$default_role = get_option( 'saml_am_default_user_role' );
	if ( empty( $default_role ) ) {
		$default_role = get_option( 'default_role' );
	}
	$wp_roles = new WP_Roles();
	$roles    = $wp_roles->get_names();
	?>
	<div class="mo-saml-bootstrap-row mo-saml-bootstrap-container-fluid" action="" id="attr-role-tab-form">
		<div class="mo-saml-bootstrap-col-md-8 mo-saml-bootstrap-mt-4 mo-saml-bootstrap-ms-5">
			<?php
			mo_saml_display_attribute_mapping();
			mo_saml_display_role_mapping( $default_role, $roles );
			?>
		</div>
		<?php mo_saml_display_support_form( true ); ?>
	</div>
	<?php
}

/**
 * Function to Display Attribute Mapping.
 *
 * @return void
 */
function mo_saml_display_attribute_mapping() {
	?>
	<div class="mo-saml-bootstrap-p-4 shadow-cstm mo-saml-bootstrap-bg-white mo-saml-bootstrap-rounded">
		<div class="mo-saml-bootstrap-row align-items-top">
			<div class="mo-saml-bootstrap-col-md-12">
				<h4 class="form-head">
					<span class="entity-info">Attribute Mapping
						<a href="https://developers.miniorange.com/docs/saml/wordpress/Attribute-Rolemapping" class="mo-saml-bootstrap-text-dark" target="_blank">
							<svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
								<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"></path>
								<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"></path>
							</svg>
						</a>
					</span>
				</h4>
			</div>
		</div>

		<div class="prem-info mo-saml-bootstrap-mt-5 mo-saml-bootstrap-d-block">
			<div class="prem-icn nameid-prem-img sso-btn-prem-img"><img class="crown_img" src="<?php echo esc_url( Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() . 'images/crown.webp' ); ?>">
				<p class="nameid-prem-text">The basic attributes are configurable in Standard, Premium, Enterprise and All-Inclusive plans. Custom Attributes are configurable in Premium and higher plans. <a href="<?php echo esc_url( Mo_Saml_External_Links::PRICING_PAGE ); ?>" target="_blank" class="mo-saml-bootstrap-text-warning">Click here to upgrade</a></p>
			</div>
			<div class="mo-saml-bootstrap-row align-items-top">
				<div class="mo-saml-bootstrap-col-md-3">
					<h6 class="mo-saml-bootstrap-text-secondary">Username (required) </span>:</h6>
				</div>
				<div class="mo-saml-bootstrap-col-md-6">
					<p>NameID</p>
				</div>
			</div>
			<div class="mo-saml-bootstrap-row align-items-top mo-saml-bootstrap-mt-4">
				<div class="mo-saml-bootstrap-col-md-3">
					<h6 class="mo-saml-bootstrap-text-secondary">Email (required) :</h6>
				</div>
				<div class="mo-saml-bootstrap-col-md-6">
					<p>NameID</p>
				</div>
			</div>
			<div class="mo-saml-bootstrap-row align-items-top mo-saml-bootstrap-mt-4">
				<div class="mo-saml-bootstrap-col-md-3">
					<h6 class="mo-saml-bootstrap-text-secondary">First Name :</h6>
				</div>
				<div class="mo-saml-bootstrap-col-md-6">
					<input type="text" name="saml_am_first_name" placeholder="Enter attribute name for First Name" class="mo-saml-bootstrap-w-100 mo-saml-bootstrap-bg-light cursor-disabled" value="" disabled>
				</div>
			</div>
			<div class="mo-saml-bootstrap-row align-items-top mo-saml-bootstrap-mt-4">
				<div class="mo-saml-bootstrap-col-md-3">
					<h6 class="mo-saml-bootstrap-text-secondary">Last Name :</h6>
				</div>
				<div class="mo-saml-bootstrap-col-md-6">
					<input type="text" name="saml_am_last_name" placeholder="Enter attribute name for Last Name" class="mo-saml-bootstrap-w-100 mo-saml-bootstrap-bg-light cursor-disabled" value="" disabled>
				</div>
			</div>
			<div class="mo-saml-bootstrap-row align-items-top mo-saml-bootstrap-mt-4">
				<div class="mo-saml-bootstrap-col-md-3">
					<h6 class="mo-saml-bootstrap-text-secondary">Group/Role :</h6>
				</div>
				<div class="mo-saml-bootstrap-col-md-6">
					<input type="text" name="" placeholder="Enter attribute name for Group/Role" class="mo-saml-bootstrap-w-100 mo-saml-bootstrap-bg-light cursor-disabled" value="" disabled>
				</div>
			</div>
			<div class="mo-saml-bootstrap-row align-items-top mo-saml-bootstrap-mt-4">
				<div class="mo-saml-bootstrap-col-md-3">
					<h6 class="mo-saml-bootstrap-text-secondary">Map Custom Attributes</h6>
				</div>
				<div class="mo-saml-bootstrap-col-md-6">
					<p>Customized Attribute Mapping means you can map any attribute of the IDP to the usermeta table of your database.</p>
				</div>
			</div>

		</div>
		<div class="align-items-top mo-saml-bootstrap-mt-5 prem-info">
			<div class="prem-icn anonymous-prem-img sso-btn-prem-img"><img class="crown_img" src="<?php echo esc_url( Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() . 'images/crown.webp' ); ?>">
				<p class="anonymous-text">Enable this option if you want to allow users to login to the WordPress site without creating a WordPress user account for them. <a href="<?php echo esc_url( Mo_Saml_External_Links::PRICING_PAGE ); ?>" target="_blank" class="mo-saml-bootstrap-text-warning">Available in Paid Plugin</a></p>
			</div>
			<div class="mo-saml-bootstrap-row mo-saml-bootstrap-align-items-center">
				<div class="mo-saml-bootstrap-col-md-3">
					<h6 class="mo-saml-bootstrap-text-secondary">Anonymous Login: </h6>
				</div>
				<div class="mo-saml-bootstrap-col-md-8">
					<section>
						<input type="checkbox" id="switch" class="mo-saml-switch cursor-disabled" disabled /><label class="mo-saml-switch-label" for="switch">Toggle</label>
					</section>
				</div>
			</div>
		</div>

	</div>
	<?php

}

/**
 * Function to Display Role Mapping.
 *
 * @param string $default_role it is the default role of the user.
 * @param mixed  $roles retrieves the list of WP role names.
 * @return void
 */
function mo_saml_display_role_mapping( $default_role, $roles ) {
	?>
	<form name="saml_form_am_role_mapping" method="post" action="">
		<?php
		wp_nonce_field( 'login_widget_saml_role_mapping' );
		?>
		<input type="hidden" name="option" value="login_widget_saml_role_mapping" />

		<div class="mo-saml-bootstrap-p-4 shadow-cstm mo-saml-bootstrap-bg-white mo-saml-bootstrap-rounded mo-saml-bootstrap-mt-4">
			<div class="mo-saml-bootstrap-row align-items-top">
				<div class="mo-saml-bootstrap-col-md-12">
					<h4 class="form-head">
					<span class="entity-info">Role Mapping
						<a href="https://developers.miniorange.com/docs/saml/wordpress/Attribute-Rolemapping#Role-Mapping" class="mo-saml-bootstrap-text-dark" target="_blank">
							<svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
								<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"></path>
								<path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"></path>
							</svg>
						</a>
					</span>
					</h4>
				</div>
			</div>
			<div class="mo-saml-bootstrap-align-items-center mo-saml-bootstrap-mt-5"><b>NOTE:</b> Role will be assigned only to new users. Existing WordPress users' role remains same.</div>
			<div class="mo-saml-bootstrap-row mo-saml-bootstrap-align-items-center mo-saml-bootstrap-mt-5">
				<div class="mo-saml-bootstrap-col-md-3">
					<h5>Default Role : </h5>
				</div>
				<div class="mo-saml-bootstrap-col-md-3">
					<select id="saml_am_default_user_role" name="saml_am_default_user_role">
						<?php
						wp_dropdown_roles( $default_role );
						?>
					</select>
				</div>
				<div class="mo-saml-bootstrap-col-md-4">
					<input type="submit" class="btn-cstm mo-saml-bootstrap-bg-info mo-saml-bootstrap-rounded" name="submit" value="Update">
				</div>
			</div>
			<div class="prem-info mo-saml-bootstrap-mt-5">
				<div class="prem-icn role-prem-img sso-btn-prem-img"><img class="crown_img" src="<?php echo esc_url( Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() . 'images/crown.webp' ); ?>">
					<p class="role-prem-text">Customized Role Mapping options are configurable in the Premium, Enterprise and All-Inclusive versions of the plugin. <a href="<?php echo esc_url( Mo_Saml_External_Links::PRICING_PAGE ); ?>" target="_blank" class="mo-saml-bootstrap-text-warning">Click here to upgrade</a></p>
				</div>
				<div class="mo-saml-bootstrap-row align-items-top mo-saml-bootstrap-mt-4 mo-saml-bootstrap-col-md-12">
					<div class="mo-saml-bootstrap-col-md-7">
						<h6 class="mo-saml-bootstrap-text-secondary">Do not auto create users if roles are not mapped here :</h6>
					</div>
					<div class="mo-saml-bootstrap-col-md-5">
						<input type="checkbox" id="switch" class="mo-saml-switch cursor-disabled" disabled /><label class="mo-saml-switch-label" for="switch">Toggle</label>

						<p class="mt-2">Enable this option if you do not want the unmapped users to register into your site via SSO.</p>
					</div>
				</div>
				<div class="mo-saml-bootstrap-row align-items-top mo-saml-bootstrap-mt-4 mo-saml-bootstrap-col-md-12">
					<div class="mo-saml-bootstrap-col-md-7">
						<h6 class="mo-saml-bootstrap-text-secondary">Do not assign role to unlisted users :</h6>
					</div>
					<div class="mo-saml-bootstrap-col-md-5">
						<input type="checkbox" id="switch" class="mo-saml-switch cursor-disabled" disabled /><label class="mo-saml-switch-label" for="switch">Toggle</label>
						<p class="mt-2">Enable this option if you do not want to assign any roles to unmapped users.</p>
					</div>
				</div>
			</div>

			<div class="mo-saml-bootstrap-d-block prem-info mo-saml-bootstrap-mt-5">
				<div class="prem-icn role-admin-prem-img sso-btn-prem-img"><img class="crown_img" src="<?php echo esc_url( Mo_SAML_Utilities::mo_saml_get_plugin_dir_url() . 'images/crown.webp' ); ?>">
					<p class="role-admin-prem-text">Customized Role Mapping options are configurable in the Premium, Enterprise and All-Inclusive versions of the plugin. <a href="<?php echo esc_url( Mo_Saml_External_Links::PRICING_PAGE ); ?>" target="_blank" class="mo-saml-bootstrap-text-warning">Click here to upgrade</a></p>
				</div>
				<?php
				foreach ( $roles as $role_value => $role_name ) {
					?>
					<div class="mo-saml-bootstrap-row align-items-top mo-saml-bootstrap-mt-4">
						<div class="mo-saml-bootstrap-col-md-3">
							<h6 class="mo-saml-bootstrap-text-secondary"><?php echo esc_html( $role_name ); ?> :</h6>
						</div>
						<div class="mo-saml-bootstrap-col-md-7">
							<input type="text" name="" placeholder="Semi-colon(;) separated Group/Role value for <?php echo esc_html( $role_name ); ?>" class="mo-saml-bootstrap-w-100 mo-saml-bootstrap-bg-light cursor-disabled" value="" disabled>
						</div>
					</div>
					<?php
				}
				?>
			</div>
		</div>
	</form>

	<?php

}

/**
 * Function to display user attributes sent by the identity provider.
 *
 * @return void
 */
function mo_saml_display_attrs_list() {
	$idp_attrs = get_option( Mo_Saml_Options_Test_Configuration::TEST_CONFIG_ATTRS );
	$idp_attrs = maybe_unserialize( $idp_attrs );
	if ( ! empty( $idp_attrs ) ) {
		?>
		<div class="mo-saml-bootstrap-bg-white mo-saml-bootstrap-text-center shadow-cstm mo-saml-bootstrap-rounded contact-form-cstm mo-saml-bootstrap-p-4">
			<h4><?php esc_html_e( 'Attributes sent by the Identity Provider', 'miniorange-saml-20-single-sign-on' ); ?>:</h4>
			<div>
				<table style="table-layout: fixed;border: 1px solid #fff;width: 100%;background-color: #e9f0ff;">
					<tr style="text-align:center;background:#d3e1ff;">
						<td style="font-weight:bold; border:2.5px solid #fff;	padding:2%;	word-wrap:break-word;"><?php esc_html_e( 'ATTRIBUTE NAME', 'miniorange-saml-20-single-sign-on' ); ?></td>
						<td style="font-weight:bold; border:2.5px solid #fff;	padding:2%;	word-wrap:break-word;"><?php esc_html_e( 'ATTRIBUTE VALUE', 'miniorange-saml-20-single-sign-on' ); ?></td>
					</tr>
					<?php
					foreach ( $idp_attrs as $attr_name => $values ) {
						if ( is_array( $values ) ) {
							$attr_values = implode( '<hr>', $values );
						} else {
							$attr_values = esc_html( $values );
						}
						$allowed_html = array( 'hr' => array() );
						?>
						<tr style="text-align:center;">
							<td style="font-weight:bold; border:2.5px solid #fff;	padding:2%;	word-wrap:break-word;"> <?php echo esc_html( $attr_name ); ?></td>
							<td style="font-weight:bold; border:2.5px solid #fff;	padding:2%;	word-wrap:break-word;"> <?php echo wp_kses( $attr_values, $allowed_html ); ?> </td>
						</tr>
					<?php } ?>

				</table>
				<br />
				<p style="text-align:center;"><input type="button" class="btn-cstm mo-saml-bootstrap-rounded mo-saml-bootstrap-mt-3" value="<?php echo esc_attr_x( 'Clear Attributes List', '', 'miniorange-saml-20-single-sign-on' ); ?>" onclick="document.forms['attrs_list_form'].submit();"></p>
				<div style="padding-right:8px;">
					<p><b><?php esc_html_e( 'NOTE', 'miniorange-saml-20-single-sign-on' ); ?> :</b> <?php esc_html_e( 'Please clear this list after configuring the plugin to hide your confidential attributes.', 'miniorange-saml-20-single-sign-on' ); ?><br />
						<?php esc_html_e( 'Click on Test configuration in Service Provider Setup tab to populate the list again.', 'miniorange-saml-20-single-sign-on' ); ?></p>
				</div>
				<form method="post" action="" id="attrs_list_form">
					<?php wp_nonce_field( 'clear_attrs_list' ); ?>
					<input type="hidden" name="option" value="clear_attrs_list">
				</form>
			</div>
		</div> 
		<?php
	}
}
