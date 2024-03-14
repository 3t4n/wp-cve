<?php
/**
 * Admin Post Metabox Class.
 *
 * @package RT_FoodMenu
 */

namespace RT\FoodMenu\Controllers\Admin\Metabox;

use RT\FoodMenu\Helpers\Fns;

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

/**
 * Admin Post Metabox Class.
 */
class PostMeta {
	use \RT\FoodMenu\Traits\SingletonTrait;

	/**
	 * Class Init.
	 *
	 * @return void
	 */
	protected function init() {
		if ( TLPFoodMenu()->has_pro() ) {
			return;
		}

		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxs' ] );
		add_action( 'save_post', [ $this, 'save_meta_boxes' ], 10, 3 );
		add_action( 'edit_form_after_title', [ $this, 'after_title_text' ] );
	}

	/**
	 * Admin Enqueue Scripts.
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		global $pagenow, $typenow;

		// validate page.
		if ( ! in_array( $pagenow, [ 'post.php', 'post-new.php', 'edit.php' ] ) ) {
			return;
		}

		if ( $typenow != TLPFoodMenu()->post_type ) {
			return;
		}

		wp_enqueue_style( [ 'wp-color-picker', 'fm-select2', 'fm-admin' ] );
		wp_enqueue_script( [ 'wp-color-picker', 'fm-select2', 'fm-admin' ] );

		$nonce = wp_create_nonce( Fns::nonceText() );

		wp_localize_script(
			'fm-admin',
			'fmp_var',
			[
				'nonceID' => esc_attr( Fns::nonceId() ),
				'nonce'   => esc_attr( $nonce ),
				'ajaxurl' => esc_url( admin_url( 'admin-ajax.php' ) ),
			]
		);
	}

	/**
	 * Add Meta Box.
	 *
	 * @return void
	 */
	public function add_meta_boxs() {
		add_meta_box(
			'tlp_food_menu_meta_details',
			esc_html__( 'Food Details', 'tlp-food-menu' ),
			[ $this, 'meta_box_callback' ],
			TLPFoodMenu()->post_type,
			'normal',
			'high'
		);
	}

	/**
	 * Save Meta Box.
	 *
	 * @param int    $post_id Post ID.
	 * @param object $post Post object.
	 * @param mixed  $update Update.
	 * @return void
	 */
	public function save_meta_boxes( $post_id, $post, $update ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! Fns::verifyNonce() ) {
			return;
		}

		// Check permissions.
		if ( TLPFoodMenu()->post_type != $post->post_type ) {
			return;
		}

		$meta['_regular_price'] = ( isset( $_POST['_regular_price'] ) ? sprintf(
			'%.2f',
			floatval( sanitize_text_field( wp_unslash( $_POST['_regular_price'] ) ) )
		) : null );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Meta Box Callback.
	 *
	 * @param object $post Post Object.
	 * @return void
	 */
	public function meta_box_callback( $post ) {
		wp_nonce_field( Fns::nonceText(), Fns::nonceId() );

		$meta  = get_post_meta( $post->ID );
		$price = ! isset( $meta['_regular_price'][0] ) ? '' : $meta['_regular_price'][0];

		?>
		<table class="form-table">

			<tr>
				<td class="team_meta_box_td" colspan="2">
					<label for="price"><?php esc_html_e( 'Price', 'tlp-food-menu' ); ?></label>
				</td>
				<td colspan="4">
					<input min="0" step="0.01" type="number" name="_regular_price" id="price" class="tlpfield" value="<?php echo sprintf( '%.2f', esc_html( $price ) ); ?>">
					<p class="description">
						<?php
						esc_html_e( 'Insert the price, leave blank if it is free', 'tlp-food-menu' );
						?>
					</p>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Text after title.
	 *
	 * @param object $post Post Object.
	 * @return string
	 */
	public function after_title_text( $post ) {
		if ( TLPFoodMenu()->post_type !== $post->post_type ) {
			return;
		}

		$html    = null;
		$proLink = 'https://www.radiustheme.com/downloads/food-menu-pro-wordpress/';

		$html .= '<div class="rt-document-box rt-update-pro-btn-wrap" style="background: none;box-shadow:none;">
					<a href="' . esc_url( $proLink ) . '" target="_blank" class="rt-update-pro-btn" style="width: 100%;margin-top: 20px;">Please check the PRO features</a>
				</div>';

		Fns::print_html( $html );
	}
}
