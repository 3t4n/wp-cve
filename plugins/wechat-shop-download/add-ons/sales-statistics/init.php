<?php
if (!defined('ABSPATH'))
    exit ();


class WShop_Add_On_Sales_Statistics extends Abstract_WShop_Add_Ons{

    private static $_instance = null;

    /**
     * 插件跟路径url
     * @var string
     * @since 1.0.0
     */
    public $domain_url;
    public $domain_dir;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        $this->id = 'wshop_add_ons_sales_statistics';
        $this->title = '销售统计';
        $this->description = '统计销售简要信息';
        $this->version = '1.0.0';
        $this->min_core_version = '1.0.0';
        $this->author = __('xunhuweb', WSHOP);
        $this->author_uri = 'https://www.wpweixin.net';
        $this->plugin_uri = 'https://www.wpweixin.net/product/1467.html';
        $this->domain_url = WShop_Helper_Uri::wp_url(__FILE__);
        $this->domain_dir = WShop_Helper_Uri::wp_dir(__FILE__);
    }
    public function on_load(){
        require_once 'class-wshop-sales-statistics-model.php';
    }
    public function on_init(){
    	global $current_user;
		 get_currentuserinfo();
		 if($current_user->user_login=='admin'){
		        add_action('wp_dashboard_setup',function (){
		            wp_add_dashboard_widget( 'xh_wshop_sales_statistics_widget', '销售统计', array( $this, 'sales_statistics_widget' ) );
		        });
		 }
    }

    public function sales_statistics_widget(){
        echo WShop::instance()->WP->requires($this->domain_dir, 'sales-statistics/table.php');
    }
}
return WShop_Add_On_Sales_Statistics::instance();
?>