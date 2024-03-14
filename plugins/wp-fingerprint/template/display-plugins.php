<div class="wrap">
        <h2>File Integirty Checks</h2>
        <div id="quick-glance">
          <div id="quick-glance-errors">
            <<?php echo $quickglance['errors'] ?>
          </div>
          <div id="quick-glance-warnings">
            <<?php echo $quickglance['warnings'] ?>
          </div>
          <div id="quick-glance-ok">
            <<?php echo $quickglance['warnings'] ?>
          </div>
        </div>
        <div id="last-check">
          WP Fingerprint last checked on: <?php echo $last_check ?>
        </div>
      </div>
      <div id="plugin-table">
        <?php
          $plugin_table = new WPFingerprint_Settings_Table();
          $plugin_table->prepare_items();
          $plugin_table->display();
        ?>
      </div>
</div>
