<?php defined( 'ABSPATH' ) || exit;
if (!class_exists('WP_Sheet_Editor_Post_Types_Teaser')) {

	/**
	 * Display the post types item in the toolbar to tease users of the free 
	 * version into purchasing the premium plugin.
	 */
	class WP_Sheet_Editor_Post_Types_Teaser {

		static private $instance = false;
		var $post_types = array();

		private function __construct() {
			
		}

		function init() {
			// Disabled
			return;
			if (!is_admin()) {
				return;
			}
			if (class_exists('WP_Sheet_Editor_CPTs')) {
				return;
			}
			$post_types = VGSE()->helpers->get_all_post_types_names(false);

			if (isset($post_types['post'])) {
				unset($post_types['post']);
			}
			if (isset($post_types['page'])) {
				unset($post_types['page']);
			}

			// We will display the teaser for the first 2 post types only
			$this->post_types = array_slice($post_types, 0, 2);
			add_action('vg_sheet_editor/editor/before_init', array($this, 'register_toolbar_items'));
		}

		function register_toolbar_items($editor) {

			$allowed_post_types = VGSE()->helpers->get_allowed_post_types();

			foreach ($post_types as $post_type) {

				foreach ($this->post_types as $post_type_tease) {

					// Skip if the post type tease is found on the enabled post types
					if (isset($allowed_post_types[$post_type_tease])) {
						continue;
					}

					$label = VGSE()->helpers->get_post_type_label($post_type_tease);

					if ($post_type_tease === apply_filters('vg_sheet_editor/woocommerce/product_post_type_key', 'product')) {
						$label = 'WooCommerce ' . $label;
					}

					$editor->args['toolbars']->register_item('edit_' . $post_type_tease, array(
						'type' => 'button',
						'content' => sprintf(__('Edit %s', 'vg_sheet_editor' ), $label),
						'icon' => 'fa fa-edit',
						'allow_in_frontend' => false,
						'extra_html_attributes' => 'data-remodal-target="modal-edit-' . $post_type_tease . '"',
						'toolbar_key' => 'secondary',
						'footer_callback' => array($this, 'render_post_type_modal')
							), $post_type);
				}
			}
		}

		function render_post_type_modal($current_post_type) {
			foreach ($this->post_types as $post_type_tease) {
				?>

				<style>
					.vg-naked-list {	
						list-style: initial;
						text-align: left;
						margin-left: 30px;
					}
				</style>
				<div class="remodal remodal<?php echo rand(8, 888); ?>" data-remodal-id="modal-edit-<?php echo esc_attr($post_type_tease); ?>" data-remodal-options="closeOnOutsideClick: false, hashTracking: false">

					<div class="modal-content">
						<h3><?php printf(__('Edit WordPress %s', 'vg_sheet_editor' ), VGSE()->helpers->get_post_type_label($post_type_tease)); ?></h3>

						<p><?php printf(__('The spreadsheet editor can be used to edit your WordPress %s.', 'vg_sheet_editor' ), VGSE()->helpers->get_post_type_label($post_type_tease)); ?></p>

						<?php if ($post_type_tease === 'attachment') { ?>
							<p><?php printf(__('You can edit your Media information like:', 'vg_sheet_editor' ), VGSE()->helpers->get_post_type_label($post_type_tease), VGSE()->helpers->get_post_type_label($post_type_tease)); ?></p>
							<ul class="vg-naked-list" style="margin-left: 130px;">
								<li><?php _e('Title', 'vg_sheet_editor' ); ?></li>
								<li><?php _e('Caption', 'vg_sheet_editor' ); ?></li>
								<li><?php _e('Alternative text', 'vg_sheet_editor' ); ?></li>
								<li><?php _e('Description', 'vg_sheet_editor' ); ?></li>
								<li><?php _e('Date', 'vg_sheet_editor' ); ?></li>
								<li><?php _e('Uploaded by user', 'vg_sheet_editor' ); ?></li>
								<li><?php _e('Status', 'vg_sheet_editor' ); ?></li>
								<li><?php _e('Enable comments', 'vg_sheet_editor' ); ?></li>
								<li><?php _e('And see previews while editing', 'vg_sheet_editor' ); ?></li>
							</ul>
						<?php } ?>
						<?php if ($post_type_tease === apply_filters('vg_sheet_editor/woocommerce/product_post_type_key', 'product')) { ?>

							<p><?php printf(__('You can edit your WooCommerce products information like:', 'vg_sheet_editor' ), VGSE()->helpers->get_post_type_label($post_type_tease), VGSE()->helpers->get_post_type_label($post_type_tease)); ?></p>
							<ul class="vg-naked-list" style="margin-left: 130px;">
								<li><?php _e('Title', 'vg_sheet_editor' ); ?></li>
								<li><?php _e('Short description', 'vg_sheet_editor' ); ?></li>
								<li><?php _e('Full content', 'vg_sheet_editor' ); ?></li>
								<li><?php _e('Sale price', 'vg_sheet_editor' ); ?></li>
								<li><?php _e('Regular price', 'vg_sheet_editor' ); ?></li>
								<li><?php _e('Sale price dates', 'vg_sheet_editor' ); ?></li>
								<li><?php _e('Featured image', 'vg_sheet_editor' ); ?></li>
								<li><?php _e('Gallery', 'vg_sheet_editor' ); ?></li>
								<li><?php _e('Visibility', 'vg_sheet_editor' ); ?></li>
								<li><?php _e('Is Downloadable', 'vg_sheet_editor' ); ?></li>
								<li><?php _e('Is Virtual', 'vg_sheet_editor' ); ?></li>
								<li><?php _e('Sold individually', 'vg_sheet_editor' ); ?></li>
								<li><?php _e('Purchase note', 'vg_sheet_editor' ); ?></li>		
								<li><?php _e('Enable reviews', 'vg_sheet_editor' ); ?></li>	
							</ul>
						<?php } else { ?>
							<p><?php printf(__('With our editor you will be able to edit all the information of <br/>your %s saving you a lot of time.', 'vg_sheet_editor' ), VGSE()->helpers->get_post_type_label($post_type_tease)); ?></p>

						<?php } ?>

						<p><?php _e('This feature is available as premium extension.', 'vg_sheet_editor' ); ?></p>

					</div>
					<br>
					<a href="<?php echo esc_url(VGSE()->get_buy_link('post-types-teaser', null, false, $post_type_tease)); ?>" class="remodal-confirm" target="_blank"><?php _e('Buy extension now!', 'vg_sheet_editor' ); ?></a>
					<button data-remodal-action="confirm" class="remodal-cancel"><?php _e('Close', 'vg_sheet_editor' ); ?></button>
				</div>
				<?php
			}
		}

		/**
		 * Creates or returns an instance of this class.
		 *
		 * 
		 */
		static function get_instance() {
			if (null == WP_Sheet_Editor_Post_Types_Teaser::$instance) {
				WP_Sheet_Editor_Post_Types_Teaser::$instance = new WP_Sheet_Editor_Post_Types_Teaser();
				WP_Sheet_Editor_Post_Types_Teaser::$instance->init();
			}
			return WP_Sheet_Editor_Post_Types_Teaser::$instance;
		}

		function __set($name, $value) {
			$this->$name = $value;
		}

		function __get($name) {
			return $this->$name;
		}

	}

}


add_action('vg_sheet_editor/initialized', 'vgse_init_post_types_teaser');

if (!function_exists('vgse_init_post_types_teaser')) {

	function vgse_init_post_types_teaser() {
		WP_Sheet_Editor_Post_Types_Teaser::get_instance();
	}

}