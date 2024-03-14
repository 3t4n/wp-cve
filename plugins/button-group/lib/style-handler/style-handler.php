<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('EbStyleHandler')) {
	final class EbStyleHandler
	{
		private static $instance;

		// private $is_gutenberg_editor;

		public static function init()
		{
			if (null === self::$instance) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		public function __construct()
		{
			$this->load_style_handler_dependencies();

			add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_css']);

			add_action('save_post', [$this, 'stylehandler_get_post_content'], 10, 3);
			add_action('rest_after_save_widget', [$this, 'eb_save_widget_action'], 10, 4);
		}

		/**
		 * Save Widget CSS when Widget is saved
		 * @return void
		 * @since 3.5.3
		 */
		function eb_save_widget_action($id, $sidebar_id, $request, $creating)
		{
			$parsed_content = isset($request['instance']['raw']['content']) ? parse_blocks($request['instance']['raw']['content']) : [];
			if (is_array($parsed_content) && !empty($parsed_content)) {

				$eb_blocks = [];
				$recursive_response = EbStyleHandlerParseCss::eb_block_style_recursive($parsed_content, $eb_blocks);

				unset($recursive_response["reusableBlocks"]);

				$style = EbStyleHandlerParseCss::blocks_to_style_array($recursive_response);

				//Write CSS file for Widget
				$upload_dir = wp_upload_dir()['basedir'] . '/eb-style/';
				$this->single_file_css_generator($style, $upload_dir, 'eb-style-widget.min.css');
			}
		}

		/**
		 * Load Dependencies
		 */
		private function load_style_handler_dependencies()
		{
			require_once plugin_dir_path(__FILE__) . 'includes/class-parse-css.php';
		}

		/**
		 * Enqueue frontend css for post if have one
		 * @return void
		 * @since 1.0.2
		 */
		public function enqueue_frontend_css()
		{
			global $post;

			if (!empty($post) && !empty($post->ID)) {
				$upload_dir = wp_upload_dir();

				//Page/Post Style Enqueue
				if (file_exists($upload_dir['basedir'] . '/eb-style/eb-style-' . $post->ID . '.min.css')) {
					wp_enqueue_style('eb-block-style-' . $post->ID, $upload_dir['baseurl'] . '/eb-style/eb-style-' . $post->ID . '.min.css', [], substr(md5(microtime(true)), 0, 10));
				}

				//Widget Style Enqueue
				if (file_exists($upload_dir['basedir'] . '/eb-style/eb-style-widget.min.css')) {
					wp_enqueue_style('eb-widget-style', $upload_dir['baseurl'] . '/eb-style/eb-style-widget.min.css', [], substr(md5(microtime(true)), 0, 10));
				}

				//FSE Style Enqueue
				if (function_exists('wp_is_block_theme') && wp_is_block_theme() && file_exists($upload_dir['basedir'] . '/eb-style/eb-style-edit-site.min.css')) {
					wp_enqueue_style('eb-fullsite-style', $upload_dir['baseurl'] . '/eb-style/eb-style-edit-site.min.css', [], substr(md5(microtime(true)), 0, 10));
				}

				// Reusable block Style Enqueues
				$reusableIds = get_post_meta($post->ID, '_eb_reusable_block_ids', true);
				$reusableIds = !empty($reusableIds) ? $reusableIds : [];
				$templateReusableIds = get_option('_eb_reusable_block_ids', []);
				$reusableIds = array_unique(array_merge($reusableIds, $templateReusableIds));
				if (!empty($reusableIds) && is_array($reusableIds)) {
					foreach ($reusableIds as $reusableId) {
						if (file_exists($upload_dir['basedir'] . '/eb-style/reusable-blocks/eb-reusable-' . $reusableId . '.min.css')) {
							wp_enqueue_style('eb-reusable-block-style-' . $reusableId, $upload_dir['baseurl'] . '/eb-style/reusable-blocks/eb-reusable-' . $reusableId . '.min.css', [], substr(md5(microtime(true)), 0, 10));
						}
					}
				}
			}
		}

		/**
		 * Get post content when page is saved
		 */
		public function stylehandler_get_post_content($post_id, $post, $update)
		{
			$post_type = get_post_type($post_id);
			$allowed_post_types = array(
				'wp_block',
				'wp_template',
				'wp_template_part',
				'page',
				'post',
			);

			//If This page is draft, return
			if (isset($post->post_status) && 'auto-draft' == $post->post_status) {
				return;
			}

			// Autosave, do nothing
			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
				return;
			}

			// Return if it's a post revision
			if (false !== wp_is_post_revision($post_id)) {
				return;
			}

			if ($post_type === 'wp_template_part' || $post_type === 'wp_template') {
				$post = get_post($post_id);
				$parsed_content = parse_blocks($post->post_content);
			} else {
				$parsed_content = parse_blocks($post->post_content);
			}

			if (empty($parsed_content)) {
				delete_post_meta($post_id, '_eb_reusable_block_ids');
			}

			if (is_array($parsed_content) && !empty($parsed_content)) {

				$eb_blocks = [];
				$recursive_response = EbStyleHandlerParseCss::eb_block_style_recursive($parsed_content, $eb_blocks);
				$reusable_Blocks = !empty($recursive_response['reusableBlocks']) ? $recursive_response['reusableBlocks'] : array();
				// remove empty reusable blocks
				$reusable_Blocks = array_filter($reusable_Blocks, function ($v) {
					return !empty($v);
				});
				unset($recursive_response["reusableBlocks"]);

				$style = EbStyleHandlerParseCss::blocks_to_style_array($recursive_response);

				if ($post->post_type === 'wp_block') {
					$this->write_reusable_block_css($style, $post_id);
				} else {
					$reusableIds = $reusable_Blocks && is_array($reusable_Blocks) ? array_keys($reusable_Blocks) : [];
					update_option('_eb_reusable_block_ids', $reusableIds);
					update_post_meta($post_id, '_eb_reusable_block_ids', $reusableIds);
					$this->write_block_css($style, $post); //Write CSS file for this page
				}
			}
		}

		/**
		 * Ajax callback to write css in upload directory
		 * @retun void
		 * @since 1.0.2
		 */
		private function write_block_css($block_styles, $post)
		{
			//Write CSS for FSE
			if (isset($post->post_type) && ($post->post_type === "wp_template_part" || $post->post_type === "wp_template")) {
				$upload_dir = wp_upload_dir()['basedir'] . '/eb-style/';
				$this->single_file_css_generator($block_styles, $upload_dir, 'eb-style-edit-site.min.css');
			}
			// Write CSS for Page/Posts
			else {
				if (!empty($css = EbStyleHandlerParseCss::build_css($block_styles))) {
					$upload_dir = wp_upload_dir()['basedir'] . '/eb-style/';
					if (!file_exists($upload_dir)) {
						mkdir($upload_dir);
					}
					file_put_contents($upload_dir . 'eb-style-' . abs($post->ID) . '.min.css', $css);
				}
			}
		}

		/**
		 * Write css for Reusable block
		 * @retun void
		 * @since 3.4.0
		 */
		private function write_reusable_block_css($block_styles, $id)
		{
			if (isset($block_styles) && is_array($block_styles)) {
				if (!empty($css = EbStyleHandlerParseCss::build_css($block_styles))) {
					$upload_dir = wp_upload_dir()['basedir'] . '/eb-style/reusable-blocks/';
					if (!file_exists($upload_dir)) {
						mkdir($upload_dir);
					}
					file_put_contents($upload_dir . 'eb-reusable-' . abs($id) . '.min.css', $css);
				}
			}
		}

		/**
		 * Single file css generator
		 * @retun void
		 * @since 3.5.3
		 */
		private function single_file_css_generator($block_styles, $upload_dir, $filename)
		{
			$editSiteCssPath = $upload_dir . $filename;
			if (file_exists($editSiteCssPath)) {
				$existingCss = file_get_contents($editSiteCssPath);
				$pattern = "~\/\*(.*?)\*\/~";
				preg_match_all($pattern, $existingCss, $result, PREG_PATTERN_ORDER);
				$allComments = $result[0];
				$seperatedIds = array();
				foreach ($allComments as $comment) {
					$id = preg_replace('/[^A-Za-z0-9\-]|Ends|Starts/', '', $comment);

					if (strpos($comment, "Starts")) {
						$seperatedIds[$id]['start'] = $comment;
					} else if (strpos($comment, "Ends")) {
						$seperatedIds[$id]['end'] = $comment;
					}
				}

				$seperateStyles = array();
				foreach ($seperatedIds as $key => $ids) {
					$data = EbStyleHandlerParseCss::get_between_data($existingCss, $ids['start'], $ids['end']);
					$seperateStyles[$key] = $data;
				}

				$finalCSSArray = array_merge($seperateStyles, $block_styles);

				if (!empty($css = EbStyleHandlerParseCss::build_css($finalCSSArray))) {
					if (!file_exists($upload_dir)) {
						mkdir($upload_dir);
					}

					file_put_contents($editSiteCssPath, $css);
				}
			} else {
				if (!empty($css = EbStyleHandlerParseCss::build_css($block_styles))) {
					$upload_dir = wp_upload_dir()['basedir'] . '/eb-style/';
					if (!file_exists($upload_dir)) {
						mkdir($upload_dir);
					}

					file_put_contents($editSiteCssPath, $css);
				}
			}
		}
	}

	EbStyleHandler::init();
}
