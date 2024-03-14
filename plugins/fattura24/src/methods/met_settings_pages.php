<?php
/**
 * Questo file è parte del plugin WooCommerce v3.x di Fattura24
 * Autore: Fattura24.com <info@fattura24.com>
 *
 * Metodi comuni a tutte le schermate di impostazioni:
 * elenco delle schermate, barra di navigazione, rating e infobox
 */

namespace fattura24;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Metodo con cui costruisco l'elenco delle tabs nella schermata di admin
 */
function fatt_24_settings_tabs()
{
    $fatt24Menus = [
        array('slug' => 'fatt-24-settings', 'label' => __('General Settings', 'fattura24')),
        array('slug' => 'fatt-24-tax', 'label' => __('Tax configuration', 'fattura24')),
        array('slug' => 'fatt-24-api-info', 'label' => __('API calls', 'fattura24')),
        array('slug' => 'fatt-24-support', 'label' => __('Support', 'fattura24')),
        array('slug' => 'fatt-24-videos', 'label' => fatt_24_video_icon() . __(' Video guides', 'fattura24')),
        array('slug' => 'fatt-24-app', 'label' => __('Mobile app', 'fattura24')),
        array('slug' => 'fatt-24-warning', 'label' => fatt_24_notice(). __(' Warning', 'fattura24')),
    ];
    return $fatt24Menus;
}

/**
 * ottengo la pagina attuale dalla url
 */
function fatt_24_get_page()
{
    $url = $_SERVER['REQUEST_URI'];
    $page = substr($url, strpos($url, 'page=') + 5);
    return $page;
}

/**
 * Barra di navigazione
 */
function fatt_24_build_nav_bar()
{
    $nav = '<nav class="nav-tab-wrapper woo-nav-tab-wrapper">';
    $tabs = fatt_24_settings_tabs();
    $activeTab = fatt_24_get_page();
       
    foreach ($tabs as $tab) {
        if ($tab['slug'] == $activeTab) {
            $nav .= '<a href="?page=' . $tab['slug'] . '"class="nav-tab nav-tab-active">' . $tab['label'] . '</a>';
        } else {
            $nav .= '<a href="?page=' . $tab['slug'] . '"class="nav-tab">' . $tab['label'] . '</a>';
        }
    }
    $nav .= '</nav>';
    return $nav;
}

function fatt_24_headers_style()
{
    return array('open' => '<div style="border-bottom:1px solid; padding-bottom: 10px; padding-top: 20px;">',
                 'close' => '</div>');
}

/**
 * Con questo metodo controllo se è già stata inserita una valutazione
 * i rating vengono registrati in wp_option (opzione fatt-24-woo-rating)
 * e confrontati con la data di oggi.  
 * 
 * @return $hidden => se la data dell'ultimo rating è maggiore di 6 mesi
 * 
 */
function fatt_24_getRating()
{
    $f24WooRatingValue = get_option('fatt-24-woo-rating');
    $hidden = false;
    if (!isset($f24WooRatingValue) || $f24WooRatingValue == '0' || $f24WooRatingValue == '{') {
         return $hidden;
    }

    $today = \DateTime::createFromFormat('d-m-Y', date('d-m-Y'));
    
    /**
     * controlli per compatibilità con codice precedente
     * magari in qualche caso il json si legge dal db anche se non credo
     */
    $lastReview = json_decode($f24WooRatingValue);
    
    if (is_object($lastReview)) {
        $lastReviewDateString = $lastReview->lastUpdate;
    } else {
        $lastReview = explode(' | ', $f24WooRatingValue);
        $lastReviewDateString = isset($lastReview[1]) ? $lastReview[1] : $today;
    }
    
    $lastReviewDateToTime = strtotime($lastReviewDateString);
    $lastReviewDate = \DateTime::createFromFormat('d-m-Y', date('d-m-Y', $lastReviewDateToTime));
    $comparison = $lastReviewDate->diff($today);
    $different_month = $comparison->m;
    $hidden = $different_month < 6;
        
    return $hidden;
}

