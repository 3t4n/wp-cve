<?php

class MailsterCoolCaptcha {

	private $plugin_path;
	private $plugin_url;

	public function __construct() {

		$this->plugin_path = plugin_dir_path( MAILSTER_COOLCAPTCHA_FILE );
		$this->plugin_url  = plugin_dir_url( MAILSTER_COOLCAPTCHA_FILE );

		register_activation_hook( MAILSTER_COOLCAPTCHA_FILE, array( &$this, 'activate' ) );

		load_plugin_textdomain( 'mailster-coolcaptcha' );

		add_action( 'init', array( &$this, 'init' ) );
	}

	public function activate( $network_wide ) {

		if ( function_exists( 'mailster' ) ) {

			$defaults = array(
				'coolcaptcha_error_msg' => __( 'Enter the text of the captcha', 'mailster-coolcaptcha' ),
				'coolcaptcha_formlabel' => __( 'Enter the text of the captcha', 'mailster-coolcaptcha' ),
				'coolcaptcha_forms'     => array(),
				'coolcaptcha_format'    => 'jpeg',
				'coolcaptcha_quality'   => 2,
				'coolcaptcha_width'     => 200,
				'coolcaptcha_height'    => 70,
				'coolcaptcha_blur'      => true,
				'coolcaptcha_min'       => 5,
				'coolcaptcha_max'       => 8,
				'coolcaptcha_yp'        => 12,
				'coolcaptcha_ya'        => 14,
				'coolcaptcha_xp'        => 11,
				'coolcaptcha_xa'        => 5,
				'coolcaptcha_rot'       => 8,
				'coolcaptcha_language'  => 'en',
			);

			$mailster_options = mailster_options();

			foreach ( $defaults as $key => $value ) {
				if ( ! isset( $mailster_options[ $key ] ) ) {
					mailster_update_option( $key, $value );
				}
			}
		}

	}

	public function init() {

		if ( is_admin() ) {

			add_filter( 'mailster_setting_sections', array( &$this, 'settings_tab' ) );

			add_action( 'mailster_section_tab_coolcaptcha', array( &$this, 'settings' ) );

		}

		add_filter( 'mailster_form_fields', array( &$this, 'form_fields' ), 10, 3 );
		add_filter( 'mailster_submit_errors', array( &$this, 'check_captcha_v1' ), 10, 1 );
		add_filter( 'mailster_submit', array( &$this, 'check_captcha' ), 10, 1 );
		add_action( 'wp_ajax_mailster_coolcaptcha_img', array( &$this, 'coolcaptcha_img' ) );
		add_action( 'wp_ajax_nopriv_mailster_coolcaptcha_img', array( &$this, 'coolcaptcha_img' ) );

	}

	public function settings_tab( $settings ) {

		$position = 4;
		$settings = array_slice( $settings, 0, $position, true ) +
					array( 'coolcaptcha' => 'Cool Captcha' ) +
					array_slice( $settings, $position, null, true );

		return $settings;
	}

