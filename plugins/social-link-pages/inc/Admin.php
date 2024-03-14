<?php

namespace SocialLinkPages;

use SocialLinkPages\Db;
use SocialLinkPages\User;

class Admin extends Singleton {

	protected function setup() {
		add_action( 'admin_menu', array( $this, 'add_menu_page' ), 10 );

		add_action( 'admin_enqueue_scripts',
			array( $this, 'admin_enqueue_scripts' ) );

		add_filter( 'plugin_action_links_'
		            . Social_Link_Pages()->plugin_basename,
			array( $this, 'add_action_links' ) );

		add_action( 'admin_init', [ $this, 'version_migration_per_user' ] );

		add_action( 'admin_init', [ $this, 'export_link_pages' ] );

		add_action( 'admin_init', [ $this, 'import_link_pages' ] );

		add_action( 'wp_ajax_' . Social_Link_Pages()->plugin_name_friendly
		            . '_update_page', array(
			$this,
			'ajax_update_page'
		) );
		add_action( 'wp_ajax_' . Social_Link_Pages()->plugin_name_friendly
		            . '_slug_exists', array(
			$this,
			'ajax_slug_exists'
		) );
		add_action( 'wp_ajax_' . Social_Link_Pages()->plugin_name_friendly
		            . '_create_page', array(
			$this,
			'ajax_create_page'
		) );
		add_action( 'wp_ajax_' . Social_Link_Pages()->plugin_name_friendly
		            . '_delete_page', array(
			$this,
			'ajax_delete_page'
		) );
		add_action( 'wp_ajax_' . Social_Link_Pages()->plugin_name_friendly
		            . '_get_pages', array(
			$this,
			'ajax_get_all_pages'
		) );

		add_action( 'wp_ajax_' . Social_Link_Pages()->plugin_name_friendly
		            . '_MailChimp_get_lists', array(
			$this,
			'ajax_MailChimp_get_lists'
		) );

		add_action( 'wp_ajax_' . Social_Link_Pages()->plugin_name_friendly
		            . '_MailChimp_get_list_merge_fields', array(
			$this,
			'ajax_MailChimp_get_list_merge_fields'
		) );

		add_action( 'wp_ajax_' . Social_Link_Pages()->plugin_name_friendly
		            . '_Convertkit_get_lists', array(
			$this,
			'ajax_Convertkit_get_lists'
		) );

		add_action( 'wp_ajax_' . Social_Link_Pages()->plugin_name_friendly
		            . '_deactivate_feedback', array(
			$this,
			'ajax_deactivate_feedback'
		) );

		add_action( 'wp_ajax_' . Social_Link_Pages()->plugin_name_friendly
		            . '_contact_support', array(
			$this,
			'ajax_contact_support'
		) );

		add_action( 'wp_ajax_' . Social_Link_Pages()->plugin_name_friendly
		            . '_update_user_app_settings', array(
			$this,
			'ajax_update_user_app_settings'
		) );

		add_action( 'admin_enqueue_scripts',
			[ $this, 'add_deactivate_thickbox' ] );

		add_action( 'admin_footer', [ $this, 'add_deactivate_html' ] );

		add_action(
			Social_Link_Pages()->plugin_name_friendly . '_do_plugin_update',
			[ $this, 'maybe_plugin_welcome' ]
		);
	}

	public function ajax_create_page() {
		global $wpdb;

		if ( empty( $_POST['_wpnonce'] )
		     || ! wp_verify_nonce( $_POST['_wpnonce'],
				Social_Link_Pages()->plugin_name_friendly )
		) {
			wp_send_json_error( [
				'error' => __( 'Invalid nonce.', 'social-link-pages' )
			] );
		}

		if ( empty( $_POST['page'] ) || empty ( $_POST['page']['slug'] ) ) {
			wp_send_json_error( [
				'error' => __( 'Parameters missing.', 'social-link-pages' )
			] );
		}

		$post_id = Db::instance()->create_page( $_POST['page'] );

		if ( false === $post_id ) {
			wp_send_json_error( __( 'Page could not be created.',
				'social-link-pages' ) );
		}

		$page_data = Db::instance()->page_data_from_post( $post_id );

		wp_send_json_success( $page_data );
	}

