<?php

namespace EasyWPGTM;

/**
 * Plugin Name:       Easy WP Google Tag Manager 
 * Description:       Easily add Google Tag Manager's container ID to your blog without having to edit your theme.
 * Version:           1.0.0
 * Requires at least: 4.0
 * Requires PHP:      5.6
 * Author:            Mani Shah Alizadegan
 * Author URI:        https://manishah.dev/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       easy-wp-google-tag-manager
 */

class EasyWPGTM
{
    function __construct()
    {
        add_action('init', [$this, 'print_gtm_tag']);
        add_action('admin_menu', [$this, 'create_plugin_settings_page']);
    }

    function print_gtm_tag()
    {
        add_action('wp_head', [$this, 'print_gtm_tag_to_head'], 1);
        add_action('wp_body_open', [$this, 'print_gtm_tag_to_body'], 1);
    }

    function print_gtm_tag_to_head()
    {
        ob_start();
?>
        <!-- Google Tag Manager -->
        <script>
            (function(w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start': new Date().getTime(),
                    event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', '<?= get_option('easyWPGTM_gtag_id') ?>');
        </script>
        <!-- End Google Tag Manager -->
    <?php
        $contents = ob_get_contents();
        ob_end_clean();

        echo $contents;
    }

    function print_gtm_tag_to_body()
    {
        ob_start();
    ?>
        <!-- Google Tag Manager (noscript) -->
        <noscript>
            <iframe src="https://www.googletagmanager.com/ns.html?id=<?= get_option('easyWPGTM_gtag_id') ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe>
        </noscript>
        <!-- End Google Tag Manager (noscript) -->
<?php
        $contents = ob_get_contents();
        ob_end_clean();

        echo $contents;
    }

    function create_plugin_settings_page()
    {
        add_settings_section(
            'easyWPGTM_gtag_settings_section',
            'Easy WP Google Tag Manager',
            [$this, 'gtag_settings_section_callback'],
            'general'
        );

        add_settings_field(
            'easyWPGTM_gtag_id',
            'Google Tag Manager ID',
            [$this, 'gtag_settings_callback'],
            'general',
            'easyWPGTM_gtag_settings_section',
            array(
                'easyWPGTM_gtag_id'
            )
        );
        register_setting('general', 'easyWPGTM_gtag_id', 'esc_attr');
    }

    function gtag_settings_section_callback()
    {
    }

    function gtag_settings_callback($args)
    {
        $option = get_option($args[0]);
        echo '<input type="text" id="' . $args[0] . '" name="' . $args[0] . '" value="' . $option . '" />';
    }
}

new EasyWPGTM();
