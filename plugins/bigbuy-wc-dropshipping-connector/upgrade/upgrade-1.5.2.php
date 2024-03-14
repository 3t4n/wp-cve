<?php

use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Repository\WoocommerceApiRepository;
use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

$woocommerceApiRepository = new WoocommerceApiRepository();
$wpDb = WordpressDatabaseService::getConnection();

try {
    $apiUserId = $woocommerceApiRepository->getUserId();

    if (get_user_by('id', $apiUserId)) {
        ConfigurationOptionManager::setUserId($apiUserId);

        $sql = 'UPDATE '.$wpDb->prefix.'posts SET post_author = '.$apiUserId.' WHERE post_author = 0';
        $wpDb->query($sql);

        return true;
    }
} catch (\Exception $exception) {
    $administratorUsers = get_users(['role' => 'administrator']);

    if (empty($administratorUsers)) {
        return true;
    }

    /** @var \WP_User $adminUser */
    $adminUser = $administratorUsers[0];
    $userId = $adminUser->get('ID');
    ConfigurationOptionManager::setUserId($userId);
    $woocommerceApiRepository->createApiCredentials($userId);

    $sql = 'UPDATE '.$wpDb->prefix.'posts SET post_author = '.$userId.' WHERE post_author = 0';
    $wpDb->query($sql);

    return true;
}