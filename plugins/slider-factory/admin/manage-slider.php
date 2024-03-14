<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wp_enqueue_script( 'jquery' );
wp_enqueue_script( 'jquery-ui-sortable' );
wp_enqueue_script( 'media-upload' );
wp_enqueue_media();
wp_enqueue_style( 'wp-color-picker' );
wp_enqueue_script( 'wp-color-picker' );
wp_enqueue_script( 'sf-uploader-js', plugin_dir_url( __FILE__ ) . 'assets/js/sf-uploader.js', array( 'jquery' ), '1.0.0' );

global $wpdb;
$sf_table_name = $wpdb->prefix . 'sf_sliders';

if ( isset( $_GET['sf-slider-action'] ) && isset( $_GET['sf-slider-layout'] ) ) {

	$sf_slider_action = sanitize_text_field( wp_unslash( $_GET['sf-slider-action'] ) );
	$sf_slider_layout = sanitize_text_field( wp_unslash( $_GET['sf-slider-layout'] ) );

	// creating new slider start
	if ( $sf_slider_action == 'create' ) {
		if ( current_user_can( 'manage_options' ) ) {
			if ( sanitize_text_field( wp_unslash( isset(  $_GET['sf-create-nonce'] ) ) ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['sf-create-nonce'] ) ), 'sf-create-nonce' ) ) {
				$sf_slider_heading     = __( 'Creating Slider With Layout', 'slider-factory' ) . ' ' . $sf_slider_layout;
				$sf_slider_button_text = __( 'Save Slider', 'slider-factory' );
				// generate slider id
				$sf_slider_id    = get_sf_slider_id();
				$sf_slider_title = '';
				$slider          = array();
			} else {
				echo esc_html_e( 'Nonce not verified action.', 'slider-factory' );
				die;
			}
		}
	}
	// creating new slider end

	// editing existing slider start
	if ( $sf_slider_action == 'edit' ) {
		if ( current_user_can( 'manage_options' ) ) {
			if ( sanitize_text_field( wp_unslash( isset( $_GET['sf-edit-nonce'] ) ) ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['sf-edit-nonce'] ) ), 'sf-edit-nonce' ) ) {
				$sf_slider_id = sanitize_text_field( wp_unslash( $_GET['sf-slider-id'] ) );
				$shortcode    = '[sf id=' . $sf_slider_id . ' layout=' . $sf_slider_layout . ']';
				// load slider content
				$slider          = get_option( 'sf_slider_' . $sf_slider_id );
				$sf_slider_title = $slider['sf_slider_title'];
				// print_r($slider);
				$sf_slider_heading     = __( 'Editing Slider Shortcode' ) . " <code>$shortcode</code> " . __( 'Build With Layout', 'slider-factory' ) . ' <code>' . $sf_slider_layout . '</code>';
				$sf_slider_button_text = __( 'Update Slider', 'slider-factory' );
			} else {
				echo esc_html_e( 'Nonce not verified action.', 'slider-factory' );
				die;
			}
		}
	}
	// editing existing slider start

	include 'slider-panel.php';

} else {

	global $wpdb;
	$sf_options_table_name = "{$wpdb->prefix}options";
	$slider_key            = 'sf_slider_';
	$all_sliders        = $wpdb->get_results(
		$wpdb->prepare( "SELECT option_name FROM $wpdb->options WHERE `option_name` LIKE %s ORDER BY option_id ASC", '%' . $slider_key . '%' )
	);
	//print_r($all_sf_sliders);
	?>
	<div class="m-3">
	<table class="table pr-3">
		<thead class="table-dark">
			<tr>
				<th scope="col"><?php esc_html_e( 'Title', 'slider-factory' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Shortcode', 'slider-factory' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Action', 'slider-factory' ); ?></th>
				<th scope="col" class="text-center"><input type="checkbox" id="sf-select-all" title="Select All Sliders"></th>
			</tr>
		</thead>
		<tbody id="sf-tbody">
			<?php
			$sf_create_nonce = wp_create_nonce( 'sf-create-nonce' );
			$sf_edit_nonce = wp_create_nonce( 'sf-edit-nonce' );
			if ( $wpdb->num_rows ) {
				$sf_counter    = 1;
				foreach ( $all_sliders as $slider ) {
					$slider_key        = $slider->option_name;
					$sf_underscore_pos = strrpos( $slider_key, '_' );
					$sf_slider_id      = substr( $slider_key, ( $sf_underscore_pos + 1 ) );

					// load slider data
					$slider = get_option( 'sf_slider_' . $sf_slider_id );
					// print_r($slider);
					if ( isset( $slider['sf_slider_id'] ) ) {
						$sf_slider_id = $slider['sf_slider_id'];
					} else {
						$sf_slider_id = '';
					}
					if ( isset( $slider['sf_slider_title'] ) ) {
						$sf_slider_title = $slider['sf_slider_title'];
					} else {
						$sf_slider_title = '';
					}
					if ( isset( $slider['sf_slider_layout'] ) ) {
						$sf_slider_layout = $slider['sf_slider_layout'];
					} else {
						$sf_slider_layout = '';
					}
					$sf_slider_shortcode = '[sf id=' . $sf_slider_id . ' layout=' . $sf_slider_layout . ']';
					if ( $sf_slider_id && $sf_slider_layout ) {
						?>
			<tr id="<?php echo esc_attr( $sf_slider_id ); ?>">
				<td><?php echo esc_html( $sf_slider_title ); ?></td>
				<td>
					<input type="text" id="sf-slider-shortcode-<?php echo esc_attr( $sf_slider_id ); ?>" class="btn btn-info btn-sm" value="<?php echo esc_attr( $sf_slider_shortcode ); ?>">
					<button type="button" id="sf-copy-shortcode-<?php echo esc_attr( $sf_slider_id ); ?>" class="btn btn-info btn-sm" title="<?php esc_html_e( 'Click To Copy Slider Shortcode', 'slider-factory' ); ?>" onclick="return WpfrankSFCopyShortcode('<?php echo esc_attr( $sf_slider_id ); ?>');"><?php esc_html_e( 'Copy', 'slider-factory' ); ?></button>
					<button class="btn btn-sm btn-success d-none sf-copied-<?php echo esc_attr( $sf_slider_id ); ?>"><?php esc_html_e( 'Copied', 'slider-factory' ); ?></button>
				</td>
				<td>
					<button type="button" id="sf-clone-slider" class="btn btn-warning btn-sm" title="<?php esc_html_e( 'Clone Slider', 'slider-factory' ); ?>" value="<?php esc_attr( $sf_slider_id ); ?>" onclick="return WpfrankSFCloneSlider('<?php echo esc_attr( $sf_slider_id ); ?>', '<?php echo esc_attr( $sf_counter ); ?>');"><i class="fas fa-copy"></i></button>
					<a href="admin.php?page=sf-manage-slider&sf-slider-action=edit&sf-slider-id=<?php echo esc_attr( $sf_slider_id ); ?>&sf-slider-layout=<?php echo esc_attr( $sf_slider_layout ); ?>&sf-edit-nonce=<?php echo esc_attr( $sf_edit_nonce ); ?>" id="sf-edit-slider" class="btn btn-warning btn-sm" title="<?php esc_html_e( 'Edit Slider', 'slider-factory' ); ?>"><i class="fas fa-edit"></i></a>
					<button id="sf-delete-slider" class="btn btn-warning btn-sm" title="<?php esc_html_e( 'Delete Slider', 'slider-factory' ); ?>" value="<?php echo esc_attr( $sf_slider_id ); ?>" onclick="return WpfrankSFremoveSlider('<?php echo esc_attr( $sf_slider_id ); ?>', 'single');"><i class="fas fa-trash-alt"></i></button>
				</td>
				<td class="text-center">
					<input type="checkbox" id="sf-slider-id" name="sf-slider-id" value="<?php echo esc_attr( $sf_slider_id ); ?>" title="<?php esc_html_e( 'Select Slider Shortcode', 'slider-factory' ); ?>">
				</td>
			</tr>
						<?php
						$sf_counter++;
					}
				} // end of for each
			} else {
				?>
				<tr>
					<td colspan="4" class="text-center">
						<div class="alert alert-danger mt-2">
						<?php esc_html_e( 'Sorry! No slider created yet.', 'slider-factory' ); ?>
						</div>
						<a class="btn btn-danger bg-gradient" style="background-color: #e76f51; border-color: #e76f51;" href="admin.php?page=sf-manage-slider&amp;sf-slider-action=create&amp;sf-slider-layout=1&amp;sf-create-nonce=<?php echo esc_attr($sf_create_nonce); ?>">
						<i class="fas fa-plus"></i> Create New Slider</a>
						
					</td>
				</tr>
				<?php
			}
			?>
		</tbody>
		<thead class="table-dark">
			<tr>
				<th scope="col"><?php esc_html_e( 'Title', 'slider-factory' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Shortcode', 'slider-factory' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Action', 'slider-factory' ); ?></th>
				<th scope="col" class="text-center"><button type="button" id="sf-delete-selected" class="btn btn-danger btn-sm" title="<?php esc_html_e( 'Delete Selected Sliders', 'slider-factory' ); ?>" onclick="return WpfrankSFremoveSlider('', 'multiple');"><i class="fas fa-trash-alt"></i></button></th>
			</tr>
		</thead>
	</table>
	</div>
	<script>
	// copy shortcode to clipboard for slider list (when user comes directly to manage slider)
	function WpfrankSFCopyShortcode(id) {
		/* Get the text field */
		var copyShortcode = document.getElementById('sf-slider-shortcode-' + id);
		copyShortcode.select();
		document.execCommand('copy');

		//fade in and out copied message
		jQuery('.sf-copied-' + id).removeClass('d-none');
		jQuery('.sf-copied-' + id).fadeIn('2000', 'linear');
		jQuery('.sf-copied-' + id).fadeOut(3000,'swing');
	}

	// clone slide start
	function WpfrankSFCloneSlider(sf_slider_id, sf_slider_counter){
		console.log(sf_slider_id + sf_slider_counter);
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				'action': 'sf_clone_slider', //this is the name of the AJAX method called in WordPress
				'nonce': '<?php echo esc_js( wp_create_nonce( "sf-clone-slider" ) ); ?>',
				//slider info
				'sf_slider_id': sf_slider_id,
				'sf_slider_counter': sf_slider_counter,
			}, 
			success: function (result) {
				//alert(result);
				jQuery('tbody#sf-tbody').append(result);
			},
			error: function () {
			}
		});
	}
	// clone slide end


	//select all sliders
	jQuery('#sf-select-all').click(function () {
		jQuery('input:checkbox').not(this).prop('checked', this.checked);
	});
	// remove slider/sliders start
	function WpfrankSFremoveSlider(sf_slider_id, do_action){
		console.log(sf_slider_id);
		if(do_action == 'multiple'){
			var sf_slider_id = [];
			jQuery('input:checkbox[name=sf-slider-id]:checked').each(function() { 
				sf_slider_id.push(jQuery(this).val());
				//hide selected table row on multiple slider delete
				jQuery('tr#' + jQuery(this).val()).fadeOut('1500');
				//delay after fadeOut table row
				jQuery(function() {
					setTimeout(function() {
						jQuery('tr#' + jQuery(this).val()).remove();
					}, 1000);
				});
			});
		}
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {
				'action': 'sf_remove_slider', //this is the name of the AJAX method called in WordPress
				'do_action': do_action, //this is the name of the AJAX method called in WordPress
				'nonce': '<?php echo esc_js( wp_create_nonce( 'sf-remove-slider' ) ); ?>',
				//slider info
				'sf_slider_id': sf_slider_id,
			}, 
			success: function (result) {
				//hide table row on slide slider delete
				if(do_action == 'single'){
					jQuery('tr#' + sf_slider_id).fadeOut('1500');
					
					//delay after fadeOut table row
					jQuery(function() {
						setTimeout(function() {
							jQuery('tr#' + sf_slider_id).remove();
						}, 1000);
					});
				}
			},
			error: function () {
			}
		});
	}
	// remove slider/sliders end
	</script>
	<?php
}