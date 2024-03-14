<?php
require_once '../../../wp-load.php';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<?php
#wp_head();
seo_tag_cloud_widget_style($_GET);
wp_print_scripts('jquery.ball');
?>
</head>
<body <?php body_class(); ?>>
<?php
seo_tag_cloud_widget($_GET);
?>
</body>
</html>