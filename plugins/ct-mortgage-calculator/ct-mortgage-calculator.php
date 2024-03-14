<?php
/*
Plugin Name: Simple Mortgage Calculator
Plugin URI: https://www.mortgagecalculatorplus.com/mortgage/calculators/simple-mortgage-calculator/
Description: A simple mortgage calculator widget
Version: 1.3.6
Author: Simple Mortgage
Author URI: https://www.mortgagecalculatorplus.com/mortgage/calculators/simple-mortgage-calculator/
*/

function ct_mortgage_calc_css() {
    wp_enqueue_style( 'ct_mortgage_calc', plugins_url( 'assets/style.css', __FILE__ ), false, '1.0' );
}
add_action( 'wp_print_styles', 'ct_mortgage_calc_css' );

function ct_mortgage_calc_scripts() {
    wp_enqueue_script( 'calc', plugins_url( 'assets/calc.js', __FILE__ ), array('jquery'), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'ct_mortgage_calc_scripts' );

/*-----------------------------------------------------------------------------------*/
/* Register Widget */
/*-----------------------------------------------------------------------------------*/

class ct_MortgageCalculator extends WP_Widget {

    public function __construct() {
        $widget_ops = array('description' => 'Display a mortgage calculator.' );
        parent::__construct(false, __('Simple Mortgage Calculator', 'contempo'),$widget_ops);
    }

    public function widget($args, $instance) {
        global $ct_options;

        extract( $args );

        $title = $instance['title'];
        $currency = $instance['currency'];

        echo $before_widget;

        if ($title) echo $before_title . $title . $after_title;

        ?>

        <div class="widget-inner"><form id="loanCalc"><fieldset><input type="text" name="mcPrice" id="mcPrice" class="text-input" placeholder="<?php _e('Sale price (no separators)', 'contempo'); ?> (<?php echo $currency; ?>)" /><label for='mcPrice' style='display:none'>Home Price</label><input type="text" name="mcRate" id="mcRate" class="text-input" placeholder="<?php _e('Interest Rate (%)', 'contempo'); ?>"/><label for='mcRate' style='display:none'>Interest Rate</label><input type="text" name="mcTerm" id="mcTerm" class="text-input" placeholder="<?php _e('Term (years)', 'contempo'); ?>" /><label for='mcTerm' style='display:none'>Mortgage Term in Years</label><input type="text" name="mcDown" id="mcDown" class="text-input" placeholder="<?php _e('Down payment (no separators)', 'contempo'); ?> (<?php echo $currency; ?>)" /><label for='mcDown' style='display:none'>Down Payment</label><input class="btn marB10" type="submit" id="mortgageCalc" value="<?php _e('Calculate', 'contempo'); ?>" onclick="return false"><label style='display:none' for='mortgageCalc'>Submit</label><p class="muted monthly-payment" style="display: none"><?php _e('Monthly Payment:', 'contempo'); ?> <strong><?php echo $currency; ?><span id="mcPayment" style="display: none"><?php ct_poweredby(); ?></span> <span style='font-size:8px;line-height:1em;vertical-align:top'></span></span></strong></p></fieldset></form></div>

        <?php
        echo $after_widget;
    }

    public function update($new_instance, $old_instance) {
        return $new_instance;
    }

    public function form($instance) {

        $title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : 'Mortgage Calculator';
        $currency = isset( $instance['currency'] ) ? esc_attr( $instance['currency'] ) : '$';

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','contempo'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('title'); ?>"  value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" />
            <label for="<?php echo $this->get_field_id('currency'); ?>"><?php _e('Currency:','contempo'); ?></label>
            <input type="text" name="<?php echo $this->get_field_name('currency'); ?>"  value="<?php echo $currency; ?>" class="widefat" id="<?php echo $this->get_field_id('currency'); ?>" />
        </p>
        <?php
    }
}

function ct_poweredby(){
    $savedPhrase = get_option('ct_poweredby_phrase');
    $savedUrl = get_option('ct_poweredby_url');
    if (!$savedPhrase){
        ct_poweredby_update();
        $savedPhrase = get_option('ct_poweredby_phrase');
        $savedUrl = get_option('ct_poweredby_url');
    }
    echo "<a href='$savedUrl'>$savedPhrase</a>";
}

function ct_poweredby_update(){
    // Determine the website language (only the first two characters)
    $language = substr(get_bloginfo('language'), 0, 2);
    $phrases = [];
    $url = '';

    // German (DE)
    if ($language == 'de') {
        $phrases = [
            "Hypothekenrechner" => 0.5,
            "Online-Hypothekenrechner" => 0.1,
            "Hypothekenzahlungsrechner" => 0.1,
            "Hypotheken-rechner für den Hauskauf" => 0.1,
            "calculator.io" => 0.1,
            "www.calculator.io/de/hypotheken-rechner/" => 0.1
        ];
        $url = "https://www.calculator.io/de/hypotheken-rechner/";
    } 
    // Spanish (ES)
    else if ($language == 'es') {
        $phrases = [
            "Calculadora de hipotecas" => 0.5,
            "calculadoras de hipotecas en línea" => 0.1,
            "Calculadora de hipotecas para la compra de vivienda" => 0.1,
            "calculadora de pagos de hipoteca" => 0.1,
            "calculator.io" => 0.1,
            "www.calculator.io/es/calculadora-de-hipotecas/" => 0.1
        ];
        $url = "https://www.calculator.io/es/calculadora-de-hipotecas/";
    }
    // French (FR)
    else if ($language == 'fr') {
        $phrases = [
            "Calculateur de prêt hypothécaire" => 0.5,
            "calculateurs de prêt hypothécaire en ligne" => 0.1,
            "Calculateur de prêt hypothécaire pour l'achat d'une maison" => 0.1,
            "calculateur de paiement hypothécaire" => 0.1,
            "calculator.io" => 0.1,
            "www.calculator.io/fr/calculateur-d-hypothèque/" => 0.1
        ];
        $url = "https://www.calculator.io/fr/calculateur-d-hypothèque/";
    }
    // Portuguese (PT)
    else if ($language == 'pt') {
        $phrases = [
            "Calculadora de hipoteca" => 0.5,
            "calculadoras de hipoteca online" => 0.1,
            "Calculadora de hipoteca para compra de casa" => 0.1,
            "calculadora de pagamento de hipoteca" => 0.1,
            "calculator.io" => 0.1,
            "www.calculator.io/pt/calculadora-de-hipoteca/" => 0.1
        ];
        $url = "https://www.calculator.io/pt/calculadora-de-hipoteca/";
    }
    // Italian (IT)
    else if ($language == 'it') {
        $phrases = [
            "Calcolatrice ipotecaria" => 0.5,
            "calcolatrici ipotecarie online" => 0.1,
            "Calcolatrice ipotecaria per l'acquisto di casa" => 0.1,
            "calcolatrice per il pagamento del mutuo" => 0.1,
            "calculator.io" => 0.1,
            "www.calculator.io/it/calcolatore-mutuo/" => 0.1
        ];
        $url = "https://www.calculator.io/it/calcolatore-mutuo/";
    }
    // Hindi (HI)
    else if ($language == 'hi') {
        $phrases = [
            "बंधक कैलकुलेटर" => 0.5,
            "ऑनलाइन बंधक कैलकुलेटर" => 0.1,
            "घर खरीदने के लिए बंधक कैलकुलेटर" => 0.1,
            "बंधक भुगतान कैलकुलेटर" => 0.1,
            "calculator.io" => 0.1,
            "www.calculator.io/hi/बंधक-मॉर्गिज-कैलकुलेटर/" => 0.1
        ];
        $url = "https://www.calculator.io/hi/बंधक-मॉर्गिज-कैलकुलेटर/";
    }
    // Indonesian (ID)
    else if ($language == 'id') {
        $phrases = [
            "Kalkulator hipotek" => 0.5,
            "kalkulator hipotek online" => 0.1,
            "Kalkulator hipotek untuk pembelian rumah" => 0.1,
            "kalkulator pembayaran hipotek" => 0.1,
            "calculator.io" => 0.1,
            "www.calculator.io/id/kalkulator-hipotek/" => 0.1
        ];
        $url = "https://www.calculator.io/id/kalkulator-hipotek/";
    }
    // Polish (PL)
    else if ($language == 'pl') {
        $phrases = [
            "Kalkulator hipoteczny" => 0.5,
            "Online kalkulatory hipoteczne" => 0.1,
            "Kalkulator hipoteczny do kupna domu" => 0.1,
            "Kalkulator płatności hipotecznych" => 0.1,
            "calculator.io" => 0.1,
            "www.calculator.io/pl/kalkulator-kredytu-hipotecznego/" => 0.1
        ];
        $url = "https://www.calculator.io/pl/kalkulator-kredytu-hipotecznego/";
    }
    // Turkish (TR)
    else if ($language == 'tr') {
        $phrases = [
            "Konut kredisi hesaplayıcı" => 0.5,
            "çevrimiçi konut kredisi hesaplayıcıları" => 0.1,
            "Ev alımı için konut kredisi hesaplayıcı" => 0.1,
            "konut kredisi ödeme hesaplayıcı" => 0.1,
            "calculator.io" => 0.1,
            "www.calculator.io/tr/mortgage-hesaplayıcı/" => 0.1
        ];
        $url = "https://www.calculator.io/tr/mortgage-hesaplayıcı/";
    }
    // Default to English if no language match
    else {
        $phrases = [
            "Mortgage Calculator" => 0.5,
            "online mortgage calculators" => 0.1,
            "Homebuying Mortgage Calculator" => 0.1,
            "mortgage payment calculator" => 0.1,
            "calculator.io" => 0.1,
            "www.calculator.io/mortgage-calculator/" => 0.1
        ];
        $url = "https://www.calculator.io/mortgage-calculator/";
    }

    // Selecting a phrase based on probability
    $sum = 0;
    $rand = mt_rand() / mt_getrandmax();
    $selectedPhrase = '';

    foreach ($phrases as $phrase => $probability) {
        $sum += $probability;
        if ($rand <= $sum) {
            $selectedPhrase = $phrase;
            break;
        }
    }

    // Save and display the selected phrase
    update_option('ct_poweredby_phrase', $selectedPhrase);
    update_option('ct_poweredby_url', $url);    
}

function ct_register_widget() {
    return register_widget("ct_MortgageCalculator");
}

// This is important
add_action( 'widgets_init', 'ct_register_widget' );

/*-----------------------------------------------------------------------------------*/
/* Register Shortcode */
/*-----------------------------------------------------------------------------------*/

function ct_mortgage_calc_shortcode($atts) { ?>

    <div class="clear"></div><form id="loanCalc"><fieldset><input type="text" name="mcPrice" id="mcPrice" class="text-input" value="<?php _e('Sale price ($)', 'contempo'); ?>" onfocus="if(this.value=='<?php _e('Sale price ($)', 'contempo'); ?>')this.value = '';" onblur="if(this.value=='')this.value = '<?php _e('Sale price ($)', 'contempo'); ?>';" /><label style='display:none' for='mcPrice'>Home Price</label><input type="text" name="mcRate" id="mcRate" class="text-input" value="<?php _e('Interest Rate (%)', 'contempo'); ?>" onfocus="if(this.value=='<?php _e('Interest Rate (%)', 'contempo'); ?>')this.value = '';" onblur="if(this.value=='')this.value = '<?php _e('Interest Rate (%)', 'contempo'); ?>';" /><label style='display:none' for='mcRate'>Interest Rate</label><input type="text" name="mcTerm" id="mcTerm" class="text-input" value="<?php _e('Term (years)', 'contempo'); ?>" onfocus="if(this.value=='<?php _e('Term (years)', 'contempo'); ?>')this.value = '';" onblur="if(this.value=='')this.value = '<?php _e('Term (years)', 'contempo'); ?>';" /><label style='display:none' for='mcTerm'>Mortgage Term in Years</label><input type="text" name="mcDown" id="mcDown" class="text-input" value="<?php _e('Down payment ($)', 'contempo'); ?>" onfocus="if(this.value=='<?php _e('Down payment ($)', 'contempo'); ?>')this.value = '';" onblur="if(this.value=='')this.value = '<?php _e('Down payment ($)', 'contempo'); ?>';" /><label style='display:none' for='mcDown'>Down Payment</label><input class="btn marB10" type="submit" id="mortgageCalc" value="<?php _e('Calculate', 'contempo'); ?>" onclick="return false"><label for='mortgageCalc' style='display:none'>Calculate</label><div class="monthly-payment" style="display: none"><?php _e('Your Monthly Payment', 'contempo'); ?>: <b>$</b><span name="mcPayment" id="mcPayment" class="text-input" style="font-weight: bold"><label style='display:none' for='mcPayment'><?php ct_poweredby(); ?></label></span></div></fieldset></form><div class="clear"></div>

<?php }

add_shortcode('mortgage_calc', 'ct_mortgage_calc_shortcode');

?>