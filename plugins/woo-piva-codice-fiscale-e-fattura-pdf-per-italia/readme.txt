=== Plugin Name ===
Contributors: dot4all
Donate link: https://dot4all.it/prodotto/plugin-woocommerce-p-iva-e-codice-fiscale-per-italia-pro/
Tags: woocommerce, e-commerce, partita iva,codice fiscale,woo,fattura PDF, ricevuta PDF, WooCommerce PDF, Woocommerce PDF Invoices & Packing Slips, Fattura elettronica, XML, Fatturazione elettronica, dot4all   
Requires at least: 3.0.1
Requires PHP: 7
Tested up to: 5.6
Requires WooCommerce at least: 3.5
Tested WooCommerce up to: 4.9.2
Stable tag: 2.1.11
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Il plugin che ti permette di rendere WooCommerce adatto al mercato italiano con l’inserimento del C.F. e della Partita IVA

== Description ==

Rende Woocommerce adeguato al mercato italiano e permette la gestione della Fatturazione Elettronica in XML. Aggiunge i campi P.IVA, CF, PEC e Codice Identificativo in base alla tipologia di fattura richiesta. Fondamentale per Aziende, Ditte Individuali, Liberi Professionisti e Privati. Possibilità di generare anche Ricevute.
<br /><br /> 
Versione di Woocommerce richiesta: 3.5<br />
Testato fino alla versione di Woocommerce: 4.9.2<br /><br />
IMPORTANTE • Le versioni 2+ sono COMPATIBILI SOLO CON WooCommerce PDF Invoices & Packing Slips 2.0+

Grazie al plugin è possibile:

* Aggiungere i campi relativi alla Fatturazione Elettronica: Codice Identificativo e PEC utili per l'export della <b>Fattura Elettronica in XML possibile con la versione PRO in vigore dal 1 gennaio 2019</b>
* Check VIES delle partite iva comunitarie automatico al checkout - in caso di azienda comunitaria rimuove l'imposta al checkout
* Inserire in fase di checkout i campi Codice Fiscale e Partita IVA
* Scegliere la tipologia di cliente (Privato, Azienda, Libero Professionista) che sta effettuando l’acquisto
* Modificare in amministrazione i campi Codice Fiscale e Partita IVA
* Ricevere nel dettaglio ordine e via mail i campi C.F e P.IVA creati
* Generare automaticamente la fattura/ricevuta lato amministrazione grazie al plugin WooCommerce PDF Invoices & Packing Slips</i>.
Credits & Copyrights: labdav • the plugin is a fork of WooCommerce PDF Invoices Italian Add-on
<br />
<br />
<p><b>FATTURAZIONE ELETTRONICA<b>: LA VERSIONE PRO permette di effettuare L'Export In XML delle fatture Elettroniche compatibili con il Sistema di Interscambio dell'Agenzia delle Entrate e di verificare in tempo reale la reale esistenza di P.IVA e CF direttamente sul sito dell'AdE!</p> e inoltre:<br />
* <strong>Scegliere se abilitare o meno il controllo VIES delle partite IVA Comunitarie</strong>
* <strong>Il cliente verrà avvisato tramite Notice, nel checkout, della validità o meno della partita IVA inserita </strong>
* <strong>Recuperare i campi P.IVA e C.F. al momento della creazione manuale di un ordine in amministrazione</strong>
* <strong>Modificare la label della select relativa alla tipologia di Utente/Documento Fiscale</strong>
* <strong>Definire quali voci mostrare al cliente nella tendina dedicata alla tipologia di Utente/Documento Fiscale</strong> desiderato
* Utilizzare la <strong>validazione live</strong> dei campi <strong>Codice Fiscale e P.IVA nel checkout</strong>
* Utilizzare la <strong>validazione live</strong> dei campi <strong>Codice Fiscale e P.IVA nella pagina dell\'account utente</strong>
* Possibilità di mostrare il generatore di Codice Fiscale
* Personalizzazione della label del Generatore di Codice Fiscale
* <strong>Definire le etichette</strong> della tendina della Tipologia di Documento Fiscale che verr&agrave; mostrata al cliente
* <strong>Nascondere</strong> automaticamente <strong>il campo Ragione Sociale</strong> nei casi in cui non &egrave; richiesto
* <strong>Ordinamento avanzato</strong> dei campi del checkout.
* Possibilità di Generare Fatture/Ricevute in momenti differenti dal Completamento dell'Ordine (Ottimo per ordini ricevuti con modalità tipo Bonifico)
* Aggiunta dettagli relativi all'orario di Completamento Ordine e Generazione Fattura/Ricevuta in Admin.


Per acquistare la versione PRO vai <a href="http://dot4all.it/prodotto/plugin-woocommerce-p-iva-e-codice-fiscale-per-italia-pro/" title="plugin woocommerce partita iva e codice fiscale per italia PRO" alt="plugin woocommerce partita iva e codice fiscale per italia PRO">sul sito ufficiale</a>

= Translations in your language =

