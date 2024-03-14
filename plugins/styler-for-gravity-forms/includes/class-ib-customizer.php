<?php
/**
 * WordPress Customizer Framework
 * Version 1.0.0
 *
 * Copyright (c) 2017 IdeaBox Creations
 */
class IBCustomizer {

    /**
	 * An array of data used to render Customizer panels.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var array $_panels
	 */
    static private $_panels = array();

    /**
	 * An array of data used to render Customizer sections.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var array $_sections
	 */
    static private $_sections = array();

    /**
     * An array of Customizer options.
     *
     * @since 1.0.0
     * @access private
     * @var array $_options
     */
    static private $_options = array();

    /**
	 * Cache for the get_theme_mods call.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var array $_mods
	 */
	static private $_mods = false;

    /**
	 * A flag for whether we're in a Customizer
	 * preview or not.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var bool $_in_customizer_preview
	 */
	static private $_is_preview = false;

    static public function init()
    {
        add_action( 'customize_preview_init',                    __CLASS__ . '::preview_init' );
        add_action( 'wp_footer',                                 __CLASS__ . '::print_preview_script', 500 );
        add_action( 'customize_controls_print_styles',           __CLASS__ . '::controls_print_styles' );
        add_action( 'customize_controls_print_footer_scripts',   __CLASS__ . '::controls_print_footer_scripts', 1000 );
        add_action( 'customize_register',                        __CLASS__ . '::register' );
    }

    /**
	 * Adds Customizer panel data to the $_panels array.
	 *
	 * @since 1.0.0
	 * @param array $data The panel data.
	 * @return void
	 */
    static public function add_panel( $data )
    {
        self::$_panels = $data;
    }

    /**
	 * Adds Customizer section data to the $_sections array.
	 *
	 * @since 1.0.0
	 * @param array $data The section data.
	 * @return void
	 */
    static public function add_section( $data )
    {
        self::$_sections = $data;
    }

    /**
	 * Called by the customize_preview_init action to initialize
	 * a Customizer preview.
	 *
	 * @since 1.0.0
	 * @return void
	 */
    static public function preview_init()
    {
        self::$_is_preview = true;
        wp_enqueue_script( 'customize-preview' );
    }

    static public function print_preview_script()
    {
        if ( self::$_is_preview ) :
        ?>
        <script>
        (function($) {

            var api = wp.customize;

            <?php
                foreach ( self::$_options as $option_key => $option_data ) {
                    if ( isset( $option_data['preview'] ) ) {
                        if ( isset( $option_data['preview']['type'] ) && 'css' == $option_data['preview']['type'] ) {
                            if ( !isset( $option_data['preview']['rules'] ) ) {
                                ?>
                                api( '<?php echo $option_key; ?>', function( value ) {
                                    value.bind( function( newval ) {
										if( 'no' === newval ) {
											$( '<?php echo $option_data['preview']['selector']; ?>' ).removeAttr('style');
										} else {
                                        	$( '<?php echo $option_data['preview']['selector']; ?>' ).css('<?php echo $option_data['preview']['property']; ?>', newval + '<?php echo isset( $option_data['preview']['unit'] ) ? $option_data['preview']['unit'] : ''; ?>' );
										}
                                    } );
                                } );
                                <?php
                            } else {
                                foreach ( $option_data['preview']['rules'] as $rule_key => $rule ) {
                                    ?>
                                    api( '<?php echo $option_key; ?>', function( value ) {
                                        value.bind( function( newval ) {
                                            <?php if ( $option_data['control']['type'] == 'ib-multitext' ) { ?>
                                                var json_data = JSON.parse(newval);
                                                $( '<?php echo $rule['selector']; ?>' ).css('<?php echo $rule['property']; ?>', json_data.<?php echo $rule_key; ?> + '<?php echo isset( $rule['unit'] ) ? $rule['unit'] : ''; ?>' );
                                            <?php } else { ?>
                                                $( '<?php echo $rule['selector']; ?>' ).css('<?php echo $rule['property']; ?>', newval + '<?php echo isset( $rule['unit'] ) ? $rule['unit'] : ''; ?>' );
                                            <?php } ?>
                                        } );
                                    } );
                                    <?php
                                }
                            }
                        }
                        if ( isset( $option_data['preview']['type'] ) && 'text' == $option_data['preview']['type'] ) {
                            ?>
                            api( '<?php echo $option_key; ?>', function( value ) {
                                value.bind( function( newval ) {
                                    $( '<?php echo $option_data['preview']['selector']; ?>' ).html( newval );
                                } );
                            } );
                            <?php
                        }
                    }
                }
            ?>
        })(jQuery);
        </script>
        <?php
        endif;
    }

