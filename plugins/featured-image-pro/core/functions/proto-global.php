<?php
/* Global Shared Proto Functions
  @category  utility
  @package featured-image-pro
  @author  Adrian Jones <adrian@shooflysolutions.com>
  @license MIT
  @link http:://www.shooflysolutions.com
 Version 1.0*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if ( !function_exists( 'proto_boolval' ) ):
	/**
	 * proto_boolval function.
	 * returns a boolean value
	 *
	 * @access public
	 * @param mixed   $val
	 * @param bool    $return_null (default: false)
	 * @return void
	 */
	function proto_boolval( $val, $return_null=false ) {

		$proto_boolval = ( is_string( $val ) ? filter_var( $val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE ) : (bool) $val );
		return $proto_boolval===null && !$return_null ? false : $proto_boolval;
	}
endif;

if (!function_exists('proto_cast_value')):
		/**
		 * proto_cast_value function.
		 * Cast a value
		 * @access public
		 * @param mixed $value
		 * @param string $casttype
		 * @return cast value
		 */
		function proto_cast_value( $value, $casttype)
		{
			$date_format = get_option( 'date_format' ); //this is the wordpress date format
			$time_format = get_option( 'time_format' ); //this is the wordpress time format

			switch ($casttype)
			{
				case 'date':
					$value = date_i18n( $date_format, strtotime( $value ) );
					break;
				case 'int':
					$value = intval( $value );
					break;
				case 'float':
					$value = sprintf('%0.2f', floatval( $value ) );
					break;
				case 'time':
					$value = date_i18n( $time_format, strtotime( $value ) );
					break;
				case 'datetime':
					$value = date_i18n( $date_format, strtotime( $value ) ) . ' ' . date_i18n($time_format, strtotime( $value ) );;
					break;
				case 'bool':
					$value = proto_boolval( $value ) ? 'true' : 'false';
					break;
				case 'string':
				default:
					if ( is_array( $value ) ) {
						foreach( $value as $key=>$val )
							$value[$key] = strval( $val );
					}
					else
						$value = strval($value);


			}
			return $value;

		}
endif;
if ( !function_exists( 'add_filter_once' ) ):
	/**
	 * Performs an add_filter only once. Helpful for factory constructors where an action only
	 * needs to be added once. Because of this, there will be no need to do a static variable that
	 * will be set to true after the first run, ala $firstLoad
	 *
	 * @since 1.9
	 *
	 * @param string  $tag             The name of the filter to hook the $function_to_add callback to.
	 * @param callback $function_to_add The callback to be run when the filter is applied.
	 * @param int     $priority        Optional. Used to specify the order in which the functions
	 *                                  associated with a particular action are executed. Default 10.
	 *                                  Lower numbers correspond with earlier execution,
	 *                                  and functions with the same priority are executed
	 *                                  in the order in which they were added to the action.
	 * @param int     $accepted_args   Optional. The number of arguments the function accepts. Default 1.
	 *
	 * @return true
	 */
	function add_filter_once( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
		global $_gambitFiltersRan;
		if ( ! isset( $_gambitFiltersRan ) ) {
			$_gambitFiltersRan = array();
		}
		// Since references to $this produces a unique id, just use the class for identification purposes
		$idxFunc = $function_to_add;
		if ( is_array( $function_to_add ) ) {
			$idxFunc[0] = get_class( $function_to_add[0] );
		}
		$idx = _wp_filter_build_unique_id( $tag, $idxFunc, $priority );
		if ( ! in_array( $idx, $_gambitFiltersRan ) ) {
			add_filter( $tag, $function_to_add, $priority, $accepted_args );
		}
		$_gambitFiltersRan[] = $idx;
		return true;
	}
