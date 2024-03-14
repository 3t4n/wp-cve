<?php

namespace WcMipConnector\Controller;

defined('ABSPATH') || exit;

use Cocur\Slugify\Slugify;
use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Model\ShopData;
use WcMipConnector\Repository\WoocommerceApiRepository;
use WcMipConnector\View\Assets\Assets;
use WcMipConnector\View\OAuth\GrantAccess;
use WcMipConnector\View\OAuth\LoginForm;

class OAuthController
{
    private const ACCESS_TOKEN = 'access_token';
    private const SECRET_KEY = 'secret_key';
    private const SHOP_URL = 'shop_url';
    private const CALLBACK_URL = 'callback_url';
    private const API_KEY = 'api_key';
    private const SHOP_NAME = 'shop_name';
    private const SHOP = 'shop';

    private const AUTHORIZE_ROUTE = 'authorize';
    private const GRANT_ACCESS_ROUTE = 'grant-access';
    public const GRANT_ACCESS_ENDPOINT = '/'.ApiController::API_ENDPOINT.'/'.self::GRANT_ACCESS_ROUTE;
    public const AUTHORIZE_ENDPOINT = '/'.ApiController::API_ENDPOINT.'/'.self::AUTHORIZE_ROUTE;

    /** @var LoginForm  */
    protected $loginView;
    /** @var GrantAccess  */
    protected $grantAccessView;
    /** @var WoocommerceApiRepository */
    private $woocommerceApiRepository;
    /** @var Assets */
    private $assets;

    public function __construct()
    {
        $this->assets = new Assets();
        $this->loginView = new LoginForm();
        $this->grantAccessView = new GrantAccess();
        $this->woocommerceApiRepository = new WoocommerceApiRepository();
    }

    /**
     * @param array $queryVars
     * @return bool
     */
    public static function canHandleRequest(array $queryVars): bool
    {
        return \array_key_exists(
                ApiController::MIP_ROUTE,
                $queryVars
            ) && (($queryVars[ApiController::MIP_ROUTE] === self::AUTHORIZE_ROUTE) || ($queryVars[ApiController::MIP_ROUTE] === self::GRANT_ACCESS_ROUTE));
    }

    /**
     * @return void
     */
    public function handleAuthRequests(): void
    {
        global $wp;

        add_action('wp_enqueue_scripts', [$this->assets, 'getHeaderOauthAssets']);

        $shopData = $this->getShopData();

        if (\array_key_exists('mip-route', $wp->query_vars) && ($wp->query_vars['mip-route'] === self::AUTHORIZE_ROUTE) && !is_user_logged_in()) {
            $this->loadLoginTemplate($shopData);
            exit;
        }

        if (\array_key_exists('mip-route', $wp->query_vars) && ($wp->query_vars['mip-route'] === self::AUTHORIZE_ROUTE) && is_user_logged_in()) {
            $this->loadGrantTemplate($shopData);
            exit;
        }

        if (\array_key_exists('mip-route', $wp->query_vars) && ($wp->query_vars['mip-route'] === self::GRANT_ACCESS_ROUTE) && is_user_logged_in() && current_user_can('manage_woocommerce')) {
            if (ConfigurationOptionManager::getAccessToken() === '') {
                ConfigurationOptionManager::setAccessToken(wc_rand_hash());
                ConfigurationOptionManager::setSecretKey(wc_rand_hash());
            }

            if (!$this->woocommerceApiRepository->existsApiAccess()) {
                $this->woocommerceApiRepository->createApiCredentials();
            }

            $userId = get_current_user_id();

            if ($userId) {
                ConfigurationOptionManager::setUserId($userId);
            }

            if (!empty($shopData->ApiKey)) {
                ConfigurationOptionManager::setApiKey($shopData->ApiKey);
            }

            $callBackUrl = $this->buildCallBackUrl($shopData);

            if ($callBackUrl) {
                wp_redirect($callBackUrl);
                exit;
            }
        }
    }

    /**
     * @return ShopData
     */
    private function getShopData(): ShopData
    {
        if (empty($_GET) || empty($_GET[self::SHOP])) {
            exit;
        }

        $shopRawData = sanitize_text_field($_GET[self::SHOP]);

        try {
            $shopDataRaw = \json_decode(\base64_decode($shopRawData), true);
        } catch (\Exception $exception) {
            exit;
        }

        if (!is_array($shopDataRaw)) {
            exit;
        }

        $shopData = new ShopData();
        $shopData->RawData = $shopRawData;
        $shopData->CallbackUrl = sanitize_text_field($shopDataRaw[self::CALLBACK_URL]);
        $shopData->ShopUrl = sanitize_text_field($shopDataRaw[self::SHOP_URL]);
        $shopData->ApiKey = sanitize_text_field($shopDataRaw[self::API_KEY]);

        if (!wc_is_valid_url($shopData->CallbackUrl)) {
            exit;
        }

        return $shopData;
    }

    /**
     * @param ShopData $shopData
     */
    public function loadLoginTemplate(ShopData $shopData): void
    {
        $this->loginView->getLoginForm($shopData);
    }

    /**
     * @param ShopData $shopData
     */
    public function loadGrantTemplate(ShopData $shopData): void
    {
        $this->grantAccessView->getGrantAccessForm($shopData);
    }

    /**
     * @param ShopData $shopData
     * @return string
     */
    public function buildCallBackUrl(ShopData $shopData): string
    {
        $shopName = empty(get_bloginfo('name')) ? $shopData->ShopUrl : addslashes(html_entity_decode(get_bloginfo('name'), ENT_QUOTES));

        $options = [
            'lowercase' => false,
            'separator' => ' ',
            'trim' => false,
        ];

        $slugify = new Slugify();
        $shopNameSlug = $slugify->slugify($shopName, $options);

        $shopDataParameters = [
            self::SHOP_URL => $shopData->ShopUrl,
            self::SHOP_NAME => $shopNameSlug,
            self::ACCESS_TOKEN => ConfigurationOptionManager::getAccessToken(),
            self::SECRET_KEY => ConfigurationOptionManager::getSecretKey(),
        ];

        $shopDataEncoded = \base64_encode(\json_encode($shopDataParameters));

        return $shopData->CallbackUrl.'?'.self::SHOP.'='.$shopDataEncoded;
    }
}