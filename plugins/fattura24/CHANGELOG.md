# 7.1.1
###### _Feb 15, 2024_
- Correzione stile campi checkout

# 7.1.0
###### _Feb 01, 2024_
- Aggiunta opzione per il Pdc linea delle commissioni
- Riordino delle opzioni nella schermata delle impostazioni

# 7.0.0
###### _Jan 30, 2024_
- Riscrittura codice per compatibilità HPOS
- Aggiornamento hooks, metodi e filtri lista ordini
- Compatibilità WC 8.4
- Miglioramento gestione coupon percentuali
- Modifica controllo sconti a importo fisso

# 6.2.9
###### _Oct 30, 2023_
- Compatibilità WP 6.4 e WC 8.2.1

# 6.2.8
###### _Oct 2, 2023_
- Corretto bug in tax.php

# 6.2.7
###### _Sep 8, 2023_
- Aggiunta opzione per il Pdc linea di spedizione
- Migliorata la gestione di idRequest: ora è univoca anche in ambiente network

# 6.2.6
###### _Ago 8, 2023_
- Aggiunto controllo sulla spedizione gratuita

# 6.2.5
###### _Lug 17, 2023_
- Aggiunti filtro e colonna 'richiesta fattura' nell'elenco degli ordini
- Migliorata la logica di creazione contemporanea di ordine e documento

# 6.2.4
###### _Giu 19, 2023_
- Migliorata compatibilità con PHP 8.x

# 6.2.3
###### _Giu 05, 2023_
- Aggiunto messaggio di chiave API non impostata
- Miglioramento sezione Supporto

# 6.2.2
###### _Mag 30, 2023_
- Corretto bug in gestione codici sconto multipli

# 6.2.1
###### _Mag 22, 2023_
- Corretto bug gestione campo da plugin WooCommerce EU VAT Number

# 6.2.0
###### _Mag 8, 2023_
- Migliorata gestione tassa spedizione

# 6.1.9
###### _Apr 17, 2023_
- Aggiornamento azione fatt_24_send_ticket
- Correzione bug

# 6.1.8
###### _Mar 27, 2023_
- Migliorata l'azione fatt_24_send_ticket

# 6.1.7
###### _Mar 20, 2023_
- Migliorata la gestione degli script lato admin

# 6.1.6
###### _Mar 13, 2023_
- Aggiunta opzione distinta per sezionali FE
- Migliorata la gestione dei messaggi relativi alla Natura

# 6.1.5
###### _Feb 27, 2023_
- Migliorata gestione natura per la spedizione

# 6.1.4
###### _Feb 15, 2023_
- Corretto bug in lista ordini (WC 7.4.0)

# 6.1.3
###### _Feb 14, 2023_
- Modificato metodo di salvataggio e test chiave API
- Correzione bug

# 6.1.2
###### _Jan 25, 2023_
- Correzione stile checkbox 'Desidero ricevere una fattura'

# 6.1.1
###### _Jan 23, 2023_
- Correzione bug

# 6.1.0
###### _Jan 23, 2023_
- Modificata logica di convalida dei campi Codice Fiscale e Partita Iva nel checkout dell'ordine
- Modificata logica di controllo del titpo di documento risultante
- Corretto bug: in caso abbia l'id della tassa, riporto la descrizione corretta anche in caso di spedizione gratuita
- Modifica idRequest: ora NON viene valorizzato se l'opzione debug è contrassegnata da spunta
- Modifica sezione supporto: aggiunto messaggio di errore se il campo id ordine è compilato e nessun ordine con quell'id è stato trovato
- Modifica stile delle istruzioni nella sezione supporto
- Inserito stile personalizzato nei campi di checkout dell'ordine

# 6.0.6
###### _Dec 14, 2022_
- Correzione bug: ora i primi due caratteri del dato immesso nel campo p.iva vengono troncati solo se corrispondono al paese di fatturazione

# 6.0.5
###### _Dec 7, 2022_
- Correzione bug logica di convalida campi fiscali

# 6.0.4
###### _Dec 6, 2022_
- Aggiunta compatibilità con plugin EU/UK VAT for WooCommerce e WooCommerce EU VAT Number
- Correzione stile checkbox Desidero ricevere una fattura e logica di convalida

