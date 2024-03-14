<?php



class fmcLocationLinks extends fmcWidget {

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
    extract($args);

    //fixing locations to work with url - removing slashes WP-149
    $settings['locations'] = stripslashes($settings['locations']);

    // set default title if a widget, none given, and the default_titles setting is turned on
    if ($type == "widget" && empty($settings['title']) && flexmlsConnect::use_default_titles()) {
      $settings['title'] = "Location Links";
    }

    $return = '';

    $title = array_key_exists('title', $settings) ? trim($settings['title']) : null;
    $my_link_id = trim($settings['link']);
    $property_type = array_key_exists('property_type', $settings) ? trim($settings['property_type']) : null;
    $my_locations = html_entity_decode(flexmlsConnect::clean_comma_list($settings['locations']));

    // check if required parameters were given
    if (empty($my_link_id) || empty($my_locations)) {
      return flexmlsConnect::widget_missing_requirements("Location Links", "Link and Locations");
    }

    $idx_links = new FMC_IDX_Links(flexmlsConnect::get_all_idx_links(true));

    if ($idx_links->links === false) {
      return flexmlsConnect::widget_not_available($fmc_api, false, $args, $settings);
    }

    if ($my_link_id == "default") {
      $my_link =  $idx_links->default_link();
    } else {
      if ( !$idx_links->validate_link($my_link_id) ) {
        echo flexmlsConnect::show_error(array(
          "title" => "1-Click Location Searches Widget",
          "message" => "The selected IDX Link is invalid."
        ));
        return;
      }
      $my_link = $idx_links->links[$my_link_id];
    }

    // break the Location Search string into separate pieces
    $locations = flexmlsConnect::parse_location_search_string($my_locations);

    // make a list of all of the standard field names used in the Location Search value
    $location_field_names = array();
    foreach ($locations as $loc) {
      $location_field_names[] = $loc['f'];
    }

    // make that list unique
    $uniq_location_field_names = array_unique($location_field_names);

    // prepare some values for the transformation API call.
    // this allows us to get the transformed link for all of the fields at once rather than requiring
    // a separate API call for each unique field we're generating links for
    $link_transform_params = array();
    foreach ($uniq_location_field_names as $loc_name) {
      $link_transform_params["{$loc_name}"] = "*{$loc_name}*";
    }

    $link_transform_params["PropertyType"] = "*PropertyType*";

    if ($settings['destination'] != 'local') {
      // make the API call to translate standard field names
      $outbound_link = $fmc_api->GetTransformedIDXLink($my_link["Id"], $link_transform_params);

    }

    $links_to_show = "";
    foreach ($locations as $loc) {

      $final_destination = null;
      $final_target = null;

      if ($settings['destination'] == 'local') {
          $tags = array(
              'SavedSearch' => $my_link['SearchId'],
              $loc['f'] => $loc['v'],
              'PropertyType' => $property_type
          );
          if(isset($settings['default_view']) && $settings['default_view'] == "map")
              $tags['view'] = "map";
        $final_destination = flexmlsConnect::make_nice_tag_url('search',$tags);
      }
      else {
        // start replacing the placeholders in the link with the real values for this link
        $this_link = $outbound_link;
        $this_link = preg_replace('/\*'.preg_quote($loc['f']).'\*/', $loc['v'], $this_link);
        $this_link = preg_replace('/\*PropertyType\*/', $property_type, $this_link);
        // replace all remaining placeholders with a blank value since it doesn't apply to this link
        $this_link = preg_replace('/\*(.*?)\*/', "", $this_link);
        $this_target = "";
        if (flexmlsConnect::get_destination_window_pref() == "new") {
          $this_target = " target='_blank'";
        }
        $final_destination = flexmlsConnect::make_destination_link($this_link);
        $final_target = $this_target;
      }


      $links_to_show .= "<li><a href=\"{$final_destination}\" title=\"{$my_link['Name']} - {$loc['l']}\"{$final_target}>{$loc['l']}</a></li>";
    }

    if (empty($links_to_show)) {
      return;
    }

    $return .= $before_widget;

    if ( !empty($title) ) {
      $return .= $before_title;
      $return .= $title;
      $return .= $after_title;
    }

    $return .= "<ul>" . $links_to_show . "</ul>";

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

   protected function get_property_type(){
      $PropertyTypes = new \SparkAPI\PropertyTypes();
      $api_property_type_options = $PropertyTypes->get_property_types();
      return $api_property_type_options;
    }

    protected function get_links(){
      $IDXLinks = new \SparkAPI\IDXLinks();
      $api_links = $IDXLinks->get_all_idx_links();
      return $api_links;
    }


