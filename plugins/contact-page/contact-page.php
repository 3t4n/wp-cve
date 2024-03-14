<?php

/*
Plugin Name: Contact Page
Description: Easily create a contact page with relevant address information, Google Maps, your latest tweets and links to relevant social media profiles.
Version: 1.0
Author: Marek Bosman
Author URI: http://marekbosman.com
*/

/*
Dependencies:
- Social Media Bookmark Icon + (http://www.nouveller.com/general/free-social-media-bookmark-icon-pack-the-ever-growing-icon-set/)
*/

class Contactpage {
  private $prefix;
  private $settings     = array();
  private $title        = 'Contact Page';

  public function __construct()
  {
    $this->prefix = strtolower(__CLASS__) . '_';

    // Get the settings.
    $allowedSettings = array(
      // Address
      'name',
      'address',
      'email',
      'phone',

      // Social Media
      'twitter',
      'facebook',
      'linkedin',
      'enable_tweets',
      'nr_tweets',
      'icon_size',

      // Google Maps
      'enable_map',
      'map_bubble',
      'map_zoom',
      'map_terrain',
      'map_lang',
    );
    $this->settings = get_option($this->prefix . 'settings');
    foreach ($allowedSettings as $setting) if (!isset($this->settings[$this->prefix . $setting])) $this->settings[$this->prefix . $setting] = FALSE;

    register_deactivation_hook(__FILE__, array(&$this, 'deactivate'));

    if (is_admin())
    {
      add_action('admin_init', array(&$this, 'admin_init_settings'));
      add_action('admin_menu', array(&$this, 'add_admin_page'));
      add_filter("plugin_action_links_" . plugin_basename( __FILE__ ), array(&$this, 'set_plugin_actions'));
    }
    else
    {
      add_shortcode('contact-page', array(&$this, 'add_contactpage'));
    }
  }

  public function deactivate()
  {
    unregister_setting($this->prefix . 'settings', $this->prefix . 'settings', array(&$this, 'validate_settings'));
  }

  public function admin_init_settings()
  {
    register_setting($this->prefix . 'settings', $this->prefix . 'settings', array(&$this, 'validate_settings'));

    add_settings_section($this->prefix . 'settings', 'Address', array(&$this, 'settings_address'), $this->prefix . 'page');
    add_settings_field($this->prefix . 'name', 'Name',  array(&$this, 'settings_field_name'),  $this->prefix . 'page', $this->prefix . 'settings');
    add_settings_field($this->prefix . 'address', 'Address', array(&$this, 'settings_field_address'), $this->prefix . 'page', $this->prefix . 'settings');
    add_settings_field($this->prefix . 'email', 'Email', array(&$this, 'settings_field_email'), $this->prefix . 'page', $this->prefix . 'settings');
    add_settings_field($this->prefix . 'phone', 'Phone', array(&$this, 'settings_field_phone'), $this->prefix . 'page', $this->prefix . 'settings');

    add_settings_section($this->prefix . 'settings-socialmedia', 'Social Media', array(&$this, 'settings_socialmedia'), $this->prefix . 'page');
    add_settings_field($this->prefix . 'twitter', 'Twitter', array(&$this, 'settings_field_twitter'), $this->prefix . 'page', $this->prefix . 'settings-socialmedia');
    add_settings_field($this->prefix . 'facebook', 'Facebook', array(&$this, 'settings_field_facebook'), $this->prefix . 'page', $this->prefix . 'settings-socialmedia');
    add_settings_field($this->prefix . 'linkedin', 'LinkedIn', array(&$this, 'settings_field_linkedin'), $this->prefix . 'page', $this->prefix . 'settings-socialmedia');
    add_settings_field($this->prefix . 'enable_tweets', 'Enable Tweets', array(&$this, 'settings_field_enable_tweets'), $this->prefix . 'page', $this->prefix . 'settings-socialmedia');
    add_settings_field($this->prefix . 'nr_tweets', 'Number of Tweets', array(&$this, 'settings_field_nr_tweets'), $this->prefix . 'page', $this->prefix . 'settings-socialmedia');
    // add_settings_field($this->prefix . 'icon_size', 'Icon Size', array(&$this, 'settings_field_icon_size'), $this->prefix . 'page', $this->prefix . 'settings-socialmedia');

    add_settings_section($this->prefix . 'settings-map', 'Google Maps', array(&$this, 'settings_map'), $this->prefix . 'page');
    add_settings_field($this->prefix . 'enable_map', 'Enable Google Maps', array(&$this, 'settings_field_enable_map'), $this->prefix . 'page', $this->prefix . 'settings-map');
    add_settings_field($this->prefix . 'map_bubble', 'Show Address Bubble', array(&$this, 'settings_field_map_bubble'), $this->prefix . 'page', $this->prefix . 'settings-map');
    add_settings_field($this->prefix . 'map_zoom', 'Zoom Level', array(&$this, 'settings_field_map_zoom'), $this->prefix . 'page', $this->prefix . 'settings-map');
    add_settings_field($this->prefix . 'map_terrain', 'Terrain Type', array(&$this, 'settings_field_map_terrain'), $this->prefix . 'page', $this->prefix . 'settings-map');
    add_settings_field($this->prefix . 'map_lang', 'Map Language', array(&$this, 'settings_field_map_lang'), $this->prefix . 'page', $this->prefix . 'settings-map');
  }

