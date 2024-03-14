<?php

namespace Attire\Blocks;
require 'ImageUploader.php';

class AttireImageAutoUpload
{
    const WP_OPTIONS_KEY = 'aui-setting';

    private static $_options;

    /**
     * WP_Auto_Upload Run.
     * Set default variables and options
     * Add wordpress actions
     */
    public function run()
    {
        add_filter('wp_insert_post_data', array($this, 'savePost'), 10, 2);
    }

    /**
     * Automatically upload external images of a post to Wordpress upload directory
     * call by wp_insert_post_data filter
     * @param array data An array of slashed post data
     * @param array $postarr An array of sanitized, but otherwise unmodified post data
     * @return array $data
     */
    public function savePost($data, $postarr)
    {
        if (wp_is_post_revision($postarr['ID']) ||
            wp_is_post_autosave($postarr['ID']) ||
            (defined('DOING_AJAX') && DOING_AJAX) ||
            (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)) {
            return $data;
        }

        if ($content = $this->save($postarr)) {
            $data['post_content'] = $content;
        }
        return $data;
    }

    /**
     * Upload images and save new urls
     * @return string filtered content
     */
    public function save($postarr)
    {
        $excludePostTypes = self::getOption('exclude_post_types');
        if (is_array($excludePostTypes) && in_array($postarr['post_type'], $excludePostTypes, true)) {
            return false;
        }

        $content = $postarr['post_content'];
        $images = $this->findAllImageUrls(stripslashes($content));

        if (count($images) == 0) {
            return false;
        }

        foreach ($images as $image) {

//            skip if image is not hosted in wpattire
            if (strpos($image['url'], 'demo.wpattire.com') === false) {
                continue;
            }

            $uploader = new ImageUploader($image['url'], $image['alt'], $postarr);
            if ($uploadedImage = $uploader->save()) {
                $urlParts = parse_url($uploadedImage['url']);
                $base_url = $uploader::getHostUrl(null, true);
                $image_url = $base_url . $urlParts['path'];
                $content = preg_replace('/' . preg_quote($image['url'], '/') . '/', $image_url, $content);
                $content = preg_replace('/alt=["\']' . preg_quote($image['alt'], '/') . '["\']/', "alt='{$uploader->getAlt()}'", $content);
            }
        }
        return $content;
    }

    /**
     * Find image urls in content and retrieve urls by array
     * @param $content
     * @return array
     */
    public function findAllImageUrls($content)
    {
        $urls1 = array();
        preg_match_all('/<img[^>]*srcset=["\']([^"\']*)[^"\']*["\'][^>]*>/i', $content, $srcsets, PREG_SET_ORDER);
        if (count($srcsets) > 0) {
            $count = 0;
            foreach ($srcsets as $key => $srcset) {
                preg_match_all('/https?:\/\/[^\s,]+/i', $srcset[1], $srcsetUrls, PREG_SET_ORDER);
                if (count($srcsetUrls) == 0) {
                    continue;
                }
                foreach ($srcsetUrls as $srcsetUrl) {
                    $urls1[$count][] = $srcset[0];
                    $urls1[$count][] = $srcsetUrl[0];
                    $count++;
                }
            }
        }

        preg_match_all('/<img[^>]*src=["\']([^"\']*)[^"\']*["\'][^>]*>/i', $content, $urls, PREG_SET_ORDER);
        $urls = array_merge($urls, $urls1);

        if (count($urls) == 0) {
            return array();
        }
        foreach ($urls as $index => &$url) {
            $images[$index]['alt'] = preg_match('/<img[^>]*alt=["\']([^"\']*)[^"\']*["\'][^>]*>/i', $url[0], $alt) ? $alt[1] : null;
            $images[$index]['url'] = $url = $url[1];
        }
        foreach (array_unique($urls) as $index => $url) {
            $unique_array[] = $images[$index];
        }
        return $unique_array;
    }


    /**
     * Returns options in an array
     * @return array
     */
    public static function getOptions()
    {
        if (static::$_options) {
            return static::$_options;
        }
        $defaults = array(
            'base_url' => get_bloginfo('url'),
            'image_name' => '%filename%',
            'alt_name' => '%image_alt%',
        );
        return static::$_options = wp_parse_args(get_option(self::WP_OPTIONS_KEY), $defaults);
    }

    /**
     * Reset options to default options
     * @return bool
     */
    public static function resetOptionsToDefaults()
    {
        $defaults = array(
            'base_url' => get_bloginfo('url'),
            'image_name' => '%filename%',
            'alt_name' => '%image_alt%',
        );
        static::$_options = $defaults;
        return update_option(self::WP_OPTIONS_KEY, $defaults);
    }

    /**
     * Return an option with specific key
     * @param $key
     * @return mixed
     */
    public static function getOption($key, $default = null)
    {
        $options = static::getOptions();
        if (isset($options[$key]) === false) {
            return $default;
        }
        return $options[$key];
    }
}
