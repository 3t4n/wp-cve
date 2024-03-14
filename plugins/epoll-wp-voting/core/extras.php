<?php
// Shortens a number and attaches K, M, B, etc. accordingly
if(!function_exists('it_epoll_number_shorten')){

	function it_epoll_number_shorten($num) {
		if($num>1000) {

				$x = round($num);
				$x_number_format = number_format($x);
				$x_array = explode(',', $x_number_format);
				$x_parts = array('k', 'm', 'b', 't');
				$x_count_parts = count($x_array) - 1;
				$x_display = $x;
				$x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
				$x_display .= $x_parts[$x_count_parts - 1];

				return $x_display;

		}
	return $num;
	}
}



//Adding Columns to epoll cpt
if(!function_exists('set_custom_edit_it_epoll_columns')){
	add_filter( 'manage_it_epoll_poll_posts_columns', 'set_custom_edit_it_epoll_columns' );
	add_filter( 'manage_it_epoll_opinion_posts_columns', 'set_custom_edit_it_epoll_columns' );
	function set_custom_edit_it_epoll_columns($columns) {
		$columns['total_option'] = __( 'Total Options', 'it_epoll' );
		$columns['poll_status'] = __( 'Poll Status', 'it_epoll' );
		$columns['shortcode'] = __( 'Shortcode', 'it_epoll' );
		$columns['view_result'] = __( 'View Result', 'it_epoll' );
		return $columns;
	}
}

if(!function_exists('custom_it_epoll_poll_column')){
	// Add the data to the custom columns for the book post type:
	add_action( 'manage_it_epoll_poll_posts_custom_column' , 'custom_it_epoll_poll_column', 10, 2 );
	add_action( 'manage_it_epoll_opinion_posts_custom_column' , 'custom_it_epoll_poll_column', 10, 2 );
	function custom_it_epoll_poll_column( $column, $post_id ) {
		switch ( $column ) {

			case 'shortcode' :
				if(get_post_type($post_id) == 'it_epoll_opinion'){
					$code = '[IT_EPOLL_POLL id="'.$post_id.'"][/IT_EPOLL_POLL]';
					if ( is_string( $code ) ){?>
						<code><?php echo esc_html($code,'it_epoll');?></code>
					<?php }else{
						echo esc_attr( 'Unable to get shortcode', 'it_epoll' );
					}
						
				}else{
					$code = '[IT_EPOLL_VOTING id="'.$post_id.'"][/IT_EPOLL_VOTING]';
					if ( is_string( $code ) ){?>
						<code><?php echo esc_html($code,'it_epoll');?></code>
					<?php }else{
						echo esc_attr( 'Unable to get shortcode', 'it_epoll' );
					}
				}
				
				break;
			case 'poll_status' :
				$poll_status = get_post_meta(get_the_id(),'it_epoll_poll_status',true);
				if($poll_status == 'live'){?>
					<span class='it_epolladmin_pro_badge'><?php echo esc_attr($poll_status,'it_epoll');?></span>
				<?php }else{?>
					<span class='it_epolladmin_pro_badge it_epolladmin_pro_badge_blue_only'><?php echo esc_attr($poll_status,'it_epoll');?></span>
				<?php }
				break;
			case 'total_option' :
				if(get_post_meta($post_id,'it_epoll_poll_option',true)){
					$total_opt = sizeof(get_post_meta($post_id,'it_epoll_poll_option',true));
				}else{
					$total_opt = 0;
				}
				echo esc_attr($total_opt,'it_epoll');
				break;
			case 'view_result' :?>
				<a href="<?php echo esc_url(admin_url('admin.php?page=epoll_dashboard&tab=reports&id='.$post_id),'it_epoll');?>" class='button button-primary'><?php echo esc_attr('View','it_epoll');?></a>
			<?php	break;
		}
	}
}


//Change Poll Title Placeholder in Editor
if(!function_exists('it_epoll_change_title_text')){

	add_filter( 'enter_title_here', 'it_epoll_change_title_text' );
	function it_epoll_change_title_text( $title ){
		$screen = get_current_screen();
	  
		if  ( 'it_epoll_opinion' == $screen->post_type ) {
			 $title = 'Enter Question / Poll title here...';
		}
		  
		if  ( 'it_epoll_poll' == $screen->post_type ) {
			$title = 'Enter Contest / Poll title here...';
	   }
	  
		return $title;
	}
	  
}


if(!function_exists('get_it_epoll_local_themes_data')){
	function get_it_epoll_local_themes_data(){
		//WP_Filesystem();
		
		$template_dir = IT_EPOLL_DIR_PATH . 'frontend/templates';
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
		global $wp_filesystem;
		$wp_filesystem = new WP_Filesystem_Direct( false );
		$themes = $wp_filesystem->dirlist($template_dir);
	
		$theme_data = array_map('it_epoll_theme_dir_array',$themes);
		$theme_obj = wp_json_encode($theme_data);
		return $theme_obj;
	}
}