	public function settings() {

		?>
	<table class="form-table">
		<tr valign="top">
			<th scope="row"><?php esc_html_e( 'Error Message', 'mailster-coolcaptcha' ); ?></th>
			<td><p><input type="text" name="mailster_options[coolcaptcha_error_msg]" value="<?php echo esc_attr( mailster_option( 'coolcaptcha_error_msg' ) ); ?>" class="large-text"></p></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php esc_html_e( 'Form Label', 'mailster-coolcaptcha' ); ?></th>
			<td><p><input type="text" name="mailster_options[coolcaptcha_formlabel]" value="<?php echo esc_attr( mailster_option( 'coolcaptcha_formlabel' ) ); ?>" class="large-text"></p></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php esc_html_e( 'Disable for logged in users', 'mailster-coolcaptcha' ); ?></th>
			<td><label><input type="hidden" name="mailster_options[coolcaptcha_loggedin]" value=""><input type="checkbox" name="mailster_options[coolcaptcha_loggedin]" value="1" <?php checked( mailster_option( 'coolcaptcha_loggedin' ) ); ?>> <?php esc_html_e( 'disable the captcha for logged in users', 'mailster-coolcaptcha' ); ?></label></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php esc_html_e( 'Forms', 'mailster-coolcaptcha' ); ?><p class="description"><?php esc_html_e( 'select forms which require a captcha', 'mailster-coolcaptcha' ); ?></p></th>
			<td>
				<ul>
				<?php
				$forms   = mailster( 'forms' )->get_all();
				$enabled = mailster_option( 'coolcaptcha_forms', array() );
				foreach ( $forms as $form ) {
					$form = (object) $form;
					$id   = isset( $form->ID ) ? $form->ID : $form->id;
					echo '<li><label><input name="mailster_options[coolcaptcha_forms][]" type="checkbox" value="' . $id . '" ' . ( checked( in_array( $id, $enabled ), true, false ) ) . '>' . $form->name . '</label></li>';
				}

				?>
				</ul>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php esc_html_e( 'Preview', 'mailster-coolcaptcha' ); ?>
			<p class="description"><?php esc_html_e( 'you have to save the settings to update the preview!', 'mailster-coolcaptcha' ); ?></p></th>
			<td>
				<?php
				printf(
					'<br><img src="%s" width="' . mailster_option( 'coolcaptcha_width' ) . '" height="' . mailster_option( 'coolcaptcha_height', 70 ) . '" style="border:1px solid #ccc">',
					add_query_arg(
						array(
							'action'  => 'mailster_coolcaptcha_img',
							'nocache' => time(),
						),
						admin_url( 'admin-ajax.php' )
					)
				);
				?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php esc_html_e( 'Image Format', 'mailster-coolcaptcha' ); ?></th>
			<td><select name="mailster_options[coolcaptcha_format]">
				<?php
				$themes      = array(
					'jpeg' => 'JPG',
					'png'  => 'PNG',
				);
					$current = mailster_option( 'coolcaptcha_format' );
				foreach ( $themes as $key => $name ) {
					echo '<option value="' . $key . '" ' . ( selected( $key, $current, false ) ) . '>' . $name . '</option>';
				}

				?>
			</select></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php esc_html_e( 'Quality', 'mailster-coolcaptcha' ); ?></th>
			<td><select name="mailster_options[coolcaptcha_quality]">
				<?php
				$themes      = array(
					1 => 'low',
					2 => 'medium',
					3 => 'high',
				);
					$current = mailster_option( 'coolcaptcha_quality' );
				foreach ( $themes as $key => $name ) {
					echo '<option value="' . $key . '" ' . ( selected( $key, $current, false ) ) . '>' . $name . '</option>';
				}

				?>
			</select></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php esc_html_e( 'Dimensions', 'mailster-coolcaptcha' ); ?></th>
			<td><p><input type="text" name="mailster_options[coolcaptcha_width]" value="<?php echo esc_attr( mailster_option( 'coolcaptcha_width', 200 ) ); ?>" class="small-text"> &times; <input type="text" name="mailster_options[coolcaptcha_height]" value="<?php echo esc_attr( mailster_option( 'coolcaptcha_height' ) ); ?>" class="small-text"> px</p></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php esc_html_e( 'Blur', 'mailster-coolcaptcha' ); ?></th>
			<td><label><input type="hidden" name="mailster_options[coolcaptcha_blur]" value=""><input type="checkbox" name="mailster_options[coolcaptcha_blur]" value="1" <?php checked( mailster_option( 'coolcaptcha_blur' ) ); ?>> <?php esc_html_e( 'use blur', 'mailster-coolcaptcha' ); ?></label></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php esc_html_e( 'Line', 'mailster-coolcaptcha' ); ?></th>
			<td><label><input type="hidden" name="mailster_options[coolcaptcha_line]" value=""><input type="checkbox" name="mailster_options[coolcaptcha_line]" value="1" <?php checked( mailster_option( 'coolcaptcha_line' ) ); ?>> <?php esc_html_e( 'strike out the text', 'mailster-coolcaptcha' ); ?></label></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php esc_html_e( 'Word length', 'mailster-coolcaptcha' ); ?></th>
			<td><p><?php esc_html_e( 'use at least', 'mailster-coolcaptcha' ); ?> <input type="text" name="mailster_options[coolcaptcha_min]" value="<?php echo esc_attr( mailster_option( 'coolcaptcha_min' ) ); ?>" class="small-text"> <?php esc_html_e( 'letters per word but max', 'mailster-coolcaptcha' ); ?> <input type="text" name="mailster_options[coolcaptcha_max]" value="<?php echo esc_attr( mailster_option( 'coolcaptcha_max' ) ); ?>" class="small-text"></p></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php esc_html_e( 'Wave configuration', 'mailster-coolcaptcha' ); ?></th>
			<td><p>Y-period: <input type="text" name="mailster_options[coolcaptcha_yp]" value="<?php echo esc_attr( mailster_option( 'coolcaptcha_yp' ) ); ?>" class="small-text">
				Y-amplitude: <input type="text" name="mailster_options[coolcaptcha_ya]" value="<?php echo esc_attr( mailster_option( 'coolcaptcha_ya' ) ); ?>" class="small-text">
				X-period: <input type="text" name="mailster_options[coolcaptcha_xp]" value="<?php echo esc_attr( mailster_option( 'coolcaptcha_xp' ) ); ?>" class="small-text">
				X-amplitude: <input type="text" name="mailster_options[coolcaptcha_xa]" value="<?php echo esc_attr( mailster_option( 'coolcaptcha_xa' ) ); ?>" class="small-text"> </p></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php esc_html_e( 'Max. rotation', 'mailster-coolcaptcha' ); ?></th>
			<td><p><input type="text" name="mailster_options[coolcaptcha_rot]" value="<?php echo esc_attr( mailster_option( 'coolcaptcha_rot' ) ); ?>" class="small-text"></p></td>
		</tr>
		<tr valign="top">
			<th scope="row"><?php esc_html_e( 'Language', 'mailster-coolcaptcha' ); ?></th>
			<td><select name="mailster_options[coolcaptcha_language]">
				<?php
				$languages   = array(
					'en' => 'English',
					'es' => 'Spanish',
				);
					$current = mailster_option( 'coolcaptcha_language' );
				foreach ( $languages as $key => $name ) {
					echo '<option value="' . $key . '" ' . ( selected( $key, $current, false ) ) . '>' . $name . '</option>';
				}

				?>
			</select></td>
		</tr>
	</table>

		<?php
	}

