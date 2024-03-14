<?php
/**
 * Iubenda Forms class.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Iubenda_Forms class.
 *
 * @class Iubenda_Forms
 */
class Iubenda_Forms {

	/**
	 * Sources.
	 *
	 * @var array
	 */
	public $sources = array();

	/**
	 * Statuses.
	 *
	 * @var array
	 */
	public $statuses = array();

	/**
	 * Class constructor.
	 */
	public function __construct() {
		// actions.
		add_action( 'plugins_loaded', array( $this, 'init' ) );
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'register_post_status' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		// Save cons for non ajax forms.
		add_action( 'wpforms_process_complete', array( $this, 'process_entry_for_wp_forms' ), 10, 2 );
		add_filter( 'mc4wp_integration_woocommerce_checkbox_attributes', array( $this, 'mc4wp_integration_woocommerce_checkbox_attributes' ) );
	}

	/**
	 * Process entry for WP forms
	 *
	 * @param   array $fields  Sanitized entry field values/properties.
	 * @param   array $entry   Original $_POST global.
	 */
	public function process_entry_for_wp_forms( $fields, $entry ) {
		global $wp_version;
		$public_api_key = iubenda()->options['cons']['public_api_key'];

		// Escape on ajax request because it will be handled by injected JS "frontend.js".
		// Or escape if the public api key is not defined.
		// Check current WP version is newer than 4.7 to use the wp_doing_ajax function.
		if ( ( version_compare( $wp_version, '4.7', '>=' ) && wp_doing_ajax() ) || ! $public_api_key ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$form_id = isset( $_POST['wpforms']['id'] ) ? absint( $_POST['wpforms']['id'] ) : 0;

		$form_args = array(
			'post_status' => array( 'mapped' ),
			'source'      => 'wpforms',
			'id'          => $form_id,
		);

		$form = $this->get_form_by_object_id( $form_args );

		if ( ! $form ) {
			return;
		}

		$data = array();
		// Prepare form subjects.
		foreach ( array( 'subject', 'preferences', 'legal_notices' ) as $key ) {
			$data = $this->prepare_data_for_wp_forms( $form, $key, $entry, $data );
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing
		$data['proofs'][0]['content'] = wp_json_encode( $_POST );

		wp_remote_post(
			iubenda()->options['cons']['cons_endpoint'],
			array(
				'body'    => wp_json_encode( $data ),
				'headers' => array(
					'apikey'       => $public_api_key,
					'Content-Type' => 'application/json',
				),
			)
		);
	}

	/**
	 * Initialize forms data.
	 *
	 * @return void
	 */
	public function init() {
		// WordPress commenting form.
		$this->sources['wp_comment_form'] = 'WordPress Comment';

		// check if Contact Form 7 is active.
		if ( class_exists( 'WPCF7' ) ) {
			$this->sources['wpcf7'] = 'Contact Form 7';
		}

		// check if WP Forms is active.
		if ( function_exists( 'wpforms' ) ) {
			$this->sources['wpforms'] = 'WP Forms';
		}

		// check if EooCommerce is active.
		if ( function_exists( 'WC' ) ) {
			$this->sources['woocommerce'] = 'WooCommerce Checkout';
		}

		// check if Elementor forms is active.
		if ( class_exists( 'ElementorPro\Modules\Forms\Module' ) ) {
			$this->sources['elementor_forms'] = 'Elementor forms';
		}

		$this->sources = apply_filters( 'iub_supported_form_sources', $this->sources );

		$this->statuses = array(
			'publish'      => _x( 'To Map', 'post status', 'iubenda' ),
			'mapped'       => _x( 'Mapped', 'post status', 'iubenda' ),
			'needs_update' => _x( 'Needs Update', 'post status', 'iubenda' ),
		);
	}

	/**
	 * Enqueue frontend script.
	 */
	public function wp_enqueue_scripts() {
		if ( ! empty( iubenda()->options['cons']['public_api_key'] ) && ( new Product_Helper() )->is_cons_service_enabled() ) {
			wp_register_script( 'iubenda-forms', IUBENDA_PLUGIN_URL . '/assets/js/frontend.js', array( 'jquery' ), iubenda()->version, true );

			$args = array();

			$form_args = array(
				'post_status' => array( 'mapped' ),
			);

			$forms = $this->get_forms( $form_args );

			if ( ! empty( $forms ) ) {
				$args = $this->prepare_mapped_forms( $forms, $args );
			}

			wp_localize_script(
				'iubenda-forms',
				'iubForms',
				$args
			);

			wp_enqueue_script( 'iubenda-forms' );
		}
	}

	/**
	 * Register iubenda form post type.
	 */
	public function register_post_type() {
		register_post_type(
			'iubenda_form',
			array(
				'labels'          => array(
					'name'          => __( 'Forms', 'iubenda' ),
					'singular_name' => __( 'Form', 'iubenda' ),
				),
				'rewrite'         => false,
				'query_var'       => false,
				'public'          => false,
				'capability_type' => 'page',
			)
		);
	}

	/**
	 * Register iubenda form post status.
	 */
	public function register_post_status() {
		foreach ( $this->statuses as $name => $label ) {
			if ( 'publish' === (string) $name ) {
				continue;
			}

			register_post_status(
				$name,
				array(
					'label'                     => $label,
					'public'                    => true,
					'exclude_from_search'       => false,
					'show_in_admin_all_list'    => true,
					'show_in_admin_status_list' => true,
					'post_type'                 => array( 'iubenda_form' ),
				)
			);
		}
	}

	/**
	 * Get iubenda forms function.
	 *
	 * @param   array $args  args.
	 *
	 * @return array
	 */
	public function get_forms( $args = array() ) {
		$defaults = array(
			'post_status'    => 'any',
			'posts_per_page' => - 1,
			'offset'         => 0,
			'orderby'        => 'ID',
			'order'          => 'ASC',
			'form_source'    => 'any',
		);

		$args = wp_parse_args( $args, $defaults );

		$args['post_type'] = 'iubenda_form';

		// specific sources only.
		if ( 'any' !== $args['form_source'] && ( is_string( $args['form_source'] ) || is_array( $args['form_source'] ) ) ) {
			$args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => '_iub_form_source',
					'value'   => $args['form_source'],
					'compare' => 'IN',
				),
			);
		}

		$q = new WP_Query();

		$posts = $q->query( $args );

		$metakeys = array(
			'form_source',
			'object_type',
			'object_id',
			'form_fields',
			'form_subject',
			'form_preferences',
			'form_exclude',
			'form_legal_notices',
		);

		if ( ! empty( $posts ) ) {
			foreach ( $posts as $index => $post ) {
				// get form data.
				$metadata_raw = get_metadata( 'post', $post->ID );

				foreach ( $metakeys as $metakey ) {
					$metadata = ! empty( $metadata_raw[ '_iub_' . $metakey ][0] ) ? maybe_unserialize( $metadata_raw[ '_iub_' . $metakey ][0] ) : '';

					if ( ! empty( $metadata ) ) {
						// unset empty values.
						if ( is_array( $metadata ) ) {
							foreach ( $metadata as $metadata_key => $metadata_value ) {
								if ( '' === $metadata_value && ! in_array( $metakey, array( 'form_legal_notices' ), true ) ) {
									unset( $metadata[ $metadata_key ] );
								}
							}
						}

						$posts[ $index ]->{$metakey} = $metadata;
					}
				}
			}
		}

		return $posts;
	}

