<?php

namespace luckywp\tableOfContents\admin\controllers;

use luckywp\tableOfContents\admin\forms\CustomizeForm;
use luckywp\tableOfContents\admin\widgets\customizeModal\CustomizeModal;
use luckywp\tableOfContents\admin\widgets\customizeSuccess\CustomizeSuccess;
use luckywp\tableOfContents\admin\widgets\widget\Widget;
use luckywp\tableOfContents\core\admin\AdminController;
use luckywp\tableOfContents\core\Core;
use luckywp\tableOfContents\core\helpers\Json;
use luckywp\tableOfContents\core\helpers\ValueHelper;
use luckywp\tableOfContents\plugin\Shortcode;

class WidgetController extends AdminController
{

    public function init()
    {
        parent::init();
        add_action('plugins_loaded', [$this, 'initAjax']);
    }

    public function initAjax()
    {
        add_action('wp_ajax_lwptoc_widget_customize', [$this, 'ajaxCustomize']);
    }

    protected function checkAccess()
    {
        Core::$plugin->admin->checkAjaxReferer();
    }

    public function ajaxCustomize()
    {
        $this->checkAccess();

        $value = (string)Core::$plugin->request->get('value');
        $widgetId = (string)Core::$plugin->request->get('widgetId');

        $attrs = Shortcode::attrsFromJson($value);

        $onlyBody = false;

        $model = new CustomizeForm(null, $attrs);
        if ($model->load(Core::$plugin->request->post())) {
            if ($model->validate()) {
                $attrs = array_filter($model->getAttrs(), function ($v) {
                    return $v !== null;
                });
                echo CustomizeSuccess::widget([
                    'after' => '<script>jQuery(document).trigger("lwptocWidgetCustomized", ' . Json::encode([
                            'id' => $widgetId,
                            'override' => Widget::overrideHtml($attrs),
                            'value' => Json::encode($attrs),
                        ]) . ')</script>',
                ]);
                wp_die();
            }
            $onlyBody = true;
        }

        $config = apply_filters('lwptoc_widget_customize_modal_config', []);
        $config = array_merge(ValueHelper::assertArray($config), [
            'onlyBody' => $onlyBody,
            'action' => 'lwptoc_widget_customize',
            'widgetId' => $widgetId,
            'model' => $model,
        ]);

        echo CustomizeModal::widget($config);
        wp_die();
    }
}
