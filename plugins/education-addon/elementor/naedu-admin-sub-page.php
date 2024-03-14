<?php
function naedu_bw_settings_init() {

  register_setting( 'naeduBasicWidgets', 'naedu_bw_settings' );

  // Card Title - Basic Widgets
  add_settings_section(
    'naedu_naeduBasicWidgets_section',
    __( 'Basic Widgets', 'education-addon' ),
    '',
    'naeduBasicWidgets' 
  );

  $naedu_basic_widgets['banner'] = __( 'Banner', 'education-addon' );
  $naedu_basic_widgets['blog'] = __( 'Blog', 'education-addon' );
  $naedu_basic_widgets['classes'] = __( 'Classes', 'education-addon' );
  $naedu_basic_widgets['countdown'] = __( 'Countdown', 'education-addon' );
  $naedu_basic_widgets['category'] = __( 'Category', 'education-addon' );
  $naedu_basic_widgets['course'] = __( 'Course', 'education-addon' );
  $naedu_basic_widgets['sensei_category'] = __( 'Sensei Category', 'education-addon' );
  $naedu_basic_widgets['sensei_course'] = __( 'Sensei Course', 'education-addon' );
  $naedu_basic_widgets['ms_category'] = __( 'MasterStudy Category', 'education-addon' );
  $naedu_basic_widgets['ms_course'] = __( 'MasterStudy Course', 'education-addon' );
  $naedu_basic_widgets['tutor_category'] = __( 'Tutor Category', 'education-addon' );
  $naedu_basic_widgets['tutor_course'] = __( 'Tutor Course', 'education-addon' );
  $naedu_basic_widgets['event'] = __( 'Event', 'education-addon' );
  $naedu_basic_widgets['instructor'] = __( 'Instructor', 'education-addon' );
  $naedu_basic_widgets['meeting'] = __( 'Meeting', 'education-addon' );
  $naedu_basic_widgets['newsletter'] = __( 'Newsletter', 'education-addon' );
  $naedu_basic_widgets['offer'] = __( 'Offer', 'education-addon' );
  $naedu_basic_widgets['plans'] = __( 'Plans', 'education-addon' );
  $naedu_basic_widgets['process'] = __( 'Process', 'education-addon' );
  $naedu_basic_widgets['profile'] = __( 'Profile', 'education-addon' );
  $naedu_basic_widgets['section_title'] = __( 'Section Title', 'education-addon' );
  $naedu_basic_widgets['testimonials'] = __( 'Testimonials', 'education-addon' );
  $naedu_basic_widgets['appointment'] = __( 'Appointment', 'education-addon' );
  $naedu_basic_widgets['course_carousel'] = __( 'Course Carousel', 'education-addon' );
  $naedu_basic_widgets['services'] = __( 'Services', 'education-addon' );
  $naedu_basic_widgets['step_flow'] = __( 'Step Flow', 'education-addon' );
  $naedu_basic_widgets['pricing_table'] = __( 'Pricing Table', 'education-addon' );
  $naedu_basic_widgets['notice'] = __( 'Notice', 'education-addon' );
  foreach ($naedu_basic_widgets as $key => $value) {
    // Label
    add_settings_field(
      'naedu_'. $key,
      $value,
      'naedu_'. $key .'_render',
      'naeduBasicWidgets',
      'naedu_naeduBasicWidgets_section',
      array( 'label_for' => 'naedu_'. $key .'-id' )
    );
  }

}

function naedu_uw_settings_init() {

  register_setting( 'naeduProWidgets', 'naedu_uw_settings' );

  // Card Title - Pro Widgets
  add_settings_section(
    'naedu_naeduProWidgets_section',
    __( 'Pro Widgets', 'education-addon' ),
    '',
    'naeduProWidgets'
  );

  $naedu_pro_widgets['pro_soon'] = __( 'Comming Soon', 'education-addon' );
  foreach ($naedu_pro_widgets as $key => $value) {
    // Label
    add_settings_field(
      'naedu_'. $key,
      $value,
      'naedu_'. $key .'_render',
      'naeduProWidgets',
      'naedu_naeduProWidgets_section',
      array( 'label_for' => 'naedu_'. $key .'-id' )
    );
  }

}

// Output on Admin Page
function naedu_admin_sub_page() { ?>
  <h2 class="title">Enable & Disable - Education Elementor Widgets</h2>
  <div class="card naedu-fields-card naedu-fields-basic">
    <form action='options.php' method='post'>
      <?php
      settings_fields( 'naeduBasicWidgets' );
      do_settings_sections( 'naeduBasicWidgets' );
      submit_button(__( 'Save Basic Widgets Settings', 'education-addon' ), 'basic-submit-class');
      ?>
    </form>
  </div><div class="card naedu-fields-card naedu-fields-pro">
    <form action='options.php' method='post'>
      <?php
      settings_fields( 'naeduProWidgets' );
      do_settings_sections( 'naeduProWidgets' );
      submit_button(__( 'Save Pro Widgets Settings', 'education-addon' ), 'pro-submit-class');
      ?>
    </form>
  </div>
  <?php
}
