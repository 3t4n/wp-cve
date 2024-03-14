<?php

include_once( COINMOTION__PLUGIN_DIR . '/includes/class.coinmotion_values.php' );

global $params_0;

$params_0 = coinmotion_get_widget_data();

$updated = "";

if ( !empty( $_POST ) ) { 
    
	if (isset($_POST[ 'refcode' ])){
    	$params_0['refcode'] = sanitize_text_field((string)$_POST[ 'refcode' ]);
    }

    if (isset($_POST[ 'register_text' ])){
    	$params_0['register_text'] = sanitize_text_field((string)$_POST[ 'register_text' ]);
    }
    if (isset($_POST[ 'lang' ])){
        $params_0['lang'] = sanitize_text_field((string)$_POST[ 'lang' ]);
    }
    if (isset($_POST[ 'register_button_color' ])){
        $params_0['register_button_color'] = sanitize_hex_color((string)$_POST[ 'register_button_color' ]);
    }
    if (isset($_POST[ 'register_text_color' ])){
        $params_0['register_text_color'] = sanitize_hex_color((string)$_POST[ 'register_text_color' ]);
    }
    if (isset($_POST[ 'register_button_hover_color' ])){
        $params_0['register_button_hover_color'] = sanitize_hex_color((string)$_POST[ 'register_button_hover_color' ]);
    }
    if (isset($_POST[ 'default_currency' ])){
        $params_0['default_currency'] = sanitize_text_field((string)$_POST[ 'default_currency' ]);
    }
        
    $bold_text = $detail_text = "";   
    $actual = get_option(COINMOTION_OPTION_NAME_WIDGET_0);
    $updated = update_option( COINMOTION_OPTION_NAME_WIDGET_0, $params_0);
    $new = get_option(COINMOTION_OPTION_NAME_WIDGET_0);
    if ($new == $actual)
        $updated = "";
}

function coinmotion_admin_settings_page(){
    global $coinmotion_active_tab, $updated;
    $coinmotion_active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'home'; 

    if ($updated !== ""){
        if (($updated == true) || ($updated == "-")){
            echo '<div class="updated notice" style="padding: 20px;"><strong>' . __( 'Great!', 'coinmotion' ) . '</strong> ' . __( 'Data updated successfully.', 'coinmotion' ) . '</div>' ;
        }    
        else if ($updated == false){
            echo '<div class="error notice" style="padding: 20px;"><strong>' .  __( 'Error', 'coinmotion' ) . '</strong> ' . __( 'There is a problem updating data.', 'coinmotion' ) . '</div>' ;
        }
    }
    
    ?>
    <h2 class="nav-tab-wrapper">
    <?php
        do_action( 'coinmotion_settings_tabs' );
    ?>
    </h2>
    <?php
        do_action( 'coinmotion_settings_content' );
}

// HOME TAB
add_action( 'coinmotion_settings_tabs', 'coinmotion_home_tab', 1 );
function coinmotion_home_tab(){
    global $coinmotion_active_tab; ?>
    <a rel="nofollow" class="nav-tab <?php echo $coinmotion_active_tab == 'home' || '' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=coinmotion_plugin_config_page&tab=home' ); ?>"><?= __( 'Home', 'coinmotion' ) ?> </a>
    <?php
}

// BUTTON TAB
add_action( 'coinmotion_settings_tabs', 'coinmotion_button_tab', 2 );
function coinmotion_button_tab(){
    global $coinmotion_active_tab;
    ?>
    <a rel="nofollow" class="nav-tab <?php echo $coinmotion_active_tab == 'button-tab' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=coinmotion_plugin_config_page&tab=button-tab' ); ?>"><?= __( 'Referral button', 'coinmotion' ); ?> </a>
    <?php
}

// WIDGET PRICE EVOLUTION TAB
add_action( 'coinmotion_settings_tabs', 'coinmotion_widget_rate_period_tab', 2 );
function coinmotion_widget_rate_period_tab(){
    global $coinmotion_active_tab;
    ?>
    <a rel="nofollow" class="nav-tab <?php echo $coinmotion_active_tab == 'widget-rate-period-tab' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=coinmotion_plugin_config_page&tab=widget-rate-period-tab' ); ?>"><?= __( 'Price evolution', 'coinmotion' ); ?> </a>
    <?php
}

