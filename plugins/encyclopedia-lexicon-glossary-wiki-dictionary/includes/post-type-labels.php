<?php

namespace WordPress\Plugin\Encyclopedia;

abstract class PostTypeLabels
{
    public static function getEncyclopediaType(): string
    {
        $type = Options::get('encyclopedia_type');
        $type = WPML::t($type, 'Encyclopedia type');
        return $type;
    }

    public static function getArchiveSlug(): string
    {
        $slug = I18n::_x('encyclopedia', 'URL Slug');
        $slug = trim($slug, '/');
        return $slug;
    }

    public static function getItemSlug(): string
    {
        $slug = I18n::_x('encyclopedia', 'URL Slug');
        $slug = trim($slug, '/');
        return $slug;
    }

    public static function getItemSingularName(): string
    {
        $name = Options::get('item_singular_name');
        $name = WPML::t($name, 'Item singular name');
        return $name;
    }

    public static function getItemPluralName(): string
    {
        $name = Options::get('item_plural_name');
        $name = WPML::t($name, 'Item plural name');
        return $name;
    }
}