/**
 *  Con questo metodo costruisco un array di 5 righe
 *  per 5 stelle; per ogni singola stella cambia solo il background
 *  per ogni singola riga (dalla 2a) viene applicato un background diverso
 */
function fatt_24_build_star_rows()
{
    /**
     *  Array di colori background: il primo codice
     *  corrisponde al grigio di default, gli altri:
     *  1 => rosso, una stella sola
     *  2 => arancione, due stelle
     *  3 => giallo, tre stelle
     *  4 => verde chiaro, 4 stelle
     *  5 => verde smeraldo, 5 stelle
     */
    $backgrounds = ['#fd3722;', '#fd8621;', '#fdce00;', '#72cf0f;', '#00b77b;', '#dbdbe3;'];
    $backgroundProp = 'background-color:';
    $styleStar = 'color: #fff; margin: 2px; padding: 2px;';
    $singleStar = array();
    $starsRow = array();
    $rowCounter = 1;
    while ($rowCounter <= 5) {
        for ($i = 0; $i<5; $i++) {
            switch ($rowCounter) {
                case 1:
                    $background = $i < $rowCounter ? $backgrounds[0] : end($backgrounds);
                    $singleStar[$i] = "<span style='$backgroundProp $background $styleStar' class='dashicons dashicons-star-filled'></span>";
                break;
                case 2:
                    $background = $i < $rowCounter ? $backgrounds[1] : end($backgrounds);
                    $singleStar[$i] = "<span style='$backgroundProp $background $styleStar' class='dashicons dashicons-star-filled'></span>";
                break;
                case 3:
                    $background = $i < $rowCounter ? $backgrounds[2] : end($backgrounds);
                    $singleStar[$i] = "<span style='$backgroundProp $background $styleStar' class='dashicons dashicons-star-filled'></span>";
                break;
                case 4:
                    $background = $i < $rowCounter ? $backgrounds[3] : end($backgrounds);
                    $singleStar[$i] = "<span style='$backgroundProp $background $styleStar' class='dashicons dashicons-star-filled'></span>";
                break;
                case 5:
                    $background = $i < $rowCounter ? $backgrounds[4] : end($backgrounds);
                    $singleStar[$i] = "<span style='$backgroundProp $background $styleStar' class='dashicons dashicons-star-filled'></span>";
                break;
                default:
                break;
            }
        }
        $starsRow[] = $singleStar;
        $rowCounter++;
    }
    return $starsRow;
}


// link alla documentazione F24
function fatt_24_doc_links()
{
    $links = [['link' => esc_url(admin_url('options-general.php?page=fatt-24-support')),
               'label' => __('Contact our tech service', 'fattura24')],
              ['link' => 'https://www.fattura24.com/documentazione-legale/condizioni-di-contratto',
               'label' => __('Fattura24 general terms and conditions of contract', 'fattura24')],
              ['link' => 'https://www.fattura24.com/documentazione-legale/regolamento-ecommerce',
               'label' => __('Supplementary F24 regulation for WooCommerce plugin', 'fattura24')],
              ['link' => 'https://www.fattura24.com/documentazione-legale/informativa-privacy',
               'label' => __('Privacy policy', 'fattura24')]
            ];
    return $links;
}

/**
 * Info box visibile in tutte le tab tranne l'ultima
 * Nella schermata di impostazioni generali posso visualizzare il box con il rating
 * collegato all'opzione F24_WOO_RATING se non è mai stato fatto click sull'opzione
 * oppure se l'ultima volta il click è stato fatto almeno 6 mesi fa
 */ 
