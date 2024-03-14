<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options;
# an admin khoi ho so
if (isset($foxtool_options['foxtool1']) && !empty($foxtool_options['foxtool11'])){
function tuan_pre_user_query($user_query){
	global $foxtool_options;
	$id = !empty($foxtool_options['foxtool11']) ? intval($foxtool_options['foxtool11']) : NULL;
    if (is_admin() && current_user_can('manage_options')){
        $user_query->query_where .= " AND {$GLOBALS['wpdb']->users}.ID != {$id}";
    }
}
add_action('pre_user_query', 'tuan_pre_user_query');
}
# Ẩn foxtool khoi menu
if (isset($foxtool_options['foxtool3'])){
function foxtool_hide_menuadmin(){
		remove_menu_page( 'foxtool-options' );
}
add_action( 'admin_menu', 'foxtool_hide_menuadmin', 999);
} 
# Ẩn plugin khoi quan ly plugin
if (isset($foxtool_options['foxtool4'])){
function foxtool_hide_plugins($plugins){
    $hidden_plugins = ['foxtool/foxtool.php'];
    foreach ($hidden_plugins as $plugin) {
        if (array_key_exists($plugin, $plugins)) {
            unset($plugins[$plugin]);
        }
    }
    return $plugins;
}
add_filter('all_plugins', 'foxtool_hide_plugins');
}
# xem csdl dung gi
function foxtool_display_db_info() {
    global $wpdb;
    $database_info = $wpdb->get_results("SHOW VARIABLES LIKE 'version'", ARRAY_A);
    if (!empty($database_info)) {
        $db_version = $database_info[0]['Value'];
        $db_type = strpos($db_version, 'MariaDB') !== false ? 'MariaDB' : 'MySQL';
        echo esc_html($db_type) .': <b>'. esc_html($db_version) .'</b>';
    } else {
        echo __('Does not exist', 'foxtool');
    }
}
# hien thi cac bang dang su dung
function foxtool_display_wp_tables() {
    global $wpdb;
    $default_tables = array(
        'posts',
        'users',
        'comments',
        'terms',
        'term_taxonomy',
        'term_relationships',
        'options',
        'postmeta',
        'usermeta',
		'links',
		'commentmeta',
		'termmeta',
    );
    $tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
    if ($tables) {
        echo '<div class="ft-showcsdl">';
        foreach ($tables as $table) {
            $table_name = $table[0];
            $row_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
            $table_name_without_prefix = substr($table_name, strlen($wpdb->prefix));
			$table_size = $wpdb->get_var("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as table_size_mb FROM information_schema.tables WHERE table_schema = '" . DB_NAME . "' AND table_name = '$table_name'");
            if (in_array($table_name_without_prefix, $default_tables)) {
                echo '<div><span style="color:#ff4444;font-weight:bold">' . esc_html($table_name) . '</span></div>';
            } else {
                echo '<div>' . esc_html($table_name) . '</div>';
            }
            echo '<div>' . esc_html($row_count) . '</div>';
			echo '<div>' . esc_html($table_size) . ' MB</div>';
        }
        echo '</div>';
    }
}
# kiêm tra dung luong database
function foxtool_get_database_size() {
    global $wpdb;
    $total_size = 0;
    $tables = $wpdb->get_results("SHOW TABLE STATUS");
    foreach ($tables as $table) {
        $total_size += $table->Data_length + $table->Index_length;
    }
    $total_size_formatted = size_format($total_size, 2);
    return $total_size_formatted;
}
# Tùy chỉnh Logo
function foxtool_logo(){
    global $foxtool_options;
    $logo = '<img src="'. esc_url(FOXTOOL_URL .'img/logo.png') .'" />';
    if (isset($foxtool_options['foxtool61'])) {
        switch ($foxtool_options['foxtool61']) {
            case 'icon 1':
                echo $logo;
                break;
            case 'icon 2':
                echo '<span style="font-size:40px;color:#fff;display:contents;" class="dashicons dashicons-admin-tools"></span>';
                break;
            case 'icon 3':
                echo '<span style="font-size:40px;color:#fff;display:contents;" class="dashicons dashicons-admin-generic"></span>';
                break;
            case 'icon 4':
                echo '<span style="font-size:40px;color:#fff;display:contents;" class="dashicons dashicons-image-filter"></span>';
                break;
			case 'icon 5':
                echo '<span style="font-size:40px;color:#fff;display:contents;" class="dashicons dashicons-wordpress"></span>';
                break;
			case 'icon 6':
                echo '<span style="font-size:40px;color:#fff;display:contents;" class="dashicons dashicons-shield"></span>';
                break;
            default:
                echo $logo;
                break;
        }
    } else {
        echo $logo;
    }
}
# Tùy chỉnh icon
function foxtool_icon(){
    global $foxtool_options;
    $icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+Cjxzdmcgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEwMCAxMDAiIHZlcnNpb249IjEuMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgeG1sbnM6c2VyaWY9Imh0dHA6Ly93d3cuc2VyaWYuY29tLyIgc3R5bGU9ImZpbGwtcnVsZTpldmVub2RkO2NsaXAtcnVsZTpldmVub2RkO3N0cm9rZS1saW5lam9pbjpyb3VuZDtzdHJva2UtbWl0ZXJsaW1pdDoyOyI+CiAgICA8cGF0aCBkPSJNNjYuODQ0LDI2Ljg5M0w5NS4yLDEyLjcwMkw5NS4yLDY0LjcwMUw1MCw4Ny4yOThMNC44LDY0LjcwMUw0LjgsMTIuNzAyTDUwLjAwOCwzNS4zMThMOTUuMiwxMi43MDJMNjYuODQ0LDI2Ljg5M1pNMTMuOCwyNy41NTNMMTMuNzA2LDYwLjA5N0w0OS45MzksNzYuMjE1TDgwLjg4Nyw2MS42NjVMMTMuOCwyNy41NTNaTTUwLjAwOCwzNS4zMThMOTUuMDU3LDU3Ljc0MUw1OC4zNTksMzEuMTM5TDUwLjAwOCwzNS4zMThaIiBzdHlsZT0iZmlsbDp3aGl0ZTsiLz4KPC9zdmc+Cg==';
	if (isset($foxtool_options['foxtool61'])) {
    switch ($foxtool_options['foxtool61']) {
        case 'icon 1':
            return $icon;
            break;
        case 'icon 2':
            return 'dashicons-admin-tools';
            break;
        case 'icon 3':
            return 'dashicons-admin-generic';
            break;
        case 'icon 4':
            return 'dashicons-image-filter';
            break;
		case 'icon 5':
            return 'dashicons-wordpress';
            break;
		case 'icon 6':
            return 'dashicons-shield';
            break;
        default:
            return $icon;
            break;
    }
	} else {
		return $icon;
	}
}

