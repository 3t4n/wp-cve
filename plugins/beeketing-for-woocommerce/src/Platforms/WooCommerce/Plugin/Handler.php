<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/12/18
 * Time: 6:43 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Plugin;

use BeeketingConnect_beeketing_woocommerce\Common\Constants as CommonConstants;
use BeeketingConnect_beeketing_woocommerce\Common\Data\CommonHelper;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Exception\EntityNotFoundException;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Cart;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Collect;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Collection;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Customer;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Order;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Product;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Variant;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Helper;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Handler
 * @property Loader loader
 * @package BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Plugin
 *
 * Handle all requests need the plugin return data
 */
class Handler
{

    /**
     * Handler constructor.
     * @param Loader $loader
     */
    public function __construct($loader)
    {
        $this->loader = $loader;
    }

    /**
     * @var Request
     */
    private $request;

    /**
     * @return Request
     */
    public function getRequest()
    {
        if (!$this->request) {
            $this->request = Request::createFromGlobals();
        }

        return $this->request;
    }

    /**
     * Setup ajax handlers
     */
    public function setupAjax()
    {
        // Save api key after verify account
        add_action('wp_ajax_beeketingwoocommerce_verify_account_callback', array($this, 'verifyAccountHandler'));
    }

    /**
     * Setup to handle all requests, don't overuse, add complex logic
     */
    public function setupAllRequests()
    {
        // Request callback
        $requestCallback = function () {
            // Handle resource when $_GET['resource'] existed
            if (isset($_GET['resource'])) {
                $this->resourceHandler($_GET['resource']);
            }
        };

        // Init handle request
        if (Helper::isWooCommerceActive()) {
            add_action('woocommerce_cart_loaded_from_session', $requestCallback);
        } else {
            add_action('wp_loaded', $requestCallback);
        }
    }

    /**
     * Handle after login account to save api key and other params
     */
    public function verifyAccountHandler()
    {
        // If not api key
        if (!isset($_POST['api_key']) || !isset($_POST['user_id']) || !isset($_POST['shop_id']) || !isset($_POST['auto_login_token'])) {
            wp_send_json_error(array(
                'error' => 'Missing data in post content'
            ));
            return;
        }

        $loader = $this->loader;
        $apiKey = sanitize_text_field($_POST['api_key']);
        $userId = sanitize_text_field($_POST['user_id']);
        $shopId = sanitize_text_field($_POST['shop_id']);
        $autoLoginToken = sanitize_text_field($_POST['auto_login_token']);
        if (!$apiKey || !$userId || !$shopId || !$autoLoginToken) {
            wp_send_json_error(array(
                'error' => 'Posted data is empty in post content'
            ));
            return;
        }

        // Set api key for future request
        $loader->setApiKey($apiKey);

        // Update shop info
        $params = array(
            'absolute_path' => CommonHelper::parseAbsolutePath(get_home_url()),
            'currency' => Helper::getCurrency(),
            'currency_format' => Helper::getCurrencyFormat(),
        );
        if (!$loader->beeketingAPI->updateShopInfo($params)) {
            $this->responseError('Failed to update shop info to Beeketing API');
            exit;
        }

        $loader->dataManager->settingManager->updateMany(array(
            CommonConstants::SETTING_API_KEY => $apiKey,
            CommonConstants::SETTING_USER_ID => $userId,
            CommonConstants::SETTING_SHOP_ID => $shopId,
            CommonConstants::SETTING_AUTO_LOGIN_TOKEN => $autoLoginToken,
        ));

        wp_send_json_success(array(
            'api_key' => $apiKey,
        ));
    }