	/**
	 * Get form function.
	 *
	 * @param   int $id  id.
	 *
	 * @return object
	 */
	public function get_form( $id ) {
		$form_id = ! empty( $id ) ? absint( $id ) : 0;

		if ( ! $form_id ) {
			return false;
		}

		$form = get_post( $form_id );

		if ( ! $form ) {
			return false;
		}

		$metakeys = array(
			'form_source',
			'object_type',
			'object_id',
			'form_fields',
			'form_subject',
			'form_preferences',
			'form_exclude',
			'form_legal_notices',
		);

		// get form data.
		$metadata = get_metadata( 'post', $form->ID );

		foreach ( $metakeys as $metakey ) {
			$form->{$metakey} = ! empty( $metadata[ '_iub_' . $metakey ][0] ) ? maybe_unserialize( $metadata[ '_iub_' . $metakey ][0] ) : '';
		}

		return $form;
	}

	/**
	 * Delete form function.
	 *
	 * @param   int $id  id.
	 *
	 * @return int
	 */
	public function delete_form( $id ) {
		$form_id = ! empty( $id ) ? absint( $id ) : 0;

		if ( ! $form_id ) {
			return false;
		}

		$form = get_post( $form_id );

		if ( ! $form ) {
			return false;
		}

		$result = wp_delete_post( $id, true );

		return $result;
	}

