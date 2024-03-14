<?php

use Carbon\Carbon;

class FrontendMenu {
	public static function create() {
		add_menu_page('WP Lock', 'WP Lock', 'manage_options', 'wp-lock', array('FrontendMenu', 'display'));
	}

	public static function getOptions() {
		$mode = get_option('wpLockMode');
		return array(
			'mode' => $mode,
			'eUntil' => ($mode == 4) ? get_option('wpLockUntil') : '',
			'dUntil' => ($mode == 2) ? get_option('wpLockUntil') : '',
			'eFor' => ($mode == 3) ? get_option('wpLockFor') : '',
			'eForI' => ($mode == 3) ? get_option('wpLockForI') : '',
			'dFor' => ($mode == 2) ? get_option('wpLockFor') : '',
			'dForI' => ($mode == 2) ? get_option('wpLockForI') : '',

			'dFrom' => ($mode == 4) ? get_option('wpLockFrom') : '',
			'dTo' => ($mode == 4) ? get_option('wpLockTo') : '',
			'eFrom' => ($mode == 5) ? get_option('wpLockFrom') : '',
			'eTo' => ($mode == 5) ? get_option('wpLockTo') : '',

			'logo' => get_option('wpLockLogo'),
			'lastUpdated' => get_option('wpLockUpdated'),
			'message' => get_option('wpLockMessage')
		);
	}

	public static function display() {
		$options = self::getOptions();
?>
		<div style="text-align:left;" class="wrap">
			<h1>AWEOS WP Lock</h1>
			<p>Lock your Website from external access. With AWEOS WP Lock you can block acess for non-registered users.<br>You can also define a specific timespan to lock or unlock your website.<br>
				This plugin was developed by the advertising agency <a href="https://aweos.de" target="_blank">AWEOS</a>.</p>
			<form method="post" action="/wp-admin/admin-post.php?action=update_wplock_settings">
				<div style="display: none;" id="wplock-mode"><?php echo $options['mode']; ?></div>
				<div style="display: none;" id="wplock-eFor"><?php echo $options['eFor']; ?></div>
				<div style="display: none;" id="wplock-eForI"><?php echo $options['eForI']; ?></div>
				<div style="display: none;" id="wplock-dFor"><?php echo $options['dFor']; ?></div>
				<div style="display: none;" id="wplock-dForI"><?php echo $options['dForI']; ?></div>
				<div style="display: none;" id="wplock-eFrom"><?php echo $options['eFrom']; ?></div>
				<div style="display: none;" id="wplock-eTo"><?php echo $options['eTo']; ?></div>
				<div style="display: none;" id="wplock-dFrom"><?php echo $options['dFrom']; ?></div>
				<div style="display: none;" id="wplock-dTo"><?php echo $options['dTo']; ?></div>
				<div style="display: none;" id="wplock-lastUpdated"><?php echo $options['lastUpdated']; ?></div>
				<table class="wp-list-table widefat fixed posts right_margin" id="vuewplockroot">
					<thead>
						<tr>
							<th>
							<button type="submit" class="button button-primary"><span class="save_button_span">Save Changes</span></button>
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<table class="form-table lock-table">
									<div v-show="option == 0" style="display: none;" class="warning-permanently">
										<p class="warning-message">
											<strong>Warning!</strong> We highly recommend to disable WP-Lock only on live sites.
											<br>
											<div class="warning-controls">
												<p @click="unlockFor2Hours" class="warning-button start">Unlock for 2 hours</p>
												<p @click="unlockFor4Hours" class="warning-button">Unlock for 4 hours</p>
											</div>
										</p>
									</div>
									<tr>
										<td>
											<label>Unlock site permanently</label>
										</td>
										<td>
										<input type="radio" name="wplock-plugin-mode" class="wplock-status" value=0 v-model="option">&nbsp;
										</td>
									</tr>
									<tr>
										<td>
											<label>Lock site permanently</label>
										</td>
										<td>
											<input type="radio" name="wplock-plugin-mode" value=1 class="wplock-status" v-model="option">&nbsp;
										</td>
									</tr>
								<!--	<tr>
										<td>
											<label>Disable until</label>
										</td>
										<td>
											<input type="radio" name="wplock-plugin-mode" value=2 class="wplock-status" <?php if($options['mode'] == 2): ?> checked <?php endif; ?>>&nbsp;
										<input type="text" name="wplock-until" class="datetimepicker wplock-value" value="<?php echo $options['dUntil']; ?>">
											&nbsp; then enable
										</td>
									</tr> -->
									<tr>
										<td>
											<label>Unlock site for</label>
										</td>
										<td v-bind:class="{'active': option == 2, 'inactive': option != 2}">
											<input type="radio" name="wplock-plugin-mode" value=2 class="wplock-status" v-model="option">&nbsp;
											<input type="number" name="wplock-for" class="wplock-value" v-model="disableUntilValue" v-bind:disabled="option != 2">&nbsp;
											<select name="wplock-for-i" class="wplock-value" v-bind:disabled="option != 2" v-model="disableUntilValueI">
												<option value="0">Minutes</option>
												<option value="1">Hours</option>
												<option value="2">Days</option>
												<option value="3">Weeks</option>
											</select>
											&nbsp; then lock</br>
											<div v-show="disableUntilDate != ''" style="display: none;">Site will be unlocked until {{ disableUntilDate }} </div>
										</td>
									</tr>
									<tr>
										<td>
											<label>Lock site for</label>
										</td>
										<td v-bind:class="{'active': option == 3, 'inactive': option != 3}">
											<input type="radio" name="wplock-plugin-mode" value=3 class="wplock-status" v-model="option">&nbsp;
											<input type="number" name="wplock-for" class="wplock-value" v-bind:disabled="option != 3" v-model="enableUntilValue">&nbsp;
											<select name="wplock-for-i" class="wplock-value" v-model="enableUntilValueI" v-bind:disabled="option != 3">
												<option value="0">Minutes</option>
												<option value="1">Hours</option>
												<option value="2">Days</option>
												<option value="3">Weeks</option>
											</select>
											&nbsp; then unlock
											<div v-show="enableUntilDate != ''" style="display: none;">Site will be locked until {{ enableUntilDate }}</div>
										</td>
									</tr>
									<tr>
										<td>
											<label>Unlock site from...to</label>
										</td>
										<td v-bind:class="{'active': option == 4, 'inactive': option != 4}">
											<input type="radio" name="wplock-plugin-mode" value=4 class="wplock-status" v-model="option">&nbsp;
											<input type="datetime-local" name="wplock-from" v-model="disableFrom" class="wplock-value" v-bind:disabled="option != 4">
											<input type="datetime-local" name="wplock-to" v-model="disableTo" v-bind:disabled="option != 4">
											&nbsp; then lock
										</td>
									</tr>
									<tr>
										<td>
											<label>Lock site from...to</label>
										</td>
										<td v-bind:class="{'active': option == 5, 'inactive': option != 5}">
											<input type="radio" name="wplock-plugin-mode" value=5 class="wplock-status" v-model="option">&nbsp;
											<input type="datetime-local" name="wplock-from" value="<?php echo $options['eFrom']; ?>" class="wplock-value" v-bind:disabled="option != 5">
											<input type="datetime-local" name="wplock-to" value="<?php echo $options['eTo']; ?>" class="wplock-value" v-bind:disabled="option != 5">
											&nbsp; then unlock
										</td>
									</tr>
									<tr>
										<td>
											<label>Display text</label>
										</td>
										<td>
											<?php wp_editor($options['message'], 'wplock-message', array('name' => 'wplock-message')); ?>
										</td>
									</tr>
									<tr>
										<td>
											<label>Image</label>
										</td>
										<td>
									        <input type="text" name="wpLockLogo" value="<?php echo $options['logo'] ?>">
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<th>
							<button type="submit" class="button button-primary"><span class="save_button_span">Save Changes</span></button>
							</th>
						</tr>
					</tfoot>
				</table>
				<?php wp_nonce_field('wplock_options_nonce','wplock_options_nonce'); ?>
			</form>
		</div>
		<?php
	}

