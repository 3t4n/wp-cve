<?php
defined( 'ABSPATH' ) || die();
require_once STLSR_PLUGIN_DIR_PATH . 'includes/class-stlsr-helper.php';

$captcha_list = STLSR_Helper::captcha_list();

$steps_url_grecaptcha   = STLSR_Helper::steps_url_grecaptcha();
$steps_url_cf_turnstile = STLSR_Helper::steps_url_cf_turnstile();

$grecaptcha_v2_themes = STLSR_Helper::grecaptcha_v2_themes();
$grecaptcha_v3_scores = STLSR_Helper::grecaptcha_v3_scores();
$grecaptcha_v3_badges = STLSR_Helper::grecaptcha_v3_badges();
$cf_turnstile_themes  = STLSR_Helper::cf_turnstile_themes();
$cf_turnstile_sizes   = STLSR_Helper::cf_turnstile_sizes();

$grecaptcha_v2 = STLSR_Helper::grecaptcha_v2();
$grecaptcha_v3 = STLSR_Helper::grecaptcha_v3();
$cf_turnstile  = STLSR_Helper::cf_turnstile();

$capt_login        = STLSR_Helper::capt_login();
$capt_lostpassword = STLSR_Helper::capt_lostpassword();
$capt_register     = STLSR_Helper::capt_register();
$capt_comment      = STLSR_Helper::capt_comment();

$grecaptcha_v2_enable = false;
$grecaptcha_v3_enable = false;
$cf_turnstile_enable  = false;

if ( $capt_login['enable'] ) {
	if ( 'google_recaptcha_v2' === $capt_login['captcha'] ) {
		$grecaptcha_v2_enable = true;
	}
	if ( 'google_recaptcha_v3' === $capt_login['captcha'] ) {
		$grecaptcha_v3_enable = true;
	}
	if ( 'cf_turnstile' === $capt_login['captcha'] ) {
		$cf_turnstile_enable = true;
	}
}

if ( $capt_lostpassword['enable'] ) {
	if ( ! $grecaptcha_v2_enable && ( 'google_recaptcha_v2' === $capt_lostpassword['captcha'] ) ) {
		$grecaptcha_v2_enable = true;
	}
	if ( ! $grecaptcha_v3_enable && ( 'google_recaptcha_v3' === $capt_lostpassword['captcha'] ) ) {
		$grecaptcha_v3_enable = true;
	}
	if ( ! $cf_turnstile_enable && ( 'cf_turnstile' === $capt_lostpassword['captcha'] ) ) {
		$cf_turnstile_enable = true;
	}
}

if ( $capt_register['enable'] ) {
	if ( ! $grecaptcha_v2_enable && ( 'google_recaptcha_v2' === $capt_register['captcha'] ) ) {
		$grecaptcha_v2_enable = true;
	}
	if ( ! $grecaptcha_v3_enable && ( 'google_recaptcha_v3' === $capt_register['captcha'] ) ) {
		$grecaptcha_v3_enable = true;
	}
	if ( ! $cf_turnstile_enable && ( 'cf_turnstile' === $capt_register['captcha'] ) ) {
		$cf_turnstile_enable = true;
	}
}

if ( $capt_comment['enable'] ) {
	if ( ! $grecaptcha_v2_enable && ( 'google_recaptcha_v2' === $capt_comment['captcha'] ) ) {
		$grecaptcha_v2_enable = true;
	}
	if ( ! $grecaptcha_v3_enable && ( 'google_recaptcha_v3' === $capt_comment['captcha'] ) ) {
		$grecaptcha_v3_enable = true;
	}
	if ( ! $cf_turnstile_enable && ( 'cf_turnstile' === $capt_comment['captcha'] ) ) {
		$cf_turnstile_enable = true;
	}
}

if ( $grecaptcha_v2_enable ) {
	if ( empty( $grecaptcha_v2['site_key'] ) ) {
		?>
	<div class="st-alert-box notice notice-warning is-dismissible">
		<p><?php esc_html_e( 'Please set the the "Site Key" for "Google reCAPTCHA Version 2".', 'login-security-recaptcha' ); ?></p>
	</div>
		<?php
	}
	if ( empty( $grecaptcha_v2['secret_key'] ) ) {
		?>
	<div class="st-alert-box notice notice-warning is-dismissible">
		<p><?php esc_html_e( 'Please set the the "Secret Key" for "Google reCAPTCHA Version 2".', 'login-security-recaptcha' ); ?></p>
	</div>
		<?php
	}
}

