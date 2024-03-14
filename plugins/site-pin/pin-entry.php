<?php

global $is_iphone;

// Redirect to https login if forced to use SSL
if ( force_ssl_admin() && !is_ssl() ) {
  if ( 0 === strpos($_SERVER['REQUEST_URI'], 'http') ) {
    wp_redirect(preg_replace('|^http://|', 'https://', $_SERVER['REQUEST_URI']));
    exit();
  } else {
    wp_redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
  }
}

add_action( 'login_head', 'wp_no_robots' );

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?php bloginfo('name'); ?> &rsaquo; Site PIN</title>
<?php
  wp_admin_css( 'wp-admin', true );
  wp_admin_css( 'colors-fresh', true );
  wp_admin_css( 'login', true );

  if ( $is_iphone ) { ?>
    <meta name="viewport" content="width=320; initial-scale=0.9; maximum-scale=1.0; user-scalable=0;" />
    <style type="text/css" media="screen">
    .login form, .login .message, #login_error { margin-left: 0px; }
    .login #nav, .login #backtoblog { margin-left: 8px; }
    .login h1 a { width: auto; }
    #login { padding: 20px 0; }
    </style>
<?php
  }

  do_action( 'login_enqueue_scripts' );
  do_action( 'login_head' );
?>
</head>
<body class="login login-action-login wp-core-ui">
<?php if ( !is_multisite() ) { ?>
<div id="login"><h1><a href="<?php echo esc_url( apply_filters('login_headerurl', 'http://wordpress.org/') ); ?>" title="<?php echo esc_attr( apply_filters('login_headertitle', __( 'Powered by WordPress' ) ) ); ?>"><?php bloginfo('name'); ?></a></h1>
<?php } else { ?>
<div id="login"><h1><a href="<?php echo esc_url( apply_filters('login_headerurl', network_home_url() ) ); ?>" title="<?php echo esc_attr( apply_filters('login_headertitle', $current_site->site_name ) ); ?>"><span class="hide"><?php bloginfo('name'); ?></span></a></h1>
<?php } ?>

<form name="loginform" id="loginform" action="" method="post">
  <p>
    <label for="site_pin">Site PIN<br />
    <input type="password" name="site_pin" id="site_pin" class="input" value="" size="10" tabindex="10" autocomplete="off" 
      style="font-size: 36px; padding: 10px; text-align: center; background: #fbfbfb;"/></label>
  </p>

  <p><?php echo get_site_option('site_pin_message', ''); ?></p>

  <p class="submit">
    <input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="Log In" tabindex="100" />
    <input type="hidden" name="redirect_to" value="" />
    <input type="hidden" name="testcookie" value="1" />
  </p>
</form>

<p id="nav">
<a href="<?php echo site_url('wp-login.php'); ?>" title="Alternatively, log in here">Alternatively, log in here</a>
</p>

<script type="text/javascript">
function wp_attempt_focus(){
setTimeout( function(){ try{
d = document.getElementById('user_login');
d.focus();
d.select();
} catch(e){}
}, 200);
}

wp_attempt_focus();
if(typeof wpOnload=='function')wpOnload();
</script>

  </div>

<?php do_action('login_footer'); ?>
<div class="clear"></div>
</body>
</html>