  /**
   * Validate the data that was provided by the user.
   */
  // @todo: add validation rules.
  public function validate_settings($input)
  {
    // echo 'validating';
    // exit('q');
    $valid_input = array();

    $valid_input[$this->prefix . 'name'] = strip_tags($input[$this->prefix . 'name']);
    $valid_input[$this->prefix . 'address'] = strip_tags($input[$this->prefix . 'address']);
    $valid_input[$this->prefix . 'email'] = strip_tags($input[$this->prefix . 'email']);
    $valid_input[$this->prefix . 'phone'] = strip_tags($input[$this->prefix . 'phone']);

    // Strip any link information for the social media profiles.
    // - Twitter
    $twitter = strtolower(strip_tags(trim($input[$this->prefix . 'twitter'])));
    $twitter = preg_replace('/^.*\//', '', $twitter);
    $valid_input[$this->prefix . 'twitter'] = $twitter;
    // - Facebook
    $facebook = strtolower(strip_tags(trim($input[$this->prefix . 'facebook'])));
    $facebook = preg_replace('/^.*\//', '', $facebook);
    $valid_input[$this->prefix . 'facebook'] = $facebook;
    // - LinkedIn
    $linkedin = strtolower(strip_tags(trim($input[$this->prefix . 'linkedin'])));
    $linkedin = preg_replace('/^.*\//', '', $linkedin);
    $valid_input[$this->prefix . 'linkedin'] = $linkedin;

    $nrTweets = intval($input[$this->prefix . 'enable_tweets']);
    if ($nrTweets < 0)
      $nrTweets = 0;
    elseif ($nrTweets > 10)
      $nrTweets = 10;
    $valid_input[$this->prefix . 'enable_tweets'] = $nrTweets;
    $valid_input[$this->prefix . 'nr_tweets'] = $input[$this->prefix . 'nr_tweets'];
    $valid_input[$this->prefix . 'icon_size'] = $input[$this->prefix . 'icon_size'];

    $valid_input[$this->prefix . 'enable_map'] = $input[$this->prefix . 'enable_map'];
    $valid_input[$this->prefix . 'map_bubble'] = $input[$this->prefix . 'map_bubble'];
    $valid_input[$this->prefix . 'map_zoom'] = $input[$this->prefix . 'map_zoom'];
    $valid_input[$this->prefix . 'map_terrain'] = $input[$this->prefix . 'map_terrain'];
    $valid_input[$this->prefix . 'map_lang'] = $input[$this->prefix . 'map_lang'];

    return $valid_input;
  }

  /**
   * Intro text of the form.
   */
  // @todo: add some explanation for the user.
  public function settings_address() {}
  public function settings_socialmedia() {}
  public function settings_map() {}

