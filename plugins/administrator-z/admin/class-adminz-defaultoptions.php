<?php 
namespace Adminz\Admin;
use Adminz\Helper\ADMINZ_Helper_Taxonomy_Thumbnail;
use Adminz\Helper\ADMINZ_Helper_Category;

class ADMINZ_DefaultOptions extends Adminz {
	public $options_group = "adminz_defaultoption";
	public $title = "Default Options";
	static $slug = 'adminz_default-setting';
	static $options;	
	function __construct() {
		
		$this::$options = get_option('adminz_default', []);
		add_filter( 'adminz_setting_tab', [$this,'register_tab']);
		add_action( 'adminz_tabs_html',[$this,'tab_html']);

		add_action( 'admin_init', [$this,'register_option_setting'] );
		add_action( 'init', [$this, 'init']);
		add_action( 'admin_init', [$this,'remove_pages'],999);
		add_action( 'admin_notices', [$this,'general_admin_notice']);

		if(is_admin()){		
			$adminz_menu_title = $this->get_option_value('adminz_menu_title');
			if($adminz_menu_title){
				add_filter('adminz_menu_title', function () use ($adminz_menu_title){return $adminz_menu_title; } );
				add_filter('login_headertext', function () use ($adminz_menu_title){return $adminz_menu_title; } );
				add_filter('adminz_slug', function () use ($adminz_menu_title){return sanitize_title($adminz_menu_title); } );
			}
			$adminz_logo_url = $this->get_option_value('adminz_logo_url');
			if($adminz_logo_url){
				add_filter('login_headerurl', function (){return $adminz_logo_url; } );
			}

		}
		$adminz_login_logo = $this->get_option_value('adminz_login_logo');
		if($adminz_login_logo){
			add_action(
				'login_head', 
				function () use ($adminz_login_logo){
					$image = wp_get_attachment_image_src( $adminz_login_logo,'full' );
					if(isset($image[0])){
						echo '<style type="text/css"> h1 a {background-image: url('.esc_attr($image[0]).') !important; background-size: contain !important;    width: 100%!important;}
						</style>';
					}					
				}
			);
		}

		if(is_admin()){
			$this->wp_configs();
		}		
		if($this->get_option_value('adminz_tax_thumb',false,[])){
			new ADMINZ_Helper_Taxonomy_Thumbnail($this->get_option_value('adminz_tax_thumb',false,[]));
		}		
	}
	function register_tab($tabs) {
		if(!$this->title) return;
 		$this->title = $this->get_icon_html('home').$this->title;
        $tabs[self::$slug] = array(
            'title' => $this->title,
            'slug' => self::$slug,
        );
        return $tabs;
    }
	function general_admin_notice(){		
		$notice = $this->get_option_value('adminz_notice');
		if(!$notice) return;
		echo '<div class="notice is-dismissible">';
		echo '<p>';
		echo '<strong>'.esc_attr($this->get_adminz_menu_title()).' notice:</strong> '. esc_attr($this->get_option_value('adminz_notice'));
		echo ' <a href="'.get_admin_url( '', '/tools.php?page=adminz#notices', '' ).'">Edit</a>';
		echo '</p>';
        echo '</div>';         
	}
	function wp_configs(){
		if($this->check_option('adminz_use_classic_editor',false,"on")){		
			add_filter('use_block_editor_for_post', '__return_false');
			// Disables the block editor from managing widgets in the Gutenberg plugin.
			add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
			// Disables the block editor from managing widgets.
			add_filter( 'use_widgets_block_editor', '__return_false' );
		}
		if($this->check_option('auto_image_excerpt',false,"on")){
			if (is_admin()) {
				add_action( 'add_attachment', function( $post_ID ) {
				if ( wp_attachment_is_image( $post_ID ) ) {
					$my_image_title = get_post( $post_ID )->post_title;
					$my_image_meta = array(
						'ID'		=> $post_ID,			// Specify the image (ID) to be updated
						'post_title'	=> $my_image_title,		// Set image Title to sanitized title
						'post_excerpt'	=> $my_image_title,		// Set image Caption (Excerpt) to sanitized title
						'post_content'	=> $my_image_title,		// Set image Description (Content) to sanitized title
					);
					update_post_meta( $post_ID, '_wp_attachment_image_alt', $my_image_title );
					wp_update_post( $my_image_meta );
				} 
				});
			}
		}
	}
	function remove_pages(){
		global $user_ID;
		$user_excluded= $this->get_option_value('adminz_user_excluded',false,[]);
		if(!$user_excluded) $user_excluded = array();
		
		if(in_array($user_ID, $user_excluded )) return;

		$hide = $this->get_option_value('adminz_hide_admin_menu',false,[]);
		if(!empty($hide)){
			foreach ($hide as $page) {
				remove_menu_page($page);
			}
		}
	}
	function init(){
			
 	}
	function tab_html(){
		if(
			(isset($_GET['tab']) and $_GET['tab'] == self::$slug) or 
			(!isset($_GET['tab']))
		){
		?>
		<form method="post" action="options.php">
			<?php 
			settings_fields($this->options_group);
	        do_settings_sections($this->options_group);
			?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<h3>Admin settings</h3>
					</th>
				</tr>
	            <tr valign="top">
	                <th scope="row">Plugin name</th>
	                <td>
 						<input type="text" name="adminz_default[adminz_menu_title]" value="<?php echo esc_attr($this->get_option_value('adminz_menu_title')); ?>" />
	                </td>
	            </tr>
	            <tr valign="top">
	                <th scope="row">Logo login image</th>
	                <td>
 						<?php 
 						$image_id = $this->get_option_value('adminz_login_logo'); 						
 						if( $image = wp_get_attachment_image_src( $image_id ) ) {
 
							echo '<a href="#" class="button adminz-upl"><img src="' . esc_attr($image[0]) . '" /></a>
							      <a href="#" class="button adminz-rmv">Remove image</a>
							      <input type="hidden" name="adminz_default[adminz_login_logo]" value="' . esc_attr($image_id) . '">';
						 
						} else {
						 
							echo '<a href="#" class="button adminz-upl">Upload image</a>
							      <a href="#" class="button adminz-rmv" style="display:none">Remove image</a>
							      <input type="hidden" name="adminz_default[adminz_login_logo]" value="">';
						 
						}
 						 ?>
	                </td>
	            </tr>
	            <tr valign="top">
	            	<th scope="row">Logo link</th>
	            	<td>
 						<input type="text" name="adminz_default[adminz_logo_url]" value="<?php echo esc_attr($this->get_option_value('adminz_logo_url')); ?>" />
	                </td>
	            </tr>
				<tr valign="top">
					<th scope="row"><h3>Hide Admin menu</h3></th>
					<td>
					</td>
				</tr>				
				<tr valign="top">
					<th scope="row">Choose menu</th>
					<td>
						<?php 
							$adminz_hide_admin_menu = $this->get_option_value('adminz_hide_admin_menu',false,[]);
							if(!$adminz_hide_admin_menu){
								$adminz_hide_admin_menu = array();
							}							
							
							foreach ($GLOBALS[ 'menu' ] as $value) {
								if($value[0]){
								?>
								<label>
							   		<input type="checkbox" name ="adminz_default[adminz_hide_admin_menu][]" value="<?php echo esc_attr($value[2]); ?>" 
							   		<?php echo in_array( $value[2], $adminz_hide_admin_menu) ? 'checked' : ""; ?>
							   		/><?php echo apply_filters('the_title',($value[0]))."<code>".esc_attr($value[2])."</code></br>"; ?>
							   	</label>
								<?php
								}
							}
						?>					   	
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Choose user exclude</th>
					<td>						
					   	<?php
					   	$adminz_user_excluded = $this->get_option_value('adminz_user_excluded',false,[]);
 						foreach (get_users() as $user) {
					   		echo "<label>";
						   		echo "<input 
						   		type='checkbox' 
						   		name='adminz_default[adminz_user_excluded][]' 
						   		value='".esc_attr($user->data->ID)."'";
						   		echo in_array( $user->data->ID, $adminz_user_excluded) ? 'checked' : "";
						   		echo "/>";
						   		echo esc_attr($user->data->user_nicename);
						   		echo "<code>".esc_attr($user->roles[0])."</code>";
					   		echo " </label>";
					   	}
					   	 ?>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><h3>Wordpress config</h3></th>
					<td>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Notices						
					</th>
					<td>
						<textarea placeholder="Leave empty for remove notice" cols="200" rows="5" id="notices" type="textarea" name="adminz_default[adminz_notice]"><?php echo esc_attr($this->get_option_value('adminz_notice')); ?></textarea>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Wordpress
						<br><small><a target="_blank" href='https://adambrown.info/p/wp_hooks/hook'>Cheatsheet</a></small>
					</th>
					<td>
						<?php 
						$checked = "";		
						if($this->check_option('adminz_use_classic_editor',false,"on")){
							$checked = "checked";
						}
						?>
						<label><input type="checkbox" <?php echo esc_attr($checked); ?> name="adminz_default[adminz_use_classic_editor]">
						<em>Use classic editor and widget</em></label>						
					</td>
				</tr>

	        	<tr valign="top">
	        		<th>Auto image excerpt</th>
	        		<td>
	        			<div>	   
	        				<?php
	        				$checked = "";		
							if($this->check_option('auto_image_excerpt',false,"on")){
								$checked = "checked";
							}
	        				?>     				
	        				<label><input type="checkbox" <?php echo esc_attr($checked); ?>  name="adminz_default[auto_image_excerpt]"/>Enable to Auto fill image information like image title </label>
	        			</div>
	        		</td>
	    		</tr>
	    		<tr valign="top">
	        		<th>Taxonomy Thumbnail</th>
	        		<td>
	        			<?php 
	        			$tax_thumb = $this->get_option_value('adminz_tax_thumb',false,[]);
	        			foreach (get_taxonomies() as $key => $tax) {
	        				?>
	        				<label>
	        					<input type="checkbox" name="adminz_default[adminz_tax_thumb][]" value="<?php echo esc_attr($tax); ?>" <?php if(in_array($tax,$tax_thumb)) echo "checked"; ?> />
	        					<?php echo esc_attr($tax); ?>
	    					</label></br>
	        				<?php
	        			}
	        			?>
	        			<small>Meta key: thumbnail_id</small>
	        		</td>
	        	</tr>
				
				<tr valign="top">
					<th scope="row"><h3>Shortcode config</h3></th>
					<td>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Countview</th>
					<td>						
						<?php
						$checked = "";
						if($this->check_option('adminz_enable_countview',false,"on")){
							$checked = "checked";
						}
					 	?>
						<input type="checkbox" name="adminz_default[adminz_enable_countview]" <?php echo esc_attr($checked); ?>> Enable count view function
					</td>
				</tr>
	        </table>	        
			<?php submit_button(); ?>
		</form>
		<?php
		}
	}
	function register_option_setting(){
		register_setting( $this->options_group, 'adminz_default');		
	}
}
