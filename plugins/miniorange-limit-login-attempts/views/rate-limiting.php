<div class="molla-sub-tab-header">
			<div id="molla-rlimit" class="molla-sub-tab molla-sub-tab-active" >RATE LIMITING</div>
</div>
     <div class="mo-lla-sub-tabs mo-lla-sub-tabs-active">
		<div id="RL" name="RL">
	    	<table style="width:100%">
			<tr>
			<th align="left">
			<h3>Rate Limiting:<a href='<?php echo esc_html($two_factor_premium_doc['Rate Limiting']);?>' target="_blank"><span class="	dashicons dashicons-external"></span></a>
				<br>
				<p><i class="mo_lla_not_bold">This will protect your Website from Dos attack and block request after a limit exceed.</i></p>
	  		</th>
	  		<th align="right">
		  		<label class='mo_lla_switch'>
				 <input type=checkbox id='rateL' name='rateL' />
				 <span class='mo_lla_slider mo_lla_round'></span>
				</label></th></h3></tr></table>
		</div>
			<div name = 'rateLFD' id ='rateLFD'>
		  <table style="width: 100%"> 
		  </h3>
		  <tr><th align="left">
		  <h3>Block user after:</th>
		  <th align="center"><input type="number" name="req" id="req" required min="1" style="width: 400px" />
			<i class="mo_lla_not_bold">Requests/min</i></h3></th>
		<th align="right">
		<h3>action
		<select id="action">
		  <option value="ThrottleIP">Throttle IP</option>
		  <option value="BlockIP">Block IP</option>
		</select>
		</h3>
		</th></tr>
		<tr><th></th>
		<th align="center">
			<br><input type="button" name="saveRateL" id="saveRateL" value="Save" class="button button-primary button-large">
			</th>
		</tr>
		</table>
		</form>
		</div>
	</div>