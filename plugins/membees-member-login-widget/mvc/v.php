<?php

function display_membee_login_menu() {                        //admin menu

  add_options_page('Membee Login Options', 'Membee Login', 'manage_options', 'membee_login', 'display_membee_login_options');

}



function display_membee_login_options() {                     //admin options page

  global $membee_options;
  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
  ?>  

  <div id="membee-form-wrapper">

    <h2>Membee Login Options</h2>

    <form id="membee-options" action="<?php echo $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" method="POST">

      <span class="membee-label">Client ID</span><input id="clientid" name="membee_client_id" type="text" size="60" value="<?php if ($membee_options['membee_client_id']) echo $membee_options['membee_client_id']; ?>" />

      <span class="membee-label">Secret</span><input id="secret" name="membee_secret" type="text" size="60" value="<?php if ($membee_options['membee_secret']) echo $membee_options['membee_secret']; ?>" />

      <span class="membee-label">Application ID</span><input id="appid" name="membee_app_id" size="60" type="text" value="<?php if ($membee_options['membee_app_id']) echo $membee_options['membee_app_id']; ?>" />

      <input type="submit" value="Save options" id="submit-membee-options" />

    </form>

  <?php

  if ($membee_options['message']) {

  ?>  

    <div id="membee-options-errors"><?php echo $membee_options['membee_message']; ?></div>

  <?php

  } ?>

    <div style="clear: both"></div>

  </div>

<?php

}



function admin_header_stylesheet() { ?>

<style type="text/css">

#membee-form-wrapper { background-color: #E3E3E3; padding: 1px 0 10px 10px; width: 430px; margin-top: 20px; }

.membee-label { display: block; float: left; clear: left; width: 100px; vertical-align: middle; line-height: 25px; }

#membee-options input { float: left; width: 320px; }

input#submit-membee-options { clear: left; margin-top: 10px; cursor: pointer; width: 79px; }

#membee-options-errors { color: red; padding-top: 10px; clear: both; }

</style>

<?php

}



function prepare_flyout() {                                   //prepare flyout Membee widget

  global $membee_options;

  if ($membee_options['membee_secret']) {

    wp_enqueue_script('jquery');

    wp_enqueue_script('jquery-ui-core');
    
    wp_enqueue_script('jquery-ui-dialog');    

    add_action('wp_print_footer_scripts', 'enqueue_membee');

  }  

}



function enqueue_membee() {

  global $membee_options;
  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
  echo '<script type="text/javascript">
    $ = jQuery.noConflict();
  	jQuery(function($) {
  		if ($("#MembeeSignInLink").length>0) {
  			 $.getScript("https://memberservices.membee.com/feeds/Login/LoginScript.ashx?clientid='.$membee_options['membee_client_id'].'&appid='.$membee_options['membee_app_id'].'&destURL='.urlencode($protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']).'")
		}
	})
  </script>';
  
}



function membee_widget() {                                    //iFrame Membee widget 
	  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
  if (is_user_logged_in()) {    

  ?>

    <a href="<?php echo wp_logout_url( 'https://memberservices.membee.com/feeds/Login/Logout.aspx?clientid='.$membee_options['membee_client_id'].'&appid='.$membee_options['membee_app_id'].'&returnURL='.urlencode($protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) ); ?>">Log out</a>    

  <?php

  } else {

    if ($_REQUEST['error']) echo '<div id="membee-error-message">'.urldecode($_GET['error_description']).'</div>';

  ?>

    <script src="https://memberservices.membee.com/feeds/Login/LoginFrameScript.ashx?clientid=<?php echo $membee_options['membee_client_id']; ?>&appid=<?php echo $membee_options['membee_app_id']; ?>&destURL=<?php echo urlencode($protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); ?>" type="text/javascript"></script>    

  <?php

  }

}   



function membee_flyout_widget() {

  global $membee_options;

  if (is_user_logged_in()) {    

  ?>

    <a href="<?php echo wp_logout_url( 'https://memberservices.membee.com/feeds/Login/Logout.aspx?clientid='.$membee_options['membee_client_id'].'&appid='.$membee_options['membee_app_id'].'&returnURL='.urlencode($protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) ); ?>">Log out</a>    

  <?php

  } else {

    if (isset($_REQUEST['error'])&&$_REQUEST['error']) echo '<div id="membee-error-message">'.strip_tags(urldecode($_GET['error_description'])).'</div>';

  ?>

    <a id="MembeeSignInLink" href="#">Sign In</a> <div id="MembeeSignInModal"></div>    

  <?php

  }

}        



function membee_reset_widget() {                              //iFrame Membee Reset widget

  global $membee_options;

  ?>

  <script src="https://memberservices.membee.com/feeds/Login/ReAssocScript.ashx?appid=<?php echo $membee_options['membee_app_id']; ?>&clientid=<?php echo $membee_options['membee_client_id']; ?>" type="text/javascript"></script>    

  <?php                                                            

}  



function membee_login_shortcode( $atts ) {

  global $membee_options;

	extract( shortcode_atts( array(

		'type' => 'iframe'

	), $atts ) );

	if (is_user_logged_in()) {	  

	  return '<a href="'.wp_logout_url( 'https://memberservices.membee.com/feeds/Login/Logout.aspx?clientid='.$membee_options['membee_client_id'].'&appid='.$membee_options['membee_app_id'].'&returnURL='.urlencode($protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']) ).'">Log out</a>';   

  } else {

    $membee = '';

    if (isset($_REQUEST['error'])&&$_REQUEST['error']) $membee .= '<div id="membee-error-message">'.strip_tags(urldecode($_GET['error_description'])).'</div>';

    if ($type == 'iframe') {
	  $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
      $membee .= '<script src="https://memberservices.membee.com/feeds/Login/LoginFrameScript.ashx?clientid='.$membee_options['membee_client_id'].'&appid='.$membee_options['membee_app_id'].'&destURL='.urlencode($protocol.$_SERVER['HTTP_HOST'].urlencode($_SERVER['REQUEST_URI'])).'" type="text/javascript"></script>';

    } else if ($type == 'flyout') {

      $membee .= '<a id="MembeeSignInLink" href="#">Sign In</a> <div id="MembeeSignInModal" />';

    } 

    return $membee;		

  }  

}



function membee_reset_shortcode() {

  global $membee_options;

	return '<script src="https://memberservices.membee.com/feeds/Login/ReAssocScript.ashx?appid='.$membee_options['membee_app_id'].'&clientid='.$membee_options['membee_client_id'].'" type="text/javascript"></script>';

}



function change_logout_url($url) {                            //change logout url in admin bar

  global $membee_options;  
  
  if (!isset($redirect_to)) {

    return $url.'&redirect_to='.urlencode('https://memberservices.membee.com/feeds/Login/Logout.aspx?clientid='.$membee_options['membee_client_id'].'&appid='.$membee_options['membee_app_id'].'&returnURL='.urlencode(get_bloginfo('url')));

  } else {

    return $url;

  }    

}

?>
