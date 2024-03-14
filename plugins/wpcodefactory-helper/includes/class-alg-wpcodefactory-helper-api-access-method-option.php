<?php
/**
 * WPFactory Helper - Admin - API access method option
 *
 * @version 1.5.8
 * @since   1.5.8
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WPCodeFactory_Helper_API_Access_Method_Option' ) ) {

	class Alg_WPCodeFactory_Helper_API_Access_Method_Option {
		/**
		 * init.
		 *
		 * @version 1.5.8
		 * @since   1.5.8
		 *
		 * @return void
		 */
		function init() {
			add_filter( 'wpfactory_helper_plugins_table_html_before', array( $this, 'generate_option_html' ), 10 );
			add_filter( 'wpfactory_helper_plugins_table_html_before', array( $this, 'handle_option_js' ), 11 );
			add_action( 'wp_ajax_save_api_access_method_option', array( $this, 'save_option_via_ajax' ) );
		}

		/**
		 * save_option.
		 *
		 * @version 1.5.8
		 * @since   1.5.8
		 *
		 * @return void
		 */
		function save_option_via_ajax() {
			check_ajax_referer( 'wpfh-api-access-method-option-nonce', 'security' );
			$args = wp_parse_args( $_POST, array(
				'selected_value' => '',
			) );
			if ( in_array( $args['selected_value'], array_keys( $this->get_possible_options() ) ) ) {
				update_option( 'alg_wpcodefactory_helper_api_access_method', $args['selected_value'] );
				wp_send_json_success();
			}
			wp_send_json_error();
		}

		/**
		 * handle_option_js.
		 *
		 * @version 1.5.8
		 * @since   1.5.8
		 *
		 * @param $html
		 *
		 * @return string
		 */
		function handle_option_js( $html ) {
			ob_start();
			$php_to_js = array(
				'security' => wp_create_nonce( 'wpfh-api-access-method-option-nonce' ),
				'action'   => 'save_api_access_method_option'
			);
			?>
            <script>
                jQuery(document).ready(function ($) {
                    let dataFromPHP = <?php echo json_encode( $php_to_js );?>;
                    let selectElement = document.getElementById('wpfh_api_access_method');
                    selectElement.addEventListener('change', function (event) {
                        let selectedValue = event.target.value;
                        let data = {
                            action: dataFromPHP.action,
                            security: dataFromPHP.security,
                            selected_value: selectedValue,
                        };
                        $.post(ajaxurl, data, function (response) {
                        });
                    });
                });
            </script>
			<?php

			$html .= ob_get_contents();
			ob_end_clean();


			return $html;
		}

		/**
		 * get_possible_options.
		 *
		 * @version 1.5.8
		 * @since   1.5.8
		 *
		 * @return array
		 */
		function get_possible_options() {
			return array(
				'curl'              => __( 'CURL' ),
				'file_get_contents' => __( 'file_get_contents' ),
			);
		}

		/**
		 * generate_option_html.
		 *
		 * @version 1.5.8
		 * @since   1.5.8
		 *
		 * @return string
		 */
		function generate_option_html( $html ) {
			ob_start();
			$possible_options = $this->get_possible_options();
			$option_val       = get_option( 'alg_wpcodefactory_helper_api_access_method', 'file_get_contents' );
			?>
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row">
                        <label for="wpfh_api_access_method"><?php _e( 'API access method', 'wpcodefactory-helper' ); ?></label>
                    </th>
                    <td>
                        <select name="wpfh_api_access_method" id="wpfh_api_access_method" class="postform">
							<?php foreach ( $possible_options as $option_id => $option_label ): ?>
                                <option value="<?php echo esc_attr( $option_id ); ?>"
									<?php selected( $option_val, $option_id ); ?>><?php echo esc_html( $option_label ); ?>
                                </option>
							<?php endforeach; ?>
                        </select>
                        <p class="description"
                           id="new-admin-email-description"><?php _e( 'The preferred way for accessing WPFactory API. If something goes wrong, change it.', 'wpcodefactory-helper' ) ?>
                        </p>
                    </td>
                </tr>
                </tbody>
            </table>
			<?php
			$html .= ob_get_contents();
			ob_end_clean();


			return $html;
		}
	}
}