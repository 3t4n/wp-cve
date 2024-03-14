<?php

	$cfa = 'contact-form-add';
	$cfa_install_link = '<a href="' . esc_url( network_admin_url('plugin-install.php?tab=plugin-information&plugin=' . $cfa . '&TB_iframe=true&width=600&height=550' ) ) . '" class="thickbox" title="More info about ' . $cfa . '">';

	$slider = 'slider-slideshow';
	$slider_install_link = '<a href="' . esc_url( network_admin_url('plugin-install.php?tab=plugin-information&plugin=' . $slider . '&TB_iframe=true&width=600&height=550' ) ) . '" class="thickbox" title="More info about ' . $slider . '">';

	$tss = 'testimonial-add';
	$tss_install_link = '<a href="' . esc_url( network_admin_url('plugin-install.php?tab=plugin-information&plugin=' . $tss . '&TB_iframe=true&width=600&height=550' ) ) . '" class="thickbox" title="More info about ' . $tss . '">';

	$sf = 'add-facebook';
	$sf_install_link = '<a href="' . esc_url( network_admin_url('plugin-install.php?tab=plugin-information&plugin=' . $sf . '&TB_iframe=true&width=600&height=550' ) ) . '" class="thickbox" title="More info about ' . $sf . '">';

	$msf = 'mailchimp-subscribe-sm';
	$msf_install_link = '<a href="' . esc_url( network_admin_url('plugin-install.php?tab=plugin-information&plugin=' . $msf . '&TB_iframe=true&width=600&height=550' ) ) . '" class="thickbox" title="More info about ' . $msf . '">';

	$ulp = 'ultimate-landing-page';
	$ulp_install_link = '<a href="' . esc_url( network_admin_url('plugin-install.php?tab=plugin-information&plugin=' . $ulp . '&TB_iframe=true&width=600&height=550' ) ) . '" class="thickbox" title="More info about ' . $ulp . '">';

	?>
<link rel="stylesheet" href="http://www.w3schools.com/lib/w3.css">

<style type="text/css">
	body{
		background: #F1F1F1;
	}
		.gr-item{
			width: 450px;
			background:#fff;
			margin:10px;
			display: inline-block;
			float: left;
		}
		.gr_header{
			border-top: 1px solid #ccc;
			padding: 20px 5px 20px 5px; 
			font-size: 20px;	
			background: rgba(255,255,255,.65);		
		}
		.gr_img{
			width: 100%;
			height: 350px;
			margin: 0 auto;
			margin-bottom: -2px;
		}
		.gr_btn{
			margin: 0 auto;
			margin-bottom: 20px;
		}
		.gr_sp{
			margin-top: 11px;
			font-size: 11px;
			text-align: left;
			float: left;
			margin-left: 8px;
		}
		.gr_sr{
			margin-top: 5px;
			font-size: 13px;
			text-align: right;
			float: right;
			margin-right: 3px;
			padding: 4px 10px 4px 10px;
			background: #3d3d3d;
			color: #fff;
			cursor: pointer;
		}
		.gr_sr:hover{
			 background: #5e5e5e;
		}
		.gr_sr > a {
			text-decoration:none;
			color: #fff;
		}
		.gr_heading{
			text-align: center !important;
		}
		#gr_wrapper{
			margin:2%;
		}
	</style>
<div id="gr_wrapper" class="w3-row">
	<div class="w3-card gr-item">
  		<?php echo $cfa_install_link; ?> 
  			<img class="w3-center gr_img" src="<?php echo WSTT_PLUGIN_URL.'/imgs/contact-form-icon.png'; ?>">
  		</a>
		<header class="w3-container w3-center gr_header" >
			<span class="gr_heading"> Form Builder (Free) </span>
			<span class="gr_sr"> <?php echo $cfa_install_link; ?> Install Now </a></span>
		</header>
	</div>

	<div class="w3-card gr-item">
  		<?php echo $slider_install_link; ?>
  			<img class="w3-center gr_img" src="<?php echo WSTT_PLUGIN_URL.'/imgs/slider.png'; ?>">
  		</a>
		<header class="w3-container w3-center gr_header" >
			<span class="gr_heading"> Slider Slideshow (Free) </span>
			<span class="gr_sr"> <?php echo $slider_install_link; ?> Install Now </a></span>
		</header>
	</div>

	<div class="w3-card gr-item">
  		<?php echo $tss_install_link; ?>
  			<img class="w3-center gr_img" src="<?php echo WSTT_PLUGIN_URL.'/imgs/testimonial.png'; ?>">
  		</a>
		<header class="w3-container w3-center gr_header" >
			<span class="gr_heading"> Testimonial Slider (Free) </span>
			<span class="gr_sr"> <?php echo $tss_install_link; ?> Install Now </a></span>
		</header>
	</div>
	<br>
</div>
<div id="gr_wrapper" class="w3-row">
	<div class="w3-card gr-item">
  		<?php echo $sf_install_link; ?>
  			<img class="w3-center gr_img" src="<?php echo WSTT_PLUGIN_URL.'/imgs/social-feed.png'; ?>">
  		</a>
		<header class="w3-container w3-center gr_header" >
			<span class="gr_heading"> Social Feed </span>
			<span class="gr_sr"> <?php echo $sf_install_link; ?> Install Now </a></span>
		</header>
	</div>

	<div class="w3-card gr-item">
  		<?php echo $msf_install_link; ?>
  			<img class="w3-center gr_img" src="<?php echo WSTT_PLUGIN_URL.'/imgs/subscribe-form.png'; ?>">
  		</a>
		<header class="w3-container w3-center gr_header" >
			<span class="gr_heading"> Subscribe Form </span>
			<span class="gr_sr"> <?php echo $msf_install_link; ?> Install Now </a></span>
		</header>
	</div>

	<div class="w3-card gr-item">
  		<?php echo $ulp_install_link; ?>
  			<img class="w3-center gr_img" src="<?php echo WSTT_PLUGIN_URL.'/imgs/ulp.png'; ?>">
  		</a>
		<header class="w3-container w3-center gr_header" >
			<span class="gr_heading"> Ultimate Landing Page </span>
			<span class="gr_sr"> <?php echo $ulp_install_link; ?> Install Now </a></span>
		</header>
	</div>
</div>	
