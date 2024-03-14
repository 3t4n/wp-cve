<?php 
namespace Adminz\Helper;
use Adminz\Admin\Adminz;

class ADMINZ_Helper_Flatsome_Shortcodes{
	public $shortcode_name;
	public $shortcode_title;
	public $shortcode_type;
	public $shortcode_allow;
	public $shortcode_compile = false;
	public $shortcode_overlay = true;
	public $shortcode_icon = 'icon_box';
	public $shortcode_callback;
	public $shortcode_template = '';
	public $options = [];

	function __construct() {
		
	}

	function general_element(){
		if(!$this->shortcode_name){
			echo __('Missing shortcode name','administrator-z');
			return;
		}

		if(!$this->shortcode_title){
			echo __('Missing shortcode title','administrator-z');
			return;
		}

		if(!$this->shortcode_callback){
			echo __('Missing shortcode callback','administrator-z');
			return;
		}
		add_action('ux_builder_setup', function (){  
		    add_ux_builder_shortcode($this->shortcode_name, array(
		        'name'      => $this->shortcode_title,
		        'type'		=> $this->shortcode_type,
				'compile'   => $this->shortcode_compile,
				'overlay'   => $this->shortcode_overlay,
				'info'      => '{{ label }}',
		        'allow' 	=> $this->shortcode_allow,
		        'category'  => Adminz::get_adminz_menu_title(),
		        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . $this->shortcode_icon . '.svg',
				'template'  => $this->shortcode_template,
		        'options' => $this->options,
		    ));
		});		
		add_shortcode($this->shortcode_name, $this->shortcode_callback);
	}

	function preview_text(){
		if(!is_admin()){
			return;
		}
		return '<span style="padding: 15px; background: #71cedf; border: 2px dashed #000; display: flex; flex-direction: column; color: white; justify-content: center; align-items: center;">Please preview in front-end</span>';
	}


	
}





// EXAMPLEEEEEEEEEEEEEEEE

/*use Adminz\Helper\ADMINZ_Helper_Flatsome_Shortcodes;
$a = new \Adminz\Helper\ADMINZ_Helper_Flatsome_Shortcodes;
$a->shortcode_name = 'bic-title';
$a->shortcode_title = 'Bic Title';
$a->shortcode_callback = [$this,'callback'];
$a->options = [
    'text' => array(
        'type'       => 'textfield',
        'heading'    => 'Text ',
        'default' => 'Text',
    ),
];
$a->general_element();
*/