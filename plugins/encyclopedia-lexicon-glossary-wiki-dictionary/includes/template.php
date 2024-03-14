<?php

namespace WordPress\Plugin\Encyclopedia;

abstract class Template
{
    public static function init(): void
    {
        add_filter('search_template', array(static::class, 'changeSearchTemplate'));
    }

    public static function changeSearchTemplate(string $template): string
    {
        global $wp_query;

        if (Search::isEncyclopediaSearch($wp_query) && $search_template = locate_Template(sprintf('search-%s.php', PostType::post_type_name)))
            return $search_template;
        else
            return $template;
    }

    public static function load(string $template_name, array $vars = []): string
    {
        extract($vars);
        $template_path = locate_Template($template_name);

        OB_Start();
        if (!empty($template_path))
            include $template_path;
        else
            include sprintf('%s/templates/%s', Core::$plugin_folder, $template_name);

        return (string) OB_Get_Clean();
    }
}

Template::init();