// WIDGET HISTORICAL DATA TAB
add_action( 'coinmotion_settings_tabs', 'coinmotion_widget_currency_details_tab', 2 );
function coinmotion_widget_currency_details_tab(){
    global $coinmotion_active_tab; 
    ?>
    <a rel="nofollow" class="nav-tab <?php echo $coinmotion_active_tab == 'widget-currency-details-tab' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=coinmotion_plugin_config_page&tab=widget-currency-details-tab' ); ?>"><?= __( 'Historical data', 'coinmotion' ); ?> </a>
    <?php
}

// WIDGET CURRENCY/CRYPTO CONVERSOR TAB
add_action( 'coinmotion_settings_tabs', 'coinmotion_widget_currency_conversor_tab', 2 );
function coinmotion_widget_currency_conversor_tab(){
    global $coinmotion_active_tab; 
    ?>
    <a rel="nofollow" class="nav-tab <?php echo $coinmotion_active_tab == 'widget-currency-conversor-tab' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=coinmotion_plugin_config_page&tab=widget-currency-conversor-tab' ); ?>"><?= __( 'Currency/Crypto conversor', 'coinmotion' ); ?> </a>
    <?php
}

// SHORTCODE CURRENCY/CRYPTO CAROUSEL
add_action( 'coinmotion_settings_tabs', 'coinmotion_carousel_currenct_tab', 2 );
function coinmotion_carousel_currenct_tab(){
    global $coinmotion_active_tab; 
    ?>
    <a rel="nofollow" class="nav-tab <?php echo $coinmotion_active_tab == 'carousel-currency-tab' ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'admin.php?page=coinmotion_plugin_config_page&tab=carousel-currency-tab' ); ?>"><?= __( 'Shortcode Currency/Crypto carousel', 'coinmotion' ); ?> </a>
    <?php
}

// BUTTON TAB
add_action( 'coinmotion_settings_content', 'coinmotion_button_render_options_page' ); 
function coinmotion_button_render_options_page() {
    global $coinmotion_active_tab;
    
    global $params_0;
    if ( 'button-tab' != $coinmotion_active_tab )
        return;
    ?>
    <div class="coinmotion_wpseo_content_wrapper_coin">
         <!-- Bloque central -->
         <div class="coinmotion_center_plugin">
             <div style="text-align: center; padding:40px;">
                 <img src="<?= plugin_dir_url( __FILE__ ).'img/coinmotion-logo-small.png' ?>" >
             </div>
             <p style="text-align: center;"><?=  __( 'From here you can change some characteristics globally.', 'coinmotion' )  ?></p>
             <div class="coinmotion_formu_short">
                 <form action="<?= $_SERVER[ 'PHP_SELF' ] ?>?page=coinmotion_plugin_config_page&tab=button-tab" method="post" class="coinmotion_form_short2">
                     <div class="coinmotion_column1_short">                         
                        <p>
                             <label for="refcode">
                                 <?= __( 'Referral code', 'coinmotion' )  ?>
                             </label>
                           
                            <input
                            class="coinmotion_widefat_coin2"
                            id="refcode"
                            name="refcode"
                            type="text"
                            maxlength="50"
                             value="<?= $params_0['refcode'] ?>" >
                         </p>
                         <p>
                             <label for="register_text">
                                 <?=  __( 'Button text', 'coinmotion' ) ?>
                             </label>
                           
                            <input
                            class="coinmotion_widefat_coin2"
                            id="register_text"
                            name="register_text"
                            type="text"
                             maxlength="25"
                             value="<?= __($params_0['register_text'],'coinmotion') ?>" >
                         </p>
                         
                     </div>
                     <div class="coinmotion_column1_short">
                         <p>
                             <input
                            class="coinmotion_widefat"
                            id="register_button_color"
                            name="register_button_color"
                            type="color"
                            style="width: 40px; height: 40px;"
                             maxlength="7"
                             value="<?= $params_0['register_button_color'] ?>" >
                             <label for="register_button_color" style="top: -11px; left: 11px; position: relative;">
                                 <?=  __( 'Button background color', 'coinmotion' ) ?>
                             </label>
                        </p>
                         <p>
                             <input
                            class="coinmotion_widefat"
                            id="register_text_color"
                            name="register_text_color"
                            type="color"
                            style="width: 40px; height: 40px;"
                             maxlength="7"
                             value="<?= $params_0['register_text_color'] ?>" >
                             <label for="register_text_color" style="top: -11px; left: 11px; position: relative;">
                                 <?=  __( 'Button text color', 'coinmotion' ) ?>
                             </label>
                        </p>
                         <p>
                            <input
                            class="coinmotion_widefat"
                            id="register_button_hover_color"
                            name="register_button_hover_color"
                            type="color"
                            style="width: 40px; height: 40px;"
                             maxlength="7"
                             value="<?= $params_0['register_button_hover_color'] ?>" >
                             <label for="register_button_hover_color" style="top: -11px; left: 11px; position: relative;">
                             <?=  __( 'Button hover background color', 'coinmotion' ) ?>
                             </label>
                        </p>
                         <input style ="float: right; margin-top: 20px;" class='button-primary' type='submit' name='Save' value='<?= __( 'Save changes', 'coinmotion' ) ?>' id='submitbutton' />
                     </div>
                 </div>
             </form>
         </div>
    <?php
    require_once( COINMOTION__PLUGIN_DIR . '/admin/sidebar.inc.php');
    ?>
    </div>
    <?php
}