endif;
if ( !function_exists( 'add_action_once' ) ):
	/**
	 * Performs an add_action only once. Helpful for factory constructors where an action only
	 * needs to be added once. Because of this, there will be no need to do a static variable that
	 * will be set to true after the first run, ala $firstLoad
	 *
	 * @since 1.9
	 *
	 * @param string  $tag             The name of the filter to hook the $function_to_add callback to.
	 * @param callback $function_to_add The callback to be run when the filter is applied.
	 * @param int     $priority        Optional. Used to specify the order in which the functions
	 *                                  associated with a particular action are executed. Default 10.
	 *                                  Lower numbers correspond with earlier execution,
	 *                                  and functions with the same priority are executed
	 *                                  in the order in which they were added to the action.
	 * @param int     $accepted_args   Optional. The number of arguments the function accepts. Default 1.
	 *
	 * @return true
	 */
	function add_action_once( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
		global $_gambitActionsRan;
		if ( ! isset( $_gambitActionsRan ) ) {
			$_gambitActionsRan = array();
		}
		// Since references to $this produces a unique id, just use the class for identification purposes
		$idxFunc = $function_to_add;
		if ( is_array( $function_to_add ) ) {
			$idxFunc[0] = get_class( $function_to_add[0] );
		}
		$idx = _wp_filter_build_unique_id( $tag, $idxFunc, $priority );
		if ( ! in_array( $idx, $_gambitActionsRan ) ) {
			add_action( $tag, $function_to_add, $priority, $accepted_args );
		}
		$_gambitActionsRan[] = $idx;
		return true;
	}
endif;

