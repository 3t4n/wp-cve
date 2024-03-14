<?php
$pdckl_lang = [
   'main_title'  =>  'Podčlánková inzercia',
    'start_list'  =>  '<h3>Prvotné nastavenie pluginu</h3>
    <ol>
      <li>Kliknite na Rozírené nastavenie</li>
      <li>Nastavte plugin pod¾a svojej potreby (cenu, rubriky, týl zobrazenia)</li>
      <li>Vyberte monos platby:
        <ul>
          <li>Copywriting.cz - vyriei za vás byrokratické záležtosti, nepotrebujete žvnos</li>
          <li>PayPal.com - ku kadému predaju musíte vystavova faktúru, pozor na platby z cudzích krajín a legislatívu MOSS</li>
        </ul>
      </li>
      <li>
        Ak nemáte účet na Copywriting.cz, zalote si ho a potom v <b>Reklamný priestor (hore v menu) -> Ponúkam reklamu -> Moje weby -> Prida web</b>
      </li>
      <li>
        Po schválení webu (väčinou do 24 h) v <b>Paypost.cz (hore v menu) -> Ponúkam reklamu -> Moje weby -> <em>tento web</em> -> Nastavi podčlánkovú inzerciu</b>
      </li>
    </ol>',

    'main_description'          => 'Podčlánková inzercia je plugin, ktorý umoňuje na web vlož rýchlu inzerciu. Pod kadým článkom je zobrazený box, kde pouívate¾ môe pomocou 1 kliknutia zakúpi reklamnú pozíciu a okamžte ju zaplati pomocou. Ihneï po zaplatení sa reklama začne zobrazova. Reklama je na webe umiestnená natrvalo.',
    'main_menu_settings'        => 'Nastavenie',
    'main_menu_orders'          => 'Preh¾ad objednávok',
    'main_menu_css'             => 'Vlastný týl',
    'main_menu_help'           => 'Nápoveda',

    /* BUTTONS */
    'btn_save'                  => 'Ulož nastavenie',
    'btn_original'              => 'Pôvodný',
    'btn_add_link'              => 'Prida odkaz',
    'btn_close'                 => 'Zavrie',
    'btn_create_backup'         => 'Vytvori zálohu',
    'btn_restore_backup'        => 'Nahra zálohu',

    /* PAGES */
    'settings_overview'         => 'Preh¾ad',
    'settings_active'           => 'Plugin je aktívny:',
    'settings_active_on'        => 'Aktívny, v poriadku',
    'settings_active_off'       => 'Plugin je vypnutý!',
    'settings_version'          => 'Verzia pluginu:',
    'settings_version_actual'   => 'Aktuálny, v poriadku',
    'settings_version_old'      => array('Pouívate zastaranú verziu pluginu!',
                                         'Aktuálna verzia:',
                                         'Stiahnu',
                                         'Pravdepodobne pouívate testovaciu verziu pluginu!'),
    'settings_curl'             => 'Funkcia cURL():',
    'settings_curl_on'          => 'Zapnuté, v poriadku',
    'settings_curl_off'         => 'Funkcia je vypnutá! Plugin nemono aktivova!',
    'settings_tables'           => 'Databázová tabu¾ka:',
    'settings_tables_found'     => 'Nájdené, v poriadku',
    'settings_tables_error'     => array('Nenájdené! Plugin nemono aktivova!',
                                        'Skúste plugin deaktivova a opätovne aktivova',
                                        'Ak nepomôe krok 1 pridajte tabu¾ky ručne',
                                        'Poprípade môžete kontaktova autora pluginu',
                                        'tu'),
    'settings_plugin_global_title'     => 'Nastavenie - plugin',
    'settings_plugin_desc'      => 'Box sa zobrazí automaticky pod príspevkom, nemusíte tak pridáva do ablóny žadny kód',
    'settings_plugin_enable'    => array('Povoli plugin:',
                                        'Áno',
                                        'Nie'),
    'settings_plugin_purchase'  => array('Povoli nákup reklamy:',
                                         'Áno',
                                         'Nie'),
    'settings_plugin_jquery'    => array('Načíta jQuery kni¾nice:',
                                         'Áno',
                                         'Nie'),
    'settings_plugin_title'     =>  'Titulok boxu',
    'settings_plugin_auto'    => array('Automaticky vlož box do ablóny:',
                                         'Áno',
                                         'Nie'),
    'settings_plugin_auto_function' =>  'Do ablóny vlote túto funkciu:',
    'settings_plugin_showform'    => array('Formulár pre nákup reklamy',
                                         '<abbr title="Pod článkem se zobrazí přímo formulář s moností nákupu">Zobrazova priamo</abbr>',
                                         '<abbr title="Je nutné kliknout na Zobrazit formulář pro nákup reklamy, aby se zobrazil formulář">Zobrazi a po kliknutí</abbr>'),
    'settings_plugin_banned_cats' =>  'Nezobrazova v týchto rubrikách:',
    'settings_plugin_links'     => 'Počet odkazov pod článkom:',
    'settings_plugin_price'     => 'Cena odkazu:',
    'settings_plugin_price_ext' => array('Cena odkazu EXTRA:',
                                         'ak je článok starí ne',
                                         'dní'),
    'settings_currency'         => 'Kč',
    'settings_type'             => 'Nastavenie parametra odkazu',
    'settings_type_both'        => 'Nechať kupca vybrať, či follow / nofollow',
    'settings_type_follow'      => 'Iba follow odkazy',
    'settings_type_nofollow'    => 'Iba nofollow odkazy',
    'settings_pixel'            => 'Konverzný pixel',
    'settings_paypal_title'     => 'Platieb cez PayPal.com',
    'settings_paypal_desc'      => 'Návod na nastavenie PayPalu krok po kroku si <a href="https://www.paypost.cz/napoveda/4" target="_blank">stiahnete zadarmo tu</a>. <strong style="color: #ff000;">POZOR! Pri tejto voľby platby musíte dávať pozor na MOSS!</strong>',
    'settings_paypal_api_user'  => 'API username:',
    'settings_paypal_api_pwd'   => 'API password:',
    'settings_paypal_api_sign'  => 'API signature:',
    'settings_paypal_mode'      => 'PayPal prostredie:',
    'settings_wd_api_token'     => 'API token:',
    'settings_copywriting_title'=> 'Platby cez Copywriting.cz',
    'settings_copywriting_desc' => 'Tu môžete prepoji plugin s Vaím <a href="https://www.copywriting.cz" target="_blank">Copywriting účtom</a>',
    'settings_donate_title'     => 'Podporte vývoj pluginu!',
    'settings_donate_desc'      => 'Tento plugin je ponúkaný úplne zadarmo a bez akýchkovek obmedzení. Vývoju pluginu som venoval svoj vo¾ný čas a pracoval som bez nároku na akúko¾vek odmenu. Ak by ste chceli podpori vývoj pluginu, môžete tak urobi zaslaním nejakej sumy na PayPal pomocou tlačidla niie.',

    'orders_table_ido'          => 'ID',
    'orders_table_idp'          => 'Názov článku',
    'orders_table_date'         => 'Dátum objednania',
    'orders_table_link'         => 'Odkaz',
    'orders_table_tools'        => 'Nástroje',
    'orders_empty'              => 'Zatia¾ nebola vykonaná žadna objednávka',
    'orders_tools_hide'         => 'Skry',
    'orders_tools_show'         => 'Zobrazi',
    'orders_tools_edit'         => 'Upravi',
    'orders_tools_delete'       => 'Odstráni',
    'orders_add_title'          => 'Prida nový odkaz',
    'orders_add_idp'            => 'ID článku',
    'orders_add_link'           => 'Odkaz',
    'orders_add_name'           => 'Názov',
    'orders_add_desc'           => 'Popisok',
    'orders_edit_title'         => 'Upravi objednávku',
    'orders_edit_ido'           => 'ID objednávky:',
    'orders_edit_idp'           => 'ID článku:',
    'orders_edit_purchased'     => 'Zakúpené:',
    'orders_edit_link'          => 'Odkaz:',
    'orders_backup_tip'         => 'TIP: Kadý deň prebieha záloha na servery Copywriting.cz, k obnoveniu zálohy môžete teda použ nau zálohu. Tá na rozdiel od vaej ručnej zálohy nepremae existujúce odkazy, iba doplní chýbajúce.',

    'css_title'                 => 'Upravi CSS',
    'css_desc'                  => 'Tu môžete zmeni vzh¾ad boxu (CSS), ktorý sa zobrazuje pod jednotlivými článkami',
    'css_preview'               => 'Náh¾ad',

    /* NOTICES */
    'n_plugin_disabled'         => 'Plugin je aktuálne vypnutý',
    'n_settings_updated'        => 'Nastavenie bolo úspene aktualizované',
    'n_settings_update_error'   => array('Cena za článok musí by väčia než 0',
                                         'Musíte vyplni PayPal údaje alebo pripoji Copywriting účet',
                                         'Nemáte vyplnené vetky potrebné PayPal údaje'),
    'n_settings_token_error'    => 'Nesprávny token pre prepojenie s Copywriting.cz',
    'n_settings_wd_disconnected'=> 'Účet Copywriting bol úspene odpojený',
    'n_orders_hiden'            => 'Objednávka #%d bola skrytá a odkaz sa teraz na webe nezobrazuje',
    'n_orders_shown'            => 'Objednávka #%d sa teraz na webe zobrazuje',
    'n_orders_added'            => 'Odkaz bol úspene pridaný do databázy (ID objednávky: %d)',
    'n_orders_edited'           => 'Objednávka #%d bola úspene upravená',
    'n_orders_delete'           => 'Naozaj odstráni objednávku #%d? Ak tak urobíte, odkaz sa na webe prestane zobrazova. <strong>TENTO KROK JE NEVRATNÝ!</strong>',
    'n_orders_delete_link'      => 'Rozumiem, odstráni',
    'n_orders_deleted'          => 'Objednávka #%d bola úspene odstránená, odkaz se u na webe nezobrazuje',
    'n_css_update_done'         => 'Nastavenie CSS bolo úspene zmenené',
    'n_css_update_error'        => 'Nastavenie CSS sa nepodarilo ulož',
    'n_css_reset_done'          => 'Nastavenie CSS bolo vrátené do pôvodného stavu',
    'n_css_reset_error'         => 'Nastavenie CSS sa nepodarilo vráti do pôvodného stavu',

    /* HELP */
    'h_settings_status_on'      => 'Plugin je aktuálne zapnutý a pod článkami sa zobrazuje reklama a mono ju zakúpi',
    'h_settings_status_off'     => 'Plugin je aktuálne vypnutý a pod článkami sa nezobrazuje žadna reklama a nemono ju zakúpi',
    'h_settings_curl_on'        => 'Na vaom hostingu je cURL povolené a plugin pracuje správne',
    'h_settings_curl_off'       => 'Na vaom hostingu nie je povolená funkcia cURL, alebo je vypnutá. Plugin nemôe pracova správne',
    'h_settings_version_act'    => 'Pouívate najnoviu a najaktuálnejiu verziu pluginu',
    'h_settings_version_old'    => 'Pouívate zastaranú a neaktuálnu verziu pluginu, odporúčame vykona aktualizáciu',
    'h_settings_version_err'    => 'Verziu pluginu sa nepodarilo overi, pretoe na vaom hostingu je zakázaná alebo vypnutá funkcia "file_get_contents"',
    'h_settings_tables_ok'      => 'Databázové tabuky nutné pre správne fungovanie pluginu boli nájdené',
    'h_settings_tables_err'     => 'Databázové tabuky nutné pre správne fungovanie pluginu NEBOLI nájdené a preto plugin nemôe fungova správne',
    'h_settings_status'         => 'Určuje, či je plugin vypnutý alebo zapnutý. V prípade, e je plugin vypnutý, reklama sa nezobrazuje a nemono ju ani zakúpi',
    'h_settings_purchase'       => 'Určuje, či je moné na webe zakúpi reklamu.
V prípade, e nákup zakáete, nebude moné zakúpi pod článkom reklamu, ale dosia¾ zakúpené odkazy sa budú na webe zobrazova',
    'h_settings_pixel'          => 'Táto adresa sa zavolá, Akonáhle u Vás niekto nakúpi podčlánkovou inzerciu',
    'h_settings_jquery'         => 'Povolí/Zakáe načítanie jQuery kninice z http://ajax.googleapis.com/ajax/libs/jquery/
Zakáte v prípade, e po naintalovaní pluginu dôjde na webe k chybám v iných pluginoch (nie vdy zakázanie pomôe)',
    'h_settings_auto'           => 'Automaticky vloí box do ablóny pod príspevky. Ak zakrtnete Nie, do ablóny budete musie vlož funkciu ručne.',
    'h_settings_showform'       => 'Nastavenie zobrazovania formulára pre nákup reklamy v boxu pri článku',
    'h_settings_title'          => 'Titulok, ktorý sa bude zobrazova pred boxom.<br /><b>Premenná $price dosadí do nadpisu hodnotu.</b><br />Je odporúčané ju uvies v nadpise, pretoe vo formulári sa inde tento údaj neobjevuje.',
    'h_settings_banned_cats'    => 'Zadajte ID rubrík, tie odde¾ujte čiarkou bez medzier. Príklad: <b>1,3,15,16</b> alebo v prípade jednej rubriky <b>2</b>',
    'h_settings_max_links'      => 'Určuje, ko¾ko mono pod kadým článkom kúpi maximálne odkazov.
V prípade, e je dosiahnutý nastavený počet, pod daným článkom u nemono zakúpi ïaliu reklamu',
    'h_settings_link_price'     => 'Suma musí by vyia ako 0',
    'h_settings_link_price_ext' => 'Ak je článok starí ne zadaný počet dní, zmení sa jeho cena na tu zadanú. <br>
Pokial chcete cenu článku vdy rovnakú, zadajte do po¾a pre extra cenu 0',
    'h_settings_copywriting'    => 'Token na prepojenie pluginu s Vaím Copywriting účtom si móete vygenerova tu:<br><b>PayPost.cz -> Ponúkam reklamu -> Moje weby -> <i>%s</i> -> Nastavenie podčlánkovej inzercie</b>',
    'h_settings_type'           => 'Voľba nezmení už predané odkazy',
    'h_add_link_clid'           => 'Zadajte ID článku, pod ktorý chcete reklamu prida',
    'h_add_link_link'           => 'Zadajte URL odkazu - VČETNE http://',
    'h_add_link_name'           => 'Zadajte názov odkazu - tento názov bude klikate¾ný',
    'h_add_link_desc'           => 'Zadajte popisok odkazu - objaví sa za odkazom a bude neklikate¾ný'
];
?>
