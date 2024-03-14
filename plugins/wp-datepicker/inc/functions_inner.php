<?php

    global $wpdp_option_name;

    $wpdp_option_name ='wp_datepicker_option-';

    if(!function_exists('wpdp_new_option_name')){

        function wpdp_new_option_name(){

            global  $wpdb, $wpdp_option_name;

            $wpdp_option_name = 'wp_datepicker_option-';



            $wpdp_option_query = "SELECT * FROM {$wpdb->prefix}options WHERE option_name LIKE '" . $wpdp_option_name . "%'";

            //pree($wpdp_option_query);exit;

            $get_results = $wpdb->get_results($wpdp_option_query, OBJECT);

            //pree($get_results);exit;

            if (!empty($get_results)) {

                //if already have some custom wpdp than get max number wpdp increment 1 and add new wpdp option
                $wpdp_id_array = array();



                foreach ($get_results as $key => $value) {
                    $option = $value->option_name;
                    $array = explode('-', $option);
                    array_push($wpdp_id_array, end($array));
                }


                $max_wpdp_id = max($wpdp_id_array);
                $new_wpdp_id = $max_wpdp_id + 1;
                $wpdp_id = $wpdp_option_name . $new_wpdp_id;


            } else {

                //if custom wpdp not exist than add new wpdp
                $wpdp_id = $wpdp_option_name . '1';
            }

            return $wpdp_id;
        }
    }

   if(!function_exists('wpdp_get_datepicker_list')){

       function wpdp_get_datepicker_list($sort = 'ASC'){

           global  $wpdb, $wpdp_option_name;

           $results =  $wpdb->get_results("SELECT * FROM {$wpdb->prefix}options WHERE option_name LIKE '" . $wpdp_option_name . "%';", OBJECT);


           usort($results, function ($a, $b)use($sort){

               $option_a = $a->option_name;
               $option_b = $b->option_name;

               $option_a = explode('-', $option_a);
               $option_b = explode('-', $option_b);

               $option_a = $option_a[1];
               $option_b = $option_b[1];

               if($sort == 'ASC'){

                   return ($option_a > $option_b) ? 1 : -1;

               }elseif($sort == 'DESC'){

                   return ($option_a > $option_b) ? -1 : 1;

               }else{
                   return 0;
               }


           });

           return $results;
       }
   }

   if(!function_exists('wpdp_init_datepicker_list')){

       function wpdp_init_datepicker_list(){

           $wpdp_get_datepicker_list = wpdp_get_datepicker_list();

           if(empty($wpdp_get_datepicker_list)){

               update_option(wpdp_new_option_name(), array());

               $wpdp_get_datepicker_list = wpdp_get_datepicker_list();
           }


           return current($wpdp_get_datepicker_list)->option_name;

       }
   }

   if(!function_exists('wpdp_get_datepicker_by_id')){

       function wpdp_get_datepicker_by_id($option_id, $default = false){

           global  $wpdb;
           $result =  $wpdb->get_results("SELECT * FROM {$wpdb->prefix}options WHERE option_id = '" . $option_id . "' ;", OBJECT);

           if(!empty($result)){
               return (maybe_unserialize(current($result)->option_value));
           }else{
               return $default;
           }



       }
   }

   if(!function_exists('wpdp_get_current_option_name')){

       function wpdp_get_current_option_name(){

         return  wpdp_init_datepicker_list();

       }
   }

   if(!function_exists('wpdp_add_new_datepicker_ajax')){
       function wpdp_add_new_datepicker_ajax(){

           global $wpdp_premium_link, $wpdp_dir, $wpdp_url, $wpdp_pro, $wpdp_data, $wpdp_options, $wpdp_styles, $wpdp_gen_file;




           if(isset($_POST['wpdp_add_new_datepicker']) || isset($_POST['wpdp_get_selected_datepicker']) || isset($_POST['wpdp_form_data'])){


               if (
                   ! isset( $_POST['wpdp_nonce_action_field'] )
                   || ! wp_verify_nonce( $_POST['wpdp_nonce_action_field'], 'wpdp_nonce_action' )
               ) {

                   print __('Sorry, your nonce did not verify.', 'wp-datepicker');
                   exit;

               } else {


                  if(isset($_POST['wpdp_add_new_datepicker'])){

                       $wpdp_new_option_name = wpdp_new_option_name();
                       update_option($wpdp_new_option_name, array());
                       echo json_encode(array('option_name' => $wpdp_new_option_name, 'default_img' => $wpdp_url.'pro/img.default.jpg'));

                   }elseif (isset($_POST['wpdp_get_selected_datepicker'])){

                      $option_name = sanitize_wpdp_data($_POST['wpdp_get_selected_datepicker']);

                      ob_start();

                      include_once ('wpdp_settings.php');

                      $content = ob_get_contents();

                      ob_clean();

                      echo $content;


                  }elseif(isset($_POST['wpdp_form_data'])){



                      parse_str($_POST['wpdp_form_data'], $wpdp_form_data);



                      if(isset($wpdp_form_data['wpdp'])){

                          $wpdp_data_post= sanitize_wpdp_data($wpdp_form_data['wpdp']);


                          $option_name = current(array_keys($wpdp_data_post));
                          $options_data_array = current($wpdp_data_post);


                          if(strlen($option_name)){

                              update_option($option_name, $options_data_array);

                          }

                      }

                  }

                  if(function_exists('wpdp_generate_js_file')){


                      wpdp_generate_js_file();

                  }

                   if(function_exists('wpdp_generate_css_file')){

                       wpdp_generate_css_file();

                   }


               }
           }

           wp_die();
       }
   }

   add_action('wp_ajax_wpdp_add_new_datepicker_ajax', 'wpdp_add_new_datepicker_ajax');
   add_action('init', 'wpdp_update_previous_version');

    if(!function_exists('wpdp_update_previous_version')){

        function wpdp_update_previous_version(){

            $wpdp_get_datepicker_list = wpdp_get_datepicker_list();
            $wpdp_previous_version_compatible = get_option('wpdp_previous_version_compatible');

            if(!$wpdp_previous_version_compatible && empty($wpdp_get_datepicker_list)){

                $updated_array = array();

                $wpdp_selectors = get_option('wp_datepicker');
                $wp_datepicker_language = get_option( 'wp_datepicker_language');
                $wp_datepicker_weekends = get_option( 'wp_datepicker_weekends');
				$wp_datepicker_autocomplete = get_option( 'wp_datepicker_autocomplete', true);
                $wp_datepicker_beforeShowDay = get_option( 'wp_datepicker_beforeShowDay');
                $wp_datepicker_months = get_option( 'wp_datepicker_months');
                $wp_datepicker_wpadmin = get_option( 'wp_datepicker_wpadmin');
                $wp_datepicker_readonly = get_option( 'wp_datepicker_readonly');
                $wpdp_options = get_option('wpdp_options', array());
                $wpdp_fonts = get_option('wpdp_fonts');

                $updated_array['wp_datepicker'] = $wpdp_selectors ? $wpdp_selectors : '';
                $updated_array['wp_datepicker_language'] = $wp_datepicker_language ? $wp_datepicker_language : '';
                $updated_array['wp_datepicker_weekends'] = $wp_datepicker_weekends ? $wp_datepicker_weekends : '';
				$updated_array['wp_datepicker_autocomplete'] = $wp_datepicker_autocomplete ? $wp_datepicker_autocomplete : '';
                $updated_array['wp_datepicker_beforeShowDay'] = $wp_datepicker_beforeShowDay ? $wp_datepicker_beforeShowDay : '';
                $updated_array['wp_datepicker_months'] = $wp_datepicker_months ? $wp_datepicker_months : '';
                $updated_array['wp_datepicker_wpadmin'] = $wp_datepicker_wpadmin ? $wp_datepicker_wpadmin : '';
                $updated_array['wp_datepicker_readonly'] = $wp_datepicker_readonly ? $wp_datepicker_readonly : '';
                $updated_array['wpdp_fonts'] = $wpdp_fonts ? $wpdp_fonts : '';
                $updated_array['wpdp_options'] = !empty($wpdp_options) ? $wpdp_options : '';

                $status = update_option(wpdp_new_option_name(), $updated_array);

                if($status){

                    foreach ($updated_array as $option_name => $option_value){
                        delete_option($option_name);
                    }

                    update_option('wpdp_previous_version_compatible', true);

                }



            }


        }

    }