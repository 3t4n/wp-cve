<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>
<div class="wrap" id="weblizar_wrap">		
	<div id="content_wrap">
		   <!-- tabs left -->
        <div class="home-content-top feed-head-cont">
         <!--our-quality-shadow-->
         	<div class="clearfix"></div>
		 	<div class="bg-feed-head">
	         	<div class="feed-head clearfix">
	         		<div class="row">
						<div class="col-md-6 pl-4">
				            <div class="col-md-3 feed-head-logo">
				               <img src="<?php echo esc_url( WEBLIZAR_FACEBOOK_PLUGIN_URL.'/images/logo.png' ); ?>" class="img-fluid" alt="Weblizar">
				            </div>
							<div class="feed-head-cont-text ">
				                <h4>
				                  	<span class=""><?php esc_html_e( 'Social LikeBox & Feed', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?> </span>
				                </h4>
				                <p><?php esc_html_e('Display a completely responsive & customizable facebook feed on your website which match with the look and feel of your website', WEBLIZAR_FACEBOOK_TEXT_DOMAIN );?>. </p>
							</div>
						</div>
			            <div class="col-md-6 feed-head-cont pr-4">
							<div class="feed-head-cont-inner">
								<div class="col-md-12 col-sm-12 search1 text-right pull-right">
							   		<a href="http://wordpress.org/plugins/facebook-by-weblizar/" class="btn button button-primary" target="_blank" title="<?php esc_attr_e( 'Support Forum', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>"><span class="fas fa-comment"></span> <?php esc_html_e('Support Forum', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></a>
									<a href="<?php echo esc_url( WEBLIZAR_FACEBOOK_PLUGIN_URL . 'readme.txt' ); ?>" class="btn button button-primary" target="_blank" title="<?php esc_attr_e( 'Plugin Changelog', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>"> <span class="fas fa-pen-square"></span> <?php esc_html_e('Plugin Change Log', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></a>      
									<a href="https://weblizar.com/plugins/facebook-feed-pro/" class="text-right btn button rating"><?php esc_html_e('Upgrade To Pro', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?> </a>
									<div class="wporg-ratings rating-stars">
										<strong><?php esc_html_e('Do you like this plugin', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?> ? <br></strong> <?php esc_html_e('Please take a few seconds to', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>
										<a class="weblizar-rate-it"  href="https://wordpress.org/support/plugin/facebook-by-weblizar/reviews/#new-post" target="_blank"><?php esc_html_e('Rate it on WordPress.org', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?></a>!<br>				
										<a href="https://wordpress.org/support/plugin/facebook-by-weblizar/reviews/#new-post" data-rating="5" title="<?php esc_attr_e( 'Fantastic!', WEBLIZAR_FACEBOOK_TEXT_DOMAIN ); ?>" class="startrat" target="_blank" >
											<span class="dashicons dashicons-star-filled" ></span>
											<span class="dashicons dashicons-star-filled" ></span>
											<span class="dashicons dashicons-star-filled" ></span>
											<span class="dashicons dashicons-star-filled" ></span>
											<span class="dashicons dashicons-star-filled" ></span>
										</a>
									</div>
								</div>
				            </div>
			            </div>
		            </div>
	         	</div>	
            </div>	
			<div class="tabbable-panel  col-m margin-tops4">
	            <div class="tabbable-line">
					<div id="content">
						<div id="options_tabs" class="">
							<ul class="nav nav-tabs tabtop  tabsetting " role="tablist" id="nav">					
								<li class="active"><a id="general"><div class="dashicons dashicons-admin-generic"></div><?php esc_html_e(' Like Box', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></a></li>
								<li><a id="fbfeed"><div class="dashicons dashicons-align-right"></div><?php esc_html_e(' Social Feed', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></a></li>
								
								<li><a id="needhelp"><div class="dashicons dashicons-editor-help"></div><?php esc_html_e(' Need Help', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></a></li>
								<li><a id="upgradetopro" ><div class="dashicons dashicons-awards"></div><?php esc_html_e(' Upgrade to Pro', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);?></a></li>		
							</ul>
							<?php require_once('help-body.php'); ?>
							<?php require_once('facebook-feed.php'); ?>
						</div>		
					</div>
					<div class="clear"></div>
				</div>
	 		</div>
 		</div>
	</div>
</div>
