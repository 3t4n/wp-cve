<?php

class RabbitLoader_21_CDN
{

    public $cdn_prefix = '';
    public $site_host = '';
    private $replaced_scripts = [];
    private $excluded_handlers = ['amelia'];
    private static $excluded_srs = [RabbitLoader_21_Public::skip_reason_ep, RabbitLoader_21_Public::skip_reason_us];

    public static function init()
    {
        if (!function_exists('is_admin') || is_admin() || RL21UtilWP::is_login_page()) {
            return;
        }
        $sr = RabbitLoader_21_Public::getSkipReason();
        if (in_array($sr, self::$excluded_srs)) {
            //excluded pages or RL origin pages
            return;
        }

        $cdn_prefix = get_option('rabbitloader_cdn_prefix');
        if (empty($cdn_prefix)) {
            return;
        }

        $cdn = new RabbitLoader_21_CDN();
        $cdn->cdn_prefix = $cdn_prefix;
        $cdn->site_host = parse_url(get_site_url())['host'];

        add_filter('script_loader_tag', [$cdn, 'rollback'], 99, 3);
        add_filter('script_loader_src', [$cdn, 'replaceAssetHost'], 10, 2);
        add_filter('style_loader_src', [$cdn, 'replaceAssetHost'], 10, 2);
        add_filter('wp_get_attachment_url', [$cdn, 'replaceAssetHost'], 10, 2);
        add_filter('wp_calculate_image_srcset', function ($sources) use ($cdn) {
            foreach ($sources as &$source) {
                if (!empty($source['url'])) {
                    $source['url'] = $cdn->replaceAssetHost($source['url'], '');
                }
            }
            return $sources;
        }, 10, 1);
    }

    public function replaceAssetHost($original_src, $handle_or_attachment_id)
    {
        if (empty($this->cdn_prefix) || stripos($original_src, ';')) {
            return $original_src;
        }
        foreach ($this->excluded_handlers as $h) {
            if (stripos($handle_or_attachment_id, $h) !== false) {
                return $original_src;
            }
        }
        $parsed_url = parse_url($original_src);
        $sameHost = !empty($parsed_url['host']) && (strcasecmp($parsed_url['host'], $this->site_host) == 0);
        $noHost = empty($parsed_url['host']);
        if ($noHost || $sameHost) {
            $parsed_url['host'] = $this->cdn_prefix;
            $parsed_url['scheme'] = "https";
            $new_src = sprintf(
                '%s://%s%s%s%s',
                $parsed_url['scheme'],
                $parsed_url['host'],
                $parsed_url['path'],
                !empty($parsed_url['query']) ? '?' . $parsed_url['query'] : '',
                !empty($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : ''
            );
            $this->replaced_scripts[$new_src] = $original_src;
            return $new_src;
        }
        return $original_src;
    }

    public function rollback($tag, $handle, $new_src)
    {
        if (!empty($new_src) && stripos($tag, "module") !== false && !empty($this->replaced_scripts[$new_src])) {
            //rollback
            $count = 0;
            return str_replace($new_src, $this->replaced_scripts[$new_src], $tag, $count);
        }
        return $tag;
    }
}
