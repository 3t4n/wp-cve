<?php

namespace platy\etsy;

class OptionsService extends DataService {
    private static  $instance = null;
    private $user_key;
    private $cache;
    private function __construct($user_key) {
        parent::__construct(\Platy_Syncer_Etsy::OPTIONS_TABLE_NAME);
        $this->user_key = $user_key;
        $this->cache = [];
    }

    public static function get_instance($user_key) {
        if(OptionsService::$instance == null) {
            OptionsService::$instance = new OptionsService($user_key);
        }
        return OptionsService::$instance;
    }
    
    public function save_option($opt_name, $opt_value, $user_id = -1, $group = null){
        global $wpdb;

        $opt_value = serialize($opt_value);
        $user_key = $this->user_key;

        $row = [
            $user_key => $user_id,
            'option_name' => $opt_name,
            'option_value' => $opt_value,
            "group" => $group
        ];
        $opions_tbl = $this->tbl_name;
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}$opions_tbl
            WHERE $user_key=$user_id AND option_name='$opt_name'");
        if(count($results)==0) {
            $wpdb->insert($wpdb->prefix . $this->tbl_name,$row);
        }else{
            $wpdb->update($wpdb->prefix . $this->tbl_name,$row, [
                $user_key => $user_id, 
                "option_name" => $opt_name
                ]
            );
        }
        $this->cache[$user_id] = null;
        do_action("platy_etsy_opt_${opt_name}_save", $opt_value, $user_id);
    }

    public function save_option_group($options, $group, $user_id = -1) {
        foreach($options as $key => $value){
            $this->save_option($key, $value, $user_id, $group);
        }
        do_action("platy_etsy_opt_group_${group}_save", $options, $user_id);
        $this->cache[$user_id] = null;
    }

    public function delete_option($opt_name, $user_id = -1){
        global $wpdb;
        $opions_tbl = $this->tbl_name;
        $user_key = $this->user_key;

        $wpdb->delete($wpdb->prefix . $opions_tbl, [
            $user_key => $user_id,
            'option_name' => $opt_name
        ]);
        $this->cache[$user_id] = null;
    }

    public function get_options_grouped($user_id)
    {
        $results = $this->get_raw_options($user_id);
        $group_options = [];
        $user_key = $this->user_key;

        foreach ($results as $opt) {
            if (!empty($opt['group'])) {
                $group_option = [
                    'id' => $opt['group'],
                    'value' => [],
                    $user_key => $opt[$user_key]
                ];
                $group_options[$opt['group']] = $group_option;
            }
        }

        foreach ($results as $opt) {
            $option = [
                'id' => $opt['option_name'],
                'value' => \unserialize($opt['option_value']),
                $user_key => $opt[$user_key]
            ];
            if (!empty($opt['group'])) {
                $group_options[$opt['group']]['value'][$opt['option_name']] = $option['value'];
                $group_options[$opt['group']]['group'] = $opt['group'];
            } else {
                $group_options[$option['id']] = $option;
            }
        }

        $ret = DataService::with_id_keys(array_values($group_options));

        return $ret;
    }

    private function get_raw_options($user_id){
        global $wpdb;
        
        if(isset($this->cache[$user_id])) {
            return $this->cache[$user_id];
        }
        
        $user_key = $this->user_key;

        $options_tbl = $this->tbl_name;
        $full_table_name = "{$wpdb->prefix}$options_tbl";
        if($wpdb->get_var( "show tables like '$full_table_name'" ) != $full_table_name){
            return [];
        }
        $results = $wpdb->get_results( "SELECT * FROM $full_table_name WHERE ($user_key=-1 OR $user_key=$user_id)", ARRAY_A);
        $this->cache[$user_id] = $results;
        return $results;
    }

    public function get_options_as_array($user_id,$option_name = ""){
        $results = $this->get_raw_options($user_id);
        if(!empty($option_name)){
            foreach($results as $opt){
                if($opt['option_name'] == $option_name){
                    return [$opt['option_name'] => unserialize($opt['option_value'])];
                }
            }
            return [];
        }
        $ret = [];
        foreach($results as $opt){
            $ret = array_merge($ret, [$opt['option_name'] => unserialize($opt['option_value'])]);
        }
        return $ret;
    }

    public function get_option($option_name, $def, $user_id){
        $option = $this->get_options_as_array($user_id, $option_name);
        return isset($option[$option_name]) ? $option[$option_name] : $def;
    }

}