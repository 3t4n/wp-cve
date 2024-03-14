<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// slider settings
if ( isset( $slider['sf_10_width'] ) ) {
	$sf_10_width = $slider['sf_10_width'];
} else {
	$sf_10_width = '100%';
}
if ( isset( $slider['sf_10_height'] ) ) {
	$sf_10_height = $slider['sf_10_height'];
} else {
	$sf_10_height = '100%';
}
if ( isset( $slider['sf_10_sorting'] ) ) {
	$sf_10_sorting = $slider['sf_10_sorting'];
} else {
	$sf_10_sorting = 0;
}

// CSS and JS
wp_enqueue_script( 'jquery' );
?>

<style>
.sf-10-main-container-<?php echo esc_html( $sf_slider_id ); ?>:parent * {
	box-sizing: border-box !important;
}

.sf-10-main-container-<?php echo esc_html( $sf_slider_id ); ?> section {
	color: #ffffff; 					/* title/desc color */
}

.sf-10-main-container-<?php echo esc_html( $sf_slider_id ); ?> {
	font-family: sans-serif;
	background: #323232; 			/* Background color */
	position: relative;
	height: <?php echo esc_html( $sf_10_height ); ?>;					/* slider height */
	width: <?php echo esc_html( $sf_10_width ); ?>;						/* slider width */
	overflow: hidden; 
}

.sf-10-snap-container-<?php echo esc_html( $sf_slider_id ); ?> {
	height: 100vh;
	overflow-y: scroll;
	scroll-snap-type: y mandatory;
	-ms-overflow-style: none;
	border: 30px solid rgba(0, 0, 0, 0); 
}

.sf-10-snap-container-<?php echo esc_html( $sf_slider_id ); ?>::-webkit-scrollbar {
	display: none; 
}

.sf-10-snap-container-<?php echo esc_html( $sf_slider_id ); ?> section {
	height: 100%;
	width: 100%;
	background-position: center center !important;
	background-size: cover !important;
	background-repeat: no-repeat !important;
	position: relative;
	background: rgba(0, 0, 0, 0.5);
	-webkit-transition: opacity 2s ease;
	-moz-transition: opacity 2s ease;
	-ms-transition: opacity 2s ease;
	-o-transition: opacity 2s ease;
	transition: opacity 2s ease;
	margin: 50px 0;
	scroll-snap-align: start;
	padding: 30px; 
}

.sf-10-snap-container-<?php echo esc_html( $sf_slider_id ); ?> section::before {
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	margin: auto;
	content: '';
	position: absolute;
	display: inline-block;
	height: 100%;
	width: 100%;
	background-position: center center;
	background-size: cover;
	background-repeat: no-repeat;
	-webkit-transition: opacity 2s ease;
	-moz-transition: opacity 2s ease;
	-ms-transition: opacity 2s ease;
	-o-transition: opacity 2s ease;
	transition: opacity 2s ease;
	opacity: 0.25;
	z-index: -1; 
}

.sf-10-snap-container-<?php echo esc_html( $sf_slider_id ); ?> section.active {
	background: rgba(0, 0, 0, 0); 
}

.sf-10-snap-container-<?php echo esc_html( $sf_slider_id ); ?> section.active::before {
	opacity: 1 !important; 
}

.sf-10-snap-container-<?php echo esc_html( $sf_slider_id ); ?> section:first-child {
	margin-top: 0 !important; 
}

.sf-10-snap-container-<?php echo esc_html( $sf_slider_id ); ?> section:last-of-type {
	margin-bottom: 0 !important; 
}

.sf-10-snap-container-<?php echo esc_html( $sf_slider_id ); ?> section .content {
	margin-top: 0vh; 
}

.sf-10-snap-container-<?php echo esc_html( $sf_slider_id ); ?> section .content .sf-10-title{
	font-size: 32px;
	font-weight: bold;
	display: block;
	padding: 10px;
	color: #ffffff;				/* title color */
}

.sf-10-snap-container-<?php echo esc_html( $sf_slider_id ); ?> section .content .sf-10-desc{
	font-size: 16px;
	display: block;
	padding: 10px;
	color: #ffffff;				/* description color */
}

.sf-10-snap-container-<?php echo esc_html( $sf_slider_id ); ?> section .content .sf-10-bttns{
	background-color: #F1F1F1; 
	color: black; border: none; 
	cursor: pointer; 
	border-radius: 4px; 
	text-align: center; 
	padding: 5px 15px; 
	margin-left: 10px;
	transition: all .3s;
}

.sf-10-snap-container-<?php echo esc_html( $sf_slider_id ); ?> section .content .sf-10-bttns:hover {
	background-color: #3E3D3D; 
	color: white; 
}

.sf-10-snap-container-<?php echo esc_html( $sf_slider_id ); ?> .nav-dots {
	position: absolute;
	top: 50%;
	right: 60px;
	display: inline-block;
	justify-content: center;
	height: fit-content;
	margin: 0;
	list-style-type: none; 
}