# 6.0.3
###### _Nov 10, 2022_
- Sostituita videolezione 5

# 6.0.2
###### _Nov 08, 2022_
- Migliorato lo stile dei campi nel checkout

# 6.0.1
###### _Nov 07, 2022_
- Aggiunto metodo di pulizia del testo nel nome del prodotto

# 6.0.0
###### _Nov 02, 2022_
- Modificata opzione gestione varianti prodotto


# 5.9.9
###### _Oct 11, 2022_
- Aggiunta gestione errori sezione supporto


# 5.9.8
###### _Oct 4, 2022_
- Aggiunta videolezione 
- Miglioramento gestione PaymentMethodName
- Aggiornamento strumento Supporto: ora punta su desktale.com


# 5.9.7
###### _Sep 21, 2022_
- Corretto bug in modalità di pagamento elettronico

# 5.9.6
###### _Sep 12, 2022_
- Aggiunta opzione relativa alle varianti prodotto
- Aggiunta opzione attiva debug
- Correzione bug minori

# 5.9.5
###### _Lug 18, 2022_
- Miglioramento gestione varianti prodotto
- Miglioramento strumento ticket (sez. 'Supporto')

# 5.9.4
###### _Giu 27, 2022_
- Aggiunta gestione valute diverse da EURO per Ordini, Fatture NON Elettroniche e Ricevute
- Miglioramento strumento gestione ticket (sezione 'Supporto')
- Correzione bug minori

# 5.9.3
###### _Mag 30, 2022_
- Aggiunte opzioni per abilitare / disabilitare download dei file pdf
- Miglioramento: ora i campi fiscali sono sempre visualizzati quando l'opzione scelta è 'FE' e la creazione di ricevute è disabilitata
- Risistemazione grafica di pulsanti e icone


# 5.9.2
###### _Mag 18, 2022_
- Migliorato lo strumento 'Supporto'


# 5.9.1
###### _Mag 16, 2022_
-  Modifica metodo di controllo documento esistente
-  Aggiornamento stili pagina tickets
-  Aggiornamento gestione tooltip

# 5.9.0
###### _Mag 09, 2022_
- Aggiunta la possibilità di aprire un ticket al supporto Fattura24 dal pannello admin
- Corretto bug di visualizzazione nel dettaglio dell'ordine
- Modificato link informativa privacy

# 5.8.0
###### _Apr 19, 2022_
- Aggiunta la possibilità di effettuare il download del pdf dell'ordine
- Modificato stile dei pulsanti e degli avvisi o errori nell'elenco degli ordini
- Aggiunto il download del pdf dell'ordine in 'il mio account'
- Aggiunti filtri per paese di fatturazione

# 5.7.3
###### _Mar 21, 2022_
- Aggiunti link alle impostazioni e alla documentazione
- Migliorata la gestione del docId nell'elenco degli ordini e delle fatture

# 5.7.2
###### _Mar 14, 2022_
- Aggiornamento videolezioni

# 5.7.1
###### _Mar 8, 2022_
- Corretto bug nel tipo di documento creato in alcuni casi particolari
- Corretti bug e migliorata la gestione dei campi aggiuntivi del checkout e dei loro attributi

# 5.7.0
###### _Mar 7, 2022_
- Cambiata la struttura del progetto: ora i file js sono tutti in una apposita cartella
- Corretto bug su attributo 'required' er il campo codice fiscale
- Corretto bug nei filtri di ricerca (elenco ordini woocommerce)

# 5.6.4
###### _Feb 14, 2022_
- Spostati metodi di test api key, invio recensione, oggetto predefinito, download file di log

# 5.6.2
###### _Feb 08, 2022_
 - Corretto bug nel metodo fatt_24_document_exists
 - Spostato il file f24_pdfcmd.js nella cartelle 'js'
 - Correzioni minori

# 5.6.0
###### _Feb 01, 2022_
- Aggiunta scheda videolezioni e risistemazione schede
- Modifica aspetto e stile delle impostazioni generali
- Adeguamento link di download documento a WPML
- Correzione bug

# 5.5.3
###### _Nov 24, 2021_
- Corretto bug che rendeva incompleti alcuni messaggi di errore nel tooltip

