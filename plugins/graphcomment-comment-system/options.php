<?php
/**
 * File containing all the logic for handling plugin options
 */

require_once('class/services/gc_date_tools.class.php');
require_once('class/controllers/gc_general_controller.class.php');
require_once('class/controllers/gc_import_controller.class.php');
require_once('class/controllers/gc_synchronisation_controller.class.php');
require_once('class/controllers/gc_select_website_controller.class.php');
require_once('class/services/gc_authorization.class.php');
require_once('class/services/gc_menu.class.php');
require_once('class/services/gc_api_service.class.php');
require_once('class/services/gc_import_service.class.php');
require_once('class/services/gc_thread_pairing.php');
require_once('class/templates/header.template.php');
require_once('class/templates/hello_login.template.php');
require_once('class/templates/settings_page_admin.template.php');

// Register the async action of comment import
GcImportController::registerAsyncAction();
// Create custom plugin settings menu and attach it to 'admin_menu' action and manage menu notification
$gc_menu = new GcMenu('admin_menu');
//Associate the function to this action
add_action('graphcomment_cron_task_sync_comments_action', '_graphcomment_cron_task_sync_comments_function');
//Associate the function to this action
add_action('graphcomment_cron_task_fetch_keys', '_graphcomment_cron_task_fetch_keys_function');
// Associate the AJAX function that gonna update the advancement importation front page
add_action('wp_ajax_graphcomment_import_pending_get_advancement', '_graphcomment_import_pending_get_advancement');
// Associate the AJAX function that gonna delete the notification on the notifications page
add_action('wp_ajax_graphcomment_notif_count', '_graphcomment_notif_count');

/**
 *  Handle the form send by tht plugin option page
 */
function handle_option_form() {
  GcLogger::getLogger()->debug('options.php::handle_option_form() gc_action: '.$_POST['gc_action']);

  $gc_controller = null;

  // Register our settings
  if ($_POST['gc_action'] === 'select_website') {
    $gc_controller = new GcSelectWebsiteController($_POST);
  }
  else if ($_POST['gc_action'] === 'general') {
    if (isset($_POST['gc-change-website']) && $_POST['gc-change-website'] === 'true') {
      update_option('gc_change_website', 'true');
      return wp_redirect(admin_url('admin.php?page=graphcomment-settings'));
    }
    $gc_controller = new GcGeneralController($_POST);
  }
  else if ($_POST['gc_action'] === 'synchronization') {
    $gc_controller = new GcSynchronisationController($_POST);
  }
  else if ($_POST['gc_action'] === 'importation') {
    $gc_controller = new GcImportController($_POST);
  }
  else if ($_POST['gc_action'] === 'gc_debug_change') {
    GcParamsService::getInstance()->graphcommentDebugChange();
    return wp_redirect(admin_url('admin.php?page=graphcomment-settings'));
  }

  if ($gc_controller !== null) {
    $gc_controller->handleOptionForm();
  } else {
    GcLogger::getLogger()->error('No controller found for the gc_option_tab');
    return wp_redirect(admin_url('admin.php?page=graphcomment-settings'));
  }
}

function my_cron_schedules($schedules) {
  if (!isset($schedules['1min'])) {
    $schedules['1min'] = array('interval' => 1 * 60, 'display' => __('Once every 1 minute'));
  }
  if (!isset($schedules['10min'])) {
    $schedules['10min'] = array('interval' => 10 * 60, 'display' => __('Once every 10 minutes'));
  }
  if (!isset($schedules['30min'])) {
    $schedules['30min'] = array('interval' => 30 * 60, 'display' => __('Once every 30 minutes'));
  }
  if (!isset($schedules['1h'])) {
    $schedules['1h'] = array('interval' => 60 * 60, 'display' => __('Once every 1 hour'));
  }
  if (!isset($schedules['12h'])) {
    $schedules['12h'] = array('interval' => 12 * 60 * 60, 'display' => __('Once every 12 hours'));
  }
  return $schedules;
}
add_filter('cron_schedules','my_cron_schedules');

if (isset($_GET['debug']) && $_GET['debug'] === 'gc-sync') {
  do_action('graphcomment_cron_task_sync_comments_action');
}

