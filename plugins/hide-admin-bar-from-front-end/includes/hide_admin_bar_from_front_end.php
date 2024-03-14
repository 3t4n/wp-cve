<?php
global $wpdb;
ob_start();
//getting all settings
$hsabffe_hide_admin_bar= get_option('hsabffe_hide_admin_bar');

//sanitize all post values
$add_opt_submit= sanitize_text_field( $_POST['add_opt_submit'] );
if($add_opt_submit!='') { 
    
	$hsabffe_hide_admin_bar= sanitize_text_field( $_POST['hsabffe_hide_admin_bar'] );	
	$saved= sanitize_text_field( $_POST['saved'] );


    if(isset($hsabffe_hide_admin_bar) ) {
		update_option('hsabffe_hide_admin_bar', $hsabffe_hide_admin_bar);
    }

	if($saved==true) {
		
		$message='saved';
	} 
}
  
?>
  <?php
        if ( $message == 'saved' ) {
		echo ' <div class="updated"><p><strong>Settings Saved.</strong></p></div>';
		}
   ?>
   
    <div class="wrap">
        <form method="post" id="settingForm" action="">
		<h2><?php _e('Hide/Show Admin Bar Setting','');?></h2>
		<table class="form-table">
		
	
	    <tr valign="top">
			<th scope="row" style="width: 370px;">
				<label for="hsabffe_hide_admin_bar"><?php _e('Hide/Show Admin Bar From Front End','');?></label>
			</th>
			<td>
			<select style="width:250px" name="hsabffe_hide_admin_bar" id="hsabffe_hide_admin_bar">
			<option value='show' <?php if($hsabffe_hide_admin_bar == 'show') { echo "selected='selected'" ; } ?>>Show Admin Bar in Front End
			<option value='hide' <?php if($hsabffe_hide_admin_bar == 'hide') { echo "selected='selected'" ; } ?>>Hide Admin Bar From Front End</option>
			</option>
		   </select>
		   <br />
			</td>
		</tr>
	   
		<tr>
		  <td>
		  <p class="submit">
		<input type="hidden" name="saved"  value="saved"/>
        <input type="submit" name="add_opt_submit" class="button-primary" value="Save Changes" />
		  <?php if(function_exists('wp_nonce_field')) wp_nonce_field('add_opt_submit', 'add_opt_submit'); ?>
        </p></td>
		</tr>
		</table>
		
        
       </form>
      
    </div>

