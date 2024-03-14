<?php
/**
 * The plugin custom post type class.
 *
 * Register new post type and it handlers
 *
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WQM_QR_Code_Type' ) ) {

	class   WQM_QR_Code_Type {

		/**
		 * Post type slug
		 */
		const POST_TYPE_SLUG = 'qrcode-card';

		/**
		 * @var array Post type card fields
		 */
		public static $custom_post_fields = array(
			'wqm_n',
			'wqm_nickname',
			'wqm_photo',
			'wqm_bday',
			'wqm_adr',
			'wqm_tel',
			'wqm_email',
			'wqm_title',
			'wqm_logo',
			'wqm_org',
			'wqm_url',
			'wqm_class',
			'wqm_note',
		);

		/**
		 * @var array Post type card settings field
		 */
		public static $qr_code_settings_fields = array(
			'wqm_type',
			'wqm_margin',
			'wqm_correction_level',
			'wqm_label',
			'wqm_logo_id',
			'wqm_logo_width',
			'wqm_logo_height',
			'wqm_bgcolor',
			'wqm_fgcolor',
			'wqm_filename',
		);

		/**
		 * Initialization post type
		 */
		public static function run() {
			add_action( 'init', __CLASS__ . '::create_post_type' );
			add_action( 'save_post_' . self::POST_TYPE_SLUG, __CLASS__ . '::save_post', 10, 2 );
		}

		/**
		 * Registers the custom post type
		 */
		public static function create_post_type() {
			if ( ! post_type_exists( self::POST_TYPE_SLUG ) ) {
				$post_type_params = self::get_post_type_params();
				$post_type        = register_post_type( self::POST_TYPE_SLUG, $post_type_params );

				add_filter( 'manage_' . self::POST_TYPE_SLUG . '_posts_columns', function () {
					$columns = array(
						'cb'             => '<input type="checkbox" />',
						'title'          => __( 'Title', 'wp-qrcode-me-v-card' ),
						'featured_image' => __( 'QR code', 'wp-qrcode-me-v-card' ),
						'shortcode'      => __( 'Shortcode', 'wp-qrcode-me-v-card' ),
						'url'            => __( 'Direct url', 'wp-qrcode-me-v-card' ),
						'date'           => __( 'Date', 'wp-qrcode-me-v-card' ),
					);

					return $columns;
				} );

				add_action( 'manage_' . self::POST_TYPE_SLUG . '_posts_custom_column', function ( $column, $post_id ) {
					switch ( $column ) {
						case 'featured_image':
							echo get_the_post_thumbnail( $post_id, 'full' );
							break;
						case 'shortcode':
							echo '[' . WQM_Shortcode::SHORTCODE_NAME . ' card_id="' . $post_id . '"]';
							break;
						case 'url':
							$is_static = get_post_meta( $post_id, 'wqm_is_static', true );
							if ( $is_static ) {
								$imurl = get_the_post_thumbnail_url( $post_id, 'full' );
								echo '<div class="dirlink">Permanent url: <a href="' . $imurl . '">' . $imurl . '</a></div>';
							} else {
								$nonce = wp_create_nonce( 'wqm-permalink-nonce' );
								$url   = admin_url( 'admin-ajax.php' ) . '?action=wqm_make_permanent&post_id=' . $post_id . '&_wpnonce=' . $nonce;
								echo '<div class="dirlink"><a href="javascript:;" onclick="_this=this;jQuery.get(\'' . $url . '\', function(r) {
											if (r.success === false || (r.success === true && r.data === false)) {
												jQuery(_this).parent().html(\'Error: \' + r.data + \'<br>\' + jQuery(_this).parent().html());
												return;
											}
											jQuery(_this).parent().html(r.data);
										})">' .
								     __( 'Create permanent url', 'wp-qrcode-me-v-card' ) .
								     '</a></div>';
							}
							echo '<br><br><a href="/?qr-code=' . $post_id . '">' . __( 'Direct .VCF Link', 'wp-qrcode-me-v-card' ) . '</a>';
							break;
					}
				}, 10, 2 );


				add_filter( 'admin_post_thumbnail_size', __CLASS__ . '::custom_admin_thumb_size', 10, 3 );

				if ( is_wp_error( $post_type ) ) {
					WQM_Common::print_error( $post_type );
				}
			}
		}


		/**
		 * Add filter to show full sidebar width featured image thumbnail
		 *
		 * @param $thumb_size
		 * @param $thumbnail_id
		 * @param $post
		 *
		 * @return array
		 */
		public static function custom_admin_thumb_size( $thumb_size, $thumbnail_id, $post ) {
			if ( self::POST_TYPE_SLUG === $post->post_type ) {
				return array( 266, 266 );
			}

			return $thumb_size;
		}

		/**
		 * Defines the parameters for the custom post type
		 *
		 * @return array
		 */
		protected static function get_post_type_params() {
			$labels = array(
				'name'               => __( 'MeCard/vCard QR codes', 'wp-qrcode-me-v-card' ),
				'all_items'          => __( 'All QR codes', 'wp-qrcode-me-v-card' ),
				'singular_name'      => __( 'QR code MeCard/vCard card', 'wp-qrcode-me-v-card' ),
				'add_new'            => __( 'Add New', 'wp-qrcode-me-v-card' ),
				'add_new_item'       => __( 'Add New MeCard/vCard card', 'wp-qrcode-me-v-card' ),
				'edit'               => __( 'Edit', 'wp-qrcode-me-v-card' ),
				'edit_item'          => __( 'Edit MeCard/vCard card', 'wp-qrcode-me-v-card' ),
				'new_item'           => __( 'New MeCard/vCard card', 'wp-qrcode-me-v-card' ),
				'view'               => __( 'View MeCard/vCard card', 'wp-qrcode-me-v-card' ),
				'view_item'          => __( 'View MeCard/vCard card', 'wp-qrcode-me-v-card' ),
				'search_items'       => __( 'Search QR code MeCard/vCard cards', 'wp-qrcode-me-v-card' ),
				'not_found'          => __( 'No MeCard/vCard cards found', 'wp-qrcode-me-v-card' ),
				'not_found_in_trash' => __( 'No MeCard/vCard cards found in Trash', 'wp-qrcode-me-v-card' ),
				'parent'             => __( 'Parent MeCard/vCard card', 'wp-qrcode-me-v-card' ),
			);

			$post_type_params = array(
				'labels'               => $labels,
				'singular_label'       => __( 'QR code MeCard/vCard card', 'wp-qrcode-me-v-card' ),
				'public'               => false,
				'exclude_from_search'  => true,
				'publicly_queryable'   => false,
				'show_ui'              => true,
				'show_in_menu'         => true,
				'register_meta_box_cb' => __CLASS__ . '::add_custom_post_fields',
				'menu_position'        => 20,
				'hierarchical'         => true,
				'capability_type'      => 'post',
				'has_archive'          => false,
				'rewrite'              => false,
				'query_var'            => false,
				'menu_icon'            => plugins_url( 'static/images/qr-code-16-icon.png', dirname( __FILE__ ) ),
				'supports'             => array( 'title', 'thumbnail', 'revisions' )
			);

			return apply_filters( 'wqm_post-type-params', $post_type_params );
		}

		/**
		 * Adds meta box for custom post type
		 *
		 * @mvc Controller
		 */
		public static function add_custom_post_fields() {
			add_meta_box(
				'wqm_custom-post-box-settings',
				__( 'QR code settings', 'wp-qrcode-me-v-card' ),
				__CLASS__ . '::markup_meta_box_settings',
				self::POST_TYPE_SLUG,
				'normal',
				'core'
			);
			add_meta_box(
				'wqm_custom-post-box-fields',
				__( 'Card fields', 'wp-qrcode-me-v-card' ),
				__CLASS__ . '::markup_meta_box_fields',
				self::POST_TYPE_SLUG,
				'normal',
				'core'
			);
		}

		/**
		 * Builds the markup for meta box fields
		 *
		 * @param object $post
		 */
		public static function markup_meta_box_fields( $post ) {

			$variables = self::get_card_metas( $post->ID );

			echo WQM_Common::render( 'card-fields-form.php', $variables );
		}

		/**
		 * Builds the markup for meta box settings
		 *
		 * @param object $post
		 */
		public static function markup_meta_box_settings( $post ) {

			$variables = self::get_qr_code_settings_metas( $post->ID );

			echo WQM_Common::render( 'settings-form.php', $variables );
		}

		/**
		 * Read all post type fields
		 *
		 * @param $post_id
		 *
		 * @return array
		 */
		public static function get_card_metas( $post_id ) {
			$variables = array();
			foreach ( self::$custom_post_fields as $field ) {
				if ( $field == 'wqm_logo' ) {
					continue;
				}
				$variables[ $field ] = get_post_meta( $post_id, $field, true );
				if ( 'wqm_photo' == $field ) {
					$variables['wqm_photo_path'] = wp_get_attachment_image_url( $variables[ $field ], 'qr-code-photo' );
					$variables['wqm_logo']       = $variables['wqm_photo_path'];
				}
			}

			return $variables;
		}

		/**
		 * Read all post type settings
		 *
		 * @param $post_id
		 *
		 * @return array
		 */
		public static function get_qr_code_settings_metas( $post_id ) {
			$variables = array();
			foreach ( self::$qr_code_settings_fields as $field ) {
				$variables[ $field ] = get_post_meta( $post_id, $field, true );
				if ( 'wqm_logo_id' == $field ) {
					$variables['wqm_logo_path'] = get_attached_file( $variables[ $field ] );
				}
			}

			return $variables;
		}

		/**
		 * Saves values of the the custom post type's extra fields
		 *
		 * @param int $post_id
		 * @param WP_Post $post
		 */
		public static function save_post( $post_id, $post ) {

			$ignored_actions = array( 'trash', 'untrash', 'restore' );

			if ( isset( $_GET['action'] ) && in_array( $_GET['action'], $ignored_actions ) ) {
				return;
			}

			if ( ! $post ) {
				return;
			}

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || 'auto-draft' == $post->post_status ) {
				return;
			}

			$post_data = self::sanitize_validate_post_data( $_POST );

			self::save_custom_fields( $post_id, $post_data );
		}

		/**
		 * Validates and saves values of the the custom post type's extra fields
		 *
		 * @mvc Model
		 *
		 * @param int $post_id
		 * @param array $new_values
		 */
		protected static function save_custom_fields( $post_id, $new_values ) {
			foreach ( $new_values as $key => $val ) {
				update_post_meta( $post_id, $key, $val );
			}

			$atid = get_post_thumbnail_id( $post_id );

			$is_static = get_post_meta( $post_id, 'wqm_is_static', true );
			if ( $is_static ) {
				$att_meta = wp_get_attachment_metadata( $atid );
				if ( empty( $att_meta ) ) {
					$is_static = false;
				} else {
					$upload    = wp_get_upload_dir();
					$is_static = $upload['basedir'] . '/' . $att_meta['file'];
				}
			}

			// generate new QR code
			$params = array_merge(
				self::get_card_metas( $post_id ),
				self::get_qr_code_settings_metas( $post_id ),
				array( 'wqm_is_static' => $is_static )
			);
			$file   = ( new WQM_Qr_Code_Generator( $params ) )->build();
			if ( $file ) {
				if ( ! $is_static ) {
					// remove exists
					if ( ! empty( $atid ) ) {
						wp_delete_attachment( $atid, true );
					}
					// save new QR code
					$media_id = self::upload_media( $file, $post_id, null );
					update_post_meta( $post_id, '_thumbnail_id', $media_id );
				} else {
					wp_create_image_subsizes( $file, $atid );
				}
			}
		}

		/**
		 * Upload generated QR code handler
		 *
		 * @param $file
		 * @param $post_id
		 * @param $desc
		 *
		 * @return int|WP_Error
		 */
		private static function upload_media( $file, $post_id, $desc ) {
			$filename = explode( '/', $file );

			$file_array = array(
				'name'     => array_pop( $filename ),
				'tmp_name' => $file,
			);

			// Do the validation and storage stuff.
			$id = media_handle_sideload( $file_array, $post_id, $desc );

			// If error storing permanently, unlink.
			if ( is_wp_error( $id ) ) {
				@unlink( $file_array['tmp_name'] );
			}

			return $id;
		}

		public static function sanitize_validate_post_data( $post ): array {
			$result = array();

			foreach ( $post as $item => $value ) {
				if ( ! in_array( $item, self::$custom_post_fields ) && ! in_array( $item, self::$qr_code_settings_fields ) ) {
					continue;
				}

				switch ( $item ) {
					case 'wqm_adr':
						$value = array_values( $value );
						array_walk( $value, function ( &$el ) {

							if ( ! empty( $el['s'] ) ) {
								$el = $el['s'];
							} else {
								unset( $el['s'] );
								$el = implode( ';', array_filter( $el ) );
							}

							return $el;
						} );
						$result[ $item ] = $value;
						break;
					case 'wqm_tel':
						$value = array_values( $value );
						array_walk( $value, function ( &$el ) {
							$el['content'] = preg_replace( '@[^\d\+\)\(]+@si', '', $el['content'] );

							return $el;
						} );
						$result[ $item ] = $value;
						break;
					case 'wqm_email':
						$value = array_values( $value );
						array_walk( $value, function ( &$el ) {
							$email = filter_var( $el, FILTER_SANITIZE_EMAIL );
							$el    = sanitize_email( $email );

							return $el;
						} );
						$result[ $item ] = $value;
						break;
					case 'wqm_bday':
						$value = trim( $value );
						if ( preg_match( '@\d{4}\-\d{2}\-\d{2}@si', $value ) ) {
							$result[ $item ] = $value;
						}
						break;
					case 'wqm_photo':
						$value = WQM_Common::clear_digits( $value );
						$url   = wp_get_attachment_image_url( $value, 'qr-code-photo' );
						if ( ! empty( $url ) && WQM_Common::is_url_exists( $url ) ) {
							$result[ $item ] = $value;
						} else {
							$result[ $item ]          = false;
							$result['wqm_phone_path'] = false;
							$result['wqm_logo']       = false;
						}
						break;
					case 'wqm_logo_id':
						$value = WQM_Common::clear_digits( $value );
						$url   = wp_get_attachment_image_url( $value, array( 100, 100 ) );
						if ( ! empty( $url ) && WQM_Common::is_url_exists( $url ) ) {
							$result[ $item ] = $value;
						} else {
							$result[ $item ]         = false;
							$result['wqm_logo_path'] = false;
						}
						break;
					case 'wqm_logo_width':
					case 'wqm_logo_height':
						$result[ $item ] = preg_replace( '@[^\d%]+@si', '', $value );
						break;
					case 'wqm_url':
						$value = array_values( $value );
						array_walk( $value, function ( &$el ) {
							$url = filter_var( $el, FILTER_SANITIZE_URL );
							$el  = esc_url_raw( $url );

							return $el;
						} );
						$result[ $item ] = $value;
						break;
					case 'wqm_type':
						if ( in_array( $value, array( 'mecard', 'vcard' ) ) ) {
							$result[ $item ] = $value;
						}
						break;
					case 'wqm_margin':
						$margin          = preg_replace( '@[^\d]+@si', '', $value );
						$result[ $item ] = $margin;
						break;
					case 'wqm_correction_level':
						if ( in_array( $value, array( 'LOW', 'MEDIUM', 'QUARTILE', 'HIGH' ) ) ) {
							$result[ $item ] = $value;
						}
						break;
					case 'wqm_n':
						if ( ! empty( $value['s'] ) ) {
							$value = $value['s'];
						} else {
							unset( $value['s'] );
							$value = implode( ';', array_filter( $value ) );
						}
					default:
						$def             = filter_var( $value, FILTER_SANITIZE_STRING );
						$result[ $item ] = sanitize_text_field( $def );
						break;
				}
			}

			return $result;
		}

		/**
		 * Ajax request to make QR code imag url permanent for direct link
		 */
		public static function wqm_make_url_permanent() {
			if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'wqm-permalink-nonce' ) ) {
				wp_send_json_error( 'security check' );
			}

			if ( empty( $_REQUEST['post_id'] ) ) {
				wp_send_json_error( 'post_id missing' );
			}

			if ( ! current_user_can( 'edit_post', $_REQUEST['post_id'] ) ) {
				wp_send_json_error( 'security check' );
			}

			$post_id = WQM_Common::clear_digits( $_REQUEST['post_id'] );
			if ( ! $post = get_post( $post_id ) ) {
				wp_send_json_error( 'post not found' );
			}

			update_post_meta( $post_id, 'wqm_is_static', true );
			$url = get_the_post_thumbnail_url( $post_id, 'full' );
			wp_send_json_success( 'Permanent url: <a href="' . $url . '" target="_blank">' . $url . '</a>' );
		}
	}
}
