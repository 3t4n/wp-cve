<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="pdckl_admin_css_leftbox">
  <h3><?php echo $pdckl_lang['css_title']; ?></h3>
  <p><?php echo $pdckl_lang['css_desc']; ?></p>
  <form name="pdckl_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
      <textarea name="pdckl_css" class="pdckl_admin_css_textarea"><?php echo file_get_contents(plugins_url('../assets/css/podclankova-inzerce.css', __FILE__)); ?></textarea>
      <input type="hidden" name="pdckl_hidden" value="css_save"> <br />
      <?php echo wp_nonce_field( 'save-css' ); ?>
      <input type="submit" class="button-primary" style="float:left;" value="<?php echo $pdckl_lang['btn_save']; ?>">
  </form>
  <form name="pdckl_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
      <input type="hidden" name="pdckl_hidden" value="css_reset">
      <?php echo wp_nonce_field( 'reset-css' ); ?>
      <input type="submit" class="button action" style="float:right;" value="<?php echo $pdckl_lang['btn_original']; ?>">
  </form>
</div>

<div class="pdckl_admin_css_rightbox">
  <h3><?php echo $pdckl_lang['css_preview']; ?></h3>
  <p>&nbsp;</p>
  <div class="pdckl_box">
  <?php
  $price_extra = explode(" ", get_option('pdckl_price_extra'));

  if(get_option('WPLANG') ==  'sk_SK') {
    require_once(dirname(__FILE__) . '/../lang/sk_box.php');
  } else {
    require_once(dirname(__FILE__) . '/../lang/cz_box.php');
  }

  if($price_extra[0] == 0) {
    $price = get_option('pdckl_price');
  } else {
    if(strtotime("now") > strtotime($published_extra)) {
      $price = $price_extra[0];
    } else {
      $price = get_option('pdckl_price');
    }
  }

    include(__DIR__ . '/../box.php');
    _e($gateway);
  ?>
  </div>
</div>
