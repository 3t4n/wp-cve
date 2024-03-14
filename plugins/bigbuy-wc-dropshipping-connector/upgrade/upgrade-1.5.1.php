<?php

use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Repository\WoocommerceApiRepository;
use WcMipConnector\Service\WordpressDatabaseService;

defined('ABSPATH') || exit;

$woocommerceApiRepository = new WoocommerceApiRepository();

try {
    $apiUserId = $woocommerceApiRepository->getUserId();

    if (get_user_by('id', $apiUserId)) {
        ConfigurationOptionManager::setUserId($apiUserId);
        $wpDb = WordpressDatabaseService::getConnection();
        $sql = 'UPDATE '.$wpDb->prefix.'posts SET post_author = '.$apiUserId.' WHERE post_author = 0 AND post_date > (NOW() - INTERVAL 7 DAY)';
        $wpDb->query($sql);
    }
} catch (\Exception $exception) {
    return true;
}

return true;
