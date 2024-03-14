<?php


class WB_IMGSPY_Conf
{
  public static $name = 'imgspider_pack';
  public static $optionName = 'wb_imgspider_option';

  public static $cnf_fields = array(
    'filter' => array(
      'type' => array(
        array(
          'code' => 'jpg',
          'name' => 'jpg'
        ),
        array(
          'code' => 'jpeg',
          'name' => 'jpeg'
        ),
        array(
          'code' => 'png',
          'name' => 'png'
        ),
        array(
          'code' => 'gif',
          'name' => 'gif'
        ),
        array(
          'code' => 'bmp',
          'name' => 'bmp'
        ),
        array(
          'code' => 'webp',
          'name' => 'webp'
        ),
      )

    )
  );

  public static function init()
  {
    if (is_admin()) {
      add_action('admin_menu', array(__CLASS__, 'admin_menu'));
      add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_enqueue_scripts'), 1);
      add_filter('plugin_action_links', array(__CLASS__, 'actionLinks'), 10, 2);
      add_filter('plugin_row_meta', array(__CLASS__, 'plugin_row_meta'), 10, 2);
      add_action('admin_notices', array(__CLASS__, 'admin_notices'));
    }
  }


  public static function admin_notices()
  {
    global $current_screen;
    if (!current_user_can('update_plugins')) {
      return;
    }
    if (!preg_match('#imgspider_pack#', $current_screen->parent_base)) {
      return;
    }
    $current         = get_site_transient('update_plugins');
    if (!$current) {
      return;
    }
    $plugin_file = plugin_basename(IMGSPY_BASE_FILE);
    if (!isset($current->response[$plugin_file])) {
      return;
    }
    $all_plugins     = get_plugins();
    if (!$all_plugins || !isset($all_plugins[$plugin_file])) {
      return;
    }
    $plugin_data = $all_plugins[$plugin_file];
    $update = $current->response[$plugin_file];

    //print_r($update);
    $update_url = wp_nonce_url(self_admin_url('update.php?action=upgrade-plugin&plugin=') . $plugin_file, 'upgrade-plugin_' . $plugin_file);

    $html = '<div class="update-message notice inline notice-warning notice-alt"><p>' . $plugin_data['Name'] . '有新版本可用。';
    $html .= '<a href="' . $update->url . '" target="_blank" aria-label="查看' . $plugin_data['Name'] . '版本' . $update->new_version . '详情">查看版本' . $update->new_version . '详情</a>';
    $html .= '或<a href="' . $update_url . '" class="update-link" aria-label="现在更新 ' . $plugin_data['Name'] . '">现在更新</a>。</p></div>';
    echo $html;
  }



  public static function plugin_row_meta($links, $file)
  {

    $base = plugin_basename(IMGSPY_BASE_FILE);
    if ($file == $base) {


      $links[] = '<a href="https://www.wbolt.com/plugins/imgspider/">插件主页</a>';
      $links[] = '<a href="https://www.wbolt.com/imgspider-plugin-documentation.html">FAQ</a>';
      $links[] = '<a href="https://wordpress.org/support/plugin/imgspider/">反馈</a>';
    }
    return $links;
  }

  public static function actionLinks($links, $file)
  {

    if ($file != plugin_basename(IMGSPY_BASE_FILE))
      return $links;
    if (!get_option('wb_imgspider_ver', 0)) {
      $a_link = '<a href="https://www.wbolt.com/plugins/imgspider-pro" target="_blank"><span style="color: #FCB214;">升至Pro版</span></a>';
      array_unshift($links, $a_link);
    }

    $settings_link = '<a href="' . menu_page_url('imgspider_pack', false) . '">设置</a>';

    array_unshift($links, $settings_link);

    return $links;
  }

  public static function admin_menu()
  {
    add_options_page(
      'IMGspider图片蜘蛛',
      '图片蜘蛛',
      'manage_options',
      'imgspider_pack',
      [__CLASS__, 'admin_settings']
    );
  }

