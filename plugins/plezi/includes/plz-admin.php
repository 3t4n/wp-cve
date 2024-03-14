<?php
if ( ! function_exists( 'plz_settings_link' ) ) :
  function plz_settings_link( $links ) {
    $signup_url = 'https://enjoy.plezi.co/signup?utm_medium=referral&utm_source=wordpress&utm_campaign=plezi_one&utm_content=plugin&utm_term=wp-plugins-list';
  	$settings_url = esc_url(
                      add_query_arg(
            		        'page',
                		    'plezi-for-wordpress-configuration.php',
                		    get_admin_url() . 'admin.php'
          	          )
                    );

    $signup = '<a href="' . $signup_url . '" title="' . __( 'Signup', 'plezi-for-wordpress' ) . '" target="_blank">' . __( 'Signup', 'plezi-for-wordpress' ) . '</a>';
  	$settings = '<a href="' . $settings_url . '" title="' . __( 'Settings', 'plezi-for-wordpress' ) . '">' . __( 'Settings', 'plezi-for-wordpress' ) . '</a>';

    array_unshift( $links, $signup );
    array_unshift( $links, $settings );

  	return $links;
  }
endif;

if ( ! function_exists( 'plz_add_pages' ) ) :
  function plz_add_pages() {
    if ( current_user_can( 'manage_options' ) ) :
      add_menu_page( __( 'Plezi', 'plezi-for-wordpress' ), __( 'Plezi', 'plezi-for-wordpress' ), 'manage_options', 'plezi-for-wordpress-configuration.php', '', plugin_dir_url( __DIR__ ) . 'images/ico_logo.svg', 26 );
      add_submenu_page( 'plezi-for-wordpress-configuration.php', __( 'Setup', 'plezi-for-wordpress' ), __( 'Setup', 'plezi-for-wordpress' ), 'manage_options', 'plezi-for-wordpress-configuration.php', 'plz_admin_configuration', 1 );
      add_submenu_page( 'plezi-for-wordpress-configuration.php', __( 'Forms', 'plezi-for-wordpress' ), __( 'Forms', 'plezi-for-wordpress' ), 'manage_options', 'plezi-for-wordpress-forms.php', 'plz_admin_forms', 2 );
      add_submenu_page( 'plezi-for-wordpress-configuration.php', __( 'FAQ', 'plezi-for-wordpress' ), __( 'FAQ', 'plezi-for-wordpress' ), 'manage_options', 'plezi-for-wordpress-faq.php', 'plz_admin_faq', 3 );
    endif;
  }
endif;

if ( ! function_exists( 'plz_admin_configuration' ) ) :
  function plz_admin_configuration() {
    if ( ! current_user_can( 'manage_options' ) ) :
      wp_die( esc_attr__( 'You do not have sufficient permissions to access this page.', 'plezi-for-wordpress' ) );
    endif;

    $options = get_option( 'plz_configuration_authentification_options' );
    $check_tracker = plz_get_tracking_enable_mode();

    if ( ! isset( $options['plz_authentification_status'] ) || empty( $options['plz_authentification_status'] ) || '0' === $options['plz_authentification_status'] ) :
      $tab_authentification_status = 'status-disable';
      $plz_class_get_authentification = '';
      $plz_class_remove_authentification = 'plz-hidden';
    else :
      $tab_authentification_status = 'status-enable';
      $plz_class_get_authentification = 'plz-hidden';
      $plz_class_remove_authentification = '';
    endif;

    if ( $check_tracker ) :
      $tab_tracking_status = 'status-enable';
    else :
      $tab_tracking_status = 'status-disable';
    endif;

    echo '<div class="plz-loader"><div class="plz-lds-dual-ring"></div></div>';
    echo '<div class="wrap plezi-wrap-page">';
    echo '<h1><strong>' . esc_attr__( 'Setup', 'plezi-for-wordpress' ) . '</strong></h1>';
    echo '<div class="plezi-wrap-page-row">';
    echo '<div class="plezi-left-column">';
    echo '<ul class="plezi-tabs-buttons">';
    echo '<li class="active"><span class="status ' . esc_attr( $tab_authentification_status ) . '"></span><a href="#" title="' . esc_attr__( 'API connexion', 'plezi-for-wordpress' ) . '" data-tab="authentification">' . esc_attr__( 'API Connexion', 'plezi-for-wordpress' ) . '</a></li>';
    echo '<li><span class="status ' . esc_attr( $tab_tracking_status ) . '"></span><a href="#" title="' . esc_attr__( 'Tracking', 'plezi-for-wordpress' ) . '" data-tab="tracking">' . esc_attr__( 'Tracking', 'plezi-for-wordpress' ) . '</a></li>';
    echo '</ul>';
    echo '</div>';
    echo '<div class="plezi-right-column">';
    echo '<div id="authentification" class="plezi-tab-content">';
    echo '<form id="plezi-configuration-authentification-form" method="post" action="options.php">';

    settings_fields( 'plz_configuration_authentification_options' );
    do_settings_sections( 'plezi-for-wordpress-configuration-authentification.php' );
    submit_button( __( 'Connect', 'plezi-for-wordpress' ), 'plz-submit-button ' . $plz_class_get_authentification, 'submit', false, array( 'id' => 'plezi-get-authentification' ) );
    submit_button( __( 'Logout', 'plezi-for-wordpress' ), 'plz-submit-button plz-logout-button ' . $plz_class_remove_authentification, 'submit', false, array( 'id' => 'plezi-remove-authentification' ) );

    echo '</form>';
    echo '</div>';
    echo '<div id="tracking" class="plezi-tab-content">';
    echo '<form id="plezi-configuration-tracking-form" method="post" action="options.php">';

    settings_fields( 'plz_configuration_tracking_options' );
    do_settings_sections( 'plezi-for-wordpress-configuration-tracking.php' );

    echo '</form>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
  }