  function settings_form($instance) {
    global $fmc_api;

    $title =          array_key_exists('title', $instance) ? esc_attr($instance['title']) : null;
    $link =           array_key_exists('link', $instance) ? esc_attr($instance['link']) : null;
    $property_type =  array_key_exists('property_type', $instance) ? esc_attr($instance['property_type']) : null;
    $locations =      array_key_exists('locations', $instance) ? $instance['locations'] : null;
    $destination =    array_key_exists('destination', $instance) ? esc_attr($instance['destination']) : null;

    $possible_destinations = flexmlsConnect::possible_destinations();

    $selected_code = " selected='selected'";

    $api_links = $this->get_links();

    $api_property_type_options = $this->get_property_type();

    $System = new \SparkAPI\System();

    if ($api_links === false || $api_property_type_options === false) {
      return flexmlsConnect::widget_not_available($fmc_api, true);
    }

    $return = "";

    $return .= "

      <p>
        <label for='".$this->get_field_id('title')."'>" . __('Title:') . "</label>
        <input fmc-field='title' fmc-type='text' type='text' class='widefat' id='".$this->get_field_id('title')."' name='".$this->get_field_name('title')."' value='{$title}'>
      </p>

      <p>
        <label for='".$this->get_field_id('link')."'>" . __('IDX Link:') . "</label>
        <select fmc-field='link' fmc-type='select' id='".$this->get_field_id('link')."' name='".$this->get_field_name('link')."'>
            ";

    $is_selected = ($link == "default") ? $selected_code : "";
    $return .= "<option value='default'{$is_selected}>(Use Saved Default)</option>";

    foreach ($api_links as $my_l) {
      $is_selected = ($my_l['LinkId'] == $link) ? $selected_code : "";
      $return .= "<option value='{$my_l['LinkId']}'{$is_selected}>{$my_l['Name']}</option>";
    }

    $return .= "
          </select><br /><span class='description'>Saved Search IDX link these locations are built upon</span>
      </p>

      <p>
        <label for='".$this->get_field_id('property_type')."'>" . __('Property Type:') . "</label>
        <select fmc-field='property_type' fmc-type='select' id='".$this->get_field_id('property_type')."' name='".$this->get_field_name('property_type')."' class='flexmls_connect__property_type'>
            ";

    $return .= "<option value=''>All</option>";
    foreach ($api_property_type_options as $k => $v) {
      $is_selected = ($k == $property_type) ? $selected_code : "";
      $return .= "<option value='{$k}'{$is_selected}>{$v}</option>";
    }

    $return .= "
        </select>
      </p>

    <div class='flexmls_connect__location'>

      <label for='".$this->get_field_id('location')."'>" . __('Locations:') . "</label> 

      <select class='flexmlsAdminLocationSearch' type='hidden' style='width: 100%;' multiple='true'
        id='" . $this->get_field_id('location') . "' name='" . $this->get_field_name('location_input') . "'
        data-portal-slug='" . \flexmlsConnect::get_portal_slug() . "'>
      </select>
    
      <input fmc-field='locations' fmc-type='text' type='hidden' value=\"{$locations}\" 
        name='" . $this->get_field_name('locations') . "' class='flexmls_connect__location_fields' />

    </div>
       <p><br/>
        <label for='".$this->get_field_id('destination')."'>" . __('Send users to:') . "</label>
        <select fmc-field='destination' fmc-type='select' id='".$this->get_field_id('destination')."' name='".$this->get_field_name('destination')."'>
            ";

    foreach ($possible_destinations as $dk => $dv) {
      $is_selected = ($dk == $destination) ? " selected='selected'" : "";
      $return .= "<option value='{$dk}'{$is_selected}>{$dv}</option>";
    }

    $return .= "
          </select>
      </p>
<p>
    <label class=\"flexmls-admin-field-label\" for=\"fmc_shortcode_field_sort\">Default view:</label>
    <select fmc-field=\"default_view\" fmc-type='select' id=\"fmc_shortcode_field_default_view\" name=\"default_view\">
        <option value='list'>
            List view
        </option>
        <option value='map'>
            Map view
        </option>
    </select>
</p>
      ";

    $return .= "<input type='hidden' name='shortcode_fields_to_catch' value='title,link,property_type,locations,destination,default_view' />";
    $return .= "<input type='hidden' name='widget' value='". get_class($this) ."' />";

    return $return;
  }

  function integration_view_vars(){
    $vars = array();

    $default = array(
      'LinkId' => 'default',
      'Name' => '(Use Saved Default)'
    );

    $vars['title'] = '';
    $vars['api_links'] = $this->get_links();
    array_unshift($vars['api_links'], $default);
    $vars['property_type'] = $this->get_property_type();
    $vars['location_slug'] = flexmlsConnect::get_portal_slug();
    $vars['destination'] = flexmlsConnect::possible_destinations();
    $vars['default_view'] = array(
      'list' => 'List view',
      'map' => 'Map view'
    );

    return $vars;
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;

    $instance['title'] = strip_tags($new_instance['title']);
    $instance['link'] = strip_tags($new_instance['link']);
    $instance['property_type'] = strip_tags($new_instance['property_type']);
    $instance['locations'] = strip_tags($new_instance['locations']);
    $instance['destination'] = strip_tags($new_instance['destination']);

    return $instance;
  }


}
