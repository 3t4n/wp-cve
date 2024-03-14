<?php

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
	die('You are not allowed to call this page directly.');
}

if ( !is_plugin_active( 'email-subscribers/email-subscribers.php' ) ) {
	echo "<div class='error fade'><p><strong>";
	_x('Please note, Email Subscribers - Group Selector plugin works only if you have activated Email Subscribers plugin first.', 'es-af-show' ,ES_AF_TDOMAIN);
	echo "</strong></p></div>";
}

// Form submitted, check the data
if (isset($_POST['frm_es_af_display']) && $_POST['frm_es_af_display'] == 'yes') {
	$did = isset($_GET['did']) ? $_GET['did'] : '0';
	if(!is_numeric($did)) { die('<p>Are you sure you want to do this?</p>'); }

	$es_af_success = '';
	$es_af_success_msg = FALSE;

	// First check if ID exist with requested ID
	$result = es_af_query::es_af_count($did);

	if ($result != '1') {
		?><div class="error fade">
			<p><strong><?php echo __('Oops, selected details does not exists.', ES_AF_TDOMAIN); ?></strong></p>
		</div><?php
	} else {
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '') {
			//	Just security thingy that wordpress offers us
			check_admin_referer('es_af_form_show');

			//	Delete selected record from the table
			es_af_query::es_af_delete($did);

			//	Set success message
			$es_af_success_msg = TRUE;
			$es_af_success = __('Selected record deleted.', ES_AF_TDOMAIN);
		}
	}

	if ($es_af_success_msg == TRUE) {
		?><div class="notice notice-success is-dismissible">
			<p><strong><?php echo $es_af_success; ?></strong></p>
		</div><?php
	}
}
?>

<div class="wrap">
	<h2>
		<?php echo __( 'Group Selector' , ES_AF_TDOMAIN ); ?>
		<a class="add-new-h2" href="<?php echo ES_AF_ADMINURL; ?>&amp;ac=add"><?php echo __( 'Add New', ES_AF_TDOMAIN ); ?></a>
		<a class="add-new-h2" target="_blank" href="<?php echo ES_AF_FAV; ?>"><?php echo __( 'Help', ES_AF_TDOMAIN ); ?></a>
	</h2>
	<p class="description" style="margin-bottom:1em;">
		<?php echo __( 'Use this to create subscribe forms to allow your subscribers to select interested group while subscribing.', ES_AF_TDOMAIN ); ?>
	</p>
	<div class="tool-box">
		<?php
		$myData = array();
		$myData = es_af_query::es_af_select(0);
		?>
		<form name="frm_es_af_display" method="post">
			<table width="100%" class="widefat" id="straymanage">
				<thead>
					<tr>
						<th class="check-column" scope="col" style="padding: 8px 2px;"><input type="checkbox" name="es_af_checkall" id="es_af_checkall" /></th>
						<th scope="col"><?php echo __( 'Title', ES_AF_TDOMAIN ); ?></th>
						<th scope="col"><?php echo __( 'Shortcode (Copy and use it anywhere on your site)', ES_AF_TDOMAIN ); ?></th>
						<th scope="col"><?php echo __( 'Groups selected in the form', ES_AF_TDOMAIN ); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th class="check-column" scope="col" style="padding: 8px 2px;"><input type="checkbox" name="es_af_checkall" id="es_af_checkall" /></th>
						<th scope="col"><?php echo __( 'Title', ES_AF_TDOMAIN ); ?></th>
						<th scope="col"><?php echo __( 'Shortcode (Copy and use it anywhere on your site)', ES_AF_TDOMAIN ); ?></th>
						<th scope="col"><?php echo __( 'Groups selected in the form', ES_AF_TDOMAIN ); ?></th>
					</tr>
				</tfoot>
				<tbody>
					<?php 
					$i = 0;
					if(count($myData) > 0 ) {
						foreach ($myData as $data) {
							?>
							<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
								<td align="left"><input name="chk_delete[]" id="chk_delete[]" type="checkbox" value="<?php echo $data['es_af_title'] ?>" /></td>
								<td><?php echo stripslashes($data['es_af_title']); ?>
									<div class="row-actions">
										<span class="edit">
											<a title="Edit" href="<?php echo ES_AF_ADMINURL; ?>&ac=edit&amp;did=<?php echo $data['es_af_id']; ?>"><?php echo __( 'Edit', ES_AF_TDOMAIN ); ?></a> | </span>
											<span class="trash">
											<a onClick="javascript:es_af_delete('<?php echo $data['es_af_id']; ?>')" href="javascript:void(0);"><?php echo __( 'Delete', ES_AF_TDOMAIN ); ?></a>
										</span>
									</div>
								</td>
								<td><code>[email-subscribers-advanced-form id="<?php echo $data['es_af_id']; ?>"]</code></td>
								<td><?php echo $data['es_af_group_list']; ?></td>
							</tr>
							<?php 
							$i = $i+1;
						}
					} else {
						?><tr><td colspan="2" align="center"><?php echo __( 'No records available.', ES_AF_TDOMAIN ); ?></td></tr><?php 
					}
					?>
				</tbody>
			</table>
			<?php wp_nonce_field('es_af_form_show'); ?>
			<input type="hidden" name="frm_es_af_display" value="yes"/>
		</form>
		<div style="height:10px;"></div>
		<p class="description"><?php echo ES_AF_OFFICIAL; ?></p>
	</div>
</div>