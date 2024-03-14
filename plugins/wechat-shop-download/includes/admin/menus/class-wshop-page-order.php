<?php 
if (! defined ( 'ABSPATH' ))
    exit (); // Exit if accessed directly

/**
 * 菜单：登录设置
 *
 * @since 1.0.0
 * @author ranj
 */
class WShop_Page_Order extends Abstract_WShop_Settings_Page{    
    /**
     * Instance
     * @since  1.0.0
     */
    private static $_instance;
    
    /**
     * Instance
     * @since  1.0.0
     */
    public static function instance() {
        if ( is_null( self::$_instance ) )
            self::$_instance = new self();
            return self::$_instance;
    }
    
    /**
     * 菜单初始化
     *
     * @since  1.0.0
     */
    private function __construct(){
        $this->id='page_order';
        $menu ='';
        if(is_admin()){
            $time = absint(get_option('wshop_order_last_view'));
            global $wpdb;
            $query =  $wpdb->get_row(
                "select count(m.id) as qty
                from {$wpdb->prefix}wshop_order m
                where m.order_date>{$time}
                      and m.status <>'".WShop_Order::Unconfirmed."';");
            
            $qty=$query?absint($query->qty):0;
            $menu =  ' <span class="awaiting-mod update-plugins count-' . esc_attr( $qty ) . '"><span class="processing-count">' . number_format_i18n( $qty ) . '</span></span>';
        }
        $this->title=__('Orders',WSHOP).$menu;
    }
    
    /* (non-PHPdoc)
     * @see Abstract_WShop_Settings_Menu::menus()
     */
    public function menus(){
        require_once 'class-wshop-menu-order-default.php';
        return apply_filters("wshop_admin_page_{$this->id}", array(
            WShop_Menu_Order_Default::instance()
        ));
    }
}?>