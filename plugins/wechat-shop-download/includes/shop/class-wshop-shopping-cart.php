<?php 
if (! defined ( 'ABSPATH' ))
    exit (); // Exit if accessed directly

class WShop_Shopping_Cart extends Abstract_WShop_Shopping_Cart{
    const COOKIE_ID='wshop-uid';
    public function __construct($wp_cart=null){
        parent::__construct($wp_cart);
    }
    
    /**
     * @return WShop_Shopping_Cart
     */
    public static function get_cart(){
       $cart =  WShop_Temp_Helper::get('cart','object',null);
       if($cart){
           $cart->__free_order_hook();
           return $cart;
       }
       
        $customer_id = WShop::instance()->session->get_customer_id();
        
        $cart =  new WShop_Shopping_Cart($customer_id);
        if(!$cart->is_load()){
            $cart->created_time = current_time( 'timestamp' );
            $cart->customer_id = $customer_id;
            $error = $cart->insert();
            if(!WShop_Error::is_valid($error)){
                return $error;
            }
        }else{
            $cart->__free_order_hook();
        }
        
        WShop_Temp_Helper::set('cart', $cart,'object');
        return $cart;
    }
    
    public function get_payment_method(){
        return $this->get('payment_method');
    }
    
    public function get_total_qty(){
        $items = $this->get_items();
        if($items instanceof WShop_Error){
            self::empty_cart();
            return 0;
        }
        
        $total = 0;
        if($items){
            foreach ($items as $post_ID=>$settings){
                $total+=isset($settings['qty'])?absint($settings['qty']):0;
            }
        }
        
        return $total;
    }
    
    /**
     * @return WShop_Shopping_Cart
     */
    public function __empty_cart(){
       $this->order =null;
       
       return $this->set_change('coupons', array())
        ->set_change('items', array())
        ->set_change('order_id', null)
        ->set_change('metas', array())
        ->set_change('obj_type', null)
        ->set_change('payment_method', null);
    }
    
    /**
     * 当前购物车绑定的订单信息
     * @var WShop_Order
     */
    private $order=null;
    
    /**
     * 释放购物车
     */
    public function __free_order_hook(){
        if(!$this->order){
            $order_id = $this->order_id;
            if(!$order_id){
                return;
            }
            
            $this->order = new WShop_Order($order_id);
        }
        
        if(! $this->order->is_load()){
            $this->__empty_cart()->save_changes();
            return;
        }
        
        if($this->order->is_paid()){
            $this->__empty_cart()->save_changes();
            return;
        }
    }
    
    public function set_order($order_id){
        $this->__set_order($order_id);
        
        return $this->save_changes();
    }
    
    public function __set_order($order_id){
        return $this->set_change('order_id', $order_id);
    }
    
    public static function add_to_cart($post_id,$qty =1,$inventory_valid_func=null,$metas=array()){
        $cart = WShop_Shopping_Cart::get_cart();
        if($cart instanceof WShop_Error){
            return $cart;
        }
      
        $cart = $cart->__add_to_cart($post_id,$qty,$inventory_valid_func,$metas);
        if($cart instanceof WShop_Error){
            return $cart;
        }
        return $cart->save_changes();
    }
    
    public function __set_payment_method($payment_method_id){
        if(is_null($payment_method_id)){
            return $this->set_change('payment_method', null);
        }
        
        $payment_method = WShop::instance()->payment->get_payment_gateway($payment_method_id);
        if(!$payment_method){
            throw new Exception(sprintf(__('unknow payment method:%s',WSHOP),$payment_method_id));
        }
        
        return $this->set_change('payment_method', $payment_method->id);
    }
    
    public function __set_meta($meta_key,$meta_val=null){
        $this->metas[$meta_key] = $meta_val;
    
        return $this->set_change('metas', $this->metas);
    }
    
    public function __set_metas($metas =array()){
        if(!is_array($metas)){$metas=array();}
        
        return $this->set_change('metas', array_merge($this->metas,$metas));
    }
    
    public function get_meta($meta_key,$default=null){
       return $this->metas&&isset($this->metas[$meta_key])? $this->metas[$meta_key]:$default;
    }
    
