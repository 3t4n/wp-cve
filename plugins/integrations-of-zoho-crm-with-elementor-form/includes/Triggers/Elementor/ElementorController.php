<?php
namespace FormInteg\IZCRMEF\Triggers\Elementor;

use FormInteg\IZCRMEF\Flow\Flow;

final class ElementorController
{
    public static function info()
    {
        //$plugin_path = 'elementor-pro/elementor-pro.php';
        $plugin_path = self::pluginActive('get_name');
        return [
            'name' => 'Elementor',
            'title' => 'Elementor is the platform web creators choose to build professional WordPress websites, grow their skills, and build their business. Start for free today!',
            'icon_url' => 'https://ps.w.org/elementor/assets/icon.svg?rev=2597493',
            'slug' => $plugin_path,
            'pro' => $plugin_path,
            'type' => 'form',
            'is_active' => is_plugin_active($plugin_path),
            'activation_url' => wp_nonce_url(self_admin_url('plugins.php?action=activate&amp;plugin=' . $plugin_path . '&amp;plugin_status=all&amp;paged=1&amp;s'), 'activate-plugin_' . $plugin_path),
            'install_url' => wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=' . $plugin_path), 'install-plugin_' . $plugin_path),
            'list' => [
                'action' => 'elementor/get',
                'method' => 'get',
            ],
            'fields' => [
                'action' => 'elementor/get/form',
                'method' => 'post',
                'data' => ['id']
            ],
        ];
    }

    public static function pluginActive($option = null)
    {
        if (is_plugin_active('elementor-pro/elementor-pro.php')) {
            return $option === 'get_name' ? 'elementor-pro/elementor-pro.php' : true;
        } elseif (is_plugin_active('elementor/elementor.php')) {
            return $option === 'get_name' ? 'elementor/elementor.php' : true;
        } else {
            return false;
        }
    }

    public static function handle_elementor_submit($record)
    {
        $form_id = $record->get_form_settings('id');

        $flows = Flow::exists('Elementor', $form_id);
        if (!$flows) {
            return;
        }

        $data = [];
        $fields = $record->get('fields');
        foreach ($fields as $field) {
            $data[$field['id']] = $field['raw_value'];
        }

        Flow::execute('Elementor', $form_id, $data, $flows);
    }

    public function getAllForms()
    {
        if (!self::pluginActive()) {
            wp_send_json_error(__('Elementor Pro is not installed or activated', 'elementor-to-zoho-crm'));
        }

        $posts = self::getElementorPosts();

        $all_forms = [];
        if ($posts) {
            foreach ($posts as $post) {
                $postMeta = self::getElementorPostMeta($post->ID);
                $forms = self::getAllFormsFromPostMeta($postMeta);

                foreach ($forms as $form) {
                    $all_forms[] = (object)[
                        'id' => $form->id,
                        'title' => !empty(property_exists($form->settings, '_title')) ? $form->settings->_title : $form->settings->form_name,
                        'post_id' => $post->ID,
                    ];
                }
            }
        }
        wp_send_json_success($all_forms);
    }

    public function getFormFields($data)
    {
        if (!self::pluginActive()) {
            wp_send_json_error(__('Elementor Pro is not installed or activated', 'elementor-to-zoho-crm'));
        }
        if (empty($data->id) && empty($data->postId)) {
            wp_send_json_error(__('Form doesn\'t exists', 'elementor-to-zoho-crm'));
        }

        $fields = self::fields($data);
        if (empty($fields)) {
            wp_send_json_error(__('Form doesn\'t exists any field', 'elementor-to-zoho-crm'));
        }

        $responseData['fields'] = $fields;
        $responseData['postId'] = $data->postId;
        wp_send_json_success($responseData);
    }

    public static function fields($data)
    {
        if (!isset($data->postId)) {
            return;
        }
        $postMeta = self::getElementorPostMeta($data->postId);
        $forms = self::getAllFormsFromPostMeta($postMeta);
        $postDetails = array_filter($forms, function ($form) use ($data) {
            return $form->id == $data->id;
        });
        if (empty($postDetails)) {
            return $postDetails;
        }

        $fields = [];
        $postDetails = array_pop($postDetails);
        foreach ($postDetails->settings->form_fields as $field) {
            $type = isset($field->field_type) ? $field->field_type : 'text';
            if ($type === 'upload') {
                $type = 'file';
            }

            $fields[] = [
                'name' => $field->custom_id,
                'type' => $type,
                'label' => $field->field_label,
            ];
        }
        return $fields;
    }

    public static function getAllFormsFromPostMeta($postMeta)
    {
        $forms = [];
        foreach ($postMeta as $widget) {
            foreach ($widget->elements as $elements) {
                foreach ($elements->elements as $element) {
                    if (isset($element->widgetType) && $element->widgetType == 'form') {
                        // var_dump($element);die;
                        $forms[] = $element;
                    }
                }
            }
        }

        // this part for inner section forms .
        foreach ($postMeta as $widget) {
            foreach ($widget->elements as $elements) {
                foreach ($elements->elements as $element) {
                    foreach ($element->elements as $subElement) {
                        foreach ($subElement->elements as $subSubElement) {
                            if (isset($subSubElement->widgetType) && $subSubElement->widgetType == 'form') {
                                $forms[] = $subSubElement;
                            }
                        }
                    }
                }
            }
        }
        // popup form
        foreach ($postMeta as $widget) {
            foreach ($widget->elements as $elements) {
                if (isset($elements->widgetType) && $elements->widgetType == 'form') {
                    $forms[] = $elements;
                }
            }
        }
        return $forms;
    }

    private static function getElementorPosts()
    {
        global $wpdb;

        $query = "SELECT ID, post_title FROM $wpdb->posts
        LEFT JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)
        WHERE $wpdb->posts.post_status = 'publish' AND ($wpdb->posts.post_type = 'post' OR $wpdb->posts.post_type = 'page' OR $wpdb->posts.post_type = 'elementor_library') AND $wpdb->postmeta.meta_key = '_elementor_data'";

        return $wpdb->get_results($query);
    }

    private static function getElementorPostMeta(int $form_id)
    {
        global $wpdb;
        $postMeta = $wpdb->get_results("SELECT meta_value FROM $wpdb->postmeta WHERE post_id=$form_id AND meta_key='_elementor_data' LIMIT 1");
        return json_decode($postMeta[0]->meta_value);
    }
}
