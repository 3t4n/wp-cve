<?php

/*
Class Name: WOO_F_LOOKBOOK_Admin_Settings
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2017 villatheme.com. All rights reserved.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOO_F_LOOKBOOK_Admin_Settings {
	static $params;

	public function __construct() {
		add_action( 'admin_init', array( $this, 'save_meta_boxes' ) );
//		add_action( 'admin_print_scripts', array( $this, 'close_iframe' ) );
	}

	public function close_iframe() {
		/*Update access token*/
		if ( isset( $_GET['code'] ) && isset( $_GET['page'] ) && $_GET['code'] && $_GET['page'] == 'woocommerce-lookbook-settings' ) {
			$params = array(
				'client_id'     => self::get_field( 'ins_client_id', '' ),
				'client_secret' => self::get_field( 'ins_client_secret', '' ),
				'grant_type'    => 'authorization_code',
				'redirect_uri'  => admin_url( 'edit.php?post_type=woocommerce-lookbook&page=woocommerce-lookbook-settings' ),
				'code'          => wp_unslash( $_GET['code'] )
			);
			$ch     = curl_init( 'https://api.instagram.com/oauth/access_token' );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $params );
			$result = curl_exec( $ch );
			$data   = json_decode( $result, true );
			if ( isset( $data['access_token'] ) && $data['access_token'] ) {
				$options                     = get_option( 'woo_lookbook_params' );
				$options['ins_access_token'] = $data['access_token'];
				update_option( 'woo_lookbook_params', $options );

			} else {
				esc_html_e( 'Please save Settings and get access token again.', 'woo-lookbook' );
				die;
			}
			?>
            <script type="text/javascript">
                window.close();
            </script>
			<?php
		}

	}

	/**
	 * @param $value
	 *
	 * @return array|string
	 */
	private function stripslashes_deep( $value ) {
		$value = is_array( $value ) ? array_map( 'stripslashes_deep', $value ) : stripslashes( $value );

		return $value;
	}

	/**
	 * Save post meta
	 *
	 * @param $post
	 *
	 * @return bool
	 */
	public function save_meta_boxes() {
		if ( ! isset( $_POST['_woo_lookbook_nonce'] ) || ! isset( $_POST['woo_lookbook_params'] ) ) {
			return false;
		}
		if ( ! wp_verify_nonce( $_POST['_woo_lookbook_nonce'], 'woo_lookbook_settings' ) ) {
			return false;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}
		$data = wc_clean( $_POST['woo_lookbook_params'] );
		if ( is_array( $data ) ) {
			$data = array_map( 'sanitize_text_field', $data );
		} else {
			$data = array();
		}
		if ( empty( $data['ins_access_token'] ) ) {
			delete_transient( 'wlb_ins_access_token' );
		}
		delete_transient( 'wlb_instagram_data' );
		update_option( 'woo_lookbook_params', $data );

	}

	/**
	 * Set Nonce
	 * @return string
	 */
	protected static function set_nonce() {
		return wp_nonce_field( 'woo_lookbook_settings', '_woo_lookbook_nonce' );
	}

	/**
	 * Set field in meta box
	 *
	 * @param      $field
	 * @param bool $multi
	 *
	 * @return string
	 */
	protected static function set_field( $field, $multi = false ) {
		if ( $field ) {
			if ( $multi ) {
				return 'woo_lookbook_params[' . $field . '][]';
			} else {
				return 'woo_lookbook_params[' . $field . ']';
			}
		} else {
			return '';
		}
	}

	/**
	 * Get Post Meta
	 *
	 * @param $field
	 *
	 * @return bool
	 */
	public static function get_field( $field, $default = '' ) {
		global $wlb_settings;
		$params = $wlb_settings;

		if ( self::$params ) {
			$params = self::$params;
		} else {
			self::$params = $params;
		}
		if ( isset( $params[ $field ] ) && $field ) {
			return $params[ $field ];
		} else {
			return $default;
		}
	}


	/**
	 * HTML setting page
	 * @return array
	 */
	public static function page_callback() {
		self::$params = get_option( 'woo_lookbook_params', array() );
		?>
        <div class="wrap woo-lookbook">
            <h2><?php esc_attr_e( 'LookBook for WooCommerce Settings', 'woo-lookbook' ) ?></h2>
            <form method="post" action="" class="vi-ui form">
				<?php echo ent2ncr( self::set_nonce() ) ?>

                <div class="vi-ui attached tabular menu">
                    <div class="item" data-tab="design">
						<?php esc_html_e( 'Design', 'woo-lookbook' ) ?>
                    </div>
                    <div class="item " data-tab="product">
						<?php esc_html_e( 'Product', 'woo-lookbook' ) ?>
                    </div>
                    <div class="item " data-tab="instagram">
						<?php esc_html_e( 'Instagram', 'woo-lookbook' ) ?>
                    </div>
                </div>
                <div class="vi-ui bottom attached tab segment" data-tab="product">
                    <!-- Tab Content !-->
                    <table class="optiontable form-table">
                        <tbody>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_field( 'link_redirect' ) ?>">
									<?php esc_html_e( 'Link Redirect', 'woo-lookbook' ) ?>
                                </label>
                            </th>
                            <td>
                                <div class="vi-ui toggle checkbox">
                                    <input id="<?php echo self::set_field( 'link_redirect' ) ?>" type="checkbox" <?php checked( self::get_field( 'link_redirect' ), 1 ) ?>
                                           tabindex="0" class="hidden" value="1" name="<?php echo self::set_field( 'link_redirect' ) ?>"/>
                                    <label></label>
                                </div>
                                <p class="description"><?php esc_html_e( 'Click on Nodes will redirect the page to the single product page.', 'woo-lookbook' ) ?></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_field( 'external_product' ) ?>">
									<?php esc_html_e( 'External Link', 'woo-lookbook' ) ?>
                                </label>
                            </th>
                            <td>
                                <div class="vi-ui toggle checkbox">
                                    <input id="<?php echo self::set_field( 'external_product' ) ?>" type="checkbox" <?php checked( self::get_field( 'external_product' ), 1 ) ?>
                                           tabindex="0" class="hidden" value="1" name="<?php echo self::set_field( 'external_product' ) ?>"/>
                                    <label></label>
                                </div>
                                <p class="description"><?php esc_html_e( 'Click on Nodes will redirect the page to the external link instead of the single product page. Working only with External/Affiliate products.', 'woo-lookbook' ) ?></p>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>

                <!-- Design !-->
                <div class="vi-ui bottom attached tab segment" data-tab="design">
                    <!-- Tab Content !-->
                    <h3><?php esc_html_e( 'Node Options', 'woo-lookbook' ) ?></h3>
                    <table class="optiontable form-table">
                        <tbody>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_field( 'icon' ) ?>">
									<?php esc_html_e( 'Icon', 'woo-lookbook' ) ?>
                                </label>
                            </th>
                            <td>
                                <a class="vi-ui wlb-add-new button blue button-primary" href="https://1.envato.market/mV0bM">
									<?php esc_html_e( 'Update this feature', 'woo-lookbook' ) ?>
                                </a>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label><?php esc_html_e( 'Color', 'woo-lookbook' ) ?></label>
                            </th>
                            <td>
                                <input type="text" class="color-picker" name="<?php echo self::set_field( 'icon_color' ) ?>"
                                       value="<?php echo self::get_field( 'icon_color', '#fff' ) ?>"
                                       style="background-color: <?php echo esc_attr( self::get_field( 'icon_color', '#fff' ) ) ?>"/>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label><?php esc_html_e( 'Background color', 'woo-lookbook' ) ?></label>
                            </th>
                            <td>
                                <input type="text" class="color-picker" name="<?php echo self::set_field( 'icon_background_color' ) ?>"
                                       value="<?php echo self::get_field( 'icon_background_color', '#E8CE40' ) ?>"
                                       style="background-color: <?php echo esc_attr( self::get_field( 'icon_background_color', '#E8CE40' ) ) ?>"/>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label><?php esc_html_e( 'Border color', 'woo-lookbook' ) ?></label>
                            </th>
                            <td>
                                <input type="text" class="color-picker" name="<?php echo self::set_field( 'icon_border_color' ) ?>"
                                       value="<?php echo self::get_field( 'icon_border_color', '#E8CE40' ) ?>"
                                       style="background-color: <?php echo esc_attr( self::get_field( 'icon_border_color', '#E8CE40' ) ) ?>"/>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_field( 'hide_title' ) ?>">
									<?php esc_html_e( 'Hide Title', 'woo-lookbook' ) ?>
                                </label>
                            </th>
                            <td>
                                <div class="vi-ui toggle checkbox">
                                    <input id="<?php echo self::set_field( 'hide_title' ) ?>" type="checkbox" <?php checked( self::get_field( 'hide_title' ), 1 ) ?> tabindex="0"
                                           class="hidden" value="1" name="<?php echo self::set_field( 'hide_title' ) ?>"/>
                                    <label></label>
                                </div>
                                <p class="description"></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label><?php esc_html_e( 'Title Color', 'woo-lookbook' ) ?></label>
                            </th>
                            <td>
                                <input type="text" class="color-picker" name="<?php echo self::set_field( 'title_color' ) ?>"
                                       value="<?php echo self::get_field( 'title_color', '#212121' ) ?>"
                                       style="background-color: <?php echo esc_attr( self::get_field( 'title_color', '#212121' ) ) ?>"/>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label><?php esc_html_e( 'Title Background Color', 'woo-lookbook' ) ?></label>
                            </th>
                            <td>
                                <input type="text" class="color-picker" name="<?php echo self::set_field( 'title_background_color' ) ?>"
                                       value="<?php echo self::get_field( 'title_background_color', '#eee' ) ?>"
                                       style="background-color: <?php echo esc_attr( self::get_field( 'title_background_color', '#212121' ) ) ?>"/>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h3><?php esc_html_e( 'Quick view', 'woo-lookbook' ) ?></h3>
                    <table class="optiontable form-table">
                        <tbody>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_field( 'loading_icon' ) ?>">
									<?php esc_html_e( 'Loading Icon', 'woo-lookbook' ) ?>
                                </label>
                            </th>
                            <td>
                                <a class="vi-ui wlb-add-new button blue button-primary" target="_blank" href="https://1.envato.market/mV0bM">
									<?php esc_html_e( 'Update this feature', 'woo-lookbook' ) ?>
                                </a>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label><?php esc_html_e( 'Text Color', 'woo-lookbook' ) ?></label>
                            </th>
                            <td>
                                <input type="text" class="color-picker" name="<?php echo self::set_field( 'text_color' ) ?>"
                                       value="<?php echo self::get_field( 'text_color', '#E8CE40' ) ?>"
                                       style="background-color: <?php echo esc_attr( self::get_field( 'text_color', '#E8CE40' ) ) ?>"/>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label><?php esc_html_e( 'Background Color', 'woo-lookbook' ) ?></label>
                            </th>
                            <td>
                                <input type="text" class="color-picker" name="<?php echo self::set_field( 'background_color' ) ?>"
                                       value="<?php echo self::get_field( 'background_color', '#fff' ) ?>"
                                       style="background-color: <?php echo esc_attr( self::get_field( 'background_color', '#fff' ) ) ?>"/>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label><?php esc_html_e( 'Border radius', 'woo-lookbook' ) ?></label>
                            </th>
                            <td>
                                <div class="vi-ui form">
                                    <div class="inline fields">
                                        <input type="number" name="<?php echo self::set_field( 'border_radius' ) ?>" value="<?php echo self::get_field( 'border_radius', 0 ) ?>"/>
                                        <label><?php esc_html_e( 'px', 'woo-lookbook' ) ?></label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_field( 'close_button' ) ?>">
									<?php esc_html_e( 'Hide Close Button', 'woo-lookbook' ) ?>
                                </label>
                            </th>
                            <td>
                                <div class="vi-ui toggle checkbox">
                                    <input id="<?php echo self::set_field( 'close_button' ) ?>" type="checkbox" <?php checked( self::get_field( 'close_button' ), 1 ) ?>
                                           tabindex="0" class="hidden" value="1" name="<?php echo self::set_field( 'close_button' ) ?>"/>
                                    <label></label>
                                </div>
                                <p class="description"></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_field( 'see_more' ) ?>">
									<?php esc_html_e( 'Hide See More Button', 'woo-lookbook' ) ?>
                                </label>
                            </th>
                            <td>
                                <div class="vi-ui toggle checkbox">
                                    <input id="<?php echo self::set_field( 'see_more' ) ?>" type="checkbox" <?php checked( self::get_field( 'see_more' ), 1 ) ?> tabindex="0"
                                           class="hidden" value="1" name="<?php echo self::set_field( 'see_more' ) ?>"/>
                                    <label></label>
                                </div>
                                <p class="description"></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_field( 'rtl' ) ?>">
									<?php esc_html_e( 'RTL', 'woo-lookbook' ) ?>
                                </label>
                            </th>
                            <td>
                                <a class="vi-ui wlb-add-new button blue button-primary" target="_blank" href="https://1.envato.market/mV0bM">
									<?php esc_html_e( 'Update this feature', 'woo-lookbook' ) ?>
                                </a>
                                <p class="description"><?php esc_html_e( 'Support RTL fully', 'woo-lookbook' ) ?></p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h3><?php esc_html_e( 'Slide Options', 'woo-lookbook' ) ?></h3>
                    <table class="optiontable form-table">
                        <tbody>
                        <tr valign="top">
                            <th scope="row">
                                <label><?php esc_html_e( 'Width', 'woo-lookbook' ) ?></label>
                            </th>
                            <td>
                                <div class="vi-ui form">
                                    <div class="inline fields">
                                        <input type="number" name="<?php echo self::set_field( 'slide_width' ) ?>" value="<?php echo self::get_field( 'slide_width', 1170 ) ?>"/>
                                        <label><?php esc_html_e( 'px', 'woo-lookbook' ) ?></label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label><?php esc_html_e( 'Height', 'woo-lookbook' ) ?></label>
                            </th>
                            <td>
                                <div class="vi-ui form">
                                    <div class="inline fields">
                                        <input type="number" name="<?php echo self::set_field( 'slide_height' ) ?>" value="<?php echo self::get_field( 'slide_height', 600 ) ?>"/>
                                        <label><?php esc_html_e( 'px', 'woo-lookbook' ) ?></label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_field( 'slide_effect' ) ?>">
									<?php esc_html_e( 'Effect', 'woo-lookbook' ) ?>
                                </label>
                            </th>
                            <td>
                                <select name="<?php echo self::set_field( 'slide_effect' ) ?>" class="vi-ui fluid dropdown">
                                    <option <?php selected( self::get_field( 'slide_effect' ), 0 ) ?> value="0"><?php esc_attr_e( 'Slide', 'woo-lookbook' ) ?></option>
                                    <option <?php selected( self::get_field( 'slide_effect' ), 1 ) ?> value="1"><?php esc_attr_e( 'Fade', 'woo-lookbook' ) ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_field( 'slide_pagination' ) ?>">
									<?php esc_html_e( 'Slide Pagination', 'woo-lookbook' ) ?>
                                </label>
                            </th>
                            <td>
                                <div class="vi-ui toggle checkbox">
                                    <input id="<?php echo self::set_field( 'slide_pagination' ) ?>" type="checkbox" <?php checked( self::get_field( 'slide_pagination' ), 1 ) ?>
                                           tabindex="0" class="hidden" value="1" name="<?php echo self::set_field( 'slide_pagination' ) ?>"/>
                                    <label></label>
                                </div>
                                <p class="description"></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_field( 'slide_navigation' ) ?>">
									<?php esc_html_e( 'Slide Navigation', 'woo-lookbook' ) ?>
                                </label>
                            </th>
                            <td>
                                <div class="vi-ui toggle checkbox">
                                    <input id="<?php echo self::set_field( 'slide_navigation' ) ?>" type="checkbox" <?php checked( self::get_field( 'slide_navigation' ), 1 ) ?>
                                           tabindex="0" class="hidden" value="1" name="<?php echo self::set_field( 'slide_navigation' ) ?>"/>
                                    <label></label>
                                </div>
                                <p class="description"></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_field( 'slide_auto_play' ) ?>">
									<?php esc_html_e( 'Auto play', 'woo-lookbook' ) ?>
                                </label>
                            </th>
                            <td>
                                <div class="vi-ui toggle checkbox">
                                    <input id="<?php echo self::set_field( 'slide_auto_play' ) ?>" type="checkbox" <?php checked( self::get_field( 'slide_auto_play' ), 1 ) ?>
                                           tabindex="0" class="hidden" value="1" name="<?php echo self::set_field( 'slide_auto_play' ) ?>"/>
                                    <label></label>
                                </div>
                                <p class="description"></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label><?php esc_html_e( 'Duration', 'woo-lookbook' ) ?></label>
                            </th>
                            <td>
                                <div class="vi-ui form">
                                    <div class="inline fields">
                                        <input type="number" name="<?php echo self::set_field( 'slide_time' ) ?>" value="<?php echo self::get_field( 'slide_time', 5000 ) ?>"/>
                                        <label><?php esc_html_e( 'milliseconds', 'woo-lookbook' ) ?></label>
                                    </div>
                                </div>
                                <p class="description"><?php esc_html_e( 'Specify a time to advance to the next lookbook.', 'woo-lookbook' ) ?></p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h3><?php esc_html_e( 'Custom Script', 'woo-lookbook' ) ?></h3>
                    <table class="optiontable form-table">
                        <tbody>
                        <tr valign="top">
                            <th scope="row">
                                <label><?php esc_html_e( 'Custom CSS', 'woo-lookbook' ) ?></label>
                            </th>
                            <td>
                                <textarea type="text" name="<?php echo self::set_field( 'custom_css' ) ?>"><?php echo self::get_field( 'custom_css' ) ?></textarea>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Instagram !-->
                <div class="vi-ui bottom attached tab segment" data-tab="instagram">
                    <h3>How to get Instagram Access Token.</h3>
                    <ul>
                        <li>
				            <?php
				            $guide = esc_html__( '1. Create Facebook App at ', 'woocommerce-lookbook' );
				            $guide .= '<a target="_blank" href="https://developers.facebook.com/">https://developers.facebook.com/</a>';
				            $guide .= '</li><li>';
				            $guide .= esc_html__( '2. Add Facebook login & Instagram module', 'woocommerce-lookbook' );
				            $guide .= '</li><li>';
				            $guide .= esc_html__( '3. Copy ', 'woocommerce-lookbook' );
				            $guide .= '<strong>' . admin_url( 'edit.php?post_type=woocommerce-lookbook&page=woocommerce-lookbook-settings#/instagram' ) . '</strong>';
				            $guide .= esc_html__( ' to Facebook Login > Settings > Valid OAuth Redirect URIs', 'woocommerce-lookbook' );
				            echo $guide;
				            ?>
                        </li>
                        <li>
                            <iframe width="560" height="315" src="https://www.youtube.com/embed/109jLhPIokY" frameborder="0"
                                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </li>
                    </ul>
                    <!-- Tab Content !-->
                    <table class="optiontable form-table">
                        <tbody>

                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_field( 'ins_client_id' ) ?>">
									<?php esc_html_e( 'Client ID', 'woocommerce-lookbook' ) ?>
                                </label>
                            </th>
                            <td>
                                <input type="text" name="<?php echo self::set_field( 'ins_client_id' ) ?>"
                                       value="<?php echo self::get_field( 'ins_client_id', '' ) ?>"/>

                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_field( 'ins_client_secret' ) ?>">
		                            <?php esc_html_e( 'Client Secret', 'woocommerce-lookbook' ) ?>
                                </label>
                            </th>
                            <td>
                                <input type="text" name="<?php echo self::set_field( 'ins_client_secret' ) ?>"
                                       value="<?php echo self::get_field( 'ins_client_secret', '' ) ?>"/>
                            </td>
                        </tr>


			            <?php
			            $access_token  = self::get_field( 'ins_access_token' );
			            $instagram     = new VillaTheme_Instagram();
			            $instagram->fb = $instagram->fb_connect();
			            $check_token   = $instagram->check_token_live( $access_token );
			            $access_token  = $check_token ? $access_token : '';
			            ?>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_field( 'ins_access_token' ) ?>">
						            <?php esc_html_e( 'Access Token', 'woocommerce-lookbook' ) ?>
                                </label>
                            </th>
                            <td>
                                <input type="hidden" name="<?php echo self::set_field( 'ins_page_id' ) ?>"
                                       value="<?php echo self::get_field( 'ins_page_id', '' ) ?>"/>
                                <input type="text" name="<?php echo self::set_field( 'ins_access_token' ) ?>"
                                       value="<?php echo esc_attr( $access_token ) ?>"/>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row"></th>
                            <td>
					            <?php
					            if ( self::get_field( 'ins_client_id', '' ) && self::get_field( 'ins_client_secret', '' ) ) {
						            if ( ! $access_token ) {
							            $link_call_back = add_query_arg(
								            array( 'post_type' => 'woocommerce-lookbook', 'page' => 'woocommerce-lookbook-settings#/instagram' ),
								            admin_url( 'edit.php' )
							            );

							            $link_login = $instagram->get_link_login(
								            $link_call_back,
								            array(
									            'public_profile',
									            'instagram_manage_comments',
									            'instagram_basic',
									            'pages_show_list',
									            'pages_read_engagement'
								            ) );
							            ?>
                                        <a href="<?php echo esc_url( $link_login ) ?>"
                                           class="vi-ui button green"><?php esc_html_e( 'Get Access Token', 'woocommerce-lookbook' ) ?></a>
						            <?php }
					            } ?>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_field( 'ins_display' ) ?>">
						            <?php esc_html_e( 'Display', 'woo-lookbook' ) ?>
                                </label>
                            </th>
                            <td>
                                <select name="<?php echo self::set_field( 'ins_display' ) ?>" class="vi-ui fluid dropdown">
                                    <option <?php selected( self::get_field( 'ins_display' ), 0 ) ?> value="0"><?php esc_attr_e( 'Gallery', 'woo-lookbook' ) ?></option>
                                    <option <?php selected( self::get_field( 'ins_display' ), 1 ) ?> value="1"><?php esc_attr_e( 'Carousel', 'woo-lookbook' ) ?></option>
                                </select>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">
                                <label><?php esc_html_e( 'Number items per row', 'woo-lookbook' ) ?></label>
                            </th>
                            <td>
                                <select name="<?php echo self::set_field( 'ins_items_per_row' ) ?>" class="vi-ui fluid dropdown">
                                    <option <?php selected( self::get_field( 'ins_items_per_row' ), 3 ) ?> value="3"><?php esc_attr_e( '3', 'woo-lookbook' ) ?></option>
                                    <option <?php selected( self::get_field( 'ins_items_per_row' ), 4 ) ?> value="4"><?php esc_attr_e( '4', 'woo-lookbook' ) ?></option>
                                    <option <?php selected( self::get_field( 'ins_items_per_row' ), 5 ) ?> value="5"><?php esc_attr_e( '5', 'woo-lookbook' ) ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label><?php esc_html_e( 'Display limit', 'woo-lookbook' ) ?></label>
                            </th>
                            <td>
                                <input type="number" name="<?php echo self::set_field( 'ins_display_limit' ) ?>" value="<?php echo self::get_field( 'ins_display_limit', 12 ) ?>"/>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_field( 'ins_link' ) ?>">
									<?php esc_html_e( 'Link to Instagram', 'woo-lookbook' ) ?>
                                </label>
                            </th>
                            <td>
                                <div class="vi-ui toggle checkbox">
                                    <input id="<?php echo self::set_field( 'ins_link' ) ?>" type="checkbox" <?php checked( self::get_field( 'ins_link' ), 1 ) ?> tabindex="0"
                                           class="hidden" value="1" name="<?php echo self::set_field( 'ins_link' ) ?>"/>
                                    <label></label>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h3><?php esc_html_e( 'Synchronize', 'woo-lookbook' ) ?></h3>
                    <table class="optiontable form-table">
                        <tbody>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_field( 'ins_schedule' ) ?>">
									<?php esc_html_e( 'Schedule', 'woo-lookbook' ) ?>
                                </label>
                            </th>
                            <td>
                                <a class="vi-ui wlb-add-new button blue button-primary" target="_blank" href="https://1.envato.market/mV0bM">
									<?php esc_html_e( 'Update this feature', 'woo-lookbook' ) ?>
                                </a>
                                <p class="description"><?php esc_html_e( 'The action will trigger when someone visits your WordPress site.', 'woo-lookbook' ) ?></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_field( 'ins_image_status' ) ?>">
									<?php esc_html_e( 'Image Status', 'woo-lookbook' ) ?>
                                </label>
                            </th>
                            <td>
                                <a class="vi-ui wlb-add-new button blue button-primary" target="_blank" href="https://1.envato.market/mV0bM">
									<?php esc_html_e( 'Update this feature', 'woo-lookbook' ) ?>
                                </a>
                                <p class="description"><?php esc_html_e( 'Lookbooks status after images are imported.', 'woo-lookbook' ) ?></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_field( 'ins_duplicate' ) ?>">
									<?php esc_html_e( 'Data Update', 'woo-lookbook' ) ?>
                                </label>
                            </th>
                            <td>
                                <a class="vi-ui wlb-add-new button blue button-primary" target="_blank" href="https://1.envato.market/mV0bM">
									<?php esc_html_e( 'Update this feature', 'woo-lookbook' ) ?>
                                </a>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="<?php echo self::set_field( 'ins_image_quantity' ) ?>">
									<?php esc_html_e( 'Image Quantity', 'woo-lookbook' ) ?>
                                </label>
                            </th>
                            <td>
                                <a class="vi-ui wlb-add-new button blue button-primary" target="_blank" href="https://1.envato.market/mV0bM">
									<?php esc_html_e( 'Update this feature', 'woo-lookbook' ) ?>
                                </a>
                                <p class="description"><?php esc_html_e( 'List images are get from API. The fewer quantity sync faster.', 'woo-lookbook' ) ?></p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <p>
                    <button class="vi-ui button labeled icon primary wlb-submit">
                        <i class="send icon"></i> <?php esc_html_e( 'Save', 'woo-lookbook' ) ?>
                    </button>
                </p>
            </form>
        </div>
		<?php
		do_action( 'villatheme_support_woo-lookbook' );
	}
} ?>