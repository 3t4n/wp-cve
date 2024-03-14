<?php
namespace YTP\Model;

class Presets {

    protected $table_name = 'yt_player_presets';

    public function createOrUpdate($args ){
        global $wpdb;
        $table_name = $wpdb->prefix.$this->table_name;

        $args['preset'] = maybe_serialize( $args['preset']);

        if(!isset($args['id'])){
            $wpdb->insert($table_name, $args);
            return $wpdb->insert_id;
        }else {
            return $wpdb->update($table_name, $args, ['id' => $args['id']]);
        }
        return false;
    }

    public function get($id){
        global $wpdb;
        $table_name = $wpdb->prefix.$this->table_name;
        $result = $wpdb->get_row($wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $id));

        if(!$result){
            return [];
        }
        
        $tempItem = [];
       foreach($result as $key => $value){
        $tempItem[$key] = maybe_unserialize( $value );
       }
       return $tempItem;
    }

    public function getPreset($id){
        $preset =  $this->get($id);
        return $preset['preset'] ?? [];
    }

    function deletePreset($args){
        global $wpdb;
        $table_name = $wpdb->prefix.$this->table_name;
        return $wpdb->delete($table_name, ['id' => $args['id']]);
    }

    function fetchPresets(){
        global $wpdb;
        $table_name =  $wpdb->prefix.$this->table_name;
        $data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name"), 'ARRAY_A');
        foreach($data as $index => $item){
            foreach($item as $key => $value ){
                $data[$index][$key] = maybe_unserialize( $data[$index][$key] );
            }
        }
        return $data;
    }

    function get_client_ip() {
        $ipaddress = '';
        if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else if(getenv('SERVER_ADDR'))
            $ipaddress = getenv('SERVER_ADDR');
        else if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}