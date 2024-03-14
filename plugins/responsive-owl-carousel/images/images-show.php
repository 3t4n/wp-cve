<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

// Form submitted, check the data
$viewguid = "";
if (isset($_POST['frm_owlc_display']) && $_POST['frm_owlc_display'] == 'yes') {
	
	$guid = isset($_GET['guid']) ? $_GET['guid'] : '0';
	$owlc_success = '';
	$owlc_success_msg = FALSE;
	
	if ($guid <> '' && $guid <> '0') {
	
		owlc_cls_security::owlc_check_guid($guid);
	
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['guid']) && $_GET['guid'] != '') 
		{
			// First check if ID exist with requested ID
			$result = owlc_cls_dbquery::owlc_image_count($guid);
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
		}
		elseif (isset($_GET['ac']) && $_GET['ac'] == 'view' && isset($_GET['guid']) && $_GET['guid'] != '') 
		{
			$viewguid = $_GET['guid'];
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
		<?php echo __( 'Image Details', 'owl-carousel-responsive' ); ?>  
		<a class="add-new-h2" href="<?php echo OWLC_ADMINURL; ?>?page=owlc-images&amp;ac=add"><?php echo __( 'Add New', 'owl-carousel-responsive' ); ?></a>
	</h2>
	<form name="frm_owlc_display" method="post">
	<div class="tablenav top">
	<label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
	<select name="owl_galleryguid" id="owl_galleryguid" onchange="return _owlc_view(this.value)">
	  <option value=''><?php _e('All Gallery', 'owl-carousel-responsive'); ?></option>
			<?php
			$gallery = array();
			$gallery = owlc_cls_dbquery::owlc_gallery_view("", 0, 100);
			if(count($gallery) > 0)
			{
				foreach ($gallery as $img)
				{
					$thisselected = "";
					if($img['owl_guid'] == $viewguid ) 
					{ 
						$thisselected = "selected='selected'" ; 
					}
					?>
					<option value='<?php echo $img['owl_guid']; ?>' <?php echo $thisselected; ?>>
						<?php echo esc_html(stripslashes($img['owl_title'])); ?>
					</option>
					<?php
					$thisselected = "";
				}
			}
			?>
	</select>
	<input id="doaction" class="button action" value="Filter" type="button"><input id="doaction" class="button action" value="Help" type="button" onclick="return _owlc_help()">
	</div>
	<div class="tool-box">
	<?php
	
	$myData = array();
	if($viewguid <> "")
	{
		$myData = owlc_cls_dbquery::owlc_image_viewbycategory($viewguid);
	}
	else
	{
		$myData = owlc_cls_dbquery::owlc_image_view("", 0, 100);
	}
		
	?>
	<table width="100%" class="widefat" id="straymanage">
		<thead>
			<tr>
				<th scope="col"><?php echo __( 'Carousel Image', 'owl-carousel-responsive' ); ?></th>
				<th scope="col"><?php echo __( 'Image Title', 'owl-carousel-responsive' ); ?></th>
				<th scope="col"><?php echo __( 'Gallery', 'owl-carousel-responsive' ); ?></th>
				<th scope="col"><?php echo __( 'Order', 'owl-carousel-responsive' ); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th scope="col"><?php echo __( 'Carousel Image', 'owl-carousel-responsive' ); ?></th>
				<th scope="col"><?php echo __( 'Image Title', 'owl-carousel-responsive' ); ?></th>
				<th scope="col"><?php echo __( 'Gallery', 'owl-carousel-responsive' ); ?></th>
				<th scope="col"><?php echo __( 'Order', 'owl-carousel-responsive' ); ?></th>
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
							<td align="left"><a href="<?php echo $data['owl_image']; ?>" target="_blank"><img style="text-align:left;max-height:150px;max-width:150px;height:auto;width:auto;" src="<?php echo $data['owl_image']; ?>" /></a>
							<div class="row-actions">
								<span class="edit">
								<a title="Edit" href="<?php echo OWLC_ADMINURL; ?>?page=owlc-images&amp;ac=edit&amp;guid=<?php echo $data['owl_guid']; ?>"><?php _e('Edit', 'owl-carousel-responsive'); ?></a> 
								</span>
								<span class="trash">
								| <a onClick="javascript:_owlc_delete('<?php echo $data['owl_guid']; ?>')" href="javascript:void(0);"><?php _e('Delete', 'owl-carousel-responsive'); ?></a>
								</span>
							</div>
							</td>
							<td><?php echo esc_html(stripslashes($data['owl_title'])); ?></td>
							<td>
							<?php
							$owl_galleryguid = $data['owl_galleryguid'];
							$gallery = owlc_cls_dbquery::owlc_gallery_view($owl_galleryguid, 0, 1); 
							echo stripslashes($gallery[0]['owl_title']);
							?>
							</td>
							<td><?php echo $data['owl_order']; ?></td>
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
	</div>
	</form>
	<p class="description">
		<?php _e('For more information about this plugin', 'owl-carousel-responsive'); ?>
		<a target="_blank" href="<?php echo OWLC_FAVURL; ?>"><?php _e('click here', 'owl-carousel-responsive'); ?></a><br />
	</p>
</div>