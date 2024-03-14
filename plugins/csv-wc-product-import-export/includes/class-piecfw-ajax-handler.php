<?php
if (!defined( 'ABSPATH')) exit;
if(!class_exists( 'PIECFW_AJAX_Handler')){
	class PIECFW_AJAX_Handler {
		/**
		* Constructor
		*/
		public function __construct() {
			add_action( 'wp_ajax_piecfw_import_request', array( $this, 'piecfw_import_request' ) );
			add_action( 'wp_ajax_piecfw_import_regenerate_thumbnail', array( $this, 'regenerate_thumbnail' ) );
			add_action( 'wp_ajax_piecfw_import_validation', array( $this, 'piecfw_import_validation' ) );
			add_action( 'wp_ajax_piecfw_cron_list', array( $this, 'piecfw_cron_list' ) );
			add_action( 'wp_ajax_piecfw_filelog_list', array( $this, 'piecfw_filelog_list' ) );
			add_action( 'wp_ajax_piecfw_datalog_list', array( $this, 'piecfw_datalog_list' ) );
			add_action( 'wp_ajax_piecfw_cron_status', array( $this, 'piecfw_cron_status' ) );
			add_action( 'wp_ajax_piecfw_cron_delete', array( $this, 'piecfw_cron_delete' ) );
			add_action( 'wp_ajax_piecfw_log_delete', array( $this, 'piecfw_log_delete' ) );
		}

		/**
		* Ajax Log delete
		*/
		function piecfw_log_delete(){
			global $wpdb;
			$wpdb->delete($wpdb->prefix.'piecfw_product_import_file_log',array('file_name'=>sanitize_text_field($_POST['log_id'])));
			$wpdb->delete($wpdb->prefix.'piecfw_product_import_data_log',array('file_name'=>sanitize_text_field($_POST['log_id'])));
			@unlink(PIECFW_UPLOAD_DIR.sanitize_text_field($_POST['log_id']));
			exit;
		}

		/**
		* Ajax Cron delete
		*/
		function piecfw_cron_delete(){
			global $wpdb;
			$file_name = $wpdb->get_var( $wpdb->prepare( "SELECT file_name FROM ".$wpdb->prefix."piecfw_product_import_cron WHERE cron_id = %d", sanitize_text_field($_POST['cron_id']) ) );
			$wpdb->delete($wpdb->prefix.'piecfw_product_import_cron',array('cron_id'=>sanitize_text_field($_POST['cron_id'])));
			@unlink(PIECFW_UPLOAD_CRON_DIR.$file_name);
			exit;
		}

		/**
		* Ajax Cron status
		*/
		function piecfw_cron_status(){
			global $wpdb;
			$wpdb->update($wpdb->prefix.'piecfw_product_import_cron', array(
				'status' => sanitize_text_field($_POST['cron_status'])
			), array('cron_id'=>sanitize_text_field($_POST['cron_id'])));
			exit;
		}

		/**
		* Ajax Cron list
		*/
		function piecfw_cron_list(){
			global $wpdb;
			$freq = PIECFW_FREQ;

			// page number
			$paged = isset($_POST['paged']) ? sanitize_text_field($_POST['paged']) : 1;
			
			//condition
			$conditions = '1=1';
			
			// sort by row column or created
			$sort['name'] = (isset($_POST['sort']) && $_POST['sort']!="") ? sanitize_text_field($_POST['sort']) : 'created_at';
			$sort['order'] = (isset($_POST['order']) && $_POST['order']!="") ? sanitize_text_field($_POST['order']) : 'DESC';
			$sort_by = ' ORDER BY ' . $sort['name'] . ' '. $sort['order'];
			
			$html = '';     
			
			// initial link for pagination.
			// "page" must be the  menu slug / clean url from the add_menu_page
			$link = 'admin.php?page=piecfw_import_export&tab=cron';

			// user info by WordPress function
			//$user_info = get_userdata($user_id);

			// initial form html
			$html = '';
			
			// example sql query to get user emails
			$sql = "SELECT *
				FROM ".$wpdb->prefix."piecfw_product_import_cron as m 
				WHERE ".$conditions." ".$sort_by;
			
				
			$rows = $wpdb->get_results($sql);     

			$rows_per_page = 20;

			// add pagination arguments from WordPress
			$pagination_args = array(
				'base' => add_query_arg('paged','%#%'),
				'format' => '',
				'total' => ceil(sizeof($rows)/$rows_per_page),
				'current' => $paged,
				'show_all' => false,
				'type' => 'plain',
			);

			$start = ($paged - 1) * $rows_per_page;
			$end_initial = $start + $rows_per_page;
			$end = (sizeof($rows) < $end_initial) ? sizeof($rows) : $end_initial;

			// if we have results
			if (count($rows) > 0) {
				$link .= '&paged=' . $paged;        

				$order = $sort['order'] == "ASC" ? "DESC" : "ASC";

				// html table head
				$html .= '<div style="float:right;"><a id="refresh_log" href="javascript:void(0);">Refresh</a><br><br></div><table id="user-sent-mail" class="wp-list-table widefat fixed users">
						<thead>
						<tr class="manage-column">
							<th class="col-file_name">
								<a class="cron_sort" data-sort="file_name" data-order="'.$order.'" href="' . $link . '&sort=file_name&order=' . $order . '">
								' . __('File name') . '
								</a>
							</th>
							<th class="col-start_date">
								<a class="cron_sort" data-sort="start_date" data-order="'.$order.'" href="' . $link . '&sort=start_date&order=' . $order . '">
								' . __('Schedule date & time') . '
								</a>
							</th>
							<th class="col-frequency">
								<a class="cron_sort" data-sort="frequency" data-order="'.$order.'" href="' . $link . '&sort=frequency&order=' . $order . '">
								' . __('Frequency') . '
								</a>
							</th>
							<th class="col-status">
								<a class="cron_sort" data-sort="status" data-order="'.$order.'" href="' . $link . '&sort=status&order=' . $order . '">
								' . __('Status') . '
								</a>
							</th>
							<th class="col-created_at">
								<a class="cron_sort" data-sort="created_at" data-order="'.$order.'" href="' . $link . '&sort=created_at&order=' . $order . '">
								' . __('Created') . '
								</a>
							</th>
							<th class="col-status">
								' . __('Action') . '
							</th>
						</tr>
						</thead>
						<tbody>
						';

				// add rows
				for ($index = $start; $index < $end;  ++$index) {
					$row = $rows[$index];

					$class_row = ($index % 2 == 1 ) ? ' class="alternate"' : '';
					$html .= '
						<tr ' . $class_row . '>
							<td>' . $row->file_name . '</td>
							<td>' . $row->start_date . '</td>
							<td>' . $freq[$row->frequency] . '</td>
							<td>' . $row->status. '</td>
							<td>' . $row->created_at . '</td>
							<td>
								<a class="cron_delete" dataid="'.$row->cron_id.'" href="javascript:void(0);">
								' . __('Delete') . '
								</a>
							</td>
						</tr>';
				}

				$html .= '</tbody></table>';     
				
				$html .= '<div class="pagination-links"><br>'.paginate_links($pagination_args).'</div>';
			} else {
				/*$html .= '<p>' . __('No records found, try again please') . '</p>';*/
			}     
			
			_e($html); 
			exit;
		}

		/**
		* Ajax file log list
		*/
		function piecfw_filelog_list(){
			global $wpdb;

			// page number
			$paged = isset($_POST['paged']) ? sanitize_text_field($_POST['paged']) : 1;
			
			//condition
			$conditions = '1=1';
			
			// sort by row column or created
			$sort['name'] = (isset($_POST['sort']) && $_POST['sort']!="") ? sanitize_text_field($_POST['sort']) : 'file_date';
			$sort['order'] = (isset($_POST['order']) && $_POST['order']!="") ? sanitize_text_field($_POST['order']) : 'DESC';
			$sort_by = ' ORDER BY ' . $sort['name'] . ' '. $sort['order'];
			
			$html = '';     
			
			// initial link for pagination.
			// "page" must be the  menu slug / clean url from the add_menu_page
			$link = 'admin.php?page=piecfw_import_export&tab=logs';
			$view_link = 'admin.php?page=piecfw_import_export&tab=logs';

			// user info by WordPress function
			//$user_info = get_userdata($user_id);

			// initial form html
			$html = '';
			
			// example sql query to get user emails
			$sql = "SELECT *
				FROM ".$wpdb->prefix."piecfw_product_import_file_log as m 
				WHERE ".$conditions." ".$sort_by;
			$rows = $wpdb->get_results($sql);     

			$rows_per_page = 20;

			// add pagination arguments from WordPress
			$pagination_args = array(
				'base' => add_query_arg('paged','%#%'),
				'format' => '',
				'total' => ceil(sizeof($rows)/$rows_per_page),
				'current' => $paged,
				'show_all' => false,
				'type' => 'plain',
			);

			$start = ($paged - 1) * $rows_per_page;
			$end_initial = $start + $rows_per_page;
			$end = (sizeof($rows) < $end_initial) ? sizeof($rows) : $end_initial;

			// if we have results
			if (count($rows) > 0) {
				$link .= '&paged=' . $paged;        

				$order = $sort['order'] == "ASC" ? "DESC" : "ASC";

				// html table head
				$html .= '<table id="user-sent-mail" class="wp-list-table widefat fixed users">
						<thead>
						<tr class="manage-column">
							<th class="col-file_name">
								<a class="log_sort" data-sort="file_name" data-order="'.$order.'" href="' . $link . '&sort=file_name&order=' . $order . '">
								' . __('File name') . '
								</a>
							</th>
							<th class="col-file_status">
								<a class="log_sort" data-sort="file_status" data-order="'.$order.'" href="' . $link . '&sort=file_status&order=' . $order . '">
								' . __('File status') . '
								</a>
							</th>
							<th class="col-start_date">
								<a class="log_sort" data-sort="file_date" data-order="'.$order.'" href="' . $link . '&sort=file_date&order=' . $order . '">
								' . __('Created') . '
								</a>
							</th>
							<th class="col-status">
								' . __('Action') . '
							</th>
						</tr>
						</thead>
						<tbody>
						';

				// add rows
				for ($index = $start; $index < $end;  ++$index) {
					$row = $rows[$index];

					$class_row = ($index % 2 == 1 ) ? ' class="alternate"' : '';
					$html .= '
						<tr ' . $class_row . '>
							<td>' . $row->file_name . '</td>
							<td>' . $row->file_status . '</td>
							<td>' . $row->file_date . '</td>
							<td>
								<a class="log_view" href="'.$view_link.'&log_file='.$row->file_name.'">
								' . __('View import logs') . '
								</a>

								|

								<a class="log_delete" dataid="'.$row->file_name.'" href="javascript:void(0);">
								' . __('Delete') . '
								</a>
							</td>
						</tr>';
				}

				$html .= '</tbody></table>';     
				
				$html .= '<div class="pagination-links"><br>'.paginate_links($pagination_args).'</div>';
			} else {
				$html .= '<p>' . __('No records available!') . '</p>';
			}     
			
			_e($html); 
			exit;
		}

		/**
		* Ajax file data log list
		*/
		function piecfw_datalog_list(){
			global $wpdb;
			
			//log file
			$filename = isset($_POST['log_file']) ? sanitize_text_field($_POST['log_file']) : '';

			// page number
			$paged = isset($_POST['paged']) ? sanitize_text_field($_POST['paged']) : 1;
			
			//condition
			$conditions = '';
			
			// sort by row column or created
			$sort['name'] = (isset($_POST['sort']) && $_POST['sort']!="") ? sanitize_text_field($_POST['sort']) : 'created_at';
			$sort['order'] = (isset($_POST['order']) && $_POST['order']!="") ? sanitize_text_field($_POST['order']) : 'DESC';
			$sort_by = ' ORDER BY ' . $sort['name'] . ' '. $sort['order'];
		
			$html = '';     
			// if we have the filename...
			if ($filename) {
		
				// initial link for pagination.
				// "page" must be the  menu slug / clean url from the add_menu_page
				$link = 'admin.php?page=piecfw_import_export&tab=logs&log_file='.$filename;
		
				// user info by WordPress function
				//$user_info = get_userdata($user_id);
		
				
				
				// example sql query to get user emails
				$sql = "SELECT *
					FROM ".$wpdb->prefix."piecfw_product_import_data_log as m 
					WHERE m.file_name='".$filename."' ".$conditions." ".$sort_by;
				$rows = $wpdb->get_results($sql);     

				// initial form html
				$html = '<h3>Total: '.sizeof($rows).'</h3>';

				$rows_per_page = 20;

				// add pagination arguments from WordPress
				$pagination_args = array(
					'base' => add_query_arg('paged','%#%'),
					'format' => '',
					'total' => ceil(sizeof($rows)/$rows_per_page),
					'current' => $paged,
					'show_all' => false,
					'type' => 'plain',
				);
		
				$start = ($paged - 1) * $rows_per_page;
				$end_initial = $start + $rows_per_page;
				$end = (sizeof($rows) < $end_initial) ? sizeof($rows) : $end_initial;
		
				// if we have results
				if (count($rows) > 0) {
					$link .= '&paged=' . $paged;        
		
					$order = $sort['order'] == "ASC" ? "DESC" : "ASC";
		
					// html table head
					$html .= '<table id="user-sent-mail" class="wp-list-table widefat fixed users">
							<thead>
							<tr class="manage-column">
								<th class="col-product_sku">
									<a class="log_sort" data-sort="product_sku" data-order="'.$order.'" data-file="'.$filename.'" data-order="'.$order.'" href="' . $link . '&sort=product_sku&order=' . $order . '">
									' . __('SKU') . '
									</a>
								</th>
								<th class="col-product_name">
									<a class="log_sort" data-sort="product_name" data-order="'.$order.'" data-file="'.$filename.'" data-order="'.$order.'" href="' . $link . '&sort=product_name&order=' . $order . '">
									' . __('Product') . '
									</a>
								</th>
								<th style="display:none;" class="col-product_type">
									<a class="log_sort" data-sort="product_type" data-order="'.$order.'" data-file="'.$filename.'" href="' . $link . '&sort=product_type&order=' . $order . '">
									' . __('Product Type') . '
									</a>
								</th>
								<th class="col-status_message">
									<a class="log_sort" data-sort="status_message" data-order="'.$order.'" data-file="'.$filename.'" href="' . $link . '&sort=status_message&order=' . $order . '">
									' . __('Status') . '
									</a>
								</th>
								<th class="col-created_at">
									<a class="log_sort" data-sort="created_at" data-order="'.$order.'" data-file="'.$filename.'" href="' . $link . '&sort=created_at&order=' . $order . '">
									' . __('Created') . '
									</a>
								</th>
							</tr>
							</thead>
							<tbody>
							';
		
					// add rows
					for ($index = $start; $index < $end;  ++$index) {
						$row = $rows[$index];
		
						$class_row = ($index % 2 == 1 ) ? ' class="alternate"' : '';
						$html .= '
							<tr ' . $class_row . '>
								<td>' . $row->product_sku . '</td>
								<td>'.$row->product_id.' - ' . $row->product_name . '</td>
								<td style="display:none;">' . $row->product_type . '</td>
								<td>' . $row->status_message . '</td>
								<td>' . $row->created_at . '</td>
							</tr>';
					}
		
					$html .= '</tbody></table>';     
				
					$html .= '<div class="pagination-links"><br>'.paginate_links($pagination_args).'</div>';
				} else {
					$html .= '<p>' . __('No records available!') . '</p>';
				}     
			}
			
			_e($html);
			exit;
		}

		/**
		* Ajax event for importing a CSV validation
		*/
		public function piecfw_import_validation(){
			global $wpdb;
			$max_filesize = (int)(ini_get("upload_max_filesize"));
			$file = sanitize_post($_FILES['file']);
			$extension = 'csv';
			$is_cron = trim(sanitize_text_field($_POST['is_cron']));

			if(isset($file))
			{
				if(isset($is_cron) && $is_cron==1){
					$status = 'Pending';
					$start_date = trim(sanitize_text_field($_POST['start_date']));
					$start_date = str_replace("/","-",$start_date);
					$start_date = $start_date.":00";
					$frequency = trim(sanitize_text_field($_POST['frequency']));
					$timezone_format = _x( 'Y-m-d H:i:s', 'timezone date format' );
					$created_at = date_i18n( $timezone_format );
			
					$upload_dir = PIECFW_UPLOAD_CRON_DIR;
					$upload_dirname = PIECFW_UPLOAD_CRON_DIR_NAME;
				}else{
					$upload_dir = PIECFW_UPLOAD_DIR;
					$upload_dirname = PIECFW_UPLOAD_DIR_NAME;
				}

				$errors= array();
				$file_name = explode(".", $file["name"]);
				$newfilename = round(microtime(true)) . '.' . end($file_name);
				$file_size = $file['size'];
				$file_tmp = $file['tmp_name'];
				$file_type = $file['type'];
				$file_ext = strtolower(end(explode('.',$file['name'])));
				$extension = str_replace(".","",$extension);
				$extensions = explode(',', $extension);          

				if(in_array($file_ext,$extensions)=== false && $extensions != '' && $extensions[0] != 'undefined'){
					$errors['error'] = '<div id="import_error" class="error">Extension not allowed, please select only csv file.</div>';
				}
				else if($file_size>($max_filesize*1000000)){
					$errors['error'] = '<div id="import_error" class="error">'.$file["name"].' exceeds the maximum upload size for this site.</div>';
				}
				else if($file['error']==1){
					$errors['error'] = '<div id="import_error" class="error">'.$file["name"].' exceeds the maximum upload size for this site.</div>';
				}
				
				if(empty($errors)==true){
					if(is_dir($upload_dir)){
						move_uploaded_file($file_tmp,$upload_dir.$newfilename);
						$file_path = $upload_dir.$newfilename;

						if(isset($is_cron) && $is_cron==1){
							$errors['success'] = '<div id="import_success" class="updated">File '.$newfilename.' uploaded successfully.</div>';
						}else{
							$errors['success'] = $newfilename;
						}
					}
					else if(mkdir($upload_dir)){
						move_uploaded_file($file_tmp,$upload_dir.$newfilename);
						$file_path = $upload_dir.$newfilename;

						if(isset($is_cron) && $is_cron==1){
							$errors['success'] = '<div id="import_success" class="updated">File '.$newfilename.' uploaded successfully.</div>';
						}else{
							$errors['success'] = $newfilename;
						}
					}else{
						$errors['error'] = '<div id="import_error" class="error"><code>'.$upload_dirname.'</code> Creating a folder Permission denied.</div>';
					}
					
					if(isset($is_cron) && $is_cron==1 && isset($errors['success'])){
						$wpdb->insert($wpdb->prefix.'piecfw_product_import_cron', array(
							'file_name' => $newfilename,
							'file_path' => $file_path,
							'start_date' => $start_date,
							'frequency' => $frequency, 
							'status' => $status,
							'created_at' => $created_at
						));
					}
				}

				_e(json_encode($errors)); die();
			}

			exit;
		}

		/**
		* Ajax event for importing a CSV
		*/
		public function piecfw_import_request() {
			define( 'WP_LOAD_IMPORTERS', true );
			PIECFW_Importer::product_importer();
		}

		/**
		* From regenerate thumbnails plugin
		*/
		public function regenerate_thumbnail() {
			header( 'Content-type: application/json' );

			$id    = (int) sanitize_text_field($_REQUEST['id']);
			$image = get_post( $id );

			if ( ! $image || 'attachment' != $image->post_type || 'image/' != substr( $image->post_mime_type, 0, 6 ) )
				die( json_encode( array( 'error' => sprintf( __( 'Failed resize: %s is an invalid image ID.', PIECFW_TRANSLATE_NAME ), esc_html( sanitize_text_field($_REQUEST['id']) ) ) ) ) );

			if ( ! current_user_can( 'manage_woocommerce' ) )
				$this->die_json_error_msg( $image->ID, __( "Your user account doesn't have permission to resize images", PIECFW_TRANSLATE_NAME ) );

			$fullsizepath = get_attached_file( $image->ID );

			if ( false === $fullsizepath || ! file_exists( $fullsizepath ) )
				$this->die_json_error_msg( $image->ID, sprintf( __( 'The originally uploaded image file cannot be found at %s', PIECFW_TRANSLATE_NAME ), '<code>' . esc_html( $fullsizepath ) . '</code>' ) );

			@set_time_limit( 900 ); // 5 minutes per image should be PLENTY

			$metadata = wp_generate_attachment_metadata( $image->ID, $fullsizepath );

			if ( is_wp_error( $metadata ) )
				$this->die_json_error_msg( $image->ID, $metadata->get_error_message() );
			if ( empty( $metadata ) )
				$this->die_json_error_msg( $image->ID, __( 'Unknown failure reason.', PIECFW_TRANSLATE_NAME ) );

			// If this fails, then it just means that nothing was changed (old value == new value)
			wp_update_attachment_metadata( $image->ID, $metadata );

			die( json_encode( array( 'success' => sprintf( __( '&quot;%1$s&quot; (ID %2$s) was successfully resized in %3$s seconds.', PIECFW_TRANSLATE_NAME ), esc_html( get_the_title( $image->ID ) ), $image->ID, timer_stop() ) ) ) );
		}

		/**
		* Die with a JSON formatted error message
		*/
		public function die_json_error_msg( $id, $message ) {
			die( json_encode( array( 'error' => sprintf( __( '&quot;%1$s&quot; (ID %2$s) failed to resize. The error message was: %3$s', 'regenerate-thumbnails' ), esc_html( get_the_title( $id ) ), $id, $message ) ) ) );
		}
	}
}
new PIECFW_AJAX_Handler();
