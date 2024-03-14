<?php

namespace luckywp\tableOfContents\admin;

use luckywp\tableOfContents\admin\controllers\EditorBlockController;
use luckywp\tableOfContents\admin\controllers\MetaboxController;
use luckywp\tableOfContents\admin\controllers\RateController;
use luckywp\tableOfContents\admin\controllers\SettingsController;
use luckywp\tableOfContents\admin\controllers\ShortcodeController;
use luckywp\tableOfContents\admin\controllers\WidgetController;
use luckywp\tableOfContents\admin\widgets\metabox\Metabox;
use luckywp\tableOfContents\admin\widgets\OverrideColorBadge;
use luckywp\tableOfContents\core\admin\helpers\AdminUrl;
use luckywp\tableOfContents\core\base\BaseObject;
use luckywp\tableOfContents\core\Core;
use luckywp\tableOfContents\core\helpers\ArrayHelper;
use luckywp\tableOfContents\core\helpers\Html;
use luckywp\tableOfContents\plugin\PostSettings;

class Admin extends BaseObject
{

    protected $pageSettingsHook;

    public function init()
    {
        if (is_admin()) {
            add_action('admin_menu', [$this, 'menu']);
            add_action('admin_enqueue_scripts', [$this, 'assets'], 9);
            add_action('add_meta_boxes', [$this, 'addMetaBoxes']);

            // Ссылки в списке плагинов
            add_filter('plugin_action_links_' . Core::$plugin->basename, function ($links) {
                array_unshift($links, Html::a(esc_html__('Settings', 'luckywp-table-of-contents'), AdminUrl::toOptions('settings')));
                return $links;
            });

            // Контроллеры
            MetaboxController::getInstance();
            ShortcodeController::getInstance();
            EditorBlockController::getInstance();
            WidgetController::getInstance();
            RateController::getInstance();

            // Здоровье сайта
            new SiteHealth();
        }
    }

    public function menu()
    {
        $this->pageSettingsHook = add_submenu_page(
            'options-general.php',
            esc_html__('Table of Contents', 'luckywp-table-of-contents'),
            esc_html__('Table of Contents', 'luckywp-table-of-contents'),
            'manage_options',
            Core::$plugin->prefix . 'settings',
            [SettingsController::class, 'router']
        );
    }

    /**
     * @return array
     */
    public function getMetaboxPostTypes()
    {
        return Core::$plugin->settings->getPostTypesForProcessingHeadings();
    }

    public function addMetaBoxes()
    {
        if (current_user_can('edit_posts') && $this->getMetaboxPostTypes()) {
            add_meta_box(
                Core::$plugin->prefix . '_postSettings',
                esc_html__('Table of Contents', 'luckywp-table-of-contents'),
                function ($post) {
                    echo Metabox::widget([
                        'post' => $post,
                    ]);
                },
                $this->getMetaboxPostTypes(),
                'side',
                'low'
            );
        }
    }

    public function assets($hook)
    {
        global $post;
        $this->enqueueAssets([
            'enqueueWpColorPicker' => in_array($hook, [$this->pageSettingsHook, 'post.php', 'post-new.php', 'widgets.php', 'settings_page_lwptoc_settings']),
            'postId' => in_array($hook, ['post.php', 'post-new.php']) ? ArrayHelper::getValue($post, 'ID') : null,
        ]);
    }

