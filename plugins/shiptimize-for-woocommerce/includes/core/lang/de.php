<?php 
return array(
	'automaticexport' => 'Automatischer Export',
	'automaticexportdescription' =>  'Alle Aufträge mit dem folgenden Status automatisch an Shiptimize senden',
	'Credentials' => 'Berechtigungsnachweise',
	'Export' => 'Exportieren',
	'Export to' => 'Exportieren nach',
	'settings' => 'Einstellungen',  
	'Settings' => 'Einstellungen',
	'help' => 'Hilfe',  
	'Export Preset Orders' => 'Voreingestellte Bestellstatus exportieren',
	'Export Preset Orders to' =>  'Voreingestellte Aufträge exportieren nach',
	'printlabel' => 'Etikett drucken', 
	'If you do not have a %s account' => 'Wenn Sie kein %s-Konto haben',
	'Click Here' => 'hier klicken',
	'Public Key' => 'Öffentlicher API-Schlüssel',
	'Private Key' => 'Privater API-Schlüssel', 
	'A new token will be automatically requested when this one expires' => 'Ein neues Token wird automatisch angefordert, wenn dieses abläuft.',
	'exportdescription' => 'In der Bestellübersicht gibt es zwei Möglichkeiten, Bestellungen an Shiptimize zu exportieren:

<p><b>Voreingestellte Bestellstatus nach Shiptimize exportieren</b><br/>
Mit dieser Schaltfläche exportiert das Plugin alle Bestellungen mit einem Status, der dem in den Plugin-Einstellungen eingestellten Bestellstatus entspricht, auf einmal.</p>

<p><b>Export nach Shiptimize</b><br/>
Diese Option befindet sich in der Dropdown-Liste "Massenaktionen". Diese Aktion exportiert alle Aufträge, die in der Auftragsübersicht mit einem Häkchen versehen sind, unabhängig vom Status.</p>',
	'statusdescription' => "In der Bestellübersicht fügt das Plugin eine zusätzliche Spalte mit dem Namen 'Shiptimize Status / Aktion' hinzu. In dieser Spalte wird ein Status pro Auftrag angezeigt.
<br/><b>Bewegen Sie den Mauszeiger über das Statussymbol, um den Exportverlauf der Bestellung zu sehen</b><br/><br/>Der Exportstatus wird im Folgenden aufgeführt',
	'When you click \"Export Preset Orders\" in the orders view, export all orders not exported successfully, with status' => 'Wenn Sie in der Auftragsansicht auf \"Vordefinierte Aufträge exportieren\" klicken, werden alle Aufträge, die nicht erfolgreich exportiert wurden, mit dem Status",

    'notexporteddescription' => 'Bestellung nicht exportiert', 
    'successdescription' => 'Bestellung erfolgreich exportiert', 
    'exporterrordescription' => 'Bestellung wurde <u>mit Fehlern exportiert</u>',
    'notprinteddescription' => 'Noch kein Versandetikett',
    'printsuccesseddescription' => 'Versandetikett erfolgreich erstellt',
    'printerrordescription' => 'Anforderung von Versandetiketten <u>führte zu Fehlern</u>',

    'labeltermsintro' => "<p>Neben der Möglichkeit, Sendungen manuell oder automatisch nach Shiptimize zu exportieren und von dort aus Etiketten zu erstellen, ist es auch möglich, Etiketten direkt aus WooCommerce heraus zu erstellen.</p>
        <p>Wenn Sie dies wünschen, müssen Sie auf der Registerkarte \"Einstellungen\" das Kontrollkästchen unter \"Versandetiketten aus WooCommerce erstellen\" aktivieren.</p>  
        <p>Um Etiketten aus der Auftragsübersicht zu erstellen, klicken Sie auf die Schaltfläche \"Etikett drucken\": </p>", 
    'labelterms' => 'In diesem Moment geschieht Folgendes:<br/>
    <ol class=\"shiptimize-list\">
	<li>Die Bestelldaten werden nach Shiptimize exportiert, so dass nach erfolgreicher Übermittlung ein Versandetikett an WooCommerce zurückgeschickt werden kann.</li>
	<li>Das Format des zurückgesendeten Versandetiketts kann in der Shiptimize Versandplattform über "Einstellungen" > "Druckeinstellungen" > "Etikettendruckformat" eingestellt werden.</li>
	<li>Falls ein Bestellung nicht exportiert werden kann, wird eine Fehlermeldung mit zusätzlichen Informationen darüber ausgegeben, warum der Export nicht erfolgreich war.</li>
	<li>Falls eine Bestellung bereits exportiert wurde, aber noch kein Versandetikett erstellt wurde, werden die Bestelldaten erneut gesendet. Alle Änderungen der Adresse werden aktualisiert.</li>
	<li>Hat sich der Käufer in der Kaufabwicklung für einen bestimmten Versanddienstleister und mögliche Versandoptionen entschieden, werden diese Auswahl(en) bei der Erstellung eines Versandetiketts berücksichtigt.</li>
	<li>Wenn keine Auswahl für einen bestimmten Versanddienst und mögliche Versandoptionen getroffen wurde, wird automatisch der Versanddienst ausgewählt, wie er in der Shiptimize Versandplattform unter "Einstellungen" > "Standardeinstellungen" > "Versandart" > "Standardversandart" eingestellt ist.</li>
	<li>Wenn unter "Standardversandart" kein Versanddienst ausgewählt ist, wird automatisch der erste verfügbare Versanddienst ausgewählt.</li>
	</ol>',
    'labelbuttondescription' => 'Um einen Etikettendruck auszulösen, klicken Sie in der Auftragsliste auf die Schaltfläche Etikett', 
    'order'  => 'Bestellung', 
    'label'  => 'Versandetikett',  
	'If a google key is provided the map served will be a google map else an openmap will be shown' => 'Wenn ein Google-Schlüssel angegeben wird, wird eine Google-Karte angezeigt, andernfalls eine OpenMap.',
	'Carriers Available In your contract' => 'Verfügbare Versandoptionen In Ihrem Vertrag', 
	'Has Pickup' => 'Hat Pickup',
	'You can add them to' => 'Sie können die oben genannten Versandoptionen hin fügen zu den',
	"Don't forget to set the appropriate cost for each carrier if you don't have free shipping for all orders" => 'Vergessen Sie nicht, die richtigen Gebühren einzustellen.',
	'shipping zones' => 'Versandzonen',
	'printlabeltitle' => 'Versandetikett drucken',
	'labelagree' => 'Ich habe den Hilfeabschnitt auf der nächsten Registerkarte gelesen und verstehe, wie Versandetiketten von Woocommerce gedruckt werden.',

    'labelbulkprintitle' => 'Etikett im Massendruck',
    'labelbulkprint' => 'Wenn Sie Etiketten für mehrere Bestellungen auf einmal drucken möchten. In der Auftragsliste:
    <ol class="shiptimize-list">
    <li>Wählen Sie die Aufträge aus, für die Sie ein Etikett drucken möchten </li>
    <li>Wählen Sie Shiptimize: Etikett drucken aus der Dropdown-Liste der Mehrfachaktionen aus.</li>
    <li>Klicken Sie auf "Übernehmen".</li>
    </ol>',

	'pickupbehaviour' => 'Die Auswahl eines Pickup-Points ist',
    'pickuppointbehavior0' => 'Optional',
    'pickuppointbehavior1' => 'Obligatorisch',
    'pickuppointbehavior2' => 'Nicht anzeigen',
    'mandatorypointmsg' => "Bitte wählen Sie einen Abholort oder eine andere Versandoption",
    'pickuppointsoptions' => "Paketshop",
    'extraoptions' => 'zusätzliche Optionen',
    'service_level' => 'Serviceniveau',
    'cashservice' => 'Lieferung gegen Nachnahme',
    'sendinsured' => 'versichert',
    'hidenotfree' => 'Wenn mindestens eine Versandmethode mit Kosten 0 verfügbar ist, werden alle Versandmethoden mit Kosten > 0 ausgeblendet.',
    'hidenotfree' => 'Versandmethoden ausblenden',
    'useapititle' => 'WP API verwenden',
    'usewpapi' => "Verwenden Sie die API von woordpress, um Aktualisierungen von Bestellungen zu senden.
 <br/><small>Weitere Informationen finden Sie auf der Registerkarte Hilfe</small>",
    'useapihelp' => '<p>Wenn Sie den Auftragsstatus so konfiguriert haben, dass er bei bestimmten Ereignissen automatisch aktualisiert wird (beim Import, bei der Erstellung des Etiketts und/oder bei der Lieferung), Sie aber bei den Aktualisierungen der Sendungsverfolgung in Ihrem GLS-Konto "nicht gefunden" sehen, führen Sie bitte die folgenden Schritte aus:
<ol>
    <li>Aktivieren Sie die Option "WordPress-API für Auftragsaktualisierungen verwenden" unten.%s </li>
    <li>Gehen Sie zu Ihrem GLS-Konto und erstellen Sie neue Schlüssel unter "Einstellungen" > "Integrationen" > "Schlüsselverwaltung" und aktivieren Sie die Schlüssel.</li>
    <li>Fügen Sie diese neuen Schlüssel in das Feld GLS Credentials in den GLS-Einstellungen hier in WordPress ein und klicken Sie auf die Schaltfläche "Änderungen speichern".</li>
</ol>', 
'hideifclasspresent' => 'Wenn mindestens ein Artikel im Warenkorb diese Klassen enthält, zeigen Sie diese Methode nicht an',
    'exportvirtualtitle' => 'Virtuelle Produkte und virtuelle Aufträge',
    'exportvirtualorders' => 'Exportaufträge, die nur virtuelle Produkte enthalten',
    'exportvirtualproducts' => 'Beim Exportieren virtuelle Produkte an Bestellungen anhängen',
    'mapfieldmandatory' => 'obligatorisch. Bitte definieren Sie einen Wert.',
    'multiorderlabelwarn' => 'Wenn Sie mehr als ein Etikett gleichzeitig drucken möchten, verwenden Sie bitte die Anwendung'
);