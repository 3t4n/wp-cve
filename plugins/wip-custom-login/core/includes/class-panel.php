<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if( !class_exists( 'wip_custom_login_panel' ) ) {

	class wip_custom_login_panel {

		/**
		 * Constructor
		 */

		public function __construct( $fields = array() ) {

			$this->panel_fields = $fields;
			$this->plugin_optionname = "wip_custom_login_settings";

			add_action('admin_menu', array(&$this, 'admin_menu') ,11);
			add_action('admin_init', array(&$this, 'add_script') ,11);
			add_action('admin_init', array(&$this, 'save_option') ,11);

		}

		/**
		 * Create option panel menu
		 */

		public function admin_menu() {

			global $admin_page_hooks;

            if ( !isset( $admin_page_hooks['tip_plugins_panel'] ) ) :

				add_menu_page(
					esc_html__('TIP Plugins', 'wip-custom-login'),
					esc_html__('TIP Plugins', 'wip-custom-login'),
					'manage_options',
					'tip_plugins_panel',
					NULL,
					plugins_url('/assets/images/tip-icon.png', dirname(__FILE__)),
					64
				);

			endif;

			add_submenu_page(
				'tip_plugins_panel',
				esc_html__('Custom Login', 'wip-custom-login'),
				esc_html__('Custom Login', 'wip-custom-login'),
				'manage_options',
				'wip_custom_login_panel',
				array(&$this, 'wip_custom_login_panel')
			);

			if ( isset( $admin_page_hooks['tip_plugins_panel'] ) )
				remove_submenu_page( 'tip_plugins_panel', 'tip_plugins_panel' );

		}

		/**
		 * Loads the plugin scripts and styles
		 */

		public function add_script() {

			global $wp_version, $pagenow;

			$file_dir = plugins_url('/assets/', dirname(__FILE__));

			wp_enqueue_style ( 'WIP_custom_login_notice', $file_dir.'css/notice.css' );

			if ( $pagenow == 'admin.php' ) {

				if( is_admin() )
					wp_enqueue_style( 'wp-color-picker' );

				wp_enqueue_style ( 'WIP_custom_login', $file_dir.'css/panel.css' );
				wp_enqueue_style ( 'WIP_custom_login_on_off', $file_dir.'css/on_off.css' );
				wp_enqueue_style ( 'WIP_custom_login_googlefonts', '//fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,600,600i,700,700i');

				wp_enqueue_media();
				wp_enqueue_script( 'jquery');
				wp_enqueue_script( "jquery-ui-core", array('jquery'));
				wp_enqueue_script( "jquery-ui-tabs", array('jquery'));
				wp_enqueue_script( 'WIP_custom_login_on_off', $file_dir.'js/on_off.js','3.5', '', TRUE);
				wp_enqueue_script( 'WIP_custom_login', $file_dir.'js/panel.js',array('jquery','media-upload','thickbox','wp-color-picker'),'1.0',TRUE );

			}

		}

		/**
		 * Message after the options saving
		 */

		public function save_message () {

			global $message_action;

			if (isset($message_action)) :

				echo '<div id="message" class="updated fade message_save WIP_custom_login_message"><p><strong>' . esc_html($message_action) . '</strong></p></div>';

			endif;

		}

		/**
		 * Sanitize background repeat function
		 */

		public function sanitize_background_repeat_function($k) {

			$allowedOptions = array(
				'',
				'repeat',
				'no-repeat',
				'repeat-x',
				'repeat-y',
			);

			if (in_array($k, $allowedOptions)) {

				return $k;

			} else {

				return 'repeat';

			}

		}

		/**
		 * Sanitize background position function
		 */

		public function sanitize_background_position_function($k) {

			$allowedOptions = array(
				'',
				'top left',
				'top center',
				'top right',
				'center',
				'bottom left',
				'bottom center',
				'bottom right',
			);

			if ( in_array($k, $allowedOptions)) {

				return $k;

			} else {

				return '';

			}

		}

		/**
		 * Sanitize background attachment function
		 */

		public function sanitize_background_attachment_function($k) {

			$allowedOptions = array(
				'normal',
				'fixed',
			);

			if ( in_array($k, $allowedOptions)) {

				return $k;

			} else {

				return 'normal';

			}

		}

		/**
		 * Sanitize charset function
		 */

		public function sanitize_charset_function($k) {

			$allowedOptions = array(
				'latin',
				'latin-ext',
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'vietnamese',
			);

			$count = 0;

			if (is_array($k)) :

				foreach ($k as $v) {

					if ( !in_array($v, $allowedOptions)) {
						$count++;
					}
				}

				return ($count == 0) ? $k : array();

			else:

				return array();

			endif;

		}

		/**
		 * Sanitize background attachment function
		 */

		public function sanitize_font_family_function($k) {

			$allowedOptions = wip_custom_login_get_font('', 'getlist');

			if (in_array($k, $allowedOptions)) {

				return $k;

			} else {

				return 'Montserrat';

			}

		}

		/**
		 * Sanitize loginbox position function
		 */

		public function sanitize_loginbox_position_function($k) {

			$allowedOptions = array(
				'left',
				'right',
				'center',
			);

			if ( in_array($k, $allowedOptions)) {

				return $k;

			} else {

				return 'center';

			}

		}

		/**
		* Multidimensional Array sanitize function
		*/

		public function array_sanitize_function($id, $value) {

			switch ($id) {

				default:
				case 'wip_custom_login_logo_width':
				case 'wip_custom_login_logo_height':
				case 'wip_custom_login_loginbox_width':

					$tosave = sanitize_text_field($value);

				break;

				case 'wip_custom_login_logo_url':
				case 'wip_custom_login_background_image':

					$tosave = esc_url_raw($value);

				break;

				case 'wip_custom_login_background_repeat':

					$tosave = $this->sanitize_background_repeat_function($value);

				break;

				case 'wip_custom_login_background_position':

					$tosave = $this->sanitize_background_position_function($value);

				break;

				case 'wip_custom_login_background_attachment':

					$tosave = $this->sanitize_background_attachment_function($value);

				break;

				case 'wip_custom_login_charset':

					$tosave = $this->sanitize_charset_function($value);

				break;

				case 'wip_custom_login_font':

					$tosave = $this->sanitize_font_family_function($value);

				break;

				case 'wip_custom_login_loginbox_position':

					$tosave = $this->sanitize_loginbox_position_function($value);

				break;

				case 'wip_custom_login_background_color':
				case 'wip_custom_login_loginbox_textcolor':
				case 'wip_custom_login_loginbox_linkcolor_hover':
				case 'wip_custom_login_loginbox_background':
				case 'wip_custom_login_loginbox_border':

					$tosave = sanitize_hex_color($value);

				break;

			}

			return $tosave;

		}

		/**
		 * Save options function
		 */

		public function save_option () {

			if (!is_user_logged_in())
				return;

			global $message_action;

			$wip_custom_login_setting = get_option( $this->plugin_optionname );

			if ( $wip_custom_login_setting != false ) :

				$wip_custom_login_setting = maybe_unserialize( $wip_custom_login_setting );

			else :

				$wip_custom_login_setting = array();

			endif;

			if (
				isset($_GET['action']) &&
				($_GET['action'] == 'wip_custom_login_backup_download') &&
				check_admin_referer( 'wip_custom_login_backup_download_backup', 'wip_backup_nonces')
			) {

				header("Cache-Control: public, must-revalidate");
				header("Pragma: hack");
				header("Content-Type: text/plain");
				header('Content-Disposition: attachment; filename="wip_custom_login_backup.dat"');
				echo serialize($this->get_options());
				exit;

			}

			if (
				isset($_GET['action']) &&
				($_GET['action'] == 'wip_custom_login_backup_reset') &&
				check_admin_referer( 'wip_custom_login_backup_reset_backup', 'wip_reset_nonces')
			) {

				update_option( $this->plugin_optionname,'');
				wp_redirect(admin_url('admin.php?page=wip_custom_login_panel&tab=Import_Export'));
				exit;

			}

			if (
				isset($_POST['wip_custom_login_upload_backup']) &&
				check_admin_referer('wip_custom_login_restore_options', 'wip_import_nonces')
			) {

				$file_ext = pathinfo($_FILES["wip_custom_login_upload_file"]['name'], PATHINFO_EXTENSION);

				if ($_FILES["wip_custom_login_upload_file"]["error"] <= 0 && $file_ext == 'dat') {

					$options = unserialize(file_get_contents($_FILES["wip_custom_login_upload_file"]["tmp_name"]));

					if ($options) {

						foreach ($options as $option) {

							foreach (unserialize($option->option_value) as $k => $v) {

								$toImport[$k] = $this->array_sanitize_function($k ,$v);

							}

						}

						update_option( $this->plugin_optionname, $toImport);

					}

					return;

				}

				wp_redirect(admin_url('admin.php?page=wip_custom_login_panel&tab=Import_Export'));
				exit;

			}

			if ( $this->wip_custom_login_request('wip_custom_login_action') == "Save" ) {

				if (
					!current_user_can('manage_options') || 
					!isset($_POST['wip_custom_login_save_nonces']) || 
					!wp_verify_nonce(esc_attr($_POST['wip_custom_login_save_nonces']), 'wip_custom_login_save_options' )
				) {
					exit;
				}

				foreach ( $this->panel_fields as $element ) {

					if ( isset($element['tab']) && $element['tab'] == $_GET['tab'] ) {

						foreach ($element as $value ) {

							if (isset($value['id']) ) {

								if (isset($_POST[$value["id"]]) ) :

									$current[$value['id']] = $this->array_sanitize_function($value['id'], $_POST[$value["id"]]);

								else :

									$current[$value["id"]] = "";

								endif;

								update_option( $this->plugin_optionname, array_merge( $wip_custom_login_setting  ,$current) );

							}

							$message_action = esc_html__('Options saved successfully.', 'wip-custom-login' );

						}

					}

				}

				if ( $_REQUEST['tab'] == "Form" ) {

					$googlefonts = get_option('wip_custom_login_fontlist');

					if ( ( !$googlefonts ) || ( $googlefonts <> wip_custom_login_fontlist() ) ){

						update_option( 'wip_custom_login_fontlist', wip_custom_login_fontlist() );

					}

				}

			}

		}

		/**
		 * Get options
		 */

		public function get_options() {

			global $wpdb;
			return $wpdb->get_results("SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name = '".$this->plugin_optionname."'");

		}

		/**
		 * Request function
		 */

		public function wip_custom_login_request($id) {

			if ( isset ( $_REQUEST[$id]) ) :
				return $_REQUEST[$id];
			endif;

		}

		/**
		 * Option panel
		 */

		public function wip_custom_login_panel() {

			global $message_action;

			if ( !isset($_GET['tab']) )  {

				$_GET['tab'] = "Logo";

			}

			foreach ( $this->panel_fields as $element) {

				if (isset($element['type'])) :

					switch ( $element['type'] ) {

						case 'navigation': ?>

							<div id="WIP_custom_login_tabs">

								<div id="WIP_custom_login_header">

									<div class="left"> <a href="<?php echo esc_url( 'https://www.themeinprogress.com'); ?>" target="_blank"><img src="<?php echo plugins_url('/assets/images/tip-logo.png', dirname(__FILE__) ); ?>" ></a> </div>
									<div class="plugin_description"> <h2 class="maintitle"> <?php echo esc_html__( 'WIP Custom Login','wip-custom-login'); ?> </h2> </div>

									<div class="clear"></div>

								</div>

								<?php $this->save_message(); ?>

								<ul>

									<?php

										foreach ($element['item'] as $option => $name ) {

											if (str_replace(" ", "", $option) == $_GET['tab'] ) {

												$class = "ui-state-active";

											} else {

												$class = "";

											}

											echo "<li class='".esc_attr($class)."''><a href='".esc_url( 'admin.php?page=wip_custom_login_panel&tab=' . str_replace(" ", "", $option))."'>".$name."</a></li>";

										}

										echo "<li><a target='_blank' href='".esc_url( get_site_url() . '/wp-login.php')."'>".esc_html__( 'Login page preview','wip-custom-login')."</a></li>";
										echo "<li><a target='_blank' href='".esc_url( 'https://www.themeinprogress.com/c-login-free-custom-login-wordpress-plugin/?aff=panel')."'>".esc_html__( 'Upgrade to PRO','wip-custom-login')."</a></li>";

									?>

									<li class="clear"></li>

								</ul>

							<?php

						break;

						case 'end-tab':  ?>

								<div class="clear"></div>

							</div>

					<?php

						break;

						case 'end-panel':

						break;

					}

				endif;

			if (isset($element['tab'])) :

				switch ( $element['tab'] ) {

					case $_GET['tab']:

						foreach ($element as $value) {

							if (isset($value['type'])) :

								switch ( $value['type'] ) {

								case 'start-form':  ?>

									<div id="<?php echo esc_attr(str_replace(" ", "", $value['name'])); ?>">

										<form method="post" enctype="multipart/form-data" action="<?php echo esc_url(get_site_url());?>/wp-admin/admin.php?page=wip_custom_login_panel&tab=<?php echo esc_attr($_GET['tab']); ?>">
											
                                            <input type="hidden" name="wip_custom_login_save_nonces" value="<?php echo esc_attr(wp_create_nonce( 'wip_custom_login_save_options' ));?>" />

								<?php break;

								case 'end-form':  ?>


                                    	</form>

									</div>

								<?php break;

								case 'start-container':

									if (
										'Save' == $this->wip_custom_login_request('wip_custom_login_action')  &&
										$value['val'] == $this->wip_custom_login_request('element-opened')
									) {
										$class=" inactive";
										$style='style="display:block;"';
									} else {
										$class="";
										$style='';
									}

									?>

									<div class="WIP_custom_login_container">

                                        <h5 class="element<?php echo esc_attr($class); ?>" id="<?php echo esc_attr($value['val']); ?>"><?php echo esc_html($value['name']); ?></h5>

                                        <div class="wip_mainbox">

								<?php break;

								case 'start-open-container':  ?>

									<div class="WIP_custom_login_container">

                                        <h5 class="element-open"><?php echo esc_html($value['name']); ?></h5>

                                        <div class="wip_mainbox wip_openbox">

								<?php break;

								case 'end-container':  ?>

										</div>

									</div>

								<?php break;

								case 'multioptions': ?>

									<div class="WIP_custom_login_box">

										<label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['name']); ?></label>

										<?php foreach ($value['options'] as $val => $option ) {

											$checked = '';

											if ( wip_custom_login_setting($value['id']) != false ) {

												foreach ( wip_custom_login_setting($value['id']) as $check ) {

												if ( $check == $val ) {

													$checked = 'checked="checked"';

												}

											}

										}

										?>

											<p>
                                                <input name="<?php echo esc_attr($value['id']); ?>[]" type="checkbox" value="<?php echo esc_attr($val); ?>" <?php echo esc_attr($checked); ?> />
                                                <?php echo esc_html($option); ?>
											</p>

										<?php } ?>

										<p><?php echo esc_html($value['desc']); ?></p>

									</div>

								<?php break;

								case 'pages': ?>

									<div class="WIP_custom_login_box">

                                        <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['name']); ?></label>

                                        <?php

											foreach ( $value['options'] as $page ) {

											$checked = '';

											if ( wip_custom_login_setting($value['id']) != false ) {

												foreach (wip_custom_login_setting($value['id']) as $check ) {

												if ($check == $page->ID )  { $checked ='checked="checked"'; } }

											}

										?>

                                        <p>
                                        	<input name="<?php echo esc_attr($value['id']); ?>[]" type="checkbox" value="<?php echo esc_attr($page->ID); ?>" <?php echo esc_attr($checked); ?> /> <?php echo esc_html($page->post_title); ?>
                                        </p>

                                        <?php } ?>

                                        <p><?php echo esc_html($value['desc']); ?></p>

									</div>

								<?php break;

								case 'text': ?>

									<div class="WIP_custom_login_box">

                                        <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['name']); ?></label>

                                        	<input name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" type="<?php echo esc_attr($value['type']); ?>"  value="<?php echo esc_attr(wip_custom_login_setting($value['id'],$value['std']));?>" />
                                          <p><?php echo esc_html($value['desc']); ?></p>

									</div>

								<?php break;

								case "upload": ?>

									<?php if (isset( $value['class'] )) { $classe = " ".$value['class']; } ?>

									<div class="WIP_custom_login_box <?php echo $classe; ?>">

                                        <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['name']); ?></label>
                                        <input id="<?php echo esc_attr($value['id']); ?>" type="text" name="<?php echo esc_attr($value['id']); ?>" class="upload_attachment" value="<?php echo esc_attr(wip_custom_login_setting($value['id'],$value['std']));?>" />
                                        <input type="button" name="upload_button" class="button upload_button" value="<?php esc_attr_e('Upload'); ?>" />
                                        <p><?php echo esc_html($value['desc']); ?></p>
                                        <?php if ( wip_custom_login_setting($value['id']) != "") { echo "<img src='".esc_url(wip_custom_login_setting($value['id']))."' class='upload-preview' alt='image'/>"; } ?>

									</div>

								<?php break;

								case 'form':  ?>

								<?php break;

								case 'navigation':

									echo esc_html($value['start']);
									foreach ($value['item'] as $option) {
										echo "<li><a href='#".str_replace(" ", "", esc_attr($option))."'>".esc_html($option)."</a></li>";
									}
									echo esc_html($value['end']);

								break;

								case 'textarea':  ?>

									<div class="WIP_custom_login_box">

                                        <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['name']); ?></label>
                                        <textarea name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" type="<?php echo esc_attr($value['type']); ?>" cols="" rows=""><?php echo esc_html(wip_custom_login_setting($value['id'], $value['std']));?></textarea>
                                        <p><?php echo esc_html($value['desc']); ?></p>

									</div>

								<?php break;

								case "on-off": ?>

									<div class="WIP_custom_login_box">

                                        <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['name']); ?></label>

                                            <div class="bool-slider <?php echo esc_attr(wip_custom_login_setting($value['id'], $value['std']));?>">
                                            <div class="inset"><div class="control"></div></div>
                                            <input name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" class="on-off" type="hidden" value="<?php echo esc_attr(wip_custom_login_setting($value['id'], $value['std']));?>" />

                                        </div>

                                        <div class="clear"></div>
                                        <p><?php echo esc_html($value['desc']); ?></p>

									</div>

								<?php break;

								case 'category': ?>

									<div class="WIP_custom_login_box">

                                        <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['name']); ?></label>


										<select name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>">
										<?php foreach ($value['options'] as $option) { ?> <option<?php if ( wip_custom_login_setting($value['id']) == get_cat_id($option)) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?> value="<?php echo esc_attr(get_cat_id($option)); ?>" ><?php echo esc_html($option); ?></option><?php } ?></select>
										<p><?php echo esc_html($value['desc']); ?></p>

									</div>

								<?php break;

								case 'select': ?>

									<div class="WIP_custom_login_box">

                                        <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['name']); ?></label>
                                        <select name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>">

                                            <?php foreach ( $value['options'] as $val => $option ) { ?>
                                              <option <?php if ( wip_custom_login_setting( $value['id'], $value['std'] ) == $val)  { echo 'selected="selected"'; } ?> value="<?php echo esc_attr($val); ?>"><?php echo esc_html($option); ?></option>
                                            <?php } ?>

                                        </select>

                                        <p><?php echo esc_html($value['desc']); ?></p>

									</div>

								<?php break;

								case "save-button": ?>

									<div class="WIP_custom_login_box">
                                        <input name="wip_custom_login_action" id="element-open" type="submit" value="<?php echo esc_attr($value['value']); ?>" class="button"/>
									</div>

								<?php break;

								case "color": ?>

									<div class="WIP_custom_login_box">

                                        <label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['name']); ?></label>
                                        <input type="text" name="<?php echo esc_attr($value['id']); ?>" id="<?php echo esc_attr($value['id']); ?>" value="<?php echo esc_attr(wip_custom_login_setting($value['id'], $value['std'])); ?>" data-default-color="<?php echo esc_attr($value['std']); ?>" class="WIP_custom_login_color"  />
                                        <p><?php echo esc_html($value['desc']); ?></p>

									</div>

								<?php break;

								case 'import_export': ?>

									<div class="WIP_custom_login_box">

									    <label><?php _e( "Current plugin settings","wip-custom-login"); ?></label>
										<p><textarea class="widefat code" rows="20" cols="100" onclick="this.select()"><?php echo esc_html(serialize($this->get_options())); ?></textarea></p>

										<?php

                                            $export_url = wp_nonce_url(
                                                '?page=wip_custom_login_panel&tab=Import_Export&action=wip_custom_login_backup_download',
                                                'wip_custom_login_backup_download_backup',
                                                'wip_backup_nonces'
                                            );

                                        ?>

										<a href="<?php echo esc_url( $export_url); ?>" class="button button-secondary"><?php _e( "Download current plugin settings","wip-custom-login"); ?></a>

                                        <div class="clear"></div>

									</div>

									<div class="WIP_custom_login_box">

									    <label><?php _e( "Reset plugin settings","wip-custom-login"); ?></label>

										<?php

                                            $reset_url = wp_nonce_url(
                                                '?page=wip_custom_login_panel&tab=Import_Export&action=wip_custom_login_backup_reset',
                                                'wip_custom_login_backup_reset_backup',
                                                'wip_reset_nonces'
                                            );

                                        ?>

										<a style="margin-left:0" href="<?php echo esc_url( $reset_url); ?>" class="button button-secondary"><?php _e( "Reset plugin settings","wip-custom-login"); ?></a>
									    <p><?php _e( "If you click the button above, the plugin options return to its default values","wip-custom-login"); ?></p>
									    <div class="clear"></div>

									</div>

									<div class="WIP_custom_login_box">

									    <label><?php _e( "Import plugin settings","wip-custom-login"); ?></label>
									    <input type="file" name="wip_custom_login_upload_file" />
									    <input type="submit" name="wip_custom_login_upload_backup" id="wip_custom_login_upload_backup" class="button-primary" value="<?php _e( "Import plugin settings","wip-custom-login"); ?>" />
									    <?php if (function_exists('wp_nonce_field')) wp_nonce_field('wip_custom_login_restore_options', 'wip_import_nonces'); ?>

									</div>

								<?php break;

								}

							endif;

						}

					}

				endif;

			}

		}

	}

}

?>
