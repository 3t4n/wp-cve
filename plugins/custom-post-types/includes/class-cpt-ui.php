<?php

defined( 'ABSPATH' ) || exit;

final class CPT_Ui extends CPT_Component {
	/**
	 * @var array
	 */
	private $no_title_post_types = array( CPT_UI_PREFIX, CPT_UI_PREFIX . '_tax' );

	/**
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'save_post', array( $this, 'generate_post_title' ) );
		add_action( 'edit_form_after_title', array( $this, 'add_post_title_label' ) );
		add_filter( 'post_row_actions', array( $this, 'edit_actions' ), 10, 2 );
		add_filter( 'post_updated_messages', array( $this, 'edit_messages' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_filter( 'cpt_admin_notices_register', array( $this, 'welcome_notices' ) );
		add_action( 'admin_menu', array( $this, 'remove_main_add_items_menu' ) );
		add_filter( 'cpt_admin_pages_register', array( $this, 'register_pro_pages' ) );
		add_filter( 'cpt_field_groups_register', array( $this, 'register_ui_fields' ) );
		add_filter( 'cpt_field_sanitize', array( $this, 'sanitize_ui_id_fields' ), 10, 4 );
		add_action( 'admin_footer', array( $this, 'add_feedback_modal' ) );
		add_action( 'admin_init', array( $this, 'feedback_actions' ), -1 );
	}

	/**
	 * @return void
	 */
	public function add_feedback_modal() {
		if ( get_current_screen()->id == 'plugins' ) { //phpcs:ignore Universal.Operators.StrictComparisons
			require_once CPT_PATH . '/includes/templates/modal-feedback.php';
		}
	}

	/**
	 * @param $feedback
	 *
	 * @return void
	 */
	private function send_feedback( $feedback ) {
		$request_url = add_query_arg(
			array(
				'id'       => 92,
				'feedback' => $feedback,
				'domain'   => md5( get_home_url() ),
				'v'        => CPT_VERSION,
			),
			'https://totalpress.org/wp-json/totalpress/v1/plugin-feedback'
		);
		wp_remote_get( $request_url, array( 'blocking' => false ) );
	}

	/**
	 * @return void
	 */
	public function feedback_actions() {
		$nonce = ! empty( $_GET['nonce'] ) && wp_verify_nonce( $_GET['nonce'], CPT_NONCE_KEY );
		if ( ! $nonce ) {
			return;
		}

		$action = ! empty( $_GET['action'] ) && 'cpt-feedback' == $_GET['action'] ? $_GET['action'] : false; //phpcs:ignore Universal.Operators.StrictComparisons
		if ( ! $action ) {
			return;
		}

		$feedback = array();
		if ( ! empty( $_GET['reason'] ) ) {
			$feedback[] = esc_textarea( $_GET['reason'] );
		}
		if ( ! empty( $_GET['suggestion'] ) ) {
			$feedback[] = esc_textarea( $_GET['suggestion'] );
		}
		if ( ! empty( $feedback ) ) {
			$feedback = implode( ' # ', $feedback );
			$this->send_feedback( $feedback );
		}

		$plugin_file      = 'custom-post-types/custom-post-types.php';
		$deactivation_url = add_query_arg(
			array(
				'action'   => 'deactivate',
				'plugin'   => $plugin_file,
				'_wpnonce' => wp_create_nonce( 'deactivate-plugin_' . $plugin_file ),
			),
			admin_url( 'plugins.php' )
		);

		wp_safe_redirect( $deactivation_url );
		exit;
	}

	/**
	 * @param $meta_value
	 * @param $meta_key
	 * @param $meta_type
	 * @param $field_group_id
	 *
	 * @return mixed|string
	 */
	public function sanitize_ui_id_fields( $meta_value, $meta_key, $meta_type, $field_group ) {
		$field_group_id = $field_group['id'];
		if (
			'id' == $meta_key && //phpcs:ignore Universal.Operators.StrictComparisons
			in_array( $field_group_id, array( CPT_UI_PREFIX, CPT_UI_PREFIX . '_tax', CPT_UI_PREFIX . '_field', CPT_UI_PREFIX . '_page', CPT_UI_PREFIX . '_notice' ), true )
		) {
			$meta_value = sanitize_title( $meta_value );
		}
		return $meta_value;
	}

