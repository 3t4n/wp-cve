<?php
class EModal_GForms {
	public function __construct()
	{
		add_action('emodal_preload_modal', array($this,'preload_modals'));
		add_action('emodal_preload_modal', array($this,'check_shortcodes'));
	}
	public function preload_modals($modal)
	{
		if(function_exists('gravity_form_enqueue_scripts'))
		{
			$regex = "/\[gravityform.*id=[\'\"]?([0-9]*)[\'\"]?.*/";
			preg_match_all($regex, $modal->content, $matches);
			foreach($matches[1] as $form_id)
			{
				add_filter("gform_confirmation_anchor_{$form_id}", create_function("","return false;"));
				gravity_form_enqueue_scripts($form_id, true);
			}
		}
		return $modal;
	}
	public function check_shortcodes($modal)
	{
		global $wp_query;
		if(!empty($wp_query->post->ID) && $post_id = $wp_query->post->ID)
		{
			if(function_exists('gravity_form_enqueue_scripts'))
			{
				$regex = "/\[gravityform.*id=[\'\"]?([0-9]*)[\'\"]?.*/";
				preg_match_all($regex, $wp_query->post->post_content, $matches);
				foreach($matches[1] as $form_id)
				{
					add_filter("gform_confirmation_anchor_{$form_id}", create_function("","return false;"));
					//gravity_form_enqueue_scripts($form_id, true);
				}
			}
		}
		return $modal;
	}

}
$Emodal_GForms = new EModal_GForms;