if(!function_exists('it_epoll_theme_dir_array')){
	function it_epoll_theme_dir_array($theme_dir){
		$template_dir = IT_EPOLL_DIR_PATH . 'frontend/templates/';
		$template_dir_url = IT_EPOLL_DIR_URL . 'frontend/templates/';
		$theme_name = $theme_dir['name'];
		$theme_dir = $template_dir.$theme_dir['name'].'/';
		$theme_data = array();
		if(is_file($theme_dir.'template.php')){
		
			$theme_data = get_file_data($theme_dir.'template.php',array('Name'=>'Name',
			'Description'=>'Description',
			'Version'=>'Version',
			'Eversion'=>'Eversion',
			'Type'=>'Type',
			'Developer' => 'Developer',
			'Url' => 'Url',
			'Id'=>'Id',
			'Icon'=>'',
			'Required'=>'Required',
			'DownloadUrl'=>''
		));
		}
		$dir_array = array('Dir'=>$theme_name);
		array_push($theme_data,$dir_array);
		
		return  $theme_data;
		
		
	}

}



if(!function_exists('it_epoll_plugin_api_request')){
	function it_epoll_plugin_api_request($url,$cache_key){
	
		$remote = get_transient($cache_key);
		if( false === $remote){
			
			$remote = wp_remote_get($url);
	
		if( 
			is_wp_error( $remote )
			|| 200 !== wp_remote_retrieve_response_code( $remote )
			|| empty( wp_remote_retrieve_body( $remote ) )
		) {
			return false;
		}
		set_transient( $cache_key, $remote, DAY_IN_SECONDS );
	}
	
		
		$remote = json_decode( wp_remote_retrieve_body( $remote ) );
		return $remote;
	
	}
}

if(!function_exists('check_it_epoll_module_update_available')){
	function check_it_epoll_module_update_available($module_type,$module_id,$current_version=1.0){
		
		if($module_type == 'template'){
			$response = it_epoll_plugin_api_request(IT_EPOLL_THEME_STORE_URL.$module_id.'?get_version=true','it_epoll_plugin_theme_update_check_'.$module_id);
		}else{
			$response = it_epoll_plugin_api_request(IT_EPOLL_EXTENSION_STORE_URL.$module_id.'?get_version=true','it_epoll_plugin_addon_update_check_'.$module_id);
		}
		
		if(!$response){
			return false;
		}
		
		if(isset($response->Version)){
			$version = $response->Version;
		}else{
			$version = '1.0';
		}
	
		
		if($version != $current_version){
			return true;
		}else{
			return false;
		}
				
	}
}


if(!function_exists('check_it_epoll_theme_update_left')){
	function check_it_epoll_theme_update_available(){
		
	}
}



if(!function_exists('get_it_epoll_local_addons_data')){
	function get_it_epoll_local_addons_data(){
		
	
		$addon_dir = IT_EPOLL_DIR_PATH . 'backend/addons';
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
		global $wp_filesystem;
		$wp_filesystem = new WP_Filesystem_Direct( false );
		$addons = $wp_filesystem->dirlist($addon_dir);
		
		$addon_data = array_map('it_epoll_addon_dir_array',$addons);
		$addon_obj = wp_json_encode($addon_data);
		return $addon_obj;
	}
}

if(!function_exists('it_epoll_addon_dir_array')){
	function it_epoll_addon_dir_array($addon_dir){
		$addons_dir = IT_EPOLL_DIR_PATH . 'backend/addons/';
		$addon_dir_url = IT_EPOLL_DIR_URL . 'backend/addons/';
		$addon_name = $addon_dir['name'];
		$addon_dir = $addons_dir.$addon_dir['name'].'/';
		
		$addon_data = array();
		if(is_file($addon_dir.'addon.php')){
			$addon_data = get_file_data($addon_dir.'addon.php',array('Name'=>'Name',
			'Description'=>'Description',
			'Version'=>'Version',
			'Eversion'=>'Eversion',
			'Type'=>'Type',
			'Developer' => 'Developer',
			'Url' => 'Url',
			'Id'=>'Id',
			'Icon'=>'',
			'Required'=>'Required',
			'DownloadUrl'=>''
			));
		}
		$dir_array = array('Dir'=>$addon_name);
	
		array_push($addon_data,$dir_array);
	return  $addon_data;
	}

}