	public function ajax_slug_exists() {
		if ( empty( $_POST['_wpnonce'] )
		     || ! wp_verify_nonce( $_POST['_wpnonce'],
				Social_Link_Pages()->plugin_name_friendly )
		) {
			wp_send_json_error( [
				'error' => __( 'Invalid nonce.', 'social-link-pages' )
			] );
		}

		if ( empty ( $_POST['slug'] ) ) {
			wp_send_json_error( [
				'error' => __( 'Parameters missing.', 'social-link-pages' )
			] );
		}

		wp_send_json_success(
			array(
				'is_unique' => Db::instance()
				                 ->is_slug_unique( sanitize_title( $_POST['slug'] ) )
			)
		);
	}

	public function ajax_get_all_pages() {
		wp_send_json_success( $this->get_all_pages() );
	}

	public function ajax_update_page() {

		if ( empty( $_POST['_wpnonce'] )
		     || ! wp_verify_nonce( $_POST['_wpnonce'],
				Social_Link_Pages()->plugin_name_friendly )
		) {
			wp_send_json_error( [
				'error' => __( 'Invalid nonce.', 'social-link-pages' )
			] );
		}

		if ( empty( $_POST['page'] ) || empty ( $_POST['page']['id'] ) ) {
			wp_send_json_error( [
				'error' => __( 'Parameters missing.', 'social-link-pages' )
			] );
		}

		$success = Db::instance()->update_page_data(
			sanitize_title( $_POST['page']['id'] ),
			$_POST['page']
		);

		$success === false ? wp_send_json_error() : wp_send_json_success();
	}

	public function ajax_delete_page() {
		if ( empty( $_POST['_wpnonce'] )
		     || ! wp_verify_nonce( $_POST['_wpnonce'],
				Social_Link_Pages()->plugin_name_friendly )
		) {
			wp_send_json_error( [
				'error' => __( 'Invalid nonce.', 'social-link-pages' )
			] );
		}

		if ( empty ( $_POST['page_id'] ) ) {
			wp_send_json_error( [
				'error' => __( 'Parameters missing.', 'social-link-pages' )
			] );
		}

		$post_id = sanitize_title( $_POST['page_id'] );
		$post    = get_post( $post_id );

		if ( ! $post ) {
			wp_send_json_error( [
				'error' => __( 'Page not found.', 'social-link-pages' )
			] );
		}

		$permission_check = apply_filters(
			Social_Link_Pages()->plugin_name_friendly
			. '_delete_page_permission_check',
			true,
			$post
		);

		if ( ! $permission_check ) {
			wp_send_json_error( [
				'error' => __( 'You do not have permission to do that.',
					'social-link-pages' )
			] );
		}

		wp_trash_post( $post_id );

		wp_send_json_success();
	}

