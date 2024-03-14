<?php

/**
 * Author: wbolt team
 * Author URI: https://www.wbolt.com
 */

class OCW_Front
{
  public function __construct()
  {
  }

  public static function init()
  {
    if (!is_admin()) {
      self::front_handle();
    }
    add_action('wp_ajax_wb_ocw_api', array(__CLASS__, 'ajax_handler'));
    add_action('wp_ajax_nopriv_wb_ocw_api', array(__CLASS__, 'ajax_handler'));
    self::init_member();
  }

  public static function init_member()
  {
    if (!class_exists('WBMember')) {
      require_once ONLINE_CONTACT_WIDGET_PATH . '/wbm/wbm.php';
      WBMember::init();
    }

    // 菜单
    add_filter('wbm_menu', function ($menu) {
      $menu['ocw'] = [
        'url' => home_url('?wbp=member&slug=ocw'),
        'name' => '我的咨询',
        'sort' => 10,
      ];
      return $menu;
    });

    // head引入js、css
    add_action('wbm_head_ocw', function () {
      $wbm_url = WBMember::wbm_url();
      wp_enqueue_script('wbm-ocw', $wbm_url . 'wbm/assets/js/ocw.js', [], true);
      wp_enqueue_style('wbm-ocw', $wbm_url . 'wbm/assets/css/ocw.css');
    });

    // footer 引入 js 、css
    // add_action('wbm_footer_ocw', function () {
    // });

    add_action('wbm_content_ocw', function () {
      echo '<div id="wbm-ocw"></div>';
    });
  }

  public static function load_wbm()
  {
  }

  public static function wbm_version()
  {
    $file = ONLINE_CONTACT_WIDGET_PATH . '/wbm/wbm.php';
    if (!file_exists($file)) {
      return 0;
    }
    $content = file_get_contents(ONLINE_CONTACT_WIDGET_PATH . '/wbm/wbm.php');
    if (!preg_match('#static \$version = ([^\s;]+)#', $content, $match)) {
      return 0;
    }
    return $match[1];
  }



  public static function front_handle()
  {
    $is_pro = get_option('wb_ocw_ver', 0);

    if ($is_pro && !self::current_pages_active()) {
      return;
    }
    if ($is_pro && !self::device_active()) {
      return;
    }

    add_action('wp_footer', array(__CLASS__, 'render_html'));
    add_action('wp_enqueue_scripts', array(__CLASS__, 'inset_assets'), 50);
    add_action('wp_enqueue_scripts', array(__CLASS__, 'get_custom_code'));
  }

  /**
   * 页面过滤处理
   */
  public static function current_pages_active()
  {
    $active_mode = OCW_Admin::opt('active_page');
    $current_url = home_url(add_query_arg(array()));
    $appoint_urls = OCW_Admin::opt('appoint_urls');
    $exception_urls = OCW_Admin::opt('exception_urls', array());

    if ($active_mode == 1) {
      return in_array($current_url, explode(',', $appoint_urls));
    } elseif ($active_mode == 2) {
      return !in_array($current_url, explode(',', $exception_urls));
    }

    return true;
  }

  /**
   * 设备过滤
   */
  public static function device_active()
  {
    $active_device = OCW_Admin::opt('active_device');

    if (empty($active_device)) {
      return false;
    }

    if (wp_is_mobile() && !in_array('1', $active_device)) {
      return false;
    }

    if (!wp_is_mobile() && !in_array('0', $active_device)) {
      return false;
    }

    return true;
  }