endif;

if ( ! function_exists( 'plz_admin_forms' ) ) :
  function plz_admin_forms() {
    if ( ! current_user_can( 'manage_options' ) ) :
      wp_die( esc_attr__( 'You do not have sufficient permissions to access this page.', 'plezi-for-wordpress' ) );
    endif;

    $options = get_option( 'plz_configuration_authentification_options' );

    echo '<div class="wrap plezi-wrap-page">';
    echo '<h1><strong>' . esc_attr__( 'Forms', 'plezi-for-wordpress' ) . '</strong></h1>';
    echo '<div class="plezi-wrap-page-content">';

    if ( ! isset( $options['plz_authentification_status'] ) || empty( $options['plz_authentification_status'] ) || '0' === $options['plz_authentification_status'] ) :
      $setup = admin_url( 'admin.php?page=plezi-for-wordpress-configuration.php' );

      echo '<div class="plezi-wrap-page-content-introduction">';
      echo '<p>' . esc_attr__( 'To add your Plezi forms onto your pages, you need to connect Plezi to your WordPress account.', 'plezi-for-wordpress' ) . '</p>';
      echo '<a class="plezi-btn-purple" href="' . esc_url( $setup ) . '" title="' . esc_attr__( 'Connect with API', 'plezi-for-wordpress' ) . '">';
      echo esc_attr__( 'Connect', 'plezi-for-wordpress' );
      echo '</a>';
      echo '</div>';
    else :
      $forms_list_table = new PLZ_List_Forms();

      $forms_list_table->prepare_items();

      if ( $forms_list_table && isset( $forms_list_table->items ) ) :
        echo '<div class="plezi-wrap-page-content-introduction">';
        echo '<p>' . esc_attr__( 'If you use Elementor, Gutenberg or Divi, that\'s fantastic! You’ll find a Plezi Form widget, directly in your page editor. Otherwise, you can copy your form\'s shortcode here and paste it once you get to your page\'s edition.', 'plezi-for-wordpress' ) . '</p>';
        echo '<div class="plz-forms-notice">';
        echo '<p>' . esc_attr__( 'To follow your website performance, go check out your dashboard in Plezi.', 'plezi-for-wordpress' ) . '</p>';
        echo '<a href="https://enjoy.plezi.co/dashboard?utm_medium=referral&utm_source=wordpress&utm_campaign=plezi_one&utm_content=plugin&utm_term=forms-page" title="' . esc_attr__( 'Go to dashboard', 'plezi-for-wordpress' ) . '" target="_blank" class="plezi-btn-purple">' . esc_attr__( 'Go to dashboard', 'plezi-for-wordpress' ) . '</a>';
        echo '</div>';
        echo '<form class="plz-forms-list" method="post">';

        $forms_list_table->display();

        echo '</form>';
        echo '</div>';
        echo '<div id="plz-popup-preview-wrapper">';
        echo '<div id="plz-popup-preview">';
        echo '<a class="plz-popup-close" href="#" title="' . esc_attr__( 'Close the popup', 'plezi-for-wordpress' ) . '">×</a>';
        echo '<div class="plz-popup-content">';
        echo '<div class="plz-lds-dual-ring"></div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
      else :
        echo '<div class="plezi-wrap-page-content-introduction plezi-forms-empty">';
        echo '<p>' . esc_attr__( 'If you use Elementor, Gutenberg or Divi, that\'s fantastic! You’ll find a Plezi Form widget, directly in your page editor. Otherwise, you can copy your form\'s shortcode here and paste it once you get to your page\'s edition.', 'plezi-for-wordpress' ) . '</p>';
        echo '<p class="plezi-image-center"><img src="' . esc_url( plugin_dir_url( __DIR__ ) ) . 'images/ico_forms_empty.svg" alt="' . esc_attr__( 'It seems that you\'ve never created any form in Plezi -yet ;)', 'plezi-for-wordpress' ) . '" /></p>';
        echo '<p class="plezi-strong">' . esc_attr__( 'It seems that you\'ve never created any form in Plezi -yet ;)', 'plezi-for-wordpress' ) . '</p>';
        echo '<p class="plezi-button-center"><a class="plezi-btn-purple" href="https://enjoy.plezi.co/resources/create?utm_medium=referral&utm_source=wordpress&utm_campaign=plezi_one&utm_content=plugin&utm_term=forms-page" target="_blank" title="' . esc_attr__( 'Create my first form', 'plezi-for-wordpress' ) . '">';
        echo esc_attr__( 'Create my first form', 'plezi-for-wordpress' );
        echo '</a></p>';
        echo '</div>';
      endif;
    endif;

    echo '</div>';
    echo '</div>';
  }