	public function ajax_MailChimp_get_lists() {

		if ( empty( $_POST['_wpnonce'] )
		     || ! wp_verify_nonce( $_POST['_wpnonce'],
				Social_Link_Pages()->plugin_name_friendly )
		) {
			wp_send_json_error( [
				'error' => __( 'Invalid nonce.', 'social-link-pages' )
			] );
		}

		if ( empty( $_POST['api'] ) ) {
			wp_send_json_error( [
				'error' => __( 'Parameters missing.', 'social-link-pages' )
			] );
		}

		// get domain from api key
		$APIKey    = sanitize_title( $_POST['api'] );
		$APIKeyArr = explode( '-', $APIKey );
		$domain    = end( $APIKeyArr );

		if ( empty( $domain ) ) {
			wp_send_json_error( [
				'error' => __( 'Invalid API key.', 'social-link-pages' )
			] );
		}

		$response = wp_remote_get(
			sprintf( 'https://%s.api.mailchimp.com/3.0/lists', $domain ),
			array(
				'headers' => array(
					'Authorization' => 'Basic ' . base64_encode( 'a:'
					                                             . $APIKey )
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			wp_send_json_error( [
				'error' => "Something went wrong: $error_message"
			] );
		}

		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( 200 !== intval( wp_remote_retrieve_response_code( $response ) ) ) {
			wp_send_json_error(
				[
					'error' => ! empty( $response_body['detail'] ) ? $response_body['detail'] : __( 'Whoops! You could not be subscribed.', 'social-link-pages' )
				],
				! empty( $response_body['response']['code'] ) ? $response_body['response']['code'] : 400
			);
		}

		if ( empty( $response_body['lists'] ) ) {
			$response_body['lists'] = [];
		}

		wp_send_json_success( $response_body['lists'] );
	}

	public function ajax_MailChimp_get_list_merge_fields() {

		if ( empty( $_POST['_wpnonce'] )
		     || ! wp_verify_nonce( $_POST['_wpnonce'],
				Social_Link_Pages()->plugin_name_friendly )
		) {
			wp_send_json_error( [
				'error' => __( 'Invalid nonce.', 'social-link-pages' )
			] );
		}

		if ( empty( $_POST['api'] ) ) {
			wp_send_json_error( [
				'error' => __( 'Parameters missing.', 'social-link-pages' )
			] );
		}

		// get domain from api key
		$APIKey    = sanitize_title( $_POST['api'] );
		$APIKeyArr = explode( '-', $APIKey );
		$domain    = end( $APIKeyArr );

		if ( empty( $domain ) ) {
			wp_send_json_error( [
				'error' => __( 'Invalid API key.', 'social-link-pages' )
			] );
		}

		if ( empty( $_POST['listId'] ) ) {
			wp_send_json_error( [
				'error' => __( 'Parameters missing.', 'social-link-pages' )
			] );
		}

		$listId = sanitize_title( $_POST['listId'] );

		$response = wp_remote_get(
			sprintf( 'https://%s.api.mailchimp.com/3.0/lists/%s/merge-fields', $domain, $listId ),
			array(
				'headers' => array(
					'Authorization' => 'Basic ' . base64_encode( 'a:'
					                                             . $APIKey )
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			wp_send_json_error( [
				'error' => "Something went wrong: $error_message"
			] );
		}

		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( 200 !== intval( wp_remote_retrieve_response_code( $response ) ) ) {
			wp_send_json_error(
				[
					'error' => ! empty( $response_body['detail'] ) ? $response_body['detail'] : __( 'Whoops! You could not be subscribed.', 'social-link-pages' )
				],
				! empty( $response_body['response']['code'] ) ? $response_body['response']['code'] : 400
			);
		}

		if ( empty( $response_body['merge_fields'] ) ) {
			$response_body['merge_fields'] = [];
		}

		wp_send_json_success( $response_body['merge_fields'] );
	}

	public function ajax_Convertkit_get_lists() {

		if ( empty( $_POST['_wpnonce'] )
		     || ! wp_verify_nonce( $_POST['_wpnonce'],
				Social_Link_Pages()->plugin_name_friendly )
		) {
			wp_send_json_error( [
				'error' => __( 'Invalid nonce.', 'social-link-pages' )
			] );
		}

		if ( empty( $_POST['api'] ) ) {
			wp_send_json_error( [
				'error' => __( 'Parameters missing.', 'social-link-pages' )
			] );
		}

		// get domain from api key
		$APIKey = sanitize_title( $_POST['api'] );

		$response = wp_remote_get(
			sprintf( 'https://api.convertkit.com/v3/forms?api_key=%s', $APIKey )
		);

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( __( 'Sorry, could not connect to ConvertKit',
				'social-link-pages' ) );
		}

		$body = json_decode( $response['body'] );

		if ( ! empty( $body->error ) ) {
			wp_send_json_error(
				[
					'error' => $body->message ? $body->message : $body->error
				],
				$response['response']['code']
			);
		}

		if ( empty( $body->forms ) ) {
			$body->forms = [];
		}

		wp_send_json_success( $body->forms );
	}

	public function export_link_pages() {
		if ( empty( $_GET['page'] )
		     || Social_Link_Pages()->plugin_name_friendly !== $_GET['page']
		) {
			return false;
		}

		if ( empty( $_GET['slp-action'] )
		     || 'export-pages' !== $_GET['slp-action']
		) {
			return false;
		}

		$args = apply_filters(
			Social_Link_Pages()->plugin_name_friendly . '_get_all_pages',
			array(
				'numberposts' => - 1,
				'post_type'   => Social_Link_Pages()->plugin_name_friendly,
				'post_status' => 'publish'
			) );

		$records = get_posts( $args );

		if ( empty( $records ) ) {
			return false;
		}


		$first_row = reset( $records );
		$fields    = array_keys( (array) $first_row );

		// Remove ID.
		$fields = Admin::array_unset_by_value( $fields, 'ID' );
		$fields = Admin::array_unset_by_value( $fields, 'filter' );

		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment;filename='
		        . Social_Link_Pages()->plugin_name_friendly . '.csv' );

		$fp = fopen( 'php://output', 'w' );

		fputcsv( $fp, $fields );
		foreach ( $records as $data ) {
			$data = (array) $data;

			unset( $data['ID'] );
			unset( $data['filter'] );

			fputcsv( $fp, $data );
		}
		fclose( $fp );

		exit;
	}

	public function import_link_pages() {
		global $wpdb;

		if ( empty( $_GET['page'] )
		     || Social_Link_Pages()->plugin_name_friendly !== $_GET['page']
		) {
			return false;
		}

		if ( empty( $_GET['slp-action'] )
		     || 'import-pages' !== $_GET['slp-action']
		) {
			return false;
		}

		$results = array();
		$handle  = fopen( $_FILES['csv']['tmp_name'], "r" );
		$fields  = fgetcsv( $handle, 1000, "," );

		if ( empty( $handle ) === false ) {
			while ( ( $data = fgetcsv( $handle, null, "," ) ) !== false ) {
				$row = [];
				foreach ( $data as $k => $value ) {
					$row[ $fields[ $k ] ] = $value;
				}
				$results[] = $row;
			}
			fclose( $handle );
		}

		foreach ( $results as $record ) {

			$record['post_author'] = get_current_user_id();

			$i = 0;
			while ( ! Db::instance()->is_slug_unique( $record['post_name'] ) ) {
				$i ++;
				$record['post_name'] = sprintf( '%s-%d', $record['post_name'], $i );
			}

			$success = $wpdb->insert(
				$wpdb->posts,
				$record
			);
		}

		wp_redirect(
			add_query_arg(
				[
					'page' => Social_Link_Pages()->plugin_name_friendly
				],
				admin_url( 'admin.php' )
			)
		);
		exit;
	}

	public function get_all_pages() {
		$args = apply_filters(
			Social_Link_Pages()->plugin_name_friendly . '_get_all_pages',
			array(
				'numberposts' => - 1,
				'post_type'   => Social_Link_Pages()->plugin_name_friendly,
				'post_status' => 'publish'
			) );

		$records = get_posts( $args );

		$pages = array();
		foreach ( $records as $record ) {
			$pages[] = Db::instance()->page_data_from_post( $record );
		}

		return $pages;
	}

	public function admin_enqueue_scripts( $hook ) {
		// Load only on ?page=mypluginname
		if ( $hook != 'toplevel_page_'
		              . Social_Link_Pages()->plugin_name_friendly
		) {
			return;
		}

		$this->enqueue_scripts();
	}

	public function version_migration_per_user() {
		if ( empty( $_GET['page'] ) || Social_Link_Pages()->plugin_name_friendly !== $_GET['page'] ) {
			return;
		}

		$current_user_app_settings = User::instance()->get_current_user_settings();

		if ( empty( $current_user_app_settings['gave_permission_to_collect_stats'] ) ) {
			User::instance()->update_current_user_settings( [ 'gave_permission_to_collect_stats' => - 1 ] );
		}
	}

	public function enqueue_scripts() {

		// Required to avoid tiny cloud notice.
		wp_enqueue_script( 'wp-tinymce' );

		wp_enqueue_media();

		wp_enqueue_code_editor( array( 'type' => 'text/css' ) );

		$enqueued = array(
			'style'  => array(),
			'script' => array()
		);

		if ( ! Social_Link_Pages()->use_local() ) {
			$styles = Social_Link_Pages()->get_asset_urls( 'admin', 'css' );

			foreach ( $styles as $style ) {
				$enqueued['style'][] = $style['name'];
				wp_enqueue_style(
					$style['name'],
					$style['url'],
					array(),
					$style['version']
				);
			}
		}

		if ( ! Social_Link_Pages()->use_local() ) {
			$scripts = Social_Link_Pages()->get_asset_urls( 'admin', 'js' );
		} else {
			$scripts = [
				[
					'name'    => 'bundle.js',
					'url'     => 'https://localhost:3000/static/js/bundle.js',
					'version' => Social_Link_Pages()->plugin_data()['Version'],
				],
				[
					'name'    => 'vendors~main.chunk.js',
					'url'     => 'https://localhost:3000/static/js/vendors~main.chunk.js',
					'version' => Social_Link_Pages()->plugin_data()['Version'],
				],
//				[
//					'name'    => '0.chunk.js',
//					'url'     => 'https://localhost:3000/static/js/0.chunk.js',
//					'version' => Social_Link_Pages()->plugin_data()['Version'],
//				],
//				[
//					'name'    => '1.chunk.js',
//					'url'     => 'https://localhost:3000/static/js/1.chunk.js',
//					'version' => Social_Link_Pages()->plugin_data()['Version'],
//
//				],
				[
					'name'    => 'main.chunk.js',
					'url'     => 'https://localhost:3000/static/js/main.chunk.js',
					'version' => Social_Link_Pages()->plugin_data()['Version'],

				],
			];
		}

		foreach ( $scripts as $script ) {
			$enqueued['script'][] = $script['name'];
			wp_enqueue_script(
				$script['name'],
				$script['url'],
				array( 'wp-i18n' ),
				$script['version'],
				true
			);

			wp_set_script_translations( $script['name'],
				'social-link-pages' );
		}

		// Localize data to first SLP script.
		$first_script = reset( $scripts );
		$this->localize_footer_vars( $first_script['name'] );

		return $enqueued;
	}

	public function localize_footer_vars( $handle ) {

		$current_user              = wp_get_current_user();
		$current_user_app_settings = User::instance()->get_current_user_settings();

//		$build_url = Social_Link_Pages()->use_local()
//			? 'https://localhost:3000'
//			: sprintf(
//				'%sadmin/build',
//				Social_Link_Pages()->plugin_dir_url,
//			);

		$app_vars = apply_filters(
			Social_Link_Pages()->plugin_name_friendly
			. '_admin_footer_vars',
			array(
				'admin_ajax'    => admin_url( 'admin-ajax.php' ),
				'rest_nonce'    => wp_create_nonce( 'wp_rest' ),
				'site_url'      => untrailingslashit( site_url() ),
				'plugin_url'    => Social_Link_Pages()->plugin_dir_url,
//				'build_url'  => untrailingslashit( $build_url ),
				'version'       => Social_Link_Pages()->plugin_data()['Version'],
				'pages'         => $this->get_all_pages(),
				'settings'      => [
					'builder_header_background_gradient_start_color' => '#c9e265',
					'builder_header_background_gradient_end_color'   => '#ffffff00',
					'branding_custom_title'                          => false,
					'is_local'                                       => Social_Link_Pages()->use_local(),
				],
				'user'          => array_merge( $current_user_app_settings, [ 'email' => $current_user->user_email ] ),
				'wpApiSettings' => [
					'root'  => esc_url_raw( rest_url() ),
					'nonce' => wp_create_nonce( 'wp_rest' )
				]
			)
		);

		wp_localize_script(
			$handle,
			Social_Link_Pages()->plugin_name_friendly,
			$app_vars
		);
	}

	public function add_menu_page() {

		$menu_page_capability = apply_filters(
			Social_Link_Pages()->plugin_name_friendly
			. '_menu_page_capability',
			'read'
		);

		add_menu_page(
			__( 'Link Pages', 'textdomain' ),
			'Link Pages',
			$menu_page_capability,
			Social_Link_Pages()->plugin_name_friendly,
			array( $this, 'render_menu_page' ),
			'data:image/svg+xml;base64,'
			. \base64_encode( '<svg viewBox="0 0 129 74" width="18" height="18" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2"><path d="M0 73.5l30.5-36.75L0 .125 8.625 0l.049.059L12.755 0l.049.059L16.884 0l.049.059L21.014 0l.049.059L25.144 0l.049.059L29.274 0l.049.059L33.404 0l.049.059L37.534 0l.049.059L41.664 0l.049.059L45.794 0l.049.059L49.924 0l30.5 36.875-30.5 36.625H0zM93.422.059L97.503 0l30.5 36.875h.001L97.504 73.5H84.748l30.5-36.75L84.748.125 93.373 0l.049.059zm-23.79 0L73.713 0l30.5 36.875h.001L73.714 73.5H60.958l30.5-36.75L60.958.125 69.583 0l.049.059z" fill="#9EA3A8" fill-rule="nonzero"/></svg>' )
		);

		add_submenu_page(
			Social_Link_Pages()->plugin_name_friendly,
			__( 'Link Pages', 'textdomain' ),
			'Link Pages',
			'read',
			Social_Link_Pages()->plugin_name_friendly,
			array( $this, 'render_menu_page' )
		);
	}

	public function render_menu_page() {
		?>
		<div
			id="<?php echo Social_Link_Pages()->plugin_name_friendly ?>-root"></div>
		<?php wp_nonce_field( Social_Link_Pages()->plugin_name_friendly,
			Social_Link_Pages()->plugin_name_friendly . '_wpnonce' ) ?>
		<?php
	}

	public function add_action_links( $links ) {
		$link = array(
			sprintf( '<a href="%s">Settings</a>',
				admin_url( 'admin.php?page='
				           . Social_Link_Pages()->plugin_name_friendly ) ),
		);

		return array_merge( $links, $link );
	}

	public static function array_unset_by_value( $arr, $val ) {
		if ( ( $key = array_search( $val, $arr ) ) !== false ) {
			unset( $arr[ $key ] );
		}

		return $arr;
	}

	public function add_deactivate_thickbox( $hook ) {
		if ( $hook != 'plugins.php' ) {
			return;
		}

		wp_enqueue_script(
			'social_link_pages-deactivate',
			sprintf( '%sclient/js/admin-deactivate.js',
				Social_Link_Pages()->plugin_dir_url ),
			array( 'jquery' ),
			Social_Link_Pages()->plugin_data()['Version']
		);
	}

	public function add_deactivate_html() {
		?>
		<div style="display: none">
			<style>
				#social_link_pages-deactivate-form-letter {
					background: #ffffe0;
					border: 1px solid #eeeed0;
					font-size: 1.142rem;
					line-height: 1.5;
					padding: 1rem;
				}

				#social_link_pages-deactivate-form-letter div:first-child {
					font-size: 1.236rem;
					font-weight: bold;
					margin-bottom: .5em;
				}

				.social_link_pages-deactivate-choice {
					background: #f6f6f6;
					border: 1px solid #ddd;
					border-radius: .5rem;
					margin: .5rem 0;
					padding: .5rem;
				}

				#social_link_pages-deactivate-form label {
					display: block;
				}

