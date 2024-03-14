<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/*
*Getting Minify Require Files from Library
*/
require_once (AEH_DIR. 'inc/lib-files.php');
use MatthiasMullie\Minify\AEH as Minify;

/*
* Declaring Class
*/

class AEH_Minify {
	public $settings = array();
	public $aeh_ignore = array();
	public $pb_keywords = array();
	public $minify_settings = array();
	public $inline_css = array();
	public $aeh_cache_update_time = null;
	public $site_home = null;
	public $domain = null;
	public $home_abs_path = ABSPATH;
	public $cachepath = null;
	public $inddir = null;
	public $cachedir =  null;
	public $cachedirurl = null;
	public $aeh_debug = null;

  public function __construct() {
		$this->aeh_debug = true;
		$this->cachepath = $this->aeh_cachepath();
		$this->inddir = $this->cachepath['inddir'];
		$this->cachedir =  $this->cachepath['cachedir'];
		$this->cachedirurl = $this->cachepath['cachedirurl'];
		$this->site_home = site_url();
		$this->domain = trim(str_ireplace(array('http://', 'https://'), '', trim($this->site_home, '/')));
    $this->settings = AEH_Settings::get_instance();
		$this->aeh_ignore = $this->settings->expires_headers_minify_default_excludes;
		$this->pb_keywords = $this->settings->expires_headers_minify_pb_keywords;
		$this->minify_settings = get_option('aeh_expires_headers_minify_settings',$this->settings->init_minify_default());
		if(!empty($this->minify_settings['escape_minify'])){
			$escape_string = preg_replace('/\s+/', '', $this->minify_settings['escape_minify']);
			$escape_string = rtrim($escape_string,',');
			$escape_array = explode (',', $escape_string);
			if(!empty($escape_array) && is_array($escape_array)){
				$this->aeh_ignore = array_unique (array_merge ($this->aeh_ignore, $escape_array));
			}
		}
		$this->aeh_cache_update_time = get_option('aeh-last-cache-update', '0');
    if(is_admin()) {
      add_action('upgrader_process_complete',array( $this, 'aeh_purge_minify'));
      add_action('after_switch_theme', array( $this,'aeh_purge_minify'));
      add_action('deactivated_plugin',array( $this,'aeh_purge_minify'));
      add_action('activated_plugin',array( $this,'aeh_purge_minify'));
      add_action('admin_init', array( $this,'aeh_purge_minify_on_update'),1);
		add_action( 'admin_bar_menu',array( $this, 'aeh_admin_minify_purge_link'), 100 );
    }else{
      add_action('setup_theme', array( $this,'aeh_minification') );
    }
  }

	/* exclude ceratin data operations mostly associated with wp and wc */
	public function aeh_ignore_operations() {
		if(isset($_SERVER['REQUEST_URI']) && stripos($_SERVER['REQUEST_URI'], '/wp-admin/') !== false) {
			return true;
		}
		if ((function_exists('is_checkout') && is_checkout() === true) || is_feed() || is_admin()  || is_preview() || is_customize_preview() || wp_doing_ajax()) {
			return true;
		}
		if(is_array($_GET)) {
			foreach ($_GET as $k=>$v) {
				if(is_string($v) && is_string($k)) {
					if(isset($this->pb_keywords) && !empty($this->pb_keywords) && is_array($this->pb_keywords)){
						foreach($this->pb_keywords as $pb_keyword){
							if(stripos($k, $pb_keyword) !== false || stripos($v, $pb_keyword) !== false) {
								return true;
							}
						}
					}
				}
			}
		}
		return false;
	}

	/* creating buffering before compression of html */
	public function aeh_min_html_compression_start(){
		if ($this->aeh_ignore_operations()) {
			return;
		}
		ob_start(array($this,'aeh_min_html_compression_finish'));
	}

	/* Uses third party solution for html minification */
	public function aeh_min_html_compression_finish($html){
		return Mrclay_Minify_HTML::minify($html);
	}

	/* creating admin toolbar link to purge minify and external cache */
	public function aeh_admin_minify_purge_link(){
		if(current_user_can('manage_options')) {
			global $wp_admin_bar;
			$wp_admin_bar->add_node(array(
				'id'    => 'aeh',
				'title' => '<span class="ab-icon"></span><span class="ab-label clear-browser-cache">AEH Purge Minify</span>',
				'href'  => wp_nonce_url( add_query_arg('_aehcache', 'clear'), 'aeh_purge_nonce')
			));
		}
	}

	/* frontend minification hooks at work */
  public function aeh_minification(){
		if( current_user_can('editor') || current_user_can('administrator') ) {
			if(isset($this->minify_settings['escape_admin']) && !empty($this->minify_settings['escape_admin'])){
				return;
			}
		}
  	if(!$this->aeh_ignore_operations()) {
		$this->aeh_init_default_expire_headers();
		if(isset($this->minify_settings['process_css'])){
			add_action('wp_print_styles',array( $this, 'aeh_merge_min_css_header'), PHP_INT_MAX );
			add_action('wp_print_footer_scripts',array( $this, 'aeh_merge_min_css_footer'), 9.99999999 );
		}
		if(isset($this->minify_settings['min_html'])){
			add_action('template_redirect', array( $this, 'aeh_min_html_compression_start'), PHP_INT_MAX);
  		}
		add_filter( 'style_loader_tag', array( $this,'aeh_min_async_css'), 10, 3 );
  	}
  }

