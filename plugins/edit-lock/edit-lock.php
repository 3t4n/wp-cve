<?php
/*
  Plugin Name: Edit Lock
  Description: Disable page editing to protect selected pages which are crucial for your website, from accidental or unwanted changes that might break your site. By locking pages and posts, they cannot be edited or deleted by users. Exception can be added for admin users to modify pages irregardless of locking status.
  Author: Aralus Digital
  Version: 1.0.3
  Author URI: https://aralus.digital/
  Text Domain: edit-lock
  Domain Path: /languages
*/

class EditLock {
  /*================================================================/
    CONSTANTS
  /----------------------------------------------------------------*/

  const TEXT_DOMAIN = 'edit-lock';
  const OPTION_PREFIX = 'edit_lock_';
  const CSS_PREFIX = 'edit-lock--';
  const DEFAULTS = array(
    'locked_posts' => '',
    'lock_type' => 'page',
    'lock_toggle' => 1,
    'allow_admin' => 0,
  );

  /*================================================================/
    STATIC PROPERTIES
  /----------------------------------------------------------------*/

  // Cached items
  protected static $locked_posts = null;

  /*================================================================/
    STATIC METHODS
  /----------------------------------------------------------------*/

  /**
   * Get option with defaults
   * 
   * @param string $option Option name
   * @return mixed Option value, or default value if unset.
   */
  public static function get_option($option) {
    return get_option(self::OPTION_PREFIX.$option, self::DEFAULTS[$option]);
  }

  /**
   * Set option
   * 
   * @param string $option Option name
   * @param mixed $value Option value
   */
  public static function set_option($option, $value) {
    if (update_option(self::OPTION_PREFIX.$option, $value) === false) {
      return add_option(self::OPTION_PREFIX.$option, $value);
    }
    return true;
  }

  /**
   * Retrieve list of locked posts
   * 
   * @return array List of locked posts as ID => ID pairs.
   */
  protected static function get_locked_posts() {
    if (self::$locked_posts == null) {
      $locked_posts_raw = self::get_option('locked_posts');
      if ($locked_posts_raw == '') {
        return false;
      }

      $locked_posts = array();
      foreach (explode("\n", $locked_posts_raw) as $item) {
        $locked_posts[trim($item)] = trim($item);
      }
      self::$locked_posts = $locked_posts;
    }
    return self::$locked_posts;
  }

  /**
   * Check if post is locked
   * 
   * @param int $post_id Post ID
   * @return bool TRUE if post is locked, FALSE otherwise.
   */
  public static function is_post_locked($post_id) {
    if (empty($post_id)) {
      return false;
    }

    $locked_posts = self::get_locked_posts();

    // $post_uri = get_page_uri($post_id);
    // if (isset($locked_posts[$post_uri]) OR isset($locked_posts["ID:{$post_id}"])) {
    if (isset($locked_posts[$post_id])) {
      return true;
    }
    else {
      return false;
    }
  }

  /*================================================================/
    METHODS
  /----------------------------------------------------------------*/

  /**
   * Plugin constructor
   */
  public function __construct() {
    add_action('plugins_loaded', array($this, 'load_plugin'));
    if (is_admin()) {
      add_action('admin_init', array($this, 'lock_editor_page'));
      add_action('admin_footer-post.php', array($this, 'lock_editor_warn'));
      add_filter('post_row_actions', array($this, 'filter_locked_post_actions'), 10, 2);
      add_filter('page_row_actions', array($this, 'filter_locked_post_actions'), 10, 2);
      add_filter('media_row_actions', array($this, 'filter_locked_post_actions'), 10, 2);
      add_filter('map_meta_cap', array($this, 'lock_capabilities'), 10, 4);

      add_action('admin_menu', array($this, 'register_settings_page'));
      add_action('admin_init', array($this, 'register_settings'));
      add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_plugin_links'));
    }
  }

  /**
   * Load plugin language file
   * 
   * @hook plugins_loaded
   */
  public function load_plugin() {
    load_plugin_textdomain(self::TEXT_DOMAIN, false, basename(dirname(__FILE__)).'/languages/');
  }