  public static function insert_assets()
  {

    $assets = include __DIR__ . '/plugins_assets.php';
    if (!$assets || !is_array($assets)) {
      return;
    }

    $wp_styles = wp_styles();
    if (isset($assets['css']) && is_array($assets['css'])) foreach ($assets['css'] as $r) {
      $wp_styles->add($r['handle'], IMGSPY_URI . $r['src'], $r['dep'], null, $r['args']);
      $wp_styles->enqueue($r['handle']); //.'?v=1'
    }
    if (isset($assets['js']) && is_array($assets['js'])) foreach ($assets['js'] as $r) {
      if (!$r['src'] && $r['in_line']) {
        wp_register_script($r['handle'], false, $r['dep'], false, true);
        wp_enqueue_script($r['handle']);
        wp_add_inline_script($r['handle'], $r['in_line'], 'after');
      } else if ($r['src']) {
        wp_enqueue_script($r['handle'], IMGSPY_URI . $r['src'], $r['dep'], null, true);
      }
    }
  }

  public static function admin_enqueue_scripts($hook)
  {
    if (!preg_match('#imgspider_pack#', $hook)) {
      return;
    }

    $prompt_items = array();
    if (file_exists(__DIR__ . '/_prompt.php')) {
      include __DIR__ . '/_prompt.php';
    }

    wp_register_script('wbp-imgscrapy', false, null, false);
    wp_enqueue_script('wbp-imgscrapy');

    wp_enqueue_media();

    // wp_enqueue_script('wbp-imgscrapy-js-vendors', IMGSPY_URI . 'setting/assets/js/chunk-vendors.js', array(), IMGSPY_VERSION, true);
    // wp_enqueue_script('wbp-imgscrapy-js', IMGSPY_URI . 'setting/assets/js/app.js', array('wbp-imgscrapy-js-vendors'), IMGSPY_VERSION, true);
    // wp_enqueue_style('wbs-style-imgscrapy', IMGSPY_URI . 'assets/wbp_setting.css', array(), IMGSPY_VERSION);
    // wp_enqueue_style('wbs-style-imgscrapy-vendors', IMGSPY_URI . 'setting/assets/css/chunk-vendors.css', array(), IMGSPY_VERSION);

    global $wp_post_types;

    $post_types = array();
    if ($wp_post_types && is_array($wp_post_types)) foreach ($wp_post_types as $type) {
      if ($type->public) {
        $post_types[$type->name] = $type->labels->name;
      }
    }

    $ajax_nonce = wp_create_nonce('wp_ajax_wb_imgspider');
    $imgspider_ver = intval(get_option('wb_imgspider_ver', 0));

    $editor = WB_IMGSPY_Image::get_image_editor(null, null);
    $wb_cnf = array(
      'base_url' => admin_url(),
      'ajax_url' => admin_url('admin-ajax.php'),
      'dir_url' => IMGSPY_URI,
      'pd_code' => IMGSPY_CODE,
      'pd_title' => 'IMGspider-图片蜘蛛',
      'pd_version' => IMGSPY_VERSION,
      'is_pro' => $imgspider_ver,
      'doc_url' => 'https://www.wbolt.com/imgspider-plugin-documentation.html',
      'action' => array(
        'act' => 'wb_scrapy_image',
        'fetch' => 'get_setting',
        'push' => 'set_setting'
      ),
      'watermark' => preg_match('/Imagick/i', $editor),
      'watermark_preview' => '',
      'prompt' => $prompt_items
    );

    $category = array_column(get_terms(['taxonomy' => 'category', 'parent' => 0,]), 'name', 'term_id');
    $post_status = get_post_statuses();
    $inline_script = 'var wb_ajaxurl="' . admin_url('admin-ajax.php') . '", 
        wb_vue_path="' . IMGSPY_URI . 'setting/",
        imgspider_ver=' . $imgspider_ver . ',
        wb_cnf=' . json_encode($wb_cnf) . ',
        post_types=' . json_encode($post_types, JSON_UNESCAPED_UNICODE) . ',
        post_status=' . json_encode($post_status, JSON_UNESCAPED_UNICODE) . ',
        category=' . json_encode($category, JSON_UNESCAPED_UNICODE) . ',
        _wb_imgspider_ajax_nonce="' . $ajax_nonce . '";';

    wp_add_inline_script('wbp-imgscrapy', $inline_script, 'before');


    add_filter('style_loader_tag', function ($tag, $handle, $href, $media) {
      if (!preg_match('#^vue-#', $media)) {
        return $tag;
      }

      $media = htmlspecialchars_decode($media);
      $r = [];
      parse_str(str_replace('vue-', '', $media), $r);
      $rel = '';
      $attr = [];
      if ($r && is_array($r)) {
        if (isset($r['rel'])) {
          $rel = $r['rel'];
          unset($r['rel']);
        }
        foreach ($r as $attr_k => $attr_v) {
          $attr[] = sprintf('%s="%s"', $attr_k, esc_attr($attr_v));
        }
      }

      $tag = sprintf(
        '<link href="%s" rel="%s" %s/>' . "\n",
        $href,
        $rel,
        implode(" ", $attr)
      );
      return $tag;
    }, 10, 4);
    add_filter('script_loader_tag', function ($tag, $handle, $src) {
      if (!preg_match('#-vue-js-#', $handle)) {
        return $tag;
      }
      $parts = explode('?', $src, 2);
      $src = $parts[0];
      $type = '';
      $attr = '';
      if (isset($parts[1])) {
        $r = [];
        parse_str(htmlspecialchars_decode($parts[1]), $r);
        if ($r) {
          if (isset($r['type'])) {
            $type = sprintf(' type="%s"', esc_attr($r['type']));
            unset($r['type']);
          }
          $attr_txt = '';
          if (isset($r['attr'])) {
            $attr_txt = $r['attr'];
            unset($r['attr']);
          }
          foreach ($r as $k => $v) {
            $attr .= sprintf(' %s="%s"', $k, esc_attr($v));
          }
          if ($attr_txt) {
            $attr .= sprintf(' %s', esc_attr($attr_txt));
          }
        }
      }

      $tag = sprintf('<script%s src="%s"%s id="%s-js"></script>' . "\n", $type, $src, $attr, $handle);
      return $tag;
    }, 10, 3);
    self::insert_assets();
  }