	/**
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public function generate_post_title( $post_id ) {
		$post_type   = get_post( $post_id )->post_type;
		$post_status = get_post( $post_id )->post_status;
		if ( ! in_array( $post_type, $this->no_title_post_types, true ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || 'trash' == $post_status ) { //phpcs:ignore Universal.Operators.StrictComparisons
			return $post_id;
		}
		$new_title = ! empty( $_POST['meta-fields']['plural'] ) ? $_POST['meta-fields']['plural'] : 'CPT_' . $post_id;
		global $wpdb;
		$wpdb->update( $wpdb->posts, array( 'post_title' => $new_title ), array( 'ID' => $post_id ) );
		return $post_id;
	}

	/**
	 * @return void
	 */
	public function add_post_title_label() {
		$screen = get_current_screen();
		$post   = ! empty( $_GET['post'] ) && get_post( $_GET['post'] ) ? get_post( $_GET['post'] ) : false;
		if ( ! in_array( $screen->post_type, $this->no_title_post_types, true ) || ! in_array( $screen->id, $this->no_title_post_types, true ) || ! $post ) {
			return;
		}
		printf( '<h1 style="padding: 0;">%s</h1>', $post->post_title );
	}

	/**
	 * @param $actions
	 * @param $post
	 *
	 * @return mixed
	 */
	public function edit_actions( $actions, $post ) {
		if ( stripos( $post->post_type, CPT_UI_PREFIX ) !== false ) {
			// Remove quick edit links
			unset( $actions['inline hide-if-no-js'] );
		}
		return $actions;
	}

	/**
	 * @param $messages
	 *
	 * @return mixed
	 */
	public function edit_messages( $messages ) {
		// Update ui confirm messages
		$messages[ CPT_UI_PREFIX ]               = array(
			1  => __( 'Post type updated', 'custom-post-types' ),
			4  => __( 'Post type updated', 'custom-post-types' ),
			6  => __( 'Post type published', 'custom-post-types' ),
			7  => __( 'Post type saved', 'custom-post-types' ),
			8  => __( 'Post type submitted', 'custom-post-types' ),
			9  => __( 'Post type scheduled', 'custom-post-types' ),
			10 => __( 'Post type draft updated', 'custom-post-types' ),
		);
		$messages[ CPT_UI_PREFIX . '_tax' ]      = array(
			1  => __( 'Taxonomy updated', 'custom-post-types' ),
			4  => __( 'Taxonomy updated', 'custom-post-types' ),
			6  => __( 'Taxonomy published', 'custom-post-types' ),
			7  => __( 'Taxonomy saved', 'custom-post-types' ),
			8  => __( 'Taxonomy submitted', 'custom-post-types' ),
			9  => __( 'Taxonomy scheduled', 'custom-post-types' ),
			10 => __( 'Taxonomy draft updated', 'custom-post-types' ),
		);
		$messages[ CPT_UI_PREFIX . '_field' ]    = array(
			1  => __( 'Field group updated', 'custom-post-types' ),
			4  => __( 'Field group updated', 'custom-post-types' ),
			6  => __( 'Field group published', 'custom-post-types' ),
			7  => __( 'Field group saved', 'custom-post-types' ),
			8  => __( 'Field group submitted', 'custom-post-types' ),
			9  => __( 'Field group scheduled', 'custom-post-types' ),
			10 => __( 'Field group draft updated', 'custom-post-types' ),
		);
		$messages[ CPT_UI_PREFIX . '_template' ] = array(
			1  => __( 'Template updated', 'custom-post-types' ),
			4  => __( 'Template updated', 'custom-post-types' ),
			6  => __( 'Template published', 'custom-post-types' ),
			7  => __( 'Template saved', 'custom-post-types' ),
			8  => __( 'Template submitted', 'custom-post-types' ),
			9  => __( 'Template scheduled', 'custom-post-types' ),
			10 => __( 'Template draft updated', 'custom-post-types' ),
		);
		$messages[ CPT_UI_PREFIX . '_page' ]     = array(
			1  => __( 'Admin page updated', 'custom-post-types' ),
			4  => __( 'Admin page updated', 'custom-post-types' ),
			6  => __( 'Admin page published', 'custom-post-types' ),
			7  => __( 'Admin page saved', 'custom-post-types' ),
			8  => __( 'Admin page submitted', 'custom-post-types' ),
			9  => __( 'Admin page scheduled', 'custom-post-types' ),
			10 => __( 'Admin page draft updated', 'custom-post-types' ),
		);
		return $messages;
	}