  /**
   * Lock editor for certain pages
   * Checks if the current page is an editor page for a locked post.
   * 
   * @hook admin_init
   */
  public function lock_editor_page() {
    global $pagenow;

    // Only applies to editor page
    if ($pagenow != 'post.php') {
      return;
    }

    // Only applies to 'page' lock mode
    if (self::get_option('lock_type') != 'page') {
      return;
    }

    // Get post_id and validate
    $post_id = !empty($_REQUEST['post']) ? intval($_REQUEST['post']) : 
      (!empty($_REQUEST['post']) ? intval($_REQUEST['post']) : 0);
    if ($post_id == 0) {
      return;
    }

    // Check if post is locked
    $post_locked = $this->is_post_locked($post_id);
    if ($post_locked) {
      if (self::get_option('allow_admin') == '1' AND current_user_can('manage_options')) {
        return;
      }
      wp_die(sprintf('<p><b>%s</b></p>', __('Protected page', self::TEXT_DOMAIN)) . $this->print_lock_message(false));
    }
  }

  /**
   * Soft-lock editor for certain pages
   * Checks if the current page is an editor page for a locked post.
   * 
   * @hook admin_footer-post.php
   */
  public function lock_editor_warn() {
    // Only applies when referrer differs than the current page
    // i.e. It's not reloaded after submitting post (classic editor)
    $message = !empty($_REQUEST['message']) ? intval($_REQUEST['message']) : null;
    if ($message == 1) {
      return;
    }

    // Get post id and validate
    $post_id = !empty($_REQUEST['post']) ? intval($_REQUEST['post']) : 
      (!empty($_REQUEST['post_ID']) ? intval($_REQUEST['post_ID']) : 0);
    if ($post_id == 0) {
      return;
    }

    // Check if post is locked
    $post_locked = $this->is_post_locked($post_id);
    if (!$post_locked) {
      return;
    }
    
    $post_type = get_post_type($post_id);
    $return_url = wp_get_referer();
    if ($return_url === false) {
      $return_url = get_admin_url();
    }
    ?>
    <div id="edit-lock--warning" class="notification-dialog-wrap hide-if-no-js">
      <div class="notification-dialog-background"></div>
      <div class="notification-dialog">
        <div class="edit-lock--warning-content">
          <h1><?php _e('Heads up!'); ?></h1>
          <p><?php _e('This page may contain program codes and contents which are crucial for the website. Editing is discouraged as to prevent accidental or unwanted changes which might break the site.', self::TEXT_DOMAIN); ?></p>
          <?php if (post_type_supports($post_type, 'revisions')) : ?>
            <p><?php _e('Apparently, revisions are stored whenever changes are made to this page. This function could be used to restore your page content if something goes wrong.', self::TEXT_DOMAIN); ?></p>
          <?php endif; ?>
          <p>
            <a class="button" href="<?php echo esc_url( $return_url ); ?>"><?php _e( 'Go back' ); ?></a>
            <button type="button" class="button edit-lock--warning-dismiss-button button-primary"><?php _e( 'I understand' ); ?></button>
          </p>
        </div>
      </div>
    </div>
    <style type="text/css">
      #edit-lock--warning .edit-lock--warning-content {
        margin: 25px;
      }

