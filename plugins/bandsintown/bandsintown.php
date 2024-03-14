<?php
/*
Plugin Name: Bandsintown Events
Plugin URI: https://wordpress.org/plugins/bandsintown/
Description: Bandsintown's Events plugin makes it easy for artists to showcase their upcoming events anywhere on their WordPress-powered blog or website. Easily display an automatically updated list of your events to your fans using the widget, shortcode or template tag.
Author: Bandsintown.com
Author URI: https://www.bandsintown.com
Version: 1.3.1
*/

// Bandsintown Events Plugin
class Bandsintown_JS_Plugin {
  function __construct() {
	  if ( is_admin() ) {
      add_action('admin_menu', array($this, 'admin_menu'));
      add_action('admin_init', array($this, 'plugin_admin_init'));
		}
		else {
      add_action('wp_enqueue_scripts', array($this, 'bandsintown_tour_dates'));
		}

    add_shortcode('bandsintown_events', array($this, 'shortcode'));
    add_action('widgets_init', array($this, 'bandsintown_widget_init'));

    $this->options = get_option('bitp_options');
  }

  function bandsintown_widget_init() {
    return register_widget('Bandsintown_JS_Widget');
  }

  function bandsintown_tour_dates() {
    wp_enqueue_script('bit-tour-dates', 'https://widget.bandsintown.com/main.min.js');
  }

	// Admin menu management.
	function admin_menu() {
	  add_options_page('Bandsintown Events', 'Bandsintown Events', 'manage_options', 'bandsintown-settings', array($this, 'settings'));
	}

  // Manage plugin settings
  function settings() {
    ?>
    <div>
    <div class="wrap" id="bandsintown_wrap">
    <div id="icon-options-general" class="icon32"></div>
    <h2>Bandsintown Events</h2>
    <form action="options.php" method="post">
    <?php
      settings_fields('plugin_options');
      do_settings_sections('bitp');
    ?>
    <input name = "Submit" type="submit" class="button-primary" tabindex="1" value="<?php esc_attr_e('Save Settings'); ?>" />
    </form></div>
    <?php
  }
  // Register_settings
  function plugin_admin_init() { // whitelist options
    register_setting( 'plugin_options', 'bitp_options', array($this, 'options_validate'));
    add_settings_section('settings_section', 'General Settings', array($this, 'main_description'), 'bitp');
    add_settings_field('artist', '', array($this,'settings_inputs'), 'bitp', 'settings_section');
  }
  //Settings Description
  function main_description() {
  //description
  }
  //The Settings Inputs
  function settings_inputs() {
    $options = get_option('bitp_options');
    echo "<tr><p><label for='bitp_options[artist]'><strong>Artist</strong></label><br>";
    $artist = esc_attr( $options['artist'] );
    echo "<input id='bitp_options_artist' name='bitp_options[artist]' type='text' value='$artist' /><br>";
    echo "<p>
		  You can use this section to create your own custom CSS rules and
		  override the look and feel of the widget output.
		  </p>";
		  $options['text_color'] = ($options['text_color'] ? $options['text_color'] : '#000');
		  $text_color = esc_attr( $options['text_color'] );
          echo "<p>
          <strong>Text color:</strong>
          <input name='bitp_options[text_color]' tabindex='1' value='" . $text_color . "' />
		  </p>";
		  $options['background_color'] = ($options['background_color'] ? $options['background_color'] : '#fff');
		  $background_color = esc_attr( $options['background_color'] );
          echo "<p>
          <strong>Background color:</strong>
          <input name='bitp_options[background_color]' tabindex='2' value='" . $background_color . "' />
		  </p>";
		  $options['button_and_link_color'] = ($options['button_and_link_color'] ? $options['button_and_link_color'] : '#2f95de');
		  $button_and_link_color = esc_attr( $options['button_and_link_color'] );
          echo "<p>
          <strong>Button and Link color:</strong>
          <input name='bitp_options[button_and_link_color]' tabindex='3' value='" . $button_and_link_color . "' />
		  </p>";
		  $options['link_text_color'] = ($options['link_text_color'] ? $options['link_text_color'] : '#fff');
		  $link_text_color = esc_attr( $options['link_text_color'] );
          echo "<p>
          <strong>Link Text color</strong>
          <input name='bitp_options[link_text_color]' tabindex='4' value='" . $link_text_color . "' />
		  </p>";
		  $options['display_limit'] = ($options['display_limit'] ? $options['display_limit'] : '15');
		  $display_limit = esc_attr( $options['display_limit'] );
          echo "<p>
          <strong>Display</strong>
		  <input name='bitp_options[display_limit]' tabindex='4' value='" . $display_limit . "' />
		  Events
		  </p>";
          $css = esc_attr( $options['custom_css'] );
          echo "<p>
          <strong>Custom CSS:</strong>
          <br>
          <textarea name='bitp_options[custom_css]' style='width: 100%; height: 150px' tabindex='1'> $css </textarea>
          </p>";
	echo '<script type="text/javascript" src="https://widget.bandsintown.com/main.min.js"></script>';

	echo '<a class="bit-widget-initializer"'
  . 'data-artist-name="' . htmlentities($options['artist']) . '"'
  . 'data-text-color="' . $options['text_color'] . '"'
  . 'data-link-color="' . $options['button_and_link_color'] . '"'
  . 'data-background-color="' . $options['background_color'] . '"'
  . 'data-display-limit="' . $options['display_limit'] . '"'
  . 'data-link-text-color="' . $options['link_text_color'] . '"'
	. 'data-display-local-dates="false"'
  . 'data-display-past-dates="true"'
	. 'data-auto-style="false"'
	. 'data-popup-background-color="#FFFFFF"></a>';
  }
  //Validation
  function options_validate($input) {
    return $input;
  }