// HOME TAB
add_action( 'coinmotion_settings_content', 'coinmotion_home_render_options_page' ); 
function coinmotion_home_render_options_page() {
    global $coinmotion_active_tab;
    global $params_0;
    
    if ( 'home' != $coinmotion_active_tab )
        return;
    
    ?>
    
    <div class="coinmotion_wpseo_content_wrapper_coin">
        <div class="coinmotion_center_plugin">
            <div style="text-align: center; padding:40px;">
                <img src="<?= plugin_dir_url( __FILE__ ).'img/coinmotion-logo-small.png' ?>" >
            </div>
            <p><strong><?= __('Plugin of the regulated cryptocurrency provider Coinmotion.', 'coinmotion'); ?></strong></p>
            <p><?= __('Here you can choose the currency that will be used globally to convert the value of the cryptocurrencies to fiat.', 'coinmotion'); ?></p>
            <p><?= __('Convert the value of cryptocurrencies to the following currency:', 'coinmotion'); ?></p>
            <form style="margin: 0px; display: block;" action="<?= $_SERVER[ 'PHP_SELF' ] ?>?page=coinmotion_plugin_config_page&tab=home" method="post" class="coinmotion_form_short2">
                <p>              
                            <select class="coinmotion_widefat_coin2"
                                id="default_currency"
                                name="default_currency" style="width: 80px;">
                                <?php
                                //$curr = new CoinmotionGetCurrencies();
                                $currencies = get_option("coinmotion_currencies");
                                if ($params_0['default_currency'] == 'EUR'){
                                    ?>
                                    <option value="EUR" selected>EUR</option>
                                    <?php
                                }
                                else{
                                    ?>
                                    <option value="EUR">EUR</option>
                                    <?php
                                }

                                if ($params_0['default_currency'] == 'USD'){
                                    ?>
                                    <option value="USD" selected>USD</option>
                                    <?php
                                }
                                else{
                                    ?>
                                    <option value="USD">USD</option>
                                    <?php
                                }


                                foreach ($currencies['conversion_rates'] as $key => $value){
                                    if (($key === "EUR") || ($key === "USD"))
                                        continue; 
                                    if ($key === $params_0['default_currency']){
                                        ?>
                                        <option value="<?= $key ?>" selected><?= $key ?></option>
                                        <?php
                                    }
                                    else{
                                        ?>
                                        <option value="<?= $key ?>"><?= $key ?></option>
                                        <?php
                                    }
                                }
                                ?>                                
                             </select>
                         </p>
                         <p><input style ="float: right; margin-top: 20px;" class='button-primary' type='submit' name='Save' value='<?= __( 'Save changes', 'coinmotion' ) ?>' id='submitbutton' /></p>
            </form>
        </div>
    <?php
    require_once( COINMOTION__PLUGIN_DIR . '/admin/sidebar.inc.php');
    ?>
    </div>
    <?php
}

