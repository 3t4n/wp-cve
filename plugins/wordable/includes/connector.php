<?php

class WordablePluginConnector {
  private $wordable_plugin;

  function __construct($wordable_plugin) {
    $this->wordable_plugin = $wordable_plugin;
  }

  function query_string() {
    $query_string_parts = array(
      'destination[external_id]=' . $this->wordable_plugin->secret(),
      'destination[url]=' . urlencode(get_site_url()),
      'destination[admin_url]=' . urlencode(admin_url()),
      'plugin_version=' . WORDABLE_VERSION,
      'wordpress_version=' . get_bloginfo('version')
    );

    return implode('&', $query_string_parts);
  }

  function destination_meta() {
    return array(
      'plugin_version' => WORDABLE_VERSION,
      'wordpress_version' => get_bloginfo('version'),
      'authors' => $this->serialized_authors(),
      'categories' => $this->serialized_categories(),
      'post_types' => $this->serialized_post_types(),
      'admin_url' => urlencode(admin_url()),
      'system_report' => $this->wordable_plugin->system_report(),
      'max_upload_size' => wp_max_upload_size()
    );
  }

  function serialized_authors() {
    $serialized_authors = array();

    foreach ($this->wordable_plugin->authors() as $author) {
      if ($author->user_login == "") {
        continue;
      }

      if($author->display_name) {
        array_push($serialized_authors, "$author->ID:$author->display_name");
      } else {
        array_push($serialized_authors, "$author->ID:$author->user_login");
      }
    }

    return implode(',', $serialized_authors);
  }

  function serialized_categories() {
    $serialized_categories = array();

    foreach ($this->wordable_plugin->categories() as $category) {
      array_push($serialized_categories, "$category->term_id:$category->name:$category->parent");
    }

    return implode(',', $serialized_categories);
  }

  function serialized_post_types() {
    $post_types = get_post_types();
    $ignored_post_types = array('attachment', 'wp_block', 'feedback', 'jp_pay_order', 'jp_pay_product', 'post', 'revision', 'nav_menu_item', 'custom_css', 'customize_changeset', 'oembed_cache', 'user_request', 'jp_mem_plan');
    $post_types = array_diff($post_types, $ignored_post_types);

    return implode(',', $post_types);
  }
}
