<?php

interface ICBroPermissionsBackend {
  function can($capability, $display_to_guests);
  function can_manage_settings();
}

?>