  /**
   * Create each of the form fields.
   */
  public function settings_field_name()
  {
    echo '<input type="text" id="' . $this->prefix . 'name" name="' . $this->prefix . 'settings[' . $this->prefix . 'name]" class="regular-text" value="' . $this->settings[$this->prefix . 'name'] . '" />';
  }
  public function settings_field_address()
  {
    // @todo: remove hardcoded styling, use default styling ('regular-text' is at the moment only available for INPUT).
    echo '<textarea id="' . $this->prefix . 'address" name="' . $this->prefix . 'settings[' . $this->prefix . 'address]" rows="4" style="width: 25em;">' . $this->settings[$this->prefix . 'address'] . '</textarea>';
  }
  public function settings_field_email()
  {
    echo '<input type="text" id="' . $this->prefix . 'email" name="' . $this->prefix . 'settings[' . $this->prefix . 'email]" class="regular-text" value="' . $this->settings[$this->prefix . 'email'] . '" />';
  }
  public function settings_field_phone()
  {
    echo '<input type="text" id="' . $this->prefix . 'phone" name="' . $this->prefix . 'settings[' . $this->prefix . 'phone]" class="regular-text" value="' . $this->settings[$this->prefix . 'phone'] . '" />';
  }
  public function settings_field_twitter()
  {
    echo '<input type="text" id="' . $this->prefix . 'twitter" name="' . $this->prefix . 'settings[' . $this->prefix . 'twitter]" class="regular-text" value="' . $this->settings[$this->prefix . 'twitter'] . '" />';
  }
  public function settings_field_facebook()
  {
    echo '<input type="text" id="' . $this->prefix . 'facebook" name="' . $this->prefix . 'settings[' . $this->prefix . 'facebook]" class="regular-text" value="' . $this->settings[$this->prefix . 'facebook'] . '" />';
  }
  public function settings_field_linkedin()
  {
    echo '<input type="text" id="' . $this->prefix . 'linkedin" name="' . $this->prefix . 'settings[' . $this->prefix . 'linkedin]" class="regular-text" value="' . $this->settings[$this->prefix . 'linkedin'] . '" />';
  }
  public function settings_field_enable_tweets()
  {
    echo '<input type="checkbox" id="' . $this->prefix . 'enable_tweets" name="' . $this->prefix . 'settings[' . $this->prefix . 'enable_tweets]" value="1"';
    if ($this->settings[$this->prefix . 'enable_tweets']) echo ' checked="checked"';
    echo ' />';
  }
  public function settings_field_nr_tweets()
  {
    echo '<select id="' . $this->prefix . 'nr_tweets" name="' . $this->prefix . 'settings[' . $this->prefix . 'nr_tweets]">' . PHP_EOL;
    echo '<option value="0">None</option>';
    for ($i=0; $i<10; $i++)
    {
      echo '<option value="' . ($i+1) . '"';
      if (($i+1) == $this->settings[$this->prefix . 'nr_tweets']) echo ' checked="checked" selected="selected"';
      echo '>' . ($i+1) . '</option>' . PHP_EOL;
    }
    echo '</select>' . PHP_EOL;
  }
  public function settings_field_icon_size()
  {
    echo '<select id="' . $this->prefix . 'icon_size" name="' . $this->prefix . 'settings[' . $this->prefix . 'icon_size]">' . PHP_EOL;

      echo '<option value="large"';
      if ('large' == $this->settings[$this->prefix . 'icon_size']) echo ' checked="checked" selected="selected"';
      echo '>Large</option>' . PHP_EOL;

      echo '<option value="small"';
      if ('small' == $this->settings[$this->prefix . 'icon_size']) echo ' checked="checked" selected="selected"';
      echo '>Small</option>' . PHP_EOL;

    echo '</select>' . PHP_EOL;

  }
  public function settings_field_enable_map()
  {
    echo '<input type="checkbox" id="' . $this->prefix . 'enable_map" name="' . $this->prefix . 'settings[' . $this->prefix . 'enable_map]" value="1"';
    if ($this->settings[$this->prefix . 'enable_map']) echo ' checked="checked"';
    echo ' />';
  }
  public function settings_field_map_bubble()
  {
    echo '<input type="checkbox" id="' . $this->prefix . 'map_bubble" name="' . $this->prefix . 'settings[' . $this->prefix . 'map_bubble]" value="1"';
    if ($this->settings[$this->prefix . 'map_bubble']) echo ' checked="checked"';
    echo ' />';
  }
  public function settings_field_map_zoom()
  {
    echo 'Out ';
    echo '<input type="range" min="0" max="19" id="' . $this->prefix . 'map_zoom" name="' . $this->prefix . 'settings[' . $this->prefix . 'map_zoom]" value="' . $this->settings[$this->prefix . 'map_zoom'] . '" />';
    echo ' In';
    /*
    echo '<select id="' . $this->prefix . 'map_zoom" name="' . $this->prefix . 'settings[' . $this->prefix . 'map_zoom]">' . PHP_EOL;
    for ($i=0; $i<20; $i++)
    {
      echo '<option value="' . $i . '"';
      if ($i == $this->settings[$this->prefix . 'map_zoom']) echo ' checked="checked" selected="selected"';
      echo '>' . $i . '</option>' . PHP_EOL;
    }
    echo '</select>' . PHP_EOL;
    */
  }
  public function settings_field_map_terrain()
  {
    echo '<select id="' . $this->prefix . 'map_terrain" name="' . $this->prefix . 'settings[' . $this->prefix . 'map_terrain]">' . PHP_EOL;

      echo '<option value="m"';
      if ('m' == $this->settings[$this->prefix . 'map_terrain']) echo ' checked="checked" selected="selected"';
      echo '>Map</option>' . PHP_EOL;

      echo '<option value="h"';
      if ('h' == $this->settings[$this->prefix . 'map_terrain']) echo ' checked="checked" selected="selected"';
      echo '>Satellite</option>' . PHP_EOL;

      echo '<option value="k"';
      if ('k' == $this->settings[$this->prefix . 'map_terrain']) echo ' checked="checked" selected="selected"';
      echo '>Satellite (without labels)</option>' . PHP_EOL;

      echo '<option value="t"';
      if ('t' == $this->settings[$this->prefix . 'map_terrain']) echo ' checked="checked" selected="selected"';
      echo '>Terrain</option>' . PHP_EOL;

      echo '<option value="e"';
      if ('e' == $this->settings[$this->prefix . 'map_terrain']) echo ' checked="checked" selected="selected"';
      echo '>Earth</option>' . PHP_EOL;

    echo '</select>' . PHP_EOL;
  }
  public function settings_field_map_lang()
  {
    echo '<select id="' . $this->prefix . 'map_lang" name="' . $this->prefix . 'settings[' . $this->prefix . 'map_lang]">' . PHP_EOL;

      echo '<option value="en"';
      if ('en' == $this->settings[$this->prefix . 'map_lang']) echo ' checked="checked" selected="selected"';
      echo '>English</option>' . PHP_EOL;

      echo '<option value="nl"';
      if ('nl' == $this->settings[$this->prefix . 'map_lang']) echo ' checked="checked" selected="selected"';
      echo '>Nederlands</option>' . PHP_EOL;

      echo '<option value="de"';
      if ('de' == $this->settings[$this->prefix . 'map_lang']) echo ' checked="checked" selected="selected"';
      echo '>Deutsch</option>' . PHP_EOL;

      echo '<option value="fr"';
      if ('fr' == $this->settings[$this->prefix . 'map_lang']) echo ' checked="checked" selected="selected"';
      echo '>Francais</option>' . PHP_EOL;

    echo '</select>' . PHP_EOL;

  }


