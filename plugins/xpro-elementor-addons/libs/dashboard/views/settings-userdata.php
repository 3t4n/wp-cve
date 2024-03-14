<?php

$xpro_elementor_user_data = $this->utils->get_option( 'xpro_elementor_user_data', array() );
$pro_active               = ( in_array( 'xpro-elementor-addons-pro/xpro-elementor-addons-pro.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) );

?>

<div class="xpro-dashboard-tab-content xpro-dashboard-tab-widgets" id="xpro-userdata">
	<div class="xpro-dashboard-tab-content-inner">

		<!--Header Area-->
		<div class="xpro-dashboard-tab-content-header">

			<!--Header Title-->
			<h2 class="xpro-dashboard-tab-content-title">
				<?php echo esc_html__( 'User Data', 'xpro-elementor-addons' ); ?>
			</h2>

			<!--Button Save Changes-->
			<button class="xpro-dashboard-save-button">
				<i class="dashicons dashicons-update"></i>
				<?php echo esc_html__( 'Save Changes', 'xpro-elementor-addons' ); ?>
			</button>

		</div>

		<div class="xpro-dashboard-item-wrapper">
			<div class="xpro-row">

				<!--Contact Form Email-->
				<div class="xpro-col-lg-4">
					<div class="xpro-dashboard-text-form-wrapper">
						<h4 class="xpro-dashboard-text-form-title">
							<?php esc_html_e( 'Contact Form', 'xpro-elementor-addons' ); ?>
						</h4>
						<hr class="xpro-dashboard-text-form-seperator">
						<p class="xpro-dashboard-text-form-description">
							Set your default email address to receive emails from Simple Contact Form.
						</p>
						<?php
						$this->utils->input(
							array(
								'type'        => 'email',
								'name'        => 'xpro_elementor_user_data[contact_form][mail]',
								'label'       => esc_html__( 'E-Mail', 'xpro-elementor-addons' ),
								'placeholder' => 'Site@email.com',
								'value'       => isset( $xpro_elementor_user_data['contact_form']['mail'] ) ? $xpro_elementor_user_data['contact_form']['mail'] : get_option( 'admin_email' ),
							)
						);
						?>
					</div>
				</div>

				<!--OpenAI-->
				<div class="xpro-col-lg-4">
					<div class="xpro-dashboard-text-form-wrapper">
						<h4 class="xpro-dashboard-text-form-title">
							<?php esc_html_e( 'OpenAi Access', 'xpro-elementor-addons' ); ?>
						</h4>
						<hr class="xpro-dashboard-text-form-seperator">
						<p class="xpro-dashboard-text-form-description">
							Go to <a href="https://openai.com/api/" target="_blank">https://openai.com/api/</a> to create your API key.
						</p>
						<?php
						$this->utils->input(
							array(
								'type'        => 'text',
								'name'        => 'xpro_elementor_user_data[openAi][api_key]',
								'label'       => esc_html__( 'API Key', 'xpro-elementor-addons' ),
								'placeholder' => __( 'Enter Your Key Here', 'xpro-elementor-addons' ),
								'value'       => ( ! isset( $xpro_elementor_user_data['openAi']['api_key'] ) ) ? '' : ( $xpro_elementor_user_data['openAi']['api_key'] ),
							)
						);
						?>
					</div>
				</div>

				<!--MailChimp-->
				<div class="xpro-col-lg-4">
					<div class="xpro-dashboard-text-form-wrapper pro <?php echo esc_attr( $this->utils->is_widget_active_class( 'mailchimp', $pro_active ) ); ?>">
						<h4 class="xpro-dashboard-text-form-title">
							<?php esc_html_e( 'MailChimp Access', 'xpro-elementor-addons' ); ?>
						</h4>
						<hr class="xpro-dashboard-text-form-seperator">
						<p class="xpro-dashboard-text-form-description">
							Go to <a href="https://mailchimp.com/help/about-api-keys/" target="_blank">https://mailchimp.com/help/about-api-keys/</a> to create your API key.
						</p>
						<?php
						$this->utils->input(
							array(
								'type'        => 'text',
								'name'        => 'xpro_elementor_user_data[mailchimp][api_key]',
								'label'       => esc_html__( 'API Key', 'xpro-elementor-addons' ),
								'placeholder' => __( 'Enter Your Key Here', 'xpro-elementor-addons' ),
								'value'       => ( ! isset( $xpro_elementor_user_data['mailchimp']['api_key'] ) ) ? '' : ( $xpro_elementor_user_data['mailchimp']['api_key'] ),
							)
						);
						?>
					</div>
				</div>

				<!--ReCaptcha-->
				<div class="xpro-col-lg-4">
					<div class="xpro-dashboard-text-form-wrapper">
						<h4 class="xpro-dashboard-text-form-title">
							<?php esc_html_e( 'reCaptcha Access', 'xpro-elementor-addons' ); ?>
						</h4>
						<hr class="xpro-dashboard-text-form-seperator">
						<p class="xpro-dashboard-text-form-description">
							Go to your Google <a href="https://accounts.google.com/">reCAPTCHA</a> > Account > Generate Keys (reCAPTCHA V2 >
							Invisible)
						</p>
						<?php
						$this->utils->input(
							array(
								'type'        => 'text',
								'name'        => 'xpro_elementor_user_data[recaptcha][site_key]',
								'label'       => esc_html__( 'Site Key', 'xpro-elementor-addons' ),
								'placeholder' => __( 'Enter Site Key Here', 'xpro-elementor-addons' ),
								'value'       => ( ! isset( $xpro_elementor_user_data['recaptcha']['site_key'] ) ) ? '' : ( $xpro_elementor_user_data['recaptcha']['site_key'] ),
							)
						);
						$this->utils->input(
							array(
								'type'        => 'text',
								'name'        => 'xpro_elementor_user_data[recaptcha][secret_key]',
								'label'       => esc_html__( 'Secret Key', 'xpro-elementor-addons' ),
								'placeholder' => __( 'Enter Secret Key Here', 'xpro-elementor-addons' ),
								'value'       => ( ! isset( $xpro_elementor_user_data['recaptcha']['secret_key'] ) ) ? '' : ( $xpro_elementor_user_data['recaptcha']['secret_key'] ),
							)
						);
						?>
					</div>
				</div>

				<!--Instagram-->
				<div class="xpro-col-lg-4">
					<div class="xpro-dashboard-text-form-wrapper pro <?php echo esc_attr( $this->utils->is_widget_active_class( 'instagra-feed', $pro_active ) ); ?>">
						<h4 class="xpro-dashboard-text-form-title">
							<?php esc_html_e( 'Instagram Access', 'xpro-elementor-addons' ); ?>
						</h4>
						<hr class="xpro-dashboard-text-form-seperator">
						<p class="xpro-dashboard-text-form-description">
							Go to <a href="https://developers.facebook.com/docs/instagram-basic-display-api/getting-started" target="_blank">https://developers.facebook.com/</a> for create your Consumer key and Access Token.
						</p>
						<?php
						$this->utils->input(
							array(
								'type'        => 'text',
								'name'        => 'xpro_elementor_user_data[instagram][user_id]',
								'label'       => esc_html__( 'User ID', 'xpro-elementor-addons' ),
								'placeholder' => __( 'Enter User ID Here', 'xpro-elementor-addons' ),
								'value'       => ( ! isset( $xpro_elementor_user_data['instagram']['user_id'] ) ) ? '' : ( $xpro_elementor_user_data['instagram']['user_id'] ),
							)
						);
						$this->utils->input(
							array(
								'type'        => 'text',
								'name'        => 'xpro_elementor_user_data[instagram][access_token]',
								'label'       => esc_html__( 'Access Token', 'xpro-elementor-addons' ),
								'placeholder' => __( 'Enter Access Token Here', 'xpro-elementor-addons' ),
								'value'       => ( ! isset( $xpro_elementor_user_data['instagram']['access_token'] ) ) ? '' : ( $xpro_elementor_user_data['instagram']['access_token'] ),
							)
						);
						?>
					</div>
				</div>

				<!--Facebook-->
				<div class="xpro-col-lg-4">
					<div class="xpro-dashboard-text-form-wrapper pro <?php echo esc_attr( $this->utils->is_widget_active_class( 'facebook-feed', $pro_active ) ); ?>">
						<h4 class="xpro-dashboard-text-form-title">
							<?php esc_html_e( 'Facebook Access', 'xpro-elementor-addons' ); ?>
						</h4>
						<hr class="xpro-dashboard-text-form-seperator">
						<p class="xpro-dashboard-text-form-description">
							Go to <a href="https://developers.facebook.com/docs/development/register" target="_blank">https://developers.facebook.com</a> for create your Consumer key and Access Token.
						</p>
						<?php
						$this->utils->input(
							array(
								'type'        => 'text',
								'name'        => 'xpro_elementor_user_data[facebook][app_id]',
								'label'       => esc_html__( 'App ID', 'xpro-elementor-addons' ),
								'placeholder' => __( 'Enter ID Here', 'xpro-elementor-addons' ),
								'value'       => ( ! isset( $xpro_elementor_user_data['facebook']['app_id'] ) ) ? '' : ( $xpro_elementor_user_data['facebook']['app_id'] ),
							)
						);
						$this->utils->input(
							array(
								'type'        => 'text',
								'name'        => 'xpro_elementor_user_data[facebook][access_token]',
								'label'       => esc_html__( 'Access Token', 'xpro-elementor-addons' ),
								'placeholder' => __( 'Enter Access Token Here', 'xpro-elementor-addons' ),
								'value'       => ( ! isset( $xpro_elementor_user_data['facebook']['access_token'] ) ) ? '' : ( $xpro_elementor_user_data['facebook']['access_token'] ),
							)
						);
						?>
					</div>
				</div>

				<!--Google Map-->
				<div class="xpro-col-lg-4">
					<div class="xpro-dashboard-text-form-wrapper pro <?php echo esc_attr( $this->utils->is_widget_active_class( 'google-map', $pro_active ) ); ?>">
						<h4 class="xpro-dashboard-text-form-title">
							<?php esc_html_e( 'Google API', 'xpro-elementor-addons' ); ?>
						</h4>
						<hr class="xpro-dashboard-text-form-seperator">
						<p class="xpro-dashboard-text-form-description">
							Visit <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">developers.google.com</a> generate your API key, and insert it here.
						</p>
						<?php
						$this->utils->input(
							array(
								'type'        => 'text',
								'name'        => 'xpro_elementor_user_data[google_map][api]',
								'label'       => esc_html__( 'Google API', 'xpro-elementor-addons' ),
								'placeholder' => esc_html__( 'Enter Google API Here', 'xpro-elementor-addons' ),
								'value'       => ( ! isset( $xpro_elementor_user_data['google_map']['api'] ) ) ? '' : ( $xpro_elementor_user_data['google_map']['api'] ),
							)
						);
						?>
					</div>
				</div>

				<!--Street Map-->
				<div class="xpro-col-lg-4">
					<div class="xpro-dashboard-text-form-wrapper pro <?php echo esc_attr( $this->utils->is_widget_active_class( 'street-map', $pro_active ) ); ?>">
						<h4 class="xpro-dashboard-text-form-title">
							<?php esc_html_e( 'Street Map API', 'xpro-elementor-addons' ); ?>
						</h4>
						<hr class="xpro-dashboard-text-form-seperator">
						<p class="xpro-dashboard-text-form-description">
							Go to <a href="https://account.mapbox.com/access-tokens/" target="_blank">www.mapbox.com</a>
							and generate the API key to insert it here.
						</p>
						<?php
						$this->utils->input(
							array(
								'type'        => 'text',
								'name'        => 'xpro_elementor_user_data[street_map][api]',
								'label'       => esc_html__( 'Street Map API', 'xpro-elementor-addons' ),
								'placeholder' => esc_html__( 'Enter Street Map API Here', 'xpro-elementor-addons' ),
								'value'       => ( ! isset( $xpro_elementor_user_data['street_map']['api'] ) ) ? '' : ( $xpro_elementor_user_data['street_map']['api'] ),
							)
						);
						?>
					</div>
				</div>

			</div>
		</div>

	</div>
</div>
