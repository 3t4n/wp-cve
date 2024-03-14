<?php 
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

/**
 * 
 * @description Save generated shortcode
 * 
 */
function wshs_save_shortcode() {

    if (!current_user_can( 'manage_options' ) ) {
        return wp_send_json( array( 'result' => 'Authentication error' ) );
    }

    check_ajax_referer('ajax-nonce', 'security');
    global $wpdb;

    $code = stripcslashes(htmlspecialchars_decode($_POST['code']));
    $type = esc_html($_POST['type']);
    $title = esc_html($_POST['title']);
    $id = esc_html($_POST['id']);
    $atts_array = shortcode_parse_atts('[wshs_list post_type="page" name="Page Sitemap" order_by="date" order ="asc"]');
    $post_data = array(
        'title' => $title,
        'attributes' => $code,
        'user_id' => get_current_user_id(),
        'code_type' => $type,
        'updated_at' => date('Y-m-d H:i:s')
    );

    if($id > 0){
        $wpdb->update( $wpdb->base_prefix.WSHS_SAVED_CODE_TABLE, $post_data, array( 'id' => $id ), array( '%s','%s','%d','%s','%s'), array( '%d' ) );
    } else {
        $post_data['created_at'] = date('Y-m-d H:i:s');
        $format = array('%s','%s','%d','%s','%s','%s');
        $wpdb->insert($wpdb->base_prefix.WSHS_SAVED_CODE_TABLE,$post_data,$format);
        $id = $wpdb->insert_id;
    }

    wp_send_json(array('id' => $id));
}
add_action('wp_ajax_wshs_save_shortcode', 'wshs_save_shortcode');
add_action('wp_ajax_nopriv_wshs_save_shortcode', 'wshs_save_shortcode');

function wshs_saved(){
    global $wpdb;
    $message = '';
    $table_name = $wpdb->prefix . WSHS_SAVED_CODE_TABLE;
    $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
    if (!empty($id) && isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete'){
        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = ".esc_sql($id)));
        //$wpdb->query($wpdb->prepare("DELETE FROM $wpdb->postmeta WHERE post_id = %d",   13, 'gargle'   ));

        
        
        $message = "Shortcode deleted successfully.";
    }
    $table = new WSHS_Saved_Code_Table();
    ?>
    <div class="wrap wtl-main">
        <h1 class="wp-heading-inline">WordPress Simple HTML Sitemap</h1>
        <hr class="wp-header-end">
        <?php if(!empty($message)): ?>
            <div class="updated notice">
                <p><?php echo $message; ?></p>
            </div>
        <?php endif; ?>
        
        <div id="post-body" class="metabox-holder columns-3">
            <!-- Top Navigation -->
            <div class="sitemap-wordpress">
                <h2 class="nav-tab-wrapper">
                    <a href="?page=wshs_page_list" class="nav-tab">Pages</a>
                    <a href="?page=wshs_post_list" class="nav-tab">Posts</a>
                    <a href="?page=wshs_saved" class="nav-tab nav-tab-active">Saved Shortcodes</a>
                    <a href="?page=wshs_documentation" class="nav-tab">Documentation</a>
                </h2>
                <div class="sitemap-pages">
                    <div class="shortcode-container shortcode-item-list">
                        <?php 
                            // Prepare table
                            $table->prepare_items();
                            // Display table
                            $table->display(); 
                        ?>
                    </div>
                    <div class="clear"></div>
                </div>
                <!-- Sidebar Advertisement -->
                <?php require_once WSHS_PLUGIN_PATH . '/inc/wshs_sidebar.php'; ?>
                <!-- Sidebar Advertisement -->
            </div>
        </div> 
    </div> 
<?php }


// Extending class
class WSHS_Saved_Code_Table extends WP_List_Table
{
    private $table_data;
    public function get_columns(){
        $columns = array(
                'title'          => __('Name', 'whsh'),
                'attributes'         => __('Shortcode', 'whsh'),
                // 'created_at'   => __('Generated On', 'whsh'),
                'action'   => __('Action', 'whsh'),
        );
        return $columns;
    }

    // Bind table with columns, data and all
    public function prepare_items(){
        $this->table_data = $this->get_table_data();
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $this->table_data;
    }

    private function get_table_data() {
        global $wpdb;
        $table = $wpdb->prefix . WSHS_SAVED_CODE_TABLE;
        return $wpdb->get_results( "SELECT * from {$table} ORDER BY ID DESC", ARRAY_A );
    }

    function column_default($item, $column_name){
        switch ($column_name) {
        case 'created_at':
            return date('Y-m-d', strtotime($item[$column_name]));
        case 'attributes':
            return '<pre>'.$item[$column_name].'</pre>';
        case 'title':
            return '<strong>'.$item[$column_name].'</strong> Date: '.date('d M, Y', strtotime($item['created_at']));
        case 'action':
            if($item['code_type'] == 'page'):
                return '<a href="'.admin_url( 'admin.php?page=wshs_page_list&id='.$item['id']).'" class="button">Edit</a><a href="'.admin_url( 'admin.php?page=wshs_saved&action=delete&id='.$item['id']).'" class="button">Delete</a>';
            else:
                return '<a href="'.admin_url( 'admin.php?page=wshs_post_list&id='.$item['id']).'" class="button">Edit</a><a href="'.admin_url( 'admin.php?page=wshs_saved&action=delete&id='.$item['id']).'" class="button">Delete</a>';
            endif;
        default:
            return $item[$column_name];
        }
    }

    public function no_items() {
        _e( "You don't have any saved shortcode." );
    }
}