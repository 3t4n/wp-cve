<?php

if (!defined('ABSPATH'))
    exit (); // Exit if accessed directly

require_once 'includes/class-wshop-download.php';

/**
 * @author rain
 *
 */
class WShop_Add_On_Download extends Abstract_WShop_Add_Ons {
    /**
     * The single instance of the class.
     *
     * @since 1.0.0
     * @var WShop_Add_On_Download
     */
    private static $_instance = null;

    /**
     * 插件跟路径url
     * @var string
     * @since 1.0.0
     */
    public $domain_url;
    public $domain_dir;
    /**
     * Main Social Instance.
     *
     * @since 1.0.0
     * @static
     * @return WShop_Add_On_Download
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        $this->id = 'wshop_add_ons_download';
        $this->title = __('Pay per download', WSHOP);
        $this->description = '将下载地址插入文章，付费后才能看到下载内容';
        $this->version = '1.0.0';
        $this->min_core_version = '1.0.0';
        $this->author = __('xunhuweb', WSHOP);
        $this->author_uri = 'https://www.wpweixin.net';
        $this->domain_url = WShop_Helper_Uri::wp_url(__FILE__);
        $this->domain_dir = WShop_Helper_Uri::wp_dir(__FILE__);

        $this->init_form_fields();
    }

    public function init_form_fields() {
        $fields = array(
            'post_types' => array(
                'title' => __('Bind post types', WSHOP),
                'type' => 'multiselect',
                'func' => true,
                'options' => array($this, 'get_post_type_options')
            )
        );

        $this->form_fields = apply_filters('wshop_download_fields', $fields);
    }

    public function wshop_online_post_types($post_types) {
        $types = $this->get_option('post_types');

        if ($types) {
            foreach ($types as $type) {
                if (!in_array($type, $post_types)) {
                    $post_types[] = $type;
                }
            }
        }

        return $post_types;
    }

    public function get_download_types() {
        return apply_filters('wshop_download_types', array(
            'general' => array(
                'title' => '简化',
                'call' => function ($field, $download) {
                    $content = $download->downloads['type'] == 'general' ? $download->downloads['content'] : [];
                    if ($content) {
                        $content = json_decode($content,true);
                    }else{
                        $content = [
                        	'type'=>'百度网盘',
                        	'type1'=>'',
                        	'type2'=>'',
                            'url'=>'https://pan.baidu.com',
                            'code'=>'XXXX',
                            'url1'=>'',
                            'code1'=>'',
                            'url2'=>'',
                            'code2'=>''
                        ];
                    }
                    ?>
                    <label>下载名称</label>
                    <input type="text" class="input-text" id="<?php echo $field; ?>_download_type" value="<?php echo esc_attr($content['type'])?>" placeholder="百度网盘" style="width: 100px"/>
                    <label>下载链接</label>
                    <input type="text" class="input-text" id="<?php echo $field; ?>_download_url" value="<?php echo esc_attr($content['url'])?>" placeholder="必填" style="min-width: 300px;max-width: 400px;"/>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <label>提取码</label>
                    <input type="text" class="input-text" id="<?php echo $field; ?>_download_code" value="<?php echo esc_attr($content['code'])?>" placeholder="选填" style="max-width: 50px;"/>
                    <br /><br />
                    <label>下载名称</label>
                    <input type="text" class="input-text" id="<?php echo $field; ?>_download_type1" value="<?php echo esc_attr($content['type1'])?>" placeholder="百度网盘" style="width: 100px"/>
                    <label>下载链接</label>
                    <input type="text" class="input-text" id="<?php echo $field; ?>_download_url1" value="<?php echo esc_attr($content['url1'])?>" placeholder="选填" style="min-width: 300px;max-width: 400px;"/>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <label>提取码</label>
                    <input type="text" class="input-text" id="<?php echo $field; ?>_download_code1" value="<?php echo esc_attr($content['code1'])?>" placeholder="选填" style="max-width: 50px;"/>
                    <br /><br />
                    <label>下载名称</label>
                    <input type="text" class="input-text" id="<?php echo $field; ?>_download_type2" value="<?php echo esc_attr($content['type2'])?>" placeholder="百度网盘" style="width: 100px"/>
                    <label>下载链接</label>
                    <input type="text" class="input-text" id="<?php echo $field; ?>_download_url2" value="<?php echo esc_attr($content['url2'])?>" placeholder="选填" style="min-width: 300px;max-width: 400px;"/>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <label>提取码</label>
                    <input type="text" class="input-text" id="<?php echo $field; ?>_download_code2" value="<?php echo esc_attr($content['code2'])?>" placeholder="选填" style="max-width: 50px;"/>
                    <input type="hidden" id="<?php echo $field; ?>" name="<?php echo $field; ?>" value="<?php echo esc_attr(json_encode($content));?>" />
                    <script type="text/javascript">
                        (function ($) {
                            var content={url:"<?php echo esc_attr($content['url'])?>",code:"<?php echo esc_attr($content['code'])?>"
		                            	,url1:"<?php echo esc_attr($content['url1'])?>",code1:"<?php echo esc_attr($content['code1'])?>"
		                            	,url2:"<?php echo esc_attr($content['url2'])?>",code2:"<?php echo esc_attr($content['code2'])?>",type:"<?php echo esc_attr($content['type'])?>",type1:"<?php echo esc_attr($content['type1'])?>",type2:"<?php echo esc_attr($content['type2'])?>"
		                            };
                            $("#<?php echo $field; ?>_download_url").keyup(function () {
                                content['url']=$(this).val();
                                content['code']=$("#<?php echo $field; ?>_download_code").val();
                                content['type']=$("#<?php echo $field; ?>_download_type").val();
                                $("#<?php echo $field; ?>").val(JSON.stringify(content));
                            });

                            $("#<?php echo $field; ?>_download_code").keyup(function () {
                                content['url']=$("#<?php echo $field; ?>_download_url").val();
                                content['code']=$(this).val();
                                content['type']=$("#<?php echo $field; ?>_download_type").val();
                                $("#<?php echo $field; ?>").val(JSON.stringify(content));
                            });
                            
                            $("#<?php echo $field; ?>_download_type").keyup(function () {
                                content['url']=$("#<?php echo $field; ?>_download_url").val();
                                content['code']=$("#<?php echo $field; ?>_download_code").val();
                                content['type']=$(this).val();
                                $("#<?php echo $field; ?>").val(JSON.stringify(content));
                            });
                            
                            $("#<?php echo $field; ?>_download_url1").keyup(function () {
                                content['url1']=$(this).val();
                                content['code1']=$("#<?php echo $field; ?>_download_code1").val();
                                 content['type1']=$("#<?php echo $field; ?>_download_type1").val();
                                $("#<?php echo $field; ?>").val(JSON.stringify(content));
                            });

                            $("#<?php echo $field; ?>_download_code1").keyup(function () {
                                content['url1']=$("#<?php echo $field; ?>_download_url1").val();
                                content['code1']=$(this).val();
                                content['type1']=$("#<?php echo $field; ?>_download_type1").val();
                                $("#<?php echo $field; ?>").val(JSON.stringify(content));
                            });
                            
                            $("#<?php echo $field; ?>_download_type1").keyup(function () {
                                content['url1']=$("#<?php echo $field; ?>_download_url1").val();
                                content['code1']=$("#<?php echo $field; ?>_download_code1").val();
                                content['type1']=$(this).val();
                                $("#<?php echo $field; ?>").val(JSON.stringify(content));
                            });
                            
                            $("#<?php echo $field; ?>_download_url2").keyup(function () {
                                content['url2']=$(this).val();
                                content['code2']=$("#<?php echo $field; ?>_download_code2").val();
                                content['type2']=$("#<?php echo $field; ?>_download_type2").val();
                                $("#<?php echo $field; ?>").val(JSON.stringify(content));
                            });

                            $("#<?php echo $field; ?>_download_code2").keyup(function () {
                                content['url2']=$("#<?php echo $field; ?>_download_url2").val();
                                content['code2']=$(this).val();
                                content['type2']=$("#<?php echo $field; ?>_download_type2").val();
                                $("#<?php echo $field; ?>").val(JSON.stringify(content));
                            });
                            
                            $("#<?php echo $field; ?>_download_type2").keyup(function () {
                                content['url2']=$("#<?php echo $field; ?>_download_url2").val();
                                content['code2']=$("#<?php echo $field; ?>_download_code2").val();
                                content['type2']=$(this).val();
                                $("#<?php echo $field; ?>").val(JSON.stringify(content));
                            });
                            
                        })(jQuery);
                    </script>

                    <?php
                },
                'render' => function ($download) {
                    echo WShop::instance()->WP->requires($this->domain_dir, 'download/output_download_html.php',[
                        'type'=>$download->downloads['type'],
                        'content'=>$download->downloads['content']
                    ]);
                }
            ),

			    'simple' => array(
                'title' => 'Html',
                'call' => function ($field, $download) {
                    $content = $download->downloads['type'] == 'simple' ? $download->downloads['content'] : '';
                    if (empty($content)) {
                        $_content='把下载地址的html代码，复制粘贴在此次，然后短码插入文章';
                        $content = apply_filters('wshop_download_default', $_content);
                    }
                    ?>
                    <textarea rows="6" cols="20" class="input-text wide-input " name="<?php echo $field; ?>"
                              style="min-width:600px;"><?php echo esc_textarea($content) ?></textarea>
                    <?php
                },
                'render' => function ($download) {
                    echo WShop::instance()->WP->requires($this->domain_dir, 'download/output_download_html.php',[
                        'type'=>$download->downloads['type'],
                        'content'=>$download->downloads['content']
                    ]);
                }
            )


        ));
    }

    public function on_install() {
        $model = new WShop_Download_Model();
        $model->init();
    }

    /**
     *
     * {@inheritDoc}
     * @see Abstract_WShop_Add_Ons::on_load()
     */
    public function on_load() {
        $o = $this;
        add_filter('wshop_order_download_received_url', array($o, 'wshop_order_received_url'), 10, 2);
        add_filter('wshop_admin_menu_menu_default_modal', function ($menus) {
            $menus[] = WShop_Add_On_Download::instance();
            return $menus;
        }, 12, 1);

        WShop_Async::instance()->async('wshop_downloads', array($o, 'wshop_downloads'));

        //将短码wshop_download_link生成内容
        add_shortcode('wshop_download_link',array($o, 'wshop_download_link'));
        

        add_filter('wshop_online_post_types', array($o, 'wshop_online_post_types'));

        add_filter("wshop_order_download_email_received", array($this, 'wshop_email_order_received'), 10, 2);

    }