if ( $grecaptcha_v3_enable ) {
	if ( empty( $grecaptcha_v3['site_key'] ) ) {
		?>
	<div class="st-alert-box notice notice-warning is-dismissible">
		<p><?php esc_html_e( 'Please set the the "Site Key" for "Google reCAPTCHA Version 3".', 'login-security-recaptcha' ); ?></p>
	</div>
		<?php
	}
	if ( empty( $grecaptcha_v3['secret_key'] ) ) {
		?>
	<div class="st-alert-box notice notice-warning is-dismissible">
		<p><?php esc_html_e( 'Please set the the "Secret Key" for "Google reCAPTCHA Version 3".', 'login-security-recaptcha' ); ?></p>
	</div>
		<?php
	}
}

if ( $cf_turnstile_enable ) {
	if ( empty( $cf_turnstile['site_key'] ) ) {
		?>
	<div class="st-alert-box notice notice-warning is-dismissible">
		<p><?php esc_html_e( 'Please set the the "Site Key" for "Cloudflare Turnstile".', 'login-security-recaptcha' ); ?></p>
	</div>
		<?php
	}
	if ( empty( $cf_turnstile['secret_key'] ) ) {
		?>
	<div class="st-alert-box notice notice-warning is-dismissible">
		<p><?php esc_html_e( 'Please set the the "Secret Key" for "Cloudflare Turnstile".', 'login-security-recaptcha' ); ?></p>
	</div>
		<?php
	}
}
?>

