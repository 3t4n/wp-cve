<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/webbuilder143/
 * @since      1.0.0
 *
 * @package    Wb_Custom_Product_Tabs_For_Woocommerce
 * @subpackage Wb_Custom_Product_Tabs_For_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wb_Custom_Product_Tabs_For_Woocommerce
 * @subpackage Wb_Custom_Product_Tabs_For_Woocommerce/admin
 * @author     Web Builder 143 <webbuilder143@gmail.com>
 */
class Wb_Custom_Product_Tabs_For_Woocommerce_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wb-custom-product-tabs-for-woocommerce-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wb-custom-product-tabs-for-woocommerce-admin.js', array( 'jquery' ), $this->version, false );
		wp_localize_script($this->plugin_name, 'wb_custom_tabs_params', array(
			'msgs'=>array(
				'untitled'=>__('Untitled', 'wb-custom-product-tabs-for-woocommerce'),
				'no_data'=>__('No data', 'wb-custom-product-tabs-for-woocommerce'),
				'sure'=>__('Are you sure?', 'wb-custom-product-tabs-for-woocommerce'),
				'title_mandatory'=>__('Please fill tab title', 'wb-custom-product-tabs-for-woocommerce'),
				'content_mandatory'=>__('Please fill tab content', 'wb-custom-product-tabs-for-woocommerce'),
				'invalid_video_id'=>__('Please enter valid YouTube video ID', 'wb-custom-product-tabs-for-woocommerce'),
				'inserting'=>__('Inserting...', 'wb-custom-product-tabs-for-woocommerce'),
				'insert'=>__('Insert', 'wb-custom-product-tabs-for-woocommerce'),
			)		
		));
	}


	/**
	 *	@since 1.0.0
	 *	Adding tabs to woocomerce product section
	 */
	public function product_data_tabs($tabs)
	{
		$tabs['wb_custom_tabs']= array(
			'label'    => __('Custom Tabs', 'wb-custom-product-tabs-for-woocommerce'),
			'target'   => 'wb_custom_tabs',
			'class'    => array(),
			'priority' =>110,
		);
		return $tabs;
	}

	/**
	 *	@since 1.0.0
	 *	Render product tab form in admin section
	 */
	public function product_data_panels()
	{
		global $post;
		$post_backup = $post;
		$product=wc_get_product($post);

		$tabs=Wb_Custom_Product_Tabs_For_Woocommerce::get_product_tabs($product);
		$post = $post_backup;
		include 'views/product_data_panels.php';
	}

	/**
	 *	Sanitize product tab data before saving
	 *	@since 1.0.0
	 *	@since 1.1.2 Nickname option added for product specific tabs
	 */
	private function sanitize_tab_input($arr)
	{
		$out=array();
		foreach($arr as $ar)
		{
			$title=sanitize_text_field(trim($ar['title']));
			$content=wp_kses_post(trim($ar['content']));
			$position=absint($ar['position']);
			$nickname=sanitize_text_field(trim($ar['nickname']));

			if($title!="") //skip empty tabs
			{
				$out[]=array(
					'title'=>$title,
					'content'=>$content,
					'tab_type'=>'local',
					'position'=>$position,
					'nickname'=>$nickname,
				);
			}			
		}
		return $out;
	}

	/**
	 *	@since 1.0.0
	 *	Save product tab data to database
	 */
	public function process_product_meta($post_id, $post)
	{
		if(empty($post_id))
		{
			return;
		}
		$product=wc_get_product($post_id);
		$product->update_meta_data('wb_custom_tabs', $this->sanitize_tab_input($_POST['wb_tab']));
        $product->save();
	}

	/**
	 *	@since 1.0.2
	 *	Register global tabs as custom post type
	 */
	public function register_global_tabs()
	{
		register_post_type(WB_TAB_POST_TYPE,
	        array(
	            'labels' => array(
	                'name' => __('Global product tabs', 'wb-custom-product-tabs-for-woocommerce'),
	                'singular_name' => __('Global product tab', 'wb-custom-product-tabs-for-woocommerce'),
	               	'all_items'             => __( 'Tabs', 'wb-custom-product-tabs-for-woocommerce'),
					'menu_name'             => _x( 'Tabs', 'Admin menu name', 'wb-custom-product-tabs-for-woocommerce'),
					'add_new'               => __( 'Add new', 'wb-custom-product-tabs-for-woocommerce'),
					'add_new_item'          => __( 'Add new tab', 'wb-custom-product-tabs-for-woocommerce'),
					'edit'                  => __( 'Edit', 'wb-custom-product-tabs-for-woocommerce'),
					'edit_item'             => __( 'Edit tab', 'wb-custom-product-tabs-for-woocommerce'),
					'new_item'              => __( 'New tab', 'wb-custom-product-tabs-for-woocommerce'),
					'view_item'             => __( 'View tab', 'wb-custom-product-tabs-for-woocommerce'),
					'view_items'            => __( 'View tabs', 'wb-custom-product-tabs-for-woocommerce'),
					'search_items'          => __( 'Search tabs', 'wb-custom-product-tabs-for-woocommerce'),
					'not_found'             => __( 'No tabs found', 'wb-custom-product-tabs-for-woocommerce'),
					'not_found_in_trash'    => __( 'No tabs found in trash', 'wb-custom-product-tabs-for-woocommerce'),
					'parent'                => __( 'Parent tab', 'wb-custom-product-tabs-for-woocommerce'),
					'featured_image'        => __( 'Tab image', 'wb-custom-product-tabs-for-woocommerce'),
					'set_featured_image'    => __( 'Set tab image', 'wb-custom-product-tabs-for-woocommerce'),
					'remove_featured_image' => __( 'Remove tab image', 'wb-custom-product-tabs-for-woocommerce'),
					'use_featured_image'    => __( 'Use as tab image', 'wb-custom-product-tabs-for-woocommerce'),
					'insert_into_item'      => __( 'Insert into tab', 'wb-custom-product-tabs-for-woocommerce'),
					'uploaded_to_this_item' => __( 'Uploaded to this tab', 'wb-custom-product-tabs-for-woocommerce'),
					'filter_items_list'     => __( 'Filter tabs', 'wb-custom-product-tabs-for-woocommerce'),
					'items_list_navigation' => __( 'Tabs navigation', 'wb-custom-product-tabs-for-woocommerce'),
					'items_list'            => __( 'Tabs list', 'wb-custom-product-tabs-for-woocommerce'),
					'item_link'             => __( 'Tab Link', 'wb-custom-product-tabs-for-woocommerce'),
					'item_link_description' => __( 'A link to a tab.', 'wb-custom-product-tabs-for-woocommerce'), 
	            ),
	            'show_ui' => true,
	            'has_archive' => false,
	            'taxonomies'   => array( 'product_cat',  'product_tag' ),
	            'show_in_menu' => 'edit.php?post_type=product'
	        )
	    );
	}

	/**
	 *	@since 1.0.2
	 *	Save metabox data
	 * 	@since 1.1.0 	Added option to save nickname info
	 */
	public function save_meta_box_data($post_id)
	{
		if(array_key_exists('wb_tab_meta_box', $_POST))
		{
			$tab_position=absint($_POST['wb_tab_tab_position']);
			update_post_meta($post_id, '_wb_tab_position', $tab_position);

			$tab_nickname=sanitize_text_field($_POST['wb_tab_tab_nickname']);
			update_post_meta($post_id, '_wb_tab_nickname', $tab_nickname);
		}
	}
	
	/**
	 *	@since 1.0.2
	 *	Register meta box for global tab custom post type
	 * 	@since 1.1.0 Moved tab location from `side` to `normal`. Tab title and id updated 
	 */
	public function register_meta_box()
	{
	    add_meta_box(
			'wb_tab_tab_other_info_meta_box',
			__('Other tab info', 'wb-custom-product-tabs-for-woocommerce'), 
			array($this, '_tab_other_info_meta_box_html'),
			WB_TAB_POST_TYPE,
			'normal',
			'default'
        );
	}

	/**
	 *	@since 1.1.0
	 *	Render HTML for tab other info meta box
	 */
	public function _tab_other_info_meta_box_html( $post, $box)
	{
		$tab_position=Wb_Custom_Product_Tabs_For_Woocommerce::_get_global_tab_position($post->ID);
		$tab_nickname=Wb_Custom_Product_Tabs_For_Woocommerce::_get_global_tab_nickname($post->ID);
		include WB_TAB_ROOT_PATH.'admin/views/_global_tab_metabox.php';
	}

	/**
	* @since 1.0.9 
	* Global tabs, Add new product links on plugins page
	*/
	public function plugin_action_links($links)
	{
		$links[]='<a href="'.admin_url('edit.php?post_type='.WB_TAB_POST_TYPE).'">'.__('Global Product tabs', 'wb-custom-product-tabs-for-woocommerce').'</a>';

		$links[]='<a href="'.admin_url('post-new.php?post_type=product').'">'.__('Add new product', 'wb-custom-product-tabs-for-woocommerce').'</a>';
		return $links;
	}


	/**
	* 	@since 1.1.0 
	* 	Add nickname column in global tabs listing page
	*/
	public function add_nickname_column($columns)
	{
		$out=array();
		foreach($columns as $column_key=>$column_title)
		{
			$out[$column_key]=$column_title;
			if('title'==$column_key)
			{
				$out['wb_tab_nickname']=__('Nickname', 'wb-custom-product-tabs-for-woocommerce');
			}
		}
		return $out;
	}

	/**
	* 	@since 1.1.0 
	* 	Add nickname column data, in global tabs listing page
	*/
	public function add_nickname_column_data($column, $post_id)
	{
		if('wb_tab_nickname'==$column)
		{
			echo Wb_Custom_Product_Tabs_For_Woocommerce::_get_global_tab_nickname($post_id);
		}
	}


	/**
	* 	Add product categories/tags columns in global tabs listing page
	* 
	* 	@since 1.1.3 
	*/
	public function add_product_cat_tag_column($columns)
	{
		$out = array();
		
		foreach($columns as $column_key=>$column_title)
		{
			$out[$column_key]=$column_title;

			if('wb_tab_nickname' == $column_key)
			{
				$out['wb_tab_product_categories'] 	= __('Product categories', 'wb-custom-product-tabs-for-woocommerce');
				$out['wb_tab_product_tags'] 		= __('Product tags', 'wb-custom-product-tabs-for-woocommerce');
			}
		}

		return $out;
	}

	
	/**
	* 	Add product categories/tags column data, in global tabs listing page
	* 
	* 	@since 1.1.3 
	*/
	public function add_product_cat_tag_column_data($column, $post_id)
	{
		if('wb_tab_product_tags' == $column || 'wb_tab_product_categories' == $column)
		{
			$this->_get_product_cat_tag_column_data($post_id, ('wb_tab_product_tags' == $column ? 'product_tag' : 'product_cat'));
		}		
	}


	/**
	* 	Prepare and print data for product categories/tags column data, in global tabs listing page
	* 
	* 	@since 1.1.3 
	*/
	private function _get_product_cat_tag_column_data($post_id, $term = 'product_cat')
	{
		$tab_product_terms = get_the_terms($post_id, $term);

		if($tab_product_terms && is_array($tab_product_terms))
		{
			$tab_product_term_names = array_column($tab_product_terms, 'name');
			echo esc_html(implode(", ", $tab_product_term_names));
		}
	}

	
	/**
	 * 	Is a custom product tab edit/add new page
	 * 
	 * 	@since 	1.1.5
	 * 	@return bool 		Is custom product tab page or not
	 */
	public static function is_wb_tab_page()
	{
		return self::is_a_post_type_page(WB_TAB_POST_TYPE);
	}

	
	/**
	 * 	Is a product edit/add new page
	 * 
	 * 	@since 	1.1.5
	 * 	@return bool 		Is product page or not
	 */
	public static function is_product_edit_page()
	{
		return self::is_a_post_type_page('product');
	}


	/**
	 * 	Is a post type edit/add new page
	 * 
	 * 	@since 	1.1.5
	 * 	@param 	string 		$post_type 		Post type
	 * 	@return bool 		Is post type page or not
	 */
	public static function is_a_post_type_page($post_type)
	{
		$file_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_BASENAME);
		if('post-new.php' === $file_name && isset($_GET['post_type']) && $post_type === sanitize_text_field($_GET['post_type']))
		{
			return true;

		}elseif('post.php' === $file_name && isset($_GET['post']) && 0 < absint($_GET['post']) && $post_type === get_post_type(absint($_GET['post'])))
		{
			return true;
		}

		return false;
	}


	/**
	 * 	Add YouTube embed button for editor
	 * 
	 * 	@since 1.1.5
	 */
	public function add_youtube_embed_button($editor_id = 'content')
	{
		if(self::is_wb_tab_page() || 'wb_tab_editor' === $editor_id)
		{
			$img = '<span class="dashicons dashicons-youtube" style="margin-top:5px;"></span> ';
			$id_attribute = ' id="' . $editor_id . '-wb_cptb-embed-youtube"';

			printf(
				'<button type="button"%s class="button wb_cptb-embed-youtube" data-editor="%s">%s</button>',
				$id_attribute,
				esc_attr($editor_id),
				$img . __('Embed YouTube', 'wb-custom-product-tabs-for-woocommerce')
			);

			
		}
	}


	/**
	 * 	Add YouTube embed popup HTML
	 * 
	 * 	@since 1.1.5
	 */
	public function add_youtube_embed_popup()
	{
		if(self::is_wb_tab_page() || self::is_product_edit_page())
		{
		?>
			<div class="wb_tab_popup_overlay"></div>
			<div class="wb_cptb_youtube_popup wb_tab_popup">
				<div class="wb_tab_popup_hd">		
					<span class="wb_tab_popup_hd_txt">
						<span class="dashicons dashicons-youtube"></span>
						<?php _e('Embed YouTube', 'wb-custom-product-tabs-for-woocommerce'); ?>
					</span>
					<span class="wb_tab_popup_close" title="Close">
						<span class="dashicons dashicons-dismiss"></span>
					</span>
				</div>
				<div class="wb_tab_popup_content">
					<div class="wb_tab_panel_frmgrp">
						<label>
							<?php _e('YouTube URL/Video ID', 'wb-custom-product-tabs-for-woocommerce'); ?>		
						</label>
						<input type="text" name="wb_cptb_youtube_url" class="wb_tabpanel_txt" placeholder="<?php esc_attr_e('YouTube URL/Video ID', 'wb-custom-product-tabs-for-woocommerce'); ?>" value="" style="width:100%;">
						<div class="wb_tab_er"></div>
					</div>
					<div class="wb_tab_panel_frmgrp" style="width:48%;">
						<label><?php _e('Width', 'wb-custom-product-tabs-for-woocommerce'); ?></label>
						<input type="number" name="wb_cptb_youtube_width" class="wb_tabpanel_txt" value="560" style="width:100%;" placeholder="<?php esc_attr_e('Default 560', 'wb-custom-product-tabs-for-woocommerce');?>" step="1" min="50">
						<div class="wb_tab_er"></div>
					</div>
					<div class="wb_tab_panel_frmgrp" style="width:48%; float:right;">
						<label><?php _e('Height', 'wb-custom-product-tabs-for-woocommerce'); ?></label>
						<input type="number" name="wb_cptb_youtube_height" class="wb_tabpanel_txt" value="315" style="width:100%;" placeholder="<?php esc_attr_e('Default 315', 'wb-custom-product-tabs-for-woocommerce');?>" step="1" min="50">
						<div class="wb_tab_er"></div>
					</div>
					<div class="wb_tab_panel_frmgrp" style="text-align:right;">
						<button class="button button-primary wb_tab_done_btn wb_cptb_youtube_insert_btn" type="button"><?php _e('Insert', 'wb-custom-product-tabs-for-woocommerce'); ?></button>
						<button class="button button-secondary wb_tab_cancel_btn" type="button"><?php _e('Cancel', 'wb-custom-product-tabs-for-woocommerce'); ?></button>
					</div>
				</div>
			</div>
		<?php
		}
	}


	/**
	 * 	Show change log in upgrade notice
	 * 
	 * 	@since 1.1.5
	 */
	public function changelog_in_upgrade_notice()
	{
		if(isset($data['upgrade_notice']))
	    {
	        $msg = str_replace(array('<p>', '</p>'), array('<div>', '</div>'), $data['upgrade_notice']);
	        echo '<div class="update-message wb_cptb_upgrade_notice" style="padding-left:20px;">'.wp_kses_post(wpautop($msg)).'</div>';

	        add_action('admin_print_footer_scripts', array($this, 'add_js_css_for_changelog_in_upgrade_notice'));
	    }
	}


	/**
	 * 	Add js css for changelog in upgrade notice
	 * 
	 * 	@since 1.1.5
	 */
	public function add_js_css_for_changelog_in_upgrade_notice()
	{
		global $pagenow;
	    
	    if('plugins.php' === $pagenow)
	    {
	      	?>
	      	<style type="text/css">
	        #wb-custom-product-tabs-for-woocommerce-update .update-message p:last-child{ display:none;}     
	        #wb-custom-product-tabs-for-woocommerce-update ul{ margin-left:20px; list-style:disc;}     
	        </style>
	        <script type="text/javascript">
	        	jQuery(document).ready(function(){\
	        		$('#wb-custom-product-tabs-for-woocommerce-update').find('.wb_cptb_upgrade_notice').next('p').remove();
	        		$('#wb-custom-product-tabs-for-woocommerce-update').find('a.update-link:eq(0)').on('click', function(){
		                $('.wb_cptb_upgrade_notice').remove();
		            });
	        	});
		    </script>
	      	<?php
	    }
	}
}