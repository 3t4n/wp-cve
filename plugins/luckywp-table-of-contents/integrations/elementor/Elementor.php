<?php

namespace luckywp\tableOfContents\integrations\elementor;

use luckywp\tableOfContents\core\admin\helpers\AdminHtml;
use luckywp\tableOfContents\core\base\BaseObject;
use luckywp\tableOfContents\core\Core;
use luckywp\tableOfContents\core\helpers\ArrayHelper;

/**
 * @see https://developers.elementor.com/
 */
class Elementor extends BaseObject
{

    public function init()
    {
        add_action('elementor/editor/after_enqueue_scripts', function () {
            Core::$plugin->admin->enqueueAssets([
                'enqueueWpColorPicker' => true,
            ]);
            wp_enqueue_script(Core::$plugin->prefix . 'elementor', Core::$plugin->url . '/integrations/elementor/assets/editor.min.js', [Core::$plugin->prefix . 'adminMain'], Core::$plugin->version);
        });
        add_filter('lwptoc_widget_customize_modal_config', function ($config) {
            if (Core::$plugin->request->get('extra.isElementorEditor', false)) {
                $config['context'] = 'elementor';
            }
            return $config;
        });
        add_filter('lwptoc_admin_html_button_attrs', function ($attrs, $options) {
            if (ArrayHelper::getValue($options, 'context') === 'elementor') {
                $theme = isset($options['theme']) ? $options['theme'] : false;

                $attrs['class'][] = 'elementor-button';
                $attrs['class'][] = 'elementor-button-default';
                ArrayHelper::remove($attrs['class'], 'button');

                if ($theme == AdminHtml::BUTTON_THEME_PRIMARY) {
                    $attrs['class'][] = 'elementor-button-success';
                    ArrayHelper::remove($attrs['class'], 'button-primary');
                }
            }
            return $attrs;
        }, 10, 2);
    }
}