endif;

if ( ! function_exists( 'plz_admin_faq' ) ) :
  function plz_admin_faq() {
    if ( ! current_user_can( 'manage_options' ) ) :
      wp_die( esc_attr__( 'You do not have sufficient permissions to access this page.', 'plezi-for-wordpress' ) );
    endif;

    $setup = admin_url( 'admin.php?page=plezi-for-wordpress-configuration.php' );
    $faq = array(
            __( 'Are there any pre-requisites to use this plugin?', 'plezi-for-wordpress' ) => __( 'To use the Plezi plugin, you need:', 'plezi-for-wordpress' ) . '<br />' . esc_attr__( '- to have a Plezi One account.', 'plezi-for-wordpress' ) . ' <a href="https://enjoy.plezi.co/signup?utm_medium=referral&utm_source=wordpress&utm_campaign=plezi_one&utm_content=plugin&utm_term=faq-page" target="_blank" title="' . esc_attr__( 'Signup here', 'plezi-for-wordpress' ) . '">' . esc_attr__( 'Signup here', 'plezi-for-wordpress' ) . '</a>.<br />' . esc_attr__( '- connect your Plezi account to your Wordpress account.', 'plezi-for-wordpress' ) . ' <a href="' . $setup . '" title="' . esc_attr__( 'Connect Plezi here', 'plezi-for-wordpress' ) . '">' . esc_attr__( 'Connect Plezi here', 'plezi-for-wordpress' ) . '</a>.',
            __( 'What is this plugin for?', 'plezi-for-wordpress' ) => __( 'This plugin allows you to:', 'plezi-for-wordpress' ) . '<br />' . esc_attr__( '- have the Plezi tracking script through all your website pages', 'plezi-for-wordpress' ) . '<br />' . esc_attr__( '- publish your Plezi forms online, thanks to a tailored Plezi widget (on Gutemberg, Elementor and Divi builders) or simplified shortcodes', 'plezi-for-wordpress' ) . '<br />' . esc_attr__( '- take shortcuts to your Plezi dashboard to follow your website stats', 'plezi-for-wordpress' ),
            __( 'What is Plezi\'s tracking script for?', 'plezi-for-wordpress' ) => __( 'The tracking script allows Plezi to see visits to your website and identify prospects when they return.', 'plezi-for-wordpress' ),
            __( 'How do I install Plezi tracking script on my website with Wordpress?', 'plezi-for-wordpress' ) =>
            __( 'To install Plezi tracking script, follow the instructions', 'plezi-for-wordpress' ) . ' <a href="' . $setup . '" title="' . esc_attr__( 'on the Setup tab of this plugin', 'plezi-for-wordpress' ) . '">' . esc_attr__( 'on the Setup tab of this plugin', 'plezi-for-wordpress' ) . '</a>.',
            __( 'How can I get all my Plezi forms in WordPress?', 'plezi-for-wordpress' ) => __( 'To find your Plezi forms in WordPress, you’ll need to authenticate your Plezi API keys.', 'plezi-for-wordpress' ),
            __( 'How can I publish a Plezi form in WordPress?', 'plezi-for-wordpress' ) => __( 'If you use Elementor, Gutenberg or Divi, that\'s fantastic! You’ll find a Plezi Form widget, directly in your page editor. Otherwise, you can copy your form\'s shortcode here and paste it in your editor.', 'plezi-for-wordpress' ),
            __( 'How can I use Plezi shortcodes in WordPress?', 'plezi-for-wordpress' ) => __( 'To publish a form online, copy its shortcode and paste it in a classic text bloc.', 'plezi-for-wordpress' ),
          );

    echo '<div class="wrap plezi-wrap-page plezi-wrap-faq">';
    echo '<h1>' . esc_attr__( 'Frequently Asked', 'plezi-for-wordpress' ) . ' <strong>' . esc_attr__( 'Questions', 'plezi-for-wordpress' ) . '</strong></h1>';
    echo '<ul>';

    foreach ( $faq as $question => $response ) :
      echo '<li>';
      echo '<div class="plezi-question"><div>' . esc_html( $question ) . '</div></div>';
      echo '<div class="plezi-response"><div>' . wp_kses( $response, 'post' ) . '</div></div>';
      echo '</li>';
    endforeach;

    echo '</ul>';
    echo '</div>';
  }
endif;