  public function aeh_init_default_expire_headers(){
	if(!get_option('aeh_expires_headers_settings')){
		$aeh_settings = AEH_Settings::get_instance();
		$defaults= $aeh_settings->init_general_defaults();
		update_option('aeh_expires_headers_settings',$defaults);
		AEH_Pro::get_instance()->main()->write_to_htaccess();
	}
  }

	public function aeh_min_async_css($tag, $handle, $src) {
		$tag = trim($tag);
		if (stripos($src, '?ver') !== false) {
			$srcf = stristr($src, '?ver', true);
			$tag = str_ireplace($src, $srcf, $tag);
			$src = $srcf;
		}
		if($this->aeh_ignore_operations()) {
			return $tag;
		}
		if (!isset($this->minify_settings['async_css'])) {
			return $tag;
		}
		if ($this->aeh_is_local_domain($src) !== true) {
			return $tag;
		}
		if (stripos($tag, PHP_EOL) !== false || stripos($tag, 'navigator.userAgent.match') !== false) {
			return $tag;
		}
		if(stripos($_SERVER["SCRIPT_NAME"], strrchr(wp_login_url(), '/')) !== false){
			return $tag;
		}
		if (stripos($tag, 'defer') === false && stripos($tag, 'async') === false) {
			$cssasync = str_replace( 'rel=\'stylesheet\' ','rel=\'preload\' as=\'style\' onload=\'this.rel="stylesheet"\' ',$tag);
			array_filter($this->aeh_ignore, function ($var) { return (stripos($var, '/cache/') === false); });
			if(isset($this->minify_settings['async_css'])) {
				if(count($this->aeh_ignore) > 0 && $this->aeh_array_check($src, $this->aeh_ignore)) {
					return $tag;
				} else {
					return $cssasync;
				}
			}
		}
		return $tag;
	}

	public function aeh_get_styles(){
		global $wp_styles;
    if(!is_object($wp_styles)) {
			return false;
		}
    $styles = wp_clone($wp_styles);
		return $styles;
	}

  public function aeh_merge_min_css_header(){
		global $wp_styles;
    $styles = $this->aeh_get_styles();
		if($styles == false){
			return false;
		}
    $styles->all_deps($styles->queue);
    $done = $styles->done;
    $google_fonts = array();
    $aeh_min_source = array();
		$aeh_no_dup = array();
		foreach( $styles->to_do as $handle):
	  	$conditional = NULL;
	    if(isset($wp_styles->registered[$handle]->extra["conditional"])) {
	  		$conditional = $wp_styles->registered[$handle]->extra["conditional"];
	  	}
	  	$type_of_media = isset($wp_styles->registered[$handle]->args) ? $wp_styles->registered[$handle]->args : 'all';
	  	if ($type_of_media == 'screen' || $type_of_media == 'screen, print' || empty($type_of_media) || is_null($type_of_media) || $type_of_media == false) { $type_of_media = 'all'; }
	  	$mediatype = $type_of_media;
	  	$aeh_handle_url = $this->aeh_get_handle_url($wp_styles->registered[$handle]->src);
	  	if( empty($aeh_handle_url)) {
	  		continue;
	  	}
	  	if(!empty($aeh_handle_url)) {
	  		$key = hash('sha1', $aeh_handle_url);
	  		if (isset($aeh_no_dup[$key])) {
					$done = array_merge($done, array($handle));
					continue;
				} else {
					$aeh_no_dup[$key] = $handle;
				}
	  	}
	  	$aeh_min_source[$handle] = array('handle'=>$handle, 'url'=>$aeh_handle_url, 'conditional'=>$conditional, 'mediatype'=>$mediatype);
	  	if (stripos($aeh_handle_url, 'fonts.googleapis.com') !== false) {
		  	if(isset($this->minify_settings['inline_gfonts'])) {
		  		$google_fonts[$handle] = $aeh_handle_url;
		  	}else {
		  		wp_enqueue_style($handle);
		  	}
		  	continue;
	  	}
  	endforeach;
	  if(isset($this->minify_settings['inline_gfonts']) && count($google_fonts) > 0){
	  	$done = $this->aeh_inline_gfonts($google_fonts,$done,'processed-header');
	  }
		$aeh_header_urls = $this->aeh_fecth_min_urls($done,$styles,$google_fonts,$aeh_min_source);
		$done = $this->aeh_process_min_urls($aeh_header_urls,'processed-header',$done);
	  $wp_styles->done = $done;
  }