				.social_link_pages-deactivate-textarea {
					display: none;
					padding-top: .5rem;
				}

				#social_link_pages-deactivate-form-buttons {
					display: flex;
					justify-content: space-between;
				}
			</style>
			<div id="social_link_pages-deactivate-modal">
				<form id="social_link_pages-deactivate-form"
					style="background: white; padding: 5px;">
					<button type="button"
						style="position: absolute; top: 10px; right: 10px;"
						class="button social_link_pages-deactivate-remove">
						<?php _e( 'Close', 'social-link-pages' ) ?>
					</button>
					<div id="social_link_pages-deactivate-form-letter">
						<div>
							Hello!
						</div>
						<div>
							<b>Please let me know why you are deactivating Social Link Pages!</b>
							If you have a minute, I'd really appreciate if you told me why so I can try to make it a
							bit better. Thank you so much!
						</div>
						<div style="text-align: right;">
							- Corey, Social Link Pages creator
						</div>
					</div>
					<div class="social_link_pages-deactivate-choice">
						<label><input type="radio" name="reason"
								value="deactivated: not what I was looking for"><?php _e( 'The plugin is not what I was looking for', 'social-link-pages' ); ?>
						</label>
						<div class="social_link_pages-deactivate-textarea">
				<textarea rows="2" class="large-text"
					name="comments[]"
					placeholder="<?php _e( 'What were you looking for?', 'social-link-pages' ); ?>"
				></textarea>
						</div>
					</div>
					<div class="social_link_pages-deactivate-choice">
						<label><input type="radio" name="reason"
								value="deactivated: decided to use something else"><?php _e( 'I decided to use something else', 'social-link-pages' ); ?>
						</label>
						<div class="social_link_pages-deactivate-textarea">
				<textarea rows="2" class="large-text"
					name="comments[]"
					placeholder="<?php _e( 'What else did you decide to use?', 'social-link-pages' ); ?>"
				></textarea>
						</div>
					</div>
					<div class="social_link_pages-deactivate-choice">
						<label><input type="radio" name="reason"
								value="deactivated: didn't have the features I wanted"><?php _e( 'The plugin didn\'t have the features I wanted', 'social-link-pages' ); ?>
						</label>
						<div class="social_link_pages-deactivate-textarea">
				<textarea rows="2" class="large-text"
					name="comments[]"
					placeholder="<?php _e( 'What features did you want?', 'social-link-pages' ); ?>"
				></textarea>
							<p>
								<b><?php _e( 'Want to request the feature instead of uninstalling?', 'social-link-pages' ); ?></b>
								<a href="<?php echo add_query_arg( [
									'page'  => Social_Link_Pages()->plugin_name_friendly,
									'modal' => 'contact'
								], admin_url( 'admin.php' ) ) ?>"
									class="button">
									<?php _e( 'Email us!', 'social-link-pages' ); ?>
								</a>
							</p>
						</div>
					</div>
					<div class="social_link_pages-deactivate-choice">
						<label><input type="radio" name="reason"
								value="deactivated: didn't work as expected"><?php _e( 'The plugin didn\'t work as expected', 'social-link-pages' ); ?>
						</label>
						<div class="social_link_pages-deactivate-textarea">
				<textarea rows="2" class="large-text"
					name="comments[]"
					placeholder="<?php _e( 'What were you expecting?', 'social-link-pages' ); ?>"
				></textarea>
						</div>
					</div>
					<div class="social_link_pages-deactivate-choice">
						<label><input type="radio" name="reason"
								value="deactivated: is not working"><?php _e( 'The plugin is not working', 'social-link-pages' ); ?>
						</label>
						<div class="social_link_pages-deactivate-textarea">
				<textarea rows="2" class="large-text"
					name="comments[]"
					placeholder="<?php _e( 'What didn\'t work?', 'social-link-pages' ); ?>"
				></textarea>
						</div>
					</div>
					<div id="social_link_pages-deactivate-form-buttons">
						<button type="button"
							class="button button-primary "
							id="social_link_pages-deactivate-submit">
							<?php _e( 'Submit feedback &amp; Deactivate',
								'social-link-pages' ) ?>
						</button>
						<button type="button"
							style="background: none; border: none; text-decoration: underline;"
							class="button"
							id="social_link_pages-deactivate-skip">
							<?php _e( 'Skip &amp; Deactivate',
								'social-link-pages' ) ?>
						</button>
					</div>
					<input type="hidden" name="action"
						value="social_link_pages_deactivate_feedback" />
					<?php wp_nonce_field( 'social_link_pages_deactivate_feedback',
						'social_link_pages' ); ?>

				</form>
			</div>
		</div>
		<?php
	}

	public function ajax_deactivate_feedback() {
		global $wpdb;

		if ( empty( $_POST['social_link_pages'] )
		     || ! wp_verify_nonce( $_POST['social_link_pages'],
				'social_link_pages_deactivate_feedback' )
		) {
			return false;
		}

		if ( empty( $_POST['reason'] ) ) {
			return;
		}

		try {
			$subject = stripcslashes( sprintf( '[slp] %s', $_POST['reason'] ) );
			$message = stripcslashes( sprintf(
				"Reason: %s\n\n%s\n\n%s",
				$subject,
				stripcslashes( implode( $_POST['comments'] ) ),
				site_url()
			) );

			wp_mail(
				'support@sociallinkpages.com',
				$subject,
				$message,
				sprintf(
					'From: "%s" <%s>',
					get_option( 'blogname' ),
					get_option( 'admin_email' )
				)
			);

			wp_send_json_success();
		} catch ( Exception $e ) {
			wp_send_json_error();
		}
	}

	public function ajax_update_user_app_settings() {
		if ( empty( $_POST['_wpnonce'] )
		     || ! wp_verify_nonce( $_POST['_wpnonce'],
				Social_Link_Pages()->plugin_name_friendly )
		) {
			wp_send_json_error( [
				'error' => __( 'Invalid nonce.', 'social-link-pages' )
			] );
		}

		User::instance()->update_current_user_settings( [
			sanitize_title( $_POST['key'] ) => sanitize_text_field( $_POST['value'] )
		] );

		wp_send_json_success();
	}

	public function ajax_contact_support() {
		global $wpdb;

		if ( empty( $_POST['_wpnonce'] )
		     || ! wp_verify_nonce( $_POST['_wpnonce'],
				Social_Link_Pages()->plugin_name_friendly )
		) {
			wp_send_json_error( [
				'error' => __( 'Invalid nonce.', 'social-link-pages' )
			] );
		}

		if ( empty( $_POST['message'] ) ) {
			return;
		}

		$from_blog = sprintf(
			'From: "%s" <%s>',
			get_option( 'blogname' ),
			get_option( 'admin_email' )
		);

		$from = sprintf(
			'From: %s',
			! empty( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : $from_blog
		);

		$subject = ! empty( $_POST['subject'] ) ? sanitize_text_field( $_POST['subject'] ) : 'Social Link Pages support';

		try {
			wp_mail(
				'support@sociallinkpages.com',
				$subject,
				stripcslashes( sprintf(
					"%s\n\n%s",
					stripcslashes( sanitize_textarea_field( $_POST['message'] ) ),
					site_url()
				) ),
				$from
			);

			wp_send_json_success();
		} catch ( Exception $e ) {
			wp_send_json_error();
		}
	}

	public function maybe_plugin_welcome( $option_record ) {
		if ( ! empty( $option_record['viewed_welcome_on_install'] ) ) {
			return false;
		}

		$plugin_option_data                              = Db::get_option_plugin_data();
		$plugin_option_data['viewed_welcome_on_install'] = \Date( 'Y-m-d H:i:s' );

		update_option(
			Db::get_option_name_plugin_data(),
			$plugin_option_data,
			false
		);

		wp_redirect( add_query_arg(
			[
				'page'  => Social_Link_Pages()->plugin_name_friendly,
				'modal' => 'welcome'
			],
			admin_url( 'admin.php' )
		) );
		exit;
	}
}
