<?php

/**
 * Pootle Pagebuilder Product Builder public class
 * @property string $token Plugin token
 * @property string $url Plugin root dir url
 * @property string $path Plugin root dir path
 * @property string $version Plugin version
 */
class WooBuilder_Public{

	/**
	 * @var 	WooBuilder_Public Instance
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * Main Pootle Pagebuilder Product Builder Instance
	 * Ensures only one instance of Storefront_Extension_Boilerplate is loaded or can be loaded.
	 * @since 1.0.0
	 * @return WooBuilder_Public instance
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
	 * @since   1.0.0
	 */
	private function __construct() {
		$this->token   =   WooBuilder::$token;
		$this->url     =   WooBuilder::$url;
		$this->path    =   WooBuilder::$path;
		$this->version =   WooBuilder::$version;

		add_shortcode( 'ppb_product_short_description', function() {
			ob_start();
			woocommerce_template_single_excerpt();
			return ob_get_clean();
		} );
		add_shortcode( 'ppb_product_price', function() {
			ob_start();
			woocommerce_template_single_price();
			return ob_get_clean();
		} );
		add_shortcode( 'ppb_product_title', function() {
			ob_start();
			woocommerce_template_loop_product_title();
			return ob_get_clean();
		} );
		add_shortcode( 'ppb_product_related', function() {
			ob_start();
			woocommerce_related_products();
			return ob_get_clean();
		} );
		add_shortcode( 'ppb_product_images', function() {
			ob_start();
			woocommerce_show_product_images();
			return ob_get_clean();
		} );
		add_shortcode( 'ppb_product_rating', function() {
			ob_start();
			woocommerce_template_single_rating();
			return ob_get_clean();
		} );
		add_shortcode( 'ppb_product_add_to_cart', function() {
			ob_start();
			woocommerce_template_single_add_to_cart();
			return ob_get_clean();
		} );
		add_shortcode( 'ppb_product_tabs', function() {
			ob_start();

			add_filter( 'the_content', 'wpautop' );

			woocommerce_output_product_data_tabs();

			remove_filter( 'the_content', 'wpautop' );

			return ob_get_clean();
		} );
		add_shortcode( 'ppb_product_reviews', function() {
			ob_start();
			comments_template();
			return ob_get_clean();
		} );

	} // End __construct()

	/**
	 * Adds front end stylesheet and js
	 * @action wp_enqueue_scripts
	 * @since 1.0.0
	 */
	public function wc_get_template_part( $template, $slug, $name ) {
		if (
			'content' == $slug &&
			'single-product' == $name
		) {
			if ( WooBuilder::is_ppb_product() ) {
				$template = dirname( __FILE__ ) . '/ppb-product-tpl.php';
			} else if ( get_post_meta( get_the_ID(), 'woobuilder_used_builder', 'single' ) ) {
				remove_filter( 'the_content', array( $GLOBALS['Pootle_Page_Builder_Render_Layout'], 'content_filter' ) );
			}
		}
		return $template;
	}

	/**
	 * Sets post meta for ppb product
	 * @param array  $page_data
	 * @param int    $post_id
	 * @param string $post_type
	 * @return array
	 */
	public function set_ppb_product_builder_meta( $page_data, $post_id, $post_type ) {
		if (
			'product' == $post_type &&
			wp_verify_nonce( filter_input( INPUT_GET, 'woobuilder-nonce' ), 'enable_ppb_product_builder' )
		) {

			if ( ! pootlepb_uses_pb( $post_id ) ) {
				global $ppble_new_live_page;

				require Pootle_Page_Builder_Live_Editor::$path . 'inc/vars.php';

				$user         = '';
				$current_user = wp_get_current_user();
				if ( $current_user instanceof WP_User ) {
					$user = ' ' . ucwords( $current_user->user_login );
				}

				/**
				 * Filters new live page template
				 *
				 * @param int $id Post ID
				 */
				$ppb_data = apply_filters( 'woobuilder_live_product_template', $ppble_new_live_page, $post_id, $post_type );

				foreach ( $ppb_data['widgets'] as $i => $wid ) {
					if ( ! empty( $wid['info']['style'] ) ) {
						$ppb_data['widgets'][ $i ]['info']['style'] = stripslashes( $wid['info']['style'] );
					}
					$ppb_data['widgets'][ $i ]['text'] = html_entity_decode( stripslashes( str_replace( '<!--USER-->', $user, str_replace( '&nbsp;', '&amp;nbsp;', $wid['text'] ) ) ) );
				}

				update_post_meta( $post_id, 'panels_data', $ppb_data );
			}

			update_post_meta( $post_id, 'woobuilder', 1 );
		}
		return $page_data;
	}

	/**
	 * @param array $ppb_data WooBuilder product starter template
	 */
	public function filter_live_product_template( $ppb_data ) {
		$id = get_option( 'pootlepb-template-product', 0 );
		if ( $id ) {
			$ppb_meta = get_post_meta( $id, 'panels_data', 'single' );
			if ( ! empty( $ppb_meta['grids'] ) && is_array( $ppb_meta['grids'] ) ) {
				$ppb_data = $ppb_meta;
			}
		}
		return $ppb_data;
	}

