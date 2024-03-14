<?php

class EOD_FD
{
    private $type = '';
    public $fd_hierarchy;
    public $id = false;
    public $group = '';
    public $list = [];
    public $errors = [];

    public function init( $type, $preset_id ){
        if(!in_array($type, ['financial','fundamental'])){
            $this->errors[] = 'Undefined fundamental data type.';
            return;
        }
        $this->type = $type;

        // Check ID
        if($preset_id && is_numeric($preset_id)){
            $this->id = (int)$preset_id;
        }else{
            $this->errors[] = 'Wrong preset id.';
        }

        // Check preset status
        if(get_post_status($preset_id) !== 'publish'){
            $this->errors[] = 'Preset is not published.';
        }

        if(!$this->has_errors()) {
            global $eod_api;
            switch ($this->type){
                case 'financial':
                    $this->group = get_post_meta($this->id, '_financial_group', true) ?: 'Financials->Balance_Sheet';
                    $this->list = get_post_meta($this->id, '_financials_list', true);
                    $this->fd_hierarchy = $eod_api->get_financial_hierarchy();
                    break;
                case 'fundamental':
                    $this->group = get_post_meta($this->id, '_fd_type', true) ?: 'common_stock';
                    $this->list = get_post_meta($this->id, '_fd_list', true);
                    $this->fd_hierarchy = $eod_api->get_fd_hierarchy();
                    break;
            }

            if($this->list) {
                $this->list = json_decode($this->list);
            } else {
                $this->errors[] = 'Empty preset.';
            }

            // Define FD lib area
            if( isset($this->fd_hierarchy[ $this->group ]) ){
                $this->fd_hierarchy = $this->fd_hierarchy[ $this->group ];
            } else {
                $this->errors[] = "Undefined {$this->type} data type.";
            }
        }
    }

    /**
     * @return bool
     */
    public function has_errors(){
        return !empty($this->errors);
    }

    /**
     * @return string
     */
    public function get_errors(){
        return implode(' ', $this->errors);
    }

    /**
     * @param string $slug - full path to an item imploded with '->'.
     * @return array
     */
    public function get_item( $slug ){
        $path = explode('->', $slug);
        $item = $this->fd_hierarchy;
        while( !empty($path) && $item ){
            $key = array_shift($path);
            $list = isset($item['list']) ? $item['list'] : $item;
            if(isset($list[$key])) {
                $item = $list[$key];
            } else {
                return [];
            }
        }
        return $item;
    }

    /**
     * @param string $slug
     * @return string
     */
    public function get_item_title( $slug ){
        $item = $this->get_item( $slug );
        if( isset($item['title']) ) {
            return $item['title'];
        } else {
            $path = explode('->', $slug);
            return end($path);
        }
    }
}