	/**
	 * @return void
	 */
	public function enqueue_assets() {
		wp_enqueue_style( CPT_OPTIONS_PREFIX . 'base', CPT_URL . 'assets/css/backend.css', array(), CPT_VERSION );
		if ( $this->load_js() ) {
			wp_enqueue_media();
			wp_enqueue_editor();
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( CPT_OPTIONS_PREFIX . 'base', CPT_URL . 'assets/js/backend.js', array( 'jquery', 'wp-i18n', 'wp-util', 'wp-hooks', 'wp-editor', 'wp-color-picker' ), CPT_VERSION, true );
			wp_localize_script( CPT_OPTIONS_PREFIX . 'base', 'cpt', cpt_utils()->get_js_variables() );
			wp_set_script_translations( CPT_OPTIONS_PREFIX . 'base', 'custom-post-types' );
		}
	}

	/**
	 * @return bool
	 */
	public function load_js() {
		$current_screen = get_current_screen();
		if (
			( ! empty( $current_screen->id ) &&
				(
					in_array( $current_screen->id, cpt_field_groups()->screens_with_fields, true ) ||
					(
						explode( '_page_', $current_screen->id ) &&
						! empty( explode( '_page_', $current_screen->id )[1] ) &&
						in_array( '_page_' . explode( '_page_', $current_screen->id )[1], cpt_field_groups()->screens_with_fields, true )
					)
				)
			) ||
			cpt_admin_notices()->has_notices
		) {
			return true;
		}
		return false;
	}