* English
* Italian (it_IT)
* Our plugin is certified fully [WPML Compatible](https://wpml.org/documentation/getting-started-guide/string-translation/).

== Installation ==

1. Caricare la cartelle del plugin all'interno di `/wp-content/plugins/` oppure installare il plugin attraverso la sezione WordPress plugins.
1. Attivare il plugin attraverso la sezione 'Plugins' di WordPress.

== Changelog ==

= 2.1.11 =
FIX images path

= 2.1.10 =
FIX PHP Session
FIX Minor Fixing
FIX Remove GB from VIES check
 
= 2.1.9 =

FIX utilizzo plugin con WP Multisite
NEW campi aggiuntivi PEC e Codice destinatario presenti in anteprima nel billing_address del singolo ordine

= 2.1.8 =

FIX check_cf errore validazione

= 2.1.7 =

FIX bug generazione numero ricevuta nello stato cancellato
NEW Variabile [order_year] per ricevute da usare nel prefisso
FIX script js
NEW campo pec visibile non obbligatorio per privato 
FIX campo ricevuta nascosto in admin se Woocommerce Invoice & Packing Slips disattivato

= 2.1.6 =

NEW Visualizzazione campi PEC e Codice Identificativo in Amministrazione
NEW Aggiornamento obbligatorietà campi PEC e Codice Identificativo nel checkout Adeguato alle nuove normative
FIX php 7.2 error on parse_str()


= 2.1.5 =

NEW Generazione ricevute per ordini con totale 0
FIX File di lingua aggiornato

= 2.1.4 =

NEW Aggiunta campi opzionali PEC e Codice Identificativo nel checkout.
NEW Controllo del Carattere di controllo del Codice Fiscale.

= 2.1.3 =

FIX wcpdf_invoice_number was called incorrectly. Order properties should not be accessed directly.

= 2.1.2 =

NEW Ordinamento campi checkout modalità VIES

= 2.1.1 =

Bug Fixing
Check Vies Aziende Comunitarie
Fix Admin CSS

= 2.1 =

TWEAK Compatibilità con plugin WooCommerce PDF Invoices eliminando generazione delle ricevute. 


= 2.0.9 =

FIX bug dei Campi CF e P.IVA non presenti in fattura risolto


= 2.0.8 =

FIX billing_cf - Order properties should not be accessed directly
FIX billing_piva - Order properties should not be accessed directly
FIX billing_invoice_type - Order properties should not be accessed directly
FIX bug numerazione ricevute - fatture sull'invio delle stesse nella  mail cliente 
FIX bug intestazione ricevuta mail cliente
FIX bug numerazione ricevute quando è visualizzata la colonna del numero fatture tramite amministrazione di Woocommerce Invoice & Packaging Slips

= 2.0.7 =
* Correzione bug cf richiesto per ricevute
* Correzione Notice in admin per label icone

= 2.0.6 =
* Correzione bug "Notice: Undefined property: WC_Piva_Cf_Invoice_Ita::$cf_force_mandatory_for_receipt 

= 2.0.5 =
* Correzione bug "Notice: Undefined index:" label - class-wp-settings

= 2.0.4 =
* Correzione bug "unset string offsets" - class-wp-settings

= 2.0.3 =
* Correzione bugs errore js su paesi esteri 

= 2.0.2 =
* Correzione bugs cf obbligatorio(*) per ricevute
* Fix visualizzazione icone in admin

= 2.0.1 =
* Correzione bugs visualizzazione lista tipologia fattura in admin/mio account

= 2.0 =
* IMPORTANTE: Compatibilità con Versione 2+ di WooCommerce PDF Invoices & Packing Slips
* Possibilità di inserire prefisso, numerazione e padding number per le Ricevute
* Aggiornamento Core Plugin

= 1.0.11 =
* Correzione warning php Undefined index: _show_recipe_invoice

= 1.0.10 =
* Validazione campo Cf non obbligatorio per Ricevute.
* Correzione bug minori

= 1.0.9 =
* Correzione bug relativo agli asterischi mostrati per campi non obbligatori in fase di checkout.
* Correzione file receipt.php lingua IT


= 1.0.8 =
* Correzione bug del campo P.IVA mostrato al caricamento con Ricevuta selezionato.

= 1.0.7 =
* I campi vengono nascosti in caso di vendita fuori dall’Italia     
* Aggiunta la possibilità di rendere i campi CF e P.IVA non obbligatori direttamente in amministrazione evitando i controlli nel checkout

= 1.0.6 =
* FIX generazione DDT    

= 1.0.5 =
* Fix file di lingua Italiano
* Fix Controllo campi P.IVA
* Enqueue file .js con codice js inserito nel DOM   

= 1.0.4 =
* Permettere all'amministratore di abilitare o meno la possibilità di far scegliere all'utente sia la creazione di fatture che di ricevute. Il flag permette in amministrazione di disabilitare nelle scelte la possibilità di generare ricevute oltre che fatture. Aggiunto una nuova voce di menu "WC CF e PIVA Italia" all'interno del menu Woocommerce 

= 1.0.3 =
* Correzione bug script class-wcpdf-integration.php e $_SESSION

= 1.0.2 =
* Aggiornamenti relativi ai controlli dei campi obbligatori cf e piva
* Correzione bug visualizzazione icone pdf

= 1.0.1 =
* Prima release

