<?php
declare( strict_types=1 );

namespace Pontet_Labs\Hide_Admin_Notices;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://pontetlabs.com
 * @since      1.0.0
 *
 * @package    Hide_Admin_Notices
 * @subpackage Hide_Admin_Notices/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Hide_Admin_Notices
 * @subpackage Hide_Admin_Notices/admin
 * @author     PontetLabs <hi@pontetlabs.com>
 */
class Admin {

  public const DONATE_LINK = 'https://www.buymeacoffee.com/pontetlabs';

  public const RATE_LINK = 'https://wordpress.org/support/plugin/hide-admin-notices/reviews/#new-post';

  protected Options $options;

  public function __construct( Options $options ) {
    $this->options = $options;
  }

  public function init(): void {
    add_action( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 20, 2 );
    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), PHP_INT_MIN );
    add_action( 'admin_notices', array( $this, 'admin_notices' ), 1 );
    add_action( 'admin_footer', array( $this, 'admin_footer' ), PHP_INT_MIN );
    add_filter( 'plugin_action_links_' . HIDE_ADMIN_NOTICES_BASENAME, array( $this, 'plugin_action_links' ) );
  }

  /**
   * Register the CSS and JavaScript.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts(): void {
    $minified = '.min';
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) {
      $minified = '';
    }
    $script_vars   = [
      'updateNagSelector' => 'div.update-nag'
    ];
    $style_handler = $script_handler = 'hide-admin-notices';
    wp_enqueue_style( $style_handler,
      HIDE_ADMIN_NOTICES_URL . 'assets/css/hide-admin-notices' . $minified . '.css',
      [], HIDE_ADMIN_NOTICES_VERSION );
    wp_register_script( $script_handler,
      HIDE_ADMIN_NOTICES_URL . 'assets/js/' . $script_handler . $minified . '.js',
      array( 'jquery' ),
      HIDE_ADMIN_NOTICES_VERSION, true );
    wp_localize_script( $script_handler, 'hideAdminNoticesVars', $script_vars );
    wp_enqueue_script( $script_handler );
  }

  /**
   * Modify plugin row meta.
   *
   * @since    1.0.0
   */
  public function plugin_row_meta( $links, $file ): array {
    if ( HIDE_ADMIN_NOTICES_BASENAME === $file ) {
      $row_meta = array(
        'donate' => '<a target="_blank" href="' . esc_url( self::DONATE_LINK ) .
                    '" aria-label="' . esc_attr__( 'Donate a $1', 'hide-admin-notices' ) .
                    '">' . esc_html__( 'Donate a $1', 'hide-admin-notices' ) . '</a>',
      );

      return array_merge( $links, $row_meta );
    }

    return (array) $links;
  }

  /**
   * Modify plugin actions.
   *
   * @param $links
   *
   * @return array
   * @since    1.2.0
   *
   */
  public function plugin_action_links( $links ): array {
    $rate_link = array(
      'rate' => '<a href="' . esc_url( $this->options->get_options_page_url() ) .
                '" aria-label="' . esc_attr__( 'Like it?', 'hide-admin-notices' ) .
                '">' . esc_html__( 'Report an issue', 'hide-admin-notices' ) . '</a>',
    );

    return array_merge( $links, $rate_link );
  }

  /**
   * Attach the active class to the page body.
   *
   * @return string
   */
  public function admin_body_class( $admin_body_class ) {
    $classes          = explode( ' ', trim( $admin_body_class ) );
    $classes[]        = 'hidden-admin-notices-active';
    $admin_body_class = implode( ' ', array_unique( $classes ) );

    return " $admin_body_class ";
  }

  /**
   * Plugin placeholder elements.
   *
   * @return void
   */
  public function admin_notices() {
    ?>
    <div id="hidden-admin-notices-panel-wrap">
      <div id="hidden-admin-notices-panel" class="hidden" tabindex="-1"
           aria-label="<?php echo esc_attr__( 'Notifications Tab', 'hide-admin-notices' ); ?> "></div>
    </div>
    <div id="hidden-admin-notices-link-wrap" class="hide-if-no-js">
      <button type="button" id="hidden-admin-notices-link"
              class="button" aria-controls="hidden-admin-notices-panel" aria-expanded="false">
        <span class="hidden-admin-notices-link-mobile-icon" aria-hidden="true"></span>
        <span class="hidden-admin-notices-link-text">
          <span class="hidden-admin-notices-link-text-show"
                aria-label="<?php echo esc_html__( 'Show Notices', 'hide-admin-notices' ); ?>"><?php echo esc_html__( 'Show Notices', 'hide-admin-notices' ); ?></span>
          <span class="hidden-admin-notices-link-text-hide"
                aria-label="<?php echo esc_html__( 'Hide Notices', 'hide-admin-notices' ); ?>"><?php echo esc_html__( 'Hide Notices', 'hide-admin-notices' ); ?></span>
        </span>
        <span class="hidden-admin-notices-link-dot" aria-hidden="true"></span>
      </button>
    </div>
    <?php
  }

  /**
   * Dynamic injection of CSS.
   *
   * @return void
   */
  public function admin_footer() {
    $style = array();
    // Do not hide:
    // 1) WP important notices e.g. "Plugin activated".
    // 2) Notices that common.js has not moved i.e. `.inline, .below-h2`.
    $common_nots = ':not(#message):not(.inline):not(.below-h2)';
    // Avoid forcing the display of hidden notices.
    $panel_nots = "$common_nots:not(.hidden)";
    // Errors.
    $style[] = "div.error$common_nots { display:none; }";
    $style[] = "#hidden-admin-notices-panel div.error$panel_nots { display:block; }";
    // Updated notices.
    $style[] = "div.updated$common_nots { display:none; }";
    $style[] = "#hidden-admin-notices-panel div.updated$panel_nots { display:block; }";
    // Standard notices.
    $style[] = "div.notice$common_nots { display:none; }";
    $style[] = "#hidden-admin-notices-panel div.notice$panel_nots { display:block; }";
    // WP update nag.
    $style[] = "div.update-nag { display:none; }";
    $style[] = "#hidden-admin-notices-panel div.update-nag { display:block; }";
    echo '<style>' . implode( "\n", $style ) . '</style>';
  }
}