	/**
	 * @param $notices
	 *
	 * @return mixed
	 */
	public function welcome_notices( $notices ) {
		$title = cpt_utils()->get_notices_title();

		$buttons = array(
			array(
				'link'   => CPT_PLUGIN_REVIEW_URL,
				'label'  => __( 'Write a Review', 'custom-post-types' ),
				'target' => '_blank',
				'cta'    => true,
			),
			array(
				'link'   => CPT_PLUGIN_DONATE_URL,
				'label'  => __( 'Make a Donation', 'custom-post-types' ),
				'target' => '_blank',
			),
		);

		if ( ! cpt_utils()->is_pro_version_active() ) {
			$buttons[] = array(
				'link'   => CPT_PLUGIN_URL,
				'label'  => __( 'Get PRO version', 'custom-post-types' ),
				'target' => '_blank',
			);
		}

		// After installation notice
		$welcome_notice = array(
			'id'          => 'welcome_notice_400',
			'title'       => $title,
			'message'     => __( 'Thanks for using this plugin! Do you want to help us grow to add new features?', 'custom-post-types' ) . '<br><br>' . sprintf( __( 'The new version %1$s introduces a lot of new features and improves the core of the plugin.<br>For any problems you can download the previous version %2$s from the official page of the plugin from WordPress.org (Advanced View > Previous version).', 'custom-post-types' ), '<u>' . CPT_VERSION . '</u>', '<u>3.1.1</u>' ),
			'type'        => 'success',
			'dismissible' => true,
			'admin_only'  => 'true',
			'buttons'     => $buttons,
		);

		if ( time() < 1688169599 ) { // 30-06-2023 23:59:59
			$welcome_notice['message'] = $welcome_notice['message'] . '<br><br>' . sprintf( 'Use the coupon <strong><u>%s</u></strong> and get the PRO version with special discount until %s.', 'WELCOME-CPT-4', '30/06/2023' );
		}

		$notices[] = $welcome_notice;

		$installation_time = get_option( cpt_utils()->get_option_name( 'installation_time' ), null );
		$updated_time      = get_option( cpt_utils()->get_option_name( 'updated_time' ), null );

		if ( $installation_time && strtotime( '+7 day', $installation_time ) < time() ) {
			// After 7 days notice
			$notices[] = array(
				'id'          => 'welcome_notice_400_1',
				'title'       => $title,
				'message'     => __( 'Wow! More than 7 days of using this amazing plugin. Your support is really important.', 'custom-post-types' ),
				'type'        => 'success',
				'dismissible' => true,
				'admin_only'  => 'true',
				'buttons'     => $buttons,
			);
		}

		if ( $installation_time && strtotime( '+30 day', $installation_time ) < time() ) {
			// After 30 days notice
			$notices[] = array(
				'id'          => 'welcome_notice_400_1',
				'title'       => $title,
				'message'     => __( 'Wow! More than 30 days of using this amazing plugin. Your support is really important.', 'custom-post-types' ),
				'type'        => 'success',
				'dismissible' => true,
				'admin_only'  => 'true',
				'buttons'     => $buttons,
			);
		}

		if (
			! cpt_utils()->is_pro_version_active() &&
			$installation_time && strtotime( '+3 day', $installation_time ) < time()
		) {
			$buttons2 = array_reverse( $buttons );
			unset( $buttons2[2] );
			$buttons2[0]['cta'] = true;
			// After 3 days PRO notice
			$notices[] = array(
				'id'          => 'welcome_notice_pro',
				'title'       => $title,
				'message'     => '<p style="font-size: 1.3em;">' . __( "It's time to PRO, <u>go to the next level</u>:", 'custom-post-types' ) . '</p><p style="font-size: 1.3em; font-weight: bold;">⚡ Custom templates<br>⚡ Custom admin pages<br>⚡ Custom admin notices<br>⚡ +8 fields types<br>⚡ Export/Import settings</p><p style="font-size: 1.3em;">' . __( 'now you are ready, one small step, one big change!', 'custom-post-types' ) . '</p>',
				'type'        => 'success',
				'dismissible' => true,
				'admin_only'  => 'true',
				'buttons'     => $buttons2,
			);
		}

		if (
			! cpt_utils()->is_pro_version_active() &&
			(
				empty( $updated_time ) ||
				( $updated_time && strtotime( '+2 day', $updated_time ) < time() )
			) &&
			time() < 1700866799 // 24-11-2023 23:59:59
		) {
			// Black friday 2023 PRO notice, 2 days after update
			$buttons2 = array_reverse( $buttons );
			unset( $buttons2[1] );
			unset( $buttons2[2] );
			$buttons2[0]['cta'] = true;
			$notices[]          = array(
				'id'          => 'welcome_notice_bf_2023',
				'title'       => $title,
				'message'     => '<p style="font-size: 1.3em;">🎁 ' . __( '<u>BLACK FRIDAY 2023</u> special limited offer', 'custom-post-types' ) . ' 🎁</p><p style="font-size: 1.3em;">' . __( '<u>50% discount</u> on PRO version using the coupon code: <strong>BLACK-FRIDAY-2023</strong>', 'custom-post-types' ) . '</p>',
				'type'        => 'success',
				'dismissible' => true,
				'admin_only'  => 'true',
				'buttons'     => $buttons2,
			);
		}

		return $notices;
	}

	/**
	 * @return void
	 */
	public function remove_main_add_items_menu() {
		remove_submenu_page( 'edit.php?post_type=' . CPT_UI_PREFIX, 'post-new.php?post_type=' . CPT_UI_PREFIX );
	}

	/**
	 * @param $args
	 *
	 * @return mixed|null
	 */
	public function register_pro_pages( $args ) {
		$args = array_merge( cpt_utils()->get_args( 'core-admin-pages-pro' ), $args );
		return $args;
	}

	/**
	 * @param $args
	 *
	 * @return mixed
	 */
	public function register_ui_fields( $args ) {
		$args[] = cpt_utils()->get_args( 'fields-post-type' );
		$args[] = cpt_utils()->get_args( 'fields-taxonomy' );
		$args[] = cpt_utils()->get_args( 'fields-field-group' );
		return $args;
	}
}
