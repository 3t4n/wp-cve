<?php
/*
    Plugin Name: AddFunc WYSIWYG Helper
    Plugin URI:
    Description: Reveals the prominent HTML elements in the default WYSIWYG editor (TinyMCE) comprehensively, while maintaining edibility as well as any theme styles (in most cases). In effect, you have a WYSIWYG and a WYSIWYM (What You See Is What You Mean) combined. Can also cancel out certain default WordPress styling in the WYSIWYG such as the captions box/border.
    Version: 5.0
    Author: AddFunc
    Author URI: http://profiles.wordpress.org/addfunc
    License: Public Domain
    @since 3.0.1
           ______
       _  |  ___/   _ _ __   ____
     _| |_| |__| | | | '_ \ / __/™
    |_ Add|  _/| |_| | | | | (__
      |_| |_|   \__,_|_| |_|\___\
                    by Joe Rhoney
*/



/*
    F U N C T I O N S
    =================
*/

add_action('init', 'aFCrntUsrMeta');
$aFCrntUsrID = '';
$aFWYSIWYM = '';
function aFCrntUsrMeta(){
  global $current_user, $aFCrntUsrID, $aFWYSIWYM;
  $aFCrntUsrID = $current_user->ID;
  $aFWYSIWYM = get_user_meta($aFCrntUsrID,'aFWYSIWYM',true);
}
function aFWHcss( $mce_css ) {
  global $aFWYSIWYM;
  if ($aFWYSIWYM == 1) {
    if (!empty($mce_css)) $mce_css .= ',';
    $mce_css .= plugins_url( 'wysiwym.css', __FILE__ );
  }
  return $mce_css;
}
if(is_admin()){
  add_filter('mce_css','aFWHcss');
}



/*
    H E L P   T A B
    ===============
*/

