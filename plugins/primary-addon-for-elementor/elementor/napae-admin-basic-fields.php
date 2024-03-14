<?php

// About Me - Checkbox
if ( ! function_exists( 'napafe_about_me_render' ) ) {
  function napafe_about_me_render() {
    $options = get_option( 'pafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_bw_settings[napafe_about_me]' id='napafe_about_me-id' <?php checked( isset($options['napafe_about_me']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// About Us - Checkbox
if ( ! function_exists( 'napafe_about_us_render' ) ) {
  function napafe_about_us_render() {
    $options = get_option( 'pafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_bw_settings[napafe_about_us]' id='napafe_about_us-id' <?php checked( isset($options['napafe_about_us']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Blog - Checkbox
if ( ! function_exists( 'napafe_blog_render' ) ) {
  function napafe_blog_render() {
    $options = get_option( 'pafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_bw_settings[napafe_blog]' id='napafe_blog-id' <?php checked( isset($options['napafe_blog']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Primary Button - Checkbox
if ( ! function_exists( 'napafe_primary_button_render' ) ) {
  function napafe_primary_button_render() {
    $options = get_option( 'pafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_bw_settings[napafe_primary_button]' id='napafe_primary_button-id' <?php checked( isset($options['napafe_primary_button']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Chart - Checkbox
if ( ! function_exists( 'napafe_chart_render' ) ) {
  function napafe_chart_render() {
    $options = get_option( 'pafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_bw_settings[napafe_chart]' id='napafe_chart-id' <?php checked( isset($options['napafe_chart']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Contact - Checkbox
if ( ! function_exists( 'napafe_contact_render' ) ) {
  function napafe_contact_render() {
    $options = get_option( 'pafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_bw_settings[napafe_contact]' id='napafe_contact-id' <?php checked( isset($options['napafe_contact']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Gallery - Checkbox
if ( ! function_exists( 'napafe_gallery_render' ) ) {
  function napafe_gallery_render() {
    $options = get_option( 'pafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_bw_settings[napafe_gallery]' id='napafe_gallery-id' <?php checked( isset($options['napafe_gallery']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Get Apps - Checkbox
if ( ! function_exists( 'napafe_get_apps_render' ) ) {
  function napafe_get_apps_render() {
    $options = get_option( 'pafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_bw_settings[napafe_get_apps]' id='napafe_get_apps-id' <?php checked( isset($options['napafe_get_apps']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// History - Checkbox
if ( ! function_exists( 'napafe_history_render' ) ) {
  function napafe_history_render() {
    $options = get_option( 'pafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_bw_settings[napafe_history]' id='napafe_history-id' <?php checked( isset($options['napafe_history']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Image Compare - Checkbox
if ( ! function_exists( 'napafe_image_compare_render' ) ) {
  function napafe_image_compare_render() {
    $options = get_option( 'pafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_bw_settings[napafe_image_compare]' id='napafe_image_compare-id' <?php checked( isset($options['napafe_image_compare']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Process - Checkbox
if ( ! function_exists( 'napafe_process_render' ) ) {
  function napafe_process_render() {
    $options = get_option( 'pafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_bw_settings[napafe_process]' id='napafe_process-id' <?php checked( isset($options['napafe_process']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Section Title - Checkbox
if ( ! function_exists( 'napafe_section_title_render' ) ) {
  function napafe_section_title_render() {
    $options = get_option( 'pafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_bw_settings[napafe_section_title]' id='napafe_section_title-id' <?php checked( isset($options['napafe_section_title']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Separator - Checkbox
if ( ! function_exists( 'napafe_separator_render' ) ) {
  function napafe_separator_render() {
    $options = get_option( 'pafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_bw_settings[napafe_separator]' id='napafe_separator-id' <?php checked( isset($options['napafe_separator']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Services - Checkbox
if ( ! function_exists( 'napafe_services_render' ) ) {
  function napafe_services_render() {
    $options = get_option( 'pafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_bw_settings[napafe_services]' id='napafe_services-id' <?php checked( isset($options['napafe_services']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Slider - Checkbox
if ( ! function_exists( 'napafe_slider_render' ) ) {
  function napafe_slider_render() {
    $options = get_option( 'pafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_bw_settings[napafe_slider]' id='napafe_slider-id' <?php checked( isset($options['napafe_slider']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Subscribe Contact - Checkbox
if ( ! function_exists( 'napafe_subscribe_contact_render' ) ) {
  function napafe_subscribe_contact_render() {
    $options = get_option( 'pafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_bw_settings[napafe_subscribe_contact]' id='napafe_subscribe_contact-id' <?php checked( isset($options['napafe_subscribe_contact']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Table - Checkbox
if ( ! function_exists( 'napafe_table_render' ) ) {
  function napafe_table_render() {
    $options = get_option( 'pafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_bw_settings[napafe_table]' id='napafe_table-id' <?php checked( isset($options['napafe_table']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Team Single - Checkbox
if ( ! function_exists( 'napafe_team_single_render' ) ) {
  function napafe_team_single_render() {
    $options = get_option( 'pafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_bw_settings[napafe_team_single]' id='napafe_team_single-id' <?php checked( isset($options['napafe_team_single']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Team - Checkbox
if ( ! function_exists( 'napafe_team_render' ) ) {
  function napafe_team_render() {
    $options = get_option( 'pafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_bw_settings[napafe_team]' id='napafe_team-id' <?php checked( isset($options['napafe_team']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Testimonials - Checkbox
if ( ! function_exists( 'napafe_testimonials_render' ) ) {
  function napafe_testimonials_render() {
    $options = get_option( 'pafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_bw_settings[napafe_testimonials]' id='napafe_testimonials-id' <?php checked( isset($options['napafe_testimonials']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Typewriter - Checkbox
if ( ! function_exists( 'napafe_typewriter_render' ) ) {
  function napafe_typewriter_render() {
    $options = get_option( 'pafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_bw_settings[napafe_typewriter]' id='napafe_typewriter-id' <?php checked( isset($options['napafe_typewriter']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Video - Checkbox
if ( ! function_exists( 'napafe_video_render' ) ) {
  function napafe_video_render() {
    $options = get_option( 'pafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_bw_settings[napafe_video]' id='napafe_video-id' <?php checked( isset($options['napafe_video']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Pricing Table - Checkbox
if ( ! function_exists( 'napafe_pricing_table_render' ) ) {
  function napafe_pricing_table_render() {
    $options = get_option( 'pafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_bw_settings[napafe_pricing_table]' id='napafe_pricing_table-id' <?php checked( isset($options['napafe_pricing_table']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Woo Product Grid - Checkbox
if ( ! function_exists( 'napafe_woo_grid_render' ) ) {
  function napafe_woo_grid_render() {
    $options = get_option( 'pafe_bw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_bw_settings[napafe_woo_grid]' id='napafe_woo_grid-id' <?php checked( isset($options['napafe_woo_grid']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}