if(!function_exists('get_it_epoll_build_theme_data')){
	function get_it_epoll_build_theme_data($data,$data_local){
				
		
	
		$template_dir_url = IT_EPOLL_DIR_URL . 'frontend/templates/';

				$themes = json_decode($data,TRUE);
				$themes_local = json_decode($data_local,TRUE);
				
					if($themes){
						$active_theme = array();
			
						if(get_option('it_epoll_active_theme')){
							$active_theme = get_option('it_epoll_active_theme');
						}
						array_push($active_theme,'default');
					
						foreach($themes as $theme){
							
							$theme_data = $theme;
							$dont_show = false;
							$extension_name = __('Unkown Theme','it_epoll');
							$extension_icon = __('null','it_epoll');
							$extension_description =__('Tell us about your theme here!','it_epoll');
							$extension_supported_version = 1;
							$extension_type = 2;
							$extension_version =__('0.1.0','it_epoll');
							$extension_id = '';
							$extension_developer = __('infotheme inc.','it_epoll');
							$extension_preview_url =  __('https://infotheme.in/epoll/themes/default/','it_epoll');
							$extension_download_url = '';
							$extension_purchase_url = '';
							$extension_dir_path ="null....";
							if(isset($theme['Name'])) $extension_name = $theme['Name'];
							if(isset($theme['Description'])) $extension_description = $theme['Description'];
							if(isset($theme['Eversion'])) $extension_supported_version = $theme['Eversion'];
							if(isset($theme['Type'])) $extension_type = $theme['Type'];
							if(isset($theme['Version'])) $extension_version = $theme['Version'];
							if(isset($theme['Developer'])) $extension_developer = $theme['Developer'];
							if(isset($theme['Url'])) $extension_preview_url = $theme['Url'];
							if(isset($theme['Id'])) $extension_id = $theme['Id'];
							if(isset($theme['Icon'])) $extension_icon = $theme['Icon'];
							if(isset($theme['BuyUrl'])) $extension_purchase_url = $theme['BuyUrl'];
							if(isset($theme['DownloadUrl'])) $extension_download_url = $theme['DownloadUrl'];
							if(isset($theme[0]['Dir'])) $extension_dir_path = $theme[0]['Dir'];
								$update_availble = false;
							
								if(!$extension_id) $dont_show = true;

								$update_availble = false;
									if($themes_local){
										
										if(in_array($extension_id, array_column($themes_local, 'Id'))){
											$dont_show = true;
										}
									}else{
										$update_availble = check_it_epoll_module_update_available('templates',$extension_id,$extension_version);
									}
	
									if(!$extension_icon){
										$extension_icon = $template_dir_url.$extension_id.'/icon.png';
									}
	
								if(!$dont_show){	

							?>
			
						<div class="plugin-card">
								<div class="plugin-card-top">
									<div class="name column-name">
										<h3>
											<a href="<?php echo esc_url($extension_preview_url,'it_epoll');?>" target="blank" class="epoll_addon-link_wrap">
													<?php echo esc_attr($extension_name,'it_epoll');?>
													<img src="<?php echo esc_url($extension_icon,'it_epoll');?>" class="epoll_addon-icon" alt="<?php echo esc_attr($extension_name,'it_epoll');?>">
												</a>
										</h3>
									</div>
									<div class="action-links">
										<ul class="plugin-action-buttons it_epoll_plugin_buttons">
												
												<?php 
												if($extension_type == 403){?>
													<button class="button button-secondary"  id="default" data-id="<?php echo esc_attr($extension_id,'it_epoll');?>" disabled><?php echo esc_attr('Coming Soon','it_epoll');?></button>
												<?php }else{
													if(!$extension_purchase_url && !$extension_download_url){
														if(!in_array($extension_id,$active_theme)){?>
														<li>
															<button class="button button-primary" id="activate" data-action="it_epoll_theme_action_activate" data-nonce="<?php echo esc_attr(wp_create_nonce( 'it_epoll_theme_action_activate_'.$extension_id ),'it_epoll');?>" data-id="<?php echo esc_attr($extension_id,'it_epoll');?>"><?php echo esc_attr('Activate','it_epoll');?></button>
														</li>
														<?php  if($extension_id != 'default'){?>
														<li>
															<button class="button button-danger"  id="delete" data-path="<?php echo esc_attr($extension_dir_path,'it_epoll');?>" data-action="it_epoll_theme_action_uninstall" data-nonce="<?php echo esc_attr(wp_create_nonce( 'it_epoll_theme_action_uninstall_'.$extension_id ),'it_epoll');?>" data-id="<?php echo esc_attr($extension_id,'it_epoll');?>"><?php echo esc_attr('Uninstall','it_epoll');?></button>
														</li>
														<?php } }else{?>
														<li>
														<?php  if($extension_id != 'default'){?>
															<button class="button button-secondary"  id="deactivate" data-action="it_epoll_theme_action_deactivate" data-nonce="<?php echo esc_attr(wp_create_nonce( 'it_epoll_theme_action_deactivate_'.$extension_id ),'it_epoll');?>" data-id="<?php echo esc_attr($extension_id,'it_epoll');?>"><?php echo esc_attr('Deactivate','it_epoll');?></button>
														<?php }else{?>
															<button class="button button-secondary"  id="default" data-id="<?php echo esc_attr($extension_id,'it_epoll');?>" disabled><?php echo esc_attr('Default','it_epoll');?></button>

															<?php }?>
														</li>
														<?php } }elseif($extension_purchase_url){?>
															<li><a href="<?php echo esc_url($extension_purchase_url,'it_epoll');?>" target="_blank" class="button button-primary"  id="default" data-id="<?php echo esc_attr($extension_id,'it_epoll');?>"><?php echo esc_attr('Buy Now','it_epoll');?></a></li>
														<?php }elseif($update_availble){?>
																<li><button class="button button-secondary"  id="install"  data-action="it_epoll_theme_action_install_update" data-nonce="<?php echo esc_attr(wp_create_nonce( 'it_epoll_theme_action_install_update_'.$extension_id ),'it_epoll');?>"  data-id="<?php echo esc_attr($extension_id,'it_epoll');?>" data-url="<?php echo esc_url(it_epoll_myext_getMyDownloadUrl($extension_id),'it_epoll');?>"><?php echo esc_attr('Update','it_epoll');?></button></li>

															<?php }else{
																
																?>
																
																<li><button class="button button-secondary"  id="install"  data-action="it_epoll_theme_action_install_update" data-nonce="<?php echo esc_attr(wp_create_nonce( 'it_epoll_theme_action_install_update_'.$extension_id ),'it_epoll');?>" data-id="<?php echo esc_attr($extension_id,'it_epoll');?>" data-url="<?php echo esc_url(it_epoll_myext_getMyDownloadUrl($extension_id),'it_epoll');?>"><?php echo esc_attr('Install','it_epoll');?></button></li>
															<?php }?>
													
													<?php }?>
													
											</ul>
										</div>
										<div class="desc column-description">
											<p class="authors"> <cite>By <a href="<?php echo esc_url($extension_preview_url,'it_epoll');?>" target="_blank"><?php echo wp_kses($extension_developer,array('a','b','i','strong'=>array('style'=>'color'),'del'=>array('style'=>'color')));?></a></cite></p>
									
											<p><?php echo esc_attr($extension_description,'it_epoll');?></p>
										</div>
									</div>
									<div class="plugin-card-notice">
									<?php if($update_availble){?>
										<div class="update-message notice inline notice-warning notice-alt"><p><?php echo esc_attr('New Update Available!','it_epoll');?></p></div>
										<?php }?>
									</div>
									<div class="plugin-card-bottom">
											<div class="column-updated">
											<strong><?php esc_attr_e('Version:','it_epoll');?></strong> <?php echo esc_attr($extension_version,'it_epoll');?>			
											</div>
											<div class="column-downloaded">
												<?php if($extension_type == 1){?>
													<strong><?php esc_attr_e('Type:','it_epoll');?></strong> <?php esc_attr_e('Funtional & Style','it_epoll');?>
												<?php }else{?>
													<strong><?php esc_attr_e('Type:','it_epoll');?></strong> <?php esc_attr_e('Style Only','it_epoll');?>
												<?php }?>
												<br><strong><?php esc_attr_e('ePoll Compatibility:','it_epoll');?></strong> <?php echo esc_attr($extension_supported_version,'it_epoll');?>
											</div>
									</div>
								</div>
						<?php
									}
							}
						}else{
							echo esc_attr('Please Install A Theme At Least to work this plugin','it_epoll');
						}
		}
}


