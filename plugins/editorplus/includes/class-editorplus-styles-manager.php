<?php

/**
 * Main file for styles manager
 *
 * @package EditorPlus
 */

if (!defined('ABSPATH')) {
	exit;
}

require_once EDPL_EDITORPLUS_PLUGIN_DIR . 'includes/class-editorplus-styles-generator.php';

/**
 * Main class for handling editorplus stylings
 */
class EditorPlus_Styles_Manager {

	/**
	 * Editorplus styles generator.
	 *
	 * @var EditorPlus_Styles_Generator
	 */
	private $generator;

	/**
	 * Contructor.
	 *
	 * @return void
	 */
	public function __construct() {

		$this->generator = new EditorPlus_Styles_Generator();

		// Rendering styles for header.
		add_action(
			'wp_head',
			function () {
				return $this->render_styles();
			}
		);

		// Rendering styles for footer.
		add_action(
			'wp_footer',
			function () {
				return $this->render_styles(true);
			}
		);

		add_action('body_class', array($this, 'insert_editorplus_body_class'));
		add_filter('editor_plus_css_code', array($this, 'merge_global_styles'), 10, 2);
		add_action('init', array($this, 'register'));
		add_filter('render_block', array($this, 'prepare_blocks_for_styles'), 10, 2);
	}

	/**
	 * Will prepare each block for styling by assigning them unique classes.
	 *
	 * @param string $block_content - Block inner content.
	 * @param array  $block - Parsed block.
	 *
	 * @return string - Block content with unique class assigned.
	 */
	public function prepare_blocks_for_styles($block_content, $block) {

		$slug = $block['blockName'];

		// Excluding unsupported blocks.
		if (!editorplus_is_supported_block($slug) || !editorplus_has_block_styles($block)) {
			return $block_content;
		}

		libxml_use_internal_errors(true); // For surpressing any document error.

		$document = new DOMDocument();

		if ('' === $block_content) {
			return $block_content;
		}

		$document->loadHTML('<?xml encoding="utf-8" ?>' . $block_content);

		$finder = new DOMXPath($document);

		$selector = $block['attrs']['epGeneratedClass'] ?? '';

		if ('' === $selector) {
			return $block_content;
		}

		$block_element = $finder->query("//*[contains(@class, '$selector')]")->item(0);

		if (is_null($block_element)) {
			// Using the top wrapper element as an alternative, if editorplus wrapper class not found.
			$first_element = $document->getElementsByTagName('body')->item(0)->firstChild;

			if (!is_null($first_element)) {
				$block_element = $first_element;
			} else {
				return $block_content;
			}
		}

		$classlist = explode(' ', $block_element->getAttribute('class') ?? '');

		$classlist[] = 'eplus-styles-uid-' . editorplus_generate_unique_uid_for_block($block);

		if (!is_null($block_element)) {
			$block_element->setAttribute(
				'class',
				join(' ', $classlist)
			);
		}

		libxml_clear_errors();

		return $document->saveHTML($document->getElementsByTagName('body')->item(0)->firstChild);
	}

	/**
	 * All registerations should be done here.
	 *
	 * @return void
	 */
	public function register() {

		$post_types = get_post_types(
			array(
				'_builtin' => false,
			),
			'names',
			'and'
		);

		$post_types['post'] = 'post';

		foreach ($post_types as $post_type) {

			// TODO: "copy styling feature" is still using metadata, refactor the client script to utilize state for this.
			register_meta(
				$post_type,
				'editor_plus_copied_stylings',
				array(
					'show_in_rest' => true,
					'single'       => true,
					'type'         => 'string',
					'default'      => '{}',
				)
			);
		}
	}

	/**
	 * Will insert editorplus body class.
	 *
	 * @param array $classes - Body classes.
	 * @return array - body classes merged with editorplus class.
	 */
	public function insert_editorplus_body_class($classes) {
		$classes['editorplus_styles'] = 'eplus_styles';
		return $classes;
	}

	/**
	 * Will merge global styles to the given css code.
	 *
	 * @param string $css_code - CSS Code.
	 * @param bool   $in_footer - True if the css code is being processed for the footer, otherwise false.
	 *
	 * @return string - CSS Code with global styles merged.
	 */
	public function merge_global_styles($css_code, $in_footer) {

		// It should be enqueued in footer, if this option is set to 'true'.
		$required_global_position = get_option('ep_custom_global_styles_position', 'false');

		if ('true' === $required_global_position && $in_footer) {
			$css_code .= get_option('ep_custom_global_css', '');
			return $css_code;
		}

		if ('true' !== $required_global_position && !$in_footer) {
			$css_code .= get_option('ep_custom_global_css', '');
			return $css_code;
		}

		return $css_code;
	}

	/**
	 * This method is responsible to output editorplus styles, scoped to a post.
	 *
	 * @param bool $in_footer - True if enqueueing in the footer, otherwise false.
	 *
	 * @return void
	 */
	public function render_styles($in_footer = false) {

		$current_post_id = get_the_ID();
		$current_post    = get_post($current_post_id);

		if (is_null($current_post)) {
			return;
		}

		$generated_editorplus_styles = '';

		// Generating styles specifically for post content.
		$current_post_content = $current_post->post_content;

		// Skipping blocks styles for footer scope.
		if (false === $in_footer) {
			$parsed_blocks                = parse_blocks($current_post_content);
			$generated_editorplus_styles .= $this->generator->generate_styles_from_blocks($parsed_blocks);
		}

		$generated_editorplus_styles = apply_filters('editor_plus_css_code', $generated_editorplus_styles, $in_footer);

		/**
		 * The generated CSS should not contain any html markup.
		 * Using regex to match and strip these html tags.
		 *
		 * WordPress is also using some regex to validate the CSS output.
		 *
		 * @see https://github.com/WordPress/WordPress/blob/56c162fbc9867f923862f64f1b4570d885f1ff03/wp-includes/customize/class-wp-customize-custom-css-setting.php#L157-L168
		 */
		if (preg_match('#</?\w+#', $generated_editorplus_styles)) {

			// Replacing all the matched tags.
			$generated_editorplus_styles = preg_replace('#</?\w+#', '', $generated_editorplus_styles);
		}

		if ('' !== $generated_editorplus_styles) {

			// Creating an style id based on the position.
			$styles_id_suffix = $in_footer ? '-footer' : '-header';

?>
			<style id="editorplus-generated-styles<?php echo $styles_id_suffix; ?>">
				<?php echo $generated_editorplus_styles; ?>
			</style>
<?php
		}
	}
}