if ( ! function_exists( 'plz_page_configuration_init' ) ) :
  function plz_page_configuration_init() {
    register_setting(
      'plz_configuration_tracking_options',
      'plz_configuration_tracking_options',
      'plz_configuration_tracking_options_validate'
    );

    register_setting(
      'plz_configuration_authentification_options',
      'plz_configuration_authentification_options',
      'plz_configuration_authentification_options_validate'
    );

    add_settings_section(
      'plz-configuration-section-tracking',
      __( 'Tracking', 'plezi-for-wordpress' ),
      'plz_page_configuration_section_tracking',
      'plezi-for-wordpress-configuration-tracking.php'
    );

    add_settings_section(
      'plz-configuration-section-authentification',
      __( 'API connexion', 'plezi-for-wordpress' ),
      'plz_page_configuration_section_authentification',
      'plezi-for-wordpress-configuration-authentification.php'
    );

    add_settings_field(
      'plz_configuration_tracking_choice',
      '',
      'plz_configuration_tracking_choice_field',
      'plezi-for-wordpress-configuration-tracking.php',
      'plz-configuration-section-tracking'
    );

    add_settings_field(
      'plz_configuration_tracking_code',
      '',
      'plz_configuration_tracking_code_field',
      'plezi-for-wordpress-configuration-tracking.php',
      'plz-configuration-section-tracking'
    );

    add_settings_field(
      'plz_configuration_authentification_public_key',
      '',
      'plz_configuration_authentification_public_key_field',
      'plezi-for-wordpress-configuration-authentification.php',
      'plz-configuration-section-authentification'
    );

    add_settings_field(
      'plz_configuration_authentification_secret_key',
      '',
      'plz_configuration_authentification_secret_key_field',
      'plezi-for-wordpress-configuration-authentification.php',
      'plz-configuration-section-authentification'
    );
  }
endif;

if ( ! function_exists( 'plz_page_configuration_section_tracking' ) ) :
  function plz_page_configuration_section_tracking() {
    echo '<p>' . esc_attr__( 'Install Plezi\'s tracking script to see visits on your website. Get your script by 1) clicking on the pink button below if you\'ve connected Plezi to Wordpress or 2) by pasting it ', 'plezi-for-wordpress' ) . ' <a href="https://enjoy.plezi.co/settings/configuration?utm_medium=referral&utm_source=wordpress&utm_campaign=plezi_one&utm_content=plugin&utm_term=settings-page" title="' . esc_attr__( 'from here', 'plezi-for-wordpress' ) . '" target="_blank">' . esc_attr__( 'from here', 'plezi-for-wordpress' ) . '</a> ' . esc_attr__( ', activate it... et voilà!', 'plezi-for-wordpress' ) . '</p>';
    echo '<p>' . esc_attr__( 'If you\'d rather choose another method to install the script (e.g. via Google Tag Manager), that\'s alright!', 'plezi-for-wordpress' ) . ' <strong>' . esc_attr__( 'Don\'t do it twice', 'plezi-for-wordpress' ) . '</strong> ' . esc_attr__( 'unless you want to see your visits counted twice ;)', 'plezi-for-wordpress' );
  }
endif;

if ( ! function_exists( 'plz_page_configuration_section_authentification' ) ) :
  function plz_page_configuration_section_authentification() {
    echo '<p>' . esc_attr__( 'To fully enjoy this plugin -like adding your Plezi forms into your pages, you need to connect Plezi to your WordPress acount. You\'ll find your API credentials here ', 'plezi-for-wordpress' ) . ' <a href="https://enjoy.plezi.co/settings/configuration?utm_medium=referral&utm_source=wordpress&utm_campaign=plezi_one&utm_content=plugin&utm_term=settings-page" title="' . esc_attr__( 'in your Plezi settings', 'plezi-for-wordpress' ) . '" target="_blank">' . esc_attr__( 'in your Plezi settings', 'plezi-for-wordpress' ) . '</a>.</p>';
  }
endif;

if ( ! function_exists( 'plz_configuration_tracking_choice_field' ) ) :
  function plz_configuration_tracking_choice_field() {
    $choice = plz_check_tracking_choice();

    echo '<input type="radio" name="plz_configuration_tracking_options[plz_tracking_choice]" id="api"  value="api" ' . esc_attr( $choice['api'] ) . ' class="plezi-radio" />';
    echo '<label for="api" class="plezi-radio-label">' . esc_attr__( 'Install with this plugin (faster)', 'plezi-for-wordpress' ) . '</label>';
    echo '<input type="radio" name="plz_configuration_tracking_options[plz_tracking_choice]" id="manual"  value="manual" ' . esc_attr( $choice['manual'] ) . ' class="plezi-radio" />';
    echo '<label for="manual" class="plezi-radio-label">' . esc_attr__( 'Install script another way', 'plezi-for-wordpress' ) . '</label>';
  }
endif;

