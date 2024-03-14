<?php
/*
Plugin Name: Last Name First Name
Description: ユーザー管理画面で、名前の表示順や入力順を「名」→「姓」から「姓」→「名」にします。
Author: Isao Homma
Version: 0.1
*/

/*  Copyright 2013 (email : wp.isao.homma@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$_lnfn_first_name;
$_lnfn_last_name;

function personal_options_last_name_first_name($user_info) {
	global $_lnfn_first_name;
	global $_lnfn_last_name;

	$_lnfn_first_name = $user_info->first_name;
	$_lnfn_last_name = $user_info->last_name;
}
add_action('personal_options', 'personal_options_last_name_first_name');

function show_password_fields_last_name_first_name($bool){
	global $_lnfn_first_name;
	global $_lnfn_last_name;

?>
<script type="text/javascript">
    jQuery(function($){
	var first_name = $('#first_name');
	var last_name = $('#last_name');

	first_name.closest('tr').find('label').attr({for:'last_name'}).text('<?php _e('Last Name'); ?>');
	last_name.closest('tr').find('label').attr({for:'first_name'}).text('<?php _e('First Name'); ?>');
	first_name.attr({id:'last_name', name:'last_name', value:'<?php echo $_lnfn_last_name; ?>'});
	last_name.attr({id:'first_name', name:'first_name', value:'<?php echo $_lnfn_first_name; ?>'});
    });
</script>
<?php
    return $bool;
}
add_action('show_password_fields', 'show_password_fields_last_name_first_name');


function manage_users_columns_last_name_first_name($column_headers){
    $column_headers_ex = array();
    foreach($column_headers as $key => $val){
        if($key == "name"){
            $column_headers_ex["fullname"] = $val;
        }else{
            $column_headers_ex[$key] = $val;
        }
    }
    return $column_headers_ex;
}

function manage_users_custom_column_last_name_first_name($custom_column, $column_name, $user_id){
    $user_info = get_userdata($user_id);
 
    if ($column_name=='fullname') {
        $custom_column = $user_info->last_name . ' ' . $user_info->first_name;
    }
 
    return $custom_column;
}

function manage_users_sortable_columns_last_name_first_name($columns){
	$columns['fullname'] = 'name';
	return $columns;
}

function request_last_name_first_name($query){
	if(isset($query['orderby']) && $query['orderby'] == 'name'){
        	$query = array_merge($query, array('meta_key' => 'name', 'orderby' => 'meta_value'));
	}
	return $query;
}

add_action('manage_users_columns', 'manage_users_columns_last_name_first_name');
add_action('manage_users_custom_column', 'manage_users_custom_column_last_name_first_name', 10, 3);
add_filter('request', 'request_last_name_first_name');
add_filter('manage_users_sortable_columns', 'manage_users_sortable_columns_last_name_first_name');