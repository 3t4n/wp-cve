<?php
/**
 * Featured Image Grow - Advanced functionality
 *
 * @category  utility
 * @package  featured-image-pro
 * @author  Adrian Jones <adrian@shooflysolutions.com>
 * @link http:://www.shooflysolutions.co
 * */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
$plugindir = plugin_dir_path( __FILE__ );
require_once $plugindir . 'proto-media.php';
//todo there should be settings allowing these options to be loaded
require_once $plugindir . 'proto-masonry-advanced.php';
if ( !function_exists( 'featured_image_pro_post_masonry_grow_register_scripts' ) ):
	//Register scripts
	function featured_image_pro_post_masonry_grow_register_scripts() {
		wp_register_script( 'ajax_proto_posts',  plugins_url( 'advanced/assets/js/posts.js', dirname( __FILE__ ) ),  array(), 5.57 );
		wp_register_script( 'proto_isotope', plugins_url ( 'advanced/assets/third-party/isotope.pkgd.min.js', dirname(__FILE__) ), array(), 5.52 );
		wp_register_style( 'featured-image-pro-isotope_styles', plugins_url ( 'advanced/assets/css/featured-image-pro-isotope.css', dirname(__FILE__) ), array(), 1.2 );
		wp_register_style( 'featured-image-pro-advanced-styles', plugins_url ( 'advanced/assets/css/featured-image-pro-advanced.css', dirname(__FILE__) ), array(), 1.4 );
	}
endif;


add_action( 'wp_enqueue_scripts', 'featured_image_pro_post_masonry_grow_register_scripts' );
/*
 *    Masonry Ajax handling for paging thumbnail grid
 */
