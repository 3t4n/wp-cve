<?php

class MtwSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
    private $current_field;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_mtw_theme_page' ), 10 );
        add_action( 'admin_init', array( $this, 'admin_init' ), 10 );
        add_action( 'admin_enqueue_scripts', array( $this, 'init_settings_scripts'), 10 );
    }

    public function init_settings_scripts()
    {
        if ( function_exists( 'mtw_get_widgets_creator' ) ) 
        {
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script( 'wp-color-picker-transform', TTR_MW_PLUGIN_URL . 'scripts/color-picker/color-picker.js', array( 'wp-color-picker' ), false, true ); 
            wp_enqueue_script( 'mtw-ajax-to-form', TTR_MW_PLUGIN_URL . 'scripts/mtw-ajax-to-form/jquery.form.min.js', array( 'jquery' ), false, false );
        }
        wp_enqueue_media();
        wp_register_script( 'media-lib-uploader-js', plugins_url( 'media-lib-uploader.js' , __FILE__ ), array('jquery') );
        wp_enqueue_script( 'media-lib-uploader-js' );
    }

    /**
     * Add options page
     */
    public function add_mtw_theme_page()
    {
        global $submenu;

        if( current_user_can( 'manage_options' ) )
        {
            add_menu_page( 'MWuse', 'MWuse', 'manage_options', 'muse-to-wordpress-setting', array( $this, 'create_admin_page' ), NULL, 58 );
            
            add_action( 'admin_head', array( $this, 'admin_head_plugins_manager' ) );
            add_action( 'admin_footer', array( $this, 'admin_footer_plugins_manager' ) );

            add_submenu_page( 'muse-to-wordpress-setting', __('Upload', 'mwuse' ), __('Upload', 'mwuse' ), 'manage_options', 'mtw-upload' , array( $this, 'create_admin_page_upload' ) );

            add_submenu_page( 'muse-to-wordpress-setting', __('Plugins', 'mwuse' ), __('Plugins', 'mwuse' ), 'manage_options', 'mtw-plugin-manager' , array( $this, 'create_admin_page_plugins_manager' ) );
            
            $submenu['muse-to-wordpress-setting'][0][0] = __('Settings', 'mwuse' );

            if ( function_exists( 'mtw_get_widgets_creator' ) ) 
            {
                add_submenu_page( 'muse-to-wordpress-setting', __('Widgets Creator', 'mwuse' ), __('Widgets Creator', 'mwuse' ), 'manage_options', 'mtw-widgets-creator', array( $this, 'create_admin_page_shortcode_to_widget' ) );
            }   
            if ( function_exists( 'meAnjanWqg_ActivationHook' ) ) 
            {
                add_submenu_page( 'muse-to-wordpress-setting', __('WP Query Generator', 'mwuse' ), __('WP Query Generator', 'mwuse' ), 'manage_options', 'mtw-query-generator' , array( $this, 'create_admin_page_query_generator' ) );
                add_action( 'admin_head', array( $this, 'admin_head_query_generator' ) );
                add_action( 'admin_footer', array( $this, 'admin_footer_query_generator' ) );
            }

            do_action( 'mwuse_admin_menus' );

            //add_submenu_page( 'muse-to-wordpress-setting', __('Experience', 'mwuse' ), __('Experience', 'mwuse' ), 'manage_options', 'mtw-experience', array( $this, '' ) );
        }

    }

    public function create_admin_page_add_ons()
    {
        $this->musetowordpress_admin_style();
        ?>
        <div class="wrap">
            <h2><img src="<?php echo TTR_MW_PLUGIN_URL . "images/logo.png"; ?>"> <?php  _e('Add-ons', 'mwuse') ?></h2>  
        </div>
        <?php
    }

    public function create_admin_page_shortcode_to_widget()
    {
        $this->musetowordpress_admin_style();
        
        if ( function_exists( 'mtw_get_widgets_creator' ) ) 
        {
            echo mtw_get_widgets_creator();
        }
        else
        {
            ?>
            <div class="wrap">
            <h2><img src="<?php echo TTR_MW_PLUGIN_URL . "images/logo.png"; ?>"> <?php  _e('Widgets Creator', 'mwuse') ?></h2> 
            <p>Mwuser Only</p>
            </div>
            <?php
        }
    }

    private function delete_path($path)
    {
        if (is_dir($path) === true)
        {
            $files = array_diff(scandir($path), array('.', '..'));

            foreach ($files as $file)
            {
                $this->delete_path(realpath($path) . '/' . $file);
            }

            return rmdir($path);
        }

        else if (is_file($path) === true)
        {
            return unlink($path);
        }

        return false;
    }

    public function create_admin_page_upload()
    {

        $message_zip = "";
        if( sanitize_text_field( @$_POST['image'] ) && sanitize_text_field( @$_POST['image-id'] ) )
        {
            global $wp_filesystem;
            WP_Filesystem();
            $zip_path = get_attached_file( $_POST['image-id'] );
            $zip_info = pathinfo( $zip_path );
            $unzipfile = unzip_file( $zip_path, TTR_MW_TEMPLATES_PATH.'temp-folder');
            if ( $unzipfile ) {
                $message_zip = 'Successfully unzipped the file!';
                $temp_folder = array_diff(scandir(TTR_MW_TEMPLATES_PATH.'temp-folder'), array('..', '.'));
                $is_one_project = false;
                $zip_name = $zip_info['filename'];
                foreach ($temp_folder as $key => $temp_file) 
                {
                   if( !is_dir( TTR_MW_TEMPLATES_PATH.'temp-folder/' . $temp_file ) ) 
                   {
                        $is_one_project = true;
                   }
                }
                if( $is_one_project )
                {
                    if( file_exists(TTR_MW_TEMPLATES_PATH.$zip_name) )   
                    {
                        $this->delete_path(TTR_MW_TEMPLATES_PATH.$zip_name);
                    }
                    rename(TTR_MW_TEMPLATES_PATH.'temp-folder', TTR_MW_TEMPLATES_PATH.$zip_name );
                }
                else
                {
                    foreach ($temp_folder as $key => $temp_sub_folder) 
                    {
                        $new_project_folder = TTR_MW_TEMPLATES_PATH.$temp_sub_folder;
                        if( file_exists($new_project_folder) )   
                        {
                            $this->delete_path($new_project_folder);
                        }
                        rename(TTR_MW_TEMPLATES_PATH.'temp-folder/' . $temp_sub_folder, $new_project_folder );
                    }
                    $this->delete_path(TTR_MW_TEMPLATES_PATH.'temp-folder');    
                }

            } else {
                $message_zip = 'There was an error unzipping the file.';       
            }
        }

        $this->musetowordpress_admin_style();
        ?>
        <style type="text/css">
        input[type="submit"]
        {
            background-color: #00CC6D;
            color: #FFFFFF;
            cursor: pointer;
        }
        input[type="text"]
        {
            margin-bottom: 8px;
        }
        </style>
        <div class="wrap">
            <h2><img src="<?php echo TTR_MW_PLUGIN_URL . "images/logo.png"; ?>"> <?php  _e('Upload a Muse Theme', 'mwuse') ?></h2> <br/>
            <p>
                <?php echo $message_zip; ?>
            </p>
            <form method="post" id="zip-form">
              <input id="image-url" type="text" name="image" readonly />
              <input id="image-id" type="hidden" name="image-id" />
              <input id="upload-button" type="button" class="button" value="Upload a ZIP file" />
              
              <input type="submit" value="Unzip it in mtw-themes" />
            </form>

            <br/><hr/><br/>
            <p>
                <em><?php _e('The structure of your project in zip will always be the same: one folder by project.', 'mwuse'); ?><br/>
                <?php _e('If the folder already exists on the server, the entire project will be overwritten.', 'mwuse' ); ?><br/></em>
                <br/><br/>
                <b><?php _e('Example with one project' , 'mwuse'); ?></b><br/><br/>
                <img src="<?php echo TTR_MW_PLUGIN_URL . "images/zip-one-project.png"; ?>">
                <br/><br/><br/>
                <b><?php _e('Example with two project' , 'mwuse') ?></b><br/><br/>
                <img src="<?php echo TTR_MW_PLUGIN_URL . "images/zip-2-projects.png"; ?>">
            </p>
            
            <script type="text/javascript">
            jQuery(document).ready(function($){

              var mediaUploader;
              $('#image-url').click(function(event) {
                    event.preventDefault();
                    $('#upload-button').trigger('click');
              });
              $('#upload-button').click(function(e) {
                e.preventDefault();
                // If the uploader object has already been created, reopen the dialog
                  if (mediaUploader) {
                  mediaUploader.open();
                  return;
                }
                // Extend the wp.media object
                mediaUploader = wp.media.frames.file_frame = wp.media({
                  title: 'Choose an Adobe Muse exported website',
                  library : {
                    type : 'application/zip'
                  },
                  button: {
                  text: 'Choose a zipped themes'
                }, multiple: false });

                // When a file is selected, grab the URL and set it as the text field's value
                mediaUploader.on('select', function() {
                  var attachment = mediaUploader.state().get('selection').first().toJSON();
                  console.log( attachment );
                  $('#image-url').val(attachment.filename);
                  $('#image-id').val(attachment.id);
                });
                // Open the uploader dialog
                mediaUploader.open();
              });

                $('#zip-form').on('submit', function(event) {
                    if( $('#image-id').val() == "" )
                    {
                        event.preventDefault();
                    }                    
                });

            });
            </script>
        </div>
        <?php
    }

    public function create_admin_page_mwuser()
    {
        $this->musetowordpress_admin_style();
        ?>
        <div class="wrap">
            <h2><img src="<?php echo TTR_MW_PLUGIN_URL . "images/logo.png"; ?>"> <?php  _e('Mwuser only', 'mwuse') ?></h2>  
        </div>
        <?php
    }

    public function musetowordpress_admin_style()
    {
        $current_screen = get_current_screen();
        if( $current_screen->parent_base == 'muse-to-wordpress-setting' )
        {
        ?>
        <style type="text/css">
            .mtw-ver-title
            {
                font-size: 13px !important;
                color: #00CC6D;
            }
            #wpwrap, .wp-toolbar
            {
                background: #23242B;
                background: linear-gradient(to right,#23242B 44%,#04080A );
            }
            .wrap
            {
                max-width: 500px;
            }
            #page-linker
            {
                /*display: none;*/
            }
            ul#adminmenu a.wp-has-current-submenu::after,
            ul#adminmenu > li.current > a.current::after
            {
                border-right-color: #23242B !important;
            }
            h1, h2, h3
            {
                color: #E0E1E4;
            }
            .form-table th, label, #wpwrap p, .mtw-check-value
            {
                color: #C2C2C5;
            }
            .wp-admin input,
            .wp-admin select,
            .wp-admin textarea,
            .wp-admin button
            {
                color: #C2C2C5;
                background: rgba( 30,31,38,1 );
                border: none;
            }
            .wp-admin button
            {
                padding: 10px 10px;
            }
            .wp-admin button:hover
            {
                color: #00CC6D;
            }
            .wp-admin textarea,
            .wp-admin input[type=text]
            {
                width: 100%;
                margin-top: 10px;
            }
            .wp-admin select
            {
                width: 250px;    
            }
            
            .update-all-links
            {
                color: #00CC6D;
            }
            hr 
            {
                border: 0;
                border-bottom: 1px solid rgba( 30,31,38,1 );
            }
            a[href="admin.php?page=mtw-Mwuser"]
            {
                color: #00CC6D !important;
            }
            .wp-admin input:disabled,
            .wp-admin select:disabled
            {
                opacity: 0.5;
            }
            .form-table
            {
                max-width: 450px;
            }
            .mtw-check-value
            {
                cursor: pointer;
            }
            .notice 
            {
                background: rgba( 30,31,38,1 ) !important;
            }
            .notice p
            {
                color: #F4F4F2 !important;
            }
        </style>
        <script type="text/javascript">
        jQuery(document).ready(function($) {

            function mtw_verify_check_box()
            {
                $('input[type="checkbox"]').next('.mtw-check-value').css('color', '#8B8C91').text('false');
                $('input[type="checkbox"]:checked').next('.mtw-check-value').css('color', '#00CC6D').text('true');
            }
            mtw_verify_check_box();
            $('input[type="checkbox"]').on('change', function(event) {
                mtw_verify_check_box();
            });
            $('.mtw-check-value').click(function(event) {
                event.preventDefault();
                if( $(this).prev('input[type="checkbox"]:checked').length == 1 )
                {
                    $(this).prev('input[type="checkbox"]:checked').prop('checked', false);
                }
                else
                {
                    $(this).prev('input[type="checkbox"]').prop('checked', true);
                }
                mtw_verify_check_box();        
            });
        });
        </script>
        <?php
        }
    }
    
    

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->musetowordpress_admin_style();
        global $mtw_version;
        ?>
        <?php

        $this->options = get_option( 'mtw_option' );
        ?>
        <div class="wrap">
            <h2><img src="<?php echo TTR_MW_PLUGIN_URL . "images/logo.png"; ?>"> MWuse <span class="mtw-ver-title"><?php echo $mtw_version; ?></span></h2>  
            <br/><br/>
            
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'mtw_option_group' );   
                do_settings_sections( 'muse-to-wordpress-setting' );
                submit_button(); 
            ?>
            </form>
        
            <br/><hr/><br/>
            <a href="?page=muse-to-wordpress-setting&update_all_logic_links=1" class="update-all-links"><?php _e( 'Update all links', 'mwuse' ); ?> </a>
            <p><?php _e( 'Use "Update all links", If you are working <br/>without "Synchronize Muse and Wordpress Pages automatically".<br/><br/>
            If you have an error on one link, try it one time. If that\'s not working <a target="_blank" href="https://mwuse.com/#contact-us">contact us.</a>', 'mwuse' ); ?></p>
            <br/><hr/><br/>
            <a target="_blank" href="https://mwuse.com/"><?php _e( 'Learn more about Muse to Wordpress', 'mwuse' ); ?></a>
            <br/><br/>
            <p>
                <?php _e( 'MWuse is and will remain free and open-source forever,<br/>
                included all essentials elements and learning.
                <br/><br/>
                Why <a href="https://mwuse.com/join-us/">become a Mwuser</a> ?<br/>
                To benefit from a comfort of use and allow the project to evolve.', 'mwuse' ); ?>
            </p>
        </div>
        <br/><br/>
        <div id="page-linker">
        <?php
            ttr_page_linker();
        ?>
        </div>
        <?php
    }

    private function get_my_plugin_data_by_name( $name )
    {
        
        foreach ( get_plugins() as $key => $plugin ) 
        {
            if( $plugin['Name'] == $name )
            {
                $plugin['File'] = $key;
                return $plugin;
            }
            if( strpos($key, $name) === 0 )            
            {
               $plugin['File'] = $key;
               return $plugin; 
            }
        }
        return array();
    }

    private function get_plugin_link( $plugin, $installed, $is_plugin_active, $file )
    {
        $a_class = "";
        if( isset( $plugin['source'] ) )
        {
            $text = "Download";
            $target = "_blank";
            if( isset( $plugin['permalink'] ) )
            {
                $href =  $plugin['permalink'];    
            }
            else
            {
                $href =  $plugin['source'];
            }

            $class = 'download';
        }
        else
        {

            $text = "Install";
            $target = "_blank";
            $href = admin_url() . "plugin-install.php?tab=plugin-information&plugin=".$plugin['slug']."&";

            $class = 'install';
        }

        if( $installed && !$is_plugin_active )
        {
            $text = "Activate";
            $class = 'activate';

            $href = admin_url() . "plugins.php";
            $target = "_self";
        }

        ob_start();
        ?>
        <div class="row-actions visible"><span class="<?php echo $class; ?>"><a href="<?php echo $href; ?>" target="<?php echo $target; ?>" class="edit <?php echo $a_class; ?>" aria-label="<?php echo $text; ?>"><?php echo $text; ?></a></span></div>
        <?php
        return ob_get_clean();
    }

    public function create_admin_page_plugins_manager()
    {
        global $wp_filesystem;
        global $plugin_manager_list;
        $this->musetowordpress_admin_style();

        if( isset( $_GET['mtw-activate-plugin'] ) )
        {
            activate_plugin( sanitize_key( $_GET['mtw-activate-plugin'] ) );
        }
        ?>
        <style type="text/css">
            .wrap
            {
                max-width: 100%;
            }
            table
            {
                background-color: rgba(255, 255, 255, 0.8);
                color: #272822;
            }
        </style>
        <div class="wrap">
            <h2><img src="<?php echo TTR_MW_PLUGIN_URL . "images/logo.png"; ?>"> Plugins Manager</h2>  
            <br/><br/>
            <table width="100%" class="wp-list-table widefat fixed">
                <thead>
                    <tr>
                        <td>Plugin</td>
                        <td>Source</td>
                        <td>Type</td>
                        <td>Version</td>
                        <td>Status</td>
                    </tr>
                </thead>
                <?php
                ksort($plugin_manager_list);
                foreach ($plugin_manager_list as $key => $plugin) 
                {
                    if( isset( $plugin['source'] ) )
                    {
                        $source = 'External Source';
                        $respo = false;
                        $version_to_update = $plugin['version'];
                    }
                    else
                    {
                        $source = 'WordPress Repository';
                        $respo = true;
                        $version_to_update = '-';
                    }
                    if( isset( $plugin['required'] ) && $plugin['required'] == 1 )
                    {
                        $type = 'Required';
                    }
                    else
                    {
                        $type = 'Recommended';
                    }

                    $version_installed = '-';
                    
                    
                    $folder = str_replace('mwuse', $plugin['slug'], plugin_dir_path( dirname( __FILE__ ) ) );
                    $plugin_data = $this->get_my_plugin_data_by_name( $plugin['name'] );
                    $plugin_file = "";
                    
                    if( $wp_filesystem->exists( $folder ) && isset( $plugin_data['File'] ) ) 
                    {
                        $installed = true;
                        $plugin_file = $plugin_data['File'];
                        $is_plugin_active = is_plugin_active( $plugin_file );
                        $installed_txt = "yes";
                        $version_installed = $plugin_data['Version'];
                    }
                    elseif( $wp_filesystem->exists( $folder ) ) 
                    {
                        $installed = true;                        
                        $plugin_data = $this->get_my_plugin_data_by_name( basename( $folder ) );
                        $plugin_file = $plugin_data['File'];
                        $is_plugin_active = is_plugin_active( $plugin_file );
                        $installed_txt = "yes";
                        $version_installed = $plugin_data['Version'];
                    }
                    else
                    {
                        $installed = false;
                        $installed_txt = "no";
                        $is_plugin_active = false;
                    }

                    if( $is_plugin_active )
                    {
                        $is_plugin_active_txt = "yes";
                    }
                    else
                    {
                        $is_plugin_active_txt = "no";   
                    }

                    if( ( ($version_installed == $version_to_update && !$respo ) || ( $installed && $respo ) ) && $is_plugin_active )
                    {
                        continue;
                    }

                    if( version_compare($version_installed, $version_to_update) == 1 )
                    {
                        continue;
                    }

                    ?>
                    <tbody>
                        <tr>
                            <td>
                                <strong>
                                <?php 
                                    echo $plugin['name'];
                                ?>
                                </strong>

                                <?php
                                    
                                    echo $this->get_plugin_link( $plugin, $installed, $is_plugin_active, $plugin_file );
                                ?>
                            </td>
                            <td><?php echo $source; ?></td>
                            <td><?php echo $type; ?></td>
                            <td>
                                Version installed: <?php print_r( $version_installed ); ?><br/>
                                Version required: <?php echo $version_to_update; ?>
                            </td>
                            <td>
                                Installed: <?php print_r($installed_txt) ?><br/>
                                Active: <?php print_r($is_plugin_active_txt) ?>
                            </td>
                        </tr>
                    </tbody>
                    <?php
                };
                ?>
                <tfoot>
                    <tr>
                        <td>Plugin</td>
                        <td>Source</td>
                        <td>Type</td>
                        <td>Version</td>
                        <td>Status</td>
                    </tr>
                </tfoot>
            </table>
            
        </div>
        <?php

    }

    public function create_admin_page_query_generator()
    {
        $this->musetowordpress_admin_style();

        ?>
            <style type="text/css">
            #iframe-wp-query-generator
            {
                height: 800px;
                height: calc(100vh - 120px);
            }
            </style>
            <p>
                
            </p>
            <iframe id="iframe-wp-query-generator" width="100%" src="<?php echo get_admin_url().'tools.php?page=wp-query-generator'.'&mtw-iframe=1'; ?>" allowTransparency="true"></iframe>

        <?php
    }

    public function admin_head_query_generator()
    {
        if( (@$_GET['page'] == 'wp-query-generator' && @$_GET['mtw-iframe'] == '1') )
        {
            ?>
            <style type="text/css">
                #wpadminbar, #adminmenumain, #wpfooter
                {
                    display: none;
                }
                #wpcontent, #wpfooter {
                    margin-left: 0px !important;
                    padding-left: 0px !important;
                }
                html.wp-toolbar
                {
                    padding-top: 0px !important;
                }
                .wrap {
                    margin: 0px !important;
                }
                #wpwrap, .wp-toolbar
                {
                    background: #23242B;
                    background: linear-gradient(to right,#23242B 44%,#04080A );
                    background: transparent;
                }
                body
                {
                    max-width: 100% !important;
                    overflow-x: hidden;
                    background: transparent;
                }
                h2:first-child, #me-anjan-wqg-form > .me-anjan-wqg-tabpanel > .me-anjan-wqg-tab-buttons li a
                {
                    color: #E0E1E4 !important;
                }
                h2:first-child
                {
                    margin-bottom: 50px !important;
                }
                #me-anjan-wqg-form > .me-anjan-wqg-tabpanel > .me-anjan-wqg-tab-buttons li.active a
                {
                    color: #333 !important;
                }
            </style>
            <?php
        }
    }

    public function admin_footer_query_generator()
    {

        if( @$_GET['page'] == 'wp-query-generator' && @$_GET['mtw-iframe'] == '1' )
        {          
            ?>
            <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('form').submit();
                setTimeout( function(){$('[data-id="tab-button-params"]').click();}, 500);
                setTimeout( function(){$('[data-id="tab-button-params"]').click();}, 750);
                setTimeout( function(){$('[data-id="tab-button-params"]').click();}, 1000);
                setTimeout( function(){$('[data-id="tab-button-params"]').click();}, 1250);
                setTimeout( function(){$('[data-id="tab-button-params"]').click();}, 1500);
                $('[href="#me-anjan-wqg-tab-button-code"]').text('Generated JSON');    
                $('h2').first().prepend('<img src="<?php echo TTR_MW_PLUGIN_URL . "images/logo.png"; ?>"> ');
            });            
            </script>
            <?php
        }
    }

    public function admin_head_plugins_manager()
    {
        if( @$_GET['page'] == 'mtw-install-plugins' )
        {
            ?>
            <style type="text/css">
                #wpwrap, .wp-toolbar
                {
                    background: #23242B;
                    background: linear-gradient(to right,#23242B 44%,#04080A );
                }
                h1
                {
                    color: #E0E1E4 !important;
                    margin-bottom: 30px !important;
                }
                #wpbody-content p
                {
                    color: #C2C2C5;
                }
                .subsubsub a, .mwuser-plugin
                {
                    color: #00CC6D;
                }
                .subsubsub a.current
                {
                    color: #E0E1E4;
                }
                .subsubsub .count
                {
                    color: #A8ABB3 !important;
                }
                .bulkactions select
                {
                    color: #C2C2C5;
                    background: rgba( 30,31,38,1 );
                    border: none;
                }
                .bulkactions .button, .bulkactions .button:hover
                {
                    color: #FFFFFF;
                    background: #00CC6D;
                    border: none !important;
                    -webkit-box-shadow: none !important;
                    box-shadow: none !important;
                }
            </style>
            <?php
        }  
    }

    public function admin_footer_plugins_manager()
    {
        if( @$_GET['page'] == 'mtw-install-plugins' )
        {
            global $plugin_manager_list;
            global $plugin_manager_api_key;
            $plugins_json = json_encode( $plugin_manager_list );
            ?>
            <script type="text/javascript">
            var plugin_manager_api_key = "<?php echo $plugin_manager_api_key; ?>";
            jQuery(document).ready(function($) {  
                $('h1').first().prepend('<img src="<?php echo TTR_MW_PLUGIN_URL . "images/logo.png"; ?>"> ');
                $.get('https://musetowordpress.com/?mwuser-api-key-validation='+plugin_manager_api_key, function(data) {

                    var plugins_array = $.parseJSON('<?php echo $plugins_json; ?>');
                    $.each(plugins_array, function(index, val) {
                        td_plugin = $( '[data-colname="Plugin"] strong:contains("'+val['name']+'")' ).parent();
                        if( val['mwuser_only'] == '1' && data != 1 )
                        {
                            td_plugin.find('.install a').text('Join us').attr('href', 'https://musetowordpress.com/join-us/').prop('target', '_blank').click(function(event) {
                                window.location.reload();
                            });
                        }
                        if ( val['mwuser_only'] == '1' ) 
                        {
                            td_plugin.find('strong').first().append('<span class="mwuser-plugin"><br>Mwuser Only</span>');
                        }

                        td_plugin.find('.install').append(' - <a href="'+val['permalink']+'" target="_blank">More info</a>');
                        
                    });
                    
                });
                $('[data-colname="Type"]:contains("Required")').css({'color': '#ff0000', 'font-weight':'bold'});
            });            
            </script>
            <?php 
        }
    }
    /**
     * Register and add settings
     */
    public function admin_init()
    { 

        register_setting(
            'mtw_option_group', // Option group
            'mtw_option', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'mtw_first_setting_section', // ID
            __( 'General settings', 'mwuse' ), // Title
            array( $this, 'print_section_info' ), // Callback
            'muse-to-wordpress-setting' // Page
        );

        /*add_settings_field(
            'mtw_production_mode', // ID
            __('Production mode', 'mwuse'), // Title 
            array( $this, 'get_bool_field' ), // Callback
            'muse-to-wordpress-setting', // Page
            'mtw_first_setting_section', // Section 
            array( 'id' => 'mtw_production_mode' ) // Callback args
        );*/

        add_settings_field(
            'mtw_auto_page', // ID
            __('Synchronize Muse and Wordpress Pages automatically', 'mwuse'), // Title 
            array( $this, 'get_bool_field' ), // Callback
            'muse-to-wordpress-setting', // Page
            'mtw_first_setting_section', // Section 
            array( 'id' => 'mtw_auto_page' ) // Callback args
        );

        add_settings_field(
            'mtw_index_exclude', // ID
            __('Exclude "index" from hierarchy', 'mwuse'), // Title 
            array( $this, 'get_bool_field' ), // Callback
            'muse-to-wordpress-setting', // Page
            'mtw_first_setting_section', // Section 
            array( 'id' => 'mtw_index_exclude' ) // Callback args
        );

        add_settings_field(
            'mtw_slideshow_attachments', // ID
            __('Use post attachments on Slideshows', 'mwuse'), // Title 
            array( $this, 'get_bool_field' ), // Callback
            'muse-to-wordpress-setting', // Page
            'mtw_first_setting_section', // Section 
            array( 'id' => 'mtw_slideshow_attachments' ) // Callback args
        );

        add_settings_field(
            'mtw_default_project', // ID
            __('Default Muse project', 'mwuse'), // Title 
            array( $this, 'get_select_field' ), // Callback
            'muse-to-wordpress-setting', // Page
            'mtw_first_setting_section', // Section 
            array( 'id' => 'mtw_default_project', 'choices' => $this->muse_project_choices() ) // Callback args
        );
 
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        
        if( isset( $input['mtw_auto_page'] ) )
            $new_input['mtw_auto_page'] = $input['mtw_auto_page'] ;

        if( isset( $input['mtw_default_project'] ) )
            $new_input['mtw_default_project'] = $input['mtw_default_project'] ;

        if( isset( $input['id_number'] ) )
            $new_input['id_number'] = absint( $input['id_number'] );

        if( isset( $input['title'] ) )
            $new_input['title'] = sanitize_text_field( $input['title'] );

        //return $new_input;

        return $input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        //print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */

    public function muse_project_choices()
    {
        $projects = ttr_get_muse_projects();

        $return = array();

        foreach ($projects as $key => $value) {
            $return[] = $key;
        }

        return $return;
    }
    public function get_input_field( $args )
    {
        $id = $args['id'];
        ?>
        <input name="mtw_option[<?php echo $id; ?>]" type="text" placeholder="<?php echo $args['placeholder'] ?>" value="<?php echo $this->options[$id] ?>" >
        <?php
    }
    
    public function get_select_field( $args )
    {
        $id = $args['id'];
        $choices = $args['choices'];

        ?>
        <select name="mtw_option[<?php echo $id; ?>]" >
            <?php
            foreach ($choices as $value) {
                ?>
                <option <?php echo ( $this->options[$id] == $value ) ? 'selected' : '' ; ?> value="<?php echo $value ?>"><?php echo $value ?></option>
                <?php
            }
            ?>
        </select>
        <?php
    }

    public function get_bool_field( $args )
    {
        $id = $args['id'];
        
        printf(
            '<input type="checkbox" id="'.$id.'" name="mtw_option['.$id.']" value="checked" %s /> <span class="mtw-check-value">'.__('yes', 'mwuse').'</span>',
            isset( $this->options[$id] ) ? esc_attr( $this->options[$id]) : ''
        );
    }

}

if( is_admin() )
{
    $MtwSettingsPage = new MtwSettingsPage();
}

?>