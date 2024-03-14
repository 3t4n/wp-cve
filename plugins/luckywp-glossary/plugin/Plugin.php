<?php

namespace luckywp\glossary\plugin;

use luckywp\glossary\admin\Admin;
use luckywp\glossary\core\base\BasePlugin;
use luckywp\glossary\core\base\Request;
use luckywp\glossary\core\base\View;
use luckywp\glossary\core\wp\Options;
use luckywp\glossary\core\wp\Settings;
use luckywp\glossary\front\Front;
use luckywp\glossary\front\Route;
use WP_Post;
use WP_Post_Type;

/**
 * @property Admin $admin
 * @property Front $front
 * @property-read Options $options
 * @property Request $request
 * @property Route $route
 * @property Settings $settings
 * @property View $view
 *
 * @property int|null $archivePageId
 * @property string $archivePageSlug
 * @property string $archivePageUrl
 * @property WP_Post|null $archivePage
 * @property WP_Post_Type[] $postTypes
 */
class Plugin extends BasePlugin
{

    public $defaultArchiveSlug;

    /**
     * @var string Ссылка на покупку плагина
     */
    public $buyUrl;

    public function init()
    {
        parent::init();
        add_action('init', [$this, 'registerPostType']);
    }

    public function registerPostType()
    {
        $archiveSlug = get_page_uri($this->getArchivePageId());
        if ($archiveSlug == '') {
            $archiveSlug = true;
        }
        register_post_type(Term::POST_TYPE, [
            'labels' => [
                'name' => esc_html__('Glossary', 'luckywp-glossary'),
                'singular_name' => esc_html__('Term', 'luckywp-glossary'),
                'menu_name' => esc_html__('Glossary', 'luckywp-glossary'),
                'all_items' => esc_html__('All Terms', 'luckywp-glossary'),
                'add_new' => esc_html__('Add Term', 'luckywp-glossary'),
                'add_new_item' => esc_html__('Add New Term', 'luckywp-glossary'),
                'edit_item' => esc_html__('Edit Term', 'luckywp-glossary'),
                'new_item' => esc_html__('New Term', 'luckywp-glossary'),
                'view_item' => esc_html__('View Term', 'luckywp-glossary'),
                'search_items' => esc_html__('Search Terms', 'luckywp-glossary'),
                'not_found' => esc_html__('No Terms', 'luckywp-glossary'),
                'not_found_in_trash' => esc_html__('No Terms found in Trash', 'luckywp-glossary'),
                'parent_item_colon' => null,
            ],
            'description' => '',
            'public' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'show_ui' => true,
            'menu_position' => '42.341',
            'menu_icon' => 'dashicons-editor-textcolor',
            'capability_type' => 'post',
            'hierarchical' => false,
            'supports' => ['title', 'editor'],
            'has_archive' => $archiveSlug,
            'rewrite' => false
        ]);
    }

    /**
     * Возвращает ID странциы с архивом записей
     * @return int|false
     */
    public function getArchivePageId()
    {
        $page = $this->getArchivePage();
        return $page ? $page->ID : null;
    }

    /**
     * @return string
     */
    public function getArchivePageSlug()
    {
        $page = $this->getArchivePage();
        return $page === null ? $this->defaultArchiveSlug : get_page_uri($page);
    }

    /**
     * @return string
     */
    public function getArchivePageUrl()
    {
        return get_permalink($this->getArchivePage());
    }

    private $_archivePage;

    /**
     * @return WP_Post|null
     */
    public function getArchivePage()
    {
        if ($this->_archivePage === null) {
            $this->_archivePage = false;
            $id = (int)$this->settings->getValue('general', 'archive_page');
            if ($id > 0) {
                $post = get_post($id);
                if ($post && $post->post_type == 'page' && $post->post_status == 'publish') {
                    $this->_archivePage = $post;
                }
            }
        }
        return $this->_archivePage === false ? null : $this->_archivePage;
    }

    private $_postTypes;

    /**
     * @return WP_Post_Type[]
     */
    public function getPostTypes()
    {
        if ($this->_postTypes === null) {
            $this->_postTypes = get_post_types([
                'public' => true,
            ], 'objects');
        }
        return $this->_postTypes;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'LuckyWP ' . esc_html__('Glossary', 'luckywp-glossary');
    }

    private function pluginI18n()
    {
        __('The plugin implements the glossary/dictionary functionality with support of synonyms.', 'luckywp-glossary');
    }
}
