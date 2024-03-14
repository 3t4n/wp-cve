<?php
echo '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";

// Get settings fields
ob_start();
settings_fields( $this->_text_domain . '_settings' );
do_settings_sections( $this->_text_domain . '_settings' );
echo ob_get_clean();

echo '<p class="submit">' . "\n";
echo '<input type="hidden" name="tab" value="' . esc_attr( $tab ) . '" />' . "\n";
echo '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save' , 'beam-me-up-scotty' ) ) . '" />' . "\n";
echo '</p>' . "\n";
echo '</form>' . "\n";
?>
