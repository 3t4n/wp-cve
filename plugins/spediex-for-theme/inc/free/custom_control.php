<?php
if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'hide_show_custom_ordering' ) ) {

	class hide_show_custom_ordering extends WP_Customize_Control {
	/**
	* The type of control being rendered
	*/
	public $type = 'sortable_repeater';
	/**
	* Enqueue our scripts and styles
	*/
	public function enqueue() {
		wp_enqueue_script( 'customizer_orderin_js', SFT_PLUGIN_DIR . '/inc/free/js/customizer_ordering.js', array( 'jquery' ), '1.0', true );
	}
	/**
	* Render the control in the customizer
	*/
	public function render_content() {
        ?>
          <div class="drag_and_drop_control">
                <?php if( !empty( $this->label ) ) { ?>
                	<h3 class="section-heading">
	                    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
	                </h3>
                <?php } ?>
                <?php if( !empty( $this->description ) ) { ?>
                    <span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
                <?php } ?>
                <?php
                $custom_ordering_diseble = get_theme_mod( 'custom_ordering_diseble' );
				$custom_diseble_arr =  explode(",",$custom_ordering_diseble); 

                ?>
                <ul class="sortable">
                	<?php
                	
						$valuechoices = $this->choices;
						foreach ($valuechoices as $key => $value) {
							?>
							<li class="repeater <?php echo (in_array($key, $custom_diseble_arr)?'invisibility':'');?>" value="<?php echo esc_attr($key)?>" id='<?php echo esc_attr($key)?>'>
		                        <div class="repeater-input">
		                        	<i class='dashicons dashicons-visibility visibility'></i>
		                        	<?php echo esc_attr($value); ?>
		                        </div>
		                    </li>
							<?php
					}								
					?>	
                </div> 
            </div>
        <?php
    }
	}
}