<?php

namespace NativeRent\Admin;

use NativeRent\Monetizations;
use NativeRent\Options as Options;
use NativeRent\Site_Moderation_Status;

use function __;
use function add_action;
use function add_menu_page;
use function class_exists;
use function defined;
use function esc_attr;
use function esc_html;
use function esc_html_e;
use function filemtime;
use function filter_input;
use function get_permalink;
use function get_posts;
use function is_admin;
use function is_null;
use function nativerent_clear_cache_possible;
use function plugin_dir_path;
use function plugins_url;
use function wp_enqueue_script;
use function wp_enqueue_style;
use function wp_nonce_field;

use const FILTER_SANITIZE_EMAIL;
use const FILTER_SANITIZE_STRING;
use const INPUT_GET;
use const INPUT_POST;
use const NATIVERENT_PARAM_AUTH;
use const NATIVERENT_PLUGIN_FILE;

defined( 'ABSPATH' ) || exit;

/**
 * Nativerent Main Settings class
 */
class Settings {
	const CLEAR_CACHE_NAME = 'NativeRentAdmin_dropSiteCache';
	const NONCE_NAME = 'NativeRentAdminNonce';
	const NONCE_ACTION = 'NativeRentAdmin';
	const LOGIN_FIELD = 'NativeRentnAdminOption_Login';
	const PASS_FIELD = 'NativeRentnAdminOption_Password';

	/**
	 * The single instance of the class.
	 *
	 * @var self|null
	 */
	private static $instance = null;

	/**
	 * Auth form flag.
	 *
	 * @var bool|null
	 */
	private $need_to_show_auth_form = null;

	/**
	 * Monetizations instance.
	 *
	 * @var Monetizations|null
	 */
	private $monetizations = null;

	/**
	 * Site moderation status instance.
	 *
	 * @var Site_Moderation_Status|null
	 */
	private $site_moderation_status = null;

	/**
	 * Main Instance.
	 *
	 * @return self
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/**
	 * A dummy magic method to prevent class from being cloned
	 */
	public function __clone() {
	}

	/**
	 * A dummy magic method to prevent class from being unserialized
	 */
	public function __wakeup() {
	}

	/**
	 * Constructor is private to privent creating new instances
	 */
	private function __construct() {
		// Include static files.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Register admin page on hook.
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );

