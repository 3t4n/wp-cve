<?php

namespace luckywp\tableOfContents\integrations;

use luckywp\tableOfContents\core\base\BaseObject;
use luckywp\tableOfContents\core\helpers\ArrayHelper;
use luckywp\tableOfContents\plugin\Shortcode;
use luckywp\tableOfContents\plugin\WpWidget;

class Wpml extends BaseObject
{

    const CONTEXT = 'LuckyWP Table of Contents';

    public function init()
    {
        add_filter('lwptoc_widget_attrs', [$this, 'widgetAttrs'], 10, 2);
        add_action('lwptoc_widget_attrs_update', [$this, 'widgetAttrsUpdate'], 10, 3);
        add_action('delete_widget', [$this, 'widgetDelete'], 10, 3);
    }

    /**
     * @param array $attrs
     * @param WpWidget $widget
     * @return array
     */
    public function widgetAttrs($attrs, $widget)
    {
        foreach ($this->getAttrs() as $key => $label) {
            if (isset($attrs[$key])) {
                $attrs[$key] = apply_filters(
                    'wpml_translate_single_string',
                    $attrs[$key],
                    Wpml::CONTEXT,
                    $this->getWidgetName($widget->number, $label)
                );
            }
        }
        return $attrs;
    }

    /**
     * @param array $newInstance
     * @param array $oldInstance
     * @param WpWidget $widget
     */
    public function widgetAttrsUpdate($newInstance, $oldInstance, $widget)
    {
        $newAttrs = Shortcode::attrsFromJson(ArrayHelper::getValue($newInstance, 'config', ''));
        $oldAttrs = Shortcode::attrsFromJson(ArrayHelper::getValue($oldInstance, 'config', ''));

        // Удалить старые значения из переводов
        if (function_exists('icl_unregister_string')) {
            foreach ($this->getAttrs() as $key => $label) {
                if (array_key_exists($key, $oldAttrs) && !isset($newAttrs[$key])) {
                    icl_unregister_string(Wpml::CONTEXT, $this->getWidgetName($widget->number, $label));
                }
            }
        }

        // Добавить новые значения
        foreach ($this->getAttrs() as $key => $label) {
            if (isset($newAttrs[$key])) {
                do_action('wpml_register_single_string', Wpml::CONTEXT, $this->getWidgetName($widget->number, $label), $newAttrs[$key]);
            }
        }
    }

    /**
     * @param string $widgetId
     * @param string $sidebarId
     * @param string $idBase
     */
    public function widgetDelete($widgetId, $sidebarId, $idBase)
    {
        if (function_exists('icl_unregister_string')) {
            if ($idBase == WpWidget::ID_BASE) {
                $number = str_replace($idBase . '-', '', $widgetId);
                foreach ($this->getAttrs() as $label) {
                    icl_unregister_string(Wpml::CONTEXT, $this->getWidgetName($number, $label));
                }
            }
        }
    }

    /**
     * @param string $number
     * @param string $caption
     * @return string
     */
    protected function getWidgetName($number, $caption)
    {
        return 'Widget #' . $number . ': ' . $caption;
    }

    /**
     * @return array
     */
    protected function getAttrs()
    {
        return [
            'title' => 'Title',
            'labelShow' => 'Label Show',
            'labelHide' => 'Label Hide'
        ];
    }
}
