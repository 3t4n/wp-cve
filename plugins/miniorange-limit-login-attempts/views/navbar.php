	<div class="mo-lla-plugin-container">
		<div class="mopns-nav-container">
			<div class="molla-nav-text">
				<div id="molla-small-logo" class="molla-miniorange-logo"></div><div>Limit Login Attempts</div>
			</div>
			<div class="molla-nav-bar">
				<a class="molla-dashboard-button" href="<?php echo esc_html($dashboard_url)	?>"><div class="dashicons dashicons-dashboard molla-nav-item"></div>Dashboard</a>
				<a class="molla-dashboard-button" href="<?php echo esc_html($login_and_spam) ?>"><div class="dashicons dashicons-admin-generic molla-nav-item"></div>Settings</a>
				<a class="molla-dashboard-button" href="<?php echo esc_html($reports_url)	?>"><div class="dashicons dashicons-media-spreadsheet molla-nav-item"></div>Reports</a> 
				<a class="molla-dashboard-button" href="<?php echo esc_html($license_url)	?>"><div class="dashicons dashicons-money-alt molla-nav-item"></div>Upgrade</a> 
				<a class="molla-dashboard-button" href="<?php echo esc_html($profile_url)	?>"><div class="dashicons dashicons-admin-users molla-nav-item"></div>Account</a> 
			</div>
		</div>

	<div class="molla-support-div">
		<div id="molla-slide-support" class="molla-slide-support-btn dashicons dashicons-arrow-right-alt2"></div>
		<div class="molla-support-header">
				<div class="dashicons dashicons-shield">
				</div>
		</div>
		<div class="molla-support-content">
						<?php include $mo_lla_dirName . 'controllers'.DIRECTORY_SEPARATOR.'support.php'; ?>
		</div>
		<div class="molla-support-footer">
		</div>
	</div>

