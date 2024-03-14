<?php
error_reporting(0);
wp_register_style( 'acl-er-login-style', false );
wp_enqueue_style( 'acl-er-login-style' );
$css = ' ';
ob_start(); ?>
		<?php echo esc_attr($login_custom_css); ?>
		/* Styles loading for Admin Custome Login */
		html {
			background: none !important;
		}

		<?php
		if ( $top_page['top_bg_type'] == 'static-background-color' ) {
			?>
			html body.login {
			background: <?php echo esc_attr( $top_page['top_color'] ); ?>;
		}

			<?php
		} elseif ( $top_page['top_bg_type'] == 'static-background-image' ) {
			?>
			html body.login {
			<?php
			if ( $top_page['top_cover'] == 'yes' ) {
				?>
				background: url(<?php echo esc_attr( $top_page['top_image'] ); ?>) no-repeat center center fixed;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
				<?php
			} else {
				?>
				background: url(<?php echo esc_attr( $top_page['top_image'] ); ?>) <?php echo esc_attr( $top_page['top_repeat'] ); ?> <?php echo esc_attr( $top_page['top_position'] ); ?> !important;
			background-size: auto !important;
			<?php } ?>
		}

			<?php
		} elseif ( $top_page['top_bg_type'] == 'slider-background' ) {
			?>
			html body.login {
			background: #000;
		}

			<?php
		}
		if ( isset( $logo_page['logo_show'] ) && ( $logo_page['logo_show'] == 'no' ) ) {
			?>
		body.login div#login>h1 {
			display: none;
		}

			<?php
		} else {
			?>

		/* Styles for logo image */
		body.login div#login h1 a {
			background-image: url(<?php echo esc_url( $logo_page['logo_image'] ); ?>) !important;
			padding-bottom: 30px;
			<?php
			if ( $login_form_float == 'center' ) {
				?>
				margin: 0 auto;
			<?php } ?>
					<?php
					if ( $login_form_float == 'left' ) {
						?>
				margin-left: 30px;
					<?php } ?>
					<?php
					if ( $login_form_float == 'right' ) {
						?>
				margin-right: 25px;
					<?php } ?>background-size: <?php echo esc_attr( $logo_page['logo_width'] ); ?>px <?php echo esc_attr( $logo_page['logo_height'] ); ?>px;
			width: <?php echo esc_attr( $logo_page['logo_width'] ); ?>px;
			height: <?php echo esc_attr( $logo_page['logo_height'] ); ?>px;
		}

			<?php
		}
		if ( $login_form_position == 'lf_float_style' ) {

			?>
		#login {
			float: <?php echo esc_attr( $login_form_float ); ?> !important;
			position: relative !important;
			<?php
			if ( $login_form_float == 'left' ) {
				?>
				padding-left: 25px !important;
			<?php } ?>
					<?php
					if ( $login_form_float == 'right' ) {
						?>
				padding-right: 25px !important;
					<?php } ?>
		}

		<?php } ?>
				<?php
				if ( $login_form_position == 'lf_customize_style' ) {
					?>
			#login {
			position: relative !important;
					<?php
					if ( $login_page['login_form_left'] !== '' ) {
						?>
				margin-left: <?php echo esc_attr( $login_page['login_form_left'] ); ?>px !important;
					<?php } ?>
					<?php
					if ( $login_page['login_form_top'] !== '' ) {
						?>
				padding-top: <?php echo esc_attr( $login_page['login_form_top'] ); ?>px !important;
					<?php } ?>
		}

				<?php } ?>body.login #login {
			width: <?php echo esc_attr( $login_page['login_form_width'] ); ?>px;
		}

		.language-switcher {
			display: <?php echo esc_attr( $login_enable_language_switcher ); ?>
		}

		.login form#loginform {
			border-radius: <?php echo esc_attr( $login_page['login_form_radius'] ); ?>px;
			border: <?php echo esc_attr( $login_page['login_border_thikness'] ); ?>px <?php echo esc_attr( $login_page['login_border_style'] ); ?> <?php echo esc_attr( $login_page['login_border_color'] ); ?> !important;
			-moz-box-shadow: <?php echo esc_attr( $login_shadow_color ); ?>;
			-webkit-box-shadow: <?php echo esc_attr( $login_shadow_color ); ?>;
			box-shadow: <?php echo esc_attr( $login_shadow_color ); ?> !important;
			<?php
			if ( $Social_page['enable_social_icon'] == 'inner' || $Social_page['enable_social_icon'] == 'both' ) {
				?>
				padding: 26px 24px 8px;
			<?php } ?>position: relative;
			z-index: 1;
			/* for ie */
			background-color: rgb(<?php echo esc_attr( $loginbg['red'] ); ?>, <?php echo esc_attr( $loginbg['green'] ); ?>, <?php echo esc_attr( $loginbg['blue'] ); ?>) !important;
			background: url(<?php echo esc_attr( $login_page['login_bg_image'] ); ?>) <?php echo esc_attr( $login_page['login_bg_repeat'] ); ?> <?php echo esc_attr( $login_page['login_bg_position'] ); ?>;
			background: rgba(<?php echo esc_attr( $loginbg['red'] ); ?>, <?php echo esc_attr( $loginbg['green'] ); ?>, <?php echo esc_attr( $loginbg['blue'] ); ?>, <?php echo esc_attr( $login_form_opacity ); ?>) !important;
			<?php
			if ( $login_page['login_bg_type'] == 'static-background-image' ) {
				?>
				background: url('<?php echo esc_url( WEBLIZAR_NALF_PLUGIN_URL . 'css/img/' . $login_page['login_bg_effect'] . '.png' ); ?>') repeat scroll left top, url(<?php echo esc_attr( $login_page['login_bg_image'] ); ?>) <?php echo esc_attr( $login_page['login_bg_repeat'] ); ?> <?php echo esc_attr( $login_page['login_bg_position'] ); ?> !important;
			<?php } ?>
		}

		.icon-ph {
			color: <?php echo esc_attr( $text_and_color_page['input_font_color'] ); ?>;
		}

		body.login div#login form p label,
		body.login div#login form #lable_password_input label,
		body.login div#login form #lable_login_input,
		body.login div#login form .user-pass-wrap,
		body.login div#login form #forgetmenot_input,
		body.login div#login form #submit_input {
			color: <?php echo esc_attr( $text_and_color_page['heading_font_color'] ); ?> !important;
			font-size: <?php echo esc_attr( $text_and_color_page['heading_font_size'] ); ?>px !important;
			font-family: <?php echo esc_attr( $text_and_color_page['heading_font_style'] ); ?> !important;
		}

		#reg_passmail {
			color: <?php echo esc_attr( $text_and_color_page['heading_font_color'] ); ?> !important;
			font-size: <?php echo esc_attr( $text_and_color_page['heading_font_size'] ); ?>px !important;
			font-family: <?php echo esc_attr( $text_and_color_page['heading_font_style'] ); ?> !important;
		}

		body.login #loginform p.submit .button-primary,
		body.wp-core-ui .button-primary {
			background: <?php echo esc_attr( $text_and_color_page['button_color'] ); ?>;
			color: <?php echo isset( $text_and_color_page['login_button_font_color'] ) ? esc_attr( $text_and_color_page['login_button_font_color'] ): NULL; ?> !important;
			font-size: <?php echo isset( $text_and_color_page['button_font_size'] ) ? esc_attr( $text_and_color_page['button_font_size'] ) : NULL; ?>px;
			border: none !important;
			font-family: <?php echo isset( $text_and_color_page['button_font_style'] ) ? esc_attr( $text_and_color_page['button_font_style'] ) : NULL; ?>;
		}

		body.login #loginform p.submit .button-primary:hover,
		body.login #loginform p.submit .button-primary:focus,
		body.wp-core-ui .button-primary:hover {
			background: rgba(<?php echo esc_attr( $btnrgba['red'] ); ?>, <?php echo esc_attr( $btnrgba['green'] ); ?>, <?php echo esc_attr( $btnrgba['blue'] ); ?>, 0.9) ;
			/*color: rgba(<?php echo esc_attr( $btnrgba['red'] ); ?>,<?php echo esc_attr( $btnrgba['green'] ); ?>,<?php echo esc_attr( $btnrgba['blue'] ); ?>, 0.9) !important;*/
		}

		body.login div#login form .input,
		.login input[type="text"] {
			color: <?php echo esc_attr( $text_and_color_page['input_font_color'] ); ?>;
			font-size: <?php echo esc_attr( $text_and_color_page['input_font_size'] ); ?>px;
			font-family: <?php echo esc_attr( $text_and_color_page['input_font_style'] ); ?>;
			-webkit-box-shadow: 0 0 0px 1000px white inset;
			-webkit-text-fill-color: <?php echo esc_attr( $text_and_color_page['input_font_color'] ); ?> !important;
		}

		p.message.register {
			display: none;
		}

		<?php
		if ( isset( $text_and_color_page['show_remember_me_field'] ) && ( $text_and_color_page['show_remember_me_field'] == 'no' ) ) {
			?>
		p.forgetmenot {
			display: none;
		}

		<?php } ?>
				<?php
				if ( isset( $text_and_color_page['show_back_to_site_link'] ) && ( $text_and_color_page['show_back_to_site_link'] == 'no' ) ) {
					?>
					p#backtoblog>a {
			display: none;
		}

				<?php } ?>
				<?php
				if ( isset( $text_and_color_page['show_copyright_link_text'] ) && ( $text_and_color_page['show_copyright_link_text'] == 'no' ) ) {
					?>
					.divfooter {
			display: none;
		}

				<?php } ?>body.login #nav a,
		body.login #backtoblog a {
			color: <?php echo esc_attr( $text_and_color_page['link_color'] ); ?> !important;
			font-family: <?php echo esc_attr( $text_and_color_page['link_font_style'] ); ?> !important;
			font-size: <?php echo esc_attr( $text_and_color_page['link_size'] ); ?>px !important;
		}

		<?php
		if ( $login_form_float == 'right' ) {
			?>
			.login #nav {
			margin: 29px 0 60px;
		}

		body.login #nav a {
			float: right;
		}

		body.login #backtoblog a {
			float: left;
		}

		<?php } ?>.divfooter {
			color: #fff;
			padding-top: 10px !important;
			text-align: center;
			width: 335px;
		}

		.divfooter a,
		body.login #backtoblog .divfooter {
			color: <?php echo esc_attr( isset( $login_page['login_tagline_text_color'] ) ? $login_page['login_tagline_text_color'] : '#ffffff' ); ?> !important;
			text-shadow: none;
		}

		.divfooter a,
		body.login #backtoblog .divfooter a {
			color: <?php echo esc_attr( isset( $login_page['login_tagline_link_color'] ) ? $login_page['login_tagline_link_color'] : '#f00' ); ?> !important;
			text-shadow: none;
		}

		.login-msg-above {
			background: #ffffff9e;
			padding: 12px !important;
			margin: 4px 0px !important;
			border-bottom: 3px solid #fff;
			border-radius: 25px;
			line-height: 1.4;
			box-shadow: 0 1px 1px 0 hsla(0, 0%, 0%, 0.1);
			<?php if ( $login_page['login_msg_fontsize'] !== '' ) { ?>
			font-size: <?php echo esc_attr( $login_page['login_msg_fontsize'] ); ?>px !important;
			<?php } ?>
			text-align: center;
			font-weight: 500;
			color:
			<?php
			if ( isset( $login_page['login_msg_font_color'] ) ) {
				echo esc_attr( $login_page['login_msg_font_color'] );
			} else {
				echo esc_attr( '#000' );
			}
			?>  !important;
		}
<?php
$css .= ob_get_clean();
wp_add_inline_style( 'acl-er-login-style', $css );
