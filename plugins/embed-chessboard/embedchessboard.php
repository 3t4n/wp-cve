<?php

/*
Plugin Name: Embed Chessboard
Plugin URI: http://wordpress.org/extend/plugins/embed-chessboard/
Description: Embeds a javascript chessboard in wordpress articles for replaying chess games. Insert chess games in PGN format into your wordpress article using the shortcode syntax <code>[pgn parameter=value ...] e4 e6 d4 d5 [/pgn]</code> directly into the classic editor or as shortcode block in the gutenberg editor. Use plugin options to blend the chessboard with the site template; use tag parameters to customize each chessboard. For more info on plugin options and tag parameters please <a href="http://pgn4web-project.casaschi.net/wiki/User_Notes_wordpress/">read the tutorial</a>.
Version: 3.06.00
Author: Paolo Casaschi
Author URI: http://pgn4web.casaschi.net
Copyright: copyright (C) 2009-2023 Paolo Casaschi

ChangeLog:
  1.00  - initial release, based on pgn4web version 1.88 and
          on the Embed Iframe plugin of Deskera (http://deskera.com)
  1.01  - minor modifications for hosting on wordpress.org/extend/plugins
  1.02  - fixing the "Cannot modify header information" warning
  1.03  - properly detecting wordpress address URI
  1.04  - minor fix
  1.05  - major rewrite simplyfying the plugin core (replacing the Embed Iframe template
          with a template from the bbcode plugin of Viper007Bond http://www.viper007bond.com/)
          added the option to configure chessboard colors, see settings submenu
  1.06  - minor fix
  1.07  - changed settings names (you might need to enter your custom config again)
  1.08  - added option for controlling autoplay of games at load
  1.09  - added options to the pgn tag [pgn parameter=value ...] ... [/pgn]
          and upgraded pgn4web to 1.89
  1.10  - added tutorial info on the admin page
  1.11  - added advanced option with the CSS style for the HTML DIV container of the plugin frame
  1.12  - added admin option and tag parameter to set horizontal/vertical layout
  1.13  - bug fixes and upgraded pgn4web to 1.92
  1.14  - bug fixes
  1.15  - more bug fixes and upgraded pgn4web to 1.93
  1.16  - upgraded pgn4web to 1.94 with search tool addition
  1.17  - minor bug fix
  1.18  - upgraded pgn4web to 1.95 and minor bug fix
  1.19  - upgraded pgn4web to 1.96 and minor bug fix
  1.20  - upgraded pgn4web to 1.97
  1.21  - upgraded pgn4web to 1.98
  1.22  - upgraded pgn4web to 2.02 with improved PGN error handling
  1.23  - upgraded pgn4web to 2.03
  1.24  - upgraded pgn4web to 2.04
  1.25  - minor bug fix
  1.26  - added rawurlencode() to url parameters and upgraded pgn4web to 2.05
  1.27  - added extendedOptions switch to the [pgn] tag and upgraded pgn4web to 2.06
  1.28  - upgraded pgn4web to 2.07, inlcuding Chess960 support
  1.29  - upgraded pgn4web to 2.08, fixing a bug in the square highlight code
  1.30  - upgraded pgn4web to 2.09, fixing a bug with IE
  1.31  - upgraded pgn4web to 2.10 and minor bug fix
  1.32  - upgraded pgn4web to 2.11 and minor bug fix
  1.33  - upgraded pgn4web to 2.12
  1.34  - enhanced frame height management and upgraded pgn4web to 2.13
  1.35  - upgraded pgn4web to 2.14
  1.36  - upgraded pgn4web to 2.15
  1.37  - upgraded pgn4web to 2.16
  1.38  - upgraded pgn4web to 2.17
  1.39  - updated compatibility flag from 3.0 to 3.1 and upgraded pgn4web to 2.17+
  1.40  - upgraded pgn4web to 2.18
  1.41  - upgraded pgn4web to 2.21
  1.42  - upgraded pgn4web to 2.22
  1.43  - upgraded pgn4web to 2.23
  1.44  - upgraded pgn4web to 2.24
  1.45  - upgraded pgn4web to 2.25
  1.46  - upgraded pgn4web to 2.26
  1.47  - upgraded pgn4web to 2.27
  1.48  - upgraded pgn4web to 2.29
  1.49  - minor bug fix
  1.50  - upgraded pgn4web to 2.31
  1.51  - upgraded pgn4web to 2.32
  1.52  - minor bug fix and upgraded pgn4web to 2.33
  1.53  - added undocumented global extended options setting and upgraded pgn4web to 2.34
  1.54  - upgraded pgn4web to 2.35
  1.55  - minor bug fix for IE9 and upgraded pgn4web to 2.36
  1.56  - minor bug fix for IE9 and upgraded pgn4web to 2.37
  1.57  - minor bug fix for IE9 and upgraded pgn4web to 2.38
  1.58  - minor bug fix for IE9 and upgraded pgn4web to 2.39
  1.59  - upgraded pgn4web to 2.40
  1.60  - upgraded pgn4web to 2.41
  1.61  - upgraded pgn4web to 2.42
  1.62  - upgraded pgn4web to 2.43
  1.63  - upgraded pgn4web to 2.46 with variations support
  1.64  - upgraded pgn4web to 2.47
  1.65  - upgraded pgn4web to 2.48
  1.66  - upgraded pgn4web to 2.49
  1.67  - upgraded pgn4web to 2.51
  1.68  - upgraded pgn4web to 2.52
  1.69  - upgraded pgn4web to 2.53
  1.70  - upgraded pgn4web to 2.54
  1.71  - upgraded pgn4web to 2.55 and minor bug fix
  1.72  - upgraded pgn4web to 2.56
  1.74  - added basic support for localization of the plugin settings page
  1.75  - minor improvements
  1.76  - upgraded pgn4web to 2.57
  1.77  - minor improvements
  1.78  - upgraded pgn4web to 2.58
  1.79  - upgraded pgn4web to 2.59
  1.80  - added [pgn4web] tag and upgraded pgn4web to 2.60
  1.81  - minor fix
  1.82  - upgraded pgn4web to 2.61
  1.83  - bug fix
  1.84  - upgraded pgn4web to 2.62
  1.85  - upgraded pgn4web to 2.63
  1.86  - upgraded pgn4web to 2.64
  1.87  - upgraded pgn4web to 2.65
  1.88  - upgraded pgn4web to 2.66
  1.89  - upgraded pgn4web to 2.67
  1.90  - upgraded pgn4web to 2.68
  1.91  - upgraded pgn4web to 2.69 and minor bug fix
  1.92  - upgraded pgn4web to 2.70
  1.93  - upgraded pgn4web to 2.71
  1.94  - upgraded pgn4web to 2.72
  1.95  - bug fix: http://wordpress.org/support/topic/cant-activate-the-plugin-2
  1.96  - upgraded pgn4web to 2.73
  1.97  - upgraded pgn4web to 2.74
  2.75.00 - changed version numbering scheme and upgraded pgn4web to 2.75
  2.76.00 - upgraded pgn4web to 2.76
  2.77.00 - upgraded pgn4web to 2.77 with improved chess informant style game text
  2.78.00 - upgraded pgn4web to 2.78
  2.78.01 - updated compatibility flag to 3.8
  2.79.00 - upgraded pgn4web to 2.79
  2.80.00 - minor fix and upgraded pgn4web to 2.80
  2.81.00 - upgraded pgn4web to 2.81
  2.82.00 - upgraded pgn4web to 2.82
  2.83.00 - upgraded pgn4web to 2.83
  2.84.00 - upgraded pgn4web to 2.84
  2.85.00 - prevented texturization of the pgn shortcodes, checked compatibility with wordpress.org 4.0 and upgraded pgn4web to 2.85
  2.86.00 - upgraded pgn4web to 2.86
  2.87.00 - upgraded pgn4web to 2.87
  2.88.00 - upgraded pgn4web to 2.88
  2.88.01 - updated compatibility flag to 4.1
  2.89.00 - upgraded pgn4web to 2.89
  2.90.00 - upgraded pgn4web to 2.90
  2.91.00 - upgraded pgn4web to 2.91
  2.91.01 - updated compatibility flag to 4.2
  2.92.00 - upgraded pgn4web to 2.92
  2.93.00 - upgraded pgn4web to 2.93 and updated compatibility flag to 4.3
  2.94.00 - upgraded pgn4web to 2.94
  2.95.00 - upgraded pgn4web to 2.95
  2.95.01 - updated compatibility flag to 4.4
  2.96.00 - upgraded pgn4web to 2.96
  3.00.00 - upgraded pgn4web to 3.00
  3.01.00 - upgraded pgn4web to 3.01
  3.02.00 - upgraded pgn4web to 3.02
  3.03.00 - upgraded pgn4web to 3.03
  3.03.01 - improved compatibility with gutenberg editor by creating an Embed Chessboard reusable block
  3.03.03 - bug fix
  3.03.04 - bug fix
  3.04.00 - upgraded pgn4web to 3.04
  3.04.01 - fixed issue with deprecated constructor method with the same name as their class
  3.04.02 - fixed issue with deprecated function create_function
  3.05.00 - upgraded pgn4web to 3.05
  3.05.01 - removed creation of the Embed Chessboard reusable block
  3.05.02 - bug fix, coping with open texturize bug in wordpress/gutenberg
  3.05.03 - fixed issue with php 8.1, see https://sourceforge.net/p/pgn4web/tickets/173/
  3.06.00 - upgraded pgn4web to 3.06
*/


