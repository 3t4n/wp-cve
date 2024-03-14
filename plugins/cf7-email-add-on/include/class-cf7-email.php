<?php
// If check class_exists or not.
if ( ! class_exists( 'Cf7_Email_Add_on' )  ) {

	class Cf7_Email_Add_on {

		/**
		 * instance
		 *
		 * @var $instance
		 */
		private static $_instance = null;

		/**
		 * Class construct.
		 */
		public function __construct() {
			// Plugin meta row.
			add_filter( 'plugin_row_meta', array( $this, 'cf7_email_add_on_plugin_row_meta' ), 10, 4 );
			// If check current page.
			if ( ! isset( $_REQUEST['page'] ) || $_REQUEST['page'] != 'wpcf7' ) return;
			// Add template using AJAX.
			add_action( 'wp_ajax_cf7_email_add_on_add_admin_template', array( $this, 'cf7_email_add_on_add_admin_template' ) );
			add_action( 'wp_ajax_nopriv_cf7_email_add_on_add_admin_template', array( $this, 'cf7_email_add_on_add_admin_template' ) );
			// Contact form 7 editor filter.
			add_filter( 'wpcf7_editor_panels', array( $this, 'cf7_email_add_on_editor_panels' ) );
			// Admin enqueue script.
			add_action( 'admin_enqueue_scripts', array( $this, 'cf7_email_add_on_scripts_admin' ) );
			// Save contact form.
			add_action( 'wpcf7_save_contact_form', array( $this, 'cf7_email_add_on_save_contact_form' ) );
		}

		/**
		 * Standard singleton pattern.
		 * @return Returns the current plugin instance.
		 */
		public static function _instance() {
			if ( is_null( self::$_instance ) || ! ( self::$_instance instanceof self ) ) {
				self::$_instance = new self;
			}
			return self::$_instance;
		}

		/**
		 * Enqueue scripts.
		 */
		public function cf7_email_add_on_scripts_admin() {
			// CSS
			wp_enqueue_style( 'cf7-email', plugin_dir_url( __FILE__ ) . '../admin/assets/css/style.css' );
			// JS
			wp_enqueue_script( 'jquery-ui-dialog' );
			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			wp_enqueue_script( 'custom-js', plugin_dir_url( __FILE__ ) . '../admin/assets/js/custom.js' , array( 'jquery' ), '', true );
			// localize data using wp_localize_script
			wp_localize_script( 'custom-js', 'cf7ea_ajax_object',
				array( 
					'ajax_url' 	=> admin_url( 'admin-ajax.php' ),
					'nonce'			=> wp_create_nonce( 'cf7-email-add-on' ),
					'pro_features_list'	=> '<h3>' . __( "CF7 Email Addon Pro Features: ", 'cf7-email-add-on' ) . '</h3><div class="inside cf7-features-list"><ul><li>' . __( 'Ability to export the HTML templates', 'cf7-email-add-on' ) . '</li><li>' . __( 'Setting panel to easily update the information', 'cf7-email-add-on' ) . '</li><li>' . __( 'Preview template & send test email', 'cf7-email-add-on' ) . '</li></ul><hr><div class="cf7-buy-now"><a href="' . esc_url( 'https://codecanyon.net/item/contact-form-7-email-add-on-pro/23172379' ) . '" target="_blank">' . __( 'Buy Now', 'cf7-email-add-on' ) . '</a><a href="' . esc_url( 'https://krishaweb.info/cf7-email-add-on-demo' ) . '" target="_blank">' . __( 'Live Demo', 'cf7-email-add-on' ) . '</a></div></div>'
				) 
			);
		}

		/**
		 * Contact form 7 editor panels.
		 *
		 * @param      array  $panels  The panels
		 *
		 * @return     array
		 */
		public function cf7_email_add_on_editor_panels( $panels ) {
			$panels['cf7-email-add-on-html-template-panel'] = array(
				'title'		=> __( 'Email Template', 'cf7-email-add-on' ),
				'callback'	=> array( $this, 'cf7_email_add_on_template_panel' ),
			);
			return $panels;
		}

		/**
		 * Template panel
		 *
		 * @param      WPCF7_ContactForm  $contactform  The contactform
		 */
		public function cf7_email_add_on_template_panel( WPCF7_ContactForm $contactform ) {
			require plugin_dir_path( __FILE__ ) . '../admin/contact-form-7-email-add-on-templates.php';
		}

		/**
		 * Save contact form 7 
		 *
		 */
		public function cf7_email_add_on_save_contact_form() {
			if ( isset( $_POST['post_ID'] ) ) {
				$contact_form_id = ( int ) $_POST['post_ID'];
				// Update contact form post meta
				if ( ! empty( $_POST['cf7ea_admin_email'] ) ) {
					$cf7ea_admin_template_name = sanitize_text_field( $_POST['cf7ea_admin_email'] );
					update_post_meta( $contact_form_id, 'cf7ea_admin_template', $cf7ea_admin_template_name );
				}
				if ( ! empty( $_POST['cf7ea_thank_you_email'] ) ) {
					$cf7ea_thank_you_template_name = sanitize_text_field( $_POST['cf7ea_thank_you_email'] );
					update_post_meta( $contact_form_id, 'cf7ea_thank_you_template', $cf7ea_thank_you_template_name );
				}
			}
		}

		/**
		 * Add email template using AJAX
		 */
		public function cf7_email_add_on_add_admin_template() {
			// WP ajax check security nonce.
			check_ajax_referer( 'cf7-email-add-on', 'nonce' );

			$template_name = $_POST['template_name'];
			$template_type = $_POST['template_type'];
			// Add dynamic fields
			$fields = $this->cf7_email_add_on_create_dynamic_fields();

			if ( isset( $template_name ) && ! empty( $template_name ) ) {
				if ( $template_type == 'admin' ) {
					ob_start();
					include_once plugin_dir_path( __FILE__ ) . '../admin/email-templates/admin/' . $template_name . '.php';
					$template_data = str_replace( '[fields]', $fields, html_entity_decode( esc_html( ob_get_clean() ) ) );
				} else {
					ob_start();
					include_once plugin_dir_path( __FILE__ ) . '../admin/email-templates/user/' . $template_name . '.php';
					$template_data = str_replace( '[fields]', $fields, html_entity_decode( esc_html( ob_get_clean() ) ) );
				}
				if ( ! empty( $template_data ) ) {
					$msg = array(
						'result' => 1,
						'template_type' => $template_type,
						'message' => str_replace( '[plugin_url]', CF7_PLUGIN_URL, $template_data ),
					);
				} else {
					$msg = array(
						'result' => 0,
						'message' => __( 'File Not Found', 'cf7-email-add-on' ),
					);	
				}
			} else {
				$msg = array(
					'result' => 0,
					'message' => 'error',
				);
			}
			echo wp_json_encode( $msg );
			wp_die();
		}

		/**
		 * Create template fields
		 *
		 * @param      string  $content  The content
		 *
		 * @return     string  ( fields )
		 */
		private function cf7_email_add_on_create_dynamic_fields( $content = '' ) {
			$template_fields = '';
			$post = WPCF7_ContactForm::get_instance( $_REQUEST['post'] );
			// Get fields
			$fields = $post->collect_mail_tags();
			// Create fields.
			foreach ( $fields as $field ) {
				$template_fields .='<tr>
				<td style="font-family: Courier, Courier New, monospace, Arial; color: #ffffff; text-align: left; padding-bottom: 35px; padding-left: 15px; padding-right: 15px;">
					<table cellpadding="0" cellspacing="0" width="100%;">
					<tr>
					<td style="font-weight: bold; font-size: 22px; color: #f18f4e;" width="200">' . str_replace( array( '-', '_' ), array( ' ', ' ' ), $field ) . '
					</td>
					<td style="font-size: 16px; font-family: sans-serif, Arial;" width="300">[' . $field. ']
					</td>
					</tr>
					</table>
					</td>
				</tr>';
			}
			// Return html.
			return $template_fields;
		}

		/**
		 * Plugin row meta.
		 *
		 * @param  array  $links       Row items.
		 * @param  string $plugin_file File path.
		 * @param  array  $plugin_data Plugin data.
		 * @param  string $status Plugin status.
		 * @return array Plugin row action links.
		 */
		public function cf7_email_add_on_plugin_row_meta( $links, $plugin_file, $plugin_data, $status ) {
			// Add documentation link in plugin meta.
			if ( ( isset( $plugin_data['slug'] ) && 'cf7-email-add-on' === $plugin_data['slug'] ) ) {
				$links[] = wp_sprintf( '<a href="%1$s" target="_blank"><span class="dashicons dashicons-search"></span> %2$s</a>', esc_url( 'https://krishaweb.com/docs/contact-form-7-email-add-on/' ), __( 'Documentation', 'cf7-email-add-on' ) );
				$links[] = wp_sprintf( '<a href="mailto:%1$s"><span class="dashicons dashicons-admin-users"></span> %2$s</a>', sanitize_email( 'support@krishaweb.com' ), __( 'Support', 'cf7-email-add-on' ) );
				$links[] = wp_sprintf( '<a href="%1$s"><span class="dashicons dashicons-cart"></span> %2$s</a>', esc_url( 'https://codecanyon.net/item/contact-form-7-email-add-on-pro/23172379' ), __( 'Premium', 'cf7-email-add-on' ) );
			}
			return $links;
		}

		/**
		 * Clear mail template
		 */
		public static function __clear_history() {
			$cf7_form = new WP_Query(
				array(
					'post_type' => 'wpcf7_contact_form',
					'posts_per_page' => -1,
					'meta_query' => array(
						'relation' => 'OR',
						array(
							'key' => 'cf7ea_admin_template',
							'compare' => '=',
						),
						array(
							'key' => 'cf7ea_thank_you_template',
							'compare' => '=',
						)
					)
				)
			);
			// If check have posts.
			if ( $cf7_form->have_posts() ) {
				while ( $cf7_form->have_posts() ) {
					$cf7_form->the_post();
					$post_id = get_the_id();
					// Get post meta
					$mail_1 = get_post_meta( $post_id, '_mail', true );
					$mail_2 = get_post_meta( $post_id, '_mail_2', true );
					// Admin template
					if ( get_post_meta( $post_id, 'cf7ea_admin_template', true ) != '' ) {
						$mail_1['body'] = '';
						// Update post meta
						update_post_meta( $post_id, '_mail', $mail_1 );
					}
					// User template
					if ( get_post_meta( $post_id, 'cf7ea_thank_you_template', true ) != '' ) {
						$mail_2['body'] = '';
						// Update post meta
						update_post_meta( $post_id, '_mail_2', $mail_2 );
					}
					// Delete post meta
					delete_post_meta( $post_id, 'cf7ea_admin_template' );
					delete_post_meta( $post_id, 'cf7ea_thank_you_template' );
				}
			}
			wp_reset_postdata();
		}
	}
}