	public function aeh_merge_min_css_footer() {
		global $wp_styles;
		$styles = $this->aeh_get_styles();
		if($styles == false){
			return false;
		}
  	$styles->all_deps($styles->queue);
  	$done = $styles->done;
  	$google_fonts = array();
		$aeh_min_source = array();
		$aeh_no_dup = array();
	  foreach( $styles->to_do as $handle ) :
	  	$conditional = NULL;
			if(isset($wp_styles->registered[$handle]->extra["conditional"])) {
	  		$conditional = $wp_styles->registered[$handle]->extra["conditional"];
	  	}
	  	$type_of_media = isset($wp_styles->registered[$handle]->args) ? $wp_styles->registered[$handle]->args : 'all';
	  	if ($type_of_media == 'screen' || $type_of_media == 'screen, print' || empty($type_of_media) || is_null($type_of_media) || $type_of_media == false) {
				$type_of_media = 'all';
			}
	  	$mediatype = $type_of_media;
	  	$aeh_handle_url = $this->aeh_get_handle_url($wp_styles->registered[$handle]->src);
	  	if( empty($aeh_handle_url)) {
	  		continue;
	  	}
	  	if(!empty($aeh_handle_url)) {
	  		$key = hash('sha1', $aeh_handle_url);
	  		if (isset($aeh_no_dup[$key])) {
					 $done = array_merge($done, array($handle));
					 continue;
				 } else {
					  $aeh_no_dup[$key] = $handle;
				 }
	  	}
			$aeh_min_source[$handle] = array('handle'=>$handle, 'url'=>$aeh_handle_url, 'conditional'=>$conditional, 'mediatype'=>$mediatype);
	  	if (stripos($aeh_handle_url, 'fonts.googleapis.com') !== false) {
	  		wp_dequeue_style($handle);
	  		if(isset($this->minify_settings['inline_gfonts'])) {
	  			$google_fonts[$handle] = $aeh_handle_url;
	  		} else {
	  			wp_enqueue_style($handle);
	  		}
	  	} else {
	  		wp_dequeue_style($handle);
				wp_enqueue_style($handle);
	  	}
	  endforeach;
	  if(count($google_fonts) > 0 || (isset($this->minify_settings['inline_gfonts']) && count($google_fonts) > 0)) {
			$done = $this->aeh_inline_gfonts($google_fonts,$done,'processed-footer');
	  }
		$aeh_footer_urls = $this->aeh_fecth_min_urls($done,$styles,$google_fonts,$aeh_min_source);
		$done = $this->aeh_process_min_urls($aeh_footer_urls,'processed-footer',$done);
		$wp_styles->done = $done;
  }

