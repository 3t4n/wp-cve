<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function pdckl_options_install()
{
    global $pdckl_db_version;

    if(get_option('WPLANG') == 'sk_SK') {
      require_once(dirname(__FILE__) . '/lang/sk_box.php');
    } else {
      require_once(dirname(__FILE__) . '/lang/cz_box.php');
    }

    if(get_option("pdckl_db_version") == '') {
      add_option("pdckl_db_version", $pdckl_db_version);
      update_option("pdckl_db_version", $pdckl_db_version);
      add_option("pdckl_jquery", 1);
      add_option("pdckl_active", 0);
      add_option("pdckl_auto", 1);
      add_option("pdckl_purchase", 1);
      add_option("pdckl_title", $box_lang['box_title']);
      add_option("pdckl_type", "both");
      add_option("pdckl_pixel", "");
      add_option("pdckl_showform", 0);
      add_option("pdckl_links", 5);
      add_option("pdckl_price", 50);
      add_option("pdckl_price_extra", "0 0");
      add_option("pdckl_wd_token", "");
      add_option("pdckl_api_userid", "");
      add_option("pdckl_api_password", "");
      add_option("pdckl_api_signature", "");
    }
}

function pdckl_db_install()
{
    global $wpdb;

    $table_name = $wpdb->prefix . "pdckl_links";

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
    id int(10) NOT NULL AUTO_INCREMENT,
    id_post int(18) NOT NULL,
    time int(10) NOT NULL,
    link text NOT NULL,
    active tinyint(1) NOT NULL,
    PRIMARY KEY id (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    $sql = 'ALTER TABLE ' . $table_name . ' CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;';
    dbDelta($sql);
}

function pdckl_options_uninstall()
{
    delete_option('pdckl_db_version');
    delete_option('pdckl_jquery');
    delete_option('pdckl_active');
    delete_option('pdckl_auto');
    delete_option('pdckl_purchase');
    delete_option('pdckl_title');
    delete_option('pdckl_showform');
    delete_option('pdckl_links');
    delete_option('pdckl_price');
    delete_option('pdckl_price_extra');
    delete_option('pdckl_type');
    delete_option('pdckl_pixel');
    delete_option('pdckl_wd_token');
    delete_option('pdckl_api_userid');
    delete_option('pdckl_api_password');
    delete_option('pdckl_api_signature');
}

function pdckl_db_uninstall()
{
    global $wpdb;

    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}pdckl_links");
}

function pdckl_update_v1_to_v2()
{
    global $wpdb, $pdckl_db_version;

    $easyad_version = get_site_option('easyad_db_version');

    if($easyad_version) {
      if(version_compare($easyad_version, '2.0') == -1) {
        //Rename table
        $wpdb->query("RENAME TABLE " . $wpdb->prefix."easyad_links TO "  . $wpdb->prefix."pdckl_links");

        //Rename options
        add_option("pdckl_db_version", $pdckl_db_version);
        update_option("pdckl_db_version", $pdckl_db_version);
        add_option("pdckl_jquery", get_option("easyad_jquery"));
        add_option("pdckl_active", get_option("easyad_active"));
        add_option("pdckl_auto", 1);
        add_option("pdckl_purchase", get_option("easyad_purchase"));
        add_option("pdckl_title", 'Nechceš zde reklamu napořád jen za $price Kč?');
        add_option("pdckl_showform", 0);
        add_option("pdckl_links", get_option("easyad_links"));
        add_option("pdckl_price", get_option("easyad_price"));
        add_option("pdckl_price_extra", get_option("easyad_price_extra"));
        add_option("pdckl_wd_token", get_option("easyad_wd_token"));
        add_option("pdckl_api_userid", get_option("easyad_api_userid"));
        add_option("pdckl_api_password", get_option("easyad_api_password"));
        add_option("pdckl_api_signature", get_option("easyad_api_signature"));

        //Delete old options
        delete_option('easyad_db_version');
        delete_option('easyad_jquery');
        delete_option('easyad_active');
        delete_option('easyad_purchase');
        delete_option('easyad_links');
        delete_option('easyad_price');
        delete_option('easyad_price_extra');
        delete_option('easyad_wd_token');
        delete_option('easyad_api_userid');
        delete_option('easyad_api_password');
        delete_option('easyad_api_signature');

        //Delete
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}easyad_links");
      }
    }
}

