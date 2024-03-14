<?php
	$active_tab = 'manage-tables';
	$tab_sub_type = null;
	$urlBase = ACF_CT_ADMIN_PAGE;

	if(isset($_GET['tab']) && empty($_GET['tab']) === false){
		$active_tab = sanitize_text_field($_GET['tab']);
	}

    if(isset($_GET['type']) && empty($_GET['type']) === false){
		$tab_sub_type = sanitize_text_field($_GET['type']);
    }

	$acf_ct_post_id = null;
	$db_update_action = null;
	$table_create_acf_post_id = null;
	$customTableName = null;
	$hideTab = '';

    /**
     * DB update screen
     */
	if($active_tab === 'manage-tables' && isset($_GET['acf_ct_post_id']) && empty($_GET['acf_ct_post_id']) === false && $tab_sub_type === 'sql-view'){
		$acf_ct_post_id = intval($_GET['acf_ct_post_id']);
		$db_update_action = $urlBase."&acf_ct_db_create_action=".$acf_ct_post_id;
		$customTableName = Acfct_utils::get_custom_table_name($acf_ct_post_id);
	}
    /**
     * Log screen
     */
    if($active_tab === 'manage-tables' && isset($_GET['acf_ct_post_id']) && empty($_GET['acf_ct_post_id']) === false && $tab_sub_type === 'log'){
        $acf_ct_post_id = intval($_GET['acf_ct_post_id']);
    }
	/**
	 *  DB update result screen
	 */
	else if($active_tab === 'manage-tables' && isset($_GET['acf_ct_db_create_action']) && empty($_GET['acf_ct_db_create_action']) === false){
		$table_create_acf_post_id = intval($_GET['acf_ct_db_create_action']);
		$hideTab = 'acf-ct-hide';
    }

?>
<style>
    .acf-ct-table{border: 0 !important;}
    .m-10{margin: 5px 0 10px 10px !important;}
    .pad-10{padding: 10px;}
    .acf-ct-toggle-wrap div{font-size: 14px; line-height: 20px;}
    .acf-ct-toggle-divs div:nth-child(n+11) {
        display: none;
    }
    .acf-ct-toggle-btn-ui{
        display: inline-block;
        color: #0073aa;
        font-weight: bold;
        text-decoration: underline;
        cursor: pointer;
        padding: 6px 0 15px;
    }
    .acf-ct-sql-preview{
        line-height: 20px;
        overflow: hidden;
    }
    .acf-ct-sql-preview.small-height{height: 113px;}
    .acf-ct-sql-preview pre{
        margin: 0;
    }
    .acf-ct-label-list td{padding: 15px;}
    .acf-ct-label-list a{width: 110px; text-align: center;}
    span.acf-ct-btn-icon{padding-top: 5px; padding-right: 7px;}
    .acf-ct-hide{display: none;}
    .or-divider{display: inline-block; width: 50px; text-align: center; line-height: 32px;}
    .acf-ct-table .view-log{line-height: 30px; margin-left: 20px;}
    .acf-ct-columns-2 {margin-right: 300px; clear: both;}
    .acf-ct-columns-2 .acf-ct-column-1 {float: left; width: 100%;}
    .acf-ct-columns-2 .acf-ct-column-2 { float: right; margin-right: -300px; width: 280px; }
    .acf-ct-heading-row{ display: flex; align-items: center; padding: 10px 0; justify-content: space-between; }
    .acf-ct-heading-row h1{ padding: 0; }
    .acf-ct-heading-row .cta{
        background: #22c884;
        border-width: 0;
        box-sizing: border-box;
        border-radius: 0.25rem;
        cursor: pointer;
        vertical-align: middle;
        text-decoration: none;
        color: #FFF;
        padding: 8px 16px;
        font-size: 14px;
    }
    .acf-ct-heading-row .cta:hover { background: #1ca76e; }
</style>
<div class="wrap">
	<?php
    if ($active_tab === 'manage-tables' && $tab_sub_type === 'sql-view'):
        include_once ACF_CUSTOM_TABLE_PATH.'includes/views/acf-custom-table-run.php';
    elseif ($active_tab === 'manage-tables' && $tab_sub_type === 'log'):
		include_once ACF_CUSTOM_TABLE_PATH.'includes/views/acf-custom-table-log.php';
    elseif ($active_tab === 'manage-tables' && $table_create_acf_post_id !== null): ?>
        <div class="acf-columns-2">
        <?php
		    $query_result = Acf_ct_database_manager::create_table($table_create_acf_post_id);

		    if($query_result['status'] === 'error'){
		        echo '<div class="notice inline notice-error notice-alt pad-10">'. esc_html($query_result['msg']) .'</div>';
            }else if($query_result['status'] === 'warning'){
				echo '<div class="notice inline notice-warning notice-alt pad-10">'. esc_html($query_result['msg']) .'</div>';
			}else if($query_result['status'] === 'success'){
				echo '<div class="notice inline notice-success notice-alt pad-10">'. esc_html($query_result['msg']) .'</div>';
			}

            /**
             * Print delta Result
             */
		    if(empty($query_result['result']) === false) {
				echo '<table class="wp-list-table widefat fixed striped tags"><tbody id="the-list" data-wp-lists="list:tag">';
				echo '<thead><tr><th>Result</th></tr></thead>';
				foreach ($query_result['result'] as $result) {
					echo '<tr><td>' . esc_html($result) . '</td></tr>';
				}
				echo '</tbody></table>';
			}

		   $acf_group_edit_link = admin_url('post.php?post='.$table_create_acf_post_id.'&action=edit');
		    echo '<br><a class="button button-primary button-large" href="'. esc_url($acf_group_edit_link) .'">Go Back to ACF Field Group</a>';
		    echo '<span class="or-divider">or</span>';
		    echo '<a class="button button-primary button-large" href="'. esc_url($urlBase) .'">Manage Tables</a>';
        ?>
        </div>
	<?php elseif ($active_tab === 'manage-tables'):
        include_once ACF_CUSTOM_TABLE_PATH.'includes/views/acf-custom-table-list.php';
	endif; ?>

</div>


