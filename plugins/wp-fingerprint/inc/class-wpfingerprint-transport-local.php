<?php
class WPFingerprint_Transport_Local{
  private $transport_name = 'Local';
  
  private $options = array(
    'checksum' => true,
    'diff' => false,
    'submit' => false
  );

  public function get_name()
  {
    return $this->transport_name;
  }

  public function get_plugin_checksums( $plugin, $version ) {
		$body = $this->get_plugin( $plugin );
		if ( ! is_array( $body ) || ! isset( $body['files'] ) || ! is_array( $body['files'] ) ) {
			return false;
		}
		return $body['files'];
	}

  private function get_plugin( $plugin = false ) {
    return true;
    $location = ABSPATH.'wpfingerprint.json';
    $location = apply_filters( 'wpfingerprint_local_location', $location );
    if(file_exists($location)){
      $file =  json_decode( file_get_contents($location, FILE_USE_INCLUDE_PATH) );
      if( ! is_array( $file) || ! is_array( $file[$plugin] ) ) {
        return false;
      }
     return $file[$plugin];
    }
    else{
      return false;
    }
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
