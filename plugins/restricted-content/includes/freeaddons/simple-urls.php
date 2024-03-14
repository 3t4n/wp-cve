<?php
if(!in_array( 'simple-urls/plugin.php', get_option('active_plugins'))) {//Make sure that plugin is active
  return;
}


add_filter('rc_settings_new_sections', 'rsc_add_new_admin_section_for_simple_urls', 10, 1);

function rsc_add_new_admin_section_for_simple_urls($sections){

  $sections['simple_urls'] = array(
    array(
      'name' => 'simple_urls_options',
      'title' => __('Simple URLs', 'rsc'),
      'subtitle' => '',
      'header' => __('Set the type or redirection and redirection location for URLs defined in the Simple URL plugin.', 'rsc'),
      'footer' => '',
      'description' => '',
      'has_save_button' => true,
    ),
  );
  return $sections;
}

add_filter('rsc_settings_store_fields', 'rsc_add_admin_simple_urls_fields', 10, 1);

function rsc_add_admin_simple_urls_fields($fields){
  $new_fields = array();

  $get_pages = get_pages( 'hide_empty=0' );
  foreach ( $get_pages as $page ) {
    $pages_array[$page->ID] = esc_attr( $page->post_title );
  }

  $new_fields[] = array(
    'field_name' => 'rsc_simple_urls_type',
    'field_title' => __('Redirection type', 'rsc'),
    'field_type' => 'select',
    'values' => array(
      'page' => 'Page',
      'url' => 'URL'
    ),
    'default_value' => 'url',
    'tooltip' => __('Select a type of redirection', 'rsc'),
    'section' => 'simple_urls_options',
  );

  $new_fields[] = array(
    'field_name' => 'rsc_simple_urls_redirection_page',
    'field_title' => __('Redirection Page', 'rsc'),
    'field_type' => 'select',
    'values' => $pages_array,
    'default_value' => '',
    'tooltip' => __('Set a page to which the users will be redirected if the URL is restricted.', 'rsc'),
    'section' => 'simple_urls_options',
    'conditional' => array(
        'field_name' => 'rsc_simple_urls_type',
        'field_type' => 'select',
        'value' => 'url',
        'action' => 'hide'
    )
  );

  $new_fields[] = array(
    'field_name' => 'rsc_simple_urls_redirection_url',
    'field_title' => __('Redirection URL', 'rsc'),
    'field_type' => 'text',
    'values' => '',
    'default_value' => site_url(),
    'tooltip' => __('Set an URL to which the users will be redirected if the URL is restricted.', 'rsc'),
    'section' => 'simple_urls_options',
    'conditional' => array(
        'field_name' => 'rsc_simple_urls_type',
        'field_type' => 'select',
        'value' => 'page',
        'action' => 'hide'
    )
  );

  $fields = array_merge($fields, $new_fields);
  return $fields;
}

add_action('rc_settings_new_menus', 'rsc_add_simple_urls_menu_item', 10, 1);

function rsc_add_simple_urls_menu_item($menus){
  $menus['simple_urls'] = __('Simple URLs', 'rsc');
  return $menus;
}

add_filter('simple_urls_redirect_url', 'rsc_maybe_restrict_url', 10, 1);

function rsc_maybe_restrict_url($redirect){
  global $wp_query;
  $id = $wp_query->post->ID;

  $rsc_content_availability = get_post_meta($id, '_rsc_content_availability', true);

  if (empty($rsc_content_availability)) {
    $rsc_content_availability = 'everyone';
  }

  $rsc_content_availability = apply_filters('rsc_content_availability', $rsc_content_availability, $id);

  if ($rsc_content_availability !== 'everyone') {
    $value_array = get_post_meta($id);
    $value_array['id'] = $id;

    if(!Restricted_Content::can_access($value_array)){
      $rsc_settings = get_option('rsc_settings');

      $restriction_type = isset($rsc_settings['rsc_simple_urls_type']) ? $rsc_settings['rsc_simple_urls_type'] : 'url';
      $restriction_url = isset($rsc_settings['rsc_simple_urls_redirection_url']) && !empty($rsc_settings['rsc_simple_urls_redirection_url']) ? $rsc_settings['rsc_simple_urls_redirection_url'] : site_url();
      $restriction_page = isset($rsc_settings['rsc_simple_urls_redirection_page']) && !empty($rsc_settings['rsc_simple_urls_redirection_page']) ? (int)$rsc_settings['rsc_simple_urls_redirection_page'] : 0;

      if($restriction_type == 'url'){
        $redirect = $restriction_url;
      }else{
        $redirect = get_permalink($restriction_page);
      }
    }
  }

  return $redirect;
}

?>
