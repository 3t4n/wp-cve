<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// slider settings
if ( isset( $slider['sf_9_width'] ) ) {
	$sf_9_width = $slider['sf_9_width'];
} else {
	$sf_9_width = '100%';
}
if ( isset( $slider['sf_9_height'] ) ) {
	$sf_9_height = $slider['sf_9_height'];
} else {
	$sf_9_height = '700px';
}
if ( isset( $slider['sf_9_auto_play'] ) ) {
	$sf_9_auto_play = $slider['sf_9_auto_play'];
} else {
	$sf_9_auto_play = 'true';
}
if ( isset( $slider['sf_9_sorting'] ) ) {
	$sf_9_sorting = $slider['sf_9_sorting'];
} else {
	$sf_9_sorting = 0;
}
?>
<div id="sf-9-<?php echo esc_attr( $sf_slider_id ); ?>">
	<div class="fullscreen-container-<?php echo esc_attr( $sf_slider_id ); ?> hidden-<?php echo esc_attr( $sf_slider_id ); ?>">
		<div class='fullscreen-div-<?php echo esc_attr( $sf_slider_id ); ?>'>
			<img class="remove-fullscreen-<?php echo esc_attr( $sf_slider_id ); ?>" src="<?php echo esc_url( plugin_dir_url( __DIR__ ).'layouts/assets/9/icons/remove_icon.webp' ); ?>" width="60" />
		</div>
	</div>

	<div id="sf-9-gallery-<?php echo esc_attr( $sf_slider_id ); ?>">
		<div id="slide-<?php echo esc_attr( $sf_slider_id ); ?>">
			<div class="counter-<?php echo esc_attr( $sf_slider_id ); ?>"></div>
			<a class="prev-<?php echo esc_attr( $sf_slider_id ); ?>">&#x2039;</a>
			<a class="next-<?php echo esc_attr( $sf_slider_id ); ?>">&#x203A;</a>
			<?php if ( $sf_9_auto_play == 'true' ) { ?>
			<img class="toggleDiapo-<?php echo esc_attr( $sf_slider_id ); ?>" src="<?php echo esc_url( plugin_dir_url( __DIR__ ). 'layouts/assets/9/icons/pause_diapo.png' ); ?>" width="40" />
			<?php }; ?>
			<?php if ( $sf_9_auto_play == 'false' ) { ?>
			<img class="toggleDiapo-<?php echo esc_attr( $sf_slider_id ); ?>" src="<?php echo esc_url( plugin_dir_url( __DIR__ ). 'layouts/assets/9/icons/play_diapo.png' ); ?>" width="40" />
			<?php }; ?>

			<img class="fullscreen-<?php echo esc_attr( $sf_slider_id ); ?>" src="<?php echo esc_url( plugin_dir_url( __DIR__ ). 'layouts/assets/9/icons/fullscreen.png' ); ?>" width="40" />
			<img id='preview-<?php echo esc_attr( $sf_slider_id ); ?>' />
		</div>

		<div class="caption-container-<?php echo esc_attr( $sf_slider_id ); ?>">
			<span id="caption-<?php echo esc_attr( $sf_slider_id ); ?>"></span>
		</div>

		<div id="thumbnails" style="text-align:center; display: none;">
			<div class="wrapper-<?php echo esc_attr( $sf_slider_id ); ?>">
				<?php
				// slide sorting start
				if ( $sf_9_sorting == 1 ) {
					// Slide ID Ascending (key Ascending)
					ksort( $slider['sf_slide_title'] );
				}
				if ( $sf_9_sorting == 2 ) {
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
						<img class="thumbnail-<?php echo esc_attr( $sf_slider_id ); ?>" src="<?php echo esc_url( $sf_slide_full_url[0] ); ?>" alt="<?php echo esc_attr( $sf_slide_title ); ?>">
						<?php
					}//end of for each
				} //end of count
				?>
			</div>
		</div>
	</div>
</div>

<style>
#sf-9-<?php echo esc_html( $sf_slider_id ); ?> {
	width: <?php echo esc_html( $sf_9_width ); ?> !important;
}

#img-<?php echo esc_html( $sf_slider_id ); ?> {

}

.caption-container-<?php echo esc_html( $sf_slider_id ); ?> {
	background-color: #000000;
	text-align: center;
	padding: 6px 8px;
	color : #ffffff;
	width: <?php echo esc_html( $sf_9_width ); ?> !important;
}

#thumbnails {
	white-space: nowrap;
	height: 20%;
	width: 100%;
}

