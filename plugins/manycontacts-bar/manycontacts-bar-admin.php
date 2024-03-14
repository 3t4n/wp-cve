<?php
	if ( $_POST['manycontacts_bar_opt_hidden'] == 'Y' ) {
		$code = $_POST['manycontacts_code'];
		update_option('manycontacts_code', sanitize_data($code));
		
		?>	
		<div class="updated"><p><strong><?php _e('Successfully saved.', 'manycontacts-bar'); ?></strong></p></div>
		<?php
	} else {
		$code = get_option('manycontacts_code');
	}
?>

<div class="wrap">
<?php echo "<h2>" . __( 'ManyContacts Widget for Wordpress - Give superpowers to your WhatsApp', 'manycontacts-bar') . "</h2>"; ?>

<form name="manycontacts_bar_form" method="post" action="<?php echo admin_url('options-general.php').'?page='.$_GET['page']; ?>">
	<input type="hidden" name="manycontacts_bar_opt_hidden" value="Y">
	<div class="metabox-holder">
		<div class="postbox"> 
			<h3><?php _e('Paste your ManyContacts Widget code here', 'manycontacts-bar'); ?></h3>
			<div style="padding:10px;" class="">
				<p><?php _e('Get your ManyContacts widget code for free at <a href="http://www.manycontacts.com" target="_blank">www.manycontacts.com</a> and make your sales growth!', 'manycontacts-bar'); ?></p>
				<textarea name="manycontacts_code" rows="15" cols="90"><?php echo stripslashes($code); ?></textarea>
			</div>
		</div>
	</div>
	<input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes','manycontacts-bar') ?>" />
</form>
</div>