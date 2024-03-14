<?php


class fmcAgents extends fmcWidget {

  function __construct() {
    global $fmc_widgets;

    $widget_info = $fmc_widgets[ get_class($this) ];

    $widget_ops = array( 'description' => $widget_info['description'] );
    add_shortcode($widget_info['shortcode'], array(&$this, 'shortcode'));

    // register where the AJAX calls should be routed when they come in
    add_action('wp_ajax_'.get_class($this).'_shortcode', array(&$this, 'shortcode_form') );
    add_action('wp_ajax_'.get_class($this).'_shortcode_gen', array(&$this, 'shortcode_generate') );
    add_action('wp_ajax_nopriv_'.get_class($this).'_shortcode_gen', array(&$this, 'shortcode_generate') );

  }


  function jelly($args, $settings, $type) {
    global $fmc_api;
    global $fmc_plugin_url;

    extract($args);
    $custom_page = new flexmlsConnectAgentSearchResults($fmc_api);
    $custom_page->settings = $settings;
    $custom_page->input_source = 'shortcode';
    $custom_page->pre_tasks(null);
    return $custom_page->generate_page(true);

  }


  function widget($args, $instance) {
    echo $this->jelly($args, $instance, "widget");
  }


  function shortcode($attr = array()) {

    $args = array(
        'before_title' => '<h3>',
        'after_title' => '</h3>',
        'before_widget' => '',
        'after_widget' => ''
        );
    return $this->jelly($args, $attr, "shortcode");
  }

    function integration_view_vars(){
      global $fmc_api;
      $api_my_account = $fmc_api->GetMyAccount();

      $vars = array();
  
      $vars['title'] = '';
      $vars['search'] = array(
        'on' => 'Yes',
        'off' => 'No'
      );
      $vars['api_my_account'] = $api_my_account;
      if ($api_my_account['UserType'] != 'Office'){
        $vars['search_type'] = array(
          'offices' => 'Offices',
          'agents' => 'Agents'
        );
      } else {
        $vars['search_type'] = array();
      }  
      return $vars;
    }


  function settings_form($instance) {
    global $fmc_api;
    $api_my_account = $fmc_api->GetMyAccount();
    $title = array_key_exists('title', $instance) ? esc_attr($instance['title']) : null;

    $selected_code = " selected='selected'";
    $checked_code = " checked='checked'";
    $return = "<p><label for='".$this->get_field_id('title')."'>" . __('Title:') . "</label>\n";
    $return .= "<input fmc-field='title' fmc-type='text' type='text' class='widefat' id='".$this->get_field_id('title')."' name='".$this->get_field_name('title')."' value='{$title}'>\n</p>";

    $return .= "<p><label> Show Search: </label>";
    $return .= "<select fmc-type=select name=search fmc-field='search'>";
    $return .= "<option value=true>Yes</option>";
    $return .= "<option value=false>No</option>";
    $return .= "</select><br />";

    if ($api_my_account['UserType'] != 'Office'){
      $return .= "<label> Show Offices or Agents by default: </label>";
      $return .= "<select fmc-type=select name=search_type fmc-field='search_type'>";
      $return .= "<option value=offices>Offices</option>";
      $return .= "<option value=agents>Agents</option>";
      $return .= "</select>";
    }

    $return .= "</p>";
    $return .= "<input type='hidden' name='shortcode_fields_to_catch' value='title,search,search_type' />\n";
    $return .= "<input type='hidden' name='widget' value='". get_class($this) ."' />\n";

    return $return;
  }


  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    return $instance;
  }

}