  /**
   * Display the settings page.
   */
  public function show_settings()
  {
    ?>
    <div class="wrap">
      <div id="icon-options-general" class="icon32"></div>
      <h2><?php echo $this->title; ?></h2>

      <form method="post" action="options.php">
        <?php settings_fields($this->prefix . 'settings'); ?>
        <?php do_settings_sections($this->prefix . 'page'); ?>

        <h3>
          Usage
        </h3>
        <p>
            Use the shortcode <code>[contact-page]</code> to add the address and contact information to any page or post.
        </p>

        <p class="submit">
          <input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" />
        </p>
      </form>
    </div>
    <?php
  }

  /**
   * Display the settings page in the backend.
   */
  public function add_admin_page()
  {
    // @todo: remove hardcoded names and slug
    // Add a link to the Settings menu.
    add_options_page(
      'Contact Page',                 // Page title
      'Contact Page',                 // Menu title
      'manage_options',               // Capability
      'contact-page',                 // Menu slug
      array(&$this, 'show_settings')  // Callback function
    );
  }

  /**
   * Add a link to the Settings page, appearing in the actions
   * section (beneath the plugin's title).
   */
  public function set_plugin_actions($links)
  {
    // @todo: remove hardcode page name.
    $link = '<a href="options-general.php?page=contact-page">' . __( 'Settings' ) . '</a>';
    array_unshift($links, $link);

    return $links;
  }