	/**
	 * Insert form function.
	 *
	 * @param   array $args  args.
	 *
	 * @return int
	 */
	public function save_form( $args = array() ) {
		$defaults = array(
			'ID'                 => 0,
			'status'             => 'publish',
			'object_type'        => 'post', // object type where the form data is stored.
			'object_id'          => 0, // unique object id.
			'form_source'        => '', // source slug.
			'form_title'         => '', // form title.
			'form_date'          => current_time( 'mysql' ), // form last modified date.
			'form_fields'        => array(), // form field names array.
			'form_subject'       => array(), // mapped form with iubenda consent subject param.
			'form_preferences'   => array(), // mapped form with iubenda consent preferences param.
			'form_exclude'       => array(), // mapped form with iubenda consent exclude param.
			'form_legal_notices' => array(), // form legal notices.
		);

		$args = wp_parse_args( $args, $defaults );

		// sanitize args.
		$args['ID']                 = ! empty( $args['ID'] ) ? absint( $args['ID'] ) : 0;
		$args['status']             = ! empty( $args['status'] ) && in_array( (string) $args['status'], array_keys( $this->statuses ), true ) ? $args['status'] : 'publish';
		$args['object_type']        = 'post';
		$args['object_id']          = ! empty( $args['object_id'] ) ? sanitize_text_field( $args['object_id'] ) : 0;
		$args['form_source']        = ! empty( $args['form_source'] ) && in_array( (string) $args['form_source'], array_keys( $this->sources ), true ) ? $args['form_source'] : '';
		$args['form_title']         = ! empty( $args['form_title'] ) ? esc_html( $args['form_title'] ) : '';
		$args['form_date']          = gmdate( 'Y-m-d H:i:s', ( ! empty( $args['form_date'] ) ? strtotime( $args['form_date'] ) : current_time( 'mysql' ) ) );
		$args['form_fields']        = ! empty( $args['form_fields'] ) && is_array( $args['form_fields'] ) ? $args['form_fields'] : array();
		$args['form_subject']       = ! empty( $args['form_subject'] ) && is_array( $args['form_subject'] ) ? array_map( 'sanitize_text_field', $args['form_subject'] ) : array();
		$args['form_preferences']   = ! empty( $args['form_preferences'] ) && is_array( $args['form_preferences'] ) ? array_map( 'sanitize_text_field', $args['form_preferences'] ) : array();
		$args['form_exclude']       = ! empty( $args['form_exclude'] ) && is_array( $args['form_exclude'] ) ? array_map( 'sanitize_text_field', $args['form_exclude'] ) : array();
		$args['form_legal_notices'] = ! empty( $args['form_legal_notices'] ) && is_array( $args['form_legal_notices'] ) ? array_map( 'sanitize_text_field', $args['form_legal_notices'] ) : array();

		$form_fields = array();

		// sanitize form fields.
		if ( ! empty( $args['form_fields'] ) && is_array( $args['form_fields'] ) ) {
			foreach ( $args['form_fields'] as $form_field ) {
				if ( ! empty( $form_field ) && is_array( $form_field ) ) {
					$form_fields[] = array_map( 'sanitize_text_field', $form_field );
				} else {
					$form_fields[] = sanitize_text_field( $form_field );
				}
			}
		}

		// bail if any issues.
		if ( ! $args['form_source'] || ! $args['form_fields'] ) {
			return false;
		}

		$post   = intval( 0 ) !== (int) $args['ID'] ? get_post( $args['ID'] ) : false;
		$update = empty( $post ) ? false : true;

		// insert new form.
		if ( ! $update ) {
			$post_id = wp_insert_post(
				array(
					'post_type'     => 'iubenda_form',
					'post_status'   => $args['status'],
					'post_title'    => $args['form_title'],
					'post_content'  => '',
					'post_date'     => $args['form_date'],
					'post_modified' => $args['form_date'],
				)
			);
			// update form.
		} else {
			$post_id = wp_update_post(
				array(
					'ID'            => $args['ID'],
					'post_status'   => $args['status'],
					'post_modified' => $args['form_date'],
				)
			);
		}

		// save form source.
		if ( isset( $args['form_source'] ) ) {
			update_post_meta( $post_id, '_iub_form_source', $args['form_source'] );
		}

		// save object type.
		if ( isset( $args['object_type'] ) ) {
			update_post_meta( $post_id, '_iub_object_type', $args['object_type'] );
		}

		// save object id.
		if ( isset( $args['object_id'] ) ) {
			update_post_meta( $post_id, '_iub_object_id', $args['object_id'] );
		}

		// save form fields.
		if ( isset( $args['form_fields'] ) ) {
			update_post_meta( $post_id, '_iub_form_fields', $form_fields );
		}

		// save form subject.
		if ( isset( $args['form_subject'] ) ) {
			update_post_meta( $post_id, '_iub_form_subject', $args['form_subject'] );
		}

		// save form preferences.
		if ( isset( $args['form_preferences'] ) ) {
			update_post_meta( $post_id, '_iub_form_preferences', $args['form_preferences'] );
		}

		// save form exclude.
		if ( isset( $args['form_exclude'] ) ) {
			update_post_meta( $post_id, '_iub_form_exclude', $args['form_exclude'] );
		}

		// save legal notices.
		if ( isset( $args['form_legal_notices'] ) ) {
			update_post_meta( $post_id, '_iub_form_legal_notices', $args['form_legal_notices'] );
		}

		return $post_id;
	}

