<?php

class Zwt_wp_linkpreviewer_Utils
{

    public static function getOptionValue($key)
    {
        $options = get_option(Zwt_wp_linkpreviewer_Constants::$OPTION_KEY_SETTINGS, array());
        if ($options && array_key_exists($key, $options)) {
            return $options[$key];
        }
        return null;
    }

    private static function getOrDefault($key, $defaultValue)
    {
        $option = self::getOptionValue($key);
        if ($option) {
            return $option;
        }
        return $defaultValue;
    }


    public static function render_img_html_admin($hash_md5)
    {
        $restNamespace = Zwt_wp_linkpreviewer_Constants::$REST_NAMESPACE;
        return "<img style='width: 50px;' class='zwt-wp-lnk-prev-img' src='../?rest_route=/$restNamespace/img/$hash_md5/compact'>";
    }


    public static function render_img_html($hash_md5, $url, $target, $is_full)
    {
        $size = $is_full ? "full" : "compact";
        $restNamespace = Zwt_wp_linkpreviewer_Constants::$REST_NAMESPACE;
        $imgHtml = "<img class='zwt-wp-lnk-prev-img' src='?rest_route=/$restNamespace/img/$hash_md5/$size'>";
        return self::wrap_anchor($url, $imgHtml, "", $target);
    }

    public static function render_empty_img_html($url, $target)
    {
        $imgHtml = "<img class='zwt-wp-lnk-prev-img empty'>";
        return self::wrap_anchor($url, $imgHtml, "", $target);
    }

    public static function wrap_notice($text)
    {
        return "<div class=\"notice notice-success\"><p>$text</p></div>";
    }

    public static function wrap_warning($text)
    {
        return "<div class=\"notice notice-warning\"><p>$text</p></div>";
    }

    public static function wrap_anchor($url, $content, $classes, $target)
    {
        $rel = self::getOrDefault(Zwt_wp_linkpreviewer_Constants::$KEY_REL, Zwt_wp_linkpreviewer_Constants::$OPTION_DEFAULT_REL);
        $targetStr = empty($target) ? "" : "target=" . $target;
        return "<a class=\"$classes\" href=\"$url\" $targetStr" . ($rel ? " rel=\"$rel\"" : "") . ">$content</a>";
    }

    public static function fetchUrl($dbInstance, $url)
    {
        $content_fetcher = new Zwt_wp_linkpreviewer_Content_Fetcher();
        $fetchResult = $content_fetcher->fetchContent($url);
        if ($fetchResult) {
            $dbInstance->insertEntry($url, self::trim_text($fetchResult['title'], Zwt_wp_linkpreviewer_Constants::$FETCH_TITLE_MAX_CHARS), self::trim_text($fetchResult['description'], Zwt_wp_linkpreviewer_Constants::$FETCH_DESC_MAX_CHARS));
            $fetch_img_result = $content_fetcher->fetchImg($fetchResult['imgUrl']);
            if ($fetch_img_result) {
                $dbInstance->updateImg($url, $fetch_img_result['img_url'], $fetch_img_result['img_full'], $fetch_img_result['img_compact']);
            }
        }
        return null;
    }


    public static function trim_text($input, $length, $ellipses = true, $strip_html = true)
    {
        if ($strip_html) {
            $input = strip_tags($input);
        }
        if (strlen($input) <= $length) {
            return $input;
        }
        $last_space = strrpos(substr($input, 0, $length), ' ');
        $trimmed_text = substr($input, 0, $last_space);
        if ($ellipses) {
            $trimmed_text .= ' ...';
        }
        return $trimmed_text;
    }

    public static function link_preview_by()
    {
        return sprintf(Zwt_wp_linkpreviewer_Constants::$TEXT_PLUGIN_PREVIEW_BY, self::wrap_anchor(esc_url(Zwt_wp_linkpreviewer_Constants::$PLUGIN_LINK), esc_html(Zwt_wp_linkpreviewer_Constants::$TEXT_PLUGIN_NAME), "", "_blank"));
    }


}
