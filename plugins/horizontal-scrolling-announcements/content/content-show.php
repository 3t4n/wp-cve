<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

// Form submitted, check the data
if (isset($_POST['frm_hsas_display']) && $_POST['frm_hsas_display'] == 'yes') {
	$guid = isset($_GET['guid']) ? $_GET['guid'] : '0';
	hsas_cls_security::hsas_check_guid($guid);

	$hsas_success = '';
	$hsas_success_msg = FALSE;

	// First check if ID exist with requested ID
	$result = hsas_cls_dbquery::hsas_content_count($guid);
	if ($result != '1') {
		?><div class="error fade">
			<p><strong>
				<?php echo __( 'Oops, selected details does not exists.', 'horizontal-scrolling-announcements' ); ?>
			</strong></p>
		</div><?php
	} else {
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['guid']) && $_GET['guid'] != '') {
			//	Just security thingy that wordpress offers us
			check_admin_referer('hsas_form_show');

			//	Delete selected record from the table
			hsas_cls_dbquery::hsas_content_delete($guid);

			//	Set success message
			$hsas_success_msg = TRUE;
			$hsas_success = __( 'Selected record deleted.', 'horizontal-scrolling-announcements' );
		}
	}
	
	if ($hsas_success_msg == TRUE) {
		?><div class="notice notice-success is-dismissible">
			<p><strong>
				<?php echo $hsas_success; ?>
			</strong></p>
		</div><?php
	}
}
?>

<div class="wrap">
	<h2>
		<?php echo __( 'Horizontal scrolling announcements', 'horizontal-scrolling-announcements' ); ?>  
		<a class="add-new-h2" href="<?php echo HSAS_ADMINURL; ?>?page=hsas-content&amp;ac=add"><?php echo __( 'Add New', 'horizontal-scrolling-announcements' ); ?></a>
	</h2>
	<h3><?php _e('Announcements', 'horizontal-scrolling-announcements'); ?></h3>
	<div class="tool-box">
		<?php
			$myData = array();
			$myData = hsas_cls_dbquery::hsas_content_view("", 0, 1000);
		?>
		<form name="frm_hsas_display" method="post">
			<table width="100%" class="widefat" id="straymanage">
				<thead>
					<tr>
						<th scope="col" width="70%"><?php echo __( 'Announcement', 'horizontal-scrolling-announcements' ); ?></th>
						<th scope="col"><?php echo __( 'Group', 'horizontal-scrolling-announcements' ); ?></th>
						<th scope="col"><?php echo __( 'Options', 'horizontal-scrolling-announcements' ); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th scope="col"><?php echo __( 'Announcement', 'horizontal-scrolling-announcements' ); ?></th>
						<th scope="col"><?php echo __( 'Group', 'horizontal-scrolling-announcements' ); ?></th>
						<th scope="col"><?php echo __( 'Options', 'horizontal-scrolling-announcements' ); ?></th>
					</tr>
				</tfoot>
				<tbody>
					<?php 
						$i = 0;
						$displayisthere = FALSE;
						if(count($myData) > 0) {
							$i = 1;
							foreach ($myData as $data) {
							?>
								<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
									<td><?php echo stripslashes(esc_html($data['hsas_text'])); ?>
									<div class="row-actions">
										<span class="edit">
										<a title="Edit" href="<?php echo HSAS_ADMINURL; ?>?page=hsas-content&amp;ac=edit&amp;guid=<?php echo $data['hsas_guid']; ?>"><?php _e('Edit', 'horizontal-scrolling-announcements'); ?></a> 
										</span>
										<span class="trash">
										| <a onClick="javascript:_hsas_delete('<?php echo $data['hsas_guid']; ?>')" href="javascript:void(0);"><?php _e('Delete', 'horizontal-scrolling-announcements'); ?></a>
										</span>
									</div>
									</td>
									<td><?php echo esc_html($data['hsas_group']); ?></td>
									<td>
									<?php echo __( 'Start : ', 'horizontal-scrolling-announcements' ); ?> <?php echo $data['hsas_datestart']; ?>, <?php echo hsas_cls_common::hsas_convert_time($data['hsas_timestart']); ?> <br />
									<?php echo __( 'End   : ', 'horizontal-scrolling-announcements' ); ?> <?php echo $data['hsas_dateend']; ?>, <?php echo hsas_cls_common::hsas_convert_time($data['hsas_timeend']); ?> <br />
									<?php echo __( 'Order', 'horizontal-scrolling-announcements' ); ?> : <?php echo esc_html($data['hsas_order']); ?>
									</td>
								</tr>
							<?php
								$i = $i+1;
							}
						} else {
							?><tr>
								<td colspan="3" align="center"><?php echo __( 'No records available.', 'horizontal-scrolling-announcements' ); ?></td>
							</tr><?php 
						}
					?>
				</tbody>
			</table>
			
			<?php wp_nonce_field('hsas_form_show'); ?>
			<input type="hidden" name="frm_hsas_display" value="yes"/>
		</form>
		<div class="tablenav bottom">
			<div class="alignleft actions bulkactions">
				<a href="<?php echo HSAS_ADMINURL; ?>?page=hsas-content&amp;ac=add"><input class="button button-primary" type="button" value="<?php _e('Add New', 'image-horizontal-reel-scroll-slideshow'); ?>" /></a>
				<a href="<?php echo HSAS_FAVURL; ?>" target="_blank"><input class="button button-primary" type="button" value="<?php _e('Short Code', 'image-horizontal-reel-scroll-slideshow'); ?>" /></a>
				<?php if(current_user_can('administrator')) { ?>
				<a href="<?php echo HSAS_ADMINURL; ?>?page=hsas-content&amp;ac=perm"><input class="button button-primary" type="button" value="<?php _e('Permission', 'image-horizontal-reel-scroll-slideshow'); ?>" /></a>
				<?php } ?>
				<a href="<?php echo HSAS_FAVURL; ?>" target="_blank"><input class="button button-primary" type="button" value="<?php _e('Live Demo', 'image-horizontal-reel-scroll-slideshow'); ?>" /></a>
				<a href="<?php echo HSAS_FAVURL; ?>" target="_blank"><input class="button button-primary" type="button" value="<?php _e('Help', 'image-horizontal-reel-scroll-slideshow'); ?>" /></a>
		  	</div>
	  </div>
		<p>
		<?php _e('Your server current Date and Time', 'horizontal-scrolling-announcements'); ?> : 
		<?php 
		$date_format = get_option( 'date_format' );
		$time_format = get_option( 'time_format' );
		echo date($date_format . ' ' . $time_format); 
		?>
		</p>
	</div>
</div>