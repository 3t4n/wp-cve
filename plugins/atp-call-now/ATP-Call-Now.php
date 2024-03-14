<?php

/*
 * Plugin Name: ATP Call Now
 * Plugin URI: https://atpsoftware.vn/
 * Description: Show button Call Now on your website (support desktop and mobile).
 * Version: 1.0.3
 * Author: ATP Software
 * Author URI: https://atpsoftware.vn/
 * Domain Path: /languages/
 * Text Domain: atp-cn
 */

if ( ! class_exists('ATPCallNow') ) :
	class ATPCallNow {
		public function __construct() {
			//Backend
			add_action( 'admin_enqueue_scripts', array($this, 'ATP_CN_admin_scripts_and_styles') );
			add_action( 'plugin_action_links_' . plugin_basename(__FILE__), array($this, 'ATP_CN_settings_link') );
			add_action( 'admin_menu', array($this, 'ATP_CN_create_menu') );
			add_filter( 'plugin_row_meta', array( $this, 'ATP_CN_add_plugin_links' ), 10, 2 );
			add_action( 'admin_init', array($this, 'ATP_CN_register_settings') );
			
			//Frontend
			add_action( 'wp_enqueue_scripts', array($this, 'ATP_CN_scripts_and_styles'));
			add_action( 'wp_footer', array($this, 'ATP_Call_Now'));
		}
		public function ATP_CN_settings_link($links){
			$settings_url = admin_url('admin.php?page=ATP-Call-Now');
			$settings_link = '<a href="' . $settings_url . '">'.__('Settings','atp-cn').'</a>';
			array_unshift($links, $settings_link);
			return $links;
		}
		public function ATP_CN_add_plugin_links($links, $file) {
			if ( $file == plugin_basename(dirname(__FILE__).'/ATP-Call-Now.php') ) { $links[] = '<a href="https://atpsoftware.vn/plugin/atp-call-now" target="_blank">' . esc_html__('Documents', 'atp-cn') . '</a>'; }
			return $links;
		}
		public function ATP_CN_scripts_and_styles() {
			wp_deregister_style( 'atp-cn-style-css' );
			wp_register_style( 'atp-cn-style-css', plugins_url( '/css/style.css', __FILE__ ));
			wp_enqueue_style( 'atp-cn-style-css' );
		}
		public function ATP_CN_admin_scripts_and_styles() {
			wp_enqueue_media(); //run wp.media button 
			wp_register_script( 'atp-pa-script-js', plugins_url('/js/script.js', __FILE__ ), array('jquery') );
			wp_enqueue_script( 'atp-pa-script-js' );
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_style( 'wp-color-picker' );
		}
		public function ATP_CN_get_version() {
			$plugin_data = get_plugin_data( __FILE__ );
			$plugin_version = $plugin_data['Version'];
			return $plugin_version;
		}
		public function ATP_CN_register_settings() {
			register_setting( 'ATP_CN_settings_group', 'atpcn_page_id');	register_setting( 'ATP_CN_settings_group', 'atpcn_link' );
			register_setting( 'ATP_CN_settings_group','atpcn_text');		register_setting( 'ATP_CN_settings_group', 'atpcn_left_right' );
			register_setting( 'ATP_CN_settings_group','atpcn_bottom_top');	register_setting('ATP_CN_settings_group', 'atpcn_hide_pc' );
			register_setting( 'ATP_CN_settings_group', 'atpcn_hide_mb');	register_setting( 'ATP_CN_settings_group', 'atpcn_image_url');
			register_setting( 'ATP_CN_settings_group', 'atpcn_size');		register_setting( 'ATP_CN_settings_group', 'atpcn_long');
			register_setting( 'ATP_CN_settings_group', 'atpcn_color');		register_setting( 'ATP_CN_settings_group', 'atpcn_color_bg');
			register_setting( 'ATP_CN_settings_group', 'atpcn_color_text');
		}
		public function ATP_CN_create_menu() {
			add_menu_page(__('ATP Call Now Settings','atp-cn'),__('ATP Call Now','atp-cn'), 'manage_options', 'ATP-Call-Now', array( $this, 'ATP_CN_settings_page' ), plugins_url('/img/telephone-20x20.png', __FILE__), 30);
		}
		public function ATP_CN_settings_page() {

			if( isset($_GET['settings-updated']) ) { echo '<div id="message" class="updated notice is-dismissible"><p><strong>'.__('Saved!','atp-cn').'</strong></p></div>';}
			echo '<div class="atp-call-now wrap">
			<h1>'.__('ATP Call Now Settings','atp-cn').' <small style="font-size:60%;color:#888;margin-left:5px;">('.__('Version','atp-cn').' '.$this->ATP_CN_get_version().')</small></h1>
			<form method="post" action="options.php">';
			settings_fields( 'ATP_CN_settings_group' );
			echo '<table class="form-table">
			<tr>
			<th><label for="atpcn_page_id">'.__('IDs pages need show Hotline','atp-cn').'</label></th>
			<td><input type="text" name="atpcn_page_id" id="atpcn_page_id" class="regular-text input-text-wrap" value="'.get_option('atpcn_page_id').'" placeholder="5530,1702 (hiện tất cả các trang nếu để trống)" /></td>
			</tr>
			<!--<tr>
			<th><label for="atpcn_not_page_id">'.__('IDs pages need hide Hotline','atp-cn').'</label></th>
			<td><input type="text" name="atpcn_not_page_id" id="atpcn_not_page_id" class="regular-text input-text-wrap" value="'.get_option('atpcn_not_page_id').'" placeholder="5530,1702" /></td>
			</tr>-->
			<tr>
			<th><label for="atpcn_link">'.__('Link when click','atp-cn').'</label></th>
			<td><input type="text" name="atpcn_link" id="atpcn_link" class="regular-text input-text-wrap" value="'.get_option('atpcn_link').'" placeholder="tel:0931999911" /></td>
			</tr>
			<tr>
			<th><label for="atpcn_image_url">'.__('Url icon Call Now','atp-cn').'</label></th>
			<td><div><a href="javascript:voi(0)" class="button button-default" id="atpcn_upload_button">Chọn icon</a></div><input type="text" name="atpcn_image_url" id="atpcn_image_url" class="regular-text input-text-wrap" value="'.get_option('atpcn_image_url').'" placeholder="https://atpsoftware.vn/img/telephone.png" /></td>
			</tr>
			<tr>
			<th><label for="atpcn_size">'.__('Size Call Now (px)','atp-cn').'</label></th>
			<td><input type="number" name="atpcn_size" id="atpcn_size" class="regular-text input-text-wrap" value="'.get_option('atpcn_size').'" placeholder="Mặc định là 50" /></td>
			</tr>
			<tr>
			<th><label for="atpcn_color">'.__('Color Call Now','atp-cn').'</label></th>
			<td><input type="text" name="atpcn_color" id="atpcn_color" value="'.get_option('atpcn_color').'" class="color-picker regular-text input-text-wrap" placeholder="#0084FF" /></td>
			</tr>
			<tr>
			<th><label for="atpcn_text">'.__('Text','atp-cn').'</label></th>
			<td><input type="text" name="atpcn_text" id="atpcn_text" class="regular-text input-text-wrap" value="'.get_option('atpcn_text').'" placeholder="Đặt mua" /></td>
			</tr>
			<tr>
			<th><label for="atpcn_color_bg">'.__('Background text','atp-cn').'</label></th>
			<td><input type="text" name="atpcn_color_bg" id="atpcn_color_bg" value="'.get_option('atpcn_color_bg').'" class="color-picker regular-text input-text-wrap" placeholder="#ffff00" /></td>
			</tr>
			<tr>
			<th><label for="atpcn_color_text">'.__('Color text','atp-cn').'</label></th>
			<td><input type="text" name="atpcn_color_text" id="atpcn_color_text" value="'.get_option('atpcn_color_text').'" class="color-picker regular-text input-text-wrap" placeholder="#000000" /></td>
			</tr>
			<tr>
			<th><label for="atpcn_left_right">'.__('Show Left or Right<br>(default Left)','atp-cn').'</label></th>
			<td><select name="atpcn_left_right" id="atpcn_left_right"><option value="left">Bên trái</option><option value="right">Bên phải</option></select></td>
			</tr>
			<tr>
			<th><label for="atpcn_bottom_top">'.__('Show Bottom or Top<br>(default Bottom)','atp-cn').'</label></th>
			<td><select name="atpcn_bottom_top" id="atpcn_bottom_top"><option value="top">Bên trên</option><option value="bottom">Bên dưới</option></select></td>
			</tr>
			<tr>
			<th><label for="atpcn_long">'.__('Distance from the border (px)','atp-cn').'</label></th>
			<td><input type="number" name="atpcn_long" id="atpcn_long" class="regular-text input-text-wrap" value="'.get_option('atpcn_long').'" placeholder="Mặc định là 25" /></td>
			</tr>
			<tr>
			<th><label for="atpcn_mb_pc">'.__('Setting Other','atp-cn').'</label></th>
			<td>';?>
			<label for="atpcn_hide_pc"><input type="checkbox" name="atpcn_hide_pc" value="1" id="atpcn_hide_pc" <?php if(get_option('atpcn_hide_pc')) echo "checked"; ?>> <?php echo __('Hide on Desktop','atp-cn');?></label> <br><label for="atpcn_hide_mb"><input type="checkbox" name="atpcn_hide_mb" value="1" id="atpcn_hide_mb" <?php if(get_option('atpcn_hide_mb')) echo "checked"; ?>> <?php echo __('Hide on mobile','atp-cn');?></label>
			<?php echo '</td></tr>
			</table>';
			submit_button();
			echo '</form>';?>
			<script type="text/javascript">
				document.getElementById('atpcn_left_right').value = "<?php echo get_option('atpcn_left_right');?>";
				document.getElementById('atpcn_bottom_top').value = "<?php echo get_option('atpcn_bottom_top');?>";
			</script>
			<?php echo '</div><!-- /.wrap -->';
		}
		public function ATP_Call_Now() {
			$atpcn_hide_pc = get_option('atpcn_hide_pc');$atpcn_hide_mb = get_option('atpcn_hide_mb');
			$atpcn_link = get_option("atpcn_link");$atpcn_image_url = get_option("atpcn_image_url");
			$atpcn_text = get_option("atpcn_text");$atpcn_size = get_option("atpcn_size");
			$atpcn_left_right = get_option("atpcn_left_right");$atpcn_bottom_top = get_option("atpcn_bottom_top");
			$atpcn_long = get_option("atpcn_long");$atpcn_color = get_option("atpcn_color");
			$atpcn_color_bg = get_option("atpcn_color_bg");$atpcn_color_text = get_option("atpcn_color_text");

			if ($atpcn_link == "" || $atpcn_link == NULL) { $atpcn_link = "0931999911"; }
			if ($atpcn_image_url == "" || $atpcn_image_url == NULL) { $atpcn_image_url = plugins_url('/img/telephone.png', __FILE__); }
			if ($atpcn_text == "" || $atpcn_text == NULL) { $atpcn_text = "Đặt mua"; }
			if ($atpcn_size == "" || $atpcn_size == NULL) { $atpcn_size = 50; }
			if ($atpcn_color == "" || $atpcn_color == NULL) { $atpcn_color = "#0089B9"; }
			if ($atpcn_color_bg == "" || $atpcn_color_bg == NULL) { $atpcn_color_bg = "#ffff00"; }
			if ($atpcn_color_text == "" || $atpcn_color_text == NULL) { $atpcn_color_text = "#000000"; }
			if ($atpcn_left_right == "" || $atpcn_left_right == NULL) { $atpcn_left_right = "left"; }
			if ($atpcn_bottom_top == "" || $atpcn_bottom_top == NULL) { $atpcn_bottom_top = "bottom"; }
			if ($atpcn_long == "" || $atpcn_long == NULL) { $atpcn_long = 25; }
			if($atpcn_hide_pc == 1) { $atpcn_show_hide_pc = "none"; }
			else { $atpcn_show_hide_pc = "block"; }
			if($atpcn_hide_mb == 1) { $atpcn_show_hide_mb = "none"; }
			else { $atpcn_show_hide_mb = "block"; }

			$atpcn_page_id = esc_attr (get_option ( 'atpcn_page_id' ));
			if ( ! empty( $atpcn_page_id ) ) { 
				$atpcn_page_id = explode( ',', $atpcn_page_id );
				if ( is_page( $atpcn_page_id ) ) { 
					echo '<style type="text/css">.atp-call { display: block; }</style>';
				} else {
					echo '<style type="text/css">.atp-call.pc.mb { display: none; }</style>';
				}
			}
			?>
			<div class="atp-call pc mb">
				<a href="<?php echo $atpcn_link;?>" rel="nofollow">
					<div class="animated infinite zoomIn atp-vong"></div>
					<div class="animated infinite pulse atp-tron"></div>
					<div class="animated infinite tada atp-phone"></div>
					<div class="atp-text"><p><?php echo $atpcn_text;?></p></div>
				</a>
			</div>
			<style type="text/css">
			@media (max-width: 780px) {
				.mb {
					display: <?php echo $atpcn_show_hide_mb; ?>;
				}
			}
			@media (min-width: 780px) {
				.pc {
					display: <?php echo $atpcn_show_hide_pc; ?>;
				}
			}
			.atp-vong {
				<?php echo $atpcn_bottom_top; ?>: <?php echo $atpcn_long."px"; ?>;
				<?php echo $atpcn_left_right; ?>: 10px;
				width: <?php echo ($atpcn_size+60)."px"; ?>;
				height: <?php echo ($atpcn_size+60)."px"; ?>;
				border-color: <?php echo $atpcn_color; ?>;
			}

			.atp-tron {
				<?php echo $atpcn_bottom_top; ?>: <?php echo ($atpcn_long+15)."px"; ?>;
				<?php echo $atpcn_left_right; ?>: 25px;
				width: <?php echo ($atpcn_size+30)."px"; ?>;
				height: <?php echo ($atpcn_size+30)."px"; ?>;
				background-color: <?php echo $atpcn_color."80"; ?>;
			}
			.atp-phone {
				<?php echo $atpcn_bottom_top; ?>: <?php echo ($atpcn_long+30)."px"; ?>;
				<?php echo $atpcn_left_right; ?>: 40px;
				width: <?php echo $atpcn_size."px"; ?>;
				height: <?php echo $atpcn_size."px"; ?>;
				background-image: url(<?php echo $atpcn_image_url;?>);
				background-color: <?php echo $atpcn_color; ?>;
			}
			.atp-text {
				<?php echo $atpcn_bottom_top; ?>: <?php if($atpcn_bottom_top == 'top') {echo ($atpcn_long+80)."px";} else {echo ($atpcn_long+5)."px";}?>;
				<?php echo $atpcn_left_right; ?>: 20px;
				background-color: <?php echo $atpcn_color_bg; ?>;
				color: <?php echo $atpcn_color_text; ?>;
			}
		</style> 
	<?php }
}
new ATPCallNow;
endif;