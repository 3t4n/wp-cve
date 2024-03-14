<?php

namespace OXIIMAEADDONS\Helper;

/**
 *
 * @author biplo
 */
trait Categories {

    /**
     * Register Widget Category
     *
     * @since v1.0.0
     */
    public function register_widget_categories($elements_manager) {

        $elements_manager->add_category(
                'oxi-h-effects-addons', [
                'title' => esc_html__('Hover Effects Ultimate', 'oxi-hover-effects-addons'),
                'icon'  => 'fa fa-plug',
                ], 1,
        );
    }

    /**
     * Register widgets
     *
     * @since v3.0.0
     */
    public function register_elements($widgets_manager) {
        if (apply_filters('oxi-hover-effects-addons-version', false) != true) :
            $elements = [
                    '\OXIIMAEADDONS\Modules\Flipbox\Data',
                    '\OXIIMAEADDONS\Modules\Image\Data',
                    '\OXIIMAEADDONS\Modules\Caption\Data',
            ];
        else:
            $elements = [
                    '\OXIIMAEADDONS\Modules\Flipbox\Flipbox',
                    '\OXIIMAEADDONS\Modules\Image\Image',
                    '\OXIIMAEADDONS\Modules\Caption\Caption',
            ];
        endif;

        foreach ($elements as $active_element) {
            if (class_exists($active_element)):
                $widgets_manager->register(new $active_element);
            endif;
        }
    }

}