	public function aeh_process_min_urls($aeh_header_urls,$title,$done,$type = 'css'){
	  global $wp_styles,$wp_scripts;
	  for($i=0,$l=count($aeh_header_urls);$i<$l;$i++){
			if(!isset($aeh_header_urls[$i]['handle'])) {
			  if($type == 'css'){
				  $aeh_inline_css_combine = array();
				  foreach($aeh_header_urls[$i]['handles'] as $handle_url){
						if(isset($this->inline_css[$handle_url]) && !empty($this->inline_css[$handle_url])) {
						  $aeh_inline_css_combine[] = $this->inline_css[$handle_url];
						}
				  }
				  $aeh_inline_css_combine_hash = md5(implode('',$aeh_inline_css_combine));
				  $aeh_hash = $title.'-'.hash('sha1',implode('',$aeh_header_urls[$i]['handles']).$aeh_inline_css_combine_hash);
			  }else{
				$aeh_hash = $title.'-'.hash('sha1',implode('',$aeh_header_urls[$i]['handles']));
			  }
			  $aeh_cache_file = $this->cachedir.$this->aeh_slash().$aeh_hash.'.min.'.$type;
			  $aeh_cache_file_url = $this->aeh_check_ssl($this->cachedirurl.'/'.$aeh_hash.'.min.'.$type);
			  clearstatcache();
			  if (!file_exists($aeh_cache_file)) {
					$log = '';
					$code = '';
					foreach($aeh_header_urls[$i]['handles'] as $handle) :
					  if($type == 'css'){
							$global_type = 'styles';
					  }else{
							$global_type = 'scripts';
			      }
					  if(!empty(${'wp_'.$global_type}->registered[$handle]->src)) {
							$aeh_handle_url = $this->aeh_get_handle_url(${'wp_'.$global_type}->registered[$handle]->src);
							if( empty($aeh_handle_url)) {
							  continue;
							}
							$aeh_json_data = $this->aeh_process_url($aeh_handle_url,$type,$handle);
							$res = json_decode($aeh_json_data, true);
							if($res['status'] != true) {
							  $log.= $res['log'];
							  continue;
							}
							if($type == 'js'){
				  				if (!empty( $wp_scripts->registered[$handle]->extra)) {
				  					if (!empty( $wp_scripts->registered[$handle]->extra['before'])){
				  						$code.= PHP_EOL . $this->aeh_try_catch_wrap(implode(PHP_EOL, $wp_scripts->registered[$handle]->extra['before']));
				  					}
				  				}
				  				$code.= "/* $aeh_handle_url */ ". PHP_EOL . $this->aeh_try_catch_wrap($res['code']);
							}else{
								$code.= $res['code'];
							}
							$log.= $res['log'];
					    if($type == 'js'){
								if (!empty( $wp_scripts->registered[$handle]->extra)) {
									if (!empty( $wp_scripts->registered[$handle]->extra['after'])){
										$code.= PHP_EOL . $this->aeh_try_catch_wrap(implode(PHP_EOL, $wp_scripts->registered[$handle]->extra['after']));
									}
								}
							}else{
								if(isset($this->inline_css[$handle]) && !empty($this->inline_css[$handle])) {
								  $code.= $this->inline_css[$handle];
								}
							}
					  } else {
						  if($type == 'css'){
								wp_dequeue_style($handle);
								wp_enqueue_style($handle);
							}else {
								wp_dequeue_script($handle);
								wp_enqueue_script($handle);
							}
					  }
					endforeach;
					$log = "Cached on ".date('r').PHP_EOL.$log.PHP_EOL;
					if(!empty($code)) {
					  file_put_contents($aeh_cache_file.'.txt', $log);
						$this->aeh_set_file_permissions($aeh_cache_file.'.txt');
					  file_put_contents($aeh_cache_file, $code);
						$this->aeh_set_file_permissions($aeh_cache_file);
					  file_put_contents($aeh_cache_file.'.gz', gzencode(file_get_contents($aeh_cache_file), 9));
					  $this->aeh_set_file_permissions($aeh_cache_file.'.gz');
					  if(function_exists('brotli_compress')) {
						file_put_contents($aeh_cache_file.'.br', brotli_compress(file_get_contents($aeh_cache_file), 11));
						$this->aeh_set_file_permissions($aeh_cache_file.'.br');
					  }
					}
			  }
			  if(file_exists($aeh_cache_file) && filesize($aeh_cache_file) > 0) {
					if(filesize($aeh_cache_file) < 20000 && $aeh_header_urls[$i]['media'] != 'all'  && $type !== 'js') {
					  echo '<style id="aeh-'.$title.'-'.$i.'" media="'.$aeh_header_urls[$i]['media'].'">'.file_get_contents($aeh_cache_file).'</style>';
					} else {
					  if($type == 'css'){
							wp_enqueue_style('aeh-'.$title.'-'.$i, $aeh_cache_file_url, array(), null, $aeh_header_urls[$i]['media']);
					  }else{
							wp_register_script('aeh-'.$title.'-'.$i, $aeh_cache_file_url, array(), null, false);
							$data = array();
							foreach($aeh_header_urls[$i]['handles'] as $handle) {
								if(isset($wp_scripts->registered[$handle]->extra['data'])) {
									$data[] = $wp_scripts->registered[$handle]->extra['data'];
								}
							}
							if(count($data) > 0) {
								$wp_scripts->registered['aeh-'.$title.'-'.$i]->extra['data'] = implode(PHP_EOL, $data);
							}
							if(file_exists($aeh_cache_file) && (filesize($aeh_cache_file) > 0 || count($data) > 0)) {
								wp_enqueue_script('aeh-'.$title.'-'.$i);
							} else {
								echo "<!-- Something went wrong with file saving - $aeh_cache_file -->";
							}
					  }
					}
			  } else {
					echo "<!-- Something went wrong with file saving - $aeh_cache_file -->";
			  }
			} else {
				if($type == 'css'){
					wp_dequeue_style($aeh_header_urls[$i]['handle']);
					wp_enqueue_style($aeh_header_urls[$i]['handle']);
				}else {
					wp_dequeue_script($aeh_header_urls[$i]['handle']);
					wp_enqueue_script($aeh_header_urls[$i]['handle']);
				}
			}
			if(isset($aeh_header_urls[$i]['handles'])){
				$done = array_merge($done, $aeh_header_urls[$i]['handles']);
			}
	  }
		return $done;
	}

	/* inline google fonts */
	public function aeh_inline_gfonts($google_fonts,$done,$string){
	  $aeh_google_fonts = array();
	  foreach ($google_fonts as $a) {
	    if(!empty($a)) {
	      $aeh_google_fonts[] = $a;
	    }
	  }
	  if(count($aeh_google_fonts) > 0) {
			$f = 0;
			$log ='';
	    foreach($aeh_google_fonts as $google_font_url) {
				$f++;
	      if($this->minify_settings['inline_gfonts'] == true) {
	        $aeh_json_data = $this->aeh_process_url($google_font_url,'css');
	        $res = json_decode($aeh_json_data, true);
	        if($res['status'] != true) {
	          $log.= $res['log'];
	          continue;
	        }
	        if($res['code'] !== false) {
	          $res['code'] = str_ireplace('font-style:normal;', 'font-display:block;font-style:normal;', $res['code']);
	          echo '<style type="text/css" media="all">'.$res['code'].'</style>'.PHP_EOL;
						echo '<!-- Inline google font -->'.PHP_EOL;
	        } else {
	          echo "<!-- Inline google font operation failed for  $google_font_url -->\n";
	        }
	      } else {
	        wp_enqueue_style($string.'-aeh-fonts-'.$aeh_file_url, $google_font_url, array(), null, 'all');
	      }
	    }
	  }
	  foreach ($google_fonts as $h=>$a) {
	    $done = array_merge($done, array($h));
	  }
	  return $done;
	}