/**
 * _graphcomment_cron_task_sync_comments_function
 *
 * The CRON function is called 24 times a day
 * Sync the comment from GraphComment to WordPress
 */
function _graphcomment_cron_task_sync_comments_function() {

  // Then, reprogram the CRON
  if (!wp_next_scheduled( 'graphcomment_cron_task_sync_comments_action' ) ) {
    wp_schedule_event(time(), get_option('gc_sync_interval'), 'graphcomment_cron_task_sync_comments_action');
  }

  GcLogger::getLogger()->debug('Gc sync: start');
  update_option('gc_sync_last_success', date("Y-m-d H:i:s", time() - date("Z")));

  // Take these usefull options
  GcParamsService::getInstance()->fetchApiKeys(); // always refresh keys
  $gc_public_key =  GcParamsService::getInstance()->graphcommentGetWebsite();
  $gc_website_id = get_option('gc_website_id');

  $res = GcApiService::getNewComments($gc_website_id);
  if ($res['error'] !== false) {
    GcLogger::getLogger()->error('Gc sync: (error) ' . json_encode($res));
    update_option('gc-sync-error', json_encode(array('content' => __('Error Getting Sync', 'graphcomment-comment-system'))));
    return;
  }

  $comments = json_decode(json_encode($res['comments']), true); // transform stdObject to array
  $commentIds = array_map(function($c) { return $c['_id']; }, $comments);
  $pairingDao = new GcCommentPairingDao();
  $pairingDao->findIds(array());

  GcLogger::getLogger()->debug('Gc sync: ' . count($comments) . ' new comments');

  function printComment($c, $title) {
    echo '<pre>';
    echo $title;
    echo $c;
    echo '</pre>';
  }

  foreach ($comments as $c) {

    $comment = new GcCommentBuilder($c);
    $post_id = GcThreadPairingService::getPostFromThread($c['thread'], $gc_public_key);

    // should NEVER happen,
    // most likely it's because the thread of the comment is not a WP article
    if ($post_id === 0) {
      GcLogger::getLogger()->debug('Gc sync: skip comment ' . $post_id . ' ' . $c['thread']);
      continue;
    }

    $comment->setPostId($post_id);

    /*
     * The comment is a first level comment
     * The parent_id has to be set to 0 in WordPress
     */
    if ($comment->isFirstLevel()) {
      $comment->setParent('0');
      //printComment($comment, 'first level:');
    }

    /*
     * This comment is an answer
     * We have to find its parents ID
     */
    else {
      $parent_id = $pairingDao->findWordpressId($comment->getParentId());

      //printComment($comment, 'sub level:');

      if ($parent_id === false) {
        GcLogger::getLogger()->error('Sync Error: parent '.$comment->getParentId().' for comment '.$comment->getGraphCommentId().' not sync yet');
        continue;
      } else {
        // Just set the good WordPress parent id
        $comment->setParent($parent_id);
      }
    }

    $comment_wp_id = $pairingDao->findWordpressId($comment->getGraphCommentId());

    if ($comment_wp_id !== false) {
      // We already know this comment, we just have to update it
      $comment->setWordpressId($comment_wp_id);

      //printComment($comment, '  update:');

      if ($comment->updateCommentInDatabase() === false) {
        // Error while getting the comments
        update_option('gc-sync-error', json_encode(array('content' => __('Error Intern MySql', 'graphcomment-comment-system'))));
        return;
      }
    }

    // We don't know yet this comment, we have to insert it
    else {
      //printComment($comment, '  insert:');
      $insert = $comment->insertCommentInDatabase();
      if ($insert === false) {
        // Error while getting the comments
        update_option('gc-sync-error', json_encode(array('content' => __('Error Intern MySql', 'graphcomment-comment-system'))));
        return;
      }

      $insert = $pairingDao->insertKnowPairIds($comment->getWordpressId(), $comment->getGraphCommentId());
      if ($insert === false) {
        // Error while getting the comments
        update_option('gc-sync-error', json_encode(array('content' => __('Error Intern MySql', 'graphcomment-comment-system'))));
        return;
      }
    }
  }

  GcLogger::getLogger()->debug('Gc sync: confirm ' . count($commentIds). ' comments');

  if (count($commentIds) > 0) {
    $res = GcApiService::confirmNewComments($gc_website_id, $commentIds);
    if ($res['error'] !== false) {
      update_option('gc-sync-error', json_encode(array('content' => __('Error Import Comments: ' . $res['error'], 'graphcomment-comment-system'))));
      return;
    }
  }

  // Here we have to set the last_sync_date to NOW and delete the gc-sync-error because no error occurred
  update_option('gc_sync_last_success', date("Y-m-d H:i:s", time() - date("Z")));
  delete_option('gc-sync-error');
}

