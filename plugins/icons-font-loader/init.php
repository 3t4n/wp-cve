<?php
/*
 * Plugin Name: Icons Font Loader
 * Plugin URI:  https://bplugins.com/html5-video-player-pro/
 * Description: Load Various Web Fonts/Icon Font (Flat Icon) To Your WordPress
 * Version:     1.1.5
 * Author:      bPlugins LLC
 * Author URI:  http://bplugins.com
 * License:     GPLv3
 * Text Domain: bifl
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

// include_once 'includes/fields.php';
if(!defined('BIFL_VER')){
    define('BIFL_VER', '1.1.5');
}

if(!class_exists('BIFL_Upload_ZIP_Form')){
class BIFL_Upload_ZIP_Form {
	protected $errors = null;
    protected $folder = '';

	protected $notices = [];

	public function __construct() {
		add_action( 'init', [ $this, 'upload' ] );
        add_action( 'plugins_loaded', [$this, 'bifl_text_domain'] );
        add_action('wp_enqueue_scripts', [$this, 'bifl_enqueue_icons_font']);
        add_action('wp_ajax_bifl_ajax_call', [$this, 'bifl_ajax_call']);
        if(is_admin()){
            add_action( 'admin_init', [$this, 'bif_crate_table'] );
            add_action( 'admin_menu', [$this, 'admin_menu'] );
            add_action('admin_enqueue_scripts', [$this, 'bifl_admin_enqueue_icons_font']);
            $this->errors = new WP_Error();
        }

	}

    
    function bifl_text_domain() {
        load_plugin_textdomain( 'bifl', false, 'icons-font-loader/languages/' );
    }


    public function bifl_ajax_call(){
        $nonce = $_POST['nonce'] ?? '';
        if(!wp_verify_nonce( $nonce, 'wp-rest' )){
            echo wp_json_encode([
                'success' => false,
                'message' => 'Invalid authorization'
            ]);
            die();
        }

        global $wp_filesystem;		
        global $wpdb;
        $table_name = $wpdb->prefix . "iconfonts"; 
        $id = (int) sanitize_text_field( $_POST['id'] );
        $action = sanitize_text_field( $_POST['do'] );
        $msg = [];

        if ( ! function_exists( 'WP_Filesystem' ) ) {
			include_once ABSPATH.'wp-admin/includes/file.php';
		}
    
		WP_Filesystem();

        if($action == 'delete'){
            $folder_path = $wpdb->get_row($wpdb->prepare("SELECT path FROM $table_name WHERE id = %d", $id));
            $delete_folder = $wp_filesystem->delete( $folder_path->path, true );
            $result = $wpdb->delete($table_name, ['id' => $id], ['%d'] );
            if($result){
                $msg['success'] = true;
            }else {
                $msg['success'] = false;
                $msg['message'] = __('Failed to Delete Icons Font', 'bifl');
            }
        }
         

        //if action is enable
        if($action == 'enable'){
            $result = $wpdb->update($table_name, ['status' => 'active'], ['id' => $id], ['%s'] );
            if($result){
                $msg['success'] = true;
                $msg['id'] = $id;
            }else {
                $msg['success'] = false;
                $msg['message'] = __('Failed to Update Status', 'bifl');
            }
        }
        

        //if action is disbale
        if($action == 'disable'){
            $result = $wpdb->update($table_name, ['status' => 'inactive'], ['id' => $id], ['%s'] );
            if($result){
                $msg['success'] = true;
            }else {
                $msg['success'] = false;
                $msg['message'] = __('Failed to Update Status', 'bifl');
            }
        }
        

        echo wp_json_encode($msg);
    
        die();
    }

    //enqueue admin script and style
    public function bifl_admin_enqueue_icons_font($screen){
        if($screen == 'tools_page_icons-font-loader'){
            wp_enqueue_script('bifl-script', plugin_dir_url(__FILE__).'/assets/js/script.js', BIFL_VER, array('jquery'), true);
            wp_enqueue_style('bifl-style', plugin_dir_url(__FILE__).'/assets/css/style.css', BIFL_VER);
            wp_localize_script( 'bifl-script', 'bifl', ['ajax_url' => admin_url( 'admin-ajax.php'), 'nonce' => wp_create_nonce('wp-rest')] );
        }
        
    }

    //enqueue icons font in frontend
    public function bifl_enqueue_icons_font(){
        global $wpdb;
        $table_name = $wpdb->prefix . "iconfonts"; 
        $results = $wpdb->get_results( "SELECT * FROM $table_name");

        foreach($results as $result){
            if($result->status == 'active'){
                wp_enqueue_style($result->name, $result->iconFont, BIFL_VER );
            }
        }
    }

    //create custom table for icons-font
	public function bif_crate_table(){
		global $wpdb;
		$table_name = $wpdb->prefix . "iconfonts"; 
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		status text NOT NULL,
        name text NOT NULL,
		uploaded datetime DEFAULT CURRENT_TIMESTAMP() NOT NULL,
		iconFont text NOT NULL,
		preview text DEFAULT '' NOT NULL,
        path text DEFAULT '' NOT NULL,
		PRIMARY KEY  (id)
		) $charset_collate;";
        

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

    //create upload form to upload icons-font
    public function form(){
		ob_start();
		if ( $this->notices ) {
			?>
			<ul>
				<?php
				foreach( $this->notices as $notice ) {
					?>
					<li><?php echo esc_html($notice); ?></li>
					<?php
				}
				?>
			</ul>
			<?php
		}

		if ( $this->errors->get_error_messages() ) {
			?>
			<ul>
				<?php
				foreach( $this->errors->get_error_messages() as $message ) {
					?>
					<li><?php echo esc_html($message); ?></li>
					<?php
				}
				?>
			</ul>
			<?php
		}
		?>
		<form method="POST" action="" class="bifl_form" enctype="multipart/form-data">
        <?php
			wp_nonce_field( 'zip_upload_nonce', 'zip_upload_nonce' );
			?>
            <p><?php esc_html_e('Select A Zip File', 'bifl'); ?></p>
			<div class="inner_form">
                <input type="file" accept="application/zip" name="file" />
			    <button class="submit button" name="upload_file" type="submit"><?php esc_html_e('Upload', 'bifl') ?></button>
            </div>
		</form>
		<?php
		echo ob_get_clean();
	}

    public function upload() {
		if ( ! isset( $_POST['zip_upload_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['zip_upload_nonce'], 'zip_upload_nonce' ) ) {
			return;
		}

        $posted_data =  [];
		$file_data   = [];
        if(isset($_POST) && is_array($_POST)){
            foreach($_POST as $key => $value){
                $posted_data[$key] = sanitize_text_field($value); 
            }
        }

        if(isset($_FILES['file']) && is_array($_FILES['file'])){
            foreach($_FILES['file'] as $key => $value){
                $file_data['file'][$key] = sanitize_text_field($value); 
            }
        }
		
		$data        = array_merge( $posted_data, $file_data );
        $newData = [];

        foreach($data as $key1 => $value1){
             if(is_array($value1)){
                foreach($value1 as $key2 => $value2){
                    if(!is_array($value2)){
                        $newData[$key1][$key2] = sanitize_text_field( $value2);
                    }
                }
            }else {
                $newData[$key1] = sanitize_text_field( $value1);
            }
        }

		include_once 'includes/class-zip-uploader.php';
		$uploader = new BIFL_ZIP_Uploader( 'icons-font' );
		$result = $uploader->upload($newData );

		if ( is_wp_error( $result ) ) {
			$this->errors->add( $result->get_error_code(), $result->get_error_message() );
		}

		// $this->notices[] = 'Uploaded! Path: ' . $result;
	}

    //add submenu under tools menu
    function admin_menu() {
        add_submenu_page('tools.php', __('Icons Font Loader', 'bifl'),__('Icons Font Loader', 'bifl') , 'manage_options', 'icons-font-loader', array($this, 'plugin_page') );
    }

    //design options page
    function plugin_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . "iconfonts"; 
        $results = $wpdb->get_results( "SELECT * FROM $table_name");
        ?>
        <div class="wrap bifl_wrapper">
            <div class="bifl__form_table">
                <?php $this->form(); ?>

            <div class="bifl_table">
            <table id="customers">
                <tr>
                    <th><?php _e("Status", "bifl"); ?></th>
                    <th><?php _e("Uploaded", "bifl"); ?></th>
                    <th><?php _e("Example", "bifl"); ?></th>
                    <th><?php _e("Enable/Disable", "bifl"); ?></th>
                    <th><?php _e("Delete", "bifl") ?> </th>
                </tr>
                
                <?php 
                foreach($results as $result){ ?>
                    <tr>
                        <td><?php echo $result->status == 'active' ? esc_html__('Enabled', 'bifl') : esc_html__('Disabled', 'bifl'); ?></td>
                        <td><?php echo esc_html(date_format(date_create($result->uploaded), get_option('date_format'))); ?></td>
                        <td>
                            <?php if($result->preview !== ''): ?>
                                <a target="_blank" href="<?php echo esc_html($result->preview) ?>" class="bifl_button"><?php esc_html_e("Preview", 'bifl'); ?> </a>
                            <?php else: ?>
                                <span style="display: flex;justify-content:center;"><?php esc_html_e("Unavailable", "bfil"); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($result->status == 'active'): ?>
                                <a href="#" class="bifl_button bifl_dynamic" data-id="<?php echo esc_attr($result->id) ?>" action="disable"><?php _e("Disable", "bifl"); ?></a>
                            <?php else :  ?>
                                <a href="#" class="bifl_button bifl_dynamic" data-id="<?php echo esc_attr($result->id) ?>" action="enable"><?php esc_html_e("Enable", "bifl"); ?></a>
                            <?php endif; ?>
                        </td>
                        <td><a href="" class="bifl_button danger bifl_dynamic" data-id="<?php echo esc_attr($result->id) ?>" action="delete"><?php esc_html_e("Delete", "bifl"); ?></a></td>
                    </tr>
        <?php } ?>
                </table>
            </div>

        </div>
       </div>
       <?php
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }
}

new BIFL_Upload_ZIP_Form();

}