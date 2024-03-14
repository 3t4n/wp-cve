<?php

$groupsResult = civicrm_api3( 'Group', 'get', [
	'sequential' => 1,
	'cache_date' => [ 'IS NULL' => 1 ],
	'is_active' => 1,
	'options' => [ 'limit' => 0 ],
] );

?>

<h2><?php _e( 'Contact Link', 'cf-civicrm' ); ?></h2>
<div id="{{_id}}_contact_link" class="caldera-config-group">
	<label><?php _e( 'Link to', 'cf-civicrm' ); ?></label>
	<div class="caldera-config-field">
		<?php caldera_forms_civicrm()->helper->contact_link_field() ?>
		<p><?php _e( 'Select which contact you want to link this processor to.', 'cf-civicrm' ); ?></p>
	</div>
</div>

<hr style="clear: both;" />

<div id="{{_id}}_contact_group" class="caldera-config-group">
	<label><?php _e('Group', 'cf-civicrm' ); ?></label>
	<div class="caldera-config-field">
		<select class="block-input field-config" name="{{_name}}[contact_group]">
		<?php foreach( $groupsResult['values'] as $key => $value ) { ?>
			<option value="<?php echo esc_attr( $value['id'] ); ?>" {{#is contact_group value=<?php echo $value['id']; ?>}}selected="selected"{{/is}}><?php echo esc_html( $value['title'] ); ?></option>
		<?php } ?>
		</select>
	</div>
</div>
