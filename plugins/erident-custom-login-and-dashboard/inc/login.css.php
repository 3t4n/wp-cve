<?php
/**
 * Output login CSS.
 *
 * @package Custom_Login_Dashboard
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Output login CSS.
 *
 * @param array $settings The plugin settings.
 */
return function ( $settings ) {

	$page_bg_color  = isset( $settings['top_bg_color'] ) && ! empty( $settings['top_bg_color'] ) ? $settings['top_bg_color'] : '';
	$page_bg_image  = isset( $settings['top_bg_image'] ) && ! empty( $settings['top_bg_image'] ) ? $settings['top_bg_image'] : '';
	$page_bg_repeat = isset( $settings['top_bg_repeat'] ) && ! empty( $settings['top_bg_repeat'] ) ? $settings['top_bg_repeat'] : '';
	$page_bg_pos_x  = isset( $settings['top_bg_xpos'] ) && ! empty( $settings['top_bg_xpos'] ) ? $settings['top_bg_xpos'] : '';
	$page_bg_pos_y  = isset( $settings['top_bg_ypos'] ) && ! empty( $settings['top_bg_ypos'] ) ? $settings['top_bg_ypos'] : '';
	$page_bg_size   = isset( $settings['top_bg_size'] ) && ! empty( $settings['top_bg_size'] ) ? $settings['top_bg_size'] : '';

	$logo_image  = isset( $settings['dashboard_image_logo'] ) && ! empty( $settings['dashboard_image_logo'] ) ? $settings['dashboard_image_logo'] : '';
	$logo_width  = isset( $settings['dashboard_image_logo_width'] ) && ! empty( $settings['dashboard_image_logo_width'] ) ? $settings['dashboard_image_logo_width'] : '';
	$logo_height = isset( $settings['dashboard_image_logo_height'] ) && ! empty( $settings['dashboard_image_logo_height'] ) ? $settings['dashboard_image_logo_height'] : '';

	$enable_form_box_shadow = isset( $settings['dashboard_check_form_shadow'] ) ? $settings['dashboard_check_form_shadow'] : 0;
	$enable_form_box_shadow = 'yes' === strtolower( $enable_form_box_shadow ) ? 1 : $enable_form_box_shadow;
	$enable_form_box_shadow = 'no' === strtolower( $enable_form_box_shadow ) ? 0 : $enable_form_box_shadow;

	$login_form_box_shadow = '';

	if ( $enable_form_box_shadow ) {
		$login_form_box_shadow = '0 4px 10px -1px ' . $settings['dashboard_form_shadow'];
	}

	$remove_register_link = isset( $settings['dashboard_check_lost_pass'] ) ? $settings['dashboard_check_lost_pass'] : 0;
	$remove_register_link = 'yes' === strtolower( $remove_register_link ) ? 1 : $remove_register_link;
	$remove_register_link = 'no' === strtolower( $remove_register_link ) ? 0 : $remove_register_link;

	$remove_back_to_blog_link = isset( $settings['dashboard_check_backtoblog'] ) ? $settings['dashboard_check_backtoblog'] : 0;
	$remove_back_to_blog_link = 'yes' === strtolower( $remove_back_to_blog_link ) ? 1 : $remove_back_to_blog_link;
	$remove_back_to_blog_link = 'no' === strtolower( $remove_back_to_blog_link ) ? 0 : $remove_back_to_blog_link;

	$btn_text_color = isset( $settings['dashboard_button_text_color'] ) && ! empty( $settings['dashboard_button_text_color'] ) ? $settings['dashboard_button_text_color'] : '';
	$btn_bg_color   = isset( $settings['dashboard_button_color'] ) && ! empty( $settings['dashboard_button_color'] ) ? $settings['dashboard_button_color'] : '';

	$btn_bg_color_hover = '';

	if ( ! empty( $btn_bg_color ) ) {
		$btn_bg_color_hover = ariColor::newColor( $btn_bg_color );
		$btn_bg_color_hover = $btn_bg_color_hover->getNew( 'alpha', 0.9 )->toCSS( 'rgba' );
	}

	$form_width         = isset( $settings['dashboard_login_width'] ) && ! empty( $settings['dashboard_login_width'] ) ? $settings['dashboard_login_width'] : '';
	$form_border_radius = isset( $settings['dashboard_login_radius'] ) && ! empty( $settings['dashboard_login_radius'] ) ? $settings['dashboard_login_radius'] : '';
	$form_border_width  = isset( $settings['dashboard_border_thick'] ) && ! empty( $settings['dashboard_border_thick'] ) ? $settings['dashboard_border_thick'] : '';
	$form_border_style  = isset( $settings['dashboard_login_border'] ) && ! empty( $settings['dashboard_login_border'] ) ? $settings['dashboard_login_border'] : '';
	$form_border_color  = isset( $settings['dashboard_border_color'] ) && ! empty( $settings['dashboard_border_color'] ) ? $settings['dashboard_border_color'] : '';

	$login_form_bg_color = '';

	if ( isset( $settings['dashboard_login_bg'] ) && ! empty( $settings['dashboard_login_bg'] ) ) {
		$login_form_bg_color = $settings['dashboard_login_bg'];

		if ( isset( $settings['dashboard_login_bg_opacity'] ) ) {
			// This `dashboard_login_bg_opacity` won't be used anymore since we use colorpicker alpha now.
			$login_form_bg_opacity = '' !== $settings['dashboard_login_bg_opacity'] ? $settings['dashboard_login_bg_opacity'] : 1; // 0 is allowed here.

			if ( false === stripos( $login_form_bg_color, 'rgba' ) && 1 > $login_form_bg_opacity ) {
				$login_form_bg_color = ariColor::newColor( $login_form_bg_color );
				$login_form_bg_color = $login_form_bg_color->getNew( 'alpha', $login_form_bg_opacity )->toCSS( 'rgba' );
			}
		}
	}

	$login_bg_image  = isset( $settings['login_bg_image'] ) && ! empty( $settings['login_bg_image'] ) ? $settings['login_bg_image'] : '';
	$login_bg_repeat = isset( $settings['login_bg_repeat'] ) && ! empty( $settings['login_bg_repeat'] ) ? $settings['login_bg_repeat'] : '';
	$login_bg_pos_x  = isset( $settings['login_bg_xpos'] ) && ! empty( $settings['login_bg_xpos'] ) ? $settings['login_bg_xpos'] : '';
	$login_bg_pos_y  = isset( $settings['login_bg_ypos'] ) && ! empty( $settings['login_bg_ypos'] ) ? $settings['login_bg_ypos'] : '';

	$label_font_color = isset( $settings['dashboard_text_color'] ) && ! empty( $settings['dashboard_text_color'] ) ? $settings['dashboard_text_color'] : '';
	$label_font_size  = isset( $settings['dashboard_label_text_size'] ) && ! empty( $settings['dashboard_label_text_size'] ) ? $settings['dashboard_label_text_size'] : '';

	$input_font_color = isset( $settings['dashboard_input_text_color'] ) && ! empty( $settings['dashboard_input_text_color'] ) ? $settings['dashboard_input_text_color'] : '';
	$input_font_size  = isset( $settings['dashboard_input_text_size'] ) && ! empty( $settings['dashboard_input_text_size'] ) ? $settings['dashboard_input_text_size'] : '';

	$link_color = isset( $settings['dashboard_link_color'] ) && ! empty( $settings['dashboard_link_color'] ) ? $settings['dashboard_link_color'] : '';

	$enable_link_text_shadow = isset( $settings['dashboard_check_shadow'] ) ? $settings['dashboard_check_shadow'] : 0;
	$enable_link_text_shadow = 'yes' === strtolower( $enable_link_text_shadow ) ? 1 : $enable_link_text_shadow;
	$enable_link_text_shadow = 'no' === strtolower( $enable_link_text_shadow ) ? 0 : $enable_link_text_shadow;

	$login_link_text_shadow = '';

	if ( $enable_link_text_shadow ) {
		$login_link_text_shadow = $settings['dashboard_link_shadow'] . ' 0 1px 0';
	}
	?>

	html {
		background: none !important;
	}

	html body.login {
		<?php if ( ! empty( $page_bg_color ) ) : ?>
			background-color: <?php echo esc_html( $page_bg_color ); ?> !important;
		<?php endif; ?>

		<?php if ( ! empty( $page_bg_image ) ) : ?>
			background-image: url(<?php echo esc_html( $page_bg_image ); ?>) !important;

			<?php if ( ! empty( $page_bg_repeat ) ) : ?>
				background-repeat: <?php echo esc_html( $page_bg_repeat ); ?> !important;
			<?php endif; ?>

			<?php if ( ! empty( $page_bg_pos_x ) && ! empty( $page_bg_pos_y ) ) : ?>
				background-position: <?php echo esc_html( $page_bg_pos_x ); ?> <?php echo esc_html( $page_bg_pos_y ); ?> !important;
			<?php endif; ?>

			<?php if ( ! empty( $page_bg_size ) ) : ?>
				background-size: <?php echo esc_html( $page_bg_size ); ?> !important;
			<?php endif; ?>
		<?php endif; ?>
	}

	body.login div#login h1 a {
		margin: 0 auto;

		<?php if ( ! empty( $logo_image ) ) : ?>
			background-image: url(<?php echo esc_html( $logo_image ); ?>) !important;
		<?php endif; ?>

		<?php if ( ! empty( $logo_width ) || ! empty( $logo_height ) ) : ?>
			<?php if ( ! empty( $logo_width ) && ! empty( $logo_height ) ) : ?>
				background-size: <?php echo esc_html( $logo_width ); ?>px <?php echo esc_html( $logo_height ); ?>px;
			<?php elseif ( ! empty( $logo_width ) && empty( $logo_height ) ) : ?>
				background-size: <?php echo esc_html( $logo_width ); ?>px auto;
			<?php elseif ( empty( $logo_width ) && ! empty( $logo_height ) ) : ?>
				background-size: auto <?php echo esc_html( $logo_height ); ?>px;
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( ! empty( $logo_width ) ) : ?>
			width: <?php echo esc_html( $logo_width ); ?>px;
		<?php endif; ?>

		<?php if ( ! empty( $logo_height ) ) : ?>
			height: <?php echo esc_html( $logo_height ); ?>px;
		<?php endif; ?>
	}

	body.login #login {
		<?php if ( ! empty( $form_width ) ) : ?>
			width: <?php echo esc_html( $form_width ); ?>px;
		<?php endif; ?>
	}

	#loginform {
		<?php if ( ! empty( $form_border_radius ) ) : ?>
			border-radius:<?php echo esc_html( $form_border_radius ); ?>px !important;
		<?php endif; ?>

		<?php if ( ! empty( $form_border_color ) ) : ?>
			border-color: <?php echo esc_html( $form_border_color ); ?> !important;

			<?php if ( ! empty( $form_border_width ) ) : ?>
				border-width: <?php echo esc_html( $form_border_width ); ?>px !important;
			<?php endif; ?>

			<?php if ( ! empty( $form_border_style ) ) : ?>
				border-style: <?php echo esc_html( $form_border_style ); ?> !important;
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( ! empty( $login_form_bg_color ) ) : ?>
			background-color: <?php echo esc_html( $login_form_bg_color ); ?> !important;
		<?php endif; ?>

		<?php if ( ! empty( $login_bg_image ) ) : ?>
			background-image: url(<?php echo esc_html( $login_bg_image ); ?>) !important;

			<?php if ( ! empty( $login_bg_repeat ) ) : ?>
				background-repeat: <?php echo esc_html( $login_bg_repeat ); ?> !important;
			<?php endif; ?>

			<?php if ( ! empty( $login_bg_pos_x ) && ! empty( $login_bg_pos_y ) ) : ?>
				background-position: <?php echo esc_html( $login_bg_pos_x ); ?> <?php echo esc_html( $login_bg_pos_y ); ?> !important;
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( ! empty( $login_form_box_shadow ) ) : ?>
			-moz-box-shadow:    <?php echo esc_html( $login_form_box_shadow ); ?> !important;
			-webkit-box-shadow: <?php echo esc_html( $login_form_box_shadow ); ?> !important;
			box-shadow:         <?php echo esc_html( $login_form_box_shadow ); ?> !important;
		<?php endif; ?>
	}

	body.login div#login form label,
	p#reg_passmail {
		<?php if ( ! empty( $label_font_color ) ) : ?>
			color: <?php echo esc_html( $label_font_color ); ?> !important;
		<?php endif; ?>

		<?php if ( ! empty( $label_font_size ) ) : ?>
			font-size: <?php echo esc_html( $label_font_size ); ?>px !important;
		<?php endif; ?>
	}

	body.login #loginform p.submit .button-primary,
	body.wp-core-ui .button-primary {
		border: none !important;

		<?php if ( ! empty( $btn_text_color ) ) : ?>
			color: <?php echo esc_html( $btn_text_color ); ?> !important;
		<?php endif; ?>

		<?php if ( ! empty( $btn_bg_color ) ) : ?>
			background: <?php echo esc_html( $btn_bg_color ); ?> !important;
		<?php endif; ?>

		<?php if ( ! empty( $login_link_text_shadow ) ) : ?>
			text-shadow: <?php echo esc_html( $login_link_text_shadow ); ?> !important;
		<?php endif; ?>
	}

	body.login #loginform p.submit .button-primary:hover,
	body.login #loginform p.submit .button-primary:focus,
	body.wp-core-ui .button-primary:hover {
		<?php if ( ! empty( $btn_bg_color_hover ) ) : ?>
			background: <?php echo esc_html( $btn_bg_color_hover ); ?> !important;
		<?php endif; ?>
	}

	body.login div#login form .input,
	.login input[type="text"] {
		<?php if ( ! empty( $input_font_color ) ) : ?>
			color: <?php echo esc_html( $input_font_color ); ?> !important;
		<?php endif; ?>

		<?php if ( ! empty( $input_font_size ) ) : ?>
			font-size: <?php echo esc_html( $input_font_size ); ?>px !important;
		<?php endif; ?>
	}

	body.login #nav a, body.login #backtoblog a {
		<?php if ( ! empty( $link_color ) ) : ?>
			color: <?php echo esc_html( $link_color ); ?> !important;
		<?php endif; ?>
	}

	body.login #nav,
	body.login #backtoblog {
		<?php if ( ! empty( $login_link_text_shadow ) ) : ?>
			text-shadow: <?php echo esc_html( $login_link_text_shadow ); ?> !important;
		<?php endif; ?>
	}

	.login form .input,
	.login input[type=text],
	.wp-core-ui .button-primary:focus {
		box-shadow: none !important;
	}

	body.login #loginform p.submit .button-primary,
	body.wp-core-ui .button-primary {
		box-shadow: none;
	}

	body.login p#nav {
		<?php if ( $remove_register_link ) : ?>
			display: none !important;
		<?php endif; ?>
	}

	body.login #backtoblog {
		<?php if ( $remove_back_to_blog_link ) : ?>
			display: none !important;
		<?php endif; ?>
	}

	<?php
};