if (!class_exists('proto_functions')):
	class proto_functions
{
	static function calculate_column_width( $options )
	{
		$columnwidth = isset($options['columnwidth']) ? intval($options['columnwidth']) : '0';
		$itemwidth  = isset($options['itemwidth']) ? sanitize_text_field($options['itemwidth']) : '';
		$imagewidth  = isset($options['imagewidth']) ? sanitize_text_field($options['imagewidth']) : '';
		$maxwidth  = isset($options['maxwidth']) ? sanitize_text_field($options['maxwidth']) : '';
		//********************Calculate Column Width*****************************************************

		if ( !$columnwidth || $columnwidth == '0' || $columnwidth == '' )
		{
			$gutter = ( isset( $options['gutter'] ) ) ? intval($options['gutter']) : 0;

			if ( $gutter > 0 )
			{
				//$averagewidth = $options['averagewidth'];
				$columnwidth  = $options['maximgwidth'];
				if ( is_numeric( $itemwidth ) || strpos( $itemwidth, 'px' ) > 0 )
				{
					$columnwidth = intval( $itemwidth );

				}
				elseif ( is_numeric( $imagewidth ) || strpos( $imagewidth, 'px' ) > 0 )
				{
					$columnwidth = intval( $imagewidth );

				}
				if ( is_numeric( $maxwidth ) || strpos( $maxwidth, 'px' ) > 0 )
				{
					$columnwidth = min($columnwidth, intval( $maxwidth ));

				}

			}
			else

				$columnwidth = 1;
		}


		return ($columnwidth);
	}
    static function get_proto_post_sizes($proto_post, $imgobj, $imagesize)
    {
	    	if ($imagesize == 'thumbnail')      //Get the size object for the thumbnail Image
				$imgobjt = $imgobj;
			else
				$imgobjt = wp_get_attachment_image_src($proto_post->id, 'thumbnail');
			if ($imagesize == "large")      //Get the size object for the large image
				$imgobjl = $imgobj;
			else
				$imgobjl = wp_get_attachment_image_src($proto_post->id, 'large'); //Large Image
			if ( $imgobjt !== false ) {
				$proto_post->thumbnail_url = $imgobjt[0]; //Link to thumbnail image

			}
			else
				$imgobjt = null;
			if ( $imgobjl !== false)
				$proto_post->large_url = $imgobjl[0];      //Link to Large image
			else
				$proto_post->large_url = null;
			if ( $imgobj !== false ) {
				$proto_post->large_url = $imgobjl[0];      //Link to Large image
				$proto_post->img_url = $imgobj[0] ;        //Href link
				$proto_post->width = $imgobj[1];           //Image Width
				$proto_post->height = $imgobj[2];          //Image Height
			} else
				$imgobj = null;
			return $proto_post;
    }
	/**
	 * check_settings function.
	 * try to fix some of the settings
	 * @access public
	 * @param mixed $noptions
	 * @return void
	 */
	static function check_settings($noptions)
	{
		$has_thumbnails = isset( $noptions['has_thumbnails'] ) ? proto_boolval( $noptions['has_thumbnails'] ) : true;
		if (! isset ($noptions['show_noimage_posts'] ) )
			$noptions['show_noimage_posts'] = !$has_thumbnails;
		$noptions['show_noimage_posts'] = isset( $noptions['show_noimage_posts'] ) ? proto_boolval ($noptions['show_noimage_posts'] ) : false ;
		unset( $noptions['has_thumbnails'] );
		//If the item width is not empty and the imagewidth is 100%, take the border into account for the image size.
		if (  $noptions['imagewidth'] == '100%' && $noptions['itemwidth'] != '' ) {
			if ( $noptions['border'] ) {
				$border = sanitize_text_field( $noptions['border'] ) . 'px';
				$itemwidth = sanitize_text_field( $noptions['itemwidth'] );
				$noptions['imagewidth'] = "calc( 100% - $border)";
			}
		}
		if ($noptions['marginbottom'] == 0)
			$noptions['marginbottom'] = '0';
		$options['excerptheight'] = isset($onptions['excerptheight']) ? proto_function::check_1option( esc_attr ( $noptions['excerptheight'] ) ) : '';

		// add a .px to width/height settings that are integer only
		$noptions['gridwidth'] = isset($noptions['gridwidth']) ? proto_functions::check_1option(esc_attr($noptions['gridwidth'])) : '';
		$noptions['itemwidth'] = isset($noptions['itemwidth']) ? proto_functions::check_1option(esc_attr($noptions['itemwidth'])) : '';
		$noptions['imagewidth'] = isset($noptions['imagewidth']) ? proto_functions::check_1option(esc_attr($noptions['imagewidth'])) : '';
		$noptions['imageheight'] = isset($noptions['imageheight']) ? proto_functions::check_1option(esc_attr($noptions['imageheight'])) : '';

		$noptions['marginbottom'] = isset($noptions['marginbottom']) ? proto_functions::check_1option(esc_attr($noptions['marginbottom'])) : '';
		$noptions['itemwidth'] = isset($noptions['imagewidth']) ? proto_functions::check_1option(esc_attr($noptions['itemwidth'])) : '';
		$noptions['maxwidth'] = isset($noptions['maxwidth']) ? proto_functions::check_1option(esc_attr($noptions['maxwidth'])) : '';
		$noptions['maxheight'] = isset($noptions['maxheight']) ? proto_functions::check_1option(esc_attr($noptions['maxheight'])) : '';
		$noptions['captionheight'] = isset($noptions['captionheight']) ? proto_functions::check_1option(esc_attr($noptions['captionheight'])) : '';
		return $noptions;
	}
	/**
	 * check_1option function.
	 * Add default px to any widths that are numeric only
	 * @access public
	 * @param mixed $option
	 * @return void
	 */
	static function check_1option($option) {

		if (is_numeric($option))
		{
			if ($option != '0')
			{
				$option =  trim( $option ) . 'px';

			}
		}
		return $option;
	}
	/**
	 * print_filters_for function.
	 *
	 * @access public
	 * @static
	 * @param string $hook (default: '')
	 * @return void
	 */
	static function proto_get_filters_for( $hook = '' ) {
		global $wp_filter;
		if( empty( $hook ) || !isset( $wp_filter[$hook] ) )
			return;

		return ( $wp_filter[$hook] ) ;
	}

		/**
		 * elementclass function.
		 * add a class to an element. The class value is stored in the options array
		 * @access public
		 * @param string $class - existing element class
		 * @param array $options - the array of options
		 * @param elementname - the name of the  element (option key)
		 * @return modified string of classes
		 */
		static function elementclass( $class, $options, $elementname )
		{
			$cclass = isset( $options[$elementname] ) ? sanitize_text_field( $options[$elementname] ) : '';
			$class = $cclass ?  $class . ' ' . $cclass : $class;
			return $class;
		}

