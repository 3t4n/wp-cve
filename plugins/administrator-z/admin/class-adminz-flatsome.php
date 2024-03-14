<?php 
namespace Adminz\Admin;
use Adminz\Helper\ADMINZ_Helper_Flatsome_Portfolio;
use Adminz\Helper\ADMINZ_Helper_Flatsome_Portfolio_Navigation;
use Adminz\Helper\ADMINZ_Helper_Flatsome_Page_Navigation;
use Adminz\Helper\ADMINZ_Helper_Flatsome_Post_Navigation;
use Adminz\Helper\ADMINZ_Helper_Language;
use Adminz\Helper\ADMINZ_Helper_Flatsome_Header_Element;
use Adminz\Helper\ADMINZ_Helper_Flatsome_Header_Mobile;
use Adminz\Helper\ADMINZ_Helper_Flatsome_Shortcodes;
use Adminz\Helper\ADMINZ_Helper_Flatsome_Tiny_Mce;
use Adminz\Helper\ADMINZ_Helper_Flatsome_Blog;

use Flatsome_Default;

class ADMINZ_Flatsome extends Adminz {
	public $options_group = "adminz_flatsome";
	public $title = 'Flatsome';
	static $slug  = 'adminz_flatsome';
	static $flatsome_actions = [];
	
	static $options;
	static $get_arr_meta_key= [];
	
	function __construct() {		
		if(!$this->is_flatsome()) return;
		$this::$options = get_option('adminz_flatsome', []);
		$this::$flatsome_actions = require_once(ADMINZ_DIR.'inc/file/flatsome_hooks.php');
		
		add_filter( 'adminz_setting_tab', [$this,'register_tab']);
		add_action( 'adminz_tabs_html',[$this,'tab_html']);
		
		add_action(	'admin_init', [$this,'register_option_setting'] );
		add_action( 'admin_init', function () {remove_action( 'admin_notices', 'flatsome_maintenance_admin_notice' ); });
		add_action( 'init', [$this, 'add_shortcodes'] );		
		

		$this->enqueue_package();
		$this->adminz_fix_css();
		
		
		
		$header_element = new ADMINZ_Helper_Flatsome_Header_Element();
		$header_element->create_adminz_header_element();
		new ADMINZ_Helper_Flatsome_Header_Mobile();
		new ADMINZ_Helper_Flatsome_Portfolio();
		new ADMINZ_Helper_Flatsome_Shortcodes();
		new ADMINZ_Helper_Flatsome_Blog();
		
 		$this->flatsome_filter_hook();
 		$this->flatsome_action_hook(); 	
	}
	
