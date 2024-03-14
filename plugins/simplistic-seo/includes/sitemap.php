<?php

// SITEMAP
//-----------------------------------------------------------------------

function sseo_generate_sitemap($is_initial)
{
  $build_sitemap = false;
  $existing_sitemap = ABSPATH . "sitemap.xml";

  $is_multidomain = get_option('sseo_activate_sitemap_multidomain');
  $domains = sseo_get_multi_domains();

  if ($is_initial && !file_exists($existing_sitemap)) {
    $build_sitemap = true;
  }
  if (!$is_initial) {
    $build_sitemap = true;
  }
  if ($build_sitemap) {
    $sitemap = '';

    foreach ($domains as $domain) {
      $domains_sitemap[$domain] = '';
    }

    if (str_replace('-', '', get_option('gmt_offset')) < 10) {
      $tempo = '-0' . str_replace('-', '', get_option('gmt_offset'));
    } else {
      $tempo = get_option('gmt_offset');
    }

    if (strlen($tempo) == 3) {
      $tempo = $tempo . ':00';
    }

    $postsForSitemap = get_posts(array('numberposts' => -1, 'orderby' => 'modified', 'post_type' => 'any', 'order' => 'DESC'));
    $sitemap .= '<?xml version="1.0" encoding="UTF-8"?>';


    // Multidomain sitemap-{lang}.xml
    foreach ($domains as $domain) {
      $domains_sitemap[$domain] .= '<?xml version="1.0" encoding="UTF-8"?>';
    }


    // Main sitemap.xml
    $sitemap .= "\n" . '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    $sitemap .= "\t" . '<url>' . "\n" .
      "\t\t" . '<loc>' . esc_url(home_url('/')) . '</loc>' .
      "\n\t\t" . '<lastmod>' . date("Y-m-d\TH:i:s", current_time('timestamp', 0)) . $tempo . '</lastmod>' .
      "\n\t" . '</url>' . "\n";

    // Multidomain sitemap-{lang}.xml
    foreach ($domains as $domain) {
      $domains_sitemap[$domain] .= "\n" . '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
        xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
      $domains_sitemap[$domain] .= "\t" . '<url>' . "\n" .
        "\t\t" . '<loc>' . esc_url(home_url('/')) . '</loc>' .
        "\n\t\t" . '<lastmod>' . date("Y-m-d\TH:i:s", current_time('timestamp', 0)) . $tempo . '</lastmod>' .
        "\n\t" . '</url>' . "\n";
    }

    $excluded_pages = get_option('sseo_sitemap_exclude');
    $excluded_pages_parsed = json_decode($excluded_pages);

    foreach ($postsForSitemap as $post) {
      $option_name = 'sseo_activate_type_sitemap_' . $post->post_type;
      if(!checked(1, get_option($option_name), false)){
        continue;
      }
    

      setup_postdata($post);

      $post_type_object = get_post_type_object($post->post_type);

      $publicly_queryable = $post_type_object->publicly_queryable;

      $sitemap .= "\t" . $post->title . "\n";

      // Multidomain sitemap-{lang}.xml
      foreach ($domains as $domain) {
        $domains_sitemap[$domain] .= "\t" . $post->title . "\n";
      }


      if ((($excluded_pages_parsed === null || !in_array(get_the_title($post->ID), $excluded_pages_parsed)) &&
          $post->post_type ===
          "page") ||
        $publicly_queryable
      ) {


        $postdate = explode(" ", $post->post_modified);
        $sitemap .= "\t" . '<url>' . "\n" .
          "\t\t" . '<loc>' . get_permalink($post->ID) . '</loc>' .
          "\n\t\t" . '<lastmod>' . $postdate[0] . 'T' . $postdate[1] . $tempo . '</lastmod>' .
          "\n\t" . '</url>' . "\n";


        // Multidomain sitemap-{lang}.xml
        foreach ($domains as $domain) {

          $url_host = end(explode(".",parse_url(get_permalink($post->ID), PHP_URL_HOST)));
          if ($url_host != $domain){
            continue;
          }

          $domains_sitemap[$domain] .= "\t" . '<url>' . "\n" .
            "\t\t" . '<loc>' . get_permalink($post->ID) . '</loc>' .
            "\n\t\t" . '<lastmod>' . $postdate[0] . 'T' . $postdate[1] . $tempo . '</lastmod>' .
            "\n\t" . '</url>' . "\n";
        }
      }
    }
  

    $termsForSitemap = get_terms();

    foreach ($termsForSitemap as $term) {

      $option_name_category = 'sseo_activate_type_categories_sitemap_' . $term->taxonomy;

      if(!checked(1, get_option($option_name_category), false)){
        continue;
      }
   
      $term_link = get_term_link($term);
    
      if (is_wp_error($term_link)) {
        continue;
      }

      $sitemap .= "\t" . '<url>' . "\n" .
        "\t\t" . '<loc>' . esc_url($term_link) . '</loc>' .
        "\n\t\t" . '<lastmod>' . date("Y-m-d\TH:i:s", current_time('timestamp', 0)) . $tempo . '</lastmod>' .
        "\n\t" . '</url>' . "\n";

      // Multidomain sitemap-{lang}.xml
      foreach ($domains as $domain) {

        $url_host = end(explode(".",parse_url(esc_url($term_link), PHP_URL_HOST)));
        if ($url_host !== $domain){
          continue;
        }

        $domains_sitemap[$domain] .= "\t" . '<url>' . "\n" .
          "\t\t" . '<loc>' . esc_url($term_link) . '</loc>' .
          "\n\t\t" . '<lastmod>' . date("Y-m-d\TH:i:s", current_time('timestamp', 0)) . $tempo . '</lastmod>' .
          "\n\t" . '</url>' . "\n";
      }
    }


    $sitemap .= "</urlset>";

    foreach ($domains as $domain) {
      $domains_sitemap[$domain] .= "</urlset>";
    }


    $fp = fopen(ABSPATH . "sitemap.xml", 'w');
    fwrite($fp, $sitemap);
    fclose($fp);

    foreach ($domains as $domain) {
      $fp = fopen(ABSPATH . "sitemap-" . $domain . ".xml", 'w');
      fwrite($fp, $domains_sitemap[$domain]);
      fclose($fp);
    }
  }
}