# 5.5.2
###### _Nov 24, 2021_
- Corretto bug con cui il campo p. iva veniva sempre riempito
- Corretto bug sul tipo di documento restituito: aggiunto anche il controllo sulla checkbox 'Forza creazione fatture'

# 5.5.1
###### _Nov 22, 2021_
- Migliorata la gestione dei dati nelle colonne Ordine F24 e Fattura F24
- Modificato messaggio di avviso / errore nel tooltip

# 5.5.0
###### _Nov 19, 2021_
- Aggiunta logica di creazione fatture in base al paese
- Modificata la gestione degli hooks

# 5.4.9
###### _Nov 15, 2021_
- Modificata logica di convalida della P.IVA : ora è legata anche alla checkbox 'Desidero ricevere una fattura'
- Aggiunti link per verifica c.f. e partita IVA lato admin

# 5.4.8
###### _Nov 12, 2021_
- Migliorata la gestione di ordini con totale zero


# 5.4.7
###### _Nov 11, 2021_
- Spostati i campi fiscali in 'Mio account': ora sono nella sezione indirizzi

# 5.4.6
###### _Nov 9, 2021_
- Corretto errore che causava la creazione di Fatture tradizionali invece di FE

# 5.4.5
###### _Nov 8, 2021_
- Correzione errore critico dovuto a mancato aggiornamento tabella tax

# 5.4.4
###### _Nov 8, 2021_
- Aggiornamento impostazioni 'Crea documento fiscale'
- Aggiornamento gestione sconti multipli: aggiunto controllo sulle categorie

# 5.4.3
###### _Oct 18, 2021_
- Corretto bug che impediva lo scarico del prodotto dal magazzino al cambio di stato dell'ordine
- Corretto bug sugli sconti inseriti lato admin

# 5.4.2
###### _Oct 13, 2021_
- Corretto bug che impediva il completamento del checkout dell'ordine in alcuni casi

# 5.4.1
###### _Oct 5, 2021_

- Miglioramento gestione sconti in percentuale

# 5.4.0
###### _Oct 4, 2021_
- Revisione struttura del codice
- Aggiunta gestione errori nell'xml


# 5.3.8
###### _Set 15, 2021_
- Aggiunte opzioni e metodi per gestire description o short description nel nome prodotto
- Corretto bug di visualizzazione dell'avviso relativo alla natura


# 5.3.4
###### _Ago 27, 2021_
- Modificati i metodi di lettura delle aliquote nei prodotti, nella spedizione, nelle fees


# 5.3.1
###### _Lug 26, 2021_
- Migliorata la gestione del campo codice fiscale lato checkout
- Compatibilità WP 5.8 e WC 5.5.1
- Estesa l'archiviazione dei file di log a 30gg

# 5.3.0
###### _Lug 13, 2021_
- Corretto bug che impediva l'attivazione di altri plugin in caso l'oggetto Customer sia null
- Modificata la logica della variabile $showCheckbox

# 5.2.9
###### _Lug 8, 2021_
- Aggiunto controllo sul campo p. iva: ora il checkout si blocca se viene inserita la stringa 'IT' e il campo è obbligatorio

# 5.2.8
###### _Giu 28, 2021_
- Modificata la ricerca delle aliquote di spedizione nelle righe prodotto
- Corretto bug nella gestione degli sconti per prodotti variabili in offerta
- Corretto bug sui metodi del prodotto WooCommerce
- Aggiunta gestione dello sku per i prodotti variabili

# 5.2.6
###### _Giu 10, 2021_
- Migliorata la gestione del campo Chiave API
- Migliorata gestione del messaggio di errore connessione API
- Corretto bug che impediva la visualizzazione del tasto 'Crea documento' 

# 5.2.5
###### _Mag 26, 2021_
- Migliorata gestione della convalida dei dati fiscali
- Ripristinato messaggio di errore API nella schermata delle impostazioni

# 5.2.4
###### _Mag 21, 2021_
- Aggiunto controllo su spedizione disabilitata
- Aggiunte impostazioni per abilitare creazione ordini e fatture con totale zero
- Migliorato metodo di aggiunta colonna 'blog_id'


