<?php

namespace luckywp\glossary\plugin\shortcodes\termsArchive;

use luckywp\glossary\core\base\BaseObject;
use luckywp\glossary\core\Core;
use luckywp\glossary\front\widgets\TermsArchive;

class TermsArchiveShortcode extends BaseObject
{

    public function init()
    {
        parent::init();
        add_shortcode('lwpglsTermsArchive', [$this, 'shortcode']);
        add_action('admin_init', function () {
            if (Core::$plugin->admin->isEditArchivePage) {
                add_editor_style(Core::$plugin->url . '/plugin/shortcodes/termsArchive/editor.css');
                add_action('print_media_templates', function () {
                    ?>
                    <script type="text/html" id="tmpl-editor-lwpgls-termsArchiveShortcode">
                        <div class="lwpgls-termsArchiveShortcode">
                            <?= esc_html__('Glossary', 'luckywp-glossary') ?>
                        </div>
                    </script>
                    <?php
                });
                add_filter('mce_external_plugins', function ($plugins) {
                    $plugins['lwpgls_shortcode_terms_archive'] = Core::$plugin->url . '/plugin/shortcodes/termsArchive/plugin.js';
                    return $plugins;
                });
                add_filter('mce_buttons', function ($buttons) {
                    array_push($buttons, 'lwpgls_shortcode_terms_archive');
                    return $buttons;
                });
            }
        });
    }

    public function shortcode()
    {
        if (!lwpgls_is_archive()) {
            return '';
        }
        return TermsArchive::widget();
    }
}