function sseo_get_multi_domains(){
  $is_multidomain = get_option('sseo_activate_sitemap_multidomain');
  if ($is_multidomain) {
    $is_multidomain_domains = get_option('sseo_activate_sitemap_multidomain_domains');
    return preg_split('/\s+/', $is_multidomain_domains);
  }
  return [];
}

function sseo_delete_sitemap()
{
  if (file_exists(ABSPATH . "sitemap.xml")) {
    unlink(ABSPATH . "sitemap.xml");
  }

  $domains = sseo_get_multi_domains();
  foreach($domains as $domain){
    if (file_exists(ABSPATH . "sitemap-" . $domain . ".xml")) { 
      unlink(ABSPATH . "sitemap-" . $domain . ".xml");
    }

  
  }

}

$option_name = 'sseo_activate_sitemap';

add_action('admin_init', function ($option_name) {
  if (is_admin()) {
    $sitemapactivated = esc_attr(get_option('sseo_activate_sitemap'));
    // Generate or delete sitemap, depending on settings
    if ($sitemapactivated) {
      sseo_generate_sitemap(true);
    } else {
      sseo_delete_sitemap();
    }
  }
}, 10, 2);

add_action('save_post', function () {

  $sitemapactivated = esc_attr(get_option('sseo_activate_sitemap'));

  // Generate or delete sitemap, depending on settings
  if ($sitemapactivated) {
    sseo_generate_sitemap(false);
  } else {
    sseo_delete_sitemap();

  }
});

add_action('admin_menu', 'test');

function test()
{


  if (isset($_GET) && array_key_exists('page', $_GET) && $_GET['page'] === "seo_settings")

    sseo_generate_sitemap(false);
}
?>