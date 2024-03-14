<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>

	<style type="text/css">
		.team-manager-free-main-area-<?php echo esc_attr( $post_id ); ?> {
			display: block;
			overflow: hidden;
		}
		.team-manager-free-items-style2-<?php echo esc_attr( $post_id ); ?>{
			background: <?php echo esc_attr( $team_fbackground_color ); ?>;
			text-align: center;
			padding: 0px;
			width:100%;
			height : 100%;
		}
		div#team-manager-free-single-items-<?php echo esc_attr( $post_id ); ?> {
		    display: flex;
		    flex-direction: row;
		    flex-wrap: wrap;
		}
		.team-manager-free-items-style2-pic-<?php echo esc_attr( $post_id ); ?> > a {
			border: medium none;
			box-shadow: none !important;
			outline: medium none;
			text-decoration: none;
		}
		<?php if($team_manager_free_imagesize == 1){ ?>
			.team-manager-free-items-style2-<?php echo esc_attr( $post_id ); ?> .team-manager-free-items-style2-pic-<?php echo esc_attr( $post_id ); ?> img{
				border-radius: 0;
				box-shadow: none;
				height: auto;
				width: 100%;
			}
		<?php }elseif($team_manager_free_imagesize == 2){ ?>
			.team-manager-free-items-style2-<?php echo esc_attr( $post_id ); ?> .team-manager-free-items-style2-pic-<?php echo esc_attr( $post_id ); ?> img{
				border-radius: 0;
				box-shadow: none;
				height: <?php echo esc_attr( $team_manager_free_img_height);?> px;
				width: 100%;
			}
		<?php } ?>
		.team-manager-free-items-style2-social_media_team-<?php echo esc_attr( $post_id ); ?>{
			padding: 0px;
			margin-bottom: 0 !important;
			list-style: none;
			text-align: center;
			background: <?php echo esc_attr( $team_manager_free_overlay_bg_color);?>;
			margin:0px;
			padding: 0px 20px;
		}
		.team-manager-free-items-style2-social_media_team-<?php echo esc_attr( $post_id ); ?> > li{
			display: inline-block;
			margin-bottom: 0px;
			margin-right: 5px;
			margin-top: 0px;
			padding: 8px 0px;
		}
		.team-manager-free-items-style2-social_media_team-<?php echo esc_attr( $post_id ); ?> > li > a{
			background: #31aab5 none repeat scroll 0 0;
			border-radius: 50%;
			box-shadow: none;
			color: #fff;
			display: block;
			height: 30px;
			line-height: 30px;
			transition: all 0.3s ease 0s;
			width: 30px;
			text-decoration: none !important;
		    box-shadow: none;
		    outline: none;
			border:none;
		}
		.team-manager-free-items-style2-social_media_team-<?php echo esc_attr( $post_id ); ?> > li > a:hover{
			background: #fff none repeat scroll 0 0;
			border: medium none;
			box-shadow: none;
			color: #31aab5;
			outline: medium none;
			text-decoration: none;
		}
		.team-manager-free-items-style2-teamprofiles-<?php echo esc_attr( $post_id ); ?> .team-manager-free-items-style2-team-title-<?php echo esc_attr( $post_id ); ?>{
			margin-bottom: 0px;
			margin-top: 0px !important;
			padding:10px 15px;
			border-bottom: 1px solid #ddd;
		}
		.team-manager-free-items-style2-teamprofiles-<?php echo esc_attr( $post_id ); ?> .team-manager-free-items-style2-team-title-<?php echo esc_attr( $post_id ); ?> a{
			box-shadow: none;
			color: <?php echo esc_attr( $team_manager_free_header_font_color ); ?>;
			font-size: <?php echo esc_attr( $team_manager_free_header_font_size); ?>px;
			letter-spacing: 1px;
			outline: medium none;
			text-decoration: none;
			text-transform: <?php echo esc_attr( $team_manager_name_font_case); ?>;
		}
		.team-manager-free-items-style2-teamprofiles-<?php echo esc_attr( $post_id ); ?> .team-manager-free-items-style2-team-title-<?php echo esc_attr( $post_id ); ?> a:hover{
			text-decoration:none;
			color:<?php echo esc_attr( $team_manager_free_name_hover_font_color); ?>;
		}
		.team-manager-free-items-style2-teamprofiles-<?php echo esc_attr( $post_id ); ?> .team-manager-free-items-style2-team-post-<?php echo esc_attr( $post_id ); ?>{
			border-bottom: 1px solid #ddd;
			display: block;
			padding: 10px 15px;
			font-size: <?php echo esc_attr( $team_manager_free_designation_font_size); ?>px;
			font-style: italic;
			color:<?php echo esc_attr( $team_manager_free_designation_font_color); ?>;
			letter-spacing: 0.5px;
		}
		.team-manager-free-items-style2-teamprofiles-<?php echo esc_attr( $post_id ); ?> .team-manager-free-items-style2-team-description-<?php echo esc_attr( $post_id ); ?>{
			font-size: <?php echo esc_attr( $team_manager_free_biography_font_size); ?>px;
			font-weight: 400;
			line-height: 23px;
			color:<?php echo esc_attr( $team_manager_free_biography_font_color); ?>;
		    padding: 15px;
		    margin-bottom: 0px;
		}
		@media screen and (max-width: 990px){
			.team-manager-free-items-style2-<?php echo esc_attr( $post_id ); ?>{
				margin-bottom: 30px;
			}
		}
		
		.lightbox { display: none; }

		.team_popup_container-<?php echo esc_attr( $post_id ); ?> {
			display: block;
			overflow: hidden;
		}
		.team_popup_left_side_area-<?php echo esc_attr( $post_id ); ?> {
			display: block;
			float: left;
			height: auto;
			margin-right: 50px;
			margin-top: 3px;
			width: 300px;
			overflow: hidden;
		}
		.team_popup_container-<?php echo esc_attr( $post_id ); ?> h2 {
			display: block;
			margin: 0px 0px 20px;
			overflow: hidden;
			font-size: 30px;
			font-weight: 600;
		}
		.team_popup_right_side_area-<?php echo esc_attr( $post_id ); ?> {
		  margin-top: 0px;
		  display: block;
		  overflow: hidden;
		}
		.team_popup_right_side_area-<?php echo esc_attr( $post_id ); ?> p {
		  line-height: 28px;
		  font-size: 15px;
		}
		.team_popup_left_side_area_img-<?php echo esc_attr( $post_id ); ?> > img {
		  display: block;
		  width: 300px;
		  overflow: hidden;
		}
		.team-manager-popup-items-social-<?php echo esc_attr( $post_id ); ?>{
			padding:0;
			margin:0;
			list-style:none;
		}
		.team-manager-popup-items-social-<?php echo esc_attr( $post_id ); ?> li{
			display:inline-block;
		}
		.team-manager-popup-items-social-<?php echo esc_attr( $post_id ); ?> li a{
			background: #fff none repeat scroll 0 0;
			border: 1px solid #fff;
			text-decoration: none !important;
		    box-shadow: none;
		    outline: none;
			color: #da3e65;
			transition: all 0.3s ease 0s;
			margin-right:10px;
		}
		.team-manager-popup-items-social-<?php echo esc_attr( $post_id ); ?> li a:hover{
			text-decoration:none;
			color:#333;
		}
		.team_popup_contact_area-<?php echo esc_attr( $post_id ); ?>{
			display: block;
			overflow : hidden;
			text-align: center;
			margin: 15px 0px;
			line-height: 25px;
		}
		.team_popup_contact_area-<?php echo esc_attr( $post_id ); ?> span.cemail {
		  display: block;
		  overflow: hidden;
		  text-align: left;
		}
		.team_popup_contact_area-<?php echo esc_attr( $post_id ); ?> span.cemail a{
		  outline: none;
		  text-decoration: none;
		  box-shadow: none;
		  color:#333;
		}

		/* Tablet Layout: 768px. */
		@media only screen and (min-width: 768px) and (max-width: 991px) { 
			.featherlight .featherlight-content {
			    margin-left: 30px;
			    margin-right: 30px;
			    max-height: 98%;
			    padding: 10px 10px 0;
			    border-bottom: 10px solid transparent;
			}
		}


		/* Wide Mobile Layout: 480px. */
		@media only screen and (min-width: 480px) and (max-width: 767px) {
			.team-manager-free-items-style2-<?php echo esc_attr( $post_id ); ?> .team-manager-free-items-style2-pic-<?php echo esc_attr( $post_id ); ?> img{
				border-radius: 0;
				box-shadow: none;
				height: auto;
				width: 100%;
			}
			.team_popup_left_side_area-<?php echo esc_attr( $post_id ); ?> {
			    display: block;
			    float: none;
			    height: auto;
			    margin-right: 50px;
			    width: 100%;
			    overflow: hidden;
			}
			.featherlight .featherlight-content {
			    margin-left: 30px;
			    margin-right: 30px;
			    max-height: 98%;
			    padding: 10px 10px 0;
			    border-bottom: 10px solid transparent;
			}
			.team_popup_left_side_area_img-<?php echo esc_attr( $post_id ); ?> > img {
			    display: block;
			    width: 100%;
			    overflow: hidden;
			    height: 100%;
			}
		}

		/* XS Portrait */
		@media (max-width: 479px) {
			.team-manager-free-items-style2-<?php echo esc_attr( $post_id ); ?> .team-manager-free-items-style2-pic-<?php echo esc_attr( $post_id ); ?> img{
				border-radius: 0;
				box-shadow: none;
				height: auto;
				width: 100%;
			}
			.team_popup_left_side_area-<?php echo esc_attr( $post_id ); ?> {
			    display: block;
			    float: none;
			    height: auto;
			    margin-right: 50px;
			    margin-top: 3px;
			    width: 100%;
			    overflow: hidden;
			}
			.featherlight .featherlight-content {
			    margin-left: 30px;
			    margin-right: 30px;
			    max-height: 98%;
			    padding: 10px 10px 0;
			    border-bottom: 10px solid transparent;
			}
			.team_popup_left_side_area_img-<?php echo esc_attr( $post_id ); ?> > img {
			    display: block;
			    width: 100%;
			    overflow: hidden;
			    height: 100%;
			}
		}
	</style>



	<div class="team-manager-free-main-area-<?php echo esc_attr( $post_id ); ?>">
		<div id="team-manager-free-single-items-<?php echo esc_attr( $post_id ); ?>">
			<?php
			// Creating a new side loop
			while ( $tmf_query->have_posts() ) : $tmf_query->the_post();
				$thumb_id 			= get_post_thumbnail_id();
				$thumb_url 			= wp_get_attachment_image_src($thumb_id,'thumbnail-size', true);
				$team_manager_free_client_designation 		= get_post_meta(get_the_ID(), 'client_designation', true);
				$team_manager_free_client_shortdescription 	= get_post_meta(get_the_ID(), 'client_shortdescription', true);
				$team_manager_free_client_email 			= get_post_meta(get_the_ID(), 'contact_email', true);
				$team_manager_free_client_number 			= get_post_meta(get_the_ID(), 'contact_number', true);
				$team_manager_free_client_address 			= get_post_meta(get_the_ID(), 'company_address', true);
				$team_manager_free_social_facebook 			= get_post_meta(get_the_ID(), 'social_facebook', true);
				$team_manager_free_social_twitter 			= get_post_meta(get_the_ID(), 'social_twitter', true);
				$team_manager_free_social_googleplus 		= get_post_meta(get_the_ID(), 'social_googleplus', true);
				$team_manager_free_social_instagram 		= get_post_meta(get_the_ID(), 'social_instagram', true);
				$team_manager_free_social_pinterest 		= get_post_meta(get_the_ID(), 'social_pinterest', true);
				$team_manager_free_social_linkedin 			= get_post_meta(get_the_ID(), 'social_linkedin', true);
				$team_manager_free_social_dribbble 			= get_post_meta(get_the_ID(), 'social_dribbble', true);
				$team_manager_free_social_youtube 			= get_post_meta(get_the_ID(), 'social_youtube', true);
				$team_manager_free_social_skype 			= get_post_meta(get_the_ID(), 'social_skype', true);

				$content 			= apply_filters( 'the_content', get_the_content() );
				$random_team_id 	= rand();
				?>

				<div class="teamshowcasefree-col-lg-<?php echo esc_attr( $team_manager_free_post_column ); ?> teamshowcasefree-col-md-2 teamshowcasefree-col-sm-2 teamshowcasefree-col-xs-1">
					<div class="team-manager-free-items-style2-<?php echo esc_attr( $post_id ); ?>">
						<div class="team-manager-free-items-style2-pic-<?php echo esc_attr( $post_id ); ?>">
							<a href="#" data-featherlight="#fl1<?php echo esc_attr( $random_team_id ); ?>">
								<img src="<?php echo esc_url( $thumb_url[0] );?>" alt="" />
							</a>
								<div class="lightbox" id="fl1<?php echo esc_attr( $random_team_id ); ?>">
									<div class="team_popup_container-<?php echo esc_attr( $post_id ); ?>">
										<h2><?php echo esc_attr(get_the_title()); ?></h2>
										<div class="team_popup_left_side_area-<?php echo esc_attr( $post_id ); ?>">
											<div class="team_popup_left_side_area_img-<?php echo esc_attr( $post_id ); ?>">
												<img src="<?php echo esc_url( $thumb_url[0] );?>" alt="" />
											</div>
											<div class="team_popup_contact_area-<?php echo esc_attr( $post_id ); ?>">
												<span class="cemail"><a href="mailto:<?php echo esc_attr( $team_manager_free_client_email ); ?>">
													<?php echo sanitize_email( $team_manager_free_client_email ); ?></a></span>
												<span class="cemail"><?php echo esc_html( $team_manager_free_client_number ); ?></span>
												<span class="cemail"><?php echo esc_html( $team_manager_free_client_address ); ?></span>
											</div>
											<ul class="team-manager-popup-items-social-<?php echo esc_attr( $post_id ); ?>">
												<?php if(!empty($team_manager_free_social_facebook)){ ?>
													<li><a target="<?php echo esc_attr( $team_manager_free_social_target ); ?>" href="<?php echo esc_url($team_manager_free_social_facebook); ?>" class="fa fa-facebook"></a></li>
												<?php } ?>
												<?php if(!empty($team_manager_free_social_twitter)){ ?>
													<li><a target="<?php echo esc_attr( $team_manager_free_social_target ); ?>" href="<?php echo esc_url($team_manager_free_social_twitter ); ?>" class="fa fa-twitter"></a></li>
												<?php } ?>
												<?php if(!empty($team_manager_free_social_googleplus)){ ?>
													<li><a target="<?php echo esc_attr( $team_manager_free_social_target ); ?>" href="<?php echo esc_url($team_manager_free_social_googleplus); ?>" class="fa fa-google-plus"></a></li>
												<?php } ?>
												<?php if(!empty($team_manager_free_social_instagram)){ ?>
													<li><a target="<?php echo esc_attr( $team_manager_free_social_target ); ?>" href="<?php echo esc_url($team_manager_free_social_instagram); ?>" class="fa fa-instagram"></a></li>
												<?php } ?>
												<?php if(!empty($team_manager_free_social_pinterest)){ ?>
													<li><a target="<?php echo esc_attr( $team_manager_free_social_target ); ?>" href="<?php echo esc_url($team_manager_free_social_pinterest); ?>" class="fa fa-pinterest"></a></li>
												<?php } ?>
												<?php if(!empty($team_manager_free_social_linkedin)){ ?>
													<li><a target="<?php echo esc_attr( $team_manager_free_social_target ); ?>" href="<?php echo esc_url($team_manager_free_social_linkedin); ?>" class="fa fa-linkedin"></a></li>
												<?php } ?>
												<?php if(!empty($team_manager_free_social_dribbble)){ ?>
													<li><a target="<?php echo esc_attr( $team_manager_free_social_target ); ?>" href="<?php echo esc_url($team_manager_free_social_dribbble); ?>" class="fa fa-dribbble"></a></li>
												<?php } ?>
												<?php if(!empty($team_manager_free_social_youtube)){ ?>
													<li><a target="<?php echo esc_attr( $team_manager_free_social_target ); ?>" href="<?php echo esc_url($team_manager_free_social_youtube); ?>" class="fa fa-youtube"></a></li>
												<?php } ?>
												<?php if(!empty($team_manager_free_social_skype)){ ?>
													<li><a target="<?php echo esc_attr( $team_manager_free_social_target ); ?>" href="<?php echo esc_url($team_manager_free_social_skype); ?>" class="fa fa-skype"></a></li>
												<?php } ?>
											</ul>
										</div>
										<div class="team_popup_right_side_area-<?php echo esc_attr( $post_id ); ?>">
											<?php the_content(); ?>
										</div>
									</div>
								</div>
						</div>
						<ul class="team-manager-free-items-style2-social_media_team-<?php echo esc_attr( $post_id ); ?>">
							<?php if(!empty($team_manager_free_social_facebook)){ ?>
								<li><a target="<?php echo esc_attr( $team_manager_free_social_target ); ?>" href="<?php echo esc_url($team_manager_free_social_facebook); ?>" class="fa fa-facebook"></a></li>
							<?php } ?>
							<?php if(!empty($team_manager_free_social_twitter)){ ?>
								<li><a target="<?php echo esc_attr( $team_manager_free_social_target ); ?>" href="<?php echo esc_url($team_manager_free_social_twitter); ?>" class="fa fa-twitter"></a></li>
							<?php } ?>
							<?php if(!empty($team_manager_free_social_googleplus)){ ?>
								<li><a target="<?php echo esc_attr( $team_manager_free_social_target ); ?>" href="<?php echo esc_url($team_manager_free_social_googleplus); ?>" class="fa fa-google-plus"></a></li>
							<?php } ?>
							<?php if(!empty($team_manager_free_social_instagram)){ ?>
								<li><a target="<?php echo esc_attr( $team_manager_free_social_target ); ?>" href="<?php echo esc_url($team_manager_free_social_instagram); ?>" class="fa fa-instagram"></a></li>
							<?php } ?>
							<?php if(!empty($team_manager_free_social_pinterest)){ ?>
								<li><a target="<?php echo esc_attr( $team_manager_free_social_target ); ?>" href="<?php echo esc_url($team_manager_free_social_pinterest); ?>" class="fa fa-pinterest"></a></li>
							<?php } ?>
							<?php if(!empty($team_manager_free_social_linkedin)){ ?>
								<li><a target="<?php echo esc_attr( $team_manager_free_social_target ); ?>" href="<?php echo esc_url($team_manager_free_social_linkedin); ?>" class="fa fa-linkedin"></a></li>
							<?php } ?>
							<?php if(!empty($team_manager_free_social_dribbble)){ ?>
								<li><a target="<?php echo esc_attr( $team_manager_free_social_target ); ?>" href="<?php echo esc_url($team_manager_free_social_dribbble); ?>" class="fa fa-dribbble"></a></li>
							<?php } ?>
							<?php if(!empty($team_manager_free_social_youtube)){ ?>
								<li><a target="<?php echo esc_attr( $team_manager_free_social_target ); ?>" href="<?php echo esc_url($team_manager_free_social_youtube); ?>" class="fa fa-youtube"></a></li>
							<?php } ?>
							<?php if(!empty($team_manager_free_social_skype)){ ?>
								<li><a target="<?php echo esc_attr( $team_manager_free_social_target ); ?>" href="<?php echo esc_url($team_manager_free_social_skype); ?>" class="fa fa-skype"></a></li>
							<?php } ?>
						</ul>
						<div class="team-manager-free-items-style2-teamprofiles-<?php echo esc_attr( $post_id ); ?>">
							<div class="team-manager-free-items-style2-team-title-<?php echo esc_attr( $post_id ); ?>">
								<a href="" data-featherlight="#fl1<?php echo esc_attr( $random_team_id ); ?>">
									<?php echo esc_attr(get_the_title()); ?>
								</a>
							</div>
							<div class="team-manager-free-items-style2-team-post-<?php echo esc_attr( $post_id ); ?>">
								<?php echo esc_html( $team_manager_free_client_designation ); ?>
							</div>
							<p style="display:<?php echo esc_attr( $team_manager_free_biography_option ); ?>" class="team-manager-free-items-style2-team-description-<?php echo esc_attr( $post_id ); ?>">
								<?php echo esc_html( $team_manager_free_client_shortdescription ); ?>
							</p>
						</div>
					</div>
				</div>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	</div>
	<div class="clearfix"></div>