	/**
	 * Autodetect forms action.
	 *
	 * @return bool
	 */
	public function autodetect_forms() {
		$found_forms = array();
		$new_forms   = array();

		// get forms from active sources.
		if ( ! empty( $this->sources ) ) {
			foreach ( $this->sources as $source => $source_name ) {
				$found_forms[ $source ] = call_user_func( array( $this, 'get_source_forms' ), $source );
			}
		}

		// insert forms.
		if ( ! empty( $found_forms ) ) {
			foreach ( $found_forms as $source => $source_forms ) {
				if ( ! empty( $source_forms ) ) {

					foreach ( $source_forms as $formdata ) {

						$exists = $this->get_form_by_object_id(
							array(
								'id'     => $formdata['object_id'],
								'source' => $formdata['form_source'],
							)
						);

						// form does not exist.
						if ( ! $exists ) {
							$result = $this->save_form( $formdata );

							if ( $result ) {
								$new_forms['new'][] = $result;
							}
						} else {
							// Is multi dimensions array.
							// Check if the existing form fields is not equal the new form fields.
							if ( is_array( current( $formdata['form_fields'] ) ) ) {
								$new_fields = md5( wp_json_encode( $this->iub_array_dot( $formdata['form_fields'] ) ) ) !== md5( wp_json_encode( $this->iub_array_dot( $exists->form_fields ) ) );
							} else {
								// check for fields changes.
								$new_fields = array_merge( array_diff( $formdata['form_fields'], $exists->form_fields ), array_diff( $exists->form_fields, $formdata['form_fields'] ) );
							}

							if ( $new_fields ) {
								$new_forms['updated'][] = $exists->ID;

								// update form.
								$formdata['ID'] = $exists->ID;

								// update to need status if form is already mapped.
								if ( 'mapped' === (string) $exists->post_status ) {
									$formdata['status'] = 'needs_update';
								}

								$result = $this->save_form( $formdata );
							}
						}
					}
				}
			}
		}

		return ! empty( $new_forms ) ? $new_forms : array();
	}

