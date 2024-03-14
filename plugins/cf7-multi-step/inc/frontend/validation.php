<?php
if ( ! class_exists( 'NjtCF7MLSValidation' ) ) {
	class NjtCF7MLSValidation {

		private $invalid_fields = array();

		public function __construct() {
			$this->doHooks();
		}

		private function doHooks() {
			add_action( 'rest_api_init', array( $this, 'register_routes' ) );
		}

		public function register_routes() {
			$callback = apply_filters( 'cf7mls_validation_callback', 'cf7mls_validation_callback' );
			if ( function_exists( 'cf7msm_fs' ) ) {
				$callback = apply_filters( 'cf7mls_validation_callback', 'cf7mls_validation_callback_new' );
			}
			register_rest_route(
				'v1',
				'cf7mls_validation',
				array(
					array(
						'methods'             => \WP_REST_Server::CREATABLE,
						'callback'            => array( $this, $callback ),
						'permission_callback' => '__return_true',
					),
				)
			);
		}

		function cf7mls_validation_callback_new() {
			global $wpdb;
			$id = (int) $_POST['_wpcf7'];

			$item = null;

			if ( ! empty( $id ) ) {
				$item = wpcf7_contact_form( $id );

			}

			if ( ! $item ) {
				return new WP_Error(
					'wpcf7_not_found',
					__( 'The requested contact form was not found.', 'contact-form-7' ),
					array( 'status' => 404 )
				);
			}

			$unit_tag = wpcf7_sanitize_unit_tag( $_POST['_wpcf7_unit_tag'] );

			$result   = $item->submit();
			$response = array_merge(
				$result,
				array(
					'into'           => sprintf( '#%s', $unit_tag ),
					'invalid_fields' => array(),
				)
			);

			if ( ! empty( $result['invalid_fields'] ) ) {
				$invalid_fields = array();

				foreach ( (array) $result['invalid_fields'] as $name => $field ) {
					if ( ! wpcf7_is_name( $name ) ) {
						continue;
					}

					$name = strtr( $name, '.', '_' );

					$invalid_fields[] = array(
						'field'    => $name,
						'message'  => $field['reason'],
						'idref'    => $field['idref'],
						'error_id' => sprintf(
							'%1$s-ve-%2$s',
							$unit_tag,
							$name
						),
					);
				}

				$response['invalid_fields'] = $invalid_fields;
			}

			$messages            = $item->prop( 'messages' );
			$response['message'] = $messages['validation_error'];

			$response['success'] = count( $response['invalid_fields'] ) <= 0;

			$invalid_fields_arr         = $response['invalid_fields'];
			$response['invalid_fields'] = array();
			if ( count( $invalid_fields_arr ) > 0 ) {
				foreach ( $invalid_fields_arr as $key => $val ) {
					$response['invalid_fields'][ $val['field'] ] = array(
						'idref'  => $val['idref'],
						'reason' => $val['message'],
					);
				}
			}
			if ( cf7mls_is_active_cf7db() ) {
				if ( get_post_meta( $id, '_cf7mls_db_save_every_step', true ) == 'yes' ) {
					$_cf7mls_db_form_data_id = ( isset( $_POST['_cf7mls_db_form_data_id'] ) ? intval( $_POST['_cf7mls_db_form_data_id'] ) : '' );
					if ( empty( $_cf7mls_db_form_data_id ) ) {
						$wpdb->insert( $wpdb->prefix . 'cf7_data', array( 'created' => date( 'Y-m-d H:i:s' ) ), array( '%s' ) );
						$_cf7mls_db_form_data_id = $wpdb->insert_id;
						$data                    = array(
							'cf7_id'  => (int) $id,
							'data_id' => (int) $_cf7mls_db_form_data_id,
							'name'    => '_cf7mls_db_form_data_id',
							'value'   => $_cf7mls_db_form_data_id,
						);
						$format                  = array( '%d', '%d', '%s', '%s' );
						$wpdb->insert( $wpdb->prefix . 'cf7_data_entry', $data, $format );
					}

					/*
					* Insert / update to database
					*/
					$contact_form = cf7d_get_posted_data( $item );

					// Modify $contact_form
					$contact_form = apply_filters( 'cf7d_modify_form_before_insert_data', $contact_form );
					$tags         = $contact_form->WPCF7_ContactForm->scan_form_tags();

					$posted_data = $_POST;
					$posted_data = $this->cf7mls_cf7d_add_more_fields( $posted_data );

					foreach ( $tags as $k => $v ) {
						if ( isset( $posted_data[ $v['name'] ] ) ) {
							$posted_data[ $v['name'] ] = $this->cf7mls_sanitize_posted_data( $posted_data[ $v['name'] ] );
						} else {
							unset( $posted_data[ $v['name'] ] );
						}
					}
					// install to database
					$cf7d_no_save_fields = cf7d_no_save_fields();
					foreach ( $posted_data as $k => $v ) {
						if ( in_array( $k, $cf7d_no_save_fields ) ) {
							continue;
						} else {
							if ( is_array( $v ) ) {
								$v = implode( ' ', $v );
							}
							$check_existing = $wpdb->get_results( 'SELECT `id` FROM ' . $wpdb->prefix . 'cf7_data_entry WHERE `cf7_id` = ' . (int) $id . ' AND `data_id` = ' . (int) $_cf7mls_db_form_data_id . " AND `name` = '" . $k . "'" );
							if ( count( $check_existing ) > 0 ) {
								/* Update */
								$data         = array(
									'value' => $v,
								);
								$data_format  = array( '%s' );
								$where        = array(
									'cf7_id'  => (int) $id,
									'data_id' => (int) $_cf7mls_db_form_data_id,
									'name'    => $k,
								);
								$where_format = array( '%d', '%d', '%s' );
								$wpdb->update( $wpdb->prefix . 'cf7_data_entry', $data, $where, $data_format, $where_format );
							} else {
								/* Insert */
								$data   = array(
									'cf7_id'  => (int) $id,
									'data_id' => (int) $_cf7mls_db_form_data_id,
									'name'    => $k,
									'value'   => $v,
								);
								$format = array( '%d', '%d', '%s', '%s' );
								$wpdb->insert( $wpdb->prefix . 'cf7_data_entry', $data, $format );
							}
						}
					}
					$response['_cf7mls_db_form_data_id'] = (int) $_cf7mls_db_form_data_id;
				}
			}
			return new \WP_REST_Response( $response );
		}
		function cf7mls_validation_callback() {
			global $wpdb;
			if ( isset( $_POST['_wpcf7'] ) ) {
				$id = (int) $_POST['_wpcf7'];

				$item = null;

				if ( ! empty( $id ) ) {
					$item = wpcf7_contact_form( $id );

				}

				if ( ! $item ) {
					return new WP_Error(
						'wpcf7_not_found',
						__( 'The requested contact form was not found.', 'contact-form-7' ),
						array( 'status' => 404 )
					);
				}

				$unit_tag = wpcf7_sanitize_unit_tag( $_POST['_wpcf7_unit_tag'] );

				$spam = false;
				if ( $contact_form = wpcf7_contact_form( $id ) ) {
					$items = array(
						'mailSent' => false,
						'into'     => '#' . $unit_tag,
						'captcha'  => null,
					);
					/* Begin validation */
					require_once WPCF7_PLUGIN_DIR . '/includes/validation.php';

					if ( $this->invalid_fields ) {
						return false;
					}

					$result = new WPCF7_Validation();

					if ( (float) WPCF7_VERSION >= 5.6 ) {
						$contact_form->validate_schema(
							array(
								'text'  => true,
								'file'  => false,
								'field' => array(),
							),
							$result
						);
					}

					$tags = $contact_form->scan_form_tags(
						array(
							'feature' => '! file-uploading',
						)
					);

					foreach ( $tags as $tag ) {
						$type   = $tag->type;
						$result = apply_filters( "wpcf7_validate_{$type}", $result, $tag );
					}

					$result = apply_filters( 'wpcf7_validate', $result, $tags );

					$this->invalid_fields = $result->get_invalid_fields();

					$upload_files         = $this->unship_uploaded_files( $contact_form );
					$success              = false;
					$this->invalid_fields = array_merge( $this->invalid_fields, $upload_files['invalid_fields'] );
					if ( false === $result->is_valid() ) {
						$success = $result->is_valid();
					} else {
						$success = $upload_files['valid'];
					}
					$return = array(
						'success'        => $success,
						'invalid_fields' => $this->invalid_fields,
					);

					if ( $return['success'] == false ) {
						$messages          = $contact_form->prop( 'messages' );
						$return['message'] = $messages['validation_error'];
						if ( empty( $return['message'] ) ) {
							$default_messages  = wpcf7_messages();
							$return['message'] = $default_messages['validation_error']['default'];
						}
					} else {
						$return['message'] = '';
					}
					if ( cf7mls_is_active_cf7db() ) {
						if ( get_post_meta( $id, '_cf7mls_db_save_every_step', true ) == 'yes' ) {
							$_cf7mls_db_form_data_id = ( isset( $_POST['_cf7mls_db_form_data_id'] ) ? intval( $_POST['_cf7mls_db_form_data_id'] ) : '' );
							if ( empty( $_cf7mls_db_form_data_id ) ) {
								$wpdb->insert( $wpdb->prefix . 'cf7_data', array( 'created' => date( 'Y-m-d H:i:s' ) ), array( '%s' ) );
								$_cf7mls_db_form_data_id = $wpdb->insert_id;
								$data                    = array(
									'cf7_id'  => (int) $id,
									'data_id' => (int) $_cf7mls_db_form_data_id,
									'name'    => '_cf7mls_db_form_data_id',
									'value'   => $_cf7mls_db_form_data_id,
								);
								$format                  = array( '%d', '%d', '%s', '%s' );
								$wpdb->insert( $wpdb->prefix . 'cf7_data_entry', $data, $format );
							}

							/*
							* Insert / update to database
							*/
							$contact_form = cf7d_get_posted_data( $item );

							// Modify $contact_form
							$contact_form = apply_filters( 'cf7d_modify_form_before_insert_data', $contact_form );
							$tags         = $contact_form->scan_form_tags();

							$posted_data = $_POST;
							$posted_data = $this->cf7mls_cf7d_add_more_fields( $posted_data );

							foreach ( $tags as $k => $v ) {
								if ( isset( $posted_data[ $v['name'] ] ) ) {
									$posted_data[ $v['name'] ] = $this->cf7mls_sanitize_posted_data( $posted_data[ $v['name'] ] );
								} else {
									unset( $posted_data[ $v['name'] ] );
								}
							}
							// install to database
							$cf7d_no_save_fields = cf7d_no_save_fields();
							foreach ( $posted_data as $k => $v ) {
								if ( in_array( $k, $cf7d_no_save_fields ) ) {
									continue;
								} else {
									if ( is_array( $v ) ) {
										$v = implode( ' ', $v );
									}
									$check_existing = $wpdb->get_results( 'SELECT `id` FROM ' . $wpdb->prefix . 'cf7_data_entry WHERE `cf7_id` = ' . (int) $id . ' AND `data_id` = ' . (int) $_cf7mls_db_form_data_id . " AND `name` = '" . $k . "'" );
									if ( count( $check_existing ) > 0 ) {
										/* Update */
										$data         = array(
											'value' => $v,
										);
										$data_format  = array( '%s' );
										$where        = array(
											'cf7_id'  => (int) $id,
											'data_id' => (int) $_cf7mls_db_form_data_id,
											'name'    => $k,
										);
										$where_format = array( '%d', '%d', '%s' );
										$wpdb->update( $wpdb->prefix . 'cf7_data_entry', $data, $where, $data_format, $where_format );
									} else {
										/* Insert */
										$data   = array(
											'cf7_id'  => (int) $id,
											'data_id' => (int) $_cf7mls_db_form_data_id,
											'name'    => $k,
											'value'   => $v,
										);
										$format = array( '%d', '%d', '%s', '%s' );
										$wpdb->insert( $wpdb->prefix . 'cf7_data_entry', $data, $format );
									}
								}
							}
							$return['_cf7mls_db_form_data_id'] = (int) $_cf7mls_db_form_data_id;
						}
					}
					return new \WP_REST_Response( $return );

				}
			}
		}
		public function cf7mls_sanitize_posted_data( $value ) {
			if ( is_array( $value ) ) {
				$value = array_map( array( $this, 'cf7mls_sanitize_posted_data' ), $value );
			} elseif ( is_string( $value ) ) {
				$value = wp_check_invalid_utf8( $value );
				$value = wp_kses_no_null( $value );
			}
			return $value;
		}
		public function cf7mls_cf7d_add_more_fields( $posted_data ) {
			// time
			$posted_data['submit_time'] = date( 'Y-m-d H:i:s' );
			// ip
			$posted_data['submit_ip'] = ( isset( $_SERVER['X_FORWARDED_FOR'] ) ) ? $_SERVER['X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
			// user id
			$posted_data['submit_user_id'] = 0;
			if ( function_exists( 'is_user_logged_in' ) && is_user_logged_in() ) {
				$current_user                  = wp_get_current_user(); // WP_User
				$posted_data['submit_user_id'] = $current_user->ID;
			}
			return $posted_data;
		}
		private function unship_uploaded_files( $contact_form ) {
			$file_result = new WPCF7_Validation();

			$file_tags = $contact_form->scan_form_tags(
				array(
					'feature' => 'file-uploading',
				)
			);

			foreach ( $file_tags as $tag ) {
				if ( empty( $_FILES[ $tag->name ] ) ) {
					continue;
				}
				$file = $_FILES[ $tag->name ];

				if ( false != $tag->is_required() ) {

					$args = array(
						'tag'       => $tag,
						'name'      => $tag->name,
						'required'  => $tag->is_required(),
						'filetypes' => $tag->get_option( 'filetypes' ),
						'limit'     => $tag->get_limit_option(),
					);

					if ( (float) WPCF7_VERSION >= 5.6 ) {
						$args['schema'] = $contact_form->get_schema();
					}

					$new_files = wpcf7_unship_uploaded_file( $file, $args );

					if ( (float) WPCF7_VERSION >= 5.6 ) {
						if ( is_wp_error( $new_files ) ) {
							$file_result->invalidate( $tag, $new_files );
						}
					}

					$file_result = apply_filters(
						"wpcf7_validate_{$tag->type}",
						$file_result,
						$tag,
						array(
							'uploaded_files' => $new_files,
						)
					);
				}
			}

			$file_invalid_fields = $file_result->get_invalid_fields();
			 return array(
				 'valid'          => $file_result->is_valid(),
				 'invalid_fields' => $file_invalid_fields,
			 );
		}
	}
	new NjtCF7MLSValidation();
}
