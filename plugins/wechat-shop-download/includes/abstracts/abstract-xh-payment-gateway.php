<?php
if (! defined ( 'ABSPATH' ))
	exit (); // Exit if accessed directly
	
/**
 * 社会化登录工具接口
 *
 * @since 1.0.0
 * @author ranj
 */
abstract class Abstract_WShop_Payment_Gateway extends Abstract_WShop_Settings{   
    /**
     * 图标地址
     * @var string
     * @since 1.0.0
     */
    public $icon;
    public $icon_small;
    
    public $group;
    
    /**
     * 判断是否启用
     * 
     * @param array $actions 申明支持接口,必须每个接口都存在，否则返回false
     * @return bool 
     * @since 1.0.0
     */
    public function is_available($action_includes = array()) {
        return $this->enabled;
    }
    
    /**
     * 执行支付
     * @param WShop_Order $order
     * @return WShop_Error
     * @since 1.0.0 
     */
    public abstract function process_payment($order);
    
    /**
     * 查询订单是否成功
     * 
     * @param $transaction_id string
     * @param $order WShop_Order
     * 
     * @return boolean
     */
    public function query_order_transaction($transaction_id,$order){
        return $transaction_id;
    }
}