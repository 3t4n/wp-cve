<?php
if ( ! class_exists( 'Zara4_WordPress_View' ) ) {


  /**
   * Class Zara4_WordPress_View
   */
  class Zara4_WordPress_View {



    /**
     * Triggered by 'wp_loaded' action - check for Zara 4 factory reset
     */
    public static function factory_reset_hook() {

      // Check if admin page plugin
      if (strpos($_SERVER['REQUEST_URI'], 'options-general.php') !== false) {

        // Check is Zara 4 Settings page
        if ( isset( $_GET['page'] ) && $_GET['page'] == 'zara-4' ) {

          // Check for factory reset
          if ( isset( $_GET['factory-reset'] ) ) {

            // Clear settings
            Zara4_WordPress_Settings::clear();

            // Redirect to Zara 4 Settings page
            wp_redirect( 'options-general.php?page=zara-4' );

            // The end...
            exit;
          }
        }
      }

    }



    /*
     *   _____
     *  |  __ \
     *  | |__) |__ _   __ _   ___
     *  |  ___// _` | / _` | / _ \
     *  | |   | (_| || (_| ||  __/
     *  |_|    \__,_| \__, | \___|
     *                 __/ |
     *                |___/
     */


    /**
     * Plugin Settings Page
     */
    public static function settings_page() {

      //
      // Update settings if POST given
      //
      $saved = false;
      $savedErrorMessage = 'Cannot save your settings. Write permission was denied. <a target="_blank" href="https://codex.wordpress.org/Changing_File_Permissions">Read about permissions</a>';
      if ( ! empty( $_POST ) ) {
        try {
          $saved = self::post_settings_page();
        } catch ( Zara4_WordPress_UsingTestCredentialsException $e ) {
          $savedErrorMessage = 'You cannot use Zara 4 test API credentials. Obtain your live credentials from <a target="_blank" href="https://zara4.com/account/api-clients/live-credentials">here</a>';
        }
      }

      $settings = new Zara4_WordPress_Settings();

      // --- --- --- --- ---

      $api_client_id     = $settings->api_client_id();
      $api_client_secret = $settings->api_client_secret();

      try {
        $access_token = Zara4_WordPress_Zara4::generate_access_token( $api_client_id, $api_client_secret );
      } catch ( Zara4_API_Communication_AccessDeniedException $e ) {
        $access_token = null;
      }

      ?>
      <script>
        var ZARA4_API_BASE_URL = "<?php echo Zara4_API_Communication_Config::BASE_URL() ?>";
        var ZARA4_API_ACCESSTOKEN = "<?php echo $access_token ?>";
      </script>

      <div class="wrap zara-4">

        <div style="margin:30px 0 10px 0">
          <span style="float: right" id="debug-info-btn" class="button">Debug Info</span>
          <h1 style="padding:0">
            <a target="_blank" href="https://zara4.com">
              <img style="height: 25px" src="<?php echo ZARA4_PLUGIN_BASE_URL ?>/img/logo.png" alt="Zara 4" />
            </a>
          </h1>
        </div>

        <div style="margin-bottom: 20px">
          <p class="large">
            Need help? &nbsp;<a href="https://zara4.com/contact-us" target="_blank">Plugin Support</a>
          </p>
        </div>



        <?php if ( ! empty( $_POST ) ): ?>
          <?php if ( $saved ): ?>
            <div class="zara-4 alert-boxed alert alert-success w-600">
              <b>Settings Saved!</b>
            </div>
          <?php else: ?>
            <div class="zara-4 alert-boxed alert alert-danger w-600">
              <b>Error :</b> <?php echo $savedErrorMessage; ?>
            </div>
          <?php endif; ?>
        <?php endif; ?>



        <div id="error-message" class="zara-4 alert-boxed alert alert-danger hidden w-600"></div>
        <div id="warning-message" class="zara-4 alert-boxed alert alert-warning hidden w-600"></div>


        <?php if ( ZARA4_DEV ): ?>
          <div id="warning-message" class="zara-4 alert-boxed alert alert-warning w-600">
            <b>Warning :</b> Running in development mode
          </div>
        <?php endif; ?>


        <?php if ( ! function_exists( 'curl_version' ) ): ?>
          <div class="zara-4 alert-boxed alert alert-danger w-600">
            <b>Error :</b> Your WordPress server does not have cURL installed.
          </div>
        <?php else: ?>


          <form method="post" id="zara-4-settings-form">

            <!-- Nav tabs -->
            <?php if ( $settings->has_api_credentials() ): ?>
            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">General</a></li>
              <li role="presentation"><a href="#advanced" aria-controls="advanced" role="tab" data-toggle="tab">Advanced</a></li>
              <li role="presentation"><a href="#statistics" aria-controls="statistics" role="tab" data-toggle="tab">Statistics</a></li>
              <?php if( false ): ?><li role="presentation"><a href="#status" aria-controls="status" role="tab" data-toggle="tab">Status</a></li><?php endif; ?>
              <li role="presentation"><a href="#management" aria-controls="status" role="tab" data-toggle="tab">Management</a></li>
            </ul>
            <?php endif; ?>

            <!-- Tab panes -->
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="general">
                <?php self::settings_page_tab_general( $settings, $access_token ); ?>
              </div>
              <div role="tabpanel" class="tab-pane" id="advanced">
                <?php self::settings_page_tab_advanced( $settings ); ?>
              </div>
              <div role="tabpanel" class="tab-pane" id="statistics">
                <?php self::settings_page_tab_statistics( $settings, $access_token ); ?>
              </div>
              <?php if( false ): ?>
              <div role="tabpanel" class="tab-pane" id="status">
                <?php self::settings_page_tab_status( $settings ); ?>
              </div>
              <?php endif; ?>
              <div role="tabpanel" class="tab-pane" id="management">
                <?php self::settings_page_tab_management( $settings ); ?>
              </div>

              <div style="margin-top:20px">
                <button type="submit" class="button button-primary">Save Settings</button>
                <button type="submit" value="1" name="clear-settings" class="button button-default">Clear Settings</button>
              </div>
            </div>

          </form>
        <?php endif; ?>


        <?php self::settings_page_debug_modal($settings); ?>

      </div>
    <?php
    }


    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---



    /*
     *   _____              _    _         _
     *  |  __ \            | |  (_)       | |
     *  | |__) |__ _  _ __ | |_  _   __ _ | | ___
     *  |  ___// _` || '__|| __|| | / _` || |/ __|
     *  | |   | (_| || |   | |_ | || (_| || |\__ \
     *  |_|    \__,_||_|    \__||_| \__,_||_||___/
     *
     */


    /**
     * @param Zara4_WordPress_Settings $settings
     */
    private static function settings_page_tab_general( $settings ) {

      global $_wp_additional_image_sizes;

      $api_client_id            = $settings->api_client_id();
      $api_client_secret        = $settings->api_client_secret();
      $auto_optimise            = $settings->auto_optimise();
      $back_up_original_images  = $settings->back_up_original_images();
      $maintain_exif            = $settings->maintain_exif();

      ?>


      <?php if ( ! $settings->has_api_credentials() ): ?>
        <div style="margin-bottom: 20px">
          <div>
            <h2 style="margin-top: 0">Quick setup</h2>
          </div>
          <span id="google-sign-up" class="button button-google"><i class="fa fa-google"></i> Sign up with Google</span>
          <span id="facebook-sign-up" class="button button-facebook"><i class="fa fa-facebook"></i> Sign up with Facebook</span>
          <span id="github-sign-up" class="button button-github"><i class="fa fa-github"></i> Sign up with GitHub</span>
          <?php if(false): ?>
          <span id="dropbox-sign-up" class="button button-dropbox"><i class="fa fa-dropbox"></i> Sign up with Dropbox</span>
          <?php endif; ?>
        </div>

        <hr class="dashed"/>

        <div>
          <h2>Manual setup</h2>
        </div>
        <div class="zara-4 alert-boxed alert alert-danger w-600">
          <b>You need to register</b> to get your API credentials - <a target="_blank" href="https://zara4.com/auth/api-register">Click here</a>
        </div>
      <?php endif; ?>


      <table class="form-table">

        <tr>
          <th>
            API Key
          </th>
          <td>
            <input id="zara4-client-id" name="_zara4_settings[api-client-id]" type="text" size="40" placeholder="Client Id" value="<?php echo esc_attr($api_client_id) ?>">
          </td>
        </tr>

        <tr>
          <th style="padding-top: 0">
            API Secret
          </th>
          <td style="padding-top: 0">
            <input id="zara4-client-secret" name="_zara4_settings[api-client-secret]" type="text" size="40" placeholder="Client Secret" value="<?php echo esc_attr($api_client_secret) ?>">
          </td>
        </tr>

        <tr>
          <th>
            Account Status
          </th>
          <td>
            <p id="zara-4-account-status">
              <img src="<?php echo ZARA4_PLUGIN_BASE_URL.'/img/loading.gif'; ?>"/> Please wait...
            </p>
          </td>
        </tr>

      </table>




      <?php if ( $settings->has_api_credentials() ): ?>
        <hr/>

        <table class="form-table">


          <tr>
            <th scope="row">Auto Compress</th>
            <td>
              <label>
                <input type="checkbox" id="auto_optimize" name="_zara4_settings[auto-optimise]" value="1" <?php checked( 1, $auto_optimise, true ); ?>/>
                Automatically compress new uploads
              </label>
              <p class="below-label">
                If enabled new images that you upload will be <b>automatically compressed</b>.
                <br/>
                If you disable this option new uploads will not be compressed.
                You can manually compress uploaded images that have not been automatically compressed from the WordPress Media manager.
              </p>
            </td>
          </tr>


          <tr>
            <th scope="row">Back Up Images</th>
            <td>
              <label>
                <input type="checkbox" id="back-up-original-image" name="_zara4_settings[back-up-original-image]" value="1" <?php checked( 1, $back_up_original_images, true ); ?>/>
                Keep a back up of original uncompressed images <b>(recommended)</b>
              </label>
              <p class="below-label">
                If enabled this will keep a <b>back up copy</b> of images before they are compressed.
                This will use additional disk space on server, but will allow you to restore images back to the original uncompressed version.
                You can delete back up images at any time.
              </p>
              <div class="hidden below-label" id="delete-all-wrapper">
                (<span class="zara-4 a delete" id="delete-all-btn">Delete all</span> existing backed up images, <b><span class="number-of-images"></span></b> can be deleted)
              </div>
            </td>
          </tr>


          <tr>
            <th scope="row">Maintain EXIF</th>
            <td>
              <label>
                <input type="checkbox" id="maintain-exif" name="_zara4_settings[maintain-exif]" value="1" <?php checked( 1, $maintain_exif, true ); ?>/>
                Maintain image EXIF meta data
              </label>
              <p class="below-label">
                Many images have EXIF metadata such as: camera manufacturer / model, GPS location, copyright information etc.
                You can remove EXIF metadata for greater compression, or keep the EXIF metadata if you would prefer to maintain this metadata.
              </p>
            </td>
          </tr>


          <tr>
            <th>
              Compress Thumbnails
            </th>
            <td>
              <p class="large">
                Select images sizes to compress
              </p>
              <p class="below-label">
                WordPress generates a number of re-sized images for each of your images, which are throughout your website.
                Zara 4 can compress each of the generated re-sized images available as well as the original image.
                Below you can select which image sizes should be compressed by default when an image is compressed.
                Each selected size will count towards your quota usage.
              </p>
              <p style="margin-top: 10px">
                <label class="small">
                  <input type="checkbox" name="_zara4_settings[compress-size][original]" value="1" disabled="true" <?php checked( 1, $settings->image_size_should_be_compressed( 'original' ), true ); ?>>
                  Original Image
                </label>
              </p>
              <p>
                <label class="small">
                  <input type="checkbox" name="_zara4_settings[compress-size][large]" value="1" <?php checked( 1, $settings->image_size_should_be_compressed( 'large' ), true ); ?>>
                  Large (1024x1024)
                </label>
              </p>
              <p>
                <label class="small">
                  <input type="checkbox" name="_zara4_settings[compress-size][medium]" value="1" <?php checked( 1, $settings->image_size_should_be_compressed( 'medium' ), true ); ?>>
                  Medium (300x300)
                </label>
              </p>
              <p>
                <label class="small">
                  <input type="checkbox" name="_zara4_settings[compress-size][thumbnail]" value="1" <?php checked( 1, $settings->image_size_should_be_compressed( 'thumbnail' ), true ); ?>>
                  Thumbnail (150x150)
                </label>
              </p>
              <?php if ( is_array( $_wp_additional_image_sizes ) ): ?>
                <?php foreach ( $_wp_additional_image_sizes as $name => $details ) : ?>
                  <?php
                  $widthIsSet = isset( $details['width'] ) && $details['width'] !== null;
                  $heightIsSet = isset( $details['height'] ) && $details['height'] !== null;
                  $width = $widthIsSet ? $details['width'] : null;
                  $height = $heightIsSet ? $details['height'] : null;
                  ?>
                  <p>
                    <label class="small">
                      <input type="checkbox" name="_zara4_settings[compress-size][<?php echo $name ?>]" value="1" <?php checked( 1, $settings->image_size_should_be_compressed( $name ), true ); ?>>
                      <?php echo ucwords(str_replace("-", " ", $name)); ?>
                      <?php if ( $widthIsSet && $heightIsSet ): ?>
                        (<?php echo $width ?>x<?php echo $height ?>)
                      <?php endif; ?>
                    </label>
                  </p>
                <?php endforeach; ?>
              <?php endif; ?>
            </td>
          </tr>


        </table>
      <?php endif; ?>

      <?php
    }


    /**
     * @param Zara4_WordPress_Settings $settings
     */
    private static function settings_page_tab_advanced( $settings ) {

      $metadata_storage_method = $settings->metadata_storage_method();
      $compress_all_feature = $settings->compress_all_feature();
      $dashboard_widget_enabled = $settings->dashboard_widget_enabled();

      ?>
      <table class="form-table">

        <?php if ( false ): ?>
        <tr>
          <th scope="row">Metadata Storage Method</th>
          <td>
            <label>
              <select name="_zara4_settings[metadata-storage-method]">
                <option value="database" <?php selected( $metadata_storage_method, 'database' ); ?>>Database</option>
                <option value="file-storage" <?php selected( $metadata_storage_method, 'file-storage' ); ?>>File Storage</option>
              </select>
            </label>
          </td>
        </tr>
        <?php endif; ?>


        <tr>
          <th scope="row">'Compress All' Feature</th>
          <td>
            <label>
              <input type="checkbox" id="compress-add-feature" name="_zara4_settings[compress-all-feature]" value="1" <?php checked( 1, $compress_all_feature, true ); ?>/>
              Enable compress all feature
            </label>
            <p class="below-label">
              The 'compress all' message is displayed as a notice at the top of the WordPress Media page.
            </p>
          </td>
        </tr>


        <tr>
          <th scope="row">Dashboard Widget</th>
          <td>
            <label>
              <input type="checkbox" id="compress-add-feature" name="_zara4_settings[dashboard-widget-enabled]" value="1" <?php checked( 1, $dashboard_widget_enabled, true ); ?>/>
              Enable dashboard widget
            </label>
            <p class="below-label">
              Show the Zara 4 dashboard widget on your site's admin dashboard page?
            </p>
          </td>
        </tr>

      </table>
      <?php
    }


    /**
     * @param Zara4_WordPress_Settings $settings
     * @param string $access_token
     */
    private static function settings_page_tab_statistics( $settings, $access_token ) {
      ?>
      <?php if ( $settings->has_api_credentials() ): ?>
        <div id="account-usage-wrapper" class="hidden">
          <h2>Account Usage</h2>
          <iframe style="height: 300px; width: 600px" src="<?php echo Zara4_API_Communication_Config::BASE_URL() ?>/v1/view/user/usage-graph?access_token=<?php echo $access_token ?>"></iframe>
          <table class="form-table" style="margin-top: 0">
            <tr>
              <th>
                Allowance Remaining
              </th>
              <td id="allowance-remaining">
              </td>
            </tr>
          </table>
        </div>

        <hr/>

        <div>
          <table class="form-table" style="margin-top: 0">

            <tr>
              <th>
                No. Compressed Images
              </th>
              <td id="number-of-compressed-images"></td>
            </tr>

            <tr>
              <th>
                No. Uncompressed Images
              </th>
              <td style="width: 60px; vertical-align: top" id="number-of-uncompressed-images"></td>
              <td>
                <?php if ( false ): ?>
                <button class="button button-default" id="exclude-all-uncompressed-images">Exclude all uncompressed images</button>
                <p class="below-label">
                  'Uncompressed images' are images that haven't been compressed or excluded.
                  You can exclude all of the images currently uncompressed by clicking the "Exclude all uncompressed images" button.
                  You will no longer be prompted to compress images once they have been excluded.
                </p>
                <?php endif; ?>
              </td>
            </tr>

            <tr>
              <th>
                No. Excluded Images
              </th>
              <td style="width: 60px; vertical-align: top" id="number-of-excluded-images"></td>
              <td>
                <?php if ( false ): ?>
                <button class="button button-default" id="include-all-excluded-images">Include all excluded images</button>
                <p class="below-label">
                  'Excluded images' are images that haven't been compressed, but will not show up in the 'Compress All' notification.
                  You can include all of the image currently excluded from being compressed by clicking the "Include all excluded images" button.
                </p>
                <?php endif; ?>
              </td>
            </tr>
          </table>
        </div>

        <hr/>
      <?php endif ?>
      <?php
    }


    /**
     * @param $settings
     */
    private static function settings_page_tab_status( $settings ) {
      ?>
      <div>
        <h2>Zara 4 Status Map</h2>
        <iframe style="height: 350px; width: 100%" src="<?php echo Zara4_API_Communication_Config::BASE_URL() ?>/v1/view/status/map"></iframe>
      </div>
      <?php
    }


    /**
     * @param $settings
     */
    private static function settings_page_tab_management( $settings ) {
      ?>

      <table class="form-table">
        <tr>
          <th scope="row">Compress All Images</th>
          <td>
            <p>
              <b><span id="management_number-of-images-with-backup"></span></b>
            </p>
            <p>
              Do you want to compress all images that have not been compressed yet?
            </p>
            <p>
              <span id="number-of-uncompressed-images"></span>
            </p>

            <span style="margin-top: 10px" id="compress-all-images-btn" class="button button-default">Compress All Images</span>
          </td>
        </tr>
      </table>

      <hr/>

      <table class="form-table">
        <tr>
          <th scope="row">Restore Original Images</th>
          <td>
            <p>
              <b><span id="management_number-of-images-with-backup"></span></b>
            </p>
            <p>
              Do you want to restore all images that have been compressed to their original uncompressed version?
            </p>
            <p class="below-label">
              <b>Note 1:</b> This can only work for images where there is a back up of the original uncompressed image available.
              <br/>
              <b>Note 2:</b> Once the back up image is restored, the compressed version of the image will be removed. You will have to use additional quota to compress images again.
            </p>

            <span style="margin-top: 10px" id="restore-all-backed-up-images-btn" class="button button-default">Restore All Original Images</span>
          </td>
        </tr>
      </table>


      <script>
        (function( $ ) {

          //
          // When the "Restore All Original Images" button is clicked.
          //
          $('#restore-all-backed-up-images-btn').click( function() {
            $.fn.Zara4_Modal_RestoreOriginal.show();
          } );



          $('#compress-all-images-btn').click(function () {
            $.fn.Zara4_Modal_PleaseWait.show( "Finding images to be compressed" );

            $.fn.Zara4_Ajax.uncompressedImages( function( response ) {

              $.fn.Zara4_Modal_PleaseWait.hide();

              var image_ids = eval( response );
              var number_of_images = image_ids.length;


              $.fn.Zara4_Modal_CompressAll.reset();

              $.fn.Zara4_Modal_CompressAll.imageIds = image_ids;
              $.fn.Zara4_Modal_CompressAll.setImageCurrentImageNumber( 0 );
              $.fn.Zara4_Modal_CompressAll.setNumberOfImages( number_of_images );

              $.fn.Zara4_Modal_CompressAll.show();
            } );

          })
        })( jQuery );
      </script>
      <?php
    }


    /**
     * @param $settings
     */
    private static function settings_page_debug_modal( $settings ) {
      ?>
      <div id="zara-4-info-modal" class="zara-4" style="display: none">
        <h2>Debug Info</h2>

        <table class="zara-4 table">
          <tr>
            <td style="width: 130px"><b>Zara 4 Version</b></td>
            <td><?php echo ZARA4_VERSION; ?></td>
          </tr>
          <tr>
            <td><b>Zara 4 Settings</b></td>
            <td><?php echo $settings; ?></td>
          </tr>
          <tr>
            <td><b>Zara 4 Mode</b></td>
            <td><?php echo ZARA4_DEV ? 'Development' : 'Production' ?></td>
          </tr>
        </table>
        <hr/>
        <table class="zara-4 table">
          <tr>
            <td style="width: 130px"><b>WordPress Version</b></td>
            <td><?php echo get_bloginfo( 'version' ); ?></td>
          </tr>
          <tr>
            <td><b>PHP Version</b></td>
            <td><?php echo phpversion(); ?></td>
          </tr>
          <tr>
            <td><b>PHP Extensions</b></td>
            <td><?php echo implode(', ', get_loaded_extensions()); ?></td>
          </tr>
          <tr>
            <td><b>Machine Info</b></td>
            <td><?php echo php_uname(); ?></td>
          </tr>
        </table>

        <div class="text-center mt-15">
          <a href="#" class="button button-primary" rel="modal:close">Close</a>
        </div>

      </div>
      <?php
    }


    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- ---


    /*
     *   _____             _
     *  |  __ \           | |
     *  | |__) |___   ___ | |_
     *  |  ___// _ \ / __|| __|
     *  | |   | (_) |\__ \| |_
     *  |_|    \___/ |___/ \__|
     *
     */


    /**
     * Handle post of settings_page.
     *
     * @throws Zara4_WordPress_UsingTestCredentialsException
     * @return boolean
     */
    private static function post_settings_page() {

      $settings = new Zara4_WordPress_Settings();

      // Clear settings
      if ( isset( $_POST['clear-settings'] ) && $_POST['clear-settings'] ) {
        Zara4_WordPress_Settings::clear();
        $settings = new Zara4_WordPress_Settings();
        return true;
      }


      $options = $_POST['_zara4_settings'];
      $imageSizes = Zara4_WordPress_Settings::thumbnail_size_names();

      // --- --- ---

      // Only save other settings if already have api credentials (since they are hidden when no api credentials)
      if ( $settings->has_api_credentials() ) {

        // Auto optimise
        $settings->set_auto_optimise( $options['auto-optimise'] );

        // Back up original images
        $settings->set_back_up_original_images( $options['back-up-original-image'] );

        // Maintain EXIF
        $settings->set_maintain_exif( $options['maintain-exif'] );

        // Image sizes
        foreach ( $imageSizes as $name ) {
          $settings->set_image_size_should_be_compressed(
            $name, isset( $options['compress-size'][$name] ) ? $options['compress-size'][$name] : false
          );
        }

        // Force original image
        $settings->set_image_size_should_be_compressed( 'original', true );

      }


      // Compress all feature
      $settings->set_compress_all_feature( isset( $options['compress-all-feature'] ) ? $options['compress-all-feature'] : false );

      // Dashboard widget enabled
      $settings->set_dashboard_widget_enabled( isset( $options['dashboard-widget-enabled'] ) ? $options['dashboard-widget-enabled'] : false );

      // Metadata Storage Method
      $settings->set_metadata_storage_method( isset( $options['metadata-storage-method'] ) ? $options['metadata-storage-method'] : 'database' );


      $settings->save();


      // --- --- ---

      //
      // Save API credentials
      //
      $api_client_id = trim( $options['api-client-id'] );
      $api_client_secret = trim( $options['api-client-secret'] );
      if ( substr( strtolower( $api_client_id ), 0, 5 ) == 'test_' || substr( strtolower( $api_client_secret ), 0, 5 ) == 'test_' ) {
        throw new Zara4_WordPress_UsingTestCredentialsException();
      }
      $settings->set_api_client_id( $api_client_id );
      $settings->set_api_client_secret( $api_client_secret );

      return $settings->save();
    }


  }

}