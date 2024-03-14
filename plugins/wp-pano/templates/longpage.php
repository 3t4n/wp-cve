<?php $id = get_the_ID();?>
<style>
#wp_pano_post_title_long{
	width: 100%;
	cursor: default;
	}
	
#wp_pano_post_title_long > h1{
	color: #fff;
	margin: 5px;
	text-align: center;
	}		
	
#wp_pano_post_wrapper_long {
    max-width: 800px;
	width: 70%;
	margin: 30px auto 30px auto;
	z-index: 9999;
	}
	
#wp_pano_post_content_long {
	width: 100%;
	-webkit-box-shadow: 0 0 50px 5px rgba(0,0,0,0.4);
	box-shadow: 0 0 50px 5px rgba(0,0,0,0.4);
	border: 1px solid;	
	box-sizing: border-box;
	padding: 15px 25px 10px 25px;
	cursor: default;
	background: #fff; 
	border-radius: 5px;
	color: #444;
	display: inline-block;
	overflow: hidden;
	}
	
.wp-pano-close-icon-long {
	color: #fff;
	text-align: center;
	line-height: 48px;
	width: 48px;
	height: 48px;
	position: absolute;
	right: 40px;
	top: 30px;
	transition: color .1s ease-in-out,background .1s ease-in-out;	
}	

.wp-pano-close-icon-long:before {
	content: '\f158';
	font: normal 48px/48px 'dashicons';
	speak: none;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
}

.wp-pano-close-icon-long:hover {
	color: #00a0d2;
	cursor: pointer;
}	
</style>
<div id="wp_pano_post_wrapper_long" >
	<div id="wp_pano_post_title_long"><h1><?php the_title();?></h1></div>
	<div id="wp_pano_post_content_long">
		<?php the_content(); ?>
	</div>
	<div class="wp-pano-close-icon-long" onclick="wppano_close_post();"></div>
</div>
<?php wp_die();?>