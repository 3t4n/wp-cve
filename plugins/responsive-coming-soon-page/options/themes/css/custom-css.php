<style>
.background_color {
	background-color : <?php echo esc_attr($wl_rcsm_options['bg_color']); ?>;
	margin: 0;
	background-size: 100% 100%;
	background-attachment: fixed;
	height:725px;
	overflow:hidden;
}

.error_email,.subscribe-message {
	text-align:center;	
}

.custom_background-image {
	background-image: url("<?php echo esc_url($wl_rcsm_options['custom_bg_img']); ?>");
	margin: 0;
	background-size: 100% 100%; 
	background-attachment: fixed;
	height:725px;
	overflow:hidden;
}

/* CSS to change the color of title and text */
.custom_bg_title_color {
	color: <?php echo esc_url($wl_rcsm_options['custom_bg_title_color']); ?>;
}
.custom_bg_desc_color {
	color: <?php echo esc_url($wl_rcsm_options['custom_bg_desc_color']); ?>;
}

.left_side_link {
    position: fixed;
    bottom: 15px;
    right: 5px;
    font-size: 15px !important;
    z-index: 9999;
	border: 1px solid #000;
}

.blockhide {
	display:none;
}

header .social-icons .icon {
	color:<?php echo esc_attr($wl_rcsm_options['theme_color_schemes']); ?> !important;
}

.count {
	background-color:<?php echo esc_attr($wl_rcsm_options['theme_color_schemes']); ?> !important;
}

.newsletter .btn {
	background-color:<?php echo esc_attr($wl_rcsm_options['theme_color_schemes']); ?> !important;
}

.touch {
	background-color:<?php echo esc_attr($wl_rcsm_options['theme_color_schemes']); ?> !important;
}

.address {
	background-color:<?php echo esc_attr($wl_rcsm_options['theme_color_schemes']); ?> !important;
}

.copyright a {
	color: <?php echo esc_attr($wl_rcsm_options['theme_color_schemes']); ?> !important;
}

.subscriber_submit {
	background-color: <?php echo esc_attr($wl_rcsm_options['theme_color_schemes']); ?> !important;
	color: #fff;
}

.subscriber_submit:hover {
	background-color: <?php echo esc_attr($wl_rcsm_options['theme_color_schemes']); ?> !important;
	color: #fff;
}

a.left_side_link, a.button_link {
	color: <?php echo esc_attr($wl_rcsm_options['theme_color_schemes']); ?> !important;
	border-color:<?php echo esc_attr($wl_rcsm_options['theme_color_schemes']); ?> !important;
	background-color:#fff!important;
	font-weight:bold;
}

a.button_link {
	color: <?php echo esc_attr($wl_rcsm_options['theme_color_schemes']); ?> !important;
}

.subscribe-message, #error_email2{
    text-align: center;
    padding: 5px 0px;
    color: #ffffff;    
    margin: 0px auto;
	font-weight: bold;
}


.carousel-caption h1{
	font-family: <?php echo esc_attr($wl_rcsm_options['theme_font_family']); ?> !important;
}
.carousel-caption h4{
	font-family: <?php echo esc_attr($wl_rcsm_options['theme_font_family']); ?> !important;
}
.carousel-caption h3{
	font-family: <?php echo esc_attr($wl_rcsm_options['theme_font_family']); ?> !important;
}
.carousel-caption .btn{
	font-family: <?php echo esc_attr($wl_rcsm_options['theme_font_family']); ?> !important;
}
.maintance-detail h2{
	font-family: <?php echo esc_attr($wl_rcsm_options['theme_font_family']); ?> !important;
}
.maintance-detail p{
	font-family: <?php echo esc_attr($wl_rcsm_options['theme_font_family']); ?> !important;
}
.rotate .text{
	font-family: <?php echo esc_attr($wl_rcsm_options['theme_font_family']); ?> !important;
}
.countDown .days, .countDown .hours, .countDown .minutes, .countDown .seconds{
	font-family: <?php echo esc_attr($wl_rcsm_options['theme_font_family']); ?> !important;
}
.form-group{
	font-family: <?php echo esc_attr($wl_rcsm_options['theme_font_family']); ?> !important;
}
.newsletter h2{
	font-family: <?php echo esc_attr($wl_rcsm_options['theme_font_family']); ?> !important;
}
.newsletter h4{
	font-family: <?php echo esc_attr($wl_rcsm_options['theme_font_family']); ?> !important;
}
.desc{
	font-family: <?php echo esc_attr($wl_rcsm_options['theme_font_family']); ?> !important;
}
.newsletter .btn{
	font-family: <?php echo esc_attr($wl_rcsm_options['theme_font_family']); ?> !important;
}
.copyright{
	font-family: <?php echo esc_attr($wl_rcsm_options['theme_font_family']); ?> !important;
}
#newsletter{
	<?php
	if($wl_rcsm_options['select_bg_subs']=='sub_bg_img'){
		?>
		background-image: url("<?php echo esc_url($wl_rcsm_options['custom_sub_bg_img']); ?>");
		<?php
	}else{
		?>
		background-color: <?php echo esc_attr($wl_rcsm_options['sub_bg_color']); ?> !important;
		/* #333 !important;	 */
		<?php
	}
	?>
	margin: 0;
	background-size: 100% 100%;
	background-attachment: fixed;
	height: 725px;
	overflow: hidden;
}
@media( min-width:768px){
	.carousel-caption {
	    width: 30%;
		margin: 40px auto;
		text-align: center;
	}
	.form_align{
		<?php
			if($wl_rcsm_options['site_logo_alignment']=='left'){
				?>
					left: 2%;
				    right: auto;
	    		<?php	

			}else if($wl_rcsm_options['site_logo_alignment']=='right'){
				?>
				    right: 2%;
			        left: auto;
				<?php
			}
		?>
	}
}
</style>