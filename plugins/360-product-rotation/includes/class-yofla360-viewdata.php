<?php

if (!defined("ABSPATH")) exit;

/**
 * Class YoFLA360ViewData
 *
 * Options and data on a 360 product view
 *
 */
class YoFLA360ViewData {

    public $iframe;          // embed using iframe or not
    public $iframe_styles;
    public $user_styles;
    public $id;
    public $width;           // px or %
	public $widthNum;        // int value
	public $height;          // px or %
	public $heightNum;       // int value
    public $isNext360;
	public $isNext360Cloud;
    public $name;
    public $src;
    public $y360_options;
    public $is_cache_enabled;
    public $ga_tracking_id ;
    public $ga_enabled;
    public $ga_category;
    public $config_url;
    public $theme_url;
    public $local_engine;
	public $autoHeight;

	private $_iframe_url;
    private $_rotatetool_js_src;
    private $_ga_label;
    private $_product_url;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->y360_options = get_option( 'yofla_360_options' ); //read options stored in WP options database
        $this->_init_defaults();
    }

    /**
     * Initializes the product view data based on the shortcode attributes
     *
     * @param $attributes
     */
    public function process_attributes($attributes)
    {
        //set iframe styles based on user settings
        if( isset( $attributes['name'] ) )
            $this->name = $attributes['name'];

        //set iframe styles based on user settings
        if( isset( $attributes['iframe_styles'] ) )
            $this->iframe_styles = $attributes['iframe_styles'];

        //set styles based on user settings
        if( isset( $attributes['styles'] ) )
            $this->user_styles = $attributes['styles'];

        //set ga_enabled based on user settings
        if( isset($attributes['ga_enabled']) )
            $this->ga_enabled = (filter_var($attributes['ga_enabled'],FILTER_VALIDATE_BOOLEAN)) ;

        //set ga_tracking_id based on user settings
        if( isset($attributes['ga_tracking_id']) )
            $this->ga_tracking_id = $attributes['ga_tracking_id'];

        //use iframe or not
        if( isset($attributes['iframe']) )
            $this->iframe = filter_var( $attributes['iframe'], FILTER_VALIDATE_BOOLEAN);

        //
        if( isset($attributes['ga_category']) )
            $this->ga_category = $attributes['ga_category'];

        //set google analytics label
        if( isset($attributes['ga_label']) )
            $this->_ga_label = $attributes['ga_label'];

        //set width, height
        if ( isset($attributes['width']) ){
			$this->width = YoFLA360()->Utils()->format_size_for_styles( $attributes['width'] );
			$this->widthNum = intval( $attributes['width'] );
		}

		if ( isset($attributes['height']) ){
			$this->height = YoFLA360()->Utils()->format_size_for_styles( $attributes['height'] );
			$this->heightNum = intval( $attributes['height'] );
		}

		if ( isset($attributes['auto-height']) ){
			$this->autoHeight = strtolower($attributes['auto-height']) == "true";
		}
		else{
			$this->autoHeight = false;
		}

		//set src parameter
        if ( isset($attributes['src']) )
            $this->set_src( sanitize_text_field($attributes['src']) );


        $this->isNext360 = YoFLA360()->Utils()->is_created_with_next360($this->src);
		$this->isNext360Cloud = YoFLA360()->Utils()->is_created_with_next360_cloud($this->src);
    }
    
    /**
     * Initializes the product view data based on get aparameters
     *
     */
    public function process_get_parameters()
    {
        //set ga_enabled based on user settings
        if( isset($_GET['ga_enabled']) )
            $this->ga_enabled = (filter_var($_GET['ga_enabled'],FILTER_VALIDATE_BOOLEAN)) ;

        //set ga_tracking_id based on user settings
        if( isset($_GET['ga_tracking_id']) )
            $this->ga_tracking_id = $_GET['ga_tracking_id'];

        //use iframe or not
        if( isset($_GET['iframe']) )
            $this->iframe = filter_var( $_GET['iframe'], FILTER_VALIDATE_BOOLEAN);

        //
        if( isset($_GET['ga_category']) )
            $this->ga_category = $_GET['ga_category'];

        //set google analytics label
        if( isset($_GET['ga_label']) )
            $this->_ga_label = $_GET['ga_label'];

        //set google analytics label
        if( isset($_GET['ga_label']) )
            $this->_ga_label = $_GET['ga_label'];
        
        //src parameter
        if( isset($_GET['src']) )
            $this->set_src(sanitize_text_field($_GET['src']));
    }

    /**
     * Returns the url of an iframe, if view is embedded as iframe
     *
     * @return mixed
     */
    public function get_iframe_url()
    {
        if(!isset($this->_iframe_url) || !$this->_iframe_url)
        {
            $this->_iframe_url =  YoFLA360()->Utils()->get_iframe_url($this);
        }
        return $this->_iframe_url;
    }

    /**
     * Returns url to product
     *
     * @return string
     */
    public function get_product_url()
    {
        if(!isset($this->_product_url) || !$this->_product_url)
        {
            $this->_product_url = YoFLA360()->Utils()->get_product_url($this);
        }

        return $this->_product_url;
    }

    /**
     * Returns the url of the rotatetool js engine
     *
     * @return string
     */
    public function get_rotatetool_js_src()
    {

        //rotatetool_js from local location, if presnt and enabled
        if ($this->local_engine) {
            $local_engine_path = YoFLA360()->Utils()->get_full_product_path( $this->src).'rotatetool.js';
            if(file_exists($local_engine_path)){
                return $this->get_product_url().'rotatetool.js';
            }
        }

        if(!empty($y360_options['rotatetooljs_url']) && filter_var($y360_options['rotatetooljs_url'], FILTER_VALIDATE_URL)){
            return $this->y360_options['rotatetooljs_url'];
        }

        if(!empty($this->y360_options['license_id'])){
			$firstTwoChars = strtolower( substr($this->y360_options['license_id'],0,2));
			if($firstTwoChars == 'yc'){
				return YOFLA_PLAYER_URL;
			}
			else{
				return YOFLA_PLAYER_URL.'?id='.$this->y360_options['license_id'];
			}
		}

        if (isset($_GET['license_id'])) {
            return YOFLA_PLAYER_URL . '?id=' . $_GET['license_id'];
        }

        return $this->_rotatetool_js_src;
    }



    /**
     * Styles used when embedding as a div (no iframe)
     *
     * @return string
     */
    public function get_styles()
    {
        $format = "width: %s; height: %s; %s";
        return sprintf($format, $this->width, $this->height, $this->user_styles);
    }


	/**
	 * Returns the global in settings set theme url, if set
	 */
    public function get_theme_url(){
		if(!empty($this->y360_options['theme_url'])){
			return $this->y360_options['theme_url'];
		}
		else{
			return '';
		}
	}

    /**
     * Returns google analytics label if set, otherwise a default label
     *
     * @return string
     */
    public function get_ga_label()
    {
        if ( empty( $this->_ga_label ) )
        {
            $format = "360 @ %s";
            return sprintf($format, $this->src);
        }
        else
        {
            return $this->_ga_label;
        }
    }


    /**
     * Sets the src parameter (as in the embed src parameter)
     *
     * @param $path
     */
    public function set_src($path)
    {
        $this->src = $path;

        if( !isset ( $this->id ) )
        {
            $this->id = 'yofla360_'.str_replace('/','_',trim(YoFLA360()->Utils()->get_relative_product_path($path),'/') );
        }

        //check if path valid
		$fullPath = YoFLA360()->Utils()->get_full_product_path($path);
        if(!file_exists($fullPath)){
			$this->error = 'Path does not exist!';
			return;
		}

        //Desktop Application Format
        if ( YoFLA360()->Utils()->is_created_with_desktop_application( $path ) )
        {
            $this->_product_url = YoFLA360()->Utils()->get_uploads_url().trim($this->src,'/').'/';
        }
        //URL
        elseif ( YoFLA360()->Utils()->is_url_path($path) )
        {
            $this->_product_url = YoFLA360()->Utils()->get_product_url($this);
        }
        else
        {
            $this->_product_url = '';
            // generate or get configFile parameter and themeUrl parameter
            $this->config_url = YoFLA360()->Utils()->get_config_url($this);
            $this->theme_url  = YoFLA360()->Utils()->get_theme_url($this);

        }
    }

    /**
     * Default settings for embedding a 360 view
     */
    private function _init_defaults()
    {
        $this->width   = '100%';
        $this->height  = '400px';
        $this->name    = '360 Product View';
        $this->iframe  = true;
        $this->iframe_styles  = 'max-width: 100%; border: 1px solid silver;';
        $this->user_styles    = 'border: 1px solid silver;';
        $this->ga_category    = 'YOFLA_360';
        $this->config_url     = '';
        $this->theme_url      = '';
        $this->local_engine   = false;

        if(!empty($this->y360_options['iframe_styles']))  $this->iframe_styles  = $this->y360_options['iframe_styles'];
        if(!empty($this->y360_options['ga_enabled']))     $this->ga_enabled     = $this->y360_options['ga_enabled'];
        if(!empty($this->y360_options['ga_tracking_id'])) $this->ga_tracking_id = $this->y360_options['ga_tracking_id'];
        if(!empty($this->y360_options['local_engine'])) $this->local_engine = $this->y360_options['local_engine'];

        //default cache setting
        if( current_user_can('editor') || current_user_can('administrator') )
        {
            $this->is_cache_enabled = false; //no chache for logged in users
        }
        else
        {
            $this->is_cache_enabled = true;
        }
        $this->_rotatetool_js_src = YOFLA_PLAYER_URL;

    }


}//class