if ( ! function_exists( 'plz_configuration_tracking_code_field' ) ) :
  function plz_configuration_tracking_code_field() {
    $options = get_option( 'plz_configuration_tracking_options' );
    $check_tracker = plz_get_tracking_enable_mode_for_header();
    $tracking_code = plz_get_tracking_code();
    $tracking_date = plz_get_tracking_date();

    if ( ! $check_tracker ) :
      $plz_class_tracking_api_validation = 'plz-hidden';
      $plz_class_tracking_api_label_active = 'plz-hidden';
      $plz_class_tracking_api_label_inactive = '';
      $plz_class_tracking_api_date = 'plz-hidden';
      $tracking_status = '';
    else :
      $plz_class_tracking_api_validation = '';
      $plz_class_tracking_api_label_active = '';
      $plz_class_tracking_api_label_inactive = 'plz-hidden';
      $plz_class_tracking_api_date = 'plz-hidden';
      $tracking_status = 'checked';

      if ( $tracking_date ) :
        $plz_class_tracking_api_date = '';
      endif;
    endif;

    if ( empty( $tracking_code ) ) :
      $plz_class_tracking_api_btn_remove = 'plz-hidden';
      $plz_class_tracking_api_btn_get = '';
      $plz_tracking_button_status = 'disabled';
      $plz_tracking_textarea_status = '';
    else :
      $plz_class_tracking_api_btn_remove = '';
      $plz_class_tracking_api_btn_get = 'plz-hidden';
      $plz_tracking_button_status = '';
      $plz_tracking_textarea_status = 'disabled="disabled"';
    endif;

    if ( isset( $options['plz_configuration_tracking_enable_manual'] ) && ! empty( $options['plz_configuration_tracking_enable_manual'] ) && 'checked' === $options['plz_configuration_tracking_enable_manual'] ) :
      $plz_class_tracking_button_manual_status = 'disabled';
      $plz_class_tracking_date_manual = '';
      $plz_configuration_tracking_button_manual_status = 'disabled="disabled"';
      $plz_configuration_tracking_enable_manual_option = 'checked';
      $plz_configuration_tracking_date_manual_option = $options['plz_configuration_tracking_manual_date'];
    else :
      $plz_class_tracking_button_manual_status = '';
      $plz_class_tracking_date_manual = 'plz-hidden';
      $plz_configuration_tracking_button_manual_status = '';
      $plz_configuration_tracking_enable_manual_option = '';
      $plz_configuration_tracking_date_manual_option = '';
    endif;

    echo '<div class="plezi-tracking-api-wrapper">';
    echo '<div class="plezi-tracking-api-row">';
    echo '<div class="plezi-tracking-left">';
    echo '<h5>' . esc_attr__( 'Tracking script', 'plezi-for-wordpress' ) . '</h5>';
    echo '<textarea id="plz_configuration_tracking_code" name="plz_configuration_tracking_options[plz_configuration_tracking_code]" ' . esc_attr( $plz_tracking_textarea_status ) . '>' . esc_attr( $tracking_code ) . '</textarea>';
    echo '<p class="plz-date-validation ' . esc_attr( $plz_class_tracking_api_date ) . '">' . esc_attr__( 'Script added on ', 'plezi-for-wordpress' ) . '<span>' . esc_attr( $tracking_date ) . '</span></p>';
    echo '</div>';
    echo '<div class="plezi-tracking-right">';
    echo '<button type="button" name="plezi-remove-tracking" id="plezi-remove-tracking" class="plezi-btn-purple plezi-btn-purple-active ' . esc_attr( $plz_class_tracking_api_btn_remove ) . '">' . esc_attr__( 'Remove tracking', 'plezi-for-wordpress' ) . '</button>';
    echo '<button type="button" name="plezi-get-tracking" id="plezi-get-tracking" class="plezi-btn-purple ' . esc_attr( $plz_class_tracking_api_btn_get ) . '">' . esc_attr__( 'Get my tracking script', 'plezi-for-wordpress' ) . '</button>';
    echo '<div class="plz-switch-wrapper">';
    echo '<label class="plz-switch">';
    echo '<input type="checkbox" id="plz_configuration_tracking_enable" name="plz_configuration_tracking_options[plz_configuration_tracking_enable]" value="checked" ' . esc_attr( $plz_tracking_button_status ) . ' ' . esc_attr( $tracking_status ) . ' />';
    echo '<span class="plz-slider plz-round"></span>';
    echo '</label>';
    echo '<span id="plz-label-active" class="plz-switch-label-active ' . esc_attr( $plz_class_tracking_api_label_active) . '">' . esc_attr__( 'Active', 'plezi-for-wordpress' ) . '</span>';
    echo '<span id="plz-label-inactive" class="plz-switch-label ' . esc_attr( $plz_class_tracking_api_label_inactive) . '">' . esc_attr__( 'Inactive', 'plezi-for-wordpress' ) . '</span>';
    echo '</div>';
    echo '</div>';
    echo '<div class="plz-tracking-confirmation-sentence ' . esc_attr( $plz_class_tracking_api_validation ) . '">';
    echo '<img src="' . esc_url( plugin_dir_url( __DIR__ ) ) . 'images/ico_valid_tracking.svg" alt="' . esc_attr__( 'The tracking script is integrated on your website! Visits will be tracked from now on in our application.', 'plezi-for-wordpress' ) . '" />';
    echo '<p>' . esc_attr__( 'The tracking script is integrated to your website! Visits will be tracked from now on.', 'plezi-for-wordpress' ) . '</p>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '<div class="plezi-tracking-manual-wrapper">';
    echo '<div class="plezi-tracking-manual-row">';
    echo '<h5>' . esc_attr__( 'Note to myself', 'plezi-for-wordpress' ) . '</h5>';
    echo '<div class="plezi-checkbox-wrapper">';
    echo '<p>';
    echo '<input type="checkbox" id="plz_configuration_tracking_enable_manual" class="plezi-checkbox" name="plz_configuration_tracking_options[plz_configuration_tracking_enable_manual]" value="checked" ' . esc_attr( $plz_configuration_tracking_enable_manual_option ) . ' />';
    echo '<label for="plz_configuration_tracking_enable_manual">' . esc_attr__( 'I installed the tracking script with Google Tag Manager or manually', 'plezi-for-wordpress' ) . '</label>';
    echo '</p>';
    echo '</div>';
    echo '<button type="button" name="plezi-set-tracking-manual" id="plezi-set-tracking-manual" class="plezi-btn-purple plezi-btn-purple-active ' . esc_attr( $plz_class_tracking_button_manual_status ) . '" ' . esc_attr( $plz_configuration_tracking_button_manual_status ) . '>' . esc_attr__( 'Save', 'plezi-for-wordpress' ) . '</button>';
    echo '<div class="plz-manual-date-validation-wrapper ' . esc_attr( $plz_class_tracking_date_manual ) . '">';
    echo '<hr />';
    echo '<p class="plz-date-validation"><span>' . esc_attr( $plz_configuration_tracking_date_manual_option ) . '</span> : ' . esc_attr__( 'Tracking script Installed via another method', 'plezi-for-wordpress' ) . '</p>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
  }
