<?php
/*
* Utility returns lists of images
* Version 2
* Retrieve Featured Images for Posts
*/


if (!class_exists('media_gallery_pro_image_retrieve_03')):

	class media_gallery_pro_image_retrieve_03 extends featured_image_pro_image_retrieve_03
{
	private $debug_log = FALSE;
	private $debug = false;
	private $debug_query = false;

	/*
       * Get Featured Images
       * Wp_query
       * atts - Query Arguments
       * options - Plugin Options
       * Returns object with list of images and navigation content
      */

	public function wordpress_media_images($attobject, $atts, $options)
	{


		$debug_query = isset( $options['debug_query'] ) ? proto_boolval( $options['debug_query'] ) : false;

		if ( $debug_query != FALSE ) //var dump the request
			add_filter( 'posts_request', array( $this, 'dump_request' ), 17  );//show the query
		wp_reset_postdata(  );


		$_qattachments   = new WP_Query($atts);// get_posts($atts);             //Get the new query

		if ( $debug_query != FALSE ) //var dump the request
			remove_filter( 'posts_request', array( $this, 'dump_request' ), 17  );//show the query
		//error_log(print_r($atts,true));
		$attachments = array();          //The array for the attachment info

		$maximgwidth = 0;
		$maxheight = isset( $options['maxheight'] ) ? sanitize_text_field( $options['maxheight'] ) : '';
		$maxwidth = isset( $options['maxwidth'] ) ? sanitize_text_field( $options['maxwidth'] ) : '';

		if ( ! (is_numeric( $maxwidth ) || strpos( $maxwidth, 'px' ) > 0 ) )
			$maxwidth = '';
		if ( ! (is_numeric( $maxheight ) || strpos( $maxheight, 'px' ) > 0 ) )
			$maxheight = '';
		$post_count = 0;
		while ( $_qattachments->have_posts() ):$_qattachments->the_post();


		{
			$proto_post = new stdClass();
			$post_count++;
			//Get the image class where our info will be stored.
			$id = get_the_id(); // $attachment->ID;
			$proto_post->post_id = $id;

			$attachment = get_post($id);
			$proto_post = $this->proto_get_post_details( $id,  $attachment, $options, $maxheight, $maxwidth );
			$attmeta = wp_get_attachment_metadata( $id );
			if (isset($attmeta['image-meta']))
				$img_meta = $attmeta['image_meta'];
			else
				$img_meta = null;

			$proto_post->extra = '';
			$proto_post->item = $post_count;
			//this filter returns content that displays under the title text
			$proto_post->extra = apply_filters('media_image_after_category', $proto_post->extra);   //Apply any extra filters to add content under the title
			//Captions
			$proto_post->image_meta = $img_meta;
			$proto_post->caption  = $proto_post->title;//  $this->proto_get_post_excerpt($attachment, $options);

			$proto_post = apply_filters( 'proto_snap_post_object', $proto_post, $options, $attachment );
			$proto_post = apply_filters( 'proto_snap_image_object', $proto_post, $options, $attachment ); //same as above, here for backwards compatibility

			$maximgwidth = $this->proto_max_width($maximgwidth, $proto_post,  $maxwidth);

			$proto_post->link_url = get_post_permalink($id);  //Link to post
			$proto_post->excerpt = $this->proto_get_post_excerpt($attachment, $options, $proto_post->link_url);
			$proto_post = apply_filters('proto_post', $proto_post, $attachment, $options); //add additional info to the proto_post object

			$attachments[] = $proto_post;

		}
		endwhile;

		do_action( 'proto_masonry_after_query', $_qattachments, $atts );
		$attobject->attachments = $attachments;

		$attobject = apply_filters('proto_wordpress_media_images', $attobject, $options, $_qattachments);
		$attobject->maximgWidth = $maximgwidth;
		$attobject = apply_filters( 'proto_masonry_attachments', $attobject, $options, $_qattachments );

		wp_reset_query();
		return $attobject;
	}

	/**
	 * wordpress_set_options function.
	 * Set local class options and filter attributesthis i
	 *
	 * @access public
	 * @param array   $atts    - query attributes
	 * @param array   $options - plugin options
	 * @return filtered attributes
	 */
	function wordpress_set_options( $atts, $options ) {
		$this->debug       = isset( $options['debug'] ) ? proto_boolval( $options['debug'] ) : false;
		$this->debug_query = isset( $options['debug_query'] ) ? proto_boolval( $options['debug_query'] ):false;
		$this->debug_log   = isset( $options['debug_log'] )? proto_boolval( $options['debug_log'] ):false;
		$this->is_single   = is_single();
		$atts              = apply_filters( 'proto_masonry_settings', $atts, $options );
		return $atts;
	}

	/**
	 * wordpress_media_attributes function.
	 * Parse the query attributes
	 * @access public
	 * @param array $atts - attributes
	 * @param array $options - options
	 * @return parsed attributes
	 */
	function wordpress_media_attributes($atts, $options)
	{

		$atts = $this->wordpress_set_options($atts, $options);

		proto_functions::debug_writer( '(Media) Before Query Parsed Arguments', $atts, $this->debug, $this->debug_log );

		/* Apply filters to the attributes*/

		$postatts = $atts;
		//Default query arguments
		$postatts = shortcode_atts(array
			(
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'posts_per_page' => get_option( 'posts_per_page' ),         //default to the wordpress settings
				'order'    => 'DESC',
				'orderby'   => '',
				'post_status'    => 'inherit',
			), $postatts);
		$postatts['post_type'] = 'attachment';
		$atts = array_merge($atts, $postatts); //merge in anything back in that is not in default array

		proto_functions::debug_writer( '(Media) Medium Query Parsed Arguments', $atts, $this->debug, $this->debug_log );





		if (isset($atts['ignore_sticky_posts']))
			$atts['ignore_sticky_posts'] = proto_boolval($atts['ignore_sticky_posts']);
		//If category is used instead of cat, fill in category
		if (isset($atts['category']) && !isset($atts['cat']))
			$this->atts['cat'] = $this->atts['category'];
		//Include only posts with thumbnails

		$atts = apply_filters('proto_wordpress_post_attributes', $atts, $options);

		proto_functions::debug_writer( '(Media) Final Query Parsed Arguments', $atts, $this->debug, $this->debug_log );

		return $atts;
	}



}
endif;