if(!function_exists('it_epoll_myext_getMyDownloadUrl')){
	function it_epoll_myext_getMyDownloadUrl($extension_id){
		$response = it_epoll_plugin_api_request(IT_EPOLL_DOWNLOAD_URL."?name=".$extension_id.'&site_url='.site_url(),'it_epoll_plugin_get_download_url_checkerV2_'.$extension_id);
		
		if(!$response) return "";
		
		if(isset($response->url)){

			return $response->url;
		}else{
			return "";
		}	
	}
}

if(!function_exists('it_epoll_MyDomainCheck')){
	function it_epoll_MyDomainCheck($url) {
		$pieces = wp_parse_url($url);
		$domain = isset($pieces['host']) ? $pieces['host'] : $pieces['path'];
		if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
		  if($regs['domain'] === 'infotheme.net'){
			return true;
		   }
		}
		return false;
	}
}


if(!function_exists('get_it_epoll_build_addon_data')){
	function get_it_epoll_build_addon_data($data,$data_local){
				
		if(!current_user_can('manage_options')) exit(wp_json_encode(array('sts'=>404,'msg'=>'You don\'t have permission to do this!')));
             
	
		$addon_dir_url = IT_EPOLL_DIR_URL . 'backend/addons/';

				$addons = json_decode($data,TRUE);
				$addons_local = json_decode($data_local,TRUE);
					if($addons){
						
						$active_addon = array();
						
						if(get_option('it_epoll_active_addon')){
							$active_addon = get_option('it_epoll_active_addon');
						}
						array_push($active_addon,'default');
					
						foreach($addons as $addon){
						
							$addon_data = $addon;
			
							$extension_name = __('Unkown Addon','it_epoll');
							$extension_icon = __('null','it_epoll');
							$extension_description =__('Tell us about your addon here!','it_epoll');
							$extension_supported_version = 1;
							$extension_type = 2;
							$extension_version =__('0.1.0','it_epoll');
							$extension_id = '';
							$extension_required = __('default','it_epoll');
							$extension_developer = __('infotheme inc.','it_epoll');
							$extension_preview_url =  __('https://infotheme.in/epoll/addon/default/','it_epoll');
							$extension_download_url = '';
							$extension_purchase_url = '';
							$extension_dir_path ="null....";
							$update_available = false;
							$dont_show = false;
							if(isset($addon['Name'])) $extension_name = $addon['Name'];
							if(isset($addon['Description'])) $extension_description = $addon['Description'];
							if(isset($addon['Eversion'])) $extension_supported_version = $addon['Eversion'];
							if(isset($addon['Type'])) $extension_type = $addon['Type'];
							if(isset($addon['Version'])) $extension_version = $addon['Version'];
							if(isset($addon['Developer'])) $extension_developer = $addon['Developer'];
							if(isset($addon['Url'])) $extension_preview_url = $addon['Url'];
							if(isset($addon['Id'])) $extension_id = $addon['Id'];
							if(isset($addon['Icon'])) $extension_icon = $addon['Icon'];
							if(isset($addon['Required'])) $extension_required = $addon['Required'];
							
							if(isset($addon['BuyUrl'])) $extension_purchase_url = $addon['BuyUrl'];
							if(isset($addon['DownloadUrl'])) $extension_download_url = $addon['DownloadUrl'];
							
							if(isset($addon[0]['Dir'])) $extension_dir_path = $addon[0]['Dir'];
							
							if(!$extension_id) $dont_show = true;
							$update_availble = false;
								if($addons_local){
									if(array_search($extension_id, array_column($addons_local, 'Id'))){
										$dont_show = true;
									}
								}else{
									$update_availble = check_it_epoll_module_update_available('addons',$extension_id,$extension_version);
								}

								if(!$extension_icon){
									$extension_icon = $addon_dir_url.$extension_id.'/icon.png';
								}

							if(!$dont_show){	
							?>
			
						<div class="plugin-card">
						<?php if(!in_array($extension_required,$active_addon)){?>
								<div class="plugin-card-notice">
									<div class="error-message notice inline notice-error error-alt"><p><?php echo esc_attr($extension_required.' Addon Required','it_epoll');?></p></div>
								</div>
								<?php }?>
								<div class="plugin-card-top">
									
									<div class="name column-name">
										<h3>
											<a href="<?php echo esc_url($extension_preview_url,'it_epoll');?>" target="blank" class="epoll_addon-link_wrap">
													<?php echo esc_attr($extension_name,'it_epoll');?>
													<img src="<?php echo esc_url($extension_icon,'it_epoll');?>" class="epoll_addon-icon" alt="<?php echo esc_attr($extension_name,'it_epoll');?>">
												</a>
										</h3>
									</div>
									<div class="action-links">
										<ul class="plugin-action-buttons it_epoll_plugin_buttons">
												
												<?php 
												
												if(!$extension_purchase_url && !$extension_download_url){
													
												if(!in_array($extension_id,$active_addon) && in_array($extension_required,$active_addon)){?>
													<li>
														<button class="button button-primary" id="activate" data-action="it_epoll_addon_action_activate"  data-nonce="<?php echo esc_attr(wp_create_nonce( 'it_epoll_addon_action_activate_'.$extension_id ),'it_epoll');?>" data-id="<?php echo esc_attr($extension_id,'it_epoll');?>"><?php echo esc_attr('Activate','it_epoll');?></button>
													</li>
													<?php  if($extension_id != 'default'){?>
													<li>
														<button class="button button-danger"  id="delete" data-path="<?php echo esc_attr($extension_dir_path,'it_epoll');?>" data-action="it_epoll_addon_action_uninstall" data-nonce="<?php echo esc_attr(wp_create_nonce( 'it_epoll_addon_action_uninstall_'.$extension_id ),'it_epoll');?>" data-id="<?php echo esc_attr($extension_id,'it_epoll');?>"><?php echo esc_attr('Uninstall','it_epoll');?></button>
													</li>
													<?php } }else{?>
													<li>
													<?php  if($extension_id != 'default' && in_array($extension_required,$active_addon)){?>
														<button class="button button-secondary"  id="deactivate" data-action="it_epoll_addon_action_deactivate" data-nonce="<?php echo esc_attr(wp_create_nonce( 'it_epoll_addon_action_deactivate_'.$extension_id ),'it_epoll');?>" data-id="<?php echo esc_attr($extension_id,'it_epoll');?>"><?php echo esc_attr('Deactivate','it_epoll');?></button>
													<?php }else{?>
														<button class="button button-secondary"  id="default" data-id="<?php echo esc_attr($extension_id,'it_epoll');?>" disabled><?php echo esc_attr('Default','it_epoll');?></button>
														<?php }?>
													</li>
													
													
												<?php } }elseif($extension_purchase_url){?>
													<li><a href="<?php echo esc_url($extension_purchase_url,'it_epoll');?>" target="_blank" class="button button-primary"  id="default" data-id="<?php echo esc_attr($extension_id,'it_epoll');?>"><?php echo esc_attr('Buy Now','it_epoll');?></a></li>
												<?php }elseif($update_availble){?>
														<li><button class="button button-secondary"  id="install"  data-action="it_epoll_addon_action_install_update" data-nonce="<?php echo esc_attr(wp_create_nonce( 'it_epoll_addon_action_install_update_'.$extension_id ),'it_epoll');?>"  data-id="<?php echo esc_attr($extension_id,'it_epoll');?>" data-url="<?php echo esc_url(it_epoll_myext_getMyDownloadUrl($extension_id),'it_epoll');?>"><?php echo esc_attr('Update','it_epoll');?></button></li>
												
													<?php }else{?>
														<li><button class="button button-secondary"  id="install"  data-action="it_epoll_addon_action_install_update" data-nonce="<?php echo esc_attr(wp_create_nonce( 'it_epoll_addon_action_install_update_'.$extension_id ),'it_epoll');?>"  data-id="<?php echo esc_attr($extension_id,'it_epoll');?>" data-url="<?php echo esc_url(it_epoll_myext_getMyDownloadUrl($extension_id),'it_epoll');?>"><?php echo esc_attr('Install','it_epoll');?></button></li>
													<?php }
													
													
													?>
										</ul>
									</div>
									<div class="desc column-description">
										<p class="authors"> <cite>By <a href="<?php echo esc_url($extension_preview_url,'it_epoll');?>" target="_blank"><?php echo wp_kses($extension_developer,array('a','b','i','strong'=>array('style'=>'color'),'del'=>array('style'=>'color')));?></a></cite></p>
								
										<p><?php echo esc_attr($extension_description,'it_epoll');?></p>
									</div>
								</div>
								<div class="plugin-card-notice">
								<?php if($update_availble){?>
									<div class="update-message notice inline notice-warning notice-alt"><p><?php echo esc_attr('New Update Available!','it_epoll');?></p></div>
									<?php }?>
								</div>
								<div class="plugin-card-bottom">
										<div class="column-updated">
											<strong><?php esc_attr_e('Version:','it_epoll');?></strong> <?php echo esc_attr($extension_version,'it_epoll');?>				
										</div>
										<div class="column-downloaded">
											
											<strong><?php esc_attr_e('ePoll Compatibility:','it_epoll');?></strong> <?php echo esc_attr($extension_supported_version,'it_epoll');?>
											
										</div>
								</div>
							</div>
					<?php
							}
						}
					}else{
						echo esc_attr('Please Install A addon At Least to work this plugin','it_epoll');
					}
	}
}


