<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('flra_options')){

class flra_options {
	public $_version = '1.0.8';
	public $_optionName = 'flra_options';
	public $_optionGroup = 'flra-options-group';
	public $_defaultOptions = array(
	    'flra_is_active' 	=>	'1',
	    'exclude' 	        =>	array(),
		'exclude_page'      =>  '',
		'show_only'         =>  array(),
		'show_only_page'    =>  '',
		'screen_w'			=>	'1140',
		'content_w'			=>	'1140',
		'show_on_mobile'	=>	0,
		'banner_left_w'		=>	118,
		'banner_left_h'		=>	400,
		'banner_right_w'	=>	118,
		'banner_right_h'	=>	400,
		'margin_l'			=>	10,
		'margin_r'			=>	10,
		'margin_t'			=>	50,
		'margin_t_scroll'	=>	0,
		'z_index'			=>	999,
		'html_code_l'		=>	'<a href="http://yourwebsite.com" target="_blank"><img src="https://placeholdit.imgix.net/~text?txtsize=30&txt=ADS%20118x400&w=118&h=400" alt="" /></a>',
		'html_code_r'		=>	'<a href="http://yourwebsite.com" target="_blank"><img src="https://placeholdit.imgix.net/~text?txtsize=30&txt=ADS%20118x400&w=118&h=400" alt="" /></a>',
	);

	function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_mysettings') );
		add_action( 'wp_enqueue_scripts', array($this, 'flra_scripts') );
		add_action('wp_footer', array($this, 'flra_footer' ));
	}
	function admin_menu() {
		add_options_page(
			__('Float Left Right Ads','devvn_flra'), 
			__('Float Left Right Ads','devvn_flra'),
			'manage_options',
			'float-left-right-ads',
			array(
				$this,
				'svl_flra_setting'
			)
		);
	}
	
	function register_mysettings() {
		register_setting( $this->_optionGroup, $this->_optionName );
	}

	function  svl_flra_setting() {
		include 'options-page.php';
	}
	
	function flra_scripts(){
		$flra_options = wp_parse_args(get_option($this->_optionName),$this->_defaultOptions);
		if($flra_options['flra_is_active'] == 1){
			if($flra_options['show_on_mobile'] == 0 && wp_is_mobile()) return;
	    	wp_register_script( 'flra-script', plugins_url('/float-left-right.js',__FILE__), array(), $this->_version, true );
	    	$array = array(
	    		'MainContentW' 		=> $flra_options['content_w'],
                'LeftBannerW' 		=> $flra_options['banner_left_w'],
                'RightBannerW' 		=> $flra_options['banner_right_w'],
                'LeftAdjust' 		=> $flra_options['margin_l'],
                'RightAdjust' 		=> $flra_options['margin_r'],
                'TopAdjust' 		=> $flra_options['margin_t'],
                'TopAdjustScroll' 	=> $flra_options['margin_t_scroll'],
	    	);
	    	wp_localize_script('flra-script', 'flra_array', $array);
		}
	}
	
	function flra_footer(){
		$flra_options = wp_parse_args(get_option($this->_optionName),$this->_defaultOptions);
		if($flra_options['flra_is_active'] == 1){
			if($flra_options['show_on_mobile'] == 0 && wp_is_mobile()) return;
			if(!$this->flra_check_show()) return;
			wp_enqueue_script('flra-script');
			?>
			<div id="divFLRARight" style="display: none;position: absolute;top: 0px;width:<?php echo $flra_options['banner_right_w']?>px;<?php echo ($flra_options['banner_right_h'])?'height:'.$flra_options['banner_right_h'].'px;':''?>overflow:hidden;z-index:<?php echo $flra_options['z_index']?>;"><?php echo html_entity_decode(do_shortcode($flra_options['html_code_r'])); ?></div>
			<div id="divFLRALeft" style="display: none;position: absolute;top: 0px;width:<?php echo $flra_options['banner_left_w']?>px;<?php echo ($flra_options['banner_left_h'])?'height:'.$flra_options['banner_left_h'].'px;':''?>overflow:hidden;z-index:<?php echo $flra_options['z_index']?>;"><?php echo html_entity_decode(do_shortcode($flra_options['html_code_l'])); ?></div>
			<?php
		}
	}

	function flra_check_show(){
        $flra_options = wp_parse_args(get_option($this->_optionName),$this->_defaultOptions);

        $show_only = isset($flra_options['show_only']) ? $flra_options['show_only'] : array();
        $show_only_page = isset($flra_options['show_only_page']) ? $flra_options['show_only_page'] : '';

        $exclude = isset($flra_options['exclude']) ? $flra_options['exclude'] : array();
        $exclude_page = isset($flra_options['exclude_page']) ? $flra_options['exclude_page'] : '';

        $show = false;

        if(empty($show_only) && !$show_only_page){
            $show = true;
        }

        $page = array_map('intval', array_filter(explode(',', $show_only_page)));

        if (
            (in_array('home', $show_only) && is_front_page())
            || (in_array('blog', $show_only) && is_home())
            || (in_array('any_single', $show_only) && is_single())
            || (in_array('any_page', $show_only) && is_page())
            || ($page && (is_page($page) || is_single($page)))
        ){
            $show = true;
        }

        if (in_array('home', $exclude) && is_front_page()) $show = false;
        if (in_array('blog', $exclude) && is_home()) $show = false;
        if (in_array('any_page', $exclude) && is_page()) $show = false;
        if (in_array('any_single', $exclude) && is_single()) $show = false;

        $page = array_map('intval', array_filter(explode(',', $exclude_page)));
        if ($page && (is_page($page) || is_single($page))) $show = false;

        return $show;
    }
}
new flra_options;
}