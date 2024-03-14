<?php

namespace Element_Ready\Base\Repository;

class Most_Share_Modal extends Base_Modal
{

  function __construct($settings)
  {
    parent::__construct($settings);
    $this->settings();
  }

  public function settings()
  {

    if (isset($this->settings['is_most_share'])) {

      if ($this->settings['is_most_share'] == 'yes') {
        $this->args['meta_key'] = 'element_ready_most_share_post';
        $this->args['orderby']  = 'meta_value_num';
      }
    }

    return $this->args;
  }
}
