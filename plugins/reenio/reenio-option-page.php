<?php
// option page for reenio plugin
if ( !class_exists( 'Reenio' ) ) {

  class Reenio {
    private $reenio_options;

    public function __construct() {
      add_action( 'admin_menu', array( $this, 'reenio_add_plugin_page' ) );
      add_action( 'admin_init', array( $this, 'reenio_page_init' ) );
    }

    public function reenio_add_plugin_page() {
      add_menu_page(
        'reenio', // page_title
        'reenio', // menu_title
        'manage_options', // capability
        'reenio', // menu_slug
        array( $this, 'reenio_create_admin_page' ), // function
        'dashicons-tickets', // icon_url
        100 // position
      );
    }

    public function reenio_create_admin_page() {
      $this->reenio_options = get_option( 'reenio_option_name' ); ?>

      <div class="wrap">

        <h2><?php esc_html_e( 'Reservation system', 'reenio' ); ?> reenio</h2>

        <p>
          <strong><?php esc_html_e( 'Add your reenio identification key,', 'reenio' ); ?></strong> <?php esc_html_e( 'push Save button and', 'reenio' ); ?> <strong><?php esc_html_e( 'copy shortcode into page', 'reenio' ); ?></strong>.<br>
          <?php esc_html_e( 'You can find reenio identification key', 'reenio' ); ?>
          <a href="https://reenio.cz/cs/admin/#/settings/website-integration" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'at this link', 'reenio' ); ?></a>.
        </p>

        <?php settings_errors(); ?>

        <form method="post" action="options.php">
          <?php
            settings_fields( 'reenio_option_group' );
            do_settings_sections( 'reenio-admin' );
            submit_button();
          ?>
        </form>

        <?php
          // options
          $reenio_options = get_option( 'reenio_option_name' ); // Array of All Options
          $reenio_options_id = $reenio_options['reenio_id_0']; // Reenio ID
          $reenio_options_text = $reenio_options['text_tlacitka_1']; // Button text
          $reenio_options_lang = $reenio_options['language_2']; // Language
          $reenio_options_type = $reenio_options['type_3']; // Type

          // shortcode - language
          if ( $reenio_options_lang!='cs' ) { $reenio_shortcode_lang = ' lang="'.$reenio_options_lang.'"'; } // cs = default
          else { $reenio_shortcode_lang = ''; }

          // shortcode - type
          if ( $reenio_options_type!='iframe' ) { $reenio_shortcode_type = ' type="'.$reenio_options_type.'"'; } // iframe = default
          else { $reenio_shortcode_type = ''; }

          // shortcode - button text
          if ( $reenio_options_type=='button' && $reenio_options_text!='' ) { $reenio_shortcode_text = ' name="'.$reenio_options_text.'"'; }
          else { $reenio_shortcode_text = ''; }

          // shorcode - composition
          $reenio_shortcode = '[reenio id="'.$reenio_options_id.'"'.$reenio_shortcode_lang.$reenio_shortcode_type.$reenio_shortcode_text.']';
        ?>

        <?php if ( $reenio_options_id!="" ): ?>

          <h3><?php esc_html_e( 'Shortcode for reservation form', 'reenio' ); ?>:</h3>
          <p style="border: 1px dashed black;padding: 15px 20px;font-size: 18px;background-color: white;">
            <?php echo $reenio_shortcode; ?>
          </p>

        <?php endif; ?>

      </div>
    <?php }

    public function reenio_page_init() {
      register_setting(
        'reenio_option_group', // option_group
        'reenio_option_name', // option_name
        array( $this, 'reenio_sanitize' ) // sanitize_callback
      );

      add_settings_section(
        'reenio_setting_section', // id
        '', // title
        array( $this, 'reenio_section_info' ), // callback
        'reenio-admin' // page
      );

      // reenio ID
      add_settings_field(
        'reenio_id_0', // id
        __( 'Insert key', 'reenio' ), // title
        array( $this, 'reenio_id_0_callback' ), // callback
        'reenio-admin', // page
        'reenio_setting_section' // section
      );

      // button text
      add_settings_field(
        'text_tlacitka_1', // id
        __( 'Button text (optional)', 'reenio' ), // title
        array( $this, 'text_tlacitka_1_callback' ), // callback
        'reenio-admin', // page
        'reenio_setting_section' // section
      );

      // language selector
      add_settings_field(
        'language_2', // id
        __( 'Language', 'reenio' ), // title
        array( $this, 'language_2_callback' ), // callback
        'reenio-admin', // page
        'reenio_setting_section' // section
      );

      // shortcode type
      add_settings_field(
        'type_3', // id
        __( 'Insert as', 'reenio' ), // title
        array( $this, 'type_3_callback' ), // callback
        'reenio-admin', // page
        'reenio_setting_section' // section
      );
    }

    public function reenio_sanitize($input) {
      $sanitary_values = array();
      if ( isset( $input['reenio_id_0'] ) ) {
        $sanitary_values['reenio_id_0'] = sanitize_text_field( $input['reenio_id_0'] );
      }

      if ( isset( $input['text_tlacitka_1'] ) ) {
        $sanitary_values['text_tlacitka_1'] = sanitize_text_field( $input['text_tlacitka_1'] );
      }

      if ( isset( $input['language_2'] ) ) {
        $sanitary_values['language_2'] = sanitize_text_field( $input['language_2'] );
      }

      if ( isset( $input['type_3'] ) ) {
        $sanitary_values['type_3'] = sanitize_text_field( $input['type_3'] );
      }

      return $sanitary_values;
    }

    public function reenio_section_info() {

    }

    public function reenio_id_0_callback() {
      printf(
        '<input class="regular-text" type="text" name="reenio_option_name[reenio_id_0]" id="reenio_id_0" value="%s">',
        isset( $this->reenio_options['reenio_id_0'] ) ? esc_attr( $this->reenio_options['reenio_id_0']) : ''
      );
    }

    public function text_tlacitka_1_callback() {
      printf(
        '<input class="regular-text" type="text" name="reenio_option_name[text_tlacitka_1]" id="text_tlacitka_1" value="%s">',
        isset( $this->reenio_options['text_tlacitka_1'] ) ? esc_attr( $this->reenio_options['text_tlacitka_1']) : ''
      );
    }

    public function language_2_callback() {
    ?> <select name="reenio_option_name[language_2]" id="language_2">
      <?php $selected = (isset( $this->reenio_options['language_2'] ) && $this->reenio_options['language_2'] === 'cs') ? 'selected' : '' ; ?>
      <option value="cs" <?php echo $selected; ?>>Česky</option>
      <?php $selected = (isset( $this->reenio_options['language_2'] ) && $this->reenio_options['language_2'] === 'sk') ? 'selected' : '' ; ?>
      <option value="sk" <?php echo $selected; ?>>Slovenčina</option>
      <?php $selected = (isset( $this->reenio_options['language_2'] ) && $this->reenio_options['language_2'] === 'en') ? 'selected' : '' ; ?>
      <option value="en" <?php echo $selected; ?>>English</option>
      <?php $selected = (isset( $this->reenio_options['language_2'] ) && $this->reenio_options['language_2'] === 'pl') ? 'selected' : '' ; ?>
      <option value="pl" <?php echo $selected; ?>>Polski</option>
      <?php $selected = (isset( $this->reenio_options['language_2'] ) && $this->reenio_options['language_2'] === 'de') ? 'selected' : '' ; ?>
      <option value="de" <?php echo $selected; ?>>Deutsch</option>
    </select> <?php
    }

    public function type_3_callback() {
    ?> <select name="reenio_option_name[type_3]" id="type_3">
      <?php $selected = (isset( $this->reenio_options['type_3'] ) && $this->reenio_options['type_3'] === 'iframe') ? 'selected' : '' ; ?>
      <option value="iframe" <?php echo $selected; ?>><?php esc_html_e( 'Full reservation', 'reenio' ); ?></option>
      <?php $selected = (isset( $this->reenio_options['type_3'] ) && $this->reenio_options['type_3'] === 'button') ? 'selected' : '' ; ?>
      <option value="button" <?php echo $selected; ?>><?php esc_html_e( 'Button only', 'reenio' ); ?></option>
    </select> <?php
    }

  }

  if ( is_admin() ) {
    $reenio = new Reenio();
  }

}

?>
