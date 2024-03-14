<?php
/*
* QuantumCloud Promo + Support Page
* Revised On: 18-10-2023
*/

if ( ! defined( 'qc_sld_support_url' ) )
    define('qc_sld_support_url', plugin_dir_url( __FILE__ ) );

if ( ! defined( 'qcld_support_img_url' ) )
    define('qcld_support_img_url', qc_sld_support_url . "/images" );


/*Callback function to add the menu */
function qc_sld_show_promo_page_callback_func()
{
    add_submenu_page(
        "edit.php?post_type=sld",
        esc_html__('More WordPress Goodies for You!'),
        esc_html__('Support'),
        'manage_options',
        "qcopd_sld_supports",
        'qc_sld_promo_support_page_callback_func'
    );
} //show_promo_page_callback_func

add_action( 'admin_menu', 'qc_sld_show_promo_page_callback_func', 10 );


/*******************************
 * Main Class to Display Support
 * form and the promo pages
 *******************************/

if ( ! function_exists( 'qc_sld_include_promo_page_scripts' ) ) {	
	function qc_sld_include_promo_page_scripts( ) {   


        if( isset($_GET["page"]) && !empty($_GET["page"]) && (   $_GET["page"] == "qcopd_sld_supports"  ) ){

            wp_enqueue_style( 'qcld-support-fontawesome-css', qc_sld_support_url . "css/font-awesome.min.css");                              
            wp_enqueue_style( 'qcld-support-style-css', qc_sld_support_url . "css/style.css");

            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'jquery-ui-core');
            wp_enqueue_script( 'jquery-ui-tabs' );
            wp_enqueue_script( 'jquery-custom-form-processor', qc_sld_support_url . 'js/support-form-script.js',  array('jquery', 'jquery-ui-core','jquery-ui-tabs') );

            wp_add_inline_script( 'jquery-custom-form-processor', 
                                    'var qc_sld_ajaxurl    = "' . admin_url('admin-ajax.php') . '";
                                    var qc_sld_ajax_nonce  = "'. wp_create_nonce( 'qc-opd' ).'";   
                                ', 'before');
            
        }
	   
	}
	add_action('admin_enqueue_scripts', 'qc_sld_include_promo_page_scripts');
	
}
		
/*******************************
 * Callback function to show the HTML
 *******************************/
