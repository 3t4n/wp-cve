<?php

class FFWDControllerOptions_ffwd {

  public function __construct() {
  }

  public function execute() {
    $task = ((isset($_POST['task'])) ? sanitize_text_field(stripslashes($_POST['task'])) : '');
    $id = ((isset($_POST['current_id'])) ? sanitize_text_field(stripslashes($_POST['current_id'])) : 0);
    if ( $task != '' ) {
      if ( !WDW_FFWD_Library::verify_nonce('options_ffwd') ) {
        die('Sorry, your nonce did not verify.');
      }
    }
    if ( !empty($_GET['ffwd_code']) ) {
      $message_id = WDFacebookFeed::save_pages($_GET['ffwd_code']);
      die('<script>window.location.href="admin.php?page=options_ffwd&success=' . $message_id . '"</script>');
    }
    if ( method_exists($this, $task) ) {
      $this->$task($id);
    }
    else {
      $this->display();
    }
  }

  public function display() {
    require_once WD_FFWD_DIR . "/admin/models/FFWDModelOptions_ffwd.php";
    $model = new FFWDModelOptions_ffwd();

    require_once WD_FFWD_DIR . "/admin/views/FFWDViewOptions_ffwd.php";
    $view = new FFWDViewOptions_ffwd($model);
    $view->display();
  }

  public function reset() {
    require_once WD_FFWD_DIR . "/admin/models/FFWDModelOptions_ffwd.php";
    $model = new FFWDModelOptions_ffwd();


    require_once WD_FFWD_DIR . "/admin/views/FFWDViewOptions_ffwd.php";
    $view = new FFWDViewOptions_ffwd($model);
    echo WDW_FFWD_Library::message('Settings successfully reset', 'updated');
    $view->display(true);
  }

  public function save() {
    $this->save_db();
    $this->display();
  }

  public function save_db() {
    //facebook user_id pahel autoupdate_interval
    global $wpdb;
		$id = 1;
		$autoupdate_interval = (isset($_POST['autoupdate_interval_hour']) && isset($_POST['autoupdate_interval_min']) ? ((int) $_POST['autoupdate_interval_hour'] * 60 + (int) $_POST['autoupdate_interval_min']) : 30);
    /*minimum autoupdate interval is 1 min*/
    $autoupdate_interval = ($autoupdate_interval >= 1 ? $autoupdate_interval : 1 );
		$facebook_app_id = (isset($_POST[WD_FB_PREFIX . '_app_id']) ? sanitize_text_field(stripslashes($_POST[WD_FB_PREFIX . '_app_id'])) : '');
		$facebook_app_secret = (isset($_POST[ WD_FB_PREFIX . '_app_secret']) ? sanitize_text_field(stripslashes($_POST[WD_FB_PREFIX . '_app_secret'])) : '');
    $post_date_format = (isset($_POST[WD_FB_PREFIX . '_post_date_format']) ? sanitize_text_field(stripslashes($_POST[WD_FB_PREFIX . '_post_date_format'])) : 'ago');
    $event_date_format = (isset($_POST[WD_FB_PREFIX . '_event_date_format']) ? sanitize_text_field(stripslashes($_POST[WD_FB_PREFIX . '_event_date_format'])) : 'F j, Y, g:i a');

    $upd_data = array(
      'autoupdate_interval' => $autoupdate_interval,
      'app_id' => $facebook_app_id,
      'app_secret' => $facebook_app_secret,
      'post_date_format' => $post_date_format,
      'event_date_format' => $event_date_format,
    );
    $save = $wpdb->update( $wpdb->prefix . 'wd_fb_option', $upd_data, array('id' => 1) );
    if ( $save !== FALSE ) {
      echo WDW_FFWD_Library::message('Item Succesfully Saved.', 'updated');
			/*
			 * Clear hook for scheduled events,
			 * refresh filter according to new time interval,
			 * then add new schedule with the same hook name
			*/
	    update_option('ffwd_autoupdate_time',$autoupdate_interval*60+current_time('timestamp'));
    }
    else {
      echo WDW_FFWD_Library::message('Error. Please install plugin again.', 'error');
    }
  }

  public function save_app_keys($facebook_app_id, $facebook_app_secret){
    global $wpdb;

    if(empty($facebook_app_id) || empty($facebook_app_secret)){
      return false;
    }

    $save = $wpdb->update($wpdb->prefix . 'wd_fb_option', array(
      'app_id' => $facebook_app_id,
      'app_secret' => $facebook_app_secret,
    ), array('id' => 1));

    return ($save !== false);
  }
}
