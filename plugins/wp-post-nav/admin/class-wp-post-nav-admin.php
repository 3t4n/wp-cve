<?php

/**
 * WP Post Nav admin functionality.
 *
 * @link:      https://en-gb.wordpress.org/plugins/wp-post-nav/
 * @since      0.0.1
 *
 * @package    wp_post_nav
 * @subpackage wp_post_nav/includes
 */

// If this file is called directly, abort. //
if ( ! defined( 'ABSPATH' ) ) {
  exit;
} 

class wp_post_nav_admin {

  /**
   * The ID of this plugin.
   *
   * @since    0.0.1
   * @access   private
   * @var      string    $name    The ID of this plugin.
   */
  private $name;

  /**
   * The version of this plugin.
   *
   * @since    0.0.1
   * @access   private
   * @var      string    $version    The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    0.0.1
   * @var      string    $name       The name of this plugin.
   * @var      string    $version    The version of this plugin.
   */
  public function __construct( $name, $version ) {

    $this->name = $name;
    $this->version = $version;
    $this->textdomain = 'wp_post_nav';
    $this->option_name = $this->textdomain . '_options';

    // Initialise settings
    add_action( 'admin_init', array( $this, 'init' ) );
    add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' );
    add_action( 'admin_footer', array( $this, 'media_fields' ) );
  }

  /**
   * Initialise settings
   * @return void
   */
  public function init() {
    $this->settings = $this->settings_fields();
    $this->options = $this->get_options();
    $this->register_settings();
  }

