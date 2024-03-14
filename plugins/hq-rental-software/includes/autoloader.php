<?php

/**
 * Dynamically loads the class attempting to be instantiated elsewhere in the
 * plugin.
 *
 */

spl_autoload_register('hq_wordpress_autoloader');

/**
 * Dynamically loads the class attempting to be instantiated elsewhere in the
 * plugin by looking at the $class_name parameter being passed as an argument.
 *
 * The argument should be in the form: TutsPlus_Namespace_Demo\Namespace. The
 * function will then break the fully-qualified class name into its pieces and
 * will then build a file to the path based on the namespace.
 *
 * The namespaces in this plugin map to the paths in the directory structure.
 *
 * @param string $class_name The fully-qualified name of the class to load.
 */
function hq_wordpress_autoloader($class_name)
{
    // If the specified $class_name does not include our namespace, duck out.
    if (false === strpos($class_name, 'HQRentalsPlugin')) {
        return;
    }
    // Split the class name into an array to read the namespace and class.
    $file_parts = explode('\\', $class_name);
    $file = $file_parts[count($file_parts) - 1] . '.php';
    $file_path = '/';
    for ($i = 1; $i < count($file_parts) - 1; $i++) {
        $file_path .= strtolower(str_replace('HQRentals', '', $file_parts[$i])) . '/';
    }
    $file_path = trailingslashit(dirname(__FILE__) . $file_path);
    $file_path .= $file;
    if (file_exists($file_path)) {
        require_once($file_path);
    } elseif (false === strpos($class_name, 'WP_Http')) {
        include_once(ABSPATH . WPINC . '/class-http.php');
    } else {
        wp_die(
            esc_html("The file attempting to be loaded at $file_path does not exist.")
        );
    }
}

if (!function_exists('hq_update_post_meta')) {
    function hq_update_post_meta($post_id, $meta_key, $meta_value)
    {
        global $wpdb;
        $dbPrefix = $wpdb->prefix;
        if (!is_string($meta_value) && !is_numeric($meta_value) && !is_bool($meta_value)) {
            $meta_value = serialize($meta_value);
        }

        $result = $wpdb->get_results(
            "SELECT meta_id FROM " . $dbPrefix . "postmeta WHERE post_id = '.$post_id.' AND meta_key = '.$meta_key.'"
        );
        if (count($result)) {
            $wpdb->query(
                $wpdb->prepare(
                    "UPDATE " . $dbPrefix . "postmeta SET meta_value = '%s' WHERE post_id = '%d' AND meta_key = '%s'",
                    $meta_value,
                    $post_id,
                    $meta_key
                )
            );
        } else {
            $wpdb->query(
                $wpdb->prepare(
                    "INSERT INTO " . $dbPrefix . "postmeta (post_id, meta_key, meta_value) VALUES ('%d', '%s', '%s')",
                    $post_id,
                    $meta_key,
                    $meta_value
                )
            );
        }
    }
}
