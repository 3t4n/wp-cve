<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
// Form submitted, check the data
if (isset($_POST['frm_ctsop_display']) && $_POST['frm_ctsop_display'] == 'yes')
{
	$did = isset($_GET['did']) ? sanitize_text_field($_GET['did']) : '0';
	if(!is_numeric($did)) { die('<p>Are you sure you want to do this?</p>'); }
	
	$ctsop_success = '';
	$ctsop_success_msg = FALSE;
	
	// First check if ID exist with requested ID
	$sSql = $wpdb->prepare(
		"SELECT COUNT(*) AS `count` FROM ".WP_ctsop_TABLE."
		WHERE `ctsop_id` = %d",
		array($did)
	);

	$result = '0';
	$result = $wpdb->get_var($sSql);
	
	if ($result != '1')
	{
		?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist', 'content-text-slider-on-post'); ?></strong></p></div><?php
	}
	else
	{
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('ctsop_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".WP_ctsop_TABLE."`
					WHERE `ctsop_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$ctsop_success_msg = TRUE;
			$ctsop_success = __('Selected record was successfully deleted.', 'content-text-slider-on-post');
		}
	}
	
	if ($ctsop_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $ctsop_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php _e('Content text slider on post', 'content-text-slider-on-post'); ?>
	<a class="add-new-h2" href="<?php echo WP_ctsop_ADMIN_URL; ?>&amp;ac=add"><?php _e('Add New', 'content-text-slider-on-post'); ?></a></h2>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".WP_ctsop_TABLE."` order by ctsop_id desc";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<form name="frm_ctsop_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
			<th scope="col"><?php _e('Title', 'content-text-slider-on-post'); ?></th>
            <th scope="col"><?php _e('Message', 'content-text-slider-on-post'); ?></th>
			<th scope="col"><?php _e('Group', 'content-text-slider-on-post'); ?></th>
          </tr>
        </thead>
		<tfoot>
          <tr>
			<th scope="col"><?php _e('Title', 'content-text-slider-on-post'); ?></th>
            <th scope="col"><?php _e('Message', 'content-text-slider-on-post'); ?></th>
			<th scope="col"><?php _e('Group', 'content-text-slider-on-post'); ?></th>
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
					<td><?php echo stripslashes($data['ctsop_title']); ?>
					<div class="row-actions">
					<span class="edit"><a title="Edit" href="<?php echo WP_ctsop_ADMIN_URL; ?>&amp;ac=edit&amp;did=<?php echo $data['ctsop_id']; ?>"><?php _e('Edit', 'content-text-slider-on-post'); ?></a> | </span>
					<span class="trash"><a onClick="javascript:ctsop_delete('<?php echo $data['ctsop_id']; ?>')" href="javascript:void(0);"><?php _e('Delete', 'content-text-slider-on-post'); ?></a></span> 
					</div>
					</td>
					<td width="70%"><?php echo stripslashes($data['ctsop_text']); ?></td>
					<td><?php echo stripslashes($data['ctsop_group']); ?></td>
					</tr>
					<?php 
					$i = $i+1; 
				} 	
			}
			else
			{
				?><tr><td colspan="3" align="center"><?php _e('No records available.', 'content-text-slider-on-post'); ?></td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('ctsop_form_show'); ?>
		<input type="hidden" name="frm_ctsop_display" value="yes"/>
      </form>	
	<div class="tablenav bottom">
		<a href="<?php echo WP_ctsop_ADMIN_URL; ?>&amp;ac=add"><input class="button action" type="button" value="<?php _e('Add New', 'content-text-slider-on-post'); ?>" /></a>
		<a href="<?php echo WP_ctsop_ADMIN_URL; ?>&amp;ac=set"><input class="button action" type="button" value="<?php _e('Plugin Setting', 'content-text-slider-on-post'); ?>" /></a>
		<a target="_blank" href="<?php echo WP_ctsop_FAV; ?>"><input class="button action" type="button" value="<?php _e('Help', 'content-text-slider-on-post'); ?>" /></a>
		<a target="_blank" href="<?php echo WP_ctsop_FAV; ?>"><input class="button button-primary" type="button" value="<?php _e('Short Code', 'content-text-slider-on-post'); ?>" /></a>
	</div>
	</div>
</div>