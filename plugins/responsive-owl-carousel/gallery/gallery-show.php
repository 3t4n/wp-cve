<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

// Form submitted, check the data
if (isset($_POST['frm_owlc_display']) && $_POST['frm_owlc_display'] == 'yes') {
	$guid = isset($_GET['guid']) ? $_GET['guid'] : '0';
	owlc_cls_security::owlc_check_guid($guid);

	$owlc_success = '';
	$owlc_success_msg = FALSE;

	// First check if ID exist with requested ID
	$result = owlc_cls_dbquery::owlc_gallery_count($guid);
	if ($result != '1') {
		?><div class="error fade">
			<p><strong>
				<?php echo __( 'Oops, selected details does not exists.', 'owl-carousel-responsive' ); ?>
			</strong></p>
		</div><?php
	} else {
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['guid']) && $_GET['guid'] != '') {
			//	Just security thingy that wordpress offers us
			check_admin_referer('owlc_form_show');

			//	Delete selected record from the table
			owlc_cls_dbquery::owlc_delete($guid);

			//	Set success message
			$owlc_success_msg = TRUE;
			$owlc_success = __( 'Selected record deleted.', 'owl-carousel-responsive' );
		}
	}
	
	if ($owlc_success_msg == TRUE) {
		?><div class="notice notice-success is-dismissible">
			<p><strong>
				<?php echo $owlc_success; ?>
			</strong></p>
		</div><?php
	}
}
?>

<div class="wrap">
	<h2>
		<?php echo __( 'Gallery Details', 'owl-carousel-responsive' ); ?>  
		<a class="add-new-h2" href="<?php echo OWLC_ADMINURL; ?>?page=owlc-gallery&amp;ac=add"><?php echo __( 'Add New', 'owl-carousel-responsive' ); ?></a>
	</h2>
	<div class="tablenav top">
	<label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
	<select name="owl_galleryguid" id="owl_galleryguid">
	  <option value=''><?php _e('All Gallery', 'owl-carousel-responsive'); ?></option>
	</select>
	<input id="doaction" class="button action" value="Filter" type="button"><input id="doaction" class="button action" value="Help" type="button" onclick="return _owlc_help()">
	</div>
	<div class="tool-box">
		<?php
			$myData = array();
			$myData = owlc_cls_dbquery::owlc_gallery_view("", 0, 100);
		?>
		<form name="frm_owlc_display" method="post">
			<table width="100%" class="widefat" id="straymanage">
				<thead>
					<tr>
						<th scope="col"><?php echo __( 'Gallery Name', 'owl-carousel-responsive' ); ?></th>
						<th scope="col"><?php echo __( 'Settings', 'owl-carousel-responsive' ); ?></th>
						<th scope="col"><?php echo __( 'Auto Width/Height', 'owl-carousel-responsive' ); ?></th>
						<th scope="col"><?php echo __( 'Short Code', 'owl-carousel-responsive' ); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th scope="col"><?php echo __( 'Gallery Name', 'owl-carousel-responsive' ); ?></th>
						<th scope="col"><?php echo __( 'Settings', 'owl-carousel-responsive' ); ?></th>
						<th scope="col"><?php echo __( 'Auto Width/Height', 'owl-carousel-responsive' ); ?></th>
						<th scope="col"><?php echo __( 'Short Code', 'owl-carousel-responsive' ); ?></th>
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
									<td><?php echo esc_html(stripslashes($data['owl_title'])); ?>
									<div class="row-actions">
										<span class="edit">
										<a title="Edit" href="<?php echo OWLC_ADMINURL; ?>?page=owlc-gallery&amp;ac=edit&amp;guid=<?php echo $data['owl_guid']; ?>"><?php _e('Edit', 'owl-carousel-responsive'); ?></a> 
										</span>
										<span class="trash">
										| <a onClick="javascript:_owlc_delete('<?php echo $data['owl_guid']; ?>')" href="javascript:void(0);"><?php _e('Delete', 'owl-carousel-responsive'); ?></a>
										</span>
									</div>
									</td>
									<td>								
									<?php
									$settings = array();
									$owl_setting = $data['owl_setting'];
									$settings = owlc_cls_common::owlc_split_settings($owl_setting);
									echo sprintf( __( 'Image display for screen 1000px: <b>%s</b>', 'owl-carousel-responsive' ), $settings["items_1000"] ) . ", ";
									echo sprintf( __( 'Image display for mobile screen: <b>%s</b>', 'owl-carousel-responsive' ), $settings["items_0"] ) . "<br>";
									echo sprintf( __( 'Navigation Button: <b>%s</b>', 'owl-carousel-responsive' ), strtoupper($settings["nav"]) ) . ", ";
									echo sprintf( __( 'Margin: <b>%spx</b>', 'owl-carousel-responsive' ), $settings["margin"] ) . ", ";
									echo sprintf( __( 'Autoplay: <b>%s</b>', 'owl-carousel-responsive' ), strtoupper($settings["autoplay"]) ) . ", ";
									echo sprintf( __( 'Autoplay Timeout: <b>%s</b>', 'owl-carousel-responsive' ), $settings["autoplayTimeout"] ) . "<br>";
									?>
									</td>
									<td><?php echo strtoupper($settings['autoWidth']); ?> / <?php echo strtoupper($settings['autoHeight']); ?></td>
									<td>[owl-carousel-responsive id="<?php echo $data['owl_id']; ?>"]</td>
								</tr>
							<?php
								$i = $i+1;
							}
						} else {
							?><tr>
								<td colspan="3" align="center"><?php echo __( 'No records available.', 'owl-carousel-responsive' ); ?></td>
							</tr><?php 
						}
					?>
				</tbody>
			</table>
			<?php wp_nonce_field('owlc_form_show'); ?>
			<input type="hidden" name="frm_owlc_display" value="yes"/>
		</form>
	</div>
	<p class="description">
		<?php _e('For more information about this plugin', 'owl-carousel-responsive'); ?>
		<a target="_blank" href="<?php echo OWLC_FAVURL; ?>"><?php _e('click here', 'owl-carousel-responsive'); ?></a><br />
	</p>
</div>