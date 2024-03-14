<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
use Elementor\Controls_Manager;
use Elementor\Repeater;

class ccew_donation_Widget extends \Elementor\Widget_Base

{

    public function __construct($data = array(), $args = null)
    {
        parent::__construct($data, $args);

        wp_register_style('ccewd-donation-style', CCEW_URL . 'donation-box/assets/css/ccew-donation-style.css', array(), CCEW_VERSION);
        wp_register_script('ccew-etherjs', CCEW_URL . 'donation-box/assets/js/ethers-5.2.umd.min.js', array('elementor-frontend'), CCEW_VERSION, true);

        wp_register_script('ccew-custom', CCEW_URL . 'donation-box/assets/js/ccewd-custom.js', array('elementor-frontend'), CCEW_VERSION, true);
        wp_register_script('ccew-sweetalert2', CCEW_URL . 'donation-box/assets/js/sweetalert2.js', array('elementor-frontend'), CCEW_VERSION, true);
        wp_register_script('ccew-metamask', CCEW_URL . 'donation-box/assets/js/ccew-metamask.js', array('elementor-frontend'), CCEW_VERSION, true);
        wp_register_script('ccew-clipboard', CCEW_URL . 'donation-box/assets/js/clipboard.min.js', array('elementor-frontend'), CCEW_VERSION, true);

    }

    public function get_script_depends()
    {
        return array('ccew-metamask', 'ccew-clipboard', 'ccew-custom', 'ccew-sweetalert2', 'ccew-etherjs');

    }

    public function get_style_depends()
    {

        return array('ccewd-donation-style');

    }
    public function get_keywords()
    {
        return array('donation box', 'crypto widget', 'ccew', 'crypto donation box', 'coins');
    }
    public function get_name()
    {
        return 'cryptocurrency-donation-box-widget';
    }

    public function get_title()
    {
        return __('Cryptocurrency Donation Widget', 'ccew');
    }

    public function get_icon()
    {
        return 'eicon-price-table ccew-donation-icon';
    }

    public function get_categories()
    {
        return array('ccew');
    }

