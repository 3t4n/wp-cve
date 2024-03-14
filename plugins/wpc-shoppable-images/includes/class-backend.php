<?php

use Automattic\WooCommerce\Utilities\FeaturesUtil;

defined( 'ABSPATH' ) || exit;

class WPCleverWpcsi_Backend {
	protected static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		add_action( 'init', [ $this, 'init' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		add_action( 'add_meta_boxes', [ $this, 'add_meta_box' ] );
		add_action( 'save_post_wpc_shoppable_image', [ $this, 'save_post' ] );

		add_filter( 'manage_edit-wpc_shoppable_image_columns', [ $this, 'custom_column' ] );
		add_action( 'manage_wpc_shoppable_image_posts_custom_column', [ $this, 'custom_column_value' ], 10, 2 );

		// HPOS compatibility
		add_action( 'before_woocommerce_init', function () {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				FeaturesUtil::declare_compatibility( 'custom_order_tables', WPCSI_FILE );
			}
		} );
	}

	public function custom_column( $columns ) {
		return [
			'cb'          => $columns['cb'],
			'title'       => esc_html__( 'Title', 'wpc-shoppable-images' ),
			'image'       => esc_html__( 'Image', 'wpc-shoppable-images' ),
			'description' => esc_html__( 'Description', 'wpc-shoppable-images' ),
			'shortcode'   => esc_html__( 'Shortcode', 'wpc-shoppable-images' ),
			'date'        => esc_html__( 'Date', 'wpc-shoppable-images' )
		];
	}

	public function custom_column_value( $column, $post_id ) {
		if ( $column == 'image' ) {
			if ( has_post_thumbnail() ) {
				the_post_thumbnail( 'thumbnail', [ 'style' => 'width: 50px;height: 50px' ] );
			}
		}

		if ( $column == 'description' ) {
			echo get_the_excerpt( $post_id );
		}

		if ( $column == 'shortcode' ) {
			?>
            <div class="wpcsi-shortcode-col-wrap">
                <input type="text" onfocus="this.select();" readonly="readonly" value="[wpcsi_shoppable_image id='<?php echo esc_attr( $post_id ); ?>']" class="code"/>
            </div>
			<?php
		}
	}

	public function add_meta_box() {
		add_meta_box( 'wpcsi_configuration', esc_html__( 'Configuration', 'wpc-shoppable-images' ), [
			$this,
			'configuration_meta'
		], 'wpc_shoppable_image', 'advanced', 'low' );

		add_meta_box( 'wpcsi_shortcode', esc_html__( 'Shortcode', 'wpc-shoppable-images' ), [
			$this,
			'configuration_shortcode'
		], 'wpc_shoppable_image', 'side', 'default' );
	}


	public function save_post( $post_id ) {
		if ( isset( $_POST['wpcsi-items'] ) ) {
			update_post_meta( $post_id, 'wpcsi-items', sanitize_text_field( $_POST['wpcsi-items'] ) );
		}
	}

	public function configuration_shortcode() {
		?>
        <div class="wpcsi-shortcode-col-wrap">
            <input type="text" onfocus="this.select();" readonly="readonly" value="[wpcsi_shoppable_image id='<?php echo esc_attr( get_the_ID() ); ?>']" class="code"/>
        </div>
		<?php
	}