  public static function get_proxy()
  {

    $cnf = WB_IMGSPY_Conf::opt();

    if (!isset($cnf['df_mode'])) {
      return false;
    }
    if (in_array($cnf['df_mode'], array('none', 'proxy', 'ext'))) {
      return false;
    }
    $proxy_type = $cnf['df_mode'];


    /*if(!isset($cnf['proxy']) || empty($cnf['proxy']) || !is_array($cnf['proxy'])){
            return false;
        }


        $proxy_type = '';

        foreach($cnf['proxy'] as $type=>$active){
            if($active){
                $proxy_type = $type;
                break;
            }
        }*/

    if (!$proxy_type) {
      return false;
    }

    /*if($proxy_type=='wb'){
            return array('type'=>$proxy_type);
        }*/


    if (!isset($cnf['proxy_manual']) || empty($cnf['proxy_manual']) || !is_array($cnf['proxy_manual'])) {
      return false;
    }

    if (!isset($cnf['proxy_manual'][$proxy_type])) {
      return false;
    }

    $ret = $cnf['proxy_manual'][$proxy_type];
    $ret['type'] = 'ip';

    return $ret;
  }

  public static function  array_sanitize_text_field($value, $skip = [])
  {
    if (is_array($value)) {
      foreach ($value as $k => $v) {
        if ($skip && in_array($k, $skip)) continue;
        $value[$k] = self::array_sanitize_text_field($v, $skip);
      }
      return $value;
    } else {
      return sanitize_text_field($value);
    }
  }

