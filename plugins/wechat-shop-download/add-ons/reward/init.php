<?php
if (! defined ( 'ABSPATH' )) exit;

require_once 'abstract_xh_add_nos_api.php';
require_once 'includes/class_wshop_reward_view.php';

class WShop_Add_On_Reward extends Abstract_WShop_Add_Ons_Reward_Api{
    private static $_instance = null;

    public $dir;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct(){
        parent::__construct();
        $this->version = '1.0.1';
        $this->id='wshop_add_ons_reward';
        $this->title='打赏';
        $this->description='使用时，将打赏短码插入文章即可。';

        $this->author=__('xunhuweb',WSHOP);
        $this->author_uri='https://www.wpweixin.net';
        $this->plugin_uri='https://www.wpweixin.net';
        $this->dir = WShop_Helper_Uri::wp_dir(__FILE__);

        $this->init_form_fields();
    }

    public function init_form_fields(){
        $fields =array(
            'post_types'=>array(
                'title'=>__('Bind post types',WSHOP),
                'type'=>'multiselect',
                'func'=>true,
                'options'=>array($this,'get_post_type_options')
            ),
            'reward_btn_txt'=>array(
                'title'=>'打赏提示',
                'type'=>'text',
                'default'=>'我要打赏'
            ),
            'reward_min_price'=>array(
                'title'=>'打赏最小金额',
                'type'=>'text',
                'default'=>'1'
            ),
            'reward_recommend_price'=>array(
                'title'=>'打赏推荐金额',
                'type'=>'textarea',
                'default'=>'1,5,10,20,50,100,200',
                'description'=>'推荐金额需用 , 隔开'
            )
        );
        $this->form_fields = apply_filters('wshop_reward_fields', $fields);
    }

    public function on_install(){

    }
    public function on_load(){
        $this->m1();
    }
    public function on_init(){
        $this->m2();
    }
    //文章编辑时，打赏短码插入
    public function register_fields(){
        WShop_Reward_Fields::instance();
    }

    //注册打赏记录的类型
    public function register_post_types(){
        register_post_type( Abstract_WShop_Add_Ons_Reward_Api::POST_T,
            array(
                'labels' => array(
                    'name' => '打赏',
                    'singular_name' =>'打赏',
                    'add_new' => '添加',
                    'add_new_item' =>'添加打赏',
                    'edit' => '编辑',
                    'edit_item' => '编辑打赏',
                    'new_item' => '添加打赏',
                    'view' => '查看',
                    'view_item' => '查看打赏',
                    'search_items' => '查询打赏',
                    'not_found' => '未找到打赏信息',
                    'not_found_in_trash' =>'回收站中无打赏',
                    'parent' => '父级打赏'
                ),
                //决定自定义文章类型在管理后台和前端的可见性
                'public' => false,
                'wshop_ignore'=>true,
                'wshop_include'=>true,
                'menu_position' => 55.5,
                'exclude_from_search '=>true,
                'publicly_queryable'=>true,
                'hierarchical'=>false,
                'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'excerpt','page-attributes' ),
                //创建自定义分类。在这里没有定义
                //'taxonomies' => array( ),
                //启用自定义文章类型的存档功能
                'has_archive' => true
            ));
    }

    public function do_ajax() {
        if(!$_POST){
            echo WShop_Error::err_code(400)->to_json();
            exit;
        }
        $data=$this->check_request_data($_POST);
        //获取货币符号
        $currency=WShop::instance()->payment->get_currency();
        $currency_symbol=WShop_Currency::get_currency_symbol($currency);
        //新增文章类型，作为打赏记录
        $post_data=[
            'post_title'=>'打赏金额'.$currency_symbol.$data['reward_price'],
            'post_parent'=>$data['post_id'],
            'post_type'=>Abstract_WShop_Add_Ons_Reward_Api::POST_T,
            'post_status'=>'publish'
        ];
        $res=wp_insert_post($post_data);
        if(is_wp_error($res)){
            WShop_Error::error_custom($res->get_error_message())->output();
            exit;
        }
        //生成产品
        $api=new WShop_Product();
        $api->post_ID=$res;
        $api->inventory=null;
        $api->sale_qty=1;
        $api->sale_price=$data['reward_price'];
        $res1=$api->insert();
        if(!WShop_Error::is_valid($res1)){
            WShop_Error::error_custom('未知错误')->output();
            exit;
        }
        //加入购物车
        $cart1 = WShop_Shopping_Cart::empty_cart(false);
        if($cart1 instanceof WShop_Error){
            echo $cart1->to_json();exit;
        }
        $cart1 = $cart1->__add_to_cart($res);
        if($cart1 instanceof WShop_Error){
            echo $cart1->to_json();
            exit;
        }
        $cart1->__set_metas(array(
            'section'=>'reward',
            'location'=>$data['location']
        ));
        $error = $cart1->save_changes();
        if(!WShop_Error::is_valid($error)){
            echo $error->to_json();
            exit;
        }
        //生成订单，并返回支付链接
        $cart = WShop_Shopping_Cart::get_cart();
        if($cart instanceof WShop_Error){
            echo $cart->to_json();
            exit;
        }

        $payment_method =$data['payment_method'];
        if($payment_method){
            $cart->__set_payment_method($payment_method);
        }
        $order = $cart->create_order();
        if($order instanceof WShop_Error){
            echo $order->to_json();
            exit;
        }
        echo WShop_Error::success($order->get_pay_url())->to_json();
        exit;
    }
    //验证并返回请求数据
    private function check_request_data($requestData){
        if(!isset($requestData['post_id'])||!isset($requestData['reward_price'])||!isset($requestData['payment_method'])||!isset($requestData['location'])){
            echo WShop_Error::err_code(400)->to_json();
            exit;
        }
        //验证post_id
        $post_id=$requestData['post_id'];
        $post=get_post($post_id);
        if(!$post){
            echo WShop_Error::err_code(400)->to_json();
            exit;
        }
        //验证reward_price
        $reward_price=(float)$requestData['reward_price'];
        if($reward_price<$this->get_option('reward_min_price')){
            $currency=WShop::instance()->payment->get_currency();
            $currency_symbol=WShop_Currency::get_currency_symbol($currency);
            WShop_Error::error_custom('打赏金额不得小于'.$currency_symbol.$this->get_option('reward_min_price'))->output();
            exit;
        }
        //验证payment_method
        $payment_method=$requestData['payment_method'];

        //验证location
        $location=$requestData['location'];

        return [
            'post_id'=>$post_id,
            'reward_price'=>$reward_price,
            'payment_method'=>$payment_method,
            'location'=>$location
        ];
    }

}
return WShop_Add_On_Reward::instance();
