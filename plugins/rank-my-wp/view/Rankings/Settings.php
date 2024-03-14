<div id="rkmw_wrap">
    <?php RKMW_Classes_ObjController::getClass('RKMW_Core_BlockToolbar')->init(); ?>
    <?php do_action('rkmw_notices'); ?>
    <div class="d-flex flex-row my-0 bg-white">
        <?php
        if (!current_user_can('rkmw_manage_rankings')) {
            echo '<div class="col-sm-12 alert alert-success text-center m-0 p-3">'. esc_html__("You do not have permission to access this page. You need RKMW Admin role.", RKMW_PLUGIN_NAME).'</div>';
            return;
        }
        ?>
        <?php echo RKMW_Classes_ObjController::getClass('RKMW_Models_Menu')->getAdminTabs(RKMW_Classes_Helpers_Tools::getValue('tab'), 'rkmw_rankings'); ?>
        <div class="d-flex flex-row flex-nowrap flex-grow-1 bg-white pl-1 pr-1 mr-0">
            <div class="flex-grow-1 mr-2 rkmw_flex">

                <form method="POST">
                    <?php do_action('rkmw_form_notices'); ?>
                    <?php RKMW_Classes_Helpers_Tools::setNonce('rkmw_ranking_settings', 'rkmw_nonce'); ?>
                    <input type="hidden" name="action" value="rkmw_ranking_settings"/>

                    <div class="card col-sm-12 p-0">
                        <div class="card-body p-0 m-0 bg-title rounded-top  row">
                            <div class="card-body p-2 bg-title rounded-top">
                                <div class="rkmw_icons_content p-3 py-4">
                                    <div class="rkmw_icons rkmw_settings_icon m-2"></div>
                                </div>
                                <h3 class="card-title py-4"><?php echo esc_html__("Rankings Settings", RKMW_PLUGIN_NAME); ?>:</h3>
                                <div class="card-title-description m-2"></div>
                            </div>
                        </div>
                        <div id="rkmw_settings" class="card col-sm-12 p-0 m-0 border-0 tab-panel border-0">
                            <div class="card-body p-0">
                                <div class="col-sm-12 m-0 p-0">
                                    <div class="card col-sm-12 p-0 border-0 ">

                                        <div class="col-sm-12 pt-0 pb-4 border-bottom tab-panel">


                                            <div class="col-sm-12 row py-2 mx-0 my-3">
                                                <div class="col-sm-4 p-1 pr-3">
                                                    <div class="font-weight-bold"><?php echo esc_html__("Google Country", RKMW_PLUGIN_NAME); ?>:</div>
                                                    <div class="small text-black-50"><?php echo esc_html__("Select the Google country for which Rank My WP will check the Google rank.", RKMW_PLUGIN_NAME); ?></div>
                                                </div>
                                                <div class="col-sm-8 p-0 input-group">
                                                    <select name="google_country" class="form-control bg-input mb-1">
                                                        <option value="com"><?php echo esc_html__("Default", RKMW_PLUGIN_NAME); ?> - Google.com (http://www.google.com/)</option>
                                                        <option value="as"><?php echo esc_html__("American Samoa", RKMW_PLUGIN_NAME); ?> (http://www.google.as/)</option>
                                                        <option value="off.ai"><?php echo esc_html__("Anguilla", RKMW_PLUGIN_NAME); ?> (http://www.google.off.ai/)</option>
                                                        <option value="com.ag"><?php echo esc_html__("Antigua and Barbuda", RKMW_PLUGIN_NAME); ?> (http://www.google.com.ag/)</option>
                                                        <option value="com.ar"><?php echo esc_html__("Argentina", RKMW_PLUGIN_NAME); ?> (http://www.google.com.ar/)</option>
                                                        <option value="com.au"><?php echo esc_html__("Australia", RKMW_PLUGIN_NAME); ?> (http://www.google.com.au/)</option>
                                                        <option value="at"><?php echo esc_html__("Austria", RKMW_PLUGIN_NAME); ?> (http://www.google.at/)</option>
                                                        <option value="az"><?php echo esc_html__("Azerbaijan", RKMW_PLUGIN_NAME); ?> (http://www.google.az/)</option>
                                                        <option value="be"><?php echo esc_html__("Belgium", RKMW_PLUGIN_NAME); ?> (http://www.google.be/)</option>
                                                        <option value="com.br"><?php echo esc_html__("Brazil", RKMW_PLUGIN_NAME); ?> (http://www.google.com.br/)</option>
                                                        <option value="vg"><?php echo esc_html__("British Virgin Islands", RKMW_PLUGIN_NAME); ?> (http://www.google.vg/)</option>
                                                        <option value="bi"><?php echo esc_html__("Burundi", RKMW_PLUGIN_NAME); ?> (http://www.google.bi/)</option>
                                                        <option value="bg"><?php echo esc_html__("Bulgaria", RKMW_PLUGIN_NAME); ?> (http://www.google.bg/)</option>
                                                        <option value="ca"><?php echo esc_html__("Canada", RKMW_PLUGIN_NAME); ?> (http://www.google.ca/)</option>
                                                        <option value="td"><?php echo esc_html__("Chad", RKMW_PLUGIN_NAME); ?> (http://www.google.td/)</option>
                                                        <option value="cl"><?php echo esc_html__("Chile", RKMW_PLUGIN_NAME); ?> (http://www.google.cl/)</option>
                                                        <option value="com.co"><?php echo esc_html__("Colombia", RKMW_PLUGIN_NAME); ?> (http://www.google.com.co/)</option>
                                                        <option value="co.cr"><?php echo esc_html__("Costa Rica", RKMW_PLUGIN_NAME); ?> (http://www.google.co.cr/)</option>
                                                        <option value="ci"><?php echo esc_html__("Côte d\'Ivoire", RKMW_PLUGIN_NAME); ?> (http://www.google.ci/)</option>
                                                        <option value="com.cu"><?php echo esc_html__("Cuba", RKMW_PLUGIN_NAME); ?> (http://www.google.com.cu/)</option>
                                                        <option value="cz"><?php echo esc_html__("Czech Republic", RKMW_PLUGIN_NAME); ?> (http://www.google.cz/)</option>
                                                        <option value="cd"><?php echo esc_html__("Dem. Rep. of the Congo", RKMW_PLUGIN_NAME); ?> (http://www.google.cd/)</option>
                                                        <option value="dk"><?php echo esc_html__("Denmark", RKMW_PLUGIN_NAME); ?> (http://www.google.dk/)</option>
                                                        <option value="dj"><?php echo esc_html__("Djibouti", RKMW_PLUGIN_NAME); ?> (http://www.google.dj/)</option>
                                                        <option value="com.do"><?php echo esc_html__("Dominican Republic", RKMW_PLUGIN_NAME); ?> (http://www.google.com.do/)</option>
                                                        <option value="com.ec"><?php echo esc_html__("Ecuador", RKMW_PLUGIN_NAME); ?> (http://www.google.com.ec/)</option>
                                                        <option value="com.eg"><?php echo esc_html__("Egypt", RKMW_PLUGIN_NAME); ?> (http://www.google.com.eg/)</option>
                                                        <option value="com.sv"><?php echo esc_html__("El Salvador", RKMW_PLUGIN_NAME); ?> (http://www.google.com.sv/)</option>
                                                        <option value="ee"><?php echo esc_html__("Estonia", RKMW_PLUGIN_NAME); ?> (http://www.google.ee/)</option>
                                                        <option value="fm"><?php echo esc_html__("Federated States of Micronesia", RKMW_PLUGIN_NAME); ?> (http://www.google.fm/)</option>
                                                        <option value="com.fj"><?php echo esc_html__("Fiji", RKMW_PLUGIN_NAME); ?> (http://www.google.com.fj/)</option>
                                                        <option value="fi"><?php echo esc_html__("Finland", RKMW_PLUGIN_NAME); ?> (http://www.google.fi/)</option>
                                                        <option value="fr"><?php echo esc_html__("France", RKMW_PLUGIN_NAME); ?> (http://www.google.fr/)</option>
                                                        <option value="gm"><?php echo esc_html__("The Gambia", RKMW_PLUGIN_NAME); ?> (http://www.google.gm/)</option>
                                                        <option value="ge"><?php echo esc_html__("Georgia", RKMW_PLUGIN_NAME); ?> (http://www.google.ge/)</option>
                                                        <option value="de"><?php echo esc_html__("Germany", RKMW_PLUGIN_NAME); ?> (http://www.google.de/)</option>
                                                        <option value="com.gh"><?php echo esc_html__("Ghana ", RKMW_PLUGIN_NAME); ?> (http://www.google.com.gh/)</option>
                                                        <option value="com.gi"><?php echo esc_html__("Gibraltar", RKMW_PLUGIN_NAME); ?> (http://www.google.com.gi/)</option>
                                                        <option value="com.gr"><?php echo esc_html__("Greece", RKMW_PLUGIN_NAME); ?> (http://www.google.com.gr/)</option>
                                                        <option value="gl"><?php echo esc_html__("Greenland", RKMW_PLUGIN_NAME); ?> (http://www.google.gl/)</option>
                                                        <option value="gg"><?php echo esc_html__("Guernsey", RKMW_PLUGIN_NAME); ?> (http://www.google.gg/)</option>
                                                        <option value="hn"><?php echo esc_html__("Honduras", RKMW_PLUGIN_NAME); ?> (http://www.google.hn/)</option>
                                                        <option value="com.hk"><?php echo esc_html__("Hong Kong", RKMW_PLUGIN_NAME); ?> (http://www.google.com.hk/)</option>
                                                        <option value="co.hu"><?php echo esc_html__("Hungary", RKMW_PLUGIN_NAME); ?> (http://www.google.co.hu/)</option>
                                                        <option value="co.in"><?php echo esc_html__("India", RKMW_PLUGIN_NAME); ?> (http://www.google.co.in/)</option>
                                                        <option value="co.id"><?php echo esc_html__("Indonesia", RKMW_PLUGIN_NAME); ?> (http://www.google.co.id/)</option>
                                                        <option value="ie"><?php echo esc_html__("Ireland", RKMW_PLUGIN_NAME); ?> (http://www.google.ie/)</option>
                                                        <option value="co.im"><?php echo esc_html__("Isle of Man", RKMW_PLUGIN_NAME); ?> (http://www.google.co.im/)</option>
                                                        <option value="co.il"><?php echo esc_html__("Israel", RKMW_PLUGIN_NAME); ?> (http://www.google.co.il/)</option>
                                                        <option value="it"><?php echo esc_html__("Italy", RKMW_PLUGIN_NAME); ?> (http://www.google.it/)</option>
                                                        <option value="com.jm"><?php echo esc_html__("Jamaica", RKMW_PLUGIN_NAME); ?> (http://www.google.com.jm/)</option>
                                                        <option value="co.jp"><?php echo esc_html__("Japan", RKMW_PLUGIN_NAME); ?> (http://www.google.co.jp/)</option>
                                                        <option value="co.je"><?php echo esc_html__("Jersey", RKMW_PLUGIN_NAME); ?> (http://www.google.co.je/)</option>
                                                        <option value="kz"><?php echo esc_html__("Kazakhstan", RKMW_PLUGIN_NAME); ?> (http://www.google.kz/)</option>
                                                        <option value="co.kr"><?php echo esc_html__("Korea", RKMW_PLUGIN_NAME); ?> (http://www.google.co.kr/)</option>
                                                        <option value="lv"><?php echo esc_html__("Latvia", RKMW_PLUGIN_NAME); ?> (http://www.google.lv/)</option>
                                                        <option value="co.ls"><?php echo esc_html__("Lesotho", RKMW_PLUGIN_NAME); ?> (http://www.google.co.ls/)</option>
                                                        <option value="li"><?php echo esc_html__("Liechtenstein", RKMW_PLUGIN_NAME); ?> (http://www.google.li/)</option>
                                                        <option value="lt"><?php echo esc_html__("Lithuania", RKMW_PLUGIN_NAME); ?> (http://www.google.lt/)</option>
                                                        <option value="lu"><?php echo esc_html__("Luxembourg", RKMW_PLUGIN_NAME); ?> (http://www.google.lu/)</option>
                                                        <option value="mw"><?php echo esc_html__("Malawi", RKMW_PLUGIN_NAME); ?> (http://www.google.mw/)</option>
                                                        <option value="com.my"><?php echo esc_html__("Malaysia", RKMW_PLUGIN_NAME); ?> (http://www.google.com.my/)</option>
                                                        <option value="com.mt"><?php echo esc_html__("Malta", RKMW_PLUGIN_NAME); ?> (http://www.google.com.mt/)</option>
                                                        <option value="mu"><?php echo esc_html__("Mauritius", RKMW_PLUGIN_NAME); ?> (http://www.google.mu/)</option>
                                                        <option value="com.mx"><?php echo esc_html__("México", RKMW_PLUGIN_NAME); ?> (http://www.google.com.mx/)</option>
                                                        <option value="ms"><?php echo esc_html__("Montserrat", RKMW_PLUGIN_NAME); ?> (http://www.google.ms/)</option>
                                                        <option value="com.na"><?php echo esc_html__("Namibia", RKMW_PLUGIN_NAME); ?> (http://www.google.com.na/)</option>
                                                        <option value="com.np"><?php echo esc_html__("Nepal", RKMW_PLUGIN_NAME); ?> (http://www.google.com.np/)</option>
                                                        <option value="nl"><?php echo esc_html__("Netherlands", RKMW_PLUGIN_NAME); ?> (http://www.google.nl/)</option>
                                                        <option value="co.nz"><?php echo esc_html__("New Zealand", RKMW_PLUGIN_NAME); ?> (http://www.google.co.nz/)</option>
                                                        <option value="com.ni"><?php echo esc_html__("Nicaragua", RKMW_PLUGIN_NAME); ?> (http://www.google.com.ni/)</option>
                                                        <option value="com.ng"><?php echo esc_html__("Nigeria", RKMW_PLUGIN_NAME); ?> (http://www.google.com.ng/)</option>
                                                        <option value="com.nf"><?php echo esc_html__("Norfolk Island", RKMW_PLUGIN_NAME); ?> (http://www.google.com.nf/)</option>
                                                        <option value="no"><?php echo esc_html__("Norway", RKMW_PLUGIN_NAME); ?> (http://www.google.no/)</option>
                                                        <option value="com.pk"><?php echo esc_html__("Pakistan", RKMW_PLUGIN_NAME); ?> (http://www.google.com.pk/)</option>
                                                        <option value="com.pa"><?php echo esc_html__("Panamá", RKMW_PLUGIN_NAME); ?> (http://www.google.com.pa/)</option>
                                                        <option value="com.py"><?php echo esc_html__("Paraguay", RKMW_PLUGIN_NAME); ?> (http://www.google.com.py/)</option>
                                                        <option value="com.pe"><?php echo esc_html__("Perú", RKMW_PLUGIN_NAME); ?> (http://www.google.com.pe/)</option>
                                                        <option value="com.ph"><?php echo esc_html__("Philippines", RKMW_PLUGIN_NAME); ?> (http://www.google.com.ph/)</option>
                                                        <option value="pn"><?php echo esc_html__("Pitcairn Islands", RKMW_PLUGIN_NAME); ?> (http://www.google.pn/)</option>
                                                        <option value="pl"><?php echo esc_html__("Poland", RKMW_PLUGIN_NAME); ?> (http://www.google.pl/)</option>
                                                        <option value="pt"><?php echo esc_html__("Portugal", RKMW_PLUGIN_NAME); ?> (http://www.google.pt/)</option>
                                                        <option value="com.pr"><?php echo esc_html__("Puerto Rico", RKMW_PLUGIN_NAME); ?> (http://www.google.com.pr/)</option>
                                                        <option value="cg"><?php echo esc_html__("Rep. of the Congo", RKMW_PLUGIN_NAME); ?> (http://www.google.cg/)</option>
                                                        <option value="ro"><?php echo esc_html__("Romania", RKMW_PLUGIN_NAME); ?> (http://www.google.ro/)</option>
                                                        <option value="ru"><?php echo esc_html__("Russia", RKMW_PLUGIN_NAME); ?> (http://www.google.ru/)</option>
                                                        <option value="rw"><?php echo esc_html__("Rwanda", RKMW_PLUGIN_NAME); ?> (http://www.google.rw/)</option>
                                                        <option value="sh"><?php echo esc_html__("Saint Helena", RKMW_PLUGIN_NAME); ?> (http://www.google.sh/)</option>
                                                        <option value="sm"><?php echo esc_html__("San Marino", RKMW_PLUGIN_NAME); ?> (http://www.google.sm/)</option>
                                                        <option value="com.sa"><?php echo esc_html__("Saudi Arabia", RKMW_PLUGIN_NAME); ?> (http://www.google.com.sa/)</option>
                                                        <option value="com.sg"><?php echo esc_html__("Singapore", RKMW_PLUGIN_NAME); ?> (http://www.google.com.sg/)</option>
                                                        <option value="sk"><?php echo esc_html__("Slovakia", RKMW_PLUGIN_NAME); ?> (http://www.google.sk/)</option>
                                                        <option value="co.za"><?php echo esc_html__("South Africa", RKMW_PLUGIN_NAME); ?> (http://www.google.co.za/)</option>
                                                        <option value="es"><?php echo esc_html__("Spain", RKMW_PLUGIN_NAME); ?> (http://www.google.es/)</option>
                                                        <option value="lk"><?php echo esc_html__("Sri Lanka", RKMW_PLUGIN_NAME); ?> (http://www.google.lk/)</option>
                                                        <option value="se"><?php echo esc_html__("Sweden", RKMW_PLUGIN_NAME); ?> (http://www.google.se/)</option>
                                                        <option value="ch"><?php echo esc_html__("Switzerland", RKMW_PLUGIN_NAME); ?> (http://www.google.ch/)</option>
                                                        <option value="com.tw"><?php echo esc_html__("Taiwan", RKMW_PLUGIN_NAME); ?> (http://www.google.com.tw/)</option>
                                                        <option value="co.th"><?php echo esc_html__("Thailand", RKMW_PLUGIN_NAME); ?> (http://www.google.co.th/)</option>
                                                        <option value="tt"><?php echo esc_html__("Trinidad and Tobago", RKMW_PLUGIN_NAME); ?> (http://www.google.tt/)</option>
                                                        <option value="com.tr"><?php echo esc_html__("Turkey", RKMW_PLUGIN_NAME); ?> (http://www.google.com.tr/)</option>
                                                        <option value="com.ua"><?php echo esc_html__("Ukraine", RKMW_PLUGIN_NAME); ?> (http://www.google.com.ua/)</option>
                                                        <option value="ae"><?php echo esc_html__("United Arab Emirates", RKMW_PLUGIN_NAME); ?> (http://www.google.ae/)</option>
                                                        <option value="co.uk"><?php echo esc_html__("United Kingdom", RKMW_PLUGIN_NAME); ?> (http://www.google.co.uk/)</option>
                                                        <option value="us"><?php echo esc_html__("United States", RKMW_PLUGIN_NAME); ?> (http://www.google.us/)</option>
                                                        <option value="com.uy"><?php echo esc_html__("Uruguay", RKMW_PLUGIN_NAME); ?> (http://www.google.com.uy/)</option>
                                                        <option value="uz"><?php echo esc_html__("Uzbekistan", RKMW_PLUGIN_NAME); ?> (http://www.google.uz/)</option>
                                                        <option value="vu"><?php echo esc_html__("Vanuatu", RKMW_PLUGIN_NAME); ?> (http://www.google.vu/)</option>
                                                        <option value="co.ve"><?php echo esc_html__("Venezuela", RKMW_PLUGIN_NAME); ?> (http://www.google.co.ve/)</option>
                                                        <option value="com.vn"><?php echo esc_html__("Vietnam", RKMW_PLUGIN_NAME); ?> (http://www.google.com.vn/)</option>
                                                    </select>
                                                    <script>jQuery('select[name=google_country]').val('<?php echo RKMW_Classes_Helpers_Tools::getOption('google_country')?>').attr('selected', true);</script>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="col-sm-12 my-3 p-0">
                        <button type="submit" class="btn rounded-0 btn-success btn-lg px-5 mx-4"><?php echo esc_html__("Save Settings", RKMW_PLUGIN_NAME); ?></button>
                    </div>
                </form>
            </div>
            <div class="rkmw_col_side sticky">
                <div class="card col-sm-12 p-0">
                    <?php echo RKMW_Classes_ObjController::getClass('RKMW_Core_BlockKnowledgeBase')->init(); ?>
                </div>
            </div>
        </div>

    </div>
</div>
