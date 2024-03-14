<?php
/*
  Plugin Name: WP Font Awesome Share Icons
Plugin URI: http://www.hostivate.com/en/blog/wp-font-awesome-share-icons/
Description: Font Awesome Share Icons for Wordpress 
Author: Spyros Vlachopoulos
Version: 1.1.1
Author URI: http://www.nitroweb.gr/
*/

// Load plugin textdomain
add_action( 'plugins_loaded', 'wpfai_load_textdomain' );
function wpfai_load_textdomain() {
  load_plugin_textdomain( 'wpfai', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}


$wpfai_socialmedia = array(
    'facebook' => array(
        'name' => 'Facebook',
        'icon' => 'facebook',
        'link' => 'http://www.facebook.com/sharer.php?u={url}&amp;t={title}'
    ),
    'twitter' => array(
        'name' => 'Twitter',
        'icon' => 'twitter',
        'link' => 'http://twitter.com/share?text={title}&url={url}'
    ),
    'google-plus' => array(
        'name' => 'Google Plus',
        'icon' => 'google-plus',
        'link' => 'https://plus.google.com/share?url={url}'
    ),
    'pinterest' => array(
        'name' => 'Pinterest',
        'icon' => 'pinterest',
        'link' => 'http://pinterest.com/pin/create/button/?url={url}&amp;description={title}&amp;media={image}'
    ),
    'linkedin' => array(
        'name' => 'Linked In',
        'icon' => 'linkedin',
        'link' => 'http://linkedin.com/shareArticle?mini=true&amp;url={url}&amp;title={title}'
    ),
    'tumblr' => array(
        'name' => 'Tumblr',
        'icon' => 'tumblr',
        'link' => 'http://www.tumblr.com/share/link?url={url}&amp;name={title}&amp;description={excerpt}'
    ),
    'vk' => array(
        'name' => 'Vkontakte',
        'icon' => 'vk',
        'link' => 'http://vk.com/share.php?url={url}'
    ),
    'stumbleupon' => array(
        'name' => 'Stumble Upon',
        'icon' => 'stumbleupon',
        'link' => 'http://www.stumbleupon.com/submit?url={url}'
    ),
    'delicious' => array(
        'name' => 'Delicious',
        'icon' => 'delicious',
        'link' => 'http://del.icio.us/post?url={url}&amp;title={title}'
    ),
    'digg' => array(
        'name' => 'Digg',
        'icon' => 'digg',
        'link' => 'http://digg.com/submit?url={url}&amp;title={title}'
    ),
    'reddit' => array(
        'name' => 'Reddit',
        'icon' => 'reddit',
        'link' => 'http://www.reddit.com/submit?url={url}'
    ),
    'xing' => array(
        'name' => 'Xing',
        'icon' => 'xing',
        'link' => 'https://www.xing-share.com/app/user?op=share;sc_p=xing-share;url={url}'
    ),
    'weibo' => array(
        'name' => 'Weibo',
        'icon' => 'weibo',
        'link' => 'http://service.weibo.com/share/share.php?url={url}&amp;appkey=&amp;title={title}&amp;pic={image}&amp;ralateUid=&amp;language=zh_cn'
    ),
    'renren' => array(
        'name' => 'Renren',
        'icon' => 'renren',
        'link' => 'http://share.renren.com/share/buttonshare?link={url}&amp;title={title}'
    ),
    'bullseye' => array(
        'name' => 'Specific Feeds',
        'icon' => 'bullseye',
        'link' => 'http://www.specificfeeds.com/follow'
    ),
    'envelope' => array(
        'name' => 'E-Mail',
        'icon' => 'envelope',
        'link' => "mailto:?subject={title}&amp;body={url}%20-%20{excerpt}"
    ),
    'code' => array(
        'name' => 'W3C Validation',
        'icon' => 'code',
        'link' => 'http://validator.w3.org/check?uri={url}'
    )
);

// Look... you can add your own icons without hacking my code :P
$wpfai_socialmedia = apply_filters( 'wpfai_array_filter', $wpfai_socialmedia );


add_action('admin_menu', 'wp_fai_options');

function wp_fai_options() {
  add_options_page('Font-Awesome Share', 'Font-Awesome Share', 'manage_options', 'wpfai','wpfai_options');
  add_action( 'admin_init', 'wpfai_register_settings' );
}

// register settings
function wpfai_register_settings(){

  register_setting( 'wpfai_group', 'wpfai' );
  register_setting( 'wpfai_group', 'wpfai_shape' );
  register_setting( 'wpfai_group', 'wpfai_inverse' );
  register_setting( 'wpfai_group', 'wpfai_size' );
  register_setting( 'wpfai_group', 'wpfai_loadfa' );
  register_setting( 'wpfai_group', 'wpfai_head_text' );
  register_setting( 'wpfai_group', 'wpfai_place' );
  register_setting( 'wpfai_group', 'wpfai_popup' );
  register_setting( 'wpfai_group', 'wpfai_short' );
  register_setting( 'wpfai_group', 'wpfai_counter' );
  register_setting( 'wpfai_group', 'wpfai_counter_minimum' );

}

function wpfai_options() {
  global $wpfai_socialmedia;
  ?>
  <div class="wrap">
    <h2><?php _e('Font Awesome Share Icons For Wordpress', 'wpfai'); ?></h2>
    <form method="post" action="options.php">
      <?php settings_fields( 'wpfai_group' ); ?>
      <?php do_settings_sections( 'wpfai_group' ); ?>

      <?php

      $alloptions = array();

      $regoptions = get_option('wpfai');
      $regshape = get_option('wpfai_shape');
      $reginverse = get_option('wpfai_inverse');
      $regsize = get_option('wpfai_size');
      $regloadfa = get_option('wpfai_loadfa');
      $reg_head_text = get_option('wpfai_head_text');
      $reg_place = get_option('wpfai_place');
      $wpfai_popup = get_option('wpfai_popup');
      $wpfai_short = get_option('wpfai_short');
      $wpfai_counter = get_option('wpfai_counter');
      $wpfai_counter_minimum = get_option('wpfai_counter_minimum');

      // sort array by previous saved sorting
      if (!empty($regoptions)) {
        $wpfai_socialmedia = array_merge($regoptions, $wpfai_socialmedia);
      }

      echo '<ul class="sociallist sortablelist">';
      foreach ($wpfai_socialmedia as $sm) {
        if (!isset($regoptions[$sm['icon']])) { $regoptions[$sm['icon']] = 'off'; }
        echo '<li class="social-item">
                <label class="social_label" for="wpfai_'. $sm['icon'] .'">
                <input id="wpfai_'. $sm['icon'] .'" type="checkbox" name="wpfai['.$sm['icon'].']" value="on" '. ($regoptions[$sm['icon']] == 'on' ? 'checked="checked"' : '') .'" />
                <div class="iconcol"><a href="#" class="wpfai-'. $sm['icon'] .' wpfai-link"><i class="fa fa-'. $sm['icon'] .'"></i></a></div>'. $sm['name'] .'</label> 
              </li>';
        $alloptions[] = 'wpfai['.$sm['icon'].']';
      }
      echo '</ul>';

      echo '<p>'. submit_button(__('Save Changes')) .'</p>';

      echo '
          <div class="wpfai-updated">
            <p><strong>'. __('W3C validation link will only be displayed when the user is logged in and is admin. If you do not know what this icon is for just ignore it.', 'wpfai') .'</strong></p>
          </div>
          <h3>'. __('Some more settings', 'wpfai') .'</h3>
          <ul class="sociallist">
            <li class="social-item">
              <label class="social_label" for="wpfai_head_text">'. __('Text Before', 'wpfai') .'</label>
              <input type="text" name="wpfai_head_text" value="'. $reg_head_text .'">
            </li>
            <li class="social-item">
              <label class="social_label" for="wpfai_shape">'. __('Shape', 'wpfai') .'</label>
              <select name="wpfai_shape">
                <option value="simple" '. ($regshape == 'simple' ? 'selected' : '') .'>'. __('Simple', 'wpfai') .'</option>
                <option value="square" '. ($regshape == 'square' ? 'selected' : '') .'>'. __('Square', 'wpfai') .'</option>
                <option value="circle" '. ($regshape == 'circle' ? 'selected' : '') .'>'. __('Circle', 'wpfai') .'</option>
                <option value="circle-thin" '. ($regshape == 'circle-thin' ? 'selected' : '') .'>'. __('Thin Circle', 'wpfai') .'</option>
              </select>
            </li>
            <li class="social-item">
              <label class="social_label" for="wpfai_inverse">'. __('Inverse', 'wpfai') .'</label>
              <select name="wpfai_inverse">
                <option value="no" '. ($reginverse == 'no' ? 'selected' : '') .'>'. __('No', 'wpfai') .'</option>
                <option value="yes" '. ($reginverse == 'yes' ? 'selected' : '') .'>'. __('Yes', 'wpfai') .'</option>
              </select>
            </li>
            <li class="social-item">
              <label class="social_label" for="wpfai_size">'. __('Size', 'wpfai') .'</label>
              <select name="wpfai_size">
                <option value="" '. ($regsize == '' ? 'selected' : '') .'></option>
                <option value="lg" '. ($regsize == 'lg' ? 'selected' : '') .'>'. __('LG', 'wpfai') .'</option>
                <option value="2x" '. ($regsize == '2x' ? 'selected' : '') .'>'. __('2x', 'wpfai') .'</option>
                <option value="3x" '. ($regsize == '3x' ? 'selected' : '') .'>'. __('3x', 'wpfai') .'</option>
                <option value="4x" '. ($regsize == '4x' ? 'selected' : '') .'>'. __('4x', 'wpfai') .'</option>
                <option value="5x" '. ($regsize == '5x' ? 'selected' : '') .'>'. __('5x', 'wpfai') .'</option>
              </select>
            </li>
            <li class="social-item">
              <label class="social_label" for="wpfai_loadfa">'. __('Load Font-Awesome Library', 'wpfai') .'</label>
              <select name="wpfai_loadfa">
                <option value="yes" '. ($regloadfa == 'yes' ? 'selected' : '') .'>'. __('Yes', 'wpfai') .'</option>
                <option value="no" '. ($regloadfa == 'no' ? 'selected' : '') .'>'. __('No', 'wpfai') .'</option>
              </select><br /><small>'. __('If you do not know, then select yes. Select no, only if you know that font-awesome is already loaded in your website.', 'wpfai') .'</small>
            </li>
            <li class="social-item">
              <label class="social_label" for="wpfai_place">'. __('Where to display', 'wpfai') .'</label>
              <select name="wpfai_place">
                <option value="no" '. ($reg_place == 'no' ? 'selected' : '') .'>'. __('Nowhere', 'wpfai') .'</option>
                <option value="after" '. ($reg_place == 'after' ? 'selected' : '') .'>'. __('After article', 'wpfai') .'</option>
                <option value="before" '. ($reg_place == 'before' ? 'selected' : '') .'>'. __('Before article', 'wpfai') .'</option>
                <option value="both" '. ($reg_place == 'both' ? 'selected' : '') .'>'. __('Before and after article', 'wpfai') .'</option>
                <option value="after_comment" '. ($reg_place == 'after_comment' ? 'selected' : '') .'>'. __('After comments', 'wpfai') .'</option>
                <option value="after_loop" '. ($reg_place == 'after_loop' ? 'selected' : '') .'>'. __('At the end of the loop', 'wpfai') .'</option>
              </select><br /><small>'. __('If you select "Nowhere", then you can use the shortcode [wpfai_social] in your posts or the code &lt;?php echo wpfai_social(); ?&gt; in your theme files', 'wpfai') .'</small>
            </li>
            <li class="social-item">
              <label class="social_label" for="wpfai_popup">'. __('Open in pop up window', 'wpfai') .'</label>
              <select name="wpfai_popup">
                <option value="no" '. ($wpfai_popup == 'no' ? 'selected' : '') .'>'. __('No', 'wpfai') .'</option>
                <option value="yes" '. ($wpfai_popup == 'yes' ? 'selected' : '') .'>'. __('Yes', 'wpfai') .'</option>
              </select><br /><small>'. __('If you select "Yes", then a pop up will open for the visitor to share the article', 'wpfai') .'</small>
            </li>
            <li class="social-item">
              <label class="social_label" for="wpfai_short">'. __('Use shortlink', 'wpfai') .'</label>
              <select name="wpfai_short">
                <option value="no" '. ($wpfai_short == 'no' ? 'selected' : '') .'>'. __('No', 'wpfai') .'</option>
                <option value="yes" '. ($wpfai_short == 'yes' ? 'selected' : '') .'>'. __('Yes', 'wpfai') .'</option>
              </select><br /><small>'. __('If you select "Yes", then the link will NOT be to the permalink but to the shortlink (Jetpack should create a wp.me shortlink)', 'wpfai') .'</small>
            </li>
            <li class="social-item">
              <label class="social_label" for="wpfai_counter">'. __('Display shares counter', 'wpfai') .'</label>
              <select name="wpfai_counter">
                <option value="no" '. ($wpfai_counter == 'no' ? 'selected' : '') .'>'. __('No', 'wpfai') .'</option>
                <option value="right" '. ($wpfai_counter == 'right' ? 'selected' : '') .'>'. __('Right', 'wpfai') .'</option>
              </select><br /><small>'. __('Please select to display the counter or not and its placement', 'wpfai') .'</small>
            </li>
            <li class="social-item">
              <label class="social_label" for="wpfai_counter_minimum">'. __('Minimum shares to show', 'wpfai') .'</label>
              <input type="number" step="1" name="wpfai_counter_minimum" value="'. $wpfai_counter_minimum .'">
              <br /><small>'. __('Specify the minimum amount of shares before showing the shares counter.', 'wpfai') .'</small>
            </li>
          </ul>
          ';

      //$allfields = array('wpfai','wpfai_shape','wpfai_inverse','wpfai_size','wpfai_loadfa','wpfai_head_text','wpfai_place','wpfai_popup','wpfai_counter');
      ?>
      <p><?php submit_button(__('Save Changes')); ?></p>
    </form>
  </div>
  <style>
    .social_label { display: inline-block; width: 290px; }
    .social-item { margin-bottom: 3px; padding-bottom: 3px; border-bottom: 1px solid #ccc; }
    .social-item select, .social-item input[type="text"] { min-width: 200px; }
    .iconcol { width: 30px; padding-right: 5px; text-align: center; display: inline-block; }
    .wpfai-updated {
      margin: 5px 0 15px;
      border-left: 4px solid #7AD03A;
      padding: 1px 12px;
      background-color: #FFF;
      -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.1);
      box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.1);

    }
  </style>
  <script>
    jQuery(document).ready(function(){
      jQuery( ".sortablelist" ).sortable();
    });
  </script>
  <?php
}


