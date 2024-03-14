<?php

namespace luckywp\glossary\front;

use luckywp\glossary\core\Core;
use luckywp\glossary\core\front\BaseFront;

/**
 * @property string $assetsUrl
 */
class Front extends BaseFront
{

    protected $defaultThemeViewsDir = 'luckywp-glossary';

    public function init()
    {
        parent::init();
        require_once Core::$plugin->dir . '/front/functions.php';
        if (!wp_doing_ajax()) {
            require_once Core::$plugin->dir . '/front/template_hooks.php';

            // Assets
            add_action('wp_enqueue_scripts', [$this, 'assets']);

            // Корректные классы для страниц, выводимых с помощью функции wp_list_pages
            add_filter('wp_list_pages', function ($pages) {
                if (!lwpgls_is()) {
                    return $pages;
                }

                // Выделение страницы архива
                $class = 'page-item-' . Core::$plugin->archivePageId;
                $pages = str_replace($class, $class . ' ' . (lwpgls_is_archive() ? 'current-menu-item' : 'current_page_parent'), $pages);

                // Убрать класс с блога
                $class = 'page-item-' . get_option('page_for_posts');
                $pages = str_replace($class . ' current_page_parent', $class, $pages);

                return $pages;
            });

            // Корректные классы у элементов меню
            add_filter('nav_menu_css_class', function ($classes, $item) {
                if (!lwpgls_is()) {
                    return $classes;
                }

                // Выделение архива терминов
                if (($item->object == 'page') &&
                    ($item->object_id == Core::$plugin->archivePageId)
                ) {
                    $classes[] = lwpgls_is_archive() ? 'current-menu-item' : 'current_page_parent';
                }

                // Убрать класс с блога
                if (($item->object == 'page') &&
                    ($item->object_id == get_option('page_for_posts'))
                ) {
                    $classes = array_diff($classes, ['current_page_parent']);
                }

                return $classes;
            }, 10, 2);
        }
    }

    public function assets()
    {
        if (lwpgls_is()) {
            wp_enqueue_style('lpwgls-main', $this->getAssetsUrl() . '/main.min.css', [], Core::$plugin->version);
        }
    }

    public function getAssetsUrl()
    {
        return Core::$plugin->url . '/front/assets';
    }
}
