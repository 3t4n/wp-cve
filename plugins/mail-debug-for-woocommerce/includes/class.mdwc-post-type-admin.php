<?php

if ( ! class_exists( 'MDWC_Post_Type_Admin' ) ) {
	class MDWC_Post_Type_Admin {
		private static $_instance;

		public static function get_instance() {
			return ! is_null( self::$_instance ) ? self::$_instance : self::$_instance = new self();
		}

		private function __construct() {

			add_action( 'init', array( $this, 'register_post_types' ), 5 );
			add_filter( 'manage_mail-debug_posts_columns', array( $this, 'manage_list_columns' ) );
			add_action( 'manage_mail-debug_posts_custom_column', array( $this, 'render_list_columns' ), 10, 2 );

			add_action( 'parse_query', array( $this, 'mail_debug_search' ) );
			add_filter( 'get_search_query', array( $this, 'search_label' ) );
			add_filter( 'query_vars', array( $this, 'add_custom_query_var' ) );


			add_action( 'admin_menu', array( $this, 'remove_publish_box' ) );
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

			add_action( 'in_admin_footer', array( $this, 'add_preview_box' ) );
			add_action( 'wp_ajax_mdwc_get_email_message', array( $this, 'ajax_get_email_message' ) );
			add_action( 'wp_ajax_nopriv_mdwc_get_email_message', array( $this, 'ajax_get_email_message' ) );

			add_action( 'manage_posts_extra_tablenav', array( $this, 'print_delete_all_button' ) );
			add_action( 'admin_init', array( $this, 'handle_delete_all' ) );
		}

		public function print_delete_all_button() {
			$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : false;
			if ( $screen && 'edit-mail-debug' === $screen->id ) {
				$mail_debugs = get_posts( array(
											  'posts_per_page' => 1,
											  'post_type'      => 'mail-debug',
											  'post_status'    => 'any',
										  ) );
				if ( ! $mail_debugs ) {
					return;
				}
				echo "<div class='alignleft'>";
				echo wp_nonce_field( 'mdwc-delete-all', 'mdwc-nonce' );
				echo "<input type='submit' name='mdwc-delete-all' class='mdwc-delete-all button button-secondary' value='" . __( 'Delete All', 'mail-debug-for-woocommerce' ) . "'></input>";
				echo "</div>";
			}
		}

		public function handle_delete_all() {
			if ( isset ( $_GET['post_type'] ) && 'mail-debug' === $_GET['post_type'] &&
				 isset( $_REQUEST['mdwc-delete-all'] ) &&
				 isset( $_REQUEST['mdwc-nonce'] ) && wp_verify_nonce( $_REQUEST['mdwc-nonce'], 'mdwc-delete-all' ) ) {
				global $wpdb;
				$wpdb->query( "DELETE meta FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE posts.post_type = 'mail-debug';" );
				$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type = 'mail-debug';" );
				wp_redirect( admin_url( 'edit.php?post_type=mail-debug' ) );
				exit();
			}
		}

		public function ajax_get_email_message() {
			$post_id = isset( $_REQUEST['post_id'] ) ? $_REQUEST['post_id'] : 0;
			$data    = array(
				'error' => __( 'Error: Missing post_id', 'mail-debug-for-woocommerce' ),
			);
			if ( $post_id ) {
				$message = get_post_meta( $post_id, 'message', true );
				$data    = array(
					'message' => $message,
				);
			}

			wp_send_json( $data );
		}

		public function add_preview_box() {
			if ( function_exists( 'get_current_screen' ) && $screen = get_current_screen() ) {
				if ( 'edit-mail-debug' === $screen->id ) {
					include MDWC_VIEWS_PATH . 'preview-box.php';
				}
			}
		}

		public function remove_publish_box() {
			remove_meta_box( 'submitdiv', 'mail-debug', 'side' );
		}

		public function add_meta_boxes( $post_type ) {
			if ( $post_type !== 'mail-debug' ) {
				return;
			}
			add_meta_box( 'mail-debug-message', 'Message',
						  array( $this, 'meta_box_print' ),
						  'mail-debug', 'normal', 'high' );

			add_meta_box( 'mail-debug-info', 'Info',
						  array( $this, 'meta_box_print' ),
						  'mail-debug', 'side', 'high' );
		}

		public function meta_box_print( $post, $metabox ) {
			switch ( $metabox['id'] ) {
				case 'mail-debug-message':
					$message = get_post_meta( $post->ID, 'message', true );
					$ical = get_post_meta( $post->ID, 'ical', true );
					$alt_body = get_post_meta( $post->ID, 'alt_body', true );

					$settings = array(
						'type'       => 'text/html',
						'codemirror' => array(
							'readOnly' => true,
						),
					);
					$settings = wp_enqueue_code_editor( $settings );

					echo "<div class='mail-debug-message__tabs-anchors'>";
					echo "<div class='mail-debug-message__tabs-anchor active' data-ref='mail-debug-message__tab--html'>" . __( 'HTML', 'mail-debug-for-woocommerce' ) . "</div>";
					echo "<div class='mail-debug-message__tabs-anchor' data-ref='mail-debug-message__tab--code'>" . __( 'HTML Code', 'mail-debug-for-woocommerce' ) . "</div>";
					if ( $alt_body ) {
						echo "<div class='mail-debug-message__tabs-anchor' data-ref='mail-debug-message__tab--plain-text'>" . __( 'Plain Text', 'mail-debug-for-woocommerce' ) . "</div>";
					}
					if ( $ical ) {
						echo "<div class='mail-debug-message__tabs-anchor' data-ref='mail-debug-message__tab--ical'>" . __( 'iCal', 'mail-debug-for-woocommerce' ) . "</div>";
					}
					echo "</div>";

					echo "<div class='mail-debug-message__tabs'>";
					echo "<div id='mail-debug-message__tab--html' class='mail-debug-message__tab active'>{$message}</div>";
					echo "<div id='mail-debug-message__tab--code' class='mail-debug-message__tab'>";
					?>
					<textarea class="mail-debug-codemirror"
							rows="8" cols="50"
							data-settings="<?php echo esc_attr( wp_json_encode( $settings ) ); ?>"
					><?php echo esc_textarea( $message ); ?></textarea>
					<?php

					echo "</div>";

					if ( $alt_body ) {
						echo "<div id='mail-debug-message__tab--plain-text' class='mail-debug-message__tab'>{$alt_body}</div>";
					}
					if ( $ical ) {
						echo "<div id='mail-debug-message__tab--ical' class='mail-debug-message__tab'>{$ical}</div>";
					}
					echo "</div>";

					break;
				case 'mail-debug-info':
					$to = get_post_meta( $post->ID, 'to', true );
					if ( is_array( $to ) ) {
						$to = implode( ', ', $to );
					}
					$subject     = get_post_meta( $post->ID, 'subject', true );
					$headers     = get_post_meta( $post->ID, 'headers', true );
					$attachments = get_post_meta( $post->ID, 'attachments', true );
					$email_id    = get_post_meta( $post->ID, 'email_id', true );
					$email_title = get_post_meta( $post->ID, 'email_title', true );
					$from        = get_post_meta( $post->ID, 'from', true );
					$from_name   = get_post_meta( $post->ID, 'from_name', true );

					if ( $email_title ) {
						$email_title = sprintf( '%s (%s)', $email_title, $email_id );
					} else {
						$email_title = $email_id;
					}

					$date_format = get_option( 'date_format' ) . ' H:i:s';

					$attachments_html = __( 'No Attachment', 'mail-debug-for-woocommerce' );
					if ( $attachments && is_array( $attachments ) ) {
						$attachments_html = '<ul style="margin: 0"><li>';
						$attachments_html .= implode( '</li><li>', $attachments );
						$attachments_html .= '</li></ul>';
					}

					$info = array(
						'to'          => array(
							'label' => __( 'To', 'mail-debug-for-woocommerce' ),
							'value' => $to,
						),
						'subject'     => array(
							'label' => __( 'Subject', 'mail-debug-for-woocommerce' ),
							'value' => $subject,
						),
						'wc_email'    => array(
							'label' => __( 'WC Email', 'mail-debug-for-woocommerce' ),
							'value' => $email_title,
						),
						'headers'     => array(
							'label' => __( 'Headers', 'mail-debug-for-woocommerce' ),
							'value' => $headers,
						),
						'attachments' => array(
							'label' => __( 'Attachments', 'mail-debug-for-woocommerce' ),
							'value' => $attachments_html,
						),
						'date'        => array(
							'label' => __( 'Date', 'mail-debug-for-woocommerce' ),
							'value' => get_the_date( $date_format, $post->ID ),
						),
						'from'        => array(
							'label' => __( 'From', 'mail-debug-for-woocommerce' ),
							'value' => $from,
						),
						'from_name'   => array(
							'label' => __( 'From name', 'mail-debug-for-woocommerce' ),
							'value' => $from_name,
						),
					);

					echo "<div class='mail-debug-info__list'>";
					foreach ( $info as $key => $single_info ) {
						$label = $single_info['label'] ?? '';
						$value = $single_info['value'] ?? '';
						if ( ! ! $value ) {
							$value = is_scalar( $value ) ? $value : print_r( $value, true );
							echo "<div class='mail-debug-single-info mail-debug-single-info--$key'><div class='mail-debug-single-info__label'><strong>$label</strong></div><div class='mail-debug-single-info__value'>$value</div></div>";
						}
					}
					echo "</div>";
					break;
			}
		}

		public function register_post_types() {
			$labels = array(
				'name'               => __( 'Mail Debug', 'mail-debug-for-woocommerce' ),
				'singular_name'      => __( 'Mail Debug', 'mail-debug-for-woocommerce' ),
				'add_new'            => __( 'Add Mail Debug', 'mail-debug-for-woocommerce' ),
				'add_new_item'       => __( 'Add Mail Debug', 'mail-debug-for-woocommerce' ),
				'edit'               => __( 'View', 'mail-debug-for-woocommerce' ),
				'edit_item'          => __( 'Mail Debug', 'mail-debug-for-woocommerce' ),
				'new_item'           => __( 'New Mail Debug', 'mail-debug-for-woocommerce' ),
				'view'               => __( 'View Mail Debug', 'mail-debug-for-woocommerce' ),
				'view_item'          => __( 'View Mail Debug', 'mail-debug-for-woocommerce' ),
				'search_items'       => __( 'Search Mail Debug', 'mail-debug-for-woocommerce' ),
				'not_found'          => __( 'No Mail Debug found', 'mail-debug-for-woocommerce' ),
				'not_found_in_trash' => __( 'No Mail Debug found in trash', 'mail-debug-for-woocommerce' ),
				'parent'             => __( 'Parent Mail Debug', 'mail-debug-for-woocommerce' ),
				'menu_name'          => __( 'Mail Debug', 'mail-debug-for-woocommerce' ),
				'all_items'          => __( 'Mail Debug', 'mail-debug-for-woocommerce' ),
			);

			$args = array(
				'label'               => __( 'Mail Debug', 'mail-debug-for-woocommerce' ),
				'labels'              => $labels,
				'description'         => __( 'This is where Mail Debug are stored.', 'mail-debug-for-woocommerce' ),
				'public'              => false,
				'show_ui'             => true,
				'capability_type'     => 'product',
				'capabilities'        => array( 'create_posts' => 'do_not_allow' ),
				'map_meta_cap'        => true,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'show_in_menu'        => true,
				'hierarchical'        => false,
				'show_in_nav_menus'   => false,
				'rewrite'             => false,
				'query_var'           => false,
				'supports'            => array( '' ),
				'has_archive'         => false,
				'menu_icon'           => 'dashicons-email',
			);

			if ( ! mdwc_settings()->show_in_wp_menu() ) {
				$args['show_in_menu'] = 'tools.php';
			}

			register_post_type( 'mail-debug', $args );
		}

		public function manage_list_columns( $columns ) {
			$date_text = $columns['date'];
			unset( $columns['date'] );
			unset( $columns['title'] );

			$columns['subject']   = __( 'Subject', 'mail-debug-for-woocommerce' );
			$columns['to']        = __( 'To', 'mail-debug-for-woocommerce' );
			$columns['wc_email']  = __( 'WC Email', 'mail-debug-for-woocommerce' );
			$columns['wc_object'] = __( 'Object', 'mail-debug-for-woocommerce' );
			$columns['ical']      = __( 'iCal', 'mail-debug-for-woocommerce' );
			$columns['date']      = $date_text;

			return $columns;
		}

		public function render_list_columns( $column, $post_id ) {
			switch ( $column ) {
				case 'to':
					$to = get_post_meta( $post_id, 'to', true );
					if ( is_array( $to ) ) {
						$to = implode( ', ', $to );
					}
					echo $to;
					break;
				case 'subject':
					$subject   = get_post_meta( $post_id, 'subject', true );
					$edit_link = get_edit_post_link( $post_id );
					echo "<strong><a href='{$edit_link}'>{$subject}</a></strong>";
					break;
				case 'ical':
					$ical = get_post_meta( $post_id, 'ical', true );
					if ( $ical ) {
						$has_ical = __( 'has an iCal', 'mail-debug-for-woocommerce' );
						echo "<span class='dashicons dashicons-calendar' title='{$has_ical}'></span>";
					}
					break;
				case 'wc_email':
					$email_id    = get_post_meta( $post_id, 'email_id', true );
					$email_title = get_post_meta( $post_id, 'email_title', true );
					if ( $email_title ) {
						$email_info = sprintf( '<span title="%s">%s</span>', $email_id, $email_title );
					} else {
						$email_info = $email_id;
					}

					$customer_email = get_post_meta( $post_id, 'customer_email', true );
					if ( '' !== $customer_email ) {
						$icon       = 'yes' === $customer_email ? 'groups' : 'admin-users';
						$info       = 'yes' === $customer_email ? __( 'Email to customer', 'mail-debug-for-woocommerce' ) : __( 'Email to admin', 'mail-debug-for-woocommerce' );
						$email_info = "<span class='dashicons dashicons-{$icon}' title='{$info}'></span>" . $email_info;
					}

					echo $email_info;
					break;
				case 'attachments':
					$has_attachments = ! ! get_post_meta( $post_id, 'attachments', true );
					if ( $has_attachments ) {
						echo "<span class'dashicons dashicons-paperclip'></span>";
					}
					break;
				case 'wc_object':
					$object = get_post_meta( $post_id, 'email_object', true );
					if ( $object ) {
						if ( is_string( $object ) ) {
							echo $object;
						} elseif ( is_object( $object ) ) {
							// backward compatibility
							$to_print = get_class( $object );

							if ( is_callable( array( $object, 'get_id' ) ) ) {
								$to_print .= ' #' . $object->get_id();
							}
							echo $to_print;
						}
					}
					break;
			}
		}

		/**
		 * Change the label when searching orders.
		 *
		 * @param mixed $query Current search query.
		 *
		 * @return string
		 */
		public function search_label( $query ) {
			global $pagenow, $typenow;

			if ( 'edit.php' !== $pagenow || 'mail-debug' !== $typenow || ! get_query_var( 'mail_debug_search' ) || ! isset( $_GET['s'] ) ) { // WPCS: input var ok.
				return $query;
			}

			return wp_unslash( $_GET['s'] ); // WPCS: input var ok, sanitization ok.
		}

		/**
		 * Query vars for custom searches.
		 *
		 * @param mixed $public_query_vars Array of query vars.
		 *
		 * @return array
		 */
		public function add_custom_query_var( $public_query_vars ) {
			$public_query_vars[] = 'mail_debug_search';

			return $public_query_vars;
		}

		/**
		 * Mail Debug Search
		 *
		 * @param WP_Query $query
		 *
		 * @since 1.0.2
		 */
		public function mail_debug_search( $query ) {
			global $pagenow;

			if ( 'edit.php' != $pagenow || empty( $query->query_vars['s'] ) || 'mail-debug' !== $query->query_vars['post_type'] ) {
				return;
			}

			$search = $_GET['s'];

			// Remove "s" - we don't want to search order name.
			unset( $query->query_vars['s'] );

			// so we know we're doing this.
			$query->query_vars['mail_debug_search'] = true;

			$query->query_vars['meta_query'] = array(
				'relation' => 'OR',
				array( 'key' => 'to', 'value' => $search, 'compare' => 'LIKE' ),
				array( 'key' => 'subject', 'value' => $search, 'compare' => 'LIKE' ),
				array( 'key' => 'email_id', 'value' => $search ),
				array( 'key' => 'email_title', 'value' => $search, 'compare' => 'LIKE' ),
			);

		}
	}
}