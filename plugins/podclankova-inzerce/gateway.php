<?php
  $pdckl_action = htmlspecialchars($_GET['a']);

  global $wpdb, $post, $box_lang;
  if(get_option('WPLANG') ==  'sk_SK') {
    require_once(dirname(__FILE__) . '/lang/sk_box.php');
  } else {
    require_once(dirname(__FILE__) . '/lang/cz_box.php');
  }

  $pdckl_auto = get_option('pdckl_auto');
  $pdckl_categories = get_the_category($post->ID);
  $post_categories = [];
  foreach($pdckl_categories as $category => $item) {
    $post_categories[] = $item->term_id;
  }
  $pdckl_banned_cats = get_option('pdckl_banned_cats');
  $pdckl_banned_cats_array = strpos($pdckl_banned_cats, ',') ? explode(',', $pdckl_banned_cats) : [0 => $pdckl_banned_cats];
  $pdckl_show = array_intersect($post_categories, $pdckl_banned_cats_array);
  if(empty($pdckl_show)) {
    $pdckl_show = true;
  } else {
    $pdckl_show = false;
  }

  $pdckl_id_post = (int) htmlspecialchars($_POST['id_post'] ?? 0);
  $pdckl_gateway_link = htmlspecialchars($_POST['pdckl_gateway_link'] ?? '');
  $pdckl_gateway_link_name = htmlspecialchars($_POST['pdckl_gateway_link_name'] ?? '');
  $pdckl_gateway_desc = htmlspecialchars($_POST['pdckl_gateway_desc'] ?? '');
  $pdckl_gateway_type = htmlspecialchars($_POST['pdckl_gateway_type'] ?? '');
  $pdckl_gateway_flink = '<a href="' . $pdckl_gateway_link . '">' . $pdckl_gateway_link_name . '</a> - ' . $pdckl_gateway_desc;
  $pdckl_gateway_flink = base64_encode($pdckl_gateway_flink);
  $pdckl_title = get_option('pdckl_title');

  $price_extra = explode(" ", get_option('pdckl_price_extra'));

  $origpostdate = get_post($pdckl_id_post);
  $post_title = $origpostdate->post_title;
  $origpostdate = $origpostdate->post_date;

  $parts = preg_split("(-| |:)", $origpostdate);

  $published_extra = @date('d.m.Y G:i', mktime($parts[3], $parts[4], 0, $parts[1], $parts[2] + $price_extra[1], $parts[0]));

  if($price_extra[0] == 0) {
    $price = get_option('pdckl_price');
  } else {
    if(strtotime("now") > strtotime($published_extra)) {
      $price = $price_extra[0];
    } else {
      $price = get_option('pdckl_price');
    }
  }

  $gateway = '';
  if(get_option("pdckl_active") == 1 && $pdckl_show)
  {
      if($pdckl_auto) {
        $gateway .= '<hr />';
      }
      $gateway .= '
      <div class="pdckl_box">
        <div class="pdckl_links">
          <ul>';
              global $wpdb;
              $limit = get_option('pdckl_links');
              $total_links = 0;
              $links = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "pdckl_links WHERE id_post = " . (int) get_the_ID() . " AND active = 1 ORDER BY id ASC LIMIT " . $limit, ARRAY_A);

              foreach($links as $link):
                $link_data = $link["link"];

                $gateway .= '<li>' . $link_data . '</li>';
                $total_links++;
              endforeach;
      $gateway .= '
          </ul>
        </div>';

          if(get_option("pdckl_purchase") == 1)
          {
            if($total_links < $limit) {
              include(dirname(__FILE__) . '/box.php');
            }
          }
        pdckl_gethash($wdbutton, $ppbutton);
    $gateway .= '
    </div>';
  }
  return $gateway;
?>