<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" id="stlsr-save-captcha-form">
	<div class="stlsr-side-by-side">

		<div class="stlsr-form">

			<?php $nonce = wp_create_nonce( 'save-captcha' ); ?>
			<input type="hidden" name="save-captcha" value="<?php echo esc_attr( $nonce ); ?>">

			<input type="hidden" name="action" value="stlsr-save-captcha">

			<table class="form-table">
				<tbody>

					<tr>
						<th scope="row"><?php esc_html_e( 'Enable Captcha', 'login-security-recaptcha' ); ?></th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span><?php esc_html_e( 'Enable Captcha at Login Form', 'login-security-recaptcha' ); ?></span>
								</legend>
								<label for="stls_capt_login_enable">
									<input <?php checked( $capt_login['enable'], true, true ); ?> name="capt_login_enable" type="checkbox" id="stls_capt_login_enable" value="1">
									<?php esc_html_e( 'Enable at Login Form', 'login-security-recaptcha' ); ?>
								</label>
							</fieldset>
							<div class="stls_capt_login">
								<select name="capt_login" class="stls_sub_field">
									<?php foreach ( $captcha_list as $key => $value ) { ?>
									<option <?php selected( $capt_login['captcha'], $key, true ); ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
									<?php } ?>
								</select>
								<hr>
							</div>

							<fieldset>
								<legend class="screen-reader-text">
									<span><?php esc_html_e( 'Enable Captcha at Lost Password Form', 'login-security-recaptcha' ); ?></span>
								</legend>
								<label for="stls_capt_lostpassword_enable">
									<input <?php checked( $capt_lostpassword['enable'], true, true ); ?> name="capt_lostpassword_enable" type="checkbox" id="stls_capt_lostpassword_enable" value="1">
									<?php esc_html_e( 'Enable at Lost Password Form', 'login-security-recaptcha' ); ?>
								</label>
							</fieldset>
							<div class="stls_capt_lostpassword">
								<select name="capt_lostpassword" class="stls_sub_field">
									<?php foreach ( $captcha_list as $key => $value ) { ?>
									<option <?php selected( $capt_lostpassword['captcha'], $key, true ); ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
									<?php } ?>
								</select>
								<hr>
							</div>

							<fieldset>
								<legend class="screen-reader-text">
									<span><?php esc_html_e( 'Enable Captcha at Registration Form', 'login-security-recaptcha' ); ?></span>
								</legend>
								<label for="stls_capt_register_enable">
									<input <?php checked( $capt_register['enable'], true, true ); ?> name="capt_register_enable" type="checkbox" id="stls_capt_register_enable" value="1">
									<?php esc_html_e( 'Enable at Registration Form', 'login-security-recaptcha' ); ?>
								</label>
							</fieldset>
							<div class="stls_capt_register">
								<select name="capt_register" class="stls_sub_field">
									<?php foreach ( $captcha_list as $key => $value ) { ?>
									<option <?php selected( $capt_register['captcha'], $key, true ); ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
									<?php } ?>
								</select>
								<hr>
							</div>

							<fieldset>
								<legend class="screen-reader-text">
									<span><?php esc_html_e( 'Enable Captcha at Comment Form', 'login-security-recaptcha' ); ?></span>
								</legend>
								<label for="stls_capt_comment_enable">
									<input <?php checked( $capt_comment['enable'], true, true ); ?> name="capt_comment_enable" type="checkbox" id="stls_capt_comment_enable" value="1">
									<?php esc_html_e( 'Enable at Comment Form', 'login-security-recaptcha' ); ?>
								</label>
							</fieldset>
							<div class="stls_capt_comment">
								<p class="description"><?php esc_html_e( 'Only when user is not logged in.', 'login-security-recaptcha' ); ?></p>
								<select name="capt_comment" class="stls_sub_field stls_sub_field_mr">
									<?php foreach ( $captcha_list as $key => $value ) { ?>
									<option <?php selected( $capt_comment['captcha'], $key, true ); ?> value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
									<?php } ?>
								</select>
								<label for="stls_capt_comment_logged_in" class="stls_sub_field">
									<input <?php checked( $capt_comment['logged_in'], true, true ); ?> name="capt_comment_logged_in" type="checkbox" id="stls_capt_comment_logged_in" value="1">
									<?php esc_html_e( 'Include logged in users.', 'login-security-recaptcha' ); ?>
								</label>
								<hr>
							</div>
						</td>
					</tr>

					<tr>
						<th scope="row"><?php esc_html_e( 'Captcha Settings', 'login-security-recaptcha' ); ?></th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span><?php esc_html_e( 'Set Captcha Settings', 'login-security-recaptcha' ); ?></span>
								</legend>
								<?php
								foreach ( $captcha_list as $key => $value ) {
									?>
								<span class="stls-mr">
									<label>
										<?php reset( $captcha_list ); ?>
										<input <?php checked( ( key( $captcha_list ) === $key ), true, true ); ?> type="radio" name="captcha" value="<?php echo esc_attr( $key ); ?>">
										<span><?php echo esc_html( $value ); ?></span>
									</label>
								</span>
									<?php
								}
								?>
							</fieldset>
						</td>
					</tr>

					<tr class="stls_capt stls_grecaptcha_v2">
						<th scope="row">
							<label for="stls_grecaptcha_v2_site_key"><?php esc_html_e( 'Site Key', 'login-security-recaptcha' ); ?></label>
						</th>
						<td>
							<input name="grecaptcha_v2_site_key" type="text" id="stls_grecaptcha_v2_site_key" value="<?php echo esc_attr( $grecaptcha_v2['site_key'] ); ?>" class="regular-text">
							<p class="description">
								<?php esc_html_e( 'Enter Google reCAPTCHA v2 Site Key.', 'login-security-recaptcha' ); ?>&nbsp;
								<a target="_blank" href="<?php echo esc_url( $steps_url_grecaptcha ); ?>"><?php esc_html_e( 'Click Here', 'login-security-recaptcha' ); ?></a>
							</p>
						</td>
					</tr>

					<tr class="stls_capt stls_grecaptcha_v2">
						<th scope="row">
							<label for="stls_grecaptcha_v2_secret_key"><?php esc_html_e( 'Secret Key', 'login-security-recaptcha' ); ?></label>
						</th>
						<td>
							<input name="grecaptcha_v2_secret_key" type="text" id="stls_grecaptcha_v2_secret_key" value="<?php echo esc_attr( $grecaptcha_v2['secret_key'] ); ?>" class="regular-text">
							<p class="description">
								<?php esc_html_e( 'Enter Google reCAPTCHA v2 Secret Key.', 'login-security-recaptcha' ); ?>&nbsp;
								<a target="_blank" href="<?php echo esc_url( $steps_url_grecaptcha ); ?>"><?php esc_html_e( 'Click Here', 'login-security-recaptcha' ); ?></a>
							</p>
						</td>
					</tr>

					<tr class="stls_capt stls_grecaptcha_v2">
						<th scope="row">
							<label for="stls_grecaptcha_v2_theme"><?php esc_html_e( 'Theme', 'login-security-recaptcha' ); ?></label>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span><?php esc_html_e( 'Select Google reCAPTCHA Version 2 Theme.', 'login-security-recaptcha' ); ?></span>
								</legend>
								<?php
								foreach ( $grecaptcha_v2_themes as $key => $value ) {
									?>
								<label>
									<input <?php checked( $grecaptcha_v2['theme'], $key, true ); ?> type="radio" name="grecaptcha_v2_theme" value="<?php echo esc_attr( $key ); ?>">
									<span><?php echo esc_html( $value ); ?></span>
								</label>
									<?php
									end( $grecaptcha_v2_themes );
									if ( key( $grecaptcha_v2_themes ) !== $key ) {
										echo '<br>';
									}
								}
								?>
							</fieldset>
							<p class="description"><?php esc_html_e( 'Select Google reCAPTCHA Version 2 Theme.', 'login-security-recaptcha' ); ?></p>
						</td>
					</tr>

					<tr class="stls_capt stls_cf_turnstile">
						<th scope="row">
							<label for="stls_cf_turnstile_site_key"><?php esc_html_e( 'Site Key', 'login-security-recaptcha' ); ?></label>
						</th>
						<td>
							<input name="cf_turnstile_site_key" type="text" id="stls_cf_turnstile_site_key" value="<?php echo esc_attr( $cf_turnstile['site_key'] ); ?>" class="regular-text">
							<p class="description">
								<?php esc_html_e( 'Enter Cloudflare Turnstile Site Key.', 'login-security-recaptcha' ); ?>&nbsp;
								<a target="_blank" href="<?php echo esc_url( $steps_url_cf_turnstile ); ?>"><?php esc_html_e( 'Click Here', 'login-security-recaptcha' ); ?></a>
							</p>
						</td>
					</tr>

					<tr class="stls_capt stls_cf_turnstile">
						<th scope="row">
							<label for="stls_cf_turnstile_secret_key"><?php esc_html_e( 'Secret Key', 'login-security-recaptcha' ); ?></label>
						</th>
						<td>
							<input name="cf_turnstile_secret_key" type="text" id="stls_cf_turnstile_secret_key" value="<?php echo esc_attr( $cf_turnstile['secret_key'] ); ?>" class="regular-text">
							<p class="description">
								<?php esc_html_e( 'Enter Cloudflare Turnstile Secret Key.', 'login-security-recaptcha' ); ?>&nbsp;
								<a target="_blank" href="<?php echo esc_url( $steps_url_cf_turnstile ); ?>"><?php esc_html_e( 'Click Here', 'login-security-recaptcha' ); ?></a>
							</p>
						</td>
					</tr>

					<tr class="stls_capt stls_cf_turnstile">
						<th scope="row">
							<label for="stls_cf_turnstile_theme"><?php esc_html_e( 'Theme', 'login-security-recaptcha' ); ?></label>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span><?php esc_html_e( 'Select Cloudflare Turnstile Theme.', 'login-security-recaptcha' ); ?></span>
								</legend>
								<?php
								foreach ( $cf_turnstile_themes as $key => $value ) {
									?>
								<label>
									<input <?php checked( $cf_turnstile['theme'], $key, true ); ?> type="radio" name="cf_turnstile_theme" value="<?php echo esc_attr( $key ); ?>">
									<span><?php echo esc_html( $value ); ?></span>
								</label>
									<?php
									end( $cf_turnstile_themes );
									if ( key( $cf_turnstile_themes ) !== $key ) {
										echo '<br>';
									}
								}
								?>
							</fieldset>
							<p class="description"><?php esc_html_e( 'Select Cloudflare Turnstile Theme.', 'login-security-recaptcha' ); ?></p>
						</td>
					</tr>

					<tr class="stls_capt stls_cf_turnstile">
						<th scope="row">
							<label for="stls_cf_turnstile_size"><?php esc_html_e( 'Size', 'login-security-recaptcha' ); ?></label>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span><?php esc_html_e( 'Select Cloudflare Turnstile Size.', 'login-security-recaptcha' ); ?></span>
								</legend>
								<?php
								foreach ( $cf_turnstile_sizes as $key => $value ) {
									?>
								<label>
									<input <?php checked( $cf_turnstile['size'], $key, true ); ?> type="radio" name="cf_turnstile_size" value="<?php echo esc_attr( $key ); ?>">
									<span><?php echo esc_html( $value ); ?></span>
								</label>
									<?php
									end( $cf_turnstile_sizes );
									if ( key( $cf_turnstile_sizes ) !== $key ) {
										echo '<br>';
									}
								}
								?>
							</fieldset>
							<p class="description"><?php esc_html_e( 'Select Cloudflare Turnstile Size.', 'login-security-recaptcha' ); ?></p>
						</td>
					</tr>

					<tr class="stls_capt stls_cf_turnstile">
						<th scope="row">
							<label for="stls_cf_turnstile_disable_btn"><?php esc_html_e( 'Disable Submit Button', 'login-security-recaptcha' ); ?></label>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span><?php esc_html_e( 'Disable Submit Button', 'login-security-recaptcha' ); ?></span>
								</legend>
								<label for="stls_cf_turnstile_disable_btn">
									<input <?php checked( $cf_turnstile['disable_btn'], true, true ); ?> name="cf_turnstile_disable_btn" type="checkbox" id="stls_cf_turnstile_disable_btn" value="1">
									<?php esc_html_e( 'Disable Submit Button', 'login-security-recaptcha' ); ?>
								</label>
							</fieldset>
							<p class="description"><?php esc_html_e( 'The user will not be able to click on the submit button until the Turnstile challenge is completed.', 'login-security-recaptcha' ); ?></p>
						</td>
					</tr>

					<tr class="stls_capt stls_grecaptcha_v3">
						<th scope="row">
							<label for="stls_grecaptcha_v3_site_key"><?php esc_html_e( 'Site Key', 'login-security-recaptcha' ); ?></label>
						</th>
						<td>
							<input name="grecaptcha_v3_site_key" type="text" id="stls_grecaptcha_v3_site_key" value="<?php echo esc_attr( $grecaptcha_v3['site_key'] ); ?>" class="regular-text">
							<p class="description">
								<?php esc_html_e( 'Enter Google reCAPTCHA v3 Site Key.', 'login-security-recaptcha' ); ?>&nbsp;
								<a target="_blank" href="<?php echo esc_url( $steps_url_grecaptcha ); ?>"><?php esc_html_e( 'Click Here', 'login-security-recaptcha' ); ?></a>
							</p>
						</td>
					</tr>

					<tr class="stls_capt stls_grecaptcha_v3">
						<th scope="row">
							<label for="stls_grecaptcha_v3_secret_key"><?php esc_html_e( 'Secret Key', 'login-security-recaptcha' ); ?></label>
						</th>
						<td>
							<input name="grecaptcha_v3_secret_key" type="text" id="stls_grecaptcha_v3_secret_key" value="<?php echo esc_attr( $grecaptcha_v3['secret_key'] ); ?>" class="regular-text">
							<p class="description">
								<?php esc_html_e( 'Enter Google reCAPTCHA v3 Secret Key.', 'login-security-recaptcha' ); ?>&nbsp;
								<a target="_blank" href="<?php echo esc_url( $steps_url_grecaptcha ); ?>"><?php esc_html_e( 'Click Here', 'login-security-recaptcha' ); ?></a>
							</p>
						</td>
					</tr>

					<tr class="stls_capt stls_grecaptcha_v3">
						<th scope="row">
							<label for="stls_grecaptcha_v3_score"><?php esc_html_e( 'Score', 'login-security-recaptcha' ); ?></label>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span><?php esc_html_e( 'Select Google reCAPTCHA Version 3 Score.', 'login-security-recaptcha' ); ?></span>
								</legend>
								<?php
								foreach ( $grecaptcha_v3_scores as $key => $value ) {
									?>
								<label>
									<input <?php checked( $grecaptcha_v3['score'], $key, true ); ?> type="radio" name="grecaptcha_v3_score" value="<?php echo esc_attr( $key ); ?>">
									<span><?php echo esc_html( $value ); ?></span>
								</label>
									<?php
									end( $grecaptcha_v3_scores );
									if ( key( $grecaptcha_v3_scores ) !== $key ) {
										echo '&nbsp;&nbsp;';
									}
								}
								?>
							</fieldset>
							<p class="description">
								<?php esc_html_e( 'Select Google reCAPTCHA Version 3 Score.', 'login-security-recaptcha' ); ?><br>
								<?php esc_html_e( 'reCAPTCHA v3 returns a score (0.0 is very likely a bot, higher score means better interaction).', 'login-security-recaptcha' ); ?>
							</p>
						</td>
					</tr>

					<tr class="stls_capt stls_grecaptcha_v3">
						<th scope="row">
							<label for="stls_grecaptcha_v3_badge"><?php esc_html_e( 'Badge Position', 'login-security-recaptcha' ); ?></label>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span><?php esc_html_e( 'Select Badge Position for reCAPTCHA Version 3.', 'login-security-recaptcha' ); ?></span>
								</legend>
								<?php
								foreach ( $grecaptcha_v3_badges as $key => $value ) {
									?>
								<label>
									<input <?php checked( $grecaptcha_v3['badge'], $key, true ); ?> type="radio" name="grecaptcha_v3_badge" value="<?php echo esc_attr( $key ); ?>">
									<span><?php echo esc_html( $value ); ?></span>
								</label>
									<?php
									end( $grecaptcha_v3_badges );
									if ( key( $grecaptcha_v3_badges ) !== $key ) {
										echo '&nbsp;&nbsp;';
									}
								}
								?>
							</fieldset>
						</td>
					</tr>

					<tr class="stls_capt stls_grecaptcha_v3">
						<th scope="row">
							<label for="stls_grecaptcha_v3_onaction"><?php esc_html_e( 'Token Generation', 'login-security-recaptcha' ); ?></label>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span><?php esc_html_e( 'Generate Token on Form Submission', 'login-security-recaptcha' ); ?></span>
								</legend>
								<label for="stls_grecaptcha_v3_onaction">
									<input <?php checked( $grecaptcha_v3['onaction'], true, true ); ?> name="grecaptcha_v3_onaction" type="checkbox" id="stls_grecaptcha_v3_onaction" value="1">
									<?php esc_html_e( 'Generate Token on Form Submission', 'login-security-recaptcha' ); ?>
								</label>
							</fieldset>
							<p class="description">
								<?php echo wp_kses( __( 'Generate token only when the user submits the form rather than on page load. <br>You may uncheck this only if you face issues with reCAPTCHA v3.', 'login-security-recaptcha' ), array( 'br' => array() ) ); ?>
							</p>
						</td>
					</tr>

				</tbody>
			</table>

			<button type="submit" class="button button-primary" id="stlsr-save-captcha-btn"><?php esc_html_e( 'Save Changes', 'login-security-recaptcha' ); ?></button>

		</div>

		<?php require_once STLSR_PLUGIN_DIR_PATH . 'admin/inc/partials/pro.php'; ?>

	</div>
</form>

<div class="stlsr-review">
	<div class="stlsr-review-us">
		<a target="_blank" href="https://wordpress.org/support/plugin/login-security-recaptcha/reviews/#new-post" class="stlsr-review-link">
			<span class="stlsr-rate-us">
				<?php esc_html_e( 'Like this plugin? Leave a Review.', 'login-security-recaptcha' ); ?>
			</span>
			<div class="vers column-rating">
				<div class="star-rating">
					<span class="screen-reader-text"><?php esc_html_e( 'Like this plugin? Leave a Review.', 'login-security-recaptcha' ); ?></span>
					<div class="star star-full" aria-hidden="true"></div>
					<div class="star star-full" aria-hidden="true"></div>
					<div class="star star-full" aria-hidden="true"></div>
					<div class="star star-full" aria-hidden="true"></div>
					<div class="star star-full" aria-hidden="true"></div>
				</div>
			</div>
		</a>
	</div>
</div>
