<?php
namespace TXElementorAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class tx_woo extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'tx-woo';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Products', 'tx' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-cart-medium';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'templatesnext-addons' ];
	}
	
	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	 
	 
	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Products Listing', 'tx' ),
			]
		);
		
		$this->add_control(
			'listingtype',
			[
				'label' => __( 'Listing Type', 'tx' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'product_categories'  	=> __( 'Product Categories', 'tx' ),
					'recent_products'  		=> __( 'Recent Products', 'tx' ),
					'featured_products'  	=> __( 'Featured Products', 'tx' ),
					'sale_products'  		=> __( 'Products On Sale', 'tx' ),
					'best_selling_products' => __( 'Best Selling Products', 'tx' ),
					'top_rated_products'  	=> __( 'Top Rated Products', 'tx' ),
					'products'  			=> __( 'Products By Ids', 'tx' ),				
				],
				'default' => 'featured_products',
				'dynamic' => [
					'active' => true,
				],				
			]
		);
		$this->add_control(
			'ids',
			[
				'label' => __( 'Product IDs', 'tx' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'description' => __( 'Comma seperated product ids, ex. 12,16,24 ', 'tx' ),
				'condition' => ['listingtype' => 'products']
			]
		);
		$this->add_control(
			'items',
			[
				'label' => __( 'Number Of Products', 'tx' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 20,
				'step' => 1,
				'default' => 4,
				'dynamic' => [
					'active' => true,
				],				
			]
		);
		$this->add_control(
			'columns',
			[
				'label' => __( 'Number Of Columns', 'tx' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 4,
				'step' => 1,
				'default' => 4,
				'dynamic' => [
					'active' => true,
				],				
			]
		);				
		
		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function render() {
		
		$settings = $this->get_settings_for_display();

		$return_string = '';
		
		//$return_string = do_shortcode('[contact-form-7 id="5" title="Contact form 1"]');
		$listtype = esc_html($settings['listingtype']);
		$prodids = esc_html($settings['ids']);
		$items = esc_html($settings['items']);
		$columns = esc_html($settings['columns']);
		
		
		if ( !empty($prodids) && ( $listtype == 'product_categories' || $listtype == 'products' ))
		{
			if ( $listtype == 'product_categories' )
			{
				$prod_shortcode = '['.$listtype.' number="'.$items.'" columns="'.$columns.'" ids="'.$prodids.'"]';
			} else
			{
				$prod_shortcode = '['.$listtype.' per_page="'.$items.'" columns="'.$columns.'" ids="'.$prodids.'"]';
			}
		} else
		{
			if ( $listtype == 'product_categories' )
			{
				$prod_shortcode = '['.$listtype.' number="'.$items.'" columns="'.$columns.'"]';
			} else
			{
				$prod_shortcode = '['.$listtype.' per_page="'.$items.'" columns="'.$columns.'"]';
			}		
		}
		
		$return_string = $prod_shortcode;				

		echo do_shortcode($return_string);

	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function content_template() {}
}
