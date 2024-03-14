---------------------------------------------------------------
Il progetto è gestito mediante "git" nei repository bitbucket di Lead Champion ma deve essere mantenuto sincronizzato l' svn ufficiale di Wordpress.
---------------------------------------------------------------

    git clone https://leadchampion@bitbucket.org/leadchampionteam/LeadChampionWordPress-plugin.git

Il repository git contiene al suo interno la struttura del repository svn di Worpress.
Dopo il checkout di git verificare se è allineato a quello svn

    cd WordPress-svn-checkout/lead-champion-discover
    svn status
    svn up
Se sono presenti disallineamenti, aggiornare prima lo stato svn e poi committare le modifiche si git

    svn commit -m 'messaggio di commento modifica' file-modificato
    git commit -m 'messaggio di commento modifica' file-modificato
    git push --all

---

Ogni modifica per essere presa in considerazione dalla distribuzione ufficiale dei plugin WP
implica la generazione di una nuova versione, con la creazione di un nuovo TAG nel repository
svn ufficiale di WordPress.

Per creare una nuova versione bisogna cambiare il numero di versione in tutti i file che lo includono:
- il codice php  (wp_lcd.php)
- il readme internazionale (readme.txt)
- il readme italiano i18n (readme-it.txt)

# Creazione di un tag di versione
Quando i version number all'interno dei sorgenti sono corretti e committati su trunk, si deve creare una copia di trunk
in una sottodirectory di tags con nome uguale al numero di versione

    svn cp https://plugins.svn.wordpress.org/lead-champion-discover/trunk https://plugins.svn.wordpress.org/lead-champion-discover/tags/1.1.1

Dopo il tag svn, aggiungere la nuova cartella tags/n.n.n a git, committare e creare lo stesso tag in git

    git add tags/n.n.n
    git commit -m 'aggiunto tag n.n.n'
    git push --all
    git tag v.n.n.n
    git push --tags

# Nazionalizzazione
Se si aggiungono o modificano stringhe nell'applicazione del plugin in php o nel file readme, il sistema wordpress se ne accorge e lo evidenzia nel sistema di traduzione disponibile all'indirizzo https://translate.wordpress.org/projects/wp-plugins/lead-champion-discover

L'account lorenzonazario è stato accreditato come traduttore ufficiale dei contenuti di WordPress, quindi le traduzioni vanno effettuate con tale account.

Il sito di traduzione rende disponibile la "traducibilità" della versione "development" ovvero i sorgenti sotto la cartella trunk (e relativo Development Readme), e la versione Stable con lo Stable Readme (sorgenti presenti nella cartella dei tags con nome uguale alla current-version-number).

Al termine della traduzione, sempre dal sito translate.wordpress.org, scaricare il "Language Packs"
che è uno zip con i file delle traduzioni.

Dovrebbe contenere i file:
- lead-champion-discover-it_IT.po
- lead-champion-discover-it_IT.mo

se non ci fosse il file compilato ".mo" è possibile generarlo con il comando:

    msgcat lead-champion-discover-it_IT.po | msgfmt -o  lead-champion-discover-it_IT.mo -

# Istruzione per visualizzare tutte le revision del plugin Lead Champion in Wordpress
    svn log https://plugins.svn.wordpress.org/lead-champion-discover

git tag v1.1.1
git push --tags

---------------------------------------------------------------
# per resettare un eventuale problema e buttare le commit locali - attenzione che serve il carattere '^' dopo HEAD
git reset --hard HEAD^
