<?php

if( !defined( 'ABSPATH' ) ) exit;

?>

<div class="wrap">
<h1 class="title_xwp"><img src='<?php echo plugin_dir_url( __FILE__ )."icon.png"?>' style="margin-right: 10px" />
<span class="logoso">Social</span><span class="logo2">2</span><span class="logowp">Blog</span>s</h1>
<h2 class="nav-tab-wrapper">
	<a href="<?php echo SOCIAL2BLOG_LOCALURL?>" class="nav-tab nav-tab"><?php echo __( 'Generale', 'social2blog-text' )?></a>

		<a <?php echo $social2blogfacebook->isFBConnected() ? "href=".SOCIAL2BLOG_LOCALURL."-facebook" : ""?> class="nav-tab nav-tab">Facebook</a>
		<a <?php echo $social2blogtwitter->isTWConnected() ? "href=".SOCIAL2BLOG_LOCALURL."-twitter" : ""?> class="nav-tab nav-tab">Twitter</a>
		<a <?php echo "href=".SOCIAL2BLOG_LOCALURL."-instagram"; ?>  class="nav-tab nav-tab-active">Instagram</a>
		<a <?php echo "href=".SOCIAL2BLOG_LOCALURL."-gallery"; ?>  class="nav-tab nav-tab">Gallery</a>
</h2>
<h2><?php __( 'Manage Instagram', 'social2blog-text' )?></h2>
<div class="postbox s2b-postbox">
	<div class="pro_feat_tit">
		<?php echo __( 'Premium feature!','social2blog-text');  ?>
	</div>
	<div class="pro_feat_sub">
		<?php echo __( 'Visita premium','social2blog-text');  ?>
	</div>
</div>