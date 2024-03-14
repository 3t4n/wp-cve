<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
?>
	<style>
		#choose {
			font-size: 2em;
			line-height: 1;
			margin-top: 75px;
			text-align: center;
		}

		#selector {
			margin-top: 20px;
		}
	</style>
	<form id="selector">
		<?php
		// Maintain query string
		foreach ($_GET as $key => $value) {
			if (is_array($value)) {
				foreach ($value as $v) {
					echo '<input type="hidden" name="' . esc_attr(sanitize_text_field($key)) . '[]" value="' . esc_attr(sanitize_text_field($v)) . '" />';
				}
			} else {
				echo '<input type="hidden" name="' . esc_attr(sanitize_text_field($key)) . '" value="' . esc_attr(sanitize_text_field($value)) . '" />';
			}
		}

		$gateways = WC()->payment_gateways->payment_gateways();

		?>
		<label for="gateway">Gateway: </label>
		<select id="gateway" name="gateway" onchange='jQuery("#selector").trigger("submit")'>
			<option value="0">------</option>
			<?php foreach ($gateways as $gateway) {
			?>
				<option
					value="<?= $gateway->id ?>" <?php selected($_GET['gateway'], $gateway->id) ?>><?= $gateway->method_title; ?></option>
			<?php
		} ?>
		</select>
	</form>
<?php if (!$selected_gateway) {
			?>
	<div id="choose">
		Choose a gateway to view stats
	</div>
<?php
		} ?>