    protected function register_controls()
    {

        $coins = array(
            'select' => 'Select Coin',
            'bitcoin' => 'Bitcoin(BTC)',
            'ethereum' => 'Ethereum(ETH)',
            'metamask' => 'MetaMask',
            'tether' => 'Tether(USDT)',
            'cardano' => 'Cardano(ADA)',
            'xrp' => 'XRP ',
            'polkadot' => 'Polkadot(DOT)',
            'binance-coin' => 'Binance Coin(BNB)',
            'litecoin' => 'Litecoin(LTC)',
            'chainlink' => 'Chainlink(LINK)',
            'stellar' => 'Stellar(XLM)',
            'binance-usd' => 'Binance USD(BUSD)',
            'bitcoin-cash' => 'Bitcoin Cash(BCH)',
            'dogecoin' => 'Dogecoin(DOGE)',
            'usdcoin' => 'USD COIN(USDC)',
            'aave' => 'Aave(AAVE)',
            'uniswap' => 'Uniswap(UNI)',
            'wrappedbitcoin' => 'Wrapped Bitcoin(WBTC)',
            'avalanche' => 'Avalanche(AVAX)',
            'bitcoin-sv' => 'Bitcoin SV(BSV)',
            'eos' => 'EOS',
            'nem' => 'NEM(XEM)',
            'tron' => 'Tron(TRX)',
            'cosmos' => 'Cosmos(ATOM)',
            'monero' => 'Monero(XMR)',
            'tezos' => 'Tezos(XTZ)',
            'elrond' => 'Elrond(EGLD)',
            'iota' => 'IOTA(MIOTA)',
            'theta' => 'THETA(THETA)',
            'synthetix' => 'Synthetix(SNX)',
            'dash' => 'Dash',
            'maker' => 'Maker(MKR)',
            'dai' => 'Dai(DAI)',
            'ethereum-classic' => 'Ethereum Classic(ETC)',
            'lisk' => 'Lisk',
            'neo' => 'NEO',
            'vechain' => 'VeChain(VET)',
            'qtum' => 'Qtum',
            'omisego' => 'OmiseGO',
            'icon' => 'ICON(ICX)',
            'nano' => 'Nano',
            'verge' => 'Verge',
            'bytecoin-bcn' => 'Bytecoin',
            'zcash' => 'Zcash(ZEC)',
            'ontology' => 'Ontology(ONT)',
            'aeternity' => 'Aeternity',
            'steem' => 'Steem',
            'bakerytoken' => 'BakeryToken(BAKE)',
            'bancor' => 'Bancor(BNT)',
            'basic-attention-token' => 'Basic Attention Token(BAT)',
            'bitcoin-gold' => 'Bitcoin Gold(BTG)',
            'bittorrent' => 'BitTorrent(BTT)',
            'celo' => 'Celo(CELO)',
            'celsius' => 'Celsius(CEL)',
            'chiliz' => 'Chiliz(CHZ)',
            'curve-dao-token' => 'Curve DAO Token(CRV)',
            'decentraland' => 'Decentraland(MANA)',
            'shiba-inu' => 'SHIBA INU(SHIB)',
            'digibyte' => 'DigiByte(DGB)',
            'enjin-coin' => 'Enjin Coin(ENJ)',
            'flow' => 'Flow(FLOW)',
            'harmony' => 'Harmony(ONE)',
            'hedera-hashgraph' => 'Hedera Hashgraph(HBAR)',
            'helium' => 'Helium(HNT)',
            'holo' => 'Holo(HOT)',
            'huobi-token' => 'Huobi Token(HT)',
            'kucoin-token' => 'KuCoin Token(KCS)',
            'kusama' => 'Kusama(KSM)',
            'near-protocol' => 'NEAR Protocol(NEAR)',
            'nexo' => 'Nexo(NEXO)',
            'okb' => 'OKB(OKB)',
            'paxos-standard' => 'Paxos Standard(PAX)',
            'quant' => 'Quant(QNT)',
            'revain' => 'Revain(REV)',
            'siacoin' => 'Siacoin(SC)',
            'stacks' => 'Stacks(STX)',
            'sushiswap' => 'SushiSwap(SUSHI)',
            'swissborg' => 'SwissBorg(CHSB)',
            'telcoin' => 'Telcoin(TEL)',
            'the-graph' => 'The Graph(GRT)',
            'theta-fuel' => 'Theta Fuel(TFUEL)',
            'thorchain' => 'THORChain(RUNE)',
            'true-usd' => 'TrueUSD(TUSD)',
            'uma' => 'UMA(UMA)',
            'waves' => 'Waves(WAVES)',
            'xinfin-network' => 'XinFin Network(XDC)',
            'yearn-finance' => 'yearn.finance(YFI)',
            'zilliqa' => 'Zilliqa(ZIL)',

        );
        $this->start_controls_section(
            'ccewd_general_section',
            array(
                'label' => __('General Settings', 'ccew'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'ccewd_widget_type',
            array(
                'label' => __('Widget Type', 'ccew'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(

                    'tabular' => 'Tabular',
                    'list' => 'List',

                ),
                'default' => 'tabular',
            )
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'ccewd_coin_list',
            array(
                'label' => __('Select Coin', 'plugin-domain'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => false,
                'options' => $coins,
                'default' => 'select',
            )
        );

        $repeater->add_control(
            'ccewd_wallet_address',
            array(
                'label' => __('Enter wallet Address', 'plugin-domain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => __('Wallet address', 'plugin-domain'),
                'show_label' => true,
                'condition' => array(
                    'ccewd_coin_list!' => 'select',
                ),
            )
        );

        $repeater->add_control(
            'ccewd_wallet_address_meta',
            array(
                'label' => __('Wallet Address Meta', 'plugin-domain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => __('Tag/Note/memo', 'plugin-domain'),
                'condition' => array(
                    'ccewd_coin_list!' => 'select',
                ),
                'show_label' => true,
            )
        );
        $this->add_control(
            'ccewd_repeater_data',
            array(
                'label' => __('Wallet address', 'plugin-domain'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => array(
                    array(
                        'ccewd_coin_list' => __('bitcoin', 'plugin-domain'),

                    ),

                ),
                'title_field' => '{{{ ccewd_coin_list }}}',
            )
        );
        $this->add_control(
            'ccewd_url',
            array(
                'label' => __('post', 'sfafe-plugin'),
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => CCEW_URL,
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'ccewd_extra_settings',
            array(
                'label' => __('Extra Settings', 'plugin-name'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,

            )
        );

        $this->add_control(
            'ccewd_metamask_price',
            array(
                'label' => __('MetaMask default price', 'plugin-domain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'default' => __('0.005', 'plugin-domain'),
                'placeholder' => __('Enter Default amount in Ethereum', 'plugin-domain'),
            )
        );

        $this->add_control(
            'ccewd_metamask_title',
            array(
                'label' => __('MetaMask Title', 'plugin-domain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'default' => __('Donate With MetaMask', 'plugin-domain'),
                'placeholder' => __('Type your title here', 'plugin-domain'),
            )
        );
        $this->add_control(
            'ccewd_metamask_description',
            array(
                'label' => __('Description', 'plugin-domain'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 5,
                'default' => __('Donate ETH Via PAY With Meta Mask', 'plugin-domain'),
                'placeholder' => __('Type your description here', 'plugin-domain'),
            )
        );

        $this->add_control(
            'ccewd_coins_title',
            array(
                'label' => __('Main Title', 'plugin-domain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'label_block' => true,
                'default' => __('Donate [coin-name] to this address', 'plugin-domain'),
                'placeholder' => __('Type your title here', 'plugin-domain'),
            )
        );
        $this->add_control(
            'ccewd_coins_description',
            array(
                'label' => __('Description', 'plugin-domain'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 5,
                'default' => __('Scan the QR code or copy the address below into your wallet to send some [coin-name]', 'plugin-domain'),
                'placeholder' => __('Type your description here', 'plugin-domain'),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'ccewd_style_section',
            array(
                'label' => __('Style Section', 'plugin-name'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );
        $this->add_control(
            'ccewd_primery_color',
            array(
                'label' => __('Primery Color', 'plugin-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,

                'selectors' => array(
                    '{{WRAPPER}} .ccewd_input_add h2.ccewd-title ,
				{{WRAPPER}} .ccewd_input_add span.ccewd_tag_heading,
				{{WRAPPER}} .ccewd-classic-list h2.ccewd-title,
				{{WRAPPER}} .ccewd-classic-list span.ccewd_tag_heading' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'ccewd_secondary_color',
            array(
                'label' => __('Secondary Color', 'plugin-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,

                'selectors' => array(
                    '{{WRAPPER}} .ccewd_input_add p.ccewd-desc ,
				{{WRAPPER}} .ccewd_input_add .ccewd_tag,
				{{WRAPPER}} .ccewd-classic-list .ccewd_tag' => 'color: {{VALUE}}',
                ),
                'condition' => array(
                    'ccewd_widget_type' => 'tabular',
                ),
            )
        );

        $this->add_control(
            'ccewd_bg_color',
            array(
                'label' => __('Background Color', 'plugin-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,

                'selectors' => array(
                    '{{WRAPPER}} .ccewd-tabs-content ,
				{{WRAPPER}} ul.ccewd-tabs li.current,
				{{WRAPPER}} li.ccewd-classic-list ' => 'background-color: {{VALUE}}',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'ccewd_border',
                'label' => __('Border', 'plugin-domain'),
                'selector' => '{{WRAPPER}} li.ccewd-classic-list,
								{{WRAPPER}} .ccewd-container',
            )
        );
        $this->add_control(
            'ccewd_border_radius',
            array(
                'label' => __('Border Radius', 'plugin-domain'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => array('px', '%', 'em'),
                'selectors' => array(
                    '{{WRAPPER}} li.ccewd-classic-list,
					{{WRAPPER}} .ccewd-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            array(
                'name' => 'ccewd_box_shadow',
                'label' => __('Box Shadow', 'plugin-domain'),
                'selector' => '{{WRAPPER}} li.ccewd-classic-list,
								{{WRAPPER}} .ccewd-container',
            )
        );
        $this->end_controls_section();
        $this->start_controls_section(
            'ccewd_review_section',
            array(
                'label' => __('We Would Appreciate Your Feedback', 'ccew'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );
        $this->add_control(
            'ccew_review_note',
            array(
                'label' => __('Review Notice', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'show_label' => false,
                'raw' => '<div class="ccew_cmc_demo">
        <div class="ccew_cmc_def">
       You\'ve used our widget for a while. We hope you liked it! </br> Please give us a quick rating to help us keep improving the plugin!</div>
        <div class="ccew_link_wrap"><a class="ccew_demo_link" href="https://wordpress.org/support/plugin/cryptocurrency-widgets-for-elementor/reviews/#new-post" target="_blank"><button class="ccew-custom-primry-btn">Submit Review ★★★★★</button></a></div>
    </div>',

            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'ccewd_permotion_section',
            array(
                'label' => __('Crypto Pro Plugins Demos', 'ccew'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );
      $this->add_control(
            'ccew_important_note',
            array(
                'label' => __('Pro plugins', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'show_label' => false,
                'raw' => '<ul class="ccew-promotion-plugins">
                            <div class="ccew_cmc_demo">
                             <div class="ccew_logo_container"><a href="'.CCEW_DEMO_URL.'/demo/coins-marketcap/'.CCEW_UTM.'" target="_blank"><img src="' . CCEW_URL . 'assets/images/coinmarketcap-logo.png" alt="Cryptocurrency widget for elementor" style="max-width:80px;"></a></div>
                             <div class="ccew_cmc_def">
                            <strong>Coins MarketCap</strong>
                            Coins Marketcap plugin creates a fully automatic crypto coins price listing website that dynamically generates 4500+ coins pages.
                            </div><div class="ccew_link_wrap"><a class="ccew_demo_link" href="'.CCEW_DEMO_URL.'/demo/coins-marketcap/'.CCEW_UTM.'" target="_blank"><button class="ccew-custom-primry-btn">View Demos </button> </a> <a class="ccew_demo_buyk" href="https://cryptocurrencyplugins.com/wordpress-plugin/coins-marketcap/?utm_source=widget_settings&utm_medium=inside&utm_campaign=get-pro-cmc&utm_content=buy-now" target="_blank"><button class="ccew-custom-primry-btn">Buy Pro</button></a></div>
                            </div>
                            <hr>
                            <div class="ccew_cmc_demo">
                            <div class="ccew_logo_container"><a href="'.CCEW_DEMO_URL.'/demo/cryptocurrency-widgets-pro/'.CCEW_UTM.'" target="_blank"><img src="' . CCEW_URL . 'assets/images/crypto-widget-pro.png" alt="Cryptocurrency widget for elementor" style="max-width:80px;"></a></div>
                            <div class="ccew_cmc_def"><strong>Cryptocurrency Widgets Pro</strong>
                            Show cryptocurrency price table, historical charts, tickers and other widgets inside any page or post.</div><div class="ccew_link_wrap"><a class="ccew_demo_link" href="'.CCEW_DEMO_URL.'/demo/cryptocurrency-widgets-pro/'.CCEW_UTM.'" target="_blank"><button class="ccew-custom-primry-btn">View Demos </button> </a><a class="ccew_demo_buyk"  href="https://cryptocurrencyplugins.com/wordpress-plugin/cryptocurrency-widgets-pro/?utm_source=widget_settings&utm_medium=inside&utm_campaign=get-pro-ccpw&utm_content=buy-now" target="_blank"><button class="ccew-custom-primry-btn">Buy Pro</button></a></div>
                            </div>
                          </ul>',

            )
        );
        $this->end_controls_section();

    }

    // for frontend
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        require CCEW_DIR . 'donation-box/includes/ccew-frontend-layouts.php';

    }

    // for live editor
    protected function content_template()
    {

        require CCEW_DIR . 'donation-box/includes/ccew-editor-layout.php';

    }

}

\Elementor\Plugin::instance()->widgets_manager->register(new ccew_donation_Widget());
