<?php
/**
 * Plugin Name: Cresta Help Chat
 * Plugin URI: https://crestaproject.com/downloads/cresta-help-chat/
 * Description: <strong>*** <a href="https://crestaproject.com/downloads/cresta-help-chat/?utm_source=plugin_whatsapp&utm_medium=description_meta" target="_blank">Get Cresta Help Chat PRO</a> ***</strong> Allow your users and customers to contact you via WhatsApp with a single click.
 * Version: 1.3.2
 * Author: CrestaProject - Rizzo Andrea
 * Author URI: https://crestaproject.com
 * Domain Path: /languages
 * Text Domain: cresta-whatsapp-chat
 * License: GPL2
 */
 
/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
 
define( 'CRESTA_WHATSAPP_CHAT_PLUGIN_VERSION', '1.3.2' );
add_action('admin_menu', 'cresta_whatsapp_chat_menu');
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'cresta_whatsapp_chat_setting_link' );
add_filter('plugin_row_meta', 'cresta_whatsapp_chat_meta_links', 10 , 2 );
add_action('plugins_loaded', 'crestawhatsappplugin_textdomain' );
add_action('admin_init', 'register_cresta_whatsapp_chat_settings' );
add_action('wp_enqueue_scripts', 'crestawhatsappplugin_front_enqueue_scripts');
add_action('admin_enqueue_scripts', 'crestawhatsappplugin_admin_enqueue_scripts');

require_once( dirname( __FILE__ ) . '/cresta-whatsapp-chat-metabox.php' );

