<?php

class Wideo
{   
    protected $options;

    protected $default = array(
    	//设置默认值
    	'width'=>'16',
    	'height'=>'9',
    	'logo'=> 'true', 
    	'logourl'=> WIDEO_URL.'/images/mini-logo.png', 
    	'theme'=>'#b7daff',
    	'volume'=>'0.7',
    	'lang'=>'zh-cn',
    	'live'=>'false',
    	'autoplay'=>'false',
    	'loop'=>'false',	
    	'screenshot'=>'false',
	    'hotkey'=>'true',
	    'preload'=>'auto',
	    'mutex'=> 'true', 	    
	    'sitename'=> '熊猫领地',
	    'siteurl'=> 'https://www.wibir.cn/',
    );

	public function __construct() {
		
        $this->options = get_option('wideo_setting');	
		add_action( 'admin_menu', array( $this, 'wideo_admin_menu' ) );//增加后台菜单
		add_action( 'admin_init', array( $this, 'wideo_setting_init'));//注册设置选项
		add_action( 'wp_enqueue_scripts', array( $this, 'wideo_scripts' ) );//加载脚本及样式
		add_shortcode( 'wideo', array( $this, 'wideo_shortcode' ) );//注册简码	
		add_filter( 'plugin_action_links', array($this,'wideo_plugin_action_link'), 10, 4);//增加设置链接
		add_action('admin_print_scripts', array($this,'wideo_quicktags'));//增加quicktag
        add_action('init', array( $this,'wideo_add_buttons'));//增加编辑器按钮

	}

    //增加后台菜单
	public function wideo_admin_menu(){

		add_menu_page('WIDEO 播放器','WIDEO 播放器','manage_options','wideo',array($this,'wideo_admin_setting'), WIDEO_URL . '/images/wideo-menu.png');
		add_submenu_page( 'wideo', '关于', '关于', 'manage_options', 'wideo-about', array($this,'wideo_about') );
	}

	public function wideo_admin_setting()
    {
        @require_once 'includes/wideo_setting.php';
       
    }

   
    public function wideo_about()
    {
        @require_once 'includes/wideo_about.php';
    }

    //增加设置选项
    public function wideo_setting_init()
    {    
    	register_setting('wideo_options_group', 'wideo_setting', array($this, 'validate'));
       
    }

    //文章更新校验
	public function validate($input) {

	$valid = array();
	$valid['width']          = sanitize_text_field($input['width']);
	$valid['height']         = sanitize_text_field($input['height']);
	$valid['logo']           = sanitize_text_field($input['logo']);
	$valid['logourl']        = sanitize_text_field($input['logourl']);
	$valid['theme']      = sanitize_text_field($input['theme']);
	$valid['volume']     = sanitize_text_field($input['volume']);
	$valid['lang']       = sanitize_text_field($input['lang']);
	$valid['live']       = sanitize_text_field($input['live']);
	$valid['autoplay']   = sanitize_text_field($input['autoplay']);
	$valid['loop']       = sanitize_text_field($input['loop']);
	$valid['screenshot'] = sanitize_text_field($input['screenshot']);
	$valid['hotkey']     = sanitize_text_field($input['hotkey']);
	$valid['preload']    = sanitize_text_field($input['preload']);
	$valid['mutex']      = sanitize_text_field($input['mutex']);


	if (strlen($valid['width']) == 0)         {	$valid['width'] = $this->options['width'];}
	if (strlen($valid['height']) == 0)        { $valid['height'] = $this->options['height'];}
	if (strlen($valid['logo']) == 0)          {	$valid['logo'] = $this->options['logo'];}
	if (strlen($valid['logourl']) == 0)       {	$valid['logourl'] = $this->options['logourl'];}
	if (strlen($valid['theme']) == 0)       {	$valid['theme'] = $this->default['theme'];}
	if (strlen($valid['volume']) == 0)      {	$valid['volume'] = $this->default['volume'];}
	if (strlen($valid['lang']) == 0)        {	$valid['lang'] = $this->default['lang'];}
	if (strlen($valid['live']) == 0)        {	$valid['live'] = $this->default['live'];}
	if (strlen($valid['autoplay']) == 0)    {	$valid['autoplay'] = $this->default['autoplay'];}
	if (strlen($valid['loop']) == 0)        {	$valid['loop'] = $this->default['loop'];}
	if (strlen($valid['screenshot']) == 0)  {	$valid['screenshot'] = $this->default['screenshot'];}
	if (strlen($valid['hotkey']) == 0)      {	$valid['hotkey'] = $this->default['hotkey'];}
	if (strlen($valid['preload']) == 0)     {	$valid['preload'] = $this->default['preload'];}
	if (strlen($valid['mutex']) == 0)       {	$valid['mutex'] = $this->default['mutex'];}
	if (strlen($valid['sitename']) == 0)    {	$valid['sitename'] = $this->default['sitename'];}
    if (strlen($valid['siteurl']) == 0)     {	$valid['siteurl'] = $this->default['siteurl'];}



	return $valid;
}


