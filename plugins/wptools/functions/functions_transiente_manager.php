<?php
add_action('admin_init',  'wptools_process_transient_actions');
function wptools_transients_admin()
{
	global $wptools_checkversion;
	wptools_show_logo();
	$search      = !empty($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
	$page        = isset($_GET['paged'])   ? absint($_GET['paged'])      : 1;
	$per_page    = 20;
	$offset      = $per_page * ($page - 1);
	$count       = wptools_get_total_transients($search);
	$pages       = ceil($count / $per_page);
	$args        = array(
		'search' => $search,
		'offset' => $offset,
		'number' => $per_page
	);
	$pagination  = paginate_links(array(
		'base'   => 'admin.php?%_%',
		'format' => '&paged=%#%',
		'total'  => $pages,
		'current' => $page
	));
	// set_transient('termina', DAY_IN_SECONDS, DAY_IN_SECONDS);
	// $fim = 20; // seconds...
	// set_transient('termina', $fim, $fim);
	//delete_transient ('termina');
	$transients = wptools_get_transients($args);
	// global $wpdb;
?>
	<div class="wrap">
		<h2><?php esc_attr_e('Show Transients', 'wptools'); ?></h2>
		<form method="post" class="alignleft" onsubmit="return confirm('<?php esc_attr_e('Do you really want delete all transients?','wptools');?>');">&nbsp;
			<input type="hidden" name="action" value="wptools_delete_all_transients" />
			<input type="hidden" name="transient" value="all" />
			<?php wp_nonce_field('transient_manager'); 
			if(empty($wptools_checkversion)) {
           echo '<strong>';
           echo esc_attr_e("Option to delete all Transients or expired Transients available in Premium Version.", "wptools");
           echo '</strong>';
        }
        else {
           ?>
		   <input type="submit" class="button-primary" value="<?php esc_attr_e('Delete All Transients', 'wptools'); ?>" />
        <?php }  ?>
		</form>
		<form method="post" class="alignleft" onsubmit="return confirm('<?php esc_attr_e('Do you really want delete all expired?','wptools');?>');">&nbsp;
			<input type="hidden" name="action" value="wptools_delete_all_expired" />
			<input type="hidden" name="transient" value="all" />
			<?php wp_nonce_field('transient_manager'); ?>
			&nbsp;&nbsp;
            <?php
		  	if(empty(!$wptools_checkversion)) { ?> 
			   <input type="submit" class="button-primary" value="<?php esc_attr_e('Delete All Expired', 'wptools'); ?>" />
            <?php } ?>
		</form>
		<br><br>
		<a href="https://wptoolsplugin.com/delete-transients/"><?php esc_attr_e('Learn More', 'wptools'); ?></a>
		<br>
		<form method="post">
			<div class="tablenav top">
				<div class="tablenav-pages">
					<span class="displaying-num"><?php printf(_n('%d Transient', '%d Transients', $count, 'wptools'), $count); ?></span>
					<span class="pagination-links"><?php
							$allowed_atts = array(
								'align'      => array(),
								'class'      => array(),
								'type'       => array(),
								'id'         => array(),
								'dir'        => array(),
								'lang'       => array(),
								'style'      => array(),
								'xml:lang'   => array(),
								'src'        => array(),
								'alt'        => array(),
								'href'       => array(),
								'rel'        => array(),
								'rev'        => array(),
								'target'     => array(),
								'novalidate' => array(),
								'type'       => array(),
								'value'      => array(),
								'name'       => array(),
								'tabindex'   => array(),
								'action'     => array(),
								'method'     => array(),
								'for'        => array(),
								'width'      => array(),
								'height'     => array(),
								'data'       => array(),
								'title'      => array(),
					
								'checked' => array(),
								'selected' => array(),
					
					
							);
					
					
					
					
							$my_allowed['form'] = $allowed_atts;
							$my_allowed['select'] = $allowed_atts;
							// select options
							$my_allowed['option'] = $allowed_atts;
							$my_allowed['style'] = $allowed_atts;
							$my_allowed['label'] = $allowed_atts;
							$my_allowed['input'] = $allowed_atts;
							$my_allowed['textarea'] = $allowed_atts;
					
							//more...future...
							$allowedposttags['form']     = $allowed_atts;
							$allowedposttags['label']    = $allowed_atts;
							$allowedposttags['input']    = $allowed_atts;
							$allowedposttags['textarea'] = $allowed_atts;
							$allowedposttags['iframe']   = $allowed_atts;
							$allowedposttags['script']   = $allowed_atts;
							$allowedposttags['style']    = $allowed_atts;
							$allowedposttags['strong']   = $allowed_atts;
							$allowedposttags['small']    = $allowed_atts;
							$allowedposttags['table']    = $allowed_atts;
							$allowedposttags['span']     = $allowed_atts;
							$allowedposttags['abbr']     = $allowed_atts;
							$allowedposttags['code']     = $allowed_atts;
							$allowedposttags['pre']      = $allowed_atts;
							$allowedposttags['div']      = $allowed_atts;
							$allowedposttags['img']      = $allowed_atts;
							$allowedposttags['h1']       = $allowed_atts;
							$allowedposttags['h2']       = $allowed_atts;
							$allowedposttags['h3']       = $allowed_atts;
							$allowedposttags['h4']       = $allowed_atts;
							$allowedposttags['h5']       = $allowed_atts;
							$allowedposttags['h6']       = $allowed_atts;
							$allowedposttags['ol']       = $allowed_atts;
							$allowedposttags['ul']       = $allowed_atts;
							$allowedposttags['li']       = $allowed_atts;
							$allowedposttags['em']       = $allowed_atts;
							$allowedposttags['hr']       = $allowed_atts;
							$allowedposttags['br']       = $allowed_atts;
							$allowedposttags['tr']       = $allowed_atts;
							$allowedposttags['td']       = $allowed_atts;
							$allowedposttags['p']        = $allowed_atts;
							$allowedposttags['a']        = $allowed_atts;
							$allowedposttags['b']        = $allowed_atts;
							$allowedposttags['i']        = $allowed_atts;
					
							echo wp_kses($pagination, $my_allowed);
					        //echo $pagination; 

					?></span>
				</div>
				<br class="clear">
			</div>
			<table class="wp-list-table wptools_admin_table fixed posts striped" align="center">
				<thead>
					<tr>
						<th style="width:40px;"><?php esc_attr_e('ID', 'wptools'); ?></th>
						<th><?php esc_attr_e('Name', 'wptools'); ?></th>
						<th><?php esc_attr_e('Expires In', 'wptools'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ($transients) : ?>
						<?php foreach ($transients as $transient) :
						?>
							<tr>
								<td><?php echo esc_attr($transient->option_id); ?></td>
								<td><?php echo esc_attr(wptools_get_transient_name($transient)); ?></td>
								<td><?php echo esc_attr(wptools_get_transient_expiration($transient)); ?></td>
							</tr>
						<?php endforeach; ?>
					<?php else : ?>
						<tr>
							<td colspan="5"><?php esc_attr_e('No transients found', 'wptools'); ?></td>
						<?php endif; ?>
				</tbody>
			</table>
			<?php if ($pages > 1) : ?>
				<div class="tablenav bottom">
					<div class="tablenav-pages">
						<span class="displaying-num"><?php printf(_n('%d Transient', '%d Transients', esc_attr($count), 'wptools'), esc_attr($count)); ?></span>
						<span class="pagination-links"><?php echo wp_kses($pagination, $my_allowed); ?></span>
					</div>
				</div>
			<?php endif; ?>
		</form>
	</div>
<?php
}
function wptools_get_transients($args = array())
{
	global $wpdb;
	$defaults = array(
		'offset' => 0,
		'number' => 20,
		'search' => ''
	);
	$args       = wp_parse_args($args, $defaults);
	$sql = "SELECT * FROM $wpdb->options WHERE option_name LIKE '%\_transient\_%' AND option_name NOT LIKE '%\_transient\_timeout%'";
	$offset = absint($args['offset']);
	$number = absint($args['number']);
	$sql .= " ORDER BY option_id DESC LIMIT $offset,$number;";
	$transients = $wpdb->get_results($sql);
	return $transients;
}
function wptools_get_total_transients($search = '')
{
	global $wpdb;
	$count = $wpdb->get_var("SELECT count(option_id) FROM $wpdb->options WHERE option_name LIKE '%\_transient\_%' AND option_name NOT LIKE '%\_transient\_timeout%'");
	return $count;
}
function wptools_get_transient_name($transient)
{
	$length = false !== strpos($transient->option_name, 'site_transient_') ? 16 : 11;
	return substr($transient->option_name, $length, strlen($transient->option_name));
}
function wptools_wptools_get_transient_expiration_time($transient)
{
	if (false !== strpos($transient->option_name, 'site_transient_')) {
		$time = get_option('_site_transient_timeout_' . wptools_get_transient_name($transient));
	} else {
		$time = get_option('_transient_timeout_' . wptools_get_transient_name($transient));
	}
	return $time;
}
function wptools_get_transient_expiration($transient)
{
	$time_now   = time();
	$expiration = wptools_wptools_get_transient_expiration_time($transient);
	if (empty($expiration)) {
		return __('Does not expire', 'wptools');
	}
	if ($time_now > $expiration) {
		return __('Expired', 'wptools');
	}
	return human_time_diff($time_now, $expiration);
}
function wptools_process_transient_actions()
{
	if (empty($_REQUEST['action'])) {
		return;
	}
	if (!current_user_can('manage_options')) {
		return;
	}
	if ($_REQUEST['action'] == 'wptools_delete_all_transients') {
		if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'transient_manager')) {
			return;
		}
		wptools_delete_all_transients();
		wp_safe_redirect(admin_url('admin.php?page=wptools_options33'));
		exit;
	}
	if ($_REQUEST['action'] == 'wptools_delete_all_expired') {
		if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'transient_manager')) {
			return;
		}
		wptools_delete_all_expired();
		wp_safe_redirect(admin_url('admin.php?page=wptools_options33'));
		exit;
	}
}
function wptools_delete_all_transients()
{
	global $wpdb;
	$count = $wpdb->query(
		"DELETE FROM $wpdb->options
			WHERE option_name LIKE '%\_transient\_%'"
	);
	return $count;
}
function wptools_delete_all_expired()
{
	global $wpdb;
	//$sql = "SELECT * FROM $wpdb->options WHERE option_name LIKE '%\_transient\_%' AND option_name NOT LIKE '%\_transient\_timeout%'";
	$sql = "SELECT * FROM $wpdb->options WHERE option_name LIKE '%\_transient\_%'";
	$transients = $wpdb->get_results($sql);
	foreach ($transients as $transient) {
		if (wptools_get_transient_expiration($transient) == "Expired") {
			$count = $wpdb->query(
				"DELETE FROM $wpdb->options
					WHERE option_name LIKE '%$transient->option_name%'"
			);
		}
	}
	return $count;
}