function crestawhatsappplugin_textdomain() {
	load_plugin_textdomain( 'cresta-whatsapp-chat', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

function cresta_whatsapp_chat_menu() {
	global $cresta_whatsapp_options_page;
	$cresta_whatsapp_options_page = add_options_page(
		esc_html__( 'Cresta Help Chat Settings','cresta-whatsapp-chat'),
		esc_html__( 'Cresta Help Chat','cresta-whatsapp-chat'),
		'manage_options',
		'cresta-whatsapp-chat.php',
		'cresta_whatsapp_chat_option',
		81 
	);
}

function cresta_whatsapp_chat_setting_link($links) { 
	$settings_link = array(
		'<a href="' . admin_url('options-general.php?page=cresta-whatsapp-chat.php') . '">' . esc_html__( 'Settings','cresta-whatsapp-chat') . '</a>',
	);
	return array_merge( $links, $settings_link );
}

function cresta_whatsapp_chat_meta_links( $links, $file ) {
	if ( strpos( $file, 'cresta-whatsapp-chat.php' ) !== false ) {
		$new_links = array(
			'<a style="color:#39b54a;font-weight:bold;" href="https://crestaproject.com/downloads/cresta-help-chat/?utm_source=plugin_whatsapp&utm_medium=upgrade_meta" target="_blank" rel="external" ><span class="dashicons dashicons-megaphone"></span> ' . esc_html__( 'Upgrade to PRO', 'cresta-whatsapp-chat' ) . '</a>', 
		);
		$links = array_merge( $links, $new_links );
	}
	return $links;
}

/* Plugin enqueue style and script */
function crestawhatsappplugin_front_enqueue_scripts() {
	if ( !is_admin() ) {
		wp_enqueue_style( 'cresta-whatsapp-chat-front-style', plugins_url('css/cresta-whatsapp-chat-front-css.min.css',__FILE__), array(), CRESTA_WHATSAPP_CHAT_PLUGIN_VERSION);
	}
}

/* Plugin enqueue admin style and script */
function crestawhatsappplugin_admin_enqueue_scripts( $hook ) {
	global $cresta_whatsapp_options_page;
	if ( $hook == $cresta_whatsapp_options_page ) {
		wp_enqueue_style( 'cresta-whatsapp-chat-admin-style', plugins_url('css/cresta-whatsapp-chat-admin-css.css',__FILE__), array(), CRESTA_WHATSAPP_CHAT_PLUGIN_VERSION);
	}
}

/* Register Settings */
function register_cresta_whatsapp_chat_settings() {
	register_setting( 'cwcplugin', 'crestawhatsappchat_settings','crestawhatsappchat_options_validate' );
	$cwc_options_arr = array(
		'cresta_whatsapp_chat_choose_using' => 'wNumber',
		'cresta_whatsapp_chat_phone_number' => '',
		'cresta_whatsapp_chat_id_group' => '',
		'cresta_whatsapp_chat_id_open' => '_blank',
		'cresta_whatsapp_chat_box_text' => 'Cresta Help Chat',
		'cresta_whatsapp_chat_box_default_text' => 'Hi there! Use this box to send me a message via WhatsApp...',
		'cresta_whatsapp_chat_box_send' => 'Send via WhatsApp',
		'cresta_whatsapp_chat_zindex' => '999',
		'cresta_whatsapp_chat_show_floating_box' => '1',	
		'cresta_whatsapp_chat_selected_page' => 'homepage,blogpage,post,page',
		'cresta_whatsapp_chat_mobile_option' => 'onBoth',
		'cresta_whatsapp_chat_click_to_close' => '',
		'cresta_whatsapp_chat_click_button_open' => 'popup'
	);
	add_option( 'crestawhatsappchat_settings', $cwc_options_arr );
}

/* CSS Code filter to header */ 
function cresta_whatsapp_chat_css_top() {
	$cwc_options = get_option( 'crestawhatsappchat_settings' );
	$whatsapp_zindex = $cwc_options['cresta_whatsapp_chat_zindex'];
	echo "<style id='cresta-help-chat-inline-css'>";
	echo ".cresta-whatsapp-chat-box, .cresta-whatsapp-chat-button {z-index:". intval($whatsapp_zindex + 1) ."}";
	echo ".cresta-whatsapp-chat-container-button {z-index:". intval($whatsapp_zindex) ."}";
	echo ".cresta-whatsapp-chat-container {z-index:". intval($whatsapp_zindex + 1) ."}";
	echo ".cresta-whatsapp-chat-overlay {z-index:". intval($whatsapp_zindex - 1) ."}";
	echo "</style>";
}
add_action('wp_head', 'cresta_whatsapp_chat_css_top');

/* Cresta WhatsApp Chat shortcode */
function cresta_whatsapp_chat_shortcode( $atts ) {
    extract(shortcode_atts( array(
        'text' => 'Need Help? Contact Me!',
		'icon' => 'yes',
		'position' => 'top',
		'use' => 'number',
		'number' => '',
		'group' => ''
    ), $atts ));
	$cwc_options = get_option( 'crestawhatsappchat_settings' );
	$whatsapp_phone_number = $cwc_options['cresta_whatsapp_chat_phone_number'];
	$whatsapp_group_id = $cwc_options['cresta_whatsapp_chat_id_group'];
	$whatsapp_box_text = $cwc_options['cresta_whatsapp_chat_box_text'];
	$whatsapp_default_text = $cwc_options['cresta_whatsapp_chat_box_default_text'];
	$whatsapp_send_button = $cwc_options['cresta_whatsapp_chat_box_send'];
	if ($whatsapp_box_text) {
		$top_text = '<div class="cresta-whatsapp-chat-top-header"><span>'. esc_html($whatsapp_box_text) .'</span></div>';
	} else {
		$top_text = '';
	}
	if ($icon == 'yes') {
		$svg_icon = '<svg id="whatsapp-msng-icon-button" data-name="whatsapp icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 800"><path d="M519 454c4 2 7 10-1 31-6 16-33 29-49 29-96 0-189-113-189-167 0-26 9-39 18-48 8-9 14-10 18-10h12c4 0 9 0 13 10l19 44c5 11-9 25-15 31-3 3-6 7-2 13 25 39 41 51 81 71 6 3 10 1 13-2l19-24c5-6 9-4 13-2zM401 200c-110 0-199 90-199 199 0 68 35 113 35 113l-20 74 76-20s42 32 108 32c110 0 199-89 199-199 0-111-89-199-199-199zm0-40c133 0 239 108 239 239 0 132-108 239-239 239-67 0-114-29-114-29l-127 33 34-124s-32-49-32-119c0-131 108-239 239-239z" style="fill:#ffffff"/></svg>';
	} else {
		$svg_icon = '';
	}
	$random_number = rand(1,1000);
	if ($use == 'number') {
		if ($number == '') {
			$theNumber = $whatsapp_phone_number;
		} else {
			$theNumber = $number;
		}
		return "
		<script>
			window.addEventListener('DOMContentLoaded', () => {
				var mobileDetect = /Android|webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),
					btnRnd = document.querySelector('.cresta-whatsapp-chat-button.click-".esc_attr($random_number)."');
				if (mobileDetect) {
					btnRnd.querySelector('.cresta-whatsapp-chat-container-button').style.display = 'none';
					btnRnd.addEventListener('click', () => {
						window.location = 'whatsapp://send?text=&phone=".esc_attr($theNumber)."&abid=".esc_attr($theNumber)."';
					})
				} else {
					btnRnd.addEventListener('click', () => {
						if (btnRnd.querySelector('.cresta-whatsapp-chat-container-button').classList.contains('open')) {
							btnRnd.querySelector('.cresta-whatsapp-chat-container-button').classList.remove('open');
						} else {
							btnRnd.querySelector('.cresta-whatsapp-chat-container-button').classList.add('open');
							setTimeout (function () {
								btnRnd.querySelector('textarea.cresta-whatsapp-textarea').focus();
							}, 100);
						}
					})
					btnRnd.querySelector('.cresta-whatsapp-chat-container-button').addEventListener('click', e => {
						e.stopPropagation();
					})
					btnRnd.querySelector('.cresta-whatsapp-chat-container-button .cresta-whatsapp-send').addEventListener('click', () => {
						var baseUrl = 'https://web.whatsapp.com/send?phone=".esc_attr($theNumber)."&text=',
							textEncode = encodeURIComponent(btnRnd.querySelector('.cresta-whatsapp-textarea').value);
						window.open(baseUrl + textEncode, '_blank');
					})
				}
			});
		</script>
		<div class='cresta-whatsapp-chat-button click-".esc_attr($random_number)."'>
			".$svg_icon."
			<span>".esc_html($text)."</span>
			<div class='cresta-whatsapp-chat-container-button ".esc_attr($position)."'>
				".$top_text."
				<div class='cresta-whatsapp-inner'>
					<textarea class='cresta-whatsapp-textarea' placeholder='".esc_attr($whatsapp_default_text)."'></textarea>
				</div>
				<div class='cresta-whatsapp-to-send'>
					<div class='cresta-whatsapp-send'>".esc_html($whatsapp_send_button)."<svg version='1.1' id='whatsapp-msng-icon-send' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' width='20px' height='12px' viewBox='0 0 14 26' enable-background='new 0 0 14 26' xml:space='preserve'> <path d='M1,0c0.256,0,0.512,0.098,0.707,0.293l12,12c0.391,0.391,0.391,1.023,0,1.414l-12,12c-0.391,0.391-1.023,0.391-1.414,0s-0.391-1.023,0-1.414L11.586,13L0.293,1.707c-0.391-0.391-0.391-1.023,0-1.414C0.488,0.098,0.744,0,1,0z' style='fill: none; stroke-width:3; stroke: #ffffff'/></svg></div>
				</div>
			</div>
		</div>
		";
	} elseif ($use == 'group') {
		$whatsapp_group_open = $cwc_options['cresta_whatsapp_chat_id_open'];
		if ($group == '') {
			$theGroup = $whatsapp_group_id;
		} else {
			$theGroup = $group;
		}
		return "
		<script>
			window.addEventListener('DOMContentLoaded', () => {
				document.querySelector('.cresta-whatsapp-chat-button.click-".esc_attr($random_number)."').addEventListener('click', () => {
					var baseUrl = 'https://chat.whatsapp.com/".esc_attr($theGroup)."';
					window.open(baseUrl, '".esc_attr($whatsapp_group_open)."');
				})
			});
		</script>
		<div class='cresta-whatsapp-chat-button click-".esc_attr($random_number)."'>
			".$svg_icon."
			<span>".esc_html($text)."</span>
		</div>
		";
	}
}
add_shortcode( 'cresta-whatsapp-chat', 'cresta_whatsapp_chat_shortcode' );

/* Cresta Help Chat shortcode */
function cresta_help_chat_shortcode( $atts ) {
    extract(shortcode_atts( array(
        'text' => 'Need Help? Contact Me!',
		'icon' => 'yes',
		'position' => 'top',
		'use' => 'number',
		'number' => '',
		'group' => ''
    ), $atts ));
	$cwc_options = get_option( 'crestawhatsappchat_settings' );
	$whatsapp_phone_number = $cwc_options['cresta_whatsapp_chat_phone_number'];
	$whatsapp_group_id = $cwc_options['cresta_whatsapp_chat_id_group'];
	$whatsapp_box_text = $cwc_options['cresta_whatsapp_chat_box_text'];
	$whatsapp_default_text = $cwc_options['cresta_whatsapp_chat_box_default_text'];
	$whatsapp_send_button = $cwc_options['cresta_whatsapp_chat_box_send'];
	if ($whatsapp_box_text) {
		$top_text = '<div class="cresta-whatsapp-chat-top-header"><span>'. esc_html($whatsapp_box_text) .'</span></div>';
	} else {
		$top_text = '';
	}
	if ($icon == 'yes') {
		$svg_icon = '<svg id="whatsapp-msng-icon-button" data-name="whatsapp icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 800"><path d="M519 454c4 2 7 10-1 31-6 16-33 29-49 29-96 0-189-113-189-167 0-26 9-39 18-48 8-9 14-10 18-10h12c4 0 9 0 13 10l19 44c5 11-9 25-15 31-3 3-6 7-2 13 25 39 41 51 81 71 6 3 10 1 13-2l19-24c5-6 9-4 13-2zM401 200c-110 0-199 90-199 199 0 68 35 113 35 113l-20 74 76-20s42 32 108 32c110 0 199-89 199-199 0-111-89-199-199-199zm0-40c133 0 239 108 239 239 0 132-108 239-239 239-67 0-114-29-114-29l-127 33 34-124s-32-49-32-119c0-131 108-239 239-239z" style="fill:#ffffff"/></svg>';
	} else {
		$svg_icon = '';
	}
	$random_number = rand(1,1000);
	if ($use == 'number') {
		if ($number == '') {
			$theNumber = $whatsapp_phone_number;
		} else {
			$theNumber = $number;
		}
		return "
		<script>
			window.addEventListener('DOMContentLoaded', () => {
				var mobileDetect = /Android|webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),
					btnRnd = document.querySelector('.cresta-whatsapp-chat-button.click-".esc_attr($random_number)."');
				if (mobileDetect) {
					btnRnd.querySelector('.cresta-whatsapp-chat-container-button').style.display = 'none';
					btnRnd.addEventListener('click', () => {
						window.location = 'whatsapp://send?text=&phone=".esc_attr($theNumber)."&abid=".esc_attr($theNumber)."';
					})
				} else {
					btnRnd.addEventListener('click', () => {
						if (btnRnd.querySelector('.cresta-whatsapp-chat-container-button').classList.contains('open')) {
							btnRnd.querySelector('.cresta-whatsapp-chat-container-button').classList.remove('open');
						} else {
							btnRnd.querySelector('.cresta-whatsapp-chat-container-button').classList.add('open');
							setTimeout (function () {
								btnRnd.querySelector('textarea.cresta-whatsapp-textarea').focus();
							}, 100);
						}
					})
					btnRnd.querySelector('.cresta-whatsapp-chat-container-button').addEventListener('click', e => {
						e.stopPropagation();
					})
					btnRnd.querySelector('.cresta-whatsapp-chat-container-button .cresta-whatsapp-send').addEventListener('click', () => {
						var baseUrl = 'https://web.whatsapp.com/send?phone=".esc_attr($theNumber)."&text=',
							textEncode = encodeURIComponent(btnRnd.querySelector('.cresta-whatsapp-textarea').value);
						window.open(baseUrl + textEncode, '_blank');
					})
				}
			});
		</script>
		<div class='cresta-whatsapp-chat-button click-".esc_attr($random_number)."'>
			".$svg_icon."
			<span>".esc_html($text)."</span>
			<div class='cresta-whatsapp-chat-container-button ".esc_attr($position)."'>
				".$top_text."
				<div class='cresta-whatsapp-inner'>
					<textarea class='cresta-whatsapp-textarea' placeholder='".esc_attr($whatsapp_default_text)."'></textarea>
				</div>
				<div class='cresta-whatsapp-to-send'>
					<div class='cresta-whatsapp-send'>".esc_html($whatsapp_send_button)."<svg version='1.1' id='whatsapp-msng-icon-send' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' x='0px' y='0px' width='20px' height='12px' viewBox='0 0 14 26' enable-background='new 0 0 14 26' xml:space='preserve'> <path d='M1,0c0.256,0,0.512,0.098,0.707,0.293l12,12c0.391,0.391,0.391,1.023,0,1.414l-12,12c-0.391,0.391-1.023,0.391-1.414,0s-0.391-1.023,0-1.414L11.586,13L0.293,1.707c-0.391-0.391-0.391-1.023,0-1.414C0.488,0.098,0.744,0,1,0z' style='fill: none; stroke-width:3; stroke: #ffffff'/></svg></div>
				</div>
			</div>
		</div>
		";
	} elseif ($use == 'group') {
		$whatsapp_group_open = $cwc_options['cresta_whatsapp_chat_id_open'];
		if ($group == '') {
			$theGroup = $whatsapp_group_id;
		} else {
			$theGroup = $group;
		}
		return "
		<script>
			window.addEventListener('DOMContentLoaded', () => {
				document.querySelector('.cresta-whatsapp-chat-button.click-".esc_attr($random_number)."').addEventListener('click', () => {
					var baseUrl = 'https://chat.whatsapp.com/".esc_attr($theGroup)."';
					window.open(baseUrl, '".esc_attr($whatsapp_group_open)."');
				})
			});
		</script>
		<div class='cresta-whatsapp-chat-button click-".esc_attr($random_number)."'>
			".$svg_icon."
			<span>".esc_html($text)."</span>
		</div>
		";
	}
}
add_shortcode( 'cresta-help-chat', 'cresta_help_chat_shortcode' );

