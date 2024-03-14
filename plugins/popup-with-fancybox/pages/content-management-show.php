<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php if ( ! empty( $_POST ) && ! wp_verify_nonce( $_REQUEST['wp_create_nonce'], 'content-management-show-nonce' ) )  { die('<p>Security check failed.</p>'); } ?>
<?php
// Form submitted, check the data
if (isset($_POST['frm_Popupwfb_display']) && $_POST['frm_Popupwfb_display'] == 'yes')
{
	$did = isset($_GET['did']) ? sanitize_text_field($_GET['did']) : '0';
	if(!is_numeric($did)) { die('<p>Are you sure you want to do this?</p>'); }
	
	$Popupwfb_success = '';
	$Popupwfb_success_msg = FALSE;
	
	// First check if ID exist with requested ID
	$sSql = $wpdb->prepare(
		"SELECT COUNT(*) AS `count` FROM ".Popupwfb_Table."
		WHERE `Popupwfb_id` = %d",
		array($did)
	);
	$result = '0';
	$result = $wpdb->get_var($sSql);
	
	if ($result != '1')
	{
		?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist.', 'popup-with-fancybox'); ?></strong></p></div><?php
	}
	else
	{
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('Popupwfb_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".Popupwfb_Table."`
					WHERE `Popupwfb_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$Popupwfb_success_msg = TRUE;
			$Popupwfb_success = __('Selected record was successfully deleted.', 'popup-with-fancybox');
		}
	}
	
	if ($Popupwfb_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $Popupwfb_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php _e('Popup with fancybox', 'popup-with-fancybox'); ?>
	<a class="add-new-h2" href="<?php echo POPUPWFB_ADMIN_URL; ?>&amp;ac=add"><?php _e('Add New', 'popup-with-fancybox'); ?></a></h2>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".Popupwfb_Table."` order by Popupwfb_id desc";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<form name="frm_Popupwfb_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
			<th scope="col"><?php _e('Id', 'popup-with-fancybox'); ?></th>
			<th scope="col"><?php _e('Title', 'popup-with-fancybox'); ?></th>
            <th scope="col"><?php _e('Width', 'popup-with-fancybox'); ?></th>
			<th scope="col"><?php _e('Timeout', 'popup-with-fancybox'); ?></th>
			<th scope="col"><?php _e('Group', 'popup-with-fancybox'); ?></th>
			<th scope="col"><?php _e('Status', 'popup-with-fancybox'); ?></th>
			<th scope="col"><?php _e('Start Date', 'popup-with-fancybox'); ?></th>
			<th scope="col"><?php _e('Expiration', 'popup-with-fancybox'); ?></th>
          </tr>
        </thead>
		<tfoot>
          <tr>
			<th scope="col"><?php _e('Id', 'popup-with-fancybox'); ?></th>
			<th scope="col"><?php _e('Title', 'popup-with-fancybox'); ?></th>
            <th scope="col"><?php _e('Width', 'popup-with-fancybox'); ?></th>
			<th scope="col"><?php _e('Timeout', 'popup-with-fancybox'); ?></th>
			<th scope="col"><?php _e('Group', 'popup-with-fancybox'); ?></th>
			<th scope="col"><?php _e('Status', 'popup-with-fancybox'); ?></th>
			<th scope="col"><?php _e('Start Date', 'popup-with-fancybox'); ?></th>
			<th scope="col"><?php _e('Expiration', 'popup-with-fancybox'); ?></th>
          </tr>
        </tfoot>
		<tbody>
			<?php 
			$i = 0;
			if(count($myData) > 0 )
			{
				foreach ($myData as $data)
				{
					?>
					<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
						<td><?php echo $data['Popupwfb_id']; ?></td>
						<td><?php echo stripslashes($data['Popupwfb_title']); ?>
						<div class="row-actions">
						<span class="edit">
						<a title="Edit" href="<?php echo POPUPWFB_ADMIN_URL; ?>&amp;ac=edit&amp;did=<?php echo $data['Popupwfb_id']; ?>"><?php _e('Edit', 'popup-with-fancybox'); ?></a> | </span>
						<span class="trash">
						<a onClick="javascript:_Popupwfb_delete('<?php echo $data['Popupwfb_id']; ?>')" href="javascript:void(0);"><?php _e('Delete', 'popup-with-fancybox'); ?></a></span> 
						</div>
						</td>
						<td><?php echo $data['Popupwfb_width']; ?></td>
						<td><?php echo $data['Popupwfb_timeout']; ?></td>
						<td><?php echo $data['Popupwfb_group']; ?></td>
						<td><?php echo $data['Popupwfb_status']; ?></td>
						<td><?php echo substr($data['Popupwfb_starttime'],0,10); ?></td>
						<td><?php echo substr($data['Popupwfb_expiration'],0,10); ?></td>
					</tr>
					<?php 
					$i = $i+1; 
				} 	
			}
			else
			{
				?><tr><td colspan="8" align="center"><?php _e('No records available.', 'popup-with-fancybox'); ?></td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('Popupwfb_form_show'); ?>
		<input type="hidden" name="frm_Popupwfb_display" value="yes"/>
		<input type="hidden" name="wp_create_nonce" id="wp_create_nonce" value="<?php echo wp_create_nonce( 'content-management-show-nonce' ); ?>"/>
      </form>	
	<div class="tablenav bottom">
		<a href="<?php echo POPUPWFB_ADMIN_URL; ?>&amp;ac=add"><input class="button action" type="button" value="<?php _e('Add New', 'popup-with-fancybox'); ?>" /></a>
		<a href="<?php echo POPUPWFB_ADMIN_URL; ?>&amp;ac=set"><input class="button action" type="button" value="<?php _e('Popup Setting', 'popup-with-fancybox'); ?>" /></a>
		<a target="_blank" href="<?php echo Popupwfb_FAV; ?>"><input class="button action" type="button" value="<?php _e('Help', 'popup-with-fancybox'); ?>" /></a>
		<a target="_blank" href="<?php echo Popupwfb_FAV; ?>"><input class="button button-primary" type="button" value="<?php _e('Short Code', 'popup-with-fancybox'); ?>" /></a>
	</div>
	</div>
</div>