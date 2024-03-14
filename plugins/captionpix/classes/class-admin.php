<?php
abstract class Captionpix_Admin {
	protected $version;
	protected $path;
	protected $parent_slug;
	protected $slug;
    protected $screen_id;
	protected $plugin;
	protected $utils;
    protected $options;  
	protected $icon;
    protected $tooltips;
    private $tips = array();
    private $messages = array();
    private $is_metabox = false;
    private $metabox_class;
    private $metabox_tab;

	function __construct($plugin, $slug = '') {
		$this->plugin = $plugin;
		$this->icon = $this->plugin->get_icon();
		$this->options = $this->plugin->get_options();
		$this->tooltips = $this->plugin->get_tooltips();
		$this->utils = $this->plugin->get_utils();
		$this->version = $this->plugin->get_version();
		$this->path = $this->plugin->get_path();
		$this->parent_slug = $this->plugin->get_slug();
		$this->slug = empty($slug) ? $this->parent_slug : ( $this->parent_slug.'-'.$slug );
		$this->metabox_class = $this->prefix_class('metabox'); 
		$this->metabox_tab = $this->prefix_action('tab'); 
		$this->init();
		add_action('wp_ajax_'. $this->metabox_tab, array($this,'save_tab'));
	}

 	function news_panel($post,$metabox){	
		$this->plugin->get_news()->display_feeds($this->plugin->get_newsfeeds());
	}

    function make_icon($icon) {
        if (empty($icon)) $icon = $this->icon;
	  return strpos($icon, '<svg') !== FALSE ? $icon : sprintf('<i class="%1$s"></i>', 'dashicons-'==substr($icon,0,10) ? ('dashicons '.$icon) : $icon) ;
	}
	
	abstract function init() ;

	abstract function admin_menu() ;

	abstract function page_content(); 

	abstract function load_page();

    function get_screen_id(){
		return $this->screen_id;
	}

	function get_version() {
		return $this->version;
	}

    function get_path() {
		return $this->path;
	}

    function get_parent_slug() {
		return $this->parent_slug;
	}

    function get_slug() {
		return $this->slug;
	}

 	function get_url() {
		return admin_url('admin.php?page='.$this->get_slug());
	}

    function get_name() {
		return $this->plugin->get_name();
	}

 	function get_code($code='') {
		return $this->utils->get_code($code);
	}
	
	function get_keys() { 
		return array_keys($this->tips);
	}

	function get_tip($label) { 
		return $this->tooltips->tip($label);
	}

	function print_admin_notices() {
		foreach ($this->messages as $message)
         print $message;
	}

	function add_admin_notice($subject, $message, $is_error = false) {
	  $this->messages[] = sprintf('<div class="notice is-dismissible %1$s"><p>%2$s %3$s</p></div>', $is_error ? 'error' : 'updated', $subject, $message);
      add_action( 'admin_notices', array($this, 'print_admin_notices') );  
	}

	function plugin_action_links ( $links, $file ) {
		if ( is_array($links) && ($this->get_path() == $file )) {
			$settings_link = '<a href="' .$this->get_url() . '">Settings</a>';
			array_unshift( $links, $settings_link );
		}
		return $links;
	}

	function set_tooltips($tips) {
		$this->tips = (array)$tips;
		$this->tooltips->init($this->tips);
		$this->add_tooltip_support();
	}
	
	function add_tooltip_support() {
		add_action('admin_enqueue_scripts', array( $this, 'enqueue_tooltip_styles'));
		add_action('admin_enqueue_scripts', array( $this, 'enqueue_color_picker_styles'));
		add_action('admin_enqueue_scripts', array( $this, 'enqueue_color_picker_scripts'));
	}
	
	function register_tooltip_styles() {
		$this->utils->register_tooltip_styles();	
	}	

	function enqueue_tooltip_styles() {
		$this->utils->enqueue_tooltip_styles();
	}	

