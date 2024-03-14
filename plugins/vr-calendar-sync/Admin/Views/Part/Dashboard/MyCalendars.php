<?php
 /**
  * PHP Version 8.0
  * Short description: Booking calendar created by Innate Images, LLC
  * Views
  * 
  * Views
  * 
  * @category  VRCalendarTable
  * @package   VRCalendarTable
  * @author    Innate Images, LLC <info@innateimagesllc.com>
  * @copyright 2015 Innate Images, LLC
  * @license   GPL-2.0+ http://www.vrcalendarsync.com
  * @link      http://www.vrcalendarsync.com
  */
?>
<h2>
    <?php _e('My Calendars', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
    <a href="<?php echo esc_url(admin_url('admin.php?page='.VRCALENDAR_PLUGIN_SLUG.'-add-calendar')) ?>" class="add-new-h2"><?php _e('Add new', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></a>
</h2>
<form id="my-calendars" name="my-calendars" method="post" action="">
    <?php
    $VRCalendarTable = new VRCalendarTable();
    $VRCalendarTable->prepare_items();
    $VRCalendarTable->display();
    $VRCalendarTable->process_bulk_action();
    ?>
</form>
<?php
/**
 * Short description: Booking calendar created by Innate Images, LLC
 * PHP Version 8.0
 
 * @category  VRCalendarTable
 * @package   VRCalendarTable
 * @author    Innate Images, LLC <info@innateimagesllc.com>
 * @copyright 2015 Innate Images, LLC
 * @license   GPL-2.0+ http://www.vrcalendarsync.com
 * @link      http://www.vrcalendarsync.com
 */

 /**
  * Short description: Booking calendar created by Innate Images, LLC
  * VRCalendarTable
  * 
  * VRCalendarTable
  * 
  * @category  VRCalendarTable
  * @package   VRCalendarTable
  * @author    Innate Images, LLC <info@innateimagesllc.com>
  * @copyright 2015 Innate Images, LLC
  * @license   GPL-2.0+ http://www.vrcalendarsync.com
  * @link      http://www.vrcalendarsync.com
  */
class VRCalendarTable extends WP_List_Table
{
    /**
     * Define template file
     **/
    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Get column based on web instance
     * 
     * @param array  $cal         calendar data
     * @param string $column_name column name
     * 
     * @return String
     */
    function column_default($cal, $column_name)
    {
        /* display all dynamic data from database  */
        switch ($column_name)
        {
        case 'title':
            echo  esc_html($cal['calendar_name']);
            break;
        case 'calendar_shortcode':
            echo  esc_html('[vrcalendar id="'.$cal['calendar_id'].'" /]');
            break;
        case 'author':
            echo  get_the_author_meta('display_name', $cal['calendar_author_id']);
            break;
        case 'last_synchronized':
            echo  get_date_from_gmt($cal['calendar_last_synchronized'], 'F d, Y \a\t h:i a');
            break;
        case 'created_on':
            echo  get_date_from_gmt($cal['calendar_created_on'], 'Y-m-d');
            break;
        default:
            return $cal->$column_name;
        }
    }
    /**
     * Column title based on web instance
     * 
     * @param array $cal calendar data
     * 
     * @return String
     */
    function column_title($cal)
    {
        $nonce = wp_nonce_url(admin_url('admin.php?page='.VRCALENDAR_PLUGIN_SLUG.'-dashboard&vrc_cmd=calendarRemove&cal_id='.$cal['calendar_id'], 'remove-cal'), 'vr-calendar-sync-nonce');
        $actions = array(
            'edit' => '<a href="' .admin_url('admin.php?page='.VRCALENDAR_PLUGIN_SLUG.'-add-calendar&cal_id='.$cal['calendar_id']). '">'.__('Edit', VRCALENDAR_PLUGIN_TEXT_DOMAIN).'</a>',
            'delete' => '<a href="'.$nonce.'">'.__('Delete', VRCALENDAR_PLUGIN_TEXT_DOMAIN).'</a>',
            'sync' => '<a href="' .admin_url('admin.php?page='.VRCALENDAR_PLUGIN_SLUG.'-dashboard&vrc_cmd=calendarSync&cal_id='.$cal['calendar_id']). '">'.__('Sync', VRCALENDAR_PLUGIN_TEXT_DOMAIN).'</a>'
        );
        return $cal['calendar_name'].$this->row_actions($actions);
    }

    /**
     * Column cb based on web instance
     * 
     * @param array $cal calendar data
     * 
     * @return String
     */
    function column_cb($cal)
    {
        return '<input type="checkbox" name="check[]" value="'.$cal['calendar_id'].'" />';
    }

    /**
     * Get columns based on web instance
     * 
     * @return String
     */
    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox"/>',
            'title' => __('Title', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            'calendar_shortcode' =>__('Calendar Shortcode', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            'author'=> __('Author', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            'last_synchronized'=> __('Last Sync', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            'created_on' =>__('Date', VRCALENDAR_PLUGIN_TEXT_DOMAIN)
        );
        return $columns;
    }

    /**
     * Bulk action process based on web instance
     * 
     * @return String
     */
    function process_bulk_action()
    {
        if (isset($_REQUEST["check"])) {
            $check = sanitize_text_field($_REQUEST["check"]);
            if ('trash'===$this->current_action() ) {
                $msg = 'delete';
                global $wpdb;
                $calendar_table = $wpdb->prefix."vrcalandar";
                foreach ($check as $cal_id) {
                    $cal_query = "DELETE FROM {$calendar_table} WHERE calendar_id=%d";
                    $wpdb->query($wpdb->prepare($cal_query, $cal_id));
                }
                
                exit;
            }
        }
    }

    /**
     * Sort columns based on web instance
     * 
     * @return String
     */
    function get_sortable_columns()
    {
        $sortable_columns = array(
            'title' => array(
                'calendar_name',
                false
            ),
            'created_date' => array(
                'created_date',
                false
            )
        );
        return $sortable_columns;
    }

    /**
     * Bulk actions based on web instance
     * 
     * @return String
     */
    function get_bulk_actions()
    {
        $actions = array(
            'trash' => __('Trash', VRCALENDAR_PLUGIN_TEXT_DOMAIN)
        );
        return $actions;
    }

    /**
     * Prepare items based on web instance
     * 
     * @return String
     */
    function prepare_items()
    {
        global $wpdb;
        $calendar_table = $wpdb->prefix."vrcalandar";
        $cal_per_page   = 10;

        $cal_query = "SELECT * FROM {$calendar_table}";
        $calendar_data = $wpdb->get_results($cal_query, ARRAY_A);
        $columns   = $this->get_columns();
        $sortable  = $this->get_sortable_columns();
        $this->process_bulk_action();
        $this->_column_headers = array(
            $columns,
            array(),
            $sortable
        );

        //pagging code starts from here
        $current_page = $this->get_pagenum();
        $total_cal = count($calendar_data);
        $calendar_data = array_slice(
            $calendar_data, (
                ($current_page-1)*$cal_per_page
            ), $cal_per_page
        );
        $this->items = $calendar_data;

        $this->set_pagination_args(
            array(
                'total_items'=>$total_cal,
                'per_page'=> $cal_per_page,
                'total_pages'=>ceil($total_cal/$cal_per_page)
            )
        );
    }

    /**
     * Sort data based on web instance
     * 
     * @param array $a order by
     * @param array $b order by
     * 
     * @return String
     */
    public function sort_data($a, $b)
    {
        // Set defaults
        $orderby = 'calendar_name';
        $order   = 'asc';
        // If orderby is set, use this as the sort column
        if (!empty($_GET['orderby'])) {
            $orderby = sanitize_text_field($_GET['orderby']);
        }
        // If order is set use this as the order
        if (!empty($_GET['order'])) {
            $order = sanitize_text_field($_GET['order']);
        }
        $result = strnatcmp($a->$orderby, $b->$orderby);
        if ($order =='asc') {
            return $result;
        }
        return -$result;
    }
}
