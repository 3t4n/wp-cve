<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
?>
	<style>
		#choose-user {
			font-size: 2em;
			line-height: 1;
			margin-top: 75px;
			text-align: center;
		}

		#user_selector {
			margin-top: 20px;
		}
	</style>
	<form id="user_selector">
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
		?>
		<label for="user">User: </label>
		<select id="user" name="user" onchange='jQuery("#user_selector").trigger("submit")'>
			<option value="0">------</option>
			<?php
			$users = get_users([
				'role__in' => ['shop_manager', 'administrator', 'cashier']
			]);
			foreach ($users as $user) {
				/* @var WP_User $user */ ?>
				<option
					value="<?= $user->ID ?>" <?php selected($_GET['user'], $user->ID) ?>><?= $user->display_name; ?></option>
			<?php
			} ?>
		</select>
	</form>
<?php if (!$selected_user instanceof \WP_User) {
				?>
	<div id="choose-user">
		Choose a user to view stats
	</div>
<?php
			} ?>