	function register_admin_styles() {
		wp_register_style($this->get_code('admin'), plugins_url('styles/admin.css',dirname(__FILE__)), array(),$this->get_version());
	}

	function enqueue_admin() {
		$this->enqueue_admin_styles();
		$this->enqueue_metabox_scripts();
		$this->enqueue_postbox_scripts();
		$this->enqueue_news_scripts();
	}

	function enqueue_admin_styles() {
		wp_enqueue_style($this->get_code('admin'));
 	}

	function enqueue_color_picker_styles() {
        wp_enqueue_style('wp-color-picker');
	}

	function enqueue_color_picker_scripts() {
		wp_enqueue_script('underscore');
		wp_enqueue_script('wp-color-picker');
		add_action('admin_print_footer_scripts', array( $this, 'enable_color_picker'));
 	}

   function enqueue_metabox_scripts() {
        $this->is_metabox = true;
        wp_enqueue_style('diy-metabox', plugins_url('styles/metabox.css',dirname(__FILE__)), array(),$this->get_version());
 		wp_enqueue_style($this->get_code('tabs'), plugins_url('styles/tabs.css',dirname(__FILE__)), array(),$this->get_version());
 		wp_enqueue_script($this->get_code('tabs'), plugins_url('scripts/jquery.tabs.js',dirname(__FILE__)), array(),$this->get_version());
    }

	function enqueue_postbox_scripts() {
		wp_enqueue_script('common');
		wp_enqueue_script('wp-lists');
		wp_enqueue_script('postbox');	
		add_action('admin_footer-'.$this->get_screen_id(), array($this, 'toggle_postboxes'));
 	}
 		
    function enqueue_news_scripts() {
        $this->plugin->get_news()->enqueue_scripts();
    }

 	function add_meta_box($code, $title, $callback_func, $callback_params = null, $context = 'normal', $priority = 'core', $post_type = false ) {
		if (empty($post_type)) $post_type = $this->get_screen_id();
		add_meta_box($this->get_code($code), __($title), array($this, $callback_func), $post_type, $context, $priority, $callback_params);
	}

 	function add_postmeta_box( $callback_func, $post_type = false, $context = 'advanced', $priority = 'default' ) {
        if ($this->plugin->is_post_type_enabled($post_type)) {
       	    $callback_params = array( '__block_editor_compatible_meta_box' => true);
    	   add_meta_box($this->get_code('post-settings'), $this->get_name().' Post Settings', array($this, $callback_func), $post_type, $context, $priority, $callback_params);
        }
	}

	function form_field($id, $name, $label, $value, $type, $options = array(), $args = array(), $wrap = false) {
		if (!$label) $label = $id;
		$label_args = (is_array($args) && array_key_exists('label_args', $args)) ? $args['label_args'] : false;
 		return $this->utils->form_field($id, $name, $this->tooltips->tip($label, $label_args), $value, $type, $options, $args, $wrap);
 	}	

	function grouped_form_field($data, $prefix, $group, $fld, $type, $options = array(), $args = array(), $wrap='tr') {
		$id = $group.'_'.$fld;
		$name = $prefix.$group.'['.$fld.']';	
		$value = isset($data[$fld]) ? stripslashes($data[$fld]) : '';
		return $this->form_field($id, $name, false, $value, $type, $options, $args, $wrap);
 	}	

	function meta_form_field($meta, $key, $type, $options=array(), $args=array()) {
		return $this->form_field( $meta[$key]['id'], $meta[$key]['name'], false, 
			$meta[$key]['value'], $type, $options, $args);
 	}	

	function fetch_form_field($fld, $value, $type, $options = array(), $args = array(), $wrap = false) {
 		return $this->form_field($fld, $fld, false, $value, $type, $options, $args, $wrap);
 	}
 	
	function fetch_text_field($fld, $value, $args = array()) {
 		return $this->fetch_form_field($fld, $value, 'text', array(), $args);
	}

