<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// slider settings
if ( isset( $slider['sf_7_width'] ) ) {
	$sf_7_width = $slider['sf_7_width'];
} else {
	$sf_7_width = '100%';
}
if ( isset( $slider['sf_7_height'] ) ) {
	$sf_7_height = $slider['sf_7_height'];
} else {
	$sf_7_height = '100%';
}
if ( isset( $slider['sf_7_slide_circle_size'] ) ) {
	$sf_7_slide_circle_size = $slider['sf_7_slide_circle_size'];
} else {
	$sf_7_slide_circle_size = 360;
}
if ( isset( $slider['sf_7_inner_circle_size'] ) ) {
	$sf_7_inner_circle_size = $slider['sf_7_inner_circle_size'];
} else {
	$sf_7_inner_circle_size = 480;
}
if ( isset( $slider['sf_7_auto_play'] ) ) {
	$sf_7_auto_play = $slider['sf_7_auto_play'];
} else {
	$sf_7_auto_play = 'true';
}
if ( isset( $slider['sf_7_sorting'] ) ) {
	$sf_7_sorting = $slider['sf_7_sorting'];
} else {
	$sf_7_sorting = 0;
}

// CSS and JS
wp_enqueue_style( 'sf-7-rotating-slider-css' );
wp_enqueue_script( 'jquery' );
wp_enqueue_script( 'sf-7-jquery.rotating-slider-js' );
?>
<style>
.sf-7-rotating-slider-container-<?php echo esc_html( $sf_slider_id ); ?> *{
	box-sizing: content-box;
}

.sf-7-rotating-slider-container-<?php echo esc_html( $sf_slider_id ); ?> {
	font-family: 'Roboto Slab', sans-serif;
	font-weight: 300;
	overflow: hidden;
	width:<?php echo esc_html( $sf_7_width ); ?> !important;
	height:<?php echo esc_html( $sf_7_height ); ?> !important;
}

.sf-7-rotating-slider ul.slides li {
	background-position: center;
	background-repeat: no-repeat;
	background-size: cover;
}

.sf-7-rotating-slider ul.slides li .inner {
	box-sizing: border-box;
	padding-left: 2em  !important;
	padding-right: 2em  !important;
	height: 100%;
	width: 100%;
	word-wrap: break-word;
}

.sf-7-rotating-slider ul.slides li .inner .sf-7-title{
	font-size: 32px;
	color: #eeeeee  !important;
	text-shadow: 1px 1px #616161 !important;
}

.sf-7-rotating-slider ul.slides li .inner .sf-7-desc{
	font-size: 16px;
	color: #eeeeee  !important;
	text-shadow: 1px 1px #616161 !important;
	margin-bottom: 0 !important; 
}

.sf-7-rotating-slider ul.slides li .inner .sf-7-button1 {
	background-color: #F1F1F1; 
	color: black; border: none; 
	cursor: pointer; 
	border-radius: 0px; 
	text-align: center; 
	padding: 5px 15px; 
	transition: all .3s;
}

.sf-7-rotating-slider ul.slides li .inner .sf-7-button2 {
	background-color: #F1F1F1; 
	color: black; border: none; 
	cursor: pointer; 
	border-radius: 0px; 
	text-align: center; 
	padding: 5px 15px; 
	transition: all .3s;
}

.sf-7-rotating-slider ul.slides li .inner .sf-7-button1:hover {
	background-color: #3E3D3D; 
	color: white; 
}

.sf-7-rotating-slider ul.slides li .inner .sf-7-button2:hover {
	background-color: #3E3D3D; 
	color: white; 
}
</style>

<!-- slider start-->
<div class="sf-7-rotating-slider-container-<?php echo esc_attr( $sf_slider_id ); ?>">
	<div class="sf-7-rotating-slider">
		<ul class="slides">
		<?php
			// slide sorting start
		if ( $sf_7_sorting == 1 ) {
			// Slide ID Ascending (key Ascending)
			ksort( $slider['sf_slide_title'] );
		}
		if ( $sf_7_sorting == 2 ) {
			// Slide ID Descending (key Descending)
			krsort( $slider['sf_slide_title'] );
		}
			// slide sorting end

			// load sides
		if ( isset( $slider['sf_slide_title'] ) ) {
			foreach ( $slider['sf_slide_title'] as $sf_id_1 => $value ) {
				$attachment_id  = $sf_id_1;
				$sf_slide_title = get_the_title( $attachment_id );
				$sf_slide_alt   = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
				// wp_get_attachment_image_src ( int $attachment_id, string|array $size = 'thumbnail', bool $icon = false )
				// thumb, thumbnail, medium, large, post-thumbnail
				$sf_slide_thumbnail_url = wp_get_attachment_image_src( $attachment_id, 'large', true ); // attachment medium URL
				$sf_slide_full_url      = wp_get_attachment_image_src( $attachment_id, 'full', true ); // attachment medium URL
				$attachment             = get_post( $attachment_id );
				$sf_slide_descs         = $attachment->post_content; // attachment description
				// print_r($sf_slide_full_url);
				?>
					<li style="	background-image: url('<?php echo esc_url( $sf_slide_full_url[0] ); ?>');" role="img" aria-label="<?php echo esc_attr( $sf_slide_alt ); ?>"  title="<?php echo esc_attr( $sf_slide_alt ); ?>">
						<div class="inner">
							<div class="sf-7-title"><?php echo esc_html( $sf_slide_title ); ?></div>	<!-- Slide Title -->
							<div class="sf-7-desc"><?php echo esc_html( $sf_slide_descs ); ?></div>	<!-- Slide Desc -->
						</div>
					</li>
				<?php
			}
			?>
			<?php
		}
		?>
		</ul>
	</div>
</div>
<!-- slider end-->

<script>
jQuery(function(){ 
	jQuery('.sf-7-rotating-slider').rotatingSlider({
		// auto play
		autoRotate: <?php echo esc_js( $sf_7_auto_play ); ?>,
		// size of slider
		slideHeight : Math.min(<?php echo esc_js( $sf_7_slide_circle_size ); ?>, window.innerWidth -80),  //slide_circle_size
		slideWidth : Math.min(<?php echo esc_js( $sf_7_inner_circle_size ); ?>, window.innerWidth - 80),  //inner_circle_size
	});
});
</script>
