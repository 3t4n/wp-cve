<?php
class Woocommerce_Catalog_Enquiry_Settings {
      
  /**
   * Start up
   */
  public function __construct() {
    // Admin menu
    add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'catalog_admin_enqueue_scripts' ) );
  }
  
  /**
   * Add options page
   */
  public function add_settings_page() {
    global $submenu;
    $slug = 'catalog';
    $dashboard = add_menu_page( __( 'catalog', 'woocommerce-catalog-enquiry' ), __( 'Catalog', 'woocommerce-catalog-enquiry' ), 'manage_woocommerce', $slug, [ $this, 'mvx_catalog_callback' ],  'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><g fill="#9EA3A8" fill-rule="nonzero"><path d="M7.8,5.4c0,0.5-0.4,0.9-0.9,0.9C6.6,6.3,6.3,6,6.1,5.7c0-0.1-0.1-0.2-0.1-0.3    c0-0.5,0.4-0.9,0.9-0.9c0.1,0,0.2,0,0.3,0.1C7.6,4.7,7.8,5,7.8,5.4z M5,7.4c-0.1,0-0.2,0-0.2,0c-0.6,0-1.1,0.5-1.1,1.1    C3.6,9,4,9.4,4.4,9.6c0.1,0,0.2,0.1,0.3,0.1c0.6,0,1.1-0.5,1.1-1.1C5.9,7.9,5.5,7.5,5,7.4z M5.8,1.7c-0.6,0-1,0.5-1,1s0.5,1,1,1    s1-0.5,1-1S6.3,1.7,5.8,1.7z M2.9,2.1c-0.3,0-0.5,0.2-0.5,0.5s0.2,0.5,0.5,0.5s0.5-0.2,0.5-0.5S3.2,2.1,2.9,2.1z M0.8,5.7    C0.3,5.7,0,6.1,0,6.5s0.3,0.8,0.8,0.8s0.8-0.3,0.8-0.8S1.2,5.7,0.8,5.7z M20,10.6c-0.1,4.3-3.6,7.7-7.9,7.7c-1.2,0-2.3-0.3-3.4-0.7    l-3.5,0.6l1.4-2c-1.5-1.4-2.5-3.5-2.5-5.7c0-0.2,0-0.4,0-0.5c0.3,0.1,0.6,0.1,0.9,0C5.9,9.7,6.4,9,6.3,8.3c0-0.2-0.1-0.4-0.2-0.5    C5.7,7,4.9,6.8,4.2,6.9C4,7,3.8,7,3.7,7C3,6.9,2.5,6.4,2.4,5.8c-0.2-1,0.6-1.9,1.6-1.9C4.6,4,5.1,4.4,5.3,5c0,0.1,0,0.2,0,0.2    c0.1,0.5,0.4,1,0.9,1.2c0.2,0.1,0.5,0.2,0.7,0.2c0.7,0,1.3-0.6,1.3-1.3c0-0.5-0.3-1-0.8-1.2c1.4-1.1,3.2-1.7,5.1-1.6    C16.7,2.8,20.1,6.3,20,10.6z M14.9,8.2c0-0.3-0.2-0.5-0.5-0.5H9.9c-0.3,0-0.5,0.2-0.5,0.5v4.6c0,0.3,0.2,0.5,0.5,0.5h2.6l0.5,1.1    h1.2l-0.5-1.1h0.9c0.3,0,0.5-0.2,0.5-0.5V8.2z M10.4,12.2h1.6l-0.3-0.6l0.9-0.4l0.5,1h0.8V8.7h-3.5V12.2z"/></g></svg>'), 50 );

    if (apply_filters('mvx_catalog_add_query', false)) {
      add_submenu_page( 'catalog', __( 'Customer Queries', 'woocommerce-catalog-enquiry' ), __( 'Customer Queries', 'woocommerce-catalog-enquiry' ), 'manage_woocommerce', 'catalog#&tab=customer&subtab=queries', '__return_null' );
    }
    add_submenu_page( 'catalog', __( 'Settings', 'woocommerce-catalog-enquiry' ), __( 'Settings', 'woocommerce-catalog-enquiry' ), 'manage_woocommerce', 'catalog#&tab=settings&subtab=general', '__return_null' );

    if (apply_filters('mvx_catalog_free_only_active', true)) {
      $submenu[ $slug ][] = [ __( '<div id="upgrade-to-pro"><i class="mvx-catalog icon-upgrade-to-pro-tab"></i>Upgrade to pro</div>', 'woocommerce-catalog-enquiry' ), 'manage_woocommerce', 'https://multivendorx.com/woocommerce-request-a-quote-product-catalog/' ];
    }
    
    remove_submenu_page( 'catalog', 'catalog' );
  }

  public function mvx_catalog_callback() {
    echo '<div id="mvx-admin-catalog"></div>';
  }

  /**
   * Enqueue scripts and styles.
   *
   * @return void
   */
  public function catalog_admin_enqueue_scripts() {
    global $Woocommerce_Catalog_Enquiry;

    if (get_current_screen()->id == 'toplevel_page_catalog') {
      wp_enqueue_style( 'mvx-catalog-style', $Woocommerce_Catalog_Enquiry->plugin_url . 'src/style/main.css' );
      wp_enqueue_script( 'mvx-catalog-script', $Woocommerce_Catalog_Enquiry->plugin_url . 'build/index.js', array( 'wp-element' ), '1.0.0', true );
      $settings_page_string = array(
            'registration_form_title'       =>  __('Registration form title', 'woocommerce-catalog-enquiry'),
            'registration_form_title_desc'  =>  __('Type the form title you want the vendor to see. eg registrazione del venditore', 'woocommerce-catalog-enquiry'),
            'registration_form_desc'        =>  __('Registration form description', 'woocommerce-catalog-enquiry'),
            'registration1'                  =>  __('Introduce your marketplace or add instructions for registration', 'woocommerce-catalog-enquiry'),
            'registration2'                  =>  __('Write questions applicable to your marketplace', 'woocommerce-catalog-enquiry'),
            'registration3'                  =>  __('Select your preferred question format. Read doc to know more about each format.', 'woocommerce-catalog-enquiry'),
            'registration4'                  =>  __('Placeholder', 'woocommerce-catalog-enquiry'),
            'registration5'                  =>  __('Tooltip description', 'woocommerce-catalog-enquiry'),
            'registration6'                  =>  __('Leave this section blank or add examples of an answer here.', 'woocommerce-catalog-enquiry'),
            'registration7'                  =>  __('Add more information or specific instructions here.', 'woocommerce-catalog-enquiry'),
            'registration8'                  =>  __('Characters Limit', 'woocommerce-catalog-enquiry'),
            'registration9'                  =>  __('Restrict vendor descriptions to a certain number of characters.', 'woocommerce-catalog-enquiry'),
            'registration11'                  =>  __('Multiple', 'woocommerce-catalog-enquiry'),
            'registration12'                  =>  __('Maximum file size', 'woocommerce-catalog-enquiry'),
            'registration13'                  =>  __('Add limitation for file size', 'woocommerce-catalog-enquiry'),
            'registration14'                  =>  __('Acceptable file types', 'woocommerce-catalog-enquiry'),
            'registration15'                  =>  __('Choose preferred file size.', 'woocommerce-catalog-enquiry'),
            'registration16'                  =>  __('reCAPTCHA Type', 'woocommerce-catalog-enquiry'),
            'registration17'                  =>  __('reCAPTCHA v3', 'woocommerce-catalog-enquiry'),
            'registration18'                  =>  __('reCAPTCHA v2', 'woocommerce-catalog-enquiry'),
            'registration19'                  =>  __('Site key', 'woocommerce-catalog-enquiry'),
            'registration20'                  =>  __('Secret key', 'woocommerce-catalog-enquiry'),
            'registration21'                  =>  __('Recaptcha Script', 'woocommerce-catalog-enquiry'),
            'registration22'                  =>  __('Write titles for your options here.', 'woocommerce-catalog-enquiry'),
            'registration23'                  =>  __('This section is available for developers who might want to mark the labels they create.', 'woocommerce-catalog-enquiry'),
            'registration24'                  =>  __('', 'woocommerce-catalog-enquiry'),
            'registration25'                  =>  __('Require', 'woocommerce-catalog-enquiry'),
            'registration26'                  =>  __('To get', 'woocommerce-catalog-enquiry'),
            'registration27'                  =>  __('reCAPTCHA', 'woocommerce-catalog-enquiry'),
            'registration28'                  =>  __('script, register your site with google account', 'woocommerce-catalog-enquiry'),
            'registration29'                  =>  __('Register', 'woocommerce-catalog-enquiry'),
            'question-format'                 => array(
                array(
                    'icon'  =>  'icon-select-question-type',
                    'value' => 'select_question_type',
                    'label' =>  __('Select question type', 'woocommerce-catalog-enquiry')
                ),
                array(
                    'icon'  =>  'icon-form-textbox',
                    'value' => 'textbox',
                    'label' =>  __('Textbox', 'woocommerce-catalog-enquiry')
                ),
                array(
                    'icon'  =>  'icon-form-email',
                    'value' => 'email',
                    'label' =>  __('Email', 'woocommerce-catalog-enquiry')
                ),
                array(
                    'icon'  =>  'icon-form-url',
                    'value' => 'url',
                    'label' =>  __('Url', 'woocommerce-catalog-enquiry')
                ),
                array(
                    'icon'  =>  'icon-form-textarea',
                    'value' => 'textarea',
                    'label' =>  __('Textarea', 'woocommerce-catalog-enquiry')
                ),
                array(
                    'icon'  =>  'icon-form-checkboxes',
                    'value' => 'checkboxes',
                    'label' =>  __('Checkboxes', 'woocommerce-catalog-enquiry')
                ),
                array(
                    'icon'  =>  'icon-form-multi-select',
                    'value' => 'multi-select',
                    'label' =>  __('Multi Select', 'woocommerce-catalog-enquiry')
                ),
                array(
                    'icon'  =>  'icon-form-radio',
                    'value' => 'radio',
                    'label' =>  __('Radio', 'woocommerce-catalog-enquiry')
                ),
                array(
                    'icon'  =>  'icon-form-dropdown',
                    'value' => 'dropdown',
                    'label' =>  __('Dropdown', 'woocommerce-catalog-enquiry')
                ),
                array(
                    'icon'  =>  'icon-form-recaptcha',
                    'value' => 'recapta',
                    'label' =>  __('Recapta', 'woocommerce-catalog-enquiry')
                ),
                array(
                    'icon'  =>  'icon-form-attachment',
                    'value' => 'attachment',
                    'label' =>  __('Attachment', 'woocommerce-catalog-enquiry')
                ),
                array(
                    'icon'  =>  'icon-form-section',
                    'value' => 'section',
                    'label' =>  __('Section', 'woocommerce-catalog-enquiry')
                ),
                array(
                    'icon'  =>  'icon-form-store-description',
                    'value' => 'datepicker',
                    'label' =>  __('Date Picker', 'woocommerce-catalog-enquiry')
                ),
                array(
                    'icon'  =>  'icon-form-address01',
                    'value' => 'timepicker',
                    'label' =>  __('Time Picker', 'woocommerce-catalog-enquiry')
                ),
            )
        );
      wp_localize_script( 'mvx-catalog-script', 'catalogappLocalizer', apply_filters('catalog_settings', [
        'apiUrl' => home_url( '/wp-json' ),
        'nonce' => wp_create_nonce( 'wp_rest' ),
        'banner_img'  => $Woocommerce_Catalog_Enquiry->plugin_url . 'assets/images/catalog-pro-add-admin-banner.jpg',
        'settings_page_string'  =>  $settings_page_string,
        'pro_active'    =>  apply_filters('mvx_catalog_free_only_active', true)
      ] ) );
    }
  }
  
}