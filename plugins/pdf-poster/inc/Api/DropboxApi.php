<?php
namespace PDFPro\Api;

class DropboxApi{

    private $appKey = null;
    private $container = 'picker_container';
    private $fieldId = 'picker_field';

    public function __construct($appKey = ''){
        if(!$appKey){
            return false;
        }
        $this->appKey = $appKey;

        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
        add_filter('script_loader_tag', [$this, 'modifyDropboxScript'] , 10, 3);
        add_action('admin_footer', [$this, 'initializePicker']);
    }

    public function setContainerId($containerId){
        $this->container = $containerId;
    }

    public function setFieldId($fieldId){
        $this->fieldId = $fieldId;
    }

    public function enqueueScripts(){
        wp_enqueue_script('dropbox-picker', 'https://www.dropbox.com/static/api/2/dropins.js');
    }

    public function modifyDropboxScript($tag, $handle, $src){
        if ( 'dropbox-picker' !== $handle ) {
			return $tag;
		}
		$tag = '<script type="text/javascript" id="dropboxjs" data-app-key="'.$this->appKey.'" src="' . esc_url( $src ) . '"></script>';
		return $tag;
    }

    public function initializePicker(){
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function(){
                if(Dropbox){
                    options = {
                    success: function (file) {
                            const field = document.getElementById('<?php echo esc_html($this->fieldId) ?>');
                            if(field) field.value = file?.[0]?.link
                    },
                    cancel: function () {},
                    linkType: "preview", // or "direct", "preview"
                    multiselect: false, // or true
                    folderselect: false, // or true
                    };
                
                    var button = Dropbox.createChooseButton(options);
                
                    const chooserContainer = document.getElementById('<?php echo esc_html($this->container) ?>');
                    chooserContainer && chooserContainer.appendChild(button);
                }
            });
            
            
        </script>
        <?php
    }

}