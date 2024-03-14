<?php
/**
 * The cta settings page
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rock_Convert\Inc\Admin
 * @link       https://rockcontent.com
 * @since      2.0.0
 *
 * @author     Rock Content
 */

namespace Rock_Convert\Inc\Admin;

use Rock_Convert\inc\libraries\MailChimp;

/**
 * Page Settings class define all plugins configuration
 */
class Page_Settings {

	/**
	 * Undocumented variable
	 *
	 * @var string $subscriptions_table_name Is the name of Rock Convert custom table.
	 */
	private string $subscriptions_table_name = 'rconvert-subscriptions';

	/**
	 * Register admin menu link
	 *
	 * @return void
	 */
	public function register() {
		add_submenu_page(
			'edit.php?post_type=cta',
			__( 'Configurações do Rock Convert', 'rock-convert' ),
			__( 'Configurações', 'rock-convert' ),
			'manage_options',
			'rock-convert-settings',
			array(
				$this,
				'display',
			)
		);
		add_filter( 'admin_footer_text', array( $this, 'custom_admin_footer' ) );
	}

	/**
	 * Add custom footer to plugin
	 *
	 * @return void
	 */
	public function custom_admin_footer() {
		$current_page = get_current_screen();
		$pages        = array( 'cta_page_rock-convert-settings', 'cta' );

		if ( in_array( $current_page->id, $pages, true ) ) {
			echo 'Rock Convert by
			<a href="' . esc_html( 'https://stage.rockcontent.com' ) . '" target="_blank">
				Stage
			</a> |
			<a href="' . esc_html( ROCK_CONVERT_REPORT_ERROR_URL ) . '" target="_blank">'
				. esc_html__( 'Entre em contato com o suporte', 'rock-convert' ) .
			'</a>';
		}
	}