	/**
	 * proto_masonry_prefix_key_value function.
	 * Compare key / value pairs to default key / value pairs. If they differ, then return them in an array, optionally with the prefix as a part of the key
	 * @access public
	 * @param array $defaults
	 * @param array $settings
	 * @param string $prefix
	 * @return void
	 */
	static function proto_masonry_prefix_key_value($defaults, $settings, $prefix, $numbool = false)
	{
		$lsettings = array_change_key_case($settings, CASE_LOWER);  //change all of the settings keys to lower case. Settings are not case sensitive

		$scriptvalues = array();
		foreach ( $defaults as $key => $defaultvalue ) {

			$lkey = strtolower($key);
			if ( isset( $settings["{$prefix}{$lkey}"] ) )
			{
				if ( is_bool( $defaultvalue )  )  //Compare the default value to the settings value
					{
					$defaultvalue = proto_boolval($defaultvalue) ?  'true' : 'false';
						if ($numbool)

							$value = proto_boolval( $lsettings["{$prefix}{$lkey}"] )  ? '1' : '0';
						else
							$value = proto_boolval( $lsettings["{$prefix}{$lkey}"] )  ? 'true' : 'false';
				}
				elseif ( is_numeric( $defaultvalue ) )
				{
					$value = intval( $lsettings["{$prefix}{$lkey}"] ) ;
				}
				elseif ( is_string( $defaultvalue ) )
				{
					$value = "'" . sanitize_text_field( $lsettings["{$prefix}{$lkey}"] )  . "'";
				}
				else
				{
					$value = sanitize_text_field( $settings["{$prefix}{$lkey}"] );
				}

				if ($value != $defaultvalue) //only include settings values that are not the same as the default value. Use the default case sensitive key name
					$scriptvalues[$key] =  $value;
			}

		}
		return $scriptvalues;
	}
	/**
	 * generate_script function.
	 * Create json script from an array
	 * @access public
	 * @static
	 * @param array $scriptvalues (default: array())
	 * @return json script
	 */
	static function generate_script($scriptvalues = array())
	{
		$script = '';
		foreach ($scriptvalues as $key=>$value)
		{

			if ($script != '')
				$script .= ',';

			if (is_string($value))
				$script .= "$key:$value";
			else
				$script .= "$key:$value";


		}
		return $script;
	}
	/**
	 * append_dependency function.
	 * Append a dependency to a registered script
	 * @access public
	 * @static
	 * @param string $handle
	 * @param string $dep
	 * @return void
	 */
	static function append_dependency( $handle, $dep ){
	    global $wp_scripts;

	    $script = $wp_scripts->query( $handle, 'registered' );

	    if( !$script )
	        return false;

	    if( !in_array( $dep, $script->deps ) ){
	        $script->deps[] = $dep;
	    }

	    return true;
	}
	static function debug_writer( $title, $output, $debug=false, $debug_log=false )
	{
		if (!$debug && !$debug_log)
			return;
		if ( $debug )
		{
			echo "<pre>$title<br>";
			if (is_array( $output ) || is_object( $output ) )
				print_r( $output );
			else
				echo $output;
			echo "</pre>";
		}
		if ($debug_log)
		{
			error_log( $title );
			if ( is_array( $output ) || is_object( $output ) )
				error_log( print_r( $output, true ) );
			else
				error_log( $output );

		}
	}

}
endif;


if (!function_exists('insite_inspect_scripts')) {
	function insite_inspect_scripts() {
	    global $wp_scripts;
	    echo PHP_EOL.'<!-- Script Handles: ';
	    foreach( $wp_scripts->queue as $handle ) :
	        echo $handle . ' || ';
	    endforeach;
	    echo ' -->'.PHP_EOL;
	}
}
add_action( 'wp_print_scripts', 'insite_inspect_scripts' );