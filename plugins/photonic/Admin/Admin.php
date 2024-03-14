<?php

namespace Photonic_Plugin\Admin;

use Photonic_Plugin\Admin\Wizard\Wizard;
use Photonic_Plugin\Core\Photonic;
use Photonic_Plugin\Platforms\SmugMug;

class Admin {
	public function __construct() {
		global $photonic_disable_flow_editor_global;

		// General
		add_action('admin_head', [$this, 'admin_head']);
		add_action('admin_enqueue_scripts', [&$this, 'add_admin_scripts']);

		// Vanilla Editor
		add_filter('media_upload_tabs', [&$this, 'media_upload_tabs']);
		add_action('media_upload_photonic', [&$this, 'media_upload_photonic']);

		add_action('print_media_templates', [&$this, 'edit_gallery']);

		// Gutenberg
		add_action('enqueue_block_editor_assets', [&$this, 'enqueue_gutenberg_assets']);

		if (empty($photonic_disable_flow_editor_global)) {
			add_action('media_buttons', [&$this, 'add_photonic_button']);
			add_action('admin_action_photonic_wizard', [&$this, 'open_wizard']);
			add_action('wp_ajax_photonic_wizard_next_screen', [&$this, 'wizard_next_screen']);
			add_action('wp_ajax_photonic_wizard_more', [&$this, 'flow_more']);
		}
	}

	public function admin_head() {
		// check user permissions
		if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
			return;
		}

		global $photonic_disable_editor, $photonic_disable_editor_post_type;
		$disabled_types = explode(',', esc_attr($photonic_disable_editor_post_type));
		$screen = get_current_screen();

