<?php
/**
 * Iubenda legal block.
 *
 * It is used to attach, delete and render legal block.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Iubenda_Legal_Block
 */
class Iubenda_Legal_Block {

	const IUB_LEGAL_BLOCK_NAME      = 'iubenda/legal-block';  // IUB legal block Name.
	const IUB_LEGAL_BLOCK_SHORTCODE = 'iub-wp-block-buttons';  // IUB legal block Shortcode.

	/**
	 * Iubenda_Legal_Block constructor.
	 */
	public function __construct() {
		add_action( 'iubenda_attach_block_in_footer', array( $this, 'attach_legal_block_into_footer' ) );

		// Change preregistered default footer content.
		add_action( 'init', array( $this, 'change_pre_registered_default_footer_content' ), 10, 0 );

		// Attach IUB legal block into WP blocks area.
		add_action( 'admin_init', array( $this, 'attach_legal_block_into_block_area' ), 10, 0 );

		// Register IUB Legal block shortcode.
		add_action( 'after_setup_theme', array( $this, 'register_shortcode' ) );
	}

	/**
	 * Register IUB Legal block shortcode function.
	 *
	 * @return void
	 */
	public function register_shortcode() {
		add_shortcode( static::IUB_LEGAL_BLOCK_SHORTCODE, array( $this, 'render_iub_legal_block' ) );
	}

	/**
	 * Attach iubenda legal block in footer.
	 */
	public function attach_legal_block_into_footer() {
		// if current theme doesn't supports blocks -> return.
		if ( ! $this->check_current_theme_supports_blocks() ) {
			return;
		}

		// Check if IUB short code exist in footer.
		if ( $this->check_iub_block_shortcode_exists_in_the_footer() ) {
			return;
		}

		$this->force_append_legal_block_in_footer();
	}

	/**
	 * Detach iubenda legal block from footer.
	 */
	public function detach_legal_block_from_footer() {
		// if current theme doesn't supports blocks -> return.
		if ( ! $this->check_current_theme_supports_blocks() ) {
			return;
		}

		// Check if IUB short code exist in footer.
		if ( $this->check_iub_block_shortcode_exists_in_the_footer() ) {
			$this->force_detach_legal_block_from_footer();

			return;
		}
	}

	/**
	 * Attach iubenda legal block in WP blocks area.
	 */
	public function attach_legal_block_into_block_area() {
		// Register IUB js block.
		wp_register_script( 'iubenda-block-editor', IUBENDA_PLUGIN_URL . '/assets/js/legal_block.js', array( 'wp-blocks', 'wp-block-editor' ), iubenda()->version, true );
		register_block_type( static::IUB_LEGAL_BLOCK_NAME, array( 'editor_script' => 'iubenda-block-editor' ) );

		// Send iub vars from backend to JS file.
		wp_localize_script(
			'iubenda-block-editor',
			'iub_block_js_vars',
			array(
				'block_name'                  => 'iubenda/legal-block',
				'iub_legal_block_shortcode'   => static::IUB_LEGAL_BLOCK_SHORTCODE,
				'iub_legal_block_short_title' => __( 'Legal', 'iubenda' ),
			)
		);
	}

	/**
	 * Render iubenda legal block and apply filters.
	 *
	 * @return mixed
	 */
	public function render_iub_legal_block() {
		$html  = '';
		$html  = apply_filters( 'before_iub_legal_block_section', $html );
		$html .= '<section>' . $this->iub_legal_block_html( $html ) . '</section>';
		$html  = apply_filters( 'after_iub_legal_block_section', $html );

		return $html;
	}

