<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if( !class_exists('WP_Post_Disclaimer_Admin') ) :
/**
 * Post Disclaimer Admin Class
 *
 * Handles all major admin functionalities
 *
 * @since WP Post Disclaimer 1.0.0
 **/
class WP_Post_Disclaimer_Admin{
	
	private $options;
	
	//Class Constructor
	public function __construct(){
		
		if( is_admin() ) : //Check Is Admin
			//Register Plugin Settings
			add_action('admin_init', 			array($this, 'register_settings'));
			//Add Admin Menu
			add_action('admin_menu', 			array($this, 'register_settings_page'));
			//Admin Scripts/Styles
			add_action('admin_enqueue_scripts',	array($this, 'register_scripts_styles'));
			//Add Metaboxe
			add_action('add_meta_boxes', 		array($this, 'add_meta_boxes'));
			//Add Footer Text
			add_filter('admin_footer_text',		array($this, 'admin_footer_text'));
			//Save Metaboxes
			add_action('save_post', 			array($this, 'save_meta_boxes'), 10, 2);
			//Add Settings Link
			add_action('plugin_action_links_' . WPPD_PLUGIN_BASE, 	array($this, 'add_plugin_action_links'), 10, 2);
		endif; //Endif
	}
	
	/**
	 * Admin Enqueue Scripts
	 **/
	public function register_scripts_styles( $hook ){
		
		global $wppd_options;
		
		if( $hook != 'settings_page_wppd-disclaimer-settings' && $hook != 'post.php' && $hook != 'post-new.php' )
			return;
		
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		
		//Font Awesome Icon Kit
		if( empty( $wppd_options['disable_fa'] ) ) : //Check Font Awesome Disabled or Not
			wp_register_style('fontawesome', WPPD_PLUGIN_URL . 'assets/css/fontawesome/all'.$suffix.'.css', array(), WPPD_PLUGIN_VERSION);
			wp_enqueue_style('fontawesome');
		endif; //Endif		
	}
	
	/**
	 * Adding Plugin Action Links
	 **/
	public function add_plugin_action_links( $links ){
		$links = array_merge( array('<a href="' . esc_url( add_query_arg( 'page', 'wppd-disclaimer-settings', admin_url( '/options-general.php' ) ) ) . '">' . esc_html__('Settings', 'wp-post-disclaimer') . '</a>' ), $links );
		return $links;		
	}
	
	/**
	 * Footer Text for Rating
	 **/
	public function admin_footer_text( $footer_text ){
		$current_screen = get_current_screen();
		if( isset( $current_screen->id ) && $current_screen->id == 'settings_page_wppd-disclaimer-settings' ) : //Check Setting Page
			$footer_text = sprintf( esc_html__('Proudly %1$s If you like %2$s please leave us a %3$s rating. Thanks a bunch in advance!', 'wp-post-disclaimer'),
									sprintf('<strong>%s</strong>', esc_html__('Made In India', 'wp-post-disclaimer')),
									sprintf('<strong>%s</strong>', esc_html__('WP Post Disclaimer', 'wp-post-disclaimer')),
									'<a href="https://wordpress.org/support/plugin/wp-post-disclaimer/reviews?rate=5#new-post" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a>');
		endif; //Endif
		return $footer_text;		
	}
	
	/**
	 * Register Plugin Settings
	 **/
	public function register_settings(){
		//Register Plugin Settings
		register_setting( 'wppd-plugin-settings', 'wppd_options', array( $this, 'sanitize' ) );
	}
	
	/**
	 * Register Plugin Page
	 **/
	public function register_settings_page(){
		// This page will be under "Settings"
        add_options_page(
            esc_html__( 'WP Post Disclaimer Settings', 'wp-post-disclaimer' ), 
            esc_html__( 'WP Post Disclaimer', 'wp-post-disclaimer' ), 
            'manage_options',
            'wppd-disclaimer-settings', 
            array( $this, 'create_settings_page' )
        );
	}
	
