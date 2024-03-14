<?php
/*
 * Utility returns lists of images. When working with custom posts, you should subclass this
 * Retrieve Featured Images & Fields for Posts
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
if ( !class_exists( 'featured_image_pro_image_retrieve_03' ) ):
	/**
	 * featured_image_pro_image_retrieve_03 class.
	 */
	class featured_image_pro_image_retrieve_03 {
	private $is_single;
	private $base_url;
	private $front_page;
	private $debug;
	private $debug_log;
	private $debug_query;
	private $excerpt_length;
	/**
	 * wordpress_featured_images function.
	 * retreive the attribute object of images and navigation
	 *
	 * @access public
	 * @param array   $atts    - query arguments
	 * @param mixed   $options - plugin options
	 * Returns object with list of images and navigation content
	 */
	function wordpress_featured_images( $attobject, $atts, $options ) {

		$default_excerpt_length = apply_filters("excerpt_length", 55); //get the default excerpt length
		$this->excerpt_length = isset( $options['excerptlength'] ) && intval( $options['excerptlength'] ) != 0 ?  intval( $options['excerptlength'] ) : $default_excerpt_length; 		   //get the excerpt length from the settings
		if ( $this->excerpt_length != $default_excerpt_length )		   //add a filter if necessary
			add_filter( 'excerpt_length', array ($this, 'custom_excerpt_length'), 999 );

		$imagesize = sanitize_text_field( $options['imagesize'] );//Get the requested image size
		$this->debug_query = isset( $options['debug_query'] ) ? proto_boolval( $options['debug_query'] ) : false;


		wp_reset_query();
		$_query = new WP_Query( $atts );//Get the new query

		if ( $this->debug_query != FALSE ) //var dump the request
			remove_filter( 'posts_request', array( $this, 'dump_request' ), 17  );//show the query

		$this->thumbnailgrid_removequeryfilter( $this->debug_query || $this->debug );//Apply any query filters
		//error_log(print_r($atts, true));
		$attachments = array();//The array for the attachment info
		$allwidths = 0;
		$count = 0;
		$maximgwidth = 0;
		$maxheight = isset( $options['maxheight'] ) ? sanitize_text_field( $options['maxheight'] ) : '';
		$maxwidth = isset( $options['maxwidth'] ) ? sanitize_text_field( $options['maxwidth'] ) : '';
		if ( ! (is_numeric( $maxwidth ) || strpos( $maxwidth, 'px' ) > 0 ) )
			$maxwidth = '';
		if ( ! (is_numeric( $maxheight ) || strpos( $maxheight, 'px' ) > 0 ) )
			$maxheight = '';
		$post_item = 0;
		//error_log( print_r( $_query, true ));
		while ( $_query->have_posts() ):$_query->the_post();
		//get the post id
		$post_item++;
		$postid = get_the_ID();//Post Id
		$qpost  = get_post( $postid );
		//Thumbnail Id
		$id     = get_post_thumbnail_id( $postid );

		$proto_post = $this->proto_get_post_details( $id,  $qpost, $options, $maxheight, $maxwidth );
		$proto_post = apply_filters( 'proto_snap_post_object', $proto_post, $options, $qpost );
		$proto_post->extra = '';
		$proto_post->item = $post_item;
		//this filter returns content that displays under the title text
		$proto_post->extra = apply_filters('media_image_after_category', $proto_post->extra);   //Apply any extra filters to add content under the title
		//Captions
		$proto_post->caption  = $proto_post->title;//Post Caption same as title
		$subcaption1 = isset($options['subcaption1']) ? sanitize_text_field($options['subcaption1']) : '';
		$subcaption2 = isset($options['subcaption2']) ? sanitize_text_field($options['subcaption2']) : '';
		$proto_post->subcaption = array();
		$proto_post->subcaption[$subcaption1] = apply_filters('proto_subcaption', '', $subcaption1, $postid);
		$proto_post->subcaption[$subcaption2] = apply_filters('proto_subcaption', '', $subcaption2, $postid);

		if ($id > 0)
		{
			$maximgwidth = $this->proto_max_width($maximgwidth, $proto_post,  $maxwidth);
			$proto_post->initialWidth = is_numeric( $proto_post->initialWidth) ? intval( $proto_post->initialWidth ) : 0;
			$allwidths = $allwidths + $proto_post->initialWidth;
			$count++;
		}
		$proto_post = apply_filters('proto_post', $proto_post, $qpost, $options); //apply any filters to the proto post object
		$proto_post->link_url = get_post_permalink($postid);//, true);  //Link to post

		$proto_post->excerpt = $this->proto_get_post_excerpt($qpost, $options, $proto_post->link_url);
		$attachments[] = $proto_post;
		endwhile;
		do_action( 'proto_masonry_after_query', $_query, $atts );
		$attobject->attachments = $attachments;
		$attobject->maximgWidth = $maximgwidth;
		if ($count > 0)
			$attobject->avgWidth = $allwidths / $count;
		else
			$attobject->avgWidth = 0;
		$attobject = apply_filters( 'proto_masonry_attachments', $attobject, $options, $_query );

		wp_reset_query();
		remove_filter( 'excerpt_length', array ($this, 'custom_excerpt_length'), 999 );

		return $attobject;
	}
	function custom_excerpt_length( $length ) {

		return $this->excerpt_length;
	}
	/**
	 * proto_get_post_excerpt function.
	 * Get the excerpt for the post
	 * @access public
	 * @param object $qpost - Wordpress post object
	 * @param array $options - Plugin options
	 * @return excerpt
	 */
	function proto_get_post_excerpt( $qpost, $options, $url )
	{   //process out the shortcodes
		$excerpt = '';
		if (isset($qpost->post_excerpt))
			$excerpt = $qpost->post_excerpt;
		if ($excerpt == '')
			$excerpt = $qpost->post_content;

		if ( isset( $options['showexcerpts'] ) && $options['showexcerpts'] )  {
			$excerpt_length = apply_filters('excerpt_length', 55);
			if ( isset( $options['htmlexcerpt']) && proto_boolval($options['htmlexcerpt'] == true ) )
				return $this->html_trim_excerpt($excerpt, $url, $excerpt_length);
			else
			{
					$excerpt = $this->proto_trim_excerpt( $excerpt, $url, $excerpt_length );
					return $excerpt;
			}
		}
	}
	/**
	 * proto_get_post_details function.
	 * Get the image for the post
	 * @access public
	 * @param int $id - the image id
	 * @param object $wp_post - the Wordpress post
	 * @param array $options - plugin options
	 * @param int $maxheight - maximum height
	 * @param int $maxwidth - maximum width
	 * @return new proto post object
	 */
	function proto_get_post_details(  $img_id, $wp_post, $options, $maxheight, $maxwidth )
	{

		$proto_post = new stdClass();
		$proto_post->id = $img_id;                                     //Set the media Id
		$proto_post->alt = get_post_meta($img_id, '_wp_attachment_image_alt', true);  //Alt
		$proto_post->meta = get_post_meta( $wp_post->ID );
		$proto_post->title = $wp_post->post_title;                    //Post Title
		$proto_post->title = apply_filters('proto_masonry_post_title', $proto_post->title, $img_id); //Apply any filters to the title
		$imagesize = sanitize_text_field($options['imagesize']);    //Get the requested image size
		$imgobj              = wp_get_attachment_image_src( $img_id, $imagesize );//Link to selected Image size
		if (intval($img_id) > 0 )
		{

			$proto_post = proto_functions::get_proto_post_sizes($proto_post, $imgobj, $imagesize);
			if ( isset( $proto_post->width ) && isset( $proto_post->height ) )
				$proto_post = $this->proto_max_adjust_size($proto_post, $options, $maxheight, $maxwidth);
		}
		$proto_post->attachment_url =   get_attachment_link($img_id); //Link to Attachment Page
		$proto_post->custom_button = $this->custom_link_button( $wp_post->ID, $options );

		return $proto_post;
	}
	/**
	 * proto_subcaption function.
	 * Get the subcaptions
	 * @access public
	 * @param string $proto_subcaption - subcaption
	 * @param string $subcaption - subcapton type
	 * @param int $postId - post id
	 * @return subcaption text
	 */
	function proto_subcaption( $proto_subcaption, $captiontype, $postId  )
	{
		switch( $captiontype )
		{
		case 'author':
			$ret = apply_filters('post_author', get_post_field('post_author', $postId));
			$ret = get_the_author_meta('display_name');
			break;
		case 'date':
			//  $ret = apply_filters('post_date', get_post_field('post_date', $postId));
			$ret = proto_cast_value(get_the_date( null, $postId ), 'date');
			break;
		case 'comment_count':
			$ret = proto_cast_value(get_comments_number( $postId ), 'int');
			break;
		default:
			$ret = '';
		}
		return $proto_subcaption . $ret;
	}
	/**
	 * proto_max_width function.
	 * Calculate the maximum image width
	 * @access public
	 * @param int $maximgWidth
	 * @param object $proto_post
	 * @param int $maxwidth
	 * @return maximgwidth
	 */
	function proto_max_width( $maximgWidth, $proto_post, $maxwidth )
	{
		$maximgWidth = max($maximgWidth, $proto_post->initialWidth);
		if ($maxwidth != 0 && $maxwidth != '')
			$maximgWidth = min($maximgWidth, intval($maximgWidth));
		return $maximgWidth;
	}
	//calculate inital width & height when height is set to auto
	/**
	 * proto_max_adjust_size function.
	 * Adjust the size of the image to be displayed based on options
	 * @access public
	 * @param object $proto_post - proto post object
	 * @param array $options - plugin options
	 * @param int $maxheight - max height allowed
	 * @param int $maxwidth - max width allowed
	 * @return updated proto post object
	 */
	function proto_max_adjust_size( $proto_post, $options, $maxheight, $maxwidth )
	{

		$proto_post->initialWidth = $proto_post->width;
		$width = isset( $options['imagewidth'] ) ? sanitize_text_field( $options['imagewidth'] ) : 'auto';
		$height = isset( $options['imageheight'] ) ? sanitize_text_field( $options['imageheight'] ) : 'auto';
		if ( intval( $height ) == 0 )
			$height = $proto_post->height;
		$proto_post->initialHeight = $height;
		if ( !$width || $width == 'auto' || $width == '0' || $width = '' )
		{
			if ($height && $height != 'auto' && $height != '0' && $height != '')
			{
				$proto_post->initialWidth = $this->calculate_width( $proto_post->width, $proto_post->height, intval( $height ), intval( $maxheight ), intval($maxwidth) );
			}
		}
		return $proto_post;
	}
	/**
	 * calculate_width function.
	 * calculate initial width when width is auto and height is set
	 * @access public
	 * @param int $originalWidth - original image width
	 * @param int $originalHeight - original image height
	 * @param int $targetHeight - target height
	 * @param int $maxHeight - max height
	 * @return calculated width
	 */
	function calculate_width( $originalWidth, $originalHeight,  $targetHeight, $maxHeight, $maxWidth=0 )
	{
		if ( intval( $maxHeight ) > 0 && intval( $targetHeight ) > intval( $maxHeight ))
			$targetHeight = $maxHeight;
		$ratio = intval( $originalWidth ) / intval( $originalHeight );
		$targetWidth =  intval($targetHeight) * $ratio;
		if (intval($maxWidth) > 0 && $targetWidth > $maxWidth)
			$targetWidth = $maxWidth;
		return $targetWidth;
	}
	/**
	 * proto_trim_excerpt function.
	 * Trim the excerpt if override excerpt length is set in options
	 *
	 * @access public
	 * @param string  $text
	 * @param int     $excerptlength (default: 55)
	 * @param url     $post permalink
	 * @return trimmed excerpt
	 */
	function proto_trim_excerpt( $text, $url, $excerptlength = 55) {
		$text = strip_shortcodes( $text );
		$text         = str_replace( ']]>', ']]&gt;', $text );

		$defurl = '<a href="' . $url . '">[...]</a>';
		$excerpt_more = apply_filters( 'excerpt_more', ' '. $defurl );
		$text         = wp_trim_words( $text, $excerptlength, $excerpt_more );


		return $text;
	}
/*	function wpse_allowedtags() {
    // Add custom tags to this string
        return '<script>,<style>,<br>,<em>,<i>,<ul>,<ol>,<li>,<a>,<p>,<img>,<video>,<audio>';
    }
*/


    function html_trim_excerpt($excerpt, $url, $excerptlength=55) {

            $excerpt = strip_shortcodes( $excerpt );
            $excerpt = str_replace(']]>', ']]&gt;', $excerpt);
			if ( !$excerpt )
				return $excerpt;
            //Set the excerpt word count and only break after sentence is complete.
            $excerpt_word_count = $excerptlength;
            $tokens = array();
            $excerptOutput = '';
            $count = 0;

            // Divide the string into tokens; HTML tags, or words, followed by any whitespace
            preg_match_all('/(<[^>]+>|[^<>\s]+)\s*/u', $excerpt, $tokens);

            foreach ($tokens[0] as $token) {

                if ($count >= $excerpt_word_count && preg_match('/[\,\;\?\.\!]\s*$/uS', $token)) {
                // Limit reached, continue until , ; ? . or ! occur at the end
                    $excerptOutput .= trim($token);
                    break;
                }

                // Add words to complete sentence
                $count++;
				if ($count > $excerpt_word_count)
					break;
                // Append what's left of the token
                $excerptOutput .= $token;

            }
			$defurl = '<a href="' . $url . '">[...]</a>';

			$excerpt = trim(force_balance_tags($excerptOutput));
			$excerpt_more = apply_filters( 'excerpt_more', ' '. $defurl );
			$excerpt .= $excerpt_more;


            return $excerpt;

        }






	/**
	 * custom_link_button function.
	 * Add a custom link button to the post
	 * @access public
	 * @param int post id - current post
	 * @param  options
	 * @return custom string
	 */
	function custom_link_button( $post_id, $options ) {

		$type = isset( $options['excerpt_custom_link_type'] ) ? sanitize_text_field( $options['excerpt_custom_link_type'] ) : 'button';
		$text = isset( $options['excerpt_custom_link_text'] ) ? sanitize_text_field( $options['excerpt_custom_link_text'] ) : '';
		$target = '';
		if ( isset ($options['openwindow'] ) )
			$target = 'target="_blank"';
		if ( isset ( $options['target'] ) )
			$target = $options['target'] != '' ? 'target="' . sanitize_text_field( $options['target' ] )  . '"' : '';
		if ($text != '')
			 return "<$type $target " . 'class="proto-link-button">' . "<a  href='". get_permalink($post_id) . "'>$text</a></$type>";
	}


	/**
	 * wordpress_set_options function.
	 * Save some local options class options and filter attributesthis i
	 *
	 * @access public
	 * @param array   $atts    - query attributes
	 * @param array   $options - plugin options
	 * @return filtered attributes
	 */
	function wordpress_set_options( $atts, $options ) {
		$this->debug       = isset( $options['debug'] ) ? proto_boolval($options['debug']) : false;
		$this->debug_query = isset( $options['debug_query'] )? proto_boolval($options['debug_query']):false;
		$this->debug_log   = isset( $options['debug_log'] )? proto_boolval($options['debug_log']):false;
		$this->is_single   = is_single();
		$atts              = apply_filters( 'proto_masonry_settings', $atts, $options );
		return $atts;
	}
	/*
	 *
	 * atts - query attributes
	 * options - current post options
	 */
	/**
	 * wordpress_post_attributes function.
	 * Parse the post options and query attributes
	 *
	 * @access public
	 * @param array   $atts    query attributes
	 * @param array   $options post options
	 * @return parsed and filtered query attributes
	 */
	function wordpress_post_attributes( $atts, $options ) {
		$this->debug_query = isset( $options['debug_query'] ) ? proto_boolval( $options['debug_query'] ) : false;
		$this->debug_log = isset( $options['debug_log'] ) ? proto_boolval( $options['debug_log'] ) : false;
		$this->debug = isset( $options['debug'] ) ? proto_boolval( $options['debug'] ) : false;

		$atts = $this->wordpress_set_options( $atts, $options );
		proto_functions::debug_writer( 'Before Parsed Post Arguments', $atts, $this->debug_query, $this->debug_log );

		/* Apply filters to the attributes*/
		$postatts = $atts;
		//Default query arguments
		$postatts = shortcode_atts( array
			(
				'post_type'            => 'post',
				'posts_per_page'      => get_option( 'posts_per_page' ), //default to the wordpress settings
				'order'               => 'DESC',
				'orderby'             => '',
				'ignore_sticky_posts' => TRUE,
			), $postatts );

		$atts = array_merge( $atts, $postatts );//merge in anything back in that is not in default array

 		 proto_functions::debug_writer( 'Parsed Post Arguments', $atts, $this->debug_query || $this->debug, $this->debug_log );

		if ( isset( $atts['ignore_sticky_posts'] ) ) {
			$atts['ignore_sticky_posts'] = proto_boolval( $atts['ignore_sticky_posts'] );
		}

		//**********************PARSE OUT THE POST CATEGORY
		$tax_query = array(); //create empty array
		//*******************************************************************
		//* Get the Category Array if this is the Widget
		if ( isset( $atts['catarray'] ) ) { //check for catarray from widget
			$catarray = $atts['catarray'];
			if (isset($catarray[0]))
				if ($catarray[0] == '0')
					unset($catarray[0]);
				if (!empty($catarray))
					$tax_query['terms'] = $catarray;
		}
		unset( $atts['catarray'] );
		//*******************************************************************
		//* Get the category or cat value from the shortcode
		if ( isset( $atts['category'] ) ) //check for category instead of cat
			$this->$atts['cat'] = $this->atts['category'];
		if ( isset( $atts['cat'] ) )
			if ( $atts['cat'] != '0' && $atts['cat'] != '' )  {  //explode into taxonomy query
				$tax_query['terms'] = explode( ',', $atts['cat'] );
				$tax_query['terms'] = array_map('trim', $tax_query['terms']);
			}
			//*******************************************************************
			//Add the updated taxonoy query to the full query
			if ( !empty( $tax_query ) ) {
				if ( isset( $atts['tax_query'] ) )
					$full_tax_query = $atts['tax_query'];
				else
					$full_tax_query = array();
				$tax_query['field'] = 'id';
				$tax_query['taxonomy'] = 'category';
				$full_tax_query[] = $tax_query;
				$atts['tax_query'] = $full_tax_query;
			}
		unset( $atts['cat'] );
		unset( $atts['category'] );
		//***********************************************************
		//**********Include only posts with thumbnails
		$noimage_posts = isset( $options['show_noimage_posts'] ) ?  proto_boolval( $options['show_noimage_posts'] ) : false;
		if ( ! $noimage_posts ) {
			$meta_query = array(
				'key'     => '_thumbnail_id',
				'compare' => 'EXISTS' );
			$atts['meta_query'] = array( $meta_query );
		}

		//***************************************************
		$atts = apply_filters( 'proto_wordpress_post_attributes', $atts, $options );

 		 proto_functions::debug_writer( 'Final Post Arguments', $atts, $this->debug_query || $this->debug, $this->debug_log );

		return $atts;
	}

	/**
	 * thumbnailgrid_removequeryfilter function.
	 * Add filters for the SQL Query
	 *
	 * @access public
	 * @param boolean $this->debug_query (default: FALSE) - if debug_query is true, log the query
	 * @return void
	 */
	function thumbnailgrid_removequeryfilter( $debug_query = FALSE ) {

		if ( $debug_query != FALSE ) {//var dump the request
			remove_filter( 'posts_request', array( $this, 'dump_request' ), 17  );//show the query
		}

	}
	/*
	 * debug_query - when true removes option to display the query
	 */
	/**
	/**
	 * dump_request function.
	 * dumps the query
	 *
	 * @access public
	 * @param string  $query
	 * @return unchanged query because it is called by the post filter
	 */
	function dump_request( $query ) {
 		 proto_functions::debug_writer( 'The Query', $query, $this->debug_query || $this->debug,$this->debug_log );

		return $query;
	}
}
endif;