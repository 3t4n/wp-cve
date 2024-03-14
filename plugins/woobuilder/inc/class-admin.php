<?php
/**
 * Pootle Pagebuilder Product Builder Admin class
 * @property string token Plugin token
 * @property string $url Plugin root dir url
 * @property string $path Plugin root dir path
 * @property string $version Plugin version
 */
class WooBuilder_Admin{

	/**
	 * @var 	WooBuilder_Admin Instance
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * Main Pootle Pagebuilder Product Builder Instance
	 * Ensures only one instance of Storefront_Extension_Boilerplate is loaded or can be loaded.
	 * @return WooBuilder_Admin instance
	 * @since 	1.0.0
	 */
	public static function instance() {
		if ( null == self::$_instance ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Constructor function.
	 * @access  private
	 * @since 	1.0.0
	 */
	private function __construct() {
		$this->token   =   WooBuilder::$token;
		$this->url     =   WooBuilder::$url;
		$this->path    =   WooBuilder::$path;
		$this->version =   WooBuilder::$version;
	} // End __construct()

	/**
	 * Adds admin only actions
	 * @action admin_init
	 */
	public function admin_init() {
		add_filter( 'pootlepb_builder_post_types', array( $this, 'remove_ppb_product' ), 99 );

		register_setting( 'pootlepage-display', 'pootlepb-template-product' );

		add_settings_field( 'responsive', __( 'WooBuilder starter template', 'ppb-panels' ), array(
			$this,
			'template_product_field',
		), 'pootlepage-display', 'display' );

	}

	public function template_product_field() {
		$selected = get_option( 'pootlepb-template-product' );
		$prods = get_posts( [
			'post_status' => 'publish',
			'numberposts' => 99,
			'post_type'   => 'product',
			'meta_query'     => array(
				array(
					'key'     => 'panels_data',
					'compare' => 'EXISTS',
				),
			)
		] );
		?>
		<select name="pootlepb-template-product">
			<?php
			if ( $prods ) {
				?>
				<option>Please choose...</option>
				<?php
				foreach ( $prods as $prod ) {
					?>
					<option value='<?php echo $prod->ID ?>' <?php selected( $prod->ID, $selected ) ?>><?php echo $prod->post_title ?></option>
					<?php
				}
			} else {
				?>
				<option>No Pagebuilder products found...</option>
				<?php
			}
			?>
		</select>
		<p>
			This product's page builder rows will be used as starter for all products.
		</p>
		<?php
	}

	/**
	 * @param int $post_id
	 */
	public function save_post( $post_id ) {
		// Verify that the nonce is valid.
		if (
			! wp_verify_nonce( filter_input( INPUT_POST, 'woobuilder-nonce' ), 'woobuilder-meta' ) ||
			( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		) {
			return;
		}
		update_post_meta( $post_id, 'woobuilder', filter_input( INPUT_POST, 'woobuilder' ) );
		update_post_meta( $post_id, 'woobuilder_used_builder', 1 );
	}

	/**
	 * Adds admin only actions
	 * @action admin_init
	 */
	public function product_meta_fields() {
		if ( 'product' !== get_post_type() ) { return; }
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'woobuilder-meta', 'woobuilder-nonce' );
		?>
		<div class="clear misc-pub-section">
			<label for="woobuilder"><b><?php _e( 'Enable Product builder', $this->token ); ?></b></label>
			<input type="checkbox" class="checkbox" style="" name="woobuilder" id="woobuilder" value="1" <?php
			checked( get_post_meta( get_the_ID(), 'woobuilder', 'single' ), 1 );
			?>>
			<span class="description">
			<?php
			if ( WooBuilder::is_ppb_product( get_the_ID() ) ) {
				_e( 'Uncheck this to disable', $this->token );
			} else {
				_e( 'Check this to enable', $this->token );
			}
			?>
			</span>
		</div>
		<?php
	}

	public function enqueue() {
		global $post;

		if ( $post->post_type == 'product' ) {
			wp_enqueue_script(  $this->token, "$this->url/assets/edit-product.js", array( 'jquery' ) );

			$nonce_url = wp_nonce_url( get_the_permalink( $post->ID ), 'ppb-live-edit-nonce', 'ppbLiveEditor' );

			$nonce_url .= '&woobuilder-nonce=' . wp_create_nonce( 'enable_ppb_product_builder' );

			wp_localize_script(  $this->token, 'wcProductBuilderLiveEditLink', $nonce_url );

			echo <<<HTML
<style>
	a.button.pootle {
		margin: .5em 0 .25em .5em;
	}
	button.wp-switch-editor {
		padding: .5em .7em;
	}
	.field.field-woobuilder .chosen-choices .search-choice {
    display: block;
    float: none;
    margin: 5px 0;
	}
</style>
HTML;

		}
	}

	/**
	 * Removes product from ppb supported posts on admin end.
	 * @param $post_types Post types
	 * @return array Post types
	 */
	public function remove_ppb_product( $post_types ) {
		$post_types = array_unique( $post_types );
		unset( $post_types[ array_search( 'product', $post_types ) ] );

		return $post_types;
	}

	/**
	 * Adds editor panel tab
	 * @param array $tabs The array of tabs
	 * @return array Tabs
	 * @filter pootlepb_content_block_tabs
	 * @since 	1.0.0
	 */
	public function content_block_tabs( $tabs ) {
		if ( WooBuilder::is_ppb_product() ) {
			$tabs[ $this->token ] = array(
				'label' => 'Product Builder',
				'priority' => 5,
			);
		}
		return $tabs;
	}

	/**
	 * Adds content block panel fields
	 * @param array $fields Fields to output in content block panel
	 * @return array Tabs
	 * @filter pootlepb_content_block_fields
	 * @since 	1.0.0
	 */
	public function content_block_fields( $fields ) {
		if ( WooBuilder::is_ppb_product() ) {
			$fields[ $this->token ] = array(
				'name'     => 'Display',
				'type'     => 'multi-select',
				'priority' => 1,
				'options'  => array(
					''                                => 'Choose...',
					'[ppb_product_title]'             => 'Product Title',
					'[ppb_product_images]'            => 'Product images',
					'[ppb_product_short_description]' => 'Short Description',
					'[ppb_product_price]'             => 'Product Price',
					'[ppb_product_add_to_cart]'       => 'Add to Cart',
					'[ppb_product_tabs]'              => 'Product tabs',
					'[ppb_product_reviews]'           => 'Product reviews',
					'[ppb_product_related]'           => 'Related products',
					'[ppb_product_rating]'            => 'Product rating',
				),
				'tab'      => $this->token,
			);
		}

		return $fields;
	}
}