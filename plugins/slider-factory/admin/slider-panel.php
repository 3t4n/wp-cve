<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;} // Exit if accessed directly ?>

<div class="p-3 m-3 sf-panel">
	<input type="hidden" class="form-control item-menu" name="sf_slider_id" id="sf_slider_id" value="<?php echo esc_attr( $sf_slider_id ); ?>">
	<input type="hidden" class="form-control item-menu" name="sf_upload_nonce" id="sf_upload_nonce" value="<?php echo esc_attr( wp_create_nonce( 'sf-upload-nonce' ) ); ?>">
	<input type="hidden" class="form-control item-menu" name="sf_slider_layout" id="sf_slider_layout" value="<?php echo esc_attr( $sf_slider_layout ); ?>">
	<!-- slider header start -->
	<div class="row px-3">
		<div class="col-md-12">
			<h2 class='py-2 sf-title'>
			<?php
			$sf_allowed_title = array( 'code' => array() );
			echo wp_kses( $sf_slider_heading, $sf_allowed_title );
			?>
			</h2>
		</div>
	</div>
	<!-- slider header end -->
	
	<!-- slider title start -->
	<div class="row mt-2 p-3">
		<div class="col-md-12">
			<h3 class="sf-panel-heading bg-dark bg-gradient p-3"><?php esc_html_e( 'Slider Title', 'slider-factory' ); ?></h3>
		</div>
		<div class="col-md-12">
			<div class="p-3 sf-panel-setting">
				<input type="text" class="form-control item-menu w-50" name="sf_slider_title" id="sf_slider_title" placeholder="<?php esc_attr_e( 'Type A Name Of The Slider', 'slider-factory' ); ?>" value="<?php echo esc_attr( $sf_slider_title ); ?>">
				<div id="sf-1-width-help" class="form-text sf-tooltip"><?php esc_html_e( 'Give a name or title to the slider.', 'slider-factory' ); ?></div>
			</div>
		</div>
	</div>
	<!-- slider title end -->
	
	<!-- slide image upload start -->
	<div class="row mt-2 p-3">
		<div class="col-md-12">
			<h3 class="sf-panel-heading bg-dark bg-gradient p-3"><?php esc_html_e( 'Upload Image Slides', 'slider-factory' ); ?></h3>
		</div>
		
		<div class="col-md-6 text-center py-2">
			<div class="p-3 sf-panel-setting">
			<div class="d-grid gap-2 x-auto">
				<button type="button" id="sf-upload-slides" class="btn btn-success bg-gradient btn-lg p-3 fs-1" style="background-color: #80b918; border-color: #80b918;"><i class="fas fa-plus"></i> <?php esc_html_e( 'Add New Image Slides', 'slider-factory' ); ?></button>
			</div>
			</div>
		</div>
		<div class="col-md-6 text-center py-2">
			<div class="p-3 sf-panel-setting">
			<div class="d-grid gap-2 mx-auto">
				<button type="button" class="btn btn-danger bg-gradient btn-lg p-3 fs-1" onclick="return WpfrankSFremoveAllSlides();" style="background-color: #e76f51; border-color: #e76f51;"><i class="fas fa-trash"></i> <?php esc_html_e( 'Remove All Image Slides', 'slider-factory' ); ?></button>
			</div>
			</div>
		</div>
	</div>
	<!-- slide image upload end -->
	
	<div id="sf-slides-help" class="form-text sf-tooltip px-3">
		<strong><?php esc_html_e( 'Drag and Drop image slides to rearrange their position.', 'slider-factory' ); ?></strong><br><strong><?php esc_html_e( 'Note', 'slider-factory' ); ?>: </strong><?php esc_html_e( 'We recommend to use the same dimension images for better result.', 'slider-factory' ); ?>
	</div>
	
	<script>
	jQuery(document).ready(function () {
		// enable sortable slider box
		jQuery( function() {
			jQuery( "#sf-slides" ).sortable();
		});
	});
	</script>
	<div id="sf-slides" class="row sf-slides p-3 m-3 sf-panel-setting">
		<?php
		// load sides
		if ( isset( $slider['sf_slide_id'] ) ) {
			foreach ( $slider['sf_slide_id'] as $sf_id_1 ) {
				// defaults
				$sf_slide_title = $sf_slide_alt = $sf_slide_descs = $sf_slide_thumbnail = '';
				// load values
				$attachment_id  = $sf_id_1;
				$sf_slide_title = get_the_title( $attachment_id );
				$sf_slide_alt   = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
				// wp_get_attachment_image_src ( int $attachment_id, string|array $size = 'thumbnail', bool $icon = false )
				// thumb, thumbnail, medium, large, post-thumbnail
				$sf_slide_thumbnail = wp_get_attachment_image_src( $attachment_id, 'full', true ); // attachment medium URL
				$attachment         = get_post( $attachment_id );
				$sf_slide_descs     = $attachment->post_content; // attachment description
				?>
				<div class="sf-slide-column col-md-4 col-lg-4 col-xl-2 my-2 sf_slide_<?php echo esc_attr( $attachment_id ); ?>" data-position="<?php echo esc_attr( $attachment_id ); ?>">
					<div id="sf-slide-box" class="p-2 text-center shadow">
						<img class="img-fluid" src="<?php echo esc_url( $sf_slide_thumbnail[0] ); ?>" style="height: 200px;">
						<input type="text" class="form-control mt-1 sf_slide_id" name="sf_slide_id[<?php echo esc_attr( $attachment_id ); ?>]" value="<?php echo esc_attr( $attachment_id ); ?>" readonly>
						<input type="text" class="form-control mt-1 sf_slide_title" name="sf_slide_title[<?php echo esc_attr( $attachment_id ); ?>]" placeholder="<?php esc_attr_e( 'Slide Title', 'slider-factory' ); ?>" value="<?php echo esc_attr( $sf_slide_title ); ?>">
						<textarea class="form-control mt-1 sf_slide_desc" name="sf_slide_desc[<?php echo esc_attr( $attachment_id ); ?>]" placeholder="<?php esc_attr_e( 'Slide Description', 'slider-factory' ); ?>"><?php echo esc_textarea( $sf_slide_descs ); ?></textarea>
						<input type="text" class="form-control mt-1 sf_slide_alt_text" name="sf_slide_alt_text[<?php echo esc_attr( $attachment_id ); ?>]" placeholder="<?php esc_attr_e( 'Slide Image SEO Text', 'slider-factory' ); ?>" value="<?php echo esc_attr( $sf_slide_alt ); ?>">
						<button type="button" class="form-control btn btn-danger mt-1" style="background-color: #e76f51; border-color: #e76f51;" onclick="return WpfrankSFremoveSlide('<?php echo esc_attr( $attachment_id ); ?>');" name="sf_slide_remove"><?php esc_html_e( 'Remove Slide', 'slider-factory' ); ?></button>
					</div>
				</div>
				<?php
			}//end of for each
		} //end of count
		?>
	</div>
	<!-- slide image upload end -->
	
	<script>
	function URISSortSlides(order){
		if(order == "ASC") {
			jQuery(".SortSlides li").sort(sort_li).appendTo('.SortSlides');
			function sort_li(a, b) {
				return (jQuery(b).data('position')) > (jQuery(a).data('position')) ? 1 : -1;
			}
		}
		if(order == "DESC") {
			jQuery(".SortSlides li").sort(sort_li).appendTo('.SortSlides');
			function sort_li(a, b) {
				return (jQuery(b).data('position')) < (jQuery(a).data('position')) ? 1 : -1;
			}
		}
	}
	</script>
	
	<!-- slider settings start -->
	<div class="row mt-5 p-3">
		<div class="col-md-12">
			<h3 class="sf-panel-heading bg-dark bg-gradient p-3"><?php esc_html_e( 'Slider Setting For Layout', 'slider-factory' ); ?> <?php echo esc_html( $sf_slider_layout ); ?></h3>
			<?php
			if ( $sf_slider_layout == 1 ) {
				include 'settings/1.php';
			}
			if ( $sf_slider_layout == 2 ) {
				include 'settings/2.php';
			}
			if ( $sf_slider_layout == 3 ) {
				include 'settings/3.php';
			}
			if ( $sf_slider_layout == 4 ) {
				include 'settings/4.php';
			}
			if ( $sf_slider_layout == 5 ) {
				include 'settings/5.php';
			}
			if ( $sf_slider_layout == 6 ) {
				include 'settings/6.php';
			}
			if ( $sf_slider_layout == 7 ) {
				include 'settings/7.php';
			}
			if ( $sf_slider_layout == 8 ) {
				include 'settings/8.php';
			}
			if ( $sf_slider_layout == 9 ) {
				include 'settings/9.php';
			}
			if ( $sf_slider_layout == 10 ) {
				include 'settings/10.php';
			}
			if ( $sf_slider_layout == 11 ) {
				include 'settings/11.php';
			}
			if ( $sf_slider_layout == 12 ) {
				include 'settings/12.php';
			}
			?>
		</div>
		<div class="col-md-12 py-3">
			<div class="p-3 sf-panel-setting">
				<div id="sf-slider-process" class="spinner-grow m-3 text-dark d-none" role="status">
					<span class="visually-hidden"><?php esc_html_e( 'Loading...', 'slider-factory' ); ?></span>
				</div>
				<button type="button" id="sf-save-slider" class="btn btn-success bg-gradient btn-lg fs-2 m-3" style="background-color: #e76f51; border-color: #e76f51;"><strong><i class="fas fa-save"></i> <?php echo esc_html( $sf_slider_button_text ); ?></strong></button>
				
				<!-- slider shortcode start -->
				<div id="sf-shortcode-content" class="py-3 m-3 
				<?php
				if ( $sf_slider_action == 'create' ) {
					echo esc_attr( 'd-none' );}
				?>
				">
					<?php $shortcode = '[sf id=' . esc_html( $sf_slider_id ) . ' layout=' . esc_html( $sf_slider_layout ) . ']'; ?>
					<input type="text" class="btn btn-lg fs-2" id="sf-slider-shortcode-text" value="<?php echo esc_attr( $shortcode ); ?>">
					<button type="button" id="sf-copy-slider-shortcode-btn" class="btn btn-success bg-gradient btn-lg fs-2" onclick="return WpfrankSFCopyShortcode();" style="background-color: #e76f51; border-color: #e76f51;"><i class="fas fa-copy"></i> <?php esc_html_e( 'Click To Copy Shortcode', 'slider-factory' ); ?></button>
					<button id="sf-copied" class="btn btn-lg btn-light d-none"><?php esc_html_e( 'Shortcode Copied', 'slider-factory' ); ?></button>
				</div>
				<!-- slider shortcode end -->
			</div>
		</div>
	</div>
	<!-- slider settings end -->
	
	<!-- Buy PRO Bottom Button start -->
	<div class="row p-3">
		<div class="col-md-12 text-center py-2">
			
				<a href="https://wpfrank.com/account/signup/slider-factory-pro" target="_blank" id="slider-demo" class="btn btn-success bg-gradient btn-lg p-3 fs-1 col-md-6" style="background-color: #56b8c5; border-color: #56b8c5;">
					<i class="fas fa-shopping-cart"></i> <?php esc_html_e( 'Upgrade to Pro', 'slider-factory' ); ?>
				</a>
		
		</div>
	</div>
	<!-- Buy PRO Bottom Button end -->
</div>
<script>
// copy shortcode to clipboard for creating slider
function WpfrankSFCopyShortcode() {
	/* Get the text field */
	var copyShortcode = document.getElementById('sf-slider-shortcode-text');
	console.log(copyShortcode);
	copyShortcode.select();
	document.execCommand('copy');

	//fade in and out copied message
	jQuery('#sf-copied').removeClass('d-none');
	jQuery('#sf-copied').fadeIn('2000', 'linear');
	jQuery('#sf-copied').fadeOut(3000,'swing');
}

// remove single image slide
function WpfrankSFremoveSlide(id) {
	console.log('sf_slide_' + id);
	jQuery('.sf_slide_' + id).fadeOut(700, function() {
		jQuery('.sf_slide_' + id).remove();
	});
}

// remove all image slides
function WpfrankSFremoveAllSlides() {
	jQuery('.col-md-4').fadeOut(700, function() {
		jQuery('.col-md-4').remove();
	});
}

// print range call back
function WpfrankSFprintRange(id, value){
	//console.log(id + value);
	field_name = '#' + id + '-value';
	//console.log(field_name);
	jQuery(field_name).text(value);
}
</script>
