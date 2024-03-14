=== Cryptocurrency Payment Gateway ===
Contributors: weprogramit
Tags: bitcoin, bitcoincash, litecoin, dogecoin, cryptocurrency, gateway, woocommerce
Requires at least: 4.7
Tested up to: 6.5
Stable tag: 1.6.3
Requires PHP: 7.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Digital Currency Payment Gateway for WooCommerce. Easily accept Bitcoin, Bitcoin Cash, Litecoin, Dogecoin, and more in your store.

== Description ==
Our Cryptocurrency Payment Gateway was built with the core values of Cryptocurrency in mind with regards to anonymity and security. The plugin seamlessly enables your store to accept  Bitcoin, Bitcoin Cash, Litecoin, Dogecoin, and more, right away by simply adding your Wallet addresses.

The plugin was created to solve a solution that we and several merchants were facing, which was a gateway that respects customer privacy, no external redirects, seamless integration, has no middleman fees, and we achieved it. Over the years our plugin has been improved to also offer Zeroconf support enabling customers to instantly receive their products without risk to the merchant.

### Plugin Features:

* Provide a list of your own Bitcoin, Bitcoin Cash, Dogecoin, Litecoin, and other cryptocurrency wallet addresses or use a Block.io premium account  (get our [HD Wallet Add-on](https://www.cryptowoo.com/shop/cryptowoo-hd-wallet-addon/) to receive payments directly to HD wallets such as Electrum, Mycelium, Trezor, or Ledger Wallet)
* Keep the customer on your website: No redirection to third-party websites or iframes during checkout.
* Keep your data: No need to give customer data to a third party.
* Optional payment completion at zero confirmations using transaction confidence metrics.
* Set per-currency maximum order amount thresholds for zeroconf payments.
* Collect refund addresses during checkout.
* Support for all WooCommerce store currencies except Lao KIP.
* Supported exchange rate APIs: Bitcoinaverage, Bitcoincharts, Bitfinex, BitPay, BitTrex, Blockchain.info, CoinCap, Coindesk, CoinGecko, GDAX, Shapeshift, Kraken, Luno.com. OKCoin.com, OKCoin.cn, Poloniex
* Apply discounts and markups individually for each currency.
* Integrated into WooCommerce order emails and admin backend.
* WordPress Multisite compatible
* Supports “WooCommerce Currency Switcher” and “Aelia Currency Switcher for WooCommerce“
* No full node required – choose between different blockchain data providers or connect to your own private Esplora or Insight API instance

### HD Wallet Add-on features [premium]

* Derive a virtually unlimited number of addresses from the extended public key of your wallet.
* Generates one address per order automatically.
* The payments from your customers go straight into your own HD wallet such as Bitcoin.com wallet, Coinomi, Electrum, Trezor, Ledger Nano, or any other wallet with HD support.

You can get the HD Wallet Add-on [on our website](https://www.cryptowoo.com/shop/cryptowoo-hd-wallet-addon/).

### Ethereum and ERC-20 Add-on features [premium]

* Accept ERC-20 cryptocurrencies or tokens such as Ether (ETH), Tether USD (USDT), USD Coin (USDC), Dai (DAI), and Verse (VERSE).
* Web3 wallet support allows your customers to easily pay with the click of a button from their wallets such as MetaMask, Brave Browser, WalletConnect, Torus, Fortmatic.
* Receive all payments into a wallet such as Bitcoin.com wallet, Coinomi, Trezor, Ledger, Metamask or any other wallet that provides an Ethereum address.

You can get the Ethereum and ERC-20 Add-on [on our website](https://www.cryptowoo.com/shop/cryptowoo-ethereum-add-on/).

### Monero Add-on features [premium]

* Accept Monero (XMR).
* Supports integrated address, generating one address per order automatically.
* Payments to your own wallet address.

You can get the Monero Add-on [on our website](https://www.cryptowoo.com/shop/monero-add-on/).

### Dash Add-on features [premium]

* Accept Dash (DASH).
* Supports Dash payments to your own HD Wallet (xpub, drkp, drkv) with the HD Wallet Add-on.
* Accept instant payments via InstantSend.

You can get the Dash Add-on [on our website](https://www.cryptowoo.com/shop/dash-add-on/).

### Solana Add-on features [premium]

* Accept Solana (SOL).
* Solana fallback address allows you to receive all payments into a single Solana address.

You can get the Solana Add-on [on our website](https://www.cryptowoo.com/shop/solana-add-on/).

### Vertcoin Add-on features [premium]

* Accept Vertcoin (VTC).
* Supports Vertcoin payments to your own HD Wallet with the HD Wallet Add-on.

You can get the Vertcoin Add-on [on our website](https://www.cryptowoo.com/shop/vertcoin-add-on/).

### Dokan Add-on features [premium]

* Accept cryptocurrency payments in your Dokan Multivendor marketplace.
* This plugin displays the cryptocurrency payment processing data on the Dokan vendor dashboard pages.
* It does not affect the calculation of vendor commissions. Dokan commissions will still be calculated in fiat currency.
* Vendor payouts in cryptocurrency is not supported.

You can get the Dokan Add-on [on our website](https://www.cryptowoo.com/shop/dokan-add-on/).

### Pay for development to add support for additional cryptocurrencies:

* [Add a cryptocurrency](https://www.cryptowoo.com/shop/add-coin/)
* [Add an ERC-20 Token](https://www.cryptowoo.com/shop/add-erc-20-token/)


== Installation ==
1. Install the plugin into your WordPress website.
2. Add cryptocurrency addresses in the Address List in the settings.
3. Choose a payment processing API that will be used to check the blockchain for incoming payments
5. Enable the payment gateway in the settings and click save.
6. Disable internal WordPress Cron jobs and setup external Cron jobs (recommended)

Done!

Optionally you may navigate through the settings to customize the payment gateway to your preferences. If you are using the HD Wallet Add-on, you can add the master public key of your wallet instead of adding addresses to the address list in step 2.

== Screenshots ==
1. This is what the customers see on your site while viewing a product.
2. This is what the customers see on your site when checking out.
3. This is the checkout page that the customer will see when paying for an order.
4. This is the checkout flow configuration page in wp-admin.
5. This is the checkout settings page in wp-admin where you can customize the checkout to your liking.
6. This is payment settings page in wp-admin where you can customize the countdown, instructions, etc.
7. This is the thank you page where you can customize your successful payment message the customer receives.
8. This is the address list page in wp-admin where you can customize your Cryptocurrency addresses and specify email alerts.
9. This is the configuation page for Block.io which allows you to set your Block.io API keys.
10. This is the HD wallet settings page which enables enhanced feature set of the plugin for various Cryptocurrencies.
11. This is the Cron Scheduling settings where it will generate your Cron job commands to setup outside of WordPress.
12. This is the confirmation settings page where you can specify your minimum confirmations for all the available Cryptocurrencies.
13. This is the zeroconf settings where you can specify maximum order values that zeroconf will be accepted for.
14. This is what the WooCommerce order page will look like when using our plugin.
15. This is the transaction confidence page where you can specify your own thresholds of trust for unconfirmed transactions.
16. This is the Blockchain Access settings where you can specify your preferred processing API
17. This is the API resources control settings where you can configure Fallback API processing.
18. This is the advanced settings of the Payment Processing section with settings such as show/hide countdown, order expiration configuration and underpayment settings.
19. This is the exchange rate settings where you can specify which providers you want to pull the live prices from.
20. This is the decimal settings where you can specify how many decimal places you want your prices to be rounded to.
21. This is the multiplier settings for discount and surcharges. This enables you to customize on a per crypto basis the price increases for using certain cryptocurrencies.
22. This is the display settings where you can customize icon colors, further explorers and pricing tables.

== Changelog ==

= 1.6.3 =
* WordPress tested up to 6.5
* Improve the error message shown when CryptoWoo is disabled because of incomplete settings
* Fix recipient address on the order received page and in customer email should not be a link if the URL is invalid
* Fix link to view address on blockchain in checkout should not be visible if the URL is invalid
* Minor improvements to Trezor connect class
* Update the supported cryptocurrencies in Trezor Connect
* Update Trezor manifest
* Update Trezor Connect to version 9
* Fix pay with Trezor button in checkout has invisible font on themes with black buttons

= 1.6.2 =
* WooCommerce tested up to 8.7
* Improve display of crypto amount plus copy address and amount tooltip on the order payment page
* Add copy crypto amount button to the order payment page
* Improve animation when copying crypto address and amount
* Fix cryptocurrency selection buttons being missing on the customer payment page for manually created orders currently in pending payment status

= 1.6.1 =
* WooCommerce tested up to 8.6
* Update Redux Framework to 4.4.12.2 (includes fixing whitespaces in address list fields in the settings)

= 1.6.0 =
* WooCommerce tested up to 8.5
* Fix description html element is added to checkout when description is empty
* Set the default payment method title to 'Cryptocurrency' and the description to ''
* Improve background color of selected and on hover in checkout buttons
* Fix positioning issue of Ethereum and Solana tokens in checkout buttons
* Improve position and spacing of the database maintenance and setup wizard buttons in options page
* Always check lock time if it is available from the block explorer api response
* Update CryptoWoo logo and icon in wp-admin and checkout
* Add WooCommerce Checkout Blocks compatibility

= 1.5.0 =
* Add compatibility for WooCommerce High-Performance Order Storage (HPOS)
* Fix force accept payment action is displaying too far down on the page
* Fix force accept payment action does not allow increments in satoshi precision
* Improve section 'other' in wallet settings
* Multiple minor improvements


[See the full changelog for all versions](https://plugins.trac.wordpress.org/browser/cryptocurrency-payment-gateway/trunk/changelog)