  /**
   * Register the stylesheets for the Dashboard.
   *
   * @since    0.0.1
   */
  public function enqueue_styles() {

    /*
    * Enqueue the admin styles.  
     */

    //Admin CSS
    wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) . 'css/wp-post-nav-admin.css', array(), $this->version, 'all' );
      
    //the color picker styles (built in from WordPress) which is required for picking colours
    wp_enqueue_style( 'wp-color-picker' );
  }

  /**
   * Register the JavaScript for the dashboard.
   *
   * @since    0.0.1
   */
  public function enqueue_scripts() {

    //Admin JS
    wp_enqueue_script( $this->name, plugin_dir_url( __FILE__ ) . 'js/wp-post-nav-admin.js', array( 'jquery' ), $this->version, FALSE );
      
    //Colorpicker JS
    wp_enqueue_script( 'wp-color-picker' ); 
    
  }

  /**
  * Add settings link to the WordPress plugins page.
  *
  * @since    1.0.0
  */
  public function add_action_links( $links ) {
    return array_merge(
      array(
          'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->name ) . '">' . __( 'Settings', $this->name ) . '</a>'
      ),
      $links
    );
  }

  /**
  * Add admin menu.
  *
  * @since    1.0.0
  */
  public function add_plugin_admin_menu() {
    $plugin_screen_hook_suffix = add_options_page( __('WP Post Nav Settings', $this->name ), 'WP Post Nav', 'manage_options', $this->name, array($this, 'display_plugin_setup_page')
    );
  }

  /**
   * Design settings page. Were tabbed now so we can remove the old template file
   *
   * @since    1.0.0
   */
  public function display_plugin_setup_page() {
    ?>
    <div class="wrap" id="<?php echo $this->name; ?>">
      <h1><?php _e('WP Post Nav', $this->textdomain); ?></h1>

      <!-- Tab navigation starts -->
      <div id="wp-post-nav-wrapper">
        <div id="wp-post-nav-left" class="wp-post-nav-col">
          <h2 class="nav-tab-wrapper settings-tabs hide-if-no-js">
          <?php
          foreach( $this->settings as $section => $data ) {
            echo '<a href="#' . $section . '" id="' . $section . '" class="nav-tab">' . $data['title'] . '</a>';
          }
          ?>
          </h2>
        
          <form action="options.php" method="POST">
            <?php settings_fields( $this->name ); ?>
              <div class="settings-container">
                <?php do_settings_sections( $this->name ); ?>
              </div>
                <?php submit_button($text = 'Save Settings', $type = 'primary', $name = 'wp-post-nav-submit'); ?>
          </form>
        </div>
        
        <div id="wp-post-nav-right" class="wp-post-nav-col">
            <?php include_once( 'partials/wp-post-nav-admin-sidebar.php' );?>
        </div>
      </div>
    </div>
  <?php
  }
  
  /**
   * Build settings fields
   * @return array Fields to be displayed on settings page
   */
  private function settings_fields() {
    $settings['post_types'] = array(
      'title'         => __( 'Post Types', $this->textdomain ),
      'description'     => __( 'Select The Post Types You Will Use The Navigation On.  If You Are Using The Shortcode Option, Anything You Check Here Will Have No Effect.', $this->textdomain ),
      'fields'        => array(
        array(
          'label' => 'Post Types',
          'id' => 'wp_post_nav_post_types',
          'type' => 'checkbox',
          'section' => 'post_types',
          'desc' => 'Select The Post Types To Display WP Post Nav On.',
          'default'=> '',
        ),
        array(
          'label' => 'Use The Same Category',
          'id' => 'wp_post_nav_same_category',
          'type' => 'checkbox',
          'options' => array(
            'Yes' => 'Yes',
          ),
          'section' => 'post_types',
          'desc' => '',
          'default'=> '',
          'after' => 'Only Show Posts From The Same Category As The Current Post?',
        )
      )
    );

    //check if yoast seo is active so we can use the primary category filter
    if ( class_exists('WPSEO_Primary_Term') ) {
      $settings['post_types']['fields'][] = array (
          'label' => 'Use Yoast SEO Primary Category?',
          'id' => 'wp_post_nav_yoast_seo',
          'type' => 'checkbox',
          'section' => 'post_types',
          'options' => array(
            'Yes' => 'Yes',
          ),
          'default'=> '',
          'after' => 'Show The Next / Previous Posts In The Same \'Primary Category\' From Yoast SEO? Warning, This Will Override Showing Posts From The Same Category If Selected Above',
          'desc' => 'Use The Yoast SEO Primary Category For The Same Category',
        );
    }

    //check if yoast seo is active so we can use the primary category filter
    if ( function_exists( 'the_seo_framework' )  ) {
      $settings['post_types']['fields'][] = array (
          'label' => 'Use SEO Framework Primary Category?',
          'id' => 'wp_post_nav_seo_framework',
          'type' => 'checkbox',
          'section' => 'post_types',
          'options' => array(
            'Yes' => 'Yes',
          ),
          'default'=> '',
          'after' => 'Show The Next / Previous Posts In The Same \'Primary Category\' From the SEO Framework?  Warning, This Will Override Showing Posts From The Same Category If Selected Above',
          'desc' => 'Use The SEO Framework Primary Category For The Same Category',
        );
    }

    //add option to skip if next / previous doesnt have primary assigned
    if (class_exists('WPSEO_Primary_Term') || function_exists( 'the_seo_framework' )) {
      $settings['post_types']['fields'][] = array (
          'label' => 'Force Excluding The Next / Previous Post If The Primary Assigned Is Different?',
          'id' => 'wp_post_nav_exclude_primary',
          'type' => 'checkbox',
          'section' => 'post_types',
          'options' => array(
            'Yes' => 'Yes',
          ),
          'default'=> 'Yes',
          'after' => 'If The Next Or Previous Post Isnt Assigned The Same Primary Category As The Current One, Don\'t Show It.<br><strong>We Recommend This Is Checked To Avoid Weird Issues In Navigation.</strong>',
          'desc' => 'Exclude Posts That Dont Have Primary Categories Assigned',
        );
    }
    
    //check if woocommerce installed and active, if so - offer to remove out of stock products
    if ( class_exists( 'woocommerce' ) ) {
       $settings['post_types']['fields'][] = array(
          'label' => 'Exclude Out Of Stock Products?',
          'id' => 'wp_post_nav_out_of_stock',
          'type' => 'checkbox',
          'section' => 'post_types',
          'options' => array(
            'Yes' => 'Yes',
          ),
          'default'=> '',
          'after' => 'Exclude WooCommerce Products Which Are Out Of Stock?',
          'desc' => 'This Will Exclude Products Market \'out of stock\' Within WooCommerce From The Navigation',
        );
     }

    $settings['general'] = array(
      'title'         => __( 'General Settings', $this->textdomain ),
      'description'     => __( 'Change The Settings For How The Navigation Works.', $this->textdomain ),
      'fields'        => array(
      array(
        'label' => 'Switch Display',
        'id' => 'wp_post_nav_switch_nav',
        'type' => 'checkbox',
        'section' => 'general_settings',
        'options' => array(
          'Yes' => 'Yes',
        ),
        'default'=> '',
        'after' => 'Switch The Next / Previous Display Side?',
        'desc' => 'The Default Display Shows The \'Next\' Post Link On THe Right, And The \'Previous\' Post Link On The Left',
      ),
      array(
        'label' => 'Show Title',
        'id' => 'wp_post_nav_show_title',
        'type' => 'checkbox',
        'section' => 'general_settings',
        'options' => array(
          'Yes' => 'Yes',
        ),
        'desc' => '',
        'default'=> '',
        'after' => 'Show The Post Title On The Navigation?',
      ),
      array(
        'label' => 'Show Category',
        'id' => 'wp_post_nav_show_category',
        'type' => 'checkbox',
        'section' => 'general_settings',
        'options' => array(
          'Yes' => 'Yes',
        ),
        'desc' => '',
        'default'=> '',
        'after' => 'Show The Category The Post Is From On The Navigation?',
      ),
      array(
        'label' => 'Show Post Excerpt',
        'id' => 'wp_post_nav_show_post_excerpt',
        'type' => 'checkbox',
        'section' => 'general_settings',
        'options' => array(
          'Yes' => 'Yes',
        ),
        'desc' => '',
        'default'=> '',
        'after' => 'Show The Post Excerpt On The Navigation?',
      ),
      array(
        'label' => 'Excerpt Length',
        'id' => 'wp_post_nav_excerpt_length',
        'type' => 'text',
        'section' => 'general_settings',
        'desc' => 'How Many Characters Of The Post Excerpt Should The Navigation Show?',
        'default'=> '300',
        'after' => '(characters)',
      ),
      array(
        'label' => 'Show Featured Image',
        'id' => 'wp_post_nav_show_featured_image',
        'type' => 'checkbox',
        'section' => 'general_settings',
        'options' => array(
          'Yes' => 'Yes',
        ),
        'desc' => '',
        'default'=> '',
        'after' => 'Show The Post Featured Image?',
      ),
      array(
        'label' => 'FallBack Image',
        'id' => 'wp_post_nav_fallback_image',
        'type' => 'media',
        'section' => 'general_settings',
        'desc' => 'Select A Fallback Image For Posts Without An Image.  A default fallback image is used if you dont change this.',
        'default' => plugin_dir_url( __FILE__ ) . '../public/images/default_fallback.png',
        'after' => '',
        ),
      ),
    );

    $settings['styles'] = array(
      'title'         => __( 'Styles', $this->textdomain ),
      'description'     => __( 'Change the Styling Of The Navigation.', $this->textdomain ),
      'fields'        => array(
          array(
          'label' => 'Nav Button Width',
          'id' => 'wp_post_nav_nav_button_width',
          'type' => 'text',
          'section' => 'post_nav_styles',
          'desc' => 'Choose The Width Of The Nav Button.',
          'default'=> '100',
          'after' => '(px)',
        ),
        array(
          'label' => 'Nav Button Height',
          'id' => 'wp_post_nav_nav_button_height',
          'type' => 'text',
          'section' => 'post_nav_styles',
          'desc' => 'Choose The Height Of The Nav Button.',
          'default' => '100',
          'after' => '(px)',
        ),
        array(
          'label' => 'Nav Background',
          'id' => 'wp_post_nav_background_color',
          'type' => 'color',
          'section' => 'post_nav_styles',
          'desc' => 'Choose The Background Colour Of The Button.',
          'default' => '#8358b0', 
          'after' => '',
        ),
        array(
          'label' => 'Nav Open Background',
          'id' => 'wp_post_nav_open_background_color',
          'type' => 'color',
          'section' => 'post_nav_styles',
          'desc' => 'Choose The Background Colour When Open.',
          'default' => '#8358b0',
          'after' => '', 
        ),
        array(
          'label' => 'Heading Font Colour',
          'id' => 'wp_post_nav_heading_color',
          'type' => 'color',
          'section' => 'post_nav_styles',
          'desc' => 'Choose The Heading Colour.',
          'default' => '#ffffff',
          'after' => '', 
        ),
        array(
          'label' => 'Heading Font Size',
          'id' => 'wp_post_nav_heading_size',
          'type' => 'text',
          'section' => 'post_nav_styles',
          'desc' => 'Choose The Heading Font Size.',
          'default' => '16',
          'after' => '(px)',
        ),
        array(
          'label' => 'Title Font Colour',
          'id' => 'wp_post_nav_title_color',
          'type' => 'color',
          'section' => 'post_nav_styles',
          'desc' => 'Choose The Title Colour.',
          'default' => '#ffffff',
          'after' => '', 
        ),
        array(
          'label' => 'Title Font Size',
          'id' => 'wp_post_nav_title_size',
          'type' => 'text',
          'section' => 'post_nav_styles',
          'desc' => 'Choose The Title Font Size.',
          'default' => '13',
          'after' => '(px)',
        ),
        array(
          'label' => 'Category Font Colour',
          'id' => 'wp_post_nav_category_color',
          'type' => 'color',
          'section' => 'post_nav_styles',
          'desc' => 'Choose The Category Colour.',
          'default' => '#ffffff',
          'after' => '', 
        ),
        array(
          'label' => 'Category Font Size',
          'id' => 'wp_post_nav_category_size',
          'type' => 'text',
          'section' => 'post_nav_styles',
          'desc' => 'Choose The Category Font Size.',
          'default' => '13',
          'after' => '(px)',
        ),
        array(
          'label' => 'Excerpt Font Colour',
          'id' => 'wp_post_nav_excerpt_color',
          'type' => 'color',
          'section' => 'post_nav_styles',
          'desc' => 'Choose The Excerpt Colour.',
          'default' => '#ffffff',
          'after' => '', 
        ),
        array(
          'label' => 'Excerpt Font Size',
          'id' => 'wp_post_nav_excerpt_size',
          //field type change from number to aid validation when switch tabs
          'type' => 'text',
          'section' => 'post_nav_styles',
          'desc' => 'Choose The Excerpt Font Size.',
          'default' => '12',
          'after' => '(px)',
        ),
      )
    );

    $settings['shortcode'] = array(
      'title'         => __( 'Shortcode', $this->textdomain ),
      'id' => 'shortcode',
      'description'     => __( 'Use The WP Post Nav Shortcode', $this->textdomain ),
      'fields'        => array(
        array(
          'label' => 'Display Navigation As Shortcode',
          'id' => 'wp_post_nav_shortcode',
          'type' => 'checkbox',
          'options' => array(
            'Yes' => 'Yes',
          ),
          'section' => 'shortcode',
          'desc' => '
                <h4>WARNING.  Select This Option To NOT Show The Default Navigation.</h4>
                <p>Version 2.0.0 Introduced the option to display the navigation as a shortcode.</p>
                <p>When using this option, you have complete control over the navigation display and it can be placed anywhere in your theme.</p>
                <p>The basic shortcode is <pre><code>[wp_post_nav]</code></pre></p>  
                <p>Simply drop this into your post / page or product template (either with your default page builder, Gutenberg etc), or by PHP code <pre><code>echo do_shortcode(\'[wp_post_nav]\')</code></pre> and the basic navigation will display.</p>
                <p>The navigation uses the same options as the default settings and styles you can choose on the default WP Post Nav tabs.</p>
                <hr>
                <h4>Styles</h4>
                <p>When using the shortcode option of the plugin, the styles selected on the styles tab will still work as intended.  Set the NAV BACKGROUND colour as desired, and leave the button heights and widths as they will not work.  All other styles, settings and colours will work as normal.</p>
                <p>If you are still using the default navigation in addition to the shortcode, some potential style issues can occur. All of the styles can be overriden with CSS adding !important after each decalration.</p>
                <p>For example to override the background colour, add the following custom CSS (in either the customizer, child theme or similar)
                <pre><code>.wp-post-nav-shortcode {<br>background:#fff !important;<br>}</code></pre> and this will set the shortcode background to be white, allowing any colour for the floating navigation.</p>
                <hr>
                <h4>Overrides</h4>
                <p>The shortcode has 2 additional options.</p>
                <ul>
                <li>display_previous = return false on this to disable showing the previous post.</li>
                <li>display_next = return false on this to disable showing the next post.</li>
                </ul>
                <p>To display the next and previous navigation, but show the next post and previous post links in different positions you can do the following:
                <pre><code>[wp_post_nav display_previous="false"]</code></pre>
                <p>This will show only the next post link and you do the same for the previous post link.</p>',
          'default'=> '',
          'after' => 'Do You Want To Display The Navigation As A Shortcode?',
        )
      )
    );

    $settings['instructions'] = array(
      'title'         => __( 'Instructions', $this->textdomain ),
      'id' => 'instructions',
      'description'     => include_once( 'partials/wp-post-nav-admin-instructions.php' ),
      'fields'        => ''
    );

    $settings = apply_filters( 'wp-post-nav-settings', $settings );

    return $settings;
  }

  /**
   * Options getter
   * @return array Options, either saved or default ones.
   */
  public function get_options() {
    $options = get_option($this->option_name);

    if ( !$options && is_array( $this->settings ) ) {
      $options = Array();
      foreach( $this->settings as $section => $data ) {
        foreach( $data['fields'] as $field ) {
          $options[ $field['id'] ] = $field['default'];
        }
      }

      add_option( $this->option_name, $options );
    }

    return $options;
  }

  /**
   * Register plugin settings
   * @return void
   */
  public function register_settings() {
    if( is_array( $this->settings ) ) {
      
      //register the setting
      register_setting( $this->name, $this->option_name, array( $this, 'validate_fields' ) );
      
      foreach( $this->settings as $section => $data ) {

        // Add section to page
        add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), $this->name );
        //add this query to allow using a tab for instructions
        if ($data['fields']) {
          foreach( $data['fields'] as $field ) {

            // Add field to page
            add_settings_field( $field['id'], $field['label'], array( $this, 'display_field' ), $this->name, $section, array( 'field' => $field ) );
          }
        }
      }
    }
  }

  /**
   * Validate individual settings field
   * @param  array $data Inputted value
   * @return array       Validated value
   */
  public function validate_fields( $data ) {
    //get the latest saved option in case validation fails and we need to re-add the correct values back
    $default_options = get_option($this->option_name);
    
    //Throw an info message if they havent selected any post types
    $settings_errors = [];
    if (!array_key_exists('wp_post_nav_post_types',$data)) {
        $settings_errors[] = add_settings_error( $this->name, 'no-post-types', __('You Haven\'t Selected Any Post Types. WP Post Nav Wont Work', $this->textdomain), 'info' );
    }
      
    foreach ($data as $key => $validation) {
      
      switch( $key ) {
        //check the image field is an allowed image, if not use the fallback
        case 'wp_post_nav_fallback_image':
          $image = $validation;

          $file_parts = pathinfo($image, PATHINFO_EXTENSION);
          $allowed_extensions = array('jpg','png','gif');

          if (in_array($file_parts, $allowed_extensions)){
              //set the value to the new image
              $data[$key] = $image;
          }
          else {  
            if (empty($validation)) {
              $settings_errors[] = add_settings_error( $this->name, 'default-fallback', __('Default Fallback Image Will Be Used.', $this->textdomain), 'info' );
            }

            else {
              $settings_errors[] = add_settings_error($this->name, 'default-fallback', __('Images Must Be PNG, JPG Or Gif.', $this->textdomain), 'error'  );
              //set the value to the old image value
              $data[$key] = $default_options[$key];
            }
          }

        break;

        //checkbox fields
        case 'wp_post_nav_same_category':
        case 'wp_post_nav_use_shortcode':
        case 'wp_post_nav_switch_nav':
        case 'wp_post_nav_show_title':
        case 'wp_post_nav_show_category':
        case 'wp_post_nav_show_post_excerpt':
        case 'wp_post_nav_show_featured_image':
        case 'wp_post_nav_out_of_stock':
        case 'wp_post_nav_yoast_seo': 
        case 'wp_post_nav_seo_framework':
        case 'wp_post_nav_exclude_primary':
        
        break;

        //numeric validation - check if the field is numeric, if not send an error
        case 'wp_post_nav_excerpt_length':
        case 'wp_post_nav_nav_button_width':
        case 'wp_post_nav_nav_button_height':
        case 'wp_post_nav_title_size':
        case 'wp_post_nav_excerpt_size':
        case 'wp_post_nav_category_size':
          // check the new value, if its not numeric
          if (!is_numeric($validation)){
              $error_message = str_replace("wp_post_nav_",' ',$key);
              $error_message = str_replace("_",' ',$error_message);
              $error_message = ucwords($error_message);
              $settings_errors[] = add_settings_error( $this->name, 'font-size', __($error_message . ' Must Be Numeric.', $this->textdomain), 'error'  );
              //set the value to the old allowed value
              $data[$key] = $default_options[$key];
              
          }
          //value is numeric
          else{
            //take the new value entered
            $data[$key] = sanitize_text_field($validation); 
          }
        break;

        case 'wp_post_nav_background_color':
        case 'wp_post_nav_open_background_color]':
        case 'wp_post_nav_title_color':
        case 'wp_post_nav_category_color':
        case 'wp_post_nav_excerpt_color':
          $color = trim( $validation );
          $color = strip_tags( stripslashes( $color ) );
           
          // Check if is a valid hex color
          if( FALSE === $this->check_color( $color ) ) {
            // Set the error message
            $error_message = str_replace("wp_post_nav_",' ',$key);
            $error_message = str_replace("_",' ',$error_message);
            $error_message = ucwords($error_message);
            add_settings_error( $this->name, 'font-size', __($error_message . ' Must Be A Colour Starting With # (hash).', $this->textdomain), 'error'  );
            //set the value to the old allowed value
            $data[$key] = $default_options[$key];
              
          } 

          else {
            //the value is allowed so set it
            $data[$key] = $validation;  
          }
        break;

        //default validation
        default:
         $data[$key] = $validation;
        break;
      }
    }
    //return the array with either errors or valid options
    return $data;
  }

  //check input is valid hex color - custom option called from validation array
  public function check_color( $value ) { 
       
      if ( preg_match( '/^#[a-f0-9]{6}$/i', $value ) ) { // if user insert a HEX color with #     
          return true;
      }
       
      return false;
  }

  public function settings_section( $section ) {
    $html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";
    echo $html;
  }

  /**
   * Generate HTML for displaying fields
   * @param  array $args Field data
   * @return void
   */
  public function display_field( $args ) {
    
    $field = $args['field'];

    $html = '';

    $option_name = $this->option_name ."[". $field['id']. "]";

    $data = (isset($this->options[$field['id']])) ? $this->options[$field['id']] : '';
    //convert the value field into the data array given
    $value = $data;

    if ($field['id'] == 'wp_post_nav_post_types'  ) {
          
          $args = array(
                        'public'   => true,
                        //'_builtin' => true,
                      );             
          $output = 'names'; // names or objects,
          $operator = 'and'; // 'and' or 'or'

          //setup the array of excluded post types used as standard
          $excluded_post_types = array (
                    'attachment' => 'attachment', 
                  );

          //add the developer hook to modify the array
          $excluded_post_types = apply_filters( 'wp-post-nav-post-type', $excluded_post_types);
          
          //get all the registered post types
          $post_types = get_post_types( $args, $output, $operator ); 
          
          //firstly check that there are post types available
          if (!empty( $post_types)) {

            //required for initial setup to stop displaying errors.  even though on inital activation, we set the default post type as 'post', it throws an error as its searching for an array that doesnt yet exist.
            if (empty($data)) {

              $options_markup = '';
              $iterator = 0;
              $print_post_type = [];

              foreach( $post_types as $key => $label ) {

                //if the post type is secluded, skip over it
                if ($excluded_post_types && in_array ($label, $excluded_post_types)) {
                  continue;
                }

                else {
                  $checked = '';
                  
                  if( $data && 'on' == $data ){
                    $checked = 'checked';
                  }

                  $iterator++;
                  $options_markup.= 
                  sprintf(
                    '<label for="%1$s_%6$s"><input id="%1$s_%6$s" class="post_type_option" name="' . esc_attr( $option_name ) . '['.$label.']" type="%2$s" value="%3$s" %4$s /> %5$s </label>  ',
                      $field['id'],
                      $field['type'],
                      $key,
                      $checked,
                      $label,
                      $iterator
                    );
                  }//end                  
                }
              }
            else {
              $options_markup = '';
              $iterator = 0;
                  
              foreach( $post_types as $key => $label ) {

                  //if the post type is secluded, skip over it
                  if ($excluded_post_types && in_array ($label, $excluded_post_types)) {
                    continue;
                  }

                  else {
                    $v = array_search($label, $data);
                    
                    if ($v !== false) {
                          $checked = 'checked';
                      } else {
                          $checked = '';
                      }
                    
                    $iterator++;
                    $options_markup.= 
                    sprintf(
                      '<label for="%1$s_%6$s"><input id="%1$s_%6$s" class="post_type_option" name="' . esc_attr( $option_name ) . '['.$label.']" type="%2$s" value="%3$s" %4$s /> %5$s </label>',
                      $field['id'],
                      $field['type'],
                      $key,
                      $checked,
                      $label,
                      $iterator
                    );
                  }
                  }//end
              }
            }//end if
            printf( '<fieldset id="post_type_options">%s</fieldset>',$options_markup);
            if( $desc = $field['desc'] ) {
              printf( '<p class="description">%s </p>', $desc );
            }
    }//end if field is

    switch( $field['type'] ) {

      //media field, notice the callback to an interior function for displaying the images
      case 'media':
        
        if (!empty($value)) {
          $value = $value;
        }

        else {
          $value = $field['default'];
        }

        $image = $value;

        printf(
          '<input style="width: 40%%" id="'. esc_attr( $option_name ).'" name="' . esc_attr( $option_name ).'" type="text" value="'. esc_attr( $value ).'"> <input style="width: 19%%" class="button wp_post_nav_media" id="' . esc_attr( $option_name ). '_button" name="' . esc_attr( $option_name ) . '_button" type="button" value="Upload" /><img id="fallback-image" style="width: 50px; height:50px; margin-left:20px; vertical-align:bottom; border:solid 1px #8358b0;" src="' . $image . '" /><br>',
          $field['id'],
          $field['id'],
          $value,
          $field['id'],
          $field['id']
        );
        if( $desc = $field['desc'] ) {
          printf( '<p class="description">%s </p>', $desc );
        }
      break;

      //standard checkbox fields
      case 'checkbox':
        if( ! empty ( $field['options'] ) && is_array( $field['options'] ) ) {

          $checked = '';
          $options_markup = '';
          $iterator = 0;
          foreach( $field['options'] as $key => $label ) {
              if (!empty($data)) {
                $checked = 'checked';
              }

              else {
                  $checked = '';
              }

              $options_markup.= sprintf('<label for="%1$s_%6$s"><input id="%1$s_%6$s" name="' . esc_attr( $option_name ) . '['.$label.']" type="%2$s" value="%3$s" %4$s/></label>  ' . $field['after'],
              $field['id'],
              $field['type'],
              $key,
              $checked,
              $label,
              $iterator
             
              );
              if ($iterator++ == 1) break;
            }
            printf( '<fieldset>%s</fieldset>',$options_markup);
            if( $desc = $field['desc'] ) {
                printf( '<p class="description">%s </p>', $desc );
              }
        }
      break;

      //color picker field - class needs to be added to make the built in colorpicker work.  We need to add the default handle 
      //for the js to pick this up to apply default colours on settings
      case 'color':
        printf( '<input name="%1$s" id="%1$s" type="%2$s" class="color-field" default="%4$s" value="%3$s" /> ' . $field['after'],
        esc_attr( $option_name ),$field['type'],$value,$field['default']
      );
        if( $desc = $field['desc'] ) {
          printf( '<p class="description">%s </p>', $desc );
        }
      break;
      default:

      if (!empty($value)) {
        $value = $value;
      }

      else {
        $value = $field['default'];
      }

      printf( '<input name="%1$s" id="%1$s" type="%2$s" value="%3$s" />' . $field['after'],
        esc_attr( $option_name ) . $field['id'],
        $field['type'],
        $value
      );
      
      if( $desc = $field['desc'] ) {
      printf( '<p class="description">%s </p>', $desc );
    }
  }
}

//function for adding and updating media fields of image uploads
public function media_fields() {
    ?><script>
      jQuery(document).ready(function($){
        if ( typeof wp.media !== 'undefined' ) {
          var mediaUploader;
          $('.wp_post_nav_media').click(function(e) {
            e.preventDefault();
            var button = $(this);
            var id = button.attr('id').replace('_button', '');
            if (mediaUploader) {
              mediaUploader.open();
              return;
            }
          
            mediaUploader = wp.media.frames.file_frame = wp.media({
              title: 'Choose Fallback',
              button: {
                text: 'Choose Fallback'
              }, 
              multiple: false 
            });
          
            mediaUploader.on('select', function() {
              var attachment = mediaUploader.state().get('selection').first().toJSON();
              var input = document.getElementById(id);
    
              input.value = attachment.url;
              $img_placeholder = document.getElementById('fallback-image').src = input.value;
          });
          
          mediaUploader.open();
        }); 
        }
        
    }); 
    </script>
    <?php
  }
}