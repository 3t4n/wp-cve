<?php
class Walker_Core_Sortable_Customize_Control extends WP_Customize_Control {
    /**
     * The type of customize control being rendered.
     *
     * @access public
     * @var    string
     */
    public $type = 'gridchamp-sortable-section';
    public $option_type = 'theme_mod';
    /**
     * Enqueue scripts/styles.
     *
     * @access public
     * @return void
     */
    public function enqueue() {
        $walker_core_customzer_dir = WALKER_CORE_URL . 'admin/customizer/';
        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_style( 'sortable', $walker_core_customzer_dir . 'walkercore-sortable.css' );
        wp_enqueue_script( 'sortable', $walker_core_customzer_dir . 'walkercore-sortable.js', array('jquery', 'jquery-ui-sortable'), '', true );
    }
    /**
     * Add custom parameters to pass to the JS via JSON.
     *
     * @access public
     * @return void
     */
    public function to_json() {
        parent::to_json();

        $this->json['default'] = $this->setting->default;
        if ( isset( $this->default ) ) {
            $this->json['default'] = $this->default;
        }
        
        $this->json['value']   = maybe_unserialize( $this->value() );
        $this->json['choices'] = $this->choices;
        $this->json['link']    = $this->get_link();
        $this->json['id']      = $this->id;

        if ( 'user_meta' === $this->option_type ) {
            $this->json['value'] = get_user_meta( get_current_user_id(), $this->id, true );
        }

        $this->json['inputAttrs'] = '';
        foreach ( $this->input_attrs as $attr => $value ) {
            $this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
        }
        $this->json['inputAttrs'] = maybe_serialize( $this->input_attrs() );

    }
    /**
     * Underscore JS template to handle the control's output.
     *
     * @access public
     * @return void
     */
    public function content_template() { ?>
       <label class="gridchamp-sortable-section">

        <# if ( ! data.choices ) {
            return;
        } #>

        <# if ( data.label ) { #>
            <span class="customize-control-title">{{ data.label }}</span>
        <# } #>

        <# if ( data.description ) { #>
            <span class="description customize-control-description">{{{ data.description }}}</span>
        <# } #>

        <ul class="walkermag-sortable-list">
            <# _.each( data.value, function( choiceID ) { #>
                    <li {{{ data.inputAttrs }}} class='sortable-item' data-value='{{ choiceID }}'>
                        <i class='dashicons dashicons-fullscreen-alt'></i>
                        {{{ data.choices[ choiceID ] }}}
                    </li>
                <# }); #>
            <# _.each( data.choices, function( choiceLabel, choiceID ) { #>
                    <# if ( -1 === data.value.indexOf( choiceID ) ) { #>
                        <li {{{ data.inputAttrs }}} class='sortable-item invisible' data-value='{{ choiceID }}'>
                            <i class='dashicons dashicons-fullscreen-alt'></i>
                            {{{ data.choices[ choiceID ] }}}
                        </li>
                    <# } #>
                <# }); #>
        </ul>
        </label>
        <?php }
    }