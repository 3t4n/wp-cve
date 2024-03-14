<p class="autopay-blik-method-description">
	<?php _e($params['description'], 'pay-wp'); ?>
</p>
<label id="blik_code_label">
	<input
		class="autopay-blik-code"
		name="blik_code"
		type="number"
		step="1"
		maxlength="6"
		minlength="6"
		min="000000"
		max="999999"
		value=""
		placeholder="000000"
		oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
	>
	<p id="blik_msg_container" class="blik_msg_container"></p>
</label>

