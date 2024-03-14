<?php
/**
 * Plugin name: Change Case
 * Author: Michael Aronoff
 * Version: 2.3
 * Description: Adds Change Case adds buttons to change text case in the WordPress visual editor.
 * */

if ( !class_exists( "change_case" ) ):

  /**
   * Change Case
   */
  class change_case {

  function __construct() {
    define( "CC_URL", WP_PLUGIN_URL.'/'.str_replace( basename( __FILE__ ), "", plugin_basename( __FILE__ ) ) );
    define( "CC_PLUGIN_DIR", "change-case-for-tinymce" );
    define( "CC_PLUGIN_URL", get_bloginfo( 'url' )."/wp-content/plugins/" . CC_PLUGIN_DIR );
    register_activation_hook( __FILE__, array( __CLASS__, "register" ) );
    add_action( 'init', array( __CLASS__, 'add_button' ) );
    add_filter( 'tiny_mce_version', array( __CLASS__, 'refresh_mce' ) );
    add_action( 'admin_menu', array( __CLASS__, 'menu' ) );
  }

  /* TINY MCE */

  public static function register() {
    $values= array( "ac"=>1, "nc"=>1, "tc"=>1, "sc"=>1 );
    if ( get_option( "CC_HR_OPTIONS" ) ) {
      $current = get_option( "CC_HR_OPTIONS" );
      if ( is_serialized( $current ) ) {
        $current = unserialize( $current );
      }
      $values = array_merge( $values, $current );
      update_option( "CC_HR_OPTIONS", $values );
    }
    else {
      add_option( "CC_HR_OPTIONS", $values );
    }
  }

  public static function add_button() {
    if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) )
      return;
    if ( get_user_option( 'rich_editing' ) == 'true' ) {
      add_filter( 'mce_external_plugins', array( __CLASS__, 'add_tinymce_plugin' ) );
      add_filter( 'mce_buttons', array( __CLASS__, 'register_button' ) );
    }
  }

  public static function register_button( $buttons ) {
    $current = get_option( "CC_HR_OPTIONS" );
    if ( is_serialized( $current ) ) {$current = unserialize( $current );}
    array_push( $buttons, "|" );
    if ( $current['ac'] == 1 ) {
      array_push( $buttons,  "allcaps" );
    }
    if ( $current['nc'] == 1 ) {
      array_push( $buttons,  "nocaps" );
    }
	if ( $current['sc'] == 1 ) {
      array_push( $buttons,  "sentencecase" );
    }
    if ( $current['tc'] == 1 ) {
      array_push( $buttons,  "titlecase" );
    }
    return $buttons;
  }

  static function add_tinymce_plugin( $plugin_array ) {
      $plugin_array['ChangeCase'] = CC_PLUGIN_URL . '/js/cc.js';
      return $plugin_array;
  }

  function refresh_mce( $ver ) {
    $ver += 7;
    return $ver;
  }

  public static function menu() {
    add_submenu_page( 'options-general.php', 'Change Case', 'Change Case', 'edit_posts', 'change-case', array( __CLASS__, 'options' ) );
  }

  public static function options() {
    $current = get_option( "CC_HR_OPTIONS" );
    if ( is_serialized( $current ) ) {$current = unserialize( $current );}
?>
    <div class='wrap'>
     	<style type="text/css"> 
		 	.cc-table { width: 320px; }
		 	.cc-table th { text-align: left; }
			.cc-table-active, .cc-keyboard, div th .cc-keyboard { text-align: center; }
		</style>
      <div style='float:left;'>
        <h1>Change Case provided by Michael Aronoff of <a href="http://www.ciic.com" target="_blank">CIIC</a></h1>
        <p class="description">The options below are to choose which buttons are added to the tinyMCE editor.</p>
        <p class="description">There are also Keyboard Shortcuts for each function. The Keyboard Shortcuts will work even if the button for that function is turned off.</p>
        <form method="post" action="options.php">
          <?php wp_nonce_field( 'update-options' ); ?>
          <table class="cc-table">
           <thead>
            <tr valign="top">
              <th width="40%" scope="row">Button</th>
              <th class="cc-table-active" width="16%">Active?
              <th class="cc-keyboard" width="44%">Keyboard Shortcut</td>
            </tr>
			</thead>
           <tbody>
            <tr valign="top">
              <th scope="row"><label for="CC_HR_OPTIONS['ac']">All Uppercase: </label></th>
              <td class="cc-table-active"><input type="checkbox" name="CC_HR_OPTIONS[ac]" id="CC_HR_OPTIONS['ac']" value='1' <?php if ( $current['ac']==1 ) {echo "checked='checked'";}?> /></td>
              <td class="cc-keyboard">Ctrl+Alt+u</td>
            </tr>

            <tr valign="top">
              <th scope="row"><label for="CC_HR_OPTIONS['nc']">All Lowercase: </label></th>
              <td class="cc-table-active" ><input type="checkbox" name="CC_HR_OPTIONS[nc]" id="CC_HR_OPTIONS['nc']" value='1' <?php if ( $current['nc']==1 ) {echo "checked='checked'";}?> /></td>
              <td class="cc-keyboard">Ctrl+Alt+l</td>
            </tr>
            <tr valign="top">
              <th scope="row"><label for="CC_HR_OPTIONS['sc']">Sentence Case: </label></th>
              <td class="cc-table-active" ><input type="checkbox" name="CC_HR_OPTIONS[sc]" id="CC_HR_OPTIONS['sc']" value='1' <?php if ( $current['sc']==1 ) {echo "checked='checked'";}?> /></td>
              <td class="cc-keyboard">Ctrl+Alt+s</td>
            </tr>
            <tr valign="top">
              <th scope="row"><label for="CC_HR_OPTIONS['tc']">Title Case: </label></th>
              <td class="cc-table-active" ><input type="checkbox" name="CC_HR_OPTIONS[tc]" id="CC_HR_OPTIONS['tc']" value='1' <?php if ( $current['tc']==1 ) {echo "checked='checked'";}?> /></td>
              <td class="cc-keyboard">Ctrl+Alt+t</td>
            </tr>
		  </tbody>
          </table>
          <input type="hidden" name="action" value="update" />
          <input type="hidden" name="page_options" value="CC_HR_OPTIONS" />
          <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>" />
          </p>
        </form>
      </div>
    </div>
<?php
  }
}

$change_case = new change_case();
endif;
?>