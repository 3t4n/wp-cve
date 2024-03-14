<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Enum\MipWcConnector;
use WcMipConnector\Service\DirectoryService;

class ConfigurationOptionManager
{
    private const API_KEY_OPTION = 'WC_MIPCONNECTOR_BIGBUY_API_KEY';
    private const UPDATE_PRODUCT_OPTION = 'WC_MIPCONNECTOR_ENABLE_UPDATE_PRODUCT_URL';
    private const WC_VERSION = 'WC_MIPCONNECTOR_VERSION';
    private const ACCESS_TOKEN = 'WC_MIPCONNECTOR_ACCESS_TOKEN';
    private const SECRET_KEY = 'WC_MIPCONNECTOR_SECRET_KEY';
    private const SEND_EMAIL = 'WC_MIPCONNECTOR_SEND_EMAIL';
    private const ACTIVE_TAG = 'WC_MIPCONNECTOR_TAG_ACTIVE';
    private const TAG_NAME = 'WC_MIPCONNECTOR_TAG_NAME';
    private const BRAND_ID = 'WC_MIPCONNECTOR_BRAND_ID';
    private const SUPPLIER_ID = 'WC_MIPCONNECTOR_SUPPLIER_ID';
    private const CARRIER_OPTION = 'WC_MIPCONNECTOR_BIGBUY_CARRIER_OPTION';
    private const PRODUCT_OPTION = 'WC_MIPCONNECTOR_PRODUCT_DELETE_OPTION';
    private const LAST_VERSION_CHECK = 'WC_MIPCONNECTOR_LAST_VERSION_CHECK';
    private const LAST_API_CARRIER_UPDATE = 'WC_MIPCONNECTOR_CARRIER_LAST_API_UPDATE';
    private const WOOCOMMERCE_BRAND_PLUGIN_FILE = 'woocommerce-brands/woocommerce-brands.php';
    private const LAST_STOCK_UPDATE = 'WC_MIPCONNECTOR_LAST_STOCK_UPDATE';
    private const MANAGE_STOCK_OPTION = 'woocommerce_manage_stock';
    private const PUBLICATION_OPTIONS = 'WC_MIPCONNECTOR_PUBLICATION_OPTIONS';
    private const USER_ID = 'WC_MIPCONNECTOR_USER_ID';
    private const PERMALINK = 'permalink_structure';
    private const PERMALINK_DEFAULT_VALUE = '/%postname%/';
    private const CATEGORY_IMAGE_DELETE = 'WC_MIPCONNECTOR_CATEGORY_IMAGE_DELETE';

    /**
     * @return string
     */
    public static function getPluginDatabaseVersion(): string
    {
        return get_option(self::WC_VERSION);
    }

    /**
     * @param string $wcVersion
     * @return bool
     */
    public static function setPluginDatabaseVersion(string $wcVersion): bool
    {
        return update_option(self::WC_VERSION, $wcVersion);
    }

    /**
     * @return bool
     */
    public static function existsPluginDatabaseWcVersion(): bool
    {
        return get_option(self::WC_VERSION) !== null;
    }

    /**
     * @return string
     */
    public static function getAccessToken(): string
    {
        return get_option(self::ACCESS_TOKEN);
    }

    public static function getLastModuleUpdate(): ?string
    {
        return get_option(self::LAST_VERSION_CHECK);
    }

    public static function getLastCarrierUpdate(): ?string
    {
        return get_option(self::LAST_API_CARRIER_UPDATE);
    }

    public static function setLastCarrierUpdate(string $date = ''): bool
    {
        return update_option(self::LAST_API_CARRIER_UPDATE, $date);
    }

    /**
     * @param string $accessToken
     * @return bool
     */
    public static function setAccessToken(string $accessToken = ''): bool
    {
        return update_option(self::ACCESS_TOKEN, $accessToken);
    }

    /**
     * @return bool
     */
    public static function existsAccessToken(): bool
    {
        return get_option(self::ACCESS_TOKEN) !== null;
    }

    /**
     * @return string
     */
    public static function getSecretKey(): string
    {
        return get_option(self::SECRET_KEY);
    }

    /**
     * @param string $secretKey
     * @return bool
     */
    public static function setSecretKey(string $secretKey = ''): bool
    {
        return update_option(self::SECRET_KEY, $secretKey);
    }

