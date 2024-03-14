import React, {Component} from "react";
import axios from "axios";


import {
    Button
} from "@wordpress/components";

import {Row} from './Row';

import {__, sprintf} from "@wordpress/i18n";
import {Data} from "./Data";
import Import from "./Import";
import {Success} from "./Success";
import {Uploader} from "./Uploader";
import {Notice} from "../notice/Notice";
import {Complete} from './Complete';

import {isEmpty as __isEmpty} from 'lodash';

interface S2WP {
    products?: object
    products_total_pages?: number
    products_current_page?: number
    products_import_complete?: string | boolean

    customers?: object
    customers_total_pages?: number
    customers_current_page?: number
    customers_import_complete?: string | boolean

    orders?: object
    orders_total_pages?: number
    orders_current_page?: number
    orders_import_complete?: string | boolean

    current_step?: "upload" | "products" | "customers" | "orders" | "plugins" | "complete"

    woocommerce_status?: 'active' | 'inactive',

    ajaxNonce?: string
    adminUrl?: string
    shopUrl?: string
    shopAdminUrl?: string

    ignoredPlugins?: string[] // These are already installed. no need to display them in the list again.
}

interface State extends S2WP {
    products_import_complete?: boolean
    customers_import_complete?: boolean
    orders_import_complete?: boolean

    isUploading?: boolean
    isImporting?: boolean
    isInstallingPlugins?: boolean

    plugins?: string[]
    pluginsToBeInstalled?: string[]
    installedPlugins?: string[]
}

declare global {
    interface Window {
        shopify2wp: S2WP
        ajaxurl: string
    }
}

// const s2wp = window.shopify2wp;

class App extends Component<any, State> {
    constructor() {
        // @ts-ignore
        super(...arguments);

        this.state = {
            products: window.shopify2wp.products,
            products_total_pages: Number(window.shopify2wp.products_total_pages),
            products_current_page: Number(window.shopify2wp.products_current_page),
            products_import_complete: !!window.shopify2wp.products_import_complete,

            customers: window.shopify2wp.customers,
            customers_total_pages: Number(window.shopify2wp.customers_total_pages),
            customers_current_page: Number(window.shopify2wp.customers_current_page),
            customers_import_complete: !!window.shopify2wp.customers_import_complete,

            orders: window.shopify2wp.orders,
            orders_total_pages: Number(window.shopify2wp.orders_total_pages),
            orders_current_page: Number(window.shopify2wp.orders_current_page),
            orders_import_complete: !!window.shopify2wp.orders_import_complete,

            // current_step: 'plugins',
            current_step: window.shopify2wp.current_step,

            isUploading: false,
            isImporting: false,
            isInstallingPlugins: false,

            plugins: [
                'all-in-one-seo-pack',
                'coming-soon',
                'wpforms-lite',
                'optinmonster',
                'google-analytics-for-wordpress',
                'wp-mail-smtp',
                'trustpulse-api'
            ],

            pluginsToBeInstalled: [],
            installedPlugins: [],
            ignoredPlugins: Object.values(window.shopify2wp.ignoredPlugins) || [],
        };

        // console.log(this.state);
    }

    componentDidMount() {
        this.setState({
            plugins: [
                'all-in-one-seo-pack',
                'coming-soon',
                'wpforms-lite',
                'optinmonster',
                'google-analytics-for-wordpress',
                'wp-mail-smtp',
                'trustpulse-api'
            ].filter(val => {
                return !this.pluginIsAlreadyInstalled(val);
            }),
        });
    }

    startUpload() {
        this.setState({
            isUploading: true,
            current_step: 'products'
        })
    }

    startImport() {
        if (this.state.isImporting) {
            return;
        }

        this.setState({isImporting: true});

        const _doImport = async (type: "products" | "customers" | "orders") => {
            const productsImporter = new Import(type);

            productsImporter.state = this.state;

            productsImporter.setState = this.setState.bind(this);
            // @ts-ignore
            productsImporter.getState = (key?: string) => key ? this.state[key] : this.state;

            await productsImporter.process();
        }

        _doImport("products").then(() => {
            this.setState({
                current_step: 'customers',
                products_import_complete: true,
            });
            _doImport("customers").then(() => {
                this.setState({
                    current_step: 'orders',
                    customers_import_complete: true,
                });
                _doImport("orders").then(() => {
                    if (__isEmpty(this.state.plugins)) {
                        this.setState({
                            orders_import_complete: true,
                            current_step: 'complete',
                            isImporting: false,
                            isInstallingPlugins: false,
                            pluginsToBeInstalled: []
                        });
                    } else {
                        this.setState({
                            current_step: 'plugins',
                            orders_import_complete: true,
                            isImporting: false
                        });
                    }

                    // Clear data
                    axios.post(`${window.ajaxurl}?action=shopify2wp_clear_data`, {
                        nonce: window.shopify2wp.ajaxNonce || false
                    })
                        .then(async (response) => {
                            console.log(response);
                        })
                        .catch(err => {
                            console.error(err);
                            // console.log(state);

                        });

                    Notice.hide();
                });
            })
        });
    }

