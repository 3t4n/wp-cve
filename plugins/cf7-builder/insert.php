<?php

/**
 * Class CFBInsert
 */
class CFBInsert {
  public static function tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $cfb_builder = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "cf7b_builder` (
      `id` bigint(20) NOT NULL AUTO_INCREMENT,
      `post_id` bigint(20) NOT NULL,
      `form` longtext NOT NULL,
      `template` longtext NOT NULL,
      `content` longtext NOT NULL,
      `order` bigint(20) NOT NULL,
      `active` tinyint(1) NOT NULL,
      `modified_date` int(10) NOT NULL,
      PRIMARY KEY (`id`)
    ) " . $charset_collate . ";";
    $wpdb->query($cfb_builder);


    $charset_collate = $wpdb->get_charset_collate();
    $cfb_builder = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "cf7b_themes` (
      `id` bigint(20) NOT NULL AUTO_INCREMENT,
      `title` varchar(200) NOT NULL,
      `options` text NOT NULL,
      `def` tinyint(1) NOT NULL,
      `created` varchar(50) NOT NULL,
      PRIMARY KEY (`id`)
    ) " . $charset_collate . ";";
    $wpdb->query($cfb_builder);

    $default = array(
                'general' => array(
                  'general_container_width' => '100',
                  'general_bg_color'        => '#ffffff',
                  'general_font_size'       => '12',
                  'general_font_weight'     => 'normal',
                  'general_font_color'      => '#000000',
                  'general_margin'          => '5px',
                  'general_padding'         => '5px',
                  'general_border_width'    => '1',
                  'general_border_style'    => 'solid',
                  'general_border_color'    => '#dfdfdf',
                  'general_border_radius'   => '0',
                  'general_column_margin'   => '10px',
                  'general_column_padding'  => '10px',
                ),
                'input_fields' => array(
                  'input_width'         => '100',
                  'input_height'        => '30',
                  'input_font_size'     => '12',
                  'input_font_weight'   => 'normal',
                  'input_bg_color'      => '#ffffff',
                  'input_color'         => '#000000',
                  'input_margin'        => '2px',
                  'input_padding'       => '2px',
                  'input_border_width'  => '1',
                  'input_border_style'  => 'solid',
                  'input_border_color'  => '#dfdfdf',
                  'input_border_radius' => '2',
                  'input_box_shadow'    => 'none',
                ),
                'textarea' => array(
                  'textarea_width'        => '100',
                  'textarea_height'        => '50',
                  'textarea_font_size'     => '12',
                  'textarea_font_weight'   => 'normal',
                  'textarea_bg_color'      => '#ffffff',
                  'textarea_color'         => '#000000',
                  'textarea_margin'        => '2px',
                  'textarea_padding'       => '2px',
                  'textarea_border_width'  => '1',
                  'textarea_border_style'  => 'solid',
                  'textarea_border_color'  => '#dfdfdf',
                  'textarea_border_radius' => '2',
                  'textarea_box_shadow'    => 'none',
                ),
                'drodown_fields' => array(
                  'drodown_width'        => '100',
                  'drodown_height'        => '30',
                  'drodown_font_size'     => '12',
                  'drodown_font_weight'   => 'normal',
                  'drodown_bg_color'      => '#ffffff',
                  'drodown_color'         => '#000000',
                  'drodown_margin'        => '2px',
                  'drodown_padding'       => '2px',
                  'drodown_border_width'  => '1',
                  'drodown_border_style'  => 'solid',
                  'drodown_border_color'  => '#dfdfdf',
                  'drodown_border_radius' => '2',
                  'drodown_box_shadow'    => 'none',
                ),
                'radio_fields' => array(
                  'radio_width'             => '14',
                  'radio_height'            => '14',
                  'radio_bg_color'          => '#ffffff',
                  'radio_margin'            => '0px 10px 0px 0px',
                  'radio_padding'           => '0px',
                  'radio_border_width'      => '1',
                  'radio_border_style'      => 'solid',
                  'radio_border_color'      => '#000000',
                  'radio_border_radius'     => '7',
                  'radio_box_shadow'        => 'none',
                  'radio_checked_bg_color'  => '#000000',
                ),
                'checkbox_fields' => array(
                  'checkbox_width'             => '15',
                  'checkbox_height'            => '15',
                  'checkbox_bg_color'          => '#ffffff',
                  'checkbox_margin'            => '2px',
                  'checkbox_padding'           => '2px',
                  'checkbox_border_width'      => '0',
                  'checkbox_border_style'      => 'solid',
                  'checkbox_border_color'      => '#dfdfdf',
                  'checkbox_border_radius'     => '2',
                  'checkbox_box_shadow'        => 'none',
                  'checkbox_checked_bg_color'  => '#000000',
                ),
                'button_fields' => array(
                  'button_width'             => '200',
                  'button_height'            => '20',
                  'button_font_size'         => '12',
                  'button_bg_color'          => '#2271b1',
                  'button_color'             => '#ffffff',
                  'button_font_weight'       => 'normal',
                  'button_margin'            => '2px',
                  'button_padding'           => '2px',
                  'button_border_width'      => '1',
                  'button_border_style'      => 'solid',
                  'button_border_color'      => '#2271b1',
                  'button_border_radius'     => '2',
                  'button_box_shadow'        => 'none',
                  'button_text_align'        => 'center',
                  'button_hover_font_weight' => 'normal',
                  'button_hover_bg_color'    => '#135e96',
                  'button_hover_color'       => '#ffffff',
                ),
                'pagination_fields' => array(
                  'pagination_width'             => '100',
                  'pagination_height'            => '20',
                  'pagination_font_size'         => '12',
                  'pagination_bg_color'          => '#2271b1',
                  'pagination_color'             => '#ffffff',
                  'pagination_font_weight'       => 'normal',
                  'pagination_margin'            => '2px',
                  'pagination_padding'           => '2px',
                  'pagination_border_width'      => '1',
                  'pagination_border_style'      => 'solid',
                  'pagination_border_color'      => '#2271b1',
                  'pagination_border_radius'     => '2',
                  'pagination_box_shadow'        => 'none',
                  'pagination_prev_text_align'   => 'center',
                  'pagination_next_text_align'  => 'center',
                  'pagination_hover_font_weight' => 'normal',
                  'pagination_hover_bg_color'    => '#135e96',
                  'pagination_hover_color'       => '#ffffff',
                ),

    );

    $exists_default = $wpdb->get_var("SELECT * FROM " . $wpdb->prefix . "cf7b_themes");
    if ( !$exists_default ) {
      $created = date('Y/m/d');
      $table = $wpdb->prefix . 'cf7b_themes';
      $data = array(
        'title' => 'Default Theme',
        'options' => json_encode($default),
        'def' => 1,
        'created' => $created
      );
      $format = array( '%s', '%s', '%d', '%s' );
      $wpdb->insert($table, $data, $format);
    }

    $charset_collate = $wpdb->get_charset_collate();
    $cfb_builder = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "cf7b_submissions` (
      `id` bigint(20) NOT NULL AUTO_INCREMENT,
      `form_id` int(11) NOT NULL,
      `fields` longtext NOT NULL,
      `created` varchar(50) NOT NULL,
      `ip_address` varchar(200) NOT NULL,
      `user_agent` varchar(250) NOT NULL,
      PRIMARY KEY (`id`)
    ) " . $charset_collate . ";";
    $wpdb->query($cfb_builder);

  }
}
