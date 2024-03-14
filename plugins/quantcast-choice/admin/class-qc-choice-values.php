<?php
namespace QCChoice\Values;

/**
 * ToDo: Remove this entire class and it's functionality.  All CMP configuration is handled
 *       in the quantcast.com user account dashboard, and imported with the js tag.
 */

/**
 * Plugin default values.
 *
 * @link       http://www.quantcast.com
 * @since      1.0.0
 *
 * @package    QC_Choice
 * @subpackage QC_Choice/admin
 */

/**
 * QC Choice default values.
 *
 * @package    QC_Choice
 * @subpackage QC_Choice/admin
 * @author     Ryan Baron <rbaron@quantcast.com>
 */
class QC_Choice_Values {

	/**
	 * The Default value for QC Choice.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $default_values    Default values for QC Choice.
	 */
	private $default_values;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->default_values = array(
			'qc_choice_language'  => 'en',
			'qc_choice_auto_localize'  => 'auto-localize-language',
			'qc_choice_display_layout' => 'popup',
			'qc_choice_display_ui' => 'inEU',
			'qc_choice_publisher_name' => '',
			'qc_choice_publisher_logo' => '',
			'qc_choice_min_days_between_ui_displays' => 30,
			'qc_choice_non_consent_display_frequency' => 1,
			'qc_choice_google_personalisation' => 'true',
			'qc_choice_post_consent_page_default' => '',
			'qc_choice_initial_screen_no_option' => true,
			'qc_choice_initial_screen_body_text_option' => 1,
			'purpose_accessing_a_device' => 0,
			'purpose_advertising_personalisation' => 0,
			'purpose_analytics' => 0,
			'purpose_content_personalisation' => 0,
			'purpose_measurement' => 0,
			'qc_choice_initial_screen_title_text' => array(
				'lang' => array(
					'en' => __( "We value your privacy", "qc-choice" ),
					'fr' => __( "Le respect de votre vie privée est notre priorité", "qc-choice" ),
					'de' => __( "Wir respektieren Ihre Privatsphäre", "qc-choice" ),
					'it' => __( "Il rispetto della tua privacy è la nostra priorità", "qc-choice" ),
					'es' => __( "Tu privacidad es importante para nosotros", "qc-choice" ),
				),
			),
			'qc_choice_initial_screen_body_text' => array(
				'lang' => array(
					'en' => array(
						'1' => __( "We and our partners use technology such as cookies on our site to personalise content and ads, provide social media features, and analyse our traffic. Click below to consent to the use of this technology across the web. You can change your mind and change your consent choices at anytime by returning to this site.", "qc-choice" ),
						'2' => __( "We and our partners process your personal data using technology such as cookies in order to serve advertising, analyse our traffic and deliver customised experiences for you. You have choice in who uses your data and for what purposes and after setting your preferences may come back anytime to make changes.", "qc-choice" ),
						'3' => __( "The quality content and information we provide to you depends on the revenue we generate from advertising. We and our partners use your personal data in order to serve personalised advertising, measure activity on the site and deliver personalised features and content to you. Click below to consent to the use of your data. You can revisit your choices at any time.", "qc-choice" ),
					),
					'fr' => array(
						'1' => __( "Nos partenaires et nous-mêmes utilisent différentes technologies, telles que les cookies, pour personnaliser les contenus et les publicités, proposer des fonctionnalités sur les réseaux sociaux et analyser le trafic. Merci de cliquer sur le bouton ci-dessous pour donner votre accord. Vous pouvez changer d’avis et modifier vos choix à tout moment.", "qc-choice" ),
						'2' => __( "Nos partenaires et nous-mêmes traitons vos données personnelles afin de vous montrer de la publicité, comprendre notre audience et offrir une expérience utilisateur personnalisée. Vous pouvez choisir qui utilise vos données personnelles et dans quel but. Vous pouvez également faire le choix à tout moment de modifier vos réglages en matière de cookies.", "qc-choice" ),
						'3' => __( "La qualité du contenu et les informations que nous vous apportons dépend du revenue généré par la publicité. Nos partenaires et nous-mêmes recueillons et traitons vos données personnelles afin de proposer de la publicité personnalisée, analyser l’activité sur le site et offrir des fonctionnalités ainsi que du contenu personnalisé. Cliquez ci-dessous pour accepter l’utilisation des données. Vous pouvez faire le choix à tout moment de modifier vos réglages en matière de cookies.", "qc-choice" ),
					),
					'de' => array(
						'1' => __( "Wir und unsere Partner nutzen auf unserer Website Technologien wie beispielsweise Cookies, um Inhalte und Werbung zu personalisieren, Social-Media-Funktionen anzubieten und den Website-Traffic zu analysieren. Durch einen Klick auf die untenstehende Schaltfläche stimmen Sie dem Einsatz dieser Technologie im gesamten Internet zu. Sie können diese Einwilligung jederzeit überarbeiten oder zurücknehmen, indem Sie auf diese Website zurückkehren.", "qc-choice" ),
						'2' => __( "Wir und unsere Partner verarbeiten Ihre persönlichen Daten auf der Basis von Technologien wie Cookies, um Ihnen Werbung auszuspielen, den Traffic zu analysieren und personalisierte Inhalte anzuzeigen. Sie haben die Wahl, welche Partner Ihre Daten für welche Zwecke nutzen dürfen. Nach der Auswahl Ihrer Präferenzen können Sie diese jederzeit bearbeiten, indem Sie auf diese Website zurückkehren.", "qc-choice" ),
						'3' => __( "Die Inhalte und Informationen, die wir Ihnen zur Verfügung stellen, werden durch die Einnahmen  finanziert, die wir durch Werbung generieren. Wir und unsere Partner nutzen Ihre persönlichen Daten, um Ihnen personalisierte Werbung anzuzeigen, Aktivitäten auf unserer Website zu analysieren und Ihnen personalisierte Funktionalitäten sowie Inhalte anzubieten. Durch einen Klick auf die untenstehende Schaltfläche können Sie der Nutzung Ihrer Daten zustimmen. Sie haben jederzeit die Möglichkeit, Ihre Entscheidung zu ändern.", "qc-choice" ),
					),
					'it' => array(
						'1' => __( "Noi e i nostri partner utilizziamo, sul nostro sito, tecnologie come i cookie per personalizzare contenuti e annunci, fornire funzionalità per social media e analizzare il nostro traffico. Facendo clic di seguito si acconsente all'utilizzo di questa tecnologia. Puoi cambiare idea e modificare le tue scelte sul consenso in qualsiasi momento ritornando su questo sito.", "qc-choice" ),
						'2' => __( "Noi e i nostri partner processiamo i tuoi dati personali utilizzando tecnologie, come i cookie, per erogare pubblicità, analizzare il nostro traffico e fornire all'utente un'esperienza di navigazione personalizzata. Hai la possibilità di scegliere chi utilizza i tuoi dati e per quali scopi, dopo aver impostato le tue preferenze puoi tornare in qualsiasi momento per apportare modifiche.", "qc-choice" ),
						'3' => __( "La qualità del contenuto e delle informazioni che forniamo dipendono dai ricavi generati dalla pubblicità. Noi e i nostri partner raccogliamo e processiamo i tuoi dati personali per erogare pubblicità personalizzata, analizzare l'attività sul nostro sito e fornire funzionalità e contenuti personalizzati. Facendo clic di seguito si acconsente all'utilizzo dei tuoi dati. Puoi modificare le tue scelte in qualsiasi momento.", "qc-choice" ),
					),
					'es' => array(
						'1' => __( "Tanto nuestros partners como nosotros utilizamos cookies en nuestro sitio web para personalizar contenido y publicidad, proporcionar funcionalidades a las redes sociales, o analizar nuestro tráfico. Haciendo click consientes el uso de esta tecnologia en nuestra web. Puedes cambiar de opinion y personalizar tu consentimiento siempre que quieras volviendo a esta web.", "qc-choice" ),
						'2' => __( "Tanto nuestros partners como nosotros procesamos tu información personal como las cookies para hacer publicidad, analizar nuestro tráfico y proporcionar experiencias personalizadas en tu navegación. Tu tienes el control sobre quién utiliza tu información personal y para que propósitos. Una vez has configurado tus preferencias puedes volver siempre que quieras para realizar cualquier cambio.", "qc-choice" ),
						'3' => __( "El contenido de calidad e información que proporcionamos depende de los ingresos que generamos por publicidad. Tanto nuestros partners como nosotros utilizamos tu información personal (cookies) con el propósito de server publicidad personalizada, medir la actividad del sitio web y proporcionar contenido y funcionalidades personalizadas para ti. Haz click a continuación para aceptar el uso de tu información personal (cookies). Puedes revisar este consentimiento siempre que quieras.", "qc-choice" ),
					),
				),
			),
			'qc_choice_initial_screen_reject_button_text' => array(
				'lang' => array(
					'en' => __( "I do not accept", "qc-choice" ),
					'fr' => __( "Je refuse", "qc-choice" ),
					'de' => __( "Ablehnen", "qc-choice" ),
					'it' => __( "Non Accetto", "qc-choice" ),
					'es' => __( "No acepto", "qc-choice" ),
				),
			),
			'qc_choice_initial_screen_accept_button_text' => array(
				'lang' => array(
					'en' => __( "I accept", "qc-choice" ),
					'fr' => __( "J'accepte", "qc-choice" ),
					'de' => __( "Annehmen", "qc-choice" ),
					'it' => __( "Accetto", "qc-choice" ),
					'es' => __( "Acepto", "qc-choice" ),
				),
			),
			'qc_choice_initial_screen_purpose_link_text' => array(
				'lang' => array(
					'en' => __( "Show Purposes", "qc-choice" ),
					'fr' => __( "Afficher toutes les utilisations prévues", "qc-choice" ),
					'de' => __( "Nutzungszwecke anzeigen", "qc-choice" ),
					'it' => __( "Mostra tutte le finalità di utilizzo", "qc-choice" ),
					'es' => __( "Más información", "qc-choice" ),
				),
			),
			'qc_choice_purpose_screen_header_title_text' => array(
				'lang' => array(
					'en' => __( "Privacy Settings", "qc-choice" ),
					'fr' => __( "Paramètres de Gestion de la Confidentialité", "qc-choice" ),
					'de' => __( "Privatsphäre-Einstellungen", "qc-choice" ),
					'it' => __( "Impostazioni sulla privacy", "qc-choice" ),
					'es' => __( "Configuración de privacidad", "qc-choice" ),
				),
			),
			'qc_choice_purpose_screen_title_text' => array(
				'lang' => array(
					'en' => __( "We value your privacy", "qc-choice" ),
					'fr' => __( "Le respect de votre vie privée est notre priorité", "qc-choice" ),
					'de' => __( "Wir respektieren Ihre Privatsphäre", "qc-choice" ),
					'it' => __( "Wir respektieren Ihre Privatsphäre", "qc-choice" ),
					'es' => __( "Tu privacidad es importante para nosotros", "qc-choice" ),
				),
			),
			'qc_choice_purpose_screen_body_text' => array(
				'lang' => array(
					'en' => __( "You can set your consent preferences and determine how you want your data to be used based on the purposes below. You may set your preferences for us independently from those of third-party partners. Each purpose has a description so that you know how we and partners use your data.", "qc-choice" ),
					'fr' => __( "Vous pouvez configurer vos réglages et choisir comment vous souhaitez que vos données personnelles soient utilisée en fonction des objectifs ci-dessous. Vous pouvez configurer les réglages de manière indépendante pour chaque partenaire. Vous trouverez une description de chacun des objectifs sur la façon dont nos partenaires et nous-mêmes utilisons vos données personnelles.", "qc-choice" ),
					'de' => __( "Sie können Ihre bevorzugten Einwilligungseinstellungen festlegen und definieren, für welche der unten aufgeführen Zwecke Ihre Daten genutzt werden dürfen. Sie können die Einstellungen für uns unabhängig von den Einstellungen für die Drittleister festlegen. Jeder Nutzungszweck ist gesondert beschrieben, damit Sie sich ein Bild machen können, wie wir und unsere Partner Ihre Daten nutzen.", "qc-choice" ),
					'it' => __( "È possibile impostare le tue preferenze sul consenso e scegliere come i tuoi dati vengono utilizzati in relazione alle diverse finalità riportate di seguito. Inoltre, potrai configurare le impostazioni per il nostro sito indipendentemente da quelle per i nostri partner. Troverai una descrizione per ciasuna delle finalità di utilizzo, in modo che tu sia a conoscenza di come noi e i nostri partner utilizziamo i tuoi dati.", "qc-choice" ),
					'es' => __( "Puedes configurar tus preferencias y elegir como quieres que tus datos sean utilizados para los siguientes propósitos. Puedes elegir configurar tus preferencias solo con nosotros independientemente del resto de nuestros partners. Cada propósito tiene una descripción para que puedas saber como nosotros y nuestros partners utilizamos tus datos", "qc-choice" ),
				),
			),
			'qc_choice_purpose_screen_enable_all_button_text' => array(
				'lang' => array(
					'en' => __( "Enable all purposes", "qc-choice" ),
					'fr' => __( "Consentement à toutes les utilisations prévues", "qc-choice" ),
					'de' => __( "Alle Nutzungszwecke erlauben", "qc-choice" ),
					'it' => __( "Abilita consenso per tutti gli usi previsti", "qc-choice" ),
					'es' => __( "Habilitar todo", "qc-choice" ),
				),
			),
			'qc_choice_purpose_screen_vendor_link_text' => array(
				'lang' => array(
					'en' => __( "See full vendor list", "qc-choice" ),
					'fr' => __( "Afficher la liste complète des partenaires", "qc-choice" ),
					'de' => __( "Komplette Partnerliste ansehen", "qc-choice" ),
					'it' => __( "Visualizza la lista completa dei partner", "qc-choice" ),
					'es' => __( "Ver lista completa de partners", "qc-choice" ),
				),
			),
			'qc_choice_purpose_screen_cancel_button_text' => array(
				'lang' => array(
					'en' => __( "Cancel", "qc-choice" ),
					'fr' => __( "Annuler", "qc-choice" ),
					'de' => __( "Abbrechen", "qc-choice" ),
					'it' => __( "Annullare", "qc-choice" ),
					'es' => __( "Cancelar", "qc-choice" ),
				),
			),
			'qc_choice_purpose_screen_save_and_exit_button_text' => array(
				'lang' => array(
					'en' => __( "Save & Exit", "qc-choice" ),
					'fr' => __( "Enregistrer et quitter", "qc-choice" ),
					'de' => __( "Speichern & verlassen", "qc-choice" ),
					'it' => __( "Salva ed Esci", "qc-choice" ),
					'es' => __( "Guardar y salir", "qc-choice" ),
				),
			),
			'qc_choice_vendor_screen_title_text' => array(
				'lang' => array(
					'en' => __( "We value your privacy", "qc-choice" ),
					'fr' => __( "Le respect de votre vie privée est notre priorité", "qc-choice" ),
					'de' => __( "Wir respektieren Ihre Privatsphäre", "qc-choice" ),
					'it' => __( "Il rispetto della tua privacy è la nostra priorità", "qc-choice" ),
					'es' => __( "Tu privacidad es importante para nosotros", "qc-choice" ),
				),
			),
			'qc_choice_vendor_screen_body_text' => array(
				'lang' => array(
					'en' => __( "You can set consent preferences for each individual third-party company below. Expand each company list item to see what purposes they use data for to help make your choices. In some cases, companies may disclose that they use your data without asking for your consent, based on their legitimate interests. You can click on their privacy policies for more information and to opt out.", "qc-choice" ),
					'fr' => __( "Vous pouvez configurer vos réglages indépendamment pour chaque partenaire listé ci-dessous. Afin de faciliter votre décision, vous pouvez développer la liste de chaque entreprise pour voir à quelles fins il utilise les données. Dans certains cas, les entreprises peuvent révéler qu'elles utilisent vos données sans votre consentement, en fonction de leurs intérêts légitimes. Vous pouvez cliquer sur leurs politiques de confidentialité pour obtenir plus d'informations et pour vous désinscrire.", "qc-choice" ),
					'de' => __( "Sie können Ihre bevorzugten Einwilligungseinstellungen für jeden aufgeführten Partner individuell festlegen. Klappen Sie hierzu die Informationen der einzelnen Partnner aus, um Ihre Auswahl zu treffen und zu sehen, welche Daten diese Partner nutzen. In manchen Fällen können Unternehmen begründet durch ein berechtigtes Interesse angeben, Ihre persönlichen Daten zu nutzen, ohne Sie hierfür nach einer Einwilligung zu fragen. Sie können auf die Datenschutzrichtlinien der jeweiligen Unternehmen klicken, um weitere Informationen zu erhalten und ein Opt-Out zu aktivieren.", "qc-choice" ),
					'it' => __( "È possibile impostare le preferenze sul consenso per ogni singola società partner riportata di seguito. Per facilitare la tua decisione, puoi espandere l'elenco di ciascun partner e visualizzare per quali finalità utilizza i dati. In alcuni casi, le società possono affermare che utilizzano i tuoi dati senza chiedere il consenso, in quanto esiste un legittimo interesse. Puoi fare clic sulle loro politiche sulla privacy per ottenere maggiori informazioni e per revocare il consenso.", "qc-choice" ),
					'es' => __( "Puedes dar tu consentimiento de manera individual a cada partner. Ver la lista de todos los propósitos para los cuales utilizan tus datos para tener más información. En algunos casos, las empresas pueden revelar que usan tus datos sin pedir tu consentimiento, en función de intereses legítimos. Puedes hacer click en su política de privacidad para obtener más información al respecto o para rechazarlo.", "qc-choice" ),
				),
			),
			'qc_choice_vendor_screen_accept_all_button_text' => array(
				'lang' => array(
					'en' => __( "Accept all", "qc-choice" ),
					'fr' => __( "Tout Accepter", "qc-choice" ),
					'de' => __( "Alle akzeptieren", "qc-choice" ),
					'it' => __( "Accettare tutto ", "qc-choice" ),
					'es' => __( "Aceptar todo", "qc-choice" ),
				),
			),
			'qc_choice_vendor_screen_reject_all_button_text' => array(
				'lang' => array(
					'en' => __( "Reject All", "qc-choice" ),
					'fr' => __( "Tout Refuser", "qc-choice" ),
					'de' => __( "Alle ablehnen", "qc-choice" ),
					'it' => __( "Rifiutare tutto", "qc-choice" ),
					'es' => __( "Rechazar todo", "qc-choice" ),
				),
			),
			'qc_choice_vendor_screen_purposes_link_text' => array(
				'lang' => array(
					'en' => __( "Back to purposes", "qc-choice" ),
					'fr' => __( "Revenir aux Objectifs", "qc-choice" ),
					'de' => __( "Zurück zu Nutzungszwecken", "qc-choice" ),
					'it' => __( "Ritorna alle finalità di utilizzo", "qc-choice" ),
					'es' => __( "Volver a propósitos", "qc-choice" ),
				),
			),
			'qc_choice_vendor_screen_cancel_button_text' => array(
				'lang' => array(
					'en' => __( "Cancel", "qc-choice" ),
					'fr' => __( "Annuler", "qc-choice" ),
					'de' => __( "Abbrechen", "qc-choice" ),
					'it' => __( "Annullare", "qc-choice" ),
					'es' => __( "Cancelar", "qc-choice" ),
				),
			),
			'qc_choice_vendor_screen_save_and_exit_button_text' => array(
				'lang' => array(
					'en' => __( "Save & Exit", "qc-choice" ),
					'fr' => __( "Enregistrer et quitter", "qc-choice" ),
					'de' => __( "Speichern & verlassen", "qc-choice" ),
					'it' => __( "Salve ed Esci", "qc-choice" ),
					'es' => __( "Guardar y salir", "qc-choice" ),
				),
			),
		);

	}

	public function get_avaliable_languages() {
		return array(
			'en' => 'en',
			'fr' => 'fr',
			'de' => 'de',
			'it' => 'it',
			'es' => 'es',
		);
	}

	public function get_default_value( $field_name, $language_code = 'en' ) {

		$ret = "";

		if( isset( $this->default_values[$field_name] ) ) {

			if( isset( $this->default_values[$field_name]['lang'] ) && is_array( $this->default_values[$field_name]['lang'] ) ) {

				if( isset( $this->default_values[$field_name]['lang'][$language_code] ) ) {

					$ret = $this->default_values[$field_name]['lang'][$language_code];

				}

			}
			else {

				$ret = $this->default_values[$field_name];

			}
		}

		return $ret;
	}

	public function get_default_array_values( $field_name, $language_code = 'en' ) {

		$ret = "";

		if( isset( $this->default_values[$field_name] ) ) {

			if( isset( $this->default_values[$field_name]['lang'] ) && is_array( $this->default_values[$field_name]['lang'] ) ) {

				if( isset( $this->default_values[$field_name]['lang'][$language_code] ) ) {

					$ret = $this->default_values[$field_name]['lang'][$language_code];

				}

			}
			else {

				$ret = $this->default_values[$field_name];

			}
		}

		return $ret;
	}
}
