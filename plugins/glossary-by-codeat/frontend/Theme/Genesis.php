<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 3.0+
 * @link      https://codeat.co
 */

namespace Glossary\Frontend\Theme;

use Glossary\Engine;

/**
 * Support for the Genesis framework
 */
class Genesis extends Engine\Base {

	/**
	 * Instance of this Glossary\Frontend\Core\Search_Engine.
	 *
	 * @var \Glossary\Frontend\Core\Search_Engine
	 */
	private $search_engine;

	/**
	 * Initialize the class with all the hooks
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function initialize() {
		parent::initialize();

		$this->search_engine = \apply_filters( 'glossary_instance_Glossary\Frontend\Core\Search_Engine', '' );

		if ( $this->search_engine === '' ) {
			$this->search_engine = new \Glossary\Frontend\Core\Search_Engine;
		}

		\add_action( 'genesis_entry_content', array( $this, 'archive_content' ), 9 );
		\add_action( 'genesis_archive_title_descriptions', array( $this, 'print_archive_bar' ), 9, 3 );

		return true;
	}

	/**
	 * Remove the code for links support for excerpt in Genesis
	 *
	 * @param string $regex The regex that we need to fix.
	 * @return string
	 */
	public function fix_for_anchor( string $regex ) {
		return \str_replace( '<a|', '', $regex );
	}

	/**
	 * Genesis hack to add the support for the archive content page
	 * Based on genesis_do_post_content
	 *
	 * @return void
	 */
	public function archive_content() {
		// Only display excerpt if not a teaser.
		if ( \in_array( 'teaser', \get_post_class(), true ) ) {
			return;
		}

		if ( !\is_archive() ) {
			return;
		}

		global $post;
		$content  = $post->post_excerpt;
		$content .= \apply_filters( 'genesis_more_text', \genesis_a11y_more_link( \__( '[Read more...]', 'genesis' ) ) );

		if ( empty( $post->post_excerpt ) ) {
			if ( \genesis_get_option( 'content_archive_limit' ) ) {
				$content = \get_the_content_limit(
					\intval( \genesis_get_option( 'content_archive_limit' ) ),
					\apply_filters( 'genesis_more_text', \genesis_a11y_more_link( \__( '[Read more...]', 'genesis' ) ) )
				);
			}
		}

		\add_filter( 'glossary-regex', array( $this, 'fix_for_anchor' ), 9 );
		$content = \wpautop( \do_shortcode( $content ) );
		$content = $this->search_engine->check_auto_link( $content );
		\remove_filter( 'glossary-regex', array( $this, 'fix_for_anchor' ) );

		echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- It is pure HTML content.
		\remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
	}

	/**
	 * Print the archive alphabetical bar
	 *
	 * @param string $heading    The title.
	 * @param string $intro_text Genesis.
	 * @param string $context    The context.
	 * @return void
	 */
	public function print_archive_bar( string $heading = '', string $intro_text = '', string $context = '' ) { // phpcs:ignore
		if ( !isset( $this->settings[ 'archive_alphabetical_bar' ] ) ) {
			return;
		}

		\remove_action( 'genesis_archive_title_descriptions', 'genesis_do_archive_headings_headline', 10 );

		if ( $context && $heading ) {
			printf( '<h1 %s>%s</h1>', \genesis_attr( 'archive-title' ), \strip_tags( $heading ) ); // phpcs:ignore
		}

		$archive = new Archive;
		echo $archive->archive_bar( '' ); // phpcs:ignore
	}

}