  public static function inset_assets()
  {
    wp_enqueue_style('wb-ocw-css', ONLINE_CONTACT_WIDGET_URL . 'assets/wbp_contact.css', array(), ONLINE_CONTACT_WIDGET_VERSION);
    wp_add_inline_style('wb-ocw-css', self::get_custom_code());

    wp_enqueue_script('wb-ocw', ONLINE_CONTACT_WIDGET_URL . 'assets/wbp_front.js', array(), ONLINE_CONTACT_WIDGET_VERSION, true);

    // 验证码
    $opt_msg_captcha = OCW_Admin::opt('items_data.msg.captcha');
    $captcha_type = $opt_msg_captcha['type'];
    if ($captcha_type == 'google') {
      wp_enqueue_script('gg-recaptcha', 'https://www.recaptcha.net/recaptcha/api.js?render=' . $opt_msg_captcha['google']['public'], false, null, false);
    }

    $opt = OCW_Admin::opt();
    $is_pro = get_option('wb_ocw_ver', 0);
    $wb_cnf = array(
      '_wb_ocw_ajax_nonce' => wp_create_nonce('wb_ocw_front_ajax'),
      'ajax_url' => admin_url('admin-ajax.php'),
      'pd_version' => ONLINE_CONTACT_WIDGET_VERSION,
      'is_pro' => $is_pro,
      'captcha_type' => $captcha_type,
      'home_url' => home_url(),
      'dir' => ONLINE_CONTACT_WIDGET_URL,
      'ver' => ONLINE_CONTACT_WIDGET_VERSION
    );

    if (isset($opt['buoy_animation_interval'])) {
      $wb_cnf['anim_interval'] = $opt['buoy_animation_interval'];
    }

    if ($captcha_type == 'google') {
      $wb_cnf['captcha_key'] = $opt_msg_captcha['google']['public'];
    }

    /**
     *
     */

    $wb_cnf['active_mode'] = $opt['active_page'];
    if ($is_pro && $opt['active_page'] == '1') {
      $wb_cnf['appoint_urls'] = $opt['appoint_urls'];
    }
    if ($is_pro && $opt['active_page'] == '2') {
      $wb_cnf['exception_urls'] = $opt['exception_urls'];
    }

    wp_add_inline_script(
      'wb-ocw',
      ' var wb_ocw_cnf=' . json_encode($wb_cnf, JSON_UNESCAPED_UNICODE) . ';',
      'before'
    );
  }

  public static function render_html()
  {
    $is_pro = get_option('wb_ocw_ver', 0);
    $opt = OCW_Admin::opt();
    $cnf = OCW_Admin::$cnf_fields;
    $active_items = $opt['items'];
    $items_data = $opt['items_data'];
    $position = $opt['position'];
    $is_fold = $opt['is_fold'];
    $dark_switch = $opt['dark_switch'];
    $fillet_select = $opt['fillet_select'];
    $size_select = $opt['size_select'];
    $name_switch = $opt['name_switch'];
    $open_msg = $opt['open_msg'];
    $contact_name = $opt['contact_name'];
    $contact_msg = $opt['contact_msg'];
    $fold_icon = $opt['fold_icon'];
    $fold_label = $opt['fold_label'];

    $active_mode = $opt['active_page'];

    $custom_head_color = $opt['color_head'];
    $custom_icon_class = $is_pro && isset($opt['color_icon']) && $opt['color_icon'] ? ' tool-list-color' : '';
    $user_avatar = ONLINE_CONTACT_WIDGET_URL . '/assets/images/def_avatar.png';
    $avatar_url = $opt['avatar_url'] ? $opt['avatar_url'] : ONLINE_CONTACT_WIDGET_URL . '/assets/images/pic_head.png';


    include_once ONLINE_CONTACT_WIDGET_PATH . '/inc/mode_contact.php';
  }

  // 获取自定义信息
  public static function get_custom_code()
  {
    $opt = OCW_Admin::opt();
    $custom_css = '';

    if (get_option('wb_ocw_ver', 0)) {
      if ($opt['custom_theme_color']) {
        $custom_css .= "--ocw-theme-color:" . $opt['custom_theme_color'] . ";";
      }

      if ($opt['buoy_icon_size']) {
        $custom_css .= "--ocw-buoy-icon-size:" . $opt['buoy_icon_size'] . "px;";
      }

      if ($opt['base_font_size']) {
        $custom_css .= "--ocw-bfs:" . $opt['base_font_size'] . "px;";
      }

      if ($opt['color_head']) {
        $custom_css .= "--ocw-head-bg-color:" . $opt['color_head'] . ";";
      }

      if ($opt['panel_hd_fcolor']) {
        $custom_css .= "--ocw-head-fcolor:" . $opt['panel_hd_fcolor'] . ";";
      }

      if ($opt['panel_width']) {
        $custom_css .= "--ocw-panel-width:" . $opt['panel_width'] . "px;";
      }

      if ($opt['unfold_size'] > 0) {
        $custom_css .= "--ocw-unfold-size:" . $opt['unfold_size'] . "px;";
      }

      if ($opt['unfold_radius'] > 0) {
        $custom_css .= "--ocw-unfold-radius:" . $opt['unfold_radius'] . "px;";
      }

      if ($opt['position_offset_x'] != 0) {
        $custom_css .= "--ocw-offset-x:" . $opt['position_offset_x'] . "px;";
      }

      if ($opt['position_offset_y'] != 0) {
        $custom_css .= "--ocw-offset-y:" . $opt['position_offset_y'] . "px;";
      }


      $custom_css = '.wb-ocw{' . $custom_css . '}';

      if ($opt['custom_style']) {
        $custom_css .= $opt['custom_style'];
      }
    }

    $other_tool_name = $opt['other_tool_name'];
    if ($other_tool_name) {
      $custom_css .= $other_tool_name . "{display: none!important;}";
    }

    // 暗黑模式兼容
    $dm_class_name = $opt['dark_mode_class'];
    if ($dm_class_name) {
      $custom_css .= $dm_class_name . '{--ocw-head-bg-color: #222; --ocw-head-fcolor: #eee; --wb-bfc: #eee; --wb-bgc: #222; --wb-bgcl: #333; --wb-wk: #666;}';
    }

    return $custom_css;
  }