/* Where to show the floating box */
function cresta_whatsapp_chat_show_floating() {
	$cwc_options = get_option( 'crestawhatsappchat_settings' );
	$cresta_whatsapp_current_post_type = get_post_type();	
	$whatsapp_selected_pages = explode (',',$cwc_options['cresta_whatsapp_chat_selected_page'] );
	if ( is_singular() && in_array( $cresta_whatsapp_current_post_type, $whatsapp_selected_pages ) ) {
		$checkCrestaWhatsAppMetaBox = get_post_meta(crestawhatsappchat_get_the_current_ID(), '_get_cresta_whatsapp_chat_plugin', true);
		if ( $checkCrestaWhatsAppMetaBox == '1' ) {
			return false;
		} else {
			return true;
		}
	}
	if (in_array( 'website', $whatsapp_selected_pages ) ) {
		return true;
	} else {
		if (is_home() && in_array( 'blogpage', $whatsapp_selected_pages ) ) {
			return true;
		}
		if (is_front_page() && in_array( 'homepage', $whatsapp_selected_pages ) ) {
			return true;
		}
		if (is_category() && in_array( 'catpage', $whatsapp_selected_pages ) ) {
			return true;
		}
		if (is_tag() && in_array( 'tagpage', $whatsapp_selected_pages ) ) {
			return true;
		}
		if (is_author() && in_array( 'authorpage', $whatsapp_selected_pages ) ) {
			return true;
		}
		if (is_date() && in_array( 'datepage', $whatsapp_selected_pages ) ) {
			return true;
		}
		if (is_search() && in_array( 'searchpage', $whatsapp_selected_pages ) ) {
			return true;
		}
		if (function_exists( 'is_woocommerce' ) ) {
			if (is_shop() && in_array( 'shoppage', $whatsapp_selected_pages ) ) {
				return true;
			}
			if (is_product_category() && in_array( 'woocatpage', $whatsapp_selected_pages ) ) {
				return true;
			}
			if (is_product_tag() && in_array( 'wootagpage', $whatsapp_selected_pages ) ) {
				return true;
			}
		}
	}
	return false;
}

