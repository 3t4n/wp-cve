<?php // (C) Copyright Bobbing Wide 2012-2023
if ( !defined( "OIK_SC_HELP_INCLUDED" ) ) {
define( "OIK_SC_HELP_INCLUDED", "3.4.1" );

/**
 * Shortcode help 
 * 
 * Library: oik-sc-help
 * Provides: oik-sc-help
 * Depends: on the oik plugin, class-BW-, bobbfunc  @TODO complete this
 * Deferred dependencies: oik_plugins, class-dependencies-cache
 *
 * Implements low level functions for displaying shortcode help.
 * This includes the help functions for some base shortcodes.
 * Parts of this library file is now deprecated in favour of "class-oik-sc-help"
 *
 */

/**
 * Get first parm name
 *
 * Return the first name for the parameter. This is used to generate the link for the shortcode parameter.
 *
 * Some shortcodes have parameters which can have multiple keys for the basically the same content e.g. src,mp3,m4a,ogg... for [audio]
 * other shortcode accepts positional parameters - e.g. api|0 for the [api] shortcode
 * 
 * @param string $parameter
 * @return string - returned value
 */
function bw_form_sc_get_first_parm_name( $parameter ) {
  $parm = str_replace( array("|", ",") , " ", $parameter );
  $parms = explode(" ", $parm ); 
  return( $parms[0] );
}

/**
 * Return link to shortcode parameter help
 *
 * Links are of the form
 *
 * `https://oik-plugins.com/oik_sc_param/$shortcode-$parameter-parameter/`
 *
 * where the URL is determined by oik_get_plugins_server()
 *
 * @param string $parameter	- may be a comma separated list of parameter name aliases e.g. "src,mp3,m4a,ogg,wav,wma"
 * @param string $shortcode	- e.g. "audio"
 * @return string the required link
 */
function bw_form_sc_parm_help( $parameter, $shortcode ) {
	$parm = $parameter;
	if ( function_exists( "oik_shortcodes_define_shortcode_parameter_server" ) ) {
		oik_shortcodes_define_shortcode_parameter_server();
	}
	if ( !function_exists( "oik_get_plugins_server" ) ) {
		oik_require_lib( "oik_plugins" );
	} 
	if ( function_exists( "oik_get_plugins_server" ) ) {
		$url = oik_get_plugins_server();
		$url .= "/oik_sc_param/$shortcode-$parm-parameter";
		$ret = retlink( null, $url, $parameter, "$shortcode $parameter parameter" );
	} else {
		$ret = null;
	}
	return( $ret );
}

/**
 * Format the shortcode syntax for returning in HTML
 *
 * @TODO - create links to the oik shortcode parameter help
 * for the specific shortcode and parameter name
 * 
 * 
 * @param array $syntax - array of key => shortcode key values
 * @param string $shortcode - the shortcode
 */
function bw_form_sc_syntax( $syntax, $shortcode ) {
  if ( is_array( $syntax ) && count( $syntax ) ) { 
    foreach ( $syntax as $key => $value ) {
      BW_::br();
      span( "key" );
      //e( $key );
      e( bw_form_sc_parm_help( $key, $shortcode ) );
      epan();
      e( "=" );
      span( "value");
      e( '"' );     
      e( bw_format_skv( $value ));  
      e( '"' );
      epan();
    }  
  }
}

/**
 * Attempt to find the function that will handle the additional processing for the shortcode
 *
 * Some shortcodes contain hyphens rather than underscores. e.g. wp-members
 * We need to translate these to underscores before searching for the function. 
 * And some contain periods. e.g. blip.tv. These need translating too.
 * 
 * If the function does not exist we see if it's in the file that implements the shortcode.
 * If it's not then we return the default function name,
 * which is:
 * 
 * - _sc__help: for shortcode help
 * - _sc__example: for shortcode example
 * - _sc__syntax: for shortcode syntax 
 *   
 * @param string $shortcode - the name of the shortcode - NOT the shortcode function
 * @param string $suffix - the additional processing required: e.g. __help, __example, __syntax
 * @return string $funcname - the function that exists to handle this request
 */
function bw_load_shortcode_suffix( $shortcode, $suffix ) {
  $_shortcode = str_replace( "-", "_", $shortcode );
	$_shortcode = str_replace( ".", "_", $_shortcode );
  //  bw_trace2( $_shortcode, "underscored shortcode" );
  $testfunc = $_shortcode . $suffix; 
  if ( function_exists( $testfunc ) ) {
    $funcname = $testfunc;
  } else {
    $funcname = '_sc'. $suffix;
    if ( bw_load_shortcodefile( $shortcode ) && function_exists( $testfunc ) ) {
      $funcname = $testfunc; 
    }   
  }   
  return( $funcname );
}

/**
 * Display shortcode help
 *
 * @param string $shortcode - the shortcode e.g. bw_codes
 */   
function bw_lazy_sc_help( $shortcode ) {
  e( _bw_lazy_sc_help( $shortcode )) ;
}

/**
 * Return shortcode help
 *
 * @param string $shortcode - the shortcode e.g. bw_codes
 * @return string - the shortcode help
 */
function _bw_lazy_sc_help( $shortcode ) { 
  $funcname = bw_load_shortcode_suffix( $shortcode, "__help" ); 
  $help = $funcname( $shortcode );
  return( $help );
}

/**
 * Display a shortcode example
 * 
 * @param string $shortcode
 * @param array $atts - shortcode example parameters
 */
function bw_lazy_sc_example( $shortcode, $atts=null ) {
  $funcname = bw_load_shortcode_suffix( $shortcode, "__example" ); 
  $funcname( $shortcode, $atts ); 
}

/**
 * Return shortcode syntax
 *
 * @param string $shortcode
 * @return array - shortcode syntax as an associative array 
 */
function _bw_lazy_sc_syntax( $shortcode ) {
	$funcname = bw_load_shortcode_suffix( $shortcode, "__syntax" );
	$syntax = $funcname( $shortcode );
	//bw_trace2( $syntax, "Syntax for $funcname" ); 
	return $syntax ;
} 

/**
 * Display shortcode syntax
 *
 * @param string $shortcode - the shortcode name
 *
 */ 
function bw_lazy_sc_syntax( $shortcode, $callback=null ) {
	oik_require( "shortcodes/oik-codes.php" );
  stag("code");
  e( '[' );
  bw_sc_link( $shortcode, $callback );
  $syntax = _bw_lazy_sc_syntax( $shortcode );
  bw_form_sc_syntax( $syntax, $shortcode );
  e( ']' );
  etag( "code" );
}

/**
 * Save original dependencies
 * 
 * We need to do the same for styles: wp_styles
 * We initialise $wp_scripts to ensure that $wp_scripts->registered is an array
 * We also need to capture the output.
 */
function bw_save_scripts() {
	$dependencies_cache = oik_require_lib( "class-dependencies-cache" );
	$dependencies_cache = dependencies_cache::instance();
	//$dependencies_cache->save_dependencies();
	$dependencies_cache->capture_scripts();
}


/**
 * Report queued scripts
 *  
 * @param bool $verbose
 * @return string most recently queued scripts and styles
 */ 
function bw_report_scripts( $verbose=true ) {
	$dependencies_cache = dependencies_cache::instance();
	//$dependencies_cache->query_dependencies_changes();
	//$dependencies = $dependencies_cache->serialize_dependencies();
	//$serialized = serialize( $dependencies );
	//p( $serialized );
	$latest_html = $dependencies_cache->get_latest_html();
	//oik_require_lib( "bobbfunc" );
    if ( function_exists( "bw_jq_get") ) {
        $latest_html .= bw_jq_get();
    }
	return $latest_html;
}

/** 
 * Produce the HTML snippet of the shortcode output
 *
 * @param string $shortcode - name of the shortcode
 * @param string $atts - shortcode parameters e.g class="bw_code"
 */
function _sc__snippet( $shortcode="bw_code", $atts=null ) {
  $example = "[$shortcode";
  if ( $atts ) 
    $example .= " $atts";
  $example .= ']';
	$formatted_example = bw_expand_shortcode( $example );
  bw_trace2( $formatted_example, "formatted example" );
  $escaped_example = esc_html( $formatted_example );
  stag( 'p', null, null, 'lang="HTML" escaped="true"' );
  e( $escaped_example );
  etag( "p" );
  $latest_html = bw_report_scripts();
	if ( $latest_html ) {
		$escaped_example = esc_html( $latest_html );
		stag( 'p', null, null, 'lang="HTML" escaped="true"' );
		e( $escaped_example );
		etag( "p" );
	}
}

/**
 * Display the HTML snippet for a shortcode example
 *
 * @param string $shortcode - shortcode name
 * @param string $example - shortcode parameter string
 */
function bw_lazy_sc_snippet( $shortcode=null, $example=null ) {
	$saved = bw_global_post();
  $funcname = bw_load_shortcode_suffix( $shortcode, "__snippet" ); 
  bw_trace2( $funcname, "funcname" );
  $funcname( $shortcode, $example ); 
	bw_global_post( $saved );
}

/**
 * Returns the help for a shortcode
 * 
 * Returns the default help for a shortcode if not provided by the "shortcode__help" function
 *
 * @param string $shortcode
 * @return string translated shortcode help
 */
function _sc__help( $shortcode="bw_code" ) {
	$default_help = array();
  $default_help = apply_filters( "_sc__help", $default_help, $shortcode );
  $help = bw_array_get( $default_help, $shortcode, __( "?", null ) );
  return $help ;
}

/**
 * Return default syntax for this shortcode
 *
 * Originally this returned "oik doesn't know about this shortcode's syntax: ". $shortcode 
 * but this was changed to support the AJAX interface
 * 
 * It has now been extended to invoke filters which enable plugins to return the
 * syntax IF they don't support the $shortcode__syntax() dynamic hook method.
 * 
 * @param string $shortcode 
 * @return array $syntax array
 */ 
function _sc__syntax( $shortcode="bw_code" ) {
  $syntax = null;
  $syntax = apply_filters( "_sc__syntax", $syntax, $shortcode );
  return( $syntax );
}


/**
 * Produces default example for this shortcode
 * 
 * @param string $shortcode
 * @param array $atts
 */
function _sc__example( $shortcode="bw_code", $atts=null ) {
  //p( "oik doesn't know how to give an example for this shortcode: ". $shortcode );
  bw_invoke_shortcode( $shortcode, $atts, __( "oik generated example.", null ) );
}

/**
 * Helper functions for shortcodes that support these atts
 *
 * @param bool $include_id Set to true if you want the id= parameter
 * @return array with "class" and "id" keys
 */
function _sc_classes( $include_id = true ) {
	$syntax =  array( "class"=> BW_::bw_skv( "", "<i>" . __( "class names", null ) . "</i>", __( "CSS class names", null ) ) );
	if ( $include_id ) {
		$syntax['id'] = BW_::bw_skv( "", "<i>" . __( "id", null ) . "</i>", __( "unique identifier", null ) );
	}
	return $syntax;
}

 
/**
 * Helper function for shortcodes that use bw_get_posts() 
 *
 * Note: post_mime_type= can have sub values. eg.
 * - application/pdf
 * - image/gif
 * - image/jpeg
 * - image/png
 * - text/css
 * - video/mp4
 *
 * 'posts_per_page' added in oik v2.3
 *
 * @return array - associative array of BW_::bw_skv's  
 */
function _sc_posts() {
  return( array( 'numberposts'  => BW_::bw_skv( "5", __( "numeric", null ), __( "number to return", null ) )
               , 'offset'          => BW_::bw_skv( 0, __( "numeric", null ), __( "offset from which to start", null ) )
               , 'category'        => BW_::bw_skv( null, "<i>" . __( "category ID", null ) . "</i>", __( "numeric ID of the post category", null ) )
               , 'category_name'        => BW_::bw_skv( null, "<i>" . __( "category-slug", null ) . "</i>", __( "category slugs (comma separated)", null ) )
               , 'customcategoryname' => BW_::bw_skv( null, __( "category-slug", null ), __( "custom category slug", null ) )
               , 'orderby'         => BW_::bw_skv( 'date', "ID|title|parent|rand|menu_order", __( "Sort sequence", null ) )
               , 'order'           => BW_::bw_skv( 'DESC', "ASC", __( "Sort order.", null ) )
               , 'include'         => BW_::bw_skv( null, "<i>" . __( "id1,id2", null ) . "</i>", __( "IDs to include", null ) )
               , 'exclude'         => BW_::bw_skv( null, "<i>" . __( "id1,id2", null ) . "</i>", __( "IDs to exclude", null ) )
               , 'meta_key'        => BW_::bw_skv( null, __( "meta key", null ), __( "post metadata key", null ) )
               , 'meta_value'      => BW_::bw_skv( null, __( "meta value", null ), __( "post metadata value", null ) )
               , 'post_type'       => BW_::bw_skv( null, "page|post|attachment|" . __( "custom post type", null ), __( "Content type to display", null ) )
               , 'post_mime_type'  => BW_::bw_skv( null, "image|application|text|video|" . __( "mime type", null ), __( "Attached media MIME type", null ) )
               , 'post_parent'     => BW_::bw_skv( null, __( "ID" , null ) , __( "Parent ID to use if not current post", null ) )
               , 'post_status'     => BW_::bw_skv( null,  "publish|inherit|pending|draft|auto-draft|future|private|trash|any", __( "Post status", null ) )
               , 'id'              => BW_::bw_skv( null, "<i>" . __( "id1,id2", null ) . "</i>", __( "IDs of posts to display", null ) )

               , 'posts_per_page'  => BW_::bw_skv( null, __( "numeric", null ) . "|.",
                    /* translators: %s current number of posts per page */
                    sprintf( __( 'Number of posts per page. Use \'.\' for current value %1$s', null ) , get_option( "posts_per_page", null ) ) )
               ));
} 

/**
 * Helper function for shortcodes that display images
 * 
 * @return array - associative array of `bw_skv()`'s
 */
function _sc_thumbnail() {   
  return( array( 'thumbnail'       => BW_::bw_skv( "thumbnail", "medium|large|full|" . __( "nnn", null ) . "|" . __( "wxh", null ), __( "image size", null ) )   
               ));
}        


function caption__help() {
  return( __( "Display the caption for an image. Standard WordPress shortcode", null ) );
}

/**
 * Example hook for caption shortcode
 *
 * @TODO The caption should surround an image - so we need to include an image in this
 */ 
function caption__example() {    
  BW_::br( __( "e.g.:", null ) );
  $ics = img_caption_shortcode( array( 'width' => 70, 'caption' => __( 'This is a caption', null ) ) );
  e( $ics );
}

function wp_caption__help() {
  return( caption__help() );
}

function caption__syntax() {
  $syntax = array( 'id' => BW_::bw_skv( null, __( "text", null ), __( "value for CSS id= keyword", null ) )
                 , 'class' => BW_::bw_skv( null, __( "classname", null) , __( "custom class", null ) )
                 , 'align' => BW_::bw_skv( "alignnone", "aligncenter|alignright|alignleft", __( "CSS alignment class", null ) )
                 , 'width' => BW_::bw_skv( null, __( "numeric", null ), __( "width in pixels (Note: 10 is added to this number)", null ) )
                 );
  return( $syntax );               
}

function wp_caption__syntax() {
  return( caption__syntax() );
} 

function gallery__help() {
  return( __( "Display the attached images in a gallery", null ) );
}

/**
 * Syntax help for the gallery shortcode
 * @TODO Find out if the post__in and HTML5 stuff should be mentioned or not. 
 * `
  $atts = shortcode_atts( array(
    'order'      => 'ASC',
    'orderby'    => 'menu_order ID', 
                 , "orderby" => BW_::bw_skv( "post__in", "<i>menu_order|ID|title|rand</i>", "Order by field" )
    'id'         => $post ? $post->ID : 0,
    'itemtag'    => $html5 ? 'figure'     : 'dl',
    'icontag'    => $html5 ? 'div'        : 'dt',
    'captiontag' => $html5 ? 'figcaption' : 'dd',
    'columns'    => 3,
    'size'       => 'thumbnail',
    'include'    => '',
    'exclude'    => '',
    'link'       => ''
 * `
 */
function gallery__syntax() {
	$syntax = array( 'order'   => BW_::bw_skv( 'ASC', "DESC", __( "Order", null ) )
		, 'orderby'    => BW_::bw_skv( 'menu_order ID', "ID|title|rand", __( "Order by field", null ) )
		, 'ids'         => BW_::bw_skv( "",  __( "ID" , null ), __( 'Post ID. Default: Current post ID', null ) )
		, 'itemtag'    => BW_::bw_skv( 'dl', "<i>" . __( "tag", null ) . "</i>", __( "Item tag", null ) )
		, 'icontag'    => BW_::bw_skv( 'dt', "<i>" . __( "tag", null ) . "</i>", __( "Icon tag", null ) )
		, 'captiontag' => BW_::bw_skv( 'dd', "<i>" . __( "tag", null ) . "</i>", __( "Caption tag", null ) )
		, 'columns'    => BW_::bw_skv( 3, __( "numeric", null ), __( "Columns", null ) )
		, 'size'       => BW_::bw_skv( 'thumbnail', "medium|full", __( "Thumbnail image size", null ) ) 
		, 'include'    => BW_::bw_skv( null, "<i>" . __( "id1,id2", null ) . "</i>", __( "IDs to include", null ) )
		, 'exclude'    => BW_::bw_skv( null, "<i>" . __( "id1,id2", null ) . "</i>", __( "IDs to exclude", null ) )
		);
	return $syntax ;
}

/**
 * Help for the embed shortcode
 */ 
function embed__help( $shortcode='embed' ) {
  return( __("Embed media", null ) );
}

/**
 * Example for the embed shortcode
 *
 * The embed shortcode requires an URL which can be provided as embedded content
 * or passed in the `src=` parameter.
 * 
 * e.g. 
 * `[embed width="480"]https://www.youtube.com/watch?v=nH228-XQ-A8[/embed]`
 *
 * @param string $shortcode
 */
function embed__example( $shortcode='embed' ) {
	$atts = 'width="480" src=https://youtu.be/awgO9VlUWCY';
  bw_invoke_shortcode( $shortcode, $atts, __( "Embed example", null ) );
	global $wp_version;
	if ( version_compare( $wp_version, "4.9", "lt" ) ) {
		BW_::p( __( "Note: It's not possible to produce a proper working example due to the way the embed shortcode is handled.", null ) );
	}
}

/**
 * Syntax for embed shortcode
 *
 * - The default width comes from $GLOBALS['content_width'], if set
 * - Default height is  min( ceil( $width * 1.5 ), 1000 );
 */
function embed__syntax( $shortcode='embed' ) {


  $syntax = array( "width" => BW_::bw_skv( "500",  __( "numeric", null ), __( "Required width", null ) )
                 , "height" => BW_::bw_skv( "750",  __( "numeric", null ), __( "Required height", null ) )
								 , "src" => BW_::bw_skv( null, __( "URL", null ), __( "URL to embed, if not provided in shortcode content", null ) )
                 );
  return( $syntax );
}

/**
 * Syntax for [audio] shortcode
 * 
 * @link https://codex.wordpress.org/Audio_Shortcode
 */
function audio__syntax( $shortcode='audio' ) {
  $syntax = array( "src,mp3,m4a,ogg,wav,wma" => BW_::bw_skv( null, "<i>" . __( "URL", null ) . "</i>", __( "If omitted uses first attachment", null ) )
                 , "loop" => BW_::bw_skv( "off", "on", __( "Allow looping of media", null ) )
                 , "autoplay" => BW_::bw_skv( "off", "on", __( "Causes media to autoplay", null ) )
                 , "preload" => BW_::bw_skv( "none", "auto|metadata", __( "How the audio should be loaded", null ) )
                 );
  return( $syntax ); 
}

/**
 * Syntax for [video] shortcode
 *
 * @link https://codex.wordpress.org/Video_Shortcode
 */
function video__syntax( $shortcode='video' ) {
  $syntax = array( "src,mp4,m4v,webm,ogv,wmv,flv" => BW_::bw_skv( null, "<i>" . __( "URL", null ) . "</i>", __( "If omitted uses first attachment", null ) )
                 , "poster" => BW_::bw_skv( null, "<i>" . __( "URL", null ) . "</i>", __( "Placeholder image", null ) )
                 , "loop" => BW_::bw_skv( "off", "on", __( "Allow looping of media", null ) )
                 , "autoplay" => BW_::bw_skv( "off", "on", __( "Causes media to autoplay", null ) )
                 , "preload" => BW_::bw_skv( "none", "auto|metadata", __( "How the audio should be loaded", null ) )
                 , "height" => BW_::bw_skv( null,  __( "numeric", null ), __( "Required height", null ) )
                 , "width" => BW_::bw_skv( null, __( "numeric", null ), __( "Required width", null ) )
                 );
  return( $syntax ); 
}

/** 
 * Return the default, values and notes for a shortcode parameter
 *
 * - bw_skv is an abbreviation of bw_sc_key_values
 * - Here we call bw_translate() to help identify code that should be changed to use BW_::bw_skv()
 * 
 * @param string|null $default default value
 * @param string|null $values vertical bar separated possible values
 * @param string $notes translatable text
 * @return array
 */
function bw_skv( $default, $values, $notes ) {
  return( array( "default" => $default
               , "values" => $values
               , "notes" => bw_translate( $notes, "oik" )
               )  );
}

/**
 * Return the choices and notes for a keywords values
 *
 * @param mixed $value - array or string containing attribute values 
 * @return string - HTML showing the default value, other values and notes
 */
function bw_format_skv( $value ) {  
  if ( is_array( $value ) ) {    
    $default = bw_array_get( $value, "default", null );
    $values = bw_array_get( $value, "values", null );
    $values = str_replace( "|", "| ", $values );
    $notes = bw_array_get( $value, "notes", null );
    if ( is_array( $values ) ) { bw_trace2(); }
    if ( is_array( $notes ) ) {bw_trace2(); }
    
    return( "<b>$default</b>| $values - $notes" );
  } else {
    return( $value );
  }    
}

/**
 * Calls a shortcode for a simple example
 *
 * @uses apply_filters() rather than do_shortcode() since the shortcodes that get invoked
 * may not support the current_filter() - which on an admin page could be oik-options_page_oik_options-1
 * Nearly ALL shortcodes support 'the_content', so we will apply that filter.
 * 
 * @param string $shortcode - name of shortcode
 * @param string $atts - attribute parameters to the shortcode
 * @param string $text - translated brief description of the shortcode
 */
function bw_invoke_shortcode( $shortcode, $atts=null, $text=null ) {
  BW_::p( $text );
  $example = "[$shortcode";
  if ($atts ) 
    $example .= " $atts";
  $example .= ']';
  sp();
  stag( "code" );
  e( $example ); 
  etag( "code" ); 
  ep();
  //p( $example );
	$saved = bw_global_post();
	$expanded = bw_expand_shortcode( $example );
	bw_global_post( $saved );
  e( $expanded );
  bw_trace2( $expanded, "expanded", true, BW_TRACE_DEBUG );
  //bw_backtrace();
}

/**
 * Syntax for [playlist] shortcode
 *
 * @link https://codex.wordpress.org/Playlist_Shortcode 
 */
function playlist__syntax( $shortcode="playlist" ) {
  $syntax = array( "ids" => BW_::bw_skv( null, "<i>" . __( "id1,id2", null ) . "</i>", __( "IDs for the playlist", null ) )
                 , "id" => BW_::bw_skv( __( "current post", null ) , "<i>" . __( "id", null ) . "</i>", __( "Post from which the playlist is chosen", "null" ) )
                 , "type" => BW_::bw_skv( "audio", "video", __( "Content type", null ) )
                 , "order" => BW_::bw_skv( "ASC", "DESC|RAND", __( "Ordering", null ) )
                 , "orderby" => BW_::bw_skv( "post__in", "menu_order|ID|title|rand", __( "Order by field", null ) )
                 , "include" => BW_::bw_skv( null, "<i>" . __( "id1,id2", null ) . "</i>", __( "IDs to include", null ) )
                 , "exclude" => BW_::bw_skv( null, "<i>" . __( "id1,id2", null ) . "</i>", __( "IDs to exclude", null ) )
                 , "style" => BW_::bw_skv( "light", "dark", __( "Playlist style", null ) )
                 , "tracklist" => BW_::bw_skv( "true", "false", __( "Display track list?", null ) )
                 , "tracknumbers" => BW_::bw_skv( "true", "false", __( "Display track numbers?", null ) )
                 , "images" => BW_::bw_skv( "true", "false", __( "Display images?", null ) )
                 , "artists" => BW_::bw_skv( "true", "false", __( "Display artists?", null ) )
                 );
  return( $syntax );
} 

/**
 * Expand the shortcode
 * 
 * Returns the cached version if available
 * 
 * Note: The cached version may have been created for a different locale
 * In tests we might need to reset it by calling bw_expand_shortcode with null
 * 
 * @param string $example shortcode to be expanded. Cannot be null in PHP 8.2
 * @return string the generated HTML
 */
function bw_expand_shortcode( $example='' ) {
	static $previous_example = null;
	static $previous_expanded = null;
	static $previous_locale = null; 
	$locale = get_locale();
	if ( ( $previous_example == $example ) && ( $previous_locale == $locale ) ) {
		$expanded = $previous_expanded;
	} else {
		bw_save_scripts(); 
		bw_push();

		$expanded = apply_filters( 'the_content', $example );
		bw_pop();
		bw_save_scripts(); 
		$previous_example = $example;
		$previous_expanded = $expanded;
		$previous_locale = $locale;
	}
	return $expanded;
}

} /* end !defined */