  // [bandsintown_events] shortcode
  function shortcode($atts) {
    $default_atts = array(
      'artist' => '',
      'artist-name' => '',
      'text-color' => '#000000',
      'link-color' => '#2F95DE',
      'link-text-color' => '#FFFFFF',
      'background-color' => '#FFFFFF',
      'popup-background-color' => '#FFFFFF',
      'separator-color' => '',
      'font' => '',
      'widget-width' => '',
      'display-logo' => '',
      'display-track-button' => '',
      'display-local-dates' => 'false',
      'display-past-dates' => 'true',
      'display-lineup' => '',
      'display-details' => '',
      'display-limit' => '15',
      'language' => '',
      'auto-style' => 'false',
      'div-id' => '',
      'facebook-page-id' => '',
      'afill-code' => '',
      'app-id' => ''
    );

    $atts = shortcode_atts($default_atts, $atts);

    $artist_name = !empty($atts['artist']) ? $atts['artist'] : $atts['artist-name'];

    if (empty($artist_name)) { $artist_name = $this->options['artist']; }

    $atts['artist-name'] = htmlentities($artist_name);
    unset($atts['artist']);

    $has_default_background_color = $atts['background-color'] == $default_atts['background-color'];
    if ($has_default_background_color && !empty($this->options['background_color'])) {
      $atts['background-color'] = $this->options['background_color'];
    }

    $has_default_display_limit = $atts['display-limit'] == $default_atts['display-limit'];
    if ($has_default_display_limit && !empty($this->options['display_limit'])) {
      $atts['display-limit'] = $this->options['display_limit'];
    }

    $has_default_link_color = $atts['link-color'] == $default_atts['link-color'];
    if ($has_default_link_color && !empty($this->options['button_and_link_color'])) {
      $atts['link-color'] = $this->options['button_and_link_color'];
    }

    $has_default_link_text_color = $atts['link-text-color'] == $default_atts['link-text-color'];
    if ($has_default_link_text_color && !empty($this->options['link_text_color'])) {
      $atts['link-text-color'] = $this->options['link_text_color'];
    }

    $has_default_text_color = $atts['text-color'] == $default_atts['text-color'];
    if ($has_default_text_color && !empty($this->options['text_color'])) {
      $atts['text-color'] = $this->options['text_color'];
    }

    // These atts should be omitted altogether if they are blank
    foreach (
      array(
        'separator-color',
        'font',
        'widget-width',
        'display-logo',
        'display-lineup',
        'display-details',
        'display-track-button',
        'language',
        'div-id',
        'facebook-page-id',
        'afill-code',
        'app-id'
      ) as $a) {
      if (empty($atts[$a])) {
        unset($atts[$a]);
      }
    }

    $output = '<a class="bit-widget-initializer" ';

    foreach ($atts as $att => $value) {
      $output.= 'data-' . $att .'="' . $value . '" ';
    }

    $output.= '></a>';

    $options = get_option('bitp_options');

    if (!empty($options['custom_css'])) {
      $output .= '<style type="text/css">' . $options['custom_css'] . '</style>';
    }

    return $output;
  }

	// actual processing of the template tag
	function template_tag($params = array(), $echo = true) {
		if (!is_array($params)) {
			$str = $params;
			$params = array();
			parse_str($str, $params);
		}

		if (empty($params['artist'])) {
			$params['artist'] = $this->options['artist'];
		}

		if ( empty($params['display_limit']) ) {
			$params['display_limit'] = $this->options['display_limit'];
		}

    $output = '<a class="bit-widget-initializer"'
      . ' data-artist-name="' . htmlentities($params['artist']) . '" '
      . ' data-text-color="' . $this->options['text_color'] . '" '
      . ' data-link-color="' . $this->options['button_and_link_color'] . '" '
      . ' data-background-color="' . $this->options['background_color'] . '" '
      . ' data-display-limit="' . $params['display_limit'] . '" '
      . ' data-link-text-color="' . $this->options['link_text_color'] . '" '
      . ' data-display-local-dates="false" '
      . ' data-display-past-dates="true" '
      . ' data-auto-style="false" '
      . ' data-popup-background-color="#FFFFFF"></a>';

		$options = get_option('bitp_options');
		if ( !empty($options['custom_css']) ) {
			$output .= '<style type="text/css">' . $options['custom_css'] . '</style>';
		}
		if ( $echo ) {
			echo $output;
		}
		else {
			return $output;
		}
	}

} // end Bandsintown_JS_Plugin

//
// Bandsintown Widget
//
class Bandsintown_JS_Widget extends WP_Widget {

  function __construct() {
    parent::__construct(false, $name = 'Bandsintown Events');
  }

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		the_bandsintown_events(array(
			'artist' => $instance['artist'],
			'display_limit' => $instance['display_limit'],
			'force_narrow_layout' => true
		));

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $new_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['artist'] = strip_tags(stripslashes($new_instance['artist']));
		$instance['display_limit'] = strip_tags(stripslashes($new_instance['display_limit']));
		return $instance;
	}

	function form( $instance ) {
		if ( empty($instance['artist']) ) {
			$options = get_option('bitp_options');
			$instance['artist'] = $options['artist'];
		}
		include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'widget-form.php';
	}

} // end Bandsintown JS Widget

global $bitp;
$bitp = new Bandsintown_JS_Plugin();

// template tag wrapper
function the_bandsintown_events( $params = array(), $echo = true ) {
	global $bitp;
	return $bitp->template_tag( $params, $echo );
}
