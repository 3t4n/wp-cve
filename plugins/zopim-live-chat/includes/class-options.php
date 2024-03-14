<?php

class Zopim_Options
{
  const ZOPIM_OPTION_SALT = 'zopimSalt';
  const ZOPIM_OPTION_USERNAME = 'zopimUsername';
  const ZOPIM_OPTION_CODE = 'zopimCode';
  const ZOPIM_OPTION_WIDGET = 'zopimWidgetOptions';
  const ZENDESK_OPTION_SUBDOMAIN = 'zendeskSubdomain';

  /**
   * Returns the addional widget options set on the plugin dashboard.
   * @return string
   */
  public static function get_widget_options()
  {
    $opts = get_option( self::ZOPIM_OPTION_WIDGET );
    if ( $opts )
      return stripslashes( $opts );

    $zopim_embed_opts = "\$zopim( function() {";
    $zopim_embed_opts .= "\n})";
    $opts = $zopim_embed_opts;

    update_option( self::ZOPIM_OPTION_WIDGET, $opts );

    $list = array(
      'zopimLang',
      'zopimPosition',
      'zopimTheme',
      'zopimColor',
      'zopimUseGreetings',
      'zopimUseBubble',
      'zopimBubbleTitle',
      'zopimBubbleText',
      'zopimBubbleEnable',
      'zopimHideOnOffline'
    );

    foreach ( $list as $key ):
      delete_option( $key );
    endforeach;

    return ( $opts ) ? $opts : '';
  }
}
