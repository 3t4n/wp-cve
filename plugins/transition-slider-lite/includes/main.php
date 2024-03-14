<?php

class TransitionSlider {

	public $PLUGIN_VERSION;
	public $PLUGIN_DIR_URL;
	public $PLUGIN_DIR_PATH;

	private static $instance = null;

	public static function get_instance() {
		if (null == self::$instance) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	protected function __construct() {
		$this->add_actions();
		register_activation_hook(TRANSITION_SLIDER_FILE, array( $this, 'activation_hook' ) );
	}

	public function activation_hook($network_wide) {
		delete_option( 'transitionslider_getting_started_dont_show' );
		add_option( 'transitionslider_activating', true );
	}

	public function enqueue_scripts() {
		wp_register_style( "transitionslider-css", $this->PLUGIN_DIR_URL."css/style.min.css" , array(), $this->PLUGIN_VERSION);
		wp_register_style( "transitionslider-swiper-css", $this->PLUGIN_DIR_URL."css/swiper.min.css" , array(), $this->PLUGIN_VERSION);
		wp_register_style( "transitionslider-pickr-css", $this->PLUGIN_DIR_URL."css/monolith.min.css" , array(), $this->PLUGIN_VERSION);
		wp_register_style( "transitionslider-select2-css", $this->PLUGIN_DIR_URL."css/select2.min.css" , array(), $this->PLUGIN_VERSION);
		wp_register_style( "transitionslider-fontselect-css", $this->PLUGIN_DIR_URL."css/fontselect.min.css" , array(), $this->PLUGIN_VERSION);
		wp_register_style( "transitionslider-fontawesome-css", "https://use.fontawesome.com/releases/v5.8.2/css/all.css" , array(), $this->PLUGIN_VERSION);

        wp_register_script("transitionslider-lib-three", $this->PLUGIN_DIR_URL."js/lib/three.min.js", array('jquery'), $this->PLUGIN_VERSION);
        wp_register_script("transitionslider-lib-swiper", $this->PLUGIN_DIR_URL."js/lib/swiper.min.js", array('jquery'), $this->PLUGIN_VERSION);
        wp_register_script('transitionslider-lib-color-pickr', $this->PLUGIN_DIR_URL.'js/lib/pickr.es5.min.js', array( 'jquery' ), $this->PLUGIN_VERSION, true);
        wp_register_script("transitionslider-lib-anime-js", $this->PLUGIN_DIR_URL."js/lib/anime.min.js", array('jquery'), $this->PLUGIN_VERSION);
        wp_register_script("transitionslider-lib-tipsy", $this->PLUGIN_DIR_URL."js/lib/jquery.tipsy.min.js", array('jquery'), $this->PLUGIN_VERSION);
        wp_register_script("transitionslider-lib-select2", $this->PLUGIN_DIR_URL."js/lib/select2.min.js", array('jquery'), $this->PLUGIN_VERSION);
		wp_register_script("transitionslider-lib-fontselect", $this->PLUGIN_DIR_URL."js/lib/jquery.fontselect.min.js", array('jquery'), $this->PLUGIN_VERSION);
        wp_register_script("transitionslider-lib-webfontloader", $this->PLUGIN_DIR_URL."js/lib/webfontloader.js", array('jquery'), $this->PLUGIN_VERSION);
		wp_register_script("transitionslider-lib-iconify", $this->PLUGIN_DIR_URL."js/lib/iconify.min.js", array('jquery'), $this->PLUGIN_VERSION);

		wp_register_script("transitionslider-embed", $this->PLUGIN_DIR_URL."js/embed.js", array('jquery'), $this->PLUGIN_VERSION);
        wp_register_script("transitionslider-build", $this->PLUGIN_DIR_URL."js/build/transitionSlider.min.js", array('jquery'), $this->PLUGIN_VERSION);
        wp_register_script("transitionslider-build-webgl", $this->PLUGIN_DIR_URL."js/build/transitionSlider.webgl.min.js", array('jquery'), $this->PLUGIN_VERSION);

	}

	public function admin_enqueue_scripts() {

		$this->enqueue_scripts();

	    wp_register_script("transitionslider-admin", $this->PLUGIN_DIR_URL."js/plugin_admin.js", array('transitionslider-layer-renderer', 'jquery', 'jquery-ui-tabs', 'jquery-ui-accordion', 'jquery-ui-resizable', 'jquery-ui-draggable'),$this->PLUGIN_VERSION);
	    wp_register_script("transitionslider-layer-renderer", $this->PLUGIN_DIR_URL."js/layer-renderer.js", array('jquery'),$this->PLUGIN_VERSION);
	    wp_register_script('transitionslider-sliders', $this->PLUGIN_DIR_URL."js/sliders.js", array('jquery'), $this->PLUGIN_VERSION);

	    wp_register_style('transitionslider-edit-slider-css', $this->PLUGIN_DIR_URL."css/transition-slider.min.css", array(), $this->PLUGIN_VERSION);
	}

	protected function get_translation_array() {
		return Array(
            'objectL10n' => array(
                'loading' => esc_html__('Loading...', 'transitionslider')

            ));
	}

	public function admin_link($links) {
		array_unshift($links, '<a href="' . get_admin_url() . 'options-general.php?page=sliders">Admin</a>');
		return $links;
	}


	public function admin_menu() {

		add_menu_page(
			esc_html__("Transition Slider", "stx"), 
			esc_html__("Transition Slider", "stx"), 
			'publish_posts', 
			'transition_slider_admin',
			array($this,'transition_slider_admin'), 
			"data:image/svg+xml,%3Csvg width='20' height='20' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Crect stroke='%23FFF' stroke-width='1.053' fill='%231D102A' x='.526' y='.526' width='18.947' height='18.947' rx='4.561'/%3E%3Cpath d='M4.21 6.316h5.264v1.5H7.646v5.868H6.012V7.816H4.211m11.226 3.688c-.015.585-.106.837-.319 1.165-.212.328-.509.58-.89.754-.38.174-.816.261-1.309.261-.137 0-.306-.01-.506-.028a5.333 5.333 0 01-.796-.158 5.94 5.94 0 01-1.033-.383v-1.76c.336.223.666.41.99.558.325.149.672.223 1.043.223.342 0 .564-.061.664-.184.101-.122.151-.237.151-.344 0-.194-.086-.357-.258-.49a3.59 3.59 0 00-.753-.411 5.815 5.815 0 01-.96-.541c-.274-.195-.5-.44-.673-.733-.175-.293-.262-.64-.262-1.04a2.1 2.1 0 01.264-1.05c.176-.312.444-.561.806-.748.361-.186.802-.28 1.323-.28.371 0 .714.04 1.029.117.315.078.575.165.78.262.204.096.345.172.421.228v1.683a5.383 5.383 0 00-.908-.565 2.38 2.38 0 00-1.072-.248c-.25 0-.43.051-.542.155a.501.501 0 000 .75c.112.104.312.23.6.379.51.259.92.492 1.227.699.307.207.547.446.722.717.174.27.277.428.261 1.012z' fill='%23FFF' fill-rule='nonzero'/%3E%3C/g%3E%3C/svg%3E" 
		);

		add_submenu_page(
			'transition_slider_admin',
			esc_html__("Sliders", "stx"),
			esc_html__("Sliders", "stx"),
		    'publish_posts',
		    'transition_slider_admin',
		    array($this,'transition_slider_admin')
		);

		add_submenu_page(
			'transition_slider_admin',
			esc_html__("Add new Slider", "stx"),
			esc_html__("Add new Slider", "stx"),
		    'publish_posts',
		    'transitionslider_add_new',
		    array($this,'transitionslider_add_new')
		);

		add_submenu_page(
			'transition_slider_admin',
			esc_html__("Go Pro", "stx"),
			'<span style="font-weight: 700; color: #00ff55"><span class="dashicons dashicons-unlock" style="font-size: 17px"></span>' . esc_html__("Go Pro", "stx") . '</span>', 
		    'publish_posts',
		    'transitionslider_go_pro',
		    array($this,'transitionslider_go_pro')
		);

		if (function_exists('register_block_type')) {

			register_block_type( 'transitionslider/embed', array(
				'attributes' => array(
					'id' => array(
						'type' => 'string',
					)
				),
			) );

			add_action( 'enqueue_block_assets', array($this,'enqueue_block_assets'));
			add_action( 'enqueue_block_editor_assets', array($this,'enqueue_block_editor_assets'));
		}

		function stx_display_menu_icon(){
            print '<style type="text/css">';
            print '#toplevel_page_transition_slider_admin .wp-menu-image img {';
            print ' padding: 7px 0 0 0 !important;';
            print '}';
            print '</style>';
        }

        add_action( 'admin_head', 'stx_display_menu_icon' );
	}

	public function transitionslider_add_new(){
		$_GET['action'] = "add_new";
		$this->transition_slider_admin();
	}
	public function transitionslider_go_pro(){
		$this->go_pro();
	}
	public function enqueue_block_assets(){

	}

	public function enqueue_block_editor_assets(){

		wp_enqueue_script("transitionslider-blocks-js", $this->PLUGIN_DIR_URL."js/blocks-editor.js", array( 'wp-editor', 'wp-blocks', 'wp-i18n', 'wp-element'), $this->PLUGIN_VERSION);

		$slider_ids = get_option('transitionslider_ids');

		$slider_names = array();

			foreach ($slider_ids as $id) {
				$_s = get_option('transitionslider_'.$id);
				array_push($slider_names, $_s["instanceName"]);
			}

		wp_localize_script( 'transitionslider-blocks-js', 'data', array(
                'slider_ids' => json_encode($slider_ids),
                'slider_names' => json_encode($slider_names)
            ) );

	}

	public function transition_slider_admin(){

		include_once( plugin_dir_path(__FILE__).'admin-actions.php' );

    }
	public function go_pro(){

		include_once( plugin_dir_path(__FILE__).'go-pro.php' );

    }
	public function init() {
		add_shortcode( 'transitionslider', array($this, 'on_shortcode') );

		if(get_option("transitionslider_activating")){

			delete_option("transitionslider_activating");

			wp_redirect( admin_url( 'admin.php?page=transition_slider_admin') );
			exit;

		}

	}

	public function plugins_loaded() {
		load_plugin_textdomain( 'transitionslider', false, dirname($this->my_plugin_basename()).'/lang/' );
	}

	protected function add_actions() {

		add_action('plugins_loaded', array($this, 'plugins_loaded') );

		add_action('init', array($this, 'init') );

		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), 5, 0 );

