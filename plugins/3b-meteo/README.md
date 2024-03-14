# 3B Meteo #
**Contributors:** [andreapernici](https://profiles.wordpress.org/andreapernici/)  
**Tags:** meteo, meteo italia, previsioni del tempo, meteo sul sito, previsioni meteo, 3B meteo, block meteo  
**Requires at least:** 5.6  
**Requires PHP:** 7.2  
**Tested up to:** 5.9  
**Stable tag:** 1.1.0  
**License:** GPL-3.0-or-later  
**License URI:** https://www.gnu.org/licenses/gpl-3.0.html  

Permette di aggiungere i widget meteo per le previsioni del tempo sul tuo sito in vari formati.

## Description ##

Permette di aggiungere i widget meteo per le previsioni del tempo sul tuo sito in vari formati.
I widget sono personalizzabili nei colori e possono essere inseriti nei post, nelle pagine e nelle sidebar.

I widget meteo disponibili sono in tre versioni, Old, Classic e Flex.
Old per mantenere retro compatibilità ha anche la versione WordPress Widget e Shortcode oltre al blocco dedicato, più sotto trovi i parametri da usare nello shortcode.
Classic ha solo la versione blocco dedicato.
Old e Classic utilizzano ancora la tecnologia iFrame html per mostrare i dati meteo.

La versione blocco della tipologia Old, Classic e Flex è configurabile tramite i settings forniti dal nuovo editor a blocchi.

Il nuovo formato flex invece è solo disponibile come blocco, questo nuovo formato viene fornito con numerose
possibilità di personalizzazione, stile, colore e tanto altro.

Una volta selezionato nell'editor se è la prima volta che si installa si dovrà procedere alla registrazione di
una API key direttamente dall'interfaccia del blocco seguendo la procedura guidata in due passaggi, nulla di complesso.
Effettuata la registrazione tramite il blocco verrà inviata una mail, che sarà da conservare, con la chiave da inserire
nell'interfaccia del blocco per terminare l'autenticazione e ricevere le informazioni meteo.
Finita la registrazione si potrà utilizzare il blocco in tutte le sue funzionalità senza limiti.


# Come utilizzare lo shortcode per la tipologia Old #

Tramite gli shortcodes è possibile inserire all’interno dei vostri post o delle vostre pagine i widget meteo semplicemente inserendo il codice [nomeshortcode].
Sono disponibili 14 shortcode che oltre ad essere elenca qui sono anche nelle anteprime di esempio dei vari widget:

Previsioni meteo località Compatti
Ti consente di visualizzare il tempo per la tua località, in forma compatta.

* **[trebi-a1]** - 1 giorno: Previsioni del giorno in corso per località in formato compatto
* **[trebi-a2]** - 6 giorni: Previsioni a 6 giorni per la tua località in formato compatto.
* **[trebi-a3]** - 7 Giorni: Previsioni della settimana per località in formato compatto.

Previsioni meteo località Dati in diretta e Tutte le località
Ti consente di visualizzare il tempo in atto per la tua località, in forma compatta, o le previsioni a sei giorni per tutte le località.

* **[trebi-b1]** - Dati in diretta: Gli ultimi dati rilevati dalle centraline meteo della tua località.
* **[trebi-b2]** - Tutte le località: Previsioni a sei giorni per località, in formato esteso con campo di ricerca.

Previsioni meteo località Estese
Ti consente di visualizzare, in formato esteso, le previsioni del tempo per la tua località.

* **[trebi-c1]** - 7 giorni: Previsioni della settimana per località, divise per quattro fascie giornaliere.
* **[trebi-c2]** - Orario: Previsioni della settimana per località, con dettaglio orario.

Previsioni meteo regionali
Ti consente di visualizzare le previsioni del tempo per la tua regione.

* **[trebi-d1]** - Compatto: Previsioni del giorno in corso nella tua regione, in formato compatto.
* **[trebi-d2]** - 7 giorni: Previsioni della settimana nella tua regione, in formato esteso.

Previsioni meteo località marine
Ti consente di visualizzare le previsioni marine per la tua località.

* **[trebi-e1]** - 1 giorno: Previsioni marine del giorno in corso nella tua località, in formato compatto.
* **[trebi-e2]** - 7 giorni: Previsioni marine della settimana nella tua località, in formato esteso.

Previsioni meteo regionali marine
Ti consente di visualizzare le previsioni marine per la tua regione.

* **[trebi-f1]** - 7 giorni: Previsioni marine della settimana nella tua regione, in formato esteso.

Previsioni meteo neve
Ti consente di visualizzare il bollettino neve per la tua località.

* **[trebi-g1]** - 1 giorno: Bollettino neve del giorno in corso nella tua località, in formato compatto.
* **[trebi-g2]** - 7 giorni: Bollettino neve a 7 giorni nella tua località, in formato esteso.

E’ inoltre possibile utilizzare gli shortcode anche all’interno del widget di testo di default di WordPress.

Gli shortcodes possono anche essere personalizzati utilizzando i seguenti parametri:

Esempio località:
[nomeshortcode loc=”codice località” c1=”ffffff” c2=”ffffff” c3=”cccccc” b1=”3b3b3b” b2=”000000″ b3=”000000″]

Esempio regione:
[nomeshortcode idreg=”codice regione” c1=”ffffff” c2=”ffffff” c3=”cccccc” b1=”3b3b3b” b2=”000000″ b3=”000000″]

* **idreg** = codice regione | loc=codice localita
* **c1,2,3** = sono i colori dei caratteri nei widget – in formato ‘FFFFFF’
* **b1,2,3** = sono i colori degli sfondi – in formato ‘FFFFFF’

I codici Regione e Località li puoi trovare nella pagina dedicata al plugin nel pannello admin di WordPress una volta attivato.
I codici Regione e Località *servono solo* per gli shortcode Old e Classic

Maggiori informazioni:

* Piu' informazioni sul [Plugin 3B Meteo](http://www.andreapernici.com/wordpress/3bmeteo/), dove e' possibile vedere l'evoluzione del plugin e lo sviluppo.
* [Meteo Italia](https://www.3bmeteo.com/widgets/wordpress) sul sito 3B Meteo.
* Dati [meteo Italia](http://www.3bmeteo.com/) forniti da 3B Meteo.

## Installation ##

### Upload ###

1. Download the [latest from WP.org](https://wordpress.org/plugins/3b-meteo/).
2. Go to the Plugins -> Add New screen and click the Upload tab.
3. Upload the zipped archive directly.
4. Go to the Plugins screen and click Activate.

### Manual ###

1. Download the [latest from WP.org](https://wordpress.org/plugins/3b-meteo/).
2. Unzip the archive.
3. Copy the folder to your /wp-content/plugins/ directory.
4. Go to the Plugins screen and click Activate.

### WP.org ###

1. Install 3B Meteo either via the WordPress.org plugin directory, or by uploading the files to the `/wp-content/plugins/` directory in your server.
2. Activate the plugin through the 'Plugins' menu in WordPress
4. After activating 3B Meteo, read the documentation for any kind of customization.
5. That's it.  You're ready to go!

## Frequently Asked Questions ##

### È compatibile con WordPress Multisite? ###

Si, è compatibile.

## Screenshots ##

1. (Old) Localita' Compatti 1 giorno **[trebi-a1]**
2. (Old) Localita' Compatti 6 giorno **[trebi-a2]**
3. (Old) Localita' Compatti 7 giorno **[trebi-a3]**
4. (Old) Localita' Dati in Diretta **[trebi-b1]**
5. (Old) Tutte le Località **[trebi-b2]**
6. (Old) Localita' estese 7 giorni **[trebi-c1]**
7. (Old) Localita' estese orario **[trebi-c2]**
8. (Old) Regionali compatto **[trebi-d1]**
9. (Old) Regionali 7 giorni **[trebi-d2]**
10. (Old) Localita' Marine 1 giorno **[trebi-e1]**
11. (Old) Localita' Marine 7 giorno **[trebi-e2]**
12. (Old) Regionali Marine 7 giorni **[trebi-f1]**
13. (Old) Neve 1 giorno **[trebi-g1]**
14. (Old) Neve 7 giorni **[trebi-g2]**
15. (Old) Settings for old widget block
16. (Old) Colors for old widget block
17. (Classic) Localita' Compatti 1 giorno
18. (Classic) Localita' Compatti 6 giorno
19. (Classic) Localita' Compatti 7 giorno
20. (Classic) Localita' Dati in Diretta
21. (Classic) Tutte le Localita'
22. (Classic) Localita' estese 7 giorni
23. (Classic) Localita' estese orario
24. (Classic) Regionali compatto
25. (Classic) Regionali 7 giorni
26. (Classic) Localita' Marine 1 giorno
27. (Classic) Localita' Marine 7 giorno
28. (Classic) Regionali Marine 7 giorni
29. (Classic) Neve 1 giorno
30. (Classic) Neve 7 giorni
31. (Classic) Italia
32. (Classic) Settings for classic widget block
33. (Classic) Colors for classic widget block
34. (Flex block) New flex meteo block
35. (Flex block) New flex meteo block
36. (Flex block) Styles for flex meteo block container
37. (Flex block) Settings for flex meteo block container
38. (Flex block) Colors for flex meteo block container
39. (Flex block) Styles for flex meteo block per day
40. (Flex block) Color support for flex meteo block day
41. (Flex block) Settings for flex meteo block day
42. (Flex block) Demo

## Changelog ##

### 1.1.0 ###
* Fixed all PHP errors now compatible with PHP <=7.2.
* Added new 3 blocks to display Weather Forecast for the WordPress new block editor

### 1.0.11 ###
* Compatibility check.

### 1.0.10 ###
* Bugfix.

### 1.0.9 ###
* Added HTTPS versione by default. TLS Everywhere.

### 1.0.8 ###
* WP 4 compatibility check

### 1.0.7 ###
* Corretto piccolo bug iframe

### 1.0.6 ###
* Corretto Bug iframe pannello admin

### 1.0.5 ###
* Corretto Bug e testato su 3.1

### 1.0.4 ###
* Corretto Bug URL su widget regioni

### 1.0.3 ###
* Corretto Bug su widget 1

### 1.0.2 ###
* Corretto Bug su altezza e larghezza widget

### 1.0.1 ###
* Bug fix

### 1.0.0 ###
* Shortcodes e Widget
* Localita' Compatti 1 giorno
* Localita' Compatti 6 giorno
* Localita' Compatti 7 giorno
* Localita' Dati in Diretta
* Tutte le Localita'
* Localita' estese 7 giorni
* Localita' estese orario
* Regionali compatto
* Regionali 7 giorni
* Localita' Marine 1 giorno
* Localita' Marine 7 giorno
* Regionali Marine 7 giorni
* Neve 1 giorno
* Neve 7 giorni


Se trovate dei bug o avete qualche idea, scriveteci.

**Utenti Avanzati**

Per un uso avanzato del plugin potete visitare la pagina del [plugin 3bmeteo](https://www.andreapernici.com/wordpress/3bmeteo/) sul sito di [Andrea](https://www.andreapernici.com/)