	/* getting file url needs to processed in proper array */
	public function aeh_fecth_min_urls($done,$styles,$google_fonts,$aeh_min_source){
		global $wp_styles;
	  $aeh_header_urls = array();
	  foreach( $styles->to_do as $handle ) :
	    if(isset($google_fonts[$handle])) {
				continue;
			}
	    if(empty($wp_styles->registered[$handle]->src)) {
				continue;
			}
	    if ($this->aeh_array_check($handle, $done)) {
				continue;
			}
	    if (!isset($aeh_min_source[$handle])) {
				continue;
			}
	    $aeh_handle_url = $aeh_min_source[$handle]['url'];
	    $conditional = $aeh_min_source[$handle]['conditional'];
	    $mediatype = $aeh_min_source[$handle]['mediatype'];
	    if ( (!$this->aeh_array_check($aeh_handle_url,$this->aeh_ignore) && !isset($conditional) && $this->aeh_local_url_check($aeh_handle_url)) || empty($aeh_handle_url)){
	      if(isset($wp_styles->registered[$handle]->extra['after']) && is_array($wp_styles->registered[$handle]->extra['after'])) {
	        $this->inline_css[$handle] = $this->aeh_minify_inline_css(implode('', $wp_styles->registered[$handle]->extra['after']));
	        $wp_styles->registered[$handle]->extra['after'] = null;
	      }
	      if(isset($aeh_header_urls[count($aeh_header_urls)-1]['handle']) || count($aeh_header_urls) == 0 || $aeh_header_urls[count($aeh_header_urls)-1]['media'] != $mediatype) {
	        array_push($aeh_header_urls, array('handles'=>array(), 'media'=>$mediatype));
	      }
	      array_push($aeh_header_urls[count($aeh_header_urls)-1]['handles'], $handle);
	    } else {
	      array_push($aeh_header_urls, array('handle'=>$handle));
	    }
	  endforeach;
	  return $aeh_header_urls;
	}

	public function aeh_process_url($url,$aeh_cache_file_type,$handle = null){
	  $tran_file_key = $aeh_cache_file_type.'-'.hash('sha1', $url).'.'.$aeh_cache_file_type;
	  $aeh_json_data = false;
	  $aeh_json_data = $this->aeh_get_json_data($tran_file_key);
	  if ( $aeh_json_data === false) {
	    $aeh_json_data = $this->aeh_get_processed_json_data($url,$aeh_cache_file_type,$handle);
	    $this->aeh_set_ind_file($tran_file_key, $aeh_json_data);
	  }
	  return $aeh_json_data;
	}
  function aeh_try_catch_wrap($js) {
  	return 'try{'.PHP_EOL . $js . PHP_EOL . '}' . PHP_EOL . 'catch(e){console.error("An error has occurred: "+e.stack);}'.PHP_EOL;
  }

  public function aeh_get_processed_json_data($aeh_handle_url,$type, $handle){
    if(is_null($aeh_handle_url) || empty($aeh_handle_url) || !in_array($type, array('js', 'css'))) {
			return false;
		}
  	if($this->aeh_check_os_windows() === false) {
	  	if (stripos($aeh_handle_url, $this->domain) !== false) {
	  		$aeh_file_url = str_ireplace(rtrim($this->site_home, '/'), rtrim($this->home_abs_path, '/'), $aeh_handle_url);
				$response = $this->aeh_get_json_response($aeh_handle_url,$type,false,$aeh_file_url);
				if($response){
					return $response;
				}
	  		$nhurl = str_ireplace(site_url(), home_url(), $aeh_handle_url);
	  		$aeh_file_url = str_ireplace(rtrim($this->site_home, '/'), rtrim($this->home_abs_path, '/'), $nhurl);
				$response = $this->aeh_get_json_response($aeh_handle_url,$type,false,$aeh_file_url);
				if($response){
					return $response;
				}
	  	}
		}
		$response = $this->aeh_get_json_response($aeh_handle_url,$type,true);
		if($response){
			return $response;
		}
  	if(stripos($aeh_handle_url, $this->domain) !== false && home_url() != site_url()) {
  		$nhurl = str_ireplace(site_url(), home_url(), $aeh_handle_url);
			$response = $this->aeh_get_json_response($nhurl,$type,true);
			if($response){
				return $response;
			}
  	}
		$aeh_log_url = str_ireplace(array(site_url(), home_url(), 'http://', 'https://'), '', $aeh_handle_url);
  	$log = $aeh_log_url.PHP_EOL;
  	$return = array('log'=>$log, 'code'=>'', 'status'=>false);
  	return json_encode($return);
  }

	public function aeh_get_json_response($aeh_handle_url, $type, $fetch = false, $aeh_src_url=null){
	  clearstatcache();
	  if($fetch){
	    $code = $this->aeh_content_download($aeh_handle_url);
	  }else{
	    $code = file_get_contents($aeh_src_url);
	  }
	  if(($code !== false) && !empty($code) && (strtolower(substr($code, 0, 9)) != "<!doctype") && (strtolower(substr($code, 0, 5)) != "<?php") && (stripos($code, "<?php") === false)) {
	    if($type == 'js') {
	      $code = $this->aeh_min_get_js($aeh_handle_url, $code );
	    } else {
	      $code = $this->aeh_min_get_css($aeh_handle_url, $code);
	    }
	    $aeh_log_url = str_ireplace(array(site_url(), home_url(), 'http://', 'https://'), '', $aeh_handle_url);
	    $log = $aeh_log_url.PHP_EOL;
	    $return = array('log'=>$log, 'code'=>$code, 'status'=>true);
	    return json_encode($return);
	  }
	  return false;
	}

