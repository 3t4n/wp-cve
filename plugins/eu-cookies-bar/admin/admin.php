<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class EU_COOKIES_BAR_Admin_Admin
 */
class EU_COOKIES_BAR_Admin_Admin {
	protected $settings;

	function __construct() {
		$this->settings = EU_COOKIES_BAR_Data::get_instance();
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'save_settings' ) );
		add_filter(
			'plugin_action_links_eu-cookies-bar/eu-cookies-bar.php', array(
				$this,
				'settings_link'
			)
		);
	}

	public function init() {
		$this->load_plugin_textdomain();
		if ( class_exists( 'VillaTheme_Support' ) ) {
			new VillaTheme_Support(
				array(
					'support'    => 'https://wordpress.org/support/plugin/eu-cookies-bar/',
					'docs'       => 'http://docs.villatheme.com/?item=eu-cookies-bar',
					'review'     => 'https://wordpress.org/support/plugin/eu-cookies-bar/reviews/?rate=5#rate-response',
					'pro_url'    => '',
					'css'        => EU_COOKIES_BAR_CSS,
					'image'      => EU_COOKIES_BAR_IMAGES,
					'slug'       => 'eu-cookies-bar',
					'menu_slug'  => 'eu-cookies-bar',
					'survey_url' => 'https://script.google.com/macros/s/AKfycbwgvONHJpnabMILVR5w6nIWf4sTG9eJB-Om1f7S4G3osKcDxRFD44B2Fsai4SnRuYzjyg/exec',
					'version'    => EU_COOKIES_BAR_VERSION
				)
			);
		}
	}

	public function settings_link( $links ) {
		$settings_link = '<a href="admin.php?page=eu-cookies-bar" title="' . __( 'Settings', 'eu-cookies-bar' ) . '">' . __( 'Settings', 'eu-cookies-bar' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}

	public function admin_menu() {
		add_menu_page( __( 'WordPress EU Cookies', 'eu-cookies-bar' ), __( 'Cookies bar', 'eu-cookies-bar' ), 'manage_options', 'eu-cookies-bar', array(
			$this,
			'settings'
		), '', 2 );

	}

	public function settings() {
		?>
        <div class="wrap">
            <h2><?php esc_html_e( 'WordPress EU Cookies', 'eu-cookies-bar' ); ?></h2>
            <form class="vi-ui form" method="POST">
				<?php wp_nonce_field( 'eu_cookies_bar_settings_page_save', 'eu_cookies_bar_nonce_field' ); ?>
                <div class="vi-ui top attached tabular menu">
                    <div class="item active"
                         data-tab="general"><?php esc_html_e( 'General', 'eu-cookies-bar' ); ?></div>
                    <div class="item"
                         data-tab="cookies_bar"><?php esc_html_e( 'Cookies bar', 'eu-cookies-bar' ); ?></div>
                    <div class="item"
                         data-tab="user_cookies_settings"><?php esc_html_e( 'Users cookies settings', 'eu-cookies-bar' ); ?></div>
                </div>
                <div class="vi-ui bottom attached active tab segment" data-tab="general">
                    <div class="two fields">
                        <div class="field">
                            <label for="eu_cookies_bar_enable"><?php esc_html_e( 'Enable', 'eu-cookies-bar' ); ?></label>
                            <div class="vi-ui toggle checkbox">
                                <input type="checkbox" name="eu_cookies_bar_enable" value="1"
                                       id="eu_cookies_bar_enable" <?php checked( $this->settings->get_params( 'enable' ), '1' ); ?>><label></label>
                            </div>
                        </div>
                        <div class="field">
                            <label for="eu_cookies_bar_block_until_accept"><?php esc_html_e( 'Block cookies until accepting', 'eu-cookies-bar' ); ?></label>
                            <div class="vi-ui toggle checkbox"
                                 data-tooltip="<?php esc_attr_e( 'Block all cookies(except strictly necessary cookies) until the visitor accepts to use.', 'eu-cookies-bar' ) ?>"
                                 data-position="right center" data-variation="wide">
                                <input type="checkbox" name="eu_cookies_bar_block_until_accept" value="1"
                                       id="eu_cookies_bar_block_until_accept" <?php checked( $this->settings->get_params( 'block_until_accept' ), '1' ); ?>><label></label>
                            </div>
                        </div>
                        <div class="field">
                            <label for="eu_cookies_bar_expire"><?php esc_html_e( 'Agreement expires after(days)', 'eu-cookies-bar' ); ?></label>
                            <input type="number" name="eu_cookies_bar_expire" id="eu_cookies_bar_expire" min="1"
                                   max="360"
                                   value="<?php echo esc_attr( $this->settings->get_params( 'expire' ) ); ?>">
                        </div>
                    </div>
                    <div class="field">
                        <label for="eu_cookies_bar_strictly_necessary"><?php esc_html_e( 'Strictly necessary cookies', 'eu-cookies-bar' ); ?></label>
                        <div class="vi-ui input"
                             data-tooltip="<?php esc_attr_e( 'These are very important cookies that users can not disable them. Enter cookies names separated with commas(,)', 'eu-cookies-bar' ) ?>"
                             data-position="top center" data-variation="wide">
                            <input type="text" name="eu_cookies_bar_strictly_necessary"
                                   id="eu_cookies_bar_strictly_necessary"
                                   value="<?php echo esc_attr( $this->settings->get_params( 'strictly_necessary' ) ); ?>">
                        </div>
                    </div>
                    <div class="field">
                        <label for="eu_cookies_bar_strictly_necessary_family"><?php esc_html_e( 'Strictly necessary cookies prefix', 'eu-cookies-bar' ); ?></label>
                        <div class="vi-ui input"
                             data-tooltip="<?php esc_attr_e( 'Cookies name started with one of these cannot be disabled. Enter cookies names separated with commas(,)', 'eu-cookies-bar' ) ?>"
                             data-position="top center">
                            <input type="text" name="eu_cookies_bar_strictly_necessary_family"
                                   id="eu_cookies_bar_strictly_necessary_family"
                                   value="<?php echo esc_attr( $this->settings->get_params( 'strictly_necessary_family' ) ); ?>">
                        </div>
                    </div>
                    <div class="field">
                        <label for="eu_cookies_bar_privacy_policy_url"><?php esc_html_e( 'Privacy page url', 'eu-cookies-bar' ); ?></label>
                        <input type="text" name="eu_cookies_bar_privacy_policy_url"
                               id="eu_cookies_bar_privacy_policy_url"
                               value="<?php echo esc_attr( $this->settings->get_params( 'privacy_policy_url' ) ? htmlentities( $this->settings->get_params( 'privacy_policy_url' ) ) : ( get_option( 'wp_page_for_privacy_policy', '' ) ? htmlentities( get_page_link( (int) get_option( 'wp_page_for_privacy_policy', '' ) ) ) : '' ) ) ?>">
                    </div>
                    <div class="field">
                        <label for="eu_cookies_bar_privacy_policy"><?php esc_html_e( 'Your privacy policy', 'eu-cookies-bar' ); ?></label>
						<?php wp_editor( $this->settings->get_params( 'privacy_policy' ), 'eu_cookies_bar_privacy_policy' ); ?>
                    </div>
                </div>
                <div class="vi-ui bottom attached tab segment" data-tab="cookies_bar">

                    <div class="field">
                        <label for="eu_cookies_bar_cookies_bar_message"><?php esc_html_e( 'Message', 'eu-cookies-bar' ); ?></label>
						<?php
						$cookies_bar_message = $this->settings->get_params( 'cookies_bar_message' );
						wp_editor( $cookies_bar_message, 'eu_cookies_bar_cookies_bar_message' ); ?>
                    </div>
                    <div class="equal width fields">
                        <div class="field">

                            <div class="vi-ui toggle checkbox">
                                <input type="checkbox" name="eu_cookies_bar_cookies_bar_show_button_accept"
                                       value="1" <?php checked( $this->settings->get_params( 'cookies_bar_show_button_accept' ), '1' ); ?>
                                       id="eu_cookies_bar_cookies_bar_show_button_accept"><label
                                        for="eu_cookies_bar_cookies_bar_show_button_accept"><?php esc_html_e( 'Show "accept" button', 'eu-cookies-bar' ); ?></label>
                            </div>
                        </div>
                        <div class="field">

                            <div class="vi-ui toggle checkbox"
                                 data-tooltip="<?php esc_attr_e( 'Enable to make it GDPR compliant.', 'eu-cookies-bar' ) ?>"
                                 data-position="top center">
                                <input type="checkbox" name="eu_cookies_bar_cookies_bar_show_button_decline"
                                       value="1" <?php checked( $this->settings->get_params( 'cookies_bar_show_button_decline' ), '1' ); ?>
                                       id="eu_cookies_bar_cookies_bar_show_button_decline"><label
                                        for="eu_cookies_bar_cookies_bar_show_button_decline"><?php esc_html_e( 'Show "decline" button', 'eu-cookies-bar' ); ?></label>
                            </div>
                        </div>
                        <div class="field">

                            <div class="vi-ui toggle checkbox">
                                <input type="checkbox" name="eu_cookies_bar_cookies_bar_show_button_close"
                                       value="1" <?php checked( $this->settings->get_params( 'cookies_bar_show_button_close' ), '1' ); ?>
                                       id="eu_cookies_bar_cookies_bar_show_button_close"><label
                                        for="eu_cookies_bar_cookies_bar_show_button_close"><?php esc_html_e( 'Show "close" button', 'eu-cookies-bar' ); ?></label>
                            </div>
                        </div>
                    </div>
                    <h4 class="vi-ui dividing header">
                        <label><?php esc_html_e( 'Cookies bar position', 'eu-cookies-bar' ) ?></label></h4>
                    <div class="equal width fields">
                        <div class="field">
                            <label for="eu_cookies_bar_cookies_bar_position_top">
                                <div class="eu-cookies-bar-browser-mockup <?php if ( $this->settings->get_params( 'cookies_bar_position' ) == 'top' )
									echo esc_attr( 'eu-cookies-bar-browser-mockup-selected' ) ?>">
                                    <div class="eu-cookies-bar-browser-mockup-cookies-bar eu-cookies-bar-browser-mockup-cookies-bar-top">
                                    </div>
                                </div>
                            </label>
                            <div class="field eu-cookies-bar-browser-mockup-checkbox">
                                <div class="vi-ui fluid toggle checkbox">
                                    <input type="radio" name="eu_cookies_bar_cookies_bar_position"
                                           id="eu_cookies_bar_cookies_bar_position_top"
                                           value="top" <?php checked( $this->settings->get_params( 'cookies_bar_position' ), 'top' ) ?>><label><?php esc_html_e( 'Top', 'eu-cookies-bar' ) ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label for="eu_cookies_bar_cookies_bar_position_bottom">
                                <div class="eu-cookies-bar-browser-mockup <?php if ( $this->settings->get_params( 'cookies_bar_position' ) == 'bottom' )
									echo esc_attr( 'eu-cookies-bar-browser-mockup-selected' ) ?>">
                                    <div class="eu-cookies-bar-browser-mockup-cookies-bar eu-cookies-bar-browser-mockup-cookies-bar-bottom">
                                    </div>
                                </div>
                            </label>
                            <div class="field eu-cookies-bar-browser-mockup-checkbox">
                                <div class="vi-ui fluid toggle checkbox">
                                    <input type="radio" name="eu_cookies_bar_cookies_bar_position"
                                           id="eu_cookies_bar_cookies_bar_position_bottom"
                                           value="bottom" <?php checked( $this->settings->get_params( 'cookies_bar_position' ), 'bottom' ) ?>><label><?php esc_html_e( 'Bottom', 'eu-cookies-bar' ) ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label for="eu_cookies_bar_cookies_bar_position_bottom_left">
                                <div class="eu-cookies-bar-browser-mockup <?php if ( $this->settings->get_params( 'cookies_bar_position' ) == 'bottom_left' )
									echo esc_attr( 'eu-cookies-bar-browser-mockup-selected' ) ?>">
                                    <div class="eu-cookies-bar-browser-mockup-cookies-bar eu-cookies-bar-browser-mockup-cookies-bar-bottom-left">
                                    </div>
                                </div>
                            </label>
                            <div class="field eu-cookies-bar-browser-mockup-checkbox">
                                <div class="vi-ui fluid toggle checkbox">
                                    <input type="radio" name="eu_cookies_bar_cookies_bar_position"
                                           id="eu_cookies_bar_cookies_bar_position_bottom_left"
                                           value="bottom_left" <?php checked( $this->settings->get_params( 'cookies_bar_position' ), 'bottom_left' ) ?>><label><?php esc_html_e( 'Bottom left', 'eu-cookies-bar' ) ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label for="eu_cookies_bar_cookies_bar_position_bottom_right">
                                <div class="eu-cookies-bar-browser-mockup <?php if ( $this->settings->get_params( 'cookies_bar_position' ) == 'bottom_right' )
									echo esc_attr( 'eu-cookies-bar-browser-mockup-selected' ) ?>">
                                    <div class="eu-cookies-bar-browser-mockup-cookies-bar eu-cookies-bar-browser-mockup-cookies-bar-bottom-right">
                                    </div>
                                </div>
                            </label>
                            <div class="field eu-cookies-bar-browser-mockup-checkbox">
                                <div class="vi-ui fluid toggle checkbox">
                                    <input type="radio" name="eu_cookies_bar_cookies_bar_position"
                                           id="eu_cookies_bar_cookies_bar_position_bottom_right"
                                           value="bottom_right" <?php checked( $this->settings->get_params( 'cookies_bar_position' ), 'bottom_right' ) ?>><label><?php esc_html_e( 'Bottom right', 'eu-cookies-bar' ) ?></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="vi-ui dividing header">
                        <label><?php esc_html_e( 'Implicit behaviors', 'eu-cookies-bar' ); ?></label>
                    </h4>
                    <div class="equal width fields">

                        <div class="field">
                            <label for="eu_cookies_bar_cookies_bar_on_close"><?php esc_html_e( 'Hit "close" button', 'eu-cookies-bar' ); ?></label>
                            <div class="vi-ui input"
                                 data-tooltip="<?php esc_attr_e( 'Select "Just close" to make it GDPR compliant.', 'eu-cookies-bar' ) ?>"
                                 data-position="top center">
                                <select name="eu_cookies_bar_cookies_bar_on_close"
                                        id="eu_cookies_bar_cookies_bar_on_close"
                                        class="vi-ui fluid dropdown">
                                    <option value="yes" <?php selected( $this->settings->get_params( 'cookies_bar_on_close' ), 'yes' ) ?>><?php esc_html_e( 'Agree and close', 'eu-cookies-bar' ); ?></option>
                                    <option value="no" <?php selected( $this->settings->get_params( 'cookies_bar_on_close' ), 'no' ) ?>><?php esc_html_e( 'Decline and close', 'eu-cookies-bar' ); ?></option>
                                    <option value="none" <?php selected( $this->settings->get_params( 'cookies_bar_on_close' ), 'none' ) ?>><?php esc_html_e( 'Just close', 'eu-cookies-bar' ); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="field">
                            <label for="eu_cookies_bar_cookies_bar_on_scroll"><?php esc_html_e( 'Scroll mouse', 'eu-cookies-bar' ); ?></label>
                            <div class="vi-ui input"
                                 data-tooltip="<?php esc_attr_e( 'Select "Do nothing" to make it GDPR compliant.', 'eu-cookies-bar' ) ?>"
                                 data-position="top center">
                                <select name="eu_cookies_bar_cookies_bar_on_scroll"
                                        id="eu_cookies_bar_cookies_bar_on_scroll"
                                        class="vi-ui fluid dropdown">
                                    <option value="yes" <?php selected( $this->settings->get_params( 'cookies_bar_on_scroll' ), 'yes' ) ?>><?php esc_html_e( 'Agree and hide bar', 'eu-cookies-bar' ); ?></option>
                                    <option value="no" <?php selected( $this->settings->get_params( 'cookies_bar_on_scroll' ), 'no' ) ?>><?php esc_html_e( 'Decline and hide bar', 'eu-cookies-bar' ); ?></option>
                                    <option value="none" <?php selected( $this->settings->get_params( 'cookies_bar_on_scroll' ), 'none' ) ?>><?php esc_html_e( 'Do nothing', 'eu-cookies-bar' ); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="field">
                            <label for="eu_cookies_bar_cookies_bar_on_page_redirect"><?php esc_html_e( 'Refresh/go to other page', 'eu-cookies-bar' ); ?></label>
                            <div class="vi-ui input"
                                 data-tooltip="<?php esc_attr_e( 'Select "Do nothing" to make it GDPR compliant.', 'eu-cookies-bar' ) ?>"
                                 data-position="top center">
                                <select name="eu_cookies_bar_cookies_bar_on_page_redirect"
                                        id="eu_cookies_bar_cookies_bar_on_page_redirect"
                                        class="vi-ui fluid dropdown">
                                    <option value="yes" <?php selected( $this->settings->get_params( 'cookies_bar_on_page_redirect' ), 'yes' ) ?>><?php esc_html_e( 'Agree', 'eu-cookies-bar' ); ?></option>
                                    <option value="no" <?php selected( $this->settings->get_params( 'cookies_bar_on_page_redirect' ), 'no' ) ?>><?php esc_html_e( 'Decline', 'eu-cookies-bar' ); ?></option>
                                    <option value="none" <?php selected( $this->settings->get_params( 'cookies_bar_on_page_redirect' ), 'none' ) ?>><?php esc_html_e( 'Do nothing', 'eu-cookies-bar' ); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <h4 class="vi-ui dividing header">
                        <label><?php esc_html_e( 'Cookies bar design', 'eu-cookies-bar' ); ?></label>
                    </h4>
                    <div class="equal width fields">
                        <div class="field">
                            <label for="eu_cookies_bar_cookies_bar_border_radius"><?php esc_html_e( 'Border radius(px)', 'eu-cookies-bar' ); ?></label>
                            <input type="number" name="eu_cookies_bar_cookies_bar_border_radius"
                                   id="eu_cookies_bar_cookies_bar_border_radius" min="0"
                                   value="<?php echo esc_attr( $this->settings->get_params( 'cookies_bar_border_radius' ) ); ?>">
                        </div>
                        <div class="field">
                            <label for="eu_cookies_bar_cookies_bar_margin"><?php esc_html_e( 'Margin(px)', 'eu-cookies-bar' ); ?></label>
                            <input type="number" name="eu_cookies_bar_cookies_bar_margin"
                                   id="eu_cookies_bar_cookies_bar_margin" min="0"
                                   value="<?php echo esc_attr( $this->settings->get_params( 'cookies_bar_margin' ) ); ?>">
                        </div>
                        <div class="field">
                            <label for="eu_cookies_bar_cookies_bar_padding"><?php esc_html_e( 'Padding(px)', 'eu-cookies-bar' ); ?></label>
                            <input type="number" name="eu_cookies_bar_cookies_bar_padding"
                                   id="eu_cookies_bar_cookies_bar_padding" min="0"
                                   value="<?php echo esc_attr( $this->settings->get_params( 'cookies_bar_padding' ) ); ?>">
                        </div>
                        <div class="field">
                            <label for="eu_cookies_bar_cookies_bar_font_size"><?php esc_html_e( 'Font size(px)', 'eu-cookies-bar' ); ?></label>
                            <input type="number" name="eu_cookies_bar_cookies_bar_font_size"
                                   id="eu_cookies_bar_cookies_bar_font_size" min="8"
                                   max="18"
                                   value="<?php echo esc_attr( $this->settings->get_params( 'cookies_bar_font_size' ) ); ?>">
                        </div>
                    </div>
                    <div class="equal width fields">
                        <div class="field">
                            <label for="eu_cookies_bar_cookies_bar_color"><?php esc_html_e( 'Text color', 'eu-cookies-bar' ); ?></label>
                            <input type="text" class="color-picker" name="eu_cookies_bar_cookies_bar_color"
                                   id="eu_cookies_bar_cookies_bar_color"
                                   style="<?php if ( $this->settings->get_params( 'cookies_bar_color' ) ) {
								       echo esc_attr( 'background:' . $this->settings->get_params( 'cookies_bar_color' ) );
							       } ?>"
                                   value="<?php echo esc_attr( $this->settings->get_params( 'cookies_bar_color' ) ); ?>">
                        </div>
                        <div class="field">
                            <label for="eu_cookies_bar_cookies_bar_bg_color"><?php esc_html_e( 'Background color', 'eu-cookies-bar' ); ?></label>
                            <input type="text" class="color-picker" name="eu_cookies_bar_cookies_bar_bg_color"
                                   id="eu_cookies_bar_cookies_bar_bg_color"
                                   style="<?php if ( $this->settings->get_params( 'cookies_bar_bg_color' ) ) {
								       echo esc_attr( 'background:' . $this->settings->get_params( 'cookies_bar_bg_color' ) );
							       } ?>"
                                   value="<?php echo esc_attr( $this->settings->get_params( 'cookies_bar_bg_color' ) ); ?>">
                        </div>
                        <div class="field">
                            <label for="eu_cookies_bar_cookies_bar_opacity"><?php esc_html_e( 'Opacity', 'eu-cookies-bar' ); ?></label>
                            <input type="number" name="eu_cookies_bar_cookies_bar_opacity"
                                   id="eu_cookies_bar_cookies_bar_opacity" min="0"
                                   max="1" step="0.1"
                                   value="<?php echo esc_attr( $this->settings->get_params( 'cookies_bar_opacity' ) ); ?>">
                        </div>


                    </div>
                    <h4 class="vi-ui dividing header">
                        <label><?php esc_html_e( '"Accept" button design', 'eu-cookies-bar' ); ?></label>
                    </h4>
                    <div class="equal width fields">

                        <div class="field">
                            <label for="eu_cookies_bar_cookies_bar_button_accept_title"><?php esc_html_e( 'Title', 'eu-cookies-bar' ); ?></label>
                            <input type="text" name="eu_cookies_bar_cookies_bar_button_accept_title"
                                   id="eu_cookies_bar_cookies_bar_button_accept_title"
                                   value="<?php echo esc_attr( htmlentities( $this->settings->get_params( 'cookies_bar_button_accept_title' ) ) ); ?>">
                        </div>
                        <div class="field">
                            <label for="eu_cookies_bar_cookies_bar_button_accept_color"><?php esc_html_e( 'Text color', 'eu-cookies-bar' ); ?></label>
                            <input type="text" class="color-picker"
                                   name="eu_cookies_bar_cookies_bar_button_accept_color"
                                   id="eu_cookies_bar_cookies_bar_button_accept_color"
                                   style="<?php if ( $this->settings->get_params( 'cookies_bar_button_accept_color' ) ) {
								       echo esc_attr( 'background:' . $this->settings->get_params( 'cookies_bar_button_accept_color' ) );
							       } ?>"
                                   value="<?php echo esc_attr( $this->settings->get_params( 'cookies_bar_button_accept_color' ) ); ?>">
                        </div>
                        <div class="field">
                            <label for="eu_cookies_bar_cookies_bar_button_accept_bg_color"><?php esc_html_e( 'Background color', 'eu-cookies-bar' ); ?></label>
                            <input type="text" class="color-picker"
                                   name="eu_cookies_bar_cookies_bar_button_accept_bg_color"
                                   id="eu_cookies_bar_cookies_bar_button_accept_bg_color"
                                   style="<?php if ( $this->settings->get_params( 'cookies_bar_button_accept_bg_color' ) ) {
								       echo esc_attr( 'background:' . $this->settings->get_params( 'cookies_bar_button_accept_bg_color' ) );
							       } ?>"
                                   value="<?php echo esc_attr( $this->settings->get_params( 'cookies_bar_button_accept_bg_color' ) ); ?>">
                        </div>
                        <div class="field">
                            <label for="eu_cookies_bar_cookies_bar_button_accept_border_radius"><?php esc_html_e( 'Border radius(px)', 'eu-cookies-bar' ); ?></label>
                            <input type="number" name="eu_cookies_bar_cookies_bar_button_accept_border_radius"
                                   id="eu_cookies_bar_cookies_bar_button_accept_border_radius" min="0"
                                   value="<?php echo esc_attr( $this->settings->get_params( 'cookies_bar_button_accept_border_radius' ) ); ?>">
                        </div>
                    </div>

                    <h4 class="vi-ui dividing header">
                        <label><?php esc_html_e( '"Decline" button design', 'eu-cookies-bar' ); ?></label>
                    </h4>
                    <div class="equal width fields">

                        <div class="field">
                            <label for="eu_cookies_bar_cookies_bar_button_decline_title"><?php esc_html_e( 'Title', 'eu-cookies-bar' ); ?></label>
                            <input type="text" name="eu_cookies_bar_cookies_bar_button_decline_title"
                                   id="eu_cookies_bar_cookies_bar_button_decline_title"
                                   value="<?php echo esc_attr( htmlentities( $this->settings->get_params( 'cookies_bar_button_decline_title' ) ) ); ?>">
                        </div>
                        <div class="field">
                            <label for="eu_cookies_bar_cookies_bar_button_decline_color"><?php esc_html_e( 'Text color', 'eu-cookies-bar' ); ?></label>
                            <input type="text" class="color-picker"
                                   name="eu_cookies_bar_cookies_bar_button_decline_color"
                                   id="eu_cookies_bar_cookies_bar_button_decline_color"
                                   style="<?php if ( $this->settings->get_params( 'cookies_bar_button_decline_color' ) ) {
								       echo esc_attr( 'background:' . $this->settings->get_params( 'cookies_bar_button_decline_color' ) );
							       } ?>"
                                   value="<?php echo esc_attr( $this->settings->get_params( 'cookies_bar_button_decline_color' ) ); ?>">
                        </div>
                        <div class="field">
                            <label for="eu_cookies_bar_cookies_bar_button_decline_bg_color"><?php esc_html_e( 'Background color', 'eu-cookies-bar' ); ?></label>
                            <input type="text" class="color-picker"
                                   name="eu_cookies_bar_cookies_bar_button_decline_bg_color"
                                   id="eu_cookies_bar_cookies_bar_button_decline_bg_color"
                                   style="<?php if ( $this->settings->get_params( 'cookies_bar_button_decline_bg_color' ) ) {
								       echo esc_attr( 'background:' . $this->settings->get_params( 'cookies_bar_button_decline_bg_color' ) );
							       } ?>"
                                   value="<?php echo esc_attr( $this->settings->get_params( 'cookies_bar_button_decline_bg_color' ) ); ?>">
                        </div>
                        <div class="field">
                            <label for="eu_cookies_bar_cookies_bar_button_decline_border_radius"><?php esc_html_e( 'Border radius(px)', 'eu-cookies-bar' ); ?></label>
                            <input type="number" name="eu_cookies_bar_cookies_bar_button_decline_border_radius"
                                   id="eu_cookies_bar_cookies_bar_button_decline_border_radius" min="0"
                                   value="<?php echo esc_attr( $this->settings->get_params( 'cookies_bar_button_decline_border_radius' ) ); ?>">
                        </div>
                    </div>
                    <div class="field">
                        <label for="eu_cookies_bar_custom_css"><?php esc_html_e( 'Custom css', 'eu-cookies-bar' ); ?></label>
                        <textarea name="eu_cookies_bar_custom_css"
                                  id="eu_cookies_bar_custom_css"><?php echo $this->settings->get_params( 'custom_css' ) ?></textarea>
                    </div>
                </div>
                <div class="vi-ui bottom attached tab segment" data-tab="user_cookies_settings">

                    <div class="equal width fields">
                        <div class="field">
                            <label for="eu_cookies_bar_user_cookies_settings_enable"><?php esc_html_e( 'Enable', 'eu-cookies-bar' ); ?></label>
                            <div class="vi-ui toggle checkbox"
                                 data-tooltip="<?php esc_attr_e( 'Enable to make it GDPR compliant.', 'eu-cookies-bar' ) ?>"
                                 data-position="right center">
                                <input type="checkbox" name="eu_cookies_bar_user_cookies_settings_enable" value="1"
                                       id="eu_cookies_bar_user_cookies_settings_enable" <?php checked( $this->settings->get_params( 'user_cookies_settings_enable' ), '1' ); ?>><label></label>
                            </div>
                        </div>
                        <div class="field">
                            <label for="eu_cookies_bar_user_cookies_settings_bar_position"><?php esc_html_e( 'Cookies settings nav bar position', 'eu-cookies-bar' ); ?></label>
                            <div class="vi-ui input"
                                 data-tooltip="<?php esc_attr_e( 'You need to either show this bar or use shortcode [eucookiesbar_settings] to make it GDPR compliant', 'eu-cookies-bar' ) ?>"
                                 data-position="top center">
                                <select name="eu_cookies_bar_user_cookies_settings_bar_position"
                                        id="eu_cookies_bar_user_cookies_settings_bar_position"
                                        class="vi-ui fluid dropdown">
                                    <option value="hide" <?php selected( $this->settings->get_params( 'user_cookies_settings_bar_position' ), 'hide' ) ?>><?php esc_html_e( 'Hide', 'eu-cookies-bar' ); ?></option>
                                    <option value="left" <?php selected( $this->settings->get_params( 'user_cookies_settings_bar_position' ), 'left' ) ?>><?php esc_html_e( 'Bottom left', 'eu-cookies-bar' ); ?></option>
                                    <option value="right" <?php selected( $this->settings->get_params( 'user_cookies_settings_bar_position' ), 'right' ) ?>><?php esc_html_e( 'Bottom right', 'eu-cookies-bar' ); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="two fields">
                        <div class="field">
                            <div class="vi-ui grid">
                                <div class="row">
                                    <div class="column">
                                        <label for="eu_cookies_bar_user_cookies_settings_heading_title"><?php esc_html_e( 'Heading', 'eu-cookies-bar' ); ?></label>
                                        <input type="text" name="eu_cookies_bar_user_cookies_settings_heading_title"
                                               id="eu_cookies_bar_user_cookies_settings_heading_title"
                                               value="<?php echo esc_attr( htmlentities( $this->settings->get_params( 'user_cookies_settings_heading_title' ) ) ); ?>">

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="column">
                                        <label for="eu_cookies_bar_user_cookies_settings_heading_color"><?php esc_html_e( 'Heading color', 'eu-cookies-bar' ); ?></label>
                                        <input type="text" class="color-picker"
                                               name="eu_cookies_bar_user_cookies_settings_heading_color"
                                               id="eu_cookies_bar_user_cookies_settings_heading_color"
                                               style="<?php if ( $this->settings->get_params( 'user_cookies_settings_heading_color' ) ) {
											       echo esc_attr( 'background:' . $this->settings->get_params( 'user_cookies_settings_heading_color' ) );
										       } ?>"
                                               value="<?php echo esc_attr( $this->settings->get_params( 'user_cookies_settings_heading_color' ) ); ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="column">
                                        <label for="eu_cookies_bar_user_cookies_settings_heading_bg_color"><?php esc_html_e( 'Heading background color', 'eu-cookies-bar' ); ?></label>
                                        <input type="text" class="color-picker"
                                               name="eu_cookies_bar_user_cookies_settings_heading_bg_color"
                                               id="eu_cookies_bar_user_cookies_settings_heading_bg_color"
                                               style="<?php if ( $this->settings->get_params( 'user_cookies_settings_heading_bg_color' ) ) {
											       echo esc_attr( 'background:' . $this->settings->get_params( 'user_cookies_settings_heading_bg_color' ) );
										       } ?>"
                                               value="<?php echo esc_attr( $this->settings->get_params( 'user_cookies_settings_heading_bg_color' ) ); ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="column">
                                        <label for="eu_cookies_bar_user_cookies_settings_button_save_color"><?php esc_html_e( '"Save settings" button color', 'eu-cookies-bar' ); ?></label>
                                        <input type="text" class="color-picker"
                                               name="eu_cookies_bar_user_cookies_settings_button_save_color"
                                               id="eu_cookies_bar_user_cookies_settings_button_save_color"
                                               style="<?php if ( $this->settings->get_params( 'user_cookies_settings_button_save_color' ) ) {
											       echo esc_attr( 'background:' . $this->settings->get_params( 'user_cookies_settings_button_save_color' ) );
										       } ?>"
                                               value="<?php echo esc_attr( $this->settings->get_params( 'user_cookies_settings_button_save_color' ) ); ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="column">
                                        <label for="eu_cookies_bar_user_cookies_settings_button_save_bg_color"><?php esc_html_e( '"Save settings" button background color', 'eu-cookies-bar' ); ?></label>
                                        <input type="text" class="color-picker"
                                               name="eu_cookies_bar_user_cookies_settings_button_save_bg_color"
                                               id="eu_cookies_bar_user_cookies_settings_button_save_bg_color"
                                               style="<?php if ( $this->settings->get_params( 'user_cookies_settings_button_save_bg_color' ) ) {
											       echo esc_attr( 'background:' . $this->settings->get_params( 'user_cookies_settings_button_save_bg_color' ) );
										       } ?>"
                                               value="<?php echo esc_attr( $this->settings->get_params( 'user_cookies_settings_button_save_bg_color' ) ); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="fields">
                            <div class="eu-cookies-bar-cookies-bar-settings-wrap">
                                <div class="eu-cookies-bar-cookies-bar-settings-wrap-container">
                                    <div class="eu-cookies-bar-cookies-bar-settings-overlay">
                                    </div>
                                    <div class="eu-cookies-bar-cookies-bar-settings">
                                        <div class="eu-cookies-bar-cookies-bar-settings-header">
                                            <span class="eu-cookies-bar-cookies-bar-settings-header-text"><?php echo esc_attr( htmlentities( $this->settings->get_params( 'user_cookies_settings_heading_title' ) ) ); ?></span>
                                            <span class="eu-cookies-bar-close eu-cookies-bar-cookies-bar-settings-close"></span>
                                        </div>
                                        <div class="eu-cookies-bar-cookies-bar-settings-nav">
                                            <div class="eu-cookies-bar-cookies-bar-settings-privacy eu-cookies-bar-cookies-bar-settings-nav-active">
												<?php esc_html_e( 'Privacy & Cookies policy', 'eu-cookies-bar' ); ?>
                                            </div>
                                            <div class="eu-cookies-bar-cookies-bar-settings-cookie-list"><?php esc_html_e( 'Cookies list', 'eu-cookies-bar' ); ?></div>
                                        </div>
                                        <div class="eu-cookies-bar-cookies-bar-settings-content">
                                            <table class="eu-cookies-bar-cookies-bar-settings-content-child eu-cookies-bar-cookies-bar-settings-content-child-inactive">
                                                <tbody>
                                                <tr>
                                                    <th><?php esc_html_e( 'Cookie name', 'eu-cookies-bar' ); ?></th>
                                                    <th><?php esc_html_e( 'Active', 'eu-cookies-bar' ); ?></th>
                                                </tr>
												<?php
												for ( $i = 1; $i <= 10; $i ++ ) {
													?>
                                                    <tr>
                                                        <td>
                                                            <label for="<?php echo esc_attr( 'cookie_name_' . $i ); ?>"><?php echo esc_html( 'cookie_name_' . $i ); ?></label>
                                                        </td>
                                                        <td><input type="checkbox"
                                                                   id="<?php echo esc_attr( 'cookie_name_' . $i ); ?>"
                                                                   checked></td>
                                                    </tr>
													<?php
												}
												?>
                                                </tbody>
                                            </table>
                                            <div class="eu-cookies-bar-cookies-bar-settings-policy eu-cookies-bar-cookies-bar-settings-content-child">
												<?php echo do_shortcode( $this->settings->get_params( 'privacy_policy' ) ) ?>
                                            </div>
                                        </div>

                                        <span class="eu-cookies-bar-cookies-bar-settings-save-button"><?php esc_html_e( 'Save settings', 'eu-cookies-bar' ) ?></span>

										<?php
										?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <input type="submit" class="vi-ui button primary"
                       value="<?php esc_html_e( 'Save', 'eu-cookies-bar' ) ?>" name="submit">
            </form>
        </div>
        <div class="eu-cookies-bar-cookies-bar-wrap <?php echo esc_attr( 'eu-cookies-bar-cookies-bar-position-' . $this->settings->get_params( 'cookies_bar_position' ) ) ?>">
            <div class="eu-cookies-bar-cookies-bar">
                <div class="eu-cookies-bar-cookies-bar-message">
                    <div>
						<?php echo wp_kses_post(force_balance_tags( $this->settings->get_params( 'cookies_bar_message' )) ); ?>
						<?php
						if ( $this->settings->get_params( 'privacy_policy_url' ) ) {
							?>
                            <a target="_blank"
                               href="<?php echo esc_url( $this->settings->get_params( 'privacy_policy_url' ) ) ?>"><?php esc_html_e( 'View more', 'eu-cookies-bar' ) ?></a>
							<?php
						} elseif ( get_option( 'wp_page_for_privacy_policy', '' ) ) {
							?>
                            <a target="_blank"
                               href="<?php echo esc_url( get_page_link( (int) get_option( 'wp_page_for_privacy_policy', '' ) ) ) ?>"><?php esc_html_e( 'View more', 'eu-cookies-bar' ) ?></a>
							<?php
						}
						?>
                    </div>
                </div>
                <div class="eu-cookies-bar-cookies-bar-button-container">

                    <div class="eu-cookies-bar-cookies-bar-button-wrap">

                        <div class="eu-cookies-bar-cookies-bar-button eu-cookies-bar-cookies-bar-button-settings <?php if ( ! $this->settings->get_params( 'user_cookies_settings_enable' ) )
							echo esc_attr( 'eu-cookies-bar-cookies-bar-button-hide' ) ?>">
                            <span><?php esc_html_e( 'Cookies settings', 'eu-cookies-bar' ); ?></span>
                        </div>

                        <div class="eu-cookies-bar-cookies-bar-button eu-cookies-bar-cookies-bar-button-accept <?php if ( ! $this->settings->get_params( 'cookies_bar_show_button_accept' ) )
							echo esc_attr( 'eu-cookies-bar-cookies-bar-button-hide' ) ?>">
                            <span class="eu-cookies-bar-tick"><?php echo esc_html( $this->settings->get_params( 'cookies_bar_button_accept_title' ) ); ?></span>
                        </div>

                        <div class="eu-cookies-bar-cookies-bar-button eu-cookies-bar-cookies-bar-button-decline <?php if ( ! $this->settings->get_params( 'cookies_bar_show_button_decline' ) )
							echo esc_attr( 'eu-cookies-bar-cookies-bar-button-hide' ) ?>">
                            <span class="eu-cookies-bar-decline"><?php echo esc_html( $this->settings->get_params( 'cookies_bar_button_decline_title' ) ); ?></span>
                        </div>

                        <div class="eu-cookies-bar-cookies-bar-button eu-cookies-bar-cookies-bar-button-close <?php if ( ! $this->settings->get_params( 'cookies_bar_show_button_close' ) )
							echo esc_attr( 'eu-cookies-bar-cookies-bar-button-hide' ) ?>">
                            <span class="eu-cookies-bar-close"></span>
                        </div>

                    </div>

                </div>
            </div>
        </div>
        <div class="eu-cookies-bar-cookies-settings-call-container <?php echo esc_attr( ( $this->settings->get_params( 'user_cookies_settings_bar_position' ) === 'hide' ) ? 'eu-cookies-bar-cookies-bar-button-hide' : 'eu-cookies-bar-cookies-settings-call-position-' . $this->settings->get_params( 'user_cookies_settings_bar_position' ) ); ?>">
            <div class="eu-cookies-bar-cookies-settings-call-button eu-cookies-bar-cookies-bar-button-settings">
                <span><?php esc_html_e( 'Cookies settings', 'eu-cookies-bar' ); ?></span>
            </div>
        </div>
		<?php
		do_action( 'villatheme_support_eu-cookies-bar' );
	}

	public function save_settings() {
		global $eu_cookies_bar_settings;
		if ( isset( $_POST['submit'] ) && isset( $_POST['eu_cookies_bar_nonce_field'] ) && wp_verify_nonce( sanitize_text_field( $_POST['eu_cookies_bar_nonce_field'] ), 'eu_cookies_bar_settings_page_save' ) ) {
			$args                    = array(
				'enable'                                     => isset( $_POST['eu_cookies_bar_enable'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_enable'] ) : '',
				'block_until_accept'                         => isset( $_POST['eu_cookies_bar_block_until_accept'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_block_until_accept'] ) : '',
				'expire'                                     => isset( $_POST['eu_cookies_bar_expire'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_expire'] ) : '',
				'privacy_policy'                             => isset( $_POST['eu_cookies_bar_privacy_policy'] ) ? wp_kses_post( stripslashes( $_POST['eu_cookies_bar_privacy_policy'] ) ) : '',
				'privacy_policy_url'                         => isset( $_POST['eu_cookies_bar_privacy_policy_url'] ) ? stripslashes( sanitize_text_field( $_POST['eu_cookies_bar_privacy_policy_url'] ) ) : '',
				'strictly_necessary'                         => isset( $_POST['eu_cookies_bar_strictly_necessary'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_strictly_necessary'] ) : '',
				'strictly_necessary_family'                  => isset( $_POST['eu_cookies_bar_strictly_necessary_family'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_strictly_necessary_family'] ) : '',
				'cookies_bar_message'                        => isset( $_POST['eu_cookies_bar_cookies_bar_message'] ) ? wp_kses_post( stripslashes( $_POST['eu_cookies_bar_cookies_bar_message'] ) ) : '',
				'cookies_bar_position'                       => isset( $_POST['eu_cookies_bar_cookies_bar_position'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_cookies_bar_position'] ) : '',
				'cookies_bar_show_button_accept'             => isset( $_POST['eu_cookies_bar_cookies_bar_show_button_accept'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_cookies_bar_show_button_accept'] ) : '',
				'cookies_bar_button_accept_title'            => isset( $_POST['eu_cookies_bar_cookies_bar_button_accept_title'] ) ? stripslashes( sanitize_text_field( $_POST['eu_cookies_bar_cookies_bar_button_accept_title'] ) ) : '',
				'cookies_bar_button_accept_color'            => isset( $_POST['eu_cookies_bar_cookies_bar_button_accept_color'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_cookies_bar_button_accept_color'] ) : '',
				'cookies_bar_button_accept_bg_color'         => isset( $_POST['eu_cookies_bar_cookies_bar_button_accept_bg_color'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_cookies_bar_button_accept_bg_color'] ) : '',
				'cookies_bar_button_accept_border_radius'    => isset( $_POST['eu_cookies_bar_cookies_bar_button_accept_border_radius'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_cookies_bar_button_accept_border_radius'] ) : '',
				'cookies_bar_show_button_close'              => isset( $_POST['eu_cookies_bar_cookies_bar_show_button_close'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_cookies_bar_show_button_close'] ) : '',
				'cookies_bar_show_button_decline'            => isset( $_POST['eu_cookies_bar_cookies_bar_show_button_decline'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_cookies_bar_show_button_decline'] ) : '',
				'cookies_bar_button_decline_title'           => isset( $_POST['eu_cookies_bar_cookies_bar_button_decline_title'] ) ? stripslashes( sanitize_text_field( $_POST['eu_cookies_bar_cookies_bar_button_decline_title'] ) ) : '',
				'cookies_bar_button_decline_color'           => isset( $_POST['eu_cookies_bar_cookies_bar_button_decline_color'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_cookies_bar_button_decline_color'] ) : '',
				'cookies_bar_button_decline_bg_color'        => isset( $_POST['eu_cookies_bar_cookies_bar_button_decline_bg_color'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_cookies_bar_button_decline_bg_color'] ) : '',
				'cookies_bar_button_decline_border_radius'   => isset( $_POST['eu_cookies_bar_cookies_bar_button_decline_border_radius'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_cookies_bar_button_decline_border_radius'] ) : '',
				'cookies_bar_on_close'                       => isset( $_POST['eu_cookies_bar_cookies_bar_on_close'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_cookies_bar_on_close'] ) : '',
				'cookies_bar_on_scroll'                      => isset( $_POST['eu_cookies_bar_cookies_bar_on_scroll'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_cookies_bar_on_scroll'] ) : '',
				'cookies_bar_on_page_redirect'               => isset( $_POST['eu_cookies_bar_cookies_bar_on_page_redirect'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_cookies_bar_on_page_redirect'] ) : '',
				'cookies_bar_font_size'                      => isset( $_POST['eu_cookies_bar_cookies_bar_font_size'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_cookies_bar_font_size'] ) : '',
				'cookies_bar_color'                          => isset( $_POST['eu_cookies_bar_cookies_bar_color'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_cookies_bar_color'] ) : '',
				'cookies_bar_bg_color'                       => isset( $_POST['eu_cookies_bar_cookies_bar_bg_color'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_cookies_bar_bg_color'] ) : '',
				'cookies_bar_border_radius'                  => isset( $_POST['eu_cookies_bar_cookies_bar_border_radius'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_cookies_bar_border_radius'] ) : '',
				'cookies_bar_padding'                        => isset( $_POST['eu_cookies_bar_cookies_bar_padding'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_cookies_bar_padding'] ) : '',
				'cookies_bar_margin'                         => isset( $_POST['eu_cookies_bar_cookies_bar_margin'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_cookies_bar_margin'] ) : '',
				'cookies_bar_opacity'                        => isset( $_POST['eu_cookies_bar_cookies_bar_opacity'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_cookies_bar_opacity'] ) : '',
				'user_cookies_settings_enable'               => isset( $_POST['eu_cookies_bar_user_cookies_settings_enable'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_user_cookies_settings_enable'] ) : '',
				'user_cookies_settings_heading_title'        => isset( $_POST['eu_cookies_bar_user_cookies_settings_heading_title'] ) ? stripslashes( sanitize_text_field( $_POST['eu_cookies_bar_user_cookies_settings_heading_title'] ) ) : '',
				'user_cookies_settings_heading_color'        => isset( $_POST['eu_cookies_bar_user_cookies_settings_heading_color'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_user_cookies_settings_heading_color'] ) : '',
				'user_cookies_settings_heading_bg_color'     => isset( $_POST['eu_cookies_bar_user_cookies_settings_heading_bg_color'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_user_cookies_settings_heading_bg_color'] ) : '',
				'user_cookies_settings_button_save_color'    => isset( $_POST['eu_cookies_bar_user_cookies_settings_button_save_color'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_user_cookies_settings_button_save_color'] ) : '',
				'user_cookies_settings_button_save_bg_color' => isset( $_POST['eu_cookies_bar_user_cookies_settings_button_save_bg_color'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_user_cookies_settings_button_save_bg_color'] ) : '',
				'user_cookies_settings_bar_position'         => isset( $_POST['eu_cookies_bar_user_cookies_settings_bar_position'] ) ? sanitize_text_field( $_POST['eu_cookies_bar_user_cookies_settings_bar_position'] ) : '',
				'custom_css'                                 => isset( $_POST['eu_cookies_bar_custom_css'] ) ? wp_kses_post( stripslashes( $_POST['eu_cookies_bar_custom_css'] ) ) : '',
			);
			$eu_cookies_bar_settings = $args;
			update_option( 'eu_cookies_bar_params', $args );
			$this->settings = EU_COOKIES_BAR_Data::get_instance( true );
		}
	}

	/**
	 * Init Script in Admin
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_style( 'eu-cookies-bar-icons', EU_COOKIES_BAR_CSS . 'eu-cookies-bar-icons.css', array(), EU_COOKIES_BAR_VERSION );
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
		if ( $page == 'eu-cookies-bar' ) {
			global $wp_scripts;
			if ( isset( $wp_scripts->registered['jquery-ui-accordion'] ) ) {
				unset( $wp_scripts->registered['jquery-ui-accordion'] );
				wp_dequeue_script( 'jquery-ui-accordion' );
			}
			if ( isset( $wp_scripts->registered['accordion'] ) ) {
				unset( $wp_scripts->registered['accordion'] );
				wp_dequeue_script( 'accordion' );
			}
			$scripts = $wp_scripts->registered;
			foreach ( $scripts as $k => $script ) {
				preg_match( '/select2/i', $k, $result );
				if ( count( array_filter( $result ) ) ) {
					unset( $wp_scripts->registered[ $k ] );
					wp_dequeue_script( $script->handle );
				}
				preg_match( '/bootstrap/i', $k, $result );
				if ( count( array_filter( $result ) ) ) {
					unset( $wp_scripts->registered[ $k ] );
					wp_dequeue_script( $script->handle );
				}
			}
			/*Stylesheet*/
			wp_enqueue_style( 'eu-cookies-bar-semantic', EU_COOKIES_BAR_CSS . 'semantic.min.css' );
			wp_enqueue_style( 'eu-cookies-bar-admin-css', EU_COOKIES_BAR_CSS . 'eu-cookies-bar-admin.css' );
			$css = '.eu-cookies-bar-cookies-bar-wrap{';
			if ( $this->settings->get_params( 'cookies_bar_font_size' ) ) {
				$css .= 'font-size:' . $this->settings->get_params( 'cookies_bar_font_size' ) . 'px;';
			}
			if ( $this->settings->get_params( 'cookies_bar_color' ) ) {
				$css .= 'color:' . $this->settings->get_params( 'cookies_bar_color' ) . ';';
			}
			if ( $this->settings->get_params( 'cookies_bar_margin' ) ) {
				$css .= 'margin:' . $this->settings->get_params( 'cookies_bar_margin' ) . 'px;';
			}
			if ( $this->settings->get_params( 'cookies_bar_padding' ) ) {
				$css .= 'padding:' . $this->settings->get_params( 'cookies_bar_padding' ) . 'px;';
			}
			if ( $this->settings->get_params( 'cookies_bar_border_radius' ) ) {
				$css .= 'border-radius:' . $this->settings->get_params( 'cookies_bar_border_radius' ) . 'px;';
			}
			$opacity    = ( $this->settings->get_params( 'cookies_bar_opacity' ) !== '' ) ? ( $this->settings->get_params( 'cookies_bar_opacity' ) ) : 0.7;
			$background = ( $this->settings->get_params( 'cookies_bar_bg_color' ) !== '' ) ? ( $this->settings->get_params( 'cookies_bar_bg_color' ) ) : '#000000';
			$css        .= 'background:' . eu_cookies_bar_hex2rgba( $background, $opacity ) . ';';
			$css        .= '}';

			$css .= '.eu-cookies-bar-cookies-bar-button-accept{';
			if ( $this->settings->get_params( 'cookies_bar_button_accept_color' ) ) {
				$css .= 'color:' . $this->settings->get_params( 'cookies_bar_button_accept_color' ) . ';';
			}
			if ( $this->settings->get_params( 'cookies_bar_button_accept_bg_color' ) ) {
				$css .= 'background:' . $this->settings->get_params( 'cookies_bar_button_accept_bg_color' ) . ';';
			}
			if ( $this->settings->get_params( 'cookies_bar_button_accept_border_radius' ) ) {
				$css .= 'border-radius:' . $this->settings->get_params( 'cookies_bar_button_accept_border_radius' ) . 'px;';
			}
			$css .= '}';

			$css .= '.eu-cookies-bar-cookies-bar-button-decline{';
			if ( $this->settings->get_params( 'cookies_bar_button_decline_color' ) ) {
				$css .= 'color:' . $this->settings->get_params( 'cookies_bar_button_decline_color' ) . ';';
			}
			if ( $this->settings->get_params( 'cookies_bar_button_decline_bg_color' ) ) {
				$css .= 'background:' . $this->settings->get_params( 'cookies_bar_button_decline_bg_color' ) . ';';
			}
			if ( $this->settings->get_params( 'cookies_bar_button_decline_border_radius' ) ) {
				$css .= 'border-radius:' . $this->settings->get_params( 'cookies_bar_button_decline_border_radius' ) . 'px;';
			}
			$css .= '}';
			/*cookies setting form*/
			$css .= '.eu-cookies-bar-cookies-bar-settings-header{';
			if ( $this->settings->get_params( 'user_cookies_settings_heading_color' ) ) {
				$css .= 'color:' . $this->settings->get_params( 'user_cookies_settings_heading_color' ) . ';';
			}
			if ( $this->settings->get_params( 'user_cookies_settings_heading_bg_color' ) ) {
				$css .= 'background:' . $this->settings->get_params( 'user_cookies_settings_heading_bg_color' ) . ';';
			}
			$css .= '}';
			$css .= '.eu-cookies-bar-cookies-bar-settings-save-button{';
			if ( $this->settings->get_params( 'user_cookies_settings_button_save_color' ) ) {
				$css .= 'color:' . $this->settings->get_params( 'user_cookies_settings_button_save_color' ) . ';';
			}
			if ( $this->settings->get_params( 'user_cookies_settings_button_save_bg_color' ) ) {
				$css .= 'background:' . $this->settings->get_params( 'user_cookies_settings_button_save_bg_color' ) . ';';
			}
			$css .= '}';
			/*custom css*/
			if ( $this->settings->get_params( 'custom_css' ) ) {
				$css .= $this->settings->get_params( 'custom_css' );
			}
			wp_add_inline_style( 'eu-cookies-bar-admin-css', $css );
			wp_enqueue_script( 'eu-cookies-bar-semantic', EU_COOKIES_BAR_JS . 'semantic.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'eu-cookies-bar-address', EU_COOKIES_BAR_JS . 'jquery.address-1.6.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'eu-cookies-bar-admin-js', EU_COOKIES_BAR_JS . 'eu-cookies-bar-admin.js', array( 'jquery' ), EU_COOKIES_BAR_VERSION );
			/*Color picker*/
			wp_enqueue_script(
				'iris', admin_url( 'js/iris.min.js' ), array(
				'jquery-ui-draggable',
				'jquery-ui-slider',
				'jquery-touch-punch'
			), false, 1
			);

		}
	}

	/**
	 * load Language translate
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'eu-cookies-bar' );
		// Global + Frontend Locale
		load_textdomain( 'eu-cookies-bar', EU_COOKIES_BAR_LANGUAGES . "eu-cookies-bar-$locale.mo" );
		load_plugin_textdomain( 'eu-cookies-bar', false, EU_COOKIES_BAR_LANGUAGES );
	}
}