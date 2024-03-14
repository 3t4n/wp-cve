<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Helpers;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class WPHelper
{
    /**
     * Returns the directory where plugin data are stored.
     * 
     * @param   string  $plugin_slug
     * @param   string  $append_path  This is any extra path that must be added to the returned directory path.
     * 
     * @return  string
     */
    public static function getPluginUploadsDirectory($plugin_slug = '', $append_path = '')
    {
        if (!$plugin_slug)
        {
            return;
        }

		$wp_upload_dir = wp_upload_dir();
        
        $path = [$wp_upload_dir['basedir'], $plugin_slug];

        if (!empty($append_path))
        {
            $path[] = $append_path;
        }
        
        return implode(DIRECTORY_SEPARATOR, $path);
    }
    
    /**
     * Returns WPML data needed to get the locale for each post
     * 
     * @param   string  $item
     * @param   string  $join_clause
     * 
     * @return  array
     */
    public static function getWPMLQueryData($item = 'p.ID', $join_clause = PostsBaseHelper::join_clause)
    {
		if (!class_exists('SitePress'))
		{
            return [];
        }

        global $wpdb;

        $locale = get_locale();
        $lang_explode = explode('_', $locale);

        $select = ',iclt.language_code as lang';
        $join = "LEFT JOIN {$wpdb->prefix}icl_translations as iclt ON iclt.element_id = {$item} {$join_clause}";

        return [
            'select' => $select,
            'join' => $join,
            'lang_code' => $lang_explode[0]
        ];
    }

    /**
     * Retives the WPML Flag image from country code
     * 
     * @param   string   $code
     * 
     * @return  string
     */
    public static function getWPMLFlagUrlFromCode($code)
    {
        if (!class_exists('SitePress'))
        {
            return '';
        }

        if (empty($code))
        {
            return '';
        }
        
        global $wpdb;
        $wpml_flags = new \WPML_Flags( $wpdb, new \icl_cache( 'flags', true ), new \WP_Filesystem_Direct( null ) );

        // Prepare country code
        switch (strtolower($code))
        {
            // Sweden
            case 'se':
                $code = 'sv';
                break;
            // Estonia
            case 'ee':
                $code = 'et';
                break;
            // Malaysia
            case 'my':
                $code = 'ms';
                break;
            // Serbia
            case 'rs':
                $code = 'sr';
                break;
            // Slovenia
            case 'si':
                $code = 'sl';
                break;
            // Ukraine
            case 'ua':
                $code = 'uk';
                break;
        }
        
        $suffix = strtolower($code) . '.png';
        
        if (!file_exists($wpml_flags->get_wpml_flags_directory() . $suffix))
        {
            return '';
        }

        return '<img src="' . esc_url($wpml_flags->get_wpml_flags_url() . $suffix) . '" style="width:18px;height:12px;" alt="' . esc_attr($code) . ' flag" />';
    }

	/**
	 * Returns the current page ID
	 * 
	 * @return  mixed
	 */
	public static function getPageID()
	{
        if (!is_front_page() && is_home())
        {
            return (int) get_option('page_for_posts');
        }
        
        global $wp_query;
        $current_object_id = $wp_query->post;

        if (!$current_object_id)
        {
            return null;
        }

        return (int) $current_object_id->ID;
    }

    /**
     * Checks if plugin is installed.
     * 
     * @param   string  $plugin_path  The path to plugin file i.e. firebox/firebox.php
     * 
     * @return  bool
     */
    public static function isPluginInstalled($plugin_path)
    {
        $installed_plugins = get_plugins();

        return array_key_exists($plugin_path, $installed_plugins) || in_array($plugin_path, $installed_plugins, true);
    }
    
	/**
	 * Checks and returns the active plugins from the a list of plugin names.
	 * 
	 * @param   array  $plugins
	 * 
	 * @return  array
	 */
	public static function getActivePluginsFromList($plugins)
	{
		if (!$plugins)
		{
			return [];
		}

		if (!is_array($plugins))
		{
			return [];
		}

		$active = [];

		foreach ($plugins as $key => $value)
		{
			if (!\is_plugin_active($key))
			{
				continue;
			}

			$active[$key] = $value;
		}
		
		return $active;
    }
    
    /**
     * Returns the version of the 3rd-party plugin
     * 
     * @param   string  $plugin_slug
     * 
     * @return  mixed
     */
    public static function getThirdPartyPluginVersion($plugin_slug)
    {
		// cache key
		$hash = md5('FPFramework\Core\Helpers\WPHelper::getThirdPartyPluginVersion(' . $plugin_slug . ')');

		// check cache
		if ($version = wp_cache_get($hash))
		{
			return $version;
        }
        
        $plugins = get_plugins();

        if (!isset($plugins[$plugin_slug]))
        {
            return false;
        }

        $version = $plugins[$plugin_slug]['Version'];

		// set cache
		wp_cache_set($hash, $version);

        return $version;
    }

    /**
     * Finds all images within the content, downloads them and replaces the images.
     * 
     * @param   string  $content
     * 
     * @return  string
     */
	public static function downloadAndReplaceImages($content)
    {
        // Extract all links.
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $content, $match);

        $all_links = array_unique($match[0]);

        // Not have any link.
        if (empty($all_links))
        {
            return $content;
        }

        $data = [];
        $images  = [];
        $links  = [];

        // Extract normal and image links.
        foreach ($all_links as $key => $link)
        {
            if (Image::isValidImageURL($link))
            {
                $images[] = $link;
            }
            else
            {
                $links[] = $link;
            }
        }

        // Download the images.
        if (!empty($images))
        {
            foreach($images as $key => $image_url)
            {
                // Download remote image.
                $image = [
                    'url' => $image_url,
                    'id'  => 0
                ];
                
                if (!$downloaded_image = \FPFramework\Libs\ImageImporter::get_instance()->import($image))
                {
                    continue;
                }

                // Store replaceable images
                $data[$image_url] = $downloaded_image['url'];
            }
        }

        // Replace the image URLs
        foreach ($data as $old_url => $new_url)
        {
            $content = str_replace($old_url, $new_url, $content);

            $old_url = str_replace('/', '/\\', $old_url);
            $new_url = str_replace('/', '/\\', $new_url);
            $content = str_replace($old_url, $new_url, $content);
        }

        return $content;
    }

    /**
     * Checks if the Classic Editor plugin is active.
     *
     * @return  bool
     */
    public static function isClassicEditorPluginActive()
    {
        if (!function_exists('is_plugin_active'))
        {
            include_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        if (is_plugin_active('classic-editor/classic-editor.php'))
        {
            return true;
        }

        return false;
    }

    /**
     * Returns all allowed HTML tags used when printing output HTML.
     * 
     * @return  array
     */
    public static function getAllowedHTMLTags()
    {
        $allowed_tags = wp_kses_allowed_html('post');

        // Form
        $allowed_tags['form'] = [
            'action'         => true,
            'accept'         => true,
            'accept-charset' => true,
            'enctype'        => true,
            'method'         => true,
            'name'           => true,
            'target'         => true,
            'role'           => true,
            'data-*'         => true
        ];

        // SVG
        $allowed_tags['svg'] = [
            'style'               => true,
            'class'               => true,
            'aria-hidden'         => true,
            'aria-labelledby'     => true,
            'role'                => true,
            'xmlns'               => true,
            'width'               => true,
            'height'              => true,
            'fill'                => true,
            'viewbox'             => true,
            'preserveaspectratio' => true
        ];
        $allowed_tags['mask'] = [
            'id'    => true,
            'fill'  => true
        ];
        $allowed_tags['line'] = [
            'x1'                => true,
            'x2'                => true,
            'y1'                => true,
            'y2'                => true,
            'stroke'            => true,
            'stroke-width'      => true,
            'stroke-linecap'    => true,
            'stroke-join'       => true
        ];
        $allowed_tags['circle'] = [
            'cx'                => true,
            'cy'                => true,
            'r'                 => true,
            'stroke'            => true,
            'stroke-width'      => true,
            'opacity'           => true,
            'width'             => true,
            'height'            => true,
            'fill'              => true,
            'stroke-dasharray'  => true
        ];
        $allowed_tags['rect'] = [
            'x'             => true,
            'y'             => true,
            'transform'     => true,
            'opacity'       => true,
            'width'         => true,
            'height'        => true,
            'stroke'        => true,
            'stroke-width'  => true,
            'rx'            => true,
            'fill'          => true,
            'mask'          => true
        ];
        $allowed_tags['g'] = [
            'fill' => true
        ];
        $allowed_tags['animatetransform'] = [
            'attributename'     => true,
            'type'              => true,
            'repeatcount'       => true,
            'dur'               => true,
            'values'            => true,
            'keytimes'          => true
        ];
        $allowed_tags['title'] = [
            'title' => true
        ];
        $allowed_tags['path'] = [
            'opacity'           => true,
            'd'                 => true,
            'stroke'            => true,
            'stroke-width'      => true,
            'stroke-linecap'    => true,
            'stroke-linejoin'   => true,
            'fill'              => true,
            'fill-rule'         => true,
            'clip-rule'         => true
        ];

        // Input
        $allowed_tags['input'] = [
            'class'       => true,
            'id'          => true,
            'name'        => true,
            'value'       => true,
            'type'        => true,
            'placeholder' => true,
            'checked'     => true,
            'data-*'      => true
        ];

        // Select
        $allowed_tags['select'] = [
            'class'     => true,
            'id'        => true,
            'name'      => true,
            'value'     => true,
            'type'      => true,
            'data-*'    => true
        ];

        // Select option
        $allowed_tags['option'] = [
            'selected'  => true,
            'name'      => true,
            'value'     => true,
            'class'     => true,
            'data-*'    => true
        ];

        // Style
        $allowed_tags['style'] = [
            'types' => true
        ];

        // i element
        $allowed_tags['i'] = [
            'class' => true
        ];

        // Script
        $allowed_tags['script'] = true;

        return $allowed_tags;
    }
}