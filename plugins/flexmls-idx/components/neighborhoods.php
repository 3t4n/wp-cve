<?php



class fmcNeighborhoods extends fmcWidget {

  function __construct() {
    global $fmc_widgets;

    $widget_info = $fmc_widgets[ get_class($this) ];

    $widget_ops = array( 'description' => $widget_info['description'] );
//    $this->WP_Widget( get_class($this) , $widget_info['title'], $widget_ops);

    // have WP replace instances of [first_argument] with the return from the second_argument function
    add_shortcode($widget_info['shortcode'], array(&$this, 'shortcode'));

    // register where the AJAX calls should be routed when they come in
    add_action('wp_ajax_'.get_class($this).'_shortcode', array(&$this, 'shortcode_form') );
    add_action('wp_ajax_'.get_class($this).'_shortcode_gen', array(&$this, 'shortcode_generate') );

    add_action('wp_ajax_'.get_class($this).'_additional_photos', array(&$this, 'additional_photos') );
    add_action('wp_ajax_nopriv_'.get_class($this).'_additional_photos', array(&$this, 'additional_photos') );

    add_action('wp_ajax_'.get_class($this).'_additional_slides', array(&$this, 'additional_slides') );
    add_action('wp_ajax_nopriv_'.get_class($this).'_additional_slides', array(&$this, 'additional_slides') );

  }


  function jelly($args, $settings, $type) {
    global $fmc_api;
    global $fmc_plugin_url;
    global $fmc_widgets;

    $return = '';

    $title = trim($settings['title']);
    $location = html_entity_decode(flexmlsConnect::clean_comma_list($settings['location']));
    $template = trim($settings['template']);

    //Find and remove & to display {Location} name from shortcode.
    $location_return = substr($location,strpos($location, "&") + 1);

    $page_content = flexmlsConnect::get_neighborhood_template_content($template);

    if ($page_content === false) {
      // no appropriate template page is selected.
      return "<span style='color:red;'>flexmls&reg; IDX: This neighborhood feature requires a template to be selected from the Settings > flexmls IDX dashboard within WordPress.</span>";
    }

    $page_content = str_replace("{Location}", $location_return, $page_content);

    // parse the location search setting for this page
    $locations = flexmlsConnect::parse_location_search_string($location);

    // make a quick list of all of the widgets supported by our plugin
    $all_widget_shortcodes = array();
    foreach ($fmc_widgets as $class => $wdg) {
      $all_widget_shortcodes[] = $wdg['shortcode'];
    }

    // make a pipe delimited list of the shortcodes ready for the regular expression
    $tagregexp = implode('|', array_map('preg_quote', $all_widget_shortcodes));

    // find all matching shortcodes
    preg_match_all('/(.?)\[('.$tagregexp.')\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?(.?)/s', $page_content, $matches);

    // go through all of our shortcodes found on the template page and start adding/replacing location values
    foreach ($matches[0] as $found) {
      $full_tag = trim($found);
      if ( preg_match('/ (location|locations)=/', $full_tag) ) {
        // the 'location' or 'locations' attribute was found in this particular shortcode so replace it's value
        $new_tag = preg_replace('/ (location|locations)="(.*?)"/', ' location="'.$locations[0]['r'].'"', $full_tag);
      }
      else {
        // no 'location' or 'locations' attribute was found so add it to the end of the attributes
        $attr_name = "location";
        if ( preg_match('/^\[idx_location_links/', $full_tag) ) {
          $attr_name = "locations";
        }

        // anchor to the beginning of the shortcode.
        // an escaped shortcode (double close square brackets) is messed up if anchored to the end
        $new_tag = preg_replace('/^(.*?)\]/', '$1 '.$attr_name.'="'.$locations[0]['r'].'"]', $full_tag);
      }

      // replace the old shortcode on the template page with the one specific to this page
      $page_content = str_replace($full_tag, $new_tag, $page_content);
    }

    // run our new content back through WordPress for formatting and shortcode parsing
    $return .= apply_filters( 'the_content', $page_content );

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
    $location = array_key_exists('location', $instance) ? $instance['location'] : null;
    $template = array_key_exists('template', $instance) ? $instance['template'] : null;

    $return = "

      <p>
        <label for='".$this->get_field_id('title')."'>" . __('Title:') . "</label>
        <input fmc-field='title' fmc-type='text' type='text' class='widefat' id='".$this->get_field_id('title')."' name='".$this->get_field_name('title')."' value='{$title}'>
      </p>

      <p>
        <label for='template'>Neighborhood Template:</label>
        ";


      $args = array(
        'post_status' => 'draft'
      );

      $page_selection = get_pages($args);
      if (!empty($page_selection)) {
	$return .= "<select fmc-field='template' name='template' id='template'>";
        $return .= "<option value='default'>(Use Saved Default)</option>";

	foreach ($page_selection as $page) {
              $return .= '<option value="' . $page->ID . '">';
              $return .= (empty($page->post_title) ? "#" . $page->ID . " (no title)" : $page->post_title);
              $return .= '</option>';
	}
        $return .= '</select>';
      }
      else {
        $return .= "Please create a page as a draft to select it here.";
      }

    $return .= "
      </p>

      <p class='flexmls_connect__location'>

        <label for='".$this->get_field_id('location')."'>" . __('Location:') . "</label> 

        <select class='flexmlsAdminLocationSearch' type='hidden' style='width: 100%;' 
          id='" . $this->get_field_id('location') . "' name='" . $this->get_field_name('location_input') . "'
          data-portal-slug='" . \flexmlsConnect::get_portal_slug() . "'>
        </select>
      
        <input fmc-field='location' fmc-type='text' type='hidden' value=\"{$location}\" 
          name='" . $this->get_field_name('location') . "' class='flexmls_connect__location_fields' />

        <select style='display:none;' fmc-field='property_type' class='flexmls_connect__property_type' fmc-type='select' id='".$this->get_field_id('property_type')."' name='".$this->get_field_name('property_type')."'>
          <option value='A' selected='selected'></option>
        </select>
      </p>

    ";

    $return .= "<input type='hidden' name='shortcode_fields_to_catch' value='title,location,template' />\n";
    $return .= "<input type='hidden' name='widget' value='". get_class($this) ."' />\n";

    return $return;

  }


  function update($new_instance, $old_instance) {
    $instance = $old_instance;

    $instance['title'] = strip_tags($new_instance['title']);
    $instance['template'] = strip_tags($new_instance['template']);
    $instance['location'] = strip_tags($new_instance['location']);

    return $instance;
  }

}
