<?php
if(!defined('ABSPATH')){die;}
?>

<div class="locations-wrapper" data-empty-object='{"Name":"","Days":[{"Day":"Monday","Hours":[{"From":"0","To":"0","ToIndication":"PM","FromIndication":"AM"}]},{"Day":"Tuesday","Hours":[{"From":"0","To":"0","ToIndication":"PM","FromIndication":"AM"}]},{"Day":"Wednesday","Hours":[{"From":"0","To":"0","ToIndication":"PM","FromIndication":"AM"}]},{"Day":"Thursday","Hours":[{"From":"0","To":"0","ToIndication":"PM","FromIndication":"AM"}]},{"Day":"Friday","Hours":[{"From":"0","To":"0","ToIndication":"PM","FromIndication":"AM"}]},{"Day":"Saturday","Hours":[{"From":"0","To":"0","ToIndication":"PM","FromIndication":"AM"}]},{"Day":"Sunday","Hours":[{"From":"0","To":"0","ToIndication":"PM","FromIndication":"AM"}]}],"Holidays":[],"Vacations":[]}'>
	<input type="hidden" name="mb-bhi-settings[locations]" value='<?php echo empty( $data['locations'] ) ? '' : json_encode($data['locations'],JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT  );?>' />

	<div id="add-location" style="display:none;">
		<p>
			<?php _e('The location name should be unique','business-hours-indicator');?>.
		</p>
		<table class="form-table">
			<tr>
				<th scope="row"><?php _e('Name','business-hours-indicator');?></th>
				<td>
					<input type="text" name="loc-name" maxlength="50" class="skip-save"  />
				</td>
			</tr>
			<tr>
				<th scope="row" colspan="2">
					<a href="#" class="btn-add-loc mabel-btn"><?php _e('Add','business-hours-indicator');?></a>
				</th>
			</tr>
		</table>
	</div>

	<div id="add-hours" style="display:none;">
		<p><?php _e("When you're closed on a certain day, just leave the hours blank.<br/>Remember: 12PM is midday, 12AM is midnight.",'business-hours-indicator');?></p>
		<table class="form-table table-hours">
			<tbody class="add-hours-table">

			</tbody>
		</table>

		<div class="modal-button-row">
			<a href="#" class="btn-edit-hours mabel-btn"><?php _e('Done','business-hours-indicator');?></a>
		</div>
	</div>

	<div id="add-specials" style="display:none;">
		<p>
			<?php _e("When you're closed on a certain day, just leave the hours blank.<br/>Remember: 12PM is midday, 12AM is midnight.",'business-hours-indicator');?></p>

		<div class="sd-display-wrapper">
			<table class="form-table sd-display"></table>
		</div>
		<hr/>

		<table class="form-table add-specials-table">
		</table>
		<div class="modal-button-row">
			<a href="javascript:tb_remove();" class="btn-edit-specials mabel-btn"><?php _e('Done','business-hours-indicator');?></a>
		</div>
	</div>

	<div id="add-vacation" style="display:none;">
		<p><?php _e("Denote when you're on vacation.",'business-hours-indicator');?></p>
		<div class="sd-display-wrapper">
			<table class="form-table vac-display"></table>
		</div>
		<hr/>

		<table class="form-table add-vacation-table"></table>
		<div class="modal-button-row">
			<a href="javascript:tb_remove();" class="btn-edit-vacation mabel-btn"><?php _e('Done','business-hours-indicator');?></a>
		</div>
	</div>

	<div class="loc-display"></div>
</div>
<div class="p-t-3">
	<a href="#" class="mabel-btn btn-open-lpopup"><?php _e('Add location','business-hours-indicator');?></a>
</div>