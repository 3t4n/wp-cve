<?php 
namespace Adminz\Admin;
use Adminz\Admin\Adminz as Adminz;

/**
 * 
 */
class Adminz_Test extends Adminz {
	protected $tab_icon = "link";
	public $title = "Test";
	static $slug = "adminz_test";
	
	function __construct() {
		add_filter( 'adminz_setting_tab', [$this,'register_tab']);
		add_action( 'adminz_tabs_html',[$this,'tab_html']);
		
	}
	function register_tab($tabs) {
 		if(!$this->title) return;
 		
 		if($this->tab_icon){ 			
 			$this->title = $this->get_icon_html($this->tab_icon).$this->title;
		}
        $tabs[self::$slug] = array(
            'title' => $this->title,
            'slug' => self::$slug,
            
            'type'=> ''
        );
        return $tabs;
    }
    function tab_html(){
    	if(!isset($_GET['tab']) or $_GET['tab'] !== self::$slug) return;
    	?>
    	<pre>Test</pre>
    	<?php
	}
}