// WIDGET PRICE EVOLUTION CONTENT
add_action( 'coinmotion_settings_content', 'coinmotion_widget_rate_period_render_options_page' ); 
function coinmotion_widget_rate_period_render_options_page() {
    global $coinmotion_active_tab;
    global $params_0;
    
    if ( 'widget-rate-period-tab' != $coinmotion_active_tab )
        return;
    ?>
    
    <div class="coinmotion_wpseo_content_wrapper_coin">
        <div class="coinmotion_center_plugin">
            <div style="text-align: center; padding:40px;">
                <img src="<?= plugin_dir_url( __FILE__ ).'img/coinmotion-logo-small.png' ?>" >
            </div>
            <p><?= __('This widget allows you to visualize in a graph the evolution of the price of the selected cryptocurrency. In the widget you can mainly set the time period to display and the currency that will be used to display the equivalent value of the cryptocurrency.', 'coinmotion') ?></p>
            <p><?= __('To add and configure this widget you have to go to:', 'coinmotion') ?></p>
            <p><?= __('Appearance > Widgets', 'coinmotion') ?></p>
            <p><br/></p>
            <p><?= __('<strong>Shortcode Configuration</strong>', 'coinmotion') ?></p>
            <p><?= __('Next, you have an explanation of each of the features that you can configure only for shortcode.', 'coinmotion') ?>
            <p><?= __('<strong>text_color</strong>: Color of the text', 'coinmotion') ?>.</p>
            <p><?= __('<strong>line_color</strong>: Color of the graph line', 'coinmotion') ?>.</p>
            <p><?= __('<strong>background_color</strong>: Background color of the graph line', 'coinmotion') ?>.</p>
            <p><?= __('<strong>width</strong>: Desired width of the widget. You can set with % or px', 'coinmotion') ?>.</p>
            <p><?= __('<strong>height</strong>: Desired height of the widget. You can set with % or px', 'coinmotion') ?>.</p>
            <p><?= __('<strong>points</strong>: Points to show on the graph', 'coinmotion') ?>.</p>
            <p><?= __('<strong>period</strong>: Time period to be displayed on the graph', 'coinmotion') ?>.</p>
            <p><?= __('<strong>currency</strong>: Crypto in which the data will be displayed', 'coinmotion') ?>.</p>
            <p><?= __('<strong>type</strong>: Type of the data to show, must be Price or Interest', 'coinmotion') ?>.</p>
            <p><?= __('<strong>show_button</strong>: (true / false). Show the Affiliate button. Default false', 'coinmotion') ?>.</p>
            <p><br/></p>
            <p><?= __('<strong>Shortcode Example</strong>', 'coinmotion') ?></p>
            <p><i>[coinmotion_rate_period currency='btc' background_color='black' period='month' type='price' points='10' show_button='true']</i></p>
        </div>
    <?php
    require_once( COINMOTION__PLUGIN_DIR . '/admin/sidebar.inc.php');
    ?>
    </div>
    <?php
}

// WIDGET HISTORICAL DATA CONTENT
add_action( 'coinmotion_settings_content', 'coinmotion_widget_currency_details_render_options_page' ); 
function coinmotion_widget_currency_details_render_options_page() {
    global $coinmotion_active_tab;
    global $params_0;
    
    if ( 'widget-currency-details-tab' != $coinmotion_active_tab )
        return;
    ?>
    
    <div class="coinmotion_wpseo_content_wrapper_coin">
        <div class="coinmotion_center_plugin">
            <div style="text-align: center; padding:40px;">
                <img src="<?= plugin_dir_url( __FILE__ ).'img/coinmotion-logo-small.png' ?>" >
            </div>
            <p><?= __('This widget displays historical data of the cryptocurrency you choose. From the current price to its maximum and minimum of 1 week, 1 month, 3 months and 1 year ago.', 'coinmotion'); ?></p>
            <p><?= __('To add and configure this widget you have to go to:', 'coinmotion') ?></p>
            <p><?= __('Appearance > Widgets', 'coinmotion') ?></p>
            <p><br/></p>
            <p><?= __('<strong>Shortcode Configuration</strong>', 'coinmotion') ?></p>
            <p><?= __('Next, you have an explanation of each of the features that you can configure only for shortcode.', 'coinmotion') ?>
            <p><?= __('<strong>currency</strong>: Crypto in which the data will be displayed', 'coinmotion') ?>.</p>
            <p><?= __('<strong>text_color</strong>: Color of the text', 'coinmotion') ?>.</p>
            <p><?= __('<strong>background_color</strong>: Background color of the conversor', 'coinmotion') ?>.</p>
            <p><?= __('<strong>type</strong>: Type of the data to show, must be Price or Interest', 'coinmotion') ?>.</p>
            <p><?= __('<strong>show_button</strong>: (true / false). Show the Affiliate button. Default false', 'coinmotion') ?>.</p>
            <p><br/></p>
            <p><?= __('<strong>Shortcode Example</strong>', 'coinmotion') ?></p>
            <p><i>[coinmotion_details background_color='black' text_color='blue' currency='btc' type='price' show_button='true']</i></p>
        </div>
    <?php
    require_once( COINMOTION__PLUGIN_DIR . '/admin/sidebar.inc.php');
    ?>
    </div>
    <?php
}

