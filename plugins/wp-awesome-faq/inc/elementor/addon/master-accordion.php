<?php
	namespace MasterAccordion\Addon;

	// Elementor Classes
	use \Elementor\Widget_Base;
	use \Elementor\Utils;
	use \Elementor\Icons_Manager;
	use \Elementor\Controls_Manager;
	use \Elementor\Repeater;
	use \Elementor\Group_Control_Border;
	use \Elementor\Group_Control_Typography;
	use \Elementor\Scheme_Typography;
	use \Elementor\Group_Control_Image_Size;
    use \Elementor\Group_Control_Background;    
	use \Elementor\Group_Control_Box_Shadow;
	use \Elementor\Group_Control_Css_Filter;
	use \Elementor\Scheme_Color;

	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 10/06/2020
	 * @package Master Addons Accordion
	 */

	// Exit if accessed directly.
	if ( ! defined( 'ABSPATH' ) ) { exit; }
    
     class Master_Accordion extends Widget_Base {
		public function get_name() {
			return 'jltmaf-accordion';
		}

		public function get_title() {
			return __( 'Master Accordion', MAF_TD );
		}

		public function get_categories() {
			return [ 'general', 'master-addons' ];
		}

		public function get_icon() {
			return 'jltmaf-icon eicon-accordion';
		}

		public function get_keywords() {
			return [ 'faq', 'awesome faq', 'accordion', 'frequently ask questions', 'general', 'questions', 'support'];
		}


        protected function _register_controls() {
            
			$this->start_controls_section(
				'jltmaf_faq_content_section',
				[
					'label' => __( 'Content', MAF_TD ),
				]
            );


            $this->add_control(
                'jltmaf_faq_cats',
                [
                    'label'                 => esc_html__( 'Category', MAF_TD ),
                    'type'                  => Controls_Manager::SELECT,
                    'label_block'           => false,
                    'options'               => $this->jltmaf_cats(),
                    'default'               => '',
                ]
            );

            $this->add_control(
                'jltmaf_faq_tags',
                [
                    'label'                 => esc_html__( 'Tags', MAF_TD ),
                    'type'                  => Controls_Manager::SELECT,
                    'label_block'           => false,
                    'options'               => $this->jltmaf_tags(),
                    'default'               => '',
                ]
            );

    
            $this->add_control(
                'jltmaf_faq_items',
                [
                    'label'                 => esc_html__( 'No. of Items', MAF_TD ),
                    'type'                  => Controls_Manager::NUMBER,
                    'label_block'           => false,
                    'default'               => -1,
                    'frontend_available'    => true,
                    'description'           => esc_html__( 'Define no. of posts. "-1" for all posts.', MAF_TD ),
                ]
            );

            $this->add_control(
                'jltmaf_faq_order',
                [
                    'label'                 => esc_html__( 'Order', MAF_TD ),
                    'type'                  => Controls_Manager::SELECT,
                    'label_block'           => false,
                    'default'               => 'DESC',
                    'options'               => [
                        'DESC'      => esc_html__('Descending', MAF_TD ),
                        'ASC'       => esc_html__('Ascending', MAF_TD )
                    ],
                ]
            );

            $this->end_controls_section();

        }


        protected function jltmaf_tags(){
            $cats = array('' => __('All Tags', MAF_TD));
            foreach(get_terms('faq_tags', 'orderby=count&hide_empty=0') as $term ){
                $tags[$term->slug] = $term->name;
            }
            return $cats;
        }


        protected function jltmaf_cats(){
            $cats = array('' => __('All Categories', MAF_TD));
            foreach(get_terms('faq_cat', 'orderby=count&hide_empty=0&post_type=faq') as $term ){
                $cats[$term->slug] = $term->name;
            }
            return $cats;
        }


		protected function render() {

            $settings       = $this->get_settings_for_display();

            $jltmaf_cats    = $settings['jltmaf_faq_cats'];
            $jltmaf_tags    = $settings['jltmaf_faq_tags'];
            $jltmaf_items   = $settings['jltmaf_faq_items'];
            $jltmaf_order   = $settings['jltmaf_faq_order'];


            echo do_shortcode('[faq cat="'. $jltmaf_cats .'" tag="'. $jltmaf_tags .'" order="'. $jltmaf_order .'" items="'. $jltmaf_items .'"]');
        }


    }