	/**
	 * Check if the current theme support WP blocks or not.
	 *
	 * @return bool
	 */
	public function check_current_theme_supports_blocks() {
		// wp_is_block_theme @since WP 5.9.0.
		if ( ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() ) || current_theme_supports( 'wp-block-styles' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Iubenda legal block html.
	 *
	 * @param string $html html-block.
	 *
	 * @return mixed|string
	 */
	public function iub_legal_block_html( $html ) {
		$quick_generator_service = new Quick_Generator_Service();

		$pp_status   = ( (string) iub_array_get( iubenda()->settings->services, 'pp.status' ) === 'true' );
		$pp_position = ( (string) iub_array_get( iubenda()->options['pp'], 'button_position' ) === 'automatic' );
		$tc_status   = ( (string) iub_array_get( iubenda()->settings->services, 'tc.status' ) === 'true' );
		$tc_position = ( (string) iub_array_get( iubenda()->options['tc'], 'button_position' ) === 'automatic' );

		if ( $pp_status && $pp_position ) {
			$html .= $quick_generator_service->pp_button();
		}

		if ( ( $pp_status && $pp_position ) && ( $tc_status && $tc_position ) ) {
			$html .= '<br>';
		}

		if ( $tc_status && $tc_position ) {
			$html .= $quick_generator_service->tc_button();
		}

		return $html;
	}

	/**
	 * Get footer post from database.
	 *
	 * @return mixed|null
	 */
	private function get_footer_from_database() {
		// Default arguments.
		$args = array(
			'post_type'      => 'wp_template_part',
			'post_status'    => 'publish',
			'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => 'wp_theme',
					'field'    => 'slug',
					'terms'    => array( get_stylesheet() ),
				),
			),
			'posts_per_page' => 1,
			'no_found_rows'  => true,
		);

		// Search for footer in database.
		$args['name'] = 'footer';

		// Run WP Query with new args.
		$footer_query = new WP_Query( $args );
		$footer       = $footer_query->have_posts() ? $footer_query->next_post() : null;

		// Footer exist in database.
		if ( $footer ) {
			return $footer;
		}

		// Search if it is inserted as a default footer in the database.
		$args['name'] = 'default-footer';

		// Run WP Query with new args.
		$footer_query = new WP_Query( $args );
		$footer       = $footer_query->have_posts() ? $footer_query->next_post() : null;

		return $footer;
	}

