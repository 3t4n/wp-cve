<?php
/**
 * Ingredients Block
 *
 * @since   1.2.0
 * @package WPZOOM Ingredients Block
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main WPZOOM_Ingredients_Block Class.
 */
class WPZOOM_Ingredients_Block {
	/**
	 * Class instance Helpers.
	 *
	 * @var WPZOOM_Helpers
	 * @since 1.2.0
	 */
	private static $helpers;

	/**
	 * The Constructor.
	 */
	public function __construct() {
		self::$helpers = new WPZOOM_Helpers();
	}

	/**
	 * Registers the jump-to-recipe block as a server-side rendered block.
	 *
	 * @return void
	 */
	public function register_hooks() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		if ( wpzoom_rcb_block_is_registered( 'wpzoom-recipe-card/block-ingredients' ) ) {
			return;
		}

		$attributes = array(
			'id'               => array(
				'type' => 'string',
			),
			'title'            => array(
				'type'     => 'string',
				'selector' => '.ingredients-title',
				'default'  => WPZOOM_Settings::get( 'wpzoom_rcb_settings_ingredients_title' ),
			),
			'print_visibility' => array(
				'type'    => 'string',
				'default' => 'visible',
			),
			'jsonTitle'        => array(
				'type' => 'string',
			),
			'items'            => array(
				'type'  => 'array',
				// 'default' => self::get_ingredients_default(),
				'items' => array(
					'type' => 'object',
				),
			),
		);

		// Hook server side rendering into render callback
		register_block_type(
			'wpzoom-recipe-card/block-ingredients',
			array(
				'attributes'      => $attributes,
				'render_callback' => array( $this, 'render' ),
			)
		);
	}

	/**
	 * Renders the block.
	 *
	 * @param array  $attributes The attributes of the block.
	 * @param string $content    The HTML content of the block.
	 *
	 * @return string The block preceded by its JSON-LD script.
	 */
	public function render( $attributes, $content ) {
		global $post;

		if ( ! is_array( $attributes ) ) {
			return $content;
		}

		if ( ! isset( $attributes['items'] ) ) {
			return $content;
		}

		$attributes = self::$helpers->omit( $attributes, array() );
		// Import variables into the current symbol table from an array
		extract( $attributes );

		$class = 'wp-block-wpzoom-recipe-card-block-ingredients';

		$items               = isset( $items ) ? $items : array();
		$ingredients_content = self::get_ingredients_content( $items );

		$btn_attributes = array(
			'title' => esc_html__( 'Print ingredients...', 'recipe-card-blocks-by-wpzoom' ),
		);

		if ( $post ) {
			$btn_attributes = array_merge( $btn_attributes, array( 'data-recipe-id' => $post->ID ) );
		}

		$atts = self::$helpers->render_attributes( $btn_attributes );

		$block_content = sprintf(
			'<div id="%1$s" class="%2$s">
				<div class="wpzoom-recipe-card-print-link %3$s">
					<a class="btn-print-link no-print" href="#%1$s" %4$s>
						<img class="icon-print-link" src="%5$s" alt="%6$s"/>%6$s
					</a>
				</div>
				<h3 class="ingredients-title">%7$s</h3>
				%8$s
			</div>',
			esc_attr( $id ),
			esc_attr( $class ),
			esc_attr( $print_visibility ),
			$atts,
			esc_url( WPZOOM_RCB_PLUGIN_URL . 'dist/assets/images/printer.svg' ),
			esc_html__( 'Print', 'recipe-card-blocks-by-wpzoom' ),
			esc_html( $title ),
			$ingredients_content
		);

		return $block_content;
	}

	public static function get_ingredients_default() {
		return array(
			array(
				'id'   => self::$helpers->generateId( 'ingredient-item' ),
				'name' => array(),
			),
			array(
				'id'   => self::$helpers->generateId( 'ingredient-item' ),
				'name' => array(),
			),
			array(
				'id'   => self::$helpers->generateId( 'ingredient-item' ),
				'name' => array(),
			),
			array(
				'id'   => self::$helpers->generateId( 'ingredient-item' ),
				'name' => array(),
			),
		);
	}

	public static function get_ingredients_content( array $ingredients ) {
		$ingredient_items = self::get_ingredient_items( $ingredients );

		$listClassNames = implode( ' ', array( 'ingredients-list' ) );

		return sprintf(
			'<ul class="%s">%s</ul>',
			esc_attr( $listClassNames ),
			$ingredient_items
		);
	}

	public static function get_ingredient_items( array $ingredients ) {
		$output        = '';
		$strikethrough = WPZOOM_Settings::get( 'wpzoom_rcb_settings_ingredients_strikethrough' ) === '1' ? ' is-strikethrough-active' : '';

		foreach ( $ingredients as $index => $ingredient ) {
			$name    = '';
			$isGroup = isset( $ingredient['isGroup'] ) ? $ingredient['isGroup'] : false;

			if ( ! $isGroup ) {
				if ( ! empty( $ingredient['name'] ) ) {
					$name    = sprintf(
						'<p class="ingredient-item-name%s">%s</p>',
						$strikethrough,
						WPZOOM_Recipe_Card_Block::wrap_ingredient_name( $ingredient['name'] )
					);
					$output .= sprintf(
						'<li id="%s" class="ingredient-item">%s</li>',
						esc_attr( $ingredient['id'] ),
						$name
					);
				}
			} else {
				if ( ! empty( $ingredient['name'] ) ) {
					$name    = sprintf(
						'<strong class="ingredient-item-group-title">%s</strong>',
						WPZOOM_Recipe_Card_Block::wrap_ingredient_name( $ingredient['name'] )
					);
					$output .= sprintf(
						'<li class="ingredient-item ingredient-item-group">%s</li>',
						$name
					);
				}
			}
		}

		return force_balance_tags( $output );
	}
}
