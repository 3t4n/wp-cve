<?php

namespace WcMipConnector\Repository;

use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

class WoocommerceApiRepository
{
    private const API_KEYS_TABLE = 'woocommerce_api_keys';
    private const DESCRIPTION = 'MIP API';
    private const PERMISSIONS = 'read_write';

    /** @var \wpdb  */
    private $wpDb;

    public function __construct()
    {
        $this->wpDb = WordpressDatabaseService::getConnection();
    }

    public function createApiCredentials(?int $userId = null): bool
    {
        if (\is_null($userId)) {
            $userId = get_current_user_id();
        }

        $data = [
            'user_id'         => $userId,
            'description'     => self::DESCRIPTION,
            'permissions'     => self::PERMISSIONS,
            'consumer_key'    => wc_api_hash(ConfigurationOptionManager::getAccessToken()),
            'consumer_secret' => ConfigurationOptionManager::getSecretKey(),
            'truncated_key'   => substr(ConfigurationOptionManager::getAccessToken(),-7),
        ];

        return $this->wpDb->insert($this->wpDb->prefix.self::API_KEYS_TABLE, $data);
    }

    /**
     * @return bool
     */
    public function existsApiAccess(): bool
    {
        $data = [
            'user_id'         => get_current_user_id(),
            'description'     => self::DESCRIPTION,
            'permissions'     => self::PERMISSIONS,
            'consumer_secret' => ConfigurationOptionManager::getSecretKey(),
            'truncated_key'   => substr(ConfigurationOptionManager::getAccessToken(),-7),
        ];

        $sql = 'SELECT * FROM '.$this->wpDb->prefix.self::API_KEYS_TABLE.'
                WHERE description = "'.$data['description'].'" AND user_id = "'.$data['user_id'].'" 
                AND permissions = "'.$data['permissions'].'" AND truncated_key = "'.$data['truncated_key'].'" 
                AND consumer_secret = "'.$data['consumer_secret'].'";';

        return !empty($this->wpDb->get_results($sql, ARRAY_A));
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getUserId(): int
    {
        $sql = 'SELECT user_id FROM '.$this->wpDb->prefix.self::API_KEYS_TABLE.'
                WHERE description = "'.self::DESCRIPTION.'" AND consumer_secret = "'.ConfigurationOptionManager::getSecretKey().'";';

        $result = $this->wpDb->get_row($sql, ARRAY_A);

        if (!$result) {
            throw new \Exception('Not found a woocommerce api key');
        }

        return (int)$result['user_id'];
    }
}