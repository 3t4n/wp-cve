<?php

namespace luckywp\tableOfContents\admin\controllers;

use luckywp\tableOfContents\admin\forms\CustomizeForm;
use luckywp\tableOfContents\admin\widgets\customizeModal\CustomizeModal;
use luckywp\tableOfContents\admin\widgets\customizeSuccess\CustomizeSuccess;
use luckywp\tableOfContents\core\admin\AdminController;
use luckywp\tableOfContents\core\Core;
use luckywp\tableOfContents\core\helpers\Json;
use luckywp\tableOfContents\plugin\PostSettings;

class EditorBlockController extends AdminController
{

    public function init()
    {
        parent::init();
        add_action('plugins_loaded', [$this, 'initAjax']);
    }

    public function initAjax()
    {
        add_action('wp_ajax_lwptoc_block_edit', [$this, 'ajaxEdit']);
        add_action('wp_ajax_lwptoc_block_view', [$this, 'ajaxView']);
    }

    public function ajaxEdit()
    {
        $post = get_post((int)Core::$plugin->request->get('postId'));
        $attrs = Core::$plugin->request->get('attrs');
        if (!is_array($attrs)) {
            $attrs = [];
        }
        $onlyBody = false;

        $postSettings = new PostSettings($post->ID);
        $model = new CustomizeForm($postSettings, $attrs);
        if ($model->load(Core::$plugin->request->post())) {
            if ($model->validate()) {
                echo CustomizeSuccess::widget([
                    'after' => '<script>jQuery(document).trigger("lwptocEditorBlockChanged", ' . Json::encode($model->getAttrs()) . ');</script>',
                ]);
                wp_die();
            }
            $onlyBody = true;
        }

        echo CustomizeModal::widget([
            'onlyBody' => $onlyBody,
            'post' => $post,
            'action' => 'lwptoc_block_edit',
            'model' => $model,
        ]);
        wp_die();
    }

    public function ajaxView()
    {
        $attrs = Core::$plugin->request->get('attrs');
        if (!is_array($attrs)) {
            $attrs = [];
        }
        $rows = Core::$plugin->admin->overrideSettingsToRows($attrs);
        echo '<div class="lwptocEditorBlock_title">' . esc_html__('Table of Contents', 'luckywp-table-of-contents') . '</div>';
        if ($rows) {
            echo '<div class="lwptocEditorBlock_items">';
            foreach ($rows as $row) {
                echo '<div class="lwptocEditorBlock_item">';
                echo '<span class="lwptocEditorBlock_item_label">' . $row[0] . ':</span> ';
                echo $row[1] === null ? '<i>' . __('empty', 'luckywp-table-of-contents') . '</i>' : $row[1];
                echo '</div>';
            }
            echo '</div>';
        }
        wp_die();
    }
}
