<?php 
// Direct calls to this file are Forbidden when core files are not present
// Thanks to Ed from ait-pro.com for this  code 
// @since 2.1

if ( !function_exists('add_action') ){
header('Status: 403 Forbidden');
header('HTTP/1.1 403 Forbidden');
exit();
}

if ( !current_user_can('manage_options') ){
header('Status: 403 Forbidden');
header('HTTP/1.1 403 Forbidden');
exit();
}

// 
//
?>

<h2>Include Additional Directory </h2>
<p>You can include the full path to another directory in your Snapshots if you wish. </p>
<p>This option is useful if you have moved your wp-content folder to a location other than the default 


 (i.e. the same folder that WordPress resides in).</p>
<p>Leave  blank if you have a default installation. </p>
<?php

?>
<form name="form3" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name3; ?>" value="Y">
<input type="text" name="<?php echo $data_field_name6; ?>" value="<?php echo $opt_val6; ?>" size="80">
<p><em>No trailing slash please - e.g. <?php echo WP_CONTENT_DIR; ?> </em></p>
   	
<p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Add Directory') ?>" />
  <br />
</p>
</form>
<hr />