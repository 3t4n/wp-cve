<?php
/**
Plugin Name: Pages Order
Plugin Tag: page, post, order, hierarchy, hierarchical
Description: <p>With this plugin, you may re-order the order of the pages and the hierarchical order of the pages.</p><p>Moreover, you may add this hierarchy into your page to ease the navigation of viewers into your website</p>
Version: 1.1.3

Framework: SL_Framework
Author: SedLex
Author Email: sedlex@sedlex.fr
Framework Email: sedlex@sedlex.fr
Author URI: http://www.sedlex.fr/
Plugin URI: http://wordpress.org/plugins/pages-order/
License: GPL3
*/

//Including the framework in order to make the plugin work

require_once('core.php') ; 

/** ====================================================================================================================================================
* This class has to be extended from the pluginSedLex class which is defined in the framework
*/
class pages_order extends pluginSedLex {
	

	/** ====================================================================================================================================================
	* Plugin initialization
	* 
	* @return void
	*/
	static $instance = false;

	protected function _init() {
		global $wpdb ; 

		// Name of the plugin (Please modify)
		$this->pluginName = 'Pages Order' ; 
		
		// The structure of the SQL table if needed (for instance, 'id_post mediumint(9) NOT NULL, short_url TEXT DEFAULT '', UNIQUE KEY id_post (id_post)') 
		$this->tableSQL = "" ; 
		// The name of the SQL table (Do no modify except if you know what you do)
		$this->table_name = $wpdb->prefix . "pluginSL_" . get_class() ; 

		//Initilisation of plugin variables if needed (Please modify)

		//Configuration of callbacks, shortcode, ... (Please modify)
		// For instance, see 
		//	- add_shortcode (http://codex.wordpress.org/Function_Reference/add_shortcode)
		//	- add_action 
		//		- http://codex.wordpress.org/Function_Reference/add_action
		//		- http://codex.wordpress.org/Plugin_API/Action_Reference
		//	- add_filter 
		//		- http://codex.wordpress.org/Function_Reference/add_filter
		//		- http://codex.wordpress.org/Plugin_API/Filter_Reference
		// Be aware that the second argument should be of the form of array($this,"the_function")
		// For instance add_action( "the_content",  array($this,"modify_content")) : this function will call the function 'modify_content' when the content of a post is displayed
		
		// add_action( "the_content",  array($this,"modify_content")) ; 
		add_action( 'wp_ajax_savePageHierarchy', array($this,"save_tree") );
		add_action( 'admin_menu', array($this,"admin_menu_local") );
		
		add_shortcode( "page_tree", array($this,"page_tree") );
		add_shortcode( "page_parents", array($this,"page_parents") );

		// Important variables initialisation (Do not modify)
		$this->path = __FILE__ ; 
		$this->pluginID = get_class() ; 
		
		// activation and deactivation functions (Do not modify)
		register_activation_hook(__FILE__, array($this,'install'));
		register_deactivation_hook(__FILE__, array($this,'deactivate'));
		register_uninstall_hook(__FILE__, array('pages_order','uninstall_removedata'));
	}
	
	/** ====================================================================================================================================================
	* In order to uninstall the plugin, few things are to be done ... 
	* (do not modify this function)
	* 
	* @return void
	*/
	
	public function uninstall_removedata () {
		global $wpdb ;
		// DELETE OPTIONS
		delete_option('pages_order'.'_options') ;
		if (is_multisite()) {
			delete_site_option('pages_order'.'_options') ;
		}
		
		// DELETE SQL
		if (function_exists('is_multisite') && is_multisite()){
			$old_blog = $wpdb->blogid;
			$old_prefix = $wpdb->prefix ; 
			// Get all blog ids
			$blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM ".$wpdb->blogs));
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				$wpdb->query("DROP TABLE ".str_replace($old_prefix, $wpdb->prefix, $wpdb->prefix . "pluginSL_" . 'pages_order')) ; 
			}
			switch_to_blog($old_blog);
		} else {
			$wpdb->query("DROP TABLE ".$wpdb->prefix . "pluginSL_" . 'pages_order' ) ; 
		}
		
