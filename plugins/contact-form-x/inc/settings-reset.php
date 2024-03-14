<?php // Contact Form X Reset Settings

if (!defined('ABSPATH')) exit;

function contactformx_admin_notice() {
	
	$screen_id = contactformx_get_current_screen_id();
	
	if ($screen_id === 'settings_page_contactformx') {
		
		if (isset($_GET['reset-options'])) {
			
			if ($_GET['reset-options'] === 'true') : ?>
				
				<div class="notice notice-success is-dismissible"><p><strong><?php esc_html_e('Default options restored.', 'contact-form-x'); ?></strong></p></div>
				
			<?php else : ?>
				
				<div class="notice notice-info is-dismissible"><p><strong><?php esc_html_e('No changes made.', 'contact-form-x'); ?></strong></p></div>
				
			<?php endif;
			
		}
		
		if (isset($_GET['reset-widget'])) {
			
			if ($_GET['reset-widget'] === 'true') : ?>
				
				<div class="notice notice-success is-dismissible"><p><strong><?php esc_html_e('All email data deleted.', 'contact-form-x'); ?></strong></p></div>
				
			<?php else : ?>
				
				<div class="notice notice-info is-dismissible"><p><strong><?php esc_html_e('No changes made.', 'contact-form-x'); ?></strong></p></div>
				
			<?php endif;
			
		}
		
		if (isset($_GET['drop-table'])) {
			
			if ($_GET['drop-table'] === 'true') : ?>
				
				<div class="notice notice-success is-dismissible"><p><strong><?php esc_html_e('CFX table removed from database.', 'contact-form-x'); ?></strong></p></div>
				
			<?php else : ?>
				
				<div class="notice notice-info is-dismissible"><p><strong><?php esc_html_e('No changes made.', 'contact-form-x'); ?></strong></p></div>
				
			<?php endif;
			
		}
		
		if (isset($_GET['delete-recipient'])) {
			
			if ($_GET['delete-recipient'] === 'true') : ?>
				
				<div class="notice notice-success is-dismissible"><p><strong><?php esc_html_e('Recipient Deleted.', 'contact-form-x'); ?></strong></p></div>
				
			<?php else : ?>
				
				<div class="notice notice-info is-dismissible"><p><strong><?php esc_html_e('No changes made.', 'contact-form-x'); ?></strong></p></div>
				
			<?php endif;
			
		}
		
		if (!contactformx_check_date_expired() && !contactformx_dismiss_notice_check()) {
			
			$tabs = array('tab1', 'tab2', 'tab3', 'tab4', 'tab5');
			
			$tab = (isset($_GET['tab']) && in_array($_GET['tab'], $tabs)) ? $_GET['tab'] : 'tab1';
			
			?>
			
			<div class="notice notice-success">
				<p>
					<strong><?php esc_html_e('Go Pro!', 'contact-form-x'); ?></strong> 
					<?php esc_html_e('Take 30% OFF any of our', 'contact-form-x'); ?> 
					<a target="_blank" rel="noopener noreferrer" href="https://plugin-planet.com/"><?php esc_html_e('Pro WordPress plugins', 'contact-form-x'); ?></a> 
					<?php esc_html_e('and', 'contact-form-x'); ?> 
					<a target="_blank" rel="noopener noreferrer" href="https://books.perishablepress.com/"><?php esc_html_e('books', 'contact-form-x'); ?></a>. 
					<?php esc_html_e('Apply code', 'contact-form-x'); ?> <code>PLANET24</code> <?php esc_html_e('at checkout. Sale ends 5/25/24.', 'contact-form-x'); ?> 
					<?php echo contactformx_dismiss_notice_link($tab); ?>
				</p>
			</div>
			
			<?php
			
		}
		
	}
	
}

//

function contactformx_dismiss_notice_activate() {
	
	delete_option('cfx-dismiss-notice');
	
}

function contactformx_dismiss_notice_version() {
	
	$version_current = CONTACTFORMX_VERSION;
	
	$version_previous = get_option('cfx-dismiss-notice');
	
	$version_previous = ($version_previous) ? $version_previous : $version_current;
	
	if (version_compare($version_current, $version_previous, '>')) {
		
		delete_option('cfx-dismiss-notice');
		
	}
	
}

function contactformx_dismiss_notice_check() {
	
	$check = get_option('cfx-dismiss-notice');
	
	return ($check) ? true : false;
	
}

function contactformx_dismiss_notice_save() {
	
	if (isset($_GET['dismiss-notice-verify']) && wp_verify_nonce($_GET['dismiss-notice-verify'], 'contactformx_dismiss_notice')) {
		
		if (!current_user_can('manage_options')) exit;
		
		$result = update_option('cfx-dismiss-notice', CONTACTFORMX_VERSION, false);
		
		$result = $result ? 'true' : 'false';
		
		$tabs = array('tab1', 'tab2', 'tab3', 'tab4', 'tab5');
		
		$tab = (isset($_GET['tab']) && in_array($_GET['tab'], $tabs)) ? $_GET['tab'] : 'tab1';
		
		$location = admin_url('options-general.php?page=contactformx&tab='. $tab .'&dismiss-notice='. $result);
		
		wp_redirect($location);
		
		exit;
		
	}
	
}

