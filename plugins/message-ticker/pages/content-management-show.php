<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
// Form submitted, check the data
if (isset($_POST['frm_mt_display']) && $_POST['frm_mt_display'] == 'yes')
{
	$did = isset($_GET['did']) ? intval($_GET['did']) : '0';
	if(!is_numeric($did)) { die('<p>Are you sure you want to do this?</p>'); }
	
	$mt_success = '';
	$mt_success_msg = FALSE;
	
	// First check if ID exist with requested ID
	$sSql = $wpdb->prepare(
		"SELECT COUNT(*) AS `count` FROM ".WP_mt_TABLE."
		WHERE `mt_id` = %d",
		array($did)
	);
	$result = '0';
	$result = $wpdb->get_var($sSql);
	
	if ($result != '1')
	{
		?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist.', 'message-ticker'); ?></strong></p></div><?php
	}
	else
	{
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('mt_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".WP_mt_TABLE."`
					WHERE `mt_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$mt_success_msg = TRUE;
			$mt_success = __('Selected record was successfully deleted.', 'message-ticker');
		}
	}
	
	if ($mt_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $mt_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php _e('Message ticker', 'message-ticker'); ?><a class="add-new-h2" href="<?php echo WP_mt_ADMIN_URL; ?>&amp;ac=add"><?php _e('Add New', 'message-ticker'); ?></a></h2>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".WP_mt_TABLE."` order by mt_id";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<form name="frm_mt_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
			<th scope="col"><?php _e('Text', 'message-ticker'); ?></th>
            <th scope="col"><?php _e('Display', 'message-ticker'); ?></th>
			<th scope="col"><?php _e('Display', 'message-ticker'); ?></th>
			<th scope="col"><?php _e('Group', 'message-ticker'); ?></th>
			<th scope="col"><?php _e('Expiration', 'message-ticker'); ?></th>
          </tr>
        </thead>
		<tfoot>
          <tr>
			<th scope="col"><?php _e('Text', 'message-ticker'); ?></th>
            <th scope="col"><?php _e('Display', 'message-ticker'); ?></th>
			<th scope="col"><?php _e('Display', 'message-ticker'); ?></th>
			<th scope="col"><?php _e('Group', 'message-ticker'); ?></th>
			<th scope="col"><?php _e('Expiration', 'message-ticker'); ?></th>
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
						<td><?php echo stripslashes($data['mt_text']); ?>
						<div class="row-actions">
						<span class="edit"><a title="Edit" href="<?php echo WP_mt_ADMIN_URL; ?>&amp;ac=edit&amp;did=<?php echo $data['mt_id']; ?>"><?php _e('Edit', 'message-ticker'); ?></a> | </span>
						<span class="trash"><a onClick="javascript:mt_delete('<?php echo $data['mt_id']; ?>')" href="javascript:void(0);"><?php _e('Delete', 'message-ticker'); ?></a></span> 
						</div>
						</td>
						<td><?php echo stripslashes($data['mt_order']); ?></td>
						<td><?php echo stripslashes($data['mt_status']); ?></td>
						<td><?php echo stripslashes($data['mt_group']); ?></td>
						<td><?php echo substr($data['mt_date'],0,10); ?></td>
					</tr>
					<?php 
					$i = $i+1; 
				} 
			}
			else
			{
				?><tr><td colspan="5" align="center"><?php _e('No records available.', 'message-ticker'); ?></td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('mt_form_show'); ?>
		<input type="hidden" name="frm_mt_display" value="yes"/>
      </form>	
	<div class="tablenav bottom">
	  	<a href="<?php echo WP_mt_ADMIN_URL; ?>&amp;ac=add"><input class="button action" type="button" value="<?php _e('Add New', 'message-ticker'); ?>" /></a>
	  	<a href="<?php echo WP_mt_ADMIN_URL; ?>&amp;ac=set"><input class="button action" type="button" value="<?php _e('Setting', 'message-ticker'); ?>" /></a>
	  	<a target="_blank" href="<?php echo WP_mt_FAV; ?>"><input class="button action" type="button" value="<?php _e('Help', 'message-ticker'); ?>" /></a>
		<a target="_blank" href="<?php echo WP_mt_FAV; ?>"><input class="button button-primary" type="button" value="<?php _e('Short Code', 'message-ticker'); ?>" /></a>
	</div>
	</div>
</div>