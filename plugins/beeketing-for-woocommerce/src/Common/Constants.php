<?php

namespace BeeketingConnect_beeketing_woocommerce\Common;

class Constants
{
    const OLD_APP_SETTING_KEY = 'beeketing_api_key';

    // Cache
    const CACHE_GROUP = 'beeketing';

    // SETTING Keys
    const SETTING_API_KEY = 'api_key';
    const SETTING_ACCESS_TOKEN = 'access_token';
    const SETTING_SNIPPET = 'snippet';
    const SETTING_SHOP_ID = 'shop_id';
    const SETTING_CLIENT_SECRET = 'client_secret';
    const SETTING_PLUGIN_VERSION = 'plugin_version';
    const SETTING_DOMAIN = 'domain';
    const SETTING_SITE_URL = 'site_url';
    const SETTING_TOTAL_INSTALLED_APPS = 'total_installed_apps';
    const SETTING_API_RATE_LIMIT = 'api_rate_limit';
    const SETTING_SHOP_INSTALLED_APPS = 'shop_installed_apps';
    const SETTING_UPGRADE_INVITED = 'upgrade_invited';
    const SETTING_PAUSE_SUBSCRIPTION_APPS = 'pause_subscription_apps';
    const SETTING_USER_ID = 'user_id';
    const SETTING_AUTO_LOGIN_TOKEN = 'auto_login_token';

    // Keys
    const VALIDATE_HEADER_ACCESS_TOKEN = 'X-Beeketing-Access-Token';
    const VALIDATE_HEADER_API_KEY = 'X-Beeketing-Key';

    // Pagination defaults
    const DEFAULT_PAGE = 1;
    const DEFAULT_ITEMS_PER_PAGE = 100;

    // App codes
    const BETTERCOUPONBOX = 'coupon_box';
    const SALESPOP = 'sale_notification';
    const QUICKFACEBOOKCHAT = 'fb_livechat';
    const HAPPYEMAIL = 'happy_email';
    const PERSONALIZEDRECOMMENDATION = 'precommend';
    const CHECKOUTBOOST = 'cboost';
    const BOOSTSALES = 'bsales';
    const MAILBOT = 'beeketing';
    const COUNTDOWNCART = 'countdown_cart';
    const MOBILEWEBBOOST = 'mboost';
    const PUSHER = 'pusher';

    // Cart
    const CART_TOKEN_KEY = '_beeketing_cart_token';
    const COOKIE_BEEKETING_CART_DATA = '_beeketing_cart_content';
    const COOKIE_CART_TOKEN_LIFE_TIME = 2592000;

    // Stock Status
    const STOCK_STATUS_IN_STOCK = 'in_stock';
    const STOCK_STATUS_OUT_OF_STOCK = 'out_of_stock';
}
