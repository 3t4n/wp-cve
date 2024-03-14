=== Wetterwarner ===
Contributors: bocanegra
Donate link: https://it93.de/unterstuetzen/
Tags: Wetter, Warnung, Sturm, Wetterdienst, Unwetter
Requires at least: 5.8
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 2.7.2
License: GPLV2
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.de.html

Wetterwarner zeigt amtliche Wetterwarnungen für Deine eingestellte Region in einem Widget an.

== Description ==
Wetterwarner zeigt amtliche Wetterwarnungen für Deine eingestellte Region in einem Widget an.

Optional kann eine Wetterkarte angezeigt werden. Die Karte aktualisiert sich selbstständig und wird herausgegeben vom Deutschen Wetterdienst.

Funktionen im Überblick:
* Anzeige von beliebig vielen Wetterwarnungen
* Widget Texte komplett frei einstellbar
* Einfache Integration in Wordpress Theme
* Cache Funktion welche die Daten auf deinem Webspace zwischengespeichert, um die benötigten Daten schneller zu laden
* Optional: Wetterkarte in anpassbarer Größe
* Optional: Mouseover Effekt - Erweiterter Warnungstext wird angezeigt
* Optional: Icons vor den Wettermeldungen
* Und vieles mehr...

[Live Demo](https://it93.de/projekte/wetterwarner/demo)

= Wichtige Informationen zum Bild =
Die verwendete Wetterkarte entstammt der Seite www.dwd.de - Beim Aufrufen des Widgets wird das Bild (SSL verschlüsselt) von einer externen Seite geladen. Sämtliche Bildrechte liegen bei dem Betreiber. Ich weise ausdrücklich darauf hin, im Namen des Betreibers, dass das Bild nicht verändert werden darf!
[Weitere Informationen](https://www.wettergefahren.de/copyright.html)

= Wichtige Informationen zur Informations Quelle =
Als Quelle der Informationen werden RSS Feeds der Webseite http://wettwarn.de genutzt. Diese Seite nutzt amtliche Meldungen des Deutschen Wetterdienst. Sämtliche Urheberrechte verbleiben bei dem Betreiber.

= Weitere Informationen =
Dieses Plugin sollte in keinem Fall einer amtlichen Informationsquelle vorgezogen werden. Die Meldungen können teilweise gekürzt sein.

Das Plugin "Wetterwarner" wurde nach bestem Wissen und Gewissen erstellt und getestet. Ich hafte nicht für entstadene Schäden, Fehlfunktionen, Verstöße gegen geltendes Urheber- und/oder Datenschutzrecht. Nur für die Nutzung in Deutschland vorgesehen. Generelle Nutzung auf eigene Gefahr! In keinster Weise steht dieses Plugin in Verbindung mit der gleichnamigen Android/iOS App. 
Alle vom Plugin initiierte Verbindungen zu externen Servern werden per SSL abgesichert.

= Credits  =
[Font Awesome](http://fontawesome.io) by Dave Gandy
[PopUp text boxes](http://nicolashoening.de?twocents&nr=8) by Nicolas Höning
[Weather Icons project](https://erikflowers.github.io/weather-icons/) by Erik Flowers
[wp-color-picker-alpha](https://github.com/kallookoo/wp-color-picker-alpha)

== Installation ==
1. Lade die Dateien unverändert aus der ZIP-Datei in das Wordpress Pluginverzeichnis: /wp-content/plugins oder installiere es direkt über die Plugin Seite deines Blogs.
2. Aktiviere das Plugin im Menü "Installierte Plugins" deines Blogs
3. Anschließend kannst du es unter "Design" --> "Widgets" nutzen

Stelle sicher, dass der /tmp/ Ordner im Wetterwarnter Verzeichnis Schreibberechtigungen besitzt. (775) Allgemeine Optionen findest du direkt in der Widget Konfiguration. Weiteres unter Einstellungen > Wetterwarner.

== Frequently Asked Questions ==

= Welche Feed ID hat meine Stadt? =
Besuche hierfür die Seite: http://wettwarn.de/warnregion, wähle die Warnregion und trage die ID der Region in die Widget Konfiguration ein. 
Weitere Hilfe findest du in der Dokumentation: https://it93.de/projekte/wetterwarner/dokumentation/

= Warum muss die Feed ID genau von wettwarn.de sein? =
Die RSS Feeds dieser Seite werden als Quelle der Meldungen genutzt.

= Wie erreiche ich den Entwickler? | Fehler melden =
Nutze das WordPress [Support Forum](https://wordpress.org/support/plugin/wetterwarner/) oder nutze das [Kontaktformular](https://it93.de/kontakt/) auf meiner Webseite

= Wie kann im meine Einstellungen testen? =
Einfach als Feed ID "100" eintragen und schon werden Beispielmeldungen angezeigt!

== Screenshots ==
1. Widget im Front-End Bereich
2. Widget Konfiguration im Back-End
3. Weitere Einstellungen

== Changelog ==
= 2.7.2 =
* Bugfix: Daten nur sporadisch aktualisiert

= 2.7.1 =
* Bugfix: Falsche Verlinkung der Meldungen behoben
* Bugfix: Widget Checkbox-Einstellungen wurden unter umständen falsch geladen

= 2.7 =
* Optimierung: Benötigte externe Daten werden nun automatisch im Hintergrund aktualisiert (WP-Cron)
* Bugfix: Fehlermeldung nach Update behoben
* Weitere Quellcode Optimierungen

= 2.6 =
* Optimierung: Die Wetterkarten werden nun als optimierte WebP-Dateien geladen
* Optimierung: Konfigurations-Test in WordPress Webseiten Zustand hinzugefügt
* Quellcode umfassend überarbeitet
* Kompatibilität zu WordPress 6.4 sichergestellt

= 2.5.1 =
* Bugfix: Diverse PHP Warnungen behoben

= 2.5 =
* Bugfix: Warnung "Undefined variable $tooltip_code" behoben
* Optimierung: Aktualisierung Color-Picker | Hintergrundfarben können nun (wieder) einen Deckkraft-Wert (Transparenz) über den Color-Picker in den Einstellungen erhalten
* Optimierung: Debug Info jetzt dauerhaft über die Einstellungen sichtbar
* Optimierung: Methode hinzugefügt um Browser-Caching der Wetterkarte zu verhindern
* Weitere Quellcode Optimierungen
 
= 2.4.2 =
* Bugfix: Warnung unter PHP 8.1 behoben
* Kompatibilität zu WordPress 6.2 sichergestellt

= 2.4.1 =
* Kompatibilität zu WordPress 6.1 sichergestellt

= 2.4 =
* Bugfix: SSL Fehler behoben
* Kompatibilität zu WordPress 6.0 sichergestellt