// function to return the icons
function wpfai_social($atts = '') {
  global $wpfai_socialmedia;

  $a = shortcode_atts( array(
      'icons' => '',
      'shape' => '',
      'inverse' => '',
      'size' => '',
      'loadfa' => '',
      'pretext' => '',
      'place' => '',
      'popup' => '',
      'short' => '',
      'counter' => '',
      'counter_minimum' => ''
  ), $atts );

  $output = '';

  $allsm = array();
  $allsmar = array();
  if ($atts != '' && $a['icons'] != '') {
    $allsm = explode(',',$a['icons']);
    foreach ($allsm as $asm) {
      $allsmar[$asm] = 'on';
    }
  }

  // get all plugin options
  $regoptions = ($atts != '' && $a['icons'] != '' ? $allsmar : get_option('wpfai'));
  $regshape = ($atts != '' && $a['shape'] != '' ? $a['shape'] : get_option('wpfai_shape'));
  $reginverse = ($atts != '' && $a['inverse'] != '' ? $a['inverse'] : get_option('wpfai_inverse'));
  $regsize = ($atts != '' && $a['size'] != '' ? $a['size'] : get_option('wpfai_size'));
  $regloadfa = ($atts != '' && $a['loadfa'] != '' ? $a['loadfa'] : get_option('wpfai_loadfa'));
  $reg_head_text = ($atts != '' && $a['pretext'] != '' ? $a['pretext'] : get_option('wpfai_head_text'));
  $reg_place = ($atts != '' && $a['place'] != '' ? $a['place'] : get_option('wpfai_place'));
  $wpfai_popup = ($atts != '' && $a['popup'] != '' ? $a['popup'] : get_option('wpfai_popup'));
  $wpfai_short = ($atts != '' && $a['short'] != '' ? $a['short'] : get_option('wpfai_short'));
  $wpfai_counter = ($atts != '' && $a['counter'] != '' ? $a['counter'] : get_option('wpfai_counter'));
  $wpfai_counter_minimum = ($atts != '' && $a['counter_minimum'] != '' ? $a['counter_minimum'] : get_option('wpfai_counter_minimum'));

  if (!is_super_admin()) { unset($regoptions['code']); } // show validation link only to admins

  $url = urlencode(esc_url($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]));
  $title = wp_title('', false);
  $image = '';
  $excerpt = '';


  $postid = 0;
  // it is a page or post or archive loop
  if (in_the_loop() && $reg_place != 'after_loop') {
    global $post;

    $postid = $post->ID;
    $url = urlencode(esc_url(get_permalink($post->ID)));
    if ($wpfai_short == 'yes') { $url = wp_get_shortlink($post->ID); }
    $title = __($post->post_title, 'wpfai');
    $img = '';
    $img = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
    $image = $img[0];
    $thetext = '';
    $thetext = trim(wp_trim_words(strip_tags(strip_shortcodes($post->post_content))));
    $excerpt = rawurlencode(str_replace('&amp;hellip;','', htmlspecialchars(__($thetext, 'wpfai'))));
  }

  $title = rawurlencode(htmlspecialchars(trim($title)));

  $find = array('{url}', '{title}', '{image}', '{excerpt}');
  $replace = array($url, $title, $image, $excerpt);

  // count shares
  $obj = '';
  if ($wpfai_counter != 'no' && $wpfai_counter != false) {
    include_once ('counter.php');
    $obj = new shareCount($url);
  }
  if ($reg_head_text != '') { $output .= '<h4>'. __($reg_head_text, 'wpfai') .'</h4>'; }

  // fix wrong combination by users
  if ($reginverse == 'yes' && $regshape == 'circle-thin') {
    $regshape = 'circle';
  }

  $output .= '<ul class="wpfai-list">';
  
  foreach ($regoptions as $icon => $sm) {

    $classname = 'get_'.str_replace('-','_', $icon);

    if ($obj != '') {
      $share_count = $obj->{$classname}();
    }

    $output .= '<li class="wpfai-list-item '. $icon .'">
      <a href="'. str_replace($find, $replace, $wpfai_socialmedia[$icon]['link']) .'" title="'. $wpfai_socialmedia[$icon]['name'] .'" class="wpfai-'. $icon .' wpfai-link'. ($wpfai_popup == 'yes' ? ' wpfainw' : '') .'">
        <span class="fa-stack '. ($regsize != '' ? 'fa-'. $regsize : '') .'">
          '. ($regshape != 'simple' ? '<i class="fa fa-'. $regshape .''. ($reginverse == 'yes' || (( $reginverse == 'no' && $regshape == 'circle-thin') ) ? '' : '-o') .' fa-stack-2x"></i>': '') .'
          <i class="fa fa-'. $icon .' fa-stack-1x '. ($reginverse == 'yes' ? 'fa-inverse' : '') .'"></i>
        </span>'. (isset($share_count, $wpfai_counter_minimum) && $share_count >= $wpfai_counter_minimum && $share_count !== false ? '<div class="counter">' . $share_count . '</div>' : '') .'
      </a>
    </li>';

  }
  $output .= '</ul>';

  // filter for the final plugin output
  $output = apply_filters( 'wpfai_output_filter', $output );

  return ($output);
}

