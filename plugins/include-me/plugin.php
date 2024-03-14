<?php

/*
  Plugin Name: Include Me
  Plugin URI: https://www.satollo.net/plugins/include-me
  Description: Include external HTML or PHP in any post or page.
  Version: 1.3.2
  Requires PHP: 5.6
  Requires at least: 4.6
  Author: Stefano Lissa
  Author URI: https://www.satollo.net
  Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
 */

if (!defined('INCLUDE_ME_DIR')) {
    define('INCLUDE_ME_DIR', WP_CONTENT_DIR . '/include-me');
}

if (is_admin()) {
    include __DIR__ . '/admin/admin.php';
} else {

    function includeme_call($attrs, $content = null) {
        global $post;

        if (!($post instanceof WP_Post)) {
            return '';
        }

        if (!user_can($post->post_author, 'administrator')) {
            return 'Only admnistrator owned posts can execute the <code>[includeme]</code> shortcode. <strong>This message is shown only to administrators</strong>.';
        }

        if (isset($attrs['file'])) {
            $file = trim(strip_tags($attrs['file']));
            if (empty($file)) {
                return '<p>Include me shortcode: the file attribute is empty</p>';
            }

            if (INCLUDE_ME_DIR === '*') {
                // Reverto to the old behavior: accept any file path, if not absolute (conventionally starting by /), add the ABSPATH
                if (substr($file, 0, 1) !== '/') {
                    $file = ABSPATH . $file;
                }
                $clean_file = realpath($file);
                if (!$clean_file) {
                    if (current_user_can('administrator')) {
                        return '<p>The provided file (<code>' . esc_html($file) . '</code>) does not exist. <strong>This message is shown only to administrators</strong>.</p>';
                    }
                }
            } else {
                $clean_file = realpath(INCLUDE_ME_DIR . '/' . $file);
                if (!$clean_file) {
                    if (current_user_can('administrator')) {
                        return '<p>The provided file (<code>' . esc_html($file) . '</code>) does not exist in the inclusion folder (<code>wp-content/include-me</code> - if not customized). <strong>This message is shown only to administrators</strong>.</p>';
                    }
                }
            }

            $clean_file = wp_normalize_path($clean_file);

            if (INCLUDE_ME_DIR === '*') {
                // Do nothing
            } else {
                // Check if the final file is actually inside the correct inclusion folder
                $abs = wp_normalize_path(INCLUDE_ME_DIR);

                if (strpos($clean_file, $abs) !== 0) {
                    if (current_user_can('administrator')) {
                        return '<p>The provided file (<code>' . esc_html($file) . '</code>) is out of the inclusion folder (<code>wp-content/include-me</code> - if not customized). <strong>This message is shown only to administrators</strong>.</p>';
                    }
                    return '';
                }
            }

            ob_start();
            include($clean_file);
            $buffer = ob_get_clean();
            $options = get_option('includeme', []);
            if (isset($options['shortcode'])) {
                $buffer = do_shortcode($buffer);
            }
            return $buffer;
        }

        if (isset($attrs['post_id'])) {
            $post = get_post($attrs['post_id']);
            $options = get_option('includeme', []);
            $buffer = $post->post_content;
            if (isset($options['shortcode'])) {
                $buffer = do_shortcode($buffer);
            }
            return $buffer;
        }

        if (isset($attrs['field'])) {
            global $post;
            $buffer = get_post_meta($post->ID, $attrs['field'], true);
            if (isset($options['php'])) {
                ob_start();
                eval('?>' . $buffer);
                $buffer = ob_get_clean();
            }
            if (isset($options['shortcode'])) {
                $buffer = do_shortcode($buffer);
            }
            return $buffer;
        }

        if (isset($attrs['src'])) {
            $tmp = '';
            foreach ($attrs as $key => $value) {
                if (!in_array($key, ['src', 'width', 'style', 'class', 'id', 'height'])) {
                    continue;
                }
                $value = strip_tags($value);

                if ($key === 'src') {
                    $value = str_replace('&amp;', '&', $value);
                }
                $tmp .= ' ' . $key . '="' . esc_attr($value) . '"';
            }
            $buffer = '<iframe' . $tmp . '></iframe>';
            return $buffer;
        }
    }

    add_shortcode('includeme', 'includeme_call');
}
