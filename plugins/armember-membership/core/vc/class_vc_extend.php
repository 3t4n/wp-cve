<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

class ARMLITE_VCExtend {

	protected static $instance  = null;
	var $is_membership_vdextend = 0;

	public function __construct() {
		add_action( 'init', array( $this, 'ARM_arm_form' ) );
		add_action( 'init', array( $this, 'ARM_arm_edit_profile' ) );
		add_action( 'init', array( $this, 'ARM_arm_logout' ) );

		add_action( 'init', array( $this, 'ARM_arm_setup' ) );
		add_action( 'init', array( $this, 'ARM_arm_member_transaction' ) );
		add_action( 'init', array( $this, 'ARM_arm_account_detail' ) );
		add_action( 'init', array( $this, 'ARM_arm_close_account' ) );
		add_action( 'init', array( $this, 'ARM_arm_membership' ) );

		add_action( 'init', array( $this, 'ARM_arm_username' ) );
		add_action( 'init', array( $this, 'ARM_arm_user_plan' ) );
		add_action( 'init', array( $this, 'ARM_arm_displayname' ) );
		add_action( 'init', array( $this, 'ARM_arm_firstname_lastname' ) );
		add_action( 'init', array( $this, 'ARM_arm_avatar' ) );
		add_action( 'init', array( $this, 'ARM_arm_usermeta' ) );

		add_action( 'init', array( $this, 'ARM_arm_user_planinfo' ) );
		add_action( 'init', array( $this, 'ARM_init_all_shortcode' ) );
	}

	public function ARM_init_all_shortcode() {
		if ( function_exists( 'vc_add_shortcode_param' ) ) {
			vc_add_shortcode_param( 'ARM_arm_form_shortcode', array( $this, 'ARM_arm_form_html' ), MEMBERSHIPLITE_URL . '/core/vc/arm_vc.js' );
			vc_add_shortcode_param( 'ARM_arm_edit_profile_shortcode', array( $this, 'ARM_arm_edit_profile_html' ), MEMBERSHIPLITE_URL . '/core/vc/arm_vc.js' );
			vc_add_shortcode_param( 'ARM_arm_logout_shortcode', array( $this, 'ARM_arm_logout_html' ), MEMBERSHIPLITE_URL . '/core/vc/arm_vc.js' );

			vc_add_shortcode_param( 'ARM_arm_setup_shortcode', array( $this, 'ARM_arm_setup_html' ), MEMBERSHIPLITE_URL . '/core/vc/arm_vc.js' );
			vc_add_shortcode_param( 'ARM_arm_member_transaction_shortcode', array( $this, 'ARM_arm_member_transaction_html' ), MEMBERSHIPLITE_URL . '/core/vc/arm_vc.js' );
			vc_add_shortcode_param( 'ARM_arm_account_detail_shortcode', array( $this, 'ARM_arm_account_detail_html' ), MEMBERSHIPLITE_URL . '/core/vc/arm_vc.js' );
			vc_add_shortcode_param( 'ARM_arm_close_account_shortcode', array( $this, 'ARM_arm_close_account_html' ), MEMBERSHIPLITE_URL . '/core/vc/arm_vc.js' );
			vc_add_shortcode_param( 'ARM_arm_membership_shortcode', array( $this, 'ARM_arm_membership_html' ), MEMBERSHIPLITE_URL . '/core/vc/arm_vc.js' );

			vc_add_shortcode_param( 'ARM_arm_username_shortcode', array( $this, 'ARM_arm_username_html' ), MEMBERSHIPLITE_URL . '/core/vc/arm_vc.js' );
			vc_add_shortcode_param( 'ARM_arm_user_plan_shortcode', array( $this, 'ARM_arm_user_plan_html' ), MEMBERSHIPLITE_URL . '/core/vc/arm_vc.js' );
			vc_add_shortcode_param( 'ARM_arm_displayname_shortcode', array( $this, 'ARM_arm_displayname_html' ), MEMBERSHIPLITE_URL . '/core/vc/arm_vc.js' );
			vc_add_shortcode_param( 'ARM_arm_firstname_lastname_shortcode', array( $this, 'ARM_arm_firstname_lastname_html' ), MEMBERSHIPLITE_URL . '/core/vc/arm_vc.js' );
			vc_add_shortcode_param( 'ARM_arm_avatar_shortcode', array( $this, 'ARM_arm_avatar_html' ), MEMBERSHIPLITE_URL . '/core/vc/arm_vc.js' );
			vc_add_shortcode_param( 'ARM_arm_usermeta_shortcode', array( $this, 'ARM_arm_usermeta_html' ), MEMBERSHIPLITE_URL . '/core/vc/arm_vc.js' );

			vc_add_shortcode_param( 'ARM_arm_user_planinfo_shortcode', array( $this, 'ARM_arm_user_planinfo_html' ), MEMBERSHIPLITE_URL . '/core/vc/arm_vc.js' );

		}
	}

