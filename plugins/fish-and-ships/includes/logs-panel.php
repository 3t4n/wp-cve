<?php
/**
 * The logs panel. 
 * Since 1.2.6 this file can generate the logs pane on standard load and AJAX calls
 *
 * It will show logs, delete old ones and does the user manual deletion also.
 *
 * Logs will be deleted after 7 days, but you can change this defining this constant into your wp-config.php:<br />
 * define('WC_FNS_DAYS_LOG', 15); // for 15 days
 *
 * @package Fish and Ships
 * @since 1.0.0
 * @version 1.4.2
 */

defined( 'ABSPATH' ) || exit;

global $Fish_n_Ships;

// The panel

// Get the logs index
$logs_index = get_option('wc_fns_logs_index', array() );

// Remove logs (user selected & expirated)
$del_logs = '';
$deleted = 0;

if ( isset($_POST['fns-remove_logs']) ) {

	// We will check the WC nonce
	check_admin_referer( 'woocommerce-settings' );

	$del_logs = isset($_POST['log']) ? $_POST['log'] : '';
	unset($_POST['fns-remove_logs']); // prevent multiinstance repeat
}


// Prevent too much logs, show error (since 1.4.2):
if ( count($logs_index) > 750 && $this->write_logs == 'everyone' ) {
	?>
	<div class="notice notice-error inline"><h3>Please, disable write logs or set them only for Admin and Shop managers (too many logs will affect performance).</h3></div>
	<?php
}
// ...cut if more than 1000
if ( count($logs_index) > 1000 ) {
	
	$n = 0;
	$logs_index_aux = array();
	
	foreach ($logs_index as $key => $val) {
		$n++;
		$logs_index_aux[$key] = $val;
		if ($n > 1000) break;
	}
	$logs_index = $logs_index_aux;
}
// ...and tell about
if ( count($logs_index) >= 1000 ) {
	$html .= '<div class="notice notice-warning inline"><p><strong>Logs are limited to 1000 for performance reasons.</p></div>';
}
// End too much logs prevention


foreach ($logs_index as $key=>$log) {
	// Remove from index the missing transients (WP expired)
	if (get_transient($log['name']) === false) {
		unset ($logs_index[$key]);
	} else {
		if (is_array($del_logs)) {
			// Remove transient by user petition
			if ( $Fish_n_Ships->is_log_name( $log['name'] ) && in_array( $log['name'], $del_logs, true ) ) {
				unset ($logs_index[$key]);
				delete_transient($log['name']);
				$deleted ++;
			}
		}
	}
}
if ($deleted !=0) echo '<div id="message" class="updated notice inline"><p>' . esc_html( sprintf(__('%s Logs have been deleted.', 'fish-and-ships'), $deleted ) ) . '</p></div>';

// Save updated index
update_option('wc_fns_logs_index', $logs_index, false);

// Remove other instances logs
foreach ($logs_index as $key=>$log) {
	if ($log['instance_id'] != $instance_id) unset ($logs_index[$key]);
}

// Logs pagination vars
$logs_per_pag = isset($_REQUEST['fnslogsperpag']) ?  intval( $_REQUEST['fnslogsperpag'] ) : 0;
$logs_pag     = isset($_REQUEST['fnslogspag']) ?     intval( $_REQUEST['fnslogspag'] )    : 0;

if ( $logs_per_pag  < 1 ) $logs_per_pag  = 10;
if ( $logs_pag      < 1 ) $logs_pag      = 1;

$first_log    = ( $logs_pag - 1 ) * $logs_per_pag + 1;

// Maybe there aren't logs on this page? Go previous page
while ( $logs_pag > 1 && $first_log > count($logs_index) ) {
	$logs_pag--;
	$first_log = ( $logs_pag - 1 ) * $logs_per_pag + 1;
}

$last_log     = $logs_pag * $logs_per_pag ;

$current_url = add_query_arg( array ('page' => 'wc-settings', 'tab' => 'shipping', 'instance_id' => intval($_REQUEST['instance_id']) ), admin_url('admin.php') );