.wrapper-<?php echo esc_html( $sf_slider_id ); ?> {
	position: relative;
	overflow: scroll;
	scroll-behavior: smooth;
	width: <?php echo esc_html( $sf_9_width ); ?> !important;
}

.wrapper-<?php echo esc_html( $sf_slider_id ); ?>::-webkit-scrollbar {
	display: none;
}

/* Hide scrollbar for IE and Edge */
.wrapper-<?php echo esc_html( $sf_slider_id ); ?> {
	-ms-overflow-style: none;
}

#slide-<?php echo esc_html( $sf_slider_id ); ?> {
	position: relative;
	overflow: hidden;
}

#preview-<?php echo esc_html( $sf_slider_id ); ?> {
	height: <?php echo esc_html( $sf_9_height ); ?> !important;
	width: <?php echo esc_html( $sf_9_width ); ?> !important;
	object-fit: fill; /*Object-fit properties are cover, contain, fit ,scale-down and none. */
}

.thumbnail-<?php echo esc_html( $sf_slider_id ); ?> {
	width: 170px;
	height: 140px;
	opacity: 0.5;
	display: unset !important;
}

.selected-<?php echo esc_html( $sf_slider_id ); ?> {
	opacity: 1;
}

.thumbnail-<?php echo esc_html( $sf_slider_id ); ?>:hover {
	opacity: 1;
}

#sf-9-gallery-<?php echo esc_html( $sf_slider_id ); ?> {
	width: 100%;
	margin: auto;
	padding: auto;
}

/* .caption-container {
	background-color :#252525;
	text-align: center;
	padding: 6px 8px;
	color : red;
} OLD CONTAINER CSS */

.prev-<?php echo esc_html( $sf_slider_id ); ?>,
.next-<?php echo esc_html( $sf_slider_id ); ?> {
	cursor: pointer;
	position: absolute;
	top: 50%;
	width: auto;
	padding: 16px;
	margin-top: -50px;
	color: white;
	font-weight: bold;
	font-size: 100px;
	border-radius: 0 3px 3px 0;
	user-select: none;
	-webkit-user-select: none;
}

.next-<?php echo esc_html( $sf_slider_id ); ?> {
	right: 0;
}

.prev-<?php echo esc_html( $sf_slider_id ); ?> {
	left: 0;
}

.toggleDiapo-<?php echo esc_html( $sf_slider_id ); ?> {
	position: absolute;
	bottom: 0;
	left: 50%;
	margin-left: -30px;
	font-size: 30px;
	color: goldenrod;
	font-weight: bold;
	cursor: pointer;
	padding: 5px;
	user-select: none;
	-webkit-user-select: none;
}

.fullscreen-<?php echo esc_html( $sf_slider_id ); ?> {
	position: absolute;
	top: 10px;
	right: 10px;
	cursor: pointer;
	user-select: none;
}

.prev-<?php echo esc_html( $sf_slider_id ); ?>:hover,
.next-<?php echo esc_html( $sf_slider_id ); ?>:hover,
.toggleDiapo-<?php echo esc_html( $sf_slider_id ); ?>:hover,
.fullscreen-<?php echo esc_html( $sf_slider_id ); ?>:hover {
	background-color: rgba(0, 0, 0, 0.8);
}

.counter-<?php echo esc_html( $sf_slider_id ); ?> {
	position: absolute;
	border-radius: 50%;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 50px;
	height: 50px;
	top: 1.4%;
	left: 0.9%;
	background-color: rgba(0, 0, 0, 0.8);
	border: 2px solid rgb(212, 207, 207);
	color: white;
	font: 1.5em Arial, sans-serif;
}

.fullscreen-container-<?php echo esc_html( $sf_slider_id ); ?> {
	position: fixed;
	top: 0;
	left: 0;
	bottom: 0;
	right: 0;
	z-index: 10 ;
	background-color: rgba(0, 0, 0, 0.9) ;
	display: block ;
}

.fullscreen-div-<?php echo esc_html( $sf_slider_id ); ?> {
	width: 100% !important; 
	height: 100% !important;
	display: block ;
	margin: auto;
	position: relative;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	box-sizing: content-box;
}

.hidden-<?php echo esc_html( $sf_slider_id ); ?> {
	display: none;
}

.counter-<?php echo esc_html( $sf_slider_id ); ?>,
.fullscreen-<?php echo esc_html( $sf_slider_id ); ?>,
.prev-<?php echo esc_html( $sf_slider_id ); ?>,
.next-<?php echo esc_html( $sf_slider_id ); ?>,
.toggleDiapo-<?php echo esc_html( $sf_slider_id ); ?> {
	z-index: 5;
}

