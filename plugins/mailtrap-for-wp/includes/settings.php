<div class="wrap">
  <?php include 'page-header.php'; ?>
  
  <form method="post" action="options.php">
    <?php settings_fields( 'mailtrap-settings' ); ?>
    <?php do_settings_sections( 'mailtrap-settings' ); ?>
    
    <table class="form-table">
      <tr>
        <th scope="row"><?php echo __( 'Enabled', 'mailtrap-for-wp' ) ?></th>
        <td><input type="checkbox" name="mailtrap_enabled" value="1" <?php echo get_option('mailtrap_enabled') === '1' ? 'checked' : '' ?> /></td>
      </tr>
      <tr>
        <th scope="row"><?php echo __( 'Username', 'mailtrap-for-wp' ) ?></th>
        <td><input type="text" name="mailtrap_username" value="<?php echo esc_attr( get_option('mailtrap_username') ); ?>" /></td>
      </tr>
      <tr>
        <th scope="row"><?php echo __( 'Password', 'mailtrap-for-wp' ) ?></th>
        <td><input type="text" name="mailtrap_password" value="<?php echo esc_attr( get_option('mailtrap_password') ); ?>" /></td>
      </tr>
      <tr>
        <th scope="row"><?php echo __( 'Port', 'mailtrap-for-wp' ) ?></th>
        <td>
          <select name="mailtrap_port">
            <option value=""><?php echo __( 'Select a port', 'mailtrap-for-wp' ) ?></option>
            <?php foreach(array(25, 465, 2525) as $port): ?>
            <option value="<?php echo $port ?>" <?php echo get_option('mailtrap_port') == $port ? 'selected' : '' ?>><?php echo $port ?></option>
            <?php endforeach; ?>
          </select>
        </td>
      </tr>
      <tr>
        <th scope="row"><?php echo __( 'Use TLS', 'mailtrap-for-wp' ) ?></th>
        <td><input type="checkbox" name="mailtrap_secure" value="tls" <?php echo get_option('mailtrap_secure') === 'tls' ? 'checked' : '' ?> /></td>
      </tr>
      <tr>
        <td colspan="2"><hr></td>
      </tr>
      <tr>
        <th scope="row"><?php echo __( 'API Token', 'mailtrap-for-wp' ) ?></th>
        <td>
          <input type="text" name="mailtrap_api_token" value="<?php echo esc_attr( get_option('mailtrap_api_token') ); ?>" /><br>
          <small><a href="https://mailtrap.io/public-api" target="blank">https://mailtrap.io/public-api</a></small>
        </td>
      </tr>
    </table>
    
    <?php submit_button(); ?>
    
  </form>
</div>
