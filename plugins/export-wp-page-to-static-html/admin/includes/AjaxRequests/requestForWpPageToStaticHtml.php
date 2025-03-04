<?php


namespace ExportHtmlAdmin\EWPPTH_AjaxRequests\requestForWpPageToStaticHtml;

class initAjax extends \ExportHtmlAdmin\Export_Wp_Page_To_Static_Html_Admin
{
    private $ajax;
    public function __construct($ajax)
    {
        /*Initialize Ajax rc_export_wp_page_to_static_html*/
        add_action('wp_ajax_rc_export_wp_page_to_static_html', array( $this, 'rc_export_wp_page_to_static_html' ));
        $this->ajax = $ajax;
    }


    /**
     * Ajax action name: rc_export_wp_page_to_static_html
     * @since    1.0.0
     * @access   public
     * @return json
     */

    public function rc_export_wp_page_to_static_html(){
        //$post = $_POST['post'];
        $pages = isset($_POST['pages']) ? (array) $_POST['pages'] : "";
        $pages = array_map( 'esc_attr', $pages );

        $replace_urls = isset($_POST['replace_urls']) && sanitize_key($_POST['replace_urls']) == "true" ? true : false;

        $skip_assets_data = isset($_POST['skip_assets']) ? (array) $_POST['skip_assets'] : array();
        $skip_assets_data = array_map( 'esc_attr', $skip_assets_data );

        $receive_email = isset($_POST['receive_email']) && sanitize_key($_POST['receive_email']) == "true" ? true : false;
        $email_lists = isset($_POST['email_lists'] ) ? sanitize_text_field($_POST['email_lists']) : "";
        $ftp = isset($_POST['ftp']) ? sanitize_key($_POST['ftp']) : 'no';
        $ftpPath = isset($_POST['path']) ?  sanitize_text_field($_POST['path']) : '';
        $nonce = isset($_POST['rc_nonce']) ? sanitize_key($_POST['rc_nonce']) : "";

        if(!$this->ajax->nonceCheck()){
            echo json_encode(array('success' => 'false', 'status' => 'nonce_verify_error', 'response' => ''));

            die();
        }




        $settings = array(
            'skipAssetsFiles' => (array) $skip_assets_data,
            'replaceUrlsToHash' => $replace_urls,
            'full_site' => false,
            'receive_email' => $receive_email,
            'email_lists' => $email_lists,
            'ftp_upload_enabled' => $ftp,
            'ftp_path' => $ftpPath,
            'singlePage' => true,
        );
        $this->removeAllSettings();
        $this->setSettings('cancel_command', 0);
        $this->setSettings('creating_html_process', 'running');
        $this->setSettings('creating_zip_process', 'running');
        $this->setSettings('total_zip_files', 0);
        $this->setSettings('zipDownloadLink', 'no');
        $this->setSettings('lastLogsTime', '');
        $this->setSettings('task', 'running');

        $pages = array_slice($pages, 0, 3);
        //$t = $this->removeAllSettings();
        $this->clear_tables_and_files();

        $s=0;
        while (true) {
            $s++;
            $taskStatus = $this->getSettings('task', '');

            if ($taskStatus == "" || $taskStatus == "completed" || $taskStatus == "failed" || $s>5) {

                //$this->create_required_directories();
                //$this->setDefaultSettings();
                wp_schedule_single_event( time() , 'start_export_internal_wp_page_to_html_event', array( $pages, $settings ) );
                echo json_encode(array('success' => 'true', 'status' => 'success', 'response' => $pages));
                break; // Exit the loop once the condition is met
            }

            sleep(1);
        }

        wp_die();

    }

    private function setDefaultSettings()
    {

        $this->setSettings('logs_in_details', 0);
        $this->setSettings('task', 'running');
        $this->setSettings('ftp_upload_enabled', '');
        $this->setSettings('ftp_status', '');
        $this->setSettings('lastLogs', '');
        $this->setSettings('lastLogsTime', '');
        $this->setSettings('timestampError', true);
        $this->setSettings('lastLogs', 0);
        $this->setSettings('lastLogsTime', time());
    }



}