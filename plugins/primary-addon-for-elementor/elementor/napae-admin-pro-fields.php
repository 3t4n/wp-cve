<?php

// Breadcrumbs (Pro) - Checkbox
if ( ! function_exists( 'napafe_pro_breadcrumbs_render' ) ) {
  function napafe_pro_breadcrumbs_render() {
    $options = get_option( 'pafe_pw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_pw_settings[napafe_pro_breadcrumbs]' id='napafe_pro_breadcrumbs-id' <?php checked( isset($options['napafe_pro_breadcrumbs']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Button (Pro) - Checkbox
if ( ! function_exists( 'napafe_pro_button_render' ) ) {
  function napafe_pro_button_render() {
    $options = get_option( 'pafe_pw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_pw_settings[napafe_pro_button]' id='napafe_pro_button-id' <?php checked( isset($options['napafe_pro_button']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Call To Action (Pro) - Checkbox
if ( ! function_exists( 'napafe_pro_call_to_action_render' ) ) {
  function napafe_pro_call_to_action_render() {
    $options = get_option( 'pafe_pw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_pw_settings[napafe_pro_call_to_action]' id='napafe_pro_call_to_action-id' <?php checked( isset($options['napafe_pro_call_to_action']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Contact Form (Pro) - Checkbox
if ( ! function_exists( 'napafe_pro_contact_form_render' ) ) {
  function napafe_pro_contact_form_render() {
    $options = get_option( 'pafe_pw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_pw_settings[napafe_pro_contact_form]' id='napafe_pro_contact_form-id' <?php checked( isset($options['napafe_pro_contact_form']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Counter (Pro) - Checkbox
if ( ! function_exists( 'napafe_pro_counter_render' ) ) {
  function napafe_pro_counter_render() {
    $options = get_option( 'pafe_pw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_pw_settings[napafe_pro_counter]' id='napafe_pro_counter-id' <?php checked( isset($options['napafe_pro_counter']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Coupon (Pro) - Checkbox
if ( ! function_exists( 'napafe_pro_coupon_render' ) ) {
  function napafe_pro_coupon_render() {
    $options = get_option( 'pafe_pw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_pw_settings[napafe_pro_coupon]' id='napafe_pro_coupon-id' <?php checked( isset($options['napafe_pro_coupon']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// FAQ (Pro) - Checkbox
if ( ! function_exists( 'napafe_pro_faq_render' ) ) {
  function napafe_pro_faq_render() {
    $options = get_option( 'pafe_pw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_pw_settings[napafe_pro_faq]' id='napafe_pro_faq-id' <?php checked( isset($options['napafe_pro_faq']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Flip Box (Pro) - Checkbox
if ( ! function_exists( 'napafe_pro_flip_box_render' ) ) {
  function napafe_pro_flip_box_render() {
    $options = get_option( 'pafe_pw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_pw_settings[napafe_pro_flip_box]' id='napafe_pro_flip_box-id' <?php checked( isset($options['napafe_pro_flip_box']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Heading (Pro) - Checkbox
if ( ! function_exists( 'napafe_pro_heading_render' ) ) {
  function napafe_pro_heading_render() {
    $options = get_option( 'pafe_pw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_pw_settings[napafe_pro_heading]' id='napafe_pro_heading-id' <?php checked( isset($options['napafe_pro_heading']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Icon Box (Pro) - Checkbox
if ( ! function_exists( 'napafe_pro_icon_box_render' ) ) {
  function napafe_pro_icon_box_render() {
    $options = get_option( 'pafe_pw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_pw_settings[napafe_pro_icon_box]' id='napafe_pro_icon_box-id' <?php checked( isset($options['napafe_pro_icon_box']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Image Hotspot (Pro) - Checkbox
if ( ! function_exists( 'napafe_pro_image_hotspot_render' ) ) {
  function napafe_pro_image_hotspot_render() {
    $options = get_option( 'pafe_pw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_pw_settings[napafe_pro_image_hotspot]' id='napafe_pro_image_hotspot-id' <?php checked( isset($options['napafe_pro_image_hotspot']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Insta Feed (Pro) - Checkbox
if ( ! function_exists( 'napafe_pro_insta_feed_render' ) ) {
  function napafe_pro_insta_feed_render() {
    $options = get_option( 'pafe_pw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_pw_settings[napafe_pro_insta_feed]' id='napafe_pro_insta_feed-id' <?php checked( isset($options['napafe_pro_insta_feed']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// News Ticker (Pro) - Checkbox
if ( ! function_exists( 'napafe_pro_news_ticker_render' ) ) {
  function napafe_pro_news_ticker_render() {
    $options = get_option( 'pafe_pw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_pw_settings[napafe_pro_news_ticker]' id='napafe_pro_news_ticker-id' <?php checked( isset($options['napafe_pro_news_ticker']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Protected Content (Pro) - Checkbox
if ( ! function_exists( 'napafe_pro_protected_content_render' ) ) {
  function napafe_pro_protected_content_render() {
    $options = get_option( 'pafe_pw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_pw_settings[napafe_pro_protected_content]' id='napafe_pro_protected_content-id' <?php checked( isset($options['napafe_pro_protected_content']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Reviews (Pro) - Checkbox
if ( ! function_exists( 'napafe_pro_reviews_render' ) ) {
  function napafe_pro_reviews_render() {
    $options = get_option( 'pafe_pw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_pw_settings[napafe_pro_reviews]' id='napafe_pro_reviews-id' <?php checked( isset($options['napafe_pro_reviews']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Skill Bar (Pro) - Checkbox
if ( ! function_exists( 'napafe_pro_skill_bar_render' ) ) {
  function napafe_pro_skill_bar_render() {
    $options = get_option( 'pafe_pw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_pw_settings[napafe_pro_skill_bar]' id='napafe_pro_skill_bar-id' <?php checked( isset($options['napafe_pro_skill_bar']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Timeline (Pro) - Checkbox
if ( ! function_exists( 'napafe_pro_timeline_render' ) ) {
  function napafe_pro_timeline_render() {
    $options = get_option( 'pafe_pw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_pw_settings[napafe_pro_timeline]' id='napafe_pro_timeline-id' <?php checked( isset($options['napafe_pro_timeline']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Woo Product Carousel (Pro) - Checkbox
if ( ! function_exists( 'napafe_pro_woo_carousel_render' ) ) {
  function napafe_pro_woo_carousel_render() {
    $options = get_option( 'pafe_pw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_pw_settings[napafe_pro_woo_carousel]' id='napafe_pro_woo_carousel-id' <?php checked( isset($options['napafe_pro_woo_carousel']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}

// Duel Button (Pro) - Checkbox
if ( ! function_exists( 'napafe_pro_duel_button_render' ) ) {
  function napafe_pro_duel_button_render() {
    $options = get_option( 'pafe_pw_settings' );
    ?>
    <label class="switch">
      <input type='checkbox' name='pafe_pw_settings[napafe_pro_duel_button]' id='napafe_pro_duel_button-id' <?php checked( isset($options['napafe_pro_duel_button']), 1 ); ?> value='1' />
      <span class="slider round"></span>
    </label>
    <?php
  }
}
