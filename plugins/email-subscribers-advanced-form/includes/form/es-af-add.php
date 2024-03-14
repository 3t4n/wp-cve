<?php

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
	die('You are not allowed to call this page directly.');
}
?>

<div class="wrap">
	<?php
	$es_af_es_plugin_active = true;
	if ( !is_plugin_active( 'email-subscribers/email-subscribers.php' ) ) {
		echo "<div class='error fade'><p><strong>";
		_x('Please note, Email Subscribers Group Selector plugin works only if you have activated Email Subscribers plugin first.', 'es-af-add' ,ES_AF_TDOMAIN);
		echo "</strong></p></div>";
		$es_af_es_plugin_active = false;
	}

	$es_af_errors = array();
	$es_af_success = '';
	$es_af_error_found = FALSE;

	// Preset the form fields
	$form = array(
		'es_af_id' => '',
		'es_af_title' => '',
		'es_af_desc' => '',
		'es_af_name' => '',
		'es_af_name_mand' => '',
		'es_af_email' => '',
		'es_af_email_mand' => '',
		'es_af_group' => '',
		'es_af_group_mand' => '',
		'es_af_group_list' => ''
	);

	// Form submitted, check the data
	if (isset($_POST['es_af_form_submit']) && $_POST['es_af_form_submit'] == 'yes') {
		//	Just security thing that wordpress offers us
		check_admin_referer('es_af_form_add');

		$form['es_af_title'] = isset($_POST['es_af_title']) ? $_POST['es_af_title'] : '';
		if ($form['es_af_title'] == '') {
			$es_af_errors[] = __('Enter title for your form.', ES_AF_TDOMAIN);
			$es_af_error_found = TRUE;
		}

		$form['es_af_desc'] = isset($_POST['es_af_desc']) ? $_POST['es_af_desc'] : '';
		$form['es_af_name'] = isset($_POST['es_af_name']) ? $_POST['es_af_name'] : '';
		$form['es_af_name_mand'] = isset($_POST['es_af_name_mand']) ? $_POST['es_af_name_mand'] : '';
		$form['es_af_email'] = isset($_POST['es_af_email']) ? $_POST['es_af_email'] : 'YES';
		$form['es_af_email_mand'] = isset($_POST['es_af_email_mand']) ? $_POST['es_af_email_mand'] : 'YES';
		$form['es_af_group'] = isset($_POST['es_af_group']) ? $_POST['es_af_group'] : '';
		$form['es_af_group_mand'] = isset($_POST['es_af_group_mand']) ? $_POST['es_af_group_mand'] : '';

		$form['es_af_group_list'] = isset($_POST['es_af_group_list']) ? $_POST['es_af_group_list'] : '';
		if($form['es_af_group_list'] != "") {
			$special_letters = es_af_registerhook::esaf_special_letters();
			if (preg_match($special_letters, $form['es_af_group_list'])) {
				$es_af_errors[] = __( 'Error: Special characters are not allowed in the group name.', ES_AF_TDOMAIN );
				$es_af_error_found = TRUE;
			}
		}

		//	No errors found, we can add this Group to the table
		if ($es_af_error_found == FALSE) {
			$action = es_af_query::es_af_act($form, "ins");
			if($action == "sus") {
				$es_af_success = __( 'Form added.', ES_AF_TDOMAIN );
			} elseif($action == "err") {
				$es_af_success = __( 'Oops unexpected error occurred.', ES_AF_TDOMAIN );
				$es_af_error_found = TRUE;
			}

			// Reset the form fields
			$form = array(
				'es_af_id' => '',
				'es_af_title' => '',
				'es_af_desc' => '',
				'es_af_name' => '',
				'es_af_name_mand' => '',
				'es_af_email' => '',
				'es_af_email_mand' => '',
				'es_af_group' => '',
				'es_af_group_mand' => '',
				'es_af_group_list' => ''
			);
		}
	}

	if ($es_af_error_found == TRUE && isset($es_af_errors[0]) == TRUE) {
		?>
		<div class="error fade">
			<p><strong><?php echo $es_af_errors[0]; ?></strong></p>
		</div>
		<?php
	}

	if ($es_af_error_found == FALSE && strlen($es_af_success) > 0) {
		?>
		<div class="notice notice-success is-dismissible">
			<p><strong>
				<?php echo $es_af_success; ?>
			</strong></p>
		</div>
		<?php
	}

	?>

	<style>
		.form-table th {
			width: 300px;
		}
	</style>

	<div class="form-wrap">
		<h2>
			<?php echo __( 'Add Form', ES_AF_TDOMAIN ); ?>
			<a class="add-new-h2" target="_blank" href="<?php echo ES_AF_FAV; ?>"><?php echo __( 'Help', ES_AF_TDOMAIN ); ?></a>
		</h2>
		<form name="es_af_form" method="post" action="#" onsubmit="return es_af_submit()">

			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="tag-link"><?php echo __( 'Title of form', ES_AF_TDOMAIN ); ?></label>
						</th>
						<td>
							<input type="text" name="es_af_title" id="es_af_title" value="" size="30" maxlength="100" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="tag-link"><?php echo __( 'Short description about form', ES_AF_TDOMAIN ); ?></label>
						</th>
						<td>
							<input name="es_af_desc" type="text" id="es_af_desc" value="" size="50" maxlength="255" />
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="tag-link"><?php echo __( 'Display NAME field?', ES_AF_TDOMAIN ); ?></label>
						</th>
						<td>
							<select name="es_af_name" id="es_af_email">
								<option value='YES'><?php echo __( 'YES', ES_AF_TDOMAIN ); ?></option>
								<option value='NO'><?php echo __( 'NO', ES_AF_TDOMAIN ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="tag-link"><?php echo __( 'Make NAME field Mandatory?', ES_AF_TDOMAIN ); ?></label>
						</th>
						<td>
							<select name="es_af_name_mand" id="es_af_name_mand">
								<option value='YES'><?php echo __( 'YES', ES_AF_TDOMAIN ); ?></option>
								<option value='NO'><?php echo __( 'NO', ES_AF_TDOMAIN ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="tag-link"><?php echo __( 'Display EMAIL field?', ES_AF_TDOMAIN ); ?></label>
						</th>
						<td>
							<select name="es_af_email" id="es_af_email" disabled="disabled">
								<option value='YES'><?php echo __( 'YES', ES_AF_TDOMAIN ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="tag-link"><?php echo __( 'Make EMAIL field Mandatory?', ES_AF_TDOMAIN ); ?></label>
						</th>
						<td>
							<select name="es_af_email_mand" id="es_af_email_mand" disabled="disabled">
								<option value='YES'><?php echo __( 'YES', ES_AF_TDOMAIN ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="tag-link"><?php echo __( 'Allow GROUP selection from form?', ES_AF_TDOMAIN ); ?></label>
						</th>
						<td>
							<select name="es_af_group" id="es_af_group">
								<option value='YES'><?php echo __( 'YES', ES_AF_TDOMAIN ); ?></option>
								<option value='NO'><?php echo __( 'NO', ES_AF_TDOMAIN ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="tag-link"><?php echo __( 'Make GROUP selection Mandatory?', ES_AF_TDOMAIN ); ?></label>
						</th>
						<td>
							<select name="es_af_group_mand" id="es_af_group_mand">
								<option value='YES'><?php echo __( 'YES', ES_AF_TDOMAIN ); ?></option>
								<option value='NO'><?php echo __( 'NO', ES_AF_TDOMAIN ); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">
							<label for="tag-link"><?php echo __( 'GROUP names to display<br>(coma separated values)', ES_AF_TDOMAIN ); ?></label>
						</th>
						<td>
							<input type="text" name="es_af_group_list" id="es_af_group_list" value="Public" size="50" maxlength="255" />
							<?php
							$existing_groups = "";
							if($es_af_es_plugin_active) {
								$groups = array();
								$groups = es_cls_dbquery::es_view_subscriber_group();
								if(count($groups) > 0) {
									$i = 1;
									foreach ($groups as $group) {
										if($i != 1) {
											$existing_groups = $existing_groups . ",";
										}
										$existing_groups = $existing_groups . $group["es_email_group"];
										$i = $i +1;
									}
								}
							}
							?>
							<p><?php echo __( 'Existing Groups : ', ES_AF_TDOMAIN ); ?><?php echo $existing_groups; ?></p>
						</td>
					</tr>
				</tbody>
			</table>
			<input type="hidden" name="es_af_form_submit" id="es_af_form_submit" value="yes"/>
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php echo __( 'Save', ES_AF_TDOMAIN ); ?>" />
			</p>
			<?php wp_nonce_field('es_af_form_add'); ?>
		</form>
	</div>
	<p class="description"><?php echo ES_AF_OFFICIAL; ?></p>
</div>