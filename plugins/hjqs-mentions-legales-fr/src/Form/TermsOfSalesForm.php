<?php

namespace Form;

use Entity\Form;

class TermsOfSalesForm extends BaseForm {

	public Form $form;

	public function __construct() {
		$form_slug        = 'hjqs_terms_of_sales';
		$form_title       = __( 'General terms and conditions form', 'hjqs-legal-notice' );
		$form_description = __( "Fill out the form with information about your business and website, then insert the shortcode <code class='hjqs-shortcode' data-clipboard-text='[hjqs_cgv]'>[hjqs_cgv]</code> in the desired page.", 'hjqs-legal-notice' );


		$fields     = [
			[
				'label'      => __( 'Site owner', 'hjqs-legal-notice' ),
				'helper'     => __( "Enter the name of the owner of the website, that is, the person or company responsible for it.", 'hjqs-legal-notice' ),
				"option_key" => "tos_ps",
				'type'       => 'text',
			],
			[
				'label'      => __( "Mailing address of the owner", 'hjqs-legal-notice' ),
				"option_key" => "tos_app",
				'helper'     => __( "Enter the mailing address of the owner of the website, i.e. the person or company responsible for it.", 'hjqs-legal-notice' ),
				'type'       => 'text',
			],
			[
				'label'      => __( "RCS registration number", 'hjqs-legal-notice' ),
				"option_key" => "tos_rcs",
				'helper'     => __( "If your company is registered with the RCS, you should have received a registration number upon registration. This number is unique and allows you to be accurately identified.", 'hjqs-legal-notice' ),
				'type'       => 'text',
			],
			[
				'label'      => __( "Name of the shop manager", 'hjqs-legal-notice' ),
				"option_key" => "tos_nrp",
				'helper'     => __( "Enter the name of the person responsible for shopping the website, i.e. the person in charge of updating the content of the site and ensuring that it complies with current regulations.", 'hjqs-legal-notice' ),
				'type'       => 'text',
			],
			[
				'label'      => __( "Email or phone of the shop manager", 'hjqs-legal-notice' ),
				"option_key" => "tos_etrp",
				'helper'     => __( "Enter the email address or phone number of the shopping manager so that users of the site can contact them if they have any questions or comments.", 'hjqs-legal-notice' ),
				'type'       => 'text',
			],
			[
				"label"      => __( "Shipping method", 'hjqs-legal-notice' ),
				"type"       => "checkbox",
				"helper"     => __( "The Shipping method field is used to specify the method by which the goods will be shipped to the customer.", 'hjqs-legal-notice' ),
				"option_key" => "tos_sm",
				"value"      => null,
				"choices"    => [
					"Livraison assurée par notre société" => __( "Delivery ensured by our company", 'hjqs-legal-notice' ),
					"Click & Collect"                     => __( "Local pickup", 'hjqs-legal-notice' ),
					"Colissimo"                           => "Colissimo",
					"DPD"                                 => "DPD",
					"Colis Privé"                         => "Colis Privé",
					"Chronopost"                          => "Chronopost",
					"Mondial Relay"                       => "Mondial Relay",
					"DHL"                                 => "DHL",
					"UPS"                                 => "UPS",
					"GLS"                                 => "GLS",
					'custom'                              => [
						'label'            => __( "Custom", 'hjqs-legal-notice' ),
						"allow_to_add_new" => true,
						"type"             => "text",
						'option_key'       => 'tos_sm_bis'
					]
				],
			],
			[
				"label"      => __( "Payment method", 'hjqs-legal-notice' ),
				"type"       => "checkbox",
				"helper"     => __( "The Payment Method field allows you to specify the types of payment that your business accepts. This can include options such as credit card, debit card, PayPal, and more.", 'hjqs-legal-notice' ),
				"option_key" => "tos_pm",
				"value"      => null,
				"choices"    => [
					"Paiement à la livraison" => __( "Payment upon delivery", 'hjqs-legal-notice' ),
					"Paiement par chèque"     => __( "Payment by check", 'hjqs-legal-notice' ),
					"Stripe"                  => "Stripe",
					"PayPal"                  => "PayPal",
					"Monetico"                => "Monetico (CIC / CM)",
					"Mercanet"                => "Mercanet (BNP)",
					"PayPlug"                 => "PayPlug (CE)",
					"SystemPay"               => "SystemPay",
					"Up2pay e-Transactions"   => "Up2pay e-Transactions (CA)",
					'custom'                  => [
						'label'            => __( "Custom", 'hjqs-legal-notice' ),
						"allow_to_add_new" => true,
						"type"             => "text",
						'option_key'       => 'tos_pm_bis'
					]
				],
			],
			[
				'label'      => __( "Return conditions", 'hjqs-legal-notice' ),
				"option_key" => "tos_rc",
				'helper'     => __( "Specify the conditions under which products can be returned. For example, within what timeframe are returns accepted and under what conditions?", 'hjqs-legal-notice' ),
				'type'       => 'textarea',
				'value' => null
			],
			[
				'label'      => __( "Refund conditions", 'hjqs-legal-notice' ),
				"option_key" => "tos_rpc",
				'helper'     => __( "Provide here the refund conditions applied by your company. This may include items such as: refund timeframe, return fees, shipping fees, etc.", 'hjqs-legal-notice' ),
				'type'       => 'textarea',
				'value' => null
			],
			[
				'label'      => __( "Cooling-off period", 'hjqs-legal-notice' ),
				"option_key" => "tos_cop",
				'helper'     => __( "The cooling-off period is the time frame during which a consumer can cancel their order and obtain a refund, without having to give a reason. This period depends on the type of product or service purchased. For example, for goods, the cooling-off period is generally 14 days, while for services, this period may vary depending on the service provided.", 'hjqs-legal-notice' ),
				'type'       => 'text',
				'value' => "14 jours"
			],
			[
				'label'            => __( "Base text", 'hjqs-legal-notice' ),
				"option_key"       => "tos_content",
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
<h2>1. Préambule</h2>
<p>En vertu de l'article 6 de la loi n° 2004-575 du 21 juin 2004 pour la confiance dans l'économie numérique, il est précisé aux utilisateurs du site %%tos_ps%% l'identité des différents intervenants dans le cadre de sa réalisation et de son suivi :</p>
<ul>
	<li>Propriétaire du site : %%tos_ps%%</li>
	<li>Siège social : %%tos_app%%</li>
	<li>Numéro d'immatriculation RCS : %%tos_rcs%%</li>
	<li>Responsable de la boutique : %%tos_nrp%% (%%tos_etrp%%)</li>
</ul>
<p>Ci-après dénommé le « Vendeur » ou la « Société » peut être jointe avec les informations suivantes : %%tos_etrp%%</p>
<p>La personne physique ou morale procédant à l’achat de produits ou services de la société, ci-après, « l’Acheteur », ou « le Client » à été exposé et convenu ce qui suit.</p>
<p>Le Vendeur est éditeur de Produits et Services de %%tos_ps%% à destination de consommateurs, commercialisés par l’intermédiaire de ses sites Internet. La liste et le descriptif des biens et services proposés par la Société peuvent être consultés sur les sites susmentionnés.</p>
<p>Les présentes Conditions Générales de Vente déterminent les droits et obligations des parties dans le cadre de la vente en ligne de Produits ou Services proposés par le Vendeur.</p>

<h2>2. Dispositions générales</h2>
<p>Les présentes Conditions Générales de Vente (CGV) régissent les ventes de Produits ou de Services, effectuées au travers des sites Internet de la Société, et sont partie intégrante du Contrat entre l’Acheteur et le Vendeur. Elle sont pleinement opposable à l'Acheteur qui les a accepté avant de passer commande.</p>
<p>Le Vendeur se réserve la possibilité de modifier les présentes, à tout moment par la publication d’une nouvelle version sur son site Internet. Les CGV applicables alors sont celles étant en vigueur à la date du paiement (ou du premier paiement en cas de paiements multiples) de la commande.</p>
<p>La Société s’assure également que leur acceptation soit claire et sans réserve en mettant en place une case à cocher et un clic de validation. Le Client déclare avoir pris connaissance de l’ensemble des présentes Conditions Générales de Vente, et le cas échéant des Conditions Particulières de Vente liées à un produit ou à un service, et les accepter sans restriction ni réserve.</p>
<p>Le Client reconnaît qu’il a bénéficié des conseils et informations nécessaires afin de s’assurer de l’adéquation de l’offre à ses besoins.</p>
<p>Le Client déclare être en mesure de contracter légalement en vertu des lois françaises ou valablement représenter la personne physique ou morale pour laquelle il s’engage. Sauf preuve contraire les informations enregistrées par la Société constituent la preuve de l’ensemble des transactions.</p>

<h2>3. Prix</h2>
<p>Les prix des produits vendus au travers des sites Internet sont indiqués en Euros hors taxes (HT) et/ou toutes taxes comprises (TTC) et précisément déterminés sur les pages de descriptifs des Produits. Ils sont également indiqués en euros toutes taxes comprises (TVA + autres taxes éventuelles) sur la page de commande des produits, et hors frais spécifiques d'expédition.</p>
<p>Pour tous les produits expédiés hors Union européenne et/ou DOM-TOM, le prix est calculé hors taxes automatiquement sur la facture. Des droits de douane ou autres taxes locales ou droits d'importation ou taxes d'état sont susceptibles d'être exigibles dans certains cas. Ces droits et sommes ne relèvent pas du ressort du Vendeur. Ils seront à la charge de l'acheteur et relèvent de sa responsabilité (déclarations, paiement aux autorités compétentes, etc.). Le Vendeur invite à ce titre l'acheteur à se renseigner sur ces aspects auprès des autorités locales correspondantes.</p>
<p>La Société se réserve la possibilité de modifier ses prix à tout moment pour l’avenir. Les frais de télécommunication nécessaires à l’accès aux sites Internet de la Société sont à la charge du Client. Le cas échéant également, les frais de livraison.</p>

<h2>4. Conclusion du contrat en ligne</h2>
<p>Conformément aux dispositions de l'article 1127-1 du Code civil, le Client doit suivre une série d’étapes pour conclure le contrat par voie électronique pour pouvoir réaliser sa commande :</p>
<ul>
	<li>Information sur les caractéristiques essentielles du Produit</li>
	<li>Choix du Produit</li>
	<li>Indication des coordonnées essentielles du Client (identification, email, adresse, etc.)</li>
	<li>Acceptation des présentes Conditions Générales de Vente</li>
	<li>Vérification des éléments de la commande (formalité du double clic) et, le cas échéant, correction des erreurs. Avant de procéder à sa confirmation, l'Acheteur a la possibilité de vérifier le détail de sa commande, son prix, et de corriger ses éventuelles erreur, ou annuler sa commande. La confirmation de la commande emportera formation du présent contrat.</li>
	<li>Vérification des instructions pour le paiement, paiement des produits, puis livraison de la commande. Le Client recevra confirmation par courrier électronique du paiement de la commande, ainsi qu’un accusé de réception de la commande la confirmant.</li>
</ul>
<p>Le client disposera pendant son processus de commande de la possibilité d'identifier d'éventuelles erreurs commises dans la saisie des données et de les corriger. La langue proposé pour la conclusion du contrat est la langue française.</p>
<p>Les modalités de l'offre et des conditions générales de vente sont renvoyées par email à l'acheteur lors de la commande et archivées sur le site web du Vendeur. Le cas échéant, les règles professionnelles et commerciales auxquelles l'auteur de l'offre entend se soumettre sont consultables dans la rubrique "règles annexes" des présentes CGV, consultables sur le site du Vendeur.</p>
<p>L'archivage des communications, de la commande, des détails de la commande, ainsi que des factures est effectué sur un support fiable et durable de manière constituer une copie fidèle et durable conformément aux dispositions de l'article 1360 du code civil. Ces informations peuvent être produits à titre de preuve du contrat.</p>
<p>Pour les produits livrés, la livraison se fera à l’adresse indiquée par le Client. Aux fins de bonne réalisation de la commande, le Client s’engage à fournir ses éléments d’identification véridiques.</p>
<p>Le Vendeur se réserve la possibilité de refuser la commande, par exemple pour toute demande anormale, réalisée de mauvaise foi ou pour tout motif légitime.</p>

<h2>5. Produits et services</h2>
<p>Les caractéristiques essentielles des biens, des services et leurs prix respectifs sont mis à disposition de l’acheteur sur les sites Internet de la société, de même, le cas échéant, que le mode d'utilisation du produit. Conformément à l'article L112-1 du Code la consommation, le consommateur est informé, par voie de marquage, d'étiquetage, d'affichage ou par tout autre procédé approprié, des prix et des conditions particulières de la vente et de l'exécution des services avant toute conclusion du contrat de vente. Dans tous les cas, le montant total dû par l'Acheteur est indiqué sur la page de confirmation de la commande. Le prix de vente du produit est celui en vigueur indiqué au jour de la commande, celui-ci ne comportant par les frais de ports facturés en supplément. Ces éventuels frais sont indiqués à l'Achteur lors du process de vente, et en tout état de cause au moment de la confirmation de la commande. Le Vendeur se réserve la possibilité de modifier ses prix à tout moment, tout en garantissant l'application du prix indiqué au moment de la commande.</p>
<p>Lorsque les produits ou services ne sont pas exécuté immédiatement, une information claire est donnée sur la page de présentation du produit quant aux dates de livraison des produits ou services. Le client atteste avoir reçu un détail des frais de livraison ainsi que les modalités de paiement, de livraison et d’exécution du contrat, ainsi qu'une information détaillée relative à l'identité du vendeur, ses coordonnées postales, téléphoniques et électroniques, et à ses activités dans le contexte de la présente vente. Le Vendeur s’engage à honorer la commande du Client dans la limite des stocks de Produits disponibles uniquement. A défaut, le Vendeur en informe le Client ; si la commande a été passée, et à défaut d'accord avec le Client sur une nouvelle date de livraison, le Vendeur rembourse le client.</p>
<p>Les informations contractuelles sont présentées en détail et en langue française. Les parties conviennent que les illustrations ou photos des produits offerts à la vente n’ont pas de valeur contractuelle. La durée de validité de l’offre des Produits ainsi que leurs prix est précisée sur les sites Internet de la Société, ainsi que la durée minimale des contrats proposés lorsque ceux-ci portent sur une fourniture continue ou périodique de produits ou services. Sauf conditions particulières, les droits concédés au titre des présentes le sont uniquement à la personne physique signataire de la commande (ou la personne titulaire de l’adresse email communiqué).</p>

<h2>6. Conformité</h2>
<p>Conformément à l'article L.411-1 du Code de la consommation, les produits et les services offert à la vente au travers des présentes CGV répondent aux prescriptions en vigueur relatives à la sécurité et à la santé des personnes, à la loyauté des transactions commerciales et à la protection des consommateurs. Indépendamment de toute garantie commerciale, le Vendeur reste tenu des défauts de conformité et des vices cachés du produit.</p>
<p>Conformément à l'article L.217-4, le vendeur livre un bien conforme au contrat et répond des défauts de conformité existant lors de la délivrance. Il répond également des défauts de conformité résultant de l'emballage, des instructions de montage ou de l'installation lorsque celleci a été mise à sa charge par le contrat ou a été réalisée sous sa responsabilité.</p>
<p>Conformément aux dispositions légales en matière de conformité et de vices cachés (art. 1641 c. civ.), le Vendeur rembourse ou échange les produits défectueux ou ne correspondant pas à la commande.</p>

<h2>7. Remboursement</h2>
<p>Le remboursement peut être demandé de la manière suivante : %%tos_rpc%%</p>

<h2>8. Clause de réserve de propriété</h2>
<p>Les produits demeurent la propriété de la Société jusqu’au complet paiement du prix.</p>

<h2>9. Modalités de livraison</h2>
<p>Les produits sont livrés à l'adresse de livraison qui a été indiquée lors de la commande et dans les délais indiqués. Ces délais ne prenent pas en compte le délai de préparation de la commande.</p>
<p>Lorsque le Client commande plusieurs produits en même temps ceux-ci peuvent avoir des délais de livraison différents.</p>
<p>En cas de retard de livraison, le Client dispose de la possibilité de résoudre le contrat dans les conditions et modalités définies à l’Article L 138-2 du Code de la consommation. Le Vendeur procède alors au remboursement du produit et aux frais « aller » dans les conditions de l’Article L 138-3 du Code de la consommation. Le Vendeur met à disposition un point de contact téléphonique (coût d’une communication locale à partir d’un poste fixe) indiqué dans l’email de confirmation de commande afin d'assurer le suivi de la commande.</p>
<p>Le Vendeur rappelle qu’au moment où le Client prend possession physiquement des produits, les risques de perte ou d’endommagement des produits lui sont transférés. Il appartient au Client de notifier au transporteur toute réserves sur le produit livré.</p>

<h2>10. Disponibilité et présentation</h2>
<p>En cas d’indisponibilité d’un article pour une période supérieure à 12 jours ouvrables, vous serez immédiatement prévenu des délais prévisibles de livraison et la commande de cet article pourra être annulée sur simple demande. Le Client pourra alors demander un avoir pour le montant de l’article ou son remboursement intégral et l'annulation de la commande.</p>

<h2>11. Paiement</h2>
<p>Le paiement est exigible immédiatement à la commande, y compris pour les produits en précommande. Le Client peut effectuer le règlement par carte de paiement ou chèque bancaire. Les cartes émises par des banques domiciliées hors de France doivent obligatoirement être des cartes bancaires internationales (Mastercard ou Visa). Le paiement sécurisé en ligne par carte bancaire est réalisé par nos prestataires de paiement. Les informations transmises sont chiffrées dans les règles de l’art et ne peuvent être lues au cours du transport sur le réseau.</p>
<p>Nos préstataires de paiement : %%tos_pm%%</p>
<p>Une fois le paiement lancé par le Client, la transaction est immédiatement débitée après vérification des informations. Conformément aux dispositions du Code monétaire et financier, l’engagement de payer donné par carte est irrévocable. En communiquant ses informations bancaires lors de la vente, le Client autorise le Vendeur à débiter sa carte du montant relatif au prix indiqué. Le Client confirme qu’il est bien le titulaire légal de la carte à débiter et qu’il est légalement en droit d’en faire usage. En cas d’erreur, ou d’impossibilité de débiter la carte, la Vente est immédiatement résolue de plein droit et la commande annulée.</p>

<h2>12. Délai de rétractation</h2>
<p>Conformément aux dispositions de l'article L 221-5 du Code de la consommation, l'Acheteur dispose du droit de se rétracter sans donner de motif, dans un délai de quatorze (14) jours à la date de réception de sa commande.</p>
<p>Le droit de rétractation peut être exercé en contactant la Société de la manière suivante : %%tos_etrp%%. Nous informons les Clients que conformément aux dispositions des articles L. 221-18 à L. 221-28 du Code de la consommation, ce droit de rétractation ne peut être exercé pour les Produits téléchargeables.</p>
<p>En cas d’exercice du droit de rétractation dans le délai susmentionné, le prix du ou des produits achetés et les frais d’envoi seront remboursés, les frais de retour restant à la charge du Client.</p>
<p>Les retours des produits sont à effectuer dans leur état d'origine et complets (emballage, accessoires, notice, etc.)</p>
<p>Les retours doivent si possible être accompagnés d’une copie du justificatif d'achat.</p>

<p>Ce droit de rétractation ne peut être exercé pour certains produits spécifiques. Une information claire est donnée sur la page de présentation de chaque produit. L'acheteur dispose alors du droit de se rétracter dans un délai de %%tos_cop%% à partir de la date de réception de sa commande.</p>
<p>Conditions de retour : %%tos_rc%%</p>

<h2>13. Garanties</h2>
<p>Conformément à la loi, le Vendeur assume les garanties suivantes : de conformité et relative aux vices cachés des produits. Le Vendeur rembourse l'acheteur ou échange les produits défectueux ou ne correspondant pas à la commande effectuée conformément aux conditions de remboursement.</p>
<p>Le Vendeur rappelle que le consommateur :</p>
<ul>
	<li>Dispose d'un délai de 2 ans à compter de la délivrance du bien pour agir auprès du Vendeur.</li>
	<li>Qu'il peut choisir entre le remplacement et la réparation du bien sous réserve des conditions prévues par les dispositions susmentionnées. (apparemment défectueux ou ne correspondant).</li>
	<li>Qu'il est dispensé d'apporter la preuve l’existence du défaut de conformité du bien durant les six mois suivant la délivrance du bien.</li>
	<li>Que, sauf biens d’occasion, ce délai sera porté à 24 mois à compter du 18 mars 2016</li>
	<li>Que le consommateur peut également faire valoir la garantie contre les vices cachés de la chose vendue au sens de l’article 1641 du code civil et, dans cette hypothèse, il peut choisir entre la résolution de la vente ou une réduction du prix de vente (dispositions des articles 1644 du Code Civil).</li>
</ul>

<h2>14. Réclamations et médiation</h2>
<p>Le cas échéant, l’Acheteur peut présenter toute réclamation en contactant la société au moyen des coordonnées suivantes :</p>
<ul>
	<li>Par email : %%tos_etrp%%</li>
	<li>Par voie postale : %%tos_app%%</li>
</ul>
<p>Conformément aux dispositions des art. L. 611-1 à L. 616-3 du Code de la consommation, le consommateur est informé qu'il peut recourir à un médiateur de la consommation dans les conditions prévues par le titre Ier du livre VI du code de la consommation.</p>
<p>En cas d'échec de la demande de réclamation auprès du service client du Vendeur, ou en l'absence de réponse dans un délai de deux mois, le consommateur peut soumettre le différent à un médiateur qui tentera en toute indépendance de rapprocher les parties en vue d'obtenir une solution amiable.</p>

<h2>15. Résolution du contrat</h2>
<p>La commande peut être résolue par l'acheteur par lettre recommandée avec demande d'avis de réception dans les cas suivants :</p>
<ul>
	<li>Livraison d'un produit non conforme aux caractéristiques de la commande.</li>
	<li>Livraison dépassant la date limite fixée lors de la commande ou, à défaut de date, dans les trente jours suivant le paiement.</li>
	<li>De hausse du prix injustifiée ou de modification du produit. Dans ces cas, l'acheteur peut exiger le remboursement de l'acompte versé majoré des intérêts calculés au taux légal à partir de la date d'encaissement de l'acompte.</li>
</ul>

<h2>16. Droits de propriété intellectuelle</h2>
<p>Les marques, noms de domaines, produits, logiciels, images, vidéos, textes ou plus généralement toute information objet de droits de propriété intellectuelle sont et restent la propriété exclusive du vendeur. Aucune cession de droits de propriété intellectuelle n’est réalisée au travers des présentes CGV. Toute reproduction totale ou partielle, modification ou utilisation de ces biens pour quelque motif que ce soit est strictement interdite.</p>

<h2>17. Force majeure</h2>
<p>L’exécution des obligations du vendeur au terme des présentes est suspendue en cas de survenance d’un cas fortuit ou de force majeure qui en empêcherait l’exécution. Le vendeur avisera le client de la survenance d’un tel évènement dès que possible.</p>

<h2>18. Nullité et modification du contrat</h2>
<p>Si l’une des stipulations du présent contrat était annulée, cette nullité n’entraînerait pas la nullité des autres stipulations qui demeureront en vigueur entre les parties. Toute modification contractuelle n’est valable qu’après un accord écrit et signé des parties.</p>

<h2>19. Protection des données personnelles</h2>
<p>Les informations demandées lors de la commande sont nécessaires à l'établissement de la facture (obligation légale) et la livraison des biens commandés, sans quoi la commande ne pourras pas être passée. Aucune décision automatisée ou profilage n'est mis en oeuvre au travers du processus de commande.</p>
<p>Notre politique de confidentialité est disponible en détails sur notre site internet.</p>

<h2>20. Droit applicable et clauses</h2>
<p>Toutes les clauses figurant dans les présentes conditions générales de vente, ainsi que toutes les opérations d’achat et de vente qui y sont visées, seront soumises au droit français.</p>
<p>La nullité d'une clause contractuelle n'entraîne pas la nullité des présentes conditions générales de vente.</p>

<h2>21. Information des consommateurs</h2>
<p>Aux fins d'information des consommateurs, les dispositions du code civil et du code de la consommation sont reproduites ci-après :</p>
<ul>
	<li>Aricle 1641 du Code civil : Le vendeur est tenu de la garantie à raison des défauts cachés de la chose vendue qui la rendent impropre à l'usage auquel on la destine, ou qui diminuent tellement cet usage que l'acheteur ne l'aurait pas acquise, ou n'en aurait donné qu'un moindre prix, s'il les avait connus.</li>
	<li>Aricle 1648 du Code civil : L'action résultant des vices rédhibitoires doit être intentée par l'acquéreur dans un délai de deux ans à compter de la découverte du vice. Dans le cas prévu par l'article 1642-1, l'action doit être introduite, à peine de forclusion, dans l'année qui suit la date à laquelle le vendeur peut être déchargé des vices ou des défauts de conformité apparents.</li>
	<li>Article L. 217-5 du Code de la consommation : Le bien est conforme au contrat : 1° S'il est propre à l'usage habituellement attendu d'un bien semblable et, le cas échéant : - s'il correspond à la description donnée par le vendeur et possède les qualités que celui-ci a présentées à l'acheteur sous forme d'échantillon ou de modèle ; - s'il présente les qualités qu'un acheteur peut légitimement attendre eu égard aux déclarations publiques faites par le vendeur, par le producteur ou par son représentant, notamment dans la publicité ou l'étiquetage ; 2° Ou s'il présente les caractéristiques définies d'un commun accord par les parties ou est propre à tout usage spécial  recherché par l'acheteur, porté à la connaissance du vendeur et que ce dernier a accepté.</li>
	<li>Article L. 217-12 du Code de la consommation : L'action résultant du défaut de conformité se prescrit par deux ans à compter de la délivrance du bien.</li>
	<li>Article L. 217-16 du Code de la consommation : Lorsque l'acheteur demande au vendeur, pendant le cours de la garantie commerciale qui lui a été consentie lors de l'acquisition ou de la réparation d'un bien meuble, une remise en état couverte par la garantie, toute période d'immobilisation d'au moins sept jours vient s'ajouter à la durée de la garantie qui restait à courir. Cette période court à compter de la demande d'intervention de l'acheteur ou de la mise à disposition pour réparation du bien en cause, si cette mise à disposition est postérieure à la demande d'intervention.</li>
</ul>
HTML;
	}

}