<?php
Acfct_utils::set_page_title('Manage Table');

if($customTableName === false){
	echo '<div class="notice inline notice-error notice-alt pad-10">Custom Table Name Not Found</div>';
	return;
}
$validate = Acf_ct_database_manager::validate_acf_group($acf_ct_post_id);
if($validate['valid'] === false){
	$acf_group_edit_link = admin_url('post.php?post='.$acf_ct_post_id.'&action=edit');
?>
    <div class="notice notice-error inline">
        <h3>Please fix following issues:</h3>
        <?php
            if(empty($validate['invalid_columns']) === false) {
                $keywords = "";
                foreach ($validate['invalid_columns'] as $column) {
                    $keywords .= "<span class='highlight' style='padding: 3px; border-radius: 4px; font-weight: bold; background-color: #e6e7e8; margin-right: 4px;' >$column</span>";
                }
                if (count($validate['invalid_columns']) > 1) {
                    echo '<p>MySQL reserved keywords ' . wp_kses($keywords, ['span' => ['style'=>[], 'class'=>[]]]) . ' are not allowed as a column name</p>';
                } else {
                    echo '<p>MySQL reserved keyword ' . wp_kses($keywords, ['span' => ['style'=>[], 'class'=>[]]]) . ' is not allowed as a column name</p>';
                }
            }
            if(empty($validate['duplicate_columns']) === false) {
                $duplicate_columns = "";
                foreach ($validate['duplicate_columns'] as $column) {
                    $duplicate_columns .= "<span class='highlight' style='padding: 3px; border-radius: 4px; font-weight: bold; background-color: #e6e7e8; margin-right: 4px;' >$column</span>";
                }
                echo '<p>Duplicate field names found: ' . wp_kses($duplicate_columns, ['span' => ['style'=>[], 'class'=>[]]]) . ' Please use unique name.</p>';
            }
        ?>
        <br>
        <a class="button button-primary button-large" href="<?php echo esc_url($acf_group_edit_link); ?>">Go Back to ACF Field Group</a>
        <br><br>
    </div>
<?php } ?>

<div>
	<div class="metabox-holder acf-columns-2">
		<div class="postbox">
			<h2 class="hndle ui-sortable-handle"><span>Create or Update Table</span></h2>
			<div>
				<?php
				global $wpdb;
				$sql_query = Acf_ct_database_manager::get_create_table_sql_query($acf_ct_post_id, $customTableName);
				$fields = Acfct_utils::get_acf_keys($acf_ct_post_id);
				$button_text = (Acf_ct_database_manager::check_table_exists($customTableName) === true) ? 'Update Table' : 'Create Table';
				?>
				<table class="wp-list-table widefat fixed striped tags acf-ct-table">
					<tbody id="the-list" data-wp-lists="list:tag">
					<tr>
						<td width="100px">Table Name</td>
						<td><strong><?php echo esc_html($wpdb->prefix.$customTableName); ?></strong></td>
					</tr>
					<tr>
						<td width="100px">Columns</td>
						<td>
							<div class="acf-ct-toggle-wrap acf-ct-toggle-divs">
                                <div>id</div><div>post_id</div>
								<?php
                                    foreach ($fields as $field){
                                        echo '<div>'. esc_html($field['name']) .'</div>';
                                    }
                                ?>
								<?php if(count($fields) > 8){ ?>
									<span class="acf-ct-toggle-btn-ui acf-ct-toggle-btn">Show All</span>
								<?php } ?>
							</div>
						</td>
					</tr>
					<tr>
						<td width="100px">Sql Query</td>
						<td>
							<div class="acf-ct-sql-preview small-height">
								<pre><?php echo esc_html(str_replace("	","",$sql_query)) ;?></pre>
							</div>
							<span class="acf-ct-toggle-btn-ui acf-ct-toggle-sql">Show SQL Query</span>
						</td>
					</tr>
					<?php
					/**
					 * Changes message
					 */
					$change_list =  Acf_ct_database_manager::get_acf_fields_change_list($acf_ct_post_id);
					if ($change_list === false || $change_list['created'] === false){ ?>
                    	<tr>
                        <td>Changes</td>
                        <td>
                            <?php
								if($change_list === false || $change_list['should_update'] === false){
									echo '<p>No changes found</p>';
								}else if($change_list['should_update'] === true){
							        if(empty($change_list['added']) === false){
							        	$s = (count($change_list['added']) > 1) ? 's' : '';
                                        echo "<p>Following column" . esc_html($s) . " will get created</p>";
                                        echo '<p>'.Acfct_utils::get_span_tags_from_array($change_list['added']).'</p>';
                                    }
                                    if(empty($change_list['updated']) === false){
										$s = (count($change_list['updated']) > 1) ? 's' : '';
										echo "<p>The data type of the following column" . esc_html($s) . " will get updated</p>";
                                        echo '<p>'.Acfct_utils::get_span_tags_from_array($change_list['updated']).'</p>';
                                    }
                                    if(empty($change_list['deleted']) === false){
                                        $isAre = (count($change_list['deleted']) === 1) ? 'is' : 'are';
                                        $s = (count($change_list['deleted']) > 1) ? 's' : '';
                                    	echo '<br><div class="notice inline notice-warning notice-alt">';
                                        echo "<h4>Note: The following field" . esc_html($s) . " " . esc_html($isAre) . " deleted. Plugin does not support column delete operation. Please delete column" . esc_html($s) . " manually.</h4>";
                                        echo '<p>'.Acfct_utils::get_span_tags_from_array($change_list['deleted']).'</p>';
                                        echo '</div>';
                                    }
								}
                            ?>
                        </td>
                    </tr>
					<?php } ?>
					</tbody>
				</table>
				<br>
                <?php
                    $disabled = "";
                    if($validate['valid'] === false){
						$disabled = 'disabled';
						$db_update_action = '#';
                    }
                    echo '<a class="button button-primary button-large m-10" '. esc_html($disabled) .' href="'. esc_url($db_update_action) .'"><span class="dashicons dashicons-controls-play acf-ct-btn-icon"></span>'. esc_html($button_text) .'</a>';
                ?>
			</div>
		</div>
	</div>
</div>
