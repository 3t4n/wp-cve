<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/15/18
 */

namespace BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Plugin;


use BeeketingConnect_beeketing_woocommerce\Common\Webhook;
use BeeketingConnect_beeketing_woocommerce\Common\Constants as CommonConstants;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Constants;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Data\ShopManager;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\DataManager\DataManager;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\DataManager\QueryHelper;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Helper;

class WebhookHandler
{
    const BEEKETING_OLD_CATEGORIES_META_KEY = '_beeketing_old_categories';

    /**
     * @var DataManager
     */
    private $dataManager;

    /**
     * @var Webhook
     */
    private $webhook;

    /**
     * @var int
     */
    private $shopId;

    /**
     * WebhookHandler constructor.
     * @param DataManager $dataManager
     * @param Webhook $webhook
     * @param $shopId
     */
    public function __construct(DataManager $dataManager, Webhook $webhook, $shopId)
    {
        $this->dataManager = $dataManager;
        $this->webhook = $webhook;
        $this->shopId = $shopId;
    }

    /**
     * Update product webhook
     *
     * @param $postId
     * @throws \Exception
     */
    public function updateProductWebhook($postId)
    {
        $postType = get_post_type($postId);

        // If post id is variant id
        if ($postType === 'product_variation') {
            $variant = wc_get_product($postId);
            if (!$variant) {
                return;
            }
            $postId = Helper::isWc3() ? $variant->get_parent_id() : $variant->parent_id;
            $postType = get_post_type($postId);
        }

        // If its not a product / if its trashed
        if (
            'product' !== $postType ||
            in_array(get_post_status($postId), array('trash', 'auto-draft'))
        ) {
            return;
        }

        // Check product id not in exclude product ids
        global $wpdb;
        $sql = QueryHelper::getExcludeProductsId();
        if ($sql) {
            $excludeProductsId = $wpdb->get_col($sql);
            if (in_array($postId, $excludeProductsId)) {
                return;
            }
        }


        // Send webhook
        $content = $this->dataManager->resourceManager->productManager->get(array(
            'resource_id' => $postId,
        ));
        $this->webhook->send(Webhook::PRODUCT_UPDATE, $this->shopId, Constants::PLATFORM_CODE, $content);
    }

    /**
     * Delete product webhook
     *
     * @param $postId
     * @throws \Exception
     */
    public function deleteProductWebhook($postId)
    {
        // If its not a product
        if ('product' !== get_post_type($postId)) {
            return;
        }

        $this->webhook->send(Webhook::PRODUCT_DELETE, $this->shopId, Constants::PLATFORM_CODE, array(
            'id' => $postId,
        ));
    }

    /**
     * Update order webhook
     *
     * @param $orderId
     * @throws \Exception
     */
    public function updateOrderWebhook($orderId)
    {
        // Validate order
        $order = wc_get_order($orderId);
        if (!$order || in_array($order->get_status(), array('draft', 'trash', 'auto-draft'))) {
            return;
        }

        // Update and clear cart token
        if (isset($_COOKIE[CommonConstants::CART_TOKEN_KEY])) {
            update_post_meta($orderId, CommonConstants::CART_TOKEN_KEY, $_COOKIE[CommonConstants::CART_TOKEN_KEY]);
            // Delete cart token
            // To ignore warning
            @setcookie(CommonConstants::CART_TOKEN_KEY, null, time() - CommonConstants::COOKIE_CART_TOKEN_LIFE_TIME + 1, '/');
        }

        $content = $this->dataManager->resourceManager->orderManager->get(array(
            'resource_id' => $orderId,
        ));
        $this->webhook->send(Webhook::ORDER_UPDATE, $this->shopId, Constants::PLATFORM_CODE, $content);

        if ($order->get_user_id()) {
            $this->updateCustomerWebhook($order->get_user_id());
        }
    }

    /**
     * Create customer webhook
     *
     * @param $userId
     * @throws \Exception
     */
    public function createCustomerWebhook($userId)
    {
        $content = $this->dataManager->resourceManager->customerManager->get(array(
            'resource_id' => $userId,
        ));
        $this->webhook->send(Webhook::CUSTOMER_CREATE, $this->shopId, Constants::PLATFORM_CODE, $content);
    }

    /**
     * Update customer webhook
     *
     * @param $userId
     * @throws \Exception
     */
    public function updateCustomerWebhook($userId)
    {
        $content = $this->dataManager->resourceManager->customerManager->get(array(
            'resource_id' => $userId,
        ));
        $this->webhook->send(Webhook::CUSTOMER_UPDATE, $this->shopId, Constants::PLATFORM_CODE, $content);
    }

    /**
     * Delete customer webhook
     *
     * @param $userId
     * @throws \Exception
     */
    public function deleteCustomerWebhook($userId)
    {
        $this->webhook->send(Webhook::CUSTOMER_DELETE, $this->shopId, Constants::PLATFORM_CODE, array(
            'id' => $userId,
        ));
    }

    /**
     * Create collection webhook
     *
     * @param $termId
     * @param $ttId
     * @param $taxonomy
     * @throws \Exception
     */
    public function createCollectionWebhook($termId, $ttId, $taxonomy)
    {
        if ($taxonomy != 'product_cat') {
            return;
        }

        $content = $this->dataManager->resourceManager->collectionManager->get(array(
            'resource_id' => $termId,
        ));
        $this->webhook->send(Webhook::COLLECTION_CREATE, $this->shopId, Constants::PLATFORM_CODE, $content);
    }

    /**
     * Update collection webhook
     *
     * @param $termId
     * @param $ttId
     * @param $taxonomy
     * @throws \Exception
     */
    public function updateCollectionWebhook($termId, $ttId, $taxonomy)
    {
        if ($taxonomy != 'product_cat') {
            return;
        }

        $content = $this->dataManager->resourceManager->collectionManager->get(array(
            'resource_id' => $termId,
        ));
        $this->webhook->send(Webhook::COLLECTION_UPDATE, $this->shopId, Constants::PLATFORM_CODE, $content);
    }

    /**
     * Delete collection webhook
     * @param $termId
     * @param $ttId
     * @param $taxonomy
     * @throws \Exception
     */
    public function deleteCollectionWebhook($termId, $ttId, $taxonomy)
    {
        if ($taxonomy != 'product_cat') {
            return;
        }

        $this->webhook->send(Webhook::COLLECTION_DELETE, $this->shopId, Constants::PLATFORM_CODE, array(
            'id' => $termId,
        ));
    }

    /**
     * send data to beeketing server when user update woocomerce's general setting
     * WC: woocommerce
    */
    public function updateGeneralSettingsWC()
    {
        $shopManager = new ShopManager();
        $this->webhook->send(
            Webhook::UPDATE_SETTING_GENERAL,
            $this->shopId,
            Constants::PLATFORM_CODE,
            $shopManager->get()
        );
    }

    /**
     * send data to beeketing server when user update wordpress general setting
     * this function update site name only
     */
    public function updateWPSetting($option, $oldValue, $value)
    {
        if ('blogname' == $option && $oldValue != $value) {
            $shopManager = new ShopManager();
            $this->webhook->send(
                Webhook::UPDATE_SETTING_GENERAL,
                $this->shopId,
                Constants::PLATFORM_CODE,
                $shopManager->get()
            );
        }
    }
}
