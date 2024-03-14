<?php
defined( 'ABSPATH' ) or die( 'Keep Quit' );

if(!function_exists('save_custom_pc_builder_lite')) {
    add_action('wp_ajax_save_custom_pc_builder_lite', 'save_custom_pc_builder_lite');
    add_action('wp_ajax_nopriv_save_custom_pc_builder_lite', 'save_custom_pc_builder_lite');
    function save_custom_pc_builder_lite()
    {
        try {
            if (empty($_POST['data'])) {
                throw new Exception('Something went wrong');
            }
            $data = json_decode(preg_replace('/\\\"/', "\"", sanitize_text_field($_POST['data'])), true);
            foreach ($data as $d) {
                if ($d['id'] == '' || $d['id'] == 0) throw new Exception('Data is empty');
                if (!get_term($d['id'])) throw new Exception('Category does not exists');
            }
            $data = custom_pc_builder_lite_unique_multidim_array($data, 'id');
            update_option('nk_custom_pc_builder', serialize($data));
            echo wp_json_encode(['err' => 0, 'message' => __('Update successfully', 'nk-custom-pc-builder')]);

        } catch (Exception $ex) {
            echo wp_json_encode(['err' => 1, 'message' => __($ex->getMessage(), 'nk-custom-pc-builder')]);
        }
        wp_die();
    }
}
