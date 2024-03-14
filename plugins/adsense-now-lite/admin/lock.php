<?php

require('../EzGA.php');

if (EzGA::isLoggedIn()) {
  if (!EzGA::isActive()) {
    $pluginsPage = admin_url('plugins.php');
    wp_die("<h3>Plugin Not Active</h3><strong>Ads EZ Plugin for Google AdSense</strong> is not active.<br/ >Please activate it from your <a href='$pluginsPage'>plugin admin page</a> before accessing this page.");
  }
  return;
}
else {
  header("location: " . wp_login_url($_SERVER['PHP_SELF']));
  exit();
}