    public function on_after_init() {
        WShop_Download_Field::instance();
    }

    /**
     *
     * {@inheritDoc}
     * @see Abstract_WShop_Add_Ons::on_init()
     */
    public function on_init() {
        $o = $this;

        $o->setting_uris = array(
            'settings' => array(
                'title' => __('Settings', WSHOP),
                'url' => admin_url('admin.php?page=wshop_page_default&section=menu_default_modal&sub=wshop_add_ons_download')
            )
        );

        //判断VIP扩展是否启用，若启用则添加每天限制下载次数
        if(WShop::instance()->get_available_addon('wshop_add_ons_membership')){
            add_filter("wshop_membership_fields", function ($fields){
                $fields['download_count']=[
                    'title'=>'限制下载次数',
                    'type'=>'text',
                    'default'=>'10d',
                    'description'=>'即：10d 每天限制下载10次，10w 每周限制下载10次，10m 每月限制下载10次。若留空则不限制下载次数'
                ];
                return $fields;
            });
        }
    }

    /**
     *
     * @param unknown $call
     * @param WShop_Order $order
     * @return WShop_Add_On_Download[]|string[]
     */
    public function wshop_email_order_received($call, $order) {
        return array(
            function ($order) {
                $user_email = $order->get_email_receiver();

                $settings = array(
                    '{email:customer}' => $user_email,
                    '{order_number}' => $order->id,
                    '{order_date}' => date('Y-m-d H:i', $order->paid_date)
                );

                $content = WShop::instance()->WP->requires(
                    WShop_Add_On_Download::instance()->domain_dir,
                    "download/emails/order-received.php",
                    array('order' => $order)
                );

                $email = new WShop_Email('order-received');
                return $email->send($settings, $content);
            }
        );
    }