	public function form_fields( $fields, $formid, $form ) {

		if ( is_user_logged_in() && mailster_option( 'coolcaptcha_loggedin' ) ) {
			return $fields;
		}

		if ( ! in_array( $formid, mailster_option( 'coolcaptcha_forms', array() ) ) ) {
			return $fields;
		}

		$offset = mailster_option( 'gdpr_forms' ) && isset( $fields['_gdpr'] ) ? 2 : 1;

		$position = count( $fields ) - $offset;
		$fields   = array_slice( $fields, 0, $position, true ) +
					array( '_coolcaptcha' => $this->get_field( $form, $formid, $form ) ) +
					array_slice( $fields, $position, null, true );

		return $fields;

	}

	public function coolcaptcha_img() {

		if ( ! session_id() ) {
			session_start();
		}

		$formid = ( isset( $_GET['formid'] ) ) ? intval( $_GET['formid'] ) : 0;

		require_once $this->plugin_path . 'captcha/captcha.php';
		$captcha = new SimpleCaptcha();

		$captcha->resourcesPath = $this->plugin_path . 'captcha';
		$captcha->wordsFile     = 'words/' . mailster_option( 'coolcaptcha_language' ) . '.php';
		$captcha->session_var   = 'mailster_coolcaptcha_' . $formid;

		$captcha->width  = intval( mailster_option( 'coolcaptcha_width' ) );
		$captcha->height = intval( mailster_option( 'coolcaptcha_height' ) );

		$captcha->imageFormat = mailster_option( 'coolcaptcha_format', 'jpg' );

		$captcha->lineWidth = mailster_option( 'coolcaptcha_line' ) ? 3 : 0;
		$captcha->scale     = intval( mailster_option( 'coolcaptcha_quality' ) );

		$captcha->minWordLength = intval( mailster_option( 'coolcaptcha_min' ) );
		$captcha->maxWordLength = intval( mailster_option( 'coolcaptcha_max' ) );

		$captcha->maxRotation = intval( mailster_option( 'coolcaptcha_rot' ) );

		$captcha->blur       = (bool) mailster_option( 'coolcaptcha_blur' );
		$captcha->Yperiod    = intval( mailster_option( 'coolcaptcha_yp' ) );
		$captcha->Yamplitude = intval( mailster_option( 'coolcaptcha_ya' ) );
		$captcha->Xperiod    = intval( mailster_option( 'coolcaptcha_xp' ) );
		$captcha->Xamplitude = intval( mailster_option( 'coolcaptcha_xa' ) );

		$captcha->CreateImage();
		exit;

	}

