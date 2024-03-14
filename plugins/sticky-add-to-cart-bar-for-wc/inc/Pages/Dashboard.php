<?php 
/**
 * @package  WooCart
 */
namespace WscInc\Pages;

use WscInc\Api\SettingsApi;
use WscInc\Base\BaseController;
use WscInc\Api\Callbacks\FormCallbacks;
use WscInc\Api\Callbacks\AdminCallbacks;

class Dashboard extends BaseController {
	public $settings;

	public $callbacks;

	public $callbacks_form;

	public $pages = array();

	public function register() {

		$this->settings = new SettingsApi();

		$this->callbacks = new AdminCallbacks();

		$this->callbacks_form = new FormCallbacks();

		$this->setPages();

		$this->setSettings();

        $this->setSections();
        
        $this->setFields();

		$this->settings->addPages( $this->pages )->withSubPage( 'Settings' )->register();
	}

    // Set wordpress admin section menu page
	public function setPages() {
		$this->pages = array(
			array(
				'page_title' => 'Sticky Add To Cart', 
				'menu_title' => 'Sticky Add To Cart', 
				'capability' => 'manage_options', 
				'menu_slug' => 'woo_cart', 
				'callback' => array( $this->callbacks, 'adminDashboard' ), 
				'icon_url' => 'dashicons-cart', 
				'position' => 56
			)
		);
	}

	public function setSettings(){
        $args = array(
            array(
                'option_group' => 'woo_sticky_cart_setting',
                'option_name' => 'woo_sticky_cart',
                'callback' => array( $this->callbacks_form , 'formSanitize' )
            ),
            array(
                'option_group' => 'woo_sticky_cart_setting',
                'option_name' => 'woo_sticky_cart',
                'callback' => array( $this->callbacks_form , 'formSanitize' )
            )
        );
        $this->settings->setSettings( $args );
    }

    public function setSections(){
        $args = array(
            array(
                'id' => 'settings',
                'title' => 'Settings',
                'callback' => array( $this->callbacks_form , 'formSectionManager' ),
                'page' => 'woo_cart'
            ),
            array(
                'id' => 'appearance',
                'title' => 'Appearance',
                'callback' => array( $this->callbacks_form , 'formSectionManager' ),
                'page' => 'woo_cart'
            )
        );


        $this->settings->setSections( $args );
    }

