<?php


class WBMember
{
  public static $version = 1.0;

  public static $instance = null;

  public $module = 'home';

  public static function init()
  {
    if (!self::$instance) {
      self::$instance = new self();
    }
    $obj = self::$instance;
    self::plugin_update();

    add_action('wp_ajax_wbp_api', [$obj, 'ajax_wb_member']);
    add_filter('get_wbm_cnf', [$obj, 'get_wbm_cnf']);
    add_filter('set_wbm_cnf', [$obj, 'set_wbm_cnf']);
    if (is_admin()) {
      return;
    }
    add_action('init', [$obj, 'wp_init']);
    add_action('wbm_head', [$obj, 'render_head'], 100);
    add_action('wbm_header', [$obj, 'render_header'], 100);
    // add_action('wbm_left', [$obj, 'render_menu'], 100);
    add_action('wbm_get_menu', [$obj, 'render_menu'], 100);
    add_action('wbm_content', [$obj, 'render_content'], 100);
    add_action('wbm_footer', [$obj, 'render_footer'], 100);
    add_action('wbm_header_logo', [$obj, 'render_header_logo'], 100);

    add_action('wbm_content_home', [$obj, 'render_content_home']);

    add_filter('script_loader_tag', [$obj, 'script_tag_handler'], 10, 3);

    //全局 引入js - 测试
    add_filter('wbm_script', function ($js) {
      $js[] = [
        'id' => 'wbm-core',
        'src' => plugin_dir_url(__FILE__) . 'assets/js/wbm.js',
        'deps' => false,
        'ver' => false,
        'args' => array('in_footer' => 'true')
      ];
      return $js;
    });

    //全局 引入样式
    add_filter('wbm_style', function ($css) {
      $css[] = [
        'id' => 'wbm-main',
        'src' => plugin_dir_url(__FILE__) . 'assets/css/wbm.css',
        /*'deps' => [],
                'ver' => false,
                'media' => 'all',*/
      ];
      return $css;
    });
  }

  public function get_wbm_cnf($cnf)
  {
    $cnf['wbm'] = self::cnf();
    return $cnf;
  }

  public function set_wbm_cnf($cnf)
  {
    if (empty($cnf['wbm'])) {
      return $cnf;
    }
    $opt = $cnf['wbm'];
    unset($cnf['wbm']);
    foreach ($opt as $k => $v) {
      if (is_array($v)) {
        continue;
      }
      $opt[$k] = sanitize_text_field($v);
    }
    $this->update_cnf($opt);

    return $cnf;
  }


  public static function plugin_update()
  {
  }

  public function render_head()
  {
    do_action('wbm_head_' . $this->module);
    wp_print_styles();
    print_head_scripts();
  }

  public function render_footer()
  {
    do_action('wbm_footer_' . $this->module);
    print_late_styles();
    print_footer_scripts();
  }

  public function render_header()
  {
    echo '<div class="wbm-page-title"></div>';
  }

  /**
   * @return void
   */
  public function render_menu()
  {
    $menus = $this->menus();
    $slug = trim(sanitize_text_field($_GET['slug'] ?? ''));
    $html = [];

    foreach ($menus as $k => $m) {
      $class_name = $slug == $k ? ' current' : '';
      $html[] = '<li class="' . $class_name . '"><a href="' . $m['url'] . '"><svg class="wbm-icon wbmico-' . $k . '"><use xlink:href="#wbm-sico-' . $k . '"></use></svg><span>' . $m['name'] . '</span></a></li>';
    }
    echo apply_filters('wbm_render_menu', implode("\n", $html));
  }

  public function render_content()
  {

    do_action('wbm_content_' . $this->module);
  }

  public function render_content_home()
  {
    echo '<div id="wb-app"></div>';
  }

  public function render_header_logo()
  {
    self::the_logo(true);
  }

  /**
   * 
   * @return void
   */
  public function wp_init()
  {
    $wbp = $_GET['wbp'] ?? null;
    if (!$wbp) {
      return;
    }
    if ($wbp !== 'member') {
      return;
    }
    if (!is_user_logged_in()) {
      wp_redirect(wp_login_url(home_url('?' . $_SERVER['QUERY_STRING'])));
      exit();
    }

    //默认样式
    $styles = [];
    //默认js
    $scripts = [];

    $scripts = apply_filters('wbm_script', $scripts);
    $styles = apply_filters('wbm_style', $styles);
    foreach ($styles as $r) {
      wp_enqueue_style($r['id'], $r['src'], $r['deps'] ?? [], $r['ver'] ?? false, $r['media'] ?? 'all');
    }

    foreach ($scripts as $r) {
      wp_enqueue_script($r['id'], $r['src'], $r['deps'] ?? [], $r['ver'] ?? false, $r['args'] ?? false);
    }

    $wbm_cnf_def = ['dir_url' => '"' . plugin_dir_url(__FILE__) . 'wbm/assets/js"'];
    $wbm_cnf = apply_filters('wbm_js_cnf', $wbm_cnf_def);
    $wbm_js_cnf = 'var wbm_js_cnf= ' . json_encode($wbm_cnf) . ';';

    // 输出js参数
    wp_register_script('wbm-inline', false, null, false);
    wp_enqueue_script('wbm-inline');
    wp_add_inline_script('wbm-inline', $wbm_js_cnf, 'before');

    // 输出全局行内样式（结合设置值）
    $cnf = self::cnf();

    $wbm_css_var = '';
    if (isset($cnf['theme_color']) && $cnf['theme_color']) {
      $wbm_css_var = ':root{';
      $wbm_css_var .= '--wbm-theme-color: ' . $cnf['theme_color'] . '; ';
      $wbm_css_var .= '}';
    }

    $wbm_inline_css = apply_filters('wbm_css_cnf', $wbm_css_var);
    wp_register_style('wbm-inline', false, null, false);
    wp_enqueue_style('wbm-inline');
    wp_add_inline_style('wbm-inline',  $wbm_inline_css, 'before');

    // 功能模块
    $slug = trim(sanitize_text_field($_GET['slug'] ?? ''));

    if (preg_match('#^[a-z0-9_-]+$#i', $slug)) {
      $this->module = $slug;
    }

    // 菜单项
    $menus = $this->menus();

    include __DIR__ . '/tpl/member.php';

    exit();
  }

