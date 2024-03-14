<?php

namespace Form;

use Entity\Form;

class PrivacyPolicyForm extends BaseForm {

	public Form $form;

	public function __construct() {
		$form_slug        = 'hjqs_privacy_policy';
		$form_title       = __( 'Privacy policy form', 'hjqs-legal-notice' );
		$form_description = __( "Fill out the form with information about your business and website, then insert the shortcode <code class='hjqs-shortcode' data-clipboard-text='[hjqs_pdc]'>[hjqs_pdc]</code> in the desired page.", 'hjqs-legal-notice' );


		$fields = [
			[
				'label'      => __( 'Site owner', 'hjqs-legal-notice' ),
				'helper'     => __( "Enter the name of the owner of the website, that is, the person or company responsible for it.", 'hjqs-legal-notice' ),
				"option_key" => "pp_ps",
				'type'       => 'text',
			],
			[
				'label'      => __( "Mailing address of the owner", 'hjqs-legal-notice' ),
				"option_key" => "pp_app",
				'helper'     => __( "Enter the mailing address of the owner of the website, i.e. the person or company responsible for it.", 'hjqs-legal-notice' ),
				'type'       => 'text',
			],
			[
				'label'      => __( "Data protection officer", 'hjqs-legal-notice' ),
				"option_key" => "ln_dpo",
				'helper'     => __( "It is mandatory to appoint a data protection officer (DPO) or a data protection responsible (DPR) who will be responsible for managing the privacy policy and protecting the personal data of users.", 'hjqs-legal-notice' ),
				'type'       => 'text',
			],
			[
				"label"      => __( "Purposes of data collection", 'hjqs-legal-notice' ),
				"type"       => "checkbox",
				"helper"     => __( "Indicate the purposes for which the personal data of users is collected and used", 'hjqs-legal-notice' ),
				"option_key" => "pp_pdc",
				"value"      => null,
				"choices"    => [
					"Finalités liées à l'exécution d'un contrat : les données sont collectées pour l'exécution d'un contrat ou la mise en place de mesures précontractuelles à la demande de l'utilisateur."                                                                   => __( "Finalités liées à l'exécution d'un contrat : les données sont collectées pour l'exécution d'un contrat ou la mise en place de mesures précontractuelles à la demande de l'utilisateur.", 'hjqs-legal-notice' ),
					"Finalités liées à l'intérêt légitime de l'entreprise : les données sont collectées pour protéger les intérêts légitimes de l'entreprise, tels que la gestion des relations avec les clients, la gestion des réclamations, la sécurité des systèmes, etc." => __( "Finalités liées à l'intérêt légitime de l'entreprise : les données sont collectées pour protéger les intérêts légitimes de l'entreprise, tels que la gestion des relations avec les clients, la gestion des réclamations, la sécurité des systèmes, etc.", 'hjqs-legal-notice' ),
					"Finalités liées à l'obligation légale : les données sont collectées pour se conformer à une obligation légale, telle que la tenue de registres pour la comptabilité ou la conformité aux lois en matière de protection des données."                      => __( "Finalités liées à l'obligation légale : les données sont collectées pour se conformer à une obligation légale, telle que la tenue de registres pour la comptabilité ou la conformité aux lois en matière de protection des données.", 'hjqs-legal-notice' ),
					"Finalités liées à l'obtention du consentement de l'utilisateur : les données sont collectées avec le consentement de l'utilisateur, par exemple pour envoyer des newsletters ou des offres spéciales."                                                    => __( "Finalités liées à l'obtention du consentement de l'utilisateur : les données sont collectées avec le consentement de l'utilisateur, par exemple pour envoyer des newsletters ou des offres spéciales.", 'hjqs-legal-notice' ),
					"Finalités liées à la protection des intérêts vitaux de l'utilisateur : les données sont collectées pour protéger les intérêts vitaux de l'utilisateur, par exemple en cas de situation de danger imminent."                                               => __( "Finalités liées à la protection des intérêts vitaux de l'utilisateur : les données sont collectées pour protéger les intérêts vitaux de l'utilisateur, par exemple en cas de situation de danger imminent.", 'hjqs-legal-notice' ),
					"Finalités liées à l'amélioration du site internet : les données sont collectées pour analyser et améliorer le site internet, par exemple pour personnaliser l'expérience de l'utilisateur."                                                               => __( "Finalités liées à l'amélioration du site internet : les données sont collectées pour analyser et améliorer le site internet, par exemple pour personnaliser la navigation de l'utilisateur.", 'hjqs-legal-notice' ),
					"Finalités liées au retargeting marketing : les données sont collectées pour cibler les publicités et les communications marketing en fonction de l'historique de navigation de l'utilisateur sur le site internet."                                       => __( "Finalités liées au retargeting marketing : les données sont collectées pour cibler les publicités et les communications marketing en fonction de l'historique de navigation de l'utilisateur sur le site internet.", 'hjqs-legal-notice' ),
					'custom'                                                                                                                                                                                                                                                   => [
						'label'            => __( "Custom", 'hjqs-legal-notice' ),
						"allow_to_add_new" => true,
						"type"             => "text",
						'option_key'       => 'pp_pdc_bis'
					]
				],
			],
			[
				'label'      => __( "Data retention period", 'hjqs-legal-notice' ),
				"option_key" => "ln_drp",
				'helper'     => __( "It is recommended to specify the period for which user personal data will be retained.", 'hjqs-legal-notice' ),
				'type'       => 'text',
			],
			[
				"label"      => __( "Type of data collected", 'hjqs-legal-notice' ),
				"type"       => "checkbox",
				"helper"     => __( "The field refers to the types of personal information that are collected from users when they visit your website or interact with your business.", 'hjqs-legal-notice' ),
				"option_key" => "pp_tdc",
				"value"      => [ 'Adresse IP', 'Email' ],
				"choices"    => [
					"Nom"                    => __( "Lastname", 'hjqs-legal-notice' ),
					"Prénom"                 => __( "Firstname", 'hjqs-legal-notice' ),
					"Email"                  => __( "Email", 'hjqs-legal-notice' ),
					"Numéro de téléphone"    => __( "Phone number", 'hjqs-legal-notice' ),
					"Adresse de livraison"   => __( "Shipping address", 'hjqs-legal-notice' ),
					"Adresse de facturation" => __( "Billing address", 'hjqs-legal-notice' ),
					"Adresse IP"             => __( "IP Address", 'hjqs-legal-notice' ),
					'custom'                 => [
						'label'            => __( "Custom", 'hjqs-legal-notice' ),
						"allow_to_add_new" => true,
						"type"             => "text",
						'option_key'       => 'pp_tdc_bis'
					]
				],
			],
			[
				"label"      => __( "Data collection methods", 'hjqs-legal-notice' ),
				"type"       => "checkbox",
				"helper"     => __( "", 'hjqs-legal-notice' ),
				"option_key" => "pp_dcm",
				"value"      => [
					'Formulaires de contact',
					"Formulaire de réinitialisation de mot de passe",
					"Cookies"
				],
				"choices"    => [
					"Formulaires de contact"                         => __( "Contact forms", 'hjqs-legal-notice' ),
					"Formulaires de commentaire"                     => __( "Forms for commenting", 'hjqs-legal-notice' ),
					"Formulaire d'inscription"                       => __( "Registration form", 'hjqs-legal-notice' ),
					"Formulaire de réinitialisation de mot de passe" => __( "Password reset form", 'hjqs-legal-notice' ),
					"Cookies"                                        => __( "Cookies", 'hjqs-legal-notice' ),
					'custom'                                         => [
						'label'            => __( "Custom", 'hjqs-legal-notice' ),
						"allow_to_add_new" => true,
						"type"             => "text",
						'option_key'       => 'pp_dcm_bis'
					]
				],
			],
			[
				"label"      => __( "Sharing data with third parties", 'hjqs-legal-notice' ),
				"type"       => "checkbox",
				"helper"     => __( "The field allows the user to specify whether or not their personal data will be shared with third-party tools or services. This might include tools for analytics, marketing, or customer relationship management, for example. It is important for the user to understand which third-party tools their data will be shared with, and for what purposes, in order to make an informed decision about whether or not to allow the sharing of their data.", 'hjqs-legal-notice' ),
				"option_key" => "pp_sdtp",
				"value"      => [ 'Gravatar' ],
				"choices"    => [
					"Gravatar"           => __( "Gravatar", 'hjqs-legal-notice' ),
					"Google Analytics"   => __( "Google Analytics", 'hjqs-legal-notice' ),
					"Google Adwords"     => __( "Google Adwords", 'hjqs-legal-notice' ),
					"Pixel Facebook"     => __( "Pixel Facebook", 'hjqs-legal-notice' ),
					"LinkedIn Tracking"  => __( "LinkedIn Tracking", 'hjqs-legal-notice' ),
					"Hotjar"             => __( "Hotjar", 'hjqs-legal-notice' ),
					"Crazy Egg"          => __( "Crazy Egg", 'hjqs-legal-notice' ),
					"Stat Counter"       => __( "Stat Counter", 'hjqs-legal-notice' ),
					"Clicky"             => __( "Clicky", 'hjqs-legal-notice' ),
					"Kiss Metrics"       => __( "Kiss Metrics", 'hjqs-legal-notice' ),
					"Woopra"             => __( "Woopra", 'hjqs-legal-notice' ),
					"Adobe Analytics"    => __( "Adobe Analytics", 'hjqs-legal-notice' ),
					"Matomo"             => __( "Matomo", 'hjqs-legal-notice' ),
					"Mixpanel"           => __( "Mixpanel", 'hjqs-legal-notice' ),
					"Open Web Analytics" => __( "Open Web Analytics", 'hjqs-legal-notice' ),
					'custom'             => [
						'label'            => __( "Custom", 'hjqs-legal-notice' ),
						"allow_to_add_new" => true,
						"type"             => "text",
						'option_key'       => 'pp_sdtp_bis'
					]
				],
			],
			[
				'label'            => __( "Base text", 'hjqs-legal-notice' ),
				"option_key"       => "pp_content",
				'helper'           => __( "<p>The Base Text field is a text editor that allows you to customize the content of the legal notices on your website. You can use this editor to add, delete or modify the basic text provided by the plugin.</p><br/><p>To use the variables in the base text, you must enclose them in two percent signs (%%). For example, if you want to display the name of the owner of the site in the legal notices, you can use the %%ln_ps%% variable. When the plugin generates the legal notices, it will replace this variable with the corresponding value entered in the form.</p><br/><p>It is important to check that the variables you use in the base text are correct and correspond to the fields of the form. If you use a variable that does not exist, the plugin will not be able to replace it and you may end up with incorrect or incomplete content.</p><br/><p>It is also recommended to check that the content of the legal notices is complete and compliant with the regulations in force. If you are not sure what should be included in your legal notices, you can consult online guides or seek the help of a lawyer specializing in internet law.</p>", 'hjqs-legal-notice' ),
				'type'             => 'wp_editor',
				'value'            => $this->content(),
				'is_content_field' => true
			],
		];

		$this->form = $this->create_form( $form_slug, $form_title, $fields, $form_description );
	}

