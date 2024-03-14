<?php

if (!defined('WPINC')) die('No access outside of wordpress.');

add_action('admin_menu', 'pzat_register_settings_page');

function pzat_register_settings_page() {
  $page_hook_suffix = add_menu_page(
    'Text Zoom Premium',
    'Text Zoom Premium',
    'manage_options',
    'text-zoom-options',
    'pzat_settings_page_html',
    PZAT_ASSETS_URL . 'admin/menu-icon.png',
    30
  );

  add_action('admin_print_scripts-' . $page_hook_suffix, 'pzat_admin_scripts');

  function pzat_admin_scripts() {
    wp_enqueue_style('pzat_admin_css', PZAT_ASSETS_URL . 'admin/settings.css');
  }
}

function pzat_settings_page_html() {
  if (!current_user_can('manage_options')) {
    return;
  }

  $logo_url = PZAT_ASSETS_URL . '/admin/abilitools-full-logo.png';
  echo("<div class='wrap pzat-wrap'>");
  echo("<h1>Text Zoom by <img src='" . $logo_url . "' class='title-logo' height='35' /></h1>");

  echo("<form action='options.php' method='post'>");

  echo(pzat_preconfigure_notice());

  settings_fields('zoom_settings');
  do_settings_sections('zoom_settings');
  submit_button();

  echo("</form>");
  echo("</div>");
}