	/**
	 * Get source forms.
	 *
	 * @param   string $source  source.
	 *
	 * @return array
	 */
	public function get_source_forms( string $source = '' ) {
		$source = ! empty( $source ) && in_array( $source, array_keys( $this->sources ), true ) ? $source : '';
		$forms  = array();

		$restricted_fields = apply_filters(
			"iub_{$source}_restricted_fields",
			array(
				'submit',
				'file',
				'quiz',
				'recaptcha',
			)
		);

		// Do what you want before preparing the form.
		do_action( "iub_before_call_{$source}_forms" );

		switch ( $source ) {
			case 'wpforms':
				$args  = array(
					'post_type'     => 'wpforms',
					'no_found_rows' => true,
					'nopaging'      => true,
				);
				$posts = get_posts( $args );

				if ( ! empty( $posts ) ) {
					foreach ( $posts as $post ) {
						// get form data.
						$formdata = array(
							'object_type' => 'post', // object type where the form data is stored.
							'object_id'   => $post->ID, // unique object id.
							'form_source' => $source, // source slug.
							'form_title'  => $post->post_title, // form title.
							'form_date'   => $post->post_modified, // form last modified date.
							'form_fields' => array(), // form field names array.
						);

						$input_fields = array(
							'text',
							'textarea',
							'select',
							'radio',
							'checkbox',
							'gdpr-checkbox',
							'email',
							'address',
							'url',
							'name',
							'hidden',
							'date-time',
							'phone',
							'number',
						);

						$fields_raw = function_exists( 'wpforms_get_form_fields' ) ? wpforms_get_form_fields( $post->ID ) : false;

						if ( ! empty( $fields_raw ) ) {
							foreach ( $fields_raw as $index => $field ) {
								// specific field types only.
								if ( ! empty( $field['type'] ) && in_array( $field['type'], $input_fields, true ) ) {
									switch ( $field['type'] ) {
										case 'name':
											if ( ! empty( $field['format'] ) ) {
												switch ( $field['format'] ) {
													case 'first-last':
														$formdata['form_fields'][] = array(
															'id'    => $field['id'],
															'name'  => 'wpforms[fields][' . $index . '][first]',
															'type'  => $field['type'],
															'label' => __( 'First name', 'iubenda' ),
														);
														$formdata['form_fields'][] = array(
															'id'    => $field['id'],
															'name'  => 'wpforms[fields][' . $index . '][last]',
															'type'  => $field['type'],
															'label' => __( 'Last name', 'iubenda' ),
														);
														break;
													case 'first-middle-last':
														$formdata['form_fields'][] = array(
															'id'    => $field['id'],
															'name'  => 'wpforms[fields][' . $index . '][first]',
															'type'  => $field['type'],
															'label' => __( 'First name', 'iubenda' ),
														);
														$formdata['form_fields'][] = array(
															'id'    => $field['id'],
															'name'  => 'wpforms[fields][' . $index . '][middle]',
															'type'  => $field['type'],
															'label' => __( 'Middle name', 'iubenda' ),
														);
														$formdata['form_fields'][] = array(
															'id'    => $field['id'],
															'name'  => 'wpforms[fields][' . $index . '][last]',
															'type'  => $field['type'],
															'label' => __( 'Last name', 'iubenda' ),
														);
														break;
													default:
														$formdata['form_fields'][] = array(
															'id'    => $field['id'],
															'name'  => 'wpforms[fields][' . $index . ']',
															'type'  => $field['type'],
															'label' => $field['label'],
														);
														break;
												}
											} else {
												$formdata['form_fields'][] = array(
													'id'   => $field['id'],
													'name' => 'wpforms[fields][' . $index . ']',
													'type' => $field['type'],
													'label' => $field['label'],
												);
											}
											break;
										// fix multiple choice checkbox.
										case 'checkbox':
											$formdata['form_fields'][] = array(
												'id'    => $field['id'],
												'name'  => 'wpforms[fields][' . $index . '][]',
												'type'  => $field['type'],
												'label' => $field['label'],
											);
											break;
										default:
											$formdata['form_fields'][] = array(
												'id'    => $field['id'],
												'name'  => 'wpforms[fields][' . $index . ']',
												'type'  => $field['type'],
												'label' => $field['label'],
											);
									}
								}
							}
						}

						$forms[] = $formdata;
					}
				}

				break;

			case 'wpcf7':
				$args  = array(
					'post_type'      => 'wpcf7_contact_form',
					'posts_per_page' => - 1,
				);
				$posts = get_posts( $args );

				if ( ! empty( $posts ) ) {
					foreach ( $posts as $post ) {
						// get form data.
						$contact_form = class_exists( 'WPCF7_ContactForm' ) ? WPCF7_ContactForm::get_instance( $post->ID ) : false;

						if ( ! empty( $contact_form ) ) {
							$formdata = array(
								'object_type' => 'post', // object type where the form data is stored.
								'object_id'   => $post->ID, // unique object id.
								'form_source' => $source, // source slug.
								'form_title'  => $post->post_title, // form title.
								'form_date'   => $post->post_modified, // form last modified date.
								'form_fields' => array(), // form field names array.
							);

							$fields_raw = $contact_form->scan_form_tags();

							if ( ! empty( $fields_raw ) ) {
								foreach ( $fields_raw as $field ) {
									// specific field types only.
									if ( ! empty( $field['basetype'] ) && ! in_array( $field['basetype'], $restricted_fields, true ) ) {
										// Track exclusive fields only [ex: name=field[] not supported by cons].
										if ( 'checkbox' === (string) $field['type'] && ! in_array( 'exclusive', $field['options'], true ) ) {
											continue;
										}
										$formdata['form_fields'][] = $field['name'];
									}
								}
							}

							$forms[] = $formdata;
						}
					}
				}

				break;

			case 'woocommerce':
				$checkout_form = '';

				if ( ! function_exists( 'WC' ) || ! class_exists( 'WC_Session_Handler' ) || ! class_exists( 'WC_Customer' ) || ! class_exists( 'WC_Cart' ) || ! class_exists( 'WC_Order' ) || ! defined( 'WC_ABSPATH' ) || ! function_exists( 'wc_get_template' ) ) {
					break;
				}

				ob_start();

				// Ensure gateways and shipping methods are loaded early.
				WC()->payment_gateways();
				WC()->shipping();

				/*
				 * First lets start the session. You cant use here WC_Session directly
				 * because it's an abstract class. But you can use WC_Session_Handler which
				 * extends WC_Session
				 */
				WC()->session = new WC_Session_Handler();

				/*
				 * Next lets create a customer so we can access checkout fields
				 * If you will check a constructor for WC_Customer class you will see
				 * that if you will not provide user to create customer it will use some
				 * default one. Magic.
				 */
				WC()->customer = new WC_Customer();

				// Create a cart contents.
				WC()->cart = new WC_Cart();

				// Create an abstract order.
				WC()->order = new WC_Order();

				/**
				 * Load notice function to be compatible with custom themes
				 * that request notice functions in templates
				 */
				if ( file_exists( WC_ABSPATH . 'includes/wc-notice-functions.php' ) ) {
					include_once WC_ABSPATH . 'includes/wc-notice-functions.php';
				}

				/**
				 * Load cart function to be compatible to avoid fatal error on checkout page
				 */
				if ( file_exists( WC_ABSPATH . 'includes/wc-cart-functions.php' ) ) {
					include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
				}

				wc_get_template(
					'checkout/form-checkout.php',
					array(
						'checkout' => WC()->checkout(),
					)
				);

				// Integrate with mailchimp.
				$mc_integration = get_option( 'mc4wp_integrations' );
				if ( is_array( $mc_integration ) && '1' === iub_array_get( $mc_integration, 'woocommerce.enabled' ) ) {
					do_action( 'woocommerce_review_order_before_submit' );
				}

				wc_get_template(
					'checkout/form-pay.php',
					array(
						'order'             => WC()->order,
						'order_button_text' => 'Submit',
					)
				);

				// Germanized for WooCommerce.
				if ( function_exists( 'woocommerce_gzd_template_render_checkout_checkboxes' ) && class_exists( 'WooCommerce_Germanized' ) ) {
					woocommerce_gzd_template_render_checkout_checkboxes();
				}

				// Allow users integrate with other plugins.
				do_action( "iub_render_{$source}_form" );

				$checkout_form = ob_get_contents();
				ob_end_clean();

				if ( ! empty( $checkout_form ) ) {
					$formdata = array(
						'object_type' => 'custom', // object type where the form data is stored.
						'object_id'   => 0, // unique object id.
						'form_source' => $source, // source slug.
						'form_title'  => $this->sources[ $source ], // form title.
						'form_date'   => current_time( 'mysql' ), // form last modified date.
						'form_fields' => array(), // form field names array.
					);

					$input_fields = array(
						'input',
						'textarea',
						'select',
					);

					// DOMDoc parser.
					if ( 'new' === (string) iubenda()->options['cs']['parser_engine'] && can_use_dom_document_class() ) {
						$previous_value = libxml_use_internal_errors( true );

						$document = new DOMDocument();

						// set document arguments.
						// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
						$document->formatOutput = true;
						// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
						$document->preserveWhiteSpace = false;

						// load HTML.
						$document->loadHTML( $checkout_form );

						// search for nodes.
						foreach ( $input_fields as $input_field ) {
							$fields_raw = $document->getElementsByTagName( $input_field );

							if ( ! empty( $fields_raw ) && is_object( $fields_raw ) ) {
								foreach ( $fields_raw as $field ) {
									$field_name = $field->getAttribute( 'name' );
									$field_type = $field->getAttribute( 'type' );

									// exclude submit.
									if ( ! empty( $field_type ) && ! in_array( (string) $field_type, array( 'submit', 'hidden' ), true ) ) {
										$formdata['form_fields'][] = $field->getAttribute( 'name' );
									}
								}
							}
						}

						$forms[] = $formdata;

						libxml_use_internal_errors( $previous_value );

						// Simple HTML Dom parser.
					} else {

						// Ensure helper class were loaded.
						if ( ! function_exists( 'str_get_html' ) ) {
							require_once IUBENDA_PLUGIN_PATH . 'iubenda-cookie-class/simple_html_dom.php';
						}

						if ( function_exists( 'str_get_html' ) ) {
							$html = str_get_html( $checkout_form, $lowercase = true, $force_tags_closed = true, $strip = false );
							if ( is_object( $html ) ) {
								// search for nodes.
								foreach ( $input_fields as $input_field ) {
									$fields_raw = $html->find( $input_field );

									if ( is_array( $fields_raw ) ) {
										foreach ( $fields_raw as $field ) {
											$field_type = $field->type;

											// exclude submit.
											if ( ! empty( $field_type ) && ! in_array( (string) $field_type, array( 'submit', 'hidden' ), true ) ) {
												$formdata['form_fields'][] = $field->getAttribute( 'name' );
											}
										}
									}
								}

								$forms[] = $formdata;
							}
						}
					}
				}
				break;

			case 'wp_comment_form':
				$comment_form = '';

				// get comment form for logged out user.
				$current_user_id = get_current_user_id();

				// get first post.
				$post_args = array(
					'numberposts' => 1,
					'orderby'     => 'ID',
					'order'       => 'ASC',
					'fields'      => 'ids',
				);

				$posts = get_posts( $post_args );

				// get comment form.
				if ( ! empty( $posts ) ) {
					wp_set_current_user( 0 );

					ob_start();

					comment_form( array(), $posts[0] );

					$comment_form = ob_get_contents();
					ob_end_clean();

					wp_set_current_user( $current_user_id );
				}

				if ( ! empty( $comment_form ) ) {
					$formdata = array(
						'object_type' => 'custom', // object type where the form data is stored.
						'object_id'   => 0, // unique object id.
						'form_source' => $source, // source slug.
						'form_title'  => $this->sources[ $source ], // form title.
						'form_date'   => current_time( 'mysql' ), // form last modified date.
						'form_fields' => array(), // form field names array.
					);

					$input_fields = array(
						'input',
						'textarea',
						'select',
					);

					// DOMDoc parser.
					if ( 'new' === (string) iubenda()->options['cs']['parser_engine'] && can_use_dom_document_class() ) {
						$previous_value = libxml_use_internal_errors( true );

						// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase, WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
						$document = new DOMDocument();

						// set document arguments.
						$document->formatOutput       = true;
						$document->preserveWhiteSpace = false;

						// load HTML.
						$document->loadHTML( $comment_form );

						// search for nodes.
						foreach ( $input_fields as $input_field ) {
							$fields_raw = $document->getElementsByTagName( $input_field );

							if ( ! empty( $fields_raw ) && is_object( $fields_raw ) ) {
								foreach ( $fields_raw as $field ) {
									$field_name = $field->getAttribute( 'name' );
									$field_type = $field->getAttribute( 'type' );

									// exclude submit.
									if ( ! empty( $field_type ) && ! in_array( (string) $field_type, array( 'submit' ), true ) ) {
										$formdata['form_fields'][] = $field->getAttribute( 'name' );
									}
								}
							}
						}

						$forms[] = $formdata;

						libxml_use_internal_errors( $previous_value );
						// phpcs:enable

						// Simple HTML Dom parser.
					} else {

						// Ensure helper class were loaded.
						if ( ! function_exists( 'str_get_html' ) ) {
							require_once IUBENDA_PLUGIN_PATH . 'iubenda-cookie-class/simple_html_dom.php';
						}

						if ( function_exists( 'str_get_html' ) ) {
							$html = str_get_html( $comment_form, $lowercase = true, $force_tags_closed = true, $strip = false );
							if ( is_object( $html ) ) {
								// search for nodes.
								foreach ( $input_fields as $input_field ) {
									$fields_raw = $html->find( $input_field );

									if ( is_array( $fields_raw ) ) {
										foreach ( $fields_raw as $field ) {
											$field_name = $field->name;
											$field_type = $field->type;

											// exclude submit.
											if ( ! empty( $field_type ) && ! in_array( (string) $field_type, array( 'submit' ), true ) ) {
												$formdata['form_fields'][] = $field->getAttribute( 'name' );
											}
										}
									}
								}

								$forms[] = $formdata;

							}
						}
					}
				}

				break;

			case 'elementor_forms':
				if ( ! class_exists( 'ElementorPro\Modules\Forms\Module' ) || ! class_exists( 'ElementorPro\Plugin' ) ) {
					break;
				}
				$args = array(
					'post_type'      => array( 'post', 'page', 'elementor_library' ),
					'posts_per_page' => - 1,
					'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
						array(
							'key'     => '_elementor_data',
							'value'   => 'form',
							'compare' => 'LIKE',
						),
					),
				);

				$q = new WP_Query();

				$posts = $q->query( $args );
				if ( ! empty( $posts ) ) {
					foreach ( $posts as $post ) {
						// get form data.
						$elementor_data = get_post_meta( $post->ID, '_elementor_data', true );
						$form_ids       = array();

						if ( $elementor_data ) {
							$elementor_data = json_decode( $elementor_data, true );

							$this->find_elementor_form_id( $elementor_data, $form_ids );
						}

						$elementor = \ElementorPro\Plugin::elementor();

						foreach ( $form_ids as $key => $form_id ) {
							$document = $elementor->documents->get( $post->ID );
							if ( $document ) {
								$elementor_form = \ElementorPro\Modules\Forms\Module::find_element_recursive( $document->get_elements_data(), $form_id );
							}

							if ( ! empty( $elementor_form ) ) {
								// Set the form name as in Elementor builder.
								$form_name  = iub_array_get( $elementor_form, 'settings.form_name' );
								$form_title = ! empty( $form_name ) ? $post->post_title . " ({$form_name})" : $post->post_title . " ({$form_id})";
								$form_data  = array(
									'object_type' => 'post', // object type where the form data is stored.
									'object_id'   => $post->ID . '-' . $form_id, // unique object id.
									'form_source' => $source, // source slug.
									'form_title'  => $form_title, // form title.
									'form_date'   => $post->post_modified, // form last modified date.
									'form_fields' => array(), // form field names array.
								);

								$fields_raw = $elementor_form['settings']['form_fields'] ?? array();

								if ( ! empty( $fields_raw ) ) {
									foreach ( $fields_raw as $field ) {
										$field_type = $field['field_type'] ?? 'text';
										// exclude submit and hidden.
										if ( ! empty( $field_type ) && ! in_array( (string) $field_type, array( 'submit', 'hidden' ), true ) ) {
											$custom_id                  = $field['custom_id'];
											$form_data['form_fields'][] = array(
												'id'    => $field['_id'],
												'name'  => 'form_fields[' . $custom_id . ']',
												'type'  => $field_type,
												'label' => ! empty( $field['field_label'] ) ? $field['field_label'] : __( 'No label', 'iubenda' ),
											);
										}
									}
								}
								$forms[] = $form_data;
							}
						}
					}
				}

				break;
		}

