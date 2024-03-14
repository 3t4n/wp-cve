<?php

namespace luckywp\termDescriptionRichText\admin;

use luckywp\termDescriptionRichText\core\base\BaseObject;
use luckywp\termDescriptionRichText\core\Core;
use WP_Term;

class Admin extends BaseObject
{

    public function init()
    {
        if (is_admin()) {
            add_action('admin_init', [$this, 'addEditors']);
            add_action('admin_enqueue_scripts', [$this, 'assets']);
            remove_filter('term_description', 'wp_kses_data');
        }
    }

    /**
     * @param string $hook
     */
    public function assets($hook)
    {
        if (in_array($hook, ['term.php', 'edit-tags.php'])) {
            wp_enqueue_style(Core::$plugin->prefix . 'main', Core::$plugin->url . '/admin/assets/main.min.css', [], Core::$plugin->version);
            wp_enqueue_script(Core::$plugin->prefix . 'main', Core::$plugin->url . '/admin/assets/main.min.js', ['jquery'], Core::$plugin->version);
        }
    }

    public function addEditors()
    {
        $taxonomies = get_taxonomies(['public' => true], 'names');
        foreach ($taxonomies as $tax) {
            add_action($tax . '_add_form', function () {
                $this->renderEditor();
            });
            add_action($tax . '_edit_form', function (WP_Term $term) {
                $this->renderEditor(html_entity_decode($term->description, ENT_QUOTES, 'UTF-8'));
            });
        }
    }

    /**
     * @param string $content
     */
    protected function renderEditor($content = '')
    {
        echo '<div class="lwptdrEditor">';
        wp_editor($content, 'lwptdrEditor', [
            'textarea_rows' => '8',
            'textarea_name' => 'description',
        ]);
        echo '</div>';
    }
}
