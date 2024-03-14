<?php

namespace MyCustomizer\WooCommerce\Connector\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use MyCustomizer\WooCommerce\Connector\Auth\MczrAccess;
use MyCustomizer\WooCommerce\Connector\Factory\MczrFactory;
use MyCustomizer\WooCommerce\Connector\Libs\MczrConnect;
use MyCustomizer\WooCommerce\Connector\Libs\MczrSettings;
use MyCustomizer\WooCommerce\Connector\Libs\MczrProduct;

MczrAccess::isAuthorized();

class MczrCartController {

	public $defaultQuantities = array(
		'input_value' => 1,
		'min_value'   => 1,
		'max_value'   => 9999,
		'step'        => 1,
	);
	public $quantities;

	public function __construct() {
		$this->request        = Request::createFromGlobals();
		$this->response       = new Response();
		$this->settings       = new MczrSettings();
		$this->mczr           = new MczrConnect();
		$this->factory        = new MczrFactory();
		$this->twig           = $this->factory->getTwig();
		$this->productToolkit = new MczrProduct();
	}

	public function init() {
		add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'saveItemMetasAsOrderMetas' ), 10, 3 );
		add_action( 'wp_ajax_addToCart', array( $this, 'addToCart' ) );
		add_action( 'wp_ajax_nopriv_addToCart', array( $this, 'addToCart' ) );
		add_action( 'woocommerce_before_cart', array( $this, 'displayCartCss' ) );
		add_action( 'woocommerce_before_checkout_form', array( $this, 'displayCartCss' ) );
		add_filter( 'woocommerce_cart_item_name', array( $this, 'displayPropertiesAfterTitleInCartPage' ), 10, 3 );
		add_filter( 'woocommerce_cart_item_thumbnail', array( $this, 'filterCartImageThumbnail' ), 10, 3 );
		add_filter( 'woocommerce_cart_item_permalink', array( $this, 'filterCartItemLink' ), 10, 3 );
	}

	public function saveItemMetasAsOrderMetas( $item, $cart_item_key, $values, $order = null ) {
		if ( isset( $values['mczrMetas'] ) ) {
			$item->update_meta_data( 'mczr', $values['mczrMetas'] );
		}
		return;
	}

	public function inputLimitFilter( $args, $variation ) {
		$quantities = array_merge( $args, $this->quantities );
		// Reset quantities for next row
		$this->quantities = $this->defaultQuantities;
		return $quantities;
	}

	public function getQuantitiesConfiguration( $item, $product ) {
		$this->quantities = $this->defaultQuantities;
		$quantities       = array();
		$correspondings   = array(
			'min_value' => 'mczrQuantityMin',
			'max_value' => 'mczrQuantityMax',
			'step'      => 'mczrQuantityStep',
		);

		// Has input value
		$start = get_post_meta( $product->get_id(), 'mczrQuantityStart', true );
		$input = (int) $item['quantity'];
		if ( ! empty( $start ) && $start > 1 && 1 === $item['quantity'] ) :
			$input = (int) get_post_meta( $product->get_id(), 'mczrQuantityStart', true );
		endif;

		foreach ( $correspondings as $name => $metaName ) :
			$metaIsEmpty         = get_post_meta( $product->get_id(), $metaName, true ) === '';
			$quantities[ $name ] = ( $metaIsEmpty ) ? $this->defaultQuantities[ $name ] : (int) get_post_meta( $product->get_id(), $metaName, true );
		endforeach;

		$quantities['input_value'] = $input;
		return $quantities;
	}

	public function displayPropertiesAfterTitleInCartPage( $itemName, $item, $cartItemKey ) {
		// Reset quantities
		$product = wc_get_product( $item['product_id'] );
		if ( ! $product->is_type( 'mczr' ) ) {
			return $itemName;
		}
		if ( ! ( isset( $item['mczrMetas']['summary_v2'] ) && count( $item['mczrMetas']['summary_v2'] ) >= 1 ) ) {
			return $itemName;
		}
		// Set quantities
		$this->quantities = $this->getQuantitiesConfiguration( $item, $product );
		add_filter( 'woocommerce_quantity_input_args', array( $this, 'inputLimitFilter' ), 99, 3 );

		$vars               = array();
		$vars['properties'] = $item['mczrMetas']['summary_v2'];
		$itemName          .= $this->twig->render( 'Cart/properties.html.twig', $vars );
		return $itemName;
	}

	public function displayCartCss() {
		echo '<style type="text/css">
		li.mczrPropsListItem {
		    overflow-wrap: break-word;
		    word-wrap: break-word;
		    -ms-word-break: break-all;
		    word-break: break-all;
		    word-break: break-word;
		    -ms-hyphens: none;
		    -moz-hyphens: none;
		    -webkit-hyphens: none;
		    hyphens: none;
		}
		</style>';
	}

	public function addToCart() {
		$this->response->headers->set( 'Content-Type', 'application/json' );
		try {
			$productId = (int) $this->request->get( 'productId' ); // TODO : filter this
			$start     = get_post_meta( $productId, 'mczrQuantityStart', true );
			$quantity  = 1;
			if ( ! empty( $start ) && $start > 1 && \is_numeric( $start ) ) :
				$quantity = $start;
			endif;

			$props = $this->request->get( 'props' ); // TODO : filter this
			$path  = "/brands/{$this->settings->get('brand')}/designs/{$props['designId']}";
			$vars  = array(
				'success' => false,
				'message' => 'Unknown error',
				'data'    => array(),
			);

			// Connect to mczr api && get the real price : avoid cheating
			$productMzcrApi                = $this->mczr->get( $path, true );
			$props['price']                = $productMzcrApi->price;
			$props['orignalStartingPoint'] = get_post_meta( $productId, 'mczrStartingPoint', true );
			$props['startingPoint']        = $productMzcrApi->_id;

			// Add the variation to the cart
			$variationDatas = array(
				'attributes'    => array(),
				'sku'           => 'mczr_' . \uniqid(),
				'regular_price' => $productMzcrApi->price,
				'sale_price'    => $productMzcrApi->price,
				'stock_qty'     => 99999,
			);
			$variationId    = $this->createVariation( $productId, $variationDatas, $props );
			$addedToCart    = WC()->cart->add_to_cart( $productId, $quantity, $variationId, array(), array( 'mczrMetas' => $props ) );

			$cart_content = WC()->cart->cart_contents;
			$cart_content_changed = false;
			foreach ( $cart_content as $cart_item_key => $cart_item ) {
				if ( $cart_item_key === $addedToCart
					&& !isset( $cart_item['mczrMetas'] ) ) {
					$cart_item['mczrMetas'] = $props;
					$cart_content[$cart_item_key] = $cart_item;
					$cart_content_changed = true;
				}
			}
			if ($cart_content_changed) {
				WC()->cart->set_cart_contents($cart_content);
			}

			// Oups
			if ( ! $addedToCart ) {
				throw new \Exception( 'Unable to add to cart' );
			}

			// All ok
			$vars['message']          = 'Product added to cart.';
			$vars['success']          = true;
			$vars['data']['props']    = $props;
			$vars['data']['redirect'] = get_permalink( wc_get_page_id( 'cart' ) );
			
			$vars['data']['fragments'] = apply_filters('woocommerce_add_to_cart_fragments', array());
			$vars['data']['cart_hash'] = WC()->cart->get_cart_hash();
			do_action( 'woocommerce_ajax_added_to_cart', $productId );

			$this->response
				->setContent( json_encode( $vars ) )
				->setStatusCode( Response::HTTP_OK )
				->send();
		} catch ( \Exception $ex ) {
			// All ok
			$vars['message'] = 'ERROR : ' . $ex->getMessage();
			$vars['success'] = false;
			$this->response
				->setContent( json_encode( $vars ) )
				->setStatusCode( Response::HTTP_INTERNAL_SERVER_ERROR )
				->send();
		}
		exit;
	}

	public function filterCartItemLink( $link, $item ) {
		$product = wc_get_product( $item['product_id'] );
		if ( ! ( $product->is_type( 'mczr' ) && isset( $item['mczrMetas']['customizationNumber'] ) ) ) {
			return $link;
		}
		$productUrl = $this->productToolkit->buildProductUrl( $product->get_id(), false );
		$url        = \str_replace( '<designId>', $item['mczrMetas']['customizationNumber'], $productUrl );
		return $url;
	}

	public function filterCartImageThumbnail( $imgTag, $item ) {
		$product = wc_get_product( $item['product_id'] );
		if ( ! ( $product->is_type( 'mczr' ) && isset( $item['mczrMetas']['image'] ) ) ) {
			return $imgTag;
		}
		// Edit image the good way
		$doc = new \DOMDocument();
		libxml_use_internal_errors(true);
		$doc->loadHTML( $imgTag, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
		$img = $doc->getElementsByTagName( 'img' )->item( 0 );
		if ( ! isset( $img ) ) {
			// Edit image the wrong way I guess
			return "<img src='" . $item['mczrMetas']['image'] . "' srcset='" . $item['mczrMetas']['image'] . "' class='mczrDesignImg'/>";
		}
		$img->setAttribute( 'src', $item['mczrMetas']['image'] );
		$img->setAttribute( 'srcset', $item['mczrMetas']['image'] );
		// Add a specific class on mczr img for tests
		$img->setAttribute( 'class', $img->getAttribute( 'class' ) . ' mczrDesignImg' );
		return $doc->saveHTML();
	}

	private function createVariation( $product_id, $variation_data ) {
		// Get the Variable product object (parent)
		$product = wc_get_product( $product_id );

		$variation_post = array(
			'post_title'  => $product->get_title(),
			'post_name'   => 'mczr-product-' . $product_id . '-variation',
			'post_status' => 'publish',
			'post_parent' => $product_id,
			'post_type'   => 'product_variation',
			'guid'        => $product->get_permalink(),
		);

		// Creating the product variation
		$variationPostId = wp_insert_post( $variation_post );
		$variation       = new \WC_Product_Variation( $variationPostId );

		// Process attributes
		foreach ( $variation_data['attributes'] as $key => $term_name ) {
			$taxonomy = 'pa_' . $key;
			// Term does not exists
			if ( ! term_exists( $term_name, $taxonomy ) ) {
				wp_insert_term( $term_name, $taxonomy );
			}

			$term_slug = get_term_by( 'name', $term_name, $taxonomy )
				->slug;
			// Get the post Terms names from the parent variable product.
			$post_term_names = wp_get_post_terms( $product_id, $taxonomy, array( 'fields' => 'names' ) );

			// Check if the post term exist && if not we set it in the parent variable product.
			if ( ! in_array( $term_name, $post_term_names ) ) {
				wp_set_post_terms( $product_id, $term_name, $taxonomy, true );
			}

			// Set/save the attribute data in the product variation
			update_post_meta( $variationPostId, 'attribute_' . $taxonomy, $term_slug );
		}
		// Set/save all other data
		if ( ! empty( $variation_data['sku'] ) ) {
			$variation->set_sku( $variation_data['sku'] );
		}

		// Prices
		if ( empty( $variation_data['sale_price'] ) ) {
			$variation->set_price( $variation_data['regular_price'] );
		} else {
			$variation->set_price( $variation_data['sale_price'] );
			$variation->set_sale_price( $variation_data['sale_price'] );
		}
		$variation->set_regular_price( $variation_data['regular_price'] );

		// Stock
		$variation->set_manage_stock( false );
		if ( ! empty( $variation_data['stock_qty'] ) ) {
			$variation->set_stock_quantity( $variation_data['stock_qty'] );
			$variation->set_manage_stock( true );
			$variation->set_stock_status( '' );
		}

		// Reset weight
		$variation->set_weight( '' );

		return $variation->save();
	}
}
