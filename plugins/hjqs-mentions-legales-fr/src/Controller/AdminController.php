<?php

namespace Controller;

use Entity\Page;
use Form\LegalNoticeForm;
use Form\PrivacyPolicyForm;
use Form\TermsOfSalesForm;

/**
 * @desc Permet de créer le panel d'administration pour le plugin
 */
class AdminController extends BaseController {

	private array $forms = [];

	public function __construct() {
		// Ajoute les pages à l'administration de WordPress
		add_action( 'admin_menu', [ $this, 'add_settings_pages' ] );
		add_action( 'admin_init', [ $this, 'settings_init' ] );

		// Ajoute le bouton Settings dans la page des extensions
		add_filter( "plugin_action_links_$this->plugin_name/index.php", [ $this, 'plugin_add_settings_link' ] );

		add_action( "wp_ajax_hjqs_ln_clear", [ $this, 'clear_settings' ] );
		add_action( "wp_ajax_hjqs_ln_preview", [ $this, 'preview_shortcode' ] );
	}

	public function preview_shortcode(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( "Vous n’avez pas l’autorisation d’effectuer cette action.", 403 );

			return;
		}

		if ( ! isset( $_REQUEST['form'] ) ) {
			wp_send_json_error( "Le formulaire est manquant", 404 );

			return;
		}

		$form = match ( $_REQUEST['form'] ) {
			"hjqs_legal_notice" => new LegalNoticeForm(),
			'hjqs_privacy_policy' => new PrivacyPolicyForm(),
			'hjqs_terms_of_sales' => new TermsOfSalesForm(),
			default => null,
		};

		if(!$form){
			wp_send_json_error( "Le formulaire est manquant", 404 );
			return;
		}