		// check if WYSIWYG is enabled
		if (user_can_richedit() && empty($photonic_disable_editor) && !in_array($_REQUEST['post_type'] ?? 'post', $disabled_types, true) && 'post' === $screen->base) {
			$this->prepare_mce_data();
			add_filter('mce_external_plugins', [$this, 'mce_photonic'], 5);
			add_filter('mce_buttons', [$this, 'mce_flow_button'], 5);
		}
	}

	public function add_photonic_button() {
		add_thickbox();
		$url = $this->get_wizard_modal_url();

		printf(
			'<a href="%1$s" class="button photonic-button thickbox" id="photonic-add-gallery" title="Photonic Gallery"><img class="wp-media-buttons-icon" src="' . esc_url(PHOTONIC_URL) . 'include/images/Photonic-20.png" alt="%2$s" /> %2$s</a>',
			esc_url($url),
			esc_html__('Add / Edit Photonic Gallery', 'photonic')
		);
	}

	public function open_wizard() {
		define('IFRAME_REQUEST', true);
		$this->enqueue_wizard_scripts();
		iframe_header(esc_html__('Add / Edit Photonic Gallery', 'photonic'));
		require_once PHOTONIC_PATH . '/Admin/Wizard/Screen_Flow.php';
		iframe_footer();
		exit;
	}

	public function enqueue_wizard_scripts() {
		if (current_user_can('edit_posts') && wp_verify_nonce($_REQUEST['nonce'], 'photonic-wizard-' . get_current_user_id())) {
			global $photonic_alternative_shortcode;
			$wizard_js = [
				'ajaxurl'                   => admin_url('admin-ajax.php'),
				'shortcode'                 => sanitize_text_field($photonic_alternative_shortcode ?: 'gallery'),
				'insert_gallery'            => esc_html__('Insert Gallery', 'photonic'),
				'update_gallery'            => esc_html__('Update Gallery', 'photonic'),
				'error_mandatory'           => esc_html__('Please fill the mandatory fields. Mandatory fields are marked with a red "*".', 'photonic'),
				'media_library_title'       => esc_html__('Select from WordPress Media Library', 'photonic'),
				'media_library_button'      => esc_html__('Select', 'photonic'),
				'info_editor_not_shortcode' => esc_html__('The text selected in the editor is not a Photonic shortcode. Creating a new shortcode.', 'photonic'),
				'info_editor_block_select'  => sprintf(esc_html__('%1$sHint:%2$s To edit an existing Photonic block simply click on the block.', 'photonic'), '<strong>', '</strong>'),
			];
			if (!empty($_REQUEST['shortcode'])) {
				$wizard_js['shortcode'] = sanitize_text_field($_REQUEST['shortcode']);
			}
			wp_enqueue_style('photonic-flow', PHOTONIC_URL . 'include/css/admin/admin-flow.css', [], Photonic::get_version(PHOTONIC_PATH . '/include/css/admin/admin-flow.css'));
			wp_enqueue_script('photonic-flow-js', PHOTONIC_URL . 'include/js/admin/flow.js', ['jquery'], Photonic::get_version(PHOTONIC_PATH . '/include/js/admin/flow.js'), false);
			wp_localize_script('photonic-flow-js', 'Photonic_Wizard_JS', $wizard_js);
		}
	}

	public function wizard_next_screen() {
		if (check_ajax_referer('photonic-wizard-next-' . get_current_user_id())) {
			require_once PHOTONIC_PATH . '/Admin/Wizard/Wizard.php';
			if (isset($_POST['provider'])) {
				$safe_tags = array_merge_recursive(
					[
						'form' => [
							'id' => true,
							'class' => true,
							'name' => true,
							'data-photonic-submission' => true,
							'data-photonic-submission-pending' => true,
						],

						'input'  => [
							'type' => true,
							'id' => true,
							'name' => true,
							'value' => true,
							'checked' => true,
							'class' => true,
							'data-photonic-option-condition' => true,
						],

						'label' => [
							'class' => true,
							'for' => true,
						],

						'select' => [
							'name' => true,
							'id' => true,
							'class' => true,
							'aria-describedby' => true,
						],

						'option' => [
							'value' => true,
							'selected' => true,
							'data-photonic-option-condition' => true,
						],

						// Custom data attributes
						'div' => [
							'data-current-screen' => true,

							'data-photonic-flow-selector-for' => true,
							'data-photonic-flow-selector-mode' => true,

							'data-photonic-condition' => true,
							'data-photonic-flow-sequence' => true,
							'data-photonic-flow-sequence-group' => true,

							'data-photonic-flow-selection-id' => true,
							'data-photonic-selection-alt-id' => true,
							'data-photonic-selection-alt-id-2' => true,
						],

						'span' => [
							'data-photonic-selection-id' => true,
						],

						'ol' => [
							'data-photonic-date-filter' => true,
							'data-photonic-filter-count' => true,
							'data-photonic-date-range-filter' => true,
						],

						'a' => [
							'data-photonic-add-date' => true,
							'data-photonic-mark-for' => true,
							'data-photonic-more-link' => true,
							'data-photonic-display-type' => true,
							'data-photonic-provider' => true,
							'data-photonic-nonce' => true,
						]
					],
					wp_kses_allowed_html('post')
				);

				$wizard = new Wizard();
				// echo $wizard->get_screen(); // Cannot escape ... has form and JS markup; individual parts are escaped as appropriate.
				echo wp_kses($wizard->get_screen(), $safe_tags); // Cannot escape ... has form and JS markup; individual parts are escaped as appropriate.
			}
		}
		die();
	}

	public function flow_more() {
		if (check_ajax_referer('photonic-wizard-more-' . get_current_user_id())) {
			require_once PHOTONIC_PATH . '/Admin/Wizard/Wizard.php';
			if (isset($_POST['url']) && isset($_POST['provider']) && isset($_POST['display_type'])) {
				$url = base64_decode(sanitize_text_field($_POST['url'])); // The `url` for fetching additional results is base64-encoded in the wizard in flow.js using `btoa`

				$provider = sanitize_text_field($_POST['provider']);
				$display_type = sanitize_text_field($_POST['display_type']);
				$existing = [];
				if (!empty($_POST['filter'])) {
					$existing['selected_data'] = sanitize_text_field($_POST['filter']);
				}
				$args = ['sslverify' => PHOTONIC_SSL_VERIFY];
				if ('smugmug' === $provider) {
					require_once PHOTONIC_PATH . '/Platforms/SmugMug.php';
					$gallery = SmugMug::get_instance();

					$body = [
						'APIKey'        => $gallery->api_key,
						'_accept'       => 'application/json',
						'_expandmethod' => 'inline',
						'_verbosity'    => '1',
					];

					if ('album-photo' === $display_type || 'multi-album' === $display_type) {
						$body['_expand'] = 'HighlightImage.ImageSizes';
					}

					$args['body'] = $body;
				}

				// URL has come via base64, so we will check that it is directing to a whitelist of hosts...
				$url_parts = wp_parse_url($url);
				if (in_array($url_parts['host'], Wizard::base_apis(), true)) {
					$response = wp_remote_request($url, $args);
					$wizard = new Wizard();
					$objects = $wizard->process_response($response, $provider, $display_type, [], $existing, $url, true);

					if (!empty($objects['success'])) {
						echo wp_kses_post($objects['success']);
					}
					elseif (!empty($objects['error'])) {
						if ($objects['error'] !== $wizard->error_no_data_returned) { // Typically happens if there is a "next token" without corresponding data
							echo wp_kses_post($objects['error']);
						}
					}
				}
			}
		}
		die();
	}

	/**
	 * Adds all scripts and their dependencies to the <head> of the Photonic administration page. This takes care to not add scripts on other admin pages.
	 *
	 * @param $hook
	 * @return void
	 */
	public function add_admin_scripts($hook) {
		if ('media-upload-popup' === $hook) {
			wp_enqueue_script('jquery');
			wp_enqueue_style('photonic-upload', PHOTONIC_URL . 'include/css/admin/admin-form.css', [], Photonic::get_version(PHOTONIC_PATH . '/include/css/admin/admin-form.css'));
		}
		elseif ('post-new.php' === $hook || 'post.php' === $hook) {
			global $photonic_disable_editor, $photonic_disable_editor_post_type;
			$disabled_types = explode(',', $photonic_disable_editor_post_type);
			wp_enqueue_style('photonic-upload', PHOTONIC_URL . 'include/css/admin/admin-form.css', [], Photonic::get_version(PHOTONIC_PATH . '/include/css/admin/admin-form.css'));
			if (empty($photonic_disable_editor) && !in_array($_REQUEST['post_type'] ?? 'post', $disabled_types, true)) {
				$this->prepare_mce_data();

				add_editor_style(PHOTONIC_URL . 'include/css/admin/admin-editor.css?' . Photonic::get_version(PHOTONIC_PATH . '/include/css/admin/admin-editor.css'));
			}
		}
		elseif ('widgets.php' === $hook) {
			Photonic::enqueue_widget_scripts();
		}
	}

	public function prepare_mce_data() {
		$url = $this->get_wizard_modal_url();
		$js_array = $this->get_wizard_js_parameters($url);

		wp_enqueue_script('photonic-admin-js', PHOTONIC_URL . 'include/js/admin/gallery-settings.js', ['jquery', 'media-views', 'media-upload'], Photonic::get_version(PHOTONIC_PATH . '/include/js/admin/gallery-settings.js'), false);
		wp_localize_script('photonic-admin-js', 'Photonic_Admin_JS', $js_array);
	}

	public function mce_photonic($plugin_array) {
		$plugin_array['photonic'] = PHOTONIC_URL . 'include/js/admin/mce.js?' . Photonic::get_version(PHOTONIC_PATH . '/include/js/admin/mce.js');
		return $plugin_array;
	}

	public function mce_flow_button($buttons) {
		array_push($buttons, 'photonic_wizard');
		return $buttons;
	}

	public function enqueue_gutenberg_assets() {
		if (function_exists('register_block_type')) {
			wp_enqueue_script(
				'photonic-gutenberg',
				PHOTONIC_URL . 'include/js/admin/block.js',
				['wp-blocks', 'wp-i18n', 'wp-element', 'shortcode', 'thickbox'],
				Photonic::get_version(PHOTONIC_PATH . '/include/js/admin/block.js'),
				false
			);

			if (function_exists('gutenberg_get_jed_locale_data')) {
				$locale = gutenberg_get_jed_locale_data('photonic');
				$content = 'wp.i18n.setLocaleData(' . wp_json_encode($locale) . ', "photonic");';
				wp_script_add_data('photonic-gutenberg', 'data', $content);
			}

			$url = $this->get_wizard_modal_url();
			$js_array = $this->get_wizard_js_parameters($url);
			wp_localize_script('photonic-gutenberg', 'Photonic_Gutenberg_JS', $js_array);

			wp_enqueue_style(
				'photonic-gutenberg',
				PHOTONIC_URL . 'include/css/admin/admin-block.css',
				['thickbox'],
				Photonic::get_version(PHOTONIC_PATH . '/include/css/admin/admin-block.css')
			);
		}
	}

	/**
	 * Adds a "Photonic" tab to the "Add Media" panel.
	 *
	 * @param $tabs
	 * @return array
	 */
	public function media_upload_tabs($tabs) {
		if (!function_exists('is_gutenberg_page') || (function_exists('is_gutenberg_page') && !is_gutenberg_page())) {
			$tabs['photonic'] = 'Photonic';
		}
		return $tabs;
	}

	/**
	 * Invokes the form to display the photonic insertion screen in the "Add Media" panel. The call to wp_iframe ensures that the right CSS and JS are called.
	 *
	 * @return void
	 */
	public function media_upload_photonic() {
		wp_iframe([&$this, 'media_upload_photonic_form']);
	}

	/**
	 * First prints the standard buttons for media upload, then shows the UI for Photonic.
	 *
	 * @return void
	 */
	public function media_upload_photonic_form() {
		media_upload_header();
		require_once PHOTONIC_PATH . '/Admin/Forms/Add_Gallery.php';
	}

	public function edit_gallery() {
		global $photonic_disable_editor, $photonic_disable_editor_post_type;
		$disabled_types = explode(',', $photonic_disable_editor_post_type);
		// check if WYSIWYG is enabled
		if (user_can_richedit() && empty($photonic_disable_editor) && !in_array($_REQUEST['post_type'] ?? 'post', $disabled_types, true)) {
			require_once PHOTONIC_PATH . '/Admin/Forms/Edit_Gallery_Templates.php';
		}
	}

	/**
	 * @return string
	 */
	private function get_wizard_modal_url(): string {
		$user = get_current_user_id();
		if (0 === $user) {
			$user = wp_rand(1);
		}

		$post = empty($_REQUEST['post']) ? '' : sanitize_text_field($_REQUEST['post']);

		$url = add_query_arg(
			[
				'action'    => 'photonic_wizard',
				'class'     => 'photonic-flow',
				'post_id'   => $post,
				'nonce'     => wp_create_nonce('photonic-wizard-' . $user),
				'width'     => '1000',
				'height'    => '600',
				'TB_iframe' => 'true',
			],
			admin_url('admin.php')
		);
		return $url;
	}

	/**
	 * @param string $url
	 * @return array
	 */
	private function get_wizard_js_parameters($url): array {
		global $photonic_alternative_shortcode, $photonic_disable_flow_editor, $photonic_disable_flow_editor_global;
		$js_array = [
			'flow_url'             => $url,
			'ajaxurl'              => admin_url('admin-ajax.php'),
			'shortcode'            => sanitize_text_field($photonic_alternative_shortcode ?: 'gallery'),
			'disable_flow'         => !empty($photonic_disable_flow_editor) || !empty($photonic_disable_flow_editor_global),
			'default_gallery_type' => 'default',
			'plugin_dir'           => plugin_dir_url(__FILE__),
		];
		return $js_array;
	}
}

new Admin();
