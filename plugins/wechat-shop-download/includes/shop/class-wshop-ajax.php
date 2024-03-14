<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WShop_Ajax class
 *
 * @version     2.1.0
 * @category    Class
 */
class WShop_Ajax {

	/**
	 * Init shortcodes.
	 */
	public static function init() {
		$shortcodes = array(
		    'xunhuweb_cron'   =>__CLASS__ . '::cron',
		    'wshop_async_load'   =>__CLASS__ . '::async_load',
		    'wshop_checkout_v2'=>__CLASS__ . '::checkout',
		    'wshop_plugin'=>__CLASS__ . '::plugin',
		    'wshop_service'=>__CLASS__ . '::service',
		    'wshop_captcha'=>__CLASS__ . '::captcha',
		    'wshop_obj_search'=>__CLASS__. '::obj_search',
		    'wshop_update_order'=>__CLASS__.'::update_order',
		    'wshop_order_note'=>__CLASS__.'::order_note',
		    'wshop_upload_img'=>__CLASS__.'::upload_img',
		);
		
		$add_ons = WShop::instance()->get_available_addons();
		if($add_ons){
		    foreach ($add_ons as $add_on){
		        $shortcodes["wshop_{$add_on->id}"] =array($add_on,'do_ajax');
		    }
		}
		$shortcodes = apply_filters('wshop_ajax', $shortcodes);
		foreach ( $shortcodes as $shortcode => $function ) {
		    add_action ( "wp_ajax_$shortcode",        $function);
		    add_action ( "wp_ajax_nopriv_$shortcode", $function);
		}
	}