    public static function get_cookie_id($create=false){
        $cookie = WShop::instance()->session->get(self::COOKIE_ID);
        $now = time();
        $hash_key = WShop::instance()->get_hash_key();
        $cookie_id=null;
        $expire_time = 365*24*60*60;
        if(!empty($cookie)){
            $ids = explode('.', $cookie);
            if(count($ids)==4){
                $_cookie_id = $ids[0];
                $_hash = $ids[1];
                $_notice_str = $ids[2];
                $_now = absint($ids[3]);
                
                if($_now>($now-$expire_time)){
                    if($_hash==substr(md5($_cookie_id.$_notice_str.$_now.$hash_key), 6,6)){
                        $cookie_id= $_cookie_id;
                    }
                }
            }
        }
        
        if($create){
            if(empty($cookie_id)||strlen($cookie_id)!=32){
                $cookie_id = md5(WShop_Helper_String::guid().$now);
                $notice_str = substr(str_shuffle($now), 0,6);               
                $hash = substr(md5($cookie_id.$notice_str.$now.$hash_key), 6,6);               
                $cookie_val ="{$cookie_id}.{$hash}.{$notice_str}.{$now}";           
                WShop::instance()->session->set(self::COOKIE_ID,$cookie_val);
            }
        }
        
        return $cookie_id;
    }
    
    public function create_order($on_order_created=null,$on_order_item_created=null,$status_of_order=null){
        $order = apply_filters('wshop_create_order', new WShop_Order(),$this);
    
        $order->payment_method = $this->get_payment_method();
        //如果支付方式未选择，那么订单为未确定订单，不进入订单列表
        $order->status = $status_of_order?$status_of_order: WShop_Order::Pending;
        
        if(is_user_logged_in()){
            $order->customer_id = get_current_user_id();
        }
      
        $order->cookie_id = self::get_cookie_id(true);
        $order->section = $this->get_meta('section');
        $order->metas = $this->metas;
        $order->obj_type = $this->obj_type;
        $order->exchange_rate = round(floatval(WShop_Settings_Default_Basic_Default::instance()->get_option('exchange_rate')),4);
        $order->total_amount= 0;
        
        $items =$this->get_items(true);
        if($items instanceof WShop_Error){
            return $items;
        }
        $post_type=null;
        foreach ($items as $post_id =>$item){
            $product =$item['product'];
            if(!$product
                ||!$product instanceof WShop_Product
                ||
                (
                    !is_null($post_type)
                    &&$product->post->post_type!=$post_type)
                ){
                continue;
            }
    
            $post_type=$product->post->post_type;
            $order_item_creator = apply_filters('wshop_order_item_creator', function($order,$item){
                $product =$item['product'];
                $order_item =new WShop_Order_Item();
                $order_item->price = $product->get_single_price(false);
                $order_item->qty =$item['qty'];
                $order_item->inventory = $item['qty'];
                $order_item->post_ID = $product->post_ID;
                $order_item->metas =array(
                    'title'=>$product->get_title(),
                    'img'=>$product->get_img(),
                    'link'=>$product->get_link(),
                    'post_type'=>$product->post->post_type
                );
                return $order_item;
            },$order,$item);
            
            $order_item_creator = apply_filters("wshop_{$post_type}_order_item_creator",$order_item_creator,$order,$item);    
            $order_item_creator = apply_filters("wshop_{$order->section}_order_item_creator",$order_item_creator,$order,$item);
            
            $order_item =call_user_func_array($order_item_creator, array($order,$item));
            if($order_item instanceof WShop_Error){
                return $order_item;
            }
            $order->order_items[]=$order_item;
            $order->total_amount +=$order_item->get_subtotal(false);
        }
        
        $order->total_amount=apply_filters('wshop_order_total_amount_before',$order->total_amount,$order,$this);
        
        //init extra_amount
        $order->extra_amount = array();
        $error=apply_filters('wshop_order_extra_amount',WShop_Error::success(),$order,$this);
        if(!WShop_Error::is_valid($error)){
            return $error;
        }
        
        foreach ($order->extra_amount as $label=>$atts){
            $order->total_amount+=$atts['amount'];
        }
        
        $order->total_amount=apply_filters('wshop_order_total_amount_final',$order->total_amount,$order,$this);
        
        $error = $order->insert();
        if(!WShop_Error::is_valid($error)){
            return $error;
        }
        
        $error = $order->call_after_insert();
        if(!WShop_Error::is_valid($error)){
            return $error;
        }
        
        //order items
        foreach ($order->order_items as $order_item){
            $order_item->order_id = $order->id;
    
            $error = $order_item->insert();
            if(!WShop_Error::is_valid($error)){
                return $error;
            }
            
            $error = apply_filters('wshop_order_item_created', WShop_Error::success(),$order_item,$order,$this);
            if(!WShop_Error::is_valid($error)){
                return $error;
            }
            
            $error = apply_filters("wshop_order_item_{$order->obj_type}_created", WShop_Error::success(),$order_item,$order,$this);
            if(!WShop_Error::is_valid($error)){
                return $error;
            }
            
            if($on_order_item_created){
                $error = call_user_func_array($on_order_item_created, array($order,$order_item));
                if(!WShop_Error::is_valid($error)){
                    return $error;
                }
            }
        }
       
        $error = apply_filters('wshop_order_created', WShop_Error::success(),$order,$this);
        if(!WShop_Error::is_valid($error)){
            return $error;
        }
        
        $error = apply_filters("wshop_order_{$order->section}_created", WShop_Error::success(),$order,$this);
        if(!WShop_Error::is_valid($error)){
            return $error;
        }
        
        if($on_order_created){
            $error = call_user_func($on_order_created, $order);
            if(!WShop_Error::is_valid($error)){
                return $error;
            }
        }
        
        $this->__set_order($order->id);       
        $error = $this->save_changes();     
        if($error instanceof WShop_Error){
            return $error;
        }
        
        $error = $order->save_changes();
        if($error instanceof WShop_Error){
            return $error;
        }
        return $order;
    }
    
