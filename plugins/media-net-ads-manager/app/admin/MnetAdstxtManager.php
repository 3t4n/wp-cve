<?php

namespace Mnet\Admin;

use DateTime;
use Mnet\Utils\Response;
use Mnet\Utils\DefaultOptions;
use Mnet\Admin\MnetAuthManager;

class MnetAdstxtManager
{
    public function getAdstxtDetails()
    {
        MnetAuthManager::returnIfSessionExpired();
        // fetch adstxt and status
        $type = $_GET['refresh'] === "false" ? 'details' : 'refresh';
        $adstxtDetails = self::makeRequest($type);

        $adstxtStatus = $adstxtDetails->adsTxtStatus;
        $lastCrawledAt = $adstxtDetails->lastCrawledAt;
        $adstxt = $adstxtDetails->adsTxt;
        $domain = $adstxtDetails->site;
        $providers = "";

        $formattedLastCrawledAt = (new DateTime($lastCrawledAt))->format('d-m-Y h:i:s A');

        // fetch providers if not optimized
        if ($adstxtStatus->id !== 2) {
            $providers = self::makeRequest('providers');
        }

        Response::success([
            "adstxt" => $adstxt,
            "adstxtStatus" => $adstxtStatus,
            "lastCrawledAt" => $formattedLastCrawledAt,
            "providers" => $providers,
            "domain" => $domain,
        ]);
    }

    private function makeRequest($type)
    {
        $url = self::getUrl($type);

        $response = \wp_remote_get($url, DefaultOptions::$MNET_API_DEFAULT_ARGS);
        MnetAuthManager::handleAccessTokenExpired($response);
        if (\is_wp_error($response) || json_decode($response['body'])->status !== "OK") {
            Response::fail("fail");
        }

        $responseBody = json_decode($response['body']);
        return $responseBody->data;
    }

    private function getUrl($type)
    {
        $access_token = \mnet_user()->token;

        if ($type === 'refresh') {
            return MNET_API_ENDPOINT . "adstxt/details?refresh=true&access_token=" . $access_token;
        }
        return MNET_API_ENDPOINT . "adstxt/${type}?access_token=" . $access_token;
    }
}
