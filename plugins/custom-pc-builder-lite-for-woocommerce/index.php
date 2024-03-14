<?php
/**
 * Plugin Name: Custom PC Builder Lite for WooCommerce
 * Plugin URI: https://choplugins.com/product/custom-pc-builder-lite-for-woocommerce
 * Version: 1.0.1
 * Description: Custom PC Builder Lite for WooCommerce allows your customers to build full PCs on their own
 * Author: choplugins
 * Author URI: https://choplugins.com
 * Text Domain: nk-custom-pc-builder
 * Domain Path: /languages
 * WC requires at least: 3.2
 * WC tested up to: 3.6.5
 */
defined('ABSPATH') or die('Keep Quit');
require __DIR__ . '/vendor/autoload.php';
use \Josantonius\Session\Session;
if(!class_exists('custom_pc_builder_lite_for_woocommerce')) {
    class custom_pc_builder_lite_for_woocommerce{
        protected static $_instance = null;
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        public function __construct() {
            $this->hooks();
            $this->includes();
            $this->language();
        }
        public function hooks()
        {
            add_action('admin_menu',array($this,'register_admin_menu'));
            if(!is_admin()) add_shortcode( 'custom_pc_builder', array($this,'custom_pc_builder_shortcode' ));
            if ( $this->is_wc_active() ) {
                add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
                add_action('wp_enqueue_scripts',array($this,'wp_enqueue_scripts'),99);
                add_action('wp_footer',array($this,'enqueue_inline_script'));
                add_filter( 'plugin_action_links_'.plugin_basename(__FILE__), array( $this, 'plugin_action_links' ) );
            }
        }
        public function includes()
        {
            require_once $this->include_path('admin-ajax-handle.php');
            require_once $this->include_path('frontend-ajax-handle.php');
            require_once $this->include_path('helper.php');

        }
        public function plugin_action_links($links){
            $mylinks = array(
                '<a href="' . admin_url( 'options-general.php?page=nk-custom-pc-builder' ) . '">'.__('Settings','nk-custom-pc-builder').'</a>',
            );
            return array_merge( $mylinks,$links );
        }
        public function admin_enqueue_scripts(){
            wp_enqueue_style( 'custom_pc_builder_lite', $this->assets_uri( "/css/admin.css" ), array(), false );
            wp_enqueue_script( 'custom_pc_builder_lite', $this->assets_uri( "/js/admin.min.js" ), array('jquery','jquery-blockui'), false,true );
        }
        public function getProductCategories(){
            $terms = get_terms(array(
                'taxonomy' => 'product_cat',
                'hide_empty' => true
            ));
            return $terms;
        }

        public function register_admin_menu(){
            add_submenu_page('options-general.php',
                esc_html__('Custom PC Builder', 'nk-custom-pc-builder'),
                esc_html__('Custom PC Builder', 'nk-custom-pc-builder') ,
                'manage_woocommerce',
                'nk-custom-pc-builder',
                array($this,'custom_pc_builder_show_page'));
        }
        public function custom_pc_builder_show_page() {
            if($this->is_wc_active()):
                ?>
                <div class="nkcpcb-container">
                    <div class="pre-saving"></div>
                    <div class="nkcpcb-left">
                        <div class="nkcpcb-inner">
                                <h1><?= __('Settings', 'nk-custom-pc-builder') ?></h1>
                                <p><?= __('Select category items to display Custom PC Builder Frontend Page', 'nk-custom-pc-builder') ?></p>
                                <form method="post" class="nkcpcb-setting-form">
                                    <div class="nkcpcb-list-title">
                                        <div class="form-inline">
                                            <?= __('Row', 'nk-custom-pc-builder') ?>
                                        </div>
                                        <div class="form-inline">
                                            <?= __('Category', 'nk-custom-pc-builder') ?>
                                        </div>
                                        <div class="form-inline">
                                            <?= __('Title', 'nk-custom-pc-builder') ?>
                                        </div>
                                        <div class="form-inline">
                                            <?= __('Action', 'nk-custom-pc-builder') ?>
                                        </div>
                                    </div>
                                    <?php $setting = unserialize(get_option('nk_custom_pc_builder'));
                                    if (!empty($setting)): ?>
                                        <div class="nkcpcb-list">
                                            <ul>
                                                <?php foreach ($setting as $key => $item): ?>
                                                    <li>
                                                        <div class="form-inline">
                                                            <span><?= ++$key ?></span>
                                                        </div>
                                                        <div class="form-inline">
                                                            <select class="cat-select form-group" name="categories[]"
                                                                    required>
                                                                <option value=""
                                                                        selected><?= __('Select Category', 'nk-custom-pc-builder') ?></option>
                                                                <?php $cats = $this->getProductCategories();
                                                                if (!empty($cats)): foreach ($cats as $cat): ?>
                                                                    <option value="<?= $cat->term_id ?>" <?php selected($cat->term_id, $item['id']) ?>><?= $cat->name ?></option>
                                                                <?php endforeach; endif; ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-inline">
                                                            <input type="text" class="form-group" name="row_title[]"
                                                                   value="<?= $item['title'] ?>"
                                                                   placeholder="<?= __('Type title', 'nk-custom-pc-builder') ?>"
                                                                   required>
                                                        </div>
                                                        <div class="form-inline">
                                                            <button type="button" class="btn button-danger remove-item">
                                                                <svg width="24" height="24"
                                                                     xmlns="http://www.w3.org/2000/svg"
                                                                     viewBox="-2 -2 24 24" role="img" aria-hidden="true"
                                                                     focusable="false">
                                                                    <path d="M14.95 6.46L11.41 10l3.54 3.54-1.41 1.41L10 11.42l-3.53 3.53-1.42-1.42L8.58 10 5.05 6.47l1.42-1.42L10 8.58l3.54-3.53z"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>

                                        </div>
                                    <?php else: ?>
                                        <div class="nkcpcb-list">
                                            <ul>
                                                <li>
                                                    <div class="form-inline">
                                                        <span>1</span>
                                                    </div>
                                                    <div class="form-inline">
                                                        <select class="cat-select form-group" name="categories[]"
                                                                required>
                                                            <option value=""
                                                                    selected><?= __('Select Category', 'nk-custom-pc-builder') ?></option>
                                                            <?php $cats = $this->getProductCategories();
                                                            if (!empty($cats)): foreach ($cats as $cat): ?>
                                                                <option value="<?= $cat->term_id ?>"><?= $cat->name ?></option>
                                                            <?php endforeach; endif; ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-inline">
                                                        <input type="text" class="form-group" name="row_title[]"
                                                               placeholder="<?= __('Type title', 'nk-custom-pc-builder') ?>"
                                                               required>
                                                    </div>
                                                    <div class="form-inline">
                                                        <button type="button" class="btn button-danger remove-item">
                                                            <svg width="24" height="24"
                                                                 xmlns="http://www.w3.org/2000/svg"
                                                                 viewBox="-2 -2 24 24" role="img" aria-hidden="true"
                                                                 focusable="false">
                                                                <path d="M14.95 6.46L11.41 10l3.54 3.54-1.41 1.41L10 11.42l-3.53 3.53-1.42-1.42L8.58 10 5.05 6.47l1.42-1.42L10 8.58l3.54-3.53z"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </li>
                                            </ul>

                                        </div>
                                    <?php endif ?>
                                    <div class="nkcpcb-add">
                                        <button type="button" class="btn" id="addNewItem">
                                            <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg"
                                                 viewBox="-2 -2 24 24" role="img" aria-hidden="true" focusable="false">
                                                <path d="M10 1c-5 0-9 4-9 9s4 9 9 9 9-4 9-9-4-9-9-9zm0 16c-3.9 0-7-3.1-7-7s3.1-7 7-7 7 3.1 7 7-3.1 7-7 7zm1-11H9v3H6v2h3v3h2v-3h3V9h-3V6zM10 1c-5 0-9 4-9 9s4 9 9 9 9-4 9-9-4-9-9-9zm0 16c-3.9 0-7-3.1-7-7s3.1-7 7-7 7 3.1 7 7-3.1 7-7 7zm1-11H9v3H6v2h3v3h2v-3h3V9h-3V6z"></path>
                                            </svg>
                                            <?= __('Add new row', 'nk-custom-pc-builder') ?>
                                        </button>
                                    </div>
                                    <div class="nkcpcb-list-footer">
                                        <button type="submit"
                                                class="btn button-primary" id="nkcpcb-update"><?php esc_attr_e('Update'); ?></button>
                                    </div>

                                </form>
                            </div>
                    </div>
                    <div class="nkcpcb-right">
                        <div class="nkcpcb-inner">
                            <h2 style="margin-top: 30px"><?= __('Documentation', 'nk-custom-pc-builder') ?></h2>
                            <p><?= __('Please create a new page first. Example: Custom PC Builder Page', 'nk-custom-pc-builder') ?> </p>
                            <p>
                                <code><?= home_url() ?>/custom-pc-builder-page</code>
                            </p>
                            <span class="shortcode">
                            <p><?= __('Copy this shortcode and paste it into your post, page, or text widget content', 'nk-custom-pc-builder') ?></p>
                            <input class="wp-ui-highlight large-text code" style="color: #fff;border: none;"
                                   value="[custom_pc_builder]"></span>
                        </div>
                        <div class="banner-pro">
                            <a target="_blank" href="https://choplugins.com/en/product/nk-custom-pc-builder-for-woocommerce">
                                <img src="<?= $this->assets_uri('/images/custom-pc-builder-pro.png') ?>" alt="custom-pc-builder-pro" class="" width="100%">
                            </a>
                        </div>
                    </div>
                    <div class="nkcpcb-setting">
                        <div class="pre-saving"></div>
                    </div>

                </div>
            <?php endif;
        }
        public function is_wc_active() {
            return class_exists( 'WooCommerce' );
        }
        public function is_required_wc_version() {
            return version_compare( WC()->version, '3.2', '>' );
        }
        public function wc_version_requirement_notice() {
            if ( $this->is_wc_active() && ! $this->is_required_wc_version() ) {
                $class   = 'notice notice-error';
                $message = sprintf( esc_html__( "Currently, you are using older version of WooCommerce. It's recommended to use latest version of WooCommerce to work with %s.", 'nk-custom-pc-builder' ), esc_html__( 'Custom PC Builder for WooCommerce', 'nk-custom-pc-builder' ) );
                printf( '<div class="%1$s"><p><strong>%2$s</strong></p></div>', $class, $message );
            }
        }
        public function language() {
            load_plugin_textdomain( 'nk-custom-pc-builder', false, trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) . 'languages' );
        }
        public function assets_uri($file){
            $file = ltrim( $file, '/' );
            return trailingslashit( plugin_dir_url( __FILE__ ) . 'assets' ).$file;
        }
        public function include_path( $file ) {
            $file = ltrim( $file, '/' );

            return trailingslashit( plugin_dir_path( __FILE__ ) . 'inc' ) . $file;
        }
        /**
         * Frontend
         */
        public function custom_pc_builder_shortcode(){
            $categories = unserialize(get_option('nk_custom_pc_builder'));
            $pc_builder = Session::get('pc_builder');
           ;
            ob_start();
            ?>
            <div class="nk_custom_pc_builder_page">
                <input type="hidden" id="currencySymbol" value="<?= esc_attr(get_woocommerce_currency_symbol());?>">
                <input type="hidden" id="currencySymbolPos" value="<?= esc_attr(get_option('woocommerce_currency_pos'));?>">
                <div class="pre-loading"></div>
                <div class="nk_custom_pc_builder_page_header flex-row">
                    <div class="left header-button">
                        <button type="button" class="btn btn-primary" id="refresh-pc-builder"><i class="nk-icon-refresh"></i> <?= esc_html(__('Refresh','nk-custom-pc-builder'));?></button>
                    </div>
                    <div class="right">
                        <?= esc_html(__('Total','nk-custom-pc-builder')); ?>: <span class="cost estimated-costs"><?= wc_price(Session::get('grand_total') ?: 0)?></span>
                    </div>
                </div>
                <div class="nk_custom_pc_builder_page_body">
                    <?php if(!empty($categories)): ?>
                        <div class="list-item">
                            <?php foreach ($categories as $key => $category):?>
                                <div class="item">
                                    <div class="left">
                                        <?= esc_html(++$key .'. '.$category['title'])?>
                                    </div>
                                    <div class="right" id="item-cat-<?= esc_attr($category['id'])?>">
                                        <?php $item = isset($pc_builder[$category['id']]['id']) ? $pc_builder[$category['id']]['id'] : 0;
                                        if($item > 0): ?>
                                            <div class="inner-item">
                                                <?php $wc_product = wc_get_product($item);?>
                                                <a href="<?= get_permalink($item) ?>" class="">
                                                    <img src="<?= get_the_post_thumbnail_url($item, 'medium') ?>"
                                                         alt="<?= get_the_title($item) ?>">
                                                </a>
                                                <div class="info">
                                                    <a href="<?= get_permalink($item) ?>" class=""><?= get_the_title($item) ?></a>
                                                </div>
                                                <div class="price-wrap">
                                                    <div class="p-price" data-price="<?= esc_attr($wc_product->get_price())?>"><?= wc_price($wc_product->get_price()) ?></div>
                                                    <div class="p-quantity">
                                                        <input type="number" data-value="<?= esc_attr($category['id']) ?>" value="<?=  esc_attr($pc_builder[$category['id']]['quantity']) ?>" class="input_quantity" min="1">
                                                    </div>
                                                    <i> = </i>
                                                    <div class="p-total" data-price="<?= esc_attr($wc_product->get_price())?>"><?= wc_price($wc_product->get_price()*(float)$pc_builder[$category['id']]['quantity']) ?>
                                                    </div>
                                                </div>
                                                <div class="action">
                                                    <button data-toggle="nk-popup" class="btn btn-success" data-id="<?= esc_attr($category['id'])?>"><i class="nk-icon-edit"></i></button>
                                                    <button class="btn btn-danger remove" data-cat_id="<?= esc_attr($category['id'])?>" data-product_id="<?= esc_attr($item)?>"><i class="nk-icon-delete"></i></button>
                                                </div>


                                            </div>
                                        <?php else:?>
                                            <button data-toggle="nk-popup" class="btn btn-primary" data-id="<?= esc_attr($category['id'])?>"><i class="nk-icon-plus"></i> <?= esc_html(__('Select','nk-custom-pc-builder'))?> <?= esc_attr($category['title'])?></button>
                                        <?php endif;?>
                                    </div>
                                </div>
                            <?php endforeach;?>

                        </div>
                    <?php endif; ?>
                </div>
                <div class="clear"></div>
                <div class="nk_custom_pc_builder_page_footer flex-row">
                    <div class="left">
                        <button type="button" id="addToCart" class="btn btn-primary add-to-cart-button"><i class="nk-icon-shopping-cart"></i> <?= esc_html(__('Add to cart','nk-custom-pc-builder'));?></button>
                    </div>
                    <div class="right">
                        <?= esc_html(__('Total','nk-custom-pc-builder'))?>: <span class="cost estimated-costs"><?= wc_price(wp_cache_get('grand_total') ?: 0)?></span>
                    </div>
                </div>
                <div class="nk-popup fade" id="nk_custom_pc_builder_popup">
                    <div class="nk-popup-overlay" onclick="closePopup()"></div>
                    <div class="nk-popup-inner">
                        <div class="nk-popup-header bg-primary">
                            <div class="left-col">
                                <span class="title"><?= esc_html(__('Find Components','nk-custom-pc-builder'))?></span>
                            </div>
                            <div class="right-col">
                                <div class="product-search-form">
                                    <div class="search-form">
                                        <input type="text" name="search" placeholder="<?= esc_attr(__('Type something...','nk-custom-pc-builder'))?>">
                                        <i class="icon-search"></i>
                                    </div>
                                </div>
                                <div class="hide-on-desktop"><span class="filter-toggle"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 477.867 477.867" style="enable-background:new 0 0 477.867 477.867;" xml:space="preserve"><path d="M460.8,221.867H185.31c-9.255-36.364-46.237-58.34-82.602-49.085c-24.116,6.138-42.947,24.969-49.085,49.085H17.067    C7.641,221.867,0,229.508,0,238.934S7.641,256,17.067,256h36.557c9.255,36.364,46.237,58.34,82.602,49.085    c24.116-6.138,42.947-24.969,49.085-49.085H460.8c9.426,0,17.067-7.641,17.067-17.067S470.226,221.867,460.8,221.867z     M119.467,273.067c-18.851,0-34.133-15.282-34.133-34.133c0-18.851,15.282-34.133,34.133-34.133s34.133,15.282,34.133,34.133    C153.6,257.785,138.318,273.067,119.467,273.067z"/><path d="M460.8,51.2h-53.623c-9.255-36.364-46.237-58.34-82.602-49.085C300.459,8.253,281.628,27.084,275.49,51.2H17.067    C7.641,51.2,0,58.841,0,68.267s7.641,17.067,17.067,17.067H275.49c9.255,36.364,46.237,58.34,82.602,49.085    c24.116-6.138,42.947-24.969,49.085-49.085H460.8c9.426,0,17.067-7.641,17.067-17.067S470.226,51.2,460.8,51.2z M341.334,102.4    c-18.851,0-34.133-15.282-34.133-34.133s15.282-34.133,34.133-34.133s34.133,15.282,34.133,34.133S360.185,102.4,341.334,102.4z"/><path d="M460.8,392.534h-87.757c-9.255-36.364-46.237-58.34-82.602-49.085c-24.116,6.138-42.947,24.969-49.085,49.085H17.067    C7.641,392.534,0,400.175,0,409.6s7.641,17.067,17.067,17.067h224.29c9.255,36.364,46.237,58.34,82.602,49.085    c24.116-6.138,42.947-24.969,49.085-49.085H460.8c9.426,0,17.067-7.641,17.067-17.067S470.226,392.534,460.8,392.534z     M307.2,443.734c-18.851,0-34.133-15.282-34.133-34.133s15.282-34.133,34.133-34.133c18.851,0,34.133,15.282,34.133,34.133    S326.052,443.734,307.2,443.734z"/></svg></span> </div>
                                <div class="button-close" onclick="closePopup()">
                                    <i class="nk-icon-close"></i>
                                </div>
                            </div>
                        </div>
                        <div class="nk-popup-body" id="nk-popup-body-result">
                        </div>
                        <div class="nk-popup-footer">
                        </div>
                    </div>
                </div>

            </div>
            <?php
            $content = ob_get_contents();
            ob_clean();
            ob_end_flush();
            return $content;
        }
        public function wp_enqueue_scripts(){
            if(is_page(get_option('nkcpcb_share_page',0))) {
                wp_enqueue_style('nk-custom-pc-builder-lite', $this->assets_uri("/css/frontend.css"), array(), false);
                wp_enqueue_script('mobile-detect', $this->assets_uri("/vendor/mobile-detect/mobile-detect.min.js"), array(), false, true);
                wp_enqueue_script('nk-custom-pc-builder-lite', $this->assets_uri("/js/frontend.min.js"), array('jquery','jquery-blockui'), false, true);
            }
        }
        public function enqueue_inline_script(){
            if(is_page(get_option('nkcpcb_share_page',0))) { ?>
                <script type="text/javascript">
                    var url = '<?= esc_url(admin_url("admin-ajax.php"))?>';
                    var current_url = '<?= get_permalink(get_queried_object()) ?>';
                    var cart_url = '<?= wc_get_cart_url()?>';
                    function closePopup() {
                        jQuery('.nk-popup').removeClass('show');
                    }
                    jQuery(document).ready(function () {
                        PCBulderLite.onInit(url);
                        PCBulderLite.onRefresh(url,current_url);
                        PCBulderLite.onAddtoCart(url,cart_url);
                    });
                </script>
            <?php }
        }
    }
    function custom_pc_builder_lite_for_woocommerce() {
        return custom_pc_builder_lite_for_woocommerce::instance();
    }
    function custom_pc_builder_lite_start_session()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            Session::init(3600);
        }
    }
    if (session_status() !== PHP_SESSION_DISABLED && (!defined('WP_CLI') || false === WP_CLI)) {
        add_action('plugins_loaded', 'custom_pc_builder_lite_start_session', 10, 0);
        if (!defined('DOING_CRON') || false === DOING_CRON) {
            add_action('plugins_loaded', 'custom_pc_builder_lite_for_woocommerce', 25);
        }
    }
}

