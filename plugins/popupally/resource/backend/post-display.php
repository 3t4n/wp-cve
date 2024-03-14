<div style="overflow-x:auto;">
<table class="form-table" style="text-align:center;">
	<tbody>
		<tr valign="top">
			<th style="padding:0 5px;">#</th>
			<th style="font-size:12px;padding:0 5px;">Time delayed</th>
			<th style="font-size:12px;padding:0 5px;">Exit intent</th>
			<th style="font-size:12px;padding:0 5px;">Embedded</th>
		</tr>
		<?php foreach($to_show as $id=>$type) { ?>
		<tr valign="top">
			<th scope="row" style="width:50%;">
				<?php echo $id; ?>
			</th>
			<td>
				<?php echo in_array('timed', $type) ? '&#x2714;' : ''; ?>
			</td>
			<td>
				<?php echo in_array('exit-intent', $type) ? '&#x2714;' : ''; ?>
			</td>
			<td>
				<?php echo in_array('embedded', $type) ? '&#x2714;' : ''; ?>
			</td>
		</tr>
		<?php } ?>
	</tbody>
</table>
</div>