<?php

class fmcIDXLinksWidget extends fmcWidget {

  function __construct() {
    global $fmc_widgets;

    $widget_info = $fmc_widgets[ get_class($this) ];

    $widget_ops = array( 'description' => $widget_info['description'] );
    WP_Widget::__construct( get_class($this) , $widget_info['title'], $widget_ops);

    // have WP replace instances of [first_argument] with the return from the second_argument function
    add_shortcode($widget_info['shortcode'], array(&$this, 'shortcode'));

    // register where the AJAX calls should be routed when they come in
    add_action('wp_ajax_'.get_class($this).'_shortcode', array(&$this, 'shortcode_form') );
    add_action('wp_ajax_'.get_class($this).'_shortcode_gen', array(&$this, 'shortcode_generate') );
    add_action('wp_ajax_nopriv_'.get_class($this).'_shortcode_gen', array(&$this, 'shortcode_generate') );

  }


  function jelly($args, $settings, $type) {
    global $fmc_api;

    $options = get_option('fmc_settings');

    extract($args);

    if ($type == "widget" && empty($settings['title']) && flexmlsConnect::use_default_titles()) {
      $settings['title'] = "IDX Links";
    }

    $return = '';
$title = ( ! empty( $settings['title'] ) ) ? trim( $settings['title'] ) : '';
$my_links = ( ! empty( $settings['links'] ) ) ? trim( $settings['links'] ) : '';
    //$title = trim(@$settings['title']);
    //$my_links = $settings['links'];

    $links_selected = array_filter(explode(",", $my_links));

    $links_to_show = "";

    $api_links = flexmlsConnect::get_all_idx_links(true);

    if ($api_links === false) {
      return flexmlsConnect::widget_not_available($fmc_api, false, $args, $settings);
    }

    // some small shuffling to maintain the given order of the links
    $valid_links = array();
    foreach ($api_links as $link) {
      $valid_links[$link['LinkId']] = array('Uri' => $link['Uri'], 'Name' => $link['Name'], 'SearchId' => $link['SearchId']);
    }

    foreach ($links_selected as $link) {
      if ( array_key_exists($link, $valid_links) || empty($my_links)) {
        $this_link = $valid_links[$link];

        if ($settings['destination'] == 'local') {
            $tags = array('SavedSearch' => $this_link['SearchId']);
            if(isset($settings['default_view']) && $settings['default_view'] == "map") {
              $tags['view'] = "map";
            }
          $destination_link = flexmlsConnect::make_nice_tag_url('search',  $tags);
        }
        else {
          $destination_link = flexmlsConnect::make_destination_link($this_link['Uri']);
        }

        $this_target = "";
        if (flexmlsConnect::get_destination_window_pref() == "new") {
          $this_target = " target='_blank'";
        }

        $links_to_show .= "<li><a href='{$destination_link}' title='{$this_link['Name']}'{$this_target}>{$this_link['Name']}</a></li>\n";
      }
    }

    if (empty($links_to_show)) {
      return;
    }

    $return .= $before_widget;

    if ( !empty($title) ) {
      $return .= $before_title;
      $return .= $title;
      $return .= $after_title;
      $return .= "\n";
    }

    $return .= "<ul>\n";
    $return .= $links_to_show;
    $return .= "</ul>\n";

    $return .= $after_widget;

    return $return;

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


  function settings_form($instance) {
    global $fmc_api;

    $title = array_key_exists('title', $instance) ? esc_attr($instance['title']) : null;
    $links = array_key_exists('links', $instance) ? esc_attr($instance['links']) : null;
    $destination = array_key_exists('destination', $instance) ? esc_attr($instance['destination']) : null;
    $default_view = array_key_exists('default_view', $instance) ? esc_attr($instance['default_view']) : null;

    $views = array(
      'list' => 'List view',
      'map' => 'Map view'
    );

    $links_selected = explode(",", $links ?? '');

    $checked_code = " checked='checked'";
    $selected_code = " selected='selected'";

    $api_links = flexmlsConnect::get_all_idx_links(true);

    if ($api_links === false) {
      return flexmlsConnect::widget_not_available($fmc_api, true);
    }

    $possible_destinations = flexmlsConnect::possible_destinations();

    if (empty($destination)) {
      $destination = 'local';
    }

    $return = "";

    $return .= "

      <p>
        <label for='".$this->get_field_id('title')."'>" . __('Title:') . "
          <input fmc-field='title' fmc-type='text' type='text' class='widefat' id='".$this->get_field_id('title')."' name='".$this->get_field_name('title')."' value='{$title}'>
        </label>
      </p>

      <p>
        <label for='".$this->get_field_id('links')."'>" . __('Saved Search IDX Links to Display:') . "</label>

        ";

    foreach ($api_links as $link) {
      $return .= "<div>";
      $this_selected = (in_array($link['LinkId'], $links_selected)) ? $checked_code : "";
      $return .= "&nbsp; &nbsp;<input fmc-field='links' fmc-type='checkbox' type='checkbox' name='".$this->get_field_name('links')."[{$link['LinkId']}]' value='{$link['LinkId']}' id='".$this->get_field_id('links')."-".$link['LinkId']."'{$this_selected} /> ";
      $return .= "<label for='".$this->get_field_id('links')."-".$link['LinkId']."'>{$link['Name']}</label>";
      $return .= "</div>\n";
    }

    $return .= "<span class='description'>Links can be managed inside the flexmls&reg; Web IDX Manager</span>";

    $return .= "
      </p>
      <p>
        <label class='flexmls-admin-field-label' for='".$this->get_field_id('default_view')."'>Default view:</label>
        <select fmc-field='default_view' fmc-type='select' id='".$this->get_field_id('default_view')."' name='".$this->get_field_name('default_view')."'>";
        foreach ($views as $dk => $dv) {
          $is_selected = ($dk == $default_view) ? $selected_code : "";
          $return .= "<option value='{$dk}'{$is_selected}>{$dv}</option>\n";
        }

        $return .= "
        </select>
    </p>

      <p>
        <label for='".$this->get_field_id('destination')."'>" . __('Send users to:') . "</label>
        <select fmc-field='destination' fmc-type='select' id='".$this->get_field_id('destination')."'
          name='".$this->get_field_name('destination')."'>
            ";

    foreach ($possible_destinations as $dk => $dv) {
      $is_selected = ($dk == $destination) ? $selected_code : "";
      $return .= "<option value='{$dk}'{$is_selected}>{$dv}</option>\n";
    }

    $return .= "
          </select>
      </p>

      ";

    $return .= "<input type='hidden' name='shortcode_fields_to_catch' value='title,links,destination,default_view' />\n";
    $return .= "<input type='hidden' name='widget' value='". get_class($this) ."' />\n";

    return $return;
  }

  function integration_view_vars(){
    $vars = array();

    $vars['api_links'] = flexmlsConnect::get_all_idx_links(true);
    $vars['api_links_description'] = 'Links can be managed inside the flexmls&reg; Web IDX Manager';
    $vars['default_view'] = array(
      'list' => 'List view',
      'map' => 'Map view'
    );
    $vars['possible_destinations'] = flexmlsConnect::possible_destinations();

    return $vars;
  }

  function update($new_instance, $old_instance) {

    $links_selected = "";
    if (is_array($new_instance['links'])) {
      foreach ($new_instance['links'] as $link) {
        if (!empty($links_selected)) {
          $links_selected .= ",";
        }
        $links_selected .= strip_tags($link);
      }
    }

    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['links'] = $links_selected;
    $instance['destination'] = strip_tags($new_instance['destination']);
    $instance['default_view'] = strip_tags($new_instance['default_view']);
    return $instance;
  }


}
