<?php
# TO ECHO, USE echoctmip() in notes.txt
function tmip_load_css(){
	global $tmip_plugin_dir_url,$tmip_load_css_loaded;
	if (!$tmip_load_css_loaded) {
		$tmip_load_css_loaded=true;
		$css_file='css/common.css';
		if ($v=(tmip_plugin_path.$css_file) and file_exists($v)) $v='?p='.filemtime($v); else $v='';
		$css_file_url=$tmip_plugin_dir_url.$css_file.$v;
		wp_enqueue_style(
			'tmip_cmstyle',
			$css_file_url,
			false,
			TMIP_VERSION,
			'all'
		);
	}
}
function tmip_load_js(){
	global $tmip_plugin_dir_url;
	wp_enqueue_script(
		'tmip_cmjavasc',
		$tmip_plugin_dir_url.'js/common.js',
		array(), //array( 'jquery', 'jquery-ui-dialog' ), // Add jquery
		TMIP_VERSION,
		true
	);
}
function tmip_plugins_dirpath($file) {
	global 	$pluginRootFilepath,$tmip_plugin_dir_url,$tmip_plugin_root_dir,
			$tmip_plugin_dir_name,$tmip_plugin_basename,$tmip_plugin_pathbase,$tmip_plugin_admin_url,
			$tmip_plugin_sett_url,$tmip_rate_wp_url,$tmip_rate_pl_url;
	
	$pluginRootFilepath=	$file;
	$tmip_plugin_dir_url=	plugin_dir_url($file);
	$tmip_plugin_root_dir=	plugin_dir_path($file);
	$tmip_plugin_dir_name=	tmip_plugin_dir_name;
	$tmip_plugin_basename=	basename($file);
	$tmip_plugin_pathbase=	$tmip_plugin_dir_name.'/'.$tmip_plugin_basename;
	$tmip_plugin_admin_url=	get_admin_url();
	
	// Functions URLS
	$tmip_plugin_sett_url=	$tmip_plugin_admin_url.'admin.php?page=tmip_lnk_wp_settings';
	$tmip_rate_wp_url=		'//wordpress.org/support/plugin/'.$tmip_plugin_dir_name.'/reviews/?rate=';
	$tmip_rate_pl_url=		$tmip_plugin_admin_url.'admin.php?page=tmip_lnk_wp_settings&showdivtarget=sec_rate&rate=';
}
function tmip_static_urls() {
	if (isset($_SERVER['HTTPS']) and $_SERVER['HTTPS']) {
		$tmip_prot='https';
		$tmip_rFsrc='wordpressDBS';
	} else {
		$tmip_prot='http';
		$tmip_rFsrc='wordpressDB';
	}
	
	$wp_user_info_ae=base64_encode(get_bloginfo('admin_email',''));
	$wp_wp_version=urlencode(get_bloginfo('version',''));
	$wp_pl_version=urlencode(TMIP_VERSION);
	
	// 'false' to set constants case sensitive
	define("tmip_home_page_url", 			
		   $tmip_prot.'://www.'.tmip_domain_name.'');
	define("tmip_go_to_projects", 		
		   $tmip_prot.'://www.'.tmip_domain_name.'/members/index.php?rnDs=197201798&page=home&wMx=1&rFsrc='.$tmip_rFsrc);
	define("tmip_go_to_upgrade", 		
		   tmip_acc_upgr_url);
	define("tmip_free_signup_page_url", 
		   tmip_service_url.'/tools/website-visitors-counter-traffic-tracker-statistics/index.php?sto=1');
	define("tmip_premium_signup_page_url", 
			tmip_service_url.'/members/index.php?page=spm_checkout&type=ssub&ntc=1');
	define("tmip_wp_learn_kb", 
			tmip_service_url.'/learn/');
	
	// Learn KB linked keywords
	define("tmip_kb_home_link",
		   '<a href="'.tmip_wp_learn_kb.'" target="_blank"><b>Knowledge Base</b></a>');
	define("tmip_kb_rock_ldr",
		   '<a href="'.tmip_wp_learn_kb.'disable-rocket-loader-to-enable-visitor-ip-tracking-936/" target="_blank"><b>Rocket Loader</b></a>');
	
	// Learn KB urls 071323070147
	define("tmip_wp_vistr_setup_tutorial", 
			tmip_wp_learn_kb.'wordpress-visitor-tracking-plugin-1382/');
	define("tmip_wp_block_ips_visit_control",
			tmip_wp_learn_kb.'how-to-block-an-ip-address-1017/');
	define("tmip_wp_other_useful_tutorials",
			tmip_wp_learn_kb.'most-popular-tracemyip-features-1594/');
	define("tmip_wp_website_cache_optimization",
			tmip_wp_learn_kb.'website-caching-cdn-and-website-analytics-and-visitor-tracking-issues-2422/');
	define("tmip_chart_tracker_url",     'https://tools.tracemyip.org/ipbox/md=1&pID=1-324473478-1-1600~1587599353~300*100*1*1*20~8CFFD1*33313B~0*0*0&uID=3-042220&pgB=013201&ttC=FF0000&trT=FFFFFF&erA=AED99E&orA=DEEFD7&nrC=666666&vrC=006666&sOP=icstzdlrpob&ipbCap=1&plVer='.$wp_pl_version.'&wpVer='.$wp_wp_version.'&wpAem='.$wp_user_info_ae);
	
	define("tmip_lrn_invtrk_lnk", tmip_link(tmip_lrn_invtrk_url,'<b>'.tmip_invisible_tracker.'</b>'));

	
	// wpPv: WP plugin version wpIf: wordpress iframe token to disable break out of frames
	define("tmip_ip_tools_index",     		'https://tools.tracemyip.org/home/'.UVAR.'wpPv='.$wp_pl_version.'&wpIf=1');
	
	define("tmip_visit_tracker_val", 		'tmip_visit_tracker', 			false);
	define("tmip_visit_tracker_default", 	'', 							false);
	
	define("tmip_position_val", 			'tmip_visit_tracker_position', 	false);
	define("tmip_position_default", 		'footer', 						false);
	
	define("tmip_page_tracker_val", 		'tmip_page_tracker', 			false);
	define("tmip_page_tracker_default", 	'', 							false);
}

