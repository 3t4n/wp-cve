<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
// Form submitted, check the data
if (isset($_POST['frm_ImgSlider_display_submit']) && $_POST['frm_ImgSlider_display_submit'] == 'yes')
{
	$did = isset($_GET['did']) ? sanitize_text_field($_GET['did']) : '0';
	if(!is_numeric($did)) { die('<p>Are you sure you want to do this?</p>'); }
	
	$ImgSlider_success = '';
	$ImgSlider_success_msg = FALSE;
	
	// First check if ID exist with requested ID
	$sSql = $wpdb->prepare(
		"SELECT COUNT(*) AS `count` FROM ".WP_ImgSlider_TABLE."
		WHERE `ImgSlider_id` = %d",
		array($did)
	);
	$result = '0';
	$result = $wpdb->get_var($sSql);
	
	if ($result != '1')
	{
		?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist.', 'image-slider-with-description'); ?></strong></p></div><?php
	}
	else
	{
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('ImgSlider_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".WP_ImgSlider_TABLE."`
					WHERE `ImgSlider_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$ImgSlider_success_msg = TRUE;
			$ImgSlider_success = __('Selected record was successfully deleted.', 'image-slider-with-description');
		}
	}
	
	if ($ImgSlider_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $ImgSlider_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php _e('Image slider with description', 'image-slider-with-description'); ?>
	<a class="add-new-h2" href="<?php echo WP_ImgSlider_ADMIN_URL; ?>&amp;sp=add"><?php _e('Add New', 'image-slider-with-description'); ?></a></h2>
    <div class="tool-box">
	<?php
	$sSql = "SELECT * FROM `".WP_ImgSlider_TABLE."` order by ImgSlider_type, ImgSlider_order";
	$myData = array();
	$myData = $wpdb->get_results($sSql, ARRAY_A);
	?>
      <form name="frm_ImgSlider_display" method="post">
        <table width="100%" class="widefat" id="straymanage">
          <thead>
            <tr>
			  <!--<th scope="col" class="check-column" style="padding: 8px 2px;"><input type="checkbox" /></th>-->
			  <th scope="col"><?php _e('Title', 'image-slider-with-description'); ?></th>
			  <th scope="col"><?php _e('Image Path', 'image-slider-with-description'); ?></th>
              <th scope="col"><?php _e('Type/Group', 'image-slider-with-description'); ?></th>
			  <th scope="col"><?php _e('Link Target', 'image-slider-with-description'); ?></th>
              <th scope="col"><?php _e('Order', 'image-slider-with-description'); ?></th>
              <th scope="col"><?php _e('Display', 'image-slider-with-description'); ?></th>
            </tr>
          </thead>
		  <tfoot>
            <tr>
			  <!--<th scope="col" class="check-column" style="padding: 8px 2px;"><input type="checkbox" /></th>-->
              <th scope="col"><?php _e('Title', 'image-slider-with-description'); ?></th>
			  <th scope="col"><?php _e('Image Path', 'image-slider-with-description'); ?></th>
              <th scope="col"><?php _e('Type/Group', 'image-slider-with-description'); ?></th>
			  <th scope="col"><?php _e('Link Target', 'image-slider-with-description'); ?></th>
              <th scope="col"><?php _e('Order', 'image-slider-with-description'); ?></th>
              <th scope="col"><?php _e('Display', 'image-slider-with-description'); ?></th>
            </tr>
          </tfoot>
		<?php 
		$i = 0;
		$displayisthere = FALSE;
		if(count($myData) > 0 )
		{
			foreach ($myData as $data)
			{
				?>
				<tbody>
				<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
					<!--<td><input type="checkbox" value="<?php //echo $data['ImgSlider_id']; ?>" name="ImgSlider_group_item[]"></td>-->
					<td align="left" valign="middle">
					<?php echo esc_html(stripslashes($data['ImgSlider_title'])); ?>
					<div class="row-actions">
					<span class="edit">
						<a title="Edit" href="<?php echo WP_ImgSlider_ADMIN_URL; ?>&amp;sp=edit&amp;did=<?php echo $data['ImgSlider_id']; ?>"><?php _e('Edit', 'image-slider-with-description'); ?></a> | 
					</span>
					<span class="trash">
						<a onClick="javascript:ImgSlider_delete('<?php echo $data['ImgSlider_id']; ?>')" href="javascript:void(0);"><?php _e('Delete', 'image-slider-with-description'); ?></a>
					</span> 
					</div>
					</td>
					<td align="left" valign="middle">
					<a href="<?php echo esc_html(stripslashes($data['ImgSlider_path'])); ?>" target="_blank" title="Preview URL">
					<img src="<?php echo WP_ImgSlider_PLUGIN_URL; ?>/images/preview.gif" alt="Preview URL" />
					</a>
					</td>
					<td align="left" valign="middle"><?php echo esc_html(stripslashes($data['ImgSlider_type'])); ?></td>
					<td align="left" valign="middle"><?php echo esc_html(stripslashes($data['ImgSlider_target'])); ?></td>
					<td align="left" valign="middle"><?php echo esc_html(stripslashes($data['ImgSlider_order'])); ?></td>
					<td align="left" valign="middle"><?php echo esc_html(stripslashes($data['ImgSlider_status'])); ?></td>
				</tr>
				</tbody>
				<?php 
				$i = $i+1; 
			}
		}
		else
		{
			?><tr><td colspan="6" align="center"><?php _e('No records available', 'image-slider-with-description'); ?></td></tr><?php 
		}
		?>
        </table>
		<?php wp_nonce_field('ImgSlider_form_show'); ?>
		<input type="hidden" name="frm_ImgSlider_display_submit" value="yes"/>
      </form>
	  <div class="tablenav bottom">
	  <a href="<?php echo WP_ImgSlider_ADMIN_URL; ?>&amp;sp=add"><input class="button action" type="button" value="<?php _e('Add New', 'image-slider-with-description'); ?>" /></a>
	  <a target="_blank" href="<?php echo WP_ImgSlider_FAV; ?>"><input class="button action" type="button" value="<?php _e('Help', 'image-slider-with-description'); ?>" /></a>
	  <a target="_blank" href="<?php echo WP_ImgSlider_FAV; ?>"><input class="button button-primary" type="button" value="<?php _e('Short Code', 'image-slider-with-description'); ?>" /></a>
	  </div>
    </div>
	<br />
	<p class="description">
		<?php _e('Note: Use the short code to add the gallery in to the posts and pages.', 'image-slider-with-description'); ?>
		<?php _e('Check official website for more information', 'image-slider-with-description'); ?>
		<a target="_blank" href="<?php echo WP_ImgSlider_FAV; ?>"><?php _e('click here', 'image-slider-with-description'); ?></a>
	</p>
</div>