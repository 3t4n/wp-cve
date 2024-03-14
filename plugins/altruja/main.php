<?php

class AltrujaMain {
  protected $options;
  public function __construct() {

    $this->options = get_option('altruja');

    add_action('wp_head', array($this, 'async'));
    add_action('wp_footer', array($this, 'footer'));
  }

  public function async() {
    echo '<script>'.$this->options['async'].'</script>'."\n";
  }

  public function footer() {
    echo $this->options['link'];
  }
}