// Define shortcode class

class pgnBBCode {

  // Plugin initialization
  function __construct() {
    // This version only supports WP 2.5+
    if ( !function_exists('add_shortcode') ) return;

    // Register the shortcodes
    add_shortcode( 'pgn' , array(&$this, 'shortcode_pgn') );
    add_shortcode( 'pgn4web' , array(&$this, 'shortcode_pgn') );
  }

  // pgn shortcode
  function shortcode_pgn( $atts = array(), $content = NULL ) {
    if ( NULL === $content ) return '';

    // [pgn height=auto showMoves=figurine initialGame=1 initialVariation=0 initialHalfmove=0 autoplayMode=loop] e4 e6 d4 d5 [/pgn]

    $pgnText = $content;
    $pgnText = preg_replace("@(<.*?>)+@", " ", $pgnText);
    $pgnText = preg_replace("@(\n|\r)+@", " ", $pgnText);
    $pgnText = str_replace(array("<", ">"), array("&lt;", "&gt;"), $pgnText);

    if ( !empty($atts) ) {
      foreach ($atts as &$value) {
        $value = preg_replace("@<.*?>@", "", $value);
        $value = preg_replace("@(<.*?$|^.*?>)@", "", $value);
      }
    }

    if ( isset($atts['layout']) ) { $layout = $atts['layout']; }
    elseif ( isset($atts['l']) ) { $layout = $atts['l']; }
    if ( isset($atts['layout']) || isset($atts['l'])) {
      if (($layout == "horizontal") || ($layout == "h")) { $horizontalLayout = "t"; }
      elseif (($layout == "vertical") || ($layout == "v")) { $horizontalLayout = "f"; }
      else { $horizontalLayout = "f"; }
    } else {
      $horizontalLayout = get_embedchessboard_option('embedchessboard_horizontal_layout');
    }

    if ( isset($atts['showmoves']) ) { $movesDisplay = $atts['showmoves']; }
    elseif ( isset($atts['sm']) ) { $movesDisplay = $atts['sm']; }
    else { $movesDisplay = 'f'; }

    if (($movesDisplay == 'puzzle') || ($movesDisplay == 'p')) { $headerDisplay = 'v'; }
    else { $headerDisplay = 'j'; }

    if ( isset($atts['height']) ) { $height = $atts['height']; }
    elseif ( isset($atts['h']) ) { $height = $atts['h']; }
    elseif ( isset($atts[0]) ) { $height = $atts[0]; } // compatibility with v < 1.09
    else { $height = get_embedchessboard_option('embedchessboard_height'); }

    $skipParameters = array('layout', 'l', 'showmoves', 'sm', 'height', 'h', 'initialgame', 'ig', 'initialVariation', 'iv', 'initialhalfmove', 'ih', 'autoplaymode', 'am', 'extendedoptions', 'eo');
    $pgnParameters = array('pgntext', 'pt', 'pgnencoded', 'pe', 'fenstring', 'fs', 'pgnid', 'pi', 'pgndata', 'pd');
    $pgnSourceOverride = false;
    $extendedOptionsString = get_embedchessboard_option('embedchessboard_extended_options');
    if ($extendedOptionsString != '') {
      $extendedOptionsString = preg_replace('/^\s+/', '', $extendedOptionsString);
      $extendedOptionsString = preg_replace('/\s+$/', '', $extendedOptionsString);
      $extendedOptionsString = preg_replace('/&amp;/', '&', $extendedOptionsString);
      $extendedOptionsString = preg_replace('/(^|\s+)/', '&', $extendedOptionsString);
      foreach ($skipParameters as &$value) {
        $extendedOptionsString = preg_replace('/&' . $value . '=[^&]*/', '', $extendedOptionsString);
      }
      foreach ($pgnParameters as &$value) {
        if (preg_match('/&' . $value . '=[^&]*/', $extendedOptionsString)) {
          $pgnSourceOverride = true;
          break;
        }
      }
    }
    if ( isset($atts['extendedoptions']) ) { $extendedOptions = $atts['extendedoptions']; }
    elseif ( isset($atts['eo']) ) { $extendedOptions = htmlspecialchars($atts['eo']); }
    else { $extendedOptions = 'false'; }
    if (($extendedOptions == 'true') || ($extendedOptions == 't')) {
      foreach ($atts as $key => &$value) {
        if (in_array(strtolower($key), $skipParameters)) { continue; }
        if (in_array(strtolower($key), $pgnParameters)) { $pgnSourceOverride = true;  }
        $extendedOptionsString .= '&' . rawurlencode($key) . '=' . rawurlencode($value);
      }
    }

    if (($height == "auto") || (strlen($height) == 0)) {
      $height = 268; // 26*8 squares + 3*2 border + 13*2 padding + 28 buttons
      // guessing if one game or multiple games are supplied
      $multiGamesRegexp = '/\s*\[\s*\w+\s*"[^"]*"\s*\]\s*[^\s\[\]]+[\s\S]*\[\s*\w+\s*"[^"]*"\s*\]\s*/';
      if ($pgnSourceOverride || (preg_match($multiGamesRegexp, $pgnText) > 0)) { $height += 34; }
      if ($horizontalLayout == "t") {
        $frameHeight = "b";
      } else {
        $height += 75; // header
        if (($movesDisplay != 'hidden') && ($movesDisplay != 'h')) { $height += 300; } // moves
        $frameHeight = $height;
      }
    } else {
      $frameHeight = $height;
    }

    if ( isset($atts['initialgame']) ) { $initialGame = $atts['initialgame']; }
    elseif ( isset($atts['ig']) ) { $initialGame = $atts['ig']; }
    else { $initialGame = 'f'; }

    if ( isset($atts['initialvariation']) ) { $initialVariation = $atts['initialvariation']; }
    elseif ( isset($atts['iv']) ) { $initialVariation = $atts['iv']; }
    else { $initialVariation = '0'; }

    if ( isset($atts['initialhalfmove']) ) { $initialHalfmove = $atts['initialhalfmove']; }
    elseif ( isset($atts['ih']) ) { $initialHalfmove = $atts['ih']; }
    else { $initialHalfmove = 's'; }

    if ( isset($atts['autoplaymode']) ) { $autoplayMode = $atts['autoplaymode']; }
    elseif ( isset($atts['am']) ) { $autoplayMode = $atts['am']; }
    else { $autoplayMode = get_embedchessboard_option('embedchessboard_autoplay_mode'); }

    $pgnId = "pgn4web_" . dechex(crc32($pgnText));

    $containerStyle = get_embedchessboard_option('embedchessboard_container_style');
    if ($containerStyle == '') { $replacement  = "<div class='chessboard-wrapper'>"; }
    else { $replacement  = "<div style='" . $containerStyle . "' class='chessboard-wrapper'>"; }

    // bugfix: adding an hidden PRE element outside the textarea in order to avoid the unwanted texturize of the shortcode due to the wordpress/gutenberg bug https://github.com/WordPress/gutenberg/issues/37754 see also https://core.trac.wordpress.org/ticket/54614 and https://wordpress.org/support/topic/bug-in-twenty-twenty-two-theme-shipping-with-wordpress-org-5-9/
    $replacement .= "<pre style='display:none;'>";
    $replacement .= "<textarea id='" . $pgnId . "' style='display:none;' cols='40' rows='8'>";
    $replacement .= $pgnText;
    $replacement .= "</textarea>";
    // bugfix: closing the PRE element, see note few lines above
    $replacement .= "</pre>";
    $replacement .= "<iframe src='" . plugins_url("pgn4web/board.html", __FILE__) . "?";
    $replacement .= "am=" . rawurlencode($autoplayMode);
    $replacement .= "&amp;d=3000";
    $replacement .= "&amp;ig=" . rawurlencode($initialGame);
    $replacement .= "&amp;iv=" . rawurlencode($initialVariation);
    $replacement .= "&amp;ih=" . rawurlencode($initialHalfmove);
    $replacement .= "&amp;ss=26&amp;ps=d&amp;pf=d";
    $replacement .= "&amp;lch=" . rawurlencode(get_embedchessboard_option('embedchessboard_light_squares_color'));
    $replacement .= "&amp;dch=" . rawurlencode(get_embedchessboard_option('embedchessboard_dark_squares_color'));
    $replacement .= "&amp;bbch=" . rawurlencode(get_embedchessboard_option('embedchessboard_board_border_color'));
    $replacement .= "&amp;hm=b";
    $replacement .= "&amp;hch=" . rawurlencode(get_embedchessboard_option('embedchessboard_square_highlight_color'));
    $replacement .= "&amp;bd=c";
    $replacement .= "&amp;cbch=" . rawurlencode(get_embedchessboard_option('embedchessboard_control_buttons_background_color'));
    $replacement .= "&amp;ctch=" . rawurlencode(get_embedchessboard_option('embedchessboard_control_buttons_text_color'));
    $replacement .= "&amp;hd=" . rawurlencode($headerDisplay);
    $replacement .= "&amp;md=" . rawurlencode($movesDisplay);
    $replacement .= "&amp;tm=13";
    $replacement .= "&amp;fhch=" . rawurlencode(get_embedchessboard_option('embedchessboard_header_text_color'));
    $replacement .= "&amp;fhs=14";
    $replacement .= "&amp;fmch=" . rawurlencode(get_embedchessboard_option('embedchessboard_moves_text_color'));
    $replacement .= "&amp;fcch=" . rawurlencode(get_embedchessboard_option('embedchessboard_comments_text_color'));
    $replacement .= "&amp;hmch=" . rawurlencode(get_embedchessboard_option('embedchessboard_move_highlight_color'));
    $replacement .= "&amp;fms=14&amp;fcs=m&amp;cd=i";
    $replacement .= "&amp;bch=" . rawurlencode(get_embedchessboard_option('embedchessboard_background_color'));
    $replacement .= "&amp;fp=13";
    $replacement .= "&amp;hl=" . rawurlencode($horizontalLayout);
    $replacement .= "&amp;fh=" . rawurlencode($frameHeight);
    $replacement .= "&amp;fw=p";
    if (!$pgnSourceOverride) { $replacement .= "&amp;pi=" . rawurlencode($pgnId); }
    $replacement .= preg_replace("/&/", "&amp;", $extendedOptionsString) . "' ";
    $replacement .= "frameborder='0' width='100%' height='" . $height . "' ";
    $replacement .= "scrolling='no' marginheight='0' marginwidth='0'>";
    $replacement .= __("your web browser and/or your host do not support iframes as required to display the chessboard; alternatively your wordpress theme might suppress the html iframe tag from articles or excerpts", "embedchess");
    $replacement .= "</iframe>";
    $replacement .= "</div>";

    return $replacement;

  }
}


