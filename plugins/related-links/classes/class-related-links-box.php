<?php

if (!class_exists('Related_Links_Box')) 
{
class Related_Links_Box
{
	/**
	 * Class properties
	 */
	private $post_type;
	private $dirname;
	private $settings;
	private $offset;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{		
		$this->settings = get_option('related_links_settings');
		$this->dirname = dirname(__FILE__);

		// Set hooks
		add_action( 'admin_init', array( $this, 'init_hooks' ) );
	}
	
	/**
	 * Init hooks
	 */
	public function init_hooks()
	{	
		global $wpdb;
	
		// read the post type
		if( isset( $_GET['post'] ) )
		{
			// from the edit screen
			$this->post_type = get_post_type( $_GET['post'] );
		}
		else if( isset( $_POST['post_id'] ) )
		{
			// from the ajax request
			$this->post_type = get_post_type( $_POST['post_id'] );
		}
		else if( isset( $_POST['post_ID'] ) )
		{
			// from the save hook
			$this->post_type = get_post_type( $_POST['post_ID'] );
		}
		else
		{
			$this->post_type = null;
		}
		
		// load the hooks
		add_action( 'wp_ajax_load_links_list', array( $this, 'load_links_list_callback' ) );
		add_action( 'admin_print_styles-post.php', array( $this, 'add_styles' ) );
		add_action( 'admin_print_styles-post-new.php', array( $this, 'add_styles' ) );
		add_action( 'admin_print_scripts-post.php', array( $this, 'add_scripts' ) );
		add_action( 'admin_print_scripts-post-new.php', array( $this, 'add_scripts' ) );
		add_action( 'save_post', array( $this, 'save_box_data' ) );

		// show the box on all public post types
		/*
		$args = array(
			'public' => true, 
			'show_ui' => true
		);
		$public_post_types = get_post_types($args);
		*/
		add_action( 'add_meta_boxes', array( $this, 'add_box' ) );
	}
	
	/**
	 * Add styles to the boxes
	 */
	public function add_styles()
	{
		wp_enqueue_style('related-links-styles', plugins_url('/css/style.css', $this->dirname));
	}
	
	/**
	 * Add scripts to the boxes
	 */
	public function add_scripts()
	{
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('related-links-scripts', plugins_url('/js/script.js', $this->dirname), array('jquery', 'jquery-ui-core', 'jquery-ui-sortable'));
	}
	
	/**
	 * Add the box content
	 */
	public function add_box()
	{
		global $post_type;
		
		add_meta_box( 'related-links-box', __( 'Related Links', 'related-links' ), array( $this, 'create_box_content' ), $post_type, 'side', 'low');
	}
	
	/**
	 * Create the box content
	 */
	public function create_box_content()
	{
		global $post;
		
		// Check permissions		
		if ( !current_user_can( 'edit_posts' ) ) 
		{
			?>
			<p><?php _e( 'You do not have sufficient permissions to access this content.', 'related-links' ); ?>.</p>
			<?php
			return;
		}
		
		// only output when there is a post
		if( empty( $post ) ) 
		{
			?>
			<p><?php _e( 'This post type doesn\'t support related links.', 'related-links' ); ?>.</p>
			<?php
			return;
		}

		// stop the output when no type is enabled		
		if ( empty( $this->settings['types'] ) )
		{
			?>
			<p><?php _e( 'There is no post type enabled in the settings.', 'related-links' ); ?>.</p>
			<?php
			return;
		}
		
		// Get the meta information	
		$meta = get_post_meta($post->ID, '_related_links', true);
		
		
		// Use nonce for verification
  		wp_nonce_field(plugin_basename( __FILE__ ), 'related_links_nonce');
		
		// legacy: parse old meta data structure
		if($meta && count($meta) > 0 && empty($meta['posts']) && empty($meta['custom']))
		{
			$parsed = array();
			$parsed['posts'] = $meta;
			$meta = $parsed;
		}

		// start the output
		?>
		<div id="related-links-inside">
			<div id="related-links-selected">
				<ul>
		<?php

		// add the selected posts
		if(!empty($meta['posts']))
		{
			foreach($meta['posts'] as $id) 
			{
				$is_custom = strrpos($id, 'custom_');

				if($is_custom !== false)
				{
					$custom_meta = $meta['custom'][$id];
					?>
					<li class="related-links-selected menu-item-handle" id="related-links-selected-<?php echo $id; ?>"><input type="hidden" name="related_links[posts][]" value="<?php echo $id; ?>" /><input type="hidden" name="related_links[custom][<?php echo $id; ?>][]" value="<?php echo $custom_meta[0]; ?>" /><input type="hidden" name="related_links[custom][<?php echo $id; ?>][]" value="<?php echo $custom_meta[1]; ?>" /><span class="selected-title"><?php echo $custom_meta[0]; ?></span><span class="selected-right"><span class="selected-type"><?php _e('Custom', 'related-links'); ?></span><a href="#" class="selected-delete"><?php _e('Delete', 'related-links'); ?></a></span></li>
					<?php
				}
				else
				{
					$meta_post = get_post($id);
					
					if(!empty($meta_post) && $meta_post->post_status != 'trash') 
					{
						$meta_post_object = get_post_type_object($meta_post->post_type);
						?>
					<li class="related-links-selected menu-item-handle" id="related-links-selected-<?php echo $meta_post->ID; ?>"><input type="hidden" name="related_links[posts][]" value="<?php echo $meta_post->ID; ?>" /><span class="selected-title"><?php echo $meta_post->post_title; ?></span><span class="selected-right"><span class="selected-type"><?php echo $meta_post_object->labels->singular_name; ?></span><a href="#" class="selected-delete"><?php _e('Delete', 'related-links'); ?></a></span></li>
						<?php
					}
				}
			}
		}
		?>
				</ul>
			</div>
		<?php
		
		// start the links list output
		if (count($this->settings['types']) > 0 )
		{						
			?>
			<div id="related-links-search">
				<input type="text" id="related-links-searchfield" class="regular-text search-textbox" autocomplete="off" placeholder="<?php _e('Search', 'related-links'); ?>" />
			</div>
			<div id="related-links-content">
				<ul id="related-links-list" class="related-links-list form-no-clear">
					<?php // create the links (loaded with ajax) ?>
					<li class="status"><span class="loading"><img src="<?php echo admin_url( 'images/wpspin_light.gif' ); ?>" alt="" /> <?php _e('Loading…', 'related-links'); ?></span><span class="complete"><?php _e('Complete', 'related-links'); ?></span></li>
				</ul>
			</div>
			<?php
		}
		
		// add a custom link	
		?>
			<div id="related-links-custom">
				<a href="#" id="related-links-custom-addurl"><?php _e('Add Link', 'related-links'); ?></a>
				<div id="related-links-custom-content">
					<p class="button-controls"><label class="howto"><span><?php _e('Label', 'related-links'); ?>:</span><input id="related-links-custom-label" type="text" class="regular-text" placeholder="<?php _e('Link name', 'related-links'); ?>"></label></p>
					<p class="button-controls"><label class="howto"><span><?php _e('URL', 'related-links'); ?>:</span><input id="related-links-custom-url" type="text" class="regular-text" placeholder="<?php _e('http://', 'related-links'); ?>"></label></p>
					<p class="button-controls"><input type="button" id="related-links-custom-submit" class="button category-add-sumbit" value="Add Link" tabindex="3"></p>
				</div>
			</div>
		</div>
		<?php
	}
	
	/**
	 * Load the content of the links list.
	 * These are all posts of the site.
	 */
	public function load_links_list( $post_id, $posts_per_page = 40, $posts_offset = 0, $posts_title = '' )
	{
		global $wpdb;		
		
		// Set offset
		if($posts_per_page > 0)
		{
			$limit = "LIMIT $posts_offset,$posts_per_page";
		}
		else
		{
			$limit = "";
		}
		
		// Set search
		if(!empty($posts_title))
		{
			$search = "AND p.post_title LIKE '%$posts_title%'";
		}
		else
		{
			$search = "";
		} 
		
		// Format the query and grab the links
		$sql_post_types = "'" . implode("', '", $this->settings['types']) . "'";

		$sql = "
			SELECT p.post_title, p.ID, p.post_type, p.post_mime_type
			FROM $wpdb->posts p
			WHERE p.post_status
			IN ('publish', 'future', 'inherit')
			AND	p.post_type 
			IN ($sql_post_types) 
			$search 
			ORDER BY p.post_type, p.post_title ASC 
			$limit";

		// start the output
		$query_posts = $wpdb->get_results( $sql );
		
		if( !empty( $query_posts ) )
		{
			// Get the meta information to mark the links
			$meta = get_post_meta($post_id, '_related_links', true);

			// add the items
			foreach( $query_posts as $query_post )
			{
				$query_post_object = get_post_type_object($query_post->post_type);
				?>
				<li id="related-links-<?php echo $query_post->ID; ?>">
					<a href="#<?php echo $query_post->ID; ?>" id="in-related-links-<?php echo $query_post->ID; ?>" class="in-related-links<?php if(!empty($meta['posts']) && in_array($query_post->ID, $meta['posts'])) { ?> selected<?php } ?>" title="<?php esc_attr( $query_post->post_title ); ?>"><?php echo $query_post->post_title; ?></a><span><?php echo $query_post_object->labels->singular_name; ?></span>
				</li>
				<?php
			}
		}
		
		// add the bookmarks type
		/*if ( !empty( $this->settings['types']['bookmark'] ) )
		{
			$args = array(
				'hide_invisible' => false
			);
			$bookmarks = get_bookmarks( $args );
			
			if( !empty( $bookmarks ) )
			{
				// add the items
				foreach( $bookmarks as $bookmark )
				{
				//print_r($bookmark);
					?>
					<li id="related-links-<?php echo $bookmark->link_id; ?>">
						<a href="#<?php echo $bookmark->link_id; ?>" id="in-related-links-<?php echo $bookmark->link_id; ?>" class="in-related-links<?php if(!empty($meta['bookmarks']) && in_array($bookmark->link_id, $meta['bookmarks'])) { ?> selected<?php } ?>" title="<?php esc_attr( $bookmark->link_name ); ?>"><?php echo $bookmark->link_name; ?></a><span><?php _e( 'Link', 'related-links' ); ?></span>
					</li>
					<?php
				}
			}
		}*/
	}
	
	/**
	 * Ajax callback for the links list
	 */
	public function load_links_list_callback()
	{		
		// Check nonce and data
		if ( empty( $_POST ) || empty( $_POST['post_id'] ) || empty( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], plugin_basename( __FILE__ ) ) ) 
		{
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		
		// Check permissions		
		if ( !current_user_can( 'edit_posts' ) ) 
		{
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
	
		$this->load_links_list( $_POST['post_id'], $_POST['posts_per_page'], $_POST['posts_offset'], $_POST['search'] );

		exit;
	}
	
	/**
	 * Save the box content
	 */
	public function save_box_data( $post_id )
	{
		// verify this came from the our screen and with 
		// proper authorization, because save_post can be 
		// triggered at other times
		if ( empty($_POST['related_links_nonce']) || !wp_verify_nonce( $_POST['related_links_nonce'], plugin_basename( __FILE__ ) )) 
		{
			return $post_id;
		}
  
  		// verify if this is an auto save routine. If it is 
  		// our form has not been submitted, so we dont want 
  		// to do anything
  		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
  		{
    		return $post_id;
    	}
  		
  		// Check permissions
		if ( $_POST['post_type'] ==  'page' ) {
			if ( !current_user_can( 'edit_page', $post_id ) )
			{
				return $post_id;
			}
		} 
		else 
		{
			if ( !current_user_can( 'edit_post', $post_id ) )
			{
				return $post_id;
			}
		}
		
		// OK, we're authenticated: Now we need to find and 
		// save the data.
		
		// save, update or delete the custom field of the post
		if(empty($_POST['related_links']))
		{
			delete_post_meta( $post_id, '_related_links' );
		}
		else
		{
			add_post_meta( $post_id, '_related_links', $_POST['related_links'], true ) or update_post_meta( $post_id, '_related_links', $_POST['related_links'] );
  		}
  	}

	/**
	 * Truncate the text when a defined 
	 * character length is overpassed
	 */
	public function truncate( $str, $length )
	{
		if ( strlen( $str ) > $length ) 
		{
			$str = substr($str, 0, $length);
			$str .= ' ...';
			return $str;
		} 
		else 
		{
			return $str;
		}
	}
	

}
}
?>