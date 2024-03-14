<?php
/*
Plugin Name:  DSGVO DE Plugin
Plugin URI:   https://billing.woolink.de/cart.php
Description:  DSGVO konforme Cookie Hinweise, Datenschutzerklärung, Google Analytics und Google Fonts Lösung
Version:      1.9
Author:       woolinks by bytecity
Author URI:   https://www.bytecity.de
License:      AGPLv3
License URI:  https://www.gnu.org/licenses/agpl-3.0
Text Domain:  bc-dsgvo
Domain Path:  /languages
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_shortcode( 'bc_dsgvo_dse', 'bc_dsgvo_inject_dse' );
add_shortcode( 'bc_dsgvo_imprint', 'bc_dsgvo_inject_imprint' );

if(get_option( "bc_dsgvo_proxy_enabled" ))
{
    function bc_dsgvo_replace_google_fonts_urls( $src )
    {
	/*
	This code is an ofuscated routine to avoid unlawful use of our proxy service.
	It generates the URL for your personal Service access and replaces the google fonts api calls with fonts.woolink.de
	This function DOES NOT call back home or submits any data from your site, it only replaces URLs in your html output.
	*/
	${"\x47LO\x42\x41\x4c\x53"}["\x68\x70d\x67p\x62rb"]="\x66\x69\x6ct\x65\x72\x65\x64_\x75\x72\x6c";
	$pzmojhwu="f\x69\x6c\x74\x65\x72\x65d\x5f\x75\x72\x6c";
	${"\x47LO\x42\x41L\x53"}["\x6f\x75\x6eou\x76\x77\x77x\x6fh\x73"]="\x73\x72c";${"\x47\x4c\x4fBAL\x53"}["g\x79f\x63\x6d\x75\x79gg"]="f\x69\x6c\x74\x65r\x65d\x5f\x75\x72\x6c";
	${${"\x47\x4cOB\x41\x4c\x53"}["\x67\x79\x66\x63\x6d\x75\x79\x67g"]}=${${"\x47\x4c\x4fBA\x4c\x53"}["\x6f\x75no\x75vw\x77\x78\x6f\x68\x73"]};
	$ymdzzkbnslm="f\x69\x6c\x74er\x65\x64_\x75\x72l";
	if(strpos(${${"GLO\x42A\x4cS"}["\x68\x70\x64gp\x62r\x62"]},"f\x6f\x6e\x74s.g\x6f\x6fg\x6c\x65\x61pis\x2eco\x6d")!==false)
	{
	    $bplfreczbyp="\x66il\x74ere\x64_\x75r\x6c";
	    ${${"\x47\x4c\x4f\x42A\x4c\x53"}["\x68\x70d\x67p\x62\x72\x62"]}=${$bplfreczbyp}."\x26\x74\x6f\x6ben=".sha1($_SERVER["HTT\x50_\x48\x4f\x53\x54"])."\x26s\x69te\x3d".md5($_SERVER["HT\x54\x50\x5f\x48\x4fST"]);
	}
	${$ymdzzkbnslm}=apply_filters("b\x63_\x64\x73gvo\x5fr\x65\x70\x6c\x61ce\x5fgo\x6f\x67le\x5ff\x6f\x6ets\x5fu\x72l\x73","\x5f_r\x65\x74u\x72n_\x74\x72ue")?preg_replace("(f\x6fn\x74\x73\x2eg\x6fo\x67l\x65a\x70\x69\x73\x2e\x63o\x6d)","\x66onts\x2ew\x6fol\x69\x6e\x6b.d\x65",${${"\x47L\x4f\x42\x41\x4c\x53"}["\x68\x70\x64\x67\x70\x62r\x62"]}):${${"\x47\x4cO\x42\x41LS"}["\x68p\x64\x67\x70\x62\x72\x62"]};
	return${$pzmojhwu};
    }
            
    add_filter( 'script_loader_src', 'bc_dsgvo_replace_google_fonts_urls', 100, 1 );
    add_filter( 'style_loader_src', 'bc_dsgvo_replace_google_fonts_urls', 100, 1 );
    add_filter( 'template_directory_uri', 'bc_dsgvo_replace_google_fonts_urls', 100, 1 );
    add_filter( 'stylesheet_directory_uri', 'bc_dsgvo_replace_google_fonts_urls', 100, 1 );
}

function bc_dsgvo_inject_imprint()
{
    ?>
    <h2><?php _e('Impressum', 'bc_dsgvo' ); ?></h2>
    <h3><?php _e('Betreiber und verantwortlich im Sinne des Telemediengesetzes:', 'bc_dsgvo' ); ?></h3>
    <table>
	<?php if(get_option( "bc_dsgvo_imprint_company" )){?><tr><td style="width: 200px;"><?php _e('Firma', 'bc_dsgvo' ); ?>:</td><td><b><?=get_option( "bc_dsgvo_imprint_company" )?></b></td></tr><?php } ?>
	<tr><td style="width: 200px;"></td><td><?=get_option( "bc_dsgvo_imprint_address" )?></td></tr>
	<tr><td style="width: 200px;"></td><td><br><?=get_option( "bc_dsgvo_imprint_zip" )?> <?=get_option( "bc_dsgvo_imprint_city" )?></td></tr>
    </table>
    <table>
	<?php if(get_option( "bc_dsgvo_imprint_ceo" )){?><tr><td style="width: 200px;"><?php _e('Geschäftsführer', 'bc_dsgvo' ); ?>:</td><td><?=get_option( "bc_dsgvo_imprint_ceo" )?></td></tr><?php } ?>
	<?php if(get_option( "bc_dsgvo_imprint_court" )){?><tr><td style="width: 200px;"><?php _e('Registergericht', 'bc_dsgvo' ); ?>:</td><td><?=get_option( "bc_dsgvo_imprint_court" )?></td></tr><?php } ?>
	<?php if(get_option( "bc_dsgvo_imprint_courtnumber" )){?><tr><td style="width: 200px;"><?php _e('Registernummer', 'bc_dsgvo' ); ?>:</td><td><?=get_option( "bc_dsgvo_imprint_courtnumber" )?></td></tr><?php } ?>
	<?php if(get_option( "bc_dsgvo_imprint_taxid" )){?><tr><td style="width: 200px;"><?php _e('Ust-ID', 'bc_dsgvo' ); ?>:</td><td><?=get_option( "bc_dsgvo_imprint_taxid" )?></td></tr><?php } ?>
    </table>
    <h3><?php _e('Kontakt', 'bc_dsgvo' ); ?></h3>
    <table>
	<?php if(get_option( "bc_dsgvo_imprint_phone" )){?><tr><td style="width: 200px;"><?php _e('Telefon', 'bc_dsgvo' ); ?>:</td><td><?=get_option( "bc_dsgvo_imprint_phone" )?></td></tr><?php } ?>
	<?php if(get_option( "bc_dsgvo_imprint_fax" )){?><tr><td style="width: 200px;"><?php _e('Telefax', 'bc_dsgvo' ); ?>:</td><td><?=get_option( "bc_dsgvo_imprint_fax" )?></td></tr><?php } ?>
	<?php if(get_option( "bc_dsgvo_imprint_email" )){?><tr><td style="width: 200px;"><?php _e('E-Mail', 'bc_dsgvo' ); ?>:</td><td><a href="mailto:<?=get_option( "bc_dsgvo_imprint_email" )?>"><?=get_option( "bc_dsgvo_imprint_email" )?></a></td></tr><?php } ?>
    </table>
    <?php if(get_option( "bc_dsgvo_imprint_supervisor" ) || get_option( "bc_dsgvo_imprint_chamber" ) || get_option( "bc_dsgvo_imprint_origin" )){?>
    <h3><?php _e('Behördliche Zulassungen', 'bc_dsgvo' ); ?><h3>
    <table>
	<?php if(get_option( "bc_dsgvo_imprint_supervisor" )){?><tr><td style="width: 200px;"><?php _e('Aufsichtsbehörde', 'bc_dsgvo' ); ?>:</td><td><?=get_option( "bc_dsgvo_imprint_supervisor" )?></td></tr><?php } ?>
	<?php if(get_option( "bc_dsgvo_imprint_chamber" )){?><tr><td style="width: 200px;"><?php _e('Kammer', 'bc_dsgvo' ); ?>:</td><td><?=get_option( "bc_dsgvo_imprint_chamber" )?></td></tr><?php } ?>
	<?php if(get_option( "bc_dsgvo_imprint_origin" )){?><tr><td style="width: 200px;"><?php _e('Berufsbezeichnung & Verleihungsland', 'bc_dsgvo' ); ?>:</td><td><?=get_option( "bc_dsgvo_imprint_origin" )?></td></tr><?php } ?>
    </table>
    <?php } ?>
    <?php if(get_option( "bc_dsgvo_imprint_other" )){?>
    <h3><?php _e('Weitere Informationen', 'bc_dsgvo' ); ?></h3>
    <table>
	<?php if(get_option( "bc_dsgvo_imprint_other" )){?><tr><td style="width: 200px;"></td><td><?=get_option( "bc_dsgvo_imprint_other" )?></td></tr><?php } ?>
    </table>
    <?php } ?>
    <?php
}