// WIDGET CURRENCY/CRYPTO CONVERSOR CONTENT
add_action( 'coinmotion_settings_content', 'coinmotion_widget_currency_conversor_render_options_page' ); 
function coinmotion_widget_currency_conversor_render_options_page() {
    global $coinmotion_active_tab;
    global $params_0;
    
    if ( 'widget-currency-conversor-tab' != $coinmotion_active_tab )
        return;
    ?>
    
    <div class="coinmotion_wpseo_content_wrapper_coin">
        <div class="coinmotion_center_plugin">
            <div style="text-align: center; padding:40px;">
                <img src="<?= plugin_dir_url( __FILE__ ).'img/coinmotion-logo-small.png' ?>" >            
            </div>
            <p><?= __('The widget is a two-way converter of the amount you set of a cryptocurrency, calculating its equivalent value in the chosen currency.', 'coinmotion') ?></p>
            <p><?= __('To add and configure this widget you have to go to:', 'coinmotion') ?></p>
            <p><?= __('Appearance > Widgets', 'coinmotion') ?></p>
            <p><br/></p>
            <p><?= __('<strong>Shortcode Configuration</strong>', 'coinmotion') ?></p>
            <p><?= __('Next, you have an explanation of each of the features that you can configure only for shortcode.', 'coinmotion') ?>
            <p><?= __('<strong>text_color</strong>: Color of the text', 'coinmotion') ?>.</p>
            <p><?= __('<strong>background_color</strong>: Background color of the conversor', 'coinmotion') ?>.</p>
            <p><?= __('<strong>show_button</strong>: (true / false). Show the Affiliate button. Default false', 'coinmotion') ?>.</p>
            <p><br/></p>
            <p><?= __('<strong>Shortcode Example</strong>', 'coinmotion') ?></p>
            <p><i>[coinmotion_conversor background_color='black' text_color='#fafafa' show_button='true']</i></p>
        </div>
    <?php
    require_once( COINMOTION__PLUGIN_DIR . '/admin/sidebar.inc.php');
    ?>
    </div>
    <?php
}

// SHORTCODE CAROUSEL CURRENCY
add_action( 'coinmotion_settings_content', 'coinmotion_carousel_currency_render_options_page' ); 
function coinmotion_carousel_currency_render_options_page() {
    global $coinmotion_active_tab;
    global $params_0;
    
    if ( 'carousel-currency-tab' != $coinmotion_active_tab )
        return;
    ?>
    
    <div class="coinmotion_wpseo_content_wrapper_coin">
        <div class="coinmotion_center_plugin">
            <div style="text-align: center; padding:40px;">
                <img src="<?= plugin_dir_url( __FILE__ ).'img/coinmotion-logo-small.png' ?>" >
            </div>
            <p><?= __('Shortcode that will allow you to show where you want a carousel with the main cryptocurrencies and their price in the currency you have selected.', 'coinmotion') ?></p>
            <p><br/></p>
            <p><?= __('<strong>Shortcode Configuration</strong>', 'coinmotion') ?></p>
            <p><?= __('Next, you have an explanation of each of the features that you can configure only for shortcode.', 'coinmotion') ?>
            <p><?= __('<strong>title</strong>: The header title of the widget', 'coinmotion') ?>.</p>
            <p><?= __('<strong>text_color</strong>: (RGB or CSS color). Color of the text for the Currency Name. Default black', 'coinmotion') ?>.</p>
            <p><?= __('<strong>background_color</strong>: Background color of the conversor', 'coinmotion') ?>.</p>
            <p><?= __('<strong>orientation</strong>: (vertical / horizontal). Show in vertical or horizontal distribution. Default horizontal.', 'coinmotion') ?></p>
            <p><?= __('<strong>show_button</strong>: (true / false). Show the Affiliate button. Default false', 'coinmotion') ?>.</p>
            <p><?= __('<strong>currency</strong>: (btc,ltc,eth,xrp,xlm,aave,link,uni,usdc,usdt,dot,sol,matic,sand,mana). Set the currencies to show. Default btc,ltc,eth,xrp,xlm,aave,link,uni,usdc.', 'coinmotion') ?></p>
            <p><br/></p>
            <p><?= __('<strong>Shortcode Example</strong>', 'coinmotion'); ?></p>
            <p><i>[coinmotion title='Cotización criptomonedas' currency='btc,ltc,eth,xrp,xlm,aave,link,uni,usdc,,usdt,dot,sol,matic,sand,mana' text_color='black' background_color='#acacac' orientation='horizontal' show_button='true']
</i></p>
        </div>
    <?php
    require_once( COINMOTION__PLUGIN_DIR . '/admin/sidebar.inc.php');
    ?>
    </div>
    <?php
}
?>