endif;

if ( ! function_exists( 'plz_configuration_authentification_public_key_field' ) ) :
  function plz_configuration_authentification_public_key_field() {
    $options = get_option( 'plz_configuration_authentification_options' );
    $authentification_status_class = '';

    if ( ! isset( $options['plz_authentification_public_key'] ) ) :
      $options['plz_authentification_public_key'] = '';
    endif;

    if ( ! isset( $options['plz_authentification_status'] ) || empty( $options['plz_authentification_status'] ) || '0' === $options['plz_authentification_status'] ) :
      $authentification_status_class = 'plz-validation-authentification-hidden';
    endif;

    echo '<label for="plz_authentification_public_key">' . esc_attr__( 'API key', 'plezi-for-wordpress' ) . '</label>';
    echo '<input type="text" id="plz_authentification_public_key" name="plz_configuration_authentification_options[plz_authentification_public_key]" value="' . esc_attr( $options['plz_authentification_public_key'] ) . '" />';
    echo '<img class="plz-validation-authentification ' . esc_attr( $authentification_status_class ) . '" src="' . esc_url( plugin_dir_url( __DIR__ ) ) . 'images/ico_valid_option.svg" alt="' . esc_attr__( 'API key', 'plezi-for-wordpress' ) . '" />';
  }
endif;

if ( ! function_exists( 'plz_configuration_authentification_secret_key_field' ) ) :
  function plz_configuration_authentification_secret_key_field() {
    $options = get_option( 'plz_configuration_authentification_options' );
    $authentification_status_class = '';

    if ( ! isset( $options['plz_authentification_secret_key'] ) ) :
      $options['plz_authentification_secret_key'] = '';
    endif;

    if ( ! isset( $options['plz_authentification_status'] ) || empty( $options['plz_authentification_status'] ) || '0' === $options['plz_authentification_status'] ) :
      $authentification_status_class = 'plz-validation-authentification-hidden';
    endif;

    echo '<label for="plz_authentification_secret_key">' . esc_attr__( 'Secret key', 'plezi-for-wordpress' ) . '</label>';
    echo '<input type="password" id="plz_authentification_secret_key" name="plz_configuration_authentification_options[plz_authentification_secret_key]" value="' . esc_attr( $options['plz_authentification_secret_key'] ) . '" />';
    echo '<img class="plz-validation-authentification ' . esc_attr( $authentification_status_class ) . '" src="' . esc_url( plugin_dir_url( __DIR__ ) ) . 'images/ico_valid_option.svg" alt="' . esc_attr__( 'Secret key', 'plezi-for-wordpress' ) . '" />';
    echo '<p class="plz-error-authentification">';
    echo esc_attr__( 'Oh no! These keys don\'t exist. Please try again.', 'plezi-for-wordpress' );
    echo '</p>';
  }
endif;

if ( ! function_exists( 'plz_configuration_tracking_options_validate' ) ) :
  function plz_configuration_tracking_options_validate( $inputs ) {
    $outputs = array();

    foreach ( $inputs as $key => $value ) :
      $outputs[ $key ] = $value;
    endforeach;

    if ( isset( $inputs['plz_configuration_tracking_code'] ) && ! empty( $inputs['plz_configuration_tracking_code'] ) ) :
      $outputs['plz_configuration_tracking_date'] = gmdate( 'd/m/Y' );
      $outputs['plz_configuration_tracking_enable'] = 'checked';
    else :
      $outputs['plz_configuration_tracking_enable'] = '';
    endif;

    return apply_filters( 'plz_configuration_tracking_options_validate', $outputs, $inputs );
  }
