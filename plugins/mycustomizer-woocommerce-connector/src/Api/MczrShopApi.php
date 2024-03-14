<?php

namespace MyCustomizer\WooCommerce\Connector\Api;

use MyCustomizer\WooCommerce\Connector\Libs\MczrConnect;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use MyCustomizer\WooCommerce\Connector\Auth\MczrAccess;
use MyCustomizer\WooCommerce\Connector\Controller\Admin\MczrProductTypeController;
use MyCustomizer\WooCommerce\Connector\Controller\MczrOrderController;
use MyCustomizer\WooCommerce\Connector\Libs\MczrSettings;

MczrAccess::isAuthorized();

class MczrShopApi implements \MyCustomizer\WooCommerce\Connector\Api\Interfaces\MczrApi {

	public function __construct() {
		$this->request                   = Request::createFromGlobals();
		$this->response                  = new Response();
		$this->settings                  = new MczrSettings();
		$this->mczrConnect               = new MczrConnect();
		$this->mczrProductTypeController = new MczrProductTypeController();
		$this->mczrOrderController			 = new MczrOrderController();
	}

	public function init() {
		// /?rest_route=/mczr/v1/
		add_action( 'rest_api_init', array( $this, 'registerRestRoute' ) );
		add_action( 'rest_api_init', array( $this, 'allowAllCors' ), 15 );
	}

	public function allowAllCors() {
		// Remove the default filter.
		remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
		// Add a Custom filter.
		add_filter( 'rest_pre_serve_request', array( $this, 'corsHeaders' ) );
	}

	public function corsHeaders( $value ) {
		header( 'Access-Control-Allow-Origin: *' );
		header( 'Access-Control-Allow-Methods: POST, GET, OPTIONS' );
		header( 'Access-Control-Allow-Credentials: true' );
		return $value;
	}

