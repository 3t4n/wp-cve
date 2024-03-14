<?php

// Comming Soon - Checkbox
function naedu_pro_soon_render() {
  $options = get_option( 'naedu_uw_settings' );
  ?>
  <label class="switch">
    <input type='checkbox' name='naedu_uw_settings[naedu_pro_soon]' id='naedu_pro_soon-id' <?php checked( isset($options['naedu_pro_soon']), 1 ); ?> value='1' />
    <span class="slider round"></span>
  </label>
  <?php
}
