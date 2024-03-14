<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$setting_tab_fields = get_option( 'elex_settings_tab_fields_data' );

	$upload_dir = wp_upload_dir();
	$base = $upload_dir['basedir'];
	$plugin_path = $base . '/elex-product-feed/';
	$dir_url = $upload_dir['baseurl'] . '/elex-product-feed/';
	$search_var = isset( $_GET['search'] ) ? sanitize_text_field( $_GET['search'] ) : '';
?>
	<div class="elex-gpf-empty-div"></div>
	<div id="elex_manage_feed" class="postbox elex-gpf-manage-feed-table-box elex-gpf-manage-feed-table-box-main ">
		<h1><?php esc_html_e( 'Manage Feeds', 'elex-product-feed' ); ?></h1>
		<div style="padding-bottom: 2%; padding-left: 75%;"><input value="<?php echo esc_html( $search_var ); ?>" style="width: 70%;" type="text" id="elex_gpf_search_feed" placeholder="Search by Feed name.." title="Type in a name"> <button onclick="elex_gpf_search_feed_fun()" class="botton button-large button-primary">search</button> <br></div>
		<table id="elex_manage_feed_files" class="elex-gpf-manage-feed-settings-table">
			<tr>
				<th class='elex-gpf-manage-feed-settings-table-name'>
					<?php esc_html_e( 'Name', 'elex-product-feed' ); ?>
				</th>
				<th class='elex-gpf-manage-feed-settings-table-url'>
					<?php esc_html_e( 'URL', 'elex-product-feed' ); ?>
				</th>
				<th class='elex-gpf-manage-feed-settings-created-date'>
					<?php esc_html_e( 'Created', 'elex-product-feed' ); ?>
				</th>
				<th class='elex-gpf-manage-feed-settings-modified-datel'>
					<?php esc_html_e( 'Modified', 'elex-product-feed' ); ?>
				</th>
				<th class='elex-gpf-manage-feed-settings-modified-datel'>
					<?php esc_html_e( 'Next schedule', 'elex-product-feed' ); ?>
				</th>
				<th class='elex-gpf-manage-feed-settings-table-actions'>
					<?php esc_html_e( 'Actions', 'elex-product-feed' ); ?>
				</th>
			</tr>
			<tr></tr>
			<?php
			$products_each_iteration = 0;
			$max_feeds_per_page = 100;
			$counter = 0;

			if ( is_dir( $plugin_path ) ) {
				global $wpdb;
				$table_name = $wpdb->prefix . 'gpf_feeds';
				$feed_id_query = "SELECT * FROM $table_name Where feed_meta_key = 'manage_feed_data' ";
				$result = array_reverse( $wpdb->get_results( ( $wpdb->prepare( '%1s', $feed_id_query ) ? stripslashes( $wpdb->prepare( '%1s', $feed_id_query ) ) : $wpdb->prepare( '%s', '' ) ), ARRAY_A ) );
				if ( ! isset( $_GET['feed_page'] ) ) {
					$current_page = 1;
				} else {
					$current_page = sanitize_text_field( $_GET['feed_page'] );
				}
				$max_pagination = 3;
				$max_feeds_per_page = 100;
				$products_each_iteration = 0;
				$start = ( $current_page - 1 ) * $max_feeds_per_page;
				$counter = 0;
				foreach ( $result as $key => $value ) {
					$val = $value['feed_meta_content'];
					$val = json_decode( $val, true );

					if ( $search_var && strpos( strtolower( $val['name'] ), strtolower( $search_var ) ) === false ) {
						continue;
					}
					if ( $counter < $start ) {
						$counter++;
						continue;
					}
					$counter++;
					if ( $products_each_iteration == $max_feeds_per_page ) {
						break;
					}
					$products_each_iteration++;
					$file_name = '';
					$created_date = '';
					$modified_date = '';
					$pause_or_play = '';
					$next_schedule = '-';

					 {
					if ( isset( $val['file'] ) ) {
						$file = $val['file'];

						$file_name = $val['name'];
						$created_date = isset( $val['created_date'] ) ? $val['created_date'] : '';
						if ( isset( $val['modified_date'] ) ) {
							$modified_date = $val['modified_date'];
						}
						$pause_or_play = $val['pause_schedule'];
						if ( 'no_refresh' != $val['refresh_schedule'] ) {
							if ( 'weekly' == $val['refresh_schedule'] && isset( $val['refresh_days'] ) && $val['refresh_days'] ) {
								$str_next_day = '';
								$today = strtolower( current_time( 'l' ) );
								if ( in_array( $today, $val['refresh_days'] ) ) {
									if ( current_time( 'G' ) >= $val['refresh_hour'] ) {
										$index = array_search( $today, $val['refresh_days'] );
										if ( isset( $val['refresh_days'][ $index + 1 ] ) ) {
											$str_next_day = 'next ' . $val['refresh_days'][ $index + 1 ];
										} else {
											$str_next_day = 'next ' . $val['refresh_days'][0];
										}
									} else {
										$str_next_day = 'today';
									}
								} else {
									foreach ( $val['refresh_days'] as $day_index => $day_value ) {
										if ( gmdate( 'w', strtotime( $day_value ) ) > gmdate( 'w', strtotime( $today ) ) ) {
											$str_next_day = 'next ' . $day_value;
											break;
										}
									}
									$str_next_day = 'next ' . $val['refresh_days'][0];
								}
								$next_schedule = gmdate( 'd-m-Y', strtotime( $str_next_day ) );
							} elseif ( 'monthly' == $val['refresh_schedule'] ) {
								$today = current_time( 'j' );
								$next_day = '';
								$number_of_days = current_time( 't' );
								$next_month = false;
								if ( in_array( $today, $val['refresh_days'] ) ) {
									if ( current_time( 'G' ) >= $val['refresh_hour'] ) {
										$index = array_search( $today, $val['refresh_days'] );
										if ( isset( $val['refresh_days'][ $index + 1 ] ) && $val['refresh_days'][ $index + 1 ] <= $number_of_days ) {
											$next_day = $val['refresh_days'][ $index + 1 ];
										} else {
											$next_day = $val['refresh_days'][0];
											$next_month = true;
										}
									} else {
										$next_day = $today;
									}
								} else {
									foreach ( $val['refresh_days'] as $day_index => $day_value ) {
										if ( ( $day_value > $today ) && ( $day_value <= $number_of_days ) ) {
											$next_day = $day_value;
											break;
										}
										$next_day = $val['refresh_days'][0];
										$next_month = true;
									}
								}
								$next_day = sprintf( '%02d', $next_day );
								$next_schedule = $next_day . '-' . current_time( 'm-Y' );
								if ( $next_month ) {
									$next_schedule = gmdate( 'd-m-Y', strtotime( $next_schedule . '+1 month' ) );
								}
							} else {
								if ( current_time( 'G' ) >= $val['refresh_hour'] ) {
									$next_schedule = gmdate( 'd-m-Y', strtotime( 'tomorrow' ) );
								} else {
									$next_schedule = gmdate( 'd-m-Y', strtotime( 'today' ) );
								}
							}
							$next_schedule .= '<br><span style="font-size: 10px;">' . sprintf( '%02d', $val['refresh_hour'] ) . ':00:00</span>';
							if ( 'paused' == $val['pause_schedule'] ) {
								$next_schedule .= '<br><span style="color:red;font-size: 10px;">(Paused)</span>';
							}
						}
					}
					}
					if ( ! $file_name ) {
						continue;
					}
					?>
				<tr>
					<td class="elex-gpf-manage-feed-settings-table-name">
						<?php echo esc_html( $file_name ); ?>
					</td>
					<td class="elex-gpf-manage-feed-settings-table-url">
						<?php echo esc_html( $dir_url . $file ); ?>
					</td>
					<td class="elex-gpf-manage-feed-settings-created-date">
						<?php
							$created_date = explode( ' ', $created_date );
							echo esc_html( $created_date[0] );
						if ( isset( $created_date[1] ) ) {
							echo '<br><span style="font-size: 10px;">' . esc_html( $created_date[1] ) . '</span>';
						}
						?>
					</td>
					<td class="elex-gpf-manage-feed-settings-modified-date">
						<?php
							$modified_date = explode( ' ', $modified_date );
							echo esc_html( $modified_date[0] );
						if ( isset( $modified_date[1] ) ) {
							echo '<br><span style="font-size: 10px;">' . esc_html( $modified_date[1] ) . '</span>';
						}
						?>
					</td>
					<td class="elex-gpf-manage-feed-settings-modified-datel">
					<?php 
							$allowed_html = array(
								'br' => array(),
							); 
							?>
						<?php echo   wp_kses( $next_schedule, $allowed_html ); ?>
					</td>
					<td class="elex-gpf-manage-feed-settings-table-actions">
						<span class=" elex-gpf-icon4 elex-gpf-icon4-edit"  title="Edit Project" onclick="elex_edit_file('<?php echo esc_html( $value['feed_id'] ); ?>')"   style="display: inline-block;"></span>
						<span class=" elex-gpf-icon2 elex-gpf-icon2-view"  title="Copy Project" onclick="elex_copy_file('<?php echo esc_html( $value['feed_id'] ); ?>')"   style="display: inline-block;"></span>
						<?php if ( 'ready' == $pause_or_play ) { ?>
						<span class="elex-gpf-icon-pause"  title="Pause Schedule" onclick="elex_pause_schedule('<?php echo esc_html( $value['feed_id'] ); ?>')"   style="display: inline-block;"></span>
					<?php } else { ?>
						<span class="elex-gpf-icon-play"  title="Resume Schedule" onclick="elex_play_schedule('<?php echo esc_html( $value['feed_id'] ); ?>')"   style="display: inline-block;"></span>
						<?php } ?>
						<span class=" elex-gpf-icon3 elex-gpf-icon3-refresh"  title="Regenerate Feed" onclick="update_file_to_latest('<?php echo esc_html( $value['feed_id'] ); ?>', '<?php echo esc_html( $file_name ); ?>')" style="display: inline-block; margin: 2px 3px -2px;"></span>
						<a href=<?php echo esc_html( $dir_url . $file ); ?> download=<?php echo esc_html( $file ); ?> target="_blank" id="<?php echo esc_html( $file ); ?>"></a>
							<span class=" elex-gpf-icon-download"  title="Download Feed" onclick="document.getElementById('<?php echo esc_html( $file ); ?>').click();" download="<?php echo esc_html( $file ); ?>"style="display: inline-block; margin: 2px 3px 1px;"></span>
						
							<span class="elex-gpf-icon-view"  title="View Feed" onclick="window.open('<?php echo esc_html( $dir_url . $file ); ?>','_blank')" style="display: inline-block; margin: 0px 2px 0px;"></span>
							<span class="elex-gpf-icon elex-gpf-icon-report"  title="Show Report" onclick="elex_show_reports('<?php echo esc_html( $value['feed_id'] ); ?>', '<?php echo esc_html( $file_name ); ?>')"   style="display: inline-block;"></span>
						<span class=" elex-gpf-icon elex-gpf-icon-delete" onclick="elex_remove_file('<?php echo esc_html( $value['feed_id'] ); ?>', '<?php echo esc_html( $file_name ); ?>')"   title="Delete Project" style="display: inline-block; margin: 0px 2px 1px;"></span>
					</td>
				</tr>
					<?php
				}
			}
			?>
		</table>
	</div>
	<?php
		$pagination = 0;

	if ( $products_each_iteration < $max_feeds_per_page ) {
		$pagination = ceil( $counter / $max_feeds_per_page );
	} elseif ( ! empty( $result ) ) {
		$pagination = ceil( count( $result ) / $max_feeds_per_page );
	}
	if ( $pagination > 1 ) {
		if ( $pagination == $max_pagination ) {
			$start = 1;
		} else {
			$start = $current_page;
			if ( $pagination == $current_page ) {
				$start = $current_page - 1;
			}
		}
		if ( 1 == $current_page ) {
			$left_disable = 'pag-disable';
			$left_href    = '#';
		} else {
			$left_disable = '';
			$left_href    = 'admin.php?page=elex-product-feed-manage&feed_page=' . ( $current_page - 1 ) . '&search=' . $search_var;
		}
		if ( $pagination == $current_page ) {
			$right_disable = 'pag-disable';
			$right_href    = '#';
		} else {
			$right_disable = '';
			$right_href    = 'admin.php?page=elex-product-feed-manage&feed_page=' . ( $current_page + 1 ) . '&search=' . $search_var;
		}
		?>
				<div style="padding-left: 76%;" class="pagination">
					<a href="<?php echo esc_html( $left_href ); ?> " class="<?php echo esc_html( $left_disable ); ?> ">&laquo;</a>
			<?php

			for ( $flag = $start; $flag <= $pagination; $flag++ ) {
					$active = '';
				if ( $flag == $current_page ) {
					$active = 'pag-active';
				}
				?>
					<a href="admin.php?page=elex-product-feed-manage&feed_page=<?php echo esc_html( $flag ); ?>&search=<?php echo esc_html( $search_var ); ?>" class="<?php echo esc_html( $active ); ?> " > <?php echo esc_html( $flag ); ?> </a>
				<?php
				if ( $flag >= $start + $max_pagination - 1 ) {
					break;
				}
			}
			?>
					<a href="<?php echo esc_html( $right_href ); ?> " class="<?php echo esc_html( $right_disable ); ?> ">&raquo;</a>
				</div>
			<?php
	}
	?>

	<div id="dialogBox" style="display: none;">
		<div id="chartContainer" class="dialog" style="height: 370px; width: 100%;"></div>
	</div>

	<?php
include_once ELEX_PRODUCT_FEED_TEMPLATE_PATH . '/elex-settings-frontend.php';
