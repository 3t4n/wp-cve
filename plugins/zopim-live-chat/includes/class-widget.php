<?php

class Zopim_Widget
{
  // We need some CSS to position the paragraph
  public static function zopimme()
  {
    $subdomain = get_option( Zopim_Options::ZENDESK_OPTION_SUBDOMAIN );

    if ($subdomain) {
      echo Zopim_Widget::get_widget_code_using_subdomain($subdomain);
      return;
    }

    $current_user = wp_get_current_user();

    $code = get_option( Zopim_Options::ZOPIM_OPTION_CODE );

    if ( ( $code == "" || $code == "zopim" ) && !( isset( $_GET[ 'page' ] ) && preg_match( "/zopim/", $_GET[ 'page' ] ) ) && ( !preg_match( "/zopim/", $_SERVER[ "SERVER_NAME" ] ) ) ) {
      return;
    }

    echo "<!--Embed from Zendesk Chat Chat Wordpress Plugin v" . VERSION_NUMBER . "-->
    <!--Start of Zopim Live Chat Script-->
    <script type=\"text/javascript\">
    window.\$zopim||(function(d,s){var z=\$zopim=function(c){z._.push(c)},$=z.s=
    d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
      _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
      $.src='//v2.zopim.com/?" . $code . "';z.t=+new Date;$.
      type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
      </script>";

      echo '<script>';
      if ( isset( $current_user ) ):
        $firstname = $current_user->display_name;
        $useremail = $current_user->user_email;
        if ( $firstname != "" && $useremail != "" ):
          echo "\$zopim(function(){\$zopim.livechat.set({name: '$firstname', email: '$useremail'}); });";
        endif;
      endif;

      echo Zopim_Options::get_widget_options();
      echo '</script>';
      echo "<!--End of Zendesk Chat Script-->";
    }

    public static function get_widget_code_using_subdomain( $subdomain )
    {
      $url = "https://ekr.zdassets.com/snippets/web_widget/" . $subdomain .".zendesk.com?dynamic_snippet=true";
      $response = wp_remote_get($url, array());


      if ( is_wp_error( $response ) ) {
        $error = array( 'wp_error' => $response->get_error_message() );
        return json_encode( $error );
      }

      return $response['body'];
    }
  }