endif;

if ( ! function_exists( 'plz_configuration_authentification_options_validate' ) ) :
  function plz_configuration_authentification_options_validate( $inputs ) {
    $outputs = array();

    foreach ( $inputs as $key => $value ) :
      $outputs[ $key ] = $value;
    endforeach;

    if ( isset( $inputs['plz_authentification_public_key'] ) && ! empty( $inputs['plz_authentification_public_key'] ) && isset( $inputs['plz_authentification_secret_key'] ) && ! empty( $inputs['plz_authentification_secret_key'] ) ) :
      $outputs['plz_authentification_status'] = '1';
    else :
      $outputs['plz_authentification_status'] = '0';
    endif;

    return apply_filters( 'plz_configuration_authentification_options_validate', $outputs, $inputs );
  }
endif;

if ( ! function_exists( 'plz_admin_include_scripts' ) ) :
  function plz_admin_include_scripts() {
	global $pagenow;

    if ( is_admin() && current_user_can( 'manage_options' ) && plz_check_screen( ) ) :
      wp_enqueue_style( 'plz-admin-fonts', plugin_dir_url( __DIR__ ) . 'css/fonts.css', array(), PLZ_VERSION, 'all' );
      wp_enqueue_style( 'plz-admin-style', plugin_dir_url( __DIR__ ) . 'css/plz-admin.css', array(), PLZ_VERSION, 'all' );
 	  wp_enqueue_script( 'plz-admin-script', plugin_dir_url( __DIR__ ) . 'js/plz-admin.js', array( 'jquery' ), PLZ_VERSION, true);
      wp_localize_script( 'plz-admin-script', 'plzlabels', array( 'plztrackingremovescript'      => __( 'Are you sure you want to delete the script? You can disable it with the toggle below.', 'plezi-for-wordpress' ) ) );
      wp_localize_script( 'plz-admin-script', 'plzapi', array( 'plzsetauthentification'          => '/wp-json/plz/v2/configuration/set-authentification',
                                                               'plzremoveauthentification'       => '/wp-json/plz/v2/configuration/remove-authentification',
                                                               'plzsettrackingmanualstatus'      => '/wp-json/plz/v2/configuration/set-tracking-manual-status',
                                                               'plzgettrackingapi'               => '/wp-json/plz/v2/configuration/get-tracking-api',
                                                               'plzsettrackingapi'               => '/wp-json/plz/v2/configuration/set-tracking-api',
                                                               'plzremovetrackingapi'            => '/wp-json/plz/v2/configuration/remove-tracking-api',
                                                               'plzsettrackingapistatus'         => '/wp-json/plz/v2/configuration/set-tracking-api-status',
                                                               'plznonceapi'                     => wp_create_nonce( 'wp_rest' ),
                                                             ) );
    elseif ( is_admin() && plz_check_dashboard_screen() ) :
      wp_enqueue_style( 'plz-admin-fonts', plugin_dir_url( __DIR__ ) . 'css/fonts.css', array(), PLZ_VERSION, 'all' );
      wp_enqueue_style( 'plz-admin-widget-style', plugin_dir_url( __DIR__ ) . 'css/plz-admin-widget.css', array(), PLZ_VERSION, 'all' );
	elseif ( is_admin() && $pagenow == 'post.php' && isset($_GET['vc_action']) ) :
		wp_enqueue_style( 'plz-admin-wpbakery-style', plugin_dir_url( __DIR__ ) . 'builders/wpbakery-element/styles/style.css', array(), PLZ_VERSION, 'all' );
	endif;
  }
endif;

if ( ! function_exists( 'plz_footer' ) ) :
  function plz_footer() {
    if ( plz_check_screen( ) ) :
      return '<span class="plz-footer">' . esc_html__( 'Powered with 💗︎ by ', 'plezi-for-wordpress' ) . '</span><img src="' . esc_url( plugin_dir_url( __DIR__ ) ) . 'images/footer_logo.svg" alt="' . esc_attr__( 'Plezi', 'plezi-for-wordpress' ) . '" class="plz-footer-image" />';
    endif;
  }
endif;

if ( ! function_exists( 'plz_check_screen' ) ) :
  function plz_check_screen() {
    $screen   = get_current_screen();
    $screens  = array( 'toplevel_page_plezi-for-wordpress-configuration', 'plezi_page_plezi-for-wordpress-forms', 'plezi_page_plezi-for-wordpress-faq' );

    if ( in_array($screen->id, $screens ) ) :
      return true;
    endif;

    return false;
  }
endif;

if ( ! function_exists( 'plz_check_dashboard_screen' ) ) :
  function plz_check_dashboard_screen() {
    $screen   = get_current_screen();

    if ( 'dashboard' === $screen->id ) :
      return true;
    endif;

    return false;
  }
endif;

if ( ! function_exists( 'plz_load_textdomain' ) ) :
  function plz_load_textdomain( $mofile, $domain ) {
    if ( 'plezi-for-wordpress' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) :
      $locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
      $mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __DIR__ ) ) . '/languages/' . $domain . '-' . $locale . '.mo';
    endif;

    return $mofile;
  }