	//加载脚本及样式
    public function wideo_scripts() {
		
		global $post, $posts;
		foreach ( $posts as $post ) {
		if ( has_shortcode( $post->post_content, 'wideo' ) ) {
			
		wp_enqueue_script( 'wideo_flv', WIDEO_URL.'/DPlayer/flv.min.js',  WIDEO_URL, WIDEO_VERSION , false );
		wp_enqueue_script( 'wideo_hls', WIDEO_URL.'/DPlayer/hls.min.js',  WIDEO_URL, WIDEO_VERSION,  false );
        wp_enqueue_script( 'wideo_dp',  WIDEO_URL.'/DPlayer/DPlayer.min.js', array('jquery'), WIDEO_VERSION,  false );

		break;
		}
		}
 
	}
 
    
    //注册简码
    public function wideo_shortcode( $atts, $content=null ) {
	    if(!is_single() && !is_page()) return'';
		$width=$this->options['width'];
		$height=$this->options['height'];
		$autoplay=$this->options['autoplay'];
		$theme=$this->options['theme'];
		$loop=$this->options['loop'];
		$lang=$this->options['lang'];
		$screenshot=$this->options['screenshot'];
		$hotkey=$this->options['hotkey'];
		$preload=$this->options['preload'];
		$logo=($this->options['logo']=="true"?$this->options['logourl']:'');
		$volume=$this->options['volume'];
		$mutex=$this->options['mutex'];

		$sitename=$this->options['sitename'];
		$siteurl=$this->options['siteurl'];
        
        $rand=rand(100000,999999);

        $html='';
    	$html.='
    	<div class="wideo" style="position:relative;">
		<div id="wideo'.$rand.'" style="width:100%;height:100%"></div>
		</div>
				
		<script type="text/javascript">

			const dp'.$rand.' = new DPlayer({
		    container: document.getElementById("wideo'.$rand.'"),
		    autoplay: '.$autoplay.',
		    theme: "'.$theme.'",
		    loop: '.$loop.',
		    lang: "'.$lang.'",
		    screenshot: '.$screenshot.',
		    hotkey: '.$hotkey.',
		    preload: "'.$preload.'",
		    logo: "'.$logo.'",
		    volume: '.$volume.',
		    mutex: '.$mutex.',
		    video: {
		        url: "'.$content.'",
		        type: "auto"
		    },
		     contextmenu: [
			{
				text: "'.$sitename.'",
				link: "'.$siteurl.'"
			}
			],

		    });	';		

		$html.='$(function(){
		    var width=$(".wideo").width(); 
		    var height=width*'.$height.'/'.$width.';

		    $(".wideo").resize(function(){
			   $(".wideo").css("height",height);
		    });					                              
	　　		})';
		$html.='</script>';	

     
    return $html;
	
    }

	//增加设置链接
	public function wideo_plugin_action_link( $actions, $plugin_file, $plugin_data ) {
		if ( strpos( $plugin_file, 'wideo' ) !== false && is_plugin_active( $plugin_file ) ) {
			$_actions = array( 'option' => '<a href="' . WIDEO_ADMIN_URL . 'admin.php?page=wideo">设置</a>' );
			$actions  = array_merge( $_actions, $actions );
		}

		return $actions;
	}
    //增加quicktag
	public function wideo_quicktags()
	{
	    wp_enqueue_script('wideo_quicktags', WIDEO_URL . '/js/wideo_quicktags.js', array('quicktags'));
	}
    //增加编辑器按钮
	public function wideo_add_buttons() {
    if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
            return;
        }

	if ( get_user_option('rich_editing') == 'true') {

		add_filter("mce_external_plugins", array($this,'add_wideo_tinymce_plugin'), 5);
		add_filter('mce_buttons', array($this,'register_wideo_button'), 5);
	    }

	}

	public function add_wideo_tinymce_plugin($plugin_array) {
		$plugin_array['wideo'] = WIDEO_URL.'/js/wideo_editor_plugin.js';
		return $plugin_array;
	}

	public function register_wideo_button($buttons) {
		array_push($buttons, "separator", "wideo");
		return $buttons;
	}
    //插件激活
    public function activate() {
		$indate=time();
	    $this->default[indate]=$indate;
		update_option('wideo_setting', $this->default);
		flush_rewrite_rules();
	}
    //插件停止
    public function deactivate() {		
		delete_option('wideo_setting');
		flush_rewrite_rules();
	}	

	

	
}


?>