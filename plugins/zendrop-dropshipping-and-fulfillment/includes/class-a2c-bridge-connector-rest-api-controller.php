<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

require_once( plugin_dir_path(dirname(__FILE__)) . '/bridge2cart/bridge.php' );

/**
 * REST API controller.
 *
 * @since 1.5.0
 */

class A2C_Bridge_Connector_V1_REST_API_Controller extends WP_REST_Controller {
  const HTTP_NO_CONTENT = '204';

  /**
   * Endpoint namespace.
   *
   * @var string
   */
  protected $namespace = 'a2c/v1';

  /**
   * Route base.
   *
   * @var string
   */
  protected $rest_base = 'bridge-action';

  /**
   * Post type.
   *
   * @var string
   */
  protected $post_type = 'shop_order';


  /**
   * Register the routes for bridge.
   */
  public function register_routes() {
    register_rest_route( $this->namespace, '/' . $this->rest_base, array(
      array(
        'methods'             => WP_REST_Server::ALLMETHODS,
        'callback'            => array( $this, 'action' ),
        'permission_callback' => array( $this, 'get_items_permissions_check' ),
        'args'                => $this->get_collection_params(),
      )
    ));
  }

  /**
   * Check permission.
   *
   * @param  WP_REST_Request $request Full details about the request.
   * @return WP_Error|boolean
   */
  public function get_items_permissions_check( $request ) {
    if (get_option(A2CBC_BRIDGE_IS_INSTALLED)) {
      $postParams = $request->get_body_params();

      if (isset($postParams['action']) && $postParams['action'] === 'checkbridge') {
        return true;
      }

      if (!defined('A2CBC_TOKEN')) {
        return false;
      } elseif ($token = $request->get_param('token')) {
        return $request->get_param('token') == A2CBC_TOKEN;
      } else {
        if (empty($postParams['a2c_sign'])) {
          return false;
        }

        $a2cSign = $postParams['a2c_sign'];
        unset($postParams['a2c_sign']);
        ksort($postParams, SORT_STRING);
        $resSign = hash_hmac('sha256', http_build_query($postParams), A2CBC_TOKEN);

        return $a2cSign === $resSign;
      }
    } else {
      return false;
    }
  }

  /**
   * @param WP_REST_Request $request Full details about the request.
   * @return WP_REST_Response
   */
  public function action(WP_REST_Request $request)
  {
    $response = new WP_REST_Response();
    $response->set_status(200);

    try {
      $adapter = new M1_Config_Adapter();
      $bridge = new M1_Bridge($adapter->create(), $request);
    } catch (\Exception $exception) {
      $response->set_data($exception->getMessage());
      $response->set_status(500);

      return $response;
    }

    $bridgeRes = $bridge->run();

    $res = !empty($bridgeRes) ? $bridgeRes : '';

    if ($res == self::HTTP_NO_CONTENT) {
      $response->set_status(self::HTTP_NO_CONTENT);
    }

    $response->set_data($res);

    return $response;
  }
}
