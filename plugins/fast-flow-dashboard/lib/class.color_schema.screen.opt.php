<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class FF_dashboard_color_schema_screen_opt
{
  /**
   * The ajax action
   */
  const ACTION = 'ff_dashboard_color_schema_save';

  /**
   * Our nonce name
   */
  const NONCE = 'ff_dashboard_color_schema_nonce';

  /**
   * Init function.  Called from outside the class.  Adds actions and such.
   *
   * @access public
   * @return null
   */
  public static function init()
  {
      add_action(
          'load-toplevel_page_fast-flow',
          array(get_class(), 'load')
      );

      add_action(
          'wp_ajax_' . self::ACTION,
          array(get_class(), 'ajax')
      );

  }

  /**
   * Hooked into `load-post-new.php`.  Adds an option and
   * hooks into a few other actions/filters
   *
   * @access public
   * @return null
   */
  public static function load()
  {
      add_filter(
          'screen_settings',
          array(get_class(), 'add_field'),
          10,
          2
      );

      add_action(
          'admin_head',
          array(get_class(), 'head')
      );

      add_filter(
          'enter_color_schema_here',
          array(get_class(), 'color_schema')
      );

      $screen = get_current_screen();
      $user = wp_get_current_user();
      $default_color_schema = get_user_option(
          sprintf('default_color_schema_%s', sanitize_key($screen->id)),
          $user->ID
      );
      if(empty($default_color_schema)){
        update_user_option(
            $user->ID,
            "default_color_schema_{$screen->id}",
            'classic'
        );
      }

  }

  /**
   * Hooked into `screen_settings`.  Adds the field to the settings area
   *
   * @access public
   * @return string The settings fields
   */
  public static function add_field($rv, $screen)
  {
      $val = get_user_option(
          sprintf('default_color_schema_%s', sanitize_key($screen->id)),
          get_current_user_id()
      );
      $min_val = get_user_option(
          sprintf('default_is_minimal_color_schema_%s', sanitize_key($screen->id)),
          get_current_user_id()
      );
      $rv .= '<div class="ff_dashboard_color_schema-container">';
      $rv .= '<h5>' . __('Default Styles') . '</h5>';
      $rv .= '<p><label><input type="radio" name="color_schema" class="normal-text" class="ff_dashboard_color_schema_field" ' .
          'value="classic" '.(($val == 'classic')?'checked="checked"':'').'>Classic</label>';
      $rv .= '<label><input type="radio" name="color_schema" class="normal-text" class="ff_dashboard_color_schema_field" ' .
          'value="light" '.(($val == 'light')?'checked=""':'').'>Light</label>';
      $rv .= '<label><input type="radio" name="color_schema" class="normal-text" class="ff_dashboard_color_schema_field" ' .
              'value="dark" '.(($val == 'dark')?'checked=""':'').'>Dark</label>';
      $rv .= '<label><input type="checkbox" name="is_minimal_color_schema" class="normal-text" class="ff_dashboard_minimal_color_schema_field" ' .
          'value="1" '.(($min_val == '1')?'checked="checked"':'').'>Minimal</label></p>';
      $rv .= wp_nonce_field(self::NONCE, self::NONCE, false, false);
      $rv .= '</div>';
      return $rv;
  }

  /**
   * Hooked into `admin_head`.  Spits out some JS to save the info
   *
   * @access public
   * @return null
   */
  public static function head()
  {
      ?>
      <script type="text/javascript">
          jQuery(document).ready(function() {
              jQuery('input[type=radio][name=color_schema]').change(function() {
                var is_minimal_color_schema = 0;
                if(jQuery('input[type=checkbox][name=is_minimal_color_schema]').is(':checked')){
                   is_minimal_color_schema = 1;
                }
                  jQuery.post(
                      ajaxurl,
                      {
                          color_schema: jQuery(this).val(),
                          is_minimal_color_schema: is_minimal_color_schema,
                          nonce: jQuery('input#<?php echo esc_js(self::NONCE); ?>').val(),
                          screen: '<?php echo esc_js(get_current_screen()->id); ?>',
                          action: '<?php echo self::ACTION; ?>',
                      }, function ( data ) {
                          if(data.success == true){
                            jQuery('body').removeClass('ff-d-light');
                            jQuery('body').removeClass('ff-d-dark');
                            jQuery('body').removeClass('ff-d-minimal');
                            if(data.color_schema == 'light'){
                              jQuery('body').addClass('ff-d-light');
                            }else if(data.color_schema == 'dark'){
                              jQuery('body').addClass('ff-d-dark');
                            }
                            if(data.is_minimal_color_schema == 1){
                                jQuery('body').addClass('ff-d-minimal');
                            }
                          }else{

                          }
                      }, 'json'
                  )
              });
              jQuery('input[type=checkbox][name=is_minimal_color_schema]').change(function() {
                var is_minimal_color_schema = 0;
                if(jQuery(this).is(':checked')){
                   is_minimal_color_schema = jQuery(this).val();
                }
                  jQuery.post(
                      ajaxurl,
                      {
                          color_schema: jQuery('input[type=radio][name=color_schema]:checked').val(),
                          is_minimal_color_schema: is_minimal_color_schema,
                          nonce: jQuery('input#<?php echo esc_js(self::NONCE); ?>').val(),
                          screen: '<?php echo esc_js(get_current_screen()->id); ?>',
                          action: '<?php echo self::ACTION; ?>',
                      }, function ( data ) {
                          if(data.success == true){
                            jQuery('body').removeClass('ff-d-light');
                            jQuery('body').removeClass('ff-d-dark');
                            jQuery('body').removeClass('ff-d-minimal');
                            if(data.color_schema == 'light'){
                              jQuery('body').addClass('ff-d-light');
                            }else if(data.color_schema == 'dark'){
                              jQuery('body').addClass('ff-d-dark');
                            }
                            if(data.is_minimal_color_schema == 1){
                                jQuery('body').addClass('ff-d-minimal');
                            }
                          }else{

                          }
                      }, 'json'
                  )
              });
          });
      </script>
      <?php
  }

  /**
   * Hooked into `wp_ajax_self::ACTION`  Handles saving the fields and such
   *
   * @access public
   * @return null
   */
  public static function ajax()
  {
      check_ajax_referer(self::NONCE, 'nonce');
      $screen = isset($_POST['screen']) ? wp_filter_nohtml_kses($_POST['screen']) : false;
      $color_schema = isset($_POST['color_schema']) ? wp_filter_nohtml_kses($_POST['color_schema']) : false;
      $is_minimal_color_schema = isset($_POST['is_minimal_color_schema']) ? wp_filter_nohtml_kses($_POST['is_minimal_color_schema']) : 0;

      if(!$screen || !($user = wp_get_current_user()))
      {
          die(json_encode(array('success'=> false)));
      }

      if(!$screen = sanitize_key($screen))
      {
          die(json_encode(array('success'=> false)));
      }
      if($color_schema){
        update_user_option(
            $user->ID,
            "default_color_schema_{$screen}",
            esc_attr(strip_tags($color_schema))
        );
      }
        update_user_option(
            $user->ID,
            "default_is_minimal_color_schema_{$screen}",
            $is_minimal_color_schema
        );


      die(json_encode(array('success'=> true,'color_schema' => $color_schema,'is_minimal_color_schema' => $is_minimal_color_schema)));
  }

  /**
   * Hooked into `enter_title_here`.  Replaces the title with the user's
   * preference (if it exists).
   *
   * @access public
   * @return string The Default title
   */
  public static function color_schema($t)
  {
      if(!$user = wp_get_current_user())
          return $t;
      $id = sanitize_key(get_current_screen()->id);
      if($color_schema = get_user_option("default_color_schema_{$id}", $user->ID))
      {
          $t = esc_attr($color_schema);
      }
      if($is_minimal_color_schema = get_user_option("default_is_minimal_color_schema_{$id}", $user->ID))
      {
          $t = esc_attr($is_minimal_color_schema);
      }
      return $t;
  }
}
