<?php

  function devign_covid_ninteen_update_settings() {

    /**
     * Verify Nonce
     */
    if ( !isset( $_POST['devign_covid_19_nonce'] ) ) {
      return;
    }

    if ( !wp_verify_nonce( $_POST['devign_covid_19_nonce'], 'devign_covid_19_nonce' ) ) {
      return;
    }

    /**
     * Update Option | devign_covid_ninteen_show_updates
     */
    if ( isset( $_POST['devign_covid_ninteen_show_updates'] ) ) {
      $option = sanitize_text_field( $_POST['devign_covid_ninteen_show_updates'] );
      update_option( 'devign_covid_ninteen_show_updates', $option );
    }
      else {
        $option = 'no';
        update_option( 'devign_covid_ninteen_show_updates', $option );
      }

    /**
     * Update Option | devign_covid_ninteen_badge_location
     */
    if ( isset( $_POST['devign_covid_ninteen_badge_location'] ) ) {
      $option = sanitize_text_field( $_POST['devign_covid_ninteen_badge_location'] );
      update_option( 'devign_covid_ninteen_badge_location', $option );
    } else {
        $option = 'right';
        update_option( 'devign_covid_ninteen_badge_location', $option );
      }

    /**
     * Update Option | devign_covid_ninteen_company_update
     */
    if ( isset( $_POST['devign_covid_ninteen_company_update'] ) ) {

      $allowedTags = '<p><strong><em><u>';
      $allowedTags .= '<li><ol><ul><span><div><br><ins><del>';
      $receivedData = $_POST['devign_covid_ninteen_company_update'];
      $option = strip_tags( stripslashes( $receivedData ), $allowedTags );

      update_option( 'devign_covid_ninteen_company_update', $option );
    }

    /**
     * Update Option | devign_covid_ninteen_local_authority_show
     */
    if ( isset( $_POST['devign_covid_ninteen_local_authority_show'] ) ) {
      $option = sanitize_text_field( $_POST['devign_covid_ninteen_local_authority_show'] );
      update_option( 'devign_covid_ninteen_local_authority_show', $option );
    } else {
        $option = 'no';
        update_option( 'devign_covid_ninteen_local_authority_show', $option );
      }

    /**
     * Update Option | devign_covid_ninteen_announcement_start_date
     */
    if ( isset( $_POST['devign_covid_ninteen_company_heading'] ) ) {
      
      $option = stripslashes( $_POST['devign_covid_ninteen_company_heading'] );
      $option = sanitize_text_field( $option );
      $option = esc_html( $option );
      // $option = filter_var( $option, FILTER_SANITIZE_STRING );

      update_option( 'devign_covid_ninteen_company_heading', $option );

    }

    /**
     * Update Option | devign_covid_ninteen_company_update
     */
    if ( isset( $_POST['devign_covid_ninteen_local_authority_text'] ) ) {

      $option = stripslashes( $_POST['devign_covid_ninteen_local_authority_text'] );
      $option = sanitize_text_field( $option );
      $option = esc_html( $option );
      update_option( 'devign_covid_ninteen_local_authority_text', $option );

    }
    
    /**
     * Update Option | devign_covid_ninteen_company_update
     */
    if ( isset( $_POST['devign_covid_ninteen_local_authority_link'] ) ) {
      
      $option = stripslashes( $_POST['devign_covid_ninteen_local_authority_link'] );
      $option = sanitize_text_field( $option );
      $option = esc_html( $option );

      update_option( 'devign_covid_ninteen_local_authority_link', $option );
    }

    /**
     * Update Option | devign_covid_ninteen_theme_color
     */
    if ( isset( $_POST['devign_covid_ninteen_theme_color'] ) ) {
      
      $option = sanitize_text_field( $_POST['devign_covid_ninteen_theme_color'] );
      update_option( 'devign_covid_ninteen_theme_color', $option );

    }

    /**
     * Update Option | devign_covid_ninteen_text_color
     */
    if ( isset( $_POST['devign_covid_ninteen_text_color'] ) ) {
      
      $option = sanitize_text_field( $_POST['devign_covid_ninteen_text_color'] );
      $option = esc_html( $option );

      update_option( 'devign_covid_ninteen_text_color', $option );
      
    }

    /**
     * Update Option | devign_covid_ninteen_background_color
     */
    if ( isset( $_POST['devign_covid_ninteen_background_color'] ) ) {
      
      $option = sanitize_text_field( $_POST['devign_covid_ninteen_background_color'] );
      $option = esc_html( $option );

      update_option( 'devign_covid_ninteen_background_color', $option );
      
    }

    /**
     * Update Option | devign_covid_ninteen_content_text_color
     */
    if ( isset( $_POST['devign_covid_ninteen_content_text_color'] ) ) {
      
      $option = sanitize_text_field( $_POST['devign_covid_ninteen_content_text_color'] );
      $option = esc_html( $option );

      update_option( 'devign_covid_ninteen_content_text_color', $option );
      
    }

    /**
     * Update Option | devign_covid_ninteen_show_button_icon
     */
    if ( isset( $_POST['devign_covid_ninteen_show_button_icon'] ) ) {
      $option = sanitize_text_field( $_POST['devign_covid_ninteen_show_button_icon'] );
      update_option( 'devign_covid_ninteen_show_button_icon', $option );
    } else {
        $option = 'no';
        update_option( 'devign_covid_ninteen_show_button_icon', $option );
      }

    /**
     * Update Option | devign_covid_ninteen_button_text
     */
    if ( isset( $_POST['devign_covid_ninteen_button_text'] ) ) {
      
      $option = stripslashes( $_POST['devign_covid_ninteen_button_text'] );
      $option = sanitize_text_field( $option );
      $option = esc_html( $option );

      update_option( 'devign_covid_ninteen_button_text', $option );

    }

    update_option( 'devign_covid_ninteen_last_updated', time() );

    /**
     * Update Option | devign_covid_ninteen_show_button_icon
     */
    if ( isset( $_POST['devign_covid_ninteen_show_backlink'] ) ) {
      $option = sanitize_text_field( $_POST['devign_covid_ninteen_show_backlink'] );
      update_option( 'devign_covid_ninteen_show_backlink', $option );
    } else {
        $option = 'no';
        update_option( 'devign_covid_ninteen_show_backlink', $option );
      }

    /**
     * Update Option | devign_covid_ninteen_google_search_announcement
     */
    if ( isset( $_POST['devign_covid_ninteen_google_search_announcement'] ) ) {
      $option = sanitize_text_field( $_POST['devign_covid_ninteen_google_search_announcement'] );
      update_option( 'devign_covid_ninteen_google_search_announcement', $option );
    } else {
        $option = 'no';
        update_option( 'devign_covid_ninteen_google_search_announcement', $option );
      }

    /**
     * Update Option | devign_covid_ninteen_spatial_coverage
     */
    if ( isset( $_POST['devign_covid_ninteen_spatial_coverage'] ) ) {
      
      $option = stripslashes( $_POST['devign_covid_ninteen_spatial_coverage'] );
      $option = sanitize_text_field( $option );
      $option = esc_html( $option );

      update_option( 'devign_covid_ninteen_spatial_coverage', $option );

    }

    /**
     * Update Option | devign_covid_ninteen_button_text_mobile
     */
    if ( isset( $_POST['devign_covid_ninteen_button_text_mobile'] ) ) {
      $option = sanitize_text_field( $_POST['devign_covid_ninteen_button_text_mobile'] );
      update_option( 'devign_covid_ninteen_button_text_mobile', $option );
    } else {
        $option = 'no';
        update_option( 'devign_covid_ninteen_button_text_mobile', $option );
      }



  }