add_action( 'wp_ajax_proto_get_post_masonry', 'proto_ajax_get_post_masonry' );
add_action( 'wp_ajax_nopriv_proto_get_post_masonry', 'proto_ajax_get_post_masonry' );
if ( !function_exists( 'proto_ajax_post_masonry' ) ) {
	/**
	 * proto_ajax_get_post_masonry function.
	 * Get ajax response for paging
	 *
	 * @access public
	 * @return grid content in json format
	 */
	function proto_ajax_get_post_masonry() {
//		check_ajax_referer( 'featured_image_pro', 'security' );
		if ( !empty( $_POST['atts'] ) && !empty( $_POST['options'] ) ) {
			$json1          = str_replace( '\\"', '"', $_POST['atts'] );//strip out extra backslash characters
			$atts          = json_decode( $json1, true );//Decode the data
			$atts['paged'] = $_POST['page'];//overwrite the static page value with the last page
			$direction     = $_POST['direction'];//the direction to page
			$json          = str_replace( '\\"', '"', $_POST['options'] );//strip out extra backslssh characters
			$options       = json_decode( $json, true );//decode the options
			$debug_log     = isset($options['debug_log']) ? sanitize_text_field($options['debug_log']) : false;
			$nextpage      = $_POST['nextpage'];
			if ($debug_log)
			{
		 		 proto_functions::debug_writer( '$_POST', $_POST, false, $debug_log );
		 		 proto_functions::debug_writer( 'json', $json, false , $debug_log );
		 		 proto_functions::debug_writer( 'atts', $atts, false , $debug_log );
		 		 proto_functions::debug_writer( 'options', $atts, false, $debug_log );
			}
			//process the data
			$featured_image_pro_ajax = new featured_image_pro_advanced( $options );
			$response =  $featured_image_pro_ajax->ajax_psp_masonry( $atts, $options, $direction, $nextpage );
			$featured_image_pro_ajax->remove_filters( );

			return wp_send_json( $response );
		} else {
			echo json_encode( 'bye' );
		}
		die();
	}
}
if ( !function_exists( 'featured_image_pro' ) ) {
	/**
	 * featured_image_pro function.
	 * Shortcode function
	 *
	 * @access public
	 * @param array   $args     (default: array()) - query attributes & options are in the same array
	 * @param string  $widgetid (default: '') - widget id
	 * @return grid widget
	 */
	function featured_image_pro( $args = array(), $widgetid = '' ) {
		if ( !$args ) {
			$args = array( 'imagesize' => 'thumbnail', 'post_type' => 'post' ); //Just make a small default array
		}
		if ( isset ( $args['id'] ) ) //Get any settings saved in the database
		{
			global $wpdb;
			$grid = intval( $args['id'] );
			$db_name = $wpdb->prefix . 'proto_masonry_grids';
			$options = $wpdb->get_var($wpdb->prepare( "Select options FROM $db_name WHERE id = %d", $grid));
			$values = json_decode( $options, true );  //decode the settings
			if (!empty($values))
			{
				foreach ( $values as $key=>$value )
				{
					$value = preg_replace('/\\\"/',"\"", $value);
					$value          = str_replace( '\\"', '"', $value );//strip out extra backslssh characters
					$value          = str_replace( "\'", "'", $value );//strip out extra backslssh characters
					$values[$key] = $value;
					foreach($values as $key=>$value) //remove any quotes from the value
					{
						$value = trim($value, "'");
						$value = trim($value, '"');
						$values[$key] = trim($value);
					}
				}
			}
			unset ( $args['id'] );
			if ( !empty( $values ) )
				$args = array_merge($values, $args);
		}
		$advanced = new featured_image_pro_advanced( $args );
		$ret = $advanced->featured_image_generate_grid( $args, $widgetid );
		$advanced->remove_filters( );
		return $ret;
	}
}
add_shortcode( 'thumbnail_masonry', 'featured_image_pro' );//Shortcode
add_shortcode( 'featured_image_pro', 'featured_image_pro' );//Shortcode
/**/
if ( !class_exists( 'featured_image_pro_advanced' ) ) {
	class featured_image_pro_advanced
	{
		private $front_page;
		private $base_url;
		private $ajaxpage;
		private $is_single;
		private $paged;
		private $max_pages;
		private $debug;
		private $debug_query;
		private $debug_log;
		private $post_type;
		private $masonry_advanced;
		private $masonry_scripts;
		function __construct( $options=array() ) {
			$this->masonry_advanced = new proto_masonry_advanced();
			$this->filters( $options );
		}
		/**
		 * filters function.
		 * add filters
		 *
		 * @access public
		 * @return void
		 */
		public function filters( $args ) {
			add_filter( 'proto_masonry_options', array( $this, 'masonry_options' ), 15, 2 );       //Process additional plugin options specific to masonry
			add_filter( 'proto_masonry_before_grid', array( $this, 'proto_masonry_navigation' ), 15, 3 );   //Html displays before the grid (in the container)
			add_filter( 'proto_masonry_after_grid', array( $this, 'proto_masonry_navigation_hidden_ajax' ), 15, 4 ); //Html displays after the grid (in the container)
			add_action( 'proto_masonry_enqueue_late', array( $this, 'proto_masonry_enqueue_late_advanced' ), 15, 1 );   //Additonal initialization (enqueue any additional scripts)
			add_filter( 'proto_masonry_before_items', array( $this, 'proto_masonry_gutter_sizer' ), 15, 2 );  //Html displays before the grid item
			add_filter( 'proto_masonry_itemstyles', array( $this, 'advanced_item_styles' ), 15, 2 );     //Process any additional styles for the masonry items
			add_filter( 'proto_inline_css', array( $this, 'advanced_styles' ), 15, 2 );        //Process any additional inline css
			add_filter( 'proto_masonry_attachments', array( $this, 'proto_navigation' ), 15, 3 );     //Add something to the attachment object
			add_filter( 'proto_masonry_image_output', array( $this, 'proto_masonry_hover_image' ), 15, 3);   //Add to an image/post object
			add_filter( 'proto_snap_post_object', array( $this, 'proto_snap_get_default_img' ), 15, 4 );   //Get the default image
			add_filter( 'proto_masonry_settings', array( $this, 'proto_advanced_local_settings' ), 15, 2 );     //Do some local customs stuff.
			add_filter( 'proto_masonry_item_inline_style', array( $this, 'proto_masonry_item_inline_style_advanced' ), 15, 3 );  //Add some inline style to the item
			add_filter( 'proto_wordpress_post_attributes', array( $this, 'proto_post_attributes' ), 15, 2 );  //Customize the query attributes
			add_action( 'proto_masonry_after_query', array( $this, 'proto_masonry_get_max_pages' ), 15, 2 );  //Do stuff after the query
			add_filter( 'proto_masonry_grid_class', array( $this, 'proto_masonry_grid_class' ), 15, 2 );  //Add any additional classes to the widget
			add_filter( 'proto_masonry_item_class', array( $this, 'proto_masonry_item_class' ), 15, 2 );
			add_filter( 'proto_masonry_caption_class', array( $this, 'proto_masonry_caption_class' ), 15, 2 );
			add_filter( 'proto_masonry_subcaption_class', array ($this, 'proto_masonry_subcaption_class' ), 15, 2);
			add_filter( 'proto_masonry_excerpt_class', array( $this, 'proto_masonry_excerpt_class' ) , 15, 2 );
			add_filter( 'proto_masonry_image_class', array( $this, 'proto_masonry_image_class' ) , 15, 2 );
			add_filter( 'proto_masonry_parent_class', array( $this, 'proto_masonry_parent_class' ), 15, 2 ); //Add any additional class to the top container
			add_filter( 'proto_grid_container_class', array( $this, 'proto_masonry_container_class' ), 15, 2 ); //Add any additional class to the top container
			add_filter( 'proto_columnwidth_masonry_script', array( $this, 'masonry_column_width_script' ) , 15, 2 ); //Change the columnwidth for percentposition
			add_filter( 'featured_image_pro_excerpt_inline_style', array( $this, 'no_image_excerpt_width_style' ), 16, 3 ); //Edit the inline style on the excerpt
			add_filter( 'featured_image_pro_caption_inline_style', array( $this, 'no_image_caption_width_style' ), 15, 3 ); //Edit the inline style on the caption
			add_filter( 'featured_image_pro_image_inline_style', array ( $this, 'default_image_size' ), 15, 3 );   //Edit the inline style on the image
			add_filter( 'proto_masonry_item_inline_style', array(  $this, 'no_image_item_width_style' ), 15, 3 );    //Edit the inline style of the item
//			add_filter( 'pre_get_posts', array($this, 'proto_fix_pre_get_posts_query'), 99, 1 );
			add_filter( 'proto_masonry_gridstyles', array($this, 'masonry_gridstyles'), 15, 2 );
			$linkto     = isset( $args['linkto'] ) ? sanitize_text_field( $args['linkto'] ) : '';
			$displayas     = isset( $args['displayas'] ) ? sanitize_text_field( $args['displayas'] ) : '';

			if ($displayas != 'masonry')
			{
				//add_action( 'proto_masonry_enqueue_late',  array ('proto_masonry_advanced', 'masonry_advanced_enqueue_late' ) , 14, 1 );   //Additonal initialization (enqueue any additional scripts)
				add_filter( 'proto_masonry_grid_class', array( $this->masonry_advanced , 'proto_widget_class' ), 15, 2 ); //add a class to the widget for isotope
				if ( $displayas == 'isotope' || $displayas == 'filtered')
				{

					add_filter( 'proto_masonry_options', array( $this->masonry_advanced, 'proto_isotope_terms' ), 15, 2);     //core code to retrieve options
					add_filter ('proto_masonry_full_script', array($this->masonry_advanced , 'masonry_isotope_script'), 16, 3 );        //generate the full js script
					add_filter ('proto_masonry_insert_masonry_scripts', array( $this->masonry_advanced, 'masonry_isotope_button_script' ), 15, 2 );
					add_filter( 'proto_masonry_before_grid', array( $this->masonry_advanced, 'proto_masonry_isotope_navigation' ), 14, 3 );  //navigation for isotope
					add_filter( 'proto_masonry_item_post_class', array( $this->masonry_advanced, 'proto_masonry_istope_item_class' ) , 15, 3 ); //add a class to the masonry items
					add_filter( 'proto_snap_post_object', array( $this->masonry_advanced, 'proto_masonry_isotope_post_terms' ), 15, 4 ); //do something with the proto post object
					add_filter ('proto_masonry_attachments', array( $this->masonry_advanced, 'proto_taxonomy_count'), 15, 3 );
					add_filter( 'proto_masonry_after_grid', array( $this->masonry_advanced, 'proto_masonry_isotope_navigation_bottom' ), 15, 3 ); //Html displays after
				}
				add_filter( 'proto_masonry_script_options', array( $this->masonry_advanced , 'proto_masonry_advanced_script_options' ), 15, 2 ); //Get masonry javascript options


				//   if ($navtype == 'title')
				add_filter( 'proto_post', array( $this, 'proto_post_titles' ), 15, 3 );
				add_filter( 'proto_post', array( $this, 'proto_post_captions' ), 15, 3 );
			}
		}

		/**
		 * remove_filters function.
		 *
		 * @access public
		 * @static
		 * @param object  $this - class to remove filters from
		 * @return void
		 */
		public function remove_filters(  ) {
			remove_filter( 'proto_masonry_options', array( $this, 'masonry_options' ), 15 );       //Process additional plugin options specific to masonry
			remove_filter( 'proto_masonry_before_grid', array( $this, 'proto_masonry_navigation' ), 15 );   //Html displays before the grid (in the container)
			remove_filter( 'proto_masonry_after_grid', array( $this, 'proto_masonry_navigation_hidden_ajax' ), 15 ); //Html displays after the grid (in the container)
			remove_action( 'proto_masonry_enqueue_late', array( $this, 'proto_masonry_enqueue_late_advanced' ), 15 );   //Additonal initialization (enqueue any additional 			remove_filter( 'proto_masonry_before_items', array( $this, 'proto_masonry_gutter_sizer' ), 15 );  //Html displays before the grid item
			remove_filter( 'proto_masonry_itemstyles', array( $this, 'advanced_item_styles' ), 15 );     //Process any additional styles for the masonry items
			remove_filter( 'proto_inline_css', array( $this, 'advanced_styles' ), 15 );        //Process any additional inline css
			remove_filter( 'proto_masonry_attachments', array( $this, 'proto_navigation' ), 15 );     //Add something to the attachment object
			remove_filter( 'proto_masonry_image_output', array( $this, 'proto_masonry_hover_image' ), 15 );   //Add to an image/post object
			remove_filter( 'proto_snap_post_object', array( $this, 'proto_snap_get_default_img' ), 15 );   //Get the default image
			remove_filter( 'proto_masonry_settings', array( $this, 'proto_advanced_local_settings' ), 15 );     //Do some local customs stuff.
			remove_filter( 'proto_masonry_item_inline_style', array( $this, 'proto_masonry_item_inline_style_advanced' ), 15, 3 );  //Add some inline style to the item
			remove_filter( 'proto_wordpress_post_attributes', array( $this, 'proto_post_attributes' ), 15 );  //Customize the query attributes
			remove_action( 'proto_masonry_after_query', array( $this, 'proto_masonry_get_max_pages' ), 15 );  //Do stuff after the query
			remove_filter( 'proto_masonry_grid_class', array( $this, 'proto_masonry_grid_class' ), 15 );  //Add any additional classes to the widget
			remove_filter( 'proto_masonry_item_class', array($this, 'proto_masonry_item_class' ), 15 );
			remove_filter( 'proto_masonry_caption_class', array($this, 'proto_masonry_caption_class' ), 15 );
			remove_filter( 'proto_masonry_subcaption_class', array($this, 'proto_masonry_subcaption_class' ), 15 );
			remove_filter( 'proto_masonry_excerpt_class', array($this, 'proto_masonry_excerpt_class' ) , 15 );
			remove_filter( 'proto_masonry_image_class', array($this, 'proto_masonry_image_class' ) , 15 );
			remove_filter( 'proto_masonry_link_class', array($this, 'proto_masonry_link_class' ) , 15 );
			remove_filter( 'proto_masonry_parent_class', array( $this, 'proto_masonry_parent_class' ), 15 ); //Add any additional class to the top container
			remove_filter( 'proto_grid_container_class', array( $this, 'proto_masonry_container_class' ), 15 ); //Add any additional class to the top container
			remove_filter( 'proto_columnwidth_masonry_script', array( $this, 'masonry_column_width_script' ) , 15 ); //Change the columnwidth for percentposition
			remove_filter( 'featured_image_pro_excerpt_inline_style', array( $this, 'no_image_excerpt_width_style' ), 16 ); //Edit the inline style on the excerpt
			remove_filter( 'featured_image_pro_caption_inline_style', array( $this, 'no_image_caption_width_style' ), 15 ); //Edit the inline style on the caption
			remove_filter( 'featured_image_pro_image_inline_style', array ( $this, 'default_image_size' ), 15 );   //Edit the inline style on the image
			remove_filter( 'proto_masonry_item_inline_style', array($this, 'no_image_item_width_style' ), 15 );    //Edit the inline style of the item
//			remove_filter( 'pre_get_posts', array($this, 'proto_fix_pre_get_posts_query'), 99 );
			remove_filter( 'proto_masonry_gridstyles', array($this, 'masonry_gridstyles'), 15 );
		//	remove_action( 'proto_masonry_enqueue_late', array( $this->masonry_advanced , 'masonry_advanced_enqueue_late' ), 14 );   //Additonal initialization (enqueue any additional scripts)
			remove_filter( 'proto_masonry_grid_class', array( $this->masonry_advanced , 'proto_widget_class' ), 15 ); //add a class to the widget for isotope
			remove_filter( 'proto_masonry_options', array( $this->masonry_advanced, 'proto_isotope_terms' ), 15 );     //core code to retrieve options
			remove_filter ('proto_masonry_full_script', array($this->masonry_advanced , 'masonry_isotope_script'), 16 );        //generate the full js script
			remove_filter ('proto_masonry_insert_masonry_scripts', array( $this->masonry_advanced, 'masonry_isotope_button_script', 15));

			remove_filter( 'proto_masonry_before_grid', array( $this->masonry_advanced, 'proto_masonry_isotope_navigation' ), 14 );  //navigation for isotope
			remove_filter( 'proto_masonry_item_post_class', array( $this->masonry_advanced, 'proto_masonry_istope_item_class' ) , 15 ); //add a class to the masonry items
			remove_filter( 'proto_snap_post_object', array( $this->masonry_advanced, 'proto_masonry_isotope_post_terms' ), 15 ); //do something with the proto post object
			remove_filter ('proto_masonry_attachments', array( $this->masonry_advanced, 'proto_taxonomy_count'), 15 );
			remove_filter( 'proto_masonry_after_grid', array( $this->masonry_advanced, 'proto_masonry_isotope_navigation_bottom' ), 15 ); //Html displays after
			remove_filter( 'proto_masonry_script_options', array( $this->masonry_advanced , 'proto_masonry_advanced_script_options' ), 15 ); //Get masonry javascript options
			remove_filter( 'proto_post', array( $this, 'proto_post_titles' ), 15 );
			remove_filter ( 'proto_post', array( $this, 'proto_post_captions' ), 15 );

		}
		/*
		 * proto_masonry_get_grid_content with a container.
		 * This function mainly meant to be used by Ajax
		 * Get grid only with no container, ex. use for ajax
		 * @access public
		 * @param array $options - Array of options
		 * @param arrau $atts	 - Array of WordPress Query Attributes
		 * @return a complete grid (html)
		 */
		public function proto_masonry_get_grid_content( $options, $atts ) {
			$attobj = $this->proto_masonry_get_images( $options, $atts );
			$masonry = new proto_masonry();
			$ret = $masonry->proto_masonry_pro_grid( $attobj, $options, $atts );
			return $ret;
		}
		/* proto_masonry_get_grid_items - Get the grid items only with no container.
		 * This function was written to be used by ajax to append images to an existing grid.
		 * Get grid items content only no container
		 * @access public
		 * @param array $options - Array of options
		 * @param arrau $atts	 - Array of WordPress Query Attributes
		 * @return grid content (html)
		 */
		public function proto_masonry_get_grid_items( $options, $atts ) {
			$attobj = $this->proto_masonry_get_images( $options, $atts );
			$masonry = new proto_masonry();
			$ret = $masonry->proto_masonry_pro_items( $attobj->attachments, $options, $atts );
			return $ret;
		}
		/**
		 * proto_masonry_get_images function.
		 * Get the images object based on post type or online gallery
		 * @access public
		 * @param mixed $options
		 * @param mixed $atts
		 * @return void
		 */
		public function proto_masonry_get_images( $options, $atts ) {
			$post_type = isset( $atts['post_type'] ) ? $atts['post_type'] : '';
			$attobj = new stdClass();
			$attobj2 = apply_filters('proto_masonry_attachment_object', $attobj, $options, $atts );
			if ($attobj2 == $attobj)
			{
				if ( $post_type == 'attachment' )
				{
					$imgr = new media_gallery_pro_image_retrieve_03();
					$imgr->wordpress_set_options( $atts, $options );   //Get the attributes for posts
					$attobj = $imgr->wordpress_media_images($attobj, $atts, $options);
				}
				else
				{
					$imgr = new featured_image_pro_image_retrieve_03();     //This is the image function class
					$imgr->wordpress_set_options( $atts, $options );   //Get the attributes for posts
					$attobj = $imgr->wordpress_featured_images( $attobj, $atts, $options ); //Get the posts with attachments
				}
			}
			else
				$attobj = $attobj2;

			return $attobj;
		}
		/**
		 * proto_post_captions function.
		 *
		 * @access public
		 * @param mixed $proto_post
		 * @param mixed $querypost
		 * @param mixed $options
		 * @return void
		 */
		function proto_post_captions( $proto_post, $querypost, $options )
		{
			$sc = 1;
			while ( isset( $options["subcaption$sc"] ) && $options["subcaption$sc"] != '' )
			{
				$captionid = "subcaption$sc";
				/**
				 * title
				 *
				 * (default value: isset( $options["subcaptiontitle$sc"] ) ?  sanitize_text_field( $options["subcaptiontitle$sc"] ) : '')
				 *
				 * @var string
				 * @access public
				 */
				$title = isset( $options["subcaptiontitle$sc"] ) ?  sanitize_text_field( $options["subcaptiontitle$sc"] ) : '';
				$type = isset( $options["subcaptiontype$sc"] ) ?  sanitize_text_field( $options["subcaptiontype$sc"] ) : '';
				$cast = isset( $options["subcaptioncast$sc"]) ? sanitize_text_field( $options["subcaptioncast$sc"] ) : '';
				$captionoption  =  sanitize_text_field( $options[$captionid] );
				if ( !isset( $proto_post->subcaption[$captionoption] ) || $proto_post->subcaption[$captionoption] == '' )
				{
					if ($title != '')
						$proto_post->subcaptiontitle[$captionoption] = $title;
					$val = '';
					$val = apply_filters( 'proto_subcaption',  $val, $captionoption, $querypost->ID ); //first check for date and author
					if ($val != '')
						proto_cast_value($val, $cast);

					if ( ( $val == '' && $type == '' ) || $type=='default' )
					{
						switch ($captionoption){
						case 'ID':
							$val = $querypost->ID;
							if ($cast == '')
								$cast = 'int';
							break;
						case 'comment_count':
							$val = get_comments_number( $querypost->ID );
							if ($cast == '')
								$cast = 'int';
							break;
						case 'date':
						if ($cast == '')
								$cast = 'date';
							break;
						default:
							$cast = $cast == '' ? 'string' : $cast;
						}
						proto_cast_value($val, $cast);

					}
					if ( ( $val == '' && $type == '') || $type == 'meta' )
					{
						$val = isset( $proto_post->meta[$captionoption] ) ? $proto_post->meta[$captionoption] : ''; //check meta
						if ( is_array($val) )
							foreach ($val as $key=>$v)
								$val[$key] = proto_cast_value($v, $cast);
						else
							proto_cast_value($val, $cast);
					}

					if ( ( $val == '' && $type == '') || $type == 'imagemeta' )
					{
						//check image meta
						$val = isset( $proto_post->image_meta[$captionoption] ) ? $proto_post->image_meta[$captionoption] : '';
						proto_cast_value($val, $cast);
					}
					if ( ( ( $val == '' && $type == '' ) || $type == 'taxonomy' ) && taxonomy_exists( $captionoption ) )          //check taxonomy
						$val = wp_get_post_terms( $querypost->ID, $captionoption, array( "fields" => "names" ) );
					if (isset( $val ) && $val  != '' || ( is_array( $val ) && !empty( $val ) ) && !is_wp_error($val)  )
					{
						if ($cast == '')
							$cast = 'string';

						if ($title != '')
							$title .= ' ';
						if ( is_string($val ) )
						{
							$proto_post->subcaption[$captionoption] = $title != '' ? $title .  $val : $val;
							proto_cast_value($val, $cast);
						}
						else
							if ( is_array( $val ) && !empty ($val) )
							{

								$rval = array_unique( $val );
								if ( is_array( $rval ) )
									foreach ($rval as $key=>$v)
										$rval[$key] = proto_cast_value($v, $cast);
								else
									proto_cast_value($rval, $cast);

								$val = implode( ', ', $rval );
								$proto_post->subcaption[$captionoption] = $title .  $val ;
							}
					}
				}
				$sc++;
			}
			return $proto_post;
		}
		/**
		 * proto_post_titles function.
		 *
		 * @access public
		 * @param mixed $proto_post
		 * @param mixed $querypost
		 * @param mixed $options
		 * @return void
		 */
		function proto_post_titles($proto_post, $querypost, $options)
		{
			$next_post = get_next_post();
			$proto_post->next_title = isset($next_post) && $next_post != '' && $next_post->ID ? ($next_post->post_title) : '';
			$prev_post = get_previous_post();
			$proto_post->prev_title = isset($prev_post) && $prev_post != '' && $prev_post->ID ?  ($prev_post->post_title) : '';
			return $proto_post;
		}
		/**
		 * ajax_psp_masonry function.
		 * Generate grid content for ajax
		 *
		 * @access public
		 * @param array   $atts      - query attributes
		 * @param array   $options   - plugin options
		 * @param string  $direction - paging direction (or style)
		 * @param string  $nextpage  - nextpage
		 * @return grid content (without container)
		 */
		public function ajax_psp_masonry( $atts, $options, $direction, $nextpage ) {
			$uniqueId = $options['uniqueid'];
			$paged = intval( $atts['paged'] );

			switch ( $direction ) {
			case 'more':
			case 'next':
			case 'title':
				$paged = $paged+1;
				break;
			case 'prev':
				$paged = $paged-1;
				if ( $paged < 1 ) {
					$paged = 1;
				}
				break;
			case 'page':
			case 'dots':
				$paged = $nextpage;
			}
			$atts['paged'] = $paged;
			if ( proto_boolval( $options['debug_log'] ) ) {
		 		 proto_functions::debug_writer( '(paging) options', $options, false, $debug_log );
		 		 proto_functions::debug_writer( '(paging) atts', $options, false, $debug_log );
		 		 proto_functions::debug_writer( '(paging) direction', $direction, false, $debug_log );
		 		 proto_functions::debug_writer( '(paging) paged', $paged, false, $debug_log );

			}
			if ( $direction == 'more' )
				$return = $this->proto_masonry_get_grid_items( $options, $atts );
			else
				$return = $this->proto_masonry_get_grid_content( $options, $atts );
			return $return;
		}
		/**
		 * featured_image_generate_grid function.
		 * Create a grid  - Written to be called from external code
		 *
		 * @access public
		 * @param mixed   $atts     - query attributes
		 * @param mixed   $widgetid - unique id
		 * @return grid with class
		 */
		public function featured_image_generate_grid( $atts, $widgetid ) {
			$pspm    = new featured_image_pro_post_masonry( $atts, $widgetid ); //grid initialization, create scripts etc.
			$this->masonry_scripts = $pspm->scriptsObj;
			$post_type = isset( $pspm->atts['post_type'] ) ? $pspm->atts['post_type'] : '';  //Get the post type
			/* Special functionality for attachments */
			if ( $post_type == 'attachment'  )
			{
				$m_imgr = new media_gallery_pro_image_retrieve_03( );               //Create a class for attachments
				remove_filter( 'proto_masonry_attributes', array( $pspm->imgr, 'wordpress_post_attributes' ), 15 );    //replace code to process the core attributes
				remove_filter( 'proto_masonry_object', array( $pspm->imgr, 'wordpress_featured_images' ), 15 );     //replace core code to retrieve the images
				add_filter( 'proto_masonry_attributes', array( $m_imgr, 'wordpress_media_attributes' ), 15, 3 );
				add_filter( 'proto_masonry_object', array( $m_imgr, 'wordpress_media_images' ), 15, 3 );
			}
			$attobj = $pspm->featured_image_pro_get_details();    //Get the attachment object
			$ret = $pspm->grid_with_class($attobj);       //Generate the grid html
			return $ret;
		}

		/**
		 * default_image_size function.
		 * Get the size of the default image and update the css style
		 * @access public
		 * @param string $style - current style
		 * @param array $options - plugin settings
		 * @param object $proto_post - proto image attachment object
		 * @return void
		 */
		public function default_image_size( $style, $options, $proto_post )
		{
			if (!isset($proto_post->id) || intval($proto_post->id) < 1)
			{
				$usedefaultimage = proto_boolval($options['usedefaultimage']);
				if ($usedefaultimage)
				{
					$defaultimg = isset( $options['defaultimage'] ) ? sanitize_text_field( $options['defaultimage'] ) : '' ;
					if ($defaultimg)
					{
						$imagesize =  isset( $options['imagesize'] ) ? sanitize_text_field( $options['imagesize'] ) : 'thumbnail';
						$size  = $this->get_wp_image_size( $imagesize );
						$imagewidth = isset( $options['imagewidth'] ) ? sanitize_text_field( $options['imagewidth'] ) : $size[0];
						$imageheight = isset( $options['imageheight'] ) ? sanitize_text_field( $options['imageheight'] ) : $size[1];
						$style = $this->replace_styles( $style, 'width', $imagewidth );
						$style = $this->replace_styles( $style, 'height', $imageheight );
					}
				}
			}
			return $style;
		}
		/**
		 * get_wp_image_size function.
		 * Get information about an available wordpress image size
		 * @access public
		 * @param string $name (default: '')
		 * @return information about the image size or false if the image size does not exist
		 */
		public function get_wp_image_size( $name = '' ) {
			global $_wp_additional_image_sizes;
			if ( isset( $_wp_additional_image_sizes[$name] ) ) {
				return $_wp_additional_image_sizes[$name];
			} else {
				return false;
			}
		}
		/**
		 * no_image_item_width_style function.
		 * return the css styles for an item when there is no image
		 * @access public
		 * @param string $style - current cssstyle
		 * @param array $options - plugin settings
		 * @param object $proto_post - proto image attachment object
		 * @return style
		 */
		public function no_image_item_width_style( $style, $options, $proto_post )
		{
			$imagewidth = isset( $options['imagewidth'] ) ? sanitize_text_field( $options['imagewidth'] ) : '';  //Global Image Width setting
			$imageheight = isset( $options['imageheight'] ) ?  sanitize_text_field( $options['imageheight'] ) : ''; //Global Image height setting
			$itemwidth = isset( $options['itemwidth'] ) ?  sanitize_text_field( $options['itemwidth'] ) : ''; //Global Image height setting
			if (!$itemwidth)
			{
				if ($imagewidth)
					$imagewval = $imagewidth;
				else
					$imagewval = $proto_post->initialWidth ? $proto_post->initialWidth . 'px' : '150px';
				$itemwidth = $imagewval;
				$style = $this->replace_styles($style, 'width', $itemwidth);
			}
			return $style;
		}
		/**
		 * no_image_excerpt_width_style function.
		 * return the css styles for the excerpt when there is no image
		 * @access public
		 * @param string $style - current cssstyle
		 * @param array $options - plugin settings
		 * @param object $proto_post - proto image attachment object
		 * @return style
		 */
		public function no_image_excerpt_width_style( $style, $options, $proto_post )
		{
			return $this->no_image_width_style($style, $options, $proto_post);
		}
		/**
		 * no_image_excerpt_width_style function.
		 * return the css styles for the excerpt when there is no image
		 * @access public
		 * @param string $style - current cssstyle
		 * @param array $options - plugin settings
		 * @param object $proto_post - proto image attachment object
		 * @return style
		 */
		public function no_image_caption_width_style( $style, $options, $proto_post )
		{
			return $this->no_image_width_style($style, $options, $proto_post);
		}
		/**
		 * no_image_excerpt_width_style function.
		 * return the css styles for the excerpt when there is no image
		 * @access public
		 * @param string $style - current cssstyle
		 * @param array $options - plugin settings
		 * @param object $proto_post - proto image attachment object
		 * @return style
		 */
		public function no_image_width_style( $style, $options, $proto_post )
		{
			$percentposition = isset( $options['percentposition'] ) ? proto_boolval( $options['percentposition'] ) : false;
			$responsive = isset( $options['responsive'] ) ? sanitize_text_field( $options['responsive'] ) : '';
			if ( ( !$proto_post->id && proto_boolval( $options['show_noimage_posts'] ) == true ) || $responsive  != '' ||  $percentposition )
			{
				$style = $this->replace_styles($style, 'width', '100%');
			}
			return $style;
		}
		/**
		 * replace_styles function.
		 *
		 * @access public
		 * @param mixed $style
		 * @param mixed $stylename
		 * @param mixed $stylevalue
		 * @return void
		 */
		public function replace_styles( $style, $stylename, $stylevalue )
		{
			$styles = explode( ';' , $style );
			$styles = array_map('trim', $styles);
			$index = -1;
			foreach ( $styles as $key=>$st )
			{
				$sstyle=explode( ':' , $st );
				$sstyle = array_map('trim', $sstyle);
				if ( $sstyle[0] == $stylename )
				{
					$index = $key;
					break;
				}
			}
			if ($index >= 0)
				$styles[$index] = "$stylename:$stylevalue";
			else
				$styles[] = "$stylename:$stylevalue";

			return implode( ';', $styles);
		}
		/**
		 * masonry_column_width_script function.
		 *
		 * @access public
		 * @param mixed $script
		 * @param mixed $options
		 * @return void
		 */
		public function masonry_column_width_script( $script, $options )
		{
			$columnwidth = isset( $options['columnwidth'] ) ? sanitize_text_field( $options['columnwidth'] ) : '';
			$uniqueId = $options['uniqueid'];
			if ( $columnwidth == 'sizer' )
				$script .= "columnwidth: '.item_sizer_{$uniqueId}',";
			return $script;
		}
		/**
		 * proto_masonry_get_max_pages function.
		 * Get the maximum number of pages
		 *
		 * @access public
		 * @param mixed   $_query
		 * @param mixed   $atts
		 * @return void
		 */
		public function proto_masonry_get_max_pages( $_query, $atts ) {
			$this->max_pages = $_query->max_num_pages;
		}
		/**
		 * proto_snap_get_default_img function.
		 * Get a default image for the image object if the post doesn't have an image
		 *
		 * @access public
		 * @param object $img     object - proto image object
		 * @param array   $options - plugin options
		 * @param object $post - wordpress post object
		 * @return void
		 */
		public function proto_snap_get_default_img( $proto_post, $options, $post ) {
			$usedefaultimage = proto_boolval($options['usedefaultimage']);
			if ( $usedefaultimage )
			{
				$id = isset( $proto_post->id ) && $proto_post->id != ''  ? $proto_post->id : -1;
				$imagesize = isset ( $options['imagesize'] ) ? sanitize_text_field( $options['imagesize'] ) : 'thumbnail';
				if ( $id <= 0 ) {
					//No image and no default image id was set, so check for the defaultimg link setting
					$defaultimg = isset( $options['defaultimage'] ) ? sanitize_text_field( $options['defaultimage'] ) : '';
					$id = isset( $options['defaultimageid'] ) ? sanitize_text_field( $options['defaultimageid'] ) : '';
					if ( $id ) {
						if ( intval( $id ) > 0 ) {
							$imgobj              = wp_get_attachment_image_src( $id, $imagesize );//Link to selected Image size
							if ( $imgobj )
							{
								$proto_post->id = $id;
								$proto_post = proto_functions::get_proto_post_sizes($proto_post, $imgobj, $imagesize);
							}
							else
								$proto_post->id = 0;
						}
					}
					if (  !isset($proto_post->id) || $proto_post->id <= 0 ) {
						$size = $this->get_wp_image_size( $options[ 'imagesize' ]);
						if ( $defaultimg ) {
							try
							{
								$size = getimagesize( $defaultimg );//get the image size
							}
							catch ( Exception $ex ) {}
						}
						$proto_post->width  = $size[0];//the image width
						$proto_post->height = $size[1];//the image height
						$proto_post->thumbnail_url = $defaultimg;//set the thumnail
						$proto_post->large_url     = $defaultimg;//set the large image
						$proto_post->img_url       = $defaultimg;//set the requested image size
					}
				}
			}
			$proto_post->initialWidth = isset( $proto_post->width ) ? $proto_post->width : '';
			return $proto_post;
		} /**
		 * proto_navigation function.
		 *  navigation content for the grid
		 *
		 * @access public
		 * @param object  $attobject - attribute object (images and navigation)
		 * @param array   $options   - plugin options
		 * @param object  $_query    - query object
		 * @return attobject with navigation content
		 */
		public function proto_navigation( $attobject, $options, $_query ) {

			$navtype = isset( $options['navtype'] ) ? sanitize_text_field( $options['navtype'] ) : '';
			$uniqueid = sanitize_text_field( $options['uniqueid'] );

			if ( isset( $options['bypage'] ) && proto_boolval( $options['bypage'] ) ) {
				switch ( $navtype ) {
				case 'dots':
					$links = $this->get_nav_pages( $navtype, true );
					break;
				case 'page_numbers':
					$links = $this->get_nav_pages( $navtype );
					break;
					//case 'infinite':
					//wp_enqueue_scripts( 'proto_masonry_infinite_scroll' );
				case 'more':
				case 'infinity':
					$links = $this->get_nav_content_more( $navtype, $uniqueid );
					break;
				case 'title':
					$links = $this->get_nav_content_titles($attobject);
					break;
				default:
					$next_nav_text = isset( $options['next_nav_text'] )?sanitize_text_field( $options['next_nav_text'] ):'';
					$prev_nav_text = isset( $options['prev_nav_text'] )?sanitize_text_field( $options['prev_nav_text'] ):'';
					$links = $this->get_nav_content( $next_nav_text, $prev_nav_text, $navtype );
					break;
				}
				$attobject->navcontenttop = "<div id='nav-above-$uniqueid' class='nav-above navigation featured_image_propost-navigation featured_image_propost-nav-above'>
                    $links
            </div>";
				$attobject->navcontentbottom = "<div id='nav-below-$uniqueid' class='nav-below navigation featured_image_propost-navigation featured_image_propost-nav-below'>
                    $links
            </div>";
			} else {
				$attobject->navcontenttop    = '';
				$attobject->navcontentbottom = '';
			}

			return $attobject;
		}
		/**
		 * proto_masonry_hover_image function.
		 * attach code for the to add a hover image that shows up when you mouse over an image in the grid
		 *
		 * @access public
		 * @param string  $imageoutput - the html source for the image
		 * @param object  $proto_post  - attachment information for the image
		 * @param array   $options     - plugin options
		 * @param string  $imgstyle    - should be height & width of the main image
		 * @return modified html element for the image
		 */
		public function proto_masonry_hover_image( $imageoutput, $proto_post, $options) {
			$prefix = sanitize_text_field( $options['prefix'] );
			$hoverimage = proto_boolval( $options['hoverimage'] );//Hover Image
			$uniqueId = $options['uniqueid'];
			/* End Advanced*/
			if ( $hoverimage ) {
				$imageoutput .= "<span class='{$prefix}_hover_image proto_hover_image proto_masonry_hover_image_$uniqueId  {$prefix}_hover_image_{$uniqueId} '
			 ></span>";
			}
			return $imageoutput;
		}
		/**
		 * proto_masonry_gutter_sizer function.
		 * add elements before the grid items
		 *
		 * @access public
		 * @param string  $beforeitems - html in the grid but before the items
		 * @param array   $options     - plugin options
		 * @return html before the items
		 */
		public function proto_masonry_gutter_sizer( $beforeitems, $options ) {
			$percentposition = isset ( $options['percentposition'] ) ?  proto_boolval( $options['percentposition'] ) : '';
			$prefix = sanitize_text_field( $options['prefix'] );
			if ( $percentposition ) {
				$beforeitems .= '<div class="' . $prefix . '_masonry_gutter-sizer proto_masonry_gutter_sizer"></div>';
			}
			return $beforeitems;
		}
		/**
		 * proto_advanced_local_settings function.
		 * set some local options & get advanced attributes
		 *
		 * @access public
		 * @param array   $atts    - query attributes
		 * @param array   $options - plugin options
		 * @return updated query attributes
		 */
		public function proto_advanced_local_settings( $atts, $options ) {
			$this->front_page = $options['front_page'];
			$this->base_url   = esc_url( $options['base_url'] );
			$this->ajaxpage = proto_boolval( $options['ajaxpage'] );
			$this->debug = isset( $options['debug'] ) ? proto_boolval( $options['debug'] ) : false;
			$this->debug_query = isset( $options['debug_query'] ) ? proto_boolval( $options['debug_query'] ) : false;
			$this->debug_log = isset( $options['debug_log'] ) ? proto_boolval( $options['debug_log'] ) : false;
			$atts           = $this->get_page_settings( $atts, $options );
			return $atts;
		}
		/**
		 * get_page_settings function.
		 * Get the current query attributes for paging
		 *
		 * @access public
		 * @param array   $atts    - query attributes
		 * @param array   $options - plugin options
		 * @return query attributes (with navigation)
		 */
		function get_page_settings( $atts, $options ) {
			if ( $options['bypage'] && $options['ajaxpage'] && isset( $atts['paged'] ) ) { //get the currrent page if ajax
				$this->paged = $atts['paged'];
			} else {
				$bypage = isset( $options['bypage'] ) ? proto_boolval( $options['bypage'] ):0;// check for the bypage option setting
				if ( is_front_page() ) {
					$get_var = "page";
				}
				//On the front page, the query var is page instead of paged
				else
					if ( $this->is_single ) {
						$get_var = 'psp_masonry_page';
					}
				//This attempt to enable paging on single pages. Not being used.
				else {
					$get_var = "paged";
				}
				if ( $bypage )//calculate the next page if the bypage option hs been set. Set the page for the query
					{
					$this->paged = ( get_query_var( $get_var ) )?get_query_var( $get_var ):1;
				}
				//It's possible that the user did not set 'bypage' but set 'page' or 'paged' instead.
				if ( $this->paged == 0 )//If paged still hasn't been determined, one last double check to see if page/paged has been set. return zero if not set,
					{ $this->paged = ( get_query_var( $get_var ) )?get_query_var( $get_var ):0;
				} else {
					$bypage = TRUE;
				}
				//make sure that this is set in case 'page' or 'paged' was set instead of bypage
				if ( $this->paged > 0 )//set everything
					{
					$atts['paged']     = $this->paged;
					$options['bypage'] = $bypage;
				}
			}
			return $atts;
		}
		/**
		 * get_previous_posts_link_url function.
		 * Generate a link for previous posts
		 *
		 * @access public
		 * @param mixed   $xpaged   - current page
		 * @param mixed   $prev_nav_text - text for previous page
		 * @return link to previous posts
		 */
		function get_previous_posts_link_url( $xpaged, $prev_nav_text ) {
			$link = "";
			if ( !is_single() && !$this->ajaxpage )//If it's not a single page or using ajax use the default code
				{ $link = get_previous_posts_link( $prev_nav_text );
			} else {//otherwise, a code needs to be generated.
				$prevtxt = '';
				if ( $xpaged == 0 ) {
					$xpaged = 1;
				}
				//Figure out what the previous page (link to newer posts) is
				$prevpage = intval( $xpaged )-1;
				if ( $prevpage < 1 )//Make sure it's not less than the first page
					{ $prevpage = 1;
				}
				if ( $xpaged != $prevpage )//Generate a new link if the page is different
					{ $link = $this->generate_nav_page_link( $prevpage, $prev_nav_text );
				}
			}
			return $link;
		}
		/**
		 * get_next_posts_link_url function.
		 * Create a link for a link to the 'next' posts - normally older posts
		 *
		 * @access public
		 * @param string  $xpaged   - current page
		 * @param string  $max      - maxium pages
		 * @param string  $next_nav_text - text for next page
		 * @return link
		 */
		function get_next_posts_link_url( $xpaged, $max, $next_nav_text ) {//usually older posts
			$link = "";
			if ( !is_single() && !$this->ajaxpage )//if not ajax or a single page, create link as always
				{
				$link = get_next_posts_link( $next_nav_text, $max );
			} else {
				$next = '';
				if ( $xpaged == 0 ) {
					$xpaged = 1;
				}
				$nextpage = intval( $xpaged )+1;//Calculate the next page (usually older posts)
				if ( $nextpage > $max ) {
					$nextpage = $max;
				}
				//Make sure it's not greater than the last page
				if ( $xpaged != $nextpage )//Generate a new link if the page is different
					{
					$link = $this->generate_nav_page_link( $nextpage, $next_nav_text );
				}
			}
			return $link;
		}
		/**
		 * generate_nav_page_link function.
		 * Generate a new page link
		 *
		 * @access public
		 * @param string  $nextpage
		 * @param string  $text
		 * @return a link to a page
		 */
		function generate_nav_page_link( $nextpage, $text ) {
			if ( $this->ajaxpage )//override the admin link that
				//comes up when paging using ajax
				{
				$link = $this->base_url;//This is the permalink which has been stored
				if ( !$this->is_single )//Add the query var
					{
					$key  = $this->front_page?'page':'paged';
					$link = esc_url( add_query_arg( $key, $nextpage, $link ) );
				}
			} else {
				//Just use regular code to get the link
				{
					$link = get_pagenum_link( $nextpage );
				}
			}
			if ( $this->is_single )//add the query arg page/paged to the link
				{
				$link = str_replace( 'paged', 'psp_masonry_page', $link );
				//$link = add_query_arg('psp_masonry_page', $nextpage, $link);
			}
			$next = "<a class='proto_link' alt='page " . $nextpage . "' href='$link'>$text</a>";
			return $next;
		}
		/**
		 * get_nav_content function.
		 * get the content for next & previous navigation links
		 *
		 * @access public
		 * @param string  $next_nav_text
		 * @param string  $prev_nav_text
		 * @return navigation content
		 */
		function get_nav_content( $next_nav_text, $prev_nav_text, $navtype='navigation' ) {// Normally next = older, prev = newer
			$content = "";
			if ( isset( $this->paged ) ) {
				if ( $this->paged > 0 && $this->max_pages > 0 ) {
					$prev = $this->get_previous_posts_link_url( $this->paged, $prev_nav_text );
					$next = $this->get_next_posts_link_url( $this->paged, $this->max_pages, $next_nav_text );
					$content = "
                    <div class='nav-previous proto_nav_$navtype proto_nav_{$navtype}_previous proto_image_pro_nav_previous'>$prev</div>
                    <div class='nav-next proto_nav_$navtype proto_nav_{$navtype}_next proto_image_pro_nav_next'>$next</span></a></div>";
				}
			}
			return $content;
		}
		/**
		 * get_nav_content_titles function.
		 *
		 * @access public
		 * @param mixed $_query
		 * @return void
		 */
		function get_nav_content_titles($_query)
		{
		}
		/**
		 * get_nav_content_more function.
		 *
		 * @access public
		 * @return void
		 */
		function get_nav_content_more( $type='more', $uniqueid = '') {
			$id =  $uniqueid != '' ? "id='proto_nav_more_$uniqueid'" : '';

			$more = $type=='more' ? $this->get_next_posts_link_url( $this->paged, $this->max_pages, __( 'More', 'proto-masonry' ) ) : '';
			return "<div $id class='nav-more proto_nav_more proto_nav_more proto_image_pro_nav_more'>$more</div>";
		}
		/**
		 * get_nav_pages function.
		 * get the content for navigation pages or dots
		 *
		 * @param boolean dots - true when dots
		 * @access public
		 * @return navigation content
		 */
		function get_nav_pages( $navtype, $dots = false ) {// Normall next = older, prev = newer
			$navtext = "";
			if ( $this->max_pages > 0 ) {
				for ( $i=1; $i <= $this->max_pages; ++$i ) {
					$ext  = '';
					if ( $navtext )
						$navtext .= ' ';
					if ( $dots ) {
						$str = '';
						$ext = '_dots';
					}
					else
						$str = $i;
					if ( $i != intval( $this->paged ) ) {
						$navtext .= "<span class='proto_page_nav{$ext}' title='page {$i}' data-page='$i'>" . $this->generate_nav_page_link( $i, $str ) . '</span>' ;
					}
					else {
						$x = ( $dots ) ? '' : $i;
						$navtext .= "<span class='proto_page_nav{$ext}  proto_page_nav{$ext}_current proto_page_nav_current'>$x</span>";
					}
				}
			}
			return $navtext;
		}
		/**
		 * proto_masonry_item_inline_style_advanced function.
		 * Apply itemwidth to the item style
		 *
		 * @access public
		 * @param string  $itemstyle  - current inline item style
		 * @param array   $options    - widget otpions
		 * @param object  $proto_post - attachment object (not used here, but sent via apply_filter)
		 * @return updated item style
		 */
		public function proto_masonry_item_inline_style_advanced( $itemstyle, $options, $proto_post ) {
			$itemwidth = $options['itemwidth'];
			$maxwidth = $options['maxwidth'];
			if ( $itemwidth )//If no global image width add styles for the image, caption and excerpt
				{ $itemstyle .= "width:$itemwidth;";
			}
			if ( $maxwidth ) {
				$maxstyle = "max-width:$maxwidth;";
				$itemstyle .= str_replace( $maxstyle, '', $itemstyle );
			}
			return $itemstyle;
		}
		/**
		 * proto_masonry_enqueue_late_advanced function.
		 * enqueue  scripts
		 *
		 * @access public
		 * @param array   $options - plugin options
		 * @return void
		 */
		public function proto_masonry_enqueue_late_advanced( $options ) {
			wp_enqueue_style( 'featured-image-pro-advanced-styles' );

			if ( isset ( $options['ajaxpage'] ) && isset ( $options['bypage'] ) && proto_boolval( $options['ajaxpage'] ) && proto_boolval( $options['bypage'] ) ) {
				global $ajaxurl;

				$params = array(
					'ajaxurl' => admin_url('admin-ajax.php'),
	//				'ajax_nonce' => wp_create_nonce('featured_image_pro'),
				);
				wp_enqueue_script( 'ajax_proto_posts' );
				if ( !$ajaxurl ) {
					wp_localize_script( 'ajax_proto_posts', 'ajax_proto_posts', $params );
				}
				$ajaxurl  = true;
			}
			$linkto = isset( $options['linkto'] ) ? esc_attr( $options['linkto'] ) : '';
			$displayas     = isset( $options['displayas'] ) ? sanitize_text_field( $options['displayas'] ) : '';
			wp_enqueue_script( 'imagesloaded' );
			proto_functions::append_dependency( 'featured-image-pro', 'imagesloaded' );
			if ( $displayas == 'isotope')
			{
				wp_enqueue_script('proto_masonry_advanced');
			}
			if ( $displayas == 'isotope'  || $displayas == 'filtered')
			{
				wp_enqueue_style ( 'featured-image-pro-isotope_styles' );
				wp_enqueue_script( 'proto_isotope' );
				proto_functions::append_dependency( 'featured-image-pro' , 'proto_isotope' );

			}
	}

		/**
		 * proto_masonry_grid_class function
		 * add class to the widget
		 * @access public
		 * @param string $class
		 * @param array $options
		 * @return string of classes
		 */
		public function proto_masonry_grid_class( $class, $options )
		{
			return proto_functions::elementclass($class, $options, 'gridclass');
		}
		/**
		 * proto_masonry_item_class function
		 * add class to the widget item
		 * @access public
		 * @param string $class
		 * @param array $options
		 * @return string of classes
		 */
		public function proto_masonry_item_class( $class,  $options )
		{
			$class = proto_functions::elementclass( $class, $options, 'itemclass' );
			if ( $options['percentposition'] == true )
				$class .= ' proto_percentposition';
			return $class;
		}
		/**
		 * proto_masonry_image_class function.
		 *
		 * @access public
		 * @param mixed $class
		 * @param mixed $options
		 * @return void
		 */
		public function proto_masonry_image_class( $class, $options )
		{
			return proto_functions::elementclass($class, $options, 'imageclass');
		}
		/**
		 * proto_masonry_parent_class function.
		 * add class to the top container (container for the grid)
		 * @access public
		 * @param string $class
		 * @param array $options
		 * @return string of classes
		 */
		public function proto_masonry_parent_class( $class, $options )
		{
			return proto_functions::elementclass($class, $options, 'parentclass');
		}
		/**
		 * proto_masonry_container_class function.
		 * add class to the container (container for the grid)
		 * @access public
		 * @param string $class
		 * @param array $options
		 * @return string of classes
		 */
		public function proto_masonry_container_class( $class, $options )
		{
			return proto_functions::elementclass($class, $options, 'containerclass');
		}
		/**
		 * proto_masonry_caption_class function.
		 * add class to the caption (container for the grid)
		 * @access public
		 * @param string $class
		 * @param array $options
		 * @return string of classes
		 */
		public function proto_masonry_caption_class( $class, $options )
		{
			return proto_functions::elementclass($class, $options, 'captionclass');
		}

		/**
		 * proto_masonry_caption_class function.
		 * add class to the caption (container for the grid)
		 * @access public
		 * @param string $class
		 * @param array $options
		 * @return string of classes
		 */
		public function proto_masonry_subcaption_class( $class, $options )
		{
			return proto_functions::elementclass($class, $options, 'subcaptionclass');
		}

		/**
		 * proto_masonry_excerpt_class function.
		 * add class to the caption (container for the grid)
		 * @access public
		 * @param string $class
		 * @param array $options
		 * @return string of classes
		 */
		public function proto_masonry_excerpt_class( $class, $options )
		{
			return proto_functions::elementclass($class, $options, 'excerptclass');
		}


		/**
		 * proto_masonry_navigation function.
		 * prepend navigation to the top of the grid container
		 *
		 * @access public
		 * @param string  $output  - above grid content
		 * @param object  $attobj  (default: null) - proto attribute object of images and navigation
		 * @param array   $options (default: array()) - plugin options
		 * @return content
		 */
		public function proto_masonry_navigation( $output, $attobj = null, $options = array()) {
			$top_page_nav = proto_boolval( $options['top_page_nav'] );
			$bypage = proto_boolval( $options['bypage'] );
			if ( $bypage && $top_page_nav ) {
				$output .= $attobj->navcontenttop;
			}
			return $output;
		}
		/**
		 * proto_masonry_navigation_hidden_ajax function.
		 * append navigation & hidden fields for ajax to the grid container
		 *
		 * @access public
		 * @param string  $xoutput
		 * @param object  $attobj  (default: null)
		 * @param array   $options (default: array())
		 * @param array   $atts    (default: array())
		 * @return content
		 */
		public function proto_masonry_navigation_hidden_ajax( $xoutput, $attobj = null, $options = array(), $atts=array() ) {
			$prefix = sanitize_text_field( $options['prefix'] );
			$output = "";
			$uniqueid = sanitize_text_field( $options['uniqueid'] );
			$ajaxpage = proto_boolval( $options['ajaxpage'] );
			$bypage = proto_boolval( $options['bypage'] );
			$navtype = sanitize_text_field( $options['navtype'] );
			$itemid = "#masonry-widget_{$uniqueid}";
			$max = $this->max_pages;
			$page = $this->paged;
			if ( $bypage ) {
				$output .= "<div id='proto_paging_items_$uniqueid' class='proto_paging_items' data-ajaxpage='$ajaxpage' data-page='$page' data-max_pages='$max' data-navtype='$navtype' data-itemid='$itemid' data-prefix='$prefix' data-id='$uniqueid'>";
				$output .= "<input type='hidden' id='atts' value='".json_encode( $atts )."'></input>";
				$output .= "<input type='hidden' id='options' value='".json_encode( $options )."'></input>";
				$output .= "<input type='hidden' id='nonce' value='".wp_create_nonce( 'page'.$uniqueid )."'></input>";
				$output .= "</div>";
			}
			$bottom_page_nav = proto_boolval( $options['bottom_page_nav'] );
			if ( $bypage && $bottom_page_nav ) {
				$output .= $attobj->navcontentbottom;
			}
			return $xoutput . $output;
		}
		/**
		 * masonry_options function.
		 * extract/add advanced options and merge them into  the options array
		 *
		 * @access public
		 * @param array   $options - settings
		 * @param array   $args - plugin arguments
		 * @return options (settings)
		 */
		function masonry_options( $options, $args ) {
			global $post;
			//check for responsive & do some settings stuff before parsing the options
			$percentposition = false;
			if ( isset( $args['responsive'] ) && $args['responsive'] != '' ) {//basic setting
				$percentposition = true;
				$itemwidth       = sanitize_text_field( $args['responsive'] );
				if (is_numeric($args['responsive']))
					$itemwidth = $itemwidth . '%';

			} else {
				$percentposition = isset( $args['percentposition'] )?proto_boolval( $args['percentposition'] ):false;
				$itemwidth       = isset( $args['itemwidth'] );
			}
			if ( $percentposition ) {
				$args['imageheight'] = isset( $args['imageheight'] ) ? sanitize_text_field( $args['imageheight'] ):'auto';
				$args['imagewidth'] =  isset( $args['imagewidth'] ) ? sanitize_text_Field( $args['imagewidth'] ) : '100%';
			}
			$navtype = isset( $args['navtype'] ) ? sanitize_text_field( $args['navtype'] ) : 'navigate';
			$next_nav_text   = ( $navtype == 'navigate' ) ? '<< Older Entries' : '<';
			$prev_nav_text   = ( $navtype == 'navigate' ) ?'Newer Entries >>' : '>';
			//Extract permium objects from the initial array
			$displayas = isset( $args['displayas'] ) ? sanitize_text_Field( $args['displayas'] ) : 'masonry';
			if ($displayas == 'filtered') $displayas = 'isotope';
			$options['displayas'] = $displayas;

			$masonry_options = shortcode_atts(
				array( 'percentposition' => $percentposition, //masonry setting
					'originleft'    => null, //masonry setting
					'origintop'     => null, //masonry setting
					'containerstyle'=> null, //masonry setting
					'transitiontype' => 'css',
					'transitionduration' => '.7s',
					//'horizontalorder' => null,
					'resize'        => null, //masonry setting
					//'stagger'       => null, //stagger is a css millisecond number like 30
					'initlayout'    => true, //defaults to true
					'stamp'         => null, //not used, but will create javascript
				), $args );
			$options = array_merge( $options, $masonry_options );
			$transitiontype = isset( $options['transitiontype'] ) ? sanitize_text_field( $options['transitiontype'] ) : 'css';
			$transitionduration = isset( $options['transitionduration'] ) ? sanitize_text_field($options['transitionduration'] ) : '';
			if (proto_boolval($options['animate']))
				$transitiontype = 'css';
			switch ($transitiontype)
			{
			case 'stagger':
				$options['stagger'] = isset( $options['stagger'] ) ? sanitize_text_field( $options['stagger'] ) : $transitionduration;
				$options['animationduration'] = '0s';  //set animation duration to nothing so code is generated to keep css animations from happening if coming from elsewhere.
				$options['transitionduration'] = null;
				$options['animate'] = true;
				break;
			case 'js':
				$options['transitionduration'] = $transitionduration;
				$options['animationduration'] = '0s';  //set animation duration to nothing so code is generated to keep css animations from happening if coming from elsewhere.
				$options['animate'] = true;
				break;
			case 'none':
				$options['animate'] = false;
				$options['transitionduration'] = 0;
				$options['stagger'] = '0';
				break;
			default:
				$options['animate'] = true;
				$options['animationduration'] = isset( $options['animationduration'] ) ? sanitize_text_field($options['animationduration'] ) : $options['transitionduration'];
				$options['transitionduration'] = 0;
				break;
			}
			unset($options['transitiontype'] );
			$noptions = shortcode_atts( array(
					'itemwidth'     => $itemwidth, //For grid items with
					'sizerwidth'    => '', //grid sizer width)
					'filteredtaxonomies' => '', //comma delimited. default to the category taxonomy\
					'filteredmenuposition' => 'top', //left right bottom top
					'filteredmenuwidth' => '100%',
					'filteredmenuwidth_left' => '',
					'filteredmenuwidth_right' => '',
					'filteredmenuwidth_bottom'=> '',
					'filteredmenuwidth_top'=> '',
					'containerclass' => '',//container class
					'captionclass'  => '', //caption class
					'subcaptionclass' => '',
					'excerptclass'  => '', //excerpt class
					'gridclass'    => '', //widget class
					'parentclass' => '', //top container class
					'imageclass'    => '', //Class for the image
					'itemclass'     => '', //Item Class
					'linkclass'   => '', //Link class
					'responsive'    => '', //See if the grid is repsonsive
					'bypage'        => FALSE, //Page items
					'ajaxpage'      => TRUE, //Use Ajax when paging
					'top_page_nav'        => FALSE, //paging navigation on top of grid
					'bottom_page_nav'     => TRUE, //paging navigation on bottom of grid
					'navtype'  => $navtype, //type of navigation
					'next_nav_text'      => $next_nav_text, //next page text
					'prev_nav_text'      => $prev_nav_text, //prev page text
					'target'        => '', //target
					'hoverimage'    => FALSE, //display an image on hover
					'hoverlink'     => plugins_url( 'img/magnify.png', __FILE__ )  , //url location of image
					'base_url'      => isset ($post->ID ) && null != get_permalink( $post->ID )  ? get_permalink( $post->ID )  : '',
					'front_page'    => is_front_page(), //set for front page for ajax navigation
					'usedefaultimage' => false,
					'defaultimage'  => includes_url().'/images/media/default.png',
					'defaultimageid'=> 0, //default image id
					'fadeintime'    => '500', //amount of time to fade in the images on initail load
					'linkto'   => 'post', //open the post on click
					'show_noimage_posts' => null,//display posts without images
					'has_thumbnails' => null, //


					//'relayout' => true,            //Layout a second time after the images are loaded and before displaying initial grid items
				), $args );
			$options = array_merge( $options, $noptions );
			if ( $percentposition ) {
				$columnwidth       = isset( $args['columnwidth'] )?intval( $args['columnwidth'] ):'sizer';
				$gutter            = isset( $args['gutter'] )?sanitize_text_field( $args['gutter'] ):'5';
				$options['gutter'] = $gutter;
				if ( $gutter != '0' ) {
					$options['itemwidth'] = 'calc('.$options['itemwidth'].' - '.$gutter.'px)';
				}
				$options['fitwidth']    = false;
				$options['imagewidth']  = '100%';
				$options['columnwidth'] = 'sizer';
				$options['sizerwidth']  = isset( $args['sizerwidth'] )?$args['sizerwidth']:$options['itemwidth'];
			}
			$percentposition = proto_boolval( $options['percentposition'] );
			if ( isset( $this->options['responsive'] ) && $options['responsive'] != '' )
				$options['percentposition'] = true;
			if ( isset( $args['paged'] ) && intval( $args['paged'] ) >= 0 )
				$options['bypage'] = true;
			$linkto = isset( $options['linkto'] ) ? esc_attr( $options['linkto'] ) : '';


			$sc = 1;
			while ( isset( $args["subcaption$sc"] ) )
			{
				$options["subcaption$sc"] = $args["subcaption$sc"];
				if ( isset( $args["subcaptiontitle$sc"] ) )
					$options["subcaptiontitle$sc"] = $args["subcaptiontitle$sc"];
				if ( isset ( $args["subcaptiontype$sc"] ) )
					$options["subcaptiontype$sc"] = $args["subcaptiontype$sc"];
				if ( isset ( $args["subcaptioncast$sc"] ) )
					$options["subcaptioncast$sc"] = $args["subcaptioncast$sc"];
				unset ( $args["subcaption$sc"] );
				unset ( $args["subcaptiontitle$sc"] );
				unset ( $args["subcaptiontype$sc"] );
				unset ( $args["subcaptioncast$sc"] );
				$sc++;
			}
			if ( isset( $args['taxonomies'] ) ) {
				$options['taxonomiesx'] = $args['taxonomies'];
				$taxarray = explode( ",", $args['taxonomies'] );
				$taxarray = array_map('trim', $taxarray);
				foreach ( $taxarray as $taxonomy ) {
					$tax_query = array( 'taxonomy' => $taxonomy );
					if ( isset( $args[$taxonomy."_terms"] ) ) {
						$options[$taxonomy.'_termsx'] = $args[$taxonomy.'_terms'];
					}
				}
			}

			return $options;
		}
			/**
		 * proto_fix_pre_get_posts_query function.
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
		}
		/**
		 * advanced_item_styles function.
		 * Update item css inline style
		 *
		 * @access public
		 * @param string  $itemstyles - current styles
		 * @param array   $options    - plugin options
		 * @return updated item styles
		 */
		function advanced_item_styles( $itemstyles, $options ) {
			if ( $options['itemwidth'] ) {
				$itemwidth = sanitize_text_field( $options['itemwidth'] );
				$itemstyles .= "
            width: $itemwidth;
            max-width: 100%;
            ";
			}
			return $itemstyles;
		}
		/**
		 * advanced_styles function.
		 * add to the styles block
		 *
		 * @access public
		 * @param string  $styles  - current styles
		 * @param array   $options - plugin options
		 * @return void
		 */
		function advanced_styles( $styles, $options ) {
			$uniqueId = $options['uniqueid'];
			$sizerid  = ".item_sizer_$uniqueId";
			if ( $options['sizerwidth'] ) {
				$sizerwidth = sanitize_text_field( $options['sizerwidth'] );
				$sizerwidth = "{width: $sizerwidth;}";
				$styles .= "$sizerid $sizerwidth";
			}
			//Set the hover image
			if ( isset( $options['hoverimage'] ) && $options['hoverimage'] != '' ) {
				if ( isset( $options['hoverlink'] ) ) {
					$hoverlink = sanitize_text_field( $options['hoverlink'] );
					$styles .= "
                .proto_masonry_hover_image_$uniqueId::before
                {
                    /*Style for hover iamge */
                    background: url($hoverlink) 0 0 no-repeat!important;
                }";
				}
			}
			$displayas = isset ( $options['displayas'] ) ? $options['displayas'] : '';
			if ( $displayas != 'masonry' )
				$styles .= ".proto_masonry_$displayas {width:100%;}";
			return $styles;
		}
		/**
		 * masonry_gridstyles function.
		 *
		 * @access public
		 * @param string $gridstyles
		 * @param array $options
		 * @return updated gridstyle
		 */
		function masonry_gridstyles( $gridstyles, $options )
		{
			$resize = isset( $options['resize'] ) ? proto_boolval( $options['resize'] ) : true;
			if ( !$resize )
			{
				$gridstyles = $this->replace_styles( $gridstyles, 'max-width', 'none!important' );
				$gridstyles = $this->replace_styles( $gridstyles, 'width', 'inherit!important' );
			}
			return $gridstyles;
		}
		/**
		 * proto_masonry_defaults function.
		 * Get default values for masonry js script
		 * @access public
		 * @static
		 * @return void
		 */
		public static function proto_masonry_defaults()
		{
			$defaults = array('percentposition'=>false, 'originLeft'=>true, 'originTop'=>true, 'horizontalOrder'=>true, 'transitionDuration'=> '.7s', 'initLayout'=>true, 'resize'=>true, 'stagger'=>'', 'stamp'=>null );
			return $defaults;
		}
		/**
		 * proto_post_attributes function.
		 * Get & prepare advanced query attributes
		 *
		 * @access public
		 * @param array   $atts
		 * @param array   $options
		 * @return void
		 */
		function proto_post_attributes( $atts, $options ) {
			proto_functions::debug_writer( 'Before Parsed advanced Arguments', $atts, $this->debug, $this->debug_log );


			$postatts = shortcode_atts( array
				(
					'before'              => '', //before date - can be set to 'today'
					'after'               => '', //after date - can be set to 'today'
					'today'               => FALSE, //If using before or after, set this to show today's post only
					'inclusive'           => FALSE, //Used with the before and after dates to make those dates inclusive instead of exclusive - doesn't work well & not supported.
				), $atts );
			$atts = array_merge( $atts, $postatts );
			proto_functions::debug_writer( 'Medium Parsed advanced Arguments', $atts, $this->debug, $this->debug_log );

			if ( isset( $atts['taxonomies'] ) ) {
				$taxonomies = sanitize_text_field( $atts['taxonomies'] );
				$taxarray = explode( ",", $taxonomies );
				$taxarray = array_map('trim', $taxarray);
				if ( isset( $atts['tax_query'] ) )
					$full_tax_query = $atts['tax_query'];
				else
					$full_tax_query = array();
				foreach ( $taxarray as $key=>$taxonomy ) {
					$tax_query = array( 'taxonomy' => $taxonomy );
					if ( isset( $atts[  $taxonomy."_terms"  ] ) ) {
						$tax_query['terms'] = wp_parse_id_list( $atts[ $taxonomy.'_terms' ] );
					}
					if ( isset( $atts[ $taxonomy.'_field' ] ) ) {
						$tax_query['field'] = $atts[  $taxonomy.'_field'  ];
					}
					if ( isset( $atts[ $taxonomy.'_operator'  ] ) ) {
						$tax_query['operator'] = $atts[$taxonomy.'_operator'];
					}
					if ( isset( $atts[ $taxonomy.'_include_children'  ] ) ) {
						$tax_query['include_children'] = proto_boolval( $atts[$taxonomy.'_include_children'] );
					}
					unset( $atts[$taxonomy."_terms"] );
					unset( $atts[$taxonomy."_field"] );
					unset( $atts[$taxonomy.'_operator'] );
					unset( $atts[$taxonomy.'_relation'] );
					unset( $atts['taxonomies'] );
					unset( $atts[$taxonomy] );
					$full_tax_query[] = $tax_query;
				}
				if ( count( $full_tax_query ) > 0 ) {
					if ( count( $full_tax_query ) > 1 )
					{
						$relation = isset ( $atts['taxonomy_relation'] ) ? sanitize_text_field( $atts['taxonomy_relation'] ) : 'OR';
						$firstItem = array('relation' => $relation);
						$full_tax_query = $firstItem + $full_tax_query;
					}
					unset( $atts['taxonomy_relation'] );
					$atts['tax_query'] = $full_tax_query;
				}
				proto_functions::debug_writer( 'Taxonomy Query', $atts['tax_query'], $this->debug, $this->debug_log );
			}
			$orderby = trim( sanitize_text_field( $atts['orderby'] ) );
			//prepare order by when meta key
			if ($orderby != '')
			{
				if ($orderby == 'meta_value' || $orderby == 'meta_value_num' )
				{
					$oby_meta_key = $atts['orderby_meta_key'];
					if ($oby_meta_key != '')
						$atts['meta_key'] = $oby_meta_key;
					if ($orderby == 'meta_key')
					{
						$oby_meta_type = $atts['orderby_meta_type'];
						if ($oby_meta_type)
							$atts['meta_type'] = $oby_meta_type;
					}
				}
				unset ( $atts['orderby_meta_key'] ) ;
				unset ( $atts['orderby_meta_type'] );
			}

			//Prepare meta query
			$full_meta_query = array();
			if ( isset( $atts['meta_query'] ) )
				$full_meta_query[] =  $atts['meta_query'];
			if ( isset( $atts['meta_queries'] ) ) {
				$meta_queries = explode( ',' , sanitize_text_field( $atts['meta_queries'] ) );
				$meta = array_map('trim', $meta_queries);
				unset ( $atts['meta_queries'] );
				$meta = array_map('trim', $meta);
				foreach ( $meta as $meta_key ) {
					$meta_array        = array();
					$meta_array['key'] = $meta_key;
					$compare =  isset( $atts[$meta_key.'_compare'] ) ? $atts[$meta_key.'_compare'] : 'IN';
					$meta_array['compare'] = $compare;
					unset( $atts[$meta_key.'_compare'] );
					if ( isset( $atts[$meta_key.'_value'] ) ) {
						$compare = strtoupper( $meta_array['compare'] );
						$values =  explode( ',' , sanitize_text_field($atts[$meta_key.'_value']) );
						$array = array_map('trim', $values);//; wp_parse_id_list( $atts[$meta_key.'_value'] );

						foreach ($array as $k=>$aval)
						{
							switch (strtolower($aval))
							{
							case 'datetoday':
						//	case 'today':
								$array[$k] = $this->todayArray();
								break;
							case 'datetomorrow':
						//	case 'tomorrow':
								$array[$k] = $this->dateArray('tomorrow');
								break;
							case 'dateyesterday':
						//	case 'yesterday':
								$array[$k] = $this->dateArray('yesterday');
								break;
							}
						}
						if ( $compare  == 'IN'  || $compare ==  'NOT IN' || $compare ==  'BETWEEN' || $compare == 'NOT BETWEEN' )
							$meta_array['value'] = $array;
						else
							$meta_array['value'] = $array[0];
						unset (  $atts[$meta_key.'_value']  );
					}
					$full_meta_query[] = $meta_array;
				}
				$atts['meta_query'] = $full_meta_query;
			}
			$before = $atts['before'];
			$after = $atts['after'];
			$today = $atts['today'];
			$inclusive = $atts['inclusive'];
			//Create the attributes for date range queries
			if ( $after || $before ) {
				$before_after = array();
				if ( sanitize_text_field( $after ) == 'today' ) {
					$after = $this->todayArray();
				}
				if ( sanitize_text_field( $before ) == 'today' ) {
					$before = $this->todayArray();
				}
				if ( $after ) {
					$before_after['after'] = $after;
				}
				if ( $before ) {
					$before_after['before'] = $before;
				}
				$before_after['inclusive'] = proto_boolval( $inclusive );
				$atts['date_query'] = $before_after;
				unset( $atts['today'] );
				unset( $atts['before'] );
				unset( $atts['after'] );
				unset( $atts['inclusive'] );
			}
			//Add todays date if the query is for today only
			if ( proto_boolval( $today ) != FALSE ) {
				$atts['date_query'] = $this->todayArray();
			}
			//Explode comma delimited fields into arrays for the query
			$atts = $this->parse_id_field( 'post__in',  $atts );
			$atts = $this->parse_id_field( 'post__not_in',  $atts );
			$atts = $this->parse_id_field( 'author__not_in',  $atts );
			$atts = $this->parse_id_field( 'author__in',  $atts );
			$atts = $this->parse_id_field( 'tag__in', $atts );
			$atts = $this->parse_id_field( 'tag__and', $atts );
			$atts = $this->parse_id_field( 'tag__not_in', $atts );
			$atts = $this->parse_id_field( 'category__not_in',  $atts );
			$atts = $this->parse_id_field( 'category__and',  $atts );
			$atts = $this->parse_id_field( 'category__not_in',  $atts );
			$atts = $this->parse_id_field( 'tag_slug__and',  $atts );
			$atts = $this->parse_id_field( 'tag_slug__in',  $atts );
			$atts = $this->parse_id_field( 'tag__and',  $atts );
			$atts = $this->parse_id_field( 'post_parent__not_in',  $atts );
			$atts = $this->parse_id_field( 'post_parent__in',  $atts );
			$atts = $this->parse_id_field ('post_name__in' , $atts);
			if (isset($atts['post_type']))
				$this->post_type = $atts['post_type'];
			proto_functions::debug_writer( 'After Parsed advanced Arguments', $atts, $this->debug, $this->debug_log );

			return $atts;
		}
		/**
		 * parse_id_field function.
		 *
		 * @access public
		 * @param string $fieldname
		 * @param array $atts
		 * @return $atts
		 */
		function parse_id_field($fieldname,  $atts)
		{
			$field = isset( $atts[$fieldname] ) ? sanitize_text_field( $atts[$fieldname] ) : null;
			unset( $atts [ $fieldname ] );
			if (isset ( $field ) && $field != '')
			{
				$fieldarray = wp_parse_id_list( $field );
				$atts[ $fieldname ] = $fieldarray;
			}
			return $atts;
		}
		/**
		 * todayArray function.
		 * Get todays date in an array
		 *
		 * @access public
		 * @return todays date in an array
		 */
		function todayArray($endofday=false) {
			$today = getdate();
			return array(
				'year'  => $today['year'],
				'month' => $today['mon'],
				'day'   => $today['mday'],
			);
		}
		function dateArray($d='tomorrow') {
			$dt = new DateTime($d);
			return array(
				'year'  => $dt['year'],
				'month' => $dt['mon'],
				'day'   => $dt['mday'],
			);
		}
	}
}
if ( !function_exists( 'featured_image_pro_post_add_query_vars' ) ) {
	/**
	 * featured_image_pro_post_add_query_vars function.
	 * Add a query var for single posts (not guaranteed to work)
	 *
	 * @access public
	 * @param mixed   $aVars
	 * @return void
	 */
	function featured_image_pro_post_add_query_vars( $aVars ) {
		$aVars[] .= 'psp_masonry_page';
		return $aVars;
	}
	add_filter( 'query_vars', 'featured_image_pro_post_add_query_vars' );
}