	/**
	 * Check if IUB short code exist in footer.
	 *
	 * @return bool
	 */
	private function check_iub_block_shortcode_exists_in_the_footer() {
		$footer = $this->get_footer_from_database();

		if ( $footer && $this->check_iub_block_shortcode_exists_in_the_footer_content( $footer->post_content ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Force append IUB legal block in footer.
	 */
	private function force_append_legal_block_in_footer() {
		$footer = $this->get_footer_from_database();

		if ( $footer ) {
			$footer->post_content = $this->insert_iub_block_shortcode_into_footer( $footer->post_content );

			$this->update_the_footer_into_database( $footer );
		}

		/**
		 * There is no footer stored in database then, Attach legal block
		 * into WP_Block_Patterns as WP default footer and insert this footer into database.
		 */
		$this->change_pre_registered_default_footer_content( true );
	}

	/**
	 * Insert Legal block into WP content.
	 *
	 * @param string $footer_content footer-content.
	 *
	 * @return false|string
	 */
	private function insert_iub_block_shortcode_into_footer( $footer_content ) {
		if ( $this->check_iub_block_shortcode_exists_in_the_footer_content( $footer_content ) ) {
			return $footer_content;
		}

		// Ensure DOMDocument class exists.
		if ( can_use_dom_document_class() ) {
			return $this->insert_iub_block_shortcode_into_footer_by_dom( $footer_content );
		}

		// Ensure Simple HTML dom exists.
		if ( ! function_exists( 'str_get_html' ) ) {
			if ( ! file_exists( IUBENDA_PLUGIN_PATH . 'iubenda-cookie-class/simple_html_dom.php' ) ) {
				return $footer_content;
			}
			require_once IUBENDA_PLUGIN_PATH . 'iubenda-cookie-class/simple_html_dom.php';
		}

		return $this->insert_iub_block_shortcode_into_footer_by_simple_html_dom( $footer_content );
	}

	/**
	 * Remove Legal block from WP content.
	 *
	 * @param string $footer_content footer-content.
	 *
	 * @return false|string
	 */
	private function remove_iub_block_shortcode_from_footer( $footer_content ) {
		$start_of_iub_legal_block = '<!-- wp:' . static::IUB_LEGAL_BLOCK_NAME . ' -->';
		$end_of_iub_legal_block   = '<!-- /wp:' . static::IUB_LEGAL_BLOCK_NAME . ' -->';

		return $this->iub_delete_in_between( $start_of_iub_legal_block, $end_of_iub_legal_block, $footer_content );
	}

	/**
	 * Update the current footer.
	 *
	 * @param WP_Post|null $footer WP_Post|null.
	 *
	 * @return mixed
	 */
	private function update_the_footer_into_database( $footer ) {
		return wp_update_post( $footer );
	}

	/**
	 * Attach legal block into WP_Block_Patterns as WP default footer
	 * if $insert_into_database is true insert default footer into database.
	 *
	 * @param   bool $insert_into_database  insert into database ?.
	 *
	 * @return void
	 */
	public function change_pre_registered_default_footer_content( $insert_into_database = false ) {
		$public_id = ( new Product_Helper() )->get_public_id_for_current_language();

		// Return false if there is no public id for current language.
		if ( ! ( $public_id ) ) {
			return;
		}

		// Check for PP & TC service status and codes.
		if ( ! ( new Product_Helper() )->check_pp_tc_status_and_position() ) {
			return;
		}

		// Check if WP_Block_Patterns_Registry is exist.
		if ( ! class_exists( 'WP_Block_Patterns_Registry', false ) ) {
			return;
		}

		$block_registry = WP_Block_Patterns_Registry::get_instance();

		foreach ( $block_registry->get_all_registered() as $block ) {
			$block_name = (string) iub_array_get( $block, 'name' );

			if ( 'twentytwentyfour/footer' === $block_name || strpos( $block_name, 'footer-default' ) !== false ) {
				// Unregister default footer.
				$block_registry->unregister( iub_array_get( $block, 'name' ) );

				// Attach Iubenda legal block in footer content.
				$block['content'] = $this->insert_iub_block_shortcode_into_footer( iub_array_get( $block, 'content' ) );

				// Register footer after attached Iubenda legal block on it.
				$block_registry->register( iub_array_get( $block, 'name' ), $block );

				if ( $insert_into_database ) {
					// Insert the footer into database.
					$this->insert_default_footer_into_database( $block );
				}
			}
		}
	}

	/**
	 * Insert default footer into database.
	 *
	 * @param   array $block  Block.
	 *
	 * @return void
	 */
	private function insert_default_footer_into_database( $block ) {
		// Current active theme slug.
		$current_active_theme_slug = get_stylesheet();

		// taxonomies.
		$taxonomies = array(
			'wp_template_part_area' => 'footer',
			'wp_theme'              => $current_active_theme_slug,
		);

		// New footer data.
		$footer = array(
			'post_title'    => 'Footer',
			'post_content'  => $block['content'],
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_type'     => 'wp_template_part',
			'post_category' => array( 'footer' ),
			'tax_input'     => $taxonomies,
		);

		// Insert the new footer into the database.
		wp_insert_post( $footer );
	}

	/**
	 * Force detach IUB legal block from footer.
	 */
	private function force_detach_legal_block_from_footer() {
		$footer = $this->get_footer_from_database();

		if ( $footer ) {
			$footer->post_content = $this->remove_iub_block_shortcode_from_footer( $footer->post_content );

			$this->update_the_footer_into_database( $footer );
		}
	}

	/**
	 * Remove specific string between $beginning and $end.
	 *
	 * @param   string $beginning      beginning.
	 * @param   string $end            end.
	 * @param   string $target_string  string.
	 *
	 * @return mixed
	 */
	private function iub_delete_in_between( $beginning, $end, $target_string ) {
		$beginning_pos = strpos( $target_string, $beginning );
		$end_pos       = strpos( $target_string, $end );
		if ( false === $beginning_pos || false === $end_pos ) {
			return $target_string;
		}

		$text_to_delete = substr( $target_string, $beginning_pos, ( $end_pos + strlen( $end ) ) - $beginning_pos );

		// recursion to ensure occurrences are removed.
		return $this->iub_delete_in_between( $beginning, $end, str_replace( $text_to_delete, '', $target_string ) );
	}

	/**
	 * Check IUB block shortcode exists in the footer content.
	 *
	 * @param string $footer_content footer-content.
	 *
	 * @return bool
	 */
	private function check_iub_block_shortcode_exists_in_the_footer_content( $footer_content ) {
		return strpos( $footer_content, '[iub-wp-block-buttons]' ) !== false;
	}

	/**
	 * Insert Legal block into WP content by php DOMDocument.
	 *
	 * @param string $footer_content footer-content.
	 *
	 * @return false|string
	 */
	private function insert_iub_block_shortcode_into_footer_by_dom( string $footer_content ) {
		$dom            = new DOMDocument();
		$previous_value = libxml_use_internal_errors( true );
		if ( function_exists( 'mb_encode_numericentity' ) ) {
			$footer_content = (string) mb_encode_numericentity( $footer_content, array( 0x80, 0x10FFFF, 0, ~0 ), 'UTF-8' );
		}

		$dom->loadHTML(
			$footer_content,
			LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
		);
		libxml_clear_errors();

		$target_div = $dom->getElementsByTagName( 'div' )->item( 1 );

		if ( ! $target_div ) {
			return $footer_content;
		}

		// insert End of Iubenda legal block before start.
		$template = $dom->createDocumentFragment();
		$template->appendXML( ' <!-- /wp:' . static::IUB_LEGAL_BLOCK_NAME . ' --> ' );

		// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		$target_div->insertBefore( $template, $target_div->firstChild );

		// Create container div with class 'wp-block-iubenda-legal-block'.
		$div = $dom->createElement( 'div' );
		$div->setAttribute( 'class', 'wp-block-iubenda-legal-block' );

		// Append the block title.
		$div->appendChild( $dom->createElement( 'p', __( 'Legal', 'iubenda' ) ) );

		// Append the block content.
		$div->appendChild( $dom->createElement( 'p', '[iub-wp-block-buttons]' ) );

		// Insert the block into the footer.
		// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		$target_div->insertBefore( $div, $target_div->firstChild );

		// Append Start of Iubenda legal block.
		$template = $dom->createDocumentFragment();
		$template->appendXML( ' <!-- wp:' . static::IUB_LEGAL_BLOCK_NAME . ' --> ' );
		// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		$target_div->insertBefore( $template, $target_div->firstChild );
		libxml_use_internal_errors( $previous_value );

		return $dom->saveHTML();
	}

	/**
	 * Insert Legal block into WP content by simple html dom.
	 *
	 * @param string $footer_content footer-content.
	 *
	 * @return false|string
	 */
	private function insert_iub_block_shortcode_into_footer_by_simple_html_dom( string $footer_content ) {
		if ( ! function_exists( 'str_get_html' ) ) {
			return false;
		}

		$html = str_get_html( $footer_content, true, true, false );

		if ( is_object( $html ) ) {
			$target_div = $html->getElementsByTagName( 'div', 1 );
			if ( ! $target_div ) {
				return $footer_content;
			}

			// Create div container with class 'wp-block-iubenda-legal-block' and add legal block shortcode into it.
			$div  = ' <!-- wp:' . static::IUB_LEGAL_BLOCK_NAME . ' --> ';
			$div .= "
				<div class='wp-block-iubenda-legal-block'>
					<p> " . esc_html__( 'Legal', 'iubenda' ) . ' </p>
					<p> [iub-wp-block-buttons] </p>
			    </div>
		    ';
			$div .= ' <!-- /wp:' . static::IUB_LEGAL_BLOCK_NAME . ' --> ';

			$target_div->innertext = $div . $target_div->innertext;

			return $html->save();
		}

		return $footer_content;
	}
}