  /**
   * Display the contact page
   */
  public function add_contactpage()
  {
    // Determine which icon size to use.
    $iconSize = ('large' == $this->settings[$this->prefix . 'icon_size']) ? '32' : '16';

    // Load the base CSS.
    // @todo: Remove hardcoded plugin name.
    wp_enqueue_style('contact-page', plugins_url('assets/css/contact-page.css', __FILE__));

    // Construct the HTML code.
    $output = '';
    $output .= $this->add_google_maps();
    $output .= $this->add_address();
    $output .= $this->add_social_media();
    $output .= $this->add_twitter_feed();
    $output = '<div id="contact-page">' . $output . '</div>';

    return $output;
  }

  /**
   * Return the HTML code to add Google Maps.
   */
  public function add_google_maps()
  {
    if ($this->settings[$this->prefix . 'enable_map'])
    {
      // Generate a valid URL for Google Maps.
      $map = array(
        'query'   => urlencode($this->settings[$this->prefix . 'address']),
        'zoom'    => $this->settings[$this->prefix . 'map_zoom'],
        'lang'    => $this->settings[$this->prefix . 'map_lang'],
        'terrain' => $this->settings[$this->prefix . 'map_terrain'],
        'bubble'  => $this->settings[$this->prefix . 'map_bubble'],
      );

      $mapQueryParameters = array(
        'q'       => $map['query'],
        'z'       => $map['zoom'],
        't'       => $map['terrain'],
        'hl'      => $map['lang'],
        'iwloc'   => '',
        'output'  => 'embed',
      );
      if ($map['bubble']) $mapQueryParameters['iwloc'] = 'A';

      $mapQuery = array();
      foreach ($mapQueryParameters as $key => $value) $mapQuery[] = $key . '=' . $value;
      $mapQuery = implode('&amp;', $mapQuery);

      $map['url'] = 'https://maps.google.com/maps?' . $mapQuery;

      return '<iframe class="contact-page-google-maps" src="' . $map['url'] . '"></iframe>';
    }
  }

  /**
   * Return the HTML code to add the name and address information.
   */
  public function add_address()
  {
    $output = '
      <div class="contact-page-column">
        <ul>
          <li class="contact-page-name">
            <strong>Name:</strong>' . $this->settings[$this->prefix . 'name'] . '
          </li>
          <li class="contact-page-address">
            <strong>Address:</strong>' . nl2br($this->settings[$this->prefix . 'address']) . '
          </li>
          <li class="contact-page-phone">
            <strong>Telephone:</strong>' . $this->settings[$this->prefix . 'phone'] . '
          </li>
          <li class="contact-page-email">
            <strong>Email:</strong>
            <a href="mailto:' . $this->settings[$this->prefix . 'email'] . '">' . $this->settings[$this->prefix . 'email'] . '</a>
          </li>
        </ul>
      </div>';

    return $output;
  }