		// DELETE FILES if needed
		//SLFramework_Utils::rm_rec(WP_CONTENT_DIR."/sedlex/xxxxx/"); 
		$plugins_all = 	get_plugins() ; 
		$nb_SL = 0 ; 	
		foreach($plugins_all as $url => $pa) {
			$info = pluginSedlex::get_plugins_data(WP_PLUGIN_DIR."/".$url);
			if ($info['Framework_Email']=="sedlex@sedlex.fr"){
				$nb_SL++ ; 
			}
		}
		if ($nb_SL==1) {
			SLFramework_Utils::rm_rec(WP_CONTENT_DIR."/sedlex/"); 
		}

	}
	/**====================================================================================================================================================
	* Function called when the plugin is activated
	* For instance, you can do stuff regarding the update of the format of the database if needed
	* If you do not need this function, you may delete it.
	*
	* @return void
	*/
	
	public function _update() {
		SLFramework_Debug::log(get_class(), "Update the plugin." , 4) ; 
	}
	
	/**====================================================================================================================================================
	* Function called to return a number of notification of this plugin
	* This number will be displayed in the admin menu
	*
	* @return int the number of notifications available
	*/
	 
	public function _notify() {
		return 0 ; 
	}
	
	/** ====================================================================================================================================================
	* Init javascript for the admin side
	* If you want to load a script, please type :
	* 	<code>wp_enqueue_script( 'jsapi', 'https://www.google.com/jsapi');</code> or 
	*	<code>wp_enqueue_script('pages_order_script', plugins_url('/script.js', __FILE__));</code>
	*
	* @return void
	*/
	
	function _admin_js_load() {	
		return ; 
	}
	
	/**====================================================================================================================================================
	* Function to instantiate the class and make it a singleton
	* This function is not supposed to be modified or called (the only call is declared at the end of this file)
	*
	* @return void
	*/
	
	public static function getInstance() {
		if ( !self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	/** ====================================================================================================================================================
	* Add a button in the TinyMCE Editor
	*
	* To add a new button, copy the commented lines a plurality of times (and uncomment them)
	* 
	* @return array of buttons
	*/
	
	function add_tinymce_buttons() {
		$buttons = array() ; 
		$buttons[] = array(__('Display pages tree', $this->pluginID), '[page_tree]', '', plugin_dir_url("/").'/'.str_replace(basename( __FILE__),"",plugin_basename( __FILE__)).'img/tree_button.jpg') ; 
		$buttons[] = array(__('Show the parent pages (breadcrumb)', $this->pluginID), '[page_parents]', '', plugin_dir_url("/").'/'.str_replace(basename( __FILE__),"",plugin_basename( __FILE__)).'img/parent_button.jpg') ; 
		return $buttons ; 
	}

	
	/** ====================================================================================================================================================
	* Define the default option values of the plugin
	* This function is called when the $this->get_param function do not find any value fo the given option
	* Please note that the default return value will define the type of input form: if the default return value is a: 
	* 	- string, the input form will be an input text
	*	- integer, the input form will be an input text accepting only integer
	*	- string beggining with a '*', the input form will be a textarea
	* 	- boolean, the input form will be a checkbox 
	* 
	* @param string $option the name of the option
	* @return variant of the option
	*/
	public function get_default_option($option) {
		switch ($option) {
			// Alternative default return values (Please modify)
			case 'other_style' 		: return "font-weight:normal;color:#DDDDDD;" 		; break ; 
			case 'parent_style' 		: return "font-weight:bold;color:#333333;" 		; break ; 
			case 'current_style' 		: return "font-weight:bold;color:#DD3333;" 		; break ; 
			case 'child_style' 		: return "font-weight:normal;color:#333333;" 		; break ; 
			case 'breadcrumb_all' 		: return "border:1px #DDDDDD solid;margin:10px;padding:10px;background-color:#EEEEEE;" 		; break ; 
			case 'breadcrumb_item' 		: return "font-weight:normal; color:#333333;" 		; break ; 
			
			case 'show_order_in_page_edit' : return true ; 
		}
		return null ;
	}

	/** ====================================================================================================================================================
	* Add menu
	*
	* @return void
	*/
	
	public function admin_menu_local() {
		if ($this->get_param('show_order_in_page_edit')) {
			add_pages_page( __('Order pages', 'SL_framework'), __('Order', 'SL_framework'), 'edit_pages', 'edit_pages-order/pages-order', array($this,'pages_order_edit'));
		}
	}
	
	/** ====================================================================================================================================================
	* Add menu
	*
	* @return void
	*/
	
	public function pages_order_edit() {
			$tabs = new SLFramework_Tabs() ; 
		
		ob_start() ; 
			echo "<p>".__("In this tab, you could re-order the page hierarchy by 'drag-and-dropping' page entries.", $this->pluginID)."</p>" ; 
		
			$args = array(
				'sort_order' => 'ASC',
				'sort_column' => 'menu_order,post_title',
				'parent' => 0,
				'child_of' => 0,
				'offset' => 0,
				'post_type' => 'page',
				'post_status' => 'publish,draft,pending,future'
			);
			
			SLFramework_Treelist::render($this->create_hierarchy_pages(get_pages($args)), true, 'savePageHierarchy', 'page_hiera');
					
		$tabs->add_tab(__('Order Pages',  $this->pluginID), ob_get_clean()) ; 	

		// HOW To
		ob_start() ;
			echo "<p>".__('With this plugin you may order your pages into hierarchical tree and display the tree in them.', $this->pluginID)."</p>" ; 
			echo "<p>".sprintf(__("To display the tree please add %s in your page.", $this->pluginID), "<code>[page_tree]</code>")."</p>" ; 
		$howto1 = new SLFramework_Box (__("Purpose of that plugin", $this->pluginID), ob_get_clean()) ; 
		ob_start() ;
			echo "<p>".__('Just drag and drop each entry.', $this->pluginID)."</p>" ; 
		$howto2 = new SLFramework_Box (__("How to order the page", $this->pluginID), ob_get_clean()) ; 
		ob_start() ;
			 echo $howto1->flush() ; 
			 echo $howto2->flush() ; 
		$tabs->add_tab(__('How To',  $this->pluginID), ob_get_clean() , plugin_dir_url("/").'/'.str_replace(basename(__FILE__),"",plugin_basename(__FILE__))."core/img/tab_how.png") ; 	


		echo $tabs->flush() ; 	
	}
	
	/** ====================================================================================================================================================
	* The admin configuration page
	* This function will be called when you select the plugin in the admin backend 
	*
	* @return void
	*/
	
	public function configuration_page() {
		global $wpdb;
		global $blog_id ; 
		
		SLFramework_Debug::log(get_class(), "Print the configuration page." , 4) ; 

		?>
		<div class="plugin-titleSL">
			<h2><?php echo $this->pluginName ?></h2>
		</div>
		
		<div class="plugin-contentSL">		
			<?php echo $this->signature ; ?>

			<?php
			//===============================================================================================
			// After this comment, you may modify whatever you want
			
			// We check rights
			$this->check_folder_rights( array(array(WP_CONTENT_DIR."/sedlex/test/", "rwx")) ) ;
			
			$tabs = new SLFramework_Tabs() ; 
			
			ob_start() ; 
				echo "<p>".__("In this tab, you could re-order the page hierarchy by 'drag-and-dropping' page entries.", $this->pluginID)."</p>" ; 
			
				$args = array(
					'sort_order' => 'ASC',
					'sort_column' => 'menu_order,post_title',
					'parent' => 0,
					'child_of' => 0,
					'offset' => 0,
					'post_type' => 'page',
					'post_status' => 'publish,draft,pending,future'
				);
				
				SLFramework_Treelist::render($this->create_hierarchy_pages(get_pages($args)), true, 'savePageHierarchy', 'page_hiera');
						
			$tabs->add_tab(__('Order Pages',  $this->pluginID), ob_get_clean()) ; 
			
			
			// HOW To
			ob_start() ;
				echo "<p>".__('With this plugin you may order your pages into hierarchical tree and display the tree in them.', $this->pluginID)."</p>" ; 
				echo "<p>".sprintf(__("To display the tree please add %s in your page.", $this->pluginID), "<code>[page_tree]</code>")."</p>" ; 
			$howto1 = new SLFramework_Box (__("Purpose of that plugin", $this->pluginID), ob_get_clean()) ; 
			ob_start() ;
				echo "<p>".__('Just drag and drop each entry.', $this->pluginID)."</p>" ; 
			$howto2 = new SLFramework_Box (__("How to order the page", $this->pluginID), ob_get_clean()) ; 
			ob_start() ;
				 echo $howto1->flush() ; 
				 echo $howto2->flush() ; 
			$tabs->add_tab(__('How To',  $this->pluginID), ob_get_clean() , plugin_dir_url("/").'/'.str_replace(basename(__FILE__),"",plugin_basename(__FILE__))."core/img/tab_how.png") ; 	
		

			ob_start() ; 
				$params = new SLFramework_Parameters($this, "tab-parameters") ; 
				
				$params->add_title(__("Tree view style (i.e. [page_tree] shortcode)", $this->pluginID)) ; 
				$params->add_param('current_style', __("Set the style of current page in tree:", $this->pluginID)) ; 
				$params->add_param('parent_style', __("Set the style of parent pages in tree:", $this->pluginID)) ; 
				$params->add_param('child_style', __("Set the style of child pages in tree:", $this->pluginID)) ; 
				$params->add_param('other_style', __("Set the style of other pages in tree:", $this->pluginID)) ; 

				$params->add_title(__("Breadcrumb style (i.e. [page_parents] shortcode)", $this->pluginID)) ; 
				$params->add_param('breadcrumb_all', __("Set the style of the breadcrumb:", $this->pluginID)) ; 
				$params->add_param('breadcrumb_item', __("Set the style of items of the breadcrumb:", $this->pluginID)) ; 
				
				$params->add_title(__("Order for editor", $this->pluginID)) ; 
				$params->add_param('show_order_in_page_edit', __("Show the order page for the editors users (menu under the page menu):", $this->pluginID)) ; 
				
				$params->flush() ; 
				
			$tabs->add_tab(__('Parameters',  $this->pluginID), ob_get_clean() , plugin_dir_url("/").'/'.str_replace(basename(__FILE__),"",plugin_basename(__FILE__))."core/img/tab_param.png") ; 	
			
			$frmk = new coreSLframework() ;  
			if (((is_multisite())&&($blog_id == 1))||(!is_multisite())||($frmk->get_param('global_allow_translation_by_blogs'))) {
				ob_start() ; 
					$plugin = str_replace("/","",str_replace(basename(__FILE__),"",plugin_basename( __FILE__))) ; 
					$trans = new SLFramework_Translation($this->pluginID, $plugin) ; 
					$trans->enable_translation() ; 
				$tabs->add_tab(__('Manage translations',  $this->pluginID), ob_get_clean() , plugin_dir_url("/").'/'.str_replace(basename(__FILE__),"",plugin_basename(__FILE__))."core/img/tab_trad.png") ; 	
			}

			ob_start() ; 
				$plugin = str_replace("/","",str_replace(basename(__FILE__),"",plugin_basename( __FILE__))) ; 
				$trans = new SLFramework_Feedback($plugin, $this->pluginID) ; 
				$trans->enable_feedback() ; 
			$tabs->add_tab(__('Give feedback',  $this->pluginID), ob_get_clean() , plugin_dir_url("/").'/'.str_replace(basename(__FILE__),"",plugin_basename(__FILE__))."core/img/tab_mail.png") ; 	
			
			ob_start() ; 
				// A liste of plugin slug to be excluded
				$exlude = array('wp-pirate-search') ; 
				// Replace sedLex by your own author name
				$trans = new SLFramework_OtherPlugins("sedLex", $exlude) ; 
				$trans->list_plugins() ; 
			$tabs->add_tab(__('Other plugins',  $this->pluginID), ob_get_clean() , plugin_dir_url("/").'/'.str_replace(basename(__FILE__),"",plugin_basename(__FILE__))."core/img/tab_plug.png") ; 	
			
			echo $tabs->flush() ; 
			
			
			// Before this comment, you may modify whatever you want
			//===============================================================================================
			?>
			<?php echo $this->signature ; ?>
		</div>
		<?php
	}

	
	/** ====================================================================================================================================================
	* Display the pages as a list
	*
	* @return array
	*/

	function create_hierarchy_pages($array, $id_to_show=0, $display=true) {
		global $post ; 
		
		$result = array() ; 
		
		foreach ( $array as $a ) {
			if ($id_to_show==0){
				$text = $this->get_text($a) ; 
			} else {
				if ($a->ID == $id_to_show) {
					if (!is_page()) {
						$text = $this->get_text($a, $this->get_param('current_style')) ; 
					} else {
						$text = $this->get_text_standard($a, $this->get_param('current_style')) ; 
					}
				} else if ($this->is_child(get_post($a->ID), $id_to_show)) {
					if (!is_page()) {
						$text = $this->get_text($a, $this->get_param('parent_style')) ; 
					} else {
						$text = $this->get_text_standard($a, $this->get_param('parent_style')) ; 
					}
				} else if ($this->is_parent(get_post($a->ID), $id_to_show)) {
					if (!is_page()) {
						$text = $this->get_text($a, $this->get_param('child_style')) ;
					} else {
						$text = $this->get_text_standard($a, $this->get_param('child_style')) ;
					}
				} else {
					
					if (!is_page()) {
						$text = $this->get_text($a, $this->get_param('other_style')) ; 
					} else {
						$text = $this->get_text_standard($a, $this->get_param('other_style')) ; 
					}
				}
			}

			// We recurse !
			$args = array(
				'sort_order' => 'ASC',
				'sort_column' => 'menu_order,post_title',
				'parent' => $a->ID,
				'child_of' => $a->ID,
				'offset' => 0,
				'post_type' => 'page',
			);
			// Check if the user may edit page
			if ( current_user_can('edit_published_pages') ) { 
				$args['post_status'] = 'publish,draft,pending,future' ; 
			} else {
				$args['post_status'] = 'publish' ; 
			}
			
			$child = get_pages($args) ; 
			if (count($child)!=0) {
				if (($id_to_show==0)||($display==false)) {
					$r = array($text,'page_'. $a->ID, $this->create_hierarchy_pages($child, $id_to_show, $display), $display) ; 
				} else {
					if (($this->is_parent(get_post($a->ID), $id_to_show)) || ($this->is_child(get_post($a->ID), $id_to_show)) ){
						$r = array($text,'page_'. $a->ID, $this->create_hierarchy_pages($child, $id_to_show, true), true) ; 
					} else {
						$r = array($text,'page_'. $a->ID, $this->create_hierarchy_pages($child, $id_to_show, false), false) ; 
					}
				}
			} else {
				$r = array($text,'page_'. $a->ID, null, $display) ; 
			}
			$result[] = $r ; 
		}
		return $result ; 
	}
	
	/** ====================================================================================================================================================
	* Get Text for the list given a post
	*
	* @return string	*/

	function get_text($a, $style="") {
		$text = "" ; 
		if ( current_user_can('edit_published_pages') ) { 
			if ($a->post_status=="publish") {
				$text .= '<span class="page_status page_published">'.__('Published', $this->pluginID).'</span>' ; 
			}
			if ($a->post_status=="draft") {
				$text .= '<span class="page_status page_draft">'.__('Draft', $this->pluginID).'</span>' ; 
			}
			if ($a->post_status=="pending") {
				$text .= '<span class="page_status page_pending">'.__('Pending', $this->pluginID).'</span>' ; 
			}
			if ($a->post_status=="future") {
				$text .= '<span class="page_status page_future">'.__('Future', $this->pluginID).'</span>' ; 
			}
		}
		
		$text .= "" ; 
		
		$text .= '<span style="'.$style.'">'.$a->post_title.'</span>' ;
	 
		// Print actions
		$text .= ' <span class="page_actions">(' ;
		$text .= '<a class="page_action_editorview" href="'.get_permalink( $a->ID ).'">'.__( 'View' , $this->pluginID).'</a>';

		// has capabilities to edit this page?
		if ( $edit = get_edit_post_link( $a->ID ) )
			$text .= ' | <a class="page_action_editorview" href="'.$edit.'">'.__( 'Edit' , $this->pluginID).'</a>';
		// has capabilities to delete this page?
		if ( $delete = get_delete_post_link( $a->ID ) )
			$text .= ' | <a class="page_action_delete" href="'.$delete.'">'.__( 'Trash', $this->pluginID).'</a>';

		$text .= ')</span>' ;
		return $text ; 
	}
	
	/** ====================================================================================================================================================
	* Get Text for the list given a post
	*
	* @return string	*/

	function get_text_standard($a, $style="") {
		$text = "" ; 
				
		$text .= '<span style="'.$style.'"><a href="'.get_permalink( $a->ID ).'" style="'.$style.'">'.$a->post_title.'</a></span>' ;
	 
		if ( current_user_can('edit_published_pages') ) { 
			// Print actions
			$text .= ' <span class="page_actions">(' ;
			// has capabilities to edit this page?
			if ( $edit = get_edit_post_link( $a->ID ) )
				$text .= '<a class="page_action_editorview" href="'.$edit.'">'.__( 'Edit' , $this->pluginID).'</a>';
			// has capabilities to delete this page?
			if ( $delete = get_delete_post_link( $a->ID ) )
				$text .= ' | <a class="page_action_delete" href="'.$delete.'">'.__( 'Trash', $this->pluginID).'</a>';

			$text .= ')</span>' ;
		}
		
		return $text ; 
	}
	
	/** ====================================================================================================================================================
	* Callback for saving the hierarchy of pages
	*
	* @return void
	*/
	
	function save_tree() {
		$array = $_POST['result'] ; 
		$this->save_tree_recurse($array, 0) ; 
		echo "OK" ; 
		die() ; 
	}
	
	function save_tree_recurse($array, $parent_id) {
		$order = 1 ; 
		foreach ($array as $a) {
			$id_page = str_replace('page_', '', $a[0]) ; 
			$old_page = get_post($id_page) ; 
			if (($old_page->post_parent != $parent_id)||($old_page->menu_order!=$order)) {
  				$my_post = array();
 				$my_post['ID'] = $id_page;
  				$my_post['post_parent'] = $parent_id;
  				$my_post['menu_order'] = $order;
				// Update the post into the database
  				if (wp_update_post( $my_post )==0) {
  					echo "Error when saving ".$id_page." as child of ".$parent_id." with menu order ".$order."\n" ;
  				}
			}
			$child = $a[1] ; 
			if (is_array($child)) {
				$this->save_tree_recurse($child, $id_page) ; 
			}
			$order++ ; 
		}
	}
	
	/** ====================================================================================================================================================
	* Check whether the post_id is a parent of the post
	* 
	* @return boolean true if the post_id os a parent page of post
	*/	
	
	function is_parent( $post, $post_id ) {
		if (is_page() && ($post->ID == $post_id)) {
			return true;
		} else if ($post->post_parent == 0) {
			return false;
		} else {
			return $this->is_parent( get_post($post->post_parent), $post_id );
		}
	}
	
	/** ====================================================================================================================================================
	* Get the root of the page hierachy
	* 
	* @return the ID of the root page
	*/	
	
	function get_root( $post_id ) {
		$post = get_post($post_id) ; 
		$parent = $post->post_parent ; 

		if ($parent == 0) {
			return $post->ID;
		} else {
			return $this->get_root($parent);
		}
	}
	
	/** ====================================================================================================================================================
	* Check whether the post_id is a child of the post
	* 
	* @return boolean true if the post_id os a child page of post
	*/	
	
	function is_child($post, $post_id) { 
		if ($post->ID==$post_id) {
		       return true;
		} else { 
			$return = false ; 
			
			// We recurse !
			$args = array(
				'sort_order' => 'ASC',
				'sort_column' => 'menu_order,post_title',
				'parent' => $post->ID,
				'child_of' => $post->ID,
				'offset' => 0,
				'post_type' => 'page',
			);
			
			// Check if the user may edit page
			if ( current_user_can('edit_published_pages') ) { 
				$args['post_status'] = 'publish,draft,pending,future' ; 
			} else {
				$args['post_status'] = 'publish' ; 
			}
			
			$child = get_pages($args) ; 
			if (count($child)!=0) {
				foreach ( $child as $c ) {
					$return = ( $return || $this->is_child($c, $post_id) ); 
				}
			} 
		       return $return; 
		}
	}
	/** ====================================================================================================================================================
	* Call when meet the shortcode "[page_tree]" in an post/page
	* 
	* @return string the replacement string
	*/	
	
	function page_tree($attribs) {	
		global $post ; 
		// We check that we are in a page and not in a post
		if (!is_page())
			return "" ; 
		ob_start() ;
			$args = array(	
				'sort_order' => 'ASC',
				'sort_column' => 'menu_order,post_title',
				'parent' => $this->get_root($post->ID),
				'child_of' => $this->get_root($post->ID),
				'offset' => 0,
				'post_type' => 'page'
			);
			// Check if the user may edit page
			if ( current_user_can('edit_published_pages') ) { 
				$args['post_status'] = 'publish,draft,pending,future' ; 
			} else {
				$args['post_status'] = 'publish' ; 
			}
			
			$children = $this->create_hierarchy_pages(get_pages($args), $post->ID) ; 
			
			$id_to_show = $post->ID ; 
			$a = get_post($this->get_root($post->ID)) ; 
			
			if ($a->ID == $id_to_show) {
				$text = $this->get_text_standard($a, $this->get_param('current_style')) ; 
			} else if ($this->is_child(get_post($a->ID), $id_to_show)) {
				$text = $this->get_text_standard($a, $this->get_param('parent_style')) ; 
			} else if ($this->is_parent(get_post($a->ID), $id_to_show)) {
				$text = $this->get_text_standard($a, $this->get_param('child_style')) ; 
			} else {
				$text = $this->get_text_standard($a, $this->get_param('other_style')) ; 
			}

			$to_show = array(array($text,'page_'. $post->ID, $children, true)) ; 
			
			SLFramework_Treelist::render($to_show, true, null, 'page_hiera');
		$out = ob_get_clean() ; 
		return "<div style='margin:10px;padding:10px;'>".$out."</div>" ;
	}
	
	/** ====================================================================================================================================================
	* Call when meet the shortcode "[page_parents]" in an post/page
	* 
	* @return string the replacement string
	*/	
	
	function page_parents($attribs) {	
		global $post ; 
		$out = "" ; 
		
		// We check that we are in a page and not in a post
		if (!is_page())
			return "" ; 
		ob_start() ;
		
		
		$parent_id = $post->post_parent ; 
		$parents = array();
		while ($parent_id) {
			$page = get_post($parent_id);
			$parents[]  = '<a href="'.get_permalink($page->ID).'" title="'.get_the_title($page->ID).'">'.get_the_title($page->ID).'</a>';
			$parent_id  = $page->post_parent;
		}
	
		// Parents are in reverse order.
		$parents = array_reverse($parents);
			foreach ($parents as $p) {
				$out .= " > <span style='".$this->get_param('breadcrumb_item')."'>".$p."</span>" ; 
			}

		$out .= ob_get_clean() ; 
		return "<div style='".$this->get_param('breadcrumb_all')."'>".$out."</div>" ;
	}
}

$pages_order = pages_order::getInstance();

?>