    render() {
        // console.log(this.state);

        return (
            <div className="import-shopify-to-wp">
                <header className="import-shopify-to-wp__header">
                    <div className="import-shopify-to-wp__container">
                        <div className="import-shopify-to-wp__logo">
                            <h1>{__('Shopify to WP Importer', 'import-shopify-to-wp')}</h1>
                        </div>
                    </div>
                </header>

                <div className="import-shopify-to-wp__container">
                    <div className="import-shopify-to-wp__steps">
                        <div className='active'>
                            <mark>1</mark>
                            {__('Upload data', 'import-shopify-to-wp')}
                        </div>
                        <div
                            className={['products', 'customers', 'orders', 'plugins', 'complete'].indexOf(this.state.current_step) > -1 ? 'active' : ''}>
                            <mark>2</mark>
                            {__('Products', 'import-shopify-to-wp')}
                        </div>
                        <div
                            className={['customers', 'orders', 'plugins', 'complete'].indexOf(this.state.current_step) > -1 ? 'active' : ''}>
                            <mark>3</mark>
                            {__('Customers', 'import-shopify-to-wp')}
                        </div>
                        <div
                            className={['orders', 'plugins', 'complete'].indexOf(this.state.current_step) > -1 ? 'active' : ''}>
                            <mark>4</mark>
                            {__('Orders', 'import-shopify-to-wp')}
                        </div>
                        <div className={this.state.current_step === 'complete' ? 'active' : ''}>
                            <mark>5</mark>
                            {__('Complete', 'import-shopify-to-wp')}
                        </div>
                    </div>

                    {this.state.current_step === 'upload' && <Row>
                        <Uploader refreshState={this.setState.bind(this)}/>
                    </Row>
                    }

                    {this.state.current_step !== 'upload' && this.state.current_step !== 'complete' && this.state.current_step !== 'plugins' && !this.state.isImporting &&
                    <div className="import-shopify-to-wp__import-button-container">
                        <Button isPrimary disabled={this.state.isImporting} onClick={this.startImport.bind(this)}>
                            {__('Start Import', 'import-shopify-to-wp')}
                        </Button>
                    </div>
                    }

                    {this.state.isImporting &&
                    <div className="notice notice-info import-shopify-to-wp__notice">
                        <span>
                            <img src={window.shopify2wp.adminUrl + 'images/spinner-2x.gif'} alt=""/>
                        </span>
                        <p dangerouslySetInnerHTML={{
                            __html: sprintf(
                                __('Please keep this window open until the import process is complete. %sDO NOT close this window or leave the page.%s', 'import-shopify-to-wp'),
                                '<strong>',
                                '</strong>'
                            )
                        }}>
                        </p>
                    </div>
                    }

                    {this.state.current_step === 'products' &&
                    <Row>
                        <div className='import-shopify-to-wp__progress-page'>
                            <div></div>
                            {this.state.products_current_page <= this.state.products_total_pages &&
                            <div dangerouslySetInnerHTML={{
                                __html: sprintf(
                                    __('Page %d of %d', 'import-shopify-to-wp'),
                                    this.state.products_current_page,
                                    this.state.products_total_pages
                                )
                            }}>
                            </div>}
                        </div>
                        {this.state.products_total_pages === 0
                            ? <Success>{__('No products to import!', 'import-shopify-to-wp')}</Success>
                            : <>{this.state.products_import_complete ?
                                <Success>{__('All products imported!', 'import-shopify-to-wp')}</Success> : <Data
                                    data={this.state.products}
                                    type='products'
                                    noData={() => {
                                        if (Object.keys(this.state.products).length < 1) {
                                            return __('No products available to import', 'import-shopify-to-wp')
                                        }

                                        return false;
                                    }}
                                />}</>
                        }
                    </Row>
                    }

                    {this.state.current_step === 'customers' &&
                    <Row>
                        <div className='import-shopify-to-wp__progress-page'>
                            <div></div>
                            <div>Page {this.state.customers_current_page} of {this.state.customers_total_pages}</div>
                        </div>

                        {this.state.customers_import_complete ?
                            <Success>{__('All customers imported!', 'import-shopify-to-wp')}</Success> : <Data
                                data={this.state.customers}
                                type='customers'
                                noData={() => {
                                    if (Object.keys(this.state.customers).length < 1) {
                                        return __('No customers available to import', 'import-shopify-to-wp')
                                    }

                                    return false;
                                }}
                            />}
                    </Row>
                    }

                    {this.state.current_step === 'orders' &&
                    <Row>
                        <div className='import-shopify-to-wp__progress-page'>
                            <div></div>
                            <div>Page {this.state.orders_current_page} of {this.state.orders_total_pages}</div>
                        </div>

                        {this.state.orders_import_complete ?
                            <Success>{__('All orders imported!', 'import-shopify-to-wp')}</Success> : <Data
                                data={this.state.orders}
                                type='orders'
                                noData={() => {
                                    if (Object.keys(this.state.orders).length < 1) {
                                        return __('No orders available to import', 'import-shopify-to-wp')
                                    }

                                    return false;
                                }}
                            />}
                    </Row>}
                    {this.state.current_step === 'plugins' &&
                    <Row>
                        <div className="import-shopify-to-wp__plugins"
                             style={{
                                 /*@ts-ignore*/
                                 '--s2wp-loader-url': 'url(' + window.shopify2wp.adminUrl + 'images/spinner-2x.gif' + ')'
                             }}>
                            <h2>{__('Recommended Plugins to Grow Your Online Store', 'import-shopify-to-wp')}</h2>
                            <div className="import-shopify-to-wp__plugins__description">
                                {__(`We recommend that you install the following free plugins to get more traffic and sales
                                to your WooCommerce store. You can uncheck any plugin that you don’t want to install,
                                but clicking continue will install and activate all plugins that are checked below.`, 'import-shopify-to-wp')}

                            </div>

                            {!this.pluginIsAlreadyInstalled('all-in-one-seo-pack') ?
                                <div className={this.pluginClass('all-in-one-seo-pack')}
                                     onClick={() => this.markPlugin('all-in-one-seo-pack')}>
                                    <div
                                        className="import-shopify-to-wp__plugin__title">{__('All in One SEO for WordPress', 'import-shopify-to-wp')}
                                    </div>
                                    <div className="import-shopify-to-wp__plugin__description">
                                        {__('Improve your SEO rankings with the #1 SEO toolkit for WordPress (used by 2 million users).', 'import-shopify-to-wp')}
                                    </div>
                                </div> : <></>}

                            {!this.pluginIsAlreadyInstalled('google-analytics-for-wordpress') &&
                            <div className={this.pluginClass('google-analytics-for-wordpress')}
                                 onClick={() => this.markPlugin('google-analytics-for-wordpress')}>
                                <div
                                    className="import-shopify-to-wp__plugin__title">{__('MonsterInsights - Google Analytics for WordPress', 'import-shopify-to-wp')}
                                </div>
                                <div className="import-shopify-to-wp__plugin__description">
                                    {__('See the stats that matter with the best analytics plugin for WordPress (trusted by 3 million users).', 'import-shopify-to-wp')}
                                </div>
                            </div>}

                            {!this.pluginIsAlreadyInstalled('coming-soon') &&
                            <div className={this.pluginClass('coming-soon')}
                                 onClick={() => this.markPlugin('coming-soon')}>
                                <div className="import-shopify-to-wp__plugin__title">
                                    {__('SeedProd - Drag & Drop Landing Page Builder', 'import-shopify-to-wp')}
                                </div>
                                <div className="import-shopify-to-wp__plugin__description">
                                    {__('Easily create custom landing pages to increase your conversions (trusted by 1 million users).', 'import-shopify-to-wp')}
                                </div>
                            </div>}


                            {!this.pluginIsAlreadyInstalled('optinmonster') &&
                            <div className={this.pluginClass('optinmonster')}
                                 onClick={() => this.markPlugin('optinmonster')}>
                                <div className="import-shopify-to-wp__plugin__title">
                                    {__('OptinMonster - Get More Subscribers & Customers', 'import-shopify-to-wp')}
                                </div>
                                <div className="import-shopify-to-wp__plugin__description">
                                    {__('Convert abandoning visitors into email subscribers & customers with the #1 marketing toolkit for WordPress.', 'import-shopify-to-wp')}
                                </div>
                            </div>}

                            {!this.pluginIsAlreadyInstalled('wpforms-lite') &&
                            <div className={this.pluginClass('wpforms-lite')}
                                 onClick={() => this.markPlugin('wpforms-lite')}>
                                <div className="import-shopify-to-wp__plugin__title">
                                    {__('WPForms - WordPress Form Builder', 'import-shopify-to-wp')}
                                </div>
                                <div className="import-shopify-to-wp__plugin__description">
                                    {__('Easily create smarter online forms, surveys, and 150+ other forms (used by 4 million website owners).', 'import-shopify-to-wp')}
                                </div>
                            </div>}

                            {!this.pluginIsAlreadyInstalled('trustpulse-api') &&
                            <div className={this.pluginClass('trustpulse-api')}
                                 onClick={() => this.markPlugin('trustpulse-api')}>
                                <div className="import-shopify-to-wp__plugin__title">
                                    {__('TrustPulse - Social Proof Notifications', 'import-shopify-to-wp')}
                                </div>
                                <div className="import-shopify-to-wp__plugin__description">
                                    {__('Increase conversions by up to 15% using real-time user activity notifications.', 'import-shopify-to-wp')}
                                </div>
                            </div>}

                            {!this.pluginIsAlreadyInstalled('wp-mail-smtp') &&
                            <div className={this.pluginClass('wp-mail-smtp')}
                                 onClick={() => this.markPlugin('wp-mail-smtp')}>
                                <div className="import-shopify-to-wp__plugin__title">
                                    {__('WP Mail SMTP - Improves Email Deliverability', 'import-shopify-to-wp')}
                                </div>
                                <div className="import-shopify-to-wp__plugin__description">
                                    {__('Make sure your receipts and other store emails always get delivered (trusted by 2 million users).', 'import-shopify-to-wp')}
                                </div>
                            </div>}

                            <div className="import-shopify-to-wp__continue">
                                <Button isPrimary disabled={this.state.isInstallingPlugins}
                                        onClick={this.startInstall.bind(this)}>
                                    {__('Continue', 'import-shopify-to-wp')} &rarr;
                                </Button>

                                {this.state.isInstallingPlugins &&
                                <img src="/wp-admin/images/spinner-2x.gif" alt=""/>
                                }
                            </div>
                        </div>
                    </Row>}

                    {this.state.current_step === 'complete' &&
                    <Row>
                        <div className="import-shopify-to-wp__import-complete">
                            <Complete/>
                        </div>
                    </Row>}
                </div>
            </div>
        );
    }