endif;

if ( ! function_exists( 'plz_load_plugin' ) ) :
	function plz_load_plugin() {
    	load_plugin_textdomain( 'plezi-for-wordpress', false, plugin_basename( __DIR__ ) . '/languages' );

		$permalink_structure = get_option( 'permalink_structure' );

	    if ( empty( $permalink_structure ) ) :
			add_action( 'admin_notices', 'plz_show_permalink_error' );
		endif;
	}
endif;

if ( ! function_exists( 'plz_show_permalink_error' ) ) :
	function plz_show_permalink_error() {
    	echo '<div class="notice notice-error is-dismissible">';
        echo '<p>';
		echo esc_attr__( 'Please, edit your ', 'plezi-for-wordpress' );
		echo '<a href="' . esc_url( admin_url( 'options-permalink.php' ) ) . '" title="' . esc_attr__( 'permalinks structure', 'plezi-for-wordpress' ) . '">';
		echo esc_attr__( 'permalinks structure', 'plezi-for-wordpress' );
		echo '</a>';
		echo esc_attr__( ' to use Plezi plugin.', 'plezi-for-wordpress' );
		echo '</p>';
    	echo '</div>';
	}
endif;

if ( ! function_exists( 'plz_add_dashboard_widget' ) ) :
  function plz_add_dashboard_widget() {
    global $wp_meta_boxes;

    $plz_widget_id = 'plezi_dashboard_widget';

    wp_add_dashboard_widget($plz_widget_id, __( 'Plezi’s dashboard', 'plezi-for-wordpress' ), 'plz_dashboard_widget_content' );

    $normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
    $widget_instance = [ $plz_widget_id => $normal_dashboard[ $plz_widget_id ] ];

    unset( $normal_dashboard[ $plz_widget_id ] );

    $sorted_dashboard = array_merge( $widget_instance, $normal_dashboard );
    $wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
  }
endif;

if ( ! function_exists( 'plz_dashboard_widget_content' ) ) :
  function plz_dashboard_widget_content( $post, $callback_args ) {
    echo '<div>';
    echo '<div class="plz-widget-dashboard">';
    echo '<div class="plz-widget-dashboard-container">';
    echo '<div class="plz-widget-dashboard-content">';
    echo '<h3>' . esc_attr__( 'Follow your website performance on Plezi', 'plezi-for-wordpress' ) . '</h3>';
    echo '<ul class="introduction">';
    echo '<li><span class="plz-dashboard-widget-validation">✔</span> ' . esc_attr__( 'Visits, conversion rate, leads', 'plezi-for-wordpress' );
    echo '<li><span class="plz-dashboard-widget-validation">✔</span> ' . esc_attr__( 'Top digital channels', 'plezi-for-wordpress' );
    echo '<li><span class="plz-dashboard-widget-validation">✔</span> ' . esc_attr__( 'Top keywords and SEO positions', 'plezi-for-wordpress' );
    echo '</ul>';
    echo '<p class="plezi-button-center">';
    echo '<a class="plezi-btn-purple" href="https://enjoy.plezi.co/dashboard?utm_medium=referral&utm_source=wordpress&utm_campaign=plezi_one&utm_content=plugin&utm_term=widget-dashboard" target="_blank" title="' . esc_attr__( 'Go to dashboard', 'plezi-for-wordpress' ) . '">';
    echo esc_attr__( 'Go to dashboard', 'plezi-for-wordpress' );
    echo '</a>';
    echo '</p>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
	}
endif;

if ( ! function_exists( 'plz_form_shortcode' ) ) :
  function plz_form_shortcode( $atts ) {
	extract( shortcode_atts( array( 'form' => null ), $atts ) );

    if ( $form && ! empty( $form ) ) :
		return '<form id="plz-form-' . $form . '"></form><script async src="https://brain.plezi.co/api/v1/web_forms/scripts?content_web_form_id=' . $form . '"></script>';
    endif;

    return '';
  }
endif;

if ( ! function_exists( 'plz_register_widget' ) ) :
  function plz_register_widget( $widgets_manager ) {
  	include_once( plugin_dir_path( __DIR__ ) . '/builders/elementor-widget/class-elementor-plezi-form-widget.php' );

	$widgets_manager->register(new \Elementor_Plezi_Form_Widget() );
  }
endif;

if ( ! function_exists( 'plz_add_elementor_widget_category' ) ) :
  function plz_add_elementor_widget_category( $elements_manager ) {
  	$elements_manager->add_category(
  		'plezi',
  		[
  			'title' => __( 'Plezi', 'plezi-for-wordpress' ),
  			'icon' => 'fa fa-plug',
  		]
  	);
  }
endif;

if ( ! function_exists( 'plz_get_user_cookies' ) ) :
  function plz_get_user_cookies() {
    $cookies = array();

    foreach ( $_COOKIE as $name => $value ) :
        $cookies[] = new WP_Http_Cookie( array( 'name' => $name, 'value' => $value ) );
    endforeach;

    return $cookies;
  }
endif;