  //工单部分
  public static function ajax_handler()
  {
    $op = isset($_POST['op']) ? sanitize_text_field($_POST['op']) : '';
    switch ($op) {
      case 'my_wo':
        self::my_ow_list();
        break;

      case 'wo_detail':
        self::my_ow_detail();
        break;

      case 'new':
        self::new_enquire();
        break;
    }
    exit();
  }

  /**
   * 工单小部件
   */
  public static function new_enquire()
  {
    global $wpdb;
    $user = wp_get_current_user();
    $uid = $user ? $user->ID : '0';
    $need_login = OCW_Admin::opt('items_data.msg.need_login');
    $ret['code'] = 1;
    do {
      if ($need_login && !$uid) {
        $ret['code'] = 403;
        /*$login_url = OCW_Admin::opt('login_url');
                if(!$login_url){
                    $login_url = wp_login_url();
                }*/
        $ret['desc'] = '需登录后才可留言。您尚未登录网站账户。'; //，<a href="'.$login_url.'" target="_blank">立即登录</a>
        break;
      }

      $name = isset($_POST['name']) ? $_POST['name'] : '';
      $name = trim(sanitize_text_field($name));
      if ($name == '') {
        $ret['desc'] = '请输入您的大名';
        break;
      }
      if (strlen($name) > 100) {
        $ret['desc'] = '姓名长度不能超过100字符';
        break;
      }

      $contact = isset($_POST['contact']) ? $_POST['contact'] : '';
      $contact = trim(sanitize_text_field($contact));
      if ($contact == '') {
        $ret['desc'] = '请输入您的联系方式';
        break;
      }

      $content = isset($_POST['message']) ? $_POST['message'] : '';
      $content = trim(sanitize_textarea_field($content));
      if (!$content) {
        $ret['desc'] = '您要咨询的是?';
        break;
      }

      if (strlen($content) > 1000) {
        $ret['desc'] = '咨询内容过长，可留下联系方式，我们联系您进一步沟通。';
        break;
      }
      $opt_msg_captcha = OCW_Admin::opt('items_data.msg.captcha');
      if ($opt_msg_captcha && $opt_msg_captcha['type'] && $opt_msg_captcha['type'] != 'none') {
        $captcha = isset($_POST['ocw_captcha']) ? trim(sanitize_text_field($_POST['ocw_captcha'])) : '';
        if (!$captcha) {
          $ret['desc'] = '请输入验证码';
          break;
        }
        if (!OCW_Captcha::verify()) {
          $verify = OCW_Captcha::result();
          $ret['desc'] = $verify['desc'];
          break;
        }
      }

      $type = isset($_POST['type']) ? $_POST['type'] : '';
      $type = trim(sanitize_text_field($type));

      $sn = current_time('ymdHi') . mt_rand(100, 999);
      $subject = $sn . ' by: ' . $name;

      $t_contcat = $wpdb->prefix . 'ocw_contact';
      $t_detail = $wpdb->prefix . 'ocw_contact_content';

      $a = array(
        'sn' => $sn,
        'name' => $name,
        'title' => $subject,
        'create_date' => current_time('mysql'),
        'update_time' => current_time('mysql'),
        'status' => 1,
        'uid' => $uid,
        'type' => $type,
        'is_read' => 1,
        'is_new' => 1,
      );


      $contact_type = isset($_POST['contact_type']) ? sanitize_text_field($_POST['contact_type']) : 'mobile';
      $contact_type = in_array($contact_type, array('qq', 'wx', 'tel', 'mobile', 'email')) ? $contact_type : 'mobile';
      $a[$contact_type] = $contact;

      $wpdb->insert($t_contcat, $a);
      $pid = $wpdb->insert_id;

      if (!$pid) {
        $ret['desc'] = '工单保存失败，请联系管理员。';
        break;
      }

      $d = array(
        'pid' => $pid,
        'uid' => $user->ID,
        'pics' => '',
        'create_date' => current_time('mysql'),
        'ip' => $_SERVER['REMOTE_ADDR'],
        'content' => $content,
      );
      $wpdb->insert($t_detail, $d);
      $ret['code'] = 0;

      $opt = OCW_Admin::opt('items_data')['msg'];
      $type = $a['type'];
      $cnf = OCW_Admin::$cnf_fields;
      $msg_cnf = $cnf['contact_items']['msg'];

      $ret['data'] = array(
        'name' => $a['name'],
        'contact_type' => $msg_cnf['form_contact_way'][$contact_type],
        'contact' => $contact,
        'type' => $opt['subject_type'][$type],
        'message' => $content
      );

      $ret['desc'] = $opt['auto_reply_msg'];

      do_action('ocw_new_concat', $pid);
    } while (false);


    header('content-type:text/json;');
    echo json_encode($ret);
    exit();
  }

