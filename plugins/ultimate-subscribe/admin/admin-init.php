<?php 
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__).'inc/cpt-forms.php';
require_once plugin_dir_path(__FILE__).'inc/meta-box.php';
require_once plugin_dir_path(__FILE__).'inc/meta-box-forms-details.php';
require_once plugin_dir_path(__FILE__).'inc/meta-box-forms-settings.php';
require_once plugin_dir_path(__FILE__).'inc/meta-box-popup-settings.php';
require_once plugin_dir_path(__FILE__).'inc/meta-box-forms-info.php';
require_once plugin_dir_path(__FILE__).'inc/default-data.php';
require_once plugin_dir_path(__FILE__).'inc/subscribe-form-widget.php';
require_once plugin_dir_path(__FILE__).'inc/class-us-list-table.php';
require_once plugin_dir_path(__FILE__).'inc/class-list-subscribers.php';


function ultimate_subscribe_admin_scripts() {
    wp_enqueue_style('ultimate-subscribe-admin-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
    wp_enqueue_style('font-awesome', ULTIMATE_SUBSCRIBE_URI . "/css/font-awesome.min.css");

    wp_enqueue_script('jquery-ui');
    wp_enqueue_script('ultimate-subscribe-jQueryColorPicker', plugin_dir_url(__FILE__) . 'assets/js/jQueryColorPicker.min.js', array('jquery'));
    wp_enqueue_script( 'jquery-cookie',  plugin_dir_url( __FILE__ ) . 'assets/js/jquery.cookie.js', array('jquery'), null, true);
    wp_enqueue_script('ultimate-subscribe-admin-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', array('jquery', 'jquery-cookie'));
}
add_action('admin_enqueue_scripts', 'ultimate_subscribe_admin_scripts');


function ultimate_subscribe_options_menu(){
    add_menu_page(
        __('Ultimate Subscribe','ultimate-subscribe'), 
        __('Ultimate Subscribe','ultimate-subscribe'), 
        'manage_options', 
        'ultimate-subscribe', 
        'ultimate_subscribe_dashboard_page', 
        'dashicons-email', 
        26
    );
    add_submenu_page( 'ultimate-subscribe', 'Dashboard', 'Dashboard', 'manage_options', 'ultimate-subscribe', 'ultimate_subscribe_dashboard_page' );
    add_submenu_page( 'ultimate-subscribe', 'Subscribers', 'Subscribers', 'manage_options', 'ultimate-subscribe-users', 'ultimate_subscribe_subscriber_page' );
    add_submenu_page( 'ultimate-subscribe', 'Forms', 'Forms', 'manage_options', 'edit.php?post_type=u_subscribe_forms'); 
    add_submenu_page( 'ultimate-subscribe', 'Ultimate Subscribe Settings', 'Settings', 'manage_options', 'ultimate-subscribe-settings', 'ultimate_subscribe_settings');    
}

function ultimate_subscribe_subscriber_page(){
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
    require_once plugin_dir_path(__FILE__) . 'manu-pages/subscribers.php';
}

function ultimate_subscribe_dashboard_page(){
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
    require_once plugin_dir_path(__FILE__) . 'manu-pages/dashboard.php';
}


function ultimate_subscribe_settings(){
    if (!current_user_can('manage_options')) {
        return;
    }
    require_once plugin_dir_path(__FILE__) . 'settings/settings-init.php';
}


if ( is_admin() ){ // admin actions
 
    add_action('admin_menu', 'ultimate_subscribe_options_menu');
    add_action( 'admin_init', 'ultimate_subscribe_register_settings' );
}

function ultimate_subscribe_register_settings(){
    register_setting( 'ultimate-subscribe-options', 'ultimate_subscribe_options' ); 
}

function ultimate_subscribe_forms_cpt_columns($columns) {
    $new_columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => __( 'Title' ),
        'shortcode' => __('Shortcode'),
        'date' => __( 'Date' )
    );
    return $new_columns;
}
add_filter('manage_u_subscribe_forms_posts_columns' , 'ultimate_subscribe_forms_cpt_columns');

function ultimate_subscribe_manage_forms_cpt_columns( $column, $post_id ) {
    global $post;
    if($column == 'shortcode'){
        echo '<div class="u-shortcoad-col"> [ultimate_subscribe_from id="'.$post_id.'"] </div>';
    }
}
add_action('manage_u_subscribe_forms_posts_custom_column', 'ultimate_subscribe_manage_forms_cpt_columns', 10, 2);


function array2csv(array &$array){
   if (count($array) == 0) {
     return null;
   }
   
   ob_start();
   $df = fopen("php://output", 'w');
   fputcsv($df, array_keys(reset($array)));
   foreach ($array as $row) {
      fputcsv($df, $row);
   }
   fclose($df);
   return ob_get_clean();
}

function download_send_headers($filename) {
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download  
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}

function ultimate_subscribe_export_subs(){

    if(isset($_GET['page']) && $_GET['page'] == 'ultimate-subscribe-users'){
        if( isset($_GET['action']) && $_GET['action'] == 'export_us_subscriber'){
            global $wpdb; //This is used only if making any database queries
            $table_name = $wpdb->prefix . 'ultimate_subscribe';
            $data = $wpdb->get_results("SELECT `first_name`, `last_name`, `email`, `active` as `status`, `created` FROM $table_name", ARRAY_A);

            download_send_headers("data_export_" . date("Y-m-d-h-i-s") . ".csv");
            echo array2csv($data);
            die();
        }

        /*if(isset($_POST['import']) && isset( $_POST['_wpnonce'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'ultimate_subscribe_import' )){

        }*/    
    }
}

function ultimate_subscribe_import_subs(){
    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
    }

    $fdata = array();
    $usFields;
    $email_key = 'email';
    $fname_key = 'first_name';
    $lname_key = 'last_name';

    $email_i;
    $fname_i;
    $lname_i;
    global $wpdb;
    $table_name = $wpdb->prefix . 'ultimate_subscribe';
    $filename = $_FILES['us_imcsv_file']['tmp_name'];
    $status = isset($_POST['us_imcsv_status'])?intval($_POST['us_imcsv_status']):0;
    $row = 1;
    echo 20;
    if (($handle = fopen($filename, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            $fdata[] = $data;
            
            if($row > 1){
                $email = $data[$email_i];
                $email_c = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE email = '$email'");
                if($email_c == 0){
                    $wpdb->insert( 
                        $table_name, 
                        array( 
                            'email' => $data[$email_i],
                            'first_name' => $data[$fname_i],
                            'last_name' => $data[$lname_i],
                            'active'   => $status
                        ), 
                        array( 
                            '%s', 
                            '%s',
                            '%s',
                            '%d'
                        )
                    );    
                }
            }else{

                $email_i = array_search($email_key, $data);
                $fname_i = array_search($fname_key, $data);
                $lname_i = array_search($lname_key, $data);

            }

            // echo "<p> $num fields in line $row: <br /></p>\n";
            /*for ($c=0; $c < $num; $c++) {
                echo $data[$c] . "<br />\n";
            }*/
            $row++;
        }
        fclose($handle);
    }

    /*
    $upload_overrides = array( 'test_form' => false );
    $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

    if ( $movefile && ! isset( $movefile['error'] ) ) {
        echo "File is valid, and was successfully uploaded.\n";
        var_dump( $movefile );
    }
    */
    echo 100;
    //print_r($fdata);
    wp_die();
}

add_action('init', 'ultimate_subscribe_export_subs');
// add_action('init', 'ultimate_subscribe_import_subs');
add_action( 'wp_ajax_us_import_action23', 'ultimate_subscribe_import_subs' );

?>