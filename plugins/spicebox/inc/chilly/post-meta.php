<?php
add_action('admin_init','chilly_metabox_init');	
	function chilly_metabox_init()
		{
			add_meta_box('chilly_metabox_page', __('Enable slider Banner on the page','spicebox'), 'chilly_meta_banner', 'page', 'normal', 'high');
			add_meta_box('chilly_metabox_page', __('Enable slider Banner on the page','spicebox'), 'chilly_postmeta_banner', 'post', 'normal', 'high');
			add_action('save_post','chilly_meta_data_save');
			add_action('save_post','chilly_meta_postdata_save');
		}


	function chilly_meta_banner()
		{
			global $post ;
			$chilly_banner_chkbx = sanitize_text_field( get_post_meta( get_the_ID(), 'chilly_banner_chkbx', true ));
			if(metadata_exists( 'post', get_the_ID(), 'chilly_banner_chkbx'))
			{
			?>
			<input type="checkbox" name="chilly_banner_chkbx" id="chilly_banner_chkbx" <?php if($chilly_banner_chkbx){echo "checked='checked'";}?> />
			<?php	
			}
			else
			{
			?>
			<input checked type="checkbox" name="chilly_banner_chkbx" id="chilly_banner_chkbx" />
			<?php
			}
			?>

			<?php _e('Click Here To Activate Slider on Individually This Page','spicebox'); ?>
			<?php
		}

		function chilly_postmeta_banner()
		{
			global $post ;
			$chilly_postbanner_chkbx = sanitize_text_field( get_post_meta( get_the_ID(), 'chilly_postbanner_chkbx', true ));
			if(metadata_exists( 'post', get_the_ID(), 'chilly_postbanner_chkbx'))
			{
			?>
			<input type="checkbox" name="chilly_postbanner_chkbx" id="chilly_postbanner_chkbx" <?php if($chilly_postbanner_chkbx){echo "checked='checked'";}?> />
			<?php	
			}
			else
			{
			?>
			<input checked type="checkbox" name="chilly_postbanner_chkbx" id="chilly_postbanner_chkbx" />
			<?php
			}
			?>

			<?php _e('Click Here To Activate Slider on Individually This Post','spicebox'); ?>
			<?php
		}


	function chilly_meta_data_save($post_id) 
	{	 
		if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || (defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']))
	        return;
			
		if ( ! current_user_can( 'edit_page', $post_id ) )
		{   return ;	} 
			
		if(isset( $_POST['post_ID']))
		{ 	
			$post_ID = $_POST['post_ID'];				
			$post_type=get_post_type($post_ID);
			
			if($post_type=='page')
			{	
				update_post_meta($post_ID, 'chilly_banner_chkbx', sanitize_text_field(isset($_POST['chilly_banner_chkbx'])));
								
			}
						
		}				
	}
	function chilly_meta_postdata_save($post_id) 
	{	 
		if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || (defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']))
	        return;
			
		if ( ! current_user_can( 'edit_page', $post_id ) )
		{   return ;	} 
			
		if(isset( $_POST['post_ID']))
		{ 	
			$post_ID = $_POST['post_ID'];				
			$post_type=get_post_type($post_ID);
			
			if($post_type=='post')
			{	
				update_post_meta($post_ID, 'chilly_postbanner_chkbx', sanitize_text_field(isset($_POST['chilly_postbanner_chkbx'])));
								
			}
						
		}				
	}
