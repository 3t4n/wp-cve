<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt;

use GoDaddy\WooCommerce\Poynt\Support\Client;
use WP_User;

defined('ABSPATH') or exit;

/**
 * The support handler.
 *
 * @since 1.2.0
 */
class Support
{
    /** @var string the support user login handle */
    const SUPPORT_USER_HANDLE = 'skyverge';

    /** @var string the support user email */
    const SUPPORT_USER_EMAIL = 'commerce@services.godaddy.com';

    /** @var Client the client instance */
    private $client;

    /**
     * Support handler constructor.
     *
     * Initializes the support {@see Client}.
     *
     * @since 1.2.0
     */
    public function __construct()
    {
        $this->client = $this->getClient();
    }

    /**
     * Gets the support client instance.
     *
     * @since 1.2.0
     *
     * @return Client
     */
    public function getClient() : Client
    {
        return $this->client instanceof Client ? $this->client : new Client();
    }

    /**
     * Gets the support user.
     *
     * @since 1.2.0
     *
     * @return WP_User|null
     */
    public static function getUser()
    {
        $user = get_user_by('login', self::SUPPORT_USER_HANDLE);

        return $user instanceof WP_User ? $user : null;
    }
}
