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

namespace Glossary\Integrations\Widgets;

/**
 * Last glossary terms widget
 */
class Last_Glossaries extends \WPH_Widget {

	/**
	 * Initialize the widget
	 *
	 * @return void
	 */
	// phpcs:disable
	public function __construct() {
		$args = array(
			'label'       => \__( 'Glossary Latest Terms', GT_TEXTDOMAIN ),
			'description' => \__( 'List of latest Glossary Terms', GT_TEXTDOMAIN ),
			'slug'        => 'glossary-latest-terms',
		);

		$args[ 'fields' ] = array(
			array(
				'name'     => \__( 'Title', GT_TEXTDOMAIN ),
				'desc'     => \__( 'Enter the widget title.', GT_TEXTDOMAIN ),
				'id'       => 'title',
				'type'     => 'text',
				'class'    => 'widefat',
				'std'      => \__( 'Latest Glossary Terms', GT_TEXTDOMAIN ),
				'validate' => 'alpha_dash',
				'filter'   => 'strip_tags|esc_attr',
			),
			array(
				'name'     => \__( 'Number', GT_TEXTDOMAIN ),
				'desc'     => \__( 'The number of terms to be shown.', GT_TEXTDOMAIN ),
				'id'       => 'number',
				'type'     => 'text',
				'validate' => 'numeric',
				'std'      => 5,
				'filter'   => 'strip_tags|esc_attr',
			),
			array(
				'name'     => \__( 'Category', GT_TEXTDOMAIN ),
				'desc'     => \__( 'Filter from Glossary category.', GT_TEXTDOMAIN ),
				'id'       => 'tax',
				'type'     => 'taxonomyterm',
				'taxonomy' => 'glossary-cat',
			),
			array(
				'name'   => \__( 'Choose theme', GT_TEXTDOMAIN ),
				'id'     => 'theme',
				'type'   => 'select',
				'fields' => array(
					array(
						'name'  => \__( 'Hyphen', GT_TEXTDOMAIN ),
						'value' => 'hyphen',
					),
					array(
						'name'  => \__( 'Arrow', GT_TEXTDOMAIN ),
						'value' => 'arrow',
					),
					array(
						'name'  => \__( 'Dot', GT_TEXTDOMAIN ),
						'value' => 'dot',
					),
					array(
						'name'  => \__( 'Tilde', GT_TEXTDOMAIN ),
						'value' => 'tilde',
					),
				),
			),
		);

		$this->create_widget( $args );
	}
	// phpcs:enable

	/**
	 * Print the widget
	 *
	 * @param array $args     Parameters.
	 * @param array $instance Values.
	 * @return void
	 */
	public function widget( $args, $instance ) { //phpcs:ignore
		$out = $args[ 'before_widget' ];

		if ( !isset( $instance[ 'tax' ] ) ) {
			$instance[ 'tax' ] = array();
		}

		if ( !isset( $instance[ 'number' ] ) ) {
			$instance[ 'number' ] = 5;
		}

		$theme = '';

		if ( isset( $instance[ 'theme' ] ) ) {
			$theme = ' theme-' . $instance[ 'theme' ];
		}

		if ( isset( $instance[ 'title' ] ) ) {
			$out .= $args[ 'before_title' ];
			$out .= $instance[ 'title' ];
			$out .= $args[ 'after_title' ];
		}

		$out .= '<div class="widget-glossary-terms-list' . $theme . '">';

		if ( \is_array( $instance[ 'tax' ] ) && empty( $instance[ 'tax' ] ) ) {
			$instance[ 'tax' ] = '';
		}

		$out .= \get_glossary_terms_list( 'last', $instance[ 'number' ], $instance[ 'tax' ] );
		$out .= '</div>';
		$out .= $args[ 'after_widget' ];
		echo $out; // phpcs:ignore
	}

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		\add_action(
		'widgets_init',
		static function () {
			\register_widget( 'Glossary\Integrations\Widgets\Last_Glossaries' );
		}
		);
	}

}
