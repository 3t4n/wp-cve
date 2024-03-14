<?php

namespace Mnet\Admin;

use Mnet\MnetDbManager;
use Mnet\Admin\MnetAuthManager;
use Mnet\Admin\MnetPluginUtils;
use Mnet\Utils\DefaultOptions;
use Mnet\Admin\MnetModuleManager;

class MnetAdTag
{
    public static $requiredColumns = 'id, type, ad_tag_id, name, width, height, product_type_id, product_name, ad_code, crid, version_id, status';

    public static $AD_HEAD_CODE_KEY = 'AD_HEAD_CODES';

    public function __construct($id, $adTagId, $name, $width, $height, $crid, $productTypeId, $productName, $adCode, $versionId, $status, $type, $created_at, $updated_at)
    {
        $this->id = $id;
        $this->adTagId = $adTagId;
        $this->name = $name;
        $this->width = $width;
        $this->height = $height;
        $this->crid = $crid;
        $this->productTypeId = $productTypeId;
        $this->productName = $productName;
        $this->adCode = $adCode;
        $this->versionId = $versionId;
        $this->status = $status;
        $this->type = $type;
        $this->createdAt = $created_at;
        $this->updatedAt = $updated_at;
    }

    public function save()
    {
        $adTag = $this->raw();
        if (!$this->id) {
            unset($adTag['id']);
        }
        return MnetDbManager::saveAdTag($adTag);
    }

    public function raw()
    {
        $result = array();

        foreach ((array) $this as $key => $value) {
            $snakeKey = strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1' . '_', $key));
            $result[$snakeKey] = $value;
        }

        return $result;
    }

    public static function all()
    {
        return MnetDbManager::all(MnetDbManager::$MNET_AD_TAGS, static::$requiredColumns, true);
    }

    public static function getAdtag($id)
    {
        $adtag = MnetDbManager::getDataById(MnetDbManager::$MNET_AD_TAGS, $id, 'id', static::$requiredColumns);
        return !empty($adtag[0]) ? $adtag[0] : array();
    }

    public static function count($clause = null)
    {
        return MnetDbManager::getRowCount(MnetDbManager::$MNET_AD_TAGS, $clause);
    }

    public static function getAvailableSizes()
    {
        $adtags = static::all();
        $adtagSizes = array();
        if (!empty($adtags)) {
            foreach ($adtags as $adtag) {
                $adtagSizes[] = $adtag['width'] . 'x' . $adtag['height'];
            }
        }
        return implode(', ', array_unique($adtagSizes));
    }

    public static function fetchAdTags($access_token = null, $checkTokenExpiry = true)
    {
        $access_token = is_null($access_token) ? \mnet_user()->token : $access_token;
        if (empty($access_token)) {
            $error = array(
                'message' => "Access token wasn't found in session"
            );
            MnetPluginUtils::sendErrorReport($error);
            return array("status" => MNET_SESSION_STATUS_EXPIRED);
        }

        $url = MNET_API_ENDPOINT . 'adtags?access_token=' . $access_token;

        $response = \wp_remote_get($url, DefaultOptions::$MNET_API_DEFAULT_ARGS);
        if (\is_wp_error($response) || (isset($response['response']) && isset($response['response']['code']) && $response['response']['code'] !== 200) || !isset($response['body'])) {
            $body = \is_wp_error($response) ? array('msg' => $response->get_error_message()) : json_decode($response['body'], true);
            if (empty($body)) {
                $body = array();
            }
            if ($checkTokenExpiry) {
                MnetAuthManager::handleAccessTokenExpired($response);
            }
            return array("status" => "error", "message" => "Something Went Wrong.");
        }

        $body = json_decode($response['body'], true);
        if (!isset($body['adtags']) || is_null($body['adtags'])) {
            return array("status" => "error", "message" => "Something went wrong. Please retry after sometime.");
        }

        $adtags = \Arr::get($body, 'adtags');
        $headCodes = \Arr::get($body, 'adHeadCodes');
        return MnetAdTag::populateAdtags($adtags, $headCodes);
    }

    public static function populateAdtags($adtags, $headCodes)
    {
        self::populateAdHeadCode($headCodes);
        $ad_tags = array_map(function ($ad_tag) {
            extract($ad_tag);
            if (intval($adtag_status)) {
                $tag = new MnetAdTag(null, $ad_tag_id, $ad_tag_name, $width, $height, $crid, $product_type_id, $product_name, $adCode, $versionId, $adtag_status, $type, $created_at, $updated_at);
                $tag->save();
                return $ad_tag_id;
            }
            return null;
        }, $adtags);

        $ad_tags = array_values(array_filter($ad_tags));
        $count = count($ad_tags);
        if ($count) {
            $status = "success";
            $message = "Ad tags successfully refreshed!";
        } else {
            $status = "no-adtags";
            $createAdUnitsAllowed = MnetModuleManager::createAdUnitsAllowed();
            $url = $createAdUnitsAllowed ? "#/ad-units/create" : "https://pubconsole.media.net/ads";
            $message = "No Ad tags available. Click <a href='${url}' target='_blank'> here </a> to create new adtags";
        }
        MnetDbManager::clearExpiredAdtagsFromDb($ad_tags);

        return compact("status", "message", "count");
    }

    public static function populateAdHeadCode($codes)
    {
        if (is_null($codes)) return;
        MnetOptions::saveOption(static::$AD_HEAD_CODE_KEY, $codes);
    }
}
