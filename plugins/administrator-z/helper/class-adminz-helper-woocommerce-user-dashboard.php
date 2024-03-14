<?php 
namespace Adminz\Helper;

/*
    Sau khi add code vào thì nhớ save lại permalink nhé
    Chú ý: Không khai báo 1 obj với action init
*/
class ADMINZ_Helper_Woocommerce_User_DashBoard
{
    public $arr_add = [
        // [
            // 'name'=> 'groupbuying',
            // 'label'=> 'GroupBuyingZ',
            // 'callback'=> 'function_name'
            // 'index' => 10
        // ]
    ];

    public $arr_remove = [
        // 'dashboard',
        // 'orders',
        // 'downloads',
        // 'edit-address',
    ];

    public $arr_change_label = [
        // 'dashboard'=> 'Hồ sơ'
    ];

    function __construct() {
        
    }

    function init(){
        $this->add_nav_item();
        $this->remove_nav_item();
        $this->change_nav_item();
    }

    function add_nav_item(){
        if(empty($this->arr_add) or !is_array($this->arr_add)) return; 

        foreach ($this->arr_add as $key => $item) {

            add_action('init',function() use($item){              
                add_rewrite_endpoint( $item['name'], EP_ROOT | EP_PAGES);
            });

            add_filter('woocommerce_account_menu_items', function($return) use($item){
                // nếu set index thì chèn vào vị trí index
                if(isset($item['index'])){
                    $firstPart = array_slice($return, 0, (int)$item['index'], true);
                    $secondPart = array_slice($return, (int)$item['index'], null, true);
                    $return = $firstPart + [$item['name'] => $item['label']] + $secondPart;
                }else{
                    $return[$item['name']] = $item['label'];
                }
                
                return $return;
            });
            
            add_action('woocommerce_account_'.$item['name'].'_endpoint', function() use($item) {
                call_user_func($item['callback']);
            });
        }
    }

    function remove_nav_item(){
        if(empty($this->arr_remove) or !is_array($this->arr_remove)) return; 

        add_filter('woocommerce_account_menu_items', function($items){   
            if(!empty($this->arr_remove) and is_array($this->arr_remove)){
                foreach ($this->arr_remove as $key => $value) {
                    if(isset($items[$value])){
                        unset($items[$value]);
                    }
                }
            }
            return $items;
        });
    }

    function change_nav_item(){
        if(empty($this->arr_change_label) or !is_array($this->arr_change_label)) return; 

        add_filter('woocommerce_account_menu_items', function($items){      
            if(!empty($this->arr_change_label) and is_array($this->arr_change_label)){
                foreach ($this->arr_change_label as $key => $value) {
                    if(isset($items[$key])){
                        $items[$key] = $value;
                    }
                }
            }
            return $items;
        });
    }
}



// Ví dụ:
// $custom = new \Adminz\Helper\ADMINZ_Helper_Woocommerce_User_DashBoard;
// $custom->arr_add[] = [
//     'name'=> 'groupbuying2',
//     'label'=> 'GroupBuyingZ3',
//     'callback'=> function (){
//         echo __FUNCTION__;
//     }
// ];
// $custom->init();


