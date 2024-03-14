<?php


use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Plugin;

class wps_tab extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'wps_tab';
    }

    public function get_title()
    {
        return __('Tabs', 'wpsection');
    }

    public function get_icon()
    {
        return 'eicon-tabs';
    }

    public function get_keywords()
    {
        return ['wps', 'tab'];
    }

    public function get_categories()
    {
     return ['wpsection_category'];
    }



    protected function register_controls()
    {
        $this->start_controls_section(
            'wps_tab',
            [
                'label' => esc_html__('Tabs Genarel Settings', 'wpsection'),
            ]
        );
		

		

		
		
 
 $this->add_control(
        'style',
        [
            'label'   => esc_html__( 'Choose Style', 'rashid' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'style-1',
            'options' => array(
                'style-1'   => esc_html__( 'Classic Style', 'rashid' ),
                'style-2'   => esc_html__( 'Metro Style', 'rashid' ),
            
            ),
        ]
    );	
		
  $this->add_control(
            'wps_tab_container_width',
            [
                'label' => esc_html__( 'Section Width ', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 3000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1320,
                ],
                'selectors' => [
                    '{{WRAPPER}} .auto-container' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );   		
		

 

        

        $this->add_control(
            'wps_columns',
            array(
                'label' => __('Columns Settings', 'wpsection'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '1'  => __('1 Column', 'wpsection'),
                    '2' => __('2 Columns', 'wpsection'),
                    '3' => __('3 Columns', 'wpsection'),
                    '4' => __('4 Columns', 'wpsection'),
					'5' => __('5 Columns', 'wpsection'),
                    '6' => __('6 Columns', 'wpsection'),
					'7' => __('7 Columns', 'wpsection'),
					'8' => __('8 Columns', 'wpsection'),
					'9' => __('9 Columns', 'wpsection'),
					'10' => __('10 Columns', 'wpsection'),
                    '12' => __('12 Columns', 'wpsection'),

                ],
            )
        );
        $this->add_control(
            'wps_columns_tab',
            array(
                'label' => __('Tab Columns Settings', 'wpsection'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
               'options' => [
                    '1'  => __('1 Column', 'wpsection'),
                    '2' => __('2 Columns', 'wpsection'),
                    '3' => __('3 Columns', 'wpsection'),
                    '4' => __('4 Columns', 'wpsection'),
					'5' => __('5 Columns', 'wpsection'),
                    '6' => __('6 Columns', 'wpsection'),
					'7' => __('7 Columns', 'wpsection'),
					'8' => __('8 Columns', 'wpsection'),
					'9' => __('9 Columns', 'wpsection'),
					'10' => __('10 Columns', 'wpsection'),
                    '12' => __('12 Columns', 'wpsection'),

                ],
            )
        );
		
	    $this->add_control(
        'text_box_style',
        [
            'label'   => esc_html__( 'Text Box Style', 'rashid' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'style-1',
            'options' => array(
                'style-1'   => esc_html__( 'InSide Image', 'rashid' ),
                'style-2'   => esc_html__( 'OutSide Image', 'rashid' ),
            
            ),
        ]
    );
  $this->add_control(
        'text_title_style',
        [
            'label'   => esc_html__( 'Title Order Style', 'rashid' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'style-1',
            'options' => array(
                'style-1'   => esc_html__( 'Title Top ', 'rashid' ),
                'style-2'   => esc_html__( 'Title Bottom', 'rashid' ),
            
            ),
        ]
    );
	
	  	


  $this->add_control(
    'block_link_icon',
    [
        'label' => esc_html__('Link Icon', 'rashid'),
        'type' => Controls_Manager::ICONS,
        'default' => [
            'value' => 'eicon-editor-external-link', // Set your default icon class here
            'library' => 'solid', // Set the icon library (solid, regular, or brands)
        ],
    ]
);


  $this->add_control(
    'block_plus_icon',
    [
        'label' => esc_html__('Light Box Icon', 'rashid'),
        'type' => Controls_Manager::ICONS,
        'default' => [
            'value' => 'eicon-lightbox-expand', // Set your default icon class here
            'library' => 'solid', // Set the icon library (solid, regular, or brands)
        ],
    ]
);
		
    $this->add_control(
        'enable_slide',
        [
            'label' => esc_html__('Enable Slide', 'your-text-domain'),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes', // Set the default value
            'label_on' => esc_html__('Yes', 'wpsection'),
            'label_off' => esc_html__('No', 'wpsection'),
        ]
    );		
		

$this->end_controls_section();  



$this->start_controls_section(
                    'product_tab_repeter',
                    [
                        'label' => __( 'Repeter Settings', 'wpsection' ),
                        'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                    ]
                ); 



        $repeater = new Repeater();


//Image One

$repeater->add_control(
    'tab_block_title_one',
    [
        'label' => esc_html__('Tab Title', 'wpsection'),
        'type' => Controls_Manager::TEXT,
        'default' => esc_html__('Tab One', 'wpsection'),
    ]
);


  $repeater->add_control(
    'tab_settings_one',
    [
        'label' => esc_html__('Enable Block One', 'wpsection'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'default' => 'yes', // Set the default value
        'label_on' => esc_html__('Yes', 'wpsection'),
        'label_off' => esc_html__('No', 'wpsection'),
    ]
);


        

  
$repeater->add_control(
    'block_image_one',
    [
        'label' => __('Image', 'wpsection'),
        'condition' => ['tab_settings_one' => 'yes'],
        'type' => Controls_Manager::MEDIA,
       
    ]
);


        $repeater->add_control(
            'block_subtitle_one',
            [
                'label' => esc_html__('Subtitle', 'wpsection'),
                'condition' => ['tab_settings_one' => 'yes'],
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Image Description', 'wpsection'),
            ]
        );
        $repeater->add_control(
            'block_title_one',
            [
                'label' => esc_html__('Title', 'wpsection'),
                'condition' => ['tab_settings_one' => 'yes'],
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Gallery Image', 'wpsection'),
            ]
        );
        $repeater->add_control(
            'block_btnlink_x_one',
            [
                'label' => __('Button Url', 'wpsection'),
                'condition' => ['tab_settings_one' => 'yes'],
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'wpsection'),
                'show_external' => true,
                'default' => [
                'url' => '',
                'is_external' => true,
                'nofollow' => true,
                        ],
                'separator' => 'after',               
            ]
        );

//Image Two


  $repeater->add_control(
    'tab_settings_two',
    [
        'label' => esc_html__('Enable Block Two', 'wpsection'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'default' => 'yes', // Set the default value
        'label_on' => esc_html__('Yes', 'wpsection'),
        'label_off' => esc_html__('No', 'wpsection'),
    ]
);


        

  
$repeater->add_control(
    'block_image_two',
    [
        'label' => __('Image', 'wpsection'),
        'condition' => ['tab_settings_two' => 'yes'],
        'type' => Controls_Manager::MEDIA,
       
    ]
);


        $repeater->add_control(
            'block_subtitle_two',
            [
                'label' => esc_html__('Subtitle', 'wpsection'),
                'condition' => ['tab_settings_two' => 'yes'],
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Image Description', 'wpsection'),
            ]
        );
        $repeater->add_control(
            'block_title_two',
            [
                'label' => esc_html__('Title', 'wpsection'),
                'condition' => ['tab_settings_two' => 'yes'],
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Gallery Image', 'wpsection'),
            ]
        );
        $repeater->add_control(
            'block_btnlink_x_two',
            [
                'label' => __('Button Url', 'wpsection'),
                'condition' => ['tab_settings_two' => 'yes'],
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'wpsection'),
                'show_external' => true,
                'default' => [
                'url' => '',
                'is_external' => true,
                'nofollow' => true,
                        ],
                'separator' => 'after',               
            ]
        );

//Three	
 $repeater->add_control(
    'tab_settings_three',
    [
        'label' => esc_html__('Enable Block Three', 'wpsection'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'default' => 'yes', // Set the default value
        'label_on' => esc_html__('Yes', 'wpsection'),
        'label_off' => esc_html__('No', 'wpsection'),
    ]
);


        

  
$repeater->add_control(
    'block_image_three',
    [
        'label' => __('Image', 'wpsection'),
        'condition' => ['tab_settings_three' => 'yes'],
        'type' => Controls_Manager::MEDIA,
       
    ]
);


        $repeater->add_control(
            'block_subtitle_three',
            [
                'label' => esc_html__('Subtitle', 'wpsection'),
                'condition' => ['tab_settings_three' => 'yes'],
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Image Description', 'wpsection'),
            ]
        );
        $repeater->add_control(
            'block_title_three',
            [
                'label' => esc_html__('Title', 'wpsection'),
                'condition' => ['tab_settings_three' => 'yes'],
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Gallery Image', 'wpsection'),
            ]
        );
        $repeater->add_control(
            'block_btnlink_x_three',
            [
                'label' => __('Button Url', 'wpsection'),
                'condition' => ['tab_settings_three' => 'yes'],
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'wpsection'),
                'show_external' => true,
                'default' => [
                'url' => '',
                'is_external' => true,
                'nofollow' => true,
                        ],
                'separator' => 'after',               
            ]
        );	


//Four	
 $repeater->add_control(
    'tab_settings_four',
    [
        'label' => esc_html__('Enable Block Four', 'wpsection'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'default' => 'yes', // Set the default value
        'label_on' => esc_html__('Yes', 'wpsection'),
        'label_off' => esc_html__('No', 'wpsection'),
    ]
);


        

  
$repeater->add_control(
    'block_image_four',
    [
        'label' => __('Image', 'wpsection'),
        'condition' => ['tab_settings_four' => 'yes'],
        'type' => Controls_Manager::MEDIA,
       
    ]
);


        $repeater->add_control(
            'block_subtitle_four',
            [
                'label' => esc_html__('Subtitle', 'wpsection'),
                'condition' => ['tab_settings_four' => 'yes'],
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Image Description', 'wpsection'),
            ]
        );
        $repeater->add_control(
            'block_title_four',
            [
                'label' => esc_html__('Title', 'wpsection'),
                'condition' => ['tab_settings_four' => 'yes'],
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Gallery Image', 'wpsection'),
            ]
        );
        $repeater->add_control(
            'block_btnlink_x_four',
            [
                'label' => __('Button Url', 'wpsection'),
                'condition' => ['tab_settings_four' => 'yes'],
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'wpsection'),
                'show_external' => true,
                'default' => [
                'url' => '',
                'is_external' => true,
                'nofollow' => true,
                        ],
                'separator' => 'after',               
            ]
        );


//Five		
		 $repeater->add_control(
    'tab_settings_five',
    [
        'label' => esc_html__('Enable Block Five', 'wpsection'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'default' => 'no', // Set the default value
        'label_on' => esc_html__('Yes', 'wpsection'),
        'label_off' => esc_html__('No', 'wpsection'),
    ]
);


        

  
$repeater->add_control(
    'block_image_five',
    [
        'label' => __('Image', 'wpsection'),
        'condition' => ['tab_settings_five' => 'yes'],
        'type' => Controls_Manager::MEDIA,
       
    ]
);


        $repeater->add_control(
            'block_subtitle_five',
            [
                'label' => esc_html__('Subtitle', 'wpsection'),
                'condition' => ['tab_settings_five' => 'yes'],
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Image Description', 'wpsection'),
            ]
        );
        $repeater->add_control(
            'block_title_five',
            [
                'label' => esc_html__('Title', 'wpsection'),
                'condition' => ['tab_settings_five' => 'yes'],
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Gallery Image', 'wpsection'),
            ]
        );
        $repeater->add_control(
            'block_btnlink_x_five',
            [
                'label' => __('Button Url', 'wpsection'),
                'condition' => ['tab_settings_five' => 'yes'],
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'wpsection'),
                'show_external' => true,
                'default' => [
                'url' => '',
                'is_external' => true,
                'nofollow' => true,
                        ],
                'separator' => 'after',               
            ]
        );
		
//Six
 $repeater->add_control(
    'tab_settings_six',
    [
        'label' => esc_html__('Enable Block Six', 'wpsection'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'default' => 'no', // Set the default value
        'label_on' => esc_html__('Yes', 'wpsection'),
        'label_off' => esc_html__('No', 'wpsection'),
    ]
);


        

  
$repeater->add_control(
    'block_image_six',
    [
        'label' => __('Image', 'wpsection'),
        'condition' => ['tab_settings_six' => 'yes'],
        'type' => Controls_Manager::MEDIA,
       
    ]
);


        $repeater->add_control(
            'block_subtitle_six',
            [
                'label' => esc_html__('Subtitle', 'wpsection'),
                'condition' => ['tab_settings_six' => 'yes'],
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Image Description', 'wpsection'),
            ]
        );
        $repeater->add_control(
            'block_title_six',
            [
                'label' => esc_html__('Title', 'wpsection'),
                'condition' => ['tab_settings_six' => 'yes'],
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Gallery Image', 'wpsection'),
            ]
        );
        $repeater->add_control(
            'block_btnlink_x_six',
            [
                'label' => __('Button Url', 'wpsection'),
                'condition' => ['tab_settings_six' => 'yes'],
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'wpsection'),
                'show_external' => true,
                'default' => [
                'url' => '',
                'is_external' => true,
                'nofollow' => true,
                        ],
                'separator' => 'after',               
            ]
        );
		
//seven		
		
 $repeater->add_control(
    'tab_settings_seven',
    [
        'label' => esc_html__('Enable  Block Seven', 'wpsection'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'default' => 'no', // Set the default value
        'label_on' => esc_html__('Yes', 'wpsection'),
        'label_off' => esc_html__('No', 'wpsection'),
    ]
);


        

  
$repeater->add_control(
    'block_image_seven',
    [
        'label' => __('Image', 'wpsection'),
        'condition' => ['tab_settings_seven' => 'yes'],
        'type' => Controls_Manager::MEDIA,
       
    ]
);


        $repeater->add_control(
            'block_subtitle_seven',
            [
                'label' => esc_html__('Subtitle', 'wpsection'),
                'condition' => ['tab_settings_seven' => 'yes'],
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Image Description', 'wpsection'),
            ]
        );
        $repeater->add_control(
            'block_title_seven',
            [
                'label' => esc_html__('Title', 'wpsection'),
                'condition' => ['tab_settings_seven' => 'yes'],
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Gallery Image', 'wpsection'),
            ]
        );
        $repeater->add_control(
            'block_btnlink_x_seven',
            [
                'label' => __('Button Url', 'wpsection'),
                'condition' => ['tab_settings_seven' => 'yes'],
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'wpsection'),
                'show_external' => true,
                'default' => [
                'url' => '',
                'is_external' => true,
                'nofollow' => true,
                        ],
                'separator' => 'after',               
            ]
        );		
		
//eight		
 $repeater->add_control(
    'tab_settings_eight',
    [
        'label' => esc_html__('Enable Block Eight', 'wpsection'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'default' => 'no', // Set the default value
        'label_on' => esc_html__('Yes', 'wpsection'),
        'label_off' => esc_html__('No', 'wpsection'),
    ]
);


        

  
$repeater->add_control(
    'block_image_eight',
    [
        'label' => __('Image', 'wpsection'),
        'condition' => ['tab_settings_eight' => 'yes'],
        'type' => Controls_Manager::MEDIA,
       
    ]
);


        $repeater->add_control(
            'block_subtitle_eight',
            [
                'label' => esc_html__('Subtitle', 'wpsection'),
                'condition' => ['tab_settings_eight' => 'yes'],
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Image Description', 'wpsection'),
            ]
        );
        $repeater->add_control(
            'block_title_eight',
            [
                'label' => esc_html__('Title', 'wpsection'),
                'condition' => ['tab_settings_eight' => 'yes'],
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Gallery Image', 'wpsection'),
            ]
        );
        $repeater->add_control(
            'block_btnlink_x_eight',
            [
                'label' => __('Button Url', 'wpsection'),
                'condition' => ['tab_settings_eight' => 'yes'],
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'wpsection'),
                'show_external' => true,
                'default' => [
                'url' => '',
                'is_external' => true,
                'nofollow' => true,
                        ],
                'separator' => 'after',               
            ]
        );		
//nine		
 $repeater->add_control(
    'tab_settings_nine',
    [
        'label' => esc_html__('Enable Block Nine', 'wpsection'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'default' => 'no', // Set the default value
        'label_on' => esc_html__('Yes', 'wpsection'),
        'label_off' => esc_html__('No', 'wpsection'),
    ]
);


        

  
$repeater->add_control(
    'block_image_nine',
    [
        'label' => __('Image', 'wpsection'),
        'condition' => ['tab_settings_nine' => 'yes'],
        'type' => Controls_Manager::MEDIA,
       
    ]
);


        $repeater->add_control(
            'block_subtitle_nine',
            [
                'label' => esc_html__('Subtitle', 'wpsection'),
                'condition' => ['tab_settings_nine' => 'yes'],
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Image Description', 'wpsection'),
            ]
        );
        $repeater->add_control(
            'block_title_nine',
            [
                'label' => esc_html__('Title', 'wpsection'),
                'condition' => ['tab_settings_nine' => 'yes'],
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Gallery Image', 'wpsection'),
            ]
        );
        $repeater->add_control(
            'block_btnlink_x_nine',
            [
                'label' => __('Button Url', 'wpsection'),
                'condition' => ['tab_settings_nine' => 'yes'],
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'wpsection'),
                'show_external' => true,
                'default' => [
                'url' => '',
                'is_external' => true,
                'nofollow' => true,
                        ],
                'separator' => 'after',               
            ]
        );		
//ten		
 $repeater->add_control(
    'tab_settings_ten',
    [
        'label' => esc_html__('Enable Block Ten', 'wpsection'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'default' => 'no', // Set the default value
        'label_on' => esc_html__('Yes', 'wpsection'),
        'label_off' => esc_html__('No', 'wpsection'),
    ]
);


        

  
$repeater->add_control(
    'block_image_ten',
    [
        'label' => __('Image', 'wpsection'),
        'condition' => ['tab_settings_ten' => 'yes'],
        'type' => Controls_Manager::MEDIA,
       
    ]
);


        $repeater->add_control(
            'block_subtitle_ten',
            [
                'label' => esc_html__('Subtitle', 'wpsection'),
                'condition' => ['tab_settings_ten' => 'yes'],
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Image Description', 'wpsection'),
            ]
        );
        $repeater->add_control(
            'block_title_ten',
            [
                'label' => esc_html__('Title', 'wpsection'),
                'condition' => ['tab_settings_ten' => 'yes'],
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Gallery Image', 'wpsection'),
            ]
        );
        $repeater->add_control(
            'block_btnlink_x_ten',
            [
                'label' => __('Button Url', 'wpsection'),
                'condition' => ['tab_settings_ten' => 'yes'],
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'wpsection'),
                'show_external' => true,
                'default' => [
                'url' => '',
                'is_external' => true,
                'nofollow' => true,
                        ],
                'separator' => 'after',               
            ]
        );		
// End of Item 
        $this->add_control(
            'repeater',
            [
                'label' => esc_html__('Repeater List', 'wpsection'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'list_title' => esc_html__('Title #1', 'wpsection'),
                        'list_content' => esc_html__('Item content. Click the edit button to change this text.', 'wpsection'),
                    ],
                ],
            ]
        );
        $this->end_controls_section();

//Style and Color

$this->start_controls_section(
            'wps_tab_project_control',
            array(
                'label' => __( 'Tab Settings', 'wpsection' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );
 //Tab Area 

        $this->add_control(
            'show_tab_area',
            array(
                'label' => esc_html__('Hide Tab Area ', 'ecolabe'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__('Show', 'ecolab'),
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__('Hide', 'ecolab'),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}} .wps_tab_container' => 'display: {{VALUE}} !important',
                ),
            )
        );
		
		
		
		$this->add_control(
            'wps_tabarea_x_alingment',
            array(
                'label' => esc_html__( 'Area Alignment', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'wpsection' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'wpsection' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'wpsection' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'flex-start',
                'toggle' => true,
                'selectors' => array(
                
                    '{{WRAPPER}}  .wps_tab_ul' => 'justify-content: {{VALUE}} !important',
                ),
            
            )
        ); 
$this->add_control( 'wps_tabarea_x_width',
                    [
                        'label' => esc_html__( 'Tab Area Width',  'wpsection' ),
                        'type' => \Elementor\Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                            'px' => [
                                'min' => 0,
                                'max' => 2000,
                                'step' => 1,
                            ],
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        
                        'selectors' => [
                            '{{WRAPPER}} .wps_tab_container' => 'width: {{SIZE}}{{UNIT}};',
                        ]
                    
                    ]
                );
        

    $this->add_control( 'wps_tabarea_x_height',
                    [
                        'label' => esc_html__( 'Tab Area Height', 'wpsection' ),
                        'type' => \Elementor\Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                        'range' => [
                            'px' => [
                                'min' => 0,
                                'max' => 1000,
                                'step' => 1,
                            ],
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        
                        'selectors' => [
                            '{{WRAPPER}} .wps_tab_container' => 'height: {{SIZE}}{{UNIT}};',
                    
                        ]
                    ]
                );      
            
    
         
$this->add_control(
            'wps_tab_area_bgcolor',
            array(
                'label'     => __( 'Backgorud Color', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
    
                'selectors' => array(
                    '{{WRAPPER}}   .wps_tab_container' => 'background: {{VALUE}} !important',

                ),
            )
        );   
		
		
    $this->add_control(
            'wps_tabarea_x_padding',
            array(
                'label'     => __( 'Area Padding', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .wps_tab_container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

    $this->add_control(
            'wps_tabarea_x_margin',
            array(
                'label'     => __( 'Area Margin', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .wps_tab_container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'wps_tabarea_x_border',
                'selector' => '{{WRAPPER}} .wps_tab_container ',
            )
        );
    

        $this->add_control(
            'wps_tabarea_radius',
            array(
                'label'     => __( 'Area Border Radius', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}} .wps_tab_container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );


            $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wps_tabarea_x_shadow',
                'label' => esc_html__( 'Area Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wps_tab_container',
            ]
        ); 
        
$this->end_controls_section();     




//=======================Tab Single Settings  ======================



$this->start_controls_section(
            'wps_tab_single_x_control',
            array(
                'label' => __( 'Tab Single Settings', 'wpsection' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );      

$this->add_control( 'wps_tab_x_width',
                    [
                        'label' => esc_html__( 'Tab Width',  'wpsection' ),
                        'type' => \Elementor\Controls_Manager::SLIDER,
                        'size_units' => [ 'px', '%' ],
                    
                        'range' => [
                            'px' => [
                                'min' => 0,
                                'max' => 2000,
                                'step' => 1,
                            ],
                            '%' => [
                                'min' => 0,
                                'max' => 100,
                            ],
                        ],
                        
                        'selectors' => [
                            '{{WRAPPER}} .wps_tab_button' => 'width: {{SIZE}}{{UNIT}};',
                        ]
                    
                    ]
                );

      $this->add_control(
            'wps_tab_x_alingment',
            array(
                'label' => esc_html__( 'Text Alignment', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'wpsection' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'wpsection' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'wpsection' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
                'selectors' => array(
                
                    '{{WRAPPER}}  .wps_tab_ul li .nav-link' => 'text-align: {{VALUE}} !important',
                ),
            )
        );         
        
$this->add_control(
            'wps_tab_x_color',
            array(
                'label'     => __( 'Button Color', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
    
                'selectors' => array(
                    '{{WRAPPER}}   .wps_tab_button .nav-link ' => 'color: {{VALUE}} !important',

                ),
            )
        );
$this->add_control(
            'wps_tab_x_color_hover',
            array(
                'label'     => __( 'Button Hover Color', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}  .wps_tab_button:hover .nav-link ' => 'color: {{VALUE}} !important',

                ),
            )
        );
$this->add_control(
            'wps_tab_xactive_color',
            array(
                'label'     => __( 'Button Active Color', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}  .wps_tab_button .nav-link.active ' => 'color: {{VALUE}} !important',

                ),
            )
        );
$this->add_control(
            'wps_tab_x_bg_color',
            array(
                'label'     => __( 'Background Color', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}  .wps_tab_button' => 'background: {{VALUE}} !important',
                ),
            )
        );  
$this->add_control(
            'wps_tab_x_hover_color',
            array(
                'label'     => __( 'Background Hover Color', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}  .wps_tab_button:hover' => 'background: {{VALUE}} !important',
                ),
            )
        );  
        
 $this->add_control(
            'wps_tab_x_active_color',
            array(
                'label'     => __( 'Background Active Color', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}}  .wps_tab_button .active' => 'background: {{VALUE}} !important',
                ),
            )
        );  
               
  
    $this->add_control(
            'wps_tab_x_padding',
            array(
                'label'     => __( 'Padding', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .wps_tab_button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

    $this->add_control(
            'wps_tab_x_margin',
            array(
                'label'     => __( 'Margin', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .wps_tab_button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'wps_tab_x_typography',
                'label'    => __( 'Typography', 'wpsection' ),
                'selector' => '{{WRAPPER}}  .wps_tab_button .nav-link ',
            )
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'wps_tab_x_border',
                'selector' => '{{WRAPPER}} .wps_tab_button ',
            )
        );
    

        $this->add_control(
            'wps_tab_x_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}} .wps_tab_button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );


            $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wps_tab_x_shadow',
                'label' => esc_html__( 'Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wps_tab_button',
            
                
            ]
        );
        
        $this->end_controls_section();  


        

        $this->start_controls_section(
            'section_title_style',
            [
                'label' => esc_html__('Title Setting', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'show_title',
            array(
                'label' => esc_html__('Show Title', 'ecolabe'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__('Show', 'ecolab'),
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__('Hide', 'ecolab'),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}} .mr_block_title' => 'display: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'title_alingment',
            array(
                'label' => esc_html__('Alignment', 'ecolab'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'ecolab'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'ecolab'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'ecolab'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'condition'    => array('show_title' => 'show'),
                'toggle' => true,
                'selectors' => array(

                    '{{WRAPPER}} .mr_block_title' => 'text-align: {{VALUE}} !important',
                ),
            )
        );


        $this->add_control(
            'title_padding',
            array(
                'label'     => __('Padding', 'ecolab'),
                'condition'    => array('show_title' => 'show'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],

                'selectors' => array(
                    '{{WRAPPER}} .mr_block_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_typography',
                'condition'    => array('show_title' => 'show'),
                'label'    => __('Typography', 'ecolab'),
                'selector' => '{{WRAPPER}} .mr_block_title a',
            )
        );
        $this->add_control(
            'title_color',
            array(
                'label'     => __('Color', 'ecolab'),
                'condition'    => array('show_title' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_block_title a' => 'color: {{VALUE}} !important',

                ),
            )
        );
        $this->add_control(
            'title_hover_color',
            array(
                'label'     => __('Color Hover', 'ecolab'),
                'condition'    => array('show_title' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_featured_block:hover a' => 'color: {{VALUE}} !important',

                ),
            )
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_subtitle_style',
            [
                'label' => esc_html__('SubTitle Setting', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'show_subtitle',
            array(
                'label' => esc_html__('Show Sub Title', 'ecolabe'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__('Show', 'ecolab'),
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__('Hide', 'ecolab'),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}} .mr_block_subtitle' => 'display: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'subtitle_alingment',
            array(
                'label' => esc_html__('Alignment', 'ecolab'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'ecolab'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'ecolab'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'ecolab'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'condition'    => array('show_title' => 'show'),
                'toggle' => true,
                'selectors' => array(

                    '{{WRAPPER}} .mr_block_subtitle' => 'text-align: {{VALUE}} !important',
                ),
            )
        );


        $this->add_control(
            'subtitle_padding',
            array(
                'label'     => __('Padding', 'ecolab'),
                'condition'    => array('show_title' => 'show'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],

                'selectors' => array(
                    '{{WRAPPER}} .mr_block_subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'subtitle_typography',
                'condition'    => array('show_subtitle' => 'show'),
                'label'    => __('Typography', 'ecolab'),
                'selector' => '{{WRAPPER}} .mr_block_subtitle',
            )
        );
        $this->add_control(
            'subtitle_color',
            array(
                'label'     => __('Color', 'ecolab'),
                'condition'    => array('show_subtitle' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_block_subtitle' => 'color: {{VALUE}} !important',

                ),
            )
        );
        $this->add_control(
            'subtitle_hover_color',
            array(
                'label'     => __('Color Hover', 'ecolab'),
                'condition'    => array('show_subtitle' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .mr_featured_block_subtitle:hover' => 'color: {{VALUE}} !important',

                ),
            )
        );
        $this->end_controls_section();
		
		
// Thumbnail SEttings		
		
	$this->start_controls_section(
            'thumbnail_control',
            array(
                'label' => __( 'Thumbanil Settings', 'wpsection' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );
        
$this->add_control(
            'show_thumbnail',
            array(
                'label' => esc_html__( 'Show Button', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__( 'Show', 'wpsection' ), 
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__( 'Hide', 'wpsection' ),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}} .mr_product_thumb' => 'display: {{VALUE}} !important',
                ),
            )
        );   



		
    $this->add_control(
            'wps_thumbnail_bg',
            [
                'label' => esc_html__('Background Color', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mr_product_thumb' => 'background-color: {{VALUE}} !important;',
                ],
                'default' => '#3A9E1E', 
            ]
        );	
		
	    $this->add_control(
            'wps_thumbnail_hover_bg',
            [
                'label' => esc_html__('Background Hover Color', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wps_overlay_style_1 .image-box:before' => 'background: {{VALUE}} !important;',
                ],
                'default' => '#3A9E1E', 
            ]
        );		
		
		
    $this->add_control(
            'thumbnail_padding',
            array(
                'label'     => __( 'Padding', 'wpsection' ),
             'condition'    => array( 'show_thumbnail' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
        
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}} .mr_product_thumb' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

    $this->add_control(
            'thumbnail_x_margin',
            array(
                'label'     => __( 'Margin', 'wpsection' ),
                    'condition'    => array( 'show_thumbnail' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
            
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .mr_product_thumb' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );


        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'thumbnail_border',
                    'condition'    => array( 'show_thumbnail' => 'show' ),
                'selector' => '{{WRAPPER}} .mr_product_thumb',
            )
        );
                
            $this->add_control(
            'thumbnail_border_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                'condition'    => array( 'show_thumbnail' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .wps_image_box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );  
		
		
		
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'thumbnail_box_shadow',
                'label' => esc_html__('Box Shadow', 'wpsection'),
				'selector' => '{{WRAPPER}} .wps_project_tab .wps_image_box',
			]
		);
        $this->end_controls_section();
        
//End of Thumbnail 
	
		

        $this->start_controls_section(
            'section_portfollio_style',
            [
                'label' => esc_html__('Icon Setting', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
  
	   $this->add_control(
            'wps_project_icon',
            array(
                'label' => esc_html__('Show Icons', 'wpsection'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__('Show', 'wpsection'),
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__('Hide', 'wpsection'),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}} .wps_project_icon' => 'display: {{VALUE}} !important',
                ),
            )
        );
	
		
        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Icon Color', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wps_project_icon a' => 'color: {{VALUE}} !important;',
                ],
                'default' => '#fff', 
            ]
        );
        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__('Icon Color Hover', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wps_project_icon a:hover' => 'color: {{VALUE}} !important;',
                ],
                'default' => '#101A30', 
            ]
        );
        $this->add_control(
            'button_background_color',
            [
                'label' => esc_html__(' Background Color', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wps_project_icon a' => 'background-color: {{VALUE}} !important;',
                ],
                'default' => '#3A9E1E', 
            ]
        );
        $this->add_control(
            'wps_project_icon_bg_hover',
            [
                'label' => esc_html__('Background Hover Color', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wps_project_icon a:hover' => 'background-color: {{VALUE}} !important;',
                ],
                'default' => '#fff', 
            ]
        );
		
		
	        $this->add_control(
            'wps_project_icon_width',
            [
                'label' => esc_html__('Icon Box Width',  'wpsection'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 85,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wps_project_icon a' => 'width: {{SIZE}}{{UNIT}};',
                ]

            ]
        );


        $this->add_control(
            'wps_project_icon_height',
            [
                'label' => esc_html__('Icon Box Height', 'wpsection'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 85,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wps_project_icon a' => 'height: {{SIZE}}{{UNIT}};',

                ]
            ]
        );



        $this->add_control(
            'wps_project_icono_padding',
            array(
                'label'     => __('Padding', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],
                'selectors' => array(
                    '{{WRAPPER}}  .wps_project_icon a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_control(
            'wps_icon_margin',
            array(
                'label'     => __('Margin', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],
                'selectors' => array(
                    '{{WRAPPER}}  .wps_project_icon a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

		
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'wps_projce_icon_typo',
                'label'    => __('Typography', 'wpsection'),
                'selector' => '{{WRAPPER}}  .wps_project_icon a',
            )
        );
		
		
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'wps_project_icon_border',
                'selector' => '{{WRAPPER}}  .wps_project_icon a ',
            )
        );


        $this->add_control(
            'wps_project_icon_radious',
            array(
                'label'     => __('Border Radius', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],

                'selectors' => array(
                    '{{WRAPPER}}  .wps_project_icon a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
		
    


        $this->add_control(
            'wps_project_expand_icon_horizontal',
            [
                'label' => esc_html__('Expand Icon Horizontal',  'wpsection'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wps_project_expand_icon' => 'left: {{SIZE}}{{UNIT}};',
                ]

            ]
        );
		
		
	$this->add_control(
            'wps_project_expand_icon_vertical',
            [
                'label' => esc_html__('Expand Icon Vertical', 'wpsection'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -2000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wps_project_expand_icon' => 'top: {{SIZE}}{{UNIT}};',

                ]
            ]
        );	
		
		
		
        $this->add_control(
            'wps_project_plus_icon_horizontal',
            [
                'label' => esc_html__('Plus Icon Horizontal Position', 'wpsection'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wps_project_plus_icon' => 'right: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_control(
            'wps_project_plus_icon_vertical',
            [
                'label' => esc_html__('Plus Icon Vertical Position', 'wpsection'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -2000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wps_project_plus_icon' => 'top: {{SIZE}}{{UNIT}};',

                ]
            ]
        );
	
	    $this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'project_icon_shadow',
                'label' => esc_html__('Box Shadow', 'wpsection'),
				'selector' => '{{WRAPPER}} .wps_project_icon a',
			]
		);
		

        $this->end_controls_section();		
		
		
		
		
		

        $this->start_controls_section(
            'wps_project_bottom_style',
            [
                'label' => esc_html__('Bottom Area Setting', 'wpsection'),
                'tab'   => Controls_Manager::TAB_STYLE,
				 'condition'    => array( 'text_box_style' => 'style-2' ),
            ]
        );
  
	   $this->add_control(
            'wps_project_bottom',
            array(
                'label' => esc_html__('Show Bottom Area', 'wpsection'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__('Show', 'wpsection'),
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__('Hide', 'wpsection'),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}} .text_outside_box' => 'display: {{VALUE}} !important',
                ),
            )
        );
	
		
 
        $this->add_control(
            'button_background_bottom_area',
            [
                'label' => esc_html__(' Background Color', 'your-text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .text_outside_box' => 'background-color: {{VALUE}} !important;',
                ],
                'default' => '#3A9E1E', 
            ]
        );
        $this->add_control(
            'wps_project_bottom_area',
            [
                'label' => esc_html__('Background Hover Color', 'wpsection'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .text_outside_box:hover' => 'background-color: {{VALUE}} !important;',
                ],
                'default' => '#fff', 
            ]
        );
		
		
	        $this->add_control(
            'wps_project_bottom_area_width',
            [
                'label' => esc_html__(' Box Width',  'wpsection'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .text_outside_box' => 'width: {{SIZE}}{{UNIT}};',
                ]

            ]
        );


        $this->add_control(
            'wps_project_bottom_area_height',
            [
                'label' => esc_html__(' Box Height', 'wpsection'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .text_outside_box' => 'height: {{SIZE}}{{UNIT}};',

                ]
            ]
        );



        $this->add_control(
            'wps_project_bottom_area_padding',
            array(
                'label'     => __('Padding', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],
                'selectors' => array(
                    '{{WRAPPER}}  .text_outside_box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_control(
            'wps_bottom_area_margin',
            array(
                'label'     => __('Margin', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],
                'selectors' => array(
                    '{{WRAPPER}} .text_outside_box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

		

		
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'wps_project_bottom_area_border',
                'selector' => '{{WRAPPER}}  .text_outside_box ',
            )
        );


        $this->add_control(
            'wps_project_bottom_area_radious',
            array(
                'label'     => __('Border Radius', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em'],

                'selectors' => array(
                    '{{WRAPPER}}  .text_outside_box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
		
    


  
	
	    $this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'project_bottom_area_shadow',
                'label' => esc_html__('Box Shadow', 'wpsection'),
				'selector' => '{{WRAPPER}} .text_outside_box',
			]
		);
		

        $this->end_controls_section();	
		
		
		
		
// Slide Arrow Settings 		
		
        $this->start_controls_section(
            'slider_path_button_3_control',
            array(
                'label' => __('Slider Arrow  Settings', 'wpsection'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				 'condition'    => array( 'enable_slide' => 'yes' ),
            )
        );

        $this->add_control(
            'slider_path_show_button_3',
            array(
                'label' => esc_html__('Show Button', 'wpsection'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__('Show', 'wpsection'),
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__('Hide', 'wpsection'),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}} .slider_path .owl-nav ' => 'display: {{VALUE}} !important',
                ),
            )
        );

        $this->add_control(
            'slider_path_button_3_color',
            array(
                'label'     => __('Button Color', 'wpsection'),
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default' => '#cbcbcb',
                'selectors' => array(
                    '{{WRAPPER}}  .slider_path .owl-nav button' => 'color: {{VALUE}} !important',

                ),
            )
        );
        $this->add_control(
            'slider_path_button_3_color_hover',
            array(
                'label'     => __('Button Hover Color', 'wpsection'),
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff ',
                'selectors' => array(
                    '{{WRAPPER}}  .slider_path .owl-nav button:hover' => 'color: {{VALUE}} !important',

                ),
            )
        );
        $this->add_control(
            'slider_path_button_3_bg_color',
            array(
                'label'     => __('Background Color', 'wpsection'),
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default' => '#f3f3f3 ',
                'selectors' => array(
                    '{{WRAPPER}}  .slider_path .owl-nav button' => 'background: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'slider_path_button_3_hover_color',
            array(
                'label'     => __('Background Hover Color', 'wpsection'),
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default' => '#222',
                'selectors' => array(
                    '{{WRAPPER}}  .slider_path .owl-nav button:hover' => 'background: {{VALUE}} !important',
                ),
            )
        );



        $this->add_control(
            'slider_path_dot_3_width',
            [
                'label' => esc_html__('Arraw Width',  'wpsection'),
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .slider_path .owl-nav .owl-prev' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .slider_path .owl-nav .owl-next' => 'width: {{SIZE}}{{UNIT}};',
                ]

            ]
        );


        $this->add_control(
            'slider_path_dot_3_height',
            [
                'label' => esc_html__('Arraw Height', 'wpsection'),
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .slider_path .owl-nav .owl-prev' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .slider_path .owl-nav .owl-next ' => 'height: {{SIZE}}{{UNIT}};',

                ]
            ]
        );



        $this->add_control(
            'slider_path_button_3_padding',
            array(
                'label'     => __('Padding', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'size_units' =>  ['px', '%', 'em'],

                'selectors' => array(
                    '{{WRAPPER}}  .slider_path .owl-nav button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_control(
            'slider_path_button_3_margin',
            array(
                'label'     => __('Margin', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'size_units' =>  ['px', '%', 'em'],
                'selectors' => array(
                    '{{WRAPPER}}  .slider_path .owl-nav button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'slider_path_button_3_typography',
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'label'    => __('Typography', 'wpsection'),
                'selector' => '{{WRAPPER}}  .slider_path .owl-nav button',
            )
        );
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'slider_path_border_3',
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'selector' => '{{WRAPPER}}  .slider_path .owl-nav .owl-prev, .slider_path .owl-nav .owl-next ',
            )
        );


        $this->add_control(
            'slider_path_border_3_radius',
            array(
                'label'     => __('Border Radius', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'size_units' =>  ['px', '%', 'em'],

                'selectors' => array(
                    '{{WRAPPER}}  .slider_path .owl-nav button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );
        $this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
                'label' => esc_html__('Box Shadow', 'wpsection'),
				'selector' => '{{WRAPPER}} .slider_path .owl-nav button',
			]
		);



        $this->add_control(
            'slider_path_horizontal_prev',
            [
                'label' => esc_html__('Horizontal Position Previous',  'wpsection'),
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .slider_path .owl-nav .owl-prev' => 'left: {{SIZE}}{{UNIT}};',
                ]

            ]
        );
        $this->add_control(
            'slider_path_horizontal_next',
            [
                'label' => esc_html__('Horizontal Position Next', 'wpsection'),
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .slider_path .owl-nav .owl-next' => 'right: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->add_control(
            'slider_path_vertical',
            [
                'label' => esc_html__('Vertical Position', 'wpsection'),
                'condition'    => array('slider_path_show_button_3' => 'show'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -2000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .slider_path .owl-nav button' => 'top: {{SIZE}}{{UNIT}};',

                ]
            ]
        );


        $this->end_controls_section();



// Dot Button Setting

        $this->start_controls_section(
            'slider_path_dot_control',
            array(
                'label' => __('Slider Dot  Settings', 'wpsection'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'    => array( 'enable_slide' => 'yes' ),

            )
        );

        $this->add_control(
            'slider_path_show_dot',
            array(
                'label' => esc_html__('Show Dot', 'wpsection'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__('Show', 'wpsection'),
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__('Hide', 'wpsection'),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'none',
                'selectors' => array(
                    '{{WRAPPER}} .slider_path .owl-dots  ' => 'display: {{VALUE}} !important',
                ),
            )
        );


        $this->add_control(
            'slider_path_dot_width',
            [
                'label' => esc_html__('Dot Width',  'wpsection'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'condition'    => array('slider_path_show_dot' => 'show'),
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .slider_path .owl-dots .owl-dot' => 'width: {{SIZE}}{{UNIT}};',
                ]

            ]
        );

        $this->add_control(
            'slider_path_dot_height',
            [
                'label' => esc_html__('Dot Height', 'wpsection'),
                'condition'    => array('slider_path_show_dot' => 'show'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .slider_path .owl-dots .owl-dot' => 'height: {{SIZE}}{{UNIT}};',

                ]
            ]
        );

        $this->add_control(
            'slider_path_dot_color',
            array(
                'label'     => __('Dot Color', 'wpsection'),

                'type'      => \Elementor\Controls_Manager::COLOR,
                'default' => '#222',
                'condition'    => array('slider_path_show_dot' => 'show'),
                'selectors' => array(
                    '{{WRAPPER}}  .slider_path .owl-dots .owl-dot' => 'background: {{VALUE}} !important',

                ),
            )
        );
		
        $this->add_control(
            'slider_path_dot_color_hover',
            array(
                'label'     => __('Dot Hover Color', 'wpsection'),

                'type'      => \Elementor\Controls_Manager::COLOR,
                'condition'    => array('slider_path_show_dot' => 'show'),
                'selectors' => array(
                    '{{WRAPPER}}  .slider_path .owl-dots .owl-dot:hover' => 'background: {{VALUE}} !important',

                ),
            )
        );
        $this->add_control(
            'slider_path_dot_bg_color',
            array(
                'label'     => __('Active Color', 'wpsection'),

                'type'      => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff',
                'condition'    => array('slider_path_show_dot' => 'show'),
                'selectors' => array(
                    '{{WRAPPER}} .slider_path  .owl-dots .owl-dot.active' => 'background: {{VALUE}} !important',
                ),
            )
        );

        $this->add_control(
            'slider_path_dot_padding',
            array(
                'label'     => __('Padding', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,

                'size_units' =>  ['px', '%', 'em'],
                'condition'    => array('slider_path_show_dot' => 'show'),
                'selectors' => array(
                    '{{WRAPPER}}  .slider_path  .owl-dots .owl-dot' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_control(
            'slider_path_dot_margin',
            array(
                'label'     => __('Margin', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,

                'size_units' =>  ['px', '%', 'em'],
                'condition'    => array('slider_path_show_dot' => 'show'),
                'selectors' => array(
                    '{{WRAPPER}}  .slider_path  .owl-dots .owl-dot' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );


        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name' => 'slider_path_dot_border',
                'condition'    => array('slider_path_show_dot' => 'show'),
                'selector' => '{{WRAPPER}}  .slider_path  .owl-dots .owl-dot',
            )
        );


        $this->add_control(
            'slider_path_dot_radius',
            array(
                'label'     => __('Border Radius', 'wpsection'),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,

                'size_units' =>  ['px', '%', 'em'],
                'condition'    => array('slider_path_show_dot' => 'show'),
                'selectors' => array(
                    '{{WRAPPER}}  .slider_path  .owl-dots .owl-dot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );




  $this->add_control(
            'slider_path_dot_horizontal',
            [
                'label' => esc_html__('Horizontal Position Previous',  'wpsection'),
                'condition'    => array('slider_path_show_dot' => 'show'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -1000,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .slider_path  .owl-dots .owl-dot' => 'left: {{SIZE}}{{UNIT}};',
                ]

            ]
        );


        $this->add_control(
            'slider_path_dot_vertical',
            [
                'label' => esc_html__('Vertical Position', 'wpsection'),
                'condition'    => array('slider_path_show_dot' => 'show'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -200,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 85,
                ],
                'selectors' => [
                    '{{WRAPPER}} .slider_path  .owl-dots .owl-dot ' => 'top: {{SIZE}}{{UNIT}};',

                ]
            ]
        );
        $this->end_controls_section();
//Project Block 		
	   $this->start_controls_section(
                'wps_project_block_settings',
                array(
                    'label' => __( 'Block Setting', 'wpsection' ),
                    'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
                )
            );

        
    $this->add_control(
            'wps_project_show_block',
            array(
                'label' => esc_html__( 'Show Block', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'show' => [
                        'show' => esc_html__( 'Show', 'wpsection' ), 
                        'icon' => 'eicon-check-circle',
                    ],
                    'none' => [
                        'none' => esc_html__( 'Hide', 'wpsection' ),
                        'icon' => 'eicon-close-circle',
                    ],
                ],
                'default' => 'show',
                'selectors' => array(
                    '{{WRAPPER}} .wp_project_block' => 'display: {{VALUE}} !important',
                ),
            )
        );  


        

$this->add_control(
            'wps_project_box_height',
            [
                'label' => esc_html__( 'Min Height', 'wpsection' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wp_project_block' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'wps_project_block_color',
            array(
                'label'     => __( 'Background Color', 'wpsection' ),
                //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wp_project_block' => 'background: {{VALUE}} !important',
                ),
            )
        );
        $this->add_control(
            'wps_project_block_hover_color',
            array(
                'label'     => __( 'Hover Color', 'wpsection' ),
               //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .wp_project_block:hover' => 'background: {{VALUE}} !important',
                ),
            )
        );
    
        $this->add_control(
            'wps_project_block_margin',
            array(
                'label'     => __( 'Block Margin', 'wpsection' ),
                    //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .wp_project_block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

        $this->add_control(
            'wps_project_block_padding',
            array(
                'label'     => __( 'Block Padding', 'wpsection' ),
                    //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
            
                'selectors' => array(
                    '{{WRAPPER}}  .wp_project_block' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

            $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wps_project_block_shadow',
                    //'condition'    => array( 'show_block' => 'show' ),
                'label' => esc_html__( 'Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wp_project_block',
            ]
        );
      $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wps_project_block_X_hover_shadow',
                   // 'condition'    => array( 'show_block' => 'show' ),
                'label' => esc_html__( 'Hover Box Shadow', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wp_project_block:hover',
            ]
        );

 
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'wps_project_block_border',
                //'condition'    => array( 'show_block' => 'show' ),
                'label' => esc_html__( 'Box Border', 'wpsection' ),
                'selector' => '{{WRAPPER}} .wp_project_block',
            ]
        );
                
            $this->add_control(
            'wps_project_block_border_radius',
            array(
                'label'     => __( 'Border Radius', 'wpsection' ),
                //'condition'    => array( 'show_block' => 'show' ),
                'type'      => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' =>  ['px', '%', 'em' ],
                'selectors' => array(
                    '{{WRAPPER}} .wp_project_block' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}!important',
                ),
            )
        );

	   $this->end_controls_section();
		
		
		
    }

    /**
     * Render button widget output on the frontend.
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $allowed_tags = wp_kses_allowed_html('post');
		
		    //Column Settings Area   

        if($settings['wps_columns'] == '10') {
                        $columns_markup = ' mr_column_10 ';
                    }      
         else if($settings['wps_columns'] == '9') {
                        $columns_markup = ' mr_column_9 ';
                    } 
        else if($settings['wps_columns'] == '8') {
                        $columns_markup = ' mr_column_8 ';
                    }   
         else if($settings['wps_columns'] == '7') {
                        $columns_markup = ' mr_column_7 ';
                    } 
        else if($settings['wps_columns'] == '6') {
                        $columns_markup = 'col-lg-2 ';
                    }
        else if($settings['wps_columns'] == '5') {
                        $columns_markup = ' mr_column_5 ';
                    } 
        else if($settings['wps_columns'] == '4') {
                        $columns_markup = 'col-lg-3 ';
                    }   
         else if($settings['wps_columns'] == '3') {
                        $columns_markup = 'col-lg-4 ';
                    }
        else if($settings['wps_columns'] == '2') {
                        $columns_markup = 'col-lg-6 ';
                    } 
        else if($settings['wps_columns'] == '1') {
                        $columns_markup = 'col-lg-12 ';
                    }

// Tab Column 

  //Column Settings Area   

        if($settings['wps_columns_tab'] == '10') {
                        $columns_markup_tab = ' mr_column_10 ';
                    }      
         else if($settings['wps_columns_tab'] == '9') {
                        $columns_markup_tab = ' mr_column_9 ';
                    } 
        else if($settings['wps_columns_tab'] == '8') {
                        $columns_markup_tab = ' mr_column_8 ';
                    }   
         else if($settings['wps_columns_tab'] == '7') {
                        $columns_markup_tab = ' mr_column_7 ';
                    } 
        else if($settings['wps_columns_tab'] == '6') {
                        $columns_markup_tab = ' col-md-2';
                    }
        else if($settings['wps_columns_tab'] == '5') {
                        $columns_markup_tab = ' mr_column_5 ';
                    } 
        else if($settings['wps_columns_tab'] == '4') {
                        $columns_markup_tab = ' col-md-3 ';
                    }   
         else if($settings['wps_columns_tab'] == '3') {
                        $columns_markup_tab = ' col-md-4';
                    }
        else if($settings['wps_columns_tab'] == '2') {
                        $columns_markup_tab = ' col-md-6';
                    } 
        else if($settings['wps_columns_tab'] == '1') {
                        $columns_markup_tab = ' col-md-12';
                    }


$columns_markup_print = $columns_markup . ' ' . $columns_markup_tab;
?>



<?php
  $style = $settings['style'];
    $style_folder = __DIR__ . '/wps_tab/';
    $style_file = $style_folder . $style . '.php';

    if (is_readable($style_file)) {
        require $style_file;
    } else {
        echo "Style file '$style.php' not found or could not be read.";
    }

?>





<?php
    }
}

// Register widget
Plugin::instance()->widgets_manager->register(new \wps_tab());