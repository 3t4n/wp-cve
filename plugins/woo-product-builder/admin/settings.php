<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

class VI_WPRODUCTBUILDER_F_Admin_Settings {
	function __construct() {
		add_action( 'admin_menu', array( $this, 'setting_menu' ), 22 );
		add_action( 'admin_init', array( $this, 'save_data' ) );
	}

	public function save_data() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}
		if ( ! isset( $_POST['_opt_woo_product_builder_nonce'] ) || ! wp_verify_nonce( $_POST['_opt_woo_product_builder_nonce'], 'opt_woo_product_builder_action_nonce' ) ) {
			return false;
		}
		if ( isset( $_POST['message_body'] ) ) {
			$_POST['woopb_option-param']['message_body'] = wp_kses_post( $_POST['message_body'] );
		}
		$data = $_POST['woopb_option-param'];
		foreach ( $data as $key => $value ) {
			if ( in_array( $key, array(
				'message_body'
			) ) ) {
				$data[ $key ] = wp_kses_post( $value );
			} elseif ( in_array( $key, array(
				'message_success',
				'email_subject',
			) ) ) {
				$data[ $key ] = stripslashes( $value );
			} else {
				$data[ $key ] = wc_clean( $value );
			}
		}
		update_option( 'woopb_option-param', $data );

	}

	public function page_callback() { ?>
        <div class="wrap woocommerce-product-builder">
            <h2><?php esc_html_e( 'Product Builder for WooCommerce Settings', 'woo-product-builder' ) ?></h2>

            <form class="vi-ui form" method="post" action="">
				<?php
				wp_nonce_field( 'opt_woo_product_builder_action_nonce', '_opt_woo_product_builder_nonce' );
				settings_fields( 'woo-product-builder' );
				do_settings_sections( 'woo-product-builder' );
				?>
                <div class="vi-ui top attached tabular menu">
                    <a class="item active" data-tab="design"><?php esc_html_e( 'Design', 'woo-product-builder' ) ?></a>
                    <a class="item " data-tab="email"><?php esc_html_e( 'Email', 'woo-product-builder' ) ?></a>
                </div>

                <!--				Email Design-->
                <div class="vi-ui bottom attached tab segment " data-tab="email">
                    <table class="form-table">
                        <tbody>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_option_field( 'enable_email' ) ?>"><?php esc_html_e( 'Enable', 'woo-product-builder' ) ?></label>
                            </th>
                            <td>
                                <div class="vi-ui checkbox toggle">
                                    <input type="checkbox" id="<?php echo self::set_option_field( 'enable_email' ) ?>"
                                           name="<?php echo self::set_option_field( 'enable_email' ) ?>"
                                           value="1" <?php checked( self::get_option_field( 'enable_email' ), 1 ) ?>>
                                </div>
                                <p class="description"><?php esc_html_e( 'Enable send email at review page.', 'woo-product-builder' ) ?></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_option_field( 'email_from' ) ?>"><?php esc_html_e( 'From', 'woo-product-builder' ) ?></label>
                            </th>
                            <td>
                                <div class="field">
									<?php $admin_email = get_option( 'admin_email' ); ?>
                                    <input type="email" id="<?php echo self::set_option_field( 'email_from' ) ?>"
                                           name="<?php echo self::set_option_field( 'email_from' ) ?>"
                                           placeholder="<?php esc_html_e( '<admin@yoursite.com>', 'woo-product-builder' ) ?>"
                                           value="<?php echo self::get_option_field( 'email_from', $admin_email ) ?>"
                                           required>
                                </div>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_option_field( 'email_subject' ) ?>"><?php esc_html_e( 'Subject', 'woo-product-builder' ) ?></label>
                            </th>
                            <td>
                                <div class="field">
                                    <input type="text" id="<?php echo self::set_option_field( 'email_subject' ) ?>"
                                           name="<?php echo self::set_option_field( 'email_subject' ) ?>"
                                           placeholder="<?php esc_html_e( '[Subject email]', 'woo-product-builder' ) ?>"
                                           value="<?php echo self::get_option_field( 'email_subject' ) ?>">
                                </div>
                                <p class="description"><?php esc_html_e( 'The first text display on subject field of email.', 'woo-product-builder' ) ?></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_option_field( 'message_body' ) ?>"><?php esc_html_e( 'Message Body', 'woo-product-builder' ) ?></label>
                            </th>
                            <td>
                                <div class="field">
									<?php $default_content = "From: {email} \nSubject: {subject} \nMessage body: \n{message_content} \n\n-- \nThis e-mail was sent from a contact form on anonymous website (http://yoursite.com)"; ?>
									<?php
									$content   = self::get_option_field( 'message_body', $default_content );
									$editor_id = 'message_body';

									wp_editor( $content, $editor_id );
									?>
                                </div>
                                <p class="description"><?php esc_html_e( 'The content of message.', 'woo-product-builder' ) ?></p>
                                <ul class="description" style="list-style: none">
                                    <li>
                                        <span>{email}</span>
                                        - <?php esc_html_e( 'Your email.', 'woo-product-builder' ) ?>
                                    </li>
                                    <li>
                                        <span>{subject}</span>
                                        - <?php esc_html_e( 'The subject of email.', 'woo-product-builder' ) ?>
                                    </li>
                                    <li>
                                        <span>{message_content}</span>
                                        - <?php esc_html_e( 'The content of message body.', 'woo-product-builder' ) ?>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_option_field( 'message_success' ) ?>"><?php esc_html_e( 'Message thank you', 'woo-product-builder' ) ?></label>
                            </th>
                            <td>
                                <div class="field">
                                    <input type="text" id="<?php echo self::set_option_field( 'message_success' ) ?>"
                                           name="<?php echo self::set_option_field( 'message_success' ) ?>"
                                           value="<?php echo self::get_option_field( 'message_success', 'Thank you! Your email has sent to your friend!' ) ?>"/>
                                </div>
                                <p class="description"><?php esc_html_e( 'The messages display after sent email.', 'woo-product-builder' ) ?></p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!--				Design-->
                <div class="vi-ui bottom attached tab segment active" data-tab="design">
                    <h3><?php esc_html_e( 'Button', 'woo-product-builder' ); ?></h3>
                    <p class="description"><?php esc_html_e( 'Set color and background color for Product Builder for WooCommerce buttons.', 'woo-product-builder' ); ?></p>
                    <table class="form-table vi-ui form">
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_option_field( 'button_text_color' ) ?>"><?php esc_html_e( 'Text color', 'woo-product-builder' ) ?></label>
                            </th>
                            <td>
                                <input class="color-picker" type="text"
                                       name="<?php echo self::set_option_field( 'button_text_color' ); ?>"
                                       value="<?php echo self::get_option_field( 'button_text_color', '#fff' ); ?>"
                                       style="background-color: <?php echo self::get_option_field( 'pagination_text_color', '#fff' ); ?>">
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_option_field( 'button_bg_color' ) ?>"><?php esc_html_e( 'Background color', 'woo-product-builder' ) ?></label>
                            </th>
                            <td>
                                <input class="color-picker" type="text"
                                       name="<?php echo self::set_option_field( 'button_bg_color' ); ?>"
                                       value="<?php echo self::get_option_field( 'button_bg_color', '#04747a' ); ?>"
                                       style="background-color: <?php echo self::get_option_field( 'pagination_bg_color', '#04747a' ); ?>">
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_option_field( 'button_main_text_color' ) ?>"><?php esc_html_e( 'Primary text color', 'woo-product-builder' ); ?></label>
                            </th>
                            <td>
                                <input class="color-picker" type="text"
                                       name="<?php echo self::set_option_field( 'button_main_text_color' ); ?>"
                                       value="<?php echo self::get_option_field( 'button_main_text_color', '#fff' ); ?>"
                                       style="background-color: <?php echo self::get_option_field( 'preview_text_color', '#fff' ); ?>">
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_option_field( 'button_main_bg_color' ) ?>"><?php esc_html_e( 'Primary background color', 'woo-product-builder' ); ?></label>
                            </th>
                            <td>
                                <input class="color-picker" type="text"
                                       name="<?php echo self::set_option_field( 'button_main_bg_color' ); ?>"
                                       value="<?php echo self::get_option_field( 'button_main_bg_color', '#4b9989' ); ?>"
                                       style="background-color: <?php echo self::get_option_field( 'preview_bg_color', '#4b9989' ); ?>">
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_option_field( 'button_icon' ) ?>"><?php esc_html_e( 'Button Icon', 'woo-product-builder' ); ?></label>
                            </th>
                            <td>
                                <select class="vi-ui dropdown"
                                        name="<?php echo self::set_option_field( 'button_icon' ) ?>">
                                    <option value="0" <?php selected( self::get_option_field( 'button_icon' ), 0 ) ?>><?php esc_html_e( 'Text', 'woo-product-builder' ); ?></option>
                                    <option value="1" <?php selected( self::get_option_field( 'button_icon' ), 1 ) ?>><?php esc_html_e( 'Icon', 'woo-product-builder' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_option_field( 'custom_css' ) ?>"><?php esc_html_e( 'Custom CSS', 'woo-product-builder' ); ?></label>
                            </th>
                            <td>
                                <a class="vi-ui button yellow" target="_blank" href="https://1.envato.market/M3Wjq">
									<?php echo esc_html__( 'Unlock This Feature', 'woo-product-builder' ) ?>
                                </a>
                            </td>
                        </tr>
                    </table>


                    <h3><?php esc_html_e( 'Mobile', 'woocommerce-product-builder' ); ?></h3>
                    <table class="form-table vi-ui form">
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_option_field( 'mobile_bar_position' ) ?>">
									<?php esc_html_e( 'Distance from bottom', 'woocommerce-product-builder' ) ?>
                                </label>
                            </th>
                            <td>
                                <input class="" type="number" min="0" step="1"
                                       name="<?php echo self::set_option_field( 'mobile_bar_position' ); ?>"
                                       value="<?php echo self::get_option_field( 'mobile_bar_position', 0 ); ?>">
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_option_field( 'mobile_bar_text_color' ) ?>">
									<?php esc_html_e( 'Control bar text color', 'woocommerce-product-builder' ) ?>
                                </label>
                            </th>
                            <td>
                                <input class="color-picker" type="text"
                                       name="<?php echo self::set_option_field( 'mobile_bar_text_color' ); ?>"
                                       value="<?php echo self::get_option_field( 'mobile_bar_text_color', '#000' ); ?>"
                                       style="background-color: <?php echo self::get_option_field( 'mobile_bar_text_color', '#000' ); ?>">
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_option_field( 'mobile_bar_bg_color' ) ?>">
									<?php esc_html_e( 'Control bar background color', 'woocommerce-product-builder' ) ?>
                                </label>
                            </th>
                            <td>
                                <input class="color-picker" type="text"
                                       name="<?php echo self::set_option_field( 'mobile_bar_bg_color' ); ?>"
                                       value="<?php echo self::get_option_field( 'mobile_bar_bg_color', '#fff' ); ?>"
                                       style="background-color: <?php echo self::get_option_field( 'mobile_bar_bg_color', '#fff' ); ?>">
                            </td>
                        </tr>

                    </table>

                    <h3><?php esc_html_e( 'Other', 'woocommerce-product-builder' ); ?></h3>
                    <table class="form-table vi-ui form">
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_option_field( 'share_link' ) ?>"><?php esc_html_e( 'Display share link', 'woocommerce-product-builder' ); ?></label>
                            </th>
                            <td>
                                <a class="vi-ui button yellow" target="_blank" href="https://1.envato.market/M3Wjq">
		                            <?php echo esc_html__( 'Unlock This Feature', 'woo-product-builder' ) ?>
                                </a>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_option_field( 'get_short_share_link' ) ?>"><?php esc_html_e( 'Display get short share link for customer', 'woocommerce-product-builder' ); ?></label>
                                <p class="description"><?php esc_html_e( 'Default: Display for admin', 'woocommerce-product-builder' ); ?></p>
                            </th>
                            <td>
                                <a class="vi-ui button yellow" target="_blank" href="https://1.envato.market/M3Wjq">
		                            <?php echo esc_html__( 'Unlock This Feature', 'woo-product-builder' ) ?>
                                </a>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_option_field( 'time_to_remove_short_share_link' ) ?>"><?php esc_html_e( 'Remove short share link records after x day(s)', 'woocommerce-product-builder' ); ?></label>
                            </th>
                            <td>
                                <a class="vi-ui button yellow" target="_blank" href="https://1.envato.market/M3Wjq">
		                            <?php echo esc_html__( 'Unlock This Feature', 'woo-product-builder' ) ?>
                                </a>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_option_field( 'remove_session' ) ?>"><?php esc_html_e( 'Clear session', 'woocommerce-product-builder' ); ?></label>
                            </th>
                            <td>
                                <div class="vi-ui checkbox toggle">
                                    <input type="checkbox" id="<?php echo self::set_option_field( 'remove_session' ) ?>"
                                           name="<?php echo self::set_option_field( 'remove_session' ) ?>"
                                           value="1" <?php checked( self::get_option_field( 'remove_session' ), 1 ) ?>>
                                </div>
                                <p class="description"><?php esc_html_e( 'Clear session after add to cart', 'woocommerce-product-builder' ); ?></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_option_field( 'clear_filter' ) ?>"><?php esc_html_e( 'Clear filter', 'woocommerce-product-builder' ); ?></label>
                            </th>
                            <td>
                                <a class="vi-ui button yellow" target="_blank" href="https://1.envato.market/M3Wjq">
		                            <?php echo esc_html__( 'Unlock This Feature', 'woo-product-builder' ) ?>
                                </a>
                                <p class="description"><?php esc_html_e( 'Clear filter after select', 'woocommerce-product-builder' ); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>

                <p>
                    <button class="vi-ui button primary woopb-button-save">
						<?php esc_html_e( 'Save', 'woo-product-builder' ); ?>
                    </button>
                </p>

            </form>
			<?php do_action( 'villatheme_support_woo-product-builder' ) ?>
        </div>
	<?php }

	public static function set_option_field( $field, $multi = false ) {
		if ( $field ) {
			if ( $multi ) {
				return 'woopb_option-param[' . $field . '][]';
			} else {
				return 'woopb_option-param[' . $field . ']';
			}

		} else {
			return '';
		}
	}

	public static function get_option_field( $field, $default = '' ) {
		$params = get_option( 'woopb_option-param', array() );
		if ( isset( $params[ $field ] ) && $field ) {
			return $params[ $field ];
		} else {
			return $default;
		}
	}

	function setting_menu() {
		add_submenu_page(
			'edit.php?post_type=woo_product_builder',
			esc_html__( 'Product Builder for WooCommerce Setting', 'woo-product-builder' ),
			esc_html__( 'Settings', 'woo-product-builder' ),
			'manage_options',
			'woocommerce-product-builder-setting',
			array( $this, 'page_callback' )
		);
	}
}