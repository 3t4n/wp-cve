<?php

namespace platy\etsy;

class TemplatesService extends DataService {
    private static  $instance = null;
    private $user_key;
    private $meta_tbl_name;
    private function __construct($user_key) {
        parent::__construct(\Platy_Syncer_Etsy::TEMPLATES_TABLE_NAME);
        $this->user_key = $user_key;
        $this->meta_tbl_name = \Platy_Syncer_Etsy::TEMPLATES_META_TABLE_NAME;
    }

    public static function get_instance($user_key) {
        if(TemplatesService::$instance == null) {
            TemplatesService::$instance = new TemplatesService($user_key);
        }
        return TemplatesService::$instance;
    }

    public function does_template_exist($tid) {
        global $wpdb;
        
        $tamplate_tbl = $this->tbl_name;
        $user_key = $this->user_key;
        $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}$tamplate_tbl WHERE id=$tid", ARRAY_A);
        
        if(empty($results)) {
            return false;
        }

        return true;
    }

    public function get_template_metas($tid){
        global $wpdb;

        if(!$this->does_template_exist($tid)) {
            throw new NoSuchTemplateException($tid);
        }

        $r = [];
        $template_meta_tbl = $this->meta_tbl_name;
        $metas = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}$template_meta_tbl WHERE template_id={$tid}", ARRAY_A);
        foreach($metas as $m){
            $r[$m['meta_name']] = $m['meta_value'];
        }
        return $r;
    }

    public function get_templates($user_id){
        global $wpdb;
        $tamplate_tbl = $this->tbl_name;
        $user_key = $this->user_key;
        $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}$tamplate_tbl WHERE $user_key=$user_id", ARRAY_A);
        foreach($results as &$r){
            $metas = $this->get_template_metas($r['id']);
            foreach($metas as $m => $v){
                $r[$m] = $v;
            }
            
        }
        return DataService::with_id_keys($results);
    }

    public function add_template($template, $user_id){
        global $wpdb;
        try{
            $this->get_template_by_name($template['name'], $user_id);
            throw new DuplicateTemplateNameException($template['name']);
        }catch(NoSuchTemplateException $e){

        }
        $status = $wpdb->insert($wpdb->prefix . $this->tbl_name,$template);
        if(empty($status)){ // num rows inserted is 0 or status is false
            throw new \Exception($wpdb->last_error);
        }
        return $wpdb->insert_id;
    }

    protected function get_template_by_name($name, $user_id){
        $templates = $this->get_templates($user_id);
        foreach($templates as $template){
            if($template['name'] == $name) return $template;
        }
        throw new NoSuchTemplateException($name);
    }

    public function update_template($tid, $template, $user_id){
        global $wpdb;
        try{
            $t = $this->get_template_by_name($template['name'], $user_id);
            if($t['id'] != $tid){
                throw new DuplicateTemplateNameException($template['name']);
            }
        }catch(NoSuchTemplateException $e){

        }
        $wpdb->update($wpdb->prefix . $this->tbl_name,$template, ['id' => $tid]);

    }

    public function delete_template($tid){
        global $wpdb;
        $wpdb->delete($wpdb->prefix . $this->tbl_name,['id' => $tid]);
        $wpdb->delete($wpdb->prefix . $this->meta_tbl_name,['template_id' => $tid]);
    }

    public function update_template_meta($tid, $meta_name, $meta_value){
        global $wpdb;
        $row = ['template_id' => $tid,'meta_name' => $meta_name, "meta_value" => $meta_value];
        $template_meta_tbl = $this->meta_tbl_name;

        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}$template_meta_tbl WHERE 
                    template_id=$tid AND meta_name='$meta_name'");
        if(count($results) == 0){
            $wpdb->insert($wpdb->prefix . $this->meta_tbl_name, $row);
        }else{
            $wpdb->update($wpdb->prefix . $this->meta_tbl_name, $row, ['template_id' => $tid, 'meta_name' => $meta_name]);
        }

    }

    public function has_templates($user_id){
        $templates = $this->get_templates($user_id);
        return !empty($templates);
    }
}