	/**
     * Options page callback
     **/
    public function create_settings_page(){
		global $wppd_options;
        //Set Class Property
        $this->options = $wppd_options;
		//Load Options Functions
		require_once( WPPD_PLUGIN_PATH . 'includes/options/general.php' );		
    }
	
	/**
     * Sanitize each setting field as needed
     **/
    public function sanitize( $input ) {        
		$input['enable'] 				= isset( $input['enable'] ) 					?	sanitize_text_field( $input['enable'] ) 					: 0; 			//Enable
		$input['display_in_post'] 		= isset( $input['display_in_post'] ) 			?	sanitize_text_field( $input['display_in_post'] ) 			: '';			//Enable for Posts
		$input['display_in_page'] 		= isset( $input['display_in_page'] ) 			?	sanitize_text_field( $input['display_in_page'] ) 			: '';			//Enable for Pages
		$input['display_in_post_position'] = isset( $input['display_in_post_position'] )?	sanitize_text_field( $input['display_in_post_position'] ) 	: 'bottom'; 	//Post Position
		$input['display_in_page_position'] = isset( $input['display_in_page_position'] )?	sanitize_text_field( $input['display_in_page_position'] ) 	: 'bottom'; 	//Page Position
		$input['disclaimer_title'] 		= isset( $input['disclaimer_title'] ) 			?	sanitize_text_field( $input['disclaimer_title'] )			: ''; 			//Default Dislaimer Title
		$input['disclaimer_content'] 	= isset( $input['disclaimer_content'] )			?	wppd_sanitize_editor_field( $input['disclaimer_content'] ) 	: ''; 			//Default Dislaimer Content
		$input['disable_fa']			= isset( $input['disable_fa'] ) 				?	sanitize_text_field( $input['disable_fa']	) 				: 0;  			//Disable Font Awesome
		$input['title_tag']				= isset( $input['title_tag'] )					?	sanitize_text_field( $input['title_tag'] )					: 'h6'; 		//Title Tag
		$input['style']					= isset( $input['style'] ) 						?	sanitize_html_class( $input['style'] )						: 'default'; 	//Style
		$input['icon']					= isset( $input['icon'] ) 						?	sanitize_text_field( $input['icon'] ) 						: ''; 			//Icon
		$input['icon_size']				= isset( $input['icon_size'] ) 					?	sanitize_text_field( $input['icon_size'] )					: 'sm'; 		//Icon Size
		$input['custom_css']			= isset( $input['custom_css'] ) 				?	wp_strip_all_tags( $input['custom_css'] )					: ''; 			//Custom CSS
		
		if( isset( $custom_css ) && !empty( $custom_css ) ) : //Check Custom CSS Set
			$this->generate_custom_css( wp_strip_all_tags( $custom_css ) );
		endif; //Endif
		return $input;
    }
	
	/**
	 * Generate Custom CSS
	 **/
	public function generate_custom_css( $custom_css ){
		
		$uploads_path = wp_upload_dir();
		
		/** Save on different directory if on multisite **/
		if( is_multisite() ) :
			$css_base_dir = trailingslashit( $uploads_path['basedir'] );
		else :
			$css_base_dir = WPPD_PLUGIN_PATH . 'assets/css/';
		endif;
		
		//Get Custom CSS
		ob_start();
		echo $custom_css;
		$css = ob_get_clean();
		
		//Generate Custom CSS File
		WP_Filesystem();
		global $wp_filesystem;
		if ( ! $wp_filesystem->put_contents( $css_base_dir . 'custom.css', $css, 0644) ) :
		    return true;
		endif; //Endif
	}
	
	/**
	 * Add Metaboxes
	 **/
	public function add_meta_boxes($post_type){
		
		global $wppd_options;
		
		if( !isset( $wppd_options['display_in_'.$post_type] ) || empty( $wppd_options['display_in_'.$post_type] ) ) : //Check Enable from Options
			return false;
		endif; //Endif
		
		$type_object = get_post_type_object( $post_type );
		//Dislaimer Metabox
		add_meta_box(
			'wppd-disclaimer',
			sprintf( '<span class="dashicons dashicons-warning"></span> %1$s', esc_html__('WP Post Disclaimer', 'wp-post-disclaimer') ),
			array( $this, 'render_disclaimer_meta_box'),
			$post_type,
			'normal',
			'default'
		);
	}
	
