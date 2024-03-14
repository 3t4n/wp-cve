<?php

namespace Mnet\Admin;

use DateTime;
use Exception;
use Log;
use Mnet\Admin\MnetAuthManager;
use Mnet\Utils\MnetAdSlot;
use Mnet\Utils\DefaultOptions;
use Mnet\Utils\Response;

class MnetReportManager
{
    public static function getReport()
    {
        MnetAuthManager::returnIfSessionExpired();
        $type = $_POST['type'];
        if (isset($_POST['range']) && $_POST['range'] === "Last 7 Audited Days") {
            $lastAuditedDate = self::getLastAuditedDate();
            $from = (new DateTime($lastAuditedDate))->modify("-6 days")->format(DateTime::ISO8601);
            $to = (new DateTime($lastAuditedDate))->format(DateTime::ISO8601);
        } else {
            $from = $_POST['from'];
            $to = $_POST['to'];
        }
        $pageSize = isset($_POST['pageSize']) && $_POST['pageSize'] > 0 ? $_POST['pageSize'] : 50;
        $page = isset($_POST['page']) && $_POST['page'] > 0 ? $_POST['page'] : 1;

        $query = array(
            'access_token' => \mnet_user()->token,
            'report_type' => $type,
            'from_date' => self::date($from),
            'to_date' => self::date($to),
            'page_size' => $pageSize,
            'page_number' => $page,
            'get_audit_date' => true,
        );
        $report = self::fetch($query);
        \wp_send_json($report, 200);
    }

    public static function getDashboardRevenueAndHeaderStats()
    {
        MnetAuthManager::returnIfSessionExpired();
        $noOfSlotsConfigured = MnetAdSlot::count();
        $access_token = \mnet_user()->token;

        $url = MNET_API_ENDPOINT . 'dashboard/revenue-rpm-details?access_token=' . $access_token;
        $response = \wp_remote_get($url, DefaultOptions::$MNET_API_DEFAULT_ARGS);

        if (\is_wp_error($response) || $response['response']['code'] !== 200) {
            Response::fail(json_decode($response['body']), 501, "Failed to fetch revenue details.");
        }

        $result = json_decode($response['body'], true);        
        $result['rpmDetails']['noOfSlots'] = $noOfSlotsConfigured;

        Response::success($result);
    }

    private static function date($str = null)
    {
        $time = new DateTime($str);
        return $time->format('m/d/Y');
    }

    private static function fetch($query)
    {
        $response = \wp_remote_get(
            MNET_API_ENDPOINT . 'report?' . http_build_query(array_merge($query, array('type' => 'json'))),
            array_merge(DefaultOptions::$MNET_API_DEFAULT_ARGS, array('timeout' => 120))
        );
        MnetAuthManager::handleAccessTokenExpired($response);
        if (\is_wp_error($response) || $response['response']['code'] !== 200) {
            \wp_send_json_error(array('error' => array('message' => "Failed to fetch reports. Click <a href='https://pubconsole.media.net/dashboard' target='_blank'> here </a> to login to media.net dashboard.")), 501);
        }
        return json_decode($response['body'], true);
    }

    private static function getLastAuditedDate()
    {
        $access_token = \mnet_user()->token;

        $url = MNET_API_ENDPOINT . 'last-audited-date?access_token=' . $access_token;
        $response = \wp_remote_get($url, DefaultOptions::$MNET_API_DEFAULT_ARGS);

        return \Arr::get($response, 'body');
    }

    public static function fetchLastAuditedDate()
    {
        $lastAuditedDate = self::getLastAuditedDate();
        \wp_send_json(array(
            'lastAuditDate' => date("Y-m-d", strtotime($lastAuditedDate))
        ));
    }
}
