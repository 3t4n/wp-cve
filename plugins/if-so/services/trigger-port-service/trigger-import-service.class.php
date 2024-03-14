<?php
/**
 *  Service for importing a trigger from a JSON file
 *
 * @author Nick Martianov
 *
 **/

namespace IfSo\Services\TriggerPortService;

use IfSo\PublicFace\Services\AnalyticsService\AnalyticsService;

require_once(IFSO_PLUGIN_BASE_DIR . 'public/services/analytics-service/analytics-service.class.php');

class TriggerImportService{

    private static $instance;
    protected $analytics_service;

    private function __construct(){
        $this->analytics_service=AnalyticsService::get_instance();
    }

    public static function get_instance(){
        if (NULL == self::$instance)
            self::$instance = new TriggerImportService();

        return self::$instance;
    }

    public function handle($duplicate=false,$dupData=false){
        if($duplicate&&$dupData) $data = json_decode($dupData,true);
        else $data = $this->get_data_from_uploaded_file();
        $postarr = $this->make_trigger_postarr($data);
        if($data && $postarr){
            $ins = wp_insert_post($postarr['postarr']);
            if($ins){
                $this->add_missing_metas($ins,$postarr['missing']);
                $this->analytics_service->reset_analytics_fields($ins); //Reset the imported trigger analytics
                $this->create_js_post_rdr('success');
                return true;
            }
        }
        $this->create_js_post_rdr('fail');

    }

    private function get_data_from_uploaded_file(){
        if ($_FILES['triggerToImport']['error'] == UPLOAD_ERR_OK               //checks for errors
            && is_uploaded_file($_FILES['triggerToImport']['tmp_name'])) {
            $file = file_get_contents($_FILES['triggerToImport']['tmp_name']);
            //$file = str_replace(array("\\n", "\\r"), array("\\\\n","\\\\r"), $file);  //Strip away all the newlines to avoid them turning to 'rn' during parsing
            return json_decode($file,true);
        }
        return false;
    }

    private function make_trigger_postarr($data){
        $ret =[];
        $missing=[];
        if(isset($data['title']) && isset($data['meta']) && is_array($data['meta'])){
            $ret['post_title'] = $data['title'];
            $ret['post_content'] = '';
            $ret['post_type'] = 'ifso_triggers';
            foreach($data['meta'] as $fieldname => $field){
                if(is_string($field) && $field!='ifso_trigger_version'){
                    $ret['meta_input'][$fieldname] = trim($field,'\"');
                }
                else{
                    $missing[$fieldname] = $field;
                }

            }
            $return = ['postarr'=>$ret,'missing'=>$missing];
            return $return;
        }
        return false;
    }

    private function add_missing_metas($postid,$metas){
        if(isset($postid) && isset($metas) && is_array($metas)){
            foreach($metas as $metakey=>$metavalues){
                foreach($metavalues as $val)
                    add_post_meta($postid,$metakey,$val);
            }
        }
    }

    private function create_js_post_rdr($isSucessfull){
        ?>
            <form id="rdrform" action="<?php echo $_SERVER['HTTP_REFERER']; ?>" method="POST">
                <input type="hidden" name="ifsoTriggerImported" value="<?php echo $isSucessfull; ?>">
            </form>
            <script>
                document.getElementById('rdrform').submit();
            </script>
        <?php
    }



}