   function get_meta_form_data($metakey, $prefix, $values = '' ) {
        $content = false;
        $meta = false;
		if (($post_id = $this->utils->get_post_id())
		&& ($meta = $this->utils->get_post_meta($post_id, $metakey))
		&& is_array($values) 
		&& is_array($meta)) 
            $values = $this->options->validate_options($values, $meta);
	
        if (is_array($values)) {
      $content = array();
		foreach ($values as $key => $val) {
			$content[$key] = array();
			$content[$key]['value'] = $val;
			 $content[$key]['id'] = $prefix.$key;
			$content[$key]['name'] = $metakey. '[' . $key . ']';
		}
        } else {
            if (is_string($values)) {
                $key ='';
                $content = array();
 			    $content[$key] = array();
 			    $content[$key]['value'] = $meta;
		 	$content[$key]['id'] = $prefix;
                $content[$key]['name'] = $metakey;           
            }
        }
		return $content;
	}
	
    function prefix_action($action, $prefix ='') {
        if (empty($prefix)) $prefix = $this->utils->get_prefix();
        if ('_' == substr($prefix,0,1)) $prefix = substr($prefix,1);
        return strtolower( $prefix.$action); 
   }

    function prefix_class($class, $prefix ='') {
        if (empty($prefix)) $prefix = $this->utils->get_prefix();
        if ('_' == substr($prefix,0,1)) $prefix = substr($prefix,1);
        return strtolower( str_replace('_', '-', $prefix).$class); 
	}
	
 	function submit_button($button_text='Save Changes', $name = 'options_update') {	
		return sprintf('<p class="save"><input type="submit" name="%1$s" value="%2$s" class="button-primary" /></p>',  $name, $button_text);
	}
 	
	function save_options($options_class, $settings_name, $trim_option_prefix = false) {
     	$saved = false;
  		$page_options = explode(",", stripslashes($_POST['page_options']));
  		if (is_array($page_options)) {
  			$options = call_user_func( array($options_class, 'get_options'));
  			$updates = false; 
    		foreach ($page_options as $option) {
       			$option = trim($option);
       			$val = array_key_exists($option, $_POST) ? (is_array($_POST[$option]) ? $_POST[$option] : trim(stripslashes($_POST[$option]))) : '';
       			if ($trim_option_prefix) $option = substr($option,$trim_option_prefix); //remove prefix
				$options[$option] = $val;
    		} //end for
   			$saved = call_user_func( array($options_class, 'save_options'), $options) ;
   			if ($saved)  
			 	$this->add_admin_notice($settings_name, ' saved successfully.');
   			else 
			 	$this->add_admin_notice($settings_name, ' have not been changed.', true); 	  
  		} else {
		 		$this->add_admin_notice($settings_name, ' not found', true);		
  		}
  		return $saved;
	}

	function save_postmeta($post_id, $enabler, $metakey, $defaults = array()) {
        if (array_key_exists($enabler, $_POST)) {
            if (isset($_POST[$metakey])) {
                $val = $_POST[$metakey];
                if (is_array($val)) {
                    foreach ($val as $k => $v) if (!is_array($v)) $val[$k] = stripslashes(trim($v));
       				//Delete postmeta if empty array
            		if (!array_filter($val)) {
                		delete_post_meta( $post_id, $metakey);
                		return true;
            		}
                    $vals = @serialize($this->options->validate_options($defaults, $val ));
                } else {
                    $vals = stripslashes(trim(esc_attr($val)));
                }
            } else {
                $vals = false;
            }
            return $this->utils->update_post_meta( $post_id, $metakey, $vals );				    
		}
        return false;
	}

	function disable_checkbox($post_id, $action, $option, $label_format) {
        $key = $this->utils->get_toggle_post_meta_key($action, $option);
        return $this->toggle_checkbox($key, $this->utils->get_post_meta_value($post_id, $key), $action=='disable' ? 'Disable' : 'Enable', $option, $label_format);
    } 