// Start this plugin once all other plugins are fully loaded

add_action( 'plugins_loaded', function() { global $pgnBBCode; $pgnBBCode = new pgnBBCode(); } );


// Make sure text within the new shortcodes is not texturized

function embedchessboard_no_texturize( $shortcodes ) {
  $shortcodes[] = 'pgn';
  $shortcodes[] = 'pgn4web';
  return $shortcodes;
}

add_filter( 'no_texturize_shortcodes', 'embedchessboard_no_texturize' );


// create custom plugin settings menu

add_action( 'admin_menu', 'embedchessboard_create_menu' );

function embedchessboard_create_menu() {

  //create new sub-level menu from the settings menu
  add_submenu_page( 'options-general.php', 'Embed Chessboard Plugin Settings', 'Embed Chessboard', 'administrator', __FILE__, 'embedchessboard_settings_page' );

  //call register settings function
  add_action( 'admin_init', 'register_embedchessboard_settings' );
}

function register_embedchessboard_settings() {
  //load language
  load_plugin_textdomain( 'embedchess', null, dirname(plugin_basename(__FILE__)) . '/lang' );

  //register our settings
  register_setting( 'embedchessboard-settings-group', 'embedchessboard_horizontal_layout' );
  register_setting( 'embedchessboard-settings-group', 'embedchessboard_height' );
  register_setting( 'embedchessboard-settings-group', 'embedchessboard_background_color' );
  register_setting( 'embedchessboard-settings-group', 'embedchessboard_light_squares_color' );
  register_setting( 'embedchessboard-settings-group', 'embedchessboard_dark_squares_color' );
  register_setting( 'embedchessboard-settings-group', 'embedchessboard_board_border_color' );
  register_setting( 'embedchessboard-settings-group', 'embedchessboard_square_highlight_color' );
  register_setting( 'embedchessboard-settings-group', 'embedchessboard_control_buttons_background_color' );
  register_setting( 'embedchessboard-settings-group', 'embedchessboard_control_buttons_text_color' );
  register_setting( 'embedchessboard-settings-group', 'embedchessboard_header_text_color' );
  register_setting( 'embedchessboard-settings-group', 'embedchessboard_moves_text_color' );
  register_setting( 'embedchessboard-settings-group', 'embedchessboard_move_highlight_color' );
  register_setting( 'embedchessboard-settings-group', 'embedchessboard_comments_text_color' );
  register_setting( 'embedchessboard-settings-group', 'embedchessboard_autoplay_mode' );
  register_setting( 'embedchessboard-settings-group', 'embedchessboard_container_style' );
  register_setting( 'embedchessboard-settings-group', 'embedchessboard_extended_options' );
}

