<?php

  /**
   * Plugin Page Links
   */
  add_filter( 'plugin_action_links_'.DEVIGN_COVID_19_BASENAME, 'devign_covid_nineteen_settings_link');
  function devign_covid_nineteen_settings_link( $links ) {
    
    $links[] = '<a href="' .
      admin_url( 'admin.php?page=devign-covid-nineteen' ) .
      '">' . __('Settings') . '</a>';

    return $links;
  }

  /**
   * WPCF7 Submissions - Activation Admin Notice
   */
  function devign_covid_nineteen_activation_message() {
    
    if( get_transient( 'devign_covid_nineteen_activator' ) ) {

      echo "<div class=\"notice notice-info is-dismissible\"> ";
        echo "<p><strong>Thank you</strong> for installing COVID-19 Alerts, you must first setup the information you wish provide your users with. Get started in the <a href=\"" . admin_url( 'admin.php?page=devign-covid-nineteen' ) . "\">settings</a> page.</p>";
      echo "</div>";

    }
    delete_transient( 'devign_covid_nineteen_activator' );

    /**
     * Save Settings
     */
    if ( isset ( $_POST['devign_covid_19_nonce'] ) ) {
      if ( wp_verify_nonce( $_POST['devign_covid_19_nonce'], 'devign_covid_19_nonce' ) ) {
        echo "<div class=\"notice notice-success is-dismissible\" style=\"margin-left: 0; !important;\">";
          echo "<p><strong>Settings Saved.</strong></p>";
        echo "</div>";
      }
    }

  }
  add_action( 'admin_notices', 'devign_covid_nineteen_activation_message' );

  /**
   * Register Admin Menus
   */
  function devign_covid_nineteen_admin_menus(){
    add_menu_page( 
      __( 'COVID-19 Update', 'devign-covid-nineteen' ),
      'COVID-19',
      'manage_options',
      'devign-covid-nineteen',
      'devign_covid_nineteen_callback',
      DEVIGN_COVID_19_PLUGIN_PATH.'assets/img/Menu-Icon.svg',
      95
    ); 
  }
  add_action( 'admin_menu', 'devign_covid_nineteen_admin_menus' );

  /**
   * Callback for Admin Menu Item
   */
  function devign_covid_nineteen_callback() {

    require_once DEVIGN_COVID_19_PLUGIN_DIR.'/views/admin_covid_nineteen.php';

  }

  /**
   * Add Footer Content
   */
  add_action( 'wp_footer', 'devign_covid_nineteen_badge' );
  function devign_covid_nineteen_badge() {

    $html = '';

    if ( !get_option('devign_covid_ninteen_show_updates') ) {
      return $html;
    }

    if ( get_option('devign_covid_ninteen_show_updates') === 'yes' ) {

      $class = array();
      $container_style = array();
      $button_style = array();
      $span_style = array();
      $covid_svg = array();
      $backlink_style = array();
      $content_style = array();

      if ( $option = get_option('devign_covid_ninteen_badge_location') ) {
        $class[] = $option;
      }
      if ( $option = get_option('devign_covid_ninteen_theme_color') ) {
        $container_style[] = 'border-color:'. $option .';';
        $button_style[] = 'background:'. $option .';';
        $backlink_style[] = 'color:'. $option .';';
      }
      if ( $option = get_option('devign_covid_ninteen_text_color') ) {
        $button_style[] = 'color:'. $option .';';
        $span_style[] = 'color:'. $option .';';
        $covid_svg[] = 'fill:'. $option .';';
      } else {
          $covid_svg[] = 'fill: #ffffff;';
        }

      if ( $option = get_option('devign_covid_ninteen_background_color') ) {
        $content_style[] = 'background-color:'. $option .';';
      }

      if ( $option = get_option('devign_covid_ninteen_content_text_color') ) {
        $content_style[] = 'color:'. $option .';';
      }

      if ( get_option('devign_covid_ninteen_button_text_mobile') === 'yes' ) {
        $class[] = 'mobile-text';
      }
    
      $html .= '<div class="covid-wrapper '.implode( ' ', $class ).'" >';
        $html .= '<div class="covid-container" style="'.implode( ' ', $container_style ).'" >';
          $html .= '<div class="covid-button" style="'.implode( ' ', $button_style ).'" >';
            $html .= '<a href="#">';
              
              if ( get_option('devign_covid_ninteen_show_button_icon') === 'yes' ) {
                $svg_display = '';
              } else {
                  $svg_display = 'hide-desktop';
                }

                $html .= '<svg version="1.1" id="covid-virus-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                viewBox="0 0 282.7 267.1" style="enable-background:new 0 0 282.7 267.1;" xml:space="preserve" class="'.$svg_display.'">';
                  $html .= '<style type="text/css">';
                  
                  $html .= '.covid-svg{'.implode( ' ', $covid_svg ).'}';

                  $html .= '</style>';
                  $html .= '<circle class="covid-svg" cx="149.8" cy="139" r="66"/>';
                  $html .= '<circle class="covid-svg" cx="213.5" cy="20.5" r="20.5"/>';
                  $html .= '<circle class="covid-svg" cx="218.9" cy="246.6" r="20.5"/>';
                  $html .= '<circle class="covid-svg" cx="20.5" cy="123.5" r="20.5"/>';
                  $html .= '<circle class="covid-svg" cx="94.7" cy="41" r="11"/>';
                  $html .= '<circle class="covid-svg" cx="272.4" cy="142.2" r="10.3"/>';
                  $html .= '<circle class="covid-svg" cx="62.3" cy="226.1" r="10.3"/>';
                  $html .= '<polygon class="covid-svg" points="149.8,145.3 92,42.5 97.3,39.6 149.7,132.8 210.8,19.1 216.1,21.9 "/>';
                  $html .= '<polygon class="covid-svg" points="216.4,248.2 144.2,135.9 272.5,139.2 272.3,145.2 155.4,142.2 221.4,245 "/>';
                  $html .= '<polygon class="covid-svg" points="64.4,228.2 60.1,224 143.3,141.3 20.2,126.5 20.9,120.6 156.3,136.8 "/>';
                $html .= '</svg>';

              if ( get_option('devign_covid_ninteen_button_text') !== '' ) {
                $html .= '<span style="'.implode( ' ', $span_style ).'">'.get_option('devign_covid_ninteen_button_text').'</span>';
              } else {
                  $html .= '<span style="'.implode( ' ', $span_style ).'">COVID-19 Update</span>';
                }

            $html .= '</a>';
          $html .= '</div>';
          $html .= '<div class="covid-content" style="'.implode( ' ', $content_style ).'">';
            $html .= '<div>';
              if ( get_option('devign_covid_ninteen_company_heading') !== '' ) {
                $html .= '<p style="font-size: 1.25em !important; margin-bottom: .5rem !important;">'.get_option('devign_covid_ninteen_company_heading').'</p>';
              }
              if ( get_option('devign_covid_ninteen_company_update') !== '' ) {
                $html .= wpautop( get_option('devign_covid_ninteen_company_update') );
              }
            $html .= '</div>';

            if ( get_option('devign_covid_ninteen_local_authority_show') === 'yes'
              && get_option('devign_covid_ninteen_local_authority_link') !== '' ) {

              $link = get_option('devign_covid_ninteen_local_authority_link');
              if ( get_option('devign_covid_ninteen_local_authority_text') ) {
                $text = get_option('devign_covid_ninteen_local_authority_text');
              } else {
                  $text = 'More COVID-19 Advice';
                }

              $html .= '<a class="covid-auth" href="'.$link.'" aria-label="'.$text.'" target="_blank" rel="noreferrer" style="'.implode( ' ', $button_style ).'" >'.$text.'</a>';


              if ( get_option('devign_covid_ninteen_show_backlink') === 'yes' ) {

                $html .= '<div class="backlink"><a href="https://www.devignstudios.co.uk/resource/covid-19-update-wordpress-plugin" target="_blank" rel="noreferrer" style="'.implode( ' ', $backlink_style ).'">Powered by COVID-19 Updates</a></div>';

              }

            }
          $html .= '</div>';
        $html .= '</div>';
      $html .= '</div>';

      if ( get_option('devign_covid_ninteen_google_search_announcement') === 'yes' ) {
 
        // Schema Markup
        $Structured_Data = array(
          "@context" => "https://schema.org",
          "@type" => "SpecialAnnouncement",
          // "datePosted" => "2020-03-17T08:00",
          // "expires" => "2020-03-24T23:59",
          "category" => "https://www.wikidata.org/wiki/Q81068910",
        );

        $SpatialCoverage = array();

        if ( get_option('devign_covid_ninteen_spatial_coverage') ) {
          $SpatialCoverage[] = array(
            'type' => 'AdministrativeArea',
            'name' => get_option('devign_covid_ninteen_spatial_coverage')
          );
          $Structured_Data['spatialCoverage'] = $SpatialCoverage;
        }

        if ( get_option('devign_covid_ninteen_company_heading') !== '' ) {
          $Structured_Data['name'] = get_option('devign_covid_ninteen_company_heading');
        }

        if ( get_option('devign_covid_ninteen_company_update') !== '' ) {
          $Structured_Data['text'] = get_option('devign_covid_ninteen_company_update');
        }

        if ( get_option('devign_covid_ninteen_last_updated') !== '' ) {
          $Structured_Data['datePosted'] = date( 'c', get_option('devign_covid_ninteen_last_updated') );
          $expiresTime = strtotime( '+7 days', get_option('devign_covid_ninteen_last_updated') );
          $Structured_Data['expires'] = date( 'c', $expiresTime );
        }

        $html .= '<script type="application/ld+json">';
          $html .= json_encode( $Structured_Data ); 
        $html .= '</script>';

      }



    }

    echo $html;

  }