# 5.2.0
###### _Mag 17, 2021_
- Migliorata la gestione dei messaggi a video: ora vengono visualizzati anche fuori dalle impostazioni del plugin
- Ripristinata l'abilitazione della casella Invia Email, aggiunta un'avvertenza specifica per le FE
- Aggiunto messaggio in caso di aliquote spedizione non impostate in WooCommerce


# 5.1.5
###### _Mag 13, 2021_
- Aggiornato metodo di gestione dei prezzi prodotto: ora controllo se il calcolo delle imposte è abilitato in WC

# 5.1.4
###### _Mag 11, 2021_
- Corretto bug su prezzo prodotto

# 5.1.3
###### _Mag 10, 2021_
- Miglioramento gestione aliquote delle commissioni sui metodi di pagamento
- Miglioramento gestione del prezzo unitario del prodotto

# 5.1.2
###### _Mag 03, 2021_
- Corretto bug che impediva l'aggancio di aliquote di spedizione a 0%
- Aggiornati metodi di pagamento elettronico

# 5.1.1
###### _Apr 21, 2021_
- Corretto bug nella sezione 'Configurazione tassa' in ambienti multisito
- Corretto bug che svuotava il contenuto dei campi fiscali occasionalmente

# 5.1.0
###### _Apr 19, 2021_
- Aggiunta impostazione per rendere sempre visibili i campi fiscali
- Migliorata la gestione dei campi fiscali

# 5.0.9
###### _Apr 06, 2021_
- Cambiata priorità dei campi nel checkout, aggiunto billing_company
- Migliorata gestione tax in ambiente multisito
- Aggiunto metodo di aggiornamento della configurazione tassa

# 5.0.8
###### _Mar 29, 2021_
- Aggiunto campo al checkout con cui è possibile visualizzare o nascondere i campi aggiuntivi (c.f., p.iva, pec, codice sdi)

# 5.0.7
###### _Mar 25, 2021_
- Aggiunto metodo per eliminare i campi aggiuntivi quando l'utente viene eliminato
- Corretto bug nella ceazione dei documenti allo stato dell'ordine prescelto

# 5.0.6
###### _Mar 15, 2021_
- Eliminato il numero d'ordine dalle FootNotes: è già possibile inserirlo nella causale del documento
- Rimossa unità di peso

# 5.0.5
###### _Mar 15, 2021_
- Aggiunto infobox alle schermate impostazioni
- Spostati link alla documentazione e pulsante di accesso F24 in infobox
- Aggiunto componente per invito alla valutazione del plugin


# 5.0.4
###### _Mar 04, 2021_
- Corretto bug nella gestione degli sconti per ordini da admin
- Migliorata la posizione dei messaggi

## 5.0.3
###### _Mar 01, 2021_
- Migliorata la gestione dei coupon di WooCommerce Subscriptions
- Migliorata la gestione del messaggio di errore in caso di fallimento chiamata API

## 5.0.2
###### _Feb 24, 2021_
- Corretto bug nei messaggi di verifica chiave api
- Corretti errori nella lettura del db

## 5.0.1
###### _Feb 18, 2021_
- Inserita la gestione dell'unità di peso
- Corretto bug in tax.php

## 5.0.0
###### _Feb 18, 2021_
- Migliorata la gestione degli sconti

## 4.9.8
###### _Feb 01, 2021_
- Modificato metodo di gestione delle righe prodotto
- Modificata la gestione delle province in caso di cliente estero
- Modificata la gestione delle aliquote in funzione dei codici natura

## 4.9.7
###### _Gen 18, 2021_
- Migliorata la gestione dei messaggi a video
- Inserito controlllo in caso di errore WP nella chiamata API
- Inserita gestione degli errori nel calcolo del'aliquota di spedizione

## 4.9.6
###### _Gen 11, 2021_
- Modificato l'elenco dei codici Natura e aggiunto messaggio per chi usa codici non più validi
- Migliorata gestione spedizione gratuita
- Aggiunto messagio di errore per calcolo tasse non abilitato in WooCommerce

## 4.9.5
###### _Dic 10, 2020_
- Correzione bug minore

## 4.9.4
###### _Dic 09, 2020_
- Migliorata a gestione delle aliquote IVA per spedizione gratuita