	public function configuration_meta() {
		$items = get_post_meta( get_the_ID(), 'wpcsi-items', true );

		if ( ! $items ) {
			$items = '[]';
		}

		$tags = json_decode( $items, true );
		?>
        <div id="wpcsi-preview">
            <div class="wpcsi-ruler">
                <div class="wpcsi-ruler-item wpcsi-ruler-320" data-width="320">320px</div>
                <div class="wpcsi-ruler-item wpcsi-ruler-375" data-width="375"></div>
                <div class="wpcsi-ruler-item wpcsi-ruler-425" data-width="425"></div>
                <div class="wpcsi-ruler-item wpcsi-ruler-768" data-width="768">768px</div>
                <div class="wpcsi-ruler-item wpcsi-ruler-1024" data-width="1024">1024px</div>
                <div class="wpcsi-ruler-item wpcsi-ruler-1440" data-width="1440">1440px</div>
            </div>
            <div id="wpcsi-image-wrapper">
                <div id="wpcsi-image" class="<?php echo has_post_thumbnail() ? 'has-image' : ''; ?>" data-count="<?php echo esc_attr( count( $tags ) ); ?>">
                    <img src="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>"/>
					<?php foreach ( $tags as $key => $tag ) { ?>
                        <span style="top: <?php echo esc_attr( $tag['position']['top'] ); ?>%; left: <?php echo esc_attr( $tag['position']['left'] ); ?>%" data-item="<?php echo esc_attr( json_encode( $tag ) ); ?>" class="wpcsi-tag" data-key="<?php echo esc_attr( $key ); ?>"><span class="hint--top" aria-label="top: <?php echo esc_attr( $tag['position']['top'] ); ?>%; left: <?php echo esc_attr( $tag['position']['left'] ); ?>%"><?php echo esc_html( $key + 1 ); ?></span></span>
					<?php } ?>
                </div>
            </div>
            <div class="wpcsi-image-add">
                <a id="wpcsi-image-add-btn" href="#">
					<?php echo has_post_thumbnail() ? esc_html__( 'Change image', 'wpc-shoppable-images' ) : esc_html__( 'Add image', 'wpc-shoppable-images' ); ?>
                </a>
            </div>
        </div>
        <div id="wpcsi-item-meta">
			<?php
			foreach ( $tags as $key => $tag ) {
				self::meta_item( $key, $tag );
			}
			?>
        </div><input id="wpcsi-items" type="hidden" name="wpcsi-items" value="<?php echo esc_attr( $items ); ?>"/>
        <script type="text/html" id="tmpl-wpcsi-meta-item">
			<?php self::meta_item(); ?>
        </script>
		<?php
	}