add_shortcode( 'wpfai_social', 'wpfai_social' );


/********** add to selected positions START **********/

function wpfai_after_content($content) { $content = $content . wpfai_social(); return $content; }
if (get_option('wpfai_place') == 'after') {  add_filter('the_content', 'wpfai_after_content'); }

function wpfai_before_content($content) { $content = wpfai_social() . $content; return $content; }
if (get_option('wpfai_place') == 'before') {  add_filter('the_content', 'wpfai_before_content'); }

function wpfai_both_content($content) { $content = wpfai_social() . $content . wpfai_social(); return $content; }
if (get_option('wpfai_place') == 'both') {  add_filter('the_content', 'wpfai_both_content'); }

function wpfai_after_comment() { echo wpfai_social(); }
if (get_option('wpfai_place') == 'after_comment') {  add_filter('comment_form_after', 'wpfai_after_comment'); }

function wpfai_after_loop() { echo wpfai_social(); }
if (get_option('wpfai_place') == 'after_loop') {  add_filter('loop_end', 'wpfai_after_loop'); }


/********** add to selected positions END **********/

// load font awesome or not
function wpfai_load_scripts () {

  if (wpfai_check_css('font-awesome.css') == 0 && wpfai_check_css('font-awesome.min.css') == 0  && get_option('wpfai_loadfa') == 'yes') {
    wp_register_style( 'wpfai_font-awesome', plugins_url( '/wp-font-awesome-share-icons/fontawesome/css/font-awesome.min.css' ) );
    wp_enqueue_style( 'wpfai_font-awesome' );
  }

  wp_register_style( 'wpfai_style', plugins_url( '/wp-font-awesome-share-icons/style.css' ) );
  wp_enqueue_style( 'wpfai_style' );

  if (get_option('wpfai_popup') == 'yes') {
    wp_enqueue_script( 'wpfai_js',  plugins_url( '/wp-font-awesome-share-icons/wpfai.js' ), array('jquery') );
  }

}
add_action("wp_enqueue_scripts", "wpfai_load_scripts");

// load font awesome or not
function wpfai_load_admin_scripts () {

  if (isset($_GET['page']) && $_GET['page'] == 'wpfai') {
    wp_enqueue_script("jquery-ui-core");
    wp_enqueue_script("jquery-ui-sortable");
    wp_enqueue_script("jquery-ui-draggable");
    wp_enqueue_script("jquery-ui-droppable");
    wp_register_style( 'wpfai_font-awesome', plugins_url( '/wp-font-awesome-share-icons/fontawesome/css/font-awesome.min.css' ) );
    wp_enqueue_style( 'wpfai_font-awesome' );
    wp_register_style( 'wpfai_style', plugins_url( '/wp-font-awesome-share-icons/style.css' ) );
    wp_enqueue_style( 'wpfai_style' );
  }

}
add_action("admin_enqueue_scripts", "wpfai_load_admin_scripts");

// check css

function wpfai_check_css($lookfor) {
  global $wp_styles;

  if (!empty($wp_styles->registered)) {
    foreach ($wp_styles->registered as $key => $obj) {
      if (strpos($obj->src,$lookfor) !== false || strpos($obj->src,$lookfor) !== false) { return 1; }
    }
  }
  return 0;
}
?>