<?php

/**
 * Plugin Name: Paste As Plain Text By Default
 * Plugin URI: https://divimundo.com
 * Description: Enable paste as text by default with one click in editors like Classic Editor, Divi Builder, Elementor, Beaver Builder and WPBakery.
 * Version: 1.1.1
 * Author: DiviMundo
 * Author URI: https://divimundo.com/en/
 */

// Paste as text in Classic Editor
add_filter('tiny_mce_before_init', function ($init) {
    $init['paste_as_text'] = true;
    return $init;
});

//Paste as text in WPUF
add_filter('wpuf_textarea_editor_args', function ($args) {
    $args['tinymce']['plugins'] = 'paste';
    $args['tinymce']['paste_as_text'] = true;
    return $args;
});

// Paste as text in Divi Visual Builder and Divi Theme Builder
add_action('wp_footer', 'dm_paste_as_text', 9999);
add_action('admin_head', 'dm_paste_as_text', 9999);

function dm_paste_as_text()
{
    if (is_user_logged_in() && ((isset($_GET['et_fb']) && $_GET['et_fb'] === '1') || (isset($_GET['page']) && $_GET['page'] === 'et_theme_builder'))) {
?>
        <script>
            jQuery(document).ready(function($) {
                tinymce.on("AddEditor", function(e) {
                    setTimeout(function() {
                        plain_text(e);
                    }, 300);

                    setTimeout(function() {
                        plain_text(e);
                    }, 1000);

                    setTimeout(function() {
                        plain_text(e);
                    }, 2000);

                    var plain_text = function(e) {
                        try {
                            if (e.editor.plugins.paste.clipboard.pasteFormat.get() !== 'text') {
                                e.editor.execCommand("mceTogglePlainTextPaste");
                                $('.mce-notification button').click();
                            }
                        } catch (exception) {
                            // prevent JS error originating from execCommand above when tinymce does not have NotificationManager
                        }
                    };
                });
            });
        </script>
<?php
    }
}

// Add Donate Link
add_filter('plugin_action_links_' . plugin_basename(__FILE__), function ($links) {
    return array_merge($links, [
        '<a href="https://www.buymeacoffee.com/divimundo" target="_blank" style="color:#3db634;">Buy developer a coffee</a>'
    ]);
});