    public function wshop_order_received_url($url, $order) {
        $location = isset($order->metas['location']) && !empty($order->metas['location']) ? esc_url_raw($order->metas['location']) : null;
        if (!empty($location)) {
            return $location;
        }

        return $url;
    }


    public function wshop_downloads($atts = array(), $content = null) {
        return WShop_Async::instance()->async_call('wshop_downloads', function (&$atts, &$content) {
            if (!is_array($atts)) {
                $atts = array();
            }

            if (!isset($atts['post_id']) || empty($atts['post_id'])) {
                if (method_exists(WShop::instance()->WP, 'get_default_post')) {
                    $default_post = WShop::instance()->WP->get_default_post();
                    $atts['post_id'] = $default_post ? $default_post->ID : 0;
                } else {
                    global $wp_query, $post;
                    $default_post = $wp_query ? $wp_query->post : null;
                    if (!$default_post && $post) {
                        $default_post = $post;
                    }
                    $atts['post_id'] = $default_post ? $default_post->ID : 0;
                }
            }

            if (!isset($atts['location']) || empty($atts['location'])) {
                $atts['location'] = WShop_Helper_Uri::get_location_uri();
            }

        }, function (&$atts, &$content) {
            $atts['section'] = 'download';
            return WShop::instance()->WP->requires(WShop_Add_On_Download::instance()->domain_dir, 'download/button-purchase.php', array(
                'content' => $content,
                'atts' => $atts
            ));
        },
            array(
                'style' => null,
                'post_id' => 0,
                'roles' => null,//admin1,admin2  or  all |null
                'class' => 'xh-btn xh-btn-danger xh-btn-sm',
                'location' => null
            ),
            $atts,
            $content);
    }