  public function ajax_wb_member()
  {
    $op = $REQUEST['op'] ?? null;

    switch ($op) {
      case 'cnf';
        $ret = [
          'code' => 0,
          'desc' => 'success',
          'data' => self::cnf()
        ];
        $this->resp($ret);
        break;

      case 'update-cnf':
        $ajax_nonce = sanitize_text_field($_POST['_ajax_nonce'] ?? '');
        if (!wp_verify_nonce($ajax_nonce, 'wbm_api_ajax')) {
          break;
        }
        if (!current_user_can('manage_options')) {
          break;
        }

        $opt = $_POST['opt'] ?? [];
        foreach ($opt as $k => $v) {
          if (is_array($v)) {
            continue;
          }
          $opt[$k] = sanitize_text_field($v);
        }
        $this->update_cnf($opt);
        $ret = [
          'code' => 0,
          'desc' => 'success',
          'data' => self::cnf()
        ];
        $this->resp($ret);
        break;
      case 'set-logo':
        $ajax_nonce = sanitize_text_field($_POST['_ajax_nonce'] ?? '');
        if (!wp_verify_nonce($ajax_nonce, 'wbm_api_ajax')) {
          break;
        }
        //设置logo
        if (!current_user_can('manage_options')) {
          break;
        }
        $logo = sanitize_text_field($_POST['logo'] ?? '');
        $this->update_cnf(['logo' => $logo]);
        $ret = [
          'code' => 0,
          'desc' => 'success',
          'data' => self::cnf()
        ];
        $this->resp($ret);
        break;
    }
  }

  public function menus()
  {
    $menus = [
      'logout' => [
        'sort' => 99,
        'name' => '退出',
        'url' => wp_logout_url(),
      ]
    ];
    $menus =  apply_filters('wbm_menu', $menus);

    uasort($menus, function ($a, $b) {
      $n1 = intval($a['sort'] ?? 0);
      $n2 = intval($b['sort'] ?? 0);
      if ($n1 == $n2) {
        return 0;
      }
      return $n1 < $n2 ? -1 : 1;
    });

    return $menus;
  }

  public static function default_cnf()
  {
    $cnf = [
      'logo' => '',
      'theme_color' => ''
      /*'load_theme_header'=>1,
        'load_theme_footer'=>1,*/
    ];

    return apply_filters('wbm_default_cnf', $cnf);
  }

  public function update_cnf($data)
  {
    $cnf = $this->cnf();
    foreach ($cnf as $k => $v) {
      if (isset($data[$k])) {
        $cnf[$k] = $data[$k];
      }
    }
    $cnf = apply_filters('wbm_update_cnf', $cnf);
    update_option('wbm-cnf', $cnf, false);
  }

  public static function cnf()
  {
    static $option = null;
    if (!$option) {
      $option = get_option('wbm-cnf', null);
    }
    $default = self::default_cnf();

    if (empty($option)) {
      $option = $default;
    } else {
      foreach ($default as $k => $v) {
        if (!isset($option[$k])) {
          $option[$k] = $v;
          continue;
        }
        if (is_array($v)) {
          foreach ($v as $sk => $sv) {
            if (!isset($option[$k][$sk])) {
              $option[$k][$sk] = $sv;
            }
          }
        }
      }
    }

    return apply_filters('wbm_cnf', $option);
  }

  public function resp($ret)
  {
    header('content-type:text/json;charset=utf-8');
    echo json_encode($ret);
    exit();
  }

  /**
   * js输出加type="module"
   * 适用vite生成module js
   *
   * @param [type] $tag
   * @param [type] $handle
   * @param [type] $src
   * @return void
   */
  public function script_tag_handler($tag, $handle, $src)
  {
    if (preg_match("/wbm\-/i", $handle)) {
      return '<script type="module" src="' . esc_url($src) . '"></script>';
    } else {
      return $tag;
    }
  }

  /**
   * logo位置内容输出
   *
   * @param bool $echo
   *
   * @return string
   */
  public static function the_logo($echo = true)
  {
    $html = '';

    $cnf = self::cnf();
    $logo_img = $cnf['logo'] ?? '';

    $html .= '<div ';
    $html .= 'class="wbm-logo"><a href="' . esc_url(home_url('/')) . '" rel="home" title="' . get_bloginfo('name') . '">';

    if ($logo_img) :
      $html .= '<img src="' . $logo_img . '" alt="' . get_bloginfo('name') . '"/>';

    else :
      $html .= '<strong>' . get_bloginfo('name') . '</strong>';
    endif;

    $html .= '</a></div>';

    if ($echo) {
      echo $html;
    }

    return $html;
  }

  public static function wbm_url()
  {
    return plugin_dir_url(__DIR__);
  }
}