    /**
     * @param array $args
     */
    public function enqueueAssets($args)
    {
        if (ArrayHelper::getValue($args, 'enqueueWpColorPicker', false)) {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');
        }
        wp_enqueue_style(Core::$plugin->prefix . 'adminMain', Core::$plugin->url . '/admin/assets/main.min.css', [], Core::$plugin->version);
        wp_enqueue_script(Core::$plugin->prefix . 'adminMain', Core::$plugin->url . '/admin/assets/main.min.js', ['jquery'], Core::$plugin->version);
        wp_localize_script(Core::$plugin->prefix . 'adminMain', 'lwptocMain', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce(Core::$plugin->prefix . 'adminMain'),
            'postId' => ArrayHelper::getValue($args, 'postId'),
            'shortcodeTag' => Core::$plugin->shortcode->getTag(),
            'tableOfContents' => esc_html__('Table of Contents', 'luckywp-table-of-contents'),
            /* translators: verb */
            'Edit' => esc_html__('Edit', 'luckywp-table-of-contents'),
        ]);
    }

    public function checkAjaxReferer()
    {
        check_ajax_referer(Core::$plugin->prefix . 'adminMain');
    }

    /**
     * @param PostSettings|array $source
     * @return array
     */
    public function overrideSettingsToRows($source)
    {
        $getValue = function ($source, $key) {
            $v = ArrayHelper::getValue($source, $key);
            if ($v === null) {
                $lowerKey = strtolower($key);
                if ($lowerKey != $key) {
                    $v = ArrayHelper::getValue($source, $lowerKey);
                }
            }
            return $v;
        };
        $rows = [];
        if (null !== $v = $getValue($source, 'position')) {
            $rows[] = [esc_html__('Position', 'luckywp-table-of-contents'), ArrayHelper::getValue(Core::$plugin->positionsList, $v)];
        }
        if (null !== $v = $getValue($source, 'min')) {
            $rows[] = [esc_html__('Minimal Count of Headings', 'luckywp-table-of-contents'), $v];
        }
        if (null !== $v = $getValue($source, 'depth')) {
            $rows[] = [esc_html__('Depth', 'luckywp-table-of-contents'), $v];
        }
        if (null !== $v = $getValue($source, 'hierarchical')) {
            $rows[] = [esc_html__('Hierarchical View', 'luckywp-table-of-contents'), $v ? __('Enabled', 'luckywp-table-of-contents') : __('Disabled', 'luckywp-table-of-contents')];
        }
        if (null !== $v = $getValue($source, 'numeration')) {
            $rows[] = [esc_html__('Numeration', 'luckywp-table-of-contents'), ArrayHelper::getValue(Core::$plugin->numerationsList, $v)];
        }
        if (null !== $v = $getValue($source, 'numerationSuffix')) {
            $rows[] = [esc_html__('Numeration Suffix', 'luckywp-table-of-contents'), ArrayHelper::getValue(Core::$plugin->getNumerationSuffixsList(), $v)];
        }
        if (null !== $v = $getValue($source, 'title')) {
            $rows[] = [esc_html__('Title', 'luckywp-table-of-contents'), $v == '' ? null : $v];
        }
        if (null !== $v = $getValue($source, 'toggle')) {
            $rows[] = [esc_html__('Toggle Show/Hide', 'luckywp-table-of-contents'), $v ? __('Enabled', 'luckywp-table-of-contents') : __('Disabled', 'luckywp-table-of-contents')];
        }
        if (null !== $v = $getValue($source, 'labelShow')) {
            $rows[] = [esc_html__('Label Show', 'luckywp-table-of-contents'), $v == '' ? null : $v];
        }
        if (null !== $v = $getValue($source, 'labelHide')) {
            $rows[] = [esc_html__('Label Hide', 'luckywp-table-of-contents'), $v == '' ? null : $v];
        }
        if (null !== $v = $getValue($source, 'hideItems')) {
            $rows[] = [esc_html__('Hide Items', 'luckywp-table-of-contents'), $v ? __('Enabled', 'luckywp-table-of-contents') : __('Disabled', 'luckywp-table-of-contents')];
        }
        if (null !== $v = $getValue($source, 'smoothScroll')) {
            $rows[] = [esc_html__('Smooth Scroll', 'luckywp-table-of-contents'), $v ? __('Enabled', 'luckywp-table-of-contents') : __('Disabled', 'luckywp-table-of-contents')];
        }
        if (null !== $v = $getValue($source, 'smoothScrollOffset')) {
            $rows[] = [esc_html__('Smooth Scroll Offset Top', 'luckywp-table-of-contents'), $v . 'px'];
        }
        if (null !== $v = $getValue($source, 'width')) {
            $rows[] = [esc_html__('Width', 'luckywp-table-of-contents'), Core::$plugin->widthToLabel($v)];
        }
        if (null !== $v = $getValue($source, 'float')) {
            $rows[] = [esc_html__('Float', 'luckywp-table-of-contents'), ArrayHelper::getValue(Core::$plugin->floatsList, $v)];
        }
        if (null !== $v = $getValue($source, 'titleFontSize')) {
            $rows[] = [esc_html__('Title Font Size', 'luckywp-table-of-contents'), Core::$plugin->fontSizeToLabel($v)];
        }
        if (null !== $v = $getValue($source, 'titleFontWeight')) {
            $rows[] = [esc_html__('Title Font Weight', 'luckywp-table-of-contents'), ArrayHelper::getValue(Core::$plugin->fontWeightsList, $v)];
        }
        if (null !== $v = $getValue($source, 'itemsFontSize')) {
            $rows[] = [esc_html__('Items Font Size', 'luckywp-table-of-contents'), Core::$plugin->fontSizeToLabel($v)];
        }
        if (null !== $v = $getValue($source, 'colorScheme')) {
            $rows[] = [esc_html__('Color Scheme', 'luckywp-table-of-contents'), ArrayHelper::getValue(Core::$plugin->colorSchemesList, $v)];
        }
        foreach ([
                     'backgroundColor' => 'Background Color',
                     'borderColor' => 'Border Color',
                     'titleColor' => 'Title Color',
                     'linkColor' => 'Link Color',
                     'hoverLinkColor' => 'Hover Link Color',
                     'visitedLinkColor' => 'Visited Link Color',
                 ] as $var => $label) {
            if (null !== $v = $getValue($source, $var)) {
                $rows[] = [esc_html__($label, 'luckywp-table-of-contents'), OverrideColorBadge::widget(['color' => $v]), false];
            }
        }
        if (null !== $v = $getValue($source, 'wrapNoindex')) {
            $rows[] = [
                sprintf(
                /* translators: %s: <!--noindex--> */
                    esc_html__('Wrap table of contents with %s tag', 'luckywp-table-of-contents'),
                    '&lt;!--noindex--&gt;'
                ),
                $v ? __('Enabled', 'luckywp-table-of-contents') : __('Disabled', 'luckywp-table-of-contents')
            ];
        }
        if (null !== $v = $getValue($source, 'useNofollow')) {
            $rows[] = [
                sprintf(
                /* translators: %s: rel="nofollow" */
                    esc_html__('Use %s for links', 'luckywp-table-of-contents'),
                    'rel="nofollow"'
                ),
                $v ? __('Enabled', 'luckywp-table-of-contents') : __('Disabled', 'luckywp-table-of-contents')
            ];
        }
        if (null !== $v = $getValue($source, 'skipHeadingLevel')) {
            $rows[] = [esc_html__('Skip headings', 'luckywp-table-of-contents'), Core::$plugin->skipHeadingLevelToLabel($v)];
        }
        if (null !== $v = $getValue($source, 'skipHeadingText')) {
            $rows[] = [esc_html__('Skip headings', 'luckywp-table-of-contents'), $v == '' ? null : $v];
        }
        if (null !== $v = $getValue($source, 'containerClass')) {
            $rows[] = [esc_html__('Additional CSS Class(es)', 'luckywp-table-of-contents'), $v == '' ? null : $v];
        }
        return array_map(function ($row) {
            if ($row[1] !== null && ArrayHelper::getValue($row, 2, true) === true) {
                $row[1] = esc_html($row[1]);
            }
            return $row;
        }, $rows);
    }
}