function tmip_get_url_vars() {
	global $show_div_target;
	$show_div_target=array();
	if (isset($_GET['showdivtarget']) and $v=sanitize_text_field($_GET['showdivtarget'])) {
		$show_div_target=explode(',',$v);
	}
	global $hide_div_target;
	$hide_div_target=array();
	if (isset($_GET['hidedivtarget']) and $v=sanitize_text_field($_GET['hidedivtarget'])) {
		$hide_div_target=explode(',',$v);
		echo $hide_div_target;
	}
}
function tmip_wp_settings() {
	tmip_settings_page();
}
function tmip_load_fontawesome_cloufare() {
    wp_enqueue_style( 'style', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css');
}
function tmip_rate_serv() {
	tmip_load_fontawesome_cloufare();
	$output .='<div id="tmip_sett_wrap_1">';
	$output .=tmip_rate_review_section();
	$output .='</div>';
	echo $output;
}
function tmip_access_reports(){
	global $tmip_plugin_dir_url,$tmip_plugin_sett_url;
	
	$menuID='tmip_admpanel_menu';
	$position=25; 
	$icon_url=$tmip_plugin_dir_url.'images/tmip_icon_admin_menu.png';
	$vis_tr_stats=tmip_log_stat_data(array('type'=>'vis_tr_stats'));
	
	// Shared MATCH CONDITION in TMIP third-party 041023084633
	$curUserAgent=trim($_SERVER['HTTP_USER_AGENT']);
	$mac_limit_drop=0;
	if ($ua=$curUserAgent) {
		if (stristr($ua,'Macintosh;') and stristr($ua,'Safari/')) {
			$mac_limit_drop=1;
		}
	}
	
	// Main admin menu
	add_menu_page(
		'', 						// menu browser title
		tmip_service_Nname, 		// menu link name
		'manage_options',   		// capability
		$menuID, 					// main menu link
		'',							// call function - called by submenu item
		$icon_url,					// Icon class 'dashicons-list-view' or URL
		$position					// Menu position
	);  
	add_submenu_page(				// Replace top menu name with another
		$menuID,					// Parent menu tree slug
		tmip_service_Nname.' > '.ucwords(tmip_submenu_reports),
		ucwords(tmip_submenu_reports),	// submenu link name
		'manage_options',			// capability
		$menuID,					// main menu link - make same as primary menu name to create and replace submenu
		'tmip_reports_page'			// callback function
	);  
	add_submenu_page(
		$menuID,
		tmip_service_Nname.' > '.ucwords(tmip_submenu_settings),
		ucwords(tmip_submenu_settings),
		'manage_options',
		'tmip_lnk_wp_settings',
		'tmip_wp_settings'
	);  
	if ($mac_limit_drop<>1) {
		add_submenu_page(
			$menuID,
			tmip_service_Nname.' > '.ucwords(tmip_submenu_unlock_frt),
			ucwords(tmip_submenu_unlock_frt),
			'manage_options',
			'tmip_upgrade_page',
			'tmip_upgrade_page'
		);
	}
	add_submenu_page(
		$menuID,
		tmip_service_Nname.' > '.ucwords(tmip_submenu_my_ipv46_adr),
		ucwords(tmip_submenu_my_ipv46_adr),
		'manage_options',
		'tmip_lnk_myipv_46_adr',
		'tmip_myipv_46_adr'
	);   
	if (1==2) { // Not used.
		add_submenu_page(
			$menuID,
			tmip_service_Nname.' > '.ucwords(tmip_submenu_ip_tools),
			ucwords('IP Tools'),	
			'manage_options',
			'tmip_lnk_ip_tools_idx',
			'tmip_ip_tools_idx'
		);
	}
	if ($vis_tr_stats['vis_tr_queries_cnt']>=tmip_codes_usage_rate_thresh) {
		if (tmip_enable_meta_rating) {
			add_submenu_page(
				$menuID,				// Parent menu tree slug
				ucwords(tmip_submenu_rate_service),	// page title
				ucwords(tmip_submenu_rate_service),
				'manage_options',		// capability
				'tmip_rate_serv_lnk',
				'tmip_rate_serv'		// callback function
			); 
		}
	}
}
function tmip_reports_page() {
	tmip_wp_iframe_page(tmip_go_to_projects);
}
function tmip_upgrade_page() {
	tmip_wp_iframe_page(tmip_go_to_upgrade);
}
function tmip_myipv_46_adr() {
	tmip_wp_iframe_page(tmip_chart_tracker_url);
}
function tmip_ip_tools_idx() {
	tmip_wp_iframe_page(tmip_ip_tools_index);
}
function tmip_wp_iframe_page($url) {
	global $tmip_plugin_dir_url;
	tmip_load_css();
	
	// ajLoader_05.gif, ajLoader_06.gif
	echo '<style>
		body::-webkit-scrollbar {
			width: 1em;
		}
		body::-webkit-scrollbar-thumb {
			background-color: black;
		}	
		.tmip_iframe_container {
			position: absolute; top: 0; left: 0;
			z-index: 100000;
			height:95vh; width:99%;
			border:none;
			background-color:#333;	
			background-image: url(\''.$tmip_plugin_dir_url.'images/ajLoader_06.gif\');
			background-repeat: no-repeat;
  			background-attachment: fixed;
  			background-position: center 40%;
		}	
	</style>';
	
/*	
		// for <iframe style="height: 100%;">
		#wpwrap { height: 100%; } #wpcontent: { height: 100%; } #wpbody { height: 100%; } #wpbody-content { height: 100%; } 
		.tmip_iframe_loading_console {
			height:120px;
			text-align:center;
			position: fixed;
			width: 100%;
			height: 100%;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background-color: rgba(0,0,0,0.5);
			z-index: 2;
			cursor: pointer;
		}
		<div id="tmip_loadconmsg" class="tmip_iframe_loading_console">
        	<div>loading console...</div>
    	</div>
		<iframe src="'.$tmip_link.'" class="tmip_iframe_container" onload="document.getElementById(\'tmip_loadconmsg\').style.display=\'none\';"></iframe>
*/
	echo '
		<iframe src="'.$url.'" class="tmip_iframe_container"><p>'.tmip_iframe_javascript_n.'</p></iframe>
	';
}
function tmip_plugin_row_add_rating($links,$file) {
	global 	$tmip_plugin_dir_name,$tmip_plugin_pathbase,$tmip_plugin_sett_url,$tmip_plugin_dir_name,
			$tmip_rate_wp_url,$tmip_rate_pl_url,$WP_admin_pages;
	
	if ($WP_admin_pages) tmip_load_js();
	
	if ($tmip_plugin_pathbase!==$file) return $links;
	$rating=array();
	$rating[1]='poor';
	$rating[2]='works';
	$rating[3]='good';
	$rating[4]='great';
	$rating[5]='awesome';
	
	$linkSet='';
	$linkSet=__(ucfirst(tmip_lang_please_rate_us).' ',$tmip_plugin_dir_name);
	$linkSet .='<span class="rate-tmip-stars">';
	foreach ($rating as $value=>$name) {
		$rate_url=$tmip_rate_wp_url;
		$url_target='target="_blank"';
		if (tmip_enable_meta_rating==2 and $value<5) {
			//$name=$rating[$value];
			$rate_url=$tmip_rate_pl_url;
			$url_target='';
		}
		$linkSet .="<a href='".$rate_url.$value."#new-post' ".$url_target." data-rating='".$value."' title='" . __($name, $tmip_plugin_dir_name)."'><span class='dashicons dashicons-star-filled' style='color:#ffb900!important;'></span></a>";
	}
	$linkSet .='<span>';
	$links[] = $linkSet;
	
	return $links;
}
function tmip_setting_link($links) {
	global $tmip_plugin_admin_url;
	if (is_multisite() && !is_main_site () && !current_user_can('manage_network_plugins')) return $links;
	
	$settings_link='<a href="'.$tmip_plugin_sett_url.'">'._x('Settings', 'Menu item', 'tracemyip-visitor-analytics-ip-tracking-control') . '</a>';
	array_unshift ($links, $settings_link);
	return $links;
}
function tmip_plugin_action_links($links) {
	global $tmip_plugin_sett_url;
	if (is_multisite() && !is_main_site () && !current_user_can ('manage_network_plugins')) {
		return $links;
	}
	
	array_unshift($links, 
		'<a href="'.tmip_acc_upgr_url.'" target="_blank">'.tmip_upgrade_to_pro_vers.'</a>' );
	
	array_unshift ($links, 
		'<a href="'.$tmip_plugin_sett_url.'">'._x('Settings', 'Menu item', 'tracemyip-visitor-analytics-ip-tracking-control') . '</a>');
	return $links;
}
function tmip_insert_visitor_tracker() {
    $code=get_option(tmip_visit_tracker_val);
	
	$successVTC=0;
	$res=array();
	
    if ($code and strpos($code,'4684NR-IPIB') !== false) {
		$code=stripslashes($code);
		$code=str_replace('src="//https:','src="',$code);
		$code=str_replace('src="//http:','src="',$code);
		$code=str_replace('src="https:','src="',$code);
		$code=str_replace('src="http:','src="',$code);
		
		// Convert an HTML code to JavaScript code in real time
		if (tmip_html_to_js_format_realti==1 and $code and 
				strpos($code,'<script')==false and strpos($code,'rgtype=4684NR')==false) {
			// Extract tracker code
			$input=array();
			$input['code_source']=$code;
			$input['code_tag']='img';
			$r=tmip_get_html_tag_string($input);
			$tmip_tag_attributes=$r[1]['tag_attributes'];
			$tmip_code_source=$r['code_source'];

			// Check and convert the tracker code
			if ($tmip_tag_attributes and $tmip_code_source) {
				$input=array();
				$input['convert_mode']=1; // 1-realtime, 2-on submit
				$input['code_source']=$tmip_code_source;
				$input['tag_attributes']=$tmip_tag_attributes;
				$res=tmip_convert_html_code_to_javascript($input);
				$successVTC=$res['success'];
				if ($successVTC) $code=$res['code_tracker'];
			}
		}
		
		
		$add_remove_async=0; // 1-Add if does not exist, 2-Remove if exists
		$pos_val=get_option(tmip_position_val);
		
		// Header Async - set tracker image enclosing div font-size to 0 to prevent line space for invisible tracker option
		if ($pos_val and $pos_val==='header_async') {
			$code=tmip_strip_divs($code);
			$reduce_line_height='lgUrl.php?gustInvT=fzize0';
			if (!stristr($code,$reduce_line_height)) {
				$code=str_replace('lgUrl.php?',$reduce_line_height.'&amp;',$code);
			}
			$add_remove_async=1;
			
		// Header no async to show tracker in center header
		} elseif ($pos_val and $pos_val==='header') {
			$add_remove_async=2;
			
		// Footer async
		} elseif (tmip_trk_add_async_attr===1) {
			$add_remove_async=1;
		}
		
		$is_async_code=0;	// 1-is async, 2-is not async
		if (strpos($code,'<script')==true) { if (stristr($code,' async ')) $is_async_code=1; else $is_async_code=2;  }
		if ($add_remove_async==1 and $is_async_code==2) $code=str_replace('<script','<script async',$code);	// Add async
		if ($add_remove_async==2 and $is_async_code==1) $code=str_replace('<script async','<script',$code);	// Remove async
		
		$js_conversion=NULL;
		if ($successVTC) $js_conversion=tmip_stats_optimi_pagntra; elseif ($res and $res['alerts']) $js_conversion=$res['alerts'];
		tmip_log_stat_data(array('type'=>'vis_tr_query','js_conversion'=>$js_conversion));
		
		echo $code;
    }
}
function tmip_strip_divs($html) {
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NOBLANKS);
    libxml_clear_errors();
    $xpath = new DOMXPath($dom);
    $divNodes = $xpath->query('//div');
    foreach ($divNodes as $divNode) {
        $parent = $divNode->parentNode;
        while ($divNode->firstChild) {
            $parent->insertBefore($divNode->firstChild, $divNode);
        }
        $parent->removeChild($divNode);
    }
    $html = $dom->saveHTML();
    return $html;
}
function tmip_insert_page_tracker() {
    $code=get_option(tmip_page_tracker_val);
    if ($code and strpos($code,'tP_lanPTl') !== false) {
		$code=stripslashes($code);
		$code=str_replace('src="http:','src="',$code);
		$code=str_replace('src="https:','src="',$code);
		tmip_log_stat_data(array('type'=>'pag_tr_query'));
		echo $code;
    }
}
function tmip_log_stat_data($input) {
	foreach ($input as $a=>$b) ${$a}=$b; unset($a,$b,$input);

	$output=array();
	$action=NULL;
	$database_column1=NULL; 
	$database_column2=NULL;
	$database_column3=NULL; 
	$database_column4=NULL;

	// Log stats
	if ($type=='vis_tr_query' or $type=='vis_tr_reset' or $type=='pag_tr_query' or $type=='pag_tr_reset' or $type=='reset_all_data') {
		if ($type=='vis_tr_query' or $type=='vis_tr_reset' or $type=='reset_all_data') {
			$database_column1='tmip_stat_vis_tr_query';
			$database_column2='tmip_stat_vis_tr_hjconv';
			$database_column3='tmip_stat_vis_tr_total_qry';
			$database_column4='tmip_stat_vis_tr_first_used_unix';
						
		} elseif ($type=='pag_tr_query' or $type=='pag_tr_reset') {
			$database_column1='tmip_stat_pag_tr_query';
		}
		if ($type=='vis_tr_query' or $type=='pag_tr_query') {
			$action='write';
		} elseif ($type=='vis_tr_reset' or $type=='pag_tr_reset') {
			$action='reset';
		}
		
		if ($action=='write') {
			if ($database_column1) update_option($database_column1,(int)get_option($database_column1)+1);
			if ($database_column2) update_option($database_column2, strip_tags(esc_js($js_conversion))); // This was previously sanitized. strip tags is not intended to sanitize and is used for a different purpose.
			if ($database_column3) update_option($database_column3,(int)get_option($database_column3)+1);
			if ($database_column4 and empty(get_option($database_column4))) update_option($database_column4, time());// Nothing to sanitize here, its unix time();
		} elseif ($action=='reset') {
			if ($database_column1) update_option($database_column1,	0);
			if ($database_column2) update_option($database_column2, '');
		} elseif ($type=='reset_all_data') {
			if ($database_column1) update_option($database_column1,	0);
			if ($database_column2) update_option($database_column2, '');
			if ($database_column3) update_option($database_column3,	0);
			if ($database_column4) update_option($database_column4, 0);
		}
	

	# READ internal plugin stats
	// Visitor tracker
	} elseif (tmip_codes_usage_stats_data and $type=='vis_tr_stats') {
		$output['vis_tr_queries_cnt']=(int)get_option('tmip_stat_vis_tr_query');
		if ($v=get_option('tmip_stat_vis_tr_hjconv')) {
			$v=stripslashes($v);
		} else {
			$v=NULL;
		}
		$output['vis_tr_htmljs_conv']=$v;
		$output['vis_tr_first_use_unix']=(int)get_option('tmip_stat_vis_tr_first_used_unix');
		$output['vis_tr_total_queries']=(int)get_option('tmip_stat_vis_tr_total_qry');
		
		if ($v=$output['vis_tr_queries_cnt']) {
			$activity=tmip_stats_receive_trdata;
			if (tmip_codes_usage_stats_data==2) $activity .=' ['.($v/1).']';
		} else {
			$activity=tmip_stats_pending_trdata.'';
		}
		$output['vis_tr_stats']='<b>'.tmip_stats_activi_pagntra.'</b>: '.$activity;
		if ($v=$output['vis_tr_htmljs_conv']) {
			$output['vis_tr_stats'] .=', <b>'.tmip_stats_status_pagntra.'</b>: '.$v;
		}
		return $output;
	
	// Page tracker
	} elseif (tmip_codes_usage_stats_data and $type=='pag_tr_stats') {
		$output['pag_tr_queries_cnt']=(int)get_option('tmip_stat_pag_tr_query');
		if ($v=$output['pag_tr_queries_cnt']) {
			$activity=tmip_stats_activet_pgdata;
			//if (tmip_codes_usage_stats_data==2) $activity .=' ['.($v/1).']';
		} else {
			$activity=tmip_stats_pending_pgdata.'';
		}
		$output['pag_tr_stats']='<b>'.tmip_stats_status_pagntra.'</b>: '.$activity;
		return $output;
	}
}
		

function tmip_addToTags($pid){
   if (is_single()) {
       global $post;
	   $queried_post=get_post($pid);
       $authorId=$queried_post->post_author;
	   ?>
		<script type="text/javascript">
            var _tmip=_tmip || [];
            _tmip.push({"tags": {"author": "<?php the_author_meta( 'nickname', $authorId); ?>"}});
        </script>
	   <?php
   }
}
// Add to Dashboard menu
function tmip_admin_menu() {
	$hook=add_submenu_page(
		'index.php',	// index,php to attached to dashboard menu
		__(tmip_service_Nname.' Reports'),
		__(tmip_service_Nname.' Reports'),
		'publish_posts',
		'tmip',			// tmip to highlight the dashboard link
		'tmip_reports_page'
	);
	add_action(
		'load-$hook',
		'tmip_reports_load'
	);
/*
	// Add to plugins drop down 
	$hook=add_submenu_page(
		'plugins.php',
		__(''.tmip_service_Nname.' Settings'),
		__(''.tmip_service_Nname.' Settings'),
		'manage_options',
		'tmip_admin',
		'tmip_reports_page1'
	);
*/
}
// Add link to WP settings
function add_tmip_option_page() {
	global $tmip_plugin_sett_url;
	global $tmip_plugin_basename;
	add_options_page(tmip_service_Nname.' Settings', tmip_service_Nname, "manage_options",  $tmip_plugin_sett_url, '');
// add_options_page(tmip_service_Nname.' Settings', tmip_service_Nname, "manage_options", $tmip_plugin_basename, 'tmip_settings_page');
}
function tmip_reports_load() {
	add_action(
		'admin_head',
		'tmip_reports_head'
	);
}
function tmip_reports_head() {
	echo '<style type="text/css">body { height: 100%; }</style>';
}

function tmip_get_html_tag_string($input,$returnrs=NULL) {
	foreach ($input as $a=>$b) ${$a}=$b; unset($a,$b,$input);
	
	$array_result=array(); $output=array();
	if (isset($code_source)) {
		$code_source=trim($code_source);
		$output['code_source']=$code_source;
		if ($code_source) preg_match_all('/<'.$code_tag.'[^>]+>/i',$code_source, $array_result); 
	}
	if (is_array($array_result) and isset($array_result[0]) and is_array($array_result[0]) and array_filter($array_result[0])) {
		$i=1;
		foreach ($array_result[0] as $tag_html) {
			if ($tag_html) {
				$output[$i]['tag_html']=$tag_html;
				$output[$i]['tag_preview']=htmlentities($tag_html);
				
				// All Attributes
				$data=array();
				if (!isset($code_attrib)) $code_attrib=NULL;
				if (!$code_attrib) {
					$dom = new DOMDocument();
					$dom->loadHTML($tag_html);
					$ul = $dom->getElementsByTagName($code_tag)->item(0);
					if ($ul->hasAttributes()) {
						foreach ($ul->attributes as $attr) {
							$name = $attr->nodeName;
							$value = $attr->nodeValue;    
							$data[$name] = $value;
						}
					}
					$output[$i]['tag_attributes']=$data;
				
				// Specific Attributes
				} else {
										preg_match_all('/('.trim($code_attrib).')=("[^"]*")/i', $tag_html, $matches);
					if (!$matches[0]) 	preg_match_all("/(".trim($code_attrib).")=('[^\']*')/i", $tag_html, $matches);
					if (is_array($matches) and array_filter($matches[1])) {
						$t=0;
						foreach ($matches[1] as $tag_key) {
							$tag_key=trim($tag_key);
							$raw_value=trim($matches[2][$t]);
							$value=trim($raw_value,'"');
							if ($value===$raw_value) {
								$value=trim($raw_value,"'");
							}
							$data[trim($tag_key)]=$value;
							$t++;
						}
					}
					$output[$i]['tag_attributes']=$data;
				}
			}
			$i++;
		}
	}
	if (isset($returnrs)) return $output[$returnrs]; else return $output;
}

function array_sortby_value_len($array,$order='desc'){
	$output=NULL;
	if (is_array($array)) {
		if ($order=='asc') 
			usort($array, function($a, $b) { $difference =  strlen($a) - strlen($b); return $difference ?: strcmp($a, $b); });
		if ($order=='desc') 
			usort($array, function($a, $b) { $difference =  strlen($b) - strlen($a); return $difference ?: strcmp($a, $b); });
		$output=$array;
	} else {
		$output=$array;
	}
	return $output;
}

function tmip_lTrimPlus2($text,$prefixes,$caseSens=1,$tryAll_OR_firstMatch=2,$tryShort_OR_longFirst=2) {
	$arr=explode('|',$prefixes);
	if ($tryShort_OR_longFirst==2) $tOrder='desc'; else $tOrder='asc';
	$arr=array_sortby_value_len($arr,$tOrder);
	
	$_strpos='stripos';
	if ($caseSens==1) {
		$_strpos='strpos';
	}
	
	foreach ($arr as $pref) {
		if (0===$_strpos($text,$pref))  {
			$text=substr($text,strlen($pref));
			if ($tryAll_OR_firstMatch==2) break;
		}
	}
    return $text;
}

function tmip_convert_html_code_to_javascript($input,$returnrs=NULL) {
	foreach ($input as $a=>$b) ${$a}=$b; unset($a,$b,$input);
	$output=array();
	$alerts=array();
	$comments=array();
	$success=0;
	$tVr=900; // Code version - reserved range 900-999 for WP plugin. Calculated in pSum.php
	
	$lanCompNameURL=tmip_service_Dname;
	$alert_1=tmip_prov_trackerc_valid;
	$alert_2=tmip_trk_code_inst_refrm;
	$alert_5=tmip_prov_trackerc_valid;
	$alert_3=tmip_prov_trk_code_notva;
	$alert_4=tmip_check_trk_code_inst;
	
	$srsString=$tag_attributes['src'];
	$urlParse=parse_url($srsString);
	$host=$urlParse['host'];
	$e=explode('.',$host);

	if ($e[1] and $e[2] and $srs=$srsString and 
			(!stristr($code_source,'stlVar2=') and !stristr($code_source,'pidnVar2=') and !stristr($code_source,'prtVar2=')) and 
			stristr($srs,'/4684NR-IPIB/') and (stristr($srs,'/njsUrl/') or stristr($srs,'/ans/'))
		) {
		
		// If an HTML code is submitted with https:// prefix, trim
		$srs=tmip_lTrimPlus2(ltrim($srs,'/'),'|http|https|:/|/',$caseSens=0,$tryAll_OR_firstMatch=1,$tryShort_OR_longFirst=2);

		$trkDomnURL=$e[1].'.'.$e[2];
		$e=preg_split('/'.$trkDomnURL.'/i', $srs);
		$trkSD=trim($e[0],'//.');
		$trkST=trim($e[1]);
		
		$trSTR='';
		foreach (tmip_trk_path_str_array as $str) {
			$v='/'.$str.'/';
			if (strstr($srs,$v)) {
				$trSTR=$v;
				$e=explode($v,$srs);
				break;
			}
		}
		
		$e=explode('/',$e[1]);
		$styleN=trim($e[0]);
		$codeID=trim($e[1]);
		$ProjID=trim($e[2]);
		$ProjPrt=trim($e[3]);
		$curYear=date("Y");
		$timeStamp=date("His");
		$dateStamp=date("mdY");
		$imgAlt=trim($tag_attributes['alt']);

		if ($styleN and $codeID and $ProjID and $ProjPrt and $curYear and $timeStamp and $dateStamp) {
			$converted=0;
			// Does not include code version tracking
			# /tracker/1500~1587394715~14*2~0F5999*08EFFF*537899*000000~1*1*0*1*1/4684NR-IPIB/124352683/1/njsUrl/
			$html_srs_code='';
			if (stristr($srs,'/njsUrl/')) {
				$converted=1;
				$html_srs_code='//'.$trkSD.'.'.$trkDomnURL.$trSTR.$styleN.'/'.$codeID.'/'.$ProjID.'/'.$ProjPrt.'/njsUrl/';
			
			// Includes code version tracking
			# /tracker/1500~1587394715~14*2~0F5999*08EFFF*537899*000000~1*1*0*1*1/4684NR-IPIB/124352683/1/12/ans/
			} elseif (stristr($srs,'/ans/')) {
				$converted=2;
				$html_srs_code='//'.$trkSD.'.'.$trkDomnURL.$trSTR.$styleN.'/'.$codeID.'/'.$ProjID.'/'.$ProjPrt.'/'.$tVr.'/ans/';
			}
			if ($converted) {
				$code_comment='';
				if ($convert_mode==1) {
					$code_comment='HTML>JS Realtime';
				} elseif ($convert_mode==2) {
					$code_comment='WP HTML>JS On-Install';
				}
				// TMIP Code Format ID 083122024803
				$code=('
<!-- Start: Copyright '.trim($curYear).' '.$lanCompNameURL.' Service Code ('.$timeStamp.'-'.$dateStamp.') '.$code_comment.' - DO NOT MODIFY //-->
<div style="line-height:16px;text-align:center;position:relative;z-index:100001;"><script type="text/javascript" src="//'.$trkSD.'.'.$trkDomnURL.$trSTR.'lgUrl.php?random=\'+Math.random()+\'&amp;stlVar2='.$styleN.'&amp;rgtype='.$codeID.'&amp;pidnVar2='.$ProjID.'&amp;prtVar2='.$ProjPrt.'&amp;scvVar2='.$tVr.'"></script><noscript><a href="https://www.'.$trkDomnURL.'"><img src="'.$html_srs_code.'" alt="'.$imgAlt.'" referrerpolicy="no-referrer-when-downgrade" style="border:0px;"></a></noscript></div>
<!-- End: '.$lanCompNameURL.' Service Code //-->');
				$code=tmip_unify_new_lines(trim($code));

				if (tmip_html_to_js_format_onsubm==1) $v=$alert_2; else $v=$alert_5;
				$alerts[]=ucfirst($v.'<hr>'.$alert_4);
				$success=1;
			}
		} else {
			$alerts[]=str_replace('%ERR_NUM%','ER-0419200934',ucfirst($alert_3));
		}
	} elseif (!stristr($code_source,'stlVar2=') or !stristr($code_source,'pidnVar2=') or !stristr($code_source,'prtVar2=')) {
		$alerts[]=str_replace('%ERR_NUM%','ER-0419200929',ucfirst($alert_3));
	} else {
		$alerts[]=ucfirst($alert_1);
		$comments[]=ucfirst($alert_4);
		$code=$code_source;
		$success=2;
	}
	
	$output['success']=trim($success);
	$output['alerts']=implode('<br>',$alerts);
	$output['comments']=implode('<br>',$comments);
	$output['code_tracker']=trim($code);
	$output['code_preview']=htmlentities($code);
	if (isset($returnrs)) return $output[$returnrs]; else return $output;
}

function tmip_alert_box($input) {
	foreach ($input as $a=>$b) ${$a}=$b; unset($a,$b,$input);
	if ($title[0])		$_title=trim($title[0]); 		elseif ($title[1])		$_title=trim($title[1]);
	if ($comments[0])	$_comments=trim($comments[0]); 	elseif ($comments[1])	$_comments=trim($comments[1]);
	//$codeAlert_title=tmip_settings_hv_updated;
	if (!isset($output)) $output='';
	$output .='<div class="'.$box_class.'">';
	if ($_title)				$output .='<p class="'.$title_class.'">'.$_title.'</p>';
	if ($_title and $_comments)	$output .='<hr>';
	if ($_comments)				$output .=$_comments;
	$output .='</div><br>';
	return $output;
}

function tmip_reset_plugin_settings() {
	// Reset settings
	update_option(tmip_position_val, ''); // clear to trigger first time install state
	update_option(tmip_visit_tracker_val,'');
	update_option(tmip_page_tracker_val,'');
}
function tmip_remove_tabs_new_lines($str,$option=1) {
	if ($str) {
		$str=preg_replace('/\r|\n/', '', $str);
		// preserve new lines after comments
		if ($option and $option<>1) {
			$str=str_replace('//-->','//-->'."\n",$str);
			$str=str_replace('<!--',"\n".'<!--',$str);
		}
		if ($option==2) {
			$str=preg_replace("/\s+/",' ', $str);
		}
		$str=trim($str);
	}
	return $str;
}
function tmip_settings_page() {

	global $show_div_target,$hide_div_target,$tmip_plugin_dir_url;
	
	$this_section='';
	$output='';
	
	// Override section by main menu URL token
	$menu_url_show_div_target=array();
	if (is_array($show_div_target) and array_filter($show_div_target)) {
		$menu_url_show_div_target=$show_div_target;
	}
	tmip_load_css();
	tmip_load_js();
	tmip_load_fontawesome_cloufare();
	
	// Open panel
	$output .='<div id="tmip_sett_wrap_1">';
	
	$codeAlert_neutralTitle=NULL;;
	$codeAlert_neutral_text=NULL;;
	
	$codeAlert_green_text=NULL;
	$codeAlert_red_text=NULL;
	
	$proceedToUpdate=0;
	$haltUpdate='';
	$noChanges=NULL;
	$visTRpsDbVar=NULL;
	$pagTRpsDbVar=NULL;
	if (isset($_POST['info_update'])) 		$info_update=sanitize_text_field($_POST['info_update']); else $info_update=NULL;
	if (isset($_POST['nonce_tmip_check'])) 	$tmip_posted_nonce=sanitize_text_field($_POST['nonce_tmip_check']); else $tmip_posted_nonce=NULL;
	
	$failedNonceCheck=0;
	$allowUpdate=1; // 1-Update on submit. 0-debug
	$tmip_genert_nonce=wp_create_nonce(plugin_basename(__FILE__));
	$tmip_verify_nonce=wp_verify_nonce($tmip_posted_nonce,plugin_basename(__FILE__));
	if ($info_update and $tmip_posted_nonce and $tmip_verify_nonce<>1) {
		$codeAlert_red_text .='<div class="tmip_alert_subtitle">'.tmip_fa__hand_point_right_lg.' '.tmip_invalid_nonce_check.'</div><br>';
		$failedNonceCheck=1;
	}

	// tmip_log_stat_data(array('type'=>'reset_all_data')); // reset all stats data, leave the codes

	
	// USAGE STATS
	$vis_tr_stats=tmip_log_stat_data(array('type'=>'vis_tr_stats'));
	$pag_tr_stats=tmip_log_stat_data(array('type'=>'pag_tr_stats'));

	$vtpTRpsDbVar=!empty($postVarVTpos) ? $postVarVTpos : get_option(tmip_position_val); // NULL before first saved settings

	if (!$vtpTRpsDbVar or ($info_update and !trim(sanitize_text_field($_POST[tmip_visit_tracker_val])))) {
		$hide_div_target=array('sec_rate');
	}
	
	if ($vis_tr_stats['vis_tr_total_queries']<tmip_codes_usage_rate_thresh) {
		$hide_div_target=array('sec_rate');
	}

	###### CHECK PROJECT CODE ALERT ###########################################################
	// Project code is not present
	$vistracker_db_source_code=trim(stripslashes(get_option(tmip_visit_tracker_val)));
	$pagtracker_db_source_code=trim((get_option(tmip_page_tracker_val)));
	
	/*	Bug report 101721053351
		$allowed_protocols=array();
		$allowed_html=array(
		'div' =>	 	array('style' => array('style'),'script' => array('type','src')),
		'script' => 	array('type' => array(),'src' => array()),
		'noscript' => 	array(),
		'img' => 		array('src' => array(),'alt' => array(),'alt' => array('style')),
		'a' => 			array('href' => array(),'title' => array()),
		);
	*/
	
	$postVarVisTr=NULL;
	if (isset($_POST[tmip_visit_tracker_val])) {
		$postVarVisTr=$_POST[tmip_visit_tracker_val];
		/*
			Bug report 101721053351  // wp_kses() bug converts &amp; to &#038; on wordpress hosted sites and ommits javascript tag
			$postVarVisTr=wp_kses($_POST[tmip_visit_tracker_val],$allowed_html,$allowed_protocols);
		*/		
		$postVarVisTr=tmip_unify_new_lines(trim(stripslashes($postVarVisTr)));
		$postVarVisTr=tmip_remove_tabs_new_lines($postVarVisTr);
	}
	
	$postDBPageTCnp=NULL;
	if ($vtpTRpsDbVar or $postVarVisTr or ($info_update and !$postVarVisTr)) {
		$visTRpsDbVar=!empty($postVarVisTr) ? $postVarVisTr : get_option(tmip_visit_tracker_val);
		// Project visitor tracker code is not placed
		if (!$visTRpsDbVar) {
			$codeAlert_red_text .='<div class="tmip_alert_subtitle">'.tmip_fa__hand_point_right_lg.' '.tmip_no_code_entered_alr.'</div><br>';
			// Page tracker code is installed, but visitor tracker is not
			if ($pagtracker_db_source_code) {
				$postDBPageTCnp=83122013858;
			}
			if (empty($postVarVisTr) and $info_update) {
				$proceedToUpdate=10; // Alow to clear the code input box
			} else {
				//$codeAlert_red_text .='<div class="tmip_alert_subtitle">'.tmip_fa__hand_point_right_lg.' '.tmip_no_code_entered_alr.'</div><br>';
			}
			
		// Project code contains page tracker code
		} elseif ($visTRpsDbVar and strpos($visTRpsDbVar,'tP_lanPTl') !== false) {
			if (empty($postVarVisTr) and $info_update) {
				$proceedToUpdate=11;
			} else {
				$codeAlert_red_text .='<div class="tmip_alert_subtitle">'.tmip_fa__hand_point_right_lg.' '.tmip_pagetr_into_vistrak.'</div><br>';
				$haltUpdate='hu_visitor_tracker_1';
			}
		
		// Project visitor tracker code is not valid - quick check
		} elseif ($visTRpsDbVar and strpos($visTRpsDbVar,'4684NR-IPIB')==false) {
			if (empty($postVarVisTr) and $info_update) {
				$proceedToUpdate=12;
			} else {
				$codeAlert_red_text .='<div class="tmip_alert_subtitle">'.tmip_fa__hand_point_right_lg.' '.tmip_tracker_code_ent_nv.'</div>';
				$v=str_replace('%ERR_NUM%','ER-0423200718',tmip_prov_trk_code_notva);
				$codeAlert_red_text .='<p class="tmip_alert_comments">'.$v.'</p>';
				$haltUpdate='hu_visitor_tracker_2';
			}
		}

		// ###### Verify and convert an HTML code into JavaScript code to enhance statistics #####
		//  or $source_code=get_option(tmip_visit_tracker_val)
		if (!$haltUpdate and (!$postVarVisTr or (
				$postVarVisTr and ($postVarVisTr<>$vistracker_db_source_code or 
				(tmip_html_to_js_format_onsubm and $postVarVisTr==$vistracker_db_source_code and 
				 	$vis_tr_stats['vis_tr_htmljs_conv']=='optimized')))
			)) {
			if ($postVarVisTr)					$code_source=$postVarVisTr;
			elseif ($vistracker_db_source_code)	$code_source=$vistracker_db_source_code;
					
				
			// Extract tracker code
			$input=array();
			if (isset($code_source)) $input['code_source']=$code_source;
			$input['code_tag']='img';
			$r=tmip_get_html_tag_string($input);
			if (isset($r[1]['tag_attributes'])) $tmip_tag_attributes=$r[1]['tag_attributes']; 	else $tmip_tag_attributes='';
			if (isset($r['code_source']))		$tmip_code_source=$r['code_source'];			else $tmip_code_source='';

			// Check and convert the tracker code
			if ($tmip_tag_attributes and $tmip_code_source) {
				$input=array();
				$input['convert_mode']=2; // 1-realtime, 2-on submit
				$input['code_source']=$tmip_code_source;
				$input['tag_attributes']=$tmip_tag_attributes;
				$res=tmip_convert_html_code_to_javascript($input);
				$successVTC=$res['success'];

				if ($info_update and $successVTC and $postVarVisTr) {
					$codeAlert_green_text .='<div class="tmip_alert_subtitle">'.tmip_fa_checkmark_lg.' '.$res['alerts'].'</div>';
					if (tmip_html_to_js_format_onsubm==1) {
						$postVarVisTr=$res['code_tracker'];
					}
				} elseif (!$successVTC) {
					$haltUpdate='hu_visitor_tracker_3';
					$codeAlert_red_text .='<div class="tmip_alert_subtitle">'.tmip_fa__hand_point_right_lg.' '.$res['alerts'].'</div>';
				}
				if ($info_update and $successVTC and $postVarVisTr) {
					$codeAlert_green_text .='<p class="tmip_alert_comments">'.$res['comments'].'</p>';
					$proceedToUpdate=$res['success']; // $proceedToUpdate=2;
				}
			}

		} elseif ($info_update and $postVarVisTr==$vistracker_db_source_code) {
			$proceedToUpdate=9;
			$noChanges[]=1;
		}
	}
	

	
	###### CHECK CODE POSITION ASSIGNMENT ###########################################################
	$vis_tracker_pos_val=get_option(tmip_position_val);
	$postVarVTpos='';
	if (isset($_POST[tmip_position_val])) {
		$postVarVTpos=trim(sanitize_text_field($_POST[tmip_position_val])); 
	}

	if ($info_update and $postVarVTpos and $postVarVTpos==$vis_tracker_pos_val) {
		$proceedToUpdate=30;
		$noChanges[]=1;
	} else {
		if (!$postVarVisTr and $info_update) {
			//$codeAlert_red_text .='<div class="tmip_alert_subtitle">'.tmip_fa__hand_point_right_lg.' '.tmip_no_code_entered_alr.'</div><br>';
		} elseif ($postVarVTpos and strpos($postVarVTpos,'header')!== false) {
			$codeAlert_green_text .='<div class="tmip_alert_subtitle">'.tmip_fa__hand_point_right_lg.' '.tmip_vistr_set_to_headA.'</div><br>';
		} elseif ($postVarVTpos and strpos($postVarVTpos,'footer')!== false) {
			$codeAlert_green_text .='<div class="tmip_alert_subtitle">'.tmip_fa__hand_point_right_lg.' '.tmip_vistr_set_to_footA.'</div><br>';
		}
	}
	
	###### CHECK PAGE TRACKER CODE ###########################################################
	// Page tracker code contains visitor tracker code
	$postVarPagTr='';
	if (isset($_POST[tmip_page_tracker_val])) {
		$postVarPagTr=trim($_POST[tmip_page_tracker_val]);
		if (strpos($postVarPagTr,'4684NR-IPIB')==false) {
			$postVarPagTr=tmip_sanitize_rebuild_page_tracker_code($_POST[tmip_page_tracker_val]);
			$postVarPagTr=str_replace('/script>"','\\\/script>"',$postVarPagTr);
		}
	}
	//$postVarPagTr=esc_js(tmip_remove_tabs_new_lines($_POST[tmip_page_tracker_val]));
	//$postVarPagTr=tmip_unify_new_lines(trim(stripslashes($postVarPagTr)));
	//$postVarPagTr=html_entity_decode($postVarPagTr);
	//$postVarPagTr=str_replace('/script>"','\\\/script>"',$postVarPagTr);

	$pagTRpsDbVar=!empty($postVarPagTr) ? $postVarPagTr : get_option(tmip_page_tracker_val);
	if ($pagTRpsDbVar and strpos($pagTRpsDbVar,'4684NR-IPIB')!== false) {
		if (empty($postVarPagTr) and $info_update) {
			$proceedToUpdate=20;
		} else {
			$codeAlert_red_text .='<div class="tmip_alert_subtitle">'.tmip_fa__hand_point_right_lg.' '.tmip_vistrk_into_pagetrk.'</div><br>';
			$haltUpdate='hu_page_tracker_1';
		}
	
	// Page Tracker code is not a JavaScript version of the code
	} elseif ($pagTRpsDbVar and 
			(	strpos($pagTRpsDbVar,'echo') !== false or 
				strpos($pagTRpsDbVar,'scr\"') !== false )
		) {
		if (empty($postVarPagTr) and $info_update) {
			$proceedToUpdate=21;
		} else {
			$codeAlert_red_text .='<div class="tmip_alert_subtitle">'.tmip_fa__hand_point_right_lg.' '.tmip_pagetr_code_notjava.'</div><br>';
			$haltUpdate='hu_page_tracker_2';
		}
	// Page Tracker code is not valid
	} elseif ($pagTRpsDbVar and strpos($pagTRpsDbVar,'tP_lanPTl')==false) {
		if (empty($postVarPagTr) and $info_update) {
			$proceedToUpdate=22;
		} else {
			$codeAlert_red_text .='<div class="tmip_alert_subtitle">'.tmip_fa__hand_point_right_lg.' '.tmip_pagetr_cd_not_valid.'</div>';
			$codeAlert_red_text .='<p class="tmip_alert_comments">'.tmip_pagetr_generate_npl.'</p>';
			$haltUpdate='hu_page_tracker_3';
		}
	// No changes in Visitor Tracker code on submit
	} elseif ($info_update and $postVarPagTr==$pagtracker_db_source_code) {
		$proceedToUpdate=19;
		$noChanges[]=1;
	
	// Page Tracker code valid and entered into DB
	} elseif ($postVarPagTr and $pagTRpsDbVar) {
		$proceedToUpdate=23;
		$codeAlert_green_text .='<div class="tmip_alert_subtitle">'.tmip_fa_checkmark_lg.' '.tmip_prov_trackerp_valid.'</div>';
	}
	
	// Page Tracker code is installed but the visitor tracker is not posted
	if ((!$info_update and $postDBPageTCnp==83122013858) or (!$postVarVisTr and $postVarPagTr)) {
		$codeAlert_red_text .='<div class="tmip_alert_subtitle">'.tmip_fa__hand_point_right_lg.' '.tmip_pagetr_no_vis_tralrt.'</div>';
	}
	



	###### CODE UPDATED ALERT ###########################################################
	if ($haltUpdate) $proceedToUpdate=0;
	if ($failedNonceCheck<>1) {

		// Remove visitor tracker code
		if ($info_update and (!$postVarVisTr and get_option(tmip_visit_tracker_val)) or stristr($haltUpdate,'hu_visitor_tracker'))	{
			if ($failedNonceCheck<>1 and $allowUpdate) {
				update_option(tmip_visit_tracker_val,'');
				tmip_log_stat_data(array('type'=>'vis_tr_reset'));
			}
			if (!$postVarVisTr) {
				$codeAlert_neutralTitle=tmip_settings_hv_updated;
				$codeAlert_neutral_text .='<div class="tmip_alert_subtitle">'.tmip_fa__hand_point_right_lg.' '.tmip_vistr_cd_not_valid.'</div>';
			}
		}
		// Remove page tracker code
		if ($info_update and (!$postVarPagTr and get_option(tmip_page_tracker_val)) or stristr($haltUpdate,'hu_page_tracker')) {
			if ($allowUpdate) {
				update_option(tmip_page_tracker_val,'');
				tmip_log_stat_data(array('type'=>'pag_tr_reset'));
			}
			if (!$postVarPagTr) {
				$codeAlert_neutralTitle=tmip_settings_hv_updated;
				$codeAlert_neutral_text .='<div class="tmip_alert_subtitle">'.tmip_fa__hand_point_right_lg.' '.tmip_pagtr_cd_not_valid.'</div>';
			}
		}
		
		// Update settings
		if ($proceedToUpdate and isset($info_update) and 
				 check_admin_referer('update_tmip_visit_tracker_nonce','tmip_visit_tracker_nonce')) {
			// Visitor Tracker Code
			$tmip_visit_tracker=$postVarVisTr;
			if ($tmip_visit_tracker=='') {
				$tmip_visit_tracker=tmip_visit_tracker_default;
			}
			if ($allowUpdate) {
				update_option(tmip_visit_tracker_val,$tmip_visit_tracker);
				tmip_log_stat_data(array('type'=>'vis_tr_reset'));
			}
	
			// Visitor Tracker Position
			$tmip_set_vistr_pos=$vis_tracker_pos_val;
			if ($tmip_set_vistr_pos != 'header' and $tmip_set_vistr_pos != 'header_async' and $tmip_set_vistr_pos != 'footer') {
				$tmip_set_vistr_pos=tmip_position_default;
			} elseif ($postVarVTpos<>$tmip_set_vistr_pos) {
				$tmip_set_vistr_pos=$postVarVTpos;
			}
			if ($allowUpdate) {
				update_option(tmip_position_val, $tmip_set_vistr_pos);
			}
			
			// Page Tracker Code
			$tmip_page_tracker=$postVarPagTr;
			if ($tmip_page_tracker=='') {
				$tmip_page_tracker=tmip_page_tracker_default;
			}
			if ($allowUpdate) {
				update_option(tmip_page_tracker_val,$tmip_page_tracker);
				tmip_log_stat_data(array('type'=>'pag_tr_reset'));
			}
			
			// Tracking stats update
			if ($allowUpdate) {
				$vis_tr_stats=tmip_log_stat_data(array('type'=>'vis_tr_stats'));
				$pag_tr_stats=tmip_log_stat_data(array('type'=>'pag_tr_stats'));
			}
		}
	
		if (is_array($noChanges) and count($noChanges)==3) {
			$codeAlert_neutralTitle=tmip_settings_no_changes;
			$codeAlert_neutral_text .='<div class="tmip_alert_subtitle">'.tmip_fa__hand_point_right_lg.' '.tmip_settings_reman_same.'</div>';
			if ($vistracker_db_source_code) {
				$show_div_target=array('sec_rate','sec_settings');
			}
			if (!$visTRpsDbVar) {
				$hide_div_target[]='sec_rate'; // 'sec_demotracker',
			}
		}
	}
	if ($codeAlert_red_text) {
		$show_div_target=array('sec_usage','sec_settings');
		$output .=tmip_alert_box(array(
			//				text, 							default
			'title'=>		array(tmip_fa_excl_triangle_lg,	''),
			'comments'=>	array($codeAlert_red_text,		''),
			'box_class'=>	'tmip_alertRed_div',
			'title_class'=>	'tmip_alert_red_title',
		));
	}
	if ($codeAlert_neutral_text) {
		$output .=tmip_alert_box(array(
			//				text, 							default
			'title'=>		array($codeAlert_neutralTitle,	''),
			'comments'=>	array($codeAlert_neutral_text,	''),
			'box_class'=>	'tmip_alertNeutral_div',
			'title_class'=>	'tmip_alert_neutral_title',
		));
	}
	if ($proceedToUpdate and $codeAlert_green_text) {
		$show_div_target=array('sec_rate','sec_settings');
		$output .=tmip_alert_box(array(
			//				text, 							default
			'title'=>		array($codeAlert_greenTitle,	tmip_settings_hv_updated),
			'comments'=>	array($codeAlert_green_text,	''),
			'box_class'=>	'tmip_alertGreen_div',
			'title_class'=>	'tmip_alert_green_title',
		));
	}
	

	// Show section requested by main menu selection
	if ($menu_url_show_div_target and array_filter($menu_url_show_div_target)) {
		$show_div_target=$menu_url_show_div_target;
		$hide_div_target=array();
	}

	###### FEATURES ###########################################################
	$logo_url=$tmip_plugin_dir_url.'images/tmip_logo_60x60_sq.png';		
	if ((!$show_div_target or in_array('sec_about',$show_div_target)) and !in_array('sec_about',$hide_div_target)) {
		$output .='<img src="'.$logo_url.'" style="vertical-align:middle;float:right;padding-bottom:20px;margin-top:-10px;">
			<h1 class="text-center">
			<i class="fa fa-chart-bar fa-sm" style="color: #097E71; opacity: 0.5;"></i> '.ucfirst(tmip_sectl_what_is_plugin).'
			</h1>
			<blockquote>
				<h2 class="tmip_sec_title">
				<i class="fa fa-angle-double-right fa-sm" style="color: #7E0929; opacity: 0.5;"></i>
				<a href="'.tmip_home_page_url.'" target="_blank"><b>'.tmip_service_Dname.'</b></a> is a free and premium website visitor tracking service</h2>
				<section>
					<ul class="tmip_sett_list_ol">
						<li><span>Full featured individual visitor <a href="'.tmip_home_page_url.'/website-analytics.htm" target="_blank">website statistics</a> IP tracker with visitor IP tagging and new activity email alerts</span></li>
						<li><span>Visitor IP address GEO data, visitor IP address changes and computer IDs tracking</span></li>
						<li><span>Website visitor alerts, redirecting, and blocking based on custom rules by an IP address, computer ID, connection location, number of visits or page views, browser type, operating system, referrers, computer hardware specifications, etc.</span></li>
						<li><span>Mobile and desktop device statistics, targeting, redirection and browsing path control</span></li>
						<li><span>Individual Real-Time one-way message delivery to selected website visitors currently browsing a website</span></li>
						<li><span>Web page, links, document and contact forms protection, IP based tracking and complete access control</span></li>
						<li><span>Integrated IP tracking data visitor control interface within WordPress dashboard</span></li>
						<li><span>EU GDPR Data Processing compliance with variable level of restrictions per jurisdiction</span></li>
						<li><span><b>FREE</b> and advanced premium service <a href="'.tmip_premium_signup_page_url.'" target="_blank">subscriptions</a></span></li>
					</ul>
				</section>
			</blockquote>
		';
	}




	###### QUICK SETUP STEPS ###########################################################
	if ((!$show_div_target or in_array('sec_usage',$show_div_target)) and !in_array('sec_usage',$hide_div_target)) {
		$output .='<br>
			<h1 class="text-center">
				<i class="fa fa-file-alt fa-sm" style="color: #097E71; opacity: 0.5;"></i> '.ucfirst(tmip_sectl_easy_steps_set).'
			</h1>	
			<blockquote>
				<h2 class="tmip_sec_title">
				<i class="fa fa-angle-double-right fa-sm" style="color: #7E0929; opacity: 0.5;"></i>
				To activate '.tmip_service_Nname.' tracking for WordPress, follow these steps:</h2>
				<section>
					<ol class="tmip_sett_list_ul">
						<li><span><a href="'.tmip_free_signup_page_url.'" target="_blank"><b>Select a '.tmip_lang_visitr_track_ic.' style</b></a> and generate a <b>JavaScript</b> version of '.tmip_service_Dname.' '.tmip_lang_visitor_tr_code.'. Confirm your '.tmip_service_Dname.' account. If you already have a '.tmip_service_Dname.' account, login to your account and click on "Add a New Project" menu link to generate a website '.tmip_lang_visitor_tr_code.' for your new WordPress website.<span> 
						<br><span class="tmip_note_small">* If you need to change a tracker style for an existing project, click on the "tracker code" link located to the right of the project\'s name on [My Projects] page.</span></li>
						
						<li><span><b>Copy the '.tmip_lang_visitor_tr_code.'</b> and <b>paste</b> it into the ['.tmip_lang_visitor_tr_code.'] input box below.</span></li>
						
						<li><span><b>Select the '.tmip_lang_tracker_icon_ps.'</b> using the drop down menu below and click on the ['.tmip_lang_update_settings.'] button. If you are using Cloudflare with <i>Rocket Loader</i>, login to your Cloudflare dashboard and go to the Speed tab. Scroll down to "Rocket Loader", and toggle the feature <b>off</b> to enable real-time visitor tracking. Here are more details on how to adjust the '.tmip_kb_rock_ldr.' settings.</span></li>
						
						<li><span><b>Verify</b> that a '.tmip_lang_visitr_track_ic.' appears on <b>ALL</b> pages of your WordPress website. If you are a Level 2+ '.tmip_service_Dname.' subscriber, you can enable an "'.tmip_lrn_invtrk_lnk.'" option. Login to your '.tmip_service_Dname.' account and click on [My Projects] menu link and then on the "edit" link for your project to locate this option.</span></li>

						<li><span><b>Access</b> your '.tmip_service_Dname.' console, login to WordPress and go to [Dashboard] => [TraceMyIP > Reports]</span></li>
					</ol>
					
				<span class="tmip_note_small"><b>Optional:</b> To block IPs and control visitor access, install a Page Tracker code (refer to the tutorial links provided below).</span>
					<p><b>Step-by-step tutorials:</b>
						<ul class="tmip_sett_list_ul">
							<li>Installing <a href="'.tmip_wp_vistr_setup_tutorial.'" target="_blank"><b>WordPress Visitor Tracker</b></a></li>
							<li>Installing <a href="'.tmip_wp_block_ips_visit_control.'" target="_blank"><b>Page Tracker</b></a> to block IPs and control visitor access</li>
							
							<li>Adjusting <a href="'.tmip_wp_website_cache_optimization.'" target="_blank"><b>settings</b></a> for website cache or speed optimization plugins as these can prevent the visitor tracking code from loading properly.</li>
							<li>See other <a href="'.tmip_wp_other_useful_tutorials.'" target="_blank"><b>most popular</b></a> tutorials</li>
							
						</ul>
					</p>
				</section>
			</blockquote>
			
		';
	}
	
	###### RATE BUTTON / REVIEW ###########################################################
	if ((!$show_div_target or $this_section=in_array('sec_rate',$show_div_target)) and !in_array('sec_rate',$hide_div_target)) {
		$output .='<br>'.tmip_rate_review_section();
		if (!$this_section) $output .='<br>';
	}


	
	
	
	// Close panel
	$output .='</div>';



	###### VISITOR TRACKER AND PAGE TRACKER SETTINGS ###########################################################
	if ((!$show_div_target or $this_section=in_array('sec_settings',$show_div_target)) and 
													 !in_array('sec_settings',$hide_div_target)) {
		global $tmip_plugin_sett_url;
		
		$dcach=tmip_disable_content_checks();
		
		$vis_tr_used_since=NULL;
		if (($v=$vis_tr_stats['vis_tr_first_use_unix'])>10000) {
			$vis_tr_used_since='<br><b>'.(tmip_stats_used_since_unx).'</b>: '.date('F j, Y',$v);
		}
		
		$output .='<br><br><br>';
		$output .='<div id="tmip_sett_wrap_2"><div style="float:right;">v'.TMIP_VERSION.'</div>';
	
			$output .='<form method="post" action="'.$tmip_plugin_sett_url.'">';
			$output .=wp_nonce_field( 'update_tmip_visit_tracker_nonce', 'tmip_visit_tracker_nonce' );
		
		
			$output .='
			
				<h1><i class="fa fa-cogs fa-lg" style="color: #7E0929; opacity: 0.5;"></i>&nbsp;'.tmip_service_Nname.' Settings</h1>
				<blockquote>
				<fieldset class="options">
		
					<table id="tmip_sett_area">
						<tr>
							<td valign="top" style="padding-top:8px;">
								<label for="'.tmip_position_val.'"><b>'.tmip_lang_tracker_icon_ps.':</b></label>
							</td>
							<td>';
							$output .='<select class="tmip_select_dropdown" name="'.tmip_position_val.'" id="'.tmip_position_val.'">';
							
							$output .='<option value="header"';
							if (get_option(tmip_position_val)=="header") $output .=' selected="selected"';
							$output .='>Header (top center)</option>';
							
							$output .='<option value="footer"';
							if (get_option(tmip_position_val)=="footer" or (!get_option(tmip_position_val) and tmip_position_default=='footer')) $output .=' selected="selected"';
							$output .='>Footer (bottom center)</option>';
							
							$output .='<option value="header_async"';
							if (get_option(tmip_position_val)=="header_async") $output .=' selected="selected"';
							$output .='>* Header script (see note)</option>';
							
							$output .='</select><div class="tmip_note">* If you use an '.tmip_lrn_invtrk_lnk.' option, use [<b>Header async script</b>] menu option.</div>';
							
							$linkVTguide='<a href="'.tmip_wp_vistr_setup_tutorial.'" target="_blank"><b>install guide</b></a>';
							$linkPTguide='<a href="'.tmip_wp_block_ips_visit_control.'" target="_blank"><b>install guide</b></a>';
							$output .='
							</td>
						</tr>
						
						<tr>
							<td colspan="2" style="padding:0px;line-height:10px;border:0px;background-color:#666;">&nbsp;</td>
						</tr>
						
						<tr>
							<td valign="top" style="padding-top:8px;" width="200">
								<label for="'.tmip_visit_tracker_val.'" class="tmip_input_box_name">'.tmip_lang_visitor_tr_code.':</label>
								<br><div style="margin:10px 0px;" class="tmip_tip_important">Use <b>JavaScript</b> '.tmip_lang_visitor_tr_code.'.</div>
								'.$vis_tr_stats['vis_tr_stats'].$vis_tr_used_since.'
							</td>
							<td>
								<textarea id="'.tmip_visit_tracker_val.'" name="'.tmip_visit_tracker_val.'" onClick="tmip_select_all(\''.tmip_visit_tracker_val.'\');" class="tmip_textarea" placeholder="'.trim(tmip_vis_trk_inp_placehl).'"'.$dcach.'>'.htmlentities(stripslashes(get_option(tmip_visit_tracker_val))).'</textarea>
								
								<div class="tmip_note">The '.tmip_lang_visitor_tr_code.' is used to track visitor IPs, computer IDs, traffic sources, IP locations and a complete set of website stats.
								<br>( '.$linkVTguide.' )</div>
							</td>
						</tr>
						
						<tr>
							<td colspan="2" style="padding:0px;line-height:10px;border:0px;background-color:#666;">&nbsp;</td>
						</tr>
						
						<tr>
							<td valign="top" style="padding-top:8px;" width="200">
								<label for="'.tmip_lang_page_tr_code.'" class="tmip_input_box_name">'.tmip_lang_page_tr_code.':</label>
								<br><div style="margin:10px 0px;" class="tmip_tip_important">Use <b>JavaScript</b> '.tmip_lang_page_tr_code.'.</div>
								'.$pag_tr_stats['pag_tr_stats'].'
							</td>
							<td>
								<textarea id="'.tmip_page_tracker_val.'" name="'.tmip_page_tracker_val.'" onClick="tmip_select_all(\''.tmip_page_tracker_val.'\');" class="tmip_textarea" placeholder="'.trim(tmip_pag_trk_inp_placehl).'"'.$dcach.'>'.stripslashes(get_option(tmip_page_tracker_val)).'</textarea>
								<div class="tmip_note">The '.tmip_lang_page_tr_code.' is used to block IPs, control access to pages, protect contact forms, send alerts and one-way messages to visitors.
								<br>( '.$linkPTguide.' )</div>
							</td>
						</tr>
						<tr>
							<td colspan="2" style="padding:0px;line-height:10px;border:0px;background-color:#666;">&nbsp;</td>
						</tr>
					</table>
				</fieldset>
				</blockquote>
		
				<div class="tmip_submit_button_wrap">
					<input type="submit" class="tmip_submit_button1" name="info_update" value="'.tmip_lang_update_settings.'" />
				</div>
				<input type="hidden" id="check" name="nonce_tmip_check" value="'.$tmip_genert_nonce.'">
				';
			$output .='</form>';
		$output .='</div>';
	}
	
	
	###### DEMO FLAG TRACKER ########################################################### 
	if (1==2 and (!$show_div_target or $this_section=in_array('sec_demotracker',$show_div_target)) and !in_array('sec_demotracker',$hide_div_target)) {
		$output .='<br><br><br>';
		$output .='<div id="tmip_sett_wrap_3">';
			$output .='
				<h1 class="text-center">
					<i class="fa fa-file-alt fa-sm" style="color: #097E71; opacity: 0.5;"></i> Demo Flag Tracker
				</h1>	
				<blockquote>
					<section>
					<i class="fa fa-angle-double-right fa-sm" style="color: #7E0929; opacity: 0.5;"></i>
					The flag tracker is just an example of many <a href="'.tmip_free_signup_page_url.'" target="_blank">tracker styles</a> available. All visitor tracker styles capture exactly the same information about your website traffic. All data captured can be accessed via your own '.tmip_service_Dname.' account. This flag tracker gets reset periodically for data rotation.
					Premium subscribers can enable an invisible tracker option to track the computer IPs and IDs anonymously.
					<br><br>
					</section>
				</blockquote>';
		$output .='</div>';
	}
	echo $output;
}
function tmip_unify_new_lines($string) {
	if ($string) {
		$string=str_replace("\r\n", "\n", $string);
		$string=str_replace("\n\r", "\n", $string);
		$string=str_replace("\r", "\n", $string);
	}
	return $string;
}
function tmip_rate_review_section() {
		tmip_load_css();

		$r=array('heart'); 					$thankYouIcon=$r[array_rand($r)]; // 'dove','child'
		$r=array('hands-helping','leaf'); 	$helpNRateSec=$r[array_rand($r)];
		
		$output='
			<h2 class="tmip_sec_title">
			<i class="fa fa-'.$helpNRateSec.' fa-lg" style="color: #02970C; opacity: 0.8;"></i> Make '.tmip_service_Nname.' work better for you</h2>
			<div class="ratehelp">
				<i class="fa fa-star fa-1x tmip_outline-icon1" style="color: #FFFF00; opacity: 1.0;"></i>
				<b>Ignite the Difference with a 5-Star rating.</b>
				<blockquote>
					<p>Your influence is <u>power</u>.</p>
					<p>Since 2008, we\'ve sculpted '.tmip_service_Nname.' to amplify <b>your</b> experience. Your 5-star rating isn\'t just feedback; it\'s a heartfelt "Thank You!" that sparks our drive and guides others toward a stellar experience.</p>
					<p>Positive reviews fuel our commitment to your satisfaction. If '.tmip_service_Nname.' has made a difference for you, a swift 5-star vote empowers your influence.</p>
				<p><b>Thank you</b> for shaping our path with <b>your</b> enthusiasm <i class="fa fa-'.$thankYouIcon.' fa-lg" style="color: #AA0000; opacity: 0.8;"></i></p>
				</blockquote>
				<div style="width:100%; text-align:center;">
				<button type="button" class="tmip_submit_button1" style="outline-color: transparent;" onclick="window.open(\'https://wordpress.org/support/plugin/tracemyip-visitor-analytics-ip-tracking-control/reviews/?rate=5#new-post\')"> Click to Rate '.tmip_service_Nname.'</button>
				</div>
			</div>
	';
	return $output;
}
function tmip_extract_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}
function tmip_extract_url_vars($url){
	$output=array();
	if ($url) {
		$parts=parse_url($url);
		if (isset($parts['query'])) {
			$query=$parts['query'];
			parse_str($query,$array);	 /// rawurlencode
			if (is_array($array)) {
				foreach ($array as $k=>$v) {
					$output[$k]=str_replace(' ','+',$v);
				}
			}
		}
	}
	return $output;
}
function tmip_disable_content_checks($var=0) {
	$v='autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"';
	return ' '.$v;
}
function tmip_sanitize_rebuild_page_tracker_code($script) {
	$output=NULL;
	$trkSTR='/'.tmip_trk_path_str_array[0].'/';
	$script=stripslashes($script);
	$str=tmip_extract_string_between($script,'src=\'//',"' ");
	$str=tmip_unify_new_lines(trim($str));
	$str=tmip_remove_tabs_new_lines($str);
	$r=explode($trkSTR.'lkh.php?c',$str,2);$host=$r[0];
	$v=tmip_extract_url_vars('http://www.domain.com/var'.$str);
	if ($host and is_array($v) and array_filter($v)) {
		$output='<script type="text/javascript">
		var tP_lanPTl=""+document.title;tP_lanPTl=tP_lanPTl.substring(0,180);
		tP_lanPTl=escape(tP_lanPTl);var tP_lanPrl=""+location.toString();
		tP_lanPrl=tP_lanPrl.substring(0,500);tP_lanPrl=escape(tP_lanPrl);
		var tP_refRrl=""+document.referrer;tP_refRrl=tP_refRrl.substring(0,500);
		tP_refRrl=escape(tP_refRrl);
		document.writeln("<script src=\'//'.
			$host.$trkSTR.'lkh.php?c='.$v['c'].'&s='.$v['s'].'&l='.$v['l'].'&p='.
			$v['p'].'&d='.$v['d'].'&i='.$v['i'].'&x='.$v['x'].'&prefr='.
			$v['prefr'].'&lpTT='.$v['lpTT'].'&lpUL='.$v['lpUL'].'&pVer='.
			$v['pVer'].'\' type=\'text/javascript\'><\/script>");</script>';
	}
	$output=tmip_remove_tabs_new_lines($output,2);
	$output=tmip_unify_new_lines($output);
	return $output;
}
// wp_head, wp_footer, wp_body_open, admin_head, admin_footer, login_head, login_footer
function tmip_embed_body_html($assoc_array) {
	if ($assoc_array and is_array($assoc_array)) {
		foreach ($assoc_array as $key=>$value) {
			$act='wp_'; // admin_, login_
			if ($key=='header') {
				$act .='head'; // [body_open] does not show on some templates, including wordpress.com hosted accounts.
			} elseif ($key=='header_async') {
				$act .='head';
			} elseif ($key=='footer') {
				$act .='footer';
			} else {
				$act .='footer';
			}
			$priority=('wp_footer'===$act) ? 40 : 10;
			add_action($act,$value,$priority);
		}
	}
}
?>