function bc_dsgvo_inject_dse()
{
    ?>
    <h1><?php _e('Datenschutzerklärung', 'bc_dsgvo' ); ?></h1>
    <p><?php _e('Verantwortliche Stelle im Sinne der Datenschutzgesetze, insbesondere der EU-Datenschutzgrundverordnung (DSGVO), ist:', 'bc_dsgvo' ); ?></p>
    <p><?=get_option( "bc_dsgvo_dse_1" )?></p>
    <h2><?php _e('Ihre Betroffenenrechte', 'bc_dsgvo' ); ?></h2>
    <p><?php _e('Unter den angegebenen Kontaktdaten unseres Datenschutzbeauftragten können Sie jederzeit folgende Rechte ausüben:', 'bc_dsgvo' ); ?></p>
    <ul>
    <li><?php _e('Auskunft über Ihre bei uns gespeicherten Daten und deren Verarbeitung', 'bc_dsgvo' ); ?>,</li>
    <li><?php _e('Berichtigung unrichtiger personenbezogener Daten', 'bc_dsgvo' ); ?>,</li>
    <li><?php _e('Löschung Ihrer bei uns gespeicherten Daten', 'bc_dsgvo' ); ?>,</li>
    <li><?php _e('Einschränkung der Datenverarbeitung, sofern wir Ihre Daten aufgrund gesetzlicher Pflichten noch nicht löschen dürfen', 'bc_dsgvo' ); ?>,</li>
    <li><?php _e('Widerspruch gegen die Verarbeitung Ihrer Daten bei uns und Datenübertragbarkeit, sofern Sie in die Datenverarbeitung eingewilligt haben oder einen Vertrag mit uns abgeschlossen haben.', 'bc_dsgvo' ); ?></li>
    </ul>
    <p><?php _e('Sofern Sie uns eine Einwilligung erteilt haben, können Sie diese jederzeit mit Wirkung für die Zukunft widerrufen.', 'bc_dsgvo' ); ?></p>
    <p><?php _e('Sie können sich jederzeit mit einer Beschwerde an die für Sie zuständige Aufsichtsbehörde wenden. Ihre zuständige Aufsichtsbehörde richtet sich nach dem Bundesland Ihres Wohnsitzes, Ihrer Arbeit oder der mutmaßlichen Verletzung. Eine Liste der Aufsichtsbehörden (für den nichtöffentlichen Bereich) mit Anschrift finden Sie unter: <a href="https://www.bfdi.bund.de/DE/Infothek/Anschriften_Links/anschriften_links-node.html" target="_blank" rel="noopener">https://www.bfdi.bund.de/DE/Infothek/Anschriften_Links/anschriften_links-node.html</a>.', 'bc_dsgvo' ); ?></p>
    <h2><?php _e('Zwecke der Datenverarbeitung durch die verantwortliche Stelle und Dritte', 'bc_dsgvo' ); ?></h2>
    <p><?php _e('Wir verarbeiten Ihre personenbezogenen Daten nur zu den in dieser Datenschutzerklärung genannten Zwecken. Eine Übermittlung Ihrer persönlichen Daten an Dritte zu anderen als den genannten Zwecken findet nicht statt. Wir geben Ihre persönlichen Daten nur an Dritte weiter, wenn:', 'bc_dsgvo' ); ?></p>
    <ul>
    <li><?php _e('Sie Ihre ausdrückliche Einwilligung dazu erteilt haben', 'bc_dsgvo' ); ?>,</li>
    <li><?php _e('die Verarbeitung zur Abwicklung eines Vertrags mit Ihnen erforderlich ist', 'bc_dsgvo' ); ?>,</li>
    <li><?php _e('die Verarbeitung zur Erfüllung einer rechtlichen Verpflichtung erforderlich ist', 'bc_dsgvo' ); ?></li>
    </ul>
    <p><?php _e('die Verarbeitung zur Wahrung berechtigter Interessen erforderlich ist und kein Grund zur Annahme besteht, dass Sie ein überwiegendes schutzwürdiges Interesse an der Nichtweitergabe Ihrer Daten haben.', 'bc_dsgvo' ); ?></p>
    <?php 
    if(get_option( "bc_dsgvo_dse_2" ))
    {
    ?>
    <h2><?php _e('Löschung bzw. Sperrung der Daten', 'bc_dsgvo' ); ?></h2>
    <p><?php _e('Wir halten uns an die Grundsätze der Datenvermeidung und Datensparsamkeit. Wir speichern Ihre personenbezogenen Daten daher nur so lange, wie dies zur Erreichung der hier genannten Zwecke erforderlich ist oder wie es die vom Gesetzgeber vorgesehenen vielfältigen Speicherfristen vorsehen. Nach Fortfall des jeweiligen Zweckes bzw. Ablauf dieser Fristen werden die entsprechenden Daten routinemäßig und entsprechend den gesetzlichen Vorschriften gesperrt oder gelöscht.', 'bc_dsgvo' ); ?></p>
    <?php
    }
    if(get_option( "bc_dsgvo_dse_3" ))
    {
    ?>
    <h2><?php _e('Erfassung allgemeiner Informationen beim Besuch unserer Website', 'bc_dsgvo' ); ?></h2>
    <p><?php _e('Wenn Sie auf unsere Website zugreifen, werden automatisch mittels eines Cookies Informationen allgemeiner Natur erfasst. Diese Informationen (Server-Logfiles) beinhalten etwa die Art des Webbrowsers, das verwendete Betriebssystem, den Domainnamen Ihres Internet-Service-Providers und ähnliches. Hierbei handelt es sich ausschließlich um Informationen, welche keine Rückschlüsse auf Ihre Person zulassen.', 'bc_dsgvo' ); ?></p>
    <p><?php _e('Diese Informationen sind technisch notwendig, um von Ihnen angeforderte Inhalte von Webseiten korrekt auszuliefern und fallen bei Nutzung des Internets zwingend an. Sie werden insbesondere zu folgenden Zwecken verarbeitet:', 'bc_dsgvo' ); ?></p>
    <ul>
    <li><?php _e('Sicherstellung eines problemlosen Verbindungsaufbaus der Website', 'bc_dsgvo' ); ?>,</li>
    <li><?php _e('Sicherstellung einer reibungslosen Nutzung unserer Website', 'bc_dsgvo' ); ?>,</li>
    <li><?php _e('Auswertung der Systemsicherheit und -stabilität sowie zu weiteren administrativen Zwecken.', 'bc_dsgvo' ); ?></li>
    </ul>
    <p><?php _e('Die Verarbeitung Ihrer personenbezogenen Daten basiert auf unserem berechtigten Interesse aus den vorgenannten Zwecken zur Datenerhebung. Wir verwenden Ihre Daten nicht, um Rückschlüsse auf Ihre Person zu ziehen. Empfänger der Daten sind nur die verantwortliche Stelle und ggf. Auftragsverarbeiter.', 'bc_dsgvo' ); ?></p>
    <p><?php _e('Anonyme Informationen dieser Art werden von uns ggfs. statistisch ausgewertet, um unseren Internetauftritt und die dahinterstehende Technik zu optimieren.', 'bc_dsgvo' ); ?></p>
    <?php
    }
    if(get_option( "bc_dsgvo_cookies_enabled" ))
    {
    ?>
    <h2><?php _e('Cookies', 'bc_dsgvo' ); ?></h2>
    <p><?php _e('Wie viele andere Webseiten verwenden wir auch so genannte „Cookies“. Cookies sind kleine Textdateien, die von einem Websiteserver auf Ihre Festplatte übertragen werden. Hierdurch erhalten wir automatisch bestimmte Daten wie z. B. IP-Adresse, verwendeter Browser, Betriebssystem und Ihre Verbindung zum Internet.', 'bc_dsgvo' ); ?></p>
    <p><?php _e('Cookies können nicht verwendet werden, um Programme zu starten oder Viren auf einen Computer zu übertragen. Anhand der in Cookies enthaltenen Informationen können wir Ihnen die Navigation erleichtern und die korrekte Anzeige unserer Webseiten ermöglichen.', 'bc_dsgvo' ); ?></p>
    <p><?php _e('In keinem Fall werden die von uns erfassten Daten an Dritte weitergegeben oder ohne Ihre Einwilligung eine Verknüpfung mit personenbezogenen Daten hergestellt.', 'bc_dsgvo' ); ?></p>
    <p><?php _e('Natürlich können Sie unsere Website grundsätzlich auch ohne Cookies betrachten. Internet-Browser sind regelmäßig so eingestellt, dass sie Cookies akzeptieren. Im Allgemeinen können Sie die Verwendung von Cookies jederzeit über die Einstellungen Ihres Browsers deaktivieren. Bitte verwenden Sie die Hilfefunktionen Ihres Internetbrowsers, um zu erfahren, wie Sie diese Einstellungen ändern können. Bitte beachten Sie, dass einzelne Funktionen unserer Website möglicherweise nicht funktionieren, wenn Sie die Verwendung von Cookies deaktiviert haben.', 'bc_dsgvo' ); ?></p>
    <?php
    }
    if(get_option( "bc_dsgvo_dse_4" ))
    {
    ?>
    <h2><?php _e('Registrierung auf unserer Webseite', 'bc_dsgvo' ); ?></h2>
    <p><?php _e('Bei der Registrierung für die Nutzung unserer personalisierten Leistungen werden einige personenbezogene Daten erhoben, wie Name, Anschrift, Kontakt- und Kommunikationsdaten wie Telefonnummer und E-Mail-Adresse. Sind Sie bei uns registriert, können Sie auf Inhalte und Leistungen zugreifen, die wir nur registrierten Nutzern anbieten. Angemeldete Nutzer haben zudem die Möglichkeit, bei Bedarf die bei Registrierung angegebenen Daten jederzeit zu ändern oder zu löschen. Selbstverständlich erteilen wir Ihnen darüber hinaus jederzeit Auskunft über die von uns über Sie gespeicherten personenbezogenen Daten. Gerne berichtigen bzw. löschen wir diese auch auf Ihren Wunsch, soweit keine gesetzlichen Aufbewahrungspflichten entgegenstehen. Zur Kontaktaufnahme in diesem Zusammenhang nutzen Sie bitte die am Ende dieser Datenschutzerklärung angegebenen Kontaktdaten.', 'bc_dsgvo' ); ?></p>
    <?php
    }
    if(get_option( "bc_dsgvo_dse_5" ))
    {
    ?>
    <h2><?php _e('Erbringung kostenpflichtiger Leistungen', 'bc_dsgvo' ); ?></h2>
    <p><?php _e('Zur Erbringung kostenpflichtiger Leistungen werden von uns zusätzliche Daten erfragt, wie z.B. Zahlungsangaben, um Ihre Bestellung ausführen zu können. Wir speichern diese Daten in unseren Systemen bis die gesetzlichen Aufbewahrungsfristen abgelaufen sind.', 'bc_dsgvo' ); ?></p>
    <?php
    }
    if(get_option( "bc_dsgvo_dse_6" ))
    {
    ?>
    <h2><?php _e('SSL-Verschlüsselung', 'bc_dsgvo' ); ?></h2>
    <p><?php _e('Um die Sicherheit Ihrer Daten bei der Übertragung zu schützen, verwenden wir dem aktuellen Stand der Technik entsprechende Verschlüsselungsverfahren (z. B. SSL) über HTTPS.', 'bc_dsgvo' ); ?></p>
    <?php
    }
    if(get_option( "bc_dsgvo_dse_7" ))
    {
    ?>
    <h2><?php _e('Kommentarfunktion', 'bc_dsgvo' ); ?></h2>
    <p><?php _e('Wenn Nutzer Kommentare auf unserer Website hinterlassen, werden neben diesen Angaben auch der Zeitpunkt ihrer Erstellung und der zuvor durch den Websitebesucher gewählte Nutzername gespeichert. Dies dient unserer Sicherheit, da wir für widerrechtliche Inhalte auf unserer Webseite belangt werden können, auch wenn diese durch Benutzer erstellt wurden.', 'bc_dsgvo' ); ?></p>
    <?php
    }
    if(get_option( "bc_dsgvo_dse_8" ))
    {
    ?>
    <h2><?php _e('Newsletter', 'bc_dsgvo' ); ?></h2>
    <p><?php _e('Auf Grundlage Ihrer ausdrücklich erteilten Einwilligung, übersenden wir Ihnen regelmäßig unseren Newsletter bzw. vergleichbare Informationen per E-Mail an Ihre angegebene E-Mail-Adresse.', 'bc_dsgvo' ); ?></p>
    <p><?php _e('Für den Empfang des Newsletters ist die Angabe Ihrer E-Mail-Adresse ausreichend. Bei der Anmeldung zum Bezug unseres Newsletters werden die von Ihnen angegebenen Daten ausschließlich für diesen Zweck verwendet. Abonnenten können auch über Umstände per E-Mail informiert werden, die für den Dienst oder die Registrierung relevant sind (Beispielsweise Änderungen des Newsletterangebots oder technische Gegebenheiten).', 'bc_dsgvo' ); ?></p>
    <p><?php _e('Für eine wirksame Registrierung benötigen wir eine valide E-Mail-Adresse. Um zu überprüfen, dass eine Anmeldung tatsächlich durch den Inhaber einer E-Mail-Adresse erfolgt, setzen wir das „Double-opt-in“-Verfahren ein. Hierzu protokollieren wir die Bestellung des Newsletters, den Versand einer Bestätigungsmail und den Eingang der hiermit angeforderten Antwort. Weitere Daten werden nicht erhoben. Die Daten werden ausschließlich für den Newsletterversand verwendet und nicht an Dritte weitergegeben.', 'bc_dsgvo' ); ?></p>
    <p><?php _e('Die Einwilligung zur Speicherung Ihrer persönlichen Daten und ihrer Nutzung für den Newsletterversand können Sie jederzeit widerrufen. In jedem Newsletter findet sich dazu ein entsprechender Link. Außerdem können Sie sich jederzeit auch direkt auf dieser Webseite abmelden oder uns Ihren entsprechenden Wunsch über die am Ende dieser Datenschutzhinweise angegebene Kontaktmöglichkeit mitteilen.', 'bc_dsgvo' ); ?></p>
    <?php
    }
    if(get_option( "bc_dsgvo_dse_9" ))
    {
    ?>
    <h2><?php _e('Kontaktformular', 'bc_dsgvo' ); ?></h2>
    <p><?php _e('Treten Sie bzgl. Fragen jeglicher Art per E-Mail oder Kontaktformular mit uns in Kontakt, erteilen Sie uns zum Zwecke der Kontaktaufnahme Ihre freiwillige Einwilligung. Hierfür ist die Angabe einer validen E-Mail-Adresse erforderlich. Diese dient der Zuordnung der Anfrage und der anschließenden Beantwortung derselben. Die Angabe weiterer Daten ist optional. Die von Ihnen gemachten Angaben werden zum Zwecke der Bearbeitung der Anfrage sowie für mögliche Anschlussfragen gespeichert. Nach Erledigung der von Ihnen gestellten Anfrage werden personenbezogene Daten automatisch gelöscht.', 'bc_dsgvo' ); ?></p>
    <?php
    }
    if(get_option( "bc_dsgvo_ga_enabled" ))
    {
    ?>
    <h2><?php _e('Verwendung von Google Analytics', 'bc_dsgvo' ); ?></h2>
    <p><?php _e('Diese Website benutzt Google Analytics, einen Webanalysedienst der Google Inc. (folgend: Google). Google Analytics verwendet sog. „Cookies“, also Textdateien, die auf Ihrem Computer gespeichert werden und die eine Analyse der Benutzung der Webseite durch Sie ermöglichen. Die durch das Cookie erzeugten Informationen über Ihre Benutzung dieser Webseite werden in der Regel an einen Server von Google in den USA übertragen und dort gespeichert. Aufgrund der Aktivierung der IP-Anonymisierung auf diesen Webseiten, wird Ihre IP-Adresse von Google jedoch innerhalb von Mitgliedstaaten der Europäischen Union oder in anderen Vertragsstaaten des Abkommens über den Europäischen Wirtschaftsraum zuvor gekürzt. Nur in Ausnahmefällen wird die volle IP-Adresse an einen Server von Google in den USA übertragen und dort gekürzt. Im Auftrag des Betreibers dieser Website wird Google diese Informationen benutzen, um Ihre Nutzung der Webseite auszuwerten, um Reports über die Webseitenaktivitäten zusammenzustellen und um weitere mit der Websitenutzung und der Internetnutzung verbundene Dienstleistungen gegenüber dem Webseitenbetreiber zu erbringen. Die im Rahmen von Google Analytics von Ihrem Browser übermittelte IP-Adresse wird nicht mit anderen Daten von Google zusammengeführt.', 'bc_dsgvo' ); ?></p>
    <p><?php _e('Die Zwecke der Datenverarbeitung liegen in der Auswertung der Nutzung der Website und in der Zusammenstellung von Reports über Aktivitäten auf der Website. Auf Grundlage der Nutzung der Website und des Internets sollen dann weitere verbundene Dienstleistungen erbracht werden. Die Verarbeitung beruht auf dem berechtigten Interesse des Webseitenbetreibers.', 'bc_dsgvo' ); ?></p>
    <p><?php _e('Sie können die Speicherung der Cookies durch eine entsprechende Einstellung Ihrer Browser-Software verhindern; wir weisen Sie jedoch darauf hin, dass Sie in diesem Fall gegebenenfalls nicht sämtliche Funktionen dieser Website vollumfänglich werden nutzen können. Sie können darüber hinaus die Erfassung der durch das Cookie erzeugten und auf Ihre Nutzung der Webseite bezogenen Daten (inkl. Ihrer IP-Adresse) an Google sowie die Verarbeitung dieser Daten durch Google verhindern, indem sie das unter dem folgenden Link verfügbare Browser-Plugin herunterladen und installieren: <a href="http://tools.google.com/dlpage/gaoptout?hl=de" target="_blank" rel="noopener">Browser Add On zur Deaktivierung von Google Analytics</a>.', 'bc_dsgvo' ); ?></p>
    <?php
    }
    if(get_option( "bc_dsgvo_dse_10" ))
    {
    ?>
    <h2><?php _e('Verwendung von Scriptbibliotheken (Google Webfonts)', 'bc_dsgvo' ); ?></h2>
    <p><?php _e('Um unsere Inhalte browserübergreifend korrekt und grafisch ansprechend darzustellen, verwenden wir auf dieser Website Scriptbibliotheken und Schriftbibliotheken wie z. B. Google Webfonts (<a href="http://www.google.com/webfonts/" target="_blank" rel="noopener">https://www.google.com/webfonts/</a>). Google Webfonts werden zur Vermeidung mehrfachen Ladens in den Cache Ihres Browsers übertragen. Falls der Browser die Google Webfonts nicht unterstützt oder den Zugriff unterbindet, werden Inhalte in einer Standardschrift angezeigt.', 'bc_dsgvo' ); ?></p>
    <p><?php _e('Der Aufruf von Scriptbibliotheken oder Schriftbibliotheken löst automatisch eine Verbindung zum Betreiber der Bibliothek aus. Dabei ist es theoretisch möglich – aktuell allerdings auch unklar ob und ggf. zu welchen Zwecken – dass Betreiber entsprechender Bibliotheken Daten erheben.', 'bc_dsgvo' ); ?></p>
    <p><?php _e('Die Datenschutzrichtlinie des Bibliothekbetreibers Google finden Sie hier: <a href="https://www.google.com/policies/privacy/" target="_blank" rel="noopener">https://www.google.com/policies/privacy/</a>', 'bc_dsgvo' ); ?></p>
    <?php
    }
    if(get_option( "bc_dsgvo_dse_11" ))
    {
    ?>
    <h2><?php _e('Verwendung von Google Maps', 'bc_dsgvo' ); ?></h2>
    <p><?php _e('Diese Webseite verwendet Google Maps API, um geographische Informationen visuell darzustellen. Bei der Nutzung von Google Maps werden von Google auch Daten über die Nutzung der Kartenfunktionen durch Besucher erhoben, verarbeitet und genutzt. Nähere Informationen über die Datenverarbeitung durch Google können Sie <a href="http://www.google.com/privacypolicy.html" target="_blank" rel="noopener">den Google-Datenschutzhinweisen</a> entnehmen. Dort können Sie im Datenschutzcenter auch Ihre persönlichen Datenschutz-Einstellungen verändern.', 'bc_dsgvo' ); ?></p>
    <p><?php _e('Ausführliche Anleitungen zur Verwaltung der eigenen Daten im Zusammenhang mit Google-Produkten<a href="http://www.dataliberation.org/" target="_blank" rel="noopener"> finden Sie hier</a>.', 'bc_dsgvo' ); ?></p>
    <?php
    }
    if(get_option( "bc_dsgvo_dse_12" ))
    {
    ?>
    <h2><?php _e('Eingebettete YouTube-Videos', 'bc_dsgvo' ); ?></h2>
    <p><?php _e('Auf einigen unserer Webseiten betten wir Youtube-Videos ein. Betreiber der entsprechenden Plugins ist die YouTube, LLC, 901 Cherry Ave., San Bruno, CA 94066, USA. Wenn Sie eine Seite mit dem YouTube-Plugin besuchen, wird eine Verbindung zu Servern von Youtube hergestellt. Dabei wird Youtube mitgeteilt, welche Seiten Sie besuchen. Wenn Sie in Ihrem Youtube-Account eingeloggt sind, kann Youtube Ihr Surfverhalten Ihnen persönlich zuzuordnen. Dies verhindern Sie, indem Sie sich vorher aus Ihrem Youtube-Account ausloggen.', 'bc_dsgvo' ); ?></p>
    <p><?php _e('Wird ein Youtube-Video gestartet, setzt der Anbieter Cookies ein, die Hinweise über das Nutzerverhalten sammeln.', 'bc_dsgvo' ); ?></p>
    <p><?php _e('Wer das Speichern von Cookies für das Google-Ad-Programm deaktiviert hat, wird auch beim Anschauen von Youtube-Videos mit keinen solchen Cookies rechnen müssen. Youtube legt aber auch in anderen Cookies nicht-personenbezogene Nutzungsinformationen ab. Möchten Sie dies verhindern, so müssen Sie das Speichern von Cookies im Browser blockieren.', 'bc_dsgvo' ); ?></p>
    <p><?php _e('Weitere Informationen zum Datenschutz bei „Youtube“ finden Sie in der Datenschutzerklärung des Anbieters unter: <a href="https://www.google.de/intl/de/policies/privacy/" target="_blank" rel="noopener">https://www.google.de/intl/de/policies/privacy/ </a>', 'bc_dsgvo' ); ?></p>
    <?php
    }
    if(get_option( "bc_dsgvo_dse_13" ))
    {
    ?>
    <h2><?php _e('Google AdWords', 'bc_dsgvo' ); ?></h2>
    <p><?php _e('Unsere Webseite nutzt das Google Conversion-Tracking. Sind Sie über eine von Google geschaltete Anzeige auf unsere Webseite gelangt, wird von Google Adwords ein Cookie auf Ihrem Rechner gesetzt. Das Cookie für Conversion-Tracking wird gesetzt, wenn ein Nutzer auf eine von Google geschaltete Anzeige klickt. Diese Cookies verlieren nach 30 Tagen ihre Gültigkeit und dienen nicht der persönlichen Identifizierung. Besucht der Nutzer bestimmte Seiten unserer Website und das Cookie ist noch nicht abgelaufen, können wir und Google erkennen, dass der Nutzer auf die Anzeige geklickt hat und zu dieser Seite weitergeleitet wurde. Jeder Google AdWords-Kunde erhält ein anderes Cookie. Cookies können somit nicht über die Websites von AdWords-Kunden nachverfolgt werden. Die mithilfe des Conversion-Cookies eingeholten Informationen dienen dazu, Conversion-Statistiken für AdWords-Kunden zu erstellen, die sich für Conversion-Tracking entschieden haben. Die Kunden erfahren die Gesamtanzahl der Nutzer, die auf ihre Anzeige geklickt haben und zu einer mit einem Conversion-Tracking-Tag versehenen Seite weitergeleitet wurden. Sie erhalten jedoch keine Informationen, mit denen sich Nutzer persönlich identifizieren lassen.', 'bc_dsgvo' ); ?></p>
    <p><?php _e('Möchten Sie nicht am Tracking teilnehmen, können Sie das hierfür erforderliche Setzen eines Cookies ablehnen – etwa per Browser-Einstellung, die das automatische Setzen von Cookies generell deaktiviert oder Ihren Browser so einstellen, dass Cookies von der Domain „googleleadservices.com“ blockiert werden.', 'bc_dsgvo' ); ?></p>
    <p><?php _e('Bitte beachten Sie, dass Sie die Opt-out-Cookies nicht löschen dürfen, solange Sie keine Aufzeichnung von Messdaten wünschen. Haben Sie alle Ihre Cookies im Browser gelöscht, müssen Sie das jeweilige Opt-out Cookie erneut setzen.', 'bc_dsgvo' ); ?></p>
    <?php
    }
    if(get_option( "bc_dsgvo_dse_14" ))
    {
    ?>
    <h2><?php _e('Einsatz von Google Remarketing', 'bc_dsgvo' ); ?></h2>
    <p><?php _e('Diese Webseite verwendet die Remarketing-Funktion der Google Inc. Die Funktion dient dazu, Webseitenbesuchern innerhalb des Google-Werbenetzwerks interessenbezogene Werbeanzeigen zu präsentieren. Im Browser des Webseitenbesuchers wird ein sog. „Cookie“ gespeichert, der es ermöglicht, den Besucher wiederzuerkennen, wenn dieser Webseiten aufruft, die dem Werbenetzwerk von Google angehören. Auf diesen Seiten können dem Besucher Werbeanzeigen präsentiert werden, die sich auf Inhalte beziehen, die der Besucher zuvor auf Webseiten aufgerufen hat, die die Remarketing Funktion von Google verwenden.', 'bc_dsgvo' ); ?></p>
    <p><?php _e('Nach eigenen Angaben erhebt Google bei diesem Vorgang keine personenbezogenen Daten. Sollten Sie die Funktion Remarketing von Google dennoch nicht wünschen, können Sie diese grundsätzlich deaktivieren, indem Sie die entsprechenden Einstellungen unter <a href="http://www.google.com/settings/ads" target="_blank" rel="noopener">http://www.google.com/settings/ads</a> vornehmen. Alternativ können Sie den Einsatz von Cookies für interessenbezogene Werbung über die Werbenetzwerkinitiative deaktivieren, indem Sie den Anweisungen unter <a href="http://www.networkadvertising.org/managing/opt_out.asp" target="_blank" rel="noopener">http://www.networkadvertising.org/managing/opt_out.asp</a> folgen.', 'bc_dsgvo' ); ?></p>
    <?php
    }
    ?>
    <h2><strong><?php _e('Änderung unserer Datenschutzbestimmungen', 'bc_dsgvo' ); ?></strong></h2>
    <p><?php _e('Wir behalten uns vor, diese Datenschutzerklärung anzupassen, damit sie stets den aktuellen rechtlichen Anforderungen entspricht oder um Änderungen unserer Leistungen in der Datenschutzerklärung umzusetzen, z.B. bei der Einführung neuer Services. Für Ihren erneuten Besuch gilt dann die neue Datenschutzerklärung.', 'bc_dsgvo' ); ?></p>
    <h2><strong><?php _e('Fragen an den Datenschutzbeauftragten', 'bc_dsgvo' ); ?></strong></h2>
    <p><?php _e('Wenn Sie Fragen zum Datenschutz haben, schreiben Sie uns bitte eine E-Mail oder wenden Sie sich direkt an die für den Datenschutz verantwortliche Person in unserer Organisation:', 'bc_dsgvo' ); ?></p>
    <p><?=get_option( "bc_dsgvo_dse_15" )?></p>
    <?php
}