	/**
	 * Prints live editor scripts
	 * @since 1.0.0
	 */
	public function live_editor_scripts() {
		add_action( 'wp_footer', function () {
			?>
			<script>
				jQuery( function ( $ ) {

					ppbProdbuilderSetting = function( $t, val ) {
						var $pnl = $( '#pootlepb-content-editor-panel' );
						$t.find( '.ppb-edit-block .settings-dialog' ).click();
						$('select[dialog-field="woobuilder"]').val( val );
						$pnl.next( 'div' ).find( 'button' ).click();
						$pnl.ppbDialog( 'close' );
					};

					window.ppbModules.ppbProd_a2c = function ( $t ) {
						ppbProdbuilderSetting( $t, '[ppb_product_add_to_cart]' );
					};

					window.ppbModules.ppbProd_desc = function ( $t ) {
						ppbProdbuilderSetting( $t, '[ppb_product_short_description]' );
					};

					window.ppbModules.ppbProd_details = function ( $t ) {
						ppbProdbuilderSetting( $t, [
							'[ppb_product_title]',
							'[ppb_product_short_description]',
							'[ppb_product_price]',
							'[ppb_product_add_to_cart]',
							] );
					};
					window.ppbModules.ppbProd_tabs = function ( $t ) {
						ppbProdbuilderSetting( $t, '[ppb_product_tabs]' );
					};
					window.ppbModules.ppbProd_related = function ( $t ) {
						ppbProdbuilderSetting( $t, '[ppb_product_related]' );
					};
					window.ppbModules.ppbProd_images = function ( $t ) {
						ppbProdbuilderSetting( $t, '[ppb_product_images]' );
					};
					window.ppbModules.ppbProd_rating = function ( $t ) {
						ppbProdbuilderSetting( $t, '[ppb_product_rating]' );
					};
					window.ppbModules.ppbProd_reviews = function ( $t ) {
						ppbProdbuilderSetting( $t, '[ppb_product_reviews]' );
					};

					$( '#pootle-page-builder' ).on( 'dblclick click', '.woobuilder-module', function( e ) {
						e.preventDefault();
						ppbNotify('Sorry, This data is coming from WooCommerce and can’t be edited in live editor.');
					} )
				} );
			</script>
			<style>
				.woobuilder-module {
					user-select: none;
					-moz-user-select: none;
					-khtml-user-select: none;
					-webkit-user-select: none;
					-o-user-select: none;
				}
				.field.field-woobuilder .chosen-choices .search-choice {
					display: block;
					float: none;
					margin: 5px 0;
				}
			</style>
			<?php
		} );
	}

	/**
	 * Disables dumping content for WB Products
	 * @param bool $bool
	 * @param int $post_id
	 *
	 * @return bool
	 */
	public function pootlepb_dump_ppb_content( $bool, $post_id ) {
		if ( 'product' == get_post_type( $post_id ) ) {
			return false;
		}

		return $bool;
	}

	/**
	 * Adds front end stylesheet and js
	 * @action wp_enqueue_scripts
	 * @since 1.0.0
	 */
	public function enqueue() {
		$token = $this->token;
		$url = $this->url;

		wp_enqueue_style( $token . '-css', $url . '/assets/front-end.css' );
		wp_enqueue_script( $token . '-js', $url . '/assets/front-end.js', array( 'jquery' ) );
	}

	/**
	 * Processes the content block setting and renders the short code
	 * @param array $data Content panel data
	 * @since 1.0.0
	 */
	public function process_shortcode( $data ) {
		if (
			(
				! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) && // Not doing AJAX
				! WooBuilder::is_ppb_product( get_the_ID() )  // And not using product builder
			) ||
			empty( $data['info'] ) || empty( $data['info']['style'] ) // Or content block info or style ain't defined
		) {
			return;
		}
		$settings = json_decode( $data['info']['style'], 'associative_array' );

		if ( ! empty( $settings[ $this->token ] ) ) {

			global $Pootle_Page_Builder_Render_Layout;

			if ( has_filter( 'the_content', array( $Pootle_Page_Builder_Render_Layout, 'content_filter' ) ) ) {
				remove_filter( 'the_content', array( $Pootle_Page_Builder_Render_Layout, 'content_filter' ) );
			}

			if ( $_SERVER['REQUEST_METHOD'] === 'POST' && Pootle_Page_Builder_Live_Editor_Public::is_active() ) {
				global $post, $product, $withcomments;
				$withcomments = true;
				$post = get_post( $_POST['post'] );
				$product = wc_get_product( $post );
			}
			$shortcodes = $settings[ $this->token ];
			if ( ! is_array( $shortcodes ) ) {
				$shortcodes = [ $shortcodes, ];
			}
			foreach ( $shortcodes as $shortcode ) {
				$code = str_replace( array( '[', ']' ), '', $shortcode ); // Remove square brackets
				$code = explode( ' ', $code )[0]; // Get shortcode name
				$shortcode = str_replace( '%id%', get_the_ID(), $shortcode );
				add_filter( 'woocommerce_gallery_image_size', [ $this, 'woocommerce_gallery_image_size' ] );
				?>
				<div id="woobuilder-<?php echo $code ?>" class="woobuilder-module">
					<!--<?php echo $shortcode ?>-->
					<?php echo do_shortcode( $shortcode ); ?>
				</div>
				<?php
				remove_filter( 'woocommerce_gallery_image_size', [ $this, 'woocommerce_gallery_image_size' ] );
			}
		}
	}

	public function woocommerce_gallery_image_size() {
		return 'large';
	}

	public function init() {

	}
}