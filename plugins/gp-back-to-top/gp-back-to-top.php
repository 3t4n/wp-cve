<?php
/*
 * Plugin Name: GP Back To Top
Plugin URI: http://wordpress.org/plugins/gp-back-to-top
Description: Create Back To Top Button Custom.
Author: Mai Dong Giang (Peter Mai)
Author URI: https://www.facebook.com/giangmd
Version: 3.0
Liciense: GPL2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/ 


/*-------------------------------------*\
*		Name: Mai Đông Giang			*
*		Skype: giangmd93				*
*		Phone: (+84) 935 114 817		*
*		Email: giangmd93@gmail.com		*
\*-------------------------------------*/

class GP_Back_To_Top
{
	const VERSION = '2.0';
	protected $fr_file, $ad_file, $d_w, $d_h, $d_fz, $d_bgr, $d_cl, $d_pd, $d_bt, $d_rt;

	function __construct()
	{
		register_deactivation_hook( __FILE__, array( $this, 'uninstall' ) );

		$this->fr_file = 'gp-bttp';
		$this->ad_file = 'gp-admin';
		$this->d_w = 35;
		$this->d_h = 35;
		$this->d_fz = 20;
		$this->d_bgr = '#111f1c';
		$this->d_cl = '#ffffff';
		$this->d_pd = 5;
		$this->d_bt = 45;
		$this->d_rt = 20;
		$this->keyBO = 'gp_btt_bocss';
		$this->keyFO = 'gp_btt_focss';

		add_action( 'admin_menu', array( $this, 'gp_create_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'gp_bttb_enqueue_admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'gp_bttp_enqueue_scripts' ) );
	}

	function uninstall() {
        require_once( plugin_dir_path( __FILE__ ) . 'inc/uninstall.php' );
    }

	public function gp_create_menu() {
		add_options_page( "GP Back To Top", "GP Back To Top", 'manage_options', 'gp-back-to-top', array($this, 'gp_create_options' ) );
	}

	public function gp_bttb_enqueue_admin_scripts() {
		wp_enqueue_style( 'gp_bttb_admin_style', plugins_url( '/css/style.css', __FILE__ ), array(), self::VERSION );

		$ad_file_p = (!empty(get_option( $this->keyBO ))) ? (dirname(__FILE__) . '/css/'.get_option( $this->keyBO ).'.css') : (dirname(__FILE__) . '/css/'.$this->ad_file.'.css');
		$get_file_p = (!empty(get_option( $this->keyBO ))) ? get_option( $this->keyBO ) : $this->ad_file;
		$file_ad = filesize( $ad_file_p );
		if ( $file_ad > 0 ) {
			wp_enqueue_style( 'style-modified', plugins_url( '/css/'.$get_file_p.'.css', __FILE__ ), array(), self::VERSION );
		}

		wp_register_script( 'gp-bttb-js', plugins_url( '/js/main.js', __FILE__), array( 'jquery' ), self::VERSION );
		wp_enqueue_script( 'gp-bttb-js' );
	}

	public function gp_bttp_enqueue_scripts() {
		$foFileSaved = (!empty(get_option( $this->keyFO ))) ? (plugins_url( '/css/'.get_option( $this->keyFO ).'.css', __FILE__ )) : (plugins_url( '/css/'.$this->fr_file.'.css', __FILE__ ));
		
		wp_register_style( 'gp-bttp-style', $foFileSaved, array(), self::VERSION );
    	wp_enqueue_style( 'gp-bttp-style' );

    	wp_register_script( 'gp-bttp-jquery', plugins_url( '/js/'.$this->fr_file.'.js', __FILE__ ), array('jquery'), self::VERSION, true );
    	wp_enqueue_script( 'gp-bttp-jquery' );
	}

