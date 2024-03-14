<?php
if ( ! defined( 'CANVAS_DIR' ) ) {
	die();
}
class CanvasViews {


	public static function view( $template, $options = array() ) {
		$dir = dirname( dirname( __FILE__ ) ) . '/views/';

		$filename = $dir . $template . '.php';
		if ( file_exists( $filename ) ) {
			if ( ! empty( $options ) ) {
				extract( $options );
			}
			require_once $dir . '/header.php';
			require_once $filename;
			if ( isset( $_GET['first-time'] ) && CanvasAdmin::welcome_screen_is_avalaible() ) {
				require_once $dir . '/' . 'first_time.php';
			}
			require_once $dir . '/footer.php';
		}
	}

	public static function add_schedule_demo() {
		if ( CanvasAdmin::welcome_screen_is_avalaible() ) {
			$url = 'https://www.mobiloud.com/free-trial/?utm_source=' . CanvasAdmin::$utm_source . '&utm_medium=admin-notice';
			?>
			<div class="notice is-dismissible canvas-schedule-demo-block0">
				<div class="clear"></div>
				<div id="canvas_img_div0"><img src="<?php echo CANVAS_URL . 'assets/img/icon.png'; ?>"></div>
				<div id="canvas_text_div0">
					<p>Request a demo</p>
					<p>Find out if MobiLoud is a good fit, have your own app built and test it on your device.</p>
				</div>
				<div id="canvas_btn_div0">
					<a href="<?php echo esc_attr( $url ); ?>" class="button button-primary canvas-schedule-demo-btn">Request a demo</a>
				</div>
				<div class="clear"></div>
			</div>
			<style type="text/css">
				.canvas-schedule-demo-block0 {
					min-height: 100px;
					padding-left: 0px;
					padding-top: 0px;
					padding-bottom: 0px;
					border-left: 0px;
					display: table;
				}
				#canvas_img_div0 {
					width: 100px;
					margin: 0px 20px 0px 0px;
					display: table-cell;
					vertical-align: middle;
				}
				#canvas_img_div0 img{
					display: block;
					width: 100px;
					height: 100px;
					margin: 0px;
					padding: 0px;
					border-image-width: 0px;
				}
				#canvas_text_div0 {
					display: table-cell;
					vertical-align: middle;
					padding: 0px 20px;
				}
				#canvas_text_div0 > p {
					margin: 0px;
				}
				#canvas_text_div0 > p:first-child {
					font-size: large;
				}
				#canvas_btn_div0 {
					margin: 20px 0px 20px 20px;
					min-height: 60px;
					display: table-cell;
					vertical-align: middle;
				}
				#canvas_btn_div0 .canvas-schedule-demo-btn {
					background-color: #55b63b;
					box-shadow: 0 -3px 0 0 #489b32 inset;
					box-sizing: border-box;
					border: none;
					text-shadow: 0 -1px 1px #489b32, 1px 0 1px #489b32, 0 1px 1px #489b32, -1px 0 1px #489b32;
				}
				@media screen and (min-width: 375px) {
					#canvas_btn_div0 .canvas-schedule-demo-btn {
					font-size: 18px;
					height: 50px;
					line-height: 48px;
					padding: 0px 20px;
				}
				}
				@media screen and (max-width: 425px) {
					#canvas_img_div0 {
					display: none;
				}
				.canvas-schedule-demo-block0, #canvas_text_div0, #canvas_btn_div0 {
					display: block;
				}
				#canvas_btn_div0 {
					text-align: center;
				}
				#canvas_text_div0 {
					padding-top: 20px;
				}
				}
				@media screen and (min-width: 1024px) {
					#canvas_text_div0 {
					width: 90%;
				}
				}
			</style>
			<script type="text/javascript">
				(function($){
					$(document).on('ready', function(){
						$( this ).on('click', '.canvas-schedule-demo-block0 .notice-dismiss', function(){
							$.post(ajaxurl, { 'action':'canvas_schedule_dismiss', 't':Math.random()});
						})
					})
				})(jQuery)
			</script>
			<?php
		}
	}
	/**
	 * Rendering the image uploader for icon
	 *
	 * https://rudrastyh.com/wordpress/customizable-media-uploader.html
	 *
	 * @param string $id
	 * @param string $name
	 * @param int    $value
	 *
	 * @return string
	 */
	public static function render_image_uploader_field( $id, $name, $value = 0 ) {
		$image      = ' button">Upload image';
		$image_size = 'full';
		$display    = 'none';

		if ( $image_attributes = wp_get_attachment_image_src( $value, $image_size ) ) {
			// $image_attributes[0] - image URL
			// $image_attributes[1] - image width
			// $image_attributes[2] - image height

			$image   = '"><img src="' . esc_attr( $image_attributes[0] ) . '" style="max-width:150px;display:block;" />';
			$display = 'inline-block';
			
		}

		return '<div>
					<a href="#" class="canvas_upload_image_button' . $image . '</a>
					<input type="hidden" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="' . esc_attr( "$value" ) . '" />
					<a href="#" class="canvas_remove_image_button" style="display:inline-block;display:' . $display . '">Remove image</a>
				</div>';
	}
}
