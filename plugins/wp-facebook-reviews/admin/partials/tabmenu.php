<?php
$urltrimmedtab = remove_query_arg( array('page', '_wpnonce', 'taction', 'tid', 'sortby', 'sortdir', 'opt') );

$urlwelcome = esc_url( add_query_arg( 'page', 'wpfb-welcome-slug',$urltrimmedtab ) );
$urlfacebook = esc_url( add_query_arg( 'page', 'wpfb-facebook',$urltrimmedtab ) );
$urlreviewlist = esc_url( add_query_arg( 'page', 'wpfb-reviews',$urltrimmedtab ) );
$urltemplateposts = esc_url( add_query_arg( 'page', 'wpfb-templates_posts',$urltrimmedtab ) );
$urlgetpro = esc_url( add_query_arg( 'page', 'wpfb-get_pro',$urltrimmedtab ) );
$urlgettwitter = esc_url( add_query_arg( 'page', 'wpfb-get_twitter',$urltrimmedtab ) );
?>	
	<div class="w3-bar w3-border w3-white">
	<a href="<?php echo $urlwelcome; ?>" class="w3-bar-item w3-button <?php if($_GET['page']=='wpfb-welcome-slug'){echo 'w3-blue';} ?>"><i class="fa fa-home"></i> <?php _e(' Welcome', 'wp-fb-reviews'); ?></a>
	<a href="<?php echo $urlfacebook; ?>" class="w3-bar-item w3-button <?php if($_GET['page']=='wpfb-facebook'){echo 'w3-blue';} ?>"><i class="fa fa-search"></i> <?php _e(' Facebook', 'wp-fb-reviews'); ?></a>
	<a href="<?php echo $urlgettwitter; ?>" class="w3-bar-item w3-button <?php if($_GET['page']=='wpfb-get_twitter'){echo 'w3-blue';} ?>"><i class="fa fa-search"></i> <?php _e(' Twitter', 'wp-fb-reviews'); ?></a>
	<a href="<?php echo $urlreviewlist; ?>" class="w3-bar-item w3-button <?php if($_GET['page']=='wpfb-reviews'){echo 'w3-blue';} ?>"><i class="fa fa-list"></i> <?php _e('Review List', 'wp-fb-reviews'); ?></a>
	<a href="<?php echo $urltemplateposts; ?>" class="w3-bar-item w3-button <?php if($_GET['page']=='wpfb-templates_posts'){echo 'w3-blue';} ?>"><i class="fa fa-commenting-o"></i> <?php _e('Templates', 'wp-fb-reviews'); ?></a>
	<a href="https://wpreviewslider.com/" target="_blank" class="w3-bar-item w3-button goprohbtn <?php if($_GET['page']=='wpfb-get_pro'){echo 'w3-blue';} ?>"><i class="fa fa-external-link-square" aria-hidden="true"></i> <?php _e('Get Pro Version', 'wp-fb-reviews'); ?></a>
	
	</div>