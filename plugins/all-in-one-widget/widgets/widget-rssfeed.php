<?php
/**
 * Rss Feed Widget Class
 */
if ( !defined('ABSPATH')) exit;

class Themeidol_rss_feed_widget extends WP_Widget {

	//Constructor
	function __construct() {

		add_action('wp_enqueue_scripts', array(&$this, 'register_rss_feed_style'));
		add_filter( 'themeidol_item_attributes', array(&$this,'themeidol_add_item_padding'), 10, 2 );
		add_filter( 'themeidol_item_attributes', array(&$this,'themeidol_classes_item' ));
		add_filter( 'themeidol_default_image', array(&$this,'themeidol_define_default_image') );
		add_filter( 'themeidol_default_error', array(&$this,'themeidol_default_error_notice'), 9, 2 );
        parent::__construct( false, $name = __( 'Themeidol- RSS Feeds', 'themeidol-all-widget' ) );
		// Refreshing the widget's cached output with each new post
	    add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
	    add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
	    add_action( 'delete_attachment', array( $this, 'flush_group_cache' ) );
	    add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );   
    }

	 /***************************************************************
	 * Enqueue Rss Feed CSS
	 ***************************************************************/
	function register_rss_feed_style() {
		wp_register_style( 'rss-feed-style', THEMEIDOL_WIDGET_CSS_URL.'rssfeed-style.css', array());
	}

	//Widget form creation
	function form( $instance ) {

		//Check values
		if( $instance ) {
			
			$title 			= esc_attr( $instance[ 'title' ] );
			$feeds			= esc_attr( $instance[ 'feeds' ] );
			$max 			= esc_attr( $instance[ 'max' ] );
			$target 		= esc_attr( $instance[ 'target' ] );
			$titlelength 	= esc_attr( $instance[ 'titlelength' ] );
			$meta 			= isset( $instance['meta']) ?wp_unslash( $instance['meta'] ) :array();;
			$summary 		= esc_attr( $instance[ 'summary' ] );
			$summarylength 	= esc_attr( $instance[ 'summarylength' ] );
			$thumb 			= esc_attr( $instance[ 'thumb' ] );
			$default 		= esc_attr( $instance[ 'default' ] );
			$size 			= esc_attr( $instance[ 'size' ] );
			$keywords_title = esc_attr( $instance[ 'keywords_title' ] );
			
		} else {
			
			$title = '';
			$feeds = '';
			$max = '';
			$target = '';
			$titlelength = '';
			$meta = '';
			$summary = '';
			$summarylength = '';
			$thumb = '';
			$default = '';
			$size = '';
			$keywords_title = '';
			
		}
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'themeidol-all-widget' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'feeds' ); ?>"><?php _e( 'The feed(s) URL (comma separated URL(s)).', 'themeidol-all-widget' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'feeds' ); ?>" name="<?php echo $this->get_field_name( 'feeds' ); ?>" type="text" value="<?php echo $feeds; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'max' ); ?>"><?php _e( 'No. of items to display.', 'themeidol-all-widget' ); ?></label>
			<input class="widefat"  id="<?php echo $this->get_field_id( 'max' ); ?>" name="<?php echo $this->get_field_name( 'max' ); ?>" type="text" value="<?php echo $max; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'target' ); ?>"><?php _e( 'Target for the link', 'themeidol-all-widget' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'target' ); ?>" name="<?php echo $this->get_field_name( 'target' ); ?>" class="widefat">
			<?php
				$options = array( '_blank', '_parent', '_self', '_top', 'framename' );
				foreach ( $options as $option) {
					echo '<option value="' . $option . '" id="' . $option . '"', $target == $option ? ' selected="selected"' : '', '>', $option, '</option>';
				}
			?>
			</select>
		</p>		
		<p>
			<label for="<?php echo $this->get_field_id( 'titlelength' ); ?>"><?php _e( 'Excerpt of the title after X characters.', 'themeidol-all-widget' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'titlelength' ); ?>" name="<?php echo $this->get_field_name( 'titlelength' ); ?>" type="text" value="<?php echo $titlelength; ?>" />
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'meta' ); ?>" name="<?php echo $this->get_field_name( 'meta' ); ?>" type="checkbox" value="1" <?php checked( '1', $meta ); ?> />
			<label for="<?php echo $this->get_field_id( 'meta' ); ?>"><?php _e( 'Display the date of publication and the author name?', 'themeidol-all-widget' ); ?></label>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'summary' ); ?>" name="<?php echo $this->get_field_name( 'summary' ); ?>" type="checkbox" value="1" <?php checked( '1', $summary ); ?> />
			<label for="<?php echo $this->get_field_id( 'summary' ); ?>"><?php _e( 'Display a description (abstract) of the retrieved item?', 'themeidol-all-widget' ); ?></label>
		</p>		
		<p>
			<label for="<?php echo $this->get_field_id( 'summarylength' ); ?>"><?php _e( 'Excerpt of the description (summary) after X characters.', 'themeidol-all-widget' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'summarylength' ); ?>" name="<?php echo $this->get_field_name( 'summarylength' ); ?>" type="text" value="<?php echo $summarylength; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'thumb' ); ?>"><?php _e( 'Display the first image of the content if available?', 'themeidol-all-widget' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'thumb' ); ?>" name="<?php echo $this->get_field_name( 'thumb' ); ?>" class="widefat">
			<?php			
				//Fix for versions before 2.3.1
				if ( $thumb == '1' ){
					$thumb = 'yes';
				} else if ( $thumb == '0' ) {
					$thumb = 'no';
				}

				$options = array( 
					array( 'no', __( 'No', 'themeidol-all-widget' ) ),
				  	array( 'yes', __( 'Yes', 'themeidol-all-widget' ) ),
					array( 'auto', __( 'Auto', 'themeidol-all-widget' ) )
				);

				foreach ( $options as $option) {
					echo '<option value="' . $option[0] . '" id="' . $option[0] . '"', $thumb == $option[0] ? ' selected="selected"' : '', '>', $option[1], '</option>';
				}
			?>
			</select>
		</p>	
		<p>
			<label for="<?php echo $this->get_field_id( 'default' ); ?>"><?php _e( 'Default thumbnail URL(no image is found0', 'themeidol-all-widget' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'default' ); ?>" name="<?php echo $this->get_field_name( 'default' ); ?>" type="text" value="<?php echo $default; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e( 'Thumblails dimension. (Do not include "px".) Eg: 150', 'themeidol-all-widget' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>" type="text" value="<?php echo $size; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'keywords_title' ); ?>"><?php _e( 'Display item if title contains specific keyword(s) (comma-separated list/case sensitive).', 'themeidol-all-widget' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'keywords_title' ); ?>" name="<?php echo $this->get_field_name( 'keywords_title' ); ?>" type="text" value="<?php echo $keywords_title; ?>" />
		</p>
				
		<?php
		
	}

	//Update widget
	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		
		$instance[ 'title' ]			= strip_tags( $new_instance[ 'title' ] );
		
		
		
		$instance[ 'feeds' ] 			= strip_tags( $new_instance[ 'feeds' ] );
		$instance[ 'max' ] 				= strip_tags( $new_instance[ 'max' ] );
		$instance[ 'target' ] 			= strip_tags( $new_instance[ 'target' ] );
		$instance[ 'titlelength' ] 		= strip_tags( $new_instance[ 'titlelength' ] );
		$instance[ 'meta' ] 			= isset( $new_instance['meta']) ?wp_unslash( $new_instance['meta'] ) :array();;
		$instance[ 'summary' ] 			= strip_tags( $new_instance[ 'summary' ] );
		$instance[ 'summarylength' ] 	= strip_tags( $new_instance[ 'summarylength' ] );
		$instance[ 'thumb' ] 			= strip_tags( $new_instance[ 'thumb' ] );
		$instance[ 'default' ] 			= strip_tags( $new_instance[ 'default' ] );
		$instance[ 'size' ] 			= strip_tags( $new_instance[ 'size' ] );
		$instance[ 'keywords_title' ] 	= strip_tags( $new_instance[ 'keywords_title' ] );
		
		return $instance;
		
	}
	/***************************************************************
	 * Define the default image thumbnail
	 ***************************************************************/
	function themeidol_define_default_image( $imageSrc ){
		return THEMEIDOL_WIDGET_IMAGES_URL.'feed-default.jpg';
	}



	/***************************************************************
	 * Default error message + log errors
	 ***************************************************************/
	function themeidol_default_error_notice( $error, $feedURL  ){
		//Write in the log file
		error_log( 'Themeidol- RSS Feeds - related feed: ' .$feedURL . ' - Error message: ' . $this->themeidol_array_obj_string( $error ) );
		//Display the error message
		return '<div id="message" class="error" data-error"' . esc_attr( $this->themeidol_array_obj_string( $error ) ) . '"><p>' . __('Sorry, this feed is currently unavailable or does not exists anymore.', 'themeidol-all-widget') . '</p></div>';
	}




	/***************************************************************
	 * Convert array or object into string
	 ***************************************************************/
	function themeidol_array_obj_string ( $error ){
		if ( is_array( $error ) || is_object( $error ) ) {
	         return print_r( $error, true );
	      } else {
	         return $error;
	      }
	}
	/***************************************************************
	 * Padding ratio based on image size
	 ***************************************************************/
	function themeidol_add_item_padding( $itemAttr, $sizes ){
		$paddinTop = number_format( ( 15 / 150 ) * $sizes[ 'height' ], 0 );
		$paddinBottom = number_format( ( 25 / 150 ) * $sizes[ 'height' ], 0 );
		$stylePadding = ' style="padding: ' . $paddinTop . 'px 0 ' . $paddinBottom . 'px"';
		return $itemAttr . $stylePadding;
	}



	/***************************************************************
	 * Feed item container class
	 ***************************************************************/
	function themeidol_classes_item( $itemAttr ){
		$classes = array( 'rss_item' );
		$classes = apply_filters( 'themeidol_add_classes_item', $classes );
		$classes = ' class="' . implode( ' ', $classes ) . '"';
		return $itemAttr . $classes;
	}
	/***************************************************************
	 * Main Rss Function function
	 ***************************************************************/
	function themeidol_rss( $atts, $content = '' ) {

		global $feedzyStyle;
		$feedzyStyle = true;
		$count = 0;

		//Load SimplePie if not already
		if ( !class_exists( 'SimplePie' ) ){
			require_once( ABSPATH . WPINC . '/class-feed.php' );
		}

		//Retrieve & extract shorcode parameters
		extract( shortcode_atts( array(
			"feeds" => '', 			//comma separated feeds url
			"max" => '5', 			//number of feeds items (0 for unlimited)
			"feed_title" => 'yes', 	//display feed title yes/no
			"target" => '_blank', 	//_blank, _self
			"title" => '', 			//strip title after X char
			"meta" => 'yes', 		//yes, no
			"summary" => 'yes', 	//strip title
			"summarylength" => '', 	//strip summary after X char
			"thumb" => 'yes', 		//yes, no, auto
			"default" => '', 		//default thumb URL if no image found (only if thumb is set to yes or auto)
			"size" => '', 			//thumbs pixel size
			"keywords_title" => '' 	//only display item if title contains specific keywords (comma-separated list/case sensitive)
			), $atts, 'themeidol_default' ) );

		//Use "shortcode_atts_feedzy_default" filter to edit shortcode parameters default values or add your owns.

		if ( !empty( $feeds ) ) {
			$feeds = rtrim( $feeds, ',' );
			$feeds = explode( ',', $feeds );
			
			//Remove SSL from HTTP request to prevent fetching errors
			foreach( $feeds as $feed ){
				$feedURL[] = preg_replace("/^https:/i", "http:", $feed);
			}

			if ( count( $feedURL ) === 1 ) {
				$feedURL = $feedURL[0];
			}
			
		}
		
		if ( $max == '0' ) {
			$max = '999';
		} else if ( empty( $max ) || !ctype_digit( $max ) ) {
			$max = '5';
		}

		if ( empty( $size ) || !ctype_digit( $size ) ){
			$size = '150';
		}
		$sizes = array( 'width' => $size, 'height' => $size );
		$sizes = apply_filters( 'themeidol_thumb_sizes', $sizes, $feedURL );

		if ( !empty( $title ) && !ctype_digit( $title ) ){
			$title = '';
		}

		if ( !empty($keywords_title)){
			$keywords_title = rtrim( $keywords_title, ',' );
			$keywords_title = array_map( 'trim', explode( ',', $keywords_title ) );
		}

		if ( !empty( $summarylength ) && !ctype_digit( $summarylength ) ){
			$summarylength = '';
		}

		if ( !empty( $default ) ) {
			$default = $default;
		
		} else {
			$default = apply_filters( 'themeidol_default_image', $default, $feedURL );
		}
	 
	 	//Load SimplePie Instance
	  	$feed = new SimplePie();
		$feed -> set_feed_url( $feedURL );
		$feed -> enable_cache( true );
		$feed -> enable_order_by_date( true );
		$feed -> set_cache_class( 'WP_Feed_Cache' );
		$feed -> set_file_class( 'WP_SimplePie_File' );
		$feed -> set_cache_duration( apply_filters( 'wp_feed_cache_transient_lifetime', 7200, $feedURL ) );
		do_action_ref_array( 'wp_feed_options', array( $feed, $feedURL ) );
		$feed -> strip_comments( true );
		$feed -> strip_htmltags( array( 'base', 'blink', 'body', 'doctype', 'embed', 'font', 'form', 'frame', 'frameset', 'html', 'iframe', 'input', 'marquee', 'meta', 'noscript', 'object', 'param', 'script', 'style' ) );
		$feed -> init();
		$feed -> handle_content_type();

		// Display the error message
		if ( $feed -> error() ) {
			$content .= apply_filters( 'themeidol_default_error', $feed -> error(), $feedURL );	
		}

		$content .= '<div class="themeidol-rss">';

		if ( $feed_title == 'yes' ) {

			$content .= '<div class="rss_header">';
			$content .= '<h2><a href="' . $feed->get_permalink() . '" class="rss_title">' . html_entity_decode( $feed->get_title() ) . '</a> <span class="rss_description"> ' . $feed->get_description() . '</span></h2>';
			$content .= '</div>';
			
		}

		$content .= '<ul>';

		//Loop through RSS feed
		$items = apply_filters( 'themeidol_feed_items', $feed->get_items(), $feedURL );
		foreach ( (array) $items as $item ) {

			$continue = apply_filters( 'themeidol_item_keyword', true, $keywords_title, $item, $feedURL );

			if ( $continue == true ) {

				//Count items
				if ( $count >= $max ){
					break;
				}
				$count++;

				//Fetch image thumbnail
				if ( $thumb == 'yes' || $thumb == 'auto' ) {
					$thethumbnail = $this->themeidol_retrieve_image( $item );
				}

				
				$itemAttr = apply_filters( 'themeidol_item_attributes', $itemAttr = '', $sizes, $item, $feedURL );

				//Build element DOM
				$content .= '<li ' . $itemAttr . '>';
				
				if ( $thumb == 'yes' || $thumb == 'auto' ) {
					
					$contentThumb = '';
					
					if ( ( ! empty( $thethumbnail ) && $thumb == 'auto' ) || $thumb == 'yes' ){
						
						$contentThumb .= '<div class="rss_image" style="width:' . $sizes['width'] . 'px; height:' . $sizes['height'] . 'px;">';
						$contentThumb .= '<a href="' . $item->get_permalink() . '" target="' . $target . '" title="' . $item->get_title() . '" >';
					
						if ( !empty( $thethumbnail )) {
							
							$thethumbnail = $this->themeidol_image_encode( $thethumbnail );
							$contentThumb .= '<span class="default" style="width:' . $sizes['width'] . 'px; height:' . $sizes['height'] . 'px; background-image:  url(' . $default . ');" alt="' . $item->get_title() . '"></span>';
							$contentThumb .= '<span class="fetched" style="width:' . $sizes['width'] . 'px; height:' . $sizes['height'] . 'px; background-image:  url(' . $thethumbnail . ');" alt="' . $item->get_title() . '"></span>';
						
						} else if ( empty( $thethumbnail ) && $thumb == 'yes' ) {
						
							$contentThumb .= '<span style="width:' . $sizes['width'] . 'px; height:' . $sizes['height'] . 'px; background-image:url(' . $default . ');" alt="' . $item->get_title() . '"></span>';
						
						}

						$contentThumb .= '</a>';
						$contentThumb .= '</div>';
						
					}

					//Filter: feedzy_thumb_output
					$content .= apply_filters( 'themeidol_thumb_output', $contentThumb, $feedURL );
					
				}
				
				$contentTitle = '';
				$contentTitle .= '<span class="title"><a href="' . $item->get_permalink() . '" target="' . $target . '">';
			   
				if ( is_numeric( $title ) && strlen( $item->get_title() ) > $title ) {

					$contentTitle .= preg_replace( '/\s+?(\S+)?$/', '', substr( $item->get_title(), 0, $title ) ) . '...';
				
				} else {

					$contentTitle .= $item->get_title();
				
				}
				
				$contentTitle .= '</a></span>';

				//Filter: feedzy_title_output
				$content .= apply_filters( 'themeidol_title_output', $contentTitle, $feedURL );

				$content .= '<div class="rss_content">';

				
				//Define Meta args
				$metaArgs = array(
							'author' => true,
							'date' => true,
							'date_format' => get_option( 'date_format' ),
							'time_format' => get_option( 'time_format' )
						);
						
				//Filter: feedzy_meta_args
				$metaArgs = apply_filters( 'themeidol_meta_args', $metaArgs, $feedURL );

				if ( $meta == 'yes' && ( $metaArgs[ 'author' ] || $metaArgs[ 'date' ] ) ) {

					$contentMeta = '';
					$contentMeta .= '<small>' . __( 'Posted', 'themeidol-all-widget' ) . ' ';

					if ( $item->get_author() && $metaArgs[ 'author' ] ) {
						
						$author = $item->get_author();
						if ( !$authorName = $author->get_name() ){
							$authorName = $author->get_email();
						}
						
						if( $authorName ){
							$domain = parse_url( $item->get_permalink() );
							$contentMeta .= __( 'by', 'themeidol-all-widget' ) . ' <a href="http://' . $domain[ 'host' ] . '" target="' . $target . '" title="' . $domain[ 'host' ] . '" >' . $authorName . '</a> ';
						}

					}
					
					if ( $metaArgs[ 'date' ] ) {
						$contentMeta .= __( 'on', 'themeidol-all-widget') . ' ' . date_i18n( $metaArgs[ 'date_format' ], $item->get_date( 'U' ) );
						$contentMeta .= ' ';
						$contentMeta .= __( 'at', 'themeidol-all-widget' ) . ' ' . date_i18n( $metaArgs[ 'time_format' ], $item->get_date( 'U' ) );
					}
					
					$contentMeta .= '</small>';
					
					//Filter: feedzy_meta_output
					$content .= apply_filters( 'themeidol_meta_output', $contentMeta, $feedURL );

				}
				if ( $summary == 'yes' ) {


					$contentSummary = '';
					$contentSummary .= '<p>';

					//Filter: feedzy_summary_input
					$description = $item->get_description();
					$description = apply_filters( 'themeidol_summary_input', $description, $item->get_content(), $feedURL );

					if ( is_numeric( $summarylength ) && strlen( $description ) > $summarylength ) {

						$contentSummary .= preg_replace( '/\s+?(\S+)?$/', '', substr( $description, 0, $summarylength ) ) . ' […]';
					
					} else {

						$contentSummary .= $description . ' […]';
					}

					$contentSummary .= '</p>';

					//Filter: feedzy_summary_output
					$content .= apply_filters( 'themeidol_summary_output', $contentSummary, $item->get_permalink(), $feedURL );

				}
				
				$content .= '</div>';
				$content .= '</li>';
				
			} //endContinue
			
		} //endforeach

		$content .= '</ul>';
		$content .= '</div>';
		return apply_filters( 'themeidol_global_output', $content, $feedURL );
		
	}//end of themeidol_rss

	/***************************************************************
	 * Retrive image from the item object
	 ***************************************************************/
	function themeidol_retrieve_image( $item ) {
		$thethumbnail = "";
		if ( $enclosures = $item->get_enclosures() ) {
			
			foreach( (array) $enclosures as $enclosure ){
				

				//item thumb
				if ( $thumbnail = $enclosure->get_thumbnail() ) {
					$thethumbnail = $thumbnail;
				}

				//media:thumbnail
				if ( isset( $enclosure->thumbnails ) ) {

					foreach ( (array) $enclosure->thumbnails as $thumbnail ) {
						$thethumbnail = $thumbnail;
					}
					
				}

				//enclosure
				if ( $thumbnail = $enclosure->embed() ) {
					
					
					$pattern = '/https?:\/\/.*\.(?:jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/iU';

					if ( preg_match( $pattern, $thumbnail, $matches ) ) {
						$thethumbnail = $matches[0];
					}
					
				}

				//media:content && strpos( $enclosure->type, 'image' ) !== false 
				foreach ( (array) $enclosure->get_link() as $thumbnail ) {

					$pattern = '/https?:\/\/.*\.(?:jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/iU';
					$imgsrc = $thumbnail;


					if ( preg_match( $pattern, $imgsrc, $matches )  ) {
						$thethumbnail = $matches[0];
						break;
					}
					
				}

				//break loop if thumbnail found
				if ( ! empty( $thethumbnail ) ) {
					break;
				}

			}
			
		}

		//xmlns:itunes podcast
		if ( empty( $thethumbnail ) ) {
			$data = $item->get_item_tags('http://www.itunes.com/dtds/podcast-1.0.dtd', 'image');
			if ( isset( $data['0']['attribs']['']['href'] ) && !empty( $data['0']['attribs']['']['href'] ) ){
				$thethumbnail = $data['0']['attribs']['']['href'];
			}
		}
		
		//content image
		if ( empty( $thethumbnail ) ) {

			$feedDescription = $item->get_content();
			$thethumbnail = $this->themeidol_returnImage( $feedDescription );
			
		}

		//description image
		if ( empty( $thethumbnail ) ) {
			
			$feedDescription = $item->get_description();
			$thethumbnail = $this->themeidol_returnImage( $feedDescription );
		
		}

		return $thethumbnail;
	}


	/***************************************************************
	 * Get an image from a string
	 ***************************************************************/
	function themeidol_returnImage( $string ) {
		$img = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
		$pattern = "/<img[^>]+\>/i";
		preg_match( $pattern, $img, $matches );
		if( isset( $matches[0] ) ){
			$blacklistCount = 0;
			foreach( $matches as $matche){
				$link = $this->themeidol_scrapeImage( $matche );
				$blacklist = array();
				$blacklist = apply_filters( 'themeidol_feed_blacklist_images', $this->themeidol_blacklist_images( $blacklist ) );
				foreach( $blacklist as $string ) {
					if ( strpos( (string) $link, $string ) !== false) {
						$blacklistCount++;
					}
				}
				if( $blacklistCount == 0) break;
			}
			if( $blacklistCount == 0) return $link;
		}
		return;
	}

	function themeidol_scrapeImage( $string, $link = '' ) {
		$pattern = '/src=[\'"]?([^\'" >]+)[\'" >]/';     
		preg_match( $pattern, $string, $link );
		if( isset( $link[1] ) ){
			$link = urldecode( $link[1] );
		}
		return $link;
	}

	/***************************************************************
	 * List blacklisted images to prevent fetching emoticons
	 ***************************************************************/
	function themeidol_blacklist_images( $blacklist ) {
		$blacklist = array(
			'frownie.png',
			'icon_arrow.gif',
			'icon_biggrin.gif',
			'icon_confused.gif',
			'icon_cool.gif',
			'icon_cry.gif',
			'icon_eek.gif',
			'icon_evil.gif',
			'icon_exclaim.gif',
			'icon_idea.gif',
			'icon_lol.gif',
			'icon_mad.gif',
			'icon_mrgreen.gif',
			'icon_neutral.gif',
			'icon_question.gif',
			'icon_razz.gif',
			'icon_redface.gif',
			'icon_rolleyes.gif',
			'icon_sad.gif',
			'icon_smile.gif',
			'icon_surprised.gif',
			'icon_twisted.gif',
			'icon_wink.gif',
			'mrgreen.png',
			'rolleyes.png',
			'simple-smile.png',
		);
		return $blacklist;
	}


	/***************************************************************
	 * Image name encode + get image url if in url param
	 ***************************************************************/
	function themeidol_image_encode( $string ) {	
		//Check if img url is set as an URL parameter
		$url_tab = parse_url( $string );
		if( isset( $url_tab['query'] ) ){
			preg_match_all( '/(http|https):\/\/[^ ]+(\.gif|\.GIF|\.jpg|\.JPG|\.jpeg|\.JPEG|\.png|\.PNG)/', $url_tab['query'], $imgUrl );
			if( isset( $imgUrl[0][0] ) ){
				$string = $imgUrl[0][0];
			}
		}
		
		//Encode image name only en keep extra parameters
		$query = $extention = '';
		$url_tab = parse_url( $string );
		if( isset( $url_tab['query'] ) ){
			$query = '?' . $url_tab['query'];
		}
		$path_parts = pathinfo( $string );
		$path = $path_parts['dirname'];
		$file = rawurldecode( $path_parts['filename'] );
		$extention = pathinfo( $url_tab['path'], PATHINFO_EXTENSION );
		if( !empty( $extention ) ){
			$extention =  '.' . $extention;
		}
		
		//Return a well encoded image url
		return $path . '/' . rawurlencode( $file ) . $extention . $query;
	}
	public function flush_widget_cache() {
    		wp_cache_delete( 'themeidol-rssfeed', 'widget' );
  	}

	//Display widget
	function widget( $args, $instance ) {
		 $cache    = (array) wp_cache_get( 'themeidol-rssfeed', 'widget' );

         if(!is_array($cache)) $cache = array();
      
         if(isset($cache[$args['widget_id']])){
            echo $cache[$args['widget_id']];
            return;
         }
      	ob_start();
		extract( $args );
		wp_enqueue_style('rss-feed-style');
		$title = apply_filters( 'widget_title', $instance[ 'title' ] );
		if (strpos($before_widget, 'widget ') !== false) {
            $before_widget = preg_replace('/widget /', "idol-widget ", $before_widget, 1);
        }	
		//Display the widget body
		echo $before_widget;
		
		//Check if title is set
		if ( $title )
			echo $before_title . $title . $after_title;



		$items = array( 'meta', 'summary' );
		foreach( $items as $item ){
			
			if( $instance[ $item ] == true ){
				
				$instance[ $item ] = 'yes';
				
			} else {
				
				$instance[ $item ] = 'no';
				
			}
			
		}
		
		//Fix for versions before 2.3.1
		if ( $instance[ 'thumb' ] == '1' ){
			
			$instance[ 'thumb' ] = 'yes';
			
		} else if ( $instance[ 'thumb' ] == '0' ) {
			
			$instance[ 'thumb' ] = 'no';
			
		}

		//Call the shortcode function
		echo $this->themeidol_rss( array(
			"feeds" 			=> $instance[ 'feeds' ],
			"max" 				=> $instance[ 'max' ],
			"feed_title" 		=> 'no',
			"target" 			=> $instance[ 'target' ],
			"title" 			=> $instance[ 'titlelength' ],
			"meta" 				=> $instance[ 'meta' ],
			"summary" 			=> $instance[ 'summary' ],
			"summarylength"	 	=> $instance[ 'summarylength' ],
			"thumb" 			=> $instance[ 'thumb' ],
			"default" 			=> $instance[ 'default' ],
			"size" 				=> $instance[ 'size' ],
			"keywords_title" 	=> $instance[ 'keywords_title' ]
		) );

		echo $after_widget;
		$widget_string = ob_get_flush();
		$cache[$args['widget_id']] = $widget_string;
		wp_cache_add('themeidol-rssfeed', $cache, 'widget');
	
	}
	
}
add_action( 'widgets_init', create_function( '', 'return register_widget("Themeidol_rss_feed_widget");' ) );