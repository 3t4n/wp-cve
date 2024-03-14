<?php
namespace PDFPro\Api;

class GoogleDriveApi{

    private $clientId = null;
    private $developerKey = null;
    private $appId = null;
    private $container = 'picker_container';
    private $fieldId = 'picker_field';
    private $buttonId = 'drive_button';

    public function __construct($developerKey='', $clientId = '', $appId=''){
        $this->clientId = $clientId;
        $this->developerKey = $developerKey;
        $this->appId = $appId;
        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
        add_action('admin_footer', [$this, 'initializePicker'], 10000, 2);
    }

    public function setContainerId($containerId){
        $this->container = $containerId;
    }

    public function setFieldId($fieldId){
        $this->fieldId = $fieldId;
    }

    public function enqueueScripts(){
        wp_enqueue_script('google-drive-api', 'https://apis.google.com/js/api.js?onload=onApiLoad');
    }

    public function initializePicker(){
        ?>
        <script>          
            const googlePickerBtn = document.getElementById('<?php echo esc_html($this->container); ?>');
            const button_field = document.getElementById('<?php echo esc_html($this->fieldId) ?>');
            if(typeof googleDrivePicker !== 'undefined'){
                googleDrivePicker({
                    appId: "<?php echo esc_html($this->appId) ?>",
                    clientId: "<?php echo esc_html($this->clientId)  ?>",
                    developerKey: "<?php echo esc_html($this->developerKey) ?>",
                    container: googlePickerBtn,
                    field: button_field
                });
            }            
        </script>
        <?php
    }

}