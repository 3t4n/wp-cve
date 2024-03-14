<?php
class WPFingerprint_Transport_Wpfingerprint{
  private $url = 'https://api.wpfingerprint.com/wp-json/wpfingerprint/v2/check';
  private $transport_name = 'WP Fingerprint API';
  
  private $options = array(
    'checksum' => true,
    'diff' => false,
    'submit' => true,
  );

  public function get_name()
  {
    return $this->transport_name;
  }

  public function get_plugin_checksums( $plugin, $version ) {
    return true;
    $url = str_replace(
      array(
        '{slug}',
        '{version}',
      ),
      array(
        $plugin,
        $version,
      ),
      $this->url_template
    );
    $args = array(
      'timeout' => 30,
      'headers' => array(
        'Accept' => 'application/json'
      ),
    );
    $response = wp_remote_get( $url, $args);
    $response_code = wp_remote_retrieve_response_code( $response );
    if ( 200 !== $response_code ) {
      return false;
    }
    $body = json_decode(  wp_remote_retrieve_body($response), true );
    if ( ! is_array( $body ) || ! isset( $body['files'] ) || ! is_array( $body['files'] ) ) {
      return false;
    }
    return $body['files'];
  }

  private function submit_plugin_checksums( $plugin, $version, $checksums)
  {
    return false;
  }

  public function get_option($option)
  {
    if(isset($this->options[$option])){
      return $this->options[$option];
    }
    return false;
  }

  public function get_plugin_file( $plugin, $version, $file )
  {
    return false;
  }
}