    /**
     * Handle ?resource=* for file sync, cart process, inventory change, etc
     * @param $resource
     * @throws \Exception
     */
    public function resourceHandler($resource)
    {
        // Print debug
        if ($resource === 'beeketing_debug') {
            $this->printDebug();
            exit();
        }

        $loader = $this->loader;

        // Authenticate and routing to correct handler
        $request = $this->getRequest();

        // If this is authenticated via access token
        if ($requestApiKey = $request->headers->get(CommonConstants::VALIDATE_HEADER_API_KEY)) {
            // ~> allow for all
            $apiKey = $loader->dataManager->settingManager->get(CommonConstants::SETTING_API_KEY);
            if ($apiKey !== $requestApiKey) {
                $this->responseUnauthorized();
                exit;
            }

            $loader->dataManager->resourceManager->allowedAccess = array(Cart::$MODEL);

        } else {
            // Get request access token
            if (!$requestAccessToken = $request->headers->get(CommonConstants::VALIDATE_HEADER_ACCESS_TOKEN)) {
                $requestAccessToken = $request->query->get('access_token');
            }

            if ($requestAccessToken) {
                // Else if this is authenticated via API Key
                $accessToken = $loader->dataManager->settingManager->get(CommonConstants::SETTING_ACCESS_TOKEN);

                if ($accessToken !== $requestAccessToken) {
                    $this->responseUnauthorized();
                    exit;
                }

                // ~> only allow cart model
                $loader->dataManager->resourceManager->allowedAccess = array('all');
            } else {
                // Else no valid key provided
                // show error
                $this->responseUnauthorized('API key or access token is not provided');
                exit;
            }
        }

        // Special request for settings (get_setting, put_setting) and post_install_app
        $specialResources = array(
            'setting',
            'install_app',
            'file_sync',
            'test_resource',
        );
        if (in_array($resource, $specialResources)) {
            $this->handleInPluginRequests($resource, $request);
            exit;
        }

        // Handle request using resource manager (common for all platforms)
        list($modelName, $method) = $this->getModelAndMethod($resource, $request->getMethod(), $request);

        // If not dev env, show error in json nicely
        if (BEEKETINGWOOCOMMERCE_ENVIRONMENT !== 'dev') {
            try {
                $loader->dataManager->resourceManager->handleRequest($modelName, $method, $request);
                exit;
            } catch (EntityNotFoundException $e) {
                $this->responseError($e->getMessage(), 404);
                exit;
            } catch (\Exception $e) {
                $this->responseError($e->getMessage());

                // Send sentry error if 500
                $loader->ravenClient->captureException($e);
                exit;
            }
        } else {
            $loader->dataManager->resourceManager->handleRequest($modelName, $method, $request);
            exit;
        }
    }

    /**
     * Get model name and method from current request to call into resource manager
     * This code is unusual but it is fallback for old route system of woocommerce plugin
     *
     * @param $resource
     * @param $requestMethod
     * @param Request $request
     * @return array
     */
    protected function getModelAndMethod($resource, $requestMethod, Request $request)
    {
        // By default return default value
        $modelName = $resource;
        $method = isset($_GET['method']) ? $_GET['method'] : $requestMethod;

        // get_products, put_products
        if ($resource === 'products') {
            $modelName = Product::$MODEL;

            // Method is getMany if resource id is not provided
            if ($requestMethod === Request::METHOD_GET && !$request->get('resource_id')) {
                $method = 'getMany';
            }
        } else if ($resource === 'products_count' && $requestMethod === Request::METHOD_GET) {
            // get_products_count
            $modelName = Product::$MODEL;
            $method = 'count';
        } else if ($resource === 'customers') {
            // get_customers
            $modelName = Customer::$MODEL;

            // Method is getMany if resource id is not provided
            if ($requestMethod === Request::METHOD_GET && !$request->get('resource_id')) {
                $method = 'getMany';
            }
        } else if ($resource === 'customers_count' && $requestMethod === Request::METHOD_GET) {
            // get_customers_count
            $modelName = Customer::$MODEL;
            $method = 'count';
        } else if ($resource === 'orders') {
            // get_orders
            $modelName = Order::$MODEL;

            // Method is getMany if resource id is not provided
            if ($requestMethod === Request::METHOD_GET && !$request->get('resource_id')) {
                $method = 'getMany';
            }
        } else if ($resource === 'orders_count' && $requestMethod === Request::METHOD_GET) {
            // get_orders_count
            $modelName = Order::$MODEL;
            $method = 'count';
        } else if ($resource === 'variants' || $resource === 'products_variants') {
            // get_variants, put_variants, post_products_variants, delete_products_variants
            $modelName = Variant::$MODEL;
        } else if ($resource === 'collections') {
            // get_collections
            $modelName = Collection::$MODEL;

            // Method is getMany if resource id is not provided
            if ($requestMethod === Request::METHOD_GET && !$request->get('resource_id')) {
                $method = 'getMany';
            }
        } else if ($resource === 'collections_count') {
            // get_collections_count
            $modelName = Collection::$MODEL;
            $method = 'count';
        } else if ($resource === 'collects') {
            // get_collects
            $modelName = Collect::$MODEL;
            if ($requestMethod === Request::METHOD_GET) {
                // Always get many methods
                $method = 'getMany';
            }
        } else if ($resource === 'collects_count') {
            // get_collections_count
            $modelName = Collect::$MODEL;
            $method = 'count';
        }

        return array(
            $modelName, $method
        );
    }