if(!function_exists('get_it_epoll_local_themes')){

	function get_it_epoll_local_themes(){
		
		$themes = get_it_epoll_local_themes_data();
	
		get_it_epoll_build_theme_data($themes,'');
	}
}


if(!function_exists('get_it_epoll_local_addons')){

	function get_it_epoll_local_addons(){
		
		$addons = get_it_epoll_local_addons_data();
		
		get_it_epoll_build_addon_data($addons,'');
	}
}



if(!function_exists('get_it_epoll_store_themes')){

	function get_it_epoll_store_themes(){
		$template_dir = IT_EPOLL_DIR_PATH . 'frontend/templates';
		$response = it_epoll_plugin_api_request(IT_EPOLL_THEME_STORE_URL,'it_epoll_plugin_store_themes_');
		
			if($response){
					$themes = get_it_epoll_local_themes_data();
					get_it_epoll_build_theme_data(wp_json_encode($response),$themes);
			}else{?>
			<div>
				<h3><?php echo esc_attr('Unable to load from store, Please check your internet connection or contact us at support@infotheme.net','it_epoll');?></h3>
				<a href="#" onClick="window.location.reload();" class="button"><?php echo esc_attr('Retry','it_epoll');?></a>
			</div>
			<?php }
	
	}
}



if(!function_exists('get_it_epoll_store_addons')){

	function get_it_epoll_store_addons(){
		
		
		$response = it_epoll_plugin_api_request(IT_EPOLL_EXTENSION_STORE_URL,'it_epoll_plugin_store_addons_');
		
			if($response){
					$addons = get_it_epoll_local_addons_data();
					get_it_epoll_build_addon_data(wp_json_encode($response),$addons);
			}else{?>
			<div>
				<h3><?php echo esc_attr('Unable to load from store, Please check your internet connection or contact us at support@infotheme.net','it_epoll');?></h3>
				<a href="#" onClick="window.location.reload();" class="button"><?php echo esc_attr('Retry','it_epoll');?></a>
			</div>
			<?php }
		

		
	}
}


