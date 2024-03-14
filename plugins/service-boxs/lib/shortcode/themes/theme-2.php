<?php
    if( !defined( 'ABSPATH' ) ){
        exit;
    }
?>

	<style type="text/css">
	.servicearea.id-<?php echo esc_attr( $post_id ); ?> {
	    display: block;
	    overflow: hidden;
	}
	.servicearea.id-<?php echo esc_attr( $post_id ); ?> .tpwp-style2-servicebox-items{
	    padding-top:<?php echo $rsbbox_padding_size; ?>px;
	    padding-right:<?php echo $rsbbox_padding_size; ?>px;
	    padding-bottom:<?php echo $rsbbox_padding_size; ?>px;
	    padding-left:90px;
	    text-align:<?php echo $rsbbox_alignment; ?>;
	    background:<?php echo $rsbbox_itembg_color; ?>;
		position: relative;
		transition: all 0.5s ease 0s;
	}
	.servicearea.id-<?php echo esc_attr( $post_id ); ?> .tpwp-style2-service-icon{
		display: inline-block;
	    position: absolute;
	    top: 33%;
	    left: 23px;
		text-align: center;
	    opacity: 0.3;
	    transition: all 0.5s ease-in 0s;
		margin-bottom:0px;
	}
	.servicearea.id-<?php echo esc_attr( $post_id ); ?> .tpwp-style2-service-icon i{
		color: <?php echo esc_attr($rsbbox_itemicons_color);?>;
		font-size: <?php echo esc_attr($rsbbox_iconsize);?>px;
		line-height: <?php echo esc_attr($rsbbox_iconheight);?>px;
	    font-family: "FontAwesome";
	    font-weight: unset;
	}
	.servicearea.id-<?php echo esc_attr( $post_id ); ?> .tpwp-style2-servicebox-items:hover .tpwp-style2-service-icon{
		opacity: 1;
	}
	.servicearea.id-<?php echo esc_attr( $post_id ); ?> .tpwp-style2-service-content h3,
	.servicearea.id-<?php echo esc_attr( $post_id ); ?> .tpwp-style2-service-content h3 a{
		color:<?php echo $rsbbox_itemtitle_color; ?>;
		font-size: <?php echo $rsbbox_titlesize; ?>px;
		font-weight: 600;
		margin: 0;
	    margin-bottom: 10px;
	}
	.servicearea.id-<?php echo esc_attr( $post_id ); ?> .tpwp-style2-service-content h3 a:hover{
		color:<?php echo $rsbbox_itemtitleh_color; ?>;
	}
	.servicearea.id-<?php echo esc_attr( $post_id ); ?> .tpwp-style2-service-content p {
		color:<?php echo $rsbbox_conten_color; ?>;
		font-size: <?php echo esc_attr($rsbbox_contentsize);?>px;
		margin: 0;
		padding: 0;
	}
	.servicearea.id-<?php echo esc_attr( $post_id ); ?> a.tpwp-style2-readmore {
		color:<?php echo $rsbbox_moreoption_color; ?>;
		font-size: <?php echo esc_attr($rsbbox_moresize);?>px;
		display: inline-block;
		padding-top: 10px;
		outline: medium none;
		text-decoration: none;
	}
	.servicearea.id-<?php echo esc_attr( $post_id ); ?> a.tpwp-style2-readmore:hover {
		color:<?php echo $rsbbox_moreoptionhover_color; ?>;
	}
	</style>

	<div class="servicearea id-<?php echo esc_attr( $post_id ); ?>">
    	<?php
	    while ($service_query->have_posts()) : $service_query->the_post();

		$shortdetails 			= get_post_meta( $post->ID, 'tup_biography', true );
		$rsbbox_button_text 	= get_post_meta( $post->ID, 'rsbbox_button_text', true );
		$rsbboxurl 				= get_post_meta( $post->ID, 'rsbbox_url', true );
		$rsbboxicons 			= get_post_meta( $post->ID, 'ftw_icon', true );
		$rsbbox_back_color 		= get_post_meta( $post->ID, 'rsbbox_back_color', true );
		$rsbbox_icon_color 		= get_post_meta( $post->ID, 'rsbbox_icon_color', true );
		$rsbbox_iconbg_color 	= get_post_meta( $post->ID, 'rsbbox_iconbg_color', true );
		$rsbbox_title_color 	= get_post_meta( $post->ID, 'rsbbox_title_color', true );
		$rsbbox_title_h_color 	= get_post_meta( $post->ID, 'rsbbox_title_h_color', true );
		$rsbbox_content_color 	= get_post_meta( $post->ID, 'rsbbox_content_color', true );
		$rsbbox_moresize_color 	= get_post_meta( $post->ID, 'rsbbox_moresize_color', true );

		?>

		<div class="serviceboxs-col-lg-<?php echo esc_attr( $rsbbox_columns ); ?> serviceboxs-col-md-4 serviceboxs-col-sm-2 serviceboxs-col-xs-1">
			<div class="tpwp-style2-servicebox-items" <?php if( !empty( $rsbbox_back_color ) ){ ?> style="background-color:<?php echo $rsbbox_back_color;?> !important" <?php } ?>>
				<?php if( $rsbbox_hideicons == 1 ){ ?>
					<div class="tpwp-style2-service-icon" <?php if( !empty( $rsbbox_iconbg_color ) ){ ?> style="background-color:<?php echo $rsbbox_iconbg_color;?>" <?php } ?>>
						<i <?php if( !empty( $rsbbox_icon_color ) ){ ?> style="color:<?php echo $rsbbox_icon_color;?> !important" <?php } ?> class="fa <?php echo $rsbboxicons; ?>"></i>
					</div>
				<?php } ?>
				<div class="tpwp-style2-service-content">
					<?php if( $rsbbox_hidetitle == 1 ){ ?>
						<?php if( !empty( $shortdetails ) ){ ?>
							<h3 <?php if( !empty( $rsbbox_title_color ) ){ ?> style="color:<?php echo $rsbbox_title_color;?> !important" <?php } ?>>
								<?php if( $rsbbox_hidelinks == 1 ){ ?>
									<a <?php if( !empty( $rsbbox_title_color ) ){ ?> style="color:<?php echo $rsbbox_title_color;?> !important" <?php } ?> href="<?php echo esc_url( $rsbboxurl ); ?>"><?php the_title(); ?></a>
								<?php } else{ ?> 
									<?php the_title(); ?>
								<?php } ?>
							</h3>
						<?php } ?>
					<?php } ?>
					<?php if( !empty( $shortdetails ) ){ ?>
						<p <?php if( !empty( $rsbbox_content_color ) ){ ?> style="color:<?php echo $rsbbox_content_color;?> !important" <?php } ?>>
							<?php echo $shortdetails;?>
						</p>
					<?php } ?>
					<?php if( $rsbbox_hidereadmore == 1 ){ ?>
						<?php if( !empty( $rsbbox_button_text ) ){ ?>
							<a href="<?php echo esc_url($rsbboxurl);?>" class="tpwp-style2-readmore" <?php if( !empty( $rsbbox_moresize_color ) ){ ?> style="color:<?php echo $rsbbox_moresize_color;?> !important" <?php } ?>><?php _e( $rsbbox_button_text );?></a>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
    	<?php endwhile; wp_reset_postdata(); ?>
	</div>