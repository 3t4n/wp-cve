<?php

if( !defined( 'ABSPATH' ) ){
    exit;
}

?>
	<style type="text/css">
		.team-manager-free-main-area-<?php echo esc_attr( $post_id ); ?> {
			display: block;
			overflow: hidden;
		}
		.team-manager-free-main-area-<?php echo esc_attr( $post_id ); ?> #team-manager-free-single-items-<?php echo esc_attr( $post_id ); ?> {
		    display: flex;
		    flex-direction: row;
		    flex-wrap: wrap;
		}
		.team-manager-free-items-style3-<?php echo esc_attr( $post_id ); ?>{
			text-align:center;
			width:100%;
			height:100%;
		}
		.team-manager-free-items-style3-<?php echo esc_attr( $post_id ); ?> .team-manager-free-items-style3-pic-<?php echo esc_attr( $post_id ); ?>{
			position: relative;
			border-radius: 100px;
			width: 200px;
			height: 200px;
			overflow: hidden;
			margin: 0 auto;
		}
		<?php if($team_manager_free_imagesize == 1){ ?>
			.team-manager-free-items-style3-<?php echo esc_attr( $post_id ); ?> .team-manager-free-items-style3-pic-<?php echo esc_attr( $post_id ); ?> img{
				width: 100%;
				height: auto;
				border-radius: 50%;
			}
		<?php }elseif($team_manager_free_imagesize == 2){ ?>
			.team-manager-free-items-style3-<?php echo esc_attr( $post_id ); ?> .team-manager-free-items-style3-pic-<?php echo esc_attr( $post_id ); ?> img{
				width: 100%;
				height: <?php echo esc_attr( $team_manager_free_img_height); ?>px;
				border-radius: 50%;
			}
		<?php } ?>
		.team-manager-free-items-style3-<?php echo esc_attr( $post_id ); ?> .pic-bottom{
			border-radius: 100px;
			box-shadow: none;
			outline: medium none;
			position: absolute;
			left: 0;
			right: 0;
			top: 0;
			transition: all 0.3s ease 0s;
			height: 200px;
			width: 200px;
		}
		.team-manager-free-items-style3-<?php echo esc_attr( $post_id ); ?> .pic-bottom:after{
			content: "\f002";
			font-family: "FontAwesome";
			position: relative;
			top:45%;
			left:0;
			opacity: 0;
			color:#fff;
			font-size: 35px;
			transition:all 0.3s ease 0s;
		}
		.team-manager-free-items-style3-<?php echo esc_attr( $post_id ); ?> .team-manager-free-items-style3-pic-<?php echo esc_attr( $post_id ); ?>:hover .pic-bottom{
			background: <?php echo esc_attr( $team_manager_free_overlay_bg_color ); ?>;
			opacity: 0.9;
		}
		.team-manager-free-items-style3-<?php echo esc_attr( $post_id ); ?> .team-manager-free-items-style3-pic-<?php echo esc_attr( $post_id ); ?>:hover .pic-bottom:after{
			opacity: 1;
		}
		.team-manager-free-items-style3-<?php echo esc_attr( $post_id ); ?> .team-manager-free-items-style3-post-title-<?php echo esc_attr( $post_id ); ?>{
			font-size: <?php echo esc_attr( $team_manager_free_header_font_size ); ?>px;
			font-weight: 700;
			color:<?php echo esc_attr( $team_manager_free_header_font_color ); ?>;
			line-height: 27px;
			margin-bottom: 5px;
			margin-top: 15px;
			text-transform: <?php echo esc_attr( $team_manager_name_font_case ); ?>;
		}
		.team-manager-free-items-style3-<?php echo esc_attr( $post_id ); ?> .team-manager-free-items-style3-post-title-<?php echo esc_attr( $post_id ); ?> a{
			color:#232a34;
			transition: all 0.3s ease 0s;
		}
		.team-manager-free-items-style3-<?php echo esc_attr( $post_id ); ?> .team-manager-free-items-style3-post-title-<?php echo esc_attr( $post_id ); ?> a:hover{
			color:#727cb6;
			text-decoration: none;
		}
		.team-manager-free-items-style3-<?php echo esc_attr( $post_id ); ?> .team-manager-free-items-style3-post-<?php echo esc_attr( $post_id ); ?>{
			margin-bottom: 10px;
			display: block;
			color:<?php echo esc_attr( $team_manager_free_designation_font_color ); ?> ;
			font-size: <?php echo esc_attr( $team_manager_free_designation_font_size ); ?>px;
		}
		.team-manager-free-items-style3-<?php echo esc_attr( $post_id ); ?> .team-manager-free-items-style3-team-social-<?php echo esc_attr( $post_id ); ?>{
			list-style: none;
			padding: 0;
		}
		.team-manager-free-items-style3-<?php echo esc_attr( $post_id ); ?> .team-manager-free-items-style3-team-social-<?php echo esc_attr( $post_id ); ?> > li{
			display: inline-block;
			margin: 5px;
			padding: 0;
		}
		.team-manager-free-items-style3-<?php echo esc_attr( $post_id ); ?> .team-manager-free-items-style3-team-social-<?php echo esc_attr( $post_id ); ?> > li > a{
			background: #efefef none repeat scroll 0 0;
			border-radius: 50%;
			box-shadow: none;
			color: #727cb6;
			display: block;
			height: 30px;
			line-height: 30px;
			outline: medium none;
			text-decoration: none;
			transition: all 0.3s ease 0s;
			width: 30px;
			border:none;
		}
		.team-manager-free-items-style3-<?php echo esc_attr( $post_id ); ?> .team-manager-free-items-style3-team-social-<?php echo esc_attr( $post_id ); ?> > li > a:hover{
			background: #727cb6;
			color:#fff;
		}
		@media screen and (max-width: 990px){
			.team-manager-free-items-style3-<?php echo esc_attr( $post_id ); ?>{
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
		.team_popup_container-<?php echo esc_attr( $post_id ); ?> > h2 {
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
			box-shadow: none;
			color: #da3e65;
			margin-right:10px;
			text-decoration:none;
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
			.team-manager-free-items-style3-<?php echo esc_attr( $post_id ); ?> .team-manager-free-items-style3-pic-<?php echo esc_attr( $post_id ); ?> img{
				width: 100%;
				height: auto;
				border-radius: 50%;
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

		/* XS Portrait */
		@media (max-width: 479px) {
			.team-manager-free-items-style3-<?php echo esc_attr( $post_id ); ?> .team-manager-free-items-style3-pic-<?php echo esc_attr( $post_id ); ?> img{
				width: 100%;
				height: auto;
				border-radius: 50%;
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
				$team_manager_free_client_designation 			= get_post_meta(get_the_ID(), 'client_designation', true);
				$team_manager_free_client_shortdescription 		= get_post_meta(get_the_ID(), 'client_shortdescription', true);
				$team_manager_free_client_email 				= get_post_meta(get_the_ID(), 'contact_email', true);
				$team_manager_free_client_number 				= get_post_meta(get_the_ID(), 'contact_number', true);
				$team_manager_free_client_address 				= get_post_meta(get_the_ID(), 'company_address', true);
				$team_manager_free_social_facebook 				= get_post_meta(get_the_ID(), 'social_facebook', true);
				$team_manager_free_social_twitter 				= get_post_meta(get_the_ID(), 'social_twitter', true);
				$team_manager_free_social_googleplus 			= get_post_meta(get_the_ID(), 'social_googleplus', true);
				$team_manager_free_social_instagram 			= get_post_meta(get_the_ID(), 'social_instagram', true);
				$team_manager_free_social_pinterest 			= get_post_meta(get_the_ID(), 'social_pinterest', true);
				$team_manager_free_social_linkedin 				= get_post_meta(get_the_ID(), 'social_linkedin', true);
				$team_manager_free_social_dribbble 				= get_post_meta(get_the_ID(), 'social_dribbble', true);
				$team_manager_free_social_youtube 				= get_post_meta(get_the_ID(), 'social_youtube', true);
				$team_manager_free_social_skype 				= get_post_meta(get_the_ID(), 'social_skype', true);

				$content 			= apply_filters( 'the_content', get_the_content() );
				$random_team_id 	= rand();
				?>

				<div class="teamshowcasefree-col-lg-<?php echo esc_attr( $team_manager_free_post_column ); ?> teamshowcasefree-col-md-2 teamshowcasefree-col-sm-2 teamshowcasefree-col-xs-1">
					<div class="team-manager-free-items-style3-<?php echo esc_attr( $post_id ); ?>">
						<div class="team-manager-free-items-style3-pic-<?php echo esc_attr( $post_id ); ?>">
							<img src="<?php echo esc_url( $thumb_url[0] );?>" alt="" />
							<a href="#" data-featherlight="#fl1<?php echo esc_attr( $random_team_id ); ?>" class="pic-bottom"></a>
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
						<div class="team-manager-free-items-style3-teamprofile-<?php echo esc_attr( $post_id ); ?>">
							<h3 class="team-manager-free-items-style3-post-title-<?php echo esc_attr( $post_id ); ?>">
								<?php echo esc_attr(get_the_title()); ?>
							</h3>
							<span class="team-manager-free-items-style3-post-<?php echo esc_attr( $post_id ); ?>">
								<?php echo esc_html( $team_manager_free_client_designation ); ?>
							</span>
							<ul class="team-manager-free-items-style3-team-social-<?php echo esc_attr( $post_id ); ?>">
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
						</div>
					</div>
				</div>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>
	</div>
	<div class="clearfix"></div>