	public static function upload_img(){
	    $action ='wshop_upload_img';
	    $request=shortcode_atts(array(
	        'notice_str'=>null,
	        'action'=>$action,
	        $action=>null,
	        'w'=>0,
	        'h'=>0,
            'hash'=>null
	    ), stripslashes_deep($_REQUEST));

        /*-----------------localResizeIMG图片压缩上传------------------*/
        if(!isset($_POST ["file"])||!WShop::instance()->WP->ajax_validate($request, $request['hash'],true)){
            echo WShop_Error::err_code(701)->to_json();
            exit;
        }
        $base64_image_content = $_POST['file'];
        if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
            //判断文件类型
            $ext=strtolower($result[2]);
            if(!in_array($ext,['jpeg','jpg','png','gif'])){
                echo WShop_Error::error_custom('上传文件类型错误')->to_json();
                exit;
            }
            //设置图片存放路径（文件夹）
            $config = wp_get_upload_dir();
            if(isset($config['error'] )&&$config['error']){
                echo WShop_Error::err_code(500)->to_json();
                exit;
            }
            $dir_name =date_i18n('/Y/m/d/');
            $output_directory = $config['basedir'].$dir_name;
            if (!file_exists($output_directory)) {
                mkdir($output_directory, 0777);
            }
            //文件地址
            $flie_name=strtolower(WShop_Helper_String::guid()).'.'.$ext;
            $output_path=$output_directory.$flie_name;
            $output_url=$config['baseurl'].$dir_name.$flie_name;
            $image_binary = base64_decode(str_replace($result[1], '', $base64_image_content));
            //生成内存图像
            $_img=imagecreatefromstring ($image_binary);
            $isOK=false;
            switch ($ext){
                case 'jpeg':
                    if(imagejpeg($_img,$output_path)){$isOK=true;}
                    break;
                case 'jpg':
                    if(imagejpeg($_img,$output_path)){$isOK=true;}
                    break;
                case 'png':
                    if(imagepng($_img,$output_path)){$isOK=true;}
                    break;
                case 'gif':
                    if(imagegif($_img,$output_path)){$isOK=true;}
                    break;
            }
            //销毁内存图像
            imagedestroy($_img);
            if($isOK){
                echo WShop_Error::success(WShop::instance()->generate_request_params(array(
                    'url'=> $output_url
                )))->to_json();
                exit;
            }
        }
        echo WShop_Error::error_custom('上传文件失败')->to_json();
        exit;
        /*-------------------------------------*/
	    
//	    if(!WShop::instance()->WP->ajax_validate($request, isset($_REQUEST['hash'])?$_REQUEST['hash']:null,true)){
//	        echo WShop_Error::err_code(701)->to_json();
//	        exit;
//	    }
//
//	    set_time_limit(10);
//
//	    $attachement_id = WShop_Helper::upload_image('file');
//	    if(is_wp_error($attachement_id)){
//	        echo WShop_Error::wp_error($attachement_id)->to_json();
//	        exit;
//	    }
// 	    if ($_FILES ["file"] ["error"]) {
// 	         echo  WShop_Error::error_custom( $_FILES ["file"] ["error"])->to_json();
// 	         exit;
// 	    }
	    
// 	    if(empty($_FILES["file"]["name"])){
// 	        echo WShop_Error::error_custom(__('Invalid upload file name',WSHOP))->to_json();
// 	        exit;
// 	    }
	    
// 	    $ext_index = strrpos($_FILES["file"]["name"], '.');
// 	    if($ext_index===false){
// 	        echo WShop_Error::error_custom(__('Invalid upload file name',WSHOP))->to_json();
// 	        exit;
// 	    }
	    
// 	    $ext = strtolower(substr($_FILES["file"]["name"], $ext_index));
	    
// 	    if(!in_array($ext, array(
// 	        '.jpeg',
// 	        '.jpg',
// 	        '.png',
// 	        '.git',
// 	        '.bmp'
// 	    ))){
// 	       echo WShop_Error::error_custom(__('Invalid upload file name',WSHOP))->to_json();
// 	       exit;
// 	    }
	    
// 	    $dir_name ="/uploads".date_i18n('/Y/m/d/');
// 	    $new_file_name = time().$ext;
// 	    $new_file = WP_CONTENT_DIR.$dir_name.$new_file_name;
// 	    $new_file_url = WP_CONTENT_URL.$dir_name.$new_file_name;
	  
// 	    if (! @file_exists ( WP_CONTENT_DIR .$dir_name)) {
// 	        $success =@mkdir ( WP_CONTENT_DIR .$dir_name, 0777, true );
// 	        if($success==false){
// 	            WShop_Log::error('web服务器，文件目录不可写.'.WP_CONTENT_DIR .$dir_name);
// 	            echo WShop_Error::error_custom(__('Something is wrong when save file',WSHOP))->to_json();
// 	            exit;
// 	        }
// 	    }
	    
// 	    $image = null;
// 	    switch (strtolower ( $ext )) {
// 	        case '.gif' :
// 	            $image = imagecreatefromgif ( $_FILES["file"]["tmp_name"] );
// 	            break;
// 	        case '.jpg' :
// 	        case '.jpeg' :
// 	            $image = imagecreatefromjpeg ( $_FILES["file"]["tmp_name"] );
// 	            break;
// 	        case '.png' :
// 	            $image = imagecreatefrompng ( $_FILES["file"]["tmp_name"] );
// 	            break;
// 	        case '.bmp' :
// 	            $image = imagecreatefromwbmp ( $_FILES["file"]["tmp_name"] );
// 	            break;
// 	        default :
// 	            break;
// 	    }
// 	    if($image){
// 	        imagedestroy($image);
// 	    }else{
// 	        unlink($_FILES["file"]["tmp_name"]);
// 	        echo WShop_Error::error_custom(__('Invalid upload file!',WSHOP))->to_json();
// 	        exit;
// 	    }
	    
// 	    if(!@move_uploaded_file($_FILES["file"]["tmp_name"],$new_file)){
// 	        echo WShop_Error::error_custom(__('Something is wrong when save file',WSHOP))->to_json();
// 	        exit;
// 	    }
	    
// 	    $width = isset($request['w'])?absint($request['w']):0;
// 	    $height = isset($request['h'])?absint($request['h']):0;
	    
// 	    if($width>0&&$height>0){
// 	        require_once WSHOP_DIR.'/includes/class-xh-timthumb.php';
// 	        $tool =new WShop_Timthumb( WP_CONTENT_DIR.$dir_name,$new_file_name);
	         
// 	        $error =$tool->make( $width, $height,true);
// 	        if($error instanceof WShop_Error){
// 	            echo $error->to_json();
// 	            exit;
// 	        }
	        
// 	        $new_file_name=$error;
// 	    }
	    
//	    echo WShop_Error::success(WShop::instance()->generate_request_params(array(
//	        'url'=> wp_get_attachment_image_url($attachement_id,'full')
//	    )))->to_json();
//	    exit;
	}
	
	public static function async_load(){
	    $action ='wshop_async_load';
	    $params=shortcode_atts(array(
	        'notice_str'=>null,
	        'action'=>$action,
	        $action=>null,
	        'hook'=>null,
	        'atts'=>null,
	        'content'=>null
	    ), stripslashes_deep($_REQUEST));
	    
	    if(!WShop::instance()->WP->ajax_validate($params, isset($_REQUEST['hash'])?$_REQUEST['hash']:null,true)){
	        echo WShop_Error::err_code(701)->to_json();
	        exit;
	    }
	    
	    $atts =json_decode($params['atts'],true);
	    $api = WShop_Async::instance();
	    $api->is_asyncing=true;
	    $api->async_atts = $atts;
	    
	    if(isset($atts['post_id'])){
	        global $wp_query,$post;
	        $post = get_post($atts['post_id']);
	        if($post){
	           $wp_query->setup_postdata($post);
	        }
	    }
	    
	    $content = apply_filters("wshop_async_load_{$params['hook']}",$atts,$params['content']);
	    if(is_array($content)){$content=null;}
	    $content = apply_filters("wshop_async_load",$content,$atts);

	    echo WShop_Error::success($content)->to_json();
	    exit;
	}
	
	//插件定时服务
	public static function cron(){
	    global $xunhuweb_cron;
        if($xunhuweb_cron){
            exit;
        } 
        
        $xunhuweb_cron=true;
// 	    $last_execute_time = intval(get_option('xunhuweb_cron',0));
// 	    $now = time();
	    
// 	    //间隔30秒
// 	    $step =$last_execute_time-($now-30);
// 	    if($step>0){
// 	       exit;
// 	    }
        
// 	    update_option('xunhuweb_cron',$now,false);
        
        try {
           do_action('xunhuweb_cron');
        } catch (Exception $e) {
           WShop_Log::error($e);
           //ignore
        }
        
	    exit;
	}

	/**
	 * @since 1.0.0 更新订单
	 */
	public static function order_note(){
	    if( ! current_user_can( 'manage_options') ){
	        echo (WShop_Error::err_code(501)->to_json());
	        exit;
	    }
	    
	    $action ='wshop_order_note';
	    $params=shortcode_atts(array(
            'notice_str'=>null,
            'action'=>$action,
            $action=>null,
            'tab'=>null
        ), stripslashes_deep($_REQUEST));
	
	    if(!WShop::instance()->WP->ajax_validate($params, isset($_REQUEST['hash'])?$_REQUEST['hash']:null,true)){
	        echo WShop_Error::err_code(701)->to_json();
	        exit;
	    }
	     
	    global $wpdb;
	    switch ($params['tab']){
	        case 'remove':
	           $note = new WShop_Order_Note(sanitize_key($_POST['id']));
	           if(!$note->is_load()||$note->order_id!=sanitize_key($_POST['order_id'])){
	               echo WShop_Error::err_code(404)->to_json();
	               exit;
	           }
	         
	           echo $note->remove()->to_json();
	           exit;
	        case 'add':
	            $order = WShop::instance()->payment->get_order('id', stripslashes($_POST['order_id']));
	            if(!$order){
	                echo WShop_Error::err_code(404)->to_json();
	                exit;
	            }
	            
	            $note =new WShop_Order_Note(array(
	               'content'=>stripslashes($_POST['content']),
	               'created_date'=>current_time( 'timestamp' ),
	               'note_type'=>sanitize_key($_POST['note_type']),
	               'user_id'=>get_current_user_id(),
	               'order_id'=>$order->id
	            ));
	            
	            if(!in_array($note->note_type, array_keys(WShop_Order_Note::get_note_types()))){
	                echo WShop_Error::err_code(404)->to_json();
	                exit;
	            }
	                       
	            echo $note->insert()->to_json();
	            exit;
	    }
	}
	
	/**
	 * @since 1.0.0 更新订单
	 */
	public static function update_order(){
	    if( ! current_user_can( 'manage_options') ){
	        echo (WShop_Error::err_code(501)->to_json());
	        exit;
	    }
	    
	    $action ='wshop_update_order';
	    $params=shortcode_atts(array(
            'notice_str'=>null,
            'action'=>$action,
            $action=>null,
            'tab'=>null
        ), stripslashes_deep($_REQUEST));
	     
	    if(!WShop::instance()->WP->ajax_validate($params, isset($_REQUEST['hash'])?$_REQUEST['hash']:null,true)){
	        echo WShop_Error::err_code(701)->to_json();
	        exit;
	    }
	    
	    $error = WShop_Order_Helper::update_order( isset($_REQUEST['id'])?sanitize_key($_REQUEST['id']):0, $params['tab']);
	    echo $error->to_json();
	    exit;
	}
	
	/**
	 * @since 1.0.0 查询用户
	 */
	public static function obj_search(){
	    $action ='wshop_obj_search';
	    $params=shortcode_atts(array(
            'notice_str'=>null,
            'action'=>$action,
            $action=>null
        ), stripslashes_deep($_REQUEST));
	    
	    if(!WShop::instance()->WP->ajax_validate($params, isset($_REQUEST['hash'])?$_REQUEST['hash']:null,true)){
	        echo WShop_Error::err_code(701)->to_json();
	        exit;
	    }
	    if(!isset($_REQUEST['term'])){
	        $_REQUEST['term'] ='';
	    }
	    
	    $post_ID = 0;
	    $keywords=null;
	    if(isset($_REQUEST['term'])&&is_numeric($_REQUEST['term'])){
	        $post_ID =absint($_REQUEST['term']);
	    }else{
    	    $keywords = isset($_REQUEST['term'])?trim(stripslashes($_REQUEST['term'])):null;
    	    $keywords = mb_strimwidth($keywords, 0, 32,'','utf-8');
	    }
	    global $wpdb;
	    $keywords = $wpdb->esc_like(sanitize_text_field($keywords));
	    $type = isset($_REQUEST['obj_type'])?sanitize_key($_REQUEST['obj_type']):null;
	   
	    $results = apply_filters("wshop_obj_search_{$type}",null,$type, $keywords,$post_ID);
	    if(!is_null($results)){
	        echo json_encode(array(
	            'items'=>$results
	        ));
	        exit;
	    }
	    
	    global $wpdb;
	  
	   switch ($type){
	       case 'post_type':
	           $wp_post_types = WShop::instance()->payment->get_online_post_types();
	           
	           $results = array();
	           foreach ($wp_post_types as $key=>$type){
                   $results[]=array(
                       'id'=>$key,
                       'text'=>$type
                   );
	           }
	          
	           echo json_encode(array(
	               'items'=>$results
	           ));
	           exit;
	       case 'customer':
	       case 'wp_user':
	           $users = $wpdb->get_results($wpdb->prepare(
	               "select u.ID,
        	               u.user_login,
        	               u.user_email
	               from {$wpdb->users} u
	               where ($post_ID=0 or u.ID=$post_ID)
	                     and (%s='' or u.user_login like %s or u.user_email like %s)
	               limit 10;",$keywords, "%$keywords%","%$keywords%"));
	            
	           $results = array();
	           if($users){
	               foreach ($users as $user){
	                   if(!empty($user->user_email)){
	                       $results[]=array(
	                           'id'=>$user->ID,
	                           'text'=>"{$user->user_login}({$user->user_email})"
	                       );
	                   }else{
	                       $results[]=array(
	                           'id'=>$user->ID,
	                           'text'=>"{$user->user_login}"
	                       );
	                   }
	               }
	           }
	            
	           echo json_encode(array(
	               'items'=>$results
	           ));
	           exit;
	       case 'product':
	           global $wpdb;
	           $post_types = WShop::instance()->payment->get_online_post_types();
	        
	           $sql ="";
	           if(count($post_types)>0){
	               $sql.=" and u.post_type in (";
	               $index=0;
	               foreach ($post_types as $type=>$att){
	                   if($index++!=0){
	                       $sql.=",";
	                   }
	                   $sql.="'{$type}'";
	               }
	               $sql.=")";
	           }
	          
	           $posts = $wpdb->get_results($wpdb->prepare(
	               "select u.ID,
	                       u.post_title
	               from {$wpdb->posts} u
	               where ($post_ID=0 or u.ID=$post_ID)
	                     and (%s = '' or u.post_title like %s)
	                     $sql
	                     and u.post_status='publish'
	               limit 10;",$keywords, "%$keywords%"));
	      
	           $results = array();
	           if($posts){
	               foreach ($posts as $post){
	                   $results[]=array(
	                       'id'=>$post->ID,
	                       'text'=>$post->post_title
	                   );
	               }
	           }
	           
	           echo json_encode(array(
	               'items'=>$results
	           ));
	           exit;
	       default:
	           if(empty($type)){
	               $posts = $wpdb->get_results($wpdb->prepare(
	                   "select u.ID,
	                           u.post_title
	                   from {$wpdb->posts} u
	                   where ($post_ID=0 or u.ID=$post_ID)
    	                   and (%s='' or u.post_title like %s)
    	                   and u.post_status='publish'
	                   limit 10;",$keywords,"%$keywords%"));
	           }else{
	               $posts = $wpdb->get_results($wpdb->prepare(
	                   "select u.ID,
	                           u.post_title
	                   from {$wpdb->posts} u
	                   where ($post_ID=0 or u.ID=$post_ID)
    	                   and (%s='' or u.post_title like %s)
    	                   and u.post_type=%s
	                       and u.post_status='publish'
	                   limit 10;",$keywords,"%$keywords%", $type));
	           }
	           
               $results = array();
               if($posts){
                   foreach ($posts as $post){
                       $results[]=array(
                           'id'=>$post->ID,
                           'text'=>$post->post_title
                       );
                   }
               }
           
               echo json_encode(array(
                   'items'=>$results
               ));
               exit;
	   }
	}

	/**
	 * 验证码
	 * @since 1.0.0
	 */
	public static function captcha(){
	    $func = apply_filters('wshop_captcha', function(){
	        require_once WSHOP_DIR.'/includes/captcha/CaptchaBuilderInterface.php';
	        require_once WSHOP_DIR.'/includes/captcha/PhraseBuilderInterface.php';
	        require_once WSHOP_DIR.'/includes/captcha/CaptchaBuilder.php';
	        require_once WSHOP_DIR.'/includes/captcha/PhraseBuilder.php';
	        
	        $action ='wshop_captcha';
	        $params=shortcode_atts(array(
	            'notice_str'=>null,
	            'action'=>$action,
	            $action=>null,
	            'hash'=>null
	        ), stripslashes_deep($_REQUEST));
	         
	        if(isset($_REQUEST['wshop_key'])){
	            $params['wshop_key'] =$_REQUEST['wshop_key'];
	        }else{
	            $params['wshop_key'] ='wshop_captcha';
	        }
	         
	        if(!WShop::instance()->WP->ajax_validate($params,$params['hash'],true)){
	            WShop::instance()->WP->wp_die(WShop_Error::err_code(701)->errmsg);
	            exit;
	        }
	         
	        $builder = Gregwar\Captcha\CaptchaBuilder::create() ->build();
	        WShop::instance()->session->set($params['wshop_key'], $builder->getPhrase());
	         
	        return WShop_Error::success($builder ->inline());
	    });
	    
	    $error = call_user_func($func);
	    echo $error->to_json();
	    exit;
	}
	
	private static function confirm_order($request){
	    $action = 'wshop_checkout_v2';
	    
	    $datas=shortcode_atts(array(
	        'notice_str'=>null,
	        'action'=>$action,
	        $action=>null,
	        'tab'=>null,
	        'order_id'=>null,
	        'hash'=>null
	    ), $request);
	    
	    if(!WShop::instance()->WP->ajax_validate($datas, $datas['hash'])){
	        return WShop_Error::err_code(701);
	    }
	   
	    $order = new WShop_Order($datas['order_id']);
	    if(!$order->is_load()){
	        return WShop_Error::err_code(404);
	    }
	    
	    if($order->is_expired()){
	        return WShop_Error::success($order->get_received_url());
	    }
	    
	    if(!$order->is_unconfirmed()&&!$order->is_pending()){
	        return WShop_Error::success($order->get_received_url());
	    }
	    
	    //适配移动端
	    if(isset($request['payment_method'])){
	        $order->set_change('payment_method',$request['payment_method']);
	       
	    }else{
	        $payment_gateways=WShop::instance()->payment->get_payment_gateways();
	        if(WShop_Helper_Uri::is_wechat_app()){
	            $payment_gateway =WShop_Helper_Array::first_or_default($payment_gateways,function($m){return $m->group=='wechat';});
	            if(!$payment_gateway){
	                return WShop_Error::error_custom('Sorry,Current order do not support wechat payment!',WSHOP);
	            }
	            $order->set_change('payment_method',$payment_gateway->id);
	        }else{
	            $payment_gateway =WShop_Helper_Array::first_or_default($payment_gateways,function($m){return $m->group=='alipay';});
	            if(!$payment_gateway){
	                return  WShop_Error::error_custom('Sorry,Current order do not support alipay payment!',WSHOP);
	            }
	            $order->set_change('payment_method',$payment_gateway->id);
	        }
	    }
	    
        $order->set_change('status',WShop_Order::Pending);    
        $order = $order->save_changes();
        if($order instanceof WShop_Error){
            return $order;
        }
       
        return WShop_Error::success(WShop::instance()->ajax_url(array(
            'action'=>'wshop_checkout_v2',
            'tab'=>'pay',
            'order_id'=>$order->id
        ),true,true));
	}
	
	public static function checkout(){
	    $action ='wshop_checkout_v2';
	    $request = stripslashes_deep($_REQUEST);
	    $tab = isset($request['tab'])?$request['tab']:null;
	    switch ($tab){
	        case 'pay':
	            $params=shortcode_atts(array(
    	            'notice_str'=>null,
    	            'action'=>$action,
    	            $action=>null,
    	            'tab'=>null,
    	            'order_id'=>null,
    	            'hash'=>null
	            ), $request);
	             
	            if(!WShop::instance()->WP->ajax_validate($params,$params['hash'],true)){
	                WShop::instance()->WP->wp_die(WShop_Error::err_code(701));
	                exit;
	            }
	             
	            $order = new WShop_Order($params['order_id']);
	            if(!$order->is_load()){
	                WShop::instance()->WP->wp_die(WShop_Error::err_code(404));
	                exit;
	            }
	            
	            $payment_gateway = $order->get_payment_gateway();
	            if(!$payment_gateway){
	                WShop::instance()->WP->wp_die(WShop_Error::error_custom(__('Payment gateway is invalid!',WSHOP)));
	                exit;
	            }
	            
	            if($order->get_total_amount(false)<=0){
	                $order->sn = $order->generate_sn();
	                $error = $order->complete_payment(null);
	                if(!WShop_Error::is_valid($error)){
	                    WShop::instance()->WP->wp_die($error);
	                    exit;
	                }
	                 
	                wp_redirect($order->get_received_url());
	                exit;
	            }
	            
	            $error = $payment_gateway->process_payment($order);
	            if(!WShop_Error::is_valid($error)){
	                WShop::instance()->WP->wp_die($error);
	                exit;
	            }
	             
	            wp_redirect($error->data);
	            exit;
	        case 'is_paid':
	            $params=shortcode_atts(array(
    	            'notice_str'=>null,
    	            'action'=>$action,
    	            $action=>null,
    	            'tab'=>null,
    	            'order_id'=>null,
    	            'hash'=>null
	            ), $request);
	            if(!WShop::instance()->WP->ajax_validate($params, $params['hash'],true)){
	                echo WShop_Error::err_code(701)->to_json();
	                exit;
	            }
	             
	            $order =  WShop::instance()->payment->get_order('id', $params['order_id']);
	            if(!$order){
	                echo WShop_Error::err_code(404)->to_json();
	                exit;
	            }
	            
	            $payment_gateway = $order->get_payment_gateway();
	            $payment_gateway_html = null;
	            if($payment_gateway){
	                switch ($payment_gateway->group){
	                    case 'wechat':
	                        $payment_gateway_html = '<i class="icon weixin"></i> 微信';
	                        break;
	                    case 'alipay':
	                        $payment_gateway_html = '<i class="icon alipay"></i> 支付宝';
	                        break;
	                }
	            }
	             
	            if($order->is_paid()){
	                echo WShop_Error::success(array(
	                    'paid'=>true,
	                    'received_url'=>$order->get_received_url(),
	                    'payment_method'=> $payment_gateway_html
	                ))->to_json();
	                exit;
	            }
	           
                $payments = WShop::instance()->payment->get_payment_gateways();
                if($payments){
                    foreach ($payments as $_payment){
                        if(method_exists($_payment, 'query_order_transaction')){
                            add_filter("query_order_transaction_{$_payment->id}", array($_payment,'query_order_transaction'),10,2);
                        }
                    }
                }
                
                $transaction_id = apply_filters('query_order_transaction', null,$order);
                if(!$transaction_id){
                    $transaction_id = apply_filters("query_order_transaction_{$payment_gateway->id}", $transaction_id,$order);
                }
                
                if($transaction_id){
                    $order->complete_payment($transaction_id);
                    echo WShop_Error::success(array(
                        'paid'=>true,
                        'received_url'=>$order->get_received_url(),
                        'payment_method'=> $payment_gateway_html
                    ))->to_json();
                    exit;
                }
                echo WShop_Error::success(array(
                    'paid'=>false,
                    'received_url'=>$order->get_received_url(),
                    'payment_method'=> $payment_gateway_html
                ))->to_json();
                exit;
	    }
	    
	    self::hook_purchase_shopping_list($request);
	    self::hook_purchase_shopping_cart($request);
	    self::hook_purchase_onestep($request);
	    
	    do_action("wshop_checkout_tab_{$tab}",$request);
	}
	
	private static function hook_purchase_shopping_list($request){
	    $step = isset($request['tab'])?$request['tab']:null;
	    $action = isset($request['action'])?$request['action']:null;
	    switch ($step){
	        case 'purchase_modal_shopping':
	            $datas=shortcode_atts(array(
	                'notice_str'=>null,
	                'action'=>$action,
	                $action=>null,
	                'tab'=>null,
	                'section'=>null,//支付方式：付费下载 ， 快速支付等
	                'hash'=>null
	            ), $request);
	            
	            if(!WShop::instance()->WP->ajax_validate($datas, $datas['hash'])){
	                echo WShop_Error::err_code(701)->to_json();
	                exit;
	            }
	             
	            if(!is_user_logged_in()&&!WShop::instance()->WP->is_enable_guest_purchase()){
                    echo WShop_Error::err_code(501)->to_json();
	                exit;
	            }
	            
	            if(!isset($request['section'])||empty($request['section'])){
	                echo WShop_Error::err_code(600)->to_json();
	                exit;
	            }
	            
	            $cart = WShop_Shopping_Cart::empty_cart(false);
	            if($cart instanceof WShop_Error){
	                echo $cart->to_json();
	                exit;
	            }
	           
	            $cart =    $cart->__add_to_cart(isset($request['post_id'])?$request['post_id']:null);
	            if($cart instanceof WShop_Error){
                    echo $cart->to_json();
                    exit;
                }
	            
	            $cart->__set_metas(array(
	                'section'=>$request['section'],
	                'location'=>isset($request['location'])?$request['location']:null
	            ));
	             
	            $order = $cart->create_order();
	            if($order instanceof WShop_Error){
	                echo $order->to_json();
	                exit;
	            }
	             
	            echo WShop_Error::success(array(
	                'redirect_url'=>$order->get_pay_url()
	            ))->to_json();
	            exit;
	        case 'purchase_modal_shopping_list':
	            $datas=shortcode_atts(array(
    	            'notice_str'=>null,
    	            'action'=>$action,
    	            $action=>null,
    	            'tab'=>null,
    	            'section'=>null,//支付方式：付费下载 ， 快速支付等
    	            'hash'=>null
	            ), $request);
	             
	            if(!WShop::instance()->WP->ajax_validate($datas, $datas['hash'])){
	                echo WShop_Error::err_code(701)->to_json();
	                exit;
	            }
	        
	            if(!is_user_logged_in()&&!WShop::instance()->WP->is_enable_guest_purchase()){
                    echo WShop_Error::err_code(501)->to_json();
	                exit;
	            }
	             
	            if(!isset($request['section'])||empty($request['section'])){
	                echo WShop_Error::err_code(600)->to_json();
	                exit;
	            }
	             
	            $cart = WShop_Shopping_Cart::empty_cart(false);
	            if($cart instanceof WShop_Error){
	                echo $cart->to_json();exit;
	            }
	           
	            $cart =     $cart->__add_to_cart(isset($request['post_id'])?$request['post_id']:null);
	            if($cart instanceof WShop_Error){
                    echo $cart->to_json();
                    exit;
                }
	            
	            $cart->__set_metas(array(
	                'section'=>$request['section'],
	                'location'=>isset($request['location'])?$request['location']:null
	            ));
	            $error = $cart->save_changes();
	            if(!WShop_Error::is_valid($error)){
	                echo $error->to_json();
	                exit;
	            }
	            echo WShop_Error::success(array(
	                'url'=>WShop::instance()->ajax_url(array('action'=>'wshop_checkout_v2', 'tab'=>'purchase_modal_shopping_list_confirm'),true,true)
	            ))->to_json();
	            exit;
            case 'purchase_modal_shopping_list_confirm':
                $datas=shortcode_atts(array(
                    'notice_str'=>null,
                    'action'=>$action,
                    $action=>null,
                    'tab'=>null,
                    'hash'=>null
                ), $request);
            
                if(!WShop::instance()->WP->ajax_validate($datas, $datas['hash'])){
                    echo WShop_Error::err_code(701)->to_json();
                    exit;
                }
                 
                if(!is_user_logged_in()&&!WShop::instance()->WP->is_enable_guest_purchase()){
                    echo WShop_Error::err_code(501)->to_json();
                    exit;
                }
            
                $cart = WShop_Shopping_Cart::get_cart();
                if($cart instanceof WShop_Error){
                    echo $cart->to_json();
                    exit;
                }
            
                $payment_method =isset($request['payment_method'])?$request['payment_method']:null;
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
	}
	
	private static function hook_purchase_shopping_cart($request){
	    $step = isset($request['tab'])?$request['tab']:null;
	    $action = isset($request['action'])?$request['action']:null;
	     
	    switch ($step){
	        case 'purchase_modal_shopping_cart_create_order':
	            $datas=shortcode_atts(array(
    	            'notice_str'=>null,
    	            'action'=>$action,
    	            $action=>null,
    	            'tab'=>null,
    	            'section'=>null,//支付方式：付费下载 ， 快速支付等
    	            'hash'=>null
	            ), $request);
	        
	            if(!WShop::instance()->WP->ajax_validate($datas, $datas['hash'])){
	                echo WShop_Error::err_code(701)->to_json();
	                exit;
	            }
	             
	            if(!is_user_logged_in()&&!WShop::instance()->WP->is_enable_guest_purchase()){
                    echo WShop_Error::err_code(501)->to_json();
	                exit;
	            }
	             
	            $cart = WShop_Shopping_Cart::get_cart();
	            if($cart instanceof WShop_Error){
	                echo $cart->to_json();
	                exit;
	            }
	             
	            $cart->__set_payment_method(isset($request['payment_method'])?$request['payment_method']:null);
	             
	            try {
	                do_action_ref_array('wshop_purchase_modal_shopping_cart_create_order', array(&$cart));
	            } catch (Exception $e) {
	                echo WShop_Error::error_custom($e->getMessage())->to_json();
	                exit;
	            }
	            
	            $order = $cart->create_order();
	            if($order instanceof WShop_Error){
	                echo $order->to_json();
	                exit;
	            }
	        
	            echo WShop_Error::success(array(
	                'redirect_url'=>$order->get_pay_url()
	            ))->to_json();
	             
	            exit;
	        //跳转购物车进行支付
	        case 'purchase_modal_shopping_cart':
	            $datas=shortcode_atts(array(
    	            'notice_str'=>null,
    	            'action'=>$action,
    	            $action=>null,
    	            'tab'=>null,
    	            'section'=>null,//支付方式：付费下载 ， 快速支付等
    	            'hash'=>null
	            ), $request);
	        
	            if(!WShop::instance()->WP->ajax_validate($datas, $datas['hash'])){
	                echo WShop_Error::err_code(701)->to_json();
	                exit;
	            }
	             
	            if(!is_user_logged_in()&&!WShop::instance()->WP->is_enable_guest_purchase()){
                    echo WShop_Error::err_code(501)->to_json();
	                exit;
	            }
	        
	            if(!isset($request['section'])||empty($request['section'])){
	                echo WShop_Error::err_code(600)->to_json();
	                exit;
	            }
	        
	            $cart = WShop_Shopping_Cart::empty_cart(false);
	            if($cart instanceof WShop_Error){
	                echo $cart->to_json();exit;
	            }
	             
	            $cart =     $cart->__add_to_cart(isset($request['post_id'])?$request['post_id']:null);
	            if($cart instanceof WShop_Error){
                    echo $cart->to_json();
                    exit;
                }
	             
	            $cart->__set_metas(array(
	                'section'=>$request['section'],
	                'location'=>isset($request['location'])?$request['location']:null
	            ));
	             
	            $_error = $cart->save_changes();
	            if($_error instanceof WShop_Error){
	                echo $_error->to_json();exit;
	            }
	             
	            echo WShop_Error::success(WShop::instance()->payment->get_order_checkout_url())->to_json();
	            exit;
	    }
	}
	
	
	private static function hook_purchase_onestep($request){
	    $step = isset($request['tab'])?$request['tab']:null;
	    $action = isset($request['action'])?$request['action']:null;
	    
	    switch ($step){
	        case 'purchase_modal_shopping_one_step':
	            $datas=shortcode_atts(array(
    	            'notice_str'=>null,
    	            'action'=>$action,
    	            $action=>null,
    	            'tab'=>null,
    	            'section'=>null,//支付方式：付费下载 ， 快速支付等
    	            'hash'=>null
	            ), $request);
	             
	            if(!WShop::instance()->WP->ajax_validate($datas, $datas['hash'])){
	                echo WShop_Error::err_code(701)->to_json();
	                exit;
	            }
	        
	            if(!is_user_logged_in()&&!WShop::instance()->WP->is_enable_guest_purchase()){
                    echo WShop_Error::err_code(501)->to_json();
	                exit;
	            }
	             
	            if(!isset($request['section'])||empty($request['section'])){
	                echo WShop_Error::err_code(600)->to_json();
	                exit;
	            }
	             
	            $cart = WShop_Shopping_Cart::empty_cart(false);
	            if($cart instanceof WShop_Error){
	                echo $cart->to_json();exit;
	            }
	        
	             $cart =    $cart->__add_to_cart(isset($request['post_id'])?$request['post_id']:null);
	            if($cart instanceof WShop_Error){
                    echo $cart->to_json();
                    exit;
                }
	        
	            $cart->__set_metas(array(
	                'section'=>$request['section'],
	                'location'=>isset($request['location'])?$request['location']:null
	            ));
	             
	            $order =$cart->create_order(null,null,WShop_Order::Unconfirmed);
	            if($order instanceof WShop_Error){
	                echo $order->to_json();
	                exit;
	            }
	             
	            $pay_url = WShop::instance()->ajax_url(array('action'=>'wshop_checkout_v2','tab'=>'purchase_modal_shopping_one_step_confirm_order_v','order_id'=>$order->id),true,true);
	            if(!class_exists('QRcode')){
	                require_once WSHOP_DIR.'/includes/phpqrcode/phpqrcode.php';
	            }
	             
	            $errorCorrectionLevel = 'L'; // 容错级别
	            $matrixPointSize = 9; // 生成图片大小
	        
	            ob_start();
	             
	            QRcode::png($pay_url,false,$errorCorrectionLevel,$matrixPointSize);
	            $imageString = "data:image/png;base64,".base64_encode(ob_get_clean());
	             
	            echo WShop_Error::success(array(
	                //这个参数不能去掉
	                'url'=>WShop::instance()->ajax_url(array(
	                    'action'=>'wshop_checkout_v2',
	                    'tab'=>'purchase_modal_shopping_one_step_confirm_order',
	                    'order_id'=>$order->id,
	                ),true,true),
	                'qrcode_url'=>$imageString,
	                'url_query'=> WShop::instance()->ajax_url(array(
	                    'action'=>$action,
	                    'tab'=>'is_paid',
	                    'order_id'=>$order->id
	                ),true,true),
	                'price_html'=>$order->get_total_amount(true)
	            ))->to_json();
	            exit;
	            
	        case 'purchase_modal_shopping_one_step_confirm_order_v':
	            $error = self::confirm_order($request);
	            if(!WShop_Error::is_valid($error)){
	                WShop::instance()->WP->wp_die($error);
	                exit;
	            }
	            wp_redirect($error->data);
	            exit;
	        case 'purchase_modal_shopping_one_step_confirm_order':
	            echo self::confirm_order($request)->to_json();
	            exit;
	    }
	}
	
	/**
	 * 远程服务
	 */
	public static function service(){
	    if( ! current_user_can( 'manage_options') ){
	        echo (WShop_Error::err_code(501)->to_json());
	        exit;
	    }
	    
	    $action ='wshop_service';
	    $params=shortcode_atts(array(
            'notice_str'=>null,
            'action'=>$action,
            $action=>null,
            'tab'=>null
        ), stripslashes_deep($_REQUEST));
	    
	    if(!WShop::instance()->WP->ajax_validate($params, isset($_REQUEST['hash'])?$_REQUEST['hash']:null,true)){
	        echo (WShop_Error::err_code(701)->to_json());
	        exit;
	    }
	   
	    switch ($params['tab']){

	        //第三方扩展
	        case 'extensions':
	            $page_index = isset($_REQUEST['pageIndex'])?intval($_REQUEST['pageIndex']):1;
	            if($page_index<1){
	                $page_index=1;
	            }
	             
	            $keywords = isset($_REQUEST['keywords'])?sanitize_title_for_query($_REQUEST['keywords']):'';
	             
	            if(empty($keywords)){
	                $info = get_option('wshop-ajax:service:extensions:'.$page_index);
	                if(!$info||!is_array($info)){
	                    $info = array();
	                }
	                
	                if(isset($info['last_cache_time'])&&$info['last_cache_time']>time()){
	                    echo WShop_Error::success($info)->to_json();
	                    exit;
	                }
	            }
	            
	            $api ='https://www.wpweixin.net/wp-content/plugins/xh-hash/api-v3.php';
	            $params = array();
	            
	            $params['pageIndex']=$page_index;
	            $params['keywords']=$keywords;
	            $params['action']='extensions';
	            $params['license_id'] =WShop::$license_id[0];
	            
	            $request =wp_remote_post($api,array(
	                'timeout'=>10,
	                'body'=>$params
	            ));
	             
	            if(is_wp_error( $request )){
	                echo (WShop_Error::err_code(1000)->to_json());
	                exit;
	            }
	      
	            $info = json_decode( wp_remote_retrieve_body( $request ) ,true);
	            if(!$info||!is_array($info)){
	                echo (WShop_Error::err_code(1000)->to_json());
	                exit;
	            } 
	            if(empty($keywords)){
    	            $info['last_cache_time'] =time()+24*60*60;
    	            wp_cache_delete('wshop-ajax:service:extensions:'.$page_index,'options');
    	            update_option('wshop-ajax:service:extensions:'.$page_index,$info,false);
	            }
	            echo (WShop_Error::success($info)->to_json());

	            exit;
	        case 'plugins':
	            $page_index = isset($_REQUEST['pageIndex'])?intval($_REQUEST['pageIndex']):1;
	            if($page_index<1){
	                $page_index=1;
	            }
	            $category_id=isset($_REQUEST['category_id'])?intval($_REQUEST['category_id']):0;
	            $keywords = isset($_REQUEST['keywords'])?sanitize_title_for_query($_REQUEST['keywords']):'';
	            if(empty($keywords)){
	                $info = get_option("wshop-ajax:service:plugins:{$category_id}:{$page_index}");
	                if(!$info||!is_array($info)){
	                    $info = array();
	                }
	                 
	                if(isset($info['last_cache_time'])&&$info['last_cache_time']>time()){
	                    echo WShop_Error::success($info)->to_json();
	                    exit;
	                }
	            }
	            $api ='https://www.wpweixin.net/wp-content/plugins/xh-hash/api-v3.php';
	            $params = array();
	             
	            $params['pageIndex']=$page_index;
	            $params['keywords']=$keywords;
	            $params['action']='plugins';
	            $params['category_id'] =$category_id;
	            
	            $request =wp_remote_post($api,array(
	                'timeout'=>10,
	                'body'=>$params
	            ));
	            
	            if(is_wp_error( $request )){
	                echo (WShop_Error::err_code(1000)->to_json());
	                exit;
	            }
	            
	            $info = json_decode( wp_remote_retrieve_body( $request ) ,true);
	            if(!$info||!is_array($info)){
	                echo (WShop_Error::err_code(1000)->to_json());
	                exit;
	            }
	            if(empty($keywords)){
    	            $info['last_cache_time'] =time()+24*60*60;
    	            wp_cache_delete("wshop-ajax:service:plugins:{$category_id}:{$page_index}",'options');
    	            update_option("wshop-ajax:service:plugins:{$category_id}:{$page_index}",$info,false);
	            }
	            echo (WShop_Error::success($info)->to_json());
	            
	            exit;
	    }
	}

	/**
	 * 管理员对插件的操作
	 */
	public static function plugin(){
	    if( ! current_user_can( 'manage_options') ){
	        echo (WShop_Error::err_code(501)->to_json());
	        exit;
	    }
	    
	    $action='wshop_plugin';
	  
	    $params=shortcode_atts(array(
	        'notice_str'=>null,
	        'action'=>$action,
	        $action=>null,
	        'plugin_id'=>null,
	        'tab'=>null
	    ), stripslashes_deep($_REQUEST));
	    if(!WShop::instance()->WP->ajax_validate($params, isset($_REQUEST['hash'])?$_REQUEST['hash']:null,true)){
	        echo (WShop_Error::err_code(701)->to_json());
	        exit;
	    }
	    
	    $plugins =WShop::instance()->WP->get_plugin_list_from_system();
	    if(!$plugins){
	        echo (WShop_Error::err_code(404)->to_json());
	        exit;
	    }
	    
	    $add_on =null;
	    $add_on_file='';
	    foreach ($plugins as $file=>$plugin){
	        if($plugin->id==$params['plugin_id']){
	            $add_on_file = $file;
	            $add_on=$plugin;
	            break;
	        }
	    }
	    
	    $base_dir =  str_replace("\\", "/", WP_CONTENT_DIR);
	    $add_on_file_format = str_replace($base_dir, '[base_dir]', $add_on_file);
	    
        if(!$add_on){
            echo (WShop_Error::err_code(404)->to_json());
            exit;
        }
       
	    $cache_time = 2*60*60; 
	    switch ($params['tab']){
	        //插件安装
	        case 'install':
	            $installed = get_option('wshop_plugins_installed',array());
	            if(!$installed||!is_array($installed)){
	                $installed =array();
	            }
	            $has = false;
	            foreach ($installed as $item){
	                if($item==$add_on_file_format){
	                    $has=true;break;
	                }
	            }
	           
	            if(!$has){
	                $installed[]=$add_on_file_format;
	                
	                try {
	                    if($add_on->depends){
	                        foreach ($add_on->depends as $id=> $depend){
	                           $contains = false;
	                           foreach (WShop::instance()->plugins as $plugin){
	                               if(!$plugin->is_active){
	                                   continue;
	                               }
	                               
	                               if($plugin->id==$id){
	                                   $contains=true;
	                                   break;
	                               }
	                           }
	                           
	                           if(!$contains){//依赖第三方插件
	                               echo (WShop_Error::error_custom(sprintf(__('Current add-on is relies on %s!',WSHOP),"“{$depend['title']}”"))->to_json());
	                               exit;
	                           }
	                        }
	                    }
	                    
	                    if(!empty($add_on->min_core_version)){
    	                    if(version_compare(WShop::instance()->version,$add_on->min_core_version, '<')){
    	                        echo (WShop_Error::error_custom(sprintf(__('Core version must greater than or equal to %s!',WSHOP),$add_on->min_core_version))->to_json());
    	                        exit;
    	                    }
	                    }
	                    
	                    WShop::instance()->__load_plugin($add_on);	                
	                    $add_on->on_install(); 
	                    
	                    ini_set('memory_limit','128M');
	                    do_action('wshop_flush_rewrite_rules');
                        flush_rewrite_rules();
	                } catch (Exception $e) {
	                    echo (WShop_Error::error_custom($e->getMessage())->to_json());
	                    exit;
	                }
	               
	            }
	           
	            $plugins_find = WShop::instance()->WP->get_plugin_list_from_system();
	            if(!$plugins_find||!is_array($plugins_find)){
	                $plugins_find=array();
	            }
	             
	            $options = array();
	            foreach ($installed as $item){
	                $has = false;
	                foreach ($plugins_find as $file=>$plugin){
	                    if($item==str_replace($base_dir, '[base_dir]', $file)){
	                        $has =true;
	                        break;
	                    }
	                }
	                if($has){
	                    $options[]=str_replace($base_dir, '[base_dir]', $item);
	                }
	            }
	            
	           wp_cache_delete("wshop_plugins_installed",'options');
	           update_option('wshop_plugins_installed', $options,true);
	           
	           echo (WShop_Error::success()->to_json());
	           exit;
	        //插件卸载   
	        case 'uninstall':
	            $installed = get_option('wshop_plugins_installed',array());
	         
	            if(!$installed||!is_array($installed)){
	                $installed =array();
	            }
	            
	            $new_values = array();
	            foreach ($installed as $item){
	                if($item!=$add_on_file_format){
	                    $new_values[]=$item;
	                }
	            }
	           
	            try {
	                foreach (WShop::instance()->plugins as $plugin){
	                    if(!$plugin->is_active){
	                        continue;
	                    }
	                    
	                    if(!$plugin->depends){
	                        continue;
	                    }
	                    
	                    foreach ($plugin->depends as $id=>$depend){
	                        if($id==$add_on->id){
	                            echo (WShop_Error::error_custom(sprintf(__('"%s" is relies on current add-on!',WSHOP),"“{$plugin->title}”"))->to_json());
	                            exit;
	                        }
	                    }
	                }
	                
	                $add_on->on_uninstall();
	            } catch (Exception $e) {
	                echo (WShop_Error::error_custom($e)->to_json());
	                exit;
	            }
	            
	            $plugins_find = WShop::instance()->WP->get_plugin_list_from_system();
	            if(!$plugins_find||!is_array($plugins_find)){
	                $plugins_find=array();
	            }
	            
	            $options = array();
	            foreach ($new_values as $item){
	                $has = false;
	                foreach ($plugins_find as $file=>$plugin){
	                    if($item==str_replace($base_dir, '[base_dir]', $file)){
	                        $has =true;
	                        break;
	                    }
	                }
	                if($has){
	                    $options[]=str_replace($base_dir, '[base_dir]', $item);
	                }
	            }
	            
	            wp_cache_delete('wshop_plugins_installed', 'options');
	            $update =update_option('wshop_plugins_installed', $options,true);
	            echo (WShop_Error::success()->to_json());
	            exit;
	        //插件更新
	        case 'update':
	        case 'update_admin_options':
	        case 'update_plugin_list':
	           $info =get_option("wshop-ajax:plugin:update:{$add_on->id}");
	           if(!$info||!is_array($info)){
	               $info=array();
	           }
	           
	           if(!isset($info['_last_cache_time'])||$info['_last_cache_time']<time()){
	               $api ='https://www.wpweixin.net/wp-content/plugins/xh-hash/api-add-ons.php';
	               $request_data = array(
	                   'l'=>$add_on->id,
	                   's'=>get_option('siteurl'),
	                   'v'=>$add_on->version,
	                   'a'=>'update'
	               );
	               //插件为非授权插件
	               $license =null;
	                $info =WShop_Install::instance()->get_plugin_options();
	                if($info){
	                    if(isset($info[$add_on->id])){
	                        $license=$info[$add_on->id];
	                    }
	                    
	                    if(empty($license)){
	                        $license = isset($info['license'])?$info['license']:null;
	                    }
	                }
	                if(empty($license)){
	                    echo WShop_Error::error_unknow()->to_json();
	                    exit;
	                }
	                
	               $request_data['c']=$license;
	                
	               $request =wp_remote_post($api,array(
	                   'timeout'=>10,
	                   'body'=>$request_data
	               ));
	              
	               if(is_wp_error( $request )){
	                   echo (WShop_Error::error_custom($request)->to_json());
	                   exit;
	               }
	               
	               $info = json_decode( wp_remote_retrieve_body( $request ) ,true);
	               if(!$info||!is_array($info)){
	                   echo (WShop_Error::error_unknow()->to_json());
	                   exit;
	               }
	               
	               //缓存30分钟
	               $info['_last_cache_time'] = time()+$cache_time;
	               update_option("wshop-ajax:plugin:update:{$add_on->id}", $info,false);
	           }
	            
	           $msg =WShop_Error::success();
	           switch($params['tab']){
	               case 'update_admin_options':
	                   $txt =sprintf(__('There is a new version of %s - %s. <a href="%s" target="_blank">View version %s details</a> or <a href="%s" target="_blank">download now</a>.',WSHOP),
	                       $info['name'],
	                       $info['upgrade_notice'],
	                       $info['homepage'],
	                       $info['version'],
	                       $info['download_link']
	                       );
	                   $msg = new WShop_Error(0, version_compare($add_on->version,  $info['version'],'<')?$txt:'');
	                   break;
	               case 'update_plugin_list':
	                   $txt =sprintf(__('<tr class="plugin-update-tr active">
	                       <td colspan="3" class="plugin-update colspanchange">
	                       <div class="notice inline notice-warning notice-alt">
	                       <p>There is a new version of %s available.<a href="%s"> View version %s details</a> or <a href="%s" class="update-link">download now</a>.</p>
	                       <div class="">%s</div>
	                       </div></td></tr>',WSHOP),
	                       $info['name'],
	                       $info['homepage'],
	                       $info['version'],
	                       $info['download_link'],
	                       $info['upgrade_notice']
	                   );
	                   $msg = new WShop_Error(0, version_compare($add_on->version,  $info['version'],'<')?$txt:'');
	                   break; 
	           }
	           
	           echo $msg->to_json();
	           exit;
	    }
	}
}
