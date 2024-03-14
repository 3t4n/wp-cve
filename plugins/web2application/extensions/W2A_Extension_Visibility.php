<?php

namespace DynamicContentForElementor\Extensions;

use Elementor\Controls_Manager;
use DynamicContentForElementor\W2A_Helper;

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

/**
 * Visibility extenstion
 *
 * Conditional Visibility Widgets & Rows/Sections
 *
 * @since 1.0.1
 */
class W2A_Extension_Visibility extends W2A_Extension_Prototype {

    public $name = 'Web2App';
    public $common_sections_actions = array(
        array(
            'element' => 'common',
            'action' => '_section_style',
        ),
        array(
            'element' => 'section',
            'action' => 'section_advanced',
        )
    );
    public static $tabs = [];
    
    /**
     * The description of the current extension
     *
     * @since 0.5.4
     * */
    public static function get_description() {
        return __('Visibility rules for Widgets and Rows');
    }

    /**
     * Add Actions
     *
     * @since 0.5.5
     *
     * @access private
     */
    protected function add_actions() {

        // Activate controls for widgets
        add_action('elementor/element/common/w2a_section_web2app_advanced/before_section_end', function( $element, $args ) {
            $this->add_controls($element, $args);
        }, 10, 2);
        foreach (self::$tabs as $tkey => $tvalue) {
            // Activate controls for widgets
            add_action('elementor/element/common/w2a_section_web2app_' . $tkey . '/before_section_end', function( $element, $args ) use ($tkey) {
                $args['section'] = $tkey;
                $this->add_controls($element, $args);
            }, 10, 2);
        }

        add_filter('elementor/widget/render_content', array($this, 'visibility_render_widget'), 10, 2);
        add_action("elementor/frontend/widget/before_render", function( $element ) {
            $settings = $element->get_settings();
            if (isset($settings['enabled_visibility']) && $settings['enabled_visibility']) {
                if ($this->is_hidden($element)) {
					echo '<!--w2a VISIBILITY HIDDEN SECTION START-->';
                    $element->add_render_attribute('_wrapper', 'class', 'w2a-visibility-section-hidden');
                    $element->add_render_attribute('_wrapper', 'class', 'w2a-visibility-original-content');
                }
            }
        }, 10, 1);


        // Activate controls for sections
        add_action('elementor/element/section/w2a_section_web2app_advanced/before_section_end', function( $element, $args ) {
            $this->add_controls($element, $args);
        }, 10, 2);
        foreach (self::$tabs as $tkey => $tvalue) {
            // Activate controls for widgets
            add_action('elementor/element/section/w2a_section_web2app_' . $tkey . '/before_section_end', function( $element, $args ) use ($tkey) {
                $args['section'] = $tkey;
                $this->add_controls($element, $args);
            }, 10, 2);
        }
        add_action('elementor/frontend/section/before_render', function( $element ) {
            $element_type = $element->get_type();
            $element_name = $element->get_unique_name();
            $element_id = $element->get_id();
            $settings = $element->get_settings();
            if (isset($settings['enabled_visibility']) && $settings['enabled_visibility']) {
                if ($this->is_hidden($element)) {
                    echo '<!--w2a VISIBILITY HIDDEN SECTION START-->';
                    $element->add_render_attribute('_wrapper', 'class', 'w2a-visibility-section-hidden');
                    $element->add_render_attribute('_wrapper', 'class', 'w2a-visibility-original-content');
                }
            }
        }, 10, 1);


        // filter sections
        add_action("elementor/frontend/section/before_render", function( $element ) {
            $settings = $element->get_settings();
            if (isset($settings['enabled_visibility']) && $settings['enabled_visibility']) {
                if ($this->is_hidden($element)) {
                    echo '<!--w2a VISIBILITY HIDDEN SECTION START-->';
                    $element->add_render_attribute('_wrapper', 'class', 'w2a-visibility-section-hidden');
                    $element->add_render_attribute('_wrapper', 'class', 'w2a-visibility-original-content');
                }
            }
        }, 10, 1);
        add_action("elementor/frontend/section/after_render", function( $element ) {
            $settings = $element->get_settings();
            $content = '';
            if (isset($settings['enabled_visibility']) && $settings['enabled_visibility']) {
                if ($this->is_hidden($element)) {
                    $this->print_conditions($element);
                    echo '<!--w2a VISIBILITY HIDDEN SECTION END-->';
					$element->add_render_attribute('_wrapper', 'class', 'w2a-visibility-section-hidden');
                    $element->add_render_attribute('_wrapper', 'class', 'w2a-visibility-original-content');
                }
            }
        }, 10, 1);
    }