	function register_tab($tabs) {
 		if(!$this->title) return;
 		$this->title = $this->get_icon_html('flatsome').$this->title;
        $tabs[self::$slug] = array(
            'title' => $this->title,
            'slug' => self::$slug,
            'type' => '1'
        );
        return $tabs;
    }
	function enqueue_package(){		

		if($choose = $this->get_option_value('adminz_choose_stylesheet')){
			add_filter('body_class',function($classes)use ($choose){
				if(apply_filters('adminz_pack1_enable_sidebar',true)){
					$classes[] = 'enable_sidebar';
				}
				if(apply_filters('adminz_pack1_hide_section_title_b',true)){
					$classes[] = 'hide_section_title_b';
				}

				$classes[] = $choose;
				return $classes;
			});
			add_action( 'wp_enqueue_scripts', function()use($choose){
				foreach ($this->get_packages() as $key => $value) {
					if($value['slug'] == $choose){
						wp_enqueue_style( 'flatsome_css_pack',$value['url']);
					}
				} 
				if($choose == 'pack1'){
					?>
					<style type="text/css">
						:root {
							--big-radius: <?php echo apply_filters('adminz_pack1_big-radius','10px'); ?>;
							--small-radius: <?php echo apply_filters('adminz_pack1_small-radius','5px'); ?>;
							--form-controls-rarius: <?php echo apply_filters('adminz_pack1_form-controls-radius','5px'); ?>;;
							--main-gray: <?php echo apply_filters('adminz_pack1_main-gray','#0000000a'); ?>;
							--border-color: <?php echo apply_filters('adminz_pack1_border-color','transparent'); ?>;
						}
					</style>
					<?php
				}
			},999);			
		}		
 	}
	function adminz_fix_css(){
		add_action( 'wp_head', function(){
			ob_start();
			require_once(ADMINZ_DIR.'inc/file/flatsome_css_fix.php');
			echo apply_filters( 'adminz_output_debug', ob_get_clean() );
		}, 999 );	
		add_filter('body_class',function($classes){
			if($this->get_option_value('adminz_enable_vertical_blog_post_mobile') == "on"){
				$classes[] = 'adminz_enable_vertical_blog_post_mobile';
			}
			if($this->get_option_value('adminz_hide_headermain_on_scroll') == "on"){
				$classes[] = 'adminz_hide_headermain_on_scroll';
			}
			if($this->get_option_value('adminz_enable_vertical_product_mobile') == "on"){
				$classes[] = 'adminz_enable_vertical_product_mobile';
			}
			if($this->get_option_value('adminz_enable_vertical_product_related_mobile') == "on"){
				$classes[] = 'adminz_enable_vertical_product_related_mobile';
			}
			if($this->get_option_value('fix_product_image_box_vertical') == "on"){
				$classes[] = 'fix_product_image_box_vertical';
			}

			return $classes;
		});
	}	
	function add_shortcodes(){
		$shortcodefiles = glob(ADMINZ_DIR.'shortcodes/flatsome*.php');
		if(!empty($shortcodefiles)){
			foreach ($shortcodefiles as $file) {
				require_once $file;
			}
		}

		// shortocdes/inc/flatsome-element-advanced.php
		add_filter('adminz_apply_content_change', function($return, $atts, $content){

			extract(shortcode_atts(array(
				"search" => "",
				"replace" => "",
				"class"=>"",
				"css"=>""
		    ), $atts));
			
			$content = trim($content);			
			if($content){
				$content = str_replace("XXX", $return,$content);
				$return = $content;
			}

			
			
			$return = str_replace($search, $replace, $return);

			ob_start();
			?>
			<div class="<?php echo esc_attr($class); ?>">
				<?php echo do_shortcode( $return ); ?>
				<?php if($css): ?>
					<style type="text/css">
						<?php echo esc_attr($css); ?>
					</style>
				<?php endif; ?>
			</div>
			<?php
			return ob_get_clean();
			
		},10,3);

	}
	function flatsome_action_hook(){		
		static $called = false;
		if($called){ return; }

		if(isset($_GET['testmeta'])){
			$post_id = sanitize_title($_GET['testmeta']);
			global $wpdb;
			$results = $wpdb->get_results(
		        $wpdb->prepare("SELECT * FROM {$wpdb->prefix}postmeta WHERE post_id = %d", $post_id)
		    );
		    if (!empty($results)) {
		        echo "<pre>";print_r($results);echo "</pre>";
		    } else {
		        return 'Không tìm thấy dữ liệu với post_id = ' . $post_id;
		    }
		    die;

		}

		$adminz_flatsome_action_hook = $this->get_option_value('adminz_flatsome_action_hook');
		if(!empty($adminz_flatsome_action_hook) and is_array($adminz_flatsome_action_hook)){			
			foreach ($adminz_flatsome_action_hook as $action => $shortcode) {
				if($shortcode){
					add_action($action,function() use ($shortcode){
						echo do_shortcode(html_entity_decode($shortcode));
					});
				}				
			}
		}
		add_action('init',function(){
			if(
				$this->get_option_value('adminz_flatsome_test_all_hook') == "on" or 
				(isset($_GET['testhook']) and $_GET['testhook'] =='flatsome')
			){
				// if(!is_admin()){
					foreach (self::$flatsome_actions as $action) {
						add_action($action, function() use ($action){
							echo do_shortcode('[adminz_test content="'.$action.'"]');
						});
					}
				// }
			}
		});

		$flatsome_hook_data = json_decode($this->get_option_value('adminz_flatsome_custom_hook'));		
		if(!empty($flatsome_hook_data) and is_array($flatsome_hook_data)){
			foreach ($flatsome_hook_data as $value) {
				$value[2] = $value[2]? $value[2] : 0;
				add_action($value[1],function() use ($value){		
					$condition = true;
					if(!empty($value[3])){
						$condition = call_user_func($value[3]);
					}
					if($condition){
						echo html_entity_decode(do_shortcode($value[0])); 
					}					
				},$value[2]);				
			}
		}
		$called = true;

	}
	function flatsome_filter_hook(){
		add_filter( 'flatsome_text_formats',[$this,'custom_text_format']);
		
		$btn_inside = $this->get_option_value('adminz_flatsome_lightbox_close_btn_inside');
		if( $btn_inside == 'on'){
			add_filter( 'flatsome_lightbox_close_btn_inside', '__return_true' );
		}

		$btn_close = $this->get_option_value('adminz_flatsome_lightbox_close_button');		
		if($btn_close){
			add_filter( 'flatsome_lightbox_close_button', function ( ) use ($btn_close){
				$html = '<button title="%title%" type="button" style="fill:white; display: grid; padding: 5px;" class="mfp-close">';
				$html .= $this->get_icon_html($btn_close );
				$html .= '</button>';
				return $html;
			});
		}


		$viewport = $this->get_option_value('adminz_flatsome_viewport_meta');				
		if($viewport =="on"){
			add_filter( 'flatsome_viewport_meta',function (){ __return_null();});
		}

		$pages = $this->get_option_value('page_for_transparent');	
		if(!empty($pages)){
			add_filter( 'body_class', function( $classes ) use($pages) {
				//https://gist.github.com/Bradley-D/7287723
				if($this->is_woocommerce()){
					if(is_shop()){
						if(in_array(get_option( 'woocommerce_shop_page_id' ),$pages)){
							$classes[] = 'page_for_transparent';
						}
					}elseif(is_cart()){
						if(in_array(get_option( 'woocommerce_cart_page_id' ),$pages)){
							$classes[] = 'page_for_transparent';
						}
					}elseif(is_checkout()){
						if(in_array(get_option( 'woocommerce_checkout_page_id' ),$pages)){
							$classes[] = 'page_for_transparent';
						}
					}elseif(is_account_page()){
						if(in_array(get_option( 'woocommerce_myaccount_page_id' ),$pages)){
							$classes[] = 'page_for_transparent';
						}
					}
				}

				if(is_home()){
					if(in_array(get_option( 'page_for_posts' ),$pages)){
						$classes[] = 'page_for_transparent';
					}
				}

				if(in_array(get_the_ID(),$pages)){
					$classes[] = 'page_for_transparent';
				}
				
				return $classes;
			} );
		}

		$adminz_use_mce_button = $this->get_option_value('adminz_use_mce_button');
		if($adminz_use_mce_button){
			if(is_admin()){	
				new ADMINZ_Helper_Flatsome_Tiny_Mce;
			}
		}
		if($this->check_option('navigation_auto_fill',false,"on")){
			new ADMINZ_Helper_Flatsome_Portfolio_Navigation;
			new ADMINZ_Helper_Flatsome_Page_Navigation;
			new ADMINZ_Helper_Flatsome_Post_Navigation;
		}

		$post_type_support = (array)$this->get_option_value('post_type_support');
		if(!empty($post_type_support) and is_array($post_type_support)){
			foreach ($post_type_support as $key => $post_type) {
				$a            = new \Adminz\Helper\ADMINZ_Helper_Flatsome_Ux_Builder;
				$a->post_type = $post_type;
				$a->post_type_content_support();
			}
		}

		$post_type_template = (array)$this->get_option_value('post_type_template');				
		if(!empty($post_type_template) and is_array($post_type_template)){
		    foreach ($post_type_template as $post_type => $template) {
		    	if($template){
					$b                    = new \Adminz\Helper\ADMINZ_Helper_Flatsome_Ux_Builder;
					$b->post_type         = $post_type;
					$b->template_block_id = $template;
					$b->post_type_layout_support();					
		    	}		        
		    }
		}
		
		$taxonomy_layout_support = (array) $this->get_option_value( 'taxonomy_layout_support' );
		if(!empty($taxonomy_layout_support) and is_array($taxonomy_layout_support)){
			foreach ($taxonomy_layout_support as $tax => $template) {
				if($template){
					$c                    = new \Adminz\Helper\ADMINZ_Helper_Flatsome_Ux_Builder;
					$c->taxonomy         = $tax;
					$c->tax_template_block_id = $template;
					$c->taxonomy_layout_support();
				}
			}
		}
		


		// var_dump($this->check_option('disabled_embedded_scripts_admin',false,"on"));die;
		if($this->check_option('disabled_scripts_embedded_with_user',false,"on")){
			$this->disabled_embedded_scripts_admin();
		}

		
	}