/* This is the float button output */
function add_cresta_whatsapp_chat_box() {
	if ( !is_admin() ) {
		$cwc_options = get_option( 'crestawhatsappchat_settings' );
		$whatsapp_show_floating_box = $cwc_options['cresta_whatsapp_chat_show_floating_box'];
		if ($whatsapp_show_floating_box == 1 && cresta_whatsapp_chat_show_floating() ) {
			$whatsapp_use_whatsapp = $cwc_options['cresta_whatsapp_chat_choose_using'];
			$whatsapp_phone_number = $cwc_options['cresta_whatsapp_chat_phone_number'];
			$whatsapp_id_group = $cwc_options['cresta_whatsapp_chat_id_group'];
			$whatsapp_box_text = $cwc_options['cresta_whatsapp_chat_box_text'];
			$whatsapp_default_text = $cwc_options['cresta_whatsapp_chat_box_default_text'];
			$whatsapp_send_button = $cwc_options['cresta_whatsapp_chat_box_send'];
			$whatsapp_mobile = $cwc_options['cresta_whatsapp_chat_mobile_option'];
			$whatsapp_click_open = $cwc_options['cresta_whatsapp_chat_click_button_open'];
			?>
			<?php if($whatsapp_use_whatsapp == 'wNumber' && $whatsapp_phone_number): ?>
				<?php if($whatsapp_click_open == 'whweb'): ?>
					<script>
						window.addEventListener('DOMContentLoaded', () => {
							var mobileDetect = /Android|webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),
								crestaBox = document.querySelector('.cresta-whatsapp-chat-box');
							if (mobileDetect) {
								crestaBox.addEventListener('click', () => {
									window.location = 'whatsapp://send?text=&phone=<?php echo esc_attr($whatsapp_phone_number); ?>&abid=<?php echo esc_attr($whatsapp_phone_number); ?>';
								})
							} else {
								crestaBox.addEventListener('click', () => {
									var baseUrl = 'https://web.whatsapp.com/send?phone=<?php echo esc_attr($whatsapp_phone_number); ?>&text=';
									window.open(baseUrl, '_blank');
								})
							}
						})
					</script>
				<?php else: ?>
					<script>
						window.addEventListener('DOMContentLoaded', () => {
							var mobileDetect = /Android|webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),
								crestaContainer = document.querySelector('.cresta-whatsapp-chat-container'),
								crestaBox = document.querySelector('.cresta-whatsapp-chat-box'),
								crestaOverlay = document.querySelector('.cresta-whatsapp-chat-overlay');
							if (mobileDetect) {
								crestaContainer.style.display = 'none';
								crestaBox.addEventListener('click', () => {
									window.location = 'whatsapp://send?text=&phone=<?php echo esc_attr($whatsapp_phone_number); ?>&abid=<?php echo esc_attr($whatsapp_phone_number); ?>';
								})
							} else {
								if (crestaOverlay) {
									[crestaBox, crestaOverlay].forEach(item => {
										item.addEventListener('click', () => {
											if(crestaBox.classList.contains('open')) {
												crestaBox.classList.remove('open');
												crestaContainer.classList.remove('open');
												crestaOverlay?.classList.remove('open');
											} else {
												crestaBox.classList.add('open');
												crestaContainer.classList.add('open');
												crestaOverlay?.classList.add('open');
												setTimeout (function () {
													document.querySelector('.cresta-whatsapp-chat-container .cresta-whatsapp-inner textarea.cresta-whatsapp-textarea').focus();
												}, 100);
											}
										})
									})
								} else {
									crestaBox.addEventListener('click', () => {
										if(crestaBox.classList.contains('open')) {
											crestaBox.classList.remove('open');
											crestaContainer.classList.remove('open');
										} else {
											crestaBox.classList.add('open');
											crestaContainer.classList.add('open');
											setTimeout (function () {
												document.querySelector('.cresta-whatsapp-chat-container .cresta-whatsapp-inner textarea.cresta-whatsapp-textarea').focus();
											}, 100);
										}
									})
								}
								document.querySelector('.cresta-whatsapp-chat-container .cresta-whatsapp-send').addEventListener('click', () => {
									var baseUrl = 'https://web.whatsapp.com/send?phone=<?php echo esc_attr($whatsapp_phone_number); ?>&text=',
										textEncode = encodeURIComponent(document.querySelector('.cresta-whatsapp-chat-container .cresta-whatsapp-textarea').value);
									window.open(baseUrl + textEncode, '_blank');
								})
							}
						})
					</script>
				<?php endif; ?>
				
				
				<?php
					$whatsapp_click_to_close = $cwc_options['cresta_whatsapp_chat_click_to_close'];
					if ($whatsapp_click_to_close == 1) {
						echo '<div class="cresta-whatsapp-chat-overlay"></div>';
					}
				?>
				<div class="cresta-whatsapp-chat-box <?php echo esc_attr($whatsapp_mobile); ?>">
					<svg id="whatsapp-msng-icon" data-name="whatsapp icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 800"><path d="M519 454c4 2 7 10-1 31-6 16-33 29-49 29-96 0-189-113-189-167 0-26 9-39 18-48 8-9 14-10 18-10h12c4 0 9 0 13 10l19 44c5 11-9 25-15 31-3 3-6 7-2 13 25 39 41 51 81 71 6 3 10 1 13-2l19-24c5-6 9-4 13-2zM401 200c-110 0-199 90-199 199 0 68 35 113 35 113l-20 74 76-20s42 32 108 32c110 0 199-89 199-199 0-111-89-199-199-199zm0-40c133 0 239 108 239 239 0 132-108 239-239 239-67 0-114-29-114-29l-127 33 34-124s-32-49-32-119c0-131 108-239 239-239z" transform="scale(1.2, 1.2), translate(-65 -65)" style="fill:#ffffff"/></svg>
					<svg id="close-icon" data-name="close icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 39.98 39.99"><path d="M48.88,11.14a3.87,3.87,0,0,0-5.44,0L30,24.58,16.58,11.14a3.84,3.84,0,1,0-5.44,5.44L24.58,30,11.14,43.45a3.87,3.87,0,0,0,0,5.44,3.84,3.84,0,0,0,5.44,0L30,35.45,43.45,48.88a3.84,3.84,0,0,0,5.44,0,3.87,3.87,0,0,0,0-5.44L35.45,30,48.88,16.58A3.87,3.87,0,0,0,48.88,11.14Z" transform="translate(-10.02 -10.02)" style="fill:#ffffff"/></svg>
				</div>
				<?php if($whatsapp_click_open != 'whweb'): ?>
					<div class="cresta-whatsapp-chat-container">
						<?php if ($whatsapp_box_text) : ?>
							<div class="cresta-whatsapp-chat-top-header"><span><?php echo esc_html($whatsapp_box_text); ?></span></div>
						<?php endif; ?>
						<div class="cresta-whatsapp-inner">
							<textarea class="cresta-whatsapp-textarea" placeholder="<?php echo esc_attr($whatsapp_default_text); ?>"></textarea>
						</div>
						<div class="cresta-whatsapp-to-send">
							<div class="cresta-whatsapp-send"><?php echo esc_html($whatsapp_send_button); ?><svg version="1.1" id="whatsapp-msng-icon-send" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="20px" height="12px" viewBox="0 0 14 26" enable-background="new 0 0 14 26" xml:space="preserve"> <path d="M1,0c0.256,0,0.512,0.098,0.707,0.293l12,12c0.391,0.391,0.391,1.023,0,1.414l-12,12c-0.391,0.391-1.023,0.391-1.414,0s-0.391-1.023,0-1.414L11.586,13L0.293,1.707c-0.391-0.391-0.391-1.023,0-1.414C0.488,0.098,0.744,0,1,0z" style="fill: none; stroke-width:3; stroke:#ffffff"/></svg></div>
						</div>
					</div>
				<?php endif; ?>
			<?php elseif($whatsapp_use_whatsapp == 'wGroup' && $whatsapp_id_group): ?>
				<?php $whatsapp_id_open = $cwc_options['cresta_whatsapp_chat_id_open']; ?>
				<script>
					window.addEventListener('DOMContentLoaded', () => {
						document.querySelector('.cresta-whatsapp-chat-box').addEventListener('click', () => {
							var baseUrl = 'https://chat.whatsapp.com/<?php echo esc_attr($whatsapp_id_group); ?>';
							window.open(baseUrl, '<?php echo esc_attr($whatsapp_id_open); ?>');
						})
					});
				</script>
				<div class="cresta-whatsapp-chat-box <?php echo esc_attr($whatsapp_mobile); ?>">
					<svg id="whatsapp-msng-icon" data-name="whatsapp icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 800"><path d="M519 454c4 2 7 10-1 31-6 16-33 29-49 29-96 0-189-113-189-167 0-26 9-39 18-48 8-9 14-10 18-10h12c4 0 9 0 13 10l19 44c5 11-9 25-15 31-3 3-6 7-2 13 25 39 41 51 81 71 6 3 10 1 13-2l19-24c5-6 9-4 13-2zM401 200c-110 0-199 90-199 199 0 68 35 113 35 113l-20 74 76-20s42 32 108 32c110 0 199-89 199-199 0-111-89-199-199-199zm0-40c133 0 239 108 239 239 0 132-108 239-239 239-67 0-114-29-114-29l-127 33 34-124s-32-49-32-119c0-131 108-239 239-239z" transform="scale(1.2, 1.2), translate(-65 -65)" style="fill:#ffffff"/></svg>
				</div>
			<?php endif; ?>
			<?php
		}
	}
}
add_action('wp_footer', 'add_cresta_whatsapp_chat_box');

