<?php
if ( class_exists( 'WP_Customize_Control' ) ) {
    /**
     * Custom Customizer controls.
     *
     * @since 1.0.0
     */
    final class IBCustomizerControl extends WP_Customize_Control {

    	/**
    	 * Used to connect controls to each other.
    	 *
    	 * @since 1.0.0
    	 * @var bool $connect
    	 */
    	public $connect = false;

    	/**
    	 * If true, the preview button for a control will be rendered.
    	 *
    	 * @since 1.0.0
    	 * @var bool $preview_button
    	 */
    	public $preview_button = false;

    	/**
    	 * Renders the content for a control based on the type
    	 * of control specified when this class is initialized.
    	 *
    	 * @since 1.0.0
    	 * @access protected
    	 * @return void
    	 */
    	protected function render_content()
    	{
    		switch($this->type) {

    			case 'ib-line':
    			$this->render_line();
    			break;

    			case 'ib-slider':
    			$this->render_slider();
    			break;

                case 'ib-multitext':
    			$this->render_multitext();
    			break;
    		}
    	}

    	/**
    	 * Renders the title and description for a control.
    	 *
    	 * @since 1.0.0
    	 * @access protected
    	 * @return void
    	 */
    	protected function render_content_title()
    	{
    		if(!empty($this->label)) {
    			echo '<span class="customize-control-title">' . esc_html($this->label) . '</span>';
    		}
    		if(!empty($this->description)) {
    			echo '<span class="description customize-control-description">' . $this->description . '</span>';
    		}
    	}

    	/**
    	 * Renders the connect attribute for a connected control.
    	 *
    	 * @since 1.0.0
    	 * @access protected
    	 * @return void
    	 */
    	protected function render_connect_attribute()
    	{
    		if ( $this->connect ) {
    			echo ' data-connected-control="'. $this->connect .'"';
    		}
    	}

    	/**
    	 * Renders a line break control.
    	 *
    	 * @since 1.0.0
    	 * @access protected
    	 * @return void
    	 */
    	protected function render_line()
    	{
    		echo '<hr />';
    	}

    	/**
    	 * Renders the slider control.
    	 *
    	 * @since 1.0.0
    	 * @access protected
    	 * @return void
    	 */
    	protected function render_slider()
    	{
    		$this->choices['min']   = ( isset( $this->choices['min'] ) )   ? $this->choices['min']   : '0';
    		$this->choices['max']   = ( isset( $this->choices['max'] ) )   ? $this->choices['max']   : '100';
    		$this->choices['step']  = ( isset( $this->choices['step'] ) )  ? $this->choices['step']  : '1';

    		echo '<label>';
    		$this->render_content_title();
    		echo '<div class="wrapper">';
    		echo '<input class="ib-range-input" type="range" min="' . $this->choices['min'] . '" max="' . $this->choices['max'] . '" step="' . $this->choices['step'] . '" value="' . $this->value() . '"';
    		$this->link();
    		echo 'data-original="' . $this->settings['default']->default . '">';
    		echo '<div class="ib-range-value">';
    		echo '<input type="text" id="ib-range-value-input" value="' . $this->value() . '">';
    		echo '</div>';
    		echo '<div class="ib-slider-reset">';
    		echo '<span class="dashicons dashicons-image-rotate"></span>';
    		echo '</div>';
    		echo '</div>';
    		echo '</label>';
    	}

        /**
         * Renders the multitext control.
         *
         * @since 1.0.0
         * @access protected
         * @return void
         */
        protected function render_multitext()
        {
            $value = is_array($this->value()) ? json_encode( $this->value() ) : json_decode( $this->value(), true );
            $value = is_array($value) ? json_encode($value) : $value;
            $field_value = is_array($this->value()) ? $this->value() : json_decode($this->value(), true);
            ?>
            <label>
    		    <?php $this->render_content_title(); ?>
        		<div class="wrapper">
                    <?php foreach ( $this->choices as $key => $label ) { ?>
                        <div class="ib-field">
                            <span class="ib-field-label"><?php echo $label ?></span>
            		        <input type="text" data-key="<?php echo $key; ?>" value="<?php echo isset( $field_value[$key] ) ? $field_value[$key] : ''; ?>" />
                        </div>
                    <?php } ?>
                    <input type="hidden" class="ib-multitext-value" value='<?php echo $value; ?>' data-value='<?php echo $value; ?>' <?php echo $this->get_link(); ?> />
        		</div>
    		</label>
            <?php
        }
    }
}