	/**
	 * Save data from all form settings in the admin area.
	 *
	 * @return void
	 */
	public function save_settings_callback() {
		if ( isset( $_POST['rock_convert_settings_nonce'] )
		&& wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_POST['rock_convert_settings_nonce'] ) ),
			'rock_convert_settings_nonce'
		)
		) {
			$tab = sanitize_key( Utils::getArrayValue( $_POST, 'tab' ) );

			if ( 'general' === $tab ) {
				$this->update_general_tab();
			}

			if ( 'advanced' === $tab ) {
				$this->update_advanced_tab();
			}

			if ( 'integrations' === $tab ) {
				$this->update_integrations_tab();
			}

			if ( 'popup' === $tab ) {
				$this->update_popup_tab();
			}

			wp_safe_redirect(
				admin_url( 'edit.php?post_type=cta&page=rock-convert-settings&tab=' . $tab . '&success=true' )
			);
		}
	}

	/**
	 * Update PopUp data
	 *
	 * @return void
	 */
	protected function update_popup_tab() {
		if ( isset( $_POST['rock_convert_settings_nonce'] )
		&& wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_POST['rock_convert_settings_nonce'] ) ),
			'rock_convert_settings_nonce'
		)
		) {
			$popup_title              = Utils::getArrayValue( $_POST, 'rock_convert_popup_title' );
			$popup_descricao          = Utils::getArrayValue( $_POST, 'rock_convert_popup_descricao' );
			$popup_color              = Utils::getArrayValue( $_POST, 'rock_convert_popup_color' );
			$popup_activate           = Utils::getArrayValue( $_POST, 'rock_convert_popup_activate' );
			$popup_image_activate     = Utils::getArrayValue( $_POST, 'rock_convert_popup_image_activate' );
			$popup_image              = Utils::getArrayValue( $_POST, 'rock_convert_popup_image' );
			$popup_button_color       = Utils::getArrayValue( $_POST, 'rock_convert_popup_button_color' );
			$popup_title_color        = Utils::getArrayValue( $_POST, 'rock_convert_popup_title_color' );
			$popup_description_color  = Utils::getArrayValue( $_POST, 'rock_convert_popup_description_color' );
			$popup_button_text_color  = Utils::getArrayValue( $_POST, 'rock_convert_popup_button_text_color' );
			$popup_button_close_color = Utils::getArrayValue( $_POST, 'rock_convert_popup_button_close_color' );
			$popup_sanitize_title     = $popup_title ? str_replace( '\\', '', $popup_title ) : null;
			$popup_sanitize_descricao = $popup_descricao ? str_replace( '\\', '', $popup_descricao ) : null;

			update_option(
				'_rock_convert_popup_title',
				sanitize_text_field( $popup_sanitize_title )
			);

			update_option(
				'_rock_convert_popup_descricao',
				sanitize_text_field( $popup_sanitize_descricao )
			);

			update_option(
				'_rock_convert_popup_color',
				sanitize_hex_color( $popup_color )
			);

			update_option(
				'_rock_convert_popup_activate',
				sanitize_text_field( $popup_activate )
			);

			update_option(
				'_rock_convert_popup_image_activate',
				sanitize_text_field( $popup_image_activate )
			);

			update_option(
				'_rock_convert_popup_image',
				sanitize_text_field( $popup_image )
			);

			update_option(
				'_rock_convert_popup_button_color',
				sanitize_hex_color( $popup_button_color )
			);

			update_option(
				'_rock_convert_popup_title_color',
				sanitize_hex_color( $popup_title_color )
			);

			update_option(
				'_rock_convert_popup_description_color',
				sanitize_hex_color( $popup_description_color )
			);

			update_option(
				'_rock_convert_popup_button_text_color',
				sanitize_hex_color( $popup_button_text_color )
			);

			update_option(
				'_rock_convert_popup_button_close_color',
				sanitize_hex_color( $popup_button_close_color )
			);
		}
	}

	/**
	 * Update general data
	 *
	 * @return void
	 */
	protected function update_general_tab() {
		if ( isset( $_POST['rock_convert_settings_nonce'] )
		&& wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_POST['rock_convert_settings_nonce'] ) ),
			'rock_convert_settings_nonce'
		)
		) {
			$enable_analytics    = intval( Utils::getArrayValue( $_POST, 'rock_convert_enable_analytics' ) );
			$enable_name_field   = intval( Utils::getArrayValue( $_POST, 'rock_convert_enable_name_field' ) );
			$enable_custom_field = intval( Utils::getArrayValue( $_POST, 'rock_convert_enable_custom_field' ) );
			$custom_field_label  = Utils::getArrayValue( $_POST, 'rock_convert_custom_field_label' );

			update_option( '_rock_convert_name_field', sanitize_text_field( $enable_name_field ) );
			update_option( '_rock_convert_custom_field', sanitize_text_field( $enable_custom_field ) );
			update_option( '_rock_convert_custom_field_label', sanitize_text_field( $custom_field_label ) );
			update_option( '_rock_convert_enable_analytics', sanitize_text_field( $enable_analytics ) );
		}
	}

	/**
	 * Update advanced data
	 *
	 * @return void
	 */
	protected function update_advanced_tab() {
		if ( isset( $_POST['rock_convert_settings_nonce'] )
		&& wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_POST['rock_convert_settings_nonce'] ) ),
			'rock_convert_settings_nonce'
		)
		) {
			$g_site_key       = sanitize_text_field( Utils::getArrayValue( $_POST, 'g_site_key' ) );
			$g_secret_key     = sanitize_text_field( Utils::getArrayValue( $_POST, 'g_secret_key' ) );
			$mailchimp_token  = sanitize_key( Utils::getArrayValue( $_POST, 'mailchimp_token' ) );
			$rd_public_token  = sanitize_key( Utils::getArrayValue( $_POST, 'rd_station_public_token' ) );
			$hubspot_form_url = esc_url_raw( Utils::getArrayValue( $_POST, 'hubspot_form_url' ) );

			update_option( '_rock_convert_g_site_key', $g_site_key );
			update_option( '_rock_convert_g_secret_key', $g_secret_key );
			update_option( '_rock_convert_mailchimp_token', $mailchimp_token );
			if ( isset( $_POST['mailchimp_list'] ) ) {
				$mailchimp_list = Utils::getArrayValue( $_POST, 'mailchimp_list' );
				update_option( '_rock_convert_mailchimp_list', $mailchimp_list );
			}
			update_option( '_rock_convert_rd_public_token', $rd_public_token );
			update_option( '_rock_convert_hubspot_form_url', $hubspot_form_url );
		}
	}

	/**
	 * Update integrations data
	 *
	 * @return void
	 */
	protected function update_integrations_tab() {
		if ( isset( $_POST['rock_convert_settings_nonce'] )
		&& wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_POST['rock_convert_settings_nonce'] ) ),
			'rock_convert_settings_nonce'
		)
		) {
			$g_site_key   = sanitize_text_field( Utils::getArrayValue( $_POST, 'g_site_key' ) );
			$g_secret_key = sanitize_text_field( Utils::getArrayValue( $_POST, 'g_secret_key' ) );

			update_option(
				'_rock_convert_g_site_key',
				$g_site_key
			);

			update_option(
				'_rock_convert_g_secret_key',
				$g_secret_key
			);

			$mailchimp_token = Utils::getArrayValue( $_POST, 'mailchimp_token' );

			update_option(
				'_rock_convert_mailchimp_token',
				sanitize_text_field( $mailchimp_token )
			);

			if ( isset( $_POST['mailchimp_list'] ) ) {
				$mailchimp_list = Utils::getArrayValue( $_POST, 'mailchimp_list' );

				update_option(
					'_rock_convert_mailchimp_list',
					sanitize_text_field( $mailchimp_list )
				);
			}
		}
	}

	/**
	 * Export CVS function
	 *
	 * @return void
	 */
	public function export_csv_callback() {
		if ( isset( $_POST['rock_convert_csv_nonce'] )
		&& wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_POST['rock_convert_csv_nonce'] ) ),
			'rock_convert_csv_nonce'
		)
		) {
			try {
				new CSV( $this->subscriptions_table_name );
			} catch ( \Exception $e ) {
				$e->getMessage();
				Utils::logError( $e );
			}
		}
	}

	/**
	 * Display Screen of settings.
	 *
	 * @return void
	 */
	public function display() {
		$active_tab       = isset( $_GET['tab'] ) ? sanitize_title( wp_unslash( $_GET['tab'] ) ) : 'general'; // phpcs:ignore WordPress.Security.NonceVerification
		$success_saved    = isset( $_GET['success'] ) ? sanitize_title( wp_unslash( $_GET['success'] ) ) : null; // phpcs:ignore WordPress.Security.NonceVerification
		$integrations_tab = 'advanced';
		?>
		<div class="wrap">

			<h1 class="wp-heading-inline"><?php esc_html_e( 'Rock Convert' ); ?></h1>

			<h2 class="nav-tab-wrapper">
				<a href="<?php echo esc_url( $this->settings_tab_url( 'general' ) ); ?>"
					class="nav-tab
			<?php echo 'general' === $active_tab ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( 'Início', 'rock-convert' ); ?>
				</a>
				<a href="<?php echo esc_url( $this->settings_tab_url( $integrations_tab ) ); ?>"
					class="nav-tab
			<?php
				echo in_array( $active_tab, array( 'integrations', 'advanced' ), true )
				? 'nav-tab-active' : '';
			?>
					">
			<?php esc_html_e( 'Integrações', 'rock-convert' ); ?>
				</a>
				<a href="<?php echo esc_url( $this->settings_tab_url( 'leads' ) ); ?>"
					class="nav-tab
				<?php echo 'leads' === $active_tab ? 'nav-tab-active' : ''; ?>">
				<?php esc_html_e( 'Contatos', 'rock-convert' ); ?>
				</a>
				<a href="<?php echo esc_url( $this->settings_tab_url( 'popup' ) ); ?>"
					class="nav-tab
				<?php echo 'popup' === $active_tab ? 'nav-tab-active' : ''; ?>">
				<?php esc_html_e( 'Popup', 'rock-convert' ); ?>
					<span class='rc-new-label contacts'>
				<?php esc_html_e( 'Novo', 'rock-convert' ); ?>
					</span>
				</a>
			</h2>

				<?php if ( $success_saved ) { ?>
				<div class="notice notice-success is-dismissible">
					<p><strong><?php esc_html_e( 'Atualizações realizadas com sucesso!', 'rock-convert' ); ?></strong></p>
					<button type="button" class="notice-dismiss">
						<span class="screen-reader-text">
							<?php esc_html_e( 'Dismiss this notice.', 'rock-convert' ); ?>
						</span>
					</button>
				</div>
			<?php } ?>

			<div class="rock-convert-settings-wrap">
				<?php
				if ( 'general' === $active_tab ) {
					$this->general_tab();
				} elseif ( 'advanced' === $active_tab ) {
					$this->advanced_tab();
				} elseif ( 'integrations' === $active_tab ) {
					$this->integrations_tab();
				} elseif ( 'logs' === $active_tab ) {
					$this->logs_tab();
				} elseif ( 'leads' === $active_tab ) {
					$this->leads_tab();
				} else {
					$this->popup_tab();
				}
				?>
			</div>


		</div>
		<?php
	}

	/**
	 * Settings URL.
	 *
	 * @param string $tab parameter to define wich table is selected.
	 * @return string
	 */
	public function settings_tab_url( $tab ) {
		return esc_url( admin_url( 'edit.php?post_type=cta&page=rock-convert-settings&tab=' . $tab ) );
	}

	/**
	 * General screen
	 *
	 * @return void
	 */
	public function general_tab() {
		$settings_nonce       = wp_create_nonce( 'rock_convert_settings_nonce' );
		$analytics_enabled    = Admin::analytics_enabled();
		$name_field_enabled   = Admin::name_field_is_enabled();
		$custom_field_enabled = Admin::custom_field_is_enabled();
		$custom_field_label   = Admin::custom_field_label_value();
		$hide_referral        = Admin::hide_referral();
		?>

		<div id="" class="rock-adm">
			<div>
				<h2><?php esc_html_e( 'Comece a usar', 'rock-convert' ); ?></h2>
				<a class="button button-primary button-hero load-customize hide-if-no-customize"
					href="<?php echo esc_url( admin_url( 'post-new.php?post_type=cta' ) ); ?>">
			<?php esc_html_e( 'Adicionar um banner', 'rock-convert' ); ?>
				</a>
				<br>
				<br>
				<h2><?php esc_html_e( 'Precisa de ajuda?', 'rock-convert' ); ?></h2>
				<ul>
					<li>
						<a href="<?php echo esc_url( ROCK_CONVERT_HELP_CENTER_URL ); ?>"
							class="icon" target="_blank">
					<?php esc_html_e( 'Dúvidas comuns (FAQ)', 'rock-convert' ); ?>
						</a>
					</li>
					<li>
						<a href="<?php echo esc_url( ROCK_CONVERT_SUGGEST_FEATURE_URL ); ?>"
							target="_blank">
					<?php
						esc_html_e( 'Sugerir nova funcionalidade', 'rock-convert' );
					?>
						</a>
					</li>
					<li>
						<a href="<?php echo esc_url( ROCK_CONVERT_REPORT_ERROR_URL ); ?>"
							target="_blank">
						<?php esc_html_e( 'Relatar um problema', 'rock-convert' ); ?>
						</a>
					</li>
				</ul>
			</div>
			<div>
				<h2><?php esc_html_e( 'Próximos passos', 'rock-convert' ); ?></h2>
				<ul>
					<li>
						<a href="<?php echo esc_url( 'https://rockcontent.com/blog/rock-convert/' ); ?>" target="_blank">
						<?php esc_html_e( 'Tudo o que você precisa saber sobre o Rock Convert', 'rock-convert' ); ?>
						</a>
					</li>
					<li>
						<a href="<?php echo esc_url( 'https://rockcontent.com/blog/o-que-e-cta/' ); ?> target="_blank">
						<?php esc_html_e( 'O que é CTA: Tudo que você precisa saber', 'rock-convert' ); ?>
						</a>
					</li>
					<li>
						<a href="<?php echo esc_url( 'https://rockcontent.com/blog/parametros-utm-do-google-analytics/' ); ?>
							target="_blank">
						<?php esc_html_e( 'Como usar os parâmetros de UTM', 'rock-convert' ); ?>
						</a>
					</li>
				</ul>
					<?php $this->newsletter_subscribe_form(); ?>
			</div>
		</div>

		<h1 class="wp-heading-inline"><?php echo esc_html__( 'Recursos disponíveis', 'rock-convert' ); ?></h1>
		<br><br>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
			<input type="hidden" name="tab" value="<?php echo esc_attr( 'general' ); ?>"/>
			<input type="hidden" name="action" value="<?php echo esc_attr( 'rock_convert_settings_form' ); ?>">
			<input type="hidden" name="rock_convert_settings_nonce" value="<?php echo esc_attr( $settings_nonce ); ?>"/>

			<label for="rock_convert_enable_analytics" style="display: block; margin-bottom: 15px;">
				<input type="checkbox" name="rock_convert_enable_analytics"
					id="rock_convert_enable_analytics"
					value="1" <?php echo esc_attr( $analytics_enabled ) ? 'checked' : null; ?>/>
				<strong><?php echo esc_html__( 'Salvar visualizações e cliques', 'rock-convert' ); ?></strong>
				<span style="padding:5px 5px 10px; display: block">
					<?php
						esc_html_e(
							'Ative esta opção para coletar os dados de visualizações e clicks nos banners do Rock Convert.',
							'rock-convert'
						);
					?>
				</span>
			</label>
			<h1 class="wp-heading-inline"><?php echo esc_html__( 'Campos adicionais', 'rock-convert' ); ?></h1>
			<div style="display: block; margin-bottom:20px;">
				<p>
					<?php
					esc_html_e(
						'Agora, além do email, você também pode captar dados adicionais de seus leads.',
						'rock-convert'
					);
					?>
				</p>
			</div>
			<label for="rock_convert_enable_name_field" style="display: block">
				<input type="checkbox" name="rock_convert_enable_name_field"
					id="rock_convert_enable_name_field"
					value="1" <?php echo $name_field_enabled ? 'checked' : null; ?>>
				<strong>
						<?php esc_html_e( "Ativar o campo 'Nome' no formulário do Widget", 'rock-convert' ); ?>
				</strong>
				<div style="padding:5px 5px 25px;">
					<span>
						<?php
						esc_html_e(
							'Ative esta opção para adicionar um novo campo para capturar o Nome do usuário.',
							'rock-convert'
						);
						?>
					</span>
				</div>
			</label>

			<label for="rock_convert_enable_custom_field" style="display: block">
				<input type="checkbox" name="rock_convert_enable_custom_field"
					id="rock_convert_enable_custom_field"
					value="1" <?php echo esc_attr( $custom_field_enabled ) ? 'checked' : null; ?>>
				<strong>
					<?php esc_html_e( 'Ativar campo customizado', 'rock-convert' ); ?>
				</strong>
				<div style="padding:5px 5px 25px;">
					<span>
					<?php
					esc_html_e(
						'Ative esta opção para configurar um campo extra no formulário do widget, selecione uma label para o campo.',
						'rock-convert'
					);
					?>
					</span>
				</div>
			</label>
			<label id="rock-convert-label-container" class="d-none"
					for="rock_convert_custom_field_label" style="display: block;">
				<strong style="display: block">
					<?php esc_html__( 'Selecione a label do campo', 'rock-convert' ); ?>
				</strong>
				<input type="text" name="rock_convert_custom_field_label"
						id="rock_convert_custom_field_label"
						value="<?php echo esc_html( $custom_field_label ); ?>">
				<div style="padding:5px 5px 25px;">
					<span>
						<?php
						esc_html_e(
							'Selecione o título que será exibido acima do campo no formulário do widget.',
							'rock-convert'
						);
						?>
					</span>
				</div>
			</label>
			<br>
			<button type="submit" class="button button-large button-primary">
						<?php esc_html_e( 'Salvar configurações', 'rock-convert' ); ?>
			</button>
		</form>
				<?php
	}

	/**
	 * Newsletter Form
	 *
	 * @return void
	 */
	public function newsletter_subscribe_form() {
		//phpcs:disable
		?>
		<h2><?php esc_html_e( 'Atualizações', 'rock-convert' ); ?></h2>
		<p class="about-description">
		<?php esc_html_e( 'Cadastre seu e-mail abaixo para receber novidades do Rock Convert!', 'rock-convert' ); ?>
		</p>
		<!--[if lte IE 8]>
		<script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/v2-legacy.js"></script>
		<![endif]-->
		<script charset="utf-8" type="text/javascript"
				src="<?php echo esc_url( '//js.hsforms.net/forms/v2.js' ); ?>"></script>
		<script>
			hbspt.forms.create({
				portalId: "355484",
				formId: "b674c60c-f3e5-4f22-95f3-2204100e8a62",
				redirectUrl: "<?php echo esc_url_raw( admin_url( 'edit.php?post_type=cta&page=rock-convert-settings&success=newsletter' ) ); ?>",
				submitButtonClass: "button button-primary button-hero rock-convert-newsletter-form__btn",
				groupErrors: true
			});
		</script>
		<?php
		//phpcs:enable
	}

	/**
	 * Advanced Screen
	 *
	 * @return void
	 */
	public function advanced_tab() {
		$settings_nonce = wp_create_nonce( 'rock_convert_settings_nonce' );
		?>
		<h1 class="wp-heading-inline"><?php echo esc_html__( 'Ferramentas de automação', 'rock-convert' ); ?></h1>
		<p style="max-width: 580px">
		<?php
		esc_html_e(
			'Selecione abaixo uma ferramenta de automação e envie os leads gerados pelos formulários do Rock Convert.',
			'rock-convert'
		);
		?>
		</p>

		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
			<input type="hidden" name="tab" value="<?php echo esc_attr( 'advanced' ); ?>"/>
			<input type="hidden" name="action" value="<?php echo esc_attr( 'rock_convert_settings_form' ); ?>">
			<input type="hidden" name="rock_convert_settings_nonce" value="<?php echo esc_attr( $settings_nonce ); ?>"/>
			<div class="rock-convert-how-it-works">
		<?php $this->google_form(); ?>
				<hr>
				<br>
		<?php $this->mailchimp_form(); ?>
				<hr>
				<br>
		<?php $this->rd_station_form(); ?>
				<hr>
				<br>
		<?php $this->hubspot_form(); ?>
			</div>
			<button type="submit" class="button button-large button-primary">
		<?php esc_html_e( 'Salvar integrações', 'rock-convert' ); ?>
			</button>
		</form>
		<?php
	}

	/**
	 * Google Form
	 *
	 * @return void
	 */
	public function google_form() {
		$g_site_key   = get_option( '_rock_convert_g_site_key' );
		$g_secret_key = get_option( '_rock_convert_g_secret_key' );
		?>

		<h3 style="margin-bottom: 0;"><?php esc_html( 'Google' ); ?></h3>

		<table class="form-table">
			<tbody>
			<tr>
				<th>
					<label for="g_site_key">
				<?php esc_html_e( 'Chave do site', 'rock-convert' ); ?>
					</label>
				</th>
				<td>
					<input name="g_site_key"
						id="g_site_key"
						type="text"
						placeholder="Ex: 54567958d8f7k8789jfd987d8ks"
						class="regular-text code"
						value="<?php echo esc_attr( $g_site_key ); ?>">
					<br>
				</td>
			</tr>
			<tr>
				<th>
					<label for="g_secret_key">
				<?php esc_html_e( 'Chave secreta', 'rock-convert' ); ?>
					</label>
				</th>
				<td>
					<input name="g_secret_key"
						id="g_secret_key"
						type="text"
						placeholder="Ex: 54567958d8f7k8789jfd987d8ks"
						class="regular-text code"
						value="<?php echo esc_attr( $g_secret_key ); ?>">
					<br>
					<small><?php esc_html_e( 'Precisa de ajuda para criar a chave?', 'rock-convert' ); ?>
						<a href="<?php echo esc_url( 'https://www.google.com/recaptcha/admin/create' ); ?>"
							target="_blank">
					<?php
						esc_html_e(
							'Acesse o painel do Google e crie um reCaptcha v2',
							'rock-convert'
						);
					?>
						</a>
					</small>
				</td>
			</tr>
			</tbody>
		</table>
				<?php
	}

	/**
	 * PopUp Form
	 *
	 * @return void
	 */
	public function popup_form() {
		$popup_title              = get_option( '_rock_convert_popup_title' );
		$popup_descricao          = get_option( '_rock_convert_popup_descricao' );
		$popup_color              = get_option( '_rock_convert_popup_color' );
		$popup_activate           = get_option( '_rock_convert_popup_activate' );
		$popup_image_activate     = get_option( '_rock_convert_popup_image_activate' );
		$popup_image              = get_option( '_rock_convert_popup_image' );
		$popup_button_color       = get_option( '_rock_convert_popup_button_color' );
		$popup_button_text_color  = get_option( '_rock_convert_popup_button_text_color' );
		$popup_button_close_color = get_option( '_rock_convert_popup_button_close_color' );
		$popup_title_color        = get_option( '_rock_convert_popup_title_color' );
		$popup_description_color  = get_option( '_rock_convert_popup_description_color' );
		?>

		<table class="form-table">
			<tbody>
			<tr>
				<th>
					<label for="rock_convert_popup_title"><?php esc_html_e( 'Título', 'rock-convert' ); ?></label>
				</th>
				<td>
				<input name="rock_convert_popup_title"
						id="rock_convert_popup_title"
						type="text"
						placeholder="Popup title"
						class="regular-text code"
						value="<?php echo esc_attr( $popup_title ); ?>">
					<br>
				</td>
			</tr>
			<tr>
				<th>
					<label for="rock_convert_popup_descricao"><?php esc_html_e( 'Descrição', 'rock-convert' ); ?></label>
				</th>
				<td>
					<textarea
						name="rock_convert_popup_descricao"
						id="rock_convert_popup_descricao"
						rows="4"
						cols="50"
						maxlength="280"
						value="<?php esc_textarea( $popup_descricao ); ?>"
					><?php echo esc_textarea( $popup_descricao ); ?></textarea>
				</td>
			</tr>
			<tr>
				<th>
					<label for="rock_convert_popup_color"><?php esc_html_e( 'Cor de fundo', 'rock-convert' ); ?></label>
				</th>
				<td>
				<p>
					<input type="text" name="rock_convert_popup_color" class="color-picker-popup"
						id="rock_convert_popup_color" value="<?php echo esc_attr( $popup_color ); ?>"
						data-default-color="#333333"/>
				</p>
				</td>
			</tr>
			<tr>
				<th>
					<label for="rock_convert_popup_button_close_color">
				<?php esc_html_e( 'Cor do botão fechar', 'rock-convert' ); ?>
					</label>
				</th>
				<td>
				<p>
					<input type="text" name="rock_convert_popup_button_close_color" class="color-picker-popup"
						id="rock_convert_popup_button_close_color" value="<?php echo esc_attr( $popup_button_close_color ); ?>"
						data-default-color="black"/>
				</p>
				</td>
			</tr>
			<tr>
				<th>
					<label for="rock_convert_popup_button_color">
						<?php esc_html_e( 'Cor do botão', 'rock-convert' ); ?>
					</label>
				</th>
				<td>
				<p>
					<input type="text" name="rock_convert_popup_button_color" class="color-picker-popup"
						id="rock_convert_popup_button_color" value="<?php echo esc_attr( $popup_button_color ); ?>"
						data-default-color="#a46497"/>
				</p>
					<br>
				</td>
			</tr>
			<tr>
				<th>
					<label for="rock_convert_popup_button_text_color">
						<?php esc_html_e( 'Cor do texto do botão', 'rock-convert' ); ?>
					</label>
				</th>
				<td>
				<p>
					<input type="text" name="rock_convert_popup_button_text_color" class="color-picker-popup"
						id="rock_convert_popup_button_text_color" value="<?php echo esc_attr( $popup_button_text_color ); ?>"
						data-default-color="#a46497"/>
				</p>
					<br>
				</td>
			</tr>
			<tr>
				<th>
					<label for="rock_convert_popup_title_color">
						<?php esc_html_e( 'Cor do titulo', 'rock-convert' ); ?>
					</label>
				</th>
				<td>
				<p>
					<input type="text" name="rock_convert_popup_title_color" class="color-picker-popup"
						id="rock_convert_popup_title_color" value="<?php echo esc_attr( $popup_title_color ); ?>"
						data-default-color="white"/>
				</p>
					<br>
				</td>
			</tr>
			<tr>
				<th>
					<label for="rock_convert_popup_description_color">
						<?php esc_html_e( 'Cor da descrição', 'rock-convert' ); ?>
					</label>
				</th>
				<td>
				<p>
					<input type="text" name="rock_convert_popup_description_color" class="color-picker-popup"
						id="rock_convert_popup_description_color" value="<?php echo esc_attr( $popup_description_color ); ?>"
						data-default-color="white"/>
				</p>
					<br>
				</td>
			</tr>
			<tr>
				<th>
					<label for="rock_convert_popup_activate">
				<?php esc_html_e( 'Ativar funcionalidade', 'rock-convert' ); ?>
					</label>
				</th>
				<td>
					<input
						type="checkbox"
						id="rock_convert_popup_activate"
						name="rock_convert_popup_activate"
						value="yes"
				<?php echo 'yes' === esc_attr( $popup_activate ) ? 'checked' : ''; ?>
					>
					<br>
				</td>
			</tr>
			<tr>
				<th>
					<label for="rock_convert_popup_image_activate">
				<?php esc_html_e( 'Habilitar imagem', 'rock-convert' ); ?>
					</label>
				</th>
				<td>
					<input
						type="checkbox"
						id="rock_convert_popup_image_activate"
						name="rock_convert_popup_image_activate"
						value="yes"
				<?php echo 'yes' === esc_attr( $popup_image_activate ) ? 'checked' : ''; ?>
					>
					<br>
				</td>
			</tr>
			<tr>
				<th>
					<label for="rock_convert_popup_image">
				<?php esc_html_e( 'Selecione a imagem do banner', 'rock-convert' ); ?>
					</label>
				</th>
				<td>
			<?php
			$image_url = wp_get_attachment_image_url(
				$popup_image,
				'medium',
				false,
				array( 'id' => 'rock_convert_popup_image' )
			);
			?>
					<div class="convert-popup" style="z-index:20000;display:none;">
						<div class="convert-popup-box" style="background-color:#494949;">
							<img src="<?php echo esc_url( $image_url ); ?>"
								alt="Convert Image" id="rock_convert_popup_image_preview"
							class="rock_convert_popup_image">
							<div class="convert-popup-content">
								<h2 class="popup-preview-title" id="popup-preview-title"
									style="color:white;font-size:28px; margin-left:60px;">
							<?php esc_html_e( 'Título', 'rock-convert' ); ?>
								</h2>
								<p class="popup-preview-descricao" id="popup-preview-descricao">
							<?php esc_html_e( 'Descrição', 'rock-convert' ); ?>
								</p>
								<a class="convert-popup-close" style="color:black;" id="btnClose">X</a>
								<div class="popup-input-group-preview">
									<input
										type="email"
										class="rock-convert-subscribe-form-email convert-popup-email"
										placeholder="E-mail"
									>
									<input
										type="button"
										class="rock-convert-subscribe-form-btn convert-popup-btn"
										value="Enviar"
										style="background-color:#24e551;color:white;padding:7px 0;margin-top:0;"
									>
								</div>
							</div>
						</div>
					</div>
					<div class="convert-popup-ni" style="z-index:20000;display:none;">
						<div class="convert-popup-box" style="background-color:#494949;width:400px;padding-right: 10px !important;
						padding-left: 10px !important; padding-bottom:10px !important; align-items: baseline !important;">
							<div class="convert-popup-content" style="padding: 18px 10px; align-items: baseline;">
								<h2 class="popup-preview-title" id="popup-preview-title-ni"
									style="color:white;font-size: 28px;margin:0;">
									<?php esc_html_e( 'Título', 'rock-convert' ); ?>
								</h2>
								<p class="popup-preview-descricao" id="popup-preview-descricao-ni"
									style="word-spacing:-1px;text-align:justify;width:370px;color:white;
										overflow-wrap:break-word;margin-bottom:30px;margin:0;">
									<?php esc_html_e( 'Descrição', 'rock-convert' ); ?>
								</p>
								<a class="convert-popup-close" style="color:black;" id="btnClose">X</a>
								<div class="popup-input-group-preview">
									<input
										type="email"
										class="rock-convert-subscribe-form-email convert-popup-email"
										placeholder="E-mail"
										style="margin:0;width: 100%;"
									>
									<input
										type="button"
										class="rock-convert-subscribe-form-btn convert-popup-btn"
										value="Enviar"
										style="background-color:#24e551;color:white;padding:7px 0;margin:0;width: 100%;"
									>
								</div>
							</div>
						</div>
					</div>
					<div class="popup-content">
						<input class="popup-content-select-image-hide" style="margin-top:8px;" type='button' class="button-primary"
						value="<?php esc_html_e( 'Selecione uma imagem', 'rock-convert' ); ?>" id="convert_popup_media"/>
						<span style="margin-top:10px;"><?php esc_html_e( 'Tamanho recomendado: 300x400px', 'rock-convert' ); ?></span>
					</div>
					<div class="popup-content-preview">
						<input type="hidden" name="rock_convert_popup_image" id="rock_convert_popup_image"
						value="<?php echo esc_attr( $popup_image ); ?>" class="regular-text" />
						<input class="popup-content-select-image" style="margin-top:8px;" type='button'
							value="<?php esc_attr_e( 'Selecione uma imagem', 'rock-convert' ); ?>" id="convert_popup_media"/>
						<span style="margin-top:10px;">
							<?php esc_html_e( 'Tamanho recomendado: 300x400px', 'rock-convert' ); ?>
						</span>
					</div>
					<br>
				</td>
			</tr>
			</tbody>
		</table>
			<?php
	}

	/**
	 * Mailchimp Form
	 *
	 * @return void
	 */
	public function mailchimp_form() {
		$mailchimp_token = get_option( '_rock_convert_mailchimp_token' );
		$mailchimp_list  = get_option( '_rock_convert_mailchimp_list' );

		$lists = $this->get_mailchimp_lists( $mailchimp_token );

		?>

		<h3 style="margin-bottom: 0;">MailChimp</h3>

		<table class="form-table">
			<tbody>
			<tr>
				<th>
					<label for="mailchimp_token">
				<?php esc_html_e( 'Chave de API do MailChimp', 'rock-convert' ); ?>
					</label>
				</th>
				<td>
					<input name="mailchimp_token"
						id="mailchimp_token"
						type="text"
						placeholder="Ex: abc123abc123abc123abc123abc123-us"
						class="regular-text code"
						value="<?php echo esc_attr( $mailchimp_token ); ?>">
					<br>
					<small><?php esc_html_e( 'Precisa de ajuda?', 'rock-convert' ); ?>
					<a href="<?php echo esc_url( 'https://mailchimp.com/help/about-api-keys/' ); ?>" target="_blank">
				<?php
					esc_html_e(
						'Veja como criar uma chave de API para o MailChimp',
						'rock-convert'
					);
				?>
					</a>
					</small>
				</td>
			</tr>
				<?php if ( ! empty( $mailchimp_token ) && empty( $lists ) ) { ?>
				<tr>
					<th></th>
					<td>
						<span style="color: orangered;font-weight: bold">
							<?php
								esc_html_e(
									'Atenção: nenhuma lista encontrada.',
									'rock-convert'
								);
							?>
						</span>
						<br/>
						<small>
							<?php
							$url  = 'https://rockcontent.com/blog/mailchimp/#listas';
							$link = sprintf(
								wp_kses(
									/* translators: %s: link term */
									__(
										'Confira se a chave de API está correta e se esta conta já possui uma lista criada.
										 Caso ainda não tenha nenhuma lista, saiba como criar <a href="%s">clicando aqui</a>.',
										'rock-convert'
									),
									array( 'a' => array( 'href' => array() ) )
								),
								esc_url( $url )
							);
							echo esc_url( $link );
							?>
						</small>
					</td>
				</tr>
			<?php } ?>
					<?php if ( ! empty( $mailchimp_token ) && ! empty( $lists ) ) { ?>
				<tr>
					<th>
						<label for="mailchimp_list">
							<?php esc_html_e( 'Selecione uma lista', 'rock-convert' ); ?>
						</label>
					</th>
					<td>
						<select name="mailchimp_list" id="mailchimp_list" class="regular-text code">
							<option>-- <?php esc_html_e( 'Selecione uma lista', 'rock-convert' ); ?> --</option>
							<?php foreach ( $lists as $list ) { ?>
								<option value="<?php echo esc_attr( $list['id'] ); ?>"
								<?php echo $mailchimp_list === $list['id'] ? 'selected' : null; ?>>
									<?php echo esc_attr( $list['name'] ); ?>
								</option>
							<?php } ?>
						</select>
						<br>
						<small>
						<?php
						esc_html_e(
							'Escolha uma lista para enviar os contatos coletados pelo Rock Convert.',
							'rock-convert'
						);
						?>
								</small>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
				<?php
	}

	/**
	 * Get Mailchimp lists.
	 *
	 * @param string $token This is the mailchimp token to make integration between platforms.
	 *
	 * @return array|bool
	 */
	public function get_mailchimp_lists( $token ) {
		if ( empty( $token ) ) {
			return array();
		}

		try {
			$mail_chimp = new MailChimp( $token );

			return $mail_chimp->getLists();
		} catch ( \Exception $e ) {
			Utils::logError( $e );
			return array();
		}
	}

	/**
	 * RDStation form.
	 *
	 * @return void
	 */
	public function rd_station_form() {
		$rd_public_token = get_option( '_rock_convert_rd_public_token' );
		?>
		<h3 style="margin-bottom: 0;"><?php echo esc_html( 'RD Station' ); ?></h3>
		<table class="form-table" >
			<tbody>
			<tr>
				<th>
					<label for="rd_station_public_token">
				<?php esc_html_e( 'Token público da RD Station', 'rock-convert' ); ?>
					</label>
				</th>
				<td>
					<input name="rd_station_public_token"
						id="rd_station_public_token"
						type="text" placeholder="Ex: e580854190764dbdaf19ac942334b0fc"
						class="regular-text code"
						value="<?php echo esc_attr( $rd_public_token ); ?>">
					<br>
			<?php if ( ! empty( $rd_public_token ) ) { ?>
						<small>
							<strong>
								<?php esc_html_e( 'Identificador único de leads:', 'rock-convert' ); ?>
							</strong> rock-convert-<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
						</small>
						<br><br>
					<?php } ?>
					<small><?php esc_html_e( 'Para encontrar o token público da RD Station acesse:', 'rock-convert' ); ?>
						<a href="<?php echo esc_url( 'https://app.rdstation.com.br/integracoes/tokens' ); ?>" target="_blank">
					<?php echo esc_url( 'https://app.rdstation.com.br/integracoes/tokens' ); ?>
						</a>
					</small>
				</td>
			</tr>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Hubspot Form.
	 *
	 * @return void
	 */
	public function hubspot_form() {
		$hubspot_form_url = get_option( '_rock_convert_hubspot_form_url' );
		?>
		<h3 style="margin-bottom: 0;"><?php echo esc_html( 'HubSpot' ); ?></h3>
		<table class="form-table">
			<tbody>
			<tr>
				<th>
					<label for="hubspot_form_url">
				<?php esc_html_e( 'URL do form da HubSpot', 'rock-convert' ); ?>
					</label>
				</th>
				<td>
					<input name="hubspot_form_url"
						id="hubspot_form_url"
						type="text"
						placeholder="Ex: https://forms.hubspot.com/uploads/form/v2/:portal_id/:form_guid"
						class="regular-text code"
						value="<?php echo esc_url( $hubspot_form_url ); ?>">
					<br>
					<small><?php esc_html_e( 'Precisa de ajuda?', 'rock-convert' ); ?>
						<a href="<?php esc_url( 'https://developers.hubspot.com/docs/methods/forms/submit_form' ); ?>"
							target="_blank">
					<?php
						esc_html_e(
							'Acesse a central de ajuda da HubSpot',
							'rock-convert'
						);
					?>
						</a>
					</small>
					<br>
					<br>
					<small>
						<strong>
						<?php esc_html_e( 'Formato da URL:', 'rock-convert' ); ?>
						</strong>
						https://forms.hubspot.com/uploads/form/v2/<strong>PORTAL_ID</strong>/<strong>FORM_GUID</strong>
					</small>
					<br><br>
					<small>
						<?php
						echo sprintf(
							/* translators: %1$s: Portal ID, %2$s: Form Guid. */
							esc_html__(
								'Onde: %1$s é o id da conta e  é o ID do %2$s formulário.',
								'rock-convert'
							),
							'<strong>PORTAL_ID</strong>',
							'<strong>FORM_GUID</strong>'
						);
						?>
					</small>
				</td>
			</tr>
			</tbody>
		</table>
				<?php
	}

	/**
	 * Integrations Screen
	 *
	 * @return void
	 */
	public function integrations_tab() {
		$settings_nonce = wp_create_nonce( 'rock_convert_settings_nonce' );
		?>
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Ferramentas de automação', 'rock-convert' ); ?></h1>
		<p style="max-width: 580px">
		<?php
		esc_html_e(
			'Selecione abaixo uma ferramenta de automação e envie os leads gerados pelos formulários do Rock Convert.',
			'rock-convert'
		);
		?>
		</p>
		<br>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
			<input type="hidden" name="tab" value="<?php echo esc_attr( 'integrations' ); ?>"/>
			<input type="hidden" name="action" value="<?php echo esc_attr( 'rock_convert_settings_form' ); ?>">
			<input type="hidden" name="rock_convert_settings_nonce" value="<?php echo esc_attr( $settings_nonce ); ?>"/>
			<div class="rock-convert-how-it-works">
			<?php $this->google_form(); ?>
			<br>
			<?php $this->mailchimp_form(); ?>
			<br>
			<?php $this->rd_station_form(); ?>
			<br>
			<?php $this->hubspot_form(); ?>
			<br>
			</div>
			<button type="submit" class="button button-large button-primary">
				<?php esc_html_e( 'Salvar integrações', 'rock-convert' ); ?>
			</button>
		</form>
		<?php
	}

	/**
	 * Logs Screen
	 *
	 * @return void
	 */
	public function logs_tab() {
		$file    = plugin_dir_path( __FILE__ ) . 'logs/debug.log';
		$content = Utils::read_backward_line( $file, 300 );
		?>
		<h2><?php echo esc_html( 'Log' ); ?></h2>
		<div style="height: 100%; overflow-x: scroll">
			<pre><?php echo esc_attr( $content ); ?></pre>
		</div>
		<?php
	}

	/**
	 * PopUp Screen
	 *
	 * @return void
	 */
	public function popup_tab() {
		$settings_nonce = wp_create_nonce( 'rock_convert_settings_nonce' );
		?>
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Configurações do popup', 'rock-convert' ); ?></h1>
		<p style="max-width: 580px">
		<?php
		esc_html_e(
			'Selecione abaixo as configurações desejadas para o seu popup.',
			'rock-convert'
		);
		?>
		</p>

		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
		<input type="hidden" name="tab" value="<?php echo esc_attr( 'popup' ); ?>"/>
		<input type="hidden" name="action" value="<?php echo esc_attr( 'rock_convert_settings_form' ); ?>">
		<input type="hidden"  name="rock_convert_settings_nonce" value="<?php echo esc_attr( $settings_nonce ); ?>"/>
		<div class="rock-convert-how-it-works">
		<?php $this->popup_form(); ?>
			<br>
		</div>
		<button type="submit" class="button button-large button-primary">
		<?php
		echo esc_html__(
			'Salvar configurações',
			'rock-convert'
		);
		?>
		</button>
		</form>
			<?php
	}

	/**
	 * Leads Screen.
	 *
	 * @return void
	 */
	public function leads_tab() {
		?>
		<div class="rock-leads-viewer-container">
			<div class="rock-leads-content leads-table">
		<?php self::leads_viewer(); ?>
			</div>
			<div class="rock-leads-content leads-download">
				<h1 class="wp-heading-inline"><?php esc_html_e( 'Exportar', 'rock-convert' ); ?></h1>
				<p>
			<?php
			echo sprintf(
				/* translators: %s: Document formart strong. */
				esc_html__(
					'Para fazer o download dos contatos capturados pelo formulário de download no formato %s, clique abaixo.',
					'rock-convert'
				),
				'<strong>CSV</strong>'
			);
			?>
				</p>
				<p>
					<strong><?php echo esc_attr( $this->get_leads_count() ); ?></strong>
				<?php esc_html_e( 'contatos salvos.', 'rock-convert' ); ?>
				</p>
				<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" target="_blank">
					<input type="hidden" name="action" value="rock_convert_export_csv">
					<?php wp_nonce_field( 'rock_convert_csv_nonce', 'rock_convert_csv_nonce' ); ?>
					<button type="submit" class="button button-primary button-hero">
						<?php esc_html_e( 'Exportar no formato CSV', 'rock-convert' ); ?>
					</button>
				</form>
			</div>
		</div>
			<?php
	}

	/**
	 * Helper to return a anchor tag with url from post
	 *
	 * @param integer $post_id Post ID.
	 * @return string
	 */
	private function get_url_origin( $post_id = 0 ) {
		$link = esc_url( home_url() );

		if ( $post_id ) {
			$link = get_permalink( $post_id );
		}

		return printf(
			'<a href="%s" title="%s" target="_blank">%s</a>',
			esc_url( $link ),
			esc_attr( $post_id ) ? esc_html( get_the_title( $post_id ) ) : esc_html__( 'Página Inicial', 'rock-convert' ),
			esc_attr( $post_id ) ? esc_html( get_the_title( $post_id ) ) : esc_html__( 'Página Inicial', 'rock-convert' )
		);

	}

	/**
	 * Render viewers list.
	 *
	 * @var array $_GET Get data from URL.
	 * @return void
	 */
	private function leads_viewer() {
		global $wpdb;
		$table     = $wpdb->prefix . $this->subscriptions_table_name;
		$rpp       = 10;
		$page      = isset( $_GET['cpage'] ) ? (int) sanitize_text_field( wp_unslash( $_GET['cpage'] ) ) : 1; // phpcs:ignore WordPress.Security.NonceVerification
		$offset    = ( $page * $rpp ) - $rpp;
		$get_leads = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM `%1s` ORDER BY created_at DESC LIMIT %d, %d', $table, $offset, $rpp ) );// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder
		$paginate  = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM `%1s` as paginate', $table ) );// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder
		$ajax_url  = admin_url( 'admin-ajax.php' );

		?>
		<table class='widefat' data-ajaxurl='<?php echo esc_url( $ajax_url ); ?>'>
			<thead>
				<tr>
			<?php if ( Admin::name_field_is_enabled() ) { ?>
						<th scope='col'> <?php esc_html_e( 'Nome', 'rock-convert' ); ?></th>
					<?php } ?>
						<th scope='col'> <?php esc_html_e( 'Email', 'rock-convert' ); ?></th>
			<?php if ( Admin::custom_field_is_enabled() ) { ?>
						<th scope='col'><?php echo esc_html( Admin::custom_field_label_value() ); ?></th>
					<?php } ?>
					<th scope='col' class='url'><?php esc_html_e( 'Origem', 'rock-convert' ); ?></th>
					<th scope='col' class='date'><?php esc_html_e( 'Data', 'rock-convert' ); ?></th>
					<th scope='col'></th>
				</tr>
			</thead>
			<tbody>
		<?php
		if ( $get_leads ) {
			foreach ( $get_leads as $lead ) {
				$date  = gmdate( __( 'd-m-Y à\s H:i:s', 'rock-convert' ), strtotime( $lead->created_at ) );
				$nonce = wp_create_nonce( "delete-entry-nonce-{$lead->id}" );
				?>
				<tr class='alternate' id='entry-<?php echo esc_attr( $lead->id ); ?>'>
				<?php if ( Admin::name_field_is_enabled() ) { ?>
					<td><?php echo esc_html( $lead->user_name ); ?></td>
				<?php } ?>
				<td><?php echo esc_html( $lead->email ); ?></td>
				<?php if ( Admin::custom_field_is_enabled() ) { ?>
					<td><?php echo esc_html( $lead->custom_field ); ?></td>
				<?php } ?>
				<td> <?php $this->get_url_origin( $lead->post_id ); ?></td>
				<td><?php echo esc_html( $date ); ?></td>
				<?php $delete = esc_html__( 'Excluir', 'rock-convert' ); ?>
				<td class='rock-lead-delete-action'>
					<a style='padding: 5px; border-radius: 3px;'
						href='jasvascript:void(0);'
						data-nonce='<?php echo esc_attr( $nonce ); ?>'
						data-entry='<?php echo esc_attr( $lead->id ); ?>'
						data-email='<?php echo esc_html( $lead->email ); ?>'
						title='<?php echo esc_html( $delete ); ?>'>
							<?php echo esc_html( $delete ); ?>
					</a>
				</td>
			</tr>
				<?php
			}
		} else {
			?>
			<tr>
				<td class='align-center' colspan='5'>
			<?php esc_html_e( 'Nenhum contato cadastrado ainda', 'rock-convert' ); ?>
				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
		</table>
		<div class='paginate-table'>
		<?php
		paginate_links(
			array(
				'base'      => add_query_arg( 'cpage', '%#%' ),
				'format'    => '',
				'prev_text' => __( '&laquo;' ),
				'next_text' => __( '&raquo;' ),
				'total'     => ceil( $paginate / $rpp ),
				'current'   => $page,
			)
		)
		?>
		</div>
			<?php
	}

	/**
	 * Confirm delete lead function.
	 *
	 * @return void
	 */
	public static function confirm_delete_lead() {
		if ( ! current_user_can( 'administrator' ) ) {
			exit;
		}
		if ( ! isset( $_POST['entry'] ) ) {
			exit;
		}

		$post_entry = sanitize_text_field( wp_unslash( $_POST['entry'] ) );
		$post_email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : null;

		check_ajax_referer( 'delete-entry-nonce-' . $post_entry, 'security' );
		?>
		<div class='rock-confirm-delete-lead'>
			<h3><?php esc_html_e( 'Confirmar Exclusão?', 'rock-convert' ); ?></h3>
			<strong><?php echo esc_attr( $post_email ); ?></strong>
			<p>
			<?php esc_html_e( 'Esta ação não poderá ser desfeita', 'rock-convert' ); ?>
			</p>
			<div class="delete-actions">
				<button class="confirm-action button-primary" data-entry="<?php echo esc_attr( $post_entry ); ?>">
				<?php esc_html_e( 'Confirmar', 'rock-convert' ); ?>
				</button>
				<button class="cancel-action button-secondary"><?php esc_html_e( 'Cancelar', 'rock-convert' ); ?></button>
			</div>
		</div><span class="overlay"></span>
		<?php
		exit;
	}

		/**
		 * Delete the lead properly.
		 *
		 * @return void
		 */
	public static function delete_lead() {
		if ( ! current_user_can( 'administrator' ) ) {
			exit;
		}
		if ( ! isset( $_POST['entry'] ) ) {
			exit;
		}
		$post_entry = sanitize_text_field( wp_unslash( $_POST['entry'] ) );

		check_ajax_referer( 'delete-entry-nonce-' . $post_entry, 'security' );

		global $wpdb;
		$instance = new self();
		$table    = $wpdb->prefix . $instance->subscriptions_table_name;
		$wpdb->delete( $table, array( 'id' => $post_entry ) );// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		exit;
	}

		/**
		 * Get number of subscribers saved in $this->subscriptions_table_name table
		 *
		 * @return int
		 */
	public function get_leads_count() {
		global $wpdb;
		$table   = $wpdb->prefix . $this->subscriptions_table_name;
		$results = $wpdb->get_results( $wpdb->prepare( 'SELECT COUNT(*) as count FROM `%1s`', $table ) );// phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder

		if ( count( $results ) ) {
			return $results[0]->count;
		} else {
			return 0;
		}
	}

	/**
	 * Add plugin action links.
	 *
	 * Add a link to the settings page on the plugins.php page.
	 *
	 * @since 2.0.0
	 *
	 * @param  array $links List of existing plugin action links.
	 *
	 * @return array         List of modified plugin action links.
	 */
	public function action_links( $links ) {
		$integrations_tab = 'advanced';

		$links = array_merge(
			array(
				'<a href="' . $this->settings_tab_url( 'general' ) . '">'
				. esc_html__( 'Configurações', 'rock-convert' ) . '</a>',
				'<a href="' . esc_url_raw( $this->settings_tab_url( $integrations_tab ) ) . '">'
				. esc_html__( 'Integrações', 'rock-convert' ) . '</a>',
			),
			$links
		);

		return $links;
	}

}
/** Confirm delete lead action */
add_action( 'wp_ajax_confirm_delete_lead', array( 'Rock_Convert\Inc\Admin\Page_Settings', 'confirm_delete_lead' ) );
add_action( 'wp_ajax_nopriv_confirm_delete_lead', array( 'Rock_Convert\Inc\Admin\Page_Settings', 'confirm_delete_lead' ) );

/** Delete lead action */
add_action( 'wp_ajax_delete_lead', array( 'Rock_Convert\Inc\Admin\Page_Settings', 'delete_lead' ) );
add_action( 'wp_ajax_nopriv_delete_lead', array( 'Rock_Convert\Inc\Admin\Page_Settings', 'delete_lead' ) );
