<div class="eod_admin_header">
    <div class="eod_admin_facade">
        <div class="h">
            <img src="<?= EOD_URL ?>img/EOD_logo_w.png" alt="<?php _e('EOD Historical Data<br> Financial WP plugin', 'eod_stock_prices'); ?>">
        </div>
        <div class="info">
            <p><b>Stocks Market Data</b>: Stocks | ETF | Funds | Indices | Futures | Bonds & Government Bonds | Options | Forex Pairs & Cryptocurrencies</p>
            <p><b>Exchanges Details | End of Day, Intraday, Delayed and Real-Time prices</b></p>
            <p><b>Fundamental data + Financial tables:</b> Earnings | Balance Sheets | Equities | Stock Shares Outstanding | Cash Flows | Income Statements</p>
        </div>
    </div>
    <nav>
        <a class="eod_btn<?= isset($_GET['page']) && $_GET['page'] === 'eod-stock-prices' ? ' current' : '' ?>" href="<?= get_admin_url() ?>admin.php?page=eod-stock-prices">
            Quick Start & Samples
        </a>
        <a class="eod_btn<?= isset($_GET['page']) && $_GET['page'] === 'eod-examples' ? ' current' : '' ?>" href="<?= get_admin_url() ?>admin.php?page=eod-examples">
            Shortcode Generator
        </a>
        <a class="eod_btn" href="<?= get_admin_url() ?>edit.php?post_type=fundamental-data">
            Fundamental Data Presets
        </a>
        <a class="eod_btn" href="<?= get_admin_url() ?>edit.php?post_type=financials">
            Financial Table Presets
        </a>
        <a class="eod_btn<?= isset($_GET['page']) && $_GET['page'] === 'eod-settings' ? ' current' : '' ?>" href="<?= get_admin_url() ?>admin.php?page=eod-settings">
            Settings
        </a>
        <?php global $eod_api; ?>
        <?php if( EOD_DEFAULT_API === $eod_api->get_eod_api_key() ) { ?>
        <a class="eod_btn blue" href="https://eodhd.com/register/?utm_source=p_c&utm_medium=wp_plugin&utm_campaign=new_wp" target="_blank">
            Get API Key
        </a>
        <?php }else{ ?>
            <a class="eod_btn blue" href="https://eodhd.com/pricing?utm_source=p_c&utm_medium=wp_plugin&utm_campaign=new_wp" target="_blank">
                Update to PRO
            </a>
        <?php } ?>
    </nav>
</div>