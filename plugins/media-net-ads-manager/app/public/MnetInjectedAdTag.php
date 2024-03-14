<?php

namespace Mnet\PublicViews;

use Mnet\Admin\MnetAdTag;
use Mnet\Admin\MnetPluginUtils;
use Mnet\MnetDbManager;

class MnetInjectedAdTag
{
    public static $TAG_TYPE_INTER = 'interstitial';
    public static $TAG_TYPE_MOBILE = 'mobile';

    public static $P_TYPE_MOBILE_ADS = 5;
    public static $P_TYPE_INTERSTITIAL_ADS = 6;
    public static $P_TYPE_INTERSTITIAL_MOBILE_ADS = 7;

    public static function getTagDetails($tag_id, $position, $type = null)
    {
        $response = "";
        $adtag = MnetAdTag::getAdtag($tag_id);
        if (!empty($adtag)) {
            if (!empty($adtag['ad_code'])) {
                $prefix = "<div class='mnet_plugin' style='position:absolute;z-index:-1;visibility:hidden' data-slot-position='" . $position . "'></div>";
                return $prefix . $adtag['ad_code'];
            }

            $cust_id = \mnet_user()->crid;
            $response = static::interTagTemplate($cust_id, $adtag, $position, $type);
        }
        return $response;
    }

    public static function interTagTemplate($cid, $adtag, $position, $type)
    {
        $versionId = $adtag['version_id'];
        $crid = $adtag['crid'];
        $sizeStr = static::getSizeString($adtag);
        $type = !is_null($type) ? $type : self::$TAG_TYPE_INTER;
        $method = $type . 'ScriptAsset';
        $src = "https://contextual.media.net/" . static::$method() . "?cid=$cid&crid=$crid&size=$sizeStr";
        $ad_tag = <<<INTER
          <script type="text/javascript">
          window._mNHandle = window._mNHandle || {};
          window._mNHandle.queue = window._mNHandle.queue || [];
          medianet_versionId = "$versionId";
          window.mnetInterAdPositionConfigured = "$position";
          (function() {
              var sct = document.createElement("script"),
              winObj = window.top || window,
              sctHl = winObj.document.getElementsByTagName("script")[0];
              sct.type = "text/javascript";
              sct.src = "$src";
              sct.async = "async";
              sctHl.parentNode.insertBefore(sct, sctHl);
          })();
      </script>
INTER;
        return $ad_tag;
    }

    public static function interstitialScriptAsset()
    {
        return "inslmedianet.js";
    }

    public static function mobileScriptAsset()
    {
        return "nmedianet.js";
    }

    public static function getMobileAdDetails($adtag, $position)
    {
        $crid = $adtag['crid'];
        $width = $adtag['width'];
        $height = $adtag['height'];
        $versionId = $adtag['version_id'];

        $script = <<<MOBILE
        <script id="mNCC" language="javascript">
            medianet_width = "$width";
            medianet_height = "$height";
            medianet_crid = "$crid";
            medianet_versionId = "$versionId";
            window.mnetMobileAdPositionConfigured = "$position";
        </script>
MOBILE;

        return $script;
    }

    public static function getHeadScript($ptypes)
    {
        $head_code = MnetDbManager::getAdHeadCodes($ptypes);
        if (!empty($head_code)) return implode("\r\n", $head_code);

        $cust_id = \mnet_user()->crid;
        $script = <<<SCRIPT
		<script type="text/javascript">
        window._mNHandle = window._mNHandle || {};  
        window._mNHandle.queue = window._mNHandle.queue || [];
        medianet_versionId = 4121199;
    </script>
    <script src="//contextual.media.net/dmedianet.js?cid=$cust_id" async="async"></script>
SCRIPT;
        return $script;
    }

    public static function getSizeString($size)
    {
        $sizeStr = $size["width"] . "x" . $size["height"];
        return $sizeStr;
    }

    public static function createTemplateCall($ad_tag, $css_options, $custom_css, $position)
    {
        $divid = $ad_tag['crid'];
        $crid = $ad_tag['crid'];
        $get_css = static::createCss($css_options, $custom_css);
        return static::tagTemplate($ad_tag, $divid, $crid, $get_css, $position);
    }

    public static function getExternalCode($custom_css, $css_options, $slot)
    {
        $external_code = MnetDbManager::getExternalCode($slot['id']);
        $external_code = html_entity_decode($external_code[0]['code'], ENT_QUOTES);
        $css = static::createCss($css_options, $custom_css);
        $position = $slot['position'];
        $ad_tag = <<<ADTAG
        <div class="mnet_plugin externalcode" style="$css" data-slot-position="$position">
            $external_code
        </div>
ADTAG;
        return $ad_tag;
    }

    public static function tagTemplate($adtag, $divid, $crid, $css, $position)
    {
        $size = static::getSizeString($adtag);
        if (MnetPluginUtils::getCurrentThemeName() === 'Twenty Twenty') {
            $css .= 'width:' . $adtag['width'] . "px;";
        }

        if (!empty($adtag['ad_code'])) {
            $code = $adtag['ad_code'];
            $ad_code = <<<ADTAG
            <div class="mnet_plugin codeblock" style="$css" data-slot-position="$position">
                $code
            </div>
ADTAG;
        } else {
            $ad_code = <<<ADTAG
        <div class="mnet_plugin codeblock" style="$css" data-slot-position="$position">
            <div id="$divid">
        <script type="text/javascript">
          try {
              window._mNHandle.queue.push(function () {
                  window._mNDetails.loadTag("$divid", "$size", "$crid");
              });
          } catch (error) {}
        </script>
      </div>
    </div>
ADTAG;
        }

        return $ad_code;
    }

    public static function createCss($css_options, $custom_css)
    {
        $css = '';
        if (!empty($css_options)) {
            $alignment = trim($css_options->alignment);
            $sticky = $css_options->sticky;

            $margin_top = $css_options->margin_top;
            $margin_right = $css_options->margin_right;
            $margin_bottom = $css_options->margin_bottom;
            $margin_left = $css_options->margin_left;

            $padding_top = $css_options->padding_top;
            $padding_right = $css_options->padding_right;
            $padding_bottom = $css_options->padding_bottom;
            $padding_left = $css_options->padding_left;

            if ($alignment === "left") {
                $css .= "float:left;";
            } elseif ($alignment === "right") {
                $css .= "float:right;";
            } else {
                $css .= "text-align:$alignment;margin: 0 auto;";
            }

            if ($sticky == "1") {
                $css .= "position:sticky;top:0;z-index:9999;";
            }

            if (intval($margin_top) !== 0) {
                $css .= "margin-top: $margin_top" . "px;";
            }

            if (intval($margin_right) !== 0) {
                $css .= "margin-right: $margin_right" . "px;";
            }

            if (intval($margin_bottom) !== 0) {
                $css .= "margin-bottom: $margin_bottom" . "px;";
            }

            if (intval($margin_left) !== 0) {
                $css .= "margin-left: $margin_left" . "px;";
            }

            if (intval($padding_top) !== 0) {
                $css .= "padding-top: $padding_top" . "px;";
            }

            if (intval($padding_right) !== 0) {
                $css .= "padding-right: $padding_right" . "px;";
            }

            if (intval($padding_bottom) !== 0) {
                $css .= "padding-bottom: $padding_bottom" . "px;";
            }

            if (intval($padding_left) !== 0) {
                $css .= "padding-left: $padding_left" . "px;";
            }
        }

        if (!empty($custom_css)) {
            $css .= $custom_css;
        }
        return $css;
    }
}