	/**
	 * @return Form
	 */
	public function get_form(): Form {
		return $this->form;
	}

	public function content(): string {
		return <<<HTML
		<h2>1. Introduction</h2>
<p>La confidentialité des visiteurs de notre site web est très importante à nos yeux, et nous nous engageons à la protéger. Cette politique détaille ce que nous faisons de vos informations personnelles chez %%pp_ps%%.</p>

<h2>2. Collecte d’informations personnelles</h2>
<p>Les types d’informations personnelles suivants peuvent collectés, stockés et utilisés : %%pp_tdc%%</p>
<p>Les informations personnelles peuvent être collectées via : %%pp_dcm%%</p>

<h2>3. Utilisation de vos informations personnelles</h2>
<p>Les informations personnelles qui nous sont fournies par le biais de notre site web seront utilisées dans les objectifs décrits dans cette politique ou dans les pages du site pertinentes. Nous pouvons utiliser vos informations personnelles pour : %%pp_pdc%%</p>
<p>Avant de nous divulguer des informations personnelles concernant une autre personne, vous devez obtenir le consentement de ladite personne en ce qui concerne la divulgation et le traitement de ces informations personnelles selon les termes de cette politique.</p>
<p>Si vous soumettez des informations personnelles sur notre site web dans le but de les publier, nous les publierons et pourrons utiliser ces informations conformément aux autorisations que vous nous accordez.</p>
<p>Sans votre consentement explicite, nous ne fournirons pas vos informations personnelles à des tierces parties pour leur marketing direct, ni celui d’autres tierces parties.</p>
<p>Une chaîne anonymisée créée à partir de votre adresse de messagerie (également appelée hash) peut être envoyée au service Gravatar pour vérifier si vous utilisez ce dernier. Les clauses de confidentialité du service Gravatar sont disponibles ici : https://automattic.com/privacy/. Après validation de votre commentaire, votre photo de profil sera visible publiquement à coté de votre commentaire.</p>
<p>Si vous êtes un utilisateur ou une utilisatrice enregistré·e et que vous téléversez des images sur le site web, nous vous conseillons d’éviter de téléverser des images contenant des données EXIF de coordonnées GPS. Les visiteurs de votre site web peuvent télécharger et extraire des données de localisation depuis ces images.</p>

<h2>4. Divulgation de vos informations personnelles</h2>
<p>Nous pouvons divulguer vos informations personnelles à n’importe lequel de nos employés, dirigeants, assureurs, conseillers professionnels, agents, fournisseurs, ou sous-traitants dans la mesure où cela est raisonnablement nécessaire aux fins énoncées dans cette politique.</p>
<p>Nous pouvons divulguer vos informations personnelles à n’importe quel membre de notre groupe d’entreprises (cela signifie nos filiales, notre société holding ultime et toutes ses filiales) dans la mesure où cela est raisonnablement nécessaire aux fins énoncées dans cette politique.</p>
<p>Nous pouvons divulguer vos informations personnelles :</p>
<ul>
        <li>Dans la mesure où nous sommes tenus de le faire par la loi ;</li>
        <li>Dans le cadre de toute procédure judiciaire en cours ou à venir ;</li>
        <li>Pour établir, exercer ou défendre nos droits légaux (y compris fournir des informations à d’autres à des fins de prévention des fraudes et de réduction des risques de crédit) ;</li>
        <li>À l’acheteur (ou acheteur potentiel) de toute entreprise ou actif en notre possession que nous souhaitons (ou envisageons de) vendre ;</li>
        <li>À toute personne que nous estimons raisonnablement faire partie intégrante d’un tribunal ou autre autorité compétente pour la divulgation de ces informations personnelles si, selon notre opinion, un tel tribunal ou une telle autorité serait susceptible de demander la divulgation de ces informations personnelles.</li>
        <li>Sauf disposition contraire de la présente politique, nous ne transmettrons pas vos informations personnelles à des tierces parties.</li>
</ul>

<h2>5. Transferts internationaux de données</h2>
<p>Les informations que nous collectons peuvent être stockées, traitées et transférées dans tous les pays dans lesquels nous opérons, afin de nous permettre d’utiliser les informations en accord avec cette politique.</p>
<p>Les informations que nous collectons peuvent être transférées dans les pays suivants, n’ayant pas de lois de protections des données équivalentes à celles en vigueur dans l’espace économique européen : les États-Unis d’Amérique, la Russie, le Japon, la Chine et l’Inde.</p>
<p>Les informations personnelles que vous publiez sur notre site web ou que vous soumettez à la publication peuvent être disponibles, via internet, dans le monde entier. Nous ne pouvons empêcher l’utilisation, bonne ou mauvaise, de ces informations par des tiers.</p>
<p>Vous acceptez expressément le transfert d’informations personnelles décrit dans cette partie.</p>

<h2>6. Conservation de vos informations personnelles</h2>
<p>Les informations personnelles que nous traitons à quelque fin que ce soit ne sont pas conservées plus longtemps que nécessaire à cette fin ou à ces fins.</p>
<p>Sans préjudice à l’article, nous supprimerons généralement les données personnelles de ces catégories</p>
<p>La durée de conservation des données est de %%ln_drp%%</p>
<p>Nous conserverons des documents (y compris des documents électroniques) contenant des données personnelles :</p>
<ul>
        <li>Dans la mesure où nous sommes tenus de le faire par la loi ;</li>
        <li>Si nous pensons que les documents peuvent être pertinents pour toute procédure judiciaire en cours ou potentielle ;</li>
        <li>Pour établir, exercer ou défendre nos droits légaux (y compris fournir des informations à d’autres à des fins de prévention des fraudes et de réduction des risques de crédit).</li>
</ul>
<p>Si vous laissez un commentaire, le commentaire et ses métadonnées sont conservés indéfiniment. Cela permet de reconnaître et approuver automatiquement les commentaires suivants au lieu de les laisser dans la file de modération.</p>
<p>Pour les utilisateurs et utilisatrices qui s’inscrivent sur notre site (si cela est possible), nous stockons également les données personnelles indiquées dans leur profil. Tous les utilisateurs et utilisatrices peuvent voir, modifier ou supprimer leurs informations personnelles à tout moment (à l’exception de leur nom d’utilisateur·ice). Les gestionnaires du site peuvent aussi voir et modifier ces informations.</p>

<h2>7. Sécurité de vos informations personnelles</h2>
<p>Nous prendrons des précautions techniques et organisationnelles raisonnables pour empêcher la perte, l’abus ou l’altération de vos informations personnelle.</p>
<p>Nous stockerons toutes les informations personnelles que vous nous fournissez sur des serveurs sécurisés (protégés par mot de passe et pare-feu).</p>
<p>Toutes les transactions financières électroniques effectuées par le biais de notre site web seront protégées par des technologies de cryptage.</p>
<p>Vous reconnaissez que la transmission d’informations par internet est intrinsèquement non sécurisée, et que nous ne pouvons pas garantir la sécurité de vos données envoyées par internet.</p>
<p>Vous êtes responsable de la confidentialité du mot de passe que vous utilisez pour accéder à notre site web ; nous ne vous demanderons pas votre mot de passe (sauf quand vous vous identifiez sur notre site web).</p>

<h2>8. Les droits que vous avez sur vos données</h2>
<p>Si vous avez un compte ou si vous avez laissé des commentaires sur le site, vous pouvez demander à recevoir un fichier contenant toutes les données personnelles que nous possédons à votre sujet, incluant celles que vous nous avez fournies. Vous pouvez également demander la suppression des données personnelles vous concernant. Cela ne prend pas en compte les données stockées à des fins administratives, légales ou pour des raisons de sécurité.</p>
<p>Pour toutes demandes concernant vos données, vous pouvez contacter notre Délégué à la protection des données (DPO) : %%ln_dpo%% via le formulaire de contact sur notre site internet ou par voie postale : %%pp_ps%% - %%pp_app%%</p>

<h2>9. Sites web tiers</h2>
<p>Notre site web contient des liens hypertextes menant vers des sites web tiers et des informations les concernant. Nous n’avons aucun contrôle sur ces sites, et ne sommes pas responsables de leurs politiques de confidentialité ni de leurs pratiques.</p>
<p>Les articles de ce site peuvent inclure des contenus intégrés (par exemple des vidéos, images, articles…). Le contenu intégré depuis d’autres sites se comporte de la même manière que si le visiteur se rendait sur cet autre site.</p>
<p>Ces sites web pourraient collecter des données sur vous, utiliser des cookies, embarquer des outils de suivis tiers, suivre vos interactions avec ces contenus embarqués si vous disposez d’un compte connecté sur leur site web.</p>

<h2>10. Cookies</h2>
<p>Notre site web utilise des cookies. Un cookie est un fichier contenant un identifiant (une chaîne de lettres et de chiffres) envoyé par un serveur web vers un navigateur web et stocké par le navigateur. L’identifiant est alors renvoyé au serveur à chaque fois que le navigateur demande une page au serveur. Les cookies peuvent être « persistants » ou « de session » : un cookie persistant est stocké par le navigateur et reste valide jusqu’à sa date d’expiration, à moins d’être supprimé par l’utilisateur avant cette date d’expiration ; quant à un cookie de session, il expire à la fin de la session utilisateur, lors de la fermeture du navigateur. Les cookies ne contiennent en général aucune information permettant d’identifier personnellement un utilisateur, mais les informations personnelles que nous stockons à votre sujet peuvent être liées aux informations stockées dans les cookies et obtenues par les cookies.</p>
<p>Nous pouvons utiliser les cookies pour :</p>
<ul>
        <li>Reconnaître un ordinateur lorsqu’un utilisateur consulte le site web</li>
        <li>Suivre les utilisateurs lors de leur navigation sur le site web</li>
        <li>Activer l’utilisation d’un panier sur le site web</li>
        <li>Améliorer l’utilisation d’un site web</li>
        <li>Analyser l’utilisation du site web</li>
        <li>Administrer le site web</li>
        <li>Empêcher la fraude et améliorer la sécurité du site web</li>
        <li>Personnaliser le site web pour chaque utilisateur</li>
        <li>Envoyer des publicités ciblées pouvant intéresser certains utilisateurs </li>
</ul>
<p>Pour cela, nous pouvons utiliser les services suivants : %%pp_sdtp%%</p>
<p>La plupart des navigateurs vous permettent de refuser ou d’accepter les cookies :</p>
<ul>
        <li>avec Internet Explorer (version 10), vous pouvez bloquer les cookies en utilisant les paramètres de remplacement de la gestion des cookies disponibles en cliquant sur «Outils», «Options internet», «Confidentialité» puis «Avancé»;</li>
        <li>avec Firefox (version 24), vous pouvez bloquer tous les cookies en cliquant sur «Outils», «Options», «Confidentialité» puis en sélectionnant «Utiliser des paramètres personnalisés pour l’historique» depuis le menu déroulant et en décochant «Accepter les cookies provenant des sites»;</li>
        <li>avec Chrome (version 29), vous pouvez bloquer tous les cookies en accédant au menu «Personnaliser et contrôler» puis en cliquant sur «Paramètres», «Montrer les paramètres avancés» et «Paramètres de contenu» puis en sélectionnant «Empêcher les sites de définir des données» dans l’en-tête «Cookies».</li>
</ul>
<p>Bloquer tous les cookies aura un impact négatif sur l’utilisation de nombreux sites web. Si vous bloquez les cookies, vous ne pourrez pas utiliser toutes les fonctionnalités de notre site web.</p>
<p>Vous pouvez supprimer les cookies déjà stockés sur votre ordinateur :</p>
<ul>
        <li>avec Internet Explorer (version 10), vous devez supprimer le fichier cookies manuellement (vous pourrez trouver des instructions pour le faire <a rel="nofollow" target="_blank" title="Support Microsoft - Comment faire pour supprimer des fichiers cookie dans Internet Explorer" href="https://support.microsoft.com/fr-fr/topic/comment-faire-pour-supprimer-des-fichiers-cookie-dans-internet-explorer-bca9446f-d873-78de-77ba-d42645fa52fc">ici</a> );</li>
        <li>avec Firefox (version 24), vous pouvez supprimer les cookies en cliquant sur «Outils», «Options», et «Confidentialité», puis en sélectionnant «Utiliser des paramètres personnalisés pour l’historique» et en cliquant sur «Montrer les cookies», puis sur «Supprimer tous les cookies»;</li>
        <li>avec Chrome (version 29), vous pouvez supprimer tous les cookies en accédant au menu «Personnaliser et contrôler» puis en cliquant sur «Paramètres», « Montrer les paramètres avancés » et «Supprimer les données de navigation» puis «Supprimer les cookies et les données des modules d’autres sites» avant de cliquer sur «Supprimer les données de navigation».</li>
</ul>
<p>Supprimer les cookies aura un impact négatif sur l’utilisation de nombreux sites web.</p>
<p>Si vous vous rendez sur la page de connexion, un cookie temporaire sera créé afin de déterminer si votre navigateur accepte les cookies. Il ne contient pas de données personnelles et sera supprimé automatiquement à la fermeture de votre navigateur.</p>
<p>Lorsque vous vous connecterez, nous mettrons en place un certain nombre de cookies pour enregistrer vos informations de connexion et vos préférences d’écran. La durée de vie d’un cookie de connexion est de deux jours, celle d’un cookie d’option d’écran est d’un an. Si vous cochez « Se souvenir de moi », votre cookie de connexion sera conservé pendant deux semaines. Si vous vous déconnectez de votre compte, le cookie de connexion sera effacé.</p>
<p>En modifiant ou en publiant une publication, un cookie supplémentaire sera enregistré dans votre navigateur. Ce cookie ne comprend aucune donnée personnelle. Il indique simplement l’ID de la publication que vous venez de modifier. Il expire au bout d’un jour.</p>

<h2>11. Modifications de la politique de confidentialité</h2>
<p>Nous pouvons parfois mettre cette politique à jour en publiant une nouvelle version sur notre site web. Vous devez vérifier cette page régulièrement pour vous assurer de prendre connaissance de tout changement effectué à cette politique. Nous pouvons vous informer des changements effectués à cette politique par courrier électronique ou par le biais du service de messagerie privée de notre site web.</p>
HTML;
	}

}