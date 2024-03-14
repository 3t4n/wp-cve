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
	.servicearea.id-<?php echo esc_attr( $post_id ); ?> .tpwp-style6-servicebox-items{
	    padding:<?php echo $rsbbox_padding_size; ?>px;
	    text-align:<?php echo $rsbbox_alignment; ?>;
	    background:<?php echo $rsbbox_itembg_color; ?>;
	}
	<?php if( $rsbbox_alignment == 'left'){ ?> 
		.servicearea.id-<?php echo esc_attr( $post_id ); ?> .tpwp-style6-service-header{
			display:flex;
			align-items: center;
		}
	<?php }elseif( $rsbbox_alignment == 'right'){ ?>
		.servicearea.id-<?php echo esc_attr( $post_id ); ?> .tpwp-style6-service-header{
			display:inline-flex;
			align-items: center;
		}
	<?php }else{ ?> 
		.servicearea.id-<?php echo esc_attr( $post_id ); ?> .tpwp-style6-service-header{
			display:inline-flex;
			align-items: center;
		}
	<?php } ?>
	.servicearea.id-<?php echo esc_attr( $post_id ); ?> .tpwp-style6-service-header h3,
	.servicearea.id-<?php echo esc_attr( $post_id ); ?> .tpwp-style6-service-header h3 a{
		color:<?php echo $rsbbox_itemtitle_color; ?>;
		font-size: <?php echo $rsbbox_titlesize; ?>px;
		font-weight: 600;
		margin: 0;
	    margin-bottom: 10px;
	}
	.servicearea.id-<?php echo esc_attr( $post_id ); ?> .tpwp-style6-service-header h3 a:hover{
		color:<?php echo $rsbbox_itemtitleh_color; ?>;
	}
	.servicearea.id-<?php echo esc_attr( $post_id ); ?> .tpwp-style6-service-icon{
		background:<?php echo esc_attr($rsbbox_itemiconsbg_color);?>;
		text-align: center;
		height: 50px;
		width: 50px;
		line-height: 50px;
		border-radius:50%;
		display: inline-block;
		margin-bottom:15px;
		margin-right:15px;
	}
	.servicearea.id-<?php echo esc_attr( $post_id ); ?> .tpwp-style6-service-icon i{
		color: <?php echo esc_attr($rsbbox_itemicons_color);?>;
		font-size: <?php echo esc_attr($rsbbox_iconsize);?>px;
		line-height: <?php echo esc_attr($rsbbox_iconheight);?>px;
	    font-family: "FontAwesome";
	    font-weight: unset;
	}
	.servicearea.id-<?php echo esc_attr( $post_id ); ?> .tpwp-style6-service-content p {
		color:<?php echo $rsbbox_conten_color; ?>;
		font-size: <?php echo esc_attr($rsbbox_contentsize);?>px;
		margin: 0;
		padding: 0;
	}
	.servicearea.id-<?php echo esc_attr( $post_id ); ?> a.tpwp-style6-readmore {
		color:<?php echo $rsbbox_moreoption_color; ?>;
		font-size: <?php echo esc_attr($rsbbox_moresize);?>px;
		display: inline-block;
		padding-top: 10px;
		outline: medium none;
		text-decoration: none;
	}
	.servicearea.id-<?php echo esc_attr( $post_id ); ?> a.tpwp-style6-readmore:hover {
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
			<div class="tpwp-style6-servicebox-items" <?php if( !empty( $rsbbox_back_color ) ){ ?> style="background-color:<?php echo $rsbbox_back_color;?> !important" <?php } ?>>
				<div class="tpwp-style6-service-header">
					<?php if( $rsbbox_hideicons == 1 ){ ?>
						<div class="tpwp-style6-service-icon" <?php if( !empty( $rsbbox_iconbg_color ) ){ ?> style="background-color:<?php echo $rsbbox_iconbg_color;?>" <?php } ?>>
							<i <?php if( !empty( $rsbbox_icon_color ) ){ ?> style="color:<?php echo $rsbbox_icon_color;?> !important" <?php } ?> class="fa <?php echo $rsbboxicons; ?>"></i>
						</div>
					<?php } ?>
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
				</div>
				<div class="tpwp-style6-service-content">
					<?php if( !empty( $shortdetails ) ){ ?>
						<p <?php if( !empty( $rsbbox_content_color ) ){ ?> style="color:<?php echo $rsbbox_content_color;?> !important" <?php } ?>>
							<?php echo $shortdetails;?>
						</p>
					<?php } ?>
					<?php if( $rsbbox_hidereadmore == 1 ){ ?>
						<?php if( !empty( $rsbbox_button_text ) ){ ?>
							<a href="<?php echo esc_url($rsbboxurl);?>" class="tpwp-style6-readmore" <?php if( !empty( $rsbbox_moresize_color ) ){ ?> style="color:<?php echo $rsbbox_moresize_color;?> !important" <?php } ?>><?php _e( $rsbbox_button_text );?></a>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
    	<?php endwhile; wp_reset_postdata(); ?>
	</div>