		add_action(
			'admin_init',
			function () {
				// Display a notice that the site on moderation.
				if (
					$this->is_settings_page()
					&& $this->get_site_moderation_status()->is_moderation()
					&& ! $this->get_monetizations()->is_all_rejected()
				) {
					Notices::add_notice( 'site_on_moderation' );
				}
			}
		);
	}

	/**
	 * Get monetizations with cache.
	 *
	 * @return Monetizations
	 */
	private function get_monetizations() {
		if ( is_null( $this->monetizations ) ) {
			$this->monetizations = Options::get_monetizations();
		}

		return $this->monetizations;
	}

	/**
	 * Get site moderation status.
	 *
	 * @return Site_Moderation_Status
	 */
	private function get_site_moderation_status() {
		if ( is_null( $this->site_moderation_status ) ) {
			$this->site_moderation_status = Options::get_site_moderation_status();
		}

		return $this->site_moderation_status;
	}


	/**
	 * Checking conditions to show auth form;
	 *
	 * @return bool
	 */
	private function need_to_show_auth_form() {
		if ( is_null( $this->need_to_show_auth_form ) ) {
			$this->need_to_show_auth_form = (
				! Options::authenticated()
				|| ( isset( $_GET[ NATIVERENT_PARAM_AUTH ] ) && Options::invalid_token() ) // phpcs:ignore WordPress.Security.NonceVerification
			);
		}

		return $this->need_to_show_auth_form;
	}

	/**
	 * Ad Units
	 */
	private function get_ad_units() {
		return array(
			'horizontalTop'    => array(
				'title'       => __( '1. Верхний блок', 'nativerent' ),
				'description' => __(
					'Должен быть виден пользователю при загрузке страницы без прокрутки экрана. Рекомендуем размещать блок в самом верху статьи: после заголовка, после анонса статьи или перед оглавлением.',
					'nativerent'
				),
			),
			'horizontalMiddle' => array(
				'title'       => __( '2. Средний блок', 'nativerent' ),
				'description' => __( 'Рекомендуем размещать блок в центре статьи.', 'nativerent' ),
			),
			'horizontalBottom' => array(
				'title'       => __( '3. Нижний блок', 'nativerent' ),
				'description' => __(
					'Рекомендуем размещать блок внизу статьи, лучше всего после последнего абзаца.',
					'nativerent'
				),
			),
		);
	}

	/**
	 * Get advanced selection options
	 */
	private function get_select_options() {
		return array(
			'before' => __( 'Перед', 'nativerent' ),
			'after'  => __( 'После', 'nativerent' ),
		);
	}

	/**
	 * Get advanced selection options
	 */
	private function get_advanced_select_options() {
		return array(
			'firstParagraph'  => array(
				'before' => __( 'первым абзацем (p)', 'nativerent' ),
				'after'  => __( 'первого абзаца (p)', 'nativerent' ),
			),
			'middleParagraph' => array(
				'before' => __( 'средним абзацем (p)', 'nativerent' ),
				'after'  => __( 'среднего абзаца (p)', 'nativerent' ),
			),
			'lastParagraph'   => array(
				'before' => __( 'последним абзацем (p)', 'nativerent' ),
				'after'  => __( 'последнего абзаца (p)', 'nativerent' ),
			),
			'firstTitle'      => array(
				'before' => __( 'первым заголовком (h2)', 'nativerent' ),
				'after'  => __( 'первого заголовка (h2)', 'nativerent' ),
			),
			'middleTitle'     => array(
				'before' => __( 'средним заголовком (h2)', 'nativerent' ),
				'after'  => __( 'среднего заголовка (h2)', 'nativerent' ),
			),
			'lastTitle'       => array(
				'before' => __( 'последним заголовком (h2)', 'nativerent' ),
				'after'  => __( 'последнего заголовка (h2)', 'nativerent' ),
			),
			''                => array(
				'before' => __( '(задать свой селектор)', 'nativerent' ),
				'after'  => __( '(задать свой селектор)', 'nativerent' ),
			),
		);
	}

	/**
	 * Add menu page
	 */
	public function add_menu_page() {
		add_menu_page(
			'Native Rent',
			'Native Rent',
			'manage_options',
			'nativerent',
			array( $this, 'options_page' ),
			plugins_url( 'admin/static/icon.png', NATIVERENT_PLUGIN_FILE )
		);
	}

	/**
	 * Enqueue scripts and styles
	 */
	public function enqueue_scripts() {
		// Add button-form lib globally.
		wp_enqueue_script(
			'nativerent-button-form-script',
			plugins_url( 'js/button-form.js', __FILE__ ),
			array(),
			plugin_dir_path( __FILE__ ) . 'js/button-form.js',
			true
		);

		if ( $this->is_settings_page() ) {
			wp_enqueue_script(
				'nativerent-admin-script',
				plugins_url( 'static/main.js', __FILE__ ),
				array(),
				filemtime( plugin_dir_path( __FILE__ ) . '/static/main.css' ),
				false
			);
			wp_enqueue_style(
				'nativerent-admin-style',
				plugins_url( 'static/main.css', __FILE__ ),
				array(),
				filemtime( plugin_dir_path( __FILE__ ) . '/static/main.css' )
			);
		}
	}

	/**
	 * Display admin page.
	 *
	 * @return void
	 */
	public function options_page() { ?>
		<div class="wrap">
			<div class="card NativeRentAdmin_header">
				<img src="https://nativerent.ru/img/logo.svg"
					 class="NativeRentAdmin_logo"
					 title="Native Rent"
					 alt="Native Rent"/>
			</div>
			<div class="card NativeRentAdmin_container">
				<h1><?php esc_html_e( 'Интеграция с платформой Native Rent', 'nativerent' ); ?></h1>

				<?php
				if ( $this->need_to_show_auth_form() ) {
					// Auth form.
					$this->auth_form();
				} elseif (
					$this->get_monetizations()->is_all_rejected() || $this->get_site_moderation_status()->is_rejected()
				) {
					// All monetizations were rejected.
					$this->all_monetizations_rejected_page();
				} else {
					// Show settings.
					$this->settings_form();
				}
				?>
			</div>
			<?php ( ! $this->need_to_show_auth_form() ? $this->show_footer() : '' ); ?>
		</div>
		<?php
	}

	/**
	 * Initial connection form
	 */
	private function auth_form() {
		?>
		<form method="post">
			<?php $this->add_description(); ?>
			<h2 class="itemTitle">
				<?php
				esc_html_e(
					'Введите e-mail и пароль от вашего аккаунта на платформе Native Rent',
					'nativerent'
				);
				?>
			</h2>

			<?php
			/** @codingStandardsIgnoreStart */
			if (
				isset( $_POST[ Actions::ACTIONS_PARAM_NAME ] )
				&& Actions::ACTION_INIT === $_POST[ Actions::ACTIONS_PARAM_NAME ]
			) {
				/** @codingStandardsIgnoreEnd */
				?>
				<div class="attention">
					<?php
					if ( ! empty( $_SESSION['NativeRentAuthError'] ) ) {
						echo esc_html( (string) $_SESSION['NativeRentAuthError'] );
					} else {
						esc_html_e( 'Неверный e-mail или пароль. Пожалуйста, попробуйте снова.', 'nativerent' );
					}
					?>
				</div>
			<?php } ?>

			<table class="form-table">
				<tbody>
				<tr>
					<th class="NativeRentAdmin_veryShortName" scope="row">
						<?php
						esc_html_e( 'E-mail', 'nativerent' );
						?>
					</th>
					<td class="forminp forminp-text">
						<input type="email"
							   name="<?php echo esc_attr( self::LOGIN_FIELD ); ?>"
							   aria-required="true"
							   value="<?php echo filter_input( INPUT_POST, self::LOGIN_FIELD, FILTER_SANITIZE_EMAIL ); ?>"
							   required="required"
							   autofocus/>
					</td>
				</tr>
				<tr>
					<th class="NativeRentAdmin_veryShortName" scope="row">
						<?php
						esc_html_e( 'Пароль', 'nativerent' );
						?>
					</th>
					<td class="forminp forminp-text">
						<input type="password"
							   name="<?php echo esc_attr( self::PASS_FIELD ); ?>"
							   aria-required="true"
							   required/>
					</td>
				</tr>
				</tbody>
			</table>

			<p class="submit">
				<input type="submit" value="<?php esc_html_e( 'Подключиться', 'nativerent' ); ?>"
					   class="button button-primary"/>
				<input type="hidden"
					   name="<?php echo esc_attr( Actions::ACTIONS_PARAM_NAME ); ?>"
					   value="<?php echo esc_attr( Actions::ACTION_INIT ); ?>"
				/>
				<?php $this->get_nonce_field(); ?>
			</p>
			<p>
				<?php esc_html_e( 'Забыли пароль?', 'nativerent' ); ?>
				<a href="https://nativerent.ru/password/reset" target="_blank">
					<?php esc_html_e( 'Восстановить', 'nativerent' ); ?>
				</a>
				<br/>
			</p>
			<p>
				<a href="https://nativerent.ru/register/partner"
				   target="_blank">
					<?php esc_html_e( 'Регистрация на платформе Native Rent', 'nativerent' ); ?>
				</a>
			</p>
		</form>
		<?php
	}

	/**
	 * Echo internal notice.
	 *
	 * @param  string           $title  Title.
	 * @param  string           $body   Body HTML.
	 * @param  'info'|'warning' $type   Notice type.
	 *
	 * @return void
	 */
	private static function e_internal_notice( $title, $body, $type = 'info' ) {
		$icons = array(
			'info'    => 'info-outline',
			'warning' => 'warning',
		);
		?>
		<div class="NativeRentAdmin_notice NativeRentAdmin_notice-<?php echo esc_attr( $type ); ?>">
			<div class="NativeRentAdmin_notice-icon">
				<span class="dashicons dashicons-<?php echo esc_attr( @$icons[ $type ] ); ?>"></span>
			</div>
			<div class="NativeRentAdmin_notice-content">
				<?php if ( ! empty( $title ) ) : ?>
					<div class="NativeRentAdmin_notice-title"><?php echo esc_html( $title ); ?></div>
				<?php endif ?>
				<div class="NativeRentAdmin_notice-body"><?php echo wp_kses( $body, 'post' ); ?></div>
			</div>
		</div>
		<?php
	}

	/**
	 * Page with notice about moderation process.
	 *
	 * @return void
	 */
	private function all_monetizations_on_moderation_page() {
		self::e_internal_notice(
			'Способы монетизации сайта на модерации',
			sprintf(
				'<p>%s</p><p>%s</p>',
				esc_html( 'Плагин расставит коды вставки рекламы после того, как модератор одобрит возможность подключения рекламных продуктов.' ),
				esc_html( 'Вы получите уведомление на почту.' )
			)
		);
	}

	/**
	 * Page with notice about rejected all monetizations.
	 *
	 * @return void
	 */
	private function all_monetizations_rejected_page() {
		self::e_internal_notice(
			'Сайт отклонен модератором, монетизация отключена',
			sprintf(
				'<p>%s <a href="mailto:tech@native.rent">tech@native.rent</a>.</p>',
				esc_html( 'Если есть вопросы, напишите нам на электронную почту' )
			),
			'warning'
		);
	}

	/**
	 * Description
	 */
	private function add_description() {
		?>
		<div class="NativeRentAdmin_description">
			<p>
				<?php
				esc_html_e(
					'Native Rent — платформа, объединяющая владельцев контентных сайтов и рекламодателей.',
					'nativerent'
				)
				?>
			</p>
			<p>
				<?php
				esc_html_e(
					'Владельцы сайтов подключаются к платформе, а рекламодатели выбирают статьи, где хотят разместить свою рекламу. Есть несколько способов монетизации:',
					'nativerent'
				);
				?>
			</p>
			<ul style="list-style-type: '- '; margin-left: 20px;">
				<li>
					<?php
					esc_html_e(
						'рекламодатель "арендует статью" целиком, вся остальная реклама на странице отключается;',
						'nativerent'
					);
					?>
				</li>
				<li>
					<?php
					esc_html_e(
						'рекламодатель выкупает только одно рекламное место на странице для своего неуникального текстово-графического блока.',
						'nativerent'
					);
					?>
				</li>
			</ul>
		</div>
		<?php
	}

	/**
	 * Settings form
	 */
	private function settings_form() {
		// Load ad-units config from options.
		$ad_units_config = Options::get_adunits_config();
		$selected        = 'before';
		?>
		<form method="post" onchange="NativeRentAdmin_submitEnable( this )" id="NativeRentAdmin_settingsForm">
			<div class="NativeRentAdmin_description">
				<?php
				esc_html_e(
					'Выберите места на страницах статей, где будет выводиться реклама. Можно указать селектор, который будет показывать место на странице для блока, или использовать преднастроенные селекторы параграфов (p).',
					'nativerent'
				);
				?>
			</div>

			<?php if ( ! $this->get_monetizations()->is_regular_rejected() ) : ?>
				<div class="NativeRentAdmin_settings-section">
					<h2 class="itemTitle"><?php esc_html_e( 'Коды вставки аренды статей', 'nativerent' ); ?></h2>
					<table class="form-table">
						<tbody>
						<tr>
							<td colspan="2" class="NativeRentAdmin_description">
								<div class="NativeRentAdmin_description">
									<?php
									esc_html_e(
										'Расположение блока влияет на эффективность рекламных кампаний. Рекламодатели выбирают сайты, у которых выше видимость блоков и CTR.',
										'nativerent'
									);
									?>
									<br/>
									<?php
									esc_html_e(
										'В длинных статьях, где расстояние между блоками очень большое, автоматически могут быть встроены до двух дополнительных блоков, дублирующих верхний или нижний блок.',
										'nativerent'
									);
									?>
								</div>
							</td>
						</tr>
						<?php
						foreach ( $this->get_ad_units() as $type => $options ) {
							if ( empty( $ad_units_config['regular'][ $type ] ) ) {
								continue;
							}
							?>
							<tr>
								<th scope="row" class="NativeRentAdmin_shortName">
									<?php echo esc_html( $options['title'] ); ?>
								</th>
								<td class="forminp forminp-text">
									<?php $this->show_placement_selectors( 'regular', $type, $ad_units_config['regular'] ); ?>
								</td>
							</tr>
							<tr>
								<td colspan="2" class="NativeRentAdmin_description">
									<div class="NativeRentAdmin_description">
										<?php echo esc_attr( $options['description'] ); ?>
									</div>
								</td>
							</tr>
							<?php
						}
						?>
						<tr>
							<th scope="row" class="NativeRentAdmin_shortName">
								<?php esc_html_e( '4. Всплывающий блок', 'nativerent' ); ?>
								<input type="hidden" name="NativeRentAdmin_adUnitsConfig[regular][popupTeaser][insert]"
									   value="inside"/>
								<input type="hidden" name="NativeRentAdmin_adUnitsConfig[regular][popupTeaser][autoSelector]"
									   value="body"/>
								<input type="hidden" name="NativeRentAdmin_adUnitsConfig[regular][popupTeaser][customSelector]"
									   value=""/>
							</th>
							<td class="forminp forminp-text">
								<label for="NativeRentAdmin_adUnitsConfig_teaser_desktopTeaser">
									<input type="checkbox" id="NativeRentAdmin_adUnitsConfig_teaser_desktopTeaser"
										   name="NativeRentAdmin_adUnitsConfig[regular][popupTeaser][settings][desktopTeaser]"
										   value="1"
										<?php
										echo $ad_units_config['regular']['popupTeaser']['settings']['desktopTeaser'] ? 'checked="checked"'
											: '';
										?>
									/>
									<?php esc_html_e( 'разрешить вывод тизера на десктопе', 'nativerent' ); ?>
								</label>
								<br/>
								<label for="NativeRentAdmin_adUnitsConfig_teaser_mobileTeaser">
									<input
										type="checkbox"
										id="NativeRentAdmin_adUnitsConfig_teaser_mobileTeaser"
										name="NativeRentAdmin_adUnitsConfig[regular][popupTeaser][settings][mobileTeaser]"
										value="1"
										<?php
										echo $ad_units_config['regular']['popupTeaser']['settings']['mobileTeaser'] ? 'checked="checked"'
											: '';
										?>
									/>
									<?php esc_html_e( 'разрешить вывод тизера на мобильных платформах', 'nativerent' ); ?>
								</label>
								<br/>
								<label for="NativeRentAdmin_adUnitsConfig_teaser_mobileFullscreen">
									<input
										type="checkbox"
										id="NativeRentAdmin_adUnitsConfig_teaser_mobileFullscreen"
										name="NativeRentAdmin_adUnitsConfig[regular][popupTeaser][settings][mobileFullscreen]"
										value="1"
										<?php
										echo $ad_units_config['regular']['popupTeaser']['settings']['mobileFullscreen'] ? 'checked="checked"'
											: '';
										?>
									/>
									<?php esc_html_e( 'разрешить вывод фулскрина на мобильных платформах', 'nativerent' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="NativeRentAdmin_description">
								<div class="NativeRentAdmin_description">
									<p>
										<?php
										esc_html_e(
											'Всплывающий блок показывается в трех форматах:',
											'nativerent'
										);
										?>
									</p>
									<ul style="list-style-type: '- '; margin-left: 10px;">
										<li>
											<?php
											esc_html_e(
												'Тизер высотой до 100 пикселей для десктопа',
												'nativerent'
											);
											?>
										</li>
										<li>
											<?php
											esc_html_e(
												'Тизер высотой до 100 пикселей внизу экрана для мобильных',
												'nativerent'
											);
											?>
										</li>
										<li>
											<?php
											esc_html_e( 'Фулскрин только для мобильных', 'nativerent' );
											?>
										</li>
									</ul>
									<p>
										<?php
										esc_html_e(
											'При каждой загрузке страницы отображается только один из форматов всплывающего блока.',
											'nativerent'
										);
										?>
									</p>
								</div>
								<?php $this->show_demo_notice(); ?>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
			<?php endif ?>

			<?php if ( ! $this->get_monetizations()->is_ntgb_rejected() ) : ?>
				<?php
				$type              = 'ntgb';
				$active_ntgb_count = count( Options::get_active_ntgb_units( $ad_units_config['ntgb'] ) );
				?>
				<div class="NativeRentAdmin_settings-section" id="NativeRentAdmin_settings-section-ntgb">
					<h2 class="itemTitle">
						<?php esc_html_e( 'Коды вставки неуникального текстово-графического блока (НТГБ)', 'nativerent' ); ?>
					</h2>
					<table class="form-table">
						<tbody>
						<tr>
							<td colspan="2" class="NativeRentAdmin_description">
								<div class="NativeRentAdmin_description">
									<?php
									esc_html_e(
										'Не размещайте блок НТГБ вплотную с другими рекламными блоками. При показе этого блока реклама других сетей блокироваться не будет.',
										'nativerent'
									);
									?>
								</div>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( 'Выводить максимум', 'nativerent' ); ?></th>
							<td class="forminp forminp-text">
								<input type="number" min="1" max="3"
									   id="NativeRentAdmin_ntgbUnitsNum"
									   value="<?php echo esc_attr( $active_ntgb_count ); ?>">
								<span class="_label">
									<?php if ( $active_ntgb_count < 2 ) : ?>
										<?php esc_html_e( 'блок на странице', 'nativerent' ); ?>
									<?php else : ?>
										<?php esc_html_e( 'блока на странице', 'nativerent' ); ?>
									<?php endif; ?>
								</span>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="NativeRentAdmin_description">
								<div class="NativeRentAdmin_description">
									<?php
									esc_html_e(
										'Между размещенными блоками на странице должно быть расстояние не менее 500 пикселей.',
										'nativerent'
									);
									?>
								</div>
							</td>
						</tr>

						<?php $_ntgb_unit_num = 0; ?>
						<?php foreach ( $ad_units_config['ntgb'] as $unit_id => $unit_config ) : ?>
							<?php
							$_ntgb_unit_num ++;
							$_inactive_class = ! empty( $unit_config['settings']['inactive'] ) ? ' ntgb-config-item-inactive' : '';
							?>
							<tr class="ntgb-config-item<?php echo esc_attr( $_inactive_class ); ?>"
								data-unit-num="<?php echo esc_attr( $_ntgb_unit_num ); ?>">
								<th scope="row" class="NativeRentAdmin_shortName">
									<?php echo esc_html( 'НТГБ ' . $unit_id ); ?>
								</th>
								<td class="forminp forminp-text">
									<?php $this->show_placement_selectors( 'ntgb', (string) $unit_id, $ad_units_config['ntgb'] ); ?>
								</td>
							</tr>
							<tr class="ntgb-config-item<?php echo esc_attr( $_inactive_class ); ?>"
								data-unit-num="<?php echo esc_attr( $_ntgb_unit_num ); ?>">
								<th scope="row" class="NativeRentAdmin_shortName">
									<?php esc_html_e( 'Вы можете добавить код-заглушку к НТГБ', 'nativerent' ); ?>
								</th>
								<td class="forminp forminp-text">
									<input type="hidden"
										   class="ntgb-config-item-inactive-input"
										   name="NativeRentAdmin_adUnitsConfig[ntgb][<?php echo esc_attr( $unit_id ); ?>][settings][inactive]"
										   value="<?php echo esc_attr( ! empty( $unit_config['settings']['inactive'] ) ? 1 : 0 ); ?>"/>
									<div class="NativeRentAdmin_fallbackCodeArea">
										<?php
										if ( empty( $unit_config['settings']['fallbackCode'] ) ) {
											$unit_config['settings']['fallbackCode'] = '';
										}
										?>
										<textarea
											name="NativeRentAdmin_adUnitsConfig[ntgb][<?php echo esc_attr( $unit_id ); ?>][settings][fallbackCode]"
											class="large-text code"
										><?php echo esc_textarea( base64_decode( $unit_config['settings']['fallbackCode'] ) ); ?></textarea>
										<p class="NativeRentAdmin_inputDescription">
											<?php
											esc_html_e(
												'Сохраните в этом поле HTML-код рекламного блока, который мы будет загружать на странице, когда нет рекламы Native Rent. Статистика показов этого кода не будет отображаться в статистике Native Rent.',
												'nativerent'
											);
											?>
										</p>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>

						<tr id="NativeRentAdmin_settings-section-ntgb-preview">
							<td colspan="2" class="NativeRentAdmin_description">
								<?php $this->show_demo_notice( true ); ?>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
			<?php endif ?>

			<script>
				if (typeof NativeRentAdmin_updateSelectors == 'function') {
					NativeRentAdmin_updateSelectors()
				} else {
					document.addEventListener('DOMContentLoaded', NativeRentAdmin_updateSelectors)
				}
			</script>
			<input type="hidden" name="<?php echo esc_attr( Actions::ACTIONS_PARAM_NAME ); ?>"
				   value="<?php echo esc_attr( Actions::ACTION_AD_UNITS_CONFIG ); ?>"/>

			<?php $this->get_nonce_field(); ?>
			<br/>

			<input type="submit"
				   value="<?php esc_html_e( 'Применить', 'nativerent' ); ?>"
				   class="button button-primary"
				   style="vertical-align: middle; margin-right: 10px"
				   disabled
			/>

			<?php
			if ( nativerent_clear_cache_possible() ) {
				$name = self::CLEAR_CACHE_NAME;
				?>
				<label for="<?php echo esc_attr( $name ); ?>"
					   id="<?php echo esc_attr( $name ); ?> Container">
					<input type="checkbox"
						   id="<?php echo esc_attr( $name ); ?>"
						   name="<?php echo esc_attr( $name ); ?>">
					<?php esc_html_e( 'Сбросить кэш', 'nativerent' ); ?>
				</label>
			<?php } ?>
		</form>
		<?php
	}

	/**
	 * Get URL for random published post.
	 *
	 * @return false|string
	 */
	private function get_random_post_url() {
		if ( ! class_exists( 'WP_Query' ) ) {
			return false;
		}

		$args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'orderby'        => 'rand',
		);

		$posts = get_posts( $args );

		if ( empty( $posts ) ) {
			return false;
		}

		$permalink = get_permalink( $posts[0]->ID );

		return ! $permalink ? false : $permalink;
	}

	/**
	 * Get nonce field
	 */
	private function get_nonce_field() {
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME );
	}

	/**
	 * Show notice with demo link.
	 *
	 * @param  bool $ntgb  NTGB flag.
	 *
	 * @return void
	 */
	private function show_demo_notice( $ntgb = false ) {
		$demo_url = $this->get_random_post_url();
		if ( ! empty( $demo_url ) ) {
			$nrdemo_val = ( $ntgb ? 3 : 1 );
			self::e_internal_notice(
				'',
				sprintf(
					'<p>%s (<a href="%s?_nrdemo=%d" target="_blank">%s</a>)</p>',
					esc_html(
						'Проверить отображение рекламных блоков ' .
						'можно на любой странице, где есть коды, добавив к ссылке параметр ?_nrdemo=' . $nrdemo_val
					),
					esc_attr( $demo_url ),
					$nrdemo_val,
					esc_html( 'пример' )
				)
			);
		}
	}

	/**
	 * Footer rendering.
	 *
	 * @return void
	 */
	public function show_footer() {
		?>
		<div class="card NativeRentAdmin_footer">
			<form method="post" id="NativeRentAdmin_PurgeForm">
				<input type="hidden"
					   name="<?php echo esc_attr( Actions::ACTIONS_PARAM_NAME ); ?>"
					   value="<?php echo esc_attr( Actions::ACTION_PURGE ); ?>"
				/>
				<?php $this->get_nonce_field(); ?>
				<input type="submit" style="display: none"/>
				<a href="javascript://" id="NativeRent_deactivatePluginButton">
					<?php esc_html_e( 'Прекратить работу плагина', 'nativerent' ); ?>
				</a>
				<?php esc_html_e( '(отключиться от платформы Native Rent)', 'nativerent' ); ?>
			</form>

			<script>
				(function () {
					var deactivatePluginButton = document.getElementById('NativeRent_deactivatePluginButton')
					if (deactivatePluginButton) {
						deactivatePluginButton.addEventListener('click', function () {
							document.getElementById('NativeRentAdmin_PurgeForm').submit()
						})
					}
				})()
			</script>
		</div>
		<?php
	}

	/**
	 * Showing placement selector.
	 *
	 * @param  array  $type       Type of units (regular, ntgb).
	 * @param  string $unit_name  Unit name.
	 * @param  array  $config     Units configuration.
	 *
	 * @return void
	 */
	private function show_placement_selectors( $type, $unit_name, $config ) {
		?>
		<select name="NativeRentAdmin_adUnitsConfig[<?php echo esc_attr( $type ); ?>][<?php echo esc_attr( $unit_name ); ?>][insert]"
				class="NativeRentAdmin_insertChange"
				onchange="NativeRentAdmin_insertChange( this, 'NativeRentAdmin_adUnitsConfig[<?php echo esc_attr( $type ); ?>][<?php echo esc_attr( $unit_name ); ?>][autoSelector]' )">
			<?php
			$selected = null;
			if ( ! empty( $config[ $unit_name ]['insert'] ) ) {
				$selected = $config[ $unit_name ]['insert'];
			}
			foreach ( $this->get_select_options() as $key => $select_option ) {
				if ( $selected === $key ) {
					echo '<option value="' . esc_attr( $key ) . '" selected="selected">'
						 . esc_html( $select_option ) . '</option>';
				} else {
					echo '<option value="' . esc_attr( $key ) . '">'
						 . esc_html( $select_option ) . '</option>';
				}
			}
			?>
		</select>
		<select name="NativeRentAdmin_adUnitsConfig[<?php echo esc_attr( $type ); ?>][<?php echo esc_attr( $unit_name ); ?>][autoSelector]"
				class="NativeRentAdmin_autoSelector"
				onchange="NativeRentAdmin_autoSelectorChanged( this, 'NativeRentAdmin_adUnitsConfig[<?php echo esc_attr( $type ); ?>][<?php echo esc_attr( $unit_name ); ?>][customSelector]' )">
			<?php
			if ( 'horizontalTop' === $unit_name ) {
				$selected = 'firstParagraph';
			}
			if ( 'horizontalMiddle' === $unit_name ) {
				$selected = 'middleParagraph';
			}
			if ( 'horizontalBottom' === $unit_name ) {
				$selected = 'lastParagraph';
			}
			if ( ! empty( $config[ $unit_name ]['customSelector'] ) ) {
				$selected = '';
			}
			if ( $config[ $unit_name ]['autoSelector'] ) {
				$selected = $config[ $unit_name ]['autoSelector'];
			}
			foreach ( $this->get_advanced_select_options() as $key => $select_option ) {
				if ( $selected === $key ) {
					echo '<option value="' . esc_attr( $key ) . '" selected="selected"></option>';
				} else {
					echo '<option value="' . esc_attr( $key ) . '"></option>';
				}
			}
			?>
		</select>
		<input type="text"
			   name="NativeRentAdmin_adUnitsConfig[<?php echo esc_attr( $type ); ?>][<?php echo esc_attr( $unit_name ); ?>][customSelector]"
			   class="NativeRentAdmin_customSelector"
			   value="<?php echo esc_attr( $config[ $unit_name ]['customSelector'] ); ?>"
			   placeholder="<?php esc_attr_e( 'пример: h3', 'nativerent' ); ?>"
		/>
		<?php
	}

	/**
	 * Check if this page is NativeRent settings page
	 *
	 * @return bool
	 */
	public function is_settings_page() {
		return ( is_admin() && 'nativerent' === filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) );
	}
}
