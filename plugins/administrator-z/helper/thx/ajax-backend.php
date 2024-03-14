<?php 
use Adminz\Admin\ADMINZ_Flatsome;

//======================= backend =========================

// lấy huyện theo tỉnh
add_action('wp_ajax_lay_gia_tri_huyen', function (){
	$tinh_huyen_xa = self::$data;

        if( !isset( $_POST['pa_nonce'] ) || !wp_verify_nonce( $_POST['pa_nonce'], 'pa_nonce' ) )
        die('Permission denied');
 
        $selected_tinh = sanitize_text_field($_POST['tinh']);
        
        
        $data = [];

        if(!empty($tinh_huyen_xa) and is_array($tinh_huyen_xa)){
                foreach ($tinh_huyen_xa as $key => $value) {
                        if($selected_tinh == $value['ten_tinh']){
                                if(!empty($value['huyen']) and is_array($value['huyen'])){
                                        foreach ($value['huyen'] as $key => $value) {
                                                $data[] = $value['ten_huyen'];
                                        }
                                }
                        }
                }
        }
        
        return wp_send_json($data);
        die();
});
//add_action('wp_ajax_nopriv_lay_gia_tri_huyen', 'lay_gia_tri_huyen');


// lấy xã theo huyện
add_action('wp_ajax_lay_gia_tri_xa', function() {        
	$tinh_huyen_xa = self::$data;
        if( !isset( $_POST['pa_nonce'] ) || !wp_verify_nonce( $_POST['pa_nonce'], 'pa_nonce' ) )
        die('Permission denied');
 
        $selected_huyen = sanitize_text_field($_POST['huyen']);
        $selected_tinh = sanitize_text_field($_POST['tinh']);
        
        
        $data = [];

        if(!empty($tinh_huyen_xa) and is_array($tinh_huyen_xa)){
                foreach ($tinh_huyen_xa as $key => $value) {
                        if($selected_tinh == $value['ten_tinh']){
                                if(!empty($value['huyen']) and is_array($value['huyen'])){
                                        foreach ($value['huyen'] as $key => $value) {
                                                if($selected_huyen == $value['ten_huyen']){
                                                        if(!empty($value['xa']) and is_array($value['xa'])){
                                                                foreach ($value['xa'] as $key => $value) {
                                                                        $data[] = $value['ten_xa'];
                                                                }                                                               
                                                        }
                                                }                                               
                                        }
                                }
                        }
                }
        }       
        return wp_send_json($data);
        die();
});
//add_action('wp_ajax_nopriv_lay_gia_tri_xa', 'lay_gia_tri_xa');



