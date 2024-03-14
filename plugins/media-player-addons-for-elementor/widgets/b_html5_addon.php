<?php
namespace BMianAddon\Widgets;
use Elementor\Modules\DynamicTags\Module as TagsModule;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class b_html5_addon extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'Html5VideoPlayer';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Html5 Video Player', 'baddon' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'bl_icon fas fa-video ';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'baddon' ];
	}

    public function get_script_depends() {
        return [ 'plyr-js', 'main-js'];
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
	protected function register_controls() {



  //Vedio player Content Settings

         //single video

    $this->start_controls_section(
        '_section_images',
        [
            'label' => __( 'Vedio Player Content Settings', 'baddon' ),
              'tab' => Controls_Manager::TAB_CONTENT,
           
        ]
    );



        $this->add_control(
            'choose_source',
            [
                'label'         => __( 'Multiple Quality', 'baddon' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'yes', 'baddon' ),
                'label_off'     => __( 'no', 'baddon' ),
                'return_value'  => 'yes',
                'default'       => '',
            ]
        );

       $this->add_control(
            'srrc_type',
            [
                'label' => esc_html__( 'Source From', 'baddon' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'uploaad',
                'options' => [
                    'uploaad' => esc_html__( 'Media Library', 'baddon' ),
                    'liink' => esc_html__( 'Video File Link', 'baddon' ),
                ],
                'condition' => [
                    'choose_source'    =>  '',
                ]
  
            ]
        );
       $this->add_control(
            'videoos_upload',
            [
                'label' => esc_html__( 'Upload Video', 'baddon' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                    'categories' => [
                        TagsModule::MEDIA_CATEGORY,
                    ],
                ],
                'media_type' => 'video',
                'condition' => [
                    'choose_source'    =>  '',
                    'srrc_type'     => 'uploaad',
                ]
            ]
        );
        $this->add_control(
            'videoos_link',
            [
                'label' => esc_html__( 'Video Link', 'baddon' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'https://your-link.com', 'baddon' ),
                'default' =>'https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4',
                'condition' => [
                    'choose_source'    =>  '',
                    'srrc_type'     => 'liink',
                ]
            ]
        );

        $this->add_control(
            'custom_poster',
            [
                'label' => esc_html__( 'Add Custom Poster', 'baddon' ),
                'type' =>Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'baddon' ),
                'label_off' => esc_html__( 'No', 'baddon' ),
                'return_value' => 'true',
                'default' => '',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'poster',
            [
                'label' => esc_html__( 'Custom Poster For Video', 'baddon' ),
                'type' =>Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'custom_poster'    =>  'true',
                ]
            ]
        );

    //End single video


    //Start Multiple video
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'src_type',
            [
                'label' => esc_html__( 'Video Source', 'baddon' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'upload',
                'options' => [
                    'upload' => esc_html__( 'Upload Video', 'baddon' ),
                    'link' => esc_html__( 'Put Video Link', 'baddon' ),
                ],
            ]
        );
        $repeater->add_control(
            'video_upload',
            [
                'label' => esc_html__( 'Upload Video', 'baddon' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                    'categories' => [
                        TagsModule::MEDIA_CATEGORY,
                    ],
                ],
                'media_type' => 'video',
                'condition' => [
                    'src_type'     => 'upload',
                ]
            ]
        );
        $repeater->add_control(
            'video_link',
            [
                'label' => esc_html__( 'Video Link', 'baddon' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'https://your-link.com', 'baddon' ),
                'default' =>'https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4',
                'condition' => [
                    'src_type'     => 'link',
                ]
            ]
        );
        $repeater->add_control(
            'video_size',
            [
                'label' => esc_html__( 'Video Size', 'baddon' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Select', 'baddon' ),
                    '240' => esc_html__( '240', 'baddon' ),
                    '360' => esc_html__( '360', 'baddon' ),
                    '480' => esc_html__( '480', 'baddon' ),
                    '576' => esc_html__( '576', 'baddon' ),
                    '720' => esc_html__( '720', 'baddon' ),
                    '1080' => esc_html__( '1080', 'baddon' ),
                    '1440' => esc_html__( '1440', 'baddon' ),
                    '2160' => esc_html__( '2160', 'baddon' ),
                    '2880' => esc_html__( '2880', 'baddon' ),
                    '4320'  => esc_html__( '4320', 'baddon' ),
                ],
            ]
        );

        $this->add_control(
            'video_list',
            [
                'label' => esc_html__( 'Video List', 'baddon' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                 'condition' => [
                    'choose_source'    =>  'yes',
                ]
           
            ]
        );

    $this->end_controls_section();
  //End multiple video


// Vedo subtitle start
  $this->start_controls_section(
        '_sub_settings',
        [
            'label' => __( 'Vedio Player Subtitle Settings', 'baddon' ),
           
        ]
    );
   $repeaterg = new \Elementor\Repeater();
        $repeaterg->add_control(
            'src_typed',
            [
                'label' => esc_html__( 'Subtitle Source', 'baddon' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'uploadds',
                'options' => [
                    'uploadds' => esc_html__( 'Upload Subtitle', 'baddon' ),
                    'linkks' => esc_html__( 'Put Subtitle Link', 'baddon' ),
                ],
            ]
        );
        $repeaterg->add_control(
            'subtitle_upload',
            [
                'label' => esc_html__( 'Upload Subtitle', 'baddon' ),
               
                'type' => Controls_Manager::MEDIA,
                'dynamic' => [
                    'active' => true,
                    'categories' => [
                        TagsModule::MEDIA_CATEGORY,
                    ],
                ],
                'media_type' => 'vtt',
                'condition' => [
                    'src_typed'    =>  'uploadds',
                ]
            ]
        );
        $repeaterg->add_control(
            'subtitle_link',
            [
                'label' => esc_html__( 'Subtitle Link', 'baddon' ),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'https://your-link.com', 'baddon' ),
                'condition' => [
                    'src_typed'    =>  'linkks',
                ]
            ]
        );
        $repeaterg->add_control(
            'subtitle_ssize',
            [
                'label' => esc_html__( 'Subtitle language', 'baddon' ),
                'type' => Controls_Manager::TEXT,
                'description' => __( 'Eg: English, For english subtitle write English', 'baddon' ),
           
            ]
        );
        $this->add_control(
            'subtitle_list',
            [
                'label' => esc_html__( 'Subtitle List', 'baddon' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeaterg->get_controls(),
           
            ]
        );
    $this->end_controls_section();

   //Vedio player Color Settings
		$this->start_controls_section(
            'section_ttabb', [
                'label' =>esc_html__( 'Vedio Player Color Settings', 'baddon' ),
            ]
        );


        $this->add_control(
            'important_color',
            [
                'label' => __( 'This Section Only For Pro Version', 'baddon' ),
                'label_block' => true,
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __( '<div class="pro_ads_img">
               <img src="'.plugins_url('assets/sc/color.png', dirname(__FILE__)).'" />
               </div>', 'baddon' ),
           
            ]
        );
  
    $this->end_controls_section();

    //Vedio player Control Settings
        $this->start_controls_section(
            'section_tabb', [
                'label' =>esc_html__( 'Vedio Player Control Settings', 'baddon' ),
            ]
        );

        $this->add_control(
            'important_note',
            [
                'label' => __( 'This Option Only For Pro Version', 'baddon' ),
                'label_block' => true,
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __( '<div class="pro_ads_img">
               <img src="'.plugins_url('assets/sc/h5vp-controls.png', dirname(__FILE__)).'" />
               </div>', 'baddon' ),
               
            ]
        );



 $this->end_controls_section();
//Others settings

    	$this->start_controls_section(
        'se_ttabb', [
            'label' =>esc_html__( 'Vedio Player Other Settings', 'baddon' ),
        ]
        );


        $this->add_control(
        'important_size',
        [
            'label' => __( 'This Option Only For Pro Version', 'baddon' ),
            'label_block' => true,
            'type' => Controls_Manager::RAW_HTML,
            'raw' => __( '<div class="pro_ads_img">
               <img src="'.plugins_url('assets/sc/control_size.png', dirname(__FILE__)).'" />
               </div>', 'baddon' ),
           
        ]
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

		 $video_list = $settings['video_list'];
         $multiple_quality = $settings['choose_source'];
         $subtitle_list = $settings['subtitle_list'];
         $poster    = $settings['poster'];

	     $controls = [];
	
	?>
	  <?php
          $video_link = '';   
          $videoos_link = '';
          $subtitle_link = '';
          //single vedio
            if($settings['srrc_type'] == 'uploaad'){
            $videoos_link = $settings['videoos_upload']['url'];
        } else {
            $videoos_link = $settings['videoos_link'];
        }
          $extensionn = $ext = pathinfo($videoos_link, PATHINFO_EXTENSION);   
       ?>

     <video class="b_addon_player" data-settings='<?php echo wp_json_encode($controls)?>'playsinline controls data-poster="<?php echo !empty($poster['url']) ? esc_url($poster['url']): '';?>">

        <?php if($multiple_quality !== 'yes'): ?>
        <source src="<?php echo esc_url($videoos_link); ?>" type="video/<?php echo esc_attr($extensionn); ?>"/>
	  	<?php else: 
	  		foreach($video_list as $item):
                if($item['src_type'] == 'upload'){
                    $video_link = $item['video_upload']['url'];
                    $video_link = $video_link;
                    $video_size = $item['video_size'];
                } else {
                    $video_link = $item['video_link'];
                    $video_link = $video_link;
                    $video_size = $item['video_size'];
                }
            $extension = $ext = pathinfo($video_link, PATHINFO_EXTENSION);
	  	 ?>
	  	 <source src="<?php echo esc_url($video_link); ?>" type="video/<?php echo esc_attr($extension); ?>" size="<?php echo esc_attr($video_size); ?>"/>
	  <?php endforeach; endif; ?>

      <?php foreach($subtitle_list as $item):
          if($item['src_typed'] == 'uploadds'){
                    $subtitle_link = $item['subtitle_upload']['url'];
                    $subtitle_link = $subtitle_link;
                    $subtitle_ssize = $item['subtitle_ssize'];
                } else {
                    $subtitle_link = $item['subtitle_link'];
                    $subtitle_link = $subtitle_link;
                    $subtitle_ssize = $item['subtitle_ssize'];
                }
                 $extensionds = $ext = pathinfo($subtitle_link, PATHINFO_EXTENSION);
        ?>
	  <track kind="captions" label="<?php echo esc_attr($subtitle_ssize); ?>" kind="subtitles/<?php echo esc_attr($extension); ?>" src="<?php echo esc_url($subtitle_link);?>" srclang="<?php echo esc_attr($subtitle_ssize); ?>" default />
     <?php endforeach; ?>
	</video>
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
	// protected function _content_template() {}
	
}
