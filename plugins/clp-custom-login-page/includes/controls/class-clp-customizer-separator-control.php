<?php

class CLP_Customizer_Separator_Control extends WP_Customize_Control {

  public $type = 'separator';

  /**
  * Render Control
  *
  * @since  1.0.0
  * @access public
  * @return void
  */
  public function render_content() {
    ?>

    <div class="clp-customizer-separator">
        <h3 class="clp-customizer-separator-heading"><?php echo esc_attr( $this->label ); ?></h3>
        <?php 
        if ( $this->description ) { ?>
            <span class="clp-customizer-separator-descr"><?php echo esc_attr( $this->description ); ?></span>
            <?php 
        } ?>
      
    </div>


  <?php }


}
?>