function fatt_24_infobox($page = '')
{
    $hidden = fatt_24_getRating(); 
    $sendReview = '';
    $pStyle = 'margin:1px;';
    $links = fatt_24_doc_links();
    // voglio visualizzare i punteggi a partire dal più alto
    $buildedStarsRows = array_reverse(fatt_24_build_star_rows());
    $html = "<div style='background: #ffc; border: 1px solid #ccd0d4; margin: 10px; padding: 15px;'>";
    
    if (!$hidden && $page == 'fatt-24-settings') {
        $html .= "<div style='margin-bottom: 30px;' id='reviewContainer'>";
        $html .= "<h4>" . __('How would you rate Fattura24?', 'fattura24') . "</h4>";
        $html .= __('Write down a review and help us to improve.
			  All the reviews, either positive or negative, will be displayed immediately.', 'fattura24');
    
        // array delle righe
        $radioValue = 5;
        foreach ($buildedStarsRows as $key => $rowStars) {
            $html .= $key == 0? "<p style='margin-bottom: 1px;'>" : "<p style='$pStyle'>";
            $html .= "<div style='margin-bottom: -10px; cursor:pointer;' id='reviewBox$radioValue'>";
            $html .= "<input id='radioReview' style='cursor:pointer; background-color: #fff; margin: 5px;' type='radio' name='review' value='$radioValue' />";
            
            //array delle singole stelle
            foreach ($rowStars as $star) {
                $html .= $star;
            }
            $html .= "</div>";
            $html .= "</p>";
            $radioValue--;
        }
        $html .= "</div>";
    }
    
    $html .= "</p>";
    $html .= "<div style='margin-top: -10px;' id='linkContainer'>";
    $html .= "<h4>" . __('Do you need help ?', 'fattura24') . "</h4>";
    
    // array dei link
    foreach ($links as $link) {
        //var_dump($link);
        if (strpos($link['link'], 'fatt-24-support') !== false) {
            $html .= "<p><a style='text-decoration:none;' href=" . $link['link'] . ">";
        } else {
            $html .= "<p><a style='text-decoration:none;' href=" . $link['link'] . " target='_blank'>";
        }
        $html .= $link['label'] . "</a></p>";
    }

    $html .= "</div>";
    $html .= "<a style='text-align:center; display:block;' class='button button-primary' href='https://www.app.fattura24.com/v3' target='_blank'>";
    $html .= __('Log in Fattura24', 'fattura24');
    $html .= "</a>";
    $html .= "</div>";
        
    return $html;
}

/**
 * nuove opzioni del menu crea documento fiscale
 * Davide Iandoli 14.10.2021
 */
function fatt_24_getInvoiceOptionsNew()
{
    return array( '0' => __('Disabled', 'fattura24'),
                  '1' => __('Non-electronic Invoice', 'fattura24'),
                  '2' => __('Electronic Invoice', 'fattura24'),
                  '3' => __('Electronic Invoice (if IT customer else Receipt)', 'fattura24'),
                  '4' => __('Electronic Invoice (if IT customer else Invoice)', 'fattura24'),
                  '6' => __('Electronic Invoice (if EU customer else Receipt)', 'fattura24'),
                  '7' => __('Electronic Invoice (if EU customer else Invoice)', 'fattura24'),
                  '5' => __('Non-fiscal Receipt', 'fattura24')
            );
}

/**
 * Con questo metodo controllo se ci sono plugin di terze parti attivi che
 * aggiungono un campo p.iva; in caso positivo Fattura24 non aggiunge
 * il proprio campo nel checkout (nelle impostazioni appare il nome del plugin
 * che aggiunge il campo). In assenza viene aggiunto il nostro
 * Davide Iandoli 24.11.2022
 */
function fatt_24_getVatFieldFrom() {
    
    $result = __('Added by Fattura24', 'fattura24'); // default
    
    $activeAddons = array_column(fatt_24_get_plugin_info(), 'name');
    $suitableAddons = ['EU/UK VAT for WooCommerce', 'WooCommerce EU VAT Number'];
    
    foreach ($suitableAddons as $addon) {
        if (in_array($addon, $activeAddons)) {
            $result = sprintf(__('Added by %s', 'fattura24'), $addon);
        }
    }
    update_option('fatt-24-add-vat-field', $result);
    return $result;
}

/**
 * Ottengo nome e versione del plugin 
 * In ambienti multisito ottengo la lista di plugin attivi nel network 
 * Cfr.: https://wordpress.stackexchange.com/questions/54742/how-to-do-i-get-a-list-of-active-plugins-on-my-wordpress-blog-programmatically
 */
function fatt_24_get_plugin_info() {
    $plugins = is_multisite() ? array_keys(get_site_option('active_sitewide_plugins')) 
                    : get_option('active_plugins');
    $result = [];
    foreach ($plugins as $item) {
        $fullPath = WP_PLUGIN_DIR . '/'. $item;
        $result[] = array('name' => get_plugin_data($fullPath)['Name'],
                          'version' => get_plugin_data($fullPath)['Version']);
   
    }
    return $result;
}

function fatt_24_get_woo_lessons() {
    $lessons = [
            [
                'link' => 'https://youtu.be/svsJbyVNQmk',
                'img' => fatt_24_jpg('../assets/lezione1-wp-wc'),
                'title' => __('Lesson 1 - Plugin installation and first setup', 'fattura24'),
                'description' => __('In this lesson we will explain how to install and activate Fattura24 plugin for WooCommerce and we will setup a basic configuration', 'fattura24')
            ],
            [
                'link' => 'https://youtu.be/iTDYvCVyG1Q',
                'img' => fatt_24_jpg('../assets/lezione2-wp-wc'),
                'title' => __('Lesson 2 - WooCommerce tax settings and Natura IVA settings', 'fattura24'),
                'description' => __('In this lesson we will explain: how to enable tax calculation in WooCommerce, how to add VAT rates in the \'Standard\' tax class, how to add a zero rate, how to assign a Natura IVA code in Fattura24->Tax configuration and issue proper Electronic Invoices ', 'fattura24')
            ],
            [
                'link' => 'https://youtu.be/1UEjxzoWw2s',
                'img' => fatt_24_jpg('../assets/lezione3-wp-wc'),
                'title' => __('Lesson 3 - Order checkout, order status in WooCommerce and document creation in Fattura24', 'fattura24'),
                'description' => __('In this video we will emulate a customer purchase in our shop, focusing on some WooCommerce fields and on fields added by Fattura24. At last we will check the order status and the documents created in Fattura24', 'fattura24')
            ],
            [
                'link' => 'https://youtu.be/6ZOyQH8JLDA',
                'img' => fatt_24_jpg('../assets/lezione4-wp-wc'),
                'title' => __('Lesson 4 - WooCommerce default checkout fields, Fattura24 additional fields and changes to checkout behaviour', 'fattura24'),
                'description' => __('In this video we will explain what are the default fields in order checkout, what are the ones added by Fattura24, how to show or hide them, how Fattura24 modifies checkout\'s behaviour', 'fattura24')
            ],
            [
                'link' => 'https://youtu.be/LEmP5CWzVOg',
                'img' => fatt_24_jpg('../assets/lezione5-wp-wc'),
                'title' => __('Lesson 5 - send a ticket to Fattura24 tech service', 'fattura24'),
                'description' => __('In this video we will explain how to use the form in Support section of Fattura24 plugin and send a ticket to Fattura24', 'fattura24')
            ]
        ];
    return $lessons;
}

/**
 * Se queste estensioni non sono attive visualizzerò un messaggio di errore lato admin
 */
function fatt_24_get_required_extensions() {
    $f24_required_extensions = [
        'curl',
        'iconv',
        'mbstring',
        'libxml',
        'xmlwriter',
        'SimpleXML'
    ];
    $loaded_extensions = get_loaded_extensions();
    $result = [];
    foreach ($f24_required_extensions as $val) {
        if (!in_array($val, $loaded_extensions)) {
            $result[] = $val;
        }
    }
    return implode(', ', $result);
}