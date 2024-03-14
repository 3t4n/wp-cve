<?php 
class EModal_Modals {
	public static $preloaded = false;
	public static $enqueued_modals = array();
	public static $loaded_modals = array();
	public static function factory()
	{

		global $wp_query;
		if(!empty($wp_query->post->ID) && $post_id = $wp_query->post->ID)
		{
			$post_modals = get_post_meta($post_id, EMCORE_SLUG.'_post_modals', true);
			if(is_array($post_modals))
				foreach($post_modals as $modal)
					self::enqueue_modal($modal);
		}
		self::preload_modals();
	}
	public static function enqueue_modal($id)
	{
		if(is_int(intval($id))) self::$enqueued_modals[] = intval($id);
	}
	public static function get_enqueued_modals()
	{
		return apply_filters('emodal_get_enqueued_modal_list', self::$enqueued_modals);
	}
	public static function preload_modals()
	{
		$ids = implode(',', self::get_enqueued_modals());
		foreach(get_all_modals("is_trash != 1 AND (is_sitewide = 1". (!empty($ids) ? " OR id IN ($ids)" : "") .")" ) as $modal)
		{
			self::$loaded_modals[$modal->id] = apply_filters('emodal_load_modal_settings', $modal->as_array());
			do_action('emodal_preload_modal', $modal);
		}
	}
	public static function load_modals()
	{
		foreach(self::get_enqueued_modals() as $id)
		{
			if(!array_key_exists($id, self::$loaded_modals))
			{
				foreach(get_all_modals("is_trash != 1 AND id IN ($ids)") as $modal)
				{
					self::$loaded_modals[$modal->id] = apply_filters('emodal_load_modal_settings', $modal->as_array());
					do_action('emodal_preload_modal', $modal);
					do_action('emodal_load_modal', $modal);
				}
			}
		}
	}
	public static function print_modals()
	{
		self::load_modals();
		foreach(apply_filters('emodal_print_modals', self::$loaded_modals) as $id => $modal)
		{
			$view = new EModal_View_Modal;
			$view->set('modal',$modal);
			$view->render();
		}
	}
}