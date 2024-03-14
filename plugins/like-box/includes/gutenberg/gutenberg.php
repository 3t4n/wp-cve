<?php
class wpda_like_box_gutenberg{	
	private $plugin_url;
	function __construct($plugin_url){
		$this->plugin_url=$plugin_url;
		$this->hooks_for_gutenberg();
	}
	
	/*############################### Function for the Gutenberg hooks ########################################*/	
	
	private function hooks_for_gutenberg(){
		add_action( 'init', array($this,'guthenberg_init') );
	}

	/*############################### Gutenberg function ########################################*/	
	
	public function guthenberg_init(){
		if ( ! function_exists( 'register_block_type' ) ) {
		// Gutenberg is not active.
		return;
		}
		register_block_type( 'wpdevart-likebox/likebox', array(
			'style' => 'wpda_like_box_gutenberg_css',
			'editor_script' => 'wpda_like_box_gutenberg_js',
		) );
		wp_add_inline_script(
			'wpda_like_box_gutenberg_js',
			sprintf('var wpda_likebox_gutenberg = {other_data: %s};',json_encode($this->other_dates(),JSON_PRETTY_PRINT)),
			'before'
		);
	}

	/*############################### Other Gutenberg data function ########################################*/
	
	private function other_dates(){
		$array=array('icon_src'=>$this->plugin_url."images/facebook_menu_icon.png","content_icon"=>$this->plugin_url."images/facebook_menu_icon.png");
		return $array;
	}	
}

