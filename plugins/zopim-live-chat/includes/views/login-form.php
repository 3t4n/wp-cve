<div id="icon-options-general" class="icon32"><br/></div>
<h2><?php echo $messages[ 'setup-your-account' ]; ?></h2>
<?php
$noticesArray = $notices->get_notices();
$hasErrors = ( isset( $noticesArray[ 'before_login_error' ] ) && ( $noticesCount = count( $noticesArray[ 'before_login_error' ] ) ) > 0 ) ? $noticesCount : 0;
if ( !$hasErrors ) {
  echo $messages[ 'congratulations' ];
}
$notices->do_notices( 'before_login' );
?>
<div id="existingform">
  <div class="metabox-holder">
    <div class="postbox">
      <h3 class="hndle"><span><?php echo $messages[ 'link-up-title' ]; ?></span></h3>

      <div class="zopim-login-form">
        <?php $notices->do_notices( 'login_form' ); ?>
        <form method="post" action="admin.php?page=zopim_account_config">
          <input type="hidden" name="action" value="login">
          <table class="form-table">

            <?php wp_nonce_field('zopim_login') ?>
            <tr valign="top">
              <th scope="row"><?php echo $messages[ 'username' ]; ?></th>
              <td><input type="text" name="zopimUsername"
                         value="<?php echo get_option( Zopim_Options::ZOPIM_OPTION_USERNAME ); ?>"/>
              </td>
            </tr>

            <tr valign="top">
              <th scope="row"><?php echo $messages[ 'password' ]; ?></th>
              <td><input type="password" name="zopimPassword" value=""/></td>
            </tr>
            <tr valign="top">
              <th scope="row"><?php echo $messages[ 'subdomain' ]?></th>
              <td><input type="text" name="<?php echo Zopim_Options::ZENDESK_OPTION_SUBDOMAIN ?>"/></td>
            </tr>
          </table>
          <br/>
          <?php echo $messages[ 'widget-display-notice' ]; ?>
          <br/>

          <p class="submit">
            <input id="linkup" type="submit" onclick="animateButton()" class="button-primary"
            value="<?php echo $messages[ 'link-up-button' ] ?>"/>
            &nbsp;<?php _e( 'Don\'t have a Zendesk Chat account?', 'zopim-live-chat' ); ?> <a
            href="<?php echo ZOPIM_SIGNUP_REDIRECT_URL; ?>" target="_blank"
            data-popup="true"><?php echo $messages[ 'sign-up-link' ]; ?></a>.
          </p>

        </form>

      </div>
    </div>
  </div>
</div>
