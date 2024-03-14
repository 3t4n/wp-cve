<?php

namespace luckywp\glossary\admin;

use luckywp\glossary\admin\controllers\MbTermSynonymsController;
use luckywp\glossary\admin\controllers\SettingsController;
use luckywp\glossary\admin\widgets\termSynonymsMetabox\TermSynonymsMetabox;
use luckywp\glossary\core\admin\helpers\AdminHtml;
use luckywp\glossary\core\admin\helpers\AdminUrl;
use luckywp\glossary\core\base\BaseObject;
use luckywp\glossary\core\Core;
use luckywp\glossary\core\helpers\Html;
use luckywp\glossary\plugin\Term;
use WP_Post;

/**
 * @property bool $isEditArchivePage
 * @property string $assetsUrl
 */
class Admin extends BaseObject
{

    protected $pageSettingsHook;

    public function init()
    {
        if (is_admin()) {
            add_action('admin_menu', [$this, 'menu']);
            add_action('add_meta_boxes', [$this, 'addMetaBoxes']);
            add_action('admin_notices', [$this, 'notices']);
            add_action('admin_enqueue_scripts', [$this, 'assets']);

            // Ссылки в списке плагинов
            add_filter('plugin_action_links_' . Core::$plugin->basename, function ($links) {
                $links[] = Html::a(esc_html__('Settings', 'luckywp-glossary'), AdminUrl::byPostTypeTo(Term::POST_TYPE, 'settings'));
                return $links;
            });

            // Статус старницы с архивом новостей в списке страниц в панели управления
            add_filter('display_post_states', function ($postStates, $post) {
                if ($post->ID == Core::$plugin->archivePageId) {
                    $postStates[Core::$plugin->prefix . '_archive_page'] = esc_html__('Glossary Page', 'luckywp-glossary');
                }
                return $postStates;
            }, 10, 2);

            // Количество записей
            add_action('publish_' . Term::POST_TYPE, function ($postId, $post) {
                if (wp_count_posts(Term::POST_TYPE)->publish > 30) {
                    /** @var WP_Post $post */
                    $post->post_status = 'draft';
                    wp_update_post($post);
                    Core::$plugin->options->setForUser('noticeLimitTerms', true);
                }
            }, 10, 2);
           add_action('admin_notices', function () {
                if (Core::$plugin->options->getForUser('noticeLimitTerms', false)) {
                    Core::$plugin->options->deleteForUser('noticeLimitTerms');
                    ?>
                    <div class="notice notice-error  is-dismissible">
                        <p>
                            <?= sprintf(
                            /* translators: %d: count of terms %s: "premium version" */
                                esc_html__('Adding more than %d terms is available in the %s', 'luckywp-glossary'),
                                30,
                                Html::a('<b>' . esc_html__('premium version', 'luckywp-glossary') . '</b>', Core::$plugin->buyUrl, ['target' => '_blank'])
                            ) ?>
                        </p>
                    </div>
                    <?php
                }
            });

            // Инициализация контроллеров
            MbTermSynonymsController::getInstance();
        }
    }

    public function menu()
    {
        $this->pageSettingsHook = add_submenu_page(
            'edit.php?post_type=' . Term::POST_TYPE,
            esc_html__('Glossary Settings', 'luckywp-glossary'),
            esc_html__('Settings', 'luckywp-glossary'),
            'manage_options',
            Core::$plugin->prefix . 'settings',
            [SettingsController::class, 'router']
        );
    }

    public function addMetaBoxes()
    {
        if (current_user_can('edit_posts')) {
            add_meta_box(
                Core::$plugin->prefix . '_termSynonyms',
                esc_html__('Synonyms', 'luckywp-glossary'),
                function ($post) {
                    echo TermSynonymsMetabox::widget([
                        'post' => $post,
                    ]);
                },
                Term::POST_TYPE,
                'normal',
                'high'
            );
        }
    }

    public function getIsEditArchivePage()
    {
        global $pagenow;
        $archivePageId = Core::$plugin->archivePageId;
        return $pagenow == 'post.php' &&
            $archivePageId &&
            Core::$plugin->request->get('post') == $archivePageId &&
            Core::$plugin->request->get('action') == 'edit';
    }

    public function notices()
    {
        if (!current_user_can('manage_options') || AdminUrl::isPage('settings', 'autoArchivePage')) {
            return;
        }
        if (
            !Core::$plugin->settings->getValue('misc', 'no_check_terms_archive_shortcode', false) &&
            ($page = Core::$plugin->archivePage) &&
            !has_shortcode($page->post_content, 'lwpglsTermsArchive')
        ) {
            ?>
            <div class="notice notice-error">
                <p>
                    <?php
                    printf(
                    /* translators: %s: [lwpglsTermsArchive] */
                        esc_html__('The shortcode %s should be added to the glossary archive page', 'luckywp-glossary'),
                        '<b>[lwpglsTermsArchive]</b>'
                    );
                    ?>
                </p>
                <p>
                    <?= AdminHtml::buttonLink(esc_html__('Edit Archive Page', 'luckywp-glossary'), get_edit_post_link($page, null)) ?>
                    &nbsp;
                    &nbsp;
                    <?= AdminHtml::buttonLink(
                        esc_html__('Disable this notification in settings', 'luckywp-glossary'),
                        'edit.php?post_type=' . Term::POST_TYPE . '&page=lwpgls_settings&tab=misc',
                        ['theme' => AdminHtml::BUTTON_THEME_LINK]
                    ) ?>
                </p>
            </div>
            <?php
        }
        if (!Core::$plugin->archivePage) {
            ?>
            <div class="notice notice-error">
                <p>
                    <?= esc_html__('The archive page should be configured for the glossary.', 'luckywp-glossary') ?><br>
                    <b><?= esc_html__('We recommend to automatically configure the initial setting.', 'luckywp-glossary') ?></b>
                </p>
                <p>
                    <?= AdminHtml::buttonLink(esc_html__('Automatic configuration', 'luckywp-glossary'), AdminUrl::to('settings', 'autoArchivePage'), [
                        'theme' => AdminHtml::BUTTON_THEME_PRIMARY
                    ]) ?>
                    &nbsp;
                    &nbsp;
                    <?= AdminHtml::buttonLink(esc_html__('Manual configuration', 'luckywp-glossary'), AdminUrl::byPostTypeTo(Term::POST_TYPE, 'settings'), [
                        'theme' => AdminHtml::BUTTON_THEME_LINK,
                        'attrs' => [
                            'style' => 'color:#aaa',
                        ],
                    ]) ?>
                </p>
            </div>
            <?php
        }
    }

    public function getAssetsUrl()
    {
        return Core::$plugin->url . '/admin/assets';
    }

    public function assets($hook)
    {
        if ($hook == $this->pageSettingsHook) {
            wp_enqueue_style(Core::$plugin->prefix . 'adminMain', $this->assetsUrl . '/main.min.css', [], Core::$plugin->version);
        }
    }
}
