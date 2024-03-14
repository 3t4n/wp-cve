<?php
/*
 * Display the Masonry Widget
 * Create a masonry from posts optionally based on selected categories
 * @author Adrian Jones
 * Shoofly Solutions 2016
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/******************************Short Code************************/
add_shortcode( 'featured_image_pro', 'featured_image_pro_widget' );
add_shortcode( 'wp_enqueue_scripts', 'featured_image_pro_enqueue_scripts' );
if ( !function_exists( 'featured_image_pro_widget' ) ) {
	/**
	 * featured_image_pro_widget function.
	 * Generate a widget from an array of objects
	 *
	 * @access public
	 * @param array   $args     (default: array())
	 * @param string  $widgetid (default: '')
	 * @return void
	 */
	function featured_image_pro_widget( $args = array(), $widgetid='' ) {
		if ( !$args ) //default options
			$args = array( 'imagesize' => 'thumbnail' ); //Just make a small default array
		$featured_image_pro_pspm = new featured_image_pro_post_masonry( $args, $widgetid );
		return $featured_image_pro_pspm->featured_image_pro_get_full_content(  );
	}
}
/*
 ***************************************** Register Scripts**********
 */
if ( !function_exists( 'featured_image_pro_post_masonry_register_scripts' ) ) {
	/**
	 * featured_image_pro_post_post_masonry_register_scripts
	 * Register scripts and style sheets for the widget
	 *
	 * @access public
	 * @return void
	 */
	function featured_image_pro_post_masonry_register_scripts() //Register scripts
	{
		wp_register_style( 'featured_image_pro_masonry_style', plugins_url( 'assets/css/proto-masonry.css', __FILE__ ), array(), '8.5' );
		wp_register_script('jquery.dotdotdot', plugins_url('assets/third-party/jQuery.dotdotdot/dotdotdot.js', __FILE__), array(), '1.0', TRUE);
		wp_register_script('featured-image-pro', plugins_url('assets/js/featured-image-pro.js', __FILE__), array('jquery'), '1.0', TRUE);

	}
}



add_action( 'wp_enqueue_scripts', 'featured_image_pro_post_masonry_register_scripts', 14 );
$plugindir = plugin_dir_path( __FILE__ );
require_once $plugindir . '/functions/proto-masonry-scripts.php'; //Class that creates the scripts
require $plugindir . '/functions/proto-snap-images_03.php'; 	  //Class that loads the images & details
require $plugindir . '/functions/proto-masonry.php'; 			  //Class that creates the grid
require_once $plugindir .'/functions/proto-global.php'; 		  //Utilities
/*
 * 	Masonry Class
 */