      @media only screen and (max-height: 480px), screen and (max-width: 450px) {
        #edit-lock--warning .notification-dialog {
          width: 100%;
          height: 100%;
          max-height: 100%;
          position: fixed;
          top: 0;
          margin: 0;
          left: 0;
        }
      }
    </style>
    <script type="text/javascript">
      document.querySelector('#edit-lock--warning .edit-lock--warning-dismiss-button').addEventListener('click', function() {
        document.querySelector('#edit-lock--warning').classList.add('hidden');
      });
    </script>
    <?php
  }

  /**
   * Remove capabilities for locked items
   * 
   * @hook map_meta_cap
   */
  public function lock_capabilities($caps, $cap, $user_id, $args) {
    // Only apply to these capabilities
    if (empty($args[0])) {
      return $caps;
    }
    switch ($cap) {
      default:
        return $caps;
      case 'delete_post':
      case 'edit_post':
        break;
    }

    if ($this->is_post_locked($args[0])) {
      if ($cap == 'delete_post') {
        $caps[] = 'do_not_allow';
        return $caps;
      }
      if ($cap == 'edit_post') {
        if (self::get_option('lock_type') != 'page') {
          return $caps;
        }
        if (self::get_option('allow_admin') == '1' AND user_can($user_id, 'manage_options')) {
          return $caps;
        }
        $caps[] = 'do_not_allow';
        return $caps;
      }
    }
    
    return $caps;
  }

  /**
   * Print lock message
   * 
   * @param bool $output Whether to print out to screen
   * @return string Message content
   */
  public function print_lock_message($output = true) {
    $out = sprintf("<p>%s</p>", __('This page may contain program codes and contents which are crucial for the website. Editing is disabled to prevent accidental or unwanted changes to this page which might break the site.', self::TEXT_DOMAIN));
    if ($output) {
      echo $out;
    }
    return $out;
  }

  /**
   * Filter locked post actions
   * 
   * @hook post_row_actions, page_row_actions, media_row_actions
   */
  public function filter_locked_post_actions($actions, $post) {
    $lock_toggle = self::get_option('lock_toggle');
    if (!current_user_can('manage_options')) {
      $lock_toggle = false;
    }

    if (self::is_post_locked($post->ID)) {
      if ($lock_toggle == '1') {
        $actions[] = sprintf('<a href="%s" title="%s"><span class="dashicons dashicons-lock"></span></a>', 
          admin_url('options-general.php?page=' . plugin_basename(__FILE__) . '&action=toggle_post_lock&id=' . $post->ID),
          __('Unlock content', self::TEXT_DOMAIN)
        );
      } 
      else {
        $actions[] = sprintf('<span title="%s" class="dashicons dashicons-lock"></span>', 
          __('Protected content', self::TEXT_DOMAIN)
        );
      }

      unset($actions['trash']);
      unset($actions['inline hide-if-no-js']);
    }
    else {
      if ($lock_toggle) {
        $actions[] = sprintf('<a href="%s" title="%s"><span class="dashicons dashicons-unlock"></span></a>', 
          admin_url('options-general.php?page=' . plugin_basename(__FILE__) . '&action=toggle_post_lock&id=' . $post->ID),
          __('Lock content', self::TEXT_DOMAIN)
        );
      }
    }
    return $actions;
  }

  /**
   * Add action links on the plugin entries
   * 
   * @hook plugin_action_links_{PLUGIN_NAME}
   */
  public function add_plugin_links($links) {
    $links[] = sprintf('<a href="%s">%s</a>', admin_url('options-general.php?page=' . plugin_basename(__FILE__)), __('Settings'));
    return $links;
  }

  /**
   * Register settings page and add menu entry
   * 
   * @hook admin_menu
   */
  public function register_settings_page() {
    $hook = add_options_page(
      __('Edit Lock Settings', self::TEXT_DOMAIN),
      __('Edit Lock', self::TEXT_DOMAIN),
      'manage_options',
      plugin_basename(__FILE__),
      array($this, 'view_settings')
    );
    add_action("load-{$hook}", array($this, 'controller_settings'));
  }

  /**
   * Register all options for use on the settings page
   * 
   * @hook admin_init
   */
  public function register_settings() {
    register_setting(plugin_basename(__FILE__), self::OPTION_PREFIX.'locked_posts', array(
      'type' => 'string',
      'sanitize_callback' => function($val) {
        $val = preg_replace('/[^0-9\n]/', '', $val);
        $val = preg_replace('/\n{2,}/', "\n", $val);
        return $val;
      }
    ));
    register_setting(plugin_basename(__FILE__), self::OPTION_PREFIX.'lock_type', array(
      'type' => 'string',
      'sanitize_callback' => function($val) {
        $valid_values = ['page', 'warn'];
        if (!in_array($val, $valid_values)) {
          return '';
        }
        return $val;
      }
    ));
    register_setting(plugin_basename(__FILE__), self::OPTION_PREFIX.'lock_toggle', array(
      'type' => 'boolean',
    ));
    register_setting(plugin_basename(__FILE__), self::OPTION_PREFIX.'allow_admin', array(
      'type' => 'boolean',
    ));
  }

  /**
   * Page controller for settings page
   * 
   * @hook load-{PLUGIN_NAME}
   */
  public function controller_settings() {
    if (!current_user_can('manage_options')) {
      wp_die('You are not permitted to access this page.');
    }
    $action = !empty($_REQUEST['action']) ? sanitize_text_field($_REQUEST['action']) : null;
    switch ($action) {
      default:
        break;

      case 'toggle_post_lock':
        $this->action_toggle_post_lock();
        $referrer = wp_get_referer();
        if ($referrer !== false) {
          wp_redirect($referrer);
        }
        else {
          wp_redirect(get_admin_url());
        }
        break;
    }
  }

  /**
   * View for settings page
   */
  public function view_settings() {
    ?>
    <div class="wrap">
      <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
      <form action="options.php" method="post">
        <?php settings_fields(plugin_basename(__FILE__)); ?>
        <table class="form-table">
          <tr>
            <th scope="row"><?php _e('Locked posts', self::TEXT_DOMAIN); ?></th>
            <td>
              <fieldset>
                <p>
                  <?php _e('Specify list of post IDs to prevent them being edited. One ID per line.', self::TEXT_DOMAIN); ?>
                </p>
                <textarea name="<?php echo self::OPTION_PREFIX.'locked_posts'; ?>" rows="10" cols="50" class="small-text code"><?php echo esc_html(self::get_option('locked_posts')); ?></textarea>
              </fieldset>
            </td>
          </tr>
          <tr>
            <th scope="row"><?php _e('Lock toggle', self::TEXT_DOMAIN); ?></th>
            <td>
              <fieldset>
                <label><input type="checkbox" name="<?php echo self::OPTION_PREFIX.'lock_toggle'; ?>" <?php echo self::get_option('lock_toggle') == '1' ? 'checked' : ''; ?> value="1" /> <?php _e('Display lock toggle on the list of posts.', self::TEXT_DOMAIN); ?></label><br />
              </fieldset>
            </td>
          </tr>
          <tr>
            <th scope="row"><?php _e('Locking mechanism', self::TEXT_DOMAIN); ?></th>
            <td>
              <fieldset>
                <label><input type="radio" name="<?php echo self::OPTION_PREFIX.'lock_type'; ?>" <?php echo self::get_option('lock_type') == 'page' ? 'checked' : ''; ?> value="page" /> <?php _e('Disable editing or deleting locked posts.', self::TEXT_DOMAIN); ?></label><br />
                <label><input type="radio" name="<?php echo self::OPTION_PREFIX.'lock_type'; ?>" <?php echo self::get_option('lock_type') == 'warn' ? 'checked' : ''; ?> value="warn" /> <?php _e('Warn users when editing locked posts. Quick edit and deletion are disabled.', self::TEXT_DOMAIN); ?></label><br />
              </fieldset>
            </td>
          </tr>
          <tr>
            <th scope="row"><?php _e('Administrator access', self::TEXT_DOMAIN); ?></th>
            <td>
              <fieldset>
                <label><input type="checkbox" name="<?php echo self::OPTION_PREFIX.'allow_admin'; ?>" <?php echo self::get_option('allow_admin') == '1' ? 'checked' : ''; ?> value="1" /> <?php _e('Allow administrators to edit locked posts.', self::TEXT_DOMAIN); ?></label><br />
              </fieldset>
            </td>
          </tr>
        </table>
        <?php submit_button(); ?>
      </form>
    </div>
    <?php
  }

  /**
   * Controller action to toggle post lock/unlock
   */
  protected function action_toggle_post_lock() {
    $locked_posts = self::get_locked_posts();
    $post_id = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
    if ($post_id == 0) {
      return;
    }
    
    if (!isset($locked_posts[$post_id])) {
      $locked_posts[$post_id] = true;
    }
    else {
      unset($locked_posts[$post_id]);
    }
    $locked_posts_raw = implode("\n", array_keys($locked_posts));
    self::set_option('locked_posts', $locked_posts_raw);
  }
}
$EditLock = new EditLock();
