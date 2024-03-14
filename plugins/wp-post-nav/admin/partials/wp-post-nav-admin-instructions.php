<?php
/**
 * The file used to show the settings on the screen
 *
 * @link:      https://en-gb.wordpress.org/plugins/wp-post-nav/
 * @since      0.0.1
 *
 * @package    wp_post_nav
 * @subpackage wp_post_nav/admin/partials
 */
// If this file is called directly, abort. //
if ( ! defined( 'ABSPATH' ) ) {
  exit;
} 
ob_start(); ?>

<div id="wp-post-nav-instructions">
  <h3>General Usage Instructions</h3>
  <p>Using WP Post Nav is really easy and can be acomplished in 3 easy steps.</p>
  <ul>
    <li>Select which post type(s) the navigation will show.</li>
    <li>Choose whether the navigation will select posts from the same category as the current post.</li>
    <li>Modify the navigation styles to fit your website.</li>
  </ul>

  <hr>

  <?php
  //yoast or woo detected
  if ( class_exists('WPSEO_Primary_Term') || class_exists ('woocommerce') ||  function_exists( 'the_seo_framework' )) {
    ?>
    <h3>Additional Options</h3>
    <?php
  }
  
  //if yoast
  if ( class_exists('WPSEO_Primary_Term')) {
    ?>
    <h4>Yoast SEO Detected</h4>
    <p>We have detected that you have Yoast SEO installed and active.  Yoast SEO offers a feature that allows you to select a 'primary' category for your post categories.</p>
    <p>If you select the option to use the Yoast SEO primary category, the navigation will select posts from this category only and will override the option for whether you request posts to be in the same category or not.</p> 
    <?php 
  }

  //if yoast
  if ( function_exists( 'the_seo_framework' )) {
    ?>
    <h4>SEO Framework Detected</h4>
    <p>We have detected that you have the SEO Framework installed and active. SEO Framework offers a feature that allows you to select a 'primary' category for your post categories.</p>
    <p>If you select the option to use the primary category, the navigation will select posts from this category only and will override the option for whether you request posts to be in the same category or not.</p>
    
    <?php 
  }

  if (class_exists('WPSEO_Primary_Term') || function_exists( 'the_seo_framework' )) {
    ?>
    <h4>BE CAREFUL WHEN USING THIS OPTION!</h4>
    <p>Using the option for utilising the Primary Category available has some limitations AND some issues. It is advisable to ensure that you either use primary categories across ALL posts, or not use them at all. It is possible to skip from category to category by not using the correct format in your posts and it is impossible for anyone to create something to stop this - IT MUST BE DONE ON YOUR SITE with the correct structure of your posts</p>
    <p>If a primary term isn't set on a post, default functionality resumes so it's important to either use primary terms or not across the whole site.</p>
    <p>If you have issues with this setting - which is especially true with WooCommerce Products where you have a multitide of variations and options, it's advisable to NOT use this setting and revert to the standard category exclusions.</p>
    
    <?php 
  }

  //if woo
  if ( class_exists( 'woocommerce' ) ) {
    ?>
    <h4>WooCommerce Detected</h4>
    <p>We have detected that you have WooCommerce installed and active.  WooCommerce offers a feature for having products 'out of stock'.</p>
    <p>If you select the option to NOT display out of stock products, the navigation will exclude these products from the next / previous posts, AND if the user lands on a product which is out of stock, the navigation will not show.</p>
    <?php
  }
  ?>

  <?php
  //yoast or woo detected
  if ( class_exists('WPSEO_Primary_Term') || class_exists ('woocommerce')) {
    ?>
    <hr>
    <?php
  }

  ?>

  <h3>Developer Options</h3>
  <p>Details of the available hooks / options are included in the examples.txt file in the root of the plugin folder.</p>

  <p class="wp-post-nav-highlight"><span>Developers!</span> attachments are automatically hidden from the post type display.  You can modify the array of post types by using the built in 'wp-post-nav-post-type' filter (usage instructions are inside the 'examples.txt' file).</p>

  <p>The following hooks are available :</p>

  <table>
    <tr>
      <th class="hook">Hook</th>
      <th class="purpose">Hook Purpose</th>
      <th class="reason">What Is This For</th>
    </tr>
    <tr>
      <td class="wp-post-nav-centered"><strong>wp-post-nav-post-type</strong></td>
      <td>Alters the array of post types to add or remove available options</td>
      <td>Often plugin / theme developers create post types which are never used on the front end (for exmaple elementor templates etc).  Using this hook means you can hide these post types from the admin</td>
    </tr>
    <tr>
      <td class="wp-post-nav-centered"><strong>wp-post-nav-excerpt</strong></td>
      <td>Use the built in the_excerpt() function</td>
      <td>To allow users to modify the length of the excerpt on the navigation, we create our own excerpt from the content.  For some websites / themes where the pages are made up of shortcodes and custom content, this will not work correctly.  In order to use WP Post Nav, simply '__return_false' this function to use the Wordpress built in excerpt function.  To then adjust the excerpt length, you will need to use the Wordpress filter <a href="https://developer.wordpress.org/reference/hooks/excerpt_length/">'excerpt_length'</a> to adjust the excerpt length.</td> 
      
    </tr>
    <tr>
      <td class="wp-post-nav-centered"><strong>gettext (WordPress built in function)</strong></td>
      <td>Translate the plugin</td>
      <td>Use this function to make text modifications and translate the text on the front end.  Look at the examples.txt file but you can now translate the wording on the NAV.</td> 
    </tr>
  </table>
</div>

<?php
    $output = ob_get_clean();
    return $output;
