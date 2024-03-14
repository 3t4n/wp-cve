<?php

namespace EmTmplF\Inc;

defined( 'ABSPATH' ) || exit;

class Email_Builder {

	protected static $instance = null;
	protected $send_test_error_message;

	private function __construct() {
		add_action( 'init', [ $this, 'register_custom_post_type' ] );
		add_action( 'dbx_post_sidebar', array( $this, 'builder_page' ) );
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );

		add_filter( 'get_sample_permalink_html', array( $this, 'delete_permalink' ) );
		add_filter( 'post_row_actions', array( $this, 'delete_view_action' ) );
		add_action( 'save_post_wp_email_tmpl', array( $this, 'save_post' ) );
		add_filter( 'manage_wp_email_tmpl_posts_columns', array( $this, 'add_column_header' ) );
		add_action( 'manage_wp_email_tmpl_posts_custom_column', array( $this, 'add_column_content' ), 10, 2 );
		add_action( 'post_action_emtmpl_duplicate', array( $this, 'duplicate_template' ) );
		add_filter( 'enter_title_here', array( $this, 'change_text_add_title' ) );
		add_action( 'edit_form_top', [ $this, 'remove_meta_boxes' ] );
		add_action( 'admin_footer', [ $this, 'support_section' ] );

		//Ajax
		add_action( 'wp_ajax_emtmpl_preview_template', array( $this, 'preview_template' ) );
		add_action( 'wp_ajax_emtmpl_send_test_email', array( $this, 'send_test_email' ) );
		add_action( 'wp_ajax_emtmpl_change_admin_bar_stt', array( $this, 'change_admin_bar_stt' ) );

//	    Send test result
		add_action( 'wp_mail_failed', [ $this, 'get_error_send_mail' ] );

	}

	public function remove_meta_boxes() {
		if ( get_current_screen()->id == 'wp_email_tmpl' ) {
			global $wp_meta_boxes;
			$wp_meta_boxes = [];
		}
	}

	public function remove_action() {
		if ( get_current_screen()->id == 'wp_email_tmpl' ) {
			remove_all_actions( 'admin_notices' );
		}
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function admin_body_class( $class ) {
		$admin_bar = Utils::get_admin_bar_stt();
		$class     = $admin_bar ? $class : $class . ' emtmpl-admin-bar-hidden';

		return $class;
	}

	public function delete_view_action( $actions ) {
		global $post_type;
		if ( 'wp_email_tmpl' === $post_type ) {
			unset( $actions['view'] );
		}

		return $actions;
	}

	public function delete_permalink( $link ) {
		global $post_type;

		return 'wp_email_tmpl' === $post_type ? '' : $link;
	}

	public function register_custom_post_type() {

		$labels = array(
			'name'               => _x( '9MAIL - WordPress Email Templates Designer', 'Post Type General Name', '9mail-wp-email-templates-designer' ),
			'singular_name'      => _x( '9MAIL - WordPress Email Templates Designer', 'Post Type Singular Name', '9mail-wp-email-templates-designer' ),
			'menu_name'          => esc_html__( '9MAIL', '9mail-wp-email-templates-designer' ),
			'parent_item_colon'  => esc_html__( 'Parent Email', '9mail-wp-email-templates-designer' ),
			'all_items'          => esc_html__( 'All Emails', '9mail-wp-email-templates-designer' ),
			'add_new_item'       => esc_html__( 'Add New Email Template', '9mail-wp-email-templates-designer' ),
			'add_new'            => esc_html__( 'Add New', '9mail-wp-email-templates-designer' ),
			'edit_item'          => esc_html__( 'Edit Email Templates', '9mail-wp-email-templates-designer' ),
			'update_item'        => esc_html__( 'Update Email Templates', '9mail-wp-email-templates-designer' ),
			'search_items'       => esc_html__( 'Search Email Templates', '9mail-wp-email-templates-designer' ),
			'not_found'          => esc_html__( 'Not Found', '9mail-wp-email-templates-designer' ),
			'not_found_in_trash' => esc_html__( 'Not found in Trash', '9mail-wp-email-templates-designer' ),
		);

		$args = array(
			'label'               => esc_html__( '9MAIL - WordPress Email Templates Designer', '9mail-wp-email-templates-designer' ),
			'labels'              => $labels,
			'supports'            => [ 'title' ],
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',
			'query_var'           => false,
			'menu_position'       => 2,
			'map_meta_cap'        => true,
			'menu_icon'           => 'dashicons-email'
		);


		register_post_type( 'wp_email_tmpl', $args );
		flush_rewrite_rules();
	}

	public function builder_page( $post ) {

		if ( $post->post_type !== 'wp_email_tmpl' ) {
			return;
		}
		$this->email_builder_box( $post );

		?>
        <div id="emtmpl-right-sidebar">
			<?php

			$boxes = [
				'settings'   => [
					'title' => esc_html__( 'Settings', '9mail-wp-email-templates-designer' ),
					'func'  => [ $this, 'email_type_box' ]
				],
				'testing'    => [
					'title' => esc_html__( 'Testing', '9mail-wp-email-templates-designer' ),
					'func'  => [ $this, 'email_testing_box' ]
				],
				'admin_note' => [
					'title' => esc_html__( "Admin's note for this template", '9mail-wp-email-templates-designer' ),
					'func'  => [ $this, 'admin_note' ]
				],
				'exim_data'  => [
					'title' => esc_html__( "Data", '9mail-wp-email-templates-designer' ),
					'func'  => [ $this, 'exim_data' ]
				],
			];

			foreach ( $boxes as $key => $data ) {
				if ( ! empty( $data['func'] ) && is_array( $data['func'] ) ) {
					$func   = $data['func'];
					$object = $func[0];
					$method = $func[1];

					if ( ! method_exists( $object, $method ) ) {
						continue;
					}

					ob_start();
					$object->$method( $post );
					$html = ob_get_clean();
					printf( "<div id='emtmpl-box-%s' class='emtmpl-setting-box'><div class='emtmpl-box-title'>%s</div>%s</div>", esc_attr( $key ), esc_html( $data['title'] ), $html );
				}
			}

			$enable = get_post_meta( $post->ID, 'emtmpl_enable_img_for_default_template', true );
			$size   = get_post_meta( $post->ID, 'emtmpl_img_size_for_default_template', true );
			?>
            <input type="hidden" name="emtmpl_enable_img_for_default_template" value="<?php echo esc_attr( $enable ) ?>">
            <input type="hidden" name="emtmpl_img_size_for_default_template" value="<?php echo esc_attr( $size ) ?>">
        </div>
		<?php
	}

	public function email_builder_box( $post ) {
		extract( [
			'admin_bar_stt' => Utils::get_admin_bar_stt(),
			'custom_css'    => get_post_meta( $post->ID, 'emtmpl_custom_css', true ),
			'direction'     => get_post_meta( $post->ID, 'emtmpl_settings_direction', true )
		] ); // @codingStandardsIgnoreLine

		include_once plugin_dir_path( __FILE__ ) . 'view/email-editor.php';
	}

	public function email_type_box( $post ) {
		extract( [
			'type_selected'      => get_post_meta( $post->ID, 'emtmpl_settings_type', true ),
			'direction_selected' => get_post_meta( $post->ID, 'emtmpl_settings_direction', true ),
			'email_types'        => Utils::get_email_ids()
		] ); // @codingStandardsIgnoreLine

		include_once plugin_dir_path( __FILE__ ) . 'view/email-type.php';
	}

	public function email_rules_box( $post ) {
		$settings = get_post_meta( $post->ID, 'emtmpl_setting_rules', true );
		extract( [
			'type_selected'       => get_post_meta( $post->ID, 'emtmpl_settings_type', true ),
			'categories_selected' => $settings['categories'] ?? [],
			'countries_selected'  => $settings['countries'] ?? [],
			'languages_selected'  => $settings['languages'] ?? [],
			'min_subtotal'        => $settings['min_subtotal'] ?? '',
			'max_subtotal'        => $settings['max_subtotal'] ?? ''
		] );
		include_once plugin_dir_path( __FILE__ ) . 'view/email-type.php';
	}

	public function email_testing_box( $post ) {
		include_once plugin_dir_path( __FILE__ ) . 'view/email-testing.php';
	}

	public function exim_data() {
		?>
        <div>
            <textarea id="emtmpl-exim-data"></textarea>
            <div class="vi-ui buttons emtmpl-btn-group">
                <button type="button" class="vi-ui button mini attached emtmpl-import-data"><?php esc_html_e( 'Import' ); ?></button>
                <button type="button" class="vi-ui button mini attached emtmpl-export-data"><?php esc_html_e( 'Export' ); ?></button>
                <button type="button" class="vi-ui button mini attached emtmpl-copy-data"><?php esc_html_e( 'Copy' ); ?></button>
            </div>
        </div>

		<?php
	}

	public function admin_note( $post ) {
		$note = get_post_meta( $post->ID, 'emtmpl_admin_note', true );
		?>
        <div>
            <textarea id="emtmpl-admin-note" name="emtmpl_admin_note"><?php echo wp_kses_post( $note ) ?></textarea>
        </div>

		<?php
	}

	public function save_post( $post_id ) {
		if ( ! current_user_can( 'manage_options' ) || ! isset( $_POST['post_status'] ) || ! in_array( $_POST['post_status'], [ 'publish', 'draft' ] ) ) {
			return;
		}
		$keys = [
			'emtmpl_settings_subject',
			'emtmpl_settings_type',
			'emtmpl_settings_direction',
			'emtmpl_email_structure',
			'emtmpl_setting_rules',
			'emtmpl_admin_note',
			'emtmpl_custom_css',
		];

		foreach ( $keys as $key ) {
			if ( isset( $_POST[ $key ] ) ) {
				$value = $this->data_clean( $_POST[ $key ] );
				update_post_meta( $post_id, $key, $value );
			}
		}
	}

	public function data_clean( $var ) {
		if ( is_array( $var ) ) {
			return array_map( [ $this, 'data_clean' ], $var );
		} else {
			return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		}
	}

	public function get_lorem_ipsum_string() {
		return sprintf( "<p>%s</p><br/><p>%s</p><br/><p>%s</p><a href='#'>%s</a>",
			esc_html__( "Email content is displayed here. You can modify the attributes in paragraph section.", '9mail-wp-email-templates-designer' ),
			esc_html__( "If ignore this template for an email, let's add this shortcode {{ignore_9mail}} to that email content.", '9mail-wp-email-templates-designer' ),
			esc_html__( "You can modify some attributes for the links in email in the Link section:", '9mail-wp-email-templates-designer' ),
			site_url() );
	}

	public function preview_template() {
		if ( ! ( isset( $_POST['nonce'], $_POST['data'] ) && wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'emtmpl_nonce' ) ) ) {
			return;
		}

		$data         = sanitize_text_field( wp_unslash( $_POST['data'] ) );
		$data         = json_decode( $data, true );
		$email_render = Email_Render::instance();

		$email_render->set_data( [
			'schema'  => $data,
			'content' => $this->get_lorem_ipsum_string()
		] );

		$email_render->preview_render();
		$custom_style = $email_render->custom_style();

		$custom_css = isset( $_POST['custom_css'] ) ? sanitize_text_field( wp_unslash( $_POST['custom_css'] ) ) : '';
		printf( '<style type="text/css">%s</style>', wp_kses_post( $custom_css . $custom_style ) );

		wp_die();
	}

	public function preview_custom_css() {
		$custom_css = isset( $_POST['custom_css'] ) ? sanitize_text_field( wp_unslash( $_POST['custom_css'] ) ) : '';

		return $custom_css;
	}

	public function get_error_send_mail( $message ) {
		$this->send_test_error_message = wp_json_encode( $message->errors );
	}

	public function send_test_email() {
		if ( isset( $_POST['nonce'], $_POST['email'], $_POST['data'] ) && wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'emtmpl_nonce' ) ) {
			$data         = sanitize_text_field( wp_unslash( $_POST['data'] ) );
			$data         = json_decode( $data, true );
			$email_render = Email_Render::instance();

			$email_render->set_data( [
				'schema'  => $data,
				'content' => $this->get_lorem_ipsum_string()
			] );

			add_filter( 'emtmpl_after_render_style', [ $this, 'preview_custom_css' ] );

			ob_start();
			$email_render->preview_render();
			$email      = ob_get_clean();
			$email      .= '{{ignore_9mail}}';
			$custom_css = isset( $_POST['custom_css'] ) ? sanitize_text_field( wp_unslash( $_POST['custom_css'] ) ) : '';

			$custom_style = $email_render->custom_style();
			$custom_style .= $custom_css;

			$email = str_replace( '[custom_style]', $custom_style, $email );
			$email = Utils::minify_html( $email );

			remove_filter( 'emtmpl_after_render_style', [ $this, 'preview_custom_css' ] );

			$headers [] = "Content-Type: text/html";
			$subject    = EMTMPL_CONST['plugin_name'] . ' ' . esc_html__( 'test email template', '9mail-wp-email-templates-designer' );
			$mail_to    = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
			$result     = false;

			if ( is_email( $mail_to ) ) {
				$result = wp_mail( $mail_to, $subject, $email, $headers );
			}

			$error_mess = esc_html__( "Mailing Error Found:", '9mail-wp-email-templates-designer' );

			if ( $this->send_test_error_message ) {
				$error_mess .= $this->send_test_error_message;
			}

			$message = $result ? esc_html__( 'Email was sent successfully', '9mail-wp-email-templates-designer' ) : $error_mess;

			$result ? wp_send_json_success( $message ) : wp_send_json_error( $message );
		}
	}

	public function add_column_header( $cols ) {
		$cols = [
			'cb'    => '<input type="checkbox">',
			'title' => esc_html__( 'Email subject', '9mail-wp-email-templates-designer' ),
			'note'  => esc_html__( 'Note', '9mail-wp-email-templates-designer' ),
			'date'  => esc_html__( 'Date', '9mail-wp-email-templates-designer' )
		];

		return $cols;
	}

	public function add_column_content( $col, $post_id ) {
		switch ( $col ) {
			case 'note':
				$note = get_post_meta( $post_id, 'emtmpl_admin_note', true );
				echo esc_html( $note );
				break;
		}

	}

	public function post_row_actions( $action, $post ) {
		if ( $post->post_type === 'wp_email_tmpl' ) {
			unset( $action['inline hide-if-no-js'] );
			$href   = admin_url( "post.php?action=emtmpl_duplicate&id={$post->ID}" );
			$action = [ 'emtmpl-duplicate' => "<a href='{$href}' onclick='this.style.visibility=\"hidden\";'>" . esc_html__( 'Duplicate', '9mail-wp-email-templates-designer' ) . "</a>" ] + $action;
		}

		return $action;
	}

	public function duplicate_template() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$dup_id = ! empty( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : '';
		if ( $dup_id ) {
			$current_post = get_post( $dup_id );

			$args   = [
				'post_title' => 'Copy of ' . $current_post->post_title,
				'post_type'  => $current_post->post_type,
			];
			$new_id = wp_insert_post( $args );

			$email_type       = get_post_meta( $dup_id, 'emtmpl_settings_type', true );
			$email_structure  = get_post_meta( $dup_id, 'emtmpl_email_structure', true );
			$email_categories = get_post_meta( $dup_id, 'emtmpl_settings_categories', true );
			$email_countries  = get_post_meta( $dup_id, 'emtmpl_settings_countries', true );
			update_post_meta( $new_id, 'emtmpl_settings_type', $email_type );
			update_post_meta( $new_id, 'emtmpl_email_structure', str_replace( '\\', '\\\\', $email_structure ) );
			update_post_meta( $new_id, 'emtmpl_settings_categories', $email_categories );
			update_post_meta( $new_id, 'emtmpl_settings_countries', $email_countries );
			wp_safe_redirect( admin_url( "post.php?post={$new_id}&action=edit" ) );
			exit;
		}
	}

	public function add_filter_dropdown() {
		if ( get_current_screen()->id === 'edit-wp_email_tmpl' ) {
			$emails = Utils::get_email_ids();
			echo '<select name="wp_email_tmpl_filter">';
			echo "<option value=''>" . esc_html__( 'Filter by type', '9mail-wp-email-templates-designer' ) . "</option>";
			foreach ( $emails as $key => $name ) {
				printf( "<option value='%s'>%s</option>", esc_attr( $key ), esc_html( $name ) );
			}
			echo '</select>';
		}
	}

	public function change_text_add_title( $title ) {
		if ( get_current_screen()->id == 'wp_email_tmpl' ) {
			$title = esc_html__( 'Add Email Subject', '9mail-wp-email-templates-designer' );
			echo "<div class='emtmpl-subject-quick-shortcode'><i class='dashicons dashicons-menu'> </i></div>";
		}

		return $title;
	}

	public function change_admin_bar_stt() {
		$current_stt = Utils::get_admin_bar_stt();
		$new_stt     = $current_stt ? false : true;
		$result      = update_option( 'emtmpl_admin_bar_stt', $new_stt );
		if ( $result ) {
			wp_send_json_success( $new_stt );
		} else {
			wp_send_json_error();
		}
		wp_die();
	}

	public function search_post() {
		if ( ! ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'emtmpl_nonce' ) ) ) {
			return;
		}
		$q = ! empty( $_POST['q'] ) ? sanitize_text_field( wp_unslash( $_POST['q'] ) ) : '';

		if ( $q ) {
			$args  = [
				'numberposts' => - 1,
				'post_type'   => 'post',
				's'           => $q
			];
			$posts = get_posts( $args );
			if ( ! empty( $posts ) && is_array( $posts ) ) {
				$result = [];
				foreach ( $posts as $post ) {
					$result[] = [ 'id' => $post->ID, 'text' => strtoupper( $post->post_title ), 'content' => do_shortcode( $post->post_content ) ];
				}

				wp_send_json( $result );
			}
		}
		wp_die();
	}

	public function support_section() {
		if ( get_current_screen()->id === 'edit-wp_email_tmpl' ) {
			?>
            <div id="emtmpl-in-all-email-page">
				<?php do_action( 'villatheme_support_9mail-wordpress-email-templates-designer' ); ?>
            </div>
		<?php }
	}
}