	public function aeh_content_download($url) {
  	$uagent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2486.0 Safari/537.36 Edge/13.10586';
  	$response = wp_remote_get($url, array('user-agent'=>$uagent, 'timeout' => 7, 'httpversion' => '1.1', 'sslverify'=>false));
  	$res_code = wp_remote_retrieve_response_code($response);
  	if($res_code == '200') {
  		$data = wp_remote_retrieve_body($response);
  		if(strlen($data) > 1) {
  			return $data;
  		}
  	}
  	return false;
  }

  public function aeh_min_get_css($url, $css) {
		if(isset($this->minify_settings['min_css'])){
			$css_minification = true;
		}else{
			$css_minification = false;
		}
    $css = $this->aeh_min_remove_utf8_bom($css);
    if(!empty($url)) {
    	$matches = array();
			preg_match_all("/url\(\s*['\"]?(?!data:)(?!http)(?![\/'\"])(.+?)['\"]?\s*\)/ui", $css, $matches);
      foreach($matches[1] as $a) {
				$b = trim($a);
				if($b != $a) {
					$css = str_replace($a, $b, $css);
				}
			}
    	$css = preg_replace("/url\(\s*['\"]?(?!data:)(?!http)(?![\/'\"])(.+?)['\"]?\s*\)/ui", "url(".dirname($url)."/$1)", $css);
    }
    $css = str_ireplace('@charset "UTF-8";', '', $css);
    $aeh_cache_update_time = get_option('aeh-last-cache-update', '0');
    $css = preg_replace('/(.eot|.woff2|.woff|.ttf)+[?+](.+?)(\)|\'|\")/ui', "$1"."#".$aeh_cache_update_time."$3", $css);
    if($css_minification) {
    	$css = $this->aeh_minify_inline_css($css);
    } else {
    	$css = $this->aeh_replace_local_urls($css);
    }
    $css = trim($css);
    return $css;
  }

	public function aeh_is_local_domain($src) {
		$locations = array(home_url(), site_url(), network_home_url(), network_site_url());
		$result = false;
		foreach ($locations as $l) {
			$l = trim(trim(str_ireplace(array('http://', 'https://', 'www.'), '', trim($l)), '/'));
			if (stripos($src, $l) !== false && $result === false) { $result = true; }
		}
		return $result;
	}

  public function aeh_min_remove_utf8_bom($text) {
      $bom = pack('H*','EFBBBF');
      $text = preg_replace("/^$bom/ui", '', $text);
      return $text;
  }

