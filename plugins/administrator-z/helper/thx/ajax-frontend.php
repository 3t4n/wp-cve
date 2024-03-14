<?php 
use Adminz\Admin\ADMINZ_Flatsome;


// ================ Front end ======================

// lấy huyện theo tỉnh
function lay_gia_tri_field_frontend(){        
    if( !isset( $_POST['pa_nonce'] ) || !wp_verify_nonce( $_POST['pa_nonce'], 'pa_nonce' ) )
    die('Permission denied');
    
    $value1 = array();
    foreach ($_POST['value1'] as $value) {
        $cleanedValue = sanitize_text_field($value);
        $value1[] = $cleanedValue;
    }

    $key1 = sanitize_text_field($_POST['key1']);
    $key2 = sanitize_text_field($_POST['key2']);



    $metavalues = adminz_get_all_meta_values_by_key_value_sql(
        $key1,
        $value1,
        $key2
    );
    // echo "<pre>";print_r($_POST);echo "</pre>";die;
    return wp_send_json($metavalues);
    die();
};
add_action('wp_ajax_lay_gia_tri_field_frontend', 'lay_gia_tri_field_frontend');
add_action('wp_ajax_nopriv_lay_gia_tri_field_frontend', 'lay_gia_tri_field_frontend');

// lấy các meta value theo gia tri tu meta key 
function adminz_get_all_meta_values_by_key_value_sql($key1 = false, $value1 = false, $key2 = false){
    if(!$key1 or !$value1 or !$key2) return false;
    global $wpdb;
    $result = [];



    $value1 = (array)$value1;
    if(!empty($value1) and is_array($value1)){
        foreach ($value1 as $key => $_value) {

            $sql = "
            SELECT DISTINCT mt1.meta_value 
            FROM $wpdb->postmeta 
            INNER JOIN $wpdb->postmeta AS mt1 
            ON ( $wpdb->postmeta.post_id = mt1.post_id ) 
            WHERE 1=1 
            AND 
            (
                ( 
                    {$wpdb->postmeta}.meta_key = '".$key1."' 
                    AND $wpdb->postmeta.meta_value = '".$_value."' )   
                    AND ( mt1.meta_key = '".$key2."' 
                    AND mt1.meta_value != '' 
                ) 
            )";
            
            $data = $wpdb->get_results($sql , ARRAY_N  );
            
            foreach($data as $array){
                $result[] = $array[0];
            }
        }
    }
    return $result;
}