<?php

// Function for learning how to add options
// SQL Query: SELECT * FROM wp_options WHERE option_name = "wpplugin_option";
function pin_generator_options()
{
  // Only add these option if they havnt been added before
  if (!get_option("pin_generator_access_key")) {
    add_option("pin_generator_access_key", "");
  }

  $design_options = [];
  $design_options['template'] = 'RandomTemplate';
  $design_options['color'] = '#27c3a6';
  $design_options['attribution'] = false; // false to default off or "1" (including quotes) to default on
  

  if (!get_option("pin_generator_design_settings")) {
    add_option("pin_generator_design_settings", $design_options);
  }

  // To remove this before release, this is here to reset the design settings on each load
  //update_option("pin_generator_design_settings", $design_options);
}
add_action("admin_init", "pin_generator_options");
 
