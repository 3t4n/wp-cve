<?php defined('ABSPATH') || exit;

class Nouvello_WeManage_Utm_Html
{

  public function __construct()
  {
  }

  public static function get_conversion_report_table_for_email($meta_array)
  {

    $html = <<<'EOT'
<table width="100%%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF">
  <tr><td style="padding:0">&nbsp;</td></tr>
  %1$s
  <tr><td style="padding:0">&nbsp;</td></tr>
</table>
EOT;

    if (isset($meta_array['cookie_consent']['value']) && $meta_array['cookie_consent']['value'] === 'deny') :

      $tr = sprintf(
        '<tr bgcolor="%3$s">
              <td style="padding:6px 4px">
                  <font style="font-family: sans-serif; font-size:12px;"><strong>%1$s</strong></font>
              </td>
           </tr>
           <tr bgcolor="%4$s">
              <td style="padding:6px 20px">
                  <font style="font-family: sans-serif; font-size:12px;">%2$s<br>%5$s</font>
              </td>
           </tr>',
        esc_html(__('Cookie Consent', 'ns-wmw')),
        esc_html(strtoupper($meta_array['cookie_consent']['value'])),
        esc_attr('#EAF2FA'),
        esc_attr('#FFFFFF'),
        esc_html(__('User did not consent to cookie tracking.', 'ns-wmw'))
      );

      return sprintf($html, $tr);

    endif;

    $tr = '';
    foreach ($meta_array as $meta) :

      if (!empty($meta['scope']) && !in_array('email', $meta['scope'])) :
        //skip if not email
        continue;
      endif;

      if (empty($meta['value']) && strlen($meta['value']) == 0) :

        $meta_value = '&nbsp;';

      elseif (isset($meta['type'])) :

        switch ($meta['type']):

          case 'url':

            $meta_value = wp_kses(Nouvello_WeManage_Utm_Functions::pretty_url_with_break($meta['value']), array('br' => array()));
            break;

          case 'datetime':

            $tmp_timestamp = Nouvello_WeManage_Utm_Functions::local_date_database_to_timestamp($meta['value']);
            $meta_value = self::formatter_timestamp_to_local_date_human($tmp_timestamp);
            break;

          default:

            $meta_value = esc_html($meta['value']);

        endswitch;

      else :

        $meta_value = esc_html($meta['value']);

      endif;

      $tr .= sprintf(
        '<tr bgcolor="%3$s">
            <td style="padding:6px 4px">
                <font style="font-family: sans-serif; font-size:12px;"><strong>%1$s</strong></font>
            </td>
         </tr>
         <tr bgcolor="%4$s">
            <td style="padding:6px 20px">
                <font style="font-family: sans-serif; font-size:12px;">%2$s</font>
            </td>
         </tr>',
        esc_html($meta['label']),
        $meta_value,
        esc_attr('#EAF2FA'),
        esc_attr('#FFFFFF')
      );
    endforeach;

    return sprintf($html, $tr);
  }


  public static function formatter_timestamp_to_local_date_human($timestamp)
  {

    try {

      $output = '-';

      if ($timestamp) :

        $output = Nouvello_WeManage_Utm_Functions::translate_timestamp_to_local_date_human($timestamp, Nouvello_WeManage_Utm_Settings::get_attr_date_format() . ' ' . Nouvello_WeManage_Utm_Settings::get_attr_time_format());

      endif;
    } catch (\Exception $e) {
    }

    return $output;
  }

  public static function get_clid_tag_html($clid)
  {

    switch ($clid):
      case 'gclid':

        $html = '<div style="display: inline-block; padding: 2px 10px; border-radius:9999px; background-color: #c53030; color: #ffffff">' . __('Google', 'ns-wmw') . '</div>';
        break;

      case 'fbclid':

        $html = '<div style="display: inline-block; padding: 2px 10px; border-radius:9999px; background-color: #2b6cb0; color: #ffffff">' . __('Facebook', 'ns-wmw') . '</div>';
        break;

      case 'msclkid':

        $html = '<div style="display: inline-block; padding: 2px 10px; border-radius:9999px; background-color: #039be5; color: #ffffff">' . __('Microsoft', 'ns-wmw') . '</div>';
        break;

      default:

        $html = '';
        break;

    endswitch;

    return $html;
  }
}