  public static function the_member_cover($curauth, $size = 36)
  {

    $defaultgravatar = wb_assets_url('img') . '/images/def_avatar.png';


    return get_avatar($curauth->ID, $size, $defaultgravatar);
  }

  public static function my_ow_list()
  {
    global $wpdb;

    $ret = array('code' => 0, 'desc' => 'success');
    $data = [];
    $current_user = wp_get_current_user();

    $get = array_map('trim', $_POST);
    $page = isset($get['page']) ? absint($get['page']) : 1;
    $num = isset($get['num']) ? absint($get['num']) : 10;
    // $page = max($page, 1);
    $offset = ($page - 1) * $num;

    $table_name = $wpdb->prefix . 'ocw_contact';
    $sql = $wpdb->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM $table_name WHERE uid=%d", $current_user->ID);

    if (isset($get['status']) && (int)$get['status']) {
      $sql .= $wpdb->prepare(" AND status = %d", $get['status']);
    } else {
      $sql .= ' AND status <> 9';
    }

    $limit = ' LIMIT ' . $offset . ',' . $num;
    $sql .= ' ORDER BY update_time DESC' . $limit;
    $list = $wpdb->get_results($sql);
    $data['total'] = $wpdb->get_var("SELECT FOUND_ROWS()");

    foreach ($list as $item) {
      $item->last_update_user = self::last_name($item->id);
    }

    $data['items'] = $list;
    $data['cnf'] = array(
      'type' => OCW_Admin::opt('items_data.msg.subject_type')
    );

    $ret['data'] = $data;

    header('Content-Type: application/json');
    echo json_encode($ret);
    exit();
  }

  public static function my_ow_detail()
  {
    global $wpdb;
    $user = wp_get_current_user();
    $t = $wpdb->prefix . 'ocw_contact';
    $t_detail = $wpdb->prefix . 'ocw_contact_content';

    $id = intval($_POST['id']);
    $id = $wpdb->get_var($wpdb->prepare("select id from $t where uid=%d AND id=%d", $user->ID, $id));

    $list = array();

    $row = $wpdb->get_row($wpdb->prepare("SELECT a.* FROM $t a  WHERE a.id=%d", $id));

    if ($id) {
      $wpdb->query($wpdb->prepare("UPDATE  $t set is_read=1 where id=%d", $id));

      $sql = $wpdb->prepare("SELECT a.*,IFNULL(b.display_name,'system') display_name FROM $t_detail a LEFT JOIN $wpdb->users b ON a.uid=b.ID where a.pid=%d order by a.cid asc ", $id);
      //echo $sql;
      $list = $wpdb->get_results($sql);
      foreach ($list as $k => $r) {
        $r->user_avatar = self::avatar_url($r->uid);
      }
    }

    $ret = array('code' => 0, 'desc' => 'success');

    $ret['row'] = $row;
    $ret['list'] = wp_unslash($list);
    // $ret['up_cnf'] = array(
    //     'upload_server' => wb_url('upload-img'),
    //     'fileupload' => wp_create_nonce('fileupload')
    // );

    header('content-type:text/json;');
    echo json_encode($ret);
    exit();
  }

  public static function avatar_url($uid)
  {
    static $src_list = array();
    $src = '';

    if (!$uid) {
      return $src;
    }
    if (isset($src_list[$uid])) {
      return $src_list[$uid];
    }


    $img_html = get_avatar($uid, 96, $src);

    if (preg_match('#src=([^\s]+)#i', $img_html, $match)) {
      $src = trim($match[1], "\"'");
    }
    $src_list[$uid] = $src;
    return $src;
  }