function bc_dsgvo_inject_cookie_notice()
{
    if(get_option( "bc_dsgvo_cookies_enabled" ) == 1 || get_option( "bc_dsgvo_ga_enabled" ) == 1)
    {
	wp_enqueue_script( 'cookieconsent.min', plugin_dir_url( __FILE__ ) . 'js/cookieconsent.min.js');
	wp_enqueue_style( 'cookieconsent.min', plugin_dir_url( __FILE__ ) . 'css/cookieconsent.min.css');
	$message = "";
	$ok = 'OK';
	if(get_option( "bc_dsgvo_cookies_text_ok" )){$ok = get_option( "bc_dsgvo_cookies_text_ok" );}
	
	if(get_option( "bc_dsgvo_cookies_enabled" ) == 1)
	{
	    $message = __('Diese Webseite verwendet Cookies. Sie erklären sich mit der Verwendung von Cookies einverstanden wenn Sie diese Webseite benutzen. ', 'bc_dsgvo' );
	    if(get_option( "bc_dsgvo_cookies_text_message" ) != ''){$message = get_option( "bc_dsgvo_cookies_text_message" ) . "<br>";}
	}
	if(get_option( "bc_dsgvo_ga_enabled" ) == 1)
	{
	    $message .=  __('Um der Verwendung von Tracking Cookies zu widersprechen <a href=\"javascript:gaOptout();\" class=\"cc-link\">klicken Sie bitte hier.</a>', 'bc_dsgvo' );
	}
	$background = '#000000';
	$text = '#FFFFFF';
	$buttons = '#FFFFFF';
	$buttonstext = '#000000';
	if(get_option( "bc_dsgvo_cookies_color_back" )){$background = get_option( "bc_dsgvo_cookies_color_back" );}
	if(get_option( "bc_dsgvo_cookies_color_text" )){$text = get_option( "bc_dsgvo_cookies_color_text" );}
	if(get_option( "bc_dsgvo_cookies_color_button" )){$buttons = get_option( "bc_dsgvo_cookies_color_button" );}
	if(get_option( "bc_dsgvo_cookies_color_buttontext" )){$buttonstext = get_option( "bc_dsgvo_cookies_color_buttontext" );}
	
	
	?>
	<script>
	window.addEventListener("load", function(){
	    window.cookieconsent.initialise({
		"palette": {
        	    "popup": {
            		"background": "<?=$background;?>",
            		"text": "<?=$text;?>"
    		    },
                    "button": {
                	"background": "<?=$buttons;?>",
                        "text": "<?=$buttonstext;?>"
		    }
		},
		<?php
		if(get_option( "bc_dsgvo_cookies_position" ) == 2) echo '"position": "bottom-left",';
		if(get_option( "bc_dsgvo_cookies_position" ) == 3) echo '"position": "bottom-right",';
		if(get_option( "bc_dsgvo_cookies_position" ) == 4) echo '"position": "top",';
		if(get_option( "bc_dsgvo_cookies_layout" ) == 2) echo '"theme": "edgeless",';
		if(get_option( "bc_dsgvo_cookies_layout" ) == 3) echo '"theme": "classic",';
		?>
		"content": {
		    "message": "<?=$message;?>",
		    "dismiss": "<?=$ok;?>",
		    "link": "<?=get_option( "bc_dsgvo_cookies_text_link" );?>",
		    "href": "<?=get_option( "bc_dsgvo_cookies_link" );?>"
		}
	    })
	});
	</script>
	<?php
    }
    if(get_option( "bc_dsgvo_ga_enabled" ) == 1)
    {
	?>
	<script> 
	    var gaProperty = '<?=get_option( "bc_dsgvo_ga_ua" )?>';
		var gaProperty2 = '<?=get_option( "bc_dsgvo_ga_ua2" )?>';		
	    var disableStr = 'ga-disable-' + gaProperty; 
	    var disableStr2 = 'ga-disable-' + gaProperty2; 
	    if (document.cookie.indexOf(disableStr + '=true') > -1) { 
			window[disableStr] = true;
	    } 
	    if (document.cookie.indexOf(disableStr2 + '=true') > -1) { 
			window[disableStr2] = true;
	    } 
	    function gaOptout() { 
	        document.cookie = disableStr + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/'; 
	        document.cookie = disableStr2 + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/'; 
	        window[disableStr] = true; 
	        window[disableStr2] = true; 
	        alert('<?php _e('Das Tracking ist jetzt deaktiviert', 'bc_dsgvo' );?>'); 
	    } 
	    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){ 
	        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o), 
	        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m) 
	    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga'); 
	    
		if(gaProperty != '')
		{
			ga('create', '<?=get_option( "bc_dsgvo_ga_ua" )?>', {name: 'default'}); 
			ga('default.set', 'anonymizeIp', true); 
			ga('default.send', 'pageview'); 
		}
		if(gaProperty2 != '')
		{
			ga('create', '<?=get_option( "bc_dsgvo_ga_ua2" )?>', {name: 'additional'}); 
			ga('additional.set', 'anonymizeIp', true); 
			ga('additional.send', 'pageview'); 
		}		
	</script>
	<?php
    }
}
add_action( 'wp_footer', 'bc_dsgvo_inject_cookie_notice' );

