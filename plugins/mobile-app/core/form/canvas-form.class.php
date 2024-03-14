<?php

if ( ! defined( 'CANVAS_DIR' ) ) {
	die();
}

class CanvasForm {


	protected $canvas_error_validation;
	protected $canvas_message_notification;
	protected $back_to_login = false;
	/**
	 * @var bool
	 */
	protected $secure_cookie = false;

	/**
	 * Get all element for the form (header,footer,form,error block...)
	 *
	 * @param $template
	 * @param $page_title
	 */
	public function get_form( $template, $page_title ) {
		add_action( 'canvas_login_register_style', array( $this, 'get_style_header' ) );
		add_action( 'canvas_login_register_scripts', array( $this, 'get_script_footer' ) );

		$dir = dirname( __FILE__, 3 ) . '/views/login-registration/';

		$canvas_notices = array(
			'errors' => array(),
			'message' => '',
		);

		$filename = $dir . $template;
		if ( file_exists( $filename ) ) {
			$canvas_page_title = $page_title;
			require_once $dir . '/header.php';
			if ( is_wp_error( $this->canvas_error_validation ) && ! empty( $this->canvas_error_validation->get_error_messages() ) ) {
				$canvas_notices['errors'] = $this->canvas_error_validation->get_error_messages();
			} elseif ( ! empty( $this->canvas_message_notification ) ) {
				$canvas_notices['message'] = $this->canvas_message_notification;
			}

			if ( $this->back_to_login ) {
				$login_url = Canvas::get_option( 'login_url', '/canvas-api/login' );
				?>
				<div class="canvas-form-group canvas-form-forgot-password-message" id="submit-container">
					<p><a class="button-primary canvas-button login" id="wp-link-submit" href="<?php echo $login_url; ?>">Log in now</a></p>
					<div class="spinner-loading hide">
						<?php require CANVAS_DIR . 'views/login-registration/parts/loading-icon.php'; ?>
					</div>
				</div>
				<?php
			} else {
				require_once $filename;
			}
			require_once $dir . '/footer.php';
		}
	}

	/**
	 * Get the style for the form, including the custom style if needed (background color, custom css)
	 */
	public function get_style_header() {
		$this->get_custom_color();
		$custom_css = Canvas::get_option( 'generated-existing-css-template', '' );
		?>
		<link rel="stylesheet" type="text/css" href="<?php echo CANVAS_URL . 'assets/css/login-register-form.css'; ?>">
		<?php
		if ( ! empty( $custom_css ) ) {
			?>
			<style><?php echo stripslashes( $custom_css ); ?></style>
			<?php
		}
	}

	/**
	 * Get script footer for the form
	 */
	public function get_script_footer() {
		?>
		<script src="<?php echo CANVAS_URL . 'assets/libs/onsen/js/onsenui.min.js'; ?>"></script>
		<script src="<?php echo CANVAS_URL . 'assets/js/login-register.js'; ?>"></script>
		<?php
	}

	/**
	 * Get the form logo, using default if it is not defined
	 */
	public static function get_logo() {
		$default_logo_url = CANVAS_URL . 'assets/img/default-logo.svg';
		$custom_logo      = Canvas::get_option( 'login_register_logo' );
		$logo_url         = ! empty( $custom_logo ) ? wp_get_attachment_image_src( $custom_logo, 'full' )[0] : $default_logo_url;
		?>
		<div class="canvas-logo">
			<img src="<?php echo $logo_url; ?>">
		</div>
		<?php
	}

	/**
	 * Get the background color, default if #fff
	 */
	public function get_custom_color() {
		$fields_arr = array(
			'bg_color'           => array(
				'element'       => 'body',
				'attribute'     => 'background-color',
				'default_value' => '#fff',
				'important'     => true,
			),
			'btn_bg_color'       => array(
				'element'       => '#wp-submit,.canvas-login-remember input:checked ~ .checkmark, #wp-link-submit',
				'attribute'     => 'background-color',
				'default_value' => '#9C57C0',
				'important'     => true,
			),
			'btn_bg_hover_color' => array(
				'element'       => '#wp-submit:not(.loading):hover, #wp-link-submit:not(.loading):hover',
				'attribute'     => 'background-color',
				'default_value' => '#9C57C0',
				'important'     => true,
			),
			'btn_text_color'     => array(
				'element'       => '#wp-submit, #wp-link-submit',
				'attribute'     => 'color',
				'default_value' => '#fff',
				'important'     => true,
			),
			'text_color'         => array(
				'element'       => '.canvas-form .canvas-form-group label,.notice-wrapper h3, .notice-wrapper p',
				'attribute'     => 'color',
				'default_value' => '#fff',
				'important'     => true,
			),
			'link_color'         => array(
				'element'       => '.canvas-custom-action a,.back-to-login',
				'attribute'     => 'color',
				'default_value' => '#fff',
				'important'     => true,
			),
			'link_hover_color'   => array(
				'element'       => '.canvas-custom-action a:hover,.back-to-login:hover',
				'attribute'     => 'color',
				'default_value' => '#9C57C0',
				'important'     => true,
			),
			'spinner_color'      => array(
				'elements'      => array(
					array(
						'element'   => '.spinner-ios div, #loading-full-page .spinner-ios div',
						'attribute' => 'background-color',
						'important' => true,
					),
					array(
						'element'   => '.spinner-android .circonf-2',
						'attribute' => 'border-color',
						'important' => false,
					),
				),
				'default_value' => '#9C57C0',
			),
			'logo_max_width'     => array(
				'element'       => '.canvas-logo',
				'attribute'     => 'max-width',
				'default_value' => '150px',
				'important'     => true,
			),
		);
		$styles     = '';
		foreach ( $fields_arr as $key => $option_arr ) {
			$value = Canvas::get_option( 'login_register_' . $key, $option_arr['default_value'] );
			if ( ! isset( $option_arr['elements'] ) ) {
				$important = $option_arr['important'] ? ' !important' : '';
				$styles   .= $option_arr['element'] . '{' . $option_arr['attribute'] . ':' . $value . ' ' . $important . ';}';
			} else {
				foreach ( $option_arr['elements'] as $element ) {
					$important = $element['important'] ? ' !important' : '';
					$styles   .= $element['element'] . '{' . $element['attribute'] . ':' . $value . ' ' . $important . ';}';
				}
			}
		}
		?>
		<style>
			<?php echo stripslashes( $styles ); ?>
		</style>
		<?php
	}

	/**
	 * Get the redirect link after login successful
	 *
	 * @return string|string[]
	 */
	public function get_redirect_link() {
		$redirect_to = esc_url_raw( Canvas::get_option( 'login_register_redirect_url' ) );

		if ( $this->secure_cookie && strstr( $redirect_to, 'wp-admin' ) ) {
			$redirect_to = str_replace( 'http:', 'https:', $redirect_to );
		}

		return empty( $redirect_to ) ? get_home_url() : $redirect_to;
	}
}

new CanvasForm();