if(!function_exists('get_it_epoll_store_docs')){

	function get_it_epoll_store_docs($tab='general'){
		if($tab == 'forum'){
			$response = it_epoll_plugin_api_request(IT_EPOLL_DOC_STORE_URL.'?type='.$tab,'it_epoll_plugin_store_forum_');
		}else{
			$response = it_epoll_plugin_api_request(IT_EPOLL_DOC_STORE_URL.'?type='.$tab,'it_epoll_plugin_store_docs_');
		}
			if($response){
					if($tab == 'forum'){
						array_map('build_it_epoll_faq_layout',($response));
					}else{
						array_map('build_it_epoll_doc_layout',($response));
					}
				
			}else{?>
			<div>
				<h3><?php echo esc_attr('Unable to load from store, Please check your internet connection or contact us at support@infotheme.net','it_epoll');?></h3>
				<a href="#" onClick="window.location.reload();" class="button"><?php echo esc_attr('Retry','it_epoll');?></a>
			</div>
			<?php }
	}
}




if(!function_exists('build_it_epoll_doc_layout')){
	function build_it_epoll_doc_layout($data){?>
	<a href="<?php echo esc_url($data->link,'it_epoll');?>" class="it_epoll_admin_box_item_link">
		<div class="it_epoll_admin_box_item_content">
			<h4><?php echo esc_attr($data->title,'it_epoll');?></h4>
			<p class="it_epoll_admin_box_item_content_description"><?php echo esc_attr($data->desc,'it_epoll');?></p>
			<span class="it_epoll_admin_item_content_link"><i class="dashicons dashicons-external"></i><?php echo esc_attr(' Read More','it_epoll');?></span>
		</div>
		<img src="<?php echo esc_url($data->thumbnail,'it_epoll');?>" alt="" width="92" height="92"/>
	</a>
	<?php			
	}
}

