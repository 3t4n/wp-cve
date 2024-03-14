<style id="adminz_flatsome_fix" type="text/css">
	/*Custom class*/
	:root{
		--secondary-color:  <?php echo get_theme_mod('color_secondary', Flatsome_Default::COLOR_SECONDARY ); ?>;
		--success-color:  <?php echo get_theme_mod('color_success', Flatsome_Default::COLOR_SUCCESS ); ?>;
		--alert-color:  <?php echo get_theme_mod('color_alert', Flatsome_Default::COLOR_ALERT ); ?>;
	}
	::-moz-selection { /* Code for Firefox */
		color: white;
  		background: var(--primary-color);
	}

	::selection {
		color: white;
  		background: var(--primary-color);
	}
	.primary-color, .primary-color *{
		color: var(--primary-color);
	}
	.primary{
		background-color: var(--primary-color);
	}
	.primary.is-link,
	.primary.is-outline,
	.primary.is-underline {
		color: var(--primary-color);
	}
	.primary.is-outline:hover {
		background-color: var(--primary-color);
		border-color: var(--primary-color);
		color: #fff;
	}
	.primary:focus-visible{
		outline-color: var(--primary-color);
	}
	


	.secondary-color, .secondary-color *{
		color: var(--secondary-color);
	}
	.secondary {
		background-color: var(--secondary-color);
	}
	.secondary.is-link,
	.secondary.is-outline,
	.secondary.is-underline {
		color: var(--secondary-color);
	}
	.secondary.is-outline:hover {
		background-color: var(--secondary-color);
		border-color: var(--secondary-color);
	}
	.secondary:focus-visible {
		outline-color: var(--secondary-color);
	}

	.success-color, .success-color *{
		color: var(--success-color);
	}
	.success {
		background-color: var(--success-color);
	}
	.success.is-link,
	.success.is-outline,
	.success.is-underline {
		color: var(--success-color);
	}
	.success.is-outline:hover {
		background-color: var(--success-color);
		border-color: var(--success-color);
	}
	.success-border {
		border-color: var(--success-color);
	}

	.alert-color, .alert-color *{
		color: var(--alert-color);
	}
	.alert {
		background-color: var(--alert-color);
	}
	.alert.is-link,
	.alert.is-outline,
	.alert.is-underline {
		color: var(--alert-color);
	}
	.alert.is-outline:hover {
		background-color: var(--alert-color);
		border-color: var(--alert-color);
	}



	blockquote, table, table td{
		color:  inherit;
	}
	.row-nopaddingbottom .flickity-slider>.col,
	.row-nopaddingbottom>.col,
	.nopadding,.nopaddingbottom{
		padding-bottom: 0 !important;
	}

	.no-marginbottom, .no-marginbottom h1, .no-marginbottom h2, .no-marginbottom h3, .no-marginbottom h4, .no-marginbottom h5, .no-marginbottom h6{
		margin-bottom: 0px;
	}
	.row .section{
		padding-left: 15px;
		padding-right: 15px;
	}
	.sliderbot{
		position: absolute;
		left:0;
		bottom: 0;
	}
	.bgr-size-auto .section-bg.bg-loaded{	
	    background-size: auto !important;
	}
	.button{
		white-space: nowrap;
	}
	/*contact group*/
	.adminz_ctg svg,
	.adminz_ctg img{
		width: 30px;
		height: 30px;
	}
