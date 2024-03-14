<?php 

  /**
   * WPCF7 Submissions - Activation Hook
   */
  register_activation_hook( DEVIGN_COVID_19_BASENAME, 'devign_covid_nineteen_activation' );
  function devign_covid_nineteen_activation() {

    // Set a transient for activation admin notice
    set_transient( 'devign_covid_nineteen_activator', true, 5 );

  }