function cresta_whatsapp_chat_option() {
	ob_start();
	?>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			if ( jQuery('input.crestaisPossbile').hasClass('active') ) {
				jQuery('.chkifpossible input').attr('disabled', false);
				jQuery('.chkifpossible').removeClass('crestaOpa');
			} else {
				jQuery('.chkifpossible input').attr('disabled', true);
				jQuery('.chkifpossible').addClass('crestaOpa');
			}
			jQuery('input.crestaisPossbile').on('click', function(){
				if ( jQuery(this).is(':checked') ) {
					jQuery('.chkifpossible input').attr('disabled', false);
					jQuery('.chkifpossible').removeClass('crestaOpa');
				} else {
					jQuery('.chkifpossible input').attr('disabled', true);
					jQuery('.chkifpossible').addClass('crestaOpa');
				}
			});
			if ( jQuery('input.ifwebsite').hasClass('active') ) {
				jQuery('.yesiswebsite input').attr('disabled', true);
				jQuery('.yesiswebsite').addClass('crestaOpa');
			} else {
				jQuery('.yesiswebsite input').attr('disabled', false);
				jQuery('.yesiswebsite').removeClass('crestaOpa');
			}
			if ( jQuery('input.chatisPossbile').hasClass('activeNumber') ) {
				jQuery('.chkifnumber').addClass('crestaOpa');
				jQuery('.chkifgroup').removeClass('crestaOpa');
			} else if ( jQuery('input.chatisPossbile').hasClass('activeGroup') ) {
				jQuery('.chkifgroup').addClass('crestaOpa');
				jQuery('.chkifnumber').removeClass('crestaOpa');
			} else {
				jQuery('.chkifnumber').addClass('crestaOpa');
				jQuery('.chkifgroup').removeClass('crestaOpa');
			}
			jQuery('input[name="crestawhatsappchat_settings[cresta_whatsapp_chat_choose_using]"]').on('click', function(){
				if (jQuery('input:radio[name="crestawhatsappchat_settings[cresta_whatsapp_chat_choose_using]"]:checked').val() == "wNumber"){
					jQuery('.chkifnumber').addClass('crestaOpa');
					jQuery('.chkifgroup').removeClass('crestaOpa');
				} else if (jQuery('input:radio[name="crestawhatsappchat_settings[cresta_whatsapp_chat_choose_using]"]:checked').val() == "wGroup"){
					jQuery('.chkifgroup').addClass('crestaOpa');
					jQuery('.chkifnumber').removeClass('crestaOpa');
				} else {
					jQuery('.chkifnumber').addClass('crestaOpa');
					jQuery('.chkifgroup').removeClass('crestaOpa');
				}
			});
			jQuery('input.ifwebsite').on('click', function(){
				if ( jQuery(this).is(':checked') ) {
					jQuery('.yesiswebsite input').attr('disabled', true);
					jQuery('.yesiswebsite').addClass('crestaOpa');
				} else {
					jQuery('.yesiswebsite input').attr('disabled', false);
					jQuery('.yesiswebsite').removeClass('crestaOpa');
				}
			});
		});
	</script>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"></div>
		<a class="crestaButtonUpgrade" href="https://crestaproject.com/downloads/cresta-help-chat/?utm_source=plugin_whatsapp&utm_medium=insideoption_meta" target="_blank" title="<?php esc_attr_e('See Details: Cresta Help Chat PRO', 'cresta-whatsapp-chat'); ?>"><span class="dashicons dashicons-megaphone"></span><?php esc_html_e('Upgrade to PRO version!', 'cresta-whatsapp-chat'); ?></a>
		<h2><?php esc_html_e('Cresta Help Chat FREE', 'cresta-whatsapp-chat'); ?></h2>
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
			<!-- main content -->
			<div id="post-body-content">
			<div class="meta-box-sortables ui-sortable">
			<div class="postbox">
			<div class="inside">
			<form method="post" action="options.php">
				<?php
				settings_fields( 'cwcplugin' ); 
				$cwc_options = get_option( 'crestawhatsappchat_settings' );
				?>
				<span class="description attributes notice"><?php echo wp_kses_post('<b>Please note:</b> remember that the WhatsApp number will be <b>visible and public to all users</b> of your website. <b>Do not</b> use a number that you want to keep private.', 'cresta-whatsapp-chat'); ?></span>
				<h3><div class="dashicons dashicons-format-image space"></div><?php esc_html_e( 'Choose Box Style', 'cresta-whatsapp-chat' ); ?></h3>
				<table class="form-table">
					<tbody>
						<tr valign="top" class="chkifgroup">
							<td>
								<ul>
									<li>
										<label class="whatsStyle">
											<input type="radio" name="crestaForPRO" class="styleisPossbile" disabled value="style1" >
											<img src="<?php echo esc_url(plugins_url( '/images/whatsapp-style-1.png' , __FILE__ )); ?>"/>
										</label>
										<label class="whatsStyle">
											<input type="radio" name="crestaForPRO" class="styleisPossbile" disabled value="style2" >
											<img src="<?php echo esc_url(plugins_url( '/images/whatsapp-style-2.png' , __FILE__ )); ?>"/>
										</label>
										<label class="whatsStyle">
											<input type="radio" name="crestaForPRO" class="styleisPossbile" disabled value="style3" >
											<img src="<?php echo esc_url(plugins_url( '/images/whatsapp-style-3.png' , __FILE__ )); ?>"/>
										</label>
									</li>
								</ul>
							</td>
						</tr>
					</tbody>
				</table>
				<h3><div class="dashicons dashicons-admin-generic space"></div><?php esc_html_e( 'General Box Settings', 'cresta-whatsapp-chat' ); ?></h3>
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Use the plugin with:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<ul>
									<li>
										<label><input type="radio" name='crestawhatsappchat_settings[cresta_whatsapp_chat_choose_using]' class="chatisPossbile <?php if($cwc_options['cresta_whatsapp_chat_choose_using'] == "wNumber") { echo 'activeNumber'; }?>" value='wNumber' <?php checked( 'wNumber', $cwc_options['cresta_whatsapp_chat_choose_using'] ); ?>><?php esc_html_e('WhatsApp number', 'cresta-whatsapp-chat'); ?></label>
									</li>
									<li>
										<label><input type="radio" name='crestawhatsappchat_settings[cresta_whatsapp_chat_choose_using]' class="chatisPossbile <?php if($cwc_options['cresta_whatsapp_chat_choose_using'] == "wGroup") { echo 'activeGroup'; }?>" value='wGroup' <?php checked( 'wGroup', $cwc_options['cresta_whatsapp_chat_choose_using'] ); ?>><?php esc_html_e('WhatsApp group', 'cresta-whatsapp-chat'); ?></label>
									</li>
								</ul>
								<span class="description attributes"><?php esc_html_e('Choose to use the plugin to write with your users via WhatsApp number or invite the user to join in a WhatsApp Group.', 'cresta-whatsapp-chat'); ?></span>
							</td>
						</tr>
						<tr valign="top" class="chkifgroup">
							<th scope="row"><?php esc_html_e( 'Your WhatsApp Number:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<input class="regular-text" type='text' name='crestawhatsappchat_settings[cresta_whatsapp_chat_phone_number]' value='<?php echo esc_attr($cwc_options['cresta_whatsapp_chat_phone_number']); ?>' placeholder='Example: 555444555'>
								<span class="description attributes">
								<?php
								/* translators: 1: start option panel link, 2: end option panel link */
								printf( esc_html__( 'Add your phone number with the country code, take a look at %1$s this page %2$s to find the correct country code.', 'cresta-whatsapp-chat' ), '<a href="https://faq.whatsapp.com/general/contacts/how-to-add-an-international-phone-number" target="_blank">', '</a>' );
								?>
								</span>
							</td>
						</tr>
						<tr valign="top" class="chkifnumber">
							<th scope="row"><?php esc_html_e( 'WhatsApp ID Group:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<input class="regular-text" type='text' name='crestawhatsappchat_settings[cresta_whatsapp_chat_id_group]' value='<?php echo esc_attr($cwc_options['cresta_whatsapp_chat_id_group']); ?>' placeholder='Example: JARt2zAI5FmG6EXSgeliiA'>
								<span class="description attributes">
								<?php
								/* translators: 1: start option panel link, 2: end option panel link */
								printf( esc_html__( 'Add the WhatsApp group ID where you want to invite users, take a look at %1$s this page %2$s to know how to get the group ID.', 'cresta-whatsapp-chat' ), '<a href="https://crestaproject.com/cresta-whatsapp-chat-demo-page/#group" target="_blank">', '</a>' );
								?>
								</span>
							</td>
						</tr>
						<tr valign="top" class="chkifnumber">
							<th scope="row"><?php esc_html_e( 'Open Group in (on desktop):', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<select name="crestawhatsappchat_settings[cresta_whatsapp_chat_id_open]" id="crestawhatsappchat_settings[cresta_whatsapp_chat_id_open]">
									<option value="_blank" <?php selected( $cwc_options['cresta_whatsapp_chat_id_open'], '_blank' ); ?>><?php esc_html_e( 'New window', 'cresta-whatsapp-chat' ); ?></option>
									<option value="_self" <?php selected( $cwc_options['cresta_whatsapp_chat_id_open'], '_self' ); ?>><?php esc_html_e( 'Same window', 'cresta-whatsapp-chat' ); ?></option>
								</select>
							</td>
						</tr>
						<tr valign="top" class="chkifgroup">
							<th scope="row"><?php esc_html_e( 'Box Text:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<input class="regular-text" type='text' name='crestawhatsappchat_settings[cresta_whatsapp_chat_box_text]' value='<?php echo esc_attr($cwc_options['cresta_whatsapp_chat_box_text']); ?>'>
								<span class="description attributes"><?php esc_html_e('Leave it blank if you do not want to use the box text.', 'cresta-whatsapp-chat'); ?></span>
							</td>
						</tr>
						<tr valign="top" class="chkifgroup">
							<th scope="row"><?php esc_html_e( 'Placeholder Text:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<input class="regular-text" type='text' name='crestawhatsappchat_settings[cresta_whatsapp_chat_box_default_text]' value='<?php echo esc_attr($cwc_options['cresta_whatsapp_chat_box_default_text']); ?>'>
								<span class="description attributes"><?php esc_html_e('Leave it blank if you do not want to show placeholder text.', 'cresta-whatsapp-chat'); ?></span>
							</td>
						</tr>
						<tr valign="top" class="chkifgroup">
							<th scope="row"><?php esc_html_e( 'Send button:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<input class="regular-text" type='text' name='crestawhatsappchat_settings[cresta_whatsapp_chat_box_send]' value='<?php echo esc_attr($cwc_options['cresta_whatsapp_chat_box_send']); ?>'>
							</td>
						</tr>
						<tr valign="top" class="chkifgroup">
							<th scope="row"><?php esc_html_e( 'Close the floating box by clicking anywhere on the page:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<input type='checkbox' name='crestawhatsappchat_settings[cresta_whatsapp_chat_click_to_close]' value="1" <?php checked( $cwc_options['cresta_whatsapp_chat_click_to_close'], '1' ); ?>>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Title and send button color:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<span class="description getPRO"><?php esc_html_e('PRO Version', 'cresta-whatsapp-chat'); ?></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Title and send button background color:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<span class="description getPRO"><?php esc_html_e('PRO Version', 'cresta-whatsapp-chat'); ?></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Textarea color:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<span class="description getPRO"><?php esc_html_e('PRO Version', 'cresta-whatsapp-chat'); ?></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Textarea placeholder color:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<span class="description getPRO"><?php esc_html_e('PRO Version', 'cresta-whatsapp-chat'); ?></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Textarea background color:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<span class="description getPRO"><?php esc_html_e('PRO Version', 'cresta-whatsapp-chat'); ?></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Boz Z-Index:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<input type='number' name='crestawhatsappchat_settings[cresta_whatsapp_chat_zindex]' value='<?php echo intval($cwc_options['cresta_whatsapp_chat_zindex']); ?>' min="0" max="999999">
								<span class="description"><?php esc_html_e('Increase this number if the box is covered by other items on the screen.', 'cresta-whatsapp-chat'); ?></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Box Width:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<span class="description getPRO"><?php esc_html_e('PRO Version', 'cresta-whatsapp-chat'); ?></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Box Height:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<span class="description getPRO"><?php esc_html_e('PRO Version', 'cresta-whatsapp-chat'); ?></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Show WhatsApp button:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<select name="crestawhatsappchat_settings[cresta_whatsapp_chat_mobile_option]" id="crestawhatsappchat_settings[cresta_whatsapp_chat_mobile_option]">
									<option value="onBoth" <?php selected( $cwc_options['cresta_whatsapp_chat_mobile_option'], 'onBoth' ); ?>><?php esc_html_e( 'Both mobile and desktop', 'cresta-whatsapp-chat' ); ?></option>
									<option value="onMobile" <?php selected( $cwc_options['cresta_whatsapp_chat_mobile_option'], 'onMobile' ); ?>><?php esc_html_e( 'Only on mobile', 'cresta-whatsapp-chat' ); ?></option>
									<option value="onDesktop" <?php selected( $cwc_options['cresta_whatsapp_chat_mobile_option'], 'onDesktop' ); ?>><?php esc_html_e( 'Only on desktop', 'cresta-whatsapp-chat' ); ?></option>
								</select>
							</td>
						</tr>
					</tbody>
				</table>
				<h3><div class="dashicons dashicons-info space"></div><?php esc_html_e( 'Floating Box', 'cresta-whatsapp-chat' ); ?></h3>
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Show floating box', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<input type='checkbox' class='crestaisPossbile <?php if($cwc_options['cresta_whatsapp_chat_show_floating_box'] == '1') { echo 'active'; }?>' name='crestawhatsappchat_settings[cresta_whatsapp_chat_show_floating_box]' value="1" <?php checked( $cwc_options['cresta_whatsapp_chat_show_floating_box'], '1' ); ?>>
							</td>
						</tr>
						<tr valign="top" class="chkifpossible">
							<th scope="row"><?php esc_html_e( 'Show the floating box on', 'cresta-whatsapp-chat' ); ?></th>
							<td>
							<?php
								$box_show_on = explode (',',$cwc_options['cresta_whatsapp_chat_selected_page'] );
								echo '<ul>'; ?>
									<li>
										<input class="ifwebsite <?php if(in_array( 'website', $box_show_on )) { echo 'active'; }?>" type="checkbox" <?php if(in_array( 'website' ,$box_show_on)) { echo 'checked="checked"'; }?> name="crestawhatsappchat_settings[cresta_whatsapp_chat_selected_page][]" value="website"/><?php esc_html_e( 'Entire website', 'cresta-whatsapp-chat' ); ?>
									</li>
									<li class="yesiswebsite">
										<input type="checkbox" <?php if(in_array( 'homepage' ,$box_show_on)) { echo 'checked="checked"'; }?> name="crestawhatsappchat_settings[cresta_whatsapp_chat_selected_page][]" value="homepage"/><?php esc_html_e( 'Home page', 'cresta-whatsapp-chat' ); ?>
									</li>
									<li class="yesiswebsite">
										<input type="checkbox" <?php if(in_array( 'blogpage' ,$box_show_on)) { echo 'checked="checked"'; }?> name="crestawhatsappchat_settings[cresta_whatsapp_chat_selected_page][]" value="blogpage"/><?php esc_html_e( 'Blog page', 'cresta-whatsapp-chat' ); ?>
									</li>
									<?php if (function_exists( 'is_woocommerce' )) : ?>
										<li class="yesiswebsite">
											<input type="checkbox" <?php if(in_array( 'shoppage' ,$box_show_on)) { echo 'checked="checked"'; }?> name="crestawhatsappchat_settings[cresta_whatsapp_chat_selected_page][]" value="shoppage"/><?php esc_html_e( 'WooCommerce Shop page', 'cresta-whatsapp-chat' ); ?>
										</li>
										<li class="yesiswebsite">
											<input type="checkbox" <?php if(in_array( 'woocatpage' ,$box_show_on)) { echo 'checked="checked"'; }?> name="crestawhatsappchat_settings[cresta_whatsapp_chat_selected_page][]" value="woocatpage"/><?php esc_html_e( 'WooCommerce Product Catgory', 'cresta-whatsapp-chat' ); ?>
										</li>
										<li class="yesiswebsite">
											<input type="checkbox" <?php if(in_array( 'wootagpage' ,$box_show_on)) { echo 'checked="checked"'; }?> name="crestawhatsappchat_settings[cresta_whatsapp_chat_selected_page][]" value="wootagpage"/><?php esc_html_e( 'WooCommerce Product Tag', 'cresta-whatsapp-chat' ); ?>
										</li>
									<?php endif; ?>
									<li class="yesiswebsite">
										<input type="checkbox" <?php if(in_array( 'catpage' ,$box_show_on)) { echo 'checked="checked"'; }?> name="crestawhatsappchat_settings[cresta_whatsapp_chat_selected_page][]" value="catpage"/><?php esc_html_e( 'Category pages', 'cresta-whatsapp-chat' ); ?>
									</li>
									<li class="yesiswebsite">
										<input type="checkbox" <?php if(in_array( 'tagpage' ,$box_show_on)) { echo 'checked="checked"'; }?> name="crestawhatsappchat_settings[cresta_whatsapp_chat_selected_page][]" value="tagpage"/><?php esc_html_e( 'Tag pages', 'cresta-whatsapp-chat' ); ?>
									</li>
									<li class="yesiswebsite">
										<input type="checkbox" <?php if(in_array( 'authorpage' ,$box_show_on)) { echo 'checked="checked"'; }?> name="crestawhatsappchat_settings[cresta_whatsapp_chat_selected_page][]" value="authorpage"/><?php esc_html_e( 'Author pages', 'cresta-whatsapp-chat' ); ?>
									</li>
									<li class="yesiswebsite">
										<input type="checkbox" <?php if(in_array( 'datepage' ,$box_show_on)) { echo 'checked="checked"'; }?> name="crestawhatsappchat_settings[cresta_whatsapp_chat_selected_page][]" value="datepage"/><?php esc_html_e( 'Date pages', 'cresta-whatsapp-chat' ); ?>
									</li>
									<li class="yesiswebsite">
										<input type="checkbox" <?php if(in_array( 'searchpage' ,$box_show_on)) { echo 'checked="checked"'; }?> name="crestawhatsappchat_settings[cresta_whatsapp_chat_selected_page][]" value="searchpage"/><?php esc_html_e( 'Search pages', 'cresta-whatsapp-chat' ); ?>
									</li>
								<?php
								$args = array(
									'public'   => true,
								);
								$post_types = get_post_types( $args, 'names', 'and' ); 
								foreach ( $post_types  as $post_type ) { 
									$post_type_name = get_post_type_object( $post_type );
									?>
									<li class="yesiswebsite">
										<input type="checkbox" <?php if(in_array( $post_type ,$box_show_on)) { echo 'checked="checked"'; }?> name="crestawhatsappchat_settings[cresta_whatsapp_chat_selected_page][]" value="<?php echo esc_attr($post_type); ?>"/><?php echo esc_html($post_type_name->labels->singular_name); ?>
									</li>
								<?php
								}
								echo '</ul>';
							?>
							<span class="description"><?php esc_html_e( 'If active, post, page and custom post type can be managed individually via metabox when you edit a page or post. You can choose to hide the WhatsApp box in a specific post, page or custom post type.', 'cresta-whatsapp-chat' ); ?></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'On desktop, when the button is clicked:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<select name="crestawhatsappchat_settings[cresta_whatsapp_chat_click_button_open]" id="crestawhatsappchat_settings[cresta_whatsapp_chat_click_button_open]">
									<option value="popup" <?php selected( $cwc_options['cresta_whatsapp_chat_click_button_open'], 'popup' ); ?>><?php esc_html_e( 'Open plugin popup', 'cresta-whatsapp-chat' ); ?></option>
									<option value="whweb" <?php selected( $cwc_options['cresta_whatsapp_chat_click_button_open'], 'whweb' ); ?>><?php esc_html_e( 'Open WhatsApp web directly', 'cresta-whatsapp-chat' ); ?></option>
								</select>
							</td>
						</tr>
						<tr valign="top" class="chkifpossible">
							<th scope="row"><?php esc_html_e( 'Preset text:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<span class="description getPRO"><?php esc_html_e('PRO Version', 'cresta-whatsapp-chat'); ?></span>
							</td>
						</tr>
						<tr valign="top" class="chkifpossible">
							<th scope="row"><?php esc_html_e( 'Icon color:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<span class="description getPRO"><?php esc_html_e('PRO Version', 'cresta-whatsapp-chat'); ?></span>
							</td>
						</tr>
						<tr valign="top" class="chkifpossible">
							<th scope="row"><?php esc_html_e( 'Icon background:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<span class="description getPRO"><?php esc_html_e('PRO Version', 'cresta-whatsapp-chat'); ?></span>
							</td>
						</tr>
						<tr valign="top" class="chkifpossible">
							<th scope="row"><?php esc_html_e( 'Icon animation:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<span class="description getPRO"><?php esc_html_e('PRO Version', 'cresta-whatsapp-chat'); ?></span>
							</td>
						</tr>
						<tr valign="top" class="chkifpossible">
							<th scope="row"><?php esc_html_e( 'Icon position:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<span class="description getPRO"><?php esc_html_e('PRO Version', 'cresta-whatsapp-chat'); ?></span>
							</td>
						</tr>
						<tr valign="top" class="chkifpossible">
							<th scope="row"><?php esc_html_e( 'Icon border radius:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<span class="description getPRO"><?php esc_html_e('PRO Version', 'cresta-whatsapp-chat'); ?></span>
							</td>
						</tr>
						<tr valign="top" class="chkifpossible">
							<th scope="row"><?php esc_html_e( 'Distance from left/right:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<span class="description getPRO"><?php esc_html_e('PRO Version', 'cresta-whatsapp-chat'); ?></span>
							</td>
						</tr>
						<tr valign="top" class="chkifpossible">
							<th scope="row"><?php esc_html_e( 'Distance from top/bottom:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<span class="description getPRO"><?php esc_html_e('PRO Version', 'cresta-whatsapp-chat'); ?></span>
							</td>
						</tr>
						<tr valign="top" class="chkifpossible">
							<th scope="row"><?php esc_html_e( 'Tooltip text:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<span class="description getPRO"><?php esc_html_e('PRO Version', 'cresta-whatsapp-chat'); ?></span>
							</td>
						</tr>
					</tbody>
				</table>
				<h3><div class="dashicons dashicons-hammer space"></div><?php esc_html_e( 'Shortcode and PHP code', 'cresta-whatsapp-chat' ); ?></h3>
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Attributes for shortcode and PHP code', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<span class="description attributes"><strong>text</strong> - <?php esc_html_e( 'The text you want to display in the button', 'cresta-whatsapp-chat' ); ?></span>
								<span class="description attributes"><strong>icon</strong> - <?php esc_html_e( 'Choose whether to display the WhatsApp icon next to the text (yes or no)', 'cresta-whatsapp-chat' ); ?></span>
								<span class="description attributes"><strong>position</strong> - <?php esc_html_e( 'The position of the box after the user has clicked on the button (top, bottom, left or right)', 'cresta-whatsapp-chat' ); ?></span>
								<span class="description attributes"><strong>use</strong> - <?php esc_html_e( 'If you want to use the shortcode to add a phone number, or if you want to invite users to join to a group. (number or group) ', 'cresta-whatsapp-chat' ); ?></span>
								<span class="description attributes"><strong>number</strong> - <?php esc_html_e( 'Use it if the "use" option is set to "number" - Use it if you want to use a different phone number than the one used in the plugin options page.', 'cresta-whatsapp-chat' ); ?></span>
								<span class="description attributes"><strong>group</strong> - <?php esc_html_e( 'Use it if the "use" option is set to "group" - Use it if you want to use a different ID WhatsApp Group than the one used in the plugin options page.', 'cresta-whatsapp-chat' ); ?></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Shortcode', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<span class="description"><?php esc_html_e('You can place the shortcode in posts or pages you want to display the WhatsApp button:', 'cresta-whatsapp-chat'); ?>
<pre><code>[cresta-help-chat text="Need Help? Contact Me!" icon="yes" position="top" use="number" number=""]</code></pre>
								</span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'PHP Code', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<span class="description"><?php esc_html_e('If you want to add the WhatsApp button in the theme code you can use this PHP code:', 'cresta-whatsapp-chat'); ?>
<pre><code>&lt;?php
	if(function_exists(&#039;cresta_help_chat_shortcode&#039;)) {
		echo do_shortcode('[cresta-help-chat text="Need Help? Contact Me!" icon="yes" position="left" use="group" group=""]');
	}
?&gt;</code></pre>
								</span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Button color:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<span class="description getPRO"><?php esc_html_e('PRO Version', 'cresta-whatsapp-chat'); ?></span>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Button background:', 'cresta-whatsapp-chat' ); ?></th>
							<td>
								<span class="description getPRO"><?php esc_html_e('PRO Version', 'cresta-whatsapp-chat'); ?></span>
							</td>
						</tr>
					</tbody>
				</table>
				<?php submit_button(); ?>
			</form>
			</div> <!-- .inside -->
			</div> <!-- .postbox -->
			</div> <!-- .meta-box-sortables .ui-sortable -->
			</div> <!-- post-body-content -->
			<!-- sidebar -->
				<div id="postbox-container-1" class="postbox-container">
					<div class="meta-box-sortables">
						<div class="postbox">
							<h3><span><div class="dashicons dashicons-star-filled"></div> <?php esc_html_e( 'Rate it!', 'cresta-whatsapp-chat' ); ?></span></h3>
							<div class="inside">
								<?php echo wp_kses_post( 'Do not forget to rate <strong>Cresta Help Chat</strong> on WordPress Pugins Directory.<br/>We really appreciate it ;)', 'cresta-whatsapp-chat' ); ?>
								<br/>
								<img src="<?php echo esc_url( plugins_url( '/images/5-stars.png' , __FILE__ )); ?>">
								<br/>
								<a class="crestaButton" href="https://wordpress.org/support/plugin/cresta-whatsapp-chat/reviews/#new-post"title="<?php esc_attr_e( 'Rate Cresta Help Chat on Wordpress Plugins Directory', 'cresta-whatsapp-chat' ); ?>" class="btn btn-primary" target="_blank"><?php esc_html_e( 'Rate Cresta Help Chat', 'cresta-whatsapp-chat' ); ?></a>
							</div> <!-- .inside -->
						</div> <!-- .postbox -->

						<div class="postbox" style="border: 4px solid #25d366;">
							
							<h3><span><div class="dashicons dashicons-megaphone"></div> <?php esc_html_e( 'Need More? Get the PRO version', 'cresta-whatsapp-chat' ); ?></span></h3>
							<div class="inside">
								<a href="https://crestaproject.com/downloads/cresta-help-chat/?utm_source=plugin_whatsapp&utm_medium=moreinfo_meta" target="_blank" alt="Get Cresta Help Chat PRO"><img src="<?php echo esc_url(plugins_url( '/images/banner-cresta-help-chat-pro.png' , __FILE__ )); ?>"></a><br/>
								<?php echo wp_kses_post( 'Get <strong>Cresta Help Chat PRO</strong> for only', 'cresta-whatsapp-chat' ); ?> <strong>12,99&euro;</strong><br/>
								<ul>
									<li><div class="dashicons dashicons-yes crestaGreen"></div> <?php esc_html_e( 'Change position of the box button', 'cresta-whatsapp-chat' ); ?></li>
									<li><div class="dashicons dashicons-yes crestaGreen"></div> <?php esc_html_e( 'Change WhatsApp number, title and welcome text for specific post/page', 'cresta-whatsapp-chat' ); ?></li>
									<li><div class="dashicons dashicons-yes crestaGreen"></div> <?php esc_html_e( 'Choose up to 3 box styles', 'cresta-whatsapp-chat' ); ?></li>
									<li><div class="dashicons dashicons-yes crestaGreen"></div> <?php esc_html_e( 'Change colors of the button, shortcode and text', 'cresta-whatsapp-chat' ); ?></li>
									<li><div class="dashicons dashicons-yes crestaGreen"></div> <?php esc_html_e( 'Add tooltip on the box button', 'cresta-whatsapp-chat' ); ?></li>
									<li><div class="dashicons dashicons-yes crestaGreen"></div> <?php esc_html_e( 'Change box size', 'cresta-whatsapp-chat' ); ?></li>
									<li><div class="dashicons dashicons-yes crestaGreen"></div> <?php esc_html_e( 'WhatsApp widget', 'cresta-whatsapp-chat' ); ?></li>
									<li><div class="dashicons dashicons-yes crestaGreen"></div> <?php esc_html_e( '4 Click animations and 9 icon animations', 'cresta-whatsapp-chat' ); ?></li>
									<li><div class="dashicons dashicons-yes crestaGreen"></div> <?php esc_html_e( '20% discount code for all CrestaProject Themes', 'cresta-whatsapp-chat' ); ?></li>
									<li><div class="dashicons dashicons-yes crestaGreen"></div> <?php esc_html_e( '1 year updates and support', 'cresta-whatsapp-chat' ); ?></li>
									<li><div class="dashicons dashicons-yes crestaGreen"></div> <?php esc_html_e( 'and Much More...', 'cresta-whatsapp-chat' ); ?></li>
								</ul>
								<a class="crestaButton" href="https://crestaproject.com/downloads/cresta-help-chat/?utm_source=plugin_whatsapp&utm_medium=moreinfo_meta" target="_blank" title="<?php esc_attr_e( 'More Details', 'cresta-whatsapp-chat' ); ?>"><?php esc_html_e( 'More Details', 'cresta-whatsapp-chat' ); ?></a>
							</div> <!-- .inside -->
						 </div> <!-- .postbox -->
						<div class="postbox" style="border: 4px solid #d54e21;">
							
							<h3><span><div class="dashicons dashicons-admin-plugins"></div> <?php esc_html_e( 'Cresta Social Share Counter Plugin', 'cresta-whatsapp-chat' ); ?></span></h3>
							<div class="inside">
								<a href="https://crestaproject.com/downloads/cresta-social-share-counter/" target="_blank" alt="Get Cresta Social Share Counter"><img src="<?php echo plugins_url( '/images/banner-cresta-social-share-counter.png' , __FILE__ ); ?>"></a><br/>
								<?php esc_html_e( 'Share your posts and pages quickly and easily with Cresta Social Share Counter showing the share count.', 'cresta-whatsapp-chat' ); ?>
								<a class="crestaButton" href="https://crestaproject.com/downloads/cresta-social-share-counter/" target="_blank" title="<?php esc_attr_e( 'Cresta Social Share Counter', 'cresta-whatsapp-chat' ); ?>"><?php esc_html_e( 'Available in FREE and PRO version', 'cresta-whatsapp-chat' ); ?></a>
							</div> <!-- .inside -->
						 </div> <!-- .postbox -->
						<div class="postbox" style="border: 4px solid #0084ff;">
                            
                            <h3><span><div class="dashicons dashicons-admin-plugins"></div> Cresta Posts Box Plugin</span></h3>
                            <div class="inside">
                                <a href="https://crestaproject.com/downloads/cresta-posts-box/" target="_blank" alt="Get Cresta Posts Box"><img src="<?php echo plugins_url( '/images/banner-cresta-posts-box.png' , __FILE__ ); ?>"></a><br/>
								Show the next or previous post in a box that appears when <strong>the user scrolls to the bottom of a current post</strong>.<br/>
								With <strong>Cresta Posts Box</strong> you can show, in a single page (posts, pages or custom post types), a <strong>small box that allows the reader to go to the next or previous post</strong>. The box appears only when the reader finishes reading the current post.
								<a class="crestaButton" href="https://crestaproject.com/downloads/cresta-posts-box/" target="_blank" title="Cresta Posts Box">Available in FREE and PRO version</a>
                            </div> <!-- .inside -->
                         </div> <!-- .postbox -->
					</div> <!-- .meta-box-sortables -->
				</div> <!-- #postbox-container-1 .postbox-container -->
			</div> <!-- #post-body .metabox-holder .columns-2 -->
			<br class="clear">
		</div> <!-- #poststuff -->
	</div>
	<?php
	echo ob_get_clean();
}

/* Get the current ID */
function crestawhatsappchat_get_the_current_ID() {
	$theID = '';
	if ( is_singular() ) {
		$theID = get_the_ID();
	} elseif (function_exists( 'is_woocommerce' ) && is_shop()) {
		$theID = get_option('woocommerce_shop_page_id');
	} elseif (is_home() && !is_front_page()) {
		$theID = get_option('page_for_posts');
	}
	$theID = apply_filters( 'cresta_help_chat_post_id', $theID );
	$theID = $theID ? $theID : '';
	return $theID;
}

/* Validate options */
function crestawhatsappchat_options_validate($input) {
	$input['cresta_whatsapp_chat_choose_using'] = wp_filter_nohtml_kses($input['cresta_whatsapp_chat_choose_using']);
	$input['cresta_whatsapp_chat_phone_number'] = sanitize_text_field($input['cresta_whatsapp_chat_phone_number']);
	$input['cresta_whatsapp_chat_id_group'] = sanitize_text_field($input['cresta_whatsapp_chat_id_group']);
	$input['cresta_whatsapp_chat_id_open'] = sanitize_text_field(wp_unslash($input['cresta_whatsapp_chat_id_open']));
	$input['cresta_whatsapp_chat_box_default_text'] = sanitize_text_field($input['cresta_whatsapp_chat_box_default_text']);
	$input['cresta_whatsapp_chat_box_text'] = sanitize_text_field($input['cresta_whatsapp_chat_box_text']);
	$input['cresta_whatsapp_chat_box_send'] = sanitize_text_field($input['cresta_whatsapp_chat_box_send']);
	$input['cresta_whatsapp_chat_zindex'] = sanitize_text_field(absint($input['cresta_whatsapp_chat_zindex']));
	$input['cresta_whatsapp_chat_show_floating_box'] = wp_filter_nohtml_kses($input['cresta_whatsapp_chat_show_floating_box']);
	$input['cresta_whatsapp_chat_mobile_option'] = sanitize_text_field(wp_unslash($input['cresta_whatsapp_chat_mobile_option']));
	$input['cresta_whatsapp_chat_click_to_close'] = wp_filter_nohtml_kses($input['cresta_whatsapp_chat_click_to_close']);
	$input['cresta_whatsapp_chat_click_button_open'] = sanitize_text_field(wp_unslash($input['cresta_whatsapp_chat_click_button_open']));
	if($input['cresta_whatsapp_chat_selected_page'] != '' && is_array($input['cresta_whatsapp_chat_selected_page'])) {
		$box_show_on = implode(',',$input['cresta_whatsapp_chat_selected_page']);
		$input['cresta_whatsapp_chat_selected_page'] = wp_filter_nohtml_kses($box_show_on); 
	} else {
		$input['cresta_whatsapp_chat_selected_page'] = 'homepage,blogpage,post,page'; 
	}
	return $input;
}
?>