/**
 * Automatically fetch api keys every hour
 * (in case it has been regenerated on the BO)
 */
if (!wp_next_scheduled( 'graphcomment_cron_task_fetch_keys' ) ) {
  wp_schedule_event(time(), '1h', 'graphcomment_cron_task_fetch_keys');
}
function _graphcomment_cron_task_fetch_keys_function() {
  // Then, reprogram the CRON
  if (!wp_next_scheduled( 'graphcomment_cron_task_fetch_keys' ) ) {
    wp_schedule_event(time(), '1h', 'graphcomment_cron_task_fetch_keys');
  }

  GcLogger::getLogger()->debug('fetch keys');
  GcParamsService::getInstance()->fetchApiKeys(); // always refresh keys
}

function _graphcomment_import_pending_get_advancement() {

  $gc_import_service = new GcImportService();
  echo json_encode($gc_import_service->getAjaxAdvancement());

  wp_die(); // this is required to terminate immediately and return a proper response
}

function _graphcomment_notif_count() {
  $gc_menu = new GcMenu('admin_menu');
  $gc_menu->get_notif();
  echo json_encode(array('count' => get_option('gc_notif_comments', 0)));
  wp_die(); // this is required to terminate immediately and return a proper response
}


/**
 * Print the page that will be used to select the good website
 */
function _graphcomment_settings_page_select_website() {
  require('class/templates/settings_page_select_website.template.php');
}

function _graphcomment_settings_page_create_website() {
  require('class/templates/settings_page_create_website.template.php');
}

/**
 * Returns the plugin options page
 */
