<?php class EModal_Admin_Postmeta {
	public function __construct()
	{
		add_action('load-post.php', array($this, 'post_meta_boxes_setup'));
		add_action('load-post-new.php', array($this, 'post_meta_boxes_setup'));
	}
	public function post_meta_boxes_setup()
	{
		add_action('add_meta_boxes', array($this, 'post_meta_boxes'));
		add_action('save_post', array($this, 'save_easy_modal_post_modals'), 10, 2);
	}
	public function post_meta_box($object, $box)
	{
		do_action('emodal_post_meta_box', $object, $box);
	}
	public function save_easy_modal_post_modals( $post_id, $post )
	{
		/* Verify the nonce before proceeding. */
		if ( empty($_POST[EMCORE_NONCE]) || !wp_verify_nonce($_POST[EMCORE_NONCE], EMCORE_NONCE) ) return $post_id;
		$post_type = get_post_type_object( $post->post_type );
		/* Verify user has permission */
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) return $post_id;
		//$loginmodal = !empty( $_POST['emplogin_loginmodal']) && is_array($_POST['emplogin_loginmodal']) ? $_POST['emplogin_loginmodal'] : array();
		//$loginmodal = emplogin_get_post_loginmodal_settings();
		$post_modals = (!empty( $_POST['emodal_post_modals']) && is_all_numeric($_POST['emodal_post_modals'])) ? $_POST['emodal_post_modals'] : array() ;
		if(!empty($post_modals))
		{
			update_post_meta($post_id, EMCORE_SLUG.'_post_modals', $post_modals);
		}
		else
		{
			delete_post_meta($post_id, EMCORE_SLUG.'_post_modals');
		}
	}
	public function post_meta_boxes()
	{
		foreach(apply_filters('emodal_post_types', array('post','page')) as $post_types)
		{
			add_meta_box(EMCORE_SLUG, esc_html__(EMCORE_NAME, 'easy-modal' ), array($this, 'post_meta_box'), $post_types);
		}
	}
}