<?php
/*
Plugin Name: Leartes TRY Exchange Rates
Plugin URI: http://www.leartes.net/wp-plugins
Description: Gets TRY Exchange Rates from TCMB (Turkish Central Bank). Use as widget or Shortcode
Version: 2.1
Author: Leartes.NET
Author URI: http://www.leartes.net
Text Domain:  lbi-exchrates
Domain Path:  /languages
*/

$plugin		= plugin_basename(__FILE__);
$plugindir	= dirname(__FILE__) . DIRECTORY_SEPARATOR;
$pluginurl  = plugin_dir_url( __FILE__ );


define( 'LBI_PLUGIN_NAME', $plugin );
define( 'LBI_PLUGIN_VERSION', '2.1' );
define( 'LBI_PLUGIN_DIR', $plugindir );
define( 'LBI_PLUGIN_URL', $pluginurl );
define( 'LBI_PLUGIN_CACHE_DIR', 'cache' );
define( 'LBI_PLUGIN_CACHE_FILE', 'exchange_rates.xml');
define( 'LBI_PLUGIN_CACHE_TIME', 30 * MINUTE_IN_SECONDS   );

require_once 'lbi-class-exchrates.php';
require_once 'lbi-exchrates-widget.php';
require_once 'lbi-vc_map.php';
require_once 'lbi-elementor.php';

class LBI_Exchange_Rates {
	private $rates;
	public function __construct() {
		$this->rates = new LBI_Exchange_Rates_Data;
		add_action( 'init', array( $this, 'exchange_rates_plugin_init' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'exchange_rates_enqueue_scripts' ) );
        //add_action( 'admin_enqueue_scripts', array( $this, 'exchange_rates_enqueue_admin_scripts' ), 10, 1);
		add_action( 'widgets_init', array( $this, 'exchange_rates_widget_init' ) );
        add_action( 'plugins_loaded', array( $this, 'textdomain_init' ), 10 );
		add_shortcode( 'lbi_exchange_rates', array( $this, 'shortcode_exchange_rates' ) );
	}

