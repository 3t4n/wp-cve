<?php
/**
 * Mentions légales [FR]
 *
 * @package           HjqsLegalNotice
 * @author            HugoJQS
 * @copyright         Hugo JACQUES
 *
 * @wordpress-plugin
 * Plugin Name:       Mentions légales [FR]
 * Description:       Le plugin de mentions légales est un outil qui permet aux propriétaires de sites de créer et d'afficher des mentions légales sur leur site (mentions légales, conditions générales de vente et politique de confidentialité).
 * Version:           2.0.3
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            Hugo JACQUES
 * Author URI:        https://hugojqs.fr
 * Text Domain:       hjqs-legal-notice
 * Domain Path: /languages
 *
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

use Controller\AdminController;
use Controller\PublicController;

require_once plugin_dir_path( __FILE__ ) . 'autoload.php';
hjqs_autoload( plugin_dir_path( __FILE__ ) . 'src/', '' );


$admin  = new AdminController();
$public = new PublicController();

register_deactivation_hook( __FILE__, [ $admin, 'plugin_deactivate' ] );




function slugify( $text, string $divider = '-' ): string {
	// replace non letter or digits by divider
	$text = preg_replace( '~[^\pL\d]+~u', $divider, $text );
	// transliterate
	$text = iconv( 'utf-8', 'us-ascii//TRANSLIT', $text );
	// remove unwanted characters
	$text = preg_replace( '~[^-\w]+~', '', $text );
	// trim
	$text = trim( $text, $divider );
	// remove duplicate divider
	$text = preg_replace( '~-+~', $divider, $text );
	// lowercase
	$text = strtolower( $text );
	if ( empty( $text ) ) {
		return 'n-a';
	}

	return $text;
}

add_action( 'plugins_loaded', 'load_text_domain' );
function load_text_domain(): void {
	load_plugin_textdomain( 'hjqs-legal-notice', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}


add_action( 'hjqs_legal_notice', 'update_hjqs_ln' );

function update_hjqs_ln(): void {
	// 1.0.0 -> 2.0.0
	$old_options = get_option( 'hjqs_mentions_legales_options' );
	if ( $old_options ) {
		$legal_notice   = get_option( 'hjqs_legal_notice' );
		$privacy_policy = get_option( 'hjqs_privacy_policy' );
		$terms_of_sales = get_option( 'hjqs_terms_of_sales' );

		foreach ( $old_options as $key => $old_option ) {
			switch ( $key ) {
				// LEGAL NOTICES
				case 'forme_juridique' :
					$legal_notice['ln_fje'] = $old_option;
					break;
				case 'proprietaire' :
				case 'cgv_proprietaire' :
					$legal_notice['ln_ps']   = $old_option;
					$privacy_policy['pp_ps'] = $old_option;
					$terms_of_sales['tos_ps'] = $old_option;
					break;
				case 'siret' :
					$legal_notice['ln_ns'] = $old_option;
					break;
				case 'rcs' :
				case 'cgv_rcs' :
					$legal_notice['ln_rcs'] = $old_option;
					$terms_of_sales['tos_rcs'] = $old_option;
					break;
				case 'adresse' :
				case 'cgv_adresse' :
					$legal_notice['ln_app']   = $old_option;
					$privacy_policy['pp_app'] = $old_option;
					$terms_of_sales['tos_app'] = $old_option;
					break;
				case 'responsable' :
				case 'cgv_responsable' :
					$legal_notice['ln_nrp'] = $old_option;
					$legal_notice['pp_dpo'] = $old_option;
					$terms_of_sales['tos_nrp'] = $old_option;
					break;
				case 'responsable_contact' :
				case 'cgv_responsable_contact' :
					$legal_notice['ln_etrp'] = $old_option;
					$terms_of_sales['tos_etrp'] = $old_option;
					break;
				case 'createur' :
					$legal_notice['ln_ncs'] = $old_option;
					break;
				case 'createur_site' :
					$legal_notice['ln_sics'] = $old_option;
					break;
				case 'createur_contact' :
					$legal_notice['ln_etcs'] = $old_option;
					break;
				case 'hebergeur' :
					$legal_notice['ln_hs_bis'] = $old_option;
					$legal_notice['ln_hs']     = 'custom';
					break;
				// PRIVACY POLICY
				case 'pdc_duree_de_concervation_des_donnees' :
					$privacy_policy['ln_drp'] = $old_option;
					break;
				case 'pdc_outils_stats' :
					$privacy_policy['pp_sdtp'] = $old_option;
					break;
				case 'pdc_user_can_subscribe':
					if ( $old_option == "Oui" ) {
						$privacy_policy['pp_dcm'][] = "Formulaire d'inscription";
						$privacy_policy['pp_dcm'][] = "Formulaire de réinitialisation de mot de passe";
						$privacy_policy['pp_tdc'][] = "Nom";
						$privacy_policy['pp_tdc'][] = "Prénom";
						$privacy_policy['pp_tdc'][] = "Email";
					}
					break;
				case 'pdc_user_can_submit_form':
					if ( $old_option == "Oui" ) {
						$privacy_policy['pp_dcm'][] = "Formulaires de contact";
						$privacy_policy['pp_tdc'][] = "Email";
					}
					break;
				case 'pdc_user_can_purcharge':
					if ( $old_option == "Oui" ) {
						$privacy_policy['pp_dcm'][] = "Formulaire d'inscription";
						$privacy_policy['pp_tdc'][] = "Nom";
						$privacy_policy['pp_tdc'][] = "Prénom";
						$privacy_policy['pp_tdc'][] = "Email";
						$privacy_policy['pp_tdc'][] = "Numéro de téléphone";
						$privacy_policy['pp_tdc'][] = "Adresse de livraison";
						$privacy_policy['pp_tdc'][] = "Adresse de facturation";
					}
					break;
				case 'pdc_user_can_publish':
					if ( $old_option == "Oui" ) {
						$privacy_policy['pp_dcm_bis'] = "Formulaire de publication";
						$privacy_policy['pp_dcm'][]   = "custom";
						$privacy_policy['pp_tdc'][]   = "Email";
					}
					break;
				case 'cgv_moyens_de_paiement' :
					$terms_of_sales['tos_pm'] = $old_option;
					break;
				case 'cgv_conditions_retour' :
					$terms_of_sales['tos_rc'] = $old_option;
					break;
				case 'cgv_indisponibilite_produit_temps' :
					$terms_of_sales['tos_cop'] = $old_option;
					break;

			}
		}

		$privacy_policy['pp_dcm'][] = "Cookies";
		$privacy_policy['pp_dcm']   = array_unique( $privacy_policy['pp_dcm'] );

		$privacy_policy['pp_tdc'][] = "Adresse IP";
		$privacy_policy['pp_tdc']   = array_unique( $privacy_policy['pp_tdc'] );

		$privacy_policy['pp_pdc'] = [
			"Finalités liées à l'exécution d'un contrat : les données sont collectées pour l'exécution d'un contrat ou la mise en place de mesures précontractuelles à la demande de l'utilisateur.",
			"Finalités liées à l'intérêt légitime de l'entreprise : les données sont collectées pour protéger les intérêts légitimes de l'entreprise, tels que la gestion des relations avec les clients, la gestion des réclamations, la sécurité des systèmes, etc.",
			"Finalités liées à l'obligation légale : les données sont collectées pour se conformer à une obligation légale, telle que la tenue de registres pour la comptabilité ou la conformité aux lois en matière de protection des données.",
			"Finalités liées à l'obtention du consentement de l'utilisateur : les données sont collectées avec le consentement de l'utilisateur, par exemple pour envoyer des newsletters ou des offres spéciales.",
			"Finalités liées à la protection des intérêts vitaux de l'utilisateur : les données sont collectées pour protéger les intérêts vitaux de l'utilisateur, par exemple en cas de situation de danger imminent.",
			"Finalités liées à l'amélioration du site internet : les données sont collectées pour analyser et améliorer le site internet, par exemple pour personnaliser l'expérience de l'utilisateur.",
			"Finalités liées au retargeting marketing : les données sont collectées pour cibler les publicités et les communications marketing en fonction de l'historique de navigation de l'utilisateur sur le site internet."
		];


		update_option( 'hjqs_legal_notice', $legal_notice );
		update_option( 'hjqs_privacy_policy', $privacy_policy );
		update_option( 'hjqs_terms_of_sales', $terms_of_sales );

		delete_option('hjqs_mentions_legales_options');
	}

	// 2.0.0 -> 2.0.3
	$old_options = get_option( 'hjqs_legal_notice' );
	if($old_options){
		update_option('hjqs_hjqs_legal_notice', $old_options);
		delete_option("hjqs_legal_notice");
	}
	$old_options = get_option( 'hjqs_privacy_policy' );
	if($old_options){
		update_option('hjqs_hjqs_privacy_policy', $old_options);
		delete_option("hjqs_privacy_policy");
	}
	$old_options = get_option( 'hjqs_terms_of_sales' );
	if($old_options){
		update_option('hjqs_hjqs_terms_of_sales', $old_options);
		delete_option("hjqs_privacy_policy");
	}


}