  public function new_contact()
  {

    global $wpdb;
    $user = wp_get_current_user();
    $t = $wpdb->prefix . 'ocw_contact';
    $t_detail = $wpdb->prefix . 'ocw_contact_content';

    $ret = array(
      'code' => 1,
      'desc' => 'error',
    );

    do {
      if (!class_exists('WB_Admin_Contact')) {
        $ret['desc'] = '工单未启用';
        break;
      }
      $name = trim($_POST['contactName']);
      if ($name == '') {
        $ret['desc'] = '请输入您的大名';
        break;
      }
      if (strlen($name) > 100) {
        $ret['desc'] = '姓名长度不能超过100字符';
        break;
      }

      $subject = trim($_POST['subject']);
      if (!$subject) {
        $ret['desc'] = '请输入咨询主题';
        break;
      }
      if (strlen($subject) > 200) {
        $ret['desc'] = '咨询主题长度不能超过200字符';
        break;
      }

      $content = trim($_POST['comments']);
      if (!$content) {
        $ret['desc'] = '您要咨询的是?';
        break;
      }
      if (strlen($content) > 1000) {
        $ret['desc'] = '咨询内容不能超过1000字符';
        break;
      }

      $s_pics = $_POST['pics'];
      if (!$s_pics || !is_array($s_pics)) {
        $s_pics = array();
      }
      $pics = array();
      /*foreach ($s_pics as $pic){
				if(strpos($pic,home_url())===0){
					$local_img = ABSPATH.str_replace(home_url('/'),'',$pic);

					$thumb_img = dirname($local_img).'/s_'.basename($local_img);

					if(file_exists($local_img)){
						$new_img = str_replace('/CCT_','/CT_',$local_img);
						if(copy($local_img,$new_img)){
							$pics[] = str_replace('/CCT_','/CT_',$pic);
							unlink($local_img);
						}else{
							$pics[] = $pic;
						}

					}

					if(file_exists($thumb_img)){
						$new_thumb = str_replace('/s_CTT_','/s_CT_',$thumb_img);
						copy($thumb_img,$new_thumb);
						unlink($thumb_img);
					}
				}
			}*/

      $pics = $s_pics;


      $t_contcat = $wpdb->prefix . 'ocw_contact';
      $t_detail = $wpdb->prefix . 'ocw_contact_content';

      $a = array(
        'sn' => current_time('ymdHi') . mt_rand(100, 999),
        'name' => sanitize_text_field($name),
        'title' => sanitize_text_field($subject),
        'create_date' => current_time('mysql'),
        'update_time' => current_time('mysql'),
        'status' => 1,
        'uid' => $user->ID,
        'type' => sanitize_text_field(trim($_POST['type'])),
        'is_read' => 1,
        'is_new' => 1,
      );


      $conf = WB_Admin_Contact::conf();

      foreach (array('qq' => 'qq', 'email' => 'email', 'mobile' => 'phone') as $field => $post_field) {
        if (!in_array($field, $conf['fields']) || !isset($_POST[$post_field])) continue;

        $a[$field] = sanitize_text_field(trim($_POST[$post_field]));
      }


      $wpdb->insert($t_contcat, $a);
      $pid = $wpdb->insert_id;
      if (!$pid) {
        $ret['desc'] = '工单保存失败，请联系管理员。';
        break;
      }

      $d = array(
        'pid' => $pid,
        'uid' => $user->ID,
        'pics' => $pics ? json_encode($pics) : '',
        'create_date' => current_time('mysql'),
        'ip' => $_SERVER['REMOTE_ADDR'],
        'content' => $content,
      );
      $wpdb->insert($t_detail, $d);
      $ret['code'] = 0;
      $ret['desc'] = 'success';
      $ret['data'] = $d;

      do_action('wb_new_concat', $pid);
    } while (false);


    header('content-type:text/json;');
    echo json_encode($ret);
    exit();
  }

  public static function last_name($pid)
  {
    global $wpdb;
    $t = $wpdb->prefix . 'ocw_contact_content';
    $row = $wpdb->get_row($wpdb->prepare("SELECT a.*,b.display_name FROM $t a LEFT  JOIN $wpdb->users b ON a.uid=b.ID WHERE  a.pid=%d ORDER BY a.cid DESC LIMIT 1", $pid));

    if ($row && $row->display_name) {
      return $row->display_name;
    }

    return 'Auto Message';
  }
}
