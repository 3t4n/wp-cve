<?php

namespace OneTeamSoftware\WooCommerce\StickyProductBar;

defined('ABSPATH') || exit;

if (!class_exists(__NAMESPACE__ . '\\Settings')):

class Settings extends \WC_Settings_Page
{
    /**
     * Constructor.
     */
    public function __construct($id)
    {
        $this->id = $id;
		$this->label = __('Sticky Product Bar', $this->id);
		add_filter('woocommerce_settings_tabs_array', array($this, 'add_settings_page'), 20);
        add_action('woocommerce_settings_' . $this->id, array($this, 'output'));
		add_action('woocommerce_settings_save_' . $this->id, array($this, 'save'));
		add_filter($this->id . '_settings_form_fields', array($this, 'groupFormFieldIds'), 1000, 1);
    }

    /**
     * Moves all form field ids into a single associate array, so everything will be stored as one option
     *
     * @return array
     */
	public function groupFormFieldIds($formFields)
	{
		$newFormFields = array();

		foreach ($formFields as $key => $field) {
			if (isset($field['id'])) {
				$field['id'] = $this->id . '[' . $field['id'] . ']';
			}

			$newFormFields[$key] = $field;
		}

		return $newFormFields;
	}

    /**
     * Get settings array
     *
     * @return array
     */
    public function get_settings()
    {

        return apply_filters($this->id . '_settings_form_fields', array(
            'title_begin' => array(
				'title' => __('Sticky Product Bar', $this->id), 
				'type' => 'title', 
				'desc' => sprintf('<div class="notice notice-info inline"><p><br/><li>%s <a href="%s" target="_blank">%s</a>.<br/><li>%s <a href="%s" target="_blank">%s</a>.</p></div>', 
					__('Do you have any questions or requests?', $this->id), 
					'https://1teamsoftware.com/contact-us/', 
					__('We are here to help you!', $this->id),
					 __('Will you recommend <strong>Sticky Product Bar</strong> plugin to others?', $this->id), 
					 'https://wordpress.org/plugins/woo-sticky-product-bar/#reviews', 
					 __('Please take 1 minute to leave your review', $this->id)),
				'id' => 'title'
			),
			'title_end' => array('type' => 'sectionend', 'id' => 'title'),

			'general_settings_begin' => array(
				'id' => 'general_settings',
				'type' => 'title',		
				'title' => __('General Settings', $this->id), 
			),
            'enable' => array(
                'title' => __('Enable Sticky Product Bar', $this->id),
                'type' => 'checkbox',
                'id' => 'enable',
                'default' => 'no',
			),
            'rtl' => array(
                'title' => __('RTL', $this->id),
                'desc' => __('Right to Left arrangement of the bar elements for RTL languages', $this->id),
                'type' => 'checkbox',
                'id' => 'rtl',
                'default' => is_rtl() ? 'yes' : 'no',
			),
			'textOutOfStock' => array(
                'title' => __('"Out of stock" text', $this->id),
                'id' => 'textOutOfStock',
                'type' => 'text',
                'default' => 'Out of stock',
			),
            'textChooseAnOption' => array(
                'title' => __('"Choose an option" text', $this->id),
                'id' => 'textChooseAnOption',
                'type' => 'text',
                'default' => 'Choose an option',
			),
			'scrollAnimationDuration' => array(
				'id' => 'scrollAnimationDuration',
				'title' => __('Duration of Scroll Animation (ms)', $this->id),
				'type' => 'number',
				'default' => 2000
			),			
			'shouldScrollToAddToCart' => array(
				'id' => 'shouldScrollToAddToCart',
				'title' => __('Always Scroll To Add To Cart', $this->id),
				'desc' => sprintf('%s %s %s', __('Do you want to always scroll to Add to Cart button when product bar Add to Cart button has been clicked?', $this->id),  __('Requires:', $this->id), '<a href="https://1teamsoftware.com/product/woocommerce-sticky-product-bar-pro/" target="_blank">WooCommerce Product Sticky Bar PRO</a>'),
				'type' => 'checkbox',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'variableProductBehavior' => array(
                'id' => 'variableProductBehavior',
				'title' => __('Variable Product Behavior', $this->id),
				'desc' => sprintf('%s %s %s', __('Product bar action button might have a different behavior for variable products.', $this->id),  __('Requires:', $this->id), '<a href="https://1teamsoftware.com/product/woocommerce-sticky-product-bar-pro/" target="_blank">WooCommerce Product Sticky Bar PRO</a>'),
				'type' => 'select',
				'options' => array(
					'addToCart' => __('Add to Cart when all options are selected', $this->id),
				),
				'default' => 'addToCart',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'addViewport' => array(
				'id' => 'addViewport',
				'title' => __('Add Viewport', $this->id),
				'desc' => __('Does bar always disappear when you scroll up? Enabling this option can solve it.', $this->id),
				'type' => 'checkbox',
			),		
			'css' => array(
				'title' => __('Custom CSS', $this->id),
				'id' => 'css',
				'type' => 'textarea',
				'desc' => __('Here you can write custom CSS to change the appearance of the Sticky Product Bar', $this->id),
			),
			'general_settings_end' => array('type' => 'sectionend', 'id' => 'general_settings'),

			'element_selectors_begin' => array(
				'id' => 'element_selectors',
				'type' => 'title',		
				'title' => __('Element Selectors', $this->id), 
			),
			'productPriceSelector' => array(
				'title' => __('Product Price Selector', $this->id),
				'id' => 'productPriceSelector',
				'type' => 'text',
				'desc' => __('Optional custom jQuery selector for the product price field from where updated price will be synchronized back to sticky product bar', $this->id),
			),
			'productPriceFilter' => array(
				'title' => __('Product Price Filter', $this->id),
				'id' => 'productPriceFilter',
				'type' => 'text',
				'desc' => __('Optional custom jQuery filter that will be applied to the results returned by the Product Price Selector', $this->id),
			),
			'productQuantitySelector' => array(
				'title' => __('Product Quantity Selector', $this->id),
				'id' => 'productQuantitySelector',
				'type' => 'text',
				'desc' => __('Optional custom jQuery selector for the product quantity field where updated quantity from the sticky product bar will be synchronized', $this->id),
			),
			'element_selectors_end' => array('type' => 'sectionend', 'id' => 'element_selectors'),

			'visibility_settings_begin' => array(
				'id' => 'visibility_settings',
				'type' => 'title',		
				'title' => __('Visibility Settings', $this->id), 
			),
            'enableDesktop' => array(
                'title' => __('Enable on Desktop', $this->id),
                'type' => 'checkbox',
                'id' => 'enableDesktop',
                'default' => 'yes',
			),
            'locationDesktop' => array(
                'id' => 'locationDesktop',
                'title' => __('Location on Desktop', $this->id),
				'type' => 'select',
				'options' => array(
					'top' => __('Top', $this->id),
					'bottom' => __('Bottom', $this->id),
				),
                'default' => 'bottom',
			),			
			'enableMobile' => array(
                'title' => __('Enable on Mobile', $this->id),
                'type' => 'checkbox',
                'id' => 'enableMobile',
                'default' => 'yes',
			),
            'locationMobile' => array(
                'id' => 'locationMobile',
                'title' => __('Location on Mobile', $this->id),
				'type' => 'select',
				'options' => array(
					'top' => __('Top', $this->id),
					'bottom' => __('Bottom', $this->id),
				),
                'default' => 'bottom',
			),			
			'enableForProduct' => array(
                'title' => __('Enable for Products', $this->id),
                'type' => 'checkbox',
                'id' => 'enableForProduct',
                'default' => 'yes',
            ),
            'enableForCart' => array(
                'title' => __('Enable for Cart', $this->id),
                'type' => 'checkbox',
                'id' => 'enableForCart',
                'default' => 'yes',
            ),
            'enableForCheckout' => array(
                'title' => __('Enable for Checkout', $this->id),
                'type' => 'checkbox',
                'id' => 'enableForCheckout',
                'default' => 'yes',
            ),
            'enableForOutOfStock' => array(
                'title' => __('Enable for Out of Stock Products', $this->id),
                'type' => 'checkbox',
                'id' => 'enableForOutOfStock',
                'default' => 'no',
			),
            'alwaysVisible' => array(
                'title' => __('Always visible', $this->id),
                'desc' => __('It can be either always visible or only when action button is no longer visible', $this->id),
                'type' => 'checkbox',
                'id' => 'alwaysVisible',
                'default' => 'yes',
			),
			'visibility_settings_end' => array('type' => 'sectionend', 'id' => 'visibility_settings'),

            'elements_begin' => array(
				'id' => 'elements',
				'type' => 'title',		
				'title' => __('Bar Elements Settings', $this->id), 
			),
            'displayImage' => array(
                'title' => __('Display product image', $this->id),
                'type' => 'checkbox',
                'id' => 'displayImage',
                'default' => 'yes',
            ),
            'displayName' => array(
                'title' => __('Display product name', $this->id),
                'type' => 'checkbox',
                'id' => 'displayName',
                'default' => 'yes',
            ),
            'displayRating' => array(
                'title' => __('Display product rating', $this->id),
                'type' => 'checkbox',
                'id' => 'displayRating',
                'default' => 'yes',
            ),
            'displayQuantity' => array(
                'title' => __('Display purchase quantity', $this->id),
                'type' => 'checkbox',
                'id' => 'displayQuantity',
                'default' => 'yes',
            ),
            'displayPrice' => array(
                'title' => __('Display product price', $this->id),
                'type' => 'checkbox',
                'id' => 'displayPrice',
                'default' => 'yes',
            ),
            'displayPriceRange' => array(
				'title' => __('Display product price range', $this->id),
				'desc' => __('Display (FROM - TO) price range for variable products', $this->id),
                'type' => 'checkbox',
                'id' => 'displayPriceRange',
                'default' => 'yes',
            ),
            'displayTotal' => array(
                'title' => __('Display cart and checkout total', $this->id),
                'type' => 'checkbox',
                'id' => 'displayTotal',
                'default' => 'yes',
            ),
            'displayTerms' => array(
                'title' => __('Display terms checkbox', $this->id),
                'type' => 'checkbox',
                'id' => 'displayTerms',
                'default' => 'yes',
            ),
            'displayButton' => array(
                'title' => __('Display button', $this->id),
                'desc' => __('It displays "Add to cart", "Proceed to Checkout", "Pay" buttons or "Out of Stock" message', $this->id),
                'type' => 'checkbox',
                'id' => 'displayButton',
                'default' => 'yes',
			),
			'displayYithAddToWhistlist' => array(
                'id' => 'displayYithAddToWhistlist',
				'title' => __('Display YITH Add to Whistlist', $this->id),
				'desc' => sprintf('%s %s', __('Requires:', $this->id), '<a href="https://1teamsoftware.com/product/woocommerce-sticky-product-bar-pro/" target="_blank">WooCommerce Product Sticky Bar PRO</a>'),
				'type' => 'checkbox',
				'default' => 'yes',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'yithAddToWhistlistLocation' => array(
				'title' => __('YITH Add to Whistlist Location', $this->id),
				'desc' => sprintf('%s %s', __('Requires:', $this->id), '<a href="https://1teamsoftware.com/product/woocommerce-sticky-product-bar-pro/" target="_blank">WooCommerce Product Sticky Bar PRO</a>'),
                'type' => 'select',
				'id' => 'yithAddToWhistlistLocation',
				'options' => array(
					'' => __('Do not display', $this->id),
				),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),				
			),

			'elements_end' => array('type' => 'sectionend', 'id' => 'elements'),
			
			// PRO version features
			'after_add_to_cart_behavior_settings_begin' => array(
				'id' => 'after_add_to_cart_behavior_settings',
				'type' => 'title',		
				'title' => __('After Add to Cart Behavior', $this->id), 
				'desc' => sprintf('%s %s %s', __('How product bar should behave after product has been added to the cart?', $this->id), __('Requries:', $this->id), '<a href="https://1teamsoftware.com/product/woocommerce-sticky-product-bar-pro/" target="_blank">WooCommerce Product Sticky Bar PRO</a>'),
			),
			'afterAddToCartDisplayImage' => array(
                'title' => __('Display product image', $this->id),
                'type' => 'checkbox',
				'id' => 'afterAddToCartDisplayImage',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
            ),
            'afterAddToCartDisplayName' => array(
                'title' => __('Display product name', $this->id),
                'type' => 'checkbox',
				'id' => 'afterAddToCartDisplayName',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
            ),
            'afterAddToCartDisplayRating' => array(
                'title' => __('Display product rating', $this->id),
                'type' => 'checkbox',
				'id' => 'afterAddToCartDisplayRating',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
            ),
            'afterAddToCartDisplayQuantity' => array(
                'title' => __('Display purchase quantity', $this->id),
                'type' => 'checkbox',
				'id' => 'afterAddToCartDisplayQuantity',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
            ),
            'afterAddToCartDisplayPrice' => array(
                'title' => __('Display product price', $this->id),
                'type' => 'checkbox',
				'id' => 'afterAddToCartDisplayPrice',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
            ),
            'afterAddToCartDisplayButton' => array(
                'title' => __('Display button', $this->id),
                'type' => 'checkbox',
				'id' => 'afterAddToCartDisplayButton',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'afterAddToCartDisplayYithAddToWhistlist' => array(
                'id' => 'afterAddToCartDisplayYithAddToWhistlist',
				'title' => __('Display YITH Add to Whistlist', $this->id),
				'type' => 'checkbox',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'afterAddToCartButtonBehavior' => array(
                'id' => 'afterAddToCartButtonBehavior',
				'title' => __('Button Behavior', $this->id),
				'type' => 'select',
				'options' => array(
					'addToCart' => __('Add to Cart', $this->id),
				),
				'default' => 'addToCart',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
            'afterAddToCartButtonText' => array(
                'title' => __('Button Text', $this->id),
                'id' => 'afterAddToCartButtonText',
                'type' => 'text',
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),

			'after_add_to_cart_behavior_settings_end' => array('type' => 'sectionend', 'id' => 'after_add_to_cart_behavior_settings'),

			'appearance_settings_begin' => array(
				'id' => 'appearance_settings',
				'type' => 'title',		
				'title' => __('Appearance Settings', $this->id), 
				'desc' => sprintf(__('Customize every little detail of the bar appearance with %s. All color options will have a picker as well as a manual entry.', $this->id), '<a href="https://1teamsoftware.com/product/woocommerce-sticky-product-bar-pro/" target="_blank">WooCommerce Product Sticky Bar PRO</a>'),
			),
			
			'css_font_family' => array(
                'title' => __('Common Font Family', $this->id),
                'id' => 'css_font_family',
				'type' => 'text',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_font_size' => array(
                'title' => __('Common Font Size', $this->id),
                'id' => 'css_font_size',
				'type' => 'number',
				'suffix' => 'px',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_font_weight' => array(
                'title' => __('Common Font Weight', $this->id),
                'id' => 'css_font_weight',
				'type' => 'select',
				'options' => array(
					'inherit' => __('Inherit', $this->id),
				),
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_color' => array(
                'title' => __('Common Font Color', $this->id),
                'id' => 'css_color',
				'type' => 'text',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_padding' => array(
                'title' => __('Bar Padding', $this->id),
                'id' => 'css_padding',
				'type' => 'number',
				'suffix' => 'px',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_width' => array(
                'title' => __('Bar Width', $this->id),
                'id' => 'css_width',
				'type' => 'number',
				'suffix' => 'px',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_height' => array(
                'title' => __('Bar Height', $this->id),
                'id' => 'css_height',
				'type' => 'number',
				'suffix' => 'px',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),

			'css_margin_top' => array(
                'title' => __('Bar Top Margin', $this->id),
                'id' => 'css_margin_top',
				'type' => 'number',
				'suffix' => 'px',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_margin_bottom' => array(
                'title' => __('Bar Bottom Margin', $this->id),
                'id' => 'css_margin_bottom',
				'type' => 'number',
				'suffix' => 'px',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),

			'css_background' => array(
                'title' => __('Bar Background Color', $this->id),
                'id' => 'css_background',
				'type' => 'text',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_opacity' => array(
                'title' => __('Bar Opacity', $this->id),
                'id' => 'css_opacity',
				'type' => 'number',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_border_top_color' => array(
                'title' => __('Bar Top Border Color', $this->id),
                'id' => 'css_border_top_color',
				'type' => 'text',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_border_top_width' => array(
                'title' => __('Bar Top Border Width', $this->id),
                'id' => 'css_border_top_width',
				'type' => 'number',
				'suffix' => 'px',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_border_bottom_color' => array(
                'title' => __('Bar Bottom Border Color', $this->id),
                'id' => 'css_border_bottom_color',
				'type' => 'text',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_border_bottom_width' => array(
                'title' => __('Bar Bottom Border Width', $this->id),
                'id' => 'css_border_bottom_width',
				'type' => 'number',
				'suffix' => 'px',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),

			'css_image_background' => array(
                'title' => __('Image Background Color', $this->id),
                'id' => 'css_image_background',
				'type' => 'text',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_image_border_color' => array(
                'title' => __('Image Border Color', $this->id),
                'id' => 'css_image_border_color',
				'type' => 'text',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_image_border_width' => array(
                'title' => __('Image Border Width', $this->id),
                'id' => 'css_image_border_width',
				'type' => 'number',
				'suffix' => 'px',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_image_border_radius' => array(
                'title' => __('Image Border Radius', $this->id),
                'id' => 'css_image_border_radius',
				'type' => 'number',
				'suffix' => 'px',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			
			'css_name_font_size' => array(
                'title' => __('Name Font Size', $this->id),
                'id' => 'css_name_font_size',
				'type' => 'number',
				'suffix' => 'px',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_name_font_weight' => array(
                'title' => __('Name Font Weight', $this->id),
                'id' => 'css_name_font_weight',
				'type' => 'select',
				'options' => array(
					'inherit' => __('Inherit', $this->id),
				),
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_name_font_color' => array(
                'title' => __('Name Font Color', $this->id),
                'id' => 'css_name_font_color',
				'type' => 'text',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),

			'css_quantity_background' => array(
                'title' => __('Quantity Background Color', $this->id),
                'id' => 'css_quantity_background',
				'type' => 'text',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_quantity_font_size' => array(
                'title' => __('Quantity Font Size', $this->id),
                'id' => 'css_quantity_font_size',
				'type' => 'number',
				'suffix' => 'px',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_quantity_font_weight' => array(
                'title' => __('Quantity Font Weight', $this->id),
                'id' => 'css_quantity_font_weight',
				'type' => 'select',
				'options' => array(
					'inherit' => __('Inherit', $this->id),
				),
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_quantity_font_color' => array(
                'title' => __('Quantity Font Color', $this->id),
                'id' => 'css_quantity_font_color',
				'type' => 'text',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),						
			'css_quantity_border_color' => array(
                'title' => __('Quantity Border Color', $this->id),
                'id' => 'css_quantity_border_color',
				'type' => 'text',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_quantity_border_width' => array(
                'title' => __('Quantity Border Width', $this->id),
                'id' => 'css_quantity_border_width',
				'type' => 'number',
				'suffix' => 'px',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_quantity_border_radius' => array(
                'title' => __('Quantity Border Radius', $this->id),
                'id' => 'css_quantity_border_radius',
				'type' => 'number',
				'suffix' => 'px',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),

			'css_price_font_size' => array(
                'title' => __('Price Font Size', $this->id),
                'id' => 'css_price_font_size',
				'type' => 'number',
				'suffix' => 'px',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_price_font_weight' => array(
                'title' => __('Price Font Weight', $this->id),
                'id' => 'css_price_font_weight',
				'type' => 'select',
				'options' => array(
					'inherit' => __('Inherit', $this->id),
				),
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_price_font_color' => array(
                'title' => __('Price Font Color', $this->id),
                'id' => 'css_price_font_color',
				'type' => 'text',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),	

			'css_button_background' => array(
                'title' => __('Button Background Color', $this->id),
                'id' => 'css_button_background',
				'type' => 'text',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_button_font_size' => array(
                'title' => __('Button Font Size', $this->id),
                'id' => 'css_button_font_size',
				'type' => 'number',
				'suffix' => 'px',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_button_font_weight' => array(
                'title' => __('Button Font Weight', $this->id),
                'id' => 'css_button_font_weight',
				'type' => 'select',
				'options' => array(
					'inherit' => __('Inherit', $this->id),
				),
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_button_font_color' => array(
                'title' => __('Button Font Color', $this->id),
                'id' => 'css_button_font_color',
				'type' => 'text',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_button_top_and_bottom_padding' => array(
                'title' => __('Button Top and Bottom Padding', $this->id),
                'id' => 'css_button_top_and_bottom_padding',
				'type' => 'number',
				'suffix' => 'px',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_button_left_and_right_padding' => array(
                'title' => __('Button Left and Right Padding', $this->id),
                'id' => 'css_button_left_and_right_padding',
				'type' => 'number',
				'suffix' => 'px',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),

			'css_button_border_color' => array(
                'title' => __('Button Border Color', $this->id),
                'id' => 'css_button_border_color',
				'type' => 'text',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_button_border_width' => array(
                'title' => __('Button Border Width', $this->id),
                'id' => 'css_button_border_width',
				'type' => 'number',
				'suffix' => 'px',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_button_border_radius' => array(
                'title' => __('Button Border Radius', $this->id),
                'id' => 'css_button_border_radius',
				'type' => 'number',
				'suffix' => 'px',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),

			'css_rating_base_color' => array(
                'title' => __('Rating Base Color', $this->id),
                'id' => 'css_rating_base_color',
				'type' => 'text',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_rating_star_color' => array(
                'title' => __('Rating Star Color', $this->id),
                'id' => 'css_rating_star_color',
				'type' => 'text',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'css_rating_star_size' => array(
                'title' => __('Rating Star Size', $this->id),
                'id' => 'css_rating_star_size',
				'type' => 'number',
				'suffix' => 'px',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),

			'appearance_settings_end' => array('type' => 'sectionend', 'id' => 'appearance_settings'),

			'insertContent_begin' => array(
				'id' => 'insertContent',
				'type' => 'title',		
				'title' => __('Insert Content', $this->id), 
				'desc' => sprintf(__('Insert HTML or a shortcode ( [your_shortcode] ) before or after any element of the bar with %s.', $this->id), '<a href="https://1teamsoftware.com/product/woocommerce-sticky-product-bar-pro/" target="_blank">WooCommerce Product Sticky Bar PRO</a>')
			),
            'enableInsertContent' => array(
                'title' => __('Enable Insert Content', $this->id),
                'type' => 'checkbox',
                'id' => 'enableInsertContent',
				'default' => 'no',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'insertBeforeContent' => array(
				'id' => 'insertBeforeContent',
				'type' => 'textarea',
				'title' => __('Before Content', $this->id),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'insertAfterContent' => array(
				'id' => 'insertAfterContent',
				'type' => 'textarea',
				'title' => __('After Content', $this->id),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'insertBeforeProductImage' => array(
				'id' => 'insertBeforeProductImage',
				'type' => 'textarea',
				'title' => __('Before Product Image', $this->id),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),

			),
			'insertAfterProductImage' => array(
				'id' => 'insertAfterProductImage',
				'type' => 'textarea',
				'title' => __('After Product Image', $this->id),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),

			),
			'insertBeforeProductName' => array(
				'id' => 'insertBeforeProductName',
				'type' => 'textarea',
				'title' => __('Before Product Name', $this->id),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),
			),
			'insertAfterProductName' => array(
				'id' => 'insertAfterProductName',
				'type' => 'textarea',
				'title' => __('After Product Name', $this->id),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),

			),
			'insertBeforeProductRating' => array(
				'id' => 'insertBeforeProductRating',
				'type' => 'textarea',
				'title' => __('Before Product Rating', $this->id),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),

			),
			'insertAfterProductRating' => array(
				'id' => 'insertAfterProductRating',
				'type' => 'textarea',
				'title' => __('After Product Rating', $this->id),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),

			),
			'insertBeforeProductPrice' => array(
				'id' => 'insertBeforeProductPrice',
				'type' => 'textarea',
				'title' => __('Before Product Price', $this->id),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),

			),
			'insertAfterProductPrice' => array(
				'id' => 'insertAfterProductPrice',
				'type' => 'textarea',
				'title' => __('After Product Price', $this->id),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),

			),
			'insertBeforeProductQuantity' => array(
				'id' => 'insertBeforeProductQuantity',
				'type' => 'textarea',
				'title' => __('Before Product Quantity', $this->id),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),

			),
			'insertAfterProductQuantity' => array(
				'id' => 'insertAfterProductQuantity',
				'type' => 'textarea',
				'title' => __('After Product Quantity', $this->id),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),

			),
			'insertBeforeProductButton' => array(
				'id' => 'insertBeforeProductButton',
				'type' => 'textarea',
				'title' => __('Before Product Button', $this->id),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),

			),
			'insertAfterProductButton' => array(
				'id' => 'insertAfterProductButton',
				'type' => 'textarea',
				'title' => __('After Product Button', $this->id),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),

			),
			'insertBeforeCartTotal' => array(
				'id' => 'insertBeforeCartTotal',
				'type' => 'textarea',
				'title' => __('Before Cart Total', $this->id),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),

			),
			'insertAfterCartTotal' => array(
				'id' => 'insertAfterCartTotal',
				'type' => 'textarea',
				'title' => __('After Cart Total', $this->id),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),

			),
			'insertBeforeCartButton' => array(
				'id' => 'insertBeforeCartButton',
				'type' => 'textarea',
				'title' => __('Before Cart Button', $this->id),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),

			),
			'insertAfterCartButton' => array(
				'id' => 'insertAfterCartButton',
				'type' => 'textarea',
				'title' => __('After Cart Button', $this->id),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),

			),
			'insertBeforeCheckoutTerms' => array(
				'id' => 'insertBeforeCheckoutTerms',
				'type' => 'textarea',
				'title' => __('Before Checkout Terms', $this->id),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),

			),
			'insertAfterCheckoutTerms' => array(
				'id' => 'insertAfterCheckoutTerms',
				'type' => 'textarea',
				'title' => __('After Checkout Terms', $this->id),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),

			),
			'insertBeforeCheckoutTotal' => array(
				'id' => 'insertBeforeCheckoutTotal',
				'type' => 'textarea',
				'title' => __('Before Checkout Total', $this->id),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),

			),
			'insertAfterCheckoutTotal' => array(
				'id' => 'insertAfterCheckoutTotal',
				'type' => 'textarea',
				'title' => __('After Checkout Total', $this->id),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),

			),
			'insertBeforeCheckoutButton' => array(
				'id' => 'insertBeforeCheckoutButton',
				'type' => 'textarea',
				'title' => __('Before Checkout Button', $this->id),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),

			),
			'insertAfterCheckoutButton' => array(
				'id' => 'insertAfterCheckoutButton',
				'type' => 'textarea',
				'title' => __('After Checkout Button', $this->id),
				'default' => '',
				'custom_attributes' => array(
					'disabled' => 'yes',
				),

			),


			'insertContent_end' => array('type' => 'sectionend', 'id' => 'insertContent'),
			
        )); // End pages settings
    }
}

endif;