    /**
     * @return bool
     */
    public static function existsSecretKey(): bool
    {
        return get_option(self::SECRET_KEY) !== null;
    }

    /**
     * @return bool
     */
    public static function getSendEmail(): bool
    {
        return get_option(self::SEND_EMAIL);
    }

    /**
     * @param bool $sendEmail
     * @return bool
     */
    public static function setSendEmail(bool $sendEmail = true): bool
    {
        return update_option(self::SEND_EMAIL, (int)$sendEmail);
    }

    /**
     * @return bool
     */
    public static function existsSendEmail(): bool
    {
        return get_option(self::SEND_EMAIL) !== null;
    }

    /**
     * @return bool
     */
    public static function getActiveTag(): bool
    {
        return get_option(self::ACTIVE_TAG);
    }

    /**
     * @param bool $activeTag
     * @return bool
     */
    public static function setActiveTag(bool $activeTag = true): bool
    {
        return update_option(self::ACTIVE_TAG, (int)$activeTag);
    }

    /**
     * @return bool
     */
    public static function existsActiveTag(): bool
    {
        return get_option(self::ACTIVE_TAG) !== null;
    }

    /**
     * @return string
     */
    public static function getTagName(): string
    {
        return get_option(self::TAG_NAME);
    }

    /**
     * @param string $tagName
     * @return bool
     */
    public static function setTagName(string $tagName = MipWcConnector::WC_MIPCONNECTOR_TAG_NAME): bool
    {
        return update_option(self::TAG_NAME, $tagName);
    }

    /**
     * @return bool
     */
    public static function existsTagName(): bool
    {
        return get_option(self::TAG_NAME) !== null;
    }

    /**
     * @return int
     */
    public static function getBrandId(): int
    {
        return (int)get_option(self::BRAND_ID);
    }

    /**
     * @return \stdClass|null
     */
    public static function getBrandAttribute(): ?\stdClass
    {
        $brandId = (int)get_option(self::BRAND_ID);

        return wc_get_attribute($brandId);
    }

    /**
     * @param int|null $brandId
     * @return bool
     */
    public static function setBrandId(int $brandId = null): bool
    {
        return update_option(self::BRAND_ID, $brandId);
    }

    /**
     * @return bool
     */
    public static function existsBrandId(): bool
    {
        return get_option(self::BRAND_ID) !== null;
    }

    /**
     * @return int
     */
    public static function getSupplierId(): int
    {
        return (int)get_option(self::SUPPLIER_ID);
    }

    /**
     * @param int|null $supplierId
     * @return bool
     */
    public static function setSupplierId(int $supplierId = null): bool
    {
        return update_option(self::SUPPLIER_ID, $supplierId);
    }

    /**
     * @return bool
     */
    public static function existsSupplierId(): bool
    {
        return get_option(self::SUPPLIER_ID) !== null;
    }

    /**
     * @return bool
     */
    public static function getCarrierOption(): bool
    {
        return get_option(self::CARRIER_OPTION);
    }

    /**
     * @param bool $carrierOption
     * @return bool
     */
    public static function setCarrierOption(bool $carrierOption = false): bool
    {
        return update_option(self::CARRIER_OPTION, (int)$carrierOption);
    }

    /**
     * @return bool
     */
    public static function existsCarrierOption(): bool
    {
        return get_option(self::CARRIER_OPTION) !== null;
    }

    /**
     * @return bool
     */
    public static function getProductOption(): bool
    {
        return get_option(self::PRODUCT_OPTION);
    }

    /**
     * @param bool $productOption
     * @return bool
     */
    public static function setProductOption(bool $productOption = true): bool
    {
        return update_option(self::PRODUCT_OPTION, (int)$productOption);
    }

    /**
     * @return bool
     */
    public static function existsProductOption(): bool
    {
        return get_option(self::PRODUCT_OPTION) !== null;
    }

    /**
     * @return string
     */
    public static function getApiKey(): string
    {
        return get_option(self::API_KEY_OPTION);
    }

    /**
     * @param string $apiKey
     * @return bool
     */
    public static function setApiKey(string $apiKey = ''): bool
    {
        return update_option(self::API_KEY_OPTION, $apiKey);
    }