		$forms = apply_filters( "iub_after_call_{$source}_forms", $forms );

		return $forms;
	}

	/**
	 * Get Post object by post_meta query
	 *
	 * @param   array $args  args.
	 *
	 * @return false|object
	 */
	public function get_form_by_object_id( $args = array() ) {
		// parse incoming $args into an array and merge it with $defaults.
		$args = wp_parse_args( $args );

		// grab page.
		$args = array(
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'   => '_iub_object_id',
					'value' => $args['id'],
				),
				array(
					'key'   => '_iub_form_source',
					'value' => $args['source'],
				),
			),
			'post_type'      => 'iubenda_form',
			'post_status'    => $args['post_status'] ?? 'any',
			'posts_per_page' => '1',
			'fields'         => 'ids',
		);

		// run query.
		$posts = get_posts( $args );

		// check result.
		if ( empty( $posts ) ) {
			return false;
		}

		// kick back results.
		return $this->get_form( $posts[0] );
	}

	/**
	 * Convert nested array into one level
	 *
	 * @param   array  $target_array  array.
	 * @param   string $prepend       prepend.
	 *
	 * @return array
	 */
	public function iub_array_dot( $target_array, $prepend = '' ) {
		$results = array();

		foreach ( $target_array as $key => $value ) {
			if ( is_array( $value ) && ! empty( $value ) ) {
				$results = array_merge( $results, $this->iub_array_dot( $value, $prepend . $key . '.' ) );
			} else {
				$results[ $prepend . $key ] = $value;
			}
		}

		return $results;
	}

	/**
	 * Prepare mapped forms
	 *
	 * @param   array $forms  forms.
	 * @param   array $args   args.
	 *
	 * @return array
	 */
	private function prepare_mapped_forms( array $forms, array $args ) {
		// required form parameters.
		$form_parameters = array(
			'subject',
			'preferences',
			'exclude',
			'legal_notices',
		);
		// loop through forms.
		foreach ( $forms as $form ) {
			// bail if user is logged in and source is WP comment form.
			if ( 'wp_comment_form' === (string) $form->form_source && is_user_logged_in() ) {
				continue;
			}

			// we need unique identifier for the html form.
			// by default it's object id, used in form html id.
			$args[ $form->form_source ][ $form->object_id ] = array();

			foreach ( $form_parameters as $parameter ) {
				$parameter_name  = 'form_' . $parameter;
				$parameter_value = ! empty( $form->$parameter_name ) ? $form->$parameter_name : '';

				switch ( $parameter ) {
					case 'legal_notices':
						if ( $parameter_value && is_array( $parameter_value ) ) {
							foreach ( $parameter_value as $value ) {
								$args[ $form->form_source ][ $form->object_id ]['consent']['legal_notices'][] = array( 'identifier' => $value );
							}
						}
						break;
					default:
						if ( $parameter_value ) {
							switch ( $form->form_source ) {
								case 'wpforms':
								case 'elementor_forms':
									// replace integers with field names.
									foreach ( $parameter_value as $index => $parameter_item ) {
										$parameter_value[ $index ] = $form->form_fields[ $parameter_item ]['name'];
									}
									$args[ $form->form_source ][ $form->object_id ]['form']['map'][ $parameter ] = $parameter_value;
									break;
								default:
									$args[ $form->form_source ][ $form->object_id ]['form']['map'][ $parameter ] = $parameter_value;
									break;
							}
						}
						break;
				}
			}
		}

		return $args;
	}

	/**
	 * Prepare data for subject and preferences
	 *
	 * @param   WP_Post $form   form.
	 * @param   string  $key    key.
	 * @param   array   $entry  Original $_POST global.
	 * @param   array   $data   data.
	 *
	 * @return array
	 */
	private function prepare_data_for_wp_forms( $form, $key, $entry, $data ) {
		// Check is key exist in form before looping.
		if ( ! isset( $form->{"form_{$key}"} ) || ! is_array( $form->{"form_{$key}"} ) ) {
			return $data;
		}

		// Prepare form preferences and subject.
		foreach ( $form->{"form_{$key}"} as $map_key => $index ) {

			if ( ! is_numeric( $index ) ) {
				continue;
			}

			if ( 'legal_notices' === $key ) {
				$data['legal_notices'][] = array( 'identifier' => $index );
				continue;
			}

			$name      = trim( $form->form_fields[ $index ]['name'] ?? '', 'wpforms' );
			$array_key = substr( str_replace( '][', '.', $name ), 1, - 1 );
			// Special handling for checkboxes, By checking the name is ending with array brackets.
			if ( '[]' === substr( $name, - 2 ) ) {
				$array_key .= '0';
			}

			$data[ $key ][ $map_key ] = iub_array_get( $entry, $array_key );

			// Special handling for preferences to cast into boolean if the field type is checkbox.
			if ( 'preferences' === (string) $key && 'checkbox' === $form->form_fields[ $index ]['type'] && function_exists( 'wpforms' ) ) {
				$wpform_content = wpforms()->form->get( $form->object_id )->post_content;
				$wpform_content = json_decode( $wpform_content, true );
				$choices        = iub_array_get( $wpform_content, substr( $array_key, 0, - 2 ) . '.choices' );

				$index = null;
				// Check preference field is entered.
				if ( ! empty( $data[ $key ][ $map_key ] ) ) {
					// Reset the array keys.
					foreach ( array_values( $choices ) as $i => $choice ) {
						// Priority to check the choice value.
						if ( $data[ $key ][ $map_key ] === $choice['value'] ) {
							$index = intval( $i );
							break;
						}

						// Then check the choice label [WPForms the label and value are always the same].
						if ( $data[ $key ][ $map_key ] === $choice['label'] ) {
							$index = intval( $i );
						}
					}
				}

				// If index isset and map to first choice then it's true exactly like the Frontend Cons behavior.
				$data[ $key ][ $map_key ] = 0 === $index;
			}
		}

		return $data;
	}

	/**
	 * Integrate with mailchimp
	 * Add id attribute to add event on it from frontend
	 *
	 * @param   array $attributes  attributes.
	 *
	 * @return string[]
	 */
	public function mc4wp_integration_woocommerce_checkbox_attributes( $attributes ) {
		$attributes['id'] = '_mc4wp_subscribe_woocommerce';

		return $attributes;
	}

	/**
	 * Finding elementor form id.
	 *
	 * @param   mixed $data      Data.
	 * @param   mixed $form_ids  Form_ids.
	 */
	private function find_elementor_form_id( $data, &$form_ids ) {
		if ( is_array( $data ) ) {
			foreach ( $data as $item ) {
				if ( isset( $item['elType'] ) && 'widget' === $item['elType'] && 'form' === $item['widgetType'] ) {
					$form_ids[] = $item['id'];
				} elseif ( isset( $item['elements'] ) && is_array( $item['elements'] ) ) {
					$this->find_elementor_form_id( $item['elements'], $form_ids );
				}
			}
		}
	}
}