## 4.9.3
###### _Dic 09, 2020_
- Corretto bug: ora le opzioni di creazione ordine e fattura vengono gestite correttamente


## 4.9.2
###### _Nov 25, 2020_
- Cambiata l'opzione 'Crea ordine' nelle impostazioni;
- Aggiunto metodo per leggere le impostazioni precedenti un aggiornamento
- Migliorata la gestione delle aliquote di spedizione

## 4.9.1
###### _Oct 27, 2020_
- Corretto bug nell'aliquota di spedizione per ordini inseriti da admin

## 4.9.0
###### _Oct 06, 2020_
- Commentato metodo get_query_var
- Aggiornato l'elenco delle Nature IVA

## 4.8.9
###### _Oct 06, 2020_
- Corretto bug nel metodo di visualizzazione dei filtri

## 4.8.8
###### _Oct 05, 2020_ 
- Aggiunti filtri per ricerca stato ordine e fattura in F24

## 4.8.7
###### _Oct 01, 2020_ 
- Corretto bug sui messaggi di errore

## 4.8.6
###### _Sep 21, 2020_ 
- Aggiunta tab statistiche chiamate API
- Cambiata la costante FATT_24_PLUGIN_DATA
- Aggiunto avvertimento in caso di creazione fattura disabilitata

## 4.8.5
###### _Sep 16, 2020_ 
- Corretto bug su campo obbligatorio ‘Codice fiscale’: ora viene intercettata anche l’azione di cambio paese lato client

## 4.8.4
###### _Sep 15, 2020_ 
- Migliorata la gestione del file di log
- Migliorata la gestione degli ordini crati lato admin
- Ripristinati link di verifica C.F. e p. Iva

## 4.8.3
###### _Sep 07, 2020_ 
- Aggiunta alla chiamata API la gestione della sorgente
- Aggiunto messaggio di cortesia sulla scadenza dell'abbonamento
- Corretto bug: ora la descrizione dell'aliquota viene ripèortata correttamente anche in caso di aliquota 0%

## 4.8.2
###### _Sep 02, 2020_ 
- Migliorata la gestione delle chiamate API

## 4.8.1
###### _Aug 27, 2020_ 
- Corretto bug nella lista dei sezionali
- Migliorata la gestione dei messaggi di errore

## 4.8.0
###### _Aug 24, 2020_ 
- Aggiunta tabella per log versioni del plugin
- Corretti bug nella schermata di impostazioni in caso di API Key vuota
- Aggiunti nuovi postmeta alla creazione del documento

## 4.7.4
###### _Aug 18, 2020_ 
- Compatibilità Wp 5.5

## 4.7.3b
###### _Aug 03, 2020_ 
- Aggiunta funzione di misura del tempo supportata da versioni PHP precedenti

## 4.7.3
###### _Lug 30, 2020_
- Aggiunta misurazione del tempo di risposta delle chiamate API nel file di log
- Corretti bug nella visualizzazione delle colonne Ordine F24 e Fattura F24

## 4.7.2
###### _Lug 27, 2020_
- Corretto bug in caso di calcolo dell'aliquota di spedizione

## 4.7.1
###### _Lug 22, 2020_
- Miglioramento: ora la logica di gestione del campo p. iva per la FE controlla il tipo di documento generato

## 4.7.0
###### _Lug 13, 2020_
- Aggiunto messaggio di avvertimento compresenza con plugin non ufficiale

## 4.6.9
###### _Lug 07, 2020_
- Corretto bug minore

## 4.6.8
###### _Giu 30, 2020_
- Aggiunto metodo di gestione del cambio di stato dell'ordine in Wordpress

## 4.6.7
###### _Giu 15, 2020_
- Aggiunto messaggio di errore in caso di FE e mancata configurazione della Natura per aliquote 0%
- Migliorata la gestione dell'elenco nella sezione 'Configurazione tassa'

## 4.6.6 
###### _Giu 03, 2020_
- Corretto bug nella lista dei sezionali fattura
- Migliorata la gestione del numero d'ordine
- Migliorata la gestione degli errori nei campi fiscali

## 4.6.5
###### _Mag 26, 2020_
- Migliorato il comportamento delle colonne aggiuntive

