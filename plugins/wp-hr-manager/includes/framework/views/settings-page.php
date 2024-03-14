<div class="wrap wphr-settings">

	<form method="post" id="mainform" action="" enctype="multipart/form-data">

	    <?php WPHR\HR_MANAGER\Framework\WPHR_Admin_Settings::output();?>

	    <?php
global $current_class;

do_action('wphr_after_admin_settings_' . $current_class->get_id());

$get_section_field_items = $current_class->get_section_field_items();
$submit_btn_status = isset($get_section_field_items['submit_button']) ? $get_section_field_items['submit_button'] : true;
?>
	    <?php
if ($submit_btn_status) {
	?>
	    	<p class="submit">
				<input name="save" class="button-primary" type="submit" value="<?php _e('Save changes', 'wphr');?>" />
		    	<input type="hidden" name="subtab" id="last_tab" />

		    	<?php wp_nonce_field('wphr-settings-nonce');?>
		    </p>
	    <?php }?>

   </form>
</div>
