<?php
/**
 * Class: LaStudioKit_Twitter
 * Name: Twitter Feed
 * Slug: lakit-twitter
 */

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
  exit;
} // Exit if accessed directly

class LaStudioKit_Twitter extends LaStudioKit_Base {

  protected function enqueue_addon_resources() {
    if ( ! lastudio_kit_settings()->is_combine_js_css() ) {
      wp_register_script( $this->get_name(), lastudio_kit()->plugin_url( 'assets/js/addons/twitter.js' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version(), true );
      $this->add_script_depends( $this->get_name() );
      if ( ! lastudio_kit()->is_optimized_css_mode() ) {
        wp_register_style( $this->get_name(), lastudio_kit()->plugin_url( 'assets/css/addons/twitter.min.css' ), [ 'lastudio-kit-base' ], lastudio_kit()->get_version() );
        $this->add_style_depends( $this->get_name() );
      }
    } else {
      wp_register_script( $this->get_name(), lastudio_kit()->plugin_url( 'assets/js/addons/twitter.js' ), [], lastudio_kit()->get_version(), true );
      $this->add_script_depends( $this->get_name() );
    }
  }

  public function get_widget_css_config( $widget_name ) {
    $file_url  = lastudio_kit()->plugin_url( 'assets/css/addons/twitter.min.css' );
    $file_path = lastudio_kit()->plugin_path( 'assets/css/addons/twitter.min.css' );

    return [
      'key'       => $widget_name,
      'version'   => lastudio_kit()->get_version( true ),
      'file_path' => $file_path,
      'data'      => [
        'file_url' => $file_url
      ]
    ];
  }

  public function get_name() {
    return 'lakit-twitter';
  }

  public function get_widget_title() {
    return esc_html__( 'Twitter Feed', 'lastudio-kit' );
  }

  public function get_icon() {
    return 'lastudio-kit-icon-twitter';
  }


  protected function register_controls() {

    $css_scheme = apply_filters(
      'lastudio-kit/twitter/css-schema',
      array(
        'wrap_outer' => '.lakit-twitter-feed',
        'wrap'       => '.lakit-twitter-feed .lakit-twitter_feed__wrapper',
        'column'     => '.lakit-twitter-feed .lakit-twitter_feed__item',
        'inner-box'  => '.lakit-twitter-feed .lakit-twitter_feed__item_inner',
        'content'    => '.lakit-twitter-feed .lakit-twitter_feed__content',
        'author'     => '.lakit-twitter-feed .lakit-twitter_feed__author',
        'link'       => '.lakit-twitter-feed .lakit-twitter_feed__links',
        'logo'       => '.lakit-twitter-feed .lakit-twitter_feed__logo',
        'action'     => '.lakit-twitter-feed .lakit-twitter_feed__interact',
      )
    );

    $this->_start_controls_section(
      'section_content',
      array(
        'label' => esc_html__( 'Setting', 'lastudio-kit' ),
      )
    );

    $this->_add_control(
      'screen_name',
      array(
        'label' => esc_html__( 'Screen Name', 'lastudio-kit' ),
        'type'  => Controls_Manager::TEXT,
      )
    );

    $this->_add_responsive_control(
      'columns',
      array(
        'label'   => esc_html__( 'Columns', 'lastudio-kit' ),
        'type'    => Controls_Manager::SELECT,
        'default' => 1,
        'options' => lastudio_kit_helper()->get_select_range( 6 )
      )
    );

    $this->_add_control(
      'limit',
      array(
        'label'   => __( 'Limit', 'lastudio-kit' ),
        'type'    => Controls_Manager::NUMBER,
        'default' => 1,
      )
    );

    $this->_add_control(
      'show_twitter_icon',
      array(
        'label'        => esc_html__( 'Show Twitter Icon', 'lastudio-kit' ),
        'type'         => Controls_Manager::SWITCHER,
        'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
        'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
        'return_value' => 'yes',
        'default'      => '',
      )
    );
    $this->_add_control(
      'show_author_box',
      array(
        'label'        => esc_html__( 'Show Author Box', 'lastudio-kit' ),
        'type'         => Controls_Manager::SWITCHER,
        'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
        'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
        'return_value' => 'yes',
        'default'      => '',
      )
    );
    $this->_add_control(
      'show_posted_date',
      array(
        'label'        => esc_html__( 'Show Posting Date', 'lastudio-kit' ),
        'type'         => Controls_Manager::SWITCHER,
        'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
        'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
        'return_value' => 'yes',
        'default'      => 'yes',
      )
    );
    $this->_add_control(
      'show_action',
      array(
        'label'        => esc_html__( 'Show Action', 'lastudio-kit' ),
        'type'         => Controls_Manager::SWITCHER,
        'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
        'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
        'return_value' => 'yes',
        'default'      => '',
      )
    );
    $this->_add_control(
      'show_link',
      array(
        'label'        => esc_html__( 'Show Link in Content', 'lastudio-kit' ),
        'type'         => Controls_Manager::SWITCHER,
        'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
        'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
        'return_value' => 'yes',
        'default'      => '',
      )
    );

    $this->_end_controls_section();

    $this->register_carousel_section( [], 'columns' );

    /** Style section */
    $this->_start_controls_section(
      'section_column_style',
      array(
        'label' => esc_html__( 'Column', 'lastudio-kit' ),
        'tab'   => Controls_Manager::TAB_STYLE,
      )
    );
    $this->_add_responsive_control(
      'column_padding',
      array(
        'label'       => esc_html__( 'Column Padding', 'lastudio-kit' ),
        'type'        => Controls_Manager::DIMENSIONS,
        'size_units'  => array( 'px' ),
        'render_type' => 'template',
        'selectors'   => array(
          '{{WRAPPER}} ' . $css_scheme['column'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
          '{{WRAPPER}} '                         => '--lakit-carousel-item-top-space: {{TOP}}{{UNIT}}; --lakit-carousel-item-right-space: {{RIGHT}}{{UNIT}};--lakit-carousel-item-bottom-space: {{BOTTOM}}{{UNIT}};--lakit-carousel-item-left-space: {{LEFT}}{{UNIT}};--lakit-gcol-top-space: {{TOP}}{{UNIT}}; --lakit-gcol-right-space: {{RIGHT}}{{UNIT}};--lakit-gcol-bottom-space: {{BOTTOM}}{{UNIT}};--lakit-gcol-left-space: {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->_end_controls_section();

    $this->_start_controls_section(
      'section_box_style',
      array(
        'label' => esc_html__( 'Box', 'lastudio-kit' ),
        'tab'   => Controls_Manager::TAB_STYLE,
      )
    );

    $this->_add_control(
      'box_bg',
      array(
        'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} ' . $css_scheme['inner-box'] => 'background-color: {{VALUE}}',
        ),
      )
    );
    $this->_add_responsive_control(
      'box_padding',
      array(
        'label'      => esc_html__( 'Box Padding', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px' ),
        'selectors'  => array(
          '{{WRAPPER}} ' . $css_scheme['inner-box'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->_add_responsive_control(
      'box_margin',
      array(
        'label'      => esc_html__( 'Box Margin', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px' ),
        'selectors'  => array(
          '{{WRAPPER}} ' . $css_scheme['inner-box'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );

    $this->_end_controls_section();

    $this->_start_controls_section(
      'section_content_style',
      array(
        'label' => esc_html__( 'Content', 'lastudio-kit' ),
        'tab'   => Controls_Manager::TAB_STYLE
      )
    );

    $this->_add_group_control(
      Group_Control_Typography::get_type(),
      array(
        'name'     => 'content_typography',
        'selector' => '{{WRAPPER}} ' . $css_scheme['content'],
      )
    );

    $this->_add_control(
      'content_color',
      array(
        'label'     => esc_html__( 'Text Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} ' . $css_scheme['wrap_outer'] => '--lakit-twitter-feed-text: {{VALUE}}',
        ),
      )
    );

    $this->_add_group_control(
      Group_Control_Typography::get_type(),
      array(
        'name'     => 'content_link_typography',
        'selector' => '{{WRAPPER}} ' . $css_scheme['content'] . ' a',
      )
    );

    $this->_add_control(
      'link_color',
      array(
        'label'     => esc_html__( 'Link Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} ' . $css_scheme['wrap_outer'] => '--lakit-twitter-feed-linkcolor: {{VALUE}}',
        ),
      )
    );

    $this->_add_control(
      'link_hover_color',
      array(
        'label'     => esc_html__( 'Link Hover Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} ' . $css_scheme['wrap_outer'] => '--lakit-twitter-feed-linkhovercolor: {{VALUE}}',
        ),
      )
    );

    $this->_add_responsive_control(
      'content_margin',
      array(
        'label'      => esc_html__( 'Content Margin', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px' ),
        'selectors'  => array(
          '{{WRAPPER}} ' . $css_scheme['content'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );

    $this->_end_controls_section();

    $this->_start_controls_section(
      'section_author_style',
      array(
        'label' => esc_html__( 'Author', 'lastudio-kit' ),
        'tab'   => Controls_Manager::TAB_STYLE
      )
    );
    $this->_add_responsive_control(
      'avatar_size',
      array(
        'label'      => esc_html__( 'Avatar Size', 'lastudio-kit' ),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => array( 'px' ),
        'selectors'  => array(
          '{{WRAPPER}} .lakit-twitter_feed__author .TweetAuthor-avatar' => 'width: {{SIZE}}{{UNIT}};',
        ),
      )
    );
    $this->_add_responsive_control(
      'avatar_spacing',
      array(
        'label'      => esc_html__( 'Avatar Spacing', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px' ),
        'selectors'  => array(
          '{{WRAPPER}} .lakit-twitter_feed__author .TweetAuthor-avatar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->_add_group_control(
      Group_Control_Typography::get_type(),
      array(
        'name'     => 'author_typography',
        'selector' => '{{WRAPPER}} ' . $css_scheme['author'] . ' .TweetAuthor-decoratedName',
      )
    );
    $this->_add_control(
      'author_color',
      array(
        'label'     => esc_html__( 'Author Text Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} ' . $css_scheme['wrap_outer'] => '--lakit-twitter-feed-authorname: {{VALUE}}',
        ),
      )
    );
    $this->_add_responsive_control(
      'author_margin',
      array(
        'label'      => esc_html__( 'Author Spacing', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px' ),
        'selectors'  => array(
          '{{WRAPPER}} ' . $css_scheme['content'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );

    $this->_add_group_control(
      Group_Control_Typography::get_type(),
      array(
        'name'     => 'screenname_typography',
        'selector' => '{{WRAPPER}} ' . $css_scheme['author'] . ' .TweetAuthor-screenName',
      )
    );
    $this->_add_control(
      'screenname_color',
      array(
        'label'     => esc_html__( 'ScreenName Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} ' . $css_scheme['wrap_outer'] => '--lakit-twitter-feed-screename: {{VALUE}}',
        ),
      )
    );

    $this->_add_responsive_control(
      'authorbox_margin',
      array(
        'label'      => esc_html__( 'Author Box Margin', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px' ),
        'selectors'  => array(
          '{{WRAPPER}} ' . $css_scheme['author'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );

    $this->_end_controls_section();

    $this->_start_controls_section(
      'section_interact_style',
      array(
        'label' => esc_html__( 'Interact/Action', 'lastudio-kit' ),
        'tab'   => Controls_Manager::TAB_STYLE
      )
    );
    $this->_add_responsive_control(
      'logo_size',
      array(
        'label'      => esc_html__( 'Logo Size', 'lastudio-kit' ),
        'type'       => Controls_Manager::SLIDER,
        'size_units' => array( 'px' ),
        'selectors'  => array(
          '{{WRAPPER}} .lakit-twitter_feed__logo' => 'font-size: {{SIZE}}{{UNIT}};',
        ),
      )
    );
    $this->_add_control(
      'logo_color',
      array(
        'label'     => esc_html__( 'Logo Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} .lakit-twitter_feed__logo' => 'color: {{VALUE}}',
        ),
      )
    );
    $this->_add_responsive_control(
      'logo_margin',
      array(
        'label'      => esc_html__( 'Logo Spacing', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px' ),
        'selectors'  => array(
          '{{WRAPPER}} .lakit-twitter_feed__logo' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->_add_group_control(
      Group_Control_Typography::get_type(),
      array(
        'name'     => 'time_typography',
        'selector' => '{{WRAPPER}} .lakit-twitter_feed__links a',
      )
    );
    $this->_add_control(
      'time_color',
      array(
        'label'     => esc_html__( 'Posting Time Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} ' . $css_scheme['wrap_outer'] => '--lakit-twitter-feed-posted: {{VALUE}}',
        ),
      )
    );
    $this->_add_group_control(
      Group_Control_Typography::get_type(),
      array(
        'name'     => 'action_typography',
        'selector' => '{{WRAPPER}} ' . $css_scheme['action'] . ' a',
      )
    );
    $this->_add_control(
      'action_color',
      array(
        'label'     => esc_html__( 'Action Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} ' . $css_scheme['wrap_outer'] => '--lakit-twitter-feed-action: {{VALUE}}',
        ),
      )
    );
    $this->_add_control(
      'action_hover_color',
      array(
        'label'     => esc_html__( 'Action Hover Color', 'lastudio-kit' ),
        'type'      => Controls_Manager::COLOR,
        'selectors' => array(
          '{{WRAPPER}} ' . $css_scheme['wrap_outer'] => '--lakit-twitter-feed-actionhover: {{VALUE}}',
        ),
      )
    );
    $this->_add_responsive_control(
      'action_margin',
      array(
        'label'      => esc_html__( 'Action Spacing', 'lastudio-kit' ),
        'type'       => Controls_Manager::DIMENSIONS,
        'size_units' => array( 'px' ),
        'selectors'  => array(
          '{{WRAPPER}} ' . $css_scheme['action'] . ' a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ),
      )
    );
    $this->_end_controls_section();

    $this->register_carousel_arrows_dots_style_section( [ 'enable_carousel' => 'yes' ] );
  }

  protected function render() {

    $this->add_render_attribute( 'main-container', 'class', 'lakit-twitter-feed' );
    $this->add_render_attribute( 'list-wrapper', 'class', 'lakit-twitter_feed__wrapper' );
    $this->add_render_attribute( 'list-container', 'class', 'lakit-twitter_feed__list' );

    $post_classes = [ 'lakit-twitter_feed__item' ];

    $feed_config = [
      'screen_name'       => $this->get_settings_for_display( 'screen_name' ),
      'limit'             => $this->get_settings_for_display( 'limit' ),
      'item_class'        => '',
      'show_twitter_icon' => $this->get_settings_for_display( 'show_twitter_icon' ),
      'show_author_box'   => $this->get_settings_for_display( 'show_author_box' ),
      'show_posted_date'  => $this->get_settings_for_display( 'show_posted_date' ),
      'show_action'       => $this->get_settings_for_display( 'show_action' ),
      'show_link'         => $this->get_settings_for_display( 'show_link' ),
      'uniqueid'          => 'twitter_feed_' . $this->get_id()
    ];

    $is_carousel = false;

    if ( filter_var( $this->get_settings_for_display( 'enable_carousel' ), FILTER_VALIDATE_BOOLEAN ) ) {
      $slider_options = $this->get_advanced_carousel_options( 'columns' );
      if ( ! empty( $slider_options ) ) {

        $is_carousel = true;

        $this->add_render_attribute( 'main-container', 'data-slider_options', json_encode( $slider_options ) );
        $this->add_render_attribute( 'main-container', 'dir', is_rtl() ? 'rtl' : 'ltr' );
        $this->add_render_attribute( 'list-wrapper', 'class', 'swiper-container' );
        $this->add_render_attribute( 'list-container', 'class', 'swiper-wrapper' );
        $this->add_render_attribute( 'main-container', 'class', 'lakit-carousel' );
        $carousel_id = $this->get_settings_for_display( 'carousel_id' );
        if ( empty( $carousel_id ) ) {
          $carousel_id = 'lakit_carousel_' . $this->get_id();
        }
        $this->add_render_attribute( 'list-wrapper', 'id', $carousel_id );

        $post_classes[] = 'swiper-slide';
      }
    } else {
      $this->add_render_attribute( 'list-container', 'class', 'col-row' );
      $post_classes[] = lastudio_kit_helper()->col_classes( [
        'desk' => $this->get_settings_for_display( 'columns' ),
        'tab'  => $this->get_settings_for_display( 'columns_tablet' ),
        'mob'  => $this->get_settings_for_display( 'columns_mobile' ),
      ] );
    }

    $this->add_render_attribute( 'list-container', 'id', 'twitter_feed_' . $this->get_id() );

    $feed_config['item_class'] = join( ' ', $post_classes );

    $this->add_render_attribute( 'main-container', 'data-feed_config', json_encode( $feed_config ) );

    ?>
    <div <?php echo $this->get_render_attribute_string( 'main-container' ); ?>>
      <?php
      if ( $is_carousel ) {
        echo '<div class="lakit-carousel-inner">';
      }
      ?>
      <div <?php echo $this->get_render_attribute_string( 'list-wrapper' ); ?>>
        <div <?php echo $this->get_render_attribute_string( 'list-container' ); ?>>
          <div class="loading"><?php esc_html_e( 'Loading...', 'lastudio-kit' ); ?></div>
        </div>
      </div>
      <?php
      if ( $is_carousel ) {
        echo '</div>';
        if ( filter_var( $this->get_settings_for_display( 'carousel_dots' ), FILTER_VALIDATE_BOOLEAN ) ) {
          echo '<div class="lakit-carousel__dots lakit-carousel__dots_' . $this->get_id() . ' swiper-pagination"></div>';
        }
        if ( filter_var( $this->get_settings_for_display( 'carousel_arrows' ), FILTER_VALIDATE_BOOLEAN ) ) {
          echo sprintf( '<div class="lakit-carousel__prev-arrow-%s lakit-arrow prev-arrow">%s</div>', $this->get_id(), $this->_render_icon( 'carousel_prev_arrow', '%s', '', false ) );
          echo sprintf( '<div class="lakit-carousel__next-arrow-%s lakit-arrow next-arrow">%s</div>', $this->get_id(), $this->_render_icon( 'carousel_next_arrow', '%s', '', false ) );
        }
        if ( filter_var( $this->get_settings_for_display( 'carousel_scrollbar' ), FILTER_VALIDATE_BOOLEAN ) ) {
	        echo sprintf('<div class="lakit-carousel__scrollbar swiper-scrollbar lakit-carousel__scrollbar_%1$s"></div>', $this->get_id());
        }
      }
      ?>
    </div>
    <?php
  }

}