	public function shortcode_exchange_rates($atts, $content = null) {
	    global $exch_currencies;
        extract( shortcode_atts( array (
            'title'          => '',
            'currencies_all' => 'true',
            'currencies'     => '',
            'caption'        => 'name',
            'captions'       => '',
            'unit'           => '',
            'flag'           => 'true',
            'flag_path'      => '',
            'fb'             => '',
            'fs'             => 'true',
            'bb'             => '',
            'bs'             => '',
            'cr'             => '',
            'showdate'       => '',
            'showsource'     => '',
            'class'          => '',
            'widget'         => 'false'
        ), $atts ) );

		$xml_data = $this->rates->xml_data();
        $currencies  = explode(',',$currencies);
        $currencies  = array_map('trim', $currencies);
        $title       = apply_filters('widget_title', $title);
        $r = 0;
        ob_start(); ?>
        <div class="currency-wraps-<?php echo ($widget == 'true' ? 'widget':'shortcode'); echo ' '.$class; ?>">
            <?php if($widget == 'false' && $title) { ?>
                <h3><span><?php echo $title; ?></span></h3>
            <?php } ?>
            <div class="lbi-currencies">
                <?php if($captions){ ?>
                    <?php if($cr || ($bs && $fs) || ($bs && $fb) || ($bb && $fs) || ($bb && $fb)){ ?>
                    <div class="c-header-top">
                        <?php if($cr){ ?><div class="c-rate cr"><?php _e('Cross', 'lbi-exchrates'); ?></div><?php } ?>
                        <?php if($bs || $bb){ ?><div class="c-rate banknote"><?php _e('Banknote', 'lbi-exchrates'); ?></div><?php } ?>
                        <?php if($fs || $fb){ ?><div class="c-rate forex"><?php _e('Forex', 'lbi-exchrates'); ?></div><?php } ?>
                    </div>
                    <div class="clearfix"></div>
                    <?php } ?>
                    <div class="c-header">
                        <?php if($cr){ ?><div class="c-rate cr"><?php _e('Rate', 'lbi-exchrates'); ?></div><?php } ?>
                        <?php if($bs){ ?><div class="c-rate bs"><?php _e('Sell', 'lbi-exchrates'); ?></div><?php } ?>
                        <?php if($bb){ ?><div class="c-rate bb"><?php _e('Buy', 'lbi-exchrates'); ?></div><?php } ?>
                        <?php if($fs){ ?><div class="c-rate fs"><?php _e('Sell', 'lbi-exchrates'); ?></div><?php } ?>
                        <?php if($fb){ ?><div class="c-rate fb"><?php _e('Buy', 'lbi-exchrates'); ?></div><?php } ?>
                    </div>
                    <div class="clearfix"></div>
                <?php } ?>
                <?php foreach($xml_data as $currency){
                    $currency_code = $currency->attributes()->{'Kod'}; // $currency['Kod']
                    $show_me = 'XDR' != $currency_code ? ($currencies_all == 'true' ? true : in_array($currency_code,$currencies)) : false;
                    if($show_me) { $r++ ?>
                    <div class="c-row<?php echo $r % 2 == 1 ? ' even':''; ?>">
                        <?php if($flag == 'true') { $flag_path = $flag_path == '' ? LBI_PLUGIN_URL . '/assets/flags' : $flag_path ;?>
                        <div class="c-symbol"><img src="<?php echo $flag_path . '/' . substr(strtolower($currency_code),0,2) . '.png'; ?>" alt="<?php echo $currency_code; ?>" title="<?php echo $currency_code; ?>"></div>
                        <?php }
                        if($cr) {
                            $cr_class = ($currency->CrossRateUSD == '' && $currency->CrossRateOther == '') ? ' empty': ($currency->CrossRateUSD == '' ? ' other':'');
                            $cr_value = ($currency->CrossRateUSD == '' && $currency->CrossRateOther == '') ? '-' : number_format(floatval($currency->CrossRateUSD != '' ? $currency->CrossRateUSD : $currency->CrossRateOther),4);
                        }
                        if($unit) { ?><div class="c-unit"><?php echo $currency->Unit; ?></div><?php }
                        if($caption == 'code' || $caption == 'both') { ?><div class="c-code"><?php echo $currency_code; ?></div><?php }
                        if($caption == 'name' || $caption == 'both') { ?><div class="c-name"><?php _e($exch_currencies["$currency_code"], 'lbi-exchrates'); ?></div><?php }
                        if($cr){ ?><div class="c-rate cr<?php echo $cr_class; ?>"><?php echo $cr_value; ?></div><?php }
                        if($bs){ ?><div class="c-rate bs<?php echo($currency->BanknoteSelling == '' ? ' empty':''); ?>"><?php echo($currency->BanknoteSelling == '' ? '-' : number_format(floatval($currency->BanknoteSelling),4)); ?></div><?php }
                        if($bb){ ?><div class="c-rate bb<?php echo($currency->BanknoteBuying == '' ? ' empty':''); ?>"><?php echo($currency->BanknoteBuying == '' ? '-' : number_format(floatval($currency->BanknoteBuying),4)); ?></div><?php }
                        if($fs){ ?><div class="c-rate fs<?php echo($currency->ForexSelling == '' ? ' empty':''); ?>"><?php echo($currency->ForexSelling == '' ? '-' :  number_format(floatval($currency->ForexSelling),4)); ?></div><?php }
                        if($fb){ ?><div class="c-rate fb<?php echo($currency->ForexBuying == '' ? ' empty':''); ?>"><?php echo($currency->ForexBuying == '' ? '-' :  number_format(floatval($currency->ForexBuying),4)); ?></div><?php } ?>
                    </div>
                    <div class="clearfix"></div>
                <?php }
                }
                if($showsource || $showdate) {
                    $lbi_date_format = get_option( 'date_format' );
                	if(has_filter('lbi_date_format')) { $lbi_date_format = apply_filters('lbi_date_format', $lbi_date_format); }

                    $tarih_date  = $xml_data->attributes()->{'Tarih'};
                    $tarih_date  = explode('.',$tarih_date);
                    //$tarih_date  = date_create($tarih_date[2].'-'.$tarih_date[1].'-'.$tarih_date[0]);
                    //$tarih_date  = date_format($tarih_date, get_option( 'date_format' ));
                    $tarih_date  = strtotime($tarih_date[2].'-'.$tarih_date[1].'-'.$tarih_date[0]);
                    $tarih_date  = date_i18n($lbi_date_format, $tarih_date);
                    ?>
                    <div class="c-footer">
                        <?php if( $showsource ){ ?>
                        <div class="c-source"><?php _e('Source', 'lbi-exchrates'); ?>: <a href="http://www.tcmb.gov.tr/wps/wcm/connect/tcmb+tr/tcmb+tr/main+page+site+area/bugun" target="_blank">TCMB</a></div>
                        <?php }

                        if( $showdate ){ ?>
                        <div class="c-date"><?php echo $tarih_date; ?></div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php $_return = ob_get_clean();
        return $_return;
	}

    public function textdomain_init(){
        load_plugin_textdomain( 'lbi-exchrates', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
    }



	public function exchange_rates_plugin_init() {
        //
	}

    public function exchange_rates_enqueue_scripts() {
        wp_enqueue_style( 'exchrates_style', plugins_url( "/assets/lbi-exchrates-style.css", __FILE__ ), array(), LBI_PLUGIN_VERSION );
    }

    public function exchange_rates_enqueue_admin_scripts($hook) {
        //
    }

	public function exchange_rates_widget_init() {
		register_widget('LBI_Exchange_Rates_Widget');
	}
}

if( ! function_exists('get_rates_data') ) {
	function get_rates_data() {
		$rates_data = new LBI_Exchange_Rates_Data;
        $rates = $rates_data->xml_data();
 		return $rates;
	}
}

if( ! function_exists('get_rate') ) {
	function get_rate($currency='USD',$the_rate = 'FS') {
        $rates = get_rates_data();
        foreach($rates->getDocNamespaces() as $strPrefix => $strNamespace) {
            if(strlen($strPrefix)==0) { $strPrefix = "lbi"; }
            $rates->registerXPathNamespace($strPrefix,$strNamespace);
        }
        //$tarih = $rates->attributes()->{'Tarih'};
        $feed = $rates->xpath('/Tarih_Date/Currency[@Kod="'. $currency .'"]');
        $feed = $feed[0];   // php < 5.4
        switch($the_rate){
            case 'FB': $rate = $feed->ForexBuying; break;
            case 'BB': $rate = $feed->BanknoteBuying; break;
            case 'BS': $rate = $feed->BanknoteSelling; break;
            case 'CR': $rate = $feed->CrossRateUSD; break;
            case 'FS':
            default: $rate = $feed->ForexSelling;
        }
		return (float)$rate;
	}
}

if( ! function_exists('exchange_rates') ) {
    function exchange_rates($atts, $do_echo = false){
        global $rates;
        extract( shortcode_atts( array (
            'title'          => '',
            'currencies_all' => 'true',
            'currencies'     => '',
            'caption'        => 'name',
            'captions'       => '',
            'flag'           => 'true',
            'flag_path'      => '',
            'fb'             => '',
            'fs'             => 'true',
            'bb'             => '',
            'bs'             => '',
            'cr'             => '',
            'showdate'       => '',
            'showsource'     => '',
            'class'          => ''
        ), $atts ) );

        $_return = $rates->shortcode_exchange_rates($atts);

        if($do_echo) {
            echo $_return;
        } else {
            return $_return;
        }
    }
}

/* iso4217 -> iso3166-1 Alpha-2
 * Decided to use first 2 letters of iso4217

$country_of_currency = array(
    'USD' => 'us',
    'AUD' => 'au',
    'DKK' => 'dk',
    'EUR' => 'eu',
    'GBP' => 'gb',
    'CHF' => 'ch',
    'SEK' => 'se',
    'CAD' => 'ca',
    'KWD' => 'kw',
    'NOK' => 'no',
    'SAR' => 'sa',
    'JPY' => 'jp',
    'BGN' => 'bg',
    'RON' => 'ro',
    'RUB' => 'ru',
    'IRR' => 'ir',
    'CNY' => 'cn',
    'PKR' => 'pk',
    'TRY' => 'tr'
);
*/

$exch_currencies = array(
    'USD' => __('Us Dollar', 'lbi-exchrates'),
    'AUD' => __('Australian Dollar', 'lbi-exchrates'),
    'DKK' => __('Danish Krone', 'lbi-exchrates'),
    'EUR' => __('Euro', 'lbi-exchrates'),
    'GBP' => __('Pound Sterling', 'lbi-exchrates'),
    'CHF' => __('Swiss Frank', 'lbi-exchrates'),
    'SEK' => __('Swedish Krona', 'lbi-exchrates'),
    'CAD' => __('Canadian Dollar', 'lbi-exchrates'),
    'KWD' => __('Kuwaiti Dinar', 'lbi-exchrates'),
    'NOK' => __('Norwegian Krone', 'lbi-exchrates'),
    'SAR' => __('Saudi Riyal', 'lbi-exchrates'),
    'JPY' => __('Japenese Yen', 'lbi-exchrates'),
    'BGN' => __('Bulgarian Lev', 'lbi-exchrates'),
    'RON' => __('New Leu', 'lbi-exchrates'),
    'RUB' => __('Russian Rouble', 'lbi-exchrates'),
    'IRR' => __('Iranian Rial', 'lbi-exchrates'),
    'CNY' => __('Chinese Renminbi', 'lbi-exchrates'),
    'PKR' => __('Pakistani Rupee', 'lbi-exchrates'),
);

$rates = new LBI_Exchange_Rates(); ?>