function get_embedchessboard_option($optionName) {
  $retVal = get_option($optionName);

  if (strlen(trim((string) $retVal)) == 0) {
    switch ($optionName) {
      case 'embedchessboard_horizontal_layout':
        $retVal = 'f';
        break;
      case 'embedchessboard_height':
        $retVal = 'auto';
        break;
      case 'embedchessboard_background_color':
        $retVal = 'FFFFFF';
        break;
      case 'embedchessboard_light_squares_color':
        $retVal = 'F6F6F6';
        break;
      case 'embedchessboard_dark_squares_color':
        $retVal = 'E0E0E0';
        break;
      case 'embedchessboard_board_border_color':
        $retVal = 'E0E0E0';
        break;
      case 'embedchessboard_square_highlight_color':
        $retVal = 'ABABAB';
        break;
      case 'embedchessboard_control_buttons_background_color':
        $retVal = 'F0F0F0';
        break;
      case 'embedchessboard_control_buttons_text_color':
        $retVal = '696969';
        break;
      case 'embedchessboard_header_text_color':
        $retVal = '000000';
        break;
      case 'embedchessboard_moves_text_color':
        $retVal = '000000';
        break;
      case 'embedchessboard_move_highlight_color':
        $retVal = 'E0E0E0';
        break;
      case 'embedchessboard_comments_text_color':
        $retVal = '808080';
        break;
      case 'embedchessboard_autoplay_mode':
        $retVal = 'l';
        break;
      case 'embedchessboard_container_style':
        $retVal = '';
        break;
      case 'embedchessboard_extended_options':
        $retVal = '';
        break;
      default:
        $retVal = '';
        break;
    }
  }
  return $retVal;
}