	public function registerRestRoute() {
		register_rest_route(
			'mczr',
			'/test',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'test' ),
				'permission_callback' => '__return_true',
			)
		);
		register_rest_route(
			'mczr',
			'/connect',
			array(
				'methods'  => 'POST',
				'callback' => array( $this, 'shopConnect' ),
				'permission_callback' => '__return_true',
			)
		);
		register_rest_route(
			'mczr',
			'/startingpoints',
			array(
				'methods'  => 'POST',
				'callback' => array( $this, 'createStartingPoint' ),
				'permission_callback' => '__return_true',
			)
		);
		register_rest_route(
			'mczr',
			'/startingpoints/(?P<id>\w+)',
			array(
				'methods'  => 'POST',
				'callback' => array($this, 'updateStartingPoint'),
				'permission_callback' => '__return_true',
			)
		);
		register_rest_route(
			'mczr',
			'/startingpoints',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'getStartingPoints' ),
				'permission_callback' => '__return_true',
			)
		);
		register_rest_route(
			'mczr',
			'/startingpoints/(?P<id>\w+)',
			array(
				'methods' => 'GET',
				'callback' => array( $this, 'getStartingPoint' ),
				'permission_callback' => '__return_true',
			)
		);
		register_rest_route(
			'mczr',
			'/startingpoints/(?P<id>\w+)',
			array(
				'methods'  => 'DELETE',
				'callback' => array( $this, 'removeStartingPoint' ),
				'permission_callback' => '__return_true',
			)
		);
		register_rest_route(
			'mczr',
			'/reconcile/orders',
			array(
				'methods'  => 'POST',
				'callback' => array( $this, 'reconcileOrders' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	public function test() {
		MczrAccess::isAPIAuthorized();

		$this->response->headers->set( 'Access-Control-Allow-Origin', '*' );
		$this->response->headers->set( 'Content-Type', 'application/json' );

		$this->response
			->setContent(
				json_encode(
					array(
						'success' => true,
					)
				)
			)
			->setStatusCode( Response::HTTP_OK )
			->send();

		exit;
	}

	public function getStartingPoints() {
		MczrAccess::isAPIAuthorized();

		$this->response->headers->set( 'Access-Control-Allow-Origin', '*' );
		$this->response->headers->set( 'Content-Type', 'application/json' );
		$vars = array(
			'success' => true,
			'data'    => array(),
		);

		$args     = array(
			'type' => 'mczr',
		);
		$products = wc_get_products( $args );

		foreach ( $products as $product ) {
			array_push(
				$vars['data'],
				array(
					'startingPointId' => get_post_meta( $product->id, 'mczrStartingPoint', true ),
					'active'          => 'publish' === $product->status,
				)
			);
		}

		$this->response
			->setContent( json_encode( $vars ) )
			->setStatusCode( Response::HTTP_OK )
			->send();

		exit;
	}

	public function getStartingPoint( $request ) {
		MczrAccess::isAPIAuthorized();

		$this->response->headers->set( 'Access-Control-Allow-Origin', '*' );
		$this->response->headers->set( 'Content-Type', 'application/json' );

		$startingPointId = $request->get_param( 'id' );

		$args = array(
			'type'              => 'mczr',
			'mczrStartingPoint' => $startingPointId
		);

		$products = new \WP_Query(
			array(
				'post_type'      => array( 'product' ),
				'posts_per_page' => 1,
				'meta_query'     => array(
					array(
						'key'     => 'mczrStartingPoint',
						'value'   => $startingPointId,
						'compare' => 'IS',
					),
				),
			)
			);

		if ( $products->have_posts() ) {
			$productId = $products->post->ID;
			$product   = wc_get_product( $productId );
			$vars = array(
				'success' => true,
				'data'    => array(
					'startingPointId' => get_post_meta( $product->id, 'mczrStartingPoint', true ),
					'active'          => 'publish' === $product->status,
				)
			);
			$this->response
			->setContent( json_encode( $vars ) )
			->setStatusCode( Response::HTTP_OK )
			->send();
		} else {
			$this
				->response
				->setContent(
					json_encode(
						array(
							'success' => false,
							'message' => 'Product not found.',
							'data'    => array(
								'startingPointId' => $startingPointId,
							),
						)
					)
				)
				->setStatusCode( Response::HTTP_NOT_FOUND )
				->send();
		}

		exit;
	}

	public function createStartingPoint() {
		MczrAccess::isAPIAuthorized();

		$this->response->headers->set( 'Access-Control-Allow-Origin', '*' );
		$this->response->headers->set( 'Content-Type', 'application/json' );

		$content = $this->request->getContent();
		$datas   = json_decode( $content, true );

		$startingPointId    = $datas['startingPointId'];
		$startingPointName  = $datas['startingPointName'];
		$price              = $datas['price'];
		if (isset($datas['startingPointImage'])) {
			$startingPointImage = $datas['startingPointImage'];
		}

		$productId = $this->mczrProductTypeController->create( $startingPointId, $startingPointName, $price );

		$this->mczrProductTypeController->attachProductThumbnail( $productId, $startingPointImage );

		$vars = array(
			'success' => true,
			'data'    => array(
				'startingPointId' => $startingPointId,
				'active'          => true,
			),
		);

		$this->response
			->setContent( json_encode( $vars ) )
			->setStatusCode( Response::HTTP_OK )
			->send();

		exit;
	}

	public function updateStartingPoint( $request ) {
		MczrAccess::isAPIAuthorized();

		$this->response->headers->set( 'Access-Control-Allow-Origin', '*' );
		$this->response->headers->set( 'Content-Type', 'application/json' );

		$startingPointId = $request->get_param( 'id' );

		$content = $this->request->getContent();
		$datas   = json_decode( $content, true );

		$startingPointName = isset($datas['startingPointName']) ? $datas['startingPointName'] : null;
		$price             = isset($datas['price']) ? $datas['price'] : null;

		if ($startingPointName == null && $price == null) {
			$vars = array(
				'success' => true,
				'data'    => array(
					'startingPointId' => $startingPointId,
				),
			);

			$this->response
			->setContent( json_encode( $vars ) )
			->setStatusCode( Response::HTTP_OK )
			->send();

			exit;
		}

		$updatedProductId = $this->mczrProductTypeController->update( $startingPointId, $startingPointName, $price );

		if ( !$updatedProductId ) {
			$this->response
				->setContent(
					json_encode(
						array(
							'success' => false,
							'message' => 'Product not found.',
							'data'    => array(
								'startingPointId' => $startingPointId,
							),
						)
					)
				)
				->setStatusCode( Response::HTTP_NOT_FOUND )
				->send();
		} else {
			$vars = array(
				'success' => true,
				'data'    => $updatedProductId
			);

			$this->response
				->setContent( json_encode( $vars ) )
				->setStatusCode( Response::HTTP_OK )
				->send();
		}

		exit;
	}

	public function removeStartingPoint( $request ) {
		$this->response->headers->set( 'Access-Control-Allow-Origin', '*' );
		$this->response->headers->set( 'Content-Type', 'application/json' );
		$this->response->setStatusCode( Response::HTTP_INTERNAL_SERVER_ERROR );

		$startingPointId = $request->get_param( 'id' );

		$wasDeleted = $this->mczrProductTypeController->delete( $startingPointId );

		if ( ! $wasDeleted ) {
			$this->response
				->setContent(
					json_encode(
						array(
							'success' => false,
							'message' => 'Product not found.',
							'data'    => array(
								'startingPointId' => $startingPointId,
							),
						)
					)
				)
				->setStatusCode( Response::HTTP_NOT_FOUND )
				->send();
			return;
		}

		$vars = array(
			'success' => true,
			'data'    => array(
				'deleted' => $wasDeleted ? $startingPointId : null,
			),
		);

		$this->response
			->setContent( json_encode( $vars ) )
			->setStatusCode( Response::HTTP_OK )
			->send();

		exit;
	}

	public function reconcileOrders() {
		MczrAccess::isAPIAuthorized();

		$this->response->headers->set( 'Access-Control-Allow-Origin', '*' );
		$this->response->headers->set( 'Content-Type', 'application/json' );
		$this->response->setStatusCode( Response::HTTP_INTERNAL_SERVER_ERROR );

		$content = $this->request->getContent();
		$datas   = json_decode( $content, true );

		$fromDate = $datas['fromDate'];

		$kickflipOrders = $this->mczrOrderController->getKickflipOrders( $fromDate );

		$vars = array(
			'success' => true,
			'data'    => $kickflipOrders
		);

		$this->response
			->setContent( json_encode( $vars ) )
			->setStatusCode( Response::HTTP_OK )
			->send();

		exit;
	}

	public function shopConnect() {
		MczrAccess::isAPIAuthorized();

		$this->response->headers->set( 'Access-Control-Allow-Origin', '*' );
		$this->response->headers->set( 'Content-Type', 'application/json' );
		$vars = array(
			'success' => true,
			'data'    => array(),
		);

		$content = $this->request->getContent();
		if ( ! $content || empty( json_decode( $content, true ) ) ) {
			$this->response->setContent( json_encode( $vars ) )->send();
			exit;
		}

		$datas             = json_decode( $content, true );
		$brand             = $datas['brand'];
		$apiToken          = $datas['token'];
		$customizerBaseUrl = $datas['customizerUrl'];

		$vars['success'] = true;

		$this->settings->update( array( 'brand' => $brand ) );
		$this->settings->update( array( 'apiToken' => $apiToken ) );
		$this->settings->update( array( 'customizerBaseUrl' => $customizerBaseUrl ) );

		$this->mczrConnect->connect( $brand );

		$this->response
			->setContent( json_encode( $vars ) )
			->setStatusCode( Response::HTTP_OK )
			->send();

		exit;
	}
}
