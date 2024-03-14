<?php
if( !defined( 'ABSPATH' ) ){
	exit;
}

if($pkslogo_types == 1){ ?>
	<style>
		.picklogo-showcase-<?php echo $postid; ?> {
		    display: flex;
		    flex-wrap: wrap;
		}
		<?php if($pkslogo_heights == 1){?>
			.picklogo-showcase-<?php echo $postid; ?> .pksl_single_logo_items {
			    background: <?php echo $pklslogo_bag_color;?>;
			    border:<?php echo $pklslogo_bordersize;?>px <?php echo $pkslogo_borderstyles;?> <?php echo $pklslogo_borderclr;?>;
			    width: 100%;
			    height: 100%;
			    margin: 5px;
			    height: auto;
			}
		<?php
		}elseif ($pkslogo_heights == 2) {?>
			.picklogo-showcase-<?php echo $postid; ?> .pksl_single_logo_items {
			    background: <?php echo $pklslogo_bag_color;?>;
			    border:<?php echo $pklslogo_bordersize;?>px <?php echo $pkslogo_borderstyles;?> <?php echo $pklslogo_borderclr;?>;
			    width: 100%;
			    height: 100%;
			    margin: 5px;
			    height: <?php echo $pkslogo_custom;?>px;
		}
		<?php } ?>
		.picklogo-showcase-<?php echo $postid; ?> .pksl_single_logo_thumbnail img {
			max-width: 100%;
		}
		.picklogo-showcase-<?php echo $postid; ?> .pksl_single_logo_thumbnail a{
			display:inline-block;
		}
		<?php if($pkslogo_imggray == 1){?>
			.picklogo-showcase-<?php echo $postid; ?> .pksl_single_logo_thumbnail img {
				filter:grayscale(0);
				transition: 0.3s;
			}
		<?php }elseif ($pkslogo_imggray == 2) { ?>
			.picklogo-showcase-<?php echo $postid; ?> .pksl_single_logo_thumbnail img {
				filter:grayscale(100);
				transition: 0.3s;
			}
			.picklogo-showcase-<?php echo $postid; ?> .pksl_single_logo_thumbnail img:hover {
				filter:grayscale(0);
				transition: 0.3s;
			}
		<?php } ?>
		.picklogo-showcase-<?php echo $postid; ?> .pksl_single_logo_items:hover {
		    border:<?php echo $pklslogo_bordersize;?>px <?php echo $pkslogo_borderstyles;?> <?php echo $pklslogo_border_hvrclr;?>;
		}
		.picklogo-showcase-<?php echo $postid; ?> .pksl_single_logo_thumbnail {
		    text-align: center;
		    padding: <?php echo $pkls_logo_padding_size;?>px;
		    position: relative;
		    overflow: hidden;
		}
		.picklogo-showcase-<?php echo $postid; ?> .pksl_single_logo_thumbnail img {
		    overflow: hidden;
		    left: 0;
		    right: 0;
		    margin: 0 auto;
		    text-align: center;
		}
		.picklogo-showcase-<?php echo $postid; ?> .pksl_logo_info {}
		.picklogo-showcase-<?php echo $postid; ?> .pksl_single_logo_title {
		    margin: 0px 0px 10px;
		    font-size: <?php echo $pkls_logotitle_font_size;?>px;
		    text-transform: <?php echo $pkls_logotitle_transfrom;?>;
		    font-style: <?php echo $pkls_logotitle_fontstyle;?>;
		    text-align: center;
		    color: <?php echo $pklslogo_title_color;?>
		}
		.picklogo-showcase-<?php echo $postid; ?> .pksl_single_logo_description {
		    font-size: <?php echo $pkls_logocontent_size;?>px;
		    text-transform: <?php echo $pkls_logocontent_transfrom;?>;
		    font-style: <?php echo $pkls_logocontent_fontstyle;?>;
		    text-align: center;
		    color: <?php echo $pklslogo_content_color;?>;
		    margin: 0px 15px 15px;
		}
		.picklogo-showcase-<?php echo $postid; ?> .slick-prev:before {
			color: <?php echo $pklslogo_arrowcolor;?>;
		}
		.picklogo-showcase-<?php echo $postid; ?> .slick-next:before {
			color: <?php echo $pklslogo_arrowcolor;?>;
		}
		.picklogo-showcase-<?php echo $postid; ?> button.slick-prev.pickbtn.slick-arrow,
		.picklogo-showcase-<?php echo $postid; ?> button.slick-next.pickbtn.slick-arrow {
			position: absolute;
		    top: 50%;
		    transform: translateY(-50%);
		    line-height: inherit;
		    border-radius: 50px;
		    display: inline-block;
		    background: #ddd;
		    text-align: center;
		    color: <?php echo $pklslogo_arrowcolor;?>;
		    border:none;
		    transition: all 0.5s;
		    z-index: 1;
		    box-shadow: none;
		    outline: none;
		    text-decoration: none;
		    padding: 0;
		    margin-top:-15px;
		}
		.picklogo-showcase-<?php echo $postid; ?> button.slick-prev.pickbtn.slick-arrow i,
		.picklogo-showcase-<?php echo $postid; ?> button.slick-next.pickbtn.slick-arrow i {
			font-size: 25px;
			line-height: 35px;
			display: inline-block;
		    width: 35px;
		    height: 35px;
		}
		.picklogo-showcase-<?php echo $postid; ?> button.slick-prev{
		    left: -14px;
		}
		.picklogo-showcase-<?php echo $postid; ?> button.slick-next{
		    right: -14px;
		}
		.picklogo-showcase-<?php echo $postid; ?> button.slick-prev.pickbtn.slick-arrow:hover,
		.picklogo-showcase-<?php echo $postid; ?> button.slick-next.pickbtn.slick-arrow:hover {
			background: #ddd;
			color: <?php echo $pklslogo_arrowcolor;?>;
		}
		.picklogo-showcase-<?php echo $postid; ?> ul.slick-dots {
		    margin: 0;
		    padding: 0;
		    list-style: none;
		    text-align: center;
		    display: block;
		    overflow: hidden;
		    width: 100%;
		}
		.picklogo-showcase-<?php echo $postid; ?> ul.slick-dots li {
		    position: relative;
		    display: inline-table;
		}
		.picklogo-showcase-<?php echo $postid; ?> ul.slick-dots li button {
		    font-size: 0;
		    line-height: 0;
		    display: block;
		    width: 10px;
		    height: 10px;
		    cursor: pointer;
		    color: transparent;
		    border: 0;
		    outline: 0;
		    background: <?php echo $pklslogo_dotcolor;?>;
		    margin: 3px;
		    padding: 0;
		    border-radius: 50px;
		}
		.picklogo-showcase-<?php echo $postid; ?> ul.slick-dots li.slick-active button{
			background: <?php echo $pklslogo_dotactcolor;?>;
		}

.tooltipster-sidetip.tooltipster-light .tooltipster-box{border-radius:3px;border:1px solid <?php echo $pkls_logotooltipclr;?>;background:<?php echo $pkls_logotooltipclr;?>}.tooltipster-sidetip.tooltipster-light .tooltipster-content{color:<?php echo $pkls_logotooltiptclr;?>}.tooltipster-sidetip.tooltipster-light .tooltipster-arrow{height:9px;margin-left:-9px;width:18px}.tooltipster-sidetip.tooltipster-light.tooltipster-left .tooltipster-arrow,.tooltipster-sidetip.tooltipster-light.tooltipster-right .tooltipster-arrow{height:18px;margin-left:0;margin-top:-9px;width:9px}.tooltipster-sidetip.tooltipster-light .tooltipster-arrow-background{border:9px solid transparent}.tooltipster-sidetip.tooltipster-light.tooltipster-bottom .tooltipster-arrow-background{border-bottom-color:<?php echo $pkls_logotooltipclr;?>;top:1px}.tooltipster-sidetip.tooltipster-light.tooltipster-left .tooltipster-arrow-background{border-left-color:<?php echo $pkls_logotooltipclr;?>;left:-1px}.tooltipster-sidetip.tooltipster-light.tooltipster-right .tooltipster-arrow-background{border-right-color:<?php echo $pkls_logotooltipclr;?>;left:1px}.tooltipster-sidetip.tooltipster-light.tooltipster-top .tooltipster-arrow-background{border-top-color:<?php echo $pkls_logotooltipclr;?>;top:-1px}.tooltipster-sidetip.tooltipster-light .tooltipster-arrow-border{border:9px solid transparent}.tooltipster-sidetip.tooltipster-light.tooltipster-bottom .tooltipster-arrow-border{border-bottom-color:<?php echo $pkls_logotooltipclr;?>}.tooltipster-sidetip.tooltipster-light.tooltipster-left .tooltipster-arrow-border{border-left-color:<?php echo $pkls_logotooltipclr;?>}.tooltipster-sidetip.tooltipster-light.tooltipster-right .tooltipster-arrow-border{border-right-color:<?php echo $pkls_logotooltipclr;?>}.tooltipster-sidetip.tooltipster-light.tooltipster-top .tooltipster-arrow-border{border-top-color:<?php echo $pkls_logotooltipclr;?>}.tooltipster-sidetip.tooltipster-light.tooltipster-bottom .tooltipster-arrow-uncropped{top:-9px}.tooltipster-sidetip.tooltipster-light.tooltipster-right .tooltipster-arrow-uncropped{left:-9px}
	</style>

	<script>
	jQuery(document).ready(function($){
	    $('.picklogo-showcase-<?php echo $postid; ?>').slick({
			infinite: true,
	        adaptiveHeight: false,
	        dots: <?php echo $pkslogo_dotsoptions; ?>,
	        pauseOnHover: <?php echo $pkslogo_pausehover; ?>,
	        slidesToShow: <?php echo $pkslogo_displayitems;?>,
	        arrows: <?php echo $pkslogo_arrowoptions; ?>,
	        prevArrow: '<button type="button" class="slick-prev pickbtn"><i class="fa fa-angle-left"></i></button>',
			nextArrow: '<button type="button" class="slick-next pickbtn"><i class="fa fa-angle-right"></i></button>',
	        rows: 0,
	        speed: 600,
	        rtl: false,
			autoplaySpeed:<?php echo $pkslogo_autoplayspeed; ?>,
			autoplay: <?php echo $pkslogo_autoplayoptions; ?>,
			slidesToScroll: 1,
			swipe: <?php echo $pkslogo_swipeoptions;?>,
			draggable: <?php echo $pkslogo_dragsoptions;?>,
			// cssEase: 'linear',
				responsive: [
			    {
			      breakpoint: 1000,
			      settings: {
			        slidesToShow: <?php echo $pkslogo_displayitems;?>
			      }
			    },
			    {
			      breakpoint: 900,
			      settings: {
			        slidesToShow: <?php echo $pkslogo_displayitems;?>
			      }
			    },
			    {
			      breakpoint: 600,
			      settings: {
			        slidesToShow: <?php echo $pkslogo_mediumitems;?>
			      }
			    },
			    {
			      breakpoint: 460,
			      settings: {
			        slidesToShow: <?php echo $pkslogo_smallitems;?>
			      }
			    }]
	    });
	    $('.tooltip-<?php echo $postid; ?>').tooltipster({
	    	theme: 'tooltipster-light'
	    });
	});
	</script>

	<div class="picklogo-showcase-<?php echo $postid; ?> slider">
		<?php
		$neptune = 700;
		for($i=0; $i<= count($image_name)-1; $i++){ ?>
			<?php if (!empty($bend_single_logo_name[$i])){ ?>
				<?php if($pkls_logotooltip == 1){ ?>
					<div class="pksl_single_logo_items tooltip-<?php echo $postid; ?>" title="<?php echo esc_attr($bend_single_logo_name[$i]); ?>">
				<?php } elseif($pkls_logotooltip == 2){ ?>
					<div class="pksl_single_logo_items">
				<?php } ?>
			<?php }else{ ?>
				<div class="pksl_single_logo_items">
			<?php } ?>
			<?php if (!empty($bend_single_logo_url[$i])){ ?>
				<div class="pksl_single_logo_thumbnail">
					<a href="<?php echo esc_url($bend_single_logo_url[$i]); ?>"><img src="<?php echo $image_name[$i]; ?>" ></a>
				</div>
			<?php }else{ ?>
				<div class="pksl_single_logo_thumbnail">
					<img src="<?php echo $image_name[$i]; ?>" >
				</div>
			<?php } ?>
			<?php if (!empty($bend_single_logo_name[$i])){ ?>
				<div class="pksl_logo_info">
					<?php if($pkslogo_title_hide == 1){ ?>
						<div class="pksl_single_logo_title"><?php echo esc_attr($bend_single_logo_name[$i]); ?></div>
					<?php } ?>
				</div>
			<?php } ?>
			</div>
		<?php
		$neptune++; } ?>
	</div>
<?php
}elseif($pkslogo_types == 2){?>
	<style>
		.pks-logo-container-<?php echo $postid; ?> {
		    display: flex;
		    flex-wrap: wrap;
		}
		.pks-logo-container-<?php echo $postid; ?> .pksl_single_logo_items {
		    background: <?php echo $pklslogo_bag_color;?>;
		    border:<?php echo $pklslogo_bordersize;?>px <?php echo $pkslogo_borderstyles;?> <?php echo $pklslogo_borderclr;?>;
		    width: 100%;
		    height: 100%;
		}
		.pks-logo-container-<?php echo $postid; ?> .pksl_single_logo_items:hover {
		    border:<?php echo $pklslogo_bordersize;?>px <?php echo $pkslogo_borderstyles;?> <?php echo $pklslogo_border_hvrclr;?>;
		}
		.pks-logo-container-<?php echo $postid; ?> .pksl_single_logo_thumbnail {
		    text-align: center;
		    padding: <?php echo $pkls_logo_padding_size;?>px;
		    position: relative;
		    overflow: hidden;
		}
		.pks-logo-container-<?php echo $postid; ?> .pksl_single_logo_thumbnail img {
		    overflow: hidden;
		    left: 0;
		    right: 0;
		    margin: 0 auto;
		    text-align: center;
		}
		.pks-logo-container-<?php echo $postid; ?> .pksl_single_logo_thumbnail img {
			max-width: 100%;
		}
		.pks-logo-container-<?php echo $postid; ?> .pksl_single_logo_thumbnail a{
			display:inline-block;
		}		
		<?php if($pkslogo_imggray == 1){ ?>
			.pks-logo-container-<?php echo $postid; ?> .pksl_single_logo_thumbnail img {
				filter:grayscale(0);
				transition: 0.3s;
			}
		<?php } elseif ($pkslogo_imggray == 2) { ?>
			.pks-logo-container-<?php echo $postid; ?> .pksl_single_logo_thumbnail img {
				filter:grayscale(100);
				transition: 0.3s;
			}
			.pks-logo-container-<?php echo $postid; ?> .pksl_single_logo_thumbnail img:hover {
				filter:grayscale(0);
				transition: 0.3s;
			}
		<?php } ?>
		.pks-logo-container-<?php echo $postid; ?> .pksl_logo_info {}
		.pks-logo-container-<?php echo $postid; ?> .pksl_single_logo_title {
		    margin: 0px 0px 10px;
		    font-size: <?php echo $pkls_logotitle_font_size;?>px;
		    text-transform: <?php echo $pkls_logotitle_transfrom;?>;
		    font-style: <?php echo $pkls_logotitle_fontstyle;?>;
		    text-align: center;
		    color: <?php echo $pklslogo_title_color;?>
		}
		.pks-logo-container-<?php echo $postid; ?> .pksl_single_logo_description {
		    font-size: <?php echo $pkls_logocontent_size;?>px;
		    text-transform: <?php echo $pkls_logocontent_transfrom;?>;
		    font-style: <?php echo $pkls_logocontent_fontstyle;?>;
		    text-align: center;
		    color: <?php echo $pklslogo_content_color;?>;
		    margin: 0px 15px 15px;
		}
		.pks-logo-container-<?php echo $postid; ?> .pksl-col-lg-1,
		.pks-logo-container-<?php echo $postid; ?> .pksl-col-lg-2,
		.pks-logo-container-<?php echo $postid; ?> .pksl-col-lg-3,
		.pks-logo-container-<?php echo $postid; ?> .pksl-col-lg-4,
		.pks-logo-container-<?php echo $postid; ?> .pksl-col-lg-5,
		.pks-logo-container-<?php echo $postid; ?> .pksl-col-lg-6,
		.pks-logo-container-<?php echo $postid; ?> .pksl-col-md-1,
		.pks-logo-container-<?php echo $postid; ?> .pksl-col-md-2,
		.pks-logo-container-<?php echo $postid; ?> .pksl-col-md-3,
		.pks-logo-container-<?php echo $postid; ?> .pksl-col-md-4,
		.pks-logo-container-<?php echo $postid; ?> .pksl-col-md-5,
		.pks-logo-container-<?php echo $postid; ?> .pksl-col-md-6,
		.pks-logo-container-<?php echo $postid; ?> .pksl-col-sm-1,
		.pks-logo-container-<?php echo $postid; ?> .pksl-col-sm-2,
		.pks-logo-container-<?php echo $postid; ?> .pksl-col-sm-3,
		.pks-logo-container-<?php echo $postid; ?> .pksl-col-sm-4,
		.pks-logo-container-<?php echo $postid; ?> .pksl-col-sm-5,
		.pks-logo-container-<?php echo $postid; ?> .pksl-col-sm-6,
		.pks-logo-container-<?php echo $postid; ?> .pksl-col-xs-1,
		.pks-logo-container-<?php echo $postid; ?> .pksl-col-xs-2,
		.pks-logo-container-<?php echo $postid; ?> .pksl-col-xs-3,
		.pks-logo-container-<?php echo $postid; ?> .pksl-col-xs-4,
		.pks-logo-container-<?php echo $postid; ?> .pksl-col-xs-5,
		.pks-logo-container-<?php echo $postid; ?> .pksl-col-xs-6 {
			float: left;
			margin-bottom: 10px !important;
			min-height: 1px;
			padding-left: 5px !important;
			padding-right: 5px !important;
			position: relative;
		}
.tooltipster-sidetip.tooltipster-light .tooltipster-box{border-radius:3px;border:1px solid <?php echo $pkls_logotooltipclr;?>;background:<?php echo $pkls_logotooltipclr;?>}.tooltipster-sidetip.tooltipster-light .tooltipster-content{color:<?php echo $pkls_logotooltiptclr;?>}.tooltipster-sidetip.tooltipster-light .tooltipster-arrow{height:9px;margin-left:-9px;width:18px}.tooltipster-sidetip.tooltipster-light.tooltipster-left .tooltipster-arrow,.tooltipster-sidetip.tooltipster-light.tooltipster-right .tooltipster-arrow{height:18px;margin-left:0;margin-top:-9px;width:9px}.tooltipster-sidetip.tooltipster-light .tooltipster-arrow-background{border:9px solid transparent}.tooltipster-sidetip.tooltipster-light.tooltipster-bottom .tooltipster-arrow-background{border-bottom-color:<?php echo $pkls_logotooltipclr;?>;top:1px}.tooltipster-sidetip.tooltipster-light.tooltipster-left .tooltipster-arrow-background{border-left-color:<?php echo $pkls_logotooltipclr;?>;left:-1px}.tooltipster-sidetip.tooltipster-light.tooltipster-right .tooltipster-arrow-background{border-right-color:<?php echo $pkls_logotooltipclr;?>;left:1px}.tooltipster-sidetip.tooltipster-light.tooltipster-top .tooltipster-arrow-background{border-top-color:<?php echo $pkls_logotooltipclr;?>;top:-1px}.tooltipster-sidetip.tooltipster-light .tooltipster-arrow-border{border:9px solid transparent}.tooltipster-sidetip.tooltipster-light.tooltipster-bottom .tooltipster-arrow-border{border-bottom-color:<?php echo $pkls_logotooltipclr;?>}.tooltipster-sidetip.tooltipster-light.tooltipster-left .tooltipster-arrow-border{border-left-color:<?php echo $pkls_logotooltipclr;?>}.tooltipster-sidetip.tooltipster-light.tooltipster-right .tooltipster-arrow-border{border-right-color:<?php echo $pkls_logotooltipclr;?>}.tooltipster-sidetip.tooltipster-light.tooltipster-top .tooltipster-arrow-border{border-top-color:<?php echo $pkls_logotooltipclr;?>}.tooltipster-sidetip.tooltipster-light.tooltipster-bottom .tooltipster-arrow-uncropped{top:-9px}.tooltipster-sidetip.tooltipster-light.tooltipster-right .tooltipster-arrow-uncropped{left:-9px}
	</style>
	<script>
		jQuery(document).ready(function($){
		    $('.tooltip-<?php echo $postid; ?>').tooltipster({
		    	theme: 'tooltipster-light'
		    });
		});
	</script>

	<div class="pks-logo-container-<?php echo $postid; ?>">
		<?php
		$neptune = 700;
		for($i=0; $i<= count($image_name)-1; $i++){ ?>
			<div class="pksl-col-lg-<?php echo $pkslogo_columns;?> pksl-col-md-2 pksl-col-sm-2 pksl-col-xs-1">
				<?php if (!empty($bend_single_logo_name[$i])){ ?>
					<?php if($pkls_logotooltip == 1){ ?>
						<div class="pksl_single_logo_items tooltip-<?php echo $postid; ?>" title="<?php echo esc_attr($bend_single_logo_name[$i]); ?>">
					<?php } elseif($pkls_logotooltip == 2){ ?>
						<div class="pksl_single_logo_items">
					<?php } ?>
				<?php }else{ ?>
					<div class="pksl_single_logo_items">
				<?php } ?>
				<?php if (!empty($bend_single_logo_url[$i])){ ?>
					<div class="pksl_single_logo_thumbnail">
						<a href="<?php echo esc_url($bend_single_logo_url[$i]); ?>"><img src="<?php echo $image_name[$i]; ?>" ></a>
					</div>
				<?php }else{ ?>
					<div class="pksl_single_logo_thumbnail">
						<img src="<?php echo $image_name[$i]; ?>" >
					</div>
				<?php } ?>
				<?php if (!empty($bend_single_logo_name[$i])){ ?>
					<div class="pksl_logo_info">
						<?php if($pkslogo_title_hide == 1){ ?>
							<div class="pksl_single_logo_title"><?php echo esc_attr($bend_single_logo_name[$i]); ?></div>
						<?php } ?>
					</div>
				<?php } ?>
				</div>
			</div>
		<?php
		$neptune++; } ?>
	</div>
<?php
}