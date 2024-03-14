<?php

use Elementor\Plugin;
use Elementor\Controls_Stack;
use Elementor\Utils;

if (!class_exists('LiveCopyPasteBtn')) {
    class LiveCopyPasteBtn {

        public function __construct() {
            add_action('wp_ajax_bdt_elementor_import_live_copy_assets_files', [$this, 'ajax_import_data']);
            add_action('elementor/editor/after_enqueue_scripts', [$this, 'live_copy_enqueue']);
            add_action('wp_enqueue_scripts', array($this, 'enqueue'));
        }

        public function live_copy_enqueue() {
            wp_enqueue_script('live-copy-storage-js', BDT_LCP_DIR_URL . 'assets/js/xdLocalStorage.js', [], BDT_LCP_VER, true);
            wp_enqueue_script('live-copy-scripts-js', BDT_LCP_DIR_URL . 'assets/js/live-copy-paste.js', ['jquery', 'elementor-editor', 'live-copy-storage-js', 'elementor-editor'], BDT_LCP_VER, true);
            wp_localize_script(
                'live-copy-scripts-js',
                'bdt_live_copy',
                [
                    'magic_key' => 'magic_copy_data',
                    'ajax_url'  => admin_url('admin-ajax.php'),
                    'nonce'     => wp_create_nonce('magic_copy_data'),
                ]
            );
        }
        public function ajax_import_data() {
            $nonce = isset($_REQUEST['security']) ? $_REQUEST['security'] : '';
            $data  = isset($_REQUEST['data']) ? wp_unslash(sanitize_text_field($_REQUEST['data'])) : '';

            if (!wp_verify_nonce($nonce, 'magic_copy_data') || empty($data)) {
                wp_send_json_error(__('Sorry, invalid nonce or empty content!', 'live-copy-paste'));
            }

            $data = [json_decode($data, true)];

            $data = $this->ready_for_import($data);
            $data = $this->import_content($data);

            wp_send_json_success($data);
        }

        protected function process_import_content(Controls_Stack $element) {
            $element_data = $element->get_data();
            $method       = 'on_import';

            if (method_exists($element, $method)) {
                $element_data = $element->{$method}($element_data);
            }

            foreach ($element->get_controls() as $control) {
                $control_class = Plugin::instance()->controls_manager->get_control($control['type']);

                if (!$control_class) {
                    return $element_data;
                }

                if (method_exists($control_class, $method)) {
                    $element_data['settings'][$control['name']] = $control_class->{$method}($element->get_settings($control['name']), $control);
                }
            }

            return $element_data;
        }

        protected function ready_for_import($content) {
            return Plugin::instance()->db->iterate_data($content, function ($element) {
                $element['id'] = Utils::generate_random_string();
                return $element;
            });
        }

        protected function import_content($content) {
            return Plugin::instance()->db->iterate_data(
                $content,
                function ($element_data) {
                    $element = Plugin::instance()->elements_manager->create_element_instance($element_data);

                    if (!$element) {
                        return null;
                    }

                    return $this->process_import_content($element);
                }
            );
        }
        public function enqueue() {
            $params = array(
                'post_id'       => get_the_ID(),
                'ajax_url'      => admin_url('admin-ajax.php'),
                'ajax_nonce'    => wp_create_nonce('live-copy-paste-magic-nonce'),
            );
            wp_localize_script('jquery', 'bdthemes_magic_copy_ajax', $params);
        }
    }
}

new LiveCopyPasteBtn();
