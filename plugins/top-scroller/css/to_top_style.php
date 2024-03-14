<style>
.To_top_btn a {

	display: block;
	font-size: <?php echo get_option('scroll_to_top_font_size');?>px;
	width:50px;
	height:50px;
	line-height: 50px;
	text-align: center;
	border-radius: 50px;
	background: <?php echo get_option('scroll_to_top_btn_color') ?>;
	color:<?php echo get_option('scroll_to_top_icon_color') ?>;
	-webkit-box-shadow:1px 1px 2px 1px rgba(0,0,0,0.3);
	box-shadow: 1px, 1px,4px,1px rgba(0,0,0,0.3);
	z-index: 9999;
	text-decoration: none;
	position: fixed;
	bottom: 20px;
	right: 30px;

}

.To_top_btn a:hover,
.To_top_btn a:focus,
.To_top_btn a:active {
	color: <?php echo get_option('scroll_to_top_hvr_icon_color');?>;
	background: <?php echo get_option('scroll_to_top_hvr_color');?>;

}
</style>