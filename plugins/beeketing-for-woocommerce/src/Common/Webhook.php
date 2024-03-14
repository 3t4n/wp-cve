<?php
/**
 * Plugin webhook topics
 *
 * @since      1.0.0
 * @author     Beeketing
 */

namespace BeeketingConnect_beeketing_woocommerce\Common;

use BeeketingConnect_beeketing_woocommerce\Common\Data\CommonHelper;
use Buzz\Message\Response;

class Webhook
{

    /* Webhook topics */
    const UNINSTALL = 'app/uninstalled';
    const ORDER_UPDATE = 'orders/updated';
    const PRODUCT_UPDATE = 'products/update';
    const PRODUCT_DELETE = 'products/delete';
    const COLLECTION_CREATE = 'collections/create';
    const COLLECTION_UPDATE = 'collections/update';
    const COLLECTION_DELETE = 'collections/delete';
    const CUSTOMER_CREATE = 'customers/create';
    const CUSTOMER_UPDATE = 'customers/update';
    const CUSTOMER_DELETE = 'customers/delete';
    const UPDATE_SETTING_GENERAL = 'shopinfo/update';

    private $webhookPath;
    private $webhookGoPath;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * Webhook constructor.
     * @param $webhookPath
     */
    public function __construct($webhookPath, $webhookGoPath = '')
    {
        $this->webhookPath = $webhookPath;
        $this->webhookGoPath = $webhookGoPath;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $path
     */
    public function setApiPath($path)
    {
        $this->webhookPath = $path;
    }

    /**
     * @param string $goPath
     */
    public function setGoApiPath($goPath)
    {
        $this->webhookGoPath = $goPath;
    }

    /**
     * Send request webhook
     *
     * @param $topic
     * @param $shopId
     * @param $platform
     * @param null $appCode
     * @param $content
     * @param array $headers
     * @return array|mixed
     * @throws \Exception
     */
    public function send($topic, $shopId, $platform, $content, $appCode = null, $headers = [])
    {
        // If all apps plugin
        if ($topic != Webhook::UNINSTALL && !$appCode) {
            // Use cbox app as appcode if not specified
            $appCode = 'coupon_box';
        } else {
            return false;
        }

        if (!$this->apiKey) {
            return false;
        }

        $headers = array_merge([
            'Content-Type' => 'application/json',
            'X-Beeketing-Topic' => $topic,
            'X-Beeketing-Plugin-Version' => 999, // Fixed value
            'X-Beeketing-Api-Key' => $this->apiKey,
        ], $headers);

        // Json encode array content
        $content = json_encode($content);

        // Url to request
        $url = $this->webhookPath . '/webhook/callback/' . $platform . '/' . $appCode . '/' . $shopId;

        $browser = CommonHelper::createBrowser();
        // Send request
        try {
            /** @var Response $response */
            $response = $browser->post($url, $headers, $content);

            // Render response
            if ($response->isOk()) {
                return json_decode($response->getContent(), true);
            }

            return $response->getContent();
        } catch (\Exception $e) {
            // Ignore all errors
            return null;
        }
    }

    /**
     * Send request webhook on go server
     *
     * @param $topic
     * @param $shopId
     * @param $platform
     * @param null $appCode
     * @param $content
     * @param array $headers
     * @return array|mixed
     * @throws \Exception
     */
    public function sendToGoServer($topic, $shopId, $platform, $content, $appCode = null, $headers = [])
    {
        // If all apps plugin
        if ($topic != Webhook::UNINSTALL && !$appCode) {
            // Use cbox app as appcode if not specified
            $appCode = 'coupon_box';
        } else {
            return false;
        }

        if (!$this->apiKey) {
            return false;
        }

        $headers = array_merge([
            'Content-Type' => 'application/json',
            'X-Beeketing-Topic' => $topic,
            'X-Beeketing-Plugin-Version' => 999, // Fixed value
            'X-Beeketing-Api-Key' => $this->apiKey,
        ], $headers);

        // Json encode array content
        $content = json_encode($content);

        // Url to request
        $url = $this->webhookGoPath . '/webhook/callback/' . $platform . '/' . $appCode . '/' . $shopId;
        $browser = CommonHelper::createBrowser();
        // Send request
        try {
            /** @var Response $response */
            $response = $browser->post($url, $headers, $content);

            // Render response
            if ($response->isOk()) {
                return json_decode($response->getContent(), true);
            }

            return $response->getContent();
        } catch (\Exception $e) {
            // Ignore all errors
            return null;
        }
    }
}
