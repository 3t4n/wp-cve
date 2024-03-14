<?php

/**
 * Basic Class
 *
 * The basic class of the Denra Plugins Framework
 *
 * @author     Denra.com aka SoftShop Ltd <support@denra.com>
 * @copyright  2019 Denra.com aka SoftShop Ltd
 * @license    GPLv2 or later
 * @version    1.0
 * @link       https://www.denra.com/
 */

namespace Denra\Plugins;

class Basic {
    
    public $id; // lowercase id with hyphens e.g. my-plugin-id
    public $id_u; // lowercase id with underscores e.g. my_plugin_id
    public $data; // init data needed for object to work correctly
    
    public function __construct($id, $data = []) {
        
        $id || die('<p>Plugin id needed for '.get_class($this).'.</p>');
        
        $this->id = $id;
        $this->id_u = str_replace('-', '_', $this->id);
        $this->data = (is_array($data) && count($data)) ? $data : [];
        
    }
}