	function visibility_checkbox($post_id, $action, $option, $label_format) {
        $key = $this->utils->get_toggle_post_meta_key($action, $option);
        return $this->toggle_checkbox($key, $this->utils->get_post_meta_value($post_id, $key), $action=='hide' ? 'Do not show' : 'Show', $option, $label_format);
    }  

	function toggle_checkbox($key, $value, $action, $option, $label_format) {
		$checked = $value ?'checked="checked" ':'';		
		$label =  __(sprintf($label_format, $action, ucwords(str_replace('_',' ', $option))));
		return sprintf('<label><input class="valinp" type="checkbox" name="%1$s" id="%1$s" %2$svalue="1" />%3$s</label><br/>', $key, $checked, $label);
    }  

    function fetch_message() {
		if (isset($_REQUEST['message']) && ! empty($_REQUEST['message'])) { 
			$message = urldecode($_REQUEST['message']);
			$_SERVER['REQUEST_URI'] = remove_query_arg(array('message'), $_SERVER['REQUEST_URI']);
			$is_error = (strpos($message,'error') !== FALSE) || (strpos($message,'fail') !== FALSE);
			$this->add_admin_notice('', $message, $is_error);
         	return $message;
		}
		return false;
    } 

	function screen_layout_columns($columns, $screen) {
		if (!defined( 'WP_NETWORK_ADMIN' ) && !defined( 'WP_USER_ADMIN' )) {
			if ($screen == $this->get_screen_id()) {
				$columns[$this->get_screen_id()] = 2;
			}
		}
		return $columns;
	}

	function admin_heading($title = '', $icon = '') {
		if (empty($title)) $title = sprintf('%1$s %2$s', ucwords(str_replace('-',' ',$this->slug)), $this->get_version());
    	return sprintf('<h2 class="title">%2$s<span>%1$s</span></h2>', $title, $this->make_icon($icon));				
	}

	function print_admin_page_start($title, $with_sidebar = false) {
      $class = $with_sidebar ? ' columns-2' : '';
    	printf('<div class="wrap">%1$s<div id="poststuff"><div id="post-body" class="metabox-holder%2$s"><div id="post-body-content">', $title, $class);
	}

	function print_admin_form_start($referer = false, $keys = false, $enctype = false, $preamble = false) {
		$this_url = $_SERVER['REQUEST_URI'];
	 	$enctype = $enctype ? 'enctype="multipart/form-data" ' : '';
		$nonces = $referer ? $this->get_nonces($referer) : '';
		$page_options = '';
		if ($keys) {
			$keys = is_array($keys) ? implode(',', $keys) : $keys;
			$page_options = sprintf('<input type="hidden" name="page_options" value="%1$s" />', $keys);
		}
    	printf('%1$s<form id="diy_options" method="post" %2$saction="%3$s"><div>%4$s%5$s</div>',
         $preamble ? $preamble : '', $enctype, $this_url, $page_options, $nonces);
   } 

	function print_admin_form_with_sidebar_middle() {
	   print '</div><div id="postbox-container-1" class="postbox-container">';
	}

	function print_admin_form_end() {
		print '</form>';
	}

	function print_admin_page_end() {
		print '</div></div><br class="clear"/></div></div>';
	}

   function print_admin_form_with_sidebar($title, $referer = false, $keys = false, $enctype = false, $preamble = false) {
      $this->print_admin_page_start ($title, true);
      $this->print_admin_form_start ($referer, $keys, $enctype, $preamble);
		do_meta_boxes($this->get_screen_id(), 'normal', null); 
		if ($keys) print $this->submit_button();		
		$this->print_admin_form_end();
		do_meta_boxes($this->get_screen_id(), 'advanced', null);
		$this->print_admin_form_with_sidebar_middle();
		do_meta_boxes($this->get_screen_id(), 'side', null); 
		$this->print_admin_page_end();
	} 