    /**
     * Response unauthorized
     * @param null $message
     */
    private function responseUnauthorized($message = null)
    {
        if (!$message) {
            $message = 'unauthorized wrong api key or access token';
        }

        $response = new JsonResponse(array(
            'error' => $message,
        ), 401);

        $response->send();
        exit;
    }

    /**
     * Response json
     * @param array $jsonData
     */
    private function response($jsonData)
    {
        $response = new JsonResponse($jsonData);
        $response->send();
        exit;
    }

    /**
     * Response error
     * @param null $message
     * @param int $status
     */
    private function responseError($message, $status = 500)
    {
        $response = new JsonResponse(array(
            'error' => $message,
        ), $status);

        $response->send();
        exit;
    }

    /**
     * Handle special request only used inside this plugin
     * @param $resource
     * @param Request $request
     */
    private function handleInPluginRequests($resource, $request)
    {
        $requestMethod = strtoupper($request->getMethod());

        $loader = $this->loader;
        if ($resource === 'setting') {
            // get_setting
            if ($requestMethod === Request::METHOD_GET) {
                $this->response(array(
                    'setting' => $loader->dataManager->settingManager->getAll(),
                ));
                exit();
            } else if ($requestMethod === Request::METHOD_PUT) {
                // put_setting
                // Validate
                if (!$request->getContent()) {
                    $this->responseError('Setting data is not valid');
                    exit();
                }

                $content = json_decode($request->getContent(), true);
                if (!isset($content['setting'])) {
                    if (!$request->getContent()) {
                        $this->responseError('Setting data is not valid - 2');
                        exit();
                    }
                }

                // Update setting
                foreach ($content['setting'] as $setting => $value) {
                    $loader->dataManager->settingManager->update($setting, $value);
                }

                $this->response(array(
                    'setting' => $loader->dataManager->settingManager->getAll(),
                ));
                exit();
            }
        } else if ($resource === 'install_app' && $requestMethod === Request::METHOD_POST) {
            // post_install_app
            // Do nothing as new flow skip this logic
            $this->response(array(
                'setting' => array(
                    'access_token' => $loader->dataManager->settingManager->getAccessToken(),
                ),
            ));
            exit();
        } else if ($resource === 'file_sync') {
            // *_file_sync
            // No longer support
            $this->responseError('No longer support file_sync');
            exit();
        } else if ($resource === 'test_resource') {
            // *_file_sync
            // No longer support
            $this->response(array(
                'success' => true,
                'message' => 'message received',
            ));
            exit();
        }

        $this->responseError('Cant handle this request, missing handler');
        exit();
    }

    /**
     * Handle uninstall plugin
     * @param string
     */
    public function uninstallPlugin($platform)
    {
        // Delete all settings only, don't cancel any apps, user need to cancel them manually
        if($this->loader->getAppsInstalledCount() == 1) {
            $this->loader->dataManager->settingManager->deleteAll();
        }

        $this->loader->beeketingAPI->trackEvent('Connect - Delete plugin', [
            'platform' => $platform
        ]);
    }

    /**
     * Print debug
     */
    public function printDebug()
    {
        $loader = $this->loader;

        if (Helper::isWooCommerceActive()) {
            global $woocommerce;
        } else {
            $woocommerce = false;
        }

        $result = array(
            'wc_version' => $woocommerce ? $woocommerce->version : false,
            'plugin_version' => $loader->dataManager->getPluginVersion(),
            'php_version' => phpversion(),
            'beeketing_plugin_connected' => $loader->dataManager->settingManager->get(CommonConstants::SETTING_API_KEY) ? true : false,
            'beeketing_shop_id' => $loader->dataManager->settingManager->get(CommonConstants::SETTING_SHOP_ID),
        );

        $this->response($result);
        exit();
    }

    public function deactivatePluginHook($event, $platform)
    {
        $this->loader->beeketingAPI->trackEvent($event, [
            'platform' => $platform
        ]);
    }

    public function activatePluginHook($event, $platform)
    {
        $this->loader->beeketingAPI->trackEvent($event, [
            'platform' => $platform
        ]);
    }

    public function uninstallHook($event, $pluginName)
    {
        $this->loader->beeketingAPI->trackEvent($event, [
            'plugin' => $pluginName
        ]);
    }
}
