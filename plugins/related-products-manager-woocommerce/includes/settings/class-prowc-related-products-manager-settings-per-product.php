<?php
/**
 * Related Products Manager for WooCommerce - Per Product Settings
 *
 * @version 1.4.2
 * @since   1.0.0
 * @author  ProWCPlugins
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'ProWC_Related_Products_Manager_Settings_Per_Product' ) ) :

class ProWC_Related_Products_Manager_Settings_Per_Product {

	/**
	 * Constructor.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	public $id;
	public function __construct() {
		$this->id = 'per_product';
		if ( 'yes' === get_option( 'prowc_related_products_manager_enabled', 'yes' ) && 'yes' === apply_filters( 'prowc_related_products_manager', 'no', 'value_relate_per_product' ) ) {
			add_action( 'add_meta_boxes',    array( $this, 'add_meta_box' ) );
			add_action( 'save_post_product', array( $this, 'save_meta_box' ), PHP_INT_MAX, 2 );
		}
	}

	/**
	 * get_products.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function get_products( $products = array(), $post_status = 'any' ) {
		$offset     = 0;
		$block_size = 1024;
		while( true ) {
			$args = array(
				'post_type'      => 'product',
				'post_status'    => $post_status,
				'posts_per_page' => $block_size,
				'offset'         => $offset,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'fields'         => 'ids',
			);
			$loop = new WP_Query( $args );
			if ( ! $loop->have_posts() ) {
				break;
			}
			foreach ( $loop->posts as $post_id ) {
				$products[ $post_id ] = get_the_title( $post_id );
			}
			$offset += $block_size;
		}
		return $products;
	}

	/**
	 * get_meta_box_options.
	 *
	 * @version 1.4.2
	 * @since   1.0.0
	 */
	function get_meta_box_options() {
		$is_chosen_select = ( 'chosen_select' === get_option( 'prowc_related_products_manager_relate_per_product_select_type', 'chosen_select' ) );
		$product_id       = get_the_ID();
		$products         = $this->get_products( array(), 'publish' );
		unset( $products[ $product_id  ] );
		$options = array(
			array(
				'name'       => 'prowc_related_products_manager_enabled',
				'default'    => 'no',
				'type'       => 'select',
				'options'    => array(
					'no'   => __( 'No', RPMW_TEXTDOMAIN ),
					'yes'  => __( 'Yes', RPMW_TEXTDOMAIN ),
					'hide' => __( 'Hide', RPMW_TEXTDOMAIN ),
				),
				'title'      => __( 'Enable', RPMW_TEXTDOMAIN ),
			),
			array(
				'name'       => 'prowc_related_products_manager_ids',
				'default'    => '',
				'type'       => 'select',
				'options'    => $products,
				'title'      => __( 'Related products', RPMW_TEXTDOMAIN ),
				'multiple'   => true,
				'tooltip'    => ( $is_chosen_select ? ''              : __( 'Hold "Control" (Ctrl) key to select multiple values. "Control" and "A" to select all values.', RPMW_TEXTDOMAIN ) ),
				'class'      => ( $is_chosen_select ? 'chosen_select' : '' ),
				'style'      => ( $is_chosen_select ? 'width:100%;'   : '' ),
			),
			array(
				'name'       => 'prowc_related_products_manager_cat_ids',
				'default'    => '',
				'type'       => 'select',
				'options'    => prowc_related_products_manager()->settings['general']->get_terms( array( 'taxonomy' => 'product_cat', 'orderby' => 'name', 'hide_empty' => false ) ),
				'title'      => __( 'Related categories', RPMW_TEXTDOMAIN ),
				'multiple'   => true,
				'tooltip'    => ( $is_chosen_select ? ''              : __( 'Hold "Control" (Ctrl) key to select multiple values. "Control" and "A" to select all values.', RPMW_TEXTDOMAIN ) ),
				'class'      => ( $is_chosen_select ? 'chosen_select' : '' ),
				'style'      => ( $is_chosen_select ? 'width:100%;'   : '' ),
			),
			array(
				'name'       => 'prowc_related_products_manager_tag_ids',
				'default'    => '',
				'type'       => 'select',
				'options'    => prowc_related_products_manager()->settings['general']->get_terms( array( 'taxonomy' => 'product_tag', 'orderby' => 'name', 'hide_empty' => false ) ),
				'title'      => __( 'Related tags', RPMW_TEXTDOMAIN ),
				'multiple'   => true,
				'tooltip'    => ( $is_chosen_select ? ''              : __( 'Hold "Control" (Ctrl) key to select multiple values. "Control" and "A" to select all values.', RPMW_TEXTDOMAIN ) ),
				'class'      => ( $is_chosen_select ? 'chosen_select' : '' ),
				'style'      => ( $is_chosen_select ? 'width:100%;'   : '' ),
			),
		);
		return $options;
	}

	/**
	 * save_meta_box.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function save_meta_box( $post_id, $post ) {
		// Check that we are saving with current metabox displayed.
		if ( ! isset( $_POST[ 'prowc_related_products_manager_' . $this->id . '_save_post' ] ) ) {
			return;
		}
		// Save options
		foreach ( $this->get_meta_box_options() as $option ) {
			if ( 'title' === $option['type'] ) {
				continue;
			}
			$is_enabled = ( isset( $option['enabled'] ) && 'no' === $option['enabled'] ) ? false : true;
			if ( $is_enabled ) {
				$option_value  = ( isset( $_POST[ $option['name'] ] ) ) ? $_POST[ $option['name'] ] : $option['default'];
				$the_post_id   = ( isset( $option['product_id'] )     ) ? $option['product_id']     : $post_id;
				$the_meta_name = ( isset( $option['meta_name'] ) )      ? $option['meta_name']      : '_' . $option['name'];
				update_post_meta( $the_post_id, $the_meta_name, $option_value );
			}
		}
	}

	/**
	 * add_meta_box.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_meta_box() {
		$screen   = ( isset( $this->meta_box_screen ) )   ? $this->meta_box_screen   : 'product';
		$context  = ( isset( $this->meta_box_context ) )  ? $this->meta_box_context  : 'normal';
		$priority = ( isset( $this->meta_box_priority ) ) ? $this->meta_box_priority : 'high';
		add_meta_box(
			'prowc_related_products_manager_' . $this->id,
			__( 'Related Products', RPMW_TEXTDOMAIN ),
			array( $this, 'create_meta_box' ),
			$screen,
			$context,
			$priority
		);
	}

	/**
	 * create_meta_box.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function create_meta_box() {
		$current_post_id = get_the_ID();
		$html = '';
		$html .= '<table class="widefat striped">';
		foreach ( $this->get_meta_box_options() as $option ) {
			$is_enabled = ( isset( $option['enabled'] ) && 'no' === $option['enabled'] ) ? false : true;
			if ( $is_enabled ) {
				if ( 'title' === $option['type'] ) {
					$html .= '<tr>';
					$html .= '<th colspan="3" style="text-align:left;font-weight:bold;">' . $option['title'] . '</th>';
					$html .= '</tr>';
				} else {
					$custom_attributes = '';
					$the_post_id   = ( isset( $option['product_id'] ) ) ? $option['product_id'] : $current_post_id;
					$the_meta_name = ( isset( $option['meta_name'] ) )  ? $option['meta_name']  : '_' . $option['name'];
					if ( get_post_meta( $the_post_id, $the_meta_name ) ) {
						$option_value = get_post_meta( $the_post_id, $the_meta_name, true );
					} else {
						$option_value = ( isset( $option['default'] ) ) ? $option['default'] : '';
					}
					$input_ending = '';
					if ( 'select' === $option['type'] ) {
						if ( isset( $option['multiple'] ) ) {
							$custom_attributes = ' multiple';
							$option_name       = $option['name'] . '[]';
						} else {
							$option_name       = $option['name'];
						}
						if ( isset( $option['custom_attributes'] ) ) {
							$custom_attributes .= ' ' . $option['custom_attributes'];
						}
						$options = '';
						foreach ( $option['options'] as $select_option_key => $select_option_value ) {
							$selected = '';
							if ( is_array( $option_value ) ) {
								foreach ( $option_value as $single_option_value ) {
									if ( '' != ( $selected = selected( $single_option_value, $select_option_key, false ) ) ) {
										break;
									}
								}
							} else {
								$selected = selected( $option_value, $select_option_key, false );
							}
							$options .= '<option value="' . $select_option_key . '" ' . $selected . '>' . $select_option_value . '</option>';
						}
					} else {
						$input_ending = ' id="' . $option['name'] . '" name="' . $option['name'] . '" value="' . $option_value . '">';
						if ( isset( $option['custom_attributes'] ) ) {
							$input_ending = ' ' . $option['custom_attributes'] . $input_ending;
						}
					}
					$class = ( isset( $option['class'] ) ? ' class="' . $option['class'] . '"' : '' );
					$style = ( isset( $option['style'] ) ? ' style="' . $option['style'] . '"' : '' );
					switch ( $option['type'] ) {
						case 'price':
							$field_html = '<input class="short wc_input_price" type="number" step="0.0001"' . $input_ending;
							break;
						case 'date':
							$field_html = '<input class="input-text" display="date" type="text"' . $input_ending;
							break;
						case 'textarea':
							$field_html = '<textarea style="min-width:300px;"' . ' id="' . $option['name'] . '" name="' . $option['name'] . '">' . $option_value . '</textarea>';
							break;
						case 'select':
							$field_html = '<select' . $class . $style . $custom_attributes . ' id="' . $option['name'] . '" name="' . $option_name . '">' . $options . '</select>';
							break;
						default:
							$field_html = '<input class="short" type="' . $option['type'] . '"' . $input_ending;
							break;
					}
					$html .= '<tr>';
					$maybe_tooltip = ( ! empty( $option['tooltip'] ) ? '<span style="float:right;">' . wc_help_tip( $option['tooltip'], true ) . '</span>' : '' );
					$html .= '<th style="text-align:left;width:25%;">' . $option['title'] . $maybe_tooltip . '</th>';
					if ( isset( $option['desc'] ) && '' != $option['desc'] ) {
						$html .= '<td style="font-style:italic;width:25%;">' . $option['desc'] . '</td>';
					}
					$html .= '<td>' . $field_html . '</td>';
					$html .= '</tr>';
				}
			}
		}
		$html .= '</table>';
		$html .= '<input type="hidden" name="prowc_related_products_manager_' . $this->id . '_save_post" value="prowc_related_products_manager_' . $this->id . '_save_post">';
		echo $html;
	}

}

endif;

return new ProWC_Related_Products_Manager_Settings_Per_Product();
