<?php
/**
 * Dynamic css
 *
 * @version 1.0
 * @package Blog Designer
 */

$settings                       = get_option( 'wp_blog_designer_settings' ); //phpcs:disable
$background                     = ( isset( $settings['template_bgcolor'] ) && '' != $settings['template_bgcolor'] ) ? $settings['template_bgcolor'] : '';
$templatecolor                  = ( isset( $settings['template_color'] ) && '' != $settings['template_color'] ) ? $settings['template_color'] : '';
$grid_hoverback_color           = ( isset( $settings['grid_hoverback_color'] ) && '' != $settings['grid_hoverback_color'] ) ? $settings['grid_hoverback_color'] : '';
$color                          = ( isset( $settings['template_ftcolor'] ) && '' != $settings['template_ftcolor'] ) ? $settings['template_ftcolor'] : '';
$linkhover                      = ( isset( $settings['template_fthovercolor'] ) && '' != $settings['template_fthovercolor'] ) ? $settings['template_fthovercolor'] : '';
$titlecolor                     = ( isset( $settings['template_titlecolor'] ) && '' != $settings['template_titlecolor'] ) ? $settings['template_titlecolor'] : '';
$contentcolor                   = ( isset( $settings['template_contentcolor'] ) && '' != $settings['template_contentcolor'] ) ? $settings['template_contentcolor'] : '';
$readmorecolor                  = ( isset( $settings['template_readmorecolor'] ) && '' != $settings['template_readmorecolor'] ) ? $settings['template_readmorecolor'] : '';
$readmorebackcolor              = ( isset( $settings['template_readmorebackcolor'] ) && '' != $settings['template_readmorebackcolor'] ) ? $settings['template_readmorebackcolor'] : '';
$alterbackground                = ( isset( $settings['template_alterbgcolor'] ) && '' != $settings['template_alterbgcolor'] ) ? $settings['template_alterbgcolor'] : '';
$titlebackcolor                 = ( isset( $settings['template_titlebackcolor'] ) && '' != $settings['template_titlebackcolor'] ) ? $settings['template_titlebackcolor'] : '';
$template_labeltextcolor		= ( isset( $settings['template_labeltextcolor'] ) && '' != $settings['template_labeltextcolor'] ) ? $settings['template_labeltextcolor'] : '';
$social_icon_style              = get_option( 'social_icon_style' ); //phpcs:enable
$template_alternativebackground = get_option( 'template_alternativebackground' );
$template_titlefontsize         = get_option( 'template_titlefontsize' );
$content_fontsize               = get_option( 'content_fontsize' );
$custom_css                     = get_option( 'custom_css' );
?>
<style type="text/css">
	/**
	 * Table of Contents
	 *
	 * 1.0 - Pagination
	 * 2.0 - Social Media Icon
	 * 3.0 - Default Blog Template
	 * 4.0 - Classical Template
	 * 5.0 - Light Breeze Template
	 * 6.0 - Spektrum Template
	 * 7.0 - Evolution Template
	 * 8.0 - Timeline Template
	 * 9.0 - News Template
	 * 10.0 - Sallet Template
	 * 11.0 - Media Grid Template
	 * 12.0 - Crayon Slider Template
	 * 13.0 - Blog-Carousel Template
	 * 14.0 - Blog-Grid-Box Template
	 * 15.0 - Ticker Template
	 *
	 */
	/**
	 * 1.0 - Pagination
	 */
	.bdp-load-more a.button.bdp-load-more-btn {
		<?php echo ( '' != $readmorebackcolor ) ? 'background-color: ' . esc_attr( $readmorebackcolor ) . ';' : ''; ?>
		<?php echo ( '' != $readmorecolor ) ? 'color: ' . esc_attr( $readmorecolor ) . ';' : ''; ?>
		<?php echo ( '' != $content_fontsize ) ? 'font-size:' . esc_attr( $content_fontsize ) . 'px;' : ''; ?>
	}
	.bd_pagination_box.wl_pagination_box .paging-navigation ul.page-numbers li a.page-numbers:hover,
	.bd_pagination_box.wl_pagination_box .paging-navigation ul.page-numbers li > span.current {
		<?php echo ( '' != $readmorebackcolor ) ? 'background-color: ' . esc_attr( $readmorebackcolor ) . ';' : ''; ?>
		<?php echo ( '' != $readmorecolor ) ? 'color: ' . esc_attr( $readmorecolor ) . ';' : ''; ?>
		<?php echo ( '' != $content_fontsize ) ? 'font-size:' . esc_attr( $content_fontsize ) . 'px;' : ''; ?>
	}
	.bd_pagination_box.wl_pagination_box .paging-navigation ul.page-numbers li a.page-numbers {
		<?php echo ( '' != $readmorecolor ) ? 'background-color: ' . esc_attr( $readmorecolor ) . ';' : ''; ?>
		<?php echo ( '' != $readmorebackcolor ) ? 'color: ' . esc_attr( $readmorebackcolor ) . ';' : ''; ?>
		<?php echo ( '' != $content_fontsize ) ? 'font-size:' . esc_attr( $content_fontsize ) . 'px;' : ''; ?>
	}
	.bd_pagination_box.wl_pagination_box .paging-navigation ul.page-numbers li a.page-numbers.dots {
		<?php echo ( '' != $content_fontsize ) ? 'font-size:' . esc_attr( $content_fontsize ) . 'px !important;' : ''; ?>
	}
	/**
	 * 2.0 - Social Media Icon
	 */    
	.blog_template .social-component a {
		<?php
		if ( 0 == $social_icon_style ) {
			echo 'border-radius: 100%;';
		}
		?>
	}
	/**
	 * 3.0 - Default Blog Template
	 */
	<?php if ( '' != $titlebackcolor ) { ?>
		/* .bdp_blog_template.nicy .blog_header h2, */
		.blog_template.sallet_slider h2,
		.blog_template.crayon_slider .blog_header h2,
		.glossary .blog_header h2,
		.bdp_blog_template .bd-blog-header h2 {        
			<?php echo 'background: ' . esc_attr( $titlebackcolor ); ?>
		}
	<?php } ?>
	.blog_template .bd-more-tag-inline {
		<?php echo ( '' != $readmorecolor ) ? 'color: ' . esc_attr( $readmorecolor ) . ' !important;' : ''; ?>
		<?php echo ( '' != $content_fontsize ) ? 'font-size:' . esc_attr( $content_fontsize ) . 'px;' : ''; ?>
	}
	<?php if ( '' != $titlecolor || '' != $template_titlefontsize || '' != $titlebackcolor ) { ?>
		.bdp_blog_template .blog_header h2 a,
		.bdp_blog_template .bd-blog-header h2 a,
		.bdp_blog_template .bd_ad_container h4 {
			<?php echo ( '' != $titlecolor ) ? 'color: ' . esc_attr( $titlecolor ) . ' !important;' : ''; ?>
			<?php echo ( '' != $template_titlefontsize ) ? 'font-size: ' . esc_attr( $template_titlefontsize ) . 'px;' : ''; ?>
			<?php echo ( '' != $titlebackcolor ) ? 'background-color: ' . esc_attr( $titlebackcolor ) . ' !important;' : ''; ?>

		}
		.bdp_blog_template .blog_header h2,
		.bdp_blog_template .bd-blog-header h2,
		.blog_template .bd_ad_container h4 {
			<?php echo ( '' != $template_titlefontsize ) ? 'font-size: ' . esc_attr( $template_titlefontsize ) . 'px;' : ''; ?>
		}
	<?php } ?>
	.post_content-inner p,
	.bd-post-content {
		<?php echo ( '' != $contentcolor ) ? 'color:' . esc_attr( $contentcolor ) . ';' : ''; ?>
		<?php echo ( '' != $content_fontsize ) ? 'font-size:' . esc_attr( $content_fontsize ) . 'px;' : ''; ?>
	}
	.bdp_blog_template .bd-metacats,
	.bd-tags,
	span.bd-category-link,
	.bdp_blog_template .author,
	.bdp_blog_template .date,
	.bdp_blog_template .bd-categories,
	.bdp_blog_template.evolution .bd-categories a,
	.bdp_blog_template .bd-categories a,
	.bd-meta-data-box .bd-metacats,
	.bd-meta-data-box .bd-metacomments,
	.bd-meta-data-box .bd-metacomments span,
	.bdp_blog_template .date i, .bdp_blog_template .author i, .bdp_blog_template .comment i,
	.bd-tags a,
	span.bd-category-link a,
	.bd-metadatabox p,
	span .bd-link-label,
	.bdp_blog_template .blog_footer span,
	.bd-metacomments i,
	.date_wrap i,
	.bdp_blog_template a,
	.bdp_blog_template .post_content,
	.bd-categories i,
	.bdp_blog_template.spektrum .post-bottom .post-by,
	.bdp_blog_template.spektrum .post-bottom .bd-tags,
	.bd-metadatabox,
	.bdp_blog_template.news .bd-blog-header .bd-metadatabox a,
	.bdp_blog_template.news .post-content-div .post_cat_tag > span i,
	.bdp_blog_template .bd-meta-data-box .bd-metadate, .bdp_blog_template .bd-meta-data-box .bd-metauser, .bdp_blog_template .bd-meta-data-box .bd-metacats,
	.bd-meta-data-box .bd-metacats a,
	.bd-meta-data-box .bd-metacomments a,
	.bdp_blog_template.box-template .bd-tags,
	.bdp_blog_template .datetime,
	.bdp_blog_template .link-lable,
	.bdp_blog_template .post-date
	.bdp_blog_template .bd_ad_container p, .blog_template .bd_ad_container, .bdp_blog_template .post-date, .bdp_blog_template .post-comment span {
		<?php echo ( '' != $content_fontsize ) ? 'font-size:' . esc_attr( $content_fontsize ) . 'px;' : ''; ?>
	}
	.bdp_blog_template .date,
	.bdp_blog_template .comment,
	.bdp_blog_template .author .bd-icon-author,
	.bdp_blog_template .author,
	.bdp_blog_template.news .post-content-div .post_cat_tag > span i ,
	.bdp_blog_template .post-by,
	.bdp_blog_template .bd-categories i,
	.bdp_blog_template .bd-metacats i,
	.bdp_blog_template .category-link i,
	.bdp_blog_template .bd-tags i,
	.bdp_blog_template .bd-metacomments,
	.bdp_blog_template .date_wrap i,
	.bdp_blog_template .post-date,
	.bdp_blog_template .link-lable,
	.bdp_blog_template .datetime,
	.bdp_blog_template.glossary .post-author span,
	.bdp_blog_template .post_content-inner p,
	.bdp_blog_template .post_content,
	.bdp_blog_template .tags i {
		<?php echo ( '' != $contentcolor ) ? 'color:' . esc_attr( $contentcolor ) . ';' : ''; ?>
	}
	.bdp_blog_template .bd-meta-data-box {
		<?php echo ( '' != $contentcolor ) ? 'color:' . esc_attr( $contentcolor ) . ';' : ''; ?>
	}
	<?php if ( '' != $contentcolor ) { ?>
		.bd-metadatabox {
			<?php echo ( '' != $contentcolor ) ? 'color:' . esc_attr( $contentcolor ) . ';' : ''; ?>
		}
	<?php } ?>
	.bd-link-label {
		<?php echo ( '' != $contentcolor ) ? 'color:' . esc_attr( $contentcolor ) . ';' : ''; ?>
	}
	.bdp_blog_template a.bd-more-tag {
		<?php echo ( '' != $readmorebackcolor ) ? 'background-color: ' . esc_attr( $readmorebackcolor ) . '!important;' : ''; ?>
		<?php echo ( '' != $readmorecolor ) ? 'color: ' . esc_attr( $readmorecolor ) . '!important;' : ''; ?>
	}
	<?php if ( '' != $readmorebackcolor || '' != $readmorecolor ) { ?>
		.bdp_blog_template a.bd-more-tag:hover {
			<?php echo ( '' != $readmorecolor ) ? 'background-color: ' . esc_attr( $readmorecolor ) . '!important;' : ''; ?>
			<?php echo ( '' != $readmorebackcolor ) ? 'color: ' . esc_attr( $readmorebackcolor ) . '!important;' : ''; ?>
		}
	<?php } ?>
	.bdp_blog_template i {
		font-style: normal !important;
	}
	<?php if ( '' != $color ) { ?>
		.bd-tags,
		span.bd-category-link,
		.bdp_blog_template .bd-categories,
		.bd-meta-data-box .bd-metacats,
		.bd-meta-data-box .bd-metacats a,
		.bd-meta-data-box .bd-metacomments a,
		.bdp_blog_template .bd-categories a,
		.bd-post-content a,
		.bd-tags a,
		span.bd-category-link a,
		.bdp_blog_template .tags,
		.bdp_blog_template a {
			color:<?php echo esc_attr( $color ); ?> !important;
			font-weight: normal !important;
		}
	<?php } ?>
	<?php if ( '' != $linkhover ) { ?>
		.bd-meta-data-box .bd-metacats a:hover,
		.bd-meta-data-box .bd-metacomments a:hover,
		.bdp_blog_template .bd-categories a:hover,
		.spektrum .post-bottom .bd-categories a:hover,
		.bd-post-content a:hover,
		.bd-tags a:hover,
		span.bd-category-link a:hover,
		.bdp_blog_template a:hover,
		.bd-post-content a:hover {
			color:<?php echo esc_attr( $linkhover ); ?> !important;
		}
	<?php } ?>
	<?php if ( '' != $templatecolor ) { ?>
		.bdp_blog_template.evolution,
		.bdp_blog_template.lightbreeze {
			background: <?php echo esc_attr( $templatecolor ); ?>;
		}
	<?php } ?>
	<?php if ( '' != $grid_hoverback_color ) { ?>
		.bdp_blog_template.evolution:hover,
		.bdp_blog_template.lightbreeze:hover {
			background: <?php echo esc_attr( $grid_hoverback_color ); ?>;
		}
	<?php } ?>
	<?php
	if ( 0 == $template_alternativebackground ) {
		if ( '' != $alterbackground ) {
			?>
			.bdp_blog_template.evolution.alternative-back,
			.bdp_blog_template.lightbreeze.alternative-back {
				background: <?php echo esc_attr( $alterbackground ); ?>;
			}
		<?php } else { ?>
			.bdp_blog_template.evolution.alternative-back,
			.bdp_blog_template.lightbreeze.alternative-back {
				background: transparent;
			}
			<?php
		}
	}
	?>
	/**
	 * 4.0 - Classical Template
	 */
	.bdp_blog_template.classical .bd-blog-header .bd-tags {
		<?php echo ( '' != $color ) ? 'color: ' . esc_attr( $color ) . ';' : ''; ?>
	}
	/**
	 * 5.0 - Light Breeze Template
	 */
	/**
	 * 6.0 - Spektrum Template
	 */
	.bdp_blog_template.spektrum .bd-blog-header {
		<?php echo ( '' != $titlebackcolor ) ? 'background:' . esc_attr( $titlebackcolor ) . ';' : ''; ?>
	}
	<?php if ( '' != $titlecolor ) { ?>
		.spektrum .date {
			background-color: <?php echo esc_attr( $titlecolor ); ?>;
		}
		<?php
	}
	if ( '' != $color ) {
		?>
		.spektrum .post-bottom .bd-categories a {
			color: <?php echo esc_attr( $color ); ?>;
		}
		<?php
	}
	if ( '' != $readmorecolor ) {
		?>
		.spektrum .details a {
			color :<?php echo esc_attr( $readmorecolor ); ?> !important;
			<?php echo ( '' != $content_fontsize ) ? 'font-size:' . esc_attr( $content_fontsize ) . 'px;' : ''; ?>
		}
		<?php
	}
	if ( '' != $readmorebackcolor ) {
		?>
		.spektrum .details a:hover {
			color :<?php echo esc_attr( $readmorebackcolor ); ?>;
		}
	<?php } ?>
	/**
	 * 7.0 - Evolution Template
	 */
	/**
	 * 8.0 - Timeline Template
	 */
	.timeline_bg_wrap:before {
		background: none repeat scroll 0 0 <?php echo esc_attr( $templatecolor ); ?>;
	}
	.bd-datetime, .timeline_year span.year_wrap {
		background: none repeat scroll 0 0 <?php echo esc_attr( $templatecolor ); ?>;
	}
	.bdp_blog_template.timeline .post_hentry > p > i {
		<?php echo ( '' != $templatecolor ) ? 'background: ' . esc_attr( $templatecolor ) . ';' : ''; ?>
	}
	.bdp_blog_template.timeline:nth-child(2n+1) .post_content_wrap:before,
	.bdp_blog_template.timeline:nth-child(2n+1) .post_content_wrap:after {
		border-left: 8px solid <?php echo esc_attr( $templatecolor ); ?>;
	}
	.rtl .bdp_blog_template.timeline:nth-child(2n+1) .post_content_wrap:before,
	.rtl .bdp_blog_template.timeline:nth-child(2n+1) .post_content_wrap:after {
		border-right: 8px solid <?php echo esc_attr( $templatecolor ); ?>;
	}
	.bdp_blog_template.timeline:nth-child(2n) .post_content_wrap:before,
	.bdp_blog_template.timeline:nth-child(2n) .post_content_wrap:after {
		border-right: 8px solid <?php echo esc_attr( $templatecolor ); ?>;
	}
	.rtl .bdp_blog_template.timeline:nth-child(2n) .post_content_wrap:before,
	.rtl .bdp_blog_template.timeline:nth-child(2n) .post_content_wrap:after {
		border-left: 8px solid <?php echo esc_attr( $templatecolor ); ?>;
	}
	.post_content_wrap {
		border:1px solid <?php echo esc_attr( $templatecolor ); ?>;
	}
	.bdp_blog_template .post_content_wrap .blog_footer {
		border-top: 1px solid <?php echo esc_attr( $templatecolor ); ?> ;
	}
	.bdp_blog_template .post-icon {
		<?php echo ( '' != $titlebackcolor ) ? 'background:' . esc_attr( $titlebackcolor ) . ';' : ''; ?>
	}
	.bdp_blog_template.timeline .desc h3 a{
		<?php echo ( '' != $titlecolor ) ? 'color: ' . esc_attr( $titlecolor ) . ' !important;' : ''; ?>
		<?php echo ( '' != $titlebackcolor ) ? 'background:' . esc_attr( $titlebackcolor ) . ' !important;' : ''; ?>
		<?php echo ( '' != $template_titlefontsize ) ? 'font-size: ' . esc_attr( $template_titlefontsize ) . 'px;' : ''; ?>
	}

	/*media query*/
	@media screen and (max-width: 991px) {
		.bdp_blog_template.timeline:nth-child(2n+1) .post_content_wrap:before,
		.bdp_blog_template.timeline:nth-child(2n+1) .post_content_wrap:after {
			border-right: 8px solid <?php echo esc_attr( $templatecolor ); ?>;
		}
	}
	/**
	 * 9.0 - News Template
	 */
	<?php if ( '' != $titlecolor || '' != $template_titlefontsize ) { ?>
		.bdp_blog_template.news .bd-blog-header h2.title a{
			<?php echo ( '' != $titlecolor ) ? 'color: ' . esc_attr( $titlecolor ) . ';' : ''; ?>
			<?php echo ( '' != $template_titlefontsize ) ? 'font-size: ' . esc_attr( $template_titlefontsize ) . 'px;' : ''; ?>
		}
	<?php } ?>
	/* .blog_template.boxy-clean .blog_header h2, */
	.bdp_blog_template.news .bd-blog-header h2.title{
		<?php
		if ( '' != $titlebackcolor ) {
			echo 'background: ' . esc_attr( $titlebackcolor );
		}
		?>
	}
	.bdp_blog_template.news {
		<?php
		if ( '' != $templatecolor ) {
			echo 'background-color: ' . esc_attr( $templatecolor );
		}
		?>
	}
	.bdp_blog_template.news a.bd-more-tag{
		float: right !important;
	}
	.blog_template.boxy-clean ul li:hover,
	.blog_template.boxy-clean ul li:hover .blog_header h2 {
		background: <?php echo esc_attr( $grid_hoverback_color ); ?>;
	}
	.glossary .post_content-inner {
		<?php echo ( '' != $templatecolor ) ? 'border-color: ' . esc_attr( $templatecolor ) . ';' : ''; ?>
	}
	.blog_template.boxy-clean .blog_wrap.bdp_blog_template .author {
		<?php echo ( '' != $templatecolor ) ? 'background: ' . esc_attr( $templatecolor ) . ';' : ''; ?>
	}
	/** 10.0 - Sallet Template */
	.bdp_blog_template.sallet_slider .blog_header > div > div > div {
		<?php echo ( '' != $background ) ? 'background: ' . esc_attr( $background ) . ';' : ''; ?>
	}
	.bdp_blog_template.sallet_slider .blog_header > div > div > div::before {
		<?php echo ( '' != $templatecolor ) ? 'background: ' . esc_attr( $templatecolor ) . ' none repeat scroll 0 0;' : ''; ?>
	}
	.bdp_blog_template.sallet_slider.right {
		<?php echo ( '' != $templatecolor ) ? 'background: ' . esc_attr( $templatecolor ) . ' ;' : ''; ?>
	}
	.blog_template.sallet_slider .category-link a {
		<?php echo ( '' != $templatecolor ) ? 'background: ' . esc_attr( $templatecolor ) . ' ;' : ''; ?>
		color: #fff !important;
	}
	/** 11.0 - Media Grid Template */
	.bdp_blog_template.media-grid .blog_header h2 a {
		<?php
		if ( '' != $titlebackcolor ) {
			echo 'background: ' . esc_attr( $titlebackcolor );
		}
		?>
	}
	.bdp_blog_template.media-grid .bd-metacats {
		<?php
		if ( '' != $templatecolor ) {
			echo 'background: ' . esc_attr( $templatecolor );
		}
		?>
	}

	.bdp_blog_template.media-grid .bd-metacats:hover{
		<?php
		if ( '' != $grid_hoverback_color ) {
			echo 'background: ' . esc_attr( $grid_hoverback_color );
		}
		?>
	}

	.bdp_blog_template.media-grid .post-meta .postdate {
		<?php
		if ( '' != $content_fontsize ) {
			echo 'font-size: ' . esc_attr( $content_fontsize ) . 'px';
		}
		?>
	}
	/** 12.0 - Crayon Slider Template */
		<?php
		if ( 'design2' == $settings['slider_design_type'] ) {
			?>
		.bdp_blog_template.crayon_slider .blog_header { 
			width: 100%;
			padding: 25px 15px 10px 15px;
		}
		.blog_template.crayon_slider ul.flex-direction-nav .flex-prev {
			right: 30px;
			left: auto;
		}
		.blog_template.crayon_slider .flex-direction-nav a {
			top: 30px;
		}
		.slides.design2 .post_metadata .category-link {
			display: flex;
		}
		.slides.design2 .post_metadata .category-link a:nth-child(3n+1) {
			background: #1abc9c;
		}
		.slides.design2 .post_metadata .category-link a:nth-child(3n+2) {
			background: #3aadff;
		}
		.slides.design2 .post_metadata .category-link a:nth-child(3n+3) {
			background: #9b59b6;
		}
		.blog_template.crayon_slider .flex-control-nav {
			bottom: 20px;
			text-align: right;
		}
		.blog_template.crayon_slider .flex-control-paging li a {
			font-size: 0;
			background: rgba(255, 255, 255, 0.9);
		}
		.blog_template.crayon_slider .flex-control-paging li a.flex-active {
			background: rgba(0, 0, 0, 0.9);
		}
		.blog_template.crayon_slider .metadatabox > div {
			margin-right: 0;
		}
		.blog_template.crayon_slider .metadatabox,.slides.design2 .post_metadata .category-link a {
			text-transform: uppercase;
		}
		@media screen and (min-width: 641px) {
			.slides.design2 .blog_header {
				display: flex;
			}
			.slides.design2 .post_metadata {
				width: 50%;
			}
			.slides.design2 .post_content {
				padding: 10px;
				border-left: 1px solid #FFF;
				width: 50%;
			}
		}
		<?php } ?>
	/** 13.0 - Blog Carousel Template */
	li.blog_template.bdp_blog_template.blog_carousel {
		<?php
		if ( '' != $templatecolor ) {
			echo 'background: ' . esc_attr( $templatecolor );
		}
		?>
	}
	.blog_template.blog_carousel .blog_header:hover {
	<?php
	if ( '' != $grid_hoverback_color ) {
		echo 'background: ' . esc_attr( $grid_hoverback_color );
	}
	?>
	}
	/** 14.0 - Blog-Grid-Box Template */
	.blog_template.blog-grid-box .bd-more-next-line a {
		<?php
		if ( '' != $readmorebackcolor ) {
			echo 'background: ' . esc_attr( $readmorebackcolor ) . ';';
		}
		?>
		<?php
		if ( '' != $readmorecolor ) {
			echo 'color: ' . esc_attr( $readmorecolor );
		}
		?>
	}
	.blog_template.blog-grid-box .post-comment a,
	.blog_template.blog-grid-box .mauthor .author a,
	.blog_template.blog-grid-box .bd-metacats a,
	.blog_template.blog-grid-box .mdate a {
		<?php
		if ( '' != $color ) {
			echo 'color: ' . esc_attr( $color );
		}
		?>
	}
	.blog_template.blog-grid-box .post-comment a:hover,
	.blog_template.blog-grid-box .mauthor .author a:hover,
	.blog_template.blog-grid-box .bd-metacats a:hover,
	.blog_template.blog-grid-box .mdate a:hover {
		<?php
		if ( '' != $linkhover ) {
			echo 'color: ' . esc_attr( $linkhover );
		}
		?>
	}
	.blog_template.blog-grid-box .post_title a {
		<?php
		if ( '' != $titlecolor ) {
			echo 'color: ' . esc_attr( $titlecolor ) . ';';
		}
		if ( '' != $titlebackcolor ) {
			echo 'background: ' . esc_attr( $titlebackcolor ) . ';';
		}
		if ( '' != $template_titlefontsize ) {
			echo 'font-size: ' . esc_attr( $template_titlefontsize ) . 'px';
		}
		?>
	}
	.blog_template.blog-grid-box .post_content {
		<?php
		if ( '' != $contentcolor ) {
			echo 'color: ' . esc_attr( $contentcolor ) . ';';
		}
		if ( '' != $content_fontsize ) {
			echo 'font-size: ' . esc_attr( $content_fontsize ) . 'px';
		}
		?>
	}
	.blog_template.blog-grid-box {
		<?php
		if ( '' != $templatecolor ) {
			echo 'background: ' . esc_attr( $templatecolor );
		}
		?>
	}

	/** 15.0 - Ticker Template */
	#blog-ticker-style-1 {
		<?php
		if ( '' != $templatecolor ) {
			echo 'border-color: ' . esc_attr( $templatecolor );
		}
		?>
	}
	#blog-ticker-style-1 .ticker-title {
		<?php
		if ( '' != $templatecolor ) {
			echo 'background-color: ' . esc_attr( $templatecolor );
		}
		?>
	}
	#blog-ticker-style-1 .ticker-style-title {
		<?php
		if ( '' != $template_labeltextcolor ) {
			echo 'color: ' . esc_attr( $template_labeltextcolor );
		}
		?>
	}
	#blog-ticker-style-1 .ticker-title>span {
		<?php
		if ( '' != $templatecolor ) {
			echo 'border-color: transparent transparent transparent' . esc_attr( $templatecolor );
		}
		?>
	}
	#blog-ticker-style-1 .blog-tickers a:hover {
		<?php
		if ( '' != $templatecolor ) {
			echo 'color: ' . esc_attr( $templatecolor );
		}
		?>
	}
	#blog-ticker-style-1 .blog-tickers a {
		<?php
		if ( '' != $titlecolor ) {
			echo 'color: ' . esc_attr( $titlecolor );
		}
		?>
	}
	<?php echo esc_html( wp_unslash( $custom_css ) ); ?>
</style>