	/**
	 * Disclaimer Meta Box Callback
	 **/
	public function render_disclaimer_meta_box($post){
		global $wppd_options;
		$wppd_options_url = esc_url( add_query_arg( 'page', 'wppd-disclaimer-settings', admin_url( '/options-general.php' ) ) );
		$wppd_disable 			= get_post_meta($post->ID, '_wppd_post_disclaimer_disable', true) 	? get_post_meta($post->ID, '_wppd_post_disclaimer_disable', true) 	: 0;
		$wppd_position 			= get_post_meta($post->ID, '_wppd_post_disclaimer_position',true) 	? get_post_meta($post->ID, '_wppd_post_disclaimer_position',true) 	: '';
		$wppd_disclaimer_title 	= get_post_meta($post->ID, '_wppd_post_disclaimer_title', 	true) 	? get_post_meta($post->ID, '_wppd_post_disclaimer_title', 	true) 	: '';
		$wppd_disclaimer_content= get_post_meta($post->ID, '_wppd_post_disclaimer_content', true) 	? get_post_meta($post->ID, '_wppd_post_disclaimer_content', true) 	: '';
		$wppd_style				= get_post_meta($post->ID, '_wppd_post_disclaimer_style', 	true) 	? get_post_meta($post->ID, '_wppd_post_disclaimer_style', 	true) 	: '';
		$wppd_title_tag 		= get_post_meta($post->ID, '_wppd_post_disclaimer_title_tag',true)	? get_post_meta($post->ID, '_wppd_post_disclaimer_title_tag',true) 	: '';
		$wppd_icon				= get_post_meta($post->ID, '_wppd_post_disclaimer_icon',	true) 	? get_post_meta($post->ID, '_wppd_post_disclaimer_icon', 	true) 	: '';
		$wppd_icon_size			= get_post_meta($post->ID, '_wppd_post_disclaimer_icon_size',true)	? get_post_meta($post->ID, '_wppd_post_disclaimer_icon_size',true) 	: '';
				
		//Nonce Field for Secure
		wp_nonce_field('wppd_meta_disclaimer', 'wppd_meta_disclaimer_nonce'); ?>
		
		<table class="form-table">
			<tr>
				<th><?php esc_html_e('Disable', 'wp-post-disclaimer');?></th>
				<td><input type="checkbox" name="_wppd_post_disclaimer_disable" id="_wppd_post_disclaimer_disable" value="1" <?php checked(1, $wppd_disable);?>/>
					<p class="description"><?php printf( '%1$s <a href="%2$s" target="_blank">%3$s</a>.', esc_html__('Check to disable for this post, if uncheck then it will consider from the','wp-post-disclaimer'), $wppd_options_url, esc_html__('options page', 'wp-post-disclaimer') );?></p>
				</td>
			</tr>
			<?php //Do Action to Extend
				do_action('wppd_disclaimer_admin_meta_box_before', $post);
			?>
			<tr>
				<th><?php esc_html_e('Position', 'wp-post-disclaimer');?></th>
				<td><select class="regular-text" name="_wppd_post_disclaimer_position" id="_wppd_post_disclaimer_position">
						<option value="0"><?php esc_html_e('Default', 'wp-post-disclaimer');?></option>
						<option value="top" <?php selected($wppd_position, 'top');?>><?php esc_html_e('Top', 'wp-post-disclaimer');?></option>
						<option value="bottom" <?php selected($wppd_position, 'bottom');?>><?php esc_html_e('Bottom', 'wp-post-disclaimer');?></option>
						<option value="top_bottom" <?php selected($wppd_position, 'top_bottom');?>><?php esc_html_e('Top & Bottom', 'wp-post-disclaimer');?></option>
						<option value="shortcode" <?php selected($wppd_position, 'shortcode');?>><?php esc_html_e('Shortcode', 'wp-post-disclaimer');?></option>
					</select>
					<p class="description"><?php printf( '%1$s <a href="%2$s" target="_blank">%3$s</a>.', esc_html__('Set position for this post, if set to default then it will consider from the','wp-post-disclaimer'), $wppd_options_url, esc_html__('options page', 'wp-post-disclaimer') );?></p>
				</td>
			</tr>
            <tr>
                <th><?php esc_html_e('Shortcode','wp-post-disclaimer');?></th>
                <td><code><?php echo esc_attr('[wppd_disclaimer title="Your disclaimer title" title_tag="h1|h2|h3|h4|h5|h6|span" style="red|yellow|blue|green|grey|black|white" icon="Any Free Font Awesome Icon Class i.e fas fa-address-book OR fab fa-accusoft" icon_size="xs|sm|lg|2x|3x|5x|7x|10x"]Your disclaimer content here[/wppd_disclaimer]');?></code>
                    <p class="description">
                        <?php printf( '%1$s <a href="%2$s" target="_blank">%3$s</a>.', esc_html__('If you leave empty shortcode attributes it will consider individual post settings then after it will consider from the','wp-post-disclaimer'), $wppd_options_url, esc_html__('options page', 'wp-post-disclaimer') );?></p>
                        <?php printf( '%1$s <a href="https://fontawesome.com/icons?d=gallery&m=free" target="_blank">%2$s</a>.', esc_html__('You may check list for','wp-post-disclaimer'), esc_html__('Font Awesome Icons', 'wp-post-disclaimer') );?>
                    </p>
                </td>
            </tr>
			<tr>
				<th><?php esc_html_e('Title', 'wp-post-disclaimer');?></th>
				<td><input type="text" class="regular-text" id="_wppd_post_disclaimer_title" name="_wppd_post_disclaimer_title" value="<?php esc_html_e( $wppd_disclaimer_title );?>" placeholder="<?php echo empty( $wppd_disclaimer_title ) ? esc_html( $wppd_options['disclaimer_title'] ) : esc_html( $wppd_disclaimer_title );?>"/>
					<p class="description"><?php printf( '%1$s <a href="%2$s" target="_blank">%3$s</a>.', esc_html__('Set title for this post, if you leave empty, then it will consider from the','wp-post-disclaimer'), $wppd_options_url, esc_html__('options page', 'wp-post-disclaimer') );?></p>
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e('Content', 'wp-post-disclaimer');?></th>
				<td><?php wp_editor( $wppd_disclaimer_content, '_wppd_post_disclaimer_content', array('media_buttons' => false, 'textarea_name' => '_wppd_post_disclaimer_content', 'editor_height' => '250px') ); ?>
					<p class="description">
						<?php printf( '%1$s <a href="%2$s" target="_blank">%3$s</a>.', esc_html__('Set disclaimer content, if you leave empty, then it will consider from the','wp-post-disclaimer'), $wppd_options_url, esc_html__('options page', 'wp-post-disclaimer') );?><br/>
						<code>%%title%%</code> - <?php esc_html_e('will display title of post/page', 'wp-post-disclaimer');?><br/>
						<code>%%excerpt%%</code> - <?php esc_html_e('will display excerpt of post/page', 'wp-post-disclaimer');?>
					</p>
				</td>
			</tr>
            <tr><th colspan="2"><?php esc_html_e('Appearance Settings', 'wp-post-disclaimer');?></th></tr>
            <tr>
                <th><?php esc_html_e('Title Tag', 'wp-post-disclaimer');?></th>
                <td><select name="_wppd_post_disclaimer_title_tag" id="_wppd_post_disclaimer_title_tag" class="regular-text">
                		<option value="0"><?php esc_html_e('Default', 'wp-post-disclaimer');?></option>
                        <?php foreach( wppd_title_tag_options() as $tkey => $tag ) : //Loop to List Styles ?>
                            <option value="<?php echo $tkey;?>" <?php selected($tkey, $wppd_title_tag );?>><?php echo $tag;?></option>
                        <?php endforeach; //Endforeach ?>
                    </select>
                    <p class="description"><?php printf( '%1$s <a href="%2$s" target="_blank">%3$s</a>.', esc_html__('Set disclaimer title HTML tag, the default will consider from the', 'wp-post-disclaimer'), $wppd_options_url, esc_html__('options page', 'wp-post-disclaimer') );?></p>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e('Style', 'wp-post-disclaimer');?></th>
                <td><select name="_wppd_post_disclaimer_style" id="_wppd_post_disclaimer_style" class="regular-text">
                    	<option value="0"><?php esc_html_e('Default', 'wp-post-disclaimer');?></option>
						<?php foreach( wppd_style_options() as $skey => $style ) : //Loop to List Styles ?>
                            <option value="<?php echo $skey;?>" <?php selected($skey, $wppd_style);?>><?php echo $style;?></option>
                        <?php endforeach; //Endforeach ?>
                    </select>
                    <p class="description"><?php printf( '%1$s <a href="%2$s" target="_blank">%3$s</a>.', esc_html__('Set style for this post, the default will consider from the','wp-post-disclaimer'), $wppd_options_url, esc_html__('options page', 'wp-post-disclaimer') );?></p>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e('Icon','wp-post-disclaimer');?></th>
                <td><select name="_wppd_post_disclaimer_icon" id="_wppd_post_disclaimer_icon" class="regular-text">
                		<option value="0"><?php esc_html_e('Default', 'wp-post-disclaimer');?></option>
						<?php foreach( wppd_fontawesome_icons_options() as $ikey => $icon ) : //Loop to List Icons ?>
                            <option value="<?php echo $ikey;?>" <?php selected($ikey, $wppd_icon);?>><?php echo $icon;?></option>
                        <?php endforeach; //Endforeach ?>
                    </select>
                    <?php echo ( isset( $wppd_icon ) && !empty( $wppd_icon ) ) ? '<i class="'.$wppd_icon.' fa-lg" style="margin-left:5px;"></i>' : '';?>
                    <p class="description"><?php printf( '%1$s <a href="%2$s" target="_blank">%3$s</a>.', esc_html__('Set icon for this post, the default will consider from the','wp-post-disclaimer'), $wppd_options_url, esc_html__('options page', 'wp-post-disclaimer') );?></p>
                </td>
            </tr>
            <tr>
                <th><?php esc_html_e('Icon Size','wp-post-disclaimer');?></th>
                <td><select name="_wppd_post_disclaimer_icon_size" id="_wppd_post_disclaimer_icon_size" class="regular-text">
                		<option value="0"><?php esc_html_e('Default', 'wp-post-disclaimer');?></option>
                        <?php foreach( wppd_fontawesome_icons_sizes_options() as $iskey => $size ) : //Loop to List Styles ?>
                            <option value="<?php echo $iskey;?>" <?php selected($iskey, $wppd_icon_size);?>><?php echo $size;?></option>
                        <?php endforeach; //Endforeach ?>                            		
                    </select>
                    <p class="description"><?php printf( '%1$s <a href="%2$s" target="_blank">%3$s</a>.', esc_html__('Set the icon size for this post, the default will consider from the','wp-post-disclaimer'), $wppd_options_url, esc_html__('options page', 'wp-post-disclaimer') );?></p>
                </td>
            </tr>
			<?php //Do Action to Extend
				do_action('wppd_disclaimer_admin_meta_box_after', $post);
			?>
		</table>
	<?php }

