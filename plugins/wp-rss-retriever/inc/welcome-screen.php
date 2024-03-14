<?php

add_action( 'admin_init', 'wp_rss_retriever_do_activation_redirect' );
function wp_rss_retriever_do_activation_redirect() {
  // Bail if no activation redirect
    if ( ! get_transient( '_wp_rss_retriever_activation_redirect' ) ) {
    return;
  }

  // Delete the redirect transient
  delete_transient( '_wp_rss_retriever_activation_redirect' );

  // Bail if activating from network, or bulk
  if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
    return;
  }

  // Redirect to plugin welcome page
  wp_safe_redirect( add_query_arg( array( 'page' => 'wp-rss-retriever-welcome' ), admin_url( 'index.php' ) ) );

}

add_action('admin_menu', 'wp_rss_retriever_pages');

function wp_rss_retriever_pages() {
  add_dashboard_page(
    'Welcome To RSS Feed Retriever',
    'WordPress RSS Feed Retriever',
    'read',
    'wp-rss-retriever-welcome',
    'wp_rss_retriever_welcome'
  );
  add_dashboard_page(
    'Welcome To RSS Feed Retriever',
    'WordPress RSS Feed Retriever',
    'read',
    'wp-rss-retriever-examples',
    'wp_rss_retriever_examples'
  );
}

function wp_rss_retriever_pro_ad() { 
  ?>
    <div class="col" style="width:350px; margin:50px 0 0 50px;">
      <a href="https://thememason.com/plugins/rss-retriever/?ref=wp_rss_retriever_welcome" target="_blank" title="WP RSS Retriever PRO">
        <img src="<?php echo plugin_dir_url( __FILE__ ) ?>imgs/rss-go-pro.jpg" width="300" height="600"/>
      </a>
    </div>
  <?php 
}

function wp_rss_retriever_welcome() {
    wp_rss_retriever_welcome_header();
    ?>
    <div class="feature-section full-width-layout" style="display:flex;">
      <div class="col" style="width:100%"> 
        <h2>Video Tutorial</h2>
        <p style="font-size:12px; text-align:center; font-style:italic;">Note: If the video appears blurry, click on the gear icon in the lower right hand corner and select <strong>Quality > 720p</strong>. </br>Then press <strong>f</strong> for full screen.</p>
        <iframe width="100%" height="420px" src="https://www.youtube.com/embed/2EPdD65zS5U?rel=0&hq=1&vq=hd720" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        <h3>Example Shortcode</h3>
        <p>[wp_rss_retriever url="http://feeds.feedburner.com/TechCrunch/" items="10" excerpt="50" read_more="true" credits="true" new_window="true" thumbnail="200" cache="12 hours"]</p>      

        <h3>Demo</h3>
        <p><a target="_blank" href="https://demo.thememason.com/rss/">Click here to view the demo</a></p>
        <h3>Properties:</h3>
        <ul>
          <li><strong>url</strong> - The url of the rss feed you wish to aggregate from. For multiple urls simply use a comma between them.</li>

          <li><strong>items</strong> - Number of items from the rss feed you wish to fetch <em>(Default is 10)</em></li>

          <li><strong>orderby</strong> - Order the items by date, reverse date, or random <em>(default, date, date_reverse, random)</em></li>

          <li><strong>title</strong> - Whether to display the title or not <em>(true or false, defaults to true)</em></li>

          <li><strong>excerpt</strong> - How many words you want to display for each item <em>(Default is 20, use 0 for full text, use 'none' to remove the excerpt)</em></li>

          <li><strong>read_more</strong> - Whether to display a read more link or not <em>(true or false, defaults to true)</em></li>

          <li><strong>new_window</strong> - Whether to open the title link and read more link in a new window <em>(true or false, defaults to true)</em></li>

          <li><strong>thumbnail**</strong> - Whether or not you want to display a thumbnail, and if so, what size you want it to be<em>(true or false, defaults to true. Inserting a number will change the size, default is 150, use 150x200 format to set both width and height, use percents to fill the width, example: 100%x250 or 50%x250)</em></li>

          <li><strong>source</strong> - Whether to display the source or not <em>(true or false, defaults to true)</em></li>

          <li><strong>date</strong> - Whether to display the publish date or not <em>(true or false, defaults to true)</em></li>

          <li><strong>cache</strong> - How long you want the feed to cache the results <em>(Default is 12 hours, you can use days, hours, seconds etc.)</em></li>

          <li><strong>dofollow</strong> - Whether or not to make links dofollow <em>(true or false, defaults to false)</em></li>

          <li><strong>ajax</strong> - Whether to load the feed via JavaScript or PHP <em>(true or false, defaults to true)</em></li>

          <li><strong>credits</strong> - Whether to give credit to the plugin author <em>(true or false, defaults to false)</em></li>
          
          <li><strong>columns</strong> - Set layout to columns/grid layout with number of columns. <em>(defaults to 0, use 2, 3, 4 etc.) **PRO version only**</em></li>
          
          <li><strong>icons</strong> - Replace source & date labels with icons <em>(true or false, defaults to true)  **PRO version only**</em></li>
        </ul>

      </div>
      <?php wp_rss_retriever_pro_ad(); ?>
    </div>
  </div>
  <?php
}