    static public function controls_print_styles()
    {
        ?>
        <style type="text/css">
        .customize-control-ib-slider .ib-range-input {
            -webkit-appearance: none;
            -webkit-transition: background .3s;
            -moz-transition: background .3s;
            transition: background .3s;
            background-color: rgba(0,0,0,.1);
            height: 5px;
            width: calc(100% - 74px);
            padding: 0;
            border-radius: 10px;
        }
        .customize-control-ib-slider .ib-range-input:hover {
            background-color: rgba(0,0,0,.25);
        }
        .customize-control-ib-slider .ib-range-input:focus {
            box-shadow: none;
            outline: none;
        }
        .customize-control-ib-slider .ib-range-input::-webkit-slider-thumb {
        	-webkit-appearance: none;
        	width: 15px;
        	height: 15px;
        	border-radius: 50%;
        	-webkit-border-radius: 50%;
        	background-color: #008ec2;
        }
        .customize-control-ib-slider .ib-range-input::-moz-range-thumb {
        	width: 15px;
        	height: 15px;
        	border: none;
        	border-radius: 50%;
        	background-color: #008ec2;
        }
        .customize-control-ib-slider .ib-range-input::-ms-thumb {
        	width: 15px;
        	height: 15px;
        	border-radius: 50%;
        	border: 0;
        	background-color: #008ec2;
        }
        .customize-control-ib-slider .ib-range-input::-moz-range-track {
        	border: inherit;
        	background: transparent;
        }
        .customize-control-ib-slider .ib-range-input::-ms-track {
        	border: inherit;
        	color: transparent;
        	background: transparent;
        }
        .customize-control-ib-slider .ib-range-input::-ms-fill-lower,
        .customize-control-ib-slider .ib-range-input::-ms-fill-upper {
        	background: transparent;
        }
        .customize-control-ib-slider .ib-range-input::-ms-tooltip {
        	display: none;
        }
        .customize-control-ib-slider .ib-range-value {
            display: inline-block;
            padding: 0 5px;
            position: relative;
            top: 1px;
        }
        .customize-control-ib-slider input#ib-range-value-input {
            width: 42px;
            height: 23px;
            font-size: 13px;
        }
        .customize-control-ib-slider .ib-slider-reset {
            color: rgba(0,0,0,.2);
            float: right;
            -webkit-transition: color .5s ease-in;
            -moz-transition: color .5s ease-in;
            -ms-transition: color .5s ease-in;
            -o-transition: color .5s ease-in;
            transition: color .5s ease-in;
        }
        .customize-control-ib-slider .ib-slider-reset span {
            font-size: 16px;
            line-height: 22px;
            cursor: pointer;
        }
        .customize-control-ib-multitext .ib-field {
            float: left;
            width: 24%;
            margin-bottom: 5px;
            margin-right: 2px;
        }
        .customize-control-ib-multitext .ib-field:nth-of-type(2n) {
        }
        .customize-control-ib-multitext .ib-field-label {
            font-size: 11px;
        }
        </style>
        <?php
    }

    static public function controls_print_footer_scripts()
    {
        ?>
        <script type="text/javascript">
        (function($) {

            var api = wp.customize;

            <?php
                $toggles = array();
                foreach ( self::$_options as $option_key => $option_data ) {
                    if ( isset( $option_data['toggle'] ) ) {
                        foreach ( $option_data['toggle'] as $toggle_key => $toggle_data ) {
                            $toggles[$option_key][] = array(
                                'controls'  => $toggle_data,
                                'value'     => $toggle_key
                            );
                        }
                    }
                }
            ?>

            var IBCustomizerToggles = <?php echo json_encode($toggles); ?>;

            /**
        	 * Helper class for the main Customizer interface.
        	 *
        	 * @since 1.0.0
        	 * @class IBCustomizer
        	 */
            IBCustomizer = {
                /**
        		 * Initializes our custom logic for the Customizer.
        		 *
        		 * @since 1.0.0
        		 * @method init
        		 */
        		init: function()
        		{
        			IBCustomizer._initToggles();
                    IBCustomizer._initControls();
        		},

                /**
        		 * Initializes the logic for showing and hiding controls
        		 * when a setting changes.
        		 *
        		 * @since 1.0.0
        		 * @access private
        		 * @method _initToggles
        		 */
        		_initToggles: function()
        		{
                    if ( Object.keys( IBCustomizerToggles ).length < 1 ) {
                        return;
                    }
        			// Loop through each setting.
        			$.each(IBCustomizerToggles, function( settingId, toggles ) {

        				// Get the setting object.
        				api( settingId, function( setting ) {

        					// Loop though the toggles for the setting.
        					$.each( toggles, function( i, toggle ) {

        						// Loop through the controls for the toggle.
        						$.each( toggle.controls, function( k, controlId ) {

        							// Get the control object.
        							api.control( controlId, function( control ) {

        								// Define the visibility callback.
        								var visibility = function( to ) {
        									control.container.toggle( IBCustomizer._matchValues( to, toggle.value ) );
        								};

        								// Init visibility.
        								visibility( setting.get() );

        								// Bind the visibility callback to the setting.
        								setting.bind( visibility );
        							});
        						});
        					});
        				});
        			});
        		},

                _initControls: function()
                {
                    // Initialize the slider control.
                    api.controlConstructor['ib-slider'] = api.Control.extend({
                        ready: function() {
                            IBCustomizer._initSliderControl();
                        }
                    });

                    // Initialize the multitext control.
                    api.controlConstructor['ib-multitext'] = api.Control.extend({
                        ready: function() {
                            IBCustomizer._initMultitextControl();
                        }
                    });
                },

                /**
        		 * Initializes the slider control.
        		 *
        		 * @since 1.0.0
        		 * @method _initSliderControl
        		 */
        		_initSliderControl: function()
        		{
                    $( '.customize-control-ib-slider .ib-slider-reset' ).on('click', function () {
                        var $slider       = $( this ).closest( 'label' ).find( '.ib-range-input' ),
                            $text_input   = $( this ).closest( 'label' ).find( '#ib-range-value-input' );
                            default_value = $slider.data( 'original' );

                        $slider.val( default_value );
                        $slider.change();
                        $text_input.val( default_value );
                    });

                    $( '.customize-control-ib-slider .ib-range-input' ).each(function() {
                        var $slider     = $(this),
                            $text_input = $( this ).closest( 'label' ).find( '#ib-range-value-input' );
                            value       = $slider.attr( 'value' );

                        $slider.on('input', function () {
                            value = $slider.attr( 'value' );
                            $text_input.val( value );
                        });

                        $text_input.on('keyup change', function(){
                            $slider.val($text_input.val());
                            $slider.change();
                        });

                    });
                },

                _initMultitextControl: function()
                {
                    $( '.customize-control-ib-multitext .ib-field input' ).on('keyup change', function(){
                        var $multitext  = $( this ).closest( 'label' ).find( '.ib-multitext-value' ),
                            value       = $multitext.data('value'),
                            choice      = $(this).data('key');

                        value[choice] = $(this).val();

                        $multitext.val( JSON.stringify(value) );
                        $multitext.trigger('change');
                    });

                    $( '.customize-control-ib-multitext .ib-multitext-value' ).each(function () {
                        var $multitext  = $(this),
                            value       = $multitext.data('value');

                        $multitext.val( JSON.stringify(value) );
                    });
                },

                /**
        		 * Match two values for toggle and return boolean.
        		 *
        		 * @since 1.0.0
        		 * @access private
        		 * @method _matchValues
        		 */
                _matchValues: function( val1, val2 )
                {
                    return val1 === val2;
                }
            }

            IBCustomizer.init();



        })(jQuery);
        </script>
        <?php
    }

    static public function register( $customizer )
    {
        require_once GFS_DIR . 'includes/class-ib-customizer-controls.php';

        $panel_priority = 1;

        foreach ( self::$_panels as $panel_key => $panel_data ) {

            $customizer->add_panel( $panel_key, array(
				'title'    => $panel_data['title'],
				'priority' => isset( $panel_data['priority'] ) ? $panel_data['priority'] : $panel_priority
			));

            $panel_priority++;

            if ( isset( $panel_data['sections'] ) ) {
                self::_register_sections( $panel_key, $panel_data['sections'], $customizer );
            }
        }

        if ( count( self::$_sections ) ) {
            self::_register_sections( '', self::$_sections, $customizer );
        }
    }

    static private function _register_sections( $panel_key = '', $data, $customizer )
    {
        $section_priority   = 1;
		$option_priority    = 1;

        foreach ( $data as $section_key => $section_data ) {

            // Make sure this section should be registered.
            if ( isset( $section_data['disable'] ) && true === $section_data['disable'] ) {
                continue;
            }

            $section = array(
                'title'    => $section_data['title'],
                'priority' => isset( $section_data['priority'] ) ? $section_data['priority'] : $section_priority
            );

            if ( !empty( $panel_key ) ) {
                $section['panel'] = $panel_key;
            }
            if ( isset( $section_data['description'] ) ) {
                $section['description'] = $section_data['description'];
            }

            $customizer->add_section( $section_key, $section );

            $section_priority++;

            if ( isset( $section_data['fields'] ) ) {

                foreach ( $section_data['fields'] as $option_key => $option_data ) {

                    self::$_options[$option_key] = $option_data;

                    // Add setting
                    if ( ! isset( $option_data['setting'] ) ) {
                        $option_data['setting'] = array( 'default' => '' );
                    } else {
                        if ( !isset( $option_data['setting']['transport'] ) && isset( $option_data['preview'] ) ) {
                            $option_data['setting']['transport'] = 'postMessage';
                        }
                    }

                    $customizer->add_setting( $option_key, $option_data['setting'] );

                    // Add control
                    $option_data['control']['section']  = $section_key;
                    $option_data['control']['settings'] = $option_key;
                    $option_data['control']['priority'] = $option_priority;

                    if ( isset( $option_data['control']['options'] ) && !isset( $option_data['control']['choices'] ) ) {
                        $option_data['control']['choices'] = $option_data['control']['options'];
                    }

                    if ( !isset( $option_data['control']['class'] ) ) {
                        $option_data['control']['class'] = 'WP_Customize_Control';
                    }

                    $customizer->add_control(
                        new $option_data['control']['class']( $customizer, $option_key, $option_data['control'] )
                    );

                    // Increment option priority
                    $option_priority++;
                }

                // Reset option priority
                $option_priority = 0;
            }
        }
        // Reset section priority
        $section_priority = 0;
    }

    /**
	 * Called by the customize_save_after action to refresh
	 * the cached CSS when Customizer settings are saved.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	static public function save( $customizer )
	{

	}

    /**
	 * Returns the Customizer setting
	 *
	 * @since 1.0.0
	 * @return mixed
	 */
	static public function get_mod( $setting_key, $multitext = false )
	{
        if ( empty( $setting_key ) ) {
            return;
        }

		$value = get_theme_mod( $setting_key );

        if ( $multitext && !is_array( $value ) ) {
            $value = json_decode( $value, true );
        }

        return apply_filters( 'ib_customizer_' . $setting_key, $value );
	}

    /**
	 * Checks to see if this is a Customizer preview or not.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	static public function is_customizer_preview()
	{
		return self::$_is_preview;
	}

	/**
	 * Sanitize callback for Customizer number field.
	 *
	 * @since 1.0.0
	 * @return int
	 */
	static public function sanitize_number( $val )
	{
		return is_numeric( $val ) ? $val : 0;
	}

    /**
	 * Sanitize callback for Customizer email field.
	 *
	 * @since 1.0.0
	 * @return int
	 */
    static public function sanitize_email( $val )
    {
        return filter_var( $val, FILTER_VALIDATE_EMAIL );
    }

}
IBCustomizer::init();
