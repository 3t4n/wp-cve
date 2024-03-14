<?php

class AltrujaAdmin {

  public function __construct() {
    load_plugin_textdomain('altruja', false, 'altruja/languages');
    add_action('admin_menu', array($this, 'menu'));
    add_action( 'admin_init', array($this, 'init'));
  }

  public function init() {
    register_setting( 'altruja', 'altruja', array($this, 'validate'));

    add_settings_section(
      'altruja_section',
      '',
      array($this, 'section'),
      'altruja'
    );

    add_settings_field(
      'email',
      __( 'E-Mail', 'altruja' ),
      array($this, 'emailField'),
      'altruja',
      'altruja_section'
    );

  }

  public function settings() {
    return null;
  }

  public function validate($options) {

    $email = $options['email'];

    $email = strtolower($email);

    if (is_wp_error($response = wp_remote_get('https://www.altruja.de/api/integration/email/'.urlencode($email), array('redirection' => 10)))) {
      return array();
    }

    $message = json_decode($response['body'], true);

    if (!$message || $message['status'] != 'success') {
      return array();
    }

    $options['email'] = $email;

    $options['link'] = $message['link'];
    $options['src'] = $message['src'];
    $options['async'] = $message['async'];

    return $options;
  }

  public function section () {
    echo strtr(__('Please provide the email address you use to login to [my]My Altruja[/my], or [reg]register an account[/reg].', 'altruja'), array(
      '[my]' => '<a href="https://altruja.de/myaltruja">',
      '[/my]' => '</a>',
      '[reg]' => '<a href="https://altruja.de/register">',
      '[/reg]' => '</a>',
    ));
  }

  public function menu() {
    add_options_page( 'altruja', 'Altruja', 'manage_options', 'altruja', array($this, 'options') );
  }

  public function emailField(  ) {

    $options = get_option( 'altruja' );

        ?>
    <input type='text' class="regular-text ltr" name='altruja[email]' value='<?php echo $options['email']; ?>'>

    <?php

  }

  public function options(  ) {

      ?>
    <form action='options.php' method='post'>

      <h2><?php echo __('Settings') ?> › Altruja</h2>

      <?php
      settings_fields( 'altruja' );
      do_settings_sections( 'altruja' );
      submit_button();
      ?>

    </form>
    <?php

  }

}