add_action('admin_init', 'pzat_init_settings');
function pzat_init_settings() {
  register_setting(
    'zoom_settings',
    'zoom_options'
  );

  add_settings_section(
    'zoom_settings_section',
    '',
    'pzat_render_zoom_settings_section',
    'zoom_settings'
  );

  function pzat_render_zoom_settings_section( $args ) {}

  add_settings_field(
    'zoom_settings_design',
    'Handle Design',
    'pzat_render_zoom_settings_design',
    'zoom_settings',
    'zoom_settings_section',
    [
      'label_for' => 'zoom_settings_design',
      'zoom_custom_data' => 'custom'
    ]
  );

  function pzat_render_zoom_settings_design( $args ) {
    $options = pzat_zoom_options();
    $id = esc_attr( $args['label_for'] );
    $custom_data = esc_attr( $args['zoom_custom_data'] );

    $select_default = selected('full', $options->get($id), false);
    $select_compact = selected('compact', $options->get($id), false);
    $select_none = selected('none', $options->get($id), false);

    $markup = <<<MARKUP
<select id='$id' data-custom='$custom_data' name='zoom_options[$id]'>
  <option value='full' $select_default>Full</option>
  <option value='compact' $select_compact>Compact</option>
  <option value='none' $select_none>None</option>
</select>
MARKUP;

    echo $markup;
    $options_preview = PZAT_ASSETS_URL . 'admin/design-options.png';
    echo "<br/><br/><img src='$options_preview' width='175' height='228' alt='Design Preview'/>";
  }

  add_settings_field(
    'zoom_settings_main_color',
    'Background Color',
    'pzat_render_zoom_settings_color',
    'zoom_settings',
    'zoom_settings_section',
    [
      'label_for' => 'zoom_settings_main_color',
      'zoom_custom_data' => 'custom'
    ]
  );

  add_settings_field(
    'zoom_settings_accent_color',
    'Font-Color',
    'pzat_render_zoom_settings_color',
    'zoom_settings',
    'zoom_settings_section',
    [
      'label_for' => 'zoom_settings_accent_color',
      'zoom_custom_data' => 'custom'
    ]
  );

  function pzat_render_zoom_settings_color( $args ) {
    $options = pzat_zoom_options();
    $id = esc_attr( $args['label_for'] );
    $custom_data = esc_attr( $args['zoom_custom_data'] );
    $value = $options->get($id);

    echo "<input id='$id' data-custom='$custom_data' type='color' name='zoom_options[$id]' value='$value'></input>";
  }

  add_settings_field(
    'zoom_settings_opacity',
    'Handle Opacity',
    'pzat_render_zoom_settings_opacity',
    'zoom_settings',
    'zoom_settings_section',
    [
      'label_for' => 'zoom_settings_opacity',
      'zoom_custom_data' => 'custom'
    ]
  );

  function pzat_render_zoom_settings_opacity( $args ) {
    $options = pzat_zoom_options();
    $id = esc_attr( $args['label_for'] );
    $custom_data = esc_attr( $args['zoom_custom_data'] );
    $opacity = $options->get($id);

    echo "<input id='$id' data-custom='$custom_data' type='range' min='0.1' max='1' step='0.01' name='zoom_options[$id]' value='$opacity' title='$opacity' onchange='this.title = this.value'></input>";
  }

  add_settings_field(
    'zoom_settings_position',
    'Position',
    'pzat_render_zoom_settings_position',
    'zoom_settings',
    'zoom_settings_section',
    [
      'label_for' => 'zoom_settings_position',
      'zoom_custom_data' => 'custom'
    ]
  );

  function pzat_render_zoom_settings_position( $args ) {
    $options = pzat_zoom_options();
    $id = esc_attr( $args['label_for'] );
    $custom_data = esc_attr( $args['zoom_custom_data'] );

    $select_cl = selected('center-left', $options->get($id), false);
    $select_tl = selected('top-left', $options->get($id), false);
    $select_bl = selected('bottom-left', $options->get($id), false);

    $markup = <<<MARKUP
<select id='$id' data-custom='$custom_data' name='zoom_options[$id]'>
  <option value='center-left' $select_cl>Center Left</option>
  <option value='top-left' $select_tl>Top Left</option>
  <option value='bottom-left' $select_bl>Bottom Left</option>
</select>
MARKUP;

    echo($markup);
    echo(pzat_position_details());
  }

  add_settings_field(
    'zoom_settings_logo_url',
    'Logo URL',
    'pzat_render_zoom_settings_logo_url',
    'zoom_settings',
    'zoom_settings_section',
    [
      'label_for' => 'zoom_settings_logo_url',
      'zoom_custom_data' => 'custom'
    ]
  );

  function pzat_render_zoom_settings_logo_url( $args ) {
    echo(pzat_zoom_options_input($args));
    echo(pzat_logo_url_details());
  }

  add_settings_field(
    'zoom_settings_logo_title',
    'Logo Title',
    'pzat_render_zoom_settings_logo_title',
    'zoom_settings',
    'zoom_settings_section',
    [
      'label_for' => 'zoom_settings_logo_title',
      'zoom_custom_data' => 'custom'
    ]
  );

  function pzat_render_zoom_settings_logo_title( $args ) {
    echo(pzat_zoom_options_input($args));
    echo(pzat_logo_title_details());
  }

  add_settings_field(
    'zoom_settings_logo_link',
    'Logo Link',
    'pzat_render_zoom_settings_logo_link',
    'zoom_settings',
    'zoom_settings_section',
    [
      'label_for' => 'zoom_settings_logo_link',
      'zoom_custom_data' => 'custom'
    ]
  );

  function pzat_render_zoom_settings_logo_link( $args ) {
    echo(pzat_zoom_options_input($args));
    echo(pzat_logo_link_details());
  }

  add_settings_field(
    'zoom_settings_blocklist',
    'Blocklist',
    'pzat_render_zoom_settings_blocklist',
    'zoom_settings',
    'zoom_settings_section',
    [
      'label_for' => 'zoom_settings_blocklist',
      'zoom_custom_data' => 'custom',
      'placeholder' => "news\n.pdf"
    ]
  );

  function pzat_render_zoom_settings_blocklist( $args ) {
    echo(pzat_zoom_options_textarea($args));
    echo(pzat_blocklist_details());
  }

  add_settings_field(
    'zoom_settings_strict_blocklist',
    'Strict Blocklist',
    'pzat_render_zoom_settings_strict_blocklist',
    'zoom_settings',
    'zoom_settings_section',
    [
      'label_for' => 'zoom_settings_strict_blocklist',
      'zoom_custom_data' => 'custom',
      'placeholder' => "https://your-website.com/homepage.html\nhttps://your-website.com/imprint"
    ]
  );

  function pzat_render_zoom_settings_strict_blocklist( $args ) {
    echo(pzat_zoom_options_textarea($args));
    echo(pzat_strict_blocklist_details());
  }

  add_settings_field(
    'zoom_settings_content_deselectors',
    'Content Deselector',
    'pzat_render_zoom_settings_content_deselectors',
    'zoom_settings',
    'zoom_settings_section',
    [
      'label_for' => 'zoom_settings_content_deselectors',
      'zoom_custom_data' => 'custom',
      'placeholder' => ".main-menu-top a\nfooter ul li"
    ]
  );

  function pzat_render_zoom_settings_content_deselectors( $args ) {
    echo(pzat_zoom_options_textarea($args));
    echo(pzat_content_deselector_details());
  }
}

// need to use admin_notices to display messages for custom menu page
add_action('admin_notices', 'pzat_settings_message');
function pzat_settings_message() {
  echo settings_errors();
}