    public function wshop_download_link($atts = array(), $content = null) {
        if(!$content) return null;
        //判断meta表是否有这个链接，没有则添加
        if(isset($atts['post_id'])&&get_post($atts['post_id'])){
            $post_ID=$atts['post_id'];
        }else{
            $post = WShop::instance()->WP->get_default_post();
            if(!$post)return null;
            $post_ID=$post->ID;
        }
        $download_link=get_post_meta($post_ID,'wshop_download_link',true);
        if(!$download_link||$download_link!=$content){
            update_post_meta($post_ID,'wshop_download_link',$content);
        }
        //返回下载点击按钮
        $context = WShop_Helper::generate_unique_id();
        add_post_meta($post_ID, 'link_'.$context, $atts['link'], true);
        ob_start();
        ?>
        <button class="xunhu-btn xunhu-btn-warning" id="wshop_download_link_<?php echo $context;?>" data-clipboard-text="<?php echo $atts['code'] ?>"><?php echo $atts['type'] ?></button>
        <?php if($atts['code']!='xx'){?>
        <script src="<?php echo WSHOP_URL?>/assets/js/clipboard.min.js"></script>
        <script type="text/javascript">
        	var clipboard=new ClipboardJS('#wshop_download_link_<?php echo $context?>');
        	clipboard.on('success',function(e){
        		alert('提取码已复制');
        		let url="<?php echo esc_url_raw(WShop::instance()->ajax_url(array('action' => 'wshop_'.$this->id, 'tab' => 'download_link'), true, true))?>";
                    let data={post_ID:<?php echo $post_ID;?>,context:'<?php echo $context;?>'};
                    //var newWindow = window.open();
                    jQuery.ajax({
                        type:'post',
                        url:url,
                        cache: false,
                        data:data,
                        dataType:'json',
                        success:function(info){
                            if(typeof info==='string')info=JSON.parse(info);
                            if(info.code==='00000'){
                                 location.href=info.data.download_link;//本窗口打开
                                 //window.open(info.data.download_link,'top');//新窗口打开
                                 //newWindow.location.href = info.data.download_link;
                            }else {
                                alert(info.msg);
                            }
                        },
                        error:function(){
                            alert('获取下载链接失败');
                        }
                    });
        	});
        </script>
        <?php }else{ ?>
        	<script type="text/javascript">
            (function ($) {
                $("#wshop_download_link_<?php echo $context;?>").click(function () {
                    let url="<?php echo esc_url_raw(WShop::instance()->ajax_url(array('action' => 'wshop_'.$this->id, 'tab' => 'download_link'), true, true))?>";
                    let data={post_ID:<?php echo $post_ID;?>,context:'<?php echo $context;?>'};
                    //var newWindow = window.open();
                    $.ajax({
                        type:'post',
                        url:url,
                        cache: false,
                        data:data,
                        dataType:'json',
                        success:function(info){
                            if(typeof info==='string')info=JSON.parse(info);
                            if(info.code==='00000'){
                                 location.href=info.data.download_link;//本窗口打开
                                 //window.open(info.data.download_link,'top');//新窗口打开
                                 //newWindow.location.href = info.data.download_link;
                            }else {
                                alert(info.msg);
                            }
                        },
                        error:function(){
                            alert('获取下载链接失败');
                        }
                    });
                });
            })(jQuery);
        </script>
        <?php }
        return ob_get_clean();
    }