	function disabled_embedded_scripts_admin(){
		// Tắt hết script nếu user đã login 
		if(!is_user_logged_in()) return;
		add_action('init',function(){
			remove_action( 'wp_head', 'flatsome_custom_header_js' );
			remove_action('wp_footer', 'flatsome_footer_scripts');
			remove_action( 'flatsome_after_body_open', 'flatsome_after_body_open' );
			remove_action( 'wp_footer', 'flatsome_before_body_close', apply_filters( 'flatsome_before_body_close_priority', 9999 ) );
		});

	}
	
	function custom_text_format($arr){
		foreach ($arr as $key => $value) {
			if($value['title'] == 'List Styles'){
				$arr[$key]['items'][] = array(
	              'title' => 'Style List - None',
	              'selector' => 'li',
	              'classes' => 'list-style-none',
	            );
			}
			if($value['title'] == 'Text Background'){
				$arr[$key]['items'][] = array(
	              'title' => 'Text shadow 1',
	              'inline' => 'span',
	              'classes' => 'text-shadow-1',
	            );
	            $arr[$key]['items'][] = array(
	              'title' => 'Text shadow 2',
	              'inline' => 'span',
	              'classes' => 'text-shadow-2',
	            );
	            $arr[$key]['items'][] = array(
	              'title' => 'Text shadow 3',
	              'inline' => 'span',
	              'classes' => 'text-shadow-3',
	            );
	            $arr[$key]['items'][] = array(
	              'title' => 'Text shadow 4',
	              'inline' => 'span',
	              'classes' => 'text-shadow-4',
	            );
	            $arr[$key]['items'][] = array(
	              'title' => 'Text shadow 5',
	              'inline' => 'span',
	              'classes' => 'text-shadow-5',
	            );
			}
		}
		return $arr;
	}
	function tab_html(){
		if(!isset($_GET['tab']) or $_GET['tab'] !== self::$slug) return;
		?>
		<form method="post" action="options.php">
	        <?php 
	        settings_fields($this->options_group);
	        do_settings_sections($this->options_group);
	        ?>
	        <table class="form-table">	        	
	        	<tr valign="top">
	        		<th><h3>UX builder</h3></th>
	        		<td>Some shortcode from ux builder has beed added. Open Ux builder to show</td>
	        	</tr>	
	        	<tr valign="top">
	        		<th scope="row">Uxbuilder Content Support </br><small>post type</small></th>
	        		<td>
	        			<?php
	        				$post_types = get_post_types();
	        				if(!empty($post_types) and is_array($post_types)){
	        					foreach ($post_types as $key => $post_type) {
	        						$checked = "";
		        					if(in_array($post_type,(array)$this->get_option_value('post_type_support'))){
		        						$checked = 'checked';
		        					}
	        						?>
	        						<label class="page_for_transparent">
	        							<input 
	        								name="adminz_flatsome[post_type_support][]" 
	        								type="checkbox" 
	        								<?php echo esc_attr($checked); ?>
	        								value="<?php echo esc_attr($post_type) ?>"
	        								>
	        							<?php echo esc_attr($post_type); ?>
	        						</label>
	        						<?php
	        					}
	        				}
	        			?>
						<div>
							<small>
								Looking for: Remove the post's default <strong>sidebar</strong>? | 
								Let's create a <strong>block</strong> valued: <strong>[adminz_post_field post_field="post_content"][/adminz_post_field]</strong> | 
								Then set that block to the post type layout in <strong>Uxbuilder Layout Support</strong></br>
							</small>
						</div>
	        		</td>
	        	</tr>
	        	<tr valign="top">
	        		<th scope="row">Uxbuilder Layout Support </br><small>post type</small></th>
	        		<td>						
	        			<?php
	        				$post_types = get_post_types();
	        				$blocks_arr = $this->get_blocks_arr();

	        				$post_type_template = (array)$this->get_option_value('post_type_template');
	        				// echo "<pre>";print_r($post_type_template);echo "</pre>";

	        				if(!empty($post_types) and is_array($post_types)){
	        					foreach ($post_types as $key => $post_type) {
	        						?>
	        						<label class="page_for_transparent">		        						
		        						<?php echo esc_attr( $post_type ); ?>
		        						<select 
		        							style="width: 100%;"
		        							name="adminz_flatsome[post_type_template][<?php echo esc_attr($post_type); ?>]"
		        							>
		        							<option value="">
		        								-- 
		        							</option>
		        							<?php
		        								if(!empty($blocks_arr) and is_array($blocks_arr)){
		        								    foreach ($blocks_arr as $block_id => $block_title) {
		        								    	$_value = "block_id_".$block_id;
		        								        ?>
		        								        <option 
		        								        	<?php
		        								        		if (
		        								        			isset($post_type_template[$key]) and 
		        								        			$post_type_template[$key] == $_value
		        								        		) echo 'selected';
		        								        	?>
		        								        	value="<?php echo esc_attr($_value); ?>">
		        								        	<?php echo esc_attr($block_title); ?>
		        								        </option>
		        								        <?php
		        								    }
		        								}
		        							?>
		        							<?php
		        								$taxonomies = get_object_taxonomies($post_type);
		        								if(!empty($taxonomies) and is_array($taxonomies)){
		        								    foreach ($taxonomies as $index => $_tax) {
		        								    	$_value = "taxonomy_".$_tax;
		        								        ?>
		        								        <option 
		        								        	<?php
		        								        		if (
		        								        			isset($post_type_template[$key]) and 
		        								        			$post_type_template[$key] == $_value
		        								        		) echo 'selected';
		        								        	?>
		        								        	value="<?php echo esc_attr($_value); ?>">
		        								        	Terms of: <?php echo esc_attr($_tax); ?>
		        								        </option>
		        								        <?php
		        								    }
		        								}
		        							?>
		        						</select>
	        						</label>
	        						<?php
	        					}
	        				}
	        			?>
	        		</td>
	        	</tr>
				<tr valign="top">
	        		<th>Uxbuilder Layout Support </br><small>Taxonomy</small></th>
	        		<td>
	        			<?php
							$taxonomies = get_taxonomies();
							$blocks_arr = $this->get_blocks_arr();

							$taxonomy_layout_support = (array) $this->get_option_value( 'taxonomy_layout_support' );

							if(!empty($taxonomies) and is_array($taxonomies)){
								foreach ($taxonomies as $key => $taxonomy) {
									?>
									<label class="page_for_transparent">
										<?php echo esc_attr($taxonomy) ?>
										<select 
		        							style="width: 100%;"
		        							name="adminz_flatsome[taxonomy_layout_support][<?php echo esc_attr( $taxonomy ); ?>]">
											<option value="">
												-- 
											</option>
											<?php
		        								if(!empty($blocks_arr) and is_array($blocks_arr)){
		        								    foreach ($blocks_arr as $block_id => $block_title) {
		        								    	$_value = "block_id_".$block_id;
		        								        ?>
		        								        <option 
		        								        	<?php
		        								        		if (
		        								        			isset($taxonomy_layout_support[$key]) and 
		        								        			$taxonomy_layout_support[$key] == $_value
		        								        		) echo 'selected';
		        								        	?>
		        								        	value="<?php echo esc_attr($_value); ?>">
		        								        	<?php echo esc_attr($block_title); ?>
		        								        </option>
		        								        <?php
		        								    }
		        								}
		        							?>
										</select>
									</label>
									<?php
								}
							}
						?>
						<div>
							<small>
								Looking for: posts grid?. Use element: <strong>Taxonomy Posts</strong>
							</small>
						</div>
	        		</td>
	        	</tr>
	        	<tr valign="top">
	        		<th>Embeded scripts</th>
	        		<td>
	        			<label>
	        				<?php 
	        				$checked = "";
	        				if($this->check_option('disabled_scripts_embedded_with_user',false,"on")){
	        					$checked = "checked";
	        				}
	        				?>
	                		<input type="checkbox" name="adminz_flatsome[disabled_scripts_embedded_with_user]" <?php echo esc_attr($checked); ?>> Disable all external scripts when is logged in (Ex: Facebook, GA)
	                	</label><br>
	        		</td>
	        	</tr>
        	</table>
	        <?php submit_button(); ?>
	        <table class="form-table">	
	        	<tr valign="top">
					<th scope="row">
						<h3>Stylesheet CSS package</h3>
					</th>
				</tr>
	            <tr valign="top">
	                <th scope="row">Choose style</th>
	                <td>
	                	<?php 						
						$choose = $this->get_option_value('adminz_choose_stylesheet');
	                	 ?>
	                	<select name="adminz_flatsome[adminz_choose_stylesheet]">
	                	<?php
                		foreach ($this->get_packages() as $pack) {
                			$seleted = ($choose == $pack['slug']) ? "selected" : "";
                			?>
                			<option <?php echo esc_attr($seleted); ?> value="<?php echo esc_attr($pack['slug']) ?>"><?php echo esc_attr($pack['name']); ?></option>
                			<?php
                		}
                	 	?>
                	 	</select>
	                </td>
	            </tr>	            
	        	<tr valign="top">
	        		<th><h3>Flatsome config</h3></th>
	        		<td></td>
	        	</tr>
	        	<tr valign="top">
	        		<th>Editor</th>
	        		<td>
	        			<label>
	        				<?php 
	        				$checked = "";
	        				if($this->check_option('adminz_use_mce_button',false,"on")){
	        					$checked = "checked";
	        				}
	        				?>
	                		<input type="checkbox" name="adminz_flatsome[adminz_use_mce_button]" <?php echo esc_attr($checked); ?>> Enable Tiny MCE editor
	                	</label><br>
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			Lightbox close button inside
	        		</th>
	        		<td>
	        			<input type="checkbox" <?php echo $this->check_option('adminz_flatsome_lightbox_close_btn_inside',false,"on") ? 'checked' : ''; ?>  name="adminz_flatsome[adminz_flatsome_lightbox_close_btn_inside]"/>
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			Lightbox close button icon
	        		</th>
	        		<td>
	        			<input type="text" value="<?php echo esc_attr($this->get_option_value('adminz_flatsome_lightbox_close_button'));?>"  name="adminz_flatsome[adminz_flatsome_lightbox_close_button]"/>
	        			<small>Example: close-round or svg url| Default: close</small>
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			Disable Meta viewport
	        		</th>
	        		<td>
	        			<input type="checkbox" <?php echo $this->check_option('adminz_flatsome_viewport_meta',false,"on") ? 'checked' : ''; ?>  name="adminz_flatsome[adminz_flatsome_viewport_meta]"/>
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			Flatsome woocommerce product gallery 
	        		</th>
	        		<td>
	        			<input type="text" value="<?php echo esc_attr($this->get_option_value('adminz_flatsome_woocommerce_product_gallery'));?>"  name="adminz_flatsome[adminz_flatsome_woocommerce_product_gallery]"/>
	        			<small>Small thumbnails in product gallery</small>
	        		</td>
	        	</tr>
        	</table>
	        <?php submit_button(); ?>
	        <table class="form-table">		
	        	<tr valign="top">	        		
	        		<th>
	        			Vertical Posts box on mobile
	        		</th>
	        		<td>
	        			<input type="checkbox" <?php echo $this->check_option('adminz_enable_vertical_blog_post_mobile',false,"on") ? 'checked' : ''; ?>  name="adminz_flatsome[adminz_enable_vertical_blog_post_mobile]"/>
	        			<small>Enable: Keep vertical in mobile | default post thumbnail width: 25%</small>
	        		</td>
	        	</tr>
	        	<?php if($this->is_woocommerce()){ ?>
	        	<tr valign="top">	        		
	        		<th>
	        			Vertical Product box on mobile
	        		</th>
	        		<td>
	        			<input type="checkbox" <?php echo $this->check_option('adminz_enable_vertical_product_mobile',false,"on") ? 'checked' : ''; ?>  name="adminz_flatsome[adminz_enable_vertical_product_mobile]"/>
	        			<small>Enable: Keep vertical in mobile | default product thumbnail width: 25%</small>
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			Vertical Product related on mobile
	        		</th>
	        		<td>
	        			<input type="checkbox" <?php echo $this->check_option('adminz_enable_vertical_product_related_mobile',false,"on") ? 'checked' : ''; ?>  name="adminz_flatsome[adminz_enable_vertical_product_related_mobile]"/>
	        			<small>Enable: Vertical on mobile | default product thumbnail width: 25%</small>
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			Fix product vertical thumbnail size desktop
	        		</th>
	        		<td>
	        			<input type="checkbox" <?php echo $this->check_option('fix_product_image_box_vertical',false,"on") ? 'checked' : ''; ?>  name="adminz_flatsome[fix_product_image_box_vertical]"/>
	        			<small>Enable: Fixed product box image thumbnail width: 25%</small>
	        		</td>
	        	</tr>
	        	<?php } ?>
	        	<tr valign="top">	        		
	        		<th>
	        			Enable Zalo, skype icon support
	        		</th>
	        		<td>
	        			<input type="checkbox" <?php echo $this->check_option('adminz_enable_zalo_support',false,"on") ? 'checked' : ''; ?>  name="adminz_flatsome[adminz_enable_zalo_support]"/>
	        			<small>Enable: Add new builder with zalo follow icon</small>
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			Hide Header main on scroll - Desktop
	        		</th>
	        		<td>
	        			<input type="checkbox" <?php echo $this->check_option('adminz_hide_headermain_on_scroll',false,"on") ? 'checked' : ''; ?>  name="adminz_flatsome[adminz_hide_headermain_on_scroll]"/>
	        			<small>Notice: Enable sticky header main, sticky header bottom & this function. </small>
	        		</td>
	        	</tr>

	        	<tr valign="top">	        		
	        		<th>
	        			Set a page to transparent header - Desktop
	        		</th>
	        		<td>
	        			<?php 	        			
	        			$get_pages = get_pages();	        			
	        			if(!empty($get_pages) and is_array($get_pages)){
	        				foreach($get_pages as $key=>$page){
	        					$checked = "";
	        					if(in_array($page->ID,(array)$this->get_option_value('page_for_transparent'))){
	        						$checked = 'checked';
	        					}
	        					echo '<label class="page_for_transparent"><input name="adminz_flatsome[page_for_transparent][]" type="checkbox" '.esc_attr($checked).' value="'.esc_attr($page->ID).'"/>'.esc_attr($page->post_title)."</label>";
	        				}
	        			}

	        			?>
	        			<p>
	        				<small>Notice: Replace default functionality of flatsome. </small>
	        			</p>
	        			<style type="text/css">
	        				label.page_for_transparent{
	        					width: 50%;
	        					display: inline-block;
	        					margin-bottom:  10px;
	        				}
	        				@media only screen and (min-width:  768px){
	        					label.page_for_transparent{
		        					width: 10%;
		        					display: inline-block;
		        				}
	        				}
	        			</style>
	        		</td>
	        	</tr>
        	</table>
	        <?php submit_button(); ?>
	        <table class="form-table">
	        	<tr valign="top">
	        		<th><h3>Portfolio</h3></th>
	        		<td></td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			<?php echo __( 'Portfolio', 'flatsome-admin' ); ?> rename
	        		</th>
	        		<td>	
	        			<input type="text" value="<?php echo esc_attr($this->get_option_value('adminz_flatsome_portfolio_name'));?>"  name="adminz_flatsome[adminz_flatsome_portfolio_name]"/>        			
	        			<small>First you can try with Customize->Portfolio->Custom portfolio page <a href="https://www.youtube.com/watch?v=3cl6XCUjOPI">Link</a></small>        			
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			<?php echo  __( 'Portfolio Categories', 'flatsome-admin' ); ?> rename
	        		</th>
	        		<td>
	        			<input type="text" value="<?php echo esc_attr($this->get_option_value('adminz_flatsome_portfolio_category'));?>"  name="adminz_flatsome[adminz_flatsome_portfolio_category]"/>
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			<?php echo __( 'Portfolio Tags', 'flatsome-admin' ); ?> rename
	        		</th>
	        		<td>
	        			<input type="text" value="<?php echo esc_attr($this->get_option_value('adminz_flatsome_portfolio_tag'));?>"  name="adminz_flatsome[adminz_flatsome_portfolio_tag]"/>
	        		</td>
	        	</tr>	        	
	        	<?php if($this->is_woocommerce()){ ?>        		
	        	<tr valign="top">	        		
	        		<th>
	        			Sync portfolio with product
	        		</th>
	        		<td>
	        			<?php
	        			$portfolio_tax = $this->get_option_value('adminz_flatsome_portfolio_product_tax');
	        			$taxonomies = get_object_taxonomies( 'product', 'objects' );	        			
	        			if(!empty($taxonomies) and is_array($taxonomies)){
	        				echo "<select name='adminz_flatsome[adminz_flatsome_portfolio_product_tax]'>";
	        				echo "<option value=''>-- Select --</option>";
	        				foreach ($taxonomies as $key => $value) {
	        					$checked = ($portfolio_tax == $key)? "selected" : "";
	        					echo "<option value='".esc_attr($key)."' ".esc_attr($checked).">".esc_attr($value->label)."</option>";
	        				}
	        				echo "</select>";
        				}
	        			?> Choose your taxonomy
	        			</br>
	        			<button class="button" onclick="jQuery('#adminz_flatsome_portfolio_pr_tax_guid').toggle(); return false;">Show guid</button>
	        			<small id="adminz_flatsome_portfolio_pr_tax_guid" style="display: none;">
	        				<ul>
	        					<li>1. Move to trash all portfolio, then restore/ pulish all to general product taxonomy and </li>
	        					<li>2. When you create new portfolio. It will create new term for your custom taxonomy and sync them.</li>
	        					<li>Note: If using bulk edit. You need re-save portfolio again.</li>
	        				</ul>	        				
	        			</small>
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			Product list in portfolio
	        		</th>
	        		<td>
	        			<textarea cols="70" rows="1" name="adminz_flatsome[adminz_add_products_after_portfolio_title]"><?php echo esc_attr($this->get_option_value('adminz_add_products_after_portfolio_title'));?></textarea>
	        			<p>
	        				<input type="checkbox" name="adminz_flatsome[adminz_add_products_after_portfolio]" <?php if($this->get_option_value('adminz_add_products_after_portfolio') == "on") echo "checked"; ?> />
	        				Auto add products after portfolio content
	        			</p>
	        			<button class="button" onclick="jQuery('#z3213321').toggle(); return false;">Show guid</button>
	        			<strong style="display: none; " id="z3213321">
	        				<code>[adminz_flatsome_portfolio_product_list title='' columns = 2 depth = 1 depth_hover= 2 image_width=25 text_align= 'left' style= 'vertical' type='row']</code>
	        			</strong>
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			Portfolio info box in product page
	        		</th>
	        		<td>
	        			<button class="button" onclick="jQuery('#z32131313').toggle(); return false;">Show guid</button>
	        			<strong style="display: none; " id="z32131313">
	        				<code>[adminz_flatsome_product_portfolio_info  title= '' show_producs_sync_portfolio= '' title_small= 'Same Portfolio' columns = 2 depth = 1 depth_hover= 2 image_width=25 text_align= 'left' style= 'vertical' type='row']</code>
	        			</strong>
	        		</td>
	        	</tr>
	        	<?php } ?>
	        	<tr valign="top">	        		
	        		<th>
	        			Portfolio search form
	        		</th>
	        		<td>
	        			<button class="button" onclick="jQuery('#z434').toggle(); return false;">Show guid</button>
	        			<strong style="display: none; " id="z434">
	        				<code>[adminz_flatsome_portfolios_form]</code>
	        			</strong>
	        		</td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			Portfolio search results pages
	        		</th>
	        		<td>
	        			<button class="button" onclick="jQuery('#z2131').toggle(); return false;">Show guid</button>
	        			<strong style="display: none; " id="z2131">
	        				<code>[adminz_flatsome_portfolios_search_result]</code>
	        			</strong>
	        		</td>
	        	</tr>
				<tr valign="top">
					<th scope="row">
						Navigation Auto Fill
					</th>
					<td>
						<label>
							<input type="checkbox" name="adminz_flatsome[navigation_auto_fill]" <?php if($this->get_option_value('navigation_auto_fill') =="on") echo "checked"; ?>> Enable this function</br>
							<button class="button" onclick="jQuery('#adminz_flatsome_navigtion_woo').toggle(); return false;">Show guid</button>
							<div id="adminz_flatsome_navigtion_woo" style="display: none;">
								<p>* How to use: Type code into CSS classes input of Navigation Items class</p>
								<p> <strong>Portfolio: </strong></p>
								<p>Get portfolios: <code>adminz_portfolio</code> Fill Portfolios as child of Navigation item</p>
								<p>Get categories: <code>adminz_portfolio_category</code></p>
								<p>Get categories: <code>adminz_portfolio_category_replace</code> Replace mode </p>
								<p>By parent: <code>parent_{term_id}</code> For get only children or your term_id. Ex: parent_57</p>
								<p> <strong>Page:</strong></p>
								<p>Get Pages: <code>adminz_page</code></p>
								<p>Get Pages: <code>adminz_page_replace</code> Mode Replaces</p>
								<p>Get pages as child of id: <code>parent_{id}</code> For get only children or your page #57. Ex: parent_57</p>
								<p> <strong>Post: </strong></p>
								<p>Get posts: <code>adminz_post</code> Fill posts as child of Navigation item</p>
								<p>Get categories: <code>adminz_post_category</code></p>
								<p>Get categories: <code>adminz_post_category_replace</code> Replace mode </p>
								<p>By parent: <code>parent_{term_id}</code> For get only children or your term_id. Ex: parent_57</p>

							</div>
						</label>
					</td>
				</tr>
	        </table>
	        <?php submit_button(); ?>
	        <table class="form-table">		
	        	<tr valign="top">
	        		<th><h3>Flatsome Actions hook</h3></th>
	        		<td></td>
	        	</tr>
	        	<tr valign="top">	        		
	        		<th>
	        			List action hooks
	        		</th>
	        		<td>
	        			<p>type <code>[adminz_test]</code> to test</p>	        			
	        		</td>
	        	</tr>
	        	<tr valign="top">
	        			<th>
		        			
		        		</th>	
		        		<td>    
				        	<?php 
				        	$adminz_flatsome_action_hook = $this->get_option_value('adminz_flatsome_action_hook');
				        	foreach (self::$flatsome_actions as $key => $value) {
				        		?>
				        		<div>
				        			<textarea cols="70" rows="1" name="adminz_flatsome[adminz_flatsome_action_hook][<?php echo esc_attr($value);?>]"><?php echo isset($adminz_flatsome_action_hook[$value]) ? esc_attr($adminz_flatsome_action_hook[$value]) : "";?></textarea><small><?php echo esc_attr($value); ?></small>
				        		</div>
				        		<?php
				        	}
			        	 	?>
			        	 	<input type="checkbox" name="adminz_flatsome[adminz_flatsome_test_all_hook]" <?php if($this->get_option_value('adminz_flatsome_test_all_hook') == "on") echo "checked"; ?>><small>Test all hook</small>
			        	</td> 
	        	 </tr>
	        	 <tr valign="top">
					<th scope="row">
						Custom action hooks						
					</th>
					<td>
						<p>type <code>[adminz_test]</code> to test</p>		
						<?php $flatsome_hook_data = $this->get_option_value('adminz_flatsome_custom_hook'); ?>
						<textarea style="display: none;" cols="70" rows="10" name="adminz_flatsome[adminz_flatsome_custom_hook]"><?php echo esc_attr($flatsome_hook_data); ?></textarea> </br>
						<div>
							<textarea cols="40" rows="1" disabled>Shortcode</textarea> 
							<textarea cols="40" rows="1" disabled>Action hook</textarea> 
							<textarea cols="20" rows="1" disabled>Priority</textarea>
							<textarea cols="20" rows="1" disabled>Conditional</textarea>
						</div>
						<div class="adminz_flatsome_custom_hook">
							<?php 
							$flatsome_hook_data = json_decode($flatsome_hook_data);							
							if(!empty($flatsome_hook_data) and is_array($flatsome_hook_data)){
								foreach ($flatsome_hook_data as $key => $value) {
									$value[0] = isset($value[0])? $value[0] : "";
									$value[1] = isset($value[1])? $value[1] : "";
									$value[2] = isset($value[2])? $value[2] : "";
									$value[3] = isset($value[3])? $value[3] : "";
									echo '<div class="item" style="margin-bottom: 5px;">
										<textarea cols="40" rows="1" name="" placeholder="[your shortcode]">'.esc_attr($value[0]).'</textarea>
										<textarea cols="40" rows="1" name="" placeholder="your action hook">'.esc_attr($value[1]).'</textarea>
										<textarea cols="20" rows="1" name="" placeholder="your priority">'.esc_attr($value[2]).'</textarea>
										<textarea cols="20" rows="1" name="" placeholder="your conditional">'.esc_attr($value[3]).'</textarea>
										<button class="button adminz_flatsome_custom_hook_remove" >Remove</button>
									</div>';
								}
							}
							?>							
						</div>
						<button class="button" id="adminz_flatsome_custom_hook_add">Add new</button>
						<script type="text/javascript">
							window.addEventListener('DOMContentLoaded', function() {
								(function($){
									var custom_woo_hooks_item = '<div class="item" style="margin-bottom: 5px;"> <textarea cols="40" rows="1" name="" placeholder="[your shortcode]"></textarea> <textarea cols="40" rows="1" name="" placeholder="your action hook"></textarea> <textarea cols="20" rows="1" name="" placeholder="your priority"></textarea> <textarea cols="20" rows="1" name="" placeholder="your conditional"></textarea> <button class="button adminz_flatsome_custom_hook_remove" >Remove</button> </div>'; $("body").on("click","#adminz_flatsome_custom_hook_add",function(){
									$(".adminz_flatsome_custom_hook").append(custom_woo_hooks_item);
										adminz_flatsome_custom_hook_update();
										return false;
									});
									$("body").on("click",".adminz_flatsome_custom_hook_remove",function(){
										$(this).closest(".item").remove();
										adminz_flatsome_custom_hook_update();
										return false;
									});
									$('body').on('keyup', '.adminz_flatsome_custom_hook .item textarea', function() {
					        			adminz_flatsome_custom_hook_update();					        			
					        		});
									function adminz_flatsome_custom_hook_update(){
										var data_js = $('textarea[name="adminz_flatsome\[adminz_flatsome_custom_hook\]"]').val();

										var alldata = [];
										$('.adminz_flatsome_custom_hook .item').each(function(){
											var itemdata = [];
											var shortcode 	= $(this).find('textarea:nth-child(1)').val();
											var hook 		= $(this).find('textarea:nth-child(2)').val();
											var priority 	= $(this).find('textarea:nth-child(3)').val(); 
											var conditional 	= $(this).find('textarea:nth-child(4)').val(); 
											itemdata = [shortcode,hook,priority,conditional];	
											alldata.push(itemdata);																					
										});
										$('textarea[name="adminz_flatsome\[adminz_flatsome_custom_hook\]"]').val(JSON.stringify(alldata));
									}
								})(jQuery);
							});
						</script>
					</td>
				</tr>
	        </table>
	        <?php submit_button(); ?>
	        <table class="form-table">
	        	<tr valign="top">
	        		<td><h3>Flatsome Css classes cheatsheet</h3></td>
	        		<td>
	        		</td>
	        	</tr>
	        	<?php 
	        	$classcheatsheet = require_once(ADMINZ_DIR.'inc/file/flatsome_css_classes.php');;
	        	foreach ($classcheatsheet as $key => $value) {
	        		?>
	        		<tr valign="top">
		        		<th><?php echo esc_attr($key); ?></th>
		        		<td>
		        			<?php foreach ($value as $classes) {
		        				echo "<p>";
		        				foreach ($classes as $class) {
		        					echo " <code>".esc_attr($class)."</code>";
		        				}
		        				echo "</p>";
		        			} ?>
		        		</td>
		        	</tr>
	        		<?php
	        	}
	        	?>
	        	
	        		        	
	        </table>
        </form>
        <?php
		
	}
	static function get_arr_tax($post_type = 'featured_item'){
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
	    $tax_arr = [];    
	    $tax_arr['search'] = "Type to search";
	    if(!empty($taxonomies) and is_array($taxonomies)){
	        foreach ($taxonomies as $key => $value) {
	            $tax_arr[$key] = $value->label;
	        }
	    }
	    return $tax_arr;
	}
	static function get_arr_meta_key($post_type = 'featured_item'){
		if(isset(self::$get_arr_meta_key[$post_type])){
			return self::$get_arr_meta_key[$post_type];
		}
		$meta_keys = self::adminz_get_all_meta_keys($post_type);
	    $key_arr = [];
	    $array_exclude = [
			'pv_commission_rate',
			'wc_productdata_options',
			'total_sales',
			'tm_meta_cpf',
			'tm_meta',
			'_'
		];
	    if(!empty($meta_keys) and is_array($meta_keys)){
	        foreach ($meta_keys as $value) {
	            if($value and !in_array($value,$array_exclude)){
	                $key_arr[$value] = "[M] ".$value;
	            }            
	        }
	    }
	    self::$get_arr_meta_key[$post_type] = $key_arr;
	    return $key_arr;
	}
	
