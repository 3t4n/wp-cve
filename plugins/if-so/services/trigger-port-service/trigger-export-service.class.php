<?php
/**
 *  Service for exporting an if-so trigger to a JSON file
 *
 * @author Nick Martianov
 *
 **/

namespace IfSo\Services\TriggerPortService;

class TriggerExportService{

    private static $instance;

    private function __construct(){

    }

    public static function get_instance(){
        if (NULL == self::$instance)
            self::$instance = new TriggerExportService();

        return self::$instance;
    }

    public function export_trigger($postid){
        $data = $this->gather_data($postid);
        $this->echo_file($data['dataStr'],$data['name']);
    }

    public function gather_data($postid){
        $ret = [];
        $name = [];
        if(isset($postid)){
            $post = get_post($postid);
            $post_meta = get_post_meta($postid);
            $ret['title'] = (isset($post->post_title)) ? $post->post_title : 'unnamed_trigger';
            $name = $ret['title'];
            foreach($post_meta as $fieldname => $field){
                if(is_array($field)&& count($field)>1) $ret['meta'][$fieldname] = $field;
                else{
                    $fieldStuff =preg_replace("!\r?\n!", "", $field[0]);
                    $ret['meta'][$fieldname] = json_encode(trim($fieldStuff,'\"'));
                    //$ret['meta'][$fieldname] = json_encode(trim($field[0],'\"'));
                }
            }
        }
        $ret = ['dataStr' => json_encode($ret),
                'name'=>$name];
        return $ret;
    }

    private function echo_file($dataStr,$name){
        $fname = $name . '.json';
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . $fname . '"');
        echo $dataStr;
    }

}