## 4.6.4rc
###### _Mag 13, 2020_
- Miglioramento delle prestazioni del plugin
- Rimosso errore tipografico

## 4.6.3
###### _Mag 11, 2020_
- Aggiunta opzione per creare sempre ricevute;
- Aggiunta opzione sullo stato degli ordini: ora è possibile creare fatture solo manualmente
- Corretto bug: ora gli importi delle commissioni sul metodo di pagamento vengono aggiunti ai totali
- Migliorata la gestione delle varianti prodotto

## 4.6.2
###### _Mag 05, 2020_
- Aggiunto link al sito AdE per la verifica del codice fiscale e della partita iva nei campi di checkout
- Aggiunto controllo nei campi c.f. e p. iva (se il paese di fatturazione è IT)
- Aggiornato il link legato al pulsante 'Accedi a Fattura24'

## 4.6.1a
###### _Apr 24, 2020_
- Aggiunta gestione degli errori di collegamento al server
- Aggiunto metodo per applicare il bollo virtuale alle FE

## 4.6.0
###### _Apr 08, 2020_
- Aggiunta possibilità di creare ordini o fatture da Subscriptions
- Aggiunti metodi di pagamento elettronico
- Corretto bug in SaveCustomer

## 4.5.9
###### _Mar 31, 2020_
- Aggiunto riferimento al numero del documento in F24 nella colonna degli ordini e nel dettaglio dell'ordine

## 4.5.8
###### _Mar 23, 2020_
- Correzione bug minori

## 4.5.7
###### _Mar 12, 2020_
- Verifica funzionalità per WP 5.4 e WC 4
- Migliorata la gestione dei pulsanti nelle colonne 'Ordine F24' e 'Fattura F24'
- Corretto errore che impediva di caricare le aliquote di spedizione in caso di carrello vuoto

## 4.5.6
###### _Mar 04, 2020_
- Rinomina del file trace.log al click su Download, aggiunto controllo su debug WP attivo
- Corretto bug che impediva il passaggio dei dati fiscali dall'ordine alla fattura
- Modificata la gestione delle aliquote di spedizione: ora vengono prese quelle dei prodotti solo in caso di errore.

## 4.5.5
###### _Feb 26, 2020_
- Migliorata la gestione del flag sul Codice Fiscale
- Migliorata la gestione dei campi fiscali per ordini inseriti lato admin
- Corretto bug su TotalWithoutTax : non veniva conteggiato lo sconto

## 4.5.4
###### _Feb 11, 2020_
- Corretto bug relativo al codice prodotto per i prodotti variabili
- Corretto bug in caso di aliquota di spedizione diversa da aliquota prodotto
- Corretto bug: ora il CustomerName viene popolato solo con i dati di fatturazione
- Revisione e pulizia del codice

## 4.5.3
###### _Gen 22, 2020_
- Corretto bug sulla gestione dei campi aggiuntivi nel checkout per clienti non registrati

## 4.5.2
###### _Gen 20, 2020_
- Cambiato metodo di gestione dei pagamenti elettronici
- Migliorata la gestione del tag FePaymentCode
- Aggiunta gestione della modalità di pagamento 'Assegno'

## 4.5.1
###### _Gen 13, 2020_
- Aggiunta gestione dei dati fiscali del cliente per gli ordini inseriti da admin
- Migliorata la gestione dei pagamenti elettronici
- Migliorata la gestione della Natura Iva per i coupon

## 4.5.0
###### _Gen 07, 2020_
- Modificata la casella di controllo 'Stato Pagato' in un menu a tendina
- Inserito il nome dell'istituto nel tag PaymentMethodName se il metodo prescelto è il bonifico
- Correzione bug minori

## 4.4.8
###### _Dic 12, 2019_
- Corretta gestione Natura IVA per ordini creati da admin
- Corretta azione di salvataggio del codice destinatario per ordini creati lato admin
- Creazione di un documento in stato 'Pagato' se il metodo prescelto è Paypal (o stripe, payplug, ppay)

## 4.4.7
###### _Dic 05, 2019_
- Aggiunta la gestione del piano dei conti nell'ordine
- Migliorata la compatibilità WPML
- Migliorata la leggibilità del codice in api_call.php