/*	html:not([ng-app="uxBuilder"]) .slider:not(.flickity-enabled){height:  0px;}*/
	.adminz_button>i,.adminz_button.reveal-icon>i{display: inline-flex;}

	h1 strong, h2 strong, h3 strong, h4 strong, h5 strong, h6 strong {
		font-weight: 900;
	}
	@media (min-width: 768px) {body.page_for_transparent #header {position: absolute; } body.page_for_transparent #header .header-wrapper:not(.stuck) .header-bottom, body.page_for_transparent #header .header-wrapper:not(.stuck) .header-bg-color {background: transparent !important; } body.page_for_transparent.header-shadow .header-wrapper:not(.stuck) {box-shadow: none !important; } }				
	/*fix*/
	
	/*header*/
	<?php if( $color_texts = get_theme_mod('color_texts')): ?>
		.nav>li>a
		{
			color:  <?php echo esc_attr($color_texts); ?>;
		}
	<?php endif; ?>

	<?php 
	$header_height_mobile = get_theme_mod('header_height_mobile', 70);
	if($header_height_mobile){ 
		?>
		@media (max-width: <?php echo '549px'; ?>) {
			body .stuck .header-main{height: <?php echo $header_height_mobile;?>px !important}
			body .stuck #logo img{max-height: <?php echo $header_height_mobile; ?>px !important}
		}
		<?php  
	}
	?>
	.header-block{
		width: unset;
		display: inline-block;
	}

	/*footer */
	.footer-1, .footer-2{
		background-size: 100%;
		background-position: center;
	}
	@media (max-width: 549px){
		.section-title a{
			margin-left: unset !important;
			margin-top:  15px;
			margin-bottom: 15px;
			padding-left:  0px;
		}
	}				
	.absolute-footer:not(.text-center) .footer-primary{
		padding:  7.5px 0;
	}
	.absolute-footer.text-center .footer-primary{
		margin-right: unset;
	}
	@media (max-width:  549px){
		.absolute-footer .container{
			display: flex;
		    flex-direction: column;
		}
	}
	<?php if(!get_theme_mod('footer_left_text') and !get_theme_mod('footer_right_text')): ?>
		.absolute-footer{
			display: none;
		}
	<?php endif; ?>
	/*page elemtn*/
	.row.equalize-box .col-inner{
		height: 100%;
	}
	.page-col .box-text-inner p{
	    font-weight: bold;
/*    	color: var(--primary-color);*/
	}
	.page-col .page-box.box-vertical .box-image .box-image{
		display: block;
    	width: 100% !important;
	}
	.mfp-close{
	    mix-blend-mode: unset;
	}
	.sliderbot .img-inner{
		border-radius: 0;
	}
	.dark .nav-divided>li+li>a:after{
		border-left: 1px solid rgb(255 255 255 / 65%);
	}
	.adminz_navigation_wrapper .sub-menu{
		z-index: 22;
	}
	.page-checkout li.wc_payment_method,
	li.list-style-none{
		list-style: none;
		margin-left: 0px !important;
	}
	.mfp-content .nav.nav-sidebar>li{
		width: calc(100% - 20px );
	}
	.mfp-content .nav.nav-sidebar>li:not(.header-social-icons)>a{
		padding-left: 10px;
	}
	.mfp-content .nav.nav-sidebar>li.html{
		padding-left:  0px;
		padding-right:  0px;
	}
	.mfp-content .nav.nav-sidebar>li.header-contact-wrapper ul li ,
	.mfp-content .nav.nav-sidebar>li.header-contact-wrapper ul li a,
	.mfp-content .nav.nav-sidebar>li.header-newsletter-item a{
		padding-left:  0px;
	}
	.nav-tabs>li>a{background-color: rgb(241 241 241);}
	.portfolio-page-wrapper{
		padding-top: 30px;
	}
	.portfolio-single-page ul li{
		margin-left: 1.3em;
	}
	.dark .icon-box:hover .has-icon-bg .icon .icon-inner{
		background-color: transparent !important;
	}
	<?php 
		$product_gallery_col = $this->get_option_value('adminz_flatsome_woocommerce_product_gallery'); 
		if(!in_array((int)$product_gallery_col,[0,4])){
			?>
			.product-gallery .product-thumbnails .col{
				width:  <?php echo 100/(int)$product_gallery_col; ?>%;
			} 
			<?php 
		} 
	?>
	<?php $mobile_overlay_bg = get_theme_mod('mobile_overlay_bg'); if($mobile_overlay_bg){ ?>
		.main-menu-overlay{
			background: #0b0b0b;
		}
		.main-menu-overlay+ .off-canvas:not(.off-canvas-center) .mfp-content{
			background: <?php echo esc_attr($mobile_overlay_bg); ?>
		}
	<?php } ?> <?php $enable_sidebar_divider = get_theme_mod('blog_layout_divider'); if(!$enable_sidebar_divider){?>
	body.page .col-divided,
	body.single-product .row-divided>.col+.col:not(.large-12){
		border-right: none;
		border-left: none;
	} <?php } ?>


	
	@media (max-width:  549px){
		body.adminz_enable_vertical_blog_post_mobile .row>.col>.col-inner>a>.box-blog-post:not(.ignore_vertical),
		body.adminz_enable_vertical_blog_post_mobile .row>.col>.col-inner>a>.page-box:not(.ignore_vertical){
			display: flex;
		}
		body.adminz_enable_vertical_blog_post_mobile .col:not(.grid-col) .page-box:not(.ignore_vertical) .box-image,
		body.adminz_enable_vertical_blog_post_mobile .col:not(.grid-col) .box-blog-post:not(.ignore_vertical) .box-image{
			width: 25% !important;
			max-width: 25% !important;					
			margin:  15px 0px 15px 0px;
			position: relative !important;
		}
		body.adminz_enable_vertical_blog_post_mobile [class*="row-box-shadow"] .col-inner .box:not(.box-vertical, .box-overlay, .box-shade, .box-none, .ignore_vertical) .box-image img{
		    border-bottom-left-radius: var(--big-radius) !important;
			border-bottom-right-radius: var(--big-radius) !important;
		}
		body.adminz_enable_vertical_blog_post_mobile .col:not(.grid-col) .page-box:not(.ignore_vertical) .box-text,
		body.adminz_enable_vertical_blog_post_mobile .col:not(.grid-col) .box-blog-post:not(.ignore_vertical) .box-text{
			text-align: left !important;
			position: relative !important;
			padding-left: 15px !important;
			padding-right: 15px !important;
			display: flex;
			align-items: center;
		}
		body.adminz_enable_vertical_blog_post_mobile .box-blog-post.box-overlay:not(.ignore_vertical) .box-text, 
		body.adminz_enable_vertical_blog_post_mobile .box-blog-post.box-shade:not(.ignore_vertical) .box-text,
		body.adminz_enable_vertical_blog_post_mobile .page-box.box-overlay:not(.ignore_vertical) .box-text, 
		body.adminz_enable_vertical_blog_post_mobile .page-box.box-shade:not(.ignore_vertical) .box-text{
			padding-top:  0px !important;
			margin-top:  0px !important;
		}	
		body.adminz_enable_vertical_blog_post_mobile .has-shadow .col:not(.grid-col) .post-item .page-box:not(.ignore_vertical) .box-image,
		body.adminz_enable_vertical_blog_post_mobile .has-shadow .col:not(.grid-col) .page-box:not(.ignore_vertical) .box-image,	
		body.adminz_enable_vertical_blog_post_mobile .has-shadow .col:not(.grid-col) .post-item .box-blog-post:not(.ignore_vertical) .box-image,
		body.adminz_enable_vertical_blog_post_mobile .has-shadow .col:not(.grid-col) .box-blog-post:not(.ignore_vertical) .box-image{
			margin-left:  15px;
		}	
		/*Chỉ áp dụng cho row - ko áp dụng cho slider*/
		body.adminz_enable_vertical_blog_post_mobile .row>.col>.col-inner>a>.page-box:not(.ignore_vertical)>.box-image>.image-cover,
		body.adminz_enable_vertical_blog_post_mobile .row>.col>.col-inner>a>.box-blog-post:not(.ignore_vertical)>.box-image>.image-cover{
			padding-top:  100% !important;
		}
		body.adminz_enable_vertical_blog_post_mobile .flickity-slider>.col>.col-inner>a>.page-box:not(.ignore_vertical)>.box-image,
		body.adminz_enable_vertical_blog_post_mobile .flickity-slider>.col>.col-inner>a>.box-blog-post:not(.ignore_vertical)>.box-image{
			width: 100% !important;
			max-width: 100% !important;
			margin:  0px !important;
			position: relative !important;
		}
		body.adminz_enable_vertical_blog_post_mobile.pack1 .flickity-slider>.col>.col-inner>a>.page-box:not(.ignore_vertical)>.box-image img,
		body.adminz_enable_vertical_blog_post_mobile.pack1 .flickity-slider>.col>.col-inner>a>.box-blog-post:not(.ignore_vertical)>.box-image img{
			border-bottom-left-radius: 0px !important;
			border-bottom-right-radius: 0px !important;
		}
		

		body.adminz_enable_vertical_blog_post_mobile .col:not(.grid-col) .page-box:not(.ignore_vertical) .box-image .box-image{
			margin-top: 0px !important;
			margin-bottom: 0px !important;
			margin-left: 0px !important;
			margin-right: 0px !important;
			max-width: 100% !important;
			width: 100% !important;
		}


		.box-vertical{
			display: table;
		}
		.box-vertical .box-image{
			display: table-cell;
			width: 21% !important;
			vertical-align: middle;
		}
		.box-vertical .box-text{
			display: table-cell;
			vertical-align: middle;
			padding-left: 15px;
			
		}
	}


	
	@media only screen and (min-width: 850px){
		body.adminz_hide_headermain_on_scroll .header-wrapper.stuck #masthead{
			display: none;
		}
	}
	
	.col.post-item .col-inner{
		height: 100%;
	}

	.section-title-container .section-title {
	  margin-bottom: 0px !important;
	}
	.section-title-container .section-title .section-title-main {
	  padding-bottom: 0px !important;
	}

	/*woocommerce*/				
	@media (max-width:  549px){
		body.adminz_enable_vertical_product_mobile .product-small{
			display: flex;
		}
		body.adminz_enable_vertical_product_mobile .product-small .box-image{
			width: 25% !important;
			max-width: 25% !important;						
			margin:  15px 0px 15px 0px;
		}
		body.adminz_enable_vertical_product_mobile .has-shadow .product-small .box-image{
			margin-left:  15px;
		}
		body.adminz_enable_vertical_product_mobile .product-small .box-text{
			text-align: left;
			padding:  15px;
		}
	}


	@media (max-width:  549px){
		body.adminz_enable_vertical_product_related_mobile .related .product-small{
			display: flex;
		}
		body.adminz_enable_vertical_product_related_mobile .related .product-small .box-image{
			width: 25% !important;
			max-width: 25% !important;						
			margin:  15px 0px 15px 0px;
		}
		body.adminz_enable_vertical_product_related_mobile .related .has-shadow .product-small .box-image{
			margin-left:  15px;
		}
		body.adminz_enable_vertical_product_related_mobile .related .product-small .box-text{
			text-align: left;
			padding:  15px;
		}
	}

	.woocommerce-bacs-bank-details ul{
		list-style: none;
	}
	.woocommerce-bacs-bank-details ul li{
		font-size: 0.9em;
	}
	.woocommerce-password-strength.bad,
	.woocommerce-password-strength.short{
		color: var(--alert-color);
	} 

	.related-products-wrapper>h3{
		max-width: unset;
	}
	@media (min-width: 532px){
		body.fix_product_image_box_vertical .related-products-wrapper .box-vertical .box-image,
		body.fix_product_image_box_vertical .has-box-vertical .col .box-image{
			width: 25% !important;
			min-width: unset !important;
		}
	}

	
	.box-text-products ul{
		list-style: none;
	}
	/*contact form 7*/
	input[type=submit].is-xsmall{font-size: .7em; }
	input[type=submit].is-smaller{font-size: .75em; }
	input[type=submit].is-mall{font-size: .8em; }
	input[type=submit]{font-size: .97em; }
	input[type=submit].is-large{font-size: 1.15em; }
	input[type=submit].is-larger{font-size: 1.3em; }
	input[type=submit].is-xlarge{font-size: 1.5em; }
	.wpcf7-form{ margin-bottom: 0px; }
	.wpcf7-response-output{
		margin: 0 0 1em !important;
	}
	.wpcf7-spinner{
		display: none;
	}
	/*zalo icon*/
	.button.zalo:not(.is-outline), .button.zalo:hover{
		color: #006eab !important;
	}
	
	/*cf7*/
	@media (max-width:  549px){
		.flex-row.form-flat.medium-flex-wrap{
			align-items: flex-start;
		}
		.flex-row.form-flat.medium-flex-wrap .ml-half{
			margin-left:  0px !important;
		}
	}
	.archive-page-header{
		display: none;
	}	
	/*ux_video*/
	.video.video-fit >div{
		width: 100% !important;
	}
	/*menu element*/
	body .ux-menu-title{
		font-size: 1em;
	}

	/*Select 2*/
	<?php if(wp_script_is('select2')):?>
	 	.select2-container .selection .select2-selection--multiple{
			height: unset !important;
			line-height: unset !important;
			padding-top: 0px !important;
			padding-bottom: 0px !important;
			min-height: unset !important;
		}	
		.select2-container .selection .select2-selection--multiple .select2-selection__choice{
			padding-top: 0px !important;
			padding-bottom: 0px !important;
		}	
		/*Fix lỗi không hiển thị nếu hidden*/
		.adminz_woo_form .select2-selection__rendered>li:first-child .select2-search__field{
		    width: 100% !important;
		}	
		body .select2-container--default .select2-selection--multiple .select2-selection__rendered{
			padding: 0px;
		}
		
	<?php endif; ?>

	html:not([ng-app="uxBuilder"]) select[multiple="multiple"]{
		display: none;
	}
	html[ng-app="uxBuilder"] select[multiple="multiple"]{
		overflow: hidden;
	}


	@media screen and (max-width: 549px){
		body .row-slider .flickity-prev-next-button {
			width: 36px !important;
		}
		body .row-slider .flickity-prev-next-button svg{
			padding: 20% !important;
		}
		body .slider-wrapper .flickity-prev-next-button{
			display: inline-block !important;
			opacity: 1 !important;
		}
	}
	.wpcf7-form .col .wpcf7-form-control:not(.wpcf7-not-valid){
		margin-bottom: 0px;
	}
	/*Blog*/
	.article-inner:hover{
		box-shadow: none !important;
	}
	@media (min-width: 850px){
		body.archive .blog-wrapper>.row.align-center>.large-10{
			max-width: 100%;
    		flex-basis: 100%;
		}
	}
</style>