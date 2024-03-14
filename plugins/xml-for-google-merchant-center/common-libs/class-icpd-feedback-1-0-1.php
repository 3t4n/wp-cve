<?php defined( 'ABSPATH' ) || exit;
/**
 * This class is responsible for the feedback form inside the plugin
 *
 * @package			iCopyDoc Plugins (ICPD)
 * @subpackage		ENG
 * @since			0.1.0
 * 
 * @version			1.0.1 (14-08-2023)
 * @author			Maxim Glazunov
 * @link			https://icopydoc.ru/
 * @see				
 * 
 * @param	array	
 *
 * @return	void	html code
 *
 * @depends			classes:	WP_Query
 *					traits:		
 *					methods:	
 *					functions:	get_woo_version_number
 *					constants:	
 *					actions:	_feedback_block
 *					filters:	_f_feedback_additional_info
 *
 */

// 'xml-for-google-merchant-center' - slug for translation (be sure to make an autocorrect)
if ( ! class_exists( 'ICPD_Feedback' ) ) {
	final class ICPD_Feedback {
		/**
		 * Plugin name
		 * @var string
		 */
		private $plugin_name = '';
		/**
		 * Plugin version (For example: '1.0.0')
		 * @var string
		 */
		private $plugin_version = '0.1.0';
		/**
		 * Plugin prefix
		 * @var string
		 */
		private $pref = '';
		/**
		 * URL of the log file
		 * @var string
		 */
		private $logs_url = '';
		/**
		 * Additional information that can be passed to the report
		 * @var string
		 */
		private $additional_info = '';

		/**
		 * Summary of __construct
		 * 
		 * @param array $args
		 */
		public function __construct( $args = [] ) {
			if ( isset( $args['plugin_name'] ) ) {
				$this->plugin_name = $args['plugin_name'];
			}
			if ( isset( $args['plugin_version'] ) ) {
				$this->plugin_version = $args['plugin_version'];
			}
			if ( isset( $args['pref'] ) ) {
				$this->pref = $args['pref'];
			}
			if ( isset( $args['logs_url'] ) ) {
				$this->logs_url = $args['logs_url'];
			}
			if ( isset( $args['additional_info'] ) ) {
				$this->additional_info = $args['additional_info'];
			}

			$this->init_hooks();
		}

		/**
		 * Init hooks
		 * 
		 * @return void
		 */
		public function init_hooks() {
			add_action( 'admin_print_footer_scripts', [ $this, 'print_css_styles' ] );
			$hook_name = $this->get_pref() . '_feedback_block';
			add_action( $hook_name, [ $this, 'print_view_html_feedback_block' ] );

			if ( isset( $_REQUEST[ $this->get_submit_name()] ) ) {
				// ! Очень важно пускать через фильтр в этом месте, а иначе фильтр _f_feedback_additional_info
				// ! внутри фукцнии send_data не будет работать
				add_action( 'admin_init', [ $this, 'send_data' ], 10 );
				add_action( 'admin_notices', function () {
					$class = 'notice notice-success is-dismissible';
					$message = __( 'The data has been sent. Thank you', 'xml-for-google-merchant-center' );
					printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
				}, 9999 );
			}
		}

		/**
		 * Print css styles
		 * 
		 * @return void
		 */
		public function print_css_styles() {
			print( '<style>.clear{clear: both;} .icpd_bold {font-weight: 700;}
		.icpd_ul {list-style-type: square; margin: 5px 0px 3px 30px;}</style>' );
		}

		/**
		 * Print html of feedback block
		 * 
		 * @return void
		 */
		public function print_view_html_feedback_block() { ?>
			<div class="postbox">
				<h2 class="hndle">
					<?php _e( 'Send data about the work of the plugin', 'xml-for-google-merchant-center' ); ?>
				</h2>
				<div class="inside">
					<?php
					printf( '<p>%s! %s:</p>',
						__( 'Sending statistics you help make the plugin even better', 'xml-for-google-merchant-center' ),
						__( 'The following data will be sent', 'xml-for-google-merchant-center' )
					);
					?>
					<ul class="icpd_ul">
						<?php
						printf( '<li>%s</li><li>%s</li><li>%s %s</li></p>',
							__( 'PHP version information', 'xml-for-google-merchant-center' ),
							__( 'Multisite mode status', 'xml-for-google-merchant-center' ),
							__( 'Technical information and plugin logs', 'xml-for-google-merchant-center' ),
							esc_html( $this->get_plugin_name() )
						); ?>
					</ul>
					<form action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" method="post" enctype="multipart/form-data">
						<?php
						printf( '<p>%s %s?</p>',
							__( 'Did my plugin help you upload your products to the', 'xml-for-google-merchant-center' ),
							esc_html( $this->get_plugin_name() )
						);
						?>
						<p>
							<?php
							printf( '<input type="radio" value="yes" name="%s">%s<br />',
								esc_attr( $this->get_radio_name() ),
								__( 'Yes', 'xml-for-google-merchant-center' )
							);

							printf( '<input type="radio" value="no" name="%s">%s<br />',
								esc_attr( $this->get_radio_name() ),
								__( 'No', 'xml-for-google-merchant-center' )
							);
							?>
						</p>
						<p>
							<?php
							_e( "If you don't mind to be contacted in case of problems, please enter your email address",
								"xml-for-google-merchant-center"
							); ?>:
						</p>
						<p><input type="email" name="<?php echo $this->get_input_name(); ?>" placeholder="your@email.com"></p>
						<p>
							<?php _e( 'Your message', 'xml-for-google-merchant-center' ); ?>:
						</p>
						<p><textarea rows="6" cols="32" name="<?php echo $this->get_textarea_name(); ?>" placeholder="<?php
						   printf( '%1$s (%2$s). %3$s',
						   	__( 'Enter your text to send me a message', 'xml-for-google-merchant-center' ),
						   	__( 'You can write me in Russian or English', 'xml-for-google-merchant-center' ),
						   	__( 'I check my email several times a day', 'xml-for-google-merchant-center' )
						   ); ?>"></textarea></p>
						<?php wp_nonce_field( $this->get_nonce_action(), $this->get_nonce_field() ); ?>
						<input class="button-primary" type="submit" name="<?php echo $this->get_submit_name(); ?>"
							value="<?php _e( 'Send data', 'xml-for-google-merchant-center' ); ?>" />
					</form>
				</div>
			</div>
			<?php
		}

		/**
		 * Summary of send_data
		 * 
		 * @return void
		 */
		public function send_data() {
			if ( ! empty( $_POST )
				&& check_admin_referer( $this->get_nonce_action(), $this->get_nonce_field() ) ) {
				if ( is_multisite() ) {
					$multisite = 'включен';
				} else {
					$multisite = 'отключен';
				}
				$current_time = (string) current_time( 'Y-m-d H:i' );

				$mail_content = sprintf(
					'<h1>Заявка (#%1$s)</h1>
				<p>Сайт: %2$s<br />
				Версия плагина: %3$s<br />
				Версия WP: %4$s<br />
				Режим мультисайта: %4$s<br />
				Версия PHP: %6$s</p>%7$s',
					esc_html( $current_time ),
					home_url(),
					esc_html( $this->get_plugin_version() ),
					get_bloginfo( 'version' ),
					esc_html( $multisite ),
					phpversion(),
					esc_html( $this->get_additional_info() )
				);

				if ( class_exists( 'WooCommerce' ) ) {
					$mail_content .= sprintf( '<p>Версия WC: %1$s<br />',
						esc_html( get_woo_version_number() )
					);

					$argsp = [ 
						'post_type' => 'product',
						'post_status' => 'publish',
						'posts_per_page' => -1
					];
					$products = new \WP_Query( $argsp );
					$vsegotovarov = $products->found_posts;
					unset( $products );
					$mail_content .= sprintf( 'Число товаров: %1$s</p>',
						esc_html( $vsegotovarov )
					);
				}

				if ( is_multisite() ) {
					$keeplogs = get_blog_option( get_current_blog_id(), $this->get_pref() . '_keeplogs' );
				} else {
					$keeplogs = get_option( $this->get_pref() . '_keeplogs' );
				}
				if ( empty( $keeplogs ) ) {
					$mail_content .= "Вести логи: отключено<br />";
				} else {
					$mail_content .= "Вести логи: включено<br />";
					$mail_content .= sprintf(
						'Расположение логов: <a target="_blank" href="%1$s">%1$s</a><br />',
						$this->get_logs_url()
					);
				}

				if ( isset( $_POST[ $this->get_radio_name()] ) ) {
					$mail_content .= sprintf( 'Помог ли плагин: %1$s<br />',
						sanitize_text_field( $_POST[ $this->get_radio_name()] )
					);
				}
				if ( isset( $_POST[ $this->get_input_name()] ) ) {
					$mail_content .= sprintf(
						'Почта: <a href="mailto:%1$s?subject=%2$s %3$s (#%4$s)" target="_blank">%5$s</a><br />',
						sanitize_email( $_POST[ $this->get_input_name()] ),
						'Ответ разработчика',
						esc_html( $this->get_plugin_name() ),
						esc_html( $current_time ),
						sanitize_email( $_POST[ $this->get_input_name()] )
					);
				}
				if ( isset( $_POST[ $this->get_textarea_name()] ) ) {
					$mail_content .= sprintf( 'Сообщение: %1$s<br />',
						sanitize_text_field( $_POST[ $this->get_textarea_name()] )
					);
				}

				$additional_info = '';
				$filters_name = $this->get_pref() . '_f_feedback_additional_info';
				$additional_info = apply_filters( $filters_name, $additional_info );
				if ( is_string( $additional_info ) ) {
					$additional_info = preg_replace( '#<script(.*?)>(.*?)</script>#is', '', $additional_info );
					$mail_content .= $additional_info;
				}

				$subject = sprintf( 'Отчёт %1$s',
					esc_html( $this->get_plugin_name() )
				);
				add_filter( 'wp_mail_content_type', [ $this, 'set_html_content_type' ] );
				wp_mail( 'support@icopydoc.ru', $subject, $mail_content );
				// Сбросим content-type, чтобы избежать возможного конфликта
				remove_filter( 'wp_mail_content_type', [ $this, 'set_html_content_type' ] );
			}
		}

		/**
		 * Summary of set_html_content_type
		 * 
		 * @return string
		 */
		public static function set_html_content_type() {
			return 'text/html';
		}

		/**
		 * Summary of get_pref
		 * @return string
		 */
		private function get_pref() {
			return $this->pref;
		}

		/**
		 * Summary of get_plugin_name
		 * @return string
		 */
		private function get_plugin_name() {
			return $this->plugin_name;
		}

		/**
		 * Summary of get_plugin_version
		 * @return string
		 */
		private function get_plugin_version() {
			return $this->plugin_version;
		}

		/**
		 * Summary of get_logs_url
		 * @return string
		 */
		private function get_logs_url() {
			return $this->logs_url;
		}

		/**
		 * Summary of get_additional_info
		 * @return string
		 */
		private function get_additional_info() {
			return $this->additional_info;
		}

		/**
		 * Summary of get_radio_name
		 * @return string
		 */
		private function get_radio_name() {
			return $this->get_pref() . '_its_ok';
		}

		/**
		 * Summary of get_input_name
		 * @return string
		 */
		private function get_input_name() {
			return $this->get_pref() . '_email';
		}

		/**
		 * Summary of get_textarea_name
		 * @return string
		 */
		private function get_textarea_name() {
			return $this->get_pref() . '_message';
		}

		/**
		 * Summary of get_submit_name
		 * @return string
		 */
		private function get_submit_name() {
			return $this->get_pref() . '_submit_send_stat';
		}

		/**
		 * Summary of get_nonce_action
		 * @return string
		 */
		private function get_nonce_action() {
			return $this->get_pref() . '_nonce_action_send_stat';
		}

		/**
		 * Summary of get_nonce_field
		 * @return string
		 */
		private function get_nonce_field() {
			return $this->get_pref() . '_nonce_field_send_stat';
		}
	} // end final class ICPD_Feedback
} // end if (!class_exists('ICPD_Feedback'))