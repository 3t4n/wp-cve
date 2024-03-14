<?php
/**
 *  Parser that prepare data for Elementor
 *
 * @package SurferSEO
 * @link https://surferseo.com
 */

namespace SurferSEO\Surfer\Content_Parsers;

/**
 * Object that imports data from different sources into WordPress.
 */
class Parsers_Controller {

	/**
	 * Parser that prepare data for Classic Editor.
	 *
	 * @var Content_Parser
	 */
	protected $chosen_parser = null;

	public const AUTOMATIC      = '';
	public const CLASSIC_EDITOR = 'classic';
	public const GUTENBERG      = 'gutenberg';
	public const ELEMENTOR      = 'elementor';


	/**
	 * Parse content from Surfer into one of used editors.
	 *
	 * @param string $content - Content from Surfer.
	 * @return string
	 */
	public function parse_content( $content ) {

		set_time_limit( 120 );

		$this->choose_parser();
		$parsed_content = $this->chosen_parser->parse_content( $content );

		return apply_filters( 'surfer_import_content_parsing', $parsed_content );
	}

	/**
	 * Choose which parser we should use.
	 *
	 * @return void
	 */
	private function choose_parser() {

		$config_parser = Surfer()->get_surfer_settings()->get_option( 'content-importer', 'default_content_editor', self::GUTENBERG );

		if ( false === $config_parser || ! in_array( $config_parser, $this->available_parsers(), true ) ) {
			$config_parser = $this->resolve_automatic_parser_selection();
		}

		$this->load_parser( $config_parser );
	}

	/**
	 * Returns available parsers.
	 *
	 * @return array
	 */
	private function available_parsers() {
		return array(
			self::CLASSIC_EDITOR,
			self::GUTENBERG,
			self::ELEMENTOR,
		);
	}

	/**
	 * Loads chosen parser.
	 *
	 * @param string $parser - key for parser to load.
	 * @return void
	 */
	private function load_parser( $parser ) {

		if ( self::CLASSIC_EDITOR === $parser ) {
			$this->chosen_parser = new Classic_Editor_Parser();
		}

		if ( self::GUTENBERG === $parser ) {
			$this->chosen_parser = new Gutenberg_Parser();
		}

		if ( self::ELEMENTOR === $parser ) {
			$this->chosen_parser = new Elementor_Parser();
		}
	}

	/**
	 * Automatically select parser based on WP version and active plugins.
	 *
	 * @return string
	 */
	private function resolve_automatic_parser_selection() {

		if ( $this->if_user_is_using_classic_editor() ) {
			return self::CLASSIC_EDITOR;
		}

		if ( $this->if_user_is_using_elementor() ) {
			return self::ELEMENTOR;
		}

		// To Gutenberg (default).
		return self::GUTENBERG;
	}

	/**
	 * Checks if user is using Classic editor:
	 *
	 * There are two cases:
	 * - WordPress version < 5.0 (before Gutenberg), without Gutenberg plugin.
	 * - WordPress version >= 5.0 (builtin Gutenberg), with Disable Gutenberg or Classing Editor plugin.
	 *
	 * @return bool
	 */
	private function if_user_is_using_classic_editor() {

		// ( WP < 5.0 && ! Gutenberg Plugin ) || ( WP > 5.0 && Gutendber Plugin )
		$wp_version                     = get_bloginfo( 'version' );
		$if_gutenberg_is_active         = surfer_check_if_plugins_is_active( 'gutenberg/gutenberg.php' );
		$if_disable_gutenberg_is_active = surfer_check_if_plugins_is_active( 'disable-gutenberg/disable-gutenberg.php' );
		$if_classic_editor_is_active    = surfer_check_if_plugins_is_active( 'classic-editor/classic-editor.php' );

		if ( version_compare( $wp_version, '5.0', '>=' ) && ( $if_disable_gutenberg_is_active || $if_classic_editor_is_active ) ) {
			return true;
		}

		if ( version_compare( $wp_version, '5.0', '<' ) && ! $if_gutenberg_is_active ) {
			return true;
		}

		return false;
	}

	/**
	 * Checks if user is using Elementor.
	 *
	 * @return bool
	 */
	private function if_user_is_using_elementor() {

		$if_elementor_is_active = surfer_check_if_plugins_is_active( 'elementor/elementor.php' );

		if ( $if_elementor_is_active ) {
			return true;
		}

		return false;
	}

	/**
	 * Returns title after parsing.
	 *
	 * @return string
	 */
	public function return_title() {
		return $this->chosen_parser->return_title();
	}

	/**
	 * Gets title from content without parsing.
	 *
	 * @param string $content - Content from Surfer.
	 * @return string
	 */
	public function parse_only_title( $content ) {
		$this->choose_parser();
		return $this->chosen_parser->parse_title( $content );
	}

	/**
	 * Runs additional actions that parser need to do, when post is already in database.
	 *
	 * @param int $post_id - ID of the post.
	 * @return void
	 */
	public function run_after_post_insert_actions( $post_id ) {

		$this->chosen_parser->run_after_post_insert_actions( $post_id );
	}
}