if(!function_exists('build_it_epoll_faq_layout')){
	function build_it_epoll_faq_layout($data){?>	
		<a href="<?php echo esc_url($data->link,'it_epoll');?>" class="it_epoll_admin_box_item_link it_epoll_admin_box_item_link_partial">
			<div class="it_epoll_admin_box_item_content">
				<h4><?php echo esc_attr($data->title,'it_epoll');?></h4>
				<p class="it_epoll_admin_box_item_content_description"><?php echo esc_attr($data->desc,'it_epoll');?></p>
				<span class="it_epoll_admin_item_content_link"><i class="dashicons dashicons-external"></i><?php echo esc_attr(' Read More','it_epoll');?></span>
			</div>
		</a>
		<?php
	}
}

if(!function_exists('it_epoll_install_from_store_zip')){
	function it_epoll_install_from_store_zip($url,$upload_dir,$action_upload,$type="template"){
		
		if(!current_user_can('manage_options')) exit(wp_json_encode(array('sts'=>404,'msg'=>'You don\'t have permission to do this!')));
          
	
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}

		global $wp_filesystem;
		if ( ! $filesystem ) {
			WP_Filesystem();
			  if ( ! $wp_filesystem )
				nuke_the_world_because_wpfs_cannot_be_initialized_in_case_of_missing_arguments('!');
		  }
		$packaging_error = "";
		$packagin_success = "";

		$package_type = "Template ";
		if($type == 'addon'){
			$package_type = "AddOn ";
		}

		$modulesPathDir ="";
		if ( filter_var( $url, FILTER_VALIDATE_URL,FILTER_SANITIZE_URL ) ){
			$downloaded = download_url( $url );
			if ( is_wp_error($downloaded)){
				$error_string = $downloaded->get_error_message();
				$packaging_error = "Unable to download this $package_type. :- ".$error_string;
			}else{
				$modulesPathDir = $downloaded;
			}
		}else{
			$packaging_error = "Invalid $package_type Url. Please try again.";
		}
		$filepath = ABSPATH . 'wp-content/uploads/installable.zip';
		if($modulesPathDir){
			
			$tempDir = get_temp_dir() . uniqid( time(), false );
			// Try to unzip the module zip file
			copy( $modulesPathDir, $filepath );
			wp_delete_file( $modulesPathDir );
			$result = unzip_file( $filepath, $tempDir );

			if ( !is_wp_error($result)){
				$dirs = glob( "{$tempDir}/*", GLOB_ONLYDIR );
					
				if ( empty( $dirs ) ){
					// Nothing to install
					$packaging_error =  'Archive is empty or does not contain a directory.';
					
				}else{
					$moduleDir = $dirs[0];
					
					if ( ! file_exists($moduleDir.'/'.$type.'.php') ){
						$packaging_error =  "Invalid. $package_type Zip File";
						
					}else{
						$theme_data = get_file_data($moduleDir.'/'.$type.'.php',array('Id'=>'Id'));
						if(isset($theme_data['Id'])){
							// Get module directory name.
							$moduleDirName = basename( $moduleDir );

							// Fallback to plugin's directory
							$modulesPath = IT_EPOLL_DIR_PATH.$upload_dir.$moduleDirName;
							if ( ! is_dir( $modulesPath ) && ! wp_mkdir_p( $modulesPath ) ) :
								$modulesPath = IT_EPOLL_DIR_PATH.$upload_dir.$moduleDirName;
							endif;

							// Copy template's files
							$result = copy_dir( $moduleDir, $modulesPath );
							$packagin_success = "$package_type has been Installed, Try to activate and see it in action";
						
						}else{
							$packaging_error =  "Invalid. $package_type Zip File";
						}
					}
						
				}
				
			}else{
				$error = $result->get_error_message();
				$packaging_error =  "Error: $error";
			}
			if(file_exists($tempDir)) $wp_filesystem->delete($tempDir,true);
			if(file_exists($filepath))  $wp_filesystem->delete($filepath,true);
	
		}

	
		if($packagin_success){
			return wp_json_encode(array('sts'=>200,'msg'=>$packagin_success));
		}else{
			return wp_json_encode(array('sts'=>404,'msg'=>$packaging_error));
		}

	}
	
}


