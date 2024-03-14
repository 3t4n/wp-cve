<?php 
namespace Adminz\Admin;

class ADMINZ_Elementor extends Adminz {
	public $options_group = "adminz_elementor";
	public $title = 'Elementor';
	static $slug  = 'adminz_elementor';
	public function register_widgets() {
		$this->add_shortcodes();
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new AdminzElementor\ADMINZ_Carousel() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new AdminzElementor\ADMINZ_Category() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new AdminzElementor\ADMINZ_Posts() );		
	}
	public function __construct() {
		if (!in_array('elementor/elementor.php', apply_filters('active_plugins', get_option('active_plugins')))){ 
			return ;
		}
		add_filter( 'adminz_setting_tab', [$this,'register_tab']);
		add_action( 'adminz_tabs_html',[$this,'tab_html']);
		
		add_action( 'elementor/elements/categories_registered', [$this,'add_elementor_widget_categories'] );
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );		
	}
	function register_tab($tabs) {
 		if(!$this->title) return;
 		$this->title = $this->get_icon_html('elementor').$this->title;
        $tabs[$this::$slug] = array(
            'title' => $this->title,
            'slug' => self::$slug,
            'type' => '1'
        );
        return $tabs;
    }
	function add_elementor_widget_categories($elements_manager){
		$elements_manager->add_category(
			$this->get_adminz_slug(),
			[
				'title' => $this->get_adminz_menu_title(),
				'icon' => 'fa fa-plug',
			],
			1
		);
	}
	function add_shortcodes(){
		$shortcodefiles = glob(ADMINZ_DIR.'shortcodes/elementor*.php');
		if(!empty($shortcodefiles)){
			foreach ($shortcodefiles as $file) {
				require_once $file;
			}
		}
	}
	function register_adminz_tab($tabs){
		$tabs[self::$slug] = array(
			'title'=> $this->title,
			'slug' => self::$slug,
			'html'=> $this->tab_html(),
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
	        		<th><h3>Elementor page builder</h3></th>
	        		<td>Some shortcode from Elementor has been added. Open page builder to show </td>
	        	</tr>	        	
	        </table>	        
        </form>
        <?php
	}
}