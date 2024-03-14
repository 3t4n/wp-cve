<?php
defined( 'ABSPATH' ) || exit;
/**
 *
 * Elementor widget
 *
 */
class Elementor_Oraclecards_Widget extends \Elementor\Widget_Base {
	public function get_name() {
		return 'oracle_cards';
	}
	public function get_title() {
		return __( 'Oracle Cards', 'oracle-cards' );
	}

	public function get_icon() {
		return 'fa fa-square';
	}

	public function get_categories() {
		return array( 'general' );
	}

  protected function _register_controls() {
    $opts = eos_cards_get_options();
  	if( false === $opts ) return;
  	$decks = array( 'none' => __( '--Select--','oracle-cards' ) );
  	foreach( $opts as $t_id => $term ){
  		$decks[absint( $t_id )] = esc_html( $term['name'] );
  	}
		$this->start_controls_section(
			'content_section',array(
				'label' => __( 'Content', 'oracle-cards' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'deck',
			array(
				'label' =>  __( 'Deck', 'oracle-cards' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'input_type' => 'select',
				'options' => $decks,
        'default' => 'none'
			)
		);
		$this->add_control(
			'deck_type',
			array(
				'label' =>  __( 'Deck Type', 'oracle-cards' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'input_type' => 'select',
				'options' => array( 'folding_fan' => __( "Folding fan",'oracle-cards' ) ,'deck' => __( "Deck",'oracle-cards' ) ),
        'default' => 'folding_fan'
			)
		);
		$this->add_control(
			'button_text_pick',
			array(
				'label' => __( 'Button text for picking a card', 'oracle-cards' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Pick your card','oracle-cards' ),
				'default' => __( 'Pick your card','oracle-cards' )
			)
		);
		$this->add_control(
			'button_text_mix',
			array(
				'label' => __( 'Button text for mixing cards', 'oracle-cards' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Mix the cards','oracle-cards' ),
				'default' => __( 'Mix the cards','oracle-cards' )
			)
		);
    $this->add_control(
			'show_title',
			array(
				'label' =>  __( 'Show cart title', 'oracle-cards' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'input_type' => 'select',
				'options' => array( 'false' => __( "Don't show title",'oracle-cards' ) ,'true' => __( "Show title",'oracle-cards' ) ),
        'default' => 'false'
			)
		);
    $this->add_control(
			'button_text_mix',
			array(
				'label' => __( 'Button text for mixing cards', 'oracle-cards' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Mix the cards','oracle-cards' ),
			)
		);
		if( defined( 'EOS_CARDS_PRO' ) && EOS_CARDS_PRO ){
	    $this->add_control(
				'custom_back_image',
				array(
					'label' => __( 'Deck back', 'oracle-cards' ),
					'type' => \Elementor\Controls_Manager::MEDIA,
	        'dynamic' => [
						'active' => true,
					],
				)
			);
		}
    $this->add_control(
			'space_top',
			array(
				'label' => __( 'Space before the deck', 'oracle-cards' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
        'default' => 20
			)
		);
    $this->add_control(
			'space_top_text',
			array(
				'label' => __( 'Space before the text', 'oracle-cards' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
        'default' => 20
			)
		);
    $this->add_control(
			'space_top_button',
			array(
				'label' => __( 'Space before the button', 'oracle-cards' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
        'default' => 20
			)
		);
    $this->add_control(
			'deck_from',
			array(
				'label' => __( 'Deck layout from', 'oracle-cards' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
        'default' => 930
			)
		);
    $this->add_control(
			'animation_distance',
			array(
				'label' => __( 'Animation distance', 'oracle-cards' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
        'default' => 200
			)
		);
    $this->add_control(
			'maxnumber',
			array(
				'label' => __( 'Max number of cards', 'oracle-cards' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
        'default' => 100
			)
		);
    $this->add_control(
			'distance',
			array(
				'label' => __( 'Cards distance', 'oracle-cards' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
        'default' => 2
			)
		);
    $this->add_control(
			'maxrand',
			array(
				'label' => __( 'Level of randomness', 'oracle-cards' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
        'default' => 100
			)
		);
    $this->add_control(
			'maxmargin',
			array(
				'label' => __( 'Maximum space (px)', 'oracle-cards' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
        'default' => 400
			)
		);
    $this->add_control(
			'back_border',
			array(
				'label' =>  __( 'Border', 'oracle-cards' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'input_type' => 'select',
				'options' => array( 'no' => __( 'Back without border','oracle-cards' ) ,'yes' => __( 'Back with border','oracle-cards' ) ),
        'default' => 'no'
			)
		);
    $this->add_control(
			'back_border_color',
			array(
				'label' => __( 'Border color', 'oracle-cards' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'separator' => 'none'
      )
    );
    $this->add_control(
			'border_radius',
			array(
				'label' => __( 'Border radius (px)', 'oracle-cards' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'input_type' => 'text',
        'default' => ''
			)
		);
    $this->add_control(
			'on_mobile',
			array(
				'label' =>  __( 'Visibility on mobile', 'oracle-cards' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'input_type' => 'select',
				'options' => array(
          'show' => __( "Show on mobile",'oracle-cards' ) ,
          'hide' => __( "Hide on mobile",'oracle-cards' ),
          'remove' => __( "Remove on mobile",'oracle-cards' ),
         ),
        'default' => 'show'
			)
		);
    $this->add_control(
			'class',
			array(
				'label' => __( 'Class name', 'oracle-cards' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'input_type' => 'text',
			)
		);

		$this->end_controls_section();

	}

	protected function render() {
    $atts = $this->get_settings_for_display();
		$atts['custom_back_id'] = $atts['custom_back_image']['id'];
		if( 'deck' === $atts['deck_type'] ){
			$atts['deck_from'] = 999999999;
		}
		echo eos_cards_oracle_cards( $atts );
	}
}