if(!function_exists('it_epoll_install_from_local_zip')){

	function it_epoll_install_from_local_zip($upload_dir,$action_upload,$type="template"){
		
		global $wp_filesystem;
		if(!current_user_can('manage_options')) exit(wp_json_encode(array('sts'=>404,'msg'=>'You don\'t have permission to do this!')));
          
		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		}
		$package_type = "Template ";
		if($type =='addon'){
			$package_type = "AddOn ";
		}
		
		?>
			<h3><?php echo esc_attr("$package_type Installation Process....",'it_epoll');?></h3>
		<?php
		$packaging_error = "";
		$movefile =  wp_handle_upload( $_FILES['zip_file'], array('action' => $action_upload,'test_form'=>false));
		if ( $movefile && ! isset( $movefile['error'] ) ){
			?>
			<p><?php echo esc_attr("$package_type File was successfully uploaded","it_epoll");?></p>
			<?php
			$modulesPathDir = $movefile['file'];

			$tempDir = get_temp_dir() . uniqid( time(), false );
			// Try to unzip the module zip file
			$result = unzip_file( $modulesPathDir, $tempDir );

			if ( $result === true ){
				$dirs = glob( "{$tempDir}/*", GLOB_ONLYDIR );

				if ( empty( $dirs ) ){
					// Nothing to install
					$packaging_error =  '<p class="error">Archive is empty or does not contain a directory.</p>';
					
				}else{
					$moduleDir = $dirs[0];
					
					if ( ! file_exists($moduleDir.'/'.$type.'.php') ){
						$packaging_error =  "<p class='error'>Invalid. $package_type Zip File</p>";
						
					}else{
						$theme_data = get_file_data($moduleDir.'/'.$type.'.php',array('Id'=>'Id'));
						if(isset($theme_data['Id'])){
							// Get module directory name.
							$moduleDirName = basename( $moduleDir );

							// Fallback to plugin's directory
							$modulesPath = IT_EPOLL_DIR_PATH.$upload_dir.$moduleDirName;
							if ( ! is_dir( $modulesPath ) && ! wp_mkdir_p( $modulesPath ) ) :
								$modulesPath = IT_EPOLL_DIR_PATH.$upload_dir.$moduleDirName;
							endif;

							// Copy template's files
							$result = copy_dir( $moduleDir, $modulesPath );
							?>
							<div class='updated notice is-dismissible'><p><?php echo esc_attr("$package_type has been Installed, Try to activate and see it in action","it_epoll");?></p></div>
						<?php
						}else{
						
							$packaging_error =  "<p class='error'>Invalid. $package_type Zip File</p>";
						}
					}
						
				}
				
			}else{
				$packaging_error =  '<p class="error">Invalid. Zip File</p>';
			}

		} else {
			$packaging_error =  '<p class="error">'.$movefile['error'].'</p>';
		}

		if(file_exists($tempDir)) $wp_filesystem->delete($tempDir,true);
		if(file_exists($modulesPathDir))  $wp_filesystem->delete($modulesPathDir,true);
		
		if($packaging_error){?>
			<div class="error notice is-dismissible"><?php echo esc_attr($packaging_error,'it_epoll');?></div>
		<?php }
	}

}



if(!function_exists('it_epoll_module_admin_script_enque')){

	function it_epoll_module_admin_script_enque(){

	}
}


if(!function_exists('it_epoll_module_admin_css_enque')){

	function it_epoll_module_admin_css_enque(){
		
	}
}

if(!function_exists('it_epoll_module_css_enque')){

	function it_epoll_module_css_enque(){
		
	}
}

if(!function_exists('it_epoll_module_script_enque')){

	function it_epoll_module_script_enque(){
		
	}
}

if(!function_exists('it_epoll_module_editor_script_enque')){

	function it_epoll_module_editor_script_enque(){
		
	}
}


if(!function_exists('it_epoll_settings_plugin_link')){

	add_filter( 'plugin_action_links', 'it_epoll_settings_plugin_link', 10, 2 );

	function it_epoll_settings_plugin_link( $links, $file ) 
	{
		if ( $file == plugin_basename(IT_EPOLL_DIR_PATH . '/it-epoll.php') ) 
		{
			
			/*
			 * Insert the link at the beginning
			 */
			$in = '<a href="admin.php?page=epoll_options">' . __('Settings','it_epoll') . '</a>';
			array_unshift($links, $in);
	
			/*
			 * Insert at the end
			 */
			 $links[] = '<a target="_blank" style="font-weight: bold; color: #FF5722;" href="'.esc_url('https://infotheme.net/item/wordpress/plugin/poll-maker-and-voting-plugin/','it_epoll').'">'.__('Get ePoll Pro','it_epoll').'</a>';
		}
		return $links;
	}
}
//Security Check for Admin Ajax Request in Addon and Theme Section for ePoll
if(!function_exists('it_epoll_admin_ajax_capabilities_check')){
	function it_epoll_admin_ajax_capabilities_check(){
		if(!current_user_can('manage_options'))   exit(wp_json_encode(array('sts'=>404,'data'=>array('name'=>$name,'id'=>$id),'msg'=>'You don\'t have permission to do this!')));
	}
}