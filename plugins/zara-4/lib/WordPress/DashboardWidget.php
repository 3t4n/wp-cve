<?php
if ( ! class_exists( 'Zara4_WordPress_DashboardWidget' ) ) {


  /**
   * Class Zara4_WordPress_DashboardWidget
   */
  class Zara4_WordPress_DashboardWidget {


    public static function zara4_widget() {

      // Read Zara 4 settings
      $settings = new Zara4_WordPress_Settings();

      // Check if the dashboard widget is enabled
      if ($settings->dashboard_widget_enabled()) {

        //
        // Construct the dashboard widget
        //
        wp_add_dashboard_widget('zara4_widget', 'Zara 4', function() use ($settings) {

          $zara4_api_base = ZARA4_DEV ? 'http://api.zara4.dev' : 'https://api.zara4.com';

          $api_client_id     = $settings->api_client_id();
          $api_client_secret = $settings->api_client_secret();

          try {
            $access_token = Zara4_WordPress_Zara4::generate_access_token( $api_client_id, $api_client_secret );
          } catch ( Zara4_API_Communication_AccessDeniedException $e ) {
            $access_token = null;
          }

          // --- --- --- --- --- --- --- --- --- --- --- --- --- ---- --- --- --- --- --- --- --- --- --- --- --- ---

          //
          // Zara 4 show dashboard
          //
          if ($access_token): ?>
            <div class="zara-4 dashboard-widget">
              <iframe style="height: 300px; width: 100%" src="<?php echo Zara4_API_Communication_Config::BASE_URL() ?>/v1/view/user/usage-graph?access_token=<?php echo $access_token ?>"></iframe>

              <div class="zara-4-summary">
                <table>
                  <tr>
                    <td style="width: 80px"><b>Name :</b></td>
                    <td><span id="zara4-widget-name"></span></td>
                  </tr>
                  <tr>
                    <td><b>Email :</b></td>
                    <td><span id="zara4-widget-email"></span></td>
                  </tr>
                  <tr>
                    <td><b>Allowance :</b></td>
                    <td><span id="zara4-widget-allowance"></span></td>
                  </tr>
                </table>
              </div>

              <div>
                <a class="button button-primary" href="/wp-admin/upload.php">Compress Images</a>
                <a class="button button-default" href="<?php echo admin_url( 'options-general.php?page=zara-4' ) ?>">Settings</a>
              </div>
            </div>


            <script>
              function prettyBytes(bytes) {
                var units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
                var divisions = 0;
                while(bytes >= 1024) {
                  bytes /= 1024;
                  divisions++;
                }
                return parseFloat(bytes).toFixed(2) + ' ' + units[divisions];
              }

              jQuery(document).ready(function($) {
                $.ajax({
                  type: 'GET',
                  url: "<?php echo $zara4_api_base ?>/v1/user",
                  data: {
                    'access_token': "<?php echo $access_token ?>"
                  },
                  crossDomain: true,
                  success: function(data) {
                    var name = data['name'];
                    var email = data['email'];
                    var totalRemaining = data['allowances']['total-remaining'];

                    $('#zara4-widget-name').html(name);
                    $('#zara4-widget-email').html(email);
                    $('#zara4-widget-allowance').html(prettyBytes(totalRemaining));
                  },
                  error: function() {

                  }
                });
              });
            </script>
          <?php
          // --- --- --- --- --- --- --- --- --- --- --- --- --- ---- --- --- --- --- --- --- --- --- --- --- --- ---

          //
          // Zara 4 needs setting up - show setup message
          //
          else: ?>
            <div id="warning-message" class="zara-4 alert-boxed alert alert-danger">
              <b>Zara 4</b> needs to be <a href="<?php echo admin_url( 'options-general.php?page=zara-4' ) ?>">setup</a> before you can compress images
            </div>

            <a class="button button-primary" href="<?php echo admin_url( 'options-general.php?page=zara-4' ) ?>">Continue Setup ></a>
          <?php endif; ?>

        <?php
        });
      }
    }

  }

}