  public function aeh_check_ssl($url) {
  	$url = ltrim(str_ireplace(array('http://', 'https://'), '', $url), '/');
    if ((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1)) || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) {
			$aeh_url_base = 'https://';
		} else {
			$aeh_url_base = 'http://';
		}
  	return $aeh_url_base.$url;
  }

  public function aeh_minify_inline_css($css) {
    $minifier = new Minify\CSS($css);
    $minifier->setMaxImportSize(15);
    $min = $minifier->minify();
    if($min !== false) {
			return $this->aeh_replace_local_urls($min);
		}
    return $this->aeh_replace_local_urls($css);
  }

  public function aeh_check_os_windows() {
  	if(defined('PHP_OS_FAMILY')) {
  		if(strtolower(PHP_OS_FAMILY) == 'windows') { return true; }
  	}
  	if(function_exists('php_uname')) {
  		$os = @php_uname('s');
  		if (stripos($os, 'Windows') !== false) {
  			return true;
  		}
  	}
  	return false;
  }

  public function aeh_local_url_check($aeh_handle_url) {
    if (substr($aeh_handle_url, 0, strlen($this->site_home)) === $this->site_home) {
			return true;
		}
    if (stripos($aeh_handle_url, $this->site_home) !== false) {
			return true;
		}
    if (isset($_SERVER['HTTP_HOST']) && stripos($aeh_handle_url, preg_replace('/:\d+$/', '', $_SERVER['HTTP_HOST'])) !== false) {
			return true;
		}
    if (isset($_SERVER['SERVER_NAME']) && stripos($aeh_handle_url, preg_replace('/:\d+$/', '', $_SERVER['SERVER_NAME'])) !== false) {
			return true;
		}
    if (isset($_SERVER['SERVER_ADDR']) && stripos($aeh_handle_url, preg_replace('/:\d+$/', '', $_SERVER['SERVER_ADDR'])) !== false) {
			return true;
		}
  	return false;
  }

  public function aeh_array_check($aeh_handle_url, $aeh_ignore){
  	$aeh_handle_url = str_ireplace(array('http://', 'https://'), '//', $aeh_handle_url);
  	$aeh_handle_url = strtok(urldecode(rawurldecode($aeh_handle_url)), '?');
  	if (!empty($aeh_handle_url) && is_array($aeh_ignore)) {
  		foreach ($aeh_ignore as $i) {
  			$i = str_ireplace(array('http://', 'https://'), '//', $i);
  			$i = strtok(urldecode(rawurldecode($i)), '?');
  			$i = trim(trim(trim(rtrim($i, '/')), '*'));
  			if (stripos($aeh_handle_url, $i) !== false) {
					return true;
				}
  		}
  	}
  	return false;
  }

  public function aeh_get_handle_url($src) {
    $aeh_handle_url = trim($src);
    if(empty($aeh_handle_url)) {
			return $aeh_handle_url;
		}
    $aeh_handle_url = str_ireplace(array('&#038;', '&amp;'), '&', $aeh_handle_url);
    if ((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1)) || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) {
			 $aeh_url_base = 'https://';
		 }else {
			 $aeh_url_base = 'http://';
		}
    $this->site_home= rtrim($this->site_home, '/');
    if (substr($aeh_handle_url, 0, 2) === "//") {
			$aeh_handle_url = $aeh_url_base.ltrim($aeh_handle_url, "/");
		}
    if (substr($aeh_handle_url, 0, 4) === "http" && stripos($aeh_handle_url, $this->domain) === false) {
			return $aeh_handle_url;
		}
    if (substr($aeh_handle_url, 0, 4) !== "http" && stripos($aeh_handle_url, $this->domain) !== false) {
			$aeh_handle_url = $this->site_home.'/'.ltrim($aeh_handle_url, "/");
		}
    $aeh_handle_url = str_ireplace('###', '://', str_ireplace('//', '/', str_ireplace('://', '###', $aeh_handle_url)));
    $proceed = 0;
		if(!empty($this->site_home)) {
    	$alt_wp_content = basename($this->site_home);
    	if(substr($aeh_handle_url, 0, strlen($alt_wp_content)) === $alt_wp_content) { $proceed = 1; }
    }
    if (substr($aeh_handle_url, 0, 12) === "/wp-includes" || substr($aeh_handle_url, 0, 9) === "/wp-admin" || substr($aeh_handle_url, 0, 11) === "/wp-content" || $proceed == 1) {
    	$aeh_handle_url = $this->site_home.'/'.ltrim($aeh_handle_url, "/");
		}
    $aeh_handle_url = $aeh_url_base.str_ireplace(array('http://', 'https://'), '', $aeh_handle_url);
    if (stripos($aeh_handle_url, '.js?v') !== false) {
			$aeh_handle_url = stristr($aeh_handle_url, '.js?v', true).'.js';
		}
    if (stripos($aeh_handle_url, '.css?v') !== false) {
			$aeh_handle_url = stristr($aeh_handle_url, '.css?v', true).'.css';
		}
    $aeh_handle_url = $this->aeh_replace_local_urls($aeh_handle_url);
    return $aeh_handle_url;
  }

  public function aeh_replace_local_urls($code) {
		if ((isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1)) || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) {
			$aeh_url_base = 'https://';
		}else {
			$aeh_url_base = 'http://';
		}
  	$code = str_ireplace(array('http://', 'https://'), $aeh_url_base, $code);
  	$code = str_ireplace($aeh_url_base.'www.w3.org', 'http://www.w3.org', $code);
  	return $code;
  }

  public function aeh_slash() {
  	if($this->aeh_check_os_windows() === false) {
  		$slash = '/';
  	} else {
  		$slash = '\\';
  	}
  	return $slash;
  }

  public function aeh_set_file_permissions($aeh_cache_file){
		$perms = 0644;
		@chmod($aeh_cache_file, $perms);
		clearstatcache();
		return true;
	}

  public function aeh_cachepath() {
		$plugin['basedir'] = rtrim(plugin_dir_path(dirname( __FILE__ )));
  	$plugin['baseurl'] = rtrim(plugin_dir_url(dirname( __FILE__ )));
	  $aeh_cache_update_time = get_option('aeh-last-cache-update', '0');
	  $pluginsdir  = str_ireplace('cache'.$this->aeh_slash().'cache', 'cache', $plugin['basedir'].$this->aeh_slash().'cache');
	  $pluginsurl  = str_ireplace('cache/cache', 'cache', $plugin['baseurl'].'cache');
	  $cachebase   = $pluginsdir.$this->aeh_slash().$aeh_cache_update_time;
	  $cachebaseurl  = $pluginsurl.'/'.$aeh_cache_update_time;
	  $this->cachedir    = $cachebase.$this->aeh_slash().'com';
	  $this->inddir      = $cachebase.$this->aeh_slash().'ind';
	  $this->cachedirurl = $cachebaseurl.'/com';
	  $dir_perms = 0755;
	  $dirs = array($cachebase, $this->cachedir, $this->inddir);
	  foreach ($dirs as $target) {
	  	if(!is_dir($target)) {
	  		if (@mkdir($target, $dir_perms, true)){
	  			if ($dir_perms != ($dir_perms & ~umask())){
	  				$folder_parts = explode( $this->aeh_slash(), substr($target, strlen(dirname($target)) + 1 ));
	  					for ($i = 1, $c = count($folder_parts ); $i <= $c; $i++){
	  					@chmod(dirname($target) . $this->aeh_slash() . implode( $this->aeh_slash(), array_slice( $folder_parts, 0, $i ) ), $dir_perms );
	  				}
	  			}
	  		} else {
	  			wp_mkdir_p($target);
	  		}
	  	}
	  }
  	return array('cachebase'=>$cachebase,'inddir'=>$this->inddir, 'cachedir'=>$this->cachedir, 'cachedirurl'=>$this->cachedirurl);
  }

  public function aeh_cache_increment() {
  	update_option('aeh-last-cache-update', time());
  }

  public function aeh_purge_all() {
  	$this->cachepath = $this->aeh_cachepath();
  	$this->inddir = $this->cachepath['inddir'];
  	$this->aeh_cache_increment();
  	if(is_dir($this->inddir)) {
			$this->aeh_rrmdir($this->inddir);
		}
		$this->aeh_purge_old();
  	return true;
  }

  public function aeh_purge_all_uninstall() {
  	$this->cachepath = $this->aeh_cachepath();
  	$cachebaseparent = dirname($this->cachepath['cachebase']);
  	if(is_dir($cachebaseparent)) {
			$this->aeh_rrmdir($cachebaseparent);
		}
  	return true;
  }

  public function aeh_purge_old() {
  	$this->cachepath = $this->aeh_cachepath();
  	$cachebaseparent = dirname($this->cachepath['cachebase']);
  	$aeh_cache_update_time = get_option('aeh-last-cache-update', '0');
  	$expires = time() - 86400 * 2;
  	if(is_dir($cachebaseparent) && is_writable(dirname($cachebaseparent))) {
  		if ($handle = opendir($cachebaseparent)) {
  			while (false !== ($d = readdir($handle))) {
  				if (strcmp($d, '.')==0 || strcmp($d, '..')==0) { continue; }
  				if($d != $aeh_cache_update_time && (is_numeric($d) && $d <= $expires)) {
  					$dir = $cachebaseparent.$this->aeh_slash().$d;
  					if(is_dir($dir)) {
  						$this->aeh_rrmdir($dir);
  						if(is_dir($dir)) { @rmdir($dir); }
  					}
  				}
  			}
  			closedir($handle);
  		}
  	}
  	return true;
  }

  public function aeh_purge_minify() {
  	if(current_user_can( 'manage_options')){
			$this->aeh_purge_all();
			$this->aeh_purge_others();
  	}
  }

  public function aeh_purge_minify_on_update() {
  	if((current_user_can( 'manage_options') && isset($_POST['aeh_min_save_options']))|| (isset($_GET['_aehcache']) && wp_verify_nonce($_GET['_wpnonce'], 'aeh_purge_nonce')) ){
		$this->aeh_purge_all();
		$this->aeh_purge_others();
		add_action( 'admin_notices', function (){
			echo  '<div class="notice notice-error is-dismissible"><p><b>All Minified Files Purged Successfully!</b></p></div>' ;
		} );
  	}
  }

  public function aeh_get_json_data($key) {
  	$this->cachepath = $this->aeh_cachepath();
  	$this->inddir = $this->cachepath['inddir'];
  	$aeh_file_url = $this->inddir.$this->aeh_slash().$key.'.transient';
  	clearstatcache();
  	if(file_exists($aeh_file_url)) {
  		return file_get_contents($aeh_file_url);
  	} else {
  		return false;
  	}
  }

  public function aeh_set_ind_file($key, $code) {
  	if(is_null($code) || empty($code)) { return false; }
  	$this->cachepath = $this->aeh_cachepath();
  	$this->inddir = $this->cachepath['inddir'];
  	$aeh_file_url = $this->inddir.$this->aeh_slash().$key.'.transient';
  	file_put_contents($aeh_file_url, $code);
  	$this->aeh_set_file_permissions($aeh_file_url);
  	return true;
  }

  public function aeh_rrmdir($path) {
  	clearstatcache();
  	if(is_dir($path)) {
  		$i = new DirectoryIterator($path);
  		foreach($i as $aeh_file_url){
  			if($aeh_file_url->isFile()){
					unlink($aeh_file_url->getRealPath());
  			} else if(!$aeh_file_url->isDot() && $aeh_file_url->isDir()){
  				$this->aeh_rrmdir($aeh_file_url->getRealPath());
  				if(is_dir($aeh_file_url->getRealPath())) {
						@rmdir($aeh_file_url->getRealPath()); }
  			}
  		}
  		if(is_dir($path)) {
				@rmdir($path);
			}
  	}
  }

  public function aeh_purge_others(){
		if(isset($this->settings->expires_headers_clear_all_cache) && is_array($this->settings->expires_headers_clear_all_cache)){
			foreach($this->settings->expires_headers_clear_all_cache as $key=>$value){
				if(function_exists($key) || class_exists($key)){
					if(is_array($value)){
						foreach($value as $v_key => $v_value){
							if(method_exists($v_key,$v_value)){
			           call_user_func($value);
			        }
						}
					}else{
						call_user_func($value);
					}
				}
			}
		}
  }
}
