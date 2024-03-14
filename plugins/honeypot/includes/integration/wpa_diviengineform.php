<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
function my_df_before_process($form_id,$post_array,$form_type){
		if (wpa_check_is_spam($_POST)){
			foreach($_POST as $param => $value){
				if(strpos($param, 'divi-form-submit') === 0){
				$is_divi_engine_form = 'true';
				$divi_engine_form_additional = esc_attr(str_replace('divi-form-submit', '', $param));	
			}
			}
			do_action('wpa_handle_spammers','divi_engine_form', $_POST);
			if (str_ends_with($_SERVER["REQUEST_URI"],"admin-ajax.php")){
				// ajax post
				$result = array( 'result' => 'failed', 'redirect' => '', 'message' => '<B>' . esc_html($GLOBALS['wpa_error_message']) . '</B>', 'message_position' => 'after_button');
				wp_send_json( $result );
			}
			else
			{
				echo "<div id='fb_form{$divi_engine_form_additional}'><p>".$GLOBALS['wpa_error_message']."</p><div></div></div>";
			}

			die();
		}	
}
add_action( 'df_before_process', 'my_df_before_process', 10, 3 );