    /**
     * Add Controls
     *
     * @since 0.5.5
     *
     * @access private
     */
    private function add_controls($element, $args) {

        if (isset($args['section'])) {
            $section = $args['section'];
        } else {
            $section = 'advanced';
        }

        $element_type = $element->get_type();

        if ($section == 'advanced') {

            $element->add_control(
                    'enabled_visibility', [
                    'label' => __('Hide in iOS or Android App', W2A_TEXTDOMAIN),
                    'type' => Controls_Manager::SWITCHER,
                    'frontend_available' => true,
                ]
            );
        }
    }

    public function visibility_print_widget($content, $widget) {
        if (!$content)
            return '';

        $notice = '<div class="w2a-visibility-warning"><i class="fa fa-eye-slash"></i> Hidden</div>'; // hide widget
        $content = "<# if ( '' !== settings.enabled_visibility ) { if ( '' !== settings.w2a_visibility_hidden ) { #>" . $notice . "<# } #><div class=\"w2a-visibility-hidden-outline\">" . $content . "</div><# } else { #>" . $content . "<# } #>";
        return $content;
    }

    public function visibility_render_widget($content, $widget) {
        $settings = $widget->get_settings();

        if (isset($settings['enabled_visibility']) && $settings['enabled_visibility']) {
            $hidden = $this->is_hidden($widget);
            if ($hidden) {
                $this->print_conditions($widget);
            }

            // show element in backend
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                $notice = '';
                if ($hidden) {
                    $widget->add_render_attribute('_wrapper', 'class', 'w2a-visibility-hidden');
                    $notice = '<div class="w2a-visibility-warning"><i class="fa fa-eye-slash"></i> Hidden</div>'; // hide widget
                }
                return $content;
            }
        } 
        return $content; // show widget
    }

    public function is_hidden($element = null, $why = false) {
        $settings = $element->get_settings();

        $hidden = FALSE;
        $conditions = array();

        if (isset($settings['enabled_visibility']) && $settings['enabled_visibility']) {

            $isAndroidApp = (strpos($_SERVER['HTTP_X_REQUESTED_WITH'], 'web2application') !== false);
            $isIOSApp = (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile/') !== false) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari/') == false);

            // FORCED HIDDEN IF IN-APP
        //    if($isIOSApp || $isAndroidApp) {
			if ($_GET['dev'] != "") {	
                $conditions['enabled_visibility'] = __('Always Hidden', W2A_TEXTDOMAIN);
                $hidden = TRUE;
			//	echo 'this object is hidden ';
            } else {
			//	echo 'not inside app';
			}

            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                $hidden = TRUE;
            }
        }
        
        $triggered = false;
        if (!empty($conditions)) {
            $triggered = true;
        }

        $shidden = false; //$settings['enabled_visibility'];

        if (self::check_visibility_condition($triggered, $shidden)) {
            $hidden = TRUE;
        }

        if ($why) {
            return $conditions;
        }

        return $hidden;
    }

    static public function check_visibility_condition($condition, $visibility) {
        $ret = $condition;
        if ($visibility) {
            if ($condition) {
                $ret = false; // show widget
            } else {
                $ret = true; // hide widget
            }
        } else {
            if ($condition) {
                $ret = true; // hide widget
            } else {
                $ret = false; // show widget
            }
        }
        return $ret;
    }

    public function print_conditions($element, $settings = null) {
        if (WP_DEBUG && !\Elementor\Plugin::$instance->editor->is_edit_mode()) {
            if (empty($settings)) {
                $settings = $element->get_settings();
            }
            if ($settings['w2a_visibility_debug']) {
                $conditions = $this->is_hidden($element, true);   
                if (!empty($conditions)) {
                    echo '<a onClick="jQuery(this).next().fadeToggle(); return false;" href="#box-visibility-debug-' . $element->get_ID() . '" class="w2a-btn-visibility w2a-btn-visibility-debug"><i class="w2a-icon-visibility fa fa fa-eye exclamation-triangle" aria-hidden="true"></i></a>';
                    echo '<div id="#box-visibility-debug-' . $element->get_ID() . '" class="w2a-box-visibility-debug"><ul>';
                    foreach ($conditions as $key => $value) {
                        echo '<li>';
                        echo $value;
                        if (isset($settings[$key])) {
                            echo ': ';
                            if (is_array($settings[$key])) {
                                echo implode(', ', $settings[$key]);
                            } else {
                                echo print_r($settings[$key], true);
                            }
                        }
                        echo '</li>';
                    }
                    echo '</ul></div>';
                }
            }
        }
    }

}