	public function meta_item( $key = null, $tag = null ) {
		$label    = ( isset( $tag['settings'] ) && isset( $tag['settings']['label'] ) ) ? $tag['settings']['label'] : '';
		$lpos     = ( isset( $tag['settings'] ) && isset( $tag['settings']['lpos'] ) ) ? $tag['settings']['lpos'] : 'top-center';
		$content  = ( isset( $tag['settings'] ) && isset( $tag['settings']['content'] ) ) ? $tag['settings']['content'] : 'products';
		$position = ( isset( $tag['settings'] ) && isset( $tag['settings']['position'] ) ) ? $tag['settings']['position'] : 'top-center';
		$cart     = ( isset( $tag['settings'] ) && isset( $tag['settings']['cart'] ) ) ? $tag['settings']['cart'] : 'no';
		$price    = ( isset( $tag['settings'] ) && isset( $tag['settings']['price'] ) ) ? $tag['settings']['price'] : 'yes';
		$carousel = ( isset( $tag['settings'] ) && isset( $tag['settings']['carousel'] ) ) ? $tag['settings']['carousel'] : 'no';
		$image    = ( isset( $tag['settings'] ) && isset( $tag['settings']['image'] ) ) ? $tag['settings']['image'] : 'left';
		$link     = ( isset( $tag['settings'] ) && isset( $tag['settings']['link'] ) ) ? $tag['settings']['link'] : 'same';
		$trigger  = ( isset( $tag['settings'] ) && isset( $tag['settings']['trigger'] ) ) ? $tag['settings']['trigger'] : 'click';
		$products = ( isset( $tag['settings'] ) && isset( $tag['settings']['products'] ) ) ? $tag['settings']['products'] : [];
		$text     = ( isset( $tag['settings'] ) && isset( $tag['settings']['text'] ) ) ? $tag['settings']['text'] : '';
		?>
        <div class="item-meta <?php echo esc_attr( isset( $key ) ? '' : 'active' ); ?>" id="wpcsi-meta-item-key-<?php echo esc_attr( isset( $key ) ? $key : '{{(data.key - 1)}}' ); ?>" data-key="<?php echo esc_attr( isset( $key ) ? $key : '{{(data.key - 1)}}' ); ?>">
            <form>
                <div class="header">
                    <span>#<?php echo esc_html( isset( $key ) ? $key + 1 : '{{data.key}}' ); ?></span>
                    <span class="item-remove deletion hint--left" aria-label="<?php esc_attr_e( 'Remove', 'wpc-shoppable-images' ); ?>"><i class="dashicons dashicons-trash"></i></span>
                </div>
                <div class="content">
                    <table class="wpcsi_configuration_table">
                        <tr class="wpcsi_configuration_tr">
                            <td class="wpcsi_configuration_th">
								<?php esc_html_e( 'Label', 'wpc-shoppable-images' ); ?>
                            </td>
                            <td class="wpcsi_configuration_td">
                                <input type="text" name="label" value="<?php echo esc_attr( $label ); ?>"/>
                                <select name="lpos">
                                    <option value="top-left" <?php selected( $lpos, 'top-left' ); ?>><?php esc_html_e( 'top-left', 'wpc-shoppable-images' ); ?></option>
                                    <option value="top-center" <?php selected( $lpos, 'top-center' ); ?>><?php esc_html_e( 'top-center', 'wpc-shoppable-images' ); ?></option>
                                    <option value="top-right" <?php selected( $lpos, 'top-right' ); ?>><?php esc_html_e( 'top-right', 'wpc-shoppable-images' ); ?></option>
                                    <option value="middle-left" <?php selected( $lpos, 'middle-left' ); ?>><?php esc_html_e( 'middle-left', 'wpc-shoppable-images' ); ?></option>
                                    <option value="middle-right" <?php selected( $lpos, 'middle-right' ); ?>><?php esc_html_e( 'middle-right', 'wpc-shoppable-images' ); ?></option>
                                    <option value="bottom-left" <?php selected( $lpos, 'bottom-left' ); ?>><?php esc_html_e( 'bottom-left', 'wpc-shoppable-images' ); ?></option>
                                    <option value="bottom-center" <?php selected( $lpos, 'bottom-center' ); ?>><?php esc_html_e( 'bottom-center', 'wpc-shoppable-images' ); ?></option>
                                    <option value="bottom-right" <?php selected( $lpos, 'bottom-right' ); ?>><?php esc_html_e( 'bottom-right', 'wpc-shoppable-images' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr class="wpcsi_configuration_tr">
                            <td class="wpcsi_configuration_th">
								<?php esc_html_e( 'Show popup', 'wpc-shoppable-images' ); ?>
                            </td>
                            <td class="wpcsi_configuration_td">
                                <label><input type="radio" name="trigger" value="click" <?php checked( $trigger, 'click' ); ?>/><?php esc_html_e( 'On click', 'wpc-shoppable-images' ); ?>
                                </label>
                                <label><input type="radio" name="trigger" value="hover" <?php checked( $trigger, 'hover' ); ?>/><?php esc_html_e( 'On hover', 'wpc-shoppable-images' ); ?>
                                </label>
                                <label><input type="radio" name="trigger" value="initial" <?php checked( $trigger, 'initial' ); ?>/><?php esc_html_e( 'On initial', 'wpc-shoppable-images' ); ?>
                                </label>
                            </td>
                        </tr>
                        <tr class="wpcsi_configuration_tr">
                            <td class="wpcsi_configuration_th">
								<?php esc_html_e( 'Popup position', 'wpc-shoppable-images' ); ?>
                            </td>
                            <td class="wpcsi_configuration_td">
                                <select name="position">
                                    <option value="top-left" <?php selected( $position, 'top-left' ); ?>><?php esc_html_e( 'top-left', 'wpc-shoppable-images' ); ?></option>
                                    <option value="top-center" <?php selected( $position, 'top-center' ); ?>><?php esc_html_e( 'top-center', 'wpc-shoppable-images' ); ?></option>
                                    <option value="top-right" <?php selected( $position, 'top-right' ); ?>><?php esc_html_e( 'top-right', 'wpc-shoppable-images' ); ?></option>
                                    <option value="middle-left" <?php selected( $position, 'middle-left' ); ?>><?php esc_html_e( 'middle-left', 'wpc-shoppable-images' ); ?></option>
                                    <option value="middle-right" <?php selected( $position, 'middle-right' ); ?>><?php esc_html_e( 'middle-right', 'wpc-shoppable-images' ); ?></option>
                                    <option value="bottom-left" <?php selected( $position, 'bottom-left' ); ?>><?php esc_html_e( 'bottom-left', 'wpc-shoppable-images' ); ?></option>
                                    <option value="bottom-center" <?php selected( $position, 'bottom-center' ); ?>><?php esc_html_e( 'bottom-center', 'wpc-shoppable-images' ); ?></option>
                                    <option value="bottom-right" <?php selected( $position, 'bottom-right' ); ?>><?php esc_html_e( 'bottom-right', 'wpc-shoppable-images' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr class="wpcsi_configuration_tr <?php echo esc_attr( $content == 'products' ? 'content-products' : 'content-text' ); ?>">
                            <td class="wpcsi_configuration_th">
								<?php esc_html_e( 'Content type', 'wpc-shoppable-images' ); ?>
                            </td>
                            <td class="wpcsi_configuration_td">
                                <label><input type="radio" name="content" value="products" <?php checked( $content, 'products' ); ?>><?php esc_html_e( 'Product(s)', 'wpc-shoppable-images' ); ?>
                                </label>
                                <label><input type="radio" name="content" value="text" <?php checked( $content, 'text' ); ?>><?php esc_html_e( 'Text', 'wpc-shoppable-images' ); ?>
                                </label>
                            </td>
                        </tr>
                        <tr class="wpcsi_configuration_tr show-type-text">
                            <td class="wpcsi_configuration_th">
								<?php esc_html_e( 'Text', 'wpc-shoppable-images' ); ?>
                            </td>
                            <td class="wpcsi_configuration_td">
                                <textarea name="text" cols="30" rows="5"><?php echo esc_html( $text ); ?></textarea>
                            </td>
                        </tr>
                        <tr class="wpcsi_configuration_tr show-type-products">
                            <td class="wpcsi_configuration_th">
								<?php esc_html_e( 'Products', 'wpc-shoppable-images' ); ?>
                            </td>
                            <td class="wpcsi_configuration_td">
                                <select class="wc-product-search" name="products" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'wpc-shoppable-images' ); ?>" data-action="woocommerce_json_search_products">
									<?php
									foreach ( $products as $product_id ) {
										$product_obj = wc_get_product( $product_id );

										if ( $product_obj ) {
											echo '<option value="' . esc_attr( $product_id ) . '" selected="selected">' . wp_kses_post( $product_obj->get_formatted_name() ) . '</option>';
										}
									}
									?>
                                </select>
                            </td>
                        </tr>
                        <tr class="wpcsi_configuration_tr show-type-products">
                            <td class="wpcsi_configuration_th">
								<?php esc_html_e( 'Product image position', 'wpc-shoppable-images' ); ?>
                            </td>
                            <td class="wpcsi_configuration_td">
                                <label><input type="radio" name="image" value="left" <?php checked( $image, 'left' ); ?>><?php esc_html_e( 'Left', 'wpc-shoppable-images' ); ?>
                                </label>
                                <label><input type="radio" name="image" value="right" <?php checked( $image, 'right' ); ?>><?php esc_html_e( 'Right', 'wpc-shoppable-images' ); ?>
                                </label>
                                <label><input type="radio" name="image" value="top" <?php checked( $image, 'top' ); ?>><?php esc_html_e( 'Top', 'wpc-shoppable-images' ); ?>
                                </label>
                                <label><input type="radio" name="image" value="hide" <?php checked( $image, 'hide' ); ?>><?php esc_html_e( 'Hide', 'wpc-shoppable-images' ); ?>
                                </label>
                            </td>
                        </tr>
                        <tr class="wpcsi_configuration_tr show-type-products">
                            <td class="wpcsi_configuration_th">
								<?php esc_html_e( 'Link to individual product', 'wpc-shoppable-images' ); ?>
                            </td>
                            <td class="wpcsi_configuration_td">
                                <label><input type="radio" name="link" value="no" <?php checked( $link, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-shoppable-images' ); ?>
                                </label>
                                <label><input type="radio" name="link" value="same" <?php checked( $link, 'same' ); ?>><?php esc_html_e( 'Same tab', 'wpc-shoppable-images' ); ?>
                                </label>
                                <label><input type="radio" name="link" value="new" <?php checked( $link, 'new' ); ?>><?php esc_html_e( 'New tab', 'wpc-shoppable-images' ); ?>
                                </label>
                                <label class="hint--top" aria-label="<?php esc_attr_e( 'Install and activate WPC Smart Quick View to make it work', 'wpc-shoppable-images' ); ?>"><input type="radio" name="link" value="quickview" <?php checked( $link, 'quickview' ); ?>><?php esc_html_e( 'Quick view popup', 'wpc-shoppable-images' ); ?>
                                </label>
                            </td>
                        </tr>
                        <tr class="wpcsi_configuration_tr show-type-products">
                            <td class="wpcsi_configuration_th">
								<?php esc_html_e( 'Show price', 'wpc-shoppable-images' ); ?>
                            </td>
                            <td class="wpcsi_configuration_td">
                                <label><input type="radio" name="price" value="no" <?php checked( $price, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-shoppable-images' ); ?>
                                </label>
                                <label><input type="radio" name="price" value="yes" <?php checked( $price, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-shoppable-images' ); ?>
                                </label>
                            </td>
                        </tr>
                        <tr class="wpcsi_configuration_tr show-type-products">
                            <td class="wpcsi_configuration_th">
								<?php esc_html_e( 'Show "Add to cart" button', 'wpc-shoppable-images' ); ?>
                            </td>
                            <td class="wpcsi_configuration_td">
                                <label><input type="radio" name="cart" value="no" <?php checked( $cart, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-shoppable-images' ); ?>
                                </label>
                                <label><input type="radio" name="cart" value="yes" <?php checked( $cart, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-shoppable-images' ); ?>
                                </label>
                            </td>
                        </tr>
                        <tr class="wpcsi_configuration_tr show-type-products">
                            <td class="wpcsi_configuration_th">
								<?php esc_html_e( 'Enable carousel', 'wpc-shoppable-images' ); ?>
                            </td>
                            <td class="wpcsi_configuration_td">
                                <label><input type="radio" name="carousel" value="no" <?php checked( $carousel, 'no' ); ?>><?php esc_html_e( 'No', 'wpc-shoppable-images' ); ?>
                                </label>
                                <label><input type="radio" name="carousel" value="yes" <?php checked( $carousel, 'yes' ); ?>><?php esc_html_e( 'Yes', 'wpc-shoppable-images' ); ?>
                                </label>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
		<?php
	}

	public function enqueue_scripts( $hook ) {
		wp_enqueue_style( 'hint', WPCSI_URI . 'assets/css/hint.css', [], WPCSI_VERSION );
		wp_enqueue_style( 'wpcsi-backend', WPCSI_URI . 'assets/css/backend.css', [ 'woocommerce_admin_styles' ], WPCSI_VERSION );
		wp_enqueue_script( 'wpcsi-backend', WPCSI_URI . 'assets/js/backend.js', [
			'jquery',
			'wc-enhanced-select',
			'jquery-ui-draggable',
		], WPCSI_VERSION, true );
	}

	public function init() {
		self::register_post_type();
	}

	public function register_post_type() {
		$labels = [
			'name'          => _x( 'Shoppable Images', 'Post Type General Name', 'wpc-shoppable-images' ),
			'singular_name' => _x( 'Shoppable Image', 'Post Type Singular Name', 'wpc-shoppable-images' ),
			'add_new_item'  => esc_html__( 'Add New Shoppable Image', 'wpc-shoppable-images' ),
			'add_new'       => esc_html__( 'Add New', 'wpc-shoppable-images' ),
			'edit_item'     => esc_html__( 'Edit Shoppable Image', 'wpc-shoppable-images' ),
			'update_item'   => esc_html__( 'Update Shoppable Image', 'wpc-shoppable-images' ),
			'search_items'  => esc_html__( 'Search Shoppable Image', 'wpc-shoppable-images' ),
		];

		$args = [
			'label'               => esc_html__( 'Shoppable Image', 'wpc-shoppable-images' ),
			'labels'              => $labels,
			'supports'            => [ 'title', 'excerpt', 'thumbnail' ],
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 28,
			'menu_icon'           => 'dashicons-format-gallery',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
			'show_in_rest'        => false,
		];

		register_post_type( 'wpc_shoppable_image', $args );
	}
}

return WPCleverWpcsi_Backend::instance();
