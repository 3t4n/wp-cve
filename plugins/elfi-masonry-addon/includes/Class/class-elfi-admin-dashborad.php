<?php
/**
 * The file that defines the bulk print admin area
 *
 * public-facing side of the site and the admin area.
 *
 * @link       https://sharabindu.com
 * @since      1.0.7
 *
 * @package    elfi-masonry-addon_pro
 * @subpackage elfi-masonry-addon_pro/admin
 */

class Elfi_admin_dashborad_Light
{

    public function __construct()
    {

        add_action('admin_menu', array(
            $this,
            'admin_menu_define'
        ));

        add_action("admin_enqueue_scripts",array(
            $this, 'enqueue_styles' ));
        add_action('admin_init', array(
            $this,'elfi_plugin_redirect'));


        add_filter( 'plugin_action_links_' . ELFI_BASENAME_LIGHT, array(
            $this,'add_action_links' ));



    }
    public function elfi_plugin_redirect() {

    if (get_option('elfi_plugin_do_activation_redirect', false)) {
        delete_option('elfi_plugin_do_activation_redirect');
        if(!isset($_GET['activate-multi']))
        {
            wp_redirect("edit.php?post_type=elfi&page=home");
        }
     }
    }



    public function add_action_links($links)
    {

        return array_merge(array(
            '<a class="yhow_yse" href="' . admin_url('edit.php?post_type=elfi&page=home') . '">' . __('How To Use', 'elfi-masonry-addon') . '</a>',
            '<a class="elfi_pro_link" href="https://sharabindu.com/plugins/elfi/">' . __('Go Pro', 'elfi-masonry-addon') . '</a>',
        ) , $links);

    }



    public function enqueue_styles()
    {
    wp_enqueue_style('google-font', ELFI_URL_LIGHT . '/assets/fonts/stylesheet.css', array() , "3.1", 'all');
    wp_enqueue_style('elfi-admin-settings', ELFI_URL_LIGHT . '/assets/css/elfi-admin-settings.css', array() , "3.1", 'all');
}

    function elfi__settting_func()
    {

        return;
    }

    public function admin_menu_define()
    {

        add_submenu_page('edit.php?post_type=elfi', __('How it works', 'elfi-masonry-addon') , __('How it works', 'elfi-masonry-addon') , 'manage_options', 'home', array(
            $this,
            'how_to_use'
        ));

    }

