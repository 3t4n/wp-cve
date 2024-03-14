<?php

namespace Element_Ready;

use Element_Ready\Modules\Header_Footer\Init as Header_Footer;
use Element_Ready\Modules\Menu_Builder\Init as Menu_Builder;
use Element_Ready\Modules\TemplateLibrary\Init as TemplateLibrary;
use Element_Ready\Modules\Newslatter\Init as Newslatter;
use Element_Ready\Modules\blocks\Init as Block;
use Element_Ready\Document\Settings_Tabs;
final class Init 
{
	/**
	 * Store all the classes inside an array
	 * @return array Full list of classes
	 */
	public static function get_services() 
	{
		return [
				
			//elementor
			dashboard\Portfolio::class,
			dashboard\Widgets::class,
			dashboard\Settings::class,
			Base\Controls\Generel_Controls::class,
			//Widget_Controls\Skins\Register::class,
			Base\Controls\Slider\Generel_Controls::class,
			Base\Controls\Slider\Generel_Block_Controls::class,
			Base\Controls\Slider\Generel_Controls_2::class,
			Base\Controls\Slider\Slick_Generel_Controls::class,
			Base\Media\Unsplash\Panel::class,
			Base\Controls\Slider\Generel_Two_Col_Controls::class,
			Base\Controls\Slider\Slick_Generel_Banner_Controls::class,
			Base\Controls\Video\Generel_Video_Controls::class,
			Base\Controls\Video\General_Video_PopUp::class,
			Base\Controls\Video\Generel_Two_Col_Controls::class,
			Base\Controls\Grid\Generel_Controls::class,
			Base\Controls\Grid\Generel_Controls_overlay::class,
			Base\Controls\Grid\Generel_Controls__two::class,
			Base\Controls\Grid\Generel_Controls_two_col::class,
			Base\Controls\Card\Generel_Controls::class,
			Base\Controls\List_Post\Generel_Controls::class,
			Base\Controls\List_Post\Generel_Grid_Controls::class,
			Base\Controls\List_Post\General_Block_List_Controls::class,
			Base\Controls\Video\Generel_Controls::class,
			Base\Controls\Data_Exclude_Controls::class,
			Base\Controls\Date_Filter_Controls::class,
			Base\Controls\Taxonomy_Filter_Controls::class,
			Base\Controls\Sort_Controls::class,
			Base\Controls\Slider_Controls::class,
			Base\Controls\Slider\Slick_Slider_Controls::class,
			Base\Controls\Sticky_Controls::class,
			Base\Controls\Widget_Control\Pro_Controls::class,
			Controls\ER_Custom_Css::class,
			Base\Download::class,
			Base\Shopping_Cart::class,
			Base\Section_Isolation::class,
			Base\SignIn::class,
			Base\SignUp::class,
			Base\PolyLang::class,
			Base\Column_Wrapper::class,
		
           
			// custom post type
			Base\CPT\Portfolio::class,
			Base\CPT\Portfolio_Category::class,
			Base\CPT\Portfolio_Tags::class,
			Document\Settings_Tabs::class,
			
		];
	}


	/**
	 * Loop through the classes, initialize them, 
	 * and call the register() method if it exists
	 * @return
	 */
	public static function register_services() 
	{
		foreach ( self::get_services() as $class ) {
			$service = self::instantiate( $class );
		
			if ( method_exists( $service, 'register' ) ) {
				$service->register();
			}
		}
	}

	public static function register_modules(){
		
		
		if(element_ready_get_modules_option('header_footer_builder')){
			Header_Footer::register_services();
		}
		
		if(element_ready_get_components_option('megamenu')){
			Menu_Builder::register_services();
		}
		
		if(element_ready_get_modules_option('template_importer')){
			TemplateLibrary::register_services();
		}

		if(element_ready_get_modules_option('_newslatter_popup')){
			Newslatter::register_services();
		}
			
		Block::register_services();
		
	}

	/**
	 * Initialize the class
	 * @param  class $class    class from the services array
	 * @return class instance  new instance of the class
	 */
	private static function instantiate( $class )
	{
		$service = new $class();

		return $service;
	}
}



