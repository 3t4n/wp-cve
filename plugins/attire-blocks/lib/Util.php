<?php

namespace Attire\Blocks;

use Attire\Blocks\Session;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Util
{
    function __construct()
    {
    }

    static function validate_license($licenseKey)
    {
        $license_server = "https://wpattire.com/";
        $domain = $_SERVER['HTTP_HOST'];
        $productId = 'ATTIREBLOCKS';
        $args = ['wpdmLicense' => 'validate', 'domain' => $domain, 'licenseKey' => $licenseKey];
        $request = array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 3,
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => [],
            'body' => $args,
            'cookies' => array()
        );
        $response = wp_remote_post($license_server, $request);
        update_option("__atbs_last_check", time(), false);
        if (is_wp_error($response)) {
            Session::set("invalid_atbs_license", "Invalid License Key!");
            return $response['body'];
        } else {
            $response = json_decode($response['body']);
            if ($response->status === 'VALID') {
                update_option('__atbs_pro', json_encode($response));
                update_option('__atbs_pro_license', $licenseKey);
                Session::set("valid_atbs_license", "Congratulation! Your Attire Blocks Pro license activated successfully.");
                return json_encode($response);
            } else {
                delete_option('__atbs_pro');
                delete_option('__atbs_pro_license');
                Session::set("invalid_atbs_license", "Invalid License Key!");
                return json_encode($response);
            }
        }
    }

    static function is_pro()
    {
        $is_pro = get_option('__atbs_pro');
        $is_pro = json_decode($is_pro);
        if ($is_pro) {
            if (($is_pro->expire_date < time() && !get_option("__atbs_rev", false)) || self::check_again()) {
                $s = self::validate_license(get_option('__atbs_license_key'));
                if (!is_wp_error($s))
                    update_option("__atbs_rev", 1);
                else
                    delete_option("__atbs_rev");
            }
            if ($is_pro->status === 'VALID') {
                return true;
            }
        }
        return false;
    }

    static function check_again()
    {
        $last_check = get_option('__atbs_last_check');
        if (!$last_check) return true;
        $check_period = 1296000;
        if (time() - $last_check > $check_period) return true;
        return false;
    }

    static function getBgAttributes($prefix = '', $defaults = array())
    {
        return [
            $prefix . 'ColorLeft' => [
                'type' => 'string',
                'default' => __::valueof($defaults, 'ColorLeft', ['default' => 'transparent'])
            ],
            $prefix . 'ColorRight' => [
                'type' => 'string',
                'default' => __::valueof($defaults, 'ColorRight', ['default' => 'transparent'])
            ],
            $prefix . "Alpha" => [
                "type" => "number",
                'default' => __::valueof($defaults, 'Alpha', ['default' => 95])
            ],
            $prefix . "CS1" => [
                "type" => "number",
                'default' => __::valueof($defaults, 'CS1', ['default' => 0])
            ],
            $prefix . "CS2" => [
                "type" => "number",
                'default' => __::valueof($defaults, 'CS2', ['default' => 100])
            ],
            $prefix . "GradAngle" => [
                "type" => "number",
                'default' => __::valueof($defaults, 'GradAngle', ['default' => 45])
            ],
            $prefix . "BgOverlay" => [
                "type" => "string",
                'default' => __::valueof($defaults, 'BgOverlay', ['default' => ''])
            ]];
    }

    static function getTypographyProps($prefix = '', $defaults = array())
    {
        return [
            $prefix . "FontSize" => [
                "type" => "number",
                "default" => __::valueof($defaults, 'FontSize', ['default' => 18])
            ],
            $prefix . "FontSizeUnit" => [
                "type" => "string",
                "default" => __::valueof($defaults, 'FontSizeUnit', ['default' => 'px'])
            ],
            $prefix . "LineHeight" => [
                "type" => "number",
                "default" => __::valueof($defaults, 'LineHeight', ['default' => 1.3])
            ],
            $prefix . "LineHeightUnit" => [
                "type" => "string",
                "default" => __::valueof($defaults, 'LineHeightUnit', ['default' => ''])
            ],
            $prefix . "LetterSpacing" => [
                "type" => "number",
                "default" => __::valueof($defaults, 'LetterSpacing', ['default' => 0])
            ],
            $prefix . "LetterSpacingUnit" => [
                "type" => "string",
                "default" => __::valueof($defaults, 'LetterSpacingUnit', ['default' => 'px'])
            ],
            $prefix . "FontWeight" => [
                "type" => "string",
                "default" => __::valueof($defaults, 'FontWeight', ['default' => '400'])
            ],
            $prefix . "TextAlign" => [
                "type" => "string",
                "default" => __::valueof($defaults, 'TextAlign', ['default' => 'left'])
            ],
            $prefix . "FontStyle" => [
                "type" => "string",
                "default" => __::valueof($defaults, 'FontStyle', ['default' => 'normal'])
            ],
            $prefix . "TextColor" => [
                "type" => "string",
                "default" => __::valueof($defaults, 'TextColor', ['default' => '#000000'])
            ],
            $prefix . "TextTransform" => [
                "type" => "string",
                "default" => __::valueof($defaults, 'TextTransform', ['default' => 'none'])
            ]
        ];
    }

    static function getSpacingProps($prefix = '', $defaults = array())
    {
        return [
            $prefix . "Padding" => [
                "type" => "array",
                "default" => __::valueof($defaults, 'Padding', ['default' => [0, 0, 0, 0]])
            ],
            $prefix . "PaddingUnit" => [
                "type" => "array",
                "default" => __::valueof($defaults, 'PaddingUnit', ['default' => ['px', 'px', 'px', 'px']])
            ],
            $prefix . "Margin" => [
                "type" => "array",
                "default" => __::valueof($defaults, 'Margin', ['default' => [0, 0, 0, 0]])
            ],
            $prefix . "MarginUnit" => [
                "type" => "array",
                "default" => __::valueof($defaults, 'MarginUnit', ['default' => ['px', 'px', 'px', 'px']])
            ]
        ];
    }

    static function getSpacingStyles($attributes, $prefix = '')
    {
        $style = '';
        $padding = $prefix . 'Padding';
        $margin = $prefix . 'Margin';
        $style .= 'padding:' . Util::getValueByIndex($attributes, $padding, 0) . ' ' . Util::getValueByIndex($attributes, $padding, 1) . ' ' . Util::getValueByIndex($attributes, $padding, 2) . ' ' . Util::getValueByIndex($attributes, $padding, 3) . ';';
        $style .= 'margin:' . Util::getValueByIndex($attributes, $margin, 0) . ' ' . Util::getValueByIndex($attributes, $margin, 1) . ' ' . Util::getValueByIndex($attributes, $margin, 2) . ' ' . Util::getValueByIndex($attributes, $margin, 3) . ';';
        return $style;
    }

    static function getValueByIndex($attributes, $propName, $index)
    {
        if (isset($attributes[$propName . 'Unit']) && ($attributes[$propName . 'Unit'][$index] === 'auto')) {
            return 'auto';
        }
        if (!isset($attributes[$propName][$index]) || !isset($attributes[$propName . 'Unit'])) return '0px';
        return $attributes[$propName][$index] . $attributes[$propName . 'Unit'][$index];
    }

    static function getReadMoreAttributes()
    {
        return [
            "readMore" => [
                "type" => "boolean",
                "default" => false
            ],
            "readMoreLabel" => [
                "type" => "string",
                "default" => "Read more"
            ],
            "readMoreLink" => [
                "type" => "string",
                "default" => ""
            ],
            "readMoreColor" => [
                "type" => "string",
                "default" => "btn-primary"
            ],
            "readMoreAlignment" => [
                "type" => "string",
                "default" => "br"
            ],
            "readMoreSize" => [
                "type" => "string",
                "default" => "md"
            ]
        ];
    }

    static function getBorderAttributes($prefix = '', $defaults = array())
    {
        return [
            $prefix . "BorderWidth" => [
                "type" => "number",
                "default" => __::valueof($defaults, 'BorderWidth', ['default' => 0])
            ],
            $prefix . "BorderRadius" => [
                "type" => "number",
                "default" => __::valueof($defaults, 'BorderRadius', ['default' => 0])
            ],
            $prefix . "BorderColor" => [
                "type" => "string",
                "default" => __::valueof($defaults, 'BorderColor', ['default' => 'transparent'])
            ],
            $prefix . "BorderStyle" => [
                "type" => "string",
                "default" => __::valueof($defaults, 'BorderStyle', ['default' => 'solid'])
            ]
        ];
    }

    static function console_log($output, $with_script_tags = true)
    {
        $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
            ');';
        if ($with_script_tags) {
            $js_code = '<script>' . $js_code . '</script>';
        }
        echo $js_code;
    }

    static function atbsprecho($data, $echo = true)
    {
        $data = "<pre>" . print_r($data, 1) . "</pre>";
        if (!$echo) return $data;
        echo $data;
        return false;
    }

    static function get_border_css($attributes, $prefix = '')
    {
        return 'border:' . $attributes[$prefix . 'BorderWidth'] . 'px; border-color:' . $attributes[$prefix . 'BorderColor'] . ';border-radius:' . $attributes[$prefix . 'BorderRadius'] . 'px;border-style:' . $attributes[$prefix . 'BorderStyle'] . ';';
    }

    static function typographyCss($attributes, $prefix = '', $ignore = [])
    {
        $css = '';

        if (!array_search('FontSize', $ignore)) {
            $css .= 'font-size:' . $attributes[$prefix . 'FontSize'] . $attributes[$prefix . 'FontSizeUnit'] . ';';
        }
        if (!array_search('FontWeight', $ignore)) {
            $css .= 'font-weight:' . $attributes[$prefix . 'FontWeight'] . ';';
        }
        if (!array_search('LineHeight', $ignore)) {
            $css .= 'line-height:' . $attributes[$prefix . 'LineHeight'] . $attributes[$prefix . 'LineHeightUnit'] . ';';
        }
        if (!array_search('LetterSpacing', $ignore)) {
            $css .= 'letter-spacing:' . $attributes[$prefix . 'LetterSpacing'] . $attributes[$prefix . 'LetterSpacingUnit'] . ';';
        }
        if (!array_search('TextAlign', $ignore)) {
            $css .= 'text-align:' . $attributes[$prefix . 'TextAlign'] . ';';
        }
        if (!array_search('FontStyle', $ignore)) {
            $css .= 'font-style:' . $attributes[$prefix . 'FontStyle'] . ';';
        }

        if (!array_search('TextColor', $ignore)) {
            $css .= 'color:' . $attributes[$prefix . 'TextColor'] . '!important;';
        }
        if (!array_search('TextTransform', $ignore)) {
            $css .= 'text-transform:' . $attributes[$prefix . 'TextTransform'] . ';';
        }

        return $css;
    }
}