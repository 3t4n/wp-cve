<?php

namespace luckywp\tableOfContents\admin\controllers;

use luckywp\tableOfContents\admin\forms\CustomizeForm;
use luckywp\tableOfContents\admin\widgets\customizeModal\CustomizeModal;
use luckywp\tableOfContents\admin\widgets\customizeSuccess\CustomizeSuccess;
use luckywp\tableOfContents\core\admin\AdminController;
use luckywp\tableOfContents\core\Core;
use luckywp\tableOfContents\core\helpers\Json;
use luckywp\tableOfContents\plugin\PostSettings;

class ShortcodeController extends AdminController
{

    public function init()
    {
        parent::init();
        add_action('plugins_loaded', [$this, 'initAjax']);
    }

    public function initAjax()
    {
        add_action('wp_ajax_lwptoc_shortcode_customize', [$this, 'ajaxCustomize']);
        add_action('wp_ajax_lwptoc_shortcode_view', [$this, 'ajaxView']);
    }

    public function ajaxCustomize()
    {
        Core::$plugin->admin->checkAjaxReferer();
        $post = get_post((int)Core::$plugin->request->get('postId'));
        $onlyBody = false;

        $postSettings = new PostSettings($post->ID);
        $model = new CustomizeForm($postSettings, $this->getAttrsFromRequest());
        if ($model->load(Core::$plugin->request->post())) {
            if ($model->validate()) {
                echo CustomizeSuccess::widget([
                    'after' => '<script>jQuery(document).trigger("lwptocShortcodeGenerated", ' . Json::encode(['shortcode' => $model->generateShortcode()]) . ');</script>',
                ]);
                wp_die();
            }
            $onlyBody = true;
        }

        echo CustomizeModal::widget([
            'onlyBody' => $onlyBody,
            'post' => $post,
            'action' => 'lwptoc_shortcode_customize',
            'model' => $model,
        ]);
        wp_die();
    }

    public function ajaxView()
    {
        Core::$plugin->admin->checkAjaxReferer();
        $rows = Core::$plugin->admin->overrideSettingsToRows($this->getAttrsFromRequest());
        echo '<div class="lwptocShortcode">';
        echo '<div class="lwptocShortcode_title">' . esc_html__('Table of Contents', 'luckywp-table-of-contents') . '</div>';
        if ($rows) {
            echo '<div class="lwptocShortcode_items">';
            foreach ($rows as $row) {
                echo '<div class="lwptocShortcode_item">';
                echo '<span class="lwptocShortcode_item_label">' . esc_html($row[0]) . ':</span> ';
                echo $row[1] === null ? '<i>' . __('empty', 'luckywp-table-of-contents') . '</i>' : $row[1];
                echo '</div>';
            }
            echo '</div>';
        }
        echo '</div>';
        wp_die();
    }

    /**
     * @return array
     */
    protected function getAttrsFromRequest()
    {
        $attrs = Core::$plugin->request->get('attrs');
        if (!is_array($attrs)) {
            $attrs = [];
        }
        return wp_unslash($attrs);
    }
}