	// Dành cho trường hợp không cài woocommerce
	static function adminz_get_all_meta_keys($post_type = 'post', $exclude_empty = true, $exclude_hidden = true){
	    if(!is_user_logged_in() and get_transient( __FUNCTION__.$post_type )){
			return get_transient( __FUNCTION__.$post_type );
		}
	    global $wpdb;
	    $query = "
	        SELECT DISTINCT($wpdb->postmeta.meta_key) 
	        FROM $wpdb->posts 
	        LEFT JOIN $wpdb->postmeta 
	        ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
	        WHERE $wpdb->posts.post_type = '%s'
	    ";
	    if($exclude_empty) 
	        $query .= " AND $wpdb->postmeta.meta_key != ''";
	    if($exclude_hidden) 
	        $query .= " AND $wpdb->postmeta.meta_key NOT RegExp '(^[_0-9].+$)' ";        
	    $meta_keys = $wpdb->get_col($wpdb->prepare($query, $post_type));
	    set_transient(__FUNCTION__.$post_type,$meta_keys,DAY_IN_SECONDS );
	    return $meta_keys;
	}
	static function adminz_get_all_meta_values_by_key($meta_key = false){
		if(!is_user_logged_in() and get_transient( __FUNCTION__.$meta_key )){
			return get_transient( __FUNCTION__.$meta_key );
		}
		if(!$meta_key) return;

		global $wpdb;		
		$sql = "SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = %s order by meta_value asc";
		$data = $wpdb->get_results($wpdb->prepare( $sql, $meta_key) , ARRAY_N  );
		$result = [];
		foreach($data as $array){
		    $result[] = $array[0];
		}
		$return = apply_filters('adminz_get_all_meta_values_by_key',$result,$meta_key);
		set_transient(__FUNCTION__.$meta_key,$result,DAY_IN_SECONDS );
		return $result;
	}
	function get_packages(){
		return [
			[
				'name'=>'Choose style',
				'slug'=>'',
				'url' => ''
			],
			[
				'name'=>'Round',
				'slug'=>'pack1',
				'url' => plugin_dir_url(ADMINZ_BASENAME).'assets/css/pack/1.css'
			],
			[
				'name'=>'Grid & border',
				'slug'=>'pack2',
				'url' => plugin_dir_url(ADMINZ_BASENAME).'assets/css/pack/2.css'
			]
			
		];
	}
	
