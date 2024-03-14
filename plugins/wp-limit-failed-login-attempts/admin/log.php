<?php
if( ! class_exists( 'WPLFLA_Attempts_Log_PRO' ) ) {
    class WPLFLA_Attempts_Log_PRO{

        public function __construct() {
			
				
            add_action( 'admin_menu', array($this,'WPLFLA_options_page') );
            add_action('admin_enqueue_scripts', array($this,'my_enqueue'));
            add_action( 'wp_ajax_WPLFLA_get_log_data', array($this,'my_ajax_get_log_data') );
            add_action( 'wp_ajax_nopriv_WPLFLA_get_log_data', array($this,'my_ajax_get_log_data') );
			
        }
		

        public function WPLFLA_options_page() {

            add_submenu_page( '', __('Attempts Log','codepressFailed_pro'), __('Attempts log','codepressFailed_pro'),'manage_options', 'WPLFLALOG',array($this,"WPLFLA_log"));
        }
		
		public function WPLFLA_esc_sql($str) {

            return str_ireplace("'", "", $str);
        }

        function my_ajax_get_log_data() {
            if (!current_user_can('manage_options')){
				return;
			}
            global $wpdb;
            $draw = absint($_POST['draw']);
			$row = absint($_POST['start']);
			$rowperpage = absint($_POST['length']); // Rows display per page
			$columnIndex = absint($_POST['order'][0]['column']); // Column index

			$sortOrderBy = sanitize_sql_orderby($_POST['columns'][$columnIndex]['data'] . " " . $_POST['order'][0]['dir']);
			if ($sortOrderBy == false){
				// default sorting if given one is invalid
				$sortOrderBy = 'id DESC';
			}
			$searchValue = sanitize_text_field($_POST['search']['value']);

    ## Search
    $searchQuery = " ";
    if($searchValue != ''){
        $escapedSearchValue = esc_sql($wpdb->esc_like($searchValue));

        $searchQuery = " and (username like '%". $escapedSearchValue . "%' or ip like '%". $escapedSearchValue ."%' or country like '%". $escapedSearchValue ."%' or city like '%". $escapedSearchValue ."%' ) ";
    }

            ## Total number of records without filtering
            $totalRecords = $wpdb->get_var("SELECT count(*) FROM ".$wpdb->prefix."WPLFLA_login_failed ");

            ## Total number of record with filtering
            $totalRecordwithFilter = $wpdb->get_var( "SELECT count(*)as allcount FROM ".$wpdb->prefix."WPLFLA_login_failed WHERE 1 ".$searchQuery);

          ## Fetch records
            $empRecords = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."WPLFLA_login_failed  WHERE 1=1 ".$searchQuery." order by ". $sortOrderBy." limit ".$row.", ".$rowperpage);


            $data = array();
            foreach ($empRecords as $row) {
                $user_info = get_user_by( 'login', $row->username);
                if($user_info){
                    $text_roles = implode(", ",$user_info->roles );
                }else{
                    $text_roles = "N/A";
                }
				
				
				
				
				$country_code_img = !empty($row->country_code) ? esc_html($row->country_code) : 'defalut';
				
				$country_code = (isset($row->country_code) && trim($row->country_code)!='Not Found') ? $row->country_code : '';
				$country_city = (isset($row->country) && trim($row->country)!='Not Found') ? '<img width="18px"  src="'.esc_url(WPLFLA_PLUGIN_URL.'/assets/images/flags/'.strtolower($country_code_img).'.png').'" >&nbsp;'.$row->country."-".$row->city : '';
				
				
				
                $data[] = array(
                    "username"=>esc_html($row->username),
                    "password"=>'<a target="_blank"  href="https://www.wp-buy.com/product/wp-limit-failed-login-attempts-pro/">Upgrade to PRO</a>',
                    "roles"=>$text_roles,
                    "ip"=>'<a href="https://db-ip.com/'.esc_html($row->ip).'" target="_blank">'. esc_html($row->ip).'</a>',
                    "country"=> $country_city,
                    "date"=>esc_html($row->date)
                );
            }

            ## Response
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordwithFilter,
                "aaData" => $data
            );


            //return the result to the ajax request and die
            echo json_encode($response);

            wp_die();
        }
        function my_enqueue($hook){
			
			
			if (strpos($hook, 'WPLFLALOG') !== false) {	
            
            wp_enqueue_script('datatables_log', plugin_dir_url(__FILE__) . '../assets/js/datatables.min.js', array('jquery') );
            wp_localize_script( 'datatables_log', 'datatablesajax_log', array('url' => admin_url('admin-ajax.php')) );
            wp_enqueue_script('datatables_log_responsive', plugin_dir_url(__FILE__) . '../assets/js/dataTables.responsive.min.js', array('jquery') );
            wp_enqueue_style('datatables', plugin_dir_url(__FILE__) . '../assets/css/datatables.min.css' );
            wp_enqueue_style('responsive_dataTables', plugin_dir_url(__FILE__) . '../assets/css/responsive.dataTables.min.css' );
			}
            //wp_enqueue_script('my_custom_script', plugin_dir_url(__FILE__) . '/myscript.js');
        }
        public function clear_log(){
            global $wpdb;
            $table  = $wpdb->prefix . 'WPLFLA_login_failed';
            $delete = $wpdb->query("TRUNCATE TABLE $table");
            if($delete){
                $this->update_notice();
                return true;
            }else{
                $this->error_notice();
                return false;
            }
        }

        function update_notice() {
            ?>
            <div class="updated notice">
                <p><?php _e( 'The operation completed successfully.', 'codepressFailed_pro' ); ?></p>
            </div>
            <?php
        }
        function error_notice() {
            ?>
            <div class="error notice">
                <p><?php _e( 'There has been an error. !', 'codepressFailed_pro' ); ?></p>
            </div>
            <?php
        }

        public function WPLFLA_log(){
            if(isset($_GET["clear"])){
                $this->clear_log();
            }
            $menu = new WPLFLA_menu();
            $menu->menu();
            ?>
            <div class="container-tap">
                <h2><?php _e( 'Failed Attempts Log', 'codepressFailed_pro' );?>&nbsp;<a  href="admin.php?page=WPLFLALOG&clear=1" class="button button-secondary "><?php _e( 'Clear log', 'codepressFailed_pro' );?></a></h2>



                <table id="table"  class="display responsive nowrap failed_login_rep" style="width:100%">
                    <thead>
                    <tr role="row" style="background-color:#ffffff; text-align:left">
                        <th><?php _e( 'Username', 'codepressFailed_pro' );?></th>
                        <!--<th><?php _e( 'Password', 'codepressFailed_pro' );?></th>-->
                        <th><?php _e( 'Role', 'codepressFailed_pro' );?></th>
                        <th><?php _e( 'Password', 'codepressFailed_pro' );?></th>
                        <th><?php _e( 'IP', 'codepressFailed_pro' );?></th>
                        <th><?php _e( 'Country', 'codepressFailed_pro' );?></th>
                        <th><?php _e( 'Date', 'codepressFailed_pro' );?></th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <script>

                jQuery(document).ready(function($) {
					var column_orderby = $('#table').find("th:contains('Date')")[0].cellIndex;

                    var jobtable = $('#table').DataTable({
						"pageLength": 20,
                        "order": [[ column_orderby, "desc" ]],
                        'processing': false,
                        'serverSide': true,
                        'serverMethod': 'post',

                        ajax: {
                            url: datatablesajax_log.url + '?action=WPLFLA_get_log_data',
                            dataType: 'json',
                            cache: false,

                        },
                        "columnDefs": [
                            { "orderable": false, "targets": 1 }
                        ],

                        "aoColumns": [
                            { data: 'username' },
                          //  { data: 'password' },
                            { data: 'roles' },
                            { data: 'password' },
                            { data: 'ip' },
                            { data: 'country' },
                            { data: 'date' }
                        ],


                    });
                });
            </script>
            <?php
        }

    }


    new WPLFLA_Attempts_Log_PRO();
}
