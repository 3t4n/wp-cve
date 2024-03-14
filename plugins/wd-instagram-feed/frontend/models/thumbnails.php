<?php

class WDI_Thumbnails_model {
  private $feed_row;
  public $theme_row;
  public $wdi_feed_counter;

  public function __construct( $feed_row, $wdi_feed_counter ) {
    $this->feed_row = $this->parseDefaults($feed_row);
    $this->wdi_feed_counter = $wdi_feed_counter;
    $this->theme_row = $this->setThemeDefaults();
  }

  public function get_feed_row() {
    return $this->feed_row;
  }

  public function getThemeDefaults() {
    return $this->theme_row;
  }

  public function parseDefaults( $args ) {
    require_once(WDI_DIR . '/admin/models/feeds.php');
    $defaults = Feeds_model_wdi::wdi_get_feed_defaults();

    return wp_parse_args($args, $defaults);
  }

  private function setThemeDefaults() {
    require_once(WDI_DIR . '/admin/models/themes.php');
    $defaults = Themes_model_wdi::get_theme_defaults();
    $theme_row = $this->get_theme_row($this->feed_row['theme_id']);

    return wp_parse_args($theme_row, $defaults);
  }

  public function get_theme_row( $theme_id ) {
    require_once(WDI_DIR . '/admin/models/themes.php');
    $theme_row = Themes_model_wdi::get_theme_row($theme_id);

    return WDILibrary::objectToArray($theme_row);
  }
}