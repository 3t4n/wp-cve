<h2 class="nav-tab-wrapper sengrid-settings-nav-bar">
  <?php
    foreach ( $sg_tabs as $tab_key => $tab_description ) {
      if ( $active_tab == $tab_key ) {
        echo '<a href="?page=sendgrid-settings&tab=' . urlencode($tab_key) . '" class="nav-tab nav-tab-active">' . esc_html($tab_description) . '</a>';
      } else {
        echo '<a href="?page=sendgrid-settings&tab=' . urlencode($tab_key) . '" class="nav-tab">' . esc_html($tab_description) . '</a>';
      }
    }
  ?>
</h2>