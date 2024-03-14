<?php
/*
  * 1st link: set the standard 'favicon.ico' as the default one.
  * 2nd link: Supply a GIF version. If the browser supports it, it overrides the first one. GIF can be animated!
  */
function favicon_head() {
?>
<!-- WP Favicon -->
<link rel="shortcut icon" href="<?php echo get_bloginfo('wpurl'); ?>/favicon.ico" type="image/x-icon" />
<link rel="icon"          href="<?php echo get_bloginfo('wpurl'); ?>/favicon.gif" type="image/gif"    />
<!-- /WP Favicon -->
<?php
}
?>