<?php

class Zopim_Template
{
  /**
   * Displays a view file. This will look for the correctly named view file in the `/views/` folder.
   *
   * @since 0.2
   *
   * @param String $name The filename of the view to display.
   * @param Array $params An associative array of variables you want to pass to the view.
   *        e.g. ['count' => 1], which you can do "<?php echo $count ?>;" in the view.
   */
  public static function load_template( $name, $params = array() )
  {
    ob_start();

    extract( $params );
    include( ZOPIM_PLUGIN_DIR . 'includes/views/' . $name . '.php' );

    ob_end_flush();
  }
}
