<?php

namespace Form;

use Entity\Form;

class LegalNoticeForm extends BaseForm {

	public Form $form;

	public function __construct() {
		$form_slug        = 'hjqs_legal_notice';
		$form_title       = __( 'Legal notices form', 'hjqs-legal-notice' );
		$form_description = __( "Fill out the form with information about your business and website, then insert the shortcode <code class='hjqs-shortcode' data-clipboard-text='[hjqs_ml]'>[hjqs_ml]</code> in the desired page.", 'hjqs-legal-notice' );

		$fields = [
			[
				'label'      => __( "Company legal entity", 'hjqs-legal-notice' ),
				'helper'     => __( "Indicate the legal form of your company, for example Limited liability company (LLC), Simplified joint-stock company (SAS), Sole proprietorship, etc.", 'hjqs-legal-notice' ),
				'type'       => 'datalist',
				"option_key" => "ln_fje",
				'choices'    => [
					'Entreprise Individuelle (EI)'                              => __( 'Entreprise Individuelle (EI)', 'hjqs-legal-notice' ),
					'Entreprise Individuelle à Responsabilité Limitée (EIRL)'   => __( 'Entreprise Individuelle à Responsabilité Limitée (EIRL)', 'hjqs-legal-notice' ),
					'Entreprise Unipersonnelle à Responsabilité Limitée (EURL)' => __( 'Entreprise Unipersonnelle à Responsabilité Limitée (EURL)', 'hjqs-legal-notice' ),
					'Société Civile Immobilière (SCI)'                          => __( 'Société Civile Immobilière (SCI)', 'hjqs-legal-notice' ),
					'Société Civile Professionnelle (SCP)'                      => __( 'Société Civile Professionnelle (SCP)', 'hjqs-legal-notice' ),
					'Société Civile de Moyens (SCM)'                            => __( 'Société Civile de Moyens (SCM)', 'hjqs-legal-notice' ),
					'Société A Responsabilité Limitée (SARL)'                   => __( 'Société A Responsabilité Limitée (SARL)', 'hjqs-legal-notice' ),
					'Société par Actions Simplifiée (SAS)'                      => __( 'Société par Actions Simplifiée (SAS)', 'hjqs-legal-notice' ),
					'Société par Actions Simplifiée Unipersonnelle (SASU)'      => __( 'Société par Actions Simplifiée Unipersonnelle (SASU)', 'hjqs-legal-notice' ),
				],
			],
			[
				'label'      => __( 'Site owner', 'hjqs-legal-notice' ),
				'helper'     => __( "Enter the name of the owner of the website, that is, the person or company responsible for it.", 'hjqs-legal-notice' ),
				"option_key" => "ln_ps",
				'type'       => 'text',
			],
			[
				'label'      => __( 'SIRET number', 'hjqs-legal-notice' ),
				'helper'     => __( "The SIRET number is a unique number assigned to each company by INSEE. It consists of 14 digits and allows precise identification.", 'hjqs-legal-notice' ),
				"option_key" => "ln_ns",
				'type'       => 'text',
			],
			[
				'label'      => __( "RCS registration number", 'hjqs-legal-notice' ),
				"option_key" => "ln_rcs",
				'helper'     => __( "If your company is registered with the RCS, you should have received a registration number upon registration. This number is unique and allows you to be accurately identified.", 'hjqs-legal-notice' ),
				'type'       => 'text',
			],
			[
				'label'      => __( "VAT identification number", 'hjqs-legal-notice' ),
				"option_key" => "ln_nitva",
				'helper'     => __( "If your company is subject to VAT, you should have received a VAT identification number upon registration. This number is unique and allows precise identification with the tax authorities.", 'hjqs-legal-notice' ),
				'type'       => 'text',
			],
			[
				'label'      => __( "Mailing address of the owner", 'hjqs-legal-notice' ),
				"option_key" => "ln_app",
				'helper'     => __( "Enter the mailing address of the owner of the website, i.e. the person or company responsible for it.", 'hjqs-legal-notice' ),
				'type'       => 'text',
			],
			[
				'label'      => __( "Name of the publishing manager", 'hjqs-legal-notice' ),
				"option_key" => "ln_nrp",
				'helper'     => __( "Enter the name of the person responsible for publishing the website, i.e. the person in charge of updating the content of the site and ensuring that it complies with current regulations.", 'hjqs-legal-notice' ),
				'type'       => 'text',
			],
			[
				'label'      => __( "Email or phone of the publishing manager", 'hjqs-legal-notice' ),
				"option_key" => "ln_etrp",
				'helper'     => __( "Enter the email address or phone number of the publishing manager so that users of the site can contact them if they have any questions or comments.", 'hjqs-legal-notice' ),
				'type'       => 'text',
			],
			[
				'label'      => __( "Name of the creator of the site", 'hjqs-legal-notice' ),
				"option_key" => "ln_ncs",
				'helper'     => __( "Enter the name of the person or company who created the website, i.e. who designed its design and content. If you have hired an agency or provider to create your site, you can indicate their name here.", 'hjqs-legal-notice' ),
				'type'       => 'text',
			],
			[
				'label'      => __( "Website of the creator of the site", 'hjqs-legal-notice' ),
				"option_key" => "ln_sics",
				'helper'     => __( "If the person or company who created your website has its own website, you can indicate its address here. This will allow users of your site to discover other creations of the creator and contact them directly if they wish.", 'hjqs-legal-notice' ),
				'type'       => 'text',
			],
			[
				'label'      => __( "Email or phone of the creator of the site", 'hjqs-legal-notice' ),
				"option_key" => "ln_etcs",
				'helper'     => __( "Indicate the email address or phone number of the creator of the site, so that users of your site can contact them if they have any questions or comments.", 'hjqs-legal-notice' ),
				'type'       => 'text',
			],
			[
				'label'      => __( "Site host", 'hjqs-legal-notice' ),
				"option_key" => "ln_hs",
				'helper'     => __( "Indicate the name of the host of your website and its postal address, i.e. the physical address of the company or organization that provides the servers and internet connections necessary to host your site. If your site is hosted on a dedicated server or shared server, you can indicate the name of the host and the address of its headquarters or offices.", 'hjqs-legal-notice' ),
				'type'       => 'radio',
				'choices'    => [
					'OVH (2 Rue Kellermann, 59100 Roubaix)'                             => 'OVH (2 Rue Kellermann, 59100 Roubaix)',
					'Gandi (63-65 Boulevard Masséna, 75013 Paris)'                      => 'Gandi (63-65 Boulevard Masséna, 75013 Paris)',
					'Ionos (7 Place de la Gare, 57200 Sarreguemines)'                   => 'Ionos (7 Place de la Gare, 57200 Sarreguemines)',
					'o2switch (222 Boulevard Gustave Flaubert, 63000 Clermont-Ferrand)' => 'o2switch (222 Boulevard Gustave Flaubert, 63000 Clermont-Ferrand)',
					'WPServeur (134 Avenue du président Wilson, 93100 Montreuil)'       => 'WPServeur (134 Avenue du président Wilson, 93100 Montreuil)',
					'custom'                                                            => [
						'label'            => __( "Custom", 'hjqs-legal-notice' ),
						"allow_to_add_new" => true,
						"type"             => "text",
						'option_key'       => 'ln_hs_bis'
					]

				],
			],
			[
				'label'            => __( "Base text", 'hjqs-legal-notice' ),
				"option_key"       => "ln_content",
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
<h2>1. Présentation du site</h2>
    <p>En vertu de l'article 6 de la loi n° 2004-575 du 21 juin 2004 pour la confiance dans l'économie numérique, il est précisé aux utilisateurs du site %%ln_ps%% l'identité des différents intervenants dans le cadre de sa réalisation et de son suivi :</p>
<ul>
	<li>Propriétaire du site : %%ln_ps%% - %%ln_fje%%</li>
	<li>Siège social : %%ln_app%%</li>
	<li>Numéro SIRET : %%ln_ns%%</li>
	<li>Numéro d'immatriculation RCS : %%ln_rcs%%</li>
	<li>Numéro identification TVA : %%ln_nitva%%</li>
	<li>Responsable publication : %%ln_nrp%% %%ln_etrp%%</li>
	<li>Créateur du site : %%ln_ncs%% %%ln_sics%%</li>
	<li>Hébergeur : %%ln_hs%%</li>
</ul>

<h2>2. Conditions générales d’utilisation du site et des services proposés</h2>
<p>L’utilisation du site %%ln_ps%% implique l’acceptation pleine et entière des conditions générales d’utilisation ci-après décrites. Ces conditions d’utilisation sont susceptibles d’être modifiées ou complétées à tout moment, les utilisateurs du site %%ln_ps%% sont donc invités à les consulter de manière régulière.</p>
<p>Ce site est normalement accessible à tout moment aux utilisateurs. Une interruption pour raison de maintenance technique peut être toutefois décidée par %%ln_ps%%, qui s’efforcera alors de communiquer préalablement aux utilisateurs les dates et heures de l’intervention.</p>
<p>Le site %%ln_ps%% est mis à jour régulièrement par %%ln_ps%%. De la même façon, les mentions légales peuvent être modifiées à tout moment : elles s’imposent néanmoins à l’utilisateur qui est invité à s’y référer le plus souvent possible afin d’en prendre connaissance.</p>

<h2>3. Description des services fournis</h2>
<p>Le site %%ln_ps%% a pour objet de fournir une information concernant l’ensemble des activités de la société.</p>
<p>%%ln_ps%% s’efforce de fournir sur le site %%ln_ps%% des informations aussi précises que possible. Toutefois, il ne pourra être tenu responsable des omissions, des inexactitudes et des carences dans la mise à jour, qu’elles soient de son fait ou du fait des tiers partenaires qui lui fournissent ces informations.</p>
<p>Tous les informations indiquées sur le site %%ln_ps%% sont données à titre indicatif, et sont susceptibles d’évoluer. Par ailleurs, les renseignements figurant sur le site %%ln_ps%% ne sont pas exhaustifs. Ils sont donnés sous réserve de modifications ayant été apportées depuis leur mise en ligne.</p>

<h2>4. Limitations contractuelles sur les données techniques</h2>
<p>Le site utilise la technologie JavaScript.</p>
<p>Le site Internet ne pourra être tenu responsable de dommages matériels liés à l’utilisation du site. De plus, l’utilisateur du site s’engage à accéder au site en utilisant un matériel récent, ne contenant pas de virus et avec un navigateur de dernière génération mis-à-jour</p>

<h2>5. Propriété intellectuelle et contrefaçons</h2>
<p>%%ln_ps%% est propriétaire des droits de propriété intellectuelle ou détient les droits d’usage sur tous les éléments accessibles sur le site, notamment les textes, images, graphismes, logo, icônes, sons, logiciels.</p>
<p>Toute reproduction, représentation, modification, publication, adaptation de tout ou partie des éléments du site, quel que soit le moyen ou le procédé utilisé, est interdite, sauf autorisation écrite préalable de : %%ln_ps%%.</p>
<p>Toute exploitation non autorisée du site ou de l’un quelconque des éléments qu’il contient sera considérée comme constitutive d’une contrefaçon et poursuivie conformément aux dispositions des articles L.335-2 et suivants du Code de Propriété Intellectuelle.</p>

<h2>6. Limitations de responsabilité</h2>
<p>%%ln_ps%% ne pourra être tenu responsable des dommages directs et indirects causés au matériel de l’utilisateur, lors de l’accès au site %%ln_ps%%, et résultant soit de l’utilisation d’un matériel ne répondant pas aux spécifications indiquées au point 4, soit de l’apparition d’un bug ou d’une incompatibilité.</p>
<p>%%ln_ps%% ne pourra également être tenue responsable des dommages indirects (tels par exemple qu’une perte de marché ou perte d’une chance) consécutifs à l’utilisation du site %%ln_ps%%.</p>
<p>Des espaces interactifs (possibilité de poser des questions dans l’espace contact) sont à la disposition des utilisateurs. %%ln_ps%% se réserve le droit de supprimer, sans mise en demeure préalable, tout contenu déposé dans cet espace qui contreviendrait à la législation applicable en France, en particulier aux dispositions relatives à la protection des données. Le cas échéant, %%ln_ps%% se réserve également la possibilité de mettre en cause la responsabilité civile et/ou pénale de l’utilisateur, notamment en cas de message à caractère raciste, injurieux, diffamant, ou pornographique, quel que soit le support utilisé (texte, photographie…).</p>

<h2>7. Gestion des données personnelles</h2>
<p>En France, les données personnelles sont notamment protégées par la loi n° 78-87 du 6 janvier 1978, la loi n° 2004-801 du 6 août 2004, l'article L. 226-13 du Code pénal et la Directive Européenne du 24 octobre 1995.</p>
<p>A l'occasion de l'utilisation du site %%ln_ps%%, peuvent êtres recueillies : l'URL des liens par l'intermédiaire desquels l'utilisateur a accédé au site %%ln_ps%%, le fournisseur d'accès de l'utilisateur, l'adresse de protocole Internet (IP) de l'utilisateur.</p>
<p>En tout état de cause %%ln_ps%% ne collecte des informations personnelles relatives à l'utilisateur que pour le besoin de certains services proposés par le site %%ln_ps%%. L'utilisateur fournit ces informations en toute connaissance de cause, notamment lorsqu'il procède par lui-même à leur saisie. Il est alors précisé à l'utilisateur du site %%ln_ps%% l’obligation ou non de fournir ces informations.</p>
<p>Conformément aux dispositions des articles 38 et suivants de la loi 78-17 du 6 janvier 1978 relative à l’informatique, aux fichiers et aux libertés, tout utilisateur dispose d’un droit d’accès, de rectification et d’opposition aux données personnelles le concernant, en effectuant sa demande écrite et signée, accompagnée d’une copie du titre d’identité avec signature du titulaire de la pièce, en précisant l’adresse à laquelle la réponse doit être envoyée.</p>
<p>Aucune information personnelle de l'utilisateur du site %%ln_ps%% n'est publiée à l'insu de l'utilisateur, échangée, transférée, cédée ou vendue sur un support quelconque à des tiers. Seule l'hypothèse du rachat de %%ln_ps%% et de ses droits permettrait la transmission des dites informations à l'éventuel acquéreur qui serait à son tour tenu de la même obligation de conservation et de modification des données vis à vis de l'utilisateur du site %%ln_ps%%.</p>
<p>Le site n'est pas déclaré à la CNIL car il n'exploite pas les données personnelles comme indiqué sur cette page</p>
<p>Les bases de données sont protégées par les dispositions de la loi du 1er juillet 1998 transposant la directive 96/9 du 11 mars 1996 relative à la protection juridique des bases de données.</p>

<h2>8. Liens hypertextes et cookies</h2>
<p>Le site %%ln_ps%% contient un certain nombre de liens hypertextes vers d’autres sites, mis en place avec l’autorisation de %%ln_ps%%. Cependant, %%ln_ps%% n’a pas la possibilité de vérifier le contenu des sites ainsi visités, et n’assumera en conséquence aucune responsabilité de ce fait.</p>
<p>La navigation sur le site %%ln_ps%% est susceptible de provoquer l’installation de cookie(s) sur l’ordinateur de l’utilisateur. Un cookie est un fichier de petite taille, qui ne permet pas l’identification de l’utilisateur, mais qui enregistre des informations relatives à la navigation d’un ordinateur sur un site. Les données ainsi obtenues visent à faciliter la navigation ultérieure sur le site, et ont également vocation à permettre diverses mesures de fréquentation.</p>
<p>Le refus d’installation d’un cookie peut entraîner l’impossibilité d’accéder à certains services. L’utilisateur peut toutefois configurer son ordinateur de la manière suivante, pour refuser l’installation des cookies :</p>
<ul>
	<li>Sous Internet Explorer : onglet outil (pictogramme en forme de rouage en haut a droite) / options internet. Cliquez sur Confidentialité et choisissez Bloquer tous les cookies. Validez sur Ok.</li>
	<li>Sous Firefox : en haut de la fenêtre du navigateur, cliquez sur le bouton Firefox, puis aller dans l'onglet Options. Cliquer sur l'onglet Vie privée. Paramétrez les Règles de conservation sur : utiliser les paramètres personnalisés pour l'historique. Enfin décochez-la pour désactiver les cookies.</li>
	<li>Sous Safari : Cliquez en haut à droite du navigateur sur le pictogramme de menu (symbolisé par un rouage). Sélectionnez Paramètres. Cliquez sur Afficher les paramètres avancés. Dans la section "Confidentialité", cliquez sur Paramètres de contenu. Dans la section "Cookies", vous pouvez bloquer les cookies.</li>
	<li>Sous Chrome : Cliquez en haut à droite du navigateur sur le pictogramme de menu (symbolisé par trois lignes horizontales). Sélectionnez Paramètres. Cliquez sur Afficher les paramètres avancés. Dans la section "Confidentialité", cliquez sur préférences. Dans l'onglet "Confidentialité", vous pouvez bloquer les cookies.</li>
</ul>

<h2>9. Droit applicable et attribution de juridiction</h2>
<p>Tout litige en relation avec l’utilisation du site .... est soumis au droit français. Il est fait attribution exclusive de juridiction aux tribunaux compétents de Paris.</p>

<h2>10. Les principales lois concernées</h2>
<p>Loi n° 78-17 du 6 janvier 1978, notamment modifiée par la loi n° 2004-801 du 6 août 2004 relative à l'informatique, aux fichiers et aux libertés.</p>
<p>Loi n° 2004-575 du 21 juin 2004 pour la confiance dans l'économie numérique.</p>

<h2>11. Lexique</h2>
<p>Utilisateur : Internaute se connectant, utilisant le site susnommé.</p>
<p>Informations personnelles : « les informations qui permettent, sous quelque forme que ce soit, directement ou non, l'identification des personnes physiques auxquelles elles s'appliquent » (article 4 de la loi n° 78-17 du 6 janvier 1978).</p>
HTML;

	}
}