	/**
	 * Disclaimer Meta Box Callback
	 **/
	public function save_meta_boxes( $post_id, $post ){
		
		//Don't save meta for revisions or autosave posts
		if( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) :
			return;
		endif; //Endif
		
		//Check security
		if ( empty( $_POST['wppd_meta_disclaimer_nonce'] ) || ! wp_verify_nonce( $_POST['wppd_meta_disclaimer_nonce'], 'wppd_meta_disclaimer' ) ) :
			return;
		endif; //Endif
		
		//Check Post ID or Current User Permission
		if( empty( $_POST['post_ID'] ) || $_POST['post_ID'] != $post_id || ! current_user_can( 'edit_post', $post_id ) ) :
			return;
		endif; //Endif
		
		//Enable Disclaimer
		if( isset( $_POST['_wppd_post_disclaimer_disable'] ) && !empty( $_POST['_wppd_post_disclaimer_disable'] ) ) :
			update_post_meta($post_id, '_wppd_post_disclaimer_disable', 1);
		else : //Else
			update_post_meta($post_id, '_wppd_post_disclaimer_disable', 0);
		endif; //Endif
		
		//Check Disclaimer Position
		if( !empty( $_POST['_wppd_post_disclaimer_position'] ) && !empty( $_POST['_wppd_post_disclaimer_position'] ) ) :
			update_post_meta($post_id, '_wppd_post_disclaimer_position', sanitize_text_field( $_POST['_wppd_post_disclaimer_position'] ));
		else : //Else
			update_post_meta($post_id, '_wppd_post_disclaimer_position', '');
		endif; //Endif
		
		//Check Disclaimer Title
		if( isset( $_POST['_wppd_post_disclaimer_title'] ) && !empty( $_POST['_wppd_post_disclaimer_title'] ) ) :
			update_post_meta($post_id, '_wppd_post_disclaimer_title', sanitize_text_field($_POST['_wppd_post_disclaimer_title']) );
		else :
			update_post_meta($post_id, '_wppd_post_disclaimer_title', '');
		endif; //Endif
		
		//Check Disclaimer Content
		if( isset( $_POST['_wppd_post_disclaimer_content'] ) ) :
			update_post_meta($post_id, '_wppd_post_disclaimer_content', wppd_sanitize_editor_field( $_POST['_wppd_post_disclaimer_content'] ) ) ;		
		else :
			update_post_meta($post_id, '_wppd_post_disclaimer_content', '');
		endif; //Endif
		
		//Check Title Tag
		if( isset( $_POST['_wppd_post_disclaimer_title_tag'] ) && !empty( $_POST['_wppd_post_disclaimer_title_tag'] ) ) :
			update_post_meta($post_id, '_wppd_post_disclaimer_title_tag', sanitize_text_field( $_POST['_wppd_post_disclaimer_title_tag'] ));
		else : //Else
			update_post_meta($post_id, '_wppd_post_disclaimer_title_tag', '');
		endif; //Endif
		
		//Check Disclaimer Style
		if( isset( $_POST['_wppd_post_disclaimer_style'] ) && !empty( $_POST['_wppd_post_disclaimer_style'] ) ) :
			update_post_meta($post_id, '_wppd_post_disclaimer_style', sanitize_html_class( $_POST['_wppd_post_disclaimer_style'] ) );
		else : //Else
			update_post_meta($post_id, '_wppd_post_disclaimer_style', '');
		endif; //Endif
		
		//Check Disclaimer Icon
		if( isset( $_POST['_wppd_post_disclaimer_icon'] ) && !empty( $_POST['_wppd_post_disclaimer_icon'] ) ) :
			update_post_meta($post_id, '_wppd_post_disclaimer_icon', sanitize_text_field( $_POST['_wppd_post_disclaimer_icon'] ) );
		else : //Else
			update_post_meta($post_id, '_wppd_post_disclaimer_icon', '');
		endif; //Endif		
		
		//Check Disclaimer Icon Size
		if( isset( $_POST['_wppd_post_disclaimer_icon_size'] ) && !empty( $_POST['_wppd_post_disclaimer_icon_size'] ) ) :
			update_post_meta($post_id, '_wppd_post_disclaimer_icon_size', sanitize_html_class( $_POST['_wppd_post_disclaimer_icon_size'] ) );
		else : //Else
			update_post_meta($post_id, '_wppd_post_disclaimer_icon_size', '');
		endif; //Endif
		
		//Hook to Save Post
		do_action('wppd_save_'.$post->post_type.'_meta', $post_id, $post);
	}	
}
//Run Class and Crate Object
$wppd_admin = new WP_Post_Disclaimer_Admin();
endif;