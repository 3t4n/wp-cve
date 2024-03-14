<?php
class Elementor_DM_Code_Snippet extends \Elementor\Widget_Base {

	public function get_name() {
		return 'code_snippet_dm';
	}

	public function get_title() {
		return esc_html__( 'Code Snippet DM', 'code-snippet-dm' );
	}

	public function get_icon() {
		return 'eicon-code';
	}

	public function get_categories() {
		return [ 'basic' ];
	}

	public function get_keywords() {
		return [ 'code', 'snippet', 'dm' ];
	}

    	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {

        $this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Content', 'code-snippet-dm' ),
			)
		);

        $this->add_control(
            'theme',
            array(
                'label'   => __( 'Theme', 'code-snippet-dm' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'dark',
                'options' => array(
                    'dark'  => __( 'Dark', 'code-snippet-dm' ),
                    'light' => __( 'Light', 'code-snippet-dm' ),
                ),
            )
        );

        $this->add_control(
            'slim_version',
            array(
                'label'   => __( 'Slim Version', 'code-snippet-dm' ),
                'description' => __( 'This is recommended for one line code. No BG, no extra style, just the code.', 'code-snippet-dm' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'no',
                'options' => array(
                    'yes' => __( 'Yes', 'code-snippet-dm' ),
                    'no'  => __( 'No', 'code-snippet-dm' ),
                ),
            )
        );

        $this->add_control(
            'line_numbers',
            array(
                'label'   => __( 'Line Numbers', 'code-snippet-dm' ),
                'description' => __( 'This will allow you to enable the Line Numbers column.', 'code-snippet-dm' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'no',
                'options' => array(
                    'yes' => __( 'Yes', 'code-snippet-dm' ),
                    'no'  => __( 'No', 'code-snippet-dm' ),
                ),
            )
        );

        $this->add_control(
            'background',
            array(
                'label'   => __( 'Enable Background', 'code-snippet-dm' ),
                'description' => __( 'Enable background color from below to show around the code area.', 'code-snippet-dm' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'yes',
                'options' => array(
                    'yes' => __( 'Yes', 'code-snippet-dm' ),
                    'no'  => __( 'No', 'code-snippet-dm' ),
                ),
            )
        );

        $this->add_control(
            'background_color',
            array(
                'label'   => __( 'Background Color', 'code-snippet-dm' ),
                'type'    => \Elementor\Controls_Manager::COLOR,
                'default' => '#abb8c3',
            )
        );

        $this->add_control(
            'background_mobile',
            array(
                'label'   => __( 'Enable Background on Mobile', 'code-snippet-dm' ),
                'description' => __( 'Enable background color from below to show around the code area on mobile devices.', 'code-snippet-dm' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'yes',
                'options' => array(
                    'yes' => __( 'Yes', 'code-snippet-dm' ),
                    'no'  => __( 'No', 'code-snippet-dm' ),
                ),
            )
        );

        $this->add_control(
			'code',
			array(
				'label'   => __( 'Code', 'code-snippet-dm' ),
				'type'    => \Elementor\Controls_Manager::CODE,
                'rows'    => '20'
			)
		);

        $this->add_control(
            'language',
            array(
                'label'   => __( 'Language', 'code-snippet-dm' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'php',
                'options' => array(
                    'clike' => __( 'C-Like', 'code-snippet-dm' ),
                    'css'  => __( 'CSS', 'code-snippet-dm' ),
                    'markup'  => __( 'HTML/Markup', 'code-snippet-dm' ),
                    'javascript'  => __( 'JavaScript', 'code-snippet-dm' ),
                    'perl'  => __( 'Perl', 'code-snippet-dm' ),
                    'php'  => __( 'PHP', 'code-snippet-dm' ),
                    'python'  => __( 'Python', 'code-snippet-dm' ),
                    'ruby'  => __( 'Ruby', 'code-snippet-dm' ),
                    'sql'  => __( 'SQL', 'code-snippet-dm' ),
                    'typescript'  => __( 'TypeScript', 'code-snippet-dm' ),
                    'shell'  => __( 'Bash/Shell', 'code-snippet-dm' ),
                ),
            )
        );

        $this->add_control(
            'wrapped',
            array(
                'label'   => __( 'Wrap Code', 'code-snippet-dm' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'default' => 'no',
                'options' => array(
                    'yes' => __( 'Yes', 'code-snippet-dm' ),
                    'no'  => __( 'No', 'code-snippet-dm' ),
                ),
            )
        );

        $this->add_control(
			'maxheight',
			array(
				'label'   => __( 'Max Height', 'code-snippet-dm' ),
                'description' => __( 'Set the max height for the code snippet. Supports any unit.', 'code-snippet-dm' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( '300px', 'code-snippet-dm' ),
			)
		);

        $this->add_control(
			'copycode',
			array(
				'label'   => __( 'Copy Text', 'code-snippet-dm' ),
                'description' => __( 'Add text on the Copy Button.', 'code-snippet-dm' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Copy Text', 'code-snippet-dm' ),
			)
		);

        $this->add_control(
			'copiedcode',
			array(
				'label'   => __( 'After Copy Text', 'code-snippet-dm' ),
                'description' => __( 'Text displayed after clicking the Copy Button.', 'code-snippet-dm' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Copied', 'code-snippet-dm' ),
			)
		);



		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

        $background 		= '';
        $background_mobile 	= '';
        $slim               = '';
		$wrap 				= '';
        

        if ( 'yes' == $settings['background'] ) {
            $background = 'default';
            } else {
            $background = 'no-background';
		}

		if ( 'yes' == $settings['background_mobile'] ) {
            $background_mobile = '';
        } else {
            $background_mobile = 'no-background_mobile';
		}

		if ( 'yes' == $settings['wrapped'] ) {
            $wrap = 'wrap';
        } else {
            $wrap = 'no-wrap';
        }
        
        if ( 'yes' == $settings['slim_version'] ) {
            $slim = 'dm-slim-version';
        } else {
            $slim = 'dm-normal-version';
        }

        if ( 'yes' == $settings['line_numbers'] ) {
            $line_numbers = 'line-numbers';
        } else {
            $line_numbers = 'no-line-numbers';
        }
		?>

        <div class="dm-code-snippet <?php echo $settings['theme']; ?> <?php echo $background; ?> <?php echo $background_mobile; ?> <?php echo $slim; ?>" style="background-color: <?php echo $settings['background_color']; ?>;" snippet-height="<?php echo $settings['maxheight']; ?>">
			<div class="control-language">
                <div class="dm-buttons">
                    <div class="dm-buttons-left">
                        <div class="dm-button-snippet red-button"></div>
                        <div class="dm-button-snippet orange-button"></div>
                        <div class="dm-button-snippet green-button"></div>
                    </div>
                    <div class="dm-buttons-right">
                        <a id="dm-copy-raw-code">
                        <span class="dm-copy-text"><?php echo $settings['copycode']; ?></span>
                        <span class="dm-copy-confirmed" style="display:none"><?php echo $settings['copiedcode']; ?></span>
                        <span class="dm-error-message" style="display:none">Use a different Browser</span></a>
                    </div>
                </div>
                <pre class="<?php echo $line_numbers; ?>"><code id="dm-code-raw" class="<?php echo $wrap; ?> language-<?php echo $settings['language']; ?>"><?php echo esc_html( $settings['code'] ); ?></code></pre>
			</div>
        </div>

		<?php
	}


    	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<#
        console.log (settings.maxheight);
        if ( settings.background === 'yes') {
            var background = 'default';
        } else {
            var background = 'no-background';
        }

        if ( settings.background_mobile === 'yes') {
            var background_mobile = '';
        } else {
            var background_mobile = 'no-background_mobile';
        }

        if ( settings.wrapped === 'yes') {
            var wrap = 'wrap';
        } else {
            var wrap = 'no-wrap';
        }

        if ( settings.slim_version === 'yes') {
            var slim = 'dm-slim-version';
        } else {
            var slim = 'dm-normal-version';
        }

        if ( settings.line_numbers === 'yes') {
            var line_numbers = 'line-numbers';
        } else {
            var line_numbers = 'no-line-numbers';
        }
		#>

        <div class="dm-code-snippet {{ settings.theme }} {{ background }} {{ background_mobile }} {{ slim }}" style="background-color: {{ settings.background_color }};" snippet-height="{{ settings.maxheight }}">
			<div class="control-language">
                <div class="dm-buttons">
                    <div class="dm-buttons-left">
                        <div class="dm-button-snippet red-button"></div>
                        <div class="dm-button-snippet orange-button"></div>
                        <div class="dm-button-snippet green-button"></div>
                    </div>
                    <div class="dm-buttons-right">
                        <a id="dm-copy-raw-code">
                        <span class="dm-copy-text">{{ settings.copycode }}</span>
                        <span class="dm-copy-confirmed" style="display:none">{{ settings.copiedcode }}</span>
                        <span class="dm-error-message" style="display:none">Use a different Browser</span></a>
                    </div>
                </div>
                <pre class="{{ line_numbers }}"><code id="dm-code-raw" class="{{ wrap }} language-{{ settings.language }}">{{ settings.code }}</code></pre>
			</div>
        </div>


		<?php
	}
}
