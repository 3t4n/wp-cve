<?php class EModal_Admin_Editor {
	public function __construct()
	{
		// Ultimate MCE Compatibility Check
		$ultmce = get_option('jwl_options_group1');
		$row = isset($ultmce['jwl_styleselect_field_id']) ? intval($ultmce['jwl_styleselect_dropdown']) : 2;
		add_filter("mce_buttons_$row", array($this, 'TinyMCEButtons'), 999);
		add_filter('tiny_mce_before_init', array($this, 'TinyMCEInit'), 999);
	}
	public function TinyMCEButtons($buttons)
	{
		if(!in_array('styleselect', $buttons))
			$buttons[] = 'styleselect';
		return $buttons;
	}
	public function TinyMCEInit($initArray)
	{
		// Add Modal styles to styles dropdown
		$styles = !empty($initArray['style_formats']) && is_array(json_decode($initArray['style_formats'])) ? json_decode($initArray['style_formats']) : array();
		foreach(get_all_modals() as $modal)
		{
			$styles[] = array(
				'title' => "Open Modal - {$modal->name}",
				'inline' => 'span',
				'classes' => "eModal-{$modal->id}"
			);
		}
		$initArray['style_formats'] = json_encode($styles);     
		return $initArray;
	}
}