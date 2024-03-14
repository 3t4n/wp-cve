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
		wp_enqueue_script( 'customizer_orderin_js', SFT_PLUGIN_DIR . '/inc/pro/js/customizer_ordering.js', array( 'jquery' ), '1.0', true );
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

				$globalddd_ordering = get_theme_mod( 'globalddd_ordering');
				$globalddd_ordering_arr =  explode(",",$globalddd_ordering);

                ?>
                <ul class="sortable">
                	<?php
                	if(!empty($globalddd_ordering)){
                		foreach ($globalddd_ordering_arr as $globalddd_orderingdd => $customs_value) {
                			if($customs_value == 'featured_slider_activate'){
                				$custom_title = 'Featured Slider';
                			}elseif ($customs_value == 'featured_section_info_activate') {
                				$custom_title = 'Featured Section';
                			} elseif ($customs_value == 'about_section_activate') {
                				$custom_title = 'About Section';
                			}elseif ($customs_value == 'our_portfolio_section_activate') {
                				$custom_title = 'Our Portfolio';
                			}elseif ($customs_value == 'our_services_activate') {
                				$custom_title = 'Our Services';
                			}elseif ($customs_value == 'our_team_activate') {
                				$custom_title = 'Our Team';
                			}elseif ($customs_value == 'our_testimonial_activate') {
                				$custom_title = 'Our Testimonial';
                			}elseif ($customs_value == 'our_sponsors_activate') {
                				$custom_title = 'Our Sponsors';
                			}?>
							<li class="repeater <?php echo (in_array($customs_value, $custom_diseble_arr)?'invisibility':'');?>" value="<?php echo esc_attr($customs_value)?>" id='<?php echo esc_attr($customs_value)?>'>
		                        <div class="repeater-input">
		                        	<i class='dashicons dashicons-visibility visibility'></i>
		                        	<i class='dashicons dashicons-menu'></i>
		                        	<?php echo esc_attr($custom_title); ?>
		                        </div>
		                    </li>
							<?php
						}
					}else{
						$valuechoices = $this->choices;
						foreach ($valuechoices as $key => $value) {
							?>
							<li class="repeater <?php echo (in_array($key, $custom_diseble_arr)?'invisibility':'');?>" value="<?php echo esc_attr($key)?>" id='<?php echo esc_attr($key)?>'>
		                        <div class="repeater-input">
		                        	<i class='dashicons dashicons-visibility visibility'></i>
		                        	<i class='dashicons dashicons-menu'></i>
		                        	<?php echo esc_attr($value); ?>
		                        </div>
		                    </li>
							<?php
						}	
					}								
					?>	
                </div> 
            </div>
        <?php
    }
	}
}