function bc_dsgvo_inject_form_fields()
{
    if(get_option( "bc_dsgvo_cookies_link"))
    {
	wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', array(), null, true);
	if(get_option( "bc_dsgvo_forms_ninja_enabled" ))
	{
	    ?>
	    <script>
	    function bc_dsgvo_waitForNinjaFormToDisplay() {
    		if(document.querySelector(".nf-form-content .submit-container")!=null) {
		    <!-- Ninja Forms -->
		    jQuery('<nf-field><div id="nf-field-99-container" class="nf-field-container checkbox-container label-above "><div class="nf-before-field"><nf-section></nf-section></div><div class="nf-field"><div id="nf-field-99-wrap" class="field-wrap checkbox-wrap" data-field-id="99"><div class="nf-field-label"><label for="nf-field-99" id="nf-label-field-99" class=""><a href="<?=get_option( "bc_dsgvo_cookies_link" );?>" target="_blank">Datenschutzerklärung anzeigen</a></label></div><div class="nf-field-element"><input type="checkbox" id="nf-field-99" name="nf-field-99" aria-invalid="false" aria-describedby="nf-error-99" class="ninja-forms-field nf-element" aria-labelledby="nf-label-field-99" required> Ich habe die Datenschutzerklärung gelesen und akzeptiert</div></div></div><div class="nf-after-field"><nf-section><div class="nf-input-limit"></div><div id="nf-error-99" class="nf-error-wrap nf-error" role="alert"></div></nf-section></div></div></nf-field>')
			.prependTo(selector);
		
		    var myCustomFieldController = Marionette.Object.extend({
			initialize: function() {
			    var submitChannel = Backbone.Radio.channel( 'submit' );
			    this.listenTo( submitChannel, 'validate:field', this.validate );
			},
			validate: function( model ) {
			    var modelID       = model.get( 'id' );
			    var errorID       = 'custom-field-error';
			    var errorMessage  = 'Sie müssen die Datenschutzerklärung akzeptieren';
			    var fieldsChannel = Backbone.Radio.channel( 'fields' );
			    if(jQuery('#nf-field-99:checked').length == 0)
			    {
				fieldsChannel.request( 'add:error', modelID, errorID, errorMessage );
			    }
			},
		    });
    		    new myCustomFieldController();
    		    return;
		}
		else
		{
    		    setTimeout(function() {
        		bc_dsgvo_waitForNinjaFormToDisplay();
		    }, 1000);
		}
	    }
	    jQuery( document ).ready(function() {
		bc_dsgvo_waitForNinjaFormToDisplay();
	    });
	    </script>
	    <?php
	}
    }
}

