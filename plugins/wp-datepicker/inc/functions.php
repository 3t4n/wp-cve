<?php  if ( ! defined( 'ABSPATH' ) ) exit; 

	function sanitize_wpdp_data( $input ) {
		if(is_array($input)){		
			$new_input = array();	
			foreach ( $input as $key => $val ) {
				$new_input[ $key ] = (is_array($val)?sanitize_wpdp_data($val):stripslashes(sanitize_text_field( $val )));
			}			
		}else{
			$new_input = stripslashes(sanitize_text_field($input));			
			if(stripos($new_input, '@') && is_email($new_input)){
				$new_input = sanitize_email($new_input);
			}
			if(stripos($new_input, 'http') || wp_http_validate_url($new_input)){
				$new_input = sanitize_url($new_input);
			}			
		}	
		return $new_input;
	}
		
	//FOR QUICK DEBUGGING
	if(!function_exists('pre')){
	function pre($data){
			if(isset($_GET['debug'])){
				pree($data);
			}
		}	 
	} 
	
	if(!function_exists('pree')){
	function pree($data){
				echo '<pre>';
				print_r($data);
				echo '</pre>';	
		
		}	 
	} 




	function wpdp_menu()
	{



		 add_options_page('WP Datepicker', 'WP Datepicker', 'activate_plugins', 'wp_dp', 'wp_dp');



	}

	function wp_dp(){ 



		if ( !current_user_can( 'administrator' ) )  {



			wp_die( __( 'You do not have sufficient permissions to access this page.', 'wp-datepicker' ) );



		}



		global $wpdb, $wpdp_dir, $wpdp_pro, $wpdp_data; 


		include($wpdp_dir.'inc/wpdp_settings.php');
		

	}	



	
	

	function wpdp_plugin_links($links) { 
		global $wpdp_premium_link, $wpdp_pro;
		
		$settings_link = '<a href="options-general.php?page=wp_dp">'.__('Settings', 'wp-datepicker').'</a>';
		
		if($wpdp_pro){
			array_unshift($links, $settings_link); 
		}else{
			 
			$wpdp_premium_link = '<a href="'.esc_url($wpdp_premium_link).'" title="'.__('Go Premium', 'wp-datepicker').'" target="_blank">'.__('Go Premium', 'wp-datepicker').'</a>'; 
			array_unshift($links, $settings_link, $wpdp_premium_link); 
		
		}
		
		
		return $links; 
	}


	function wpdp_load_datepicker_scripts(){

        global $wpdp_actual_link, $wpdp_new_js, $wpdp_new_css, $wpdp_dir;
        $js_file_path = $wpdp_dir.$wpdp_new_js;
        $css_file_path = $wpdp_dir.$wpdp_new_css;

        if(!file_exists($js_file_path)){

            wpdp_generate_js_file();
        }

        if(!file_exists($css_file_path)){

            wpdp_generate_css_file();

        }

        $wpdp_enqueue_url = get_option('wpdp_enqueue_url', array());

        $datepicker_script = true;


        if(!empty($wpdp_enqueue_url)){

            $wpdp_enqueue_url = array_map(function($url){
                return rtrim($url, '/');
            }, $wpdp_enqueue_url);

              if(!in_array(rtrim($wpdp_actual_link,'/'), $wpdp_enqueue_url)){
                $datepicker_script = false;
            }

        }

        return $datepicker_script;

    }


	
	function register_wpdp_scripts() {

        global $wpdp_url, $wpdp_new_js, $wpdp_new_css, $wpdp_pro, $wpdp_global_settings;
        $js_file_url = $wpdp_url.$wpdp_new_js;
        $css_file_url = $wpdp_url.$wpdp_new_css;
        $is_datepicker_script = wpdp_load_datepicker_scripts();
		
			
		if (is_admin()){
		
			
			
			if(isset($_GET['page']) && $_GET['page']=='wp_dp'){
				
					
				wp_enqueue_media();
				
				$wp_datepicker_bootstrap_disabled = (array_key_exists('wp_datepicker_bootstrap_disabled', $wpdp_global_settings) && $wpdp_global_settings['wp_datepicker_bootstrap_disabled']==1);
				

				$translation_array = array(
				        'del_msg' => __('Are you sure, you want to delete this instance?', 'wp-datepicker'),
                        'this_url' => admin_url( 'admin.php?page=wp_dp' ),
						'nonce' => wp_create_nonce('wpdp_nonce_action'),

                );
			
				
				 
				wp_enqueue_script(
					'wpdp-scripts-admin',
					plugins_url('js/scripts.js?t='.time(), dirname(__FILE__)),
					array('jquery')
				);	
				
				
			
				wp_enqueue_style( 'wpdp-style1', plugins_url('css/admin-styles.css', dirname(__FILE__)), array(), date('Ymdhi'));
				wp_enqueue_style( 'fontawesome', plugins_url('css/fontawesome.min.css', dirname(__FILE__)), array(), date('Ymdhi'));
				
				if(function_exists('wpdp_fonts_enqueue')){
					wpdp_fonts_enqueue();
				}

				wp_localize_script('wpdp-scripts-admin', 'wpdp_obj', $translation_array);
				


				wp_enqueue_script(
					'wpdp-scripts3',
					plugins_url('js/jqColorPicker.min.js', dirname(__FILE__)),
					array('jquery')
				);			
				
				wp_enqueue_script(
					'fontawesome',
					plugins_url('js/fontawesome.min.js?t='.time(), dirname(__FILE__)),
					array('jquery')
				);		
				
				
				if(!$wp_datepicker_bootstrap_disabled){	
					wp_enqueue_style( 'bootstrap', plugins_url('css/bootstrap.min.css', dirname(__FILE__)), array(), true );
					
					wp_enqueue_script(
						'bootstrap',
						plugins_url('js/bootstrap.min.js', dirname(__FILE__)),
						array('jquery'),
						date('Y')
					);				
				}	
				
			}
			
			
			
		
		}else{
			
		}
		

		$wp_datepicker_wpadmin = (array_key_exists('wp_datepicker_wpadmin', $wpdp_global_settings) && $wpdp_global_settings['wp_datepicker_wpadmin']==1);
		//pree($wpdp_global_settings);
		//pree($wp_datepicker_no_wpadmin);
		
//		if((!is_admin() || (is_admin() && get_option( 'wp_datepicker_wpadmin', 0))){
        if($is_datepicker_script && (!is_admin() || (is_admin() && $wp_datepicker_wpadmin))){
				
				$translation_array = array(
						'nonce' => wp_create_nonce('wpdp_nonce_action'),
						'wpml_current_language' => apply_filters( 'wpml_current_language', null )

                );
				
				wp_enqueue_script(
					'wpdp-scripts2',
					plugins_url('js/scripts-front.js', dirname(__FILE__)),
					array('jquery', 'jquery-ui-datepicker')
				);	
						
				wp_localize_script('wpdp-scripts2', 'wpdp_obj', $translation_array);
				
				wp_register_style('wpdp-style2', plugins_url('css/front-styles.css?t='.time(), dirname(__FILE__)));	
				
				wp_enqueue_style( 'wpdp-style2' );


            $wpdp_get_datepicker_list = wpdp_get_datepicker_list();

            if(!empty($wpdp_get_datepicker_list)) {

                $wpdp_custom_used = array();
				
				$enqueued_langs = array();

                foreach ($wpdp_get_datepicker_list as $dp_list => $datepicker) {

                    $dp_option_id = $datepicker->option_id;
                    $dp_option_name = $datepicker->option_name;
                    $dp_option_value = maybe_unserialize($datepicker->option_value);



                    $wp_datepicker_language = isset($dp_option_value['wp_datepicker_language']) ? $dp_option_value['wp_datepicker_language'] : '';
					$wp_datepicker_language = wpdp_slashes($wp_datepicker_language);
           			$wp_datepicker_language = str_replace(''.__('Select Language', 'wp-datepicker').'', 'en-GB|datepicker-en-GB.js', $wp_datepicker_language);
					
					//pree($wp_datepicker_language);

                    if($wp_datepicker_language!=''){
                        $lang = explode('|', $wp_datepicker_language);
                        $filename = (!empty($lang)?end($lang):$lang);

                        if(substr($filename, 0, strlen('Select'))!='Select' && !in_array($filename, $enqueued_langs)){
							$enqueued_langs[] = $filename;
                            wp_enqueue_script(
                                'wpdp-'.$dp_option_id.'-i18n',
                                plugins_url('js/i18n/'.$filename, dirname(__FILE__)),
                                array('jquery')
                            );
                        }
                    }



                }
            }
			
				
			wp_register_style('wpdp-style3', plugins_url('css/jquery-ui.css', dirname(__FILE__)));	
			
			wp_enqueue_style( 'wpdp-style3' );	
			
			
			if(wp_is_mobile()){
				
				wp_enqueue_style( 'jquery.ui.datepicker.mobile', plugins_url('css/mobile/jquery.ui.datepicker.mobile.css', dirname(__FILE__)), array(), date('Yhi'));
				/*wp_enqueue_script(
					'wpdp-datepicker-ui',
					plugins_url('js/mobile/jQuery.ui.datepicker.js', dirname(__FILE__)),
					array('jquery')
				);*/	
				/*wp_enqueue_script(
					'wpdp-datepicker-mobile',
					plugins_url('js/mobile/jquery.ui.datepicker.mobile.js', dirname(__FILE__)),
					array('jquery')
				);*/	
											
			}
			
			if(function_exists('wpdp_fonts_enqueue')){
				wpdp_fonts_enqueue();
			}
			
		}


        if($is_datepicker_script){


            if($wpdp_pro){

                wp_enqueue_style( 'datepicker-style-auto', $css_file_url, array(), date('Ymdh'));

            }

            wp_enqueue_script(
                'datepicker-script-auto',
                $js_file_url,
                array('jquery'),
				date('Ymdhi'),
				true
            );


        }
							
	} 
	
	
		
	if(!function_exists('wp_datepicker')){
	function wp_datepicker(){

		
		}
	}
	
	function wpdp_clean_selector($wpdp_selector){
		return str_replace(array('#', '.', '[', ']', '(', ')', '=', "'", '"'), '', $wpdp_selector);
	}
	
	add_action('init', 'wpdp_footer_scripts_debug');
	
	function wpdp_footer_scripts_debug(){
		if(isset($_GET['wpdp_footer_scripts_debug'])){
			wpdp_footer_scripts();exit;
		}
	}
	
	if(!function_exists('wpdp_footer_scripts')){
	function wpdp_footer_scripts(){

	    global  $wpdp_gen_file;
	    $wpdp_get_datepicker_list = wpdp_get_datepicker_list();

	    if(!empty($wpdp_get_datepicker_list)){

            if(function_exists('wpdp_footer_js_pro')){

                wpdp_footer_js_pro();

            }

			$wpdp_range = get_option('wpdp_range');
			$wpdp_range = is_array($wpdp_range)?$wpdp_range:array();
			//pree($wpdp_range);
			
	        foreach ($wpdp_get_datepicker_list as $dp_list => $datepicker){

            $dp_option_id = $datepicker->option_id;
            $dp_option_name = $datepicker->option_name;
			$instance_id = wpdp_get_instance_id_from_name($dp_option_name);
            $dp_option_value = maybe_unserialize($datepicker->option_value);

            $wpdp_options_db = array_key_exists('wpdp_options', $dp_option_value) ? $dp_option_value['wpdp_options']: false;

            if(array_key_exists('wpdp_options', $dp_option_value)){

                unset($dp_option_value['wpdp_options']);

            }

//            pree($dp_option_value);



		$wpdp_selectors = array_key_exists( 'wp_datepicker', $dp_option_value) ? $dp_option_value['wp_datepicker']: false;
		$wp_datepicker_alive_scripts = array_key_exists('wp_datepicker_alive_scripts', $dp_option_value) ? $dp_option_value['wp_datepicker_alive_scripts'] : 'no';

		if($wpdp_selectors!=''){

			$wpdp_selectors = wpdp_slashes($wpdp_selectors);



?>
	
	<script type="text/javascript" language="javascript">

	jQuery(document).ready(function($){


		
		if($('.wpcf7-form-control.wpcf7-repeater-add').length>0){
			$('.wpcf7-form-control.wpcf7-repeater-add').on('click', function(){
				wpdp_refresh_<?php echo $dp_option_id ?>(jQuery, true);
			});
		}
		
<?php
			global $wpdp_options, $wpdp_js_options;
			$options = array();




			if(!empty($wpdp_options)){
//				$wpdp_options_db = get_option('wpdp_options');
				foreach($wpdp_options as $option=>$type){
					if(!isset($wpdp_options_db[$option])){
						$wpdp_options_db[$option] = '';
					}
					//pree($type);
					switch($type){
						default: 
							$val = $wpdp_options_db[$option];
							//pree($option);
							//pree($val);
							if($val==''){
								switch($option){
									case 'dateFormat':
										//$val = get_option('date_format'); pree($val);
										$val = 'mm/dd/yy'; //pree($val);
									break;
								}
							}
								
							$val = '"'.$val.'"';
							
						break;
						case 'checkbox':
							$val = ($wpdp_options_db[$option]==true?'true':'false');//exit;
						break;
					}
					$wpdp_js_options[$option] = $val;
					$options[] = $option.':'.$val.'';
					//$options[] = array('key'=>$option, 'val'=>$val);
				}
			}
			$wpdb_date_format = $wpdp_options_db['dateFormat'] ? $wpdp_options_db['dateFormat'] : 'mm/dd/yy';
			


?>
	
});
var wpdp_refresh_first_<?php echo $dp_option_id ?> = 'yes';
var wpdp_intv_<?php echo $dp_option_id ?>;
var wpdp_counter_<?php echo $dp_option_id ?> = 0;
var wpdp_month_array_<?php echo $dp_option_id ?> = [];
<?php 
	if(!empty($wpdp_js_options)){
		foreach($wpdp_js_options as $opts=>$vals){
?>
var wpdp_<?php echo $opts; ?> = <?php echo $vals; ?>;
<?php			
		}
	}
?>
function wpdp_refresh_<?php echo $dp_option_id ?>($, force){
<?php 
			if(!is_admin() || (isset($_GET['page']) && $_GET['page']!='wp_dp') || (is_admin() && get_option( 'wp_datepicker_wpadmin', 0)) || $wpdp_gen_file):


		
			$wp_datepicker_language = false;
			$wp_datepicker_weekends = false;
			$wp_datepicker_autocomplete = true;
			$wp_datepicker_beforeShowDay = false;
			$wp_datepicker_months = false;
			$wp_datepicker_readonly = true;
//			$inline = ((''!=wpdp_get('wpdp_inline')) && wpdp_get('wpdp_inline')==1);
			$inline = (array_key_exists('wpdp_inline', $wpdp_options_db) && $wpdp_options_db['wpdp_inline'] == 1);

			extract($dp_option_value);

//			pree($dp_option_value);
    //                    pree($options);


            $wp_datepicker_language = wpdp_slashes($wp_datepicker_language);
            $wp_datepicker_language = str_replace(''.__('Select Language', 'wp-datepicker').'', 'en-GB|datepicker-en-GB.js', $wp_datepicker_language);
//            pree('40850');
//            pree($wp_datepicker_language);


            $wp_datepicker_beforeShowDay = trim($wp_datepicker_beforeShowDay);


    		if($wp_datepicker_months){

				if (($key = array_search('changeMonth:false', $options)) !== false) {
					unset($options[$key]);
					$options[]='changeMonth:true';


                }
					
				$options[] = 'monthNamesShort:wpdp_month_array_'.$dp_option_id;
			}


			
			if($wp_datepicker_language != ''){
			
			$code = current(explode('|', $wp_datepicker_language));
			
			
?>
			var wpml_code = wpdp_obj.wpml_current_language;
			
			wpml_code = (wpml_code?wpml_code:"<?php echo $code; ?>");
			switch(wpml_code){
				case "en":
					wpml_code = "en-US";
				break;
			}
	
	
			
				if(typeof $.datepicker!='undefined' && typeof $.datepicker.regional[wpml_code]!='undefined'){
				<?php if($wp_datepicker_months): 
				
				?>
				
				wpdp_month_array_<?php echo $dp_option_id ?> = $.datepicker.regional[wpml_code].monthNamesShort;
				
				<?php else: ?>	
				wpdp_month_array_<?php echo $dp_option_id ?> = $.datepicker.regional[wpml_code].monthNames;
				<?php endif; ?>					
				}
				
				
		
				
<?php

				$wpdp_selectors = explode(',', $wpdp_selectors);
				
				
				
				if(!empty($wpdp_selectors)){
					foreach($wpdp_selectors as $selector_index=>$wpdp_selector_item){
						
						$wpdp_selector = trim($wpdp_selector_item);
						
						$selector_date_range = 'wpdp-date-range-'.$selector_index;

						$wpdp_range_this = array_key_exists($selector_date_range, $wpdp_range)?$wpdp_range[$selector_date_range]:array();
						
						//pree($wpdp_range_this);
						
						$wpdp_range_target = trim(array_key_exists('target', $wpdp_range_this)?$wpdp_range_this['target']:'');
						
						//pree($wpdp_range_target);
						
						if($wpdp_range_target!=''){
							
							$wpdp_range_target_num = explode('-', $wpdp_range_target);
							if(is_array($wpdp_range_target_num) && is_numeric(end($wpdp_range_target_num))){

								$wpdp_datepicker_instance = $wpdp_get_datepicker_list[end($wpdp_range_target_num)];
								
								//pree($wpdp_datepicker_instance);

								$instance_value = maybe_unserialize($wpdp_datepicker_instance->option_value);
								$instance_value = (is_array($instance_value)?$instance_value:array());

								$instance_selectors = array_key_exists( 'wp_datepicker', $instance_value) ? $instance_value['wp_datepicker']: false;	
								
								//pree($instance_selectors);

								if($instance_selectors){							
									$wpdp_range_target = trim($instance_selectors);
								}
								
								//pree($wpdp_range_target);
							}
						}
						//pree($wpdp_range_this);
						
						$wpdp_range_d = trim(array_key_exists('day', $wpdp_range_this)?$wpdp_range_this['day']:'');
						$wpdp_range_m = trim(array_key_exists('month', $wpdp_range_this)?$wpdp_range_this['month']:'');
						$wpdp_range_y = trim(array_key_exists('year', $wpdp_range_this)?$wpdp_range_this['year']:'');		
						$wpdp_range_status = trim(array_key_exists('status', $wpdp_range_this)?$wpdp_range_this['status']:false);				
?>

<?php if($inline){ // && !is_admin() ?>

<?php
						$wpdp_selector_div = $wpdp_selector.'_div';
						
						$wpdp_selector = $wpdp_selector_div;
						$wpdp_selector_div = trim($wpdp_selector_item);
						
						$wpdp_selector_extra = wpdp_clean_selector($wpdp_selector);

?>	
				if($("#<?php echo $wpdp_selector_extra; ?>").length==0 && $("<?php echo $wpdp_selector_div; ?>").length>0){
					$('<div id="<?php echo $wpdp_selector_extra; ?>" class="<?php echo isset($wpdp_options_db['use_custom_style1']) ? $wpdp_options_db['use_custom_style1'] : '' ?>"></div>').insertAfter("<?php echo $wpdp_selector_div; ?>");
					$('#<?php echo $wpdp_selector_extra; ?>').datepicker({
						inline: true,
						altField: "<?php echo $wpdp_selector_div; ?>"
					});		
								
					$("<?php echo $wpdp_selector_div; ?>").change(function(){
						$('#<?php echo $wpdp_selector_extra; ?>').datepicker('setDate', $(this).val());
					});	
					
					<?php $wpdp_selector = $wpdp_selector_div; ?>
				}
				
<?php } ?>

				if($("<?php echo $wpdp_selector; ?>").length>0){
					
				$("<?php echo $wpdp_selector; ?>").attr("autocomplete", "off");
					
				//document.title = wpdp_refresh_first=='yes';
				//force = true;
				<?php if($wp_datepicker_alive_scripts=='no'){ ?>
				if(wpdp_refresh_first_<?php echo $dp_option_id ?> == 'yes' || force){
					
					
					
										
					if(typeof $.datepicker!='undefined')
					$("<?php echo $wpdp_selector; ?>").datepicker( "destroy" );
					
					
					$("<?php echo $wpdp_selector; ?>").removeClass("hasDatepicker");
					wpdp_refresh_first_<?php echo $dp_option_id ?> = 'done';
					
				}
				<?php } ?>
				$('body').on('mouseover, mousemove', function(){//
				
			
				
				if ($("<?php echo $wpdp_selector; ?>").length>0) {
					$.each($("<?php echo $wpdp_selector; ?>"), function(wp_si, wp_sv){
						if($(this).val()!=''){
							$(this).attr('data-default-val', $(this).val());
						}
					});
				}		
				
				
				<?php if($wp_datepicker_alive_scripts!='yes'): ?>
				if(wpdp_counter_<?php echo $dp_option_id ?> > 2)
				clearInterval(wpdp_intv_<?php echo $dp_option_id ?>);
				<?php endif; ?>
				
				
					
				if($("<?php echo $wpdp_selector; ?>.hasDatepicker").length!=$("<?php echo $wpdp_selector; ?>").length){

				
					
				$("<?php echo $wpdp_selector; ?>").datepicker($.extend(  
					{},  // empty object  
					$.datepicker.regional[ wpml_code ],       // Dynamically  
					{ <?php if($wp_datepicker_beforeShowDay!=''){?> beforeShowDay: <?php echo htmlspecialchars_decode($wp_datepicker_beforeShowDay); ?>, <?php }elseif($wp_datepicker_weekends){ ?>beforeShowDay: $.datepicker.noWeekends,
 <?php } ?> 
 					dateFormat: wpdp_dateFormat
					}
				)).on( "change", function() {
<?php
	//echo '/* '.($wpdp_range_status.' / '.$wpdp_range_target.' ('.$wpdp_range_d.' || '.$wpdp_range_m.' || '.$wpdp_range_y.')').'*/';
	if($wpdp_range_status){
		
	if($wpdp_range_target && ($wpdp_range_d || $wpdp_range_m || $wpdp_range_y)):
?>						
					var desired_date = $(this).datepicker('getDate'); 
					
<?php
	if($wpdp_range_d){
?>					
  					desired_date.setDate(desired_date.getDate()+<?php echo $wpdp_range_d; ?>); 
<?php 
	}
?>	
<?php
	if($wpdp_range_m){
?>					
  					desired_date.setMonth(desired_date.getMonth()+<?php echo $wpdp_range_m; ?>); 
<?php 
	}
?>	
<?php
	if($wpdp_range_y){
?>					
  					desired_date.setYear(desired_date.getYear()+<?php echo $wpdp_range_y; ?>); 
<?php 
	}
?>	
<?php if($wpdp_selector!=$wpdp_range_target){ 
		//pree($wpdp_selectors);
		$wpdp_range_target_arr = (is_array($wpdp_selectors)?$wpdp_selectors:explode(',', $wpdp_selectors));
		$wpdp_range_target_arr = array_filter($wpdp_range_target_arr);
		$wpdp_range_target_arr = array_map('trim', $wpdp_range_target_arr);
		//pree($wpdp_range_target_arr);
		if(!empty($wpdp_range_target_arr)){
			$target_index = str_replace('wpdp-date-range-', '', $wpdp_range_this['target']);
			//pree($target_index);
			$wpdp_range_target = (array_key_exists($target_index, $wpdp_range_target_arr)?$wpdp_range_target_arr[$target_index]:$wpdp_range_target);
			
		}
		//pree($wpdp_range_target);
		
		
?>
					$("<?php echo $wpdp_range_target; ?>").datepicker( "option", "minDate", desired_date );
<?php } ?>					

					$("<?php echo $wpdp_selector; ?>").datepicker( "option", "maxDate", desired_date );

<?php 
	endif;
	
	}
	
?>						
				}); 
				
<?php if($wp_datepicker_readonly): ?>
				$("<?php echo $wpdp_selector; ?>").attr('readonly', 'readonly');
<?php endif; ?>				
				
				
				
<?php 
					if(!empty($options)){ //pree($options);exit;
						
						foreach($options as $option){	
						$opt = explode(':', $option);
						
						$key = current($opt);
						
						array_shift($opt);
						
						
						$val = implode(':', $opt);
						//pree($val);


						
							
							switch($key){
								case 'defaultDate':
								
								
								if(trim(str_replace('"', '', $val))!=''){
?>
setTimeout(function(){ $("<?php echo $wpdp_selector; ?>").datepicker().datepicker('setDate', <?php echo $val; ?>); }, 100);
<?php
								}else{
?>

setTimeout(function(){ 

	 $.each($("<?php echo $wpdp_selector; ?>"), function(){

        <?php


         if($wp_datepicker_autocomplete):

         ?>

            $(this).prop('autocomplete', 'on');


         <?php else: ?>

            $(this).prop('autocomplete', 'off');

         <?php endif; ?>
		 		
		var expected_default = $(this).data('default');		

		
		var expected_stamp = $(this).data('default_stamp');
		var expected_stamp_date = new Date(expected_stamp*1000);
		var expected_stamp_str = $.datepicker.formatDate('<?php echo $wpdb_date_format; ?>', expected_stamp_date);		 
	 
		if(expected_default != undefined && expected_default!=''){ $(this).datepicker().datepicker('setDate', expected_default); }
		if(expected_stamp != undefined && expected_stamp!=''){ $(this).datepicker().datepicker('setDate', expected_stamp_str); }		
		
	});
	
}, 100);
	
<?php
									
								}
								
								break;
								
								default:
									if(trim(str_replace('"', '', $val))!=''){
?>
				$("<?php echo $wpdp_selector; ?>").datepicker( "option", "<?php echo $key; ?>", <?php echo $val; ?> );

<?php
									}
								break;
							}
						
						}

						?>




<?php
					}
					
					if(function_exists('wpdp_extend_options_filter')){wpdp_extend_options_filter($wpdp_selector, $instance_id);}
?>


					$.each($("<?php echo $wpdp_selector; ?>"), function(){
						var this_selector = $(this);
						var parent_form = this_selector.closest('form');
						
						parent_form.on('reset', function(){
							if(this_selector.data('default-val')!= ""){
								setTimeout(function(){
									if(this_selector.val() == ''){
										this_selector.val(this_selector.data('default-val'));
									}
								});
							}
						});
						if($(this).data('default-val')!= ""){
							$(this).val($(this).data('default-val'));
						}
						
					});
						
				
				}
				
				
				
				});
				}
<?php
					
					}
				}
?>
		
<?php
			}else{
?>
				$("<?php echo $wpdp_selectors; ?>").datepicker({dayNamesMin: ['S', 'M', 'T', 'W', 'T', 'F', 'S'], <?php echo implode(', ', $options); ?>});
<?php				
			}


?>


		
		$('.ui-datepicker').addClass('notranslate');
<?php 
			endif; 
?>
}
	wpdp_intv_<?php echo $dp_option_id ?> = setInterval(function(){
		wpdp_counter_<?php echo $dp_option_id ?>++;
		wpdp_refresh_<?php echo $dp_option_id ?>(jQuery, <?php echo ($wp_datepicker_alive_scripts=='yes'?'true':'false'); ?>);
		
	}, <?php echo ($wp_datepicker_alive_scripts!='yes'?'500':'1500'); ?>);

	<?php

//    $wpdp_selectors = explode(',', $wpdp_selectors);
    if(is_array($wpdp_selectors) && !empty($wpdp_selectors)){

        foreach($wpdp_selectors as $wpdp_selector_item){

            $wpdp_selector = trim($wpdp_selector_item);

            ?>
                jQuery(document).ready(function($){

                        $("<?php echo $wpdp_selector; ?>").on('click', function(){

                            $('.ui-datepicker-div-wrapper').prop('class', 'ui-datepicker-div-wrapper <?php echo $dp_option_name; ?> <?php echo isset($wpdp_options_db['use_custom_style1']) ? $wpdp_options_db['use_custom_style1'] : '' ?>');

                        });

                        setTimeout(function () {
                                $("<?php echo $wpdp_selector; ?>").click();
                                //$("<?php //echo $wpdp_selector; ?>//").focusout();
                        }, 1000);



                });

            <?php

        }
    }

    ?>

    //wpdp_refresh_<?php //echo $dp_option_id ?>//(jQuery, false);
	
	</script>    
<?php		
		}



    }


//            exit;

    }

	}
	}
	
	function wpdp_slashes($str, $s=false){
		return str_replace(array('"'), "'", stripslashes($str));
	}
	
	function wpdp_free_settings($current_option){
		global $wpdp_pro, $wpdp_fonts, $wpdp_options, $wpdp_styles, $wpdp_options_data;
		//pree($_POST);

        $wpdp_options_data_pro = array_key_exists('wpdp_options', $wpdp_options_data) ? $wpdp_options_data['wpdp_options'] : array();
		
		if(is_array($wpdp_options_data_pro))
        extract($wpdp_options_data_pro);



        $wpdp_fonts = unserialize(base64_decode($wpdp_fonts));
		if(!empty($_POST) && !$wpdp_pro){
			//pree($_POST);exit;
			if(isset($_POST['wpdp_options']))
			update_option('wpdp_options', sanitize_wpdp_data($_POST['wpdp_options']));
			
		}
		

?>
	<div class="wpdp_free_settings">
    
    
    <?php
		if(!empty($wpdp_options)){

			
			//pree($_POST);
			//pree($wpdp_options_db);
			foreach($wpdp_options as $item=>$type){
				?>
                
                <div style="clear:both; margin-top:20px;">
                <label for="<?php echo $item; ?>"><?php echo ucwords($item); ?>:</label>
                <?php 
					switch($type){
						case 'text':
						?>
                
                <input id="<?php echo $item; ?>" type="text" value="<?php echo isset($$item) ? $$item : '';  ?>" name="wpdp[<?php echo $current_option ?>][wpdp_options][<?php echo $item; ?>]" class="wpdp-useable" data-name="[wpdp_options][<?php echo $item; ?>]" /> <a href="http://api.jqueryui.com/datepicker/#option-<?php echo $item; ?>" target="_blank" title="<?php _e('Click here for documentation about', 'wp-datepicker'); ?> <?php echo $item; ?>" style="text-decoration:none">?</a>
                <?php
						break;
						case 'checkbox':
						?>
                
                <input id="<?php echo $item; ?>" type="checkbox" value="1" <?php echo (isset($$item) && $$item == 1)?'checked':''; ?> name="wpdp[<?php echo $current_option ?>][wpdp_options][<?php echo $item; ?>]" class="wpdp-useable" data-name="[wpdp_options][<?php echo $item; ?>]" /> <a href="http://api.jqueryui.com/datepicker/#option-<?php echo $item; ?>" target="_blank" title="<?php _e('Click here for documentation about', 'wp-datepicker'); ?> <?php echo $item; ?>" style="text-decoration:none">?</a>
                <?php
						break;						
					}
						?>
                </div>
                <?php
			}
		}
    ?>
    </div>
<?php		
	}	
	if(!function_exists('wpdp_get')){
		function wpdp_get($index){
			global $wpdp_options;

			$wpdp_options_db = get_option('wpdp_options');
			$val = '';
			if(isset($wpdp_options_db[$index])){
				$val = $wpdp_options_db[$index];
			}
			return $val;
		}
	}


	if(!function_exists('wpdp_generate_js_file')){
	    function wpdp_generate_js_file(){

	        if(function_exists('wpdp_footer_scripts')){

                global  $wpdp_dir, $wpdp_new_js, $wpdp_gen_file;
                $js_file = $wpdp_dir.$wpdp_new_js;
                $wpdp_gen_file = true;

                ob_start();
                wpdp_footer_scripts();

                $js_code = ob_get_contents();

                ob_end_clean();

                $js_code = str_replace('<script type="text/javascript" language="javascript">', '', $js_code);
                $js_code = str_replace("</script>", '', $js_code);

                file_put_contents($js_file, $js_code);

            }
        }
    }

	if(!function_exists('wpdp_generate_css_file')){

	    function wpdp_generate_css_file(){

	        if(function_exists('wpdp_footer_scripts_pro')){

                global  $wpdp_dir, $wpdp_new_css;
                $css_file = $wpdp_dir.$wpdp_new_css;



                ob_start();

                wpdp_footer_scripts_pro();

                $css_code = ob_get_contents();

                ob_end_clean();

                $css_code = str_replace('<style type="text/css">', '', $css_code);
                $css_code = str_replace("</style>", '', $css_code);

                file_put_contents($css_file, $css_code);

            }

        }
    }
	
    if(!function_exists('wpdp_get_instance_id_from_name')){
        function wpdp_get_instance_id_from_name($option_name){

            $instance_id = explode('-', $option_name);
            $instance_id = end($instance_id);
            return $instance_id;

        }
    }

    add_action('wp_ajax_wpdp_update_developer_options', 'wpdp_update_developer_options');

    if(!function_exists('wpdp_update_developer_options')){


        function wpdp_update_developer_options(){

            $result = array(
                    'status' => 'false',
            );

            if(!empty($_POST) && isset($_POST['wpdp_developer_options'])){


                if(!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'wpdp_nonce_action')){

                    wp_die(__('Sorry, your nonce did not verify.', 'text-domain'));
                }else{

                    //your code here
                    $wpdp_developer_options = sanitize_wpdp_data($_POST['wpdp_developer_options']);
                    $result['status'] = update_option('wpdp_developer_options', $wpdp_developer_options);

                }

            }

            wp_send_json($result);

        }
    }
	
	add_action('wp_ajax_wpdb_refresh_scripts_ajax', 'wpdb_refresh_scripts_ajax');
	if(!function_exists('wpdb_refresh_scripts_ajax')){
		function wpdb_refresh_scripts_ajax(){

			if(isset($_POST['wpdp_nonce']) && wp_verify_nonce($_POST['wpdp_nonce'], 'wpdp_nonce_action')){

				wpdp_generate_css_file();
				wpdp_generate_js_file();

			}

		}
	}
	
	
	
		
	include 'functions_inner.php';