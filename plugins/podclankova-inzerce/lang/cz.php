<?php
$pdckl_lang = [
    'main_title'  =>  'Podčlánková inzerce',
    'start_list'  =>  '<h3>Prvotní nastavení pluginu</h3>
    <ol>
      <li>Klikněte na Rozšířené nastavení</li>
      <li>Nastavte plugin podle své potřeby (cenu, rubriky, styl zobrazení)</li>
      <li>Vyberte možnost platby:
        <ul>
          <li>Copywriting.cz - vyřeší za vás byrokratické záležitosti, nepotřebujete živnost</li>
          <li>PayPal.com - ke každému prodeji musíte vystavovat fakturu, pozor na platby z cizích zemí a legislativu MOSS</li>
        </ul>
      </li>
      <li>
        Pokud nemáte účet na Copywriting.cz/PayPost.cz, založte si jej a pak v <b>PayPost.cz (nahoře v menu) -> Nabízím reklamu -> Moje weby -> Přidat web</b>
      </li>
      <li>
        Po schválení webu (většinou do 24h) v <b>PayPost.cz (nahoře v menu) -> Nabízím reklamu -> Moje weby -> <em>tento web</em> -> Nastavit podčlánkovou inzerci</b>
      </li>
    </ol>',


    'main_description'          => 'Podčlánková inzerce je plugin, který umožňuje na web vložit rychlou inzerci. Pod každým článkem je zobrazen box, kde uživatel může pomocí 1 kliknutí zakoupit reklamní pozici a okamžitě ji zaplatit pomocí. Ihned po zaplacení se reklama začne zobrazovat. Reklama je na webu umístěna trvale.',
    'main_menu_settings'        => 'Nastavení',
    'main_menu_orders'          => 'Přehled objednávek',
    'main_menu_css'             => 'Vlastní styl',
    'main_menu_help'            => 'Nápověda',

    /* BUTTONS */
    'btn_save'                  => 'Uložit nastavení',
    'btn_original'              => 'Původní',
    'btn_add_link'              => 'Přidat odkaz',
    'btn_close'                 => 'Zavřít',
    'btn_create_backup'         => 'Vytvořit zálohu',
    'btn_restore_backup'        => 'Nahrát zálohu',

    /* PAGES */
    'settings_overview'         => 'Přehled',
    'settings_active'           => 'Plugin je aktivní:',
    'settings_active_on'        => 'Aktivní, v pořádku',
    'settings_active_off'       => 'Plugin je vypnutý!',
    'settings_version'          => 'Verze pluginu:',
    'settings_version_actual'   => 'Aktuální, v pořádku',
    'settings_version_old'      => ['Používáte zastaralou verzi pluginu!',
                                    'Aktuální verze:',
                                    'Stáhnout',
                                    'Nejspíše používáte testovací verzi pluginu!'],
    'settings_curl'             => 'Funkce cURL():',
    'settings_curl_on'          => 'Zapnuto, v pořádku',
    'settings_curl_off'         => 'Funkce je vypnutá! Plugin nelze aktivovat!',
    'settings_tables'           => 'Databázová tabulka:',
    'settings_tables_found'     => 'Nalezeno, v pořádku',
    'settings_tables_error'     => ['Nenalezeno! Plugin nelze aktivovat!',
                                    'Zkuste plugin deaktivovat a opětovně aktivovat',
                                    'Pokud nepomůže krok 1 přidejte tabulky ručně',
                                    'Popřípadě můžete kontaktovat autora pluginu',
                                    'zde'],
    'settings_plugin_global_title'     => 'Nastavení - plugin',
    'settings_plugin_desc'      => 'Box se zobrazí automaticky pod příspěvkem, nemusíte tak přidávat do šablony žádný kód',
    'settings_plugin_enable'    => ['Povolit plugin:',
                                    'Ano',
                                    'Ne'],
    'settings_plugin_purchase'  => ['Povolit nákup reklamy:',
                                         'Ano',
                                         'Ne'],
    'settings_plugin_jquery'    => array('Načíst jQuery knihovny:',
                                         'Ano',
                                         'Ne'),
    'settings_plugin_auto'    => array('Automaticky vložit box do šablony:',
                                         'Ano',
                                         'Ne'),
    'settings_plugin_auto_function' =>  'Do šablony vložte tuto funkci:',
    'settings_plugin_title'     =>  'Titulek boxu',
    'settings_plugin_showform'    => array('Formulář pro nákup reklamy',
                                         '<abbr title="Pod článkem se zobrazí přímo formulář s možností nákupu">Zobrazovat přímo</abbr>',
                                         '<abbr title="Je nutné kliknout na „Zobrazit formulář pro nákup reklamy”, aby se zobrazil formulář">Zobrazit až po kliknutí</abbr>'),
    'settings_plugin_banned_cats' =>  'Nezobrazovat v těchto rubrikách:',
    'settings_plugin_links'     => 'Počet odkazů pod článkem:',
    'settings_plugin_price'     => 'Cena odkazu:',
    'settings_plugin_price_ext' => array('Cena odkazu EXTRA:',
                                         'pokud je článek starší než',
                                         'dní'),
    'settings_currency'         => 'Kč',
    'settings_type'             => 'Nastavení parametru odkazu',
    'settings_type_both'        => 'Nechat kupce vybrat, zda follow/nofollow',
    'settings_type_follow'      => 'Pouze follow odkazy',
    'settings_type_nofollow'    => 'Pouze nofollow odkazy',
    'settings_pixel'            => 'Konverzní pixel',
    'settings_paypal_title'     => 'Plateb přes PayPal.com',
    'settings_paypal_desc'      => 'Návod na nastavení PayPalu krok po kroku si <a href="https://www.paypost.cz/napoveda/4" target="_blank">stáhněte zdarma zde</a>. <strong style="color: #ff0000;">POZOR! U této volby platby musíte dávat pozor na <a href="https://www.financnisprava.cz/cs/mezinarodni-spoluprace/mezinarodni-spoluprace-a-dph/mini-one-stop-shop">MOSS</a>!</strong>',
    'settings_paypal_api_user'  => 'API username:',
    'settings_paypal_api_pwd'   => 'API password:',
    'settings_paypal_api_sign'  => 'API signature:',
    'settings_paypal_mode'      => 'PayPal prostředí:',
    'settings_wd_api_token'     => 'API token:',
    'settings_copywriting_title'=> 'Platby přes Copywriting.cz',
    'settings_copywriting_desc' => 'Zde můžete propojit plugin s Vašim <a href="https://www.copywriting.cz" target="_blank">Copywriting účtem</a>',
    'settings_donate_title'     => 'Podpořte vývoj pluginu!',
    'settings_donate_desc'      => 'Tento plugin je nabízen zcela zdarma a bez jakýchkoliv omezení. Vývoji pluginu jsem věnoval svůj volný čas a pracoval jsem bez nároku na jakoukoliv odměnu. Pokud byste chtěli podpořit vývoj pluginu, můžete tak učinit zasláním nějaké částky na PayPal pomocí tlačítka níže.',

    'orders_table_ido'          => 'ID',
    'orders_table_idp'          => 'Název článku',
    'orders_table_date'         => 'Datum objednání',
    'orders_table_link'         => 'Odkaz',
    'orders_table_tools'        => 'Nástroje',
    'orders_empty'              => 'Prozatím nebyla provedena žádná objednávka',
    'orders_tools_hide'         => 'Skrýt',
    'orders_tools_show'         => 'Zobrazit',
    'orders_tools_edit'         => 'Upravit',
    'orders_tools_delete'       => 'Odstranit',
    'orders_add_title'          => 'Přidat nový odkaz',
    'orders_add_idp'            => 'ID článku',
    'orders_add_link'           => 'Odkaz',
    'orders_add_name'           => 'Název',
    'orders_add_desc'           => 'Popisek',
    'orders_edit_title'         => 'Upravit objednávku',
    'orders_edit_ido'           => 'ID objednávky:',
    'orders_edit_idp'           => 'ID článku:',
    'orders_edit_purchased'     => 'Zakoupeno:',
    'orders_edit_link'          => 'Odkaz:',
    'orders_backup_tip'         => 'TIP: Každý den probíhá záloha na servery Copywriting.cz, k obnovení zálohy můžete tedy použít naši zálohu. Ta narozdíl od vaší ruční zálohy nepřemaže existující odkazy, pouze doplní chybějící.',

    'css_title'                 => 'Upravit CSS',
    'css_desc'                  => 'Zde můžete změnit vzhled boxu (CSS), který se zobrazuje pod jednotlivými články',
    'css_preview'               => 'Náhled',

    /* NOTICES */
    'n_plugin_disabled'         => 'Plugin je aktuálně vypnutý',
    'n_settings_updated'        => 'Nastavení bylo úspěšně aktualizováno',
    'n_settings_update_error'   => [
                                        'Cena za článek musí být větší než 0',
                                        'Musíte vyplnit PayPal údaje nebo připojit Copywriting účet',
                                        'Nemáte vyplněny všechny potřebné PayPal údaje'
                                   ],
    'n_settings_token_error'    => 'Nesprávný token pro propojení s Copywriting.cz',
    'n_settings_wd_disconnected'=> 'Účet Copywriting byl úspešně odpojen',
    'n_orders_hiden'            => 'Objednávka #%d byla skryta a odkaz se nyní na webu nezobrazuje',
    'n_orders_shown'            => 'Objednávka #%d se nyní na webu zobrazuje',
    'n_orders_added'            => 'Odkaz byl úspěšně přidán do databáze (ID objednávky: %d)',
    'n_orders_edited'           => 'Objednávka #%d byla úspěšně upravena',
    'n_orders_delete'           => 'Opravdu odstranit objednávku #%d? Pokud tak učiníte, odkaz se na webu přestane zobrazovat. <strong>TENTO KROK JE NEVRATNÝ!</strong>',
    'n_orders_delete_link'      => 'Rozumím, odstranit',
    'n_orders_deleted'          => 'Objednávka #%d byla úspěšně odstraněna, odkaz se již na webu nezobrazuje',
    'n_css_update_done'         => 'Nastavení CSS bylo úspěšně změněno',
    'n_css_update_error'        => 'Nastavení CSS se nepodařilo uložit',
    'n_css_reset_done'          => 'Nastavení CSS bylo vráceno do původního stavu',
    'n_css_reset_error'         => 'Nastavení CSS se nepodařilo vrátit do původního stavu',

    /* HELP */
    'h_settings_status_on'      => 'Plugin je aktuálně zapnutý a pod články se zobrazuje reklama a lze ji zakoupit',
    'h_settings_status_off'     => 'Plugin je aktuálně vypnutý a pod články se nezobrazuje žádná reklama a nelze ji zakoupit',
    'h_settings_curl_on'        => 'Na vašem hostingu je cURL povoleno a plugin pracuje správně',
    'h_settings_curl_off'       => 'Na vašem hostingu není povolena funkce cURL a nebo je vypnutá. Plugin nemůže pracovat správně',
    'h_settings_version_act'    => 'Používáte nejnovější a nejaktuálnější verzi pluginu',
    'h_settings_version_old'    => 'Používáte zastaralou a neaktuální verzi pluginu, doporučujeme provést aktualizaci',
    'h_settings_version_err'    => 'Verzi pluginu se nepodařilo ověřit, protože na vašem hostingu je zakázána nebo vypnutá funkce "file_get_contents"',
    'h_settings_tables_ok'      => 'Databázové tabulky nutné pro správné fungování pluginu byly nalezeny',
    'h_settings_tables_err'     => 'Databázové tabulky nutné pro správné fungování pluginu NEBYLY nalezeny a proto plugin nemůže fungovat správně',
    'h_settings_status'         => 'Určuje, zda je plugin vypnutý či zapnutý. V případě, že je plugin vypnutý, reklama se nezobrazuje a nelze ji ani zakoupit',
    'h_settings_purchase'       => 'Určuje, zda je možné na webu zakoupit reklamu.
V případě, že nákup zakážete, nebude možné zakoupit pod článkem reklamu avšak dosud zakoupené odkazy se budou na webu zobrazovat',
    'h_settings_pixel'          => 'Tato adresa se zavolá, jakmile u Vás někdo nakoupí podčlánkovou inzerci',
    'h_settings_jquery'         => 'Povolí/Zakáže načtení jQuery knihovny z http://ajax.googleapis.com/ajax/libs/jquery/
Zakažte v případě, že po nainstalování pluginu dojde na webu k chybám v jiných pluginech (ne vždy zakázání pomůže)',
    'h_settings_auto'       => 'Automaticky vloží box do šablony pod příspěvky. Pokud zaškrtnete Ne, do šablony budete muset vložit funkci ručně.',
    'h_settings_showform'       => 'Nastavení zobrazování formuláře pro nákup reklamy v boxu u článku',
    'h_settings_title'       => 'Titulek, který se bude zobrazovat před boxem.<br /><b>Proměnná $price dosadí do nadpisu hodnotu.</b><br />Je doporučeno ji uvést v nadpisu, protože ve formuláři se jinde tento údaj neobjevuje.',
    'h_settings_banned_cats'       => 'Zadejte ID rubrik, ty oddělujte čárkou bez mezer. Příklad: <b>1,3,15,16</b> nebo v případě jedné rubriky <b>2</b>',
    'h_settings_max_links'      => 'Určuje, kolik lze pod každým článkem koupit maximálně odkazů.
V případě, že je dosažen nastavený počet, pod daným článkem již nelze zakoupit další reklamu',
    'h_settings_link_price'     => 'Částka musí být vyšší než 0',
    'h_settings_link_price_ext' => 'Pokud je článek starší než zadaný počet dnů, změní se jeho cena na zde zadanou.
Pokud chcete cenu článku vždy stejnou, zadejte do pole pro extra cenu 0',
    'h_settings_copywriting'    => 'Token pro propojení pluginu s Vaším Copywriting účtem si můžete vygenerovat zde:<br><b>Paypost.cz -> Nabízím reklamu -> Moje weby -> <i>%s</i> -> Nastavení podčlánkové inzerce</b>',
    'h_settings_type'           => 'Volba neovlivní už zakoupené odkazy',
    'h_add_link_clid'           => 'Zadejte ID článku, pod který chcete reklamu přidat',
    'h_add_link_link'           => 'Zadejte URL odkazu - VČETNĚ https://',
    'h_add_link_name'           => 'Zadejte název odkazu - tento název bude klikatelný',
    'h_add_link_desc'           => 'Zadejte popisek odkazu - objeví se za odkazem a bude neklikatelný'
];
?>