	function register_option_setting() {		
		register_setting( $this->options_group, 'adminz_flatsome' );
	    
    	ADMINZ_Helper_Language::register_pll_string('adminz_flatsome[adminz_add_products_after_portfolio]',self::$slug,false );
 		ADMINZ_Helper_Language::register_pll_string('adminz_flatsome[adminz_add_products_after_portfolio_title]',self::$slug,false );

 		ADMINZ_Helper_Language::register_pll_string('adminz_flatsome[adminz_flatsome_portfolio_product_tax]',self::$slug,false);
	    ADMINZ_Helper_Language::register_pll_string('adminz_flatsome[adminz_flatsome_portfolio_name]',self::$slug,false);
	    ADMINZ_Helper_Language::register_pll_string('adminz_flatsome[adminz_flatsome_portfolio_category]',self::$slug,false);
	    ADMINZ_Helper_Language::register_pll_string('adminz_flatsome[adminz_flatsome_portfolio_tag]',self::$slug,false);
	}	

	function get_blocks_arr(){
		$blocks_arr = [];
		$args       = [ 
			'post_type'      => 'blocks',
			'post_status'    => 'publish',
			'posts_per_page' => -1
		];

		$the_query = new \WP_Query( $args );

		if ( $the_query->have_posts() ) :
			while ( $the_query->have_posts() ) :
				$the_query->the_post();
				$blocks_arr[get_the_ID()] = get_the_title();
			endwhile;
		endif;
		wp_reset_postdata();
		return $blocks_arr;
	}
}