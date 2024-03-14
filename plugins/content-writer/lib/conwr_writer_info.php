<?php
include_once(dirname(__FILE__) . '/sc_functions.php');

$result = "default";

if (isset($_POST['action'])) {
    switch($_POST['action']) {
        case 'get_content_info':
            if (isset($_POST['content'])) {
                if (!function_exists("get_option")) {
                    include_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/wp-load.php');
                }                

                $post_id = get_option("conwr_edited_post_id");

                $sc_keywords_meta = trim(conwr_get_post_keywords_meta($post_id));
                $sc_keywords_meta = rtrim($sc_keywords_meta, ',');

                if ($sc_keywords_meta != null && !empty($sc_keywords_meta)) {
                    $sc_keywords_arr = explode(",", $sc_keywords_meta);

                    $content = urldecode($_POST['content']);

                    $first_p_contains_kw = conwr_is_first_paragraph_contains_kw($content, $sc_keywords_arr) ? "Yes" : "No";

                    $first_kw_no_of_occ = count($sc_keywords_arr) > 0 ? conwr_get_number_of_occurrences($content, trim($sc_keywords_arr[0])) : 0;
                    $second_kw_no_of_occ = count($sc_keywords_arr) > 1 ? conwr_get_number_of_occurrences($content, trim($sc_keywords_arr[1])) : 0;
                    $third_kw_no_of_occ = count($sc_keywords_arr) > 2 ? conwr_get_number_of_occurrences($content, trim($sc_keywords_arr[2])) : 0;

                    $result = "{$first_p_contains_kw}|{$first_kw_no_of_occ}|{$second_kw_no_of_occ}|{$third_kw_no_of_occ}";
                }
            }
        break;
        default:
        break;
    }
}

echo $result;
?>