	public function gp_create_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __('Permissions denied to access.') );
		}
	?>
		<div class="wrap">
			<h2>GP Back To Top Plugin</h2>
			<form action="" method="POST" class="gpbttb-form">
				<div class="form-group">
					<label for="width">Width: </label>
					<input type="number" min="1" max="100" name="width" id="width" value="<?php echo $this->d_w; ?>">
				</div>
				<div class="form-group">
					<label for="height">Height: </label>
					<input type="number" min="1" max="100" name="height" id="height" value="<?php echo $this->d_h; ?>">
				</div>
				<div class="form-group">
					<label for="font">Font-size: </label>
					<input type="number" min="1" max="100" name="font" id="font" value="<?php echo $this->d_fz; ?>">
				</div>
				<div class="form-group">
					<label for="bg_color">Background color: </label>
					<input type="color" name="bg_color" id="bg_color" value="<?php echo $this->d_bgr; ?>">
				</div>
				<div class="form-group">
					<label for="color">Color: </label>
					<input type="color" name="color" id="color" value="<?php echo $this->d_cl; ?>">
				</div>
				<div class="form-group">
					<label for="bottom">Bottom: </label>
					<input type="number" min="1" max="100" name="bottom" id="bottom" value="<?php echo $this->d_bt; ?>">
				</div>
				<div class="form-group">
					<label for="right">Right: </label>
					<input type="number" min="1" max="100" name="right" id="right" value="<?php echo $this->d_rt; ?>">
				</div>
				<div class="form-group">
					<input type="submit" name="gp_bttb_up" value="Submit">
				</div>
			</form>
			<p>
				<div class="gp-back-to-top" id="gpToTop"><span></span></div>
			</p>
			<script type="text/javascript">
				(function ($) {
					$(document).ready(function() {
						var hexDigits = new Array
				        ("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f"); 

						//Function to convert hex format to a rgb color
						function rgb2hex(rgb) {
						 	rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
						 	return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
						}
						function hex(x) {
						  	return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
						}

						var demo = $('.gp-back-to-top'),
							width = $('.gpbttb-form').find('#width'),
							height = $('.gpbttb-form').find('#height'),
							font = $('.gpbttb-form').find('#font'),
							bg_color = $('.gpbttb-form').find('#bg_color'),
							color = $('.gpbttb-form').find('#color'),
							bottom = $('.gpbttb-form').find('#bottom'),
							right = $('.gpbttb-form').find('#right');

						function updateStyle() {
							width.val( demo.css('width').replace("px", '') );
							height.val( demo.css('height').replace("px", '') );
							font.val( demo.css('font-size').replace("px", '') );
							bg_color.val( rgb2hex(demo.css('background-color')) );
							color.val( rgb2hex(demo.css('color')) );
						}

						updateStyle();
					});
				})(jQuery);
			</script>
		</div>
	<?php
		if ( isset($_POST['gp_bttb_up']) ) {

			$width = ( !empty($_POST['width']) ) ? (int) strip_tags( trim($_POST['width']) ) : $this->d_w;
			$height = ( !empty($_POST['height']) ) ? (int) strip_tags( trim($_POST['height']) ) : $this->d_h;
			$font = ( !empty($_POST['font']) ) ? (int) strip_tags( trim($_POST['font']) ) : $this->d_fz;
			$bg_color = ( !empty($_POST['bg_color']) ) ? strip_tags( trim($_POST['bg_color']) ) : $this->d_bgr;
			$color = ( !empty($_POST['color']) ) ? strip_tags( trim($_POST['color']) ) : $this->d_cl;
			$bottom = ( !empty($_POST['bottom']) ) ? (int) strip_tags( trim($_POST['bottom']) ) : $this->d_bt;
			$right = ( !empty($_POST['right']) ) ? (int) strip_tags( trim($_POST['right']) ) : $this->d_rt;

			$txt = "/*
					 * Style GP Back To Top Plugin
					 *
					 * @author Giang Peter
					 */
					@import url(\"font.css\");
					.gp-back-to-top {
						display: none;
						width: ".$width."px;
						height: ".$height."px;
						border-radius: 50%;
						padding: 5px;
						background-color: ".$bg_color.";
						color: ".$color.";
						text-align: center;
						position: fixed;
						z-index: 99999;
						bottom: ".$bottom."px;
						right: ".$right."px;
						font-size: ".$font."px;
						cursor: pointer;
						-webkit-box-sizing: content-box;
					    -moz-box-sizing: content-box;
					    box-sizing: content-box;
					}
					.gp-back-to-top span {
						position: absolute;
						top: 24%;
						left: 50%;
						-webkit-transform: translateX(-50%);
						-moz-transform: translateX(-50%);
						-ms-transform: translateX(-50%);
						-o-transform: translateX(-50%);
						transform: translateX(-50%);
						font-family: 'Glyphicons Halflings';
					    line-height: 1;
					    -webkit-font-smoothing: antialiased;
					    -moz-osx-font-smoothing: grayscale;
					}
					.gp-back-to-top span:before {
					    content: '\\e113';
					}";

			$boFileToSaved = 'bo-'.date('Y-m-d').'-'.microtime();
			$file_bo = dirname(__FILE__) . '/css/'.$boFileToSaved.'.css';
			file_put_contents($file_bo, $txt);

			if ( (empty(get_option( $this->keyBO ))) ) {
				add_option( $this->keyBO, $boFileToSaved, '', 'yes' );
			} else {
				$fileDel = dirname(__FILE__) . '/css/'.get_option( $this->keyBO ).'.css';
				unlink($fileDel);
				update_option( $this->keyBO, $boFileToSaved );
			}
			

			$foFileToSaved = 'fo-'.date('Y-m-d').'-'.microtime();
			$file_fo = dirname(__FILE__) . '/css/'.$foFileToSaved.'.css';
			file_put_contents($file_fo, $txt);

			if ( (empty(get_option( $this->keyFO ))) ) {
				add_option( $this->keyFO, $foFileToSaved, '', 'yes' );
			} else {
				$fileDel = dirname(__FILE__) . '/css/'.get_option( $this->keyFO ).'.css';
				unlink($fileDel);
				update_option( $this->keyFO, $foFileToSaved );
			}
			?>
			<script type="text/javascript">window.location.reload(true);</script>
			<?php
		}
	}
}

$gp_back_to_top = new GP_Back_To_Top();