		wp_send_json_success( [
			'preview' => $form->prepare_render(),
			'form'    => $_REQUEST['form']
		], 200 );

	}

	/**
	 * @desc Action AJAX permettant de supprimer les options du formulaire
	 * $_REQUEST[form] est nécessaire pour supprimer uniquement les options de ce formulaire
	 * @return void
	 */
	public function clear_settings(): void {
		if (
			! isset( $_REQUEST['nonce'] ) or
			! wp_verify_nonce( $_REQUEST['nonce'], 'hjqs_ln_clear' ) or
			! current_user_can( 'manage_options' )
		) {
			wp_send_json_error( "Vous n’avez pas l’autorisation d’effectuer cette action.", 403 );
		}

		if ( ! isset( $_REQUEST['form'] ) ) {
			wp_send_json_error( "Le formulaire est manquant", 404 );
		}

		$has_been_deleted = delete_option( "hjqs_" . $_REQUEST['form'] );

		$form = match ( $_REQUEST['form'] ) {
			"hjqs_legal_notice" => new LegalNoticeForm(),
			'hjqs_privacy_policy' => new PrivacyPolicyForm(),
			'hjqs_terms_of_sales' => new TermsOfSalesForm(),
			default => null,
		};

		$fields         = $form->get_form()->get_fields();
		$default_values = [];
		foreach ( $fields as $field ) {
			$default_values[ $field->get_option_key() ] = $field->get_value();
		}

		wp_send_json_success( [
			'has_been_deleted' => $has_been_deleted,
			'form'             => $default_values
		], 200 );
	}

	/**
	 * @desc Permet d'ajouter les options du plugin à la base de données WordPress
	 * @return void
	 */
	public function settings_init(): void {
		foreach ( $this->forms as $form ) {
			register_setting( $form->get_slug(), 'hjqs_' . $form->get_slug(), function ( $input ) {
				return $input;
			} );
			$section_slug = $form->get_slug() . "_section";
			add_settings_section(
				$section_slug,
				$form->get_title(),
				function () use ( $form ) {
					echo $this->render( plugin_dir_path( dirname( __FILE__, 2 ) ) . 'templates/admin/components/after_section_settings.php', [
						'form' => $form
					] );
				},
				$form->get_slug(),
			);
			foreach ( $form->get_fields() as $field ) {
				add_settings_field(
					$field->get_option_key(),
					$field->get_label(),
					function () use ( $field, $form ) {
						$this->render_field( $field );
					},
					$form->get_slug(),
					$section_slug,
					[
						'label_for' => $field->get_option_key()
					]
				);
			}

		}
	}

	/**
	 * @desc Ajoute les pages d'administrations et retourne les formulaires pour chaque page
	 * @return Page[]
	 */
	public function prepare_admin_pages(): array {
		$legal_notice = new Page();
		$legal_notice->set_slug( 'legal_notice' );
		$legal_notice->set_page_title( __( 'Legal notices', 'hjqs-legal-notice' ) );
		$legal_notice->set_menu_title( __( 'Legal notices', 'hjqs-legal-notice' ) );
		$legal_notice->set_capability( 'manage_options' );
		$legal_notice->set_show_in_admin_menu( true );

		$legal_notice_form                        = new LegalNoticeForm();
		$this->forms[ $legal_notice->get_slug() ] = $legal_notice_form->get_form();

		$privacy_policy = new Page();
		$privacy_policy->set_slug( 'privacy_policy' );
		$privacy_policy->set_page_title( __( 'Privacy policy', 'hjqs-legal-notice' ) );
		$privacy_policy->set_menu_title( __( 'Privacy policy', 'hjqs-legal-notice' ) );
		$privacy_policy->set_capability( 'manage_options' );
		$privacy_policy->set_show_in_admin_menu( false );

		$privacy_policy_form                        = new PrivacyPolicyForm();
		$this->forms[ $privacy_policy->get_slug() ] = $privacy_policy_form->get_form();

		$terms_of_sales = new Page();
		$terms_of_sales->set_slug( 'terms_of_sales' );
		$terms_of_sales->set_page_title( __( 'Terms and conditions', 'hjqs-legal-notice' ) );
		$terms_of_sales->set_menu_title( __( 'Terms and conditions', 'hjqs-legal-notice' ) );
		$terms_of_sales->set_capability( 'manage_options' );
		$terms_of_sales->set_show_in_admin_menu( false );

		$terms_of_sales_form                        = new TermsOfSalesForm();
		$this->forms[ $terms_of_sales->get_slug() ] = $terms_of_sales_form->get_form();

		return [ $legal_notice, $privacy_policy, $terms_of_sales ];
	}

	/**
	 * @desc Préparation des templates à utiliser sur les pages d'administration (avec le header, le body et le footer)
	 * @return void
	 */
	public function add_settings_pages(): void {
		$pages = $this->prepare_admin_pages();

		foreach ( $pages as $page ) {
			$page_index         = $page->get_slug();
			$page_title         = $page->get_page_title();
			$menu_title         = $page->get_menu_title();
			$capability         = $page->get_capability();
			$show_in_admin_menu = $page->is_show_in_admin_menu();
			$plugin_name        = $this->plugin_name;

			$render_function = function () use ( $page, $pages, $plugin_name ) {
				echo $this->render( plugin_dir_path( dirname( __FILE__, 2 ) ) . 'templates/admin/header.php', [
					'pages'        => $pages,
					'current_page' => $page,
					'plugin_name'  => $plugin_name
				] );
				echo $this->render( plugin_dir_path( dirname( __FILE__, 2 ) ) . 'templates/admin/body.php', [
					'form'        => $this->render_form( $page ),
					'plugin_name' => $plugin_name
				] );
				echo $this->render( plugin_dir_path( dirname( __FILE__, 2 ) ) . 'templates/admin/footer.php' );
			};
			if ( $show_in_admin_menu ) {
				$menu = add_options_page( $page_title, $menu_title, $capability, $page_index, $render_function );
			} else {
				$menu = add_submenu_page( null, $page_title, $menu_title, $capability, $page_index, $render_function );
			}
			add_action( 'admin_print_styles-' . $menu, [ $this, 'admin_custom_css' ] );
			add_action( 'admin_print_scripts-' . $menu, [ $this, 'admin_custom_js' ] );
		}
	}

	public function admin_custom_css() {
		wp_enqueue_style( 'hjqs-legal-notice-admin', plugin_dir_url( dirname( __FILE__, 2 ) ) . 'assets/css/hjqs-legal-notice-admin.css' );
	}

	public function admin_custom_js() {
		wp_enqueue_script( 'clipboard', plugin_dir_url( dirname( __FILE__, 2 ) ) . 'assets/js/clipboard.min.js', [], false, true );
		wp_enqueue_script( 'hjqs-legal-notice-admin', plugin_dir_url( dirname( __FILE__, 2 ) ) . 'assets/js/hjqs-legal-notice-admin.js', [], false, true );
	}

	/**
	 * @desc Permet d'ajouter le bouton settings dans la page des extensions WordPress
	 *
	 * @param $links
	 *
	 * @return mixed
	 */
	public function plugin_add_settings_link( $links ): mixed {
		$settings_link = '<a href="options-general.php?page=' . $this->plugin_admin_url . '">' . __( 'Settings' ) . '</a>';
		$links[]       = $settings_link;

		return $links;
	}

	/**
	 * @desc Aide a afficher le template
	 *
	 * @param $templateFile
	 * @param array $vars
	 *
	 * @return bool|string
	 */
	public function render( $templateFile, array $vars = [] ): bool|string {
		ob_start();
		extract( $vars );
		require( $templateFile );

		return ob_get_clean();
	}

	/**
	 * @desc Retourne le formulaire courtant
	 *
	 * @param $page
	 *
	 * @return mixed
	 */
	public function render_form( $page ): mixed {
		return $this->forms[ $page->get_slug() ];
	}

	/**
	 * @desc Permet d'afficher un champ
	 *
	 * @param $field *
	 *
	 * @return void
	 */
	public function render_field( $field ): void {
		if ( $field->get_type() === 'wp_editor' ) {
			echo $this->render( plugin_dir_path( dirname( __FILE__, 2 ) ) . 'templates/admin/components/wp-editor.php', [
				'field' => $field
			] );
		} else {
			echo $this->render( plugin_dir_path( dirname( __FILE__, 2 ) ) . 'templates/admin/components/field.php', [
				'field' => $field
			] );
		}
	}

	/**
	 * Permet de supprimer les options du plugin lorsque le plugin est desactivé
	 * @return void
	 */
	public function plugin_deactivate(): void {
		foreach ( $this->forms as $form ) {
			$option_key = 'hjqs_' . $form->get_slug();
			delete_option( $option_key );
		}
	}
}
