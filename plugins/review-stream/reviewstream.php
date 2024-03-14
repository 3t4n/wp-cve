<?php
/*
Plugin Name: Review Stream
Plugin URI: https://wordpress.org/plugins/review-stream
Description: Stream your latest and greatest reviews from around the Web to your Wordpress site and display them with SEO-friendly rich-snippet markup.
Version: 1.6.7
Author: Grade Us, Inc.
Author URI: https://www.grade.us/home
Author Email: dev@grade.us
License:

  Copyright 2013-2023 | Grade Us, Inc.

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 3, as
  published by the Free Software Foundation.

  https://www.gnu.org/licenses/gpl-3.0.en.html

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

// Set the version of this plugin
if( ! defined( 'REVIEWSTREAM_VERSION' ) ) {
  define( 'REVIEWSTREAM_VERSION', '1.6.5' );
}

class ReviewStream {

  /*--------------------------------------------*
   * Constants
   *--------------------------------------------*/
  const name = 'Review Stream';
  const slug = 'reviewstream';

  /**
   * Constructor
   */
  function __construct() {

    //register an activation hook for the plugin
    register_activation_hook( __FILE__, array( &$this, 'install_reviewstream' ) );

    register_deactivation_hook( __FILE__, array( __CLASS__, 'plugin_deactivation' ) );

    //Hook up to the init action
    add_action( 'init', array( &$this, 'init_reviewstream' ) );

    add_action('admin_init', array(&$this, 'admin_init'));
    add_action('admin_menu', array(&$this, 'add_menu'));

    // Display the admin notification
    add_action( 'admin_notices', array( $this, 'plugin_update' ) ) ;
    add_action( 'admin_notices', array( $this, 'plugin_activation' ) ) ;

    //shortcode
    add_shortcode('reviewstream', array($this, 'shortcode'));

  }

  /**
   * Checks plugin version and instructs customer accordingly
   */
  public function plugin_update() {
    // Deactivate plugin to force activation and show admin_notices
    if( REVIEWSTREAM_VERSION != get_option( 'reviewstream_version' ) ) {
      // $notices = array(
      //   '1.5' => '<strong>IMPORTANT:</strong> There are changes to white-label configuration for the Review Stream plugin. <strong>The Review Stream may not be white-labeled if you ignore this message.</strong> Wordpress may require you to deactivate and reactivate the plugin to remove this message and proceed.'
      // );

      // $html = '<div class="updated notice">';
      //   $html .= '<p>';
      //     $html .= $notices[REVIEWSTREAM_VERSION];
      //   $html .= '</p>';
      // $html .= '</div>';
      // echo $html;
    }
  }

  /**
   * Saves the version of the plugin to the database and displays activation notices
   */
  public function plugin_activation() {
    if( get_option( 'reviewstream_active' ) != 'true' ) {
      add_option( 'reviewstream_active', 'true' );
      update_option( 'reviewstream_active', 'true' );
      // $notices = array(
      //   '1.5' => '<strong>IMPORTANT:</strong> To white-label the Review Stream, please create your <em>config.php</em> file in <em>uploads/reviewstream-settings</em>. A sample file has been added for you.'
      // );

      // $html = '<div class="updated notice is-dismissible">';
      //   $html .= '<p>';
      //     $html .= $notices[REVIEWSTREAM_VERSION];
      //   $html .= '</p>';
      // $html .= '</div>';
      // echo $html;

    }
  }

  /**
   * Deletes the option from the database.
   */
  public function plugin_deactivation() {
    delete_option( 'reviewstream_active' );
    delete_option( 'reviewstream_version' );
    // Display an error message if the option isn't properly deleted.
    if( get_option( 'reviewstream_active' ) || get_option( 'reviewstream_version' ) ) {
      $html = '<div class="error">';
        $html .= '<p>';
          $html .= 'There was a problem deactivating the Review Stream Plugin. Please try again.';
        $html .= '</p>';
      $html .= '</div>';
      echo $html;
    }
  }

  /**
   * Runs when the plugin is activated
   */
  function install_reviewstream() {
    // do not generate any output here
    // add version
    add_option( 'reviewstream_version', REVIEWSTREAM_VERSION );
    update_option( 'reviewstream_version', REVIEWSTREAM_VERSION );
    // create reviewstream-settings
    $uploads = wp_upload_dir();
    wp_mkdir_p( $uploads['basedir'].'/reviewstream-settings' );
    $settings_sample = $uploads['basedir'].'/reviewstream-settings/config-sample.php';
    if( file_exists( $settings_sample) ) {
      return true;
    }
    if ( $handle = @fopen ( $settings_sample, 'w' ) ) {
      fwrite( $handle, '<?php' . "\n" );
      fwrite( $handle, '/* ------------------------------------------------------------' . "\n\n" );
      fwrite( $handle, 'Change this filename from config-sample.php to config.php
  to rebrand the settings page and output of this plugin.' . "\n\n" );
      fwrite( $handle, '------------------------------------------------------------ */' . "\n\n" );
      fwrite ( $handle, '$brand = \'YOURBRAND\';' . "\n" );
      fwrite ( $handle, '$brand_domain = \'example.com\';' . "\n" );
      fwrite ( $handle, '$powered_by = \'Powered by <a href="https://www.example.com">YOURBRAND</a>\'' . "\n\n" );
      fwrite ( $handle, "?>" );
      fclose ( $handle );
    }
    return true;
  }

  /**
   * Runs when the plugin is initialized
   */
  function init_reviewstream() {

    // Setup localization
    load_plugin_textdomain( self::slug, false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
    // Load JavaScript and stylesheets
    $this->register_scripts_and_styles();

    // Check for custom config options
    $uploads = wp_upload_dir();
    $settings_file = $uploads['basedir'].'/reviewstream-settings/config.php';
    if(file_exists($settings_file)) {
      include ($settings_file);
      $this->brand = $brand;
      $this->brand_domain = sanitize_url($brand_domain, ['http', 'https']);
      $this->powered_by = esc_html($powered_by);
    } else {
      $this->brand = 'Grade.us';
      $this->brand_domain = 'grade.us';
      $this->powered_by = esc_html('Powered by <a href="https://www.grade.us/home" title="Grade.us Review Management Software">Grade.us</a>');
    }

    // Register the shortcode [reviewstream]
    add_shortcode( 'reviewstream', array( &$this, 'render_shortcode' ) );

    if ( is_admin() ) {
      //this will run when in the WordPress admin
    } else {
      //this will run when on the frontend
    }

  }

  // main reviewstream shortcode
  function render_shortcode($atts) {
    $base_url = "https://www.grade.us/api/v3/profiles";
    $token = preg_replace( '/[^a-zA-Z0-9]/', '', get_option('rs_api_token') );

    // Extract the attributes while sanitizing them
    extract(shortcode_atts(array(
      'path' => sanitize_url(preg_replace('/\/{2,}/', '/', '/' . get_option('rs_path'))),
      'count' => intval(preg_replace( '/[^0-9]/', '', strval(get_option('rs_default_count')))),
      'type' => preg_replace('/[^a-zA-Z]/', '', strval(get_option('rs_type'))),
      'format' => preg_replace('/[^a-zA-Z]/', '', strval(get_option('rs_schema'))),
      'display' => preg_replace('/[^a-zA-Z]/', '', strval(get_option('rs_review_display'))),
      'schema_direct_only' => get_option('rs_schema_direct_only'),
      'show_aggregate_rating' => get_option('rs_show_aggregate_rating'),
      'last_initial' => get_option('rs_last_initial', false),
      'show_reviews' => get_option('rs_show_reviews'),
      'include_empty' => get_option('rs_include_empty', true),
      'stream_only' => get_option('rs_stream_only'),
      'show_powered_by' => get_option('rs_show_powered_by'),
      'show_pager' => get_option('rs_pager')
      ), $atts));
    // Set defaults just in case

    $count = ($count > 0 && $count <= 50 ? $count : 1);

    if($type != 'Product') {
      // Default
      $type = 'LocalBusiness';
    }
    if($format != 'plain') {
      // Default
      $format = 'jsonld';
    }
    if($schema_direct_only != 'true' && $schema_direct_only != true) {
      $schema_direct_only = false;
    }
    if($show_aggregate_rating != 'false' && $show_aggregate_rating != false) {
      // Default
      $show_aggregate_rating = true;
    } else {
      $show_aggregate_rating = false;
    }
    if($show_reviews != 'false' && $show_reviews != false) {
      // Default
      $show_reviews = true;
    } else {
      $show_reviews = false;
    }
    if($include_empty != 'false' && $include_empty != false) {
      $include_empty = true;
    } else {
      $include_empty = false;
    }
    if($stream_only != 'false' && $stream_only != false) {
      $stream_only = true;
    } else {
      $stream_only = false;
    }
    if($last_initial != 'true' && $last_initial != true) {
      $last_initial = false;
    }
    if($show_powered_by != 'false' && $show_powered_by != false) {
      // Default
      $show_powered_by = true;
    } else {
      $show_powered_by = false;
    }

    $fetchcount = $count;

    if($display != 'carousel' && $display != 'Carousel') {
      $display = 'List';
    }
    // load carousel assets
    if(strtolower($display) == 'carousel' && $count > 1) {
      $assetpath = 'https://static.reviewmgr.com/assets/';
      wp_register_script('rs_flexslider', $assetpath.'jquery.flexslider.js?v='.date('Ymd'), array('jquery'));
      wp_register_style('rs_flexslidercss', $assetpath.'flexslider.css?v='.date('Ymd'));
      wp_register_script('rs_sliderstart', $assetpath.'flexslider.js?v='.date('Ymd'));
      wp_enqueue_script('rs_flexslider');
      wp_enqueue_style('rs_flexslidercss');
      wp_enqueue_script('rs_sliderstart');
    }
    // Build the query, remove double slashes
    $query = preg_replace('/\/{2,}/', '/', $path.'/reviews/?count='.$fetchcount);
    $emptystatus = 'noempty';
    if($include_empty) {
      $emptystatus = 'empty';
      $query .= '&include_empty=true';
    } else {
      $query .= '&include_empty=false';
    }
    $laststatus = 'lastfull';
    if($last_initial) {
      $laststatus = 'lastinitial';
      $query .= '&last_initial=true';
    } else {
      $query .= '&last_initial=false';
    }
    $streamstatus = 'streamonly';
    if($stream_only) {
      $query .= '&stream=true';
    } else {
      $streamstatus = 'allreviews';
      $query .= '&stream=false';
    }
    // pager vars
    $offset = 0;
    $rsp = 1;
    $shownextlink = $showprevlink = false;
    if($display=='List' && $show_pager) {
      if(isset($_GET['rsp'])) {
        $rsp = intval($_GET['rsp']);
        $offset = ($rsp-1) * $count;
        if($rsp > 1) {
          $showprevlink = true;
        }
        // API V3 uses page only.  Leaving this code in for unique key for get_transient()
        //$query .= '&offset='.$offset;
      }
    }

    $query .= "&page={$rsp}";

    $url = sanitize_url($base_url . $query, ['http', 'https']);
    $args = array(
      'headers' => array(
        'Content-Type' => 'application/json',
        'Authorization' => "Token token=$token"
      ),
      'timeout' => 30
    );

    // Retrieve from cache or get and set cache
    $cache_expires_in = 3600 * 3; // 3 hours, change to 60 seconds for easier debugging
    $cache_key = "reviewstream-{$path}-{$rsp}-{$fetchcount}-{$token}-{$emptystatus}-{$laststatus}-{$streamstatus}";
    $response = get_transient($cache_key);
    if ($response === false) {
      $req = wp_remote_get($url, $args);

      if(is_wp_error($req)) {
        $req_errors = join(" +-+ ", $req->get_error_messages());
        return $this->rs_error_formatter($req_errors);
      } else {
        if($req['response']['code'] == 200) {
          $response = $req['body'];
          if(!function_exists('curl_version')) {
            // CURL not enabled, manually inflate the result
            $response = gzinflate($req['body']);
          }
          set_transient($cache_key, $response, $cache_expires_in);
        } else {
          $req_errors = "Request Error. Status: {$req['response']['code']}. Body: {$req['body']}";
          return $this->rs_error_formatter($req_errors);
        }
      }
    }
    // Get JSON into assoc array
    $response = json_decode($response, true);
    $output = '';
    $suffix = $schema_direct_only ? 'plain' : $format;
    $review_total = $response['total_count'];
    // Add aggregate rating content
    if ($show_aggregate_rating) {
      $template_content = file_get_contents(dirname(__FILE__).'/templates/aggregate_rating_'.$format.'.php');
      $widget = '<div class="rating-widget"><span class="stars">';
      for($s=1;$s<=$response['total_ratings_max'];$s++) {
        $class = 'star-md';
        if($response['total_ratings_average'] > $s-0.3) {
          //$class .= '';
        } elseif($response['total_ratings_average'] > $s-0.8) {
          $class .= '-half';
        } else {
          $class .= '-off';
        }
        $widget .= '<i class="'.$class.'">&nbsp;</i>';
      }
      $widget .= '</span></div>';
      $template_content = str_replace('[[ratings_widget]]', $widget, $template_content);
      $template_content = str_replace('[[ratings_average]]', number_format((float)$response['total_ratings_average'], 1, '.', ''), $template_content);
      $template_content = str_replace('[[ratings_max]]', $response['total_ratings_max'], $template_content);
      $template_content = str_replace('[[reviews_count]]', $response['total_count'], $template_content);
      $template_content = str_replace('[[business_category]]', $type, $template_content);
      $output .= $template_content;
    }
    // Add review content
    if ($show_reviews) {
      $standard_template_content = file_get_contents(dirname(__FILE__).'/templates/review_'.$suffix.'.php');
      $schema_template_content = file_get_contents(dirname(__FILE__).'/templates/review_'.$format.'.php');
      $tokens = array('category', 'attribution', 'escaped_snippet', 'snippet', 'rating', 'url', 'link');
      if(strtolower($display) == 'carousel' && $count > 1) {
        $output .= '<div class="flexslider"><ul class="slides">';
      }
      foreach($response['reviews'] as $indresp) {
        $output .= (strtolower($display) == 'carousel' && $count > 1)?'<li>':'';
        $widget = '<div class="rating-widget"><span class="stars">';
        for($s=1;$s<=$response['total_ratings_max'];$s++) {
          $class = 'star-sm';
          if($indresp['rating'] > $s-0.3) {
            //$class .= '';
          } elseif($indresp['rating'] > $s-0.8) {
            $class .= '-half';
          } else {
            $class .= '-off';
          }
          $widget .= '<i class="'.$class.'">&nbsp;</i>';
        }
        $widget .= '</span></div>';
        if($indresp['category'] == 'direct') {
          $tempcontent = $schema_template_content;
        } else {
          $tempcontent = $standard_template_content;
        }
        $dt = date('Y-m-d', strtotime($indresp['date']));
        $tempcontent = str_replace('[[reviewdate]]', $dt, $tempcontent);
        $tempcontent = str_replace('[[ratingwidget]]', $widget, $tempcontent);
        foreach($tokens as $token) {
          $replace = '';
          switch ($token) {
            case 'link':
              if (!empty($indresp['url'])) {
                $replace = '<a href="' . $indresp['url'] . '" target="_blank">View full review here</a>';
              }
              break;
            case 'escaped_snippet':
              $replace = json_encode($indresp['snippet']);
              break;
            default:
              $replace = $indresp[$token];
          }
          $tempcontent = str_replace('[['.$token.']]', $replace, $tempcontent);
        }
        $output .= $tempcontent;
        $output .= (strtolower($display) == 'carousel' && $count > 1)?'</li>':'';
      }
    }
    $output .= (strtolower($display) == 'carousel' && $count > 1)?'</ul></div>':'';
    $template_wrapper = file_get_contents(dirname(__FILE__).'/templates/wrapper_'.$format.'.php');
    $template_wrapper = str_replace('[[type]]', $type, $template_wrapper);
    $template_wrapper = str_replace('[[streamclass]]', strtolower($display), $template_wrapper);
    $output = str_replace('[[content]]', $output, $template_wrapper);
    $output = str_replace('[[name]]', $response['name'], $output);
    $output = str_replace('[[image_url]]', $response['image_url'], $output);
    $output = str_replace('[[business_category]]', $type, $output);

    //pager if applicable
    if(($offset + intval($count)) < $review_total) {
      $shownextlink = true;
    }
    if ($show_pager) {
      $pagerfrom = $offset + 1;
      $pagerto = $offset + intval($count);
      if($pagerto > $review_total) {
        $pagerto = $review_total;
      }
      $pager_copy = '<div class="reviewstream-pager-summary">Showing '.$pagerfrom.' to '.$pagerto.' of ' . $review_total . ' entries</div>';
      if($showprevlink) {
        if($rsp-1 == 1) {
          $rsprevurl = add_query_arg(array('rsp' => false));
        } else {
          $rsprevurl = add_query_arg(array('rsp' => $rsp-1));
        }
        $pager_copy .= '<a class="reviewstream-pager-previous" href="'.$rsprevurl.'">« Previous</a> ';
      }
      if($shownextlink) {
        $rspnexturl = add_query_arg(array('rsp' => $rsp+1));
        $pager_copy .= '<a class="reviewstream-pager-next" href="'.$rspnexturl.'">Next »</a>';
      }
      $output = str_replace('[[pager]]', $pager_copy, $output);
    } else {
      $output = str_replace('[[pager]]', '', $output);
    }

    //powered by
    if ($show_powered_by) {
      $output = str_replace('[[powered_by]]', $this->powered_by, $output);
    } else {
      $output = str_replace('[[powered_by]]', '', $output);
    }
    return $output;
  }

  /**
   * Registers and enqueues stylesheets for the administration panel and the
   * public facing site.
   */
  private function register_scripts_and_styles() {
    if ( !is_admin() ) {
      wp_enqueue_style('reviewstream', 'https://static.reviewmgr.com/assets/reviewstream.css?v='.date('Ymd'));
    }
  } // end register_scripts_and_styles

  /**
   * Helper function for registering and enqueueing scripts and styles.
   *
   * @name  The   ID to register with WordPress
   * @file_path   The path to the actual file
   * @is_script   Optional argument for if the incoming file_path is a JavaScript source file.
   */
  private function load_file( $name, $file_path, $is_script = false ) {

    $url = plugins_url($file_path, __FILE__);
    $file = plugin_dir_path(__FILE__) . $file_path;

    if( file_exists( $file ) ) {
      if( $is_script ) {
        wp_register_script( $name, $url, array('jquery') ); //depends on jquery
        wp_enqueue_script( $name );
      } else {
        wp_register_style( $name, $url );
        wp_enqueue_style( $name );
      } // end if
    } // end if

  } // end load_file

  function rs_error_formatter($error) {
    return "<div class=\"rs-error\">Error connecting, check your Review Stream settings<pre style=\"display: none;\">$error</pre></div>";
  }
  /* admin_init */
  public function admin_init() {
    $this->init_settings();
  }

  public function init_settings() {
    register_setting('wprs_group', 'rs_type');
    register_setting('wprs_group', 'rs_schema');
    register_setting('wprs_group', 'rs_schema_direct_only');
    register_setting('wprs_group', 'rs_path', array($this, 'rs_path_validate'));
    register_setting('wprs_group', 'rs_api_token', array($this, 'rs_token_validate'));
    register_setting('wprs_group', 'rs_show_aggregate_rating');
    register_setting('wprs_group', 'rs_last_initial');
    register_setting('wprs_group', 'rs_show_reviews');
    register_setting('wprs_group', 'rs_show_powered_by');
    register_setting('wprs_group', 'rs_default_count', array($this, 'rs_default_count_validate')/*'intval'*/);
    register_setting('wprs_group', 'rs_review_display');
    register_setting('wprs_group', 'rs_include_empty');
    register_setting('wprs_group', 'rs_stream_only');
    register_setting('wprs_group', 'rs_pager');
  }
  public function add_menu() {
    add_options_page('Review Stream Settings', 'Review Stream', 'manage_options', 'wprs_plugin', array(&$this, 'wprs_settings_page'));
  }

  public function wprs_settings_page() {
    if(!current_user_can('manage_options')) {
      wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
  }

  function rs_default_count_validate($input) {
    $max = 50;
    $newinput = intval($input);
    if($newinput < 1) {
      $newinput = 1;
      add_settings_error('rs_default_count', esc_attr('settings_updated'), 'Default count must be at least 1. Minimum value has been added.');
    }
    if($newinput > $max) {
      $newinput = $max;
      add_settings_error('rs_default_count', esc_attr('settings_updated'), 'Default count cannot be more than '.$max.'. Maximum value has been added.');
    }
    return $newinput;
  }

  function rs_path_validate($input) {
    if(empty($input)) {
      add_settings_error('rs_path', esc_attr('settings_updated'), 'Path cannot be empty.', 'error');
    }
    return $input;
  }

  function rs_token_validate($input) {
    if(empty($input)) {
      add_settings_error('rs_api_token', esc_attr('settings_updated'), 'API token cannot be empty.', 'error');
    }
    return $input;
  }
} // end class

if(class_exists('ReviewStream')) {
  $reviewstream = new ReviewStream();
}

if(isset($reviewstream)) {
  function rs_plugin_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=wprs_plugin">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
  }
  $plugin = plugin_basename(__FILE__);
  add_filter("plugin_action_links_$plugin", 'rs_plugin_settings_link');
}
?>
