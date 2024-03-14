<?php
// Exit if accessed directly.
if(!defined('ABSPATH')) exit;

if(!class_exists('RTCORE_Elementor_Post')):

/**
 * Elementor Post Widget.
 * @since 1.0.0
 */
class RTCORE_Elementor_Post extends \Elementor\Widget_Base
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
        return 'post_list';
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
        return __('Posts', 'realtyna-core');
    }

    /**
     * Get widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-post-list';
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
            'show_date',
            [
                'label' => __('Show Date', 'realtyna-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 1,
                'options' => [
                    1 => __('Yes', 'realtyna-core'),
                    0 => __('No', 'realtyna-core'),
                ]
            ]
        );

        $this->add_control(
            'show_description',
            [
                'label' => __('Show Description', 'realtyna-core'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 1,
                'options' => [
                    1 => __('Yes', 'realtyna-core'),
                    0 => __('No', 'realtyna-core'),
                ]
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
        $show_date = $settings['show_date'];
        $show_description = $settings['show_description'];
        $show_dots = ($settings['show_dots']) ? 'true' : 'false';
        $show_arrows = ($settings['show_arrows']) ? 'true' : 'false';

        global $post;

        $args = array(
            'post__not_in' => array($post->ID),
            'posts_per_page' => $posts_count, 
            'post_type'      => 'post',
        );

        $my_query = new wp_query($args);
        if($my_query->have_posts())
        {
            ?>
            <div class="re-recent-post-carousel re-carousel" data-slick='{"slidesToShow": <?php echo esc_attr($posts_per_page); ?>, "slidesToScroll": 1 , "dots": <?php echo esc_attr($show_dots); ?>, "arrows": <?php echo esc_attr($show_arrows); ?>}'>
            <?php    

            while($my_query->have_posts())
            {
                $my_query->the_post(); 
                ?>
                    <div class="re-carousel-items">
                        <div class="re-recent-post-thumb">
                            <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr(get_the_title()); ?>"><?php the_post_thumbnail(); ?></a>
                        </div>
                        <div class="content">
                            <h3 class="re-recent-post-title">
                                <a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php echo esc_attr(get_the_title()); ?>"><?php echo esc_html(get_the_title()); ?></a>
                            </h3>
                            <?php if($show_date): ?> <div class="re-recent-post-date"><?php echo esc_html(get_the_time('M j, Y')) ?></div> <?php endif; ?>
                            <?php if($show_description): ?><div class="re-recent-post-content"><?php the_excerpt(); ?></div><?php endif; ?>
                        </div>
                    </div>
                <?php
            }

            echo '</div>';
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