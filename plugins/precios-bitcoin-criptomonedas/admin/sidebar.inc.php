<!--Sidebar -->
<div class="coinmotion_content_cell_coin" id="coinmotion_sidebar-container">
    <div id="coinmotion_sidebar">
        <div class="coinmotion-sidebar__section_coin">
            <h2 style="color: #375f7a;"><?=  __( 'Join Coinmotion', 'coinmotion' ) ?></h2>
            <ul>
                <li><strong><?=  __( 'Each time a new user that has registered using your referral link in the plugin buys or sells cryptocurrencies in Coinmotion, you will receive 50% of all trade commissions charged on that user during the first 12 months.', 'coinmotion' ) ?></strong></li>
                <li><strong><?=  __( 'In addition, your users will enjoy a trade commission reduced by 50% during the first 30 days by creating an account in Coinmotion using your link.', 'coinmotion' ) ?></strong></li>
                <li><strong><?=  __( 'Your rewards will be credited to your Coinmotion account. For that you must have or create a free account in Coinmotion.', 'coinmotion' ) ?></strong></li>
            </ul>
            <?php
            $data_lang = explode("_", get_locale());
            $lang = $data_lang[0];
            if (!in_array($lang, ['es', 'en', 'fi'])){
                $lang = 'en';
            }
            $url = "https://app.coinmotion.com/".$lang."/";
            ?>
            <a rel="nofollow" id="coinmotion-premium-button" class="coinmotion-button-upsell_coin" href="<?= $url ?>/register/signup?referral_code=<?= $params_0['refcode'] ?>&utm_campaign=price_widget_<?= $lang ?>&utm_source=<?= $params_0['refcode'] ?>&utm_medium=plugin_setup_button" target="_blank">
            <?=  __( 'Create a free account', 'coinmotion' ) ?></a><br>
        </div>
    </div>
</div>