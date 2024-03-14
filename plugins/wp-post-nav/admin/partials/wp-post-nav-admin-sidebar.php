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
?>

<?php
// If this file is called directly, abort. //
if ( ! defined( 'ABSPATH' ) ) {
  exit;
} 
?>

<div id="wp-post-nav-sidebar">
  <div class="wp-post-nav-centered">
    <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . '/images/logo.png';?>" alt="WP Post Nav" width="100" height="auto">
  </div>
  <div id="wp-post-nav-about">

    <?php
      $plugin_path = $my_plugin = WP_PLUGIN_DIR . '/' . $this->name;
      $plugin_info = get_plugin_data($plugin_path . '/wp-post-nav.php');
    ?>

    <h3 class="wp-post-nav-centered">Plugin Information</h3>
    <ul>
      <li>Plugin Version: <?php echo $plugin_info['Version'];?></li>
      <li>Plugin Author: <?php echo ucfirst($plugin_info['Author']);?></li>
      <li>Plugin Url : <a href="<?php echo $plugin_info['PluginURI'];?>">View On WordPress</a></li>
    </ul>
    <hr>

    <p>Thank you for installing WP Post Nav.</p>
    
    <div id="wp-post-nav-review">
      <h4>Leave A Review</h4>
      <p>Please support WP Post Nav by leaving a review on the WordPress Plugin page.</p>
      <p>A review helps other users find the best plugins for their site, and in turn, shows your support for the plugin developer.</p>
      <button id="review-button" class="button-primary"><a href="https://wordpress.org/support/plugin/wp-post-nav/reviews/">Leave A Review</a></button>
    </div>

    <div id="wp-post-nav-support">
      <h4>Get Support</h4>
      <p>Got a support question or issue with WP Post Nav?</p>
      <p>Ask a question in the WordPress Support forum</p>
      <button id="support-button" class="button-secondary"><a href="https://wordpress.org/support/plugin/wp-post-nav/" >Get Support</a></button>
    </div>
  </div>
</div>