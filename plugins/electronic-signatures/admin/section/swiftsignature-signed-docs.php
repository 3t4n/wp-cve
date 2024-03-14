<?php
/*
 *       SwiftSignature local capture page
 */

function ssing_log_pagination($num_of_pages, $pagenum, $total_filtered_log, $limit) {
    $page_links = paginate_links(array(
        'base' => add_query_arg('pagenum', '%#%'),
        'format' => '',
        'prev_text' => __('&laquo;', 'swift-cloud'),
        'next_text' => __('&raquo;', 'swift-cloud'),
        'total' => $num_of_pages,
        'current' => $pagenum
    ));
    if ($page_links) {
        if ($total_filtered_log > $limit) {
            echo '<div class="tablenav" id="swiftlog-pagination"><div class="tablenav-pages">' . $page_links . '</div></div>';
        }
    }
}

function ssign_signed_docs_cb() {
    global $wpdb;

    wp_enqueue_style('swiftcloud-fontawesome', "//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css", '', '', '');
    wp_enqueue_script('swiftcloud-timeago', plugins_url('../js/jquery.timeago.js', __FILE__), 'jquery', '', true);
    $SSIGN_MESSAGES = swiftsign_global_msg();

    $table_name = $wpdb->prefix . 'ssing_log';
    $where = " WHERE 1 ";
    $order_by = " ORDER BY `ssign_id` desc";

    $pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
    $limit = 100; // number of rows in page
    $offset = ( $pagenum - 1 ) * $limit;
    $total = $wpdb->get_var("SELECT count(*) FROM $table_name $where $order_by");
    $num_of_pages = ceil($total / $limit);
    $total_filtered_log = $wpdb->get_var("SELECT count(*) FROM $table_name $where $order_by");

    $fLog = $wpdb->get_results("SELECT * FROM $table_name $where $order_by LIMIT $offset,$limit");
    ?>
    <div class="wrap">
        <h2 class="swiftpage-title"><?php echo $SSIGN_MESSAGES['ssing_page_title_signed_docs']; ?></h2><hr>
        <div class="inner_content">
            <table class="wp-list-table widefat fixed users" cellspacing="0">
                <thead>
                    <tr>
                        <th><?php echo $SSIGN_MESSAGES['ssign_signed_doc_name']; ?></th>
                        <th><?php echo $SSIGN_MESSAGES['ssign_signed_doc_email']; ?></th>
                        <th><?php echo $SSIGN_MESSAGES['ssign_signed_doc_datetime']; ?></th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($fLog)): ?>
                        <?php foreach ($fLog as $log): ?>
                            <tr>
                                <td><?php echo (!empty($log->ssign_capture_name) ? $log->ssign_capture_name : "Name Not Given"); ?></td>
                                <td><?php echo $log->ssign_capture_email; ?></td>
                                <td><time class="timeago" datetime="<?php echo $log->date_time; ?>"><?php echo $log->date_time; ?></time></td>
                                <td><a href="<?php echo admin_url() . '?page=ssign_signed_docs_details&ssing_log=' . $log->ssign_id; ?>" target="_blank"><i class="fa fa-search"></i></a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4"><h3><center><?php echo $SSIGN_MESSAGES['ssing_msg_no_records']; ?></center></h3></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php ssing_log_pagination($num_of_pages, $pagenum, $total_filtered_log, $limit); ?>
        </div>
    </div>
    <?php
}

/*
 *      Log detail page
 */

function ssign_signed_docs_details_cb() {
    global $wpdb;
    $table_name = $wpdb->prefix . "ssing_log";
    $fLogDetail = false;
    if (isset($_GET['ssing_log']) && !empty($_GET['ssing_log'])) {
        $fLog = $wpdb->get_results("SELECT * FROM $table_name WHERE ssign_id='" . $_GET['ssing_log'] . "' ");
        $fLogDetail = (isset($fLog[0]) && !empty($fLog[0])) ? $fLog[0] : false;
    }
    wp_enqueue_script('swiftcloud-timeago', plugins_url('../js/jquery.timeago.js', __FILE__), 'jquery', '', true);
    ?>
    <div class="wrap">
        <h2>Form Log Detail</h2> <a href="<?php echo admin_url() . 'admin.php?page=ss_signed_docs'; ?>">Back to Log List</a>
        <div class="inner_content">
            <table cellspacing="0" class="widefat striped fixed users">
                <?php if ($fLogDetail) : ?>
                    <tr>
                        <td width="30%">Name: </td>
                        <td width="70%"><?php echo $fLogDetail->ssign_capture_name; ?></td>
                    </tr>
                    <tr class="">
                        <td>Email Address: </td>
                        <td><?php echo $fLogDetail->ssign_capture_email; ?></td>
                    </tr>
                    <tr>
                        <td>Captured Data: </td>
                        <td>
                            <?php
                            if (!empty($fLogDetail->ssign_capture_data)) {
                                if ($captured_data = json_decode($fLogDetail->ssign_capture_data)) {
                                    $op = '';
                                    foreach ($captured_data as $hv_key => $hv_val) {
                                        $op.='<li>';
                                        $op.= '<strong>' . $hv_key . "</strong> : ";
                                        if (!empty($hv_val)) {
                                            if (stripos($hv_key, "ss_signDataURL") !== false) {
                                                $code_base64 = $hv_val;
                                                $code_base64 = str_replace('data:image/png;base64,', '', $code_base64);
                                                $code_binary = base64_decode($code_base64);
                                                $op .= '<img src="data:image/png;base64,' . $code_base64 . '" style="vertical-align: middle;" alt="signature" />';
                                            } else if (stripos($hv_key, "extra_date_dropdown") !== false) {
                                                $op .= @implode('-', $hv_val);
                                            } else {
                                                if (is_array($hv_val)) {
                                                    $op .= @implode(', ', $hv_val);
                                                } else {
                                                    $op .= $hv_val;
                                                }
                                            }
                                        } else {
                                            $op .= "<b>--</b>";
                                        }
                                        $op.='</li>';
                                    }
                                    echo '<ul>' . $op . '</ul>';
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Date: </td>
                        <td>
                            <abbr class="timeago" title="<?php echo $fLogDetail->date_time; ?>"></abbr>
                        </td>
                    </tr>
                    <tr class="">
                        <td><a href="<?php echo admin_url() . 'admin.php?page=ss_signed_docs'; ?>">Back to Log List</a></td>
                        <td>&nbsp;</td>
                    </tr>
                <?php else: ?>
                    <tr id='user-1' class="">
                        <td scope='row' class='check-column' colspan="9" align="center" valign="middle"><?php _e('No Record found.', 'swift-mortgage-app') ?></th>
                    </tr>
                <?php
                endif; //first if end
                ?>
                </tbody>
            </table>
            <script type="text/javascript">
                jQuery(document).ready(function() {
                    jQuery("abbr.timeago").timeago();
                });
            </script>
        </div>
    </div>
    <?php
}