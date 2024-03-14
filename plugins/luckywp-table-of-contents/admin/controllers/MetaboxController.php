<?php

namespace luckywp\tableOfContents\admin\controllers;

use luckywp\tableOfContents\admin\forms\CustomizeForm;
use luckywp\tableOfContents\admin\widgets\customizeModal\CustomizeModal;
use luckywp\tableOfContents\admin\widgets\customizeSuccess\CustomizeSuccess;
use luckywp\tableOfContents\admin\widgets\metabox\Metabox;
use luckywp\tableOfContents\core\admin\AdminController;
use luckywp\tableOfContents\core\Core;
use luckywp\tableOfContents\core\helpers\Json;
use luckywp\tableOfContents\plugin\PostSettings;

class MetaboxController extends AdminController
{

    public function init()
    {
        parent::init();
        add_action('plugins_loaded', [$this, 'initAjax']);
    }

    public function initAjax()
    {
        add_action('wp_ajax_lwptoc_metabox_set_enabled', [$this, 'ajaxSetEnabled']);
        add_action('wp_ajax_lwptoc_metabox_set_processing', [$this, 'ajaxSetProcessing']);
        add_action('wp_ajax_lwptoc_metabox_customize', [$this, 'ajaxCustomize']);
    }

    protected function checkAccess($post)
    {
        Core::$plugin->admin->checkAjaxReferer();
        if (!$post ||
            !current_user_can('edit_post', $post->ID) ||
            !in_array($post->post_type, Core::$plugin->admin->getMetaboxPostTypes())
        ) {
            $this->notAllowed();
        }
    }

    public function ajaxSetEnabled()
    {
        $post = get_post((int)Core::$plugin->request->get('postId'));
        $this->checkAccess($post);

        $settings = new PostSettings($post->ID);
        $settings->enabled = (bool)Core::$plugin->request->get('enabled', true);
        $settings->save();

        echo Metabox::widget(['post' => $post]);
        wp_die();
    }

    public function ajaxSetProcessing()
    {
        $post = get_post((int)Core::$plugin->request->get('postId'));
        $this->checkAccess($post);

        $settings = new PostSettings($post->ID);
        $settings->processHeadings = (bool)Core::$plugin->request->get('enabled', true);
        $settings->save();

        echo Metabox::widget(['post' => $post]);
        wp_die();
    }

    public function ajaxCustomize()
    {
        $post = get_post((int)Core::$plugin->request->get('postId'));
        $this->checkAccess($post);
        $onlyBody = false;

        $postSettings = new PostSettings($post->ID);
        $model = new CustomizeForm($postSettings);
        if ($model->load(Core::$plugin->request->post())) {
            if ($model->validate()) {
                $model->toPostSettings($postSettings);
                $postSettings->save();
                echo CustomizeSuccess::widget([
                    'after' => '<script>jQuery(document).trigger("lwptocMetaboxCustomized", ' . Json::encode([
                            'metabox' => Metabox::widget(['post' => $post]),
                        ]) . ');</script>',
                ]);
                wp_die();
            }
            $onlyBody = true;
        }

        echo CustomizeModal::widget([
            'onlyBody' => $onlyBody,
            'post' => $post,
            'action' => 'lwptoc_metabox_customize',
            'model' => $model,
        ]);
        wp_die();
    }
}