  public static function update_cnf()
  {
    $opt_data = self::array_sanitize_text_field($_POST['opt'], ['domain', 'custom_name', 'custom_title']);
    if (!empty($opt_data['filter']['domain'])) {
      $filter_domain = trim(str_replace("\r\n", "\n", sanitize_textarea_field($opt_data['filter']['domain'])));
      $filter_domain = explode("\n", $filter_domain);
      $domain = [];
      foreach ($filter_domain as $v) {
        $v = trim($v);
        if (!$v) continue;
        $domain[] = $v;
      }
      $opt_data['filter']['domain'] = $domain;
    } else {
      $opt_data['filter']['domain'] = [];
    }
    if (!empty($opt_data['filter']['type'])) {
      $filter_type = explode(',', trim($opt_data['filter']['type']));
      $types = [];
      foreach ($filter_type as $v) {
        $v = trim($v);
        if (!$v) continue;
        $types[$v] = '1';
      }
    } else {
      $opt_data['filter']['type'] = [];
    }

    update_option(self::$optionName, $opt_data, false);
  }

  public static function opt()
  {

    static $opt = null;
    if ($opt) {
      return $opt;
    }
    $def = array(
      'switch' => '1',
      'mode' => '1',
      'df_mode' => 'none',
      'proxy' => array(
        'wb' => 0,
      ),
      'proxy_manual' => array(
        //array('name'=>'test','ip'=>'127.0.0.1','port'=>'1080','user'=>'','pwd'=>'')
      ),

      /*
            'proxy_type'=>'none',
            'proxy_ip'=>'',
            'proxy_port'=>'',
            'proxy_user'=>'',
            'proxy_pwd'=>'',*/

      'del_src_url' => 0,
      'thumbnail' => 0, //第一张图作为缩略图
      'rule' => array(
        'size' => 0,
        'custom_size' => '',
        'file_name' => 0,
        'custom_name' => '',
        'title_alt' => 0,
        'custom_title' => '',
        'align' => 'none'
      ),

      'filter' => array(
        'except_index' => '',
        'min_width' => '',
        'domain' => [],
        'type' => []
      ),
      'watermark' => array(
        'type' => 0,
        'image' => '',
        'text' => '',
        'font' => '',
        'size' => 32,
        'color' => '',
        'alpha' => 30,
        'pos' => 1,
        'x' => '',
        'y' => '',
        'apply' => 'new',
        'min_width' => '700',
        'min_height' => '500',
      ),
    );
    $opt = get_option(self::$optionName, array());
    foreach ($def as $k => $v) {
      if (!isset($opt[$k])) {
        $opt[$k] = $v;
      } else {
        if (is_array($v)) foreach ($v as $sk => $sv) {
          if (!isset($opt[$k][$sk])) {
            $opt[$k][$sk] = $sv;
          }
        }
      }
    }
      foreach (['size', 'file_name', 'title_alt'] as $f) {
          if (!isset($opt['rule'])) break;
          if (isset($opt['rule'][$f])) {
              $opt['rule'][$f] = intval($opt['rule'][$f]);
          }
      }
    foreach (['type', 'size', 'alpha', 'pos'] as $f) {
      if (!isset($opt['watermark'])) break;
      if (isset($opt['watermark'][$f])) {
        $opt['watermark'][$f] = intval($opt['watermark'][$f]);
      }
    }

    return apply_filters('wb_imgspy_cnf', $opt);
  }

  public static function cnf($key, $default = null)
  {
    static $option = array();
    if (!$option) {
      $option = self::opt();
    }
    $keys = explode('.', $key);
    $find = false;
    $cnf = $option;
    foreach ($keys as $_k) {
      if (isset($cnf[$_k])) {
        $cnf = $cnf[$_k];
        $find = true;
        continue;
      }
      $find = false;
    }
    if ($find) {
      return $cnf;
    }

    return $default;
  }



  public static function admin_settings()
  {
    echo '<div id="optionsframework-wrap">
			    <div id="app"></div>
			    <div style="display:none;">
			        <button type="button" id="wb-wbsm-btn-spy-batch-ext"></button>
			    </div>
			</div>';
  }
}
