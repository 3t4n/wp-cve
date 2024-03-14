<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	

	function ap_search_orderby($orderby) {
	
		global $wpdb, $ap;	
	
		if ( $ap!='' ) {
			$orderby = $wpdb->prefix . "posts.post_title ASC";	
		}
	
		return $orderby;
	
	}
	
	add_filter( 'wp_title', 'ap_page_title', 10, 2 );
	
	function ap_page_title($title){
		global $ap;
		if ( $title!='' && $ap!=''){
			return $title;//.' with '.$ap.' | ';
		}else{
			return $title;
		}
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
				echo '<pre class="red">';
				print_r($data);
				echo '</pre>';	
		
		}	 
	} 


	
	if(!function_exists('ap_init_actions')){
		function ap_init_actions(){
			global $ap_current_cat;
	
			$categories = get_the_category();
			$ap_current_cat = $categories;
			//pree($ap_current_cat);
		}
	}


	function ap_menu()
	{
		global $ap_customp, $ap_datap;
		//pree($ap_datap);
		$title = $ap_datap['Name'].($ap_customp?' '.__('Pro', 'alphabetic-pagination'):'');
		
		add_options_page($title, $title, 'activate_plugins', 'alphabetic_pagination', 'alphabetic_pagination');



	}


	function alphabetic_pagination(){ 


		
		if ( !current_user_can( 'administrator' ) )  {



			wp_die( __( 'You do not have sufficient permissions to access this page.', 'alphabetic-pagination' ) );



		}



		global $wpdb, $ap_group, $ap_langs, $ap_settings_saved;

		if(!empty($_POST) && isset($_POST['ap_submit_settings']) && current_user_can( 'administrator')){
				
			if ( 
				! isset( $_POST['wp_nonce_action_field'] ) 
				|| ! wp_verify_nonce( $_POST['wp_nonce_action_field'], 'wp_nonce_action' ) 
			) {
			
			   _e('Sorry, your nonce did not verify.', 'alphabetic-pagination');
			   exit;
			
			}else{    
	


				if(isset($_REQUEST['ap_layout']) && in_array($_REQUEST['ap_layout'], array('H', 'V')))		
		
				{
		
					ap_update_option( 'ap_layout', sanitize_ap_data($_REQUEST['ap_layout']) );
		
				}
		
				
		
				if(isset($_REQUEST['ap_case']) && in_array($_REQUEST['ap_case'], array('U', 'L')))		
		
				{
		
					ap_update_option( 'ap_case', sanitize_ap_data($_REQUEST['ap_case']) );
		
				}
				if(isset($_REQUEST['ap_single']) && in_array($_REQUEST['ap_single'], array(1, 0)))		
		
				{
		
					ap_update_option( 'ap_single', sanitize_ap_data($_REQUEST['ap_single']) );
		
				}		
			
				if(isset($_REQUEST['ap_dom'])){
					
					if($_REQUEST['ap_dom']!='')
					ap_update_option( 'ap_dom', sanitize_ap_data($_REQUEST['ap_dom']) );
					else
					ap_update_option( 'ap_dom', '' );
		
				}
				
				if(isset($_REQUEST['ap_implementation'])){
					
					if($_REQUEST['ap_implementation']!=''){
						ap_update_option( 'ap_implementation', sanitize_ap_data($_REQUEST['ap_implementation']) );
					}else{
						ap_update_option( 'ap_implementation', '' );
					}
		
				}		
				
				
				
				if(isset($_REQUEST['ap_tax']) && !empty($_REQUEST['ap_tax']))		
		
				{
		
		
					ap_update_option( 'ap_tax', sanitize_ap_data($_REQUEST['ap_tax']) );
		
				}
				
				if(isset($_REQUEST['ap_tax_types']) && !empty($_REQUEST['ap_tax_types']))		
		
				{
		
		
					ap_update_option( 'ap_tax_types', sanitize_ap_data($_REQUEST['ap_tax_types']) );
		
				}	
				
				if(isset($_REQUEST['ap_tax_types_x']) && !empty($_REQUEST['ap_tax_types_x']))		
		
				{
		
		
					ap_update_option( 'ap_tax_types_x', sanitize_ap_data($_REQUEST['ap_tax_types_x']) );
		
				}	
				
				if(isset($_REQUEST['ap_where_meta']))		
		
				{
					ap_update_option( 'ap_where_meta', sanitize_ap_data($_REQUEST['ap_where_meta']) );
		
				}	
				
				if(isset($_REQUEST['ap_allowed_pages']))		
		
				{
					$ap_allowed_pages = sanitize_ap_data($_REQUEST['ap_allowed_pages']);		
					ap_update_option( 'ap_allowed_pages', $ap_allowed_pages );
		
				}	
				
				if(isset($_REQUEST['ap_query']))		
		
				{
					$ap_query = sanitize_ap_data($_REQUEST['ap_query']);		
					ap_update_option( 'ap_query', $ap_query );
		
				}			
				
				if(isset($_REQUEST['ap_post_types']))		
		
				{
					$ap_post_types = sanitize_ap_data($_REQUEST['ap_post_types']);
					ap_update_option( 'ap_post_types', $ap_post_types );
		
				}					
				
				
				//pree($_REQUEST);exit;							
				
				if(isset($_REQUEST['ap_all'])){
				
					if($_REQUEST['ap_all']==1)
					ap_update_option( 'ap_all', 1);
					else
					ap_update_option( 'ap_all', 0);
				}			
				
				if(isset($_REQUEST['ap_numeric_sign'])){
				
					if($_REQUEST['ap_numeric_sign']==1)
					ap_update_option( 'ap_numeric_sign', 1);
					else
					ap_update_option( 'ap_numeric_sign', 0);
				}			
						
				if(isset($_REQUEST['ap_reset_sign'])){
				
					if($_REQUEST['ap_reset_sign']==1)
					ap_update_option( 'ap_reset_sign', 1);
					else
					ap_update_option( 'ap_reset_sign', 0);
				}	
						
				if(isset($_REQUEST['ap_reset_theme']) && in_array($_REQUEST['ap_reset_theme'], array('dark', 'light'))){									
					ap_update_option( 'ap_reset_theme', sanitize_ap_data($_REQUEST['ap_reset_theme']));
				}						
						
				if(isset($_REQUEST['ap_lang']) && !empty($_REQUEST['ap_lang']))		
		
				{
		
		
					ap_update_option( 'ap_lang', sanitize_ap_data($_REQUEST['ap_lang']) );
		
				}			
					
				/*        
				if(isset($_REQUEST['ap_style']) && !empty($_REQUEST['ap_style'])){
					ap_update_option( 'ap_style', $_REQUEST['ap_style'] );
				}	               
				*/
						
				
				
				if(isset($_REQUEST['ap_disable'])){
					ap_update_option( 'ap_disable', sanitize_ap_data($_REQUEST['ap_disable']) );
					
				}	
				if(isset($_REQUEST['ap_grouping'])){
					ap_update_option( 'ap_grouping', sanitize_ap_data($_REQUEST['ap_grouping']) );
					
				}			
				
				if(isset($_REQUEST['ap_wc_shortcodes'])){
					ap_update_option( 'ap_wc_shortcodes', sanitize_ap_data($_REQUEST['ap_wc_shortcodes']) );
					
				}									
				
				if(isset($_REQUEST['ap_auto_post_statuses'])){
					ap_update_option( 'ap_auto_post_statuses', sanitize_ap_data($_REQUEST['ap_auto_post_statuses']) );
					
				}		
				if(isset($_REQUEST['ap_auto_post_types'])){
					ap_update_option( 'ap_auto_post_types', sanitize_ap_data($_REQUEST['ap_auto_post_types']) );
					
				}

                $ap_settings_saved = true;
			}
			
		}
		$ap_disable = (ap_get_option('ap_disable')==0?false:true);
		$ap_group = (ap_get_option('ap_grouping')==0?false:true);
        ap_refresh_saved_settings();
				
		include('ap_settings.php');	

		

	}	

	
	function ap_add_query_vars( $vars ){
	  global $ap_vv;
	  $ap_vv = $vars;
	  return $vars;
	}
	
	
	
	function ap_get_query_vars(){
		global $ap_vv;
		$v_val = array();
		if(!empty($vv)){
			foreach($vv as $vals){
				$v_val[$vals] = get_query_var($vals, '');
			}
			$v_val = array_filter($v_val, 'strlen');
			$v_val = array_filter($v_val, 'is_numeric');
			$v_val = array_keys();
		}
											
		return $v_val;									
	}

	function ap_remove_var($url, $key) { 
		$url = preg_replace('/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&'); 
		$url = substr($url, 0, -1); 
		
		$in = '/'.$key;
		if(stristr($url, $in)){
			$url2 = explode($in, $url);
			$url = current($url2);
		}
		
		return $url; 
	}
	

	
	if(!function_exists('ap_url_reset')){
		function ap_url_reset($alphabetz_bar){  
		
			$ap_reset_sign = (ap_get_option('ap_reset_sign')==0?false:true);
			if($ap_reset_sign){
				global $arg, $ap, $ap_reset_theme;
	
				$url = $url_x = sanitize_url($_SERVER['REQUEST_URI']);
				
				if(
					(!isset($_GET[$arg]) && get_query_var('paged', 0)!=0)
					||	
					(isset($_GET[$arg]) && $ap!='numeric')
				){
					$url_x = ap_remove_var( $url, 'page' );
				}else{
					$url_x = $url;
				}
				
				$url_reset = parse_url($url);
				$url_reset = (isset($url_reset['path'])?$url_reset['path']:'');		
				
				

				$alphabetz_bar .= '<li class="ap_reset '.$ap_reset_theme.'">';
				$alphabetz_bar .= '<a href="'.$url_reset.'">&nbsp;</a>';
				$alphabetz_bar .= '</li>';				
			
			}			
			
			return $alphabetz_bar;
		}
	}
				
	if(!function_exists('alphabets_bar')){
                    function alphabets_bar(){                                			
                                            global $ap, $ap_customp, $arg, $ap_queries, $ap_group;
											
											$languages_selected = ap_get_option('ap_lang', array());
											$languages_selected = is_array($languages_selected)?$languages_selected:array();
											//pree($languages_selected);
											
											$url = $_SERVER['REQUEST_URI'];
											$url_x = ap_remove_var($url, 'page');
                                            $alphabets = ap_alphabets();
                                            
											//pree($url_x);
											
                                            $alphabetz_bar = '';

                                            foreach($alphabets as $language=>$alphabetz){
												$selected = '';											
												if(is_admin()){
													$selected = 'hide';	
													if(in_array(ucwords($language), $languages_selected)){
														$selected = 'ap_slanguage';
														
													}
												}
                                         

																					    
                                            $alphabetz_bar .= '<ul class="ap_'.$language.' '.$selected.' ap_pagination case_'.ap_get_option('ap_case').' layout_'.(is_admin()?'H':ap_get_option('ap_layout')).' '.ap_get_option('ap_style').' by_'.$ap_queries.'">';
											
											
											$alphabetz_bar = ap_url_reset($alphabetz_bar);
											
											$alphabetz_bar .= '<li class="ap_numeric">';
                                            $alphabetz_bar .= '<a href="'.add_query_arg( array($arg => 'numeric'), $url_x).'"  class="'.(strtolower($ap)=='numeric'?'selected':'').'">#</a>';

                                            $alphabetz_bar .= '</li>';
																								
											
											$alpha_count = 0;
											$alpha_jump = ($ap_group?4:0);
											$alpha_jump_count = 0;
											$alpha_jump_arr = array();
											
                                            foreach($alphabetz as $num=>$alphabet){
											
													$alpha_count++;	
											
													if(
														(!isset($_GET[$arg]) && get_query_var('paged', 0)!=0)
														||	
														(isset($_GET[$arg]) && $ap!=$alphabet)
													){
														$url_x = ap_remove_var( $url, 'page' );
													}else{
														$url_x = $url;
													}	
												
													
												
													if($alpha_jump==0){					
														
																									
														$alphabetz_bar .= '<li class="ap_'.strtolower($alphabet).' ap_an_'.$num.'">';
														$alphabetz_bar .= '<a href="'.add_query_arg( array($arg => $alphabet), $url_x).'" class="'.(strtolower($ap)==$alphabet?'selected':'').'">'.$alphabet.'</a>';
														$alphabetz_bar .= '</li>';
																												
													}else{
														
														$alpha_jump_count++;
														
														if($alpha_jump_count<=$alpha_jump){
															$alpha_jump_arr[] = $alphabet;
															if($alpha_jump_count==$alpha_jump || $alpha_count==count($alphabetz)){
																$alphabet_arg = current($alpha_jump_arr).(current($alpha_jump_arr)!=end($alpha_jump_arr)?'-'.end($alpha_jump_arr):'');
																$alphabet_str = implode(' ap_', $alpha_jump_arr);
																$alphabetz_bar .= '<li class="ap_'.strtolower($alphabet_str).'">';
																$alphabetz_bar .= '<a href="'.add_query_arg( array($arg => $alphabet_arg), $url_x).'" class="'.(strtolower($ap)==$alphabet_arg?'selected':'').'">'.$alphabet_arg.'</a>';
																$alphabetz_bar .= '</li>';
															}
														}else{					
															$alpha_jump_arr = array();
															$alpha_jump_arr[] = $alphabet;
															$alpha_jump_count = 1;
														}
														
														
														
														
													}
												
												}

												$alphabetz_bar .= '</ul>';
                                            }
											
											//pre($alphabetz_bar);
											
                                            //pre($alpha_jump_arr);
                                            return $alphabetz_bar;
											
											
                    }
       }
                                
	if(!function_exists('ap_tax_types_callback')){    
		function ap_tax_types_callback() {
			
			if(!isset($_POST['type']))
			die();
			
			global $wpdb;
			$return['msg'] = false;
			$return = array();
			
			$args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'parent'                   => '',
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => 0,
				'hierarchical'             => 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'taxonomy'                 => sanitize_ap_data($_POST['type']),
				'pad_counts'               => false 
			
			); 
			
			$categories = get_categories( $args );
			
			if(!empty($categories)){
				$return['msg'] = true;
				$return['data'][0] = 'Select to ';
				$return['selected'] = ap_get_option('ap_tax_types');
				$return['selected_x'] = ap_get_option('ap_tax_types_x');
				$lang = ap_get_option('ap_lang');
				//print_r($lang);
				foreach($categories as $cats){
					$return['data'][$cats->cat_ID] = $cats->cat_name;//(!in_array('English')?'Category ID: '.$cats->cat_ID:$cats->cat_name);
				}
			}
			//print_r($return);exit;
			echo json_encode($return);
			exit;
		}		                        
	}
	function ap_gtz($value){
		return $value > 0;
	}
	
	function ap_tax_types_get(){
		
		$ret = array();
		
		$ap_tax_types = ap_get_option('ap_tax_types');
		$ap_tax_types = (is_array($ap_tax_types)?$ap_tax_types:array());			
		$ap_tax_types = array_filter($ap_tax_types, 'ap_gtz');
		
		//pree($ap_tax_types);
		
		$ap_tax_types_x = ap_get_option('ap_tax_types_x');
		$ap_tax_types_x = (is_array($ap_tax_types_x)?$ap_tax_types_x:array());
		$ap_tax_types_x = array_filter($ap_tax_types_x, 'ap_gtz');		
		
		$ret['ap_tax_types'] = $ap_tax_types;
		$ret['ap_tax_types_x'] = $ap_tax_types_x;
		
		return $ret;
		
	}
	
	if(!function_exists('ap_has_term')){
	function ap_has_term($taxonomy){
			
			global $ap_current_cat;
			
			$response = false;
			
					
			$ap_tax_types_get = ap_tax_types_get();
			extract($ap_tax_types_get);

			
			//pree($taxonomy);
			//pre($ap_tax_types);
			
			switch($taxonomy){
				
				case 'category':
					//pree($ap_current_cat);
					$categories = $ap_current_cat;//get_the_category();
					$current_cat = ((isset($categories[0]) && isset($categories[0]->cat_ID))?$categories[0]->cat_ID:'');					
					
					//pre($current_cat);
										
					$response = is_category();
					if($response){
						$current_cat = get_query_var('cat'); 
						
					}					
					
					//pre($response);
					//pree($ap_tax_types);
					//pree($ap_tax_types_x);
					
					//pre(is_array($ap_tax_types_x));
					//pre(!empty($ap_tax_types_x));
					//pre(!in_array($current_cat, $ap_tax_types_x));
					
					if(!empty($ap_tax_types)){
						$response = (
											in_array($current_cat, $ap_tax_types)
										&& 
											!in_array($current_cat, $ap_tax_types_x)
											
									);
					}
					
					//pre($response);
					
				break;
				case 'post_tag':
					$response = is_tag();
				break;	
				case 'product_cat':
				
					$cate = get_queried_object();
					
					
			
					if(!empty($ap_tax_types)){
						
						if(is_object($cate) && isset($cate->term_id)){
							$cateID = $cate->term_id;
							$response = (
												in_array($cateID, $ap_tax_types)
											&& 
												!in_array($cateID, $ap_tax_types_x)
												
										);
						}
					}
					
										
				break;		
				case 'product_visibility':
					$response = true;		
				break;			
				default:
					$response = has_term('', $taxonomy);					
				break;							
				
			}
			
			
			
			return $response;
		}
	}

	if(!function_exists('ap_go')){
		function ap_go(){
			
			
			
			$allowed_taxes = ap_get_option('ap_tax');
			//pree($allowed_taxes);
			//pree(ap_get_option('ap_all'));exit;
			//ap_compability
			$ap_go = FALSE;
			if(!ap_get_option('ap_all') && !empty($allowed_taxes) && $allowed_taxes[0]!=''){ // empty string logic for very first selection having empty
				//pree($allowed_taxes);exit;
				foreach($allowed_taxes as $taxonomy_allowed){
					//pree($taxonomy_allowed);
					$ap_go = ap_has_term($taxonomy_allowed);
					
					if($ap_go)
					break;
				}
			}elseif(1){
				//pree(is_archive());
			}
	
			 if(!$ap_go && ap_get_option('ap_all')){				
				$ap_go = TRUE; 				
			}
			
			//pree($ap_go);exit;
			
			if($ap_go){		
				global $post, $wp_query, $ap;
				
				$condition = true;
				if($ap=='' && have_posts() && !ap_get_option('ap_single'))
				$condition = ($wp_query->post_count>1);
				
				//pree(have_posts());
				//pree(is_archive());
				//pree(is_single());
				//pree($condition);
				//pree(is_category() || is_product_category());
				
				$ap_go = (
								(
										!is_single() //NOT VALID FOR SINGLE PAGE/POST BECAUSE NO LOOP IS THERE
								
									|| 
									
										(is_single() && (is_category() || (ap_is_woocommerce_page() && is_product_category())))
									
								)
								
							 && 
							 
							 	$condition
						);		
			}
			
			//pree($ap_go);exit;
			
			return $ap_go;
		}
	}
	
	


	
	if(!function_exists('ap_start')){
		function ap_start(){	

				ap_update_option( 'ap_case', 'U');
				ap_update_option( 'ap_layout', 'H');
				ap_update_option( 'ap_dom', '#content' );
				
		}	
	}

	if(!function_exists('ap_end')){
	function ap_end(){	

				delete_option( 'ap_case');
				delete_option( 'ap_layout');
				delete_option( 'ap_dom');

		}
	}	

	

	if(!function_exists('ap_where_meta')){
		function ap_where_meta($where='', $ap_force=''){
			
			$ret = array();
			
			global $wpdb, $ap, $where_meta, $ap_langs_multiple, $ap_language_selected;
			
			$ap_multiple_characters_query = array();
			$ap_multiple_characters_query['include'] = array();
			$ap_multiple_characters_query['exclude'] = array();
			
			if(!$ap_language_selected){
				$ap_alphabets = ap_alphabets();
				//pree($ap_alphabets);
				if(
						array_key_exists($ap_language_selected, $ap_langs_multiple)
				){
					//pree($ap_language_selected);
					//pree($ap_langs_multiple);
					if(array_key_exists($ap, $ap_langs_multiple[$ap_language_selected])){
						$ap_multiple_characters_query['include'] = $ap_langs_multiple[$ap_language_selected][$ap];
					}else{						
						if(!empty($ap_langs_multiple[$ap_language_selected])){
							foreach($ap_langs_multiple[$ap_language_selected] as $char_ind=>$char_arr){
								$ap_multiple_characters_query['exclude'][] = $char_ind;
									if(!empty($char_arr)){
										foreach($char_arr as $char){
											$ap_multiple_characters_query['exclude'][] = $char;
										}
									}

							}
							
						}
					}
					//pree($ap_multiple_characters_query);
				}
				
			}
			//pree($ap_langs_multiple);
			//pree($where);
			
			//pree($ap_multiple_characters_query);
			
			//$where_meta = ap_get_option('ap_where_meta');
			$continuity = ($ap_force=='');
			$ap = ($continuity?$ap:$ap_force);
			//pree($continuity);
			//pree($where_meta);
			
			//pre($where);
			//pree($ap);exit;
			
			$ands = explode('AND', $where);
			$awhere = array();
			//pre($ands);
			if(!empty($ands) && $where_meta!=''){
				foreach($ands as $and){
					//pre(stripos($and, $wpdb->postmeta));
					if(stripos($and, $wpdb->postmeta)){								
						$ob += substr_count($and, '(');
						$cb += substr_count($and, ')');
						//pree($ob);
						//pree($cb);
					}else{
						$awhere[] = $and;
					}
				}
				//pre($awhere);
				if(!empty($awhere)){
					$where = implode('AND', $awhere).')';
					if(!$continuity){
						$whr = trim($where);
						//pre($whr);
						$where = substr(trim($whr), strlen('AND'), strlen($whr));
						//pre($where);
					}
					//pree($where);exit;
					$where .= " AND ($wpdb->postmeta.meta_key = '$where_meta' AND $wpdb->postmeta.meta_value LIKE ".ap_char_type()."'".esc_sql($ap)."%')  ";
					//pree($where);exit;
					//pree($continuity);
					
				}
			}else{				
			
				if(
						!empty($ap_multiple_characters_query)
					&&
						(
								!empty($ap_multiple_characters_query['include'])
							||
								!empty($ap_multiple_characters_query['exclude'])
						)
				){
					$where .= ' AND ('.$wpdb->prefix.'posts.post_title LIKE '.ap_char_type().'"'.esc_sql($ap).'%"';
					if(!empty($ap_multiple_characters_query['include'])){
						foreach($ap_multiple_characters_query['include'] as $char){
							$where .= ' OR '.$wpdb->prefix.'posts.post_title LIKE '.ap_char_type().'"'.esc_sql($char).'%"';
						}
					}
					if(!empty($ap_multiple_characters_query['exclude'])){
						foreach($ap_multiple_characters_query['exclude'] as $char){
							$where .= ' AND '.$wpdb->prefix.'posts.post_title NOT LIKE '.ap_char_type().'"'.esc_sql($char).'%"';
						}
					}
					
					$where .= ' )';
				}else{
					$where .= ' AND '.$wpdb->prefix.'posts.post_title LIKE '.ap_char_type().'"'.esc_sql($ap).'%"';
				}
				
				
			}	
			
			//pree($ap_language_selected);
			//pree($ap_langin);
			//pree($uniqe_query);
			//pree($ap_langs_multiple);
			//pree($where);
			//$ret['where'] = $where;
			//$ret['unique_query'] = $unique_query;
			

						
			return $where;		
		}
	}
	
	function ap_posts_join($join) {
		
		global $wpdb, $ap, $where_meta;
		
		if ($ap!='' && $where_meta!='') {
			$join .= "LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id ";
		}
	
		return $join;
	}
	
	add_filter('posts_join', 'ap_posts_join');
		

	function ap_char_type(){
		global $ap_lang;
		
		
		$type = '';
		switch($ap_lang){
			case 'hungarian':
				$type = 'BINARY _utf8';
			break;
		}
		
		return $type;
	}
	

	if(!function_exists('ap_where_meta_clean')){
		function ap_where_meta_clean($where=''){
			
			global $where_meta;
			//pree($where_meta);						
			if($where_meta=='')
			return $where;
				
			$obt = substr_count($where, '(');
			$cbt = substr_count($where, ')');
			
			if($obt!=$cbt){
				$xwhere = explode('AND', $where);
				//pree($xwhere);
				$twhere = array();
				if(!empty($xwhere)){
					foreach($xwhere as $xwhr){
						$ob = substr_count($xwhr, '(');
						$cb = substr_count($xwhr, ')');
						//echo ($ob).'|'.($cb).' > '.$xwhr.'<br />';
						$xwhr = trim($xwhr);
						if($ob!=$cb){
							if($ob<$cb){
								$c = ($cb-$ob);
								$twhere[] = str_repeat('(', $c).$xwhr;
							}else{
								$c = ($ob-$cb);
								$twhere[] = $xwhr.str_repeat(')', $c);
							}
						}else{
							$twhere[] = $xwhr;
						}
					}
					if(!empty($twhere)){
						$twhere = array_filter($twhere, 'strlen');
						//pre($twhere);
						$where = implode(' AND ', $twhere);
					}
				}
			}else{
				$xwhere = explode('AND', $where);
				//pree($xwhere);
				$twhere = array();
				if(!empty($xwhere)){
					foreach($xwhere as $xwhr){	
							$xwhr = trim($xwhr);					
							$twhere[] = $xwhr;
					}	
				}			
				if(!empty($twhere)){
					
					$twhere = array_filter($twhere, 'strlen');	
					//pree($twhere);			
					$where = implode(' AND ', $twhere);
				}				
			}
			
			return $where;
		}
	}

	if(!function_exists('ap_compability')){
		function ap_compability(){
			global $ap_compability_arr, $post;
			$compability_mode = false;
			if($ap_compability_arr['marketpress']['activated'] &&  strpos('>'.$post->post_content, '[mp_list_products]')>0){
				$compability_mode = true;
				set_ap_query_n($ap_compability_arr['marketpress']['ap_query']);
			}		
			
			return $compability_mode;	

		}
	}
	
	function ap_disable_query_results($where='', $ap_queries=0){
	
		if(is_admin()){
			return;
		}
		global $wpdb, $ap_disable, $where_meta, $disabled_letters, $ap, $ap_auto_post_types, $ap_auto_post_statuses, $ap_customp, $ap_implementation, $ap_lang;
		//pree($ap_implementation);
		if($ap_disable){//$ap_implementation!=AP_CUSTOM && 
		
			$unique_query_where = array();
			
			$unique_query = 'SELECT DISTINCT LEFT(%s, 1) as letter FROM `%s` %s';
			
			if($where_meta!=''){
			
				$unique_query = sprintf($unique_query, 'meta_value', $wpdb->postmeta, ','.$wpdb->posts);
				$unique_query_where[] = '('.$wpdb->posts.'.ID='.$wpdb->postmeta.'.post_id AND '.$wpdb->postmeta.'.meta_key=\''.$where_meta.'\')';
			
			}else{
			
				$unique_query = sprintf($unique_query, 'post_title', $wpdb->posts, '');
			
			}
			
			//pree($unique_query);
			
			$ap_alphabets = ap_alphabets(true);
			
			$product_cat = get_query_var( 'product_cat' );
			
			
			if($unique_query!=''){

				$ap_tax_types_tax = array();
				
				$allowed_taxes = ap_get_option('ap_tax', array());
				$allowed_taxes = is_array($allowed_taxes)?$allowed_taxes:array();
					

				
				$ap_tax_types_get = ap_tax_types_get();
				//pree($ap_tax_types_get);
				extract($ap_tax_types_get);
				
				$unique_query_joins = array();
				
				
				if(!ap_get_option('ap_all') && !empty($allowed_taxes)){
				
					$unique_query_joins[] = $wpdb->term_relationships.' ON ('.$wpdb->posts.'.ID = '.$wpdb->term_relationships.'.object_id)';
					$unique_query_joins[] = $wpdb->term_taxonomy.' ON ('.$wpdb->term_relationships.'.term_taxonomy_id = '.$wpdb->term_taxonomy.'.term_taxonomy_id)';
					
				}
				
				if(!empty($unique_query_joins)){
					$unique_query .= ' LEFT JOIN '.implode(' LEFT JOIN ', $unique_query_joins);
				}	
				
				$current_category = get_queried_object();
	
				//pree($ap_auto_post_types);
				if(!empty($ap_auto_post_types)){
					
					
					
					//pree($current_category);
					
					if(is_object($current_category)){
						
						$taxObject = get_taxonomy($current_category->taxonomy);
						
						//pree($taxObject);
						
						if(is_object($taxObject) && isset($taxObject->object_type)){							
							
							$ap_auto_post_types = array_intersect($taxObject->object_type, $ap_auto_post_types);							
							
						}
						
					}
					
					$unique_query_where[] = $wpdb->posts.".post_type IN ('".implode("','", $ap_auto_post_types)."')";
					
					//pree($unique_query_where);
				}
				//pree($unique_query_where);
				if(!empty($ap_auto_post_statuses))
				$unique_query_where[] = $wpdb->posts.".post_status IN ('".implode("','", $ap_auto_post_statuses)."')";
				
				$category = array();
				if($product_cat){
					$category = get_term_by( 'slug', $product_cat, 'product_cat' );				
					$all_ids = get_posts( array(
										  'post_type' => 'product',
										  'numberposts' => -1,
										  'post_status' => 'publish',
										  'fields' => 'ids',
										  'tax_query' => array(
											 array(
												'taxonomy' => 'product_cat',
												'field' => 'slug',
												'terms' => $product_cat, /*category name*/
												'operator' => 'IN',
												)
											 ),
										  ));
					if(!empty($all_ids)){
						$unique_query_where[] = 'ID IN ('.implode(',', $all_ids).')';
					}
				}
				
				if(!ap_get_option('ap_all')){
					
					$category = (is_object($current_category)?$current_category:get_category( get_query_var( 'cat' ) ));
					
					
					
					//
					//pree($category);
					//pree($allowed_taxes);
					if(!empty($category) && !in_array($category->taxonomy, $allowed_taxes) && ap_has_term($category->taxonomy)){
						$allowed_taxes[] = $category->taxonomy;
					}
					//pree($ap_tax_types_get);
					//pree($ap_tax_types);
					
					if(!empty($allowed_taxes)){
						
						
						$allowed_taxes = array_filter($allowed_taxes, 'strlen');
						
						//pree($allowed_taxes);
						
						$unique_query_where[] = $wpdb->term_taxonomy.".taxonomy IN ('".implode("','", $allowed_taxes)."')";
						
						//pree($category->term_id);
						//pree($ap_tax_types);
							
						if(!is_wp_error($category)){
							
							if(in_array($category->term_id, $ap_tax_types)){
								
								foreach($allowed_taxes as $atax){									
									
									$for_tax = get_term_by( 'id', $category->term_id, $atax);
									
									if(!empty($for_tax))
									$ap_tax_types_tax[$atax][] = $category->term_id;
									
								}
								
								if(!empty($ap_tax_types_tax)){
									foreach($ap_tax_types_tax as $ataxes=>$aterms){											
										$unique_query_where[] = '('.$wpdb->term_taxonomy.".taxonomy IN ('".$ataxes."')  AND ".$wpdb->term_taxonomy.".term_id IN('".implode("','", $aterms)."'))";
									}
								}
							}						
							
						}
					}
					
					
					

				}
				
				if(!empty($unique_query_where)){
					$unique_query .= ' WHERE '.implode(' AND ', $unique_query_where);
				}
				
				//pree($unique_query);
				
				$unique_available = $wpdb->get_results($unique_query);
				
				//pree($unique_available);
				
				if(!empty($unique_available)){
					$available_arr = array();
					foreach($unique_available as $available){
						$available = strtolower($available->letter);
						$available_arr[] = $available;
					}
					$available_arr = (is_array($available_arr)?$available_arr:array());					
					if(!array_key_exists($ap_lang, $available_arr)){
						$available_arr = array($ap_lang=>$available_arr);
					}
					
					//pree($ap_alphabets);
					//pree($available_arr);
					
					$ap_alphabets_lang = (array_key_exists($ap_lang, $ap_alphabets)?array($ap_lang=>$ap_alphabets[$ap_lang]):array($ap_lang=>$ap_alphabets));
					
					$unique_disable = array();

					//pree($ap_alphabets_lang);
					//pree($available_arr);
					
					if(array_key_exists($ap_lang, $ap_alphabets_lang) && array_key_exists($ap_lang, $available_arr)){
						$unique_disable = array_diff($ap_alphabets_lang[$ap_lang], $available_arr[$ap_lang]);
					}
					//pree($unique_disable);
					$disabled_letters[$ap_queries] = $unique_disable;
					//pree($disabled_letters);
					
					$available_numeric = array_map(function($elem){ return is_numeric($elem); }, $available_arr);
					
					$available_numeric = array_filter($available_numeric);
					
					if(empty($available_numeric)){
						$disabled_letters[$ap_queries][] = 'numeric';
					}
					
				}
			}		
			
		}
		
		//pree($available_arr);pree($disabled_letters);
		
		return $disabled_letters;
	}
				
	if(!function_exists('ap_where_clause')){
		function ap_where_clause($where=''){
			
			//pree($where);
			global $wpdb, $ap_queries, $post, $ap_query, $ap, $ap_customp, $where_meta, $ap_allowed_pages, $ap_query_number, $ap_implementation, $ap_all_plugins, $ap_plugins_activated, $disabled_letters, $ap_auto_post_types;
			
			if(!empty($ap_auto_post_types)){
				$irrelevant_main = true;
				foreach($ap_auto_post_types as $ap_auto_post_type){
					if(strpos($where, "'".$ap_auto_post_type."'")){
						$irrelevant_main = false;
					}
					
				}
				if($irrelevant_main){
					return $where;
				}
			}
			
			
			$ap_queries++;
			
			$compability_mode = ap_compability();
			
			
			
			//pree($ap_query.' | '.$ap_queries);
			//pree($ap_allowed_pages);exit;
			//pree($post);
			//pree(is_page());
			//pree($post);exit;
			
			if(is_page()){
				//pree($ap_query.' | '.$ap_queries);
				$q_obj = get_queried_object();
				if(isset($q_obj->ID) && !empty($ap_allowed_pages) && in_array($q_obj->ID, $ap_allowed_pages)){
					
					if(array_key_exists($q_obj->ID, $ap_query_number) && $ap_query_number[$q_obj->ID]>0){
						$ap_query = $ap_query_number[$q_obj->ID];
					}
					//pree($q_obj);
				}elseif(!$compability_mode){
					//pree(is_page());
					
					return $where;
				}
			}else{
				
			}
			
			if($compability_mode){
				
			}elseif($ap_implementation!=AP_CUSTOM){
				$ap_query = ($ap_query>0?$ap_query:1);
				
				if(array_key_exists('default', $ap_query_number) && $ap_query_number['default']>0){
					$ap_query = $ap_query_number['default'];
				}
				
				//pree($ap_query);
			}
			//pree($ap_query);
			
			$dt = debug_backtrace();
			//pree($dt[1]['function']);exit;
			
			
			
			

			
			
			$where_meta = ap_get_option('ap_where_meta');
			
			
			
			$ap_query = (int)$ap_query;
			//pree($ap_query.' | '.$ap_queries);exit;
			if(!empty($ap_allowed_pages) && (!$ap_query || ($ap_query && $ap_query!=$ap_queries)))
			return $where;
			
			//pree($ap_query.' | '.$ap_queries);exit;
			//pree($ap_queries);
			//$ap_query && $ap_query==$ap_queries && 

			
			if($ap=='numeric'){
				$where .= ' AND '.$wpdb->prefix.'posts.post_title NOT REGEXP \'^[[:alpha:]]\'';
			}else{
				$ap_arr = explode('-', $ap);
				$ap_arr = array_filter($ap_arr, 'strlen');
				//pree($ap_arr);
				if(count($ap_arr)>1){
					$ap_arr = range(current($ap_arr), end($ap_arr));
					$where .= ' AND (';
					$mwhere = array();
					foreach($ap_arr as $ap){
						$mwhere[] = $wpdb->prefix.'posts.post_title LIKE '.ap_char_type().'"'.esc_sql($ap).'%"';
					}
					//COLLATE utf8_bin
					$where .= implode(' OR ', $mwhere).')';
				}elseif($ap!=''){
				
					$where = ap_where_meta($where);
					//pree($where_and_unqiue);
					//extract($where_and_unqiue);
					//pree($where);
				}
				
				
			}
			
			
			
			//pree($disabled_letters);
			
			//$disabled_letters[$ap_queries][] = $ap;
			//$disabled_letters
			
			//pree($where);
			//pree($uniqe_query);
			
			//pree($where);
			$where = ap_where_meta_clean($where);
			//pree($obt);
			
			//pree($where);

			/*if(function_exists('ap_disable_empty')){				
				//pree($ap_queries);
				ap_disable_empty($where);
			}*/
			ap_disable_query_results($where, $ap_queries);
			
			ready_alphabets();
			
			//pree(ap_is_woocommerce_page());exit;
						
			//pree($ap_queries);

			if(
				array_key_exists('woocommerce/woocommerce.php', $ap_all_plugins)
				&&				
				in_array('woocommerce/woocommerce.php', $ap_plugins_activated)
				&&
				ap_is_woocommerce_page()
				//(!empty($_GET) && (isset($_GET['orderby']) || isset($_GET['order'])))
				
			){
			}else{
				add_filter('posts_orderby', 'ap_search_orderby', 999);
				
			}
			
			$where = ($where_meta!=''?' AND ':'').$where;
			//$where = ap_where_meta_clean($where);
			//pree($where);
			
			//pree($where);//exit;
			//pree($ap_queries);
			//pree($ap_query.' | '.$ap_queries);
			
			return $where;
			
	
		}
	}
	if(!function_exists('ap_is_woocommerce_page')){
		function ap_is_woocommerce_page () {
				if(  function_exists ( "is_woocommerce" ) && is_woocommerce()){
						return true;
				}
				$woocommerce_keys   =   array ( "woocommerce_shop_page_id" ,
												"woocommerce_terms_page_id" ,
												"woocommerce_cart_page_id" ,
												"woocommerce_checkout_page_id" ,
												"woocommerce_pay_page_id" ,
												"woocommerce_thanks_page_id" ,
												"woocommerce_myaccount_page_id" ,
												"woocommerce_edit_address_page_id" ,
												"woocommerce_view_order_page_id" ,
												"woocommerce_change_password_page_id" ,
												"woocommerce_logout_page_id" ,
												"woocommerce_lost_password_page_id" ) ;
				foreach ( $woocommerce_keys as $wc_page_id ) {
						if ( get_the_ID () == get_option ( $wc_page_id , 0 ) ) {
								return true ;
						}
				}
				return false;
		}	
	}
	if(!function_exists('set_ap_query_1')){
		function set_ap_query_1(){
			global $ap_query;
			$ap_query = 1;
		}
	}	
	if(!function_exists('set_ap_query_2')){
		function set_ap_query_2(){
			global $ap_query;
			$ap_query = 2;
		}
	}	
	if(!function_exists('set_ap_query_3')){
		function set_ap_query_3(){
			global $ap_query;
			$ap_query = 3;
		}
	}	
	if(!function_exists('set_ap_query_4')){
		function set_ap_query_4(){
			global $ap_query;
			$ap_query = 4;
		}
	}	
	if(!function_exists('set_ap_query_5')){
		function set_ap_query_5(){
			global $ap_query;
			$ap_query = 5;
		}
	}	
	if(!function_exists('set_ap_query_6')){
		function set_ap_query_6(){
			global $ap_query;
			$ap_query = 6;
		}
	}						

	if(!function_exists('set_ap_query_n')){
		function set_ap_query_n($n){
			global $ap_query;
			$ap_query = $n;
		}
	}		
	
	if(!function_exists('ap_pagination')){
		function ap_pagination($query){


			
			if(!is_admin()){

				global $ap_customp, $ap_implementation, $wpdb;
				//pree($query->is_main_query());
				//pree($ap_customp.' | '.$ap_implementation);
				if($query->is_main_query() && $ap_implementation=='auto'){
					
					ap_where_filter();
					
				}
				


			}
			

		}
		
	}
	
	if(!function_exists('ap_where')){
		function ap_where($where){

			//pree($where);
			$where = ap_where_clause($where);
			//pree($where);
			return $where;

		}

	}

	if(!function_exists('ap_where_filter')){
		function ap_where_filter(){
			
			global $wpdb;
			add_filter( 'posts_where' , 'ap_where' );	
			//pree($wpdb->last_query);
			pre_render_alphabets();
		}
	}
	
	if(!function_exists('ready_alphabets')){
		function ready_alphabets(){
			global $rendered_alphabets_arr, $ap_query;
			//pree($ap_query);
			//if(empty($rendered_alphabets_arr))
			$rendered_alphabets_arr[] = alphabets_bar();
		}
	}


	if(!function_exists('render_alphabets')){
		function render_alphabets($handle = ''){
			
			if(!$handle){ return; }
	
		global $wpdb, $ap_implementation, $rendered, $rendered_alphabets_arr, $disabled_letters, $ap_disable;
		

			
		//pree($disabled_letters);exit;
		
		if(empty($rendered_alphabets_arr)){
			ready_alphabets();
		}
		//pree($disabled_letters);
		//return;
		//if(isset($_GET['debug']))
		//pre(ap_get_queries());
		//pree(alphabets_bar());
		$default_place = ap_get_option('ap_dom')==''?'#content':ap_get_option('ap_dom');
		
		
		//pree($default_place);exit;
		//$alphabets_bar = alphabets_bar();
		
		//pre($alphabets_bar);
		//pre($default_place);
		//pree($rendered_alphabets_arr);exit;
		if(!empty($rendered_alphabets_arr)){
			$alphabets_bar = implode('', $rendered_alphabets_arr);
		}
		//pree($alphabets_bar);exit;
		$script = 'jQuery(document).ready(function($) {
			setTimeout(function(){	
				if($("'.esc_attr($default_place).'").find("ul.ap_pagination").length==0){
					$("'.esc_attr($default_place).'").prepend(\''.($alphabets_bar).'\');							
				}
			}, 100);
		
		});';

		
		if(ap_get_option('ap_layout')=='V'){		
			$script .= '		
				setTimeout(function(){		
					var p = jQuery("'.esc_attr($default_place).'");		
					var position = p.position();		
					jQuery(".layout_V").css({left:position.left-26}); 
				}, 300);
			';
		}		
		
		//pree($disabled_letters);
		$script .= 'jQuery(document).ready(function($){ ';
		
		if($ap_disable){
			

			
			$disabled_letters = (empty($disabled_letters)?ap_disable_query_results():$disabled_letters);
			

			
			
		
			$script .= 'var disab_alph = setInterval(function(){ if($(".ap_pagination:visible").length>0){ clearInterval(disab_alph); ';
			
			$scripts = array();
			if(!empty($disabled_letters)){
				foreach($disabled_letters as $number=>$letters){
					//if(isset($_GET['debug']))
					//echo 'console.log("Disabled: '.($number.'('.count($letters)).')");';
					foreach($letters as $letter){
						$number_str = (is_array($number)?implode('', $number):$number);
						$letter_str = (is_array($letter)?implode('', $letter):$letter);
						$scripts[] = 'disable_ap_letters(".by_'.$number_str.' li.ap_'.$letter_str.'");';
						//$scripts[] = 'disable_ap_letters(".ap_pagination li.ap_'.$letter.'");';						
					}
				}
			}
			if(is_array($scripts) && !empty($scripts))
			$script .= implode('', $scripts);
			
			$script .= ' } }, 500); ';
		
		}
		
		$script .= '});';
				
		
		$script .= '';
		

		//pre($rendered);
		
		$style = ap_sign_visibility();
		
		//pree(ap_go());exit;
		//pree(ap_compability());exit;
		
		
			if(!$rendered){
			
				if($ap_implementation==AP_CUSTOM){
			
					$rendered=TRUE;
			
					//echo $script;		
					wp_add_inline_script( $handle, $script, 'after' );
					
			
				}elseif(ap_go() || ap_compability()){
			
					$rendered=TRUE;
					
					wp_add_inline_script( $handle, $script, 'after' );
					wp_add_inline_style( $handle, $style);
			
				}
			
			}										
	
		}

	}

		
	if(!function_exists('ap_sign_visibility')){
		function ap_sign_visibility($style=''){
				
	
			$ap_numeric_sign = (ap_get_option('ap_numeric_sign')==0?false:true);
			
			if(!$ap_numeric_sign){
				$style .= 'ul.ap_pagination li.ap_numeric{ display:none; } ';
			}		
			
			$ap_reset_sign = (ap_get_option('ap_reset_sign')==0?false:true);
			
			if(!$ap_reset_sign){
				$style .= 'ul.ap_pagination li.ap_reset{ display:none; } ';
			}		
						
			return $style;	
		}
	}

	if(!function_exists('ap_get_alphabets')){
		function ap_get_alphabets(){
		
			$alpha_array = range('a','z');
			
			return $alpha_array;
		
		}
	}
					
	if(!function_exists('ap_alphabets')){
		function ap_alphabets($single=false){
			global $ap_language_selected, $ap_lang;
			$languages_selected = ap_get_option('ap_lang', array());
			$languages_selected = (is_array($languages_selected)?$languages_selected:array());
			
			require_once('languages.php');
			global $ap_langs, $ap_langin;
			$ap_langs = is_array($ap_langs)?$ap_langs:array();
			$alphabets = array();
			$language_selected = 'english';
			//pree($languages_selected);//exit;
			if(empty($languages_selected) || in_array('English', $languages_selected)){
			
				//LETS START WITH AN OLD STRING
								
				$alphabets[$language_selected] = ap_get_alphabets();		
			
			}
			
			if(!empty($languages_selected)){
			
				foreach($languages_selected as $language_selected){
				
					$language_selected = strtolower($language_selected);
					
					if(in_array($language_selected, array_keys($ap_langs)) && !isset($alphabets[$language_selected])){
					
						$alphabets[$language_selected] = $ap_langs[$language_selected];
					
					}
				}
			}			
			if(defined('ICL_LANGUAGE_CODE') && array_key_exists(ICL_LANGUAGE_CODE, $ap_langin)){
				$lang_name = $ap_langin[ICL_LANGUAGE_CODE];		
				if($lang_name!=''){	
					
					switch($lang_name){
						case 'english':
							$alphabets['english'] = ap_get_alphabets();
						break;
						default:
							$alphabets[$lang_name] = $ap_langs[$lang_name];
						break;
					}
					
				
					
				}
			}
			
			$ap_language_selected = $language_selected;
			//pree($ap_language_selected);
			if(is_admin()){
				$alphabets['english'] = ap_get_alphabets();		
				$alphabets = array_merge($ap_langs, $alphabets);
				//pree($alphabets);
			}elseif($single && array_key_exists($ap_lang, $alphabets)){
				$alphabets = $alphabets[$ap_lang];
			}
			
			$alphabets = (is_array($alphabets)?$alphabets:array());
			
			return $alphabets;

			

		}

	}
	
	if(!function_exists('pre_render_alphabets')){
		function pre_render_alphabets( $settings=array() ) {
			//render_alphabets($settings);
			//add_action("wp_footer", 'render_alphabets', 100);
		}
	}




	function ap_plugin_links($links) { 
		global $ap_premium_link, $ap_customp;
		
		$settings_link = '<a href="options-general.php?page=alphabetic_pagination">'.__('Settings', 'alphabetic-pagination').'</a>';
		
		if($ap_customp){
			array_unshift($links, $settings_link); 
		}else{
			 
			$ap_premium_link = '<a href="'.esc_url($ap_premium_link).'" title="'.__('Go Premium', 'alphabetic-pagination').'" target="_blank">'.__('Go Premium', 'alphabetic-pagination').'</a>'; 
			array_unshift($links, $settings_link, $ap_premium_link); 
		
		}
		
		
		return $links; 
	}
	
	function register_ap_scripts() {
		
		
		global $ap_customp, $ap_reset_theme, $post, $ap_lang, $ap_disable;
		
		wp_enqueue_script(
			'ap-front',
			plugins_url('js/scripts.js', dirname(__FILE__)),
			array('jquery')
		);	

		wp_enqueue_style('ap-front', plugins_url('css/front-style.css', dirname(__FILE__)), array(), time(), 'all');	
		wp_enqueue_style('ap-mobile', plugins_url('css/mobile.css', dirname(__FILE__)), array(), time(), 'all');	

		ap_ready('ap-front');
		render_alphabets('ap-front');
				
		if(!is_admin()){			
			//wp_enqueue_style('ap-mobile', plugins_url('css/mobile.css', dirname(__FILE__)), array(), date('Ymd'), 'all' );
		}

        $allowed_pages = array(
            'alphabetic_pagination'
        );

		if(is_admin() && isset($_GET['page']) && in_array($_GET['page'], $allowed_pages)){
			
			wp_enqueue_style('fontawesome', plugins_url('css/fontawesome.min.css', dirname(__FILE__)));

            wp_enqueue_script(
                'bs-scripts',
                plugins_url('js/bootstrap.min.js', dirname(__FILE__)),
                array('jquery')
            );

            wp_enqueue_style('bs-styles', plugins_url('css/bootstrap.min.css', dirname(__FILE__)));


            wp_enqueue_script(
                'slim-scripts',
                plugins_url('js/slimselect.js', dirname(__FILE__)),
                array('jquery')
            );
            wp_enqueue_style('slim-styles', plugins_url('css/slimselect.css', dirname(__FILE__)));

            wp_enqueue_script(
                'admin-scripts',
                plugins_url('js/admin-scripts.js', dirname(__FILE__)),
                array('jquery')
            );
			wp_enqueue_script(
                'jquery.blockUI',
                plugins_url('js/jquery.blockUI.js', dirname(__FILE__)),
                array('jquery')
            );
			ap_ready('admin-scripts');

            $translation_array = array(

                'this_url' => admin_url('admin.php?page=alphabetic_pagination'),
                'ap_tab' => (isset($_GET['t'])?esc_attr($_GET['t']):''),
				'ap_pro' => $ap_customp?'true':'false',
				'reset_theme' => $ap_reset_theme,
				'ap_disable' => $ap_disable,
				'clear_log_nonce' => wp_create_nonce('ap_clear_log_nonce')

            );

            wp_localize_script('admin-scripts', 'ap_object', $translation_array);
        }
		if(!is_admin() && is_object($post)){
			
			$translation_array = array(
				'ap_pro' => $ap_customp?'true':'false',
				'reset_theme' => $ap_reset_theme,
				'page_id' => $post->ID,
				'ap_lang' => $ap_lang,
				'ap_disable' => $ap_disable

            );
			wp_localize_script('ap-front', 'ap_object', $translation_array);
			wp_enqueue_script(
                'jquery.blockUI',
                plugins_url('js/jquery.blockUI.js', dirname(__FILE__)),
                array('jquery')
            );
		}
		
	
	}
	if(!function_exists('ap_ready')){
		function ap_ready($handle=''){
			
			if(!$handle){ return; }

			$class = '.ap_pagination';
			
			if(is_admin()){
				$class .= '.ap_slanguage';
			}
			
			$ready = '
			jQuery(document).ready(function($) {
			    
			setTimeout(function(){	
			//console.log("'.$class.'");
			if($("'.$class.'").length){
			$("'.$class.'").eq(0).show();
			} }, 1000);
			
			});
			';

			wp_add_inline_script( $handle, $ready, 'after' );
			
		}
	}
		
	function ap_admin_style() {
		
		global $css_arr;
		
		
		wp_enqueue_style('ap-admin', plugins_url('css/admin-style.css', dirname(__FILE__)), array(), time(), 'all');



	}
			
	
	function ap_pro_admin_style() {
		
		global $css_arr;
		

		
		$css_arr[] = '#menu-settings li.current {
					border-left: 4px #25bcf0 solid;
					border-right: 4px #fc5151 solid;
					}
					#menu-settings li.current a{
						margin-left:-4px;
					}';
	}		
	
	function ap_get_queries(){
		global $wpdb;
		
		return $wpdb->queries;
	}
	
	if(!$ap_customp){
	
		if(!function_exists('ap_pagination_custom')){
			function ap_pagination_custom( $atts ) {
				global $ap_datap, $ap_premium_link;
				
				$premium_notice = __('shortcodes are available in', 'alphabetic-pagination').' <a href="'.$ap_premium_link.'" target="_blank">'.__('premium version', 'alphabetic-pagination').'</a>.';
				return '<div class="ap_premium_notice">'.$ap_datap['Name'].' '.$premium_notice.'</div>';
				
			}
		}
		
		if(!function_exists('ap_pagination_results')){
			function ap_pagination_results( $atts ) {
				global $ap_datap, $ap_premium_link;
				$premium_notice = __('shortcodes are available in', 'alphabetic-pagination').' <a href="'.$ap_premium_link.'" target="_blank">'.__('premium version', 'alphabetic-pagination').'</a>.';
				return '<div class="ap_premium_notice">'.$ap_datap['Name'].' '.$premium_notice.'</div>';				
			}
		}		

	}

	if(!function_exists('ap_start_settings_form')){
	    function ap_start_settings_form(){

	        ?>

        <form action="<?php echo esc_attr($_SERVER['REQUEST_URI']); ?>" method="post">
            <?php wp_nonce_field( 'wp_nonce_action', 'wp_nonce_action_field' ); ?>
            <input type="hidden" name="ap_tn" value="<?php echo isset($_GET['t']) ? esc_attr($_GET['t']) : ''; ?>">

            <?php

        }
    }


    if(!function_exists('ap_end_settings_form')){
        function ap_end_settings_form($row_class = ''){

            ?>
                <div class="row <?php echo esc_attr($row_class); ?>">
                    <div class="col-md-2">
                        <div class="row">
                            <input type="submit" value="<?php _e('Save Changes','alphabetic-pagination'); ?>" class="btn btn-lg btn-warning" id="submit" name="ap_submit_settings" />
                        </div>
                    </div>
                </div>

                </form>

            <?php

        }
    }

	
	function ap_set_query_order( $query ) {
		if ( $query->is_category() && $query->is_main_query()) {

			$category = get_queried_object();
			

			$ap_tax_types = ap_get_option('ap_tax_types');
			$ap_tax_types = (is_array($ap_tax_types)?$ap_tax_types:array());			
			$ap_tax_types = array_filter($ap_tax_types, 'ap_gtz');
			
			if(in_array($category->term_id, $ap_tax_types)){
	
				$query->set( 'orderby', 'title' );
				$query->set( 'order', 'ASC' );
				
			}
		}
	}
	add_action( 'pre_get_posts', 'ap_set_query_order' );


	

	
	include_once('logger.php');