  /**
   * Return the HTML code to add social media information.
   */
  public function add_social_media()
  {
    $output = '
      <div class="contact-page-column">
        <ul>';
    if ($this->settings[$this->prefix . 'twitter'])   $output .= '<li class="contact-page-social-media contact-page-social-media-twitter"><a href="http://twitter.com/' . $this->settings[$this->prefix . 'twitter'] . '">@' . $this->settings[$this->prefix . 'twitter'] . '</a></li>';
    if ($this->settings[$this->prefix . 'facebook'])  $output .= '<li class="contact-page-social-media contact-page-social-media-facebook"><a href="http://www.facebook.com/' . $this->settings[$this->prefix . 'facebook'] . '">/' . $this->settings[$this->prefix . 'facebook'] . '</a></li>';
    if ($this->settings[$this->prefix . 'linkedin'])  $output .= '<li class="contact-page-social-media contact-page-social-media-linkedin"><a href="http://www.linkedin.com/in/' . $this->settings[$this->prefix . 'linkedin'] . '">/in/' . $this->settings[$this->prefix . 'linkedin'] . '</a></li>';
    $output .= '
        </ul>
      </div>';

    return $output;
  }

  public function add_twitter_feed()
  {
    if (!$this->settings[$this->prefix . 'twitter'] || !$this->settings[$this->prefix . 'enable_tweets'] || $this->settings[$this->prefix . 'nr_tweets'] < 1) return;

    $tweetCountString = ($this->settings[$this->prefix . 'nr_tweets'] > 1) ? $this->settings[$this->prefix . 'nr_tweets'] . ' tweets' : ' tweet';

    // @todo: move js-code to separate file (?)
    $output = '
      <h3 class="contact-page-twitter-count">Last ' . $tweetCountString . '</h3>
      <ol id="contact-page-twitter-feed"><li></li></ol>

      <script type="text/javascript">
        function twitterCallback2(twitters) {
          var statusHTML = [];
          for (var i=0; i<twitters.length; i++){
            var username = twitters[i].user.screen_name;
            var status = twitters[i].text.replace(/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;\'">\:\s\<\>\)\]\!])/g, function(url) {
              return \'<a href="\'+url+\'">\'+url+\'</a>\';
            }).replace(/\B@([_a-z0-9]+)/ig, function(reply) {
              return  reply.charAt(0)+\'<a href="http://twitter.com/\'+reply.substring(1)+\'">\'+reply.substring(1)+\'</a>\';
            });
            statusHTML.push( \'<li><span class="tweet">\'+status+\' <a href="http://twitter.com/\'+username+\'/statuses/\'+twitters[i].id_str+\'" class="time">\'+relative_time(twitters[i].created_at)+\'</a></span></li>\' );
          }
          document.getElementById(\'contact-page-twitter-feed\').innerHTML = statusHTML.join(\'\');
        }
        function relative_time(time_value) {
          var values = time_value.split( " " );
          time_value = values[1] + " " + values[2] + ", " + values[5] + " " + values[3];
          var parsed_date = Date.parse(time_value);
          var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
          var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);
          delta = delta + (relative_to.getTimezoneOffset() * 60);
          if (delta < 60) {
            return \'' . esc_attr__('less than a minute ago') . '\';
          } else if(delta < 120) {
            return \'' . esc_attr__('about a minute ago', 'woothemes') . '\';
          } else if(delta < (60*60)) {
            return (parseInt(delta / 60)).toString() + \' ' . esc_attr__('minutes ago') . '\';
          } else if(delta < (120*60)) {
            return \'about an hour ago\';
          } else if(delta < (24*60*60)) {
            return \'about \' + (parseInt(delta / 3600)).toString() + \' ' . esc_attr__('hours ago') . '\';
          } else if(delta < (48*60*60)) {
            return \'1 day ago\';
          } else {
            return (parseInt(delta / 86400)).toString() + \' ' . esc_attr__('days ago') . '\';
          }
        }
      </script>
      <script type="text/javascript" src="http';
      if (is_ssl()) $output .= 's';
      $output .= '://api.twitter.com/1/statuses/user_timeline/' . $this->settings[$this->prefix . 'twitter'] . '.json?callback=twitterCallback2&amp;count=' . $this->settings[$this->prefix . 'nr_tweets'] . '&amp;include_rts=t"></script>';

    return $output;
  }
}

new Contactpage();