    /**
     * 加入购物车代码必须要放到最前
     * 
     * @param int $post_id
     * @param number $qty
     * @param function $func_inventory_valid
     * @return WShop_Shopping_Cart
     */
    public function __add_to_cart($post_id,$qty =1,$func_inventory_valid=null,$metas=array()){
        $product = new WShop_Product($post_id);
        if(!$product->is_load()){
            return WShop_Error::error_custom(__('Product info is invalid!',WSHOP));
        }
        
        if($this->obj_type&&$product->post_type!=$this->obj_type){
            $this->__empty_cart();
        }
        
        if(!is_numeric($qty)){$qty=1;}
        if(!$this->items||!is_array($this->items)){$this->items=array();}

        $new_items = array();
        foreach ($this->items as $post_ID=>$settings){
            $_product = new WShop_Product($post_id);
            if(!$_product->is_load()||$_product->get('post_status')!='publish'||$_product->get('post_type')!=$product->get('post_type')){
                continue;
            }
        
            $new_items[$post_ID] = $settings;
        }
       
        $this->items=$new_items;
        unset($new_items);
        
        if(!$func_inventory_valid){
            $func_inventory_valid = function($cart,$product,$qty,$metas){ 
                $oqty = isset($cart->items[$product->post_ID]['qty'])? absint($cart->items[$product->post_ID]['qty']):0;   
                $now_qty =$oqty+$qty;
                
                $cart->items[$product->post_ID]['qty']=$now_qty;
                if(isset($cart->items[$product->post_ID]['metas'])&&$cart->items[$product->post_ID]['metas']&&is_array($cart->items[$product->post_ID]['metas'])){
                    $cart->items[$product->post_ID]['metas'] = array_merge($cart->items[$product->post_ID]['metas'],$metas);
                }else{
                    $cart->items[$product->post_ID]['metas']=$metas;
                }
                
                $inventory = $product->get('inventory');
                $api = WShop_Settings_Checkout_Options::instance();
                $enable_ = $api->get_option('modal')=='shopping_cart'&&$api->get_option('enable_inventory')=='yes';
                if(!is_null($inventory)&&$enable_) {
                    if($inventory-$now_qty<0){
                        return WShop_Error::error_custom(__('Product is understock!',WSHOP));
                    }
                }
            
                return apply_filters('wshop_cart_item_validate', WShop_Error::success(),$cart,$product,$now_qty);
            };
        }
    
        $error = call_user_func_array($func_inventory_valid, array($this,$product,$qty,$metas));
        if($error instanceof WShop_Error && !WShop_Error::is_valid($error)){
            return $error;
        }
       
        try {
            do_action_ref_array('wshop_shopping_cart_item_added', array(&$this,$product));
        } catch (Exception $e) {
            return WShop_Error::error_custom($e->getMessage());
        }
        
        $this->set_change('obj_type', $product->get('post_type'));
        return $this->set_change('items', $this->items);
    }
    
}