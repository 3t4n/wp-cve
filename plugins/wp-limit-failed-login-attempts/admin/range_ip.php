<?php
if (!class_exists('WPLFLA_range_ip')) {
    class WPLFLA_range_ip
    {

        public function __construct()
        {
            add_action('admin_menu', array($this, 'WPLFLA_options_page'));
            add_action('admin_enqueue_scripts', array($this, 'my_enqueue'));
            add_action('wp_ajax_WPLFLA_range_ip', array($this, 'my_ajax_get_range_ip_data'));
            add_action('wp_ajax_nopriv_WPLFLA_range_ip', array($this, 'my_ajax_get_range_ip_data'));
        }

        public function WPLFLA_options_page()
        {

            add_submenu_page('WPLFLA', __('Settings', 'codepressFailed_pro'), __('Settings', 'codepressFailed_pro'), 'manage_options', 'WPLFLARANGEIP', array($this, "WPLFLA_range_ip"));
        }

        function my_ajax_get_range_ip_data()
        {
            global $wpdb;
            ## Read value
            $draw = absint($_POST['draw']);
            $row = absint($_POST['start']);
            $rowperpage = absint($_POST['length']); // Rows display per page
            $columnIndex = absint($_POST['order'][0]['column']); // Column index
            $columnName = sanitize_text_field($_POST['columns'][$columnIndex]['data']); // Column name
            $columnSortOrder = sanitize_text_field($_POST['order'][0]['dir']); // asc or desc
            $searchValue = sanitize_text_field($_POST['search']['value']);

            ## Search  WPLFLA_block_ip_range
            $searchQuery = "";
            if ($searchValue != '') {
                $searchQuery = " and (start_ip like '%" . $searchValue . "%' or end_ip like '%" . $searchValue . "%' or date like '%" . $searchValue . "%') ";
            }
            $searchQuery .= " and `type_intr` = " . absint($_GET['type']);

            ## Total number of records without filtering
            $totalRecords = $wpdb->get_var("SELECT count(*) FROM " . $wpdb->prefix . "WPLFLA_block_ip_range ");

            ## Total number of record with filtering
            $totalRecordwithFilter = $wpdb->get_var("SELECT count(*)as allcount FROM " . $wpdb->prefix . "WPLFLA_block_ip_range WHERE 1 " . $searchQuery);

            ## Fetch records
            $empRecords = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "WPLFLA_block_ip_range  WHERE 1 " . $searchQuery . " order by " . $columnName . " " . $columnSortOrder . " limit " . $row . "," . $rowperpage);
            $data = array();
            foreach ($empRecords as $row) {

                $data[] = array(

                    "start_ip" => $row->start_ip,
                    "end_ip" => $row->end_ip,
                    "date" => $row->date,
                    "action" => '<a href="' . admin_url('admin.php?page=WPLFLARANGEIP&step=delete&id=') . $row->id . '" class="delete_ip"  onclick = "if (! confirm(\'' . __('Are you sure to delete this IP?', 'codepressFailed_pro') . '\')) { return false; }"><i class="fa fa-trash" aria-hidden="true"></i></a>'
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
        function my_enqueue($hook)
        {
            if (strpos($hook, '_page_WPLFLARANGEIP') !== false) {


                wp_enqueue_script('datatables_range_ip', plugin_dir_url(__FILE__) . '../assets/js/datatables.min.js', array('jquery'));
                wp_localize_script('datatables_range_ip', 'datatablesajax_log', array('url' => admin_url('admin-ajax.php')));
                wp_enqueue_script('datatables_log_responsive', plugin_dir_url(__FILE__) . '../assets/js/dataTables.responsive.min.js', array('jquery'));
                wp_enqueue_style('datatables', plugin_dir_url(__FILE__) . '../assets/css/datatables.min.css');
                wp_enqueue_style('responsive_dataTables', plugin_dir_url(__FILE__) . '../assets/css/responsive.dataTables.min.css');
                wp_enqueue_style('font-awesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
                wp_enqueue_style('failed_admin-pro-css', WPLFLA_PLUGIN_URL . '/assets/css/admin-css.css?re=1.2');
            }


            //wp_enqueue_script('my_custom_script', plugin_dir_url(__FILE__) . '/myscript.js');
        }
        public function delete($id)
        {
            global $wpdb;
            $table  = $wpdb->prefix . 'WPLFLA_block_ip_range';
            $delete = $wpdb->query($wpdb->prepare(
                "DELETE FROM $table WHERE id = %d",
                $id
            ));
            if ($delete) {
                $this->update_notice();
                return true;
            } else {
                $this->error_notice();
                return false;
            }
        }

        function update_notice()
        {
?>
            <div class="updated notice">
                <p><?php _e('The operation completed successfully.', 'codepressFailed_pro'); ?></p>
            </div>
        <?php
        }
        function error_notice()
        {
        ?>
            <div class="error notice">
                <p><?php _e('There has been an error. !', 'codepressFailed_pro'); ?></p>
            </div>
        <?php
        }
        public function registration_ip($start, $end, $type)
        {
            if (empty($start) || empty($end)) {
                $this->error_notice();
                return false;
            }

            global $wpdb;

            $table_name = $wpdb->prefix . 'WPLFLA_block_ip_range';
            $insert = $wpdb->query(
                $wpdb->prepare(
                    "
                   INSERT INTO $table_name
                   ( start_ip, end_ip,type_intr, date )
                   VALUES ( %s, %s, %d, %s)
                   ",
                    array(
                        $start,
                        $end,
                        $type,
                        date_i18n('Y-m-d H:i:s')

                    )
                )
            );

            $last_insert_id = $wpdb->insert_id;
            if ($type == 1) {
                $from_menu_send_email = new WPLFLA_menu();
                $from_menu_send_email->send_mail_block_range_ip($start, $end);
            }
            if ($last_insert_id) {
                $this->update_notice();
                return true;
            } else {
                $this->error_notice();
                return false;
            }
        }
        // Function to validate IP address using regular expression
        private function validateIPAddress($ip)
        {
            $pattern = '/^(25[0-5]|2[0-4][0-9]|[0-1]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[0-1]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[0-1]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[0-1]?[0-9][0-9]?)$/';

            return preg_match($pattern, $ip);
        }
        public function WPLFLA_range_ip()
        {
            if (isset($_GET["step"]) && $_GET["step"] == 'delete' && isset($_GET["id"]) && $_GET["id"] != '') {
                $this->delete(absint($_GET["id"]));
            }

            if (isset($_POST["start"]) && isset($_POST["end"])) {
                $start = sanitize_text_field($_POST["start"]);
                $end = sanitize_text_field($_POST["end"]);

                if ($this->validateIPAddress($start) && $this->validateIPAddress($end)) {
                    $startIP = ip2long($start);
                    $endIP = ip2long($end);

                    // Check if $end is greater than $start
                    if ($endIP > $startIP) {
                        $insert = $this->registration_ip($start, $end, sanitize_text_field($_POST["type"]));
                        if ($insert) {
                            $_POST = array();
                        }
                    } else {
                        echo '<div class="error notice"><p>';
                        _e('The ending IP must be greater than the starting IP', 'wp-list-filter');
                        echo '</p></div>';
                    }
                } else {
                    echo '<div class="error notice"><p>';
                    _e('Please enter a valid IP range', 'wp-list-filter');
                    echo '</p></div>';
                }
            }

            $menu = new WPLFLA_menu();
            $menu->menu();
        ?>
            <div class="container-tap">
                <h2><?php _e('Block a range of IP addresses', 'codepressFailed_pro'); ?>&nbsp;</h2>

                <form class="example" action="<?php esc_attr_e(admin_url('admin.php?page=WPLFLARANGEIP')); ?>" method="post" style="margin-top: 20px;max-width:600px;margin-bottom: 25px;">
                    <input required type="text" style="max-width: 225px;" placeholder="<?php _e('From ex:192.168.1.1', 'codepressFailed_pro'); ?>" name="start">
                    <input required type="text" style="max-width: 225px;" placeholder="<?php _e('To ex:192.168.1.256', 'codepressFailed_pro'); ?>" name="end">
                    <input type="hidden" name="type" value="1">
                    <button type="submit"><i class="fa fa-floppy-o"></i></button>
                </form>

                <table id="table" class="display responsive nowrap failed_login_rep" style="width:100%">
                    <thead>
                        <tr role="row" style="background-color:#ffffff; text-align:left">
                            <th><?php _e('Range From', 'codepressFailed_pro'); ?></th>
                            <th><?php _e('To', 'codepressFailed_pro'); ?></th>
                            <th><?php _e('Date', 'codepressFailed_pro'); ?></th>
                            <th><?php _e('Action', 'codepressFailed_pro'); ?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>


                <h2><?php _e('Exclude IP addresses', 'codepressFailed_pro'); ?>&nbsp;</h2>

                <form class="example" action="<?php esc_attr_e(admin_url('admin.php?page=WPLFLARANGEIP')); ?>" method="post" style="margin-top: 20px;max-width:600px;margin-bottom: 25px;">
                    <input required type="text" style="max-width: 225px;" placeholder="<?php _e('From ex:192.168.1.1', 'codepressFailed_pro'); ?>" name="start">
                    <input required type="text" style="max-width: 225px;" placeholder="<?php _e('To ex:192.168.1.256', 'codepressFailed_pro'); ?>" name="end">
                    <input type="hidden" name="type" value="2">
                    <button type="submit"><i class="fa fa-floppy-o"></i></button>
                </form>

                <table id="tableex" class="display responsive nowrap failed_login_rep" style="width:100%">
                    <thead>
                        <tr role="row" style="background-color:#ffffff; text-align:left">
                            <th><?php _e('Range From', 'codepressFailed_pro'); ?></th>
                            <th><?php _e('To', 'codepressFailed_pro'); ?></th>
                            <th><?php _e('Date', 'codepressFailed_pro'); ?></th>
                            <th><?php _e('Action', 'codepressFailed_pro'); ?></th>
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
                        "order": [
                            [column_orderby, "desc"]
                        ],
                        'processing': false,
                        'serverSide': true,
                        'serverMethod': 'post',

                        ajax: {
                            url: datatablesajax_log.url + '?action=WPLFLA_range_ip&type=1',
                            dataType: 'json',
                            cache: false,

                        },
                        "columnDefs": [{
                            "orderable": false,
                            "targets": 1
                        }],

                        "aoColumns": [{
                                data: 'start_ip'
                            },
                            {
                                data: 'end_ip'
                            },
                            {
                                data: 'date'
                            },
                            {
                                data: 'action'
                            }
                        ],


                    });
                });

                jQuery(document).ready(function($) {
                    var column_orderby = $('#tableex').find("th:contains('Date')")[0].cellIndex;

                    var jobtable = $('#tableex').DataTable({
                        "pageLength": 20,
                        "order": [
                            [column_orderby, "desc"]
                        ],
                        'processing': false,
                        'serverSide': true,
                        'serverMethod': 'post',

                        ajax: {
                            url: datatablesajax_log.url + '?action=WPLFLA_range_ip&type=2',
                            dataType: 'json',
                            cache: false,

                        },
                        "columnDefs": [{
                            "orderable": false,
                            "targets": 1
                        }],

                        "aoColumns": [{
                                data: 'start_ip'
                            },
                            {
                                data: 'end_ip'
                            },
                            {
                                data: 'date'
                            },
                            {
                                data: 'action'
                            }
                        ],


                    });
                });
            </script>
<?php
        }
    }


    new WPLFLA_range_ip();
}