add_action('init','aFWHAddHT');
function aFWHHelpTab() {
    $screen = get_current_screen();
    $screen->add_help_tab( array(
        'id'      => 'aFWHHelpTab',
        'title'   => __('Highlighted Content'),
        'content' => '
        <p>'.__( 'The colored borders and highlighting you see in Visual editing mode are there to reveal for you what and where some of the various HTML elements are that comprise the content you are editing.' ).'</p>
        <p><strong>'.__( 'Legend' ).'</strong></p>
        <ul>
          <li style="border-radius:3px;box-sizing:border-box;border:1px solid rgba(255,0,105,0.15);">
            <strong style="padding: 0 4px 0 3px;border: 0;display: inline-block;background:rgba(255,0,105,0.15);">P</strong>
            '.__('paragraph').'
          </li>
          <li style="border-radius:3px;box-sizing:border-box;border:1px solid rgba(255,17,0,0.3);">
            <strong style="padding: 0 4px 0 3px;border: 0;display: inline-block;background:rgba(255,17,0,0.3);">&#182</strong>
            '.__('article').'
          </li>
          <li style="border-radius:3px;box-sizing:border-box;border:1px solid rgba(255,99,0,0.35);">
            <strong style="padding: 0 4px 0 3px;border: 0;display: inline-block;background:rgba(255,99,0,0.35);">{</strong>
            '.__('figure').'
          </li>
          <li style="border-radius:3px;box-sizing:border-box;border:1px solid rgba(255,150,0,0.35);">
            <strong style="padding: 0 4px 0 3px;border: 0;display: inline-block;background: rgba(255,150,0,0.35);">L</strong>
            '.__('unordered list').'
          </li>
          <li style="border-radius:3px;box-sizing:border-box;border:1px solid rgba(255,215,0,0.3);">
            <strong style="padding: 0 4px 0 3px;border: 0;display: inline-block;background: rgba(255,215,0,0.3);">#</strong>
            '.__('ordered list').'
          </li>
          <li style="border-radius:3px;box-sizing:border-box;border:1px solid rgba(205,255,0,0.4);">
            <strong>('.__('within').' L '.__('or').' #):</strong>
            '.__('individual list item').'
          </li>
          <li style="border-radius:3px;box-sizing:border-box;border:1px solid rgba(180,235,0,0.45);">
            <strong style="padding: 0 4px 0 3px;border: 0;display: inline-block;background: rgba(180,235,0,0.45);">D</strong>
            '.__('div (a box, basically)').'
          </li>
          <li style="border-radius:3px;box-sizing:border-box;border:1px solid rgba(0,235,139,0.3);">
            <strong style="padding: 0 4px 0 3px;border: 0;display: inline-block;background: rgba(0,235,139,0.3);">&amp;</strong>
            '.__('aside').'
          </li>
          <li style="border-radius:3px;box-sizing:border-box;border:1px solid rgba(0,180,235,0.3);">
            <strong style="padding: 0 4px 0 3px;border: 0;display: inline-block;background: rgba(0,180,235,0.3);">1-6</strong>
            '.__('heading 1, heading 2, etc.').'
          </li>
          <li style="border-radius:3px;box-sizing:border-box;border:1px solid rgba(81,90,245,0.3);border-top:12px solid rgba(81,90,245,0.3);">
            <strong style="padding: 0 4px 0 3px;border: 0;display: inline-block;background: rgba(81,90,245,0.3);">S</strong>
            '.__('section').'
          </li>
          <li style="border-radius:3px;box-sizing:border-box;border:1px solid rgba(162,0,255,0.3);">
            <strong style="padding: 0 4px 0 3px;border: 0;display: inline-block;background: rgba(162,0,255,0.3);">H &amp; F</strong>
            '.__('header and footer (respectively)').'
          </li>
          <li style="background:rgba(150,0,255,0.1);outline:5px solid rgba(150,0,255,0.1);">
            <strong>('.__('purple highlights').'):</strong>
            '.__('span (text with added formatting)').'
          </li>
          <li style="border-radius:3px;box-sizing:border-box;border: 1px solid rgba(60,0,0,0.25);">
            <strong style="padding: 0 4px 0 3px;border: 0;display: inline-block;background: rgba(60,0,0,0.25);font-family:Verdana;">&lt;</strong>
            '.__('preformatted (monotype block)').'
          </li>
          <li style="background: rgba(60,60,0,0.1);outline: 3px solid rgba(60,60,0,0.1);">
            <strong>('.__('grey highlights').'):</strong>
            '.__('code (inline monotype)').'
          </li>
        </ul>',
    ));
}
function aFWHAddHT(){
  global $aFWYSIWYM;
  if($aFWYSIWYM == 1) {
    add_action('load-post.php', 'aFWHHelpTab');
  }
}



/*
    U S E R   P R E F E R E N C E S
    ===============================
*/

add_action( 'show_user_profile', 'aFWHUserPref' );
add_action( 'edit_user_profile', 'aFWHUserPref' );

function aFWHUserPref( $user ) { ?>
<h3><?php _e("WYSIWYG Helper", "blank"); ?></h3>
<table class="form-table">
  <tr>
    <th><label for="aFWYSIWYM">WYSIWYM</label></th>
    <td>
      <input type="checkbox" name="aFWYSIWYM" id="aFWYSIWYM" value="1" <?php if (esc_attr( get_the_author_meta( "aFWYSIWYM", $user->ID )) == 1) echo "checked"; ?> /><label for="aFWYSIWYM"><?php _e("Enable"); ?></label><br />
      <p class="description">WYSIWYM = <span style='text-decoration: underline;'>W</span>hat <span style='text-decoration: underline;'>Y</span>ou <span style='text-decoration: underline;'>S</span>ee <span style='text-decoration: underline;'>I</span>s <span style='text-decoration: underline;'>W</span>hat <span style='text-decoration: underline;'>Y</span>ou <span style='text-decoration: underline;'>M</span>ean</p>
    </td>
  </tr>
</table>
<?php }

add_action( 'personal_options_update', 'save_aFWHUserPref' );
add_action( 'edit_user_profile_update', 'save_aFWHUserPref' );

function save_aFWHUserPref( $user_id ) {
  if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }
  update_user_meta( $user_id, 'aFWYSIWYM', $_POST['aFWYSIWYM'] );
}
