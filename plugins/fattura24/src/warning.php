<?php
/**
 * Questo file è parte del plugin WooCommerce v3.x di Fattura24
 * Autore: Fattura24.com <info@fattura24.com> 
 *
 * Descrizione: Tab "Attenzione" della schermata di impostazioni
 * 
 */

namespace fattura24;

if (!defined('ABSPATH'))
    exit;

/*if (is_admin())
{
    //@session_start();
}*/

// visualizza la tab "Attenzione"
function show_warning() {
    ?>

    <div class='wrap'>
    <h2></h2>
    <?php fatt_24_get_link_and_logo(__('', 'fatt-24-warning')); 
        echo fatt_24_build_nav_bar();
    ?>


       <div style = "text-align:center;">
            <h1>Informazione fiscale importantissima</h1>
            <h2>Circolare Agenzia Entrate n. 14/E del 17.06.2019</h2>
       </div>
       
       <p style="font-size: 120%; text-align:justify;">
       Come noto, la Circolare n. 14/E del 17/06/2019 dell'Agenzia delle Entrate, denominata <strong>"Chiarimenti in tema di documentazione di operazioni rilevanti ai fini IVA, alla luce dei recenti interventi normativi in tema di fatturazione elettronica"</strong>, ha dato un’interpretazione unitaria delle innovazioni legislative degli ultimi anni in tema di IVA e di <strong>fatturazione elettronica</strong>.
       <br /><br />

La Circolare rileva innanzitutto che: 
<div style="padding-left:30px; font-size: 120%; text-align:justify;">
    <ul style="list-style-type: disc;">
        <li>    
        nella fattura elettronica si deve indicare, <u>OLTRE</u> alle altre tradizionali e consuete informazioni previste dal D.p.r. 633/1972, la "<strong>data</strong> in cui è effettuata la cessione di beni o la prestazione di servizi ovvero la data in cui è corrisposto in tutto o in parte il corrispettivo, sempreché tale data sia diversa dalla data di emissione della fattura" (v. punto 3.1 della Circolare);</li>
        <br />

        <li> la fattura elettronica attestante la prestazione deve essere emessa "<em><strong>entro dodici giorni dall'effettuazione dell'operazione determinata ai sensi dell'art.6</strong></em>  [della Legge IVA]" (si riferisce al nuovo comma 4, periodo 1, dell'art. 21 del D.p.r. 633/72).</li>
        
        <br />

    </ul>

</div>
<div style="font-size: 120%; text-align:justify;">
L’Agenzia, inoltre, afferma che, poiché “<em>per una fattura elettronica veicolata attraverso lo Sdi quest’ultimo ne attesta inequivocabilmente ... la data ... di avvenuta trasmissione”, allora “è possibile assumere che la data riportata nel campo <strong>‘Data’</strong> della sezione ‘Dati Generali’ del file della fattura elettronica sia <strong><u>sempre e comunque</u> la data di <u>effettuazione dell’operazione</u></strong></em>” (v. punto 3.1 della Circolare). </li>
<br /><br />
Nella Circolare, infine, l’Agenzia rileva che “<em>anche se l’operatore decidesse di ‘emettere’ la fattura elettronica via Sdi <strong>non</strong> entro le 24 ore dal giorno dell’Operazione, <strong>bensì</strong> in uno dei successivi 12 giorni previsti dal novellato articolo 21 ... </em>[della legge IVA] ,<em><strong> la data del documento dovrà sempre essere valorizzata con la data dell’<u>operazione</u></strong> e i 12 giorni citati potranno essere sfruttati per la trasmissione del file della fattura elettronica al Sistema di Interscambio</em>”. 

La Circolare contiene notazioni specifiche anche sul tema della <strong>fattura differita</strong> e di cosa indicare in questo caso nel campo data.
<br /><br />
Nota di F24: Tenuto conto della (ancora sostanziale) novità e complessità dei temi introdotti dalla Circolare, vi invitiamo a confrontarvi sempre con il commercialista per verificare ed organizzare correttamente le vostre modalità e tempistiche di fatturazione.
</div>
</p>
<?php  }