    function how_to_use()
    {

?>

     <div class="yoobaradmin__codewrap">
      <div class="yooba_wp_admin">
         <ul class="yoobaradmin__nav_bar">
            <li><a href="https://elfi.sharabindu.com/wp/" target="_blank"><?php echo esc_html('View Demo', 'elfi-masonry-addon') ?></a></li>
            <li><a href="https://elfi.sharabindu.com/wp/docs/introduction/" target="_blank"><?php echo esc_html('Docs', 'elfi-masonry-addon') ?></a></li>
            <li><a href="https://sharabindu.com/plugins/" target="_blank"><?php echo esc_html('More Plugin', 'elfi-masonry-addon') ?></a></li>
            <li><a href="https://sharabindu.com/plugins/elfi/" target="_blank"><?php echo esc_html('Get Premium', 'elfi-masonry-addon') ?></a></li>
         </ul>

      </div>
        <div class="tirmoof" >
          <div class="yoobar_howku" style="background:#fff;background-size:cover;width:100%;">
          <ul  class="yoobaradmin__hdaer_cnt">
             <li> 
              
              <img src=" <?php echo ELFI_URL_LIGHT . '/assets/img/icon-256x256-min.png' ?>" alt="elfi logo" class="elfilogo"></li>
             <li  class="yoobaradmin__fd_cnt">
              <h3 style="color:#fff"><?php echo esc_html('ELFI - V:', 'elfi-masonry-addon') . ' ' . ELFI_VERSION_LIGHT; ?> </h3>
                <small><?php echo esc_html('Masonry filter addon for Elementor', 'elfi-masonry-addon') ?></small>
             </li>
          </ul>
          <ul class="thsnlot">
         <li><?php echo esc_html('Thanks a lot', 'elfi-masonry-addon') ?></li>   
         <li><?php echo esc_html('for choosing ElFI Masonry Filter', 'elfi-masonry-addon') ?></li>   

          </ul>
       </div>


      <div class="yoobar-feature">
           <img class='yoo_dcs_img' src=" <?php echo ELFI_URL_LIGHT . '/assets/img/aaaaaaaa.png' ?>"> 
        <h3 class="yoobar-feature-title"><?php echo esc_html('What is in this addon?', 'elfi-masonry-addon') ?></h3>
        <p class="feature-title18"><?php echo esc_html('This is a Addon for Elementor page builder.  so you need an Elementor plugin (Both free /pro versions) to enable these plugin features.  Through this addon, you can create a showcase with a portfolio, product, or post items and set a category-based filter button. You can also create gallery items using custom images. You will get two widgets in this ELFI addon, one is filter Masonry and another is gallery Masonry. Filter Masonry works on a type basis and gallery Masonry can be created from the Elementor editor page.', 'elfi-masonry-addon') ?></p>


 

      </div>

      <div class="yoobar-feature">
        <ul>
          <li class="yoobar-feature_6" style="left:0">

        <h3 class="yoobar-feature-title"><?php echo esc_html('Filter Masonry', 'elfi-masonry-addon') ?> </h3>

                <p class="feature-title18"><?php echo esc_html('Filter masonry is a widget that lets you create filters based on categories. Suppose you create a filter item for products. Now select the category which will be used as the filter button and the products will be displayed according to the category', 'elfi-masonry-addon') ?> </p>
          <img src=" <?php echo ELFI_URL_LIGHT . '/assets/img/flsds.png' ?>">




        </li>  
        </ul>
<div>
    
    <iframe width="560" height="315" src="https://www.youtube.com/embed/nXSVlFIwq4Y" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
</div>

      </div>
      <div class="yoobar-feature">
        <ul>
          <li class="yoobar-feature_6" style="left:0">

        <h3 class="yoobar-feature-title"><?php echo esc_html('Gallery Masonry', 'elfi-masonry-addon') ?> </h3>

                <p class="feature-title18"><?php echo esc_html('Gallery Masonry is a widget that allows you to create an image showcase. It doesn\'t depend on the post type, you can add images from the Elementor editor page', 'elfi-masonry-addon') ?> </p>
          <img src=" <?php echo ELFI_URL_LIGHT . '/assets/img/gallery.png' ?>">

        </li>  


        </ul>
                <div>
            
            <iframe width="560" height="315" src="https://www.youtube.com/embed/JeCBGGw5Po4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        </div>
      </div>
      <div class="yoobar-feature">
        <ul>
          <li class="yoobar-feature_6 fswr">
           <img class='yoo_dcs_img' src=" <?php echo ELFI_URL_LIGHT . '/assets/img/question.svg' ?>"> 

        <h3 class="yoobar-feature-title"><?php echo esc_html('FAQ', 'elfi-masonry-addon') ?> </h3>
        <p class="feature-title18"><?php echo esc_html('Frequently Asked Questions', 'elfi-masonry-addon') ?></p>

        <div class="yoofaqdes">
      <h4><?php echo esc_html('Will this addon work on Pro Elementor', 'elfi-masonry-addon') ?></h4>
      <p><?php echo esc_html('Yes, it will work on both Pro and free elementor version. Use it to bring colorful moments to your site. And don’t forget to check out our premium features.', 'elfi-masonry-addon') ?></p>
      </div>
      <div class="yoofaqdes">
      <h4><?php echo esc_html('Is there any support available for the free users?', 'elfi-masonry-addon') ?></h4>
      <p><?php echo esc_html('Both the free and pro versions bring great support from us. However Pro users will get priority support.', 'elfi-masonry-addon') ?></p>
      </div>


        </li>
        <li style="width:400px"></li>
        </ul>

      </div>

      <div class="yoobar-feature">
        <ul>
          <li class="yoobar-feature_6" style="text-align: center;">
           <img class='yoo_dcs_img' src=" <?php echo ELFI_URL_LIGHT . '/assets/img/support.svg' ?>"> 

        <h3 class="yoobar-feature-title"><?php echo esc_html('Support', 'elfi-masonry-addon') ?></h3>
        <p class="feature-title18"><?php echo esc_html('Feeling like consulting an expert? Get our live chat support. We are always ready to help you.', 'elfi-masonry-addon') ?></p>

        <div>
          <a class="ydocsbutn" href="https://sharabindu.com/contact-us"><?php echo esc_html('Get Support', 'elfi-masonry-addon') ?></a>
          </div>

        </li>
        <li style="width:400px"></li>
        </ul>

      </div>

      <div class="yoobar-feature">
        <ul>
          <li class="yoobar-feature_6">

        <h3 class="yoobar-feature-title"><?php echo esc_html('Missing Any Features', 'elfi-masonry-addon') ?></h3>
        <p style="width:90%"><?php echo esc_html('Do you need any features that we don\'t have in our plugin? Let us know Feel free to do a request from here', 'elfi-masonry-addon') ?></p>
        <a class="ydocsbutn" href="https://sharabindu.com/what-features-want-to-see/" style="background: #e2498a"><?php echo esc_html('Request Feature', 'elfi-masonry-addon') ?></a>
        </li>
        <li><img src=" <?php echo ELFI_URL_LIGHT . '/assets/img/missing.jpg' ?>"></li>

        </ul>

      </div>
      <div class="yoobar-feature">
        <div id="yooshado">
        <ul>

          <li><img src=" <?php echo ELFI_URL_LIGHT . '/assets/img/review.svg' ?>"></li>
          <li class="yoobar-feature_67">

        <h3 class="yoobar-feature-title"><?php echo esc_html('Happy with Our Plugin?', 'elfi-masonry-addon') ?></h3>
        <p class="feature-title18"><?php echo esc_html('We are really grateful that you have chosen our plugin. If you like our plugin, please share your happiness by giving us a 5star rating in WordPress Org.', 'elfi-masonry-addon') ?></p>
        <a class="ydocsbutn" href="https://wordpress.org/plugins/elfi-masonry-addon/#reviews"><?php echo esc_html('Give us 5', 'elfi-masonry-addon') ?>*</a>
        </li>
        </ul>
        </div>
      </div>
      </div>
    </div>


    <?php
    }

}

if (class_exists('Elfi_admin_dashborad_Light'))
{
    new Elfi_admin_dashborad_Light;
};

