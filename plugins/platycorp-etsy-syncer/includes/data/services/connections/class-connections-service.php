<?php

namespace platy\etsy;

class ConnectionsService extends DataService {
    private static  $instance = null;
    private $user_key;
    private function __construct($user_key) {
        parent::__construct(\Platy_Syncer_Etsy::CONNECTIONS_TABLE_NAME);
        $this->user_key = $user_key;
    }

    public static function get_instance($user_key) {
        if(ConnectionsService::$instance == null) {
            ConnectionsService::$instance = new ConnectionsService($user_key);
        }
        return ConnectionsService::$instance;
    }

    public function get_existing_connections($target_type, $user_id){
        global $wpdb;
        $connections_tbl = $this->tbl_name;
        $user_key = $this->user_key;
        $connections = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}$connections_tbl
             WHERE target_type='$target_type' AND $user_key=$user_id", ARRAY_A);
        $ret = [];
        foreach($connections as $connection){
            $ret[$connection['source_id']] = [
                'id' => $connection['target_id'],  'name' => $connection['target_name']
            ];
        }
        return $ret;
    }

    public function get_connectable_data_entities($type,$user_id){
        global $wpdb;

        $terms = get_terms(['taxonomy' => $type, "hide_empty" => false]);
        $ret = [];
        foreach($terms as $term){
            $ret[$term->term_id] = ['id' => $term->term_id, "name" => $term->name];
        }
        $connections_tbl = $this->tbl_name;
        $user_key = $this->user_key;
        $connections = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}$connections_tbl WHERE source_type = '$type' AND $user_key=$user_id", ARRAY_A);
        foreach($connections as $connection){

            $ret[$connection['source_id']][$connection['target_type']] = [
                'id' => $connection['target_id'],  'name' => $connection['target_name']
            ];
            
        }
        return $ret;
    }

    public function update_connection($connection, $type, $user_id){
        global $wpdb;

        if($connection[$connection['type']]==null){
            $this->delete_connection($connection, $type, $user_id);
            return;
        }
        $connections_tbl = $this->tbl_name;
        $user_key = $this->user_key;
        $row = [
            'source_type' => $type, 
            $user_key => $user_id,
            'target_type' => $connection['type'],
            'source_id' => $connection['id'],
            'target_id' => $connection[$connection['type']]['id'],
            'target_name' => $connection[$connection['type']]['name']
        ];

        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}$connections_tbl
            WHERE source_type='$type' AND target_type='{$connection['type']}' AND source_id={$connection['id']} AND $user_key=$user_id");

        if(count($results)==0){
            $wpdb->insert($wpdb->prefix . $connections_tbl,$row);
        }else{
            $wpdb->update($wpdb->prefix . $connections_tbl,$row, [
                    'source_type' => $type, 
                    "target_type" => $connection['type'],
                    'source_id' => $connection['id'],
                    $user_key => $user_id
                ]
            );
        }
    }

    public function delete_connection($connection, $type, $user_id){
        global $wpdb;
        $connections_tbl = $this->tbl_name;
        $user_key = $this->user_key;
        $wpdb->delete($wpdb->prefix . $connections_tbl, [
            $user_key => $user_id,
            'source_type' => $type, 
            "target_type" => $connection['type'],
            'source_id' => $connection['id']
        ]);
    }
    
    

}