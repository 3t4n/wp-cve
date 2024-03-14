<?php
/**
 * CPT Admin Columns Class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Controllers\Admin;

use RT\FoodMenu\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Admin Columns Class.
 */
class AdminColumns {
	use \RT\FoodMenu\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		add_filter( 'manage_edit-fmsc_columns', [ $this, 'custom_sc_columns' ] );
		add_action( 'manage_fmsc_posts_custom_column', [ $this, 'manage_sc_columns' ], 10, 2 );

		if ( TLPFoodMenu()->has_pro() ) {
			return;
		}

		add_action( 'quick_edit_custom_box', [ $this, 'bulk_quick_edit_custom_box' ], 10, 2 );
		add_action( 'save_post', [ $this, 'quick_edit_save' ] );
		add_action( 'admin_print_scripts-edit.php', [ $this, 'enqueue_edit_scripts' ] );
		add_filter( 'manage_edit-food-menu_columns', [ $this, 'custom_columns' ] );
		add_action( 'manage_food-menu_posts_custom_column', [ $this, 'manage_columns' ], 10, 2 );
		add_action( 'restrict_manage_posts', [ $this, 'add_taxonomy_filters' ] );
	}

	/**
	 * Bulk Quick Edit.
	 *
	 * @param string $column_name Column name.
	 * @param string $post_type Post Type.
	 * @return void
	 */
	public function bulk_quick_edit_custom_box( $column_name, $post_type ) {
		switch ( $post_type ) {
			case TLPFoodMenu()->post_type:
				switch ( $column_name ) {
					case 'price':
						global $post;
						$price = get_post_meta( $post->ID, '_regular_price', true );
						?>
						<fieldset class="inline-edit-col-right">
							<div class="inline-edit-group">
								<label>
									<span class="title">Price</span>
									<span class="input-text-wrap">
											<input type="text" name="_regular_price" class="inline-edit-menu-order-input" value="<?php echo esc_attr( $price ); ?>"/>
										</span>
								</label>
							</div>
						</fieldset>
						<?php
						break;
				}

				break;
		}
	}

	/**
	 * Save Quick Edit.
	 *
	 * @param int $post_id Post ID.
	 * @return mixed
	 */
	public function quick_edit_save( $post_id ) {
		$post = get_post( $post_id );

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || TLPFoodMenu()->post_type != $post->post_type ) {
			return $post_id;
		}

		if ( $post->post_type != 'revision' ) {
			$price = ( isset( $_POST['_regular_price'] ) ? sprintf(
				'%.2f',
				floatval( sanitize_text_field( wp_unslash( $_POST['_regular_price'] ) ) )
			) : null );

			update_post_meta( $post_id, '_regular_price', $price );
		}
	}

	/**
	 * Save Quick Edit.
	 *
	 * @return void
	 */
	public function enqueue_edit_scripts() {
		wp_enqueue_script(
			'food-menu-admin-edit',
			TLPFoodMenu()->assets_url() . 'js/quick_edit.js',
			[
				'jquery',
				'inline-edit-post',
			],
			'',
			true
		);
	}

	/**
	 * Custom Columns
	 *
	 * @param array $columns Columns.
	 * @return array
	 */
	public function custom_columns( $columns ) {
		$column_thumbnail = [ 'thumbnail' => esc_html__( 'Image', 'tlp-food-menu' ) ];
		$column_price     = [ 'price' => esc_html__( 'Price', 'tlp-food-menu' ) ];
		return array_slice( $columns, 0, 2, true ) + $column_thumbnail + $column_price + array_slice( $columns, 1, null, true );
	}

	/**
	 * Manage Columns
	 *
	 * @param array $column Column name.
	 * @return void
	 */
	public function manage_columns( $column ) {
		switch ( $column ) {
			case 'thumbnail':
				echo get_the_post_thumbnail( get_the_ID(), [ 100, 100 ] );
				break;
			case 'price':
				echo sprintf( '%.2f', esc_html( get_post_meta( get_the_ID(), '_regular_price', true ) ) );
				break;
		}
	}

	/**
	 * Adds a tax filter.
	 *
	 * @return void
	 */
	public function add_taxonomy_filters() {
		global $typenow;
		// Must set this to the post type you want the filter(s) displayed on.
		if ( TLPFoodMenu()->post_type !== $typenow ) {
			return;
		}
		foreach ( TLPFoodMenu()->taxonomies as $tax_slug ) {
			Fns::print_html( $this->build_taxonomy_filter( $tax_slug ), true );
		}
	}

	/**
	 * Custom SC Columns
	 *
	 * @param array $columns Columns.
	 * @return array
	 */
	public function custom_sc_columns( $columns ) {
		$shortcode = [ 'fmp_short_code' => esc_html__( 'Shortcode', 'tlp-food-menu' ) ];

		return array_slice( $columns, 0, 2, true ) + $shortcode + array_slice( $columns, 1, null, true );
	}

	/**
	 * Manage SC Columns
	 *
	 * @param array $column Column name.
	 * @return void
	 */
	public function manage_sc_columns( $column ) {
		switch ( $column ) {
			case 'fmp_short_code':
				echo '<input type="text" onfocus="this.select();" readonly="readonly" value="[foodmenu id=&quot;' . get_the_ID() . '&quot; title=&quot;' . esc_html( get_the_title() ) . '&quot;]" class="large-text code rt-code-sc">';
				break;
			default:
				break;
		}
	}

	/**
	 * Build an individual dropdown filter.
	 *
	 * @param  string $tax_slug Taxonomy slug to build filter for.
	 * @return string Markup, or empty string if taxonomy has no terms.
	 */
	protected function build_taxonomy_filter( $tax_slug ) {
		$terms = get_terms( $tax_slug );

		if ( 0 == count( $terms ) ) {
			return '';
		}

		$tax_name         = $this->get_taxonomy_name_from_slug( $tax_slug );
		$current_tax_slug = isset( $_GET[ $tax_slug ] ) ? sanitize_title_with_dashes( wp_unslash( $_GET[ $tax_slug ] ) ) : false;
		$filter           = '<select name="' . esc_attr( $tax_slug ) . '" id="' . esc_attr( $tax_slug ) . '" class="postform">';
		$filter          .= '<option value="0">' . esc_html( $tax_name ) . '</option>';
		$filter          .= $this->build_term_options( $terms, $current_tax_slug );
		$filter          .= '</select>';

		return $filter;
	}

	/**
	 * Get the friendly taxonomy name, if given a taxonomy slug.
	 *
	 * @param  string $tax_slug Taxonomy slug.
	 * @return string Friendly name of taxonomy, or empty string if not a valid taxonomy.
	 */
	protected function get_taxonomy_name_from_slug( $tax_slug ) {
		$tax_obj = get_taxonomy( $tax_slug );

		if ( ! $tax_obj ) {
			return '';
		}

		return $tax_obj->labels->name;
	}

	/**
	 * Build a series of option elements from an array.
	 *
	 * Also checks to see if one of the options is selected.
	 *
	 * @param  array  $terms            Array of term objects.
	 * @param  string $current_tax_slug Slug of currently selected term.
	 * @return string Markup.
	 */
	protected function build_term_options( $terms, $current_tax_slug ) {
		$options = '';

		foreach ( $terms as $term ) {
			$options .= sprintf(
				"<option value='%s' %s />%s</option>",
				esc_attr( $term->slug ),
				esc_attr( selected( $current_tax_slug, $term->slug, false ) ),
				esc_html( $term->name . '(' . $term->count . ')' )
			);
		}

		return $options;
	}
}