function _graphcomment_settings_page() {


  /** Check PHP version */
  if (version_compare(phpversion(), '5.3', '<' )) {
?>
    <br>
    <div class="gc-alert gc-alert-danger">
      Graphcomment requires PHP version 5.3 or higher. Plugin was deactivated.
    </div>
<?php
    die;
  }

  if (!GcParamsService::getInstance()->graphcommentHasWebsites()) {
    if (GcParamsService::getInstance()->graphcommentHasUser()) {
      return _graphcomment_settings_page_create_website();
    }
    // API is down
    else {
      update_option('gc-msg', json_encode(array('type' => 'danger', 'content' => __('API Down Msg', 'graphcomment-comment-system'), 'active_tab' => 'general')));
    }
  }
  else if ((GcParamsService::getInstance()->graphcommentHasWebsites() && !GcParamsService::getInstance()->graphcommentIsWebsiteChoosen()) ||
    get_option('gc_change_website') === 'true'
  ) {
    if (get_option('gc_change_website') !== 'true' && GcParamsService::getInstance()->graphcommentGetNbrWebsites() === 1) {
      GcParamsService::getInstance()->graphcommentSelectOnlyWebsite();
      // Don't return to continue the proccess
    }
    else {
      GcParamsService::getInstance()->graphcommentDeleteWebsite(true, true);
      return _graphcomment_settings_page_select_website();
    }
  }

  $activated = get_option('gc_activated'); // use in children template.
  $gc_sync_error = get_option('gc-sync-error');
  $gc_import_error = get_option('gc-import-error');

  $gc_msg = get_option('gc-msg');
  $gc_msg = ($gc_msg !== false) ? json_decode($gc_msg, true) : false;

  $active_tab = '';
  if (!empty($gc_msg) && isset($gc_msg['active_tab'])) {
    $active_tab = $gc_msg['active_tab'];
  }

  if (GcAuthorization::checkOrPrintOauthIframe()) {
    ?>

    <div class="gc-container">
      <?php header_template(); ?>

      <div class="gc-wrap">

        <form class="gc-alert gc-alert-success gc-alert-website" method="post" action="options.php">
          <input type="hidden" name="gc_action" value="general"/>
          <strong for="graphcomment_website_id_label"><?php _e('Website Id', 'graphcomment-comment-system'); ?>:</strong>
          <i><?php echo GcParamsService::getInstance()->graphcommentGetWebsite(); ?></i>
          <div class="gc-alert-website-small">
            <label><?php _e('Not Right Website Id', 'graphcomment-comment-system'); ?> </label>
            <button type="submit" id="gc-change-website" role="button" class="gc_button_link">
            <?php _e('Change Website Button Label', 'graphcomment-comment-system'); ?></button>
            <a href="<?php echo admin_url('admin.php?page=graphcomment&url=' . urlencode('/settings')); ?>" class="website-settings">
              <img src="data:image/png;base64,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">
              <span><?php _e('Website Settings', 'graphcomment-comment-system'); ?></span>
            </a>
          </div>
        </form>

        <ul class="gc-tabs gc-cloak">

          <li id="graphcomment-options-general-tab"
              class="<?php echo ($active_tab === 'general' ? 'active' : ''); ?>"
          >
            <a href="#general"><?php _e('General Tab', 'graphcomment-comment-system'); ?></a>
          </li>


          <li id="graphcomment-options-synchronization-tab"
              class="<?php echo($active_tab === 'synchronization' ? 'active' : ''); ?>"
          >
            <a href="#synchronization">
              <?php _e('Synchronization Tab', 'graphcomment-comment-system'); ?>
              <?php if ($gc_sync_error !== false) echo '<span class="glyphicon glyphicon-warning-sign graphcomment-tabs-alerts-sync" aria-hidden="true"></span>'; ?>
            </a>
          </li>

          <li id="graphcomment-options-importation-tab"
              class="<?php echo($active_tab === 'importation' ? 'active' : ''); ?>"
          >
            <a href="#importation">
              <?php _e('Importation Tab', 'graphcomment-comment-system'); ?>
              <?php if ($gc_sync_error !== false) echo '<span class="glyphicon glyphicon-warning-sign graphcomment-tabs-alerts-sync" aria-hidden="true"></span>'; ?>
            </a>
          </li>
        </ul>

        <div class="tab-content">

          <!-- General Tab -->
          <div id="graphcomment-general" class="gc-tabs-content <?php echo($active_tab === 'general' ? 'active' : ''); ?>">
            <?php
              delete_option('gc-msg');
              if ($gc_msg !== false) {
                if (array_key_exists('content', $gc_msg) && $gc_msg['content']) {
                  echo '
                    <div class="gc-alert gc-alert-' . $gc_msg['type'] . '">
                      <a class="gc-close" data-dismiss="alert" aria-label="close">&times;</a>
                        ' . $gc_msg['content'] . '
                    </div>';
                }
              }
            ?>

            <?php include 'class/templates/settings_page_general.template.php'; ?>
          </div>
          <!-- End General Tab -->

          <!-- Synchronization Tab -->
          <div id="graphcomment-synchronization"
               class="gc-tabs-content <?php echo($active_tab === 'synchronization' ? 'active' : ''); ?>">
            <?php include 'class/templates/settings_page_synchro.template.php'; ?>
          </div>
          <!-- End Synchronization Tab -->


          <!-- Importation Tab -->
          <div id="graphcomment-importation"
               class="gc-tabs-content <?php echo($active_tab === 'importation' ? 'active' : ''); ?>">
            <?php include 'class/templates/settings_page_import.template.php'; ?>
          </div>
          <!-- End Importation Tab -->

        </div>

        <div class="gc-footer">
          <form class="gc-debug" method="post" action="options.php">
            <div class="gc-debug-enable gc-form-field gc-checkbox">
              <label>
                <input type="hidden" name="gc_action" value="gc_debug_change">
                <input type="checkbox" name="gc_debug_enabled" value="1"
                  <?php echo GcParamsService::getInstance()->graphcommentDebugIsActivated() ? 'checked="checked"' : ''; ?>
                />
                <?php _e('Activate Label', 'graphcomment-comment-system'); ?>&nbsp;
                <a id="show-logs"><?php _e('Show Logs', 'graphcomment-comment-system'); ?></a>
              </label>
            </div>

            <div class="gc-debug-meta">
              <a href="<?php _e('Contact URL', 'graphcomment-comment-system'); ?>" target="_blank"
                ><?php _e('Contact Us', 'graphcomment-comment-system'); ?></a>
              -
              <a href="<?php _e('Bugtrack URL', 'graphcomment-comment-system'); ?>" target="_blank"
                ><?php _e('Report Bug', 'graphcomment-comment-system'); ?></a>
              -
              <span><?php _e('GC Version', 'graphcomment-comment-system'); ?> <?php echo constant('GRAPHCOMMENT_VERSION'); ?></span>
            </div>
          </form>

          <textarea class="gc-logs" onClick="this.select()"><?php echo GcLogger::getLogger()->debugLogs(); ?></textarea>

          <div class="gc-alert gc-alert-info gc-alert-rate-plugin">
              <a href="https://wordpress.org/support/plugin/graphcomment-comment-system/reviews?rate=5#new-post" target="_blank">
                <?php _e('Rate Plugin', 'graphcomment-comment-system'); ?>
              </a>
          </div>
        </div>

      </div>

    </div>

  <?php }
}