$reload_url  = add_query_arg ( 'fnslogspag', $logs_pag, $current_url ) . '#fnslogs';

					  
// No logs yet
if ( count($logs_index) == 0 ) {

	$html .= '<div id="fnslogs"><div id="wc_fns_logs_list" class="updated woocommerce-message inline" style="display:none"><p>' 
			. esc_html__('This shipping method have not logs yet (or maybe you should refresh this list to see if there are new ones).', 'fish-and-ships')
			. '</p><p><a id="fns_logs_reload" href="'.esc_attr($reload_url).'" data-fns-logs-pag="' . esc_attr($logs_pag) . '" data-instance_id="'.esc_attr($instance_id).'" class="button button-small"><span class="dashicons dashicons-update"></span> ' . esc_html__('Refresh', 'fish-and-ships') . '</a></p></div></div>';
} else {

	// Table header
	$html .= '<table class="widefat striped" id="fnslogs">
	<thead>
		<tr><td class="manage-column column-cb check-column">
			<label class="screen-reader-text" for="cb-select-all">' . esc_html__('Select all') . '</label>
			<input class="cb-select-all" type="checkbox">
		</td>
		<th class="thin">' . esc_html_x('When', 'table cell title, human date', 'fish-and-ships') . '</th>
		<th class="thin">' . esc_html__('User') . '</th>
		<th class="thin">' . esc_html__('See', 'fish-and-ships') . '</th>
		<th class="thin">' . esc_html__('Shipping cost', 'fish-and-ships') . '</th>
		<th>' . esc_html_x('Cart Items', 'table cell title, number of items', 'fish-and-ships') . 
		'<a id="fns_logs_reload" href="'.esc_attr($reload_url).'" data-fns-logs-pag="' . esc_attr($logs_pag) . '" data-instance_id="'.esc_attr($instance_id).'" class="button button-small"><span class="dashicons dashicons-update"></span> ' . esc_html__('Refresh', 'fish-and-ships') . '</a></th>
	</thead>
	<tbody>';
	
	$log_name = '';
	
	if ( isset($_GET['fns_see_log']) && $Fish_n_Ships->is_log_name($_GET['fns_see_log']) ) {
		$log_name = sanitize_key($_GET['fns_see_log']);
	}

	// Reverse to show: newest before
	$n = 0;

	foreach (array_reverse($logs_index) as $log) {
		
		$n++;
		
		if ( $n >= $first_log && $n <= $last_log ) {
		
			$user = get_userdata($log['user_id']);

			$active = false;
			
			if ($log_name === $log['name']) {

				// Let's read the log
				$active = true;
				$log_details = get_transient($log['name']);
				if ( $log_details === false || !is_array($log_details) || count($log_details) == 0 ) $active = false;
			}
			
			// A row
			$html .= '<tr ' . ( $active ? 'class="fns-open-log loaded"' : '' ) . '>
						<th class="check-column"><input type="checkbox" name="log[]" value="' . esc_attr($log['name']) . '"></th>
						<td class="thin">' . esc_html( sprintf(__('%s ago'), human_time_diff( $log['time'], time() ) ) ) . '</td>
						<td class="thin">';
			
			if ($log['user_id'] == 0) {
				// Unknown user, unregistered
				$html .= '<em>unregistered</em>';
			} else if (!$user) {
				// Unknown user, maybe deleted, let's show the user ID
				$html .= '#'  .  $log['user_id'];
			} else {
				$html .= esc_html( $user->data->display_name );
			}
						
			$html .= '</td><td class="thin"><a href="' . esc_attr(add_query_arg(array('fns_remove_log' => false, 'fns_see_log' => $active ? false : $log['name']), $current_url)) . '#fnslogs" data-fns-log="' . esc_attr($log['name']) . '" class="open_close">';
			
			$html .= '<span class="open">[' . esc_html__('Open', 'fish-and-ships') . ']</span><span class="close">[' . esc_html__('Close', 'fish-and-ships') . ']</span></a></td>';
			
			$html .= '<td class="thin">' . wp_kses($log['final_cost'], array('strong' => array() ) ) . '</td>
					  <td>' . esc_html($log['cart_qty']) . '</td></tr>';

			// Display the log via server (no AJAX)
			if ( $active ) {

				$html .= '<tr class="log_content"><td colspan="6"><div class="fns-log-details"><div class="wrap">';

				foreach ($log_details as $line) {
					$tab = strlen($line) - strlen(ltrim($line));
					$html .= apply_filters('the_content', str_repeat('&nbsp;', $tab) . $line);
				}
				$html .= '</div></div></td></tr>';
			}
		}
		if ($n >= $last_log) break;
	}
	
	// Footer table
	$html .= '</tbody><tfoot><tr><td class="manage-column column-cb check-column">
			<label class="screen-reader-text" for="cb-select-all">' . esc_html__('Select all') . '</label>
			<input class="cb-select-all" type="checkbox">
		</td>
		<td colspan="2">
			<button name="fns-remove_logs" class="button woocommerce-save-button" type="submit" value="' . esc_attr__('Remove selected logs', 'fish-and-ships') . '">' . esc_html__('Remove selected logs', 'fish-and-ships') . '</button>
		</td><td colspan="3" style="text-align:right">';
	
	// pagination
	$html .= '<div class="tablenav-pages">
				<span class="displaying-num">' . esc_html(sprintf( _n( '%s item', '%s items', count($logs_index) ), number_format_i18n( count($logs_index) ) ) ) . '</span>';
	
	if ( $logs_per_pag < count($logs_index) ) {
		
		$total_pages = ceil( count($logs_index) / $logs_per_pag );
		
		$html .= '<span class="pagination-links">';
		
		if ( $logs_pag > 2 ) {

			$first_url = add_query_arg ( 'fnslogspag', false, $current_url ) . '#fnslogs';
			$html .= '<a class="first-page button" href="'.$first_url.'" data-fns-logs-pag="1" data-instance_id="'.$instance_id.'"><span class="screen-reader-text">'
					  . esc_html__( 'First page' ) . '</span><span aria-hidden="true">&laquo;</span></a>';
			
		} else {

			$html .= '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&laquo;</span>';
		}

		if ( $logs_pag > 1 ) {

			$prev_url = add_query_arg ( 'fnslogspag', $logs_pag == 1 ? false : $logs_pag -1, $current_url ) . '#fnslogs';
			$html .= '<a class="prev-page button" href="'.esc_attr($prev_url).'" data-fns-logs-pag="' . esc_attr($logs_pag -1) . '" data-instance_id="'.esc_attr($instance_id).'">
					  <span class="screen-reader-text">' . esc_html__( 'Previous page' ) . '</span><span aria-hidden="true">&lsaquo;</span></a>';

		} else {

			$html .= '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&lsaquo;</span>';
		}
		
		$html .= '<span class="screen-reader-text">' . esc_html__( 'Current Page' ) . '</span>
		<span id="table-paging" class="paging-input"><span class="tablenav-paging-text">' . esc_html(sprintf( _x( '%1$s of %2$s', 'paging' ), $logs_pag, $total_pages ) ). '</span></span>'; // <span class="total-pages">' . $total_pages . '</span>
		
		if ( $logs_pag < $total_pages ) {

			$next_url = add_query_arg ( 'fnslogspag', $logs_pag +1, $current_url ) . '#fnslogs';
			$html .= '<a class="next-page button" href="'.esc_attr($next_url).'" data-fns-logs-pag="' . esc_attr($logs_pag +1) . '" data-instance_id="'.esc_attr($instance_id).'">
					  <span class="screen-reader-text">' . esc_html__( 'Next page' ) . '</span><span aria-hidden="true">&rsaquo;</span></a>';
		
		} else {
			
			$html .= '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&rsaquo;</span>';
		}

		if ( $logs_pag +1 < $total_pages ) {

			$last_url = add_query_arg ( 'fnslogspag', $total_pages, $current_url ) . '#fnslogs';
			$html .= '<a class="last-page button" href="' . esc_attr($last_url) . '" data-fns-logs-pag="' . esc_attr($total_pages) . '" data-instance_id="'.esc_attr($instance_id).'">
					  <span class="screen-reader-text">' . esc_html__( 'Last page' ) . '</span><span aria-hidden="true">&raquo;</span></a>';
		
		} else {
			
			$html .= '<span class="tablenav-pages-navspan button disabled" aria-hidden="true">&raquo;</span>';
		}
	}

	if ( count($logs_index) > 10 ) {

		$html .= '<span class="fns-logs-pp">' . esc_html('Logs per page: ', 'fish-and-ships') . ' <select name="fnslogsperpag" data-instance_id="'.esc_attr($instance_id).'">';
		
		foreach ( array (10,30,50,100) as $val ) {
			$html .= '<option value="' . esc_attr($val) . '" ' . ($val == $logs_per_pag ? 'selected' : '') . '>' . esc_html($val) . '</option>';
		}
		$html .= '</select></span>';
	}

	$html .= '</div></td></tr></tfoot></table>';
}