		if (is_admin()) {
			add_action('admin_menu', array($this, 'admin_menu'));

            add_action( 'wp_ajax_transitionslider_save', array($this, 'save_slider') );
            add_action( 'wp_ajax_nopriv_transitionslider_save', array($this, 'save_slider') );

            add_action( 'wp_ajax_transitionslider_duplicate', array($this, 'duplicate_slider') );
            add_action( 'wp_ajax_nopriv_transitionslider_duplicate', array($this, 'duplicate_slider') );

            add_action( 'wp_ajax_transitionslider_delete', array($this, 'delete_slider') );
            add_action( 'wp_ajax_nopriv_transitionslider_delete', array($this, 'delete_slider') );

            add_action( 'wp_ajax_transitionslider_import', array($this, 'import_slider') );
            add_action( 'wp_ajax_nopriv_transitionslider_import', array($this, 'import_slider') );

            add_action( 'wp_ajax_transitionslider_export', array($this, 'export_sliders') );
            add_action( 'wp_ajax_nopriv_transitionslider_export', array($this, 'export_sliders') );

            add_action( 'wp_ajax_transitionslider_get_slider', array($this, 'get_slider') );
            add_action( 'wp_ajax_nopriv_transitionslider_get_slider', array($this, 'get_slider') );

            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts') );

            add_filter( 'plugin_action_links', array($this, 'add_plugin_link'), 10, 2);

		}
	}


	public function add_plugin_link( $plugin_actions, $plugin_file ) {

	    $new_actions = array();
	    if ( 'transition-slider/transition-slider.php' === $plugin_file ) {
	        $new_actions['stx_go_pro'] = sprintf( __( '<a href="%s" style="color:#04c018;font-weight:bold;" target="_blank">Go Pro</a>', 'comment-limiter' ), esc_url( admin_url( 'admin.php?page=transitionslider_go_pro' ) ) );
	    }

	 	    return array_merge( $plugin_actions, $new_actions );
	}

	public function on_shortcode($atts, $content=null) {

		$args = shortcode_atts(
			array(
				'id' => '-1',
                'name' => '-1',
				'width' =>'-1',

				'height' =>'-1',
				'heighttablet' =>'-1',
				'heightmobile' =>'-1',

				'responsive' =>'-1',

				'ratio' =>'-1',
				'ratiotablet' =>'-1',
				'ratiomobile' =>'-1',

				'fullscreen' =>'-1',

				'size' =>'-1',

				'transition' =>'-1',
				'duration' =>'-1',
				'direction' =>'-1',
				'brightness' =>'-1',
				'easing' =>'-1',
				'distance' =>'-1',

			),
			$atts
		);

        $id = (int) $args['id'];
		$name = $args['name'];

		if($name != -1){
			$slider_ids = get_option('transitionslider_ids');

			foreach ($slider_ids as $id) {
				$_s = get_option('transitionslider_'.$id);
				if($_s && $_s['instanceName'] == $name){
					$slider = $_s;
					$id = $slider['id'];
					break;
				}
			}
		}else if($id != -1){
			$slider = get_option('transitionslider_'.$id);
		}

		$newSlides = array();

		foreach ($slider["slides"] as $slide) {

			if($args['transition'] != '-1')
				$slide['transitionEffect'] = $args['transition'];

			if($args['duration'] != '-1')
				$slide['transitionDuration'] = $args['duration'];

			if($args['direction'] != '-1')
				$slide['direction'] = $args['direction'];

			if($args['brightness'] != '-1')
				$slide['brightness'] = $args['brightness'];

			if($args['distance'] != '-1')
				$slide['distance'] = $args['distance'];

			if($args['easing'] != '-1')
				$slide['easing'] = $args['easing'];

			array_push($newSlides, $slide);
		}

		$slider['slides'] = $newSlides;

		if($args['ratio'] != -1) $slider['ratio'] = $args['ratio'];
		if($args['ratiotablet'] != -1) $slider['ratioTablet'] = $args['ratiotablet'];
		if($args['ratiomobile'] != -1) $slider['ratioMobile'] = $args['ratiomobile'];
		if($args['responsive'] != -1) $slider['responsive'] = $args['responsive'];
		if($args['fullscreen'] != -1) $slider['fullscreen'] = $args['fullscreen'];
		if($args['height'] != -1) $slider['height'] = $args['height'];
		if($args['heighttablet'] != -1) $slider['heightTablet'] = $args['heighttablet'];
		if($args['heightmobile'] != -1) $slider['heightMobile'] = $args['heightmobile'];

		if($args['size'] == "fixed") {
			$slider['responsive'] = false;
			$slider['fullscreen'] = false;
		}

		if($args['size'] == "responsive") {
			$slider['responsive'] = true;
			$slider['fullscreen'] = false;
		}

		if($args['size'] == "fullscreen") {
			$slider['fullscreen'] = true;
		}

        $slider['rootFolder'] = $this->PLUGIN_DIR_URL."";

        ?>
    <?php

    	$slider_id = uniqid('slider_');

        $output = '<div id="'. $slider_id .'" class="slider_instance" style="position: relative;" data-options="'.htmlentities(wp_json_encode($slider)).'">';

        $firstSlideSrc = $slider['slides'][0]['src'];

        if(isset($slider['preloadFirstSlide']) && $slider['preloadFirstSlide'] && (strpos($firstSlideSrc, 'jpg') !== false || strpos($firstSlideSrc, 'jpeg') !== false || strpos($firstSlideSrc, 'png') !== false )){
        	?>
		    <script>
		        function STXsliderPreloader(wrapper){
		        	var options = JSON.parse(wrapper.dataset.options);
		        	var preloaderDiv = document.createElement('div')
		        	wrapper.appendChild(preloaderDiv)
		        	preloaderDiv.classList.add("stx_remove")
		        	preloaderDiv.style.position = "absolute"
		        	preloaderDiv.style.backgroundImage = 'url("'+options.slides[0].src+'")';
		        	preloaderDiv.style.zIndex = 2
		        	preloaderDiv.style.backgroundSize = "cover";
		        	preloaderDiv.style.backgroundPosition = "center";

		        	var wrapperWidth = wrapper.clientWidth;
		        	var fullscreen = options.fullscreen
		        	var forceResponsive = options.forceResponsive
		        	var windowWidth = document.documentElement.clientWidth
		        	if(wrapperWidth < options.mobileSize) {
		        		options.ratio = options.ratioMobile || options.ratioTablet || options.ratio;
		        		options.responsive = options.responsiveMobile;
		        		options.width = options.widthMobile || options.widthTablet || options.width;
		        		options.height = options.heightMobile || options.heightTablet || options.height;
		        		fullscreen = options.fullscreenMobile
		        		forceResponsive = options.forceResponsiveMobile
		        	}else if(wrapperWidth < options.tabletSize){
		        		options.ratio = options.ratioTablet || options.ratio;
		        		options.responsive = options.responsiveTablet;
		        		options.width = options.widthTablet || options.width;
		        		options.height = options.heightTablet || options.height;
		        		fullscreen = options.fullscreenTablet
		        		forceResponsive = options.forceResponsiveTablet
		        	}


		        	if(options.responsive){
		        		if(options.forceResponsive){
		        			preloaderDiv.style.width = windowWidth + "px"		        			
		        			preloaderDiv.style.marginLeft = "-" + wrapper.offsetLeft + "px"
		        			preloaderDiv.style.height = windowWidth / options.ratio + "px";
		        			wrapper.style.height = windowWidth / options.ratio + "px";
		        		}else{
		        			preloaderDiv.style.width = "100%";
		        			preloaderDiv.style.left = "50%"
		        			preloaderDiv.style.transform = "translateX(-50%)"
		        			preloaderDiv.style.webkitTransform = "translateX(-50%)"
		        			preloaderDiv.style.height = wrapper.clientWidth / options.ratio + "px";
		        			wrapper.style.height = wrapper.clientWidth / options.ratio + "px";
		        		}

		        				        	}else{
		        		preloaderDiv.style.width = options.width + "px";
		        		preloaderDiv.style.height = options.height + "px";
		        		wrapper.style.height = options.height + "px";
		        	}

		        	if(fullscreen){
		        		var rect = wrapper.getBoundingClientRect();
						var offset = { 
			                top: rect.top + window.scrollY, 
			                left: rect.left + window.scrollX, 
			            };
		        		options.height = window.innerHeight - offset.top;
		        		preloaderDiv.style.height = options.height + "px";
		        		wrapper.style.height = options.height + "px";
		        	}
		        }
		    </script>
		    <?php

	    	$output .= '<script class="stx_remove">STXsliderPreloader(document.getElementById("'.$slider_id.'"))</script>';

   		}

        $output .= '</div>';

        $cssMode = isset($slider["mode"]) && $slider["mode"] == "css";

        if(!$cssMode)
        	wp_enqueue_script("transitionslider-lib-three");
		wp_enqueue_script("transitionslider-lib-swiper");
		wp_enqueue_script("transitionslider-lib-anime-js");
		wp_enqueue_script("transitionslider-lib-webfontloader");
		wp_enqueue_script("transitionslider-lib-iconify");

				wp_enqueue_script("transitionslider-build");
		if(!$cssMode)
			wp_enqueue_script("transitionslider-build-webgl");

	    wp_enqueue_style( "transitionslider-css");
	    wp_enqueue_style( "transitionslider-swiper-css");
	    wp_enqueue_style( "transitionslider-fontawesome-css");

	    wp_enqueue_script("transitionslider-embed");

		return $output;

	}

	public function sanitize_array($arr){
	   foreach ($arr as $key => $val) {

	      if(is_array($val))
	        $arr[$key] = $this->sanitize_array($val);
	      else
	        $arr[$key] = sanitize_textarea_field($val);

	   }

	   return $arr;
	}

	public function save_slider() {

        check_ajax_referer( 'stx_nonce', 'security' );

		$current_id = $page_id = '';

		$json = stripslashes($_POST['slider']);

        $slider = slider_objectToArray(json_decode($json));

		if (isset($_POST['id']) ) {
			$current_id = $_POST['id'];
		}

		$slider_ids = get_option('transitionslider_ids');
		if(!$slider_ids){
			$slider_ids = array();
		}
		$sliders = array();
		foreach ($slider_ids as $id) {
			$_slider = get_option('transitionslider_'.$id);
			if($_slider){
				$sliders[$id] = $_slider;
			}else{
				$slider_ids = array_diff($slider_ids, array($id));
			}
		}

		if ($slider['status'] == 'draft') {
			array_push($slider_ids,$current_id);
			add_option('transitionslider_'.$current_id, array());
			$sliders[$current_id] = array();
		}

		update_option('transitionslider_ids', $slider_ids);

		$sliders[$current_id] = $slider;

		$sliders[$current_id]['status'] = 'published';

		update_option('transitionslider_'.$current_id, $sliders[$current_id]);


		wp_die(); 
	}

	public function duplicate_slider() {

        check_ajax_referer( 'stx_nonce', 'security' );

        $current_id = sanitize_text_field($_POST['currentId']);

        $new_id = 0;
        $highest_id = 0;

        $slider_ids = get_option('transitionslider_ids');

        foreach ($slider_ids as $id) {
            if((int)$id > $highest_id) {
                $highest_id = (int)$id;
            }
        }
        $new_id = $highest_id + 1;

        $current =  get_option('transitionslider_'.(string)$current_id);

        $new = $current;
        $new["id"] = $new_id;
        $new["name"] = $current["name"]." (copy)";
        $new["instanceName"] = $current["instanceName"]." (copy)";

        $new["date"] = current_time( 'mysql' );

        delete_option('transitionslider_'.(string)$new_id);
        add_option('transitionslider_'.(string)$new_id,$new);

        array_push($slider_ids,$new_id);

        delete_option('transitionslider_ids');
        add_option('transitionslider_ids',$slider_ids);

        wp_die();

    }

    public function delete_slider() {

        check_ajax_referer( 'stx_nonce', 'security' );

        $slider_ids = get_option('transitionslider_ids');

        $current_id = sanitize_text_field($_POST['currentId']);

        if($current_id){

            $ids = explode(',', $current_id);

            foreach ($ids as $id) {
              delete_option('transitionslider_'.(string)$id);
            }
            $slider_ids = array_diff($slider_ids, $ids);
            update_option('transitionslider_ids', $slider_ids);

        }else{

            foreach ($slider_ids as $id) {
              delete_option('transitionslider_'.(string)$id);
            }

            delete_option('transitionslider_ids');

        }

        wp_die();

    }

        public function export_sliders() {

        check_ajax_referer( 'stx_nonce', 'security' );

        $slider_ids = get_option('transitionslider_ids');

        $current_id = sanitize_text_field($_POST['currentId']);

        $arr = array();

        if($current_id){

            $ids = explode(',', $current_id);

            foreach ($ids as $id) {

		        $slider = get_option('transitionslider_'.$id);
		        if($slider){
		            $arr[$id] = $slider;
		        }

            }

        }else{

            foreach ($slider_ids as $id) {
            	$slider = get_option('transitionslider_'.$id);
		        if($slider){
		            $arr[$id] = $slider;
		        }

            }

        }

        echo(json_encode($arr));

        wp_die();

    }

    public function import_slider() {

        check_ajax_referer( 'stx_nonce', 'security' );

        $json = stripslashes($_POST['slider']);

        $slider = slider_objectToArray(json_decode($json));

        $new_id = 0;
        $highest_id = 0;

        $slider_ids = get_option('transitionslider_ids');

        foreach ($slider_ids as $id) {
            if((int)$id > $highest_id) {
                $highest_id = (int)$id;
            }
        }

        $new_id = $highest_id + 1;

        $upload_dir = wp_upload_dir();
		$slidersFolder = $upload_dir['basedir'] . '/transition-slider/';
		$sliderFolder = $slidersFolder . 'slider_' . $new_id . '/';
		$thumbnailsFolder = $sliderFolder . 'thumbnails/';

		$slidersUrl = $upload_dir['baseurl'] . '/transition-slider/';
		$sliderUrl = $slidersUrl. 'slider_' . $new_id . '/';
		$thumbnailsUrl = $sliderUrl . 'thumbnails/';

		if (!file_exists($slidersFolder)) {
			mkdir($slidersFolder, 0777, true);
		}

		if (!file_exists($sliderFolder)) {
			mkdir($sliderFolder, 0777, true);
		}

		if (!file_exists($thumbnailsFolder)) {
            mkdir($thumbnailsFolder, 0777, true);
        }

        foreach ($slider["slides"] as $key => $slide) {
        	$src = $slide['src'];
        	$thumbSrc = $slide['thumbSrc'];

        	$info = pathinfo($slide['src']);
        	$fileName = $info['basename'];

			$newPath = $sliderFolder . $fileName ;

			$thumbInfo = pathinfo($slide['thumbSrc']);
			$thumbFileName = $thumbInfo['basename'];

			$newThumbPath = $thumbnailsFolder . $thumbFileName ;

			if ( copy($src, $newPath) ) {
			    echo "Copy success!";
			}else{
			    echo "Copy failed.";
			}

            if ( copy($thumbSrc, $newThumbPath) ) {
                echo "Copy success!";
            }else{
                echo "Copy failed.";
            }

			$slider['slides'][$key]['src'] = $sliderUrl . $fileName ;
			$slider['slides'][$key]['thumbSrc'] = $thumbnailsUrl . $thumbFileName ;

        }

        $slider['id'] = $new_id;
        $slider["date"] = current_time( 'mysql' );
        $sliders[$new_id] = $slider;

		update_option('transitionslider_'.$new_id, $sliders[$new_id]);

		array_push($slider_ids,$new_id);

        delete_option('transitionslider_ids');
        add_option('transitionslider_ids',$slider_ids);

		wp_die(); 
	}

	public function get_slider() {

        check_ajax_referer( 'stx_nonce', 'security' );

        $current_id = sanitize_text_field($_POST['currentId']);

        $s = get_option('transitionslider_'.$current_id);

        echo(json_encode($s));

		wp_die(); 
	}

	protected function my_plugin_basename() {
		$basename = plugin_basename(__FILE__);
		if ('/'.$basename == __FILE__) { 
			$basename = basename(dirname(__FILE__)).'/'.basename(__FILE__);
		}
		return $basename;
	}

	protected function my_plugin_url() {
		$basename = plugin_basename(__FILE__);
		if ('/'.$basename == __FILE__) { 
			return plugins_url().'/'.basename(dirname(__FILE__)).'/';
		}
		return plugin_dir_url( __FILE__ );
	}
}
if(!function_exists("trace_stx")){

	function trace_stx($var){
		echo("<pre style='z-index:999999;background:#fcc;color:#000;font-size:12px;'>");
		print_r($var);
		echo("</pre>");
	}

}

if(!function_exists("slider_objectToArray")){

	function slider_objectToArray($d) {
		if (is_object($d)) {
			$d = get_object_vars($d);
		}

		if (is_array($d)) {
			return array_map(__FUNCTION__, $d);
		}
		else {
			return $d;
		}
	}

}