function embedchessboard_settings_page() {
?>
<div class="wrap">
<h2><?php _e("Embed Chessboard Plugin Settings", "embedchess"); ?></h2>
<p style="font-size:smaller; line-height:normal;">
<a href="http://pgn4web-project.casaschi.net/wiki/User_Notes_wordpress/" target="_blank"><?php _e("read the tutorial", "embedchess");?></a> <?php _e("for more details about this plugin", "embedchess"); ?>
</p>
<p style="font-size:smaller; line-height:normal;">
<?php _e("leave blank values to reset to defaults", "embedchess"); ?>
</p>

<script type="text/javascript" src="<?php echo plugins_url('pgn4web/libs/jscolor/jscolor.js', __FILE__) ?>"></script>

<form method="post" action="options.php">
  <?php settings_fields('embedchessboard-settings-group'); ?>
  <table class="form-table">

    <tr><td colspan=3><h3><?php _e("Layout", "embedchess"); ?></h3></td></tr>

    <tr valign="top">
    <th scope="row"><label for="embedchessboard_horizontal_layout"><?php _e("chessboard frame layout", "embedchess"); ?></label></th>
    <td colspan=2>
      <select name="embedchessboard_horizontal_layout">
      <option <?php if ("t" == get_embedchessboard_option('embedchessboard_horizontal_layout')) echo "selected" ?> value="t"><?php _e("horizontal layout", "embedchess"); ?></option>
      <option <?php if ("f" == get_embedchessboard_option('embedchessboard_horizontal_layout')) echo "selected" ?> value="f"><?php _e("vertical layout", "embedchess"); ?></option>
      </select>
    </td>
    </tr>

    <tr valign="top">
    <th scope="row"><label for="embedchessboard_height"><?php _e("chessboard frame height", "embedchess"); ?></label></th>
    <td><input type="text" name="embedchessboard_height" value="<?php echo get_embedchessboard_option('embedchessboard_height'); ?>" /></td>
    <td><p style="font-size:smaller; line-height:normal;"><?php _e("normally set to <b>auto</b>, it can be set to a number to assign the chessboard frame height", "embedchess"); ?></p></td>
    </tr>

    <tr><td colspan=3></td></tr>

    <tr><td colspan=3><h3><?php _e("Colors", "embedchess"); ?></h3></td></tr>

    <tr valign="top">
    <th scope="row"><label for="embedchessboard_background_color"><?php _e("background color", "embedchess"); ?></label></th>
    <td><input class="color {required:false}" type="text" name="embedchessboard_background_color" value="<?php echo get_embedchessboard_option('embedchessboard_background_color'); ?>" /></td>
    <td></td>
    </tr>

    <tr><td colspan=3></td></tr>

    <tr valign="top">
    <th scope="row"><label for="embedchessboard_light_squares_color"><?php _e("light squares color", "embedchess"); ?></label></th>
    <td><input class="color {required:false}" type="text" name="embedchessboard_light_squares_color" value="<?php echo get_embedchessboard_option('embedchessboard_light_squares_color'); ?>" /></td>
    <td></td>
    </tr>

    <tr valign="top">
    <th scope="row"><label for="embedchessboard_dark_squares_color"><?php _e("dark squares color", "embedchess"); ?></label></th>
    <td><input class="color {required:false}" type="text" name="embedchessboard_dark_squares_color" value="<?php echo get_embedchessboard_option('embedchessboard_dark_squares_color'); ?>" /></td>
    <td></td>
    </tr>

    <tr valign="top">
    <th scope="row"><label for="embedchessboard_board_border_color"><?php _e("board border color", "embedchess"); ?></label></th>
    <td><input class="color {required:false}" type="text" name="embedchessboard_board_border_color" value="<?php echo get_embedchessboard_option('embedchessboard_board_border_color'); ?>" /></td>
    <td></td>
    </tr>

    <tr valign="top">
    <th scope="row"><label for="embedchessboard_square_highlight_color"><?php _e("square highlight color", "embedchess"); ?></label></th>
    <td><input class="color {required:false}" type="text" name="embedchessboard_square_highlight_color" value="<?php echo get_embedchessboard_option('embedchessboard_square_highlight_color'); ?>" /></td>
    <td></td>
    </tr>

    <tr><td colspan=3></td></tr>

    <tr valign="top">
    <th scope="row"><label for="embedchessboard_control_buttons_background_color"><?php _e("buttons background color", "embedchess"); ?></label></th>
    <td><input class="color {required:false}" type="text" name="embedchessboard_control_buttons_background_color" value="<?php echo get_embedchessboard_option('embedchessboard_control_buttons_background_color'); ?>" /></td>
    <td></td>
    </tr>

    <tr valign="top">
    <th scope="row"><label for="embedchessboard_control_buttons_text_color"><?php _e("buttons text color", "embedchess"); ?></label></th>
    <td><input class="color {required:false}" type="text" name="embedchessboard_control_buttons_text_color" value="<?php echo get_embedchessboard_option('embedchessboard_control_buttons_text_color'); ?>" /></td>
    <td></td>
    </tr>

    <tr><td colspan=3></td></tr>

    <tr valign="top">
    <th scope="row"><label for="embedchessboard_header_text_color"><?php _e("header text color", "embedchess"); ?></label></th>
    <td><input class="color {required:false}" type="text" name="embedchessboard_header_text_color" value="<?php echo get_embedchessboard_option('embedchessboard_header_text_color'); ?>" /></td>
    <td></td>
    </tr>

    <tr valign="top">
    <th scope="row"><label for="embedchessboard_moves_text_color"><?php _e("moves text color", "embedchess"); ?></label></th>
    <td><input class="color {required:false}" type="text" name="embedchessboard_moves_text_color" value="<?php echo get_embedchessboard_option('embedchessboard_moves_text_color'); ?>" /></td>
    <td></td>
    </tr>

    <tr valign="top">
    <th scope="row"><label for="embedchessboard_move_highlight_color"><?php _e("move highlight color", "embedchess"); ?></label></th>
    <td><input class="color {required:false}" type="text" name="embedchessboard_move_highlight_color" value="<?php echo get_embedchessboard_option('embedchessboard_move_highlight_color'); ?>" /></td>
    <td></td>
    </tr>

    <tr valign="top">
    <th scope="row"><label for="embedchessboard_comments_text_color"><?php _e("comments text color", "embedchess"); ?></label></th>
    <td><input class="color {required:false}" type="text" name="embedchessboard_comments_text_color" value="<?php echo get_embedchessboard_option('embedchessboard_comments_text_color'); ?>" /></td>
    <td></td>
    </tr>

    <tr><td colspan=3></td></tr>

    <tr><td colspan=3><h3><?php _e("Autoplay Mode", "embedchess"); ?></h3></td></tr>

    <tr valign="top">
    <th scope="row"><label for="embedchessboard_autoplay_mode"><?php _e("autoplay mode", "embedchess"); ?></label></th>
    <td colspan=2>
      <select name="embedchessboard_autoplay_mode">
      <option <?php if ("g" == get_embedchessboard_option('embedchessboard_autoplay_mode')) echo "selected" ?> value="g"><?php _e("autoplay the initial game only", "embedchess"); ?></option>
      <option <?php if ("l" == get_embedchessboard_option('embedchessboard_autoplay_mode')) echo "selected" ?> value="l"><?php _e("autoplay all games in a loop", "embedchess"); ?></option>
      <option <?php if ("n" == get_embedchessboard_option('embedchessboard_autoplay_mode')) echo "selected" ?> value="n"><?php _e("do not autoplay games", "embedchess"); ?></option>
      </select>
    </td>
    </tr>

    <tr><td colspan=3></td></tr>

    <tr><td colspan=3><h3><?php _e("Advanced Settings", "embedchess"); ?></h3></td></tr>

    <tr valign="top">
    <th scope="row"><label for="embedchessboard_container_style"><?php _e("CSS style for the HTML DIV container of the plugin frame", "embedchess"); ?></label></th>
    <td><input type="text" name="embedchessboard_container_style" value="<?php echo get_embedchessboard_option('embedchessboard_container_style'); ?>" /></td>
    <td><p style="font-size:smaller; line-height:normal;"><?php _e("normally left blank, it can be used to fix layout issues with certain wordpress templates; for instance, if the chessboard frame is constraint too narrow, setting this parameter as <b>width:500px;</b> might improve the layout", "embedchess"); ?></p></td>
    </tr>

    <tr valign="top">
    <th scope="row"><label for="embedchessboard_extended_options"><?php _e("extended options", "embedchess"); ?></label></th>
    <td><input type="text" name="embedchessboard_extended_options" value="<?php echo get_embedchessboard_option('embedchessboard_extended_options'); ?>" /></td>
    <td><p style="font-size:smaller; line-height:normal;"><?php _e("normally left blank, undocumented feature: improper use will break the chessboard display", "embedchess"); ?></p></td>
    </tr>

    <tr><td colspan=3></td></tr>

  </table>

  <p class="submit">
  <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
  </p>

</form>
</div>
<?php } ?>