    // Fields to store the custom data
    public function setFields(){

        $args = array(
            array(
                'id' => 'enable',
                'title' => 'Enable<div class="tooltip">?<div class="tipbox"><p>Enable or Disable Sticky Add To Cart.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'checkboxField' ),
                'page' => 'woo_cart',
                'section' => 'settings',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'enable',
                ),
            ),
            array(
                'id' => 'desktop',
                'title' => 'Desktop<div class="tooltip">?<div class="tipbox"><p>Display on Desktop.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'checkboxField' ),
                'page' => 'woo_cart',
                'section' => 'settings',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'desktop',
                ),
            ),
            array(
                'id' => 'mobile',
                'title' => 'Mobile<div class="tooltip">?<div class="tipbox"><p>Display on Mobile.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'checkboxField' ),
                'page' => 'woo_cart',
                'section' => 'settings',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'mobile',
                ),
            ),
            array(
                'id' => 'scroll',
                'title' => 'Show only after scroll<div class="tooltip">?<div class="tipbox"><p>Show after user scrolls down the product page.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'checkboxField' ),
                'page' => 'woo_cart',
                'section' => 'settings',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'scroll',
                ),
            ),
            array(
                'id' => 'scroll_height',
                'title' => 'Show Bar after scroll pixels<div class="tooltip">?<div class="tipbox"><p>Show Bar after user scroll given pixels on the page.<br>Empty will be 100px.<br>Only work when "Show only after scroll" option is enabled.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'textField' ),
                'page' => 'woo_cart',
                'section' => 'settings',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'scroll_height',
                    'placeholder' => 'without &quot;px&quot;',
                ),
            ),
            array(
                'id' => 'position',
                'title' => 'Position of Sticky Bar<div class="tooltip">?<div class="tipbox"><p>Select Position of sticky add to cart. Default is Top.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'radioButton' ),
                'page' => 'woo_cart',
                'section' => 'settings',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'position',
                    'options' => array(
                        'Top' => 'top',
                        'Bottom' => 'bottom',
                    ),
                ),
            ),
            array(
                'id' => 'height',
                'title' => 'Height<div class="tooltip">?<div class="tipbox"><p>Height of Sticky Bar. Empty will be auto.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'textField' ),
                'page' => 'woo_cart',
                'section' => 'settings',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'height',
                    'placeholder' => 'without &quot;px&quot;',
                ),
            ),
            array(
                'id' => 'dis_products',
                'title' => 'Disable on products <a href="https://addonsplus.com/downloads/woocommerce-sticky-add-to-cart-bar-pro/" class="get-pro" target="_blank" >Get Pro Feature</a><div class="tooltip">?<div class="tipbox"><p>Disable on selected products.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'textField' ),
                'page' => 'woo_cart',
                'section' => 'settings',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => '',
                    'placeholder' => 'Separate With , (comma)',
                ),
            ),
            array(
                'id' => 'ajaxcart',
                'title' => 'Ajax Cart <a href="https://addonsplus.com/downloads/woocommerce-sticky-add-to-cart-bar-pro/" class="get-pro" target="_blank" >Get Pro Feature</a><div class="tooltip">?<div class="tipbox"><p>Ajax add to cart.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'checkboxField' ),
                'page' => 'woo_cart',
                'section' => 'settings',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'ajaxcart',
                ),
            ),
            array(
                'id' => 'redirect',
                'title' => 'Redirect after add to cart with ajax <a href="https://addonsplus.com/downloads/woocommerce-sticky-add-to-cart-bar-pro/" class="get-pro" target="_blank" >Get Pro Feature</a><div class="tooltip">?<div class="tipbox"><p>Select the page you want to redirect after add to cart. Default is none. Only use when WooCommerce Sticky add to cart bar ajax feature is eanbled.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'radioButton' ),
                'page' => 'woo_cart',
                'section' => 'settings',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'redirect',
                    'options' => array(
                        'Shop Page' =>'shop',
                        'Cart Page' => 'cart',
                        'Checkout Page' => 'checkout',
                        'None' => 'none',
                    ),
                ),
            ),
            array(
                'id' => 'show_image',
                'title' => 'Show image of product<div class="tooltip">?<div class="tipbox"><p>Show image of product on bar.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'checkboxField' ),
                'page' => 'woo_cart',
                'section' => 'settings',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'show_image',
                ),
            ),
            array(
                'id' => 'show_bar_variable',
                'title' => 'Enable on variable product <a href="https://addonsplus.com/downloads/woocommerce-sticky-add-to-cart-bar-pro/" class="get-pro" target="_blank" >Get Pro Feature</a><div class="tooltip">?<div class="tipbox"><p>Enable or Disable bar on variable product.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'checkboxField' ),
                'page' => 'woo_cart',
                'section' => 'settings',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'show_bar_variable',
                ),
            ),
            array(
                'id' => 'show_range_price_variable',
                'title' => 'Show range of price on variable product<div class="tooltip">?<div class="tipbox"><p>Show min-max price on bar. If disabled it will show minimum price.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'checkboxField' ),
                'page' => 'woo_cart',
                'section' => 'settings',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'show_range_price_variable',
                ),
            ),
            array(
                'id' => 'hide_bar_if_outofstock',
                'title' => 'Hide bar if product is out of stock<div class="tooltip">?<div class="tipbox"><p>The product bar will not show if the product is out of stock.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'checkboxField' ),
                'page' => 'woo_cart',
                'section' => 'settings',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'hide_bar_if_outofstock',
                ),
            ),

            array(
                'id' => 'add_cart_text',
                'title' => 'Text of cart button<div class="tooltip">?<div class="tipbox"><p>Text that is on simple product add to cart button. Leave blank for default "Add To Cart".</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'textField' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'add_cart_text',
                    'placeholder' => 'Text of cart button',
                ),
            ),
            array(
                'id' => 'star',
                'title' => 'Star Rating<div class="tooltip">?<div class="tipbox"><p>Show star rating on bar.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'checkboxField' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'star',
                ),
            ),
            array(
                'id' => 'review',
                'title' => 'Review Count<div class="tooltip">?<div class="tipbox"><p>Show review count on bar.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'checkboxField' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'review',
                ),
            ),
            array(
                'id' => 'review_count_color',
                'title' => 'Review count text color<div class="tooltip">?<div class="tipbox"><p>Color of total review count.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'colorPicker' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'review_count_color',
                ),
            ),
            array(
                'id' => 'stock',
                'title' => 'Stock Count<div class="tooltip">?<div class="tipbox"><p>Show Available stock count on bar.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'checkboxField' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'stock',
                ),
            ),
            array(
                'id' => 'stock_color',
                'title' => 'Stock text color<div class="tooltip">?<div class="tipbox"><p>Color of in stock items.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'colorPicker' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'stock_color',
                ),
            ),
            array(
                'id' => 'bg_color',
                'title' => 'Background Color<div class="tooltip">?<div class="tipbox"><p>Background Color of bar</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'colorPicker' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'bg_color',
                ),
            ),
            array(
                'id' => 'bg_image',
                'title' => 'Background Image <a href="https://addonsplus.com/downloads/woocommerce-sticky-add-to-cart-bar-pro/" class="get-pro" target="_blank" >Get Pro Feature</a><div class="tooltip">?<div class="tipbox"><p>Background image of bar.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'imageSelect' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'bg_image',
                ),
            ),
            array(
                'id' => 'bg_image_size',
                'title' => 'Size of background image <a href="https://addonsplus.com/downloads/woocommerce-sticky-add-to-cart-bar-pro/" class="get-pro" target="_blank" >Get Pro Feature</a><div class="tooltip">?<div class="tipbox"><p>Select the size of background image.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'radioButton' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'bg_image_size',
                    'options' => array(
                        'Contain' => 'contain',
                        'Cover' => 'cover',
                        'Default' => 'inherit',
                    ),
                ),
            ),
            array(
                'id' => 'bg_image_position',
                'title' => 'Position of background image <a href="https://addonsplus.com/downloads/woocommerce-sticky-add-to-cart-bar-pro/" class="get-pro" target="_blank" >Get Pro Feature</a><div class="tooltip">?<div class="tipbox"><p>Select the position of background image.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'radioButton' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'bg_image_position',
                    'options' => array(
                        'Center' => 'center',
                        // 'Bottom' => 'bottom',
                        'Left' => 'left',
                        'Right' => 'right',
                        // 'Top' => 'top',
                    ),
                ),
            ),
            array(
                'id' => 'bg_repeat',
                'title' => 'Repeat Background <a href="https://addonsplus.com/downloads/woocommerce-sticky-add-to-cart-bar-pro/" class="get-pro" target="_blank" >Get Pro Feature</a><div class="tooltip">?<div class="tipbox"><p>Repeat background or not.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'checkboxField' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'bg_repeat',
                ),
            ),
            array(
                'id' => 'star_bg_color',
                'title' => 'Star background Color <a href="https://addonsplus.com/downloads/woocommerce-sticky-add-to-cart-bar-pro/" class="get-pro" target="_blank" >Get Pro Feature</a><div class="tooltip">?<div class="tipbox"><p>Background Color of star.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'colorPicker' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'star_bg_color',
                ),
            ),
            array(
                'id' => 'star_color',
                'title' => 'Star Color<div class="tooltip">?<div class="tipbox"><p>Color of star.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'colorPicker' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'star_color',
                ),
            ),
            array(
                'id' => 'border_color',
                'title' => 'Border Color<div class="tooltip">?<div class="tipbox"><p>Border Color of bar.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'colorPicker' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'border_color',
                ),
            ),
            array(
                'id' => 'border_shadow',
                'title' => 'Shadow of border<div class="tooltip">?<div class="tipbox"><p>Enable or disable shadow of border.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'checkboxField' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'border_shadow',
                ),
            ),
            array(
                'id' => 'cart_btn_bg',
                'title' => 'Cart Button background color<div class="tooltip">?<div class="tipbox"><p>Background Color of cart button.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'colorPicker' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'cart_btn_bg',
                ),
            ),
            array(
                'id' => 'cart_btn_bg_hover',
                'title' => 'Cart Button background hover color<div class="tooltip">?<div class="tipbox"><p>Cart button background hover color.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'colorPicker' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'cart_btn_bg_hover',
                ),
            ),
            array(
                'id' => 'btn_text_color',
                'title' => 'Text Color of cart button<div class="tooltip">?<div class="tipbox"><p>Text Color of cart button.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'colorPicker' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'btn_text_color',
                ),
            ),
            array(
                'id' => 'btn_text_color_hover',
                'title' => 'Text Color of cart button hover<div class="tooltip">?<div class="tipbox"><p>Text Color of cart button on hover event.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'colorPicker' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'btn_text_color_hover',
                ),
            ),
            array(
                'id' => 'out_stock_color',
                'title' => 'Out of stock color<div class="tooltip">?<div class="tipbox"><p>Color when product is out of stock.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'colorPicker' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'out_stock_color',
                ),
            ),
            array(
                'id' => 'price_text_color',
                'title' => 'Price text color<div class="tooltip">?<div class="tipbox"><p>Color of price text.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'colorPicker' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'price_text_color',
                ),
            ),
            array(
                'id' => 'price_text_bg_color',
                'title' => 'Price text background color <a href="https://addonsplus.com/downloads/woocommerce-sticky-add-to-cart-bar-pro/" class="get-pro" target="_blank" >Get Pro Feature</a><div class="tooltip">?<div class="tipbox"><p>Background Color of price text.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'colorPicker' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'price_text_bg_color',
                ),
            ),
            array(
                'id' => 'sale_badge',
                'title' => 'Sale/offer badge <a href="https://addonsplus.com/downloads/woocommerce-sticky-add-to-cart-bar-pro/" class="get-pro" target="_blank" >Get Pro Feature</a><div class="tooltip">?<div class="tipbox"><p>Enable or disable sale or offer badge.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'checkboxField' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'sale_badge',
                ),
            ),
            array(
                'id' => 'sale_badge_text',
                'title' => 'Sale/offer badge Text <a href="https://addonsplus.com/downloads/woocommerce-sticky-add-to-cart-bar-pro/" class="get-pro" target="_blank" >Get Pro Feature</a><div class="tooltip">?<div class="tipbox"><p>Text for sale or offer badge.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'textField' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'sale_badge_text',
                    'placeholder' => 'Sale',
                ),
            ),
            array(
                'id' => 'sale_badge_text_color',
                'title' => 'Sale/offer badge Text color <a href="https://addonsplus.com/downloads/woocommerce-sticky-add-to-cart-bar-pro/" class="get-pro" target="_blank" >Get Pro Feature</a><div class="tooltip">?<div class="tipbox"><p>Text color for sale or offer badge.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'colorPicker' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'sale_badge_text_color',
                ),
            ),
            array(
                'id' => 'sale_badge_bg_color',
                'title' => 'Sale/offer badge Background color <a href="https://addonsplus.com/downloads/woocommerce-sticky-add-to-cart-bar-pro/" class="get-pro" target="_blank" >Get Pro Feature</a><div class="tooltip">?<div class="tipbox"><p>Background color for sale or offer badge.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'colorPicker' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'sale_badge_bg_color',
                ),
            ),
            array(
                'id' => 'product_text_color',
                'title' => 'Product name color<div class="tooltip">?<div class="tipbox"><p>Color for the product name.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'colorPicker' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'product_text_color',
                ),
            ),
            array(
                'id' => 'animate_btn',
                'title' => 'Animate cart button <a href="https://addonsplus.com/downloads/woocommerce-sticky-add-to-cart-bar-pro/" class="get-pro" target="_blank" >Get Pro Feature</a><div class="tooltip">?<div class="tipbox"><p>Select animation style of button to get users attention.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'radioButton' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'animate_btn',
                    'options' => array(
                        'Wiggle Animation' => 'wiggle',
                        'Horizontal Animation' => 'horizontal',
                        'Drop' => 'drop',
                        'None' => 'no',
                    ),
                ),
            ),
            array(
                'id' => 'image_shape',
                'title' => 'Shape of product image<div class="tooltip">?<div class="tipbox"><p>Select shape of image on sticky bar.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'radioButton' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'image_shape',
                    'options' => array(
                        'Round' => '50%',
                        'Square' => '0%',
                    ),
                ),
            ),
            array(
                'id' => 'price_bg_shape',
                'title' => 'Background shape of product price <a href="https://addonsplus.com/downloads/woocommerce-sticky-add-to-cart-bar-pro/" class="get-pro" target="_blank" >Get Pro Feature</a><div class="tooltip">?<div class="tipbox"><p>Select background shape of price on sticky bar.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'radioButton' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'price_bg_shape',
                    'options' => array(
                        'Round' => '30px',
                        'Square' => '0px',
                    ),
                ),
            ),
            array(
                'id' => 'cart_icon_image',
                'title' => 'Cart Icon Image <a href="https://addonsplus.com/downloads/woocommerce-sticky-add-to-cart-bar-pro/" class="get-pro" target="_blank" >Get Pro Feature</a><div class="tooltip">?<div class="tipbox"><p>Image of add to cart icon. Empty will be no image.</p><i></i></div></div>',
                'callback' => array( $this->callbacks_form , 'imageSelect' ),
                'page' => 'woo_cart',
                'section' => 'appearance',
                'args' => array(
                    'option_name' => 'woo_sticky_cart',
                    'label_for' => 'cart_icon_image',
                ),
            ),

        );
        $this->settings->setFields( $args );
    }

    public function resetFields(){
        $default = array(
            "enable"                    =>"1",
            "desktop"                   =>"1",
            "mobile"                    =>"1",
            //"scroll"                    =>"",
            "position"                  =>"bottom",
            "height"                    =>"",
            "dis_products"              =>"",
            "ajaxcart"                  =>"1",
            "redirect"                  =>"none",
            "show_image"                =>"1",
            "show_bar_variable"         =>"1",
            "show_range_price_variable" =>"1",
            
            "add_cart_text"             =>"",
            "star"                      =>"1",
            "review"                    =>"1",
            "stock"                     =>"1",
            "stock_color"               =>"#000000",
            "review_count_color"        =>"#000000",
            "bg_color"                  =>"#ffffff", 
            "bg_image"                  =>"",
            "bg_image_size"             =>"contain",
            "bg_image_position"         =>"center",
            //"bg_repeat"                 =>"",
            "star_bg_color"             =>"", 
            "star_color"                =>"#000000",
            "border_color"              =>"",
            "border_shadow"             =>"1",
            "cart_btn_bg"               =>"#000000",
            "cart_btn_bg_hover"         =>"#444444",
            "btn_text_color"            =>"#ffffff",
            "btn_text_color_hover"      =>"#ffffff",
            "out_stock_color"           =>"#dd3333",
            "price_text_color"          =>"#ffffff",
            "price_text_bg_color"       =>"#000000",
            "sale_badge"                =>"1",
            "sale_badge_text"           =>"",
            "sale_badge_text_color"     =>"#ffffff",
            "sale_badge_bg_color"       =>"#000000",
            "product_text_color"        =>"#000000",
            "animate_btn"               =>"wiggle",
            "image_shape"               =>"0%",
            "price_bg_shape"            =>"0px",
            "cart_icon_image"           =>"",
        );
        return $default;
    }
}