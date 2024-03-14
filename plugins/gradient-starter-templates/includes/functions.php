<?php
function gradient_starter_templates_get_current_theme_author(){
    $current_theme = wp_get_theme();
    return $current_theme->get('Author');
}
function gradient_starter_templates_get_current_theme_slug(){
    $current_theme = wp_get_theme();
    return $current_theme->stylesheet;
}
function gradient_starter_templates_get_theme_screenshot(){
    $current_theme = wp_get_theme();
    return $current_theme->get_screenshot();
}
function gradient_starter_templates_get_theme_name(){
    $current_theme = wp_get_theme();
    return $current_theme->get('Name');
}

function gradient_starter_templates_is_pro(){
    
    //echo gradient_starter_templates_get_current_theme_slug();
    
    if(gradient_starter_templates_get_current_theme_slug()=='best-shop' && function_exists('best_shop_pro_textdomain') ){
        return false;
    } 
    
    if(gradient_starter_templates_get_current_theme_slug()=='news-blog' && function_exists('news_blog_pro_textdomain') ){
        return false;
    } 
    
    if(gradient_starter_templates_get_current_theme_slug()=='hotel-and-travel' && function_exists('hotel_and_travel_pro_textdomain') ){
        return false;
    }     
    
    return true;
}



function gradient_starter_templates_get_templates_lists( $theme_slug ){
    
    $demo_templates_lists = array();    
    //Use parent templatest for child themes
    $theme_slug = get_template();
    
    
    if ( $theme_slug == "hotel-and-travel" ){
            
          $theme_slug = 'common'; //point to common demos folder
          $demo_templates_lists = array(
              
              'hotel-and-travel' =>array(
                  
                'title' => __( 'Hotel and Travel', 'wp-starter-templates' ),/*Title*/
                'is_pro' => gradient_starter_templates_is_pro(),/*Premium*/
                'pro_url' => 'https://gradientthemes.com/',
                'type' => 'Hotel', /*Optional eg elementor or other page builders*/
                'author' => __( 'Gradient Themes', 'wp-starter-templates' ),/*Author Name*/
                'keywords' => array( 'woocommerce', 'Hotel', 'business', 'elementor', 'blog' ),/*Search keyword*/
                'template_url' => array(
                    'content' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/hotel-and-travel/content.json',
                    'options' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/hotel-and-travel/options.json',
                    'widgets' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/hotel-and-travel/widgets.json'
                ),
                'screenshot_url' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/hotel-and-travel/screenshot.png',/*Screenshot of block*/
                'demo_url' => 'https://wordpress.gradientthemes.com/hotel-and-travel/',/*Demo Url*/
                'plugins' => array(
                        array(
                        'name'      => 'Elementor Page Builder',
                        'slug'      => 'elementor',
                        ),
                        array(
                        'name'      => 'WP Hotel Booking',
                        'slug'      => 'wp-hotel-booking',
                        ),                    

                )

            ),
              
              
             'business-agency' =>array(
                  'title' => __( 'Business / Agency', 'wp-starter-templates' ),/*Title*/
                  'is_pro' => gradient_starter_templates_is_pro(),/*Premium*/
                  'pro_url' => 'https://gradientthemes.com/',
                  'type' => 'elementor', /*Optional eg elementor or other page builders*/
                  'author' => __( 'Gradient Themes', 'wp-starter-templates' ),/*Author Name*/
                  'keywords' => array( 'woocommerce', 'business', 'elementor' ),/*Search keyword*/
                  'template_url' => array(
                      'content' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/business-agency/content.json',
                      'options' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/business-agency/options.json',
                      'widgets' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/business-agency/widgets.json'
                  ),
                  'screenshot_url' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/business-agency/screenshot.png',/*Screenshot of block*/
                  'demo_url' => 'https://wordpress.gradientthemes.com/business-consulting/',/*Demo Url*/
                  /**/
                  'plugins' => array(

                          array(
                              'name'      => 'Elementor Page Builder',
                              'slug'      => 'elementor',
                          ),



                  )
              ),


             'fashion-shop' =>array(
                  'title' => __( 'Fashion Shop', 'wp-starter-templates' ),/*Title*/
                  'is_pro' => gradient_starter_templates_is_pro(),/*Premium*/
                  'pro_url' => 'https://gradientthemes.com/',
                  'type' => 'elementor', /*Optional eg elementor or other page builders*/
                  'author' => __( 'Gradient Themes', 'wp-starter-templates' ),/*Author Name*/
                  'keywords' => array( 'woocommerce', 'business', 'elementor' ),/*Search keyword*/
                  'template_url' => array(
                      'content' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/fashion-shop/content.json',
                      'options' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/fashion-shop/options.json',
                      'widgets' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/fashion-shop/widgets.json'
                  ),
                  'screenshot_url' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/fashion-shop/screenshot.jpg',/*Screenshot of block*/
                  'demo_url' => 'https://wordpress.gradientthemes.com/fashion-shop/',/*Demo Url*/
                  /**/
                  'plugins' => array(

                          array(
                              'name'      => 'Elementor Page Builder',
                              'slug'      => 'elementor',
                          ),

                          array(
                              'name'      => 'WooCommerce',
                              'slug'      => 'woocommerce',
                          ),


                          array(
                              'name'      => 'Product Quick View',
                              'slug'      => 'yith-woocommerce-quick-view',
                              'main_file' => 'init.php',
                          ),	


                  )
              ),              

          
          );
              
              
    } //end demos
    
	
    if ( $theme_slug == "news-blog" ){
            
          $theme_slug = 'common'; //point to common demos folder
          $demo_templates_lists = array(
              
              'news-blog' =>array(
                  
                'title' => __( 'News Blog', 'wp-starter-templates' ),/*Title*/
                'is_pro' => gradient_starter_templates_is_pro(),/*Premium*/
                'pro_url' => 'https://gradientthemes.com/',
                'type' => 'news', /*Optional eg elementor or other page builders*/
                'author' => __( 'Gradient Themes', 'wp-starter-templates' ),/*Author Name*/
                'keywords' => array( 'woocommerce', 'news', 'business', 'elementor', 'blog' ),/*Search keyword*/
                'template_url' => array(
                    'content' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/news-blog/content.json',
                    'options' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/news-blog/options.json',
                    'widgets' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/news-blog/widgets.json'
                ),
                'screenshot_url' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/news-blog/screenshot.png',/*Screenshot of block*/
                'demo_url' => 'https://wordpress.gradientthemes.com/news-blog/',/*Demo Url*/
                'plugins' => array(
					
						array(
							'name'      => 'Elementor Page Builder',
							'slug'      => 'elementor',
						),
                    
                )

            ));
              
              
    } //end demos
    
    
    


    if ( $theme_slug == "best-shop" ){

        $theme_slug = 'common';
    
        $demo_templates_lists = array(
            
            
           'shop-demo' =>array(
                'title' => __( 'Shop Demo', 'wp-starter-templates' ), /*Title*/
                'is_pro' => false, /*Not Premium*/
                'type' => 'elementor', /*Optional eg elementor or other page builders*/
                'author' => __( 'Gradient Themes', 'wp-starter-templates' ), /*Author Name*/
                'keywords' => array( 'woocommerce', 'business', 'elementor' ), /*Search keyword*/
                'template_url' => array(
                    'content' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/best-shop/content.json',
                    'options' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/best-shop/options.json',
                    'widgets' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/best-shop/widgets.json'
                ),
                'screenshot_url' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/best-shop/screenshot.png', /*Screenshot of block*/
                'demo_url' => 'https://wordpress.gradientthemes.com/brand-shop/home/', /*Demo Url*/
                /**/
                'plugins' => array(
					
						array(
							'name'      => 'Elementor Page Builder',
							'slug'      => 'elementor',
						),
						
						array(
							'name'      => 'WooCommerce',
							'slug'      => 'woocommerce',
						),
												
                    
						array(
							'name'      => 'Product Quick View',
							'slug'      => 'yith-woocommerce-quick-view',
                            'main_file' => 'init.php',
						),		

                    
                    
                )
            ),
            


            
           'brand-shop' =>array(
                'title' => __( 'Brand Shop', 'wp-starter-templates' ),/*Title*/
                'is_pro' => gradient_starter_templates_is_pro(),/*Premium*/
                'pro_url' => 'https://gradientthemes.com/',               
                'type' => 'elementor', /*Optional eg elementor or other page builders*/
                'author' => __( 'Gradient Themes', 'wp-starter-templates' ),/*Author Name*/
                'keywords' => array( 'woocommerce', 'business', 'elementor' ),/*Search keyword*/
                'template_url' => array(
                    'content' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/brand-shop/content.json',
                    'options' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/brand-shop/options.json',
                    'widgets' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/brand-shop/widgets.json'
                ),
                'screenshot_url' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/brand-shop/screenshot.png',/*Screenshot of block*/
                'demo_url' => 'https://wordpress.gradientthemes.com/brand-shop/home-2/',/*Demo Url*/
                /**/
                'plugins' => array(
					
						array(
							'name'      => 'Elementor Page Builder',
							'slug'      => 'elementor',
						),
						
						array(
							'name'      => 'WooCommerce',
							'slug'      => 'woocommerce',
						),
                    						
						array(
							'name'      => 'Product Quick View',
							'slug'      => 'yith-woocommerce-quick-view',
                            'main_file' => 'init.php',
						),		
                    
                )
            ),
            

           'business-agency' =>array(
                'title' => __( 'Business / Agency', 'wp-starter-templates' ),/*Title*/
                'is_pro' => false,/*Premium*/
                'type' => 'elementor', /*Optional eg elementor or other page builders*/
                'author' => __( 'Gradient Themes', 'wp-starter-templates' ),/*Author Name*/
                'keywords' => array( 'woocommerce', 'business', 'elementor' ),/*Search keyword*/
                'template_url' => array(
                    'content' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/business-agency/content.json',
                    'options' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/business-agency/options.json',
                    'widgets' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/business-agency/widgets.json'
                ),
                'screenshot_url' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/business-agency/screenshot.png',/*Screenshot of block*/
                'demo_url' => 'https://wordpress.gradientthemes.com/business-consulting/',/*Demo Url*/
                /**/
                'plugins' => array(
					
						array(
							'name'      => 'Elementor Page Builder',
							'slug'      => 'elementor',
						),
												

                    
                )
            ),
            
            
            
           'fashion-shop' =>array(
                'title' => __( 'Fashion Shop', 'wp-starter-templates' ),/*Title*/
                'is_pro' => gradient_starter_templates_is_pro(),/*Premium*/
                'pro_url' => 'https://gradientthemes.com/',
                'type' => 'elementor', /*Optional eg elementor or other page builders*/
                'author' => __( 'Gradient Themes', 'wp-starter-templates' ),/*Author Name*/
                'keywords' => array( 'woocommerce', 'business', 'elementor' ),/*Search keyword*/
                'template_url' => array(
                    'content' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/fashion-shop/content.json',
                    'options' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/fashion-shop/options.json',
                    'widgets' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/fashion-shop/widgets.json'
                ),
                'screenshot_url' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/fashion-shop/screenshot.jpg',/*Screenshot of block*/
                'demo_url' => 'https://wordpress.gradientthemes.com/fashion-shop/',/*Demo Url*/
                /**/
                'plugins' => array(
					
						array(
							'name'      => 'Elementor Page Builder',
							'slug'      => 'elementor',
						),
												
						array(
							'name'      => 'WooCommerce',
							'slug'      => 'woocommerce',
						),
								
                    						
						array(
							'name'      => 'Product Quick View',
							'slug'      => 'yith-woocommerce-quick-view',
                            'main_file' => 'init.php',
						),	

                    
                )
            ),              
            
            
            

           'grocery-shop' =>array(
                'title' => __( 'Grocery Shop', 'wp-starter-templates' ),/*Title*/
                'is_pro' => gradient_starter_templates_is_pro(),/*Premium*/
                'pro_url' => 'https://gradientthemes.com/',
                'type' => 'elementor', /*Optional eg elementor or other page builders*/
                'author' => __( 'Gradient Themes', 'wp-starter-templates' ),/*Author Name*/
                'keywords' => array( 'woocommerce', 'business', 'elementor' ),/*Search keyword*/
                'template_url' => array(
                    'content' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/grocery-shop/content.json',
                    'options' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/grocery-shop/options.json',
                    'widgets' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/grocery-shop/widgets.json'
                ),
                'screenshot_url' => gradient_starter_templates_TEMPLATE_URL.$theme_slug.'/grocery-shop/screenshot.jpg',/*Screenshot of block*/
                'demo_url' => 'https://wordpress.gradientthemes.com/grocery-shop/',/*Demo Url*/
                /**/
                'plugins' => array(
					
						array(
							'name'      => 'Elementor Page Builder',
							'slug'      => 'elementor',
						),
												
						array(
							'name'      => 'WooCommerce',
							'slug'      => 'woocommerce',
						),
                    						
						array(
							'name'      => 'Product Quick View',
							'slug'      => 'yith-woocommerce-quick-view',
                            'main_file' => 'init.php',
						),		

                    
                )
            ),            
    	
        ); // end demos
        
        

    }
			

    return $demo_templates_lists;

}