function contactformx_dismiss_notice_link($tab) {
	
	$nonce = wp_create_nonce('contactformx_dismiss_notice');
	
	$href  = add_query_arg(array('dismiss-notice-verify' => $nonce), admin_url('options-general.php?page=contactformx&tab='. $tab));
	
	$label = esc_html__('Dismiss', 'contact-form-x');
	
	echo '<a class="cfx-dismiss-notice" href="'. esc_url($href) .'">'. esc_html($label) .'</a>';
	
}

function contactformx_check_date_expired() {
	
	$expires = apply_filters('contactformx_check_date_expired', '2024-05-25');
	
	return (new DateTime() > new DateTime($expires)) ? true : false;
	
}

//

function contactformx_reset_options() {
	
	if (isset($_GET['reset-options-verify']) && wp_verify_nonce($_GET['reset-options-verify'], 'contactformx_reset_options')) {
		
		if (!current_user_can('manage_options')) exit;
		
		$sections = array('email', 'form', 'customize', 'appearance', 'advanced');
		
		foreach ($sections as $section) {
			
			$default = contactformx_options($section);
			
			${'delete_'. $section} = delete_option('contactformx_'. $section);
			
		}
		
		$result = 'false';
		
		foreach ($sections as $section) {
			
			if (${'delete_'. $section}) {
				
				$result = 'true';
				
			}
			
		}
		
		$location = admin_url('options-general.php?page=contactformx&reset-options='. $result);
		
		wp_redirect($location);
		
		exit;
		
	}
	
}

function contactformx_reset_widget() {
	
	if (isset($_GET['reset-widget-verify']) && wp_verify_nonce($_GET['reset-widget-verify'], 'contactformx_reset_widget')) {
		
		if (!current_user_can('manage_options')) exit;
		
		$result = false;
		
		$emails = get_posts(array('post_type' => 'cfx_email', 'post_status' => 'any', 'posts_per_page' => -1));
		
		foreach ($emails as $email) $result = wp_delete_post($email->ID, true);
		
		$result = $result ? 'true' : 'false';
		
		$location = admin_url('options-general.php?page=contactformx&tab=tab5&reset-widget='. $result);
		
		wp_redirect($location);
		
		exit;
		
	}
	
}

function contactformx_reset_widget_legacy() {
	
	if (isset($_GET['reset-widget-verify-legacy']) && wp_verify_nonce($_GET['reset-widget-verify-legacy'], 'contactformx_reset_widget_legacy')) {
		
		if (!current_user_can('manage_options')) exit;
		
		global $wpdb;
		
		$table = $wpdb->prefix .'cfx_email';
		
		$delete = $wpdb->query("TRUNCATE TABLE ". $table);
		
		$result = $delete ? 'true' : 'false';
		
		$location = admin_url('options-general.php?page=contactformx&tab=tab5&reset-widget='. $result);
		
		wp_redirect($location);
		
		exit;
		
	}
	
}

function contactformx_drop_table_legacy() {
	
	if (isset($_GET['drop-table-verify-legacy']) && wp_verify_nonce($_GET['drop-table-verify-legacy'], 'contactformx_drop_table_legacy')) {
		
		if (!current_user_can('manage_options')) exit;
		
		global $wpdb;
		
		$table = $wpdb->prefix .'cfx_email';
		
		$delete = $wpdb->query("DROP TABLE IF EXISTS ". $table);
		
		$result = $delete ? 'true' : 'false';
		
		$location = admin_url('options-general.php?page=contactformx&tab=tab5&drop-table='. $result);
		
		wp_redirect($location);
		
		exit;
		
	}
	
}

function contactformx_delete_recipient() {
	
	if (isset($_GET['delete-recipient-verify']) && wp_verify_nonce($_GET['delete-recipient-verify'], 'contactformx_delete_recipient')) {
		
		if (!current_user_can('manage_options')) exit;
		
		$id = isset($_GET['recipient']) ? $_GET['recipient'] : null;
		
		$options = contactformx_options('email');
		
		if (isset($options['recipient-'. $id])) unset($options['recipient-'. $id]);
		
		if (isset($options['number-recipients'])) {
			
			$options['number-recipients'] = (string) --$options['number-recipients'];
			
			if (intval($options['number-recipients']) <= 0) $options['number-recipients'] = '1';
			
		} else {
			
			$options['number-recipients'] = '1';
			
		}
		
		$update = update_option('contactformx_email', $options);
		
		$result = $update ? 'true' : 'false';
		
		$location = admin_url('options-general.php?page=contactformx&delete-recipient='. $result);
		
		wp_redirect($location);
		
		exit;
		
	}
	
}