if(get_option( "bc_dsgvo_forms_ninja_enabled" ))
{
    add_action( 'wp_footer', 'bc_dsgvo_inject_form_fields' );
}

add_action( 'admin_menu', 'bc_dsgvo_menu' );

function bc_dsgvo_menu() {
    add_menu_page( 'BC-DSGVO', 'BC-DSGVO', 'manage_options', 'bc_dsgvo', 'show_admin_page', null, 5 );
}

function show_admin_page()
{
    if (!current_user_can('manage_options'))
    {
          wp_die( _e('You do not have sufficient permissions to access this page.') );
    }
    
    wp_enqueue_style( 'bc_dsgvo_style', plugin_dir_url( __FILE__ ) . 'css/style.css' );
    
    $hidden_field_name = 'bc_dsgvo_submit_hidden';
    
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' )
    {
	check_admin_referer( 'bc_dsgvo_update_options' );
	foreach($_POST as $key => $value)
	{
	    if(substr($key,0,9) == 'bc_dsgvo_')
	    {
		update_option( $key, sanitize_text_field($value) );
	    }
	}
	?>
	<div class="updated"><p><strong><?=_e('Gespeichert', 'bc_dsgvo' );?></strong></p></div>
	<?php
    }
    
    $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'cookies';
    
    echo '<div class="wrap">';
    echo "<h2>" . _e( 'DSGVO Deutschland', 'bc_dsgvo' ) . "</h2>";
    ?>
    <h2 class="nav-tab-wrapper">
        <a href="?page=bc_dsgvo&tab=cookies" class="nav-tab <?php echo $active_tab == 'cookies' ? 'nav-tab-active' : ''; ?>">Cookiehinweis (BDSG & DSGVO)</a>
        <a href="?page=bc_dsgvo&tab=ga" class="nav-tab <?php echo $active_tab == 'ga' ? 'nav-tab-active' : ''; ?>">Tracking & OPT-OUT (DSGVO)</a>
        <a href="?page=bc_dsgvo&tab=dse" class="nav-tab <?php echo $active_tab == 'dse' ? 'nav-tab-active' : ''; ?>">Datenschutzerklärung (DSGVO)</a>
        <a href="?page=bc_dsgvo&tab=imprint" class="nav-tab <?php echo $active_tab == 'imprint' ? 'nav-tab-active' : ''; ?>">Impressum (TMG)</a>
        <a href="?page=bc_dsgvo&tab=forms" class="nav-tab <?php echo $active_tab == 'forms' ? 'nav-tab-active' : ''; ?>">Formulare (DSGVO)</a>
        <a href="?page=bc_dsgvo&tab=fonts" class="nav-tab <?php echo $active_tab == 'fonts' ? 'nav-tab-active' : ''; ?>">Google Fonts (Premium)</a>
    </h2>
    <form method="post" class="shadow2" style="background-color: #FFFFFF; padding:20px;">
	<?php
	wp_nonce_field( 'bc_dsgvo_update_options' );
	?>
	<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
	<table class="form-table" style="background-color: #FFFFFF;">
	<tbody>
	<?php
	    if( $active_tab == 'cookies' ) {
		?>
		<tr><h2 class="shadow2">Cookie-Richtline</h2></tr>
		<tr>
		<p>
		Die Cookie-Richtlinie sieht im Wesentlichen vor, dass die Besucher einer Website über den Einsatz von Cookies in einer leicht verständlichen Form informiert werden und der 
		Speicherung zustimmen müssen. Cookies dürfen laut der Richtlinie nur dann ungefragt gesetzt werden, wenn sie technisch notwendig sind – also beispielsweise um einen durch 
		den Nutzer erwünschten Dienst umzusetzen. Hierzu zählen etwa Session-Cookies zur Speicherung der Spracheinstellung, der Log-in-Daten und des Warenkorbs oder Flash-Cookies 
		zur Wiedergabe von Medieninhalten.
		</p>
		</tr>
		<tr><hr></tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Cookiehinweis aktivieren (Empfohlen):', 'bc_dsgvo' ); ?></b></h3>
		    Deaktiviert <input type="radio" name="bc_dsgvo_cookies_enabled" <?php echo (get_option( "bc_dsgvo_cookies_enabled" ) == 0 ? 'checked' : ''); ?> value="0">
		    Aktiviert <input type="radio" name="bc_dsgvo_cookies_enabled" <?php echo (get_option( "bc_dsgvo_cookies_enabled" ) == 1 ? 'checked' : ''); ?> value="1">
		</tr>
		<tr><hr></tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Position:', 'bc_dsgvo' ); ?></b></h3>
		    <table>
			<tr><td width="100">Unten</td><td><input type="radio" name="bc_dsgvo_cookies_position" <?php echo (get_option( "bc_dsgvo_cookies_position" ) == 1 ? 'checked' : ''); ?> value="1"></td></tr>
			<tr><td>Links</td><td><input type="radio" name="bc_dsgvo_cookies_position" <?php echo (get_option( "bc_dsgvo_cookies_position" ) == 2 ? 'checked' : ''); ?> value="2"></td></tr>
			<tr><td>Rechts</td><td><input type="radio" name="bc_dsgvo_cookies_position" <?php echo (get_option( "bc_dsgvo_cookies_position" ) == 3 ? 'checked' : ''); ?> value="3"></td></tr>
			<tr><td>Oben</td><td><input type="radio" name="bc_dsgvo_cookies_position" <?php echo (get_option( "bc_dsgvo_cookies_position" ) == 4 ? 'checked' : ''); ?> value="4"></td></tr>
		    </table>
		</tr>
		<tr><hr></tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Layout:', 'bc_dsgvo' ); ?></b></h3>
		    <table>
			<tr><td width="100">Block</td><td><input type="radio" name="bc_dsgvo_cookies_layout" <?php echo (get_option( "bc_dsgvo_cookies_layout" ) == 1 ? 'checked' : ''); ?> value="1"></td></tr>
			<tr><td>Edgeless</td><td><input type="radio" name="bc_dsgvo_cookies_layout" <?php echo (get_option( "bc_dsgvo_cookies_layout" ) == 2 ? 'checked' : ''); ?> value="2"></td></tr>
			<tr><td>Classic</td><td><input type="radio" name="bc_dsgvo_cookies_layout" <?php echo (get_option( "bc_dsgvo_cookies_layout" ) == 3 ? 'checked' : ''); ?> value="3"></td></tr>
		    </table>
		</tr>
		<tr><hr></tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Farben:', 'bc_dsgvo' ); ?></b></h3>
		    <table>
			<tr><td width="100">Hintergrund</td><td><input type="text" name="bc_dsgvo_cookies_color_back" value="<?php echo get_option( "bc_dsgvo_cookies_color_back" );?>"></td></tr>
			<tr><td>Text</td><td><input type="text" name="bc_dsgvo_cookies_color_text" value="<?php echo get_option( "bc_dsgvo_cookies_color_text" );?>"></td></tr>
			<tr><td>Buttons</td><td><input type="text" name="bc_dsgvo_cookies_color_button" value="<?php echo get_option( "bc_dsgvo_cookies_color_button" );?>"></td></tr>
			<tr><td>Button Text</td><td><input type="text" name="bc_dsgvo_cookies_color_buttontext" value="<?php echo get_option( "bc_dsgvo_cookies_color_buttontext" );?>"></td></tr>
		    </table>
		</tr>
		<tr><hr></tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Link zur Datenschutzerklärung:', 'bc_dsgvo' ); ?></b></h3>
		    <table width="100%">
			<tr><td width="100">Link</td><td><input style="width:100%;" type="text" name="bc_dsgvo_cookies_link" value="<?php echo get_option( "bc_dsgvo_cookies_link" );?>"></td></tr>
		    </table>
		</tr>
		<tr><hr></tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Texte (Cookie Hinweis):', 'bc_dsgvo' ); ?></b></h3>
		    <table width="100%">
			<tr>
			    <td width="100">Nachricht</td>
			    <td>
				<input style="width:100%;" type="text" name="bc_dsgvo_cookies_text_message" value="<?php echo get_option( "bc_dsgvo_cookies_text_message" );?>">
				<small><i>Beispiel: Diese Webseite verwendet Cookies für die fehlerfreie Funktion der Webseite. Sie erklären sich mit der Nutzung von wichtigen Cookies einverstanden wenn Sie diese Webseite nutzen.</i></small>
			    </td>
			</tr>
			<tr><td width="100">OK Button</td><td><input style="width:100%;" type="text" name="bc_dsgvo_cookies_text_ok" value="<?php echo get_option( "bc_dsgvo_cookies_text_ok" );?>"></td></tr>
			<tr><td width="100">Link Text</td><td><input style="width:100%;" type="text" name="bc_dsgvo_cookies_text_link" value="<?php echo get_option( "bc_dsgvo_cookies_text_link" );?>"></td></tr>
		    </table>
		</tr>
		<?php
	    }
	    if( $active_tab == 'ga' ) {
		?>
		<tr><h2 class="shadow2">Google Analytics inclusive OPT-Out</h2></tr>
		<tr>
		<p>
		Eine datenschutzkonforme Nutzung von Google Analytics ist nur mit der Code-Erweiterung „anonymizeIp“ möglich. Der von Google vorgegebene Tracking-Code erfüllt 
		nicht die Anforderungen zum Datenschutz, deshalb muss der jeweilige Google Analytics Tracking-Code händisch angepasst werden. Durch Nutzung der Code-Erweiterung 
		werden die letzten 8 Bit der IP-Adressen gelöscht und somit anonymisiert. Dadurch ist zwar weiterhin eine grobe Lokalisierung möglich, dies ist jedoch von den 
		deutschen Datenschutzbehörden anerkannt und akzeptiert.
		<br><br>
		Es ist notwendig, dass den Betroffenen die Möglichkeit eines Widerspruchs gegen die Erstellung von Nutzungsprofilen eingeräumt wird. 
		Google hat dafür ein Deaktivierungs-Add-on entwickelt, das aber nicht auf allen Endgeräten installierbar ist. Deshalb muss das Script 
		erweitert werden, damit ein Opt-Out-Cookie gesetzt wird. Dieses Cookie verhindert die zukünftige Datenerfassung. Da bei Universal Analytics 
		das Tracking geräteübergreifend erfolgt, ist es mit einem einfachen Opt-Out regelmäßig nicht getan. Der Nutzer muss seinen Widerspruch auf 
		allen genutzten Systemen erklären, damit keine geräteübergreifende Zuordnung seiner Nutzung zu der angelegten User-ID erfolgt.
		</p>
		</tr>
		<tr><hr></tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Google Analytics einbinden und OPT-OUT aktivieren:', 'bc_dsgvo' ); ?></b></h3>
		    Deaktiviert <input type="radio" name="bc_dsgvo_ga_enabled" <?php echo (get_option( "bc_dsgvo_ga_enabled" ) == 0 ? 'checked' : ''); ?> value="0">
		    Aktiviert <input type="radio" name="bc_dsgvo_ga_enabled" <?php echo (get_option( "bc_dsgvo_ga_enabled" ) == 1 ? 'checked' : ''); ?> value="1">
		</tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Tracking ID:', 'bc_dsgvo' ); ?></b></h3>
		    <table width="100%">
			<tr><td width="200">UA Nummer</td><td><input type="text" name="bc_dsgvo_ga_ua" value="<?php echo get_option( "bc_dsgvo_ga_ua" );?>"></td></tr>
		    </table>
		</tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Zusätzliche Tracking ID:', 'bc_dsgvo' ); ?></b></h3>
		    <table width="100%">
			<tr><td width="200">UA Nummer</td><td><input type="text" name="bc_dsgvo_ga_ua2" value="<?php echo get_option( "bc_dsgvo_ga_ua2" );?>"></td></tr>
		    </table>
		</tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Link zur Datenschutzerklärung:', 'bc_dsgvo' ); ?></b></h3>
		    <table width="100%">
			<tr><td width="200">Link</td><td><input type="text" style="width:100%;" name="bc_dsgvo_cookies_link" value="<?php echo get_option( "bc_dsgvo_cookies_link" );?>"></td></tr>
		    </table>
		</tr>
		<?php
	    }
	    if( $active_tab == 'dse' ) {
		?>
		<tr><h2 class="shadow2">Datenschutzerklärung generieren</h2></tr>
		<tr>
		<p>
		Unser Generator erlaubt es Ihnen, mit nur wenigen Klicks eine Datenschutzerklärung bzw. Datenschutzhinweise für Ihre Webseite zu erstellen, 
		selbstverständlich schon unter Berücksichtigung der EU-Datenschutz-Grundverordnung (DSGVO)!
		<br><br>
		Bitte beachten Sie, dass der Generator nicht auf alle denkbaren Spezialfälle eingehen kann und eine datenschutzrechtliche oder sonst 
		anwaltliche Beratung weder ersetzen kann noch will. Eine Haftung ist ausgeschlossen!
		</p>
		</tr>
		<hr>
		<h2>Die generierte Datenschutzerklärung können sie mit dem shortcode <b>[bc_dsgvo_dse]</b> in jede belibige Seite einbinden.</h2>
		<tr><hr></tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Wer ist die verantwortliche Stelle für Ihre Website?:', 'bc_dsgvo' ); ?></b></h3>
		    <textarea style="width: 200px; height: 200px;" name="bc_dsgvo_dse_1" placeholder="Name, Anschrift, E-Mail, Geschäftsführer"><?php echo get_option( "bc_dsgvo_dse_1" );?></textarea>
		</tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Löschen bzw. sperren Sie personenbezogene Daten nach Ablauf des Erhebungszwecks bzw. der Aufbewahrungsfrist?:', 'bc_dsgvo' ); ?></b></h3>
		    Nein <input type="radio" name="bc_dsgvo_dse_2" <?php echo (get_option( "bc_dsgvo_dse_2" ) == 0 ? 'checked' : ''); ?> value="0">
		    Ja <input type="radio" name="bc_dsgvo_dse_2" <?php echo (get_option( "bc_dsgvo_dse_2" ) == 1 ? 'checked' : ''); ?> value="1">
		</tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Erheben Sie bzw. Ihr Provider Zugriffsdaten?:', 'bc_dsgvo' ); ?></b></h3>
		    Nein <input type="radio" name="bc_dsgvo_dse_3" <?php echo (get_option( "bc_dsgvo_dse_3" ) == 0 ? 'checked' : ''); ?> value="0">
		    Ja <input type="radio" name="bc_dsgvo_dse_3" <?php echo (get_option( "bc_dsgvo_dse_3" ) == 1 ? 'checked' : ''); ?> value="1">
		    <br><small>z.B. Logfiles, in der Regel sollten sie das mit Ja beantworten</small>
		</tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Kann sich der Nutzer auf Ihrer Webseite registrieren lassen (NICHT Newsletter)?:', 'bc_dsgvo' ); ?></b></h3>
		    Nein <input type="radio" name="bc_dsgvo_dse_4" <?php echo (get_option( "bc_dsgvo_dse_4" ) == 0 ? 'checked' : ''); ?> value="0">
		    Ja <input type="radio" name="bc_dsgvo_dse_4" <?php echo (get_option( "bc_dsgvo_dse_4" ) == 1 ? 'checked' : ''); ?> value="1">
		</tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Erbringen Sie auf Ihrer Webseite kostenpflichtige Leistungen/Dienste?:', 'bc_dsgvo' ); ?></b></h3>
		    Nein <input type="radio" name="bc_dsgvo_dse_5" <?php echo (get_option( "bc_dsgvo_dse_5" ) == 0 ? 'checked' : ''); ?> value="0">
		    Ja <input type="radio" name="bc_dsgvo_dse_5" <?php echo (get_option( "bc_dsgvo_dse_5" ) == 1 ? 'checked' : ''); ?> value="1">
		</tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Setzen Sie bei der Übertragung Verschlüsselungsverfahren wie z. B. SSL ein?:', 'bc_dsgvo' ); ?></b></h3>
		    Nein <input type="radio" name="bc_dsgvo_dse_6" <?php echo (get_option( "bc_dsgvo_dse_6" ) == 0 ? 'checked' : ''); ?> value="0">
		    Ja <input type="radio" name="bc_dsgvo_dse_6" <?php echo (get_option( "bc_dsgvo_dse_6" ) == 1 ? 'checked' : ''); ?> value="1">
		</tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Können Nutzer auf Ihrer Website Kommentare z. B. in einem Blog hinterlassen?:', 'bc_dsgvo' ); ?></b></h3>
		    Nein <input type="radio" name="bc_dsgvo_dse_7" <?php echo (get_option( "bc_dsgvo_dse_7" ) == 0 ? 'checked' : ''); ?> value="0">
		    Ja <input type="radio" name="bc_dsgvo_dse_7" <?php echo (get_option( "bc_dsgvo_dse_7" ) == 1 ? 'checked' : ''); ?> value="1">
		</tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Können Nutzer sich auf Ihrer Website für einen Newsletter oder vergleichbare E-Mailing-Dienste anmelden?:', 'bc_dsgvo' ); ?></b></h3>
		    Nein <input type="radio" name="bc_dsgvo_dse_8" <?php echo (get_option( "bc_dsgvo_dse_8" ) == 0 ? 'checked' : ''); ?> value="0">
		    Ja <input type="radio" name="bc_dsgvo_dse_8" <?php echo (get_option( "bc_dsgvo_dse_8" ) == 1 ? 'checked' : ''); ?> value="1">
		</tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Steht den Nutzern der Website eine Kontaktmöglichkeit (z. B. Kontaktformular) zur Verfügung?:', 'bc_dsgvo' ); ?></b></h3>
		    Nein <input type="radio" name="bc_dsgvo_dse_9" <?php echo (get_option( "bc_dsgvo_dse_9" ) == 0 ? 'checked' : ''); ?> value="0">
		    Ja <input type="radio" name="bc_dsgvo_dse_9" <?php echo (get_option( "bc_dsgvo_dse_9" ) == 1 ? 'checked' : ''); ?> value="1">
		</tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Verwenden Sie Google Webfonts?:', 'bc_dsgvo' ); ?></b></h3>
		    Nein <input type="radio" name="bc_dsgvo_dse_10" <?php echo (get_option( "bc_dsgvo_dse_10" ) == 0 ? 'checked' : ''); ?> value="0">
		    Ja <input type="radio" name="bc_dsgvo_dse_10" <?php echo (get_option( "bc_dsgvo_dse_10" ) == 1 ? 'checked' : ''); ?> value="1">
		</tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Binden Sie Karten von Google Maps auf Ihrer Website ein?:', 'bc_dsgvo' ); ?></b></h3>
		    Nein <input type="radio" name="bc_dsgvo_dse_11" <?php echo (get_option( "bc_dsgvo_dse_11" ) == 0 ? 'checked' : ''); ?> value="0">
		    Ja <input type="radio" name="bc_dsgvo_dse_11" <?php echo (get_option( "bc_dsgvo_dse_11" ) == 1 ? 'checked' : ''); ?> value="1">
		</tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Binden Sie Youtube-Videos auf Ihrer Website ein?:', 'bc_dsgvo' ); ?></b></h3>
		    Nein <input type="radio" name="bc_dsgvo_dse_12" <?php echo (get_option( "bc_dsgvo_dse_12" ) == 0 ? 'checked' : ''); ?> value="0">
		    Ja <input type="radio" name="bc_dsgvo_dse_12" <?php echo (get_option( "bc_dsgvo_dse_12" ) == 1 ? 'checked' : ''); ?> value="1">
		</tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Nutzen Sie Google AdWords und das Conversion Tracking?:', 'bc_dsgvo' ); ?></b></h3>
		    Nein <input type="radio" name="bc_dsgvo_dse_13" <?php echo (get_option( "bc_dsgvo_dse_13" ) == 0 ? 'checked' : ''); ?> value="0">
		    Ja <input type="radio" name="bc_dsgvo_dse_13" <?php echo (get_option( "bc_dsgvo_dse_13" ) == 1 ? 'checked' : ''); ?> value="1">
		</tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Setzen Sie die Retargeting-Funktion Google Remarketing ein?:', 'bc_dsgvo' ); ?></b></h3>
		    Nein <input type="radio" name="bc_dsgvo_dse_14" <?php echo (get_option( "bc_dsgvo_dse_14" ) == 0 ? 'checked' : ''); ?> value="0">
		    Ja <input type="radio" name="bc_dsgvo_dse_14" <?php echo (get_option( "bc_dsgvo_dse_14" ) == 1 ? 'checked' : ''); ?> value="1">
		</tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Ist in Ihrer Organisation ein Datenschutzbeauftragter bestellt? Wenn ja, geben Sie hier bitte die Kontaktdaten ein. Wenn nein, nennen Sie eine allgemeine Kontaktmöglichkeit für Datenschutzfragen zu Ihrem Unternehmen.:', 'bc_dsgvo' ); ?></b></h3>
		    <textarea style="width: 200px; height: 200px;" name="bc_dsgvo_dse_15" placeholder="Datensachutzbeauftragter oder Kontaktdaten verantwortlicher"><?php echo get_option( "bc_dsgvo_dse_15" );?></textarea>
		</tr>
		
		<?php
	    }
	    if( $active_tab == 'imprint' ) {
		?>
		<tr><h2 class="shadow2">Impressum verwalten</h2></tr>
		<tr>
		<p>
		Geschätzt 90% aller Webseiten und Blogs unterliegen der Impressumspflicht nach TMG, auch Anbieterkennzeichnung genannt. Impressumsverstöße sind seit Jahren einer der Abmahnklassiker im Netz.
		</p>
		</tr>
		<hr>
		<h2>Das generierte Impressum können sie mit dem shortcode <b>[bc_dsgvo_imprint]</b> in jede belibige Seite einbinden.</h2>
		<tr><hr></tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Betreiber:', 'bc_dsgvo' ); ?></b></h3>
		    <table width="100%">
			<tr><td width="200"><?php _e('Firmenbezeichnung:', 'bc_dsgvo' ); ?></td><td><input style="width:100%;" type="text" name="bc_dsgvo_imprint_company" value="<?php echo get_option( "bc_dsgvo_imprint_company" );?>"></td></tr>
			<tr><td width="200"><?php _e('Anschrift:', 'bc_dsgvo' ); ?></td><td><input style="width:100%;" type="text" name="bc_dsgvo_imprint_address" value="<?php echo get_option( "bc_dsgvo_imprint_address" );?>"></td></tr>
			<tr><td width="200"><?php _e('Postleitzahl:', 'bc_dsgvo' ); ?></td><td><input style="width:100%;" type="text" name="bc_dsgvo_imprint_zip" value="<?php echo get_option( "bc_dsgvo_imprint_zip" );?>"></td></tr>
			<tr><td width="200"><?php _e('Ort:', 'bc_dsgvo' ); ?></td><td><input style="width:100%;" type="text" name="bc_dsgvo_imprint_city" value="<?php echo get_option( "bc_dsgvo_imprint_city" );?>"></td></tr>
		    </table>
		</tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Rechtsangaben:', 'bc_dsgvo' ); ?></b></h3>
		    <table width="100%">
			<tr><td width="200"><?php _e('Geschäftsführer:', 'bc_dsgvo' ); ?></td><td><input style="width:100%;" type="text" name="bc_dsgvo_imprint_ceo" value="<?php echo get_option( "bc_dsgvo_imprint_ceo" );?>"></td></tr>
			<tr><td width="200"><?php _e('Registergericht:', 'bc_dsgvo' ); ?></td><td><input style="width:100%;" type="text" name="bc_dsgvo_imprint_court" value="<?php echo get_option( "bc_dsgvo_imprint_court" );?>"></td></tr>
			<tr><td width="200"><?php _e('Registernummer:', 'bc_dsgvo' ); ?></td><td><input style="width:100%;" type="text" name="bc_dsgvo_imprint_courtnumber" value="<?php echo get_option( "bc_dsgvo_imprint_courtnumber" );?>"></td></tr>
			<tr><td width="200"><?php _e('Umsatzsteuer ID:', 'bc_dsgvo' ); ?></td><td><input style="width:100%;" type="text" name="bc_dsgvo_imprint_taxid" value="<?php echo get_option( "bc_dsgvo_imprint_taxid" );?>"></td></tr>
		    </table>
		</tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Kontaktangaben:', 'bc_dsgvo' ); ?></b></h3>
		    <table width="100%">
			<tr><td width="200"><?php _e('Telefon:', 'bc_dsgvo' ); ?></td><td><input style="width:100%;" type="text" name="bc_dsgvo_imprint_phone" value="<?php echo get_option( "bc_dsgvo_imprint_phone" );?>"></td></tr>
			<tr><td width="200"><?php _e('Telefax:', 'bc_dsgvo' ); ?></td><td><input style="width:100%;" type="text" name="bc_dsgvo_imprint_fax" value="<?php echo get_option( "bc_dsgvo_imprint_fax" );?>"></td></tr>
			<tr><td width="200"><?php _e('E-Mail:', 'bc_dsgvo' ); ?></td><td><input style="width:100%;" type="text" name="bc_dsgvo_imprint_email" value="<?php echo get_option( "bc_dsgvo_imprint_email" );?>"></td></tr>
		    </table>
		</tr>
		<tr>
		    <h3 class="shadow"><b><?php _e('Sonderangaben:', 'bc_dsgvo' ); ?></b></h3>
		    <table width="100%">
			<tr><td width="200"><?php _e('Aufsichtsbehörden:', 'bc_dsgvo' ); ?></td><td><input style="width:100%;" type="text" name="bc_dsgvo_imprint_supervisor" value="<?php echo get_option( "bc_dsgvo_imprint_supervisor" );?>"></td></tr>
			<tr><td width="200"><?php _e('Kammer:', 'bc_dsgvo' ); ?></td><td><input style="width:100%;" type="text" name="bc_dsgvo_imprint_chamber" value="<?php echo get_option( "bc_dsgvo_imprint_chamber" );?>"></td></tr>
			<tr><td width="200"><?php _e('Berufsbezeichnung und Staat der Verleihung:', 'bc_dsgvo' ); ?></td><td><input style="width:100%;" type="text" name="bc_dsgvo_imprint_origin" value="<?php echo get_option( "bc_dsgvo_imprint_origin" );?>"></td></tr>
			<tr><td width="200"><?php _e('Sonstiges:', 'bc_dsgvo' ); ?></td><td><input style="width:100%;" type="text" name="bc_dsgvo_imprint_other" value="<?php echo get_option( "bc_dsgvo_imprint_other" );?>"></td></tr>
		    </table>
		</tr>
		<?php
	    }
	    if( $active_tab == 'fonts' ) {
		?>
		<tr><h2 class="shadow2">Google Fonts umleiten</h2></tr>
		<tr>
		<p>
		Bei der Verwendung von Google Fonts wird die IP Adresse Ihres Besuchers an die Server von Google übertragen. Neben der Tatsache das die Daten auf einem Server außerhalb der EU 
		gespeichert werden, kann Google die Bewegungen im Netz verfolgen und ein Nutzerprofil erstellen.
		
		</p>
		<h3 class="shadow">Ihre persönliche Proxy URL lautet:</h3>
		<code>https://fonts.woolink.de/css?token=<?=sha1($_SERVER['HTTP_HOST'])?>&site=<?=md5($_SERVER['HTTP_HOST'])?>&family=</code>
		<br>
		<b><a href="https://billing.woolink.de/cart.php" target="_blank">Diese URL funktioniert erst nach Kauf der Jahreslizenz (10,00 &euro; / Jahr) und Freischaltung Ihrer Domain durch uns.</a></b>
		<h3 class="shadow"><b><?php _e('Globale Google Fonts', 'bc_dsgvo' ); ?></b></h3>
		<tr>
		    <h3><b><?php _e('Fonts Proxy aktivieren:', 'bc_dsgvo' ); ?></b></h3>
		    Deaktiviert <input type="radio" name="bc_dsgvo_proxy_enabled" <?php echo (get_option( "bc_dsgvo_proxy_enabled" ) == 0 ? 'checked' : ''); ?> value="0">
		    Aktiviert <input type="radio" name="bc_dsgvo_proxy_enabled" <?php echo (get_option( "bc_dsgvo_proxy_enabled" ) == 1 ? 'checked' : ''); ?> value="1">
		</tr>
		<p>
		Mit dieser Funktion können sie alle Standard-Einbindungen von Google Fonts durch den WooLink Fonts Proxy umleiten der in Deutschland steht und keine IP Adressen Ihrer Besucher an Google Server übermittelt.
		<br>
		Der Proxy Server speichert zudem keinerlei Zugriffe in Logfiles.
		</p>
		<h3 class="shadow"><b><?php _e('Revolution Slider', 'bc_dsgvo' ); ?></b></h3>
		<p>
		    <b>Um Google Fonts in Revolution SLider umzuleiten, besuchen Sie bitte die globalen Einstellungen des Revolution Sliders und ändern Sie die URL für Google Fonts.</b>
		    Sollten Sie keinen alternativen Dienst zur Hand haben, bieten wir Ihnen diesen Dienst als Mietlizenz an.
		    <br><br>
		</p>
		<img src="<?=plugin_dir_url( __FILE__ );?>img/5c1a95c6303beb2cc68cc8400ac6ffe0.png">
		</tr>
		<tr><hr></tr>
		<?php
	    }
	    if( $active_tab == 'forms' ) {
		?>
		<tr><h2 class="shadow2">Formulare DSGVO konform</h2></tr>
		<tr>
		    <p>
			Vor der Übermittlung von Formulardaten muss der Absender auf die Datenschutzerklärung aufmerksam gemacht werden. Um zu bestätigen das der Absender die Datenschutzerklärung 
			angeboten bekommen hat, diese im idealfall gelesen und akzeptiert hat, sollte eine CheckBox in jedes Formular integriert werden um dies zu bestätigen und zu verhindern dass 
			das Formular ohne Zustimmung abgesendet werden kann..
		    </p>
		    <p>
			<h3>Urteil des OLG Köln (Az.: 6 U 121/15)</h3>
			Nach § 13 Abs. 1 S. 1 TMG hat der Diensteanbieter den Nutzer zu Beginn des Nutzungsvorgangs über Art, Umfang und Zwecke der Erhebung und Verwendung personenbezogener 
			Daten sowie über die Verarbeitung seiner Daten im EU-Ausland in allgemein verständlicher Form zu unterrichten, sofern eine solche Unterrichtung nicht bereits erfolgt ist. 
			Nach Abs. 2 kann die Einwilligung elektronisch erklärt werden, wenn der Diensteanbieter sicherstellt, dass 1. der Nutzer seine Einwilligung bewusst und eindeutig erteilt hat, 
			2. die Einwilligung protokolliert wird, 3. der Nutzer den Inhalt der Einwilligung jederzeit abrufen kann und 4. der Nutzer die Einwilligung jederzeit mit Wirkung für die 
			Zukunft widerrufen kann. Nach § 13 Abs. 3 S. 1 TMG hat der Diensteanbieter den Nutzer vor Erklärung der Einwilligung auf sein Widerrufsrecht hinzuweisen.
		    </p>
		</tr>
		<tr><hr></tr>
		<tr>
		    <h3><b><?php _e('Ninja Forms:', 'bc_dsgvo' ); ?></b></h3>
		    Deaktiviert <input type="radio" name="bc_dsgvo_forms_ninja_enabled" <?php echo (get_option( "bc_dsgvo_forms_ninja_enabled" ) == 0 ? 'checked' : ''); ?> value="0">
		    Aktiviert <input type="radio" name="bc_dsgvo_forms_ninja_enabled" <?php echo (get_option( "bc_dsgvo_forms_ninja_enabled" ) == 1 ? 'checked' : ''); ?> value="1">
		</tr>
		<tr>
		    <h3><b><?php _e('Caldera Forms:', 'bc_dsgvo' ); ?></b></h3>
		    <a href="https://calderaforms.com/doc/setting-caldera-forms-gdpr-data-requests/" target="_blank">Bitte lesen sie die Caldera Forms Dokumentation zur DSGVO!</a>
		</tr>
		<?php
	    }
	?>
	</tbody>
	</table>
	<p class="submit"><input type="submit" value="<?php echo _e( 'Speichern'); ?>" class="button-primary" name="Submit"></p>
    </form>
    <?php
    echo '</div>';
}