// Call the js scripts and css style
add_action('admin_init', '_graphcomment_load_requirement');
// Call register settings function
add_action('admin_init', '_graphcomment_register_settings');

/**
 * _graphcomment_load_requirement
 * Register and enqueue style and script into the options page
 */
function _graphcomment_load_requirement() {
  wp_register_script('bootstrap-js', plugins_url('/theme/vendors/bootstrap/dist/js/bootstrap.min.js', __FILE__), array('jquery', 'jquery-ui-core'));

  wp_enqueue_style('gc-main-style-eveywhere', plugins_url('/theme/css/everywhere.css', __FILE__));
  wp_enqueue_style('jquery-ui-style', plugins_url('/theme/vendors/jquery-ui/themes/smoothness/jquery-ui.min.css', __FILE__));

  // Include bootstrap just for our plugin option page
  if (isset($_GET['page']) && ($_GET['page'] === 'graphcomment' || $_GET['page'] === 'graphcomment-settings')) {
    /*
    wp_enqueue_style('bootstrap-css', plugins_url('/theme/vendors/bootstrap/dist/css/bootstrap.min.css', __FILE__));
    wp_enqueue_style('bootstrap-css-theme', plugins_url('/theme/vendors/bootstrap/dist/css/bootstrap-theme.min.css', __FILE__), array('bootstrap-css'));
    */

    wp_enqueue_style('gc-main-style', plugins_url('/theme/css/main.css', __FILE__));
    wp_enqueue_style('gc-main2-style', plugins_url('/theme/css/styles.css', __FILE__));

    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('bootstrap-js');
    wp_enqueue_script('gc-farbtastic-script', plugins_url('/theme/js/farbtastic.js', __FILE__));
    wp_enqueue_script('gc-options-script', plugins_url('/theme/js/options.js', __FILE__), array('jquery-ui-datepicker', 'bootstrap-js'));
    wp_enqueue_script('gc-general-script', plugins_url('/theme/js/general.js', __FILE__), array('jquery-ui-datepicker', 'bootstrap-js'));
  }
}

/**
 * Registers the plugin options
 */
function _graphcomment_register_settings() {
  if (isset($_POST['gc_action'])) {
    handle_option_form();
  }

  // An OAuth2 redirection is in progress
  else if (isset($_GET['graphcomment_oauth_code']) && $_GET['graphcomment_oauth_code'] === 'true' && $_GET['code']) {
    GcParamsService::getInstance()->graphcommentCreateOauthToken($_GET['code']);

    require('class/templates/login_success.template.php');

    wp_die(); // this is required to terminate immediately and return a proper response
  }
}