.sf-10-snap-container-<?php echo esc_html( $sf_slider_id ); ?> .nav-dots li {
	display: block;
	margin: 10px 0;
	background: #323232;					/* Dots color */
	border-radius: 100px;
	width: 10px;
	height: 10px; 
}

.sf-10-snap-container-<?php echo esc_html( $sf_slider_id ); ?> .nav-dots li.active {
	background-color: #ffffff; 					/* Dots active color */
}

<?php if ( strstr( $sf_10_height, 'px' ) ) { ?>
.sf-10-snap-container-<?php echo esc_html( $sf_slider_id ); ?> {
	height: <?php echo esc_html( $sf_10_height ); ?>;
}
<?php } ?>
</style>

<div class="sf-10-main-container-<?php echo esc_attr( $sf_slider_id ); ?>">
	<div class="sf-10-snap-container-<?php echo esc_attr( $sf_slider_id ); ?>">
		<?php
		// slide sorting start
		if ( $sf_10_sorting == 1 ) {
			// Slide ID Ascending (key Ascending)
			ksort( $slider['sf_slide_title'] );
		}
		if ( $sf_10_sorting == 2 ) {
			// Slide ID Descending (key Descending)
			krsort( $slider['sf_slide_title'] );
		}
		// slide sorting end
		// load sides
		if ( isset( $slider['sf_slide_title'] ) ) {
			foreach ( $slider['sf_slide_title'] as $sf_id => $value ) {
				$attachment_id  = $sf_id;
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
				<section style="background-image: url('<?php echo esc_url( $sf_slide_full_url[0] ); ?>');">
					<div class="content">
						<span class="sf-10-title"><?php echo esc_html( $sf_slide_title ); ?></span>
						<span class="sf-10-desc"><?php echo esc_html( $sf_slide_descs ); ?></span>
					</div>
				</section>
				<?php
			} //end of for each
		} //end of count
		?>
		<ul class="nav-dots">
			<?php
			// For Dots
			if ( isset( $slider['sf_slide_title'] ) ) {
				$y = 0;
				foreach ( $slider['sf_slide_title'] as $sf_id_2 => $value ) {
					?>
					<li data-slide-link="<?php echo esc_html( $y ); ?>"></li>
					<?php
					$y++;
				}
				?>
			<?php } ?>
		</ul>	
	</div>
</div>

<script>
var nunumR;
jQuery(document).ready((function() {
	jQuery(".sec").html("Active slide:" + 1);
	jQuery(".sf-10-snap-container-<?php echo esc_js( $sf_slider_id ); ?> section:nth-child(1)").addClass("active");
	jQuery(".sf-10-snap-container-<?php echo esc_js( $sf_slider_id ); ?> li:nth-child(1)").addClass("active")
}));

jQuery(".sf-10-snap-container-<?php echo esc_js( $sf_slider_id ); ?>").scroll((function() {
	jQuery(".text").html("ScrollTop: " + jQuery(".sf-10-snap-container-<?php echo esc_js( $sf_slider_id ); ?>").scrollTop()); 
	nunum = jQuery(".sf-10-snap-container-<?php echo esc_js( $sf_slider_id ); ?>").scrollTop() / jQuery(".sf-10-snap-container-<?php echo esc_js( $sf_slider_id ); ?>").height() + 1;
	nunumR = nunum.toString().split(".");
	jQuery(".sf-10-snap-container-<?php echo esc_js( $sf_slider_id ); ?> section").removeClass("active"); 
	jQuery(".sf-10-snap-container-<?php echo esc_js( $sf_slider_id ); ?> section:nth-child(" + nunumR[0] + ")").addClass("active"); 
	jQuery(".sec").html("Active slide: " + nunumR[0]);
	clearTimeout(jQuery.data(this, "scrollTimer"));
	jQuery.data(this, "scrollTimer", setTimeout((function() {
		jQuery(".sf-10-snap-container-<?php echo esc_js( $sf_slider_id ); ?> li").removeClass("active"), jQuery(".sf-10-snap-container-<?php echo esc_js( $sf_slider_id ); ?> li:nth-child(" + nunumR[0] + ")").addClass("active")
	}), 150))
})); 

jQuery(window).resize((function() {
	clearTimeout(jQuery.data(this, "resizelTimer")); 
	jQuery.data(this, "resizelTimer", setTimeout((function() {
		jQuery(".sf-10-snap-container-<?php echo esc_js( $sf_slider_id ); ?>").animate({
			scrollTop: 0
		}, 500)
	}), 150))
}));

jQuery(".count").html("Slides: " + jQuery(".sf-10-snap-container-<?php echo esc_js( $sf_slider_id ); ?>").length);

jQuery(".sf-10-snap-container-<?php echo esc_js( $sf_slider_id ); ?> li").click((function() {
	jQuery(".sf-10-snap-container-<?php echo esc_js( $sf_slider_id ); ?>").animate({
		scrollTop: jQuery(this).attr("data-slide-link") * jQuery(".sf-10-snap-container-<?php echo esc_js( $sf_slider_id ); ?> section").height() + 120
	}, 1500)
}));
</script>