## 4.4.6
###### _Nov 26, 2019_
- Migliorati i controlli sulle aliquote dei prodotti
- Aggiunta la gestione del piano dei conti nell'ordine

## 4.4.5
###### _Nov 19, 2019_
- Migliorata la gestione dei prodotti
- Aggiunta la Natura per le commissioni (fees)
- Verificata compatibilità con WP 5.3 e WC 3.8

## 4.4.4
###### _Nov 06, 2019_
- Aggiunti pulsanti di creazione documento nelle colonne di controllo status ordine e fattura
- Migliorata la gestione della natura IVA
- Aggiornata la gestione del metodo di spedizione

## 4.4.3
###### _Ott 30, 2019_
- Migliorata la gestione delle aliquote di spedizione
- Migliorati i testi sorgente e le traduzioni
- Migliorato lo stile della schermata 'Attenzione'

## 4.4.2
###### _Ott 28, 2019_
- Aggiunta gestione del campo vuoto partita IVA per stati esteri
- Corretto bug in descrizione IVA per i coupon
- Disabilitata l'azione 'Invia Email' quando è selezionata la Fattura Elettronica

## 4.4.1
###### _Ott 18, 2019_
- Migliorata la gestione della natura IVA per la spedizione

## 4.4.0
###### _Set 27, 2019_
- Migliorata la gestione delle aliquote di spedizione
- Modificato il testo di suggerimento sotto il campo API Key

## 4.3.9a
###### _Set 25, 2019_
- Migliorata la gestione dei coupon in compatibilità con versioni precedenti di WooCommerce

## 4.3.9
###### _Set 24, 2019_
- Modificata la visualizzazione dei sezionali: ora vengono visualizzate le anteprima
- Migliorata la gestione dei numeratori nell'xml
- Corretto il comportamento della checkbox Salva Cliente

## 4.3.8
###### _Set 23, 2019_
- Corretto bug: non venivano gestiti gli importi delle fees

## 4.3.7
###### _Set 18, 2019_
- Aggiunte colonne di controllo stato per ordine e fattura
- Migliorata la gestione delle imposte nei costi di spedizione

## 4.3.6
###### _Set 16, 2019_
- Corretto bug: non venivano aggiunti i costi di spedizione all'imponibile

## 4.3.5
###### _Set 11, 2019_
- Commentata funzione di controllo stato ordine e fattura

## 4.3.4
###### _Set 09, 2019_
- Aggiornato metodo di gestione coupon
- Aggiunte colonne di controllo stato per ordine e fattura (beta)

## 4.3.3
###### _Set 06, 2019_
- Corretto bug che impediva la creazione dell'ordine in caso di utilizzo coupon
- Modificata gestione dei totali: ora vengono presi da WooCommerce e non calcolati
- Migliorata la gestione dei dati di spedizione
- Corretto bug relativo alla descrizione aliquota IVA

## 4.3.2
###### _Set 02, 2019_
- Corretto bug: il sistema non elencava il metodo di spedizione nell'xml

## 4.3.1
###### _Ago 28, 2019_
- Aliquote IVA: vengono recuperate dai metodi WooCommerce
- Migliorata la gestione Natura IVA, in Configurazione Tassa e nella gestione della FE 
## 4.3.0
###### _Ago 22, 2019_
- Ripristinate azioni Fattura24 nella schermata ordini
- Aggiunto tag di descrizione al metodo di pagamento Paypal
- Aggiunta gestione aliquote IVA con decimali e corretti bug di arrotondamento al centesimo

## 4.2.4
###### _Ago 01, 2019_
- Aggiunto pulsante per causale documento predefinita
- Correzione bug minore

## 4.2.3
###### _Lug 25, 2019_
- Migliorata la gestione dei metodi di pagamento

## 4.2.2
###### _Lug 08, 2019_
- Modificate le etichette dei campi aggiuntivi

## 4.2.1
###### _Lug 01, 2019_
- Migliorata gestione azioni aggiuntive nella schermata degli ordini

## 4.2.0
###### _Lug 01, 2019_
- Aggiunto Tab notizie importanti
- Ripristino dei link nella sezione ordini
- Aggiunto ai link supporto per la FE
- Correzione bug minori
