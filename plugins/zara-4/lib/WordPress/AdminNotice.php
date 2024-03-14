<?php
if ( ! class_exists( 'Zara4_WordPress_AdminNotice' ) ) {


  /**
   * Class Zara4_WordPress_AdminNotice
   */
  class Zara4_WordPress_AdminNotice {


    /**
     *
     */
    public static function continue_setup() {

      /** @noinspection PhpUndefinedFunctionInspection */
      $settings_url = admin_url( 'options-general.php' );
      $settings_url_parts = parse_url($settings_url);

      $protocol = $_SERVER["HTTPS"] == "on" ? "https" : "http";
      $current_url = $protocol."://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
      $current_url_parts = parse_url($current_url);

      $on_zara4_settings_page = $current_url_parts["path"] == $settings_url_parts["path"] && $_GET["page"] == "zara-4";


      if( ! $on_zara4_settings_page) {
        ?>
        <div class="notice notice-warning is-dismissible">
          <p>
            <b style="color:#777">Zara 4</b> needs to be setup before you can compress your images.
            <a href="<?php echo admin_url( 'options-general.php?page=zara-4' ); ?>">Continue Setup</a>
          </p>
        </div>
      <?php
      }
    }

  }

}