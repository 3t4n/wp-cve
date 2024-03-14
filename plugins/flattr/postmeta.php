<?php

class Flattr_PostMeta
{
	public function __construct()
	{
		add_action('admin_menu', array($this, 'add_meta_box'));
		add_action('save_post', array($this, 'save_post'));
	}

	public function save_post($id)
	{
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
		{
			return $id;
		}
	
		if ( !current_user_can('edit_post', $id) )
		{
			return $id;
		}
	
		if ( isset($_POST['flattr_post_language']) && $_POST['flattr_post_language'] != '0' ) {
			add_post_meta($id, '_flattr_post_language', $_POST['flattr_post_language'], true) or update_post_meta($id, '_flattr_post_language', $_POST['flattr_post_language']);
		}
		if ( isset($_POST['flattr_post_category']) && $_POST['flattr_post_category'] != '0' ) {
			add_post_meta($id, '_flattr_post_category', $_POST['flattr_post_category'], true) or update_post_meta($id, '_flattr_post_category', $_POST['flattr_post_category']);
		}
		if ( isset($_POST['flattr_post_hidden']) ) {
			add_post_meta($id, '_flattr_post_hidden',   $_POST['flattr_post_hidden'],   true) or update_post_meta($id, '_flattr_post_hidden',   $_POST['flattr_post_hidden']);
		}
		if ( isset($_POST['flattr_btn_disabled']) ) {
			add_post_meta($id, '_flattr_btn_disabled',  $_POST['flattr_btn_disabled'],  true) or update_post_meta($id, '_flattr_btn_disabled',  $_POST['flattr_btn_disabled']);
		}
		if ( isset($_POST['flattr_post_customurl']) ) {
			add_post_meta($id, '_flattr_post_customurl',  $_POST['flattr_post_customurl'],  true) or update_post_meta($id, '_flattr_post_customurl',  $_POST['flattr_post_customurl']);
		}
		
		return true;
	}
	
	public function add_meta_box()
	{
		if ( function_exists('add_meta_box') )
		{
			add_meta_box('flattr_post_settings', __('Flattr settings'), array($this, 'inner_meta_box'), 'post', 'advanced');
			add_meta_box('flattr_post_settings', __('Flattr settings'), array($this, 'inner_meta_box'), 'page', 'advanced');
		}
		else
		{
			add_action('dbx_post_advanced', array($this, 'old_meta_box'));
			add_action('dbx_page_advanced', array($this, 'old_meta_box'));
		}
	}
	
	public function old_meta_box()
	{
		?>
		<div class="dbx-b-ox-wrapper">
			<fieldset id="flattr_fieldsetid" class="dbx-box">
				<div class="dbx-h-andle-wrapper"><h3 class="dbx-handle">Flattr settings</h3></div>
				<div class="dbx-c-ontent-wrapper">
					<div class="dbx-content">
						<?php $this->inner_meta_box(); ?>
					</div>
				</div>
			</fieldset>
		</div>
		<?php
	}
	
	public function inner_meta_box()
	{
		global $post;
		
		$selectedLanguage = get_post_meta($post->ID, '_flattr_post_language', true);
		if (empty($selectedLanguage))
		{
			$selectedLanguage = false;
		}

		$selectedCategory = get_post_meta($post->ID, '_flattr_post_category', true);
		if (empty($selectedCategory))
		{
			$selectedCategory = false;
		}

		$hidden = get_post_meta($post->ID, '_flattr_post_hidden',	true);
		if ($hidden == '')
		{
			$hidden = get_option('flattr_hide', 0);
		}

		$btnDisabled = get_post_meta($post->ID, '_flattr_btn_disabled',	true);
		if (empty($btnDisabled))
		{
			$btnDisabled = get_option('flattr_disable', 0);
		}

		$customUrl = get_post_meta($post->ID, '_flattr_post_customurl', true);
		
		include('postmeta-template.php');
	}

}