	public function ARM_arm_form() {
		global $arm_lite_version, $ARMemberLite;
		if ( function_exists( 'vc_map' ) ) {
			vc_map(
				array(
					'name'              => esc_html__( 'ARMember Form', 'armember-membership' ),
					'description'       => '',
					'base'              => 'arm_form',
					'category'          => 'armember-membership',
					'class'             => '',
					'controls'          => 'full',
					'icon'              => 'arm_vc_icon',
					'admin_enqueue_css' => array( MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css' ),
					'front_enqueue_css' => MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css',
					'params'            => array(
						array(
							'type'        => 'ARM_arm_form_shortcode',
							'heading'     => false,
							'param_name'  => 'id',
							'value'       => '',
							'description' => '',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_form_shortcode',
							'heading'     => false,
							'param_name'  => 'logged_in_message',
							'value'       => '',
							'description' => '',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_form_shortcode',
							'heading'     => false,
							'param_name'  => 'assign_default_plan',
							'value'       => '',
							'description' => '',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_form_shortcode',
							'heading'     => false,
							'param_name'  => 'form_position',
							'value'       => 'center',
							'description' => '',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_form_shortcode',
							'heading'     => false,
							'param_name'  => 'popup',
							'value'       => false,
							'description' => '',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_form_shortcode',
							'heading'     => false,
							'param_name'  => 'link_type',
							'value'       => 'link',
							'description' => '',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_form_shortcode',
							'heading'     => false,
							'param_name'  => 'link_title',
							'value'       => 'Click here to open Form',
							'description' => '',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_form_shortcode',
							'heading'     => false,
							'param_name'  => 'overlay',
							'value'       => '0.6',
							'description' => '',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_form_shortcode',
							'heading'     => false,
							'param_name'  => 'modal_bgcolor',
							'value'       => '#000000',
							'description' => '',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_form_shortcode',
							'heading'     => false,
							'param_name'  => 'popup_height',
							'value'       => 'auto',
							'description' => '',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_form_shortcode',
							'heading'     => false,
							'param_name'  => 'popup_width',
							'value'       => '700',
							'description' => '',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_form_shortcode',
							'heading'     => false,
							'param_name'  => 'link_css',
							'value'       => 'color: #000000;',
							'description' => '',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_form_shortcode',
							'heading'     => false,
							'param_name'  => 'link_hover_css',
							'value'       => 'color: #ffffff;',
							'description' => '',
							'admin_label' => true,
						),
					),
				)
			);
		}
	}

	public function ARM_arm_form_html( $settings, $value ) {
		global $wpdb, $ARMemberLite, $arm_slugs, $arm_shortcodes, $arm_members_class, $arm_global_settings, $arm_email_settings,  $arm_member_forms, $arm_subscription_plans;

		echo '<input id="' . esc_attr( $settings['param_name'] ) . '" name="' . esc_attr( $settings['param_name'] ) . '" class=" ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_armfield" type="hidden" value="' . esc_attr( $value ) . '" />';

		if ( $this->is_membership_vdextend == 0 ) {
			$this->is_membership_vdextend = 1;
			?>
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm-font-awesome.css" />
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm_tinymce.css" />
			<div class="arm_tinymce_shortcode_options_container arm_shortcode_options_popup_wrapper">
				<div class="arm_tinymce_content_block accordion_menu">
					<!-- *********************[arm_form]********************* -->
					<div class="arm_tinymce_shortcode_content">
						<div class="arm_shortcode_content_header accordion_header" style="box-shadow:none;border-bottom: none;"><?php esc_html_e( 'ARMember Form', 'armember-membership' ); ?></div>
						<div class="arm_shortcode_detail_wrapper">
							<div class="arm_shortcode_detail_container">
								<div class="arm_shortcode_description"><?php // esc_html_e('Member forms shortcode.', 'armember-membership'); ?></div>
								<div class="arm_shortcode_generator_form arm_generator_arm_form" style="width: 660px;">
									<form onsubmit="return false;">
										<table class="arm_shortcode_option_table">
											<tr>
												<th><?php esc_html_e( 'Select a form to insert into page', 'armember-membership' ); ?></th>
												<td>
													<?php
													$arm_forms   = $arm_member_forms->arm_get_all_member_forms( 'arm_form_id, arm_form_label, arm_form_type' );
													$armFormList = '';
													if ( ! empty( $arm_forms ) ) {
														foreach ( $arm_forms as $_form ) {
															$armFormList .= '<li class="arm_shortcode_form_id_li" data-form-type="' . $_form['arm_form_type'] . '" data-label="' . strip_tags( stripslashes( $_form['arm_form_label'] ) ) . ' &nbsp;(ID: ' . $_form['arm_form_id'] . ')' . '" data-value="' . $_form['arm_form_id'] . '">' . strip_tags( stripslashes( $_form['arm_form_label'] ) ) . ' &nbsp;(ID: ' . $_form['arm_form_id'] . ')' . '</li>';
														}
													}
													?>
													<input type="hidden" id="arm_form_select" class="wpb_vc_param_value" name="id" value="" onChange="arm_show_hide_logged_in_message(this.value)" />
													<dl class="arm_selectbox column_level_dd">
														<dt><span></span><input type="text" style="display:none;" value="" class="arm_autocomplete"/><i class="armfa armfa-caret-down armfa-lg"></i></dt>
														<dd>
															<ul class="arm_form_select" data-id="arm_form_select">
																<li data-label="<?php esc_html_e( 'Select Form', 'armember-membership' ); ?>" data-value=""><?php esc_html_e( 'Select Form', 'armember-membership' ); ?></li>
																<?php echo $armFormList; //phpcs:ignore ?>
															</ul>
														</dd>
													</dl>
												</td>
											</tr>
											<tr id="arm_member_form_default_free_plan" style="display:none;">
												<th><?php esc_html_e( 'Assign Default Plan', 'armember-membership' ); ?></th>
												<td>
													<?php
													$all_plans    = $arm_subscription_plans->arm_get_all_subscription_plans( 'arm_subscription_plan_id, arm_subscription_plan_name' );
													$arm_planlist = '';
													if ( ! empty( $all_plans ) ) :
														foreach ( $all_plans as $plan ) :
															if ( ! $arm_subscription_plans->isFreePlanExist( $plan['arm_subscription_plan_id'] ) ) {
																continue; }
															$arm_planlist .= '<li class="arm_shortcode_form_id_li" data-label="' . stripslashes( $plan['arm_subscription_plan_name'] ) . '" data-value="' . $plan['arm_subscription_plan_id'] . '">' . stripslashes( $plan['arm_subscription_plan_name'] ) . '</li>';
														endforeach;
													endif;
													?>
													<input type="hidden" id="assign_default_plan" class="wpb_vc_param_value" name="assign_default_plan" value="" />
													<dl class="arm_selectbox column_level_dd" id="assign_default_plan_dd">
														<dt><span></span><input type="text" style="display:none;" value="" class="arm_autocomplete"/><i class="armfa armfa-caret-down armfa-lg"></i></dt>
														<dd>
															<ul class="assign_default_plan" data-id="assign_default_plan">
																<li data-label="<?php esc_html_e( 'Select Plan', 'armember-membership' ); ?>" data-value=""><?php esc_html_e( 'Select Plan', 'armember-membership' ); ?></li>
																<?php echo $arm_planlist; //phpcs:ignore ?>
															</ul>
														</dd>
													</dl>
												</td>
											</tr>
											<tr id="arm_member_form_logged_in_message" style="display:none;">
												<th><?php esc_html_e( 'Logged in Message', 'armember-membership' ); ?></th>
												<td>
													<input type="text" class="wpb_vc_param_value" name="logged_in_message" value="" id="logged_in_message" />
												</td>
											</tr>
											
											<tr id="arm_form_position_wrapper">
												<th><?php esc_html_e( 'Form Position', 'armember-membership' ); ?></th>
												<td>
													<input type="hidden" name="form_position" value="center" class="wpb_vc_param_value" id="arm_position_hidden" />
													<label class="form_position_type_radio">
														<input type="radio" name="arm_form_position" value="left" id="arm_position_left" onclick="arm_position_input()" class="arm_iradio" />
														<?php esc_html_e( 'Left', 'armember-membership' ); ?>
													</label>
													<label class="form_position_type_radio">
														<input type="radio" name="arm_form_position" value="center" checked="checked" id="arm_position_center" onclick="arm_position_input()" class="arm_iradio" />
														<?php esc_html_e( 'Center', 'armember-membership' ); ?>
													</label>
													<label class="form_position_type_radio">
														<input type="radio" name="arm_form_position" value="right" id="arm_position_right" onclick="arm_position_input()" class="arm_iradio" />
														<?php esc_html_e( 'Right', 'armember-membership' ); ?>
													</label>
												</td>
											</tr>
										</table>
									</form>
								</div>
								<div class="armclear"></div>
							</div>
						</div>
						<div class="armclear"></div>
					</div>

				</div>
			</div>
			<?php
		}
	}

	public function ARM_arm_edit_profile() {
		global $arm_lite_version, $ARMemberLite;
		if ( function_exists( 'vc_map' ) ) {
			vc_map(
				array(
					'name'              => esc_html__( 'ARMember Edit Profile', 'armember-membership' ),
					'description'       => '',
					'base'              => 'arm_edit_profile',
					'category'          => 'armember-membership',
					'class'             => '',
					'controls'          => 'full',
					'icon'              => 'arm_vc_icon',
					'admin_enqueue_css' => array( MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css' ),
					'front_enqueue_css' => MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css',
					'params'            => array(
						array(
							'type'        => 'ARM_arm_edit_profile_shortcode',
							'heading'     => false,
							'param_name'  => 'title',
							'value'       => esc_html__( 'Edit Profile', 'armember-membership' ),
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_edit_profile_shortcode',
							'heading'     => false,
							'param_name'  => 'message',
							'value'       => esc_html__( 'Your profile has been updated successfully.', 'armember-membership' ),
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_edit_profile_shortcode',
							'heading'     => false,
							'param_name'  => 'form_position',
							'value'       => esc_html__( 'Form Position', 'armember-membership' ),
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_edit_profile_shortcode',
							'heading'     => false,
							'param_name'  => 'view_profile',
							'value'       => true,
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_edit_profile_shortcode',
							'heading'     => false,
							'param_name'  => 'view_profile_link',
							'value'       => esc_html__( 'View Profile', 'armember-membership' ),
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_edit_profile_shortcode',
							'heading'     => false,
							'param_name'  => 'form_id',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_edit_profile_shortcode',
							'heading'     => false,
							'param_name'  => 'social_fields',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
					),
				)
			);
		}
	}

	public function ARM_arm_edit_profile_html( $settings, $value ) {
		global $wpdb, $ARMemberLite, $arm_slugs, $arm_shortcodes, $arm_members_class, $arm_global_settings, $arm_email_settings,  $arm_member_forms, $arm_subscription_plans,$arm_social_feature;

		$arm_forms = $arm_member_forms->arm_get_all_member_forms( 'arm_form_id, arm_form_label, arm_form_type' );

		echo '<input id="' . esc_attr( $settings['param_name'] ) . '" name="' . esc_attr( $settings['param_name'] ) . '" class=" ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_armfield" type="hidden" value="' . esc_attr( $value ) . '" />';

		if ( $this->is_membership_vdextend == 0 ) {
			$this->is_membership_vdextend = 1;
			?>
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm-font-awesome.css" />
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm_tinymce.css" />
			<input type="hidden" id="ajax_url_hidden" value="<?php echo admin_url( 'admin-ajax.php' ); //phpcs:ignore ?>" />
			<div class="arm_tinymce_shortcode_options_container arm_shortcode_options_popup_wrapper">
				<div class="arm_tinymce_content_block accordion_menu">
					<!-- *********************[arm_logout]********************* -->
					<div class="arm_tinymce_shortcode_content">
						<div class="arm_shortcode_content_header accordion_header" style="box-shadow:none;border-bottom: none;"><?php esc_html_e( 'ARMember Edit Profile', 'armember-membership' ); ?></div>
						<div class="arm_shortcode_detail_wrapper">
							<div class="arm_shortcode_detail_container">
								<div class="arm_shortcode_description"><?php // esc_html_e('Logout Shortcode.', 'armember-membership'); ?></div>
								<div class="arm_shortcode_generator_form arm_generator_arm_edit_profile">
									<form onsubmit="return false;">
										<table class="arm_shortcode_option_table">
											<tr>
												<th><?php esc_html_e( 'Select Form', 'armember-membership' ); ?></th>
												<td>
													<div>
														<input type="hidden" id="arm_edit_profile_form" name="form_id" value="" class="wpb_vc_param_value" 
														<?php
														if ( $arm_social_feature->isSocialFeature ) :
															?>
															 onChange="arm_get_social_fields(this.value);" <?php endif; ?> >
														<dl class="arm_selectbox column_level_dd">
															<dt>
															<span><?php esc_html_e( 'Select Form', 'armember-membership' ); ?></span>
															<input type="text" style="display:none;" value="<?php esc_html_e( 'Select Form', 'armember-membership' ); ?>" class="arm_autocomplete"/>
															<i class="armfa armfa-caret-down armfa-lg"></i>
															</dt>
															<dd>
																<ul data-id="arm_edit_profile_form">
																	<li data-label="<?php esc_html_e( 'Select Form', 'armember-membership' ); ?>" data-value="">
																		<?php esc_html_e( 'Select Form', 'armember-membership' ); ?>
																	</li>
																	<?php if ( ! empty( $arm_forms ) ) : ?>
																		<?php
																		foreach ( $arm_forms as $_form ) :
																			if ( $_form['arm_form_type'] == 'registration' ) {
																				$formTitle = strip_tags( stripslashes( $_form['arm_form_label'] ) ) . ' &nbsp;(ID: ' . $_form['arm_form_id'] . ')';
																				?>
																				<li class="arm_shortcode_form_id_li_edit_profile <?php echo esc_attr($_form['arm_form_type']); ?>" data-label="<?php echo esc_attr($formTitle); ?>" data-value="<?php echo esc_attr($_form['arm_form_id']); ?>"><?php echo $formTitle; //phpcs:ignore ?></li>
																				<?php
																			}
																		endforeach;
																		?>
			<?php endif; ?>
																</ul>
															</dd>
														</dl>
													</div>
												</td>
											</tr>
			<?php if ( $arm_social_feature->isSocialFeature ) : ?>
												<tr>
													<th><?php esc_html_e( 'Social Profile Fields', 'armember-membership' ); ?></th>
													<td>
														<div id="arm_social_fields_wrapper">
														</div>
													</td>
												</tr>
			<?php endif; ?>
											<tr>
												<th><?php esc_html_e( 'Form Position', 'armember-membership' ); ?></th>
												<td>
													<input type="hidden" name="form_position" class="wpb_vc_param_value" value="center" id="arm_edit_profile_position" />
													<label class="form_position_type_radio">
														<input type="radio" name="arm_edit_profile_position" value="left" id="arm_edit_profile_form_left" onclick="arm_edit_form_position_input()" class="arm_iradio" />
														<?php esc_html_e( 'Left', 'armember-membership' ); ?>
													</label>
													<label class="form_position_type_radio">
														<input type="radio" name="arm_edit_profile_position" value="center" checked="checked" id="arm_edit_profile_form_center" onclick="arm_edit_form_position_input()" class="arm_iradio" />
														<?php esc_html_e( 'Center', 'armember-membership' ); ?>
													</label>
													<label class="form_position_type_radio">
														<input type="radio" name="arm_edit_profile_position" value="right" id="arm_edit_profile_form_right" onclick="arm_edit_form_position_input()" class="arm_iradio" />
														<?php esc_html_e( 'Right', 'armember-membership' ); ?>
													</label>
												</td>
											</tr>
											<tr>
												<th><?php esc_html_e( 'Title', 'armember-membership' ); ?></th>
												<td><input type="text" name="title" class="wpb_vc_param_value" value="<?php esc_html_e( 'Edit Profile', 'armember-membership' ); ?>" id="arm_title" /></td>
											</tr>
											<tr>
												<th><?php esc_html_e( 'Message', 'armember-membership' ); ?></th>
												<td>
													<input type="text" class="wpb_vc_param_value" name="message" value="<?php esc_html_e( 'Your profile has been updated successfully.', 'armember-membership' ); ?>" id="arm_message" />
												</td>
											</tr>
											<tr>
												<th><?php esc_html_e( 'View Profile', 'armember-membership' ); ?></th>
												<td>
													<label>
														<input type='hidden' name='view_profile' id='arm_view_profile_hidden' value='true' class='wpb_vc_param_value' />
														<input type="checkbox" id="arm_view_profile_checkbox" name="view_profile" value="true" checked="checked" onclick="arm_view_profile_checked();" class="arm_icheckbox" />
														<span><?php esc_html_e( 'View Profile', 'armember-membership' ); ?></span>
													</label>
												</td>
											</tr>
											<tr>
												<th><?php esc_html_e( 'View Profile Link Label', 'armember-membership' ); ?></th>
												<td>
													<input type="text" class="wpb_vc_param_value" name="view_profile_link" value="<?php esc_html_e( 'View Profile', 'armember-membership' ); ?>" id="view_profile_link_label" />
												</td>
											</tr>
										</table>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}

	public function ARM_arm_logout() {
		global $arm_lite_version, $ARMemberLite;
		if ( function_exists( 'vc_map' ) ) {
			vc_map(
				array(
					'name'              => esc_html__( 'ARMember Logout', 'armember-membership' ),
					'description'       => '',
					'base'              => 'arm_logout',
					'category'          => 'armember-membership',
					'class'             => '',
					'controls'          => 'full',
					'icon'              => 'arm_vc_icon',
					'admin_enqueue_css' => array( MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css' ),
					'front_enqueue_css' => MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css',
					'params'            => array(
						array(
							'type'        => 'ARM_arm_logout_shortcode',
							'heading'     => false,
							'param_name'  => 'label',
							'value'       => esc_html__( 'Logout', 'armember-membership' ),
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_logout_shortcode',
							'heading'     => false,
							'param_name'  => 'type',
							'value'       => 'link',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_logout_shortcode',
							'heading'     => false,
							'param_name'  => 'user_info',
							'value'       => 'true',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_logout_shortcode',
							'heading'     => false,
							'param_name'  => 'redirect_to',
							'value'       => ARMLITE_HOME_URL,
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_logout_shortcode',
							'heading'     => false,
							'param_name'  => 'link_css',
							'value'       => 'color: #000000;',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_logout_shortcode',
							'heading'     => false,
							'param_name'  => 'link_hover_css',
							'value'       => 'color: #ffffff;',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
					),
				)
			);
		}
	}

	public function ARM_arm_logout_html( $settings, $value ) {
		global $wpdb, $ARMemberLite, $arm_slugs, $arm_shortcodes, $arm_members_class, $arm_global_settings, $arm_email_settings,  $arm_member_forms, $arm_subscription_plans;

		echo '<input id="' . esc_attr( $settings['param_name'] ) . '" name="' . esc_attr( $settings['param_name'] ) . '" class=" ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_armfield" type="hidden" value="' . esc_attr( $value ) . '" />';

		if ( $this->is_membership_vdextend == 0 ) {
			$this->is_membership_vdextend = 1;
			?>
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm-font-awesome.css" />
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm_tinymce.css" />
			<div class="arm_tinymce_shortcode_options_container arm_shortcode_options_popup_wrapper">
				<div class="arm_tinymce_content_block accordion_menu">
					<!-- *********************[arm_logout]********************* -->
					<div class="arm_tinymce_shortcode_content">
						<div class="arm_shortcode_content_header accordion_header" style="box-shadow:none;border-bottom: none;"><?php esc_html_e( 'ARMember Logout', 'armember-membership' ); ?></div>
						<div class="arm_shortcode_detail_wrapper">
							<div class="arm_shortcode_detail_container">
								<div class="arm_shortcode_description"><?php // esc_html_e('Logout Shortcode.', 'armember-membership'); ?></div>
								<div class="arm_shortcode_generator_form arm_generator_arm_logout">
									<form onsubmit="return false;">
										<table class="arm_shortcode_option_table">
											<tr>
												<th><?php esc_html_e( 'Link Type', 'armember-membership' ); ?></th>
												<td>
													<input type="hidden" id="arm_shortcode_logout_link_type" class="wpb_vc_param_value" name="type" value="link" />
													<dl class="arm_selectbox column_level_dd">
														<dt><span></span><input type="text" style="display:none;" value="" class="arm_autocomplete"/><i class="armfa armfa-caret-down armfa-lg"></i></dt>
														<dd>
															<ul data-id="arm_shortcode_logout_link_type">
																<li data-label="<?php esc_attr_e( 'Link', 'armember-membership' ); ?>" data-value="link"><?php esc_html_e( 'Link', 'armember-membership' ); ?></li>
																<li data-label="<?php esc_attr_e( 'Button', 'armember-membership' ); ?>" data-value="button"><?php esc_html_e( 'Button', 'armember-membership' ); ?></li>
															</ul>
														</dd>
													</dl>
												</td>
											</tr>
											<tr>
												<th>
													<span class="arm_shortcode_logout_link_opts"><?php esc_html_e( 'Link Text', 'armember-membership' ); ?></span>
													<span class="arm_shortcode_logout_button_opts arm_hidden"><?php esc_html_e( 'Button Text', 'armember-membership' ); ?></span>
												</th>
												<td><input type="text" name="label" class="wpb_vc_param_value" id="arm_logout_label" value="<?php esc_html_e( 'Logout', 'armember-membership' ); ?>"></td>
											</tr>
											<tr>
												<th><?php esc_html_e( 'Display User Info?', 'armember-membership' ); ?></th>
												<td>
													<input type="hidden" name="user_info" value="true" class="wpb_vc_param_value" id="arm_user_info_hidden" />
													<label>
														<input type="radio" name="arm_user_info" value="true" checked="checked" id="arm_user_info_true" onclick="arm_user_info_action();" class="arm_iradio" />
														<span><?php esc_html_e( 'Yes', 'armember-membership' ); ?></span>
													</label>
													<label>
														<input type="radio" name="arm_user_info" value="false"  id="arm_user_info_false" onclick="arm_user_info_action();" class="arm_iradio" />
														<span><?php esc_html_e( 'No', 'armember-membership' ); ?></span>
													</label>
												</td>
											</tr>
											<tr>
												<th><?php esc_html_e( 'Redirect After Logout', 'armember-membership' ); ?></th>
												<td>
													<input type="text" class="wpb_vc_param_value" name="redirect_to" value="<?php echo ARMLITE_HOME_URL; //phpcs:ignore ?>" id="arm_redirect_to" />
												</td>
											</tr>
											<tr>
												<th>
													<span class="arm_shortcode_logout_link_opts"><?php esc_html_e( 'Link CSS', 'armember-membership' ); ?></span>
													<span class="arm_shortcode_logout_button_opts arm_hidden"><?php esc_html_e( 'Button CSS', 'armember-membership' ); ?></span>
												</th>
												<td>
													<textarea class="arm_popup_textarea wpb_vc_param_value" id="arm_logout_link_css" name="link_css" rows="3"></textarea>
												</td>
											</tr>
											<tr>
												<th>
													<span class="arm_shortcode_logout_link_opts"><?php esc_html_e( 'Link Hover CSS', 'armember-membership' ); ?></span>
													<span class="arm_shortcode_logout_button_opts arm_hidden"><?php esc_html_e( 'Button Hover CSS', 'armember-membership' ); ?></span>
												</th>
												<td>
													<textarea class="arm_popup_textarea wpb_vc_param_value" id="arm_logout_link_hover_css" name="link_hover_css" rows="3"></textarea>
												</td>
											</tr>
										</table>
									</form>	
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}




	public function ARM_arm_setup() {
		global $arm_lite_version, $ARMemberLite;
		if ( function_exists( 'vc_map' ) ) {
			vc_map(
				array(
					'name'              => esc_html__( 'ARMember Membership Setup Wizard', 'armember-membership' ),
					'description'       => '',
					'base'              => 'arm_setup',
					'category'          => 'armember-membership',
					'class'             => '',
					'controls'          => 'full',
					'icon'              => 'arm_vc_icon',
					'admin_enqueue_css' => array( MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css' ),
					'front_enqueue_css' => MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css',
					'params'            => array(
						array(
							'type'        => 'ARM_arm_setup_shortcode',
							'heading'     => false,
							'param_name'  => 'id',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_setup_shortcode',
							'heading'     => false,
							'param_name'  => 'hide_title',
							'value'       => false,
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_setup_shortcode',
							'heading'     => false,
							'param_name'  => 'hide_plans',
							'value'       => 0,
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_setup_shortcode',
							'heading'     => false,
							'param_name'  => 'subscription_plan',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_setup_shortcode',
							'heading'     => false,
							'param_name'  => 'popup',
							'value'       => 'false',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_setup_shortcode',
							'heading'     => false,
							'param_name'  => 'link_type',
							'value'       => 'link',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_setup_shortcode',
							'heading'     => false,
							'param_name'  => 'link_title',
							'value'       => 'Click here to open Form test',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_setup_shortcode',
							'heading'     => false,
							'param_name'  => 'overlay',
							'value'       => '0.6',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_setup_shortcode',
							'heading'     => false,
							'param_name'  => 'modal_bgcolor',
							'value'       => '#000000',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_setup_shortcode',
							'heading'     => false,
							'param_name'  => 'popup_height',
							'value'       => 'auto',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_setup_shortcode',
							'heading'     => false,
							'param_name'  => 'popup_width',
							'value'       => '800',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_setup_shortcode',
							'heading'     => false,
							'param_name'  => 'link_css',
							'value'       => 'color:#000000;',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_setup_shortcode',
							'heading'     => false,
							'param_name'  => 'link_hover_css',
							'value'       => 'color:#ffffff;',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
					),
				)
			);
		}
	}

	public function ARM_arm_setup_html( $settings, $value ) {
		global $wpdb, $ARMemberLite, $arm_slugs, $arm_shortcodes, $arm_members_class, $arm_global_settings, $arm_email_settings,  $arm_member_forms, $arm_subscription_plans;

		$setups = $wpdb->get_results( 'SELECT `arm_setup_id`, `arm_setup_name` FROM `' . $ARMemberLite->tbl_arm_membership_setup . '` ' );//phpcs:ignore --Reason: $tbl_arm_membership_setup is a table name. No need to prepare Query without Where clause
		if ( $settings['param_name'] == 'id' ) {
			$value = ( ! empty( $value ) ? $value : ( ! empty( $setups[0] ) ? $setups[0]->arm_setup_id : '' ) );
		}

		echo '<input id="' . esc_attr( $settings['param_name'] ) . '" name="' . esc_attr( $settings['param_name'] ) . '" class=" ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_armfield" type="hidden" value="' . esc_attr( $value ) . '" />';

		if ( $this->is_membership_vdextend == 0 ) {
			$this->is_membership_vdextend = 1;
			?>
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm-font-awesome.css" />
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm_tinymce.css" />
			<div class="arm_tinymce_shortcode_options_container arm_shortcode_options_popup_wrapper">
				<div class="arm_tinymce_content_block accordion_menu">
					<!-- *********************[arm_setup]********************* -->
					<div class="arm_tinymce_shortcode_content">
						<div class="arm_shortcode_content_header accordion_header" style="box-shadow:none;border-bottom: none;"><?php esc_html_e( 'ARMember Membership Setup Wizard', 'armember-membership' ); ?></div>
						<div class="arm_shortcode_detail_wrapper">
							<div class="arm_shortcode_detail_container">
								<div class="arm_shortcode_description"><?php // esc_html_e('Membership Setup Wizard Shortcode.', 'armember-membership'); ?></div>
								<div class="arm_shortcode_generator_form arm_generator_arm_setup" style="width:660px;">
									<form onsubmit="return false;">
										<table class="arm_shortcode_option_table">
											<tr>
												<th><?php esc_html_e( 'Select Setup', 'armember-membership' ); ?></th>
												<td>
													<input type="hidden" id="arm_subscription_id_select" class="wpb_vc_param_value" name="id" value="" />
													<dl class="arm_selectbox column_level_dd">
														<dt><span></span><input type="text" style="display:none;" value="" class="arm_autocomplete"/><i class="armfa armfa-caret-down armfa-lg"></i></dt>
														<dd>
															<ul data-id="arm_subscription_id_select">
																<!--<li data-label="<?php esc_html_e( 'Select Setup', 'armember-membership' ); ?>" data-value=""><?php esc_html_e( 'Select Action Type', 'armember-membership' ); ?></li>-->
																<?php if ( ! empty( $setups ) ) : ?>
																	<?php foreach ( $setups as $ms ) : ?>
																		<li data-label="<?php echo stripslashes( esc_attr($ms->arm_setup_name) ); //phpcs:ignore ?>" data-value="<?php echo esc_attr($ms->arm_setup_id) //phpcs:ignore ?>"><?php echo stripslashes( $ms->arm_setup_name ); //phpcs:ignore ?></li>
																	<?php endforeach; ?>
																<?php endif; ?>
															</ul>
														</dd>
													</dl>
												</td>
											</tr>
											
											<tr>
												<th><?php esc_html_e( 'Hide Setup Title?', 'armember-membership' ); ?></th>
												<td>
													<input type="hidden" name="hide_title" value="false" id="arm_subscription_show_hide_title_hidden" class="wpb_vc_param_value" />
													<label>
														<input type="radio" name="arm_subscription_hide_title" value="true" id="arm_subscription_hide_title_true" onclick="arm_subscription_show_hide_title();" class="arm_iradio" />
														<span><?php esc_html_e( 'Yes', 'armember-membership' ); ?></span>
													</label>
													<label>
														<input type="radio" name="arm_subscription_hide_title" value="false" id="arm_subscription_hide_title_false" onclick="arm_subscription_show_hide_title();" class="arm_iradio" />
														<span><?php esc_html_e( 'No', 'armember-membership' ); ?></span>
													</label>
												</td>
											</tr>
											<tr>
									<th><?php esc_html_e( 'Default Selected Plan', 'armember-membership' ); ?></th>
									<td>
										<input type="text" name="subscription_plan" value="" id="subscription_plan_input" class="wpb_vc_param_value" >
										<div><em><?php esc_html_e( 'Please enter plan id', 'armember-membership' ); ?></em></div>
									</td>
								</tr>
											<tr>
									<th><?php esc_html_e( 'Hide Plan Selection Area', 'armember-membership' ); ?></th>
									<td>
										<input type="hidden" name="hide_plans" value="0"  class="wpb_vc_param_value hide_plans">
										<input type="checkbox" name="hide_plans_checkbox" onchange="arm_change_hide_plan_settigs()" class="wpb_vc_param_value hide_plans_checkbox">
										
										
									</td>
								</tr>
											
											<tr>
												<th><?php esc_html_e( 'How you want to include this form into page?', 'armember-membership' ); ?></th>
												<td>
													<input type="hidden" name="popup" value=""  id="arm_subscription_display_form_type_hidden" class="wpb_vc_param_value"/>
													<label>
														<input type="radio" name="arm_subscription_display_type" id="arm_subscription_display_type_internal" class="arm_iradio" value="false" onclick="arm_subscription_setup_display_type();" />
														<span><?php esc_html_e( 'Internal', 'armember-membership' ); ?></span>
													</label>
													<label>
														<input type="radio" name="arm_subscription_display_type" id="arm_subscription_display_type_external" class="arm_iradio" value="true" onclick="arm_subscription_setup_display_type();" />
														<span><?php esc_html_e( 'External', 'armember-membership' ); ?></span>
													</label>
													
													<div class="form_popup_options">
														<div class="form_popup_options_row">
															<span class="arm_opt_title"><?php esc_html_e( 'Link Type', 'armember-membership' ); ?></span>
															<input type="hidden" id="arm_subscription_link_type" class="wpb_vc_param_value" name="link_type" value="link" />
															<dl class="arm_selectbox column_level_dd">
																<dt><span><?php esc_html_e( 'Link', 'armember-membership' ); ?></span><input type="text" style="display:none;" value="" class="arm_autocomplete"/><i class="armfa armfa-caret-down armfa-lg"></i></dt>
																<dd>
																	<ul data-id="arm_subscription_link_type">
																		<li data-label="<?php echo esc_attr_e( 'Link', 'armember-membership' ); ?>" data-value="link"><?php echo esc_html_e( 'Link', 'armember-membership' ); ?></li>
																		<li data-label="<?php echo esc_attr_e( 'Button', 'armember-membership' ); ?>" data-value="button"><?php echo esc_html_e( 'Button', 'armember-membership' ); ?></li>
																	</ul>
																</dd>
															</dl>
														</div>
														<div class="form_popup_options_row">
															<span class="arm_opt_title arm_shortcode_setup_link_opts"><?php esc_html_e( 'Link Text', 'armember-membership' ); ?></span>
															<span class="arm_opt_title arm_hidden arm_shortcode_setup_button_opts"><?php esc_html_e( 'Button Text', 'armember-membership' ); ?></span>
															<input type="text" class="wpb_vc_param_value" name="link_title" value="<?php esc_html_e( 'Click Here to Open form', 'armember-membership' ); ?>" id="arm_setup_link_text_id" />                                                        
														</div>
														<div class="form_popup_options_row arm_setup_background_overlay">
															<span class="arm_opt_title"><?php esc_html_e( 'Background Overlay', 'armember-membership' ); ?></span>
															<div>
																<input type="hidden" id="arm_overlay_select" name="overlay" value="0.6" class="wpb_vc_param_value" />
																<dl class="arm_selectbox column_level_dd">
																	<dt style="width:80px;"><span></span><input type="text" style="display:none;" value="" class="arm_autocomplete" /><i class=" armfa armfa-caret-down armfa-lg"></i></dt>
																	<dd>
																		<ul data-id="arm_overlay_select">
																			<li data-label="0 (<?php esc_attr_e( 'None', 'armember-membership' ); ?>)" data-value="0">0 (<?php esc_html_e( 'None', 'armember-membership' ); ?>)</li>
																			<?php for ( $i = 1; $i < 11; $i++ ) : ?>

																				<li data-label="<?php echo intval($i) * 10; ?>" data-value="<?php echo intval($i) / 10; ?>"><?php echo intval($i) * 10; ?></li>
																			<?php endfor; ?>
																		</ul>
																	</dd>
																</dl>
															</div>
															<div>
																<input id="arm_vc_setup_modal_bgcolor" type="text" name="modal_bgcolor" class="arm_colorpicker modal_bgcolor wpb_vc_param_value" value="#000000" /><em>&nbsp;&nbsp;(<?php esc_html_e( 'Background Color', 'armember-membership' ); ?>)</em>
															</div>
														</div>
														<div class="armclear"></div>
														<div class="form_popup_options_row arm_setup_popup_size">
															<span class="arm_opt_title"><?php esc_html_e( 'Size', 'armember-membership' ); ?>: </span>
															<div><input class="wpb_vc_param_value" type="text" name="popup_height" id="arm_setup_popup_height" value="" /><br/><?php esc_html_e( 'Height', 'armember-membership' ); ?></div>
															<span class="popup_height_suffinx">px</span>
															<div><input class="wpb_vc_param_value" type="text" name="popup_width" id="arm_setup_popup_width" value="" /><br/><?php esc_html_e( 'Width', 'armember-membership' ); ?></div>
															<span class="popup_width_suffinx">px</span>
														</div>
														<div class="form_popup_options_row">
															<span class="arm_opt_title arm_shortcode_setup_link_opts" style="vertical-align: top;"><?php esc_html_e( 'Link CSS', 'armember-membership' ); ?>: </span>
															<span class="arm_opt_title arm_shortcode_setup_button_opts arm_hidden" style="vertical-align: top;"><?php esc_html_e( 'Button CSS', 'armember-membership' ); ?>: </span>
															<textarea class="arm_popup_textarea wpb_vc_param_value" name="link_css" id="arm_link_css" rows="3"></textarea>
														</div>
														<div class="form_popup_options_row">
															<span class="arm_opt_title arm_shortcode_setup_link_opts" style="vertical-align: top;"><?php esc_html_e( 'Link Hover CSS', 'armember-membership' ); ?>: </span>
															<span class="arm_opt_title arm_shortcode_setup_button_opts arm_hidden" style="vertical-align: top;"><?php esc_html_e( 'Button Hover CSS', 'armember-membership' ); ?>: </span>
															<textarea class="arm_popup_textarea wpb_vc_param_value" name="link_hover_css" id="arm_link_hover_css" rows="3"></textarea>
														</div>
													</div>
												</td>
											</tr>
										</table>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}

	public function ARM_arm_member_transaction() {
		global $arm_lite_version, $ARMemberLite;
		if ( function_exists( 'vc_map' ) ) {
			vc_map(
				array(
					'name'              => esc_html__( 'ARMember Payment Transaction', 'armember-membership' ),
					'description'       => '',
					'base'              => 'arm_member_transaction',
					'category'          => 'armember-membership',
					'class'             => '',
					'controls'          => 'full',
					'icon'              => 'arm_vc_icon',
					'admin_enqueue_css' => array( MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css' ),
					'front_enqueue_css' => MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css',
					'params'            => array(
						array(
							'type'        => 'ARM_arm_member_transaction_shortcode',
							'heading'     => false,
							'param_name'  => 'label',
							'value'       => 'transaction_id,plan,payment_gateway,payment_type,transaction_status,amount,payment_date,',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_member_transaction_shortcode',
							'heading'     => false,
							'param_name'  => 'value',
							'value'       => 'Transaction ID,Plan,Payment Gateway,Payment Type,Transaction Status,Amount,Payment Date,',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_member_transaction_shortcode',
							'heading'     => false,
							'param_name'  => 'title',
							'value'       => esc_html__( 'Transactions', 'armember-membership' ),
							'description' => '&nbsp;',
							'admin_label' => true,
						),

						array(
							'type'        => 'ARM_arm_member_transaction_shortcode',
							'heading'     => false,
							'param_name'  => 'per_page',
							'value'       => '5',
							'description' => '&nbsp',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_member_transaction_shortcode',
							'heading'     => false,
							'param_name'  => 'message_no_record',
							'value'       => esc_html__( 'There is no any Transactions found', 'armember-membership' ),
							'description' => '&nbsp;',
							'admin_label' => true,
						),
					),
				)
			);
		}
	}

	public function ARM_arm_member_transaction_html( $settings, $value ) {
		global $wpdb, $ARMemberLite, $arm_slugs, $arm_shortcodes, $arm_members_class, $arm_global_settings, $arm_email_settings,  $arm_member_forms, $arm_subscription_plans;

		echo '<input id="' . esc_attr( $settings['param_name'] ) . '" name="' . esc_attr( $settings['param_name'] ) . '" class=" ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_armfield" type="hidden" value="' . esc_attr( $value ) . '" />';

		if ( $this->is_membership_vdextend == 0 ) {
			$this->is_membership_vdextend = 1;
			?>
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm-font-awesome.css" />
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm_tinymce.css" />
			<script type="text/javascript">
				__TRANSACTION_FIELD_VALUES = "Transaction ID,Plan,Payment Gateway,Payment Type,Transaction Status,Amount,Payment Date";
			</script>
			<div class="arm_tinymce_shortcode_options_container arm_shortcode_options_popup_wrapper">
				<div class="arm_tinymce_content_block accordion_menu">
					<!-- *********************[arm_member_transaction]********************* -->
					<div class="arm_tinymce_shortcode_content">
						<div class="arm_shortcode_content_header accordion_header" style="box-shadow:none;border-bottom: none;"><?php esc_html_e( 'ARMember Transaction', 'armember-membership' ); ?></div>
						<div class="arm_shortcode_detail_wrapper">
							<div class="arm_shortcode_detail_container">
								<div class="arm_shortcode_description"><?php // esc_html_e('Display user\'s activities', 'armember-membership'); ?></div>
								<div class="arm_shortcode_generator_form arm_generator_arm_member_transaction">
									<form onsubmit="return false;">
										<table class="arm_shortcode_option_table">
											<tr>
												<th><?php esc_html_e( 'Transaction History', 'armember-membership' ); ?></th>
												<td>
													<input type='hidden' name='label' class='wpb_vc_param_value' id='arm_transaction_label_hidden' value='' />
													<input type='hidden' name='value' class='wpb_vc_param_value' id='arm_transaction_value_hidden' value='' />
													<ul class="arm_member_transaction_fields">
														<li class="arm_member_transaction_field_list">
															<label class="arm_member_transaction_field_item">
																<input type="checkbox" class="arm_icheckbox arm_member_transaction_fields arm_member_transaction_field_input" name="arm_transaction_fields[]" value="transaction_id" checked="checked" onchange="arm_select_transaction_fields()" />
															</label>
															<input type="text" class="arm_member_transaction_fields" onkeyup="arm_select_transaction_fields()" name="value[]" value="<?php esc_html_e( 'Transaction ID', 'armember-membership' ); ?>" />
														</li>
														
														<li class="arm_member_transaction_field_list">
															<label class="arm_member_transaction_field_item">
																<input type="checkbox" class="arm_icheckbox arm_member_transaction_fields arm_member_transaction_field_input" name="arm_transaction_fields[]" value="plan" checked="checked" onchange="arm_select_transaction_fields()" />
															</label>
															<input type="text" class="arm_member_transaction_fields" onkeyup="arm_select_transaction_fields()" name="value[]" value="<?php esc_html_e( 'Plan', 'armember-membership' ); ?>" />
														</li>
														<li class="arm_member_transaction_field_list">
															<label class="arm_member_transaction_field_item">
																<input type="checkbox" class="arm_icheckbox arm_member_transaction_fields arm_member_transaction_field_input" name="arm_transaction_fields[]" value="payment_gateway" checked="checked" onchange="arm_select_transaction_fields()" />
															</label>
															<input type="text" class="arm_member_transaction_fields" onkeyup="arm_select_transaction_fields()" name="value[]" value="<?php esc_html_e( 'Payment Gateway', 'armember-membership' ); ?>" />
														</li>
														<li class="arm_member_transaction_field_list">
															<label class="arm_member_transaction_field_item">
																<input type="checkbox" class="arm_icheckbox arm_member_transaction_fields arm_member_transaction_field_input" name="arm_transaction_fields[]" value="payment_type" checked="checked" onchange="arm_select_transaction_fields()" />
															</label>
															<input type="text" class="arm_member_transaction_fields" onkeyup="arm_select_transaction_fields()" name="value[]" value="<?php esc_html_e( 'Payment Type', 'armember-membership' ); ?>" />
														</li>
														<li class="arm_member_transaction_field_list">
															<label class="arm_member_transaction_field_item">
																<input type="checkbox" class="arm_icheckbox arm_member_transaction_fields arm_member_transaction_field_input" name="arm_transaction_fields[]" value="transaction_status" checked="checked" onchange="arm_select_transaction_fields()" />
															</label>
															<input type="text" class="arm_member_transaction_fields" onkeyup="arm_select_transaction_fields()" name="value[]" value="<?php esc_html_e( 'Transaction Status', 'armember-membership' ); ?>" />
														</li>
														<li class="arm_member_transaction_field_list">
															<label class="arm_member_transaction_field_item">
																<input type="checkbox" class="arm_icheckbox arm_member_transaction_fields arm_member_transaction_field_input" name="arm_transaction_fields[]" value="amount" checked="checked" onchange="arm_select_transaction_fields()" />
															</label>
															<input type="text" class="arm_member_transaction_fields" onkeyup="arm_select_transaction_fields()" name="value[]" value="<?php esc_html_e( 'Amount', 'armember-membership' ); ?>" />
														</li>
														
														<li class="arm_member_transaction_field_list">
															<label class="arm_member_transaction_field_item">
																<input type="checkbox" class="arm_icheckbox arm_member_transaction_fields arm_member_transaction_field_input" name="arm_transaction_fields[]" value="payment_date" checked="checked" onchange="arm_select_transaction_fields()" />
															</label>
															<input type="text" class="arm_member_transaction_fields" onkeyup="arm_select_transaction_fields()" name="value[]" value="<?php esc_html_e( 'Payment Date', 'armember-membership' ); ?>" />
														</li>
													</ul>
												</td>
											</tr>
											<tr>
												<th><?php esc_html_e( 'Title', 'armember-membership' ); ?></th>
												<td>
													<input type="text" name="title"  id="arm_transaction_title"  value="<?php esc_html_e( 'Transactions', 'armember-membership' ); ?>" class="wpb_vc_param_value"/>
												</td>
											</tr>
											<tr>
												<th><?php esc_html_e( 'Records per Page', 'armember-membership' ); ?></th>
												<td>
													<input type="text" name="per_page" id="arm_transaction_per_page_record" value="" class="wpb_vc_param_value" />
												</td>
											</tr>
											<tr>
												<th><?php esc_html_e( 'No Records Message', 'armember-membership' ); ?></th>
												<td>
													<input type="text" name="message_no_record"  id="arm_transaction_message_no_record" value="<?php esc_html_e( 'There is no any Transactions found', 'armember-membership' ); ?>" class="wpb_vc_param_value"/>
												</td>
											</tr>
										</table>
									</form>	
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}

	public function ARM_arm_account_detail() {
		global $arm_lite_version, $ARMemberLite;
		if ( function_exists( 'vc_map' ) ) {
			vc_map(
				array(
					'name'              => esc_html__( 'ARMember My Profile', 'armember-membership' ),
					'description'       => '',
					'base'              => 'arm_account_detail',
					'category'          => 'armember-membership',
					'class'             => '',
					'controls'          => 'full',
					'icon'              => 'arm_vc_icon',
					'admin_enqueue_css' => array( MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css' ),
					'front_enqueue_css' => MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css',
					'params'            => array(

						array(
							'type'        => 'ARM_arm_account_detail_shortcode',
							'heading'     => false,
							'param_name'  => 'label',
							'value'       => 'first_name,last_name,display_name,user_login,user_email',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_account_detail_shortcode',
							'heading'     => false,
							'param_name'  => 'value',
							'value'       => 'First Name,Last Name,Display Name,Username,Email Address,',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_account_detail_shortcode',
							'heading'     => false,
							'param_name'  => 'social_fields',
							'value'       => '',
							'descripiton' => '&nbsp;',
							'admin_label' => true,
						),
					),
				)
			);
		}
	}

	public function ARM_arm_account_detail_html( $settings, $value ) {
		global $wpdb, $ARMemberLite, $arm_slugs, $arm_shortcodes, $arm_members_class, $arm_global_settings, $arm_email_settings,  $arm_member_forms, $arm_subscription_plans,$arm_members_directory,$arm_social_feature;

		echo '<input id="' . esc_attr( $settings['param_name'] ) . '" name="' . esc_attr( $settings['param_name'] ) . '" class="' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_armfield" type="hidden" value="' . esc_attr( $value ) . '" />';

		if ( $this->is_membership_vdextend == 0 ) {
			$this->is_membership_vdextend = 1;
			?>
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm-font-awesome.css" />
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm_tinymce.css" />
			<div class="arm_tinymce_shortcode_options_container arm_shortcode_options_popup_wrapper">
				<div class="arm_tinymce_content_block accordion_menu">
					<!-- *********************[arm_account_detail]********************* -->
					<div class="arm_tinymce_shortcode_content">
						<div class="arm_shortcode_content_header accordion_header" style="box-shadow:none;border-bottom: none;"><?php esc_html_e( 'ARMember Account Detail', 'armember-membership' ); ?></div>
						<div class="arm_shortcode_detail_wrapper">
							<div class="arm_shortcode_detail_container">
								<div class="arm_shortcode_description"><?php // esc_html_e('Display user\'s account details', 'armember-membership'); ?></div>
								<div class="arm_shortcode_generator_form arm_generator_arm_account_detail" style="width: 785px;">
									<form onsubmit="return false;">
										<table class="arm_shortcode_option_table">
											<tr>
												<th><?php esc_html_e( 'Profile Fields', 'armember-membership' ); ?></th>
												<td class="arm_view_profile_wrapper">
													<div class="arm_social_profile_fields_selection_wrapper">
													<input type="hidden" name="label" class="wpb_vc_param_value" id="arm_profile_label_hidden" value="" />
													<input type="hidden" name="value" class="wpb_vc_param_value" id="arm_profile_value_hidden" value="" />
													<?php
													$dbProfileFields = $arm_members_directory->arm_template_profile_fields();
													if ( ! empty( $dbProfileFields ) ) {
														?>
														<?php $i = 1; foreach ( $dbProfileFields as $fieldMetaKey => $fieldOpt ) { ?>
															<?php
															if ( empty( $fieldMetaKey ) || $fieldMetaKey == 'user_pass' || in_array( $fieldOpt['type'], array( 'hidden', 'html', 'section', 'rememberme' ) ) ) {
																continue;
															}
															$fchecked = '';
															if ( in_array( $fieldMetaKey, array( 'user_email', 'user_login', 'first_name', 'last_name' ) ) ) {
																$fchecked = 'checked="checked"';
															}
															?>
														<label class="account_detail_radio arm_account_detail_options arm_acount_field_details_option">
															<input type="checkbox" id="arm_account_detail_<?php echo esc_attr($fieldMetaKey); ?>" name="fields[]" value="<?php echo esc_attr($fieldMetaKey); ?>" <?php echo $fchecked; //phpcs:ignore ?> onchange="arm_account_detail_tab_func();" class="arm_account_detail_fields arm_account_chk_fields arm_icheckbox" />
															<input type="text" class="arm_account_detail_fields arm_account_detail_input" onkeyup="arm_account_detail_tab_func()" name="value[]" value="<?php echo esc_attr($fieldOpt['label']); ?>" />
														</label>
															<?php
															$i++; }
													}
													?>
													</div>
												</td>
											</tr>
											<?php if ( $arm_social_feature->isSocialFeature ) : ?>
											<tr>
												<th><?php esc_html_e( 'Social Profile Fields', 'armember-membership' ); ?></th>
												<td class='arm_view_profile_wrapper'>
													<input type='hidden' name='social_fields' id='profile_social_fields_hidden' class='wpb_vc_param_value' value='' />
													<div class="arm_social_profile_fields_selection_wrapper">
														<?php
														$socialProfileFields = $arm_member_forms->arm_social_profile_field_types();
														if ( ! empty( $socialProfileFields ) ) {
															foreach ( $socialProfileFields as $spfKey => $spfLabel ) {
																?>
																<label class="account_detail_radio arm_account_detail_options">
																	<input type="checkbox" class="arm_icheckbox arm_spf_profile_fields" value="<?php echo esc_attr($spfKey); ?>" name="social_fields[]" id="arm_spf_<?php echo esc_attr($spfKey); ?>_status" onchange="arm_select_profile_social_fields()" >
																	<span><?php echo esc_attr($spfLabel); ?></span>
																</label>
																<?php
															}
														}
														?>
													</div>
												</td>
											</tr>
											<?php endif; ?>
										</table>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}

	public function ARM_arm_close_account() {
		global $arm_lite_version, $ARMemberLite;
		if ( function_exists( 'vc_map' ) ) {
			vc_map(
				array(
					'name'              => esc_html__( 'ARMember Close Account', 'armember-membership' ),
					'description'       => '',
					'base'              => 'arm_close_account',
					'category'          => 'armember-membership',
					'class'             => '',
					'controls'          => 'full',
					'icon'              => 'arm_vc_icon',
					'admin_enqueue_css' => array( MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css' ),
					'front_enqueue_css' => MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css',
					'params'            => array(
						array(
							'type'        => 'ARM_arm_close_account_shortcode',
							'heading'     => false,
							'param_name'  => 'set_id',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_close_account_shortcode',
							'heading'     => false,
							'param_name'  => 'css',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
					),
				)
			);
		}
	}

	public function ARM_arm_close_account_html( $settings, $value ) {
		global $wpdb, $ARMemberLite, $arm_slugs, $arm_shortcodes, $arm_members_class, $arm_global_settings, $arm_email_settings,  $arm_member_forms, $arm_subscription_plans;

		echo '<input id="' . esc_attr( $settings['param_name'] ) . '" name="' . esc_attr( $settings['param_name'] ) . '" class=" ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_armfield" type="hidden" value="' . esc_attr( $value ) . '" />';

		if ( $this->is_membership_vdextend == 0 ) {
			$this->is_membership_vdextend = 1;
			?>
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm-font-awesome.css" />
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm_tinymce.css" />

			<div class="arm_tinymce_shortcode_options_container arm_shortcode_options_popup_wrapper">
				<div class="arm_tinymce_content_block accordion_menu">
					<!-- *********************[arm_close_account]********************* -->
					<div class="arm_tinymce_shortcode_content">
						<div class="arm_shortcode_content_header accordion_header" style="box-shadow:none;border-bottom: none;"><?php esc_html_e( 'ARMember Close Account', 'armember-membership' ); ?></div>
						<div class="arm_shortcode_detail_wrapper">
							<div class="arm_shortcode_detail_container">
								<!--<div class="arm_shortcode_description">< ?php esc_html_e('Close Account Shortcode', 'armember-membership'); ?></div> -->
								<div class="arm_shortcode_generator_form arm_generator_arm_close_account">
									<form onsubmit="return false;">
										<?php
										$setnames = $wpdb->get_results( $wpdb->prepare('SELECT * FROM `' . $ARMemberLite->tbl_arm_forms . "` WHERE `arm_form_type` = %s GROUP BY arm_set_id ORDER BY arm_form_id ASC",'login') );//phpcs:ignore --Reason: $tbl_arm_forms is a table name. False Positive Alarm
										?>
										<table class="arm_shortcode_option_table">
											<tr>
												<th> <?php esc_html_e( 'Select set of login form', 'armember-membership' ); ?> </th>
												<td>
													<input type="hidden" name="set_id" class="wpb_vc_param_value" id="arm_set_id" onchange="arm_show_hide_css_textarea(this.value)" />
													<dl class="arm_selectbox column_level_dd arm_set_id_dd">
														<dt><span></span><input type="text" style="display:none;" value="" class="arm_autocomplete"/><i class="armfa armfa-caret-down armfa-lg"></i></dt>
														<dd>
															<ul class="arm_set_id" data-id="arm_set_id">
																<li data-label="<?php esc_html_e( 'Select Form', 'armember-membership' ); ?>" data-value=""><?php esc_html_e( 'Select Form', 'armember-membership' ); ?></li>
																<?php if ( ! empty( $setnames ) ) : ?>
																	<?php foreach ( $setnames as $sn ) : ?>
																		<li data-label="<?php echo stripslashes( esc_attr($sn->arm_set_name) ); //phpcs:ignore ?>" data-value="<?php echo esc_attr($sn->arm_form_id); //phpcs:ignore ?>"><?php echo stripslashes( esc_attr($sn->arm_set_name) ); //phpcs:ignore ?></li>
																	<?php endforeach; ?>
																<?php endif; ?>
															</ul>
														</dd>
													</dl>
												</td>
											</tr>
										  
										</table>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}


	public function ARM_arm_membership() {
		global $arm_lite_version, $ARMemberLite;
		if ( function_exists( 'vc_map' ) ) {
			vc_map(
				array(
					'name'              => esc_html__( 'ARMember Current Membership', 'armember-membership' ),
					'description'       => '',
					'base'              => 'arm_membership',
					'category'          => 'armember-membership',
					'class'             => '',
					'controls'          => 'full',
					'icon'              => 'arm_vc_icon',
					'admin_enqueue_css' => array( MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css' ),
					'front_enqueue_css' => MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css',
					'params'            => array(
						array(
							'type'        => 'ARM_arm_membership_shortcode',
							'heading'     => false,
							'param_name'  => 'title',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_membership_shortcode',
							'heading'     => false,
							'param_name'  => 'setup_id',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_membership_shortcode',
							'heading'     => false,
							'param_name'  => 'display_renew_button',
							'value'       => 'false',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_membership_shortcode',
							'heading'     => false,
							'param_name'  => 'renew_text',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_membership_shortcode',
							'heading'     => false,
							'param_name'  => 'make_payment_text',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_membership_shortcode',
							'heading'     => false,
							'param_name'  => 'renew_css',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_membership_shortcode',
							'heading'     => false,
							'param_name'  => 'renew_hover_css',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_membership_shortcode',
							'heading'     => false,
							'param_name'  => 'display_cancel_button',
							'value'       => 'false',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_membership_shortcode',
							'heading'     => false,
							'param_name'  => 'cancel_text',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_membership_shortcode',
							'heading'     => false,
							'param_name'  => 'cancel_css',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_membership_shortcode',
							'heading'     => false,
							'param_name'  => 'cancel_hover_css',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_membership_shortcode',
							'heading'     => false,
							'param_name'  => 'display_update_card_button',
							'value'       => 'false',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_membership_shortcode',
							'heading'     => false,
							'param_name'  => 'update_card_text',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_membership_shortcode',
							'heading'     => false,
							'param_name'  => 'update_card_css',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_membership_shortcode',
							'heading'     => false,
							'param_name'  => 'update_card_hover_css',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_membership_shortcode',
							'heading'     => false,
							'param_name'  => 'trial_active',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_membership_shortcode',
							'heading'     => false,
							'param_name'  => 'message_no_record',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_membership_shortcode',
							'heading'     => false,
							'param_name'  => 'cancel_message',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_membership_shortcode',
							'heading'     => false,
							'param_name'  => 'membership_label',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_membership_shortcode',
							'heading'     => false,
							'param_name'  => 'membership_value',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
					),
				)
			);
		}
	}

	public function ARM_arm_membership_html( $settings, $value ) {
		global $wpdb, $ARMemberLite;
		echo '<input id="' . esc_attr( $settings['param_name'] ) . '" name="' . esc_attr( $settings['param_name'] ) . '" class=" ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_armfield" type="hidden" value="' . esc_attr( $value ) . '" />';
		if ( $this->is_membership_vdextend == 0 ) {
			$this->is_membership_vdextend = 1;
			?>
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm-font-awesome.css" />
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm_tinymce.css" />
			<div class="arm_tinymce_shortcode_options_container arm_shortcode_options_popup_wrapper">
				<div class="arm_tinymce_content_block accordion_menu">
					<!-- *********************[arm_current_membership]********************* -->
					<div class="arm_tinymce_shortcode_content">
						<div class="arm_shortcode_content_header accordion_header" style="box-shadow:none;border-bottom: none;"><?php // esc_html_e('ARMember Cancel Membership', 'armember-membership'); ?><?php esc_html_e( 'ARMember Current Membership', 'armember-membership' ); ?></div>
						<div class="arm_shortcode_detail_wrapper">
							<div class="arm_shortcode_detail_container">
								<div class="arm_shortcode_description"></div>
								<div class="arm_shortcode_generator_form arm_generator_arm_membership" style="width:660px">
									<form onsubmit="return false;">
										<table class="arm_shortcode_option_table">
										<tr>
											<th><?php esc_html_e( 'Title', 'armember-membership' ); ?></th>
											<td>
												<input type="text" class="wpb_vc_param_value" id='current_membership_label' name="title" value="<?php esc_attr_e( 'Current Membership', 'armember-membership' ); ?>" />
											</td>
										</tr>
										<tr>
											<th><?php esc_html_e( 'Select Setup', 'armember-membership' ); ?></th>
											<td>
												<?php
												$setups       = $wpdb->get_results( 'SELECT `arm_setup_id`, `arm_setup_name` FROM `' . $ARMemberLite->tbl_arm_membership_setup . '` ' );//phpcs:ignore --Reason: $tbl_arm_membership_setup is a table name. No need to prepare query without Where clause. False Positive Alarm
												$armsteuplist = '';
												if ( ! empty( $setups ) ) {
													foreach ( $setups as $ms ) {
														$armsteuplist .= '<li class="arm_shortcode_form_id_li" data-label="' . stripslashes( esc_attr($ms->arm_setup_name) ) . '" data-value="' . esc_attr($ms->arm_setup_id) . '">' . stripslashes( esc_attr($ms->arm_setup_name) ) . '</li>';
													}
												}
												?>
												<input type="hidden" id="arm_form_select" class="wpb_vc_param_value" name="setup_id" value=""/>
												<dl class="arm_selectbox column_level_dd" id="arm_form_select_dropdown">
													<dt><span></span><input type="text" style="display:none;" value="" class="arm_autocomplete"/><i class="armfa armfa-caret-down armfa-lg"></i></dt>
													<dd>
														<ul class="arm_form_select" data-id="arm_form_select">
															<li data-label="<?php esc_attr_e( 'Select Form', 'armember-membership' ); ?>" data-value=""><?php esc_html_e( 'Select Form', 'armember-membership' ); ?></li>
															<?php echo $armsteuplist; //phpcs:ignore ?>
														</ul>
													</dd>
												</dl>
											</td>
										</tr>
										<tr>
											<th><?php esc_html_e( 'Current Membership', 'armember-membership' ); ?></th>
											<td>
												<input type="hidden" class="wpb_vc_param_value" name='membership_label' id="arm_current_membership_fields_label" value="current_membership_no,current_membership_is,current_membership_recurring_profile,current_membership_started_on,current_membership_expired_on,current_membership_next_billing_date,action_button" />
												<input type="hidden" class="wpb_vc_param_value" name='membership_value' id="arm_current_membership_fields_value" value="No.,Membership Plan,Plan Type,Starts On,Expires On,Cycle Date,Action," />
												<ul class="arm_member_current_membership_fields">
														<li class="arm_member_current_membership_field_list">
																<label class="arm_member_current_membership_field_item">
																		<input type="checkbox" class="arm_icheckbox arm_current_membership_field_input arm_member_current_membership_fields" name="arm_current_membership_fields[]" id="current_membership_no" value="current_membership_no" checked="checked" onchange="arm_select_current_membership_fields()" />
																</label>
																<input type="text" id="current_membership_no_text" class="arm_member_current_membership_fields arm_text_input" name="arm_current_membership_field_label_current_membership_no" value="<?php esc_attr_e( 'No.', 'armember-membership' ); ?>" onkeyup="arm_select_current_membership_fields()" />
														</li>
														<li class="arm_member_current_membership_field_list">
																<label class="arm_member_current_membership_field_item">
																		<input type="checkbox" class="arm_icheckbox arm_current_membership_field_input arm_member_current_membership_fields" name="arm_current_membership_fields[]" id="current_membership_is" value="current_membership_is" checked="checked" onchange="arm_select_current_membership_fields()" />
																</label>
																<input type="text" class="arm_member_current_membership_fields arm_text_input" id="current_membership_is_text" name="arm_current_membership_field_label_current_membership_is" value="<?php esc_attr_e( 'Membership Plan', 'armember-membership' ); ?>" onkeyup="arm_select_current_membership_fields()" />
														</li>
														<li class="arm_member_current_membership_field_list">
																<label class="arm_member_current_membership_field_item">
																		<input type="checkbox" class="arm_icheckbox arm_current_membership_field_input arm_member_current_membership_fields" name="arm_current_membership_fields[]" id="current_membership_recurring_profile" value="current_membership_recurring_profile" checked="checked" onchange="arm_select_current_membership_fields()" />
																</label>
																<input type="text" class="arm_member_current_membership_fields arm_text_input" id="current_membership_recurring_profile_text" name="arm_current_membership_field_label_current_membership_recurring_profile" value="<?php esc_attr_e( 'Plan Type', 'armember-membership' ); ?>" onkeyup="arm_select_current_membership_fields()" />
														</li>
														<li class="arm_member_current_membership_field_list">
																<label class="arm_member_current_membership_field_item">
																		<input type="checkbox" class="arm_icheckbox arm_current_membership_field_input arm_member_current_membership_fields" name="arm_current_membership_fields[]" id="current_membership_started_on" value="current_membership_started_on" checked="checked" onchange="arm_select_current_membership_fields()" />
																</label>
																<input type="text" id="current_membership_started_on_text" class="arm_member_current_membership_fields arm_text_input" name="arm_current_membership_field_label_current_membership_started_on" value="<?php esc_attr_e( 'Starts On', 'armember-membership' ); ?>" onkeyup="arm_select_current_membership_fields()" />
														</li>
														<li class="arm_member_current_membership_field_list">
																<label class="arm_member_current_membership_field_item">
																		<input type="checkbox" class="arm_icheckbox arm_current_membership_field_input arm_member_current_membership_fields" name="arm_current_membership_fields[]" id="current_membership_expired_on" value="current_membership_expired_on" checked="checked" onchange="arm_select_current_membership_fields()" />
																</label>
																<input type="text" class="arm_member_current_membership_fields arm_text_input" id="current_membership_expired_on_text" name="arm_current_membership_field_label_current_membership_expired_on" value="<?php esc_attr_e( 'Expires On', 'armember-membership' ); ?>" onkeyup="arm_select_current_membership_fields()" />
														</li>
														<li class="arm_member_current_membership_field_list">
																<label class="arm_member_current_membership_field_item">
																		<input type="checkbox" class="arm_icheckbox arm_current_membership_field_input arm_member_current_membership_fields" name="arm_current_membership_fields[]" id="current_membership_next_billing_date" value="current_membership_next_billing_date" checked="checked" onchange="arm_select_current_membership_fields()" />
																</label>
																<input type="text" class="arm_member_current_membership_fields arm_text_input" id="current_membership_next_billing_date_text" name="arm_current_membership_field_label_current_membership_next_billing_date" value="<?php esc_attr_e( 'Cycle Date', 'armember-membership' ); ?>" onkeyup="arm_select_current_membership_fields()" />
														</li>
														
														<li class="arm_member_current_membership_field_list">
																<label class="arm_member_current_membership_field_item">
																		<input type="checkbox" class="arm_icheckbox arm_current_membership_field_input arm_member_current_membership_fields" name="arm_current_membership_fields[]" id="action_button" value="action_button" checked="checked" onchange="arm_select_current_membership_fields()" />
																</label>
																<input type="text" class="arm_member_current_membership_fields arm_text_input" id="action_button_text" name="arm_current_membership_field_label_action_button" value="<?php esc_attr_e( 'Action', 'armember-membership' ); ?>" onkeyup="arm_select_current_membership_fields()" />
														</li>
														
												</ul>
											</td>
										</tr>
										<tr>
											<th><?php esc_html_e( 'Display Renew Subscription Button', 'armember-membership' ); ?></th>
											<td>
												<input type="hidden" name="display_renew_button" value="false" class="wpb_vc_param_value" id="arm_show_renew_subscription_hidden" />
												<label class="form_show_renew_subscription_type_radio">
												<input type="radio" name="arm_show_renew_subscription_input" value="false" checked="checked" id="arm_show_renew_subscription_hidden_false" onclick="arm_show_renew_subscription();" class="arm_iradio" />
													<?php esc_html_e( 'No', 'armember-membership' ); ?>
												</label>
												<label class="form_show_renew_subscription_type_radio">
													<input type="radio" name="arm_show_renew_subscription_input" value="true" id="arm_show_renew_subscription_true" onclick="arm_show_renew_subscription();" class="arm_iradio" />
													<?php esc_html_e( 'Yes', 'armember-membership' ); ?>
												</label>
											</td>
										</tr>
										<tr class="form_popup_options" id="show_renew_subscription_section">
											<th><?php esc_html_e( 'Button Text', 'armember-membership' ); ?></th>
											<td>
												<input type="text" class="wpb_vc_param_value" name="renew_text" value="<?php esc_attr_e( 'Renew', 'armember-membership' ); ?>" id="arm_renew_membership_text" />
											</td>
										</tr>
										<tr class="form_popup_options" id="show_renew_subscription_section">
											<th><?php esc_html_e( 'Make Payment Text', 'armember-membership' ); ?></th>
											<td>
												<input type="text" class="wpb_vc_param_value" name="make_payment_text" value="<?php esc_attr_e( 'Make Payment', 'armember-membership' ); ?>" id="arm_make_payment_membership_text" />
											</td>
										</tr>
										<tr class="form_popup_options" id="show_renew_subscription_section">
											<th><?php esc_html_e( 'Button CSS', 'armember-membership' ); ?></th>
											<td><textarea class="arm_popup_textarea wpb_vc_param_value" name="renew_css" id="arm_button_css" rows="3"></textarea></td>
										</tr>
										<tr class="form_popup_options" id="show_renew_subscription_section">
											<th><?php esc_html_e( 'Button Hover CSS', 'armember-membership' ); ?></th>
											<td><textarea class="arm_popup_textarea wpb_vc_param_value" name="renew_hover_css" id="arm_button_hover_css" rows="3"></textarea></td>
											</tr> 
										<tr> 
											<th><?php esc_html_e( 'Display Cancel Subscription Button?', 'armember-membership' ); ?></th>
											<td>
												<input type="hidden" name="display_cancel_button" value="false" class="wpb_vc_param_value" id="arm_show_cancel_subscription_hidden" />
												<label class="form_show_cancel_subscription_type_radio">
												<input type="radio" name="arm_show_cancel_subscription_input" value="false" checked="checked" id="arm_show_cancel_subscription_hidden_false" onclick="arm_show_cancel_subscription();" class="arm_iradio" />
													<?php esc_html_e( 'No', 'armember-membership' ); ?>
												</label>
												<label class="form_show_cancel_subscription_type_radio">
													<input type="radio" name="arm_show_cancel_subscription_input" value="true" id="arm_show_cancel_subscription_true" onclick="arm_show_cancel_subscription();" class="arm_iradio" />
													<?php esc_html_e( 'Yes', 'armember-membership' ); ?>
												</label>
											</td>
										</tr>
										<tr class="form_popup_options" id="show_cancel_subscription_section">
											<th><?php esc_html_e( 'Button Text', 'armember-membership' ); ?></th>
											<td>
												<input type="text" class="wpb_vc_param_value" name="cancel_text" value="<?php esc_attr_e( 'Cancel', 'armember-membership' ); ?>" id="arm_cancel_membership_text" />
											</td>
										</tr>
										<tr class="form_popup_options" id="show_cancel_subscription_section">
											<th><?php esc_html_e( 'Button CSS', 'armember-membership' ); ?></th>
											<td><textarea class="arm_popup_textarea wpb_vc_param_value" name="cancel_css" id="arm_cancel_button_css" rows="3"></textarea></td>
										</tr> 
										<tr class="form_popup_options" id="show_cancel_subscription_section">
											<th><?php esc_html_e( 'Button Hover CSS', 'armember-membership' ); ?></th>
											<td><textarea class="arm_popup_textarea wpb_vc_param_value" name="cancel_hover_css" id="arm_cancel_button_hover_css" rows="3"></textarea></td>
										</tr>
										<tr class="form_popup_options" id="show_cancel_subscription_section">
											<th><?php esc_html_e( 'Subscription Cancelled Message', 'armember-membership' ); ?></th>
											<td><input type="text" class="wpb_vc_param_value" name="cancel_message" value="<?php esc_attr_e( 'Your subscription has been cancelled.', 'armember-membership' ); ?>" id="arm_cancel_message" />
										</tr>
										<tr>
											<th><?php esc_html_e( 'Display Update Card Subscription Button?', 'armember-membership' ); ?></th>
											<td>
												<input type="hidden" name="display_update_card_button" value="true" class="wpb_vc_param_value" id="arm_show_update_card_subscription_hidden" />
												<label class="form_show_update_card_subscription_type_radio">
												<input type="radio" name="arm_show_update_card_subscription_input" value="false" checked="checked" id="arm_show_update_card_subscription_hidden_false" onclick="arm_show_update_card_subscription();" class="arm_iradio" />
													<?php esc_html_e( 'No', 'armember-membership' ); ?>
												</label>
												<label class="form_show_update_card_subscription_type_radio">
													<input type="radio" name="arm_show_update_card_subscription_input" value="true" id="arm_show_update_card_subscription_true" onclick="arm_show_update_card_subscription();" class="arm_iradio" />
													<?php esc_html_e( 'Yes', 'armember-membership' ); ?>
												</label>
											</td>
										</tr>
										<tr class="form_popup_options" id="show_update_card_subscription_section">
											<th><?php esc_html_e( 'Update Card Text', 'armember-membership' ); ?></th>
											<td>
												<input type="text" class="wpb_vc_param_value" name="update_card_text" value="<?php esc_attr_e( 'Update Card', 'armember-membership' ); ?>" id="arm_update_card_membership_text" />
											</td>
										</tr>
										<tr class="form_popup_options" id="show_update_card_subscription_section">
											<th><?php esc_html_e( 'Button CSS', 'armember-membership' ); ?></th>
											<td><textarea class="arm_popup_textarea wpb_vc_param_value" name="update_card_css" id="arm_update_card_button_css" rows="3"></textarea></td>
										</tr>
										<tr class="form_popup_options" id="show_update_card_subscription_section">
											<th><?php esc_html_e( 'Button Hover CSS', 'armember-membership' ); ?></th>
											<td><textarea class="arm_popup_textarea wpb_vc_param_value" name="update_card_hover_css" id="arm_update_card_button_hover_css" rows="3"></textarea></td>
										</tr> 
										<tr>
											<th><?php esc_html_e( 'Trial Active Label', 'armember-membership' ); ?></th>
											<td><input type="text" class="wpb_vc_param_value" name="trial_active" value="<?php esc_attr_e( 'trial active', 'armember-membership' ); ?>" id="arm_trial_active" />
										</tr>
										<tr>
											<th><?php esc_html_e( 'No Records Message', 'armember-membership' ); ?></th>
											<td><input type="text" class="wpb_vc_param_value" name="message_no_record" value="<?php esc_attr_e( 'There is no membership found.', 'armember-membership' ); ?>" id="arm_message_no_record" />
										</tr>
									</table>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}







	public function ARM_arm_username() {
		global $arm_lite_version,$ARMemberLite;
		if ( function_exists( 'vc_map' ) ) {
			vc_map(
				array(
					'name'              => esc_html__( 'ARMember Username', 'armember-membership' ),
					'description'       => '',
					'base'              => 'arm_username',
					'category'          => 'armember-membership',
					'class'             => '',
					'controls'          => 'full',
					'icon'              => 'arm_vc_icon',
					'admin_enqueue_css' => array( MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css' ),
					'front_enqueue_css' => MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css',
					'params'            => array(
						array(
							'type'        => 'ARM_arm_username_shortcode',
							'heading'     => false,
							'param_name'  => 'arm_username',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
					),
				)
			);
		}
	}
	public function ARM_arm_username_html( $settings, $value ) {
		global $wpdb, $ARMemberLite, $arm_slugs, $arm_shortcodes, $arm_members_class, $arm_global_settings, $arm_email_settings,  $arm_member_forms, $arm_subscription_plans;

		echo '<input id="' . esc_attr( $settings['param_name'] ) . '" name="' . esc_attr( $settings['param_name'] ) . '" class=" ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_armfield" type="hidden" value="' . esc_attr( $value ) . '" />';

		if ( $this->is_membership_vdextend == 0 ) {
			$this->is_membership_vdextend = 1;
			?>
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm-font-awesome.css" />
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm_tinymce.css" />
			<div class="arm_tinymce_shortcode_options_container arm_shortcode_options_popup_wrapper">
				<div class="arm_tinymce_content_block accordion_menu">
					<div class="arm_tinymce_shortcode_content">
						<div class="arm_shortcode_content_header accordion_header" style="box-shadow:none;border-bottom: none;"><?php esc_html_e( 'ARMember Username', 'armember-membership' ); ?></div>
					</div>
				</div>
			</div>
			<?php
		}
	}
	public function ARM_arm_user_plan() {
		global $arm_lite_version,$ARMemberLite;
		if ( function_exists( 'vc_map' ) ) {
			vc_map(
				array(
					'name'              => esc_html__( 'ARMember User Plan', 'armember-membership' ),
					'description'       => '',
					'base'              => 'arm_user_plan',
					'category'          => 'armember-membership',
					'class'             => '',
					'controls'          => 'full',
					'icon'              => 'arm_vc_icon',
					'admin_enqueue_css' => array( MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css' ),
					'front_enqueue_css' => MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css',
					'params'            => array(
						array(
							'type'        => 'ARM_arm_user_plan_shortcode',
							'heading'     => false,
							'param_name'  => 'arm_user_plan',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
					),
				)
			);
		}
	}
	public function ARM_arm_user_plan_html( $settings, $value ) {
		global $wpdb, $ARMemberLite, $arm_slugs, $arm_shortcodes, $arm_members_class, $arm_global_settings, $arm_email_settings, $arm_manage_coupons, $arm_member_forms, $arm_subscription_plans;
		echo '<input id="' . esc_attr( $settings['param_name'] ) . '" name="' . esc_attr( $settings['param_name'] ) . '" class=" ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_armfield" type="hidden" value="' . esc_attr( $value ) . '" />';
		if ( $this->is_membership_vdextend == 0 ) {
			$this->is_membership_vdextend = 1;
			?>
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm-font-awesome.css" />
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm_tinymce.css" />
			<div class="arm_tinymce_shortcode_options_container arm_shortcode_options_popup_wrapper">
				<div class="arm_tinymce_content_block accordion_menu">
					<div class="arm_tinymce_shortcode_content">
						<div class="arm_shortcode_content_header accordion_header" style="box-shadow:none;border-bottom: none;"><?php esc_html_e( 'ARMember User Plan', 'armember-membership' ); ?></div>
					</div>
				</div>
			</div>
			<?php
		}
	}

	public function ARM_arm_displayname() {
		global $arm_lite_version,$ARMemberLite;
		if ( function_exists( 'vc_map' ) ) {
			vc_map(
				array(
					'name'              => esc_html__( 'ARMember User Displayname', 'armember-membership' ),
					'description'       => '',
					'base'              => 'arm_displayname',
					'category'          => 'armember-membership',
					'class'             => '',
					'controls'          => 'full',
					'icon'              => 'arm_vc_icon',
					'admin_enqueue_css' => array( MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css' ),
					'front_enqueue_css' => MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css',
					'params'            => array(
						array(
							'type'        => 'ARM_arm_displayname_shortcode',
							'heading'     => false,
							'param_name'  => 'arm_userdisplay',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
					),
				)
			);
		}
	}
	public function ARM_arm_displayname_html( $settings, $value ) {
		global $wpdb, $ARMemberLite, $arm_slugs, $arm_shortcodes, $arm_members_class, $arm_global_settings, $arm_email_settings,  $arm_member_forms, $arm_subscription_plans;

		echo '<input id="' . esc_attr( $settings['param_name'] ) . '" name="' . esc_attr( $settings['param_name'] ) . '" class=" ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_armfield" type="hidden" value="' . esc_attr( $value ) . '" />';

		if ( $this->is_membership_vdextend == 0 ) {
			$this->is_membership_vdextend = 1;
			?>
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm-font-awesome.css" />
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm_tinymce.css" />
			<div class="arm_tinymce_shortcode_options_container arm_shortcode_options_popup_wrapper">
				<div class="arm_tinymce_content_block accordion_menu">
					<div class="arm_tinymce_shortcode_content">
						<div class="arm_shortcode_content_header accordion_header" style="box-shadow:none;border-bottom: none;"><?php esc_html_e( 'ARMember User Displayname', 'armember-membership' ); ?></div>
					</div>
				</div>
			</div>
			<?php
		}
	}

	public function ARM_arm_firstname_lastname() {
		global $arm_lite_version,$ARMemberLite;
		if ( function_exists( 'vc_map' ) ) {
			vc_map(
				array(
					'name'              => esc_html__( 'ARMember User Firstname Lastname', 'armember-membership' ),
					'description'       => '',
					'base'              => 'arm_firstname_lastname',
					'category'          => 'armember-membership',
					'class'             => '',
					'controls'          => 'full',
					'icon'              => 'arm_vc_icon',
					'admin_enqueue_css' => array( MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css' ),
					'front_enqueue_css' => MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css',
					'params'            => array(
						array(
							'type'        => 'ARM_arm_firstname_lastname_shortcode',
							'heading'     => false,
							'param_name'  => 'arm_firstname_lastname',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
					),
				)
			);
		}
	}
	public function ARM_arm_firstname_lastname_html( $settings, $value ) {
		global $wpdb, $ARMemberLite, $arm_slugs, $arm_shortcodes, $arm_members_class, $arm_global_settings, $arm_email_settings,  $arm_member_forms, $arm_subscription_plans;

		echo '<input id="' . esc_attr( $settings['param_name'] ) . '" name="' . esc_attr( $settings['param_name'] ) . '" class=" ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_armfield" type="hidden" value="' . esc_attr( $value ) . '" />';

		if ( $this->is_membership_vdextend == 0 ) {
			$this->is_membership_vdextend = 1;
			?>
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm-font-awesome.css" />
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm_tinymce.css" />
			<div class="arm_tinymce_shortcode_options_container arm_shortcode_options_popup_wrapper">
				<div class="arm_tinymce_content_block accordion_menu">
					<div class="arm_tinymce_shortcode_content">
						<div class="arm_shortcode_content_header accordion_header" style="box-shadow:none;border-bottom: none;"><?php esc_html_e( 'ARMember User Firstname Lastname', 'armember-membership' ); ?></div>
					</div>
				</div>
			</div>
			<?php
		}
	}

	public function ARM_arm_avatar() {
		global $arm_lite_version,$ARMemberLite;
		if ( function_exists( 'vc_map' ) ) {
			vc_map(
				array(
					'name'              => esc_html__( 'ARMember User Avatar', 'armember-membership' ),
					'description'       => '',
					'base'              => 'arm_avatar',
					'category'          => 'armember-membership',
					'class'             => '',
					'controls'          => 'full',
					'icon'              => 'arm_vc_icon',
					'admin_enqueue_css' => array( MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css' ),
					'front_enqueue_css' => MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css',
					'params'            => array(
						array(
							'type'        => 'ARM_arm_avatar_shortcode',
							'heading'     => false,
							'param_name'  => 'arm_user_avatar',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
					),
				)
			);
		}
	}
	public function ARM_arm_avatar_html( $settings, $value ) {
		global $wpdb, $ARMemberLite, $arm_slugs, $arm_shortcodes, $arm_members_class, $arm_global_settings, $arm_email_settings,  $arm_member_forms, $arm_subscription_plans;

		echo '<input id="' . esc_attr( $settings['param_name'] ) . '" name="' . esc_attr( $settings['param_name'] ) . '" class=" ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_armfield" type="hidden" value="' . esc_attr( $value ) . '" />';

		if ( $this->is_membership_vdextend == 0 ) {
			$this->is_membership_vdextend = 1;
			?>
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm-font-awesome.css" />
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm_tinymce.css" />
			<div class="arm_tinymce_shortcode_options_container arm_shortcode_options_popup_wrapper">
				<div class="arm_tinymce_content_block a  ccordion_menu">
					<div class="arm_tinymce_shortcode_content">
						<div class="arm_shortcode_content_header accordion_header" style="box-shadow:none;border-bottom: none;"><?php esc_html_e( 'ARMember User Avatar', 'armember-membership' ); ?></div>
					</div>
				</div>
			</div>
			<?php
		}
	}

	public function ARM_arm_usermeta() {
		global $arm_lite_version, $ARMemberLite;
		if ( function_exists( 'vc_map' ) ) {
			vc_map(
				array(
					'name'              => esc_html__( 'ARMember User Custom Meta', 'armember-membership' ),
					'description'       => '',
					'base'              => 'arm_usermeta',
					'category'          => 'armember-membership',
					'class'             => '',
					'controls'          => 'full',
					'icon'              => 'arm_vc_icon',
					'admin_enqueue_css' => array( MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css' ),
					'front_enqueue_css' => MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css',
					'params'            => array(
						array(
							'type'        => 'ARM_arm_usermeta_shortcode',
							'heading'     => false,
							'param_name'  => 'meta',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
					),
				)
			);
		}
	}
	public function ARM_arm_usermeta_html( $settings, $value ) {
		global $wpdb, $ARMemberLite, $arm_slugs, $arm_shortcodes, $arm_members_class, $arm_global_settings, $arm_email_settings,  $arm_member_forms, $arm_subscription_plans;

		echo '<input id="' . esc_attr( $settings['param_name'] ) . '" name="' . esc_attr( $settings['param_name'] ) . '" class=" ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_armfield" type="hidden" value="' . esc_attr( $value ) . '" />';

		if ( $this->is_membership_vdextend == 0 ) {
			$this->is_membership_vdextend = 1;
			?>
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm-font-awesome.css" />
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm_tinymce.css" />

			<div class="arm_tinymce_shortcode_options_container arm_shortcode_options_popup_wrapper">
				<div class="arm_tinymce_content_block accordion_menu">
					<!-- *********************[arm_custome_user_meta]********************* -->
					<div class="arm_tinymce_shortcode_content">
						<div class="arm_shortcode_content_header accordion_header" style="box-shadow:none;border-bottom: none;"><?php esc_html_e( 'ARMember User Custom Meta', 'armember-membership' ); ?></div>
						<div class="arm_shortcode_detail_wrapper">
							<div class="arm_shortcode_detail_container">
								<div class="arm_shortcode_generator_form arm_generator_arm_usermeta">
									<form onsubmit="return false;">
										<table class="arm_shortcode_option_table">
											<tr>
												<th> <?php esc_html_e( 'Enter Usermeta Name', 'armember-membership' ); ?> </th>
												<td>
													<input type="text" class="wpb_vc_param_value" name="meta" value="" id="arm_user_custom_meta" />
												</td>
											</tr>
										</table>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}




	public function ARM_arm_user_planinfo() {
		global $arm_lite_version, $ARMemberLite;
		if ( function_exists( 'vc_map' ) ) {
			vc_map(
				array(
					'name'              => esc_html__( 'ARMember User Plan Information', 'armember-membership' ),
					'description'       => '',
					'base'              => 'arm_user_planinfo',
					'category'          => 'armember-membership',
					'class'             => '',
					'controls'          => 'full',
					'icon'              => 'arm_vc_icon',
					'admin_enqueue_css' => array( MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css' ),
					'front_enqueue_css' => MEMBERSHIPLITE_URL . '/core/vc/arm_vc.css',
					'params'            => array(
						array(
							'type'        => 'ARM_arm_user_planinfo_shortcode',
							'heading'     => false,
							'param_name'  => 'plan_id',
							'value'       => '',
							'description' => '&nbsp;',
							'admin_label' => true,
						),
						array(
							'type'        => 'ARM_arm_user_planinfo_shortcode',
							'heading'     => false,
							'param_name'  => 'plan_info',
							'value'       => false,
							'description' => '&nbsp;',
							'admin_label' => true,
						),
					),
				)
			);
		}
	}
	public function ARM_arm_user_planinfo_html( $settings, $value ) {
		 global $wpdb, $ARMemberLite, $arm_slugs, $arm_shortcodes, $arm_members_class, $arm_global_settings, $arm_email_settings,  $arm_member_forms, $arm_subscription_plans;

		echo '<input id="' . esc_attr( $settings['param_name'] ) . '" name="' . esc_attr( $settings['param_name'] ) . '" class=" ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_armfield" type="hidden" value="' . esc_attr( $value ) . '" />';

		$all_plans = $arm_subscription_plans->arm_get_all_subscription_plans( 'arm_subscription_plan_id, arm_subscription_plan_name' );
		if ( $this->is_membership_vdextend == 0 ) {
			$this->is_membership_vdextend = 1;
			?>
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm-font-awesome.css" />
			<link rel="stylesheet" href="<?php echo MEMBERSHIPLITE_URL; //phpcs:ignore ?>/css/arm_tinymce.css" />
			<div class="arm_tinymce_shortcode_options_container arm_shortcode_options_popup_wrapper">
				<div class="arm_tinymce_content_block accordion_menu">
					<!-- *********************[arm_setup]********************* -->
					<div class="arm_tinymce_shortcode_content">
						<div class="arm_shortcode_content_header accordion_header" style="box-shadow:none;border-bottom: none;"><?php esc_html_e( 'ARMember User Plan Information', 'armember-membership' ); ?></div>
						<div class="arm_shortcode_detail_wrapper">
							<div class="arm_shortcode_detail_container">
								<div class="arm_shortcode_description"><?php // esc_html_e('Membership Setup Wizard Shortcode.', 'armember-membership'); ?></div>
								<div class="arm_shortcode_generator_form arm_generator_arm_setup" style="width:660px;">
									<form onsubmit="return false;">
										<table class="arm_shortcode_option_table">
												<tr>
													<th><?php esc_html_e( 'Select Membership Plan', 'armember-membership' ); ?></th>
													<td>
														<input type='hidden' class="wpb_vc_param_value" name="plan_id" id="arm_plan_id" value=""/>
														<dl class="arm_selectbox column_level_dd arm_member_form_dropdown">
															<dt><span></span><input type="text" style="display:none;" value="" class="arm_autocomplete"/><i class="armfa armfa-caret-down armfa-lg"></i></dt>
															<dd><ul data-id="arm_plan_id">
															<li data-label="<?php esc_html_e( 'Select Plan', 'armember-membership' ); ?>" data-value=""><?php esc_html_e( 'Select Plan', 'armember-membership' ); ?></li>
															<?php
															foreach ( $all_plans as $p ) {
																echo '<li data-label="' . stripslashes( esc_attr( $p['arm_subscription_plan_name'] ) ) . '" data-value="' . esc_attr($p['arm_subscription_plan_id']) . '">' . stripslashes( esc_attr( $p['arm_subscription_plan_name'] ) ) . '</li>'; //phpcs:ignore
															}
															?>
															</ul></dd>
														</dl>
													</td>
												</tr>
												<tr>
													<th><?php esc_html_e( 'Select Plan Information', 'armember-membership' ); ?></th>
													<td>
														<input type='hidden' class="wpb_vc_param_value" name="plan_info" id="plan_info" value="start_date"/>
														<dl class="arm_selectbox column_level_dd arm_member_form_dropdown">
															<dt><span></span><input type="text" style="display:none;" value="" class="arm_autocomplete"/><i class="armfa armfa-caret-down armfa-lg"></i></dt>
															<dd><ul data-id="plan_info">
																	<li data-label="<?php esc_attr_e( 'Start Date', 'armember-membership' ); ?>" data-value="arm_start_plan"><?php esc_html_e( 'Start Date', 'armember-membership' ); ?></li>
																	<li data-label="<?php esc_attr_e( 'End Date', 'armember-membership' ); ?>" data-value="arm_expire_plan"><?php esc_html_e( 'End Date', 'armember-membership' ); ?></li>
																	<li data-label="<?php esc_attr_e( 'Trial Start Date', 'armember-membership' ); ?>" data-value="arm_trial_start"><?php esc_html_e( 'Trial Start Date', 'armember-membership' ); ?></li>
																	<li data-label="<?php esc_attr_e( 'Trial End Date', 'armember-membership' ); ?>" data-value="arm_trial_end"><?php esc_html_e( 'Trial End Date', 'armember-membership' ); ?></li>
																	<li data-label="<?php esc_attr_e( 'Grace End Date', 'armember-membership' ); ?>" data-value="arm_grace_period_end"><?php esc_html_e( 'Grace End Date', 'armember-membership' ); ?></li>
																	<li data-label="<?php esc_attr_e( 'Paid By', 'armember-membership' ); ?>" data-value="arm_user_gateway"><?php esc_html_e( 'Paid By', 'armember-membership' ); ?></li>
																	<li data-label="<?php esc_attr_e( 'Completed Recurrence', 'armember-membership' ); ?>" data-value="arm_completed_recurring"><?php esc_html_e( 'Completed Recurrence', 'armember-membership' ); ?></li>
																	<li data-label="<?php esc_attr_e( 'Next Due Date', 'armember-membership' ); ?>" data-value="arm_next_due_payment"><?php esc_html_e( 'Next Due Date', 'armember-membership' ); ?></li>
																	<li data-label="<?php esc_attr_e( 'Payment Mode', 'armember-membership' ); ?>" data-value="arm_payment_mode"><?php esc_html_e( 'Payment Mode', 'armember-membership' ); ?></li>
																	<li data-label="<?php esc_attr_e( 'Payment Cycle', 'armember-membership' ); ?>" data-value="arm_payment_cycle"><?php esc_html_e( 'Payment Cycle', 'armember-membership' ); ?></li>
																</ul></dd>
														</dl>
													</td>
												</tr>
										</table>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}
}?>
