<?php 
namespace Adminz\Admin;
use Adminz\Admin\Adminz as Adminz;

class ADMINZ_ACF extends Adminz {
	public $options_group = "adminz_acf";
	public $title = "ACF";
	protected $tab_icon = "link";
	static $slug = "adminz_acf";
	static $options;
	function __construct() {
		if(!function_exists('get_field')) return;
		$this::$options = get_option('adminz_acf', []);	
		add_action( 'admin_init', [$this, 'register_option_setting']);
		add_filter( 'adminz_setting_tab', [$this,'register_tab']);
		add_action( 'adminz_tabs_html',[$this,'tab_html']);
		
		$this->general_theme_setting();

		$hide_menu_custom_fields = $this->get_option_value('hide_menu_custom_fields');
		if($hide_menu_custom_fields){
			add_filter('acf/settings/show_admin', '__return_false');
		}

		$this->add_hook();
		
	}

	function add_hook(){
		if(isset($_GET['testfield'])){
			$post_id = sanitize_title($_GET['testfield']);
			if($fields = get_fields($post_id)){
				echo "<pre>";print_r($fields);echo "</pre>";
			}else{
				echo 'No fields';
			}

		    die;

		}
	}

	function general_theme_setting(){
		add_action('acf/init',function(){
			// Add parent.
			if(!function_exists('acf_add_options_page')) return;
	        $parent = acf_add_options_page(array(
	            'page_title'  => apply_filters( 'adminz_theme_general_settings_title', 'Theme Settings' ),
	            'menu_title'  => apply_filters( 'adminz_theme_general_settings_title', 'Theme Settings' ),
	            'menu_slug' 	=> apply_filters( 'adminz_theme_general_settings_slug', 'theme-general-settings' ),
	            'capability'	=> apply_filters('adminz_theme_general_settings_capability','manage_options'),
	            'redirect'    => false,
	        ));
	        // add children.
	        do_action('adminz_after_create_general_settings',$parent);
		},10);
	}
	
	function register_option_setting() {        
        register_setting($this->options_group, self::$slug);
    }
    function register_tab($tabs) {
 		if(!$this->title) return;
 		if($this->tab_icon){ 			
 			$this->title = $this->get_icon_html($this->tab_icon).$this->title;
		}
        $tabs[self::$slug] = array(
            'title' => $this->title,
            'slug' => self::$slug,
            'type'=> '1'
        );
        return $tabs;
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
	        		<th><h3>ACF Pro</h3></th>
	        		<td></td>
	        	</tr>
	        	<tr valign="top">
	        		<th> <?php echo __("Hide menu Custom Fields","administrator-z"); ?> </th>
	        		<td>
                        <input type="checkbox" <?php if($this->check_option('hide_menu_custom_fields',false,"on")) echo 'checked'; ?> name="adminz_acf[hide_menu_custom_fields]" />
                    </td>
	        	</tr>
        	</table>
        	<?php submit_button(); ?>
        </form>
        <?php
	}
}