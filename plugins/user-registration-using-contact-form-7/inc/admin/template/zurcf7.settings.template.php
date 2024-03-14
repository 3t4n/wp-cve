<?php
/**
 * Admin Setting Page
 *
 * @package WordPress
 * @package User Registration using Contact Form 7
 * @since 1.0
 */

if ( !defined( 'ABSPATH' ) ) exit;
wp_enqueue_script( 'wp-pointer' );
wp_enqueue_style( 'wp-pointer' );

?>
<div class="wrap">
  <h1><?php echo __( 'User Registration CF7 Settings', 'zeal-user-reg-cf7' );?></h1>
  <div class="notice notice-error is-dismissible" id="zeal-user-reg-cf7" style="display:none;">
    <p><?php echo __( 'Please fill all mandatory fields.', 'zeal-user-reg-cf7' ); ?></p>
      <button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php echo __( 'Please fill all mandatory fields..', 'zeal-user-reg-cf7' ); ?></span></button>
  </div>
  <form id="setting-form" method="post">
    <?php if(isset($_REQUEST['setting_zurcf7_submit']) ){?>
    <div class="notice notice-success is-dismissible">
        <p><?php echo __( 'Settings saved successfully !', 'zeal-user-reg-cf7' );?></p>
    </div>
    <?php }?>
    <?php if(isset($_REQUEST['setting_reset']) ){?>
    <div class="notice notice-success is-dismissible">
        <p><?php echo __( 'Settings are reset successfully !', 'zeal-user-reg-cf7' );?></p>
    </div>
    <?php }?>

    <?php 
		//Form settings file
		require_once( ZURCF7_DIR .  '/inc/admin/template/' . ZURCF7_PREFIX . '.form.settings.php' );

    //ACF Field Mapping
    require_once( ZURCF7_DIR .  '/inc/admin/template/' . ZURCF7_PREFIX . '.fieldmapping.settings.php' );

    //social Fb registration
    require_once( ZURCF7_DIR .  '/inc/admin/template/' . ZURCF7_PREFIX . '.fb.settings.php' ); 

    ?>


    <p class="submit">
    <input type="hidden" id="_wpnonce" name="_zurcf7_settings_nonce" value="<?php echo wp_create_nonce( 'zurcf7_settings_nonce' );?>">
      <input type="submit" name="setting_zurcf7_submit" id="setting_zurcf7_submit" class="button button-primary" value="<?php echo __( 'Save Settings', 'zeal-user-reg-cf7' );?>">
      <input type="submit" name="setting_reset" id="setting_reset" class="button button-secondary" value="<?php echo __( 'Reset Settings', 'zeal-user-reg-cf7' );?>">
    </p>
  </form>
</div>