.remove-fullscreen-<?php echo esc_html( $sf_slider_id ); ?> {
	position: absolute;
	top: 0;
	right: 0;
	cursor: pointer;
}

.remove-fullscreen-<?php echo esc_html( $sf_slider_id ); ?>:hover {
	background-color: rgba(0, 0, 0, 0.8);
}
</style>
<script>
window.myNameSpace = window.myNameSpace || {};

jQuery(function () {

	// load images
	jQuery.fn.addImage = function (filename, description) {
		var img = document.createElement('img');
		img.src = "images/" + filename;
		img.alt = description;
		img.className = "thumbnail-<?php echo esc_js( $sf_slider_id ); ?>";
		jQuery(this).append(img);
	}

	// check if element is hidden after scrollbar
	jQuery.fn.overflown = function () {
		var limitLeft = jQuery('.wrapper-<?php echo esc_js( $sf_slider_id ); ?>').offset().left;
		var limitRight = limitLeft + jQuery('.wrapper-<?php echo esc_js( $sf_slider_id ); ?>').width();
		var elemOffsetLeft = jQuery(this[0]).offset().left;
		var elemOffsetRight = elemOffsetLeft + jQuery(this[0]).width() / 2;
		return (elemOffsetRight > limitRight || elemOffsetLeft < limitLeft) ? true : false;
	}

	// scroll to the end of element
	function scrollToElement(el, direction) {
		element_width = el.width();
		scroll_left = jQuery('.wrapper-<?php echo esc_js( $sf_slider_id ); ?>')[0].scrollLeft;
		if (direction == 'next')
			jQuery('.wrapper-<?php echo esc_js( $sf_slider_id ); ?>')[0].scrollTo(scroll_left + element_width, 0);
		else if (direction == 'prev')
			jQuery('.wrapper-<?php echo esc_js( $sf_slider_id ); ?>')[0].scrollTo(scroll_left - element_width, 0);
	}

	function showNextImg() {
		clearInterval(interv);
		
		interv = setInterval(showNextImg, 4000);
		
		var el = jQuery('.selected-<?php echo esc_js( $sf_slider_id ); ?>');
		var counter = jQuery('.counter-<?php echo esc_js( $sf_slider_id ); ?>');
		if (el.next().length != 0) {
			counter.text(el.index() + 1);
			if (el.next().overflown())
				scrollToElement(el, 'next');
			//el.fadeOut(200, () => {
				el.next().trigger('click');
				el.show();
			//});
		}
		else {
			jQuery('.thumbnail-<?php echo esc_js( $sf_slider_id ); ?>:first').trigger('click');
			jQuery('.wrapper-<?php echo esc_js( $sf_slider_id ); ?>')[0].scrollTo(0, 0);
			counter.text('1');
		}
		jQuery('.toggleDiapo-<?php echo esc_js( $sf_slider_id ); ?>').attr('src', "<?php echo esc_url( plugin_dir_url( __DIR__ ). 'layouts/assets/9/icons/pause_diapo.png' ); ?>");
	}

	function showPrevImg() {
		clearInterval(interv);
		
		interv = setInterval(showNextImg, 4000);
		
		var el = jQuery('.selected-<?php echo esc_js( $sf_slider_id ); ?>');
		var counter = jQuery('.counter-<?php echo esc_js( $sf_slider_id ); ?>');
		if (el.prev().length != 0) {
			counter.text(el.index() + 1);
			if (el.prev().overflown())
				scrollToElement(el, 'prev');
			el.prev().trigger('click');
		}
		else {
			jQuery('.thumbnail-<?php echo esc_js( $sf_slider_id ); ?>:last').trigger('click');
			jQuery('.wrapper-<?php echo esc_js( $sf_slider_id ); ?>')[0].scrollTo(jQuery('.wrapper-<?php echo esc_js( $sf_slider_id ); ?>')[0].scrollWidth, 0);
			counter.text(jQuery('.thumbnail-<?php echo esc_js( $sf_slider_id ); ?>:last').index() + 1);
		}
		jQuery('.toggleDiapo-<?php echo esc_js( $sf_slider_id ); ?>').attr('src', "<?php echo esc_url( plugin_dir_url( __DIR__ ). 'layouts/assets/9/icons/pause_diapo.png' ); ?>");
	}

	function previewImg(e) {
		if (e.originalEvent !== undefined) {
			jQuery('.toggleDiapo-<?php echo esc_js( $sf_slider_id ); ?>').attr('src', "<?php echo esc_url( plugin_dir_url( __DIR__ ). 'layouts/assets/9/icons/play_diapo.png' ); ?>");
			interv = clearInterval(interv);
		}
		var wrapper = jQuery('.wrapper-<?php echo esc_js( $sf_slider_id ); ?>');
		var index = jQuery(this).index();
		jQuery('.selected-<?php echo esc_js( $sf_slider_id ); ?>').toggleClass('selected-<?php echo esc_js( $sf_slider_id ); ?>');
		jQuery(this).toggleClass('selected-<?php echo esc_js( $sf_slider_id ); ?>')
		jQuery("#caption-<?php echo esc_js( $sf_slider_id ); ?>").text(jQuery('.selected-<?php echo esc_js( $sf_slider_id ); ?>').attr('alt'));
		jQuery('.counter-<?php echo esc_js( $sf_slider_id ); ?>').text(index + 1);
		var src = jQuery(this).attr('src');
		jQuery('#preview-<?php echo esc_js( $sf_slider_id ); ?>').fadeOut(300, () => {
			jQuery('#preview-<?php echo esc_js( $sf_slider_id ); ?>').attr('src', src);
			jQuery('#preview-<?php echo esc_js( $sf_slider_id ); ?>').fadeIn(300);
		});
	}

	function toggleDiapo() {
		interv = (interv != null) ? clearInterval(interv) : setInterval(showNextImg, 4000);
		var src = jQuery('.toggleDiapo-<?php echo esc_js( $sf_slider_id ); ?>').attr('src');
		if (src == "<?php echo esc_url( plugin_dir_url( __DIR__ ). 'layouts/assets/9/icons/play_diapo.png' ); ?>")
			jQuery('.toggleDiapo-<?php echo esc_js( $sf_slider_id ); ?>').attr('src', "<?php echo esc_url( plugin_dir_url( __DIR__ ). 'layouts/assets/9/icons/pause_diapo.png' ); ?>");
		else
			jQuery('.toggleDiapo-<?php echo esc_js( $sf_slider_id ); ?>').attr('src', "<?php echo esc_url( plugin_dir_url( __DIR__ ). 'layouts/assets/9/icons/play_diapo.png' ); ?>");
	}

	function goFullscreen() {
		jQuery('.toggleDiapo-<?php echo esc_js( $sf_slider_id ); ?>').attr('src', "<?php echo esc_url( plugin_dir_url( __DIR__ ). 'layouts/assets/9/icons/remove_icon.webp' ); ?>");
		interv = clearInterval(interv);

		var selected = jQuery('.selected-<?php echo esc_js( $sf_slider_id ); ?>').attr('src');
		var container = jQuery('.fullscreen-container-<?php echo esc_js( $sf_slider_id ); ?>');
		jQuery('.fullscreen-div-<?php echo esc_js( $sf_slider_id ); ?>').css({
			'background-image': 'url(' + selected + ')',
			'background-size': 'contain',
			'background-repeat': 'no-repeat',
			'background-position': 'center'
		});
		container.fadeIn('slow');
		container.on('click', function () {
			jQuery(this).fadeOut('slow');
		});
	}

	// show first image
	var first = jQuery('.thumbnail-<?php echo esc_js( $sf_slider_id ); ?>:first').toggleClass('selected-<?php echo esc_js( $sf_slider_id ); ?>');
	jQuery('.counter-<?php echo esc_js( $sf_slider_id ); ?>').text('1');
	jQuery('#preview-<?php echo esc_js( $sf_slider_id ); ?>').attr('src', first.attr('src'));
	jQuery("#caption-<?php echo esc_js( $sf_slider_id ); ?>").text(first.attr('alt'));
	// start auto diapo
	var interv;
	<?php if ( $sf_9_auto_play == 'true' ) { ?>
	interv = setInterval(showNextImg, 4000);
	<?php }; ?>
	<?php if ( $sf_9_auto_play == 'false' ) { ?>
	interv = null;
	<?php }; ?>

	// setup event listeners
	jQuery('.next-<?php echo esc_js( $sf_slider_id ); ?>').on('click', showNextImg);
	jQuery('.prev-<?php echo esc_js( $sf_slider_id ); ?>').on('click', showPrevImg);
	jQuery('.thumbnail-<?php echo esc_js( $sf_slider_id ); ?>').on('click', previewImg);
	jQuery('.toggleDiapo-<?php echo esc_js( $sf_slider_id ); ?>').on('click', toggleDiapo);
	jQuery('.fullscreen-<?php echo esc_js( $sf_slider_id ); ?>').on('click', goFullscreen);

});
</script>
