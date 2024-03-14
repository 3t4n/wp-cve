<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
// Form submitted, check the data
if (isset($_POST['frm_onclickpopup_display']) && $_POST['frm_onclickpopup_display'] == 'yes')
{
	$did = isset($_GET['did']) ? sanitize_text_field($_GET['did']) : '0';
	if(!is_numeric($did)) { die('<p>Are you sure you want to do this?</p>'); }
	
	$onclickpopup_success = '';
	$onclickpopup_success_msg = FALSE;
	
	// First check if ID exist with requested ID
	$sSql = $wpdb->prepare(
		"SELECT COUNT(*) AS `count` FROM ".WP_ONCLICK_PLUGIN."
		WHERE `onclickpopup_id` = %d",
		array($did)
	);
	$result = '0';
	$result = $wpdb->get_var($sSql);
	
	if ($result != '1')
	{
		?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist', 'onclick-popup'); ?></strong></p></div><?php
	}
	else
	{
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('onclickpopup_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".WP_ONCLICK_PLUGIN."`
					WHERE `onclickpopup_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$onclickpopup_success_msg = TRUE;
			$onclickpopup_success = __('Selected record was successfully deleted.', 'onclick-popup');
		}
	}
	
	if ($onclickpopup_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $onclickpopup_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php _e('Onclick Popup', 'onclick-popup'); ?>
	<a class="add-new-h2" href="<?php echo WP_onclickpopup_ADMIN_URL; ?>&amp;ac=add"><?php _e('Add New', 'onclick-popup'); ?></a></h2>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".WP_ONCLICK_PLUGIN."` order by onclickpopup_group, onclickpopup_id";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
	<form name="frm_onclickpopup_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <!--<th class="check-column" scope="row" style="padding: 8px 2px;"><input type="checkbox" /></th>-->
			<th scope="col"><?php _e('Popup Title', 'onclick-popup'); ?></th>
			<th scope="col"><?php _e('Popup content', 'onclick-popup'); ?></th>
            <th scope="col"><?php _e('Group', 'onclick-popup'); ?></th>
			<th scope="col"><?php _e('Expiration', 'onclick-popup'); ?></th>
          </tr>
        </thead>
		<tfoot>
          <tr>
            <!--<th class="check-column" scope="row" style="padding: 8px 2px;"><input type="checkbox" /></th>-->
			<th scope="col"><?php _e('Popup Title', 'onclick-popup'); ?></th>
			<th scope="col"><?php _e('Popup content', 'onclick-popup'); ?></th>
            <th scope="col"><?php _e('Group', 'onclick-popup'); ?></th>
			<th scope="col"><?php _e('Expiration', 'onclick-popup'); ?></th>
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
						<!--<td align="left"><input type="checkbox" value="<?php //echo $data['onclickpopup_id']; ?>" name="onclickpopup_group_item[]"></td>-->
						<td><?php echo $data['onclickpopup_title']; ?>
						<div class="row-actions">
						<span class="edit"><a title="Edit" href="<?php echo WP_onclickpopup_ADMIN_URL; ?>&amp;ac=edit&amp;did=<?php echo $data['onclickpopup_id']; ?>"><?php _e('Edit', 'onclick-popup'); ?></a> | </span>
						<span class="trash"><a onClick="javascript:onclickpopup_delete('<?php echo $data['onclickpopup_id']; ?>')" href="javascript:void(0);"><?php _e('Delete', 'onclick-popup'); ?></a></span> 
						</div>
						</td>
						<td><?php echo substr(stripslashes($data['onclickpopup_content']),0,80); ?>...</td>
						<td><?php echo $data['onclickpopup_group']; ?></td>
						<td><?php echo substr($data['onclickpopup_date'],0,10); ?></td>
					</tr>
					<?php 
					$i = $i+1; 
				} 
			}
			else
			{
				?><tr><td colspan="4" align="center"><?php _e('No records available.', 'onclick-popup'); ?></td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('onclickpopup_form_show'); ?>
		<input type="hidden" name="frm_onclickpopup_display" value="yes"/>
    </form>	
	<div class="tablenav bottom">
		<a href="<?php echo WP_onclickpopup_ADMIN_URL; ?>&amp;ac=add"><input class="button action" type="button" value="<?php _e('Add New', 'onclick-popup'); ?>" /></a>
		<a target="_blank" href="<?php echo WP_onclickpopup_FAV; ?>"><input class="button action" type="button" value="<?php _e('Help', 'onclick-popup'); ?>" /></a>
		<a target="_blank" href="<?php echo WP_onclickpopup_FAV; ?>"><input class="button button-primary" type="button" value="<?php _e('Short Code', 'onclick-popup'); ?>" /></a>
	</div>
	
	</div>
</div>