<?php	
if ( !class_exists( 'TSP_Easy_Dev_Posts' ) )
{
	/**
	 * API implementations for LAPDI Easy Dev Pro's Posts class - Manages post fields and data
	 * @package 	TSP_Easy_Dev
	 * @author 		sharrondenice, letaprodoit
	 * @author 		Sharron Denice, Let A Pro Do IT!
	 * @copyright 	2021 Let A Pro Do IT!
	 * @license 	APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
	 * @version 	1.2.9
	 */
	final class TSP_Easy_Dev_Posts
	{
		/**
		 * A reference to the TSP_Easy_Dev_Options object
		 *
		 * @var object
		 */
		private $options	= null;
		/**
		 * A boolean to turn debugging on for this class
		 *
		 * @ignore
		 *
		 * @var boolean
		 */
		private $debugging 	= false;
		
		/**
		 * Constructor
		 *
		 * @since 1.0
		 *
		 * @param object $options Required - reference to the TSP_Easy_Dev_Options class
		 *
		 * @return void
		 */
		public function __construct( $options )
		{
			$this->options = $options;
			
			add_action( 'save_post', 				array( $this, 'update_post_metadata' ));
			add_action( 'admin_menu', 				array( $this, 'load_post_metadata_box' ));
		}//end __construct

	
		/**
		 *  Display the form fields on the post page
		 *
		 * @ignore - Must be public, used by WordPress hooks
		 *
		 * @since 1.0
		 *
		 * @param void
		 *
		 * @return void - output to screen
         *
         * @throws SmartyException
		 */
		public function add_post_metadata_fields ()
		{
			global $post;
			
			if (!empty ( $post ))
			{
				$post_fields = get_option( $this->options->get_value('post-fields-option-name') );
				$defaults = new TSP_Easy_Dev_Data ( $post_fields, 'post' );
	
			    $default_fields = $defaults->get_values();
			    
			    $post_data = array();
			    
			    // loop through the default fields in order to get all of the post
			    // data in the database
			    if ( ! empty ( $default_fields ))
			    {
				    foreach ( $default_fields as $key => $value )
				    {
						$post_data[$key]    = get_post_meta( $post->ID, $key, 1 );
				    }//end foreach
				    
				    $defaults->set_values( $post_data );
				    $form_fields = $defaults->get_values( true );
			
					$smarty = new TSP_Easy_Dev_Smarty( $this->options->get_value('smarty_template_dirs'), 
						$this->options->get_value('smarty_cache_dir'), 
						$this->options->get_value('smarty_compiled_dir'), true );
						
			    	$smarty->assign( 'shortcode_fields', $form_fields );
			    	$smarty->assign( 'class', 'widefat' );
				    $smarty->display( 'easy-dev-shortcode-form.tpl' );
			    }//end if
			}//end if
		}//end add_post_metadata_fields

	
		/**
		 *  Register the hook to display post fields to the screen
		 *
		 * @ignore - Must be public, used by WordPress hooks
		 *
		 * @since 1.0
		 *
		 * @param void
		 *
		 * @return void
		 */
		public function load_post_metadata_box ()
		{
			add_meta_box(
				'post_info', 
				__( $this->options->get_value('title') . ' Information', $this->options->get_value('name') ) , 
				array( $this, 'add_post_metadata_fields' ), 
				'post', 'side', 'high');
		}//end load_post_metadata_box

	
		/**
		 *  Implementation to update post metadata
		 *
		 * @ignore - Must be public, used by WordPress hooks
		 *
		 * @since 1.0
		 *
		 * @param integer $post_ID Required the id of the post to update
		 *
		 * @return void
		 */
		public function update_post_metadata ( $post_ID )
		{
		    $article = get_post($post_ID);
		    
		    if (!empty ( $article ))
		    {
				$post_fields = get_option( $this->options->get_value('post-fields-option-name') );
				$defaults = new TSP_Easy_Dev_Data ( $post_fields, 'post' );
	
			   	$defaults->set_values( $_POST );
			    
			    $form_fields = $defaults->get_values();
			    
			    if (!empty ( $form_fields ))
			    {
				    foreach ( $form_fields as $key => $value )
				    {
					    if ( !empty ( $value )) 
					    {
					        add_post_meta( $article->ID, $key, $value, TRUE ) or update_post_meta( $article->ID, $key, $value );
					    }//end if 
					    else 
					    {
					        delete_post_meta( $article->ID, $key );
					    }//end else
				    }//end foreach
			    }//end if
		    }//end if
		}//end update_post_metadata

		/**
		 * Find and return an image from the post
		 *
		 * @api
		 *
		 * @since 1.0
		 *
		 * @param object $a_post  - the post to parse
		 * @param int $thumb_width  - the width to set the image to
		 * @param int $thumb_height  - the height to set the image to
		 *
		 * @return string $media return the the first media item found
		 */
		public function get_post_media( &$a_post, $thumb_width, $thumb_height )
		{
	       	$media 		= null;
	        $img     	= $this->get_post_thumbnail( $a_post, $thumb_width, $thumb_height );
	        
	        if ( empty( $img ) )
	        {
	        	$video = $this->get_post_video( $a_post );
	        
		       	if ( !empty( $video ) )
		       	{
		       		$video = $this->adjust_post_video( $video, $thumb_width, $thumb_height);
		       		$media = "<code>$video</code>";
		       	}//end if
		    }//endif
		    else
		    {
				$media = "<img align='left' src='$img' alt='{$a_post->post_title}' width='$thumb_width' height='$thumb_height'/>";
		    }//end else
		    
		    return $media;
		}//end get_post_media
	
		/**
		 * Return an array of the post fields
		 *
		 * @api
		 *
		 * @since 1.0
		 *
		 * @param int $ID  - the post's ID
		 *
		 * @return array $post_fields return an array of fiels stored in the post
		 */
		public function get_post_fields( $ID )
		{
			$new_post_fields = array();
			       
			$post_fields = get_option( $this->options->get_value('post-fields-option-name') );
			$defaults = new TSP_Easy_Dev_Data ( $post_fields, 'post' );
			
			$fields = $defaults->get_values();
	
	        if (!empty ( $fields ))
	        {
		        foreach ( $fields as $key => $default_value )
		        {
			        // get the quote for the post
			        $value_arr = get_post_custom_values( $key, $ID );
			        
			        if (!empty( $value_arr ))
			        	$new_post_fields[$key] = $value_arr[0];
			        else
			        	$new_post_fields[$key] = "";
		        }//end foreach
	        }//endif
	        		
			return $new_post_fields;
		}//end get_post_fields
	
		/**
		 * Find and return an image from the post
		 *
		 * @api
		 *
		 * @since 1.0
		 *
		 * @param object $a_post  - the post to parse
		 * @param int $thumb_width Optional  - the width to set the image to
		 * @param int $thumb_height Optional  - the height to set the image to
		 *
		 * @return string $img return the the first image found
		 */
		public function get_post_thumbnail( &$a_post, $thumb_width = null, $thumb_height = null )
		{
		   	$img = null;
		   
		   	if ( !empty( $a_post ))
		   	{
				ob_start();
				ob_end_clean();
				
				$thumb_size = array();
				
				// if thumb size passed in use it
				// passing sizes to get_the_post_thumbnail is necessary
				// to get a URL link that has the correct thumbnail size
				// which changes based on the thumbnail size, if no size
				// is passed in it will return the size of the thumbnail
				// which is very small and will be stretched to the size the user requires
				if ($thumb_width && $thumb_height)
				{
					$thumb_size = array($thumb_width, $thumb_height);
				}//end if
				
				// Check to see if the post has a featured image
				// if it does then we will parse the image for matches
				// else we will parse the content for matches
				$search = get_the_post_thumbnail($a_post->ID, $thumb_size);
				
				if (empty($search))
				{
					$search = $a_post->post_content;
				}//end if
				
				// If it does not have a featured image check the content for an image
				$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $search, $matches);
				
				if ( !empty( $matches[1] ))
				{
					$img    = $matches[1][0];
				}//end if
			}//end if
		    
		   	return $img;
		}//end get_post_thumbnail
		
		/**
		 * Find and return a video from the post
		 *
		 * @api
		 *
		 * @since 1.0
		 *
		 * @param object $a_post  - the post to parse
		 *
		 * @return string $video return the the first video found
		 */
		public function get_post_video( &$a_post )
		{
		    $video = null;
		    
		    
		   	if ( !empty( $a_post ))
		   	{
			    ob_start();
			    ob_end_clean();
			    
			    $output = preg_match_all('/<code>(.*?)<\/code>/i', $a_post->post_content, $matches);
				if ( !empty( $matches[1] ))
				{
					$video    = $matches[1][0];
				}//end if
			    
			   	// if video wasn't found look for iframes
			    if ( empty( $video ) )
			    {
				    //if its not wrapped in the code tags find the other methods of viewing videos
				    $output = preg_match_all('/<iframe (.*?)>(.*?)<\/iframe>/i', $a_post->post_content, $matches);
					if ( !empty ( $matches[0] ))
					{
						$video    = $matches[0][0];
					}//endif
			    }
			    
			    // if iframes weren't found look for flash
			    if ( empty( $video ) )
			    {
				    //if its not wrapped in the code tags find the other methods of viewing videos
				    $output = preg_match_all('/<object (.*?)>(.*?)<\/object>/i', $a_post->post_content, $matches);
					if ( !empty ( $matches[0] ))
					{
						$video    = $matches[0][0];
					}//endif
			    }
		   	}//end if
		    
		    return $video;
		}//end get_post_video
		
		/**
		 * Set the width and height in the the video string
		 *
		 * @api
		 *
		 * @since 1.0
		 *
		 * @param string $video  - the video to parse
		 * @param string $width  - the width of the video
		 * @param string $height  - the height of the video
		 *
		 * @return string $video return the the updated video string
		 */
		public function adjust_post_video($video, $width, $height)
		{
			$video = preg_replace('/width="(.*?)"/i', 'width="'.$width.'"', $video);
			$video = preg_replace('/height="(.*?)"/i', 'height="'.$height.'"', $video);
			
			$video = preg_replace('/width=\'(.*?)\'/i', 'width=\''.$width.'\'', $video);
			$video = preg_replace('/height=\'(.*?)\'/i', 'height=\''.$height.'\'', $video);
			
			return $video;
		}//end adjust_post_video
	
	}//end TSP_Easy_Dev_Posts
}//end if
