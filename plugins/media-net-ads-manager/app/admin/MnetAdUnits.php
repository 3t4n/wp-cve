<?php

namespace Mnet\Admin;

use Mnet\Admin\MnetAdTag;
use Mnet\Admin\MnetAdAdvanceConfiguration;
use Mnet\MnetDbManager;
use Mnet\Utils\DefaultOptions;
use Mnet\Admin\MnetAuthManager;

class MnetAdUnits
{
    static $API_ENDPOINT = MNET_API_ENDPOINT;

    public static function getAdUnits()
    {
        try {
            $page = $_GET['page'];
            $rows = $_GET['rows'];
            $search = $_GET['search'];
            $result = MnetDbManager::getAdtagWithPagination($page, $rows, $search);
            \wp_send_json(array_merge(['status' => 'SUCCESS'], $result));
        } catch (\Exception $e) {
            \wp_send_json_error(
                array(
                    'error' => array('message' => 'Something went wrong. Please retry after sometime.')
                ),
                501
            );
        }
    }

    public static function createAdunit()
    {
        MnetAuthManager::returnIfSessionExpired();

        $payload = [
            'name' => $_POST['name'],
            'size' => $_POST['size'],
            'responsive' => $_POST['responsive'],
            'docked' => $_POST['docked'],
            'access_token' => \mnet_user()->token
        ];

        $url = static::$API_ENDPOINT . "ad-units";
        $response = \wp_remote_post(
            $url,
            array_merge(
                DefaultOptions::$MNET_API_DEFAULT_ARGS,
                array(
                    'method' => 'POST',
                    'body' => $payload,
                )
            )
        );
        MnetAuthManager::handleAccessTokenExpired($response);

        if (\is_wp_error($response) || !isset($response['body']) || $response['body'] == "" || $response === null) {
            \wp_send_json_error(
                array("status" => "error", "message" => "Something went wrong. Please retry after sometime."),
                400
            );
        }

        MnetAdTag::fetchAdTags(null, false);
        \wp_send_json(json_decode($response['body']));
    }

    public static function getAdUnitSizes()
    {
        MnetAuthManager::returnIfSessionExpired();

        $url = static::$API_ENDPOINT . "ad-units/sizes?access_token=" . \mnet_user()->token;
        $response = \wp_remote_get($url, DefaultOptions::$MNET_API_DEFAULT_ARGS);

        MnetAuthManager::handleAccessTokenExpired($response);

        if (\is_wp_error($response) || $response['response']['code'] !== 200 || !isset($response['body']) || $response['body'] == "") {
            $responseBody = !\is_wp_error($response) ? json_decode($response['body'], true) : null;
            \wp_send_json_error(
                array(
                    'error' => is_array($responseBody) ? $responseBody : array("message" => "Something went wrong. Please retry after sometime.")
                ),
                501
            );
        }
        \wp_send_json(json_decode($response['body']));
    }

    public static function getSlotsData()
    {
        $adtags = MnetAdTag::all();
        $adtags = array_reduce($adtags, function ($acc, $adtag) {
            $acc[$adtag['id']] = [
                'id' => $adtag['id'],
                'name' => $adtag['name'],
                'width_by_height' => $adtag['width'] . 'x' . $adtag['height']
            ];
            return $acc;
        }, []);
        $pageSlots = MnetAdAdvanceConfiguration::getFormattedPageSlots();

        $data = [];
        foreach ($pageSlots as $page => $slots) {
            $data[$page] = array_map(function ($slot) use ($adtags) {
                $slot['adtag'] = \Arr::get($adtags, $slot['tagId'], []);
                return $slot;
            }, $slots);
        }
        if(empty($data)) {
            $data = (Object)[];
        }
        \wp_send_json(compact('data'), 200);
    }
}