function pdckl_header()
{
  $jquery_enabled = get_option('pdckl_jquery');
  $showform = get_option('pdckl_showform');

  if($jquery_enabled == 1) {
    wp_enqueue_script('jquery');

    if($showform == 0) {
      _e('
        <script>
          if(window.jQuery) {
            var sh = jQuery.noConflict();
            sh(function(){
              sh("#pdckl_gateway_form").hide();
            })
          } else {
            function sh() {
              var btn = document.getElementsByClassName("pdckl_showform_link");
              btn[0].style.display = "none";

              var el = document.getElementById("pdckl_gateway_form");
              el.style.display = "block";
            }
          }
        </script>
      ');
    }
  } else {
    if($showform == 0) {
      _e('
        <script>
          function sh() {
            var btn = document.getElementsByClassName("pdckl_showform_link");
            btn[0].style.display = "none";

            var el = document.getElementById("pdckl_gateway_form");
            el.style.display = "block";
          }
        </script>
      ');
    }
  }

  _e('
    <script>
      if(window.jQuery) {
        jQuery(function ($) {
          $("#pdckl_gateway_link").keyup(function() {
            $("#pdckl_display_link").attr("href", $(this).val());
            $("#pdckl_howitlooks").show();
          });
          $("#pdckl_gateway_title").keyup(function() {
            $("#pdckl_display_title").text($(this).val());
          });
          $("#pdckl_gateway_desc").keyup(function() {
            $("#pdckl_display_desc").text($(this).val());
          });
          $(".pdckl_showform_link").click(function() {
            $(".pdckl_showform_link").hide();
          });
        });
      }
    </script>
  ');
}

function pdckl_db_check()
{
    global $pdckl_db_version;
    if (get_site_option('pdckl_db_version') != $pdckl_db_version)
    {
        pdckl_db_install();
    }
}

function pdckl_curl_check()
{
   if  (in_array  ('curl', get_loaded_extensions()))
   {
      return true;
   }
   else
   {
      return false;
   }
}

function pdckl_active_check()
{
    global $pdckl_lang;
    $plugin_active = get_option('pdckl_active');
    if($plugin_active == 0)
    {
        _e('<div class="error"><p><strong>' . $pdckl_lang['n_plugin_disabled'] . '</strong></p></div>');
    }
}

function pdckl_gethash($core, $validate)
{
  if($core == '' && $validate != '') {
    $crypt = 'PHNwYW4gc3R5bGU9ImRpc3BsYXk6aW5saW5lLWJsb2NrOyB3aWR0aDppbmhlcml0OyB0ZXh0LWFsaWduOnJpZ2h0OyBtYXJnaW4tYm90dG9tOiAxMHB4OyI+Wmt1c3RlIDxhIGhyZWY9Imh0dHA6Ly93d3cuY29weXdyaXRpbmcuY3oiIHRhcmdldD0iX2JsYW5rIj5wb2TEjWzDoW5rb3ZvdSBpbnplcmNpPC9hPjwvc3Bhbj4=';
    $crypt = base64_decode($crypt);
    _e($crypt);
  }
}

function pdckl_admin_head()
{?>
    <link rel="stylesheet" type="text/css" href="<?php _e(plugins_url('assets/css/podclankova-inzerce_admin.min.css', __FILE__)); ?>">
    <link rel="stylesheet" type="text/css" href="<?php _e(plugins_url('assets/css/podclankova-inzerce.css', __FILE__)); ?>">
    <script type="text/javascript" src="<?php _e(plugins_url('webdeal_lib/Connect.js', __FILE__)); ?>"></script>
<?php
wp_enqueue_script('jquery');
 _e('
  <script type="text/javascript">
    jQuery(function ($) {
      $(".autofunction" ).click(function() {
        if($(this).val() == 1) {
          $("#manual").hide("slow");
        } else {
          $("#manual").show("slow");
        }
      });
    });
  </script>
 ');
}

function pdckl_admin_menu()
{
    add_options_page('Podčlánková inzerce', 'Podčlánková inzerce', 'manage_options', 'podclankova-inzerce', 'pdckl_content');
}

function pdckl_show_help($hid)
{
    global $pdckl_lang;
    return '<i class="dashicons dashicons-info tooltips" title="' . $pdckl_lang[$hid] . '"></i>';
}

function pdckl_paypal() {
  wp_enqueue_script('dg', '//www.paypalobjects.com/js/external/dg.js');
}

function pdckl_footer()
{
    if(get_option('pdckl_api_username') != '' && get_option('pdckl_api_password') != '' && get_option('pdckl_api_signature') != '') {
      add_action('wp_enqueue_scripts', 'pdckl_paypal');

      _e('
      <script src="//www.paypalobjects.com/js/external/dg.js" type="text/javascript"></script>
      <script>
      var dg = new PAYPAL.apps.DGFlow(
    	{
          trigger: "paypal_submit",
          expType: "instant"
          //PayPal will decide the experience type for the buyer based on his/her "Remember me on your computer" option.
      });
      </script>');
    }

    if(get_option('pdckl_jquery') == 1) {
      wp_enqueue_script('jquery');
    }
    wp_enqueue_script('wdconnect', plugins_url('webdeal_lib/Connect.js', __FILE__));
}

function pdckl_getPrice($origpostdate)
{
    $price_extra = explode(" ", get_option('pdckl_price_extra'));
    $parts = preg_split("(-| |:)", $origpostdate);

    $published_extra = date('d.m.Y G:i', mktime($parts[3], $parts[4], 0, $parts[1], $parts[2] + $price_extra[1], $parts[0]));

    if($price_extra[0] == 0)
        $price = get_option('pdckl_price');
    else
    {
        if(strtotime("now") > strtotime($published_extra))
            $price = $price_extra[0];
        else
            $price = get_option('pdckl_price');
    }
    return $price;
}

function pdckl_clearcache($post_id) {
  global $wpdb, $wp_query;

  $plugin_array = [];
  $plugins = get_option('active_plugins');
  foreach($plugins as $key => $value) {
    $string = explode('/',$value); // Folder name will be displayed
    $plugin_array[] = $string[0];
  }

  if(in_array('zencache', $plugin_array)) {
    $post = get_post($post_id);

    $e = explode('-', substr($post->post_date, 0, 10));
    $dd = $e[2];
    $mm = $e[1];
    $yy = $e[0];

    $url = str_replace('/plugins/podclankova-inzerce', '', dirname(__FILE__)).'/cache/zencache/cache/http/'.str_replace('.', '-', str_replace('https://', '', str_replace('http://', '', get_option('siteurl')))).'/'.$yy.'/'.$mm.'/'.$dd.'/';
    if(!is_dir($url)) {
      $url = str_replace('/plugins/podclankova-inzerce', '', dirname(__FILE__)).'/cache/zencache/cache/http/'.str_replace('.', '-', str_replace('https://', '', str_replace('http://', '', get_option('siteurl')))).'/'.$yy.'/'.$mm.'/*';
    } else {
      $url .= '/*';
    }

    $files = glob($url);

    if(is_array($files)) {
      foreach($files as $file){
        if(is_file($file))
          unlink($file);
      }
    }
  } elseif(in_array('comet-cache', $plugin_array)) {
    comet_cache::clearPost($post_id);
  } elseif(in_array('wp-super-cache', $plugin_array)) {
    if(function_exists('wp_cache_post_change')) {
      $GLOBALS["super_cache_enabled"]=1;
      wp_cache_post_change($post_id);
    }
  } elseif(in_array('wp-fastest-cache', $plugin_array)) {
    if(isset($GLOBALS['wp_fastest_cache']) && method_exists($GLOBALS['wp_fastest_cache'], 'singleDeleteCache')) {
      $GLOBALS['wp_fastest_cache']->singleDeleteCache(false, $post_id);
    }
  } elseif(in_array('w3-total-cache', $plugin_array)) {
    if (function_exists('w3tc_pgcache_flush_post')){
      w3tc_pgcache_flush_post($post_id);
    }
  }

  return true;
}

/**
 * Custom endpoint
 *
 */
add_action( 'init', 'pdckl_add_json_endpoint' );
function pdckl_add_json_endpoint() {
  add_rewrite_endpoint('pdckl', EP_ALL);
}

add_action('template_redirect', 'pdckl_json_template_redirect');
function pdckl_json_template_redirect() {
    global $wpdb, $wp_query, $post, $pdckl_db_version;

    if (!isset($wp_query->query_vars['pdckl']))
        return;

    switch($wp_query->query_vars['pdckl']) {
      case 'status':
        $price_extra = $price_extra_days = '';
        $epe = explode(' ', get_option('pdckl_price_extra'));
        if(isset($epe[0]) && isset($epe[1])) {
          $price_extra = $epe[0];
          $price_extra_days = $epe[1];
        }
        $array = [
          'maxlinks'    =>  get_option('pdckl_links'),
          'price'       =>  get_option('pdckl_price'),
          'price_extra' =>  $price_extra,
          'price_extra_days'  =>  $price_extra_days,
          'type'        =>  get_option('pdckl_type'),
          'paypal'      =>  get_option('pdckl_api_username') ? 1 : 0,
          'active'      =>  get_option('pdckl_active'),
          'version'     =>  $pdckl_db_version,
          'php'         =>  phpversion(),
        ];

        echo json_encode($array);
        exit;
      break;

      case 'pixel':
        print_r(get_option('pdckl_pixel'));
        exit;
      break;

      case 'plugins':
        $plugin_array = [];
        $plugins = get_option('active_plugins');
        foreach($plugins as $key => $value) {
          $string = explode('/',$value); // Folder name will be displayed
          echo $string[0] . '<br />';
        }
        exit;
      break;

      case 'clearcache':
        $id = (int) htmlspecialchars($_GET['id']);
        _e(pdckl_clearcache($id));
        exit;
      break;

      case 'checkout':
        if(get_option('WPLANG') == 'sk_SK') {
          require_once(dirname(__FILE__) . '/lang/sk_box.php');
        } else {
          require_once(dirname(__FILE__) . '/lang/cz_box.php');
        }

        $pdckl_id_post      = (int) htmlspecialchars($_POST['id_post']);
        $pdckl_gateway_link = htmlspecialchars($_POST['pdckl_gateway_link']);
        $pdckl_gateway_link_name = htmlspecialchars($_POST['pdckl_gateway_link_name']);
        $pdckl_gateway_desc = htmlspecialchars($_POST['pdckl_gateway_desc']);

        if(get_option('pdckl_type') == 'both') {
          $pdckl_gateway_type = htmlspecialchars($_POST['pdckl_gateway_type']) == 'nofollow' ? 'rel="nofollow"' : '';
        } elseif(get_option('pdckl_type') == 'nofollow') {
          $pdckl_gateway_type = 'rel="nofollow"';
        } else {
          $pdckl_gateway_type = '';
        }

        $pdckl_gateway_flink = '<a href="' . $pdckl_gateway_link . '" ' . $pdckl_gateway_type . '>' . $pdckl_gateway_link_name . '</a> - ' . $pdckl_gateway_desc;
        $pdckl_gateway_flink = base64_encode($pdckl_gateway_flink);
        $origpostdate = get_post($pdckl_id_post);
        $post_title   = $origpostdate->post_title;
        $origpostdate = $origpostdate->post_date;
        $price        = pdckl_getPrice($origpostdate);

        if($pdckl_gateway_link != "" and $pdckl_gateway_link_name != "" and $pdckl_gateway_desc != "")
        {
            if(isset($_POST['wd_submit']))
            {
                $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
                $return_url = explode("?", $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
                $request = [
                    'token'      =>  get_option('pdckl_wd_token'),
                    'post_id'    =>  $pdckl_id_post,
                    'post_title' =>  $post_title,
                    'post_price' =>  $price,
                    'post_link'  =>  $_POST['url_post'],
                    'link'       =>  $pdckl_gateway_flink,
                    'pixel'      =>  get_option('pdckl_pixel') != '' ? get_option('pdckl_pixel') : '',
                    'return_url' =>  base64_encode($return_url[0])
                ];
                $request = json_encode($request);

                $ch = curl_init('https://api.copywriting.cz/podclankova-inzerce/v3/checkout.php?mode=data');
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($request)
                ]);

                $result = curl_exec($ch);
                curl_close($ch);

                $json = json_decode($result);

                if($json->status && isset($json->token_order)) {
                    header("Location: https://api.copywriting.cz/podclankova-inzerce/v3/checkout.php?mode=login&token_order=" . $json->token_order);
                } else {
                    _e ('
                        <html>
                        <head>
                          <title>'.$box_lang['order_error_title'].'</title>
                          <meta http-equiv="content-type" content="text/html; charset=utf-8">
                        </head>
                        <body style="padding: 0; margin: 0; font-family: Verdana; text-align: center;">
                          <div style="background: #f2dede; border: 1px solid #ebccd1; color: #a94442; padding: 10px;">'.$box_lang['order_error'].'</div><br />
                          <div><a href="#" onclick="window.close()" style="padding: 6px; background: #de605d; border: 1px solid #ba514e; color: #fff; border-radius: 3px; text-decoration: none;">'.$box_lang['order_close'].'</a></div>
                          <div style="color: #ccc; font-size: 11px; position: fixed; bottom: 10px;">'.(isset($json->error) && isset($box_lang['order_error_'.$json->error]) ? $box_lang['order_error_'.$json->error] : '').'</span>
                        </body>
                        </html>
                   ');
                }
            } elseif(isset($_POST['cd_submit'])) {
                $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
                $return_url = explode("?", $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
                $request = [
                               'token'      =>  get_option('pdckl_wd_token'),
                               'post_id'    =>  $pdckl_id_post,
                               'post_title' =>  $post_title,
                               'post_price' =>  $price,
                               'post_link'  =>  $_POST['url_post'],
                               'link'       =>  base64_decode($pdckl_gateway_flink),
                               'pixel'      =>  get_option('pdckl_pixel') != '' ? get_option('pdckl_pixel') : '',
                           ];
                $request = json_encode($request);

                $ch = curl_init('https://api.copywriting.cz/podclankova-inzerce/v3/card.php?mode=data');
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($request)
                ]);

                $result = curl_exec($ch);
                curl_close($ch);

                $json = json_decode($result);

                header("Location: https://api.copywriting.cz/podclankova-inzerce/v3/card.php?mode=overview");
                exit;
            } else {
                SetCookie("pdckl_id_post", $pdckl_id_post);
                SetCookie("pdckl_gateway_flink", $pdckl_gateway_flink);

                $item_name = "Reklama na webu " . get_bloginfo('wpurl');

                include('paypal_lib/checkout.php');
            }
        } else {
            _e ('
                <html>
                <head>
                    <title>'.str_replace('!', '', $box_lang['order_required_inputs']).'</title>
                    <meta http-equiv="content-type" content="text/html; charset=utf-8">
                </head>
                <body style="padding: 0; margin: 0; font-family: Verdana; text-align: center;">
                    <div style="background: #f2dede; border: 1px solid #ebccd1; color: #a94442; padding: 10px;">'.$box_lang['order_required_inputs'].'</div><br />
                    <div><a href="#" onclick="window.close()" style="padding: 6px; background: #de605d; border: 1px solid #ba514e; color: #fff; border-radius: 3px; text-decoration: none;">'.$box_lang['order_close'].'</a></div>
                </body>
                </html>
            ');
        }
      break;

      case 'wd_confirm':
        $json = json_decode(file_get_contents("php://input"));

        if(isset($json->post_id) && isset($json->link) && isset($json->token))
        {
            if($json->token == get_option('pdckl_wd_token'))
            {
                if(!$wpdb->get_results('SELECT * FROM ' .$wpdb->term_relationships. ' WHERE object_id = ' . $json->post_id . ' AND term_taxonomy_id IN("'.get_option('pdckl_banned_cats').'")', ARRAY_A)) {
                  $table_name = $wpdb->prefix . "pdckl_links";
                  $inserted   = $wpdb->insert($table_name, [
                      'id_post' =>  $json->post_id,
                      'time'    =>  time(),
                      'link'    =>  base64_decode($json->link),
                      'active'  =>  1
                  ]);

                  $lastid = $wpdb->insert_id;

                  $cache = pdckl_clearcache($json->post_id);

                  if($inserted) {
                    echo json_encode(['status'  =>  'inserted', 'dbid' =>  $lastid]);
                    exit;
                  } else {
                    echo json_encode(['status'  =>  'non_inserted']);
                    exit;
                  }
                } else {
                  echo json_encode(['status'  =>  'banned_cat']);
                  exit;
                }
            } else {
              echo json_encode(['status'  =>  'bad_token']);
              exit;
            }
        } else {
          echo json_encode(['status'  =>  'mising_parameters']);
          exit;
        }
      break;

      case 'pp_confirm':
        include('paypal_lib/orderconfirm.php');
      break;

      case 'pp_success':
        if(get_option('WPLANG') == 'sk_SK') {
          require_once(dirname(__FILE__) . '/lang/sk_box.php');
        } else {
          require_once(dirname(__FILE__) . '/lang/cz_box.php');
        }

        _e('
          <html>
            <head>
              <meta http-equiv="content-type" content="text/html; charset=utf-8">
            </head>
            <body style="padding: 0; margin: 0; font-family: Verdana; text-align: center;">
              <div style="background: #d3f2ca; border: 1px solid #ebccd1; color: #4a9e45; padding: 10px;">Platba proběhla úspěšně! Nyní zavřete toto okno a aktualizujte stránku.'.$box_lang['order_success'].'</div><br />
              <div><a href="#" onclick="window.close()" style="padding: 6px; background: #59be53; border: 1px solid #4ea749; color: #fff; border-radius: 3px; text-decoration: none;">'.$box_lang['order_close'].'</a></div>
            </body>
          </html>
        ');
      break;

      case 'pp_canceled':
        if(get_option('WPLANG') == 'sk_SK') {
          require_once(dirname(__FILE__) . '/lang/sk_box.php');
        } else {
          require_once(dirname(__FILE__) . '/lang/cz_box.php');
        }

        _e ('
            <html>
            <head>
            <meta http-equiv="content-type" content="text/html; charset=utf-8">
            </head>
            <body style="padding: 0; margin: 0; font-family: Verdana; text-align: center;">
              <div style="background: #f2dede; border: 1px solid #ebccd1; color: #a94442; padding: 10px;">'.$box_lang['order_cancelled'].'</div><br />
              <div><a href="#" onclick="window.close()" style="padding: 6px; background: #de605d; border: 1px solid #ba514e; color: #fff; border-radius: 3px; text-decoration: none;">'.$box_lang['order_close'].'</a></div>
            </body>
            </html>
        ');
      break;

      case 'edit':
        $json = json_decode(file_get_contents("php://input"));

        if(get_option('pdckl_wd_token') != $json->token)
        {
          echo json_encode(['status' => false]);
          exit;
        }
      break;

      case 'links':
        $json = json_decode(file_get_contents("php://input"));

        if(get_option('pdckl_wd_token') != $json->token)
        {
          echo json_encode(['status' => false, 'message' =>  'NO_TOKEN_MATCH']);
          exit;
        }

        $action   = isset($json->action)   ? $json->action : '';
        $dbid   = isset($json->dbid)   ? $json->dbid : 0;
        $link   = isset($json->link)   ? html_entity_decode($json->link, ENT_COMPAT, 'UTF-8') : '';
        $type   = isset($json->type)   ? $json->type : '';

        switch($action) {
          case 'edit':
            if($link) {
              $wpdb->update($wpdb->prefix . 'pdckl_links', ['link' =>  $link], ['id'  =>  intval($dbid)]);
              //$wpdb->query('UPDATE ' . $wpdb->prefix . 'pdckl_links SET link = "'.$link.'" WHERE id = '.intval($dbid));
            }

            if($type) {
              $wpdb->query('UPDATE ' . $wpdb->prefix . 'pdckl_links SET type = "'.$type.'" WHERE id = '.intval($dbid));
            }

            echo json_encode(['status' => true]);
            exit;
          break;

          case 'show':
            $wpdb->query("UPDATE " . $wpdb->prefix . "pdckl_links SET active = 1 WHERE id = ".intval($dbid));

            echo json_encode(['status' => true]);
            exit;
          break;

          case 'hide':
            $wpdb->query("UPDATE " . $wpdb->prefix . "pdckl_links SET active = 0 WHERE id = ".intval($dbid));

            echo json_encode(['status' => true]);
            exit;
          break;

          case 'delete':
            $wpdb->query("DELETE " . $wpdb->prefix . "pdckl_links WHERE id = ".intval($dbid));

            echo json_encode(['status' => true]);
            exit;
          break;
        }

        echo json_encode(['status' => false, 'message' =>  'NO_ACTION']);
        exit;
      break;

      case 'backup_create':
        $json = json_decode(file_get_contents("php://input"));

        if(get_option('pdckl_wd_token') != $json->token)
        {
          echo json_encode(['status' => false]);
          exit;
        }

        $links = $wpdb->get_results('SELECT * FROM ' .$wpdb->prefix. 'pdckl_links ORDER BY ID ASC', ARRAY_A);
        $response = [];
        foreach($links as $link)
        {
            $response[] = [
                'id'      => $link['id'],
                'id_post' => $link['id_post'],
                'time'    => $link['time'],
                'link'    => htmlspecialchars($link['link']),
                'active'  => $link['active'],
            ];
        }
        echo json_encode($response);
        exit;
      break;

      case 'backup_load':
        $json = json_decode(file_get_contents("php://input"));

        if(get_option('pdckl_wd_token') != $json->token)
        {
          echo json_encode(['status' => false]);
          exit;
        }

        $links = json_decode($json->links);

        foreach($links as $link) {
          $sql_link = $wpdb->get_results("SELECT * FROM " .$wpdb->prefix. "pdckl_links WHERE id = " . sanitize_text_field($link->id) . " AND time = " . sanitize_text_field($link->time) . " ORDER BY ID ASC", ARRAY_A);

          if(empty($sql_link)) {
            $wpdb->insert($wpdb->prefix . "pdckl_links", [
              'id' => $link->id,
              'id_post' => $link->id_post,
              'time' => $link->time,
              'link' => html_entity_decode($link->link),
              'active' => $link->active
            ]);
          }
        }

        echo json_encode(['status' =>  true]);
        exit;
      break;

      // Slouží na automatické vložení tokenu systémem
      case 'token':
        $json = json_decode(file_get_contents("php://input"));

        if(get_option('pdckl_wd_token') != '' && isset($json->old_token) && $json->old_token != get_option('pdckl_wd_token')) {
          echo json_encode(array('status' => false, 'message' =>  'NO_NULL'));
          exit;
        } elseif(!isset($json->token)) {
          echo json_encode(array('status' => false, 'message' =>  'NO_TOKEN'));
          exit;
        }

        update_option('pdckl_wd_token', $json->token);

        echo json_encode(array('status' => true, 'message' =>  'OK'));
        exit;
      break;

      // Slouží pro ověření tokenu
      case 'ping_token':
        $json = json_decode(file_get_contents("php://input"));

        if(get_option('pdckl_wd_token') == $json->token)
          echo json_encode(array('status' => true));
        else
          echo json_encode(array('status' => false));
        exit;
      break;

      case 'ping_position':
        $json = json_decode(file_get_contents("php://input"));

        if(get_option('pdckl_wd_token') != $json->token)
        {
            echo json_encode(array('status' => false));
            exit;
        }

        $count = $wpdb->get_var("SELECT COUNT(*) FROM " . $wpdb->prefix . "pdckl_links WHERE id_post = " . $json->post_id);
        if($count < get_option('pdckl_links')) {
          echo json_encode(array(
            'status' => true,
            'price'  => 0, //TODO
          ));
        } else {
          echo json_encode(array('status' => false));
        }
        exit;
      break;

      case 'ping_price':
        $json = json_decode(file_get_contents("php://input"));

        if(get_option('pdckl_wd_token') != $json->token)
        {
            echo json_encode(array('status' => false));
            exit;
        } elseif(!is_int($json->id)) {
            echo json_encode(array('status' => false));
            exit;
        }

        $post_date = $wpdb->get_var("SELECT post_date FROM " . $wpdb->posts . " WHERE ID = " . $json->id);
        echo json_encode(array('price' => pdckl_getPrice($post_date)));
        exit;
      break;

      case 'ping_latest':
        $json = json_decode(file_get_contents("php://input"));

        if(get_option('pdckl_wd_token') != $json->token)
        {
            echo json_encode(array('status' => false));
            exit;
        }

        $limit = $json->limit == 0 ? '' : ' LIMIT ' . $json->limit;
        $links = $wpdb->get_results('SELECT * FROM ' .$wpdb->posts. ' WHERE post_type = "post" AND post_status = "publish" ORDER BY ID DESC' . $limit, ARRAY_A);
        $response = array('posts' => array());

        foreach($links as $link)
        {
          if(!$wpdb->get_results('SELECT * FROM ' .$wpdb->term_relationships. ' WHERE object_id = ' . $link['ID'] . ' AND term_taxonomy_id IN("'.get_option('pdckl_banned_cats').'")', ARRAY_A))
            $response['posts'][] = array(
                'id'    => $link['ID'],
                'title' => $link['post_title'],
                'date'  => $link['post_date'],
                'price' => pdckl_getPrice($link['post_date'])
            );
        }
        echo json_encode($response);
        exit;
      break;
    }
    exit;
}

function pdckl_box_show($content)
{
  global $wpdb;

  if(get_option("pdckl_auto")) {
    if (is_single()) {
      $content .= include(PDCKL_PLUGIN_DIR . 'gateway.php');
      return $content;
    } else {
      return $content;
    }
  } else {
    return $content;
  }
}

add_filter("the_content", "pdckl_box_show");

function podclankova_inzerce_box()
{
  global $wpdb;

  if(get_option("pdckl_auto") == 0) {
    return include(PDCKL_PLUGIN_DIR . 'gateway.php');
  }
}

//Fix, pokud má někdo ve stylech ještě starý pozůstatek skriptu, tak ať to nevyhazuje chybu
if(!function_exists('easyad_box_show')) {
  function easyad_box_show() {}
}
?>