if ( !class_exists( 'featured_image_pro_post_masonry' ) ) {
	 /* featured_image_pro_post_masonry class.
	 */
	class featured_image_pro_post_masonry {
		public $atts = array();         //The query options
		public $options = array();      //The masonry options
		public $uniqueId = '';          //Unique Id for current masonry
//		private $footer_scripts;        //Footer scripts
		public $scriptsObj;				//Generate Styles & Scripts Object
		public $imgr = null;			//Generate Images Object
		private $post_type;
		/**
		 * __construct function.
		 *
		 * @access public
		 * @param array   $args     (default: null) = all query attributes & options in one array
		 * @param string  $widgetId (default: '') - widget id
		 * @param boolean $widget   - true if being called by the widget, keeps standard options from being processed
		 * @return void
		 */
		function __construct( $args = array(), $widgetId = '', $widget = false ) {

			$this->atts = $args;    		//args can be set in the constructor when used with shortcodes
			//----------------------------Determine the Unique Id. If none was passed, create one
			if ( isset( $this->atts['uniqueid'] ) )      //Allow unique id to be set from the user shortcode
				$this->uniqueId = sanitize_text_field( $this->atts['uniqueid'] );
			elseif ( $widgetId )      //Use the widget id
				$this->uniqueId = sanitize_text_field( $widgetId );
			else          //Genearate an id at runtime
				$this->uniqueId = uniqid();  //Set the unique id for the widget on construction
			$this->atts['uniqueid'] = $this->uniqueId;
			if (isset($args['post_type']))
				$this->post_type = $args['post_type'];

			if ( is_string ($args ))
					$args = array();
			$this->atts = array_change_key_case ( $args ,  CASE_LOWER ); 						//make everything lower case. Case is set later in the code if necessary (for javascript options which are handled separately - see proto_masonry_scripts for an example).
			$this->imgr = new featured_image_pro_image_retrieve_03();     						//This objwxr is the image function class
			$this->scriptsObj = new proto_masonry_scripts();  //The generate scripts and style object
			/*****************************************Get Ready********************************/
			if ( !$widget )
				add_filter( 'proto_masonry_options', array( $this, 'basic_options' ), 10, 2 ); 						//1. parse out the attributes & options into
			add_filter( 'proto_masonry_options',  array( 'proto_functions', 'check_settings' ), 15, 1);  			 //2. post process the options - fix anything that needs to be fixed, removed or adjusted after the options are processed ($options)
			add_filter( 'proto_masonry_attributes', array( $this->imgr, 'wordpress_post_attributes' ), 15, 3);		 //3. process the core attributes for the Wordpress Query
			add_action( 'proto_masonry_enqueue_late', array( $this, 'proto_masonry_enqueue_late' ), 15, 2 ); 						 //4. Initialize, enqueue stuff
			add_action( 'proto_masonry_enqueue_late', array( $this, 'proto_masonry_enqueue_late_scripts' ), 99, 2 ); 				//4. Initialize, enqueue stuff
do_action('wp_print_scripts');

			add_filter( 'pre_get_posts', array($this, 'proto_fix_pre_get_posts_query'), 99, 1 );

			/**********************************************************************************/
			/*Process the posts/images
			/***********************************************************************************/
			add_action('proto_subcaption', array($this->imgr, 'proto_subcaption'), 15, 3);
			/*****************************************Create the Widget***********************/
			add_filter( 'proto_masonry_object', array( $this->imgr, 'wordpress_featured_images' ), 15, 3);			  //core code to retrieve the images
			add_filter('proto_masonry_footer_scripts', array( $this->scriptsObj, 'masonry_doscript' ), 15, 2);			//Create the inline javascript scripts
		    add_filter( 'proto_masonry_script_options', array( $this->scriptsObj, 'masonry_script_options' ), 14, 2 );  //Process the javascript options for each widget
		    add_filter( 'proto_masonry_full_script', array( $this->scriptsObj, 'masonry_default_script' ), 15, 3 ); 	//Create the javascript script for each widget
			add_filter ( 'proto_inline_css', array ( $this->scriptsObj, 'masonry_dostyle' ), 15, 2 );					//Create the inline css styles
//			add_action( 'wp_footer', array( $this, 'featured_image_pro_post_doscript' ), 15 );     //Add inline scripts to footer
			do_action( 'proto_masonry_add_filters', $args );

			proto_functions::debug_writer( 'Initial Arguments', $args,
				( isset( $args['debug'] ) ? proto_boolval( $args['debug'] ) : false ) ,
				( isset( $args['debug_log'] ) ? proto_boolval( $args['debug_log'] ) : false ) );
		}
		public function remove_filters() // Called after grid has been created.
		{
			remove_filter( 'pre_get_posts', array($this, 'proto_fix_pre_get_posts_query'), 99 );
			remove_all_filters( 'proto_masonry_options' );  //process the options
			remove_all_filters( 'proto_masonry_attributes' );		  //process the core attributes
			remove_all_filters( 'proto_masonry_object' );			  //core code to retrieve the images
			remove_all_filters( 'proto_masonry_script_options' );	//process the javascript options
		    remove_all_filters( 'proto_masonry_full_script' );			//Create the script
		    remove_all_filters( 'proto_inline_css' );
		    remove_all_filters( 'proto_masonry_footer_scripts' );
		    remove_all_actions( 'proto_masonry_enqueue_late' );
			remove_all_actions( 'proto_subcaption' );
			remove_all_filters( 'proto_masonry_object' );			  //core code to retrieve the images
			remove_filter( 'pre_get_posts', array($this, 'proto_fix_pre_get_posts_query'), 99);
			do_action('proto_masonry_remove_filters');			  //remove filters filter
			remove_all_filters( 'proto_masonry_remove_filters' );
		}
		/**
		 * featured_image_pro_get_full_content function.
		 * Get complete grid content with container & class
		 *
		 * @access public
		 * @return void
		 */
		public function featured_image_pro_get_full_content( $widget = false) {
			$attobj = $this->featured_image_pro_get_details();    //do initialization stuff, get the details for the grid items
			return  $this->grid_with_class($attobj, $widget);       		  //get the grids.
		}
		 /* proto_fix_pre_get_posts_query function.
		 * Replace any code that might have overridden the post type
		 * @access public
		 * @param mixed $query
		 * @return void
		 */
		function proto_fix_pre_get_posts_query($query)
		{

			if ( isset( $this->post_type ) && $this->post_type != '' )
			{
				$post_type = explode(',', $this->post_type);
				$post_type = array_map('trim', $post_type);
				$query->set('post_type', $post_type);
			}
			$this->post_type = '';
			return $query;
		}		/*
		 * featured_image_pro_get_details function.
		 * initialize the widget
		 * @access public
		 * @return void
		 */
		function featured_image_pro_get_details() {									//atts contains all of the arguments at first
			$this->options = $this->parse_options($this->atts, $this->uniqueId);  //Get the options, parse them out of the arguments
			$this->atts =  array_diff_key( $this->atts, $this->options );         //remove the options from all of the arguments
			$this->atts = apply_filters('proto_masonry_attributes',  $this->atts, $this->options );   //Parse out & customize the query attributes
			proto_functions::debug_writer( 'Parsed Options', $this->options,
				( isset( $this->options['debug'] ) ? proto_boolval( $this->options['debug'] ) : false ) ,
				( isset( $this->options['debug_log'] ) ? proto_boolval( $this->options['debug_log'] ) : false ) );

			//-----------------------------------------------------------
			do_action( 'proto_image_pro_init', $this->options, $this->scriptsObj ); //depracated
			do_action( 'proto_masonry_enqueue_late', $this->options, $this->scriptsObj );  //Do initialization stuff after parsing out all of the

			proto_functions::debug_writer( 'Filters for proto_masonry_enqueue_late', proto_functions::proto_get_filters_for('proto_masonry_enqueue_late'), false ,
				( isset( $this->options['debug_log'] ) ? proto_boolval( $this->options['debug_log'] ) : false ) );


			//----------------------------Get the image/post detail
			$attobj = new stdClass();//this object contains all of the attachment objects & paging details
			$attobj = apply_filters('proto_masonry_object', $attobj, $this->atts, $this->options ); //Get the posts with attachments
			$this->options['maximgwidth'] = $attobj->maximgWidth;
			//---------------------------Get styles and sctips
			$styles = '';
			$styles =  apply_filters('proto_inline_css',  $styles,  $this->options );  //Get inline styles from options
			wp_add_inline_style( 'featured_image_pro_masonry_style', $styles );  //Insert Styles
			$footer_scripts = '';
			$footer_scripts = apply_filters( 'proto_masonry_footer_scripts', $footer_scripts, $this->options );  //get any scripts that are going to go into the footer. They will be output in the footer. (do not encapsalate your scripts with  <script></script>
			wp_add_inline_script('featured-image-pro', $footer_scripts);
			return $attobj;
		}
		/**
		 * proto_masonry_enqueue_late function.
		 * Selectively Enqueue registered scripts after options have been parsed
		 * that have already been registered.
		 * @access public
		 * @param options - plugin options
		 * @param script object - this object may be used by some functions to unregister some of the styles and js script functions
		 * @return void
		 */
		function proto_masonry_enqueue_late($options)  //Where we enqueue scripts
		{
			wp_enqueue_style( 'dashicons' );
			$displayas = isset( $options['displayas'] ) ? sanitize_text_field( $options['displayas'] ) : 'masonry';
			if ($displayas == 'masonry')
				wp_enqueue_script( 'jquery-masonry' );           //Enqueue Masonry
			wp_enqueue_style( 'featured_image_pro_masonry_style' );   //Enqueue Styles
			if ($this->options['captionheight'])
				wp_enqueue_script('jquery.dotdotdot') ; //, plugins_url('assets/third-party/jQuery.dotdotdot/src/jquery.dotdotdot.js', __FILE__), array(), '1.0', TRUE);

		}

		function proto_masonry_enqueue_late_scripts() //this should be the last script enqueued. It's empty and it's what we append our generated script tp
		{
				wp_enqueue_script('featured-image-pro');
		}
		/**
		 * grid_with_class function.
		 * Generates a grid with a container & class based on the prefix
		 *
		 * @access public
		 * @return void
		 */
		 function grid_with_class($attobj, $widget = false) {
			$options = $this->options;
			$gridalign = esc_attr( $options['gridalign'] );   //left, right or center
			$prefix = sanitize_text_field( $options['prefix'] ); //the current grid preefix
			$fitwidth = proto_boolval( $options['fitwidth'] );    //fit width
			$uniqueId = sanitize_text_field( $options['uniqueid'] );
			$containerclass = 'proto_masonry_container_'.strtolower( $gridalign );
			if ( $fitwidth ) {
				$containerclass .= ' ' . $prefix . '_masonry_fitwidth proto_masonry_fitwidth ';
			}
			$containerclass = apply_filters( 'proto_grid_container_class', $containerclass, $options ); //add a class to the grid
			$topclass = '';
			$featured_image_grid  = new proto_masonry(); //grid class
			$grid = $featured_image_grid->proto_masonry_pro_grid( $attobj, $options, $this->atts );
			$topclass =  apply_filters( 'proto_masonry_parent_class', $topclass, $options );
			$ret = '';
			if ( !$widget )
				$ret =  "<div class='{$prefix} proto_masonry_top $topclass'>";
			$ret .=  "
				<div id='{$prefix}_masonry_container_{$this->uniqueId}'  class='{$prefix}_masonry_container proto_masonry_container $containerclass'>
				$grid
				</div><!--masonry-container--><br style='clear:both'>
				<a class='proto_bottom'  href='#bottom_$uniqueId' id='proto_bottom_$uniqueId'></a>";
			if ( !$widget )
				$ret .= "</div><!--$prefix-->";
			$this->remove_filters();
			return $ret;
		}
		function grid_items() {
			$attobj = new stdClass();//this object contains all of the attachment objects & paging details
			$attobj = apply_filters( 'proto_masonry_object', $attobj, $this->atts, $this->options ); //customize the attachment object
			$featured_image_grid  = new proto_masonry(); //grid class
			$ret =  $featured_image_grid->proto_masonry_pro_items( $attobj, $this->options );
			$this->remove_filters();
		}
		function parse_options($atts, $uniqueid)
		{
			$options = array();
			//Filter the options
			$atts['uniqueid'] = $uniqueid;
			$options = apply_filters( 'proto_masonry_options',  $options, $atts );
			$options['uid'] = str_replace( '-', '_', $uniqueid );
			if ( proto_boolval( $options['openwindow'] ) )
				$options['target'] = '_blank';
			return $options;
		}
		/**
		 * featured_image_pro_post_doscript function.
		 * Just print the pre-saved js scripts for the grid
		 * Called in wp_footer hook
		 * @access public
		 * @return void
		 */
	/*	public function featured_image_pro_post_doscript() {
			echo $this->footer_scripts;
			remove_action( 'wp_footer', array( $this, 'featured_image_pro_post_doscript' ), 15 );     //Add inline scripts to footer
		}*/
		/**
		 * basic_options function.
		 * Get options array
		 *
		 * @access public
		 * @param array   $options
		 * @param array   $atts
		 * @return void
		 */
		function basic_options( $options, $args ) {
			/* Common mistakes */
			if ( isset ($args['margin-bottom'] ) && !isset($args['marginbottom'] ) )
				$args['marginbottom'] = $args['margin-bottom'];
			if ( isset ($args['margin'] ) && !isset($args['marginbottom'] ) )
				$args['marginbottom'] = $args['margin'];
			unset ( $args['margin-bottom'] );
			unset ( $args['margin'] );
			$defaults = array(
					'uniqueid' => $args['uniqueid'],
					'fitwidth' => true,             //masonry settings fit width to content
					'columnwidth' => '',  //masonry setting column width
					'gutter' => 5, //masonry setting space between items
					'showcaptions'  => FALSE,       //display post title
					'captionalign' => 'center',
					'hovercaptions' => FALSE,       //hover title over image
					'captionheight' => '',          //fixed caption height
					'captionheight' => '',          //fixed caption height
					'excerptheight' => '',          //fixed excerpt height
					'marginbottom' => '5px',        //masonry item margin bottom
                    'item_color' => '',             //item color
                    'item_bgcolor' => '',           //item background color
                    'link_color' => '',             //item link color
                    'link_hovercolor' => '',        //link hover color,
					'imagesize' => 'thumbnail',     //image size
					'border' => 0,                  //item border width (<= gutter)
					//'excerpt' => false,             //show excerpt
					'showexcerpts' => false, 		//same
					'excerpthr' => false,           //include horizontal line under excerpt
					'captionhr' => false,			//include horizontal line above excerpt
					'excerptlength' => null,        //override excerpt length
					'htmlexcerpt' => false,			//html excerpts
					'excerptalign'=>'left',			//align excerpt text
					'imagewidth' => '',             //Fixed Image width
					'imageheight' => '',            //Fixed Image height
					'padimage' => true,            //Pad image
					'gridwidth' =>'100%',           //Width of Grid
					'openwindow' => FALSE,          //Set target to blank
					'gridalign'=>'center',          //align grid center, left, right
					'uniqueid' => '',               //Unique grid id
					'uid' => '', 					//Modified unique grid id
					'maxwidth' => '',               //maximum item width
					'maxheight' => '',              //maximim item height
					'prefix' => 'featured_image_pro', //Prefix
					'itemwidth' => '',				//width of gtid item
					'animate' => false,				//enable disable css transitions
					'animationduration' => '.7s',	//set css transition duration
					'tooltip' => true,				//enable caption toolitp on image
					'subcaption1' => '',			//subcaption 1
					'subcaption2' => '',			//subcaption 2
					'subcaptionalign' => 'center',  //align subcaption
					'resizeonload'=> false,			//resize after loading for grid items that overlap
					'layoutonresize' => 500,		//how long to wait before runing layout when resizeonload is true
					'rel' => '',					//rel for the link item
					'debug_log' => false,			//write debug content to the log
					'debug' => false,				//debug to the screen
					'debug_query' => false,			//debug query info to the screen
					'linksubcaptions' => false,		//add the link to the post to the subcaptions
					'boxshadow' => true,			//add a box shadow to the grid items
					'transitionduration' => 0, 				//default transition duration to 0
					'excerpt_custom_link_text' => '', 		//override read more for excerpts
					'excerpt_custom_link_type' => '',		//span, div or button
			);
			$noptions = shortcode_atts( $defaults , $args  );
			if ( isset( $args['excerpt'] ) && !isset( $args['showexcerpts'] ) )
				$noptions['showexcerpts'] = $args['excerpt'];
			unset($noptions['excerpt']);
			$options = array_merge( $options, $noptions );
			if ( proto_boolval( $options['hovercaptions'] ) )
				$options['showcaptions'] = true;
			return $options;
		}
	}
}