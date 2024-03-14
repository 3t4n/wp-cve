<?php
// Exit if accessed directly.
if(!defined('ABSPATH')) exit;

if(!class_exists('RTCORE_Elementor_Testimonial')):

/**
 * Elementor Dynamic Testimonial Widget.
 * Elementor widget that shows Testimonials 
 * @since 1.0.0
 */
class RTCORE_Elementor_Testimonial extends \Elementor\Widget_Base
{
    /**
     * Get widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'testimonial_list';
    }

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Dynamic Testimonials', 'realtyna-core');
    }

    /**
     * Get widget icon.
     *
     * Retrieve oEmbed widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-testimonial-list';
    }

    /**
     * Get widget categories.
     *
     * @since 1.0.0
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return array('general');
    }

    /**
     * Register oEmbed widget controls.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'realtyna-core'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'description_limit',
            [
                'label' => __('Description Limit Charachters', 'realtyna-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '200',
                'title' => esc_html__('Enter some text', 'realtyna-core'),
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => __('Number of posts per page', 'realtyna-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 4,
                'options' => [
                    1 => __('One', 'realtyna-core'),
                    2 => __('Two', 'realtyna-core'),
                    3 => __('Three', 'realtyna-core'),
                    4 => __('Four', 'realtyna-core'),
                    6 => __('Six', 'realtyna-core'),
                ]
            ]
        );

        $this->add_control(
            'posts_count',
            [
                'label' => __('Number of posts', 'realtyna-core'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 3,
            ]
        );

        $this->add_control(
            'show_dots',
            [
                'label' => __('Show dot navigators', 'realtyna-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 1,
                'options' => [
                    1 => __('Yes', 'realtyna-core'),
                    0 => __('No', 'realtyna-core'),
                ]
            ]
        );

        $this->add_control(
            'show_arrows',
            [
                'label' => __('Show arrow navigators', 'realtyna-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 1,
                'options' => [
                    1 => __('Yes', 'realtyna-core'),
                    0 => __('No', 'realtyna-core'),
                ]
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render oEmbed widget output on the frontend.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $posts_count = $settings['posts_count'];
        $posts_per_page = $settings['posts_per_page'];
        $description_limit = $settings['description_limit'];
        $show_dots = ($settings['show_dots']) ? 'true' : 'false';
        $show_arrows = ($settings['show_arrows']) ? 'true' : 'false';

        global $post;

        $args = array(
            'post__not_in' => array($post->ID),
            'posts_per_page' => $posts_count, 
            'post_type' => 'rtcore-testimonial',
        );

        $query = new wp_query($args);
        if($query->have_posts())
        {
            ?>
            <div class="re-testimonial re-carousel" data-slick='{"slidesToShow": <?php echo esc_attr($posts_per_page); ?>, "slidesToScroll": 1 , "dots": <?php echo esc_attr($show_dots); ?>, "arrows": <?php echo esc_attr($show_arrows); ?>}'>
            <?php
            while($query->have_posts())
            {
                $query->the_post();
                ?>
                    <div class="re-carousel-items">
                        <div class="re-testimonial-container clearfix">
                            <div class="re-testimonial-thumb">
                                <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr(get_the_title()); ?>"><?php the_post_thumbnail(); ?></a>
                            </div>
                            <div class="re-content">
                                <?php
                                    $content = get_the_content();
                                    $content_first_part = substr($content , 0 ,$description_limit);
                                    $content_second_part = substr($content , $description_limit ,strlen($content));
                                ?>
                                <div class="re-testimonial-content">
                                    <span class="first-part"><?php echo esc_html($content_first_part); ?></span>
                                    <span class="second-part"><?php echo esc_html($content_second_part); ?></span>
                                    <a href="#" class="read-more"><?php echo esc_html__('Read More','realtyna-core')  ?></a>
                                </div>
                                <span class="re-client-name" rel="bookmark" title="<?php echo esc_attr(get_the_title()); ?>"><?php echo esc_html(get_the_title()); ?></span>
                            </div>
                        </div>
                    </div>
                <?php
            }

            echo '</div></div>';
        }
        
        wp_reset_query();
    }

    protected function _content_template()
    {
        ?>
        <?php
    }
}

endif;