if ( ! function_exists( 'qc_sld_promo_support_page_callback_func' ) ) {

	function qc_sld_promo_support_page_callback_func() {
		
?>
        <div class="qc-sld-support">
            <div class="support-btn-main justify-content-center">
                <div class="col text-center">
                    <h2 class="py-3"><?php esc_html_e('Check Out Some of Our Other Works that Might Make Your Website Better', 'qc-opd'); ?></h2>
                    <h5><?php esc_html_e('All our Pro Version users get Premium, Guaranteed Quick, One on One Priority Support.', 'qc-opd'); ?></h5>
                    <div class="support-btn">
                        <a class="premium-support" href="<?php echo esc_url('https://qc.ticksy.com/'); ?>" target="_blank"><?php esc_html_e('Get Priority Support ', 'qc-opd'); ?></a>
                        <a style="width:282px" class="premium-support" href="<?php echo esc_url('https://www.quantumcloud.com/resources/kb-sections/simple-link-directory/'); ?>" target="_blank"><?php esc_html_e('Online KnowledgeBase', 'qc-opd'); ?></a>
                    </div>
                </div>
            </div>
            <div class="qc-column-12" style="margin-top: 12px;">
                <div class="support-btn">
                    <a class="premium-support premium-support-free" href="<?php echo esc_url('https://www.quantumcloud.com/resources/free-support/','qc-opd') ?>" target="_blank"><?php esc_html_e('Get Support for Free Version','qc-opd') ?></a>
                </div>
            </div>
            <div class="row g-0">

                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.wpbot.pro/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/wp-bot.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.wpbot.pro/'); ?>" target="_blank"><?php esc_html_e('WPBot – ChatBot for WordPress', 'qc-opd'); ?></a></h5>
                            <p><?php esc_html_e('WPBot is a ChatBot for any WordPress website that can improve user engagement, answer questions & help generate more leads. Integrated with Google‘s DialogFlow (AI and NLP).', 'qc-opd'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-business-directory/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/icon.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-business-directory/'); ?>" target="_blank"><?php esc_html_e('Simple Business Directory', 'qc-opd'); ?></a></h5>
                            <p><?php esc_html_e('This innovative and powerful, yet', 'qc-opd'); ?><strong> <?php esc_html_e('Simple Multi-purpose Business Directory', 'qc-opd'); ?></strong> <?php esc_html_e('WordPress PlugIn allows you to create 
                            comprehensive Lists of Businesses with maps and tap to call features.', 'qc-opd'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/slider-hero/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/slider-hero-icon-256x256.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/slider-hero/'); ?>" target="_blank"><?php esc_html_e('Slider Hero', 'qc-opd'); ?></a></h5>
                            <p><?php esc_html_e('Slider Hero is a unique slider plugin that allows you to create', 'qc-opd'); ?> <strong><?php esc_html_e('Cinematic Product Intro Adverts', 'qc-opd'); ?></strong>  <?php esc_html_e('and', 'qc-opd'); ?>
                            <strong><?php esc_html_e('Hero sliders', 'qc-opd'); ?></strong> <?php esc_html_e('with great Javascript animation effects.', 'qc-opd'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-link-directory/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/sld-icon-256x256.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-link-directory/'); ?>" target="_blank"><?php esc_html_e('Simple Link Directory', 'qc-opd'); ?></a></h5>
                            <p><?php esc_html_e('Directory plugin with a unique approach! Simple Link Directory is an advanced WordPress Directory plugin for One Page directory and Content Curation solution.', 'qc-opd'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->

                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center" >
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/infographic-maker-ilist/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/iList-icon-256x256.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/infographic-maker-ilist/'); ?>" target="_blank"><?php esc_html_e('InfoGraphic Maker – iList', 'qc-opd'); ?></a></h5>
                            <p><?php esc_html_e('iList is first of its kind', 'qc-opd'); ?> <strong><?php esc_html_e('InfoGraphic maker', 'qc-opd'); ?></strong> <?php esc_html_e('WordPress plugin to create Infographics and elegant Lists effortlessly to visualize data. It is a must have content creation and content curation tool.', 'qc-opd'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->

                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/woocommerce-chatbot-woowbot/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/logo (1).png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/woocommerce-chatbot-woowbot/'); ?>" target="_blank"><?php esc_html_e('WoowBot WooCommerce ChatBot', 'qc-opd'); ?></a></h5>
                            <p><?php esc_html_e('WooWBot is a stand alone WooCommerce Chat Bot with zero configuration or bot training required. This plug and play chatbot also does not require any 3rd party service integration like Facebook.', 'qc-opd'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/woocommerce-shop-assistant-jarvis/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/jarvis-icon-256x256.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/woocommerce-shop-assistant-jarvis/'); ?>" target="_blank"><?php esc_html_e('WooCommerce Shop Assistant', 'qc-opd'); ?></a></h5>
                            <p><?php esc_html_e('WooCommerce Shop Assistant', 'qc-opd'); ?> – <strong><?php esc_html_e('JARVIS', 'qc-opd'); ?></strong> <?php esc_html_e('shows recent user activities, provides advanced search, floating cart, featured products, store notifications, order notifications – all in one place for easy access by buyer and make quick decisions.', 'qc-opd'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/portfolio-x-plugin/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/portfolio-x-logo-dark-2.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/portfolio-x-plugin/'); ?>" target="_blank"><?php esc_html_e('Portfolio X', 'qc-opd'); ?></a></h5>
                            <p><?php esc_html_e('Portfolio X is an advanced, responsive portfolio with streamlined workflow and unique designs and templates to show your works or projects.', 'qc-opd'); ?>&nbsp;<strong>
                            <?php esc_html_e('Portfolio Showcase', 'qc-opd'); ?></strong> <?php esc_html_e('and', 'qc-opd'); ?> <strong><?php esc_html_e('Portfolio Widgets', 'qc-opd'); ?></strong> <?php esc_html_e('are included.', 'qc-opd'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/woo-tabbed-category-product-listing/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/woo-tabbed-icon-256x256.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/woo-tabbed-category-product-listing/'); ?>" target="_blank"><?php esc_html_e('Woo Tabbed Category Products', 'qc-opd'); ?></a></h5>
                            <p><?php esc_html_e('WooCommerce plugin that allows you to showcase your products category wise in tabbed format. This is a unique woocommerce plugin that lets dynaimically load your products in tabs based on your product categories .', 'qc-opd'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/knowledgebase-helpdesk/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/knowledgebase-helpdesk.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/knowledgebase-helpdesk/'); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e('KnowledgeBase HelpDesk', 'qc-opd'); ?></a></h5>
                            <p><p><?php esc_html_e('KnowledgeBase HelpDesk is an advanced Knowledgebase plugin with helpdesk', 'qc-opd'); ?><strong>, </strong><?php esc_html_e('glossary and FAQ features all in one. KnowledgeBase HelpDesk is extremely simple and easy to use.', 'qc-opd'); ?></p></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/express-shop/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/express-shop.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/express-shop/'); ?>" target="_blank"><?php esc_html_e('Express Shop', 'qc-opd'); ?></a></h5>
                            <p><?php esc_html_e('Express Shop is a WooCommerce addon to show all products in one page. User can add products to cart and go to checkout. Filtering and search integrated in single page.', 'qc-opd'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/ichart/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/ichart.jpg" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/ichart/'); ?>" target="_blank"><?php esc_html_e('iChart – Easy Charts and Graphs', 'qc-opd'); ?></a></h5>
                            <p><?php esc_html_e('Charts and graphs are now easy to build and add to any WordPress page with just a few clicks and shortcode generator. iChart is a Google chartjs implementation to add graphs', 'qc-opd'); ?> &amp; 
                            <strong><?php esc_html_e('charts', 'qc-opd'); ?></strong> – <?php esc_html_e('directly from WordPress Visual editor.', 'qc-opd'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/comment-link-remove/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/comment-link-remove.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/comment-link-remove/'); ?>" target="_blank"><?php esc_html_e('Comment Link Remove', 'qc-opd'); ?></a></h5>
                            <p><?php esc_html_e('All in one solution to fight comment spammers. Tired of deleting useless spammy comments from your WordPress blog posts? Comment Link Remove WordPress plugin removes author link and any other links from the user comments.', 'qc-opd'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/bargain-bot/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/bargaining-chatbot.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/bargain-bot/'); ?>" target="_blank"><?php esc_html_e('Bargain Bot', 'qc-opd'); ?></a></h5>
                            <p><?php esc_html_e('Allow shoppers to Make Their Offer Now with a Bargaining Bot. Win more customers with smart price negotiations. Bargain Bot can work with any WooCommerce website in LightBox mode or as an addon for the WoowBot!', 'qc-opd'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/chatbot-addons/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/chatbot-addons.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/chatbot-addons/'); ?>" target="_blank"><?php esc_html_e('ChatBot Addons', 'qc-opd'); ?></a></h5>
                            <p><?php esc_html_e('Empower ', 'qc-opd'); ?><a href="<?php echo esc_url('https://www.quantumcloud.com/products/chatbot-for-wordpress/'); ?>" target="_blank"><?php esc_html_e('WPBot ', 'qc-opd'); ?></a> <?php esc_html_e('and ', 'qc-opd'); ?> <a href="<?php echo esc_url('https://www.quantumcloud.com/products/woocommerce-chatbot-woowbot/'); ?>" target="_blank"> <?php esc_html_e('WoowBot', 'qc-opd'); ?> </a> <?php esc_html_e(' – Extend Capabilities with AddOns! FaceBook messenger, white label and more!', 'qc-opd'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/directory-addons/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/directory-addons.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/directory-addons/'); ?>" target="_blank"><?php esc_html_e('Directory AddOns', 'qc-opd'); ?></a></h5>
                            <p><?php esc_html_e('Empower ', 'qc-opd'); ?><a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-link-directory/'); ?>" target="_blank"><?php esc_html_e('Simple Link Directory ', 'qc-opd'); ?></a> <?php esc_html_e('and ', 'qc-opd'); ?> <a href="<?php echo esc_url('https://www.quantumcloud.com/products/simple-business-directory/'); ?>" target="_blank"> <?php esc_html_e('Simple Business Directory ', 'qc-opd'); ?> </a> <?php esc_html_e(' Pro  – Extend Capabilities with AddOns!', 'qc-opd'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/image-tools-for-wordpress/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/image-tools-pro.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/image-tools-for-wordpress/'); ?>" target="_blank"><?php esc_html_e('Image Tools Pro', 'qc-opd'); ?></a></h5>
                            <p><?php esc_html_e('Image Tools Pro adds an arsenal of ', 'qc-opd'); ?> <b><?php esc_html_e('practical tools', 'qc-opd'); ?></b>  <?php esc_html_e(' for your WordPress Images to make your life easier.', 'qc-opd'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/image-tools-for-wordpress/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/live-chat-wordpress-plugin.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/image-tools-for-wordpress/'); ?>" target="_blank"><?php esc_html_e('Live Chat plugin for WordPress', 'qc-opd'); ?></a></h5>
                            <p><?php esc_html_e('This feature rich, ', 'qc-opd'); ?> <b><?php esc_html_e('native Live Chat plugin for WordPress', 'qc-opd'); ?></b>  <?php esc_html_e('plugin can work with the WPBot or work', 'qc-opd'); ?> <b><?php esc_html_e('stand alone.', 'qc-opd'); ?></b> <?php esc_html_e(' Does not require external server or complex set up.', 'qc-opd'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/support-ticket-for-knowledgebase/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/support-ticket.jpg" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/support-ticket-for-knowledgebase/'); ?>" target="_blank"><?php esc_html_e('WordPress Support Ticket', 'qc-opd'); ?></a></h5>
                            <p><?php esc_html_e('Provide complete helpdesk ticket system on your website. Easy to configure and AJAX based ticket plugin for WordPress.', 'qc-opd'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->
                
                
                <div class="col"><!-- col-sm-4 -->
                    <!-- Feature Box 1 -->
                    <div class="card text-center"  >
                        
                        <a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/premium-themes.png" alt=""></a>
                        <div class="card-body">
                            <h5><a href="<?php echo esc_url('https://www.quantumcloud.com/products/themes/'); ?>" target="_blank"><?php esc_html_e('Premium WordPress Themes', 'qc-opd'); ?></a></h5>
                            <p><b><?php esc_html_e('Premium WordPress Themes', 'qc-opd'); ?></b> <?php esc_html_e('that add perceptible value to your business and website.', 'qc-opd'); ?></p>

                        </div>
                    </div>
                </div><!--/col-sm-4 -->




            </div>
            <!--qc row-->

            <div class="qc-sld-sup_wrap">

                <div class="qc-sld-sup_title">
                    <h3><?php esc_html_e('Available on our ', 'qc-opd'); ?> <a href="<?php echo esc_url('https://www.dna88.com/'); ?>"> <?php esc_html_e('dna88.com', 'qc-opd'); ?> </a> <?php esc_html_e('website', 'qc-opd'); ?></h3>
                </div>
                <div class="row g-0">

                    <div class="col"><!-- col-sm-4 -->
                        <!-- Feature Box 1 -->
                        <div class="card text-center"  >
                            
                            <a href="<?php echo esc_url('https://www.dna88.com/product/button-menu/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/button-menu.png" alt=""></a>
                            <div class="card-body">
                                <h5><a href="<?php echo esc_url('https://www.dna88.com/product/button-menu/'); ?>" target="_blank"><?php esc_html_e('Button Menu', 'qc-opd'); ?></a></h5>
                                <p><?php esc_html_e('Show your WordPress navigation menus anywhere on any page as buttons easily using a shortcode. Supports unlimited sub menu levels with icons, animations and complete control over the colors of the individual icons.', 'qc-opd'); ?></p>

                            </div>
                        </div>
                    </div><!--/col-sm-4 -->
                    
                    <div class="col"><!-- col-sm-4 -->
                        <!-- Feature Box 1 -->
                        <div class="card text-center"  >
                            
                            <a href="<?php echo esc_url('https://www.dna88.com/product/notice-pro/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/notice-pro.png" alt=""></a>
                            <div class="card-body">
                                <h5><a href="<?php echo esc_url('https://www.dna88.com/product/notice-pro/'); ?>" target="_blank"><?php esc_html_e('WordPress Notifications', 'qc-opd'); ?></a></h5>
                                <p><?php esc_html_e('Display Sitewide notices elegantly with beautiful action button. The Notice Pro version supports unlimited, concurrent sitewide notices that can be defined to display for specific user roles on specific pages.', 'qc-opd'); ?></p>

                            </div>
                        </div>
                    </div><!--/col-sm-4 -->
                    
                    <div class="col"><!-- col-sm-4 -->
                        <!-- Feature Box 1 -->
                        <div class="card text-center"  >
                            
                            <a href="<?php echo esc_url('https://www.dna88.com/product/highlight/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/highlight.png" alt=""></a>
                            <div class="card-body">
                                <h5><a href="<?php echo esc_url('https://www.dna88.com/product/highlight/'); ?>" target="_blank"><?php esc_html_e('Highlight Sitewide Notice, Text, Button Menu', 'qc-opd'); ?></a></h5>
                                <p><?php esc_html_e('Add a sitewide notice or small message bar to the top or bottom of each page of your website to display notice messages or notification such as sales, notices, coupons and any text messages.', 'qc-opd'); ?> </p>

                            </div>
                        </div>
                    </div><!--/col-sm-4 -->
                    
                    <div class="col"><!-- col-sm-4 -->
                        <!-- Feature Box 1 -->
                        <div class="card text-center"  >
                            
                            <a href="<?php echo esc_url('https://www.dna88.com/product/video-connect/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/video-connect.png" alt=""></a>
                            <div class="card-body">
                                <h5><a href="<?php echo esc_url('https://www.dna88.com/product/video-connect/'); ?>" target="_blank"><?php esc_html_e('Video Connect', 'qc-opd'); ?></a></h5>
                                <p><?php esc_html_e('Featured Product videos for Woocommerce, Video widget, Videos with contact form 7. Use videos to explain your products or services and connect with your users. All in one Video solution for WordPress.', 'qc-opd'); ?> </p>

                            </div>
                        </div>
                    </div><!--/col-sm-4 -->
                    
                    <div class="col"><!-- col-sm-4 -->
                        <!-- Feature Box 1 -->
                        <div class="card text-center"  >
                            
                            <a href="<?php echo esc_url('https://www.dna88.com/product/seo-help-pro/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/seo-help.png" alt=""></a>
                            <div class="card-body">
                                <h5><a href="<?php echo esc_url('https://www.dna88.com/product/seo-help-pro/'); ?>" target="_blank"><?php esc_html_e('SEO Help', 'qc-opd'); ?></a></h5>
                                <p><?php esc_html_e('SEO Help is a unique WordPress plugin to help you write better Link Bait titles. The included LinkBait title generator will take the WordPress post title as Subject and generate alternative ClickBait titles for you to choose from.', 'qc-opd'); ?></p>

                            </div>
                        </div>
                    </div><!--/col-sm-4 -->
                    
                    <div class="col"><!-- col-sm-4 -->
                        <!-- Feature Box 1 -->
                        <div class="card text-center"  >
                            
                            <a href="<?php echo esc_url('https://www.dna88.com/product/voice-widgets/'); ?>" target="_blank"> <img src="<?php echo qcld_support_img_url ?>/voice-widgets-for-wordPress.png" alt=""></a>
                            <div class="card-body">
                                <h5><a href="<?php echo esc_url('https://www.dna88.com/product/voice-widgets/'); ?>" target="_blank"><?php esc_html_e('Voice Widgets', 'qc-opd'); ?></a></h5>
                                <p><?php esc_html_e('Get voice messages with your forms and increase user conversions with Voice widgets. Record voice messages with your WordPress forms – CF7, WPForms, BBPress, Blog Comments, and Woocommerce Product Reviews. Supports standalone voice form.', 'qc-opd'); ?> </p>

                            </div>
                        </div>
                    </div><!--/col-sm-4 -->

                </div><!--/row -->
                
            </div>

        </div>



			
		
<?php
            
       
    }
}


/*******************************
 * Handle Ajex Request for Form Processing
 *******************************/
add_action( 'wp_ajax_qc_sld_process_qc_promo_form', 'qc_sld_process_qc_promo_form' );

if( !function_exists('qc_sld_process_qc_promo_form') ){

    function qc_sld_process_qc_promo_form(){

        check_ajax_referer( 'qc-opd', 'security');
        
        $data['status']   = 'failed';
        $data['message']  = esc_html__('Problem in processing your form submission request! Apologies for the inconveniences.<br> 
Please email to <span style="color:#22A0C9;font-weight:bold !important;font-size:14px "> quantumcloud@gmail.com </span> with any feedback. We will get back to you right away!', 'qc-opd');

        $name         = isset($_POST['post_name']) ? trim(sanitize_text_field($_POST['post_name'])) : '';
        $email        = isset($_POST['post_email']) ? trim(sanitize_email($_POST['post_email'])) : '';
        $subject      = isset($_POST['post_subject']) ? trim(sanitize_text_field($_POST['post_subject'])) : '';
        $message      = isset($_POST['post_message']) ? trim(sanitize_text_field($_POST['post_message'])) : '';
        $plugin_name  = isset($_POST['post_plugin_name']) ? trim(sanitize_text_field($_POST['post_plugin_name'])) : '';

        if( $name == "" || $email == "" || $subject == "" || $message == "" )
        {
            $data['message'] = esc_html('Please fill up all the requried form fields.', 'qc-opd');
        }
        else if ( filter_var($email, FILTER_VALIDATE_EMAIL) === false ) 
        {
            $data['message'] = esc_html('Invalid email address.', 'qc-opd');
        }
        else
        {

            //build email body

            $bodyContent = "";
                
            $bodyContent .= "<p><strong>".esc_html('Support Request Details:', 'qc-opd')."</strong></p><hr>";

            $bodyContent .= "<p>".esc_html('Name', 'qc-opd')." : ".$name."</p>";
            $bodyContent .= "<p>".esc_html('Email', 'qc-opd')." : ".$email."</p>";
            $bodyContent .= "<p>".esc_html('Subject', 'qc-opd')." : ".$subject."</p>";
            $bodyContent .= "<p>".esc_html('Message', 'qc-opd')." : ".$message."</p>";

            $bodyContent .= "<p>".esc_html('Sent Via the Plugin', 'qc-opd')." : ".$plugin_name."</p>";

            $bodyContent .="<p></p><p>".esc_html('Mail sent from:', 'qc-opd')." <strong>".get_bloginfo('name')."</strong>, URL: [".get_bloginfo('url')."].</p>";
            $bodyContent .="<p>".esc_html('Mail Generated on:', 'qc-opd')." " . date("F j, Y, g:i a") . "</p>";           
            
            $toEmail = "quantumcloud@gmail.com"; //Receivers email address
            //$toEmail = "qc.kadir@gmail.com"; //Receivers email address

            //Extract Domain
            $url = get_site_url();
            $url = parse_url($url);
            $domain = $url['host'];
            

            $fakeFromEmailAddress = "wordpress@" . $domain;
            
            $to = $toEmail;
            $body = $bodyContent;
            $headers = array();
            $headers[] = 'Content-Type: text/html; charset=UTF-8';
            $headers[] = 'From: '.esc_attr($name, 'qc-opd').' <'.esc_attr($fakeFromEmailAddress, 'qc-opd').'>';
            $headers[] = 'Reply-To: '.esc_attr($name, 'qc-opd').' <'.esc_attr($email, 'qc-opd').'>';

            $finalSubject = esc_html('From Plugin Support Page:', 'qc-opd')." " . esc_attr($subject, 'qc-opd');
            
            $result = wp_mail( $to, $finalSubject, $body, $headers );

            if( $result )
            {
                $data['status'] = 'success';
                $data['message'] = esc_html__('Your email was sent successfully. Thanks!', 'qc-opd');
            }

        }

        ob_clean();

        
        echo json_encode($data);
    
        die();
    }
}