    /**
     * @return bool
     */
    public static function existsApiKey(): bool
    {
        return get_option(self::API_KEY_OPTION) !== null;
    }

    /**
     * @return bool
     */
    public static function getUpdateProductUrl(): bool
    {
        return get_option(self::UPDATE_PRODUCT_OPTION);
    }

    /**
     * @param bool $googleShopping
     * @return bool
     */
    public static function setUpdateProductUrl(bool $googleShopping = false): bool
    {
        return update_option(self::UPDATE_PRODUCT_OPTION, (int)$googleShopping);
    }

    /**
     * @return bool
     */
    public static function existsUpdateProductUrl(): bool
    {
        return get_option(self::UPDATE_PRODUCT_OPTION) !== null;
    }

    /**
     * @return string
     */
    public static function getOptionBySiteUrl(): string
    {
        return home_url();
    }

    /**
     * @return bool
     */
    public static function isWoocommerceBrandPluginEnable(): bool
    {
        return is_plugin_active(self::WOOCOMMERCE_BRAND_PLUGIN_FILE);
    }

    /**
     * @return bool
     */
    public static function isPluginEnable(): bool
    {
        return is_plugin_active(MipWcConnector::MODULE_NAME.'/WcMipconnector.php');
    }

    /**
     * @return bool
     */
    public static function enablePlugin(): bool
    {
        $hasBeenActivated = activate_plugin(MipWcConnector::MODULE_NAME.'/WcMipconnector.php');
        if (is_wp_error($hasBeenActivated)) {
            return false;
        }

        return true;
    }

    public static function getPluginFilesVersion(): string
    {
        $moduleInfo = get_plugin_data(DirectoryService::getInstance()->getModuleDir().'/WcMipconnector.php');

        return $moduleInfo['Version'];
    }

    /**
     * @param string $dateTime
     * @return bool
     */
    public static function setLastStockUpdate(string $dateTime = ''): bool
    {
        return update_option(self::LAST_STOCK_UPDATE, $dateTime);
    }

    /**
     * @return string
     */
    public static function getLastStockUpdate(): string
    {
        return get_option(self::LAST_STOCK_UPDATE);
    }

    /**
     * @return bool
     */
    public static function existsLastStockUpdate(): bool
    {
        return get_option(self::LAST_STOCK_UPDATE) !== null;
    }

    /**
     * @return bool
     */
    public static function existsLastCarrierUpdate(): bool
    {
        return get_option(self::LAST_API_CARRIER_UPDATE) !== null;
    }

    /**
     * @return bool
     */
    public static function existsPermalink(): bool
    {
        return get_option(self::PERMALINK) !== '';
    }

    /**
     * @return bool
     */
    public static function setDefaultPermalink(): bool
    {
        return update_option(self::PERMALINK, self::PERMALINK_DEFAULT_VALUE);
    }

    /**
     * @return bool
     */
    public static function setManageStockOption(): bool
    {
        return update_option(self::MANAGE_STOCK_OPTION, 'yes');
    }

    /**
     * @param string $publicationOptions
     * @return bool
     */
    public static function setPublicationOptions(string $publicationOptions): bool
    {
        return update_option(self::PUBLICATION_OPTIONS, $publicationOptions);
    }

    /**
     * @return string
     */
    public static function getPublicationOptions(): string
    {
        return get_option(self::PUBLICATION_OPTIONS);
    }

    /**
     * @param string $categoryImageDeleteDate
     * @return bool
     */
    public static function setCategoryImageDeleteDate(string $categoryImageDeleteDate): bool
    {
        return update_option(self::CATEGORY_IMAGE_DELETE, $categoryImageDeleteDate);
    }

    /**
     * @return string
     */
    public static function getCategoryImageDeleteDate(): string
    {
        return get_option(self::CATEGORY_IMAGE_DELETE);
    }

    /**
     * @param int|null $userId
     * @return bool
     */
    public static function setUserId(?int $userId = null): bool
    {
        return update_option(self::USER_ID, $userId);
    }

    /**
     * @return int|null
     */
    public static function getUserId(): ?int
    {
        $result = get_option(self::USER_ID);

        if (empty($result)) {
            return null;
        }

        return (int)$result;
    }

    /**
     * @return bool
     */
    public static function existsUserId(): bool
    {
        return get_option(self::USER_ID) !== null;
    }
}