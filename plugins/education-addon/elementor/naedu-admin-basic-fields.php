<?php
// Banner - Checkbox
function naedu_banner_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_banner]' id='naedu_banner-id' <?php checked( isset($options['naedu_banner']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}
// Blog - Checkbox
function naedu_blog_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_blog]' id='naedu_blog-id' <?php checked( isset($options['naedu_blog']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}
// Classes - Checkbox
function naedu_classes_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_classes]' id='naedu_classes-id' <?php checked( isset($options['naedu_classes']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}
// Countdown - Checkbox
function naedu_countdown_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_countdown]' id='naedu_countdown-id' <?php checked( isset($options['naedu_countdown']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}
// Category - Checkbox
function naedu_category_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_category]' id='naedu_category-id' <?php checked( isset($options['naedu_category']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}
// Course - Checkbox
function naedu_course_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_course]' id='naedu_course-id' <?php checked( isset($options['naedu_course']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}
// Category - Checkbox
function naedu_sensei_category_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_sensei_category]' id='naedu_sensei_category-id' <?php checked( isset($options['naedu_sensei_category']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}
// Course - Checkbox
function naedu_sensei_course_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_sensei_course]' id='naedu_sensei_course-id' <?php checked( isset($options['naedu_sensei_course']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}
// Category - Checkbox
function naedu_ms_category_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_ms_category]' id='naedu_ms_category-id' <?php checked( isset($options['naedu_ms_category']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}
// Course - Checkbox
function naedu_ms_course_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_ms_course]' id='naedu_ms_course-id' <?php checked( isset($options['naedu_ms_course']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}
// Category - Checkbox
function naedu_tutor_category_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_tutor_category]' id='naedu_tutor_category-id' <?php checked( isset($options['naedu_tutor_category']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}
// Course - Checkbox
function naedu_tutor_course_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_tutor_course]' id='naedu_tutor_course-id' <?php checked( isset($options['naedu_tutor_course']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}
// Event - Checkbox
function naedu_event_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_event]' id='naedu_event-id' <?php checked( isset($options['naedu_event']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}
// Instructor - Checkbox
function naedu_instructor_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_instructor]' id='naedu_instructor-id' <?php checked( isset($options['naedu_instructor']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}
// Meeting - Checkbox
function naedu_meeting_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_meeting]' id='naedu_meeting-id' <?php checked( isset($options['naedu_meeting']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}
// Newsletter - Checkbox
function naedu_newsletter_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_newsletter]' id='naedu_newsletter-id' <?php checked( isset($options['naedu_newsletter']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}
// Offer - Checkbox
function naedu_offer_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_offer]' id='naedu_offer-id' <?php checked( isset($options['naedu_offer']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}
// Plans - Checkbox
function naedu_plans_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_plans]' id='naedu_plans-id' <?php checked( isset($options['naedu_plans']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}
// Process - Checkbox
function naedu_process_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_process]' id='naedu_process-id' <?php checked( isset($options['naedu_process']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}
// Profile - Checkbox
function naedu_profile_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_profile]' id='naedu_profile-id' <?php checked( isset($options['naedu_profile']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}
// Section Title - Checkbox
function naedu_section_title_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_section_title]' id='naedu_section_title-id' <?php checked( isset($options['naedu_section_title']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}
// Testimonials - Checkbox
function naedu_testimonials_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_testimonials]' id='naedu_testimonials-id' <?php checked( isset($options['naedu_testimonials']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}

// Appointment - Checkbox
function naedu_appointment_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_appointment]' id='naedu_appointment-id' <?php checked( isset($options['naedu_appointment']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}

// Course Carousel - Checkbox
function naedu_course_carousel_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_course_carousel]' id='naedu_course_carousel-id' <?php checked( isset($options['naedu_course_carousel']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}

// Services - Checkbox
function naedu_services_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_services]' id='naedu_services-id' <?php checked( isset($options['naedu_services']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}

// Step Flow - Checkbox
function naedu_step_flow_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_step_flow]' id='naedu_step_flow-id' <?php checked( isset($options['naedu_step_flow']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}

// Pricing Table - Checkbox
function naedu_pricing_table_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_pricing_table]' id='naedu_pricing_table-id' <?php checked( isset($options['naedu_pricing_table']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}

// Notice - Checkbox
function naedu_notice_render() {
  $options = get_option( 'naedu_bw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_bw_settings[naedu_notice]' id='naedu_notice-id' <?php checked( isset($options['naedu_notice']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}