    public function do_ajax(){
        $action ="wshop_{$this->id}";
        $datas=WShop_Async::instance()->shortcode_atts(array(
            'notice_str'=>null,
            'action'=>$action,
            $action=>null,
            'tab'=>null
        ), stripslashes_deep($_REQUEST));
        switch ($datas['tab']){
            case 'download_link':
                $info=[
                    'code'=>'99999',
                    'msg'=>'获取下载连接错误',
                    'data'=>[]
                ];
                //判断是否会员
                $this->check_download_count();
                $download_link=get_post_meta($_REQUEST['post_ID'],'link_'.$_REQUEST['context'],true);
                if($download_link){
                    $info['code']='00000';
                    $info['msg']='获取成功';
                    $info['data']['download_link']=$download_link;
                }
                echo json_encode($info);
                exit;
        }
    }

    //判断是否会员
    //是会员则验证下载次数是否以满足当前会员等级下载限制次数，超过返回false；反之，新增一条点击下载记录并返回true
    public function check_download_count($type = ""){
        global $current_user;
        if(!class_exists('WShop_Membership_Item')) return true;
        $member=new WShop_Membership_Item($current_user->ID);
        if($member->is_member()){
            $membership=new WShop_Membership($member->membership_id);
            $download_count=$membership->download_count;
            if($download_count){
                $count=substr($download_count, 0,-1);
                $mark=substr($download_count, -1);
                $time=0;
                $mark_txt='';
                date_default_timezone_set("PRC");
                switch ($mark){
                    case 'd':
                        //获取当天开始时间戳
                        $time=mktime(0,0,0,date('m'),date('d'),date('Y'));
                        $mark_txt='今日';
                        break;
                    case 'w':
                        //获取当周开始时间戳
                        $time=(floor(time()/86400)-(date("w")?date("w"):7)+1)*86400-3600*8;
                        $mark_txt='本周';
                        break;
                    case 'm':
                        //获取当月开始时间戳
                        $time=mktime(0,0,0,date('m'),1,date('Y'));
                        $mark_txt='本月';
                        break;
                }
                global $wpdb;
                $sql="select count(*) as num from `{$wpdb->prefix}wshop_download_log` where user_id={$current_user->ID} and create_time>={$time};";
                $_count=$wpdb->get_var($sql);
                if($type=='download_frequency'){
                	$d_count=$count-$_count;
                	 $d_info=array(
                	 	'data'=>$d_count,
                	 	'msg'=>$mark_txt.'下载次数还剩'.$d_count.'次'
                	 	);
                	 return $d_info;
                }
                if($_count>=$count){
                    echo json_encode([
                        'code'=>'99999',
                        'msg'=>$mark_txt.'下载次数已用完，请升级更高级会员提升下载次数。',
                        'data'=>[]
                    ]);
                    exit;
                }
                $wpdb->insert($wpdb->prefix.'wshop_download_log', array('user_id'=>$current_user->ID,'create_time'=>time()));
                return true;
            }
        }else{
            return true;
        }
    }
}

if (!function_exists('wshop_downloads')) {
    function wshop_downloads($atts = array(), $content = null, $echo = true) {
        $html = WShop_Add_On_Download::instance()->wshop_downloads($atts, $content);
        if ($echo) {
            echo $html;
        } else {
            return $html;
        }
    }
}

return WShop_Add_On_Download::instance();
?>
