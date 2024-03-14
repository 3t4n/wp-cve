<?php 
require_once MAXGALLERIA_PLUGIN_DIR . '/maxgallery-options.php';

class MaxGalleriaYoutubeOptions extends MaxGalleryOptions {
	
  public $nonce_save_youtube_defaults = array(
		'action' => 'save_youtube_defaults',
		'name' => 'maxgalleria_save_youtube_defaults'
	);
  
	public function __construct($post_id = 0) {
		parent::__construct($post_id);
    
  }
    
	public $developer_api_key_default = '';
	public $developer_api_key_default_key = 'maxgallery_developer_api_key_default';
	public $developer_api_key_key = 'maxgallery_developer_api_key';
  

  public function get_developer_api_key_default() {
		return get_option($this->developer_api_key_key, $this->developer_api_key_default);
	}

  public function save_options($options = null) {
			$options = $this->get_options();
			parent::save_options($options);
	}
  
  public function get_options() {
		return array(
      $this->developer_api_key_key
  	);
	}
    
  
} 

?>