<?php /* Hide template from public access ... Next line is email subject - Following lines are email body
[organisation] Booking Confirmation
<h2>Meal Selections for each ticket
<table class="form-table">
	<tr valign="top">
	[startloop]
		<td>Ticket:&nbsp;[ticketSeat]</td>
		<td>
			<select class="stageshow_customco stageshow-trolley-ui" input-format="select" minval=1 id=stageshow_customcoItem_Meal_Selection[cartIndex] name=stageshow_customcoItem_Meal_Selection[cartIndex] alt="Meal Selection">
				<option value="">&nbsp;&nbsp;</option>
				<option value="Meat">Meat</option>
				<option value="Vegetarian" selected="">Vegetarian</option>
			</select>
		</td>
	[endloop]
	</tr>
</table>
*/ ?>