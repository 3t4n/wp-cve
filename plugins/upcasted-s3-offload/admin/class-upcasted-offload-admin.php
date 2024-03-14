<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.upcasted.com
 * @since      1.0.0
 *
 * @package    Upcasted_S3_Offload
 * @subpackage Upcasted_S3_Offload/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Upcasted_S3_Offload
 * @subpackage Upcasted_S3_Offload/admin
 * @author     Upcasted <contact@upcasted.com>
 */
class Upcasted_S3_Offload_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private  $version ;
    /** @var CloudCredentialsEncryption $encryption */
    private  $encryption ;
    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     *
     * @since    1.0.0
     */
    public function __construct( string $plugin_name, string $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->encryption = CloudCredentialsEncryption::getInstance();
    }
    
    /**
     * Register submenu
     * @return void
     */
    public function register_sub_menu()
    {
        add_submenu_page(
            'upload.php',
            'S3 Offload Settings',
            'S3 Offload Settings',
            'manage_options',
            'upcasted-s3-offload-panel',
            array( &$this, 'submenu_page_callback' )
        );
    }
    
    /**
     * Render submenu
     * @return void
     */
    public function submenu_page_callback()
    {
        // check user capabilities
        if ( !current_user_can( 'manage_options' ) ) {
            return;
        }
        $options = get_option( UPCASTED_S3_OFFLOAD_SETTINGS );
        $access_key_id = ( !empty($options[UPCASTED_S3_OFFLOAD_ACCESS_KEY_ID]) ? $this->encryption->decrypt( $options[UPCASTED_S3_OFFLOAD_ACCESS_KEY_ID] ) : '' );
        $secret_access_key = ( !empty($options[UPCASTED_S3_OFFLOAD_SECRET_ACCESS_KEY]) ? $this->encryption->decrypt( $options[UPCASTED_S3_OFFLOAD_SECRET_ACCESS_KEY] ) : '' );
        $region = $options[UPCASTED_OFFLOAD_REGION] ?? '';
        $custom_endpoint = $options[UPCASTED_CUSTOM_ENDPOINT] ?? '';
        $fileTypes = $options[UPCASTED_S3_OFFLOAD_INCLUDED_FILETYPES] ?? [];
        $bucket = $options[UPCASTED_S3_OFFLOAD_BUCKET] ?? '';
        $protocol = $options[UPCASTED_S3_OFFLOAD_PROTOCOL] ?? '';
        $custom_domain = $options[UPCASTED_S3_OFFLOAD_CUSTOM_DOMAIN] ?? '';
        $custom_batch_size = $options[UPCASTED_CUSTOM_BATCH_SIZE] ?? '';
        $run_local_to_s3_cron = $options['upcasted_move_from_local_to_cloud_using_cron__premium_only'] ?? '';
        $run_s3_to_local_cron = $options['upcasted_move_from_cloud_to_local_using_cron__premium_only'] ?? '';
        $remove_local_file = $options[UPCASTED_REMOVE_LOCAL_FILE] ?? '';
        $remove_s3_file = $options[UPCASTED_REMOVE_CLOUD_FILE] ?? '';
        $availableRegions = array(
            "us-east-2"      => "[AWS S3] US East (Ohio) - us-east-2",
            "us-east-1"      => "[AWS S3] US East (N. Virginia) - us-east-1",
            "us-west-1"      => "[AWS S3] US West (N. California) - us-west-1",
            "us-west-2"      => "[AWS S3] US West (Oregon) - us-west-2",
            "af-south-1"     => "[AWS S3] Africa (Cape Town) - af-south-1",
            "ap-east-1"      => "[AWS S3] Asia Pacific (Hong Kong) - ap-east-1",
            "ap-southeast-3" => "[AWS S3] Asia Pacific (Jakarta) - ap-southeast-3",
            "ap-south-1"     => "[AWS S3] Asia Pacific (Mumbai) - ap-south-1",
            "ap-northeast-3" => "[AWS S3] Asia Pacific (Osaka) - ap-northeast-3",
            "ap-northeast-2" => "[AWS S3] Asia Pacific (Seoul) - ap-northeast-2",
            "ap-southeast-1" => "[AWS S3] Asia Pacific (Singapore) - ap-southeast-1",
            "ap-southeast-2" => "[AWS S3] Asia Pacific (Sydney) - ap-southeast-2",
            "ap-northeast-1" => "[AWS S3] Asia Pacific (Tokyo) - ap-northeast-1",
            "ca-central-1"   => "[AWS S3] Canada (Central) - ca-central-1",
            "cn-north-1"     => "[AWS S3] China (Beijing) - cn-north-1",
            "cn-northwest-1" => "[AWS S3] China (Ningxia) - cn-northwest-1",
            "eu-central-1"   => "[AWS S3] Europe (Frankfurt) - eu-central-1",
            "eu-west-1"      => "[AWS S3] Europe (Ireland) - eu-west-1",
            "eu-west-2"      => "[AWS S3] Europe (London) - eu-west-2",
            "eu-south-1"     => "[AWS S3] Europe (Milan) - eu-south-1",
            "eu-west-3"      => "[AWS S3] Europe (Paris) - eu-west-3",
            "eu-north-1"     => "[AWS S3] Europe (Stockholm) - eu-north-1",
            "sa-east-1"      => "[AWS S3] South America (São Paulo) - sa-east-1",
            "me-south-1"     => "[AWS S3] Middle East (Bahrain) - me-south-1",
            "us-gov-east-1"  => "[AWS S3] AWS GovCloud (US-East) - us-gov-east-1",
            "us-gov-west-1"  => "[AWS S3] AWS GovCloud (US-West) - us-gov-west-1",
            "NYC3"           => "[DigitalOcean Spaces] North America - NYC3",
            "SFO3"           => "[DigitalOcean Spaces] South America - SFO3",
            "SGP1"           => "[DigitalOcean Spaces] Asia, Singapore - SGP1",
            "AMS3"           => "[DigitalOcean Spaces] Europe, Amsterdam - AMS3",
            "FRA1"           => "[DigitalOcean Spaces] Europe, Frankfurt - FRA1",
        );
        ?>
        <div class="wrap">
            <div class="upcasted-notice-wrapper"><h2 style="margin:0"></h2></div>
            <div class="upcasted-plugin-header">
                <h1><?php 
        echo  esc_html( get_admin_page_title() ) ;
        ?></h1>
                <img src="<?php 
        echo  plugin_dir_url( __FILE__ ) . 'assets/img/logo-upcasted.png' ;
        ?>" width="170px;"
                     alt="Upcasted"/>
            </div>
            <p><strong></strong></p>
            <div class="upcasted-plugin-info">
                <div class="upcasted-col">
                    <h3>How to check if everything is working properly?</h3>
                    <ol>
                        <li>Set the Mandatory Settings of the plugin below and click "Save Settings"</li>
                        <li>Select your bucket and click "Save"</li>
                        <li>Go to Media Library and upload a file (an image is better for testing purposes)</li>
                        <li>Check to see if you can view the file. If it's an image you should see the preview.</li>
                        <li>Check the URL of that file. You should see the new URL. You can compare it with the URL of an old file.</li>
                    </ol>
                    <h3>Helpful articles:</h3>
                    <ol>
                        <li><a href="https://upcasted.com/aws-s3-what-is-it-why-does-it-help-you-and-how-to-create-an-aws-account-today/" target="_blank">AWS S3 - What is it, why does it help you and how to create an AWS account today?</a></li>
                        <li><a href="https://upcasted.com/create-amazon-s3-bucket-generate-iam-api-key-and-secret-key-new-aws-s3/" target="_blank">How to create an Amazon S3 bucket and generate your IAM credentials (API key and Secret key) using the new 2022 AWS S3 interface?</a></li>
                    </ol>
                    <h3>Support us:</h3>
                    <p>
                    <a href="https://wordpress.org/support/plugin/upcasted-s3-offload/reviews/" style="text-decoration: none" class="button button-primary" target="_blank">Write a review</a>
                        <span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled">
                        </span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span>
                    </p>
                    <p>Your review counts alot! Give us a rating so we can continue developing this product. Please allow us to fix your issue before writing us a bad review.</p>
                    
                </div>
                <div class="upcasted-col">
                    <h3>How it works?</h3>
                    <ol>
                        <li>After you set the "Mandatory Settings" and select a bucket, the plugin will automatically move all the newly added files to your bucket.</li>
                        <li>If you have a PRO license, you have some extra tools. They will assist you in moving your old files from media library to S3 and backwards.</li>
                    </ol>
                    <h3>How to debug an issue?</h3>
                    <ol>
                        <li>Edit your wp-config.php file and <a href="https://wordpress.org/support/article/debugging-in-wordpress/" target="_blank">turn on debug mode and debug log</a></li>
                        <li>Go to wp-content and search for debug.log if you can find a file with that name already there please rename it to something else or delete it if you don't need the old logs.</li>
                        <li>Repeat the opperation that you were trying to do when you had trouble.</li>
                        <li>Check the contents of debug.log and try to find out what went wrong. On that log you can see all the errors, notices and warnings timestamped in chronological order.</li>
                        <li>Depending on what you see in your debug log, disable your plugins that throw errors one by one and repeat steps 3-4 until is working. <br />This way you will be able to find out if the problem is caused by another plugin.</li>
                        <li>If you can't figure it out, <a href="https://upcasted.com/" target="_blank">let us know</a>!</li>
                    </ol>
                </div>
            </div>
            <div class="upcasted-container">
                <div class="upcasted-settings-container">
                    <h2 class="upcasted-title">Mandatory Settings</h2>
                    <div class="upcasted-tools-option">
                        <div class="upcasted-tools-option-header">
                            <div class="upcasted-tools-tool-name">
                                <span><strong>File migration settings</strong></span>
                            </div>
                        </div>
                        <div class="upcasted-tools-option-body">
                            <label class="upcasted-label" for="upcasted_s3_offload_access_key_id">Access key ID
                                <input class="upcasted-input" type='password'
                                       value="<?php 
        echo  $access_key_id ;
        ?>" name='upcasted_s3_offload_access_key_id'>
                            </label>
                            <label class="upcasted-label" for="upcasted_s3_offload_secret_access_key">Secret access key
                                <input class="upcasted-input" type='password'
                                       value="<?php 
        echo  $secret_access_key ;
        ?>"
                                       name='upcasted_s3_offload_secret_access_key'>
                            </label>
                            <label class="upcasted-label" for="upcasted_offload_region">Region</label>
                            <select name="upcasted_offload_select_region" id="upcasted_offload_select_region">
                                <option value="">Select a region or write one in the input below</option>
                                <?php 
        foreach ( $availableRegions as $availableRegion => $regionName ) {
            ?>
                                    <option value="<?php 
            echo  $availableRegion ;
            ?>"  <?php 
            echo  ( $region == $availableRegion ? 'selected' : '' ) ;
            ?>><?php 
            echo  $regionName ;
            ?></option>
                                <?php 
        }
        ?>
                            </select>
                            <input class="upcasted-input"
                                   value="<?php 
        echo  $region ;
        ?>"
                                   name='upcasted_offload_region'
                                   id="upcasted_offload_region">
                            <br /><br />
                            <label class="upcasted-label" for="upcasted_custom_endpoint">Define custom endpoint</label>
                            <input class="upcasted-input"
                                   value="<?php 
        echo  $custom_endpoint ;
        ?>"
                                   name='upcasted_custom_endpoint'>
                            <small>This is necessary if you use S3 compatible service providers like DigitalOcean Spaces and many more. </small><br/><br/>
                            <?php 
        ?>

                            <label class="upcasted-label" for="upcasted-delete-local-file">
                                Keep a copy of the files (on current server) when are migrated to S3:</label>
                            <select name="upcasted-delete-local-file">
                                <option value="no">No</option>
                                <option value="yes" <?php 
        echo  ( 'yes' === $remove_local_file ? 'selected' : '' ) ;
        ?>>
                                    Yes
                                </option>
                            </select>
                            <br /><br/>
                            <?php 
        ?>


                        </div>
                        <div class="upcasted-tools-option-footer">
                            <button id="upcasted-save-settings"
                                    class="upcasted-button <?php 
        ?>">
                                Save settings
                            </button>
                        </div>
                    </div>
                </div>

                <div class="upcasted-modal hidden" id="select-bucket-modal">
                    <div class="upcasted-modal-content">
                        <div class="upcasted-modal-header">
                            <div class="upcasted-modal-title">
                                Choose or create a bucket
                            </div>
                            <button class="upcasted-close-modal-button"><span class="dashicons dashicons-no-alt"></span>
                            </button>
                        </div>
                        <div class="upcasted-modal-body">
                            <div class="upcasted-modal-result hidden">
                                <div class="upcasted-inline-option upcasted-select-bucket">
                                    <label for="upcasted_s3_offload_bucket">Select an existing bucket</label>
                                    <select class="upcasted-buckets-list" name="upcasted_s3_offload_bucket"></select>
                                    <button class="upcasted-button" id="upcasted-save-bucket">Save</button>
                                </div>
                                <?php 
        ?>
                            </div>
                            <div class="upcasted-modal-error hidden"></div>
                        </div>
                    </div>
                </div>

                <div class="upcasted-tools-container <?php 
        echo  $bucket ?? 'hidden' ;
        ?>">
                    <h2 class="upcasted-title">Tools</h2>
                    <?php 
        ?>
                    <div class="upcasted-image-number-container hidden">
                        <div class="upcasted-running-tool"><strong>Tool:</strong> <span></span></div>
                        <p class="upcasted-total-images-container">
                            <span>Total images to move:</span>
                            <span class="upcasted-number-of-images"></span>
                        </p>
                    </div>
                    <div class="upcasted-cron-message hidden"></div>
                    <div class="upcasted-tools-error hidden"></div>
                    <div class="upcasted-tools-option upcasted-current-bucket">
                        <div class="upcasted-tools-option-header">
                            <div class="upcasted-tools-tool-name">
                                <span>Current Bucket: <strong><?php 
        echo  $bucket ;
        ?></strong></span>
                            </div>
                            <div class="upcasted-tools-tool-actions">
                                <button id="change-current-bucket" class="upcasted-button upcasted-tool-button">
                                    Change bucket
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="upcasted-tools-option upcasted-cdn-delivery">
                        <div class="upcasted-tools-option-header">
                            <div class="upcasted-tools-tool-name">
                                <span><strong>Content Delivery Network <?php 
        echo  ( uso_fs()->is_not_paying() ? '[Premium feature]' : '' ) ;
        ?></strong></span>
                            </div>
                        </div>
                        <div class="upcasted-tools-option-body">
                            <select name="upcasted-cdn-delivery-protocol" id="upcasted-cdn-delivery-protocol">
                                <option value="https://" <?php 
        echo  ( 'https://' === $protocol ? 'selected' : '' ) ;
        ?>>
                                    https://
                                </option>
                                <option value="http://" <?php 
        echo  ( 'http://' === $protocol ? 'selected' : '' ) ;
        ?>>
                                    http://
                                </option>
                            </select>
                            <input type="text" name="upcasted-cdn-delivery-domain"
                                   id="upcasted-cdn-delivery-domain"
                                   placeholder="Eg.: assets.example.com"
                                   value="<?php 
        echo  $custom_domain ;
        ?>">
                            <?php 
        
        if ( uso_fs()->is_not_paying() ) {
            ?>
                                <a href="<?php 
            echo  uso_fs()->get_upgrade_url() ;
            ?>"
                                   class="upcasted-button upcasted-upgrade-plan">Upgrade now</a>
                            <?php 
        } else {
            ?>
                                <button id="cdn-delivery-domain-button" class="upcasted-button">Activate</button>
                                <button id="reset-cdn-delivery-domain-button"
                                        class="upcasted-button upcasted-stop-action <?php 
            echo  ( !empty($custom_domain) ? '' : 'hidden' ) ;
            ?>">
                                    Reset
                                </button>
                            <?php 
        }
        
        ?>
                        </div>
                        <div class="cdn-tool-message"></div>
                        <div class="upcasted-tools-option-footer">
                            <button class="upcasted-find-out-more"><span class="dashicons dashicons-editor-help"></span>
                                Need help?
                            </button>
                            <div class="upcasted-hidden-help-description">
                                <p>For this to work you need to change DNS record to your domain or subdomain.</p>
                                <p>To do this you have to got to your CDN provider administration panel and put in the right settings.</p>
                                <p>Since this is different from provider to provider it is recommended to search for guidance through your provider support.</p>
                            </div>
                        </div>
                    </div>
                    <div class="upcasted-tools-option local-to-s3-cron">
                        <div class="upcasted-tools-option-header">
                            <div class="upcasted-tools-tool-name">
                                <span><strong>
                                    Migrate files to S3 bucket<?php 
        echo  ( uso_fs()->is_not_paying() ? '[Premium feature]' : '' ) ;
        ?>
                                </strong> <br/>
                                <strong>[</strong> From current server <strong>to</strong> AWS S3 <strong>]</strong></span>
                            </div>
                            <div class="upcasted-tools-tool-actions">
                                <?php 
        
        if ( uso_fs()->is_not_paying() ) {
            ?>
                                    <a href="<?php 
            echo  uso_fs()->get_upgrade_url() ;
            ?>"
                                       class="upcasted-button upcasted-upgrade-plan">Upgrade now</a>
                                <?php 
        } else {
            ?>
                                    <button id="cron-local-to-s3-button"
                                            class="upcasted-button upcasted-tool-button  <?php 
            echo  ( $run_local_to_s3_cron !== '' ? 'upcasted-stop-action-event upcasted-stop-action' : '' ) ;
            ?>">
                                        <?php 
            echo  ( $run_local_to_s3_cron !== '' ? 'Stop' : 'Start' ) ;
            ?>
                                    </button>
                                <?php 
        }
        
        ?>
                            </div>
                        </div>
                        <div class="upcasted-tools-option-footer">
                            <button class="upcasted-find-out-more"><span class="dashicons dashicons-warning"></span>
                                More info
                            </button>
                            <div class="upcasted-hidden-help-description">
                                <p>This tool runs in the background using default WordPress CRON. You can exit the
                                    browser window and the file migration will still run.</p>
                            </div>
                        </div>
                    </div>
                    <div class="upcasted-tools-option s3-to-local-cron">
                        <div class="upcasted-tools-option-header">
                            <div class="upcasted-tools-tool-name">
                                <span><strong>Migrate files back to current server <?php 
        echo  ( uso_fs()->is_not_paying() ? '[Premium feature]' : '' ) ;
        ?></strong> <br/>
                                <strong>[</strong>From S3 bucket <strong>to</strong> current server<strong>]</strong></span>
                            </div>
                            <div class="upcasted-tools-tool-actions">
                                <?php 
        
        if ( uso_fs()->is_not_paying() ) {
            ?>
                                    <a href="<?php 
            echo  uso_fs()->get_upgrade_url() ;
            ?>"
                                       class="upcasted-button upcasted-upgrade-plan">Upgrade now</a>
                                <?php 
        } else {
            ?>
                                    <button id="cron-s3-to-local-button"
                                            class="upcasted-button upcasted-tool-button <?php 
            echo  ( $run_s3_to_local_cron !== '' ? 'upcasted-stop-action-event upcasted-stop-action' : '' ) ;
            ?>">
                                        <?php 
            echo  ( $run_s3_to_local_cron !== '' ? 'Stop' : 'Start' ) ;
            ?>
                                    </button>
                                <?php 
        }
        
        ?>
                            </div>
                        </div>
                        <div class="upcasted-tools-option-footer">
                            <button class="upcasted-find-out-more"><span class="dashicons dashicons-warning"></span>
                                More info
                            </button>
                            <div class="upcasted-hidden-help-description">
                                <p>This tool runs in the background using default WordPress CRON. You can exit the
                                    browser window and the file migration will still run.</p>
                            </div>
                        </div>
                    </div>
                    <div class="upcasted-tools-option upcasted-define-batch">
                        <div class="upcasted-tools-option-header">
                            <div class="upcasted-tools-tool-name">
                                <span><strong>Define batch size <?php 
        echo  ( uso_fs()->is_not_paying() ? '[Premium feature]' : '' ) ;
        ?></strong></span>
                            </div>
                        </div>
                        <div class="upcasted-tools-option-body">
                            <input type="number" name="upcasted-custom-batch-size"
                                   id="custom-batch-size"
                                   placeholder="Default is 20"
                                   value="<?php 
        echo  $custom_batch_size ;
        ?>">
                            <?php 
        
        if ( uso_fs()->is_not_paying() ) {
            ?>
                                <a href="<?php 
            echo  uso_fs()->get_upgrade_url() ;
            ?>"
                                   class="upcasted-button upcasted-upgrade-plan">Upgrade now</a>
                            <?php 
        } else {
            ?>
                                <button id="custom-batch-size-button" class="upcasted-button <?php 
            echo  ( $run_s3_to_local_cron !== '' || $run_local_to_s3_cron !== '' ? 'upcasted-stop-action-event' : '' ) ;
            ?>">Save</button>
                                <div class="batch-size-message"></div>
                            <?php 
        }
        
        ?>
                        </div>
                        <div class="upcasted-tools-option-footer">
                            <button class="upcasted-find-out-more"><span class="dashicons dashicons-editor-help"></span>
                                Need help?
                            </button>
                            <div class="upcasted-hidden-help-description">
                                <p>This number represents the number of attachments that will be moved in one request.</p>
                                <p>Let's say you move one attachment that is an image and you set batch size to 1.
                                    If that attachment has four resized images it means the plugin will move five images.
                                    If you have a batch size of 5 it will move:  <br />5 attachments * (4 resized images + 1 main image) = 25 images in one batch.</p>
                                <p>This will help those who have powerful dedicated servers to upload more files at the same time.</p>
                                <p>This option is for advanced users only and should be used with <strong>CAUTION!</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php 
    }
    
    /**
     * Register and add settings
     */
    public function settings_page_init()
    {
        register_setting( 'general', UPCASTED_S3_OFFLOAD_SETTINGS );
    }
    
    /**
     * Adds new column "Storage" to media library list view 
     *
     * @since    3.0.2
     */
    public function add_cloudindicator_column( $columns )
    {
        $columns['cloudIndicator'] = __( 'File storage' );
        return $columns;
    }
    
    /**
     * Populates "Storage" column in media library list view with either bucket name or "Local storage"
     *
     * @since    3.0.2
     */
    public function add_cloudindicator_value( $columnName, $columnID )
    {
        
        if ( $columnName == 'cloudIndicator' ) {
            $metadata = wp_get_attachment_metadata( $columnID );
            echo  ( isset( $metadata['bucket'] ) ? "<b>Bucket:</b><br />" . $metadata['bucket'] : "Local server" ) ;
        }
    
    }
    
    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Upcasted_S3_Offload_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Upcasted_S3_Offload_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/css/upcasted-offload-admin.css' );
    }
    
    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Upcasted_S3_Offload_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Upcasted_S3_Offload_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/js/upcasted-offload-admin.js', array( 'jquery' ) );
        wp_localize_script( $this->plugin_name, 'upcasted_offload_s3_params', array(
            'ajaxurl'  => admin_url( 'admin-ajax.php' ),
            'settings' => get_option( UPCASTED_S3_OFFLOAD_SETTINGS ),
        ) );
        wp_enqueue_script( $this->plugin_name );
    }

}