	public function get_field( $html, $formid, $form ) {

		$form = (object) $form;

		$width  = intval( mailster_option( 'coolcaptcha_width' ) );
		$height = intval( mailster_option( 'coolcaptcha_height' ) );

		$label = mailster_option( 'coolcaptcha_formlabel' );

		$html = '<div class="mailster-wrapper mailster-_coolcaptcha-wrapper">';
		if ( empty( $form->inline ) ) {
			$html .= '<label for="mailster-_coolcaptcha-' . $formid . '">' . $label . '</label>';
		}
		$img = sprintf(
			'<div class="mailster-coolcaptcha-wrap"><img title="' . __( 'click to reload', 'mailster-coolcaptcha' ) . '" onclick="var s=this.src;this.src=s.replace(/nocache=\d+/, \'nocache=\'+(+new Date()))" src="%s" style="cursor:pointer;width:%dpx;height:%dpx"></div>',
			add_query_arg(
				array(
					'action'  => 'mailster_coolcaptcha_img',
					'nocache' => time(),
					'formid'  => $formid,
				),
				admin_url( 'admin-ajax.php' )
			),
			$width,
			$height
		);

		$input = '<input id="mailster-_coolcaptcha-' . $formid . '" name="mailster__coolcaptcha" type="text" value="" class="input mailster-coolcaptcha" placeholder="' . ( ! empty( $form->inline ) ? $label : '' ) . '">';

		$html = $html . $img . $input;

		$html .= '</div>';

		return $html;

	}

	public function check_captcha( $object ) {

		if ( is_user_logged_in() && mailster_option( 'coolcaptcha_loggedin' ) ) {
			return $object;
		}

		$formid = ( isset( $_POST['formid'] ) ) ? intval( $_POST['formid'] ) : 0;

		if ( ! in_array( $formid, mailster_option( 'coolcaptcha_forms', array() ) ) ) {
			return $object;
		}

		if ( ! session_id() ) {
			session_start();
		}

		$session_var = 'mailster_coolcaptcha_' . $formid;

		if ( empty( $_SESSION[ $session_var ] ) || strtolower( trim( $_REQUEST['mailster__coolcaptcha'] ) ) != $_SESSION[ $session_var ] ) {
			$object['errors']['_coolcaptcha'] = mailster_option( 'coolcaptcha_error_msg' );
		} else {

		}

		return $object;

	}

	public function check_captcha_v1( $errors ) {

		if ( is_user_logged_in() && mailster_option( 'coolcaptcha_loggedin' ) ) {
			return $errors;
		}

		$formid = ( isset( $_POST['formid'] ) ) ? intval( $_POST['formid'] ) : 0;

		if ( ! in_array( $formid, mailster_option( 'coolcaptcha_forms', array() ) ) ) {
			return $errors;
		}

		if ( ! session_id() ) {
			session_start();
		}

		$session_var = 'mailster_coolcaptcha_' . $formid;

		if ( empty( $_SESSION[ $session_var ] ) || strtolower( trim( $_REQUEST['mailster__coolcaptcha'] ) ) != $_SESSION[ $session_var ] ) {
			$errors['_coolcaptcha'] = mailster_option( 'coolcaptcha_error_msg' );
		} else {

		}

		return $errors;

	}


}