function wp_rss_retriever_examples() {
    wp_rss_retriever_welcome_header();
    ?>

    <div class="feature-section full-width-layout" style="display:flex;">
      <div class="col" style="width:100%">
        <h2>Example Shortcode</h2>
        <p>[wp_rss_retriever url="https://wordpress.org/news/feed/" items="10" excerpt="50" read_more="true" credits="true" new_window="true" thumbnail="200" cache="7200"]</p>

        <h3>Features:</h3>
        <ul>
          <li>Fetch as many RSS feeds as you want</li>
          <li>Display the RSS feed wherever you want using shortcode, including text widgets</li>
          <li>Control whether to display the entire RSS feeds content or just an excerpt</li>
          <li>Control how many words display in the excerpt</li>
          <li>Control whether it has a Read more link or not</li>
          <li>Control whether links open in a new window or not</li>
          <li>Simple, lightweight, and fast</li>
          <li>Easy to setup</li>
          <li>Fetch thumbnail or first image</li>
          <li>Control size of thumbnail (width and height)</li>
          <li>Set cache time (in seconds)</li>
          <li>Control order of items</li>
          <li>Aggregate multiple feeds into one list</li>
          <li>Dofollow or nofollow options</li>
        </ul>
      </div>
      <?php wp_rss_retriever_pro_ad(); ?>
  </div>
  <?php
}


function wp_rss_retriever_welcome_header() {
  $screen = get_current_screen();
  ?>
  <div class="wrap about-wrap full-width-layout">
    <h1>Welcome to RSS Feed Retriever v<?php echo WP_RSS_RETRIEVER_VER; ?></h1>

    
    <p class="about-text">
      The fastest RSS plugin for WordPress. Use the RSS shortcode below to fetch and display an RSS feed including thumbnails and excerpts. <a target="_blank" href="https://thememason.com/plugins/rss-retriever/?ref=welcome_header_learn_more" title="WordPress RSS Feed Retriever">Learn more</a>
    </p>
    <!--  <div class="wp-badge" style="background-color: #282828; background-image:url(<?php echo plugin_dir_url( __FILE__ ) . 'imgs/rss-icon.svg'; ?>)">Version <?php echo WP_RSS_RETRIEVER_VER; ?></div> -->
    
    <h2 class="nav-tab-wrapper wp-clearfix">
      <a href="<?php echo admin_url( 'index.php?page=wp-rss-retriever-welcome') ?>" class="nav-tab<?php echo ($screen->id == 'dashboard_page_wp-rss-retriever-welcome' ? ' nav-tab-active' : ''); ?>">Get Started</a>
      <a href="<?php echo admin_url( 'index.php?page=wp-rss-retriever-examples') ?>" class="nav-tab<?php echo ($screen->id == 'dashboard_page_wp-rss-retriever-examples' ? ' nav-tab-active' : ''); ?>">Examples</a>
    </h2>
  <?php
}

add_action( 'admin_head', 'wp_rss_retriever_remove_menus', 999 );
function wp_rss_retriever_remove_menus() {
    remove_submenu_page( 'index.php', 'wp-rss-retriever-welcome' );
    remove_submenu_page( 'index.php', 'wp-rss-retriever-examples' );
}