	public static function updateValues() {
		if (!wp_verify_nonce($_POST['wplock_options_nonce'], 'wplock_options_nonce')) {
			die('Security check failed! Nonce is invalid!');
		}

		self::resetValues();
		update_option('wpLockUpdated', Carbon::parse('now', 'Europe/Berlin')->toDateTimeString());
		update_option('wpLockMode', $_POST['wplock-plugin-mode']);
		update_option('wpLockMessage', $_POST['wplock-message']);
		update_option('wpLockLogo', $_POST['wpLockLogo']);

		switch ($_POST['wplock-plugin-mode']) {
			case 2:
			case 3:
				update_option('wpLockFor', $_POST['wplock-for']);
				update_option('wpLockForI', $_POST['wplock-for-i']);
				break;
			case 4:
			case 5:
				update_option('wpLockFrom', $_POST['wplock-from']);
				update_option('wpLockTo', $_POST['wplock-to']);
				break;
		}
		wp_redirect(home_url('/wp-admin/admin.php?page=wp-lock'));
	}

	public static function enablePermanently() {
		self::resetValues();
		update_option('wpLockMode', 1);
	}

	public static function disablePermanently() {
		self::resetValues();
		update_option('wpLockMode', 0);
	}

	private static function resetValues() {
		update_option('wpLockUntil', '');
		update_option('wpLockFor', '');
		update_option('wpLockForI', '');
		update_option('wpLockFrom', '');
		update_option('wpLockTo', '');
	}
}
