<?php

class FFWDModelOptions_ffwd {

  private $facebook_sdk;
  private $app_id;
  private $app_secret;
  private $access_token;
  public  $date_timezones = '';
	public  $date_formats = array(
						"ago" => "2 days ago",
						"F j, Y, g:i a" => "March 10, 2015, 5:16 pm",
						"F j, Y" => "March 10, 2015",
						"l, F jS, Y" => "Tuesday, March 10th, 2015",
						"l, F jS, Y, g:i a" => "Tuesday, March 10th, 2015, 5:16 pm",
						"Y/m/d \a\\t g:i a" => "2015/03/10 at 12:50 AM",
						"Y/m/d" => " 2015/03/10",
                        "d/m/Y" => " 10/03/2015",
						"Y.m.d" => " 2015.03.10",
					);

  public function __construct() {
  }

  public function get_facebook_data() {
    global $wpdb;
		//if(!class_exists('Facebook'))

    include WD_FFWD_DIR . "/framework/facebook-sdk/src/Facebook/autoload.php";
		
		$row = $this->get_row_data(false);
		$this->app_id = $row->app_id;
		$this->app_secret = $row->app_secret;
		//$this->access_token = $row->user_access_token;

    if($row->app_id!='' and $row->app_secret!='') {
	        $this->facebook_sdk = new Facebook\Facebook( array(
		                                                     'app_id'     => $this->app_id,
		                                                     'app_secret' => $this->app_secret,
	                                                     ) );

    }



		if(isset($_POST['app_log_out'])) {
			//setcookie('fbs_'.$this->facebook_sdk->getAppId(), '', time()-100, '/', 'http://localhost/wordpress_rfe/');
			unset($_SESSION['facebook_access_token']);
		}
		/*if($this->facebook_sdk->getUser()) {
			try{
			}
			catch (FacebookApiException $e) {
				echo "<!--DEBUG: ".$e." :END-->";
				error_log($e);
			}
    }*/
		//echo $this->facebook_sdk->getAccessToken();


    if($this->facebook_sdk)
        return true;
    return false;
    //return $this->facebook_sdk->getUser();
  }
  
  public function log_in_log_out() {
		global $wpdb;

		$this->get_facebook_data();


		if($this->facebook_sdk) {
					$helper = $this->facebook_sdk->getRedirectLoginHelper();

					$user = 0; //$this->facebook_sdk->getUser();
					try {
						$accessToken = $helper->getAccessToken();
					} catch ( Facebook\Exceptions\FacebookSDKException $e ) {
						// There was an error communicating with Graph
						echo $e->getMessage();
						exit;
					}
				}

	  if (isset($accessToken)) {
		  // User authenticated your app!
		  // Save the access token to a session and redirect
		  $_SESSION['facebook_access_token'] = (string) $accessToken;
		  // Log them into your web framework here . . .
		  // Redirect here . . .

	  }


		if (isset($_SESSION['facebook_access_token'])) {
			try {

				// Proceed knowing you have a logged in user who's authenticated.
				$user_profile = $this->facebook_sdk->get('/me',$_SESSION['facebook_access_token']);
				/*$this->facebook_sdk->setExtendedAccessToken();
				$access_token = $this->facebook_sdk->getAccessToken();*/
			} catch (FacebookApiException $e) {
				echo '<div class="error"><p><strong>OAuth Error</strong>Error added to error_log: '.$e.'</p></div>';
				error_log($e);
				$user = null;
			}
					$user_profile=$user_profile->getDecodedBody();

				}


		// Login or logout url will be needed depending on current user state.
		$app_link_text = $app_link_url = null;
		if (isset($_SESSION['facebook_access_token']) && !isset($_POST['app_log_out'])) {


			/*$helper = $this->facebook_sdk->getRedirectLoginHelper();
		$callback=admin_url() . 'admin.php?page=options_ffwd';

		$app_link_url = $helper->getLogoutUrl($callback,'');
			$app_link_text = __("Logout of your app", 'facebook-albums');*/
			$validaye_app=0;
		} else {
		    if($this->facebook_sdk)
			$helper = $this->facebook_sdk->getRedirectLoginHelper();
            $permissions = array( 'user_photos', 'user_videos', 'user_posts', 'user_events' );
		    $callback=admin_url() . 'admin.php?page=options_ffwd';

			//$app_link_url = $helper->getLoginUrl(array('scope' => 'user_photos,user_videos,user_posts,user_events'));
			if($this->facebook_sdk)
          $app_link_url ='';// $helper->getLoginUrl($callback,$permissions);
			else
							$app_link_url='';
			$app_link_text = __('Log into Facebook with your app', 'facebook-albums');
			$validaye_app=1;
		} ?>
	  <?php 
		if(isset($_SESSION['facebook_access_token']) && !isset($_POST['app_log_out'])) :
			?>
			<script> 
				wd_fb_log_in = true; 
			</script>
			<div style="float: right;">
				<span style="margin: 0 10px;"><?php echo $user_profile['name']; ?></span>
				<img src="https://graph.facebook.com/<?php echo $user_profile['id']; ?>/picture?type=square" style="vertical-align: middle"/>
			</div>
			<ul style="margin:0px;list-style-type:none">
				<li>
					<a href="https://developers.facebook.com/apps/<?php echo $this->app_id; ?>" target="_blank"><?php _e("View your application's settings.", 'facebook-albums'); ?></a>
				</li>
				<input class="button-primary" type="submit" name="app_log_out" value="Log out from app" />
			</ul>
	  <?php 
		else :
      if(isset($_POST['app_log_out'])) {
				?> 
				  <script> 
					  window.location = '<?php echo admin_url() . 'admin.php?page=options_ffwd'; ?>';
				  </script> 
			  <?php
			}
			?>
	    <a id="<?php echo WD_FB_PREFIX; ?>_login_button" class="<?php echo WD_FB_PREFIX; ?>_login_button" <?php if($validaye_app==1) echo 'onclick="check_app(\'ffwd\',\'login\');if(jQuery(\'#ffwd_app_id\').val()==\'\') {alert(\'App Id is required\');return false;} if(jQuery(\'#ffwd_app_secret\').val()==\'\' ){alert(\'App secret is required\');return false;};"'?> href="<?php echo $app_link_url; ?>"><?php echo $app_link_text; ?></a>
	  <?php
		endif; 
		?>
	  <div style="clear: both;">&nbsp;</div>	  
	  <?php
  }

  /**
   * Get row data.
   *
   * @param bool $reset
   *
   * @return array|object|void|null
   */
  public function get_row_data( $reset = FALSE ) {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wd_fb_option WHERE id="%d"', 1));
    if ( $reset ) {
      $row->autoupdate_interval = 90;
      $row->app_id = '';
      $row->app_secret = '';
      $row->post_date_format = 'ago';
      $row->date_timezone = '';
      $row->event_date_format = '';
      $wpdb->update($wpdb->prefix . 'wd_fb_option', array(
        'autoupdate_interval' => $row->autoupdate_interval,
        'app_id' => $row->app_id,
        'app_secret' => $row->app_secret,
        'date_timezone' => '',
        'post_date_format' => $row->post_date_format,
        'event_date_format' => $row->event_date_format,
      ), array( 'id' => 1 ));
    }

    return $row;
  }
}