    pluginIsAlreadyInstalled(pluginSlug: string) {
        return (this.state.ignoredPlugins.indexOf(pluginSlug) >= 0);
    }

    pluginClass(pluginSlug: string) {
        return `import-shopify-to-wp__plugin` +
            (this.state.plugins.indexOf(pluginSlug) >= 0 ? ' selected' : '') +
            (this.state.pluginsToBeInstalled.indexOf(pluginSlug) >= 0 ? ' installing' : '') +
            (this.state.installedPlugins.indexOf(pluginSlug) >= 0 ? ' installed' : '');
    }

    markPlugin(pluginSlug: string) {
        const plugins = [...this.state.plugins];
        const index = plugins.indexOf(pluginSlug);

        if (index > -1) {
            plugins.splice(index, 1);
        } else {
            plugins.push(pluginSlug);
        }

        this.setState({
            plugins
        });
    }

    startInstall() {
        this.setState({isInstallingPlugins: true});

        const plugins = [...this.state.plugins];

        if (plugins.length < 1) {
            return this.setState({
                current_step: 'complete',
                isImporting: false,
                isInstallingPlugins: false,
                pluginsToBeInstalled: []
            });
        }

        this.setState({
            pluginsToBeInstalled: [...plugins]
        });

        let left = plugins.length;

        for (let i = 0; i < plugins.length; i++) {
            axios.post(`${window.ajaxurl}?action=s2wp_install_plugin`, {
                nonce: window.shopify2wp.ajaxNonce || false,
                slug: plugins[i]
            })
                .then(response => {
                    // const {data} = response;

                    // console.log(response);
                })
                .catch(err => {
                    console.error(err);
                })
                .finally(() => {
                    const installedPlugins = [...this.state.installedPlugins];
                    const toBeInstalled = [...this.state.pluginsToBeInstalled];
                    const index = toBeInstalled.indexOf(plugins[i]);

                    if (index > -1) {
                        toBeInstalled.splice(index, 1);
                        installedPlugins.push(plugins[i])
                    }

                    this.setState({
                        pluginsToBeInstalled: toBeInstalled,
                        installedPlugins: installedPlugins
                    });

                    left = left - 1;

                    if (left === 0) {
                        axios.post(`${window.ajaxurl}?action=s2wp_activate_plugins`, {
                            nonce: window.shopify2wp.ajaxNonce || false,
                            slugs: this.state.installedPlugins,
                        })
                            .then(response => {
                                // const {data} = response;

                                // console.log(response);
                            })
                            .catch(err => {
                                console.error(err);
                            })
                            .finally(() => {
                                this.setState({
                                    current_step: 'complete',
                                    isImporting: false,
                                    isInstallingPlugins: false,
                                    pluginsToBeInstalled: []
                                });
                            });

                    }
                });
        }
    }
}

export default App;
