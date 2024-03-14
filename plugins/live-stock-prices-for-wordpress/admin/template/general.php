<?php
$options =  get_option('eod_options');
$has_apikey = (bool) $options['api_key'];
$use_demo = $has_apikey && $options['api_key'] === EOD_DEFAULT_API;
?>
<div class="wrap">
    <div class="eod_page with_sidebar">
        <?php eod_include( 'admin/template/header.php' ); ?>
        <div>
            <div class="eod_section quick_start">
                <div class="h">Quick start</div>
                <div class="list">
                    <div class="item <?= $has_apikey && !$use_demo ? 'complete' : '' ?>">
                        <div class="goal">
                            <span class="number">1</span>
                            <span>Sign up on the website</span>
                        </div>
                        <ul class="eod_list">
                            <li>Click the <a target="_blank" href="https://eodhd.com/register">Create Account</a> button.</li>
                            <li>Fill out the registration form with your name, email address, and password.</li>
                            <li>Click "Register" to complete the registration process.</li>
                        </ul>
                    </div>
                    <div class="item <?= $has_apikey && !$use_demo ? 'complete' : '' ?>">
                        <div class="goal">
                            <span class="number">2</span>
                            <span>Get your API key</span>
                        </div>
                        <ul class="eod_list">
                            <li>After successfully registering, <a target="_blank" href="https://eodhd.com/login">log in</a> to your account on the website.</li>
                            <li>Go to the <a href="https://eodhd.com/cp/settings?utm_source=p_c&utm_medium=wp_plugin&utm_campaign=new_wp" target="_blank">Settings</a> section and look for the option to copy your API key.</li>
                            <li>Copy the API key or check your registered email for its delivery.</li>
                        </ul>
                    </div>
                    <div class="item <?= $has_apikey && !$use_demo ? 'complete' : '' ?>">
                        <div class="goal">
                            <span class="number">3</span>
                            <span>Insert Your API Key Here</span>
                        </div>
                        <form id="quick_start_form">
                            <input class="key_input" type="text" value="" placeholder="demo">
                            <input class="eod_btn blue" type="submit" value="Save">
                        </form>
                        <?php do_action('api_key_error'); ?>
                        <p><b>Note:</b> The Free key grants access to historical data for one year.</p>
                    </div>
                    <?php if($has_apikey){ ?>
                    <div class="item">
                        <div class="goal">
                            <span class="number">4</span>
                            <span>Get started with our features</span>
                        </div>
                        <ul class="eod_list">
                            <li>Use shortcodes on your website pages with our <a href="<?= get_admin_url() ?>admin.php?page=eod-examples">shortcode generator</a>.</li>
                            <li>Add our widget to your <a href="<?= get_admin_url() ?>widgets.php">theme's widget areas</a>.</li>
                            <?php if(EOD_ELEMENTOR_INSTALLED){ ?>
                                <li>Find our widgets in Elementor under the name EODHD or in the corresponding group.</li>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php } ?>
                </div>
            </div>

            <div class="eod_section">
                <div class="h">Pricing plans</div>
                <p>Check out our pricing page for plan details, features, and pricing options.</p>
                <a class="eod_btn blue" href="https://eodhd.com/pricing?utm_source=p_c&utm_medium=wp_plugin&utm_campaign=new_wp" target="_blank">Go to pricing page</a>
            </div>

            <div class="eod_section">
                <div class="h">Create a widget by simple SHORTCODE</div>
                <p>Just generate the shortcode by the samples below and put it into your post.</p>
                <div class="samples_grid">
                    <div class="widget row2">
                        <div class="header">Financial News</div>
                        <div class="description">Displays news feed related by TAGs or ticker</div>
                        <div class="sample">
                            <img src="<?= EOD_URL ?>/img/sample_news.png">
                        </div>
                        <div class="footer">
                            <a href="<?= get_admin_url() ?>admin.php?page=eod-examples&e=news" class="s eod_btn">Create Shortcode</a>
                            <a href="<?= get_admin_url() ?>widgets.php" class="w">
                                or use as WP Widget:
                                <span>EODHD Financial news</span>
                            </a>
                        </div>
                    </div>
                    <div class="widget col2">
                        <div class="header">Financial Table</div>
                        <div class="description">Allows for organizing the Financial data eg: Earnings, Financial Reports, Balance Sheets, Cash Flows, and Income Statements by the quarterly or yearly view, with the specified time intervals.</div>
                        <div class="sample">
                            <img src="<?= EOD_URL ?>/img/sample_ftable.png">
                        </div>
                        <div class="footer">
                            <a href="<?= get_admin_url() ?>admin.php?page=eod-examples&e=financials" class="s eod_btn">Create Shortcode</a>
                            <a href="<?= get_admin_url() ?>widgets.php" class="w">
                                or use as WP Widget:
                                <span>EODHD Financial Table</span>
                            </a>
                        </div>
                    </div>
                    <div class="widget">
                        <div class="header">Ticker String</div>
                        <div class="description">Сan be used to display single ticker prices in various places of your site</div>
                        <div class="sample">
                            <img src="<?= EOD_URL ?>/img/sample_ticker.png">
                        </div>
                        <div class="footer">
                            <a href="<?= get_admin_url() ?>admin.php?page=eod-examples&e=ticker" class="s eod_btn">Create Shortcode</a>
                            <a href="<?= get_admin_url() ?>widgets.php" class="w">
                                or use as WP Widget:
                                <span>EODHD Stock Price Ticker</span>
                            </a>
                        </div>
                    </div>
                    <div class="widget">
                        <div class="header">Fundamental Data</div>
                        <div class="description">Such as General Information, Numbers for Valuation, Earnings etc. For Stocks, ETFs, Mutual Funds, Indices</div>
                        <div class="sample">
                            <img src="<?= EOD_URL ?>/img/sample_fundamental.png">
                        </div>
                        <div class="footer">
                            <a href="<?= get_admin_url() ?>admin.php?page=eod-examples&e=fundamental" class="s eod_btn">Create Shortcode</a>
                            <a href="<?= get_admin_url() ?>widgets.php" class="w">
                                or use as WP Widget:
                                <span>EODHD Fundamental Data</span>
                            </a>
                        </div>
                    </div>
                    <div class="widget">
                        <div class="header">Currency Converter (Crypto + Forex)</div>
                        <div class="description">
                            Plugin provides your WordPress website users with the powerful tool for real-time converting of:
                            <ul>
                                <li>• Forex Currency to Cryptocurrency</li>
                                <li>• Cryptocurrency to Forex Currency</li>
                                <li>• Forex Currency to any other Forex Currency</li>
                                <li>• Cryptocurrency to any other Cryptocurrency</li>
                            </ul>
                        </div>
                        <div class="sample">
                            <img src="<?= EOD_URL ?>/img/sample_converter.png">
                        </div>
                        <div class="footer">
                            <a href="<?= get_admin_url() ?>admin.php?page=eod-examples&e=converter" class="s eod_btn">Create Shortcode</a>
                            <a href="<?= get_admin_url() ?>widgets.php" class="w">
                                or use as WP Widget:
                                <span>EODHD Currency Converter</span>
                            </a>
                        </div>
                    </div>

<!--                    <a href="--><?//= get_admin_url() ?><!--admin.php?page=eod-examples&e=ticker">-->
<!--                        <div class="h">Ticker string</div>-->
<!--                        <div>Сan be used to display single ticker prices in various places of your site</div>-->
<!--                    </a>-->
<!--                    <a href="--><?//= get_admin_url() ?><!--admin.php?page=eod-examples&e=fundamental">-->
<!--                        <div class="h">Fundamental data</div>-->
<!--                        <div>Such as General Information, Numbers for Valuation, Earnings etc. For Stocks, ETFs, Mutual Funds, Indices</div>-->
<!--                    </a>-->
<!--                    <a href="--><?//= get_admin_url() ?><!--admin.php?page=eod-examples&e=news">-->
<!--                        <div class="h">Financial news</div>-->
<!--                        <div>Displays news feed related by TAGs or ticker</div>-->
<!--                    </a>-->
                </div>
            </div>

            <div class="eod_section">
                <div class="h">Or use standard WordPress widget's</div>
                <p>
                    You can configure any of our plugin widgets:<br>
                    <ul style="list-style: disc; margin-left: 20px;">
                        <li>EODHD Financial news</li>
                        <li>EODHD Stock Price Ticker</li>
                        <li>EODHD Fundamental Data</li>
                        <li>EODHD Financial Table</li>
                        <li>EODHD Currency Converter</li>
                    </ul>
                    on the <a href="<?= get_admin_url() ?>widgets.php">'Appearance-> Widgets' settings page</a>.
                </p>
            </div>

            <div class="eod_section">
                <div class="h">Any suggestions or still have any questions?</div>
                <p>We are gladly implementing new demanded features, which are suggested by our subscriber's partner and potential users, feel free to send us an email to <a href="mailto:support@eodhistoricaldata.com">support@eodhistoricaldata.com</a> and we will get back to you next 24 hours!</p>
            </div>
        </div>
        <div class="eod_sidebar">
            <?php include( plugin_dir_path( __FILE__ ) . 'sidebar.php'); ?>
        </div>
    </div>
</div>