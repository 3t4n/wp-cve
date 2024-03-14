<?php

namespace WordPress\Plugin\GalleryManager;

use DOMDocument;

abstract class Core
{
    const
        version = '1.6.58'; # Current release number

    public static
        $base_url, # url to the plugin directory
        $plugin_file, # the main plugin file
        $plugin_folder; # the path to the folder the plugin files contains

    public static function init(string $plugin_file): void
    {
        static::$plugin_file = $plugin_file;
        static::$plugin_folder = DirName(static::$plugin_file);

        register_Activation_Hook(static::$plugin_file, [static::class, 'installPlugin']);
        register_Deactivation_Hook(static::$plugin_file, [static::class, 'uninstallPlugin']);
        add_Action('plugins_loaded', [static::class, 'loadBaseUrl']);
        add_Filter('post_class', [static::class, 'addContentUnitPostClass']);
        add_Filter('body_class', [static::class, 'addTaxonomyBodyClass']);
        add_Filter('wp_get_attachment_link', [static::class, 'filterAttachmentLink'], 10, 2);
        add_Filter('get_the_archive_title', [static::class, 'filterArchiveTitle']);
    }

    public static function installPlugin(): void
    {
        Taxonomies::updateTaxonomyNames();
        PostType::updatePostTypeName();
        Taxonomies::registerTaxonomies();
        PostType::registerPostType();
        flush_Rewrite_Rules();
    }

    public static function uninstallPlugin(): void
    {
        flush_Rewrite_Rules();
    }

    public static function loadBaseURL(): void
    {
        $absolute_plugin_folder = RealPath(static::$plugin_folder);

        if (StrPos($absolute_plugin_folder, ABSPATH) === 0)
            static::$base_url = site_url() . '/' . SubStr($absolute_plugin_folder, Strlen(ABSPATH));
        else
            static::$base_url = Plugins_Url(BaseName(static::$plugin_folder));

        static::$base_url = Str_Replace("\\", '/', static::$base_url); # Windows Workaround
    }

    public static function addContentUnitPostClass(array $arr_class): array
    {
        $arr_class[] = 'gallery-content-unit';
        return $arr_class;
    }

    public static function addTaxonomyBodyClass(array $arr_class): array
    {
        $gallery_taxonomies = get_Object_Taxonomies(PostType::post_type_name);
        if (!empty($gallery_taxonomies) && is_Tax($gallery_taxonomies))
            $arr_class[] = 'gallery-taxonomy';

        return $arr_class;
    }

    public static function filterAttachmentLink(string $link, int $attachment_id): string
    {
        static $libxml_is_fine = -1;

        if ($libxml_is_fine < 0)
            $libxml_is_fine = Version_Compare(LIBXML_DOTTED_VERSION, '2.7.8', '>=');

        if (WP_Attachment_Is_Image($attachment_id) && $libxml_is_fine && class_exists('DOMDocument')) {
            $image = get_Post($attachment_id);

            # convert the link in an HTML object
            $html = new DOMDocument('1.0', 'UTF-8');
            $html->loadHTML('<?xml version="1.0" encoding="UTF-8" ?>' . $link, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $html->encoding = 'UTF-8';
            $link_node = $html->childNodes->item(1);

            # Add link attributes
            if (!$link_node->hasAttribute('title'))
                $link_node->setAttribute('title', $image->post_title);

            if (!$link_node->hasAttribute('data-description'))
                $link_node->setAttribute('data-description', $image->post_content);

            # convert the link node back to a html string
            $link = (string) $html->saveHTML($link_node);
        }

        return $link;
    }

    public static function filterArchiveTitle(string $title): string
    {
        if (is_Post_Type_Archive(PostType::post_type_name))
            return post_type_archive_title('', false);
        else
            return $title;
    }
}