   function print_admin_form ($title, $referer = false, $keys = false, $enctype = false, $preamble = false) {
      $this->print_admin_page_start ($title);
      $this->print_admin_form_start ($referer, $keys, $enctype, $preamble);
		do_meta_boxes($this->get_screen_id(), 'normal', null); 
		if ($keys) print $this->submit_button();	
		$this->print_admin_form_end();
		do_meta_boxes($this->get_screen_id(), 'advanced', null); 		
		$this->print_admin_page_end();
	} 

   function is_metabox_active($post_type, $context) {
		return ('advanced' === $context ) && $this->plugin->is_post_type_enabled($post_type) ;
   }

	function tabbed_metabox($container_id, $tabs, $n=0) {
      if (!$tabs || (is_array($tabs) && (count($tabs) == 0))) return;
        $tabselect = sprintf('tabselect%1$s', $n);
        if (isset($_REQUEST[$tabselect]))
            $tab = $_REQUEST[$tabselect];
        else {
            $tab = get_user_option($this->metabox_tab.'_'.$container_id ) ;
            if (!$tab) $tab = 'tab1' ;     
        }
        $t=0;
      $labels = $contents = '';
      foreach ($tabs as $label => $content) {
         $t++;
         $labels .=  sprintf('<li class="tab tab%1$s"><a href="#">%2$s</a></li>', $t, $label);
         $contents .=  sprintf('<div class="tab%1$s"><div class="tab-content">%2$s</div></div>', $t, $content);
      }
        return sprintf('<div class="diy-metabox %1$s"><ul class="metabox-tabs">%2$s</ul><div class="metabox-content">%3$s</div><input type="hidden" class="tabselect" name="%4$s" value="%5$s" />%6$s</div>', 
            $this->metabox_class, $labels, $contents, $tabselect, $tab, $this->get_action_nonce($this->metabox_tab));
    }

 	function get_action_nonce($action) {
		return wp_nonce_field($action, $action.'nonce', false, false );
	}

 	function get_nonces($referer) {
		return wp_nonce_field($referer, '_wpnonce', true, false).
			wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false, false ).
			wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false, false);
	}

    function save_tab() {
        check_ajax_referer( $this->metabox_tab, 'tabnonce');
        $tabselect = isset( $_POST['tabselect'] ) ? $_POST['tabselect'] : 'tab0';
        $box = isset( $_POST['box'] ) ? $_POST['box'] : '';
    	if ( $box != sanitize_key( $box ) ) wp_die( 0 );
        if ( ! $user = wp_get_current_user() ) wp_die( -1 );
        if ( $tabselect ) update_user_option($user->ID, $this->metabox_tab.'_'.$box, $tabselect, true);
        wp_die( 1 );
   }

	function toggle_postboxes() {
		$hook = $this->get_screen_id();
    	print <<< SCRIPT
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready( function($) {
	$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
	postboxes.add_postbox_toggles('{$hook}');
});
//]]>
</script>
SCRIPT;
    }	

    function enable_color_picker() {
        if ($this->is_metabox)
            $this->enable_color_picker_metabox();
        else
            $this->enable_color_picker_widgets();
    }
        
    function enable_color_picker_metabox() {
        $target = sprintf('.%1$s .color-picker', $this->metabox_class); 
	    print <<< SCRIPT
<script>
( function( $ ){
   $( document ).ready( function() { $( '{$target}' ).wpColorPicker(); });
}( jQuery ) );
</script>
SCRIPT;
    }

    function enable_color_picker_widgets() {
	    print <<< SCRIPT
<script>
( function( $ ){
   function initColorPickerWidget( widget ) { widget.find( '.color-picker' ).wpColorPicker( { change: _.throttle( function() {  $(this).trigger( 'change' );}, 3000 ) }); }
   function colorPickerWidgetUpdate( event, widget ) { initColorPickerWidget( widget ); }
   $( document ).on( 'widget-added widget-updated', colorPickerWidgetUpdate );
   $( document ).ready( function() { $( '#widgets-right .widget:has(.color-picker)' ).each( function () { initColorPickerWidget( $( this ) );} ); } );
}( jQuery ) );
</script>
SCRIPT;
    }

}
